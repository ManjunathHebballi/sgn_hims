<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/masterClass.php');
$newMaster = new master;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Edit Time Table</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" ></script>


<!-- ClockPicker Stylesheet -->
<link rel="stylesheet" type="text/css" href="clockpicker-gh-pages/dist/bootstrap-clockpicker.min.css">

  
<style>
    .clockpicker-plate{
        margin-left: -9px !important;
    }
    .pm-button {
        margin-left: 110px !important;
    }
</style>

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
            Content body start
        ***********************************-->
        <div class="content-body">
            <!-- row -->



<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-6">
            <h4 class="text-uppercase">Edit Time Table</h4>
        </div>
         <div class="col-md-6 ">
             
             <a href="create-timetable.php" class="btn btn-success float-right btn-sm"> Add Timetable </a>
             </div>
    </div>


    <div class="row" id="content">
        

    <div class="col-md-12">
    <ul class="nav nav-pills justify-content-around mb-3" id="pills-tab" role="tablist">
  <li class="nav-item" role="presentation">
    <a class="nav-link active" id="pills-monday-tab" data-toggle="pill" href="#pills-monday" role="tab" aria-controls="pills-monday" aria-selected="true">Monday</a>
  </li>
  <li class="nav-item" role="presentation">
    <a class="nav-link" id="pills-tuesday-tab" data-toggle="pill" href="#pills-tuesday" role="tab" aria-controls="pills-tuesday" aria-selected="false">Tueday</a>
  </li>
  <li class="nav-item" role="presentation">
    <a class="nav-link" id="pills-wed-tab" data-toggle="pill" href="#pills-wed" role="tab" aria-controls="pills-wed" aria-selected="false">Wednesday</a>
  </li>
  <li class="nav-item" role="presentation">
    <a class="nav-link" id="pills-thursday-tab" data-toggle="pill" href="#pills-thursday" role="tab" aria-controls="pills-thursday" aria-selected="false">Thursday</a>
  </li>
  <li class="nav-item" role="presentation">
    <a class="nav-link" id="pills-friday-tab" data-toggle="pill" href="#pills-friday" role="tab" aria-controls="pills-friday" aria-selected="false">Friday</a>
  </li>
  <li class="nav-item" role="presentation">
    <a class="nav-link" id="pills-saturday-tab" data-toggle="pill" href="#pills-saturday" role="tab" aria-controls="pills-saturday" aria-selected="false">Saturday</a>
  </li>
  <li class="nav-item" role="presentation">
    <a class="nav-link" id="pills-sunday-tab" data-toggle="pill" href="#pills-sunday" role="tab" aria-controls="pills-sunday" aria-selected="false">Sunday</a>
  </li>
</ul>
        <div class="card">
            <div class="card-body">

         
<div class="tab-content" id="pills-tabContent">
  <div class="tab-pane fade active show" id="pills-monday" role="tabpanel" aria-labelledby="pills-monday-tab">
