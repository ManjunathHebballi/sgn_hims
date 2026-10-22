<?php 

require_once(__DIR__ . '/model/session_config.php');
include('model/billingClass.php');
$newBill = new addBilling;
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Pathology Billing</title>
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
  </head>
  <body>
      
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
      
      
      
  <div id="main-wrapper">

<?php include ('leftbar.php')?>
<div class="content-body">


    <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <div class="main-panel">
                
                <div class="content-wrapper ">
                                  <div class="row">
                                      <div class="col-md-8">
                                            <h4 class="page-title text-dark font-weight-bold mb-2 ">PATHOLOGY BILLING</h4>
                                      </div>
                                  </div>
                        


          
                                    <form method="post" action="controller/processBilling.php" >
                                    
            <div class="row mb-4 mt-3">
                <div class="col-md-12">
                 
                            
            <div class="card">
                <div class="card-body">

                <div class="row">
                   
                    <div class="col-md-3">
                        <div class="form-group">

                    <label for="pname">Patient Name</label>
                    <input class="form-control" type="text"  name="pname" id="pname" value="" required>

                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">

                    <label for="pname">Age</label>
                    <input class="form-control" type="text"  name="age" id="age" value="" required>

                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">

                    <label for="pname">Gender</label>
                    <select class="form-control"  name="gender" id="gender" required>
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">

                    <label for="pname">Mobile Number</label>
                    <input class="form-control" type="text"  name="pnum" id="pnum" value="">

                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">

                    <label for="pname">Addresss</label>
                    <input class="form-control" type="text"  name="address" id="address" value="">

                        </div>
                    </div>
                    <div class="col-md-3">
                <div class="form-group">
                    <label >Doctor <small class="text-danger"> *</small></label>

                    <select required class="form-control select2search"  onchange="getDept(this.value)" name="doc" id="doc">
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
                <label >Payment Mode <small class="text-danger"> *</small></label>

                <select required class="form-control" name="payment_mode" id="payment_mode">
                    <option value="">Payment Mode</option>
                    <option value="Cash">Cash</option>
                    <option value="UPI">UPI</option>
                    <option value="Cheque">Cheque</option>
                </select>

            </div>
         </div>
                  

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
                  
                <select name="service[]" id="service1 " class="form-control select2search" required onchange="showResult(this.value,1)">
                    <option>Select Service</option>
                    <?php $res=$newBill->getPItems();
                    while($row=mysqli_fetch_assoc($res))
                    { ?>
                    <option value="<?=$row['testid']?>"><?=$row['testname']?></option>
                    <?php } ?>
                </select>
               </td>
               
                <td>
                    <input type="number" class="form-control" name="quantity[]" id="quantity1" value="1" onkeyup="checkQty(1)" required>
                    
                </td>
                    <input type="hidden" class="form-control" name="service_id[]" id="service_id1">
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

                <td colspan="4" > </td>
                <td> Gross Amount </td>
                <td>
                <input type="text" class="form-control" name="gross" id="gross" placeholder="0.00" value="0.00" readonly >
                </td>
                 <td class="">
                    <button type="button" id="add_row" class="btn btn-success">Add Row</button>
                </td>
            </tr>
            <tr >

                <td colspan="4" > </td>
                <td> Service Level Discount </td>
                <td>
                <input type="text" class="form-control" name="srv_disc" id="srv_disc" placeholder="0.00" value="0.00" readonly >
                </td>
                
            </tr>
            <tr >

                <td colspan="4" > </td>
                <td> Total Amount </td>
                <td>
                <input type="text" class="form-control" name="tot_amt" id="tot_amt" placeholder="0.00" value="0.00" readonly >
                </td>
                
            </tr>
         
            
            <tr>
                <td colspan="4" > </td>
                <td> Discount Amount </td>
                <td>
                <input type="text" class="form-control" name="discount" id="discount" onkeyup="updateDiscount()" placeholder="0.00" value="0" >
                </td>
            </tr>
            <tr>
                <td colspan="4" > </td>
                <td> Grand Total Amount </td>
                <td>
                <input type="text" class="form-control" name="grand" id="grand" placeholder="0.00" value="0" readonly>
                </td>
            </tr>
            <tr>
                <td colspan="4" > </td>
                <td> Amount Paid </td>
                <td>
                <input type="text" class="form-control" name="paid_amt" id="paid_amt" onkeyup="updateDiscount()" placeholder="0.00" value="0" >
                </td>
            </tr>
            <tr >

                <td colspan="4" > </td>
                <td> Outstanding amount </td>
                <td>
               <input type="number" class="form-control" name="outstand" id="outstand" placeholder="0.00" min="0" max="0" onkeyup="updateDiscount()"  >
                </td>
               
            </tr>
       
       
               
           
        <input type="hidden" name="id" id="id" value=1>
         

            <!-- <tr>
                <td colspan="8" class="">  <button type="submit" class="btn btn-success text-white  d-flex justify-content-end" name="submit" value="submit">Save</button></td>
               
                
            </tr> -->
        </tfoot>
    </table>
     <div class="col-md-12 d-flex justify-content-end m-4 ">
    <button type="submit" name="submit" id="submit" value="op_patho" class="btn btn-success  font-weight-normal">Save</button>
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

