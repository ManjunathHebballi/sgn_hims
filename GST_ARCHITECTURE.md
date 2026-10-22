# Item-Level GST Implementation - Visual Architecture

## System Architecture Diagram

```
┌─────────────────────────────────────────────────────────────────┐
│                    USER INTERFACE LAYER                         │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  ┌──────────────────────────────┐  ┌──────────────────────────┐│
│  │   Add Purchase Order         │  │  GST Rate Master         ││
│  │  (add_store_po.php)          │  │  (gst_rate_master.php)   ││
│  │                              │  │                          ││
│  │  ┌──────────────────────┐   │  │  ┌────────────────────┐  ││
│  │  │ Item Row Layout:     │   │  │  │ Configure GST for  │  ││
│  │  │ Item|Qty|Unit|Rate   │   │  │  │ each Category      │  ││
│  │  │ Amt|GST%|GST$|Net$   │   │  │  │                    │  ││
│  │  └──────────────────────┘   │  │  │ Medical: 5%        │  ││
│  │                              │  │  │ Equipment: 18%     │  ││
│  │  Real-time Calculations     │  │  │ Consumables: 0%    │  ││
│  │  • Qty × Rate = Amount      │  │  │                    │  ││
│  │  • Amt × GST% = GST Amt     │  │  └────────────────────┘  ││
│  │  • Amount + GST = Net Amt   │  │                          ││
│  └──────────────────────────────┘  └──────────────────────────┘│
│                                                                 │
│           JavaScript (jQuery): Real-time calculations            │
│                                                                 │
│  Auto-fill GST % (via AJAX)                                     │
│         ↓                                                        │
│    ┌─────────────────────────────┐                              │
│    │  ajax/getItemGstRate.php    │                              │
│    │  Fetch category GST rate    │                              │
│    └─────────────────────────────┘                              │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│                   CONTROLLER LAYER                              │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  controller/processStore.php                                    │
│                                                                 │
│  ┌──────────────────────────┐   ┌────────────────────────────┐ │
│  │  store_po Handler        │   │  store_gst_rate Handler    │ │
│  │                          │   │                            │ │
│  │  • Get PO header data    │   │  • Get category_id         │ │
│  │  • Get item data arrays  │   │  • Get gst_percent         │ │
│  │  • Get gst_percent[]     │   │  • Disable old rates       │ │
│  │  • Call insertPO()       │   │  • Set new rate            │ │
│  │  • Loop items            │   │  • Redirect to master      │ │
│  │  • Call insertPOItem()   │   │                            │ │
│  │    with gst_percent      │   │                            │ │
│  └──────────────────────────┘   └────────────────────────────┘ │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│                     MODEL LAYER                                 │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  model/storeClass.php                                           │
│                                                                 │
│  ┌────────────────────────────────────────────────────────┐    │
│  │  insertPurchaseOrderItem(                             │    │
│  │    po_id, item_id, qty, rate, gst_percent            │    │
│  │  )                                                     │    │
│  │                                                        │    │
│  │  Calculations:                                         │    │
│  │  • amount = qty × rate                                │    │
│  │  • gst_amt = (amount × gst_percent) / 100            │    │
│  │  • net_amt = amount + gst_amt                         │    │
│  │                                                        │    │
│  │  INSERT INTO store_purchase_order_items               │    │
│  │  (po_id, item_id, qty, rate, gst_percent,            │    │
│  │   gst_amount, net_amount)                             │    │
│  └────────────────────────────────────────────────────────┘    │
│                                                                 │
│  ┌────────────────────────────────────────────────────────┐    │
│  │  setGstRateForCategory(category_id, gst_percent)      │    │
│  │                                                        │    │
│  │  • Check for existing rate                            │    │
│  │  • If exists: UPDATE with new value                  │    │
│  │  • If not: INSERT new rate                            │    │
│  │                                                        │    │
│  │  INSERT INTO store_item_gst_rates                      │    │
│  │  (category_id, gst_percent, applicable_from, ...)     │    │
│  └────────────────────────────────────────────────────────┘    │
│                                                                 │
│  ┌────────────────────────────────────────────────────────┐    │
│  │  getGstRateForCategory(category_id)                   │    │
│  │                                                        │    │
│  │  SELECT gst_percent FROM store_item_gst_rates         │    │
│  │  WHERE category_id = X                                │    │
│  │  AND status = 1                                        │    │
│  │  ORDER BY applicable_from DESC LIMIT 1                │    │
│  │                                                        │    │
│  │  Returns: GST percentage (0 if not found)             │    │
│  └────────────────────────────────────────────────────────┘    │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│                     DATABASE LAYER                              │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  ┌──────────────────────────────────────────────────────┐      │
│  │  store_purchase_order_items (UPDATED)                │      │
│  │  ┌─────────────────────────────────────────────┐     │      │
│  │  │ po_id          → FK to purchase orders      │     │      │
│  │  │ item_id        → FK to items                │     │      │
│  │  │ quantity       → Item quantity              │     │      │
│  │  │ rate           → Unit rate                  │     │      │
│  │  │ gst_percent    → GST % (NEW)               │     │      │
│  │  │ gst_amount     → Calculated GST (NEW)      │     │      │
│  │  │ net_amount     → Amount + GST (NEW)        │     │      │
│  │  │ created_at     → Timestamp                  │     │      │
│  │  └─────────────────────────────────────────────┘     │      │
│  └──────────────────────────────────────────────────────┘      │
│                                                                 │
│  ┌──────────────────────────────────────────────────────┐      │
│  │  store_item_gst_rates (NEW)                          │      │
│  │  ┌─────────────────────────────────────────────┐     │      │
│  │  │ id             → Primary key                │     │      │
│  │  │ category_id    → FK to categories           │     │      │
│  │  │ gst_percent    → GST percentage             │     │      │
│  │  │ applicable_from→ Rate effective date        │     │      │
│  │  │ applicable_to  → Rate expiry date (opt)     │     │      │
│  │  │ status         → 1=Active, 0=Inactive       │     │      │
│  │  │ created_at     → Timestamp                  │     │      │
│  │  │ created_by     → User ID who created        │     │      │
│  │  └─────────────────────────────────────────────┘     │      │
│  └──────────────────────────────────────────────────────┘      │
│                                                                 │
│  Related Tables (referenced):                                   │
│  • store_items (category, unit details)                        │
│  • store_categories (item categories)                          │
│  • store_purchase_orders (PO header)                           │
│  • store_units (unit names)                                    │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

---

## Data Flow Diagram

```
USER CREATES PURCHASE ORDER
         ↓
