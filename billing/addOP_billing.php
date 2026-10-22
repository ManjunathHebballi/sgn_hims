<?php 

require_once(__DIR__ . '/../model/session_config.php');
include('model/billingClass.php');
$newBill = new addBilling;
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>OP Billing</title>
    <!-- base:css -->

    <link rel="stylesheet" href="css/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="images/favicon.png" />
  
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" ></script>
        <!-- Sweet Alert-->
     <link href="css/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
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
  <body onload="patientDetail('<?= $_REQUEST['opd']?>')">
     
  <div id="main-wrapper">
<?php include ('leftbar.php')?>
<div class="content-body">
       <div class="container-fluid page-body-wrapper">
            <div class="main-panel">
                <div class="content-wrapper ">
            <div class="row">
                                      <div class="col-md-8">
                                             <h3 class="page-title text-dark font-weight-bold mb-2 px-4">OP BILLING</h3>
                                      </div>
                                       <div class="col-md-4">
                          
                                  <div class="form-group">
                                      
<div class="input-group mb-3">
  <input list="searchpatients" class="form-control text-uppercase" name="searchpatient" id="searchpatient" oninput="getPatient(this.value)" onchange="getOPDdetails(this.value)" placeholder="Search by Patient Name,Patient Number">

<datalist id="searchpatients">
   
                       </datalist>
  <div class="input-group-append">
   <a href="../opregistration.php" class="btn btn-success" name="search">Add New</a>
  </div>
