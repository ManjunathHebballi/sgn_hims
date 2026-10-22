<?php 
require_once(__DIR__ . '/model/session_config.php');

include('model/optreatmentClass1.php');

$newTreat=new addOTTreat;
$patient=$newTreat->getPatient($_REQUEST['id']);
$res=mysqli_fetch_assoc($patient);

$exam_res=$newTreat->getOPExamination($_REQUEST['id']);
$exam_row=mysqli_fetch_assoc($exam_res);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>OP Treatment</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
   
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">

    <link href="assets/libs/datatables.net-select-bs4/css//select.bootstrap4.min.css" rel="stylesheet" type="text/css" />

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet"/>
<!-- Summernote css -->
<link href="assets/libs/summernote/summernote-bs4.min.css" rel="stylesheet" type="text/css" />


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

        <!--**********************************
            Ccontent body start
        ***********************************-->
        <div class="content-body">
            <!-- row -->

<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <h4 class="text-uppercase">DEPARTMENT OP VISIT DETAILS</h4>
        </div>
    </div>
    <form action="controller/processOPTreat1.php" method="post">
    <div class="card">
        <div class="card-body">
             <h5><?= $res['opd_no'].' : '.$res['name'].' ( '.$res['age'].' y, '.$res['gender'].' )'?> </h5>
             <hr/>
             
             <input type="hidden" name="exam_edit" value="<?= $exam_row['id']?>">
        <div class="row">
            
             <div class="col-md-6">
                 <label>Comorbidities</label>
                                                    <select name="como[]" multiple  id="como" class="form-control selectpicker" placeholder="Select Comorbidities"  data-live-search="true">
                                                                               
                                                   <?php $como=$newTreat->getComorbidities(); 
                                                                    while ($res1=mysqli_fetch_assoc($como))
                                                                    {?>
                                                                    <option value="<?= $res1['comorbidities']?>" ><?= $res1['comorbidities']?></option>
                                                                    <?php } 
                                                                     $comorbidities = $exam_row['comorbidities'];  // This is your comma-separated values string
                                                        $comorbiditiesArray = explode(',', $comorbidities);  // Split the string into an array

                                                        foreach ($comorbiditiesArray as $como) {
                                                        $como = trim($como);  // Trim any extra spaces
                                                         if(strlen($como)>0){
                                                        echo '<option value="' . htmlspecialchars($como) . '" selected>' . htmlspecialchars($como) . '</option>';
                                                        }
                                                        }
                                                        ?>
  
                                                                                    
                                                </select>
                                          
                                                 </div>
                                                 <div class="col-md-6">
                 <label>Hypersensitivity</label>
                                                      <select name="hyper[]" multiple  id="hyper" class="form-control selectpicker" placeholder="Select Hypersensitivity"  data-live-search="true">
                                                                               
                                                   <?php $hyper=$newTreat->getHypersensitivity(); 
                                                                    while ($res2=mysqli_fetch_assoc($hyper))
                                                                    {?>
                                                                    <option value="<?= $res2['hypersensitivity']?>" ><?= $res2['hypersensitivity']?></option>
                                                                    <?php } 
                                                                    
                                                                    
                                                                    $hypersensitivity = $exam_row['hypersensitivity'];  // This is your comma-separated values string
                                                        $hypersensitivityArray = explode(',', $hypersensitivity);  // Split the string into an array

                                                        foreach ($hypersensitivityArray as $hyper) {
                                                        $hyper = trim($hyper);  // Trim any extra spaces
                                                         if(strlen($hyper)>0){
                                                        echo '<option value="' . htmlspecialchars($hyper) . '" selected>' . htmlspecialchars($hyper) . '</option>';
                                                        }
                                                        }
                                                        ?>
  
                                                                                    
                                                </select>
                                          
                                                 </div>
                                                  <div class="col-md-6 mt-3">
              <div class="form-group">
                     <label>Chief Complain</label>
                                                    <select name="complaint[]" multiple  id="complaint" class="form-control selectpicker"  data-live-search="true">
  <?php
                                                    $comp=$newTreat->getComplaints();
                                                        while($row=mysqli_fetch_assoc($comp))
                                                        {
                                                        ?>
                                                         <option value="<?= $row['complaints']?>" ><?= $row['complaints']?></option>
                                                         <?php 
                                                        }
                                                        $complaints = $exam_row['complaints'];  // This is your comma-separated values string
                                                        $complaintsArray = explode(',', $complaints);  // Split the string into an array

                                                        foreach ($complaintsArray as $complaint) {
                                                        $complaint = trim($complaint);  // Trim any extra spaces
                                                         if(strlen($complaint)>0){
                                                        echo '<option value="' . htmlspecialchars($complaint) . '" selected>' . htmlspecialchars($complaint) . '</option>';
                                                        }
                                                        }
                                                        ?>
                                                        
                                                                                    
                                                </select>
              </div>
                                          
                                                 </div>
                                                 <div class="col-md-6 mt-3">
                 <label>Associated With</label>
                                                     <select name="associated[]" multiple  id="associated" class="form-control selectpicker" placeholder="Select Associated With"  data-live-search="true">
                                                                               
                                                   <?php $associated=$newTreat->getAssociated_with(); 
                                                                    while ($res2=mysqli_fetch_assoc($associated))
                                                                    {?>
                                                                    <option value="<?= $res2['associated_with']?>" ><?= $res2['associated_with']?></option>
                                                                    <?php } 
                                                                   
                                                                   
                                                                   $associated_with = $exam_row['associated_with'];  // This is your comma-separated values string
                                                        $associated_withArray = explode(',', $associated_with);  // Split the string into an array

                                                        foreach ($associated_withArray as $assoc) {
                                                        $assoc = trim($assoc);  // Trim any extra spaces
                                                         if(strlen($assoc)>0){
                                                        echo '<option value="' . htmlspecialchars($assoc) . '" selected>' . htmlspecialchars($assoc) . '</option>';
                                                        }
                                                        }
                                                        ?>
  
                                                                                    
                                                </select>
                                          
                                                 </div>
                                                 <div class="col-md-12 mt-4">
                                                     <label> History</label>
                                                       
           <pre><textarea name="history" id="textarea1"  placeholder="Type Here...."><?=$exam_row['history']?></textarea></pre> 
                                                 </div>
                                                 
                                                 
                                                 <div class="col-md-12">
                                                     <h5>Vitals</h5>
                                                 <hr>
                                                     <div class="row">
                                                         <div class="col-sm-2">
                                                             <label>BP</label>
                                                              <div class="input-group mb-3">
  <input type="text" class="form-control" placeholder="BP" name="bp" id='bp' value="<?=$exam_row['bp']?>">
  <div class="input-group-append">
    <span class="input-group-text" id="basic-addon2">BP_S / BP_D</span>
  </div>
