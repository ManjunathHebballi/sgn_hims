<?php 

require_once(__DIR__ . '/../model/session_config.php');
include('model/billingClass.php');
$newBill = new addBilling;

$flag = isset($_REQUEST['flag']) ? (int) $_REQUEST['flag'] : 0;

$result=$newBill->getPatient($_REQUEST['id'],$_REQUEST['bill_no']);

 $row1=mysqli_fetch_assoc($result); 
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>View OP Billing</title>
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

     .watermark {
                position: absolute;
                font-size: 50px; /* Adjust font size as needed */
                color: rgba(0, 0, 0, 0.5); /* Set text color with reduced opacity */
                transform: rotate(-45deg) scaleX(2);
                padding-left:300px;
                padding-top:300px;
            }
      </style>
  </head>
  <body onload="getButton()">
     
  <div id="main-wrapper">
<?php include ('leftbar.php')?>
<div class="content-body">
       <div class="container-fluid page-body-wrapper">
            <div class="main-panel">
                <div class="content-wrapper ">
            <div class="row">
                                      <div class="col-md-8">
                                             <h3 class="page-title text-dark font-weight-bold mb-2 px-4">VIEW BILLING [<?= $row1['bill_no']?>]</h3>
                                      </div>
                                     
                                  </div>
       
                                    
            <div class="row mb-4 mt-3">
                <div class="col-md-12">
                 
                            
            <div class="card">
                <div class="card-body">

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">

                    <label for="pname">OPD No</label>
                    <input onchange="javascript:patientDetail(this.value)" class="form-control text-uppercase" type="text" value="<?= $row1['opd_no']?>" readonly>
                     <input type="hidden"  name="patient_id" id="patient_id" value="" >
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">

                    <label for="pname">Patient Name</label>
                    <input class="form-control text-uppercase" type="text" readonly value="<?= $row1['name']?>" >

                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">

                    <label for="pname">Age</label>
                    <input class="form-control text-uppercase" type="text" readonly value="<?= $row1['age']?>" >

                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">

                    <label for="pname">Gender</label>
                    <input class="form-control text-uppercase" type="text" readonly  value="<?= $row1['gender']?>" >

                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">

                    <label for="pname">Mobile Number</label>
                    <input class="form-control text-uppercase" type="text"  value="<?= $row1['mobile']?>" readonly>

                        </div>
                    </div>
                    <div class="col-md-3">
                <div class="form-group">
                    <label >Doctor <small class="text-danger"> *</small></label>
                    <input class="form-control text-uppercase" type="text" value="<?= $row1['doc']?>" readonly>
                 

                </div>
         </div>
         <div class="col-md-3">
                <div class="form-group">
                    <label >Departement <small class="text-danger"> *</small></label>

                     <input class="form-control text-uppercase" type="text"  value="<?= $row1['dept_name']?>" readonly>

                </div>
         </div>
      
                  
                 

                            <div class="col-md-3">
                   <div class="form-group">
                      <label for="date">Billing Date</label>
                      <input class="form-control text-uppercase"  value="<?php echo date("Y-m-d H:i:s",strtotime($row1['bill_date'])); ?>" >
                    </div>
                    
                   </div>
                    



                </div>
               

            
                </div>
            </div>
                </div>
            </div>
            

<div class="row" >
    <div class='col-sm-12'>
        <button type="button" class="btn btn-primary btn-sm float-right" data-toggle="modal" id="pay_button" style="display:none;"data-target="#exampleModal">
  Record Payment
