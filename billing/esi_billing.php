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
    <title>ESI Billing</title>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" ></script>
        <!-- Sweet Alert-->
     <link href="css/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
 

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
  <body onload="patientDetail('<?= $_REQUEST['id']?>')">
  <div id="main-wrapper">

<?php include ('leftbar.php')?>
<div class="content-body">


    <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <div class="main-panel">
                
                <div class="content-wrapper ">
                                    <h3 class="page-title text-dark font-weight-bold mb-2 px-4">ESI BILLING</h3>



          
                                    <form method="post" action="controller/processBilling.php" id="form1">
                                    
            <div class="row mb-4 mt-3">
                <div class="col-md-12">
                 
                            
            <div class="card">
                <div class="card-body">

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">

                    <label for="pname">IPD No</label>
                    <input onchange="javascript:patientDetail(this.value)" class="form-control" type="text"  name="ip_no" id="ip_no" value="<?= $_REQUEST['id']?>" required>
                     <input type="hidden"  name="patient_id" id="patient_id" value="<?= $_REQUEST['id']?>" >
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">

                    <label for="pname">Patient Name</label>
                    <input class="form-control" type="text" readonly name="pname" id="pname" value="" required>

                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">

                    <label for="pname">Age</label>
                    <input class="form-control" type="text" readonly name="age" id="age" value="" required>

                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">

                    <label for="pname">Gender</label>
                    <input class="form-control" type="text" readonly name="gender" id="gender" value="" required>

                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">

                    <label for="pname">Mobile Number</label>
                    <input class="form-control" type="text" readonly name="pnum" id="pnum" value="">

                        </div>
                    </div>
                    <div class="col-md-3">
                <div class="form-group">
                    <label >Doctor <small class="text-danger"> *</small></label>

                    <select required class="form-control select2search" onchange="getDept(this.value)" name="doc" id="doc">
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
                   <div class="col-md-3">
                <div class="form-group">
                    <label >Ward Type<small class="text-danger"> *</small></label>

                    <select required class="form-control text-uppercase select2search" name="ward" id="ward">
                        <option value="">Select Ward</option>
                        <option>GENERAL WARD</option>
                        <option>SEMI SPECIAL WARD</option>
                         <option>SPECIAL WARD</option>
                         <option>DELUX WARD</option>
                         <option>EMERGENCY WARD</option>
                         <option>LABOUR WARD</option>
                         <option>ISOLATION WARD</option>
                       <option>SUITE ROOM</option>
                        <option>ICU WARD</option>
                         <option>ITU WARD</option>
                         <option>HDU WARD</option>
                         <option>SINGLE AC ROOM</option>
                    </select>

                </div>
         </div>
                  <input type="hidden" name="patient_type" id="patient_type" value="IP">
                  <input type="hidden" name="service_type" id="service_type" value="IP">

                            <div class="col-md-3">
                   <div class="form-group">
                      <label for="date">Billing Date</label>
                      <input class="form-control" type="datetime-local" name="date" id="date" value="<?php echo date("Y-m-d H:i:s"); ?>" >
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
               <th>ESI Code</th>
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
                  
                <select name="service[]" id="service1" class="form-control select2doctor" required onchange="showResult(this.value,1)">
                    <option  value="">Select Service</option>
                    <?php $res=$newBill->getESItems();
                    while($row=mysqli_fetch_assoc($res))
                    { ?>
                    <option value="<?=$row['id']?>"><?=$row['name']?></option>
                    <?php } ?>
                </select>
               </td>
                <td>
                    <input type="text" class="form-control" name="esi[]" id="esi1" readonly>
                </td>
               
                <td>
                    <input type="number" class="form-control" name="quantity[]" id="quantity1" value="1" onkeyup="checkQty(1)" required>
                    
                </td>
                    <input type="hidden" class="form-control" name="service_id[]" id="service_id1">
                    <input type="hidden" class="form-control" name="doctor_id[]" id="doctor_id1" value="0">
                
                <td>
                    <input type="number" class="form-control" name="price[]" id="price1" placeholder="0" onkeyup="checkQty(1)" required>
                </td>
                <td>
                    <input type="number" class="form-control" name="disc_per[]" id="disc_per1" placeholder="0" onkeyup="checkQty(1)" >
                </td>
                <td>
                    <input type="number" class="form-control" name="disc_amt[]" id="disc_amt1" placeholder="0" onkeyup="checkQty(1)" >
                </td>
             
                <td>
                    <input type="text" class="form-control" name="total[]" id="total1" placeholder="0" readonly>
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
                <input type="text" class="form-control" name="gross" id="gross" placeholder="0.00" value="0.00" readonly >
                </td>
                 <td class="">
                    <button type="button" id="add_row" class="btn btn-success">Add Row</button>
                </td>
            </tr>
            <tr >

                <td colspan="5" > </td>
                <td> Service Level Discount </td>
                <td>
                <input type="text" class="form-control" name="srv_disc" id="srv_disc" placeholder="0.00" value="0.00" readonly >
                </td>
                
            </tr>
            <tr >

                <td colspan="5" > </td>
                <td> Total Amount </td>
                <td>
                <input type="text" class="form-control" name="tot_amt" id="tot_amt" placeholder="0.00" value="0.00" readonly >
                </td>
                
            </tr>
         
            
            <tr>
                <td colspan="5" > </td>
                <td> Discount Amount </td>
                <td>
                <input type="text" class="form-control" name="discount" id="discount" onkeyup="updateDiscount()" placeholder="0.00" value="0" >
                </td>
            </tr>
            <tr>
                <td colspan="5" > </td>
                <td> Grand Total Amount </td>
                <td>
                <input type="text" class="form-control" name="grand" id="grand" placeholder="0.00" value="0" readonly>
                </td>
            </tr>
            <tr id="adjust" style="display:none">
                <td colspan="5" > </td>
                <td> Adjust Amount </td>
                <td>
                <input type="text" class="form-control" name="adj" id="adj"  placeholder="0.00" value="0" >
                </td>
              
            </tr>
       
            <tr >

                <td colspan="5" > </td>
                <td> Outstanding amount </td>
                <td>
               <input type="number" class="form-control" name="outstand" id="outstand" placeholder="0.00"  onkeyup="updateDiscount()"  >
                </td>
               
            </tr>
       
           
        <input type="hidden" name="id" id="id" value=1>
         <input type="hidden" name="bill_no" id="bill_no" value="">

            <!-- <tr>
                <td colspan="8" class="">  <button type="submit" class="btn btn-success text-white  d-flex justify-content-end" name="submit" value="submit">Save</button></td>
               
                
            </tr> -->
        </tfoot>
    </table>
     <div class="col-md-12 d-flex justify-content-end m-4 ">
         <input type="hidden" name="submit"  value="esi_bill">
    <button type="submit" id="submitBtn" class="btn btn-primary  font-weight-normal">Save</button>
    <input type="hidden" name="final_bill" id="final_bill" value="1">
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
   $("#addr"+i).html("<td class='col-md-3'><select name='service[]' id='service"+i+"' class='form-control mySelect2' required onchange='showResult(this.value,"+i+")'><option>Select Service</option><?php $res=$newBill->getESItems();while($row=mysqli_fetch_assoc($res)){ ?><option value='<?=$row['id']?>'><?=$row['name']?></option><?php } ?></select></td>  <td><input type='text' class='form-control' name='esi[]' id='esi"+i+"' readonly></td>  <input type='hidden' class='form-control' name='service_id[]' id='service_id"+i+"'>  <input type='hidden' class='form-control' name='doctor_id[]'' id='doctor_id"+i+"' value='0'>      <td> <input type='number' class='form-control' name='quantity[]' id='quantity"+i+"' value='1' onkeyup='checkQty("+i+")' required> </td> <td><input type='text' class='form-control' onkeyup='checkQty("+i+")'  name='price[]' id='price"+i+"' placeholder='0' required ></td><td><input type='number' class='form-control' name='disc_per[]' id='disc_per"+i+"' placeholder='0' onkeyup='checkQty("+i+")'></td><td><input type='number' class='form-control' name='disc_amt[]' id='disc_amt"+i+"' placeholder='0' onkeyup='checkQty("+i+")'></td><td><input type='number' class='form-control' name='total[]' id='total"+i+"' placeholder='0' readonly ></td><td class=' text-center'><a id='delete_row' name='dlt"+i+"' onclick='dlt("+i+")' class='pull-right btn btn-danger'><i class='icofont-ui-delete' data-toggle='tooltip' data-placement='top' title='Delete'></i></a></td>");
        $('.mySelect2').select2();
        $('.select2search').select2();

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
//   document.getElementById('id').value=i;

});
function dlt(id){
      
      $("#addr"+(id)).html('');
    //   document.getElementById('id').value=id;

     checkQty(id-1);
  };