</div>
                                                           
                                                         </div>
                                                         <div class="col-sm-2" >
                                                             
                                                                 <label>RBS</label>
                                                             <div class="input-group mb-3">
  <input type="text" class="form-control" placeholder="RBS" name="rbs" id='rbs' value="<?=$exam_row['rbs']?>">
  <div class="input-group-append">
    <span class="input-group-text" id="basic-addon2">mg/dl</span>
  </div>
</div>
                                                         </div>
                                                          <div class="col-sm-2">
                                                             
                                                                 <label>PR</label>
                                                             <div class="input-group mb-3">
  <input type="text" class="form-control" placeholder="PR" name="pr" id='pr' value="<?=$exam_row['pr']?>">
  <div class="input-group-append">
    <span class="input-group-text" id="basic-addon2">bpm</span>
  </div>
</div>
                                                         </div>
                                                          <div class="col-sm-2">
                                                             
                                                                 <label>Ht</label>
                                                             <div class="input-group mb-3">
  <input type="text" class="form-control" placeholder="Ht" name="ht" id='ht' value="<?=$exam_row['ht']?>">
  <div class="input-group-append">
    <span class="input-group-text" id="basic-addon2">Cms</span>
  </div>
</div>

                                                         </div>
                                                        
 <div class="col-sm-2">
     
                                                             
                                                                 <label>Waist</label>
                                                             <div class="input-group mb-3">
  <input type="text" class="form-control" placeholder="Waist" name="waist" id='waist' value="<?=$exam_row['waist']?>">
  <div class="input-group-append">
    <span class="input-group-text" id="basic-addon2">Cms</span>
  </div>
</div>
   </div>
 <div class="col-sm-2">
                                                             
                                                                 <label>TP</label>
                                                             <div class="input-group mb-3">
  <input type="text" class="form-control" placeholder="TP" name="tp" id='tp' value="<?=$exam_row['tp']?>">
  <div class="input-group-append">
    <span class="input-group-text" id="basic-addon2"><sup>o</sup>F</span>
  </div>
