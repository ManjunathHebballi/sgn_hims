<?php 

require_once(__DIR__ . '/../model/session_config.php');
include('model/billingClass.php');
$newBill = new addBilling;
$data=$newBill->getPatientBill($_REQUEST['id'],$_REQUEST['editid']);
$row1=mysqli_fetch_assoc($data);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Radiology Billing</title>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" ></script>
        <!-- Sweet Alert-->
     <link href="css/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
   <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <link rel="stylesheet" href="css/style.css">
 
    <link rel="shortcut icon" href="images/favicon.png" />
      <style>
           /* Hide the spinner controls for number input */
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type=number] {
        -moz-appearance: textfield; /* Firefox */
    }
      </style>
      
      
  </head>
  <body onload="updateDiscount()">
  <div id="main-wrapper">

<?php include ('leftbar.php')?>
<div class="content-body text-uppercase">


    <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <div class="main-panel">
                
                <div class="content-wrapper ">
                                    <h3 class="page-title text-dark font-weight-bold mb-2 px-4">Edit Radiology Bill</h3>



        
                                    <form method="post" action="controller/processBilling.php">
                                    
            <div class="row mb-4 mt-3">
                <div class="col-md-12">
                 
                            
            <div class="card">
                <div class="card-body">

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">

                    <label for="pname">OPD No</label>
                    <input onchange="javascript:patientDetail(this.value)" class="form-control" type="text"  name="ip_no" id="ip_no" readonly value="<?= $row1['opd_no']?>" required>
                     <input type="hidden"  name="patient_id" id="patient_id" readonly value="<?= $_REQUEST['id']?>" >
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">

                    <label for="pname">Patient Name</label>
                    <input class="form-control" type="text" readonly name="pname" id="pname" value="<?= $row1['name']?>"  required>

                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">

                    <label for="pname">Age</label>
                    <input class="form-control" type="text" readonly name="age" id="age" value="<?= $row1['gender']?>" required>

                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">

                    <label for="pname">Gender</label>
                    <input class="form-control" type="text" readonly name="gender" id="gender" value="<?= $row1['age']?>" required>

                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">

                    <label for="pname">Mobile Number</label>
                    <input class="form-control" type="text" readonly name="pnum" id="pnum" value="<?= $row1['mobile']?>">

                        </div>
                    </div>
                    <div class="col-md-3">
                <div class="form-group">
                    <label >Doctor <small class="text-danger"> *</small></label>

                    <select required class="form-control select2doctor " name="doc" id="doc">
                        <option value="">Select Doctor</option>
                        <?php $result=$newBill->selectDoctor();
                        while($row=mysqli_fetch_assoc($result))
                        { ?>
                        <option value="<?php echo $row['name']." ".$row['last_name']; ?>"<?php if($row['name']." ".$row['last_name']==$row1['doctor']) echo 'selected';?>><?php echo $row['name']." ".$row['last_name']; ?></option>
                        
                        <?php } ?>
                       
                    </select>

                </div>
         </div>
         <div class="col-md-3">
                <div class="form-group">
                    <label >Departement <small class="text-danger"> *</small></label>

                    <select required class="form-control text-uppercase select2search" name="dept" id="dept">
                        <option value="">Select Departement</option>
                        <?php $result=$newBill->selectDepartment();
                        while($row=mysqli_fetch_assoc($result))
                        { ?>
                        <option value="<?php echo $row['id'] ?>" <?php if($row['id']==$row1['dept_id']) echo 'selected';?>><?php echo $row['dept_name']; ?></option>
                        
                        <?php } ?>
                       
                    </select>

                </div>
         </div>
                 
                  <input type="hidden" name="patient_type" id="patient_type" value="OP">
                  <input type="hidden" name="service_type" id="service_type" value="Radiology">

                            <div class="col-md-3">
                   <div class="form-group">
                      <label for="date">Billing Date</label>
                      <input class="form-control" type="datetime-local" name="date" id="date" value="<?php if(isset($row1['date'])) { echo date("Y-m-d H:i:s",strtotime($row1['date'])); } else { echo date("Y-m-d H:i:s"); } ?>" readonly>
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
            

                <table class="table table-bordered" id="bill_list"  >
     <thead>
            <tr>
                <th>Items</th>
               <th>Doctor</th>
                <th>Unit</th>
                <th>Price</th>
                <th>Disc (%)</th>
                <th>Disc Amt</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
        </thead>
            <tbody id="tab_logic">
                
                <?php
                    $data1=$newBill->getPatientBillDetails($row1['id'],$_REQUEST['editid']);  
                    $i=1;
                    $serv_lvl=$tot_amt=0;$net_amt=0;
                    while($res1=mysqli_fetch_assoc($data1))
                    {
                        
                       ?> 
                       <tr id='addr<?= $i?>'>
                
              <td class="col-md-3">
                  
                <select name="service[]" id="service<?= $i?>" class="form-control select2search" required onchange="showResult(this.value,<?= $i?>);checkQty(<?= $i?>);" >
                    <option>Select Service</option>
                    <?php $res=$newBill->getRItems();
                    
                    while($row=mysqli_fetch_assoc($res))
                    { ?>
                    <option value="<?=$row['id']?>" <?php if($row['id']==$res1['service_id']) echo 'selected';?>><?=$row['name']?></option>
                    <?php } ?>
                </select>
               </td>
               <td>
                  <select required class="form-control select2doctor" name="doctor[]" id="doctor<?= $i?>" onchange="getDoctor(this.value,<?= $i?>)" >
                        <option value="">Select Doctor</option>
                        <?php $result=$newBill->selectDoctor();
                        
                        while($row=mysqli_fetch_assoc($result))
                        { ?>
                        <option value="<?php echo $row['id']?>" <?php if($row['id']==$res1['doctor_id']) echo 'selected';?>><?php echo $row['name']." ".$row['last_name']; ?></option>
                        
                        <?php } ?>
                       
                    </select>
             </td>
               
                <td>
                    <input type="number" class="form-control" name="quantity[]" id="quantity<?= $i?>" placeholder="0" onkeyup="checkQty(<?= $i?>)" value="<?= $res1['unit']?>" required>
                    
                </td>
                    <input type="hidden" class="form-control" name="service_id[]" id="service_id<?= $i?>" value="<?= $res1['service_id']?>">
                    <input type="hidden" class="form-control" name="doctor_id[]" id="doctor_id<?= $i?>" value="<?= $res1['doctor_id']?>">
                <td>
                    <input type="number" class="form-control" name="price[]" id="price<?= $i?>" placeholder="0" value="<?= $res1['price']?>" onkeyup="checkQty(<?= $i?>)" required>
                </td>
                <td>
                    <input type="number" class="form-control" name="disc_per[]" id="disc_per<?= $i?>" placeholder="0"  onkeyup="checkQty(<?= $i?>)">
                </td>
                <td>
                    <input type="number" class="form-control" name="disc_amt[]" id="disc_amt<?= $i?>" placeholder="0" value="<?= $res1['discount_amount']?>" onkeyup="checkQty(<?= $i?>)">
                </td>
             
                <td>
                    <input type="text" class="form-control" name="total[]" id="total<?= $i?>" placeholder="0" value="<?= ($res1['unit'] * $res1['price'])-$res1['discount_amount']?>" readonly>
 

                </td>
                <td class=" text-center">
               <a class="pull-right btn btn-danger"  id='cancel'  onclick='cancelBill(<?= $res1['id']?>,<?= $i?>)' >  Delete</a>
               <input type="hidden" name="editid[]" value="<?= $res1['id']?>">
                </td>
            </tr>
            <?php $serv_lvl +=$res1['discount_amount'];
            $tot_amt +=$res1['unit'] * $res1['price'];
                        $i++;
                    }?>
            <tr>
                
            </tr>
            <tr  id='addr<?= $i?>'></tr>
            </tbody>
        
            <tfoot> 
          
            <tr >

                <td colspan="5" > </td>
                <td> Gross Amount </td>
                <td>
                <input type="text" class="form-control" name="gross" id="gross" placeholder="0.00" value="<?= $tot_amt?>" readonly >
                </td>
                
            </tr>
            <tr >

                <td colspan="5" > </td>
                <td> Service Level Discount </td>
                <td>
                <input type="text" class="form-control" name="srv_disc" id="srv_disc" placeholder="0.00" value="<?= $serv_lvl?>" readonly >
                </td>
                
            </tr>
            <tr >

                <td colspan="5" > </td>
                <td> Total Amount </td>
                <td>
                <input type="text" class="form-control" name="tot_amt" id="tot_amt" placeholder="0.00" value="<?= $tot_amt-$serv_lvl?>" readonly >
                </td>
                
            </tr>
         
            
            <tr>
                <td colspan="5" > </td>
                <td> Discount Amount </td>
                <td>
                <input type="text" class="form-control" name="discount" id="discount" onkeyup="updateDiscount()" placeholder="0.00" value="<?= $row1['discount_amount']?>">
                </td>
            </tr>
            <tr>
                <td colspan="5" > </td>
                <td> Grand Total Amount </td>
                <td>
                <input type="text" class="form-control" name="grand" id="grand" placeholder="0.00" value="<?= $row1['amount']-$row1['discount_amount']?>" readonly>
                </td>
            </tr>
            <tr>
                <td colspan="5" > </td>
                <td> Amount Paid </td>
                <td>
                <input type="text" class="form-control" name="paid_amt" id="paid_amt" onkeyup="updateDiscount()" placeholder="0.00" value="<?= $row1['paid_amount']?>" readonly>
                </td>
            </tr>
              <tr>
                <td colspan="5" > </td>
                <td> Amount Returned </td>
                <td>
                <input type="text" class="form-control" name="ret_amt" id="ret_amt"  value="<?= $row1['returned_amount']?>" readonly >
                </td>
            </tr>
            <tr >

                <td colspan="5" > </td>
                <td> Outstanding amount </td>
                <td>
                <input type="number" class="form-control" name="outstand" id="outstand" placeholder="0.00"  readonly onkeyup="updateDiscount()" value="<?= $row1['outstand_amount']?>" >
                </td>
               
            </tr>
       
       
               
           
        <input type="hidden" name="id" id="id" value=<?= $i?>>
        <input type="hidden" name="billno" id="billno" value=<?= $_REQUEST['editid']?>>
        <input type="hidden" name="billid" id="billid" value=<?= $row1['id']?>>
         

            <!-- <tr>
                <td colspan="8" class="">  <button type="submit" class="btn btn-success text-white  d-flex justify-content-end" name="submit" value="submit">Save</button></td>
               
                
            </tr> -->
        </tfoot>
    </table>
     <div class="col-md-12 d-flex justify-content-end m-4 ">
    <button type="submit" name="submit" value="op_patho" class="btn btn-success  font-weight-normal">Save</button>
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
<?php include('footer.php')?>
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








