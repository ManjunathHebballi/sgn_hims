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

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Bill List</title>
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
        <div class="content-body">
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
  <input class="form-control"  type="hidden"  name="type" value="<?=$_REQUEST['type']?>" required id="type"  >
  <input type="hidden" value="<?=$op_from?>" name="date_from" >
  <input type="hidden" value="<?=$op_to?>" name="date_to" >
</div>
            </div>
        </div>
       </div>
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit"  class="btn btn-primary" name="submit" value="return">Save </button>
      </div>
    </div>
    </form>
  </div>
</div>  <!-- row -->




<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <h4 class="text-uppercase"><?=$_REQUEST['type']?> Billing List </h4>
        </div>
    </div>
    



<div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
  <div class="row">
                <div class="col-md-12">
                    <!--<h4>OP Patient </h4><br>-->
                 
                </div>
            </div> 
<form action="" method="POST">
    <div class="row">

        <div class="col-md-4">
            <div class="form-group">
                <label for="">Search By</label>
                <input type="text" class="form-control" name="search" id="search" placeholder="Search by IP Number" value="<?= $s?>">
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
                                <h4 class="card-title"><?=$_REQUEST['type']?> Billing List</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped display text-center" id="loadBill">
                                        <thead>
                                            <tr  >
                                                <th>Sl NO</th>
                                                <!--<th>Id</th>-->
                                                <th>Bill No</th>
                                                 <th>IPD No</th>
                                                <th>Department</th>
                                                <th>Visit Date</th>
                                                <th>Name</th>
                                                <th>Gender</th>
                                                <th>Address</th>
                                                <th>Particulars</th>
                                                <th>Price</th>
                                                <th>Unit</th>
                                                <th>Total</th>
                                                <th>Discount</th>
                                                <th>Grand Total</th>
                                                <th>Action</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            <?php $sn=1;
                                            
$dept=$newBill->getBillListESI($op_from,$op_to,$s,$_REQUEST['type']);
                                             while($res=mysqli_fetch_assoc($dept))
                                            { ?>
                                            <tr <?php if($res['status']=='CANCELLED') { ?> class="table-danger" <?php } ?>>
                                                <td><?= $sn?></td>
                                                
                                                <td><?= $res['bill_no']?></td>
                                                <td><?= $res['ipd_no']?></td>
                                                <td><?=$res['dept_name'] ?></td>
                                                <td><?= date("d.m.Y",strtotime($res['date']))?></td>
                                                <td><?= $res['name']?></td>
                                                <td class="text-capitalize"><?= $res['gender']?></td>
                                                <td><?= $res['address']?></td>
                                                <td><?= $res['service']?></td>
                                                <td><?= $res['price']?></td>
                                                <td><?= $res['unit']?></td>
                                                <td><?= $res['gt']?></td>
                                                <td><?= $res['discount']+$res['b_discount']?></td>
                                                  <td><?php if($res['status']=='CANCELLED') { echo "Bill Cancelled"; } else { echo  $total=abs($res['gt']-$res['discount']-$res['b_discount']-$res['advance'])-$res['returned_amount']; }?> </td>
                                               
                                                 <td><?php if($res['status']!='CANCELLED') { ?>
                                                 <a href="print_esibill.php?id=<?= $res['ipd_no']?>&bill_no=<?= $res['bill_no']?>&type=<?=$_REQUEST['type']?>&status=<?=$res['status']?>" title="Print" class="btn btn-primary btn-sm mr-1 text-light"><i class="ri-printer-line" style="font-size:15px" target="_blank"></i> <?php } ?></a>
                                                <!--<a href="editesi_billing.php??editid=<?= $res['bill_no']?>&id=<?= $res['patient_id']?>"   title="Edit Bill" class="btn btn-info btn-sm mr-1 text-light"><i class="ri-edit-box-line" style="font-size:15px" target="_blank"></i></a>-->
                                                <a href="editesi_billing.php?editid=<?= $res['bill_no']?>&id=<?= $res['ipd_no']?>"   title="Edit Bill" class="btn btn-info btn-sm mr-1 text-light"><i class="ri-edit-box-line" style="font-size:15px" target="_blank"></i></a>
                                                <?php  if(($res['status']=='CANCELLED' || $res['outstand']<0) && ($res['gt']-($res['discount']+$res['b_discount'])!=$res['returned_amount'])) {?><a type="button" data-toggle="modal" data-target="#return_amount" onclick="modalReturn('<?= $res['bill_no']?>','<?= $res['opd_no']?>','<?php if($res['status']=='CANCELLED') { echo $res['gt']-$res['discount']-$res['returned_amount']-$res['b_discount']-$res['advance']; } else {echo abs($res['outstand']); }?>')" title="Return Amount" class="btn btn-success btn-sm mr-2 text-light">Return</a><?php } ?>
                                                <?php  if($res['status']!='CANCELLED' && $_SESSION['role']=='Admin') {?><a href="#" onclick="cancelBill('<?= $res['bill_no']?>')" data-toggle="tooltip" title="Cancel Bill <?=$res['bill_no']?>" class="badge badge-pill badge-danger"><i class="ri-close-line "></i></a><?php } ?></td>
 
                                            </tr>
                                           <?php $sn++; } 
                                           
                                          ?>
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
<script>
          function modalReturn(val2,val,tot)
    {
      document.getElementById('bill_no').value=val2;  
      document.getElementById('ipd_dr').innerHTML=val;
      document.getElementById('remain_amount').max=tot;
      document.getElementById('remain_amount').min=tot;
      document.getElementById('remain_amount').value=tot;
    }
</script>
<script>
    function cancelBill(bill)
    {
        // alert(id)
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
                        // Perform your AJAX call here
                        $.ajax({
                            type : 'post',
                            url : 'ajax.php', //Here you will fetch records 
                            data :  'bill_no='+bill+'&reason='+reason+'&page=bill_cancel', //Pass $id
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
</script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Datatable -->
    <script src="./vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="./js/plugins-init/datatables.init.js"></script>

</body>

</html>