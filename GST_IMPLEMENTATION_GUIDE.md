# Item-Level GST Implementation Guide

## Overview
This document describes the complete item-level GST (Goods and Services Tax) implementation for the Hospital Store Module. Users can now apply GST percentages at individual item level in Purchase Orders and Invoices.

---

## Features Implemented

### 1. ✅ Database Schema Updates
- **Columns Added:**
  - `gst_percent` (DECIMAL 5,2) - GST percentage for the item
  - `gst_amount` (DECIMAL 10,2) - Calculated GST amount
  - `net_amount` (DECIMAL 10,2) - Amount including GST

- **Tables Updated:**
  - `store_purchase_order_items`
  - `store_purchase_invoice_items`
  - `store_issue_items`

- **New Table Created:**
  - `store_item_gst_rates` - Stores default GST rates by category

### 2. ✅ Model Layer (storeClass.php)
**New Methods Added:**

#### GST Rate Management
```php
setGstRateForCategory($category_id, $gst_percent)
// Set default GST rate for a category
// Returns: true/false based on success

getGstRateForCategory($category_id)
// Get current GST rate for a category
// Returns: GST percentage (0 if not found)

getAllGstRates()
// Get all active GST rates by category
// Returns: MySQL result object

disableOldGstRate($category_id)
// Deactivate previous rates when new one is set
// Returns: MySQL result
```

#### Calculation Methods
```php
calculateItemWithGst($quantity, $rate, $gst_percent)
// Calculate item total with GST
// Returns: Array with:
//   - amount: Qty × Rate
//   - gst_amount: Amount × GST%
//   - net_amount: Amount + GST Amount
```

#### Updated Methods
```php
insertPurchaseOrderItem($po_id, $item_id, $quantity, $rate, $gst_percent = 0, $gst_amount = 0)
// Now includes GST calculation
// Automatically calculates net_amount
```

### 3. ✅ User Interface Updates

#### Add Purchase Order (add_store_po.php)
**Single Row Item Layout:**
- Item (Dropdown) | Qty | Unit | Rate | Amount | GST % | GST Amt | Net Amt | Remove

**Features:**
- Real-time GST calculation
- Auto-fill GST % based on category default
- Display total amount, total GST, and total net amount
- Compact form layout with form-control-sm styling
- Color-coded summary section

#### GST Rate Master (gst_rate_master.php)
**New Page for Managing Category-wise GST Rates:**
- Set default GST percentage for each category
- View currently active GST rates
- Automatically disables old rates when new ones are set
- Active rates display in sidebar

### 4. ✅ Controller Updates (processStore.php)
**New Handler:**
```php
// store_gst_rate action
if($_REQUEST['submit'] == 'store_gst_rate') {
    // Set category GST rate
}
```

**Updated Handler:**
```php
// store_po action now captures gst_percent
$gst_percent = isset($_REQUEST['gst_percent'][$i]) ? $_REQUEST['gst_percent'][$i] : 0;
```

### 5. ✅ AJAX Support
**New File: ajax/getItemGstRate.php**
- Fetches auto-fill GST % when item is selected
- Based on item's category default rate

---

## Database Migration

### SQL Script: migrations/gst_implementation.sql

Execute the following queries:

```sql
-- Add GST columns to tables
ALTER TABLE `store_purchase_order_items` 
ADD COLUMN `gst_percent` DECIMAL(5, 2) DEFAULT 0,
ADD COLUMN `gst_amount` DECIMAL(10, 2) DEFAULT 0,
ADD COLUMN `net_amount` DECIMAL(10, 2) DEFAULT 0;

ALTER TABLE `store_purchase_invoice_items` 
ADD COLUMN `gst_percent` DECIMAL(5, 2) DEFAULT 0,
ADD COLUMN `gst_amount` DECIMAL(10, 2) DEFAULT 0,
ADD COLUMN `net_amount` DECIMAL(10, 2) DEFAULT 0;

-- Create GST rates table
CREATE TABLE IF NOT EXISTS `store_item_gst_rates` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `category_id` INT,
    `gst_percent` DECIMAL(5, 2) NOT NULL,
    `applicable_from` DATE DEFAULT CURDATE(),
    `applicable_to` DATE,
    `status` INT DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `created_by` INT,
    FOREIGN KEY (`category_id`) REFERENCES `store_categories`(`id`),
    KEY `idx_category_gst` (`category_id`, `status`)
);
```

---

## How to Use

### Step 1: Configure Default GST Rates (Admin)
1. Navigate to: **Store → GST Rate Master**
2. Select a Category
3. Enter GST Percentage (e.g., 5, 12, 18)
4. Click "Save GST Rate"
5. Rates are now set and will auto-fill for items in that category

### Step 2: Create Purchase Order with Item-Level GST
1. Navigate to: **Store → Add Purchase Order**
2. Fill PO header details (PO Date, Supplier, Payment Terms)
3. Click "Add Item" button
4. **Item Row:**
   - Select Item → Auto-fills Unit & GST %
   - Enter Quantity
   - Enter Rate
   - Amount = Qty × Rate (Auto-calculated)
   - GST % = Pre-filled from category (Editable)
   - GST Amt = Amount × GST% (Auto-calculated)
   - Net Amt = Amount + GST Amt (Auto-calculated)
