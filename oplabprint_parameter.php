<?php date_default_timezone_set("Asia/Kolkata"); 
require_once(__DIR__ . '/model/session_config.php');

include('model/reportClass.php');
$newReport = new report;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= $_REQUEST['name'] ?> OP Lab Report</title>
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

    font-size:10px
   }

   @media print {

    .dark{
background-color:black;

-webkit-print-color-adjust:exact;
    }
   }
</style>

<body style=" font-family: 'Poppins', sans-serif;" onload="window.print()">

    


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
                            <img src="assets/logo.png" class="" alt="" width=100>

                        </div>
                        <div class="col-sm-10  align-items-center">
                        <h2 class="text-center text-black"><?= $_SESSION['college_name']?></h2>
                        <h5 class="text-center text-black"><?= $_SESSION['college_location']?></h5>
                        <h5 class="text-center text-black"><?= $_SESSION['college_phone']?></h5>

                        </div>
                    </div>
                    
                    <h4 class="text-center text-black">  <?php if($_SESSION['role']=='Radiology')
                                            {echo 'Radiology'; } else echo 'LAB - '. $_REQUEST['name'] ;?> OP REPORT 
                        <?php if($_REQUEST['from'] !='2000-01-01' && $_REQUEST['to'] !='2049-12-01') { 
                        echo "Between ". date("d.m.Y",strtotime($_REQUEST['from'])) ." and ". date("d.m.Y",strtotime($_REQUEST['to'])); 
                         } ?>
                    </h4>

                    
                

            </tbody></table>
        </div>



        <div class="table-responsive">
                                    <table class="table table-bordered  nowrap table-hover">
                                        <thead class="thead-dark" >
                                            <tr class="dark">
                                                <th >Sl NO</th>
                                                <th>Date</th>
                                                <th>OP Reg No</th>
                                                                                   
                                               
                                                <th> Name</th>
                                                <th>Age</th>
                                                <th>Gender </th>
                                                 <th>Department </th>
                                                  <th>Doctor </th>
                                                   <!--<th >Technician</th>-->
                                                   <!--<th>Diagnosis</th>-->
                                                    <th>Symptoms</th>
                                                <!--<th >Investigation</th>-->
                                                <th>Result</th>
                                                <!--<th>Recipt No</th>-->
                                               

                                                
                                                
                                               
                                              
                                                
                                            </tr>
                                        </thead>
                                        <tbody class=" text-black">
                                        <?php $ot=$newReport->oplabReportParameter($_REQUEST['from'],$_REQUEST['to'],$_REQUEST['id']);
                $sn=1;
                while($row=mysqli_fetch_assoc($ot)){
                ?>
                                            <tr>
                                                <td><?= $sn?></td>
                                                <td><?= date("d.m.Y",strtotime($row['date']))?></td>
                                                <td><?= $row['opd_no']?></td>
                                                <td><?= $row['name']?></td>
                                                 <td><?= $row['age']?></td>
                                                <td ><?= $row['gender']?></td>
                                                <td ><?= $row['dept_name']?></td>
                                                <td ><?= $row['doc']?></td>
                                                <!--<td ><?= $row['diagnosis']?></td>-->
                                                <td ><?= $row['symptoms']?></td>
                                                <!--<td><?= $row['lab_tech']?></td>-->
                                                <!--<td ><?= $row['test']?></td>-->
                                                
                                                <td ><?= $row['test_result']?></td>
                                                <!--<td >FREE</td>-->
                                               
                                            </tr>
                     <?php $sn++; } ?>     
                                            
                                            

                                            

                                           
      
                                            
                           
                                               
                                            
                                            </tbody>
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

    
   
     



</body>

</html>