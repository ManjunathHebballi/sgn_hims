# Store Module - Invoice & Payment System - Files Created

## Summary

Complete Purchase Invoice and Supplier Payment system with automatic ledger maintenance.

---

## Database Files

### 1. store_invoice_payment_schema.sql

**Location**: `/alvashospital.com/store_invoice_payment_schema.sql`

**Tables Created:**

- `store_purchase_invoices` - Invoice header with payment tracking
- `store_purchase_invoice_items` - Invoice line items with tax/discount
- `store_supplier_payments` - Payment records
- `store_payment_allocations` - Links payments to invoices
- `vw_supplier_ledger` - View combining invoices and payments

**Execute this SQL file first to create all tables.**

---

## Model Layer

### 2. model/storeClass.php (Updated)

**Location**: `/alvashospital.com/model/storeClass.php`

**Methods Added (15+ new methods):**

**Invoice Methods:**

- `generateInvoiceNumber()` - Auto-generate invoice number
- `insertPurchaseInvoice($data)` - Create invoice
- `insertPurchaseInvoiceItem($data)` - Add invoice items
- `getPurchaseInvoices()` - List all invoices
- `getPurchaseInvoice($id)` - Get invoice details
- `getPurchaseInvoiceItems($invoice_id)` - Get items
- `getUnpaidInvoicesBySupplier($supplier_id)` - For payment allocation

**Payment Methods:**

- `generatePaymentNumber()` - Auto-generate payment number
- `insertSupplierPayment($data)` - Create payment
- `insertPaymentAllocation($payment_id, $invoice_id, $amount)` - Allocate payment
- `getSupplierPayments()` - List all payments
- `getSupplierPayment($id)` - Get payment details

**Ledger Methods:**

- `updateInvoicePayment($invoice_id, $amount)` - Update paid amount & status
- `getSupplierLedger($supplier_id, $from, $to)` - Get ledger entries
- `getSupplierBalance($supplier_id)` - Current outstanding
- `getAllSuppliersBalance()` - All suppliers balances
- `getSupplierById($id)` - Get supplier details

---

## Controller Layer

### 3. controller/processStore.php (Updated)

**Location**: `/alvashospital.com/controller/processStore.php`

**Handlers Added:**

**store_invoice:**

- Receives invoice form data
- Inserts invoice header
- Loops through items and inserts each
- Redirects to invoice list

**store_payment:**

- Receives payment form data
- Inserts payment record
- Loops through selected invoices
- Creates allocation records
- Updates invoice paid amounts and status
- Redirects to payment list

---

## View Layer (Forms)

### 4. add_store_invoice.php

**Location**: `/alvashospital.com/add_store_invoice.php`

**Features:**

- Auto-generated invoice number (read-only)
- Supplier dropdown
- Payment terms with auto-calculated due date
- Dynamic item rows (add/remove)
- Real-time calculations:
  - Amount = Qty × Rate
  - Tax Amount
  - Discount Amount
  - Net Amount per line
- Grand totals section
- Form validation (JavaScript)
- Submits to `controller/processStore.php` with action `store_invoice`

**UI Elements:**

- Bootstrap 4 responsive design
- Select2 for supplier dropdown
- Dynamic table for items
- Color-coded total section

---

### 5. add_store_payment.php

**Location**: `/alvashospital.com/add_store_payment.php`

**Features:**

- Auto-generated payment number
- Supplier selection with outstanding balance display
- Payment mode dropdown (CASH/CHEQUE/BANK_TRANSFER/UPI/CARD)
- AJAX-based unpaid invoice loading
- Invoice allocation table:
  - Shows invoice details (no, date, due date, amounts, balance, status)
  - Checkboxes to select invoices
  - Auto-allocation feature
  - Manual override for allocation amounts
- Payment summary section:
  - Payment amount
  - Allocated amount
  - Remaining amount (color-coded)
- Form validation
- Submits to `controller/processStore.php` with action `store_payment`

**UI Elements:**

- Select2 for supplier
- DataTables-ready structure
- Badge indicators for payment status
- Real-time calculation updates

---

## View Layer (Lists)