</div>

                                                         </div>
                                                          <div class="col-sm-2">
                                                             
                                                                 <label>RR</label>
                                                             <div class="input-group mb-3">
  <input type="text" class="form-control" placeholder="RR" name="rr" id='rr' value="<?=$exam_row['rr']?>">
  <div class="input-group-append">
    <span class="input-group-text" id="basic-addon2">Cpm</span>
  </div>
</div>
                                                         </div>
                                                         
                                                          <div class="col-sm-2">
                                                             
                                                                 <label>SpO2</label>
                                                             <div class="input-group mb-3">
  <input type="text" class="form-control" placeholder="SpO2" name="spo" id='spo' value="<?=$exam_row['spo2']?>">
  <div class="input-group-append">
    <span class="input-group-text" id="basic-addon2">%</span>
  </div>
</div>
                                                         
                                                     </div>
                                                      <div class="col-sm-2">
                                                             
                                                                 <label>Weight</label>
                                                             <div class="input-group mb-3">
  <input type="text" class="form-control" placeholder="Weight" name="weight" id='weight' value="<?=$exam_row['weight']?>">
  <div class="input-group-append">
    <span class="input-group-text" id="basic-addon2">Kgs</span>
  </div>
</div>
                                                 </div>
                                                 
                                                  <div class="col-sm-2">
                                                             
                                                                 <label>HC</label>
                                                             <div class="input-group mb-3">
  <input type="text" class="form-control" placeholder="HC" name="hc" id='hc' value="<?=$exam_row['hc']?>">
  <div class="input-group-append">
    <span class="input-group-text" id="basic-addon2">Cms</span>
  </div>
</div>
                                                 
        </div>
         <div class="col-sm-2">
                                                             
                                                                 <label>BMI</label>
                                                             <div class="input-group mb-3" >
  <input type="text" class="form-control" placeholder="BMI" name="bmi" id='bmi' value="<?=$exam_row['bmi']?>">
 
</div>
            
        </div>
        
    </div>
    
    <div class="col-md-12 mt-4">
                                                     <h5> Examination</h5>
                                                     <hr/>
                                                       
           <pre><textarea name="examination" id="textarea2"  placeholder="Type Here...."><?=$exam_row['examination']?></textarea></pre> 
                                                 </div>
                                                 <hr/>
                                                   <div class="col-md-12 mt-3">
                 <label>Diagnosis</label>
                                                     <select name="diagnosis[]" multiple  id="diagnosis" class="form-control selectpicker" placeholder="Select Diagnosis"  data-live-search="true">
                                                                               
   <?php $icd=$newTreat->getIcdCodes(); 
                    while ($res1=mysqli_fetch_assoc($icd))
                    {?>
                    <option value="<?= $res1['details']?>" ><?= $res1['details']?></option>
                    <?php } 
                    
                    $diagnosis = $exam_row['diagnosis'];  // This is your comma-separated values string
                                                        $diagnosisArray = explode(',', $diagnosis);  // Split the string into an array
                        
                                                        foreach ($diagnosisArray as $diag) {
                                                        $diag = trim($diag);  // Trim any extra spaces
                                                        if(strlen($diag)>0){
                                                        echo '<option value="' . htmlspecialchars($diag) . '" selected>' . htmlspecialchars($diag) . '</option>';
                                                        }
                        }
                    ?>
  
                                                                                    
                                                </select>
                                          
                                                 </div>
                                                 
                                                  <div class="row px-3 mt-3">

                                            

   </div>

