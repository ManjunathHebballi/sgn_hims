# CathLab Sub-Store Module - Setup & Testing Guide

## Pre-Installation Checklist

- [ ] Backup existing database
- [ ] Verify PHP version 7.2+
- [ ] Verify MySQL 5.7.12+ (for JSON support)
- [ ] Existing store, patient, billing modules working
- [ ] User roles configured (doctor, store_staff, billing_staff)

---

## Installation Steps

### Step 1: Database Schema Setup

1. Open MySQL client or phpMyAdmin
2. Select your hospital database
3. Execute the SQL from `CATHLAB_SCHEMA.sql`
   - All tables will be created
   - All views will be created
   - Stored procedures ready (if using MySQL 5.7+)

### Step 2: Create CathLab Store

Execute this SQL:
```sql
INSERT INTO store (store_name, store_type, is_independent, contact_person, phone, address, status, created_by, created_at)
VALUES ('CathLab Store', 'cathlab', 1, 'CathLab Manager', '1234567890', 'CathLab Department', 'active', 1, NOW());
```

Get the store ID:
```sql
SELECT id FROM store WHERE store_type = 'cathlab';
```
Note down this ID (e.g., 5).

### Step 3: Add Sample CathLab Items

```sql
-- Add Catheters
INSERT INTO cathlab_store_items 
(store_id, item_code, item_name, category_id, unit_id, item_type, cost_price, selling_price, is_batch_mandatory, is_expiry_mandatory, status, created_by)
VALUES 
(5, 'CAT-GD-001', 'Guiding Catheter 6F', 1, 1, 'consumable', 2500.00, 4500.00, 1, 1, 'active', 1),
(5, 'CAT-JL-002', 'JL 3.5 Guiding Catheter', 1, 1, 'consumable', 2000.00, 3500.00, 1, 1, 'active', 1),
(5, 'CAT-DIAG-003', 'Diagnostic Catheter 6F', 1, 1, 'consumable', 1500.00, 2500.00, 1, 1, 'active', 1),
(5, 'BALLOON-001', 'Balloon 2.0 x 12mm', 1, 1, 'consumable', 5000.00, 8500.00, 1, 1, 'active', 1),
(5, 'BALLOON-002', 'Balloon 2.5 x 15mm', 1, 1, 'consumable', 5500.00, 9000.00, 1, 1, 'active', 1),
(5, 'STENT-001', 'Stent 2.5 x 28mm', 1, 1, 'implant', 15000.00, 35000.00, 1, 1, 'active', 1),
(5, 'STENT-002', 'Stent 3.0 x 28mm', 1, 1, 'implant', 18000.00, 42000.00, 1, 1, 'active', 1),
(5, 'WIRE-001', 'Guide Wire 0.014', 1, 1, 'consumable', 1200.00, 2000.00, 1, 1, 'active', 1);
```

### Step 4: Copy Files to Application

Copy these files to your HMS root:
- `cathlab_grn.php` - GRN entry
- `cathlab_procedure.php` - Procedure entry
- `controller/processCathLab.php` - Backend logic
- `ajax/searchAdmission.php` - Search functionality
- `ajax/getBatches.php` - Batch selection

### Step 5: Update storeClass.php

Add all methods from `CATHLAB_STORECLASS_METHODS.php` to your `model/storeClass.php` class.

### Step 6: Update Leftbar/Navigation

Add menu items for CathLab:
```php
<li>
    <a href="cathlab_grn.php">
        <i class="fa fa-cube"></i>
        <span>CathLab GRN</span>
    </a>
</li>
<li>
    <a href="cathlab_procedure.php">
        <i class="fa fa-heartbeat"></i>
        <span>CathLab Procedure</span>
    </a>
</li>
```

---

## Testing Workflow

### Test 1: Create a GRN

**Objective**: Verify GRN creation and batch tracking

1. Go to CathLab GRN form
2. Fill details:
   - GRN Date: Today
   - Supplier: Select any supplier
   - Invoice No: INV001
   - Invoice Date: Today
3. Add items:
   - Item: Guiding Catheter 6F
   - Qty: 5
   - Cost: 2500
   - Batch: BATCH001
   - Expiry: 2027-01-31
4. Click Save
5. Verify:
   - GRN created successfully
   - Check `SELECT * FROM store_grn WHERE grn_number LIKE 'CATHLAB-GRN%'`
   - Check `SELECT * FROM cathlab_batch_master WHERE batch_number = 'BATCH001'`
   - Verify `available_quantity = 5`

### Test 2: Create a CathLab Procedure

**Objective**: Verify procedure creation and item reservation

1. Admit a test patient to pre-op ward
2. Go to CathLab Procedure form
3. Search and select the admitted patient
4. Fill procedure details:
   - Procedure Type: Angiography
   - Doctor: Select doctor
   - Status: Completed
5. Add items consumed:
   - Item: Guiding Catheter 6F
   - Qty: 2
   - Select Batch: BATCH001
6. Click Save
7. Verify:
   - Procedure created
   - Check `SELECT * FROM cathlab_procedure`
   - Check batch: `SELECT available_quantity, reserved_quantity FROM cathlab_batch_master WHERE id = [batch_id]`
   - available_quantity should be 3 (5-2)
   - reserved_quantity should be 2

