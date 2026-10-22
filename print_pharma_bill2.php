<?php 

require_once(__DIR__ . '/model/session_config.php');
include('model/ipClass.php');
$newOP = new addIP;
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
        .table td, .table th{
            padding:0;
        }
        hr{
                margin-top: 1px;
                     margin-bottom: 0; 
                     /*border: 0; */
                    /*border-top: 1;*/
        }
    </style>
  </head>



  <body>
   
                
                <div class="content-wrapper ">
                        
                
           
<div class="container-fluid  p-2">


                <div class="container-fluid">

<div class="row">

    <div class="col-md-12  align-items-center">
                        
                        <h3 class="text-center text-black"><?= $_SESSION['pharma_name']?></h3>
                        <p class="text-center text-black"><?= $_SESSION['college_location']?> <br> Ph: <?= $_SESSION['college_phone']?></p>
                      

                        </div>
                       
</div>
 <div class="d-flex justify-content-between">
                            
                     
                              <p>License No. :<?=$_SESSION['pharma_license_no'] ?></p>  
                        
                        </div>
                        
                        


 <hr>

      <?php $result=$newOP->getPatientname($_REQUEST['id']);

                $row1=mysqli_fetch_assoc($result); 
                ?>




    <table class="table table-borderless" >
 
        <tr>
              <th class="text-right mr-3">Patient NAME : &nbsp;&nbsp;&nbsp;</th><td align="left" >  <?php echo $row1['name'];?></td>
            <th class="text-right mr-3" >Patient Id. : &nbsp;&nbsp;&nbsp;</th><td align="left"> <?php echo $row1['opd_no'];?></td>
            <th class="text-right mr-3"> Invoice No. : &nbsp;&nbsp;&nbsp; </th> <td align="left">PMGS-<?php echo $_REQUEST['inv'];?></td>
        </tr>
        <!-- <hr> -->
        <tr>
          <th class="text-right mr-3">Dr. Name : &nbsp;&nbsp;&nbsp;</th><td align="left"><?php echo $row1['doctor'];?></td>
            <th class="text-right">Patient Address :&nbsp;&nbsp;&nbsp; </th><td align="left" ><?php echo $row1['address'];?></td>
      
            <th  class="text-right mr-3">Date : &nbsp;&nbsp;&nbsp;</th><td align="left" > <?php echo date('d-m-Y', strtotime($row1['date']));?></td>
            
        </tr>

    </table>
</div>


 <div class="container-fluid">

<div class="row p-2">

       
            <table class="table">
                <thead>
                    <tr>
                        <th>Sl. No.</th>
                        <th>Qty</th>
                        <th>Expire Date</th>
                         <th>Batch</th> 
                        <th>Medicine</th>
                        <!--<th>Dosage</th>-->
                        <th>MRP</th>
                         <th>GST %</th> 
                        <th class="text-right">Total</th>
                    </tr>

                </thead>

                <tbody>
                    <?php $result=$newOP->pharmaPrint($_REQUEST['id']);
                        $sn=1;
                        $total=0;
                        $gt=0;
                        $d=0;
                while ($row=mysqli_fetch_assoc($result))
                { 
                $d += $row['discount'];
                // $gt += $row['grand_total'];


                ?>
                    <tr>
                        <td><?php echo $sn; ?></td>
                        <td><?php echo $row['qty']; ?></td>
                        <td><?php echo $row['expdate']; ?></td>
                        <td><?php echo $row['batch']; ?></td>
                        <td><?php echo $row['mname']; ?></td>
                        
                        <td><?php echo $row['price']; ?></td>
                        <td><?php echo $row['tax']; ?>%</td>
                        <td class="text-right"><?php echo round($sum=$row['qty']*$row['price']); ?></td>

                    </tr><?php $sn++; 
                     $gt+=$sum;
                    
                    
                    
                     } 
                    
                    ?>

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
   
    <div class="container-fluid">
        <div class="row">

            <div class="col-md-9">
                <div class="row ">
                   
                
                </div>
                <div class="row">
                    <p>Goods once Sold Cannot be taken back or Exchanges. Prices Changes are inclusive of taxes &  <br>  duties paid. Drugs (price control order 1970) any excess collected by oversight, will be refunded.</p>
                </div>
            </div>
            <br><br>
            <div class="col-md-3"><strong>
                <div class="row">
                    <div class="col-md-8 float-right"><p class="float-right">Bill Amt. :</p></div>
                    <div class="col-md-4 float-left"><p class="float-right"><?php echo round($gt,2); ?></p></div>
                
                </div>
                <div class="row">
                    <div class="col-md-8 float-right"><p class="float-right">Discount Amt. :</p></div>
                    <div class="col-md-4 float-left"><p class="float-right"><?php echo $d; ?></p></div>
                
                </div>
                
                <div class="row">
                    <div class="col-md-8 float-right"><p class="float-right">NET Amt. :</p></div>
                    <div class="col-md-4 float-left"><p class="float-right"><?php echo round(($gt-$d),2); ?></p></div>
                
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
    <!--<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>-->
    <!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>-->

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js" integrity="sha384-VHvPCCyXqtD5DqJeNxl2dtTyhF78xXNXdkwX1CZeRusQfRKp+tA7hAShOK/B/fQ2" crossorigin="anonymous"></script>
   
  </body>
</html>
    