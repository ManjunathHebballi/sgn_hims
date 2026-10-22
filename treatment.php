<?php 
require_once(__DIR__ . '/model/session_config.php');
include('model/treatmentClass.php');

$newTreat=new addTreat;
$patient= $newTreat->getIpPatient($_REQUEST['id']);
$dt=mysqli_fetch_assoc($patient);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>IP Treatment</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
   
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">

    <link href="../../assets/plugins/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
    <!-- DataTables -->
    <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/datatables.net-select-bs4/css//select.bootstrap4.min.css" rel="stylesheet" type="text/css" />

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">


</head>

<style>
    .col-md-4,.col-md-6{

        margin-bottom:20px;
    }
     .bootstrap-select .btn-light{
    color:black!important
    }
    
    .bootstrap-select .btn-light {
        background-color:white;
    }
     .bootstrap-select .btn-light:hover {
        background-color:white;
    }

    /* Keep round-assessment service dropdown readable for long names */
    #round_service + .bootstrap-select {
        width: 100% !important;
    }

   
    .dropdown-menu {
        max-width: 80%;
    }
    #round_service + .bootstrap-select .dropdown-menu .inner li a,
    #round_service + .bootstrap-select .dropdown-menu .inner li a span.text,
    #round_service + .bootstrap-select .dropdown-menu .dropdown-item {
        white-space: normal !important;
        overflow-wrap: anywhere;
        word-break: break-word;
        line-height: 1.25;
    }

    #omedicine + .bootstrap-select .dropdown-menu .text-xs,
    #omedicine + .bootstrap-select .filter-option-inner-inner .text-xs {
        font-size: 11px;
        line-height: 1.2;
        margin-top: 2px;
    }

    #omedicine + .bootstrap-select .dropdown-menu .text-gray-400,
    #omedicine + .bootstrap-select .filter-option-inner-inner .text-gray-400 {
        color: #8c97a5;
    }
</style>

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

        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <!-- row -->




<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <h4 class="text-uppercase">IP DETAILS</h4>
        </div>
    </div>



<div class="container-fluid">

<div class="tab-content" id="v-pills-tabContent">



    <div class="row">

   
<div class="col-md-12">
    <div class="card">
        <div class="card-body">
    

