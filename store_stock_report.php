<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/storeClass.php');
$newStore = new store;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#319197">

    <title>Store Stock Reports</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon.png">
    <link href="css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" ></script>
    <link href="vendor/datatables/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
 
<style>
    .report-card {
        cursor: pointer;
        transition: all 0.3s;
    }
    .report-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }
    .low-stock { background-color: #fff3cd; }
    .expiring-soon { background-color: #f8d7da; }
    .select2-container .select2-selection--single { height: 38px; }
    .select2-container .select2-selection--single .select2-selection__rendered { line-height: 36px; }
    .select2-container .select2-selection--single .select2-selection__arrow { height: 36px; }
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
        <div class="col-md-12">
            <h4 class="text-uppercase">📊 Store Stock Reports</h4>
        </div>
    </div>
    
    <!-- Report Cards -->
    <div class="row">
        <div class="col-md-3">
            <div class="card report-card" onclick="showReport('current_stock')">
                <div class="card-body text-center">
                    <i class="fa fa-archive fa-3x text-primary mb-3"></i>
                    <h5>Current Stock</h5>
                    <p>View all items in stock</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card report-card" onclick="showReport('low_stock')">
                <div class="card-body text-center">
                    <i class="fa fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                    <h5>Low Stock Alert</h5>
                    <p>Items below reorder level</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card report-card" onclick="showReport('expiring')">
                <div class="card-body text-center">
                    <i class="fa fa-calendar-times-o fa-3x text-danger mb-3"></i>
                    <h5>Expiring Items</h5>
                    <p>Items expiring in 90 days</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card report-card" onclick="showReport('ledger')">
                <div class="card-body text-center">
                    <i class="fa fa-book fa-3x text-success mb-3"></i>
                    <h5>Stock Ledger</h5>
                    <p>Complete transaction history</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Current Stock Report -->
    <div class="row" id="current_stock" style="display:none;">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>📦 Current Stock Report</h4>
                    <button onclick="printReport('current_stock_table')" class="btn btn-sm btn-primary float-right">
                        <i class="fa fa-print"></i> Print
                    </button>
                    <button onclick="exportToExcel('current_stock_table', 'Current_Stock')" class="btn btn-sm btn-success float-right mr-2">
                        <i class="fa fa-file-excel"></i> Excel
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm" id="current_stock_table">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Sl.No.</th>
                                    <th>Item Code</th>
                                    <th>Item Name</th>
                                    <th>Category</th>
                                    <th>Batch No</th>
                                    <th>Expiry Date</th>
                                    <th>Available Qty</th>
                                    <th>Unit</th>
                                    <th>Last Updated</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $stock=$newStore->getCurrentStock();
                                $sn=1;
                                $total_items = 0;
                                while($row=mysqli_fetch_assoc($stock)){
                                    $total_items += $row['available_qty'];
                                ?>
                                <tr>
                                    <td><?= $sn?></td>
                                    <td><?= $row['item_code']?></td>
                                    <td><?= $row['item_name']?></td>
                                    <td><?= $row['category_name']?></td>
                                    <td><?= $row['batch_no'] ? $row['batch_no'] : 'N/A'?></td>
                                    <td><?= $row['expiry_date'] && $row['expiry_date'] != '0000-00-00' ? date('d-m-Y', strtotime($row['expiry_date'])) : 'N/A'?></td>
                                    <td class="text-right"><strong><?= $row['available_qty']?></strong></td>
                                    <td><?= $row['unit_name']?></td>
                                    <td><?= $row['last_updated'] && $row['last_updated'] != '0000-00-00 00:00:00' ? date('d-m-Y H:i', strtotime($row['last_updated'])) : 'N/A' ?></td>
                                </tr>
                                <?php 
                                $sn++; } 
                                ?>
                            </tbody>
                            <tfoot>
                                <tr class="table-info">
                                    <td colspan="6" class="text-right"><strong>Total Items:</strong></td>
                                    <td class="text-right"><strong><?= $total_items?></strong></td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Low Stock Report -->
    <div class="row" id="low_stock" style="display:none;">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-warning">
                    <h4>⚠️ Low Stock Alert Report</h4>
                    <button onclick="printReport('low_stock_table')" class="btn btn-sm btn-dark float-right">
                        <i class="fa fa-print"></i> Print
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm" id="low_stock_table">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Sl.No.</th>
                                    <th>Item Code</th>
                                    <th>Item Name</th>
                                    <th>Category</th>
                                    <th>Current Stock</th>
                                    <th>Reorder Level</th>
                                    <th>Required Qty</th>
                                    <th>Unit</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $low=$newStore->getLowStockItems();
                                $sn=1;
                                while($row=mysqli_fetch_assoc($low)){
                                    $required = $row['reorder_level'] - $row['current_stock'];
                                ?>
                                <tr class="low-stock">
                                    <td><?= $sn?></td>
                                    <td><?= $row['item_code']?></td>
                                    <td><?= $row['item_name']?></td>
                                    <td><?= $row['category_name']?></td>
                                    <td class="text-right text-danger"><strong><?= $row['current_stock']?></strong></td>
                                    <td class="text-right"><?= $row['reorder_level']?></td>
                                    <td class="text-right text-primary"><strong><?= $required?></strong></td>
                                    <td><?= $row['unit_name']?></td>
                                </tr>
                                <?php 
                                $sn++; } 
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Expiring Items Report -->
    <div class="row" id="expiring" style="display:none;">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h4>📅 Expiring Items Report (Next 90 Days)</h4>
                    <button onclick="printReport('expiring_table')" class="btn btn-sm btn-light float-right">
                        <i class="fa fa-print"></i> Print
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm" id="expiring_table">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Sl.No.</th>
                                    <th>Item Code</th>
                                    <th>Item Name</th>
                                    <th>Batch No</th>
                                    <th>Expiry Date</th>
                                    <th>Days to Expire</th>
                                    <th>Available Qty</th>
                                    <th>Unit</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $expiring=$newStore->getExpiringItems(90);
                                $sn=1;
                                while($row=mysqli_fetch_assoc($expiring)){
                                    $days = $row['days_to_expire'];
                                    $row_class = $days <= 30 ? 'expiring-soon' : ($days <= 60 ? 'low-stock' : '');
                                ?>
                                <tr class="<?= $row_class?>">
                                    <td><?= $sn?></td>
                                    <td><?= $row['item_code']?></td>
                                    <td><?= $row['item_name']?></td>
                                    <td><?= $row['batch_no']?></td>
                                    <td><?= date('d-m-Y', strtotime($row['expiry_date']))?></td>
                                    <td class="text-center">
                                        <?php if($days <= 30) { ?>
                                            <span class="badge badge-danger"><?= $days?> days</span>
                                        <?php } else if($days <= 60) { ?>
                                            <span class="badge badge-warning"><?= $days?> days</span>
                                        <?php } else { ?>
                                            <span class="badge badge-info"><?= $days?> days</span>
                                        <?php } ?>
                                    </td>
                                    <td class="text-right"><?= $row['available_qty']?></td>
                                    <td><?= $row['unit_name']?></td>
                                </tr>
                                <?php 
                                $sn++; } 
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Ledger Report -->
    <div class="row" id="ledger" style="<?= isset($_POST['show_ledger']) ? '' : 'display:none;' ?>">  
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4>📖 Stock Ledger Report</h4>
                </div>
                <div class="card-body">
                    <form method="POST" class="mb-3">
                        <div class="row">
                            <div class="col-md-3">
                                <label>From Date</label>
                                <input type="date" class="form-control" name="from_date" value="<?= isset($_POST['from_date']) ? $_POST['from_date'] : date('Y-m-01')?>">
                            </div>
                            <div class="col-md-3">
                                <label>To Date</label>
                                <input type="date" class="form-control" name="to_date" value="<?= isset($_POST['to_date']) ? $_POST['to_date'] : date('Y-m-d')?>">
                            </div>
                            <div class="col-md-4">
                                <label>Item (Optional)</label>
                                <select class="form-control select2-item" name="item_id" style="width:100%">
                                    <option value="">All Items</option>
                                    <?php 
                                    $items = $newStore->getActiveItems();
                                    while($item = mysqli_fetch_assoc($items)) {
                                        $selected = (isset($_POST['item_id']) && $_POST['item_id'] == $item['id']) ? 'selected' : '';
                                        echo "<option value='{$item['id']}' {$selected}>{$item['item_code']} - {$item['item_name']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-primary btn-block" name="show_ledger">Show Report</button>
                            </div>
                        </div>
                    </form>
                    
                    <?php if(isset($_POST['show_ledger'])) { ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm" id="ledger_table">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Date</th>
                                    <th>Item Code</th>
                                    <th>Item Name</th>
                                    <th>Batch</th>
                                    <th>Transaction Type</th>
                                    <th>Reference No</th>
                                    <th>Qty In</th>
                                    <th>Qty Out</th>
                                    <th>Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $item_id = isset($_POST['item_id']) ? $_POST['item_id'] : null;
                                $from_date = $_POST['from_date'];
                                $to_date = $_POST['to_date'];
                                
                                $ledger=$newStore->getStockLedger($item_id, $from_date, $to_date);
                                while($row=mysqli_fetch_assoc($ledger)){
                                ?>
                                <tr>
                                    <td><?= date('d-m-Y H:i', strtotime($row['transaction_date']))?></td>
                                    <td><?= $row['item_code']?></td>
                                    <td><?= $row['item_name']?></td>
                                    <td><?= $row['batch_no'] ? $row['batch_no'] : 'N/A'?></td>
                                    <td>
                                        <?php 
                                        $type_colors = [
                                            'GRN' => 'success',
                                            'ISSUE' => 'danger',
                                            'RETURN' => 'info',
                                            'ADJUSTMENT' => 'warning'
                                        ];
                                        $color = $type_colors[$row['transaction_type']];
                                        ?>
                                        <span class="badge badge-<?= $color?>"><?= $row['transaction_type']?></span>
                                    </td>
                                    <td><?= $row['reference_number']?></td>
                                    <td class="text-right text-success"><?= $row['qty_in'] > 0 ? $row['qty_in'] : '-'?></td>
                                    <td class="text-right text-danger"><?= $row['qty_out'] > 0 ? $row['qty_out'] : '-'?></td>
                                    <td class="text-right"><strong><?= $row['balance']?></strong></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <?php } ?>
                </div>
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
    
    <script src="vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    function showReport(reportId) {
        // Hide all reports
        $('#current_stock, #low_stock, #expiring, #ledger').hide();
        
        // Show selected report
        $('#' + reportId).show();
        
        // Initialize DataTable
        $('#' + reportId + '_table').DataTable({
            "pageLength": 50,
            "ordering": true,
            "searching": true,
            "destroy": true
        });

        // Init Select2 inside ledger form after it becomes visible
        if (reportId === 'ledger') {
            initLedgerSelect2();
        }
    }

    function initLedgerSelect2() {
        if (!$.fn.select2) {
            return;
        }

        if ($('.select2-item').hasClass('select2-hidden-accessible')) {
            $('.select2-item').select2('destroy');
        }

        $('.select2-item').select2({
            placeholder: 'Search item...',
            allowClear: true,
            width: '100%'
        });
    }

    // Auto-init when page loads with POST ledger results
    $(document).ready(function () {
        <?php if (isset($_POST['show_ledger'])): ?>
        initLedgerSelect2();
        if ($.fn.DataTable) {
            $('#ledger_table').DataTable({
                "pageLength": 50,
                "ordering": true,
                "searching": true,
                "destroy": true
            });
        }
        <?php endif; ?>
    });

    function printReport(tableId) {
        var printContents = document.getElementById(tableId).outerHTML;
        var originalContents = document.body.innerHTML;
        
        document.body.innerHTML = '<html><head><title>Store Report</title>' +
            '<style>table {width: 100%; border-collapse: collapse;} ' +
            'th, td {border: 1px solid #000; padding: 5px; font-size: 12px;} ' +
            'th {background-color: #333; color: #fff;}</style></head><body>' +
            printContents + '</body></html>';
        
        window.print();
        document.body.innerHTML = originalContents;
        location.reload();
    }

    function exportToExcel(tableId, filename) {
        var downloadLink;
        var dataType = 'application/vnd.ms-excel';
        var tableSelect = document.getElementById(tableId);
        var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
        
        filename = filename ? filename + '.xls' : 'excel_data.xls';
        
        downloadLink = document.createElement("a");
        document.body.appendChild(downloadLink);
        
        if(navigator.msSaveOrOpenBlob){
            var blob = new Blob(['\ufeff', tableHTML], {
                type: dataType
            });
            navigator.msSaveOrOpenBlob( blob, filename);
        }else{
            downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
            downloadLink.download = filename;
            downloadLink.click();
        }
    }
</script>

</body>
</html>