</div>
                                          

                                  </div>

                         </div>
                                  </div>
            <form method="post" action="controller/processBilling.php" id="form1">
                                    
            <div class="row mb-4 mt-3">
                <div class="col-md-12">
                 
                            
            <div class="card">
                <div class="card-body">

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">

                    <label for="pname">OPD No</label>
                    <input onchange="javascript:patientDetail(this.value)" class="form-control text-uppercase" type="text"  name="ip_no" id="ip_no" value="<?= $_REQUEST['opd']?>" required>
                     <input type="hidden"  name="patient_id" id="patient_id" value="" >
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">

                    <label for="pname">Patient Name</label>
                    <input class="form-control text-uppercase" type="text" readonly name="pname" id="pname" value="" required>

                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">

                    <label for="pname">Age</label>
                    <input class="form-control text-uppercase" type="text" readonly name="age" id="age" value="" required>

                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">

                    <label for="pname">Gender</label>
                    <input class="form-control text-uppercase" type="text" readonly name="gender" id="gender" value="" required>

                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">

                    <label for="pname">Mobile Number</label>
                    <input class="form-control text-uppercase" type="text" readonly name="pnum" id="pnum" value="">

                        </div>
                    </div>
                    <div class="col-md-3">
                <div class="form-group">
                    <label >Doctor <small class="text-danger"> *</small></label>

                    <select required class="form-control text-uppercase select2search" onchange="getDept(this.value)" name="doc" id="doc">
                        <option value="">Select Doctor</option>
                        <?php $result=$newBill->selectDoctor();
                        while($row=mysqli_fetch_assoc($result))
                        { ?>
                        <option value="<?php echo $row['name']." ".$row['last_name']; ?>"><?php echo $row['name']." ".$row['last_name']; ?></option>
                        
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
                        <option value="<?php echo $row['id'] ?>"><?php echo $row['dept_name']; ?></option>
                        
                        <?php } ?>
                       
                    </select>

                </div>
         </div>

                  
                  <input type="hidden" name="patient_type" id="patient_type" value="OP">
                  <input type="hidden" name="service_type" id="service_type" value="OP">

                            <div class="col-md-3">
                   <div class="form-group">
                      <label for="date">Billing Date</label>
                      <input class="form-control text-uppercase" type="datetime-local" name="date" id="date" value="<?php echo date("Y-m-d H:i:s"); ?>" >
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
            

                <table class="table table-bordered"   >
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
            <tr id='addr0'>
                
              <td class="col-md-3">
                  
                <select name="service[]" id="service1" class="form-control text-uppercase select2search" required onchange="showResult(this.value,1)">
                    <option  value="">Select Service</option>
                    <?php $res=$newBill->getOItems();
                    while($row=mysqli_fetch_assoc($res))
                    { ?>
                    <option value="<?=$row['id']?>"><?=$row['name']?></option>
                    <?php } ?>
                </select>
               </td>
               <td> 
                  <select required class="form-control text-uppercase select2search" name="doctor[] " id="doctor1" onchange="getDoctor(this.value,1)">
                        <option value="">Select Doctor</option>
                        <?php $result=$newBill->selectDoctor();
                        while($row=mysqli_fetch_assoc($result))
                        { ?>
                        <option value="<?php echo $row['id']?>"><?php echo $row['name']." ".$row['last_name']; ?></option>
                        
                        <?php } ?>
                       
                    </select>
             </td>
               
                <td>
                    <input type="number" class="form-control text-uppercase" name="quantity[]" id="quantity1" value="1" onkeyup="checkQty(1)" required>
                    
                </td>
                    <input type="hidden" class="form-control text-uppercase" name="service_id[]" id="service_id1">
                    <input type="hidden" class="form-control text-uppercase" name="doctor_id[]" id="doctor_id1" value="0">
                <td>
                    <input type="number" class="form-control text-uppercase" name="price[]" id="price1" placeholder="0" onkeyup="checkQty(1)" required>
                </td>
                <td>
                    <input type="number" class="form-control text-uppercase" name="disc_per[]" id="disc_per1" placeholder="0" onkeyup="checkQty(1)">
                </td>
                <td>
                    <input type="number" class="form-control text-uppercase" name="disc_amt[]" id="disc_amt1" placeholder="0" onkeyup="checkQty(1)">
                </td>
             
                <td>
                    <input type="text" class="form-control text-uppercase" name="total[]" id="total1" placeholder="0" readonly>
                <!--<input type="hidden" name="medicineid1" id="medicineid1" value=''>-->

                </td>
                <td class=" text-center">
               <a class="pull-right btn btn-danger"  id='delete_row' name='dlt1' onclick='dlt(1)' >  <i class="icofont-ui-delete "  data-toggle="tooltip" data-placement="top" title="Delete"></i></a>
                </td>
            </tr>
            <tr  id='addr2'></tr>
            </tbody>
            <!-- </table>
            <table class="table table-responsive table-bordered"   id="tab_logic"> -->
            <tfoot> 
            <!-- <tr id='addr110'></tr> -->
            <tr >

                <td colspan="5" > </td>
                <td> Gross Amount </td>
                <td>
                <input type="text" class="form-control text-uppercase" name="gross" id="gross" placeholder="0.00" value="0.00" readonly >
                </td>
                 <td class="">
                    <button type="button" id="add_row" class="btn btn-success">Add Row</button>
                </td>
            </tr>
            <tr >

                <td colspan="5" > </td>
                <td> Service Level Discount </td>
                <td>
                <input type="text" class="form-control text-uppercase" name="srv_disc" id="srv_disc" placeholder="0.00" value="0.00" readonly >
                </td>
                
            </tr>
            <tr >

                <td colspan="5" > </td>
                <td> Total Amount </td>
                <td>
                <input type="text" class="form-control text-uppercase" name="tot_amt" id="tot_amt" placeholder="0.00" value="0.00" readonly >
                </td>
                
            </tr>
         
            
            <tr>
                <td colspan="5" > </td>
                <td> Discount Amount <input type='number' style="width: 60px;" class="form-control  form-control-sm d-inline-block"  onkeyup="discountAmt(this.value)" id="disc_per" placeholder="DISC%"></td></td>
                
                <td>
                <input type="text" class="form-control text-uppercase" name="discount" id="discount" onkeyup="updateDiscount()" placeholder="0.00" value="0" >
                </td>
            </tr>
            <tr>
                <td colspan="5" > </td>
                <td> Grand Total Amount </td>
                <td>
                <input type="text" class="form-control text-uppercase" name="grand" id="grand" placeholder="0.00" value="0" readonly>
                </td>
            </tr>
            
            <tr >

                <td colspan="5" > </td>
                <td> Outstanding amount </td>
                <td>
                <input type="number" class="form-control text-uppercase" name="outstand" id="outstand" placeholder="0.00" min="0"  onkeyup="updateDiscount()"  >
                </td>
               
            </tr>
       
       
               
           
        <input type="hidden" name="id" id="id" value=1>
         

            <!-- <tr>
                <td colspan="8" class="">  <button type="submit" class="btn btn-success text-white  d-flex justify-content-end" name="submit" value="submit">Save</button></td>
               
                
            </tr> -->
        </tfoot>
    </table>
     <div class="col-md-12 d-flex justify-content-end m-4 ">
         <input type="hidden" name="submit" value="op_patho"> 
    <button type="submit"  id="submitBtn" class="btn btn-success  font-weight-normal">Save</button>
</div>          



                </form>
            </div>
        </div>

    </div>
