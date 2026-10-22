<?php
require_once(__DIR__ . '/../model/session_config.php');
include('../model/storeClass.php');
$newStore = new store;

function validateGRNInvoiceUpload($file) {
    if(!isset($file['error']) || $file['error'] == UPLOAD_ERR_NO_FILE) {
        return array('success' => true);
    }

    if($file['error'] != UPLOAD_ERR_OK) {
        return array('success' => false, 'message' => 'Invoice upload failed. Please try again.');
    }

    $allowed_extensions = array('pdf', 'jpg', 'jpeg', 'png');
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if(!in_array($extension, $allowed_extensions)) {
        return array('success' => false, 'message' => 'Only PDF, JPG, JPEG and PNG files are allowed for invoice upload.');
    }

    if($file['size'] > (5 * 1024 * 1024)) {
        return array('success' => false, 'message' => 'Invoice file size must be less than 5MB.');
    }

    return array('success' => true);
}

function saveGRNInvoiceUpload($grn_number, $file) {
    if(!isset($file['error']) || $file['error'] == UPLOAD_ERR_NO_FILE) {
        return array('success' => true, 'file' => null);
    }

    $validation = validateGRNInvoiceUpload($file);
    if(!$validation['success']) {
        return $validation;
    }

    $upload_dir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'store_grn_invoices' . DIRECTORY_SEPARATOR;
    if(!is_dir($upload_dir) && !mkdir($upload_dir, 0777, true)) {
        return array('success' => false, 'message' => 'Unable to create invoice upload folder.');
    }

    @chmod(dirname($upload_dir), 0777);
    @chmod($upload_dir, 0777);
    clearstatcache(true, $upload_dir);

    if(!is_writable($upload_dir)) {
        return array(
            'success' => false,
            'message' => 'Invoice upload folder is not writable. In Docker, run: chown -R www-data:www-data /var/www/html/uploads && chmod -R 775 /var/www/html/uploads'
        );
    }

    $safe_grn_number = preg_replace('/[^A-Za-z0-9_-]/', '_', $grn_number);
    $existing_files = glob($upload_dir . $safe_grn_number . '.*');
    if(is_array($existing_files)) {
        foreach($existing_files as $existing_file) {
            if(is_file($existing_file)) {
                @unlink($existing_file);
            }
        }
    }

    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $file_name = $safe_grn_number . '.' . $extension;
    $target_path = $upload_dir . $file_name;

    if(@move_uploaded_file($file['tmp_name'], $target_path) || @rename($file['tmp_name'], $target_path) || @copy($file['tmp_name'], $target_path)) {
        @chmod($target_path, 0664);
        return array('success' => true, 'file' => 'uploads/store_grn_invoices/' . $file_name);
    }

    $temp_dir = ini_get('upload_tmp_dir');
    $temp_hint = $temp_dir ? ' PHP temp dir: ' . $temp_dir . '.' : '';
    return array('success' => false, 'message' => 'Unable to save the invoice file on the server. Check Docker write permissions for uploads/store_grn_invoices.' . $temp_hint);
}

// ======================== CATEGORY OPERATIONS ========================
if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='store_category')
{
    $newStore->setName($_REQUEST['name']);
    $newStore->setCategoryType($_REQUEST['category_type']);
    $newStore->setStatus($_REQUEST['status']);
    if($_REQUEST['condition']=='new')
    {
        $newStore->insertCategory();
    }
    else{
        $newStore->updateCategory($_REQUEST['editid']);
    }
    echo "<script>window.location='../add_store_category.php?success=1'</script>";
}

// ======================== UNIT OPERATIONS ========================
if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='store_unit')
{
    $newStore->setName($_REQUEST['name']);
    $newStore->setStatus($_REQUEST['status']);
    if($_REQUEST['condition']=='new')
    {
        $newStore->insertUnit();
    }
    else{
        $newStore->updateUnit($_REQUEST['editid']);
    }
    echo "<script>window.location='../add_store_unit.php?success=1'</script>";
}

