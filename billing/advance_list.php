<?php 
require_once(__DIR__ . '/../model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/billingClass.php');
$newBill = new addBilling;

if(isset($_REQUEST['op_from']))
{
    $op_from=$_REQUEST['op_from'];
}
else{
        $op_from=date("Y-m-d");
}
if(isset($_REQUEST['op_to']))
{
    $op_to=$_REQUEST['op_to'];
}
else{
        $op_to=date("Y-m-d");
}
if(isset($_REQUEST['search']))
{
    $s=$_REQUEST['search'];
}
else{
        $s="";
}
$dept=$newBill->getAdvanceListIP($op_from,$op_to,$s);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Patient List</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" ></script>
    <!-- Datatable -->
    <link href="./vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">

    <style>
     #notification {
            position: absolute;
            top: 90px;
           
            right:2px;
            z-index: 99;
        }
</style>

</head>

<body >

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
        <div class="modal fade" id="advance_amount" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="max-width: 35%;" role="document">
    <div class="modal-content" width="150%">
      <div class="modal-header">

      
        <h5 class="modal-title" id="exampleModalLabel">Edit Advance  Amount of <span class="text-uppercase text-danger" id="ipd_name"></span> ( <span class="text-uppercase text-danger" id="ipd_dr"></span> )</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="controller/processBilling.php" method="post">
       
        <div class="col-md-12">
            <div class="row">
            <div class="col-md-7">
            <div class="form-group">
               <label for="amount">Amount</label>
               <div class="input-group mb-3">
  <div class="input-group-prepend">
    <span class="input-group-text" id="basic-addon1">Rs.</span>
  </div>
  <div>
  <input class="form-control" placeholder="Enter Amount" type="number"   name="advance_amount" required id="advance_amount1"  >
  </div>
  <input class="form-control"  type="hidden"  name="ipd" required id="ipd"  >
  <input type="hidden" name="editid" id="editid" value="">
</div>
            </div>
            </div>
                         <div class="col-md-5">
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
          <div class="col-md-12">
            <div class="form-group">
               <label for="ref_note">Ref Note</label>
            
  <textarea class="form-control" placeholder="Enter Ref Note" type="text"   name="ref_note"  id="ref_note"  ></textarea>
  </div>
 
</div>
        </div>
       </div>
      </div>
      
      <div class="modal-footer">
          
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit"  class="btn btn-primary" name="submit" value="advance">Save </button>
      </div>
    </div>
    </form>
  </div>
</div>
</div>
</div>
<div class="modal fade" id="return_amount" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">

      
        <h5 class="modal-title" id="exampleModalLabel">Return Remaining  Amount of <span class="text-uppercase text-danger" id="ipd_dr"></span></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="controller/processBilling.php" method="post">
       <div class="row">
        <div class="col-md-12">
            <div class="form-group">
               <label for="amount">Amount</label>
               <div class="input-group mb-3">
  <div class="input-group-prepend">
    <span class="input-group-text" id="basic-addon1">Rs.</span>
  </div>
  <div>
  <input class="form-control" placeholder="Enter Amount" type="number"   name="remain_amount" required id="remain_amount"  >
  </div>
  <input class="form-control"  type="hidden"  name="bill_no" required id="bill_no"  >
  <input type="hidden" value="<?=$op_from?>" name="date_from" >
  <input type="hidden" value="<?=$op_to?>" name="date_to" >
</div>
            </div>
        </div>
       </div>
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit"  class="btn btn-primary" name="submit" value="return_advance">Save </button>
      </div>
    </div>
    </form>
  </div>
</div>  <!-- row -->
</div></div>
        <div class="content-body">
            <!-- row -->





<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <h4 class="text-uppercase">Advance List IP</h4>
        </div>
    </div>
    



<div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
  <div class="row">
                <div class="col-md-12">
                    <h4>IP Patient </h4><br>
                 
                </div>
            </div> 
<form action="" method="POST">
    <div class="row">

        <div class="col-md-4">
            <div class="form-group">
                <label for="">Search By</label>
                <input type="text" class="form-control" name="search" id="search" placeholder="Search by IP Number">
            </div>
        </div>
        <div class="col-md-4">
           <div class="form-group">
                  <label for="exampleInputEmail1"> From Date </label>
                  <input type="date" class="form-control"  name="op_from" id="op_from" required value="<?= $op_from?>">
                  
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                   <label for="exampleInputEmail1"> To Date  </label>
                   <input type="date" class="form-control"  name="op_to" id="op_to" required value="<?= $op_to?>">
                   
             </div>
        </div>

         

    </div>
    <?php if(isset($_REQUEST['edit'])){?>
                    <input type="hidden" name="page" value="<?= $_REQUEST['edit']?>">
                    <?php } else {?> 
                        <input type="hidden" name="page" value='new'>   
                    <?php } ?>
    <div class="row">
            <div class="col-md-12">
            <button type="submit" class="btn btn-success  float-right btn-rounded"  name="submit"><span><i class="bi bi-search"></i></span> Search</button>
            </div>    
    </div>
