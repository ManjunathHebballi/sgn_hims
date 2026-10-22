<?php 
require_once(__DIR__ . '/model/session_config.php');
include("model/medicineClass.php");
$newMedicine= new medicine();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>OP Patient </title>
    <!-- base:css -->
    <link rel="stylesheet" href="vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="vendors/base/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="css/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="images/favicon.png" />
     <link rel="stylesheet" href="./vendor/select2/css/select2.min.css">
  </head>
  <body>
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

  <?php include ('leftbar.php')?>
  <div class="content-body">

    <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <div class="main-panel">
                
                <div class="content-wrapper ">
                                    <h3 class="text-dark font-weight-bold mb-2 px-4">Pharmacy Bill</h3>


        
      <form action="controller/ProcessPharmaBill.php" method="POST">                                        
            <div class="row mb-4 mt-3">
                <div class="col-md-12">
                  
                            
            <div class="card">
                <div class="card-body">

                

<input type="hidden" name="pid" value="<?= $_REQUEST['id']?>">
<!-- <input type="hidden" name="aid" value="<?= $_REQUEST['aid']?>"> -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">

                    <label for="pname">Patient Name</label>
                    <?php  
$result=$newMedicine->getPatientname($_REQUEST['id']);

$row1=mysqli_fetch_assoc($result);
     ?>

                    <input class="form-control" type="text" name="pname1" id="pname1" value="<?php echo $row1['name']; ?>" readonly>

                   </div>
                    </div>
                   <div class="col-md-3">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label for="">Doctor <small class="text-danger">*</small></label>
                                        <input class="form-control" type="text" name="doctor" id="doctor" value="<?php echo $row1['doctor']; ?>" readonly>
                                    </div>
                                </div>
                            </div>
                             <div class="col-md-3">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label for="">Department <small class="text-danger">*</small></label>
                                        <input class="form-control" type="text" name="dept" id="dept" value="<?php echo $row1['dept_name']; ?>" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                   <div class="form-group">
                      <label for="date">Prescription Date</label>
                      <input class="form-control" readonly name="date1" id="date1" value="<?php echo date('d-m-Y', strtotime($row1['date'])) ;?>"  >
                    </div>
                    
                   </div>
           <div class="col-md-3">
                   <div class="form-group">
                      <label for="date">Payment Mode</label>
                      <select class="form-control" readonly name="pay_mode" id="pay_mode"  required >
                          <option value=''>Select Payment Mode</option>
                          <option>Cash</option>
                          <option>UPI</option>
                          <option>Cheque</option>
                          <option>Card</option>
                          <option>Bank Transfer</option>
                      </select>
                    </div>
                    
                   </div>


                </div>
               

            
                </div>
            </div>
                </div>
            </div>
            

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body table-responsive">
            

                <table class="table  table-bordered"   id="tab_logic">
     <thead>
            <tr>
                <th>Medicine Information</th>
                <th>Batch</th>
                <th>Exp Date</th>
                <th>Stock Qty</th>
                <th>Quantity</th>
                <th>Price (in Rs.)</th>
                 <th>GST %</th>
                <th>Total (in Rs.)</th>
                <th>Action</th>
            </tr>
        </thead>
            <tbody>

            <tr id='addr1'>
                <td>    

                    <select class="form-control selectpicker medicine1 bg-white" name="madicine[]" id="madicine1" onchange="showResult(this.value,1)" autocomplete="off" placeholder="Enter Medicine Name">
              
<option value="">Select Medicine</option>

<?php  
$result=$newMedicine->getMedicine1();

