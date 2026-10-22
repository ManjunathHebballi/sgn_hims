<?php 

require_once(__DIR__ . '/model/session_config.php');
include("model/medicineClass.php");
$newOT= new medicine;
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
    
    
         
@media print { @page {size: auto !important;
    
    margin: 10px;
} .element {
     /* Set margin to zero for .element within this @media query */
  }



    
    
   
}


        body{
            
           
            
/*font-family: monospace;*/
                                                
font-family: Sans-serif ;
            line-height: 1.2;
            
            
            margin: 20px;
            
                font-size: 0.85rem;
                /*font-weight:600;*/
        }
        .table td, .table th{
            padding:0;
            
        }
        .medicine{
            font-size:0.9rem;
            
            
        }
        
        table{
            margin-bottom:2px!important;
        }
        hr{
                margin-top: 1px;
                     margin-bottom: 0; 
                     /*border: 0; */
                    /*border-top: 1;*/
        }
        .px-5{
                padding-left: 0em!important;
                    padding-right: 7em!important;
                   
        }
        
        
        
    </style>
  </head>



  <body onload="window.print()">
   
                
                <div class="content-wrapper ">
                        
                
           
<div class="container-fluid px-5">


           
  
                <div class="container-fluid">

<div class="row">

    <div class="col-md-12  align-items-center">
                        
                        <h4 class="text-center text-black"><?= $_SESSION['pharma_name']?></h4>
                        <p class="text-center text-black"><?= $_SESSION['college_location']?> <br><b> Ph:</b> <?= $_SESSION['college_phone']?> <span class="ml-5"> <b>License No. :</b><?=$_SESSION['pharma_license_no'] ?></span>  </p>
                      
 
                        </div>
                       
</div>
                        
                        
 <hr>
        <?php $result=$newOT->getPatientname($_REQUEST['id']);

                $row1=mysqli_fetch_assoc($result); 
                ?>

                    


    
    <table class="table table-borderless" >
 
        <tr>
              <th class="text-right mr-3">Patient NAME : &nbsp;&nbsp;&nbsp;</th><td align="left" >  <?php echo $row1['name'];?></td>
            <th class="text-right mr-3" >Patient Id. : &nbsp;&nbsp;&nbsp;</th><td align="left"> <?php echo $row1['opd_no'];?></td>
            <th class="text-right mr-3"> Invoice No. : &nbsp;&nbsp;&nbsp; </th> <td align="left"><span id='invoice'>AHCP-<?= $_REQUEST['inv']?></span></td>
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

<div class="row">

       
            <table class="table  table-bordered medicine">
                <thead>
                    <tr>
                        <th>Sl. No.</th>
                        <th>Qty</th>
                        <th>Expire Date</th>
                         <th>Batch</th> 
                        <th>Particulars</th>
                        <!--<th>Dosage</th>-->
                        <th>MRP</th>
                         <th>GST %</th> 
                        <th class="text-right">Total</th>
                    </tr>

                </thead>

                <tbody>
                    <?php $result=$newOT->getPharmaBill($_REQUEST['id'],$_REQUEST['inv']);
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
                        <td class="text-center"><?php echo $sn; ?></td>
                          <td class="text-center"><?php echo $row['qty']; ?></td>
                              
                      <td class="text-center"><?php if($row['exp_date']<date("Y-m-d")) {echo ""; }else {echo date('m/Y', strtotime($row['exp_date']));}?></td>
                          
                      <td class="text-center"><?php echo $row['batch']?></td>
                        <td class="text-center"><?php echo $row['name']; ?></td>
                    
                        <td class="text-center"><?php echo sprintf('%.2f',$row['price']); ?></td>
                            <td class="text-center"><?= $row['tax']?></td>
                      
                        
                        
                        
                        
                        <td class="text-right"><?php echo sprintf('%.2f',($sum=$row['qty']*$row['price'])); ?></td>

                    </tr><?php $sn++; 
                     $gt+=$sum;
                    
                
                    
                     } 
                   
                    ?>
  <tr>
                        
                        <td colspan="5" rowspan="3">
                            Goods once Sold Cannot be taken back or Exchanges. Prices Changes are inclusive of taxes &    duties paid. Drugs (price control order 1970) any excess collected by oversight, will be refunded.
                            
                        </td>
                        <th colspan="2" class="text-right">
                            Bill Amt. :
                            
                        </th>
                        <th class="text-right"><?php echo sprintf('%.2f',$gt); ?></th>
                    </tr>
                    <tr>
                         <th colspan="2" class="text-right">
                            Disc Amt. :
                            
                        </th>
                        <th class="text-right"><?php echo sprintf('%.2f',$d); ?></th>
                    </tr>
                     <th colspan="2" class="text-right">
                            NET Amt. :
                            
                        </th>
                        <th class="text-right"><?php echo sprintf('%.2f',($gt-$d)); ?></th>
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
    