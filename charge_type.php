<?php
require_once(__DIR__ . '/model/session_config.php');
// print_r($_SESSION);exit;
include('model/masterClass.php');
$newMaster = new master;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Charge Category List</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" ></script>
   
    
    <!-- DataTables -->
    <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/datatables.net-select-bs4/css//select.bootstrap4.min.css" rel="stylesheet" type="text/css" />

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">



    <style>
     #notification {
            position: absolute;
            top: 90px;
           
            right:2px;
            z-index: 99;
        }
        .bootstrap-select .btn-light{
            background: #f7f7f7;
    border-color: #cbcbcb;
    color: #000 !important;
        }
        .bootstrap-select .btn-light:hover{
            background: #f7f7f7;
    border-color: #cbcbcb;
    color: #000 !important;
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

    <div aria-live="polite" id="notification" aria-atomic="true" >
<?php if(isset($_REQUEST['success']))
{ ?>
    
<div class="alert alert-success solid alert-dismissible fade show">
                                    <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span>
                                    </button>
                                    <strong> Data is addedd successfully!</strong> 
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
        <div class="col-md-6">
            <h4 class="text-uppercase">Charge Types </h4>
        </div>
        <div class="col-md-6">
            <!-- <button class="btn btn-sm btn-success float-right " data-toggle="modal" data-target="#addnewcharge"> Add Charge Category</button> -->
        </div>
    </div>
  
  <div class="card">
    <div class="card-body">
    <div class="row">
                  
        <div class="col-md-12 mb-3">
            <h6 class="text-uppercase">Select Criteria</h6>
    
    </div>
    </div>
        <form method="POST"action="controller/processmaster.php">
    <div class="row d-flex align-items-center">
           
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Name</label>
                    <input type="text" class="form-control" name="chargename" id="chargename">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Select Module</label>
                  
                                                                                                                                     <select  name="lab_test[]" multiple  id="lab_test[]"  class="form-control selectpicker"  data-live-search="true">
                                                                                                                                        <option value="Appointment">Appointment</option>
                                                                                                                                        <option value="OPD">OPD</option>
                                                                                                                                        <option value="IPD">IPD</option>
                                                                                                                                        <option value="Pathology">Pathology</option>
                                                                                                                                        <option value="Radiology">Radiology</option>
                                                                                                                                        <option value="Blood Bank">Blood Bank</option>
                                                                                                                                        <option value="Ambulance">Ambulance</option>
                                                                                                                                        
</select>

                </div>
            </div>
            </div>
            <div class="row">
            <div class="col-md-12">
                <button type="submit" name="submit" value="charge_type" class="btn btn-success float-right">Save</button>
            </div>
        </div>
        </form>
    </div>
  </div>
    
  <div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <h6>Charge Type List</h6>
                <hr>
            </div>
        </div>
    <table id="datatable-buttons" class="table table-hoverde display text-center">
            <thead>
                <tr>
                
                    <th>Sl No.</th>
                    <th>Charge Type</th>
                    <th>Appointment</th>
                    <th>OPD</th>
                    <th>IPD</th>
                    <th>Pathology</th>
                    <th>Radiology</th>
                    <th>Blood Bank</th>
                    <th>Ambulance</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>                       <?php 
                                                $sn=1;
                                                $res=$newMaster->getChargeType();
                                                while($row=mysqli_fetch_assoc($res)) 
                                                {
                                                ?>
                                                                             <tr>
                                                                                        <td><?php echo $sn; ?></td>
                                                                                        <td><?php echo $row['charge_name']; ?></td>
                                                                                        <td><input <?php if($row['appointment']==1) { echo 'checked'; } ?> type="checkbox"  /></td>
                                                                                        <td><input <?php if($row['opd']==1) { echo 'checked'; } ?> type="checkbox" /></td>
                                                                                        <td><input <?php if($row['ipd']==1) { echo 'checked'; } ?> type="checkbox" /></td>
                                                                                        <td><input <?php if($row['pathology']==1) { echo 'checked'; } ?> type="checkbox"  /></td>
                                                                                        <td><input <?php if($row['radiology']==1) { echo 'checked'; } ?> type="checkbox"  /></td>
                                                                                        <td><input <?php if($row['blood_bank']==1) { echo 'checked'; } ?> type="checkbox" /></td>
                                                                                        <td><input <?php if($row['ambulance']==1) { echo 'checked'; } ?> type="checkbox"  /></td>
                                                                                       

                                                                                        <td class="text-center">

                                                <a  class="btn btn-default btn-xs editcharge"  data-toggle="tooltip" title="Edit" ;>
                                                        <i class="fa fa-edit"></i>
                                                    </a>

                                                                                                    <a  class="btn btn-default text-danger btn-xs" data-toggle="tooltip" title="Delete" ;>
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                                                            </td>
                                        </tr>
                                        <?php $sn++; } ?>
            </tbody>
        </table>
    </div>
  </div>
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
    <!-- Required vendors -->
    <script src="./vendor/global/global.min.js"></script>
    <script src="./js/quixnav-init.js"></script>
    <script src="./js/custom.min.js"></script>

    

    <!-- Required datatable js -->
    <script src="assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
        <!-- Buttons examples -->
        <script src="assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
        <script src="assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
        <script src="assets/libs/jszip/jszip.min.js"></script>
        <script src="assets/libs/pdfmake/build/pdfmake.min.js"></script>
        <script src="assets/libs/pdfmake/build/vfs_fonts.js"></script>
        <script src="assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
        <script src="assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
        <script src="assets/libs/datatables.net-buttons/js/buttons.colVis.min.js"></script>

        <script src="assets/libs/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
        <script src="assets/libs/datatables.net-select/js/dataTables.select.min.js"></script>
        

        <!-- Datatable init js -->
        <script src="assets/js/pages/datatables.init.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>



  

</body>

</html>