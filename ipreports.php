<?php 
require_once(__DIR__ . '/model/session_config.php');

date_default_timezone_set("Asia/Kolkata"); 

include('model/reportClass.php');
$newReport = new report;

if(isset($_REQUEST['ip_from']))
{
    $ip_from=$_REQUEST['ip_from'];
}
else{
        $ip_from=date('Y-m-d');
}
if(isset($_REQUEST['ip_to']))
{
    $ip_to=$_REQUEST['ip_to'];
}
else{
        $ip_to=date('Y-m-d');
}


if(isset($_REQUEST['dept_from']))
{
    $dept_from=$_REQUEST['dept_from'];
}
else{
        $dept_from=date('Y-m-d');
}
if(isset($_REQUEST['dept_to']))
{
    $dept_to=$_REQUEST['dept_to'];
}
else{
        $dept_to=date('Y-m-d');
}



if(isset($_REQUEST['beddept_from']))
{
    $beddept_from=$_REQUEST['beddept_from'];
}
else{
        $beddept_from=date('Y-m-d');
}
if(isset($_REQUEST['beddept_to']))
{
    $beddept_to=$_REQUEST['beddept_to'];
}
else{
        $beddept_to=date('Y-m-d');
}
if(isset($_REQUEST['bed_date']))
{
    $bed_date=$_REQUEST['bed_date'];
}
else{
        $bed_date=date("Y-m-d");
}
if(isset($_REQUEST['bedicu_from']))
{
    $bedicu_from=$_REQUEST['bedicu_from'];
}
else{
        $bedicu_from=date('Y-m-d');
}
if(isset($_REQUEST['bedicu_to']))
{
    $bedicu_to=$_REQUEST['bedicu_to'];
}
else{
        $bedicu_to=date('Y-m-d');
}
if(isset($_REQUEST['dis_from']))
{
    $dis_from=$_REQUEST['dis_from'];
}
else{
        $dis_from=date('Y-m-d');
}

