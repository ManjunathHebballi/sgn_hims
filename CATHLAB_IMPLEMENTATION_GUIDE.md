# CathLab Sub-Store Module - Implementation Guide

## Overview
This module provides a dedicated CathLab sub-store with independent GRN processing, batch-level inventory tracking, and direct patient billing integration.

---

## Architecture & Key Features

### 1. Store Independence
- **store_type = 'cathlab'** identifies CathLab as independent store
- No dependency on main store
- Separate item master and batch tracking
- Dedicated stock ledger

### 2. GRN Processing
- CathLab users create GRNs directly
- Batch and expiry tracking mandatory
- Stock added to CathLab inventory immediately upon GRN completion
- Cost price captured for margin tracking

### 3. Procedure & Consumption
- CathLab Procedure = Medical procedure record
- Items consumed during procedure marked as "pending_billing"
- Stock reserved (available → reserved) when procedure items selected
- No stock deduction until billing finalized

### 4. Billing Integration
- Items in pending_billing status converted to billing
- Stock deducted from reserved → consumed on bill finalization
- Batch-level traceability maintained for implants
- Integration with existing patient billing module

---

## Database Tables

### Core Tables
1. **cathlab_store_items** - CathLab specific items
2. **cathlab_batch_master** - Batch-level inventory with expiry tracking
3. **cathlab_stock_ledger** - Audit trail for all movements
4. **cathlab_procedure** - CathLab procedures
5. **cathlab_procedure_items** - Items consumed per procedure
6. **cathlab_implant_traceability** - Patient-wise implant tracking
7. **cathlab_billing_header** - Optional dedicated billing header

### Extended Tables
- **store** - Added store_type, parent_store_id, is_independent
- **patient_admission** - Added cathlab_procedure_id, cathlab_items_included
- **billing_item_detail** - Added cathlab_procedure_id, batch_id, is_cathlab_item

---

## Work Flow

### GRN Processing
```
CathLab Store → New GRN Entry
    ↓
Add Items (Item, Batch, Expiry, Cost Price, Qty)
    ↓
Save GRN → Creates Batch Master Records
    ↓
Stock Added to CathLab Inventory
    ↓
Stock Ledger Entry (GRN_IN)
```

### Procedure & Billing
```
Patient Admitted (Pre-op/CathLab ward)
    ↓
Create Procedure (Patient, Admission, Doctor, Type)
    ↓
Select Items from Stock + Choose Batch
    ↓
Stock Reserved (Available qty ↓, Reserved qty ↑)
    ↓
Stock Ledger Entry (PROCEDURE_RESERVE)
    ↓
BILLING INTEGRATION:
    Items Added to Bill
    ↓
    Bill Finalized
    ↓
    Stock Deducted (Reserved ↓, Consumed ↑)
    ↓
    Batch Traceability Locked (for implants)
```

---

## Key Business Rules

### Stock Management
- No stock deduction during procedure creation
- Stock only deducted when bill is finalized
- Batch selection mandatory (for expiry tracking)
- Quantity cannot exceed available quantity in batch

### Billing
- No billing without valid procedure
- No procedure without patient + admission
- Items marked as pending_billing until bill finalized
- Margin calculation: Selling Price - Cost Price

### Implant Traceability (Compliance)
- Batch number mandatory for implants
- Patient details locked with batch details
- Useful for recalls and regulatory audits

---

## SQL Queries & Reports

### 1. CathLab Stock Summary
```sql
SELECT 
    csi.item_code,
    csi.item_name,
    SUM(cbm.available_quantity) AS available,
    SUM(cbm.reserved_quantity) AS reserved,
    SUM(cbm.consumed_quantity) AS consumed,
    COUNT(DISTINCT CASE WHEN cbm.expiry_date < CURDATE() THEN cbm.id END) AS expired_batches,
    (SUM(cbm.available_quantity) * csi.cost_price) AS inventory_value
FROM cathlab_store_items csi
LEFT JOIN cathlab_batch_master cbm ON csi.id = cbm.item_id
WHERE csi.store_id = [CATHLAB_STORE_ID] AND csi.status = 'active'
GROUP BY csi.id
ORDER BY csi.item_name;
```

