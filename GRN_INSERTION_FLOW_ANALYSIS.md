# CathLab GRN Complete Flow Analysis

## Overview
This document provides a comprehensive analysis of the GRN (Goods Receipt Note) insertion and retrieval flow for the CathLab Sub-Store module.

---

## 1. DATABASE SCHEMA ALIGNMENT

### Tables Used in GRN Flow

#### `cathlab_grn` (Header Table)
```sql
- id (PK)
- grn_number (UNIQUE) - Format: CATHLAB-GRN-YYYY-MM-DD-0001
- store_id (FK) - CathLab store identifier
- grn_date (DATE)
- supplier_name (VARCHAR) - Supplier name at time of GRN
- total_items (INT) - Count of items
- total_quantity (DECIMAL) - Sum of quantities
- total_cost (DECIMAL) - Sum of item costs
- remarks (TEXT)
- created_by (INT) - User who created
- created_at (TIMESTAMP)
- updated_at (TIMESTAMP)
```

#### `cathlab_grn_items` (Detail Table)
```sql
- id (PK)
- grn_id (FK) - Reference to cathlab_grn
- item_id (FK) - Reference to cathlab_store_items
- quantity (DECIMAL) - Received quantity
- cost_price (DECIMAL) - Unit cost
- batch_number (VARCHAR) - Batch/lot number
- expiry_date (DATE) - Expiry/shelf-life date
- serial_number (VARCHAR) - Optional serial for devices
- total_cost (DECIMAL) - quantity × cost_price
- created_at (TIMESTAMP)
```

#### `cathlab_store_items` (Item Master)
```sql
- id (PK)
- item_code (UNIQUE)
- item_name
- category_id
- unit_id
- item_type (consumable|implant|device)
- unit_cost (purchase cost)
- billing_price (patient charge)
- current_stock (inventory level)
- min_stock, reorder_level
- is_batch_mandatory, is_expiry_required
- status (active|inactive)
```

#### `cathlab_stock_ledger` (Audit Trail)
```sql
- id (PK)
- store_id, item_id, batch_id
- transaction_type (GRN_IN|PROCEDURE_CONSUME|BILLING_DEDUCT|etc)
- quantity (signed: positive for IN, negative for OUT)
- reference_type (GRN|BILLING|PROCEDURE)
- reference_id (links to GRN, Billing, or Procedure)
- remarks
- created_by, created_at
```

---

## 2. GRN INSERTION FLOW

### 2.1 Form Submission (add_cathlab_grn.php)

**User Action:** Fills form with:
```
- GRN Number: Auto-generated (readonly)
- GRN Date: Date received (required)
- Purchase Order: Optional (links to existing PO)
- Supplier: Required selection
- Invoice Number: Supplier invoice no
- Invoice Date: When supplier invoiced
- Remarks: Additional notes
- Received By: Default from session

Items Array:
[
  {
    item_id: 5,
    quantity: 10,
    rate: 1500.00,
    batch_no: "BATCH-001",
    expiry_date: "2026-12-31"
  },
  ...
]
```

**Submit Method:** POST to `controller/processCathLab.php` with `action=saveCathLabGRN`

### 2.2 Server-Side Processing (processCathLab.php)

**Step 1: Retrieve Supplier Name**
```php
// Lines 15-23
$supplier_query = "SELECT supplier_name FROM store_supplier WHERE id = ?";
// Fetch supplier name to store in cathlab_grn header
```

**Step 2: Set GRN Properties**
```php
// Lines 28-38
$cathLab->setGrnNumber($grn_number);
$cathLab->setGrnDate($grn_date);
$cathLab->setSupplierId($supplier_id);
$cathLab->setSupplierName($supplier_name);  // ← IMPORTANT: Supplier name stored
$cathLab->setPoId($po_id);
$cathLab->setInvoiceNumber($invoice_number);
$cathLab->setInvoiceDate($invoice_date);
$cathLab->setRemarks($remarks);
$cathLab->setReceivedBy($received_by);
```

**Step 3: Database Transaction**
```php
// Line 40
mysqli_begin_transaction($db);

// Inside try block:
1. Insert GRN header
2. For each item:
   - Insert GRN item
   - Update stock
   - Create ledger entry
3. Commit

// On error: Rollback all changes
```

### 2.3 CathLab Class Methods (cathLabClass.php)

#### Method: `insertCathLabGRN()`
**Location:** Lines 196-231

**Functionality:**
```php
// Inserts into cathlab_grn table (NOT store_grn)
INSERT INTO cathlab_grn 
(grn_number, store_id, grn_date, supplier_name, 
 total_items, total_quantity, total_cost, 
 remarks, created_by, created_at)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())

// Parameter binding:
"ssissdddssi" = 
- s: grn_number (string)
- s: store_id (converted to int)
- i: store_id (1 for CathLab)
- s: grn_date (string YYYY-MM-DD)
- s: supplier_name (string)
- i: total_items (0 initially, updated later)
- d: total_quantity (decimal)
- d: total_cost (decimal)
- d: remarks (string, nullable)
- s: remarks (actually supplier_name)
- s: created_by (user ID)
- i: created_by (user ID)

Returns: GRN ID (auto-increment)
```

