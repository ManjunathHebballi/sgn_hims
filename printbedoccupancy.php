<?php

date_default_timezone_set("Asia/Kolkata"); 

require_once(__DIR__ . '/model/session_config.php');
$date=date("Y-m-d");

include('model/reportClass.php');
$newReport = new report;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Deptwise Report</title>
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
    <div class="container-fluid px-5">
  <div class="row">
        <div class="col-md-12">
           
                    <div class="row">
                        <div class="col-md-1 mr-3">
                            <img src="assets/logo.png"  width="100" class="" alt="">

                        </div>
                        <div class="col-md-10  align-items-center">
                        <h5 class="text-center text-black"><?= $_SESSION['college_society']?></h5>
                        <h3 class="text-center text-black"><?= $_SESSION['college_name']?></h3>
                        <h6 class="text-center text-black"><?= $_SESSION['college_location']?></h6>
                        <h6 class="text-center text-black"><?= $_SESSION['college_phone']?></h6>

                        </div>
                    </div>
                   <div class="row mt-3">
        <div class="col-md-12">
            
                        <h4 class="ml-4 text-center text-black mb-3 ">Bed Occupancy <span class="text-danger"> On <?= date("d.m.Y",strtotime($_REQUEST['on']))?> </span></h4>
                    
                   

                  
                    <table id="example3" class="table table-bordered display text-center">
                                    <thead class="thead-dark">
                                    <tr >
                                                <th>Sl NO</th>
                                                <th>Date</th>
                                                <th>OPD No</th>
                                                <th>IP Reg.No New</th>
                                                <th>Name</th>
                                                <th>Gender</th>
                                                <th>Age</th>
                                                <th>Address</th>
                                                <th>Department</th>
                                                <th>DOA </th>
                                                <th>DOD </th>
                                                <th> Bed NO </th>
                                                
                                                
                                                
                                            </tr>
                                        </thead>
                                        <tbody class="text-center text-black">
                                            <?php $sn=1;
                                            $data=$newReport->getBedOccupancy($_REQUEST['on']);
                                            while($row=mysqli_fetch_assoc($data)){
                                            ?>
                                            <tr>
                                                <td><?= $sn?></td>
                                                <td><?= date("d.m.Y",strtotime($row['admission_date']))?></td>
                                                 <td><?= $row['opd_no']?></td>
                                                <td><?= $row['ipd_no']?></td>
                                                <td><?= $row['name']?></td>
                                                <td ><?= $row['gender']?></td>
                                                <td ><?= $row['age']?></td>
                                                <td ><?= $row['address']?></td>
                                                <td ><?= $row['dept_name']?></td>
                                                <td ><?= date("d.m.Y",strtotime($row['admission_date']))?></td>
                                                <td ><?php if(strlen($row['discharge_date'])> 0 ) {echo date("d.m.Y",strtotime($row['discharge_date'])); }?></td>
                                                <td><?= $row['bed_number']?></td>
                                            </tr>
                                            <?php  $sn++; } ?>
                                            
                                            
                                            </tbody>
                                            </table>   
                    
                </div>
            </div>
        