<?php if($_SESSION['role']!='Operation Theater') { ?>
     
  <h4 class="text-danger mb-4 text-uppercase"> Medication <span class="text-black"> <?= $dt['name'].' ( '.$_REQUEST['id'].' ) '?></span> </h4>
 
 
 <div class="row p-3">

                                    <div class="col-md-12">
<h5 class="text-primary  text-uppercase  mb-3">ORAL MEDICINES</h5>


      </div>

      <div class="col-sm-3">
                                                  <div class="form-group">
                                                      <label for="">Date</label>
                                                      <input type="date" name="odate" id="odate" value="<?= date("Y-m-d")?>" class="form-control">
                                                  </div>

                                              </div>
                                              </div>
                                              <div class="row px-3">

                                            

                                              <div class="col-sm-6">
                                                  <div class="form-group">
                                                      <label for="">Medicine Name</label>
                                                     <select id="omedicine" class="form-control selectpicker " data-live-search="true">
                                                         <option value="">Select Medicine</option>
                                                         <option value="manual_other">Other</option>
                                                     </select>
                                                     <input type="hidden" id="omedicine_name">
                                                     </div>
                                                     </div>

                                    <div class="col-md-6" id="manual_medicine_group" style="display:none;">
                                        <div class="form-group">
                                            <label for="manual_medicine">Enter Medicine Manually</label>
                                            <input type="text" class="form-control" id="manual_medicine" placeholder="Type medicine name">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Quantity</label>
                                            <input type="text" class="form-control" id="qty1" placeholder="e.g. 10" required>
                                        </div>
                                    </div>
                                   
                                    <div class="col-md-12 ">
                                        <button type="button" class="btn btn-primary float-right" onclick="addMedicine()">Add Medicine</button>
                                    </div>  
      </div>

      <div class="row p-3">
      <div class="col-md-12">
                                    <h6 class="text-info mb-3">Medicines to Submit</h6>
                                    <div class="table-responsive">
                                                                    <table class="table table-bordered " id="pendingMedicinesTable">
                                                                        <thead class="thead-primary text-center ">
                                                                            <tr>
                                                                                <th class="text-center" width="50px">Sl.No.</th>
                                                                                <th>Date</th>
                                                                                <th>Medicine Name</th>
                                                                                <th>Quantity</th>
                                                                                <th>Action</th>
                                                                            </tr>
                                                                        </thead>

                                                                        <tbody class="text-black text-center" id="pendingMedicinesBody">
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                    <div class="text-right">
                                        <form action="controller/processTreatment.php" method="post" id="medicineForm">
                                            <input type="hidden" name="pid" value="<?= $_REQUEST['id']?>">
                                            <input type="hidden" name="rx_id" value="<?= $dt['rx_id']?>">
                                            <input type="hidden" name="patient_type" value="<?= $dt['patient_type']?>">
                                            <input type="hidden" name="medicines_data" id="medicines_data" value="">
                                            <button type="submit" name="submit" value="medic_batch" class="btn btn-success">Submit All Medicines</button>
                                        </form>
                                    </div>
      </div>
      </div>

 <div class="row p-3">
      <div class="col-md-12">
                                    <h6 class="text-success mb-3">Already Submitted Medicines</h6>
                                    <div class="table-responsive">
                                                                    <table class="table table-bordered " >
                                                                        <thead class="thead-dark  text-center ">
                                                                            <tr>
                                                                                <th class="text-center" width="50px">Sl.No.</th>
                                                                                <th>Date</th>
                                                                                <th>Medicine Name</th>
                                                                                <th>Quantity</th>
                                                                                <th>DC Number</th>
                                                                                <th>Created By</th>
                                                                                <th>status</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody class="text-black text-center">
                                                                            <?php $md=$newTreat->getPatienMedic($_REQUEST['id']);
                                                                            $sn=0;

                                                                            while($row=mysqli_fetch_assoc($md)) {
                                                                                $sn++;
                                                                                ?>
                                                                            <tr>
                                                                            <td><?= $sn; ?></td>
                                                                               <td><?= date("d.m.Y",strtotime($row['date']))?></td>
                                                                           
                                                                               <td><?= $row['product_name']?></td>
                                                                               <td><?= $row['qty']?></td>
                                                                                 <td><?= $row['rx_id'] ? $row['dc_no'] : 'Outside Medicine'?></td>
                                                                                <td><?= $row['created_by']?></td>
                                                                                 <td class="<?= isset($row['status']) && $row['status'] === 'REJECTED' ? 'text-danger' : (isset($row['status']) && $row['status'] === 'DELIVERED' ? 'text-success' : '') ?> "><?= isset($row['status']) ? $row['status'] : 'Pending' ?></td>
                                                                            </tr>
                                                    
                                                                        <?php  } ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                    </div>

                                  <div class="col-md-12 mt-4">
                                        <h5 class="text-primary text-uppercase mb-3">ROUND ASSESSMENT</h5>

                                        <?php if(isset($_REQUEST['round_success']) && $_REQUEST['round_success']==1) { ?>
                                            <div class="alert alert-success">Round assessment saved successfully.</div>
                                        <?php } ?>
                                        <?php if(isset($_REQUEST['round_error']) && $_REQUEST['round_error']==1) { ?>
                                            <div class="alert alert-danger">Unable to save round assessment. Please add valid entries and try again.</div>
                                        <?php } ?>

                                        <form action="controller/processTreatment.php" method="post" id="roundAssessmentForm">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="round_datetime">Date & Time</label>
                                                        <input type="datetime-local" id="round_datetime" class="form-control" value="<?= date("Y-m-d\\TH:i") ?>" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="round_service">Service</label>
                                                        <select id="round_service" class="form-control selectpicker" data-live-search="true" >
                                                            <option value="">Select Service</option>
                                                            <?php $roundServiceList=$newTreat->getRoundServiceList();
                                                            while($serviceRow=mysqli_fetch_assoc($roundServiceList)) { ?>
                                                                <option value="<?= $serviceRow['id'] ?>" data-doctor-share="<?= $serviceRow['is_doctor'] ?>"><?= $serviceRow['name'] ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3" id="round_doctor_col" style="display:none;">
                                                    
                                                            <div class="form-group">
                                                                <label for="round_doctor">Doctor</label>
                                                                <select id="round_doctor" class="form-control selectpicker" data-live-search="true">
                                                                    <option value="">Select Doctor</option>
                                                                    <?php $roundDoctors=$newTreat->getdoctor(); while($docRow=mysqli_fetch_assoc($roundDoctors)) { ?>
                                                                        <option value="<?= $docRow['id'] ?>"><?= htmlspecialchars($docRow['name'].' '.$docRow['last_name']) ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                      
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="round_qty">Qty</label>
                                                        <input type="number" step="0.01" min="0.01" id="round_qty" class="form-control" placeholder="e.g. 1" >
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-12">
                                                    <button type="button" class="btn btn-primary float-right" onclick="addRoundAssessment()">Add Round Assessment</button>
                                                </div>
                                            </div>

                                            <div class="table-responsive mt-3">
                                                <table class="table table-bordered" id="pendingRoundTable">
                                                    <thead class="thead-primary text-center">
                                                        <tr>
                                                            <th class="text-center" width="50px">Sl.No.</th>
                                                            <th>Date & Time</th>
                                                            <th>Service</th>
                                                            <th>Qty</th>
                                                            <th>Doctor</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="text-center" id="pendingRoundBody"></tbody>
                                                </table>
                                            </div>

                                            <div class="text-right">
                                                <input type="hidden" name="pid" value="<?= $_REQUEST['id']?>">
                                                <input type="hidden" name="round_data" id="round_data" value="">
                                                <button type="submit" name="submit" value="round_assessment" class="btn btn-success">Submit Round Assessment</button>
                                            </div>
                                        </form>

                                        <h6 class="text-success mt-4 mb-3">Already Submitted Round Assessments</h6>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead class="thead-dark text-center">
                                                    <tr>
                                                        <th class="text-center" width="50px">Sl.No.</th>
                                                        <th>Date & Time</th>
                                                        <th>Service</th>
                                                        <th>Qty</th>
                                                        <th>Doctor</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="text-black text-center">
                                                    <?php $roundAssessments=$newTreat->getRoundAssessments($_REQUEST['id']);
                                                    $rsn=1;
                                                    while($roundRow=mysqli_fetch_assoc($roundAssessments)) { ?>
                                                        <tr>
                                                            <td><?= $rsn ?></td>
                                                            <td><?= date("d.m.Y h:i A",strtotime($roundRow['round_datetime'])) ?></td>
                                                            <td><?= $roundRow['service_name'] ? $roundRow['service_name'] : '-' ?></td>
                                                            <td><?= $roundRow['qty'] ?></td>
                                                            <td><?= isset($roundRow['doctor_name']) && $roundRow['doctor_name'] ? $roundRow['doctor_name'] : '-' ?></td>
                                                        </tr>
                                                    <?php $rsn++; } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                  </div>
      </div>
 <form action="controller/processTreatment.php" method="post">

<div class="row p-3">


<div class="col-md-12">
<h5 class="text-primary  text-uppercase  mb-3">IP DAILY ENTRY</h5>

      </div>

      <div class="col-sm-3">
                                                  <div class="form-group">
                                                      <label for="">Date</label>
                                                      <input type="datetime-local" name="odate" id="odate" readonly value="<?= date("Y-m-d\TH:i:s") ?>" class="form-control">
                                                  </div>

                                              </div>
                                              <div class="col-sm-3">
                                                  <div class="form-group">
                                                      <label for="">Pulse</label>
                                                      <input type="text" name="pulse" id="pulse" class="form-control">
                                                  </div>

                                              </div>
                                              <div class="col-sm-3">
                                                  <div class="form-group">
                                                      <label for="">BP</label>
                                                      <input type="text" name="bp" id="bp" class="form-control">
                                                  </div>

                                              </div>
                                              
                                              <div class="col-sm-3">
                                                  <div class="form-group">
                                                      <label for="">Temp</label>
                                                      <input type="text" name="temp" id="temp" class="form-control">
                                                  </div>

                                              </div>
                                              <div class="col-sm-3">
                                                  <div class="form-group">
                                                      <label for="">SPO2</label>
                                                      <input type="text" name="spo2" id="spo2" class="form-control">
                                                  </div>

                                              </div>
                        <div class="col-sm-3">
                                                  <div class="form-group">
                                                      <label for="">RBS</label>
                                                      <input type="text" name="rbs" id="rbs" class="form-control">
                                                  </div>
                                                    </div>                                         
                                            <div class="col-sm-3">

                                                  <div class="form-group">
                                                      <label for="">URINE/OUTPUT</label>
                                                      <input type="text" name="urine_output" id="urine_output" class="form-control">
                                                  </div>

                                              </div>
                                              <div class="col-sm-3">
                                            <div class="form-group">
                                                      <label for="">Respiratory Rate</label>
                                                      <input type="text" name="respiratory_rate" id="respiratory_rate" class="form-control">
                                                  </div>

                                              </div>
                                              <div class="col-md-3">
                                                  <div class="form-group">
                                                      <label for="">Diet</label>
                                                      <select name="diet" id="diet" class="form-control" >
                                                          <option value="">Select Diet</option>
                                                          <option value="NBM">NBM</option>
                                                          <option value="Soft Diet">Soft Diet</option>
                                                          <option value="Full Diet">Full Diet</option>
                                                          <option value="RT Feeding">RT Feeding</option>
                                                          <option value="Crush Diet">Crush Diet</option>
                                                          <option value="Extra Salt Diet">Extra Salt Diet</option>
                                                      </select>
                                                  </div>
                                              </div>
                                              <div class="col-md-3">
                                                  <div class="form-group">
                                                      <label for="">Medicine</label>
                                                      <select name="medicin" id="medicin" class="form-control" required>
                                                          <option value="">Select</option>
                                                          <option value="Yes">Yes</option>
                                                          <option value="No">No</option>
                                                        
                                                      </select>
                                                  </div>
                                              </div>

                                              <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">Progress</label>
                                                    <textarea name="progress" id="progress" cols="1" rows="1" class="form-control"></textarea>
                                                </div>
                                              </div>
                                           
                                              <div class="col-md-12">
                                                     <button class="btn btn-success float-right" name="submit" value="daily"> Add</button>
                                                    <input type="hidden" name="pid" value="<?= $_REQUEST['id']?>">
                                                 </div>
                                                 </form>

</div>
<div class="row mt-4">
                                    <div class="col-md-12">
                                    <div class="table-responsive">
                                                                    <table class="table table-bordered " >
                                                                        <thead class="thead-dark  text-center ">
                                                                            <tr>
                                                                                <th class="text-center" width="50px">Sl.No.</th>
                                                                                <th>Date</th>
                                                                                <th>Pulse</th>
                                                                                
                                                                                <th >BP</th>
                                                                                <th>Temp</th>
                                                                              
                                                                                <th >SPO2</th>
                                                                                <th >RBS</th>
                                                                                <th >Urine/Output</th>
                                                                                <th >Respiratory Rate</th>
                                                                                <th>Diet</th>
                                                                                <th>Medicines</th>
                                                                                <th>Progress</th>
                                                                                <!-- <th>Entered By</th> -->
                                                                                <!-- <th >Action</th> -->
                                                                                
                                                                            </tr>
                                                                        </thead>

                                                                        <tbody class="text-black text-center    ">
                                                                            <?php $daily=$newTreat->getDaily($_REQUEST['id']);
                                                                            $sn=1;
                                                                            while($row=mysqli_fetch_assoc($daily)){?>
                                                                            <tr>
                                                                               <td><?= $sn?></td>
                                                                               <td><?= $row['date']?></td>
                                                                               <td><?= $row['pulse']?></td>                                                                           
                                                                               <td><?= $row['temp']?></td>
                                                                               <td><?= $row['bp']?></td>
                                                                               <td><?= $row['spo2']?></td>
                                                                               <td><?= $row['rbs']?></td>
                                                                               <td><?= $row['urine_output']?></td>
                                                                               <td><?= $row['respiratory_rate']?></td>
                                                                               <td><?= $row['diet']?></td>
                                                                               <td><?= $row['medicine']?></td>
                                                                               <td><?= $row['progress']?></td>
                                                                              
                                                                                
                                                                                
                                                                                <!-- <td>
                                                                             
                                                                                <a href="" class="badge badge-pill badge-danger  " data-toggle="tooltip" title="Delete Entry"><i class=" ri-close-line"></i></a>
                                                                                </td> -->
                                                                            </tr>
                                                                        <?php $sn++;} ?>
                                                         
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                    </div>
                                    
                                </div>

 <hr>
 <?php } ?>
 <form action="controller/processTreatment.php" method="post">
 <div class="row p-3">
     
                                    <div class="col-md-12">
<h5 class="text-primary  text-uppercase  mb-3">OPERATION THEATER <?php if($_SESSION['role']=='Operation Theater') { ?><span class="text-black"> <?= $dt['name'].' ( '.$_REQUEST['id'].' ) '?></span><?php } ?> </h5>

      </div>

      <div class="col-sm-3">
                                                  <div class="form-group">
                                                      <label for="" class="text-black">Date</label>
                                                      <input type="datetime-local" name="otdate" id="otdate" class="form-control" value="<?= date("Y-m-d\TH:i:s") ?>">
                                                  </div>

                                              </div>
                                             <div class="col-md-3">
                                                  <div class="form-group">
                                                      <label for="">Doctor</label>
                                                      <select name="doctor" id="doctor" class="form-control selectpicker" data-live-search="true" required>
                                                         <option value="">Select Doctor</option>
                                                         <?php 
                                                      $doctor=$newTreat->getdoctor();
                                                      while($row=mysqli_fetch_assoc($doctor)){
                                                      ?>
                                                      <option value="<?= $row['name'].' '.$row['last_name']?>"><?= $row['name'].' '.$row['last_name']?></option>
                                                      <?php } ?>
                                                      </select>
                                                  </div>
                                              </div>
                                              <div class="col-md-3">
                                                  <div class="form-group">
                                                      <label for="">OT Type</label>
                                                      <select name="ottype" id="ottype" class="form-control" onchange="getDetailOT()" required>
                                                         <option value="">Select OT Type</option>
                                                      <?php 
                                                      $ot_type=$newTreat->getOT_type();
                                                      while($row=mysqli_fetch_assoc($ot_type)){
                                                      ?>
                                                      <option value="<?= $row['id']?>"><?= $row['ot_name']?></option>
                                                      <?php } ?>
                                                      </select>
                                                  </div>
                                              </div>
                                             

                                              <div class="col-md-3" id="major_ot" style="display: none">
                                                  <div class="form-group">
                                                      <label for=""> Major Operative Procedures </label>
                                                      <select name="major_ot" id="ottype" class="form-control">
                                                        
                                                      <option value="">Select Proceduer</option>
                                                      <?php 
                                                      $major_ot=$newTreat->getOT_details(1);
                                                      while($row=mysqli_fetch_assoc($major_ot)){
                                                      ?>
                                                      <option value="<?= $row['id']?>"><?= $row['ot_procedure']?></option>
                                                      <?php } ?>
                                                       
                                                        </select>
                                                    </div>
                                                </div>
                                              <div class="col-md-3" id="minor_ot" style="display: none">
                                                  <div class="form-group">
                                                      <label for=""> Minor Operative Procedures </label>
                                                      <select name="minor_ot" id="ottype" class="form-control" >
                                                          <option value="">Select Proceduer</option>
                                                         
                                                          <?php 
                                                      $major_ot=$newTreat->getOT_details(2);
                                                      while($row=mysqli_fetch_assoc($major_ot)){
                                                      ?>
                                                      <option value="<?= $row['id']?>"><?= $row['ot_procedure']?></option>
                                                      <?php } ?>
        

                                                    
                                                      </select>
                                                  </div>
                                              </div>
                                              

                                              <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">Comments</label>
                                                    <textarea name="comments" id="comments" cols="1" rows="1" class="form-control"></textarea>
                                                </div>
                                              </div>
                                              

                                              <div class="col-md-12 mb-3">
                                                <div class="form-group">
                                                    
                                                    <button class="btn btn-success float-right" name="submit" value="ot_reg"> Add</button>
                                                   </div>
                                                    <input type="hidden" name="pid" value="<?= $_REQUEST['id']?>">
                                              </div>
</form>
                                              <div class="col-md-12">
                                    <div class="table-responsive">
                                                                    <table class="table table-bordered " >
                                                                        <thead class="thead-dark  text-center ">
                                                                            <tr>
                                                                                <th class="text-center" width="50px">Sl.No.</th>
                                                                                <th>Date</th>
                                                                                <th>Doctor</th>
                                                                                <th>Ot Type</th>
                                                                                <th>Procedure</th>
                                                                                <th>
                                                                                Comments
                                                                                </th>
                                                                                
                                                                               
                                                                                 <th >Action</th> 
                                                                            
                                                                            </tr>
                                                                        </thead>

                                                                        <tbody class="text-black text-center">
                                                                             <?php $ot=$newTreat->getOTReg($_REQUEST['id']);
                                                                            $sn=1;
                                                                            while($row=mysqli_fetch_assoc($ot)){?>
                                                                            <tr>
                                                                               <td><?= $sn?></td>
                                                                               <td><?= date("d.m.Y",strtotime($row['date']))?></td>

                                                                               <td><?php echo $row['doctor'] ?></td>
                                                                               <td><?= $row['ot_name']?></td>
                                                                               <td><?= $row['ot_procedure']?></td>
                                                                               <td><?= $row['comment']?></td>
                                                                                 <td>
                                                                             
                                                                                <a href="#" class="badge badge-pill badge-danger" onclick="delOT(<?= $row['id']?>)" data-toggle="tooltip" title="Delete Entry"><i class=" ri-delete-bin-line"></i></a>
                                                                                </td> 
                                                                             
                                                                            </tr>
                                                                        <?php $sn++;} ?>
                                                    
                                                         
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                    </div>



      </div>
 
<hr>


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

  
  

    


    

    
    <!-- Datatable -->
    <script src="./vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="./js/plugins-init/datatables.init.js"></script>
    <script src="./js/plugins-init/datatables.init.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>


<script>
  
    let medicinesToAdd = []; // Array to hold medicines to be submitted
    let currentPage = 1;
    let isLoading = false;
    let hasMoreData = true;
    const medicineCache = [];
    const DROPDOWN_DISTINCT_LIMIT = 20;
    let roundsToAdd = [];

    function escapeHtml(str) {
        return String(str || '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function toggleManualMedicineField() {
        const medicineSelect = document.getElementById('omedicine');
        const manualMedicineGroup = document.getElementById('manual_medicine_group');
        const manualMedicineInput = document.getElementById('manual_medicine');
        const manualSelected = medicineSelect && medicineSelect.value === 'manual_other';

        if (manualMedicineGroup) {
            manualMedicineGroup.style.display = manualSelected ? 'block' : 'none';
        }

        if (!manualSelected && manualMedicineInput) {
            manualMedicineInput.value = '';
        }
    }

    function clearDynamicMedicineOptions() {
        $('#omedicine option[data-dynamic="1"]').remove();
    }

    function getDynamicOptionCount() {
        return $('#omedicine option[data-dynamic="1"]').length;
    }

    // Add medicine to pending table
    function addMedicine() {
        const medicineId = document.getElementById('omedicine').value;
        const manualMedicineName = (document.getElementById('manual_medicine').value || '').trim();
        const isManual = medicineId === 'manual_other';
        const medicineName = isManual
            ? manualMedicineName
            : (document.getElementById('omedicine_name').value || (document.getElementById('omedicine').selectedIndex >= 0 ? document.getElementById('omedicine').options[document.getElementById('omedicine').selectedIndex].text.split(' (Qty:')[0] : ''));
        const quantity = document.getElementById('qty1').value;
        const date = document.getElementById('odate').value;

        if ((!medicineId || (isManual && !manualMedicineName)) || !quantity) {
            alert('Please select a medicine, enter manual medicine name for Other, and enter quantity');
            return;
        }

        if (isNaN(quantity) || quantity <= 0) {
            alert('Quantity must be a positive number');
            return;
        }

        // Add to array
        medicinesToAdd.push({
            product_id: isManual ? '' : medicineId,
            product_name: medicineName,
            quantity: quantity,
            date: date,
            is_manual: isManual ? 1 : 0
        });

        // Update pending table
        updatePendingTable();

        // Reset fields
        document.getElementById('omedicine').value = '';
        document.getElementById('omedicine_name').value = '';
        document.getElementById('manual_medicine').value = '';
        document.getElementById('qty1').value = '';
        toggleManualMedicineField();
        $('#omedicine').selectpicker('refresh');

        console.log('Medicines:', medicinesToAdd);
    }

    // Update pending medicines table
    function updatePendingTable() {
        const tbody = document.getElementById('pendingMedicinesBody');
        tbody.innerHTML = '';

        medicinesToAdd.forEach((med, index) => {
            const row = `<tr>
                <td>${index + 1}</td>
                <td>${med.date}</td>
                <td>${med.product_name}${med.is_manual ? ' <span class="badge badge-secondary">Manual</span>' : ''}</td>
                <td>${med.quantity}</td>
                <td><button type="button" class="btn btn-sm btn-danger" onclick="removeMedicine(${index})">Remove</button></td>
            </tr>`;
            tbody.innerHTML += row;
        });
    }

    // Remove medicine from pending list
    function removeMedicine(index) {
        medicinesToAdd.splice(index, 1);
        updatePendingTable();
    }

    function addRoundAssessment() {
        const dateTimeInput = document.getElementById('round_datetime');
        const serviceSelect = document.getElementById('round_service');
        const qtyInput = document.getElementById('round_qty');

        if (!dateTimeInput || !serviceSelect || !qtyInput) {
            return;
        }

        const roundDateTime = dateTimeInput.value;
        const serviceId = serviceSelect.value;
        const selectedServiceOpt = serviceSelect.options[serviceSelect.selectedIndex];
        const serviceName = selectedServiceOpt ? selectedServiceOpt.text : '';
        const isDoctorShare = selectedServiceOpt ? (selectedServiceOpt.getAttribute('data-doctor-share') == '1') : false;
        const qty = qtyInput.value;

        if (!roundDateTime || !serviceId || !qty) {
            alert('Please select date time, service and quantity');
            return;
        }

        if (isNaN(qty) || parseFloat(qty) <= 0) {
            alert('Quantity must be a positive number');
            return;
        }

        let doctorId = '';
        let doctorName = '-';
        if (isDoctorShare) {
            const doctorSelect = document.getElementById('round_doctor');
            doctorId = doctorSelect ? doctorSelect.value : '';
            doctorName = (doctorSelect && doctorSelect.selectedIndex >= 0 && doctorSelect.value) ? doctorSelect.options[doctorSelect.selectedIndex].text : '-';
            if (!doctorId) {
                alert('Please select a doctor for this service');
                return;
            }
        }

        roundsToAdd.push({
            round_datetime: roundDateTime,
            service_id: serviceId,
            service_name: serviceName,
            qty: qty,
            doctor_id: doctorId,
            doctor_name: doctorName
        });

        updateRoundAssessmentTable();

        document.getElementById('round_qty').value = '';
        document.getElementById('round_service').value = '';
        $('#round_service').selectpicker('refresh');
        $('#round_doctor_col').hide();
        if (document.getElementById('round_doctor')) {
            document.getElementById('round_doctor').value = '';
            $('#round_doctor').selectpicker('refresh');
        }
    }

    function updateRoundAssessmentTable() {
        const tbody = document.getElementById('pendingRoundBody');
        if (!tbody) {
            return;
        }

        tbody.innerHTML = '';

        roundsToAdd.forEach((row, index) => {
            const tr = `<tr>
                <td>${index + 1}</td>
                <td>${row.round_datetime.replace('T', ' ')}</td>
                <td>${row.service_name}</td>
                <td>${row.qty}</td>
                <td>${row.doctor_name || '-'}</td>
                <td><button type="button" class="btn btn-sm btn-danger" onclick="removeRoundAssessment(${index})">Remove</button></td>
            </tr>`;
            tbody.innerHTML += tr;
        });
    }

    function removeRoundAssessment(index) {
        roundsToAdd.splice(index, 1);
        updateRoundAssessmentTable();
    }

    // On form submit, serialize medicines to JSON
    const medicineForm = document.getElementById('medicineForm');
    if (medicineForm) {
        medicineForm.addEventListener('submit', function(e) {
            if (medicinesToAdd.length === 0) {
                e.preventDefault();
                alert('Please add at least one medicine');
                return;
            }

            document.getElementById('medicines_data').value = JSON.stringify(medicinesToAdd);
            console.log('Submitting medicines:', medicinesToAdd);
        });
    }

    const roundAssessmentForm = document.getElementById('roundAssessmentForm');
    if (roundAssessmentForm) {
        roundAssessmentForm.addEventListener('submit', function(e) {
            if (roundsToAdd.length === 0) {
                e.preventDefault();
                alert('Please add at least one round assessment');
                return;
            }

            document.getElementById('round_data').value = JSON.stringify(roundsToAdd);
        });
    }
    
    // Load medicines from RetailXpress API
    function loadMedicines(page = 1, searchTerm = '') {
        if (isLoading || !hasMoreData) return;
        
        isLoading = true;
        console.log('Loading medicines page:', page);
        
        $.ajax({
            type: 'POST',
            url: 'api/pharmacy_stock_items.php',
            data: {
                action: 'get_medicines',
                page: page,
                limit: 20,
                search: searchTerm
            },
            success: function(response) {
                console.log('API Response:', response);
                try {
                    const data = typeof response === 'string' ? JSON.parse(response) : response;
                    console.log('Parsed data:', data);
                    
                    if (data.status && data.data && data.data.items) {
                        const medicines = data.data.items;
                        console.log('Medicines found:', medicines.length);
                        
                        if (medicines.length === 0) {
                            hasMoreData = false;
                            isLoading = false;
                            return;
                        }
                        
                        medicines.forEach(function(medicine) {
                            // Keep one row per product, but always keep the highest available qty across batches.
                            const qty = parseFloat(medicine.current_quantity || 0);
                            const existingIndex = medicineCache.findIndex(function(m) {
                                return m.product_id === medicine.product_id;
                            });

                            if (existingIndex === -1) {
                                medicineCache.push(medicine);
                            } else {
                                const existingQty = parseFloat(medicineCache[existingIndex].current_quantity || 0);
                                if (qty > existingQty) {
                                    medicineCache[existingIndex] = medicine;
                                }
                            }

                            const bestMedicine = existingIndex === -1 ? medicine : medicineCache[existingIndex];
                            const medicineName = bestMedicine.product && bestMedicine.product.name ? bestMedicine.product.name : 'Unknown Medicine';
                            const generic_name = bestMedicine.product && bestMedicine.product.generic_name ? bestMedicine.product.generic_name : '';
                            const bestQty = parseFloat(bestMedicine.current_quantity || 0);
                            const optionLabel = medicineName + (bestQty > 0 ? ' (Qty: ' + bestQty + ')' : ' (Out of Stock)');
                            const safeLabel = escapeHtml(optionLabel);
                            const safeGeneric = escapeHtml(generic_name);
                            const optionContent = '<div>'
                                + '<div>' + safeLabel + '</div>'
                                + (safeGeneric ? '<div class="small text-muted d-block">' + safeGeneric + '</div>' : '')
                                + '</div>';

                            const existingOption = $('#omedicine option[value="' + medicine.product_id + '"][data-dynamic="1"]');
                            if (existingOption.length) {
                                existingOption.text(optionLabel);
                                existingOption.attr('data-name', medicineName);
                                existingOption.attr('data-content', optionContent);
                            } else if (getDynamicOptionCount() < DROPDOWN_DISTINCT_LIMIT) {
                                $('#omedicine').append(
                                    $('<option>', {
                                        value: medicine.product_id,
                                        text: optionLabel,
                                        'data-dynamic': '1',
                                        'data-name': medicineName,
                                        'data-content': optionContent
                                    })
                                );
                            }
                        });
                        
                        $('#omedicine').selectpicker('refresh');
                        currentPage = page + 1;
                        isLoading = false;
                        
                        if (medicines.length < 20) {
                            hasMoreData = false;
                        }

                        // Ensure dropdown shows up to 20 distinct products even when API pages contain duplicates.
                        if (hasMoreData && getDynamicOptionCount() < DROPDOWN_DISTINCT_LIMIT) {
                            loadMedicines(currentPage, searchTerm);
                        }
                    } else {
                        console.error('Invalid data structure:', data);
                        hasMoreData = false;
                        isLoading = false;
                    }
                } catch (e) {
                    console.error('Error parsing medicine data:', e, response);
                    isLoading = false;
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX failed:', status, error);
                console.error('Response:', xhr.responseText);
                isLoading = false;
            }
        });
    }

    // Initialize medicine loading on page load
    $(document).ready(function() {
        if ($('#omedicine').length) {
            $('#omedicine').selectpicker();

            // Then load medicines
            setTimeout(function() {
                loadMedicines(1);
            }, 500);

            // Load medicine name on selection
            $('#omedicine').on('change', function() {
                const selectedOption = $(this).find('option:selected');
                const selectedValue = $(this).val();

                if (selectedValue === 'manual_other') {
                    $('#omedicine_name').val('');
                } else {
                    $('#omedicine_name').val(selectedOption.data('name') || selectedOption.text().split(' (Qty:')[0]);
                }

                toggleManualMedicineField();
            });

            toggleManualMedicineField();
        }

        if ($('#round_service').length) {
            $('#round_service').selectpicker();
            $('#round_service').on('change', function() {
                var sel = document.getElementById('round_service');
                var opt = sel ? sel.options[sel.selectedIndex] : null;
                var isDr = opt ? opt.getAttribute('data-doctor-share') : '0';
                if (isDr == '1') {
                    $('#round_doctor_col').show();
                    $('#round_doctor').selectpicker('refresh');
                } else {
                    $('#round_doctor_col').hide();
                    if (document.getElementById('round_doctor')) {
                        document.getElementById('round_doctor').value = '';
                        $('#round_doctor').selectpicker('refresh');
                    }
                }
            });
        }
        if ($('#round_doctor').length) {
            $('#round_doctor').selectpicker();
        }
        
        // Search functionality with debounce - bind after dropdown is shown
        let searchTimeout;
        let searchBound = false;
        
        $('#omedicine').on('shown.bs.select', function() {
            // Bind search event only once
            if (!searchBound) {
                const searchInput = $(this).parent().find('.bs-searchbox input');
                
                searchInput.on('input', function() {
                    const searchTerm = $(this).val().trim();
                    
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(function() {
                        if (searchTerm.length >= 2) {
                            console.log('Searching for:', searchTerm);
                            
                            // Reset pagination and cache
                            currentPage = 1;
                            hasMoreData = true;
                            medicineCache.length = 0;
                            
                            // Clear dynamically loaded options and preserve static options
                            clearDynamicMedicineOptions();
                            $('#omedicine').selectpicker('refresh');
                            
                            // Load with search term
                            loadMedicines(1, searchTerm);
                        } else if (searchTerm.length === 0) {
                            // Reset to show all medicines when search is cleared
                            currentPage = 1;
                            hasMoreData = true;
                            medicineCache.length = 0;
                            
                            clearDynamicMedicineOptions();
                            $('#omedicine').selectpicker('refresh');
                            
                            loadMedicines(1);
                        }
                    }, 500); // 500ms debounce
                });
                
                searchBound = true;
            }
            
            // Progressive loading on dropdown scroll
            const dropdown = $('.bootstrap-select .dropdown-menu.inner');
            dropdown.off('scroll').on('scroll', function() {
                if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight - 100) {
                    if (!isLoading && hasMoreData) {
                        const searchInput = $('#omedicine').parent().find('.bs-searchbox input');
                        const searchTerm = searchInput.val().trim();
                        loadMedicines(currentPage, searchTerm);
                    }
                }
            });
        });
    });
  
    function showSubProce(val)
    {
        $.ajax({
            type : 'post',
            url : 'ajax.php', //Here you will fetch records 
            data :  'proce='+val+'&page=sub_proce', //Pass $id
            success : function(data){
            $('#subprocedure').html(data);//Show fetched data from database
            }
        });
         $.ajax({
            type : 'post',
            url : 'ajax.php', //Here you will fetch records 
            data :  'proce='+val+'&page=proce_medicine', //Pass $id
            success : function(data){
            $('#proce_medicine').html(data);//Show fetched data from database
            }
        });
    }
 
      if(window.location.href.split("#")[1]!=undefined)
        {
            var collection=document.getElementsByClassName(' show active');
       for (let i = 0; i < collection.length; i++) {
        collection[i].classList.remove("active");
            }
       var collection1=document.getElementsByClassName(' active');
       for (let i = 0; i < collection1.length; i++) {
  collection1[i].classList.remove("active");
            }
           var id= window.location.href.split("#")[1];
       document.getElementById(id).className += " active";
        // alert(id);
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
    // alert(element)
       document.getElementById(element).className += " show active";
        }
         function getDetailOT()
    {
        var val=document.getElementById('ottype').value;
        if(val==1)
        {
            document.getElementById('major_ot').style.display="block";
            document.getElementById('minor_ot').style.display="none";

        }
        else if(val==2)
        {
            document.getElementById('minor_ot').style.display="block";
            document.getElementById('major_ot').style.display="none";

        }
        else{
            document.getElementById('minor_ot').style.display="none";
            document.getElementById('major_ot').style.display="none";

        }
    }
    function getIcdCode(id,type)
    {
        $.ajax({
            type:'post',
             url : 'ajax.php', //Here you will fetch records 
            data :  'icd='+id+'&page=icd_code',
              success : function(data){
           document.getElementById(type).value=data;
            }
        });
    }
    function delOT(id)
    {
        $.ajax({
            type:'post',
             url : 'ajax.php', //Here you will fetch records 
            data :  'id='+id+'&page=deleteOT',
              success : function(response){
           if (response == 1) {
            location.reload();
            } else {
            alert('OT is not deleted');
                }
            },
            error: function() {
                alert('Error!');
            }
        });
    }
</script> 

  

</body>

</html>