<!--<form action="controller/processTimetable.php" method="post">-->
  <div class="row" id="monday">
        <div class="col-md-12">
            <div class="table-responsive">
            <table class="table table-bordered" width="100%"   id="mo_tab_logic">
     <thead>
            <tr class="text-center"> 
                <th>Department</th>
                <th>Doctor</th>
                <th>From</th>
                <th>TO</th>
              
                <th>Action</th>
            </tr>
        </thead>
            <tbody >
                <?php $data=$newMaster->getTimeTable('Monday');
                while($res=mysqli_fetch_assoc($data)){
                ?>
            <tr id='mo_addr0'>
            <td>     <?= $res['dept_name']?>           </td>
                <td>
                    <select type="text" class="form-control" id="mo_doctor<?= $res['id']?>" style="display:none"  >
        
                    <option value="">Select Doctor</option>
                    <?php
                    $doctor=$newMaster->selectDoctor();
                    while($row=mysqli_fetch_assoc($doctor)){
                    ?>
                    <option value="<?= $row['id']?>" <?php if($res['doctor_id']==$row['id']) echo "SELECTED"?>><?= $row['name'].' '.$row['last_name']?></option>
                    <?php } ?>
                    
        </select>
        <span id="mo_doctor1<?= $res['id']?>"><?= $res['name'].' '.$res['last_name']?></span>
                </td>
                <td>

                <!-- Input group, just add class 'clockpicker', and optional data-* -->

                <div id="mo_start<?= $res['id']?>" class="input-group clockpicker  mx-auto" style="width: 200px; display:none;" data-placement="bottom" data-align="top" data-autoclose="true">
                    <input type="text"  class="form-control" placeholder="00:00" id="mo_from<?= $res['id']?>" value="<?= $res['start_time']?>"  > <span class="input-group-append"><span class="input-group-text"><i class="fa fa-clock-o"></i></span></span>
                
</div>
              <span id="mo_start1<?= $res['id']?>"><?= $res['start_time']?></span>
                  
                </td>
                <td class="text-center">
                <div id="mo_end<?= $res['id']?>" class="input-group clockpicker mx-auto" style="width: 200px; display:none" data-placement="bottom" data-align="top" data-autoclose="true" >
                    <input type="text" class="form-control" placeholder="00:00" id="mo_to<?= $res['id']?>" value="<?= $res['end_time']?>"> <span class="input-group-append"><span class="input-group-text"><i class="fa fa-clock-o"></i></span></span>
              </div>
              <span id="mo_end1<?= $res['id']?>"><?= $res['end_time']?></span>
                </td>
               
                <td class=" text-center">
                
               <a class="btn btn-success m-1 text-light"  type="button" id="mo_edit<?= $res['id']?>" onclick="changeSave('mo','<?= $res[id]?>')"   >  Edit</a>
               <a class="btn btn-success m-1 text-light"  type="button" id="mo_save<?= $res['id']?>" onclick="updateSave('mo','<?= $res[id]?>')"   style="display:none;">  Save</a>
                </td>
                
            </tr>
            <?php } ?>
            </tbody>
           
          
     
            </table>
            </div>
            <!--<button type="submit" name="day" value="Monday" class="btn btn-success float-right m-1"><i class="icofont-diskette"></i> Save</button> -->
        </div>
        </div>
<!--</form>-->

  </div>
  <div class="tab-pane fade" id="pills-tuesday" role="tabpanel" aria-labelledby="pills-tuesday-tab">
      <!--<form action="controller/processTimetable.php" method="post">-->
  <div class="row" id="tuesday">
        <div class="col-md-12">
            <div class="table-responsive">
            <table class="table table-bordered" width="100%"   id="tu_tab_logic">
     <thead>
            <tr class="text-center"> 
                <th>Department</th>
                <th>Doctor</th>
                <th>From</th>
                <th>TO</th>
              
                <th>Action</th>
            </tr>
        </thead>
                    <tbody >
                <?php $data=$newMaster->getTimeTable('Tuesday');
                while($res=mysqli_fetch_assoc($data)){
                ?>
            <tr id='tu_addr0'>
            <td>     <?= $res['dept_name']?>           </td>
                <td>
                    <select type="text" class="form-control" id="tu_doctor<?= $res['id']?>" style="display:none"  >
        
                    <option value="">Select Doctor</option>
                    <?php
                    $doctor=$newMaster->selectDoctor();
                    while($row=mysqli_fetch_assoc($doctor)){
                    ?>
                    <option value="<?= $row['id']?>" <?php if($res['doctor_id']==$row['id']) echo "SELECTED"?>><?= $row['name'].' '.$row['last_name']?></option>
                    <?php } ?>
                    
        </select>
        <span id="tu_doctor1<?= $res['id']?>"><?= $res['name'].' '.$res['last_name']?></span>
                </td>
                <td>

                <!-- Input group, just add class 'clockpicker', and optional data-* -->

                <div id="tu_start<?= $res['id']?>" class="input-group clockpicker  mx-auto" style="width: 200px; display:none;" data-placement="bottom" data-align="top" data-autoclose="true">
                    <input type="text"  class="form-control" placeholder="00:00" id="tu_from<?= $res['id']?>" value="<?= $res['start_time']?>"  > <span class="input-group-append"><span class="input-group-text"><i class="fa fa-clock-o"></i></span></span>
                
</div>
              <span id="tu_start1<?= $res['id']?>"><?= $res['start_time']?></span>
                  
                </td>
                <td class="text-center">
                <div id="tu_end<?= $res['id']?>" class="input-group clockpicker mx-auto" style="width: 200px; display:none" data-placement="bottom" data-align="top" data-autoclose="true" >
                    <input type="text" class="form-control" placeholder="00:00" id="tu_to<?= $res['id']?>" value="<?= $res['end_time']?>"> <span class="input-group-append"><span class="input-group-text"><i class="fa fa-clock-o"></i></span></span>
              </div>
              <span id="tu_end1<?= $res['id']?>"><?= $res['end_time']?></span>
                </td>
               
                <td class=" text-center">
                
               <a class="btn btn-success m-1 text-light"  type="button" id="tu_edit<?= $res['id']?>" onclick="changeSave('tu','<?= $res[id]?>')"   >  Edit</a>
               <a class="btn btn-success m-1 text-light"  type="button" id="tu_save<?= $res['id']?>" onclick="updateSave('tu','<?= $res[id]?>')"   style="display:none;">  Save</a>
                </td>
                
            </tr>
            <?php } ?>
            </tbody>
            </table>
            </div>
            <!--<button type="submit" name="day" value="Tuesday" class="btn btn-success float-right m-1"><i class="icofont-diskette"></i> Save</button> -->
        </div>
        </div>
