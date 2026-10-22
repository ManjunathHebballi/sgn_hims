<?php 

date_default_timezone_set("Asia/Kolkata");  
include('model/dbconnect.php');
setlocale(LC_MONETARY,"en_IN");
$newlogin = new userlogin;                  
$con=$newlogin->dbconnect1();
// if(substr($_REQUEST['id'], 0, 2)!='ip')
 $id=$_REQUEST['id'];
 
 // Validate and sanitize testid parameter
 if (!isset($_REQUEST['testid']) || empty($_REQUEST['testid'])) {
     die("Error: testid parameter is required");
 }
 
 $testid='';
 if(isset($_REQUEST['testid']))
 {
     // Handle multiple comma-separated test IDs
     $test_ids = explode(',', $_REQUEST['testid']);
     $test_ids = array_map('trim', $test_ids); // Remove any whitespace
     $test_ids = array_filter($test_ids); // Remove empty values
     
     if (!empty($test_ids)) {
         $test_ids_quoted = array_map(function($id) use ($con) {
            return "'" . mysqli_real_escape_string($con, $id) . "'";
        }, $test_ids);
         $testid = " and lt.testid IN (" . implode(',', $test_ids_quoted) . ")";
     }
 }
 function getLabTestData($con, $patient_id, $bill_no, $testid, $gender) {
    // Handle multiple test IDs
    if (is_array($testid)) {
        $test_ids_quoted = array_map(function($id) use ($con) {
            return "'" . mysqli_real_escape_string($con, $id) . "'";
        }, $testid);
        $testid_condition = "lt.testid IN (" . implode(',', $test_ids_quoted) . ")";
    } else {
        $testid_condition = "lt.testid='$testid'";
    }
    
    // Fetch all test parameters for the given testid, including all types
    $sql = "SELECT 
                ltp.id as parameter_id,
                ltp.name as parametername,
                ltp.type,
                ltp.unit,
                ltp.method,
                ltp.position,
                ltp.gender,
                ltp.status,
                lt.testname,
                lt.testname_header,
                ltp.is_print
            FROM lab_test_parameter ltp,lab_test lt
            WHERE ltp.test_id = lt.testid
              AND ltp.status = 'Active'
              AND $testid_condition
              AND (ltp.gender = '$gender' OR ltp.gender = 'both')
            ORDER BY lt.testid, ltp.position ASC";
    $param_res = mysqli_query($con, $sql);

    $result = [];
    while ($param = mysqli_fetch_assoc($param_res)) {
        $row = $param;

        // Fetch lab_report row for this parameter, patient, bill
        if (is_array($testid)) {
            $test_ids_quoted = array_map(function($id) use ($con) {
                return "'" . mysqli_real_escape_string($con, $id) . "'";
            }, $testid);
            $testid_condition = "lr.testid IN (" . implode(',', $test_ids_quoted) . ")";
        } else {
            $testid_condition = "lr.testid = '$testid'";
        }
        
        $lab_report_sql = "SELECT lr.id, lr.test_result, lr.print_flag
         FROM lab_report lr 

                           WHERE lr.patient_id = '$patient_id' 
                             AND lr.bill_no = '$bill_no' 
                             AND $testid_condition
                             AND lr.parameter_id = '{$param['parameter_id']}'";
                            //  echo $lab_report_sql.'<br>';
        $lab_report_res = mysqli_query($con, $lab_report_sql);
        $lab_report = mysqli_fetch_assoc($lab_report_res);
        $row['lab_report_id'] = $lab_report ? $lab_report['id'] : null;
        $row['test_result'] = $lab_report ? $lab_report['test_result'] : '';
        $row['print_flag'] = $lab_report ? $lab_report['print_flag'] : $param['is_print'];
// echo $lab_report_sql;
        // Fetch normal value, lower/upper limit for 'parameter' type
        if ($param['type'] == 'parameter') {
            $range_sql = "SELECT normal_value, lower_limit, upper_limit 
                          FROM lab_test_range 
                          WHERE parameter_id = '{$param['parameter_id']}' 
                            AND status = 'active' 
                            AND (gender = '$gender' OR gender = 'both')
                          LIMIT 1";
            $range_res = mysqli_query($con, $range_sql);
            $range = mysqli_fetch_assoc($range_res);
            $row['normal_value'] = $range ? $range['normal_value'] : '';
            $row['lower_limit'] = $range ? $range['lower_limit'] : '';
            $row['upper_limit'] = $range ? $range['upper_limit'] : '';
            
        } else {
            $row['normal_value'] = '';
            $row['lower_limit'] = '';
            $row['upper_limit'] = '';
        }

        // Fetch options for 'parameter' type (dropdown)
        if ($param['type'] != 'free_text' && $param['type'] != 'heading') {
            $options_sql = "SELECT options FROM lab_test_result 
                            WHERE test_parameter_id = '{$param['parameter_id']}'";
            $options_res = mysqli_query($con, $options_sql);
            $options = [];
            while ($opt = mysqli_fetch_assoc($options_res)) {
                $options[] = $opt['options'];
            }
            $row['options'] = $options;
        } else {
            $row['options'] = '';
        }

        // For table_format, fetch table data rows
        if ($param['type'] == 'table_format') {
            $table_data_sql = "SELECT * FROM lab_test_table_data_row 
                               WHERE parameter_id = '{$param['parameter_id']}' 
                                 AND status = 'active'
                               ORDER BY position ASC";
            $table_data_res = mysqli_query($con, $table_data_sql);
            $table_data = [];
            while ($td = mysqli_fetch_assoc($table_data_res)) {
                $table_data[] = $td;
            }
            $row['table_data_rows'] = $table_data;
        }

        // For antibiotic type, fetch antibiotic data
        if ($param['type'] == 'antibiotic') {
            $antibiotic_sql = "SELECT * FROM lab_test_antibiotic 
                               WHERE parameter_id = '{$param['parameter_id']}'";
            $antibiotic_res = mysqli_query($con, $antibiotic_sql);
            $antibiotic_data = [];
            while ($ab = mysqli_fetch_assoc($antibiotic_res)) {
                $antibiotic_data[] = $ab;
            }
            $row['antibiotic_data'] = $antibiotic_data;
        }

        $result[] = $row;
    }
    return $result;
}
if(substr($_REQUEST['id'], 0, 2)!='IP')
{
    $query = "SELECT 
                c.*, 
                CONCAT(d.name, ' ', d.last_name) AS doctor, 
                lr.date, 
                lr.visit_no, 
                lr.testid, 
                lt.testname_header, 
                ld.`left_signature`, 
                ld.`right_signature`, 
                ld.`left_e_signature`, 
                ld.`right_e_signature`, 
                case when ISNULL(ld.sample_received_date) then ss.sample_collected_at else ld.sample_received_date end sample_received_date,
                case when ISNULL(ld.reporting_time) then NOW() else ld.reporting_time end reporting_time,
                ld.print_signature_on_every_page,
                lrr.reference_name
            FROM 
                `central_opd` c
                LEFT JOIN `doctor` d ON d.id = c.doctor
                INNER JOIN `lab_report` lr ON lr.patient_id = c.id
                INNER JOIN sample_status ss on ss.patient_id=lr.patient_id and ss.bill_no=lr.bill_no and ss.test_id=lr.testid
                INNER JOIN `lab_test` lt ON lt.testid = lr.testid
                LEFT JOIN `billing` b ON b.bill_no=lr.bill_no
                LEFT JOIN lab_reference_range lrr ON lrr.id=b.referal_center_id
                LEFT JOIN `lab_report_dispatch` ld 
                    ON FIND_IN_SET(lr.testid, ld.test_id) > 0 
                    AND ld.bill_no = lr.bill_no 
                    AND ld.patient_id = lr.patient_id
            WHERE 
                c.id = '$id' 
                $testid 
                AND lr.bill_no = '{$_REQUEST['bill']}'
            GROUP BY 
                lr.testid";
}
else {
    $query = "SELECT 
                c.*, 
                lr.date, 
                lr.visit_no, 
                lr.testid, 
                lt.testname_header, 
                ld.`left_signature`, 
                ld.`right_signature`, 
                ld.`left_e_signature`, 
                ld.`right_e_signature`, 
                case when ISNULL(ld.sample_received_date) then ss.sample_collected_at else ld.sample_received_date end sample_received_date,
                case when ISNULL(ld.reporting_time) then NOW() else ld.reporting_time end reporting_time,
                ld.print_signature_on_every_page,
                lrr.reference_name
            FROM 
                `central_ipd` c
                INNER JOIN `lab_report` lr ON lr.patient_id = c.ipd_no
                INNER JOIN sample_status ss on ss.patient_id=lr.patient_id and ss.bill_no=lr.bill_no and ss.test_id=lr.testid
                INNER JOIN `lab_test` lt ON lt.testid = lr.testid
                
                LEFT JOIN `billing` b ON b.bill_no=lr.bill_no
                LEFT JOIN lab_reference_range lrr ON lrr.id=b.referal_center_id
                LEFT JOIN `lab_report_dispatch` ld 
                    ON FIND_IN_SET(lr.testid, ld.test_id) > 0 
                    AND ld.bill_no = lr.bill_no 
                    AND ld.patient_id = lr.patient_id
            WHERE 
                c.ipd_no = '$id' 
                $testid 
                AND lr.bill_no = '{$_REQUEST['bill']}'
            GROUP BY 
                lr.testid";
}
// echo $query;
$res=mysqli_query($con,$query);
$row=mysqli_fetch_assoc($res);

