-- ============================================================================
-- Blood Bank — Stages 3 to 5 (screening, component separation, issue)
-- Run once per database:
--   mysql -u root hospital < sql/blood_bank_flow.sql
--
-- Stages 1 and 2 already ship:
--   1. Donor lifecycle   db/blood_donor_schema.sql
--   2. Collection        BLOOD_COLLECTION_SCHEMA.sql
--
-- Collection leaves every unit at unit_status='Collected_Unscreened' with
-- is_usable=0 and is_available_for_issue=0, and BLOOD_COLLECTION_SCHEMA.sql
-- states the unit cannot be issued until "Testing completed (Stage 3)".
-- blood_units.testing_id already points at a `blood_unit_testing` table — which
-- was never created, so nothing could ever clear a unit for use and the flow
-- dead-ended after collection. This file supplies the missing stages.
--
-- Every statement is written to be safe to re-run.
-- ============================================================================

DROP PROCEDURE IF EXISTS `bb_add_column`;
DELIMITER //
CREATE PROCEDURE `bb_add_column`(IN tbl VARCHAR(64), IN col VARCHAR(64), IN spec TEXT)
BEGIN
  IF NOT EXISTS (
    SELECT 1 FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = tbl AND COLUMN_NAME = col
  ) THEN
    SET @ddl = CONCAT('ALTER TABLE `', tbl, '` ADD COLUMN `', col, '` ', spec);
    PREPARE stmt FROM @ddl; EXECUTE stmt; DEALLOCATE PREPARE stmt;
  END IF;
END //
DELIMITER ;

DROP PROCEDURE IF EXISTS `bb_add_index`;
DELIMITER //
CREATE PROCEDURE `bb_add_index`(IN tbl VARCHAR(64), IN idx VARCHAR(64), IN cols TEXT)
BEGIN
  IF NOT EXISTS (
    SELECT 1 FROM information_schema.STATISTICS
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = tbl AND INDEX_NAME = idx
  ) THEN
    SET @ddl = CONCAT('ALTER TABLE `', tbl, '` ADD INDEX `', idx, '` (', cols, ')');
    PREPARE stmt FROM @ddl; EXECUTE stmt; DEALLOCATE PREPARE stmt;
  END IF;
END //
DELIMITER ;


-- ============================================================================
-- REPAIR — columns BLOOD_COLLECTION_SCHEMA.sql intended to add but never did
-- ----------------------------------------------------------------------------
-- That file ends with:
--   ALTER TABLE `blood_donor_master`
--     ADD COLUMN IF NOT EXISTS `total_donations_lifetime` ...,
--     ADD COLUMN IF NOT EXISTS `next_eligible_donation_date` ...;
-- MySQL has no ADD COLUMN IF NOT EXISTS (MariaDB does), so on MySQL the whole
-- statement is a syntax error and neither column is created. Both are
-- load-bearing: processBloodCollection.php UPDATEs them after every collection,
-- and bloodDonorClass::checkDonorEligibility() reads next_eligible_donation_date
-- to enforce the cooling period. Without them a collection cannot record the
-- donation and the cooling period is never applied.
-- ============================================================================
CALL bb_add_column('blood_donor_master', 'total_donations_lifetime',    "INT(11) NOT NULL DEFAULT 0");
CALL bb_add_column('blood_donor_master', 'next_eligible_donation_date', "DATE DEFAULT NULL");