┌─────────────────────────────────┐
│ Fill PO Header                  │
│ • Supplier                      │
│ • Date                          │
│ • Payment Terms                 │
└─────────────────────────────────┘
         ↓
┌─────────────────────────────────┐
│ Click "Add Item"                │
│ (JavaScript: addItemRow())       │
└─────────────────────────────────┘
         ↓
┌─────────────────────────────────┐
│ Item Row Template Created       │
│ • Item Dropdown                 │
│ • Quantity Input                │
│ • Unit (auto-filled)            │
│ • Rate Input                    │
│ • Amount (calculated)           │
│ • GST % (editable)              │
│ • GST Amt (calculated)          │
│ • Net Amt (calculated)          │
└─────────────────────────────────┘
         ↓
┌─────────────────────────────────┐
│ User Selects Item               │
│ JavaScript detects:             │
│ • category_id from item option  │
└─────────────────────────────────┘
         ↓
┌─────────────────────────────────┐
│ AJAX Call                       │
│ POST to getItemGstRate.php      │
│ Data: category_id=5             │
└─────────────────────────────────┘
         ↓
┌──────────────────────────────────────────┐
│ Server (getItemGstRate.php)              │
│ 1. Include storeClass                   │
│ 2. Call getGstRateForCategory(5)         │
│ 3. Query store_item_gst_rates            │
│ 4. Return GST % (e.g., 5)                │
└──────────────────────────────────────────┘
         ↓
