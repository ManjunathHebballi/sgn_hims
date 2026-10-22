<?php 
require_once(__DIR__ . '/model/session_config.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set("Asia/Kolkata");  
include('model/cathLabClass.php');

$cathLab = new cathLab;

// Get all GRNs
try {
    $grns_result = $cathLab->getCathLabGrns(1, 100, 0);
} catch(Exception $e) {
    $_SESSION['error_message'] = "Error loading GRNs: " . $e->getMessage();
    $grns_result = null;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#319197">

    <title>CathLab GRN List</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon.png">
    <link href="css/style.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <style>
    .grn-badge {
        display: inline-block;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .card-header.cathlab {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .content-body {
        margin-top: 120px;
    }

    .action-buttons .btn {
        margin-right: 5px;
        margin-bottom: 5px;
    }

    .badge-completed {
        background-color: #28a745;
        color: white;
        padding: 5px 10px;
        border-radius: 4px;
    }

    .stats-card {
        background: linear-gradient(135deg, #f0f0ff 0%, #e8eaf6 100%);
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        text-align: center;
    }

    .stats-card .value {
        font-size: 32px;
        font-weight: bold;
        color: #667eea;
    }

    .stats-card .label {
        font-size: 14px;
        color: #666;
        margin-top: 5px;
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

    <div id="main-wrapper" style="display: block; opacity: 1;">

        <?php include ('leftbar.php')?>

        <div class="content-body">

            <div class="container-fluid">
                
                <!-- Header -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <span class="grn-badge">📦 GRN MANAGEMENT</span>
                        <h4 class="text-uppercase">CathLab Goods Receipt Notes</h4>
                        <p class="text-muted">View and manage all CathLab GRN records</p>
                    </div>
                </div>

                <?php if(isset($_SESSION['success_message'])): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>Success!</strong> <?= $_SESSION['success_message'] ?>
                    </div>
                    <?php unset($_SESSION['success_message']); ?>
                <?php endif; ?>

                <?php if(isset($_SESSION['error_message'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>Error!</strong> <?= $_SESSION['error_message'] ?>
                    </div>
                    <?php unset($_SESSION['error_message']); ?>
                <?php endif; ?>

                <!-- Statistics -->
                <?php 
                try {
                    $stats = $cathLab->getCathLabGrnStats(1);
                } catch(Exception $e) {
                    $stats = array('total_grns' => 0, 'total_items' => 0, 'total_quantity' => 0, 'total_cost' => 0);
                }
                ?>
                <div class="row">
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="value"><?= $stats['total_grns'] ?? 0 ?></div>
                            <div class="label">Total GRNs</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="value"><?= $stats['total_items'] ?? 0 ?></div>
                            <div class="label">Total Items</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="value"><?= number_format($stats['total_quantity'] ?? 0, 0) ?></div>
                            <div class="label">Total Quantity</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="value">₹<?= number_format($stats['total_cost'] ?? 0, 2) ?></div>
                            <div class="label">Total Value</div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <a href="add_cathlab_grn.php" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Add New GRN
                        </a>
                        <a href="cathlab_store_dashboard.php" class="btn btn-info">
                            <i class="fa fa-arrow-left"></i> Back to Dashboard
                        </a>
                    </div>
                </div>

                <!-- GRN List Table -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header cathlab">
                                <h5><i class="fa fa-list"></i> All GRN Records</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="grnTable" class="table table-striped table-bordered" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>GRN Number</th>
                                                <th>GRN Date</th>
                                                <th>Supplier</th>
                                                <th>Items</th>
                                                <th>Quantity</th>
                                                <th>Total Cost</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            if($grns_result && mysqli_num_rows($grns_result) > 0) {
                                                while($grn = mysqli_fetch_assoc($grns_result)) {
                                            ?>
                                                <tr>
                                                    <td><strong><?= htmlspecialchars($grn['grn_number']) ?></strong></td>
                                                    <td><?= date('d-m-Y', strtotime($grn['grn_date'])) ?></td>
                                                    <td><?= htmlspecialchars($grn['supplier_name']) ?></td>
                                                    <td class="text-center"><?= $grn['total_items'] ?? 0 ?></td>
                                                    <td class="text-center"><?= number_format($grn['total_quantity'] ?? 0, 2) ?></td>
                                                    <td class="text-right"><strong>₹<?= number_format($grn['total_cost'] ?? 0, 2) ?></strong></td>
                                                    <td>
                                                        <span class="badge-completed">Completed</span>
                                                    </td>
                                                    <td class="action-buttons">
                                                        <a href="view_cathlab_grn.php?id=<?= $grn['id'] ?>" 
                                                           class="btn btn-sm btn-info" 
                                                           title="View Details">
                                                            <i class="fa fa-eye"></i> View
                                                        </a>
                                                        <a href="print_cathlab_grn.php?id=<?= $grn['id'] ?>" 
                                                           class="btn btn-sm btn-secondary" 
                                                           target="_blank"
                                                           title="Print GRN">
                                                            <i class="fa fa-print"></i> Print
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php 
                                                }
                                            } else {
                                            ?>
                                                <tr>
                                                    <td colspan="8" class="text-center">
                                                        <p class="text-muted mt-3 mb-3">
                                                            <i class="fa fa-inbox fa-3x"></i><br><br>
                                                            No GRN records found. Click "Add New GRN" to create one.
                                                        </p>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <?php include ('footer.php')?>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#preloader').hide();
            $('#main-wrapper').show();

            // Initialize DataTable
            $('#grnTable').DataTable({
                "order": [[1, "desc"]], // Sort by date descending
                "pageLength": 25,
                "language": {
                    "search": "Search GRNs:",
                    "lengthMenu": "Show _MENU_ GRNs per page",
                    "info": "Showing _START_ to _END_ of _TOTAL_ GRNs",
                    "infoEmpty": "No GRNs available",
                    "infoFiltered": "(filtered from _MAX_ total GRNs)"
                }
            });
        });
    </script>

</body>

</html>
