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
    <title>Booked Ambulance</title>
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
            <h4 class="text-uppercase">Booked Ambulance</h4>
        </div>
    </div>
    


   <div class="row">
   <div class="col-md-12">
      <div class="card">
         <div class="card-body">
            <div class="row mt-3 mb-2">
               <div class="col-md-12">
                  <h4>Search Ambulances </h4>
                  <br>
               </div>
            </div>
            <form method="POST">
               <div class="row">
                  <div class="col-md-4">
                     <div class="form-group">
                         <label for="amb_number"> Vehicle Number </label>
                         <input type="text" class="form-control" required="" value="" name="amb_number"  placeholder="Search by Vehicle Number"  id="amb_number"></div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-12"><button type="submit" name="search" class="btn btn-success  float-right btn-rounded" name="submit"><span><i class="bi bi-search"></i></span> Search</button></div>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-header">
            <h4 class="card-title"> List</h4>
         </div>
         <div class="card-body">
            <div class="table-responsive">
               <table class="table table-striped display text-center">
                  <thead class="text-center">
                     <tr>
                        <th>Sl NO</th>
                        <th>Number</th>
                        <th>Patient</th>
                        <th>Patient ID</th>
                        <th>Distance</th>
                        <th>Amount</th>
                        <th>Driver</th>
                     </tr>
                  </thead>
                  <tbody class="text-center"></tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
</div></div>
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