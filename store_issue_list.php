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

    <title>Store Issue List</title>
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
                        <h4 class="text-uppercase">📦 Store Issue List</h4>
                    </div>
                    <div class="col-md-4 text-right">
                        <a href="add_store_issue.php" class="btn btn-primary">
                            <i class="fa fa-plus"></i> New Issue
                        </a>
                    </div>
                </div>

                <?php if(isset($_GET['success']) && $_GET['success'] == 1): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>Success!</strong> Items issued successfully.
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
                                    <div class="col-md-4">
                                        <select class="form-control" id="filter_department">
                                            <option value="">All Departments</option>
                                           <?php 
                                                    $substores = $newStore->getSubstores();
                                                    if($substores) {
                                                        while($row = mysqli_fetch_assoc($substores)) {
                                                            echo "<option value='{$row['store_name']}'>{$row['store_name']}</option>";
                                                        }
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
                                    <table id="issueTable" class="display table table-striped table-bordered"
                                        style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>S.No</th>
                                                <th>Issue No</th>
                                                <th>Issue Date</th>
                                                <th>Department</th>
                                                <th>Requested By</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                $issues = $newStore->getIssues();
                                $sno = 1;
                                while($issue = mysqli_fetch_assoc($issues)) {
                                    $status_badge = '<span class="badge badge-success">ISSUED</span>';
                                    if($issue['status'] == 'CANCELLED') {
                                        $status_badge = '<span class="badge badge-danger">CANCELLED</span>';
                                    }
                                    
                                    echo "<tr>";
                                    echo "<td>{$sno}</td>";
                                    echo "<td>{$issue['issue_number']}</td>";
                                    echo "<td>" . date('d-M-Y', strtotime($issue['issue_date'])) . "</td>";
                                    echo "<td>{$issue['department']}</td>";
                                    echo "<td>{$issue['requested_by']}</td>";
                                    echo "<td>{$status_badge}</td>";
                                    echo "<td>
                                            <a href='view_store_issue.php?id={$issue['id']}' class='btn btn-info btn-sm' title='View'>
                                                <i class='fa fa-eye'></i>
                                            </a>
                                            <a href='print_store_issue.php?id={$issue['id']}' class='btn btn-secondary btn-sm' title='Print' target='_blank'>
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
        table = $('#issueTable').DataTable({
            "order": [
                [2, "desc"]
            ], // Order by issue date descending
            "pageLength": 25,
            "columnDefs": [{
                    "orderable": false,
                    "targets": 6
                } // Action column not sortable
            ]
        });
    });

    function filterTable() {
        var department = $('#filter_department').val();
        table.column(3).search(department).draw();
    }

    function resetFilter() {
        $('#filter_department').val('');
        table.search('').columns().search('').draw();
    }
    </script>

</body>

</html>