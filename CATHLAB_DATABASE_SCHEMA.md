# CathLab Sub-Store Module - Database Schema

This document provides the SQL schema required for the CathLab Sub-Store module implementation.

## Tables Structure

### 1. cathlab_store_items
Stores all CathLab-specific consumables and medical items

```sql
CREATE TABLE cathlab_store_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    item_code VARCHAR(50) UNIQUE NOT NULL,
    item_name VARCHAR(255) NOT NULL,
    category_id INT NOT NULL,
    unit_id INT NOT NULL,
    item_type ENUM('consumable', 'medical_device', 'implant') NOT NULL,
    unit_cost DECIMAL(12, 2) NOT NULL,
    billing_price DECIMAL(12, 2) NOT NULL,
    min_stock DECIMAL(12, 2) NOT NULL,
    reorder_level DECIMAL(12, 2) NOT NULL,
    current_stock DECIMAL(12, 2) DEFAULT 0,
    is_expiry_required TINYINT(1) DEFAULT 0,
    is_serial_required TINYINT(1) DEFAULT 0,
    description TEXT,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (category_id) REFERENCES store_category(id),
    FOREIGN KEY (unit_id) REFERENCES store_unit(id),
    INDEX idx_item_code (item_code),
    INDEX idx_status (status),
    INDEX idx_stock_level (current_stock)
);
```

### 2. store_grn (Enhanced for CathLab)
Modified to include CathLab-specific GRN processing

```sql
ALTER TABLE store_grn ADD COLUMN grn_type ENUM('main_store', 'cathlab', 'radiology', 'pathology') DEFAULT 'main_store' AFTER po_id;
```

### 3. cathlab_billing
Main table for CathLab patient billing records

```sql
CREATE TABLE cathlab_billing (
    id INT PRIMARY KEY AUTO_INCREMENT,
    billing_number VARCHAR(50) UNIQUE NOT NULL,
    billing_date DATE NOT NULL,
    patient_id INT NOT NULL,
    patient_ip_no VARCHAR(50) NOT NULL,
    procedure_type VARCHAR(100) NOT NULL,
    subtotal DECIMAL(12, 2) NOT NULL,
    discount_amount DECIMAL(12, 2) DEFAULT 0,
    gst_amount DECIMAL(12, 2) NOT NULL,
    total_amount DECIMAL(12, 2) NOT NULL,
    remarks TEXT,
    billed_by INT NOT NULL,
    status ENUM('pending', 'billed', 'paid', 'cancelled') DEFAULT 'billed',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patient_admission(id),
    FOREIGN KEY (billed_by) REFERENCES users(id),
    INDEX idx_billing_number (billing_number),
    INDEX idx_patient_ip_no (patient_ip_no),
    INDEX idx_billing_date (billing_date),
    INDEX idx_status (status)
);
```

### 4. cathlab_billing_items
Line items for each CathLab billing

```sql
CREATE TABLE cathlab_billing_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    billing_id INT NOT NULL,
    item_id INT NOT NULL,
    quantity DECIMAL(12, 2) NOT NULL,
    unit_price DECIMAL(12, 2) NOT NULL,
    billing_price DECIMAL(12, 2) NOT NULL,
    markup_percent DECIMAL(5, 2) DEFAULT 0,
    amount DECIMAL(12, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (billing_id) REFERENCES cathlab_billing(id) ON DELETE CASCADE,
    FOREIGN KEY (item_id) REFERENCES cathlab_store_items(id),
    INDEX idx_billing_id (billing_id)
);
```

### 5. cathlab_stock_ledger
Stock movement tracking for CathLab items

```sql
CREATE TABLE cathlab_stock_ledger (
    id INT PRIMARY KEY AUTO_INCREMENT,
    item_id INT NOT NULL,
    transaction_type ENUM('GRN_IN', 'BILLING_OUT', 'ADJUSTMENT', 'RETURN', 'TRANSFER') NOT NULL,
    quantity DECIMAL(12, 2) NOT NULL,
    reference_type VARCHAR(50) NOT NULL,
    reference_id INT NOT NULL,
    description TEXT,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (item_id) REFERENCES cathlab_store_items(id),
    FOREIGN KEY (created_by) REFERENCES users(id),
    INDEX idx_item_id (item_id),
    INDEX idx_transaction_type (transaction_type),
    INDEX idx_created_at (created_at)
);
```

### 6. patient_admission (Enhanced)
Enhanced to track CathLab billing linkage

```sql
ALTER TABLE patient_admission 
ADD COLUMN cathlab_billed VARCHAR(10) DEFAULT 'no' AFTER discharge_date,
ADD COLUMN cathlab_billing_id INT AFTER cathlab_billed,
ADD COLUMN cathlab_procedure_type VARCHAR(100) AFTER cathlab_billing_id,
ADD COLUMN cathlab_notes TEXT AFTER cathlab_procedure_type,
ADD FOREIGN KEY (cathlab_billing_id) REFERENCES cathlab_billing(id);
```