</form>
        
    </div>
            </div>
        </div>
    </div>
    <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Advance List IP</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped display text-center" id="loadAdv">
                                        <thead>
                                            <tr>
                                                <th>Sl NO</th>
                                                <th>Advance Receipt No</th>
                                                 <th>OPD No</th>
                                                 <th>IPD No</th>
                                                <th>Department</th>
                                               
                                                
                                                <th>Name</th>
                                                <th>Gender</th>
                                                <th>Address</th>
                                                 <th>Paid Date</th>
                                                <th>Advance</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            <?php $sn=1;
                                             while($res=mysqli_fetch_assoc($dept))
                                            { ?>
                                            <tr>
                                                <td><?= $sn?></td>
                                                <td><?= $res['adv_id']?></td>
                                                <td><?= $res['opd_no']?></td>
                                                <td><?= $res['ipd_no']?></td>
                                                <td><?=$res['dept_name'] ?></td>
                                              
                                                
                                                <td><?= $res['name']?></td>
                                                <td><?= $res['gender']?></td>
                                                <td><?= $res['address']?></td>
                                                  <td><?= date("d.m.Y",strtotime($res['adv_date']))?></td>
                                                  <td><?= $res['adv_amt']?></td>
                                                <td><?php if( $res['status']=='PAID') { echo "PAID"; } else { echo "CANCELLED"; } ?></td>
                                                <td> <a target="_blank" href="print_advance_amount.php?id=<?= $res['ipd_no']?>&adv_id=<?= $res['adv_id']?>&status=<?= $res['status']?>" title="Print" class="text-primary"><i class="ri-printer-line" style="font-size:15px"></i> </a>
                                                
                                                 <?php  if($res['status']=='CANCELLED' && ($res['adv_amt']-$res['returned_amount']) >0) { ?>
                                                 
                                                 <a type="button" data-toggle="modal" data-target="#return_amount" onclick="modalReturn('<?= $res['adv_id']?>','<?= $res['ipd_no']?>','<?= $res['adv_amt'] ?>')" title="Return Amount" class="btn btn-success btn-sm mr-2 text-light">Return</a><?php } ?>
                                                 <?php  if($res['status']!='CANCELLED' && ($_SESSION['role']=='Admin' || $_SESSION['loginid']==57) ) {?>  
                                                 <a type="button" data-toggle="modal" data-target="#advance_amount" onclick="modalAdvance('<?= $res['adv_id']?>','<?= $res['ipd_no']?>','<?= $res['name']?>','<?= $res['adv_amt'] ?>','<?= $res['payment_type'] ?>','<?= $res['payment_note'] ?>')" title="Edit Advance Amount" class="badge badge-pill badge-success "><i class="ri-edit-box-line" style="font-size:15px" target="_blank"></i></a>
                                                 <a href="#" onclick="cancelAdvance(<?= $res['adv_id']?>)" data-toggle="tooltip" title="Cancel Advance <?=$res['adv_id']?>" class="badge badge-pill badge-danger"><i class="ri-close-line "></i></a><?php } ?>
                                                </td>

                                            </tr>
                                           <?php
                                           if($res['status']!='CANCELLED')
                                           {
                                               $total +=$res['adv_amt']; 
                                           }
                                          
                                           $sn++; } 
                                           
                                          ?>
                                          <tr><td colspan=9 class="text-right">total</td>
                                          <td><?= $total?></td><td colspan=2></td></tr>
                                            </tbody>
                                            </table>   
                                            </div>  
                                            </div>
                                            </div></div></div>
        </div>
</div>
        <!--**********************************
            Content body end
        ***********************************-->


        <!--**********************************
            Footer start
        ***********************************-->
        <?php include('footer.php');
       ?>
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

    <!--**********************************ed
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="./vendor/global/global.min.js"></script>
    <script src="./js/quixnav-init.js"></script>
    <script src="./js/custom.min.js"></script>

    
    <!-- Datatable -->
    <script src="./vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="./js/plugins-init/datatables.init.js"></script>
    <script>
    function cancelAdvance(bill)
    {
        Swal.fire({
                title: 'Are you sure?',
                text: 'Please provide a reason for canceling the bill:',
                input: 'text',
                showCancelButton: true,
                confirmButtonText: 'Yes, cancel it!',
                cancelButtonText: 'No, keep it',
                preConfirm: (reason) => {
                    // If the user clicks 'Yes', proceed with the AJAX call
                    if (reason) {
        // alert(id)
         $.ajax({
            type : 'post',
            url : 'ajax.php', //Here you will fetch records 
            data :  'adv_no='+bill+'&reason='+reason+'&page=advance_cancel', //Pass $id
            success : function(data){
         Swal.fire({
                                    title: 'Cancelled!',
                                    text: 'The bill has been canceled.',
                                    icon: 'success'
                                }).then((result) => {
                                    // Reload the page if the user clicks "OK"
                                    if (result.isConfirmed) {
                                        location.reload();
                                    }
                                });
                            },
                            error: function(xhr, status, error) {
                                // Handle error response
                                Swal.fire('Error!', 'An error occurred while canceling the bill.', 'error');
                            }
                        });
                    } else {
                        // If the user didn't provide a reason, show an error alert
                        Swal.fire('Error!', 'Please provide a reason to cancel the bill.', 'error');
                    }
                }
            });
        
    }
    function modalReturn(val2,val,tot)
    {
      document.getElementById('bill_no').value=val2;  
      document.getElementById('ipd_dr').innerHTML=val;
      document.getElementById('remain_amount').max=tot;
      document.getElementById('remain_amount').min=tot;
      document.getElementById('remain_amount').value=tot;
    }
      function modalAdvance(val2,val,name,val3,val4,val5)
    {
      document.getElementById('ipd').value=val;  
      document.getElementById('ipd_dr').innerHTML=val;
      document.getElementById('ipd_name').innerHTML=name;
      document.getElementById('editid').value=val2;
      document.getElementById('advance_amount1').value=val3;
      document.getElementById('payment_mode').value=val4;
      document.getElementById('ref_note').value=val5;
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>