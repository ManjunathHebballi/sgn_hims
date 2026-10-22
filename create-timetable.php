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
    <title>Create Time Table</title>
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

<div aria-live="polite" id="notification" aria-atomic="true" >
<?php if(isset($_REQUEST['success']))
{ ?>
    
<div class="alert alert-success solid alert-dismissible fade show">
                                    <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span>
                                    </button>
                                    <strong>Success!</strong> OP Patient inserted
                                </div>
                         <?php   }
                            ?>
</div>

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
            <h4 class="text-uppercase">Create Time Table</h4>
        </div>
    </div>


    <div class="row">
        

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
  <div class="tab-pane fade show active" id="pills-monday" role="tabpanel" aria-labelledby="pills-monday-tab">
<form action="controller/processTimetable.php" method="post">
  <div class="row">
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
            <tbody>
            <tr id='mo_addr0'>
            <td>
                    <select type="text" class="form-control" name="mo_dept[]" id="mo_dept[]"  >
                        <option value="">Select Department</option>
                    <?php
                    $dept=$newMaster->selectDept();
                    while($row=mysqli_fetch_assoc($dept)){
                    ?>
                    <option value="<?= $row['id']?>"><?= $row['dept_name']?></option>
                    <?php } ?>
        </select>
                </td>
                <td>
                    <select type="text" class="form-control" name="mo_dctor[]" id="mo_dctor[]"  >
        
                    <option value="">Select Doctor</option>
                    <?php
                    $doctor=$newMaster->selectDoctor();
                    while($row=mysqli_fetch_assoc($doctor)){
                    ?>
                    <option value="<?= $row['id']?>"><?= $row['name'].' '.$row['last_name']?></option>
                    <?php } ?>
        </select>
                </td>
                <td>

                <!-- Input group, just add class 'clockpicker', and optional data-* -->

                <div class="input-group clockpicker" style="width: 200px;" data-placement="bottom" data-align="top" data-autoclose="true">
                    <input type="text" name="mo_from[]" class="form-control" placeholder="00:00" id="mo_from[]" > <span class="input-group-append"><span class="input-group-text"><i class="fa fa-clock-o"></i></span></span>
                
</div>
              
                  
                </td>
                <td class="text-center">
                <div class="input-group clockpicker" style="width: 200px;" data-placement="bottom" data-align="top" data-autoclose="true">
                    <input type="text" name="mo_to[]" class="form-control" placeholder="00:00" id="mo_to[]" > <span class="input-group-append"><span class="input-group-text"><i class="fa fa-clock-o"></i></span></span>
              
                </td>
               
                <td class=" text-center">
                
               <a class="btn btn-danger m-1 text-light"  type="button" id='delete_row' name='dlt[]' onclick="dlt('mo',0)" >  <i class="icofont-ui-delete "  data-toggle="tooltip" data-placement="top" title="Delete"></i></a>
                </td>
                
            </tr>
            <tr id='mo_addr1'></tr>
            </tbody>
            <!-- </table>
            <table class="table table-responsive table-bordered"   id="tab_logic"> -->
            <tfoot> 
            <!-- <tr id='addr110'></tr> -->
            
            
            <tr>
        
                <td colspan="9"> 
                <input type='hidden' id="mo_id" value=1 >
                <a id="add_row" onclick="addNewRow('mo')" type="button" data-toggle="tooltip" title="Add New Row" class="btn btn-success text-light float-right m-1"><i class="icofont-plus" ></i> Add Row</a> </td>
               
                
            </tr>
        </tfoot>
            </table>
            </div>
            <button type="submit" name="day" value="Monday" class="btn btn-success float-right m-1"><i class="icofont-diskette"></i> Save</button> 
        </div>
        </div>
