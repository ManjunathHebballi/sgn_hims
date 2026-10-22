<?php
require_once(__DIR__ . '/session_config.php');
include('dbconnect.php');

class store {
    
    // Properties
    private $name;
    private $status;
    private $category_type;
    private $contact_person;
    private $phone;
    private $email;
    private $gst_number;
    private $address;
    private $item_code;
    private $item_name;
    private $category_id;
    private $unit_id;
    private $item_type;
    private $min_stock;
    private $reorder_level;
    private $is_expiry_required;
    private $is_serial_required;
    private $po_number;
    private $supplier_id;
    private $po_date;
    private $expected_date;
    private $remarks;
    private $grn_number;
    private $po_id;
    private $received_date;
    private $invoice_number;
    private $invoice_date;
    private $issue_number;
    private $issue_date;
    private $department;
    private $requested_by;
    private $issued_by;
    private $item_id;
    private $batch_no;
    private $expiry_date;
    private $serial_no;
    private $quantity;
    private $rate;
    private $amount;
    private $return_number;
    private $return_date;
    private $reason;
    private $returned_by;
    private $adjustment_number;
    private $adjustment_qty;
    private $adjustment_type;
    private $adjusted_by;
    private $adjustment_date;
    private $payment_terms;
    private $total_amount;
    private $grn_id;

    private function getSessionStoreId() {
        if(isset($_SESSION['store_id']) && $_SESSION['store_id'] !== '') {
            return (int)$_SESSION['store_id'];
        }

       
        return 0;
    }
    
    // Setters
    function setName($name) {
        $this->name = $name;
    }
    
    function setStatus($status) {
        $this->status = $status;
    }
    
    function setCategoryType($category_type) {
        $this->category_type = $category_type;
    }
    
    function setContactPerson($contact_person) {
        $this->contact_person = $contact_person;
    }
    
    function setPhone($phone) {
        $this->phone = $phone;
    }
    
    function setEmail($email) {
        $this->email = $email;
    }
    
    function setGstNumber($gst_number) {
        $this->gst_number = $gst_number;
    }
    
    function setAddress($address) {
        $this->address = $address;
    }
    
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
    
    function setPoNumber($po_number) {
        $this->po_number = $po_number;
    }
    
    function setSupplierId($supplier_id) {
        $this->supplier_id = $supplier_id;
    }
    
    function setPoDate($po_date) {
        $this->po_date = $po_date;
    }
    
    function setExpectedDate($expected_date) {
        $this->expected_date = $expected_date;
    }
    
    function setRemarks($remarks) {
        $this->remarks = $remarks;
    }
    
    function setGrnNumber($grn_number) {
        $this->grn_number = $grn_number;
    }
    
    function setPoId($po_id) {
        $this->po_id = $po_id;
    }
    
    function setReceivedDate($received_date) {
        $this->received_date = $received_date;
    }
    
    function setInvoiceNumber($invoice_number) {
        $this->invoice_number = $invoice_number;
    }
    
    function setInvoiceDate($invoice_date) {
        $this->invoice_date = $invoice_date;
    }
    
    function setIssueNumber($issue_number) {
        $this->issue_number = $issue_number;
    }
    
    function setIssueDate($issue_date) {
        $this->issue_date = $issue_date;
    }
    
    function setDepartment($department) {
        $this->department = $department;
    }
    
    function setRequestedBy($requested_by) {
        $this->requested_by = $requested_by;
    }
    
    function setIssuedBy($issued_by) {
        $this->issued_by = $issued_by;
    }
    
    function setItemId($item_id) {
        $this->item_id = $item_id;
    }
    
    function setBatchNo($batch_no) {
        $this->batch_no = $batch_no;
    }
    
    function setExpiryDate($expiry_date) {
        $this->expiry_date = $expiry_date;
    }
    
    function setSerialNo($serial_no) {
        $this->serial_no = $serial_no;
    }
    
    function setQuantity($quantity) {
        $this->quantity = $quantity;
    }
    
    function setRate($rate) {
        $this->rate = $rate;
    }
    
    function setAmount($amount) {
        $this->amount = $amount;
    }
    
    function setReturnNumber($return_number) {
        $this->return_number = $return_number;
    }
    
    function setReturnDate($return_date) {
        $this->return_date = $return_date;
    }
    
    function setReason($reason) {
        $this->reason = $reason;
    }
    
    function setReturnedBy($returned_by) {
        $this->returned_by = $returned_by;
    }
    
    function setAdjustmentNumber($adjustment_number) {
        $this->adjustment_number = $adjustment_number;
    }
    
    function setAdjustmentQty($adjustment_qty) {
        $this->adjustment_qty = $adjustment_qty;
    }
    
    function setAdjustmentType($adjustment_type) {
        $this->adjustment_type = $adjustment_type;
    }
    
    function setAdjustedBy($adjusted_by) {
        $this->adjusted_by = $adjusted_by;
    }
    
    function setAdjustmentDate($adjustment_date) {
        $this->adjustment_date = $adjustment_date;
    }
    
    function setGrnId($grn_id) {
        $this->grn_id = $grn_id;
    }
    
    function setPaymentTerms($payment_terms) {
        $this->payment_terms = $payment_terms;
    }
    
    function setTotalAmount($total_amount) {
        $this->total_amount = $total_amount;
    }
    
   
    
    // ======================== CATEGORY METHODS ========================
    
