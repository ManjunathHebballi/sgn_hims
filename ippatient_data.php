<?php
require_once(__DIR__ . '/model/session_config.php');
if (!isset($_SESSION['loginid'])) {
    echo "<script>window.location='index.php';</script>";
}
date_default_timezone_set("Asia/Kolkata");
include('model/dbconnect.php');

function getBulkGeneratedIpdNo($con, $date)
{
    $query1 = "SELECT table_id FROM data_table WHERE id=2";
    $central_ipd = mysqli_fetch_assoc(mysqli_query($con, $query1));
    $ipd_no = isset($central_ipd['table_id']) ? ($central_ipd['table_id'] + 1) : 0;
    $y = date("Ymd", strtotime($date));

    if ($y != substr((string)$ipd_no, 0, 8)) {
        $data = "SELECT SUBSTR(MAX(ipd_no),3,11) as ipd FROM central_ipd WHERE ipd_no LIKE 'IP$y%'";
        $ip_no = mysqli_fetch_assoc(mysqli_query($con, $data));
        if (!empty($ip_no['ipd'])) {
            $ipd_no = $ip_no['ipd'] + 1;
        } else {
            $ipd_no = $y . '001';
        }
    }

    return $ipd_no;
}

function getBulkGeneratedBed($con, $dept_id, $gender = '')
{
    $dept_id = (int)$dept_id;
    $bed_query = "SELECT id, ward FROM bed WHERE status='active' AND department='$dept_id'";

    if (!in_array($dept_id, array(2, 12, 17)) && !empty($gender)) {
        $safe_gender = mysqli_real_escape_string($con, $gender);
        $bed_query .= " AND ward LIKE CONCAT('$safe_gender','%')";
    }

    $bed_query .= " ORDER BY RAND() LIMIT 1";
    return mysqli_fetch_assoc(mysqli_query($con, $bed_query));
}