### 2. Stock Ledger Report
```sql
SELECT 
    csl.created_at,
    csi.item_code,
    csi.item_name,
    cbm.batch_number,
    cbm.expiry_date,
    csl.transaction_type,
    csl.quantity,
    csl.reference_type,
    u.name AS created_by
FROM cathlab_stock_ledger csl
JOIN cathlab_store_items csi ON csl.item_id = csi.id
LEFT JOIN cathlab_batch_master cbm ON csl.batch_id = cbm.id
JOIN users u ON csl.created_by = u.id
WHERE csl.store_id = [CATHLAB_STORE_ID]
ORDER BY csl.created_at DESC;
```

### 3. Procedure-wise Consumption
```sql
SELECT 
    cp.procedure_code,
    cp.procedure_type,
    p.name,
    u.name AS doctor,
    csi.item_code,
    csi.item_name,
    cpi.quantity_consumed,
    cpi.cost_price,
    cbm.batch_number,
    cbm.expiry_date,
    cpi.status
FROM cathlab_procedure cp
JOIN patient p ON cp.patient_id = p.id
JOIN users u ON cp.doctor_id = u.id
JOIN cathlab_procedure_items cpi ON cp.id = cpi.procedure_id
JOIN cathlab_store_items csi ON cpi.item_id = csi.id
LEFT JOIN cathlab_batch_master cbm ON cpi.batch_id = cbm.id
WHERE cp.procedure_date BETWEEN '[START_DATE]' AND '[END_DATE]'
ORDER BY cp.procedure_date DESC, cp.procedure_code;
```

### 4. Expiry Alert Report
```sql
SELECT 
    csi.item_code,
    csi.item_name,
    cbm.batch_number,
    cbm.expiry_date,
    DATEDIFF(cbm.expiry_date, CURDATE()) AS days_to_expiry,
    cbm.available_quantity,
    CASE 
        WHEN cbm.expiry_date < CURDATE() THEN 'EXPIRED'
        WHEN DATEDIFF(cbm.expiry_date, CURDATE()) <= 30 THEN 'EXPIRING_SOON'
        ELSE 'OK'
    END AS status
FROM cathlab_batch_master cbm
JOIN cathlab_store_items csi ON cbm.item_id = csi.id
WHERE cbm.status = 'active'
ORDER BY cbm.expiry_date ASC;
```

### 5. Cost vs Revenue Report
```sql
SELECT 
    DATE(cp.procedure_date) AS procedure_date,
    COUNT(DISTINCT cp.id) AS procedures,
    SUM(cpi.quantity_consumed * cpi.cost_price) AS total_cost,
    SUM(cpi.quantity_consumed * csi.selling_price) AS total_revenue,
    (SUM(cpi.quantity_consumed * csi.selling_price) - SUM(cpi.quantity_consumed * cpi.cost_price)) AS margin,
    ROUND(
        ((SUM(cpi.quantity_consumed * csi.selling_price) - SUM(cpi.quantity_consumed * cpi.cost_price)) / 
         SUM(cpi.quantity_consumed * csi.selling_price) * 100), 2
    ) AS margin_percent
FROM cathlab_procedure cp
JOIN cathlab_procedure_items cpi ON cp.id = cpi.procedure_id
JOIN cathlab_store_items csi ON cpi.item_id = csi.id
WHERE cpi.status != 'cancelled'
GROUP BY DATE(cp.procedure_date)
ORDER BY procedure_date DESC;
```

### 6. Patient-wise Implant Traceability
```sql
SELECT 
    p.name,
    p.patient_id_card,
    pa.ip_no,
    cit.batch_id,
    cbm.batch_number,
    cbm.expiry_date,
    csi.item_name,
    cit.quantity_used,
    u.name AS implanted_by,
    cit.implant_site,
    cit.created_at
FROM cathlab_implant_traceability cit
JOIN patient p ON cit.patient_id = p.id
JOIN patient_admission pa ON cit.admission_id = pa.id
JOIN cathlab_batch_master cbm ON cit.batch_id = cbm.id
JOIN cathlab_store_items csi ON cit.item_id = csi.id
JOIN users u ON cit.implanted_by = u.id
WHERE p.id = [PATIENT_ID]
ORDER BY cit.created_at DESC;
```

