<?php
require_once(__DIR__ . '/model/session_config.php');
include('model/radiologyClass.php');
$newRadio = new addRadio;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Radiology OP Billing</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">



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

            <div class="position-fixed bottom-0 right-0 p-3" style="z-index: 5; right: 0; bottom: 0;">
  <div id="liveToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true" data-delay="4000">
    <div class="toast-header bg-success text-light">
    <i class="ri-checkbox-circle-line mr-3"></i>
      <strong class="mr-auto">Bill Collected Successfully</strong> <br>
    
      <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="toast-body">
<p class="text-black">     Total Rs. 125 is Collected. </p>
    </div>
  </div>
</div>


<div class="container-fluid">
    <form method="POST" action="controller/processRadiology.php">
    <div class="row mb-3">
        <div class="col-md-12">
            <h4 class="text-uppercase"> Radiology OP Billing</h4>
        </div>
    </div>

    <div class="row">
       <div class="col-md-12">
       <div class="card">
            <div class="card-body">
                <div class="row">
                <div class="col-md-2">
                        <div class="form-group">
                            <label for="">OPD ID</label>
                            <input type="text" onkeyup="javascript:patientDetail(this.value)" class="form-control" name="opdid" id="opdid"   >
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="">Patient Name</label>
                            <input type="text" class="form-control" name="pname" id="pname"   >
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="">Gender</label>
                            <input type="text" class="form-control" name="gender" id="gender"  >
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="">Age</label>
                            <input type="text" class="form-control" name="age" id="age"  >
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="">Department</label>
                            <input type="text" class="form-control" name="dept" id="dept"   >
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="">Doctor</label>
                            <input type="text" class="form-control" name="doctor" id="doctor"   >
                        </div>
                    </div>
                     <div class="col-md-2">
                        <div class="form-group">
                            <label for="">Payment Mode</label>
                           <select name="payment_mode" class="form-control"><option value="Cash">Cash</option><option value="Online">Online</option></select>
                        </div>
                    </div>
                     <div class="col-md-2">
                        <div class="form-group">
                            <label for="">Note</label>
                            <input type="text" class="form-control"  name="note" id="note" >
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
       </div>
    </div>
    

    <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Radiology Billing </h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="tab_logic" class="table table-bordered  nowrap table-hover">
                                        <thead class="thead-dark ">
                                            <tr>
                                              
                                              
                                             
                                               
                                                <th>Test Name</th>
                                                <th>Report Days</th>
                                                <th>Report Date</th>
                                                <th>Tax</th>
                                                <th>Amount</th>
                                                
                                                
                                               <th>Action</th>
                                              
                                                
                                            </tr>
                                        </thead>
                                        <tbody class=" text-black">
                                       
            <tr id='addr0'>
        
                <td width="30%">
                   
                   <select onchange="setTest(this.value,0)" name="test0"  class="form-control" required id="test0">
                    <option value="">Select Test</option>
                    <?php $res=$newRadio->getRadioTest4Bill();
                    while($row=mysqli_fetch_assoc($res))
                    { ?>
                         <option value="<?php echo $row['id']; ?>"><?php echo $row['test_name']; ?></option>
                  <?php  } ?>
                    
                   </select>
                </td>
                <input type="hidden" name="patient_id" value="<?php echo $_REQUEST['id']; ?>">
                <td>
                    <input type="text" name="days0" id="days0"class="form-control" placeholder="Report days" readonly required>
                </td>
                <td>
                    <input onchange="totalCal(0)" type="date" name="date0" id="date0"class="form-control" required placeholder="date">
                </td>
                <td>
                    <input type="text" name="tax0" id="tax0"class="form-control" placeholder="Tax" readonly>
                </td>
                <td>
                    <input type="text" name="amount0" id="amount0"class="form-control" placeholder="amount" readonly>
                </td>
              
                <td class=" text-center">
                
               <a class="btn btn-danger m-1 text-light"  type="button" id='delete_row' name='dlt[]' onclick='dlt(0)' >  <i class="icofont-ui-delete "  data-toggle="tooltip" data-placement="top" title="Delete"></i></a>
                </td>
            </tr>
            
            <tr id='addr1'>
                 <input type="hidden" name="id" id="id" value=1>
            </tr>
            </tbody>
            <tfoot>
                                            <tr>
                                            <td colspan="4" class="text-right">Grand Total (Rs.)</td>
                                                <td class=""><input type="text" class="form-control" value="" id="grand_total" name="grand_total" ></td>
      
                                                <td><a id="add_row" type="button" data-toggle="tooltip" title="Add New Row" class="btn btn-success text-light float-right m-1"><i class="icofont-plus" ></i></a></td>

                                            </tr>
                                            <tr>
                                            <td colspan="4" class="text-right">Total Tax (Rs.)</td>
                                              <td class=""><input type="text" class="form-control" value="" id="total_tax" name="total_tax" ><input type="hidden" id="total_tax1"  name="total_tax1" value=""></td>
                                              
                                                </tr>


                                            <td colspan="4" class="text-right">Discount (Rs.)</td>
                                            <td><input onchange="disCal(this.value)" type="text" name="discount" class="form-control" id="discount" value="" ></td>
                                              
                                                </tr>


                                              
                                                <tr>
                                                <td colspan="4" class="text-right">Net Amount (Rs.) </td>
                                                <td><input type="text" name="net_amount" class="form-control" id="net_amount" value="" ></td>
      
                                                </tr>
                                                <tr>
                                                 <td colspan="4" class="text-right">Paid Amount (Rs.) </td>
                                                <td><input onchange="dueCal(this.value)" class="form-control" id="paid_amount"  name="paid_amount" value=""></td>
      
                                                </tr>
                                                  <tr>
                                                 <td colspan="4" class="text-right">Due Amount (Rs.) </td>
                                                <td><input class="form-control" id="due_amount"  name="due_amount" value=""></td>
      
                                                </tr>
                                                  <tr>
                                                                                                 <td colspan="4" class="text-right">In Words </colspan="4"></td>
                                                                                                 <td class="text-danger"><span id="words"></span></td>

                                                </tr>
                                                
                                      
                                            <!-- <tr>
                                                <td colspan="8" >
                                                <div align="center" class="text-danger">No data available in table <br> <br><img src="images/addnewitem.svg" widtd="200"><br><br> <span class="text-success bolds"><i class="fa fa-arrow-left"></i> Add new record or search with different criteria.</span><div></div></div>
                                                </td>
                                            </tr> -->
                                            
                                            </tfoot>

                                            </table>   
                                            </div> 
                                            <div class="row mt-3 ">
                                                <div class="col-md-12 d-flex justify-content-end">
                                                    <button type="submit" name="submit" value="test_bill" class="btn btn-primary mr-2" id="myBtn"><i class="bi bi-cash-stack"></i> Paid</button>
                                                    <button type="button" class="btn btn-danger mr-2" id="print"><i class="bi bi-print"></i> Print</button>
                                                </div>
                                            </div>
                                            </div>
                                            </div></div></div>
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
    <!-- Required vendors -->
    <script src="./vendor/global/global.min.js"></script>
    <script src="./js/quixnav-init.js"></script>
    <script src="./js/custom.min.js"></script>

    <script>
        $(document).ready(function(){
   var i=1;
   
  $("#add_row").click(function(){
   $("#addr"+i).html("<td><select class='form-control' onchange='setTest(this.value,"+i+")' name='test"+i+"' id='test"+i+"'><option>Select Test</option> <?php $res=$newRadio->getRadioTest4Bill();  while($row=mysqli_fetch_assoc($res)) { ?> <option value='<?php echo $row['id']; ?>'><?php echo $row['test_name']; ?></option>  <?php  } ?></select> </td><td><input type='text' name='days"+i+"' class='form-control '  readonly id='days"+i+"'  placeholder='Report days' > </td><td><input onchange='totalCal("+i+")' type='date' name='date"+i+"' class='form-control '   id='date"+i+"'  placeholder='date' > </td><td><input type='text' name='tax"+i+"' class='form-control '  readonly id='tax"+i+"'  placeholder='Tax' > </td><td><input type='text' name='amount"+i+"' class='form-control '  readonly id='amount"+i+"'  placeholder='amount' > </td><td class='text-center'><a id='delete_row' name='dlt[]' onclick='dlt("+i+")' class='text-center text-light btn btn-danger' type='button'  data-toggle='tooltip' title='Delete'> <i class='icofont-ui-delete ' ></i></a></td>");
   
   $('#tab_logic').append('<tr id="addr'+(i+1)+'"></tr>');


   i++; 
   document.getElementById('id').value=i;
});
  $("#delete_row").click(function(){
      if(i>1){
      $("#addr"+(i-1)).html('');
      i--;
      
      }document.getElementById('id').value=i;
  });

});
function dlt(id){
      
      $("#addr"+(id)).html('');
     
  };
 </script>

