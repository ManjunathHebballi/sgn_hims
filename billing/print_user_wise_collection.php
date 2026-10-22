<?php 

require_once(__DIR__ . '/../model/session_config.php');
include('model/billingClass.php');
$newBill = new addBilling;
$dept=$newBill->getBillListUserwise($_REQUEST['from'],$_REQUEST['to'],$_REQUEST['search'],$_REQUEST['payment_mode'],$_REQUEST['service']);
?>
    
    <!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">


<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">

    <title><?= $_REQUEST['type']?> BILL</title>


    <style>
        body{

            font-family: 'Poppins', sans-serif;
        }
        .table td, .table th{
            padding:0;
        }
        hr{
                margin-top: 1px;
                     margin-bottom: 0; 
                     /*border: 0; */
                    /*border-top: 1;*/
        }
          @page {
            margin: 0;
            size: A4; /* Set page size to A4 */
        }

        /*@media print {*/
            .watermark {
                position: absolute;
                font-size: 50px; /* Adjust font size as needed */
                color: rgba(0, 0, 0, 0.5); /* Set text color with reduced opacity */
                transform: rotate(-45deg) scaleX(2);
            }

            .top-left {
                top: 65%; /* Position the top-left watermark at 10% from the top */
                left: 30%;
               /* transform: rotate(-45deg); /* Rotate the text */
            }

            .bottom-right {
                bottom: 40%; /* Position the bottom-right watermark at 10% from the bottom */
                right: 40%;
               /* transform: rotate(-45deg); /* Rotate the text */
            }
        /*}*/
    </style>
  </head>



  <body>
   
                
                <div class="content-wrapper text-uppercase">
                        
                
           
<div class="container-fluid  p-0">


            <div class="card  border-0 p-0">
                <div class="card-body ">
                 
                <div class="container-fluid">

                   <div class="row">
                         <div class="col-sm-2">
                            <img src="assets/logo.png" class="" width="100" alt="">
                        </div>
     <div class="col-sm-8  align-items-center">
                        <h5 class="text-center text-black"><?= $_SESSION['college_society']?></h5>
                        <h3 class="text-center text-black"><?= $_SESSION['college_name']?></h3>
                        <p class="text-center text-black"><?= $_SESSION['college_location']?>,<?= $_SESSION['college_phone']?></p>
                        <h5 class="text-center text-black"></h5>

                        </div>
                        <div class="col-sm-2 ">
                              
                        </div>
</div>
<h4 class="text-center text-black"> User wise collection Report between <?= date("d.m.Y",strtotime($_REQUEST['from']))?> and <?= date("d.m.Y",strtotime($_REQUEST['to']))?>
                    
                    </h4>
                    </div>