┌─────────────────────────────────┐
│ JavaScript Receives GST %       │
│ Auto-fills in form field        │
│ GST % = 5                       │
└─────────────────────────────────┘
         ↓
┌─────────────────────────────────┐
│ User Enters:                    │
│ • Quantity: 100                 │
│ • Rate: 50                      │
│ • GST %: 5 (or modify)          │
└─────────────────────────────────┘
         ↓
┌──────────────────────────────────────────┐
│ JavaScript Calculations                  │
│ (calculateGstAndTotal function)          │
│                                          │
│ amount = 100 × 50 = 5000                 │
│ gst_amount = 5000 × (5/100) = 250        │
│ net_amount = 5000 + 250 = 5250           │
│                                          │
│ Update form fields with calculated values│
└──────────────────────────────────────────┘
         ↓
┌──────────────────────────────────────────┐
│ Calculate Grand Totals                   │
│                                          │
│ total_amount = SUM(amount)               │
│ total_gst = SUM(gst_amount)              │
│ total_net = SUM(net_amount)              │
│                                          │
│ Update summary section                   │
└──────────────────────────────────────────┘
         ↓
┌─────────────────────────────────┐
│ User Adds More Items            │
│ (Repeat process for each item)   │
└─────────────────────────────────┘
         ↓
┌─────────────────────────────────┐
│ User Submits Form               │
│ POST all data to processStore.php│
└─────────────────────────────────┘
         ↓
┌──────────────────────────────────────────┐
│ Controller (processStore.php)            │
│ store_po handler                         │
│                                          │
│ 1. Get PO header data                    │
│ 2. insertPurchaseOrder()                 │
│    → Returns po_id                       │
│ 3. For each item:                        │
│    • Get: item_id, qty, rate, gst_percent
│    • Call insertPurchaseOrderItem(       │
│      po_id, item_id, qty, rate,         │
│      gst_percent                         │
│    )                                     │
└──────────────────────────────────────────┘
         ↓
┌──────────────────────────────────────────┐
│ Model (storeClass.php)                   │
│ insertPurchaseOrderItem()                │
│                                          │
│ 1. Calculate:                            │
│    amount = qty × rate = 5000            │
│    gst_amt = amount × gst_percent/100    │
│    net_amt = amount + gst_amt            │
│                                          │
│ 2. INSERT into database:                 │
│    INSERT INTO store_purchase_order_items
│    (po_id, item_id, qty, rate,           │
│     gst_percent, gst_amount, net_amount) │
│    VALUES (...)                          │
│                                          │
│ 3. Return success                        │
└──────────────────────────────────────────┘
         ↓
┌──────────────────────────────────────────┐
│ DATABASE STORAGE                         │
│                                          │
│ store_purchase_order_items:              │
│ po_id    | item_id | qty | rate | gst% │
│ 5        | 2       | 100 | 50   | 5    │
│                                          │
│ gst_amount | net_amount                  │
│ 250        | 5250                        │
└──────────────────────────────────────────┘
         ↓
┌─────────────────────────────────┐
│ Redirect to PO List             │
│ Show Success Message            │
│ "PO saved successfully!"        │
└─────────────────────────────────┘
```

---

## GST Rate Configuration Flow

```
ADMIN CONFIGURES GST RATES
         ↓
┌───────────────────────────────────┐
│ Navigate to GST Rate Master       │
│ (gst_rate_master.php)             │
└───────────────────────────────────┘
         ↓
┌───────────────────────────────────┐
│ Form Displayed:                   │
│ • Category Dropdown               │
│ • GST % Input                     │
│ • Save Button                     │
└───────────────────────────────────┘
         ↓
┌───────────────────────────────────┐
│ Admin Selects:                    │
│ • Category: Medicines (ID=2)      │
│ • GST %: 5                        │
└───────────────────────────────────┘
         ↓
