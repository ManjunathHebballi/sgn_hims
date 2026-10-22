# Hospital Store Module - Purchase Invoice & Payment System

## Overview

This document describes the complete Purchase Invoice and Supplier Payment system for maintaining accurate supplier ledgers.

---

## Database Tables

### 1. store_purchase_invoices

Stores invoice header information:

- **invoice_number**: Auto-generated unique number (format: INV-YYYYMMDD-001)
- **invoice_date**: Date of invoice
- **supplier_id**: Links to store_suppliers
- **grn_id**: Optional link to GRN (if invoice is based on GRN)
- **due_date**: Payment due date
- **payment_terms**: NET 15/30/45/60/IMMEDIATE
- **total_amount**: Sum of all item amounts
- **tax_amount**: Total tax amount
- **discount_amount**: Total discount amount
- **net_amount**: Final payable amount
- **paid_amount**: Amount paid so far
- **balance_amount**: Outstanding amount (net_amount - paid_amount)
- **payment_status**: UNPAID / PARTIALLY_PAID / PAID

### 2. store_purchase_invoice_items

Stores invoice line items:

- **invoice_id**: Links to store_purchase_invoices
- **item_id**: Links to store_items
- **batch_no**: Batch/Lot number
- **quantity**: Qty purchased
- **rate**: Unit rate
- **amount**: quantity × rate
- **tax_percent**: Tax percentage
- **tax_amount**: Calculated tax
- **discount_percent**: Discount percentage
- **discount_amount**: Calculated discount
- **net_amount**: Final amount (amount + tax - discount)

### 3. store_supplier_payments

Stores payment transactions:

- **payment_number**: Auto-generated (format: PAY-YYYYMMDD-001)
- **payment_date**: Date of payment
- **supplier_id**: Links to store_suppliers
- **payment_mode**: CASH/CHEQUE/BANK_TRANSFER/UPI/CARD
- **payment_amount**: Total payment amount
- **reference_number**: Cheque no / Transaction ID
- **bank_name**: Bank details (optional)
- **remarks**: Additional notes

### 4. store_payment_allocations

Links payments to specific invoices:

- **payment_id**: Links to store_supplier_payments
- **invoice_id**: Links to store_purchase_invoices
- **allocated_amount**: Amount allocated to this invoice

### 5. vw_supplier_ledger (View)

Unified view combining invoices and payments:

```sql
-- Debit side: Invoices
SELECT supplier_id, supplier_name, transaction_date, transaction_number,
       'INVOICE' as transaction_type, net_amount as debit, 0 as credit
FROM store_purchase_invoices

UNION ALL

-- Credit side: Payments
SELECT supplier_id, supplier_name, payment_date, payment_number,
       'PAYMENT' as transaction_type, 0 as debit, payment_amount as credit
FROM store_supplier_payments
```

---

## File Structure

### Forms (Data Entry)

1. **add_store_invoice.php** - Create new purchase invoices
2. **add_store_payment.php** - Record supplier payments

### Listing Pages

3. **store_invoice_list.php** - View all invoices with filters
4. **store_payment_list.php** - View all payments

### Reports

5. **supplier_ledger_report.php** - Supplier-wise ledger with running balance

### Backend

6. **model/storeClass.php** - Data access methods (15+ methods)
7. **controller/processStore.php** - Form submission handlers
8. **ajax/get_unpaid_invoices.php** - Load unpaid invoices for payment allocation

---

## Features

### Purchase Invoice Entry (add_store_invoice.php)

**Header Section:**

- Auto-generated invoice number
- Invoice date with default today's date
- Supplier dropdown (active suppliers only)
- Payment terms (NET 15/30/45/60/IMMEDIATE)
- Auto-calculated due date based on payment terms
- Optional GRN linking
- Remarks field

**Items Section:**

- Dynamic row addition/removal
- Item selection dropdown
- Batch number entry
- Quantity, Rate, Amount calculation
- Tax % and Tax Amount
- Discount % and Discount Amount
- Net Amount per line
- Real-time total calculations

**Calculations:**

```javascript
Amount = Quantity × Rate
Tax Amount = Amount × (Tax% / 100)
Discount Amount = Amount × (Discount% / 100)
Net Amount = Amount + Tax Amount - Discount Amount

Grand Total = Sum of all Net Amounts
```

**Validation:**

- At least one item required
- Supplier must be selected
- All items must have valid item selection
- Quantities and rates must be positive

---

### Supplier Payment Entry (add_store_payment.php)

**Payment Details:**

- Auto-generated payment number
- Payment date
- Supplier selection with outstanding balance display
- Payment mode dropdown
- Reference number (cheque/transaction ID)
- Total payment amount
- Remarks