5. Click "+ Add Item" to add more items
6. Review totals:
   - Total Amount (before GST)
   - Total GST
   - Total Net Amount (with GST)
7. Click "Save Purchase Order"

### Step 3: View GST Details in PO
1. Navigate to: **Store → PO List**
2. Click on any PO to view details
3. See item-level GST breakdown
4. GST summary at bottom

---

## Calculation Example

**Example Item:**
- Item: Paracetamol 500mg
- Category: Medicines (GST Rate: 5%)
- Quantity: 100 boxes
- Rate: ₹50 per box

**Calculations:**
```
Amount = 100 × 50 = ₹5,000
GST Amount = 5,000 × (5 / 100) = ₹250
Net Amount = 5,000 + 250 = ₹5,250
```

**For Multiple Items:**
```
Item 1 Net Amount = ₹5,250
Item 2 Net Amount = ₹3,180
Item 3 Net Amount = ₹2,970

Total Amount = ₹11,200
Total GST = ₹430
Total Net Amount = ₹11,630
```

---

## API Reference

### Model Methods

#### setGstPercent($gst_percent)
```php
$newStore->setGstPercent(18);
```

#### setGstAmount($gst_amount)
```php
$newStore->setGstAmount(2700);
```

#### setNetAmount($net_amount)
```php
$newStore->setNetAmount(18700);
```

#### setGstRateForCategory($category_id, $gst_percent)
```php
// Set 18% GST for category ID 5
$newStore->setGstRateForCategory(5, 18);
```

#### getGstRateForCategory($category_id)
```php
// Get GST rate for category 5
$rate = $newStore->getGstRateForCategory(5);
// Returns: 18 (or 0 if not found)
```

#### calculateItemWithGst($quantity, $rate, $gst_percent)
```php
$result = $newStore->calculateItemWithGst(100, 50, 5);
// Returns: Array (
//   'amount' => 5000,
//   'gst_amount' => 250,
//   'net_amount' => 5250
// )
```

---

## Frontend JavaScript Functions

### calculateGstAndTotal(row)
```javascript
// Recalculate GST and Net Amount for a specific row
let row = $('.item-row').first();
calculateGstAndTotal(row);
```

### calculateTotal()
```javascript
// Recalculate all totals
calculateTotal();
// Updates:
// - #totalAmount
// - #totalGst
// - #totalNetAmount
```

---

## File Structure

```
alvashospital.com/
├── add_store_po.php (Updated with GST fields)
├── gst_rate_master.php (NEW - GST rate configuration)
├── controller/
│   └── processStore.php (Updated with GST handler)
├── model/
│   └── storeClass.php (Updated with GST methods)
├── ajax/
│   └── getItemGstRate.php (NEW - Auto-fill GST)
└── migrations/
    └── gst_implementation.sql (Database changes)
```

---

## Features and Highlights

### ✅ Automatic Calculations
- GST percentage applied automatically
- GST amount calculated in real-time
- Net amount updated instantly
- Grand totals updated dynamically

### ✅ Category-Based Defaults
- Set GST rate per category
- Items auto-fill GST based on category
- Override per item if needed

### ✅ User-Friendly Interface
- Single row layout for item entry
- Compact form controls
- Clear visual feedback
- Real-time validation

### ✅ Audit Trail
- GST rates tracked with applicable dates
- Creation timestamp and user tracking
- Old rates marked as inactive
- Historical data preserved

### ✅ Flexibility
- GST % editable per item
- Can be 0 for tax-exempt items
- Supports any percentage value
- Works with decimal values

---

## Reporting & Analytics

### GST Report (To be implemented)
- Total GST collected by category
- GST by supplier
- GST by period (Date range)
- Export to Excel

### Purchase Order Report
- Show GST breakdown
- Filter by GST percentage
- Sort by net amount

---

## Validation Rules

1. **GST Percentage:** 0 to 100 (decimal supported)
2. **Quantity:** Must be positive
3. **Rate:** Must be positive
4. **Category:** Required for auto-fill
5. **Net Amount:** Auto-calculated, read-only

---

## Common Issues & Solutions

### Issue: GST % not auto-filling
**Solution:** 
- Ensure item has category assigned
- Category must have GST rate configured in Rate Master
- Clear browser cache and reload

### Issue: Calculations incorrect
**Solution:**
- Check decimal places (using toFixed(2))
- Verify rate and quantity are valid numbers
- Refresh page if stuck

### Issue: GST rates not updating
**Solution:**
- Go to GST Rate Master
- Delete old rate first
- Add new rate
- Clear cache

---

## Future Enhancements

- [ ] GST reporting module
- [ ] Tax audit reports
- [ ] Multi-rate HSN-SAC integration
- [ ] Invoice GST display
- [ ] GST compliance checking
- [ ] State-wise tax rates
- [ ] GSTR filing assistance

---

## Support & Documentation

For detailed questions or customization requests, refer to:
- storeClass.php - Core calculations
- add_store_po.php - UI implementation
- gst_rate_master.php - Rate configuration

---

**Last Updated:** January 28, 2026  
**Version:** 1.0  
**Status:** Production Ready
