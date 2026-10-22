<?php 

require_once(__DIR__ . '/../model/session_config.php');
include('model/billingClass.php');
$newBill = new addBilling;
$dept=$newBill->getBillListUserwiseDetailed($_REQUEST['from'],$_REQUEST['to'],$_REQUEST['search'],$_REQUEST['payment_mode'],$_REQUEST['service']);
$pay=$_REQUEST['payment_mode'];
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

                                
                                
                                <table id="datatable-buttons4" class="table text-wrap text-center table-bordered">
                                    <thead>
                                           <tr>
                                                 <th>Sl NO</th>
                                                <th>Date</th>
                                                <th>Bill NO</th>
                                                <th>Patient Reg No</th>
                                                <th>Name</th>
                                                <th>Gender</th>
                                                <th>Remark</th>
                                                <?php if($pay=='Cash' || $pay=='') 
                                        {?>
                                        <th>Cash</th>
                                        <?php } if($pay=='UPI' || $pay=='') 
                                        {
                                        ?>
                                         <th>UPI</th>
                                                <?php } if($pay=='Cheque' || $pay=='') 
                                        {
                                        ?>
                                                <th>Cheque</th>
                                                <?php } if($pay=='Card' || $pay=='') 
                                        {
                                        ?>
                                                <th>Card</th>
                                                <?php } if($pay=='Credit' || $pay=='') 
                                        {
                                        ?>
                                                <th>Credit</th>
                                                <?php } if($pay=='Bank Transfer' || $pay=='') 
                                        {
                                        ?>
                                                <th>Bank</th>
                                                <?php }?>
                                                <th>Bill Amount</th>
                                                
                                            </tr>
                                    </thead>
                                  <tbody class="text-left">
                                            <?php $cash=$upi=$cheque=$total=$credit=$bank=$card=0;$sn=1;
                                             while($res=mysqli_fetch_assoc($dept))
                                            { $total1=0;if($col!=$res['collected_by']) { echo "<tr><th colspan=11 class='ml-2'>$res[collected_by]</th></tr>";}?>
                                        <tr><td><?= $sn?></td>
                                        <td><?= date("d.m.Y h:i:s A", strtotime($res['date']))?></td>
                                        <td><?= $res['bill_no']?></td>
                                        <td><?= $res['opd']?></td>
                                        <td><?= $res['name']?></td>
                                        <td><?= $res['gender']?></td>
                                        <td><?= $res['payment_note']?></td>
                                        <?php if($pay=='Cash' || $pay=='') 
                                        {  $cash +=$res['cash'];
                                        $total1 +=$res['cash'];?>
                                        <td><?= $res['cash']?></td>
                                        <?php } if($pay=='UPI' || $pay=='') 
                                        {  $upi +=$res['upi'];
                                         $total1 +=$res['upi'];?>
                                        <td><?= $res['upi']?></td>
                                        <?php } if($pay=='Cheque' || $pay=='') 
                                        { $cheque +=$res['cheque'];
                                         $total1 +=$res['cheque'];?>
                                        <td><?= $res['cheque']?></td>
                                        <?php } if($pay=='Card' || $pay=='') 
                                        { $card +=$res['card'];
                                         $total1 +=$res['card'];?>
                                        <td><?= $res['card']?></td>
                                        <?php } if($pay=='Credit' || $pay=='') 
                                        {  $credit +=$res['credit'];
                                         $total1 +=$res['credit'];?>
                                        <td><?= $res['credit']?></td>
                                        <?php } if($pay=='Bank Transfer' || $pay=='') 
                                        {  $bank +=$res['bank'];
                                         $total1 +=$res['bank'];?>
                                        <td><?= $res['bank']?></td>
                                        <?php }?>
                                        <td><?= $total1?></td>
                                        </tr>
                                          <?php 
                                            
                                            
                                            
                                            $total +=$total1;
                                         
                                          
                                          $col=$res['collected_by'];
                                          $sn++; } ?>
                                         <tr >
                                             <th colspan=7 class="text-right"  style="padding: 8px;">Total</th>
                                            <?php if($pay=='Cash' || $pay=='') 
                                        {
                                        ?><th style="padding: 8px;"><?= $cash ?></th>
                                        <?php } if($pay=='UPI' || $pay=='') 
                                        {
                                        ?>
                                            <th style="padding: 8px;"><?= $upi ?></th>
                                           <?php } if($pay=='Cheque' || $pay=='') 
                                        {
                                        ?> 
                                            <th style="padding: 8px;"><?= $cheque ?></th>
                                            <?php } if($pay=='Card' || $pay=='') 
                                        {
                                        ?>
                                            <th style="padding: 8px;"><?= $card ?></th>
                                            <?php } if($pay=='Credit' || $pay=='') 
                                        {
                                        ?>
                                            <th style="padding: 8px;"><?= $credit ?></th>
                                            <?php } if($pay=='Bank Transfer' || $pay=='') 
                                        {
                                        ?>
                                            <th style="padding: 8px;"><?= $bank ?></th>
                                            <?php }?>
                                             
                                             <th  style="padding: 8px;"><?= $total?></th>
                                         </tr>
                                           <tr>
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
    