<?php 
require_once(__DIR__ . '/model/session_config.php');
if(!isset($_SESSION['loginid']))
{
echo "<script>window.location='index.php';</script>";
}
date_default_timezone_set("Asia/Kolkata");  
include('model/dbconnect.php');

function escapeBulkValue($con, $value)
{
    return mysqli_real_escape_string($con, (string)$value);
}

function fetchBulkRows($con, $sql)
{
    $rows = array();
    $res = mysqli_query($con, $sql);

    if($res)
    {
        while($row = mysqli_fetch_assoc($res))
        {
            $rows[] = $row;
        }
    }

    return $rows;
}

function getBulkGeneratedOpdCounters($con, $date)
{
    $opd_no = 0;
    $uhid = 0;
    $series_res = mysqli_query($con, "SELECT id, table_id FROM data_table WHERE id IN (1,4) ORDER BY id");

    if($series_res)
    {
        while($series_row = mysqli_fetch_assoc($series_res))
        {
            if($series_row['id'] == 1)
            {
                $opd_no = (int)$series_row['table_id'] + 1;
            }
            elseif($series_row['id'] == 4)
            {
                $uhid = (int)$series_row['table_id'] + 1;
            }
        }
    }

    $y = date("Ymd", strtotime($date));

    if($y != substr((string)$opd_no, 0, 8))
    {
        $opd_res = mysqli_query($con, "SELECT SUBSTR(MAX(opd_no),3,12) as opd FROM central_opd WHERE opd_no LIKE 'OP$y%'");
        $opd_row = $opd_res ? mysqli_fetch_assoc($opd_res) : array();
        $opd_no = !empty($opd_row['opd']) ? ((int)$opd_row['opd'] + 1) : (int)($y.'0001');
    }

    if($y != substr((string)$uhid, 0, 8))
    {
        $uhid_res = mysqli_query($con, "SELECT SUBSTR(MAX(uhid),4,12) as uhid FROM central_opd WHERE uhid LIKE 'SGN$y%'");
        $uhid_row = $uhid_res ? mysqli_fetch_assoc($uhid_res) : array();
        $uhid = !empty($uhid_row['uhid']) ? ((int)$uhid_row['uhid'] + 1) : (int)($y.'0001');
    }

    return array('opd_no' => $opd_no, 'uhid' => $uhid);
}

function getBulkNewPatientRows($con, $count, $patientWhere, $templateTable, $templateWhere, $useTemplateAge=true)
{
    $count = (int)$count;
    if($count <= 0)
    {
        return array();
    }

    $patient_sql = "SELECT name, age, gender, caste, occupation FROM patient_data WHERE $patientWhere ORDER BY RAND() LIMIT $count";
    $template_sql = "SELECT d.address, d.diagnosis, d.symptoms, d.dept, d.age as template_age, st.id as doctor_id FROM `$templateTable` d 
    LEFT JOIN doctor st ON st.department=d.dept and st.status='active' and st.schedule_type='1' WHERE $templateWhere ORDER BY RAND() LIMIT $count";

    $patients = fetchBulkRows($con, $patient_sql);
    $templates = fetchBulkRows($con, $template_sql);
    $rows = array();
    $total = min(count($patients), count($templates));

    for($i=0; $i<$total; $i++)
    {
        $patient = $patients[$i];
        $template = $templates[$i];
        $rows[] = array(
            'name' => $patient['name'],
            'age' => ($useTemplateAge && isset($template['template_age']) && $template['template_age'] !== '') ? $template['template_age'] : $patient['age'],
            'gender' => $patient['gender'],
            'occupation' => $patient['occupation'],
            'caste' => $patient['caste'],
            'address' => isset($template['address']) ? $template['address'] : '',
            'diagnosis' => isset($template['diagnosis']) ? $template['diagnosis'] : '',
            'dept' => isset($template['dept']) ? $template['dept'] : 0,
            'doctor_id' => isset($template['doctor_id']) ? $template['doctor_id'] : 0,
            'symptoms' => isset($template['symptoms']) ? $template['symptoms'] : ''
        );
    }

    return $rows;
}