    function insertCategory() {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $category_type = isset($this->category_type) ? $this->category_type : 'CONSUMABLE';
        $sql = "INSERT INTO `store_categories` (`category_name`, `category_type`, `status`) VALUES ('$this->name', '$category_type', '$this->status')";
      
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    function getCategories() {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $sql = "SELECT * FROM `store_categories` ORDER BY `category_name`";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    function getCategory($id) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $sql = "SELECT * FROM `store_categories` WHERE id=$id";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    function updateCategory($id) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $category_type = isset($this->category_type) ? $this->category_type : 'CONSUMABLE';
        $sql = "UPDATE `store_categories` SET `category_name`='$this->name', `category_type`='$category_type', `status`='$this->status' WHERE id=$id";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    function deleteCategory($id) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $sql = "DELETE FROM `store_categories` WHERE id=$id";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    // ======================== UNIT METHODS ========================
    
    function insertUnit() {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $sql = "INSERT INTO `store_units` (`unit_name`, `status`) VALUES ('$this->name', '$this->status')";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    function getUnits() {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $sql = "SELECT * FROM `store_units` ORDER BY `unit_name`";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    function getUnit($id) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $sql = "SELECT * FROM `store_units` WHERE id=$id";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    function updateUnit($id) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $sql = "UPDATE `store_units` SET `unit_name`='$this->name', `status`='$this->status' WHERE id=$id";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    function deleteUnit($id) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $sql = "DELETE FROM `store_units` WHERE id=$id";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    // ======================== SUPPLIER METHODS ========================
    
    function insertSupplier() {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $sql = "INSERT INTO `store_suppliers` (`supplier_name`, `contact_person`, `phone`, `email`, `gst_number`, `address`, `status`) 
                VALUES ('$this->name', '$this->contact_person', '$this->phone', '$this->email', '$this->gst_number', '$this->address', '$this->status')";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    function getSuppliers() {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $sql = "SELECT * FROM `store_suppliers` ORDER BY `supplier_name`";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    function getActiveSuppliers() {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $sql = "SELECT * FROM `store_suppliers` WHERE status=1 ORDER BY `supplier_name`";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    function getSupplier($id) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $sql = "SELECT * FROM `store_suppliers` WHERE id=$id";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    function updateSupplier($id) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $sql = "UPDATE `store_suppliers` SET `supplier_name`='$this->name', `contact_person`='$this->contact_person', 
                `phone`='$this->phone', `email`='$this->email', `gst_number`='$this->gst_number', 
                `address`='$this->address', `status`='$this->status' WHERE id=$id";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    function deleteSupplier($id) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $sql = "DELETE FROM `store_suppliers` WHERE id=$id";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    // ======================== ITEM METHODS ========================
    
    function insertItem() {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $sql = "INSERT INTO `store_items` (`item_code`, `item_name`, `category_id`, `unit_id`, `item_type`, `min_stock`, `reorder_level`, `is_expiry_required`, `is_serial_required`, `status`) 
                VALUES ('$this->item_code', '$this->item_name', '$this->category_id', '$this->unit_id', '$this->item_type', '$this->min_stock', '$this->reorder_level', '$this->is_expiry_required', '$this->is_serial_required', '$this->status')";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    function getItems() {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $sql = "SELECT si.*, sc.category_name, su.unit_name 
                FROM `store_items` si 
                LEFT JOIN `store_categories` sc ON si.category_id = sc.id 
                LEFT JOIN `store_units` su ON si.unit_id = su.id 
                ORDER BY si.item_name";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    function getActiveItems() {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $sql = "SELECT si.*, sc.category_name, su.unit_name 
                FROM `store_items` si 
                LEFT JOIN `store_categories` sc ON si.category_id = sc.id 
                LEFT JOIN `store_units` su ON si.unit_id = su.id 
                WHERE si.status=1 
                ORDER BY si.item_name";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    function getItem($id) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $sql = "SELECT si.*, sc.category_name, su.unit_name 
                FROM `store_items` si 
                LEFT JOIN `store_categories` sc ON si.category_id = sc.id 
                LEFT JOIN `store_units` su ON si.unit_id = su.id 
                WHERE si.id=$id";
                // echo $sql;
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    function updateItem($id) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $sql = "UPDATE `store_items` SET `item_code`='$this->item_code', `item_name`='$this->item_name', 
                `category_id`='$this->category_id', `unit_id`='$this->unit_id', `item_type`='$this->item_type', 
                `min_stock`='$this->min_stock', `reorder_level`='$this->reorder_level', 
                `is_expiry_required`='$this->is_expiry_required', `is_serial_required`='$this->is_serial_required', 
                `status`='$this->status' WHERE id=$id";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    function deleteItem($id) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $sql = "DELETE FROM `store_items` WHERE id=$id";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    // ======================== PURCHASE ORDER METHODS ========================
    
    function insertPurchaseOrder() {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $created_by = $_SESSION['loginid'];
        $store_id = $this->getSessionStoreId();
        $sql = "INSERT INTO `store_purchase_orders` (`po_number`, `supplier_id`, `po_date`, `expected_date`, `payment_terms`, `total_amount`, `remarks`, `status`, `created_by`, `store_id`) 
            VALUES ('$this->po_number', '$this->supplier_id', '$this->po_date', '$this->expected_date', '$this->payment_terms', '$this->total_amount', '$this->remarks', 'PENDING', '$created_by', '$store_id')";
        $res = mysqli_query($con, $sql);
        // echo $sql;exit;
        if($res) {
            return mysqli_insert_id($con);
        }
        return false;
    }
    
    function insertPurchaseOrderItem($po_id, $item_id, $quantity, $rate, $amount, $gst_percent = 0) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        // Calculate amounts
        $amount = $quantity * $rate;
        $gst_amt = ($amount * $gst_percent) / 100;
        $net_amt = $amount + $gst_amt;
        
        $sql = "INSERT INTO `store_purchase_order_items` (`po_id`, `item_id`, `quantity`, `rate`, `gst_percent`, `gst_amount`, `net_amount`, `amount`) 
                VALUES ('$po_id', '$item_id', '$quantity', '$rate', '$gst_percent', '$gst_amt', '$net_amt', '$amount')";
                // echo $sql;exit; 
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    function updatePurchaseOrderItem($po_item_id, $item_id, $quantity, $rate, $gst_percent = 0) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        // Calculate amounts
        $amount = $quantity * $rate;
        $gst_amt = ($amount * $gst_percent) / 100;
        $net_amt = $amount + $gst_amt;
        
        $sql = "UPDATE `store_purchase_order_items` 
                SET `item_id`='$item_id', 
                    `quantity`='$quantity', 
                    `rate`='$rate', 
                    `gst_percent`='$gst_percent', 
                    `gst_amount`='$gst_amt', 
                    `net_amount`='$net_amt', 
                    `amount`='$amount'
                WHERE `id`='$po_item_id'";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    function getPurchaseOrders() {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $store_id = $this->getSessionStoreId();
        $where= "";
        if($_SESSION['role'] != 'Admin') {
            $where = "WHERE spo.store_id = $store_id";
        } 

        $sql = "SELECT spo.*, ss.supplier_name 
                FROM `store_purchase_orders` spo 
                LEFT JOIN `store_suppliers` ss ON spo.supplier_id = ss.id 
             $where
                ORDER BY spo.created_at DESC";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    function getPurchaseOrder($id) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $store_id = $this->getSessionStoreId();
        $where= "";
        if($_SESSION['role'] != 'Admin') {
            $where = "AND spo.store_id = $store_id";
        } 
        $sql = "SELECT spo.*, ss.supplier_name, ss.phone, ss.email, ss.address 
                FROM `store_purchase_orders` spo 
                LEFT JOIN `store_suppliers` ss ON spo.supplier_id = ss.id 
            WHERE spo.id=$id $where";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    function getPurchaseOrderItems($po_id) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $sql = "SELECT spoi.*, si.item_code, si.item_name, su.unit_name 
                FROM `store_purchase_order_items` spoi 
                LEFT JOIN `store_items` si ON spoi.item_id = si.id 
                LEFT JOIN `store_units` su ON si.unit_id = su.id 
                WHERE spoi.po_id=$po_id";
        $res = mysqli_query($con, $sql);
        return $res;
    }

    
    function updatePurchaseOrderStatus($po_id, $status) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $store_id = $this->getSessionStoreId();
        $sql = "UPDATE `store_purchase_orders` SET `status`='$status' WHERE id=$po_id AND store_id = $store_id";
        $res = mysqli_query($con, $sql);
        return $res;
    }

    function approvePurchaseOrder($po_id) {
        if(!isset($_SESSION['role']) || $_SESSION['role'] != 'Admin') {
            return false;
        }

        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $store_id = $this->getSessionStoreId();
        $approved_by = isset($_SESSION['name']) ? mysqli_real_escape_string($con, $_SESSION['name']) : '';
        if($_SESSION['role'] != 'Admin') {
            $where = "AND store_id = $store_id";
        } else {
            $where = "";
        }
        $sql = "UPDATE `store_purchase_orders`
                SET `status`='APPROVED',
                    `approved_by`='$approved_by',
                    `approved_at`=NOW()
                WHERE id=$po_id AND `status`='PENDING' $where";
        $res = mysqli_query($con, $sql);
        return $res && mysqli_affected_rows($con) > 0;
    }
    
    function updatePurchaseOrder() {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $updated_by = $_SESSION['loginid'];
        $store_id = $this->getSessionStoreId();
        $sql = "UPDATE `store_purchase_orders` SET 
                `supplier_id`='$this->supplier_id', 
                `po_date`='$this->po_date', 
                `expected_date`='$this->expected_date', 
                `payment_terms`='$this->payment_terms', 
                `total_amount`='$this->total_amount', 
                `remarks`='$this->remarks',
                `updated_by`='$updated_by',
                `updated_at`=NOW()
                WHERE id='$this->po_id' AND `store_id`='$store_id'";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    function deletePurchaseOrderItems($po_id) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $sql = "DELETE FROM `store_purchase_order_items` WHERE po_id=$po_id";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    function deletePurchaseOrderItem($po_item_id) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $sql = "DELETE FROM `store_purchase_order_items` WHERE id=$po_item_id";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    function getPendingPurchaseOrders() {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $store_id = $this->getSessionStoreId();
        $where= "";
        if($_SESSION['role'] != 'Admin') {
            $where = "AND spo.store_id = $store_id";
        } 
        $sql = "SELECT spo.*, ss.supplier_name 
                FROM `store_purchase_orders` spo 
                LEFT JOIN `store_suppliers` ss ON spo.supplier_id = ss.id 
            WHERE spo.status IN ('APPROVED', 'PARTIALLY_RECEIVED') $where
                ORDER BY spo.po_date DESC";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    // ======================== GRN METHODS ========================
    
    function insertGRN() {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $created_by = $_SESSION['loginid'];
        $store_id = $this->getSessionStoreId();
        $total_amount = floatval($this->total_amount);
        $sql = "INSERT INTO `store_grn` (`grn_number`, `po_id`, `supplier_id`, `received_date`, `invoice_number`, `invoice_date`, `total_amount`, `remarks`, `created_by`, `store_id`) 
            VALUES ('$this->grn_number', '$this->po_id', '$this->supplier_id', '$this->received_date', '$this->invoice_number', '$this->invoice_date', '$total_amount', '$this->remarks', '$created_by', '$store_id')";
        $res = mysqli_query($con, $sql);
        // echo $sql;exit;
        if($res) {
            return mysqli_insert_id($con);
        }
        return false;
    }

    function updateGRNHeader($grn_id) {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $store_id = $this->getSessionStoreId();

        $grn_id = intval($grn_id);
        $po_id_value = $this->po_id ? intval($this->po_id) : "NULL";
        $invoice_number = $this->invoice_number ? "'".$this->invoice_number."'" : "NULL";
        $invoice_date = $this->invoice_date ? "'".$this->invoice_date."'" : "NULL";
        $remarks = $this->remarks ? "'".$this->remarks."'" : "NULL";

        $total_amount = floatval($this->total_amount);

        $sql = "UPDATE `store_grn`
                SET `po_id` = $po_id_value,
                    `supplier_id` = '$this->supplier_id',
                    `received_date` = '$this->received_date',
                    `invoice_number` = $invoice_number,
                    `invoice_date` = $invoice_date,
                    `total_amount` = '$total_amount',
                    `remarks` = $remarks
                WHERE `id` = $grn_id AND `store_id` = $store_id";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    function insertGRNItem($grn_id, $item_id, $batch_no, $expiry_date, $serial_no, $quantity, $rate, $amount, $gst_percent = 0, $gst_amount = 0, $net_amount = 0) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();

        $gst_percent = floatval($gst_percent);
        $gst_amount = $gst_amount ? floatval($gst_amount) : (($amount * $gst_percent) / 100);
        $net_amount = $net_amount ? floatval($net_amount) : ($amount + $gst_amount);
        
        // Insert GRN item
        $sql = "INSERT INTO `store_grn_items` (`grn_id`, `item_id`, `batch_no`, `expiry_date`, `serial_no`, `quantity`, `rate`, `amount`, `gst_percent`, `gst_amount`, `net_amount`) 
                VALUES ('$grn_id', '$item_id', ".($batch_no ? "'$batch_no'" : "NULL").", ".($expiry_date ? "'$expiry_date'" : "NULL").", ".($serial_no ? "'$serial_no'" : "NULL").", '$quantity', '$rate', '$amount', '$gst_percent', '$gst_amount', '$net_amount')";
        // echo $sql;exit;
        $res = mysqli_query($con, $sql);
        
        if($res) {
            // Update stock
            $this->updateStockOnGRN($item_id, $batch_no, $expiry_date, $quantity, $grn_id);
            return true;
        }
        return false;
    }
    
    function updateStockOnGRN($item_id, $batch_no, $expiry_date, $quantity, $grn_id) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $store_id = $this->getSessionStoreId();
        $batch_condition = $batch_no ? "='$batch_no'" : "IS NULL";
        
        // Check if stock exists for this item and batch
        $check_sql = "SELECT * FROM `store_stock` WHERE item_id='$item_id' AND store_id = $store_id AND batch_no $batch_condition";
        $check_res = mysqli_query($con, $check_sql);
        
        if(mysqli_num_rows($check_res) > 0) {
            // Update existing stock
            $sql = "UPDATE `store_stock` SET available_qty = available_qty + $quantity WHERE item_id='$item_id' AND store_id = $store_id AND batch_no $batch_condition";
        } else {
            // Insert new stock
            $sql = "INSERT INTO `store_stock` (`item_id`, `batch_no`, `expiry_date`, `available_qty`, `store_id`) 
                    VALUES ('$item_id', ".($batch_no ? "'$batch_no'" : "NULL").", ".($expiry_date ? "'$expiry_date'" : "NULL").", '$quantity', '$store_id')";
        }
        mysqli_query($con, $sql);
        
        // Get current balance
        $balance_sql = "SELECT available_qty FROM `store_stock` WHERE item_id='$item_id' AND store_id = $store_id AND batch_no $batch_condition";
        $balance_res = mysqli_query($con, $balance_sql);
        $balance_row = mysqli_fetch_assoc($balance_res);
        $balance = $balance_row['available_qty'];
        
        // Get GRN number for reference
        $grn_sql = "SELECT grn_number FROM `store_grn` WHERE id=$grn_id AND store_id = $store_id";
        $grn_res = mysqli_query($con, $grn_sql);
        $grn_row = mysqli_fetch_assoc($grn_res);
        $grn_number = $grn_row['grn_number'];
        
        // Insert into ledger
        $ledger_sql = "INSERT INTO `store_stock_ledger` (`item_id`, `batch_no`, `transaction_type`, `reference_id`, `reference_number`, `qty_in`, `qty_out`, `balance`) 
                       VALUES ('$item_id', ".($batch_no ? "'$batch_no'" : "NULL").", 'GRN', '$grn_id', '$grn_number', '$quantity', 0, '$balance')";
        mysqli_query($con, $ledger_sql);
        
        return true;
    }
    
    function getGRNs() {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $store_id = $this->getSessionStoreId();
        $where= "";
        if($_SESSION['role'] != 'Admin') {
            $where = "WHERE sg.store_id = $store_id";
        } 
        $sql = "SELECT sg.*, ss.supplier_name, spo.po_number 
                FROM `store_grn` sg 
                LEFT JOIN `store_suppliers` ss ON sg.supplier_id = ss.id 
                LEFT JOIN `store_purchase_orders` spo ON sg.po_id = spo.id 
            $where
                ORDER BY sg.created_at DESC";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    function getGRN($id) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $store_id = $this->getSessionStoreId();
        $where= "";
        if($_SESSION['role'] != 'Admin') {
            $where = "AND  sg.store_id = $store_id";
        } 
        $sql = "SELECT sg.*, ss.supplier_name, spo.po_number 
                FROM `store_grn` sg 
                LEFT JOIN `store_suppliers` ss ON sg.supplier_id = ss.id 
                LEFT JOIN `store_purchase_orders` spo ON sg.po_id = spo.id 
            WHERE sg.id=$id $where";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    function getGRNItems($grn_id) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $store_id = $this->getSessionStoreId();
        $where= "";
        if($_SESSION['role'] != 'Admin') {
            $where = "AND sg.store_id = $store_id";
        } 
        $sql = "SELECT sgi.*, si.item_code, si.item_name, su.unit_name 
                FROM `store_grn_items` sgi 
            INNER JOIN `store_grn` sg ON sg.id = sgi.grn_id
                LEFT JOIN `store_items` si ON sgi.item_id = si.id 
                LEFT JOIN `store_units` su ON si.unit_id = su.id 
            WHERE sgi.grn_id=$grn_id $where";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    // ======================== ISSUE METHODS ========================
    
    function insertIssue() {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $issued_by = $_SESSION['loginid'];
        $store_id = $this->getSessionStoreId();
        $sql = "INSERT INTO `store_issue_header` (`issue_number`, `issue_date`, `department`, `requested_by`, `issued_by`, `remarks`, `status`, `store_id`) 
            VALUES ('$this->issue_number', '$this->issue_date', '$this->department', '$this->requested_by', '$issued_by', '$this->remarks', 'ISSUED', '$store_id')";
        $res = mysqli_query($con, $sql);
        if($res) {
            return mysqli_insert_id($con);
        }
        return false;
    }
    
    function insertIssueItem($issue_id, $item_id, $batch_no, $serial_no, $quantity) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        $batch_no=$batch_no=='null' ? null : $batch_no;
        // Insert issue item
        $sql = "INSERT INTO `store_issue_items` (`issue_id`, `item_id`, `batch_no`, `serial_no`, `quantity`) 
                VALUES ('$issue_id', '$item_id', ".($batch_no !== null ? "'$batch_no'" : "NULL").", ".($serial_no ? "'$serial_no'" : "NULL").", '$quantity')";
        $res = mysqli_query($con, $sql);
        // echo $sql."<br>";
        if($res) {
            // Update stock
            $this->updateStockOnIssue($item_id, $batch_no, $quantity, $issue_id);
            return true;
        }
        return false;
    }
    
    function updateStockOnIssue($item_id, $batch_no, $quantity, $issue_id) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $store_id = $this->getSessionStoreId();
        
        // Build WHERE clause for batch
        $batch_condition = ($batch_no !== null) ? "batch_no='$batch_no'" : "(batch_no IS NULL OR batch_no='' )";
        
        // Reduce stock
        $sql = "UPDATE `store_stock` SET available_qty = available_qty - $quantity 
            WHERE item_id='$item_id' AND store_id = $store_id AND $batch_condition";
        mysqli_query($con, $sql);
        
        // Get current balance
        $balance_sql = "SELECT available_qty FROM `store_stock` 
                       WHERE item_id='$item_id' AND store_id = $store_id AND $batch_condition";
        $balance_res = mysqli_query($con, $balance_sql);
        $balance_row = mysqli_fetch_assoc($balance_res);
        $balance = $balance_row ? $balance_row['available_qty'] : 0;
        
        // Get issue number for reference
        $issue_sql = "SELECT issue_number FROM `store_issue_header` WHERE id=$issue_id AND store_id = $store_id";
        $issue_res = mysqli_query($con, $issue_sql);
        $issue_row = mysqli_fetch_assoc($issue_res);
        $issue_number = $issue_row['issue_number'];
        
        // Insert into ledger
        $batch_value = ($batch_no !== null) ? "'$batch_no'" : "NULL";
        $ledger_sql = "INSERT INTO `store_stock_ledger` (`item_id`, `batch_no`, `transaction_type`, `reference_id`, `reference_number`, `qty_in`, `qty_out`, `balance`) 
                       VALUES ('$item_id', $batch_value, 'ISSUE', '$issue_id', '$issue_number', 0, '$quantity', '$balance')";
        mysqli_query($con, $ledger_sql);
        
        return true;
    }
    
    function getIssues() {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $store_id = $this->getSessionStoreId();
        $where= "";
        if($_SESSION['role'] != 'Admin') {
            $where = "WHERE store_id = $store_id";
        } 
        $sql = "SELECT * FROM `store_issue_header` $where ORDER BY created_at DESC";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    function getIssue($id) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $store_id = $this->getSessionStoreId();
        $where= "";
        if($_SESSION['role'] != 'Admin') {
            $where = "AND store_id = $store_id";
        } 
        $sql = "SELECT * FROM `store_issue_header` WHERE id=$id $where";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    function getIssueItems($issue_id) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $store_id = $this->getSessionStoreId();
        $where= "";
        if($_SESSION['role'] != 'Admin') {
            $where = "AND sih.store_id = $store_id";
        } 
        $sql = "SELECT sii.*, si.item_code, si.item_name, su.unit_name 
                FROM `store_issue_items` sii 
            INNER JOIN `store_issue_header` sih ON sih.id = sii.issue_id
                LEFT JOIN `store_items` si ON sii.item_id = si.id 
                LEFT JOIN `store_units` su ON si.unit_id = su.id 
            WHERE sii.issue_id=$issue_id $where";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    // ======================== STOCK & LEDGER METHODS ========================
    
    function getCurrentStock() {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $store_id = $this->getSessionStoreId();
        $where= "";
        if($_SESSION['role'] != 'Admin') {
            $where = "AND ss.store_id = $store_id";
        } 
        $sql = "SELECT ss.*, si.item_code, si.item_name, su.unit_name, sc.category_name 
                FROM `store_stock` ss 
                LEFT JOIN `store_items` si ON ss.item_id = si.id 
                LEFT JOIN `store_units` su ON si.unit_id = su.id 
                LEFT JOIN `store_categories` sc ON si.category_id = sc.id 
            WHERE ss.available_qty > 0 $where
                ORDER BY si.item_name";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    function getStockByItem($item_id) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $store_id = $this->getSessionStoreId();
        $where= "";
        if($_SESSION['role'] != 'Admin') {
            $where = "AND ss.store_id = $store_id";
        } 
        mysqli_real_escape_string($con, $item_id);
        $sql = "SELECT ss.*, si.item_code, si.item_name, su.unit_name 
                FROM `store_stock` ss 
                LEFT JOIN `store_items` si ON ss.item_id = si.id 
                LEFT JOIN `store_units` su ON si.unit_id = su.id 
            WHERE ss.item_id=$item_id AND ss.available_qty > 0 $where
                ORDER BY ss.expiry_date ASC";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    function getStockLedger($item_id = null, $from_date = null, $to_date = null) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $sql = "SELECT sl.*, si.item_code, si.item_name, su.unit_name 
                FROM `store_stock_ledger` sl 
                LEFT JOIN `store_items` si ON sl.item_id = si.id 
                LEFT JOIN `store_units` su ON si.unit_id = su.id 
                WHERE 1=1 ";
        
        if($item_id) {
            $sql .= " AND sl.item_id = $item_id";
        }
        
        if($from_date && $to_date) {
            $sql .= " AND DATE(sl.transaction_date) BETWEEN '$from_date' AND '$to_date'";
        }
        
        $sql .= " ORDER BY sl.transaction_date";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    function getLowStockItems() {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $store_id = $this->getSessionStoreId();
        $where= "";
        if($_SESSION['role'] != 'Admin') {
            $where = "AND ss.store_id = $store_id";
        } 
        $sql = "SELECT si.item_code, si.item_name, si.reorder_level, COALESCE(SUM(ss.available_qty), 0) as current_stock, su.unit_name, sc.category_name 
                FROM `store_items` si 
            LEFT JOIN `store_stock` ss ON si.id = ss.item_id $where
                LEFT JOIN `store_units` su ON si.unit_id = su.id 
                LEFT JOIN `store_categories` sc ON si.category_id = sc.id 
                WHERE si.status = 1 
                GROUP BY si.id 
                HAVING current_stock < si.reorder_level 
                ORDER BY current_stock ASC";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    function getExpiringItems($days = 90) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $store_id = $this->getSessionStoreId();
        $where= "";
        if($_SESSION['role'] != 'Admin') {
            $where = "AND ss.store_id = $store_id";
        } 
        $sql = "SELECT ss.*, si.item_code, si.item_name, su.unit_name, sc.category_name, 
                DATEDIFF(ss.expiry_date, CURDATE()) as days_to_expire 
                FROM `store_stock` ss 
                LEFT JOIN `store_items` si ON ss.item_id = si.id 
                LEFT JOIN `store_units` su ON si.unit_id = su.id 
                LEFT JOIN `store_categories` sc ON si.category_id = sc.id 
                WHERE ss.expiry_date IS NOT NULL 
            $where
                AND ss.available_qty > 0 
                AND DATEDIFF(ss.expiry_date, CURDATE()) <= $days 
                AND DATEDIFF(ss.expiry_date, CURDATE()) >= 0 
                ORDER BY ss.expiry_date ASC";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    // ======================== STOCK ADJUSTMENT METHODS ========================
    
    function insertStockAdjustment() {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $adjusted_by = $_SESSION['loginid'];
        
        // Insert adjustment record
        $sql = "INSERT INTO `store_stock_adjustments` (`adjustment_number`, `item_id`, `batch_no`, `adjustment_qty`, `adjustment_type`, `reason`, `adjusted_by`, `adjustment_date`) 
                VALUES ('$this->adjustment_number', '$this->item_id', ".($this->batch_no ? "'$this->batch_no'" : "NULL").", '$this->adjustment_qty', '$this->adjustment_type', '$this->reason', '$adjusted_by', '$this->adjustment_date')";
        $res = mysqli_query($con, $sql);
        
        if($res) {
            $adjustment_id = mysqli_insert_id($con);
            
            // Update stock based on adjustment type
            if($this->adjustment_type == 'ADD') {
                $stock_sql = "UPDATE `store_stock` SET available_qty = available_qty + {$this->adjustment_qty} 
                             WHERE item_id='{$this->item_id}' AND batch_no=".($this->batch_no ? "'{$this->batch_no}'" : "NULL");
            } else {
                $stock_sql = "UPDATE `store_stock` SET available_qty = available_qty - {$this->adjustment_qty} 
                             WHERE item_id='{$this->item_id}' AND batch_no=".($this->batch_no ? "'{$this->batch_no}'" : "NULL");
            }
            mysqli_query($con, $stock_sql);
            
            // Get current balance
            $balance_sql = "SELECT available_qty FROM `store_stock` WHERE item_id='{$this->item_id}' AND batch_no=".($this->batch_no ? "'{$this->batch_no}'" : "NULL");
            $balance_res = mysqli_query($con, $balance_sql);
            $balance_row = mysqli_fetch_assoc($balance_res);
            $balance = $balance_row['available_qty'];
            
            // Insert into ledger
            if($this->adjustment_type == 'ADD') {
                $ledger_sql = "INSERT INTO `store_stock_ledger` (`item_id`, `batch_no`, `transaction_type`, `reference_id`, `reference_number`, `qty_in`, `qty_out`, `balance`, `remarks`) 
                              VALUES ('{$this->item_id}', ".($this->batch_no ? "'{$this->batch_no}'" : "NULL").", 'ADJUSTMENT', '$adjustment_id', '{$this->adjustment_number}', '{$this->adjustment_qty}', 0, '$balance', '{$this->reason}')";
            } else {
                $ledger_sql = "INSERT INTO `store_stock_ledger` (`item_id`, `batch_no`, `transaction_type`, `reference_id`, `reference_number`, `qty_in`, `qty_out`, `balance`, `remarks`) 
                              VALUES ('{$this->item_id}', ".($this->batch_no ? "'{$this->batch_no}'" : "NULL").", 'ADJUSTMENT', '$adjustment_id', '{$this->adjustment_number}', 0, '{$this->adjustment_qty}', '$balance', '{$this->reason}')";
            }
            mysqli_query($con, $ledger_sql);
            
            return true;
        }
        return false;
    }
    
    function getStockAdjustments() {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $sql = "SELECT sa.*, si.item_code, si.item_name, su.unit_name 
                FROM `store_stock_adjustments` sa 
                LEFT JOIN `store_items` si ON sa.item_id = si.id 
                LEFT JOIN `store_units` su ON si.unit_id = su.id 
                ORDER BY sa.created_at DESC";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    // ======================== UTILITY METHODS ========================
    
    function generatePONumber() {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $store_id = $this->getSessionStoreId();
        $where= "";
        if($_SESSION['role'] != 'Admin') {
            $where = "WHERE store_id = $store_id";
        } 
        $sql = "SELECT po_number FROM `store_purchase_orders` $where ORDER BY id DESC LIMIT 1";
        $res = mysqli_query($con, $sql);
        
        if(mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            $last_number = intval(substr($row['po_number'], 4));
            $new_number = $last_number + 1;
        } else {
            $new_number = 1;
        }
        
        return 'PO' . date('y') . str_pad($new_number, 5, '0', STR_PAD_LEFT);
    }
    
    function generateGRNNumber() {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $store_id = $this->getSessionStoreId();
        $where= "";
        if($_SESSION['role'] != 'Admin') {
            $where = "WHERE store_id = $store_id";
        } 
        $sql = "SELECT grn_number FROM `store_grn` $where ORDER BY id DESC LIMIT 1";
        $res = mysqli_query($con, $sql);
        
        if(mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            $last_number = intval(substr($row['grn_number'], 5));
            $new_number = $last_number + 1;
        } else {
            $new_number = 1;
        }
        
        return 'GRN' . date('y') . str_pad($new_number, 5, '0', STR_PAD_LEFT);
    }
    
    function generateIssueNumber() {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $store_id = $this->getSessionStoreId();
        $where= "";
        if($_SESSION['role'] != 'Admin') {
            $where = "WHERE store_id = $store_id";
        } 
        $sql = "SELECT issue_number FROM `store_issue_header` $where ORDER BY id DESC LIMIT 1";
        $res = mysqli_query($con, $sql);
        
        if(mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            $last_number = intval(substr($row['issue_number'], 4));
            $new_number = $last_number + 1;
        } else {
            $new_number = 1;
        }
        
        return 'IS' . date('y') . str_pad($new_number, 5, '0', STR_PAD_LEFT);
    }
    
    function generateAdjustmentNumber() {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $store_id = $this->getSessionStoreId();
        $where= "";
        if($_SESSION['role'] != 'Admin') {
            $where = "WHERE store_id = $store_id";
        } 
        $sql = "SELECT adjustment_number FROM `store_stock_adjustments` $where ORDER BY id DESC LIMIT 1";
        $res = mysqli_query($con, $sql);
        
        if(mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            $last_number = intval(substr($row['adjustment_number'], 3));
            $new_number = $last_number + 1;
        } else {
            $new_number = 1;
        }
        
        return 'AD' . date('y') . str_pad($new_number, 5, '0', STR_PAD_LEFT);
    }
    
    // ======================== PURCHASE INVOICE METHODS ========================
    
    function generateInvoiceNumber() {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $store_id = $this->getSessionStoreId();
        $where= "";
        if($_SESSION['role'] != 'Admin') {
            $where = "WHERE store_id = $store_id";
        } 
        $sql = "SELECT invoice_number FROM `store_purchase_invoices` $where ORDER BY id DESC LIMIT 1";
        $res = mysqli_query($con, $sql);
        
        if(mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            $last_number = intval(substr($row['invoice_number'], 7)); // INV/26/XXXXX
            $new_number = $last_number + 1;
        } else {
            $new_number = 1;
        }
        
        return 'INV/' . date('y') . '/' . str_pad($new_number, 5, '0', STR_PAD_LEFT);
    }
    
    function insertPurchaseInvoice($total_amount, $tax_amount, $discount_amount, $net_amount, $payment_terms, $due_date) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $created_by = $_SESSION['loginid'];
        $store_id = $this->getSessionStoreId();
        
        $sql = "INSERT INTO `store_purchase_invoices` 
            (`invoice_number`, `invoice_date`, `supplier_id`, `grn_id`, `total_amount`, `tax_amount`, `discount_amount`, `net_amount`, `balance_amount`, `payment_terms`, `due_date`, `remarks`, `created_by`, `store_id`) 
            VALUES ('$this->invoice_number', '$this->invoice_date', '$this->supplier_id', ".($this->grn_id ? "'$this->grn_id'" : "NULL").", '$total_amount', '$tax_amount', '$discount_amount', '$net_amount', '$net_amount', '$payment_terms', '$due_date', '$this->remarks', '$created_by', '$store_id')";
        $res = mysqli_query($con, $sql);
        if($res) {
            return mysqli_insert_id($con);
        }
        return false;
    }
    
    function insertPurchaseInvoiceItem($invoice_id, $item_id, $batch_no, $quantity, $rate, $amount, $tax_percent, $tax_amount, $discount_percent, $discount_amount, $net_amount) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        $sql = "INSERT INTO `store_purchase_invoice_items` 
                (`invoice_id`, `item_id`, `batch_no`, `quantity`, `rate`, `amount`, `tax_percent`, `tax_amount`, `discount_percent`, `discount_amount`, `net_amount`) 
                VALUES ('$invoice_id', '$item_id', ".($batch_no ? "'$batch_no'" : "NULL").", '$quantity', '$rate', '$amount', '$tax_percent', '$tax_amount', '$discount_percent', '$discount_amount', '$net_amount')";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    function getUnpaidInvoicesBySupplier($supplier_id) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $store_id = $this->getSessionStoreId();
        $sql = "SELECT * FROM `store_purchase_invoices` 
            WHERE supplier_id=$supplier_id AND payment_status != 'PAID' AND store_id = $store_id
                ORDER BY due_date ASC";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    function updateInvoicePayment($invoice_id, $paid_amount) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $store_id = $this->getSessionStoreId();
        
        // Get current invoice details
        $invoice_sql = "SELECT net_amount, paid_amount FROM `store_purchase_invoices` WHERE id=$invoice_id AND store_id = $store_id";
        $invoice_res = mysqli_query($con, $invoice_sql);
        $invoice = mysqli_fetch_assoc($invoice_res);
        
        $new_paid_amount = $invoice['paid_amount'] + $paid_amount;
        $balance_amount = $invoice['net_amount'] - $new_paid_amount;
        
        // Determine payment status
        if($balance_amount <= 0) {
            $payment_status = 'PAID';
            $balance_amount = 0;
        } elseif($new_paid_amount > 0) {
            $payment_status = 'PARTIALLY_PAID';
        } else {
            $payment_status = 'UNPAID';
        }
        
        $sql = "UPDATE `store_purchase_invoices` 
                SET paid_amount = $new_paid_amount, 
                    balance_amount = $balance_amount, 
                    payment_status = '$payment_status' 
                WHERE id = $invoice_id AND store_id = $store_id";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    // ======================== SUPPLIER PAYMENT METHODS ========================
    
    function generatePaymentNumber() {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $store_id = $this->getSessionStoreId();
        $where= "";
        if($_SESSION['role'] != 'Admin') {
            $where = "WHERE store_id = $store_id";
        } 
        $sql = "SELECT payment_number FROM `store_supplier_payments` $where  ORDER BY id DESC LIMIT 1";
        $res = mysqli_query($con, $sql);
        
        if(mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            $last_number = intval(substr($row['payment_number'], 7)); // PAY/26/XXXXX
            $new_number = $last_number + 1;
        } else {
            $new_number = 1;
        }
        
        return 'PAY/' . date('y') . '/' . str_pad($new_number, 5, '0', STR_PAD_LEFT);
    }
    
    function insertSupplierPayment($payment_number, $payment_date, $supplier_id, $payment_mode, $payment_amount, $reference_number, $bank_name, $remarks) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $created_by = $_SESSION['loginid'];
        $store_id = $this->getSessionStoreId();
        
        $sql = "INSERT INTO `store_supplier_payments` 
            (`payment_number`, `payment_date`, `supplier_id`, `payment_mode`, `payment_amount`, `reference_number`, `bank_name`, `remarks`, `created_by`, `store_id`) 
            VALUES ('$payment_number', '$payment_date', '$supplier_id', '$payment_mode', '$payment_amount', '$reference_number', '$bank_name', '$remarks', '$created_by', '$store_id')";
        $res = mysqli_query($con, $sql);
        // echo $sql; exit;
        if($res) {
            return mysqli_insert_id($con);
        }
        return false;
    }
    
    function insertPaymentAllocation($payment_id, $invoice_id, $allocated_amount) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        $sql = "INSERT INTO `store_payment_allocations` 
                (`payment_id`, `invoice_id`, `allocated_amount`) 
                VALUES ('$payment_id', '$invoice_id', '$allocated_amount')";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    function getSupplierPayments() {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $store_id = $this->getSessionStoreId();
        $where= "";
        if($_SESSION['role'] != 'Admin') {
            $where = "WHERE sp.store_id = $store_id";
        } 
        $sql = "SELECT sp.*, ss.supplier_name 
                FROM `store_supplier_payments` sp 
                LEFT JOIN `store_suppliers` ss ON sp.supplier_id = ss.id 
                $where
                ORDER BY sp.created_at DESC";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    function getSupplierPayment($id) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $store_id = $this->getSessionStoreId();
        $where= "";
        if($_SESSION['role'] != 'Admin') {
            $where = "AND sp.store_id = $store_id";
        } 
        $sql = "SELECT sp.*, ss.supplier_name, ss.phone 
                FROM `store_supplier_payments` sp 
                LEFT JOIN `store_suppliers` ss ON sp.supplier_id = ss.id 
            WHERE sp.id=$id $where";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    function getPurchaseInvoices() {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $store_id = $this->getSessionStoreId();
        $where= "";
        if($_SESSION['role'] != 'Admin') {
            $where = "WHERE pi.store_id = $store_id";
        } 
        $sql = "SELECT pi.*, ss.supplier_name 
                FROM `store_purchase_invoices` pi 
                LEFT JOIN `store_suppliers` ss ON pi.supplier_id = ss.id 
            $where
                ORDER BY pi.invoice_date DESC, pi.created_at DESC";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    function getPurchaseInvoice($id) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $store_id = $this->getSessionStoreId();
        $where= "";
        if($_SESSION['role'] != 'Admin') {
            $where = "AND pi.store_id = $store_id";
        } 

        $sql = "SELECT pi.*, ss.supplier_name, ss.phone, ss.email, ss.address 
                FROM `store_purchase_invoices` pi 
                LEFT JOIN `store_suppliers` ss ON pi.supplier_id = ss.id 
            WHERE pi.id=$id $where";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    function getPurchaseInvoiceItems($invoice_id) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $sql = "SELECT pii.*, si.item_code, si.item_name, su.unit_name 
                FROM `store_purchase_invoice_items` pii 
                LEFT JOIN `store_items` si ON pii.item_id = si.id 
                LEFT JOIN `store_units` su ON si.unit_id = su.id 
                WHERE pii.invoice_id=$invoice_id";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    function getSupplierById($id) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $sql = "SELECT * FROM `store_suppliers` WHERE id=$id";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    function getPaymentAllocations($payment_id) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $sql = "SELECT spa.*, spi.invoice_number, spi.invoice_date, spi.net_amount 
                FROM `store_payment_allocations` spa 
                LEFT JOIN `store_purchase_invoices` spi ON spa.invoice_id = spi.id 
                WHERE spa.payment_id=$payment_id";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    function getSupplierLedger($supplier_id, $from_date = null, $to_date = null) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        $sql = "SELECT * FROM vw_supplier_ledger WHERE supplier_id = $supplier_id";
        
        if($from_date && $to_date) {
            $sql .= " AND DATE(transaction_date) BETWEEN '$from_date' AND '$to_date'";
        }
        
        $sql .= " ORDER BY transaction_date ASC, created_at ASC";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    function getSupplierBalance($supplier_id) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        $sql = "SELECT 
                    COALESCE(SUM(CASE WHEN transaction_type = 'INVOICE' THEN debit ELSE 0 END), 0) as total_invoices,
                    COALESCE(SUM(CASE WHEN transaction_type = 'PAYMENT' THEN credit ELSE 0 END), 0) as total_payments,
                    COALESCE((SUM(CASE WHEN transaction_type = 'INVOICE' THEN debit ELSE 0 END) - 
                     SUM(CASE WHEN transaction_type = 'PAYMENT' THEN credit ELSE 0 END)), 0) as outstanding_balance
                FROM vw_supplier_ledger 
                WHERE supplier_id = $supplier_id";
        $res = mysqli_query($con, $sql);
        
        if($res && mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            return $row['outstanding_balance'];
        }
        return 0;
    }
    
    function getAllSuppliersBalance() {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        $sql = "SELECT 
                    supplier_id,
                    supplier_name,
                    SUM(CASE WHEN transaction_type = 'INVOICE' THEN debit ELSE 0 END) as total_invoices,
                    SUM(CASE WHEN transaction_type = 'PAYMENT' THEN credit ELSE 0 END) as total_payments,
                    (SUM(CASE WHEN transaction_type = 'INVOICE' THEN debit ELSE 0 END) - 
                     SUM(CASE WHEN transaction_type = 'PAYMENT' THEN credit ELSE 0 END)) as outstanding_balance
                FROM vw_supplier_ledger 
                GROUP BY supplier_id, supplier_name 
                HAVING outstanding_balance > 0
                ORDER BY outstanding_balance DESC";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    // Get supplier payment by ID with full details
    function getSupplierPaymentById($payment_id) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $store_id = $this->getSessionStoreId();
        $where= "";
        if($_SESSION['role'] != 'Admin') {
            $where = "AND p.store_id = $store_id";
        } 
        $sql = "SELECT p.*, s.supplier_name, s.phone as contact_number, s.email, s.address
                FROM store_supplier_payments p
                INNER JOIN store_suppliers s ON p.supplier_id = s.id
            WHERE p.id = $payment_id $where";
        $res = mysqli_query($con, $sql);
        
        if($res && mysqli_num_rows($res) > 0) {
            return mysqli_fetch_assoc($res);
        }
        return null;
    }
    
    // Get all purchase orders with supplier names
    function getAllPurchaseOrders() {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $store_id = $this->getSessionStoreId();
        $where= "";
        if($_SESSION['role'] != 'Admin') {
            $where = "where po.store_id = $store_id";
        } 
        
            $sql = "SELECT po.*, s.supplier_name, u.name as created_by
                    FROM store_purchase_orders po
                    INNER JOIN store_suppliers s ON po.supplier_id = s.id
                    LEFT JOIN user u ON po.created_by = u.id
                    $where
                    ORDER BY po.po_date DESC";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    // Get purchase order by ID with supplier details
    function getPurchaseOrderById($po_id) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $store_id = $this->getSessionStoreId();
        $where= "";
        if($_SESSION['role'] != 'Admin') {
            $where = "AND po.store_id = $store_id";
        } 
        $sql = "SELECT po.*, s.supplier_name,s.phone,s.email,s.address
                FROM store_purchase_orders po
                INNER JOIN store_suppliers s ON po.supplier_id = s.id
            WHERE po.id = $po_id $where";
        $res = mysqli_query($con, $sql);
        
        if($res && mysqli_num_rows($res) > 0) {
            return mysqli_fetch_assoc($res);
        }
        return null;
    }
    
    // Get PO items with item and unit details
    function getPOItemsByPOId($po_id) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        $sql = "SELECT poi.*, i.item_name, u.unit_name,i.item_code
                FROM store_purchase_order_items poi
                INNER JOIN store_items i ON poi.item_id = i.id
                INNER JOIN store_units u ON i.unit_id = u.id
                WHERE poi.po_id = $po_id
                ORDER BY poi.id";
                // echo $sql;
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    // Get all GRNs with supplier and PO details
    function getAllGRNs() {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $store_id = $this->getSessionStoreId();
        $where= "";
        if($_SESSION['role'] != 'Admin') {
            $where = "WHERE g.store_id = $store_id";
        } 
        $sql = "SELECT g.*, s.supplier_name, po.po_number, u.name as created_by, COALESCE(g.total_amount, po.total_amount, 0) as total_amount
                FROM store_grn g
                INNER JOIN store_suppliers s ON g.supplier_id = s.id
                LEFT JOIN store_purchase_orders po ON g.po_id = po.id
                LEFT JOIN user u ON g.created_by = u.id
                $where
                ORDER BY g.received_date DESC";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    // Get GRN by ID with full details
    function getGRNById($grn_id) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $store_id = $this->getSessionStoreId();
        $where= "";
        if($_SESSION['role'] != 'Admin') {
            $where = "AND g.store_id = $store_id";
        }

        $sql = "SELECT g.*, s.supplier_name, po.po_number,s.email,s.phone,s.address
                FROM store_grn g
                INNER JOIN store_suppliers s ON g.supplier_id = s.id
                LEFT JOIN store_purchase_orders po ON g.po_id = po.id
            WHERE g.id = $grn_id $where";
        $res = mysqli_query($con, $sql);
        
        if($res && mysqli_num_rows($res) > 0) {
            return mysqli_fetch_assoc($res);
        }
        return null;
    }
    
    // Get GRN items with item and unit details
    function getGRNItemsByGRNId($grn_id) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        $sql = "SELECT gi.*, i.item_name, u.unit_name
                FROM store_grn_items gi
                INNER JOIN store_items i ON gi.item_id = i.id
                INNER JOIN store_units u ON i.unit_id = u.id
                WHERE gi.grn_id = $grn_id
                ORDER BY gi.id";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    // Insert PO item into PO
    function insertPOItem($po_id, $item_id, $quantity, $rate) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        $amount = $quantity * $rate;
        $sql = "INSERT INTO store_po_items (po_id, item_id, ordered_quantity, rate, amount) 
                VALUES ($po_id, $item_id, $quantity, $rate, $amount)";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    // Update PO total after adding items
    function updatePOTotalAmount($po_id, $total_amount) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $store_id = $this->getSessionStoreId();
        
        $sql = "UPDATE store_purchase_orders SET total_amount = $total_amount WHERE id = $po_id AND store_id = $store_id";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    // Update GRN total after adding items
    function updateGRNTotalAmount($grn_id, $total_amount) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $store_id = $this->getSessionStoreId();
        
        $sql = "UPDATE store_grn SET total_amount = $total_amount WHERE id = $grn_id AND store_id = $store_id";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    
    // Update PO status based on received items
    function updatePOStatus($po_id) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $store_id = $this->getSessionStoreId();
        
        // Check if all items are fully received
        $sql = "SELECT 
                    SUM(poi.ordered_quantity) as total_ordered,
                    COALESCE(SUM(gi.received_quantity), 0) as total_received
                FROM store_po_items poi
                LEFT JOIN store_grn_items gi ON poi.po_id = gi.grn_id AND poi.item_id = gi.item_id
                WHERE poi.po_id = $po_id";
        $res = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($res);
        
        $status = 'PENDING';
        if($row['total_received'] > 0) {
            if($row['total_received'] >= $row['total_ordered']) {
                $status = 'COMPLETED';
            } else {
                $status = 'PARTIALLY_RECEIVED';
            }
        }
        
        $update_sql = "UPDATE store_purchase_orders SET status = '$status' WHERE id = $po_id AND store_id = $store_id";
        mysqli_query($con, $update_sql);
    }
  
    /**
     * Check if GRN exists for a PO
     */
    function checkGRNExists($po_id) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $store_id = $this->getSessionStoreId();
        $where= "";
        if($_SESSION['role'] != 'Admin') {
            $where = "AND store_id = $store_id";
        } 
        $sql = "SELECT COUNT(*) as count FROM store_grn WHERE po_id = $po_id $where";
        $res = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($res);
        
        return $row['count'] > 0;
    }
    
    /**
     * Cancel a Purchase Order
     */
    function cancelPurchaseOrder($po_id, $cancelled_by = null) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $store_id = $this->getSessionStoreId();
        
        // Check if GRN exists
        if($this->checkGRNExists($po_id)) {
            return array('success' => false, 'message' => 'Cannot cancel PO. GRN already created.');
        }
        
        $sql = "UPDATE store_purchase_orders SET status = 'CANCELLED', updated_at = NOW() WHERE id = $po_id AND store_id = $store_id";
        $result = mysqli_query($con, $sql);
        
        if($result) {
            return array('success' => true, 'message' => 'Purchase Order cancelled successfully.');
        } else {
            return array('success' => false, 'message' => mysqli_error($con));
        }
    }
    
    // ===================================================================
    // CATHLAB SUB-STORE METHODS
    // ===================================================================

    /**
     * Get CathLab Store ID
     */
    function getCathLabStoreId() {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        $sql = "SELECT id FROM cathlab_store WHERE store_type = 'cathlab' AND is_active = 1 LIMIT 1";
        $result = mysqli_query($con, $sql);
        
        if($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            return $row['id'];
        }
        return null;
    }

    /**
     * Get all CathLab Items
     */
    function getCathLabItems($store_id = null) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        if(!$store_id) {
            $store_id = $this->getCathLabStoreId();
        }
        
        $sql = "SELECT csi.*, 
                       SUM(cbm.available_quantity) as current_stock,
                       csi.reorder_level as min_stock
                FROM cathlab_store_items csi
                LEFT JOIN cathlab_batch_master cbm ON csi.id = cbm.item_id AND cbm.status = 'active'
                WHERE csi.store_id = $store_id AND csi.status = 'active'
                GROUP BY csi.id
                ORDER BY csi.item_name ASC";
        
        $result = mysqli_query($con, $sql);
        return $result;
    }

