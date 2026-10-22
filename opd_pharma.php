<?php 

require_once(__DIR__ . '/model/session_config.php');
include("model/medicineClass.php");
$newMedicine= new medicine();
date_default_timezone_set("Asia/Calcutta");
if(isset($_REQUEST['from']))
{
    $from=$_REQUEST['from'];
}
else{
    $from=date("Y-m-d");   
}

if(isset($_REQUEST['to']))
{
    $to=$_REQUEST['to'];
}
else{
    $to=date("Y-m-d");   
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#319197">

    <title>OPD Pharma </title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" ></script>
        <!-- Sweet Alert-->
     <link href="css/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />


     <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
     <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap4.min.css">
  <!-- Datatable -->
  <link href="./vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">

    


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
<div class="row">

<div class="col-md-12">
    <div class="card">
        <div class="card-body">

        <div class="row">
        <div class="col-md-6">
        <h4>  Select Criteria</h4>
        </div>

      
              
        </div>
           <br>
          <form action="" method="post">
          <div class="row">
              
              
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="">From Date </label>
                      <input type="date" name="from" id="from" class="form-control" value="<?= $from?>" required>
                      
                      
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="">To Date </label>
                      <input type="date" name="to" id="to" class="form-control" value="<?= $to?>" required>
                      
                      
                  </div>
              </div>
              <div class="col-md-4 p-4">
                  <button type="submit" class="btn btn-success btn-md float-center " name="search">Search</button>
              </div>
              </form>
          </div>
        </div>
    </div>
</div>
</div>


    <div class="row">
         <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                   <div class="row m-2 justify-content-between">
                   <h4> Invoice Lists</h4> 
                    </div><hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive" >
                                <table id="example" class="display table table-bordered" style="width:100%">
                              
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Sl. No.</th>
                                            <th>OPD No.</th>
                                            <th>Patient Details</th>
                                            <th>Date & Time</th>
                                            <th>Department</th>
                                            <th>Doctor</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                
                                    <tbody>
                                        <?php
                                        $sn=1;
                                        $data=$newMedicine->getOPDMedicine($from,$to);
                                        while($row=mysqli_fetch_assoc($data)) {
                                        ?>
                                        <tr>
                                        <td><?= $sn?></td>
                                        <td><?= $row['opd_no']?></td>
                                        <td><?= $row['name']?></td>
                                        <td><?= date("d.m.Y",strtotime($row['date']))?></td>
                                        <td><?= $row['dept_name']?></td>
                                        <td><?= $row['doctor']?></td>
                                        <td><a class="btn btn-primary" href="pharma_billing.php?id=<?= $row['id']?>">Pharmacy Bill</a></td>
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


 <!-- Datatable -->
 <script src="./vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="./js/plugins-init/datatables.init.js"></script>





</body>

</html>