</form>

  </div>
  <div class="tab-pane fade" id="pills-tuesday" role="tabpanel" aria-labelledby="pills-tuesday-tab">
      <form action="controller/processTimetable.php" method="post">
  <div class="row">
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
            <tbody>
            <tr id='tu_addr0'>
            <td>
                    <select type="text" class="form-control" name="tu_dept[]" id="tu_dept[]"  >
        
                    <option value="">Select Department</option>
                      <?php
                    $dept=$newMaster->selectDept();
                    while($row=mysqli_fetch_assoc($dept)){
                    ?>
                    <option value="<?= $row['id']?>"><?= $row['dept_name']?></option>
                    <?php } ?>
        </select>
                </td>
                <td>
                    <select type="text" class="form-control" name="tu_dctor[]" id="tu_dctor[]"  >
        
                    <option value="">Select Doctor</option>
                    <?php
                    $doctor=$newMaster->selectDoctor();
                    while($row=mysqli_fetch_assoc($doctor)){
                    ?>
                    <option value="<?= $row['id']?>"><?= $row['name'].' '.$row['last_name']?></option>
                    <?php } ?>
        </select>
                </td>
                <td>

                <!-- Input group, just add class 'clockpicker', and optional data-* -->

                <div class="input-group clockpicker" style="width: 200px;" data-placement="bottom" data-align="top" data-autoclose="true">
                    <input type="text" name="tu_from[]" class="form-control" placeholder="00:00" id="tu_from[]" > <span class="input-group-append"><span class="input-group-text"><i class="fa fa-clock-o"></i></span></span>
                
</div>
              
                  
                </td>
                <td class="text-center">
                <div class="input-group clockpicker" style="width: 200px;" data-placement="bottom" data-align="top" data-autoclose="true">
                    <input type="text" name="tu_to[]" class="form-control" placeholder="00:00" id="tu_to[]" > <span class="input-group-append"><span class="input-group-text"><i class="fa fa-clock-o"></i></span></span>
              
                </td>
               
                <td class=" text-center">
                
               <a class="btn btn-danger m-1 text-light"  type="button" id='delete_row' name='dlt[]' onclick="dlt('tu',0)" >  <i class="icofont-ui-delete "  data-toggle="tooltip" data-placement="top" title="Delete"></i></a>
                </td>
                
            </tr>
            <tr id='tu_addr1'></tr>
            </tbody>
            <!-- </table>
            <table class="table table-responsive table-bordered"   id="tab_logic"> -->
            <tfoot> 
            <!-- <tr id='addr110'></tr> -->
            
            
            <tr>
        
                <td colspan="9"> 
                <input type='hidden' id="tu_id" value=1 >
                <a id="add_row" onclick="addNewRow('tu')" type="button" data-toggle="tooltip" title="Add New Row" class="btn btn-success text-light float-right m-1"><i class="icofont-plus" ></i> Add Row</a> </td>
               
                
            </tr>
        </tfoot>
            </table>
            </div>
            <button type="submit" name="day" value="Tuesday" class="btn btn-success float-right m-1"><i class="icofont-diskette"></i> Save</button> 
        </div>
        </div>
</form>

  </div>
  <div class="tab-pane fade" id="pills-wed" role="tabpanel" aria-labelledby="pills-web-tab">
      <form action="controller/processTimetable.php" method="post">
  <div class="row">
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
            <tbody>
            <tr id='we_addr0'>
            <td>
                    <select type="text" class="form-control" name="we_dept[]" id="we_dept[]"  >
        
                    <option value="">Select Department</option>
                      <?php
                    $dept=$newMaster->selectDept();
                    while($row=mysqli_fetch_assoc($dept)){
                    ?>
                    <option value="<?= $row['id']?>"><?= $row['dept_name']?></option>
                    <?php } ?>
        </select>
                </td>
                <td>
                    <select type="text" class="form-control" name="we_dctor[]" id="we_dctor[]"  >
        
                    <option value="">Select Doctor</option>
                    <?php
                    $doctor=$newMaster->selectDoctor();
                    while($row=mysqli_fetch_assoc($doctor)){
                    ?>
                    <option value="<?= $row['id']?>"><?= $row['name'].' '.$row['last_name']?></option>
                    <?php } ?>
        </select>
                </td>
                <td>

                <!-- Input group, just add class 'clockpicker', and optional data-* -->

                <div class="input-group clockpicker" style="width: 200px;" data-placement="bottom" data-align="top" data-autoclose="true">
                    <input type="text" name="we_from[]" class="form-control" placeholder="00:00" id="we_from[]" > <span class="input-group-append"><span class="input-group-text"><i class="fa fa-clock-o"></i></span></span>
                
