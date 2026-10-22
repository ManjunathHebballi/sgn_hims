<?php

date_default_timezone_set("Asia/Kolkata"); 

require_once(__DIR__ . '/../model/session_config.php');
$date=date("Y-m-d");

include('model/reportClass.php');
$newReport = new report;

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
        $op_to=$date;
}
if(isset($_REQUEST['dept_from']))
{
    $dept_from=$_REQUEST['dept_from'];
}
else{
        $dept_from=$date;
}
if(isset($_REQUEST['dept_to']))
{
    $dept_to=$_REQUEST['dept_to'];
}
else{
        $dept_to=$date;
}
if(isset($_REQUEST['cent_from']))
{
    $cent_from=$_REQUEST['cent_from'];
}
else{
        $cent_from=$date;
}
if(isset($_REQUEST['cent_to']))
{
    $cent_to=$_REQUEST['cent_to'];
}
else{
        $cent_to=$date;
}

if(isset($_REQUEST['proce_from']))
{
    $proce_from=$_REQUEST['proce_from'];
}
else{
        $proce_from=$date;
}
if(isset($_REQUEST['proce_to']))
{
    $proce_to=$_REQUEST['proce_to'];
}
else{
        $proce_to=$date;
}
if(isset($_REQUEST['proced_from']))
{
    $proced_from=$_REQUEST['proced_from'];
}
else{
        $proced_from=$date;
}
if(isset($_REQUEST['proced_to']))
{
    $proced_to=$_REQUEST['proced_to'];
}
else{
        $proced_to=$date;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Radiology Report</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">

    <link href="./vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">


       <!-- DataTables -->
    
        <link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/datatables.net-select-bs4/css/select.bootstrap4.min.css" rel="stylesheet" type="text/css" />

     <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>




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

  <?php include ('leftbar.php');?>

  <!-- View -->





        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body text-uppercase">
            <!-- row -->
        <div class="container-fluid">

        <div class="row mb-4">
          <h4 class="text-uppercase ml-3">Radiology Report</h4>
        </div>


        <ul class="nav nav-pills mb-3 justify-content-around" id="pills-tab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">X-RAY Report</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="pills-deptwise-tab" data-toggle="pill" href="#pills-deptwise" role="tab" aria-controls="pills-deptwise" aria-selected="false"> CT Scan Report</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="pills-combinedop-tab" data-toggle="pill" href="#pills-combinedop" role="tab" aria-controls="pills-combinedop" aria-selected="false">USG Report</a>
  </li>

  
  

</ul>


    

       

<div class="tab-content" id="pills-tabContent">
  <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab"><br>
  <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
  <div class="row">
                <div class="col-md-12">
                    <h4>Radiology Report </h4><br>
                 
                </div>
            </div> 
<form action="#pills-home-tab" method="GET">
    <div class="row">
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

         <div class="col-md-4">
            <div class="form-group">
                   <label for="exampleInputEmail1"> Doctor  </label>
                   <select  class="form-control select1doctor"  name="doctor1" id="doctor1" >
                       <option value="">All</option>
                       <?php $data=$newReport->doctor();
                       while($row=mysqli_fetch_assoc($data)){ ?>
                       <option value="<?= $row['name'].' '.$row['last_name']?>" <?php if($row['name'].' '.$row['last_name']==$_REQUEST['doctor1']) echo 'selected';?>><?= $row['name'].' '.$row['last_name']?></option>
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
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                        <h5 class="ml-4">X-RAY Report <span class="text-danger">
                            <?php if(isset($_REQUEST['op_from']) && isset($_REQUEST['op_to'])) {
                            echo "Between ".  date('d.m.Y',strtotime($_REQUEST['op_from']))." and ". date('d.m.Y',strtotime($_REQUEST['op_to']));
                             } ?>
                             </span></h5>
                             </div>
<!--<div class="col-md-6">-->
<!--                             <a href="opprint.php?from=<?= $op_from?>&to=<?= $op_to?>" target="_blank" class="float-right badge badge-pill badge-danger"><i class="icofont-print"></i> Print </a>-->
<!--                        </div>-->
                    </div>
                    <hr>

                   <div class="table-responsive">
                    <table id="datatable-buttons2" class="table text-wrap text-left  no-footer " role="grid" aria-describedby="datatable-buttons_info">
                                   <thead>
                                            <tr>
                                                <th>Sl NO</th>
                                                <!--<th>Id</th>-->
                                                <th>Bill No</th>
                                                 <th>Patient Reg.No</th>
                                                <th>Department</th>
                                                <th>Visit Date</th>
                                                <th>Name</th>
                                                <th>Gender</th>
                                                <th>Doctor</th>
                                                <th>Particulars</th>
                                                <th>Price</th>
                                                <th>Unit</th>
                                                <th>Total</th>
                                                <th>Discount</th>
                                                <th>Returned Amount</th>
                                                <th>Grand Total</th>
                                                <th>Paid Amount</th>
                                                <th>Balance Amount</th>
                                                
                                                
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            <?php $sn=1;
                                            $dept=$newReport->getBillListRadiology($op_from,$op_to,'56',$_REQUEST['doctor1']);
                                            $price=$unit=$gt=$returned_amount=$paid_amount=$outstand=$discount=$total1=0;
                                             while($res=mysqli_fetch_assoc($dept))
                                            { $total=0;?>
                                            <tr <?php if($res['status']=='CANCELLED') { ?> class="table-danger" <?php } ?>>
                                                <td><?= $sn?></td>
                                                
                                                <td><?= $res['bill_no']?></td>
                                                <td><?= $res['opd_no']?></td>
                                                <td><?=$res['dept_name'] ?></td>
                                                <td><?= date("d.m.Y",strtotime($res['date']))?></td>
                                                <td><?= $res['name']?></td>
                                                <td class="text-capitalize"><?= $res['gender']?></td>
                                                 <td class="text-capitalize"><?= $res['doctor']?></td>
                                                <td><?= $res['service']?></td>
                                                <td><?= $res['price']?></td>
                                                <td><?= $res['unit']?></td>
                                                <td><?= $res['gt']?></td>
                                                <td><?= $res['discount']+$res['b_discount']?></td>
                                                <td><?= $res['returned_amount']?></td>
                                               
                                                <td><?php if($res['status']=='CANCELLED') { echo "Bill Cancelled"; } else { echo  $total=$res['gt']-$res['discount']-$res['returned_amount']-$res['b_discount']; }?> </td>
                                                <td><?= $res['gt']>$res['paid_amount'] ? $res['paid_amount'] : $res['gt']; ?></td>
                                                <td><?= $res['outstand']?></td>
                                              
                                            </tr>
                                           <?php $sn++; 
                                                $price +=$res['price_tot'];
                                                $unit +=$res['unit_tot'];
                                                $gt +=$res['gt'];
                                                $returned_amount +=$res['returned_amount'];
                                                $paid_amount +=$res['gt']>$res['paid_amount'] ? $res['paid_amount'] : $res['gt'];;
                                                $outstand +=$res['outstand'];
                                                $discount +=$res['discount']+$res['b_discount'];
                                                $total1 +=$total;
                                                
                                            } 
                                           
                                          ?>
                                            </tbody>
                                            <tfoot>
                                                <th class="text-right" colspan="9">Total</th>
                                                <th><?= $price?></th>
                                                <th><?= $unit?></th>
                                                <th><?=$gt?></th>
                                                <th><?=$discount?></th>
                                                <th><?= $returned_amount?></th>
                                                <th><?= $total1?></th>
                                                <th><?=$paid_amount?></th>
                                                
                                                <th><?= $outstand?></th>
                                            </tfoot>
                                            </table>   
                   </div>
                </div>
            </div>
        </div>
    </div>


         


</div>
<div class="tab-pane fade  " id="pills-deptwise" role="tabpanel" aria-labelledby="pills-deptwise-tab"><br>
<div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
  <div class="row">
                <div class="col-md-12">
                    <h4>CT Scan Report </h4><br>

                </div>
            </div> 
<form action="#pills-deptwise-tab" method="GET">
            <div class="row">
        <div class="col-md-4">
           <div class="form-group">
                  <label for="exampleInputEmail1"> From Date </label>
                  <input type="date" class="form-control"  name="dept_from" id="dept_from" required value="<?= $dept_from?>">
                  
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                   <label for="exampleInputEmail1"> To Date  </label>
                   <input type="date" class="form-control"  name="dept_to" id="dept_to" required value="<?= $dept_to?>">
                   
             </div>
        </div>

         <div class="col-md-4">
            <div class="form-group">
                   <label for="exampleInputEmail1"> Doctor  </label>
                   <select  class="form-control select2doctor"  name="doctor2" id="doctor2" >
                       <option value="">All</option>
                       <?php $data=$newReport->doctor();
                       while($row=mysqli_fetch_assoc($data)){ ?>
                       <option value="<?= $row['name'].' '.$row['last_name']?>" <?php if($row['name'].' '.$row['last_name']==$_REQUEST['doctor2']) echo 'selected';?>><?= $row['name'].' '.$row['last_name']?></option>
                       <?php } ?>
                   </select>
                   
             </div>
        </div>

    </div>

    
    <div class="row">
            <div class="col-md-12">
            <button type="submit" class="btn btn-success  float-right btn-rounded" name="submit"><span><i class="bi bi-search"></i></span> Search</button>
            </div>    
    </div>
</form>      
    </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                    

                        <h5 class="ml-4">CT Scan Report <span class="text-danger">
                        <?php if(isset($dept_from) && isset($dept_to)) {
                            echo "Between ".  date('d.m.Y',strtotime($dept_from))." and ". date('d.m.Y',strtotime($dept_to));
                             } ?>
                        </span></h5>
                   
                </div>
                    <hr>

                    <div class="table-responsive">
                    <table id="datatable-buttons3"  class="table text-wrap text-left">
                                    <thead>
                                            <tr>
                                                <th>Sl NO</th>
                                                <!--<th>Id</th>-->
                                                <th>Bill No</th>
                                                 <th>Patient Reg.No</th>
                                                <th>Department</th>
                                                <th>Visit Date</th>
                                                <th>Name</th>
                                                <th>Gender</th>
                                                <th>Doctor</th>
                                                <th>Particulars</th>
                                                <th>Price</th>
                                                <th>Unit</th>
                                                <th>Total</th>
                                                <th>Discount</th>
                                                <th>Returned Amount</th>
                                                <th>Grand Total</th>
                                                <th>Paid Amount</th>
                                                <th>Balance Amount</th>
                                                
                                                
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            <?php $sn=1;
                                            $dept=$newReport->getBillListRadiology($dept_from,$dept_to,'8',$_REQUEST['doctor2']);
                                               $price=$unit=$gt=$returned_amount=$paid_amount=$outstand=$discount=$total1=0;
                                             while($res=mysqli_fetch_assoc($dept))
                                            { $total=0;?>
                                            <tr <?php if($res['status']=='CANCELLED') { ?> class="table-danger" <?php } ?>>
                                                <td><?= $sn?></td>
                                                
                                                <td><?= $res['bill_no']?></td>
                                                <td><?= $res['opd_no']?></td>
                                                <td><?=$res['dept_name'] ?></td>
                                                <td><?= date("d.m.Y",strtotime($res['date']))?></td>
                                                <td><?= $res['name']?></td>
                                                <td class="text-capitalize"><?= $res['gender']?></td>
                                                <td class="text-capitalize"><?= $res['doctor']?></td>
                                                <td><?= $res['service']?></td>
                                                <td><?= $res['price']?></td>
                                                <td><?= $res['unit']?></td>
                                                <td><?= $res['gt']?></td>
                                                <td><?= $res['discount']+$res['b_discount']?></td>
                                                <td><?= $res['returned_amount']?></td>
                                               
                                                <td><?php if($res['status']=='CANCELLED') { echo "Bill Cancelled"; } else { echo  $total=$res['gt']-$res['discount']-$res['returned_amount']-$res['b_discount']; }?> </td>
                                                <td><?= $res['gt']>$res['paid_amount'] ? $res['paid_amount'] : $res['gt'];?></td>
                                                <td><?= $res['outstand']?></td>
                                              
                                            </tr>
                                           <?php $sn++; 
                                                $price +=$res['price_tot'];
                                                $unit +=$res['unit_tot'];
                                                $gt +=$res['gt'];
                                                $returned_amount +=$res['returned_amount'];
                                                $paid_amount +=$res['gt']>$res['paid_amount'] ? $res['paid_amount'] : $res['gt'];;
                                                $outstand +=$res['outstand'];
                                                $discount +=$res['discount']+$res['b_discount'];
                                                $total1 +=$total;
                                                
                                            } 
                                           
                                          ?>
                                            </tbody>
                                            <tfoot>
                                                <th class="text-right" colspan="9">Total</th>
                                                <th><?= $price?></th>
                                                <th><?= $unit?></th>
                                                <th><?=$gt?></th>
                                                <th><?=$discount?></th>
                                                <th><?= $returned_amount?></th>
                                                <th><?= $total1?></th>
                                                <th><?=$paid_amount?></th>
                                                
                                                <th><?= $outstand?></th>
                                            </tfoot>
                                            </tfoot>
                                            </table>   
                    </div>
                </div>
            </div>
        </div>
    </div>

  
</div>
<div class="tab-pane fade  " id="pills-combinedop" role="tabpanel" aria-labelledby="pills-combinedop-tab"><br>
<div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
  <div class="row">
                <div class="col-md-12">
                    <h4>USG Report </h4><br>
                  
                </div>
            </div> 
<form action="#pills-combinedop-tab" method="GET">

            <div class="row">
        <div class="col-md-4">
           <div class="form-group">
                  <label for="exampleInputEmail1"> From Date </label>
                  <input type="date" class="form-control"  name="cent_from" id="cent_from" value='<?= $cent_from?>'required>
                  
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                   <label for="exampleInputEmail1"> To Date  </label>
                   <input type="date" class="form-control"  name="cent_to" id="cent_to" value='<?= $cent_to?>'required>
                   
             </div>
        </div>

         <div class="col-md-4">
            <div class="form-group">
                   <label for="exampleInputEmail1"> Doctor  </label>
                   <select  class="form-control select3doctor"  name="doctor3" id="doctor3" >
                       <option value="">All</option>
                       <?php $data=$newReport->doctor();
                       while($row=mysqli_fetch_assoc($data)){ ?>
                       <option value="<?= $row['name'].' '.$row['last_name']?>" <?php if($row['name'].' '.$row['last_name']==$_REQUEST['doctor3']) echo 'selected';?>><?= $row['name'].' '.$row['last_name']?></option>
                       <?php } ?>
                   </select>
                   
             </div>
        </div>

    </div>

    
    <div class="row">
            <div class="col-md-12">
            <button type="submit" class="btn btn-success  float-right btn-rounded" name="submit"><span><i class="bi bi-search"></i></span> Search</button>
            </div>    
    </div>
</form>      
    </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                    <div class="col-md-6">

                        <h5 class="ml-4">USG Report <span class="text-danger">
                        <?php if(isset($cent_from) && isset($cent_to)) {
                            echo "Between ".  date('d.m.Y',strtotime($cent_from))." and ". date('d.m.Y',strtotime($cent_to));
                             } ?>
                        </span></h5>
                    </div>
                    <!--<div class="col-md-6">-->
                    <!--         <a href="Combineprint.php?from=<?= $cent_from?>&to=<?= $cent_to?>" target="_blank" class="float-right badge badge-pill badge-light"><i class="icofont-print"></i> Print </a>-->
                    <!--    </div>-->
                        </div>
                    <hr>

                <div class="table-responsive">
                    <table id="datatable-buttons4" class="table text-wrap  text-left">
                                    <thead>
                                            <tr>
                                                <th>Sl NO</th>
                                                <!--<th>Id</th>-->
                                                <th>Bill No</th>
                                                 <th>Patient Reg.No</th>
                                                <th>Department</th>
                                                <th>Visit Date</th>
                                                <th>Name</th>
                                                <th>Gender</th>
                                                 <th>Doctor</th>
                                                <th>Particulars</th>
                                                <th>Price</th>
                                                <th>Unit</th>
                                                <th>Total</th>
                                                <th>Discount</th>
                                                <th>Returned Amount</th>
                                                <th>Grand Total</th>
                                                <th>Paid Amount</th>
                                                <th>Balance Amount</th>
                                                
                                                
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            <?php $sn=1;
                                            $dept=$newReport->getBillListRadiology($cent_from,$cent_to,'53',$_REQUEST['doctor3']);
                                               $price=$unit=$gt=$returned_amount=$paid_amount=$outstand=$discount=$total1=0;
                                             while($res=mysqli_fetch_assoc($dept))
                                            { $total=0;?>
                                            <tr <?php if($res['status']=='CANCELLED') { ?> class="table-danger" <?php } ?>>
                                                <td><?= $sn?></td>
                                                
                                                <td><?= $res['bill_no']?></td>
                                                <td><?= $res['opd_no']?></td>
                                                <td><?=$res['dept_name'] ?></td>
                                                <td><?= date("d.m.Y",strtotime($res['date']))?></td>
                                                <td><?= $res['name']?></td>
                                                <td class="text-capitalize"><?= $res['gender']?></td>
                                                <td class="text-capitalize"><?= $res['doctor']?></td>
                                                <td><?= $res['service']?></td>
                                                <td><?= $res['price']?></td>
                                                <td><?= $res['unit']?></td>
                                                <td><?= $res['gt']?></td>
                                                <td><?= $res['discount']+$res['b_discount']?></td>
                                                <td><?= $res['returned_amount']?></td>
                                               
                                                <td><?php if($res['status']=='CANCELLED') { echo "Bill Cancelled"; } else { echo  $total=$res['gt']-$res['discount']-$res['returned_amount']-$res['b_discount']; }?> </td>
                                                <td><?= $res['gt']>$res['paid_amount'] ? $res['paid_amount'] : $res['gt'];?></td>
                                                <td><?= $res['outstand']?></td>
                                              
                                            </tr>
                                           <?php $sn++; 
                                                $price +=$res['price_tot'];
                                                $unit +=$res['unit_tot'];
                                                $gt +=$res['gt'];
                                                $returned_amount +=$res['returned_amount'];
                                                $paid_amount +=$res['gt']>$res['paid_amount'] ? $res['paid_amount'] : $res['gt'];;
                                                $outstand +=$res['outstand'];
                                                $discount +=$res['discount']+$res['b_discount'];
                                                $total1 +=$total;
                                                
                                            } 
                                           
                                          ?>
                                            </tbody>
                                            <tfoot>
                                                <th class="text-right" colspan="9">Total</th>
                                                <th><?= $price?></th>
                                                <th><?= $unit?></th>
                                                <th><?=$gt?></th>
                                                <th><?=$discount?></th>
                                                <th><?= $returned_amount?></th>
                                                <th><?= $total1?></th>
                                                <th><?=$paid_amount?></th>
                                                
                                                <th><?= $outstand?></th>
                                            </tfoot>
                                            </tfoot>
                                            </table>   
                    </div>
                </div>
            </div>
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

 <script src="./vendor/select2/js/select2.full.min.js"></script>
    <script src="./js/plugins-init/select2-init.js"></script>
   
       
 <script>
   $('.select1doctor').select2();
    $('.select2doctor').select2();
     $('.select3doctor').select2();
    $(document).ready(function() {
            $('#datatable-buttons2').DataTable({

                dom: 'Bfrtip',
                responsive: true,
                pageLength: 25,
                // lengthMenu: [0, 5, 10, 20, 50, 100, 200, 500],
                
                buttons: [
            
            {
                extend: 'pdfHtml5',
                title: 'Radiology Report',
               
                orientation: 'landscape',
                pageSize: 'LEGAL',
                footer : true
            },
            {
                extend: 'excelHtml5',
                title: 'Radiology Report',
                footer : true
            }
        ]

            });
        });
</script>
 <script>
    $(document).ready(function() {
            $('#datatable-buttons3').DataTable({

                dom: 'Bfrtip',
                responsive: true,
                pageLength: 25,
                // lengthMenu: [0, 5, 10, 20, 50, 100, 200, 500],
                
                buttons: [
            
            {
                extend: 'pdfHtml5',
                title: 'Department wise Radiology Report from <?= date("d.m.Y",strtotime($dept_from))?> to <?= date("d.m.Y",strtotime($dept_to))?>',
                 orientation: 'landscape',
                pageSize: 'LEGAL',
                footer : true
            }
        ]

            });
        });
</script>
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
                title: 'Radiology',
                 orientation: 'landscape',
                pageSize: 'LEGAL',
                footer : true
            }
        ]

            });
        });
</script>
   
<script>
    if(window.location.href.split("#")[1]!=undefined)
        {
            var collection=document.getElementsByClassName(' show active');
       for (let i = 0; i < collection.length; i++) {
        // collection[i].classList.remove("show"); 
        collection[i].classList.remove("active");
            }
       var collection1=document.getElementsByClassName(' active');
       for (let i = 0; i < collection1.length; i++) {
  collection1[i].classList.remove("active");
            }
           var id= window.location.href.split("#")[1];
       document.getElementById(id).className += " active";
    //    alert(.length);
    var int=id.split("-");
    var flag=0;
    var element='';
    for (let index = 0; index < int.length - 1; index++) {
        if(flag==1)
        {
            element +='-';
        }
         element += int[index];
         flag=1;
        
    }
    // alert()
       document.getElementById(element).className += " show active";
        }
</script>
  

</body>

</html>