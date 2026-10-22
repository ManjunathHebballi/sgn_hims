<?php 
require_once(__DIR__ . '/../model/session_config.php');
include('model/billingClass.php');
$newBill = new addBilling;

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

    <title>IP BILL</title>


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
            margin: 10mm;
            size: A4;
        }

        @media print {
            table {
                page-break-inside: auto;
            }
            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
            thead {
                display: table-header-group;
            }
            tfoot {
                display: table-footer-group;
            }
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



  <body class="text-uppercase">
   
                
                <div class="content-wrapper ">
                        
                
           
<div class="container-fluid  p-0">


            <div class="card  border-0 p-0">
                <div class="card-body ">
                    
                <div class="container-fluid">

                   <div class="row">
                         <div class="col-sm-2 ">
                            <img src="assets/logo.png" class="" width="100" alt="">
                        </div>
    <div class="col-sm-8  align-items-center">
                        <h3 class="text-center text-black"><?= $_SESSION['college_name']?></h3>
                        <h6 class="text-center text-black"><?= $_SESSION['college_location']?> , Cell : <?= $_SESSION['college_phone']?></h6>
                        
                        </div>
                       
</div>
<h5 class="text-center mt-2 text-black" style="text-transform: uppercase;"><?= $_REQUEST['type']?> Bill Receipt</h5>

 <hr>
<!--<h4 class="text-center"><u> IP Bill RECEIPT</u></h4>-->


    
    <table class="table table-borderless">
         <?php $result=$newBill->getCalimPatientBill($_REQUEST['id'],$_REQUEST['bill_no']);

                $row1=mysqli_fetch_assoc($result); 
                ?>
         <tr>
             <th width="15%">IPD No</th><td align="left" width="10%"> <?php  echo $row1['ipd_no']; ?></td>
            
              <th width="20%"> Date:</th> <td align="left" width="35%"><?php echo date('d-m-Y h:i:s A',strtotime($row1['date'])); ?></td>
         </tr>  
         <tr>
             <th width="15%">Patient NAME:</th><td align="left" width="35%">  <?= $row1['name'].' ' .$row1['parent_name'] . '['.$row1['age'].' / '.$row1['gender'].']'?></td>
            <th width="15%">Bill No</th><td align="left" width="35%"> <?php  echo $row1['bill_no']; ?></td>
          
        </tr>
        
         <tr>
             <th width="15%">Admission Date:</th><td align="left" width="35%">  <?= date('d-m-Y h:i:s A',strtotime($row1['admission_date']))?></td>
             
            <th width="15%">Discharge Date</th><td align="left" width="35%"> <?php if($row1['discharge']=='1' ) { echo  date('d-m-Y h:i:s A',strtotime($row1['discharge_date'])); } ?></td>
          
        </tr>
        
        
        <tr>
            <th width="15%">Address: </th><td align="left" width="35%"><?php echo $row1['address'];?></td>
            <th width="15%">Dr Name:</th><td align="left" width="35%"><?php echo $row1['doctor']; ?></td>
            
        </tr>
        <!-- <hr> -->
        <tr>
            <th  width="15%">Department</th><td align="left" width="35%"><?php echo $row1['dept_name'];?></td>
            
            <th  width="15%">Bed No</th><td align="left" width="35%"><?php echo $row1['bed_number'].' ['.$row1['ward_type'].']';?></td>
        </tr>
                <tr>
            <th  width="15%">Scheme</th><td align="left" width="35%"><?= $row1['scheme']?></td>
            
            <th width="15%">DIH</th><td align="left" width="35%"><?php 
$admission = DateTime::createFromFormat('Y-m-d H:i:s', $row1['admission_date']);
$discharge = DateTime::createFromFormat('Y-m-d H:i:s', $row1['discharge_date']);

$diff = $admission->diff($discharge);

// Base days
$days = $diff->days;

// If more than 1 hour (or 1 hour with minutes), count as next day
if ($diff->h > 1 || ($diff->h == 1 && $diff->i > 0)) {
    $days++;
}
echo $days." days";?></td>
        </tr>
    </table>

</div>


 <div class="container-fluid mt-3">

<div class="row">

       <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">Sl. No.</th>
                        <th class="text-center">Service Name</th>
                        <th class="text-center">Unit</th>
                        <th class="text-center">Rate</th>
                        <th class="text-center">Gross Amount</th>
                        <th class="text-center">Service Level Discount</th>
                        <th class="text-center">Net Amount</th>
                    </tr>

                </thead>

                <tbody>
                    <?php $result=$newBill->getClaimBill($_REQUEST['id'],$row1['bill_no']);
                        $gross=0;
                        $disc=0;
                        $net=0;
                        $sn=1;
                        $collected='';
                        $disc_amt=0;
                        $adv=0;
                while ($row=mysqli_fetch_assoc($result))
                { 
               


                ?>
                    <tr>
                        <td class="text-center"><?php echo $sn; ?></td>
                        <td class="text-left pl-1"><?php echo $row['service']; ?></td>
                         <td class="text-center"><?php echo $row['unit']; ?></td>
                        <td class="text-center"><?php echo $row['price']; ?></td>
                        <td class="text-center"><?php echo $row['unit']*$row['price']; ?></td>
                        <td class="text-center"><?php echo $row['discount']; ?></td>
                        <td class="text-center"><?php echo ($row['unit']*$row['price'])-$row['discount']; ?></td>
                       
                        
                    </tr>
                <?php if($_REQUEST['status']=='CANCELLED') { ?>    <div class="watermark top-left">CANCELLED</div> <?php } ?>
                    <?php $sn++;
                          $gross +=$row['unit']*$row['price'];
                          $disc +=$row['discount'];
                          $net += ($row['unit']*$row['price'])-$row['discount'];
                          $collected=$row['collected_by'];
                          $disc_amt=$row['discount_amount'];
                          $paid=$row['paid_amount'];
                          $adv = $row['adjusted_amount'] ? $row['adjusted_amount'] : 0;
                    } 
                    
                    ?>

                </tbody>

                <tfoot>
                    <tr>
                        <th colspan="4" class="text-right">Total</th>
                        <td class="text-center"><?=$gross?></td>
                        <td class="text-center"><?=$disc?></td>
                        <td class="text-center"><?=$net?></td>
                    </tr>
                    <tr>
                        <td colspan="6" class="text-right">Discount</td>
                        <td class="text-center"><?= $disc_amt?></td>
                    </tr>
                    <tr>
                        <td colspan="6" class="text-right">Net Amount</td>
                        <td class="text-center"><?= $net-$disc_amt?></td>
                    </tr>
                    <tr>
                        <td colspan="6" class="text-right">Billed By</td>
                        <td class="text-center"><?= $collected?></td>
                    </tr>
                </tfoot>
            </table>
           
        </div>
    </div>
   
   


                </div>
            </div>
<button class="btn btn-info mb-4 float-right d-print-none ml-3" onclick="window.print()"> Print</button>
<button class="btn btn-danger mb-4 float-right d-print-none" onclick="window.history.back()"> Go Back</button>

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
    