<?php
date_default_timezone_set("Asia/Kolkata");  
require_once(__DIR__ . '/model/session_config.php');
include('model/reportClass.php');
$date=date("Y-m-d");
$newReport = new report;


if(isset($_REQUEST['op_from']))
{
    $op_from=$_REQUEST['op_from'];
}
else{
        $op_from=$date;
}
if(isset($_REQUEST['op_to']))
{
    $op_to=$_REQUEST['op_to'];
}
else{
        $op_to=$date;
}


if(isset($_REQUEST['ip_from']))
{
    $ip_from=$_REQUEST['ip_from'];
}
else{
        $ip_from=$date;
}
if(isset($_REQUEST['ip_to']))
{
    $ip_to=$_REQUEST['ip_to'];
}
else{
        $ip_to=$date;
}
if(isset($_REQUEST['bed_date']))
{
    $bed_date=$_REQUEST['bed_date'];
}
else{
        $bed_date=$date;
}
if(isset($_REQUEST['dis_from']))
{
    $dis_from=$_REQUEST['dis_from'];
}
else{
        $dis_from=$date;
}
if(isset($_REQUEST['dis_to']))
{
    $dis_to=$_REQUEST['dis_to'];
}
else{
        $dis_to=$date;
}
if(isset($_REQUEST['radio_from']))
{
    $radio_from=$_REQUEST['radio_from'];
}
else{
        $radio_from=$date;
}
if(isset($_REQUEST['radio_to']))
{
    $radio_to=$_REQUEST['radio_to'];
}
else{
        $radio_to=$date;
}

$op_gen = isset($_REQUEST['op_gen']) ? $_REQUEST['op_gen'] : '';
$op_dept = isset($_REQUEST['op_dept']) ? $_REQUEST['op_dept'] : 'All';
$ip_gen = isset($_REQUEST['ip_gen']) ? $_REQUEST['ip_gen'] : '';
$gender = isset($_REQUEST['gender']) ? $_REQUEST['gender'] : '';
$ip_dept = isset($_REQUEST['ip_dept']) ? $_REQUEST['ip_dept'] : 'All';
$occ_gen = isset($_REQUEST['occ_gen']) ? $_REQUEST['occ_gen'] : '';
$occ_dept = isset($_REQUEST['occ_dept']) ? $_REQUEST['occ_dept'] : 'All';
$dis_gen = isset($_REQUEST['dis_gen']) ? $_REQUEST['dis_gen'] : '';
$dis_dept = isset($_REQUEST['dis_dept']) ? $_REQUEST['dis_dept'] : 'All';
$radio_gen = isset($_REQUEST['radio_gen']) ? $_REQUEST['radio_gen'] : '';
$radio_type = isset($_REQUEST['radio_type']) ? $_REQUEST['radio_type'] : '';

// Optional Time column - shown only when the user ticks it in the filter form
$op_show_time = isset($_REQUEST['op_show_time']) && $_REQUEST['op_show_time']=='1';
$ip_show_time = isset($_REQUEST['ip_show_time']) && $_REQUEST['ip_show_time']=='1';

$_REQUEST['op_gen'] = $op_gen;
$_REQUEST['op_dept'] = $op_dept;
$_REQUEST['ip_gen'] = $ip_gen;
$_REQUEST['gender'] = $gender;
$_REQUEST['ip_dept'] = $ip_dept;
$_REQUEST['occ_gen'] = $occ_gen;
$_REQUEST['occ_dept'] = $occ_dept;
$_REQUEST['dis_gen'] = $dis_gen;
$_REQUEST['dis_dept'] = $dis_dept;
$_REQUEST['radio_gen'] = $radio_gen;
$_REQUEST['radio_type'] = $radio_type;

