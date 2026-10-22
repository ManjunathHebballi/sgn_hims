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

    <title>Purchase Orders List</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon.png">
    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">

    <style>
    .badge-pending {
        background-color: #ffc107;
    }

    .badge-approved {
        background-color: #007bff;
    }

    .badge-partially-received {
        background-color: #17a2b8;
    }

    .badge-completed {
        background-color: #28a745;
    }

    .badge-cancelled {
        background-color: #dc3545;
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
                    <div class="col-md-6">
                        <h4 class="text-uppercase">🛒 Purchase Orders</h4>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="add_store_po.php" class="btn btn-primary">
                            <i class="fa fa-plus"></i> New Purchase Order
                        </a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="poTable" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>PO Number</th>
                                                <th>PO Date</th>
                                                <th>Supplier</th>
                                                <th>Amount</th>
                                                <th>Total GST</th>
                                                <th>Total Payable</th>
                                                <th>Status</th>
                                                <th>Created By</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $pos = $newStore->getAllPurchaseOrders();
                                            while($po = mysqli_fetch_assoc($pos)):
                                                // Calculate total GST and net amount from PO items
                                                $po_items = $newStore->getPOItemsByPOId($po['id']);
                                                $total_amount = 0;
                                                $total_gst = 0;
                                                while($item = mysqli_fetch_assoc($po_items)) {
                                                    $item_amount = $item['quantity'] * $item['rate'];
                                                    $gst_amount = isset($item['gst_amount']) ? $item['gst_amount'] : 0;
                                                    $total_amount += $item_amount;
                                                    $total_gst += $gst_amount;
                                                }
                                                $total_payable = $total_amount + $total_gst;
                                                
                                                // Check if GRN exists for this PO
                                                $grn_exists = $newStore->checkGRNExists($po['id']);
                                            ?>
                                            <tr>
                                                <td><?= $po['po_number']?></td>
                                                <td><?= date('d-M-Y', strtotime($po['po_date']))?></td>
                                                <td><?= $po['supplier_name']?></td>
                                                <td class="text-right">₹<?= number_format($total_amount, 2)?></td>
                                                <td class="text-right">₹<?= number_format($total_gst, 2)?></td>
                                                <td class="text-right font-weight-bold">₹<?= number_format($total_payable, 2)?></td>
                                                <td>
                                                    <?php
                                                    $status = $po['status'];
                                                    if($status == 'PENDING') {
                                                        echo '<span class="badge badge-pending">Pending</span>';
                                                    } else if($status == 'APPROVED') {
                                                        echo '<span class="badge badge-approved">Approved</span>';
                                                    } else if($status == 'PARTIALLY_RECEIVED') {
                                                        echo '<span class="badge badge-partially-received">Partial</span>';
                                                    } else if($status == 'RECEIVED' || $status == 'COMPLETED') {
                                                        echo '<span class="badge badge-completed">RECEIVED</span>';
                                                    } else if($status == 'CANCELLED') {
                                                        echo '<span class="badge badge-cancelled">Cancelled</span>';
                                                    }
                                                    ?>
                                                </td>
                                                <td><?= $po['created_by']?></td>
                                                <td>
                                                    <a href="view_store_po.php?id=<?= $po['id']?>"
                                                        class="btn btn-sm btn-info" title="View">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <?php if($status =='PENDING'): ?>
                                                    <a href="add_store_po.php?id=<?= $po['id']?>"
                                                        class="btn btn-sm btn-primary" title="Edit">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'Admin'): ?>
                                                    <form action="controller/processStore.php" method="POST" class="d-inline"
                                                        onsubmit="return confirm('Approve PO <?= $po['po_number']?>?');">
                                                        <input type="hidden" name="po_id" value="<?= $po['id']?>">
                                                        <button type="submit" name="submit" value="approve_po"
                                                            class="btn btn-sm btn-warning" title="Approve PO">
                                                            <i class="fa fa-check"></i>
                                                        </button>
                                                    </form>
                                                    <?php else: ?>
                                                    <button type="button" class="btn btn-sm btn-secondary" disabled title="Only Admin can approve PO">
                                                        <i class="fa fa-shield"></i>
                                                    </button>
                                                    <?php endif; ?>
                                                    <?php endif; ?>
                                                    <?php if($status == 'APPROVED' || $status == 'PARTIALLY_RECEIVED'): ?>
                                                    <a href="add_store_grn.php?po_id=<?= $po['id']?>"
                                                        class="btn btn-sm btn-success" title="Create GRN">
                                                        <i class="fa fa-truck"></i>
                                                    </a>
                                                    <?php endif; ?>
                                                    <?php if(!$grn_exists && $status != 'CANCELLED'): ?>
                                                    <button type="button" class="btn btn-sm btn-danger cancel-po" data-po-id="<?= $po['id']?>" data-po-number="<?= $po['po_number']?>" title="Cancel PO">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                    <?php endif; ?>
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
        $('#poTable').DataTable({
            "order": [
                [1, "desc"]
            ],
            "pageLength": 25
        });

        // Handle PO cancellation
        $(document).on('click', '.cancel-po', function(e) {
            e.preventDefault();
            let poId = $(this).data('po-id');
            let poNumber = $(this).data('po-number');
            let $btn = $(this);

            if (confirm('Are you sure you want to cancel PO ' + poNumber + '?\n\nThis action cannot be undone.')) {
                $.ajax({
                    url: 'controller/processStore.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        submit: 'cancel_po',
                        po_id: poId
                    },
                    success: function(response) {
                        if (response.success) {
                            alert('Purchase Order ' + poNumber + ' has been cancelled successfully.');
                            location.reload();
                        } else {
                            alert('Error: ' + (response.message || 'Failed to cancel PO'));
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('AJAX Error:', xhr.responseText);
                        alert('Error: Failed to process cancellation. ' + error);
                    }
                });
            }
        });
    });
    </script>

</body>

</html>
