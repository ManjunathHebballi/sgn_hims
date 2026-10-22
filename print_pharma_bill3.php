<?php
require_once(__DIR__ . '/model/session_config.php');// include('dbconnection.php');
include("model/medicineClass.php");
$newMedicine= new medicine();
$data=$newMedicine->getwalkinpatient($_REQUEST['id'],$_REQUEST['inv']);
$row1=mysqli_fetch_assoc($data);
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

    <title>PHARMACY RECEIPT</title>


    <style>
        body{

            font-family: 'Poppins', sans-serif;
        }

        
    </style>
  </head>



  <body onload="window.print()">
   
                
                <div class="content-wrapper ">
                        
                
           
<div class="container-fluid  p-0">


           
                <div class="container-fluid">

<div class="row">

    <div class="col-md-12  align-items-center">
                        
                        <h3 class="text-center text-black"><?= $row1['pharma_name']?></h3>
                        <p class="text-center text-black"><?= $row1['address1'].'<br>'.$row1['address2']?>. Ph: <?= $row1['phone_no']?>.</p>
                      

                        </div>
                       
</div>
 <div class="d-flex justify-content-between">
                            
                     
                              <p>D. L. No. :<?=$row1['dl_no'] ?></p>  
                        <p>FSSAI No. :<?=$row1['fssai'] ?></p>
                        <p>GST NO. :<?= $row1['gst_no']?></p>
                        </div>
                        
                       
 <hr>
<h4 class="text-center"><u> PHARMACY RECEIPT</u></h4>
    <table class="table table-borderless" >
 
        <tr>
              <th class="text-right mr-3">Patient NAME : &nbsp;&nbsp;&nbsp;</th><td align="left" >  <?php echo $row1['patientname'];?></td>
            <th class="text-right mr-3"> Mobile No. : &nbsp;&nbsp;&nbsp; </th> <td align="left"><?php echo $row1['mob_no'];?></td>
            <th class="text-right mr-3"> Invoice No. : &nbsp;&nbsp;&nbsp; </th> <td align="left"><?php echo $row1['bill_no'];?></td>
        </tr>
        <!-- <hr> -->
        <tr>
          <th class="text-right mr-3">Dr. Name : &nbsp;&nbsp;&nbsp;</th><td align="left"><?php echo $row1['doctor'];?></td>
            <th class="text-right">Payment Mode :&nbsp;&nbsp;&nbsp; </th><td align="left" ><?php echo $row1['payment_mode'];?></td>
      
            <th  class="text-right mr-3">Date : &nbsp;&nbsp;&nbsp;</th><td align="left" > <?php echo date('d-m-Y', strtotime($row1['date']));?></td>
            
        </tr>

    </table>

</div>


 <div class="container-fluid">

<div class="row">

       
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Sl. No.</th>
                        <th>Batch</th>
                        <th>Particulars</th>
                        <th>Expire Date</th>
                        <!--<th>Dosage</th>-->
                        <th>MRP</th>
                        <th>Qty</th>
                         <th>GST %</th> 
                        <th class="text-right">Total</th>
                    </tr>

                </thead>

                <tbody>
                    <?php $result=$newMedicine->getWalkinPharmaBillP($_REQUEST['id'],$_REQUEST['inv']);
                        $sn=1;
                        $total=0;
                        $d=0;
                       
                while ($row=mysqli_fetch_assoc($result))
                { 
                $d +=$row['discount_amt'];
                // $gm=$row['grand_amt'];

                ?>
                    <tr>
                        <td><?php echo $sn; ?></td>
                        
                        
                        <td><?php echo $row['batch']; ?></td>
                        <td><?php echo $row['mname']; ?></td>
                        <td><?php echo date("m/Y",strtotime($row['exp_date'])); ?></td>
                        <td><?php echo $row['price']; ?></td>
                        <td><?php echo $row['quantity']; ?></td>
                         <td><?php echo $row['tax']; ?></td> 
                        <td class="text-right"><?php echo round($sum=$row['quantity']*$row['price'],2); ?></td>


                    </tr><?php $sn++; 
                     $total +=$sum;
                     
                    
                    
                     } 
                    
                    $bill_amt=$total; ?>

                </tbody>

              <!--   <tfoot>
                    <tr>

                        <th colspan="4"></th>
                        <th>Tax</th>
                       
                        <th>GST 0% : 0.00</th>
                    </tr>
                </tfoot> -->
            </table>
        </div>
    </div>
    <br>
    <div class="container-fluid">
        <div class="row">

            <div class="col-sm-8">
                 
                <div class="row">
                    <p>Goods once Sold Cannot be taken back or Exchanges. Prices Changes are inclusive of taxes &    duties paid. Drugs (price control order 1970) any excess collected by oversight, will be refunded.</p>
                </div>
            </div>
            <br><br>
            <div class="col-sm-4"><strong>
                <div class="row">
                    <div class="col-sm-8 float-right"><p class="float-right">Bill Amt. :</p></div>
                    <div class="col-sm-4 float-left"><p class="float-right"><?php echo round($total,2); ?></p></div>
                                                                                        
                </div>
                <div class="row">
                    <div class="col-sm-8 float-right"><p class="float-right">Discount Amt. :</p></div>
                    <div class="col-sm-4 float-left"><p class="float-right"><?php echo $d; ?></p></div>
                
                </div>
                
                <div class="row">
                    <div class="col-sm-8 float-right"><p class="float-right">NET Amt. :</p></div>
                    <div class="col-sm-4 float-left"><p class="float-right"><?php echo round($total-$d,2); ?></p></div>
                
                </div>
                <div class="row">
                    <div class="col-sm-8 float-right"><p class="float-right">Billed By</p></div>
                    <div class="col-sm-4 float-left"><p class="float-right"><?php echo $row1['collected_by']; ?></p></div>
                
                </div>
            </strong></div>
        </div>
    </div>


<button class="btn btn-danger mb-4 float-right d-print-none" onclick="window.print()"> Print</button>

            </div>


                </div>
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
    