// ======================== SUPPLIER OPERATIONS ========================
if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='store_supplier')
{
    $newStore->setName($_REQUEST['name']);
    $newStore->setContactPerson($_REQUEST['contact_person']);
    $newStore->setPhone($_REQUEST['phone']);
    $newStore->setEmail($_REQUEST['email']);
    $newStore->setGstNumber($_REQUEST['gst_number']);
    $newStore->setAddress($_REQUEST['address']);
    $newStore->setStatus($_REQUEST['status']);
    
    if($_REQUEST['condition']=='new')
    {
        $newStore->insertSupplier();
    }
    else{
        $newStore->updateSupplier($_REQUEST['editid']);
    }
    echo "<script>window.location='../add_store_supplier.php?success=1'</script>";
}

// ======================== ITEM OPERATIONS ========================
if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='store_item')
{
    $item_code = trim($_REQUEST['item_code']);
    $item_name = trim($_REQUEST['item_name']);

    if(preg_match('/[\'\"]/',$item_code) || preg_match('/[\'\"]/',$item_name)) {
        echo "<script>alert('Item Code and Item Name cannot contain single or double quotes.'); window.history.back();</script>";
        exit;
    }

    $newStore->setItemCode($item_code);
    $newStore->setItemName($item_name);
    $newStore->setCategoryId($_REQUEST['category_id']);
    $newStore->setUnitId($_REQUEST['unit_id']);
    $newStore->setItemType($_REQUEST['item_type']);
    $newStore->setMinStock($_REQUEST['min_stock']);
    $newStore->setReorderLevel($_REQUEST['reorder_level']);
    $newStore->setIsExpiryRequired($_REQUEST['is_expiry_required']);
    $newStore->setIsSerialRequired($_REQUEST['is_serial_required']);
    $newStore->setStatus($_REQUEST['status']);
    
    if($_REQUEST['condition']=='new')
    {
        $newStore->insertItem();
    }
    else{
        $newStore->updateItem($_REQUEST['editid']);
    }
    echo "<script>window.location='../add_store_item.php?success=1'</script>";
}

// ======================== PURCHASE ORDER OPERATIONS ========================
if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='store_po')
{
    $newStore->setPoNumber($_REQUEST['po_number']);
    $newStore->setSupplierId($_REQUEST['supplier_id']);
    $newStore->setPoDate($_REQUEST['po_date']);
    $newStore->setExpectedDate($_REQUEST['expected_delivery_date']);
    $newStore->setPaymentTerms($_REQUEST['payment_terms']);
    $newStore->setTotalAmount($_REQUEST['total_amount']);
    $newStore->setRemarks($_REQUEST['remarks']);
    
    // Check if this is create or update
    if(isset($_REQUEST['condition']) && $_REQUEST['condition'] == 'edit') {
        // Update existing PO
        $po_id = $_REQUEST['po_id'];
        $newStore->setPoId($po_id);
        $newStore->updatePurchaseOrder();
        
        // Smart update for PO items - update existing, insert new, delete removed
        $submitted_po_item_ids = []; // Track which existing items are still present
        
        $n = count($_REQUEST['item_id']);
        for($i = 0; $i < $n; $i++) {
            $item_id = $_REQUEST['item_id'][$i];
            $quantity = $_REQUEST['quantity'][$i];
            $rate = $_REQUEST['rate'][$i];
            $gst_percent = isset($_REQUEST['gst_percent'][$i]) ? $_REQUEST['gst_percent'][$i] : 0;
            $po_item_id = isset($_REQUEST['po_item_id'][$i]) && !empty($_REQUEST['po_item_id'][$i]) ? $_REQUEST['po_item_id'][$i] : null;
            
            if($item_id && $quantity > 0) {
                if($po_item_id) {
                    // Update existing item
                    $newStore->updatePurchaseOrderItem($po_item_id, $item_id, $quantity, $rate, $gst_percent);
                    $submitted_po_item_ids[] = $po_item_id;
                } else {
                    // Insert new item
                    $newStore->insertPurchaseOrderItem($po_id, $item_id, $quantity, $rate, 0, $gst_percent);
                }
            }
        }
        
        // Delete items that were removed (not in submitted list)
        if(!empty($submitted_po_item_ids)) {
            $ids_string = implode(',', array_map('intval', $submitted_po_item_ids));
            $existing_items = $newStore->getPOItemsByPOId($po_id);
            while($existing = mysqli_fetch_assoc($existing_items)) {
                if(!in_array($existing['id'], $submitted_po_item_ids)) {
                    $newStore->deletePurchaseOrderItem($existing['id']);
                }
            }
        } else {
            // No items submitted, delete all
            $newStore->deletePurchaseOrderItems($po_id);
        }
        
        echo "<script>window.location='../view_store_po.php?id=$po_id&success=1'</script>";
    } else {
        // Create new PO
        $po_id = $newStore->insertPurchaseOrder();
        
        if($po_id) {
            $n = count($_REQUEST['item_id']);
            for($i = 0; $i < $n; $i++) {
                $item_id = $_REQUEST['item_id'][$i];
                $quantity = $_REQUEST['quantity'][$i];
                $rate = $_REQUEST['rate'][$i];
                $amount = $_REQUEST['amount'][$i];
                $gst_percent = isset($_REQUEST['gst_percent'][$i]) ? $_REQUEST['gst_percent'][$i] : 0;
                
                if($item_id && $quantity > 0) {
                    $newStore->insertPurchaseOrderItem($po_id, $item_id, $quantity, $rate, $amount, $gst_percent);
                }
            }
        }
        echo "<script>window.location='../list_store_po.php?success=1'</script>";
    }
}

