<?php date_default_timezone_set("Asia/Kolkata"); 
require_once(__DIR__ . '/model/session_config.php');

include('model/reportClass.php');
$newReport = new report;
$date=date("Y-m-d");
if(isset($_REQUEST['from']))
{
    $op_from=$_REQUEST['from'];
}
else{
        $op_from=date("Y-m-d");
}
if(isset($_REQUEST['to']))
{
    $op_to=$_REQUEST['to'];
}
else{
        $op_to=$date;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>OP Report</title>
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

    font-size:14px
   }

   @media print{
       
       @page {
    size: A4 portrait;
}


        body {
        zoom: 80%; /* Scale down the entire page for printing */
    }

   
    #search{
        visibility: hidden;
    }
   
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
                        <div class="col-sm-1 mr-3">
                            <img src="assets/logo.png" class="" width="150" alt="">

                        </div>
                        <div class="col-sm-10  align-items-center">
                        <h2 class="text-center text-black"><?= $_SESSION['college_name']?></h2>
                        <h5 class="text-center text-black"><?= $_SESSION['college_location']?></h5>
                         <h5 class="text-center text-black"><?= $_SESSION['college_phone']?></h5> 

                        </div>
                    </div>
                    <h4 class="text-center text-black"> OP REPORT 
                        <?php
                        echo "Between ". date("d.m.Y",strtotime($op_from)) ." and ". date("d.m.Y",strtotime($op_to)); 
                         ?>
                    </h4>

                    
                

            </tbody></table>
        </div>



        <div class="table-responsive">
                                    <table class="table table-bordered  nowrap table-hover">
                                        <thead class="thead-dark" >
                                            <tr class="dark">
                                                <th >Sl NO</th>
                                                <th>Date</th>
                                                 <th>UHID</th>
                                                <th>OP Reg.No New</th>
                                                <th>OP Reg.No Old</th>
                                                                                   
                                               
                                                <th> Name</th>
                                                <th>Gender </th>
                                                <th >Age</th>
                                                <!--<th>Address</th>-->
                                                 <!-- <th>Diagnosis</th> -->
                                                <!-- <th>Symptoms</th>  -->
                                                <th>Doctor</th>
                                                <!--<th>Unit</th>-->
                                                 <th>Department</th>
                                               <!--<th>Type</th>-->
                                                <th>Remarks</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody class=" text-black">
                                        <?php $ot=$newReport->getOPDetails($op_from,$op_to,$_REQUEST['gen'],$_REQUEST['dept']);
                $sn=1;
                while($row=mysqli_fetch_assoc($ot)){
                ?>
                                            <tr>
                                                <td><?= $sn?></td>
                                                <td><?= date("d.m.Y",strtotime($row['date']))?></td>
                                                <td><?= $row['uhid']?></td>
                                                <?php if($row['old_patient']==0) {?>
                                                <td><?= $row['opd_no']?></td>
                                                <td></td>

                                                <?php } else { ?>
                                                <td></td>
                                                <td><?= $row['opd_no']?></td>

                                                <?php } ?>
                                                <td><?= $row['name']?></td>
                                                <td ><?= $row['gender']?></td>
                                                <td ><?= $row['age']?></td>
                                                <!--<td ><?= $row['address']?></td>-->
                                                <!-- <td ><?= $row['diagnosis']?></td> -->
                                                <!-- <td ><?= $row['symptoms']?></td> -->
                                                 <td ><?= $row['doctor_name']?></td>
                                                  <!--<td ><?= $row['unit']?></td>-->
                                                <td ><?= $row['dept_name']?></td>
                                                 <!--<td ><?php if($row['is_generated']==1){ echo "Generated";} else{ echo "Original";}?></td>-->
                                                  <td ><?= $row['remark']?> <?php if($row['ipd_no']!='') { echo " Admitted"; } ?></td>
                                               
                                            </tr>
                     <?php $sn++; } ?>     
                                            
                                            

                                            

                                           
      
                                            
                           
                                               
                                            
                                            </tbody>
                                            </table>   
                                            <hr>
                                             <table id="datatable-buttons3"  class="table text-wrap text-center">
                                    <thead class="thead-dark">
                                    <tr >
                                                <th>Sl NO</th>
                                                <th>Departmwent Name</th>
                                                <th>No.Of New patients Visited</th>
                                                <th>No.Of Old patients Visited</th>
                                                <th>Total</th>
                                                
                                                
                                                
                                            </tr>
                                        </thead>
                                        <tbody class="text-center text-black">
                                            <?php $dept=$newReport->getDept_OP1($op_from,$op_to,$_REQUEST['gen'],$_REQUEST['dept']);
                                            $sn=1;$ntotal=$ototal=$total=0;
                                            while ($row=mysqli_fetch_assoc($dept)){?>
                                            <tr> </div>
                                                <td><?= $sn?></td>
                                                <td><?= $row['dept_name']?></td>
                                                <td ><?= $row['num'] - $row['old_patient']?></td>
                                                <td><?= $row['old_patient']?></td>
                                                <td><?= $row['num']?></td>

                                            </tr>
                                              <?php 
                                              $ntotal += $row['num'] - $row['old_patient'];
                                              $ototal += $row['old_patient'];
                                              $total +=$row['num'];
                                              $sn++; } ?>  
                                            
                                            
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th></th>
                                                <th>Total</th>
                                                <th><?= $ntotal ?></th>
                                                <th><?= $ototal ?></th>
                                                <th><?= $total ?></th>
                                                </tr>
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


        <!-- Datatable init js -->
        <script src="assets/js/pages/datatables.init.js"></script>


  <script>
     $(document).ready(function() {
    $('table.display').DataTable();
} );
  </script>
  

</body>

</html>