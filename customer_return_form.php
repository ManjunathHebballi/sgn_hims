<?php 

require_once(__DIR__ . '/model/session_config.php');
include('model/medicineClass.php');
$newMed = new medicine;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#319197">

    <title>Add Returns</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" ></script>
        <!-- Sweet Alert-->
     <link href="css/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
 
<style>
     #notification {
            position: absolute;
            top: 90px;
           
            right:2px;
            z-index: 99;
        }
</style>

</head>

<body>


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

<div aria-live="polite" id="notification" aria-atomic="true" >
<?php if(isset($_REQUEST['success']))
{ ?>
    
<div class="alert alert-success solid alert-dismissible fade show">
                                    <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span>
                                    </button>
                                    <strong>Success!</strong>Record Saved Successfully!!
                                </div>
                         <?php   }
                            ?>
</div>

    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">


    

    

  <?php include ('leftbar.php')?>
  

        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <!-- row -->



<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <h4 class="text-uppercase"> Return Invoice</h4>
        </div>
    </div>
    <?php $res=$newMed->getPatientreturn($_REQUEST['cust_invoice']);
    $row=mysqli_fetch_assoc($res); ?>

   <form action="controller/processMedicine.php" method="POST"> <div class="row">
   <div class="col-md-12">
       <div class="card">
          
           <div class="card-body">
               <div class="row">
                   <div class="col-md-4">
                       <div class="form-group">
                           <label for="">Customer Name</label>
                           <input type="text" name="cust_name" class="form-control" id="cust_name" value="<?=$row['name']?>"readonly>
                       </div>
                   </div>
                   <div class="col-md-4">
                       <div class="form-group">
                           <label for="">Purchase Date</label>
                           <input type="text" name="date" class="form-control" id="date" value="<?=date('d-m-Y',strtotime($row['date']))?>"readonly>
                       </div>
                   </div>
                    <div class="col-md-4">
                       <div class="form-group">
                           <label for="">Patient Type</label>
                           <input type="text" name="ptype" class="form-control" id="date" value="<?=$row['type']?>"readonly>
                       </div>
                   </div>
               </div>

               <div class="row">
                   <div class="col-md-12">
                       <table class="table table-bordered">
                           <thead class="thead-dark">
                               <tr>
                                   <th>Medicine Information </th>
                                   <th>Sold Qty</th>
                                   <th>Price </th>
                                   <th>Ret Qty <snap class="text-danger">*</snap></th>
                                   <th>Price Per Medicine</th>
                                   <th>Deduction %</th>
                                   <th>Return Amount</th>
                                   <!--<th>Check Return<snap class="text-danger">*</snap></th>-->
                               </tr>
                           </thead>
                           <tbody>
                               <?php
                               $sn=1;
                               $ret=$newMed->getReturnMedicine($_REQUEST['cust_invoice']);
                               while($med=mysqli_fetch_assoc($ret))
                               { ?>
                               <tr> 
                                <input type="hidden" name="patient_id[]" value=<?=$row['patient_id']?>>
                                 <input type="hidden" name="cust_invoice" value=<?=$_REQUEST['cust_invoice']?>>
                                <input type="hidden" name="pur_id[]" value=<?=$med['id']?>>   
                                   <td><input type="text" name="medicine[]" id="medicine<?=$sn?>" class="form-control" readonly value="<?=$med['name']?>"></td>
                                   <td><input type="text" name="sold_qty[]" id="sold_qty<?=$sn?>" class="form-control" readonly value="<?=$med['qty']?>"></td>
                                   <td><input type="text" name="price[]" id="price<?=$sn?>" class="form-control" readonly value="<?=$med['total']?>"></td>
                                   <td><input type="text" name="ret[]" id="ret<?=$sn?>" onkeyup="calPrice(this.value, <?=$sn?>); calTotal(<?=$sn?>);retQty(<?=$sn?>)" class="form-control"  value="0" required></td>
                                    <td><input type="text" name="med_price[]" id="med_price<?=$sn?>" class="form-control"  value="<?=$med['price']?>" required></td>
                                    
                                    <input type="hidden" name="ded_amount[]" id="ded_amount<?=$sn?>" value="">
                                    
                                   <td><input type="text" name="deduction[]" id="deduction<?=$sn?>" onkeyup="calDeduct(this.value, <?=$sn?>); calTotal(<?=$sn?>)"class="form-control"  value="0"></td>
                                   <td><input type="text" name="total[]" id="total<?=$sn?>" class="form-control" readonly value=""></td>
                                   <!--<td><input type="checkbox" name="check<?=$sn?>" id="check<?=$sn?>" class="form-control"  value="return"></td>-->

                                  
                                </tr>
                                <?php $sn++; } ?>
                                <input type="hidden" id="sn_val" name="sn_val" value=<?=$sn?>>
                                <tr >
                                    <td  colspan="5" rowspan="3"> <textarea name="comment" class="form-control" id="comment" cols="3" rows="3"></textarea></td>
                                    <td class="border-right" >Total Deduction:</td>
                                    <td class="border-right" ><input type="text" name="total_deduction" id="total_deduction" class="form-control"  value="0.00" readonly></td>

                                </tr>
                                <tr>
                                <!--<td class="border-right" >Total Tax:</td>-->
                                <!--    <td class="border-right" ><input type="text" name="tax" id="tax" class="form-control"  value="0.00" disabled></td>-->

                                </tr>
                                <tr><td class="border-right" >Net Return:</td>
                                    <td class="border-right" ><input type="text" name="net_return" id="net_return" class="form-control"  value="0.00" readonly></td>