<hr>

                                
                                
                                <table id="datatable-buttons4" class="table text-wrap text-left table-bordered">
                                    <thead>
                                           <tr>
                                                <th>Sl NO</th>
                                                <th>Name</th>
                                                <th>Collection Type</th>
                                                 <th>OP</th>
                                                <th>IP</th>
                                                <th>Pathology</th>
                                                <th>Radiology</th>
                                                <th>Dialysis</th>
                                                <th>Blood Bank</th>
                                                <th>Advance</th>
                                                <th>OP Refund</th>
                                                <th>IP Refund</th>
                                                <th>Total</th>
                                               
                                                
                                            </tr>
                                    </thead>
                                  <tbody class="text-center">
                                            <?php $sn=1;$total=0;$op=$ip=$patho=$blood=$radio=$dialysis=$advance=$cashop=$upiop=$chequeop=$caship=$upiip=$chequeip=$cashpt=$upipt=$chequept=$cashop_ret=$caship_ret=0; 
                                            $btop=$btip=$btpt=$btrd=$btdi=$btbl=$btad=$cardop=$cardip=$cardpt=$cardrd=$carddi=$cardbl=$cardad=0;
                                            // $claimop=$claimip=$claimpt=$claimrd=$claimdi=$claimbl=$claimad=0;
                                            $cashrd=$upird=$chequerd=$cashdi=$upidi=$chequedi=$cashbl=$upibl=$chequebl=$cashad=$upiad=$chequead=0;
                                            $user="";$op1=$ip1=$patho1=$blood1=$radio1=$dialysis1=$advance1=$op_ret1=$ip_ret1=0;
                                             while($res=mysqli_fetch_assoc($dept))
                                            { ?>
                                            <tr>
                                                <?php if($user!=$res['collected_by']) { 
                                                $total1=0;
                                                if($sn>1) { 
                                                  
                                                ?>
                                                
                                                    <th colspan=3 class="text-right">Total</th>
                                                    <th><?= $op1?></th>
                                                    <th><?= $ip1?></th>
                                                    <th><?= $patho1?></th>
                                                    <th><?= $radio1?></th>
                                                    <th><?= $dialysis1?></th>
                                                    <th><?= $blood1?></th>
                                                    <th><?= $advance1?></th>
                                                    <th><?= $op_ret1?></th>
                                                    <th><?= $ip_ret1?></th>
                                                    <th><?= $op1+$ip1 +$patho1 +$radio1 +$dialysis1+ $blood1 +$advance1-$op_ret1-$ip_ret1;?></th>
                                                </tr>
                                               <tr>
                                                    <?php } ?>
                                                <td><?= $sn?></td>
                                                <td><?= $res['collected_by']?></td>
                                                <?php $op1=$ip1=$patho1=$blood1=$radio1=$dialysis1=$advance1=$op_ret1=$ip_ret1=0;  $sn++;} else {?>
                                                <td colspan=2></td>
                                                <?php } ?>
                                                <td><?= $res['payment_mode']?></td>
                                                <td><?= $res['op_bill']?></td>
                                                <td><?= $res['ip_bill']?></td>
                                                <td><?= $res['patho_bill']?></td>
                                                <td><?= $res['radio_bill']?></td>
                                                <td><?= $res['dialysis_bill']?></td>
                                                <td><?= $res['blood_bill']?></td>
                                                <td><?= $res['advance']?></td>
                                                <td><?= $res['op_return']?></td>
                                                <td><?= $res['ip_return']?></td>
                                                <td><?= $res['blood_bill']+$res['dialysis_bill']+$res['radio_bill']+$res['op_bill']+$res['patho_bill']+$res['ip_bill']+$res['advance']-$res['op_return']-$res['ip_return']?></td>
                                            </tr>
                                           <?php
                                           $op1 +=$res['op_bill'];
                                            $ip1 +=$res['ip_bill'];
                                            $patho1 +=$res['patho_bill'];
                                            $radio1 +=$res['radio_bill'];
                                            $dialysis1 +=$res['dialysis_bill'];
                                            $blood1 +=$res['blood_bill'];
                                            $advance1 +=$res['advance'];
                                            $op +=$res['op_bill'];
                                            $ip +=$res['ip_bill'];
                                            $patho +=$res['patho_bill'];
                                            $radio +=$res['radio_bill'];
                                            $dialysis +=$res['dialysis_bill'];
                                            $blood +=$res['blood_bill'];
                                            $advance +=$res['advance'];
                                            $op_ret1 +=$res['op_return'];
                                            $ip_ret1 +=$res['ip_return'];
                                            $op_ret +=$res['op_return'];
                                            $ip_ret +=$res['ip_return'];
                                            $user=$res['collected_by'];
                                            if($res['payment_mode']=='Cash')
                                            {
                                                $cashop +=$res['op_bill'];
                                                $caship +=$res['ip_bill'];
                                                $cashpt +=$res['patho_bill'];
                                                $cashrd +=$res['radio_bill'];
                                                $cashdi +=$res['dialysis_bill'];
                                                $cashbl +=$res['blood_bill'];
                                                $cashad +=$res['advance'];
                                                $cashop_ret +=$res['op_return'];
                                                $caship_ret +=$res['ip_return'];
                                            }
                                            elseif($res['payment_mode']=='UPI')
                                            {
                                                $upiop +=$res['op_bill'];
                                                $upiip +=$res['ip_bill'];
                                                $upipt +=$res['patho_bill'];
                                                $upird +=$res['radio_bill'];
                                                $upidi +=$res['dialysis_bill'];
                                                $upibl +=$res['blood_bill'];
                                                $upiad +=$res['advance'];
                                            }
                                            elseif($res['payment_mode']=='Cheque'){
                                                $chequeop +=$res['op_bill'];
                                                $chequeip +=$res['ip_bill'];
                                                $chequept +=$res['patho_bill'];
                                                $chequerd +=$res['radio_bill'];
                                                $chequedi +=$res['dialysis_bill'];
                                                $chequebl +=$res['blood_bill'];
                                                $chequead +=$res['advance'];
                                            }
                                            elseif($res['payment_mode']=='Bank Transfer'){
                                                $btop +=$res['op_bill'];
                                                $btip +=$res['ip_bill'];
                                                $btpt +=$res['patho_bill'];
                                                $btrd +=$res['radio_bill'];
                                                $btdi +=$res['dialysis_bill'];
                                                $btbl +=$res['blood_bill'];
                                                $btad +=$res['advance'];
                                            }
                                            elseif($res['payment_mode']=='Card'){
                                                $cardop +=$res['op_bill'];
                                                $cardip +=$res['ip_bill'];
                                                $cardpt +=$res['patho_bill'];
                                                $cardrd +=$res['radio_bill'];
                                                $carddi +=$res['dialysis_bill'];
                                                $cardbl +=$res['blood_bill'];
                                                $cardad +=$res['advance'];
                                            }
                                            elseif($res['payment_mode']=='credit'){
                                                $creditop +=$res['op_bill'];
                                                $creditip +=$res['ip_bill'];
                                                $creditpt +=$res['patho_bill'];
                                                $creditrd +=$res['radio_bill'];
                                                $creditdi +=$res['dialysis_bill'];
                                                $creditbl +=$res['blood_bill'];
                                                $creditad +=$res['advance'];
                                            }
                                            else{
                                                
                                            }
                                            // $sn++;
                                          } 
                                           
                                          ?>
                                          <tr>
                                                    <th colspan=3 class="text-right">Total</th>
                                                    <th><?= $op1?></th>
                                                    <th><?= $ip1?></th>
                                                    <th><?= $patho1?></th>
                                                    <th><?= $radio1?></th>
                                                    <th><?= $dialysis1?></th>
                                                    <th><?= $blood1?></th>
                                                    <th><?= $advance1?></th>
                                                    <th><?= $op_ret1?></th>
                                                    <th><?= $ip_ret1?></th>
                                                    <th><?= $op1+$ip1 +$patho1 +$radio1 +$dialysis1+ $blood1 +$advance1-$op_ret1-$ip_ret1;?></th>
                                                </tr>
                                            <tr>
                                                <td colspan=2 rowspan=7 class="text-center">All Users Total</td>
                                                <td>Cash</td>
                                                <td><?= $cashop?></td>
                                                <td><?= $caship?></td>
                                                <td><?= $cashpt?></td>
                                                <td><?= $cashrd?></td>
                                                <td><?= $cashdi?></td>
                                                <td><?= $cashbl?></td>
                                                <td><?= $cashad?></td>
                                                <td><?= $cashop_ret?></td>
                                                <td><?= $caship_ret?></td>
                                                <td><?= $cashop+$caship +$cashpt +$cashrd +$cashdi+ $cashbl +$cashad-$cashop_ret-$caship_ret;?></td>
                                            </tr>
                                             <tr>
                                                
                                                <td>UPI</td>
                                                <td><?= $upiop?></td>
                                                <td><?= $upiip?></td>
                                                <td><?= $upipt?></td>
                                                <td><?= $upird?></td>
                                                <td><?= $upidi?></td>
                                                <td><?= $upibl?></td>
                                                <td><?= $upiad?></td>
                                                <td>0</td>
                                                <td>0</td>
                                                <td><?= $upiop+$upiip +$upipt +$upird +$upidi+ $upibl +$upiad;?></td>
                                            </tr>
                                            <tr>
                                                
                                                <td>Cheque</td>
                                                <td><?= $chequeop?></td>
                                                <td><?= $chequeip?></td>
                                                <td><?= $chequept?></td>
                                                <td><?= $chequerd?></td>
                                                <td><?= $chequedi?></td>
                                                <td><?= $chequebl?></td>
                                                <td><?= $chequead?></td>
                                                <td>0</td>
                                                <td>0</td>
                                                <td><?= $chequeop+$chequeip +$chequept +$chequerd +$chequedi+ $chequebl +$chequead;?></td>
                                            </tr>
                                            <tr>
                                                
                                                <td>Bank Transfer</td>
                                                <td><?= $btop?></td>
                                                <td><?= $btip?></td>
                                                <td><?= $btpt?></td>
                                                <td><?= $btrd?></td>
                                                <td><?= $btdi?></td>
                                                <td><?= $btbl?></td>
                                                <td><?= $btad?></td>
                                                <td>0</td>
                                                <td>0</td>
                                                <td><?= $btop+$btip +$btpt +$btrd +$btdi+ $btbl +$btad;?></td>
                                            </tr>
                                            <tr>
                                                
                                                <td>Card</td>
                                                <td><?= $cardop?></td>
                                                <td><?= $cardip?></td>
                                                <td><?= $cardpt?></td>
                                                <td><?= $cardrd?></td>
                                                <td><?= $carddi?></td>
                                                <td><?= $cardbl?></td>
                                                <td><?= $cardad?></td>
                                                <td>0</td>
                                                <td>0</td>
                                                <td><?= $cardop+$cardip +$cardpt +$cardrd +$carddi+ $cardbl +$cardad;?></td>
                                            </tr>
                                            <tr>
                                                
                                                <td>Credit</td>
                                                <td><?= $creditop?></td>
                                                <td><?= $creditip?></td>
                                                <td><?= $creditpt?></td>
                                                <td><?= $creditrd?></td>
                                                <td><?= $creditdi?></td>
                                                <td><?= $creditbl?></td>
                                                <td><?= $creditad?></td>
                                                <td>0</td>
                                                <td>0</td>
                                                <td><?= $creditop+$creditip +$creditpt +$creditrd +$creditdi+ $creditbl +$creditad;?></td>
                                            </tr>
                                            <!--<tr>-->
                                                
                                            <!--    <td>Claim</td>-->
                                            <!--    <td><?= $claimop?></td>-->
                                            <!--    <td><?= $claimip?></td>-->
                                            <!--    <td><?= $claimpt?></td>-->
                                            <!--    <td><?= $claimrd?></td>-->
                                            <!--    <td><?= $claimdi?></td>-->
                                            <!--    <td><?= $claimbl?></td>-->
                                            <!--    <td><?= $claimad?></td>-->
                                            <!--    <td>0</td>-->
                                            <!--    <td>0</td>-->
                                            <!--    <td><?= $claimop+$claimip +$claimpt +$claimrd +$claimdi+ $claimbl +$claimad;?></td>-->
                                            <!--</tr>-->
                                            <tr>
                                                
                                                <th>Total</th>
                                                <th><?= $op?></th>
                                                <th><?= $ip?></th>
                                                <th><?= $patho?></th>
                                                <th><?= $radio?></th>
                                                <th><?= $dialysis?></th>
                                                <th><?= $blood?></th>
                                                <th><?= $advance?></th>
                                                <th><?= $op_ret?></th>
                                                <th><?= $ip_ret?></th>
                                                <th><?= $op+$ip +$patho +$radio +$dialysis+ $blood +$advance-$op_ret-$ip_ret;?></th>
                                            </tr>
                                            </tbody>  </table>
                                           
                                                <!-- <table class="table text-wrap text-left table-bordered mt-4">
                                                    <tr>
                                                <th colspan="8" class="text-center">Total Billing </th>
                                            </tr>
                                                <th>Service Type</th>
                                                <th class="text-center">2000</th>
                                                <th class="text-center" >Gross Amount</th>
                                                <th class="text-center" >Discount</th>
                                                <th class="text-center" >Net Amount</th>
                                               <th class="text-center" >Paid Amount</th>
                                               <th class="text-center" >Balance</th>
                                            </tr>
                                            <?php $data=$newBill->billing_summary($_REQUEST['from'],$_REQUEST['to']);
                                            while($row1=mysqli_fetch_assoc($data))
                                            {
                                            ?>
                                            <tr>
                                                <td  class="text-center"><?= $row1['service_type']?></td>
                                                <td  class="text-center"><?= $row1['amount']?></td>
                                                <td  class="text-center"><?= $row1['discount']?></td>
                                                <td  class="text-center"><?= $row1['amount']-$row1['discount']?></td>

                                                <td  class="text-center"><?php if($row1['service_type']=='Pathology') echo $total=$patho-$creditpt; elseif($row1['service_type']=='OP')echo $total=$op-$creditop;
                                                elseif($row1['service_type']=='IP')echo $total=$ip-$creditip;elseif($row1['service_type']=='Radiology')echo $total=$radio-$creditrd;
                                                elseif($row1['service_type']=='Blood Bank')echo $total=$blood-$creditbl;elseif($row1['service_type']=='Dialysis')echo $total=$dialysis-$creditdi;?></td>
                                                <td  class="text-center"><?= $row1['amount']-$row1['discount']-$total?></td>
                                            </tr>
                                             <?php } ?>
                                                </table>
                                           -->
                     
                                                <table class="table table-bordered mt-4">
                                                    <tr>
                                                <th colspan="12" class="text-center">Currency Denomination </th>
                                            </tr>
                                                <th>Denomination</th>
                                               
                                                <th class="text-center">500</th>
                                                <th class="text-center">200</th>
                                                <th class="text-center">100</th>
                                                <th class="text-center">50</th>
                                                <th class="text-center">20</th>
                                                <th class="text-center">10</th>
                                                <th class="text-center">5</th>
                                                <th class="text-center">2</th>
                                                <th class="text-center">1</th>
                                                <th class="text-center">Coins</th>
                                            </tr>
                                            <tr>
                                                <th>Counts</th>
                                                  
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                             <tr>
                                                <th>Total</th>
                                                 
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                                </table>
                                            </tr>
                                            </tbody>  </table>
                                            </div>
                                            
           </div>
            </div>
<button class="btn btn-primary mb-4 mr-2 float-right d-print-none" onclick="window.print()"> Print</button>
<a href="user_wise_collection.php" class="btn btn-danger mb-4 mr-2 float-right d-print-none"> Back</a>

            </div>


                </div>
                

<script>
    window.addEventListener('afterprint', function () {
        // Close the window after printing
        window.close();
      }, { once: true });
      function changePrice(val,id,num)
      {
          document.getElementById('tot_'+id).innerHTML=val*num;
      }
</script>
               
                <!-- content-wrapper ends -->

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js" integrity="sha384-VHvPCCyXqtD5DqJeNxl2dtTyhF78xXNXdkwX1CZeRusQfRKp+tA7hAShOK/B/fQ2" crossorigin="anonymous"></script>
    -->
  </body>
</html>
    