function getBulkOldPatientRows($con, $date, $dept_id, $count)
{
    $count = (int)$count;
    $dept_id = (int)$dept_id;
    $date = mysqli_real_escape_string($con, $date);

    if($count <= 0 || $dept_id <= 0)
    {
        return array();
    }

    $sql = "SELECT c.uhid, c.name, c.age, c.gender, c.mobile, c.address, c.diagnosis, c.dept_id, c.sub_deptid, c.annual_income, c.religion, c.caste, c.category, c.parent_name, c.education, c.marital_status, c.occupation, c.economic_state, c.bpl_no, st.id as doctor_id
            FROM central_opd c
            LEFT JOIN doctor st ON st.department=c.dept_id and st.status='active' and st.schedule_type='1'
            WHERE c.dept_id='$dept_id'
              AND c.old_patient=0
              AND c.is_generated=1
              AND c.is_ipd=0
              AND c.uhid NOT IN (SELECT uhid FROM central_opd WHERE date='$date')
              AND c.date < DATE_SUB('$date', INTERVAL 5 DAY)
              AND c.date > '$date' - INTERVAL 8 WEEK
            ORDER BY RAND()
            LIMIT $count";

    return fetchBulkRows($con, $sql);
}

if(isset($_REQUEST['submit']))
{
    $newlogin = new userlogin;
    $con = $newlogin->dbconnect();
    $year = date("Y", strtotime($_REQUEST['date']));
    $date = $_REQUEST['date'];
    $safe_date = escapeBulkValue($con, $date);

    $pd = isset($_REQUEST['pd_number']) ? (int)$_REQUEST['pd_number'] : 0;
    $ps = isset($_REQUEST['ps_number']) ? (int)$_REQUEST['ps_number'] : 0;
    $sk = isset($_REQUEST['sk_number']) ? (int)$_REQUEST['sk_number'] : 0;
    $tr = isset($_REQUEST['tr_number']) ? (int)$_REQUEST['tr_number'] : 0;
    $or = isset($_REQUEST['or_number']) ? (int)$_REQUEST['or_number'] : 0;
    $op = isset($_REQUEST['op_number']) ? (int)$_REQUEST['op_number'] : 0;
    $og = isset($_REQUEST['og_number']) ? (int)$_REQUEST['og_number'] : 0;
    $dn = isset($_REQUEST['dn_number']) ? (int)$_REQUEST['dn_number'] : 0;
    $sg = isset($_REQUEST['sg_number']) ? (int)$_REQUEST['sg_number'] : 0;
    $en = isset($_REQUEST['en_number']) ? (int)$_REQUEST['en_number'] : 0;
    $me = isset($_REQUEST['me_number']) ? (int)$_REQUEST['me_number'] : 0;
    $em = isset($_REQUEST['em_number']) ? (int)$_REQUEST['em_number'] : 0;

    $oldpd = isset($_REQUEST['oldpd_number']) ? (int)$_REQUEST['oldpd_number'] : 0;
    $oldps = isset($_REQUEST['oldps_number']) ? (int)$_REQUEST['oldps_number'] : 0;
    $oldsk = isset($_REQUEST['oldsk_number']) ? (int)$_REQUEST['oldsk_number'] : 0;
    $oldtr = isset($_REQUEST['oldtr_number']) ? (int)$_REQUEST['oldtr_number'] : 0;
    $oldor = isset($_REQUEST['oldor_number']) ? (int)$_REQUEST['oldor_number'] : 0;
    $oldop = isset($_REQUEST['oldop_number']) ? (int)$_REQUEST['oldop_number'] : 0;
    $oldog = isset($_REQUEST['oldog_number']) ? (int)$_REQUEST['oldog_number'] : 0;
    $oldsg = isset($_REQUEST['oldsg_number']) ? (int)$_REQUEST['oldsg_number'] : 0;
    $olden = isset($_REQUEST['olden_number']) ? (int)$_REQUEST['olden_number'] : 0;
    $oldme = isset($_REQUEST['oldme_number']) ? (int)$_REQUEST['oldme_number'] : 0;
    $oldem = isset($_REQUEST['oldem_number']) ? (int)$_REQUEST['oldem_number'] : 0;

    $mpd = round($pd * 60 / 100);
    $fpd = $pd - $mpd;
    $mps = round($ps * 60 / 100);
    $fps = $ps - $mps;
    $msk = round($sk * 60 / 100);
    $fsk = $sk - $msk;
    $mtr = round($tr * 60 / 100);
    $ftr = $tr - $mtr;
    $mor = round($or * 60 / 100);
    $for = $or - $mor;
    $mop = round($op * 60 / 100);
    $fop = $op - $mop;
    $mdn = round($dn * 60 / 100);
    $fdn = $dn - $mdn;
    $msg = round($sg * 60 / 100);
    $fsg = $sg - $msg;
    $men = round($en * 60 / 100);
    $fen = $en - $men;
    $mme = round($me * 60 / 100);
    $fme = $me - $mme;
    $mem = round($em * 60 / 100);
    $fem = $em - $mem;

    $new_patient_groups = array(
        array('count' => $mpd, 'patient_where' => "age < 14 AND gender='male'", 'template_table' => 'temp', 'template_where' => "sex='male' AND dept=2", 'use_template_age' => true),
        array('count' => $fpd, 'patient_where' => "age < 14 AND gender='female'", 'template_table' => 'temp', 'template_where' => "sex='female' AND dept=2", 'use_template_age' => true),
        array('count' => $mps, 'patient_where' => "age > 11 AND gender='male'", 'template_table' => 'temp', 'template_where' => "sex='male' AND dept=3", 'use_template_age' => true),
        array('count' => $fps, 'patient_where' => "age > 11 AND gender='female'", 'template_table' => 'temp', 'template_where' => "sex='female' AND dept=3", 'use_template_age' => true),
        array('count' => $msk, 'patient_where' => "age > 11 AND gender='male'", 'template_table' => 'temp', 'template_where' => "sex='male' AND dept=4", 'use_template_age' => true),
        array('count' => $fsk, 'patient_where' => "age > 11 AND gender='female'", 'template_table' => 'temp', 'template_where' => "sex='female' AND dept=4", 'use_template_age' => true),
        array('count' => $mtr, 'patient_where' => "age > 11 AND gender='male'", 'template_table' => 'temp', 'template_where' => "sex='male' AND dept=5", 'use_template_age' => true),
        array('count' => $ftr, 'patient_where' => "age > 11 AND gender='female'", 'template_table' => 'temp', 'template_where' => "sex='female' AND dept=5", 'use_template_age' => true),
        array('count' => $mor, 'patient_where' => "age > 11 AND gender='male'", 'template_table' => 'temp', 'template_where' => "sex='male' AND dept=7", 'use_template_age' => true),
        array('count' => $for, 'patient_where' => "age > 11 AND gender='female'", 'template_table' => 'temp', 'template_where' => "sex='female' AND dept=7", 'use_template_age' => true),
        array('count' => $mop, 'patient_where' => "gender='male'", 'template_table' => 'temp', 'template_where' => "sex='male' AND dept=11", 'use_template_age' => true),
        array('count' => $fop, 'patient_where' => "gender='female'", 'template_table' => 'temp', 'template_where' => "sex='female' AND dept=11", 'use_template_age' => true),
        array('count' => $og, 'patient_where' => "age > 18 AND gender='female'", 'template_table' => 'temp', 'template_where' => "sex='female' AND dept=12", 'use_template_age' => true),
        array('count' => $mdn, 'patient_where' => "age BETWEEN 11 AND 50 AND gender='male'", 'template_table' => 'daignosis_data', 'template_where' => "dept=8", 'use_template_age' => false),
        array('count' => $fdn, 'patient_where' => "age BETWEEN 11 AND 50 AND gender='female'", 'template_table' => 'daignosis_data', 'template_where' => "dept=8", 'use_template_age' => false),
        array('count' => $msg, 'patient_where' => "age > 11 AND gender='male'", 'template_table' => 'temp', 'template_where' => "sex='male' AND dept=6", 'use_template_age' => true),
        array('count' => $fsg, 'patient_where' => "age > 11 AND gender='female'", 'template_table' => 'temp', 'template_where' => "sex='female' AND dept=6", 'use_template_age' => true),
        array('count' => $men, 'patient_where' => "age > 11 AND gender='male'", 'template_table' => 'temp', 'template_where' => "sex='male' AND dept=18", 'use_template_age' => true),
        array('count' => $fen, 'patient_where' => "age > 11 AND gender='female'", 'template_table' => 'temp', 'template_where' => "sex='female' AND dept=18", 'use_template_age' => true),
        array('count' => $mme, 'patient_where' => "age > 11 AND gender='male'", 'template_table' => 'temp', 'template_where' => "sex='male' AND dept=1", 'use_template_age' => true),
        array('count' => $fme, 'patient_where' => "age > 11 AND gender='female'", 'template_table' => 'temp', 'template_where' => "sex='female' AND dept=1", 'use_template_age' => true),
        array('count' => $mem, 'patient_where' => "age > 11 AND gender='male'", 'template_table' => 'temp', 'template_where' => "sex='male' AND dept=17 AND age > 15", 'use_template_age' => true),
        array('count' => $fem, 'patient_where' => "age > 11 AND gender='female'", 'template_table' => 'temp', 'template_where' => "sex='female' AND dept=17 AND age > 15", 'use_template_age' => true)
    );

    $new_patient_rows = array();
    foreach($new_patient_groups as $group)
    {
        $new_patient_rows = array_merge(
            $new_patient_rows,
            getBulkNewPatientRows(
                $con,
                $group['count'],
                $group['patient_where'],
                $group['template_table'],
                $group['template_where'],
                $group['use_template_age']
            )
        );
    }

    if(!empty($new_patient_rows))
    {
        shuffle($new_patient_rows);
    }

    $series = getBulkGeneratedOpdCounters($con, $date);
    $next_opd_no = $series['opd_no'];
    $next_uhid = $series['uhid'];
    $created_by = escapeBulkValue($con, $_SESSION['name']);
    $new_insert_values = array();

    foreach($new_patient_rows as $row)
    {
        $dept_id = (int)$row['dept'];
        if($dept_id <= 0)
        {
            continue;
        }

        $uhid_value = 'SGN'.$next_uhid;
        $opd_value = 'OP'.$next_opd_no;
        $name = escapeBulkValue($con, $row['name']);
        $age = escapeBulkValue($con, $row['age']);
        $gender = escapeBulkValue($con, $row['gender']);
        $occupation = escapeBulkValue($con, $row['occupation']);
        $caste = escapeBulkValue($con, $row['caste']);
        $address = escapeBulkValue($con, $row['address']);
        $diagnosis = escapeBulkValue($con, $row['diagnosis']);
        $doctor_id = (int)$row['doctor_id'];
        $symptoms = escapeBulkValue($con, $row['symptoms']);

        $new_insert_values[] = "('$uhid_value','$opd_value','$safe_date','$name','$age','$gender','$occupation','$caste','$address','$diagnosis','$dept_id','$doctor_id','1','$created_by','$symptoms')";
        $next_opd_no++;
        $next_uhid++;
    }

    if(!empty($new_insert_values))
    {
        $new_insert_query = "INSERT INTO `central_opd`(`uhid`,`opd_no`,`date`, `name`, `age`, `gender`,`occupation`, `caste`, `address`, `diagnosis`, `dept_id`,`doctor`,`is_generated`,`created_by`,`symptoms`) VALUES ".implode(",", $new_insert_values);
        mysqli_query($con, $new_insert_query);
    }

    $old_department_counts = array(
        1 => $oldme,
        2 => $oldpd,
        3 => $oldps,
        4 => $oldsk,
        5 => $oldtr,
        6 => $oldsg,
        7 => $oldor,
        11 => $oldop,
        12 => $oldog,
        18 => $olden,
        17 => $oldem
    );

    $old_patient_rows = array();
    foreach($old_department_counts as $dept_id => $count)
    {
        $old_patient_rows = array_merge($old_patient_rows, getBulkOldPatientRows($con, $date, $dept_id, $count));
    }

    if(!empty($old_patient_rows))
    {
        shuffle($old_patient_rows);
    }

    $old_insert_values = array();
    foreach($old_patient_rows as $row)
    {
        $dept_id = (int)$row['dept_id'];
        $uhid = escapeBulkValue($con, $row['uhid']);

        if($dept_id <= 0 || $uhid === '')
        {
            continue;
        }

        $opd_value = 'OP'.$next_opd_no;
        $name = escapeBulkValue($con, $row['name']);
        $age = escapeBulkValue($con, $row['age']);
        $gender = escapeBulkValue($con, $row['gender']);
        $mobile = escapeBulkValue($con, $row['mobile']);
        $address = escapeBulkValue($con, $row['address']);
        $diagnosis = escapeBulkValue($con, $row['diagnosis']);
        $sub_deptid = escapeBulkValue($con, $row['sub_deptid']);
        $annual_income = escapeBulkValue($con, $row['annual_income']);
        $religion = escapeBulkValue($con, $row['religion']);
        $caste = escapeBulkValue($con, $row['caste']);
        $category = escapeBulkValue($con, $row['category']);
        $parent_name = escapeBulkValue($con, $row['parent_name']);
        $education = escapeBulkValue($con, $row['education']);
        $marital_status = escapeBulkValue($con, $row['marital_status']);
        $occupation = escapeBulkValue($con, $row['occupation']);
        $economic_state = escapeBulkValue($con, $row['economic_state']);
        $bpl_no = escapeBulkValue($con, $row['bpl_no']);
        $doctor_id = (int)$row['doctor_id'];

        $old_insert_values[] = "('$uhid','$opd_value','$name','$age','$gender','$mobile','$address','$diagnosis','$dept_id','$sub_deptid','$annual_income','$religion','$caste','$category','$parent_name','$education','$marital_status','$occupation','$economic_state','$bpl_no','$safe_date','1','$doctor_id','1','$created_by')";
        $next_opd_no++;
    }

    if(!empty($old_insert_values))
    {
        $old_insert_query = "INSERT INTO `central_opd`(`uhid`,`opd_no`, `name`, `age`, `gender`, `mobile`, `address`, `diagnosis`, `dept_id`, `sub_deptid`, `annual_income`, `religion`, `caste`, `category`, `parent_name`, `education`, `marital_status`, `occupation`, `economic_state`, `bpl_no`,`date`,`old_patient`,`doctor`,`is_generated`,`created_by`) VALUES ".implode(",", $old_insert_values);
        mysqli_query($con, $old_insert_query);
    }

    if(!empty($new_insert_values) || !empty($old_insert_values))
    {
        mysqli_query($con, "UPDATE data_table SET table_id='".($next_opd_no - 1)."' WHERE id=1");
    }

    if(!empty($new_insert_values))
    {
        mysqli_query($con, "UPDATE data_table SET table_id='".($next_uhid - 1)."' WHERE id=4");
    }

    $select_crn = "SELECT @i:=0";
    mysqli_query($con, $select_crn);
    $update_crn = "UPDATE `central_opd` SET year_no=@i:=@i+1 WHERE date LIKE '$year-%'";
    mysqli_query($con, $update_crn);

    echo "<script>window.location='patient_data.php';</script>";
    exit;
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
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" ></script>
        <!-- Sweet Alert-->
     <link href="css/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
 
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
								<label for="type">Number of New <span class="text-danger">Pediatrics</span> Patient</label>
								<div class="form-line">
									<input type="text" class="form-control" name="pd_number" id="pd_number" value="" onkeyup="getTotal()">
								</div>
							</div>
						</div>
						<div class="col-sm-3 col-xs-12">
							<div class="form-group">
								<label for="type">Number of Old <span class="text-primary">Pediatrics</span> Patient</label>
								<div class="form-line">
									<input type="text" class="form-control" name="oldpd_number" id="pd_number" value="" onkeyup="getTotal()">
								</div>
							</div>
						</div>
						<div class="col-sm-3 col-xs-12">
							<div class="form-group">
								<label for="type">Number of New <span class="text-danger">Psychiatry</span> Patient</label>
								<div class="form-line">
									<input type="text" class="form-control" name="ps_number" id="ps_number" value="" onkeyup="getTotal()">
								</div>
							</div>
						</div>
						<div class="col-sm-3 col-xs-12">
							<div class="form-group">
								<label for="type">Number of Old <span class="text-primary">Psychiatry</span> Patient</label>
								<div class="form-line">
									<input type="text" class="form-control" name="oldps_number" id="ps_number" value="" onkeyup="getTotal()">
								</div>
							</div>
						</div>
						<div class="col-sm-3 col-xs-12">
							<div class="form-group">
								<label for="type">Number of New <span class="text-danger">Skin</span> Patient</label>
								<div class="form-line">
									<input type="text" class="form-control" name="sk_number" id="sk_number" value="" onkeyup="getTotal()">
								</div>
							</div>
						</div>
						<div class="col-sm-3 col-xs-12">
							<div class="form-group">
								<label for="type">Number of Old <span class="text-primary">Skin</span> Patient</label>
								<div class="form-line">
									<input type="text" class="form-control" name="oldsk_number" id="sk_number" value="" onkeyup="getTotal()">
								</div>
							</div>
						</div>
						<!-- <div class="col-sm-3 col-xs-12">
							<div class="form-group">
								<label for="type">Number of New <span class="text-danger">TBCD</span> Patient</label>
								<div class="form-line"> -->
									<input type="hidden" class="form-control" name="tr_number" id="tr_number" value="" onkeyup="getTotal()">
								<!-- </div>
							</div>
						</div> -->
						<!-- <div class="col-sm-3 col-xs-12">
							<div class="form-group">
								<label for="type">Number of Old <span class="text-primary">TBCD</span> Patient</label>
								<div class="form-line"> -->
									<input type="hidden" class="form-control" name="oldtr_number" id="tr_number" value="" onkeyup="getTotal()">
								<!-- </div>
							</div>
						</div> -->
						<div class="col-sm-3 col-xs-12">
							<div class="form-group">
								<label for="type">Number of New <span class="text-danger">Ortho</span> Patient</label>
								<div class="form-line">
									<input type="text" class="form-control" name="or_number" id="or_number" value="" onkeyup="getTotal()">
								</div>
							</div>
						</div>
						<div class="col-sm-3 col-xs-12">
							<div class="form-group">
								<label for="type">Number of Old <span class="text-primary">Ortho</span> Patient</label>
								<div class="form-line">
									<input type="text" class="form-control" name="oldor_number" id="or_number" value="" onkeyup="getTotal()">
								</div>
							</div>
						</div>
						<div class="col-sm-3 col-xs-12">
							<div class="form-group">
								<label for="type">Number of New <span class="text-danger">Ophto</span> Patient</label>
								<div class="form-line">
									<input type="text" class="form-control" name="op_number" id="op_number" value="" onkeyup="getTotal()">
								</div>
							</div>
						</div>
						<div class="col-sm-3 col-xs-12">
							<div class="form-group">
								<label for="type">Number of Old <span class="text-primary">Ophto</span> Patient</label>
								<div class="form-line">
									<input type="text" class="form-control" name="oldop_number" id="op_number" value="" onkeyup="getTotal()">
								</div>
							</div>
						</div>
						<div class="col-sm-3 col-xs-12">
							<div class="form-group">
								<label for="type">Number of New <span class="text-danger">OBG</span> Patient</label>
								<div class="form-line">
									<input type="text" class="form-control" name="og_number" id="og_number" value="" onkeyup="getTotal()">
								</div>
							</div>
						</div>
						<div class="col-sm-3 col-xs-12">
							<div class="form-group">
								<label for="type">Number of Old <span class="text-primary">OBG</span> Patient</label>
								<div class="form-line">
									<input type="text" class="form-control" name="oldog_number" id="oldog_number" value="" onkeyup="getTotal()">
								</div>
							</div>
						</div>
						
						
						<div class="col-sm-3 col-xs-12">
							<div class="form-group">
								<label for="type">Number of New <span class="text-danger">General Surgery</span> Patient</label>
								<div class="form-line">
									<input type="text" class="form-control" name="sg_number" id="sg_number" value="" onkeyup="getTotal()">
								</div>
							</div>
						</div>
							<div class="col-sm-3 col-xs-12">
							<div class="form-group">
								<label for="type">Number of old <span class="text-primary">General Surgery</span> Patient</label>
								<div class="form-line">
									<input type="text" class="form-control" name="oldsg_number" id="oldsg_number" value="" onkeyup="getTotal()">
								</div>
							</div>
						</div>
						<div class="col-sm-3 col-xs-12">
							<div class="form-group">
								<label for="type">Number of New <span class="text-danger">ENT</span> Patient</label>
								<div class="form-line">
									<input type="text" class="form-control" name="en_number" id="en_number" value="" onkeyup="getTotal()">
								</div>
							</div>
						</div>
						<div class="col-sm-3 col-xs-12">
							<div class="form-group">
								<label for="type">Number of old <span class="text-primary">ENT</span> Patient</label>
								<div class="form-line">
									<input type="text" class="form-control" name="olden_number" id="olden_number" value="" onkeyup="getTotal()">
								</div>
							</div>
						</div>
						<div class="col-sm-3 col-xs-12">
							<div class="form-group">
								<label for="type">Number of New <span class="text-danger">General Medicine</span> Patient</label>
								<div class="form-line">
									<input type="text" class="form-control" name="me_number" id="me_number" value="" onkeyup="getTotal()">
								</div>
							</div>
						</div>
						
						<div class="col-sm-3 col-xs-12">
							<div class="form-group">
								<label for="type">Number of Old <span class="text-primary">General Medicine</span> Patient</label>
								<div class="form-line">
									<input type="text" class="form-control" name="oldme_number" id="oldme_number" value="" onkeyup="getTotal()">
								</div>
							</div>
						</div>
						<div class="col-sm-3 col-xs-12">
							<div class="form-group">
								<label for="type">Number of New <span class="text-danger">Dental</span> Patient</label>
								<div class="form-line">
									<input type="text" class="form-control" name="dn_number" id="dn_number" value="" onkeyup="getTotal()">
								</div>
							</div>
						</div>
					
						
						<div class="col-sm-3 col-xs-12">
							<div class="form-group">
								<label for="type">Number of New <span class="text-danger">Emergency</span> Patient</label>
								<div class="form-line">
									<input type="text" class="form-control" name="em_number" id="em_number" value="" onkeyup="getTotal()">
								</div>
							</div>
						</div>
						<div class="col-sm-3 col-xs-12">
							<div class="form-group">
								<label for="type">Number of Old <span class="text-primary">Emergency</span> Patient</label>
								<div class="form-line">
								    <input type="text" class="form-control" name="oldem_number" id="oldem_number" value="" onkeyup="getTotal()">
								</div>
							</div>
						</div>
					
						<div class="col-sm-3 col-xs-12">
							<div class="form-group">
								<label for="type">Date</label>
								<div class="form-line">
									<input type="date" class="form-control" name="date" id="date" value="<?= date("Y-m-d");?>">
								</div>
							</div>
						</div>
						
					
										<div class="col-sm-12 ">
										    <p class="h2">Total : <span id="total"></span></p>
						<input type="submit" name="submit" id="submit" value="Submit" class="btn btn-success float-right">
					</div>

				

			</div></form>
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





<!-- Sweet Alerts js -->
<script src="css/sweetalert2/sweetalert2.min.js"></script>

<!-- Sweet alert init js-->
<script src="js/sweet-alerts.init.js"></script>
  <script>
      function getTotal()
      {
          const form = document.getElementById("frmtreat");
        let sum = 0;
        
        form.querySelectorAll("input[type='text']").forEach(input => {
          sum += parseInt(input.value) || 0;
        });
        document.getElementById('total').innerHTML=sum;
      }
  </script>

</body>

</html>
