<?php
require_once(__DIR__ . '/session_config.php');
include('dbconnect.php');
class cathLab {
    
    // Properties for GRN
    private $grn_number;
    private $grn_date;
    private $supplier_id;
    private $supplier_name;
    private $po_id;
    private $invoice_number;
    private $invoice_date;
    private $remarks;
    private $received_by;
    private $grn_type = 'cathlab';
    private $total_items;
    private $total_quantity;
    private $total_cost;
    
    // Properties for Billing
    private $billing_number;
    private $billing_date;
    private $patient_id;
    private $patient_ip_no;
    private $procedure_type;
    private $subtotal;
    private $discount_amount;
    private $gst_amount;
    private $total_amount;
    private $billed_by;
    
    // Properties for Items
    private $item_code;
    private $item_name;
    private $category_id;
    private $unit_id;
    private $item_type;
    private $unit_cost;
    private $billing_price;
    private $min_stock;
    private $reorder_level;
    private $is_expiry_required;
    private $is_serial_required;
    private $description;
    private $current_stock;
    private $status;
    
    // Properties for Procedures
    private $procedure_id;
    private $procedure_date;
    private $procedure_code;
    
    // Setters for GRN
    function setGrnNumber($grn_number) {
        $this->grn_number = $grn_number;
    }
    
    function setGrnDate($grn_date) {
        $this->grn_date = $grn_date;
    }
    
    function setSupplierId($supplier_id) {
        $this->supplier_id = $supplier_id;
    }
    
    function setSupplierName($supplier_name) {
        $this->supplier_name = $supplier_name;
    }
    
    function setPoId($po_id) {
        $this->po_id = $po_id;
    }
    
    function setInvoiceNumber($invoice_number) {
        $this->invoice_number = $invoice_number;
    }
    
    function setInvoiceDate($invoice_date) {
        $this->invoice_date = $invoice_date;
    }
    
    function setRemarks($remarks) {
        $this->remarks = $remarks;
    }
    
    function setReceivedBy($received_by) {
        $this->received_by = $received_by;
    }
    
    function setGrnType($grn_type) {
        $this->grn_type = $grn_type;
    }
    
    // Setters for Billing
    function setBillingNumber($billing_number) {
        $this->billing_number = $billing_number;
    }
    
    function setBillingDate($billing_date) {
        $this->billing_date = $billing_date;
    }
    
    function setPatientId($patient_id) {
        $this->patient_id = $patient_id;
    }
    
    function setPatientIpNo($patient_ip_no) {
        $this->patient_ip_no = $patient_ip_no;
    }
    
    function setProcedureType($procedure_type) {
        $this->procedure_type = $procedure_type;
    }
    
    function setProcedureId($procedure_id) {
        $this->procedure_id = $procedure_id;
    }
    
    function setSubtotal($subtotal) {
        $this->subtotal = $subtotal;
    }
    
    function setDiscountAmount($discount_amount) {
        $this->discount_amount = $discount_amount;
    }
    
    function setGstAmount($gst_amount) {
        $this->gst_amount = $gst_amount;
    }
    
    function setTotalAmount($total_amount) {
        $this->total_amount = $total_amount;
    }
    
    function setBilledBy($billed_by) {
        $this->billed_by = $billed_by;
    }
    
    // Setters for Items
    function setItemCode($item_code) {
        $this->item_code = $item_code;
    }
    
    function setItemName($item_name) {
        $this->item_name = $item_name;
    }
    
    function setCategoryId($category_id) {
        $this->category_id = $category_id;
    }
    
    function setUnitId($unit_id) {
        $this->unit_id = $unit_id;
    }
    
    function setItemType($item_type) {
        $this->item_type = $item_type;
    }
    
    function setUnitCost($unit_cost) {
        $this->unit_cost = $unit_cost;
    }
    
    function setBillingPrice($billing_price) {
        $this->billing_price = $billing_price;
    }
    
    function setMinStock($min_stock) {
        $this->min_stock = $min_stock;
    }
    
    function setReorderLevel($reorder_level) {
        $this->reorder_level = $reorder_level;
    }
    
    function setIsExpiryRequired($is_expiry_required) {
        $this->is_expiry_required = $is_expiry_required;
    }
    
    function setIsSerialRequired($is_serial_required) {
        $this->is_serial_required = $is_serial_required;
    }
    
    function setDescription($description) {
        $this->description = $description;
    }
    
    function setCurrentStock($current_stock) {
        $this->current_stock = $current_stock;
    }
    
    function setStatus($status) {
        $this->status = $status;
    }
    
    // ==================== GRN OPERATIONS ====================
    

    
    /**
     * Insert GRN Items
     */
    function insertGrnItem($grn_id, $item_id, $quantity, $rate, $batch_no, $expiry_date) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        $total_cost = $quantity * $rate;
        
        // Insert into cathlab_grn_items with batch tracking
        $item_query = "INSERT INTO cathlab_grn_items (grn_id, item_id, quantity, cost_price, batch_number, expiry_date, total_cost, created_at) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
        
        $stmt = mysqli_prepare($con, $item_query);
        
        if(!$stmt) {
            throw new Exception("Query prepare failed: " . mysqli_error($con));
        }
        
        mysqli_stmt_bind_param($stmt, "iiddssd", $grn_id, $item_id, $quantity, $rate, $batch_no, $expiry_date, $total_cost);
        
        if(!mysqli_stmt_execute($stmt)) {
            throw new Exception("GRN item insertion failed: " . mysqli_error($con));
        }
        