<!--</form>-->

  </div>
  <div class="tab-pane fade" id="pills-wed" role="tabpanel" aria-labelledby="pills-web-tab">
      <!--<form action="controller/processTimetable.php" method="post">-->
  <div class="row" id="wednesday">
        <div class="col-md-12">
            <div class="table-responsive">
            <table class="table table-bordered" width="100%"   id="we_tab_logic">
     <thead>
            <tr class="text-center"> 
                <th>Department</th>
                <th>Doctor</th>
                <th>From</th>
                <th>TO</th>
              
                <th>Action</th>
            </tr>
        </thead>
             <tbody >
                <?php $data=$newMaster->getTimeTable('Wednesday');
                while($res=mysqli_fetch_assoc($data)){
                ?>
            <tr id='we_addr0'>
            <td>     <?= $res['dept_name']?>           </td>
                <td>
                    <select type="text" class="form-control" id="we_doctor<?= $res['id']?>" style="display:none"  >
        
                    <option value="">Select Doctor</option>
                    <?php
                    $doctor=$newMaster->selectDoctor();
                    while($row=mysqli_fetch_assoc($doctor)){
                    ?>
                    <option value="<?= $row['id']?>" <?php if($res['doctor_id']==$row['id']) echo "SELECTED"?>><?= $row['name'].' '.$row['last_name']?></option>
                    <?php } ?>
                    
        </select>
        <span id="we_doctor1<?= $res['id']?>"><?= $res['name'].' '.$res['last_name']?></span>
                </td>
                <td>

                <!-- Input group, just add class 'clockpicker', and optional data-* -->

                <div id="we_start<?= $res['id']?>" class="input-group clockpicker  mx-auto" style="width: 200px; display:none;" data-placement="bottom" data-align="top" data-autoclose="true">
                    <input type="text"  class="form-control" placeholder="00:00" id="we_from<?= $res['id']?>" value="<?= $res['start_time']?>"  > <span class="input-group-append"><span class="input-group-text"><i class="fa fa-clock-o"></i></span></span>
                
</div>
              <span id="we_start1<?= $res['id']?>"><?= $res['start_time']?></span>
                  
                </td>
                <td class="text-center">
                <div id="we_end<?= $res['id']?>" class="input-group clockpicker mx-auto" style="width: 200px; display:none" data-placement="bottom" data-align="top" data-autoclose="true" >
                    <input type="text" class="form-control" placeholder="00:00" id="we_to<?= $res['id']?>" value="<?= $res['end_time']?>"> <span class="input-group-append"><span class="input-group-text"><i class="fa fa-clock-o"></i></span></span>
              </div>
              <span id="we_end1<?= $res['id']?>"><?= $res['end_time']?></span>
                </td>
               
                <td class=" text-center">
                
               <a class="btn btn-success m-1 text-light"  type="button" id="we_edit<?= $res['id']?>" onclick="changeSave('we','<?= $res[id]?>')"   >  Edit</a>
               <a class="btn btn-success m-1 text-light"  type="button" id="we_save<?= $res['id']?>" onclick="updateSave('we','<?= $res[id]?>')"   style="display:none;">  Save</a>
                </td>
                
            </tr>
            <?php } ?>
            </tbody>
            </table>
            </div>
            <!--<button type="submit" name="day" value="Wednesday" class="btn btn-success float-right m-1"><i class="icofont-diskette"></i> Save</button> -->
        </div>
        </div>