<script src="./vendor/datatables/js/jquery.dataTables.min.js"></script>
<script src="./js/plugins-init/datatables.init.js"></script>
<script src="./js/plugins-init/datatables.init.js"></script>


    
        <script src="./vendor/select2/js/select2.full.min.js"></script>
    <script src="./js/plugins-init/select2-init.js"></script>
    
    
    
    <!-- endinject -->
    <script>
        $(document).ready(function(){
  var i=document.getElementById('id').value;
  $("#add_row").click(function(){
    var i=parseInt(document.getElementById('id').value);
//  alert(i)        
   $("#addr"+i).html("<td class='col-md-3'><select name='service[]' id='service"+i+"' class='form-control mySelect2' required onchange='showResult(this.value,"+i+")'><option>Select Service</option><?php $res=$newBill->getRItems();while($row=mysqli_fetch_assoc($res)){ ?><option value='<?=$row['id']?>'><?=$row['name']?></option><?php } ?></select></td> <td><select required class='form-control select2search' name='doctor[]' id='doctor"+i+"' onchange='getDoctor(this.value,"+i+")'><option value=''>Select Doctor</option><?php $result=$newBill->selectDoctor();while($row=mysqli_fetch_assoc($result)){ ?><option value='<?php echo $row['id'] ?>'><?php echo $row['name'].' '.$row['last_name']; ?></option><?php } ?></select></td>  <input type='hidden' class='form-control' name='service_id[]' id='service_id"+i+"'>  <input type='hidden' class='form-control' name='doctor_id[]'' id='doctor_id"+i+"' value='0'>      <td> <input type='number' class='form-control' name='quantity[]' id='quantity"+i+"' placeholder='0' onkeyup='checkQty("+i+")' required> </td> <td><input type='text' class='form-control' onkeyup='checkQty("+i+")'  name='price[]' id='price"+i+"' placeholder='0' required ></td><td><input type='number' class='form-control' name='disc_per[]' id='disc_per"+i+"' placeholder='0' onkeyup='checkQty("+i+")'></td><td><input type='number' class='form-control' name='disc_amt[]' id='disc_amt"+i+"' placeholder='0' onkeyup='checkQty("+i+")'></td><td><input type='number' class='form-control' name='total[]' id='total"+i+"' placeholder='0' readonly ></td><td class=' text-center'><a id='delete_row' name='dlt"+i+"' onclick='dlt("+i+");updateDiscount()' class='pull-right btn btn-danger'><i class='icofont-ui-delete' data-toggle='tooltip' data-placement='top' title='Delete'></i></a></td>");

// Initialize Select2 for the new elements
        $('.mySelect2').select2();
        $('.select2search').select2();
        
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
  $('.select2search').select2();
   $('.select2search').on('focus', function() {
    $(this).select2('open');
});
function dlt(id){
      
      $("#addr"+(id)).html('');
      document.getElementById('id').value=id;

     
  };


function checkQty(i)
{
// alert(i)
    if(i !='0')
        {
            
    var data=document.getElementById('quantity'+i).value *  document.getElementById('price'+i).value;
            if(document.getElementById('disc_per'+i).value>0)
            {
                document.getElementById('disc_amt'+i).value=(data * document.getElementById('disc_per'+i).value)/100;
            }
    document.getElementById('total'+i).value= data-document.getElementById('disc_amt'+i).value;
        }
    
   
        var grand=0;
        var serv_disc=0;
    // }
    if(document.getElementById('id').value==1)
    {
        id=2;
    }
    else{
        id=document.getElementById('id').value;
    }
// alert(id);
    for (let index = 1; index <id; index++) {
        if(document.getElementById('total'+index)!= null && document.getElementById('total'+index).value.length > 0)
        {
            grand =parseFloat(grand)+parseFloat(document.getElementById('total'+index).value);
        }
        if(document.getElementById('disc_amt'+index)!= null && document.getElementById('disc_amt'+index).value.length > 0)
        {
            serv_disc=parseFloat(serv_disc)+parseFloat(document.getElementById('disc_amt'+index).value);
        }
    }
    document.getElementById('gross').value= grand + serv_disc;
    document.getElementById('srv_disc').value= serv_disc ;
    document.getElementById('tot_amt').value= grand ;
    document.getElementById('grand').value= grand - document.getElementById('discount').value ;
    document.getElementById('outstand').value= document.getElementById('grand').value - document.getElementById('paid_amt').value + parseInt(document.getElementById('ret_amt').value);
}
function updateDiscount()
{ 
    var grand=0;
    // }
    if(document.getElementById('id').value==1)
    {
        id=2;
    }
    else{
        id=document.getElementById('id').value;
    }
// alert(id);
    for (let index = 1; index <id; index++) {
        if(document.getElementById('total'+index)!= null && document.getElementById('total'+index).value.length > 0)
        {
            // alert(document.getElementById('total['+index+']').value);
        grand =parseFloat(grand)+parseFloat(document.getElementById('total'+index).value);
        }
    }
    // alert(grand)
    
    document.getElementById('grand').value= grand;
     document.getElementById('tot_amt').value= grand;
     document.getElementById('grand').value=document.getElementById('grand').value-parseFloat(document.getElementById('discount').value);
     document.getElementById('outstand').value= document.getElementById('grand').value - document.getElementById('paid_amt').value + parseInt(document.getElementById('ret_amt').value);
}

 </script>

 <script>
    $(document).ready(function() {
        
     i=document.getElementById('id').value;
     
     checkQty(i)
    } );
    
    
    
    function patientDetail(op)
    {
        // alert(op)
     $.ajax({
      url: 'ajax.php',
        type: 'POST', // Send post data
        data: 'op_no='+op+'&page=ipreg',
        async: false,
        success: function(s){
            // alert(s);
            json_events = s.split("|");
            $("#pname").val(json_events[3]);
            $("#gender").val(json_events[5]);
            $("#age").val(json_events[4]);
            $("#pnum").val(json_events[6]);
            $("#patient_id").val(json_events[0]);
            $("#doc").val(json_events[16]);
            // alert(json_events[30]);
            // $("#doctor option[value="+json_events[30]+"]").attr("selected", "selected");
            // $("#doctor").val(json_events[30]);
            // $("#age").val(json_events[4]);
            
            //  $("#sex").val(json_events[5]);
           // $("#sex option[value="+json_events[5]+"]").attr("selected", "selected");
            // $("#sex option:not(:selected)").attr("disabled", "disabled");
            // $("#address").val(json_events[7]);
           
          }
        });
  }
  
  
   function showResult(str,i)
    {
        // alert(i)
     $.ajax({
      url: 'ajax.php',
        type: 'POST', // Send post data
        data: 'id='+str+'&page=getService',
        async: false,
        success: function(s){
           
            json_events = s.split("|");
            //  alert(json_events[4])
            $("#price"+i).val(json_events[2]);
            $("#service_id"+i).val(json_events[0]);
            if(json_events[4]==0)
            {
                 $("#doctor"+i).val();
                 $("#doctor_id"+i).val(0);
                 $("#doctor"+i).prop('disabled', 'disabled');
            }
            else{
                 $("#doctor"+i).prop('disabled', false);
            }
           
            document.getElementById('quantity'+i).focus();
           
            
           
          }
        });
  }
  function cancelBill(id,i){
      $.ajax({
      url: 'ajax.php',
        type: 'POST', // Send post data
        data: 'id='+id+'&page=cancelBill',
        async: false,
        success: function(s){
       location.reload() ;
           
          }
        });
        checkQty(i);
        updateDiscount();
  }
  
  function getDoctor(str,i)
  {
      $("#doctor_id"+i).val(str);
  }
     </script>

   <script>
 $(document).ready(function() {
    $('.select2search').select2();
    $('.select2doctor').select2();
    
});
     </script>

    <!-- End custom js for this page-->
  </body>
</html>