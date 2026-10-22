# 🏥 HOSPITAL STORE MODULE - IMPLEMENTATION GUIDE

## ✅ Completed Files

### 1. Database Schema
- ✅ `store_module_schema.sql` - Complete database structure with all 11 tables

### 2. Model Layer
- ✅ `model/storeClass.php` - Complete MVC model with all CRUD operations and stock management logic

### 3. Controller
- ✅ `controller/processStore.php` - Handles all form submissions

### 4. Master Data Forms (COMPLETED)
- ✅ `add_store_category.php` - Category Master with list
- ✅ `add_store_unit.php` - Unit Master with list  
- ✅ `add_store_supplier.php` - Supplier Master with list
- ✅ `add_store_item.php` - Item Master with list

---

## 📋 Remaining Files to Create

### 5. Purchase Order Module
**File: `add_store_po.php`**
```php
Key Features:
- Auto-generate PO Number (PO26XXXXX)
- Select Supplier
- Add multiple items dynamically
- Calculate totals
- Submit to controller/processStore.php
```

**File: `store_po_list.php`**
```php
Features:
- List all Purchase Orders
- Filter by status (PENDING, RECEIVED, CANCELLED)
- View PO details
- Print PO
```

### 6. GRN (Goods Receipt Note) Module  
**File: `add_store_grn.php`**
```php
🔥 CRITICAL FEATURES:
- Auto-generate GRN Number (GRN26XXXXX)
- Link to Purchase Order (optional)
- Enter batch numbers and expiry dates
- Serial numbers for assets
- AUTOMATICALLY UPDATES:
  ✓ store_stock (increases available_qty)
  ✓ store_stock_ledger (qty_in entry)
  ✓ PO status to RECEIVED
```

**File: `store_grn_list.php`**
```php
Features:
- List all GRNs with supplier details
- View GRN items
- Print GRN
```

### 7. Issue Module
**File: `add_store_issue.php`**
```php
🔥 CRITICAL FEATURES:
- Auto-generate Issue Number (IS26XXXXX)
- Select Department
- Select items from available stock
- Choose batch if multiple batches exist
- AUTOMATICALLY UPDATES:
  ✓ store_stock (reduces available_qty)
  ✓ store_stock_ledger (qty_out entry)
```

**File: `store_issue_list.php`**
```php
Features:
- List all issues by department
- View issue details
- Print issue slip
```

### 8. Stock Management
**File: `add_store_adjustment.php`**
```php
Features:
- Adjust stock (ADD/REMOVE)
- Select item and batch
- Enter reason
- AUTOMATICALLY UPDATES:
  ✓ store_stock
  ✓ store_stock_ledger
```

**File: `store_stock_report.php`**
```php
Key Reports:
1. Current Stock Report
2. Low Stock Items (below reorder level)
3. Expiring Items (within 90 days)
4. Stock by Category
```

**File: `store_ledger_report.php`**
```php
Features:
- Complete transaction history
- Filter by item, date range
- Shows GRN, ISSUE, RETURN, ADJUSTMENT
- Running balance column
```

---

## 🔥 CRITICAL STOCK LOGIC

### GRN Stock Update Logic (in storeClass.php - ALREADY IMPLEMENTED)
```php
function updateStockOnGRN($item_id, $batch_no, $expiry_date, $quantity, $grn_id) {
    // 1. Check if stock exists for item+batch
    // 2. If exists: UPDATE available_qty = available_qty + quantity
    // 3. If not: INSERT new stock record
    // 4. INSERT into store_stock_ledger with qty_in
    // 5. Store current balance
}
```

### Issue Stock Update Logic (in storeClass.php - ALREADY IMPLEMENTED)
```php
function updateStockOnIssue($item_id, $batch_no, $quantity, $issue_id) {
    // 1. UPDATE store_stock SET available_qty = available_qty - quantity
    // 2. Get current balance
    // 3. INSERT into store_stock_ledger with qty_out
}
```

---

## 📊 Sample Ajax Scripts for Dynamic Forms

### For Purchase Order - Add Item Row
```javascript
function addPORow() {
    var rowCount = $('#po_table tr').length;
    var html = '<tr>' +
        '<td>' + rowCount + '</td>' +
        '<td><select name="item_id[]" class="form-control item-select" required>' +
        '<option value="">Select Item</option>' +
        // Populate from PHP
        '</select></td>' +
        '<td><input type="number" name="quantity[]" class="form-control qty" required></td>' +
        '<td><input type="number" name="rate[]" class="form-control rate" step="0.01" required></td>' +
        '<td><input type="text" name="amount[]" class="form-control amount" readonly></td>' +
        '<td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">X</button></td>' +
        '</tr>';
    $('#po_table tbody').append(html);
}

// Calculate amount on quantity or rate change
$(document).on('input', '.qty, .rate', function() {
    var row = $(this).closest('tr');
    var qty = parseFloat(row.find('.qty').val()) || 0;
    var rate = parseFloat(row.find('.rate').val()) || 0;
    var amount = qty * rate;
    row.find('.amount').val(amount.toFixed(2));
    calculateTotal();
});
```