<!--</form>-->

  </div>
  <div class="tab-pane fade" id="pills-thursday" role="tabpanel" aria-labelledby="pills-thursday-tab">
      <!--<form action="controller/processTimetable.php" method="post">-->
  <div class="row" id="thursday">
        <div class="col-md-12">
            <div class="table-responsive">
            <table class="table table-bordered" width="100%"   id="th_tab_logic">
     <thead>
            <tr class="text-center"> 
                <th>Department</th>
                <th>Doctor</th>
                <th>From</th>
                <th>TO</th>
              
                <th>Action</th>
            </tr>
        </thead>
             <tbody >
                <?php $data=$newMaster->getTimeTable('Thursday');
                while($res=mysqli_fetch_assoc($data)){
                ?>
            <tr id='th_addr0'>
            <td>     <?= $res['dept_name']?>           </td>
                <td>
                    <select type="text" class="form-control" id="th_doctor<?= $res['id']?>" style="display:none"  >
        
                    <option value="">Select Doctor</option>
                    <?php
                    $doctor=$newMaster->selectDoctor();
                    while($row=mysqli_fetch_assoc($doctor)){
                    ?>
                    <option value="<?= $row['id']?>" <?php if($res['doctor_id']==$row['id']) echo "SELECTED"?>><?= $row['name'].' '.$row['last_name']?></option>
                    <?php } ?>
                    
        </select>
        <span id="th_doctor1<?= $res['id']?>"><?= $res['name'].' '.$res['last_name']?></span>
                </td>
                <td>

                <!-- Input group, just add class 'clockpicker', and optional data-* -->

                <div id="th_start<?= $res['id']?>" class="input-group clockpicker  mx-auto" style="width: 200px; display:none;" data-placement="bottom" data-align="top" data-autoclose="true">
                    <input type="text"  class="form-control" placeholder="00:00" id="th_from<?= $res['id']?>" value="<?= $res['start_time']?>"  > <span class="input-group-append"><span class="input-group-text"><i class="fa fa-clock-o"></i></span></span>
                
</div>
              <span id="th_start1<?= $res['id']?>"><?= $res['start_time']?></span>
                  
                </td>
                <td class="text-center">
                <div id="th_end<?= $res['id']?>" class="input-group clockpicker mx-auto" style="width: 200px; display:none" data-placement="bottom" data-align="top" data-autoclose="true" >
                    <input type="text" class="form-control" placeholder="00:00" id="th_to<?= $res['id']?>" value="<?= $res['end_time']?>"> <span class="input-group-append"><span class="input-group-text"><i class="fa fa-clock-o"></i></span></span>
              </div>
              <span id="th_end1<?= $res['id']?>"><?= $res['end_time']?></span>
                </td>
               
                <td class=" text-center">
                
               <a class="btn btn-success m-1 text-light"  type="button" id="th_edit<?= $res['id']?>" onclick="changeSave('th','<?= $res[id]?>')"   >  Edit</a>
               <a class="btn btn-success m-1 text-light"  type="button" id="th_save<?= $res['id']?>" onclick="updateSave('th','<?= $res[id]?>')"   style="display:none;">  Save</a>
                </td>
                
            </tr>
            <?php } ?>
            </tbody>
            </table>
            </div>
            <!--<button type="submit" name="day" value="Thursday" class="btn btn-success float-right m-1"><i class="icofont-diskette"></i> Save</button> -->
        </div>
        </div>
