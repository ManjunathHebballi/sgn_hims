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
    <title>Departemtn wise Report</title>
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
             
                 

                    
                




       
                                    <table class="table table-bordered   table-hover" width="80">
                                        <thead class="" >
                                            <tr>
                                                <td colspan='8'>
                                                       <h4 class="text-center text-black "> <?= $_REQUEST['type']?> Department wise Report 
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
                                                <th>Amount</th>
                                                <?php
                                        if($_REQUEST['type']=='IP')
                                        {
                                            echo '<th>Discounted Amount</th><th>Adjusted Amount</th><th>Paid Amount</th>';
                                        } 
                                            ?>
                                                
                                                
                                                
                                            </tr>
                                        </thead>
                                        <tbody class=" text-black">
                                        <?php $cent=$newReport->getDepartmentCollection($_REQUEST['from'],$_REQUEST['to'],$_REQUEST['search'],$_REQUEST['type']);
                $sn=1;$disc=0;$amount=$discount=$adjust=0;
                while($row=mysqli_fetch_assoc($cent)){
                  if($dr!=$row['dept_name']) { 
                if($sn>1){
                $data=$newReport->getDepartmentAdjustedAdvance($_REQUEST['from'],$_REQUEST['to'],$dept_id);
                $res=mysqli_fetch_assoc($data);
                ?>
                <tr>
                    <th colspan=2 class="text-center">Total</th>
                    <th><?= $count?></th>
                    <th><?= $serv?></th>
                    <?php
                    if($_REQUEST['type']=='IP')
                    { ?>
                    <th><?= $res['discount_amount']?></th>
                    <th><?= $res['adjusted_amount']?></th>  
                    <th><?= $serv-$res['adjusted_amount']-$res['discount_amount']?></th>  
                    <?php } ?>
                </tr>
                <?php  $adjust +=$res['adjusted_amount'];
                 $amount +=$serv-$res['adjusted_amount']-$res['discount_amount'];
                 $discount +=$res['discount_amount'];
                 $count=$serv=$disc=0;
               
                } ?>
                <tr><th colspan='7'><?= $row['dept_name']?></th></tr> 
                <?php } ?>
                <tr>
                <td><?= $sn?></td>
                <td><?= $row['name']?></td>
                <td><?= $row['count']?></td>
                 <td><?= $row['paid']?></td>
                      <?php
                                        if($_REQUEST['type']=='IP')
                                        {
                                           
                                            echo '<td></td>';
                                            echo "<td></td>";
                                            echo "<td></td>";
                                        }  ?>
                </tr>
                 
               
                                        <?php $sn++;
                                        $count +=$row['count'];
                                 $serv +=$row['paid'];
                                     $paid +=   $row['paid'];
                                       $net +=$row['count'];
                                       $dr=$row['dept_name'];
                                       $disc +=$row['discount'];
                                       $dept_id =$row['dept_id'];
                                        } 
                                        
                $data=$newReport->getDepartmentAdjustedAdvance($_REQUEST['from'],$_REQUEST['to'],$dept_id);
                $res=mysqli_fetch_assoc($data);
                $adjust +=$res['adjusted_amount'];
                 $amount +=$serv-$res['adjusted_amount']-$res['discount_amount'];
                 $discount +=$res['discount_amount'];
                                        ?>
                                          <tr>
                    <th colspan=2 class="text-center">Total</th>
                    <th><?= $count?></th>
                    
                    <th><?= $serv?></th>
                    <?php
                    if($_REQUEST['type']=='IP')
                    { ?>
                    <th><?= $res['discount_amount']?></th>
                    <th><?= $res['adjusted_amount']?></th>  
                    <th><?= $serv-$res['adjusted_amount']-$res['discount_amount']?></th>  
                    <?php } ?>
                </tr>
                                        
                                        <tr>
                                            <th colspan=2>Grand Total</th>
                                            <th><?= $net?></th>
                                             <th><?= $paid?></th>
                                         <?php    if($_REQUEST['type']=='IP')
                                        {
                                            echo "<th>$discount</th><th>$adjust</th>";
                                            echo "<th>$amount</th>";
                                        } ?>
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