-- ============================================================================
-- STAGE 3 — Transfusion-transmissible infection (TTI) screening
-- ----------------------------------------------------------------------------
-- One row per unit tested. A unit is released only when all five mandatory TTI
-- markers are Non_Reactive AND the re-grouped ABO/Rh matches what was recorded
-- at collection. Any reactive marker fails the unit permanently.
-- ============================================================================
CREATE TABLE IF NOT EXISTS `blood_unit_testing` (
  `testing_id`            INT(11)      NOT NULL AUTO_INCREMENT,
  `unit_id`               INT(11)      NOT NULL,
  `unit_barcode`          VARCHAR(50)  NOT NULL,

  -- Mandatory TTI markers (NACO / NBTC screening panel)
  `hiv_result`            ENUM('Pending','Non_Reactive','Reactive') NOT NULL DEFAULT 'Pending',
  `hbsag_result`          ENUM('Pending','Non_Reactive','Reactive') NOT NULL DEFAULT 'Pending',
  `hcv_result`            ENUM('Pending','Non_Reactive','Reactive') NOT NULL DEFAULT 'Pending',
  `syphilis_result`       ENUM('Pending','Non_Reactive','Reactive') NOT NULL DEFAULT 'Pending',
  `malaria_result`        ENUM('Pending','Non_Reactive','Reactive') NOT NULL DEFAULT 'Pending',

  `test_method`           VARCHAR(50)  NOT NULL DEFAULT 'ELISA' COMMENT 'ELISA / Rapid / CLIA / NAT',
  `kit_lot_number`        VARCHAR(100) DEFAULT NULL,

  -- ABO/Rh re-grouping. A mismatch against the collection record is a hard fail:
  -- it means the bag and the donor record disagree and neither can be trusted.
  `abo_group_confirmed`   VARCHAR(10)  DEFAULT NULL,
  `rh_factor_confirmed`   ENUM('Positive','Negative') DEFAULT NULL,
  `grouping_matches`      TINYINT(1)   NOT NULL DEFAULT 1,

  `overall_result`        ENUM('Pending','Passed','Failed') NOT NULL DEFAULT 'Pending',
  `failure_reason`        VARCHAR(255) DEFAULT NULL,

  `tested_by`             VARCHAR(100) NOT NULL,
  `tested_date`           DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `approved_by`           VARCHAR(100) DEFAULT NULL,
  `approved_date`         DATETIME     DEFAULT NULL,
  `remarks`               TEXT         DEFAULT NULL,

  PRIMARY KEY (`testing_id`),
  UNIQUE KEY `uniq_unit` (`unit_id`),
  KEY `idx_overall_result` (`overall_result`),
  KEY `idx_tested_date` (`tested_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='TTI screening + ABO/Rh confirmation — Stage 3';


-- ============================================================================
-- STAGE 4 — Component separation
-- ----------------------------------------------------------------------------
-- A whole-blood unit is split into child units. The children are ordinary
-- blood_units rows carrying parent_unit_id, so stock, issue and traceability
-- all work on them unchanged; the parent moves to 'Separated' and leaves
-- inventory.
-- ============================================================================
CALL bb_add_column('blood_units', 'parent_unit_id', "INT(11) DEFAULT NULL COMMENT 'Set on components split from a whole-blood unit'");
CALL bb_add_column('blood_units', 'separated_date', "DATETIME DEFAULT NULL");
CALL bb_add_index('blood_units', 'idx_parent_unit_id', '`parent_unit_id`');

-- 'Separated' is not in the shipped ENUM; add it without disturbing the rest.
ALTER TABLE `blood_units`
  MODIFY COLUMN `unit_status` ENUM(
    'Collected_Unscreened','Under_Testing','Testing_Passed','Testing_Failed',
    'Quarantined','Released_To_Inventory','Reserved','Issued','Transfused',
    'Discarded','Expired','Separated'
  ) NOT NULL DEFAULT 'Collected_Unscreened';

CREATE TABLE IF NOT EXISTS `blood_component_separation` (
  `separation_id`     INT(11)      NOT NULL AUTO_INCREMENT,
  `parent_unit_id`    INT(11)      NOT NULL,
  `parent_barcode`    VARCHAR(50)  NOT NULL,
  `components_made`   VARCHAR(255) NOT NULL COMMENT 'Comma list of component types produced',
  `component_count`   INT(11)      NOT NULL DEFAULT 0,
  `separation_method` VARCHAR(50)  NOT NULL DEFAULT 'Centrifugation',
  `separated_by`      VARCHAR(100) NOT NULL,
  `separated_date`    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `remarks`           TEXT         DEFAULT NULL,
  PRIMARY KEY (`separation_id`),
  KEY `idx_parent_unit_id` (`parent_unit_id`),
  KEY `idx_separated_date` (`separated_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Component separation events — Stage 4';

-- Shelf life drives the child unit's expiry date. Kept as a table rather than
-- hardcoded so a blood bank can adjust to its own SOP without a code change.
CREATE TABLE IF NOT EXISTS `blood_component_shelf_life_ref` (
  `component_id`     INT(11)      NOT NULL AUTO_INCREMENT,
  `component_type`   VARCHAR(50)  NOT NULL,
  `shelf_life_days`  INT(11)      NOT NULL,
  `storage_temp`     VARCHAR(50)  NOT NULL,
  `typical_volume_ml` INT(11)     NOT NULL,
  `is_active`        TINYINT(1)   NOT NULL DEFAULT 1,
  PRIMARY KEY (`component_id`),
  UNIQUE KEY `uniq_component_type` (`component_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `blood_component_shelf_life_ref`
  (`component_type`, `shelf_life_days`, `storage_temp`, `typical_volume_ml`) VALUES
  ('Whole_Blood',      35,  '2°C to 6°C',    450),
  ('Packed_RBC',       42,  '2°C to 6°C',    280),
  ('Plasma_FFP',       365, '-30°C or below', 200),
  ('Platelets',        5,   '20°C to 24°C',  60),
  ('Cryoprecipitate',  365, '-30°C or below', 30);


-- ============================================================================
-- STAGE 5 — Request, crossmatch, issue
-- ----------------------------------------------------------------------------
-- A ward raises a request; units are crossmatched against the patient; a
-- compatible unit is issued and its outcome recorded. Units move
-- Released_To_Inventory -> Reserved -> Issued -> Transfused (or back to
-- inventory on return).
-- ============================================================================
CREATE TABLE IF NOT EXISTS `blood_requests` (
  `request_id`        INT(11)      NOT NULL AUTO_INCREMENT,
  `request_number`    VARCHAR(50)  NOT NULL COMMENT 'BR-YYYYMMDD-XXXX',

  -- Patient. Either an OPD or IPD number; both are kept as free text because a
  -- request may be raised for a referral who is not yet registered.
  `patient_type`      ENUM('OPD','IPD','External') NOT NULL DEFAULT 'IPD',
  `patient_no`        VARCHAR(50)  DEFAULT NULL,
  `patient_name`      VARCHAR(150) NOT NULL,
  `patient_age`       VARCHAR(20)  DEFAULT NULL,
  `patient_gender`    VARCHAR(10)  DEFAULT NULL,
  `ward`              VARCHAR(100) DEFAULT NULL,

  `blood_group`       VARCHAR(10)  NOT NULL,
  `component_type`    VARCHAR(50)  NOT NULL DEFAULT 'Whole_Blood',
  `units_required`    INT(11)      NOT NULL DEFAULT 1,
  `units_issued`      INT(11)      NOT NULL DEFAULT 0,

  `urgency`           ENUM('Routine','Urgent','Emergency') NOT NULL DEFAULT 'Routine',
  `indication`        VARCHAR(255) DEFAULT NULL COMMENT 'Clinical reason for transfusion',
  `requested_by`      VARCHAR(150) NOT NULL COMMENT 'Requesting doctor',
  `request_status`    ENUM('Pending','Crossmatching','Ready','Issued','Partially_Issued','Cancelled')
                      NOT NULL DEFAULT 'Pending',
  `cancel_reason`     VARCHAR(255) DEFAULT NULL,

  `created_by`        VARCHAR(100) NOT NULL,
  `created_date`      DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_date`     DATETIME     DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY (`request_id`),
  UNIQUE KEY `uniq_request_number` (`request_number`),
  KEY `idx_request_status` (`request_status`),
  KEY `idx_blood_group` (`blood_group`),
  KEY `idx_created_date` (`created_date`),
  KEY `idx_patient_no` (`patient_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Clinical blood requests — Stage 5';

CREATE TABLE IF NOT EXISTS `blood_crossmatch` (
  `crossmatch_id`     INT(11)      NOT NULL AUTO_INCREMENT,
  `request_id`        INT(11)      NOT NULL,
  `unit_id`           INT(11)      NOT NULL,
  `unit_barcode`      VARCHAR(50)  NOT NULL,
  `method`            ENUM('Saline','AHG_Coombs','Gel_Card','Electronic') NOT NULL DEFAULT 'AHG_Coombs',
  `result`            ENUM('Pending','Compatible','Incompatible') NOT NULL DEFAULT 'Pending',
  `incompatibility_note` VARCHAR(255) DEFAULT NULL,
  `performed_by`      VARCHAR(100) NOT NULL,
  `performed_date`    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`crossmatch_id`),
  KEY `idx_request_id` (`request_id`),
  KEY `idx_unit_id` (`unit_id`),
  KEY `idx_result` (`result`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Compatibility testing — Stage 5';

CREATE TABLE IF NOT EXISTS `blood_issues` (
  `issue_id`          INT(11)      NOT NULL AUTO_INCREMENT,
  `issue_number`      VARCHAR(50)  NOT NULL COMMENT 'BI-YYYYMMDD-XXXX',
  `request_id`        INT(11)      NOT NULL,
  `unit_id`           INT(11)      NOT NULL,
  `unit_barcode`      VARCHAR(50)  NOT NULL,
  `crossmatch_id`     INT(11)      DEFAULT NULL,

  `patient_no`        VARCHAR(50)  DEFAULT NULL,
  `patient_name`      VARCHAR(150) NOT NULL,
  `blood_group`       VARCHAR(10)  NOT NULL,
  `component_type`    VARCHAR(50)  NOT NULL,

  `issued_by`         VARCHAR(100) NOT NULL,
  `issued_date`       DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `collected_by`      VARCHAR(150) DEFAULT NULL COMMENT 'Ward staff who took the unit',

  -- Outcome. A returned unit goes back to inventory only if the cold chain held.
  `transfusion_status` ENUM('Issued','Transfused','Returned','Discarded') NOT NULL DEFAULT 'Issued',
  `transfusion_date`  DATETIME     DEFAULT NULL,
  `reaction_flag`     TINYINT(1)   NOT NULL DEFAULT 0,
  `reaction_type`     VARCHAR(150) DEFAULT NULL,
  `reaction_notes`    TEXT         DEFAULT NULL,
  `return_reason`     VARCHAR(255) DEFAULT NULL,
  `returned_date`     DATETIME     DEFAULT NULL,

  PRIMARY KEY (`issue_id`),
  UNIQUE KEY `uniq_issue_number` (`issue_number`),
  KEY `idx_request_id` (`request_id`),
  KEY `idx_unit_id` (`unit_id`),
  KEY `idx_issued_date` (`issued_date`),
  KEY `idx_transfusion_status` (`transfusion_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Unit issue and transfusion outcome — Stage 5';


-- Indexes the stock and issue screens rely on.
CALL bb_add_index('blood_units', 'idx_available', '`is_available_for_issue`,`blood_group`,`component_type`');

DROP PROCEDURE IF EXISTS `bb_add_column`;
DROP PROCEDURE IF EXISTS `bb_add_index`;