</div>
              
                  
                </td>
                <td class="text-center">
                <div class="input-group clockpicker" style="width: 200px;" data-placement="bottom" data-align="top" data-autoclose="true">
                    <input type="text" name="we_to[]" class="form-control" placeholder="00:00" id="we_to[]" > <span class="input-group-append"><span class="input-group-text"><i class="fa fa-clock-o"></i></span></span>
              
                </td>
               
                <td class=" text-center">
                
               <a class="btn btn-danger m-1 text-light"  type="button" id='delete_row' name='dlt[]' onclick="dlt('we',0)" >  <i class="icofont-ui-delete "  data-toggle="tooltip" data-placement="top" title="Delete"></i></a>
                </td>
                
            </tr>
            <tr id='we_addr1'></tr>
            </tbody>
            <!-- </table>
            <table class="table table-responsive table-bordered"   id="tab_logic"> -->
            <tfoot> 
            <!-- <tr id='addr110'></tr> -->
            
            
            <tr>
        <input type='hidden' id="we_id" value=1 >
                <td colspan="9"> <a id="add_row" onclick="addNewRow('we')" type="button" data-toggle="tooltip" title="Add New Row" class="btn btn-success text-light float-right m-1"><i class="icofont-plus" ></i> Add Row</a> </td>
               
                
            </tr>
        </tfoot>
            </table>
            </div>
            <button type="submit" name="day" value="Wednesday" class="btn btn-success float-right m-1"><i class="icofont-diskette"></i> Save</button> 
        </div>
        </div>
</form>

  </div>
  <div class="tab-pane fade" id="pills-thursday" role="tabpanel" aria-labelledby="pills-thursday-tab">
      <form action="controller/processTimetable.php" method="post">
  <div class="row">
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
            <tbody>
            <tr id='th_addr0'>
            <td>
                    <select type="text" class="form-control" name="th_dept[]" id="th_dept[]"  >
        
                    <option value="">Select Department</option>
                      <?php
                    $dept=$newMaster->selectDept();
                    while($row=mysqli_fetch_assoc($dept)){
                    ?>
                    <option value="<?= $row['id']?>"><?= $row['dept_name']?></option>
                    <?php } ?>
        </select>
                </td>
                <td>
                    <select type="text" class="form-control" name="th_dctor[]" id="th_dctor[]"  >
        
                    <option value="">Select Doctor</option>
                    <?php
                    $doctor=$newMaster->selectDoctor();
                    while($row=mysqli_fetch_assoc($doctor)){
                    ?>
                    <option value="<?= $row['id']?>"><?= $row['name'].' '.$row['last_name']?></option>
                    <?php } ?>
        </select>
                </td>
                <td>

                <!-- Input group, just add class 'clockpicker', and optional data-* -->

                <div class="input-group clockpicker" style="width: 200px;" data-placement="bottom" data-align="top" data-autoclose="true">
                    <input type="text" name="th_from[]" class="form-control" placeholder="00:00" id="th_from[]" > <span class="input-group-append"><span class="input-group-text"><i class="fa fa-clock-o"></i></span></span>
                
</div>
              
                  
                </td>
                <td class="text-center">
                <div class="input-group clockpicker" style="width: 200px;" data-placement="bottom" data-align="top" data-autoclose="true">
                    <input type="text" name="th_to[]" class="form-control" placeholder="00:00" id="th_to[]" > <span class="input-group-append"><span class="input-group-text"><i class="fa fa-clock-o"></i></span></span>
              
                </td>
               
                <td class=" text-center">
                
               <a class="btn btn-danger m-1 text-light"  type="button" id='delete_row' name='dlt[]' onclick="dlt('th',0)" >  <i class="icofont-ui-delete "  data-toggle="tooltip" data-placement="top" title="Delete"></i></a>
                </td>
                
            </tr>
            <tr id='th_addr1'></tr>
            </tbody>
            <!-- </table>
            <table class="table table-responsive table-bordered"   id="tab_logic"> -->
            <tfoot> 
            <!-- <tr id='addr110'></tr> -->
            
            
            <tr>
        
                <td colspan="9">
                    <input type='hidden' id="th_id" value=1 >
                    <a id="add_row" onclick="addNewRow('th')"  type="button" data-toggle="tooltip" title="Add New Row" class="btn btn-success text-light float-right m-1"><i class="icofont-plus" ></i> Add Row</a> </td>
               
                
            </tr>
        </tfoot>
            </table>
            </div>
            <button type="submit" name="day" value="Thursday" class="btn btn-success float-right m-1"><i class="icofont-diskette"></i> Save</button> 
        </div>
        </div>