### 6. store_invoice_list.php

**Location**: `/alvashospital.com/store_invoice_list.php`

**Features:**

- DataTables with pagination
- Shows all invoices with:
  - Invoice number
  - Date
  - Supplier
  - Due date
  - Amounts (invoice, paid, balance)
  - Payment status (badge)
- Filter options:
  - By payment status
  - By supplier
- Overdue invoices highlighted (red background)
- Action buttons:
  - View details
  - Print invoice
- Success message display

**Columns:**
S.No | Invoice No | Invoice Date | Supplier | Due Date | Invoice Amt | Paid Amt | Balance | Status | Action

---

### 7. store_payment_list.php

**Location**: `/alvashospital.com/store_payment_list.php`

**Features:**

- DataTables with pagination
- Shows all payments with:
  - Payment number
  - Date
  - Supplier
  - Mode (badge)
  - Reference number
  - Amount
- Filter by supplier
- Action buttons:
  - View details
  - Print receipt
- Success message display

**Columns:**
S.No | Payment No | Payment Date | Supplier | Payment Mode | Reference No | Amount | Action

---

## View Layer (Reports)

### 8. supplier_ledger_report.php

**Location**: `/alvashospital.com/supplier_ledger_report.php`

**Features:**

- Supplier selection (required)
- Date range filter
- Supplier info header:
  - Name, contact, address
  - Current outstanding balance
- Ledger table showing:
  - Date
  - Transaction number
  - Particulars
  - Debit (invoices) - Red
  - Credit (payments) - Green
  - Running balance
  - Status
  - View action
- Summary cards:
  - Total Invoices (Debit)
  - Total Payments (Credit)
  - Net Balance
- Export options:
  - Print (printer-friendly)
  - Excel export
- Print-optimized CSS

**Ledger Calculation:**

```
Running Balance = Previous Balance + Current Debit - Current Credit
```

---

## AJAX Handler

### 9. ajax/get_unpaid_invoices.php

**Location**: `/alvashospital.com/ajax/get_unpaid_invoices.php`

**Purpose:**

- AJAX endpoint for payment form
- Receives supplier_id via POST
- Returns JSON with unpaid/partially paid invoices
- Used to populate invoice allocation table

**Response Format:**

```json
{
  "success": true,
  "invoices": [
    {
      "id": 1,
      "invoice_number": "INV-20240115-001",
      "invoice_date": "2024-01-15",
      "due_date": "2024-02-14",
      "net_amount": 10000.0,
      "paid_amount": 0.0,
      "balance_amount": 10000.0,
      "payment_status": "UNPAID"
    }
  ]
}
```

---

## Documentation

### 10. STORE_INVOICE_PAYMENT_GUIDE.md

**Location**: `/alvashospital.com/STORE_INVOICE_PAYMENT_GUIDE.md`

**Contents:**

- Complete system overview
- Database table descriptions
- File structure explanation
- Feature details
- Business logic documentation
- Model methods reference
- Controller actions guide
- Usage workflows
- Integration notes
- Testing checklist
- SQL quick reference
- Future enhancements roadmap

---

## Installation Steps

### 1. Database Setup

```bash
# Execute SQL file
mysql -u username -p database_name < store_invoice_payment_schema.sql
```

### 2. Verify File Locations

Ensure all files are in correct locations:

```
/alvashospital.com/
├── add_store_invoice.php
├── add_store_payment.php
├── store_invoice_list.php
├── store_payment_list.php
├── supplier_ledger_report.php
├── ajax/
│   └── get_unpaid_invoices.php
├── model/
│   └── storeClass.php (updated)
├── controller/
│   └── processStore.php (updated)
└── store_invoice_payment_schema.sql
```

### 3. Add Menu Items

Edit `leftbar.php` and add:

```html
<li><a href="add_store_invoice.php">Purchase Invoice</a></li>
<li><a href="add_store_payment.php">Supplier Payment</a></li>
<li><a href="store_invoice_list.php">Invoice List</a></li>
<li><a href="store_payment_list.php">Payment List</a></li>
<li><a href="supplier_ledger_report.php">Supplier Ledger</a></li>
```

