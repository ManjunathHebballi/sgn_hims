<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
$date=date("Y-m-d");
include('model/reportClass.php');
$newReport = new report;
if(isset($_REQUEST['op_from']))
{
    $op_from=$_REQUEST['op_from'];
}
else{
        $op_from=$date;
}
if(isset($_REQUEST['op_to']))
{
    $op_to=$_REQUEST['op_to'];
}
else{
        $op_to=$date;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>MIS Report</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" ></script>
       <!-- DataTables -->
    
        <link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/datatables.net-select-bs4/css/select.bootstrap4.min.css" rel="stylesheet" type="text/css" />


    <style>
     #notification {
            position: absolute;
            top: 90px;
           
            right:2px;
            z-index: 99;
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
<h4 class="text-uppercase">MIS Report</h4>

<div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
  <div class="row">
                <div class="col-md-12">
                    <h4>Select Criteria</h4><br>
                 
                </div>
            </div> 
<form action="#" href="#pills-home" method="POST">
    <div class="row">
        <div class="col-md-4">
           <div class="form-group">
                  <label for="exampleInputEmail1"> From Date </label>
                  <input type="date" class="form-control"  name="op_from" id="op_from" required value="<?= $op_from?>">
                  
            </div>
        </div>
    
    <div class="col-md-4">
        <div class="form-group">
               <label for="exampleInputEmail1"> To Date  </label>
               <input type="date" class="form-control"  name="op_to" id="op_to" required value="<?= $op_to?>">
               
         </div>
    </div>

         

    </div>
    
    <div class="row">
            <div class="col-md-12">
            <button type="submit" class="btn btn-success  float-right btn-rounded"  name="submit"><span><i class="bi bi-search"></i></span> Search</button>
            </div>    
    </div>
</form>
        
    </div>
            </div>
        </div>
    </div>
   
   
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                        <h5 class="ml-4">MIS Report Lists <span class="text-danger">
                            <?php if(isset($op_from) && isset($op_to)) {
                            echo "Between ".  date('d.m.Y',strtotime($op_from)).' and '.date('d.m.Y',strtotime($op_to));
                             } ?>
                             </span></h5>
                             </div>
<div class="col-md-6">
                               </div>
                    </div>
                    <hr>
<div class="card">
    <div class="card-body">
    <div class="row">
    <div class="col-md-6">
    <table class="table  table-bordered">
      <thead>
        <tr>
          <th colSpan="4" class='text-left'>Count</th>

        </tr>
        <tr>
          <th>Department</th>
          <th>OP</th>
          <th>IP</th>
          <th>OT</th>
        </tr>
      </thead>
      <tbody>
          <?php 
          $op=$ip=$ot=0;
          $data=$newReport->misreport1($op_from,$op_to);
          foreach ($data as $specialty => $details){
          ?>
        <tr>
            <td><?= $specialty?></td>
            <td><?php if($details['OP']>0) echo $details['OP']; else echo 0;?></td>
            <td><?php if($details['IP']>0) echo $details['IP']; else echo 0;?></td>
            <td><?php if($details['OT']>0) echo $details['OT']; else echo 0;?></td>
        </tr>
        <?php 
        $op +=$details['OP'];
        $ip +=$details['IP'];
        $ot +=$details['OT'];
} ?>
      </tbody>
      <tfoot>
          <th>Total</th>
          <th><?= $op?></th>
          <th><?= $ip?></th>
          <th><?= $ot?></th>
      </tfoot>
    </table>
    </div>
  
    <div class="col-md-6">
    <div class="row">
    <table class="table  table-bordered text-left">
      <thead>
        <tr>
          <th colSpan="2" class='text-left'>INCOME</th>

        </tr>
        <tr>
          <th>Type</th>
          <th>Income</th>
          
        </tr>
      </thead>
      <tbody>
          <?php 
          $data=$newReport->misreport2($op_from,$op_to);
          $total=0;
          while($row=mysqli_fetch_assoc($data)){ ?>
        <tr>
          <td><?= $row['service_type']?></td>
          <td><?= $row['amount']?></td>
        </tr>
        <?php $total+=$row['amount']; } ?>
       
      </tbody>
      <tfoot>
          <th>Total</th>
          <th><?= $total?></th>
      </tfoot>
    </table>
    
  
    <!--<table class="table  table-bordered text-center mt-3">-->
    <!--  <thead>-->
    <!--    <tr>-->
    <!--      <th colSpan="3" class='text-center'>INCOME & COUNT</th>-->

    <!--    </tr>-->
    <!--    <tr>-->
    <!--      <th>Type</th>-->
    <!--      <th>Count</th>-->
    <!--      <th>Income</th>-->

          
    <!--    </tr>-->
    <!--  </thead>-->
    <!--  <tbody>-->
    <!--    <tr>-->
          
    <!--    </tr>-->
        
       
    <!--  </tbody>-->
    <!--</table>-->
    </div>
    </div>
  
    
  
  </div>
    </div>
  </div>
  
  <div class="card">
    <div class="card-body">
    <div class="row">
  <div class="col-md-6">
    <table class="table  table-bordered text-left">
      <thead>
        <tr>
          <th colSpan="2" class='text-center'>DOCTOR COUNT</th>

        </tr>
        <tr>
          <th>Dept-Doctor</th>
          <th>Count</th>
          
        </tr>
      </thead>
      <tbody>
          <?php
          $total=0;
          $data=$newReport->misreport3($op_from,$op_to);
          while($row=mysqli_fetch_assoc($data))
          {
          ?>
        <tr>
          <td><?= $row['dept_name'].' - '.$row['doctor']?></td>
          <td><?= $row['data']?></td>
        </tr>
        
       <?php $total +=$row['data']; } ?>
      </tbody>
      <tfoot>
          <th>Total</th>
          <th><?= $total?></th>
      </tfoot>
    </table>
    </div>

    <div class="col-md-6">
    <table class="table table-bordered text-left">
      <thead>
        <tr>
          <th colSpan="2" class='text-center'>ROOM CATEGORY COUNT</th>

        </tr>
        <tr>
          <th>Room</th>
          <th>Count</th>
          

          
        </tr>
      </thead>
      <tbody>
          <?php 
          $data1=$newReport->misreport4($op_from,$op_to);
           foreach ($data1 as $key => $value) 
          {
          ?>
        <tr>
          <td><?= $key?></td>
          <td><?= $value?></td>
        </tr>
        <?php } ?>
       
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

    <script>
    $(document).ready(function() {
            $('#datatable-buttons4').DataTable({

                dom: 'Bfrtip',
                responsive: true,
                pageLength: 25,
                // lengthMenu: [0, 5, 10, 20, 50, 100, 200, 500],
                
                buttons: [
            
            {
                extend: 'pdfHtml5',
                title: '<?php echo addslashes($_SESSION['college_name'].", ".$_SESSION['college_location']." Between ".date('d.m.Y',strtotime($op_from))." and ".date('d.m.Y',strtotime($op_to))); ?>',
                orientation: 'landscape',
                pageSize: 'LEGAL'
            }
        ]

            });
        });
</script>
   
</body>

</html>