//   print_r($_REQUEST);exit;
if (isset($_REQUEST['submit'])) {

    $newlogin = new userlogin;
    $con = $newlogin->dbconnect();
    $week = date("l", strtotime($_REQUEST['date']));
    $year = date("Y", strtotime($_REQUEST['date']));
    // echo $year;exit;
    //  $ipdpatient=$_REQUEST['number'];  
    $date = $_REQUEST['date'];
    //   $discharge=$_REQUEST['discharge'];
    $discdate = date_create($date);
    date_sub($discdate, date_interval_create_from_date_string("1 days"));
    $discdate = date_format($discdate, "Y-m-d");

    $created_by = mysqli_real_escape_string($con, $_SESSION['name']);
    $sql2 = "SELECT ipd_no, admission_date, name, age, gender, address, dept_id, bed_id, diagnosis,dis_date FROM central_ipd  WHERE discharge = 0 and dis_date ='$date' ORDER BY RAND()";
    $res2 = mysqli_query($con, $sql2);
    $discharge_array = array();
    $bed_array = array();
    $ipd_array = array();
    while ($row2 = mysqli_fetch_assoc($res2)) {
        $query5 = " ('$row2[ipd_no]','$row2[name]','$row2[dept_id]','$row2[age]','$row2[gender]','$row2[admission_date]','$date','$row2[diagnosis]','$created_by')";
        array_push($discharge_array, $query5);
        array_push($bed_array, $row2['bed_id']);
        array_push($ipd_array, $row2['ipd_no']);
    }
    if (!empty($discharge_array)) {
        $discharge_query = "INSERT INTO `discharge`(`patient_id`, `patient_name`, `dept_id`, `age`, `sex`, `admission_date`, `discharge_date`, `diagnosis`, `created_by`) 
    VALUES " . implode(",", $discharge_array);
        mysqli_query($con, $discharge_query);
        $bed_update = "UPDATE bed SET status='active' where id in (" . implode(",", $bed_array) . ")";
        mysqli_query($con, $bed_update);
        $ipd_update = "UPDATE central_ipd SET discharge=1,dis_date='$date' where ipd_no in ('" . implode("','", $ipd_array) . "')";
        mysqli_query($con, $ipd_update);
    }
    $pd_male = $pd_female = $ps_male = $ps_female = $sk_male = $sk_female = $or_male = $or_female = $op_male = $op_female = $og_male = $og_female = $sg_male = $sg_female = $en_male = $en_female = $me_male = $me_female = $em_male = $em_female = 0;

    $pd_male = isset($_REQUEST['pd_male_number']) ? (int)$_REQUEST['pd_male_number'] : 0;
    $pd_female = isset($_REQUEST['pd_female_number']) ? (int)$_REQUEST['pd_female_number'] : 0;
    $ps_male = isset($_REQUEST['ps_male_number']) ? (int)$_REQUEST['ps_male_number'] : 0;
    $ps_female = isset($_REQUEST['ps_female_number']) ? (int)$_REQUEST['ps_female_number'] : 0;
    $sk_male = isset($_REQUEST['sk_male_number']) ? (int)$_REQUEST['sk_male_number'] : 0;
    $sk_female = isset($_REQUEST['sk_female_number']) ? (int)$_REQUEST['sk_female_number'] : 0;
    $or_male = isset($_REQUEST['or_male_number']) ? (int)$_REQUEST['or_male_number'] : 0;
    $or_female = isset($_REQUEST['or_female_number']) ? (int)$_REQUEST['or_female_number'] : 0;
    $op_male = isset($_REQUEST['op_male_number']) ? (int)$_REQUEST['op_male_number'] : 0;
    $op_female = isset($_REQUEST['op_female_number']) ? (int)$_REQUEST['op_female_number'] : 0;
    $og_male = isset($_REQUEST['og_male_number']) ? (int)$_REQUEST['og_male_number'] : 0;
    $og_female = isset($_REQUEST['og_female_number']) ? (int)$_REQUEST['og_female_number'] : 0;
    $sg_male = isset($_REQUEST['sg_male_number']) ? (int)$_REQUEST['sg_male_number'] : 0;
    $sg_female = isset($_REQUEST['sg_female_number']) ? (int)$_REQUEST['sg_female_number'] : 0;
    $en_male = isset($_REQUEST['en_male_number']) ? (int)$_REQUEST['en_male_number'] : 0;
    $en_female = isset($_REQUEST['en_female_number']) ? (int)$_REQUEST['en_female_number'] : 0;
    $me_male = isset($_REQUEST['me_male_number']) ? (int)$_REQUEST['me_male_number'] : 0;
    $me_female = isset($_REQUEST['me_female_number']) ? (int)$_REQUEST['me_female_number'] : 0;
    $em_male = isset($_REQUEST['em_male_number']) ? (int)$_REQUEST['em_male_number'] : 0;
    $em_female = isset($_REQUEST['em_female_number']) ? (int)$_REQUEST['em_female_number'] : 0;

    $gendered_department_counts = array(
        array('dept_id' => 2, 'gender' => 'Male', 'count' => $pd_male),
        array('dept_id' => 2, 'gender' => 'Female', 'count' => $pd_female),
        array('dept_id' => 3, 'gender' => 'Male', 'count' => $ps_male),
        array('dept_id' => 3, 'gender' => 'Female', 'count' => $ps_female),
        array('dept_id' => 4, 'gender' => 'Male', 'count' => $sk_male),
        array('dept_id' => 4, 'gender' => 'Female', 'count' => $sk_female),
        array('dept_id' => 7, 'gender' => 'Male', 'count' => $or_male),
        array('dept_id' => 7, 'gender' => 'Female', 'count' => $or_female),
        array('dept_id' => 11, 'gender' => 'Male', 'count' => $op_male),
        array('dept_id' => 11, 'gender' => 'Female', 'count' => $op_female),
        array('dept_id' => 12, 'gender' => 'Male', 'count' => $og_male),
        array('dept_id' => 12, 'gender' => 'Female', 'count' => $og_female),
        array('dept_id' => 6, 'gender' => 'Male', 'count' => $sg_male),
        array('dept_id' => 6, 'gender' => 'Female', 'count' => $sg_female),
        array('dept_id' => 18, 'gender' => 'Male', 'count' => $en_male),
        array('dept_id' => 18, 'gender' => 'Female', 'count' => $en_female),
        array('dept_id' => 1, 'gender' => 'Male', 'count' => $me_male),
        array('dept_id' => 1, 'gender' => 'Female', 'count' => $me_female),
        array('dept_id' => 17, 'gender' => 'Male', 'count' => $em_male),
        array('dept_id' => 17, 'gender' => 'Female', 'count' => $em_female)
    );

    $bulk_select_parts = array();
    foreach ($gendered_department_counts as $entry) {
        if ($entry['count'] <= 0) {
            continue;
        }

        $dept_id = (int)$entry['dept_id'];
        $gender = mysqli_real_escape_string($con, $entry['gender']);

        $bulk_select_parts[] = "(SELECT c.id,c.opd_no,c.name,c.age,c.gender,c.mobile,c.category,c.address,c.dept_id,c.religion,c.caste,c.parent_name,c.occupation,c.annual_income,c.diagnosis,concat(d.name,' ',d.last_name) as doctor FROM `central_opd` c INNER JOIN `doctor` d ON d.id=c.doctor and d.status='active' and d.schedule_type='1' WHERE c.date='$date' AND c.is_ipd=0 AND c.is_generated=1 AND c.dept_id='$dept_id' AND c.gender='$gender' ORDER BY RAND() LIMIT {$entry['count']})";
    }

    $bulk_patient_array = array();
    if (!empty($bulk_select_parts)) {
        $bulk_sql = implode(" UNION ALL ", $bulk_select_parts);
        $bulk_res = mysqli_query($con, $bulk_sql);
        while ($row = mysqli_fetch_assoc($bulk_res)) {
            $bulk_patient_array[] = $row;
        }
    }

    if (!empty($bulk_patient_array)) {
        shuffle($bulk_patient_array);
    }

    $ipd_insert_array = array();


    foreach ($bulk_patient_array as $row) {
        if (!isset($_SESSION['loginid'])) {
            echo "<script>window.location='index.php';</script>";
            exit;
        }

        $opd_id = (int)$row['id'];
        $opd_check = mysqli_fetch_assoc(mysqli_query($con, "SELECT is_ipd FROM central_opd WHERE id='$opd_id'"));
        if (empty($opd_check) || $opd_check['is_ipd'] != 0) {
            continue;
        }

        $bed = getBulkGeneratedBed($con, $row['dept_id'], $row['gender']);
        if (empty($bed)) {
            continue;
        }

        $ipd_no = getBulkGeneratedIpdNo($con, $date);

        $opd_no = mysqli_real_escape_string($con, $row['opd_no']);
        $name = mysqli_real_escape_string($con, $row['name']);
        $age = mysqli_real_escape_string($con, $row['age']);
        $gender = mysqli_real_escape_string($con, $row['gender']);
        $address = mysqli_real_escape_string($con, $row['address']);
        $dept_id = (int)$row['dept_id'];
        $bed_id = (int)$bed['id'];
        $ward = mysqli_real_escape_string($con, $bed['ward']);
        $diagnosis = mysqli_real_escape_string($con, $row['diagnosis']);
        $doctor = mysqli_real_escape_string($con, $row['doctor']);

        $ipd_insert_array[] = "('$opd_no','IP$ipd_no','$date','$name','$age','$gender','$address','$dept_id','$bed_id','$ward','$diagnosis','$doctor','1','$created_by')";

        mysqli_query($con, "UPDATE bed SET status='occupied' WHERE id='$bed_id'");
        mysqli_query($con, "UPDATE data_table SET table_id='$ipd_no' WHERE id=2");
        mysqli_query($con, "UPDATE central_opd SET is_ipd=1 WHERE id='$opd_id'");
    }

    if (!empty($ipd_insert_array)) {
        $ipd_insert_query = "INSERT INTO central_ipd(opd_no,ipd_no,admission_date,name,age,gender,address,dept_id,bed_id,ward,diagnosis,doctor,is_generated,`created_by`) VALUES " . implode(",", $ipd_insert_array);
        mysqli_query($con, $ipd_insert_query);
    }
    // if(count($insert_id)>0)
    // {
    // $insertid=implode(",",$insert_id);
    // $sql="select * from `central_ipd` where id in ($insertid)";
    // $ip_res=mysqli_query($con,$sql);

    //  $month = date('n'); // Numeric representation of month (1-12)
    //     $year = date('Y');

    //     if ($month >= 4) {
    //         // April to December
    //         $startYear = $year;
    //         $endYear = $year + 1;
    //     } else {
    //         // January to March
    //         $startYear = $year - 1;
    //         $endYear = $year;
    //     }

    //     $financialYear= "$startYear-" . substr($endYear, -2);

    //     while($bill_row=mysqli_fetch_assoc($ip_res))
    //     {
    //          $query="SELECT * FROM data_table WHERE id=6";
    //                      $bill=mysqli_fetch_assoc(mysqli_query($con,$query));
    //                      $bill_no=$bill['table_id'];

    //                      $bill_no=$bill_no+1;
    //                      $fi_bill_no='IP/GEN/'.$financialYear.'/'.sprintf("%04d", $bill_no);

    //          $sql="INSERT INTO `billing` ( `patient_id`, `amount`, `service_level_discount`, `discount_amount`, `paid_amount`, `outstand_amount`, `bill_no`, `doctor`, `dept_id`,  `service_type`, `patient_type`, `status`, `collected_by`, `collected_at`,`is_generated`,`discount_reason`) VALUES 
    //                                ( '$bill_row[ipd_no]', '0', '0', '0', '0', '0', '$fi_bill_no', '$bill_row[doctor]', '$bill_row[dept_id]', 'IP', 'IP', 'PAID', '$_SESSION[name]', NOW(),'1','CAMP')";
    //                             //   echo $sql;exit;
    //                       $res=mysqli_query($con,$sql);
    //                       $id=mysqli_insert_id($con);
    //                       if($id>0)  {
    //                       $query1="UPDATE data_table set table_id='$bill_no' WHERE id=6";
    //                       mysqli_query($con,$query1);

    //                       $sql1="INSERT INTO `billing_details` (`bill_id`, `bill_no`, `service_id`, `doctor_id`, `unit`, `price`, `discount`, `status`, `created_at`) VALUES 
    //                                 ( '$id', '$fi_bill_no', '6936', '0', '1', '0', '0', 'PAID', NOW())";
    //                              $res=mysqli_query($con,$sql1);  
    //                       }
    //     }
    // }
    echo "<script>window.location='ippatient_data_discharge.php'</script>";
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>IP- Registration</title>
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

        .male-label {
            color: #0066cc;
            font-weight: 600;
        }

        .female-label {
            color: #cc0066;
            font-weight: 600;
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






        <?php include('leftbar.php') ?>


        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <!-- row -->



            <div class="container-fluid">
                <div class="row mb-3">
                    <div class="col-md-12">
                        <h4 class="text-uppercase">Add Bulk Data</h4>
                    </div>
                </div>
                <div class="row clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">

                            <div class="card-body">

                                <form method="post" action="" name="frmtreat" id="frmtreat">
                                    <div class="row">
                                        <div class="col-sm-3 col-xs-12">
                                            <div class="form-group">
                                                <label for="pd_male_number" class="male-label">Pediatrics Male</label>
                                                <div class="form-line">
                                                    <input type="number" class="form-control gender-count" name="pd_male_number" id="pd_male_number" min="0" value="" onkeyup="getTotal()">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-xs-12">
                                            <div class="form-group">
                                                <label for="pd_female_number" class="female-label">Pediatrics Female</label>
                                                <div class="form-line">
                                                    <input type="number" class="form-control gender-count" name="pd_female_number" id="pd_female_number" min="0" value="" onkeyup="getTotal()">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-xs-12">
                                            <div class="form-group">
                                                <label for="ps_male_number" class="male-label">Psychiatry Male</label>
                                                <div class="form-line">
                                                    <input type="number" class="form-control gender-count" name="ps_male_number" id="ps_male_number" min="0" value="" onkeyup="getTotal()">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-xs-12">
                                            <div class="form-group">
                                                <label for="ps_female_number" class="female-label">Psychiatry Female</label>
                                                <div class="form-line">
                                                    <input type="number" class="form-control gender-count" name="ps_female_number" id="ps_female_number" min="0" value="" onkeyup="getTotal()">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-xs-12">
                                            <div class="form-group">
                                                <label for="sk_male_number" class="male-label">Skin Male</label>
                                                <div class="form-line">
                                                    <input type="number" class="form-control gender-count" name="sk_male_number" id="sk_male_number" min="0" value="" onkeyup="getTotal()">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-xs-12">
                                            <div class="form-group">
                                                <label for="sk_female_number" class="female-label">Skin Female</label>
                                                <div class="form-line">
                                                    <input type="number" class="form-control gender-count" name="sk_female_number" id="sk_female_number" min="0" value="" onkeyup="getTotal()">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-3 col-xs-12">
                                            <div class="form-group">
                                                <label for="or_male_number" class="male-label">Ortho Male</label>
                                                <div class="form-line">
                                                    <input type="number" class="form-control gender-count" name="or_male_number" id="or_male_number" min="0" value="" onkeyup="getTotal()">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-xs-12">
                                            <div class="form-group">
                                                <label for="or_female_number" class="female-label">Ortho Female</label>
                                                <div class="form-line">
                                                    <input type="number" class="form-control gender-count" name="or_female_number" id="or_female_number" min="0" value="" onkeyup="getTotal()">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-xs-12">
                                            <div class="form-group">
                                                <label for="op_male_number" class="male-label">Ophto Male</label>
                                                <div class="form-line">
                                                    <input type="number" class="form-control gender-count" name="op_male_number" id="op_male_number" min="0" value="" onkeyup="getTotal()">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-xs-12">
                                            <div class="form-group">
                                                <label for="op_female_number" class="female-label">Ophto Female</label>
                                                <div class="form-line">
                                                    <input type="number" class="form-control gender-count" name="op_female_number" id="op_female_number" min="0" value="" onkeyup="getTotal()">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-xs-12">
                                            <div class="form-group">
                                                <label for="og_male_number" class="male-label">OBG Male</label>
                                                <div class="form-line">
                                                    <input type="number" class="form-control gender-count" name="og_male_number" id="og_male_number" min="0" value="" onkeyup="getTotal()">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-xs-12">
                                            <div class="form-group">
                                                <label for="og_female_number" class="female-label">OBG Female</label>
                                                <div class="form-line">
                                                    <input type="number" class="form-control gender-count" name="og_female_number" id="og_female_number" min="0" value="" onkeyup="getTotal()">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-xs-12">
                                            <div class="form-group">
                                                <label for="sg_male_number" class="male-label">General Surgery Male</label>
                                                <div class="form-line">
                                                    <input type="number" class="form-control gender-count" name="sg_male_number" id="sg_male_number" min="0" value="" onkeyup="getTotal()">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-xs-12">
                                            <div class="form-group">
                                                <label for="sg_female_number" class="female-label">General Surgery Female</label>
                                                <div class="form-line">
                                                    <input type="number" class="form-control gender-count" name="sg_female_number" id="sg_female_number" min="0" value="" onkeyup="getTotal()">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-xs-12">
                                            <div class="form-group">
                                                <label for="en_male_number" class="male-label">ENT Male</label>
                                                <div class="form-line">
                                                    <input type="number" class="form-control gender-count" name="en_male_number" id="en_male_number" min="0" value="" onkeyup="getTotal()">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-xs-12">
                                            <div class="form-group">
                                                <label for="en_female_number" class="female-label">ENT Female</label>
                                                <div class="form-line">
                                                    <input type="number" class="form-control gender-count" name="en_female_number" id="en_female_number" min="0" value="" onkeyup="getTotal()">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-xs-12">
                                            <div class="form-group">
                                                <label for="me_male_number" class="male-label">General Medicine Male</label>
                                                <div class="form-line">
                                                    <input type="number" class="form-control gender-count" name="me_male_number" id="me_male_number" min="0" value="" onkeyup="getTotal()">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-xs-12">
                                            <div class="form-group">
                                                <label for="me_female_number" class="female-label">General Medicine Female</label>
                                                <div class="form-line">
                                                    <input type="number" class="form-control gender-count" name="me_female_number" id="me_female_number" min="0" value="" onkeyup="getTotal()">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- <div class="row"> -->
                                        <!--<div class="col-sm-4 col-xs-12">-->
                                        <!--	<div class="form-group">-->
                                        <!--		<label for="type">Number of <span class="text-danger"> Discharge</label>-->
                                        <!--		<div class="form-line">-->
                                        <!--			<input type="text" class="form-control" name="discharge" id="discharge" min="0" value="" >-->
                                        <!--		</div>-->
                                        <!--	</div>-->
                                        <!--</div>-->
                                        <div class="col-sm-4 col-xs-12">
                                            <div class="form-group">
                                                <label for="type">Date</label>
                                                <div class="form-line">
                                                    <input type="date" class="form-control" name="date" id="date" value="<?= date("Y-m-d"); ?>">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-12 ">
                                            <p class="h2">Total : <span id="total"></span></p>
                                            <input type="submit" name="submit" id="submit" value="Submit" class="btn btn-success float-right">
                                        </div>





                                    </div>
                                </form>
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

    <script>
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            history.go(1);
        };

        function getTotal() {
            const form = document.getElementById("frmtreat");
            let sum = 0;

            form.querySelectorAll("input[type='number']").forEach(input => {
                sum += parseInt(input.value) || 0;
            });
            document.getElementById('total').innerHTML = sum;
        }
    </script>



    <!-- Sweet Alerts js -->
    <script src="css/sweetalert2/sweetalert2.min.js"></script>

    <!-- Sweet alert init js-->
    <script src="js/sweet-alerts.init.js"></script>


</body>

</html>