if(isset($_REQUEST['dis_to']))
{
    $dis_to=$_REQUEST['dis_to'];
}
else{
        $dis_to=date('Y-m-d');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>IP Report</title>
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
          <h4 class="text-uppercase">IP Report</h4>
        </div>


        <ul class="nav nav-pills mb-3 justify-content-around" id="pills-tab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home-tab" aria-selected="true">  IP Report</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="pills-deptwise-tab" data-toggle="pill" href="#pills-deptwise" role="tab" aria-controls="pills-deptwise" aria-selected="false"> Department wise IP Report</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="pills-combinedop-tab" data-toggle="pill" href="#pills-combinedop" role="tab" aria-controls="pills-combinedop" aria-selected="false">Bed Occupancy</a>
  </li>

  <li class="nav-item">
    <a class="nav-link" id="pills-diet-tab" data-toggle="pill" href="#pills-diet" role="tab" aria-controls="pills-diet" aria-selected="false">Department Bed Occupancy</a>
  </li>

   <li class="nav-item">
    <a class="nav-link" id="pills-dis-tab" data-toggle="pill" href="#pills-dis" role="tab" aria-controls="pills-dis" aria-selected="false">Discharge Report</a>
  </li>
</ul>


    

       

<div class="tab-content" id="pills-tabContent">
  <div class="tab-pane fade show active  " id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab"><br>
  <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
  <div class="row">
                <div class="col-md-12">
                    <h4>IP Report </h4><br>
                   
                </div>
            </div> 
 <form action="#pills-home-tab" method="POST">
            <div class="row">
        <div class="col-md-4">
           <div class="form-group">
                  <label for="exampleInputEmail1"> From Date </label>
                  <input type="date" class="form-control"  name="ip_from" id="ip_from" value="<?= $ip_from?>" required>
                  
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                   <label for="exampleInputEmail1"> To Date  </label>
                   <input type="date" class="form-control"  name="ip_to" id="ip_to" value="<?= $ip_to?>" required>
                   
             </div>
        </div>

         

    </div>

    
    <div class="row">
            <div class="col-md-12">
            <button type="submit" class="btn btn-success  float-right btn-rounded"  name="submit"><span><i class="bi bi-search"></i></span> Search</button>
            </div>    
    </div>
        
    </div>
    </form>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="ml-4">IP Report <span class="text-danger"><?php if(isset($ip_from) && isset($ip_to)) {
                            echo "Between ".  date('d.m.Y',strtotime($ip_from))." and ". date('d.m.Y',strtotime($ip_to));
                             } ?></span></h5>
                        </div>
                             
                             <div class="col-md-6">
                             <a href="ipreportprint.php?from=<?= $ip_from?>&to=<?= $ip_to?>" data-toggle="tooltip" title="Print IP Report" target="_blank" class="float-right badge badge-pill badge-danger"><i class="icofont-print"></i> Print </a>
                        </div>
                    </div>
                    
                    
                    <hr>

                    <div class="table-responsive">
                        <table id="datatable-buttons2" class="table table-hoverde text-wrap text-center">
                                        <thead class="thead-dark">
                                        <tr >
                                                            <th>Sl NO</th>
                                                    <th>Date</th>
                                                    <th>OP Reg No</th>
                                                    <th>IP Reg No</th>
                                                    <th>Name</th>
                                                    <th>Gender</th>
                                                    <th>Age</th>
                                                    <th>Address</th>
                                                    <th>Diagnosis</th>
                                                    <th>Department</th>
                                                    <!--<th>Bed No.</th>-->
                                                    <th>Date of Discharge</th>
                                                    <th>DIH</th>
                                                    <th>View Report</th>
                                                    
                                                    
                                                </tr>
                                            </thead>
                                            <tbody class="text-center text-black">
                                                <?php $ot=$newReport->getIPDetails($ip_from,$ip_to);
                    $sn=1;
                    while($row=mysqli_fetch_assoc($ot)){
                    ?>
                                                <tr>
                                                    <td><?= $sn?></td>
                                                      <td><?= date("d.m.Y",strtotime($row['admission_date']))?></td>
                                                    <td><?= $row['opd_no']?></td>
                                                    <td> <?= $row['ipd_no']?></td>
                                                    <td><?= $row['name']?></td>
                                                    <td ><?= $row['gender']?></td>
                                                    <td ><?= $row['age']?></td>
                                                    <td ><?= $row['address']?></td>
                                                    <td><?= $row['diagnosis']?></td>
                                                    <td ><?= $row['dept_name']?></td>
                                                    <!--<td ><?= $row['bed_number']?></td>-->
                                                    <td ><?php if($row['discharge']==1) echo date("d.m.Y",strtotime($row['discharge_date'])); else echo '00.00.0000'?></td>
                                                    <td ><?php $date1=date_create($row['admission_date']);
    $date2=date_create($row['discharge_date']);
    $diff=date_diff($date1,$date2);
    if($row['discharge_date']!='0000-00-00 00:00:00')echo $diff->format("%a day");?></td>
                                                    <td ><?php if(strlen($row['discharge_file'])>0) { ?><a href="<?= $row['discharge_file']?>" target="_blank"><span class="badge badge-pill badge-primary"><i class="bi bi-eye-square"></i> View </span> </a><?php } ?></td>
                                                    </tr>
                                                <?php $sn++; } ?>
                                                
                                                </tbody>
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
                    <h4>Department wise IP Report </h4><br>
                    
                </div>
            </div> 
<form action="#pills-deptwise-tab" method="POST">
            <div class="row">
        <div class="col-md-4">
           <div class="form-group">
                  <label for="exampleInputEmail1"> From Date </label>
                  <input type="date" class="form-control"  name="dept_from" id="dept_from" value="<?= $dept_from?>">
                  
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                   <label for="exampleInputEmail1"> To Date  </label>
                   <input type="date" class="form-control"  name="dept_to" id="dept_to" value="<?= $dept_to?>">
                   
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
                        <h5 class="ml-4">Department Wise IP Report <span class="text-danger">
                            <?php if(isset($dept_from)&&isset($dept_to)){ ?>
                            Between <?= $dept_from?> and <?= $dept_to?>
                            <?php } ?>
                            </span></h5>
                            </div>
                             <div class="col-md-6">
                             <a href="print_dept_wise_ip_reports.php?from=<?= $dept_from?>&to=<?= $dept_to?>" data-toggle="tooltip" title="Print Department Report" target="_blank" class="float-right badge badge-pill badge-danger"><i class="icofont-print"></i> Print </a>
                        </div>
                    </div>
                    <hr>

                    <div class="table-responsive">
                    <table id="" class="table table-hoverde text-wrap text-center">
                                    <thead class="thead-dark">
                                    <tr >
                                                <th>Sl NO</th>
                                                <th>Department Name</th>
                                                <th>No.Of New patients Visited</th>
                                            
                                                
                                                
                                                
                                                
                                            </tr>
                                        </thead>
                                        <tbody class="text-center text-black">
                                            
                                    <?php $dept=$newReport->getDept_IP($dept_from,$dept_to); 
                                    $sn=1;$total=0;
                                    while($row=mysqli_fetch_assoc($dept)){
                                    ?>
                                            <tr>
                                                <td><?= $sn?></td>
                                                <td><?= $row['dept_name']?></td>
                                                <td><a href='ipreportdept.php?from=<?= $dept_from?>&to=<?= $dept_to?>&dept=<?= $row['id']?>&dept_name=<?=$row['dept_name']?>' target='_blanks'><?= $row['num']?></a></td>
                                                
                                                
                                                </tr>
                                                <?php $total +=$row['num'];$sn++; } ?>
                                            
                                            
                                            </tbody>
                                            <tfoot>
                                                <th colspan=2 class="text-right">Total</th>
                                                <th><?= $total ?></th>
                                            </tfoot>
                                            </table>   
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
                        <h5 class="ml-4">DEPARTMENT WISE Gender wise Count REPORT<span class="text-danger"> 
                         <?php if(isset($_REQUEST['dept_form'])&&isset($_REQUEST['dept_to'])){ ?>
                            Between <?= $_REQUEST['dept_form']?> and <?= $_REQUEST['dept_to']?>
                            <?php } ?>
                            </span></h5>
                    </div>
                    <hr>

                    <div class="table-responsive">
                    <table id="example3" class="table table-hoverde display text-center">
                                    <thead class="thead-dark">
                                    <tr>
                                                <th>Sl NO</th>
                                                <th>Department Name</th>
                                                <th>Male Count</th>
                                                <th>Female Count</th>
                                                <th>Total</th>
                                                
                                                
                                                
                                            </tr>
                                        </thead>
                                        <tbody class="text-center text-black">
                                            <?php $dept=$newReport->getGenderDept_IP($dept_from,$dept_to); 
                                    $sn=1;
                                    while($row=mysqli_fetch_assoc($dept)){
                                    ?>
                                            <tr>
                                                <td><?= $sn?></td>
                                                <td><?= $row['dept_name']?></td>
                                                 <td><?= $row['male']?></td>
                                                <td><?= $row['female']?></td>
                                                <td ><?= $row['male'] + $row['female']?></td>
                                                
                                                </tr>
                                                <?php $sn++; } ?>
                                            
                                               
                                                
                                                
                                            
                                            
                                            </tbody>
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
                <form action="#pills-combinedop-tab" method="POST">
                <div class="card-body">
                  <div class="row">
                <div class="col-md-12">
                    <h4>Bed Occupancy </h4><br>
                
                </div>
            </div> 

            <div class="row">
        <div class="col-md-4">
           <div class="form-group">
                  <label for="exampleInputEmail1"> Select Date </label>
                  <input type="date" class="form-control"  name="bed_date" id="bed_date" value="<?= $_REQUEST['bed_date']?>">
                  
            </div>
        </div>


         

    </div>

    
    <div class="row">
            <div class="col-md-12">
            <button type="submit" class="btn btn-success  float-right btn-rounded" name="submit"><span><i class="bi bi-search"></i></span> Search</button>
            </div>    
    </div>
        
    </div>
    </form>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <h5 class="ml-4">Bed Occupancy <span class="text-danger"> On <?= date("d.m.Y",strtotime($bed_date))?> </span></h5>
                        <div class="col-md-6">
                             <a href="printbedoccupancy.php?on=<?= $bed_date?>" data-toggle="tooltip" title="Print Bed Report" target="_blank" class="float-right badge badge-pill badge-danger"><i class="icofont-print"></i> Print </a>
                        </div>
                    </div>
                    <hr>

                    <div class="table-responsive">
                    <table id="datatable-buttons4" class="table table-hoverde text-wrap text-center">
                                    <thead class="thead-dark">
                                    <tr >
                                                <th>Sl NO</th>
                                                <th>Date</th>
                                                <th>IP Reg.No New</th>
                                                <th>Name</th>
                                                <th>Gender</th>
                                                <th>Age</th>
                                                <th>Address</th>
                                                <th>Department</th>
                                                <th>DOA </th>
                                                <th>DOD </th>
                                                <th> Bed NO </th>
                                                
                                                
                                                
                                            </tr>
                                        </thead>
                                        <tbody class="text-center text-black">
                                            <?php $sn=1;
                                            $data=$newReport->getBedOccupancy($bed_date);
                                            while($row=mysqli_fetch_assoc($data)){
                                            ?>
                                            <tr>
                                                <td><?= $sn?></td>
                                                <td><?= date("d.m.Y",strtotime($row['admission_date']))?></td>
                                                <td><?= $row['ipd_no']?></td>
                                                <td><?= $row['name']?></td>
                                                <td ><?= $row['gender']?></td>
                                                <td ><?= $row['age']?></td>
                                                <td ><?= $row['address']?></td>
                                                <td ><?= $row['dept_name']?></td>
                                                <td ><?= date("d.m.Y",strtotime($row['admission_date']))?></td>
                                                <td ><?php if(strlen($row['discharge_date'])> 0 ) {echo date("d.m.Y",strtotime($row['discharge_date'])); }?></td>
                                                <td><?= $row['bed_number']?></td>
                                            </tr>
                                            <?php $sn++; } ?>
                                            
                                            
                                            </tbody>
                                            </table>   
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
                        <h5 class="ml-4">Gender wise Bed Occupancy <span class="text-danger"> On <?= date("d.m.Y",strtotime($bed_date))?> </span></h5>
                    </div>
                    <hr>

                    <div class="table-responsive">
                    <table id="example3" class="table table-hoverde display text-center">
                                    <thead class="thead-dark">
                                    <tr >
                                                <th>Sl NO</th>
                                                <th>Department Name</th>
                                                <th>Male</th>
                                                <th>Female</th>
                                                <th>Total</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody class="text-center text-black">
                                            <?php $sn=1;$male=$female=0;
                                            $data=$newReport->getBedOccupancy1($bed_date);
                                            while($row=mysqli_fetch_assoc($data)){
                                            ?>
                                            <tr>
                                                <td><?= $sn?></td>
                                               
                                                <td><?= $row['dept_name']?></td>
                                                <td ><?= $row['male']?></td>
                                                <td ><?= $row['female']?></td>
                                                <td ><?= $row['male']+$row['female']?></td>
                                            
                                               
                                            </tr>
                                            <?php $male+=$row['male'];$female+=$row['female'];
                                            
                                            $sn++;  } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td></td>
                                                    <th>Total</th>
                                                    <th><?= $male?></th>
                                                    <th><?= $female?></th>
                                                    <th><?= $male+$female?></th>
                                                </tr>
                                            </tfoot>
                                            </table>   
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
                        <h5 class="ml-4">List of Patients Admitted <span class="text-danger"> On <?= date("d.m.Y",strtotime($bed_date))?> </span></h5>
                    </div>
                    <hr>

                    <div class="table-responsive">
                    <table id="example3" class="table table-hoverde display text-center">
                                    <thead class="thead-dark">
                                    <tr >
                                                <th>Sl NO</th>
                                                <th>Date</th>
                                                <th>IP Reg.No New</th>
                                                <th>Name</th>
                                                <th>Gender</th>
                                                <th>Age</th>
                                                <th>Address</th>
                                                <th>Department</th>
                                                <th>DOA </th>
                                                <th>DOD </th>
                                                <th> Bed NO </th>
                                                
                                                
                                                
                                            </tr>
                                        </thead>
                                        <tbody class="text-center text-black">
                                            <?php $sn=1;
                                            $data=$newReport->getAdmission($bed_date);
                                            while($row=mysqli_fetch_assoc($data)){
                                            ?>
                                            <tr>
                                                <td><?= $sn?></td>
                                                <td><?= date("d.m.Y",strtotime($row['admission_date']))?></td>
                                                <td><?= $row['ipd_no']?></td>
                                                <td><?= $row['name']?></td>
                                                <td ><?= $row['gender']?></td>
                                                <td ><?= $row['age']?></td>
                                                <td ><?= $row['address']?></td>
                                                <td ><?= $row['dept_name']?></td>
                                                <td ><?= date("d.m.Y",strtotime($row['admission_date']))?></td>
                                                <td ><?php if(strlen($row['discharge_date'])> 0 ) {echo date("d.m.Y",strtotime($row['discharge_date'])); }?></td>
                                                <td><?= $row['bed_number']?></td>
                                            </tr>
                                            <?php $sn++;  } ?>
                                            </tbody>
                                            </table>   
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
                        <h5 class="ml-4">List of Patients Discharged <span class="text-danger"> On <?= date("d.m.Y",strtotime($bed_date))?> </span></h5>
                    </div>
                    <hr>

                    <div class="table-responsive">
                    <table id="example3" class="table table-hoverde display text-center">
                                    <thead class="thead-dark">
                                    <tr >
                                                <th>Sl NO</th>
                                                <th>Date</th>
                                                <th>IP Reg.No New</th>
                                                <th>Name</th>
                                                <th>Gender</th>
                                                <th>Age</th>
                                                <th>Address</th>
                                                <th>Department</th>
                                                <th>DOA </th>
                                                <th>DOD </th>
                                                <th> Bed NO </th>
                                                
                                                
                                                
                                            </tr>
                                        </thead>
                                        <tbody class="text-center text-black">
                                            <?php $sn=1;
                                            $data=$newReport->getDischarge($bed_date);
                                            while($row=mysqli_fetch_assoc($data)){
                                            ?>
                                            <tr>
                                                <td><?= $sn?></td>
                                                <td><?= date("d.m.Y",strtotime($row['admission_date']))?></td>
                                                <td><?= $row['ipd_no']?></td>
                                                <td><?= $row['name']?></td>
                                                <td ><?= $row['gender']?></td>
                                                <td ><?= $row['age']?></td>
                                                <td ><?= $row['address']?></td>
                                                <td ><?= $row['dept_name']?></td>
                                                <td ><?= date("d.m.Y",strtotime($row['admission_date']))?></td>
                                                <td ><?= date("d.m.Y",strtotime($row['discharge_date']))?></td>
                                                <td><?= $row['bed_number']?></td>
                                            </tr>
                                            <?php $sn++; } ?>
                                            </tbody>
                                            </table>   
                    </div>
                </div>
            </div>
        </div>
    </div>

    
</div>

<div class="tab-pane fade  " id="pills-diet" role="tabpanel" aria-labelledby="pills-diet-tab"><br>

<div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
  <div class="row">
                <div class="col-md-12">
                    <h4>Dept wise Bed Occupancy</h4><br>
                  
                </div>
            </div> 
  <form action="#pills-diet-tab" method="POST">
            <div class="row">
        <div class="col-md-4">
           <div class="form-group">
                  <label for="exampleInputEmail1"> From Date </label>
                  <input type="date" class="form-control"  name="beddept_from" id="beddept_from" value="<?= $beddept_from?>" >
                  
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                   <label for="exampleInputEmail1"> To Date  </label>
                   <input type="date" class="form-control"  name="beddept_to" id="beddept_to" value="<?= $beddept_to?>" >
                   
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
                        <h5 class="ml-4">Dept wise Bed Occupancy<span class="text-danger"> <?php if(isset($beddept_from)&&isset($beddept_to)){ ?>
                            Between <?= $beddept_from?> and <?= $beddept_to?>
                            <?php } ?></span></h5>
                    </div>
                     <div class="col-md-12">
                             <a href="print_deptwise_bedoccupcancy.php?from=<?= $beddept_from?>&to=<?= $beddept_to?>" data-toggle="tooltip" title="Print Department Report" target="_blank" class="float-right badge badge-pill badge-danger"><i class="icofont-print"></i> Print </a>
                        </div>
                    <hr>

                    <div class="table-responsive">
                    <table id="datatable-buttons5" class="table table-hoverde text-wrap text-center" style="width:100%">
                                    <thead class="thead-dark">
                                    <tr >
                                                <th>Sl NO</th>
                                                <th>Deparment Name</th>
                                                <th>Remaining</th>
                                                <th>Admisstion</th>
                                                <th>Discharge</th>
                                                <th>Total</th>
                                                
                                                
                                                
                                                
                                                
                                                
                                            </tr>
                                        </thead>
                                        <tbody class="text-center text-black">
                                            
                                            <?php $sn=1;
                                             $diff= date_diff(date_create($beddept_from), date_create($beddept_to));
                                            $interval=$diff->format("%a");
                                            if($interval==0)
                                            {
                                             $interval=1;   
                                            }
                                            // echo "<script>alert('$interval')</script>";
                                            $totramin=$occupied=$discharge=$bed=0;
                                            $totbed=$newReport->getTotalBed($beddept_from);
                                            $bedDept=$newReport->getDeptBed($beddept_from,$beddept_to);
                                            while($row=mysqli_fetch_assoc($bedDept)){
                                            $rema=$row['bed'] - $row['occupied'] + $row['discharge'];
                                            ?>
                                            <tr>
                                                <td><?= $sn?></td>
                                                <td><?= $row['dept_name']?></td>
                                                <td><?= $rema?></td>
                                                <td><?= $row['occupied']?></td>
                                                <td><?= $row['discharge']?></td>
                                                <td><?= $row['bed']?></td> 
                                              </tr>
                                              <?php $sn++; 
                                              $totramin +=$rema;
                                                $occupied +=$row['occupied'];
                                                $discharge +=$row['discharge'];
                                                $bed +=$row['bed'];
                                                $bed_count +=$row['bed_count'];
                                              } 
                                              
                                              
                                              ?>
                                              
                                            </tbody>
                                            <tfoot>
                                                <tr >
                                                <td></td>
                                                <td class="font-weight-bold">Total</td>
                                                <td class="font-weight-bold"><?= $totramin?></td>
                                                <td class="font-weight-bold"><?= $occupied?></td>
                                                <td class="font-weight-bold"><?= $discharge?></td>
                                                <td class="font-weight-bold"><?= $bed." ( ". round((($bed*100)/$totbed)/$interval,2) ."% )"?></td> 
                                              </tr>
                                            </tfoot>
                                            </table>   
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="tab-pane fade  " id="pills-dis" role="tabpanel" aria-labelledby="pills-dis-tab"><br>

<div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
  <div class="row">
                <div class="col-md-12">
                    <h4>Discharge Report</h4><br>
                  
                </div>
            </div> 
  <form action="#pills-dis-tab" method="POST">
            <div class="row">
        <div class="col-md-4">
           <div class="form-group">
                  <label for="exampleInputEmail1"> From Date </label>
                  <input type="date" class="form-control"  name="dis_from" id="dis_from" value="<?= $dis_from?>" >
                  
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                   <label for="exampleInputEmail1"> To Date  </label>
                   <input type="date" class="form-control"  name="dis_to" id="dis_to" value="<?= $dis_to?>" >
                   
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
                        <h5 class="ml-4">Discharge Report between<span class="text-danger"> <?= $dis_from?> and <?= $dis_to?>
                             </span></h5>
                    </div>
                     <div class="col-md-12">
                             <a href="print_discharge_report.php?from=<?= $dis_from?>&to=<?= $dis_to?>" data-toggle="tooltip" title="Print Department Report" target="_blank" class="float-right badge badge-pill badge-danger"><i class="icofont-print"></i> Print </a>
                        </div>
                    <hr>

                    <div class="table-responsive">
                    <table id="datatable-buttons5" class="table table-hoverde text-wrap text-center" style="width:100%">
                                    <thead class="thead-dark">
                                    <tr >
                                                <th>Sl NO</th>
                                                <th>Discharge Type</th>
                                                <th>Total</th>
                                                
                                                
                                                
                                                
                                                
                                                
                                            </tr>
                                        </thead>
                                        <tbody class="text-center text-black">
                                            
                                            <?php $sn=1;
                                           $total=0;
                                            $bedDept=$newReport->getdischargeType($dis_from,$dis_to);
                                            while($row=mysqli_fetch_assoc($bedDept)){
                                            
                                            ?>
                                            <tr>
                                                <td><?= $sn?></td>
                                                <td><?= $row['discharge_condition']?></td>
                                                <td><?= $row['total']?></td> 
                                              </tr>
                                              <?php $total+=$row['total']; $sn++; 
                                            
                                              } 
                                              
                                              
                                              ?>
                                              
                                            </tbody>
                                            <tfeet>
                                                <th colspan=2>TOTAL NO OF DISCHARGE</th>
                                                <th><?= $total?></th>
                                            </tfeet>
                                           
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
                 title: 'IP Report between<?php echo  date('d.m.Y',strtotime($_REQUEST['ip_from']))." and ". date('d.m.Y',strtotime($_REQUEST['ip_to'])); ?>',
               
                orientation: 'landscape',
                pageSize: 'LEGAL'
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
                title: 'Department wise IP Report between <?php echo  date('d.m.Y',strtotime($_REQUEST['ip_from']))." and ". date('d.m.Y',strtotime($_REQUEST['ip_to'])); ?>',
                 orientation: 'landscape',
                pageSize: 'LEGAL'
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
                title: 'Bed Ocupency of<?php echo date("d.m.Y",strtotime($bed_date)) ?>',
                 orientation: 'landscape',
                pageSize: 'LEGAL'
            }
        ]

            });
        });
</script>
 <script>
    $(document).ready(function() {
            $('#datatable-buttons6').DataTable({

                dom: 'Bfrtip',
                responsive: true,
                pageLength: 25,
                // lengthMenu: [0, 5, 10, 20, 50, 100, 200, 500],
                
                buttons: [
           
            {
                extend: 'pdfHtml5',
                title: 'Bed Ocupency of<?php echo date("d.m.Y",strtotime($bed_date)) ?>',
                 orientation: 'landscape',
                pageSize: 'LEGAL'
            }
        ]

            });
        });
</script>
   <script>
    $(document).ready(function() {
            $('#datatable-buttons5').DataTable({

                dom: 'Bfrtip',
                responsive: true,
                pageLength: 25,
                // lengthMenu: [0, 5, 10, 20, 50, 100, 200, 500],
                
                buttons: [
           
            {
                extend: 'pdfHtml5',
                title: 'Department wise Bed Ocupency between <?= $beddept_from?> and <?= $beddept_to?>',
                 
            }
        ]

            });
        });
        
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