┌───────────────────────────────────┐
│ Admin Clicks "Save GST Rate"      │
│ POST to processStore.php          │
│ Data: category_id=2, gst_percent=5
└───────────────────────────────────┘
         ↓
┌─────────────────────────────────────────────┐
│ Controller Handler (store_gst_rate)         │
│                                             │
│ 1. Validate inputs                         │
│ 2. Call disableOldGstRate(2)               │
│    → Marks old rate as inactive             │
│ 3. Call setGstRateForCategory(2, 5)         │
│    → Inserts or updates new rate            │
└─────────────────────────────────────────────┘
         ↓
┌─────────────────────────────────────────────┐
│ Model Methods Execute                       │
│                                             │
│ disableOldGstRate(2):                       │
│  UPDATE store_item_gst_rates                │
│  SET applicable_to = yesterday,             │
│      status = 0                             │
│  WHERE category_id = 2                      │
│  AND status = 1                             │
│  AND DATE(applicable_from) < TODAY          │
│                                             │
│ setGstRateForCategory(2, 5):                │
│  INSERT INTO store_item_gst_rates           │
│  (category_id, gst_percent, applicable_from│
│  status, created_by)                        │
│  VALUES (2, 5, NOW(), 1, user_id)           │
└─────────────────────────────────────────────┘
         ↓
┌──────────────────────────────────────────┐
│ Database State After                     │
│                                          │
│ Old Rate (Inactive):                     │
│ category_id: 2                           │
│ gst_percent: 3                           │
│ status: 0 (INACTIVE)                     │
│ applicable_to: 2026-01-27                │
│                                          │
│ New Rate (Active):                       │
│ category_id: 2                           │
│ gst_percent: 5                           │
│ status: 1 (ACTIVE)                       │
│ applicable_from: 2026-01-28              │
└──────────────────────────────────────────┘
         ↓
┌───────────────────────────────────┐
│ Redirect to GST Rate Master       │
│ Show Success: "Rate saved!"       │
│ Update "Active GST Rates" sidebar  │
└───────────────────────────────────┘
         ↓
┌──────────────────────────────────────────┐
│ Now When Creating Purchase Orders:       │
│ • Select item from Medicines category    │
│ • GST % automatically fills with 5%      │
│ • User can override if needed            │
└──────────────────────────────────────────┘
```

---

## Single Row Item Layout

```
┌─────────────────────────────────────────────────────────────────────────────────────────────────┐
│  ITEM ROW (Compact Single-Row Display)                                                          │
├─────────────────────────────────────────────────────────────────────────────────────────────────┤
│                                                                                                 │
│  [2] [1]  [1]   [1]    [1]      [1]     [1]      [1]      [0.5]                                │
│  ┌──────┐ ┌────┐┌────┐┌─────┐┌──────┐┌──────┐┌────────┐┌───────┐ ┌──────┐                    │
│  │Item* │ │Qty*││Unit││Rate*││Amount││GST%  ││GST Amt ││Net Amt│ │Delete│                    │
│  ├──────┤ ├────┤├────┤├─────┤├──────┤├──────┤├────────┤├───────┤ ├──────┤                    │
│  │▼Medi-│ │100 ││Box ││ 50  ││ 5000 ││  5   ││  250   ││ 5250  │ │ 🗑   │                    │
│  │cines │ │    ││    ││     ││      ││      ││        ││       │ │      │                    │
│  └──────┘ └────┘└────┘└─────┘└──────┘└──────┘└────────┘└───────┘ └──────┘                    │
│                                                                                                 │
│  Form-Control-Small: Compact styling, minimal padding, smaller font                             │
│                                                                                                 │
│  All fields visible in single row:                                                              │
│  • Item selection with auto-complete                                                            │
│  • Quantity input                                                                               │
│  • Unit display (read-only, auto-filled)                                                        │
│  • Rate entry                                                                                   │
│  • Amount calculation (read-only)                                                               │
│  • GST Percentage (editable, auto-filled from category)                                         │
│  • GST Amount (read-only, calculated: Amt × GST%)                                               │
│  • Net Amount (read-only, calculated: Amt + GST Amt)                                            │
│  • Delete button                                                                                │
│                                                                                                 │
│  JavaScript Events:                                                                             │
│  • On item select → Fetch category → Auto-fill GST %                                            │
│  • On quantity/rate change → Recalculate Amount, GST, Net                                       │
│  • On GST % change → Recalculate GST Amt, Net Amt, Grand Totals                                 │
│  • On delete → Remove row, recalculate totals                                                   │
│                                                                                                 │
└─────────────────────────────────────────────────────────────────────────────────────────────────┘

