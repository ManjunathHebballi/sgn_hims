<?php 
require_once(__DIR__ . '/../model/session_config.php');
date_default_timezone_set("Asia/Kolkata");

$date = date("Y-m-d");
include('model/reportClass.php');
$newReport = new report;

$op_from = !empty($_REQUEST['op_from']) ? $_REQUEST['op_from'] : $date;
$op_to = !empty($_REQUEST['op_to']) ? $_REQUEST['op_to'] : $date;

$dateRangeText = "Between " . date('d.m.Y', strtotime($op_from)) . " and " . date('d.m.Y', strtotime($op_to));
$pdfTitle = addslashes(($_SESSION['college_name'] ?? '') . ", " . ($_SESSION['college_location'] ?? '') . " " . $dateRangeText);

$countData = $newReport->misreport1($op_from, $op_to);
$incomeResult = $newReport->misreport2($op_from, $op_to);
$doctorResult = $newReport->misreport3($op_from, $op_to);
$roomData = $newReport->misreport4($op_from, $op_to);

$op = $ip = $ot = 0;
foreach ((array)$countData as $details) {
    $op += (int)($details['OP'] ?? 0);
    $ip += (int)($details['IP'] ?? 0);
    $ot += (int)($details['OT'] ?? 0);
}

$incomeRows = array();
$incomeTotal = 0;
if ($incomeResult) {
    while ($row = mysqli_fetch_assoc($incomeResult)) {
        $incomeRows[] = $row;
        $incomeTotal += (float)$row['amount'];
    }
}

$doctorRows = array();
$doctorTotal = 0;
if ($doctorResult) {
    while ($row = mysqli_fetch_assoc($doctorResult)) {
        $doctorRows[] = $row;
        $doctorTotal += (int)$row['data'];
    }
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
        @media print {
            .no-print,
            #preloader,
            .nav-header,
            .header,
            .quixnav,
            .footer,
            .deznav,
            .dlabnav,
            .left-sidebar {
                display: none !important;
            }
            .content-body,
            .container-fluid,
            .card,
            .card-body {
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
            }
            body {
                background: #fff !important;
            }
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
    <div class="col-md-8">
        <h4 class="text-uppercase">MIS Report</h4>
    </div>
    <div class="col-md-4 text-right no-print">
        <button type="button" class="btn btn-primary btn-sm" onclick="printReport()">
            <i class="fa fa-print"></i> Print Report
        </button>
    </div>
</div>

<div class="row no-print">
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
                        <h5 class="ml-4">MIS Report Lists <span class="text-danger"><?= $dateRangeText ?></span></h5>
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
            <?php foreach ((array)$countData as $specialty => $details) { ?>
          <tr>
              <td><?= htmlspecialchars($specialty) ?></td>
              <td><?= (int)($details['OP'] ?? 0) ?></td>
              <td><?= (int)($details['IP'] ?? 0) ?></td>
              <td><?= (int)($details['OT'] ?? 0) ?></td>
          </tr>
          <?php } ?>
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
          <?php foreach ($doctorRows as $row) { ?>
        <tr>
          <td><?= htmlspecialchars($row['dept_name'].' - '.$row['doctor']) ?></td>
          <td><?= (int)$row['data'] ?></td>
        </tr>
       <?php } ?>
      </tbody>
      <tfoot>
          <th>Total</th>
          <th><?= $doctorTotal ?></th>
      </tfoot>
    </table>
    </div>
    
  
    
  
  </div>
    </div>
  </div>
  
  <div class="card">
    <div class="card-body">
    <div class="row">
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
              <?php foreach ($incomeRows as $row) { ?>
            <tr>
              <td><?= htmlspecialchars($row['service_type']) ?></td>
              <td><?= $row['amount'] ?></td>
            </tr>
            <?php } ?>
          
          </tbody>
          <tfoot>
              <th>Total</th>
              <th><?= $incomeTotal ?></th>
          </tfoot>
        </table>
      
    
      
      </div>
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
          <?php foreach ((array)$roomData as $key => $value) { ?>
        <tr>
          <td><?= htmlspecialchars($key) ?></td>
          <td><?= (int)$value ?></td>
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
    function printReport() {
        window.print();
    }

    $(document).ready(function() {
        const $table = $('#datatable-buttons4');
        if ($table.length) {
            $table.DataTable({
                dom: 'Bfrtip',
                responsive: true,
                pageLength: 25,
                buttons: [
                    {
                        extend: 'pdfHtml5',
                        title: '<?= $pdfTitle ?>',
                        orientation: 'landscape',
                        pageSize: 'LEGAL'
                    }
                ]
            });
        }
    });
</script>
   
</body>

</html>