-- ============================================================================
-- Tender & Procurement module, with a multi-level approval workflow
-- Run once per database:
--   mysql -u root hospital < sql/procurement_tender.sql
--
-- WHERE THIS SITS
-- ---------------
-- The store module already covers: item master -> purchase order -> GRN ->
-- stock -> issue -> invoice -> payment. What it has no notion of is how a
-- purchase order comes to exist: there is no requisition, no competitive
-- bidding, and no approval on the PO at all — anyone with store access can
-- raise one for any amount.
--
-- This module supplies the front half of that pipeline:
--
--   requisition -> approval -> tender -> bids -> comparison -> award
--                                                     -> approval -> PO
--
-- and ends by writing an ordinary row into the existing
-- store_purchase_orders / store_purchase_order_items tables, so GRN, stock and
-- payment continue to work exactly as they do today. NO STORE TABLE IS ALTERED
-- AND NO STORE CODE IS TOUCHED — this module only reads store_items,
-- store_suppliers and sub_store, and inserts a finished PO.
--
-- Every statement is safe to re-run.
-- ============================================================================


-- ============================================================================
-- 1. Purchase requisition — a department states a need
-- ============================================================================
CREATE TABLE IF NOT EXISTS `procurement_requisitions` (
  `id`               INT(11)      NOT NULL AUTO_INCREMENT,
  `requisition_no`   VARCHAR(50)  NOT NULL COMMENT 'PR-YYYYMMDD-XXXX',
  `store_id`         INT(11)      NOT NULL DEFAULT 0 COMMENT 'sub_store.id — requesting store',
  `department`       VARCHAR(150) NOT NULL DEFAULT '',
  `required_by`      DATE         DEFAULT NULL,
  `priority`         ENUM('Normal','Urgent','Emergency') NOT NULL DEFAULT 'Normal',
  `justification`    TEXT         DEFAULT NULL COMMENT 'Why this purchase is needed',
  `estimated_amount` DECIMAL(14,2) NOT NULL DEFAULT 0.00,

  -- DRAFT is editable; everything from SUBMITTED on is locked to the raiser.
  `status`           ENUM('DRAFT','SUBMITTED','APPROVED','REJECTED','TENDERED','CLOSED','CANCELLED')
                     NOT NULL DEFAULT 'DRAFT',
  `current_level`    INT(11)      NOT NULL DEFAULT 0 COMMENT 'Approval level reached',
  `reject_reason`    VARCHAR(255) DEFAULT NULL,

  `created_by`       VARCHAR(150) NOT NULL,
  `created_at`       DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`       DATETIME     DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_requisition_no` (`requisition_no`),
  KEY `idx_status` (`status`),
  KEY `idx_created_at` (`created_at`),
  KEY `idx_store` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Purchase requisitions — the start of procurement';

CREATE TABLE IF NOT EXISTS `procurement_requisition_items` (
  `id`              INT(11)       NOT NULL AUTO_INCREMENT,
  `requisition_id`  INT(11)       NOT NULL,
  `item_id`         INT(11)       NOT NULL COMMENT 'store_items.id',
  `quantity`        DECIMAL(14,3) NOT NULL DEFAULT 0.000,
  `estimated_rate`  DECIMAL(14,2) NOT NULL DEFAULT 0.00,
  `estimated_value` DECIMAL(14,2) NOT NULL DEFAULT 0.00,
  `specification`   TEXT          DEFAULT NULL COMMENT 'Technical spec carried into the tender',
  `remarks`         VARCHAR(255)  DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_requisition_id` (`requisition_id`),
  KEY `idx_item_id` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================================
-- 2. Tender — competitive bidding for an approved requisition
-- ============================================================================
CREATE TABLE IF NOT EXISTS `tenders` (
  `id`               INT(11)      NOT NULL AUTO_INCREMENT,
  `tender_no`        VARCHAR(50)  NOT NULL COMMENT 'TND-YYYYMMDD-XXXX',
  `requisition_id`   INT(11)      NOT NULL DEFAULT 0 COMMENT '0 = raised without a requisition',
  `title`            VARCHAR(255) NOT NULL,
  `description`      TEXT         DEFAULT NULL,
  `tender_type`      ENUM('Open','Limited','Single','Rate_Contract') NOT NULL DEFAULT 'Open',

  `published_date`   DATE         DEFAULT NULL,
  `closing_date`     DATE         DEFAULT NULL COMMENT 'Last date to receive bids',
  `opening_date`     DATE         DEFAULT NULL COMMENT 'When bids are opened',

  `emd_amount`       DECIMAL(14,2) NOT NULL DEFAULT 0.00 COMMENT 'Earnest money deposit',
  `estimated_value`  DECIMAL(14,2) NOT NULL DEFAULT 0.00,
  `terms_conditions` TEXT          DEFAULT NULL,

  `status`           ENUM('DRAFT','PENDING_APPROVAL','PUBLISHED','BIDDING_CLOSED','UNDER_EVALUATION','AWARDED','CANCELLED')
                     NOT NULL DEFAULT 'DRAFT',
  `current_level`    INT(11)      NOT NULL DEFAULT 0,
  `cancel_reason`    VARCHAR(255) DEFAULT NULL,

  `created_by`       VARCHAR(150) NOT NULL,
  `created_at`       DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`       DATETIME     DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_tender_no` (`tender_no`),
  KEY `idx_status` (`status`),
  KEY `idx_requisition_id` (`requisition_id`),
  KEY `idx_closing_date` (`closing_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Tenders / RFQs';

CREATE TABLE IF NOT EXISTS `tender_items` (
  `id`             INT(11)       NOT NULL AUTO_INCREMENT,
  `tender_id`      INT(11)       NOT NULL,
  `item_id`        INT(11)       NOT NULL COMMENT 'store_items.id',
  `quantity`       DECIMAL(14,3) NOT NULL DEFAULT 0.000,
  `estimated_rate` DECIMAL(14,2) NOT NULL DEFAULT 0.00,
  `specification`  TEXT          DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_tender_id` (`tender_id`),
  KEY `idx_item_id` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================================
-- 3. Bids — one header per supplier, one row per item quoted
-- ============================================================================
CREATE TABLE IF NOT EXISTS `tender_bids` (
  `id`               INT(11)      NOT NULL AUTO_INCREMENT,
  `tender_id`        INT(11)      NOT NULL,
  `supplier_id`      INT(11)      NOT NULL COMMENT 'store_suppliers.id',
  `bid_reference`    VARCHAR(100) DEFAULT NULL COMMENT "Supplier's own quotation number",
  `bid_date`         DATE         DEFAULT NULL,
  `total_amount`     DECIMAL(14,2) NOT NULL DEFAULT 0.00,
  `delivery_days`    INT(11)      NOT NULL DEFAULT 0,
  `payment_terms`    VARCHAR(255) DEFAULT NULL,
  `warranty_terms`   VARCHAR(255) DEFAULT NULL,
  `emd_received`     TINYINT(1)   NOT NULL DEFAULT 0,

  -- A bid that fails the technical check is excluded from price comparison,
  -- which is the whole point of a two-envelope tender.
  `technical_status` ENUM('Pending','Qualified','Disqualified') NOT NULL DEFAULT 'Pending',
  `disqualify_reason` VARCHAR(255) DEFAULT NULL,
  `status`           ENUM('DRAFT','SUBMITTED','WITHDRAWN') NOT NULL DEFAULT 'SUBMITTED',
  `remarks`          TEXT         DEFAULT NULL,

  `created_by`       VARCHAR(150) NOT NULL,
  `created_at`       DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,

  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_tender_supplier` (`tender_id`,`supplier_id`),
  KEY `idx_tender_id` (`tender_id`),
  KEY `idx_supplier_id` (`supplier_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Supplier bids against a tender';

CREATE TABLE IF NOT EXISTS `tender_bid_items` (
  `id`             INT(11)       NOT NULL AUTO_INCREMENT,
  `bid_id`         INT(11)       NOT NULL,
  `tender_item_id` INT(11)       NOT NULL,
  `item_id`        INT(11)       NOT NULL,
  `rate`           DECIMAL(14,2) NOT NULL DEFAULT 0.00,
  `gst_percent`    DECIMAL(6,2)  NOT NULL DEFAULT 0.00,
  `net_rate`       DECIMAL(14,2) NOT NULL DEFAULT 0.00 COMMENT 'Rate incl. GST — what ranking compares',
  `amount`         DECIMAL(14,2) NOT NULL DEFAULT 0.00,
  `brand`          VARCHAR(150)  DEFAULT NULL,
  `remarks`        VARCHAR(255)  DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_bid_item` (`bid_id`,`tender_item_id`),
  KEY `idx_bid_id` (`bid_id`),
  KEY `idx_tender_item_id` (`tender_item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================================
-- 4. Award — the decision, and the PO it produced
-- ============================================================================
CREATE TABLE IF NOT EXISTS `tender_awards` (
  `id`              INT(11)      NOT NULL AUTO_INCREMENT,
  `award_no`        VARCHAR(50)  NOT NULL COMMENT 'AWD-YYYYMMDD-XXXX',
  `tender_id`       INT(11)      NOT NULL,
  `bid_id`          INT(11)      NOT NULL,
  `supplier_id`     INT(11)      NOT NULL,
  `award_amount`    DECIMAL(14,2) NOT NULL DEFAULT 0.00,

  -- Awarding anything other than the lowest qualified bid has to be defended;
  -- that reason is the audit trail a purchase committee is asked for.
  `is_lowest`       TINYINT(1)   NOT NULL DEFAULT 1,
  `deviation_reason` VARCHAR(500) DEFAULT NULL,

  `status`          ENUM('PENDING_APPROVAL','APPROVED','REJECTED','PO_GENERATED')
                    NOT NULL DEFAULT 'PENDING_APPROVAL',
  `current_level`   INT(11)      NOT NULL DEFAULT 0,
  `reject_reason`   VARCHAR(255) DEFAULT NULL,

  `po_id`           INT(11)      DEFAULT NULL COMMENT 'store_purchase_orders.id once generated',
  `po_number`       VARCHAR(50)  DEFAULT NULL,
  `po_generated_at` DATETIME     DEFAULT NULL,

  `created_by`      VARCHAR(150) NOT NULL,
  `created_at`      DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,

  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_award_no` (`award_no`),
  KEY `idx_tender_id` (`tender_id`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Tender awards and the purchase order each produced';


-- ============================================================================
-- 5. Approval engine
-- ----------------------------------------------------------------------------
-- One matrix row per (entity, level) says which role signs at that level and
-- for which amount band. A requisition for a few thousand rupees can clear with
-- one signature; a large one collects more. Levels are walked in order, and an
-- entity is only approved once every applicable level has signed.
-- ============================================================================
CREATE TABLE IF NOT EXISTS `procurement_approval_matrix` (
  `id`           INT(11)       NOT NULL AUTO_INCREMENT,
  `entity_type`  ENUM('REQUISITION','TENDER','AWARD') NOT NULL,
  `level`        INT(11)       NOT NULL COMMENT '1 = first signature',
  `approver_role` VARCHAR(50)  NOT NULL COMMENT 'Matches $_SESSION[role]',
  `min_amount`   DECIMAL(14,2) NOT NULL DEFAULT 0.00,
  `max_amount`   DECIMAL(14,2) DEFAULT NULL COMMENT 'NULL = no upper bound',
  `is_active`    TINYINT(1)    NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_entity_level` (`entity_type`,`level`),
  KEY `idx_entity_type` (`entity_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Who signs off what, by amount band';

-- Defaults: Store signs everything; Admin is pulled in above 50,000.
-- Adjust the bands to the hospital's delegation of authority.
INSERT IGNORE INTO `procurement_approval_matrix`
  (`entity_type`, `level`, `approver_role`, `min_amount`, `max_amount`) VALUES
  ('REQUISITION', 1, 'Store', 0.00,     NULL),
  ('REQUISITION', 2, 'Admin', 50000.00, NULL),
  ('TENDER',      1, 'Store', 0.00,     NULL),
  ('TENDER',      2, 'Admin', 50000.00, NULL),
  ('AWARD',       1, 'Store', 0.00,     NULL),
  ('AWARD',       2, 'Admin', 50000.00, NULL);

CREATE TABLE IF NOT EXISTS `procurement_approvals` (
  `id`          INT(11)      NOT NULL AUTO_INCREMENT,
  `entity_type` ENUM('REQUISITION','TENDER','AWARD') NOT NULL,
  `entity_id`   INT(11)      NOT NULL,
  `level`       INT(11)      NOT NULL,
  `approver_role` VARCHAR(50) NOT NULL COMMENT 'Role the matrix required',
  `action`      ENUM('APPROVED','REJECTED') NOT NULL,
  `acted_by`    VARCHAR(150) NOT NULL,
  `acted_role`  VARCHAR(50)  NOT NULL COMMENT 'Role the actor actually held',
  `acted_at`    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `remarks`     VARCHAR(500) DEFAULT NULL,
  PRIMARY KEY (`id`),
  -- One decision per level per entity; a re-submit clears the old rows first.
  UNIQUE KEY `uniq_entity_level` (`entity_type`,`entity_id`,`level`),
  KEY `idx_entity` (`entity_type`,`entity_id`),
  KEY `idx_acted_at` (`acted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Immutable record of every approval decision';
