-- ============================================================================
-- ABHA / ABDM integration schema
-- Run once per database:
--   mysql -u root hospital < sql/abha_integration.sql
--
-- Backs createAabha.php, abha_integration.php and abha_link_patient.php.
-- Every statement is written to be safe to re-run.
-- ============================================================================

-- Gateway API keys minted at login (controller/processLogin.php).
CREATE TABLE IF NOT EXISTS `abha_api_key` (
  `id`        int(11)      NOT NULL AUTO_INCREMENT,
  `keyId`     varchar(255) NOT NULL,
  `apiKey`    text         NOT NULL,
  `expiresAt` datetime     NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Every ABHA address seen for an ABHA number (a patient may hold several).
CREATE TABLE IF NOT EXISTS `abha_addresses` (
  `id`           int(11)      NOT NULL AUTO_INCREMENT,
  `abha_number`  varchar(50)  NOT NULL,
  `abha_address` varchar(120) NOT NULL,
  `full_name`    varchar(150) DEFAULT NULL,
  `is_preferred` tinyint(1)   NOT NULL DEFAULT 0,
  `created_at`   datetime     NOT NULL DEFAULT current_timestamp(),
  `updated_at`   datetime     NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_number_address` (`abha_number`,`abha_address`),
  KEY `idx_abha_number` (`abha_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Audit trail of the consent text the patient accepted before each ABDM call.
CREATE TABLE IF NOT EXISTS `abha_consent_log` (
  `id`              int(11)      NOT NULL AUTO_INCREMENT,
  `purpose`         varchar(20)  NOT NULL,
  `abha_number`     varchar(50)  DEFAULT NULL,
  `consent_version` varchar(20)  DEFAULT NULL,
  `language`        varchar(10)  DEFAULT NULL,
  `consent_text`    text         DEFAULT NULL,
  `user_name`       varchar(150) DEFAULT NULL,
  `ip_address`      varchar(64)  DEFAULT NULL,
  `created_at`      datetime     NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_abha_number` (`abha_number`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------------------------------------------------------
-- ABHA columns on the patient tables.
-- MySQL/MariaDB has no ADD COLUMN IF NOT EXISTS on every version in the wild,
-- so each column is added through a prepared statement that no-ops when the
-- column already exists.
-- ----------------------------------------------------------------------------
DROP PROCEDURE IF EXISTS `abha_add_column`;
DELIMITER //
CREATE PROCEDURE `abha_add_column`(IN tbl VARCHAR(64), IN col VARCHAR(64), IN spec TEXT)
BEGIN
  IF NOT EXISTS (
    SELECT 1 FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = tbl AND COLUMN_NAME = col
  ) THEN
    SET @ddl = CONCAT('ALTER TABLE `', tbl, '` ADD COLUMN `', col, '` ', spec);
    PREPARE stmt FROM @ddl;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
  END IF;
END //
DELIMITER ;

CALL abha_add_column('central_opd', 'abha_number',  "varchar(100) NOT NULL DEFAULT ''");
CALL abha_add_column('central_opd', 'abha_address', "varchar(100) NOT NULL DEFAULT ''");
CALL abha_add_column('central_opd', 'abha_linked',  "tinyint(1) NOT NULL DEFAULT 0");

CALL abha_add_column('central_ipd', 'abha_number',  "varchar(255) NOT NULL DEFAULT ''");
CALL abha_add_column('central_ipd', 'abha_address', "varchar(255) NOT NULL DEFAULT ''");
CALL abha_add_column('central_ipd', 'abha_linked',  "tinyint(1) NOT NULL DEFAULT 0");

-- Care-context link flags used by abha_integration.php::ccGroupLinked().
CALL abha_add_column('billing',       'abha_linked', "tinyint(1) NOT NULL DEFAULT 0");
CALL abha_add_column('sample_status', 'abha_linked', "tinyint(1) NOT NULL DEFAULT 0");

DROP PROCEDURE IF EXISTS `abha_add_column`;

-- ============================================================================
-- Date of birth
-- ----------------------------------------------------------------------------
-- ABDM returns a real date of birth, and the ABHA prefill on opregistration.php
-- writes it here. Nullable on purpose: every row registered before this column
-- existed has an age ("54Y") but no DOB, and NULL says "unknown" where
-- '0000-00-00' would read as a real date.
-- ============================================================================
DROP PROCEDURE IF EXISTS `abha_add_column`;
DELIMITER //
CREATE PROCEDURE `abha_add_column`(IN tbl VARCHAR(64), IN col VARCHAR(64), IN spec TEXT)
BEGIN
  IF NOT EXISTS (
    SELECT 1 FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = tbl AND COLUMN_NAME = col
  ) THEN
    SET @ddl = CONCAT('ALTER TABLE `', tbl, '` ADD COLUMN `', col, '` ', spec);
    PREPARE stmt FROM @ddl;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
  END IF;
END //
DELIMITER ;

CALL abha_add_column('central_opd', 'dob', "date DEFAULT NULL AFTER `name`");
CALL abha_add_column('central_ipd', 'dob', "date DEFAULT NULL AFTER `name`");

DROP PROCEDURE IF EXISTS `abha_add_column`;

-- ============================================================================
-- Tables read by api/patient_history.php (care-context discovery).
-- Both are optional clinical stores this install did not have; created empty so
-- the history builder can query them without erroring.
-- ============================================================================
CREATE TABLE IF NOT EXISTS `immunization` (
  `id`                int(11)      NOT NULL AUTO_INCREMENT,
  `patient_id`        int(11)      NOT NULL,
  `immunization_name` varchar(255) NOT NULL,
  `manufacturer`      varchar(150) NOT NULL DEFAULT '',
  `lot_number`        varchar(100) NOT NULL DEFAULT '',
  `dose_number`       varchar(50)  NOT NULL DEFAULT '',
  `next_date`         date         DEFAULT NULL,
  `created_at`        datetime     NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `patient_documents` (
  `id`            int(11)      NOT NULL AUTO_INCREMENT,
  `patient_type`  varchar(10)  NOT NULL DEFAULT 'IP',
  `patient_no`    varchar(100) NOT NULL,
  `patient_name`  varchar(255) NOT NULL,
  `document_name` varchar(255) NOT NULL,
  `file_path`     varchar(500) NOT NULL,
  `uploaded_by`   varchar(100) NOT NULL,
  `uploaded_at`   datetime     NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_patient_no` (`patient_no`),
  KEY `idx_uploaded_at` (`uploaded_at`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- ============================================================================
-- Care-context link state + postal address parts
-- ----------------------------------------------------------------------------
-- linkcare_token / linkRequestId cache the ABDM link token issued for a patient
-- so a second clinical save reuses it instead of asking again (ABDM rejects a
-- duplicate request with ABDM-1092 while one is still live).
--
-- state / district / pincode are the postal parts ABDM returns on a verified
-- ABHA profile. Nullable: rows registered before these columns existed have the
-- whole address in `address` alone.
-- ============================================================================
DROP PROCEDURE IF EXISTS `abha_add_column`;
DELIMITER //
CREATE PROCEDURE `abha_add_column`(IN tbl VARCHAR(64), IN col VARCHAR(64), IN spec TEXT)
BEGIN
  IF NOT EXISTS (
    SELECT 1 FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = tbl AND COLUMN_NAME = col
  ) THEN
    SET @ddl = CONCAT('ALTER TABLE `', tbl, '` ADD COLUMN `', col, '` ', spec);
    PREPARE stmt FROM @ddl;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
  END IF;
END //
DELIMITER ;

CALL abha_add_column('central_opd', 'linkcare_token', "text DEFAULT NULL");
CALL abha_add_column('central_opd', 'linkRequestId',  "varchar(255) DEFAULT NULL");
CALL abha_add_column('central_opd', 'state',          "varchar(100) DEFAULT NULL");
CALL abha_add_column('central_opd', 'district',       "varchar(100) DEFAULT NULL");
CALL abha_add_column('central_opd', 'pincode',        "varchar(10)  DEFAULT NULL");

CALL abha_add_column('central_ipd', 'linkcare_token', "text DEFAULT NULL");
CALL abha_add_column('central_ipd', 'linkRequestId',  "varchar(255) DEFAULT NULL");

-- Set when a lab report's care context is pushed, so the callback can be matched
-- back to the sample it belongs to.
CALL abha_add_column('sample_status', 'linkRequestId', "varchar(64) DEFAULT NULL");

DROP PROCEDURE IF EXISTS `abha_add_column`;

-- ============================================================================
-- OP treatment / wellness capture (optreatment.php)
-- ----------------------------------------------------------------------------
-- The wellness and family-history fields the treatment form records. They are
-- what lets a visit publish an ABDM WellnessRecord care context instead of a
-- card full of "NA"; family_history holds one JSON row per relative.
-- ============================================================================
DROP PROCEDURE IF EXISTS `abha_add_column`;
DELIMITER //
CREATE PROCEDURE `abha_add_column`(IN tbl VARCHAR(64), IN col VARCHAR(64), IN spec TEXT)
BEGIN
  IF NOT EXISTS (
    SELECT 1 FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = tbl AND COLUMN_NAME = col
  ) THEN
    SET @ddl = CONCAT('ALTER TABLE `', tbl, '` ADD COLUMN `', col, '` ', spec);
    PREPARE stmt FROM @ddl;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
  END IF;
END //
DELIMITER ;

CALL abha_add_column('op_examination', 'created_at',       "datetime DEFAULT current_timestamp()");
CALL abha_add_column('op_examination', 'family_history',   "text DEFAULT NULL");
CALL abha_add_column('op_examination', 'smoking',          "varchar(100) DEFAULT NULL");
CALL abha_add_column('op_examination', 'alcohol',          "varchar(100) DEFAULT NULL");
CALL abha_add_column('op_examination', 'diet',             "varchar(255) DEFAULT NULL");
CALL abha_add_column('op_examination', 'steps',            "varchar(50)  DEFAULT NULL");
CALL abha_add_column('op_examination', 'calories_burned',  "varchar(50)  DEFAULT NULL");
CALL abha_add_column('op_examination', 'sleep_duration',   "varchar(50)  DEFAULT NULL");
CALL abha_add_column('op_examination', 'lmp',              "varchar(50)  DEFAULT NULL");
CALL abha_add_column('op_examination', 'menopause',        "varchar(50)  DEFAULT NULL");
CALL abha_add_column('op_examination', 'menarche',         "varchar(50)  DEFAULT NULL");
CALL abha_add_column('op_examination', 'fluid_intake',     "varchar(50)  DEFAULT NULL");
CALL abha_add_column('op_examination', 'mental_status',    "varchar(255) DEFAULT NULL");
CALL abha_add_column('op_examination', 'overall_wellness', "varchar(255) DEFAULT NULL");

DROP PROCEDURE IF EXISTS `abha_add_column`;

-- Lookup behind the treatment form's "Roga" dropdown. addOTTreat::getRoga()
-- has always queried this table; it simply did not exist here, so the query
-- failed silently and the dropdown rendered empty. Created empty to fix that.
CREATE TABLE IF NOT EXISTS `roga` (
  `id`        int(11)      NOT NULL AUTO_INCREMENT,
  `roga_name` varchar(100) NOT NULL,
  `status`    varchar(20)  NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Lookup behind the treatment form's sub-procedure column. optreatment's
-- getProceMedic() has always joined it; the table simply did not exist here, so
-- the old mysqli_query() returned false and the join silently produced nothing.
-- The ported prepared-statement version turns that into a fatal, so create it.
CREATE TABLE IF NOT EXISTS `sub_procedure` (
  `id`                int(11)      NOT NULL AUTO_INCREMENT,
  `subprocedure_name` varchar(100) NOT NULL,
  `procedure_id`      int(11)      NOT NULL,
  `status`            varchar(20)  NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