// ======================== PURCHASE ORDER APPROVAL ========================
if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='approve_po')
{
    if(!isset($_SESSION['role']) || $_SESSION['role'] != 'Admin') {
        echo "<script>alert('Only Admin can approve Purchase Orders.'); window.location='../list_store_po.php'</script>";
        exit;
    }

    $po_id = isset($_REQUEST['po_id']) ? intval($_REQUEST['po_id']) : 0;
    $po = $newStore->getPurchaseOrderById($po_id);

    if(!$po) {
        echo "<script>alert('Purchase Order not found!'); window.location='../list_store_po.php'</script>";
        exit;
    }

    if($po['status'] != 'PENDING') {
        echo "<script>alert('Only PENDING Purchase Orders can be approved.'); window.location='../view_store_po.php?id=$po_id'</script>";
        exit;
    }

    if($newStore->approvePurchaseOrder($po_id)) {
        echo "<script>alert('Purchase Order approved successfully. GRN can now be created against this PO.'); window.location='../view_store_po.php?id=$po_id&approved=1'</script>";
    } else {
        echo "<script>alert('Unable to approve Purchase Order. Please try again.'); window.location='../view_store_po.php?id=$po_id'</script>";
    }
    exit;
}

// ======================== GRN OPERATIONS ========================
if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='store_grn')
{
    echo "<pre>";
    // print_r($_REQUEST);
    $newStore->setGrnNumber($_REQUEST['grn_number']);
    $newStore->setPoId($_REQUEST['po_id']);
    $newStore->setSupplierId($_REQUEST['supplier_id']);
    $newStore->setReceivedDate($_REQUEST['grn_date']);
    $newStore->setInvoiceNumber($_REQUEST['invoice_number']);
    $newStore->setInvoiceDate($_REQUEST['invoice_date']);
    $newStore->setRemarks($_REQUEST['remarks']);
    $newStore->setTotalAmount(isset($_REQUEST['total_amount']) ? floatval($_REQUEST['total_amount']) : 0);

    $invoice_upload_validation = validateGRNInvoiceUpload(isset($_FILES['invoice_file']) ? $_FILES['invoice_file'] : null);
    if(!$invoice_upload_validation['success'])
    {
        $upload_error = addslashes($invoice_upload_validation['message']);
        echo "<script>alert('$upload_error'); window.history.back();</script>";
        exit;
    }

    $po_id = isset($_REQUEST['po_id']) ? intval($_REQUEST['po_id']) : 0;
    $is_grn_edit = (isset($_REQUEST['condition']) && $_REQUEST['condition'] == 'edit' && !empty($_REQUEST['grn_id']));
    if($po_id > 0)
    {
        $po = $newStore->getPurchaseOrderById($po_id);
        $allowed_statuses = $is_grn_edit ? array('APPROVED', 'PARTIALLY_RECEIVED', 'RECEIVED', 'COMPLETED') : array('APPROVED', 'PARTIALLY_RECEIVED');
        if(!$po || !in_array($po['status'], $allowed_statuses))
        {
            echo "<script>alert('Please approve the Purchase Order before creating GRN against it.'); window.location='../view_store_po.php?id=$po_id'</script>";
            exit;
        }
    }

    if($is_grn_edit)
    {
        $grn_id = intval($_REQUEST['grn_id']);
        $newStore->updateGRNHeader($grn_id);

        $upload_result = saveGRNInvoiceUpload($_REQUEST['grn_number'], isset($_FILES['invoice_file']) ? $_FILES['invoice_file'] : null);
        if(!$upload_result['success']) {
            $upload_message = addslashes($upload_result['message']);
            echo "<script>alert('GRN updated, but invoice upload failed: $upload_message'); window.location='../view_store_grn.php?id=$grn_id'</script>";
            exit;
        }

        echo "<script>window.location='../view_store_grn.php?id=$grn_id&success=1'</script>";
        exit;
    }
    
    $grn_id = $newStore->insertGRN();
   
    if($grn_id)
    {
        $n = count($_REQUEST['item_id']);
        $grn_total_amount = 0;
        // echo $n;
        for($i = 0; $i < $n; $i++)
        {
            $item_id = $_REQUEST['item_id'][$i];
            $batch_no = $_REQUEST['batch_number'][$i] ? $_REQUEST['batch_number'][$i] : null;
            $expiry_date = $_REQUEST['expiry_date'][$i] ? $_REQUEST['expiry_date'][$i] : null;
            $serial_no = $_REQUEST['serial_no'][$i] ? $_REQUEST['serial_no'][$i] : null;
            $quantity = $_REQUEST['received_quantity'][$i];
            $rate = $_REQUEST['rate'][$i];
            $amount = $_REQUEST['rate'][$i]*$_REQUEST['received_quantity'][$i];
            $gst_percent = isset($_REQUEST['gst_percent'][$i]) ? floatval($_REQUEST['gst_percent'][$i]) : 0;
            $gst_amount = isset($_REQUEST['gst_amount'][$i]) ? floatval($_REQUEST['gst_amount'][$i]) : (($amount * $gst_percent) / 100);
            $net_amount = isset($_REQUEST['net_amount'][$i]) ? floatval($_REQUEST['net_amount'][$i]) : ($amount + $gst_amount);
            // echo $item_id."------------".$quantity;
            if($item_id && $quantity > 0)
            {
                $newStore->insertGRNItem($grn_id, $item_id, $batch_no, $expiry_date, $serial_no, $quantity, $rate, $amount, $gst_percent, $gst_amount, $net_amount);
                $grn_total_amount += $amount;
            }
        }

        $newStore->updateGRNTotalAmount($grn_id, $grn_total_amount);
        
        // Update PO status if PO is linked
        if($_REQUEST['po_id'])
        {
            $newStore->updatePurchaseOrderStatus($_REQUEST['po_id'], 'RECEIVED');
        }

        $upload_result = saveGRNInvoiceUpload($_REQUEST['grn_number'], isset($_FILES['invoice_file']) ? $_FILES['invoice_file'] : null);
        if(!$upload_result['success']) {
            $upload_message = addslashes($upload_result['message']);
            echo "<script>alert('GRN saved, but invoice upload failed: $upload_message'); window.location='../view_store_grn.php?id=$grn_id'</script>";
            exit;
        }
    }
    // exit;
    echo "<script>window.location='../list_store_grn.php?success=1'</script>";
}

