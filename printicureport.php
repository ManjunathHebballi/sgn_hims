<?php 
require_once(__DIR__ . '/model/session_config.php');

include('model/reportClass.php');
$newReport = new report;
date_default_timezone_set("Asia/Kolkata"); 
if(isset($_REQUEST['from']))
{
    $ip_from=$_REQUEST['from'];
}
else{
        $ip_from='2000-01-01';
}
if(isset($_REQUEST['to']))
{
    $ip_to=$_REQUEST['to'];
}
else{
        $ip_to='2049-12-01';
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>IP Report</title>
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

   @media print {

    .dark{
background-color:black;

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
                        <!--<h5 class="text-center text-black">PSM Hospital</h5>-->
                        <h2 class="text-center text-black"><?= $_SESSION['college_name']?></h2>
                        <h5 class="text-center text-black"><?= $_SESSION['college_location']?></h5>
                        <h5 class="text-center text-black"><?= $_SESSION['college_phone']?></h5>

                        </div>
                    </div>
                    
                    <h4 class="text-center text-black"> IP REPORT 
                         <?= date('d.m.Y',strtotime($ip_from))?> and <?= date('d.m.Y',strtotime($ip_to))?>
                    </h4>

                    
                

            </tbody></table>
        </div>

 <div class="row mb-4">
                                                <div class="col-md-12">
                                                    <button onclick="window.print()"class="btn btn-danger float-right d-print-none"> <i class="ri-printer-line"></i> Print </button>
                                                </div>
                                            </div>

      
                                <table  class="table table-hoverde  text-center">
                                    <thead class="thead-dark">
                                    <tr >
                                                <th>Sl NO</th>
                                                <th>Date</th>
                                                <th>OP Reg No</th>
                                                <th>IP Reg No</th>
                                                <th>Name</th>
                                                <th>Gender</th>
                                                <th>Age</th>
                                                <!--<th>Address</th>-->
                                                <th>Diagnosis</th>
                                                <th>Unit</th>
                                                <th>Department</th>
                                                <th>Bed</th>
                                                <th>Date of Discharge</th>
                                                <th>Condition of Discharge</th>
                                                
                                                <th>DIH</th>
                                                
                                                
                                                
                                            </tr>
                                        </thead>
                                        <tbody class="text-center text-black">
                                            <?php $ot=$newReport->getIPDetailsICU($ip_from,$ip_to,$_REQUEST['bed'],$_REQUEST['type']);
                $sn=1;
                while($row=mysqli_fetch_assoc($ot)){
                ?>
                                            <tr>
                                                <td><?= $sn?></td>
                                                  <td><?= date("d.m.Y",strtotime($row['admission_date']))?></td>
                                                <td><?= $row['opd_no']?></td>
                                                <td>  <?= $row['ipd_no']?></td>
                                                <td><?= $row['name']?></td>
                                                <td ><?= $row['gender']?></td>
                                                <td ><?= $row['age']?></td>
                                                <!--<td ><?= $row['address']?></td>-->
                                                <td><?= $row['diagnosis']?></td>
                                                <td ><?= $row['unit']?></td>
                                                <td ><?= $row['dept_name']?></td>
                                                <td><?= $row['bed_number']?></td>
                                               <td ><?php if($row['discharge']==1) echo date("d.m.Y",strtotime($row['discharge_date'])); else echo '00.00.0000'?></td>
                                               <td ><?= $row['advice_discharge']?></td>
                                                <td ><?php $date1=date_create($row['admission_date']);
$date2=date_create($row['discharge_date']);
$diff=date_diff($date1,$date2);
if($row['discharge_date']!='0000-00-00 00:00:00')echo $diff->format("%a day");?></td>
                                                <!--<td ><a href="" data-toggle="modal" data-target="#view"><span class="badge badge-pill badge-primary"><i class="bi bi-pencil-square"></i> Edit </span> </a></td>-->
                                                </tr>
                                            <?php $sn++; } ?>
                                            
                                            </tbody>
                                            </table>  
                                           
 
       
                  
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