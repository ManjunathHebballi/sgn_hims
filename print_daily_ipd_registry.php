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
    <title>Print Daily IPD Registry Report</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

        <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">

</head>

<style>
   td{

    font-size:14px
   }

  @media print{
      
      @page {
      
      size: landscape}

    .dark{
background-color:black;

-webkit-print-color-adjust:exact;
print-color-adjust: exact;
    }
   }
   
   th{
       font-weight:600;
   }
   
   .table th, .table td {
       
       padding:3px;
       font-size:0.8rem;
      text-align: center; 
    vertical-align: middle;
   }
</style>

<body style=" font-family: 'Poppins', sans-serif;" onload="window.print()">

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





  

        <!--**********************************
            Content body start
        ***********************************-->
        
            <!-- row -->







    <div class="row">

    <div class="col-md-12">
      
                <div class="container">
                   
                        <div class="d-flex align-items-center ml-3">
                            <img src="assets/logo.png" class="" alt="" width="150">

                        
                        <div class=" ml-5">
                        <h5 class="text-center text-black"><?= $escape(isset($_SESSION['college_society']) ? $_SESSION['college_society'] : '') ?></h5>
                        <h3 class="text-center text-black"><?= $escape(isset($_SESSION['college_name']) ? $_SESSION['college_name'] : '') ?></h3>
                        <h5 class="text-center text-black"><?= $escape(isset($_SESSION['college_location']) ? $_SESSION['college_location'] : '') ?></h5>
                        <h5 class="text-center text-black"><?= $escape(isset($_SESSION['college_phone']) ? $_SESSION['college_phone'] : '') ?></h5>

                        </div>
                   </div>


                    
                

          
        </div>

                    <hr/>
                    <h4 class="text-center text-black mb-3 mt-5">Daily IPD Registry Report between <?= date("d.m.Y", strtotime($from)) ?> and <?= date("d.m.Y", strtotime($to)) ?>
                       
                    </h4>

                              
                    <table class="table table-bordered text-center  text-wrap table-hover">
                                        <thead class="te">
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
                    
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <button onclick="window.print()"class="btn btn-danger float-right d-print-none"> <i class="ri-printer-line"></i> Print </button>
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
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>

</body>

</html>