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

    <title>Supplier Payment List</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon.png">
    <link href="css/style.css" rel="stylesheet">
    <link href="vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

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
            <h4 class="text-uppercase">💳 Supplier Payment List</h4>
        </div>
        <div class="col-md-4 text-right">
            <a href="add_store_payment.php" class="btn btn-success">
                <i class="fa fa-plus"></i> Make Payment
            </a>
        </div>
    </div>

    <?php if(isset($_GET['success']) && $_GET['success'] == 1): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Success!</strong> Payment recorded successfully.
    </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Filter by Supplier</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <select class="form-control" id="filter_supplier">
                                <option value="">All Suppliers</option>
                                <?php 
                                $suppliers = $newStore->getActiveSuppliers();
                                while($sup = mysqli_fetch_assoc($suppliers)) {
                                    echo "<option value='{$sup['supplier_name']}'>{$sup['supplier_name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-info btn-block" onclick="filterTable()">
                                <i class="fa fa-filter"></i> Apply
                            </button>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-secondary btn-block" onclick="resetFilter()">
                                <i class="fa fa-refresh"></i> Reset
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="paymentTable" class="display table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Payment No</th>
                                    <th>Payment Date</th>
                                    <th>Supplier</th>
                                    <th>Payment Mode</th>
                                    <th>Reference No</th>
                                    <th>Amount</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $payments = $newStore->getSupplierPayments();
                                $sno = 1;
                                while($pay = mysqli_fetch_assoc($payments)) {
                                    echo "<tr>";
                                    echo "<td>{$sno}</td>";
                                    echo "<td>{$pay['payment_number']}</td>";
                                    echo "<td>" . date('d-M-Y', strtotime($pay['payment_date'])) . "</td>";
                                    echo "<td>{$pay['supplier_name']}</td>";
                                    echo "<td><span class='badge badge-info'>{$pay['payment_mode']}</span></td>";
                                    echo "<td>{$pay['reference_number']}</td>";
                                    echo "<td class='text-right'><strong>₹" . number_format($pay['payment_amount'], 2) . "</strong></td>";
                                    echo "<td>
                                            <a href='view_store_payment.php?id={$pay['id']}' class='btn btn-info btn-sm' title='View Details'>
                                                <i class='fa fa-eye'></i>
                                            </a>
                                            <a href='print_store_payment.php?id={$pay['id']}' class='btn btn-secondary btn-sm' title='Print Receipt' target='_blank'>
                                                <i class='fa fa-print'></i>
                                            </a>
                                          </td>";
                                    echo "</tr>";
                                    $sno++;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
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

<script>
var table;

$(document).ready(function() {
    table = $('#paymentTable').DataTable({
        "order": [[ 2, "desc" ]], // Order by payment date descending
        "pageLength": 25,
        "columnDefs": [
            { "orderable": false, "targets": 7 } // Action column not sortable
        ]
    });
});

function filterTable() {
    var supplier = $('#filter_supplier').val();
    table.column(3).search(supplier).draw();
}

function resetFilter() {
    $('#filter_supplier').val('');
    table.search('').columns().search('').draw();
}
</script>

</body>
</html>