</button>
    </div>
    <div class="col-md-12">
        <!-- Button trigger modal -->



        <div class="card">
            <div class="card-body table-responsive">
            <h3>Billing Summary</h3>
                <table class="table table-bordered"   >
     <thead>
            <tr>
                <th>Items</th>
               <th>Doctor</th>
                <th>Unit</th>
                <th>Price</th>
                <th>Amount</th>
                <th>Disc Amt</th>
                <th>Total Amount</th>
            </tr>
        </thead>
            <tbody>
                    <?php $result=$newBill->getBill($_REQUEST['id'],$_REQUEST['bill_no']);
                        $gross=0;
                        $disc=0;
                        $net=0;
                        $sn=1;
                        $collected='';
                        $disc_amt=0;
                while ($row=mysqli_fetch_assoc($result))
                { 
               


                ?>
                    <tr>
                        <td class="text-center"><?php echo $sn; ?></td>
                        <td class="text-left pl-1"><?php echo $row['service'].' ['.$row['doctor_name'].']'; ?></td>
                         <td class="text-center"><?php echo $row['unit']; ?></td>
                        <td class="text-center"><?php echo $row['price']; ?></td>
                        <td class="text-center"><?php echo $row['unit']*$row['price']; ?></td>
                        <td class="text-center"><?php echo $row['discount']; ?></td>
                        <td class="text-center"><?php echo ($row['unit']*$row['price'])-$row['discount']; ?></td>
                       
                        
                    </tr>
                <?php if($_REQUEST['status']=='CANCELLED') { ?>    <div class="watermark top-left">CANCELLED</div> <?php } ?>
                    <?php $sn++;
                          $gross +=$row['unit']*$row['price'];
                          $disc +=$row['discount'];
                          $net += ($row['unit']*$row['price'])-$row['discount'];
                          $collected=$row['collected_by'];
                          $disc_amt=$row['discount_amount'];
                          $paid=$row['paid_amount'];
                          $return=$row['returned_amount'];
                          $serv_type=$row['service_type'];
                    }  ?>

                </tbody>
            <!-- </table>
            <table class="table table-responsive table-bordered"   id="tab_logic"> -->
            <tfoot> 
            <!-- <tr id='addr110'></tr> -->
            <tr >

                <td colspan="5" > </td>
                <td class="text-center"> Gross Amount </td>
                <td class="text-center">
                <?= $gross?>
                </td>
                 
            </tr>
            <tr >

                <td colspan="5" > </td>
                <td class="text-center"> Service Level Discount </td>
                <td class="text-center">
                <?= $disc?>
                </td>
                
            </tr>
            <tr >

                <td colspan="5" > </td>
                <td class="text-center"> Net Amount </td>
                <td class="text-center">
                <?= $gross-$disc?>
                </td>
                
            </tr>
         
            
            <tr>
                <td colspan="5" > </td>
                <td class="text-center"> Discount Amount </td>
                <td class="text-center">
                <?= $disc_amt?>
                </td>
            </tr>
            <tr>
                <td colspan="5" > </td>
                <td class="text-center"> Grand Total Amount </td>
                <td class="text-center">
               <?= $grand_total=$gross-$disc-$disc_amt?>
                </td>
            </tr>
            <tr>
                <td colspan="5" > </td>
                <td class="text-center"> Amount Paid </td>
                <td class="text-center">
                 <?= $paid?>
                </td>
            </tr>
            <?php if($return>0) { ?>
             <tr>
                <td colspan="5" > </td>
                <td class="text-center"> Returned Amount </td>
                <td class="text-center">
                 <?= $return?>
                </td>
            </tr>
            <?php } ?>
            <tr >

                <td colspan="5" > </td>
                <td class="text-center"> Outstanding amount </td>
                <td class="text-center">
                <?php
                if($grand_total<$paid)
                {
                 echo $outstand=$gross-$disc-$disc_amt-$paid+$return;
                }
                else{
                   
                
                echo $outstand=$gross-$disc-$disc_amt-$paid-$return;
                }
                ?>
                </td>
               
            </tr>
       
       
               
           
        <input type="hidden" name="id" id="id" value=1>
         

         
        </tfoot>
    </table>
           



            
            </div>
        </div>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Payment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <div class="container mt-4">
  <form method="post" action="controller/processBilling.php" id="form1">
      <div class="mb-3">
      <p><b>Due Amount : </b>Rs. <?= $outstand?></p>
    </div>
    <div class="mb-3">
      <label for="payment_made" class="form-label">Amount paid</label>
      <input type="number" class="form-control" id="payment_made" name="payment_made" max="<?= $outstand?>" placeholder="Enter amount" value="<?= $outstand?>" required>
    </div>

    <div class="mb-3">
      <label for="payment_date" class="form-label">Payment Date</label>
      <input type="datetime-local" class="form-control" id="payment_date" name="payment_date" value="<?php echo date("Y-m-d H:i:s"); ?>" required>
    </div>

    <div class="mb-3">
      <label for="payment_method" class="form-label">Payment Method</label>
      <select class="form-control" id="payment_method" name="payment_method" required>
        <option value="" disabled selected>Select method</option>
        <option value="Cash">Cash</option>
        <option value="Card">Card</option>
        <option value="UPI">UPI</option>
        <option value="Bank Transfer">Bank Transfer</option>
        <option value="Cheque">Cheque</option>
      </select>
    </div>

    <div class="mb-3">
      <label for="reference_number" class="form-label">Reference Note</label>
      <input type="text" class="form-control" id="reference_number" name="reference_note" placeholder="Enter reference note" >
    </div>

  
