<?php date_default_timezone_set("Asia/Kolkata"); 
require_once(__DIR__ . '/model/session_config.php');

include('model/reportClass.php');
$newReport = new report;

if(isset($_REQUEST['op_from']))
{
    $op_from=$_REQUEST['op_from'];
}
else{
        $op_from='2000-01-01';
}
if(isset($_REQUEST['op_to']))
{
    $op_to=$_REQUEST['op_to'];
}
else{
        $op_to='2049-12-01';
}
if(isset($_REQUEST['fu_to']))
{
    $fu_to=$_REQUEST['fu_to'];
}
else{
        $fu_to='2049-12-01';
}
if(isset($_REQUEST['fu_from']))
{
    $fu_from=$_REQUEST['fu_from'];
}
else{
        $fu_from='2000-01-01';
}
if(isset($_REQUEST['sw_from']))
{
    $sw_from=$_REQUEST['sw_from'];
}
else{
        $sw_from='2000-01-01';
}
if(isset($_REQUEST['sw_to']))
{
    $sw_to=$_REQUEST['sw_to'];
}
else{
        $sw_to='2049-12-01';
}

if(isset($_REQUEST['dr_from']))
{
    $dr_from=$_REQUEST['dr_from'];
}
else{
        $dr_from='2000-01-01';
}
if(isset($_REQUEST['dr_to']))
{
    $dr_to=$_REQUEST['dr_to'];
}
else{
        $dr_to='2049-12-01';
}

if(isset($_REQUEST['au_from']))
{
    $au_from=$_REQUEST['au_from'];
}
else{
        $au_from='2000-01-01';
}
if(isset($_REQUEST['au_to']))
{
    $au_to=$_REQUEST['au_to'];
}
else{
        $au_to='2049-12-01';
}