// Get testid and gender from request or patient data
$testid = $row['testid'] ?? '';
$gender = $row['gender'] ?? 'both';
$bill_no = $_REQUEST['bill'] ?? '';
$left_signature=$row['left_signature'];
$right_signature=$row['right_signature'];
$left_e_signature=$row['left_e_signature'];
$right_e_signature=$row['right_e_signature'];
$print_signature_on_every_page=$row['print_signature_on_every_page'];

// Function to get lab test data using the same logic as ajaxClass.php


// Get the lab test data using the new function
// Parse test IDs for the function call
$test_ids_for_function = [];
if (isset($_REQUEST['testid'])) {
    $test_ids_for_function = explode(',', $_REQUEST['testid']);
    $test_ids_for_function = array_map('trim', $test_ids_for_function);
    $test_ids_for_function = array_filter($test_ids_for_function);
}

$lab_test_data = getLabTestData($con, $id, $bill_no, $test_ids_for_function, $gender);

// Debug information - uncomment to see the processed data
// echo "<pre>";
// echo "Patient ID: " . $id . "\n";
// echo "Bill No: " . $bill_no . "\n";
// echo "Test IDs: " . implode(', ', $test_ids_for_function) . "\n";
// echo "Gender: " . $gender . "\n";
// echo "Lab Test Data Count: " . count($lab_test_data) . "\n";
// print_r($lab_test_data);
// exit;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="format-detection" content="telephone=no">
    <title>Lab Report</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">

    <!-- Datatable -->
    <link href="./vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">

    <!-- DataTables -->
    <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <link href="assets/libs/datatables.net-select-bs4/css//select.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />

    <!-- Responsive datatable examples -->
    <link href="assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">