</div>

      </div>
      <div class="modal-footer">
           <input type="hidden" name="patient_type" id="patient_type" value="OP">
           <input type="hidden" name="service_type" id="service_type" value="OP">
          <input type="hidden" name="bill_id" value="<?= $_REQUEST['bill_id']?>">
          <input type="hidden" name="bill_no" value="<?= $_REQUEST['bill_no']?>"><input type="hidden" name="id" value="<?= $_REQUEST['id']?>">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <input type="hidden" name="submit" value="payment">
        <button type="submit" id="submitBtn" class="btn btn-primary">Submit</button>
      </div>
    </div>
  </div>
</div>
</form>
    </div>
    <div class='col-sm-12'>
      <a href="print_bill.php?id=<?= $_REQUEST['id']?>&bill_no=<?= $_REQUEST['bill_no']?>&type=<?= $serv_type?>" target="_blank" class="btn btn-primary float-right ml-3 d-print-none">Print Bill</a>
    <button class="btn btn-danger mb-4 float-right d-print-none" onclick="window.location='<?= $flag === 1 ? '../opregistration.php' : 'bill_list.php?type=' . urlencode($serv_type) ?>'"> Go Back</button>
  </div>
</div>

<?php $result1=$newBill->getBillPayment($_REQUEST['bill_id'],$_REQUEST['bill_no']); 
if(mysqli_num_rows($result1)>0) {
?>
<div class='card'>
    <div class='card-body'>
        <h5>Payment Information</h5>
<table class='table table-bordered'>
    
    <thead>
        <tr>
            <th>Sl no</th>
            <th>Recipt No.</th>
            <th>Date</th>
            <th>Amount</th>
            <th>Payment Mode</th>
            <th>Payment Note</th>
            <th>Status</th>
            <th>Collected By</th>
            <th>Action</th>
        </tr>
        
        
    </thead>
    
    <tbody>
        <?php
        $sn=1;
        while($res1=mysqli_fetch_assoc($result1)) {
        ?>
        <tr>
            <td><?= $sn?></td>
            <td><?= $res1['bill_receipt']?></td>
            <td><?= date("d.m.Y h:i:s A",strtotime($res1['date']))?></td>
            <td><?= $res1['amount']?></td>
            <td><?= $res1['payment_mode']?></td>
            <td><?= $res1['payment_note']?></td>
            <td><?= $res1['payment_status']?></td>
            <td><?= $res1['collected_by']?></td>
            <td>
                <?php 
                if($_SESSION['role']=='Admin' || $_SESSION['role']=='Accountant' || $_SESSION['name']==$res1['collected_by']) {
                ?>
                <a href="#" title="Edit Bill" class="btn btn-info btn-sm mr-1 text-light edit-bill-btn" data-toggle="modal" data-target="#editBillModal"
                   data-payment-id="<?= $res1['id'] ?>"
                   data-amount="<?= $res1['amount'] ?>"
                   data-mode="<?= htmlspecialchars($res1['payment_mode'], ENT_QUOTES) ?>"
                   data-note="<?= htmlspecialchars($res1['payment_note'], ENT_QUOTES) ?>"
                   data-date="<?= date('Y-m-d\TH:i', strtotime($res1['date'])) ?>"
                   data-status="<?= htmlspecialchars($res1['payment_status'], ENT_QUOTES) ?>"
                   data-collected-by="<?= htmlspecialchars($res1['collected_by'], ENT_QUOTES) ?>">
                   <i class="ri-edit-box-line" style="font-size:15px"></i>
                </a>
                <?php } ?>
                <a href="print_receipt.php?id=<?= $_REQUEST['id']?>&bill_no=<?= $_REQUEST['bill_no']?>&type=<?= $serv_type?>&bill_id=<?=$res1['id']?>" title="Print" class="btn btn-primary btn-sm mr-1 text-light" target="_blank"><i class="ri-printer-line" style="font-size:15px" ></i></a></td>
        </tr>
        <?php $sn++; } ?>
    </tbody>



</table>
    </div>
</div>

<?php } ?>

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

