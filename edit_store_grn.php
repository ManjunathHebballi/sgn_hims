<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");
include('model/storeClass.php');
$newStore = new store;

if(!isset($_REQUEST['id']) || empty($_REQUEST['id'])) {
    echo "<script>window.location='list_store_grn.php'</script>";
    exit();
}

$grn_id = intval($_REQUEST['id']);
$grn = $newStore->getGRNById($grn_id);

if(!$grn) {
    echo "<script>alert('GRN not found!'); window.location='list_store_grn.php'</script>";
    exit();
}

$grn_items = $newStore->getGRNItemsByGRNId($grn_id);
$current_po = null;
if(!empty($grn['po_id'])) {
    $current_po = $newStore->getPurchaseOrderById($grn['po_id']);
}

$invoice_file_url = '';
if(!empty($grn['grn_number'])) {
    $invoice_pattern = __DIR__ . '/uploads/store_grn_invoices/' . preg_replace('/[^A-Za-z0-9_-]/', '_', $grn['grn_number']) . '.*';
    $invoice_matches = glob($invoice_pattern);
    if(!empty($invoice_matches)) {
        $invoice_file_url = 'uploads/store_grn_invoices/' . basename($invoice_matches[0]);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#319197">

    <title>Edit GRN - <?= $grn['grn_number']?></title>
    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon.png">
    <link href="css/style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
</head>

<body>

    <div id="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>

    <div id="main-wrapper">

        <?php include ('leftbar.php')?>

        <div class="content-body">

            <div class="container-fluid">
                <div class="row mb-3">
                    <div class="col-md-8">
                        <h4 class="text-uppercase">✏️ Edit Goods Receipt Note (<?= $grn['grn_number']?>)</h4>
                    </div>
                    <div class="col-md-4 text-right">
                        <a href="view_store_grn.php?id=<?= $grn['id']?>" class="btn btn-secondary btn-sm">
                            <i class="fa fa-arrow-left"></i> Back to GRN
                        </a>
                    </div>
                </div>

                <form action="controller/processStore.php" method='POST' enctype="multipart/form-data" id="grnEditForm">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h5>GRN Details</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>GRN Number</label>
                                                <input type="text" class="form-control" name="grn_number"
                                                    value="<?= $grn['grn_number']?>" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>GRN Date <span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" name="grn_date"
                                                    value="<?= $grn['received_date'] ? date('Y-m-d', strtotime($grn['received_date'])) : ''?>" required>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Purchase Order</label>
                                                <select class="form-control" name="po_id" id="po_id">
                                                    <option value="">Select PO (Optional)</option>
                                                    <?php 
                                                    $pos = $newStore->getPendingPurchaseOrders();
                                                    $currentPoPresent = false;
                                                    while($po = mysqli_fetch_assoc($pos)) {
                                                        $selected = ($grn['po_id'] == $po['id']) ? 'selected' : '';
                                                        if($selected) { $currentPoPresent = true; }
                                                        echo "<option value='{$po['id']}' {$selected}>{$po['po_number']} - {$po['supplier_name']}</option>";
                                                    }

                                                    if(!$currentPoPresent && $current_po) {
                                                        echo "<option value='{$current_po['id']}' selected>{$current_po['po_number']} - {$current_po['supplier_name']}</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Supplier <span class="text-danger">*</span></label>
                                                <select class="form-control" name="supplier_id" id="supplier_id"
                                                    required>
                                                    <option value="">Select Supplier</option>
                                                    <?php 
                                                    $suppliers = $newStore->getActiveSuppliers();
                                                    while($sup = mysqli_fetch_assoc($suppliers)) {
                                                        $selected = ($sup['id'] == $grn['supplier_id']) ? 'selected' : '';
                                                        echo "<option value='{$sup['id']}' {$selected}>{$sup['supplier_name']}</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Invoice Number</label>
                                                <input type="text" class="form-control" name="invoice_number"
                                                    value="<?= htmlspecialchars($grn['invoice_number'])?>"
                                                    placeholder="Supplier Invoice No">
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Invoice Date</label>
                                                <input type="date" class="form-control" name="invoice_date"
                                                    value="<?= $grn['invoice_date'] ? date('Y-m-d', strtotime($grn['invoice_date'])) : ''?>">
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Invoice Upload</label>
                                                <input type="file" class="form-control" name="invoice_file" id="invoice_file"
                                                    accept=".pdf,.jpg,.jpeg,.png">
                                                <small class="text-muted">PDF, JPG, PNG up to 5MB</small>
                                                <?php if($invoice_file_url): ?>
                                                <div class="mt-1">
                                                    <a href="<?= $invoice_file_url?>" target="_blank" class="text-primary">
                                                        <i class="fa fa-paperclip"></i> View current invoice
                                                    </a>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Remarks</label>
                                                <input type="text" class="form-control" name="remarks"
                                                    value="<?= htmlspecialchars($grn['remarks'])?>"
                                                    placeholder="Any remarks">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="alert alert-info mb-0">
                                        Item quantities and rates are locked in edit mode to preserve stock ledger accuracy. GST details are shown for reference.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">GRN Items (Read Only)</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Item</th>
                                                    <th>Unit</th>
                                                    <th class="text-right">Qty</th>
                                                    <th class="text-right">Rate</th>
                                                    <th class="text-right">Amount</th>
                                                    <th class="text-right">GST %</th>
                                                    <th class="text-right">GST Amt</th>
                                                    <th class="text-right">Net Amt</th>
                                                    <th>Batch</th>
                                                    <th>Expiry</th>
                                                    <th>Serial</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                $total_amount = 0;
                                                $total_gst_amount = 0;
                                                $total_net_amount = 0;
                                                while($item = mysqli_fetch_assoc($grn_items)):
                                                    $item_amount = isset($item['amount']) ? (float)$item['amount'] : 0;
                                                    $item_gst_percent = isset($item['gst_percent']) ? (float)$item['gst_percent'] : 0;
                                                    $item_gst_amount = isset($item['gst_amount']) ? (float)$item['gst_amount'] : 0;
                                                    $item_net_amount = isset($item['net_amount']) && $item['net_amount'] !== null ? (float)$item['net_amount'] : ($item_amount + $item_gst_amount);
                                                    $total_amount += $item_amount;
                                                    $total_gst_amount += $item_gst_amount;
                                                    $total_net_amount += $item_net_amount;
                                                ?>
                                                <tr>
                                                    <td><?= $item['item_name']?></td>
                                                    <td><?= $item['unit_name']?></td>
                                                    <td class="text-right"><?= number_format($item['quantity'], 2)?></td>
                                                    <td class="text-right">₹<?= number_format($item['rate'], 2)?></td>
                                                    <td class="text-right">₹<?= number_format($item_amount, 2)?></td>
                                                    <td class="text-right"><?= number_format($item_gst_percent, 2)?>%</td>
                                                    <td class="text-right">₹<?= number_format($item_gst_amount, 2)?></td>
                                                    <td class="text-right">₹<?= number_format($item_net_amount, 2)?></td>
                                                    <td><?= $item['batch_no'] ? $item['batch_no'] : '-'?></td>
                                                    <td><?= $item['expiry_date'] ? date('d-M-Y', strtotime($item['expiry_date'])) : '-'?></td>
                                                    <td><?= $item['serial_no'] ? $item['serial_no'] : '-'?></td>
                                                </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-4 ml-auto">
                                            <table class="table table-sm table-bordered mb-0">
                                                <tr>
                                                    <th>Total Amount</th>
                                                    <td class="text-right">₹<?= number_format($total_amount, 2)?></td>
                                                </tr>
                                                <tr>
                                                    <th>Total GST</th>
                                                    <td class="text-right">₹<?= number_format($total_gst_amount, 2)?></td>
                                                </tr>
                                                <tr>
                                                    <th>Total Net Amount</th>
                                                    <td class="text-right font-weight-bold">₹<?= number_format($total_net_amount, 2)?></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 text-right">
                            <button type="submit" class="btn btn-success" name="submit" value="store_grn">
                                <i class="fa fa-save"></i> Update GRN
                            </button>
                        </div>
                    </div>

                    <input type="hidden" name="condition" value="edit">
                    <input type="hidden" name="grn_id" value="<?= $grn['id']?>">
                    <input type="hidden" name="total_amount" value="<?= number_format($total_amount, 2, '.', '')?>">

                </form>

            </div>

        </div>

        <?php include('footer.php') ?>

    </div>

    <script src="vendor/global/global.min.js"></script>
    <script src="js/quixnav-init.js"></script>
    <script src="js/custom.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#supplier_id, #po_id').select2({
            width: '100%'
        });
    });
    </script>

</body>

</html>
