<?php 

require_once(__DIR__ . '/model/session_config.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#319197">

    <title>Add Prescriptions</title>
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

     <link href="assets/bootstrap-select-country-4.2.0/dist/css/bootstrap-select-country.min" rel="stylesheet" type="text/css" />



 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">

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
                                    <strong>Success!</strong> New Purchase Order Added Successfully.
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
        <div class="col-md-6">
            <h4 class="text-uppercase">Add Prescriptions</h4>

        </div>
        <div class="col-md-6">
      <a href="patient_billing.php">  <button class="btn btn-success btn-sm float-lg-right ml-lg-3 mt-lg-3" ><i class="ri-list-check"></i> View Patient List </button> </a>

       
      
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
<form action="controller/" method='POST'>

    <div class="row">
    
    <div class="col-md-3">
            <div class="form-group">
                   <label > Customer Name<span style="color:red">*</span> </label>
                   <input  type="text"  class="form-control " name="name" id="name" required> 
                   
             </div>
         </div>
       
         <div class="col-md-3">
            <div class="form-group">
                   <label > Date<span style="color:red">*</span> </label>
                   <input  type="date"  class="form-control " name="date" id="date"  value="<?= date("d/m/Y")?>"required> 
                   
             </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                   <label > Invoice Number <span style="color:red">*</span> </label>
                   <input type="text" class="form-control " name="invoice" id="invoice"  placeholder="Enter Invoice Number"required> 
                   
             </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                   <label > Details</label>
                   <textarea name="details" class="form-control" id="details" cols="1" rows="1" ></textarea>
                   
             </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                   <label > Payment Mode <span style="color:red">*</span> </label>
                   <select class="form-control selectpicker bg-white country " data-live-search="true" id="payment" name="payment">
       <option value="cash">Cash</option>
       <option value="online">Online</option>


    </select>
             </div>
         </div>
         
</div>

<div class="row">
         <div class="col-md-12">
          

            <button type="submit" class="btn btn-success  float-right" name="submit" value="unit">Save</button>
            
         </div>
  
    </div>
</form>
                </div>
            </div>
        </div>
    </div>

<form action="">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
            <table class="table table-bordered text-nowrap" width="100%"   id="tab_logic">
     <thead class="thead-dark">
            <tr class="text-center"> 
                <th>Medicine Information <span class="text-danger">*</span> </th>
                <th>Batch Id</th>
                <th>Expiry Date <span class="text-danger">*</span></th>
                <th>Stock Qty</th>
                <th>Unit  </th>
                <th>Box Qty<span class="text-danger">*</span></th>

                <th>Quantity  <span class="text-danger">*</span></th>
       

   
                <th>Total Purchase Price</th>


              
                <th>Action</th>
            </tr>

            
        </thead>
            <tbody>
            <tr id='addr0'>
            <td>
            <select class="form-control selectpicker bg-white country " data-live-search="true" id="medicine[]" name="medicine[]">
       <option value="cash">Cash</option>
       <option value="online">Online</option>


    </select></td>
                <td>
                    <input type="text" name="batch[]" id="batch[]"class="form-control" placeholder="Batch Id">
                </td>
                
                <td>
                    <input type="date" name="exdate[]" id="exdate[]"class="form-control" placeholder="Date" required>
                </td>
                <td>
                    <input type="text" name="stock[]" id="stock[]"class="form-control" placeholder="00.00" disabled>
                </td>
                <td>
                    <input type="number" name="boxqunt[]" id="boxqunt[]"class="form-control" placeholder="Box Quantity" required>
                </td>
                <td>
                    <input type="number" name="qunt[]" id="qunt[]"class="form-control" placeholder="Quantity" required>
                </td>
                <td>
                    <input type="number" name="mprice[]" id="mprice[]"class="form-control" placeholder="Manufacturer Price" required>
                </td>
                
                <td>
                    <input type="text" name="tprice[]" id="tprice[]"class="form-control" placeholder="Total Purchase Price" disabled>
                </td>
                <td class=" text-center">
                
               <a class="btn btn-danger m-1 text-light"  type="button" id='delete_row' name='dlt[]' onclick='dlt(0)' >  <i class="icofont-ui-delete "  data-toggle="tooltip" data-placement="top" title="Delete"></i></a>
                </td>
            </tr>
            
            <tr id='addr1'></tr>
            
            </tbody>
            <!-- </table>
            <table class="table table-responsive table-bordered"   id="tab_logic"> -->
            <tfoot> 
           
            
            
            <tr>
            <tr>
                <td colspan="7" class="text-right">Sub Total</td>
                <td><input type="text" class="form-control" disabled value="578.02" name="subtotal" id="subtotal"> </td>

                <td><a id="add_row" type="button" data-toggle="tooltip" title="Add New Row" class="btn btn-success text-light float-right m-1"><i class="icofont-plus" ></i></a></td>
            </tr>
            <tr>
            <td colspan="7" class="text-right">Discount</td>
            <td><input type="text" class="form-control" disabled value="100.00" name="discunt" id="discunt"> </td>

            </tr>  
            <tr>
            <td colspan="7" class="text-right">Grand Total</td>
            <td><input type="text" class="form-control" disabled value="478.02" name="gtotal" id="gtotal"> </td>

            </tr>  
                
            <tr>
            <td colspan="7" class="text-right">Paid Amount</td>
            <td><input type="text" class="form-control"  value="478.02" name="pamount" id="pamount"> </td>

            </tr>  
            <tr>
            <td colspan="7" class="text-right">Due Amount</td>
            <td><input type="text" class="form-control" disabled value="478.02" name="due" id="due"> </td>

            </tr>  
            </tr>
        </tfoot>
            </table>
            </div>
            <button type="submit" class="btn btn-success float-right m-1"> Save</button> 

          
        </div>
        </div>

</form>
                </div>
            </div>
        </div>
    </div>

    

      

</div>

          
        </div>
        <!--**********************************
            Content body end
        ***********************************-->


        <!--**********************************
            Footer start
        ***********************************-->
        <?php include('../footer.php') ?>
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



<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>


<script>
        $(document).ready(function(){
   var i=1;
   
  $("#add_row").click(function(){
   $("#addr"+i).html("<td><select class='form-control selectpicker' data-live-search='true' name='medicine[]' id='medicine[]'><option value=''>Select Medicine</option></select></td><td><input type='text' class='form-control' placeholder='Batch ID name='batch[]' ></td><td><input type='date' name='expdate[]' class='form-control'  id='expdate[]' > </td><td><input type='text' name='stock[]' class='form-control'  id='stock[]' disabled placeholder='00.00'> </td><td><input type='number' name='quantity[]' class='form-control'  id='quantity[]' placeholder='Box Quantity' > </td><td><input type='number' name='mprice[]' class='form-control'  id='mprice[]' required placeholder='Manufaturer Price' > </td><td><input type='number' name='mrp[]' class='form-control'  id='mrp[]' placeholder='Box MRP' > </td><td><input type='text' name='tprice[]' class='form-control '  disabled id='tprice[]'  placeholder='Total Purchase Price' > </td><td class='text-center'><a id='delete_row' name='dlt[]' onclick='dlt("+i+")' class='text-center text-light btn btn-danger' type='button'  data-toggle='tooltip' title='Delete'> <i class='icofont-ui-delete ' ></i></a></td>");
   
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






  

</body>

</html>