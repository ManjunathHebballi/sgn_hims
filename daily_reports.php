<?php
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  

include('model/reportClass.php');
$newReport = new report;

if(isset($_REQUEST['am_from']))
{
    $am_from=$_REQUEST['am_from'];
}
else{
     $am_from=date('Y-m-d');
}
if(isset($_REQUEST['am_to']))
{
    $am_to=$_REQUEST['am_to'];
}
else{
     $am_to=date('Y-m-d');
}

if(isset($_REQUEST['sr_from']))
{
    $sr_from=$_REQUEST['sr_from'];
}
else{
     $sr_from=date('Y-m-d');
}
if(isset($_REQUEST['sr_to']))
{
    $sr_to=$_REQUEST['sr_to'];
}
else{
     $sr_to=date('Y-m-d');
}
if(isset($_REQUEST['pt_from']))
{
    $pt_from=$_REQUEST['pt_from'];
}
else{
     $pt_from=date('Y-m-d');
}
if(isset($_REQUEST['pt_to']))
{
    $pt_to=$_REQUEST['pt_to'];
}
else{
     $pt_to=date('Y-m-d');
}

if(isset($_REQUEST['rd_from']))
{
    $rd_from=$_REQUEST['rd_from'];
}
else{
     $rd_from=date('Y-m-d');
}
if(isset($_REQUEST['rd_to']))
{
    $rd_to=$_REQUEST['rd_to'];
}
else{
     $rd_to=date('Y-m-d');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Daily Reports</title>
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

  <!-- View -->


        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <!-- row -->
        <div class="container-fluid">

        <div class="row mb-4 ml-3">
          <h4 class="text-uppercase">Daily Reports</h4>
        </div>


        <ul class="nav nav-pills mb-3 justify-content-around" id="pills-tab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="pills-minor-tab" data-toggle="pill" href="#pills-minor" role="tab" aria-controls="pills-minor" aria-selected="true"> Adult-Minor Report</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="pills-summary-tab" data-toggle="pill" href="#pills-summary" role="tab" aria-controls="pills-summary" aria-selected="false"> Summary Report </a>
  </li>
  <!-- <li class="nav-item">-->
  <!--  <a class="nav-link" id="pills-dept-tab" data-toggle="pill" href="#pills-dept" role="tab" aria-controls="pills-dept" aria-selected="false"> Details  Report </a>-->
  <!--</li>-->
  <!--<li class="nav-item">-->
  <!--  <a class="nav-link" id="pills-deptwise-tab" data-toggle="pill" href="#pills-deptwise" role="tab" aria-controls="pills-deptwise" aria-selected="false"> Department wise Report </a>-->
  <!--</li>-->
   <li class="nav-item">
    <a class="nav-link" id="pills-patho-tab" data-toggle="pill" href="#pills-patho" role="tab" aria-controls="pills-patho" aria-selected="false"> Lab  Report </a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="pills-radio-tab" data-toggle="pill" href="#pills-radio" role="tab" aria-controls="pills-radio" aria-selected="false"> Radiology Report </a>
  </li>
</ul>


    

       

<div class="tab-content" id="pills-tabContent">
 
 <div class="tab-pane fade show active  " id="pills-minor" role="tabpanel" aria-labelledby="pills-minor-tab"><br>
 
<div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
  <div class="row">
                <div class="col-md-12">
                    <h4>Select Criteria</h4><br>
                 
                </div>
            </div> 
<form action="#pills-minor-tab"  method="POST">
    <div class="row">
        <div class="col-md-4">
           <div class="form-group">
                  <label for="exampleInputEmail1"> From Date </label>
                  <input type="date" class="form-control"  name="am_from" id="am_from" required value="<?= $am_from?>">
                  
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                   <label for="exampleInputEmail1"> To Date  </label>
                   <input type="date" class="form-control"  name="am_to" id="am_to" required value="<?= $am_to?>">
                   
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
                        <h5 class="ml-4">Daily Adult Minor Report <span class="text-danger">
                            <?php 
                            echo "Between ".  date('d.m.Y',strtotime($am_from))." and ". date('d.m.Y',strtotime($am_to));
                              ?>
                             </span></h5>
                             </div>
<div class="col-md-6">
                             <!--<a href="print_daily_adult_report.php" target="_blank" class="float-right badge badge-pill badge-danger" data-toggle="tooltip" data-placement="top" title="Print Lab Report"><i class="icofont-print"></i> Print </a>-->
                        </div>
                    </div>
                    <hr>

                    <div class="table-responsive">
                    <table id="datatable-buttons2" class="table table-hoverde table-bordered display text-center dataTable no-footer" role="grid" aria-describedby="datatable-buttons_info">
                                    <thead class="thead-nex">
                                    <tr >
                                               <th>Sl. No.</th>
                                                <th>Adult/Minor/Senior</th>
                                                <th>Gender</th>
                                                <th>New/Revisit</th>
                                                <th>Total Patient </th>
                                                
                                                
                                                
                                            </tr>
                                        </thead>
                                       <tbody>
                                       <?php $am=$newReport->getam($am_from,$am_to,'adult');
                                        $sn=1;
                while($row=mysqli_fetch_assoc($am)){
                                       
                                       ?>
                                       <tr>
                                           <td><?= $sn?></td>
                                           <td>Adult</td>
                                           <td><?= $row['gender']?></td>
                                           <td><?= $row['type']?></td>
                                           <td><?= $row['total']?></td>
                                       </tr>
                                       <?php $sn++;} 
                                       $am=$newReport->getam($am_from,$am_to,'minor');
                                       
                while($row=mysqli_fetch_assoc($am)){?>
                <tr>
                                           <td><?= $sn?></td>
                                           <td>Minor</td>
                                           <td><?= $row['gender']?></td>
                                           <td><?= $row['type']?></td>
                                           <td><?= $row['total']?></td>
                                       </tr>
                <?php $sn++;} 
                
                                       $am=$newReport->getam($am_from,$am_to,'senior');
                                       
                while($row=mysqli_fetch_assoc($am)){?>
                <tr>
                                           <td><?= $sn?></td>
                                           <td>Senior</td>
                                           <td><?= $row['gender']?></td>
                                           <td><?= $row['type']?></td>
                                           <td><?= $row['total']?></td>
                                       </tr>
                                       <?php $sn++;} ?>
                                       </tbody>
                                            </table>   
                    </div>
                </div>
            </div>
        </div>
    </div>


         


</div>
    <!--end of Adult Minor report tab-->
<div class="tab-pane fade   " id="pills-summary" role="tabpanel" aria-labelledby="pills-summary-tab"><br>
<div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
  <div class="row">
                <div class="col-md-12">
                    <h4>Select Criteria</h4><br>
                 
                </div>
            </div> 
<form action="#pills-summary-tab" method="POST">
    <div class="row">
        <div class="col-md-4">
           <div class="form-group">
                  <label for="exampleInputEmail1"> From Date </label>
                  <input type="date" class="form-control"  name="sr_from" id="sr_from" required value="<?= $sr_from?>">
                  
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                   <label for="exampleInputEmail1"> To Date  </label>
                   <input type="date" class="form-control"  name="sr_to" id="sr_to" required value="<?= $sr_to?>">
                   
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
                        <h5 class="ml-4">Daily Summary Report <span class="text-danger">
                            <?php 
                            echo "Between ".  date('d.m.Y',strtotime($sr_from))." and ". date('d.m.Y',strtotime($sr_to));
                              ?>
                             </span></h5>
                             </div>
<div class="col-md-6">
                             <!--<a href="print_daily_summary_report.php" target="_blank" class="float-right badge badge-pill badge-danger" data-toggle="tooltip" data-placement="top" title="Print Lab Report"><i class="icofont-print"></i> Print </a>-->
                        </div>
                    </div>
                    <hr>

                    <div class="table-responsive">
                    <table id="datatable-buttons3" class="table table-hoverde table-bordered display text-center dataTable no-footer" role="grid" aria-describedby="datatable-buttons_info">
                                    <thead class="thead-nex">
                                    <tr >
                                               <th>Sl. No.</th>
                                                <th>Deptartment</th>
                                                <th>Unit</th>
                                                <th>New Patient</th>
                                                <th>Revisit Patient</th>
                                                <th>Total  </th>
                                                
                                                
                                                
                                            </tr>
                                        </thead>
                                    
                                          
                                       <?php $sr=$newReport->getsr($sr_from,$sr_to);
                                        $sn=1;
                while($row=mysqli_fetch_assoc($sr)){
                                       
                                       ?>
                                           <tbody>
                                       <tr>
                                           <td><?= $sn?></td>
                                           <td><?= $row['dept_name']?></td>
                                           <td><?= $row['unit']?></td>
                                           <td><?= $row['new']?></td>
                                           <td><?= $row['old']?></td>
                                           <td><?= $row['total']?></td>
                                       </tr>
                                       <?php $sn++;}
                                       ?>
                                        </tbody>
                                       
                                            </table>   
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
    <!--end of Summary report tab-->
    
   <div class="tab-pane fade   " id="pills-dept" role="tabpanel" aria-labelledby="pills-dept-tab"><br>
<div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
  <div class="row">
                <div class="col-md-12">
                    <h4>Select Criteria</h4><br>
                 
                </div>
            </div> 
<form action="#" href="#pills-home" method="POST">
    <div class="row">
        <div class="col-md-4">
           <div class="form-group">
                  <label for="exampleInputEmail1"> From Date </label>
                  <input type="date" class="form-control"  name="op_from" id="op_from" required value="<?= $_REQUEST['op_from']?>">
                  
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                   <label for="exampleInputEmail1"> To Date  </label>
                   <input type="date" class="form-control"  name="op_to" id="op_to" required value="<?= $_REQUEST['op_to']?>">
                   
             </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="">Order By</label>
                <select name="order_by" id="" class="form-control">
                    <option value="Department">Order by Department</option>
                    <option value="Registration_no">Order by Registration No</option>

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
                        <h5 class="ml-4">Daily Detail Report  <span class="text-danger">
                            <?php if(isset($_REQUEST['op_from']) && isset($_REQUEST['op_to'])) {
                            echo "Between ".  date('d.m.Y',strtotime($_REQUEST['op_from']))." and ". date('d.m.Y',strtotime($_REQUEST['op_to']));
                             } ?>
                             </span></h5>
                             </div>
<div class="col-md-6">
                             <a href="print_daily_details_report.php" target="_blank" class="float-right badge badge-pill badge-danger" data-toggle="tooltip" data-placement="top" title="Print Lab Report"><i class="icofont-print"></i> Print </a>
                        </div>
                    </div>
                    <hr>

                    <div class="table-responsive">
                    <table id="datatable-buttons" class="table table-hoverde table-bordered display text-center dataTable no-footer" role="grid" aria-describedby="datatable-buttons_info">
                                    <thead class="thead-dark">
                                    <tr >
                                    <th>Sl. No.</th>
                                                <th>UHID</th>
                                                <th>NAME</th>
                                                <th>DEPARTMENT No.</th>
                                                <th>AGE  </th>
                                             <th>SEX</th>
                                             <th>F/S/W/D/H OF</th>
                                             <th>PAID
UNPAID</th>
                                     
<th>BILLING TYPE</th>
                                                
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>

                                            </tr>
                                        </tbody>
                                       
                                            </table>   
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
    <!--end of Summary report tab--> 
    
     <div class="tab-pane fade   " id="pills-deptwise" role="tabpanel" aria-labelledby="pills-deptwise-tab"><br>
<div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
  <div class="row">
                <div class="col-md-12">
                    <h4>Select Criteria</h4><br>
                 
                </div>
            </div> 
<form action="#"  method="POST">
    <div class="row">
    <div class="col-md-3">
            <div class="form-group">
                <label for="">Department</label>
                <select name="dept" id="" class="form-control">
                    <option value="all">All</option>
                    

                </select>
            </div>
        </div>
        <div class="col-md-3">
           <div class="form-group">
                  <label for="exampleInputEmail1">  Date </label>
                  <input type="date" class="form-control"  name="op_from" id="op_from" required value="<?= $_REQUEST['op_from']?>">
                  
            </div>
        </div>

    
        <div class="col-md-3">
            <div class="form-group">
                <label for="">Report Type</label>
                <select name="type" id="" class="form-control">
                    <option value="all">All</option>
                    

                </select>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="">Report for</label>
                <select name="for" id="" class="form-control">
                    <option value="all">All</option>
                    

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
                        <h5 class="ml-4">Department wise Daily  Report   <span class="text-danger">
                            <?php if(isset($_REQUEST['op_from']) && isset($_REQUEST['op_to'])) {
                            echo "Between ".  date('d.m.Y',strtotime($_REQUEST['op_from']))." and ". date('d.m.Y',strtotime($_REQUEST['op_to']));
                             } ?>
                             </span></h5>
                             </div>
<div class="col-md-6">
                             <a href="print_deptwise_daily_report.php" target="_blank" class="float-right badge badge-pill badge-danger" data-toggle="tooltip" data-placement="top" title="Print Lab Report"><i class="icofont-print"></i> Print </a>
                        </div>
                    </div>
                    <hr>

                    <div class="table-responsive">
                    <table id="datatable-buttons" class="table table-hoverde table-bordered display text-center r" role="grid" aria-describedby="datatable-buttons_info">
                                    <thead class="thead-nex">
                                    <tr >
                                               <th>Sl. No.</th>
                                                <th>UHID</th>
                                                <th>NAME</th>
                                                <th>DEPARTMENT No.</th>
                                                <th>AGE  </th>
                                             <th>SEX</th>
                                             <th>F/S/W/D/H OF</th>
                                             <th>PAID
UNPAID</th>
                                     
<th>BILLING TYPE</th>
                                                
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>

                                            </tr>
                                        </tbody>
                                       
                                            </table>   
                    </div>
                </div>
            </div>
        </div>
    </div>

       


</div>
    <!--end of Summary report tab--> 
   <div class="tab-pane fade" id="pills-patho" role="tabpanel" aria-labelledby="pills-patho-tab"><br>
 
<div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
  <div class="row">
                <div class="col-md-12">
                    <h4>Select Criteria</h4><br>
                 
                </div>
            </div> 
<form action="#pills-patho-tab"  method="POST">
    <div class="row">
        <div class="col-md-4">
           <div class="form-group">
                  <label for="exampleInputEmail1"> From Date </label>
                  <input type="date" class="form-control"  name="pt_from" id="pt_from" required value="<?= $pt_from?>">
                  
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                   <label for="exampleInputEmail1"> To Date  </label>
                   <input type="date" class="form-control"  name="pt_to" id="pt_to" required value="<?= $pt_to?>">
                   
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
                        <h5 class="ml-4">Lab Report <span class="text-danger">
                            <?php 
                            echo "Between ".  date('d.m.Y',strtotime($pt_from))." and ". date('d.m.Y',strtotime($pt_to));
                              ?>
                             </span></h5>
                             </div>
<div class="col-md-6">
                             <!--<a href="print_daily_adult_report.php" target="_blank" class="float-right badge badge-pill badge-danger" data-toggle="tooltip" data-placement="top" title="Print Lab Report"><i class="icofont-print"></i> Print </a>-->
                        </div>
                    </div>
                    <hr>

                    <div class="table-responsive">
                      <table id="datatable-buttons4" class="table table-hoverde table-bordered text-wrap text-center " >
                                    <thead class="thead-dark">
                                    <tr >
                                               
                                                <th>Test Name</th>
                                                <th>IP</th>
                                                <th>OP</th>
                                                
                                                
                                                
                                            </tr>
                                        </thead>
                                        <tbody class="text-center text-black">
                                            <?php 
                                           
                                            $data=$newReport->labReport($pt_from,$pt_to);
                                        
                                            while($row=mysqli_fetch_assoc($data)) {
                                            ?>
                                            <tr>
                                                <td>TOTAL NO OF <?= $row['testname_header']?></td>
                                        
                                                <td> <a href="iplabprint.php?from=<?= $pt_from?>&to=<?= $pt_to?>&testname_header=<?= $row['testname_header']?>" target="_blank" class="text-primary"><?= $row['IP']?></a></td>
                                                <td><a href="oplabprint.php?from=<?= $pt_from?>&to=<?= $pt_to?>&testname_header=<?= $row['testname_header']?>" target="_blank" class="text-primary"><?= $row['OP']?></a></td>
                                               
                                            </tr>
                                            <?php } ?>
                                            </tbody>
                                            </table>   
                    </div>
                </div>
            </div>
        </div>
    </div>


         


</div> 
<div class="tab-pane fade" id="pills-radio" role="tabpanel" aria-labelledby="pills-radio-tab"><br>
 
<div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
  <div class="row">
                <div class="col-md-12">
                    <h4>Select Criteria</h4><br>
                 
                </div>
            </div> 
<form action="#pills-radio-tab"  method="POST">
    <div class="row">
        <div class="col-md-4">
           <div class="form-group">
                  <label for="exampleInputEmail1"> From Date </label>
                  <input type="date" class="form-control"  name="rd_from" id="rd_from" required value="<?= $rd_from?>">
                  
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                   <label for="exampleInputEmail1"> To Date  </label>
                   <input type="date" class="form-control"  name="rd_to" id="rd_to" required value="<?= $rd_to?>">
                   
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
                        <h5 class="ml-4">Radiology Report <span class="text-danger">
                            <?php 
                            echo "Between ".  date('d.m.Y',strtotime($rd_from))." and ". date('d.m.Y',strtotime($rd_to));
                              ?>
                             </span></h5>
                             </div>
<div class="col-md-6">
                             <!--<a href="print_daily_adult_report.php" target="_blank" class="float-right badge badge-pill badge-danger" data-toggle="tooltip" data-placement="top" title="Print Lab Report"><i class="icofont-print"></i> Print </a>-->
                        </div>
                    </div>
                    <hr>

                    <div class="table-responsive">
                      <table id="datatable-buttons4" class="table table-hoverde table-bordered text-wrap text-center " >
                                    <thead class="thead-dark">
                                    <tr >
                                               
                                                <th>Test Name</th>
                                                <th>IP</th>
                                                <th>OP</th>
                                                
                                                
                                                
                                            </tr>
                                        </thead>
                                        <tbody class="text-center text-black">
                                            <?php 
                                           
                                            $data=$newReport->radioReport($rd_from,$rd_to);
                                        
                                            while($row=mysqli_fetch_assoc($data)) {
                                            ?>
                                            <tr>
                                                <td>TOTAL NO OF <?= $row['testname']?></td>
                                        
                                                <td> <a href="iplabprint.php?from=<?= $rd_from?>&to=<?= $rd_to?>&id=<?= $row['testid']?>" target="_blank" class="text-primary"><?= $row['IP']?></a></td>
                                                <td><a href="oplabprint.php?from=<?= $rd_from?>&to=<?= $rd_to?>&id=<?= $row['testid']?>" target="_blank" class="text-primary"><?= $row['OP']?></a></td>
                                               
                                            </tr>
                                            <?php } ?>
                                            </tbody>
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


       
 <script>
    $(document).ready(function() {
            $('#datatable-buttons2').DataTable({

                dom: 'Bfrtip',
                responsive: true,
                pageLength: 25,
                // lengthMenu: [0, 5, 10, 20, 50, 100, 200, 500],
                
                buttons: [
            
            {
                extend: 'pdfHtml5',
                title: 'Daily Adult Minor Report  <?php 
                            echo "Between ".  date('d.m.Y',strtotime($am_from))." and ". date('d.m.Y',strtotime($am_to));
                              ?>',
               
              
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
                title: '<?= $_SESSION['college_name'].','.$_SESSION['college_location']?>\nDaily Summary Report <?php if(isset($_REQUEST['op_from']) && isset($_REQUEST['op_to'])) {
                            echo "Between ".  date('d.m.Y',strtotime($_REQUEST['op_from']))." and ". date('d.m.Y',strtotime($_REQUEST['op_to']));
                             } ?> ',
               
               
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