<?php
date_default_timezone_set("Asia/Kolkata");  
require_once(__DIR__ . '/model/session_config.php');



include('model/reportClass.php');
$newReport = new report;

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
if(isset($_REQUEST['dept']))
{
    $dept=$_REQUEST['dept'];
}
else{
 $dept=0;   
}



if(isset($_REQUEST['doc_from']))
{
    $doc_from=$_REQUEST['doc_from'];
}
else{
     $doc_from=date('Y-m-d');
}
if(isset($_REQUEST['doc_to']))
{
    $doc_to=$_REQUEST['doc_to'];
}
else{
     $doc_to=date('Y-m-d');
}

if(isset($_REQUEST['doctor']))
{
    $doctor=$_REQUEST['doctor'];
}
else{
 $doctor=0;   
}
if(isset($_REQUEST['type']))
{
    $type=$_REQUEST['type'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Periodical Reports</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">

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
          <h4 class="text-uppercase">Periodical Reports</h4>
        </div>


        <ul class="nav nav-pills mb-3 justify-content-around" id="pills-tab" role="tablist">
             <li class="nav-item">
    <a class="nav-link" id="pills-deptwise-tab" data-toggle="pill" href="#pills-deptwise" role="tab" aria-controls="pills-deptwise" aria-selected="false"> Dept.wise Patient Visit Report </a>
  </li>
 
  <!--<li class="nav-item">-->
  <!--  <a class="nav-link" id="pills-summary-tab" data-toggle="pill" href="#pills-summary" role="tab" aria-controls="pills-summary" aria-selected="false">Periodical  Summary Report </a>-->
  <!--</li>-->
 <li class="nav-item">
    <a class="nav-link" id="pills-doctor-tab" data-toggle="pill" href="#pills-doctor" role="tab" aria-controls="pills-doctor" aria-selected="false">Doctor wise Patient Visit Report</a>
  </li>
 
</ul>


    

       

<div class="tab-content" id="pills-tabContent">
 
 <div class="tab-pane fade show active  " id="pills-deptwise" role="tabpanel" aria-labelledby="pills-deptwise-tab"><br>

<div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
  <div class="row">
                <div class="col-md-12">
                    <h4>Select Criteria</h4><br>
                 
                </div>
            </div> 
<form action="#pills-deptwise-tab" href="#pills-deptwise" method="POST">
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
                <label for="">Deparment</label>
                <select name="dept" id="dept" class="form-control" required >
                    <option value="">Select Department</option>
                    <?php $dept_name=$newReport->getDept();
                    while($row=mysqli_fetch_assoc($dept_name))
                    {
                    ?>
                    <option value="<?= $row['id']?>" <?php if($dept==$row['id']) echo 'selected';?>><?= $row['dept_name']?></option>
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
                        <h5 class="ml-4">Department Wise Patient visit Report <span class="text-danger">
                            <?php
                            echo "Between ".  date('d.m.Y',strtotime($dept_from))." and ". date('d.m.Y',strtotime($dept_to));
                              ?>
                             </span></h5>
                             </div>
<!--<div class="col-md-6">-->
<!--                             <a href="print_deptwise_visit_report.php" target="_blank" class="float-right badge badge-pill badge-danger" data-toggle="tooltip" data-placement="top" title="Print Lab Report"><i class="icofont-print"></i> Print </a>-->
<!--                        </div>-->
                    </div>
                    <hr>

                    <div class="table-responsive">
                    <table id="datatable-buttons2" class="table table-hoverde table-bordered  text-center text-wrap" >
                                    <thead class="thead-nex">
                                    <tr >
                                    <tr>
                        <th ><b>Sl. No.</b></th>
                        <th ><b>UHID</b></th>
                       <th ><b>OPD No.</b></th>
                        <th ><b>Patient name</b></th>
                        <th ><b>Patient  Address</b></th>
                        <th ><b>Sex</b></th>
                        <th ><b>Age</b></th>
                        <th ><b>New/Re-Visit</b></th>
                        <th ><b>Visit Date</b></th>
                        <th ><b>Department</b></th>
                    </tr>                 
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                             <?php $ot=$newReport->getDeptDetails($dept_from,$dept_to,$dept,'');
                $sn=1;
                while($row=mysqli_fetch_assoc($ot)){
                ?>
                                            <tr>
                                                <td><?= $sn?></td>
                                                 <td><?= $row['uhid']?></td>
                                                 <td><?= $row['opd_no']?></td>
                                                 <td><?= $row['name']?></td>
                                                <td ><?= $row['address']?></td>
                                                <td ><?= $row['gender']?></td>
                                                <td ><?= $row['age']?></td>
                                                <td ><?php if($row['old_patient']==0) echo 'New'; else echo 'Re-visit';?></td>
                                                <td><?= date("d.m.Y",strtotime($row['date']))?></td>
                                                <td ><?= $row['dept_name']?></td>
                                                <!--<td ><?= $row['medic']?></td>-->
                                               
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
    <!--end of Adult Minor report tab-->
<div class="tab-pane fade  " id="pills-doctor" role="tabpanel" aria-labelledby="pills-doctor-tab"><br>

<div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
  <div class="row">
                <div class="col-md-12">
                    <h4>Select Criteria</h4><br>
                 
                </div>
            </div> 
<form action="#pills-doctor-tab" href="#pills-doctor" method="POST">
    <div class="row">
        <div class="col-md-3">
           <div class="form-group">
                  <label for="exampleInputEmail1"> From Date </label>
                  <input type="date" class="form-control"  name="doc_from" id="doc_from" required value="<?= $doc_from?>">
                  
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1"> To Date  </label>
                   <input type="date" class="form-control"  name="doc_to" id="doc_to" required value="<?= $doc_to?>">
                   
             </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="">Doctor</label>
                <select name="doctor" id="doctor" class="form-control" required >
                    <option value="">Select Doctor</option>
                    <?php $doc_name=$newReport->getDoctor();
                    while($row=mysqli_fetch_assoc($doc_name))
                    {
                    ?>
                    <option value="<?= $row['id']?>" <?php if($doctor==$row['id']) echo 'selected';?>><?= $row['name'].' '.$row['last_name']?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="">Type</label>
                <select name="type" id="type" class="form-control" required >
                    <option value="">Select type</option>
                    <option value="OPD" <?php if($_REQUEST['type']=="OPD") echo "selected";?>>OPD</option>
                    <option value="IPD" <?php if($_REQUEST['type']=="IPD") echo "selected";?>>IPD</option>
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
                        <h5 class="ml-4">Doctor Wise Patient visit Report <span class="text-danger">
                            <?php
                            echo "Between ".  date('d.m.Y',strtotime($doc_from))." and ". date('d.m.Y',strtotime($doc_to));
                              ?>
                             </span></h5>
                             </div>
<!--<div class="col-md-6">-->
<!--                             <a href="print_deptwise_visit_report.php" target="_blank" class="float-right badge badge-pill badge-danger" data-toggle="tooltip" data-placement="top" title="Print Lab Report"><i class="icofont-print"></i> Print </a>-->
<!--                        </div>-->
                    </div>
                    <hr>

                    <div class="table-responsive">
                    <table id="datatable-buttons3" class="table table-hoverde table-bordered  text-center text-wrap" >
                                    <thead class="thead-nex">
                                    <tr >
                                    <tr>
                        <th ><b>Sl. No.</b></th>
                        <?php if($_REQUEST['type']=='IP')
                        { ?>
                        <th ><b>IPD No.</b></th>
                        <?php } else { ?>
                        <th ><b>UHID</b></th>
                        <?php } ?>
                       <th ><b>OPD No.</b></th>
                        <th ><b>Patient name</b></th>
                        <th ><b>Patient  Address</b></th>
                        <th ><b>Sex</b></th>
                        <th ><b>Age</b></th>
                        <th ><b>Visit Date</b></th>
                        <th ><b>Department</b></th>
                    </tr>                 
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                             <?php $ot=$newReport->getDoctorDetails($doc_from,$doc_to,$doctor,$type);
                $sn=1;
                while($row=mysqli_fetch_assoc($ot)){
                ?>
                                            <tr>
                                                <td><?= $sn?></td>
                                                 <td><?= $row['uhid']?></td>
                                                 <td><?= $row['opd_no']?></td>
                                                 <td><?= $row['name']?></td>
                                                <td ><?= $row['address']?></td>
                                                <td ><?= $row['gender']?></td>
                                                <td ><?= $row['age']?></td>
                                                <td><?= date("d.m.Y",strtotime($row['date']))?></td>
                                                <td ><?= $row['dept_name']?></td>
                                            </tr>
                                             <?php $sn++; 
                                             $doctor_name=$row['doctor'];
                                             } ?>  
                                        </tbody>
                                       
                                            </table>   
                    </div>
                </div>
            </div>
        </div>
    </div>

         


</div>
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
<form action="#" href="#pills-home" method="POST">
<div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                            <div class="input-group mb-3">
  <div class="input-group-prepend">
   
    <div class="input-group-text">
    Registration Type: 
    </div>
  </div>
  <div class="form-control">
                                        <input type="radio"  class="ml-4"id="typeopd" name="regtype" value="old" checked="" >
                                        OPD (New &amp; Follow Up Total)
                                        <input type="radio" class="ml-4" id="typeopd" name="regtype" value="New Registration" >
                                        New Registration
                                        <input type="radio" class="ml-4" id="typeopd" name="regtype" value="Follow Up" >
                                        Follow up
                                       
                                        <input type="radio" id="typeall" class="ml-4" name="regtype" value="All" >
                                        All
                                    </div>
</div>
                                
                        </div>
                        </div>
                      

                        <div class="col-md-12">
                            <div class="form-group">
                            <div class="input-group mb-3">
  <div class="input-group-prepend">
   
    <div class="input-group-text">
    Registration Type: 
    </div>
  </div>
                                  
                                    <div class="form-control">
                                        <input type="radio" class="ml-4" id="rptype_summary" name="rptype" value="S" checked="" >Summary report
                                        <input type="radio" class="ml-4" id="rptype_detail" name="rptype" value="D">Detail Report
                                    </div>
                                </div>
                            </div> 
                        </div>

                        <div class="col-md-12">  

                        <div class="input-group mb-3">
  <div class="input-group-prepend">
  <div class="input-group-text">
      <input type="checkbox" name="date" aria-label="Checkbox for following text input mr-5">
    </div>
    <span class="input-group-text" id="basic-addon1">Date From</span>
  </div>
  <input type="text" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">
  <div class="input-group-prepend">
    <span class="input-group-text" id="basic-addon1">Date To</span>
  </div>
  <input type="text" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">
  
</div>

                           
                                   
                          
                        </div>

                        <div class="col-sm-6">
                        <div class="input-group mb-3">
  <div class="input-group-prepend">
  <div class="input-group-text">
      <input type="checkbox" name="age" aria-label="Checkbox for following text input mr-5">
    </div>
    <span class="input-group-text" id="basic-addon1">Age From</span>
  </div>
  <select class="form-control" name="agefrom" id="agefrom">

<option value="0">Below 1 year</option>




</select> 
  
  
</div>

                        </div>
                        <div class="col-sm-6">
                        <div class="input-group mb-3">
  <div class="input-group-prepend">

    <span class="input-group-text" id="basic-addon1">Age to</span>
  </div>
  <select class="form-control" name="agefrom" id="agefrom">

                                        <option value="0">Below 1 year</option>
                                      
                                        
                                       
                                        
                                    </select> 
 
</div>

                        </div>
             

                        <div class="col-sm-6">
                        <div class="input-group mb-3">
  <div class="input-group-prepend">
  <div class="input-group-text">
      <input type="checkbox" name="dept" aria-label="Checkbox for following text input mr-5">
    </div>
    <span class="input-group-text" id="basic-addon1">Department:</span>
  </div>
  <select class="form-control" name="cboDept" id="cboDept" >
                                        <option value="0">All</option><option value="72">ART PLUS</option> 
                                    </select>
  
  
</div>

                        </div>
                        <div class="col-sm-6">
                        <div class="input-group mb-3">
  <div class="input-group-prepend">
  <div class="input-group-text">
      <input type="checkbox" name="unit" aria-label="Checkbox for following text input mr-5">
    </div>
    <span class="input-group-text" id="basic-addon1">Unit:</span>
  </div>
  <select class="form-control" name="cboRoom" id="cboRoom" >
                                        <option value="0">All</option><option value="72">ART PLUS</option> 
                                    </select>
  
  
</div>

                        </div>

                        <div class="col-sm-6">
                        <div class="input-group mb-3">
  <div class="input-group-prepend">
  <div class="input-group-text">
      <input type="checkbox" name="clinic" aria-label="Checkbox for following text input mr-5">
    </div>
    <span class="input-group-text" id="basic-addon1">Clinic:</span>
  </div>
  <select id="cboClinic" name="cboClinic" class="form-control">
                                        <option value="">--Select--</option>
                                        <option value="0">All</option>
                                        <option value="0">--Select--</option><option value="1">ART CENTER </option><option value="28">CANCER TREATMENT WING</option><option value="25">CARDIOLOGY </option><option value="31">COVID 19</option><option value="30">CVTS</option><option value="2">DENTISTRY </option><option value="3">EMERGENCY MEDICINE </option><option value="33">ENDICRIONOLOGY </option><option value="4">ENT </option><option value="5">GENERAL MEDICINE </option><option value="6">GENERAL SURGERY </option><option value="32">GYNECOLOGY ONCOLOGY</option><option value="20">IMMUNISATION-VACCINATION-EPIDEMILOGY-MAL</option><option value="19">LEPROSY </option><option value="22">MAXILOFACIAL</option><option value="8">NEPHROLOGY </option><option value="26">NEUROLOGY </option><option value="7">NEURO SURGERY </option><option value="9">OBSTETRICS AND GYNECOLOGY</option><option value="10">OPHTHALMOLOGY </option><option value="11">ORTHOPEDICS </option><option value="13">PAEDIATRCIS SURGERY </option><option value="12">PAEDIATRICS </option><option value="14">PLASTIC SURGERY </option><option value="34">PMR</option><option value="23">PMR</option><option value="29">PMSSY SUPER SPECIFICITY </option><option value="15">PSYCHIATRY </option><option value="16">PULMONARY MEDICINE </option><option value="24">RADIOTHERAPY </option><option value="17">SKIN STD</option><option value="27"> SURGICAL GASTROENTEROLOGY</option><option value="21">SURGICAL ONCOLOGY </option><option value="18">UROLOGY </option>
                                    </select>
  
  
</div>

                        </div>
                   
                        <div class="col-sm-6">
                        <div class="input-group mb-3">
  <div class="input-group-prepend">
  <div class="input-group-text">
      <input type="checkbox" name="sex" aria-label="Checkbox for following text input mr-5">
    </div>
    <span class="input-group-text" id="basic-addon1">Gender :</span>
  </div>
  <select class="form-control" id="ddlgender" name="ddlgender" tabindex="13" >
                                        <option value="0">All</option>
                                        <option value="1">Male</option>
                                        <option value="2">Female</option>
                                        <option value="4">Others</option>
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
                        <h5 class="ml-4">Periodical Summary  Report  <span class="text-danger">
                            <?php if(isset($_REQUEST['op_from']) && isset($_REQUEST['op_to'])) {
                            echo "Between ".  date('d.m.Y',strtotime($_REQUEST['op_from']))." and ". date('d.m.Y',strtotime($_REQUEST['op_to']));
                             } ?>
                             </span></h5>
                             </div>
<div class="col-md-6">
                             <a href="print_periodical_summary_report.php" target="_blank" class="float-right badge badge-pill badge-danger" data-toggle="tooltip" data-placement="top" title="Print Lab Report"><i class="icofont-print"></i> Print </a>
                        </div>
                    </div>
                    <hr>

                    <div class="table-responsive">
                    <table id="datatable-buttons" class="table table-hoverde table-bordered display text-center dataTable no-footer" role="grid" aria-describedby="datatable-buttons_info">
                                    <thead class="thead-nex">
                                    <tr >
                                    <th>Sl.No.</th>
                                
                            <th>Department</th>
                            <th>Unit</th>
                            <th>Male</th>
                            <th>Female</th>
                            <th>Transgender</th>
                            <th>Total Minor Male</th>
                            <th>Total Minor Female</th>
                            <th>Total Patient</th>
                                                
                                                
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
    <!-- Datatable -->
     
   
   
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
                title: '<?= $_SESSION['college_name'].','.$_SESSION['college_location']?>\nDepartment Wise Patient visit Report  <?php
                            echo "Between ".  date('d.m.Y',strtotime($dept_from))." and ". date('d.m.Y',strtotime($dept_to));
                              ?> ',
               
                orientation: 'landscape',
                pageSize: 'LEGAL'
            }
        ]

            });
            $('#datatable-buttons3').DataTable({

                dom: 'Bfrtip',
                responsive: true,
                pageLength: 25,
                // lengthMenu: [0, 5, 10, 20, 50, 100, 200, 500],
                
                buttons: [
            
            {
                extend: 'pdfHtml5',
                title: '<?= $_SESSION['college_name'].','.$_SESSION['college_location']?>\nDoctor Wise Patient visit Report  <?php
                            echo "of $doctor_name Between ".  date('d.m.Y',strtotime($doc_from))." and ". date('d.m.Y',strtotime($doc_to));
                              ?> ',
               
                orientation: 'landscape',
                pageSize: 'LEGAL'
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