// ======================== ISSUE OPERATIONS ========================
if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='store_issue')
{
    $newStore->setIssueNumber($_REQUEST['issue_number']);
    $newStore->setIssueDate($_REQUEST['issue_date']);
    $newStore->setDepartment($_REQUEST['department']);
    $newStore->setRequestedBy($_REQUEST['requested_by']);
    $newStore->setRemarks($_REQUEST['remarks']);
    
    $issue_id = $newStore->insertIssue();
    
    if($issue_id)
    {
        $n = $_REQUEST['item_count'];
        for($i = 0; $i < $n; $i++)
        {
            $item_id = $_REQUEST['item_id'][$i];
            $batch_no = $_REQUEST['batch_no'][$i] ? $_REQUEST['batch_no'][$i] : null;
            $serial_no = $_REQUEST['serial_no'][$i] ? $_REQUEST['serial_no'][$i] : null;
            $quantity = $_REQUEST['quantity'][$i];
            
            if($item_id && $quantity > 0)
            {
                $newStore->insertIssueItem($issue_id, $item_id, $batch_no, $serial_no, $quantity);
            }
        }
    }
    echo "<script>window.location='../store_issue_list.php?success=1'</script>";
}
// ======================== SUB STORE OPERATIONS ========================
if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='sub_store')
{
    $newStore->setName($_REQUEST['store_name']);
    $newStore->setContactPerson($_REQUEST['contact_person']);
    $newStore->setPhone($_REQUEST['phone']);
    $newStore->setEmail($_REQUEST['email']);
    $newStore->setAddress($_REQUEST['address']);
    $newStore->setStatus($_REQUEST['status']);
    
    if($_REQUEST['condition']=='new')
    {
        $newStore->insertSubStore();
    }
    else{
        $newStore->updateSubStore($_REQUEST['editid']);
    }
    echo "<script>window.location='../add_sub_store.php?success=1'</script>";
}