SUMMARY SECTION (Below Item Rows)
┌────────────────────────────────────────────┐
│  Amount: ₹10,000                           │
│  ─────────────────────────────────────────  │
│  GST: ₹850                                 │
│  ─────────────────────────────────────────  │
│  Total (with GST): ₹10,850                 │
└────────────────────────────────────────────┘
```

---

## Database Relationships

```
store_categories
│
├─ category_id (PK)
├─ category_name
└─ ...
      │
      │ (1:N)
      │
      ↓
store_items
│
├─ id (PK)
├─ item_name
├─ category_id (FK) ◄──────────┐
├─ unit_id                     │
└─ ...                         │
      │                        │
      │ (1:N)                  │
      │                        │
      ↓                        │
store_purchase_order_items     │
│                              │
├─ id (PK)                     │
├─ po_id (FK)                  │
├─ item_id (FK) ◄──────────────┤
├─ quantity                    │
├─ rate                        │
├─ gst_percent                 │
├─ gst_amount                  │
└─ net_amount                  │
                               │
                               │
store_item_gst_rates ◄─────────┘
│
├─ id (PK)
├─ category_id (FK)
├─ gst_percent
├─ applicable_from
├─ applicable_to
├─ status
├─ created_at
└─ created_by

store_suppliers
│
├─ id (PK)
├─ supplier_name
├─ gst_number
└─ ...
      │
      │ (1:N)
      │
      ↓
store_purchase_orders
│
├─ id (PK)
├─ po_number
├─ supplier_id (FK) ◄─────────┐
├─ po_date                    │
├─ total_amount               │
└─ ...                        │
      │                       │
      │ (1:N)                 │
      │                       │
      ↓                       │
store_purchase_order_items    │
├─ po_id (FK) ◄───────────────┘
├─ (other fields)
```

---

## Calculation Logic Flowchart

```
USER ENTERS ITEM DATA
│
├─ Quantity input ─┐
├─ Rate input ────┼─► AMOUNT = Qty × Rate
│                 │
└─────────────────┘
                   │
                   ↓
          Display: Amount = 5000
                   │
                   ├─ GST % input (auto-filled or manual)
                   │
                   ↓
          GST_AMOUNT = Amount × (GST% / 100)
                   │
                   ↓
          Display: GST Amount = 250
                   │
                   ├─ Calculate Net Amount
                   │
                   ↓
          NET_AMOUNT = Amount + GST_AMOUNT
                   │
                   ↓
          Display: Net Amount = 5250
                   │
                   ├─ Loop through all items
                   │
                   ↓
          TOTAL_AMOUNT = SUM(Amount)
          TOTAL_GST = SUM(GST_AMOUNT)
          TOTAL_NET = SUM(NET_AMOUNT)
                   │
                   ↓
          Display: Summary
          Total Amount: 10,000
          Total GST: 850
          Total Net: 10,850
                   │
                   ↓
          SUBMIT PO
                   │
                   ↓
          SERVER CALCULATIONS (Verify)
          ├─ Recalculate amounts
          ├─ Store in database
          └─ Confirm success
```

---

This architecture provides a complete, scalable item-level GST implementation with clear separation of concerns between presentation, business logic, and data layers.
