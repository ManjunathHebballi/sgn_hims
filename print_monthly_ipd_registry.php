<?php 
date_default_timezone_set("Asia/Kolkata"); 
require_once(__DIR__ . '/model/session_config.php');
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
                            <img src="assets/logo.png" class="" alt="" width='150'>

                        
                        <div class=" ml-5">
                        <h5 class="text-center text-black"><?= $_SESSION['college_society']?></h5>
                        <h3 class="text-center text-black"><?= $_SESSION['college_name']?></h3>
                        <h5 class="text-center text-black"><?= $_SESSION['college_location']?></h5>
                        <h5 class="text-center text-black"><?= $_SESSION['college_phone']?></h5>

                        </div>
                   </div>


                    
                

          
        </div>

                    <hr/>
                    <h4 class="text-center text-black mb-3 mt-5">Monthly IPD Registry Report
                       
                    </h4>

                              
                    <table class="table table-bordered text-center  text-wrap table-hover">
                                        <thead>
                                            <tr>
                                            <th rowspan="2">Month</th>
                                            <th colspan="2">ICU</th>
                                            <?php
                                            $count=1;
                                            $res=$newReport->getIPDepartment();
                                            while($row=mysqli_fetch_assoc($res))
                                            {
                                            ?>
                                            <th colspan="2"><?= $row['dept_name']?></th>
                                            <?php
                                            $count++;
                                            } ?>
                                             <th rowspan="2">Total <br>Admission</th>
                                              <th rowspan="2">Total <br>Discharge</th>
                                            </tr>
                                            <tr>
                                              <?php for($i=0;$i<$count;$i++) { ?>  
                                                <th>  Admission </th>
                                              <th>  Discharge </th>
                                              <!--<th>  Remaining </th>-->
                                              
                                              <?php } ?>
                                             
                                              </tr>
                                         
                                        </thead>
                                        <tbody class=" text-black">
                                           <?php 
                                           $gma=$gmd=$pda=$pdd=$pya=$pyd=$ska=$skd=$gsa=$gsd=$ora=$ord=$dna=$dnd=$ana=$and=$opa=$opd=$gya=$gyd=$ema=$emd=$ena=$end=$icua=$icud=0;
                                            if($year==date("Y"))
                                           {
                                               $month=date('n');
                                           }
                                           else{
                                               $month=12;
                                           }
                                           for($i=1;$i<=$month;$i++) {
                                           $totadm=$totdis=0; ?>
                                           <tr>
                                            <th><?= date("M",mktime(0,0,0,$i+1,0,0))?></th>
                                            <?php $rest=$newReport->getDeptIPReport($i,$year);
                                            $sn=1;
                                            while($row1=mysqli_fetch_assoc($rest)){
                                            $totadm +=$row1['occupied'];
$totdis +=$row1['discharge'];?> 
                                          <td><?= $row1['occupied']?></td>
                                            
                                             <td><?= $row1['discharge']?></td>
                                         
                                           <?php 
                                           
                                       if($sn==1)
                                       {
                                       $icua += $row1['occupied'];
                                       $icud += $row1['discharge'];
                                       }    
                                       if($sn==2)
                                       {
                                       $gma += $row1['occupied'];
                                       $gmd += $row1['discharge'];
                                       }
                                       if($sn==3)
                                       {
                                       $pda += $row1['occupied'];
                                       $pdd += $row1['discharge'];
                                       }
                                       if($sn==4)
                                       {
                                       $pya += $row1['occupied'];
                                       $pyd += $row1['discharge'];
                                       }
                                       if($sn==5)
                                       {
                                       $ska += $row1['occupied'];
                                       $skd += $row1['discharge'];
                                       }
                                       if($sn==6)
                                       {
                                       $gsa += $row1['occupied'];
                                       $gsd += $row1['discharge'];
                                       }
                                       if($sn==7)
                                       {
                                       $ora += $row1['occupied'];
                                       $ord += $row1['discharge'];
                                       }
                                       
                                       if($sn==8)
                                       {
                                       $opa += $row1['occupied'];
                                       $opd += $row1['discharge'];
                                       }
                                       if($sn==9)
                                       {
                                       $gya += $row1['occupied'];
                                       $gyd += $row1['discharge'];
                                       }
                                      
                                       if($sn==10)
                                       {
                                       $ena += $row1['occupied'];
                                       $end += $row1['discharge'];
                                       }
                                           
                                           $sn++;
                                           } ?>
                                           
                                           <td ><?= $totadm?></td>
                                           <td><?= $totdis?></td>
                                          
                                            
                                            
                                            
                                           </tr>
                                           <?php } ?>
</tbody>
                                             <tfoot>
                                                <tr>
                                                    <th>Total</th>
                                                  <th><?= $icua ?></th>
<th><?= $icud ?></th>
<th><?= $gma ?></th>
<th><?= $gmd ?></th>
<th><?= $pda ?></th>
<th><?= $pdd ?></th>
<th><?= $pya ?></th>
<th><?= $pyd ?></th>
<th><?= $ska ?></th>
<th><?= $skd ?></th>
<th><?= $gsa ?></th>
<th><?= $gsd ?></th>
<th><?= $ora ?></th>
<th><?= $ord ?></th>
<th><?= $opa ?></th>
<th><?= $opd ?></th>
<th><?= $gya ?></th>
<th><?= $gyd ?></th>
<th><?= $ena ?></th>
<th><?= $end ?></th>
<th><?= $gma+$pda+$pya+$ska+$gsa+$ora+$dna+$ana+$opa+$gya+$ema+$ena;?></th>
<th><?= $gmd+$pdd+$pyd+$skd+$gsd+$ord+$dnd+$and+$opd+$gyd+$emd+$end;?></th>
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