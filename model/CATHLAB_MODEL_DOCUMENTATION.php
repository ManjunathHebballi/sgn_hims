<?php
/**
 * CATHLAB MODEL CLASS DOCUMENTATION
 * 
 * File: model/cathLabClass.php
 * Purpose: Centralized database operations for CathLab Sub-Store module
 * 
 * This class follows the same pattern as storeClass.php for consistency
 * All database queries are moved from the controller to this model
 */

/**
 * CLASS STRUCTURE
 * ===============
 */

// PROPERTIES - All private with getter/setter methods
// - GRN related: grn_number, grn_date, supplier_id, po_id, invoice_number, etc.
// - Billing related: billing_number, patient_id, patient_ip_no, discount_amount, etc.
// - Items related: item_code, item_name, category_id, unit_id, unit_cost, etc.
// - Procedures related: procedure_id, procedure_date, procedure_code, etc.

/**
 * METHODS - Organized by functionality
 */

// ==================== GRN OPERATIONS ====================

/**
 * insertCathLabGRN()
 * - Inserts GRN header into store_grn table
 * - Returns: GRN ID on success, throws Exception on failure
 * - Uses: grn_number, grn_date, supplier_id, po_id, invoice_number, invoice_date, remarks, received_by
 */

/**
 * insertGrnItem($grn_id, $item_id, $quantity, $rate, $batch_no, $expiry_date)
 * - Inserts individual GRN item
 * - Returns: true on success, throws Exception on failure
 * - Automatically calculates amount (quantity * rate)
 */

/**
 * updateStockFromGrn($item_id, $quantity)
 * - Updates cathlab_store_items stock on GRN receipt
 * - Returns: true on success, throws Exception on failure
 * - Adds quantity to current_stock
 */

/**
 * createStockLedger($item_id, $transaction_type, $quantity, $reference_type, $reference_id, $description)
 * - Creates audit trail entry for stock movements
 * - Returns: true on success, throws Exception on failure
 * - Transaction types: GRN_IN, PROCEDURE_RESERVE, BILLING_OUT
 */

/**
 * getRecentGrns($limit = 10)
 * - Retrieves recent GRN records with item counts
 * - Returns: Array of GRN records
 * - Joins with store_supplier for supplier names
 */

// ==================== BILLING OPERATIONS ====================

/**
 * insertCathLabBilling()
 * - Inserts billing header into cathlab_billing table
 * - Returns: Billing ID on success, throws Exception on failure
 * - Automatically calculates GST (18%) and total amount
 * - Uses: billing_number, billing_date, patient_id, patient_ip_no, procedure_type, subtotal, discount_amount, gst_amount, total_amount
 */

/**
 * insertBillingItem($billing_id, $item_id, $quantity, $unit_price, $billing_price, $markup_percent, $amount)
 * - Inserts individual billing item
 * - Returns: true on success, throws Exception on failure
 * - Tracks markup percentage for profitability analysis
 */

/**
 * deductStockForBilling($item_id, $quantity)
 * - Reduces cathlab_store_items stock after billing
 * - Returns: true on success, throws Exception on failure
 * - Deducts quantity from current_stock
 */

/**
 * updatePatientBillingStatus($patient_admission_id, $billing_id)
 * - Updates patient_admission table with billing information
 * - Returns: true on success, throws Exception on failure
 * - Sets cathlab_billed = 'yes' and links billing ID
 */

/**
 * getRecentBillings($limit = 10)
 * - Retrieves recent billing records with item counts
 * - Returns: Array of billing records
 * - Sorted by billing_date DESC
 */

// ==================== ITEM OPERATIONS ====================

/**
 * insertCathLabItem()
 * - Inserts new CathLab item into cathlab_store_items table
 * - Returns: Item ID on success, throws Exception on failure
 * - Initial stock is set to 0
 * - Uses: item_code, item_name, category_id, unit_id, item_type, unit_cost, billing_price, min_stock, reorder_level, is_expiry_required, is_serial_required, description
 */

