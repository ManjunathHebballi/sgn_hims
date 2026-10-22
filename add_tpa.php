<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/tpaClass.php');
$newTPA = new addTPA;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Add TPA </title>
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



 <form method="POST" action="controller/processTPA.php">
<div class="container-fluid">
  
   <div class="row mb-3">
      <div class="col-md-12">
         <h4 class="text-uppercase">Add TPA </h4>
      </div>
   </div>
   <div class="row">
      <div class="col-xl-12">
         <div class="card">
            <div class="card-body">
               <div class="card-title">
                  <h4 class="card-title">TPA Details</h4>
               </div>
               <hr>
               <div class="row">
                  <div class="col-md-4">
                     <div class="form-group"><label for="validationCustom01"> Name<span style="color: red;">*</span></label><input type="text" name="name" id="name"class="form-control" required="" value="" wtx-context="548C5A41-A6DB-4FAD-8F3A-63E9337EBA12"></div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group"><label for="validationCustom01">Contact Person<span style="color: red;">*</span></label><input type="text" name="contact_person" id="contact_person" class="form-control" required="" value="" wtx-context="DE2AABCC-490B-44DD-98C1-13385E06A633"></div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group"><label for="validationCustom01"> Contact Number<span style="color: red;">*</span></label><input type="text" name="mobile" id="mobile" class="form-control" required="" value="" wtx-context="B10B07A5-1623-4846-851E-17D4BC79238F"></div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group"><label for="validationCustom01">Address<span style="color: red;">*</span></label><input type="text" name="address"  id="address" class="form-control" required="" value="" wtx-context="32F5A97A-1531-41B8-93F8-31B857313B1E"></div>
                  </div>
               </div>
               <div >
                
                <button type="submit" name="submit" value="tpa" class="btn btn-primary float-right">Submit</button>
              </div>
               
            </div>
         </div>
      </div>
   </div>
   
</div>
</form>
</div>
   <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="text-center">
        <tr>
            <th>Sl No.</th>
            <th>Name</th>
            <th>Contact Person</th>
            <th>Mobile No.</th>
            <th>Address</th>
            <th>Date</th>
        </tr>
        </thead>
                    <tbody class="text-center">
        <?php
        $sn=1;
        $res=$newTPA->getTPA();
        while($row=mysqli_fetch_assoc($res))
        { ?>
        <tr>
            <td><?php echo $sn;?></td>
            <td><?php echo $row['name'];?></td>
            <td><?php echo $row['contact_person'];?></td>
            <td><?php echo $row['mobile'];?></td>
            <td><?php echo $row['address'];?></td>
            <td><?php echo $row['date'];?></td>
        </tr>
        <?php $sn++; } ?>
   </tbody>
                        </table>
    </div></div
    
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