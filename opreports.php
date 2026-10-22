<?php

date_default_timezone_set("Asia/Kolkata"); 

require_once(__DIR__ . '/model/session_config.php');
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
    <title>OP Report</title>
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

  <?php include ('leftbar.php');?>

  <!-- View -->





        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <!-- row -->
        <div class="container-fluid">

        <div class="row mb-4">
          <h4 class="text-uppercase ml-3">OP Report</h4>
        </div>


        <ul class="nav nav-pills mb-3 justify-content-around" id="pills-tab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true"> &nbsp; OP Report</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="pills-deptwise-tab" data-toggle="pill" href="#pills-deptwise" role="tab" aria-controls="pills-deptwise" aria-selected="false"> &nbsp; Department wise Report</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="pills-combinedop-tab" data-toggle="pill" href="#pills-combinedop" role="tab" aria-controls="pills-combinedop" aria-selected="false">Central OP Report</a>
  </li>
  <!--  <li class="nav-item">-->
  <!--  <a class="nav-link" id="pills-Count-tab" data-toggle="pill" href="#pills-Count" role="tab" aria-controls="pills-Count" aria-selected="false">Procedure Report</a>-->
  <!--</li>-->
  <!--  <li class="nav-item">-->
    <!--<a class="nav-link" id="pills-consol-tab" data-toggle="pill" href="#pills-consol" role="tab" aria-controls="pills-consol" aria-selected="false">Procedure Wise Report</a>-->
  <!--</li>-->
  <!--<li class="nav-item">-->
  <!--  <a class="nav-link" id="pills-dept-tab" data-toggle="pill" href="#pills-dept" role="tab" aria-controls="pills-dept" aria-selected="false">Department Wise Procedure Report</a>-->
  <!--</li>-->
  

</ul>


    

       

<div class="tab-content" id="pills-tabContent">
  <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab"><br>
  <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
  <div class="row">
                <div class="col-md-12">
                    <h4>OP Report </h4><br>
                 
                </div>
            </div> 
<form action="#pills-home-tab" method="POST">
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
                        <h5 class="ml-4">OP Report <span class="text-danger">
                            <?php if(isset($_REQUEST['op_from']) && isset($_REQUEST['op_to'])) {
                            echo "Between ".  date('d.m.Y',strtotime($_REQUEST['op_from']))." and ". date('d.m.Y',strtotime($_REQUEST['op_to']));
                             } ?>
                             </span></h5>
                             </div>
<div class="col-md-6">
                             <a href="opprint.php?from=<?= $op_from?>&to=<?= $op_to?>" target="_blank" class="float-right badge badge-pill badge-danger"><i class="icofont-print"></i> Print </a>
                        </div>
                    </div>
                    <hr>

                   
                    <table id="datatable-buttons2" class="table text-wrap text-center  no-footer " role="grid" aria-describedby="datatable-buttons_info">
                                    <thead class="thead-dark">
                                    <tr  >
                                                <th>Sl NO</th>
                                                <th>Date</th>
                                                <th>UHID</th>
                                                <th>OP Reg No</th>
                                                <th>Name</th>
                                                <th>Gender</th>
                                                <th>Age</th>
                                                <th>Mobile</th>
                                                <th>Address</th>
                                                 <th>Patient Type</th>
                                                  <th>Type Comment</th>
                                                <th>Department</th>
                                                 <th>Unit</th>
                                                <th>Registered By</th> 
                                                
                                                
                                            </tr>
                                        </thead>
                                        <tbody class="text-center text-black">
                <?php $ot=$newReport->getOTDetails($op_from,$op_to);
                $sn=1;
                while($row=mysqli_fetch_assoc($ot)){
                ?>
                                            <tr>
                                                <td><?= $sn?></td>
                                                <td><?= date("d.m.Y",strtotime($row['date']))?></td>
                                                <td><?= $row['uhid']?></td>
                                                <td><?= $row['opd_no']?></td>
                                                <td><?= $row['name']?></td>
                                                <td ><?= $row['gender']?></td>
                                                <td ><?= $row['age']?></td>
                                                <td ><?= $row['mobile']?></td>
                                                <td class="text-wrap" ><?= $row['address']?></td>
                                                <td ><?= $row['patient_type']?></td>
                                                <td ><?= $row['patient_type_comment']?></td>
                                                <td ><?= $row['dept_name']?></td>
                                                 <td ><?= $row['unit']?></td>
                                                 <td ><?= $row['created_by']?></td>
                                                <!--<td ><a href="opregistration.php"><span class="badge badge-pill badge-primary"><i class="bi bi-pencil-square"></i> Edit </span> </a></td>-->
                                            
                                            </tr>
                     <?php $sn++; } ?>                       
                                            </tbody>
                                            </table>   
                   
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
                    <h4>Department wise Report </h4><br>

                </div>
            </div> 
