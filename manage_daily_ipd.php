<?php 
require_once(__DIR__ . '/model/session_config.php');

if (!isset($_SESSION['loginid']) || (int)$_SESSION['loginid'] <= 0) {
    echo "<script>window.location='index.php';</script>";
    exit;
}

session_write_close();

date_default_timezone_set("Asia/Kolkata");  

include('model/reportClass.php');
$newReport = new report;

$escape = function ($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
};

$today = date("Y-m-d");
$from = isset($_REQUEST['from']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', (string)$_REQUEST['from']) ? $_REQUEST['from'] : $today;
$to = isset($_REQUEST['to']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', (string)$_REQUEST['to']) ? $_REQUEST['to'] : $today;

if ($from > $to) {
    $temp = $from;
    $from = $to;
    $to = $temp;
}

$departments = array();
$departmentIds = array();
$departmentResult = $newReport->getIPDepartment();
if ($departmentResult) {
    while ($departmentRow = mysqli_fetch_assoc($departmentResult)) {
        $departments[] = $departmentRow;
        $departmentIds[] = (int)$departmentRow['id'];
    }
}

$dailySummary = array();
$summaryResult = $newReport->getDailyIPRegistrySummarySeparatedIcu($from, $to);
$showIcuColumn = false;
if ($summaryResult) {
    while ($summaryRow = mysqli_fetch_assoc($summaryResult)) {
        $reportDate = $summaryRow['report_date'];
        $departmentId = (int)$summaryRow['dept_id'];
        $isIcu = (int)$summaryRow['is_icu'] === 1;
        if (!isset($dailySummary[$reportDate])) {
            $dailySummary[$reportDate] = array(
                'departments' => array(),
                'icu' => array('admission' => 0, 'discharge' => 0)
            );
        }

        if ($isIcu) {
            $dailySummary[$reportDate]['icu'] = array(
                'admission' => (int)$summaryRow['admission_count'],
                'discharge' => (int)$summaryRow['discharge_count']
            );
            if ((int)$summaryRow['admission_count'] > 0 || (int)$summaryRow['discharge_count'] > 0) {
                $showIcuColumn = true;
            }
        } else {
            $dailySummary[$reportDate]['departments'][$departmentId] = array(
                'admission' => (int)$summaryRow['admission_count'],
                'discharge' => (int)$summaryRow['discharge_count']
            );
        }
    }
}

$dates = $newReport->getBetweenDates($from, $to);
$reportRows = array();
foreach ($dates as $reportDate) {
    $totalAdmission = 0;
    $totalDischarge = 0;
    $departmentCounts = array();

    foreach ($departmentIds as $departmentId) {
        $admissionCount = isset($dailySummary[$reportDate]['departments'][$departmentId]['admission']) ? $dailySummary[$reportDate]['departments'][$departmentId]['admission'] : 0;
        $dischargeCount = isset($dailySummary[$reportDate]['departments'][$departmentId]['discharge']) ? $dailySummary[$reportDate]['departments'][$departmentId]['discharge'] : 0;
        $departmentCounts[$departmentId] = array(
            'admission' => $admissionCount,
            'discharge' => $dischargeCount
        );
        $totalAdmission += $admissionCount;
        $totalDischarge += $dischargeCount;
    }

    $icuAdmission = isset($dailySummary[$reportDate]['icu']['admission']) ? $dailySummary[$reportDate]['icu']['admission'] : 0;
    $icuDischarge = isset($dailySummary[$reportDate]['icu']['discharge']) ? $dailySummary[$reportDate]['icu']['discharge'] : 0;
    $totalAdmission += $icuAdmission;
    $totalDischarge += $icuDischarge;

    $reportRows[] = array(
        'date' => $reportDate,
        'counts' => $departmentCounts,
        'icu' => array(
            'admission' => $icuAdmission,
            'discharge' => $icuDischarge
        ),
        'totalAdmission' => $totalAdmission,
        'totalDischarge' => $totalDischarge
    );
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Manage IPD Registry</title>
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
        
        table th{
        
            font-weight:600;
        }
          .table thead th{
        
            font-weight:600;
        }
        .bg-primary{
            
            background-color:#364277 !important;
            color:white
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
<h4 class="text-uppercase mb-5"> Daily IPD Registry Report</h4>


   
   <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
<form action="" method="GET">
    <div class="row">
        <div class="col-md-4">
           <div class="form-group">
                  <label for="exampleInputEmail1"> From Date </label>
                  <input type="date" class="form-control"  name="from" id="from" required value="<?= $escape($from) ?>">
                  
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                   <label for="exampleInputEmail1"> To Date  </label>
                   <input type="date" class="form-control"  name="to" id="to" required value="<?= $escape($to) ?>">
                   
             </div>
        </div>

         

    </div>
    
    <div class="row">
            <div class="col-md-12">
            <button type="submit" class="btn btn-success  float-right btn-rounded"  name="submit"><span><i class="bi bi-search"></i></span> Search</button>
            </div>    
    </div>
</form>
<hr>

                   <div class="d-flex justify-content-end">
                       <a href="print_daily_ipd_registry.php?from=<?= urlencode($from) ?>&to=<?= urlencode($to) ?>" class="badge badge-pill badge-danger" target="_blank" data-toggle="tooltip" data-placement="top" title="Print  Report"><i class="icofont-print"></i> Print</a>
                       
                   </div>
                   

                    <div class="table-responsive">
                    <table class="table table-bordered text-center  text-wrap table-hover">
                                        <thead class="bg-primary te">
                                            <tr>
                                            <th rowspan="2">Dates</th>
                                                                                        <?php foreach ($departments as $department) { ?>
                                                                                        <th colspan="2"><?= $escape($department['dept_name']) ?></th>
                                                                                        <?php } ?>
                                                                                        <?php if ($showIcuColumn) { ?>
                                                                                        <th colspan="2">ICU</th>
                                                                                        <?php } ?>
                                             <th rowspan="2">Total Admission</th>
                                              <th rowspan="2">Total Discharge</th>
                                            </tr>
                                            <tr>
                                                                                            <?php foreach ($departments as $department) { ?>  
                                                <th>  Admission </th>
                                              <th>  Discharge </th>
                                              
                                              <?php } ?>
                                                                                            <?php if ($showIcuColumn) { ?>
                                                                                                <th>Admission</th>
                                                                                                <th>Discharge</th>
                                                                                            <?php } ?>
                                             
                                              </tr>
                                         
                                        </thead>
                                        <tbody class=" text-black">
                                                                                     <?php foreach ($reportRows as $reportRow) { ?>
                                           <tr>
                                                                                                                    <th><?= date("d.m.Y", strtotime($reportRow['date'])) ?></th>
                                                                                                                    <?php foreach ($departmentIds as $departmentId) { ?>
                                                                                                                <td><?= $reportRow['counts'][$departmentId]['admission'] ?></td>
                                            
                                                                                                                        <td><?= $reportRow['counts'][$departmentId]['discharge'] ?></td>
                                         
                                                         <?php } ?>
                                                         <?php if ($showIcuColumn) { ?>
                                                         <td><?= $reportRow['icu']['admission'] ?></td>
                                                         <td><?= $reportRow['icu']['discharge'] ?></td>
                                                         <?php } ?>
                                           
                                                                                     <td><?= $reportRow['totalAdmission'] ?></td>
                                                                                     <td><?= $reportRow['totalDischarge'] ?></td>
                                          
                                            
                                            
                                            
                                           </tr>
                                           <?php } ?>
</tbody>
                                            <!-- <tfoot>
                                                <tr>
                                                    <th>Total</th>
                                                    <th>17</th>
                                                    <th>15</th>
                                                    <th>32</th>
                                                    <th>0</th>
                                                    <th>0</th>
                                                </tr>
                                            </tfoot> -->
                                            </table>
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

    
    <!-- Datatable -->
    <script src="./vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="./js/plugins-init/datatables.init.js"></script>


  

</body>

</html>