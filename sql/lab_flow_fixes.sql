-- ============================================================================
-- Lab module — schema the code already depends on
-- Run once per database:
--   mysql -u root hospital < sql/lab_flow_fixes.sql
--
-- labClass::insertSampleCollect() writes sample_status.barcode_no and
-- api/import.php reads it, but the column is absent from lab.sql. Where the live
-- database already has it (added by hand at some point) this is a no-op; where
-- it does not, sample collection fails outright with "Unknown column".
--
-- Safe to re-run.
-- ============================================================================

DROP PROCEDURE IF EXISTS `lab_add_column`;
DELIMITER //
CREATE PROCEDURE `lab_add_column`(IN tbl VARCHAR(64), IN col VARCHAR(64), IN spec TEXT)
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

DROP PROCEDURE IF EXISTS `lab_add_index`;
DELIMITER //
CREATE PROCEDURE `lab_add_index`(IN tbl VARCHAR(64), IN idx VARCHAR(64), IN cols TEXT)
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

-- Barcode printed on the tube: "<visit_no>/<test_id>".
CALL lab_add_column('sample_status', 'barcode_no', "varchar(100) NOT NULL DEFAULT '' AFTER `id`");

-- The stage worklists filter on these flags across a date range; without an
-- index every screen scans the whole table.
CALL lab_add_index('sample_status', 'idx_ss_collected',  '`is_sample_collected`');
CALL lab_add_index('sample_status', 'idx_ss_dispatch',   '`is_sample_dispatch`');
CALL lab_add_index('sample_status', 'idx_ss_ack',        '`is_sample_acknowledge`');
CALL lab_add_index('sample_status', 'idx_ss_finalized',  '`is_finalized`');
CALL lab_add_index('sample_status', 'idx_ss_barcode',    '`barcode_no`');

-- ----------------------------------------------------------------------------
-- Defaults for the stage columns.
--
-- sample_status declares 21 columns NOT NULL with no default, but a sample is
-- created knowing only that it was collected — nothing yet about dispatch,
-- acknowledgement, outsourcing, results or finalization. Under
-- STRICT_TRANS_TABLES (the default since MySQL 5.7) the collection INSERT is
-- rejected outright with "Field 'is_sample_dispatch' doesn't have a default
-- value", so the module only runs on a server with strict mode switched off.
--
-- Giving each stage column the value it logically starts at makes collection
-- work under strict mode and changes nothing where strict mode is already off.
-- The *_at timestamps become nullable on purpose: a sample that has not been
-- dispatched has no dispatch time, and NO_ZERO_DATE rejects '0000-00-00'
-- anyway. bill_no / patient_id / test_id stay required — they genuinely are.
-- ----------------------------------------------------------------------------
ALTER TABLE `sample_status`
  MODIFY `is_sample_collected`     int          NOT NULL DEFAULT 0,
  MODIFY `is_sample_dispatch`      int          NOT NULL DEFAULT 0,
  MODIFY `is_sample_acknowledge`   int          NOT NULL DEFAULT 0,
  MODIFY `is_outsourced`           int          NOT NULL DEFAULT 0,
  MODIFY `is_result_entered`       int          NOT NULL DEFAULT 0,
  MODIFY `is_finalized`            int          NOT NULL DEFAULT 0,
  MODIFY `outsource_lab_id`        int          NOT NULL DEFAULT 0,
  MODIFY `sample_collected_by`     varchar(100) NOT NULL DEFAULT '',
  MODIFY `sample_dispatch_by`      varchar(100) NOT NULL DEFAULT '',
  MODIFY `sample_acknowledged_by`  varchar(100) NOT NULL DEFAULT '',
  MODIFY `outsourced_by`           varchar(100) NOT NULL DEFAULT '',
  MODIFY `outsourced_price`        varchar(50)  NOT NULL DEFAULT '',
  MODIFY `result_entered_by`       varchar(200) NOT NULL DEFAULT '',
  MODIFY `finalized_by`            varchar(200) NOT NULL DEFAULT '',
  MODIFY `sample_collected_at`     datetime     NULL DEFAULT NULL,
  MODIFY `sample_dispatch_at`      datetime     NULL DEFAULT NULL,
  MODIFY `sample_acknowledged_at`  datetime     NULL DEFAULT NULL,
  MODIFY `outsourced_at`           datetime     NULL DEFAULT NULL,
  MODIFY `result_entered_at`       datetime     NULL DEFAULT NULL,
  MODIFY `finalized_at`            datetime     NULL DEFAULT NULL;

DROP PROCEDURE IF EXISTS `lab_add_column`;
DROP PROCEDURE IF EXISTS `lab_add_index`;

-- ----------------------------------------------------------------------------
-- NOT added on purpose: a UNIQUE key on (bill_no, patient_id, test_id).
-- It is the right long-term shape, but any database that already accumulated
-- duplicate collection rows would reject the index and the migration would fail
-- half-applied. insertSampleCollect() now refuses a duplicate in code instead.
-- To adopt the constraint later, de-duplicate first:
--   SELECT bill_no, patient_id, test_id, COUNT(*) c FROM sample_status
--    GROUP BY bill_no, patient_id, test_id HAVING c > 1;
-- ----------------------------------------------------------------------------