if(isset($_REQUEST['st_from']))
{
    $st_from=$_REQUEST['st_from'];
}
else{
        $st_from='2000-01-01';
}
if(isset($_REQUEST['st_to']))
{
    $st_to=$_REQUEST['st_to'];
}
else{
        $st_to='2049-12-01';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Others Report</title>
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

        <div class="row mb-4">
          <h4 class="text-uppercase ml-3">Others Report</h4>
        </div>


        <ul class="nav nav-pills mb-3 justify-content-between" id="pills-tab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="pills-anaesthesia-tab" data-toggle="pill" href="#pills-anaesthesia" role="tab" aria-controls="pills-anaesthesia" aria-selected="true">  &nbsp; Anaesthesia</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="pills-fumigations-tab" data-toggle="pill" href="#pills-fumigations" role="tab" aria-controls="pills-fumigations" aria-selected="false"> &nbsp; Fumigations</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="pills-swab-tab" data-toggle="pill" href="#pills-swab" role="tab" aria-controls="pills-swab" aria-selected="false">Swab</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="pills-pads-tab" data-toggle="pill" href="#pills-pads" role="tab" aria-controls="pills-pads" aria-selected="false">Dressing Pads</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="pills-clave-tab" data-toggle="pill" href="#pills-clave" role="tab" aria-controls="pills-clave" aria-selected="false">Auto Clave</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="pills-diet-tab" data-toggle="pill" href="#pills-diet" role="tab" aria-controls="pills-diet" aria-selected="false">Diet Register</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="pills-stock-tab" data-toggle="pill" href="#pills-stock" role="tab" aria-controls="pills-stock" aria-selected="false">Stock Register</a>
  </li>
 
</ul>


    

       

<div class="tab-content" id="pills-tabContent">
  <div class="tab-pane fade show active" id="pills-anaesthesia" role="tabpanel" aria-labelledby="pills-anaesthesia-tab"><br>
 
  <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
  <div class="row">
                <div class="col-md-12">
                    <h4>Select Criteria</h4><br>
                 
                </div>
            </div> 
<form action="#pills-anaesthesia-tab"  method="POST">
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
                        <h5 class="">Anaesthesia Report Lists <span class="text-danger">
                            <?php if(isset($_REQUEST['op_from']) && isset($_REQUEST['op_to'])) {
                            echo "Between ".  date('d.m.Y',strtotime($_REQUEST['op_from']))." and ". date('d.m.Y',strtotime($_REQUEST['op_to']));
                             } ?>
                             </span></h5>
                             </div>
<div class="col-md-6">
                             <a href="printanasthesiareport.php?from=<?= $op_from?>&to=<?= $op_to?>" target="_blank" class="float-right badge badge-pill badge-danger" data-toggle="tooltip" data-placement="top" title="Print Anasthesia Report"><i class="icofont-print"></i> Print </a>
                        </div>
                    </div>
                    <hr>

                    <div class="table-responsive">
                    <table id="datatable-buttons" class="table table-hoverde table-bordered display text-center dataTable no-footer" role="grid" aria-describedby="datatable-buttons_info">
                                    <thead class="thead-dark">
                                    <tr align="center">
              <th>SI-No</th>
              <th>Date</th>
              <th>Patient Id</th>
              <th>Name</th>
              <th>Age</th>
              <th>Diagnosis</th>
              <th>Anaesthesia Type</th>
              <th>Dose</th>
              <th>Surgeon Name</th>
            </tr> 
       
                                        </thead>
                                        <tbody class="text-center text-black">
                                            <?php $data=$newReport->getAnasthesia($op_from,$op_to);
              $sn=1;
                while($row=mysqli_fetch_assoc($data)){
                ?>
                <tr>
                    <td><?= $sn?></td>
                    <td><?= date("d.m.Y",strtotime($row['date']))?></td>
                    <td><?= $row['ip_num']?></td>
                    <td><?= $row['name']?></td>
                    <td><?= $row['age']?></td>
                    <td><?= $row['diagnosis']?></td>
                    <td><?= $row['anaesthesia_type']?></td>
                    <td><?= $row['dose']?></td>
                    <td><?= $row['surgeon_name']?></td>
                    
                </tr>
                <?php 
                $sn++; } 
                ?>
                                   
                                            </tbody>
                                            </table>   
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
<div class="tab-pane fade  " id="pills-fumigations" role="tabpanel" aria-labelledby="pills-fumigations-tab"><br>

<div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
  <div class="row">
                <div class="col-md-12">
                    <h4>Select Criteria</h4><br>
                 
                </div>
            </div> 
<form action="#pills-fumigations-tab" method="POST">
    <div class="row">
        <div class="col-md-4">
           <div class="form-group">
                  <label for="exampleInputEmail1"> From Date </label>
                  <input type="date" class="form-control"  name="fu_from" id="fu_from" required value="<?= $_REQUEST['fu_from']?>">
                  
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                   <label for="exampleInputEmail1"> To Date  </label>
                   <input type="date" class="form-control"  name="fu_to" id="fu_to" required value="<?= $_REQUEST['fu_to']?>">
                   
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
                        <h5 class="">Fumigation Report Lists <span class="text-danger">
                            <?php if(isset($_REQUEST['fu_from']) && isset($_REQUEST['fu_to'])) {
                            echo "Between ".  date('d.m.Y',strtotime($_REQUEST['fu_from']))." and ". date('d.m.Y',strtotime($_REQUEST['fu_to']));
                             } ?>
                             </span></h5>
                             </div>
<div class="col-md-6">
                             <a href="printfumigationreport.php?from=<?= $fu_from?>&to=<?= $fu_to?>" target="_blank" class="float-right badge badge-pill badge-danger" data-toggle="tooltip" data-placement="top" title="Print Fumigation Report"><i class="icofont-print"></i> Print </a>
                        </div>
                    </div>
                    <hr>

                    <div class="table-responsive">
                    <table id="datatable-buttons" class="table table-hoverde table-bordered display text-center dataTable no-footer" role="grid" aria-describedby="datatable-buttons_info">
                                    <thead class="thead-dark">
                                    <tr align="center">
              <th>SI-No</th>
              <th>Date</th>
             
              <th>Description</th>
        
            </tr> 
       
                                        </thead>
                                        <tbody class="text-center text-black">
                <?php 
                $data=$newReport->getFumigation($fu_from,$fu_to); 
                $sn=1;
                while($row=mysqli_fetch_array($data)){
                ?>
                <tr>
                    <td><?= $sn?></td>
                    <td><?= date("d.m.Y",strtotime($row[1]))?></td>
                    <td><?= $row[2]?></td>
                    
                </tr>
                <?php 
                $sn++; } 
                ?>
                                   
                                            </tbody>
                                            </table>   
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
<div class="tab-pane fade  " id="pills-swab" role="tabpanel" aria-labelledby="pills-swab-tab"><br>
<div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
  <div class="row">
                <div class="col-md-12">
                    <h4>Select Criteria</h4><br>
                 
                </div>
            </div> 
<form action="#pills-swab-tab" method="POST">
    <div class="row">
        <div class="col-md-4">
           <div class="form-group">
                  <label for="exampleInputEmail1"> From Date </label>
                  <input type="date" class="form-control"  name="sw_from" id="sw_from" required value="<?= $_REQUEST['sw_from']?>">
                  
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                   <label for="exampleInputEmail1"> To Date  </label>
                   <input type="date" class="form-control"  name="sw_to" id="sw_to" required value="<?= $_REQUEST['sw_to']?>">
                   
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
                        <h5 class="">Swab Report Lists <span class="text-danger">
                            <?php if(isset($_REQUEST['sw_from']) && isset($_REQUEST['sw_to'])) {
                            echo "Between ".  date('d.m.Y',strtotime($_REQUEST['sw_from']))." and ". date('d.m.Y',strtotime($_REQUEST['sw_to']));
                             } ?>
                             </span></h5>
                             </div>
<div class="col-md-6">
                             <a href="printswabreport.php?from=<?= $sw_from?>&to=<?= $sw_to?>" target="_blank" class="float-right badge badge-pill badge-danger" data-toggle="tooltip" data-placement="top" title="Print Swab Report"><i class="icofont-print"></i> Print </a>
                        </div>
                    </div>
                    <hr>

                    <div class="table-responsive">
                    <table id="datatable-buttons" class="table table-hoverde table-bordered display text-center dataTable no-footer" role="grid" aria-describedby="datatable-buttons_info">
                                    <thead class="thead-dark">
                                    <tr align="center">
              <th>SI-No</th>
              <th>Date</th>
              <th>Prepared Swab</th>
              <th>Used Swab</th>
              <th>Balance</th>
        
            </tr> 
       
                                        </thead>
                                        <tbody class="text-center text-black">
                <?php $swab=$newReport->getSwab($sw_from,$sw_to);
                $sn=1;
                
                while($row=mysqli_fetch_assoc($swab)) {
                ?>
                <tr>
                    <td><?= $sn?></td>
                    <td><?= date("d.m.Y",strtotime($row['date']))?></td>
                    <td><?= $row['swab_prepared']?></td>
                    <td><?= $row['swab_used']?></td>
                    <td><?= $row['swab_balance']?></td>
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
<div class="tab-pane fade  " id="pills-pads" role="tabpanel" aria-labelledby="pills-pads-tab"><br>
<div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
  <div class="row">
                <div class="col-md-12">
                    <h4>Select Criteria</h4><br>
                 
                </div>
            </div> 
<form action="#pills-pads-tab"  method="POST">
    <div class="row">
        <div class="col-md-4">
           <div class="form-group">
                  <label for="exampleInputEmail1"> From Date </label>
                  <input type="date" class="form-control"  name="dr_from" id="dr_from" required value="<?= $_REQUEST['dr_from']?>">
                  
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                   <label for="exampleInputEmail1"> To Date  </label>
                   <input type="date" class="form-control"  name="dr_to" id="dr_to" required value="<?= $_REQUEST['dr_to']?>">
                   
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
                        <h5 class="">Dressing Pad Report Lists <span class="text-danger">
                            <?php if(isset($_REQUEST['dr_from']) && isset($_REQUEST['dr_to'])) {
                            echo "Between ".  date('d.m.Y',strtotime($_REQUEST['dr_from']))." and ". date('d.m.Y',strtotime($_REQUEST['dr_to']));
                             } ?>
                             </span></h5>
                             </div>
<div class="col-md-6">
                             <a href="printdressingpadreports.php?from=<?= $dr_from?>&to=<?= $dr_to?>" target="_blank" class="float-right badge badge-pill badge-danger" data-toggle="tooltip" data-placement="top" title="Print Dessing Pad Reports"><i class="icofont-print"></i> Print </a>
                        </div>
                    </div>
                    <hr>

                    <div class="table-responsive">
                    <table id="datatable-buttons" class="table table-hoverde table-bordered display text-center dataTable no-footer" role="grid" aria-describedby="datatable-buttons_info">
                                    <thead class="thead-dark">
                                    <tr align="center">
              <th>SI-No</th>
              <th>Date</th>
              <th>Prepared Pads</th>
              <th>Used Pads</th>
              <th>Balance</th>
        
            </tr> 
       
                                        </thead>
                                        <tbody class="text-center text-black">
                <?php $pad=$newReport->getPad($dr_from,$dr_to);
                $sn=1;
                
                while($row=mysqli_fetch_assoc($pad)) {
                ?>
                <tr>
                    <td><?= $sn?></td>
                    <td><?= date("d.m.Y",strtotime($row['date']))?></td>
                    <td><?= $row['pad_prepared']?></td>
                    <td><?= $row['pad_used']?></td>
                    <td><?= $row['pad_balance']?></td>
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
<div class="tab-pane fade  " id="pills-clave" role="tabpanel" aria-labelledby="pills-clave-tab"><br>


<div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
  <div class="row">
                <div class="col-md-12">
                    <h4>Select Criteria</h4><br>
                 
                </div>
            </div> 
<form action="#pills-clave-tab"  method="POST">
    <div class="row">
        <div class="col-md-4">
           <div class="form-group">
                  <label for="exampleInputEmail1"> From Date </label>
                  <input type="date" class="form-control"  name="au_from" id="au_from" required value="<?= $_REQUEST['au_from']?>">
                  
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                   <label for="exampleInputEmail1"> To Date  </label>
                   <input type="date" class="form-control"  name="au_to" id="au_to" required value="<?= $_REQUEST['au_to']?>">
                   
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
                        <h5 class="">Auto Clave Report Lists <span class="text-danger">
                            <?php if(isset($_REQUEST['au_from']) && isset($_REQUEST['au_to'])) {
                            echo "Between ".  date('d.m.Y',strtotime($_REQUEST['au_from']))." and ". date('d.m.Y',strtotime($_REQUEST['au_to']));
                             } ?>
                             </span></h5>
                             </div>
<div class="col-md-6">
                             <a href="printautoclavereport.php?from=<?= $au_from?>&to=<?= $au_to?>" target="_blank" class="float-right badge badge-pill badge-danger" data-toggle="tooltip" data-placement="top" title="Print Auto Clave Report"><i class="icofont-print"></i> Print </a>
                        </div>
                    </div>
                    <hr>

                    <div class="table-responsive">
                    <table id="datatable-buttons" class="table table-hoverde table-bordered display text-center dataTable no-footer" role="grid" aria-describedby="datatable-buttons_info">
                                    <thead class="thead-dark">
                                    <tr align="center">
              <th>SI-No</th>
              <th>Date</th>
             
              <th>Description</th>
        
            </tr> 
       
                                        </thead>
                                        <tbody class="text-center text-black">
               <?php 
                $data=$newReport->getClave($au_from,$au_to); 
                $sn=1;
                while($row=mysqli_fetch_array($data)){
                ?>
                <tr>
                    <td><?= $sn?></td>
                    <td><?= date("d.m.Y",strtotime($row[1]))?></td>
                    <td><?= $row[2]?></td>
                    
                </tr>
                <?php 
                $sn++; } 
                ?>
                                   
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
                    <h4>Select Criteria</h4><br>
                 
                </div>
            </div> 
<form action="#" href="#pills-home" method="POST">
    <div class="row">
        <div class="col-md-4">
           <div class="form-group">
                  <label for="exampleInputEmail1"> From Date </label>
                  <input type="date" class="form-control"  name="di_from" id="di_from" required value="<?= $_REQUEST['di_from']?>">
                  
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                   <label for="exampleInputEmail1"> To Date  </label>
                   <input type="date" class="form-control"  name="di_to" id="di_to" required value="<?= $_REQUEST['di_to']?>">
                   
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
                        <h5 class="">Diet Chat Report Lists <span class="text-danger">
                            <?php if(isset($_REQUEST['di_from']) && isset($_REQUEST['di_to'])) {
                            echo "Between ".  date('d.m.Y',strtotime($_REQUEST['di_from']))." and ". date('d.m.Y',strtotime($_REQUEST['di_to']));
                             } ?>
                             </span></h5>
                             </div>
<div class="col-md-6">
                             <a href="printdietchatreport.php?from=<?= $di_from?>&to=<?= $di_to?>" target="_blank" class="float-right badge badge-pill badge-danger" data-toggle="tooltip" data-placement="top" title="Print Diet Report"><i class="icofont-print"></i> Print </a>
                        </div>
                    </div>
                    <hr>

                    <div class="table-responsive">
                    <table id="datatable-buttons" class="table table-hoverde table-bordered display text-center dataTable no-footer" role="grid" aria-describedby="datatable-buttons_info">
                                    <thead class="thead-dark">
                                    <tr align="center">
              <th>SI-No</th>
              <th>Date</th>
             
              <th>Name of The Patient</th>
              <th>Age</th>
              <th>Sex</th>
              <th>Address</th>
              <th>IP.NO</th>
              <th>Diagnosis</th>
              <th>Diet Chart</th>
        
            </tr> 
       
                                        </thead>
                                        <tbody class="text-center text-black">
                <tr>
                    <td></td>
                </tr>
                                   
                                            </tbody>
                                            </table>   
                    </div>
                </div>
            </div>
        </div>
    </div>



</div>


<div class="tab-pane fade  " id="pills-stock" role="tabpanel" aria-labelledby="pills-stock-tab"><br>

<div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
  <div class="row">
                <div class="col-md-12">
                    <h4>Select Criteria</h4><br>
                 
                </div>
            </div> 
<form action="#pills-stock-tab" method="POST">
    <div class="row">
        <div class="col-md-4">
           <div class="form-group">
                  <label for="exampleInputEmail1"> From Date </label>
                  <input type="date" class="form-control"  name="st_from" id="st_from" required value="<?= $_REQUEST['st_from']?>">
                  
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                   <label for="exampleInputEmail1"> To Date  </label>
                   <input type="date" class="form-control"  name="st_to" id="st_to" required value="<?= $_REQUEST['st_to']?>">
                   
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
                        <h5 class="ml-4">Stocks Report Lists <span class="text-danger">
                            <?php if(isset($_REQUEST['st_from']) && isset($_REQUEST['st_to'])) {
                            echo "Between ".  date('d.m.Y',strtotime($_REQUEST['st_from']))." and ". date('d.m.Y',strtotime($_REQUEST['st_to']));
                             } ?>
                             </span></h5>
                             </div>
<div class="col-md-6">
                             <a href="printstocksreport.php?from=<?= $st_from?>&to=<?= $st_to?>" target="_blank" class="float-right badge badge-pill badge-danger" data-toggle="tooltip" data-placement="top" title="Print Stocks Report"><i class="icofont-print"></i> Print </a>
                        </div>
                    </div>
                    <hr>

                    <div class="table-responsive">
                    <table id="datatable-buttons" class="table table-hoverde table-bordered display text-center dataTable no-footer" role="grid" aria-describedby="datatable-buttons_info">
                                    <thead class="thead-dark">
                                    <tr align="center">
              <th>SI-No</th>
              <th>Date</th>
              <th>Prepared Sutra</th>
              <th>Used Sutra</th>
              <th>Balance</th>
        
            </tr> 
       
                                        </thead>
                                        <tbody class="text-center text-black">
                 <?php $stock=$newReport->getStock($st_from,$st_to);
                $sn=1;
                
                while($row=mysqli_fetch_assoc($stock)) {
                ?>
                <tr>
                    <td><?= $sn?></td>
                    <td><?= date("d.m.Y",strtotime($row['date']))?></td>
                    <td><?= $row['sutra_prepared']?></td>
                    <td><?= $row['sutra_used']?></td>
                    <td><?= $row['sutra_balance']?></td>
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