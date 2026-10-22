<?php 
require_once(__DIR__ . '/model/session_config.php');
error_reporting( E_ALL ); 
 ini_set('display_errors', 1);
date_default_timezone_set("Asia/Kolkata");  
include('model/userClass.php');
$newOP = new addclass;
$dept=$newOP->selectDept();
$user=$newOP->getUser($_REQUEST['id']);
$res=mysqli_fetch_assoc($user);
// Fetch active lab departments for dropdown
$labDept = $newOP->getLabDepartments();
$stores = $newOP->getStores();
// Decode JSON lab_department if exists
$selectedLabDepts = array();
if(isset($res['lab_department']) && !empty($res['lab_department'])) {
    $decoded = json_decode($res['lab_department'], true);
    if(is_array($decoded)) {
        $selectedLabDepts = $decoded;
    } else {
        // If not JSON, treat as single value
        $selectedLabDepts = array($res['lab_department']);
    }
}

$selectedStore = isset($res['store_id']) ? $res['store_id'] : '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Edit User</title>
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
            <strong>Success!</strong>Updated Successfully..
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
                        <h4 class="text-uppercase">Edit User</h4>
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
                                                <input class="form-control" type="date" name="dob" id="dob"
                                                    value='<?= $res['dob']?>'>


                                            </div>
                                        </div>



                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">First Name</label>
                                                <input type="text" class="form-control " name="fname" id="fname"
                                                    placeholder="Enter First Name" value='<?= $res['name']?>'>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Last Name</label>
                                                <input type="text" class="form-control " name="lname" id="lname"
                                                    placeholder="Enter Last Name" value='<?= $res['last_name']?>'>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Designation</label>
                                                <input type="text" class="form-control " name="designation"
                                                    id="designation" placeholder="Enter Designation"
                                                    value='<?= $res['designation']?>'>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Contact No.</label>
                                                <input type="text" class="form-control " name="contact" id="contact"
                                                    placeholder="Enter Mobile No." value='<?= $res['mobile']?>'>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"> Sex</label>

                                                <select class="form-control " name="sex" id="sex">
                                                    <option value="">Select Gender</option>
                                                    <option value="male"
                                                        <?php if($res['gender']=='male') echo 'selected';?>>Male
                                                    </option>
                                                    <option value="female"
                                                        <?php if($res['gender']=='female') echo 'selected';?>>Female
                                                    </option>
                                                    <option value="other"
                                                        <?php if($res['gender']=='other') echo 'selected';?>>Other
                                                    </option>
                                                </select>
                                            </div>
                                        </div>



                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"> Qualification</label>

                                                <input type="text" class="form-control" name="qualification"
                                                    id="qualification" aria-describedby="emailHelp"
                                                    value='<?= $res['qualification']?>'
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
                                                    <option value="<?= $row['id']?>"
                                                        <?php if($res['department']==$row['id']) echo 'selected';?>>
                                                        <?= $row['dept_name']?></option>
                                                    <?php } ?>


                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"> Role</label>

                                                <select class="form-control " name="role" id="role"
                                                    onchange="toggleLabDepartment(this.value)">
                                                    <?php 
                    if($_SESSION['role']=='Lab Admin')
                    {
                    ?>
                                                    <option value="">Select Role</option>
                                                    <option value="Lab"
                                                        <?php if($res['role']=='Lab') echo 'selected'; ?>>Lab</option>
                                                    <option value="Lab Admin"
                                                        <?php if($res['role']=='Lab Admin') echo 'selected'; ?>>Lab
                                                        Admin</option>
                                                        <option value="Phlebotomy"
                                                        <?php if($res['role']=='Phlebotomy') echo 'selected'; ?>>
                                                        Phlebotomy</option>
                                                    <?php 
                    }
                    else{
                    ?>
                                                    <option value="">Select Role</option>
                                                    <option value="MRD"
                                                        <?php if($res['role']=='MRD') echo 'selected'; ?>>MRD</option>
                                                    <option value="IT"
                                                        <?php if($res['role']=='IT') echo 'selected'; ?>>IT</option>
                                                    <option value="Doctor"
                                                        <?php if($res['role']=='Doctor') echo 'selected'; ?>>Doctor
                                                    </option>
                                                    <option value="Lab"
                                                        <?php if($res['role']=='Lab') echo 'selected'; ?>>Lab</option>
                                                        <option value="Lab Admin"
                                                        <?php if($res['role']=='Lab Admin') echo 'selected'; ?>>Lab Admin</option>
                                                   <option value="Phlebotomy"
                                                        <?php if($res['role']=='Phlebotomy') echo 'selected'; ?>>Phlebotomy</option>    
                                                        <option value="Receptionist"
                                                        <?php if($res['role']=='Receptionist') echo 'selected'; ?>>
                                                        Receptionist</option>
                                                        <option value="Store"
                                                            <?php if($res['role']=='Store') echo 'selected'; ?>>Store
                                                        </option>
                                                    <option value="Pharmacist"
                                                        <?php if($res['role']=='Pharmacist') echo 'selected'; ?>>
                                                        Pharmacist</option>
                                                    <option value="Radiology"
                                                        <?php if($res['role']=='Radiology') echo 'selected'; ?>>
                                                        Radiology</option>
                                                    <option value="OPD Register"
                                                        <?php if($res['role']=='OPD Register') echo 'selected'; ?>>OPD
                                                        Register</option>
                                                    <option value="Billing"
                                                        <?php if($res['role']=='Billing') echo 'selected'; ?>>Billing
                                                    </option>
                                                    <option value="Operation Theater"
                                                        <?php if($res['role']=='Operation Theater') echo 'selected'; ?>>
                                                        Operation Theater</option>
                                                    <option value="Generated"
                                                        <?php if($res['role']=='Generated') echo 'selected'; ?>>
                                                        Generated</option>
                                                     <option value="Admin"
                                                        <?php if($res['role']=='Admin') echo 'selected'; ?>>Admin</option>

                                                    <option value="Accountant"
                                                        <?php if($res['role']=='Accountant') echo 'selected'; ?>>
                                                        Accountant</option>
                                                    <option value="Nursing"
                                                        <?php if($res['role']=='Nursing') echo 'selected'; ?>>
                                                        Nursing</option>
                                                    <?php } ?>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3" id="lab_dept_field" style="display:none;">
                                            <div class="form-group">
                                                <label for="lab_department">Lab Department <span
                                                        style="color:red">*</span></label>
                                                <select class="form-control text-uppercase selectpicker" name="lab_department[]" id="lab_department" multiple data-live-search="true" data-live-search-style="contains">
                                                    <?php while($lab_row = mysqli_fetch_assoc($labDept)) { ?>
                                                    <option value="<?= $lab_row['id']?>"
                                                        <?php if(in_array($lab_row['id'], $selectedLabDepts)) echo 'selected'; ?>>
                                                        <?= $lab_row['dept_name']?>
                                                    </option>
                                                    <?php } ?>
                                                </select>
                                                <small class="form-text text-muted">Hold Ctrl (Windows) or Cmd (Mac) to select multiple</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3" id="store_field" style="display:none;">
                                            <div class="form-group">
                                                <label for="store">Store <span style="color:red">*</span></label>
                                                <select class="form-control text-uppercase selectpicker" name="store" id="store" data-live-search="true" data-live-search-style="contains">
                                                    <option value="">Select Store</option>
                                                    <?php while($store_row = mysqli_fetch_assoc($stores)) { ?>
                                                    <option value="<?= $store_row['id']?>"
                                                        <?php if($selectedStore == $store_row['id']) echo 'selected'; ?>>
                                                        <?= $store_row['store_name']?>
                                                    </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"> Email</label>

                                                <input type="text" class="form-control" name="email" id="email"
                                                    aria-describedby="emailHelp" placeholder="Enter Email "
                                                    value='<?= $res['email']?>'>

                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"> Password</label>

                                                <input type="text" class="form-control no-uppercase" name="pass" id="pass"
                                                    aria-describedby="emailHelp" placeholder="Enter Password">

                                            </div>
                                        </div>





                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">

                                            <input type='hidden' name='id' value='<?= $_REQUEST['id']?>'>
                                            <button type="submit" class="btn btn-primary btn-lg float-right"
                                                name="submit" value="Edit">Edit</button>

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
        var field = document.getElementById('lab_dept_field');
        var select = $('#lab_department');
        var storeField = document.getElementById('store_field');
        var storeSelect = $('#store');
        if (!field || !select.length || !storeField || !storeSelect.length) return;

        if (role === 'Lab' || role === 'Lab Admin') {
            field.style.display = 'block';
            select.prop('required', true);
            select.selectpicker('refresh');

            storeField.style.display = 'none';
            storeSelect.prop('required', false);
            storeSelect.val('').trigger('change');
            storeSelect.selectpicker('refresh');
        } else if (role === 'Store') {
            storeField.style.display = 'block';
            storeSelect.prop('required', true);
            storeSelect.selectpicker('refresh');

            field.style.display = 'none';
            select.prop('required', false);
            select.selectpicker('deselectAll');
        } else {
            field.style.display = 'none';
            select.prop('required', false);
            select.selectpicker('deselectAll');

            storeField.style.display = 'none';
            storeSelect.prop('required', false);
            storeSelect.val('').trigger('change');
            storeSelect.selectpicker('refresh');
        }
    }
    
    $(document).ready(function() {
        $('.selectpicker').selectpicker();
        var role = document.getElementById('role');
        if (role) {
            toggleLabDepartment(role.value);
        }
    });
    </script>






</body>

</html>