<?php 
require_once(__DIR__ . '/model/session_config.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set("Asia/Kolkata");  
include('model/cathLabClass.php');

$cathLab = new cathLab;

// Get all Procedures
try {
    $procedures_result = $cathLab->getCathLabProcedures(100, 0);
} catch(Exception $e) {
    $_SESSION['error_message'] = "Error loading procedures: " . $e->getMessage();
    $procedures_result = null;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#319197">

    <title>CathLab Procedure List</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon.png">
    <link href="css/style.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <style>
    .procedure-badge {
        display: inline-block;
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .card-header.cathlab {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
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

    .badge-pending {
        background-color: #ffc107;
        color: #333;
        padding: 5px 10px;
        border-radius: 4px;
    }

    .badge-cancelled {
        background-color: #dc3545;
        color: white;
        padding: 5px 10px;
        border-radius: 4px;
    }

    .stats-card {
        background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        text-align: center;
    }

    .stats-card .value {
        font-size: 32px;
        font-weight: bold;
        color: #2e7d32;
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
                        <span class="procedure-badge">💉 PROCEDURE MANAGEMENT</span>
                        <h4 class="text-uppercase">CathLab Procedure Records</h4>
                        <p class="text-muted">View and manage all CathLab procedure records</p>
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
                    $stats = $cathLab->getCathLabProcedureStats();
                } catch(Exception $e) {
                    $stats = array('total_procedures' => 0, 'total_items' => 0, 'total_quantity' => 0, 'total_cost' => 0);
                }
                ?>
                <div class="row">
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="value"><?= $stats['total_procedures'] ?? 0 ?></div>
                            <div class="label">Total Procedures</div>
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
                            <div class="label">Total Cost</div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <a href="cathlab_procedure.php" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Add New Procedure
                        </a>
                        <a href="cathlab_store_dashboard.php" class="btn btn-info">
                            <i class="fa fa-arrow-left"></i> Back to Dashboard
                        </a>
                    </div>
                </div>

                <!-- Procedure List Table -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header cathlab">
                                <h5><i class="fa fa-list"></i> All Procedure Records</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="procedureTable" class="table table-striped table-bordered" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Procedure Code</th>
                                                <th>Date & Time</th>
                                                <th>Patient Name</th>
                                                <th>Doctor</th>
                                                <th>Procedure Type</th>
                                                <th>Items</th>
                                                <th>Quantity</th>
                                                <th>Cost</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            if($procedures_result && mysqli_num_rows($procedures_result) > 0) {
                                                while($proc = mysqli_fetch_assoc($procedures_result)) {
                                                    // Status badge
                                                    $status_badge = 'badge-pending';
                                                    if($proc['status'] == 'completed') $status_badge = 'badge-completed';
                                                    if($proc['status'] == 'cancelled') $status_badge = 'badge-cancelled';
                                            ?>
                                                <tr>
                                                    <td><strong><?= htmlspecialchars($proc['procedure_code']) ?></strong></td>
                                                    <td>
                                                        <?= date('d-m-Y', strtotime($proc['procedure_date'])) ?>
                                                        <?php if($proc['procedure_time']): ?>
                                                            <br><small><?= date('h:i A', strtotime($proc['procedure_time'])) ?></small>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?= htmlspecialchars($proc['patient_name']) ?></td>
                                                    <td><?= htmlspecialchars($proc['doctor_name']) ?></td>
                                                    <td><?= htmlspecialchars($proc['procedure_type']) ?></td>
                                                    <td class="text-center"><?= $proc['total_items'] ?? 0 ?></td>
                                                    <td class="text-center"><?= number_format($proc['total_quantity'] ?? 0, 2) ?></td>
                                                    <td class="text-right"><strong>₹<?= number_format($proc['total_cost'] ?? 0, 2) ?></strong></td>
                                                    <td>
                                                        <span class="<?= $status_badge ?>"><?= ucfirst($proc['status']) ?></span>
                                                    </td>
                                                    <td class="action-buttons">
                                                        <a href="view_cathlab_procedure.php?id=<?= $proc['id'] ?>" 
                                                           class="btn btn-sm btn-info" 
                                                           title="View Details">
                                                            <i class="fa fa-eye"></i> View
                                                        </a>
                                                        <a href="print_cathlab_procedure.php?id=<?= $proc['id'] ?>" 
                                                           class="btn btn-sm btn-secondary" 
                                                           target="_blank"
                                                           title="Print Procedure">
                                                            <i class="fa fa-print"></i> Print
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php 
                                                }
                                            } else {
                                            ?>
                                                <tr>
                                                    <td colspan="10" class="text-center">
                                                        <p class="text-muted mt-3 mb-3">
                                                            <i class="fa fa-inbox fa-3x"></i><br><br>
                                                            No procedure records found. Click "Add New Procedure" to create one.
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
            $('#procedureTable').DataTable({
                "order": [[1, "desc"]], // Sort by date descending
                "pageLength": 25,
                "language": {
                    "search": "Search Procedures:",
                    "lengthMenu": "Show _MENU_ procedures per page",
                    "info": "Showing _START_ to _END_ of _TOTAL_ procedures",
                    "infoEmpty": "No procedures available",
                    "infoFiltered": "(filtered from _MAX_ total procedures)"
                }
            });
        });
    </script>

</body>

</html>