</head>

<style>
th,
td {
    font-size: 14px !important;
    line-height: 1.5 !important;
}

td[colspan="4"] table {
    width: 100%;
    border-collapse: collapse;
    text-align: left;
}

td[colspan="4"] table td,
td[colspan="4"] table th {
    padding: 6px 8px;
    font-size: 13px !important;
}

td[colspan="4"] p {
    line-height: 1.3;
    margin: 2px 0;
}

.table th {
    font-weight: 600;
}

.table-borderless td,
.table-borderless th {
    border: none !important;
}

@media print {
    body {
        margin-top: 20% !important;
    }

    .outer-tr {
        page-break-inside: avoid;
    }

    .group-title {
        page-break-after: avoid;
    }

    /* Page numbering */


    /* Signature footer for every page */
    .footer-every-page {
        position: fixed !important;
        bottom: 0 !important;
        left: 0 !important;
        width: 100% !important;
        background: #fff !important;
        border-top: 2px solid #000 !important;
        font-size: 18px !important;
        padding: 15px 0 !important;
        display: flex !important;
        justify-content: space-between !important;
        z-index: 9999 !important;
        -webkit-print-color-adjust: exact !important;
        color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    /* Signature footer for last page only */
    .footer-last-page {
        position: static !important;
        width: 100% !important;
        background: #fff !important;
        border-top: 2px solid #000 !important;
        font-size: 18px !important;
        padding: 15px 0 !important;
        display: flex !important;
        justify-content: space-between !important;
        -webkit-print-color-adjust: exact !important;
        color-adjust: exact !important;
        page-break-inside: avoid !important;
        print-color-adjust: exact !important;
        margin-top: 20px !important;
    }

    /* Normal footer positioning */
    body {
        margin-bottom: 0 !important;
        padding-bottom: 0 !important;
    }

    /* Normal main wrapper spacing */
    #main-wrapper {
        margin-bottom: 0 !important;
        padding-bottom: 0 !important;
    }

    /* Force footer to show */
    .d-print-block {
        display: block !important;
    }

    /* Additional print-specific footer rules */
    .footer-left,
    .footer-right {
        width: 50% !important;
        padding: 0 30px !important;
        text-align: center !important;
        display: block !important;
    }

    /* Ensure proper page breaks for last page signature */
    .footer-last-page {
        page-break-inside: avoid !important;
        break-inside: avoid !important;
    }

    .signature-image img {
        max-height: 80px !important;
        max-width: 200px !important;
        display: block !important;
        margin: 0 auto 5px auto !important;
    }

    .user-name {
        font-size: 18px !important;
        font-weight: bold !important;
        margin-bottom: 5px !important;
    }

    .designation {
        font-size: 16px !important;
        margin-bottom: 3px !important;
    }

    .qualification {
        font-size: 14px !important;
    }

    /* Normal page margins */
    @page {
        margin-bottom: 20px;
    }
}

.border-b {
    border-bottom: 2px solid #000000 !important;
    border-top: 2px solid #000000 !important;
}

.footer {
    position: static;
    width: 100%;
    background: #fff;
    border-top: 2px solid #000;
    font-size: 18px;
    padding: 15px 0;
    display: flex;
    page-break-inside: avoid !important;
    break-inside: avoid !important;
    justify-content: space-between;
    margin-top: 20px;
}

