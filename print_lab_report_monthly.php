<?php 
date_default_timezone_set("Asia/Kolkata"); 
require_once(__DIR__ . '/model/session_config.php');
$year=$_REQUEST['year'];
include('model/reportClass.php');
$newReport = new report;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Print Montly OPD Registry Report</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

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
      
      size: landscape}

    .dark{
background-color:black;

-webkit-print-color-adjust:exact;
    }
   }
   
   th{
       font-weight:600;
   }
   
   .table th, .table td {
       
       padding:3px;
       font-size:0.8rem;
      text-align: center; 
    vertical-align: middle;
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
      
                <div class="container">
                   
                        <div class="d-flex align-items-center ml-3">
                            <img src="assets/logo.png" class="" alt="" width="150">

                        
                        <div class=" ml-5">
                        <h5 class="text-center text-black"><?= $_SESSION['college_society']?></h5>
                        <h3 class="text-center text-black"><?= $_SESSION['college_name']?></h3>
                        <h5 class="text-center text-black"><?= $_SESSION['college_location']?></h5>
                        <h5 class="text-center text-black"><?= $_SESSION['college_phone']?></h5>

                        </div>
                   </div>


                    
                

          
        </div>

                    <hr/>
                    <h4 class="text-center text-black mb-3 mt-5">Monthly LAB Registry Report of <?=$year?>
                       
                    </h4>

                               <div class="table-responsive">
                    <table class="table table-bordered text-center  text-wrap table-hover">
                                        <thead class="bg-primary te">
                                            <th>
                                            Group
                                           </th>
                                      
                                            <th>JAN<br>Patient -- Test</th>
                                            <th>FEB<br>Patient -- Test</th>
                                            <th>MAR<br>Patient -- Test</th>
                                            <th>APR<br>Patient -- Test</th>
                                            
                                            <th>MAY<br>Patient -- Test</th>
                                            <th>JUNE<br>Patient -- Test</th>
                                            <th>JULY<br>Patient -- Test</th>
                                            <th>AUG<br>Patient -- Test</th>
                                            
                                            <th>SEP<br>Patient -- Test</th>
                                            <th>OCT<br>Patient -- Test</th>
                                            <th>NOV<br>Patient -- Test</th>
                                            <th>DEC<br>Patient -- Test</th>
                                        
                                        </thead>
                                        <tbody class=" text-black">
                                            <?php $res=$newReport->getMonthlyLab($year);
                                            while($row=mysqli_fetch_assoc($res)) { ?>
                                           <tr>
                                               <td><?=$row['testname'];?></td>
                                                <td><?=$row['jan_distinct']." --  ".$row['jan_lab_results']?></td>
                                                
                                                <td><?=$row['feb_distinct']." --  ".$row['feb_lab_results']?></td>
                                                
                                                <td><?=$row['mar_distinct']." --  ".$row['mar_lab_results']?></td>
                                                
                                                <td><?=$row['apr_distinct']." --  ".$row['apr_lab_results']?></td>
                                                
                                                <td><?=$row['may_distinct']." --  ".$row['may_lab_results']?></td>
                                                
                                                <td><?=$row['june_distinct']." --  ".$row['june_lab_results']?></td>
                                                
                                                <td><?=$row['july_distinct']." --  ".$row['july_lab_results']?></td>
                                                
                                                <td><?=$row['aug_distinct']." --  ".$row['aug_lab_results']?></td>
                                                
                                                <td><?=$row['sep_distinct']." --  ".$row['sep_lab_results']?></td>
                                                
                                                <td><?=$row['oct_distinct']." --  ".$row['oct_lab_results']?></td>
                                                
                                                <td><?=$row['nov_distinct']." --  ".$row['nov_lab_results']?></td>
                                                
                                                <td><?=$row['dec_distinct']." --  ".$row['dec_lab_results']?></td>
                                           </tr>
                                           <?php } ?>
</tbody>
                                            <!-- <tfoot>
                                                <tr>
                                                    <th>Total</th>
                                                    <th>17</th>
                                                    <th>15</th>
                                                    <th>32</th>
                                                    <th>0</th>
                                                    <th>0</th>
                                                </tr>
                                            </tfoot> -->
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
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>

</body>

</html>