// Delete sub store
if(isset($_REQUEST['action']) && $_REQUEST['action']=='delete_sub_store')
{
    $newStore->deleteSubStore($_REQUEST['id']);
    echo "<script>window.location='../add_sub_store.php?success=1'</script>";
}
// ======================== STOCK ADJUSTMENT OPERATIONS ========================
if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='store_adjustment')
{
    $newStore->setAdjustmentNumber($_REQUEST['adjustment_number']);
    $newStore->setItemId($_REQUEST['item_id']);
    $newStore->setBatchNo($_REQUEST['batch_no']);
    $newStore->setAdjustmentQty($_REQUEST['adjustment_qty']);
    $newStore->setAdjustmentType($_REQUEST['adjustment_type']);
    $newStore->setReason($_REQUEST['reason']);
    $newStore->setAdjustmentDate($_REQUEST['adjustment_date']);
    
    $newStore->insertStockAdjustment();
    echo "<script>window.location='../add_store_adjustment.php?success=1'</script>";
}

// ======================== PURCHASE INVOICE OPERATIONS ========================
if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='store_invoice')
{
    $newStore->setInvoiceNumber($_REQUEST['invoice_number']);
    $newStore->setInvoiceDate($_REQUEST['invoice_date']);
    $newStore->setSupplierId($_REQUEST['supplier_id']);
    $newStore->setGrnId($_REQUEST['grn_id'] ? $_REQUEST['grn_id'] : null);
    $newStore->setRemarks($_REQUEST['remarks']);
    
    $total_amount = $_REQUEST['total_amount'];
    $tax_amount = $_REQUEST['tax_amount'];
    $discount_amount = $_REQUEST['discount_amount'];
    $net_amount = $_REQUEST['net_amount'];
    $payment_terms = $_REQUEST['payment_terms'];
    $due_date = $_REQUEST['due_date'];
    
    $invoice_id = $newStore->insertPurchaseInvoice($total_amount, $tax_amount, $discount_amount, $net_amount, $payment_terms, $due_date);
    
    if($invoice_id)
    {
        $n = $_REQUEST['item_count'];
        for($i = 0; $i < $n; $i++)
        {
            $item_id = $_REQUEST['item_id'][$i];
            $batch_no = $_REQUEST['batch_no'][$i] ? $_REQUEST['batch_no'][$i] : null;
            $quantity = $_REQUEST['quantity'][$i];
            $rate = $_REQUEST['rate'][$i];
            $amount = $_REQUEST['amount'][$i];
            $tax_percent = $_REQUEST['tax_percent'][$i];
            $tax_amount_item = $_REQUEST['tax_amount_item'][$i];
            $discount_percent = $_REQUEST['discount_percent'][$i];
            $discount_amount_item = $_REQUEST['discount_amount_item'][$i];
            $net_amount_item = $_REQUEST['net_amount_item'][$i];
            
            if($item_id && $quantity > 0)
            {
                $newStore->insertPurchaseInvoiceItem($invoice_id, $item_id, $batch_no, $quantity, $rate, $amount, $tax_percent, $tax_amount_item, $discount_percent, $discount_amount_item, $net_amount_item);
            }
        }
    }
    echo "<script>window.location='../store_invoice_list.php?success=1'</script>";
}