**Invoice Allocation:**

- Shows all UNPAID and PARTIALLY_PAID invoices for selected supplier
- Displays: Invoice No, Date, Due Date, Invoice Amt, Paid Amt, Balance, Status
- Checkboxes to select invoices for allocation
- Auto-allocation feature:
  - When invoices are checked, payment amount is auto-distributed
  - Pays off oldest invoices first
  - Shows remaining unallocated amount
- Manual override: Can manually enter allocation amounts
- Summary section shows:
  - Total payment amount
  - Allocated amount
  - Remaining amount (with color coding)

**Validation:**

- Payment amount must be > 0
- Allocated amount cannot exceed payment amount
- Allocated amount per invoice cannot exceed invoice balance
- If no invoices selected, payment recorded as advance (with confirmation)

**Auto-Update Logic:**
After payment allocation:

```php
// For each invoice receiving payment:
1. Update paid_amount = paid_amount + allocated_amount
2. Calculate new balance_amount = net_amount - paid_amount
3. Update payment_status based on balance:
   - If balance = 0: PAID
   - If balance > 0 and balance < net_amount: PARTIALLY_PAID
   - If balance = net_amount: UNPAID
```

---

### Invoice List (store_invoice_list.php)

**Display:**

- All invoices with pagination (DataTables)
- Columns: S.No, Invoice No, Date, Supplier, Due Date, Invoice Amt, Paid Amt, Balance, Status, Actions
- Status badges:
  - UNPAID: Red badge
  - PARTIALLY_PAID: Yellow badge
  - PAID: Green badge
- Overdue invoices highlighted with red background

**Filters:**

- Filter by payment status (All/Unpaid/Partially Paid/Paid)
- Filter by supplier
- Date range filter (via DataTables search)

**Actions:**

- View invoice details
- Print invoice
- Edit invoice (if unpaid)

---

### Payment List (store_payment_list.php)

**Display:**

- All payments with pagination
- Columns: S.No, Payment No, Date, Supplier, Mode, Reference No, Amount, Actions
- Payment mode displayed as badge

**Filters:**

- Filter by supplier
- Date range filter

**Actions:**

- View payment details (shows allocated invoices)
- Print payment receipt

---

### Supplier Ledger Report (supplier_ledger_report.php)

**Filters:**

- Supplier selection (required)
- Date range (from/to dates)
- Default: Current month

**Display:**

- Supplier details header (name, contact, address, current outstanding)
- Ledger table with columns:
  - Date
  - Transaction No (Invoice or Payment number)
  - Particulars (description)
  - Debit (Invoices) - Red
  - Credit (Payments) - Green
  - Running Balance
  - Status
  - View action

**Calculations:**

```
Opening Balance = Previous period balance (if any)
Debit = Invoice amounts
Credit = Payment amounts
Running Balance = Opening + Cumulative(Debit - Credit)
```

**Summary Cards:**

- Total Invoices (Red)
- Total Payments (Green)
- Net Balance (Yellow/Orange)

**Export Options:**

- Print (printer-friendly layout)
- Export to Excel

---

## Business Logic

### Invoice Payment Status Auto-Update

When payment is allocated to invoice:

```php
function updateInvoicePayment($invoice_id, $allocated_amount) {
    // 1. Get current invoice details
    $invoice = getPurchaseInvoice($invoice_id);

    // 2. Calculate new paid amount
    $new_paid_amount = $invoice['paid_amount'] + $allocated_amount;

    // 3. Calculate balance
    $balance_amount = $invoice['net_amount'] - $new_paid_amount;

    // 4. Determine status
    if($balance_amount == 0) {
        $payment_status = 'PAID';
    } else if($balance_amount > 0 && $balance_amount < $invoice['net_amount']) {
        $payment_status = 'PARTIALLY_PAID';
    } else {
        $payment_status = 'UNPAID';
    }

    // 5. Update invoice
    UPDATE store_purchase_invoices
    SET paid_amount = $new_paid_amount,
        balance_amount = $balance_amount,
        payment_status = $payment_status
    WHERE id = $invoice_id;
}
```

### Payment Allocation Process

```php
// In processStore.php for 'store_payment' action:

1. Insert payment record → get payment_id
2. Loop through selected invoices:
   foreach($invoice_ids as $key => $invoice_id) {
       $allocated_amount = $allocated_amounts[$key];

       // a. Insert allocation record
       insertPaymentAllocation($payment_id, $invoice_id, $allocated_amount);

       // b. Update invoice paid amount and status
       updateInvoicePayment($invoice_id, $allocated_amount);
   }
3. Redirect to payment list with success message
```

