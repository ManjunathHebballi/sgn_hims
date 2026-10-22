<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/dbconnect.php');
setlocale(LC_MONETARY,"en_IN");
$newlogin = new userlogin;                  
$con = $newlogin->dbconnect();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
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
    <link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/libs/datatables.net-select-bs4/css/select.bootstrap4.min.css" rel="stylesheet" type="text/css" />

    <!-- Responsive datatable examples -->
    <link href="assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />   

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">

</head>

<style>

    .test {
        font-size: 20px !important ;
    }
    .table th {
        font-weight: 600;
        font-size: 20px !important ;
    }
    .intr{
    font-size: 22px !important;
}
    @media print {
        .outer-tr {
            page-break-inside: avoid;
        }
        .group-title {
            page-break-after: avoid;
        }
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.1;
            font-size: 100px;
            color: red;
            z-index: 1000;
            pointer-events: none;
        }
        .footer-left {
            position: fixed;
            bottom: 30px;
            left: 0;
            text-align: left;
        }
        .footer-right {
            position: fixed;
            bottom: 30px;
            right: 0;
            text-align: right;
        }
         @page:first {
        .watermark {
            display: block;
        }
    }
    @page:not(:first) {
        .watermark {
            display: none;
        }
    }
    
    }
    .border-b {
        border-bottom: 2px solid #000000 !important;
        border-top: 2px solid #000000 !important;
    }
    body {
        font-family: 'Adobe Arabic', sans-serif;
        padding-left: 35px;
        background: url('assets/preview.png') no-repeat center center;
        background-size: cover;
    }
    
    .footer {
                position: fixed;
                bottom: 0;
                width: 100%;
                text-align: center;
                font-size: 18px;
                padding: 10px 0;
                background: #fff;
                border-top: 2px solid #000;
            }
</style>

<body style="font-family: 'Poppins', sans-serif; padding-left: 35px;" onload="window.print()" class="bg-white">
    <div class="watermark d-none d-print-inline">PREVIEW</div>

    <div id="main-wrapper" class="bg-white">

        <table class="table table-borderless" width="100%">
            <thead>
                <tr>
                    <td colspan="4">
                        <table class="table" width="100%">
                            <tr>
                                <th rowspan="2" width="20%"><img src="https://nexenstial.in/lab/assets/logo.png" alt="" width="150"></th>
                                <th class="text-center"><h2>Lab Management Software</h2></th>
                                <td rowspan="2" class="text-center">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?data=preview&choe=UTF-8" title="Lab Result" width="150" />
                                </td>
                            </tr>
                            <tr class="text-center">
                                <td class='test'>Opps to KIMS  <br> Hubli. Karnataka - 580021</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        <table class="table mb-4" width="100%">
                            <tbody>
                                <tr>
                                    <th width="20%">Patient Id.</th>
                                    <td align="left" width="30%"></td>
                                    <th width="20%"> Date:</th>
                                    <td align="left" width="40%" id="bill"></td>
                                </tr>
                                <tr>
                                    <th width="20%">Patient NAME:</th>
                                    <td align="left" width="30%"></td>
                                    <th width="20%">Patient Address: </th>
                                    <td align="left" width="40%"></td>
                                </tr>
                                <tr>
                                    <th width="20%">Age</th>
                                    <td align="left" width="10%"></td>
                                    <th width="15%">Doctor Name:</th>
                                    <td align="left" width="40%"></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr class="border-b mb-4">
                    <th>Test Information</th>
                    <th>Result</th>   
                    <th>Units</th>
                    <th class="text-center">Normal Range</th>
                </tr>
            </thead>
            <tbody>
            <?php
$testid = $_REQUEST['id'] ?? '';
$gender = $_REQUEST['gender'] ?? 'both';

// Fetch all test parameters for the given testid, including all types
$sql = "SELECT 
            ltp.id as parameter_id,
            ltp.name as testname,
            ltp.type,
            ltp.unit,
            ltp.method,
            ltp.position,
            ltp.gender,
            ltp.status
        FROM lab_test_parameter ltp
        WHERE ltp.test_id = '$testid' 
          AND ltp.status = 'Active'
          AND (ltp.gender = '$gender' OR ltp.gender = 'both')
        ORDER BY ltp.position ASC";
$param_res = mysqli_query($con, $sql);