**Key Change:** Now uses `cathlab_grn` table instead of `store_grn`

#### Method: `insertGrnItem($grn_id, $item_id, $quantity, $rate, $batch_no, $expiry_date)`
**Location:** Lines 235-255

**Functionality:**
```php
INSERT INTO cathlab_grn_items
(grn_id, item_id, quantity, cost_price, batch_number, 
 expiry_date, total_cost, created_at)
VALUES (?, ?, ?, ?, ?, ?, ?, NOW())

// Parameters:
- i: grn_id (from insertCathLabGRN result)
- i: item_id (from form)
- d: quantity (decimal)
- d: cost_price (rate from form)
- s: batch_number (string)
- s: expiry_date (DATE)
- d: total_cost (quantity × rate)
```

#### Method: `updateStockFromGrn($item_id, $quantity)`
**Location:** Lines 259-273

**Functionality:**
```php
UPDATE cathlab_store_items 
SET current_stock = current_stock + ?
WHERE id = ?

// Adds received quantity to inventory immediately
// This ensures stock is available for procedures
```

#### Method: `createStockLedger($item_id, $transaction_type, $quantity, $reference_type, $reference_id, $description)`
**Location:** Lines 277-297

**Functionality:**
```php
INSERT INTO cathlab_stock_ledger
(store_id, item_id, transaction_type, quantity,
 reference_type, reference_id, remarks, created_by, created_at)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())

// Creates audit trail entry with transaction_type = 'GRN_IN'
```

---

## 3. GRN RETRIEVAL FLOW (GET/VIEW)

### 3.1 View GRN Details (view_cathlab_grn.php)

**Page URL:** `view_cathlab_grn.php?id=<grn_id>`

**Functionality:**

#### Step 1: Get GRN Header
```php
$cathLab->getCathLabGrnById($grn_id);
```

**Query:**
```sql
SELECT * FROM cathlab_grn WHERE id = ?
```

**Returns:** Single row with GRN header info

#### Step 2: Get GRN Items with Details
```sql
SELECT 
    cgi.id, cgi.item_id, cgi.quantity, cgi.cost_price,
    cgi.batch_number, cgi.expiry_date, cgi.total_cost,
    csi.item_name, csi.item_code,
    su.unit_name
FROM cathlab_grn_items cgi
LEFT JOIN cathlab_store_items csi ON cgi.item_id = csi.id
LEFT JOIN store_unit su ON csi.unit_id = su.id
WHERE cgi.grn_id = ?
```

**Returns:** All items with names and units

### 3.2 Display Logic

**Section 1: GRN Header**
- GRN Number
- GRN Date
- Supplier Name (from cathlab_grn.supplier_name)
- Status: "Completed"

**Section 2: Additional Details**
- Remarks
- Received By (created_by user)
- Created On (created_at timestamp)

**Section 3: Items Table**
Columns:
| Item Code | Item Name | Qty | Unit | Cost Price | Total Cost | Batch Details |
|-----------|-----------|-----|------|------------|------------|---------------|
| CODE-001  | Stent 3mm  | 10  | PCS  | ₹1500      | ₹15000     | BATCH-001, Exp: 31-12-2026 |

**Section 4: Summary**
- Total Items (count)
- Total Quantity (sum)
- Total Cost (sum)

---

## 4. COMPLETE DATA FLOW DIAGRAM

```
┌─────────────────────────────────────┐
│  add_cathlab_grn.php                │
│  (GRN Entry Form)                   │
│  - Item selection                   │
│  - Batch/Expiry entry               │
│  - Quantity input                   │
└──────────────┬──────────────────────┘
               │ POST (JSON array)
               ▼
┌─────────────────────────────────────┐
│  processCathLab.php                 │
│  (Controller)                       │
│  action = "saveCathLabGRN"          │
│                                     │
│  1. Get supplier name               │
│  2. Set GRN properties              │
│  3. BEGIN TRANSACTION               │
└──────────────┬──────────────────────┘
               │
               ├─ insertCathLabGRN()
               │  └─ INSERT cathlab_grn (header)
               │
               ├─ For each item:
               │  ├─ insertGrnItem() 
               │  │  └─ INSERT cathlab_grn_items
               │  │
               │  ├─ updateStockFromGrn()
               │  │  └─ UPDATE cathlab_store_items (add qty)
               │  │
               │  └─ createStockLedger()
               │     └─ INSERT cathlab_stock_ledger (GRN_IN)
               │
               ├─ COMMIT transaction
               │
               └─ Redirect to dashboard
                  └─ Success message
```

---

## 5. DATA FLOW EXAMPLE