while ($tab=mysqli_fetch_array($result)) 
    { 

    
                             $end = time(); // or your date as well
                        $start = strtotime($tab['exp_date']);
                        $day=ceil(abs($end - $start) / 86400);
                        if($day < 35)
                        {
                            $col="red";
                        }
                        else if($day < 65){
                            $col="yellow";
                        }
                        else if($day < 95)
                        {
                            $col="green";
                        }
                        else{
                            $col="white";
                        }
                        $strip=round(($tab['total_qty']-$tab['free_qty'])/$tab['box_qty']);
                        $price=round($tab['box_mrp']/(($tab['total_qty']-$tab['free_qty'])/$tab['box_qty']),2);
                        ?>
                        <option value="<?= $tab['id']?>"><?= $tab['name']?></option>
        <?php } ?>
                    </select>
                </td>
        
                <td>
                    <select  class="form-control " name="batch[]" id="batch1" onchange="getExpiry(this.value,1)" placeholder="0" required>
                        <option value="">select batch</option>
                        </select>
                </td>
                <td>
                    <input type="month" class="form-control" readonly name="expdate[]" id="expdate1" placeholder="0" >
                </td>
                <td>
                    <input type="number" class="form-control" name="stock[]" id="stock1" placeholder="0" readonly>
                </td>
                <td>
                    <input type="number" class="form-control" name="quantity[]" id="quantity1" placeholder="0" onkeyup="checkQty(1)">
                </td>
             
                <td>
                    <input type="text" class="form-control" name="price[]" id="price1" placeholder="0" onkeyup="checkQty(1)">
                </td>
                <td>
                    <input type="text" class="form-control" name="tax[]" id="tax1" placeholder="0" onkeyup="checkQty(1)">
                </td>
       
                <td>
                    <input type="text" class="form-control" name="total[]" id="total1" placeholder="0" readonly>
            
                

                </td>
                <td class=" text-center">
               <a class="pull-right btn btn-danger"  id='delete_row' name='dlt1' onclick='dlt(1)' >  <i class="icofont-ui-delete "  data-toggle="tooltip" data-placement="top" title="Delete">Delete</i></a>
                </td>
            </tr>
             <tr id='addr2'></tr> 
            </tbody>
            <!-- </table>
            <table class="table table-responsive table-bordered"   id="tab_logic"> -->
            <tfoot> 
            
            <tr>
                <td colspan="6" > </td>
                <td>Invoice Discount (in Rs.)</td>
                <td>
                <input type="text" class="form-control"  name="indiscount"  id="indiscount"  onkeyup="checkQty(0)" placeholder="0.00" value="">
                </td>
                <td class="">
                    <button type="button" id="add_row" class="btn btn-success pull-right">Add Row</button>
                </td>
            </tr>
       
       
               
            <tr>
                <td colspan="6"> </td>
                <td> Grand Total (in Rs.) </td>
                <td>
                <input type="text" class="form-control" name="grand" id="grand" placeholder="0.00" readonly value="<?= $total-$dis; ?>" >
                </td>
                
            </tr>
        <input type="hidden" name="id" id="id" value=1>
         

            <!-- <tr>
                <td colspan="8" class="">  <button type="submit" class="btn btn-success text-white  d-flex justify-content-end" name="submit" value="submit">Save</button></td>
               
                
            </tr> -->
        </tfoot>
            </table>
             <?php 
            $pid=$_REQUEST['id']; ?>       
<div class="col-md-12 d-flex justify-content-end ">
   <?php if($_REQUEST['save']!=1) { ?> <button type="submit" class="btn btn-success  font-weight-normal">Save</button><?php } ?>
   
   <?php if($_REQUEST['save']==1) { ?>  <a href="print_pharma_bill.php?id=<?=$pid;?>" class="btn btn-danger  font-weight-normal mx-3" target="_blank">Print</a><?php } ?>

</div>
               
            </div>
        </div>
    </div>
</div>
 </form>

                </div>
                <!-- content-wrapper ends -->
                <!-- partial:partials/_footer.html -->
            
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
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
<!-- Required vendors -->
<script src="./vendor/global/global.min.js"></script>
<script src="./js/quixnav-init.js"></script>
<script src="./js/custom.min.js"></script>

<!-- <script src="assets/js/common.min.js"></script> -->
<!-- <script src="assets/js/bootstrap-select"></script> -->


<!-- <script src="assets/bootstrap-multiselect/js/bootstrap-multiselect.js"></script>
<script src="assets/js/bootstrap-multiselect-init.js"></script>
<script src="assets/js/bootstrap-select-init.js"></script> -->








