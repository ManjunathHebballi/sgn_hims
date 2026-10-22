<?php
require_once(__DIR__ . '/model/session_config.php');
if (!isset($_SESSION['loginid'])) {
    echo "<script>window.location='index.php';</script>";
    exit;
}
date_default_timezone_set("Asia/Kolkata");
include('model/dbconnect.php');

$message = '';

if (isset($_REQUEST['submit'])) {
    $newlogin = new userlogin;
    $con = $newlogin->dbconnect();
    $date = mysqli_real_escape_string($con, $_REQUEST['date']);
    $created_by = mysqli_real_escape_string($con, $_SESSION['name']);

    $pd_male   = (int)($_REQUEST['pd_male_number']   ?? 0);
    $pd_female = (int)($_REQUEST['pd_female_number'] ?? 0);
    $me_male   = (int)($_REQUEST['me_male_number']   ?? 0);
    $me_female = (int)($_REQUEST['me_female_number'] ?? 0);
    $ps_male   = (int)($_REQUEST['ps_male_number']   ?? 0);
    $ps_female = (int)($_REQUEST['ps_female_number'] ?? 0);
    $sk_male   = (int)($_REQUEST['sk_male_number']   ?? 0);
    $sk_female = (int)($_REQUEST['sk_female_number'] ?? 0);
    $sg_male   = (int)($_REQUEST['sg_male_number']   ?? 0);
    $sg_female = (int)($_REQUEST['sg_female_number'] ?? 0);
    $or_male   = (int)($_REQUEST['or_male_number']   ?? 0);
    $or_female = (int)($_REQUEST['or_female_number'] ?? 0);
    $op_male   = (int)($_REQUEST['op_male_number']   ?? 0);
    $op_female = (int)($_REQUEST['op_female_number'] ?? 0);
    $og_male   = (int)($_REQUEST['og_male_number']   ?? 0);
    $og_female = (int)($_REQUEST['og_female_number'] ?? 0);
    $en_male   = (int)($_REQUEST['en_male_number']   ?? 0);
    $en_female = (int)($_REQUEST['en_female_number'] ?? 0);
    $em_male   = (int)($_REQUEST['em_male_number']   ?? 0);
    $em_female = (int)($_REQUEST['em_female_number'] ?? 0);

    $gendered_department_counts = array(
        array('dept_id' => 2,  'gender' => 'Male',   'count' => $pd_male),
        array('dept_id' => 2,  'gender' => 'Female',  'count' => $pd_female),
        array('dept_id' => 1,  'gender' => 'Male',   'count' => $me_male),
        array('dept_id' => 1,  'gender' => 'Female',  'count' => $me_female),
        array('dept_id' => 3,  'gender' => 'Male',   'count' => $ps_male),
        array('dept_id' => 3,  'gender' => 'Female',  'count' => $ps_female),
        array('dept_id' => 4,  'gender' => 'Male',   'count' => $sk_male),
        array('dept_id' => 4,  'gender' => 'Female',  'count' => $sk_female),
        array('dept_id' => 6,  'gender' => 'Male',   'count' => $sg_male),
        array('dept_id' => 6,  'gender' => 'Female',  'count' => $sg_female),
        array('dept_id' => 7,  'gender' => 'Male',   'count' => $or_male),
        array('dept_id' => 7,  'gender' => 'Female',  'count' => $or_female),
        array('dept_id' => 11, 'gender' => 'Male',   'count' => $op_male),
        array('dept_id' => 11, 'gender' => 'Female',  'count' => $op_female),
        array('dept_id' => 12, 'gender' => 'Male',   'count' => $og_male),
        array('dept_id' => 12, 'gender' => 'Female',  'count' => $og_female),
        array('dept_id' => 18, 'gender' => 'Male',   'count' => $en_male),
        array('dept_id' => 18, 'gender' => 'Female',  'count' => $en_female),
        array('dept_id' => 17, 'gender' => 'Male',   'count' => $em_male),
        array('dept_id' => 17, 'gender' => 'Female',  'count' => $em_female),
    );

    $discharge_array = array();
    $bed_array = array();
    $ipd_array = array();
    $discdate = date('Y-m-d', strtotime($date . ' -1 day'));

    foreach ($gendered_department_counts as $entry) {
        if ($entry['count'] <= 0) {
            continue;
        }

        $dept_id = (int)$entry['dept_id'];
        $gender  = mysqli_real_escape_string($con, $entry['gender']);
        $count   = $entry['count'];

        $sql = "SELECT ipd_no, admission_date, name, age, gender, address, dept_id, bed_id, diagnosis
                FROM central_ipd
                WHERE discharge = 0
                  AND admission_date < '$discdate'
                  AND is_generated = 1
                  AND dept_id = '$dept_id'
                  AND gender = '$gender'
                ORDER BY admission_date
                LIMIT $count";
        $res = mysqli_query($con, $sql);
        while ($row = mysqli_fetch_assoc($res)) {
            $discharge_array[] = "('" . mysqli_real_escape_string($con, $row['ipd_no']) . "','" . mysqli_real_escape_string($con, $row['name']) . "','" . mysqli_real_escape_string($con, $row['dept_id']) . "','" . mysqli_real_escape_string($con, $row['age']) . "','" . mysqli_real_escape_string($con, $row['gender']) . "','" . mysqli_real_escape_string($con, $row['admission_date']) . "','" . mysqli_real_escape_string($con, $date) . "','" . mysqli_real_escape_string($con, $row['diagnosis']) . "','" . $created_by . "')";
            $bed_array[] = (int)$row['bed_id'];
            $ipd_array[] = mysqli_real_escape_string($con, $row['ipd_no']);
        }
    }

    if (!empty($discharge_array)) {
        $discharge_query = "INSERT INTO `discharge`(`patient_id`, `patient_name`, `dept_id`, `age`, `sex`, `admission_date`, `discharge_date`, `diagnosis`, `created_by`) VALUES " . implode(",", $discharge_array);
        mysqli_query($con, $discharge_query);

        if (!empty($bed_array)) {
            $bed_update = "UPDATE bed SET status='active' WHERE id IN (" . implode(',', array_unique($bed_array)) . ")";
            mysqli_query($con, $bed_update);
        }

        if (!empty($ipd_array)) {
            $ipd_update = "UPDATE central_ipd SET discharge=1 WHERE ipd_no IN ('" . implode("','", array_unique($ipd_array)) . "')";
            mysqli_query($con, $ipd_update);
        }

        $message = 'Bulk discharge completed for ' . count($ipd_array) . ' patient(s).';
    } else {
        $message = 'No eligible patients found for the selected departments and date.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Bulk Department Discharge</title>
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <link href="css/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
    <style>
        .male-label {
            color: #0066cc;
            font-weight: 600;
        }

        .female-label {
            color: #cc0066;
            font-weight: 600;
        }

        .dept-section {
            border-left: 3px solid #dee2e6;
            padding-left: 10px;
            margin-bottom: 8px;
        }

        .dept-title {
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            color: #555;
            margin-bottom: 4px;
        }
    </style>
</head>

<body>
    <div id="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>

    <div id="main-wrapper">
        <?php include('leftbar.php') ?>

        <div class="content-body">
            <div class="container-fluid">
                <div class="row mb-3">
                    <div class="col-md-12">
                        <h4 class="text-uppercase">Bulk Discharge Department Wise</h4>
                        <?php if ($message): ?>
                            <div class="alert alert-info mt-3"><?= $message ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="card-body">
                                <form method="post" action="" name="frmbulkdischarge" id="frmbulkdischarge">
                                    <div class="row">

                                        <!-- Pediatrics -->
                                        <div class="col-sm-3 col-xs-12">
                                            <div class="form-group dept-section">
                                                <div class="dept-title text-danger">Pediatrics</div>
                                                <label class="male-label">Male</label>
                                                <input type="number" class="form-control gender-count" name="pd_male_number" id="pd_male_number" min="0" value="0" onkeyup="getTotal()">
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-xs-12">
                                            <div class="form-group dept-section">
                                                <div class="dept-title" style="color:transparent">Pediatrics</div>
                                                <label class="female-label">Female</label>
                                                <input type="number" class="form-control gender-count" name="pd_female_number" id="pd_female_number" min="0" value="0" onkeyup="getTotal()">
                                            </div>
                                        </div>

                                        <!-- General Medicine -->
                                        <div class="col-sm-3 col-xs-12">
                                            <div class="form-group dept-section">
                                                <div class="dept-title text-danger">General Medicine</div>
                                                <label class="male-label">Male</label>
                                                <input type="number" class="form-control gender-count" name="me_male_number" id="me_male_number" min="0" value="0" onkeyup="getTotal()">
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-xs-12">
                                            <div class="form-group dept-section">
                                                <div class="dept-title" style="color:transparent">General Medicine</div>
                                                <label class="female-label">Female</label>
                                                <input type="number" class="form-control gender-count" name="me_female_number" id="me_female_number" min="0" value="0" onkeyup="getTotal()">
                                            </div>
                                        </div>

                                        <!-- Psychiatry -->
                                        <div class="col-sm-3 col-xs-12">
                                            <div class="form-group dept-section">
                                                <div class="dept-title text-danger">Psychiatry</div>
                                                <label class="male-label">Male</label>
                                                <input type="number" class="form-control gender-count" name="ps_male_number" id="ps_male_number" min="0" value="0" onkeyup="getTotal()">
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-xs-12">
                                            <div class="form-group dept-section">
                                                <div class="dept-title" style="color:transparent">Psychiatry</div>
                                                <label class="female-label">Female</label>
                                                <input type="number" class="form-control gender-count" name="ps_female_number" id="ps_female_number" min="0" value="0" onkeyup="getTotal()">
                                            </div>
                                        </div>

                                        <!-- Skin -->
                                        <div class="col-sm-3 col-xs-12">
                                            <div class="form-group dept-section">
                                                <div class="dept-title text-danger">Skin</div>
                                                <label class="male-label">Male</label>
                                                <input type="number" class="form-control gender-count" name="sk_male_number" id="sk_male_number" min="0" value="0" onkeyup="getTotal()">
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-xs-12">
                                            <div class="form-group dept-section">
                                                <div class="dept-title" style="color:transparent">Skin</div>
                                                <label class="female-label">Female</label>
                                                <input type="number" class="form-control gender-count" name="sk_female_number" id="sk_female_number" min="0" value="0" onkeyup="getTotal()">
                                            </div>
                                        </div>

                                        <!-- General Surgery -->
                                        <div class="col-sm-3 col-xs-12">
                                            <div class="form-group dept-section">
                                                <div class="dept-title text-danger">General Surgery</div>
                                                <label class="male-label">Male</label>
                                                <input type="number" class="form-control gender-count" name="sg_male_number" id="sg_male_number" min="0" value="0" onkeyup="getTotal()">
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-xs-12">
                                            <div class="form-group dept-section">
                                                <div class="dept-title" style="color:transparent">General Surgery</div>
                                                <label class="female-label">Female</label>
                                                <input type="number" class="form-control gender-count" name="sg_female_number" id="sg_female_number" min="0" value="0" onkeyup="getTotal()">
                                            </div>
                                        </div>

                                        <!-- Ortho -->
                                        <div class="col-sm-3 col-xs-12">
                                            <div class="form-group dept-section">
                                                <div class="dept-title text-danger">Ortho</div>
                                                <label class="male-label">Male</label>
                                                <input type="number" class="form-control gender-count" name="or_male_number" id="or_male_number" min="0" value="0" onkeyup="getTotal()">
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-xs-12">
                                            <div class="form-group dept-section">
                                                <div class="dept-title" style="color:transparent">Ortho</div>
                                                <label class="female-label">Female</label>
                                                <input type="number" class="form-control gender-count" name="or_female_number" id="or_female_number" min="0" value="0" onkeyup="getTotal()">
                                            </div>
                                        </div>

                                        <!-- Optho -->
                                        <div class="col-sm-3 col-xs-12">
                                            <div class="form-group dept-section">
                                                <div class="dept-title text-danger">Optho</div>
                                                <label class="male-label">Male</label>
                                                <input type="number" class="form-control gender-count" name="op_male_number" id="op_male_number" min="0" value="0" onkeyup="getTotal()">
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-xs-12">
                                            <div class="form-group dept-section">
                                                <div class="dept-title" style="color:transparent">Optho</div>
                                                <label class="female-label">Female</label>
                                                <input type="number" class="form-control gender-count" name="op_female_number" id="op_female_number" min="0" value="0" onkeyup="getTotal()">
                                            </div>
                                        </div>

                                        <!-- OBG -->
                                        <div class="col-sm-3 col-xs-12">
                                            <div class="form-group dept-section">
                                                <div class="dept-title text-danger">OBG</div>
                                                <label class="male-label">Male</label>
                                                <input type="number" class="form-control gender-count" name="og_male_number" id="og_male_number" min="0" value="0" onkeyup="getTotal()">
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-xs-12">
                                            <div class="form-group dept-section">
                                                <div class="dept-title" style="color:transparent">OBG</div>
                                                <label class="female-label">Female</label>
                                                <input type="number" class="form-control gender-count" name="og_female_number" id="og_female_number" min="0" value="0" onkeyup="getTotal()">
                                            </div>
                                        </div>

                                        <!-- ENT -->
                                        <div class="col-sm-3 col-xs-12">
                                            <div class="form-group dept-section">
                                                <div class="dept-title text-danger">ENT</div>
                                                <label class="male-label">Male</label>
                                                <input type="number" class="form-control gender-count" name="en_male_number" id="en_male_number" min="0" value="0" onkeyup="getTotal()">
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-xs-12">
                                            <div class="form-group dept-section">
                                                <div class="dept-title" style="color:transparent">ENT</div>
                                                <label class="female-label">Female</label>
                                                <input type="number" class="form-control gender-count" name="en_female_number" id="en_female_number" min="0" value="0" onkeyup="getTotal()">
                                            </div>
                                        </div>

                                        <!-- Emergency -->
                                        <div class="col-sm-3 col-xs-12">
                                            <div class="form-group dept-section">
                                                <div class="dept-title text-danger">Emergency</div>
                                                <label class="male-label">Male</label>
                                                <input type="number" class="form-control gender-count" name="em_male_number" id="em_male_number" min="0" value="0" onkeyup="getTotal()">
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-xs-12">
                                            <div class="form-group dept-section">
                                                <div class="dept-title" style="color:transparent">Emergency</div>
                                                <label class="female-label">Female</label>
                                                <input type="number" class="form-control gender-count" name="em_female_number" id="em_female_number" min="0" value="0" onkeyup="getTotal()">
                                            </div>
                                        </div>

                                        <!-- Date -->
                                        <div class="col-sm-4 col-xs-12">
                                            <div class="form-group">
                                                <label>Date</label>
                                                <input type="date" class="form-control" name="date" id="date" value="<?= date('Y-m-d'); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-sm-12">
                                            <p class="h2">Total Discharges: <span id="total">0</span></p>
                                            <button type="submit" name="submit" id="submit" class="btn btn-success float-right">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include('footer.php') ?>
    </div>

    <script src="./vendor/global/global.min.js"></script>
    <script src="./js/quixnav-init.js"></script>
    <script src="./js/custom.min.js"></script>
    <script>
        function getTotal() {
            let sum = 0;
            document.querySelectorAll('.gender-count').forEach(input => {
                sum += parseInt(input.value) || 0;
            });
            document.getElementById('total').innerHTML = sum;
        }
        document.addEventListener('DOMContentLoaded', () => getTotal());
    </script>
</body>

</html>