### 7. Low Stock Alert
```sql
SELECT 
    csi.item_code,
    csi.item_name,
    SUM(cbm.available_quantity) AS available,
    csi.reorder_level,
    CASE 
        WHEN SUM(cbm.available_quantity) <= 0 THEN 'OUT_OF_STOCK'
        WHEN SUM(cbm.available_quantity) <= (csi.reorder_level / 2) THEN 'CRITICAL'
        WHEN SUM(cbm.available_quantity) <= csi.reorder_level THEN 'LOW_STOCK'
    END AS status
FROM cathlab_store_items csi
LEFT JOIN cathlab_batch_master cbm ON csi.id = cbm.item_id AND cbm.status = 'active'
WHERE csi.store_id = [CATHLAB_STORE_ID] AND csi.status = 'active'
GROUP BY csi.id
HAVING SUM(cbm.available_quantity) <= csi.reorder_level
ORDER BY status, available;
```

---

## PHP Implementation Files

### Core Files Created
1. **CATHLAB_SCHEMA.sql** - Database schema with all tables
2. **cathlab_grn.php** - GRN entry form
3. **cathlab_procedure.php** - Procedure entry form
4. **controller/processCathLab.php** - Backend logic
5. **ajax/searchAdmission.php** - Admission search
6. **ajax/getBatches.php** - Batch selection

### Integration Points
- Existing **store_grn** table extended with store_id
- Existing **patient_admission** table extended for CathLab linking
- Existing **billing_item_detail** table extended for batch tracking

---

## Workflow Integration with Existing Billing

### Current Billing Flow
```
IP/Day Care Patient
    ↓
Admission Created
    ↓
Services & Items Added to Bill
    ↓
Bill Finalized
```

### Enhanced with CathLab
```
IP Patient Admitted (Pre-op/CathLab ward)
    ↓
CathLab Procedure Created (Items Consumed)
    ↓
Items Added to Patient Bill (via procedure link)
    ↓
Bill Finalized
    ↓
CathLab Stock Deducted + Batch Traceability Locked
```

### Billing Item Integration Code
```php
// When adding procedure items to bill
$procedure_query = "SELECT cp.id, cpi.item_id, cpi.batch_id, cpi.quantity_consumed, 
                           csi.selling_price, csi.cost_price
                    FROM cathlab_procedure cp
                    JOIN cathlab_procedure_items cpi ON cp.id = cpi.procedure_id
                    JOIN cathlab_store_items csi ON cpi.item_id = csi.id
                    WHERE cp.id = ?";

// Insert into billing_item_detail with CathLab references
$bill_insert = "INSERT INTO billing_item_detail 
                (billing_header_id, item_id, quantity, rate, amount, cathlab_procedure_id, batch_id, is_cathlab_item)
                VALUES (?, ?, ?, ?, ?, ?, ?, 1)";
```

---

## Installation Steps

1. **Execute Database Schema**
   ```
   Run CATHLAB_SCHEMA.sql in MySQL
   ```

2. **Create CathLab Store Record**
   ```sql
   INSERT INTO store (store_name, store_type, is_independent, status, created_by)
   VALUES ('CathLab Store', 'cathlab', 1, 'active', 1);
   ```

3. **Create CathLab Items**
   - Use cathlab_store_items table
   - Add consumables (catheters, balloons, guides)
   - Add implants (stents, wires)

4. **Create CathLab Users**
   - Assign users to CathLab department
   - Grant permissions for GRN, Procedure, Billing

5. **Test Workflow**
   - Create test GRN
   - Create test procedure
   - Create test billing

---

## Testing Checklist

- [ ] GRN created with batch tracking
- [ ] Batch master record created
- [ ] Stock added to inventory
- [ ] Procedure created with items
- [ ] Stock reserved correctly
- [ ] Billing integrates with existing module
- [ ] Stock deducted on bill finalization
- [ ] Batch traceability maintained
- [ ] Reports generating correctly

---

## Future Enhancements

1. **Auto GRN Creation from PO**
2. **Expiry Alerts & Auto-Discard**
3. **CathLab Dashboard**
4. **Mobile App Integration**
5. **Supplier Performance Reports**
6. **Multi-level Approval Workflow**

