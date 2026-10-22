-- ============================================================================
-- Indexes behind the ABHA registration report (abha_reports.php)
-- Run once per database:
--   mysql -u root hospital < sql/abha_report_indexes.sql
--
-- central_opd and central_ipd shipped with nothing but PRIMARY KEY (id), so
-- every date-range report scans the whole table, and any join on opd_no scans
-- it once per driving row. On a 150k-row central_opd that made one ABHA report
-- page load take ~6 minutes; with these four indexes it is well under a second.
--
-- These help far more than the ABHA report: reportClass::getIPDetails(),
-- getOPDetails(), the daily/periodical registers and api/patient_history.php
-- all filter on the same columns.
--
-- Every statement is written to be safe to re-run.
-- ============================================================================

DROP PROCEDURE IF EXISTS `abha_add_index`;
DELIMITER //
CREATE PROCEDURE `abha_add_index`(IN tbl VARCHAR(64), IN idx VARCHAR(64), IN cols TEXT)
BEGIN
  IF NOT EXISTS (
    SELECT 1 FROM information_schema.STATISTICS
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = tbl AND INDEX_NAME = idx
  ) THEN
    SET @ddl = CONCAT('ALTER TABLE `', tbl, '` ADD INDEX `', idx, '` (', cols, ')');
    PREPARE stmt FROM @ddl;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
  END IF;
END //
DELIMITER ;

-- The IPD -> OPD ABHA fallback joins on this. Without it, each admission in the
-- report window costs a full scan of central_opd.
CALL abha_add_index('central_opd', 'idx_opd_no', '`opd_no`');

-- Date-range filters: `WHERE c.date BETWEEN ? AND ?` and
-- `WHERE i.admission_date >= ? AND i.admission_date < ?`.
CALL abha_add_index('central_opd', 'idx_date',           '`date`');
CALL abha_add_index('central_ipd', 'idx_admission_date', '`admission_date`');

-- Department filter on the report, and the dept-wise breakdowns.
CALL abha_add_index('central_ipd', 'idx_dept_id', '`dept_id`');

-- "Care Linked" counts a visit as linked when any of its bills or lab samples
-- is linked, looked up per row by patient_id (reportClass::abhaLinkedExpr()).
CALL abha_add_index('billing',       'idx_patient_id', '`patient_id`');
CALL abha_add_index('sample_status', 'idx_patient_id', '`patient_id`');

DROP PROCEDURE IF EXISTS `abha_add_index`;