<!--</form>-->

  </div>
  <div class="tab-pane fade" id="pills-friday" role="tabpanel" aria-labelledby="pills-web-friday"> 
  <!--<form action="controller/processTimetable.php" method="post">-->
  <div class="row" id="friday">
        <div class="col-md-12">
            <div class="table-responsive">
            <table class="table table-bordered" width="100%"   id="fr_tab_logic">
     <thead>
            <tr class="text-center"> 
                <th>Department</th>
                <th>Doctor</th>
                <th>From</th>
                <th>TO</th>
              
                <th>Action</th>
            </tr>
        </thead>
             <tbody >
                <?php $data=$newMaster->getTimeTable('Friday');
                while($res=mysqli_fetch_assoc($data)){
                ?>
            <tr id='fr_addr0'>
            <td>     <?= $res['dept_name']?>           </td>
                <td>
                    <select type="text" class="form-control" id="fr_doctor<?= $res['id']?>" style="display:none"  >
        
                    <option value="">Select Doctor</option>
                    <?php
                    $doctor=$newMaster->selectDoctor();
                    while($row=mysqli_fetch_assoc($doctor)){
                    ?>
                    <option value="<?= $row['id']?>" <?php if($res['doctor_id']==$row['id']) echo "SELECTED"?>><?= $row['name'].' '.$row['last_name']?></option>
                    <?php } ?>
                    
        </select>
        <span id="fr_doctor1<?= $res['id']?>"><?= $res['name'].' '.$res['last_name']?></span>
                </td>
                <td>

                <!-- Input group, just add class 'clockpicker', and optional data-* -->

                <div id="fr_start<?= $res['id']?>" class="input-group clockpicker  mx-auto" style="width: 200px; display:none;" data-placement="bottom" data-align="top" data-autoclose="true">
                    <input type="text"  class="form-control" placeholder="00:00" id="fr_from<?= $res['id']?>" value="<?= $res['start_time']?>"  > <span class="input-group-append"><span class="input-group-text"><i class="fa fa-clock-o"></i></span></span>
                
</div>
              <span id="fr_start1<?= $res['id']?>"><?= $res['start_time']?></span>
                  
                </td>
                <td class="text-center">
                <div id="fr_end<?= $res['id']?>" class="input-group clockpicker mx-auto" style="width: 200px; display:none" data-placement="bottom" data-align="top" data-autoclose="true" >
                    <input type="text" class="form-control" placeholder="00:00" id="fr_to<?= $res['id']?>" value="<?= $res['end_time']?>"> <span class="input-group-append"><span class="input-group-text"><i class="fa fa-clock-o"></i></span></span>
              </div>
              <span id="fr_end1<?= $res['id']?>"><?= $res['end_time']?></span>
                </td>
               
                <td class=" text-center">
                
               <a class="btn btn-success m-1 text-light"  type="button" id="fr_edit<?= $res['id']?>" onclick="changeSave('fr','<?= $res[id]?>')"   >  Edit</a>
               <a class="btn btn-success m-1 text-light"  type="button" id="fr_save<?= $res['id']?>" onclick="updateSave('fr','<?= $res[id]?>')"   style="display:none;">  Save</a>
                </td>
                
            </tr>
            <?php } ?>
            </tbody>
            </table>
            </div>
            <!--<button type="submit" name="day" value="Friday" class="btn btn-success float-right m-1"><i class="icofont-diskette"></i> Save</button> -->
        </div>
        </div>
