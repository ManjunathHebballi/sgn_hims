<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('model/bloodDonorClass.php');

// Check authorization
if(!isset($_SESSION['loginid'])) {
    header("Location: login.php");
    exit;
}

// Get filter status
$filter_status = $_GET['status'] ?? '';
$search_term = $_GET['search'] ?? '';
$page = (int)($_GET['page'] ?? 1);
$limit = 50;
$offset = ($page - 1) * $limit;

$donor = new bloodDonor();

// Fetch donors
if ($search_term) {
    $donors_result = $donor->searchDonor($search_term);
} else {
    $donors_result = $donor->getAllDonors($filter_status ?: null, $limit, $offset);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#319197">

    <title>Blood Donor Management</title>
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
    <link rel="stylesheet" href="vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <link href="css/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
    <style>
        #notification {
            position: fixed;
            top: 90px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
        }
        .content-body {
            margin-top: 120px;
        }
        .page-title-box {
            padding: 20px 0;
            margin-bottom: 20px;
            border-bottom: 1px solid #e9ecef;
        }
        .page-title-box h4 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
            color: #495057;
        }
        .search-filter-card {
            border: none;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }
        .search-filter-card .card-body {
            padding: 20px;
        }
        .filter-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 10px;
            display: block;
        }
        .status-badge {
            padding: 6px 12px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }
        .status-eligible {
            background-color: #d4edda;
            color: #155724;
        }
        .status-temp-deferred {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-perm-deferred {
            background-color: #f8d7da;
            color: #721c24;
        }
        .status-registered {
            background-color: #e2e3e5;
            color: #383d41;
        }
        .donors-table-card {
            border: none;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }
        .table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            color: #495057;
            padding: 12px;
        }
        .table tbody td {
            vertical-align: middle;
            padding: 12px;
        }
        .btn-action-group {
            white-space: nowrap;
        }
        .btn-action-group .btn {
            margin-right: 5px;
        }
        .empty-state-icon {
            font-size: 64px;
            color: #ced4da;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->

    <div aria-live="polite" id="notification" aria-atomic="true">
        <?php if(isset($_GET['success'])): ?>
            <div class="alert alert-success solid alert-dismissible fade show">
                <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span></button>
                <strong>Success!</strong> <?php echo htmlspecialchars($_GET['success']); ?>
            </div>
        <?php endif; ?>

        <?php if(isset($_GET['error'])): ?>
            <div class="alert alert-danger solid alert-dismissible fade show">
                <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span></button>
                <strong>Error!</strong> <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>
    </div>

    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">

        <?php include('leftbar.php'); ?>

        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">

            <div class="container-fluid">
                <!-- Page Title -->
                <div class="row page-title-box">
                    <div class="col-md-8">
                        <h4>
                            <i class="ri-heart-pulse-fill text-danger"></i> Blood Donor Management
                        </h4>
                        <p class="text-muted mb-0">Manage donor registration, screening, and eligibility tracking</p>
                    </div>
                    <div class="col-md-4 text-right">
                        <a href="add_blood_donor.php" class="btn btn-success">
                            <i class="ri-user-add-fill"></i> Register New Donor
                        </a>
                    </div>
                </div>

                <!-- Search & Filter Section -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card search-filter-card">
                            <div class="card-body">
                                <form method="GET" class="row align-items-end">
                                    <div class="col-md-7">
                                        <label class="filter-label"><i class="ri-search-line"></i> Search Donors</label>
                                        <input type="text" name="search" class="form-control" 
                                               placeholder="Search by name, mobile, or Donor ID..." 
                                               value="<?php echo htmlspecialchars($search_term); ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="filter-label"><i class="ri-filter-3-line"></i> Filter by Status</label>
                                        <select name="status" class="form-control">
                                            <option value="">All Donors</option>
                                            <option value="Registered" <?php echo $filter_status == 'Registered' ? 'selected' : ''; ?>>In Progress</option>
                                            <option value="Eligible" <?php echo $filter_status == 'Eligible' ? 'selected' : ''; ?>>Eligible</option>
                                            <option value="Deferred_Temporary" <?php echo $filter_status == 'Deferred_Temporary' ? 'selected' : ''; ?>>Temp Deferred</option>
                                            <option value="Deferred_Permanent" <?php echo $filter_status == 'Deferred_Permanent' ? 'selected' : ''; ?>>Perm Deferred</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary btn-block">
                                            <i class="ri-search-line"></i> Search
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Donors Table -->
                <div class="row">
                    <div class="col-md-12">
                        <?php if ($donors_result && $donors_result->num_rows > 0): ?>
                            <div class="card donors-table-card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="ri-list-check"></i> Donor List 
                                        <span class="badge badge-primary ml-2"><?php echo $donors_result->num_rows; ?> records</span>
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Donor ID</th>
                                                    <th>Name</th>
                                                    <th>Age/Gender</th>
                                                    <th>Blood Group</th>
                                                    <th>Mobile</th>
                                                    <th>Status</th>
                                                    <th>Registered</th>
                                                    <th class="text-center">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while($row = $donors_result->fetch_assoc()): ?>
                                                    <tr>
                                                        <td>
                                                            <strong class="text-primary"><?php echo htmlspecialchars($row['donor_unique_id']); ?></strong>
                                                        </td>
                                                        <td>
                                                            <strong><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></strong>
                                                        </td>
                                                        <td>
                                                            <?php echo $row['age']; ?> Y / <?php echo $row['gender'][0]; ?>
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-light badge-lg">
                                                                <?php echo htmlspecialchars($row['blood_group'] ?? 'Unknown'); ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <a href="tel:<?php echo htmlspecialchars($row['mobile_number']); ?>" class="text-dark">
                                                                <i class="ri-phone-line"></i> <?php echo htmlspecialchars($row['mobile_number']); ?>
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            $status = $row['current_status'];
                                                            $status_class = '';
                                                            $status_icon = '';
                                                            
                                                            switch($status) {
                                                                case 'Eligible':
                                                                    $status_class = 'status-eligible';
                                                                    $status_icon = '✓';
                                                                    break;
                                                                case 'Deferred_Temporary':
                                                                    $status_class = 'status-temp-deferred';
                                                                    $status_icon = '⏱';
                                                                    break;
                                                                case 'Deferred_Permanent':
                                                                    $status_class = 'status-perm-deferred';
                                                                    $status_icon = '✗';
                                                                    break;
                                                                case 'Registered':
                                                                    $status_class = 'status-registered';
                                                                    $status_icon = '◦';
                                                                    break;
                                                                default:
                                                                    $status_class = 'status-registered';
                                                                    $status_icon = '◦';
                                                            }
                                                            ?>
                                                            <span class="status-badge <?php echo $status_class; ?>">
                                                                <?php echo $status_icon; ?> <?php echo str_replace('_', ' ', $status); ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <small><?php echo date('d M Y', strtotime($row['created_date'])); ?></small>
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="btn-action-group">
                                                                <a href="blood_donor_profile.php?donor_id=<?php echo $row['donor_id']; ?>" 
                                                                   class="btn btn-primary btn-sm" title="View Profile">
                                                                    <i class="ri-eye-line"></i>
                                                                </a>
                                                                <?php if ($row['current_status'] == 'Registered'): ?>
                                                                    <a href="blood_donor_medical_history.php?donor_id=<?php echo $row['donor_id']; ?>" 
                                                                       class="btn btn-info btn-sm" title="Continue Screening">
                                                                        <i class="ri-arrow-right-line"></i>
                                                                    </a>
                                                                <?php endif; ?>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Pagination -->
                            <?php if (!$search_term && $page > 1): ?>
                                <div class="text-center mt-3">
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination">
                                            <li class="page-item">
                                                <a class="page-link" href="?page=<?php echo $page-1; ?><?php echo $filter_status ? '&status=' . $filter_status : ''; ?>">Previous</a>
                                            </li>
                                            <li class="page-item active">
                                                <span class="page-link"><?php echo $page; ?></span>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="?page=<?php echo $page+1; ?><?php echo $filter_status ? '&status=' . $filter_status : ''; ?>">Next</a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            <?php endif; ?>

                        <?php else: ?>
                            <!-- Empty State -->
                            <div class="card donors-table-card">
                                <div class="card-body text-center py-5">
                                    <div class="empty-state-icon">
                                        <i class="ri-inbox-line"></i>
                                    </div>
                                    <h4 class="text-muted">No Donors Found</h4>
                                    <p class="text-muted mb-4">
                                        <?php if ($search_term): ?>
                                            No donors matching "<strong><?php echo htmlspecialchars($search_term); ?></strong>"
                                        <?php elseif ($filter_status): ?>
                                            No donors with status: <strong><?php echo str_replace('_', ' ', $filter_status); ?></strong>
                                        <?php else: ?>
                                            Start by registering your first blood donor.
                                        <?php endif; ?>
                                    </p>
                                    <?php if ($search_term || $filter_status): ?>
                                        <a href="blood_donor_list.php" class="btn btn-secondary mr-2">
                                            <i class="ri-refresh-line"></i> Clear Filters
                                        </a>
                                    <?php endif; ?>
                                    <a href="add_blood_donor.php" class="btn btn-success">
                                        <i class="ri-user-add-fill"></i> Register First Donor
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- **********************************
            Content body end
        ******************************* -->

    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!-- jQuery -->
    <script src="vendor/global/global.min.js"></script>
    <script src="vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
    <script src="vendor/chart.js/Chart.bundle.min.js"></script>
    <script src="js/custom.min.js"></script>
    <script src="js/deznav-init.js"></script>
    <script src="css/sweetalert2/sweetalert2.min.js"></script>

</body>
</html>