### 4. Test the System

1. Create a test supplier (if not exists)
2. Create a purchase invoice
3. Make a payment against the invoice
4. View supplier ledger report
5. Verify calculations and status updates

---

## Key Features

### ✅ Auto-Generated Numbers

- Invoice: `INV-YYYYMMDD-001`
- Payment: `PAY-YYYYMMDD-001`

### ✅ Auto-Calculations

- Due date based on payment terms
- Item amount = qty × rate
- Tax and discount calculations
- Net amounts
- Running balance in ledger

### ✅ Auto-Status Updates

- When payment allocated:
  - Updates invoice paid_amount
  - Calculates balance_amount
  - Sets payment_status (UNPAID/PARTIALLY_PAID/PAID)

### ✅ Payment Allocation

- Select multiple invoices
- Auto-allocate payment amount
- Manual override option
- Real-time summary updates

### ✅ Supplier Ledger

- Unified view of all transactions
- Running balance calculation
- Debit (invoices) and Credit (payments)
- Export to Excel/Print

---

## Business Workflows

### Workflow 1: Invoice Entry

```
1. Receive supplier invoice
2. Go to "Add Purchase Invoice"
3. Select supplier
4. Enter invoice details
5. Add items with rates
6. Apply tax/discount
7. Save
→ Invoice created with status UNPAID
```

### Workflow 2: Payment Entry

```
1. Decide to pay supplier
2. Go to "Make Payment"
3. Select supplier
4. Enter payment amount and mode
5. System shows unpaid invoices
6. Select invoices to pay
7. System auto-allocates amount
8. Save
→ Payment recorded, invoices updated
```

### Workflow 3: Ledger Review

```
1. Go to "Supplier Ledger Report"
2. Select supplier
3. Choose date range
4. View running balance
5. Export or print
→ Complete transaction history
```

---

## Technical Highlights

### Database Design

- Normalized structure (3NF)
- Foreign key constraints
- Proper indexing
- View for easy reporting
- Timestamps on all tables

### PHP Architecture

- OOP approach
- Model-View-Controller separation
- Reusable methods
- Session-based auth
- SQL injection prevention

### Frontend

- Responsive Bootstrap 4
- jQuery for dynamic behavior
- Select2 for enhanced dropdowns
- DataTables for listings
- Real-time calculations
- Form validation

### AJAX

- Load unpaid invoices
- JSON responses
- Error handling

---

## File Count Summary

**Total Files Created/Modified: 10**

**New Files:**

- 5 View files (forms, lists, reports)
- 1 AJAX handler
- 1 SQL schema file
- 2 Documentation files

**Modified Files:**

- 1 Model file (storeClass.php)
- 1 Controller file (processStore.php)

---

## Dependencies

**Required:**

- PHP 7.0+
- MySQL 5.7+
- jQuery 3.3.1
- Bootstrap 4.3.1
- Select2 4.0.13
- DataTables (for listings)
- Font Awesome (for icons)

**Existing:**

- userlogin class (for DB connection)
- Session management
- leftbar.php (menu)
- footer.php

---

## Next Steps

1. ☑ Execute `store_invoice_payment_schema.sql`
2. ☑ Verify model/storeClass.php has new methods
3. ☑ Verify controller/processStore.php has new handlers
4. ☑ Add menu items to leftbar.php
5. ☑ Test invoice creation
6. ☑ Test payment entry
7. ☑ Test ledger report
8. ☑ Configure any print templates (optional)

---

## Support

For questions or issues:

1. Check `STORE_INVOICE_PAYMENT_GUIDE.md` for detailed documentation
2. Review SQL schema for table structure
3. Check model methods in storeClass.php
4. Review controller logic in processStore.php
5. Test with sample data first

---

**System Status**: ✅ Complete and Ready to Use

**Files**: 10 files  
**Tables**: 4 tables + 1 view  
**Methods**: 15+ new methods  
**Features**: Invoice entry, Payment allocation, Supplier ledger

---

**Created**: <?= date('d-M-Y H:i:s')?>  
**Version**: 1.0  
**Module**: Hospital Store - Invoice & Payment System