<script>
    function getButton()
    {
        let cond = <?= $outstand?>;
        if(cond>0)
        {
            document.getElementById("pay_button").style.display='block';
        }
    }
    $('form1').submit(function(){
      document.getElementById("submitBtn").disabled = true;
  document.getElementById("submitBtn").innerText = "Submitting...";
});
</script>






<!-- Datatable -->
<script src="./vendor/datatables/js/jquery.dataTables.min.js"></script>
<script src="./js/plugins-init/datatables.init.js"></script>
<script src="./js/plugins-init/datatables.init.js"></script>

    
        <script src="./vendor/select2/js/select2.full.min.js"></script>
    <script src="./js/plugins-init/select2-init.js"></script>


    <!-- End custom js for this page-->

<!-- Edit Bill Modal -->
<div class="modal fade" id="editBillModal" tabindex="-1" aria-labelledby="editBillModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post" action="controller/processBilling.php" id="editBillForm">
        <div class="modal-header">
          <h5 class="modal-title" id="editBillModalLabel">Edit Payment</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
            <?php 
            $readonly = $_SESSION['role']!='Admin' && $_SESSION['role']!='Accountant' ? 'readonly' : '';
            ?>
          <input type="hidden" name="payment_id" id="edit_payment_id">
          <input type="hidden" name="bill_id" value="<?= $_REQUEST['bill_id'] ?>">
          <input type="hidden" name="bill_no" value="<?= $_REQUEST['bill_no'] ?>">
          <input type="hidden" name="id" value="<?= $_REQUEST['id'] ?>">
          <div class="form-group">
            <label>Amount</label>
            <input type="number" step="0.01" class="form-control" name="amount" id="edit_amount"  <?= $readonly ?>  required >
          </div>
          <div class="form-group">
            <label>Payment Date</label>
            <input type="datetime-local" class="form-control" name="date" id="edit_date" <?= $readonly ?> required>
          </div>
          <div class="form-group">
            <label>Payment Method</label>
            <select class="form-control" name="payment_mode" id="edit_mode" required>
              <option value="Cash">Cash</option>
              <option value="Card">Card</option>
              <option value="UPI">UPI</option>
              <option value="Bank Transfer">Bank Transfer</option>
              <option value="Cheque">Cheque</option>
            </select>
          </div>
          <div class="form-group">
            <label>Reference Note</label>
            <input type="text" class="form-control" name="payment_note" id="edit_note">
          </div>
          <?php
          if($_SESSION['role']=='Admin' || $_SESSION['role']=='Accountant') {
          ?>
            <div class="form-group">
                   <label for="exampleInputEmail1">Collected By</label>
                   <select class="form-control"   name="collected_by" id="edit_collected_by" >
                       <?php $data=$newBill->getUser();
                       while($row=mysqli_fetch_assoc($data)) { ?>
                       <option value="<?= $row['user_name']?>"><?= $row['user_name']?></option>
                       <?php } ?>
                    </select>                   
             </div>
            <?php } else {?>
                <input type="hidden" name="collected_by" id="edit_collected_by" value="<?= $_SESSION['name']?>">
            <?php } ?>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="submit" value="update_payment">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- /Edit Bill Modal -->

<script>
  $('#editBillModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    $('#edit_payment_id').val(button.data('payment-id'));
    $('#edit_amount').val(button.data('amount'));
    $('#edit_mode').val(button.data('mode'));
    $('#edit_note').val(button.data('note'));
    $('#edit_date').val(button.data('date'));
    $('#edit_collected_by').val(button.data('collected-by'));
  });
</script>

  </body>
</html>