### Supplier Balance Calculation

```php
function getSupplierBalance($supplier_id) {
    SELECT
        (SUM(CASE WHEN type = 'INVOICE' THEN net_amount ELSE 0 END) -
         SUM(CASE WHEN type = 'PAYMENT' THEN payment_amount ELSE 0 END))
        as balance
    FROM vw_supplier_ledger
    WHERE supplier_id = $supplier_id
}
```

---

## Model Methods (storeClass.php)

### Invoice Methods:

1. `generateInvoiceNumber()` - Auto-generate INV-YYYYMMDD-NNN
2. `insertPurchaseInvoice($data)` - Insert invoice header
3. `insertPurchaseInvoiceItem($data)` - Insert invoice items
4. `getPurchaseInvoices()` - Get all invoices with supplier names
5. `getPurchaseInvoice($id)` - Get single invoice details
6. `getPurchaseInvoiceItems($invoice_id)` - Get items for invoice
7. `getUnpaidInvoicesBySupplier($supplier_id)` - Get unpaid invoices
8. `updateInvoicePayment($invoice_id, $amount)` - Update paid amount & status

### Payment Methods:

9. `generatePaymentNumber()` - Auto-generate PAY-YYYYMMDD-NNN
10. `insertSupplierPayment($data)` - Insert payment record
11. `insertPaymentAllocation($payment_id, $invoice_id, $amount)` - Link payment to invoice
12. `getSupplierPayments()` - Get all payments
13. `getSupplierPayment($id)` - Get single payment details

### Ledger Methods:

14. `getSupplierLedger($supplier_id, $from_date, $to_date)` - Get ledger entries
15. `getSupplierBalance($supplier_id)` - Get current balance
16. `getAllSuppliersBalance()` - Get balances for all suppliers

---

## Controller Actions (processStore.php)

### store_invoice

```php
if($_POST['submit'] == 'store_invoice') {
    // Get header data
    $invoice_data = [...header fields...];

    // Insert invoice
    $invoice_id = $newStore->insertPurchaseInvoice($invoice_data);

    // Insert items
    for($i=0; $i<$item_count; $i++) {
        $item_data = [...item fields from arrays...];
        $newStore->insertPurchaseInvoiceItem($item_data);
    }

    // Redirect
    header("Location: ../store_invoice_list.php?success=1");
}
```

### store_payment

```php
if($_POST['submit'] == 'store_payment') {
    // Insert payment
    $payment_id = $newStore->insertSupplierPayment($payment_data);

    // Allocate to invoices
    foreach($invoice_ids as $key => $invoice_id) {
        $allocated_amount = $allocated_amounts[$key];

        // Insert allocation
        $newStore->insertPaymentAllocation($payment_id, $invoice_id, $allocated_amount);

        // Update invoice
        $newStore->updateInvoicePayment($invoice_id, $allocated_amount);
    }

    // Redirect
    header("Location: ../store_payment_list.php?success=1");
}
```

---

## Usage Workflow

### Adding Purchase Invoice:

1. Navigate to "Add Purchase Invoice"
2. Select supplier and enter invoice details
3. Set payment terms (NET 30 default) - due date auto-calculates
4. Add items row by row:
   - Select item
   - Enter batch, quantity, rate
   - Enter tax % and discount % if applicable
   - Net amount calculates automatically
5. Review grand totals
6. Click "Save Invoice"
7. Invoice saved with status UNPAID

### Making Payment:

1. Navigate to "Make Payment"
2. Select supplier - shows outstanding balance
3. Enter payment details (date, mode, amount, reference)
4. System loads unpaid/partially paid invoices for that supplier
5. Select invoices to pay (checkbox)
6. System auto-allocates payment amount to selected invoices
7. Or manually enter allocation amounts
8. Review allocation summary (payment, allocated, remaining)
9. Click "Save Payment"
10. Payment recorded and invoices updated automatically

### Viewing Ledger:

1. Navigate to "Supplier Ledger Report"
2. Select supplier and date range
3. Click "Show Ledger"
4. View all invoices (debit) and payments (credit) with running balance
5. Export to Excel or Print as needed

---

## Integration with Existing Store Module

### Stock Management Integration:

- Purchase Invoices are independent of GRN
- GRN increases stock, Invoice records payable
- Invoice can optionally link to GRN using `grn_id` field
- Workflow:
  1. Goods received → Create GRN → Stock increased
  2. Invoice received → Create Purchase Invoice → Payable recorded
  3. Payment made → Create Payment → Payable reduced

### Accounting Flow:

```
GRN Entry:
Dr. Stock (Inventory)
    Cr. GRN Received (Temporary)

Invoice Entry:
Dr. GRN Received (clears temporary)
    Cr. Accounts Payable (Supplier)

Payment Entry:
Dr. Accounts Payable (Supplier)
    Cr. Cash/Bank
```

---

## Menu Integration

Add these menu items to `leftbar.php`:

```html
<li class="nav-label">Store Module</li>

<!-- Transactions -->
<li>
  <a class="has-arrow" href="javascript:void()" aria-expanded="false">
    <i class="icon icon-app-store"></i
    ><span class="nav-text">Transactions</span>
  </a>
  <ul aria-expanded="false">
    <li><a href="add_store_invoice.php">Purchase Invoice</a></li>
    <li><a href="add_store_payment.php">Supplier Payment</a></li>
    <li><a href="store_invoice_list.php">Invoice List</a></li>
    <li><a href="store_payment_list.php">Payment List</a></li>
  </ul>
</li>

<!-- Reports -->
<li>
  <a class="has-arrow" href="javascript:void()" aria-expanded="false">
    <i class="icon icon-chart-bar-33"></i><span class="nav-text">Reports</span>
  </a>
  <ul aria-expanded="false">
    <li><a href="supplier_ledger_report.php">Supplier Ledger</a></li>
    <li><a href="store_stock_report.php">Stock Reports</a></li>
  </ul>
</li>
```

---

## Security & Validation

### Backend Validation:

- All inputs sanitized using `mysqli_real_escape_string()`
- Session-based authentication (`$_SESSION['loginid']`)
- Created_by tracking on all inserts

### Frontend Validation:

- Required fields marked with \*
- Numeric validation on amounts
- Date validation
- Allocation amount cannot exceed invoice balance
- Payment amount must be positive

---

## Testing Checklist

- [ ] Create invoice with single item
- [ ] Create invoice with multiple items
- [ ] Verify totals calculation (amount, tax, discount, net)
- [ ] Make full payment against invoice (status should become PAID)
- [ ] Make partial payment (status should become PARTIALLY_PAID)
- [ ] Make payment against multiple invoices at once
- [ ] Make advance payment (no invoice selected)
- [ ] View invoice list with filters
- [ ] View payment list
- [ ] View supplier ledger report
- [ ] Verify running balance in ledger
- [ ] Export ledger to Excel
- [ ] Print invoice
- [ ] Print payment receipt
- [ ] Check overdue invoice highlighting
- [ ] Verify due date auto-calculation based on payment terms

---

## Future Enhancements

1. **Credit Notes**: Handle returns and adjustments
2. **Payment Reminders**: Email alerts for due invoices
3. **Aging Analysis**: 30/60/90 days overdue report
4. **Purchase Orders**: PO → GRN → Invoice linking
5. **Supplier Statement**: Email supplier their ledger
6. **Multi-currency**: Support foreign suppliers
7. **Tax GST Compliance**: GSTIN, HSN codes, tax breakup
8. **Recurring Invoices**: Auto-generate monthly invoices
9. **Bank Reconciliation**: Match bank statements with payments
10. **Advance Payment**: Handle advance payments properly

---

## SQL Quick Reference

```sql
-- Check invoice status
SELECT invoice_number, net_amount, paid_amount, balance_amount, payment_status
FROM store_purchase_invoices
WHERE supplier_id = 1;

-- Check payment allocations
SELECT p.payment_number, i.invoice_number, pa.allocated_amount
FROM store_payment_allocations pa
JOIN store_supplier_payments p ON pa.payment_id = p.id
JOIN store_purchase_invoices i ON pa.invoice_id = i.id
WHERE p.supplier_id = 1;

-- Supplier ledger
SELECT * FROM vw_supplier_ledger
WHERE supplier_id = 1
ORDER BY transaction_date;

-- Outstanding invoices
SELECT * FROM store_purchase_invoices
WHERE payment_status IN ('UNPAID', 'PARTIALLY_PAID')
AND supplier_id = 1;

-- Total outstanding per supplier
SELECT ss.supplier_name, SUM(spi.balance_amount) as outstanding
FROM store_purchase_invoices spi
JOIN store_suppliers ss ON spi.supplier_id = ss.id
WHERE spi.payment_status != 'PAID'
GROUP BY ss.id, ss.supplier_name;
```

---

## Support & Maintenance

For issues or enhancements, check:

1. Database table structure in `store_invoice_payment_schema.sql`
2. Model methods in `model/storeClass.php`
3. Controller logic in `controller/processStore.php`
4. Form validations in respective PHP files (JavaScript section)

---

**Document Version**: 1.0  
**Last Updated**: <?= date('d-M-Y')?>  
**Created By**: Hospital Store Module Team
