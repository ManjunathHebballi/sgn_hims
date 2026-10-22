<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");
include('model/storeClass.php');
$newStore = new store;

$from_date = isset($_GET['from_date']) && $_GET['from_date'] ? $_GET['from_date'] : date('Y-m-01');
$to_date = isset($_GET['to_date']) && $_GET['to_date'] ? $_GET['to_date'] : date('Y-m-d');
$report_type = isset($_GET['report_type']) && $_GET['report_type'] ? $_GET['report_type'] : 'purchase_summary';
$item_id = isset($_GET['item_id']) ? intval($_GET['item_id']) : 0;
$supplier_id = isset($_GET['supplier_id']) ? intval($_GET['supplier_id']) : 0;
$department = isset($_GET['department']) ? trim($_GET['department']) : '';

$report_titles = [
    'purchase_item_wise' => 'Item Wise Purchase Report',
    'supplier_wise_purchase' => 'Supplier Wise Purchase Report',
    'item_wise_issue' => 'Item Wise Issue Report',
    'issue_summary' => 'Issue Summary Report',
    'issue_detailed' => 'Issue Detailed Report',
    'purchase_summary' => 'Purchase Summary Report',
    'purchase_detailed' => 'Purchase Detailed Report'
];

$report_title = isset($report_titles[$report_type]) ? $report_titles[$report_type] : 'Store Transaction Report';