### 7. store_grn (Enhanced for CathLab Items)
Update GRN to support CathLab items table

```sql
-- Existing store_grn table modification
ALTER TABLE store_grn_items 
ADD COLUMN cathlab_item_id INT AFTER item_id,
ADD FOREIGN KEY (cathlab_item_id) REFERENCES cathlab_store_items(id);
```

## Views and Reports

### CathLab Inventory Summary View

```sql
CREATE VIEW cathlab_inventory_summary AS
SELECT 
    csi.id,
    csi.item_code,
    csi.item_name,
    csi.item_type,
    sc.category_name,
    su.unit_name,
    csi.current_stock,
    csi.min_stock,
    csi.reorder_level,
    csi.unit_cost,
    csi.billing_price,
    (csi.current_stock * csi.unit_cost) as stock_value,
    CASE 
        WHEN csi.current_stock <= 0 THEN 'OUT_OF_STOCK'
        WHEN csi.current_stock <= csi.min_stock THEN 'LOW_STOCK'
        ELSE 'OK'
    END as status
FROM cathlab_store_items csi
LEFT JOIN store_category sc ON csi.category_id = sc.id
LEFT JOIN store_unit su ON csi.unit_id = su.id
WHERE csi.status = 'active';
```

### CathLab Monthly Report View

```sql
CREATE VIEW cathlab_monthly_summary AS
SELECT 
    DATE_FORMAT(cb.billing_date, '%Y-%m') as month,
    COUNT(DISTINCT cb.id) as total_billings,
    SUM(cb.total_amount) as total_billing_amount,
    COUNT(DISTINCT cb.patient_id) as unique_patients,
    AVG(cb.total_amount) as avg_billing_amount
FROM cathlab_billing cb
GROUP BY DATE_FORMAT(cb.billing_date, '%Y-%m')
ORDER BY month DESC;
```

## Required Stored Procedures

### Generate CathLab GRN Number

```sql
DELIMITER $$
CREATE PROCEDURE GenerateCathLabGRNNumber(OUT grn_no VARCHAR(50))
BEGIN
    DECLARE curr_year VARCHAR(4);
    DECLARE curr_month VARCHAR(2);
    DECLARE next_sequence INT;
    
    SELECT DATE_FORMAT(CURDATE(), '%Y%m') INTO curr_year;
    SELECT DATE_FORMAT(CURDATE(), '%m') INTO curr_month;
    
    SELECT IFNULL(MAX(CAST(SUBSTRING(grn_number, 9) AS UNSIGNED)), 0) + 1
    INTO next_sequence
    FROM store_grn
    WHERE grn_type = 'cathlab'
    AND DATE_FORMAT(grn_date, '%Y%m') = curr_year;
    
    SET grn_no = CONCAT('CATH-GRN-', curr_year, '-', LPAD(next_sequence, 4, '0'));
END $$
DELIMITER ;
```

### Generate CathLab Billing Number

```sql
DELIMITER $$
CREATE PROCEDURE GenerateCathLabBillingNumber(OUT bill_no VARCHAR(50))
BEGIN
    DECLARE curr_year VARCHAR(4);
    DECLARE next_sequence INT;
    
    SELECT DATE_FORMAT(CURDATE(), '%Y') INTO curr_year;
    
    SELECT IFNULL(MAX(CAST(SUBSTRING(billing_number, 9) AS UNSIGNED)), 0) + 1
    INTO next_sequence
    FROM cathlab_billing
    WHERE DATE_FORMAT(billing_date, '%Y') = curr_year;
    
    SET bill_no = CONCAT('CATH-BILL-', curr_year, '-', LPAD(next_sequence, 5, '0'));
END $$
DELIMITER ;
```

## Installation Steps

1. **Create Tables** in order:
   - cathlab_store_items
   - cathlab_billing
   - cathlab_billing_items
   - cathlab_stock_ledger

2. **Create Views**:
   - cathlab_inventory_summary
   - cathlab_monthly_summary

3. **Alter Existing Tables**:
   - Add CathLab columns to patient_admission
   - Add grn_type to store_grn
   - Add cathlab_item_id to store_grn_items

4. **Create Stored Procedures**:
   - GenerateCathLabGRNNumber
   - GenerateCathLabBillingNumber

5. **Create Indexes** for performance optimization

## Migration Script

Use the provided `CATHLAB_DATABASE_SCHEMA.sql` file to execute all the above in a single transaction.

---

## Notes

- All amounts are stored in DECIMAL(12, 2) for accounting accuracy
- Stock tracking uses a ledger system for audit trail
- GRN and Billing numbers are auto-generated with year-month prefixes for easy identification
- All timestamps are in server timezone (Asia/Kolkata)
- Soft deletes are used for data retention