<script>
    var a = ['','one ','two ','three ','four ', 'five ','six ','seven ','eight ','nine ','ten ','eleven ','twelve ','thirteen ','fourteen ','fifteen ','sixteen ','seventeen ','eighteen ','nineteen '];
var b = ['', '', 'twenty','thirty','forty','fifty', 'sixty','seventy','eighty','ninety'];

function inWords (num) {
    if ((num = num.toString()).length > 9) return 'overflow';
    n = ('000000000' + num).substr(-9).match(/^(\d{2})(\d{2})(\d{2})(\d{1})(\d{2})$/);
    if (!n) return; var str = '';
    str += (n[1] != 0) ? (a[Number(n[1])] || b[n[1][0]] + ' ' + a[n[1][1]]) + 'crore ' : '';
    str += (n[2] != 0) ? (a[Number(n[2])] || b[n[2][0]] + ' ' + a[n[2][1]]) + 'lakh ' : '';
    str += (n[3] != 0) ? (a[Number(n[3])] || b[n[3][0]] + ' ' + a[n[3][1]]) + 'thousand ' : '';
    str += (n[4] != 0) ? (a[Number(n[4])] || b[n[4][0]] + ' ' + a[n[4][1]]) + 'hundred ' : '';
    str += (n[5] != 0) ? ((str != '') ? 'and ' : '') + (a[Number(n[5])] || b[n[5][0]] + ' ' + a[n[5][1]]) + 'only ' : '';
    return str;
}
document.getElementById('paid_amount').value=   document.getElementById('total_amount').innerHTML