switch($report_type) {
    case 'purchase_item_wise':
        $reportData = $newStore->getPurchaseItemWiseReport($from_date, $to_date, $item_id, $supplier_id);
        break;
    case 'supplier_wise_purchase':
        $reportData = $newStore->getPurchaseSupplierWiseReport($from_date, $to_date, $supplier_id);
        break;
    case 'item_wise_issue':
        $reportData = $newStore->getIssueItemWiseReport($from_date, $to_date, $item_id, $department);
        break;
    case 'issue_summary':
        $reportData = $newStore->getIssueSummaryReport($from_date, $to_date, $department);
        break;
    case 'issue_detailed':
        $reportData = $newStore->getIssueDetailedReport($from_date, $to_date, $item_id, $department);
        break;
    case 'purchase_detailed':
        $reportData = $newStore->getPurchaseDetailedReport($from_date, $to_date, $item_id, $supplier_id);
        break;
    case 'purchase_summary':
    default:
        $reportData = $newStore->getPurchaseSummaryReport($from_date, $to_date, $supplier_id);
        break;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#319197">
    <title>Store Transaction Reports</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon.png">
    <link href="css/style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <style>
        .report-card { cursor: pointer; transition: all 0.3s ease; }
        .report-card:hover { transform: translateY(-3px); box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
        .report-card.active-card { border: 2px solid #11998e; background: #f1fffb; }
        .report-box { background: #fff; border-radius: 8px; }
        .filter-group { display: none; }
        @media print {
            .no-print, #preloader, .nav-header, .header, .quixnav, .footer, .deznav, .dlabnav, .left-sidebar { display: none !important; }
            .content-body, .container-fluid, .report-box { margin: 0 !important; padding: 0 !important; }
            body { background: #fff !important; }
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
                <div class="row mb-3">
                    <div class="col-md-8">
                        <h4 class="text-uppercase">📊 Store Purchase & Issue Reports</h4>
                    </div>
                    <div class="col-md-4 text-right no-print">
                        <button type="button" class="btn btn-primary btn-sm" onclick="printReport()">
                            <i class="fa fa-print"></i> Print Report
                        </button>
                    </div>
                </div>

                <div class="row no-print">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-3 mb-3"><div class="card report-card" data-type="purchase_item_wise" onclick="setReportType('purchase_item_wise')"><div class="card-body text-center"><i class="fa fa-cubes fa-2x text-primary mb-2"></i><h6>Item Wise Purchase</h6></div></div></div>
                            <div class="col-md-3 mb-3"><div class="card report-card" data-type="supplier_wise_purchase" onclick="setReportType('supplier_wise_purchase')"><div class="card-body text-center"><i class="fa fa-truck fa-2x text-success mb-2"></i><h6>Supplier Wise Purchase</h6></div></div></div>
                            <div class="col-md-3 mb-3"><div class="card report-card" data-type="item_wise_issue" onclick="setReportType('item_wise_issue')"><div class="card-body text-center"><i class="fa fa-share-square fa-2x text-warning mb-2"></i><h6>Item Wise Issue</h6></div></div></div>
                            <div class="col-md-3 mb-3"><div class="card report-card" data-type="issue_summary" onclick="setReportType('issue_summary')"><div class="card-body text-center"><i class="fa fa-list-alt fa-2x text-info mb-2"></i><h6>Issue Summary</h6></div></div></div>
                            <div class="col-md-3 mb-3"><div class="card report-card" data-type="issue_detailed" onclick="setReportType('issue_detailed')"><div class="card-body text-center"><i class="fa fa-file-text-o fa-2x text-danger mb-2"></i><h6>Issue Detailed</h6></div></div></div>
                            <div class="col-md-3 mb-3"><div class="card report-card" data-type="purchase_summary" onclick="setReportType('purchase_summary')"><div class="card-body text-center"><i class="fa fa-shopping-bag fa-2x text-secondary mb-2"></i><h6>Purchase Summary</h6></div></div></div>
                            <div class="col-md-3 mb-3"><div class="card report-card" data-type="purchase_detailed" onclick="setReportType('purchase_detailed')"><div class="card-body text-center"><i class="fa fa-table fa-2x text-dark mb-2"></i><h6>Purchase Detailed</h6></div></div></div>
                        </div>
                    </div>
                </div>

                <div class="card no-print">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Report Filters</h5>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Report Type</label>
                                        <select name="report_type" id="report_type" class="form-control" onchange="toggleFilters()">
                                            <?php foreach($report_titles as $key => $label): ?>
                                            <option value="<?= $key?>" <?= $report_type == $key ? 'selected' : '' ?>><?= $label?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>From Date</label>
                                        <input type="date" name="from_date" class="form-control" value="<?= $from_date?>">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>To Date</label>
                                        <input type="date" name="to_date" class="form-control" value="<?= $to_date?>">
                                    </div>
                                </div>
                                <div class="col-md-3 filter-group" id="item_filter_group">
                                    <div class="form-group">
                                        <label>Item</label>
                                        <select name="item_id" id="item_id" class="form-control">
                                            <option value="0">All Items</option>
                                            <?php 
                                            $items = $newStore->getActiveItems();
                                            while($item = mysqli_fetch_assoc($items)) {
                                                $selected = $item_id == $item['id'] ? 'selected' : '';
                                                echo "<option value='{$item['id']}' {$selected}>{$item['item_name']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 filter-group" id="supplier_filter_group">
                                    <div class="form-group">
                                        <label>Supplier</label>
                                        <select name="supplier_id" id="supplier_id" class="form-control">
                                            <option value="0">All Suppliers</option>
                                            <?php 
                                            $suppliers = $newStore->getActiveSuppliers();
                                            while($supplier = mysqli_fetch_assoc($suppliers)) {
                                                $selected = $supplier_id == $supplier['id'] ? 'selected' : '';
                                                echo "<option value='{$supplier['id']}' {$selected}>{$supplier['supplier_name']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 filter-group" id="department_filter_group">
                                    <div class="form-group">
                                        <label>Department</label>
                                        <input type="text" name="department" id="department" class="form-control" value="<?= htmlspecialchars($department)?>" placeholder="All">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-search"></i> View Report
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card report-box" id="reportArea">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><?= $report_title?></h5>
                        <small>Date Range: <?= date('d-m-Y', strtotime($from_date))?> to <?= date('d-m-Y', strtotime($to_date))?></small>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <?php if($report_type == 'purchase_item_wise'): ?>
                                <table class="table table-bordered table-sm">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Sl.No</th>
                                            <th>Item Code</th>
                                            <th>Item Name</th>
                                            <th>Unit</th>
                                            <th class="text-right">GRN Count</th>
                                            <th class="text-right">Total Qty</th>
                                            <th class="text-right">Avg Rate</th>
                                            <th class="text-right">Amount</th>
                                            <th class="text-right">GST</th>
                                            <th class="text-right">Net</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $sn=1; $qty=0; $amt=0; $gst=0; $net=0; while($row=mysqli_fetch_assoc($reportData)) { $qty += $row['total_qty']; $amt += $row['total_amount']; $gst += $row['total_gst']; $net += $row['total_net']; ?>
                                        <tr>
                                            <td><?= $sn++?></td>
                                            <td><?= $row['item_code']?></td>
                                            <td><?= $row['item_name']?></td>
                                            <td><?= $row['unit_name']?></td>
                                            <td class="text-right"><?= $row['grn_count']?></td>
                                            <td class="text-right"><?= number_format($row['total_qty'], 2)?></td>
                                            <td class="text-right">₹<?= number_format($row['avg_rate'], 2)?></td>
                                            <td class="text-right">₹<?= number_format($row['total_amount'], 2)?></td>
                                            <td class="text-right">₹<?= number_format($row['total_gst'], 2)?></td>
                                            <td class="text-right">₹<?= number_format($row['total_net'], 2)?></td>
                                        </tr>
                                        <?php } if($sn==1) { echo '<tr><td colspan="10" class="text-center text-danger">No records found</td></tr>'; } ?>
                                    </tbody>
                                    <tfoot><tr class="table-info"><th colspan="5" class="text-right">Total</th><th class="text-right"><?= number_format($qty, 2)?></th><th></th><th class="text-right">₹<?= number_format($amt, 2)?></th><th class="text-right">₹<?= number_format($gst, 2)?></th><th class="text-right">₹<?= number_format($net, 2)?></th></tr></tfoot>
                                </table>
                            <?php elseif($report_type == 'supplier_wise_purchase'): ?>
                                <table class="table table-bordered table-sm">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Sl.No</th>
                                            <th>Supplier Name</th>
                                            <th class="text-right">GRN Count</th>
                                            <th class="text-right">Invoice Count</th>
                                            <th class="text-right">Total Qty</th>
                                            <th class="text-right">Amount</th>
                                            <th class="text-right">GST</th>
                                            <th class="text-right">Net</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $sn=1; $qty=0; $amt=0; $gst=0; $net=0; while($row=mysqli_fetch_assoc($reportData)) { $qty += $row['total_qty']; $amt += $row['total_amount']; $gst += $row['total_gst']; $net += $row['total_net']; ?>
                                        <tr>
                                            <td><?= $sn++?></td>
                                            <td><?= $row['supplier_name']?></td>
                                            <td class="text-right"><?= $row['grn_count']?></td>
                                            <td class="text-right"><?= $row['invoice_count']?></td>
                                            <td class="text-right"><?= number_format($row['total_qty'], 2)?></td>
                                            <td class="text-right">₹<?= number_format($row['total_amount'], 2)?></td>
                                            <td class="text-right">₹<?= number_format($row['total_gst'], 2)?></td>
                                            <td class="text-right">₹<?= number_format($row['total_net'], 2)?></td>
                                        </tr>
                                        <?php } if($sn==1) { echo '<tr><td colspan="8" class="text-center text-danger">No records found</td></tr>'; } ?>
                                    </tbody>
                                    <tfoot><tr class="table-info"><th colspan="4" class="text-right">Total</th><th class="text-right"><?= number_format($qty, 2)?></th><th class="text-right">₹<?= number_format($amt, 2)?></th><th class="text-right">₹<?= number_format($gst, 2)?></th><th class="text-right">₹<?= number_format($net, 2)?></th></tr></tfoot>
                                </table>
                            <?php elseif($report_type == 'item_wise_issue'): ?>
                                <table class="table table-bordered table-sm">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Sl.No</th>
                                            <th>Item Code</th>
                                            <th>Item Name</th>
                                            <th>Unit</th>
                                            <th class="text-right">Issue Count</th>
                                            <th class="text-right">Total Qty</th>
                                            <th class="text-right">Avg Rate</th>
                                            <th class="text-right">Issued Value</th>
                                            <th>Departments</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $sn=1; $qty=0; $amt=0; while($row=mysqli_fetch_assoc($reportData)) { $qty += $row['total_qty']; $amt += $row['total_amount']; ?>
                                        <tr>
                                            <td><?= $sn++?></td>
                                            <td><?= $row['item_code']?></td>
                                            <td><?= $row['item_name']?></td>
                                            <td><?= $row['unit_name']?></td>
                                            <td class="text-right"><?= $row['issue_count']?></td>
                                            <td class="text-right"><?= number_format($row['total_qty'], 2)?></td>
                                            <td class="text-right">₹<?= number_format($row['avg_rate'], 2)?></td>
                                            <td class="text-right">₹<?= number_format($row['total_amount'], 2)?></td>
                                            <td><?= $row['departments'] ? $row['departments'] : '-'?></td>
                                        </tr>
                                        <?php } if($sn==1) { echo '<tr><td colspan="9" class="text-center text-danger">No records found</td></tr>'; } ?>
                                    </tbody>
                                    <tfoot><tr class="table-info"><th colspan="5" class="text-right">Total</th><th class="text-right"><?= number_format($qty, 2)?></th><th></th><th class="text-right">₹<?= number_format($amt, 2)?></th><th></th></tr></tfoot>
                                </table>
                            <?php elseif($report_type == 'issue_summary'): ?>
                                <table class="table table-bordered table-sm">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Sl.No</th>
                                            <th>Issue No</th>
                                            <th>Date</th>
                                            <th>Department</th>
                                            <th>Requested By</th>
                                            <th>Issued By</th>
                                            <th class="text-right">Item Count</th>
                                            <th class="text-right">Total Qty</th>
                                            <th class="text-right">Issued Value</th>
                                            <th>Status</th>
                                            <th>Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $sn=1; $item_count=0; $qty=0; $amt=0; while($row=mysqli_fetch_assoc($reportData)) { $item_count += $row['item_count']; $qty += $row['total_qty']; $amt += $row['total_amount']; ?>
                                        <tr>
                                            <td><?= $sn++?></td>
                                            <td><?= $row['issue_number']?></td>
                                            <td><?= date('d-m-Y', strtotime($row['issue_date']))?></td>
                                            <td><?= $row['department']?></td>
                                            <td><?= $row['requested_by']?></td>
                                            <td><?= $row['issued_by']?></td>
                                            <td class="text-right"><?= $row['item_count']?></td>
                                            <td class="text-right"><?= number_format($row['total_qty'], 2)?></td>
                                            <td class="text-right">₹<?= number_format($row['total_amount'], 2)?></td>
                                            <td><?= $row['status']?></td>
                                            <td><?= $row['remarks']?></td>
                                        </tr>
                                        <?php } if($sn==1) { echo '<tr><td colspan="11" class="text-center text-danger">No records found</td></tr>'; } ?>
                                    </tbody>
                                    <tfoot><tr class="table-info"><th colspan="6" class="text-right">Total</th><th class="text-right"><?= $item_count?></th><th class="text-right"><?= number_format($qty, 2)?></th><th class="text-right">₹<?= number_format($amt, 2)?></th><th colspan="2"></th></tr></tfoot>
                                </table>
                            <?php elseif($report_type == 'issue_detailed'): ?>
                                <table class="table table-bordered table-sm">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Sl.No</th>
                                            <th>Date</th>
                                            <th>Issue No</th>
                                            <th>Department</th>
                                            <th>Item Code</th>
                                            <th>Item Name</th>
                                            <th>Batch No</th>
                                            <th>Serial No</th>
                                            <th>Unit</th>
                                            <th class="text-right">Qty</th>
                                            <th class="text-right">Price</th>
                                            <th class="text-right">Amount</th>
                                            <th>Requested By</th>
                                            <th>Created By (User ID)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $sn=1; $qty=0; $amt=0; while($row=mysqli_fetch_assoc($reportData)) { $qty += $row['quantity']; $amt += $row['total_amount']; ?>
                                        <tr>
                                            <td><?= $sn++?></td>
                                            <td><?= date('d-m-Y', strtotime($row['issue_date']))?></td>
                                            <td><?= $row['issue_number']?></td>
                                            <td><?= $row['department']?></td>
                                            <td><?= $row['item_code']?></td>
                                            <td><?= $row['item_name']?></td>
                                            <td><?= $row['batch_no'] ? $row['batch_no'] : '-'?></td>
                                            <td><?= $row['serial_no'] ? $row['serial_no'] : '-'?></td>
                                            <td><?= $row['unit_name']?></td>
                                            <td class="text-right"><?= number_format($row['quantity'], 2)?></td>
                                            <td class="text-right">₹<?= number_format($row['rate'], 2)?></td>
                                            <td class="text-right">₹<?= number_format($row['total_amount'], 2)?></td>
                                            <td><?= $row['requested_by']?></td>
                                            <td><?= $row['created_by_user_id']?></td>
                                        </tr>
                                        <?php } if($sn==1) { echo '<tr><td colspan="14" class="text-center text-danger">No records found</td></tr>'; } ?>
                                    </tbody>
                                    <tfoot><tr class="table-info"><th colspan="9" class="text-right">Total</th><th class="text-right"><?= number_format($qty, 2)?></th><th></th><th class="text-right">₹<?= number_format($amt, 2)?></th><th colspan="2"></th></tr></tfoot>
                                </table>
                            <?php elseif($report_type == 'purchase_detailed'): ?>
                                <table class="table table-bordered table-sm">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Sl.No</th>
                                            <th>Date</th>
                                            <th>GRN No</th>
                                            <th>PO No</th>
                                            <th>Supplier</th>
                                            <th>Invoice No</th>
                                            <th>Item</th>
                                            <th>Batch</th>
                                            <th class="text-right">Qty</th>
                                            <th class="text-right">Rate</th>
                                            <th class="text-right">Amount</th>
                                            <th class="text-right">GST %</th>
                                            <th class="text-right">GST Amt</th>
                                            <th class="text-right">Net Amt</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $sn=1; $qty=0; $amt=0; $gst=0; $net=0; while($row=mysqli_fetch_assoc($reportData)) { $qty += $row['quantity']; $amt += $row['amount']; $gst += $row['gst_amount']; $net += $row['net_amount']; ?>
                                        <tr>
                                            <td><?= $sn++?></td>
                                            <td><?= date('d-m-Y', strtotime($row['received_date']))?></td>
                                            <td><?= $row['grn_number']?></td>
                                            <td><?= $row['po_number'] ? $row['po_number'] : '-'?></td>
                                            <td><?= $row['supplier_name']?></td>
                                            <td><?= $row['invoice_number'] ? $row['invoice_number'] : '-'?></td>
                                            <td><?= $row['item_name']?></td>
                                            <td><?= $row['batch_no'] ? $row['batch_no'] : '-'?></td>
                                            <td class="text-right"><?= number_format($row['quantity'], 2)?></td>
                                            <td class="text-right">₹<?= number_format($row['rate'], 2)?></td>
                                            <td class="text-right">₹<?= number_format($row['amount'], 2)?></td>
                                            <td class="text-right"><?= number_format($row['gst_percent'], 2)?>%</td>
                                            <td class="text-right">₹<?= number_format($row['gst_amount'], 2)?></td>
                                            <td class="text-right">₹<?= number_format($row['net_amount'], 2)?></td>
                                        </tr>
                                        <?php } if($sn==1) { echo '<tr><td colspan="14" class="text-center text-danger">No records found</td></tr>'; } ?>
                                    </tbody>
                                    <tfoot><tr class="table-info"><th colspan="8" class="text-right">Total</th><th class="text-right"><?= number_format($qty, 2)?></th><th></th><th class="text-right">₹<?= number_format($amt, 2)?></th><th></th><th class="text-right">₹<?= number_format($gst, 2)?></th><th class="text-right">₹<?= number_format($net, 2)?></th></tr></tfoot>
                                </table>
                            <?php else: ?>
                                <table class="table table-bordered table-sm">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Sl.No</th>
                                            <th>GRN No</th>
                                            <th>Date</th>
                                            <th>PO No</th>
                                            <th>Supplier</th>
                                            <th>Invoice No</th>
                                            <th class="text-right">Item Count</th>
                                            <th class="text-right">Total Qty</th>
                                            <th class="text-right">Amount</th>
                                            <th class="text-right">GST</th>
                                            <th class="text-right">Net</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $sn=1; $qty=0; $amt=0; $gst=0; $net=0; while($row=mysqli_fetch_assoc($reportData)) { $qty += $row['total_qty']; $amt += $row['total_amount']; $gst += $row['total_gst']; $net += $row['total_net']; ?>
                                        <tr>
                                            <td><?= $sn++?></td>
                                            <td><?= $row['grn_number']?></td>
                                            <td><?= date('d-m-Y', strtotime($row['received_date']))?></td>
                                            <td><?= $row['po_number'] ? $row['po_number'] : '-'?></td>
                                            <td><?= $row['supplier_name']?></td>
                                            <td><?= $row['invoice_number'] ? $row['invoice_number'] : '-'?></td>
                                            <td class="text-right"><?= $row['item_count']?></td>
                                            <td class="text-right"><?= number_format($row['total_qty'], 2)?></td>
                                            <td class="text-right">₹<?= number_format($row['total_amount'], 2)?></td>
                                            <td class="text-right">₹<?= number_format($row['total_gst'], 2)?></td>
                                            <td class="text-right">₹<?= number_format($row['total_net'], 2)?></td>
                                        </tr>
                                        <?php } if($sn==1) { echo '<tr><td colspan="11" class="text-center text-danger">No records found</td></tr>'; } ?>
                                    </tbody>
                                    <tfoot><tr class="table-info"><th colspan="7" class="text-right">Total</th><th class="text-right"><?= number_format($qty, 2)?></th><th class="text-right">₹<?= number_format($amt, 2)?></th><th class="text-right">₹<?= number_format($gst, 2)?></th><th class="text-right">₹<?= number_format($net, 2)?></th></tr></tfoot>
                                </table>
                            <?php endif; ?>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        function setReportType(type) {
            document.getElementById('report_type').value = type;
            toggleFilters();
            document.querySelector('form').submit();
        }

        function toggleFilters() {
            const type = document.getElementById('report_type').value;
            const itemReports = ['purchase_item_wise', 'item_wise_issue', 'issue_detailed', 'purchase_detailed'];
            const supplierReports = ['purchase_item_wise', 'supplier_wise_purchase', 'purchase_summary', 'purchase_detailed'];
            const departmentReports = ['item_wise_issue', 'issue_summary', 'issue_detailed'];

            document.getElementById('item_filter_group').style.display = itemReports.includes(type) ? 'block' : 'none';
            document.getElementById('supplier_filter_group').style.display = supplierReports.includes(type) ? 'block' : 'none';
            document.getElementById('department_filter_group').style.display = departmentReports.includes(type) ? 'block' : 'none';

            document.querySelectorAll('.report-card').forEach(card => card.classList.remove('active-card'));
            const activeCard = document.querySelector(`.report-card[data-type="${type}"]`);
            if (activeCard) {
                activeCard.classList.add('active-card');
            }
        }

        function printReport() {
            window.print();
        }

        $(document).ready(function() {
            $('#item_id, #supplier_id, #report_type').select2({ width: '100%' });
            toggleFilters();
        });
    </script>
</body>
</html>