.test-result-abnormal {
    font-weight: bold;
    color: #dc3545;
}

.test-result-normal {
    font-weight: normal;
}

.method-info {
    font-size: 14px;
    color: #6c757d;
    margin-top: 0px;
    margin-bottom: 0px;

}

/* Page number styles */
</style>

<body style="font-family: 'Poppins', sans-serif; padding-left:15px;padding-right:15px; margin-top: 20%;"
    onload="window.print()" class="bg-white">

    <!-- Page Number Element -->


    <div id="main-wrapper">
        <table class="table table-borderless" width="100%">
            <thead class="">
                <!-- Hospital header removed - using pre-printed letterhead -->
                <tr>
                    <td colspan="4">
                        <table class="table table-borderless" width="100%"
                            style="margin-top: 20px; margin-bottom: 10px;">
                            <tbody>
                                <tr style="border-bottom: 1px solid #e0e0e0;">
                                    <td width="15%"
                                        style="padding: 8px 0; font-weight: 600; font-size: 13px; color: #333;">PATIENT
                                        ID.</td>
                                    <td width="35%" style="padding: 8px 0; font-size: 13px; color: #000;">
                                        <?php if(isset($_REQUEST['ipd'])) echo strtoupper($row['ipd_no']); else if(isset($_REQUEST['opd'])) echo strtoupper($row['opd_no']); else echo strtoupper($row['visit_no']);?>
                                    </td>
                                    <td width="20%"
                                        style="padding: 8px 0; font-weight: 600; font-size: 13px; color: #333;">SAMPLE
                                        COLLECTION</td>
                                    <td width="30%" style="padding: 8px 0; font-size: 13px; color: #000;">
                                        <?= date("d/m/Y h:i A",strtotime($row['sample_received_date'])) ?>
                                    </td>
                                </tr>
                                <tr style="border-bottom: 1px solid #e0e0e0;">
                                    <td style="padding: 8px 0; font-weight: 600; font-size: 13px; color: #333;">PATIENT
                                        NAME</td>
                                    <td
                                        style="padding: 8px 0; font-size: 13px; color: #000; text-transform: uppercase;">
                                        <?= $row['name']?></td>
                                    <td style="padding: 8px 0; font-weight: 600; font-size: 13px; color: #333;">DATE OF
                                        REPORT</td>
                                    <td style="padding: 8px 0; font-size: 13px; color: #000;">
                                        <?= date("d/m/Y h:i A",strtotime($row['reporting_time'])) ?>
                                    </td>
                                </tr>
                                <tr style="border-bottom: 1px solid #e0e0e0;">
                                    <td style="padding: 8px 0; font-weight: 600; font-size: 13px; color: #333;">AGE /
                                        GENDER</td>
                                    <td style="padding: 8px 0; font-size: 13px; color: #000;">
                                        <?= $row['age']?> Years / <?= strtoupper($row['gender'].$row['sex'])?>
                                    </td>
                                    <td style="padding: 8px 0; font-weight: 600; font-size: 13px; color: #333;">REFERRED
                                        BY</td>
                                    <td
                                        style="padding: 8px 0; font-size: 13px; color: #000; text-transform: uppercase;">
                                        <?= $row['doctor']?></td>
                                </tr>
                                <tr>
                                    <td style="padding: 8px 0; font-weight: 600; font-size: 13px; color: #333;">REF
                                        CENTRE</td>
                                    <td
                                        style="padding: 8px 0; font-size: 13px; color: #000; text-transform: uppercase;">
                                        <?= $row['reference_name']?></td>
                                    <td style="padding: 8px 0; font-weight: 600; font-size: 13px; color: #333;">REF NO
                                    </td>
                                    <td style="padding: 8px 0; font-size: 13px; color: #000;">
                                        <?php if(isset($row['ipd_no'])) echo strtoupper($row['ipd_no']); else echo strtoupper($row['opd_no']);?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan='3'>
                        <div style='width:100%;'>
                            <div
                                style='text-align:center; font-weight:bold; font-size:22px; text-transform:uppercase; letter-spacing:1px;'>
                                Department of <?php 
                    if (isset($_REQUEST['testid']) && strpos($_REQUEST['testid'], ',') !== false) {
                        // Multiple tests - show all departments
                        $test_ids = explode(',', $_REQUEST['testid']);
                        $test_ids = array_map('trim', $test_ids);
                        $test_ids = array_filter($test_ids);
                        
                        $departments = [];
                        foreach ($test_ids as $tid) {
                            $dept_query = "SELECT DISTINCT testname_header FROM lab_test WHERE testid = '$tid'";
                            $dept_result = mysqli_query($con, $dept_query);
                            if ($dept_row = mysqli_fetch_assoc($dept_result)) {
                                $departments[] = $dept_row['testname_header'];
                            }
                        }
                        $departments = array_unique($departments);
                        echo htmlspecialchars(implode(' / ', $departments));
                    } else {
                        echo htmlspecialchars($row['testname_header']);
                    }
                 ?></div>
                        </div>
                    </td>
                </tr>
                <tr style="background-color: #f8f9fa; border-top: 2px solid #8B4789; border-bottom: 2px solid #8B4789;">
                    <th style="padding: 10px 8px; font-weight: 600; font-size: 14px; color: #333;">TEST PARAMETER</th>
                    <th style="padding: 10px 8px; font-weight: 600; font-size: 14px; color: #333; text-align: center;">
                        RESULT</th>
                    <th style="padding: 10px 8px; font-weight: 600; font-size: 14px; color: #333; text-align: center;">
                        REFERENCE RANGE</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $sn = 1;
                $current_test_group = '';
                $current_test_name = '';
               
                $has_printable_items = false;
                $test='';
                
                foreach($lab_test_data as $tab)
                {
                    $type = $tab['type'];
                   
                    // New test name (left, underlined)
                    if ($test != $tab['testname']) {
                         if($sn > 1) {
                                echo "</tbody><tbody class='outer-tr'>";
                            }
                        echo "<tr><td >";
                        echo "<div style='text-align:left; margin-top:10px;'>";
                        echo "<span style='display:inline-block; border-bottom:2px solid #000; width:100%; font-size:20px; font-weight:600; text-transform:uppercase;'>";
                        echo htmlspecialchars($tab['testname']);
                        echo "</span>";
                        echo "</div></td></tr>";
                    }
                    if ($type == 'heading') {
                        // print_r($tab)
                        $should_print = false;
                        
                        // Check print_flag from lab_report table
                        if (!empty($tab['print_flag']) && $tab['print_flag'] == 1) {
                            $should_print = true;
                        }
                        // Test group header (Department headers)
                        if($current_test_group != $tab['parametername'] && $should_print) {
                           
                            echo "<tr>";
                            echo "<th colspan='4' class='mt-5 mb-5 text-left'>" . htmlspecialchars($tab['parametername']) . "</th>";
                            echo "</tr>";
                            $current_test_group = $tab['parametername'];
                        }
                    } elseif ($type == 'parameter') {
                        // Check if this parameter should be printed based on print_flag
                        $should_print = false;
                        
                        // Check print_flag from lab_report table
                        if (!empty($tab['print_flag']) && $tab['print_flag'] == 1) {
                            $should_print = true;
                        }
                        
                        // If no print_flag found, check if there's a print flag in the test_result JSON
                        if (!$should_print && !empty($tab['test_result'])) {
                            $decoded_result = json_decode($tab['test_result'], true);
                            if (is_array($decoded_result) && isset($decoded_result['print']) && $decoded_result['print'] == 1) {
                                $should_print = true;
                            }
                        }
                        
                        
                        if ($should_print) {
                            $has_printable_items = true;
                             if (!empty($tab['test_result'])) {
                            ?>

                <tr id='addr1' style="border-bottom: 1px solid #e0e0e0;">
                    <td style="padding: 10px 8px; font-size: 13px; color: #333;">
                        <?= htmlspecialchars($tab['parametername']) ?>
                        <?php if(!empty($tab['method'])) echo "<br><span class='method-info'>Method: " . htmlspecialchars($tab['method']) . "</span>"; ?>
                    </td>

                    <td class="text-center" style="padding: 10px 8px;">
                        <?php
                                    if (!empty($tab['test_result'])) {
                                        $result_class = 'test-result-normal';
                                        $result_value = $tab['test_result'];
                                        
                                        // Check if result is outside normal range
                                        if (!empty($tab['lower_limit']) && !empty($tab['upper_limit'])) {
                                            $test_result_num = floatval(str_replace([',', ' '], '', $tab['test_result']));
                                            $lower_limit_num = floatval(str_replace([',', ' '], '', $tab['lower_limit']));
                                            $upper_limit_num = floatval(str_replace([',', ' '], '', $tab['upper_limit']));
                                            if ($test_result_num > $upper_limit_num || $test_result_num < $lower_limit_num) {
                                                $result_class = 'test-result-abnormal';
                                            }
                                        }
                                        
                                        echo "<span class='{$result_class}'>" . $result_value . " ".$tab['unit'] ?? ''."</span>";
                                    } else {
                                        echo "";
                                    }
                                    ?>
                    </td>


                    <td class="text-center" style="padding: 10px 8px; font-size: 13px; color: #555;">
                        <?php 
                                    if (!empty($tab['normal_value'])) {
                                        echo htmlspecialchars($tab['normal_value']) . ' ' . htmlspecialchars($tab['unit'] ?? '');
                                    } elseif (!empty($tab['lower_limit']) && !empty($tab['upper_limit'])) {
                                        echo htmlspecialchars($tab['lower_limit']) . ' - ' . htmlspecialchars($tab['upper_limit']) . ' ' . htmlspecialchars($tab['unit'] ?? '');
                                    } else {
                                        echo "";
                                    }
                                    ?>
                    </td>
                </tr>
                <?php
                             }
                        }
                    } elseif ($type == 'free_text') {
                        // Check if this free text should be printed based on print_flag
                        $should_print = false;
                        
                        // Check print_flag from lab_report table
                        if (!empty($tab['print_flag']) && $tab['print_flag'] == 1) {
                            $should_print = true;
                        }
                        
                        // If no print_flag found, check if there's a print flag in the test_result JSON
                        if (!$should_print && !empty($tab['test_result'])) {
                            $decoded_result = json_decode($tab['test_result'], true);
                            if (is_array($decoded_result) && isset($decoded_result['print']) && $decoded_result['print'] == 1) {
                                $should_print = true;
                            }
                        }
                     
                        if ($should_print) {
                            $has_printable_items = true;
                            ?>
                <tr>
                    <td colspan="4" style="width:100%;">
                        <?php if(!empty($tab['method'])) echo "<br><p class='method-info'>Method: " . htmlspecialchars($tab['method']) . "</p>"; ?>
                        <div style="margin-bottom: 5px; width:100%;">
                            <?= trim($tab['test_result'] ?? '') ?>
                        </div>
                    </td>
                </tr>
                <?php
                        }
                    } elseif ($type == 'table_format') {
                        // echo '<pre>';/
                        // print_r($tab);
                        // Check if this table format should be printed based on print_flag
                        $should_print = false;
                        
                        // Check print_flag from lab_report table
                        if (!empty($tab['print_flag']) && $tab['print_flag'] == 1) {
                            $should_print = true;
                        }
                        
                        if ($should_print) {
                            $has_printable_items = true;
                            $decoded_result = [];
                            if (!empty($tab['test_result'])) {
                                $decoded_result = json_decode($tab['test_result'], true) ?: [];
                            }
                            ?>
                <tr>
                    <td colspan="4" style="width:100%;">
                        <strong><?= htmlspecialchars($tab['parametername']) ?></strong>
                        <?php if(!empty($tab['method'])) echo "<br><p class='method-info'>Method: " . htmlspecialchars($tab['method']) . "</p>"; ?>
                        <table class="table table-borderless table-sm mt-2" style="width:100%;">
                            <?php
                                        if (!empty($decoded_result)) {
                                            // Display table data
                                            $headers = [];
                                            $data_rows = [];
                                            
                                            foreach ($decoded_result as $item) {
                                                if (isset($item['row_type']) && $item['row_type'] == 'TableHeaderRow') {
                                                    $headers[] = $item['name'];
                                                } elseif (isset($item['row_type']) && $item['row_type'] == 'TableDataRow') {
                                                    $data_rows[] = $item;
                                                }
                                            }
                                            
                                            if (!empty($headers)) {
                                                echo "<tr>";
                                                foreach ($headers as $header) {
                                                    echo "<th>" . htmlspecialchars($header) . "</th>";
                                                }
                                                echo "</tr>";
                                            }
                                            
                                            foreach ($data_rows as $row_data) {
                                                if (isset($row_data['print']) && $row_data['print'] == 1) {
                                                    echo "<tr>";
                                                    echo "<td>" . htmlspecialchars($row_data['name']) . "</td>";
                                                    for($i=1;$i<=count($row_data['result']);$i++) {
                                                        echo "<td>" . htmlspecialchars($row_data['result'][$i]) . "</td>";
                                                    }
                                                    echo "</tr>";
                                                }
                                            }
                                        }
                                        ?>
                        </table>
                    </td>
                </tr>
                <?php
                        }
                    } elseif ($type == 'antibiotic') {
                        
                        // Check if this antibiotic test should be printed based on print_flag
                        $should_print = false;
                        
                        // Check print_flag from lab_report table
                        if (!empty($tab['print_flag']) && $tab['print_flag'] == 1) {
                            $should_print = true;
                        }
                        
                        // If no print_flag found, check if there's a print flag in the test_result JSON
                        if (!$should_print && !empty($tab['test_result'])) {
                            $decoded_result = json_decode($tab['test_result'], true);
                            if (is_array($decoded_result) && isset($decoded_result['print']) && $decoded_result['print'] == 1) {
                                $should_print = true;
                            }
                        }
                        
                     
                        
                        if ($should_print) {
                            $has_printable_items = true;
                            $decoded_antibiotic_data = [];
                            if (!empty($tab['test_result'])) {
                                $decoded_antibiotic_data = json_decode($tab['test_result'], true) ?: [];
                            }
                            ?>
                <tr>
                    <td colspan="4">
                        <strong><?= htmlspecialchars($tab['parametername']) ?></strong>
                        <?php if(!empty($tab['method'])) echo "<br><p class='method-info'>Method: " . htmlspecialchars($tab['method']) . "</p>"; ?>
                        <table class="table table-borderless table-sm mt-2">
                            <tr>
                                <th>Antibiotic</th>
                                <th>INHIBITION ZONE</th>
                                <th>STANDARD ZONE</th>
                            </tr>
                            <?php
                                        if (!empty($decoded_antibiotic_data)) {
                                            foreach ($decoded_antibiotic_data as $antibiotic) {
                                                if (isset($antibiotic['print']) && $antibiotic['print'] == 1) {
                                                    echo "<tr>";
                                                    echo "<td>" . htmlspecialchars($antibiotic['name'] ?? '') . "</td>";
                                                    echo "<td>" . htmlspecialchars($antibiotic['inhibition_zone'] ?? '') . "</td>";
                                                    echo "<td>" . htmlspecialchars($antibiotic['standard_zone'] ?? '') . "</td>";
                                                    echo "</tr>";
                                                }
                                            }
                                        }
                                        ?>
                        </table>
                    </td>
                </tr>
                <?php
                        }
                    }
                    
                    $sn++;
                    $test = $tab['testname'];
                } 
                
                // Only show end of report if there are printable items
               
                ?>
            </tbody>
        </table>

        <!-- Spacer for footer -->
        <?php if ($has_printable_items) {
                    ?>
        <div class="text-center"><strong class="mt-5">-------------------End of the report-------------------</strong>
        </div>
        <?php
                }
         // Signature Footer 
        
        // Helper function to fetch signature data
        function get_signature_data($con, $signature_id) {
            if (!$signature_id) return null;
            $sql = "SELECT * FROM lab_signature WHERE id = '" . intval($signature_id) . "' ";
            // echo $sql;
            $res = mysqli_query($con, $sql);
            return mysqli_fetch_assoc($res);
        }

        // Determine signature display class based on print_signature_on_every_page setting
        $signature_class = ($print_signature_on_every_page == 1) ? 'footer-every-page' : 'footer-last-page';
        if (empty($left_signature) && empty($right_signature)) {
    echo '<div style="
        position: fixed;
        top: 40%;
        left: 0;
        width: 100%;
        text-align: center;
        opacity: 0.15;
        font-size: 120px;
        font-weight: bold;
        color: #000;
        z-index: 9999;
        pointer-events: none;
        transform: rotate(-20deg);
        user-select: none;
    " class="d-print-block">INTERIM</div>';
}
        ?>

        <div class="footer <?php echo $signature_class; ?> d-print-block"
            style="width: 100%; background: #fff; font-size: 18px; padding: 15px 0; display: flex; justify-content: space-between; margin-top: 10px;">
            <div class="footer-left" style="width: 50%; padding: 0 30px; text-align: center;">
                <?php
                  // Use left signature data
                  $sign_data = get_signature_data($con, $left_signature);
                  if ($sign_data) {
                      if (!empty($sign_data['signature_image']) && $left_e_signature == 1) {
                          echo '<div class="signature-image" style="margin-bottom: 10px;"><img src="' . htmlspecialchars($sign_data['signature_image']) . '" alt="Signature" style="max-height: 80px; max-width: 200px; display: block; margin: 0 auto;"></div>';
                      }
                      if (!empty($sign_data['user_name'])) {
                          echo '<div class="user-name" style="font-size: 16px; font-weight: bold; margin-bottom: 3px; text-transform: uppercase;">' . htmlspecialchars($sign_data['user_name']) . '</div>';
                      }
                      if (!empty($sign_data['qualification'])) {
                          echo '<div class="qualification" style="font-size: 12px; color: #666; font-style: italic;">' . htmlspecialchars($sign_data['qualification']) . '</div>';
                      }
                      if (!empty($sign_data['designation'])) {
                          echo '<div class="designation" style="font-size: 14px; color: #333;">' . htmlspecialchars($sign_data['designation']) . '</div>';
                      }
                  } 
                  ?>
            </div>
            <div class="footer-right" style="width: 50%; padding: 0 30px; text-align: center;">
                <?php
                  // Use right signature data
                  $sign_data = get_signature_data($con, $right_signature);
                  if ($sign_data) {
                      if (!empty($sign_data['signature_image']) && $right_e_signature == 1) {
                          echo '<div class="signature-image" style="margin-bottom: 10px;"><img src="' . htmlspecialchars($sign_data['signature_image']) . '" alt="Signature" style="max-height: 80px; max-width: 200px; display: block; margin: 0 auto;"></div>';
                      }
                      if (!empty($sign_data['user_name'])) {
                          echo '<div class="user-name" style="font-size: 16px; font-weight: bold; margin-bottom: 3px; text-transform: uppercase;">' . htmlspecialchars($sign_data['user_name']) . '</div>';
                      }
                      if (!empty($sign_data['qualification'])) {
                          echo '<div class="qualification" style="font-size: 12px; color: #666; font-style: italic;">' . htmlspecialchars($sign_data['qualification']) . '</div>';
                      }
                      if (!empty($sign_data['designation'])) {
                          echo '<div class="designation" style="font-size: 14px; color: #333;">' . htmlspecialchars($sign_data['designation']) . '</div>';
                      }
                  } 
                  ?>
            </div>
        </div>

        <style>
        @media print {

            /* Signature footer for every page */
            .footer-every-page {
                position: fixed !important;
                bottom: 0 !important;
                left: 0 !important;
                width: 100% !important;
                background: #fff !important;
                border-top: 2px solid #000 !important;
                font-size: 18px !important;
                padding: 15px 0 !important;
                display: flex !important;
                justify-content: space-between !important;
                z-index: 9999 !important;
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            /* Signature footer for last page only */
            .footer-last-page {
                position: static !important;
                width: 100% !important;
                background: #fff !important;
                border-top: 2px solid #000 !important;
                font-size: 18px !important;
                padding: 15px 0 !important;
                display: flex !important;
                justify-content: space-between !important;
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
                page-break-inside: avoid !important;
                print-color-adjust: exact !important;
                margin-top: 20px !important;
            }

            .footer-left,
            .footer-right {
                width: 50% !important;
                padding: 0 30px !important;
                text-align: center !important;
            }

            .signature-image img {
                max-height: 80px !important;
                max-width: 200px !important;
                display: block !important;
                margin: 0 auto 5px auto !important;
            }

            .user-name {
                font-size: 18px !important;
                font-weight: bold !important;
                margin-bottom: 5px !important;
            }

            .designation {
                font-size: 16px !important;
                margin-bottom: 3px !important;
            }

            .qualification {
                font-size: 14px !important;
            }
        }

        /* Default footer styles for both modes */
        .footer {
            position: static;
            width: 100%;
            background: #fff;
            border-top: 2px solid #000;
            font-size: 18px;
            padding: 15px 0;
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        /* Specific styles for last page only signature */
        .footer-last-page {
            position: static;
            width: 100%;
            background: #fff;
            border-top: 2px solid #000;
            font-size: 18px;
            padding: 15px 0;
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        /* Specific styles for every page signature */
        .footer-every-page {
            position: static;
            width: 100%;
            background: #fff;
            border-top: 2px solid #000;
            font-size: 18px;
            padding: 15px 0;
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .footer-left,
        .footer-right {
            width: 50%;
            padding: 0 30px;
            text-align: center;
        }

        .signature-image img {
            max-height: 80px;
            max-width: 200px;
            display: block;
            margin: 0 auto 5px auto;
            object-fit: contain;
        }

        .user-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .designation {
            font-size: 16px;
            margin-bottom: 3px;
            color: #333;
        }

        .qualification {
            font-size: 14px;
            color: #666;
            font-style: italic;
        }

        body {
            margin-right: 20px !important;
            margin-left: 20px !important;
            margin-bottom: 0 !important;
        }

        /* Normal main wrapper spacing */
        #main-wrapper {
            margin-bottom: 0 !important;
        }
        </style>

        <div class="row">
            <div class="col-md-12">
                <button onclick="window.print()" class="btn btn-danger float-right d-print-none">
                    <i class="ri-printer-line"></i> Print
                </button>
            </div>
        </div>
    </div>

    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="./vendor/global/global.min.js"></script>
    <script src="./js/quixnav-init.js"></script>
    <script src="./js/custom.min.js"></script>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        var testname = "<?php echo $test; ?>";
        document.getElementById("suggested-filename").textContent = testname || "Lab_Report";


    });



    // Function to create page numbers

    function printDocument() {
        // Ensure footer is visible before printing
        var footer = document.querySelector('.footer');
        if (footer) {
            footer.style.display = 'flex';
            footer.style.position = 'static';
            footer.style.width = '100%';
            footer.style.backgroundColor = '#fff';
            //   footer.style.borderTop = '2px solid #000';
            footer.style.marginTop = '10px';
        }



        window.print();
    }

    // Also ensure footer is visible on page load
    window.addEventListener('load', function() {
        var footer = document.querySelector('.footer');
        if (footer) {
            footer.style.display = 'flex';
            footer.style.position = 'static';
            footer.style.width = '100%';
            footer.style.backgroundColor = '#fff';
            //   footer.style.borderTop = '2px solid #000';
            footer.style.marginTop = '10px';
        }


    });

    // Handle signature display based on print_signature_on_every_page setting
    document.addEventListener('DOMContentLoaded', function() {
        var printSignatureOnEveryPage = <?php echo $print_signature_on_every_page ?? 0; ?>;
        var footer = document.querySelector('.footer');

        if (footer) {
            if (printSignatureOnEveryPage == 1) {
                footer.classList.add('footer-every-page');
                footer.classList.remove('footer-last-page');
            } else {
                footer.classList.add('footer-last-page');
                footer.classList.remove('footer-every-page');
            }
        }
    });
    </script>

</body>

</html>