<!--</form>-->
</div>
  <div class="tab-pane fade" id="pills-saturday" role="tabpanel" aria-labelledby="pills-web-saturday">
      <!--<form action="controller/processTimetable.php" method="post">-->
  <div class="row" id="saturday">
        <div class="col-md-12">
            <div class="table-responsive">
            <table class="table table-bordered" width="100%"   id="sa_tab_logic">
     <thead>
            <tr class="text-center"> 
                <th>Department</th>
                <th>Doctor</th>
                <th>From</th>
                <th>TO</th>
              
                <th>Action</th>
            </tr>
        </thead>
    <tbody >
                <?php $data=$newMaster->getTimeTable('Saturday');
                while($res=mysqli_fetch_assoc($data)){
                ?>
            <tr id='sa_addr0'>
            <td>     <?= $res['dept_name']?>           </td>
                <td>
                    <select type="text" class="form-control" id="sa_doctor<?= $res['id']?>" style="display:none"  >
        
                    <option value="">Select Doctor</option>
                    <?php
                    $doctor=$newMaster->selectDoctor();
                    while($row=mysqli_fetch_assoc($doctor)){
                    ?>
                    <option value="<?= $row['id']?>" <?php if($res['doctor_id']==$row['id']) echo "SELECTED"?>><?= $row['name'].' '.$row['last_name']?></option>
                    <?php } ?>
                    
        </select>
        <span id="sa_doctor1<?= $res['id']?>"><?= $res['name'].' '.$res['last_name']?></span>
                </td>
                <td>

                <!-- Input group, just add class 'clockpicker', and optional data-* -->

                <div id="sa_start<?= $res['id']?>" class="input-group clockpicker  mx-auto" style="width: 200px; display:none;" data-placement="bottom" data-align="top" data-autoclose="true">
                    <input type="text"  class="form-control" placeholder="00:00" id="sa_from<?= $res['id']?>" value="<?= $res['start_time']?>"  > <span class="input-group-append"><span class="input-group-text"><i class="fa fa-clock-o"></i></span></span>
                
</div>
              <span id="sa_start1<?= $res['id']?>"><?= $res['start_time']?></span>
                  
                </td>
                <td class="text-center">
                <div id="sa_end<?= $res['id']?>" class="input-group clockpicker mx-auto" style="width: 200px; display:none" data-placement="bottom" data-align="top" data-autoclose="true" >
                    <input type="text" class="form-control" placeholder="00:00" id="sa_to<?= $res['id']?>" value="<?= $res['end_time']?>"> <span class="input-group-append"><span class="input-group-text"><i class="fa fa-clock-o"></i></span></span>
              </div>
              <span id="sa_end1<?= $res['id']?>"><?= $res['end_time']?></span>
                </td>
               
                <td class=" text-center">
                
               <a class="btn btn-success m-1 text-light"  type="button" id="sa_edit<?= $res['id']?>" onclick="changeSave('sa','<?= $res[id]?>')"   >  Edit</a>
               <a class="btn btn-success m-1 text-light"  type="button" id="sa_save<?= $res['id']?>" onclick="updateSave('sa','<?= $res[id]?>')"   style="display:none;">  Save</a>
                </td>
                
            </tr>
            <?php } ?>
            </tbody>
            </table>
            </div>
            <!--<button type="submit" name="day" value="Saturday" class="btn btn-success float-right m-1"><i class="icofont-diskette"></i> Save</button> -->
        </div>
        </div>
