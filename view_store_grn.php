<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/storeClass.php');
$newStore = new store;

if(!isset($_REQUEST['id'])) {
    echo "<script>window.location='list_store_grn.php'</script>";
    exit();
}

$grn_id = $_REQUEST['id'];
$grn = $newStore->getGRNById($grn_id);

if(!$grn) {
    echo "<script>alert('GRN not found!'); window.location='list_store_grn.php'</script>";
    exit();
}

$grn_items = $newStore->getGRNItemsByGRNId($grn_id);

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

    <title>View GRN - <?= $grn['grn_number']?></title>
    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon.png">
    <link href="css/style.css" rel="stylesheet">

    <style>
    .grn-header {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
    }

    .info-card {
        border: 1px solid #e3e6f0;
        border-radius: 8px;
        padding: 20px;
        height: 100%;
        background: #f8f9fc;
    }

    .info-card h5 {
        color: #11998e;
        font-weight: bold;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid #11998e;
    }

    .info-label {
        font-weight: 600;
        color: #495057;
        display: inline-block;
        width: 150px;
    }

    .grn-items-table thead {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white;
    }

    .grn-items-table thead th {
        font-weight: 600;
        border: none;
        padding: 12px 8px;
    }

    .summary-box {
        background: #f8f9fc;
        border: 2px solid #e3e6f0;
        border-radius: 8px;
        padding: 20px;
        margin-top: 20px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        font-size: 14px;
    }

    .summary-row.total {
        border-top: 2px solid #11998e;
        padding-top: 12px;
        margin-top: 10px;
        font-size: 18px;
        font-weight: bold;
        color: #11998e;
    }

    .badge-completed {
        background-color: #28a745;
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
    }
    </style>

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

                <!-- Header -->
                <div class="grn-header">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="mb-1">📦 Goods Receipt Note</h4>
                            <h3><?= $grn['grn_number']?></h3>
                        </div>
                        <div class="col-md-6 text-right">
                            <span class="badge-completed">RECEIVED</span>
                            <br><br>
                            <a href="list_store_grn.php" class="btn btn-info btn-sm">
                                <i class="fa fa-arrow-left"></i> Back to List
                            </a>
                            <a href="edit_store_grn.php?id=<?= $grn['id']?>" class="btn btn-warning btn-sm">
                                <i class="fa fa-edit"></i> Edit GRN
                            </a>
                            <a href="print_store_grn.php?id=<?= $grn['id']?>" target="_blank"
                                class="btn btn-light btn-sm">
                                <i class="fa fa-print"></i> Print GRN
                            </a>
                        </div>
                    </div>
                </div>

                <!-- GRN Details -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-card">
                            <h5><i class="fa fa-info-circle"></i> GRN INFORMATION</h5>
                            <p><span class="info-label">GRN Number:</span> <?= $grn['grn_number']?></p>
                            <p><span class="info-label">GRN Date:</span>
                                <?= date('d-M-Y', strtotime($grn['received_date']))?></p>
                            <?php if($grn['po_number']): ?>
                            <p><span class="info-label">PO Reference:</span> 
                                <a href="view_store_po.php?id=<?= $grn['po_id']?>"><?= $grn['po_number']?></a>
                            </p>
                            <?php endif; ?>
                            <?php if($grn['invoice_number']): ?>
                            <p><span class="info-label">Invoice Number:</span> <?= $grn['invoice_number']?></p>
                            <?php endif; ?>
                            <?php if($grn['invoice_date']): ?>
                            <p><span class="info-label">Invoice Date:</span>
                                <?= date('d-M-Y', strtotime($grn['invoice_date']))?></p>
                            <?php endif; ?>
                            <?php if($invoice_file_url): ?>
                            <p><span class="info-label">Invoice File:</span>
                                <a href="<?= $invoice_file_url?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fa fa-paperclip"></i> View Invoice
                                </a>
                            </p>
                            <?php endif; ?>
                            <?php if($grn['remarks']): ?>
                            <p><span class="info-label">Remarks:</span> <?= $grn['remarks']?></p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-card">
                            <h5><i class="fa fa-building"></i> SUPPLIER DETAILS</h5>
                            <p><span class="info-label">Supplier Name:</span> <?= $grn['supplier_name']?></p>
                            <?php if($grn['phone']): ?>
                            <p><span class="info-label">Phone:</span> <?= $grn['phone']?></p>
                            <?php endif; ?>
                            <?php if($grn['email']): ?>
                            <p><span class="info-label">Email:</span> <?= $grn['email']?></p>
                            <?php endif; ?>
                            <?php if($grn['address']): ?>
                            <p><span class="info-label">Address:</span> <?= $grn['address']?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- GRN Items -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white;">
                                <h5 class="mb-0"><i class="fa fa-boxes"></i> RECEIVED ITEMS</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered grn-items-table">
                                        <thead>
                                            <tr>
                                                <th width="5%">S.No</th>
                                                <th width="25%">Item Name</th>
                                                <th width="10%">Unit</th>
                                                <th width="10%">Quantity</th>
                                                <th width="10%">Rate</th>
                                                <th width="10%">Amount</th>
                                                <th width="6%">GST %</th>
                                                <th width="10%">GST Amt</th>
                                                <th width="10%">Net Amt</th>
                                                <th width="7%">Batch No</th>
                                                <th width="7%">Expiry Date</th>
                                                <th width="7%">Serial No</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $sno = 1;
                                            $total_qty = 0;
                                            $total_amount = 0;
                                            $total_gst_amount = 0;
                                            $total_net_amount = 0;
                                            while($item = mysqli_fetch_assoc($grn_items)) {
                                                $total_qty += $item['quantity'];
                                                $item_amount = isset($item['amount']) ? (float)$item['amount'] : 0;
                                                $item_gst_percent = isset($item['gst_percent']) ? (float)$item['gst_percent'] : 0;
                                                $item_gst_amount = isset($item['gst_amount']) ? (float)$item['gst_amount'] : 0;
                                                $item_net_amount = isset($item['net_amount']) && $item['net_amount'] !== null ? (float)$item['net_amount'] : ($item_amount + $item_gst_amount);
                                                $total_amount += $item_amount;
                                                $total_gst_amount += $item_gst_amount;
                                                $total_net_amount += $item_net_amount;
                                            ?>
                                            <tr>
                                                <td><?= $sno++?></td>
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
                                            <?php } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr style="background-color: #f8f9fa; font-weight: bold;">
                                                <td colspan="3" class="text-right">TOTAL:</td>
                                                <td class="text-right"><?= number_format($total_qty, 2)?></td>
                                                <td></td>
                                                <td class="text-right">₹<?= number_format($total_amount, 2)?></td>
                                                <td></td>
                                                <td class="text-right">₹<?= number_format($total_gst_amount, 2)?></td>
                                                <td class="text-right">₹<?= number_format($total_net_amount, 2)?></td>
                                                <td colspan="3"></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Summary -->
                <div class="row">
                    <div class="col-md-6 ml-auto">
                        <div class="summary-box">
                            <div class="summary-row">
                                <span>TOTAL AMOUNT:</span>
                                <span>₹<?= number_format($total_amount, 2)?></span>
                            </div>
                            <div class="summary-row">
                                <span>TOTAL GST:</span>
                                <span>₹<?= number_format($total_gst_amount, 2)?></span>
                            </div>
                            <div class="summary-row total">
                                <span>TOTAL NET AMOUNT:</span>
                                <span>₹<?= number_format($total_net_amount, 2)?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Info -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <small>
                                <strong>Created By:</strong> <?= $_SESSION['name']?> | 
                                <strong>Created On:</strong> <?= date('d-M-Y h:i A', strtotime($grn['created_at']))?>
                                <?php if($grn['updated_at']): ?>
                                | <strong>Last Updated:</strong> <?= date('d-M-Y h:i A', strtotime($grn['updated_at']))?>
                                <?php endif; ?>
                            </small>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <?php include('footer.php') ?>

    </div>

    <script src="vendor/global/global.min.js"></script>
    <script src="js/quixnav-init.js"></script>
    <script src="js/custom.min.js"></script>

</body>

</html>