</form>

  </div>
  <div class="tab-pane fade" id="pills-friday" role="tabpanel" aria-labelledby="pills-web-friday"> 
  <form action="controller/processTimetable.php" method="post">
  <div class="row">
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
            <tbody>
            <tr id='fr_addr0'>
            <td>
                    <select type="text" class="form-control" name="fr_dept[]" id="fr_dept[]"  >
        
                    <option value="">Select Department</option>
                      <?php
                    $dept=$newMaster->selectDept();
                    while($row=mysqli_fetch_assoc($dept)){
                    ?>
                    <option value="<?= $row['id']?>"><?= $row['dept_name']?></option>
                    <?php } ?>
        </select>
                </td>
                <td>
                    <select type="text" class="form-control" name="fr_dctor[]" id="fr_dctor[]"  >
        
                    <option value="">Select Doctor</option>
                    <?php
                    $doctor=$newMaster->selectDoctor();
                    while($row=mysqli_fetch_assoc($doctor)){
                    ?>
                    <option value="<?= $row['id']?>"><?= $row['name'].' '.$row['last_name']?></option>
                    <?php } ?>
        </select>
                </td>
                <td>

                <!-- Input group, just add class 'clockpicker', and optional data-* -->

                <div class="input-group clockpicker" style="width: 200px;" data-placement="bottom" data-align="top" data-autoclose="true">
                    <input type="text" name="fr_from[]" class="form-control" placeholder="00:00" id="fr_from[]" > <span class="input-group-append"><span class="input-group-text"><i class="fa fa-clock-o"></i></span></span>
                
</div>
              
                  
                </td>
                <td class="text-center">
                <div class="input-group clockpicker" style="width: 200px;" data-placement="bottom" data-align="top" data-autoclose="true">
                    <input type="text" name="fr_to[]" class="form-control" placeholder="00:00" id="fr_to[]" > <span class="input-group-append"><span class="input-group-text"><i class="fa fa-clock-o"></i></span></span>
              
                </td>
               
                <td class=" text-center">
                
               <a class="btn btn-danger m-1 text-light"  type="button" id='delete_row' name='dlt[]' onclick="dlt('fr',0)" >  <i class="icofont-ui-delete "  data-toggle="tooltip" data-placement="top" title="Delete"></i></a>
                </td>
                
            </tr>
            <tr id='fr_addr1'></tr>
            </tbody>
            <!-- </table>
            <table class="table table-responsive table-bordered"   id="tab_logic"> -->
            <tfoot> 
            <!-- <tr id='addr110'></tr> -->
            
            
            <tr>
        
                <td colspan="9"> 
                <input type='hidden' id="fr_id" value=1 >
                <a id="add_row" onclick="addNewRow('fr')" type="button" data-toggle="tooltip" title="Add New Row" class="btn btn-success text-light float-right m-1"><i class="icofont-plus" ></i> Add Row</a> </td>
               
                
            </tr>
        </tfoot>
            </table>
            </div>
            <button type="submit" name="day" value="Friday" class="btn btn-success float-right m-1"><i class="icofont-diskette"></i> Save</button> 
        </div>
        </div>