</tr>
                           </tbody>
                       </table>

                   <button type="submit" name="submit" value="cust_return" class="btn btn-success float-right">Return</button>
                   </div>
               </div>

           </div>
       </div>
   </div>
    </div>
    </form>
   

    



</div>

          
        </div>
        <!--**********************************
            Content body end
        ***********************************-->


        <!--**********************************
            Footer start
        ***********************************-->
        <?php include('footer.php') ?>
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
    <!--  vendors -->
    <script src="./vendor/global/global.min.js"></script>
    <script src="./js/quixnav-init.js"></script>
    <script src="./js/custom.min.js"></script>

<script>
    
    window.setTimeout(function() {
    $(".alert").fadeTo(500, 0).slideUp(500, function(){
        $(this).remove(); 
    });
}, 4000);
</script>

<script>
    function calPrice(val,i)
    {
       document.getElementById('total'+i).value=( document.getElementById('ret'+i).value * document.getElementById('med_price'+i).value).toFixed(2);
    }
    
    function calDeduct(val,i)
    { 
        document.getElementById('total'+i).value=(document.getElementById('total'+i).value-((document.getElementById('total'+i).value * document.getElementById('deduction'+i).value)/100)).toFixed(2);
         document.getElementById('ded_amount'+i).value=2 * (((document.getElementById('total'+i).value * document.getElementById('deduction'+i).value)/100)).toFixed(2);
    }
    

       
        
 

function calTotal(j)
{
 
 
  var i = document.getElementById('sn_val').value;
  var tot = 0; 
  var ded=0;
  for (var n = 1; n < i; n++) 
  {
    var value = parseFloat(document.getElementById('total' + n).value);
    ded += ((document.getElementById('total'+n).value * document.getElementById('deduction'+n).value)/100);

    if (value) 
    { 
      tot += value;
    }
   
  }

  document.getElementById('net_return').value = tot.toFixed(2);
   document.getElementById('total_deduction').value = 2*(ded).toFixed(2);

}

function retQty(i)
{
    var sold=document.getElementById('sold_qty'+i).value;
    var ret=document.getElementById('ret'+i).value;
    if(ret>sold)
    {
        alert('Returning Quantity is more than sold')
        document.getElementById('ret'+i).value=0;
        document.getElementById('total'+i).value=0;
    }
   
}
 </script>

  

</body>

</html>