</div>




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

    
        <script src="./vendor/select2/js/select2.full.min.js"></script>
    <script src="./js/plugins-init/select2-init.js"></script>

    <!-- endinject -->
    <script>
        $(document).ready(function(){
   var i=2;
  $("#add_row").click(function(){
    var i=parseInt(document.getElementById('id').value);
//  alert(i)        
   $("#addr"+i).html("<td class='col-md-3'><select name='service[]' id='service"+i+"' class='form-control text-uppercase mySelect2' required onchange='showResult(this.value,"+i+")'><option value=''>Select Service</option><?php $res=$newBill->getOItems();while($row=mysqli_fetch_assoc($res)){ ?><option value='<?=$row['id']?>'><?=$row['name']?></option><?php } ?></select></td> <td><select required class='form-control text-uppercase select2search' name='doctor[]' id='doctor"+i+"' onchange='getDoctor(this.value,"+i+")'><option value=''>Select Doctor</option><?php $result=$newBill->selectDoctor();while($row=mysqli_fetch_assoc($result)){ ?><option value='<?php echo $row['id'] ?>'><?php echo $row['name'].' '.$row['last_name']; ?></option><?php } ?></select></td>  <input type='hidden' class='form-control text-uppercase' name='service_id[]' id='service_id"+i+"'>  <input type='hidden' class='form-control text-uppercase' name='doctor_id[]'' id='doctor_id"+i+"' value='0'>      <td> <input type='number' class='form-control text-uppercase' name='quantity[]' id='quantity"+i+"' value='1' onkeyup='checkQty("+i+")' required> </td> <td><input type='text' class='form-control text-uppercase' onkeyup='checkQty("+i+")'  name='price[]' id='price"+i+"' placeholder='0' required ></td><td><input type='number' class='form-control text-uppercase' name='disc_per[]' id='disc_per"+i+"' placeholder='0' onkeyup='checkQty("+i+")'></td><td><input type='number' class='form-control text-uppercase' name='disc_amt[]' id='disc_amt"+i+"' placeholder='0' onkeyup='checkQty("+i+")'></td><td><input type='number' class='form-control text-uppercase' name='total[]' id='total"+i+"' placeholder='0' readonly ></td><td class=' text-center'><a id='delete_row' name='dlt"+i+"' onclick='dlt("+i+")' class='pull-right btn btn-danger'><i class='icofont-ui-delete' data-toggle='tooltip' data-placement='top' title='Delete'></i></a></td>");


   // Initialize Select2 only for the newly added elements
        $('#service'+i).select2();
        $('#doctor'+i).select2();
   $('#tab_logic').append('<tr id="addr'+(i+1)+'"></tr>');
   $('#service'+i).focus();
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

     checkQty(id-1);
     
  };


function checkQty(i)
{
    // alert(document.getElementById('quantity'+i).value)
    //     alert(document.getElementById('available'+i).value)
   

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
   var inputs = document.getElementsByName('total[]');
    for (var i = 0; i < inputs.length; i++) {
       
            // alert(document.getElementById('total['+index+']').value);
        grand =parseFloat(grand)+parseFloat(inputs[i].value);
        
       
    }
    var index = document.getElementsByName('disc_amt[]');
    for (var i = 0; i < index.length; i++) {
       if(index[i]!= null && index[i].value.length > 0)
        {
            // alert(document.getElementById('total['+index+']').value);
        serv_disc =parseFloat(serv_disc)+parseFloat(index[i].value);
        }
       
    }
    // alert(grand)
    document.getElementById('gross').value= grand + serv_disc;
    document.getElementById('srv_disc').value= serv_disc ;
    document.getElementById('tot_amt').value= grand ;
    document.getElementById('grand').value= grand - document.getElementById('discount').value ;
    document.getElementById('outstand').value= document.getElementById('grand').value ;
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
     document.getElementById('grand').value=document.getElementById('grand').value-parseFloat(document.getElementById('discount').value);
     document.getElementById('outstand').value= document.getElementById('grand').value;
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
        if(document.getElementById('total'+index) != null && document.getElementById('total'+index).value.length > 0)
        {
                grand =parseFloat(grand)+parseFloat(document.getElementById('total'+index).value);
            
        }
    }
  
    document.getElementById('grand').value= grand - document.getElementById('indiscount').value;
}

 </script>

 <script>
    $(document).ready(function() {
        var table = $('#example').DataTable( {
            lengthChange: false,
            buttons: [ 'copy', 'excel', 'csv', 'pdf','print', 'colvis' ]
        } );
     
        table.buttons().container()
            .appendTo( '#example_wrapper .col-md-6:eq(0)' );
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
            var select = document.getElementById("doc");
            // $("#doc").val(json_events[16]);
             for (var i = 0; i < select.options.length; i++) {
              if (select.options[i].value === json_events[16]) {
                select.options[i].selected = true;
                break;
              }
             }
            $(select).trigger('change');
            
            
             var select2 = document.getElementById("dept");
            
             for (var i = 0; i < select2.options.length; i++) {
              if (select2.options[i].value === json_events[9]) {
                select2.options[i].selected = true;
                break;
              }
             }
            $(select2).trigger('change');
          }
        });
  }
  
   function getDept(val)
  {
      $.ajax({
      url: 'ajax.php',
        type: 'POST', // Send post data
        data: 'id='+encodeURIComponent(val)+'&page=getDept',
        async: false,
        success: function(s){
          
           var select = document.getElementById("dept");
             for (var i = 0; i < select.options.length; i++) {
              if (select.options[i].value === s) {
                select.options[i].selected = true;
                break;
              }
             }
            $(select).trigger('change');
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
                if(json_events[0]=='18')
                {
                    id=$("#doc").val();
                    // alert(id)
                 var select = document.getElementById("doctor"+i);
                     for (var j = 0; j < select.options.length; j++) {
                        //  console.log(select.options[j].text)
                      if (select.options[j].text == id) {
                        select.options[j].selected = true;
                        break;
                      }
                     }
                     $(select).trigger('change');
                    //  console.log(select.value);
                 $("#doctor_id"+i).val(select.value);
                }
                
                
            }
           
            document.getElementById('quantity'+i).focus();
           
            
           
          }
        });
        checkQty(i);
  }
  
  function getDoctor(str,i)
  {
      $("#doctor_id"+i).val(str);
  }
     </script>



        <script>
 $(document).ready(function() {
    // Initialize Select2 with check to prevent duplicate initialization
    $('.select2search').each(function() {
        if (!$(this).hasClass('select2-hidden-accessible')) {
            $(this).select2();
        }
    });
    
    $('.select2doctor').each(function() {
        if (!$(this).hasClass('select2-hidden-accessible')) {
            $(this).select2();
        }
    });
    
    // Open on focus
    $(document).on('focus', '.select2search', function() {
        if (!$(this).hasClass('select2-hidden-accessible')) {
            $(this).select2();
        }
        $(this).select2('open');
    });
    
    $(document).on('focus', '.select2doctor', function() {
        if (!$(this).hasClass('select2-hidden-accessible')) {
            $(this).select2();
        }
        $(this).select2('open');
    });
});
function getOPDdetails(val)
    {
        data=val.split("-");
        patientDetail(data[0]);
        $('#ip_no').val(data[0]);
    }  
