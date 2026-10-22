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

    <title>Purchase Invoice List</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon.png">
    <link href="css/style.css" rel="stylesheet">
    <link href="vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <style>
    .badge-unpaid {
        background-color: #dc3545;
    }

    .badge-partial {
        background-color: #ffc107;
        color: #000;
    }

    .badge-paid {
        background-color: #28a745;
    }

    .overdue {
        background-color: #ffe5e5;
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
                        <h4 class="text-uppercase">📄 Purchase Invoice List</h4>
                    </div>
                    <div class="col-md-4 text-right">
                        <a href="add_store_invoice.php" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Add New Invoice
                        </a>
                    </div>
                </div>

                <?php if(isset($_GET['success']) && $_GET['success'] == 1): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>Success!</strong> Invoice saved successfully.
                </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>Filter Options</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <select class="form-control" id="filter_status">
                                            <option value="">All Status</option>
                                            <option value="UNPAID">Unpaid</option>
                                            <option value="PARTIALLY_PAID">Partially Paid</option>
                                            <option value="PAID">Paid</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
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
                                            <i class="fa fa-filter"></i> Apply Filter
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
                                    <table id="invoiceTable" class="display table table-striped table-bordered"
                                        style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>S.No</th>
                                                <th>Invoice No</th>
                                                <th>Invoice Date</th>
                                                <th>Supplier</th>
                                                <th>Due Date</th>
                                                <th>Invoice Amt</th>
                                                <th>Paid Amt</th>
                                                <th>Balance</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                $invoices = $newStore->getPurchaseInvoices();
                                $sno = 1;
                                while($inv = mysqli_fetch_assoc($invoices)) {
                                    $status_badge = '';
                                    if($inv['payment_status'] == 'UNPAID') {
                                        $status_badge = '<span class="badge badge-unpaid">Unpaid</span>';
                                    } else if($inv['payment_status'] == 'PARTIALLY_PAID') {
                                        $status_badge = '<span class="badge badge-partial">Partially Paid</span>';
                                    } else {
                                        $status_badge = '<span class="badge badge-paid">Paid</span>';
                                    }
                                    
                                    // Check if overdue
                                    $row_class = '';
                                    if($inv['payment_status'] != 'PAID' && strtotime($inv['due_date']) < time()) {
                                        $row_class = 'overdue';
                                    }
                                    
                                    echo "<tr class='$row_class'>";
                                    echo "<td>{$sno}</td>";
                                    echo "<td>{$inv['invoice_number']}</td>";
                                    echo "<td>" . date('d-M-Y', strtotime($inv['invoice_date'])) . "</td>";
                                    echo "<td>{$inv['supplier_name']}</td>";
                                    echo "<td>" . date('d-M-Y', strtotime($inv['due_date'])) . "</td>";
                                    echo "<td class='text-right'>₹" . number_format($inv['net_amount'], 2) . "</td>";
                                    echo "<td class='text-right'>₹" . number_format($inv['paid_amount'], 2) . "</td>";
                                    echo "<td class='text-right'>₹" . number_format($inv['balance_amount'], 2) . "</td>";
                                    echo "<td>{$status_badge}</td>";
                                    echo "<td>
                                            <a href='view_store_invoice.php?id={$inv['id']}' class='btn btn-info btn-sm' title='View'>
                                                <i class='fa fa-eye'></i>
                                            </a>
                                            <a href='print_store_invoice.php?id={$inv['id']}' class='btn btn-secondary btn-sm' title='Print' target='_blank'>
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
        table = $('#invoiceTable').DataTable({
            "order": [
                [2, "desc"]
            ], // Order by invoice date descending
            "pageLength": 25,
            "columnDefs": [{
                    "orderable": false,
                    "targets": 9
                } // Action column not sortable
            ]
        });
    });

    function filterTable() {
        var status = $('#filter_status').val();
        var supplier = $('#filter_supplier').val();

        table.column(8).search(status).draw();
        table.column(3).search(supplier).draw();
    }

    function resetFilter() {
        $('#filter_status').val('');
        $('#filter_supplier').val('');
        table.search('').columns().search('').draw();
    }
    </script>

</body>

</html>