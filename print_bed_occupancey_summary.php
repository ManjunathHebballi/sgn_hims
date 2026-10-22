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
    <title>Bed Occupancy Summary</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
  
    <link href="./css/style.css" rel="stylesheet">




        <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">

</head>



<body style=" font-family: 'Poppins', sans-serif;background-color:white;" onload="window.print()">

  


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
                    <h4 class="text-center text-black">  Bed Occupancy Summary of
                        <?php
                        echo date("d.m.Y") ; 
                         ?>
                    </h4>

                    
                

        
        </div>

                    <div class="">
                    <table id="example3" class="table  table-bordered table-hoverde display text-center">
                                    <thead class="thead-dark">
                                    <tr >
                                        <th>Sl No</th>
                                        <th>IPD No</th>
                                        <th>OPD No</th>
                                        <th>Department</th>
                                        <th>Patient Name</th>  
                                        <th>Age</th>
                                        <th>Sex</th>
                                   </tr>
                                        </thead>
                                        <tbody class="text-center text-black">
                                            
                                        <?php
                                        
                                        $sn=1;
                                        $flag='';
                                        $res=$newReport->getBedOccDetails($_REQUEST['date']);
                                               while($row=mysqli_fetch_assoc($res))
                                               { 
                                                 
                                               if($flag!=$row['ward']) {?>
                        
                        <tr ><td class="text-capitalize text-center font-weight-bold" colspan="5" ><?= $row['ward']?></td></tr>
                        
                        
                        <?php
                    } ?>
                                            <tr>
                                                <td><?=$sn;?></td>
                                                <td><?=$row['ipd_no'];?></td>
                                                <td><?=$row['opd_no'];?></td>
                                                <td><?=$row['dept_name'];?></td>
                                                <td><?=$row['name'];?></td>
                                                <td><?=$row['age'];?></td>
                                                <td><?=$row['gender'];?></td>
                                            </tr>
                                                
                                           <?php $sn++;$flag=$row['ward'];  } ?>
                                            
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