    /**
     * Get Store Categories (for compatibility)
     */
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

    /**
     * Create CathLab Item
     */
    function createCathLabItem($data) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        $store_id = $data['store_id'] ?? $this->getCathLabStoreId();
        $item_code = mysqli_real_escape_string($con, $data['item_code']);
        $item_name = mysqli_real_escape_string($con, $data['item_name']);
        $category = mysqli_real_escape_string($con, $data['category'] ?? 'Uncategorized');
        $unit = mysqli_real_escape_string($con, $data['unit'] ?? 'PCS');
        $item_type = mysqli_real_escape_string($con, $data['item_type'] ?? 'consumable');
        $cost_price = $data['cost_price'] ?? 0;
        $selling_price = $data['selling_price'] ?? 0;
        $is_batch_mandatory = $data['is_batch_mandatory'] ?? 1;
        $is_expiry_mandatory = $data['is_expiry_mandatory'] ?? 1;
        $reorder_level = $data['reorder_level'] ?? 0;
        
        $sql = "INSERT INTO cathlab_store_items 
                (store_id, item_code, item_name, category, unit, item_type, 
                 cost_price, selling_price, is_batch_mandatory, is_expiry_mandatory, 
                 reorder_level, status, created_by, created_at)
                VALUES 
                ($store_id, '$item_code', '$item_name', '$category', '$unit', '$item_type',
                 $cost_price, $selling_price, $is_batch_mandatory, $is_expiry_mandatory,
                 $reorder_level, 'active', " . ($_SESSION['id'] ?? 'NULL') . ", NOW())";
        