async function getPatient(val) {
  if (val.length > 4) {
    try {
      const response = await $.ajax({
        url: 'ajax.php',
        type: 'POST',
        data: 'search=' + val + '&page=getPatient',
      });

      if (response.length > 0) {
        $('#searchpatients').html(response);
      } else {
        $('#searchpatients').html("<option value=''>NO Result Found, add New</option>");
      }
    } catch (error) {
      console.error('Error in AJAX request:', error);
    }
  } else {
    $('#searchpatients').html("");
  }
}
let isSubmitting = false;

function lockBillingInputsForSubmit() {
    // Freeze values exactly as user sees them before form posts.
    $("select[name='service[]']").each(function () {
        $(this).css('pointer-events', 'none').attr('tabindex', '-1');
    });

    $("input[name='quantity[]'], input[name='price[]'], input[name='disc_per[]'], input[name='disc_amt[]'], input[name='discount'], input[name='paid_amt'], input[name='outstand']").each(function () {
        $(this).prop('readonly', true);
    });
}

// Prevent accidental mouse-wheel value changes in focused number inputs.
$(document).on('wheel', "input[type='number']", function () {
    $(this).blur();
});

$('#form1').on('submit', function (e) {

  if (isSubmitting) {
    e.preventDefault();   // stop second submit
    return false;
  }

  isSubmitting = true;
    lockBillingInputsForSubmit();

  const btn = $('#submitBtn');
  btn.prop('disabled', true).text('Submitting...');
});
 function discountAmt(val)
{
    let total=$('#tot_amt').val();
    let disc=(total*val/100)
    $('#discount').val(disc);
    updateDiscount();
}
     </script>
    <!-- End custom js for this page-->
  </body>
</html>