### Test 3: Add Procedure Items to Billing

**Objective**: Verify integration with existing billing module

1. Go to patient billing (existing module)
2. Search for the admitted patient with procedure
3. Add services/items from CathLab procedure
4. Verify procedure items appear in billing
5. Finalize bill
6. Verify:
   - Stock deducted from reserved to consumed
   - `SELECT consumed_quantity FROM cathlab_batch_master WHERE id = [batch_id]` should be 2

### Test 4: Check Stock Ledger

**Objective**: Verify audit trail

```sql
SELECT * FROM cathlab_stock_ledger 
WHERE item_id = (SELECT id FROM cathlab_store_items WHERE item_code = 'CAT-GD-001')
ORDER BY created_at DESC;
```

Expected entries:
- GRN_IN: +5 qty
- PROCEDURE_RESERVE: -2 qty
- BILLING_DEDUCT: -2 qty (when bill finalized)

### Test 5: Check Expiry Alerts

```sql
SELECT * FROM vw_cathlab_expiry_alert WHERE status != 'OK';
```

Should show any expiring batches.

### Test 6: Generate Reports

#### Cost vs Revenue Report
```sql
SELECT * FROM vw_cathlab_cost_vs_revenue;
```

#### Stock Summary
```sql
SELECT * FROM vw_cathlab_stock_summary WHERE total_available > 0;
```

#### Procedure Consumption
```sql
SELECT * FROM vw_cathlab_procedure_consumption 
ORDER BY procedure_date DESC LIMIT 10;
```

---

## Troubleshooting

### Issue: "CathLab store not configured"
**Solution**: Execute this SQL to create CathLab store
```sql
INSERT INTO store (store_name, store_type, is_independent, status, created_by)
VALUES ('CathLab Store', 'cathlab', 1, 'active', 1);
```

### Issue: Batch selection shows no batches
**Solution**: 
- Verify GRN was created successfully
- Check: `SELECT * FROM cathlab_batch_master WHERE available_quantity > 0`
- Verify item_id matches in database

### Issue: Stock not reserving on procedure creation
**Solution**:
- Check procedure_items were inserted: `SELECT * FROM cathlab_procedure_items`
- Verify batch_id is valid
- Check stored procedure permissions

### Issue: Bill not integrating with existing billing
**Solution**:
- Verify billing_item_detail table structure has cathlab columns
- Check procedure_id is correctly linked
- Verify foreign key constraints

---

## Database Validation

Run this query to verify schema completeness:

```sql
-- Check all tables exist
SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME LIKE '%cathlab%';

-- Should return:
-- cathlab_batch_master
-- cathlab_billing_header (optional)
-- cathlab_implant_traceability
-- cathlab_procedure
-- cathlab_procedure_items
-- cathlab_stock_ledger
-- cathlab_store_items

-- Check views
SELECT TABLE_NAME FROM INFORMATION_SCHEMA.VIEWS 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME LIKE '%cathlab%';

-- Should return:
-- vw_cathlab_cost_vs_revenue
-- vw_cathlab_expiry_alert
-- vw_cathlab_procedure_consumption
-- vw_cathlab_stock_summary
```

---

## Performance Optimization

### Add Indexes for Large Datasets

```sql
-- Procedure queries
CREATE INDEX idx_cathlab_proc_date ON cathlab_procedure(procedure_date);
CREATE INDEX idx_cathlab_proc_status ON cathlab_procedure(status, billed_status);

-- Stock ledger queries
CREATE INDEX idx_csl_created ON cathlab_stock_ledger(created_at);
CREATE INDEX idx_csl_reference ON cathlab_stock_ledger(reference_type, reference_id);

-- Batch queries
CREATE INDEX idx_batch_available ON cathlab_batch_master(available_quantity);
CREATE INDEX idx_batch_expiry ON cathlab_batch_master(expiry_date);
```

---

## Security Considerations

1. **User Permissions**: Restrict GRN creation to authorized staff
2. **Audit Logging**: All transactions logged in cathlab_stock_ledger
3. **Batch Traceability**: Implants linked to patients for recall management
4. **Soft Deletes**: No hard deletes - maintains data integrity

---

## Data Backup

Backup CathLab data regularly:
```bash
mysqldump -u root -p hospital_db cathlab_* > cathlab_backup_$(date +%Y%m%d).sql
```

---

## Go-Live Checklist

- [ ] Database schema installed and verified
- [ ] CathLab store created
- [ ] Sample items added
- [ ] All files copied to application
- [ ] storeClass methods added
- [ ] Navigation menu updated
- [ ] Test workflow completed successfully
- [ ] Reports verified
- [ ] User training completed
- [ ] Backup created

---

## Support & Documentation

- Schema Details: `CATHLAB_SCHEMA.sql`
- Implementation Guide: `CATHLAB_IMPLEMENTATION_GUIDE.md`
- Query Examples: `CATHLAB_IMPLEMENTATION_GUIDE.md`
- Methods Documentation: `CATHLAB_STORECLASS_METHODS.php`