</form>
</div>
  <div class="tab-pane fade" id="pills-saturday" role="tabpanel" aria-labelledby="pills-web-saturday">
      <form action="controller/processTimetable.php" method="post">
  <div class="row">
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
            <tbody>
            <tr id='sa_addr0'>
            <td>
                    <select type="text" class="form-control" name="sa_dept[]" id="sa_dept[]"  >
        
                    <option value="">Select Department</option>
                      <?php
                    $dept=$newMaster->selectDept();
                    while($row=mysqli_fetch_assoc($dept)){
                    ?>
                    <option value="<?= $row['id']?>"><?= $row['dept_name']?></option>
                    <?php } ?>
        </select>
                </td>
                <td>
                    <select type="text" class="form-control" name="sa_dctor[]" id="sa_dctor[]"  >
        
                    <option value="">Select Doctor</option>
                    <?php
                    $doctor=$newMaster->selectDoctor();
                    while($row=mysqli_fetch_assoc($doctor)){
                    ?>
                    <option value="<?= $row['id']?>"><?= $row['name'].' '.$row['last_name']?></option>
                    <?php } ?>
        </select>
                </td>
                <td>

                <!-- Input group, just add class 'clockpicker', and optional data-* -->

                <div class="input-group clockpicker" style="width: 200px;" data-placement="bottom" data-align="top" data-autoclose="true">
                    <input type="text" name="sa_from[]" class="form-control" placeholder="00:00" id="sa_from[]" > <span class="input-group-append"><span class="input-group-text"><i class="fa fa-clock-o"></i></span></span>
                
</div>
              
                  
                </td>
                <td class="text-center">
                <div class="input-group clockpicker" style="width: 200px;" data-placement="bottom" data-align="top" data-autoclose="true">
                    <input type="text" name="sa_to[]" class="form-control" placeholder="00:00" id="sa_to[]" > <span class="input-group-append"><span class="input-group-text"><i class="fa fa-clock-o"></i></span></span>
              
                </td>
               
                <td class=" text-center">
                
               <a class="btn btn-danger m-1 text-light"  type="button" id='delete_row' name='dlt[]' onclick="dlt('sa',0)" >  <i class="icofont-ui-delete "  data-toggle="tooltip" data-placement="top" title="Delete"></i></a>
                </td>
                
            </tr>
            <tr id='sa_addr1'></tr>
            </tbody>
            <!-- </table>
            <table class="table table-responsive table-bordered"   id="tab_logic"> -->
            <tfoot> 
            <!-- <tr id='addr110'></tr> -->
            
            
            <tr>
        <input type='hidden' id="sa_id" value=1 >
                <td colspan="9"> <a id="add_row" onclick="addNewRow('sa')"  type="button" data-toggle="tooltip" title="Add New Row" class="btn btn-success text-light float-right m-1"><i class="icofont-plus" ></i> Add Row</a> </td>
               
                
            </tr>
        </tfoot>
            </table>
            </div>
            <button type="submit" name="day" value="Saturday" class="btn btn-success float-right m-1"><i class="icofont-diskette"></i> Save</button> 
        </div>
        </div>
</form>

  </div>
  <div class="tab-pane fade" id="pills-sunday" role="tabpanel" aria-labelledby="pills-sunday-tab">
      <form action="controller/processTimetable.php" method="post">
  <div class="row">
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
            <tbody>
            <tr id='su_addr0'>
            <td>
                    <select type="text" class="form-control" name="su_dept[]" id="su_dept[]"  >
        
                    <option value="">Select Department</option>
                      <?php
                    $dept=$newMaster->selectDept();
                    while($row=mysqli_fetch_assoc($dept)){
                    ?>
                    <option value="<?= $row['id']?>"><?= $row['dept_name']?></option>
                    <?php } ?>
        </select>
                </td>
                <td>
                    <select type="text" class="form-control" name="su_dctor[]" id="su_dctor[]"  >
        
                    <option value="">Select Doctor</option>
                    <?php
                    $doctor=$newMaster->selectDoctor();
                    while($row=mysqli_fetch_assoc($doctor)){
                    ?>
                    <option value="<?= $row['id']?>"><?= $row['name'].' '.$row['last_name']?></option>
                    <?php } ?>
        </select>
                </td>
                <td>

                <!-- Input group, just add class 'clockpicker', and optional data-* -->

                <div class="input-group clockpicker" style="width: 200px;" data-placement="bottom" data-align="top" data-autoclose="true">
                    <input type="text" name="su_from[]" class="form-control" placeholder="00:00" id="su_from[]" > <span class="input-group-append"><span class="input-group-text"><i class="fa fa-clock-o"></i></span></span>
                
