<?php 
require_once(__DIR__ . '/../model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/billingClass.php');
$newOP = new addBilling;

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
$dept=$newOP->getBillListOP($op_from,$op_to,$s);

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
        <form action="controller/processIpBill.php" method="post">
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
  <div>
  <textarea class="form-control" name="returned_note" id="returned_note" required placeholder="Enter Note"></textarea>
  </div>
  <input class="form-control"  type="hidden"  name="ip_dr" required id="ip_dr"  >
  <input class="form-control"  type="hidden"  name="dept_type" value="OP" required id="dept_type"  >
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
            <h4 class="text-uppercase">Billing List OP</h4>
        </div>
    </div>
    



<div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
  <div class="row">
                <div class="col-md-12">
                    <h4>OP Patient </h4><br>
                 
                </div>
            </div> 
<form action="" method="POST">
    <div class="row">

        <div class="col-md-4">
            <div class="form-group">
                <label for="">Search By</label>
                <input type="text" class="form-control" name="search" id="search" placeholder="Search by OP Number" value="<?= $s?>">
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
                                <h4 class="card-title">Billing List OP</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped display text-center">
                                        <thead>
                                            <tr  <?php if($res['status']=='CANCELLED') { ?> class="table-danger" <?php } ?>>
                                                <th>Sl NO</th>
                                                <!--<th>Id</th>-->
                                                <th>Bill No</th>
                                                 <th>OP Reg.No</th>
                                                <th>Department</th>
                                                <th>Visit Date</th>
                                                <th>Name</th>
                                                <th>Gender</th>
                                                <th>Address</th>
                                                <th>Particulars</th>
                                                <th>Price</th>
                                                <th>Total</th>
                                                <th>Grand Total</th>
                                                <th>Action</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            <?php $sn=1;
                                             while($res=mysqli_fetch_assoc($dept))
                                            { ?>
                                            <tr>
                                                <td><?= $sn?></td>
                                                
                                                <td><?= $res['ibinv']?></td>
                                                <td><?= $res['opd_no']?></td>
                                                <td><?=$res['dept_name'] ?></td>
                                                <td><?= date("d.m.Y",strtotime($res['date']))?></td>
                                                <td><?= $res['name']?></td>
                                                <td class="text-capitalize"><?= $res['gender']?></td>
                                                <td><?= $res['address']?></td>
                                                <td><?= $res['items']?></td>
                                                <td><?= $res['price']?></td>
                                                <td><?= $res['total']?></td>
                                                <td><?php if($res['status']=='CANCELLED') { echo "Bill Cancelled"; } else { echo $res['gt']; }?> </td>
                                                <td> <a href="print_op_bill.php?id=<?= $res['id']?>&inv=<?= $res['ibinv']?>" title="Print" class="text-primary"><i class="ri-printer-line" style="font-size:15px"></i> </a>
                                                <a type="button" data-toggle="modal" data-target="#return_amount" onclick="modalReturn('<?= $res['id']?>','<?= $res['opd_no']?>')" title="Return Amount" class="btn btn-success btn-sm mr-2 text-light">Return</a>
                                                <a href="cancel_bill.php?id=<?=$res['id']?>&inv=<?= $res['ibinv']?>&opd=<?= $res['opd_no']?>&date=<?= date("Y-m-d",strtotime($res['date']))?>&payment=<?= $res['payment_mode']?>"data-toggle="tooltip" title="Cancel Bill No <?= $res['ibinv']?>" class="badge badge-pill badge-danger"><i class="ri-close-line "></i></a></td>
 
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
     function modalReturn(val2,val)
    {
      document.getElementById('ip_dr').value=val2;  
      document.getElementById('ipd_dr').innerHTML=val;
    }
</script>
<script>
    function cancelBill(id)
    {
        // alert(id)
         $.ajax({
            type : 'post',
            url : 'ajax.php', //Here you will fetch records 
            data :  'id='+id+'&page=op_bill_cancel', //Pass $id
            success : function(data){
         $('#loadLab').load(" #loadLab");//Show fetched data from database
            }
        });
    }
</script>
    
    <!-- Datatable -->
    <script src="./vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="./js/plugins-init/datatables.init.js"></script>

</body>

</html>