        $result = mysqli_query($con, $sql);
        
        if($result) {
            return array('success' => true, 'id' => mysqli_insert_id($con), 'message' => 'Item created successfully');
        } else {
            return array('success' => false, 'message' => mysqli_error($con));
        }
    }

    /**
     * Get Available Batches for Item
     */
    function getAvailableBatches($item_id, $store_id = null) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        if(!$store_id) {
            $store_id = $this->getCathLabStoreId();
        }
        
        $sql = "SELECT id, batch_number, expiry_date, available_quantity, cost_price, status
                FROM cathlab_batch_master
                WHERE item_id = $item_id AND store_id = $store_id AND available_quantity > 0 AND status = 'active'
                AND expiry_date > CURDATE()
                ORDER BY expiry_date ASC, batch_number ASC";
        
        $result = mysqli_query($con, $sql);
        return $result;
    }

    /**
     * Get CathLab Stock Summary
     */
    function getCathLabStockSummary($store_id = null) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        if(!$store_id) {
            $store_id = $this->getCathLabStoreId();
        }
        
        $sql = "SELECT * FROM vw_cathlab_stock_summary WHERE store_id = $store_id";
        $result = mysqli_query($con, $sql);
        return $result;
    }

    /**
     * Get CathLab Expiry Alerts
     */
    function getCathLabExpiryAlerts($store_id = null) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        if(!$store_id) {
            $store_id = $this->getCathLabStoreId();
        }
        
        $sql = "SELECT * FROM vw_cathlab_expiry_alert WHERE store_name LIKE (SELECT store_name FROM cathlab_store WHERE id = $store_id)
                AND (status = 'EXPIRED' OR status = 'EXPIRING_SOON')
                ORDER BY expiry_date ASC";
        
        $result = mysqli_query($con, $sql);
        return $result;
    }

    /**
     * Get Procedure Consumption Details
     */
    function getProcedureConsumption($procedure_id = null) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        if($procedure_id) {
            $sql = "SELECT * FROM vw_cathlab_procedure_consumption WHERE procedure_id = $procedure_id";
        } else {
            $sql = "SELECT * FROM vw_cathlab_procedure_consumption ORDER BY procedure_date DESC";
        }
        
        $result = mysqli_query($con, $sql);
        return $result;
    }

    /**
     * Get Cost vs Revenue Report
     */
    function getCathLabCostVsRevenue($date = null) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        if($date) {
            $sql = "SELECT * FROM vw_cathlab_cost_vs_revenue WHERE procedure_date = '$date'";
        } else {
            $sql = "SELECT * FROM vw_cathlab_cost_vs_revenue ORDER BY procedure_date DESC LIMIT 30";
        }
        
        $result = mysqli_query($con, $sql);
        return $result;
    }

    /**
     * Get Low Stock Items
     */
    function getCathLabLowStockItems($store_id = null) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        if(!$store_id) {
            $store_id = $this->getCathLabStoreId();
        }
        
        $sql = "SELECT csi.*, SUM(cbm.available_quantity) as current_stock
                FROM cathlab_store_items csi
                LEFT JOIN cathlab_batch_master cbm ON csi.id = cbm.item_id AND cbm.status = 'active'
                WHERE csi.store_id = $store_id AND csi.status = 'active'
                GROUP BY csi.id
                HAVING current_stock <= csi.reorder_level
                ORDER BY current_stock ASC";
        
        $result = mysqli_query($con, $sql);
        return $result;
    }

    /**
     * Get Item Stock Ledger
     */
    function getItemStockLedger($item_id, $store_id = null) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        if(!$store_id) {
            $store_id = $this->getCathLabStoreId();
        }

        $item_id = (int)$item_id;
        $store_id = (int)$store_id;
        
        $sql = "SELECT csl.*, csi.item_name, csi.item_code, cbm.batch_number,
                       CASE WHEN csl.transaction_type IN ('GRN_IN', 'RETURN', 'ADJUSTMENT')
                            THEN csl.quantity ELSE 0 END AS quantity_in,
                       CASE WHEN csl.transaction_type IN ('PROCEDURE_RESERVE', 'PROCEDURE_CONSUME', 'BILLING_DEDUCT', 'BILLING_OUT', 'DISCARD', 'TRANSFER')
                            THEN csl.quantity ELSE 0 END AS quantity_out
                FROM cathlab_stock_ledger csl
                LEFT JOIN cathlab_store_items csi ON csl.item_id = csi.id
                LEFT JOIN cathlab_batch_master cbm ON csl.batch_id = cbm.id
                WHERE csl.item_id = $item_id AND csl.store_id = $store_id
                ORDER BY csl.created_at DESC, csl.id DESC";
        
        $result = mysqli_query($con, $sql);
        return $result;
    }

    /**
     * Get Patient Implants
     */
    function getPatientImplants($patient_id) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        $sql = "SELECT cit.*, csi.item_name, csi.item_code, cbm.batch_number, cbm.expiry_date
                FROM cathlab_implant_traceability cit
                JOIN cathlab_store_items csi ON cit.item_id = csi.id
                JOIN cathlab_batch_master cbm ON cit.batch_id = cbm.id
                WHERE cit.patient_id = $patient_id
                ORDER BY cit.created_at DESC";
        
        $result = mysqli_query($con, $sql);
        return $result;
    }

    /**
     * Get Procedure Details
     */
    function getProcedureDetails($procedure_id) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        $sql = "SELECT * FROM cathlab_procedure WHERE id = $procedure_id";
        $result = mysqli_query($con, $sql);
        
        if($result && mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        }
        return null;
    }

    /**
     * Get Procedure Items for Billing
     */
    function getProcedureItemsForBilling($procedure_id) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        $sql = "SELECT cpi.*, csi.item_name, csi.item_code, cbm.batch_number
                FROM cathlab_procedure_items cpi
                JOIN cathlab_store_items csi ON cpi.item_id = csi.id
                LEFT JOIN cathlab_batch_master cbm ON cpi.batch_id = cbm.id
                WHERE cpi.procedure_id = $procedure_id AND cpi.status = 'pending_billing'
                ORDER BY cpi.id ASC";
        
        $result = mysqli_query($con, $sql);
        return $result;
    }

    /**
     * Create CathLab GRN
     */
    function createCathLabGRN($data) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        $grn_number = mysqli_real_escape_string($con, $data['grn_number']);
        $store_id = $data['store_id'] ?? $this->getCathLabStoreId();
        $grn_date = $data['grn_date'];
        $supplier_name = mysqli_real_escape_string($con, $data['supplier_name']);
        $total_items = $data['total_items'] ?? 0;
        $total_quantity = $data['total_quantity'] ?? 0;
        $total_cost = $data['total_cost'] ?? 0;
        
        $sql = "INSERT INTO cathlab_grn 
                (grn_number, store_id, grn_date, supplier_name, total_items, total_quantity, total_cost, created_by, created_at)
                VALUES 
                ('$grn_number', $store_id, '$grn_date', '$supplier_name', $total_items, $total_quantity, $total_cost, " . ($_SESSION['id'] ?? 'NULL') . ", NOW())";
        
        $result = mysqli_query($con, $sql);
        
        if($result) {
            return array('success' => true, 'id' => mysqli_insert_id($con), 'message' => 'GRN created successfully');
        } else {
            return array('success' => false, 'message' => mysqli_error($con));
        }
    }

    /**
     * Create CathLab Procedure
     */
    function createCathLabProcedure($data) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        $procedure_code = mysqli_real_escape_string($con, $data['procedure_code']);
        $patient_id = $data['patient_id'];
        $patient_name = mysqli_real_escape_string($con, $data['patient_name']);
        $doctor_name = mysqli_real_escape_string($con, $data['doctor_name']);
        $procedure_type = mysqli_real_escape_string($con, $data['procedure_type']);
        $procedure_date = $data['procedure_date'];
        
        $sql = "INSERT INTO cathlab_procedure 
                (procedure_code, patient_id, patient_name, doctor_name, procedure_type, procedure_date, status, billed_status, created_by, created_at)
                VALUES 
                ('$procedure_code', $patient_id, '$patient_name', '$doctor_name', '$procedure_type', '$procedure_date', 'completed', 'pending', " . ($_SESSION['id'] ?? 'NULL') . ", NOW())";
        
        $result = mysqli_query($con, $sql);
        
        if($result) {
            return array('success' => true, 'id' => mysqli_insert_id($con), 'message' => 'Procedure created successfully');
        } else {
            return array('success' => false, 'message' => mysqli_error($con));
        }
    }

    /**
     * Add Item to Procedure
     */
    function addItemToProcedure($data) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        $procedure_id = $data['procedure_id'];
        $item_id = $data['item_id'];
        $batch_id = $data['batch_id'];
        $quantity_consumed = $data['quantity_consumed'];
        $cost_price = $data['cost_price'];
        
        $sql = "INSERT INTO cathlab_procedure_items 
                (procedure_id, item_id, batch_id, quantity_consumed, cost_price, status, created_at)
                VALUES 
                ($procedure_id, $item_id, $batch_id, $quantity_consumed, $cost_price, 'pending_billing', NOW())";
        
        $result = mysqli_query($con, $sql);
        
        if($result) {
            // Reserve stock from batch
            $update_batch = "UPDATE cathlab_batch_master 
                           SET available_quantity = available_quantity - $quantity_consumed,
                               reserved_quantity = reserved_quantity + $quantity_consumed
                           WHERE id = $batch_id";
            mysqli_query($con, $update_batch);
            
            // Add stock ledger entry
            $store_id = $this->getCathLabStoreId();
            $ledger = "INSERT INTO cathlab_stock_ledger 
                     (store_id, item_id, batch_id, transaction_type, quantity, reference_type, reference_id, created_by, created_at)
                     VALUES 
                     ($store_id, $item_id, $batch_id, 'PROCEDURE_RESERVE', $quantity_consumed, 'PROCEDURE', $procedure_id, " . ($_SESSION['id'] ?? 'NULL') . ", NOW())";
            mysqli_query($con, $ledger);
            
            return array('success' => true, 'message' => 'Item added to procedure successfully');
        } else {
            return array('success' => false, 'message' => mysqli_error($con));
        }
    }

    /**
     * Finalize CathLab Billing (Deduct Stock)
     */
    function finalizeCathLabBilling($data) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        $procedure_id = $data['procedure_id'];
        $billing_id = $data['billing_id'] ?? null;
        
        // Get all pending items for this procedure
        $sql = "SELECT cpi.*, cbm.id as batch_id FROM cathlab_procedure_items cpi
                LEFT JOIN cathlab_batch_master cbm ON cpi.batch_id = cbm.id
                WHERE cpi.procedure_id = $procedure_id AND cpi.status = 'pending_billing'";
        
        $result = mysqli_query($con, $sql);
        $store_id = $this->getCathLabStoreId();
        
        while($row = mysqli_fetch_assoc($result)) {
            $item_id = $row['item_id'];
            $batch_id = $row['batch_id'];
            $quantity = $row['quantity_consumed'];
            
            // Update batch: reserved -> consumed
            $update = "UPDATE cathlab_batch_master 
                      SET reserved_quantity = reserved_quantity - $quantity,
                          consumed_quantity = consumed_quantity + $quantity
                      WHERE id = $batch_id";
            mysqli_query($con, $update);
            
            // Update procedure item status to billed
            $update_item = "UPDATE cathlab_procedure_items SET status = 'billed' WHERE id = " . $row['id'];
            mysqli_query($con, $update_item);
            
            // Add stock ledger entry for deduction
            $ledger = "INSERT INTO cathlab_stock_ledger 
                     (store_id, item_id, batch_id, transaction_type, quantity, reference_type, reference_id, created_by, created_at)
                     VALUES 
                     ($store_id, $item_id, $batch_id, 'BILLING_DEDUCT', $quantity, 'BILLING', " . ($billing_id ?? $procedure_id) . ", " . ($_SESSION['id'] ?? 'NULL') . ", NOW())";
            mysqli_query($con, $ledger);
        }
        
        // Update procedure billing status
        $update_proc = "UPDATE cathlab_procedure SET billed_status = 'billed' WHERE id = $procedure_id";
        mysqli_query($con, $update_proc);
        
        return array('success' => true, 'message' => 'Billing finalized and stock deducted successfully');
    }
    
    /**
     * Get CathLab Inventory Summary
     */
    function getCathLabInventorySummary() {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        $store_id = $this->getCathLabStoreId();
        
        $sql = "SELECT 
                COUNT(DISTINCT csi.id) as total_items,
                SUM(cbm.available_quantity) as total_available,
                SUM(cbm.available_quantity * csi.cost_price) as total_value
                FROM cathlab_store_items csi
                LEFT JOIN cathlab_batch_master cbm ON csi.id = cbm.item_id AND cbm.status = 'active'
                WHERE csi.store_id = $store_id AND csi.status = 'active'";
        
        $result = mysqli_query($con, $sql);
        
        if($result && mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        }
        
        return array('total_items' => 0, 'total_available' => 0, 'total_value' => 0);
    }

    /**
     * Get CathLab GRN Count for a period
     */
    function getCathLabGRNCount($period = null) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        $store_id = $this->getCathLabStoreId();
        
        if(!$period) {
            $period = date('Y-m');
        }
        
        $sql = "SELECT 
                COUNT(*) as total,
                SUM(total_cost) as total_cost,
                SUM(total_quantity) as total_qty
                FROM cathlab_grn
                WHERE store_id = $store_id 
                AND DATE_FORMAT(grn_date, '%Y-%m') = '$period'";
        
        $result = mysqli_query($con, $sql);
        
        if($result && mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        }
        
        return array('total' => 0, 'total_cost' => 0, 'total_qty' => 0);
    }

    /**
     * Get CathLab Billing Count for a period
     */
    function getCathLabBillingCount($period = null) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        if(!$period) {
            $period = date('Y-m');
        }
        
        $sql = "SELECT 
                COUNT(*) as total,
                SUM(total_amount) as total_amount,
                AVG(total_amount) as avg_amount
                FROM cathlab_billing
                WHERE DATE_FORMAT(created_at, '%Y-%m') = '$period'
                AND payment_status IN ('paid', 'pending', 'partial')";
        
        $result = mysqli_query($con, $sql);
        
        if($result && mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        }
        
        return array('total' => 0, 'total_amount' => 0, 'avg_amount' => 0);
    }

    /**
     * Get Recent CathLab GRNs
     */
    function getRecentCathLabGRNs($limit = 10) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        $store_id = $this->getCathLabStoreId();
        
        $sql = "SELECT 
                cg.id,
                cg.grn_number,
                cg.grn_date,
                cg.supplier_name,
                cg.total_cost,
                cg.total_quantity,
                cg.total_items as item_count,
                'completed' as status
                FROM cathlab_grn cg
                WHERE cg.store_id = $store_id
                ORDER BY cg.grn_date DESC
                LIMIT $limit";
        
        $result = mysqli_query($con, $sql);
        
        $grns = array();
        if($result && mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $grns[] = $row;
            }
        }
        
        return $grns;
    }

    /**
     * Get Recent CathLab Billings
     */
    function getRecentCathLabBillings($limit = 10) {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        $sql = "SELECT 
                cb.id,
                cb.billing_number,
                cb.patient_id,
                CONCAT(cp.patient_name) as patient_name,
                cb.patient_id as patient_ip_no,
                cp.procedure_type,
                cb.total_amount,
                cb.created_at as billing_date,
                cb.payment_status as status
                FROM cathlab_billing cb
                LEFT JOIN cathlab_procedure cp ON cb.procedure_id = cp.id
                ORDER BY cb.created_at DESC
                LIMIT $limit";
        
        $result = mysqli_query($con, $sql);
        
        $billings = array();
        if($result && mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $billings[] = $row;
            }
        }
        
        return $billings;
    }

    /**
     * Get CathLab Dashboard Statistics
     */
    function getCathLabDashboardStats() {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        
        $store_id = $this->getCathLabStoreId();
        $current_month = date('Y-m');
        
        $stats = array(
            'total_items' => 0,
            'total_value' => 0,
            'low_stock_count' => 0,
            'grn_this_month' => 0,
            'billing_this_month' => 0,
            'total_revenue_month' => 0,
            'expiring_soon' => 0
        );
        
        // Total items
        $sql1 = "SELECT COUNT(DISTINCT id) as count FROM cathlab_store_items WHERE store_id = $store_id AND status = 'active'";
        $r1 = mysqli_query($con, $sql1);
        if($r1) $stats['total_items'] = mysqli_fetch_assoc($r1)['count'];
        
        // Total value
        $sql2 = "SELECT SUM(available_quantity * cost_price) as value FROM cathlab_batch_master WHERE store_id = $store_id AND status = 'active'";
        $r2 = mysqli_query($con, $sql2);
        if($r2) {
            $val = mysqli_fetch_assoc($r2);
            $stats['total_value'] = $val['value'] ?? 0;
        }
        
        // Low stock count
        $sql3 = "SELECT COUNT(DISTINCT csi.id) as count FROM cathlab_store_items csi 
                 LEFT JOIN cathlab_batch_master cbm ON csi.id = cbm.item_id AND cbm.status = 'active'
                 WHERE csi.store_id = $store_id AND SUM(cbm.available_quantity) <= csi.reorder_level";
        $r3 = mysqli_query($con, $sql3);
        if($r3) $stats['low_stock_count'] = mysqli_fetch_assoc($r3)['count'];
        
        // GRN this month
        $sql4 = "SELECT COUNT(*) as count FROM cathlab_grn WHERE store_id = $store_id AND DATE_FORMAT(grn_date, '%Y-%m') = '$current_month'";
        $r4 = mysqli_query($con, $sql4);
        if($r4) $stats['grn_this_month'] = mysqli_fetch_assoc($r4)['count'];
        
        // Billing this month
        $sql5 = "SELECT COUNT(*) as count FROM cathlab_billing WHERE DATE_FORMAT(created_at, '%Y-%m') = '$current_month'";
        $r5 = mysqli_query($con, $sql5);
        if($r5) $stats['billing_this_month'] = mysqli_fetch_assoc($r5)['count'];
        
        // Revenue this month
        $sql6 = "SELECT SUM(total_amount) as revenue FROM cathlab_billing WHERE DATE_FORMAT(created_at, '%Y-%m') = '$current_month'";
        $r6 = mysqli_query($con, $sql6);
        if($r6) {
            $rev = mysqli_fetch_assoc($r6);
            $stats['total_revenue_month'] = $rev['revenue'] ?? 0;
        }
        
        // Expiring soon
        $sql7 = "SELECT COUNT(*) as count FROM cathlab_batch_master WHERE store_id = $store_id AND status = 'active' 
                 AND expiry_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)";
        $r7 = mysqli_query($con, $sql7);
        if($r7) $stats['expiring_soon'] = mysqli_fetch_assoc($r7)['count'];
        
        return $stats;
    }
    
     function getStockValue() {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
          $store_id = $this->getSessionStoreId();
        $where= "";
        if($_SESSION['role'] != 'Admin') {
            $where = "AND sg.store_id = $store_id";
        } 
        $sql = "SELECT SUM(ss.available_qty * sgi.rate) as total_value 
        FROM store_items si,store_stock ss,store_grn sg,store_grn_items sgi 
        WHERE si.status = '1' AND si.id=ss.item_id 
          AND sg.id=sgi.grn_id AND sgi.item_id=si.id AND sgi.batch_no=ss.batch_no
          AND ss.store_id = $store_id $where";
        
        return mysqli_query($con, $sql);
    }
    
    function getLowStockItems_dash() {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $store_id = $this->getSessionStoreId();
        $where= "";
        if($_SESSION['role'] != 'Admin') {
            $where = "AND ss.store_id = $store_id";
        } 
        $sql = "SELECT si.item_code, si.item_name, si.reorder_level, COALESCE(SUM(ss.available_qty), 0) as current_stock, su.unit_name, sc.category_name,min_stock 
                FROM `store_items` si 
            LEFT JOIN `store_stock` ss ON si.id = ss.item_id $where
                LEFT JOIN `store_units` su ON si.unit_id = su.id 
                LEFT JOIN `store_categories` sc ON si.category_id = sc.id 
                WHERE si.status = 1 
                GROUP BY si.id 
                HAVING current_stock < si.reorder_level 
                ORDER BY current_stock ASC
                LIMIT 20";
        
        return mysqli_query($con, $sql);
    }
    

    
    function getTodayGRNCount($today) {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $store_id = $this->getSessionStoreId();
        $where= "";
        if($_SESSION['role'] != 'Admin') {
            $where = "AND store_id = $store_id";
        } 
        $sql = "SELECT COUNT(*) as count FROM store_grn WHERE DATE(received_date) = '$today' $where";
        
        return mysqli_query($con, $sql);
    }
    
    function getTodayIssueCount($today) {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $store_id = $this->getSessionStoreId();
        $where= "";
        if($_SESSION['role'] != 'Admin') {
            $where = "AND store_id = $store_id";
        } 
        $sql = "SELECT COUNT(*) as count FROM store_issue_header WHERE DATE(issue_date) = '$today' $where";
        
        return mysqli_query($con, $sql);
    }
    
    function getRecentGRNs($limit = 5) {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $store_id = $this->getSessionStoreId();
        $where= "";
        if($_SESSION['role'] != 'Admin') {
            $where = "WHERE sg.store_id = $store_id";
        } 
        
        $sql = "SELECT sg.*, ss.supplier_name
                FROM store_grn sg 
                LEFT JOIN store_suppliers ss ON sg.supplier_id = ss.id 
            $where
                ORDER BY sg.received_date DESC, sg.id DESC
                LIMIT $limit";
        
        return mysqli_query($con, $sql);
    }
    
    function getRecentIssues($limit = 5) {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $store_id = $this->getSessionStoreId();
        $where= "";
        if($_SESSION['role'] != 'Admin') {
            $where = "WHERE store_id = $store_id";
        } 
        
        $sql = "SELECT * FROM store_issue_header 
            $where
                ORDER BY issue_date DESC, id DESC 
                LIMIT $limit";
        
        return mysqli_query($con, $sql);
    }

    // ======================== STORE REPORT METHODS ========================

    function getPurchaseItemWiseReport($from_date, $to_date, $item_id = 0, $supplier_id = 0) {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $store_id = $this->getSessionStoreId();

        $from_date = mysqli_real_escape_string($con, $from_date);
        $to_date = mysqli_real_escape_string($con, $to_date);
        $where = "WHERE g.received_date >= '$from_date' AND g.received_date <= '$to_date'";

        if($_SESSION['role'] != 'Admin') {
            $where .= " AND g.store_id = $store_id";
        }
        if($item_id > 0) {
            $where .= " AND gi.item_id = ".intval($item_id);
        }
        if($supplier_id > 0) {
            $where .= " AND g.supplier_id = ".intval($supplier_id);
        }

        $sql = "SELECT i.item_code, i.item_name, u.unit_name,
                       COUNT(DISTINCT g.id) AS grn_count,
                       SUM(gi.quantity) AS total_qty,
                       ROUND(AVG(gi.rate), 2) AS avg_rate,
                       SUM(gi.amount) AS total_amount,
                       SUM(COALESCE(gi.gst_amount, 0)) AS total_gst,
                       SUM(COALESCE(gi.net_amount, gi.amount + COALESCE(gi.gst_amount, 0))) AS total_net
                FROM store_grn_items gi
                INNER JOIN store_grn g ON g.id = gi.grn_id
                INNER JOIN store_items i ON i.id = gi.item_id
                LEFT JOIN store_units u ON u.id = i.unit_id
                $where
                GROUP BY gi.item_id, i.item_code, i.item_name, u.unit_name
                ORDER BY i.item_name ASC";

        return mysqli_query($con, $sql);
    }

    function getPurchaseSupplierWiseReport($from_date, $to_date, $supplier_id = 0) {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $store_id = $this->getSessionStoreId();

        $from_date = mysqli_real_escape_string($con, $from_date);
        $to_date = mysqli_real_escape_string($con, $to_date);
        $where = "WHERE g.received_date >= '$from_date' AND g.received_date <= '$to_date'";

        if($_SESSION['role'] != 'Admin') {
            $where .= " AND g.store_id = $store_id";
        }
        if($supplier_id > 0) {
            $where .= " AND g.supplier_id = ".intval($supplier_id);
        }

        $sql = "SELECT s.supplier_name,
                       COUNT(DISTINCT g.id) AS grn_count,
                       COUNT(DISTINCT g.invoice_number) AS invoice_count,
                       SUM(gi.quantity) AS total_qty,
                       SUM(gi.amount) AS total_amount,
                       SUM(COALESCE(gi.gst_amount, 0)) AS total_gst,
                       SUM(COALESCE(gi.net_amount, gi.amount + COALESCE(gi.gst_amount, 0))) AS total_net
                FROM store_grn g
                INNER JOIN store_suppliers s ON s.id = g.supplier_id
                LEFT JOIN store_grn_items gi ON gi.grn_id = g.id
                $where
                GROUP BY g.supplier_id, s.supplier_name
                ORDER BY s.supplier_name ASC";

        return mysqli_query($con, $sql);
    }

    function getIssueItemWiseReport($from_date, $to_date, $item_id = 0, $department = '') {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $store_id = $this->getSessionStoreId();

        $from_date = mysqli_real_escape_string($con, $from_date);
        $to_date = mysqli_real_escape_string($con, $to_date);
        $where = "WHERE ih.issue_date >= '$from_date' AND ih.issue_date <= '$to_date'";

        if($_SESSION['role'] != 'Admin') {
            $where .= " AND ih.store_id = $store_id";
        }
        if($item_id > 0) {
            $where .= " AND ii.item_id = ".intval($item_id);
        }
        if(!empty($department)) {
            $department = mysqli_real_escape_string($con, $department);
            $where .= " AND ih.department LIKE '%$department%'";
        }

        $sql = "SELECT i.item_code, i.item_name, u.unit_name,
                       COUNT(DISTINCT ih.id) AS issue_count,
                       SUM(ii.quantity) AS total_qty,
                       ROUND(AVG(COALESCE((
                           SELECT sgi.rate
                           FROM store_grn_items sgi
                           INNER JOIN store_grn sg ON sg.id = sgi.grn_id
                           WHERE sgi.item_id = ii.item_id
                             AND (
                                 (ii.batch_no IS NOT NULL AND ii.batch_no != '' AND sgi.batch_no = ii.batch_no)
                                 OR (ii.batch_no IS NULL OR ii.batch_no = '')
                             )
                           ORDER BY sg.received_date DESC, sgi.id DESC
                           LIMIT 1
                       ), 0)), 2) AS avg_rate,
                       ROUND(SUM(ii.quantity * COALESCE((
                           SELECT sgi.rate
                           FROM store_grn_items sgi
                           INNER JOIN store_grn sg ON sg.id = sgi.grn_id
                           WHERE sgi.item_id = ii.item_id
                             AND (
                                 (ii.batch_no IS NOT NULL AND ii.batch_no != '' AND sgi.batch_no = ii.batch_no)
                                 OR (ii.batch_no IS NULL OR ii.batch_no = '')
                             )
                           ORDER BY sg.received_date DESC, sgi.id DESC
                           LIMIT 1
                       ), 0)), 2) AS total_amount,
                       GROUP_CONCAT(DISTINCT ih.department ORDER BY ih.department SEPARATOR ', ') AS departments
                FROM store_issue_items ii
                INNER JOIN store_issue_header ih ON ih.id = ii.issue_id
                INNER JOIN store_items i ON i.id = ii.item_id
                LEFT JOIN store_units u ON u.id = i.unit_id
                $where
                GROUP BY ii.item_id, i.item_code, i.item_name, u.unit_name
                ORDER BY i.item_name ASC";

        return mysqli_query($con, $sql);
    }

    function getIssueSummaryReport($from_date, $to_date, $department = '') {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $store_id = $this->getSessionStoreId();

        $from_date = mysqli_real_escape_string($con, $from_date);
        $to_date = mysqli_real_escape_string($con, $to_date);
        $where = "WHERE ih.issue_date >= '$from_date' AND ih.issue_date <= '$to_date'";

        if($_SESSION['role'] != 'Admin') {
            $where .= " AND ih.store_id = $store_id";
        }
        if(!empty($department)) {
            $department = mysqli_real_escape_string($con, $department);
            $where .= " AND ih.department LIKE '%$department%'";
        }

        $sql = "SELECT ih.issue_number, ih.issue_date, ih.department, ih.requested_by, ih.issued_by, ih.status, ih.remarks,
                       COUNT(ii.id) AS item_count,
                       SUM(ii.quantity) AS total_qty,
                       ROUND(SUM(ii.quantity * COALESCE((
                           SELECT sgi.rate
                           FROM store_grn_items sgi
                           INNER JOIN store_grn sg ON sg.id = sgi.grn_id
                           WHERE sgi.item_id = ii.item_id
                             AND (
                                 (ii.batch_no IS NOT NULL AND ii.batch_no != '' AND sgi.batch_no = ii.batch_no)
                                 OR (ii.batch_no IS NULL OR ii.batch_no = '')
                             )
                           ORDER BY sg.received_date DESC, sgi.id DESC
                           LIMIT 1
                       ), 0)), 2) AS total_amount
                FROM store_issue_header ih
                LEFT JOIN store_issue_items ii ON ii.issue_id = ih.id
                $where
                GROUP BY ih.id, ih.issue_number, ih.issue_date, ih.department, ih.requested_by, ih.issued_by, ih.status, ih.remarks
                ORDER BY ih.issue_date DESC, ih.id DESC";

        return mysqli_query($con, $sql);
    }

    function getIssueDetailedReport($from_date, $to_date, $item_id = 0, $department = '') {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $store_id = $this->getSessionStoreId();

        $from_date = mysqli_real_escape_string($con, $from_date);
        $to_date = mysqli_real_escape_string($con, $to_date);
        $where = "WHERE ih.issue_date >= '$from_date' AND ih.issue_date <= '$to_date'";

        if($_SESSION['role'] != 'Admin') {
            $where .= " AND ih.store_id = $store_id";
        }
        if($item_id > 0) {
            $where .= " AND ii.item_id = ".intval($item_id);
        }
        if(!empty($department)) {
            $department = mysqli_real_escape_string($con, $department);
            $where .= " AND ih.department LIKE '%$department%'";
        }

        $sql = "SELECT ih.issue_number, ih.issue_date, ih.department, ih.requested_by, u2.name AS created_by_user_id, ih.status,
                       i.item_code, i.item_name, u.unit_name, ii.batch_no, ii.serial_no, ii.quantity,
                       ROUND(COALESCE((
                           SELECT sgi.rate
                           FROM store_grn_items sgi
                           INNER JOIN store_grn sg ON sg.id = sgi.grn_id
                           WHERE sgi.item_id = ii.item_id
                             AND (
                                 (ii.batch_no IS NOT NULL AND ii.batch_no != '' AND sgi.batch_no = ii.batch_no)
                                 OR (ii.batch_no IS NULL OR ii.batch_no = '')
                             )
                           ORDER BY sg.received_date DESC, sgi.id DESC
                           LIMIT 1
                       ), 0), 2) AS rate,
                       ROUND(ii.quantity * COALESCE((
                           SELECT sgi.rate
                           FROM store_grn_items sgi
                           INNER JOIN store_grn sg ON sg.id = sgi.grn_id
                           WHERE sgi.item_id = ii.item_id
                             AND (
                                 (ii.batch_no IS NOT NULL AND ii.batch_no != '' AND sgi.batch_no = ii.batch_no)
                                 OR (ii.batch_no IS NULL OR ii.batch_no = '')
                             )
                           ORDER BY sg.received_date DESC, sgi.id DESC
                           LIMIT 1
                       ), 0), 2) AS total_amount
                FROM store_issue_items ii
                INNER JOIN store_issue_header ih ON ih.id = ii.issue_id
                INNER JOIN store_items i ON i.id = ii.item_id
                INNER JOIN user u2 ON u2.id = ih.issued_by
                LEFT JOIN store_units u ON u.id = i.unit_id
                $where
                ORDER BY ih.issue_date DESC, ih.id DESC, i.item_name ASC";
// echo $sql;
        return mysqli_query($con, $sql);
    }

    function getPurchaseSummaryReport($from_date, $to_date, $supplier_id = 0) {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $store_id = $this->getSessionStoreId();

        $from_date = mysqli_real_escape_string($con, $from_date);
        $to_date = mysqli_real_escape_string($con, $to_date);
        $where = "WHERE g.received_date >= '$from_date' AND g.received_date <= '$to_date'";

        if($_SESSION['role'] != 'Admin') {
            $where .= " AND g.store_id = $store_id";
        }
        if($supplier_id > 0) {
            $where .= " AND g.supplier_id = ".intval($supplier_id);
        }

        $sql = "SELECT g.id, g.grn_number, g.received_date, g.invoice_number, g.invoice_date, s.supplier_name, po.po_number,
                       COUNT(gi.id) AS item_count,
                       SUM(gi.quantity) AS total_qty,
                       SUM(gi.amount) AS total_amount,
                       SUM(COALESCE(gi.gst_amount, 0)) AS total_gst,
                       SUM(COALESCE(gi.net_amount, gi.amount + COALESCE(gi.gst_amount, 0))) AS total_net
                FROM store_grn g
                INNER JOIN store_suppliers s ON s.id = g.supplier_id
                LEFT JOIN store_purchase_orders po ON po.id = g.po_id
                LEFT JOIN store_grn_items gi ON gi.grn_id = g.id
                $where
                GROUP BY g.id, g.grn_number, g.received_date, g.invoice_number, g.invoice_date, s.supplier_name, po.po_number
                ORDER BY g.received_date DESC, g.id DESC";

        return mysqli_query($con, $sql);
    }

    function getPurchaseDetailedReport($from_date, $to_date, $item_id = 0, $supplier_id = 0) {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $store_id = $this->getSessionStoreId();

        $from_date = mysqli_real_escape_string($con, $from_date);
        $to_date = mysqli_real_escape_string($con, $to_date);
        $where = "WHERE g.received_date >= '$from_date' AND g.received_date <= '$to_date'";

        if($_SESSION['role'] != 'Admin') {
            $where .= " AND g.store_id = $store_id";
        }
        if($item_id > 0) {
            $where .= " AND gi.item_id = ".intval($item_id);
        }
        if($supplier_id > 0) {
            $where .= " AND g.supplier_id = ".intval($supplier_id);
        }

        $sql = "SELECT g.grn_number, g.received_date, g.invoice_number, po.po_number, s.supplier_name,
                       i.item_code, i.item_name, u.unit_name, gi.batch_no, gi.expiry_date, gi.serial_no,
                       gi.quantity, gi.rate, gi.amount, gi.gst_percent, gi.gst_amount,
                       COALESCE(gi.net_amount, gi.amount + COALESCE(gi.gst_amount, 0)) AS net_amount
                FROM store_grn_items gi
                INNER JOIN store_grn g ON g.id = gi.grn_id
                INNER JOIN store_suppliers s ON s.id = g.supplier_id
                LEFT JOIN store_purchase_orders po ON po.id = g.po_id
                INNER JOIN store_items i ON i.id = gi.item_id
                LEFT JOIN store_units u ON u.id = i.unit_id
                $where
                ORDER BY g.received_date DESC, g.id DESC, i.item_name ASC";

        return mysqli_query($con, $sql);
    }

    function getSubstores() {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();

        $sql = "SELECT * FROM sub_store WHERE status = 'active' ORDER BY store_name ASC";
        return mysqli_query($con, $sql);
    }
    
   function getSubStore($id) {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();

        $sql = "SELECT * FROM sub_store WHERE id = $id";
        $result = mysqli_query($con, $sql);
        if($result && mysqli_num_rows($result) > 0) {
            return $result;
        }
        return null;
    }

     // Insert sub store
    function insertSubStore() {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();

        $store_name = mysqli_real_escape_string($con, $this->name);
        $contact_person = mysqli_real_escape_string($con, $this->contact_person ?? '');
        $phone = mysqli_real_escape_string($con, $this->phone ?? '');
        $email = mysqli_real_escape_string($con, $this->email ?? '');
        $address = mysqli_real_escape_string($con, $this->address ?? '');
        $status = mysqli_real_escape_string($con, $this->status ?? 'active');

        $sql = "INSERT INTO sub_store (store_name, contact_person, phone, email, address, status, created_at) 
                VALUES ('$store_name', '$contact_person', '$phone', '$email', '$address', '$status', NOW())";

        $result = mysqli_query($con, $sql);
        if($result) {
            return mysqli_insert_id($con);
        }
        return false;
    }

    // Update sub store
    function updateSubStore($id) {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();

        $store_name = mysqli_real_escape_string($con, $this->name);
        $contact_person = mysqli_real_escape_string($con, $this->contact_person ?? '');
        $phone = mysqli_real_escape_string($con, $this->phone ?? '');
        $email = mysqli_real_escape_string($con, $this->email ?? '');
        $address = mysqli_real_escape_string($con, $this->address ?? '');
        $status = mysqli_real_escape_string($con, $this->status ?? 'active');

        $sql = "UPDATE sub_store SET 
                store_name = '$store_name',
                contact_person = '$contact_person',
                phone = '$phone',
                email = '$email',
                address = '$address',
                status = '$status',
                updated_at = NOW()
                WHERE id = $id";

        return mysqli_query($con, $sql);
    }

    // Delete sub store
    function deleteSubStore($id) {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();

        $sql = "DELETE FROM sub_store WHERE id = $id";
        return mysqli_query($con, $sql);
    }
}
?>