### For GRN - Get Stock by Batch
```javascript
$(document).on('change', '.item-select', function() {
    var item_id = $(this).val();
    var row = $(this).closest('tr');
    
    $.ajax({
        url: 'ajax/get_item_stock.php',
        method: 'POST',
        data: {item_id: item_id},
        success: function(response) {
            var data = JSON.parse(response);
            // Populate batch dropdown or enable batch input
            if(data.is_expiry_required == 1) {
                row.find('.expiry-field').show();
            }
            if(data.is_serial_required == 1) {
                row.find('.serial-field').show();
            }
        }
    });
});
```

---

## 🗂️ Menu Structure to Add in leftbar.php

```php
<li>
    <a class="has-arrow" href="javascript:void()" aria-expanded="false">
        <i class="icon icon-layout-25"></i>
        <span class="nav-text">Store Module</span>
    </a>
    <ul aria-expanded="false">
        <li><a href="add_store_category.php">Category Master</a></li>
        <li><a href="add_store_unit.php">Unit Master</a></li>
        <li><a href="add_store_supplier.php">Supplier Master</a></li>
        <li><a href="add_store_item.php">Item Master</a></li>
        <li><a href="add_store_po.php">Purchase Order</a></li>
        <li><a href="store_po_list.php">PO List</a></li>
        <li><a href="add_store_grn.php">Goods Receipt (GRN)</a></li>
        <li><a href="store_grn_list.php">GRN List</a></li>
        <li><a href="add_store_issue.php">Issue to Department</a></li>
        <li><a href="store_issue_list.php">Issue List</a></li>
        <li><a href="add_store_adjustment.php">Stock Adjustment</a></li>
        <li><a href="store_stock_report.php">Stock Report</a></li>
        <li><a href="store_ledger_report.php">Stock Ledger</a></li>
    </ul>
</li>
```

---

## 🚀 Installation Steps

### Step 1: Import Database
```sql
-- Run this in phpMyAdmin or MySQL command line
source /path/to/store_module_schema.sql
```

### Step 2: Verify Model File
- Ensure `model/storeClass.php` is in place
- Check database connection in `model/dbconnect.php`

### Step 3: Test Master Forms
1. Go to `add_store_category.php` - Add 2-3 categories
2. Go to `add_store_unit.php` - Add units (Pieces, Box, Dozen)
3. Go to `add_store_supplier.php` - Add 1-2 suppliers
4. Go to `add_store_item.php` - Add items with category and unit

### Step 4: Create Remaining Files
You can use the above code structure to create:
- Purchase Order files
- GRN files
- Issue files
- Reports

---

## 🎯 Key Differences from Pharmacy Module

1. **Batch Management**: Optional (only if item requires expiry)
2. **Serial Numbers**: For assets (equipment, IT items)
3. **Issue to Departments**: Instead of patient sales
4. **No Payment**: Store items are issued, not sold
5. **Asset Tracking**: ASSET type items tracked separately
6. **Stock Ledger**: Complete audit trail with running balance

---

## 📞 Support Queries

### Q: How to handle items without batch/expiry?
A: Set `is_expiry_required = 0` in item master. Batch and expiry fields will be NULL in stock table.

### Q: Can I issue items without GRN?
A: No. You must first receive items via GRN to create stock. Then issue from available stock.

### Q: How to check current stock?
A: Query `store_stock` table or use `getCurrentStock()` method from storeClass.

### Q: Can I delete a GRN?
A: Not recommended after stock is issued. Use stock adjustment to correct mistakes.

---

## ✅ Testing Checklist

- [ ] Database tables created successfully
- [ ] Category master working (Add/Edit/List)
- [ ] Unit master working
- [ ] Supplier master working
- [ ] Item master working
- [ ] Purchase Order creation
- [ ] GRN with stock update
- [ ] Issue with stock deduction
- [ ] Stock ledger showing all transactions
- [ ] Low stock alert working
- [ ] Expiring items report working

---

## 🔐 Security Notes

1. All forms use POST method
2. Session checking in every PHP file
3. SQL injection protected via prepared statements (upgrade recommended)
4. User ID tracked in created_by, issued_by fields
5. Stock changes logged with transaction type and reference

---

**Generated on:** 14 January 2026  
**Module Status:** Core Implementation Complete ✅  
**Remaining Work:** Transaction Forms (PO, GRN, Issue) + Reports

For immediate use, you have:
- ✅ Complete Database Schema
- ✅ Complete Model with all stock logic
- ✅ All Master Data entry forms
- ✅ Controller to handle submissions

Next steps: Create the transaction entry forms (PO, GRN, Issue) using the patterns from `add_store_category.php` and dynamic row addition like in your existing `add_purchase.php`.
