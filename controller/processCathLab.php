<?php
require_once(__DIR__ . '/../model/session_config.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set("Asia/Kolkata");

include('../model/cathLabClass.php');

$action = isset($_POST['action']) ? $_POST['action'] : '';
$cathLab = new cathLab;

// ===== SAVE CATHLAB GRN =====
if($action == 'saveCathLabGRN') {
    $grn_number = $_POST['grn_number'];
    $grn_date = $_POST['grn_date'];
    $supplier_id = $_POST['supplier_id'];
    $po_id = isset($_POST['po_id']) ? $_POST['po_id'] : 0;
    $invoice_number = isset($_POST['invoice_number']) ? $_POST['invoice_number'] : '';
    $invoice_date = isset($_POST['invoice_date']) ? $_POST['invoice_date'] : null;
    $remarks = isset($_POST['remarks']) ? $_POST['remarks'] : '';
    $received_by = isset($_POST['received_by']) ? $_POST['received_by'] : $_SESSION['loginid'];

    // Get supplier name using cathLabClass method
    $supplier_name = $cathLab->getSupplierNameById($supplier_id);

    // Begin transaction
    echo "$grn_number, $grn_date, $supplier_id, $supplier_name, $po_id, $invoice_number, $invoice_date, $remarks, $received_by";
    try {
        // Set GRN properties
        $cathLab->setGrnNumber($grn_number);
        $cathLab->setGrnDate($grn_date);
        $cathLab->setSupplierId($supplier_id);
        $cathLab->setSupplierName($supplier_name);
        $cathLab->setPoId($po_id);
        $cathLab->setInvoiceNumber($invoice_number);
        $cathLab->setInvoiceDate($invoice_date);
        $cathLab->setRemarks($remarks);
        $cathLab->setReceivedBy($received_by);
        
        // Calculate totals from items
        $total_items = 0;
        $total_quantity = 0;
        $total_cost = 0;
        
        if(isset($_POST['grn_items'])) {
            foreach($_POST['grn_items'] as $item) {
                $total_items++;
                $total_quantity += floatval($item['quantity']);
                $total_cost += (floatval($item['quantity']) * floatval($item['rate']));
            }
        }
        
        // Set totals
        $cathLab->setTotalItems($total_items);
        $cathLab->setTotalQuantity($total_quantity);
        $cathLab->setTotalCost($total_cost);
        
        // Insert GRN header
        $grn_id = $cathLab->insertCathLabGRN();
        
        // Insert GRN Items
        if(isset($_POST['grn_items'])) {
            foreach($_POST['grn_items'] as $item) {
                $item_id = $item['item_id'];
                $quantity = $item['quantity'];
                $rate = $item['rate'];
                $batch_no = isset($item['batch_no']) ? $item['batch_no'] : '';
                $expiry_date = isset($item['expiry_date']) ? $item['expiry_date'] : null;

                // Insert GRN item
                $cathLab->insertGrnItem($grn_id, $item_id, $quantity, $rate, $batch_no, $expiry_date);
                
                // Insert batch master record for batch-wise tracking
                $batch_id = $cathLab->insertBatchMaster(
                    $item_id, 
                    $batch_no, 
                    $expiry_date, 
                    $quantity, 
                    $rate, 
                    $supplier_name,
                    $invoice_number
                );
                
                // Update stock
                $cathLab->updateStockFromGrn($item_id, $quantity);
                
                // Create stock ledger entry
                $cathLab->createStockLedger($item_id, 'GRN_IN', $quantity, 'GRN', $grn_id, 'Goods Received - GRN ' . $grn_number);
            }
        }

    

        // Log activity
        $cathLab->logActivity($_SESSION['loginid'], 'CATHLAB_STORE', 'GRN_CREATED', 'CathLab GRN ' . $grn_number . ' created with ' . count($_POST['grn_items']) . ' items');

        $_SESSION['success_message'] = "CathLab GRN " . $grn_number . " created successfully!";
        header('Location: ../view_cathlab_grn.php');
        exit();

    } catch (Exception $e) {
        $_SESSION['error_message'] = "Error: " . $e->getMessage();
        header('Location: ../add_cathlab_grn.php');
        exit();
    }
}

// ===== SAVE CATHLAB PROCEDURE =====
else if($action == 'saveCathLabProcedure') {
    $procedure_code = $_POST['procedure_code'];
    $procedure_type = $_POST['procedure_type'];
    $procedure_date = $_POST['procedure_date'];
    $procedure_time = isset($_POST['procedure_time']) ? $_POST['procedure_time'] : '';
    $doctor_name = $_POST['doctor_name'];
    $admission_id = $_POST['admission_id'];
    $status = isset($_POST['status']) ? $_POST['status'] : 'completed';
    $observations = isset($_POST['observations']) ? $_POST['observations'] : '';
    $duration_minutes = isset($_POST['duration_minutes']) ? intval($_POST['duration_minutes']) : 0;
    $items = isset($_POST['items']) ? $_POST['items'] : array();

    try {
        // Insert procedure header
        $procedure_id = $cathLab->insertCathLabProcedure($procedure_code, $procedure_type, $procedure_date, $procedure_time, $doctor_name, $admission_id, $status, $observations, $duration_minutes);

        // For each item, insert procedure item, deduct stock, update batch, and log ledger
        foreach($items as $item) {
            $item_id = $item['item_id'];
            $batch_id = $item['batch_id'];
            $quantity = floatval($item['quantity']);
            $cost_price = floatval($item['cost_price']);

            // Insert procedure item
            $cathLab->insertCathLabProcedureItem($procedure_id, $item_id, $batch_id, $quantity, $cost_price);

            // Deduct stock from item master
            $cathLab->deductStockForProcedure($item_id, $quantity);

            // Deduct stock from batch
            $cathLab->deductBatchStock($batch_id, $quantity);

            // Log stock ledger entry
            $cathLab->createStockLedger($item_id, 'PROCEDURE_CONSUME', $quantity, 'PROCEDURE', $procedure_id, 'Item consumed in procedure ' . $procedure_code);
        }

        // Log activity
        $cathLab->logActivity($_SESSION['loginid'], 'CATHLAB_STORE', 'PROCEDURE_CREATED', 'CathLab Procedure ' . $procedure_code . ' created with ' . count($items) . ' items consumed');

        $_SESSION['success_message'] = 'CathLab Procedure ' . $procedure_code . ' saved successfully! Stock has been deducted.';
        header('Location: ../cathlab_procedure.php');
        exit();

    } catch (Exception $e) {
        $_SESSION['error_message'] = 'Error: ' . $e->getMessage();
        header('Location: ../cathlab_procedure.php');
        exit();
    }
}

// Add CathLab Item
else if($action == 'addCathLabItem') {
    try {
        $cathLab->setItemCode($_POST['item_code']);
        $cathLab->setItemName($_POST['item_name']);
        $cathLab->setItemType($_POST['item_type']);
        $cathLab->setCategoryId($_POST['category_id']);
        $cathLab->setUnitId($_POST['unit_id']);
        $cathLab->setUnitCost($_POST['unit_cost']);
        $cathLab->setBillingPrice($_POST['billing_price']);
        $cathLab->setMinStock($_POST['min_stock']);
        $cathLab->setReorderLevel($_POST['reorder_level']);
        $cathLab->setIsExpiryRequired(isset($_POST['is_expiry_required']) ? 1 : 0);
        $cathLab->setIsSerialRequired(isset($_POST['is_serial_required']) ? 1 : 0);
        $cathLab->setDescription(isset($_POST['description']) ? $_POST['description'] : '');
        
        $item_id = $cathLab->insertCathLabItem();
        
        // Log activity
        $cathLab->logActivity($_SESSION['loginid'], 'CATHLAB_STORE', 'ITEM_CREATED', 'CathLab Item \'' . $_POST['item_name'] . '\' (' . $_POST['item_code'] . ') created');

        $_SESSION['success_message'] = "CathLab item '" . $_POST['item_name'] . "' added successfully!";
        header('Location: ../add_cathlab_store_item.php');
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Error adding item: " . $e->getMessage();
        header('Location: ../add_cathlab_store_item.php');
    }
    exit();
}

// Delete CathLab Item
else if($action == 'deleteCathLabItem') {
    try {
        $item_id = $_POST['item_id'];
        $cathLab->deleteCathLabItem($item_id);
        
        $cathLab->logActivity($_SESSION['loginid'], 'CATHLAB_STORE', 'ITEM_DELETED', 'CathLab Item ID ' . $item_id . ' deleted');
        
        echo json_encode(['success' => true, 'message' => 'Item deleted successfully']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit();
}

// Bulk Import CathLab Items
else if($action == 'bulkImportCathLabItems') {
    try {
        if(!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] != 0) {
            throw new Exception("Please select a valid CSV file");
        }

        $csv_file = $_FILES['csv_file']['tmp_name'];
        $result = $cathLab->bulkImportItems($csv_file);
        
        $cathLab->logActivity($_SESSION['loginid'], 'CATHLAB_STORE', 'BULK_IMPORT', 'Imported ' . $result['imported'] . ' items');

        $_SESSION['success_message'] = "Imported " . $result['imported'] . " items successfully! (" . $result['errors'] . " errors)";
        header('Location: ../add_cathlab_store_item.php');
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Error: " . $e->getMessage();
        header('Location: ../add_cathlab_store_item.php');
    }
    exit();
}

else if($action == 'updateCathLabItem') {
    try {
        $item_id = $_POST['item_id'];
        $cathLab->setItemCode($_POST['item_code']);
        $cathLab->setItemName($_POST['item_name']);
        $cathLab->setItemType($_POST['item_type']);
        $cathLab->setCategoryId($_POST['category_id']);
        $cathLab->setUnitId($_POST['unit_id']);
        $cathLab->setUnitCost($_POST['unit_cost']);
        $cathLab->setBillingPrice($_POST['billing_price']);
        $cathLab->setMinStock($_POST['min_stock']);
        $cathLab->setReorderLevel($_POST['reorder_level']);
        $cathLab->setIsExpiryRequired(isset($_POST['is_expiry_required']) ? 1 : 0);
        $cathLab->setIsSerialRequired(isset($_POST['is_serial_required']) ? 1 : 0);
        $cathLab->setDescription(isset($_POST['description']) ? $_POST['description'] : '');
        
        $cathLab->updateCathLabItem($item_id);
        
        // Log activity
        $cathLab->logActivity($_SESSION['loginid'], 'CATHLAB_STORE', 'ITEM_UPDATED', 'CathLab Item ID ' . $item_id . ' updated');

        $_SESSION['success_message'] = "CathLab item updated successfully!";
        header('Location: ../view_cathlab_item.php?id=' . $item_id);
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Error updating item: " . $e->getMessage();
        header('Location: ../edit_cathlab_item.php?id=' . $item_id);
    }
    exit();   
}

else {
    $_SESSION['error_message'] = "Invalid action";
    header('Location: ../cathlab_store_dashboard.php');
    exit();
}

?>