        return true;
    }
    
    /**
     * Update Stock after GRN
     */
    function updateStockFromGrn($item_id, $quantity) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        $update_stock = "UPDATE cathlab_store_items SET current_stock = current_stock + ? WHERE id = ?";
        $stmt = mysqli_prepare($con, $update_stock);
        mysqli_stmt_bind_param($stmt, "di", $quantity, $item_id);
        
        if(!mysqli_stmt_execute($stmt)) {
            throw new Exception("Stock update failed: " . mysqli_error($con));
        }
        
        return true;
    }
    
    /**
     * Create Stock Ledger Entry
     */
    function createStockLedger($item_id, $transaction_type, $quantity, $reference_type, $reference_id, $description) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        $store_id = 1; // CathLab store ID
        
        $ledger_query = "INSERT INTO cathlab_stock_ledger (store_id, item_id, transaction_type, quantity, reference_type, reference_id, remarks, created_by, created_at) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        
        $stmt = mysqli_prepare($con, $ledger_query);
        
        if(!$stmt) {
            throw new Exception("Stock ledger prepare failed: " . mysqli_error($con));
        }
        
        $created_by = $_SESSION['loginid'] ?? 0;

        // Keep bind order exactly aligned with insert column order:
        // reference_type (string), reference_id (int)
        mysqli_stmt_bind_param($stmt, "iisdsisi", $store_id, $item_id, $transaction_type, $quantity, $reference_type, $reference_id, $description, $created_by);
        
        if(!mysqli_stmt_execute($stmt)) {
            throw new Exception("Stock ledger entry failed: " . mysqli_error($con));
        }
        
        return true;
    }
    
    
    function insertBatchMaster($item_id, $batch_number, $expiry_date, $quantity, $cost_price, $supplier_name = '', $invoice_number = '', $store_id = 1) {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        
        $insert_query = "INSERT INTO cathlab_batch_master 
                        (item_id, store_id, batch_number, expiry_date, cost_price, total_quantity, available_quantity, supplier_name, invoice_number, status, created_at, updated_at) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'active', NOW(), NOW())";
        
        $stmt = mysqli_prepare($con, $insert_query);
        
        if(!$stmt) {
            throw new Exception("Batch master query prepare failed: " . mysqli_error($con));
        }
        
        mysqli_stmt_bind_param($stmt, "issdddsss", 
            $item_id,
            $store_id,
            $batch_number,
            $expiry_date,
            $cost_price,
            $quantity,
            $quantity,
            $supplier_name,
            $invoice_number
        );
        
        if(mysqli_stmt_execute($stmt)) {
            return mysqli_insert_id($con);
        } else {
            throw new Exception("Batch master insertion failed: " . mysqli_error($con));
        }
    }
    
    /**
     * Deduct Stock from Batch
     */
    function deductBatchStock($batch_id, $quantity) {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        
        $update_query = "UPDATE cathlab_batch_master 
                        SET available_quantity = available_quantity - ?,
                            consumed_quantity = consumed_quantity + ?,
                            updated_at = NOW()
                        WHERE id = ? AND available_quantity >= ?";
        
        $stmt = mysqli_prepare($con, $update_query);
        
        if(!$stmt) {
            throw new Exception("Batch stock deduction prepare failed: " . mysqli_error($con));
        }
        
        mysqli_stmt_bind_param($stmt, "ddid", $quantity, $quantity, $batch_id, $quantity);
        
        if(!mysqli_stmt_execute($stmt)) {
            throw new Exception("Batch stock deduction failed: " . mysqli_error($con));
        }
        
        if(mysqli_stmt_affected_rows($stmt) == 0) {
            throw new Exception("Insufficient stock in batch or batch not found");
        }
        
        return true;
    }
    
    /**
     * Deduct Stock for Procedure
     */
    function deductStockForProcedure($item_id, $quantity) {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        
        $update_query = "UPDATE cathlab_store_items 
                        SET current_stock = current_stock - ?
                        WHERE id = ? AND current_stock >= ?";
        
        $stmt = mysqli_prepare($con, $update_query);
        
        if(!$stmt) {
            throw new Exception("Stock deduction prepare failed: " . mysqli_error($con));
        }
        
        mysqli_stmt_bind_param($stmt, "did", $quantity, $item_id, $quantity);
        
        if(!mysqli_stmt_execute($stmt)) {
            throw new Exception("Stock deduction failed: " . mysqli_error($con));
        }
        
        if(mysqli_stmt_affected_rows($stmt) == 0) {
            throw new Exception("Insufficient stock or item not found");
        }
        
        return true;
    }
    
    /**
     * Insert CathLab Procedure
     */
    function insertCathLabProcedure($procedure_code, $procedure_type, $procedure_date, $procedure_time, $doctor_id, $admission_id, $status, $observations, $duration_minutes) {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        
        // Get admission details to populate patient_id and patient_name
        $admission_details = $this->getAdmissionDetails($admission_id);
        if(!$admission_details) {
            throw new Exception("Admission not found");
        }
        
        // Use admission_id as patient_id since central_ipd doesn't have separate patient_id
        $patient_id = $admission_details['id'] ?? 0;
        $patient_name = $admission_details['name'] ?? '';
        
        // Get doctor name
        $doctor_query = "SELECT name FROM doctor WHERE id = ?";
        $stmt_doctor = mysqli_prepare($con, $doctor_query);
        mysqli_stmt_bind_param($stmt_doctor, "i", $doctor_id);
        mysqli_stmt_execute($stmt_doctor);
        $doctor_result = mysqli_stmt_get_result($stmt_doctor);
        $doctor_row = mysqli_fetch_assoc($doctor_result);
        $doctor_name = $doctor_row['name'] ?? '';
        
        $insert_query = "INSERT INTO cathlab_procedure 
                        (procedure_code, patient_id, patient_name, admission_id, doctor_name, procedure_type, procedure_date, procedure_time, duration_minutes, observations, status, created_by, created_at) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        
        $stmt = mysqli_prepare($con, $insert_query);
        
        if(!$stmt) {
            throw new Exception("Procedure insertion prepare failed: " . mysqli_error($con));
        }
        
        $created_by = $_SESSION['loginid'] ?? 0;
        
        mysqli_stmt_bind_param($stmt, "sissssssissi", 
            $procedure_code,
            $patient_id,
            $patient_name,
            $admission_id,
            $doctor_name,
            $procedure_type,
            $procedure_date,
            $procedure_time,
            $duration_minutes,
            $observations,
            $status,
            $created_by
        );
        
        if(mysqli_stmt_execute($stmt)) {
            return mysqli_insert_id($con);
        } else {
            throw new Exception("Procedure insertion failed: " . mysqli_error($con));
        }
    }
    
    /**
     * Insert CathLab Procedure Item
     */
    function insertCathLabProcedureItem($procedure_id, $item_id, $batch_id, $quantity, $cost_price) {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        
        $insert_query = "INSERT INTO cathlab_procedure_items 
                        (procedure_id, item_id, batch_id, quantity_consumed, cost_price, created_at) 
                        VALUES (?, ?, ?, ?, ?, NOW())";
        
        $stmt = mysqli_prepare($con, $insert_query);
        
        if(!$stmt) {
            throw new Exception("Procedure item insertion prepare failed: " . mysqli_error($con));
        }
        
        mysqli_stmt_bind_param($stmt, "iiidd", 
            $procedure_id,
            $item_id,
            $batch_id,
            $quantity,
            $cost_price
        );
        
        if(!mysqli_stmt_execute($stmt)) {
            throw new Exception("Procedure item insertion failed: " . mysqli_error($con));
        }
        
        return true;
    }
    
    /**
     * Get Recent GRNs
     */
    function getRecentGrns($limit = 10) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        $query = "SELECT sg.id, sg.grn_number, sg.grn_date, sg.supplier_id, ss.supplier_name, sg.status, COUNT(sgi.id) as item_count 
                  FROM store_grn sg 
                  LEFT JOIN store_suppliers ss ON sg.supplier_id = ss.id 
                  LEFT JOIN store_grn_items sgi ON sg.id = sgi.grn_id 
                  WHERE sg.grn_type = 'cathlab' 
                  GROUP BY sg.id 
                  ORDER BY sg.grn_date DESC 
                  LIMIT ?";
        
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "i", $limit);
        mysqli_stmt_execute($stmt);
        
        $result = mysqli_stmt_get_result($stmt);
        $grns = array();
        
        while($row = mysqli_fetch_assoc($result)) {
            $grns[] = $row;
        }
        
        return $grns;
    }
    function getStoreCategories() {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        $sql = "SELECT DISTINCT id, category_name FROM store_categories WHERE status = '1' ORDER BY category_name ASC";
        $result = mysqli_query($con, $sql);
        return $result;
    }

    /**
     * Get Store Units (for compatibility)
     */
    function getStoreUnits() {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        $sql = "SELECT DISTINCT id, unit_name FROM store_units WHERE status = '1' ORDER BY unit_name ASC";
        $result = mysqli_query($con, $sql);
        return $result;
    }
    // ==================== BILLING OPERATIONS ====================
    
    /**
     * Insert CathLab Billing Header
     */
    function insertCathLabBilling() {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        $billing_query = "INSERT INTO cathlab_billing (billing_number, billing_date, patient_id, patient_ip_no, procedure_id, subtotal, discount_amount, gst_amount, total_amount, remarks, billed_by, status, created_at) 
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        
        $stmt = mysqli_prepare($con, $billing_query);
        $status = 'pending';
        mysqli_stmt_bind_param($stmt, "ssisidddddsss", $this->billing_number, $this->billing_date, $this->patient_id, $this->patient_ip_no, $this->procedure_id, $this->subtotal, $this->discount_amount, $this->gst_amount, $this->total_amount, $this->remarks, $this->billed_by, $status);
        
        if(mysqli_stmt_execute($stmt)) {
            return mysqli_insert_id($con);
        } else {
            throw new Exception("Billing insertion failed: " . mysqli_error($con));
        }
    }
    
    /**
     * Insert Billing Items
     */
    function insertBillingItem($billing_id, $item_id, $quantity, $unit_price, $billing_price, $markup_percent, $amount) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        $bill_item_query = "INSERT INTO cathlab_billing_items (billing_id, item_id, quantity, unit_price, billing_price, markup_percent, amount) 
                          VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($con, $bill_item_query);
        mysqli_stmt_bind_param($stmt, "iidddds", $billing_id, $item_id, $quantity, $unit_price, $billing_price, $markup_percent, $amount);
        
        if(!mysqli_stmt_execute($stmt)) {
            throw new Exception("Billing item insertion failed: " . mysqli_error($con));
        }
        
        return true;
    }
    
    /**
     * Deduct Stock for Billing
     */
    function deductStockForBilling($item_id, $quantity) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        $deduct_stock = "UPDATE cathlab_store_items SET current_stock = current_stock - ? WHERE id = ?";
        $stmt = mysqli_prepare($con, $deduct_stock);
        mysqli_stmt_bind_param($stmt, "di", $quantity, $item_id);
        
        if(!mysqli_stmt_execute($stmt)) {
            throw new Exception("Stock deduction failed: " . mysqli_error($con));
        }
        
        return true;
    }
    
    /**
     * Update Patient Billing Status
     */
    function updatePatientBillingStatus($patient_admission_id, $billing_id) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        $update_patient = "UPDATE patient_admission SET cathlab_billed = 'yes', cathlab_billing_id = ? WHERE id = ?";
        $stmt = mysqli_prepare($con, $update_patient);
        mysqli_stmt_bind_param($stmt, "ii", $billing_id, $patient_admission_id);
        
        if(!mysqli_stmt_execute($stmt)) {
            throw new Exception("Patient billing status update failed: " . mysqli_error($con));
        }
        
        return true;
    }
    
    /**
     * Get Recent Billings
     */
    function getRecentBillings($limit = 10) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        $query = "SELECT cb.id, cb.billing_number, cb.billing_date, cb.patient_ip_no, cb.total_amount, cb.status, COUNT(cbi.id) as item_count 
                  FROM cathlab_billing cb 
                  LEFT JOIN cathlab_billing_items cbi ON cb.id = cbi.billing_id 
                  GROUP BY cb.id 
                  ORDER BY cb.billing_date DESC 
                  LIMIT ?";
        
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "i", $limit);
        mysqli_stmt_execute($stmt);
        
        $result = mysqli_stmt_get_result($stmt);
        $billings = array();
        
        while($row = mysqli_fetch_assoc($result)) {
            $billings[] = $row;
        }
        
        return $billings;
    }
    
    // ==================== ITEM OPERATIONS ====================
    
    /**
     * Insert CathLab Item
     */
    function insertCathLabItem() {

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $newlogin = new userlogin;
    $con = $newlogin->dbconnect();

    if (!$con) {
        die("DB connection failed");
    }

    $sql = "INSERT INTO cathlab_store_items 
    (item_code, item_name, category_id, unit_id, item_type,
     unit_cost, billing_price, min_stock, reorder_level,
     is_expiry_required, is_serial_required,
     description, current_stock, status, created_at)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

    $stmt = mysqli_prepare($con, $sql);

    if (!$stmt) {
        die("Prepare failed: " . mysqli_error($con));
    }

    $current_stock = 0;
    $status = 'active';

    // FORCE integer flags
    $this->is_expiry_required = !empty($this->is_expiry_required) ? 1 : 0;
    $this->is_serial_required = !empty($this->is_serial_required) ? 1 : 0;

    mysqli_stmt_bind_param(
        $stmt,
        "ssiisddddiisds",
        $this->item_code,
        $this->item_name,
        $this->category_id,
        $this->unit_id,
        $this->item_type,
        $this->unit_cost,
        $this->billing_price,
        $this->min_stock,
        $this->reorder_level,
        $this->is_expiry_required,
        $this->is_serial_required,
        $this->description,
        $current_stock,
        $status
    );

    if (!mysqli_stmt_execute($stmt)) {
        die("Execute failed: " . mysqli_stmt_error($stmt));
    }

    echo "INSERTED ID: " . mysqli_insert_id($con);
    exit;
}


    
    /**
     * Get All CathLab Items
     */
   function getCathLabItems($item_type = null, $stock_status = null, $search_term = null) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        $query = "SELECT 
                    csi.id,
                    csi.item_code,
                    csi.item_name,
                    csi.item_type,
                    csi.category_id,
                    sc.category_name,
                    csi.unit_id,
                    su.unit_name,
                    csi.unit_cost,
                    csi.billing_price,
                    csi.current_stock,
                    csi.min_stock,
                    csi.reorder_level,
                    csi.status
                FROM cathlab_store_items csi
                LEFT JOIN store_categories sc ON csi.category_id = sc.id
                LEFT JOIN store_units su ON csi.unit_id = su.id
                WHERE csi.status = 'active' and csi.item_type ".($item_type ? "= '".mysqli_real_escape_string($con, $item_type)."'" : "IS NOT NULL")."  
                ".($stock_status == 'low_stock' ? "AND csi.current_stock <= csi.min_stock" : "")." AND 
                ".($search_term ? "(csi.item_name LIKE '%".mysqli_real_escape_string($con, $search_term)."%' OR csi.item_code LIKE '%".mysqli_real_escape_string($con, $search_term)."%')" : "1")."
                ORDER BY csi.item_name ASC";
        // echo $query;exit;
        $result = mysqli_query($con, $query);
        
        if(!$result) {
            throw new Exception("Query failed: " . mysqli_error($con));
        }
        
        return $result;
    }
    /**
     * Get Item by ID
     */
    function getCathLabItemById($item_id) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        $query = "SELECT 
                    csi.id,
                    csi.item_code,
                    csi.item_name,
                    csi.item_type,
                    csi.category_id,
                    sc.category_name,
                    csi.unit_id,
                    su.unit_name,
                    csi.unit_cost,
                    csi.billing_price,
                    csi.current_stock,
                    csi.min_stock,
                    csi.reorder_level,
                    csi.is_expiry_required,
                    csi.is_serial_required,
                    csi.description,
                    csi.status
                FROM cathlab_store_items csi
                LEFT JOIN store_categories sc ON csi.category_id = sc.id
                LEFT JOIN store_units su ON csi.unit_id = su.id
                WHERE csi.id = ?";
        
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "i", $item_id);
        mysqli_stmt_execute($stmt);
        
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }
    
    /**
     * Update CathLab Item
     */
    function updateCathLabItem($item_id) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        $update_query = "UPDATE cathlab_store_items 
                        SET item_code = ?, 
                            item_name = ?, 
                            category_id = ?, 
                            unit_id = ?, 
                            item_type = ?, 
                            unit_cost = ?, 
                            billing_price = ?, 
                            min_stock = ?, 
                            reorder_level = ?, 
                            is_expiry_required = ?, 
                            is_serial_required = ?, 
                            description = ?,
                            status = ?
                        WHERE id = ?";
        
        $stmt = mysqli_prepare($con, $update_query);
        mysqli_stmt_bind_param($stmt, "ssiiiddddiiisi", $this->item_code, $this->item_name, $this->category_id, $this->unit_id, $this->item_type, $this->unit_cost, $this->billing_price, $this->min_stock, $this->reorder_level, $this->is_expiry_required, $this->is_serial_required, $this->description, $this->status, $item_id);
        
        if(!mysqli_stmt_execute($stmt)) {
            throw new Exception("Item update failed: " . mysqli_error($con));
        }
        
        return true;
    }
    
    /**
     * Delete CathLab Item (Soft Delete)
     */
    function deleteCathLabItem($item_id) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        $delete_query = "UPDATE cathlab_store_items SET status = 'inactive', deleted_at = NOW() WHERE id = ?";
        $stmt = mysqli_prepare($con, $delete_query);
        mysqli_stmt_bind_param($stmt, "i", $item_id);
        
        if(!mysqli_stmt_execute($stmt)) {
            throw new Exception("Item deletion failed: " . mysqli_error($con));
        }
        
        return true;
    }
    
    /**
     * Bulk Import Items from CSV
     */
    function bulkImportItems($csv_file) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        $imported_count = 0;
        $error_count = 0;
        $errors = array();

        if(($handle = fopen($csv_file, "r")) !== FALSE) {
            // Skip header row
            fgetcsv($handle);

            while(($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $item_code = $data[0] ?? '';
                $item_name = $data[1] ?? '';
                $category = $data[2] ?? '';
                $item_type = $data[3] ?? '';
                $unit = $data[4] ?? '';
                $unit_cost = $data[5] ?? 0;
                $billing_price = $data[6] ?? 0;
                $min_stock = $data[7] ?? 0;
                $description = $data[8] ?? '';

                try {
                    // Get category and unit IDs
                    $cat_result = mysqli_query($con, "SELECT id FROM store_categories WHERE category_name = '" . mysqli_real_escape_string($con, $category) . "'");
                    $category_id = ($cat_row = mysqli_fetch_assoc($cat_result)) ? $cat_row['id'] : 1;

                    $unit_result = mysqli_query($con, "SELECT id FROM store_units WHERE unit_name = '" . mysqli_real_escape_string($con, $unit) . "'");
                    $unit_id = ($unit_row = mysqli_fetch_assoc($unit_result)) ? $unit_row['id'] : 1;

                    // Insert item
                    $insert = "INSERT INTO cathlab_store_items (item_code, item_name, category_id, unit_id, item_type, unit_cost, billing_price, min_stock, description, current_stock, status, created_at) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
                    
                    $stmt = mysqli_prepare($con, $insert);
                    $current_stock = 0;
                    $status = 'active';
                    mysqli_stmt_bind_param($stmt, "ssiiidddsis", $item_code, $item_name, $category_id, $unit_id, $item_type, $unit_cost, $billing_price, $min_stock, $description, $current_stock, $status);
                    
                    if(mysqli_stmt_execute($stmt)) {
                        $imported_count++;
                    } else {
                        $error_count++;
                        $errors[] = "Error importing item: " . $item_code;
                    }
                } catch (Exception $e) {
                    $error_count++;
                    $errors[] = "Error processing row: " . $e->getMessage();
                }
            }
            fclose($handle);
        }

        return array(
            'imported' => $imported_count,
            'errors' => $error_count,
            'error_messages' => $errors
        );
    }
    
    // ==================== UTILITY OPERATIONS ====================
    
    /**
     * Log Activity
     */
    function logActivity($user_id, $module, $action, $description) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        $log_query = "INSERT INTO activity_log (user_id, module, action, description, timestamp) 
                      VALUES (?, ?, ?, ?, NOW())";
        $stmt = mysqli_prepare($con, $log_query);
        mysqli_stmt_bind_param($stmt, "isss", $user_id, $module, $action, $description);
        
        return mysqli_stmt_execute($stmt);
    }
    
    /**
     * Get Low Stock Items
     */
    function getLowStockItems() {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        $query = "SELECT 
                    csi.id,
                    csi.item_code,
                    csi.item_name,
                    csi.current_stock,
                    csi.min_stock,
                    csi.reorder_level,
                    su.unit_name
                FROM cathlab_store_items csi
                LEFT JOIN store_units su ON csi.unit_id = su.id
                WHERE csi.status = 'active' AND csi.current_stock <= csi.min_stock
                ORDER BY csi.current_stock ASC";
        
        $result = mysqli_query($con, $query);
        
        if(!$result) {
            throw new Exception("Query failed: " . mysqli_error($con));
        }
        
        return $result;
    }
    
    /**
     * Get Inventory Summary
     */
    function getInventorySummary() {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        $query = "SELECT 
                    COUNT(DISTINCT id) as total_items,
                    SUM(CASE WHEN current_stock > 0 THEN current_stock ELSE 0 END) as total_qty,
                    SUM(current_stock * unit_cost) as total_value
                FROM cathlab_store_items
                WHERE status = 'active'";
        
        $result = mysqli_query($con, $query);
        
        if($result) {
            return mysqli_fetch_assoc($result);
        } else {
            throw new Exception("Query failed: " . mysqli_error($con));
        }
    }
    
    /**
     * Get Cost vs Revenue
     */
    function getCostVsRevenue($period = 'monthly') {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        if($period == 'monthly') {
            $group_by = "DATE_TRUNC(cb.billing_date, MONTH)";
        } else {
            $group_by = "DATE(cb.billing_date)";
        }
        
        $query = "SELECT 
                    DATE(cb.billing_date) as date,
                    SUM(cbi.quantity * csi.unit_cost) as total_cost,
                    SUM(cbi.amount) as total_revenue,
                    (SUM(cbi.amount) - SUM(cbi.quantity * csi.unit_cost)) as profit
                FROM cathlab_billing cb
                LEFT JOIN cathlab_billing_items cbi ON cb.id = cbi.billing_id
                LEFT JOIN cathlab_store_items csi ON cbi.item_id = csi.id
                WHERE cb.status = 'billed'
                GROUP BY DATE(cb.billing_date)
                ORDER BY DATE(cb.billing_date) DESC";
        
        $result = mysqli_query($con, $query);
        
        if(!$result) {
            throw new Exception("Query failed: " . mysqli_error($con));
        }
        
        return $result;
    }
    /**
     * Generate CathLab GRN Number
     */
    function generateCathLabGRNNumber() {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        $sql = "SELECT grn_number FROM `cathlab_grn` ORDER BY id DESC LIMIT 1";
        $res = mysqli_query($con, $sql);
        
        if(mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            $last_number = intval(substr($row['grn_number'], 7)); // CGRN + yy + XXXXX
            $new_number = $last_number + 1;
        } else {
            $new_number = 1;
        }
        
        return 'CGRN' . date('y') . str_pad($new_number, 5, '0', STR_PAD_LEFT);
    }

    // ==================== CATHLAB GRN OPERATIONS ====================

  
    
    function setTotalItems($total_items) {
        $this->total_items = $total_items;
    }
    
    function setTotalQuantity($total_quantity) {
        $this->total_quantity = $total_quantity;
    }
    
    function setTotalCost($total_cost) {
        $this->total_cost = $total_cost;
    }
    

   
    /**
     * Insert CathLab GRN
     */
    function insertCathLabGrn($store_id = null) {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        
        if(!$store_id) {
            $store_id = 1; // Default store ID for CathLab
        }
        
        $insert_query = "INSERT INTO cathlab_grn 
                        (grn_number, store_id, grn_date, supplier_name, total_items, total_quantity, total_cost, remarks, created_by, created_at, updated_at) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
        
        $stmt = mysqli_prepare($con, $insert_query);
        
        if(!$stmt) {
            throw new Exception("Query prepare failed: " . mysqli_error($con));
        }
        
        mysqli_stmt_bind_param($stmt, "sissdddsi", 
            $this->grn_number, 
            $store_id, 
            $this->grn_date, 
            $this->supplier_name, 
            $this->total_items, 
            $this->total_quantity, 
            $this->total_cost, 
            $this->remarks, 
            $this->received_by
        );
        
        if(mysqli_stmt_execute($stmt)) {
            return mysqli_insert_id($con);
        } else {
            throw new Exception("GRN insertion failed: " . mysqli_error($con));
        }
    }

    /**
     * Get All CathLab GRNs
     */
    function getCathLabGrns($store_id = 1, $limit = 50, $offset = 0) {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        
        $query = "SELECT * FROM cathlab_grn 
                  WHERE store_id = ? 
                  ORDER BY created_at DESC 
                  LIMIT ? OFFSET ?";
        
        $stmt = mysqli_prepare($con, $query);
        
        if(!$stmt) {
            throw new Exception("Query prepare failed: " . mysqli_error($con));
        }
        
        mysqli_stmt_bind_param($stmt, "iii", $store_id, $limit, $offset);
        mysqli_stmt_execute($stmt);
        
        $result = mysqli_stmt_get_result($stmt);
        return $result;
    }

    /**
     * Get CathLab GRN by ID
     */
    function getCathLabGrnById($grn_id) {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        
        $query = "SELECT * FROM cathlab_grn WHERE id = ?";
        
        $stmt = mysqli_prepare($con, $query);
        
        if(!$stmt) {
            throw new Exception("Query prepare failed: " . mysqli_error($con));
        }
        
        mysqli_stmt_bind_param($stmt, "i", $grn_id);
        mysqli_stmt_execute($stmt);
        
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }

    /**
     * Get CathLab GRN Items by GRN ID
     */
    function getCathLabGrnItems($grn_id) {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        
        $query = "SELECT 
                    cgi.id,
                    cgi.item_id,
                    cgi.quantity,
                    cgi.cost_price,
                    cgi.batch_number,
                    cgi.expiry_date,
                    cgi.total_cost,
                    csi.item_name,
                    csi.item_code,
                    su.unit_name
                FROM cathlab_grn_items cgi
                LEFT JOIN cathlab_store_items csi ON cgi.item_id = csi.id
                LEFT JOIN store_units su ON csi.unit_id = su.id
                WHERE cgi.grn_id = ?";
        
        $stmt = mysqli_prepare($con, $query);
        
        if(!$stmt) {
            throw new Exception("Query prepare failed: " . mysqli_error($con));
        }
        
        mysqli_stmt_bind_param($stmt, "i", $grn_id);
        mysqli_stmt_execute($stmt);
        
        $result = mysqli_stmt_get_result($stmt);
        return $result;
    }

    /**
     * Update CathLab GRN
     */
    function updateCathLabGrn($grn_id) {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        
        $update_query = "UPDATE cathlab_grn 
                        SET grn_number = ?, 
                            grn_date = ?, 
                            supplier_name = ?, 
                            total_items = ?, 
                            total_quantity = ?, 
                            total_cost = ?, 
                            remarks = ?, 
                            updated_at = NOW() 
                        WHERE id = ?";
        
        $stmt = mysqli_prepare($con, $update_query);
        
        if(!$stmt) {
            throw new Exception("Query prepare failed: " . mysqli_error($con));
        }
        
        mysqli_stmt_bind_param($stmt, "siddddssi", 
            $this->grn_number, 
            $this->grn_date, 
            $this->supplier_name, 
            $this->total_items, 
            $this->total_quantity, 
            $this->total_cost, 
            $this->remarks, 
            $grn_id
        );
        
        if(mysqli_stmt_execute($stmt)) {
            return true;
        } else {
            throw new Exception("GRN update failed: " . mysqli_error($con));
        }
    }

    /**
     * Delete CathLab GRN
     */
    function deleteCathLabGrn($grn_id) {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        
        $delete_query = "DELETE FROM cathlab_grn WHERE id = ?";
        
        $stmt = mysqli_prepare($con, $delete_query);
        
        if(!$stmt) {
            throw new Exception("Query prepare failed: " . mysqli_error($con));
        }
        
        mysqli_stmt_bind_param($stmt, "i", $grn_id);
        
        if(mysqli_stmt_execute($stmt)) {
            return true;
        } else {
            throw new Exception("GRN deletion failed: " . mysqli_error($con));
        }
    }

    /**
     * Get CathLab GRN by Number
     */
    function getCathLabGrnByNumber($grn_number) {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        
        $query = "SELECT * FROM cathlab_grn WHERE grn_number = ?";
        
        $stmt = mysqli_prepare($con, $query);
        
        if(!$stmt) {
            throw new Exception("Query prepare failed: " . mysqli_error($con));
        }
        
        mysqli_stmt_bind_param($stmt, "s", $grn_number);
        mysqli_stmt_execute($stmt);
        
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }

    /**
     * Get GRN Statistics
     */
    function getCathLabGrnStats($store_id = 1, $from_date = null, $to_date = null) {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        
        $query = "SELECT 
                    COUNT(DISTINCT id) as total_grns,
                    SUM(total_items) as total_items,
                    SUM(total_quantity) as total_quantity,
                    SUM(total_cost) as total_cost,
                    MIN(grn_date) as first_grn_date,
                    MAX(grn_date) as last_grn_date
                  FROM cathlab_grn 
                  WHERE store_id = ?";
        
        if($from_date && $to_date) {
            $query .= " AND grn_date BETWEEN ? AND ?";
        }
        
        $stmt = mysqli_prepare($con, $query);
        
        if(!$stmt) {
            throw new Exception("Query prepare failed: " . mysqli_error($con));
        }
        
        if($from_date && $to_date) {
            mysqli_stmt_bind_param($stmt, "iss", $store_id, $from_date, $to_date);
        } else {
            mysqli_stmt_bind_param($stmt, "i", $store_id);
        }
        
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }

    /**
     * Get Store Items (CathLab Items)
     */
    function getStoreItems() {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        
        $sql = "SELECT 
                    csi.id,
                    csi.item_code,
                    csi.item_name,
                    csi.category_id,
                    sc.category_name,
                    csi.unit_id,
                    su.unit_name,
                    csi.unit_cost,
                    csi.billing_price,
                    csi.current_stock,
                    csi.min_stock,
                    csi.reorder_level,
                    csi.item_type
                FROM cathlab_store_items csi
                LEFT JOIN store_categories sc ON csi.category_id = sc.id
                LEFT JOIN store_units su ON csi.unit_id = su.id
                WHERE csi.status = 'active'
                ORDER BY csi.item_name ASC";
        
        $result = mysqli_query($con, $sql);
        return $result;
    }

    /**
     * Get Active Suppliers
     */
    function getActiveSuppliers() {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        
        $sql = "SELECT id, supplier_name, contact_person, phone, email, address 
                FROM store_suppliers 
                WHERE status = '1' 
                ORDER BY supplier_name ASC";
        
        $result = mysqli_query($con, $sql);
        return $result;
    }

    /**
     * Get Pending Purchase Orders
     */
    function getPendingPurchaseOrders() {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        
        $sql = "SELECT 
                    spo.id,
                    spo.po_number,
                    spo.supplier_id,
                    ss.supplier_name,
                    spo.po_date,
                    spo.expected_date,
                    spo.total_amount,
                    spo.status
                FROM store_po spo
                LEFT JOIN store_suppliers ss ON spo.supplier_id = ss.id
                WHERE spo.status IN ('pending', 'partial')
                ORDER BY spo.po_date DESC";
        
        $result = mysqli_query($con, $sql);
        return $result;
    }

    /**
     * Get Purchase Order by ID
     */
    function getPurchaseOrderById($po_id) {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        
        $query = "SELECT 
                    spo.id,
                    spo.po_number,
                    spo.supplier_id,
                    ss.supplier_name,
                    spo.po_date,
                    spo.expected_date,
                    spo.total_amount,
                    spo.status,
                    spo.remarks
                FROM store_po spo
                LEFT JOIN store_suppliers ss ON spo.supplier_id = ss.id
                WHERE spo.id = ?";
        
        $stmt = mysqli_prepare($con, $query);
        
        if(!$stmt) {
            throw new Exception("Query prepare failed: " . mysqli_error($con));
        }
        
        mysqli_stmt_bind_param($stmt, "i", $po_id);
        mysqli_stmt_execute($stmt);
        
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }

    /**
     * Get Supplier Name by ID
     */
    function getSupplierNameById($supplier_id) {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        
        $query = "SELECT supplier_name FROM store_suppliers WHERE id = ?";
        
        $stmt = mysqli_prepare($con, $query);
        
        if(!$stmt) {
            throw new Exception("Query prepare failed: " . mysqli_error($con));
        }
        
        mysqli_stmt_bind_param($stmt, "i", $supplier_id);
        mysqli_stmt_execute($stmt);
        
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        
        return $row['supplier_name'] ?? 'Unknown';
    }

    /**
     * Finalize CathLab Billing - Deduct stock and mark as finalized
     * Called when billing is approved/finalized to deduct items from inventory
     * 
     * @param int $billing_id - Billing ID to finalize
     * @return bool - Success status
     */
    function finalizeCathLabBilling($billing_id) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        try {
            // Get all billing items for this billing
            $items_query = "SELECT item_id, quantity FROM cathlab_billing_items WHERE billing_id = ?";
            $stmt = mysqli_prepare($con, $items_query);
            mysqli_stmt_bind_param($stmt, "i", $billing_id);
            mysqli_stmt_execute($stmt);
            $items_result = mysqli_stmt_get_result($stmt);
            
            // Deduct stock for each item
            while($item = mysqli_fetch_assoc($items_result)) {
                $item_id = $item['item_id'];
                $quantity = $item['quantity'];
                
                // Deduct from CathLab stock
                $this->deductStockForBilling($item_id, $quantity);
                
                // Create stock ledger entry
                $negative_qty = -$quantity;
                $this->createStockLedger($item_id, 'BILLING_OUT', $negative_qty, 'BILLING', $billing_id, 'Items billed - Billing ID ' . $billing_id);
            }
            
            // Update billing status to finalized
            $finalize_query = "UPDATE cathlab_billing SET status = 'finalized', finalized_at = NOW() WHERE id = ?";
            $stmt = mysqli_prepare($con, $finalize_query);
            mysqli_stmt_bind_param($stmt, "i", $billing_id);
            
            if(!mysqli_stmt_execute($stmt)) {
                throw new Exception("Billing finalization failed: " . mysqli_error($con));
            }
            
            return true;
            
        } catch (Exception $e) {
            throw new Exception("Error finalizing billing: " . $e->getMessage());
        }
    }

    /**
     * Get All CathLab Procedures with pagination
     * 
     * @param int $limit - Number of records per page
     * @param int $offset - Starting record
     * @return mysqli_result - Procedures result set
     */
    function getCathLabProcedures($limit = 100, $offset = 0) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        $query = "SELECT 
                    cp.id,
                    cp.procedure_code,
                    cp.procedure_type,
                    cp.procedure_date,
                    cp.procedure_time,
                    cp.patient_name,
                    cp.doctor_name,
                    cp.status,
                    cp.created_at,
                    COUNT(cpi.id) as total_items,
                    SUM(cpi.quantity_consumed) as total_quantity,
                    SUM(cpi.quantity_consumed * cpi.cost_price) as total_cost
                  FROM cathlab_procedure cp
                  LEFT JOIN cathlab_procedure_items cpi ON cp.id = cpi.procedure_id
                  GROUP BY cp.id
                  ORDER BY cp.procedure_date DESC, cp.created_at DESC
                  LIMIT ? OFFSET ?";
        
        $stmt = mysqli_prepare($con, $query);
        
        if (!$stmt) {
            throw new Exception("Procedure query preparation failed: " . mysqli_error($con));
        }
        
        mysqli_stmt_bind_param($stmt, "ii", $limit, $offset);
        mysqli_stmt_execute($stmt);
        
        return mysqli_stmt_get_result($stmt);
    }

    /**
     * Get CathLab Procedure Statistics
     * 
     * @return array - Statistics array
     */
    function getCathLabProcedureStats() {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        $query = "SELECT 
                    COUNT(DISTINCT cp.id) as total_procedures,
                    COUNT(DISTINCT cpi.item_id) as total_items,
                    SUM(cpi.quantity_consumed) as total_quantity,
                    SUM(cpi.quantity_consumed * cpi.cost_price) as total_cost
                  FROM cathlab_procedure cp
                  LEFT JOIN cathlab_procedure_items cpi ON cp.id = cpi.procedure_id";
        
        $stmt = mysqli_prepare($con, $query);
        
        if (!$stmt) {
            throw new Exception("Procedure stats query preparation failed: " . mysqli_error($con));
        }
        
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        return mysqli_fetch_assoc($result) ?? array(
            'total_procedures' => 0,
            'total_items' => 0,
            'total_quantity' => 0,
            'total_cost' => 0
        );
    }

    /**
     * Get CathLab Procedure Header by ID
     *
     * @param int $procedure_id
     * @return array|null
     */
    function getCathLabProcedureById($procedure_id) {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();

        $query = "SELECT
                    cp.*,
                    COALESCE(pi.total_items, 0) AS total_items,
                    COALESCE(pi.total_quantity, 0) AS total_quantity,
                    COALESCE(pi.total_cost, 0) AS total_cost,
                    COALESCE(bi.billing_count, 0) AS billing_count,
                    COALESCE(bi.billed_amount, 0) AS billed_amount,
                    COALESCE(bi.payment_status, 'pending') AS payment_status
                  FROM cathlab_procedure cp
                  LEFT JOIN (
                    SELECT
                        procedure_id,
                        COUNT(*) AS total_items,
                        SUM(quantity_consumed) AS total_quantity,
                        SUM(quantity_consumed * cost_price) AS total_cost
                    FROM cathlab_procedure_items
                    GROUP BY procedure_id
                  ) pi ON cp.id = pi.procedure_id
                  LEFT JOIN (
                    SELECT
                        procedure_id,
                        COUNT(*) AS billing_count,
                        SUM(total_amount) AS billed_amount,
                        MAX(payment_status) AS payment_status
                    FROM cathlab_billing
                    GROUP BY procedure_id
                  ) bi ON cp.id = bi.procedure_id
                  WHERE cp.id = ?
                  LIMIT 1";

        $stmt = mysqli_prepare($con, $query);
        if (!$stmt) {
            throw new Exception("Procedure detail query preparation failed: " . mysqli_error($con));
        }

        mysqli_stmt_bind_param($stmt, "i", $procedure_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        }

        return null;
    }

    /**
     * Get CathLab Procedure Items by Procedure ID
     *
     * @param int $procedure_id
     * @return mysqli_result
     */
    function getCathLabProcedureItems($procedure_id) {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();

        $query = "SELECT
                    cpi.id,
                    cpi.item_id,
                    cpi.batch_id,
                    cpi.quantity_consumed,
                    cpi.cost_price,
                    cpi.remarks,
                    cpi.status,
                    cpi.created_at,
                    csi.item_code,
                    csi.item_name,
                    cbm.batch_number,
                    cbm.expiry_date
                  FROM cathlab_procedure_items cpi
                  LEFT JOIN cathlab_store_items csi ON cpi.item_id = csi.id
                  LEFT JOIN cathlab_batch_master cbm ON cpi.batch_id = cbm.id
                  WHERE cpi.procedure_id = ?
                  ORDER BY cpi.id ASC";

        $stmt = mysqli_prepare($con, $query);
        if (!$stmt) {
            throw new Exception("Procedure items query preparation failed: " . mysqli_error($con));
        }

        mysqli_stmt_bind_param($stmt, "i", $procedure_id);
        mysqli_stmt_execute($stmt);

        return mysqli_stmt_get_result($stmt);
    }

    /**
     * Get CathLab Store ID
     */
    function getCathLabStoreId() {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        $query = "SELECT id FROM store WHERE store_type = 'cathlab' LIMIT 1";
        $stmt = mysqli_prepare($con, $query);
        
        if (!$stmt) {
            throw new Exception("Store query preparation failed: " . mysqli_error($con));
        }
        
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        
        return $row['id'] ?? 0;
    }

    /**
     * Get Admission Details by ID
     */
    function getAdmissionDetails($admission_id) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        $query = "SELECT * 
                  FROM central_ipd 
                  WHERE id = ? LIMIT 1";
        
        $stmt = mysqli_prepare($con, $query);
        
        if (!$stmt) {
            throw new Exception("Admission query preparation failed: " . mysqli_error($con));
        }
        
        mysqli_stmt_bind_param($stmt, "i", $admission_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if($result && mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        }
        
        return null;
    }

    /**
     * Get Active Doctors
     */
    function getActiveDoctors() {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        $query = "SELECT id, name FROM doctor WHERE role = 'doctor' AND status = 'active'";
        $stmt = mysqli_prepare($con, $query);
        
        if (!$stmt) {
            throw new Exception("Doctors query preparation failed: " . mysqli_error($con));
        }
        
        mysqli_stmt_execute($stmt);
        return mysqli_stmt_get_result($stmt);
    }

    /**
     * Get Active CathLab Items
     */
    function getActiveCathLabItems() {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        $query = "SELECT id, item_code, item_name, unit_cost as cost_price, billing_price as selling_price 
                  FROM cathlab_store_items 
                  WHERE 
                   status = 'active' AND deleted_at IS NULL";
        
        $stmt = mysqli_prepare($con, $query);
        
        if (!$stmt) {
            throw new Exception("Items query preparation failed: " . mysqli_error($con));
        }
        mysqli_stmt_execute($stmt);
        return mysqli_stmt_get_result($stmt);
    }

    /**
     * Generate Next Procedure Code
     */
    function generateProcedureCode() {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        $query = "SELECT MAX(CAST(SUBSTRING(procedure_code, -4) AS UNSIGNED)) as max_num FROM cathlab_procedure";
        $stmt = mysqli_prepare($con, $query);
        
        if (!$stmt) {
            throw new Exception("Procedure code generation failed: " . mysqli_error($con));
        }
        
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        
        $next_num = ($row['max_num'] ?? 0) + 1;
        return "PROC-" . date('Y') . "-" . str_pad($next_num, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Generate Next GRN Number
     */
    function generateGrnNumber($store_id) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        $query = "SELECT MAX(CAST(SUBSTRING(grn_number, LOCATE('-', grn_number, LOCATE('-', grn_number) + 1) + 1) AS UNSIGNED)) as max_num 
                  FROM cathlab_grn WHERE store_id = ?";
        
        $stmt = mysqli_prepare($con, $query);
        
        if (!$stmt) {
            throw new Exception("GRN number generation failed: " . mysqli_error($con));
        }
        
        mysqli_stmt_bind_param($stmt, "i", $store_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        
        $next_num = ($row['max_num'] ?? 0) + 1;
        return "CATHLAB-GRN-" . date('Y-m-d') . "-" . str_pad($next_num, 4, '0', STR_PAD_LEFT);
    }

}


?>
