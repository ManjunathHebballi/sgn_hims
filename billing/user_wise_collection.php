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
if(isset($_REQUEST['service']))
{
    $service=$_REQUEST['service'];
}
else{
    $service='';
}
$dept=$newBill->getBillListUserwise($op_from,$op_to,$search,$pay,$service);

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

        <div class="col-md-2">
           <div class="form-group">
                  <label for="exampleInputEmail1"> From Date </label>
                  <input type="date" class="form-control"  name="op_from" id="op_from" required value="<?= $op_from?>">
                  
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                   <label for="exampleInputEmail1"> To Date  </label>
                   <input type="date" class="form-control"  name="op_to" id="op_to" required value="<?= $op_to?>">
                   
             </div>
        </div>
        <div class="col-md-2">
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
                <label >Service Type</label>
                <select class="form-control" name="service" id="service">
                    <option value="">All</option>
                    
                    <option value="OP" <?php if($service=='OP') echo 'selected'; ?>>OP</option>
                    <option value="IP" <?php if($service=='IP') echo 'selected'; ?>>IP</option>
                    <option value="Pathology" <?php if($service=='Pathology') echo 'selected'; ?>>Lab</option>
                    <option value="Blood Bank" <?php if($service=='Blood Bank') echo 'selected'; ?>>Blood Bank</option>
                    <option value="Radiology" <?php if($service=='Radiology') echo 'selected'; ?>>Radiology</option>
                    <option value="Dialysis" <?php if($service=='Dialysis') echo 'selected'; ?>>Dialysis</option>
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
                                  <div class="col-md-6">
           <a href='print_user_wise_collection.php?from=<?= $op_from?>&to=<?= $op_to?>&search=<?= $search?>&payment_mode=<?= $pay?>&service=<?= $service?>' class="btn btn-danger btn-sm float-right">Print Summary</a>
           <a href='print_user_wiseDetailed_collection.php?from=<?= $op_from?>&to=<?= $op_to?>&search=<?= $search?>&payment_mode=<?= $pay?>&service=<?= $service?>' class="btn btn-warning btn-sm mr-2 float-right">Print Detailed</a>
        </div>
                   
                            </div>
                                
                                
                                <table id="datatable-buttons4" class="table text-wrap text-center table-bordered mt-2">
                                    <thead>
                                           <tr>
                                                <th>Sl NO</th>
                                                <th>Name</th>
                                                <th>Collection Type</th>
                                                 <th>OP</th>
                                                <th>IP</th>
                                                <th>Pathology</th>
                                                <th>Radiology</th>
                                                <th>Dialysis</th>
                                                <th>Blood Bank</th>
                                                <th>Advance</th>
                                                <th>OP Refund</th>
                                                <th>IP Refund</th>
                                                <th>Total</th>
                                               
                                                
                                            </tr>
                                    </thead>
                                  <tbody class="text-center">
                                            <?php $sn=1;$total=0;$op=$ip=$patho=$blood=$radio=$dialysis=$advance=$op_ret=$ip_ret=0; 
                                            $user="";$op1=$ip1=$patho1=$blood1=$radio1=$dialysis1=$advance1=0;
                                             while($res=mysqli_fetch_assoc($dept))
                                            { ?>
                                            <tr>
                                                <?php if($user!=$res['collected_by']) { 
                                                $total1=0;
                                                if($sn>1) { ?>
                                                
                                                    <th colspan=3 class="text-right">Total</th>
                                                    <th><?= $op1?></th>
                                                    <th><?= $ip1?></th>
                                                    <th><?= $patho1?></th>
                                                    <th><?= $radio1?></th>
                                                    <th><?= $dialysis1?></th>
                                                    <th><?= $blood1?></th>
                                                    <th><?= $advance1?></th>
                                                    <th><?= $op_ret1?></th>
                                                    <th><?= $ip_ret1?></th>
                                                    <th><?= $op1+$ip1 +$patho1 +$radio1 +$dialysis1+ $blood1 +$advance1-$op_ret1-$ip_ret1;?></th>
                                                </tr>
                                               <tr>
                                                    <?php
                                                    } ?>
                                                <td><?= $sn?></td>
                                                <td><?= $res['collected_by']?></td>
                                                <?php $op1=$ip1=$patho1=$blood1=$radio1=$dialysis1=$advance1=$op_ret1=$ip_ret1=0; $sn++;} else {?>
                                                <td colspan=2></td>
                                                <?php } ?>
                                                <td><?= $res['payment_mode']?></td>
                                                <td><?= $res['op_bill']?></td>
                                                <td><?= $res['ip_bill']?></td>
                                                <td><?= $res['patho_bill']?></td>
                                                <td><?= $res['radio_bill']?></td>
                                                <td><?= $res['dialysis_bill']?></td>
                                                <td><?= $res['blood_bill']?></td>
                                                <td><?= $res['advance']?></td>
                                                <td><?= $res['op_return']?></td>
                                                <td><?= $res['ip_return']?></td>
                                                <td><?= $res['blood_bill']+$res['dialysis_bill']+$res['radio_bill']+$res['op_bill']+$res['patho_bill']+$res['ip_bill']+$res['advance']-$res['op_return']-$res['ip_return']?></td>
                                            </tr>
                                           <?php
                                           $op1 +=$res['op_bill'];
                                            $ip1 +=$res['ip_bill'];
                                            $patho1 +=$res['patho_bill'];
                                            $radio1 +=$res['radio_bill'];
                                            $dialysis1 +=$res['dialysis_bill'];
                                            $blood1 +=$res['blood_bill'];
                                            $advance1 +=$res['advance'];
                                            $op +=$res['op_bill'];
                                            $ip +=$res['ip_bill'];
                                            $patho +=$res['patho_bill'];
                                            $radio +=$res['radio_bill'];
                                            $dialysis +=$res['dialysis_bill'];
                                            $blood +=$res['blood_bill'];
                                            $advance +=$res['advance'];
                                            $op_ret1 +=$res['op_return'];
                                            $ip_ret1 +=$res['ip_return'];
                                            $op_ret +=$res['op_return'];
                                            $ip_ret +=$res['ip_return'];
                                            $user=$res['collected_by'];
                                             
                                            } 
                                           
                                          ?>
                                          <tr>
                                                    <th colspan=3 class="text-right">Total</th>
                                                    <th><?= $op1?></th>
                                                    <th><?= $ip1?></th>
                                                    <th><?= $patho1?></th>
                                                    <th><?= $radio1?></th>
                                                    <th><?= $dialysis1?></th>
                                                    <th><?= $blood1?></th>
                                                    <th><?= $advance1?></th>
                                                    <th><?= $op_ret1?></th>
                                                    <th><?= $ip_ret1?></th>
                                                    <th><?= $op1+$ip1 +$patho1 +$radio1 +$dialysis1+ $blood1 +$advance1-$op_ret1-$ip_ret1;?></th>
                                                </tr>
                                            <tr>
                                                <th colspan=3 class="text-center">All Users Total</th>
                                                <th><?= $op?></th>
                                                <th><?= $ip?></th>
                                                <th><?= $patho?></th>
                                                <th><?= $radio?></th>
                                                <th><?= $dialysis?></th>
                                                <th><?= $blood?></th>
                                                <th><?= $advance?></th>
                                                <th><?= $op_ret?></th>
                                                <th><?= $ip_ret?></th>
                                                <th><?= $op+$ip +$patho +$radio +$dialysis+ $blood +$advance-$op_ret-$ip_ret;?></th>
                                            </tr>
                                           <tr>
                                                <table class="table table-bordered mt-4">
                                                    <tr>
                                                <th colspan="12" class="text-center">Currency Denomination </th>
                                            </tr>
                                                <th>Denomination</th>
                                               
                                                <th class="text-center">500</th>
                                                <th class="text-center">200</th>
                                                <th class="text-center">100</th>
                                                <th class="text-center">50</th>
                                                <th class="text-center">20</th>
                                                <th class="text-center">10</th>
                                                <th class="text-center">5</th>
                                                <th class="text-center">2</th>
                                                <th class="text-center">1</th>
                                                <th class="text-center">Coins</th>
                                            </tr>
                                            <tr>
                                                <th>Counts</th>
                                                  
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                             <tr>
                                                <th>Total</th>
                                                 
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                                </table>
                                            </tr>
                                            </tbody>  </table>
                               
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