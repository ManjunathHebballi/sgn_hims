<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/opClass.php');
$newOP = new addOP;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Book Ambulance</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" ></script>
    <!-- Datatable -->
    <link href="./vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">

    <style>
     #notification {
            position: absolute;
            top: 90px;
           
            right:2px;
            z-index: 99;
        }

        

.table th, .table td{
    padding:15px!important;
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
            <h4 class="text-uppercase">Book Ambulance</h4>
        </div>
    </div>
    
   <div class="row">
<div class="col-xl-12">
   <div class="card">
      <div class="card-body">
         <div class="card-title"></div>
         <div class="row mt-1">
            <div class="col-xl-12">
               
                     <div class="card-title">
                        <h4 class="card-title">Issue</h4>
                     </div>
                     <hr>
                     <div class="row">
                        <div class="col-md-4">
                           <div class="form-group">
                              <label for="validationCustom01"> Type<span style="color: red;">*</span></label>
                              <select type="text" class="form-control" required="" wtx-context="7E2A67F3-D680-4EEB-8745-04799AAF2A61">
                                 <option value="">Select</option>
                                 <option value="ipd">IPD</option>
                                 <option value="external">External</option>
                              </select>
                           </div>
                        </div>
                        <div class="col-md-4">
                           <div class="form-group">
                              <label for="validationCustom01"> Search Ambulance<span style="color: red;">*</span></label>
                              <div class="select-search">
                                 <div class="">
                                    <select class="form-control" name="serach_amb" id="search_amb">
                                        <option>Search Ambulance</option>
                                    </select></div>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-4">
                           <div class="form-group"><label for="validationCustom01"> Patient Name<span style="color: red;">*</span></label><input type="text"  class="form-control" name="patient_name" id="patient_name" required="" value=""></div>
                        </div>
                        <div class="col-md-4">
                           <div class="form-group"><label for="patient_contact"> Patient Contact<span style="color: red;">*</span></label><input type="text"  class="form-control" name="patient_contact" id="patient_contact"  required="" value="" ></div>
                        </div>
                        <div class="col-md-4">
                           <div class="form-group"><label for="patient_id"> Patient ID<span style="color: red;">*</span></label><input type="text" class="form-control" required="" name="patient_id" id="patient_id"  value=""></div>
                        </div>
                     </div>
                 
            </div>
         </div>
         <button type="button" class="btn btn-primary float-right">Submit</button>
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

    
    <!-- Datatable -->
    <script src="./vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="./js/plugins-init/datatables.init.js"></script>


  

</body>

</html>