<!--</form>-->

  </div>
  <div class="tab-pane fade" id="pills-sunday" role="tabpanel" aria-labelledby="pills-sunday-tab">
      <!--<form action="controller/processTimetable.php" method="post">-->
  <div class="row" id="sunday">
        <div class="col-md-12">
            <div class="table-responsive">
            <table class="table table-bordered" width="100%"   id="su_tab_logic">
     <thead>
            <tr class="text-center"> 
                <th>Department</th>
                <th>Doctor</th>
                <th>From</th>
                <th>TO</th>
              
                <th>Action</th>
            </tr>
        </thead>
             <tbody >
                <?php $data=$newMaster->getTimeTable('Sunday');
                while($res=mysqli_fetch_assoc($data)){
                ?>
            <tr id='su_addr0'>
            <td>     <?= $res['dept_name']?>           </td>
                <td>
                    <select type="text" class="form-control" id="su_doctor<?= $res['id']?>" style="display:none"  >
        
                    <option value="">Select Doctor</option>
                    <?php
                    $doctor=$newMaster->selectDoctor();
                    while($row=mysqli_fetch_assoc($doctor)){
                    ?>
                    <option value="<?= $row['id']?>" <?php if($res['doctor_id']==$row['id']) echo "SELECTED"?>><?= $row['name'].' '.$row['last_name']?></option>
                    <?php } ?>
                    
        </select>
        <span id="su_doctor1<?= $res['id']?>"><?= $res['name'].' '.$res['last_name']?></span>
                </td>
                <td>

                <!-- Input group, just add class 'clockpicker', and optional data-* -->

                <div id="su_start<?= $res['id']?>" class="input-group clockpicker  mx-auto" style="width: 200px; display:none;" data-placement="bottom" data-align="top" data-autoclose="true">
                    <input type="text"  class="form-control" placeholder="00:00" id="su_from<?= $res['id']?>" value="<?= $res['start_time']?>"  > <span class="input-group-append"><span class="input-group-text"><i class="fa fa-clock-o"></i></span></span>
                
</div>
              <span id="su_start1<?= $res['id']?>"><?= $res['start_time']?></span>
                  
                </td>
                <td class="text-center">
                <div id="su_end<?= $res['id']?>" class="input-group clockpicker mx-auto" style="width: 200px; display:none" data-placement="bottom" data-align="top" data-autoclose="true" >
                    <input type="text" class="form-control" placeholder="00:00" id="su_to<?= $res['id']?>" value="<?= $res['end_time']?>"> <span class="input-group-append"><span class="input-group-text"><i class="fa fa-clock-o"></i></span></span>
              </div>
              <span id="su_end1<?= $res['id']?>"><?= $res['end_time']?></span>
                </td>
               
                <td class=" text-center">
                
               <a class="btn btn-success m-1 text-light"  type="button" id="su_edit<?= $res['id']?>" onclick="changeSave('su','<?= $res[id]?>')"   >  Edit</a>
               <a class="btn btn-success m-1 text-light"  type="button" id="su_save<?= $res['id']?>" onclick="updateSave('su','<?= $res[id]?>')"   style="display:none;">  Save</a>
                </td>
                
            </tr>
            <?php } ?>
            </tbody>
            </table>
            </div>
            <!--<button type="submit" name="day" value="Sunday" class="btn btn-success float-right m-1"><i class="icofont-diskette"></i> Save</button> -->
        </div>
        </div>

<!--</form>-->
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
    <!--  vendors -->
    <script src="./vendor/global/global.min.js"></script>
    <script src="./js/quixnav-init.js"></script>
    <script src="./js/custom.min.js"></script>
    <script src="./js/custom.min.js"></script>

 
    



<style>
    ..popover{
        max-width:250px;
    }
</style>











<!-- ClockPicker script -->
<script type="text/javascript" src="clockpicker-gh-pages/dist/bootstrap-clockpicker.min.js"></script>


<script type="text/javascript">

$('.clockpicker').clockpicker({ twelvehour: true })


</script>


<script>
function changeSave(day,id)
{
    document.getElementById(day+'_edit'+id).style.display='none';
    document.getElementById(day+'_save'+id).style.display='block';
    
    document.getElementById(day+'_doctor1'+id).style.display='none';
    document.getElementById(day+'_doctor'+id).style.display='block';
    
    document.getElementById(day+'_edit'+id).style.display='none';
    document.getElementById(day+'_save'+id).style.display='block';
    
    document.getElementById(day+'_start1'+id).style.display='none';
    document.getElementById(day+'_start'+id).style.display='';
    
    document.getElementById(day+'_end1'+id).style.display='none';
    document.getElementById(day+'_end'+id).style.display='';
}
 function updateSave(val,id)
    {
        var doctor=document.getElementById(val+'_doctor'+id).value;
        var from=document.getElementById(val+'_from'+id).value;
        var to=document.getElementById(val+'_to'+id).value;
         $.ajax({
            type : 'post',
            url : 'ajax.php', //Here you will fetch records 
            data :  'id='+id+'&from='+from+'&to='+to+'&doctor='+doctor+'&page=TimeTable', //Pass $id
            success : function(data){
                if(val=='mo')
                {
                    $('#pills-tabContent').load(" #monday");
                }else if(val=='tu'){
                    $('#pills-tabContent').load(" #tuesday");
                }else if(val=='we'){
                    $('#pills-tabContent').load(" #wednesday");
                }else if(val=='th'){
                    $('#pills-tabContent').load(" #thursday");
                }else if(val=='fr'){
                    $('#pills-tabContent').load(" #friday");
                }else if(val=='sa'){
                    $('#pills-tabContent').load(" #saturday");
                }else if(val=='su'){
                    $('#pills-tabContent').load(" #sunday");
                }
        
            }
        });
    }
</script>

</body>

</html>