<div class="row mt-4">
        <div class="col-md-12">
            
                    <div class="row">
                        <h5 class="ml-4">List of Patients Admitted <span class="text-danger"> On <?= date("d.m.Y",strtotime($_REQUEST['on']))?> </span></h5>
                    </div>
                    <hr>

                   
                    <table id="example3" class="table table-bordered display text-center">
                                    <thead class="thead-dark">
                                    <tr >
                                                <th>Sl NO</th>
                                                <th>Date</th>
                                                <th>OPD No</th>
                                                <th>IP Reg.No New</th>
                                                <th>Name</th>
                                                <th>Gender</th>
                                                <th>Age</th>
                                                <th>Address</th>
                                                <th>Department</th>
                                                <th>DOA </th>
                                                <th>DOD </th>
                                                <th> Bed NO </th>
                                                
                                                
                                                
                                            </tr>
                                        </thead>
                                        <tbody class="text-center text-black">
                                            <?php $sn=1;
                                            $data=$newReport->getAdmission($_REQUEST['on']);
                                            while($row=mysqli_fetch_assoc($data)){
                                            ?>
                                            <tr>
                                                <td><?= $sn?></td>
                                                <td><?= date("d.m.Y",strtotime($row['admission_date']))?></td>
                                                <td><?= $row['opd_no']?></td>
                                                <td><?= $row['ipd_no']?></td>
                                                <td><?= $row['name']?></td>
                                                <td ><?= $row['gender']?></td>
                                                <td ><?= $row['age']?></td>
                                                <td ><?= $row['address']?></td>
                                                <td ><?= $row['dept_name']?></td>
                                                <td ><?= date("d.m.Y",strtotime($row['admission_date']))?></td>
                                                <td ><?php if(strlen($row['discharge_date'])> 0 ) {echo date("d.m.Y",strtotime($row['discharge_date'])); }?></td>
                                                <td><?= $row['bed_number']?></td>
                                            </tr>
                                            <?php $sn++; } ?>
                                            </tbody>
                                            </table>   
                    </div>
                </div>
          
        
    <div class="row mt-4">
        <div class="col-md-12">
            
                    <div class="row">
                        <h5 class="ml-4">List of Patients Discharged <span class="text-danger"> On <?= date("d.m.Y",strtotime($_REQUEST['on']))?> </span></h5>
                    </div>
                    <hr>

                   
                    <table id="example3" class="table table-bordered display text-center">
                                    <thead class="thead-dark">
                                    <tr >
                                                <th>Sl NO</th>
                                                <th>Date</th>
                                                <th>OPD No</th>
                                                <th>IP Reg.No New</th>
                                                <th>Name</th>
                                                <th>Gender</th>
                                                <th>Age</th>
                                                <th>Address</th>
                                                <th>Department</th>
                                                <th>DOA </th>
                                                <th>DOD </th>
                                                <th> Bed NO </th>
                                                
                                                
                                                
                                            </tr>
                                        </thead>
                                        <tbody class="text-center text-black">
                                            <?php $sn=1;
                                            $data=$newReport->getDischarge($_REQUEST['on']);
                                            while($row=mysqli_fetch_assoc($data)){
                                            ?>
                                            <tr>
                                                <td><?= $sn?></td>
                                                <td><?= date("d.m.Y",strtotime($row['admission_date']))?></td>
                                                <td><?= $row['opd_no']?></td>
                                                <td><?= $row['ipd_no']?></td>
                                                <td><?= $row['name']?></td>
                                                <td ><?= $row['gender']?></td>
                                                <td ><?= $row['age']?></td>
                                                <td ><?= $row['address']?></td>
                                                <td ><?= $row['dept_name']?></td>
                                                <td ><?= date("d.m.Y",strtotime($row['admission_date']))?></td>
                                                <td ><?= date("d.m.Y",strtotime($row['discharge_date']))?></td>
                                                <td><?= $row['bed_number']?></td>
                                            </tr>
                                            <?php $sn++; } ?>
                                            </tbody>
                                            </table>   
                    </div>
                </div>
           
      
    
      <div class="row mt-4">
        <div class="col-md-12">
           
                    <div class="row">
                        <h5 class="ml-4">DEPARTMENT WISE Gender wise Count of BED Occupany REPORT<span class="text-danger"> 
                     
                            </span></h5>
                    </div>
                    <hr>

                 
                    <table id="" class="table table-bordered  text-center">
                                    <thead class="thead-dark">
                                    <tr>
                                                <th>Sl NO</th>
                                                <th>Department Name</th>
                                                <th>Male Count</th>
                                                <th>Female Count</th>
                                                <th>Total</th>
                                                
                                                
                                                
                                            </tr>
                                        </thead>
                                        <tbody class="text-center text-black">
                                            <?php $dept=$newReport->getBedOccupancy1($_REQUEST['on']); 
                                    $sn=1;
                                    $male=$female=$total=0;
                                    while($row=mysqli_fetch_assoc($dept)){
                                        $male +=$row['male'];
                                        $female +=$row['female'];
                                        $total +=$row['male'] + $row['female'];
                                        
                                    ?>
                                            <tr>
                                                <td><?= $sn?></td>
                                                <td><?= $row['dept_name']?></td>
                                                 <td><?= $row['male']?></td>
                                                <td><?= $row['female']?></td>
                                                <td ><?= $row['male'] + $row['female']?></td>
                                                
                                                </tr>
                                                <?php $sn++; } ?>
                                            
                                               
                                                
                                                
                                            
                                            
                                            </tbody>
                                            <tfoot>
                                                <th colspan=2>Total</th>
                                                <th><?= $male?></th>
                                                <th><?= $female?></th>
                                                <th><?= $total?></th>
                                            </tfoot>
                                            </table>   
                   
                </div>
            </div>
            </div>
        
<script src="./vendor/global/global.min.js"></script>
    <script src="./js/quixnav-init.js"></script>
    <script src="./js/custom.min.js"></script>

    
    <!-- Datatable -->
    
  

</body>

</html>