<script src="./vendor/datatables/js/jquery.dataTables.min.js"></script>
<script src="./js/plugins-init/datatables.init.js"></script>
<script src="./js/plugins-init/datatables.init.js"></script>


    
        <script src="./vendor/select2/js/select2.full.min.js"></script>
    <script src="./js/plugins-init/select2-init.js"></script>
    <script>
        $(document).ready(function(){
   var i=2;
  $("#add_row").click(function(){
    var i=parseInt(document.getElementById('id').value);
//  alert(i)        
   $("#addr"+i).html("<td class='col-md-3'><select name='service[]' id='service"+i+"' class='form-control mySelect2' required onchange='showResult(this.value,"+i+")'><option>Select Service</option><?php $res=$newBill->getPItems();while($row=mysqli_fetch_assoc($res)){ ?><option value='<?=$row['testid']?>'><?=$row['testname']?></option><?php } ?></select></td> <input type='hidden' class='form-control' name='service_id[]' id='service_id"+i+"'>  <td> <input type='number' class='form-control' name='quantity[]' id='quantity"+i+"' value='1' onkeyup='checkQty("+i+")' required> </td> <td><input type='text' class='form-control' onkeyup='checkQty("+i+")'  name='price[]' id='price"+i+"' placeholder='0' required ></td><td><input type='number' class='form-control' name='disc_per[]' id='disc_per"+i+"' placeholder='0' onkeyup='checkQty("+i+")'></td><td><input type='number' class='form-control' name='disc_amt[]' id='disc_amt"+i+"' placeholder='0' onkeyup='checkQty("+i+")' ></td><td><input type='number' class='form-control' name='total[]' id='total"+i+"' placeholder='0' readonly ></td><td class=' text-center'><a id='delete_row' name='dlt"+i+"' onclick='dlt("+i+")' class='pull-right btn btn-danger'><i class='icofont-ui-delete' data-toggle='tooltip' data-placement='top' title='Delete'></i></a></td>");



// Initialize Select2 for the new elements
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
  document.getElementById('id').value=i;

});
function dlt(id){
      
      $("#addr"+(id)).html('');
      document.getElementById('id').value=id;

   checkQty(id-1);
  };

function disableSubmitButton() {
    document.getElementById('submit').disabled = true;
  }
   function getDept(val)
  {
      $.ajax({
      url: 'ajax.php',
        type: 'POST', // Send post data
        data: 'id='+val+'&page=getDept',
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
    document.getElementById('outstand').value= document.getElementById('grand').value - document.getElementById('paid_amt').value ;
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
  var inputs = document.getElementsByName('total[]');
    for (var i = 0; i < inputs.length; i++) {
       
            // alert(document.getElementById('total['+index+']').value);
        grand =parseFloat(grand)+parseFloat(inputs[i].value);
        
       
    }
    // alert(grand)
    document.getElementById('grand').value= grand;
     document.getElementById('grand').value=document.getElementById('grand').value-parseFloat(document.getElementById('discount').value);
     document.getElementById('outstand').value= document.getElementById('grand').value - document.getElementById('paid_amt').value ;
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
   var inputs = document.getElementsByName('total[]');
    for (var i = 0; i < inputs.length; i++) {
       
            // alert(document.getElementById('total['+index+']').value);
        grand =parseFloat(grand)+parseFloat(inputs[i].value);
        
       
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
            $("#price"+i).val(json_events[2]);
            $("#service_id"+i).val(json_events[0]);
           
           
            document.getElementById('quantity'+i).focus();
           
            
           
          }
        });
         checkQty(i);
  }
 
     </script>


   <script>
 $(document).ready(function() {
    $('.select2search').select2();
    
});


     </script>

    <!-- End custom js for this page-->
  </body>
</html>