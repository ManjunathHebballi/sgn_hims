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
                    <h4 class="text-center text-black mb-3 mt-5">Monthly OPD Registry Report
                       
                    </h4>

                               <table class="table table-bordered text-center  text-wrap table-hover">
                                        <thead class="">
                                            <tr>
                                            <th rowspan="3">Month</th>
                                            <?php
                                            $count=0;
                                            $res=$newReport->getDepartment();
                                            while($row=mysqli_fetch_assoc($res))
                                            {
                                            ?>
                                            <th colspan="5"><?= $row['dept_name']?></th>
                                            <?php
                                            $count++;
                                            } ?>
                                            <th rowspan="3">Total</th>
                                            </tr>
                                            <tr>
                                              <?php for($i=0;$i<$count;$i++) { ?>  
                                                <th colspan="2">  New </th>
                                              <th colspan="2">  Old </th>
                                              <th rowspan="2">Tot</th>
                                              <?php } ?>
                                              </tr>
                                            <tr class="dark">
                                                <?php for($i=0;$i<$count;$i++) { ?> 
                                                <th> M </th>
                                                <th> F </th>
                                                <th> M </th>
                                                <th> F </th>
                                                 <?php } ?>
                                            </tr>
                                        </thead>
                                         <tbody class=" text-black">
                                           <?php 
                                          $gm_male_new=$gm_male_old=$gm_female_new=$gm_female_old=$pd_male_new=$pd_male_old=$pd_female_new=$pd_female_old=$ps_male_new=$ps_male_old=$ps_female_new=$ps_female_old=$sk_male_new=$sk_male_old=0;
                                          $sk_female_new=$sk_female_old=$tb_male_new=$tb_male_old=$tb_female_new=$tb_female_old=$gs_male_new=$gs_male_old=$gs_female_new=$gs_female_old=$or_male_new=$or_male_old=$or_female_new=$or_female_old=0;
                                          $op_male_new=$op_male_old=$op_female_new=$op_female_old=$obg_male_new=$obg_male_old=$obg_female_new=$obg_female_old=$em_male_new=$em_male_old=$em_female_new=$em_female_old=$en_male_new=0;
                                          $en_male_old=$en_female_new=$en_female_old=$dn_male_new=$dn_male_old=$dn_female_new=$dn_female_old=$ph_male_new=$ph_male_old=$ph_female_new=$ph_female_old=0;
                                          $total=0;
                                           for($i=1;$i<=date('n');$i++) {
                                           $gm=$pd=$ps=$sk=$tb=$gs=$or=$op=$obg=$em=$en=$dn=$ph=0?>
                                           <tr>
                                            <th><?= date("M",mktime(0,0,0,$i+1,0,0))?></th>
                                            <?php $rest=$newReport->getDeptReport($i,$year);
                                            $row1=mysqli_fetch_assoc($rest); ?> 
                                          
                                          <td><?= $row1['gm_male_old'] ?></td><td><?= $row1['gm_female_old']?></td>
                                          <td><?= $row1['gm_male_new'] ?></td><td><?= $row1['gm_female_new']?></td>
                                          <th><?= $gm=$row1['gm_male_new']+$row1['gm_female_new']+$row1['gm_male_old']+$row1['gm_female_old']?></th>
                                           
                                           
                                          
                                            
                                            <td><?= $row1['pd_male_old']?></td><td><?=$row1['pd_female_old']?></td>
                                            <td><?= $row1['pd_male_new']?></td><td><?=$row1['pd_female_new']?></td>
                                           <th><?= $pd=$row1['pd_male_new']+$row1['pd_female_new']+$row1['pd_male_old']+$row1['pd_female_old']?></th>
                                           
                                           
                                            <td><?= $row1['ps_male_old']?></td><td><?=$row1['ps_female_old']?></td>
                                            <td><?= $row1['ps_male_new']?></td><td><?=$row1['ps_female_new']?></td>
                                            <th><?= $ps=$row1['ps_male_new']+$row1['ps_female_new']+$row1['ps_male_old']+$row1['ps_female_old']?></th>
                                           
                                           
                                            <td><?= $row1['sk_male_old'] ?></td><td><?= $row1['sk_female_old']?></td>
                                            <td><?= $row1['sk_male_new'] ?></td><td><?= $row1['sk_female_new']?></td>
                                           <th><?= $sk=$row1['sk_male_new']+$row1['sk_female_new']+$row1['sk_male_old']+$row1['sk_female_old']?></th>
                                           
                                           
                                            <td><?= $row1['gs_male_old'] ?></td><td><?= $row1['gs_female_old']?></td>
                                            <td><?= $row1['gs_male_new'] ?></td><td><?= $row1['gs_female_new']?></td>
                                            <th><?= $gs=$row1['gs_male_new']+$row1['gs_female_new']+$row1['gs_male_old']+$row1['gs_female_old']?></th>
                                            
                                            
                                              <td><?= $row1['or_male_old'] ?></td><td><?= $row1['or_female_old']?></td>
                                            <td><?= $row1['or_male_new'] ?></td><td><?= $row1['or_female_new']?></td>
                                            <th><?= $or=$row1['or_male_new']+$row1['or_female_new']+$row1['or_male_old']+$row1['or_female_old'] ?></th>
                                            
                                              <td><?= $row1['dn_male_old'] ?></td><td><?= $row1['dn_female_old']?></td>
                                            <td><?= $row1['dn_male_new'] ?></td><td><?= $row1['dn_female_new']?></td>
                                            <th><?= $dn=$row1['dn_male_new']+$row1['dn_female_new']+$row1['dn_male_old']+$row1['dn_female_old'] ?></th>
                                            
                                              <td><?= $row1['op_male_old'] ?></td><td><?= $row1['op_female_old']?></td>
                                            <td><?= $row1['op_male_new'] ?></td><td><?= $row1['op_female_new']?></td>
                                            <th><?= $op=$row1['op_male_new']+$row1['op_female_new']+$row1['op_male_old']+$row1['op_female_old'] ?></th>
                                            
                                            <td><?= $row1['obg_male_old'] ?></td><td><?= $row1['obg_female_old']?></td>
                                            <td><?= $row1['obg_male_new'] ?></td><td><?= $row1['obg_female_new']?></td>
                                            <th><?= $obg=$row1['obg_male_old']+$row1['obg_female_old']+$row1['obg_male_new']+$row1['obg_female_new']?></th>
                                            
                                            <td><?= $row1['em_male_old'] ?></td><td><?= $row1['em_female_old']?></td>
                                            <td><?= $row1['em_male_new'] ?></td><td><?= $row1['em_female_new']?></td>
                                            <th><?= $em=$row1['em_male_new']+$row1['em_female_new']+$row1['em_male_old']+$row1['em_female_old']?></th>
                                           
                                           <td><?= $row1['en_male_old'] ?></td><td><?= $row1['en_female_old']?></td>
                                            <td><?= $row1['en_male_new'] ?></td><td><?= $row1['en_female_new']?></td>
                                            <th><?= $en=$row1['en_male_new']+$row1['en_female_new']+$row1['en_male_old']+$row1['en_female_old']?></th>
                                           
                                       
                                            <td><?= $row1['ph_male_old'] ?></td><td><?= $row1['ph_female_old']?></td>
                                            <td><?= $row1['ph_male_new'] ?></td><td><?= $row1['tb_female_new']?></td>
                                             <th><?= $ph=$row1['ph_male_new']+$row1['ph_female_new']+$row1['ph_male_old']+$row1['ph_female_old'] ?></th>
                                           
                                           
                                            
                                          
                                          
                                            <th><?= $gm+$pd+$ps+$sk+$tb+$gs+$or+$op+$obg+$em+$en+$dn+$ph?></th>
                                           </tr>
                                           <?php 
                       $total +=$gm+$pd+$ps+$sk+$tb+$gs+$or+$op+$obg+$em+$en+$dn+$ph;
                                           
                                           $gm_male_new +=$row1['gm_male_old'];
$gm_male_old +=$row1['gm_male_new'];
$gm_female_new +=$row1['gm_female_old'];
$gm_female_old +=$row1['gm_female_new'];
$pd_male_new +=$row1['pd_male_old'];
$pd_male_old +=$row1['pd_male_new'];
$pd_female_new +=$row1['pd_female_old'];
$pd_female_old +=$row1['pd_female_new'];
$ps_male_new +=$row1['ps_male_old'];
$ps_male_old +=$row1['ps_male_new'];
$ps_female_new +=$row1['ps_female_old'];
$ps_female_old +=$row1['ps_female_new'];
$sk_male_new +=$row1['sk_male_old'];
$sk_male_old +=$row1['sk_male_new'];
$sk_female_new +=$row1['sk_female_old'];
$sk_female_old +=$row1['sk_female_new'];
$gs_male_new +=$row1['gs_male_old'];
$gs_male_old +=$row1['gs_male_new'];
$gs_female_new +=$row1['gs_female_old'];
$gs_female_old +=$row1['gs_female_new'];
$or_male_new +=$row1['or_male_old'];
$or_male_old +=$row1['or_male_new'];
$or_female_new +=$row1['or_female_old'];
$or_female_old +=$row1['or_female_new'];
$op_male_new +=$row1['op_male_old'];
$op_male_old +=$row1['op_male_new'];
$op_female_new +=$row1['op_female_old'];
$op_female_old +=$row1['op_female_new'];
$obg_male_new +=$row1['obg_male_old'];
$obg_male_old +=$row1['obg_male_new'];
$obg_female_new +=$row1['obg_female_old'];
$obg_female_old +=$row1['obg_female_new'];
$em_male_new +=$row1['em_male_old'];
$em_male_old +=$row1['em_male_new'];
$em_female_new +=$row1['em_female_old'];
$em_female_old +=$row1['em_female_new'];
$en_male_new +=$row1['en_male_old'];
$en_male_old += $row1['en_male_new'];
$en_female_new +=$row1['en_female_old'];
$en_female_old += $row1['en_female_new'];
$dn_male_new +=$row1['dn_male_old'];
$dn_male_old +=$row1['dn_male_new'];
$dn_female_new +=$row1['dn_female_old'];
$dn_female_old +=$row1['dn_female_new'];
$ph_male_new +=$row1['ph_male_old'];
$ph_male_old +=$row1['ph_male_new'];
$ph_female_new +=$row1['ph_female_old'];
$ph_female_old +=$row1['ph_female_new'];
                                           
                                           
                                           
                                           
                                           
                                           } ?>
</tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>Total</th>
                                                   <th><?= $gm_male_new?></th>
<th><?= $gm_female_new?></th>
<th><?= $gm_male_old?></th>
<th><?= $gm_female_old?>
<th><?= $gm_male_new+$gm_male_old+$gm_female_new+$gm_female_old?></th>
<th><?= $pd_male_new?></th>
<th><?= $pd_female_new?></th>
<th><?= $pd_male_old?></th>
<th><?= $pd_female_old?></th>
<th><?= $pd_male_new+$pd_male_old+$pd_female_new+$pd_female_old?></th>
<th><?= $ps_male_new?></th>
<th><?= $ps_female_new?></th>
<th><?= $ps_male_old?></th>
<th><?= $ps_female_old?></th>
<th><?= $ps_male_new+$ps_male_old+$ps_female_new+$ps_female_old?></th>
<th><?= $sk_male_new?></th>
<th><?= $sk_female_new?></th>
<th><?= $sk_male_old?></th>
<th><?= $sk_female_old?></th>
<th><?= $sk_male_new+$sk_male_old+$sk_female_new+$sk_female_old?></th>
<th><?= $gs_male_new?></th>
<th><?= $gs_female_new?></th>
<th><?= $gs_male_old?></th>
<th><?= $gs_female_old?></th>
<th><?= $gs_male_new+$gs_male_old+$gs_female_new+$gs_female_old?></th>
<th><?= $or_male_new?></th>
<th><?= $or_female_new?></th>
<th><?= $or_male_old?></th>
<th><?= $or_female_old?></th>
<th><?= $or_male_new+$or_male_old+$or_female_new+$or_female_old?></th>
<th><?= $op_male_new?></th>
<th><?= $op_female_new?></th>
<th><?= $op_male_old?></th>
<th><?= $op_female_old?></th>
<th><?= $op_male_new+$op_male_old+$op_female_new+$op_female_old?></th>
<th><?= $obg_male_new?></th>
<th><?= $obg_female_new?></th>
<th><?= $obg_male_old?></th>
<th><?= $obg_female_old?></th>
<th><?= $obg_male_new+$obg_male_old+$obg_female_new+$obg_female_old?></th>
<th><?= $em_male_new?></th>
<th><?= $em_female_new?></th>
<th><?= $em_male_old?></th>
<th><?= $em_female_old?></th>
<th><?= $em_male_new+$em_male_old+$em_female_new+$em_female_old?></th>
<th><?= $en_male_new?></th>
<th><?= $en_female_new?></th>
<th><?= $en_male_old?></th>
<th><?= $en_female_old?></th>
<th><?= $en_male_new+$en_male_old+$en_female_new+$en_female_old?></th>
<th><?= $dn_male_new?></th>
<th><?= $dn_female_new?></th>
<th><?= $dn_male_old?></th>
<th><?= $dn_female_old?></th>
<th><?= $dn_male_new+$dn_male_old+$dn_female_new+$dn_female_old?></th>
<th><?= $ph_male_new?></th>
<th><?= $ph_female_new?></th>
<th><?= $ph_male_old?></th>
<th><?= $ph_female_old?></th>
<th><?= $ph_male_new+$ph_male_old+$ph_female_new+$ph_female_old?></th>

<th><?= $total?></th>
                                                </tr>
                                            </tfoot> 
                                            </table>
                    
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