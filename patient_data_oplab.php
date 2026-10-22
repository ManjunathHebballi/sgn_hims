<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/dbconnect.php');

$newlogin = new userlogin;
$con = $newlogin->dbconnect();


$date = isset($_REQUEST['date']) ? date('Y-m-d', strtotime($_REQUEST['date'])) : date('Y-m-d');
$labInput = (isset($_REQUEST['lab_number']) && is_array($_REQUEST['lab_number'])) ? $_REQUEST['lab_number'] : [];

$labCounts = [];
foreach ($labInput as $deptName => $count) {
    $deptName = trim($deptName);
    $count = (int)$count;
    if ($deptName !== '' && $count > 0) {
        $labCounts[$deptName] = $count;
    }
}

if(isset($_REQUEST['submit']))
{
$insertValues = [];
$selectedPatientIds = [];

// Track visit_no counter globally across all patients
$date1 = date('Ymd',strtotime($date)); // 20250625
$prefix = 'LAB-' . $date1 . '-';
$query_init = "SELECT visit_no FROM lab_report WHERE visit_no LIKE '$prefix%' ORDER BY visit_no DESC LIMIT 1";
$res_init = mysqli_query($con, $query_init);

if ($row_init = mysqli_fetch_assoc($res_init)) {
    $visit_counter = (int)substr($row_init['visit_no'], -4); // Extract last 4 digits
} else {
    $visit_counter = 0;
}
foreach ($labCounts as $deptName => $labCount) {
    $deptNameEsc = mysqli_real_escape_string($con, $deptName);

    $excludePatientsSql = '';
    if (!empty($selectedPatientIds)) {
        $excludePatientsSql = " AND o.id NOT IN (" . implode(',', array_map('intval', $selectedPatientIds)) . ")";
    }

    $sql = "
    SELECT id, diagnosis
    FROM central_opd o
    WHERE date = '$date'
      AND diagnosis IN (
            SELECT DISTINCT t.diagnosis
            FROM temp_lab t
            INNER JOIN lab_test lt ON lt.testid = t.test_id
            WHERE lt.status='active' AND lt.testname_header = '$deptNameEsc'
      )
      AND created_by LIKE 'MRD%'
      AND is_generated = 1
      $excludePatientsSql
      AND NOT EXISTS (
            SELECT 1
            FROM lab_report l
            INNER JOIN lab_test lt2 ON lt2.testid = l.testid
            WHERE l.patient_id = o.id
              AND l.patient_type='OP'
              AND lt2.status='active'
              AND lt2.testname_header = '$deptNameEsc'
        )
    ORDER BY RAND()
    LIMIT $labCount
    ";
    $res = mysqli_query($con, $sql);

    $patients = [];
    $diagnosisList = [];

    while ($row = mysqli_fetch_assoc($res)) {
        $patients[] = $row;
        $diagnosisList[] = "'" . mysqli_real_escape_string($con, $row['diagnosis']) . "'";
        $selectedPatientIds[] = (int)$row['id'];
    }

    if (empty($patients)) {
        continue;
    }

    $diagnosisStr = implode(',', array_unique($diagnosisList));

    $sqlGroup = "
    SELECT t1.diagnosis, MIN(t1.group_id) as group_id
    FROM temp_lab t1
    INNER JOIN lab_test lt ON lt.testid = t1.test_id
    WHERE lt.status='active'
      AND lt.testname_header = '$deptNameEsc'
      AND t1.diagnosis IN ($diagnosisStr)
    GROUP BY t1.diagnosis
    ";
    $resGroup = mysqli_query($con, $sqlGroup);
    $groupMap = [];
    while ($row = mysqli_fetch_assoc($resGroup)) {
        $groupMap[$row['diagnosis']] = $row['group_id'];
    }

    $groupIds = array_unique(array_values($groupMap));
    if (empty($groupIds)) {
        continue;
    }

    $sqlParams = "
    SELECT t.parameter_id, t.test_id, t.group_id, t.result
    FROM temp_lab t
    INNER JOIN lab_test lt ON lt.testid = t.test_id
    WHERE lt.status='active'
      AND lt.testname_header = '$deptNameEsc'
      AND t.group_id IN (" . implode(',', array_map('intval', $groupIds)) . ")
    ";
    $resParams = mysqli_query($con, $sqlParams);
    $paramMap = [];
    while ($row = mysqli_fetch_assoc($resParams)) {
        $paramMap[$row['group_id']][] = $row;
    }

    foreach ($patients as $p) {
    
    $sql="select * from `central_opd` where id='$p[id]'";
    
    $ip_res=mysqli_query($con,$sql);
    $bill_row=mysqli_fetch_assoc($ip_res);
    
    // Define patient_id variable
    $pid = $p['id'];
    
    
    // Define base datetime for sample status timestamps
    $baseDateTime = strtotime($bill_row['created_at']);
$minTime = $baseDateTime + (5 * 60); // +5 minutes

$endTime = strtotime(date("Y-m-d", $baseDateTime) . " 19:59:59");

if ($minTime >= $endTime) {
    $dt = date("Y-m-d H:i:s", $baseDateTime);
} else {
    $randomTimestamp = rand($minTime, $endTime);
    $dt = date("Y-m-d H:i:s", $randomTimestamp);
}

    $query="SELECT * FROM data_table WHERE id=7";
                 $bill=mysqli_fetch_assoc(mysqli_query($con,$query));
                 
                 
$month = date('n',strtotime($date)); // Numeric representation of month (1-12)
    $year = date('Y',strtotime($date));

    if ($month >= 4) {
        // April to December
        $startYear = $year;
        $endYear = $year + 1;
    } else {
        // January to March
        $startYear = $year - 1;
        $endYear = $year;
    }

    $financialYear= "$startYear-" . substr($endYear, -2);
                 
                 
                 $bill_no=$bill['table_id'];
               
                 $bill_no=$bill_no+1;
                 $fi_bill_no='PH/'.$financialYear.'/'.sprintf("%04d", $bill_no);
         
         $sql="INSERT INTO `billing` ( `patient_id`, `amount`, `service_level_discount`, `discount_amount`, `paid_amount`, `outstand_amount`, `bill_no`, `doctor`, `dept_id`,  `service_type`, `patient_type`, `status`, `collected_by`, `collected_at`,`is_generated`,`discount_reason`) VALUES 
                               ( '$bill_row[id]', '0', '0', '0', '0', '0', '$fi_bill_no', '$bill_row[doctor]', '$bill_row[dept_id]', 'Pathology', 'OP', 'PAID', '$_SESSION[name]', NOW(),'1','CAMP')";
                            //   echo $sql;exit;
                      $res=mysqli_query($con,$sql);
                      $id=mysqli_insert_id($con);
                      if($id>0)  {
                      $query1="UPDATE data_table set table_id='$bill_no' WHERE id=7";
                      mysqli_query($con,$query1);
                      }
    
    // Increment visit_no for each patient
    $visit_counter++;
    $visit_no = $prefix . str_pad($visit_counter, 4, '0', STR_PAD_LEFT);
                    
                    // 
        // echo "Generated visit_no: $visit_no<br>";
        // Tests already recorded for this bill on this save. Replaces the old
        // $test_id_old check, which only compared against the previous iteration
        // and so let an interleaved parameter order create duplicates.
        $sample_seen = array();
 
 
        
    // ---------------------------------------------------------------------------------------------------
    $group_id = $groupMap[$p['diagnosis']] ?? null;
    if (!$group_id) continue;

    if (!isset($paramMap[$group_id])) {
        continue;
    }

    foreach ($paramMap[$group_id] as $param) {
        
        
        $query2="SELECT test_id,type FROM `lab_test_parameter` where id=$param[parameter_id]";
        $res2=mysqli_query($con, $query2);
        $row2=mysqli_fetch_assoc($res2);
        $test_id=$row2['test_id'];
        // Guard on both this save and what is already on file: another path
        // (sample_collection.php, or an earlier save of this treatment) may have
        // recorded the same bill/test already.
        $dup_chk = mysqli_query($con, "SELECT id FROM sample_status
                                        WHERE bill_no='" . mysqli_real_escape_string($con, (string) $fi_bill_no) . "'
                                          AND patient_id='" . mysqli_real_escape_string($con, (string) $p[id]) . "'
                                          AND test_id='" . mysqli_real_escape_string($con, (string) $test_id) . "'
                                        LIMIT 1");
        $already = ($dup_chk && mysqli_num_rows($dup_chk) > 0);
        if(!isset($sample_seen[$test_id]) && !$already)
        {
            $dt_sp=date("Y-m-d H:i:s", strtotime($dt . " +10 minutes"));
            $dt_ac=date("Y-m-d H:i:s", strtotime($dt_sp . " +10 minutes"));
            $dt_en=date("Y-m-d H:i:s", strtotime($dt_ac . " +100 minutes"));
            $dt_fz=date("Y-m-d H:i:s", strtotime($dt_en . " +10 minutes"));
            $sql="INSERT IGNORE INTO sample_status(`bill_no`, `patient_id`, `test_id`, `is_sample_collected`, `sample_collected_by`, `sample_collected_at`, `is_sample_acknowledge`, `sample_acknowledged_by`, `sample_acknowledged_at`,is_result_entered,result_entered_by,result_entered_at,`is_finalized`,`finalized_by`,`finalized_at`)
             VALUES ('$fi_bill_no','$p[id]','$test_id','1','lab','$dt_sp','1','lab','$dt_ac','1','lab','$dt_en','1','lab','$dt_fz')";
            // echo $sql.'<br>';
            mysqli_query($con,$sql);
            if($id>0)
            {
                $sql1="INSERT INTO `billing_details` (`bill_id`, `bill_no`, `service_id`, `doctor_id`, `unit`, `price`, `discount`, `status`, `created_at`) VALUES 
                                    ( '$id', '$fi_bill_no', '$test_id', '0', '1', '0', '0', 'PAID', NOW())";
                $res=mysqli_query($con,$sql1);  
            }
        }
        $sample_seen[$test_id] = true;
        
        
        
        $insertValues[] = "(
            '{$param['parameter_id']}',
            '$date',
            '{$param['result']}',
            '{$p['id']}',
            'OP',
            '{$param['test_id']}',
            '{$row2['type']}',
            '{$visit_no}',
            '{$fi_bill_no}',
            '$_SESSION[name]',
            '1'
        )";
    }
}
}
// echo "<pre>";
// print_r($insertValues);
if (!empty($insertValues)) {
    $insertSql = "
    INSERT INTO lab_report
    (parameter_id, date, test_result, patient_id, patient_type, testid,
      parameter_type, visit_no, bill_no, lab_tech,print_flag)
    VALUES " . implode(',', $insertValues);
    mysqli_query($con, $insertSql);
    // echo $insertSql;
}
// exit;

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>OP- Registration</title>
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


    <style>
    #notification {
        position: absolute;
        top: 90px;

        right: 2px;
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
                <div class="row mb-3">
                    <div class="col-md-12">
                        <h4 class="text-uppercase">Add Bulk OPD LAB Data</h4>
                    </div>
                </div>
                <div class="row clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">

                            <div class="card-body">

                                <form method="post" action="" name="frmtreat" onsubmit="return disableBtn()">

                                    <div class="row">

                                        <div class="col-sm-3     col-xs-12">
                                            <div class="form-group">
                                                <label for="type">Date</label>
                                                <div class="form-line">
                                                    <input type="date" class="form-control" name="date" id="date"
                                                        value="<?= date("Y-m-d");?>">
                                                </div>
                                            </div>
                                        </div>
<?php $deptSql="SELECT * FROM `lab_department` where status='active'";
$deptRes=mysqli_query($con,$deptSql);
while($deptRow=mysqli_fetch_assoc($deptRes)){
    $deptName = $deptRow['dept_name'];
?>
                                        <div class="col-sm-3 col-xs-12">
                                            <div class="form-group">
                                                <label for="type">Number of Patients for <?= htmlspecialchars($deptName) ?> </label>
                                                <div class="form-line">
                                                    <input type="number" class="form-control" name="lab_number[<?= htmlspecialchars($deptName) ?>]"
                                                        id="lab_number_<?= (int)$deptRow['id'] ?>" value="" min="0" placeholder="Enter No. of Patients">
                                                </div>
                                            </div>
                                        </div>
<?php } ?>
                                    </div>
                                    <div class="col-sm-12 ">
                                        <!--<b class="h3">Total : <span id="total"></span></b>-->
                                        <input type="hidden" name="submit" value="Submit">
                                        <input type="submit" id="submit" class="btn btn-success float-right">


                                    </div>
                                </form>
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
    <!--onsubmit="return disableBtn()-->
    <script type="text/javascript" language="javascript">
    function disableBtn() {

        document.getElementById("submit").disabled = true;
        return true;

    }
    </script>


    <!-- Required vendors -->
    <script src="./vendor/global/global.min.js"></script>
    <script src="./js/quixnav-init.js"></script>
    <script src="./js/custom.min.js"></script>





    <!-- Sweet Alerts js -->


    <!-- Sweet alert init js-->
    <script src="js/sweet-alerts.init.js"></script>


</body>

</html>