$lab_test_data = [];
while ($param = mysqli_fetch_assoc($param_res)) {
    $row = $param;

    // Fetch normal value, lower/upper limit for 'parameter' type
    if ($param['type'] == 'parameter') {
        $range_sql = "SELECT normal_value, lower_limit, upper_limit 
                      FROM lab_test_range 
                      WHERE parameter_id = '{$param['parameter_id']}' 
                        AND status = 'active' 
                        AND (gender = '$gender' OR gender = 'both')
                      LIMIT 1";
                    //   echo $range_sql;
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
        $table_format_sql = "SELECT * FROM lab_test_table_format 
                           WHERE parameter_id = '{$param['parameter_id']}' 
                             ";
                           echo $table_format_sql;
        $table_format_res = mysqli_query($con, $table_format_sql);
        $table_format = [];
        while ($tf = mysqli_fetch_assoc($table_format_res)) {
            $table_format[] = $tf;
        }
        $row['table_format'] = $table_format;   
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

    $lab_test_data[] = $row;
}

$current_test_group = '';
foreach($lab_test_data as $tab) {
    $type = $tab['type'];
    if ($type == 'heading') {
        if($current_test_group != $tab['testname']) {
            echo "<tr class='group-title'><td colspan='4'><u>" . htmlspecialchars($tab['testname']) . "</u></td></tr>";
            $current_test_group = $tab['testname'];
        }
    } elseif ($type == 'parameter') {
        echo "<tr>";
        echo "<td class='test'>" . htmlspecialchars($tab['testname']) . "</td>";
        echo "<td class='test'></td>";
        echo "<td class='test'>" . htmlspecialchars($tab['unit'] ?? '') . "</td>";
        echo "<td class='test'>";
        if (!empty($tab['normal_value'])) {
            echo htmlspecialchars($tab['normal_value']) . ' ' . htmlspecialchars($tab['unit'] ?? '');
        } elseif (!empty($tab['lower_limit']) && !empty($tab['upper_limit'])) {
            echo htmlspecialchars($tab['lower_limit']) . ' - ' . htmlspecialchars($tab['upper_limit']) . ' ' . htmlspecialchars($tab['unit'] ?? '');
        }
        echo "</td>";
        echo "</tr>";
    } elseif ($type == 'free_text') {
        echo "<tr><td colspan='4'><em></em></td></tr>";
    } elseif ($type == 'table_format') {
        echo "<tr><td colspan='4'><strong>" . htmlspecialchars($tab['testname']) . "</strong>";
        if(!empty($tab['method'])) echo "<br><span class='method-info'>Method: " . htmlspecialchars($tab['method']) . "</span>";
        echo "<table class='table table-bordered mt-2'>";
        if (!empty($tab['table_format'])) {
            echo "<tr>";
            foreach ($tab['table_format'] as $row) {
                echo "<th class='test'>" . htmlspecialchars($row['column_name']) . "</th>";
            }
            echo "</tr>";
        }
        if (!empty($tab['table_data_rows'])) {
            foreach ($tab['table_data_rows'] as $row) {
                echo "<tr><td class='test'>" . htmlspecialchars($row['data_name']) . "</td>";
                               $count=count($tab['table_format']);

                for($i=1;$i<$count;$i++)
                {
                    echo "<td class='test'></td>";
                }
               
                echo "</tr>";
            }
        }
        echo "</table></td></tr>";
    } elseif ($type == 'antibiotic') {
        echo "<tr><td colspan='4'><strong>" . htmlspecialchars($tab['testname']) . "</strong>";
        if(!empty($tab['method'])) echo "<br><span class='method-info'>Method: " . htmlspecialchars($tab['method']) . "</span>";
        echo "<table class='table table-bordered mt-2'>";
        echo "<tr><th>Antibiotic</th><th>INHIBITION ZONE</th><th>STANDARD ZONE</th></tr>";
        if (!empty($tab['antibiotic_data'])) {
            foreach ($tab['antibiotic_data'] as $ab) {
                echo "<tr>";
                echo "<td class='test'>" . htmlspecialchars($ab['heading1']) . "</td>";
                echo "<td class='test'>" . htmlspecialchars($ab['heading2']) . "</td>";
                echo "<td class='test'>" . htmlspecialchars($ab['heading3']) . "</td>";
                echo "</tr>";
            }
        }
        echo "</table></td></tr>";
    }
}
?>
            </tbody>
        </table>
         <div class="footer d-none d-print-inline">
            <div class="footer-left">
                LAB TECHNICIAN
            </div>
            <div class="footer-right">
                <?php 
                if ($testname_header == 'PATHOLOGY') {
                    echo 'PATHOLOGIST';
                } elseif ($testname_header == 'BIOCHEMISTRY') {
                    echo 'BIOCHEMIST';
                } elseif ($testname_header == 'MICROBIOLOGY') {
                    echo 'MICROBIOLOGIST';
                }
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <button onclick="printDocument()" class="btn btn-danger float-right d-print-none"><i class="ri-printer-line"></i> Print</button>
            </div>
        </div>
    </div>
 <script>
        document.addEventListener("DOMContentLoaded", function () {
            var testname = "<?php echo $test; ?>";
            document.getElementById("suggested-filename").textContent = testname || "Lab_Report";
        });

        function printDocument() {
            window.print();
        }
    </script>
    <script src="./vendor/global/global.min.js"></script>
    <script src="./js/quixnav-init.js"></script>
    <script src="./js/custom.min.js"></script>
</body>

</html>
