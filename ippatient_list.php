<?php date_default_timezone_set("Asia/Kolkata"); 
require_once(__DIR__ . '/model/session_config.php');

include('model/reportClass.php');
$newReport = new report;
$date=date("Y-m-d");
if(isset($_REQUEST['from']))
{
    $ip_from=$_REQUEST['from'];
}
else{
        $ip_from=date("Y-m-d");
}
if(isset($_REQUEST['to']))
{
    $ip_to=$_REQUEST['to'];
}
else{
        $ip_to=$date;
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
        @page { size: A4 portrait; margin: 8mm; }

    .dark{
background-color:black;
-webkit-print-color-adjust:exact;
    }
    table {
        width: 100% !important;
        table-layout: fixed !important;
        word-break: break-word;
    }
    th, td {
        font-size: 9px !important;
        padding: 2px 3px !important;
        border: 1px solid #000 !important;
    }
    h2 { font-size: 16px !important; }
    h4, h5 { font-size: 12px !important; }
    .table-responsive {
        overflow: visible !important;
    }
    .btn, .d-print-none, .dataTables_length, .dataTables_filter, .dataTables_info, .dataTables_paginate, .dt-buttons {
        display: none !important;
    }
    html, body {
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        overflow: visible !important;
    }
    #main-wrapper { margin: 0 !important; padding: 0 !important; }
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
                        <div class="col-sm-1 mr-3">
                            <img src="assets/logo.png" class="" width="150" alt="">

                        </div>
                        <div class="col-sm-10  align-items-center">
                        <h2 class="text-center text-black"><?= $_SESSION['college_name']?></h2>
                        <h5 class="text-center text-black"><?= $_SESSION['college_location']?></h5>
                         <h5 class="text-center text-black"><?= $_SESSION['college_phone']?></h5> 

                        </div>
                    </div>
                    <h4 class="text-center text-black"> IP REPORT 
                        <?php
                        echo "Between ". date("d.m.Y",strtotime($ip_from)) ." and ". date("d.m.Y",strtotime($ip_to)); 
                         ?>
                    </h4>

                    
                

            </tbody></table>
        </div>



        <div >
                                    <table class="table table-bordered  nowrap table-hover">
                                        <thead class="thead-dark" >
                                            <tr class="text-center" >
                                            <th>Sl.No</th>
                                              <th>Date</th>
                                              <th>UHID</th>
                                              <th>OP No</th>
                                              
                                              <th>IP No</th>
                                              <th>Patient Name</th>
                                              <th>Gender</th>
                                              <th>Age</th>
                                               <th>Address</th>
                                               <th>Diagnosis</th>
                                               <th>Doctor</th>
                                                 
                                                <!--<th>Unit</th>-->
                                                 <th>Department</th>
                                                 <th>Ward </th>
                                                 <th>Bed No.</th>
                                               <th>DOD</th>
                                              <!--<th>Type</th>-->
                                                
                                                
                                                
                                            </tr>
                                        </thead>
                                        <tbody class=" text-black">
                                        <?php $sn=1;
                            $data=$newReport->getIPGenDetails($ip_from,$ip_to,$_REQUEST['gen'],$_REQUEST['dept'],$_REQUEST['gender']);
                            while($row=mysqli_fetch_assoc($data)){
                            ?>                
                        <tr height="25" class="text-center">
                          <td><?= $sn?></td>
                        <td><?= date("d.m.Y",strtotime($row['admission_date']))?></td>
                        <td><?= $row['uhid']?></td>
                        <td><?= $row['opd_no']?></td>
                        
                        <td><?= $row['ipd_no']?></td>
                        <td><?= $row['name']?></td>
                        <td><?= $row['gender']?></td>
                        <td><?= $row['age']?></td>
                        <td><?= $row['address']?></td>
                         <td><?= $row['diagnosis']?></td>
                          <td><?= $row['doctor']?></td>
                        <!--<td><?= $row['unit']?></td>-->
                        <td><?= $row['dept_name']?></td>
                        <td><?= $row['ward']?></td>
                        <td><?=$row['bed_number']?></td>
                         <td><?php if($row['discharge_date']>'0000-00-00') echo date("d.m.Y",strtotime($row['discharge_date']))?></td>
                        <!--<td><?php if($row['is_generated']==1){ echo "Generated";} else{ echo "Original";}?></td>-->
                         <?php if($_REQUEST['gen']==1 && $row['dis_date']>'0000-00-00') { ?><td><?php if($row['dis_date']>'0000-00-00') echo date("d.m.Y",strtotime($row['dis_date']))?></td> <?php } ?>
                          </tr>
                          <?php $sn++;} ?>
                                            
                                            

                                            

                                           
      
                                            
                           
                                               
                                            
                                            </tbody>
                                            </table>  
                                            
                                            <hr>
                                            
                                            <table id="" class="table table-hoverde text-wrap text-center">
                                    <thead class="thead-dark">
                                    <tr >
                                                <th>Sl NO</th>
                                                <th>Department Name</th>
                                                <th>No.Of New patients Visited</th>
                                            
                                                
                                                
                                                
                                                
                                            </tr>
                                        </thead>
                                        <tbody class="text-center text-black">
                                            
                                    <?php $dept=$newReport->getDept_IP1($ip_from,$ip_to,$_REQUEST['gen'],$_REQUEST['dept']); 
                                    $sn=1;$total=0;
                                    while($row=mysqli_fetch_assoc($dept)){
                                    ?>
                                            <tr>
                                                <td><?= $sn?></td>
                                                <td><?= $row['dept_name']?></td>
                                                <td><?= $row['num']?></td>
                                                
                                                
                                                </tr>
                                                <?php $total +=$row['num'];$sn++; } ?>
                                            
                                            
                                            </tbody>
                                            <tfoot>
                                                <th colspan=2 class="text-right">Total</th>
                                                <th><?= $total ?></th>
                                            </tfoot>
                                            </table>  
                                            </div>  
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <button onclick="window.print()"class="btn btn-danger float-right d-print-none"> <i class="ri-printer-line"></i> Print </button>
                                                </div>
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