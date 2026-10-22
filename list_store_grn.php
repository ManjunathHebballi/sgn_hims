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

    <title>GRN List</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon.png">
    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">

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
                    <div class="col-md-6">
                        <h4 class="text-uppercase">📦 Goods Receipt Notes (GRN)</h4>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="add_store_grn.php" class="btn btn-primary">
                            <i class="fa fa-plus"></i> New GRN
                        </a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="grnTable" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>GRN Number</th>
                                                <th>GRN Date</th>
                                                <th>PO Number</th>
                                                <th>Supplier</th>
                                                <th>Invoice No</th>
                                                <th>Total Amount</th>
                                                <th>Created By</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $grns = $newStore->getAllGRNs();
                                            while($grn = mysqli_fetch_assoc($grns)):
                                            ?>
                                            <tr>
                                                <td><?= $grn['grn_number']?></td>
                                                <td><?= date('d-M-Y', strtotime($grn['received_date']))?></td>
                                                <td><?= $grn['po_number'] ?: 'N/A'?></td>
                                                <td><?= $grn['supplier_name']?></td>
                                                <td><?= $grn['invoice_number'] ?: 'N/A'?></td>
                                                <td class="text-right">₹<?= number_format($grn['total_amount'], 2)?>
                                                </td>
                                                <td><?= $grn['created_by']?></td>
                                                <td>
                                                    <a href="view_store_grn.php?id=<?= $grn['id']?>"
                                                        class="btn btn-sm btn-info" title="View">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <a href="edit_store_grn.php?id=<?= $grn['id']?>"
                                                        class="btn btn-sm btn-warning" title="Edit">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <a href="print_store_grn.php?id=<?= $grn['id']?>" target="_blank"
                                                        class="btn btn-sm btn-primary" title="Print">
                                                        <i class="fa fa-print"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endwhile; ?>
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
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>

    <script>
    $(document).ready(function() {
        $('#grnTable').DataTable({
            "order": [
                [1, "desc"]
            ],
            "pageLength": 25
        });
    });
    </script>

</body>

</html>
