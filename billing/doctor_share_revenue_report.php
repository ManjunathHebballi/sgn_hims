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

  .doctor {page-break-after: always;}
  
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







        <!--        <div class="container header-container">-->
        <!--            <div class="row d-flex align-items-center ">-->
        <!--                <div class="col-sm-3 ">-->
        <!--                    <img src="assets/logo.png" class="img-fluid" width="150"  alt="">-->

        <!--                </div>-->
        <!--                <div class="col-sm-8  ">-->
                        <!--<h5 class="text-center text-black"><?= $_SESSION['college_name']?></h5>-->
        <!--                <h3 class="text-center text-black"><?= $_SESSION['college_name']?></h3>-->
        <!--                <p class="text-center text-black"><?= $_SESSION['college_mail']?></p>-->
        <!--                <p class="text-center text-black"><?= $_SESSION['college_phone']?></p>-->

        <!--                </div>-->
        <!--            </div>-->
                    
        <!--</div>-->
        
         <div class=" tbl ">
             
                 

                    
                




       
                                    <table class="table table-bordered   table-hover" width="80">
                                        <thead class="" >
                                            <tr class="border-0" >
                                                <th>
                                                     <img src="assets/logo.png" class="img-fluid" width="150"  alt="">
                                                </th>
                                                <th colspan="7">
                                                    <h3 class="text-center text-black"><?= $_SESSION['college_name']?></h3>
                        <p class="text-center text-black"><?= $_SESSION['college_mail']?></p>
                        <p class="text-center text-black"><?= $_SESSION['college_phone']?></p>
                                                </th>
                                            </tr>
                                            <tr>
                                                <td colspan='8'>
                                                       <h4 class="text-center text-black "> Doctor Revenue Report 
                    <?php if($_REQUEST['from'] !='2000-01-01' && $_REQUEST['from'] !='2049-12-01') { 
                        echo "Between ". date("d.m.Y",strtotime($_REQUEST['from'])) ." and ". date("d.m.Y",strtotime($_REQUEST['to'])); 
                         } ?>
                    </h4>
                                                </td>
                                            </tr>
                                            <tr class="dark">
                                               <th>Sl NO</th>
                                                <th>Service Name</th>
                                                <th>Count</th>
                                                <th>Service Amt</th>
                                                <th>Service Level Disc</th>
                                                <th>Net Amount</th>
                                                <th>Doctor Share</th>
                                                <th>Hospital Share</th>
                                                
                                                
                                               
                                              
                                                
                                            </tr>
                                        </thead>
                                        <tbody class=" text-black">
                                        <?php $cent=$newReport->getDoctorShare($_REQUEST['from'],$_REQUEST['to'],$_REQUEST['search'],$_REQUEST['service']);
                $ln=$sn=1;$total=$grand=$count=$allcount=$serv=$allserv=$disc=$alldisc=$net=$allnet=$doctor=$alldoctor=$hospital=$allhospital=0;
                while($row=mysqli_fetch_assoc($cent)){
                  
                if($dr!=$row['doctor']) { 
                if($sn>1){?>
                <tr>
                    <th colspan=2>Total</th>
                    <th><?= $count?></th>
                    <th><?= $serv?></th>
                    <th><?= $disc?></th>
                    <th><?= $net?></th>
                    <th><?= $doctor?></th>
                    <th><?= $hospital?></th>
                </tr>
                <tr  class="doctor"></tr>
                <?php $count=$serv=$disc=$net=$doctor=$hospital=0;$sn=1;
                } ?>
                <tr ><th><?= $ln?></th><th colspan='7'><?= $row['doctor']?></th></tr> 
                <?php $ln++; }?>
                <tr>
                <td><?= $sn?></td>
                <td><?= $row['name']?></td>
                <td><?= $row['count']?></td>
                <td><?= $row['amount']?></td>
                <td><?= $row['discount']?></td>
                <td><?= $net1=$row['amount']-$row['discount']?></td>
                <td><?= round($row['share_amt'])?></td>
                <td><?= $net1-$row['share_amt']?></td>
                </tr>
                 
               
                                        <?php $sn++;
                                        $count +=$row['count'];
                                        $allcount +=$row['count'];
                                        $serv +=$row['amount'];
                                        $allserv +=$row['amount'];
                                        $disc +=$row['discount'];
                                        $alldisc +=$row['discount'];
                                        $net +=$net1;
                                        $allnet +=$net1;
                                        $doctor +=round($row['share_amt']);
                                        $alldoctor +=round($row['share_amt']);
                                        $hospital +=$net1-$row['share_amt'];
                                        $allhospital += $net1-$row['share_amt'];
                                        
                                        $dr=$row['doctor'];
                                        } ?>
                                        
                                        <tr>
                                            <th colspan=2>Total</th>
                                            <th><?= $count?></th>
                                            <th><?= $serv?></th>
                                            <th><?= $disc?></th>
                                            <th><?= $net?></th>
                                            <th><?= $doctor?></th>
                                            <th><?= $hospital?></th>
                                        </tr>
                                        <tr>
                                            <th colspan=2>Grand Total</th>
                                            <th><?= $allcount?></th>
                                            <th><?= $allserv?></th>
                                            <th><?= $alldisc?></th>
                                            <th><?= $allnet?></th>
                                            <th><?= $alldoctor?></th>
                                            <th><?= $allhospital?></th>
                                        </tr>
                                        </tbody>
                                            </table>   
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