// document.getElementById('number').onkeyup = function () {

    document.getElementById('words').innerHTML = inWords(document.getElementById('paid_amount').value);
// };
</script>



<script>
    

  $("#myBtn").click(function(){
    $('.toast').toast('show');
  });

</script>
<script>
    function patientDetail(op)
    {
        // alert(op)
     $.ajax({
      url: 'ajax.php',
        type: 'POST', // Send post data
        data: 'op_no='+op+'&page=radioreg',
        async: false,
        success: function(s){
            // alert(s);
            json_events = s.split("|");
            $("#pname").val(json_events[3]);
            $("#age").val(json_events[4]);
            $("#gender").val(json_events[5]);
            $("#doctor").val(json_events[25]);
            $("#dept").val(json_events[26]);
          
          }
        });
  }
  window.setTimeout(function() {
    $(".alert").fadeTo(500, 0).slideUp(500, function(){
        $(this).remove(); 
    });
}, 4000);
</script>

<script>
    function setTest(id,i)
    {
        $.ajax({
      url: 'ajax.php',
        type: 'POST', // Send post data
        data: 'id='+id+'&page=test',
        async: false,
        success: function(s){
        //   alert(s);
          json_events = s.split("|");
        //   alert(json_events);
          $("#days"+i).val(json_events[7]);
          $("#tax"+i).val(json_events[10]);
          $("#amount"+i).val(json_events[12]);
        }
         });
    }
    
    function totalCal(i)
    {   
     
       
       var tot=document.getElementById('grand_total').value=Number(document.getElementById('grand_total').value) +  Number(document.getElementById('amount'+i).value);
       var tax=document.getElementById('total_tax1').value=Number(document.getElementById('total_tax1').value) +  Number(document.getElementById('tax'+i).value);
       var tax_amt=(tax*tot)/100;
       document.getElementById('total_tax').value=tax_amt;
    }
    
    function disCal(val)
    {   
        // alert(val)
        var net=Number(document.getElementById('grand_total').value)-val;
        document.getElementById('net_amount').value=net;
    }
    
    function dueCal(val)
    {
        var due=Number(document.getElementById('net_amount').value)-val;
        document.getElementById('due_amount').value=due;
    }
</script>
</body>

</html>