<div class="row p-3">
    
                                    <div class="col-md-12">
                                        <h5>Add Prescription</h5>
                                        <hr/>
                                    <div >
                                    <table class="table table-bordered " id="loadMedic">
                                                                        <thead class="thead-dark  text-center "  >
                                                                            <tr>
                                                                                <!--<th class="text-center" width="50px">Sl.No.</th>-->
                                                                                <!--<th>Date</th>-->
                                                                                <th>Medicine Name</th>
                                                                                
                                                                                <th >Dosage</th>
                                                                                <th>Timing</th>
                                                                               <th>Frequency</th>
                                                                               <th>Duration</th>
                                                                               
                                                                                <th>Quantity</th>
                                                                                <th>Instructions</th>
                                                                              
                                                                              
                                                                                <th >Action</th>
                                                                                
                                                                            </tr>
                                                                        </thead>


                                                                        <tbody class="text-black text-center" >
                                                                            <?php
                                                                            $data=$newTreat->getPatienMedicDoc($_REQUEST['id']);
                                                                            if(mysqli_num_rows($data)>0)
                                                                            {
                                                                            while($row=mysqli_fetch_assoc($data)) {?>
                                                                            <tr>
                                                                          
                                                                              
                                                                           
                                                                               <td>
                                                                                   
                                                                                   
                                                                                   <?= $row['name']?></td>
                                                                               <td><?php echo $row['dosage']?></td>
                                                                               <td><?php echo $row['when']?></td><td> <?php echo $row['frequency']?></td><td><?php echo $row['days']?></td>
                                                                               
                                                                                <td><?=$row['qty']?></td>
                                                                                 <td><?=$row['instruction']?></td>
                                                                                <td class="text-center"><a href="#"  title="Delete Test" class="badge badge-pill badge-danger"><i class="ri-close-line "></i></a></td>
                                                                               
                                                                             
                                                                            </tr>
                                                                            <?php } 
                                                                            }
                                                                           else if($res['old_patient']==1) {
                                                                               $sn=1;
                                                                                   $data=$newTreat->patientoldDetials($res['opd_no'],$res['date']);
                                                                                   while($row=mysqli_fetch_assoc($data))
                                                                                   {
                                                                                       ?>
                                                                                       <tr id="add<?= $sn?>">
                                                                            
                                                                        
                                                                               <td >
 <input type="text" class="form-control" list="medicines101" name="medicine[]" id="medicine101" onkeyup="addMedic(this.value,101)" value="<?= $row['name']?>" autocomplete="off" readonly placeholder="Enter Medicine Name">
                    
                                                                                 
                                                                                   
                                                                                  
                                                                               </td>
                                                                               <td>
                                                                                   
                                                                                   
                  <input type="text" class="form-control"  list="dosage"  name="dose[]" id="dose101" onchange="calQty(101)" value="<?= $row['dosage']?>" readonly placeholder="Enter Dosage"/>
                                                       
                                             </td>
                                                                               <td> <input type="text" class="form-control"  readonly name="food[]" id="food101"  value="<?= $row['when']?>" />
                                                 
                                             </td>
                                                                               <td><input type="text" class="form-control"  readonly name="frequency[]" id="frequency101"  value="<?= $row['frequency']?>" />
                                                                                   
                                                                               </td>
                                                                               
                                                                                 <td>
                                                                                    
                                                                                      <input type="text" class="form-control" list="day1" name="days[]" id="days101" onkeyup="showResult(this.value,1)" onchange="calQty(1)" autocomplete="off" placeholder="Enter Medicine Name" value="<?= $row['days']?>" readonly>
                  
                                                                                  
                                                                                 </td>
                                                                               <td> <input class="form-control" name="qty[]" id="qty101" value="<?= $row['qty']?>" readonly></td>
                                                                               <td><a href="#" onclick="dltold(<?=$sn;?>)" class="text-danger">Delete</a></td>
                                                                              
                                                                                 <input type='hidden' name='medicine_id[]' id='medicine_id101' value='<?= $row['medicine']?>'>
                                                                                
                                                                                 <!-- <td>
                                                                             
                                                                                     <a href="" class="badge badge-pill badge-danger p-3 "><i class=" bi bi-trash"></i></a>
                                                                                 </td> -->
                                                                             </tr>
                                                                                      
                                                                                       <?php $sn++;
                                                                                   }
                                                                             } 
                                                                            ?>
                                                                             <tr id="addr1">
                                                                            
                                                                        
                                                                               <td >
                                                                                                                                                <input type="text" class="form-control" list="medicines1" name="medicine[]" id="medicine1" onkeyup="addMedic(this.value,1)" autocomplete="off" placeholder="Enter Medicine Name">
                    
                                                                                   <datalist id="medicines1">
       <option value="">Select Medicine</option>
                                                                                <option value="">Select Medicine</option>
                                                                                   <?php $medi=$newTreat->getdoctMedic();
                                                                                   while($medic=mysqli_fetch_assoc($medi)){?>
                                                                                   <option value="<?= $medic['name']?>"></option>
                                                                                   <?php } ?>
                                                </datalist>
                                                                                   
                                                                                  
                                                                               </td>
                                                                               <td>
                                                                                   
                                                                                   
                                                                                     <input type="text" class="form-control"  list="dosage"  name="dose[]" id="dose1" onchange="calQty(1)" placeholder="Enter Dosage"/>
                                                                                   <datalist id="dosage">
                                                <option value="">Select</option>
                                                <option value="1-0-0">1-0-0</option>
                                                <option value="0-0-1">0-0-1</option>
                                                <option value="1-0-1">1-0-1</option>   
                                                <option value="1-1-1">1-1-1</option>
                                                <option value="1-1-0">1-1-0</option>
                                                <option value="0-1-0">0-1-0</option>
                                                <option value="0-1-1">0-1-1</option> 
                                                <option value="0-0-0-1">0-0-0-1</option> 
                                            </datalist>
                                            </td>
                                                                               <td> <select class="form-control" name="food[]" id="food1">
                                                  <option value="">Select</option>
                                                <option value="Before Food">Before Food</option>
                                                <option value="After Food">After Food</option>
                                                <option value="Before Breakfast">Before Breakfast</option>
                                                <option value="After Breakfast">After Breakfast</option>
                                                <option value="Before Lunch">Before Lunch</option>
                                                <option value="After Lunch">After Lunch</option>
                                                <option value="Before Dinner">Before Dinner</option>
                                                <option value="After Dinner">After Dinner</option>
                                                <option value="Empty Stomach">Empty Stomach</option>
                                                <option value="Bed Time">Bed Time</option>
                                                <option value="SOS">SOS</option>
                                                
                                            </select></td>
                                                                               <td>
                                                                                   <select class="form-control" name="frequency[]" id="frequency1">
                                                  <option value="">Select</option>
                                                <option value="Daily">Daily</option>
                                                 <option value="Alternate Day">Alternative Days</option>
                                                  <option value="Weekly">Weekly</option>
                                                   <option value="Fort Night">Fort Night</option>
                                                    <option value="Monthly">Monthly</option>
                                                
                                                
                                            </select>
                                                                               </td>
                                                                               
                                                                                <td>
                                                                                    
                                                                                     <input type="text" class="form-control" list="day1" name="days[]" id="days1" onkeyup="showResult(this.value,1)" onchange="calQty(1)" autocomplete="off" placeholder="Enter Medicine Name">
                    <datalist id="day1">

  
                                                                                    <option><span class="span_day1"></span> days</option>
                                                                                    <option><span class="span_day1"></span> weeks</option>
                                                                                    <option><span class="span_day1"></span> months</option>
                                                                                    <option><span class="span_day1"></span> years</option>
                                                </datalist>
                                                                                  
                                                                                </td>
                                                                               <td> <input class="form-control" name="qty[]" id="qty1"></td>
                                                                               <td> <input class="form-control" name="inst[]" id="inst1" placeholder='Enter Instructions'></td>
                                                                               <td><button type="button" class="btn btn-danger" onclick="dlt(1)">Delete</button></td>
                                                                              
                                                                                <input type='hidden' name='medicine_id[]' id='medicine_id1' value=''>
                                                                                
                                                                                <!-- <td>
                                                                             
                                                                                    <a href="" class="badge badge-pill badge-danger p-3 "><i class=" bi bi-trash"></i></a>
                                                                                </td> -->
                                                                            </tr>
                                                                            <tr id="addr2"></tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                    </div>
                                    
                                </div>
 
                               
                               <!--end of Medication-->
                               
                             <hr>
 <div class="row ">

                                            
