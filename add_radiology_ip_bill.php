<?php
require_once(__DIR__ . '/model/session_config.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Add Radiology IP  Bill</title>
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

            <div class="position-fixed top-0 right-0 p-3" style="z-index: 5; right: 0; bottom: 0;">
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
    <div class="row mb-3">
        <div class="col-md-12">
            <h4 class="text-uppercase"> Radiology  Billing</h4>
        </div>
    </div>
<form action="/radiology_billing.php">
    <div class="row">
       <div class="col-md-12">
       <div class="card">
            <div class="card-body">
                <div class="row">
                     <div class="col-md-2">
                        <div class="form-group">
                            <label for="">IPD ID</label>
                            <input type="text" class="form-control" name="ipd" id="ipd"  >
                        </div>
                    </div>
                     
                <div class="col-md-2">
                        <div class="form-group">
                            <label for="">OPD ID</label>
                            <input type="text" class="form-control" readonly name="opdid" id="opdid"  >
                        </div>
                    </div>
                     
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="">Patient Name</label>
                            <input type="text" class="form-control" name="pname" id="pname" readonly  >
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="">Gender</label>
                            <input type="text" class="form-control" name="gender" id="gender" readonly >
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="">Age</label>
                            <input type="text" class="form-control" name="age" id="age" readonly >
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="">Department</label>
                            <input type="text" class="form-control" name="dept" id="dept"  readonly >
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="">Doctor</label>
                            <input type="text" class="form-control" name="doctor" id="doctor" readonly  >
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
                   
                   <select name="test[]"  class="form-control" required id="test[]">
                    <option value="">Select Test</option>
                   </select>
                </td>
                
                <td>
                    <input type="text" name="days[]" id="days0"class="form-control" placeholder="Report days" readonly required>
                </td>
                <td>
                    <input type="date" name="date[]" id="date0"class="form-control" placeholder="date">
                </td>
                <td>
                    <input type="text" name="tax[]" id="tax0"class="form-control" placeholder="Tax" readonly>
                </td>
                <td>
                    <input type="text" name="amount[]" id="amount0"class="form-control" placeholder="amount" readonly>
                </td>
              
                <td class=" text-center">
                
               <a class="btn btn-danger m-1 text-light"  type="button" id='delete_row' name='dlt[]' onclick='dlt(0)' >  <i class="icofont-ui-delete "  data-toggle="tooltip" data-placement="top" title="Delete"></i></a>
                </td>
            </tr>
            
            <tr id='addr1'></tr>
            </tbody>
            <tfoot>
                                            <tr>
                                            <th colspan="4" class="text-right">Grand Total (Rs.)
                                                <th class="text-center"> <span id="total">125</span></th>
      
                                                <td><a id="add_row" type="button" data-toggle="tooltip" title="Add New Row" class="btn btn-success text-light float-right m-1"><i class="icofont-plus" ></i></a></td>

                                            </tr>
                                            <tr>
                                            <th colspan="4" class="text-right">Total Tax (Rs.)</th>
                                               <td class="total_tax text-center">25.00</td>
                                              
                                                </tr>


                                              
                                                <tr>
                                                <th colspan="4" class="text-right">Net Amount (Rs.)
                                                <td class="text-center" id="net_amount"> 125.00</td>
      
                                                </tr>
                                                <tr>
                                                    <td  colspan="6" class="text-right" ><button type="submit" class="btn btn-success"  name="submit">Save</button></td>
                                                </tr>
                                                 
                                                
                                               
                                            
                                            
                                            </tfoot>

                                            </table>   
                                            </div>  
                                            </div>
                                            </div></div></div>
        </div>
</div>
</form>
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
   $("#addr"+i).html("<td><select class='form-control' name='test[]' id='test"+i+"'><option>Select Test</option></select> </td><td><input type='text' name='days' class='form-control '  readonly id='days"+i+"'  placeholder='Report days' > </td><td><input type='date' name='date[]' class='form-control '   id='date"+i+"'  placeholder='date' > </td><td><input type='text' name='tax[]' class='form-control '  readonly id='tax"+i+"'  placeholder='Tax' > </td><td><input type='text' name='total[]' class='form-control '  readonly id='total"+i+"'  placeholder='amount' > </td><td class='text-center'><a id='delete_row' name='dlt[]' onclick='dlt("+i+")' class='text-center text-light btn btn-danger' type='button'  data-toggle='tooltip' title='Delete'> <i class='icofont-ui-delete ' ></i></a></td>");
   
   $('#tab_logic').append('<tr id="addr'+(i+1)+'"></tr>');


   i++; 
});
  $("#delete_row").click(function(){
      if(i>1){
      $("#addr"+(i-1)).html('');
      i--;
      }
  });

});
function dlt(id){
      
      $("#addr"+(id)).html('');
     
  };
 </script>




<script>
    

  $("#myBtn").click(function(){
    $('.toast').toast('show');
  });

</script>
</body>

</html>