function disableSubmitButton(button) {
    button.disabled = true;
  }
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
    
    var grand=0;var advance=0;
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
    // alert(grand)
    document.getElementById('grand').value= grand;
     document.getElementById('grand').value=document.getElementById('grand').value-parseFloat(document.getElementById('discount').value);
     if(document.getElementById('checkboxId').checked)
     {
         advance=document.getElementById('advance').value;
     }
     document.getElementById('outstand').value= document.getElementById('grand').value - advance;
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
        if (op == null || !op) {
        return; // exits the function
    }
     $.ajax({
      url: 'ajax.php',
        type: 'POST', // Send post data
        data: 'ip_no='+op+'&page=transfer_bed',
        async: false,
        success: function(s){
            // alert(s);
            json_events = s.split("|");
            if(json_events[31]==1)
            {
            // alert('Final Bill Already Done')
            $("button[name='submit'][value='final']").hide();
            $("button[name='submit'][value='op_patho']").hide();
            }
            $("#pname").val(json_events[5]);
            $("#gender").val(json_events[7]);
            $("#age").val(json_events[6]);
            $("#pnum").val(json_events[8]);
            $("#patient_id").val(json_events[2]);
            // $("#doc").val(json_events[18]);
            $("#advance").val(json_events[32]);
            $("#adv_id").val(json_events[33]);
            var select = document.getElementById("doc");
            // $("#doc").val(json_events[16]);
             for (var i = 0; i < select.options.length; i++) {
              if (select.options[i].value === json_events[18]) {
                select.options[i].selected = true;
                break;
              }
             }
            $(select).trigger('change');
           if(json_events[34]==0)
           {
               var select1 = document.getElementById("service1");
               select1.value=1;
            // $("#doc").val(json_events[16]);
          
                 $(select1).trigger('change');
           }
        //   alert(json_events[11])
           var select2 = document.getElementById("dept");
             for (var i = 0; i < select2.options.length; i++) {
              if (select2.options[i].value === json_events[11]) {
                select2.options[i].selected = true;
                break;
              }
             }
            $(select2).trigger('change');
          }
        });
        
        $.ajax({
      url: 'ajax.php',
        type: 'POST', // Send post data
        data: 'ip_no='+op+'&page=getPatientBill',
        async: false,
        success: function(s){
           json_events = s.split("|");
           $("#tab_logic").html(json_events[0]);
           i=json_events[1]
                $('#service'+i).focus();
               document.getElementById('id').value=i;
               document.getElementById('bill_no').value=json_events[2];
        }
    });
     checkQty(i-1);     
        
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
            $("#price"+i).val(json_events[8]);
            $("#service_id"+i).val(json_events[0]);
            $("#esi"+i).val(json_events[7]);
                 $("#doctor_id"+i).val(0);
            
            document.getElementById('quantity'+i).focus();
           
            
           
          }
        });
        checkQty(i)
  }
  
  function getDoctor(str,i)
  {
      $("#doctor_id"+i).val(str);
  }
  
  
  function calAdjust()
  {
     document.getElementById('adj').value=document.getElementById('advance').value;
     
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
     
      
    var checkbox = document.getElementById("checkboxId");
    var row = document.getElementById("adjust");

    if (checkbox.checked) {
        document.getElementById('outstand').value= document.getElementById('grand').value  - document.getElementById('advance').value;
      row.style.display = "table-row";
    } else {
        document.getElementById('outstand').value= document.getElementById('grand').value  ;
      row.style.display = "none";
    }
  
     
    //  document.getElementById("adjust").style.display='block';
     
     
  }
  
let isSubmitting = false;
$('#form1').on('submit', function (e) {

  if (isSubmitting) {
    e.preventDefault();   // stop second submit
    return false;
  }

  isSubmitting = true;

  const btn = $('#submitBtn');
  btn.prop('disabled', true).text('Submitting...');
});
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