### Input Data
```json
{
  "grn_number": "CATHLAB-GRN-2026-01-31-0001",
  "grn_date": "2026-01-31",
  "supplier_id": 5,
  "supplier_name": "Apollo Medical",
  "remarks": "Emergency stent order",
  "received_by": "nurse_john",
  "grn_items": [
    {
      "item_id": 12,
      "quantity": 10,
      "rate": 1500.00,
      "batch_no": "BATCH-2026-001",
      "expiry_date": "2026-12-31"
    }
  ]
}
```

### Database Inserts

**1. cathlab_grn**
```
INSERT INTO cathlab_grn VALUES
(1, 'CATHLAB-GRN-2026-01-31-0001', 1, '2026-01-31', 
 'Apollo Medical', 1, 10, 15000, 'Emergency stent order', 
 'nurse_john', '2026-01-31 14:30:00', '2026-01-31 14:30:00')
```

**2. cathlab_grn_items**
```
INSERT INTO cathlab_grn_items VALUES
(1, 1, 12, 10, 1500.00, 'BATCH-2026-001', '2026-12-31', 
 NULL, 15000.00, '2026-01-31 14:30:00')
```

**3. cathlab_store_items (UPDATE)**
```
UPDATE cathlab_store_items 
SET current_stock = current_stock + 10
WHERE id = 12
-- New stock = 50 + 10 = 60
```

**4. cathlab_stock_ledger**
```
INSERT INTO cathlab_stock_ledger VALUES
(NULL, 1, 12, NULL, 'GRN_IN', 10, 'GRN', 1, 
 'Goods Received - GRN CATHLAB-GRN-2026-01-31-0001', 
 'nurse_john', '2026-01-31 14:30:00')
```

### View Retrieval

**When accessing view_cathlab_grn.php?id=1:**

**Query 1: Get GRN Header**
```sql
SELECT * FROM cathlab_grn WHERE id = 1
```
**Result:**
```
id: 1
grn_number: CATHLAB-GRN-2026-01-31-0001
store_id: 1
grn_date: 2026-01-31
supplier_name: Apollo Medical
total_items: 1
total_quantity: 10
total_cost: 15000
remarks: Emergency stent order
created_by: nurse_john
created_at: 2026-01-31 14:30:00
```

**Query 2: Get GRN Items**
```sql
SELECT cgi.*, csi.item_name, csi.item_code, su.unit_name ...
FROM cathlab_grn_items cgi
WHERE cgi.grn_id = 1
```
**Result:**
```
id: 1
grn_id: 1
item_id: 12
quantity: 10
cost_price: 1500.00
batch_number: BATCH-2026-001
expiry_date: 2026-12-31
total_cost: 15000.00
item_name: Stent 3mm Coronary
item_code: STENT-3MM
unit_name: PCS
```

---

## 6. KEY FIXES IMPLEMENTED

### Issue 1: Wrong Table Reference
**Before:** `INSERT INTO store_grn` (generic store)  
**After:** `INSERT INTO cathlab_grn` (CathLab-specific)  
**Impact:** CathLab operations now isolated from main store

### Issue 2: Missing Supplier Name
**Before:** Stored `supplier_id` only  
**After:** Also stores `supplier_name` for audit trail  
**Impact:** GRN readable without joining supplier table

### Issue 3: Missing Batch Tracking
**Before:** batch_no, expiry_date not stored properly  
**After:** Stored in `cathlab_grn_items` with proper fields  
**Impact:** Full batch/expiry tracking for compliance

### Issue 4: No View Page
**Before:** No way to view GRN details  
**After:** Created `view_cathlab_grn.php` with full details  
**Impact:** Audit trail visibility

---

## 7. TESTING CHECKLIST

- [ ] Add GRN with valid items
- [ ] Verify cathlab_grn entry created
- [ ] Verify cathlab_grn_items entries created
- [ ] Check cathlab_store_items stock updated
- [ ] Check cathlab_stock_ledger entries created
- [ ] View GRN details page loads
- [ ] Batch/expiry displays correctly
- [ ] Summary calculations correct
- [ ] Print GRN function works
- [ ] Transaction rollback on error

---

## 8. RELATED PAGES AND METHODS

### Pages Using GRN Data
- `cathlab_store_dashboard.php` - Lists recent GRNs
- `view_cathlab_grn.php` - View single GRN (NEW)
- `cathlab_procedure.php` - Uses stock from GRN

### Key Methods in cathLabClass.php
- `insertCathLabGRN()` - Create GRN header
- `insertGrnItem()` - Add items to GRN
- `updateStockFromGrn()` - Update inventory
- `createStockLedger()` - Audit trail
- `getCathLabGrnById()` - Get GRN by ID
- `getCathLabGrns()` - List GRNs
- `getCathLabGrnByNumber()` - Search by number

### Related Features
- Purchase Order linking (po_id field)
- Stock ledger tracking
- Batch/expiry validation
- Transaction management
