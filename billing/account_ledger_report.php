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
    $search=$_REQUEST['search'];
}
elseif($_SESSION['role']=='Billing' || $_SESSION['role']=='Lab'){
    $search=$_SESSION['loginid'];
}
else{
    $search='';
}
if(isset($_REQUEST['payment_mode']))
{
    $pay=$_REQUEST['payment_mode'];
}
else{
    $pay='';
}
$dept=$newBill->getBillListDetailed($op_from,$op_to,$search);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>User wise Bill List</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">

    <link href="../vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" ></script>
    <!-- Datatable -->
 
    <link href="../vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">


       <!-- DataTables -->
    
        <link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/datatables.net-select-bs4/css/select.bootstrap4.min.css" rel="stylesheet" type="text/css" />


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
        <div class="content-body text-uppercase">
 <!-- row -->




<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-6">
            <h4 class="text-uppercase">User wise Collection Report </h4>
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

        <div class="col-md-3">
           <div class="form-group">
                  <label for="exampleInputEmail1"> From Date </label>
                  <input type="date" class="form-control"  name="op_from" id="op_from" required value="<?= $op_from?>">
                  
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1"> To Date  </label>
                   <input type="date" class="form-control"  name="op_to" id="op_to" required value="<?= $op_to?>">
                   
             </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label >Payment Mode</label>
                <select class="form-control" name="payment_mode" id="payment_mode">
                    <option value="">All</option>
                    <option value="Cash" <?php if($pay=='Cash') echo 'selected'; ?>>Cash</option>
                    <option value="UPI" <?php if($pay=='UPI') echo 'selected'; ?>>UPI</option>
                    <option value="Cheque" <?php if($pay=='Cheque') echo 'selected'; ?>>Cheque</option>
                    <option value="Card" <?php if($pay=='Card') echo 'selected'; ?>>Card</option>
                    <option value="Credit" <?php if($pay=='Credit') echo 'selected'; ?>>Credit</option>
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1">Select User </label>
                   <select class="form-control"  name="search" id="search" >
                       <option value="">All Users </option>
                       <?php $data=$newBill->getUser();
                       while($row=mysqli_fetch_assoc($data)) { ?>
                       <option value="<?= $row['user_name']?>" <?php if($search==$row['user_name']) echo 'selected';?>><?= $row['user_name']?></option>
                       <?php } ?>
                    </select>                   
             </div>
        </div>
         

    </div>
   
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
                          
                  
                            <div class="card-body">
                                  <div class="row">
                                  <div class="col-md-6">
                                        <h4 class="card-title"><?=$_REQUEST['type']?> Billing List</h4>
                                  </div>
                                
                   
                            </div>
                                
                                
                                <table id="datatable-buttons4" class="table text-wrap text-center table-bordered mt-2">
    <thead>
        <tr>
            <th>Sl No</th>
            <th>Date</th>
            <th>Bill Date</th>
            <th>Bill No</th>
            <th>Patient Reg No</th>
            <th>Name</th>
            <th>Gender</th>
            <th>Gross Amount</th>
            <th>Discount Amount</th>
            <th>Advance Adjustment</th>
            <th>Returned Amount</th>
            <th>Net Amount</th>
            <th>Billed By</th>
            <th>Receipt No</th>
            <th>Paid Amount</th>
            <th>Remark</th>
            <th>Payment Made</th>
            <th>Credit</th>
            <th>Collected By</th>
        </tr>
    </thead>
    <tbody class="text-left">
        <?php 
        $sn = 1;
        $gross_total = $discount_total = $advance_total = $returned_total = 0;
        $paid_total = $credit_total = $net_total = 0;

        while ($res = mysqli_fetch_assoc($dept)) {
            // Compute net amount and credit logic
            $gross = $res['amount'];
            $discount = $res['discount_amount'];
            $advance = $res['advance_amount'];
            $returned = $res['returned_amount'];
            $paid = $res['paid_amount'];
            
            $net = $gross - $discount - $returned - $advance;
            $credit = ($res['payment_mode'] == 'credit') ? $paid : 0;

            // Totals
            $gross_total += $gross;
            $discount_total += $discount;
            $advance_total += $advance;
            $returned_total += $returned;
            $paid_total += $paid;
            $credit_total += $credit;
            $net_total += $net;
        ?>
        <tr>
            <td><?= $sn ?></td>
            <td><?= date("d.m.Y h:i:s A", strtotime($res['date'])) ?></td>
            <td><?= date("d.m.Y", strtotime($res['bill_date'])) ?></td>
            <td><?= $res['bill_no'] ?></td>
            <td><?= $res['opd_no'] ?></td>
            <td><?= $res['name'] ?></td>
            <td><?= $res['gender'] ?></td>
            <td><?= number_format($gross, 2) ?></td>
            <td><?= number_format($discount, 2) ?></td>
            <td><?= number_format($advance, 2) ?></td>
            <td><?= number_format($returned, 2) ?></td>
            <td><?= number_format($net, 2) ?></td>
            <td><?= $res['billed_by'] ?></td>
            <td><?= $res['bill_receipt'] ?></td>
            <td><?= number_format($paid, 2) ?></td>
            <td><?= $res['payment_note'] ?></td>
            <td><?= strtoupper($res['payment_mode']) ?></td>
            <td><?= number_format($credit, 2) ?></td>
            <td><?= $res['collected_by'] ?></td>
        </tr>
        <?php $sn++; } ?>

        <!-- Totals Row -->
        <tr class="fw-bold bg-light">
            <th colspan="7" class="text-end" style="padding:8px;">Total</th>
            <th style="padding:8px;"><?= number_format($gross_total, 2) ?></th>
            <th style="padding:8px;"><?= number_format($discount_total, 2) ?></th>
            <th style="padding:8px;"><?= number_format($advance_total, 2) ?></th>
            <th style="padding:8px;"><?= number_format($returned_total, 2) ?></th>
            <th style="padding:8px;"><?= number_format($net_total, 2) ?></th>
            <th colspan="2"></th>
            <th style="padding:8px;"><?= number_format($paid_total, 2) ?></th>
            <th></th>
            <th style="padding:8px;"><?= number_format($credit_total, 2) ?></th>
            <th></th>
        </tr>
    </tbody>
</table>

                               
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
   
   
   
   
    <!-- Required datatable js -->
    <script src="assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
        <!-- Buttons examples -->
        <script src="assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
        <script src="assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
        <script src="assets/libs/jszip/jszip.min.js"></script>
        <script src="assets/libs/pdfmake/build/pdfmake.min.js"></script>
        <script src="assets/libs/pdfmake/build/vfs_fonts.js"></script>
        <script src="assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
        <script src="assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
        <script src="assets/libs/datatables.net-buttons/js/buttons.colVis.min.js"></script>

        <script src="assets/libs/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
        <script src="assets/libs/datatables.net-select/js/dataTables.select.min.js"></script>
        

        <!-- Datatable init js -->
        <script src="assets/js/pages/datatables.init.js"></script>

    <script>
    $(document).ready(function() {
            $('#datatable-buttons4').DataTable({

                dom: 'Bfrtip',
                responsive: true,
                pageLength: 25,
                // lengthMenu: [0, 5, 10, 20, 50, 100, 200, 500],
                
                buttons: [
           
            {
                extend: 'pdfHtml5',
                title: 'Center OP',
                 orientation: 'landscape',
                pageSize: 'LEGAL'
            }
        ]

            });
        });
</script>
       
</body>

</html>