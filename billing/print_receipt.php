<?php 

require_once(__DIR__ . '/../model/session_config.php');
include('model/billingClass.php');
$newBill = new addBilling;
$result=$newBill->getPayment($_REQUEST['bill_id']);
                    $sn=1;
                $row=mysqli_fetch_assoc($result)
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



  <body onload="window.print()">
   
                
                <div class="content-wrapper ">
                        
                
           
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
                        
</div>
<hr>
<h5 class="text-center text-black mt-2" style="text-transform: uppercase;"><?= $_REQUEST['type']?> Payment Receipt</h5>
 <hr>
<!--<h4 class="text-center"><u> IP Bill RECEIPT</u></h4>-->


    
    <table class="table table-borderless text-uppercase">
         <?php 
         if($_REQUEST['type']=='IP')
         {
             $result=$newBill->getIPPatient($_REQUEST['id'],$_REQUEST['bill_no']);
         }
         else{
             $result=$newBill->getPatient($_REQUEST['id'],$_REQUEST['bill_no']);
         }
                $row1=mysqli_fetch_assoc($result); 
                ?>
         <tr>
             <?php 
         if($_REQUEST['type']=='IP')
         {
             echo '<th width="15%">IPD No</th><td align="left" width="35%">'.    $row1['ipd_no'].'</td>';
         }else {
             echo '<th width="15%">OPD No</th><td align="left" width="35%">'.    $row1['opd_no'].'</td>';
             } ?>
              <th width="20%"> Date:</th> <td align="left" width="35%"><?php echo date('d-m-Y h:i:s',strtotime($row['date'])); ?></td>
         </tr>  
         <tr>
             <th width="15%">Patient NAME:</th><td align="left" width="35%">  <?= $row1['name']. '['.$row1['age'].' / '.$row1['gender'].']'?></td>
            <th width="15%">Bill No</th><td align="left" width="35%"> <?php  echo $row1['bill_no']; ?></td>
          
        </tr>
        <tr>
            <th width="15%">Address: </th><td align="left" width="35%"><?php echo $row1['address'];?></td>
            <th width="15%">Dr Name:</th><td align="left" width="35%"><?php echo $row1['doc']; ?></td>
            
        </tr>
        <!-- <hr> -->
        <tr>
            <th  width="15%">Department</th><td align="left" width="35%"><?php echo $row1['dept_name'];?></td>
            <th  width="15%">Receipt No</th><td align="left" width="35%"><span id="receipt"></span></td>
        </tr>
       
        

    </table>

</div>


 <div class="container-fluid mt-3">

<div class="row">

       <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">Sl. No.</th>
                        <th class="text-center">Description</th>
                        <th class="text-center">Note</th>
                        <th class="text-center">Amount</th>
                    </tr>

                </thead>

                <tbody>
                    <?php 
                
               


                ?>
                    <tr  height="150vh" class="align">
                        <td class="text-center"><?php echo $sn; ?></td>
                        <td class="text-left pl-1">Payment Received By :  <?= $row['payment_mode'];?></td>
                         <td class="text-center"><?php echo $row['payment_note']; ?></td>
                        <td class="text-center"><?php echo $row['amount']; ?></td>
                       
                        
                    </tr>
                

                

                              <tr>
                    <td colspan="3" class="text-right" id="total">Collected By</td><td class="text-center"> <?= $row['collected_by']?></td>
                    </tr>
                  
                    </tbody>
                    
            </table>
           
        </div>
    </div>
   
   


                </div>
            </div>
<button class="btn btn-info mb-4 float-right d-print-none ml-3" onclick="window.print()"> Print</button>
<button class="btn btn-danger mb-4 float-right d-print-none" onclick="window.history.back()"> Go Back</button>

            </div>


                </div>
                
<script>document.getElementById('receipt').innerHTML='<?= $row['bill_receipt']?>';</script>
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
    