<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/userClass.php');
$newOP = new addclass;
$dept=$newOP->selectDept();
$labDept=$newOP->getLabDepartments();
$stores=$newOP->getStores();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Add User</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <!-- Sweet Alert-->
    <link href="css/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
    <!-- Bootstrap Select -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
 <link href="assets/libs/datatables.net-select-bs4/css/select.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <style>
    #notification {
        position: absolute;
        top: 90px;

        right: 2px;
        z-index: 99;
    }
    
    .bootstrap-select .btn-light {
        color: black !important;
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
        <?php if(isset($_REQUEST['success']))
{ ?>

        <div class="alert alert-success solid alert-dismissible fade show">
            <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i
                        class="mdi mdi-close"></i></span>
            </button>
            <strong>Success!</strong> New User Added Successfully..
        </div>
        <?php   }
                            ?>
    </div>

    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">






        <?php include ('leftbar.php')?>


        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <!-- row -->



            <div class="container-fluid">
                <div class="row mb-3">
                    <div class="col-md-12">
                        <h4 class="text-uppercase">Add New User</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="controller/prodcessAddUser.php" method='POST'>

                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="dob"> Date of Birth</label>
                                                <input class="form-control" type="date" name="dob" id="dob">


                                            </div>
                                        </div>



                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">First Name</label>
                                                <input type="text" class="form-control " name="fname" id="fname"
                                                    placeholder="Enter First Name">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Last Name</label>
                                                <input type="text" class="form-control " name="lname" id="lname"
                                                    placeholder="Enter Last Name">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Designation</label>
                                                <input type="text" class="form-control " name="designation"
                                                    id="designation" placeholder="Enter Designation">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Contact No.</label>
                                                <input type="text" class="form-control " name="contact" id="contact"
                                                    placeholder="Enter Mobile No.">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"> Sex</label>

                                                <select class="form-control " name="sex" id="sex">
                                                    <option value="">Select Gender</option>
                                                    <option value="male">Male</option>
                                                    <option value="female">Female</option>
                                                    <option value="other">Other</option>
                                                </select>
                                            </div>
                                        </div>



                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"> Qualification</label>

                                                <input type="text" class="form-control" name="qualification"
                                                    id="qualification" aria-describedby="emailHelp"
                                                    placeholder="Enter Patient Ocupation">

                                            </div>
                                        </div>



                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"> Department</label>

                                                <select class="form-control " name="dept" id="dept">
                                                    <option value="">Select Department</option>



                                                    <?php 

// echo "<option value=''>".$dept.'</option>';
while($row=mysqli_fetch_assoc($dept))

{
?>
                                                    <option value="<?= $row['id']?>"><?= $row['dept_name']?></option>
                                                    <?php } ?>


                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"> Role</label>

                                                <select class="form-control text-uppercase" name="role" id="role"
                                                    onchange="toggleLabDepartment(this.value)">
                                                    <?php 
                    if($_SESSION['role']=='Lab Admin')
                    {
                    ?>
                                                    <option value="">Select Role</option>
                                                    <option value="Lab">Lab</option>
                                                    <option value="Lab Admin">Lab Admin</option>
                                                    <option value="Phlebotomy">Phlebotomy</option>  
                                                    <?php 
                    }
                    else{
                    ?>
                                                    <option value="">Select Role</option>
                                                    <option value="MRD">MRD</option>
                                                    <option value="IT">IT</option>
                                                    <option value="Doctor">Doctor</option>
                                                    <option value="Receptionist">Receptionist</option>
                                                    <option value="Store">Store</option>
                                                    <option value="Pharmacist">Pharmacist</option>
                                                    <option value="Lab">Lab</option>
                                                    <option value="Lab Admin">Lab Admin</option>
                                                    <option value="Phlebotomy">Phlebotomy</option>
                                                    <option value="Radiology">Radiology</option>
                                                    <option value="Billing">Billing</option>
                                                    <option value="Operation Theater">Operation Theater</option>
                                                    <option value="OPD Register">OPD Register</option>
                                                    <option value="Generated">Generated</option>
                                                    <option value="Accountant">Accountant</option>
                                                    <option value="Nursing">Nursing</option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3" id="lab_dept_field" style="display:none;">
                                            <div class="form-group">
                                                <label for="lab_department">Lab Department <span
                                                        style="color: red">*</span></label>
                                                <select class="form-control text-uppercase selectpicker" name="lab_department[]" id="lab_department" multiple data-live-search="true" data-live-search-style="contains">
                                                    <?php 
                                                    while($lab_row = mysqli_fetch_assoc($labDept)) {
                                                    ?>
                                                    <option value="<?= $lab_row['id']?>">
                                                        <?= $lab_row['dept_name']?></option>
                                                    <?php 
                                                    }
                                                    ?>
                                                </select>
                                                <small class="form-text text-muted">Hold Ctrl (Windows) or Cmd (Mac) to select multiple</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3" id="store_field" style="display:none;">
                                            <div class="form-group">
                                                <label for="store">Store <span
                                                        style="color: red">*</span></label>
                                                <select class="form-control text-uppercase selectpicker" name="store" id="store">
                                                    <option value="">Select Store</option>
                                                    <?php 
                                                    while($store_row = mysqli_fetch_assoc($stores)) {
                                                    ?>
                                                    <option value="<?= $store_row['id']?>">
                                                        <?= $store_row['store_name']?></option>
                                                    <?php 
                                                    }
                                                    ?>
                                                </select>
                                                      </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"> Email</label>

                                                <input type="text" class="form-control" name="email" id="email"
                                                    aria-describedby="emailHelp" placeholder="Enter Email ">

                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"> Password</label>

                                                <input type="text" class="form-control" name="pass" id="pass"
                                                    aria-describedby="emailHelp" placeholder="Enter Password">

                                            </div>
                                        </div>





                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">


                                            <button type="submit" class="btn btn-primary btn-lg float-right"
                                                name="submit" value="submit">Save</button>

                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>


        </div>
        <!--**********************************
            Content body end
        ***********************************-->


        <!--**********************************
            Footer start
        ***********************************-->
        <?php include('footer.php') ?>
        <!--**********************************
            Footer end
        ***********************************-->

        <!--**********************************
           Support ticket button start
        ***********************************-->

        <!--**********************************
           Support ticket button end
        ***********************************-->


    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!--**********************************
        Scripts
    ***********************************-->
    <!--  vendors -->
    <script src="./vendor/global/global.min.js"></script>
    <script src="./js/quixnav-init.js"></script>
    <script src="./js/custom.min.js"></script>
    <!-- Bootstrap Select -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

    <script>
    function toggleLabDepartment(role) {
        var labDeptField = document.getElementById('lab_dept_field');
        var labDeptSelect = $('#lab_department');
        var storeField = document.getElementById('store_field');
        var storeSelect = $('#store');
        if (role === 'Lab' || role === 'Lab Admin') {
            labDeptField.style.display = 'block';
            labDeptSelect.prop('required', true);
            // Destroy and reinitialize selectpicker when showing
            labDeptSelect.selectpicker('destroy');
            labDeptSelect.selectpicker({
                liveSearch: true,
                liveSearchStyle: 'contains'
            });
            storeSelect.val('').trigger('change');
            storeSelect.prop('required', false);
            storeField.style.display = 'none';
            storeSelect.selectpicker('refresh');
        } 
        else if(role === 'Store') {
            storeField.style.display = 'block';
            storeSelect.prop('required', true);
            // Destroy and reinitialize selectpicker when showing
            storeSelect.selectpicker('destroy');
            storeSelect.selectpicker({
                liveSearch: true,
                liveSearchStyle: 'contains'
            });
            labDeptSelect.val([]).trigger('change');
            labDeptSelect.prop('required', false);
            labDeptField.style.display = 'none';
            labDeptSelect.selectpicker('refresh');
        }
        else {
            labDeptSelect.val([]).trigger('change');
            labDeptSelect.prop('required', false);
            labDeptField.style.display = 'none';
            labDeptSelect.selectpicker('refresh');
            storeSelect.val('').trigger('change');
            storeSelect.prop('required', false);
            storeField.style.display = 'none';
            storeSelect.selectpicker('refresh');
        }
    }
    
    $(document).ready(function() {
        $('.selectpicker').selectpicker({
            liveSearch: true,
            liveSearchStyle: 'contains'
        });
            toggleLabDepartment($('#role').val());
    });
    </script>

</body>

</html>