<?php 
require_once(__DIR__ . '/../model/session_config.php');
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
    <title>Doctor Revenue Report</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
  
    <link href="./css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">

  

        <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">

</head>

<style>
   td{

    font-size:15px
   }
   
.border-0{
    
    border:0px;
}

@media print {
    @page {
        size: A4;
         
    }

  
}

</style>

<body style=" font-family: 'Poppins', sans-serif; background-color:white" onload="window.print()">

    <!--*******************
        Preloader start
    ********************-->


    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">





  

        <!--**********************************
            Content body start
        ***********************************-->
        
            <!-- row -->







                <div class="container header-container">
                    <div class="row d-flex align-items-center ">
                        <div class="col-sm-3 ">
                            <img src="assets/logo.png" class="img-fluid" width="150"  alt="">

                        </div>
                        <div class="col-sm-8  ">
                        <h3 class="text-center text-black"><?= $_SESSION['college_name']?></h3>
                        <h6 class="text-center text-black"><?= $_SESSION['college_location']?></h6>
                        <p class="text-center text-black"><?= $_SESSION['college_mail']?></p>
                        <p class="text-center text-black"><?= $_SESSION['college_phone']?></p>

                        </div>
                    </div>
                    
        <!--</div>-->
        
         <div class=" tbl ">
             
                 

                    
                




       
                                      <table class="table text-wrap text-left table-bordered mt-2">
                                       <thead class="thead-dark" >
                                            <tr class="dark">
                                               <th>Sl NO</th>
                                               <th>Date</th>
                                                <th>Bill NO</th>
                                                <th>Patient Reg No</th>
                                                <th>Name</th>
                                                <th>Gender</th>
                                                <th>Doctor</th>
                                                <th>Paid Amount</th>
                                                 <th>Collected By</th>
                                                 <th>Payment Mode</th>
                                                
                                                
                                               
                                              
                                                
                                            </tr>
                                        </thead>
                                        <tbody class=" text-black">
                                        <?php $cent=$newReport->getDeptCollectionDetails($_REQUEST['from'],$_REQUEST['to'],$_REQUEST['search'],$_REQUEST['type']);
                $sn=1;$total=$grand=$count=$allcount=$serv=$allserv=$disc=$alldisc=$net=$allnet=$doctor=$alldoctor=$hospital=$allhospital=0;
                while($row=mysqli_fetch_assoc($cent)){
                  
                if($dr!=$row['dept_name']) { 
                if($sn>1){?>
                <tr>
                    <th colspan=7 class="text-center">Total</th>
                    <th><?= $serv?></th>
                    
                    <th></th>
                    <th></th>
                </tr>
                <?php $count=$serv=$disc=$net=$doctor=$hospital=0;
                } ?>
                <tr><th colspan='8'><?= $row['dept_name']?></th></tr> 
                <?php }?>
                <tr>
                <td><?= $sn?></td>
                <td><?= $row['date']?></td>
                <td><?= $row['bill_no']?></td>
                <td><?= $row['opd']?></td>
                <td><?= $row['name']?></td>
                <td><?= $row['gender']?></td>
                <td><?= $row['doctor']?></td>
                <td><?= $row['paid_amount']?></td>
                <td><?= $row['collected_by']?></td>
                <td><?= $row['payment_mode']?></td>
                </tr>
                                        <?php $sn++;
                                        
                                        $serv +=$row['paid_amount'];
                                        $allserv +=$row['paid_amount'];
                                        $dr=$row['dept_name'];
                                        } ?>
                                        <tr>
                                            <th colspan=7 class="text-center">Total</th>
                                            <th><?= $serv?></th>
                                           
                                            <th></th>
                    <th></th>
                                        </tr>
                                        <tr>
                                            <th colspan=7 class="text-center">Grand Total</th>
                                            <th><?= $allserv?></th>
                                           
                                            <th></th>
                    <th></th>
                                        </tr>
                                        </tbody> </table>   
                                            </div>  

                                            <div class="row">
                                                <div class="col-md-12">
                                                       <button onclick="window.print()"class="btn btn-primary float-right d-print-none"> <i class="ri-printer-line"></i> Print </button>
                                                     <button onclick="window.history.go(-1)" class="btn btn-danger float-right mr-2 d-print-none"> Back </button>
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