/**
 * getCathLabItems()
 * - Retrieves all active CathLab items with category and unit details
 * - Returns: MySQLi result object
 * - Joins with store_category and store_unit tables
 * - Ordered by item_name ASC
 */

/**
 * getCathLabItemById($item_id)
 * - Retrieves single item by ID with all details
 * - Returns: Associative array of item data
 * - Includes category and unit information
 */

/**
 * updateCathLabItem($item_id)
 * - Updates existing CathLab item
 * - Returns: true on success, throws Exception on failure
 * - Updates: item_code, item_name, category_id, unit_id, item_type, unit_cost, billing_price, min_stock, reorder_level, is_expiry_required, is_serial_required, description, status
 */

/**
 * deleteCathLabItem($item_id)
 * - Soft delete of CathLab item (sets status to inactive)
 * - Returns: true on success, throws Exception on failure
 * - Sets deleted_at timestamp
 */

/**
 * bulkImportItems($csv_file)
 * - Bulk import items from CSV file
 * - Returns: Array with imported count, error count, and error messages
 * - CSV format: Item Code, Item Name, Category, Type, Unit, Unit Cost, Billing Price, Min Stock, Description
 * - Auto-resolves category and unit names to IDs
 */

// ==================== UTILITY OPERATIONS ====================

/**
 * logActivity($user_id, $module, $action, $description)
 * - Logs user actions for audit trail
 * - Returns: true on success, false on failure
 * - Used for: GRN_CREATED, BILLING_CREATED, ITEM_CREATED, ITEM_DELETED, BULK_IMPORT
 */

/**
 * getLowStockItems()
 * - Retrieves items with stock <= min_stock level
 * - Returns: MySQLi result object
 * - Ordered by current_stock ASC (most critical first)
 */

/**
 * getInventorySummary()
 * - Returns aggregated inventory statistics
 * - Returns: Array with total_items, total_qty, total_value
 * - Only includes active items
 */

/**
 * getCostVsRevenue($period = 'monthly')
 * - Analyzes profitability by date
 * - Returns: MySQLi result object with date, cost, revenue, profit
 * - Groups by DATE(billing_date)
 */

/**
 * USAGE IN CONTROLLER (processCathLab.php)
 * ==========================================
 * 
 * 1. Include the class:
 *    include('../model/cathLabClass.php');
 *    $cathLab = new cathLab;
 * 
 * 2. Use setters to populate properties:
 *    $cathLab->setGrnNumber($grn_number);
 *    $cathLab->setGrnDate($grn_date);
 *    // ... set other properties
 * 
 * 3. Call appropriate method:
 *    $grn_id = $cathLab->insertCathLabGRN();
 * 
 * 4. Handle exceptions:
 *    try {
 *        $grn_id = $cathLab->insertCathLabGRN();
 *    } catch (Exception $e) {
 *        $_SESSION['error_message'] = $e->getMessage();
 *    }
 * 
 * CONSISTENCY WITH STORECLASS PATTERN
 * ====================================
 * - Private properties with public setter methods
 * - Methods organized by functionality (COMMENTS section)
 * - Exception throwing for error handling
 * - Global $db connection usage
 * - MySQLi prepared statements for SQL injection prevention
 * - Consistent naming: set[PropertyName](), [Operation][EntityName]()
 * - Transaction support in controller level
 * - Activity logging for audit trail
 * 
 * ADVANTAGES OF THIS STRUCTURE
 * =============================
 * - Separation of concerns: Model (cathLabClass.php) vs Controller (processCathLab.php)
 * - Reusable methods across different views/controllers
 * - Centralized database logic for easier maintenance
 * - Consistent with existing storeClass pattern
 * - Better code organization and readability
 * - Easier to unit test
 * - Single responsibility principle
 * - DRY (Don't Repeat Yourself)
 */

?>
