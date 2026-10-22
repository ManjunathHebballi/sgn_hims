<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata"); 
include('model/reportClass.php');
$newReport = new report;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Bed Occupancy </title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">


        <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">

</head>



<body style=" font-family: 'Poppins', sans-serif; background-color:white;" onload="window.print()">

    <!--*******************
        Preloader start
    ********************-->
  
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
                     <div class="row">
                        <div class="col-md-1 mr-3">
                            <img src="assets/logo.png" class="" width="150" alt="">

                        </div>
                        <div class="col-md-10  align-items-center">
                        <h2 class="text-center text-black"><?= $_SESSION['college_name']?></h2>
                        <h5 class="text-center text-black"><?= $_SESSION['college_location']?></h5>
                         <h5 class="text-center text-black"><?= $_SESSION['college_phone']?></h5> 

                        </div>
                    </div>
                    <h4 class="text-center text-black"> Dept wise Bed Occupancy On
                        <?php
                        echo date("d.m.Y") ; 
                         ?>
                    </h4>

                    
                

            </tbody></table>
        </div>



        <div >
                    <table  class="table table-bordered table-hoverde  text-center">
                                    <thead class="thead-dark">
                                    <tr >
                                                <th>Sl NO</th>
                                                <th>DEPARTMENT & PATIENT NAME</th>
                                                <th>Total Bed</th>
                                                <th>CUR_BED OCC.</th>
                                                <th>FLOOR_BED OCC.</th>
                                                <th>BEDOCC.(%)</th>
                                   </tr>
                                        </thead>
                                        <tbody class="text-center text-black">
                                            
                                            <?php $sn=1;
                                            $total=$total_occ=0;
                                            $bedDept=$newReport->getBedOcc($_REQUEST['date']);
                                            while($row=mysqli_fetch_assoc($bedDept)){
                                            
                                            ?>
                                            <tr>
                                                <td><?= $sn?></td>
                                                <td><?= $row['dept_name']." - ". $row['ward']." - ". $row['unit']?></td>
                                                <td><?= $row['bed_count']?></td>
                                                <td><?= $row['occupied']?></td>
                                                <td><?= ""?></td>
                                                
                                                <td><?=round(($row['occupied']*100)/$row['bed_count'],2)?></td>
                                              </tr>
                                              <?php $sn++; 
                                                    $total +=$row['bed_count'];
                                                    $total_occ +=$row['occupied'];
                                                    $total_percent +=round(($row['occupied']*100)/$row['bed_count'],2);
                                              } ?>
                                            <tr >
                                                <td></td>
                                                <td class="font-weight-bold">Total</td>
                                                <td class="font-weight-bold"><?= $total?></td>
                                                <td class="font-weight-bold"><?= $total_occ?></td>
                                                <td class="font-weight-bold"><?= ''?></td>
                                                <td class="font-weight-bold"><?= round(($total_occ*100)/$total,2)?></td> 
                                              </tr>    
                                                
                                            
                                            
                                            </tbody>
                                            </table>   
                    </div>
                    
                    
                    
                    
                    
                    <br>
                    <br>
                    <br>
                    
                   
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
    <!-- Required vendors -->
    <script src="./vendor/global/global.min.js"></script>
    <script src="./js/quixnav-init.js"></script>
    <script src="./js/custom.min.js"></script>

    
  



  

</body>

</html>