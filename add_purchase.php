<?php 
require_once(__DIR__ . '/model/session_config.php');
include('model/medicineClass.php');
$medic= new medicine;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#319197">

    <title>Add Purchase Order</title>
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
     
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />


<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">

<style>
     #notification {
            position: absolute;
            top: 90px;
           
            right:2px;
            z-index: 99;
        }.bootstrap-select .btn-light{
            color:#000 !important;
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
            <h4 class="text-uppercase">Add Purchase</h4>

        </div>
        
        <div class="col-md-3">
           
        </div>
        <div class="col-md-3">
             <a type="button" class="btn text-light btn-success btn-sm float-lg-right ml-lg-3 mt-lg-" data-toggle="modal" data-target="#exampleModal">
 Upload Document
</a>
      <a type="button" href="assets/medicine_purchase.csv" class="btn text-light btn-primary btn-sm float-lg-right ml-lg-3 mt-lg-" download>
 Download Templete
</a>
      <!--<a href="view_purchase.php">  <button class="btn btn-success btn-sm float-lg-right ml-lg-3 mt-lg-3" ><i class="ri-list-check"></i> View Purchase </button> </a>-->

       
      
        </div>
    </div>
    <form action="controller/processPurchase.php" method='POST'>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">


    <div class="row">
    
        <div class="col-md-3">
            <div class="form-group">
                   <label> Select Distributor  <span style="color:red">*</span> </label>
                   <!--onchange="getMedicine(this.value,1)"-->
                   <select class="form-control  "  id="manufacture" name="manufacture" onchange="getMedicine(this.value)" required >
                       <option value="">Select Distributor</option>
<?php $res=$medic->getManufacture();
while($row=mysqli_fetch_assoc($res)) {?>
<option value="<?= $row['id']?>"><?= $row['manuf_name']?></option>
<?php } ?>
</select>
                   
             </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                   <label > Date<span style="color:red">*</span> </label>
                   <input  type="date"  class="form-control " name="pur_date" id="pur_date"  value="<?= date("d/m/Y")?>"required> 
                   
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
                   <select class="form-control  bg-white  " data-live-search="true" id="payment" name="payment">
       <option value="cash">Cash</option>
       <option value="online">Online</option>


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
                <div class="card-body">
                <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
            <table class="table table-bordered text-nowrap" width="100%"   id="tab_logic">
     <thead class="thead-dark">
            <tr class="text-center"> 
                <th width="10%">Medicine Information <span class="text-danger">*</span> </th>
                <th >Batch Id</th>
                <th>Expiry Date <span class="text-danger">*</span></th>
                <th>Stock<br>Qty</th>
                <th>Pack <span class="text-danger">*</span></th>
                <th>Pack<br>Qty <span class="text-danger">*</span></th>

                <th>Quantity<span class="text-danger">*</span></th>
                <th>Free</th>
                <th>Rate <span class="text-danger">*</span></th>

                <th>MRP<span class="text-danger">*</span></th>
                <th>Disc<br>(%)<span class="text-danger"></span></th>
                <th> G.S.T % <span class="text-danger">*</span></th>
                <th>Total<br>Purchase<br>Price</th>


              
                <th>Action</th>
            </tr>

            
        </thead>
            <tbody>
            <tr id='addr1'>
            
           <td>
               
               
<!--                  <input type="text" class="form-control" list="madicines1" name="madicine[]" id="medicine1" onchange="showResult(this.value,1)" autocomplete="off" placeholder="Select Medicine Name">-->
<!--            <datalist id="madicines1">-->
<!--      <option value="Select Medicine"></option>-->


<!--</datalist>-->
                    
         <select class="form-control medicine-select" name="medicine[]" id="medicine1" onchange="showResult(this.value,1)">
    <option value="">Search & Select Medicine</option>
</select>

</td>
                <td>
                    <input type="text" name="batch[]" id="batch1"class="form-control" placeholder="Batch Id">
                </td>
                
                <td>
                    <input type="month" name="exdate[]" id="exdate1"class="form-control" placeholder="Date" >
                </td>
                <td>
                    <input type="text" name="stock[]" id="stock1"class="form-control" value="0" readonly>
                </td>
                <td>
                    <select name="leaf[]" id="leaf1"class="form-control" onchange="purchase_calculation(1)" onkeyup="purchase_calculation(1)" required>
                        <option>select leaf</option>
                        <?php $res=$medic->getLeafs();
                        while($row=mysqli_fetch_assoc($res))
                        {
                        ?>
                        <option value="<?= $row['number']?>"><?= $row['leaf_type'].' ('.$row['number'].')'?></option>
                        <?php } ?>
                    </select>
                </td>
                <td>
                    <input type="number" name="boxqunt[]" id="boxqunt1"class="form-control" onchange="purchase_calculation(1)" onkeyup="purchase_calculation(1)" placeholder="Box Quantity" required value=0>
                </td>
                <td>
                    <input type="number" name="qunt[]" id="qunt1"class="form-control" onchange="purchase_calculation(1)" onkeyup="purchase_calculation(1)" placeholder="Quantity" readonly value=0>
                </td>
                  <td>
                    <input type="number" name="freequnt[]" id="freequnt1"class="form-control" onchange="purchase_calculation(1)" onkeyup="purchase_calculation(1)" placeholder="Free Quantity"  value=0>
                </td>
                <td>
                    <input type="text" name="mprice[]" id="mprice1"class="form-control" placeholder="Manufacturer Price" onchange="purchase_calculation(1)" onkeyup="purchase_calculation(1)" required>
                </td>
                <td>
                    <input type="text" name="mrp[]" id="mrp1"class="form-control" placeholder="Box MRP" required>
                </td>
                 <td>
                    <input type="text" name="disc[]" id="disc1" onkeyup="purchase_calculation(1)" class="form-control" placeholder="Discount %" value="0">
                </td>
                <td>
                    <select class="form-control" name="tax[]" id="tax1" onchange="purchase_calculation(1)" required>
                        <option value="0">0%</option>
                        <option value="5">5%</option>
                        <option value="12">12%</option>
                        <option value="18">18%</option>
                        <option value="28">28%</option>
                    </select>
                </td>
                <td>
                    <input type="text" name="tprice[]" id="tprice1"class="form-control total_price " placeholder="Total Purchase Price" readonly>
                     <input type='hidden' name='taxprice[]' id='taxprice1' class='form-control tax_price' readonly>
                     <input type='hidden' name='discprice[]' id='discprice1' class='form-control disc_price' readonly>
                </td>
                
                <td class=" text-center">
                
               <a class="btn btn-danger m-1 text-light"  type="button" id='delete_row' name='dlt[]' onclick='dlt(1)' >  <i class="icofont-ui-delete "  data-toggle="tooltip" data-placement="top" title="Delete"></i></a>
                </td>
            </tr>
            
            <tr id='addr2'></tr>
            
            </tbody>
            <!-- </table>
            <table class="table table-responsive table-bordered"   id="tab_logic"> -->
            <tfoot> 
           
            
            
            <tr>
            <tr>
                <td colspan="12" class="text-right">Sub Total</td>
                <td><input type="text" class="form-control" readonly placeholder="0" name="subtotal" id="subtotal"> </td>

                <td><a id="add_row" type="button" data-toggle="tooltip" title="Add New Row" class="btn btn-success text-light float-right m-1"><i class="icofont-plus" ></i></a></td>
            </tr>
            <tr>
            <td colspan="12" class="text-right">Tax Amount</td>
            <td><input type="text" class="form-control" readonly  placeholder="0" name="gst" id="gst"> </td>

            </tr> 
            <tr>
            <td colspan="12" class="text-right">Discount</td>
            <td><input type="text" class="form-control" onkeyup="discount_calculation()" value="0" name="discunt" id="discunt" readonly> </td>

            </tr>  
            <tr>
            <td colspan="12" class="text-right">Grand Total</td>
            <td><input type="text" class="form-control" readonly placeholder="0" name="gtotal" id="gtotal"> </td>

            </tr>  
                
            <tr>
            <td colspan="12" class="text-right">Paid Amount</td>
            <td><input type="text" class="form-control"  placeholder="0" name="pamount" id="pamount" onkeyup="paid_calculation()"> </td>

            </tr>  
            <tr>
            <td colspan="12" class="text-right">Due Amount</td>
            <td><input type="text" class="form-control" readonly placeholder="0" name="due" id="due"> </td>

            </tr>  
            </tr>
        </tfoot>
            </table>
            </div>
            <button type="submit" name="submit" value="purchase" class="btn btn-success float-right m-1"> Save</button> 

            <button type="button" onclick="full_paid_purchase()" class="btn btn-warning text-white float-right m-1">Full Paid</button> 
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
        <?php include('./footer.php') ?>
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

   <script src="./vendor/select2/js/select2.full.min.js"></script>
    <script src="./js/plugins-init/select2-init.js"></script>
<script>
function getMedicine(val,i)
    {
         
        $.ajax({
            type : 'post',
            url : 'ajax.php', //Here you will fetch records 
            data :  'manufac='+val+'&page=medic', //Pass $id
            success : function(data){
           document.getElementById('medicine'+i).innerHTML=data;//Show fetched data from database
            }
        });
    }
       $(document).ready(function(){
    var i = 2; // Start from second row

    
    function initializeSelect2(selector) {
        $(selector).select2({
            placeholder: "Search & Select Medicine",
            minimumInputLength: 2,
            ajax: {
                url: "ajax.php", // Fetch medicine names dynamically
                type: "POST",
                dataType: "json",
                delay: 250,
                data: function(params) {
                    return { searchTerm: params.term, page: "getMedicine" };
                },
                processResults: function(data) {
                    return { results: data };
                },
                cache: true
            }
        });
    }

    
    initializeSelect2("#medicine1");

    $("#add_row").click(function(){
        var newRow = `
        <tr id="addr${i}">
            <td><select class='form-control medicine-select' name='medicine[]' id='medicine${i}' required></select></td>
            <td><input type='text' class='form-control' placeholder='Batch ID' name='batch[]' id='batch${i}'></td>
            <td><input type='month' name='exdate[]' class='form-control' id='exdate${i}'></td>
            <td><input type='text' name='stock[]' class='form-control' id='stock${i}' readonly placeholder='00.00'></td>
            <td><select name='leaf[]' id='leaf${i}'class='form-control' onchange='purchase_calculation(${i})' onkeyup='purchase_calculation(${i})' required><option>select leaf</option><?php $res=$medic->getLeafs();while($row=mysqli_fetch_assoc($res)) { ?><option value='<?= $row[number]?>'><?= $row['leaf_type'].' ('.$row['number'].')'?></option><?php } ?></select></td>
            <td><input type='number' name='boxqunt[]' class='form-control' id='boxqunt${i}' onchange='purchase_calculation(${i})' onkeyup='purchase_calculation(${i})' placeholder='Box Quantity'></td>
            <td><input type='number' name='qunt[]' class='form-control' id='qunt${i}' placeholder='Quantity' readonly onchange='purchase_calculation(${i})' onkeyup='purchase_calculation(${i})'></td>
            <td><input type='number' name='freequnt[]' class='form-control' id='freequnt${i}' placeholder='Free Quantity' onchange='purchase_calculation(${i})' onkeyup='purchase_calculation(${i})'></td>
            <td><input type='text' name='mprice[]' class='form-control' id='mprice${i}' required placeholder='Manufacturer Price' onchange='purchase_calculation(${i})' onkeyup='purchase_calculation(${i})'></td>
            <td><input type='text' name='mrp[]' class='form-control' id='mrp${i}' placeholder='Box MRP'></td>
            <td><input type='text' name='disc[]' id='disc${i}' class='form-control' placeholder='Discount %' value='0' onkeyup='purchase_calculation(${i})'></td>
            <td><select class='form-control' name='tax[]' id='tax${i}' required onchange='purchase_calculation(${i})'>
                <option value='0'>0%</option><option value='5'>5%</option><option value='12'>12%</option><option value='18'>18%</option><option value='28'>28%</option>
            </select></td>
            <td><input type='text' name='tprice[]' class='form-control total_price' readonly id='tprice${i}' placeholder='Total Purchase Price'>
            <input type='hidden' name='taxprice[]' id='taxprice${i}' class='form-control tax_price' readonly>
            <input type='hidden' name='discprice[]' id='discprice${i}' class='form-control disc_price' readonly></td>
            <td class='text-center'>
                <a class='text-center text-light btn btn-danger delete_row' data-id='${i}' type='button' data-toggle='tooltip' title='Delete'>
                    <i class='icofont-ui-delete'></i>
                </a>
            </td>
        </tr>`;

        $('#tab_logic').append(newRow);
        initializeSelect2("#medicine" + i); 
        i++;
    });

  
    $(document).on("click", ".delete_row", function(){
        var rowId = $(this).data("id");
        $("#addr" + rowId).remove();
    });
});

  
    
     function showResult(val,i)
    {
        $.ajax({
            type : 'post',
            url : 'ajax.php', //Here you will fetch records 
            data :  'medic='+val+'&page=stock', //Pass $id
            success : function(data){
                let res=data.split('|');
            $('#stock'+i).val(res[0]>0 ? res[0] : 0);
            $('#mprice'+i).val(res[1]);
            $('#mrp'+i).val(res[2]);
            $('#tax'+i).val(res[3]);
            }
        });
    }
    
    function purchase_calculation(sl) {
    var gr_tot = tot_gst=tot_disc = 0;
    var b_p = $("#leaf" + sl).val();
    var tax = $("#tax" + sl).val();
    var box = $("#boxqunt" + sl).val();
    var total_unit = b_p * box;
    $("#qunt" + sl).val(total_unit);
    var item_ctn_qty = $("#boxqunt" + sl).val();
    var vendor_rate = $("#mprice" + sl).val();
    var discount = $("#disc" + sl).val();
    var disc=(discount*item_ctn_qty * vendor_rate)/100;
    var gst=(((item_ctn_qty * vendor_rate)-((discount*item_ctn_qty * vendor_rate)/100))*tax)/100;
    $('#taxprice'+sl).val(gst);
     $('#discprice'+sl).val(disc);
    var total_price = (item_ctn_qty * vendor_rate);
    $("#tprice" + sl).val(total_price.toFixed(2));
    $(".total_price").each(function() {
        isNaN(this.value) || 0 == this.value.length || (gr_tot += parseFloat(this.value))
    });
    $(".tax_price").each(function() {
        isNaN(this.value) || 0 == this.value.length || (tot_gst += parseFloat(this.value))
    });
   $(".disc_price").each(function() {
        isNaN(this.value) || 0 == this.value.length || (tot_disc += parseFloat(this.value))
    });
    $("#subtotal").val((gr_tot).toFixed(2, 2));
    $("#gst").val(tot_gst.toFixed(2, 2));
    $("#discunt").val(tot_disc.toFixed(2, 2));
    $("#gtotal").val(gr_tot.toFixed(2, 2));
    discount_calculation();
}
function discount_calculation() {
    // console.log("here")
    var gr_tot = 0;
    var total = parseFloat($("#subtotal").val())+parseFloat($("#gst").val());
    $(".total_price").each(function() {
        isNaN(this.value) || 0 == this.value.length || (gr_tot += parseFloat(this.value))
    });
    var t = $("#discunt").val()==''?0: $("#discunt").val();
  
    if (t > total) {
        var message = 'Discount Can not Greater than Total Amount';
       alert(message);
        $("#discunt").val(0);
        var net_total = total ;
    } else {
        var net_total = parseFloat(total) - parseFloat(t);
    }
 
    $("#gtotal").val(net_total.toFixed(2, 2));
    var due=parseFloat(net_total)-parseFloat($("#pamount").val()=='' ? 0 :$("#pamount").val())
    $("#due").val(due.toFixed(2, 2))
}

    function paid_calculation() {
    var paid_amount = $("#pamount").val();
    var gr_total = $("#gtotal").val();
    var net_total = parseFloat(gr_total) - paid_amount;
    $("#due").val(net_total.toFixed(2, 2));
    discount_calculation();
}
// function disc_calculation(val)
// {
//   var discount = (parseFloat($("#disc"+val).val())*parseFloat($("#tprice"+val).val()))/100; 
// //   alert(discount);
//   var grand_dis = parseFloat($("#discunt").val());
// //   alert(grand_dis);
// //   alert(grand_dis+discount);
//   $("#discunt").val((grand_dis+discount).toFixed(2, 2));
//   discount_calculation();
// }
function full_paid_purchase() {
    var grandTotal = $("#gtotal").val();
    $("#pamount").val(grandTotal);
    paid_calculation();
}


$("#medicine1").select2();

 </script>

<script>
$(document).ready(function() {
    $("#medicine1").select2({
        placeholder: "Search & Select Medicine",
        minimumInputLength: 2,
        ajax: {
            url: "ajax.php", 
            type: "POST",
            dataType: "json",
            delay: 250,
            data: function(params) {
                return { searchTerm: params.term, page: "getMedicine" };
            },
            processResults: function(data) {
                return { results: data };
            },
            cache: true
        }
    });
});

</script>
    <!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Upload Document</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <form action="controller/processPurchase.php" method='POST' enctype="multipart/form-data">
           <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">


    <div class="row">
    
        <div class="col-md-12">
            <div class="form-group">
                   <label> Select Distributor  <span style="color:red">*</span> </label>
                   <select class="form-control  "  id="manufacture" name="manufacture" required onchange="getMedicine(this.value,1)">
                       <option value="">Select Distributor</option>
<?php $res=$medic->getManufacture();
while($row=mysqli_fetch_assoc($res)) {?>
<option value="<?= $row['id']?>"><?= $row['manuf_name']?></option>
<?php } ?>
</select>
                   
             </div>
         </div>
         <div class="col-md-12">
            <div class="form-group">
                   <label > Date<span style="color:red">*</span> </label>
                   <input  type="date"  class="form-control " name="pur_date" id="pur_date"  value="<?= date("d/m/Y")?>"required> 
                   
             </div>
         </div>
         <div class="col-md-12">
            <div class="form-group">
                   <label > Invoice Number <span style="color:red">*</span> </label>
                   <input type="text" class="form-control " name="invoice" id="invoice"  placeholder="Enter Invoice Number"required> 
                   
             </div>
         </div>
         <div class="col-md-12">
            <div class="form-group">
                   <label > Details</label>
                   <textarea name="details" class="form-control" id="details" cols="1" rows="1" ></textarea>
                   
             </div>
         </div>
         <div class="col-md-12">
            <div class="form-group">
                   <label > Payment Mode <span style="color:red">*</span> </label>
                   <select class="form-control  bg-white  " data-live-search="true" id="payment" name="payment">
       <option value="cash">Cash</option>
       <option value="online">Online</option>


    </select>
             </div>
         </div>
         <div class="col-md-12">
            <div class="form-group">
                   <label > Paid Amount</label>
                   <input type="text" class="form-control" placeholder="0" name="pamount" id="pamount" >
                   
             </div>
         </div>
         <div class="col-md-12">
             <div class="form-group">
                 <lable>Upload Document <span style="color:red">*</span></lable>
                  <input class="form-control" type="file" name="csv" id="csv" required accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
             </div>
         </div>
         
</div>


                </div>
            </div>
        </div>
    </div>
          
           <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" name="submit" value="bulk_purchase"class="btn btn-primary">Save changes</button>
      </div>
       </form>
      </div>
      
    </div>
  </div>
</div>

  

</body>

</html>