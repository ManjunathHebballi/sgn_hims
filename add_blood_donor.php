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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#319197">

    <title>Blood Donor Registration</title>
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
    <link href="css/style.css" rel="stylesheet">
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
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h4 class="text-uppercase"><i class="ri-user-add-fill"></i> Blood Donor Registration - Stage 1</h4>
                        <small class="text-muted">Personal Information</small>
                    </div>
                    <div class="col-md-6">
                        <a href="blood_donor_list.php" class="btn btn-secondary float-lg-right ml-lg-3 mt-lg-3">
                            <i class="ri-arrow-left-line"></i> Back to List
                        </a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-10 offset-md-1">
                        <div class="card">
                            <div class="card-header border-bottom">
                                <h5 class="card-title mb-0"><i class="ri-user-2-line"></i> Personal Information</h5>
                                <small class="text-muted">Please provide accurate personal information</small>
                            </div>
                            <div class="card-body">
                                <form action="controller/processBloodDonor.php" method="POST" id="donorForm">
                                    <input type="hidden" name="action" value="registerDonor">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>First Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="first_name" required placeholder="Enter first name" maxlength="100">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Last Name</label>
                                                <input type="text" class="form-control" name="last_name" placeholder="Enter last name" maxlength="100">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Date of Birth <span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" name="date_of_birth" required max="<?php echo date('Y-m-d', strtotime('-18 years')); ?>">
                                                <small class="form-text text-muted">Must be 18+ years old</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Gender <span class="text-danger">*</span></label>
                                                <select class="form-control" name="gender" required>
                                                    <option value="">-- Select Gender --</option>
                                                    <option value="Male">Male</option>
                                                    <option value="Female">Female</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Blood Group <span class="text-danger">*</span></label>
                                                <select class="form-control" name="blood_group" required>
                                                    <option value="">-- Select Blood Group --</option>
                                                    <option value="O+">O+</option>
                                                    <option value="O-">O-</option>
                                                    <option value="A+">A+</option>
                                                    <option value="A-">A-</option>
                                                    <option value="B+">B+</option>
                                                    <option value="B-">B-</option>
                                                    <option value="AB+">AB+</option>
                                                    <option value="AB-">AB-</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Mobile Number <span class="text-danger">*</span></label>
                                                <input type="tel" class="form-control" name="mobile_number" required placeholder="10-digit number" pattern="[0-9]{10}" maxlength="10">
                                                <small class="form-text text-muted">Must be 10 digits</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Alternate Mobile</label>
                                        <input type="tel" class="form-control" name="alternate_mobile" placeholder="Optional" pattern="[0-9]{10}" maxlength="10">
                                    </div>

                                    <div class="form-group">
                                        <label>Email Address</label>
                                        <input type="email" class="form-control" name="email" placeholder="your.email@example.com">
                                    </div>

                                    <hr class="my-3">

                                    <div class="form-group">
                                        <label>Address <span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="address" required rows="3" placeholder="Street address" maxlength="500"></textarea>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>City</label>
                                                <input type="text" class="form-control" name="city" placeholder="City" maxlength="50">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>State</label>
                                                <input type="text" class="form-control" name="state" placeholder="State" maxlength="50">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Pincode</label>
                                                <input type="text" class="form-control" name="pincode" placeholder="6 digits" pattern="[0-9]{6}" maxlength="6">
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="my-3">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Government ID Type</label>
                                                <select class="form-control" name="id_proof_type">
                                                    <option value="">-- None / Not Provided --</option>
                                                    <option value="Aadhar">Aadhar</option>
                                                    <option value="Passport">Passport</option>
                                                    <option value="Driving License">Driving License</option>
                                                    <option value="Voter ID">Voter ID</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>ID Number</label>
                                                <input type="text" class="form-control" name="id_proof_number" placeholder="ID number" maxlength="50">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Notes</label>
                                        <textarea class="form-control" name="notes" rows="2" placeholder="Any additional notes (optional)" maxlength="1000"></textarea>
                                    </div>

                                    <hr class="my-3">

                                    <div class="form-group text-right">
                                        <button type="submit" class="btn btn-success mr-2">
                                            <i class="ri-check-line"></i> Continue to Medical History
                                        </button>
                                        <a href="blood_donor_list.php" class="btn btn-secondary">
                                            <i class="ri-close-line"></i> Cancel
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
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
    <script src="js/custom.min.js"></script>
    <script src="js/deznav-init.js"></script>
    <script src="css/sweetalert2/sweetalert2.min.js"></script>

    <script>
        // Age validation
        document.querySelector('input[name="date_of_birth"]').addEventListener('change', function() {
            var birthDate = new Date(this.value);
            var today = new Date();
            var age = today.getFullYear() - birthDate.getFullYear();
            
            if (age < 18) {
                swal("Invalid Age", "Donor must be 18 years or older", "warning");
                this.value = '';
            }
        });

        // Mobile number validation
        document.querySelector('input[name="mobile_number"]').addEventListener('change', function() {
            if (!/^\d{10}$/.test(this.value.trim())) {
                swal("Invalid Mobile", "Mobile number must be 10 digits", "warning");
                this.value = '';
            }
        });
    </script>

</body>
</html>        // Mobile number format validation
        document.querySelector('input[name="mobile_number"]').addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);
        });
    </script>
</body>
</html>
