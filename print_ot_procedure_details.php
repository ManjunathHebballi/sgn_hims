<?php
require_once(__DIR__ . '/model/session_config.php');
include('model/reportClass.php');
$newReport = new report;
$name = isset($_REQUEST['name']) ? $_REQUEST['name'] : '';
$from = isset($_REQUEST['from']) ? $_REQUEST['from'] : '';
$to = isset($_REQUEST['to']) ? $_REQUEST['to'] : '';
$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
$data = false;

if($name=='Procedures')
{
$data=$newReport->otProcedureReport($from,$to,$id);
}
else if($name=='type')
{
    $data=$newReport->otTypeReports($from,$to,$id);
}

$exportUrl = 'print_ot_procedure_details.php?' . http_build_query(array(
    'name' => $name,
    'from' => $from,
    'to' => $to,
    'id' => $id,
    'export' => 'excel'
));

if(!function_exists('report_excel_upper')) {
    function report_excel_upper($value)
    {
        $value = (string)$value;
        if(function_exists('mb_strtoupper')) {
            return mb_strtoupper($value, 'UTF-8');
        }
        return strtoupper($value);
    }
}

if(isset($_REQUEST['export']) && $_REQUEST['export'] == 'excel') {
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=ot_procedure_details_" . date('Ymd_His') . ".xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    echo "<table border='1'>";
    echo "<tr>";
    echo "<th>SL.NO</th>";
    echo "<th>DATE</th>";
    echo "<th>OP NO</th>";
    echo "<th>IP NO</th>";
    echo "<th>PATIENT NAME</th>";
    echo "<th>GENDER</th>";
    echo "<th>AGE</th>";
    echo "<th>OT NAME</th>";
    echo "<th>OT PROCEDURE</th>";
    echo "<th>ATTENDED BY</th>";
    echo "</tr>";

    $sn = 1;
    if($data) {
        while($row=mysqli_fetch_assoc($data)) {
            echo "<tr>";
            echo "<td>" . $sn . "</td>";
            echo "<td>" . date("d.m.Y",strtotime($row['date'])) . "</td>";
            echo "<td>" . htmlspecialchars(report_excel_upper($row['opd_no'])) . "</td>";
            echo "<td>" . htmlspecialchars(report_excel_upper($row['ipd_no'])) . "</td>";
            echo "<td>" . htmlspecialchars(report_excel_upper($row['name'])) . "</td>";
            echo "<td>" . htmlspecialchars(report_excel_upper($row['gender'])) . "</td>";
            echo "<td>" . htmlspecialchars(report_excel_upper($row['age'])) . "</td>";
            echo "<td>" . htmlspecialchars(report_excel_upper($row['ot_name'])) . "</td>";
            echo "<td>" . htmlspecialchars(report_excel_upper($row['comment'])) . "</td>";
            echo "<td>" . htmlspecialchars(report_excel_upper($row['doctor'])) . "</td>";
            echo "</tr>";
            $sn++;
        }
    }
    echo "</table>";
    exit;
}

?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Patient Details For OT Procedure </title>
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
        <link href="assets/libs/datatables.net-select-bs4/css//select.bootstrap4.min.css" rel="stylesheet" type="text/css" />

        <!-- Responsive datatable examples -->
        <link href="assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />   

        <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">

</head>

<style>
   td{

    font-size:15px
   }
body{
    text-transform: uppercase;
}
   @media print {

    .dark{
background-color:black;

print-color-adjust:exact;
-webkit-print-color-adjust:exact;
    }
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
        <div class="card">
            <div class="card-body">
                <div class="container">
                    <div class="row">
                        <div class="col-md-1 mr-3">
                            <img src="assets/logo.png" class="" alt="" width=150>

                        </div>
                        <div class="col-md-10  align-items-center">
                        <h2 class="text-center text-black"><?= $_SESSION['college_name']?></h2>
                        <h5 class="text-center text-black"><?= $_SESSION['college_location']?></h5>
                        <h5 class="text-center text-black"><?= $_SESSION['college_phone']?></h5>

                        </div>
                    </div>
                    
                    <h4 class="text-center text-black">Patient Details of OT type 
                        <?php if($from !='2000-01-01' && $to !='2049-12-01') { 
                        echo "Between ". date("d.m.Y",strtotime($from)) ." and ". date("d.m.Y",strtotime($to)); 
                         } ?>
                    </h4>

                    
                

            </tbody></table>
        </div>

 <div class="row mb-4">
                                                <div class="col-md-12">
                                                    <a href="<?= $exportUrl ?>" class="btn btn-success float-right d-print-none ml-2"><i class="ri-file-excel-2-line"></i> Export Excel</a>
                                                    <button onclick="window.print()"class="btn btn-danger float-right d-print-none"> <i class="ri-printer-line"></i> Print </button>
                                                </div>
                                            </div>

      
                               <table id="" class="table table-bordered  text-center">
                                    <thead class="thead-dark">
                                    <tr>
                          <th>Sl.No</th>
                          <th>Date</th>
                          <th>OP No</th>
                          <th>IP No</th>
                          <th>Patient Name</th>
                          <th>Gender</th>
                          <th>Age</th>
                          <th>OT Name</th>
                          <th>OT Procedure</th>
                          <!-- <th>Diagnosis</th> -->
                          <!-- <th>Comments</th> -->
                          <th>Attended by</th>
                          <!--<th>Attended Time</th>-->
                          <!--<th>Remarks</th>-->
                        </tr>
                                        </thead>
                                        <tbody class="text-center text-black">
                                            
                                   <?php $sn=1;
                                   
                                   if($data) {
                                   while($row=mysqli_fetch_assoc($data)){
                                   ?>
                                            <tr>
                                                <td><?= $sn?></td>
                                                <td><?= date("d.m.Y",strtotime($row['date']))?></td>
                                                <td><?= $row['opd_no']?></td>
                                                <td><?= $row['ipd_no']?></td>
                                                <td><?= $row['name']?></td>
                                                <td><?= $row['gender']?></td>
                                                <td><?= $row['age']?></td>
                                                <td><?= $row['ot_name']?></td>
                                                <!-- <td><?= $row['ot_procedure']?></td> -->
                                                <!-- <td><?= $row['diagnosis']?></td> -->
                                                <td><?= $row['comment']?></td>
                                                <td><?= $row['doctor']?></td>
                                                <!--<td><?= date("h:i:s a",strtotime($row['date']))?></td>-->
                                                <!--<td></td>-->
                                                
                                                </tr>
                                              
                                            <?php $sn++; } } ?>     
                                            
                                            </tbody>
                                            </table>   
                                 
 
                                           
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
    <!-- Required vendors -->
    <script src="./vendor/global/global.min.js"></script>
    <script src="./js/quixnav-init.js"></script>
    <script src="./js/custom.min.js"></script>

    
    <!-- Datatable -->
    <script src="./vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="./js/plugins-init/datatables.init.js"></script>

  
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
    $('table.display').DataTable();
} );
  </script>
  

</body>

</html>