// ======================== SUPPLIER PAYMENT OPERATIONS ========================
if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='store_payment')
{
    $payment_number = $_REQUEST['payment_number'];
    $payment_date = $_REQUEST['payment_date'];
    $supplier_id = $_REQUEST['supplier_id'];
    $payment_mode = $_REQUEST['payment_mode'];
    $payment_amount = $_REQUEST['payment_amount'];
    $reference_number = $_REQUEST['reference_number'];
    $bank_name = $_REQUEST['bank_name'];
    $remarks = $_REQUEST['remarks'];
    
    $payment_id = $newStore->insertSupplierPayment($payment_number, $payment_date, $supplier_id, $payment_mode, $payment_amount, $reference_number, $bank_name, $remarks);
    
    if($payment_id)
    {
        // Allocate payment to invoices
        if(isset($_REQUEST['invoice_ids']) && is_array($_REQUEST['invoice_ids']))
        {
            foreach($_REQUEST['invoice_ids'] as $key => $invoice_id)
            {
                $allocated_amount = $_REQUEST['allocated_amounts'][$key];
                
                if($allocated_amount > 0)
                {
                    // Insert allocation
                    $newStore->insertPaymentAllocation($payment_id, $invoice_id, $allocated_amount);
                    
                    // Update invoice payment status
                    $newStore->updateInvoicePayment($invoice_id, $allocated_amount);
                }
            }
        }
    }
    echo "<script>window.location='../store_payment_list.php?success=1'</script>";
}

// ======================== STORE ISSUE OPERATIONS ========================
if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='store_issue')
{
    $newStore->setIssueNumber($_REQUEST['issue_number']);
    $newStore->setIssueDate($_REQUEST['issue_date']);
    $newStore->setDepartment($_REQUEST['department']);
    $newStore->setRequestedBy($_REQUEST['requested_by']);
    $newStore->setRemarks($_REQUEST['remarks']);
    
    $issue_id = $newStore->insertIssue();
    
    if($issue_id)
    {
        $item_count = $_REQUEST['item_count'];
        for($i=0; $i<$item_count; $i++)
        {
            $item_id = $_REQUEST['item_id'][$i];
            $batch_no = $_REQUEST['batch_no'][$i];
            $serial_no = $_REQUEST['serial_no'][$i];
            $quantity = $_REQUEST['quantity'][$i];
            
            if($item_id && $quantity > 0)
            {
                $newStore->insertIssueItem($issue_id, $item_id, $batch_no, $serial_no, $quantity);
            }
        }
    }
    echo "<script>window.location='../store_issue_list.php?success=1'</script>";
}
// ======================== CANCEL PURCHASE ORDER ========================
if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='cancel_po')
{
    header('Content-Type: application/json; charset=utf-8');
    
    $po_id = isset($_REQUEST['po_id']) ? intval($_REQUEST['po_id']) : 0;
    
    if($po_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid PO ID']);
        exit;
    }
    
    $result = $newStore->cancelPurchaseOrder($po_id);
    echo json_encode($result);
    exit;
}
?>