</div>
              
                  
                </td>
                <td class="text-center">
                <div class="input-group clockpicker" style="width: 200px;" data-placement="bottom" data-align="top" data-autoclose="true">
                    <input type="text" name="su_to[]" class="form-control" placeholder="00:00" id="su_to[]" > <span class="input-group-append"><span class="input-group-text"><i class="fa fa-clock-o"></i></span></span>
              
                </td>
               
                <td class=" text-center">
                
               <a class="btn btn-danger m-1 text-light" type="button" id='delete_row' name='dlt[]' onclick="dlt('su',0)" >  <i class="icofont-ui-delete "  data-toggle="tooltip" data-placement="top" title="Delete"></i></a>
                </td>
                
            </tr>
            <tr id='su_addr1'></tr>
            </tbody>
            <!-- </table>
            <table class="table table-responsive table-bordered"   id="tab_logic"> -->
            <tfoot> 
            <!-- <tr id='addr110'></tr> -->
            
            
            <tr>
        <input type='hidden' id="su_id" value=1 >
                <td colspan="9"> <a id="add_row"  onclick="addNewRow('su')" type="button" data-toggle="tooltip" title="Add New Row" class="btn btn-success text-light float-right m-1"><i class="icofont-plus" ></i> Add Row</a> </td>
               
                
            </tr>
        </tfoot>
            </table>
            </div>
            <button type="submit" name="day" value="Sunday" class="btn btn-success float-right m-1"><i class="icofont-diskette"></i> Save</button> 
        </div>
        </div>

</form>
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

 
    



<script>
     
   
   
 function addNewRow(j){
     var i=$("#"+j+"_id").val();
    //  return false;
   $("#"+j+"_addr"+i).html("<td><select type='text' class='form-control' name='"+j+"_dept[]'  ><option value=''>Select Department</option><?php $dept=$newMaster->selectDept(); while($row=mysqli_fetch_assoc($dept)){  ?> <option value='<?= $row[id]?>'><?= $row[dept_name]?></option><?php } ?></select></td><td><select type='text' class='form-control' name='"+j+"_dctor[]'  ><option value=''>Select Doctor</option><?php $doctor=$newMaster->selectDoctor(); while($row=mysqli_fetch_assoc($doctor)){ ?> <option value='<?= $row[id]?>'><?= $row[name].' '.$row[last_name]?></option><?php } ?></select></td><td><div class='input-group clockpicker' style='width: 200px;' data-placement='bottom' data-align='top' data-autoclose='true'><input type='text' name='"+j+"_from[]' class='form-control' placeholder='00:00' id='"+j+"_from[]' > <span class='input-group-append'><span class='input-group-text'><i class='fa fa-clock-o'></i></span></span></div></td><td class='text-center'><div class='input-group clockpicker' style='width: 200px;' data-placement='bottom' data-align='top' data-autoclose='true'><input type='text' name='"+j+"_to[]' class='form-control' placeholder='00:00' id='"+j+"_to[]' > <span class='input-group-append'><span class='input-group-text'><i class='fa fa-clock-o'></i></span></span></td><td class='text-center'><a id='delete_row' name='dlt[]' onclick=\"dlt('"+j+"',"+i+")\" class='text-center text-light btn btn-danger' type='button'  data-toggle='tooltip' title='Delete'> <i class='icofont-ui-delete ' ></i></a></td>");
    i++; 
   $('#'+j+'_tab_logic').append('<tr id="'+j+'_addr'+(i)+'"></tr>');
$('.clockpicker').clockpicker({ twelvehour: true })

 
  $("#"+j+"_id").val(i);
}

function dlt(day,id){
      
      $("#"+day+"_addr"+(id)).html('');
     
  };
 </script>








<!-- ClockPicker script -->
<script type="text/javascript" src="clockpicker-gh-pages/dist/bootstrap-clockpicker.min.js"></script>


<script type="text/javascript">

$('.clockpicker').clockpicker({ twelvehour: true })


</script>

</body>

</html>