<form action="#pills-deptwise-tab" method="POST">
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
                    

                        <h5 class="ml-4">Department Wise Report <span class="text-danger">
                        <?php if(isset($dept_from) && isset($dept_to)) {
                            echo "Between ".  date('d.m.Y',strtotime($dept_from))." and ". date('d.m.Y',strtotime($dept_to));
                             } ?>
                        </span></h5>
                   
                </div>
                    <hr>

                    
                    <table id="datatable-buttons3"  class="table text-wrap text-center">
                                    <thead class="thead-dark">
                                    <tr >
                                                <th>Sl NO</th>
                                                <th>Department Name</th>
                                                <th>No.Of New patients Visited</th>
                                                <th>No.Of Old patients Visited</th>
                                                <th>Total</th>
                                                
                                                
                                                
                                            </tr>
                                        </thead>
                                        <tbody class="text-center text-black">
                                            <?php $dept=$newReport->getDept_OP($dept_from,$dept_to);
                                            $sn=1;$ntotal=$ototal=$total=0;
                                            while ($row=mysqli_fetch_assoc($dept)){?>
                                            <tr> </div>
                                                <td><?= $sn?></td>
                                                <td><?= $row['dept_name']?></td>
                                                <td ><a class="text-primary" href="depwiseopprint.php?from=<?= $dept_from?>&to=<?= $dept_to?>&dept=<?= $row['id']?>&type=new&dept_name=<?=$row['dept_name']?>" target="_blank"><?= $row['num'] - $row['old_patient']?></a></td>
                                                <td><a class="text-primary" href="depwiseopprint.php?from=<?= $dept_from?>&to=<?= $dept_to?>&dept=<?= $row['id']?>&type=old&dept_name=<?=$row['dept_name']?>" target="_blank"><?= $row['old_patient']?></a></td>
                                                <td><a class="text-primary" href="depwiseopprint.php?from=<?= $dept_from?>&to=<?= $dept_to?>&dept=<?= $row['id']?>&type=total&dept_name=<?=$row['dept_name']?>" target="_blank"><?= $row['num']?></a></td>

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
<div class="tab-pane fade  " id="pills-combinedop" role="tabpanel" aria-labelledby="pills-combinedop-tab"><br>
<div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
  <div class="row">
                <div class="col-md-12">
                    <h4>Combined OP Report </h4><br>
                  
                </div>
            </div> 
<form action="#pills-combinedop-tab" method="POST">

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

                        <h5 class="ml-4">Combined OP Report <span class="text-danger">
                        <?php if(isset($cent_from) && isset($cent_to)) {
                            echo "Between ".  date('d.m.Y',strtotime($cent_from))." and ". date('d.m.Y',strtotime($cent_to));
                             } ?>
                        </span></h5>
                    </div>
                    <div class="col-md-6">
                             <a href="Combineprint.php?from=<?= $cent_from?>&to=<?= $cent_to?>" target="_blank" class="float-right badge badge-pill badge-light"><i class="icofont-print"></i> Print </a>
                        </div>
                        </div>
                    <hr>

                
                    <table id="datatable-buttons4" class="table text-wrap text-center">
                                    <thead class="thead-dark">
                                    <tr >
                                                <th>Sl NO</th>
                                                <th>Date</th>
                                                <th>UHID</th>
                                                <th>OP Reg.No New</th>
                                                <th>OP Reg.No Old</th>
                                                <th>Name</th>
                                                <th>Gender</th>
                                                <th>Age</th>
                                                <!--<th>Address</th>-->
                                               
                                                <th>Doctor</th>
                                                <!--<th>Unit </th>-->
                                                 <th>Department</th>
                                                
                                                <th>Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center text-black">
                                        <?php $cent=$newReport->getOTDetails($cent_from,$cent_to);
                $sn=1;
                while($row=mysqli_fetch_assoc($cent)){
                ?>
                                            <tr>
                                                <td><?= $sn?></td>
                                                <td><?= date("d.m.Y",strtotime($row['date']))?></td>
                                                <td><?= $row['uhid']?></td>
                                                <?php if($row['old_patient']==0) {?>
                                                <td><?= $row['opd_no']?></td>
                                                <td></td>

                                                <?php } else { ?>
                                                <td></td>
                                                <td><?= $row['opd_no']?></td>

                                                <?php } ?>
                                                <td><?= $row['name']?></td>
                                                <td ><?= $row['gender']?></td>
                                                <td ><?= $row['age']?></td>
                                                <!--<td ><?= $row['address']?></td>-->
                                                
                                                <td ><?= $row['doctor_name']?></td>
                                                 <!--<td ><?= $row['unit']?></td>-->
                                                 <td ><?= $row['dept_name']?></td>
                                                 <td ><?= $row['remark']?> <?php if($row['ipd_no']!='') { echo " Admitted"; } ?></td>
                                            </tr>
                     <?php $sn++; } ?>          
                                            
                                            
                                            </tbody>
                                            </table>   

                </div>
            </div>
        </div>
    </div>

    
</div>
<div class="tab-pane fade  " id="pills-Count" role="tabpanel" aria-labelledby="pills-Count-tab"><br>
<div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
  <div class="row">
                <div class="col-md-12">
                    <h4>OP Procedure Report </h4><br>
                    
                </div>
            </div> 
            <form action="#pills-Count-tab" method="POST">

            <div class="row">
        <div class="col-md-4">
           <div class="form-group">
                  <label for="exampleInputEmail1"> From Date </label>
                  <input type="date" class="form-control"  name="proce_from" id="proce_from" value="<?= $_REQUEST['proce_from']?>" required>
                  
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                   <label for="exampleInputEmail1"> To Date  </label>
                   <input type="date" class="form-control"  name="proce_to" id="proce_to" value="<?= $_REQUEST['proce_to']?>" required>
                   
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
                        <h5 class="ml-4">OP Procedure Report <span class="text-danger">
                        <?php if(isset($_REQUEST['proce_from']) && isset($_REQUEST['proce_to'])) {
                            echo "Between ".  date('d.m.Y',strtotime($_REQUEST['proce_from']))." and ". date('d.m.Y',strtotime($_REQUEST['proce_to']));
                             } ?>
                        </span></h5>
                    </div>
                    <hr>

                    <div class="table-responsive">
                    <table id="example3" class="table table-hoverde display text-center">
                                    <thead class="thead-dark">
                                    <tr >
                                                <th>Sl NO</th>
                                                <th>Procedure Name</th>
                                                <th>Number of Procedures</th>
                                                
                                                
                                                
                                                
                                            </tr>
                                        </thead>
                                        <tbody class="text-center text-black">
                                            <?php $sn=1;
                                            $data=$newReport->getProcedureReport();
                                            while($row=mysqli_fetch_assoc($data))
                                            {
                                            ?>
                                            <tr>
                                                <td><?= $sn?></td>
                                                <td><?= $row['procedure_name']?></td>
                                                <td><?= $row['val']?></td>
                                                
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
<div class="tab-pane fade  " id="pills-consol" role="tabpanel" aria-labelledby="pills-diet-tab"><br>
<div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
  <div class="row">
                <div class="col-md-12">
                    <h4>OP Procedure Wise Report</h4><br>
                   
                </div>
            </div> 
            <form action="#pills-consol-tab" method="POST">

            <div class="row">
        <div class="col-md-4">
           <div class="form-group">
                  <label for="exampleInputEmail1"> From Date </label>
                  <input type="date" class="form-control"  name="proced_from" id="proced_from" value="<?= $_REQUEST['proced_from']?>" required>
                  
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                   <label for="exampleInputEmail1"> To Date  </label>
                   <input type="date" class="form-control"  name="proced_to" id="proced_to" value="<?= $_REQUEST['proced_to']?>" required>
                   
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
                        <h5 class="ml-4">OP Procedure Wise Report  <span class="text-danger"><?php if(isset($_REQUEST['proced_from']) && isset($_REQUEST['proced_to'])) {
                            echo "Between ".  date('d.m.Y',strtotime($_REQUEST['proced_from']))." and ". date('d.m.Y',strtotime($_REQUEST['proced_to']));
                             } ?></span></h5>
                    </div>
                    <hr>

                    <div class="table-responsive">
                    <table id="example3" class="table table-hoverde display text-center">
                                    <thead class="thead-dark">
                                    <tr >
                                                <th>Sl NO</th>
                                                <th>Procedure Date</th>
                                                <th>Procedure Name</th>
                                                <th>Procedure Medicine</th>
                                                <th>OP.NO</th>
                                                <th>Patient Name</th>
                                                <th>Gender</th>
                                                <th>Age</th>
                                                <th>Address</th>
                                                <!--<th>Diagnosis</th>-->
                                                <th>Symptoms</th>
                                                
                                                
                                                
                                                
                                            </tr>
                                        </thead>
                                        <tbody class="text-center text-black">
                                            <?php $sn=1;
                                            $data=$newReport->getProcedureWise();
                                            while($row=mysqli_fetch_assoc($data))
                                            {
                                            ?>
                                            <tr>
                                                <td><?= $sn?></td>
                                                <td><?= date("d.m.Y",strtotime($row['date']))?></td>
                                                <td><?= $row['procedure_name']?></td>
                                                <td><?= $row['medicine']?></td>
                                                <td><?= $row['opd_no']?></td>
                                                <td><?= $row['name']?></td>
                                                <td><?= $row['gender']?></td>
                                                <td><?= $row['age']?></td>
                                                <td><?= $row['address']?></td>
                                                <!--<td><?= $row['diagnosis']?></td>-->
                                                <td><?= $row['symptoms']?>
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
                    <h4>Diet Register</h4><br>
                  
                </div>
            </div> 
            <form action="" method="POST">

            <div class="row">
        <div class="col-md-4">
           <div class="form-group">
                  <label for="exampleInputEmail1"> From Date </label>
                  <input type="date" class="form-control"  name="from" id="from" >
                  
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                   <label for="exampleInputEmail1"> To Date  </label>
                   <input type="date" class="form-control"  name="from" id="from" >
                   
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
                        <h5 class="ml-4">Diet Register <span class="text-danger">Between 01-02-2022 and 04-02-2022</span></h5>
                    </div>
                    <hr>

                    <div class="table-responsive">
                    <table id="example3" class="table table-hoverde display text-center">
                                    <thead class="thead-dark">
                                    <tr >
                                                <th>Sl NO</th>
                                                <th>Date</th>
                                                <th>IP No</th>
                                                <th>Patient Name</th>
                                                <th>Gender</th>
                                                <th>Age</th>
                                                <th>Address</th>
                                                <th>Diagnosis</th>
                                                <th>Diet Chart</th>
                                                
                                                
                                                
                                                
                                            </tr>
                                        </thead>
                                        <tbody class="text-center text-black">
                                            <tr>
                                                <td>01</td>
                                                <td>24.04.2020</td>
                                                <td>00222</td>
                                                <td>Krishna shindhe</td>
                                                <td>Male</td>
                                                <td>Age</td>
                                                <td>Gokak</td>
                                                <td>dara</td>
                                                <td>0</td>
                                                
                                                
                                                
                                            
                                            
                                            </tbody>
                                            </table>   
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="tab-pane fade  " id="pills-dept" role="tabpanel" aria-labelledby="pills-diet-tab"><br>
<div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
  <div class="row">
                <div class="col-md-12">
                    <h4>Department Wise Procedure Report </h4><br>
                 
                </div>
            </div> 
            <form action="" method="POST">

            <div class="row">
            <div class="col-md-3">
           <div class="form-group">
                  <label for="exampleInputEmail1"> Department </label>
                  <select class="form-control" name="kaumara" id="kaumara">
                <option value="">Kaumara BRUTYA </option>   
                
                </select>
                  
            </div>
        </div>
      
            <div class="col-md-3">
           <div class="form-group">
                  <label for="exampleInputEmail1"> IP/OP </label>
                  <select class="form-control" name="ip" id="ip">
                    <option value="">IP</option>
                  </select>
                
                  
            </div>
        </div>
        <div class="col-md-3">
           <div class="form-group">
                  <label for="exampleInputEmail1"> From Date </label>
                  <input type="date" class="form-control"  name="from" id="from" >
                  
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1"> To Date  </label>
                   <input type="date" class="form-control"  name="from" id="from" >
                   
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
                        <h5 class="ml-4">Procedure Report For Deparment KAUMARA BRUTYA <span class="text-danger">Between 01-02-2022 and 04-02-2022</span></h5>
                    </div>
                    <hr>

                    <div class="table-responsive">
                    <table id="example3" class="table table-hoverde display text-center">
                                    <thead class="thead-dark">
                                    <tr >
                                                <th>Sl NO</th>
                                                <th>Procedure Name</th>
                                                <th>Count</th>
                                                
                                                
                                                
                                                
                                                
                                            </tr>
                                        </thead>
                                        <tbody class="text-center text-black">
                                            <tr>
                                                <td>01</td>
                                                <td>matravasti</td>
                                                <td>1</td>
                                                
                                                
                                                
                                            
                                            
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
                title: 'OP Report',
               
                orientation: 'landscape',
                pageSize: 'LEGAL'
            },
            {
                extend: 'excelHtml5',
                title: 'OP Report'
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
                title: 'Department wise OP Report from <?= date("d.m.Y",strtotime($dept_from))?> to <?= date("d.m.Y",strtotime($dept_to))?>',
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
                title: 'Center OP',
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