<div class="col-md-6 mb-2"><h5> LAB Investigations</h5>
                       

<hr/>

                                                    <div class="">
                                                                <div class="form-group">
                                                                    <label for="">Select Tests</label>
                                                                    <select id="lab" name="lab_test[]" class="form-control selectpicker" multiple style="width: 100%">
                                                                        <option value="">Select Tests</option>
                                                                        <?php $lab=$newTreat->getLab(); 
                                                                        $row=mysqli_fetch_assoc($lab);
                                                                        // print_r($row);
                                                                        while($row=mysqli_fetch_assoc($lab)){ ?>
                                                                            <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                   
                                                                </div>
                                                                
                                                            </div>
      


                                                             <table class="table table-bordered" id="loadLab">
                                                                        <thead class="thead-dark">
                                                                            <tr>
                                                                                <th>Sl.No.</th>
                                                                                <th>Selected Tests</th>
                                                                                <th class="text-center">Action</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php
                                                                            $sn=1;
                                                                             $lab=$newTreat->getPatientLab($_REQUEST['id']);
                                                                            while($row=mysqli_fetch_assoc($lab)){ ?>
                                                                            <tr>
                                                                                <td><?= $sn?></td>
                                                                                <td>
                                                                                <p><?= $row['name']?></p>
                                                                                </td>
                                                                              
                                                                                <td class="text-center"><a href="#" onclick="deleteLab('<?= $row['op_lab_id'] ?>','<?= $_REQUEST['id'] ?>')" data-toggle="tooltip" title="Delete Test" class="badge badge-pill badge-danger"><i class="ri-close-line "></i></a></td>
                                                                                
                                                                            </tr>
                                                                            <?php $sn++;} ?>
                                                                        </tbody>
                                                                    </table>
                                                                    <textarea class="form-control mt-3" cols='1' rows='2' placeholder="Enter Laboratory Observation...." name='lab_obs'><?=$exam_row['lab_observation']?></textarea>
                                                        </div>
                                                        <div class="col-md-6 mb-2"><h5>Radiology</h5>
                       

<hr/>

                                                    <div class="">
                                                                <div class="form-group">
                                                                    <label for="">Select Tests</label>
                                                                    <select id="radio_lab" name="radio_lab_test[]" class="form-control selectpicker" multiple style="width: 100%">
                                                                        <option value="">Select Tests</option>
                                                                        <?php $radio_lab = $newTreat->getRadiology(); 
                                                                        while($row=mysqli_fetch_assoc($radio_lab)){ ?>
                                                                            <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                   
                                                                </div>
                                                                
                                                            </div>
      

                                                             <table class="table table-bordered" id="loadLab">
                                                                        <thead class="thead-dark">
                                                                            <tr>
                                                                                <th>Sl.No.</th>
                                                                                <th>Selected Tests</th>
                                                                                <th class="text-center">Action</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php
                                                                            $sn=1;
                                                                             $lab=$newTreat->getPatientLabRadio($_REQUEST['id']);
                                                                            while($row=mysqli_fetch_assoc($lab)){ ?>
                                                                            <tr>
                                                                                <td><?= $sn?></td>
                                                                                <td>
                                                                                <p><?= $row['test_name']?></p>
                                                                                </td>
                                                                                <td class="text-center"><a href="#" onclick="deleteLab('<?= $row['op_lab_id']?>','<?= $_REQUEST['id']?>')" data-toggle="tooltip" title="Delete Test" class="badge badge-pill badge-danger"><i class="ri-close-line "></i></a></td>
                                                                                
                                                                            </tr>
                                                                            <?php $sn++;} ?>
                                                                        </tbody>
                                                                    </table>
                                                                    <textarea class="form-control mt-3" cols='1' rows='2' placeholder="Enter Radiology Observation...." name='radio_obs'><?=$exam_row['radiology_observation']?></textarea>
                                                       
                                                        </div>
                                                        </div>
                                                        
                                     <hr>                    <div class="row">
                                                            <div class="col-md-12">
                                                                <label>Medical Advice</label>
                                                                 <textarea class="form-control mt-3" cols='1' rows='3' placeholder="Enter Medical Advices...." name='medical_advice'><?=$exam_row['medical_advice']?></textarea>
                                                            </div>
                                                             <div class="col-md-12 mt-3">
                 <label>Advice</label>
                                                    <select name="advice[]" multiple  id="advice" class="form-control selectpicker" placeholder="Select Advice"  data-live-search="true">
                                                                               
                                                   <?php $advice=$newTreat->getAdvice(); 
                                                                    while ($res2=mysqli_fetch_assoc($advice))
                                                                    {?>
                                                                    <option value="<?= $res2['advice']?>" ><?= $res2['advice']?></option>
                                                                    <?php } 
                                                                       $advice = $exam_row['advice'];  // This is your comma-separated values string
                                                        $advice_withArray = explode(',', $advice);  // Split the string into an array

                                                        foreach ($advice_withArray as $adv) {
                                                        $adv = trim($adv);  // Trim any extra spaces
                                                         if(strlen($adv)>0){
                                                        echo '<option value="' . htmlspecialchars($adv) . '" selected>' . htmlspecialchars($adv) . '</option>';
                                                        }
                                                        }
                                                        ?>
  
                                                                                    
                                                </select>
                                          
                                                 </div>
                                                 
                                                      <hr>  
                                                  <div class="col-md-12 mt-4">
                                                                      
                                                                      <div class="row d-flex align-items-center ">
                                                                            <div class="col-md-4 ">
                                                                <lable>Next Visit </lable>
                                                                
                                                                <div class="input-group mb-3">
<input type="number" min="0" class="form-control mt-2" name="day" placeholder="Days" aria-label="Recipient's username" aria-describedby="basic-addon2">
  <div class="input-group-append">
       
                                                                <div class="btn-group btn-group-toggle mt-2 text-center" data-toggle="buttons">
  <label class="btn btn-outline-secondary active">
    <input type="radio" checked name="indays" id="day" value="days"> Days
  </label>
  <label class="btn btn-outline-secondary">
    <input type="radio" name="indays" id="week" value="weeks"> Weeks
  </label>
  <label class="btn btn-outline-secondary">
    <input type="radio" name="indays" id="month" value="months"> Months
  </label> 
</div>
  </div>
</div>
                                                               
                                                               
                                                                
                                                            </div>
                                                            <div class="col-md-1 mt-3 text-center">
                                                                <h5>OR</h5>
                                                            </div>
                                                                    <div class="col-md-3">
                                                               
                                                                <div class="form-group">
                                                                    <lable>Select Date</lable>
                                                                    <input class="form-control" value="<?php if($exam_row['next_visit_date']>date('Y-m-d')) echo $exam_row['next_visit_date'];?>" type="date" minvalue="<?php echo date('d-m-Y')?>"name="nextdate">
                                                                </div>
                                                                
                                                            </div>
                                                              <div class="col-md-3">
                                                               
                                                                <div class="form-group">
                                                                    <lable>Refer To.</lable>
                                                                    <select class="form-control selectpicker"  name="refer_to"  id="refer_to" >
                                                                        <option value="">Select Doctor</option>
                                                                        <?php 
                                                    $docto=$newTreat->getDoctor();
                                                    
                                                    while($row=mysqli_fetch_assoc($docto))
                                                    
                                                    {
                                                    ?>
                                                                        <option <?php if($exam_row['referred_to']==$row['id']) { echo "selected"; } ?> value="<?= $row['id']?>"><?= $row['name'].' '.$row['last_name']?></option>
                                                    <?php } ?>
                                                                     
                                                                    </select>
                                                                    </div>
                                                                
                                                            </div>
                                                             
              
                                                                      </div>
                                                                        <div class="row">
                                                              <input type="hidden" name="pid" value="<?= $_REQUEST['id']?>">
        <!--<button type="submit" class="btn btn-success mr-3 mt-4" name="submit" value="invest">Add</button>-->
                                                            <div class="col-md-12">
                                                               
                                                                 <button class="btn btn-success float-right ml-2 mr-2"  name="sumbit" type="submit"value="print">Save & Print</button>
                                                                  <button class="btn btn-warning float-right ml-2 mr-2" name="sumbit" type="submit"value="save">Save</button>
                                                            </div>
                                                        </div>
                                                                  </div> 
                                                        </div>



</div>
<!--End Container Fluied-->
</div>
</div>
</div>
 </form> 
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

<!--<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script>

 <!-- Summernote js -->
        <script src="assets/libs/summernote/summernote-bs4.min.js"></script>
      <!--tinymce js-->
        <script src="assets/libs/tinymce/tinymce.min.js"></script>

        <!-- init js -->
        <script src="assets/js/pages/form-editor.init.js"></script>
    
<script>
    $(".selectpicker").select2({
  tags: true
});

tinymce.init({

      selector: "#textarea1",
        toolbar_mode: 'sliding',
       height: 300

    });
 tinymce.init({

      selector: "#textarea2",
      toolbar_mode: 'sliding',
       height: 300

    });
</script>

<script>

function addMedic(val,i){
    const options = $('#medicines'+i+' option');
    const filter = val.toLowerCase();

    options.each(function() {
      const optionValue = $(this).val().toLowerCase();
      const startsWithFilter = optionValue.startsWith(filter);

      $(this).toggle(startsWithFilter);
    });
    i++;
    $('#addr'+i).html("<td><input class='form-control'list='medicines"+i+"' name='medicine[]' id='medicine"+i+"' onchange='addMedic(this.value,"+i+")'><datalist id='medicines"+i+"'><option value='' >Select Medicine</option><?php $medi=$newTreat->getdoctMedic();while($medic=mysqli_fetch_assoc($medi)){?><option value='<?= $medic['name']?>'></option><?php } ?></datalist></td> <td><input type='text'  list='dosage"+i+"' class='form-control' name='dose[]' id='dose"+i+"' onchange='calQty("+i+")'><datalist id='dosage"+i+"'><option value=''>Select</option><option value='1-0-0'>1-0-0</option><option value='0-0-1'>0-0-1</option><option value='1-0-1'>1-0-1</option><option value='1-1-1'>1-1-1</option><option value='1-1-0'>1-1-0</option><option value='0-1-0'>0-1-0</option><option value='0-1-1'>0-1-1</option><option value='0-0-0-1'>0-0-0-1</option></datalist></td> <td><select class='form-control' name='food[]' id='food"+i+"'><option value=''>Select</option><option value='Before Food'>Before Food</option><option value='After Food'>After Food</option><option value='Before Breakfast'>Before Breakfast</option><option value='After Breakfast'>After Breakfast</option><option value='Before Lunch'>Before Lunch</option><option value='After Lunch'>After Lunch</option><option value='Before Dinner'>Before Dinner</option><option value='After Dinner'>After Dinner</option><option value='Empty Stomach'>Empty Stomach</option><option value='Bed Time'>Bed Time</option><option value='SOS'>SOS</option></select></td> <td><select class='form-control' name='frequency[]' id='frequency"+i+"'><option value=''>Select</option><option value='Daily'>Daily</option><option value='Alternative Days'>Alternative Days</option><option value='Weekly'>Weekly</option><option value='Fort Night'>Fort Night</option><option value='Monthly'>Monthly</option></select></td> <td><input type='text' class='form-control' list='day"+i+"' name='days[]' id='days"+i+"' onkeyup='showResult(this.value,"+i+");calQty("+i+")' autocomplete='off' placeholder='Enter Medicine Name'><datalist id='day"+i+"'><option><span class='span_day"+i+"'></span> days</option><option><span class='span_day"+i+"'></span> weeks</option><option><span class='span_day"+i+"'></span> months</option><option><span class='span_day"+i+"'></span> Years</option></datalist></td> <td><input class='form-control' name='qty[]' id='qty"+i+"'></td> <td><input class='form-control' name='inst[]' id='inst"+i+"' placeholder='Enter Instructions'></td><td><button class='btn btn-danger' type='button' onclick='dlt("+i+")'>Delete</button><input type='hidden' name='medicine_id[]' id='medicine_id"+i+"' value=''></td>");
    $('#loadMedic').append('<tr id="addr'+(i+1)+'"></tr>');
   i--;
      $.ajax({
            type:'post',
             url : 'ajax.php', //Here you will fetch records 
            data :  'medicine='+val+'&page=doc_medicine',
              success : function(data){
                  s=data.split('|');
           document.getElementById("dose"+i).value=s[1];
           document.getElementById("food"+i).value=s[2];
           document.getElementById("frequency"+i).value=s[3];
           document.getElementById("days"+i).value=s[4];
           document.getElementById("qty"+i).value=s[0];
            document.getElementById("medicine_id"+i).value=s[5];
            }
        });
}
function dlt(id)
{
      if(id>1)
      {
          $("#addr"+(id)).html('');
      }
  
}
function dltold(id)
{
      
          $("#add"+(id)).html('');
      
  
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

    function deleteLab(val,id)
    {
         $.ajax({
            type : 'post',
            url : 'ajax.php', //Here you will fetch records 
            data :  'id='+val+'&page=op_lab_del', //Pass $id
            success : function(data){
         $('#loadLab').load(" #loadLab");//Show fetched data from database
            }
        });
    }

 function deleteMedicine(val)
    {
         $.ajax({
            type : 'post',
            url : 'ajax.php', //Here you will fetch records 
            data :  'id='+val+'&page=medic_del', //Pass $id
            success : function(data){
         $('#loadMedic').load(" #loadMedic");//Show fetched data from database
            }
        });
    }
        
    function showResult(val,i)
    {
        var numericPart = val.replace(/\D/g, "");
        var elements = document.getElementsByClassName("span_day"+i);
        for (var i = 0; i < elements.length; i++) {
          elements[i].innerHTML = numericPart;
        }
    }
     
</script> 
<script>
    function calQty(val)
    {
        // var dose=document.getElementById('dose'+val).value);
        
        
          var doseString = document.getElementById('dose'+val).value;
         var doseArray = doseString.split('-');
         var sum = 0;
         for (var i = 0; i < doseArray.length; i++) {
        sum += parseInt(doseArray[i]);
          }
          
          
          var stringValue = document.getElementById('days'+val).value;
          
         var numericValue = parseInt(stringValue.match(/\d+/)[0]);
        //  alert(numericValue)
        //  var textValue = stringValue.split(' ')[1];
         
         if(stringValue.toLowerCase().includes('days'))
         {
             var days=1;
         }
         else if(stringValue.toLowerCase().includes('weeks'))
         {
             var days=7;
         }
         else if(stringValue.toLowerCase().includes('months'))
         {
             var days=30;
         }
         else
         {
             var days=365;
         }
        //  alert(sum*numericValue*days)
        document.getElementById('qty'+val).value=sum*numericValue*days;
    }
</script>




</body>

</html>