<!-- Datatable -->
<script src="./vendor/datatables/js/jquery.dataTables.min.js"></script>
<script src="./js/plugins-init/datatables.init.js"></script>
<script src="./js/plugins-init/datatables.init.js"></script>


        <!-- container-scroller -->
    <!-- base:js -->
    <script src="vendors/base/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page-->
    <!-- End plugin js for this page-->
    <!-- inject:js -->
    <script src="js/template.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js" integrity="sha384-VHvPCCyXqtD5DqJeNxl2dtTyhF78xXNXdkwX1CZeRusQfRKp+tA7hAShOK/B/fQ2" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.1.1.min.js"> </script>

  <script src="./vendor/select2/js/select2.full.min.js"></script>
    <script src="./js/plugins-init/select2-init.js"></script>

    <!-- endinject -->
    <script>
        $(document).ready(function(){
   var i=2;
  $("#add_row").click(function(){
    var i=parseInt(document.getElementById('id').value);
 
   $("#addr"+i).html("<td><select type='text' class='form-control selectpicker medicine1 bg-white' name='madicine[]' id='madicine"+i+"' onchange='showResult(this.value,"+i+")' placeholder='Enter Medicine Name'><option value=''>Select Medicine</option><?php $result=$newMedicine->getMedicine1(); while($tab=mysqli_fetch_assoc($result)){ $price=round($tab['box_mrp']/(($tab['total_qty']-$tab['free_qty'])/$tab['box_qty']),2);  $strip=round(($tab['total_qty']-$tab['free_qty'])/$tab['box_qty']); ?><option value='<?= $tab['id']?>'><?= $tab['name']?></option><?php } ?>    </select></td><td><select class='form-control' name='batch[]' id='batch"+i+"' getExpiry(this.value,"+i+") required></select></td>  <td><input type='month' class='form-control' name='expdate[]' id='expdate"+i+"' placeholder='0' readonly></td>    <td><input type='number' class='form-control' name='stock[]' id='stock"+i+"' placeholder='0' readonly></td>   <td> <input type='number' class='form-control' name='quantity[]' id='quantity"+i+"' placeholder='0' onkeyup='checkQty("+i+")' required> </td> <td><input type='text' class='form-control' name='price[]' id='price"+i+"' onkeyup='checkQty("+i+")' placeholder='0' required ></td> <td><input type='text' class='form-control' name='tax[]' id='tax"+i+"' placeholder='0' onkeyup='checkQty(1)'></td> <td><input type='number' class='form-control' name='total[]' id='total"+i+"' placeholder='0' readonly ></td><td class=' text-center'><a id='delete_row' name='dlt"+i+"' onclick='dlt("+i+")' class='pull-right btn btn-danger'><i class='icofont-ui-delete' data-toggle='tooltip' data-placement='top' title='Delete'>Delete</i></a></td>");
 $('.medicine1').select2();
   $('#tab_logic').append('<tr id="addr'+(i+1)+'"></tr>');
   i++; 
   document.getElementById('id').value=i;
});
  $("#delete_row").click(function(){
      if(i>1){
      $("#addr"+(i-1)).html('');
      i--;
      }
  });
  document.getElementById('id').value=i;

});
function dlt(id){
      
      $("#addr"+(id)).html('');
      document.getElementById('id').value=id;

     
  };
  function showResult(str,i) {
     $.ajax({
      url: 'ajax.php',
        type: 'POST', // Send post data
        data: 'id='+str+'&page=getmedic',
        async: false,
        success: function(s){
            // alert(s);
            json_events = s;
           document.getElementById('batch'+i).innerHTML=json_events;
           
          }
        });
batch=document.getElementById('batch'+i).value;
getExpiry(batch,i);
      
        document.getElementById('quantity'+i).focus();
}
function getExpiry(str,i){
    med=document.getElementById('madicine'+i).value;
     $.ajax({
      url: 'ajax.php',
        type: 'POST', // Send post data
        data: 'batch='+str+'&medic='+med+'&page=getexpiry',
        async: false,
        success: function(s){
            // alert(s);
            json_events = s.split("|");
           document.getElementById('expdate'+i).value=json_events[0];
           document.getElementById('price'+i).value=json_events[1];
           document.getElementById('tax'+i).value=json_events[2];
           document.getElementById('stock'+i).value=json_events[3];
           
          }
        });
}
function checkQty(i)
{
   
    if(i !='0')
        {
            
    var data=document.getElementById('quantity'+i).value *  document.getElementById('price'+i).value;
    document.getElementById('total'+i).value= data.toFixed(2) ;
        }
    
    if(document.getElementById('indiscount').value.length == 0){
        document.getElementById('indiscount').value=0;
    }
    
    // var grand=document.getElementById("grand_amt").value;
   
    if(document.getElementById('id').value==1)
    {
        id=2;
    }
    else{
        id=document.getElementById('id').value;
    }
var grand=0;
    for (let index = 1; index <id; index++)
    {
        if(document.getElementById('total'+index)!= null && document.getElementById('total'+index).value.length > 0)
        {
            // alert(document.getElementById('total['+index+']').value);
            
        grand =parseFloat(grand)+parseFloat(document.getElementById('total'+index).value);
        }
       
    }
   
    document.getElementById('grand').value= grand - document.getElementById('indiscount').value;
}

function updateqty(sn,id)
{
    document.getElementById('totl_pre'+sn).value=document.getElementById('qty'+sn).value * document.getElementById('price_pre'+sn).value;
    var grand=0;
    for(var i=1;i<=id;i++)
    {
        grand +=parseInt(document.getElementById('totl_pre'+i).value);
    }
    document.getElementById('grand').value=grand;
     if(document.getElementById('id').value==1)
    {
        id=2;
    }
    else{
        id=document.getElementById('id').value;
    }
// alert(id);
//   alert(id)
    for (let index = 1; index <id; index++) {
        // alert(document.getElementById('total['+index+']'))
        if(document.getElementById('total['+index+']') != null && document.getElementById('total['+index+']').value.length > 0)
        {
                grand =parseFloat(grand)+parseFloat(document.getElementById('total['+index+']').value);
            
        }
    }
  
    document.getElementById('grand').value= grand - document.getElementById('indiscount').value;
}


$(document).ready(function() {
    $('.medicine1').select2();
    
   
});

 </script>


    <!-- End custom js for this page-->
  </body>
</html>