$active_page = isset($_GET['view']) ? $_GET['view'] : 'op';
$allowed_pages = array('op', 'ip', 'discharge', 'radiology');
if($_SESSION['role']!='Generated') {
    $allowed_pages[] = 'occupancy';
}
if(!in_array($active_page, $allowed_pages)) {
    $active_page = 'op';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Generated Reports</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">

    <link href="./vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">



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
          <h4 class="text-uppercase">Generated Reports</h4>
        </div>


        <ul class="nav nav-pills mb-3 justify-content-around" id="pills-tab" role="tablist">

  <li class="nav-item">
    <a class="nav-link <?= $active_page == 'op' ? 'active' : '' ?>" href="generated.php?view=op"> OP Report </a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?= $active_page == 'ip' ? 'active' : '' ?>" href="generated.php?view=ip">IP Report</a>
  </li>
  <?php if($_SESSION['role']!='Generated') { ?>
  <li class="nav-item">
    <a class="nav-link <?= $active_page == 'occupancy' ? 'active' : '' ?>" href="generated.php?view=occupancy">Occupancy Report</a>
  </li>
  <?php } ?>
  <li class="nav-item">
    <a class="nav-link <?= $active_page == 'discharge' ? 'active' : '' ?>" href="generated.php?view=discharge">Discharge Report</a>
  </li>
  <!--<li class="nav-item">-->
  <!--  <a class="nav-link <?= $active_page == 'radiology' ? 'active' : '' ?>" href="generated.php?view=radiology">Radiology Report</a>-->
  <!--</li>-->
</ul>


    

       

<div class="tab-content" id="pills-tabContent">
<?php if($active_page == 'op') { ?>
<br>
<div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
  <div class="row">
                <div class="col-md-12">
                    <h4>OP Report</h4>
                    <small class="text-muted">Use the bulk option below to print OP case sheets for selected or filtered records.</small><br><br>
                </div>
            </div> 
<form action="generated.php?view=op" method="POST">
            <div class="row">
        <div class="col-md-3">
           <div class="form-group">
                  <label for="exampleInputEmail1"> From Date </label>
                  <input type="date" class="form-control"  name="op_from" id="op_from" value="<?= $op_from?>" required>
                  
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1"> To Date  </label>
                   <input type="date" class="form-control"  name="op_to" id="op_to" value="<?= $op_to?>" required>
                   
             </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1"> Type   </label>
                   <select class="form-control" name="op_gen" id="op_gen">
                       <?php if($_SESSION['role']!='Generated')
                       { ?>
                       <option value="" <?php if($_REQUEST['op_gen']=="") echo"selected";?>>All</option>
                       <option value="0" <?php if($_REQUEST['op_gen']=="0") echo"selected";?>>Original</option>
                       <?php } ?>
                       <option value="1" <?php if($_REQUEST['op_gen']=="1") echo"selected";?>>Generated</option>
                   </select>
                   
             </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1"> Department   </label>
                   <select class="form-control" name="op_dept" id="op_dept">
                       <option value="All" <?php if($_REQUEST['op_dept']=="All") echo"selected";?>>All</option>
                       <?php 
                       $dept=$newReport->getDept();
                       while($row=mysqli_fetch_assoc($dept))
                       { ?>
                           <option value="<?= $row['id']?>" <?php if($_REQUEST['op_dept']==$row['id']) echo"selected";?>><?= $row['dept_name']?></option>
                      <?php }
                       ?>
                    
                   </select>
                   
             </div>
        </div>



    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" name="op_show_time" id="op_show_time" value="1" <?php if($op_show_time) echo "checked";?>>
                    <label class="custom-control-label" for="op_show_time"> Show Time column </label>
                </div>
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
                        <h5 class="ml-4">OP Generated Report <span class="text-danger">
                           
                            Between <?= $op_from?> and <?= $op_to?>
                            
                            </span></h5>
                            </div>
                             <div class="col-md-6">
                                 <button type="button" id="bulk-opcasesheet-btn" class="float-right badge badge-pill badge-primary mr-2 border-0">Bulk OP Case Sheet</button>
                                 <button class="float-right badge badge-pill badge-danger mr-2" onclick="exportTableToExcel('datatable-buttons1', '<?="OPD Report Between ".date('d-m-Y',strtotime($op_from))." to ".date('d-m-Y',strtotime($op_to))?>')">Export to Excel</button>
                             <a href="patient_list.php?from=<?= $op_from?>&to=<?= $op_to?>&gen=<?= $_REQUEST['op_gen']?>&dept=<?= $_REQUEST['op_dept']?>" data-toggle="tooltip" title="Print OP Report" target="_blank" class="float-right badge badge-pill badge-danger mr-2"><i class="icofont-print"></i> Print </a>
                        </div>
                    </div>
                    <hr>

                    <div class="table-responsive">
                     <table id="datatable-buttons1" class="table text-wrap text-center  no-footer " role="grid" aria-describedby="datatable-buttons_info">
                                    <thead class="thead-dark">
                                     <tr>
                                                <th><input type="checkbox" id="select-all-opcasesheet" title="Select all filtered rows"></th>
                                                <th>Sl NO</th>
                                                <th>Date</th>
                                                <?php if($op_show_time) { ?><th>Time</th><?php } ?>
                                                <th>UHID</th>
                                                <th>OP Reg No New</th>
                                                <th>OP Reg No Old</th>
                                                <th>Name</th>
                                                <th>Gender</th>
                                                <th>Age</th>
                                                <th>Address</th>
                                                <!--<th>Diagnosis</th> -->
                                                <!-- <th>Symptoms</th> -->
                                                <th>Doctor</th>
                                                <!--<th>Unit</th>-->
                                                <th>Department</th>
                                                <th>Case Type</th>
                                                <th>Created At</th>
                                                <th>Type</th>
                                                <th>Remarks</th>
                                      </tr>
                                        </thead>
                                        <tbody class="text-center text-black">
                                            
                                       <?php $ot=$newReport->getOPDetails($op_from,$op_to,$_REQUEST['op_gen'],$_REQUEST['op_dept']);
                $sn=1;
                while($row=mysqli_fetch_assoc($ot)){
                ?>
                                            <tr>
                                                <td><input type="checkbox" class="opcasesheet-row" value="<?= isset($row['id']) ? (int)$row['id'] : 0 ?>"></td>
                                                <td><?= $sn?></td>
                                                <td><?= date("d.m.Y",strtotime($row['date']))?></td>
                                                <?php if($op_show_time) { ?><td><?= (!empty($row['created_at']) && $row['created_at']!='0000-00-00 00:00:00')? date("h:i A",strtotime($row['created_at'])):''?></td><?php } ?>
                                                <td><?= $row['uhid']?></td>
                                                <?php if($row['old_patient']==0) {?>
                                                <td><?= $row['opd_no']?></td>
                                                <td></td>

                                                <?php } else { ?>
                                                <td></td>
                                                <td><?= $row['opd_no']?></td>

                                                <?php } ?>
                                                <td><?= $row['name']?></td>
                                                <td><?= $row['gender']?></td>
                                                <td><?= $row['age']?></td>
                                                <td><?= $row['address']?></td>
                                                <!--<td><?= $row['diagnosis']?></td>-->
                                                <!-- <td><?= $row['symptoms']?></td> -->
                                                <td><?= $row['doctor_name']?></td>
                                                <!--<td><?= $row['unit']?></td>-->
                                                <td><?= $row['dept_name']?></td>
                                                <td><?= $row['patient_type']?></td>
                                                <td><?= date("d.m.Y h:i A",strtotime($row['created_at']))?></td>
                                                <td><?php if($row['is_generated']==1){ echo "Generated";} else{ echo "Original";}?></td>
                                                <td><?= $row['remark']?> <?php if($row['ipd_no']!='') { echo " Admitted"; } ?></td>
                                            </tr>
                     <?php $sn++; } ?>     
                                            
                                            </tbody>
                                            </table>   
                                             <hr>
                                             <table id="datatable-buttons3"  class="table text-wrap text-center">
                                    <thead class="thead-dark">
                                    <tr >
                                                <th>Sl NO</th>
                                                <th>Departmwent Name</th>
                                                <th>No.Of New patients Visited</th>
                                                <th>No.Of Old patients Visited</th>
                                                <th>Total</th>
                                                
                                                
                                                
                                            </tr>
                                        </thead>
                                        <tbody class="text-center text-black">
                                            <?php $dept=$newReport->getDept_OP1($op_from,$op_to,$_REQUEST['op_gen'],$_REQUEST['op_dept']);
                                            $sn=1;$ntotal=$ototal=$total=0;
                                            while ($row=mysqli_fetch_assoc($dept)){?>
                                            <tr> </div>
                                                <td><?= $sn?></td>
                                                <td><?= $row['dept_name']?></td>
                                                <td ><?= $row['num'] - $row['old_patient']?></td>
                                                <td><?= $row['old_patient']?></td>
                                                <td><?= $row['num']?></td>

                                            </tr>
                                              <?php 
                                              $ntotal += $row['num'] - $row['old_patient'];
                                              $ototal += $row['old_patient'];
                                              $total +=$row['num'];
                                              $sn++; } ?>  
                                            
                                            
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th></th>
                                                <th>Total</th>
                                                <th><?= $ntotal ?></th>
                                                <th><?= $ototal ?></th>
                                                <th><?= $total ?></th>
                                                </tr>
                                            </tfoot>
                                            </table>   
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<?php } elseif($active_page == 'ip') { ?>
<br>
<div class="row">
   
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                     <form action="generated.php?view=ip" method="POST">
  <div class="row">
                <div class="col-md-6">
                    <h4>IP REPORT </h4>
                    <small class="text-muted">Use the bulk options below to print admission slips, address graphs, or OPD case sheets for selected or filtered IP records.</small><br><br>
                </div>
                
            </div> 
                    
            <div class="row">
        <div class="col-md-2">
           <div class="form-group">
                  <label for="exampleInputEmail1"> From Date </label>
                  <input type="date" class="form-control"  name="ip_from" id="ip_from" value="<?= $ip_from?>" required>
                  
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                   <label for="exampleInputEmail1"> To Date  </label>
                   <input type="date" class="form-control"  name="ip_to" id="ip_to" value="<?= $ip_to?>" required>
                   
             </div>
        </div>
     <div class="col-md-2">
            <div class="form-group">
                   <label for="exampleInputEmail1"> Type   </label>
                   <select class="form-control" name="ip_gen" id="ip_gen">
                        <?php if($_SESSION['role']!='Generated')
                       { ?>

                       <option value="" <?php if($_REQUEST['ip_gen']=="") echo"selected";?>>All</option>
                       <option value="0" <?php if($_REQUEST['ip_gen']=="0") echo"selected";?>>Original</option>
                  
                        <?php } ?>
                       <option value="1" <?php if($_REQUEST['ip_gen']=="1") echo"selected";?>>Generated</option>
                   </select>
                   
             </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                   <label for="exampleInputEmail1"> Gender   </label>
                   <select class="form-control" name="gender" id="gender">
                   

                       <option value="" <?php if($_REQUEST['gender']=="") echo"selected";?>>All</option>
                       <option value="Male" <?php if($_REQUEST['gender']=="Male") echo"selected";?>>Male</option>
                  
                       <option value="Female" <?php if($_REQUEST['gender']=="Female") echo"selected";?>>Female</option>
                   </select>
                   
             </div>
        </div>
          <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1"> Department   </label>
                   <select class="form-control" name="ip_dept" id="ip_dept">
                       <option value="All" <?php if($_REQUEST['ip_dept']=="All") echo"selected";?>>All</option>
                       <?php 
                       $dept=$newReport->getDept();
                       while($row=mysqli_fetch_assoc($dept))
                       { ?>
                           <option value="<?= $row['id']?>" <?php if($_REQUEST['ip_dept']==$row['id']) echo"selected";?>><?= $row['dept_name']?></option>
                      <?php }
                       ?>
                    <option value="ICU" <?php if($_REQUEST['ip_dept']=='ICU') echo"selected";?>>ICU</option>
                   </select>

             </div>
        </div>

    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" name="ip_show_time" id="ip_show_time" value="1" <?php if($ip_show_time) echo "checked";?>>
                    <label class="custom-control-label" for="ip_show_time"> Show Time column </label>
                </div>
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
                       <h5 class="ml-4">IP Generated REPORT  <span class="text-danger">
                            
                            Between <?= $ip_from?> and <?= $ip_to?>
                            
                            </span></h5>
                       </div>

                            <div class="col-md-6">
                                <button type="button" id="bulk-ip-opcasesheet-btn" class="float-right badge badge-pill badge-info mr-2 border-0">Bulk OPD Case Sheet</button>
                                <button type="button" id="bulk-address-graph-btn" class="float-right badge badge-pill badge-success mr-2 border-0">Bulk Address Graph</button>
                                <button type="button" id="bulk-admission-slip-btn" class="float-right badge badge-pill badge-primary mr-2 border-0">Bulk Admission Slip</button>
                                <button class="float-right badge badge-pill badge-danger mr-2" onclick="exportTableToExcel('datatable-buttons4', '<?="IPD Report Between ".date('d-m-Y',strtotime($ip_from))." to ".date('d-m-Y',strtotime($ip_to))?>')">Export to Excel</button>
                             <a href="ippatient_list.php?from=<?= $ip_from?>&to=<?= $ip_to?>&gen=<?= $_REQUEST['ip_gen']?>&dept=<?= $_REQUEST['ip_dept']?>&gender=<?= $_REQUEST['gender']?>" data-toggle="tooltip" title="Print Department Wise OT Report" target="_blank" class="float-right badge badge-pill badge-danger mr-2"><i class="icofont-print"></i> Print </a>
                        </div>
                    </div>
                    <hr>

                    <div class="table-responsive">
                      <table id="datatable-buttons4" class="table text-wrap text-center  no-footer " role="grid" aria-describedby="datatable-buttons_info">
                                    <thead class="thead-dark">
                                    <tr >
                                    <th><input type="checkbox" id="select-all-admission-slip" title="Select all filtered rows"></th>
                                    <th>Sl.No</th>
                          <th>Date</th>
                          <?php if($ip_show_time) { ?><th>Time</th><?php } ?>
                          <th>UHID</th>
                          <th>OP No</th>
                          <th>IP No</th>
                          <th>Patient Name</th>
                          <th>Gender</th>
                          <th>Age</th>
                           <th>Address</th>
                                                 <th>Diagnosis</th> 
                                               
                                                <th>Doctor</th>
                                                <!--<th>Unit</th>-->
                                                 <th>Department</th>
                                                 <th>Ward</th>
                                                 <th>Bed No.</th>
                                               <th>DOD</th>
                                               <th>Case Type</th>
                                               <!--<th>Discharged By</th>-->
                                              <th>Type</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center text-black">
                            <?php $sn=1;
                            $data=$newReport->getIPGenDetails($ip_from,$ip_to,$_REQUEST['ip_gen'],$_REQUEST['ip_dept'],$_REQUEST['gender']);
                            while($row=mysqli_fetch_assoc($data)){
                                $date1=date_create($row['admission_date']);
                                $date2=date_create($row['discharge_date']);
                                $diff=date_diff($date1,$date2)->format("%a");
                                // echo $diff;
                            ?>                
                        <tr height="25">
                          <td><input type="checkbox" class="admission-slip-row" value="<?= isset($row['id']) ? (int)$row['id'] : 0 ?>" data-opcase-id="<?= isset($row['op_case_id']) ? (int)$row['op_case_id'] : 0 ?>"></td>
                          <td><?= $sn?></td>
                        <td><?= date("d.m.Y",strtotime($row['admission_date']))?></td>
                        <?php if($ip_show_time) { ?><td><?= (!empty($row['admission_date']) && $row['admission_date']!='0000-00-00 00:00:00')? date("h:i A",strtotime($row['admission_date'])):''?></td><?php } ?>
                        <td><?= $row['uhid']?></td>
                        <td><?= $row['opd_no']?></td>
                        <td><a href="casesheet_generator.php?patient=<?= $row['name']?>&admission_date=<?= $row['admission_date']?>&age=<?= $row['age']?>&gender=<?= $row['gender']?>&diagnosis=<?= $row['diagnosis']?>&dept=<?= $row['dept_name']?>&dih=<?= $diff?>" target="_blank"><?= $row['ipd_no']?></a></td>
                        <td><?= $row['name']?></td>
                        <td><?= $row['gender']?></td>
                        <td><?= $row['age']?></td>
                        <td><?= $row['address']?></td>
                        <td><?= $row['diagnosis']?></td>
                        <td><?= $row['doctor']?></td>
                        <!--<td><?= $row['unit']?></td>-->
                        <td><?= $row['dept_name']?></td>
                        <td><?= $row['ward']?></td>
                        <td><?= $row['bed_number']?></td>
                        <td><?php if($row['discharge_date']>'0000-00-00') echo date("d.m.Y",strtotime($row['discharge_date']))?></td>
                       <td><?= $row['patient_type']?></td>
                        <!--<td><?=$row['dis_by']?></td>-->
                        <td><?php if($row['is_generated']==1){ echo "Generated";} else{ echo "Original";}?></td>
                        
                          </tr>
                          <?php $sn++;} ?>
                                            </tbody>
                                            </table>   
                                            <hr>
                                            
                                            <table id="" class="table table-hoverde text-wrap text-center">
                                    <thead class="thead-dark">
                                    <tr >
                                                <th>Sl NO</th>
                                                <th>Department Name</th>
                                                <th>No.Of New patients Visited</th>
                                            
                                                
                                                
                                                
                                                
                                            </tr>
                                        </thead>
                                        <tbody class="text-center text-black">
                                            
                                    <?php $dept=$newReport->getDept_IP1($ip_from,$ip_to,$_REQUEST['ip_gen'],$_REQUEST['ip_dept']); 
                                    $sn=1;$total=0;
                                    while($row=mysqli_fetch_assoc($dept)){
                                    ?>
                                            <tr>
                                                <td><?= $sn?></td>
                                                <td><?= $row['dept_name']?></td>
                                                <td><?= $row['num']?></td>
                                                
                                                
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

    

    
</div>
<?php } elseif($active_page == 'occupancy') { ?>
<br>
<div class="row">
    
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="generated.php?view=occupancy" method="POST">
  <div class="row">
                <div class="col-md-6">
                    <h4>OCCUPANCY REPORT </h4><br>

                </div>
                
            </div> 
                    
    <div class="row">
        <div class="col-md-4">
           <div class="form-group">
                  <label for="exampleInputEmail1"> Date </label>
                  <input type="date" class="form-control"  name="bed_date" id="bed_date" value="<?= $bed_date?>" required>
                  
            </div>
        </div>

      
     <div class="col-md-4">
            <div class="form-group">
                   <label for="exampleInputEmail1"> Type   </label>
                   <select class="form-control" name="occ_gen" id="occ_gen">
                       <?php if($_SESSION['role']!='Generated')
                       { ?>
                       <option value="" <?php if($_REQUEST['occ_gen']=="") echo"selected";?>>All</option>
                       <option value="0" <?php if($_REQUEST['occ_gen']=="0") echo"selected";?>>Original</option>
                       <?php } ?>
                       <option value="1" <?php if($_REQUEST['occ_gen']=="1") echo"selected";?>>Generated</option>
                   </select>
                   
             </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                   <label for="exampleInputEmail1"> Department   </label>
                   <select class="form-control" name="occ_dept" id="occ_dept">
                       <option value="All" <?php if($_REQUEST['occ_dept']=="All") echo"selected";?>>All</option>
                       <?php 
                       $dept=$newReport->getDept();
                       while($row=mysqli_fetch_assoc($dept))
                       { ?>
                           <option value="<?= $row['id']?>" <?php if($_REQUEST['occ_dept']==$row['id']) echo"selected";?>><?= $row['dept_name']?></option>
                      <?php }
                       ?>
                    <option value="ICU" <?php if($_REQUEST['occ_dept']=='ICU') echo"selected";?>>ICU</option>
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
                       <h5 class="ml-4">Occupancy Generated REPORT </h5>
                       </div>

                            <div class="col-md-6">
                                <button class="float-right badge badge-pill badge-danger" onclick="exportTableToExcel('datatable-buttons5', '<?="Occupancy Report of ".date('d-m-Y',strtotime($bed_date))?>')">Export to Excel</button>
                           
                             <a href="occpatient_list.php?from=<?= $bed_date?>&gen=<?= $_REQUEST['occ_gen']?>&dept=<?= $_REQUEST['occ_dept']?>" data-toggle="tooltip" title="Print Department Wise OT Report" target="_blank" class="float-right badge badge-pill badge-danger"><i class="icofont-print"></i> Print </a>
                        </div>
                    </div>
                    <hr>

                    <div class="table-responsive">
                    <table id="datatable-buttons5" class="table table-hoverde text-wrap text-center">
                                    <thead class="thead-dark">
                                    <tr >
                                                <th>Sl NO</th>
                                                <th>Date</th>
                                                <th>IP Reg.No New</th>
                                                <th>Name</th>
                                                <th>Gender</th>
                                                <th>Age</th>
                                                <th>Department</th>
                                                <th>DOA </th>
                                                <th>DOD </th>
                                                <th> Bed NO </th>
                                                <th> Ward </th>
                                                <th>case type</th>
                                                <th> Type </th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody class="text-center text-black">
                                            <?php
                                            $sn=1;
                                            $deptWiseCount = array();
                                            $totalOccupancy = 0;
                                            $data=$newReport->getBedOccupancyGen($bed_date,$_REQUEST['occ_gen'],$_REQUEST['occ_dept']);
                                            while($row=mysqli_fetch_assoc($data)){
                                                $deptName = (!empty($row['ward']) && strtoupper(trim($row['ward'])) == 'ICU')
                                                    ? 'ICU'
                                                    : (!empty($row['dept_name']) ? $row['dept_name'] : 'Unknown');
                                                if(!isset($deptWiseCount[$deptName])) {
                                                    $deptWiseCount[$deptName] = 0;
                                                }
                                                $deptWiseCount[$deptName]++;
                                                $totalOccupancy++;
                                            ?>
                                            <tr>
                                                <td><?= $sn?></td>
                                                <td><?= date("d.m.Y",strtotime($row['admission_date']))?></td>
                                                <td><?= $row['ipd_no']?></td>
                                                <td><?= $row['name']?></td>
                                                <td ><?= $row['gender']?></td>
                                                <td ><?= $row['age']?></td>
                                                <td ><?= $row['dept_name']?></td>
                                                <td ><?= date("d.m.Y",strtotime($row['admission_date']))?></td>
                                                <td ><?php if(strlen($row['discharge_date'])> 0 ) {echo date("d.m.Y",strtotime($row['discharge_date'])); }?></td>
                                                <td><?= $row['bed_number']?></td><td><?= $row['ward']?></td>
                                                <td><?= $row['patient_type']?></td>
                                                <td><?php if($row['is_generated']==1){ echo "Generated";} else{ echo "Original";}?></td>
                                            </tr>
                                            <?php $sn++; } ?>
                                            </tbody>
                                            </table>

                                            <?php ksort($deptWiseCount); ?>
                                            <hr>
                                            <table id="datatable-buttons6" class="table table-hoverde text-wrap text-center">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th>Sl NO</th>
                                                        <th>Department Name</th>
                                                        <th>Occupancy Count</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="text-center text-black">
                                                    <?php $deptSn = 1; foreach($deptWiseCount as $deptName => $count){ ?>
                                                    <tr>
                                                        <td><?= $deptSn ?></td>
                                                        <td><?= $deptName ?></td>
                                                        <td><?= $count ?></td>
                                                    </tr>
                                                    <?php $deptSn++; } ?>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="2" class="text-right">Total</th>
                                                        <th><?= $totalOccupancy ?></th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    

    
</div>
<?php } elseif($active_page == 'discharge') { ?>
<br>
<div class="row">
   
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                     <form action="generated.php?view=discharge" method="POST">
  <div class="row">
                <div class="col-md-6">
                    <h4>Discharge REPORT </h4><br>

                </div>
                
            </div> 
                    
            <div class="row">
        <div class="col-md-3">
           <div class="form-group">
                  <label for="exampleInputEmail1"> From Date </label>
                  <input type="date" class="form-control"  name="dis_from" id="dis_from" value="<?= $dis_from?>" required>
                  
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1"> To Date  </label>
                   <input type="date" class="form-control"  name="dis_to" id="dis_to" value="<?= $dis_to?>" required>
                   
             </div>
        </div>
     <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1"> Type   </label>
                   <select class="form-control" name="dis_gen" id="dis_gen">
                       <?php if($_SESSION['role']!='Generated')
                       { ?>
                       <option value="" <?php if($_REQUEST['dis_gen']=="") echo"selected";?>>All</option>
                       <option value="0" <?php if($_REQUEST['dis_gen']=="0") echo"selected";?>>Original</option>
                       <?php } ?>
                       <option value="1" <?php if($_REQUEST['dis_gen']=="1") echo"selected";?>>Generated</option>
                   </select>
                   
             </div>
        </div>
          <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1"> Department   </label>
                   <select class="form-control" name="dis_dept" id="dis_dept">
                       <option value="All" <?php if($_REQUEST['dis_dept']=="All") echo"selected";?>>All</option>
                       <?php 
                       $dept=$newReport->getDept();
                       while($row=mysqli_fetch_assoc($dept))
                       { ?>
                           <option value="<?= $row['id']?>" <?php if($_REQUEST['dis_dept']==$row['id']) echo"selected";?>><?= $row['dept_name']?></option>
                      <?php }
                       ?>
                    
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
                       <h5 class="ml-4">Dischargre REPORT  <span class="text-danger">
                            
                            Between <?= $dis_from?> and <?= $dis_to?>
                            
                            </span></h5>
                       </div>

                            <div class="col-md-6">
                                <button class="float-right badge badge-pill badge-danger" onclick="exportTableToExcel('datatable-buttons2', '<?="Discharge Report Between ".date('d-m-Y',strtotime($dis_from))." to ".date('d-m-Y',strtotime($dis_to))?>')">Export to Excel</button>
                             <a href="discpatient_list.php?from=<?= $dis_from?>&to=<?= $dis_to?>&gen=<?= $_REQUEST['dis_gen']?>&dept=<?= $_REQUEST['dis_dept']?>" data-toggle="tooltip" title="Print Department Wise OT Report" target="_blank" class="float-right badge badge-pill badge-danger"><i class="icofont-print"></i> Print </a>
                        </div>
                    </div>
                    <hr>

                    <div class="table-responsive">
                     <table id="datatable-buttons2" class="table text-wrap text-center  no-footer " role="grid" aria-describedby="datatable-buttons_info">
                                    <thead class="thead-dark">
                                    <tr >
                                    <th>Sl.No</th>
                          <th>Admitted Date</th>
                          <th>Discharge Date</th>
                          <th>OP No</th>
                          <th>IP No</th>
                          <th>Patient Name</th>
                          <th>Gender</th>
                          <th>Age</th>
                           <th>Address</th>
                                                 <th>Diagnosis</th> 
                                               
                                                <th>Doctor</th>
                                                <th>Unit</th>
                                                 <th>Department</th>
                                               
                                              <th>Type</th>
                                                
                                                <th>Discharged By</th>
                                                <th>Case Type</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center text-black">
                            <?php $sn=1;
                            $data=$newReport->getDisGenDetails($dis_from,$dis_to,$_REQUEST['dis_gen'],$_REQUEST['dis_dept']);
                            while($row=mysqli_fetch_assoc($data)){
                            ?>                
                        <tr height="25">
                          <td><?= $sn?></td>
                        <td><?= date("d.m.Y",strtotime($row['admission_date']))?></td>
                        <td><?= date("d.m.Y",strtotime($row['discharge_date']))?></td>
                        <td><?= $row['opd_no']?></td>
                        <td><?= $row['ipd_no']?></td>
                        <td><?= $row['name']?></td>
                        <td><?= $row['gender']?></td>
                        <td><?= $row['age']?></td>
                        <td><?= $row['address']?></td>
                        <td><?= $row['diagnosis']?></td>
                        <td><?= $row['doctor']?></td>
                        <td><?= $row['unit']?></td>
                        <td><?= $row['dept_name']?></td>
                        <td><?php if($row['is_generated']==1){ echo "Generated";} else{ echo "Original";}?></td>
                      <td><?= $row['created_by']?></td>
                      <td><?= $row['patient_type']?></td>
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
<?php } elseif($active_page == 'radiology') { ?>
<br>
<div class="row">
   
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                     <form action="generated.php?view=radiology" method="POST">
  <div class="row">
                <div class="col-md-6">
                    <h4>Radiology REPORT </h4><br>

                </div>
                
            </div> 
                    
            <div class="row">
        <div class="col-md-3">
           <div class="form-group">
                  <label for="exampleInputEmail1"> From Date </label>
                  <input type="date" class="form-control"  name="radio_from" id="radio_from" value="<?= $radio_from?>" required>
                  
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1"> To Date  </label>
                   <input type="date" class="form-control"  name="radio_to" id="radio_to" value="<?= $radio_to?>" required>
                   
             </div>
        </div>
     <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1"> Type   </label>
                   <select class="form-control" name="radio_gen" id="radio_gen">
                       <option value="" <?php if($_REQUEST['radio_gen']=="") echo"selected";?>>All</option>
                       <option value="0" <?php if($_REQUEST['radio_gen']=="0") echo"selected";?>>Original</option>
                       <option value="1" <?php if($_REQUEST['radio_gen']=="1") echo"selected";?>>Generated</option>
                   </select>
                   
             </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1">Patient Type   </label>
                   <select class="form-control" name="radio_type" id="radio_type">
                       <option value="" <?php if($_REQUEST['radio_type']=="") echo"selected";?>>All</option>
                       <option value="OP" <?php if($_REQUEST['radio_type']=="OP") echo"selected";?>>OP</option>
                       <option value="IP" <?php if($_REQUEST['radio_type']=="IP") echo"selected";?>>IP</option>
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
                       <h5 class="ml-4">Radiology REPORT  <span class="text-danger">
                            
                            Between <?= $radio_from?> and <?= $radio_to?>
                            
                            </span></h5>
                       </div>

                            <div class="col-md-6">
                             <a href="print_radio_gen.php?from=<?= $radio_from?>&to=<?= $radio_to?>&gen=<?= $_REQUEST['radio_gen']?>&type=<?= $_REQUEST['radio_type']?>" data-toggle="tooltip" title="Print Department Wise OT Report" target="_blank" class="float-right badge badge-pill badge-danger"><i class="icofont-print"></i> Print </a>
                        </div>
                    </div>
                    <hr>

                    <div class="table-responsive">
                     <table id="datatable-buttons2" class="table text-wrap text-center  no-footer " role="grid" aria-describedby="datatable-buttons_info">
                                    <thead class="thead-dark">
                                    <tr >
                                    <th>Sl.No</th>
                          <th>Date</th>
                          <th>OP No</th>
                          <th>IP No</th>
                          <th>Patient Name</th>
                          <th>Gender</th>
                          <th>Age</th>
                           <th>Address</th>
                            <th>Test Name</th>
                                                
                                               
                                              <th>Type</th>
                                                
                                                
                                                
                                            </tr>
                                        </thead>
                                        <tbody class="text-center text-black">
                            <?php $sn=1;
                            $data=$newReport->radioGenReport($radio_from,$radio_to,$_REQUEST['radio_gen'],$_REQUEST['radio_type']);
                            while($row=mysqli_fetch_assoc($data)){
                            ?>                
                        <tr height="25">
                          <td><?= $sn?></td>
                        <td><?= date("d.m.Y",strtotime($row['date']))?></td>
                       
                        <td><?= $row['opd_no']?></td>
                        <td><?= $row['ipd_no']?></td>
                        <td><?= $row['name']?></td>
                        <td><?= $row['gender']?></td>
                        <td><?= $row['age']?></td>
                        <td><?= $row['address']?></td>
                        <td><?= $row['testname']?></td>
                        
                        <td><?php if($row['is_generated']==1){ echo "Generated";} else{ echo "Original";}?></td>
                      
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
<?php } ?>
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
    <!-- Datatable -->
    <script src="./vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="./js/plugins-init/datatables.init.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
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


 <script>
 function exportTableToExcel(tableID, filename){
        function downloadWorkbook(table) {
            let wb = XLSX.utils.table_to_book(table, {sheet: "Sheet1"});
            let wbout = XLSX.write(wb, {bookType: 'xlsx', type: 'binary'});

            function s2ab(s) {
                let buf = new ArrayBuffer(s.length);
                let view = new Uint8Array(buf);
                for (let i = 0; i < s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
                return buf;
            }

            let blob = new Blob([s2ab(wbout)], {type: "application/octet-stream"});
            let link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = filename ? filename + '.xlsx' : 'table_data.xlsx';
            link.click();
        }

        let selector = '#' + tableID;
        let table = document.getElementById(tableID);
        if (!table) {
            return;
        }

        if ($.fn.dataTable && $.fn.dataTable.isDataTable(selector)) {
            let dt = $(selector).DataTable();
            let oldPageLength = dt.page.len();
            let oldPageIndex = dt.page();

            // Export all filtered rows, not just the current paginated page.
            dt.page.len(-1).draw();

            setTimeout(function() {
                downloadWorkbook(table);
                dt.page.len(oldPageLength).draw(false);
                if (oldPageIndex >= 0) {
                    dt.page(oldPageIndex).draw('page');
                }
            }, 0);
            return;
        }

        downloadWorkbook(table);
}
    $(document).ready(function() {
            function initializeReportTable(selector, extraOptions) {
                if (!$(selector).length) {
                    return null;
                }

                var defaultOptions = {
                    dom: 'Bfrtip',
                    responsive: true,
                    paging: true,
                    bPaginate: true,
                    bLengthChange: true,
                    lengthChange: true,
                    pageLength: 25,
                    lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']]
                };

                return $(selector).DataTable($.extend(true, {}, defaultOptions, extraOptions || {}));
            }

            initializeReportTable('#datatable-buttons2');
            var opReportTable = initializeReportTable('#datatable-buttons1', {
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: 'Download Excel'
                    }
                ]
            });
            initializeReportTable('#datatable-buttons3');
            initializeReportTable('#datatable-buttons5');
            initializeReportTable('#datatable-buttons6');
            var ipReportTable = initializeReportTable('#datatable-buttons4');

            $('#select-all-opcasesheet').on('change', function() {
                if (!opReportTable) {
                    return;
                }

                var isChecked = $(this).is(':checked');
                $(opReportTable.rows({ search: 'applied' }).nodes()).find('.opcasesheet-row').prop('checked', isChecked);
            });

            $('#bulk-opcasesheet-btn').on('click', function() {
                if (!opReportTable) {
                    return;
                }

                var selectedIds = [];
                $(opReportTable.rows({ search: 'applied' }).nodes()).find('.opcasesheet-row:checked').each(function() {
                    if ($(this).val() && $(this).val() !== '0') {
                        selectedIds.push($(this).val());
                    }
                });

                if (selectedIds.length === 0) {
                    $(opReportTable.rows({ search: 'applied' }).nodes()).find('.opcasesheet-row').each(function() {
                        if ($(this).val() && $(this).val() !== '0') {
                            selectedIds.push($(this).val());
                        }
                    });
                }

                selectedIds = [...new Set(selectedIds)];

                if (selectedIds.length === 0) {
                    alert('No OP records found for bulk casesheet printing.');
                    return;
                }

                window.open('bulk_opcasesheet.php?id=' + encodeURIComponent(selectedIds.join(',')), '_blank');
            });

            $('#select-all-admission-slip').on('change', function() {
                if (!ipReportTable) {
                    return;
                }

                var isChecked = $(this).is(':checked');
                $(ipReportTable.rows({ search: 'applied' }).nodes()).find('.admission-slip-row').prop('checked', isChecked);
            });

            function getSelectedIpReportIds() {
                if (!ipReportTable) {
                    return [];
                }

                var selectedIds = [];
                $(ipReportTable.rows({ search: 'applied' }).nodes()).find('.admission-slip-row:checked').each(function() {
                    if ($(this).val() && $(this).val() !== '0') {
                        selectedIds.push($(this).val());
                    }
                });

                if (selectedIds.length === 0) {
                    $(ipReportTable.rows({ search: 'applied' }).nodes()).find('.admission-slip-row').each(function() {
                        if ($(this).val() && $(this).val() !== '0') {
                            selectedIds.push($(this).val());
                        }
                    });
                }

                return [...new Set(selectedIds)];
            }

            function getSelectedIpReportOpCaseIds() {
                if (!ipReportTable) {
                    return [];
                }

                var selectedOpIds = [];
                $(ipReportTable.rows({ search: 'applied' }).nodes()).find('.admission-slip-row:checked').each(function() {
                    var opCaseId = $(this).data('opcase-id');
                    if (opCaseId && String(opCaseId) !== '0') {
                        selectedOpIds.push(String(opCaseId));
                    }
                });

                if (selectedOpIds.length === 0) {
                    $(ipReportTable.rows({ search: 'applied' }).nodes()).find('.admission-slip-row').each(function() {
                        var opCaseId = $(this).data('opcase-id');
                        if (opCaseId && String(opCaseId) !== '0') {
                            selectedOpIds.push(String(opCaseId));
                        }
                    });
                }

                return [...new Set(selectedOpIds)];
            }

            $('#bulk-admission-slip-btn').on('click', function() {
                var selectedIds = getSelectedIpReportIds();

                if (selectedIds.length === 0) {
                    alert('No IP records found for bulk admission slip printing.');
                    return;
                }

                window.open('bulk_admission_slip.php?id=' + encodeURIComponent(selectedIds.join(',')), '_blank');
            });

            $('#bulk-address-graph-btn').on('click', function() {
                var selectedIds = getSelectedIpReportIds();

                if (selectedIds.length === 0) {
                    alert('No IP records found for bulk address graph printing.');
                    return;
                }

                window.open('bulk_address_graph.php?id=' + encodeURIComponent(selectedIds.join(',')), '_blank');
            });

            $('#bulk-ip-opcasesheet-btn').on('click', function() {
                var selectedOpIds = getSelectedIpReportOpCaseIds();

                if (selectedOpIds.length === 0) {
                    alert('No linked OPD case sheets were found for the selected IP records.');
                    return;
                }

                window.open('bulk_opcasesheet.php?id=' + encodeURIComponent(selectedOpIds.join(',')), '_blank');
            });
        });
</script>
  

</body>

</html>