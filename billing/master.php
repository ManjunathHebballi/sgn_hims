<?php 
require_once(__DIR__ . '/../model/session_config.php');
// print_r($_SESSION);
// exit;
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
    <title>Master</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
 <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="./css/style.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>


    <!-- DataTables -->
    <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/datatables.net-select-bs4/css//select.bootstrap4.min.css" rel="stylesheet" type="text/css" />

<script type= "text/javascript" src = "countries.js"></script>


</head>

<style>
    table.dataTable tbody td {
        
        padding:5px;
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




<div class="container-fluid text-uppercase">
    <div class="row mb-3">   
        <div class="col-md-12">
            <h4 class="text-uppercase">Master</h4>
        </div>
    </div>

    <ul class="nav nav-pills mb-3 nav-justified" id="pills-tab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true"> <i class="icofont-bed"></i>&nbsp; Service Group</a>
  </li>
  
  <li class="nav-item">
    <a class="nav-link" id="pills-ot-tab" data-toggle="pill" href="#pills-ot" role="tab" aria-controls="pills-ot" aria-selected="false"><i class="icofont-operation-theater"></i> &nbsp; Service List</a>
  </li>
   <li class="nav-item">
    <a class="nav-link" id="pills-doct-tab" data-toggle="pill" href="#pills-doct" role="tab" aria-controls="pills-doct" aria-selected="false"><i class="icofont-doctor-alt"></i> &nbsp; Doctor Shares</a>
  </li>

</ul>



    

       

<div class="tab-content" id="pills-tabContent">
     
  <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
  <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
    <form action="controller/processMaster.php" method="POST">

  <div class="row">

                <div class="col-md-12">
                    <h4>Add Service Group</h4><br>
                    
                </div>
            </div> 
            <?php $bed=$newMaster->getServiceGroupId($_REQUEST['editgroup']);
            $row=mysqli_fetch_assoc($bed);
            ?>
            <div class="row">
        <div class="col-md-4">
           <div class="form-group">
                  <label for="exampleInputEmail1"> Group Name <span style="color:red">*</span></label>
                  <input type="text" class="form-control text-uppercase"  name="group_name" id="group_name" required placeholder="Enter Group Name" value=<?= $row['name']?> >
                  
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                   <label for="exampleInputEmail1"> Bill Group  <span style="color:red">*</span></label>
                   <select type="text" class="form-control text-uppercase " name="bill_group" id="bill_group" required >
                       <option value="">Select Bill Group</option>
                       <option value="ADMISSION CHARGES" <?php if($row['bill_group']=='ADMISSION CHARGES') { echo 'selected'; }?>>ADMISSION CHARGES</option>
                       <option value="BLOOD CHARGES" <?php if($row['bill_group']=='BLOOD CHARGES') { echo 'selected'; }?>>BLOOD CHARGES</option>
                       <option value="CONSULTING CHARGES" <?php if($row['bill_group']=='CONSULTING CHARGES') { echo 'selected'; }?>>CONSULTING CHARGES</option>
                       <option value="INVESTIGATION CHARGES" <?php if($row['bill_group']=='INVESTIGATION CHARGES') { echo 'selected'; }?>>INVESTIGATION CHARGES</option>
                       <option value="OPRATION CHARGES" <?php if($row['bill_group']=='OPRATION CHARGES') { echo 'selected'; }?>>OPRATION CHARGES</option>
                       <option value="OTHER CHARGES" <?php if($row['bill_group']=='OTHER CHARGES') { echo 'selected'; }?>>OTHER CHARGES</option>
                       <option value="PATHOLOGY TEST" <?php if($row['bill_group']=='PATHOLOGY TEST') { echo 'selected'; }?>>PATHOLOGY TEST</option>
                       <option value="PHYSIOTHERAPY CHARGES" <?php if($row['bill_group']=='PHYSIOTHERAPY CHARGES') { echo 'selected'; }?>>PHYSIOTHERAPY CHARGES</option>
                       <option value="ROOM CHARGES" <?php if($row['bill_group']=='ROOM CHARGES') { echo 'selected'; }?>>ROOM CHARGES</option>
                       <option value="TPA" <?php if($row['bill_group']=='TPA') { echo 'selected'; }?>>TPA</option>
                       <option value="ESI" <?php if($row['bill_group']=='ESI') { echo 'selected'; }?>>ESI</option>
                   </select>
             </div>
        </div>


    </div>
<?php if(isset($_REQUEST['editgroup'])){?>
    <input type="hidden" name="page" value='<?= $_REQUEST['editgroup']?>'>
    <?php } else {?> 
        <input type="hidden" name="page" value='new'>   
    <?php } ?>
    <div class="row">
            <div class="col-md-12">
            <button type="submit" class="btn btn-success float-right btn-rounded" name="submit" value="group"><span><i class="ri-save-3-line"></i></span> Save</button>
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
                        <h5 class="ml-4">Service Group List</h5>
                    </div>
                    <hr>

                    <div class="table-responsive" id="bedload">
                    <table id="datatable-buttons5" class="table table-bordered display text-center">
                                    <thead class="thead-dark">
                                    <tr >
                                                <th>Sl NO</th>
                                                <th>Service Group</th>
                                                
                                                <th>Bill Group</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody class="text-center text-black">
                                        <?php 
                                        $bed=$newMaster->getServiceGroup();
                                        $sn=1;
                                        while($res=mysqli_fetch_assoc($bed))
                                        {
                                        ?>
                                            <tr>
                                                <td><?= $sn?></td>
                                                <td><?= $res['name']?></td>
                                                <td><?= $res['bill_group']?></td>
                                            <td>  <?php if ($res['status'] == 'active') { ?><span class="badge badge-soft-success">Active</span> <?php } else { ?> <span class="badge badge-soft-danger">Inactive</span> <?php } ?></td>
                                               
                                               <td><a href="master.php?editgroup=<?= $res['id']?>#pills-home-tab"><span class=" badge badge-pill badge-primary p-2"><i class="bi bi-pencil mr-2"></i> Edit</span> </a>
                                                </td>
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

<div class="tab-pane fade" id="pills-ot" role="tabpanel" aria-labelledby="pills-ot-tab">
  <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
    <form action="controller/processMaster.php" method="POST">

  <div class="row">

                <div class="col-md-12">
                    <h4>Add Service Group</h4><br>
                    
                </div>
            </div> 
            <?php $bed1=$newMaster->getServiceNameId($_REQUEST['editservice']);
            $row1=mysqli_fetch_assoc($bed1);
            ?>
            <div class="row">
                
                
        <div class="col-md-4">
           <div class="form-group">
                  <label for="exampleInputEmail1"> Group Name <span style="color:red">*</span></label>
                  <select  class="form-control text-uppercase"  name="group_name" id="group_name" required>
                      <option value="">Select Group Name</option>
                      <?php
                      $res=$newMaster->getServiceGroup();
                      while($row=mysqli_fetch_assoc($res))
                      { ?>
                      <option <?php if($row1['service_group_id']==$row['id']) { echo "selected='selected'"; } ?> value="<?=$row['id']?>" ><?=$row['name']?></option>
                      <?php } ?>
                  </select>
            </div>
        </div>
        
        
        <div class="col-md-4">
           <div class="form-group">
                  <label for="exampleInputEmail1"> Service Name <span style="color:red">*</span></label>
                  <input type="text" class="form-control text-uppercase"  name="service_name" id="service_name" required placeholder="Enter Service Name" value="<?= $row1['name']?>" >
                  
            </div>
        </div>
        
        <div class="col-md-4">
           <div class="form-group">
                  <label for="exampleInputEmail1"> ESI Code</label>
                  <input type="text" class="form-control text-uppercase"  name="esi_code" id="esi_code"  placeholder="Enter ESI Code" value="<?= $row1['esi_code']?>" >
            </div>
        </div>
        <div class="col-md-4">
           <div class="form-group">
                  <label for="exampleInputEmail1"> OPD Charge <span style="color:red">*</span></label>
                  <input type="text" class="form-control text-uppercase"  name="opd_charge" id="opd_charge" required placeholder="Enter OPD Charge" value="<?= $row1['opd_charge']?>" >
            </div>
        </div>
        
        
        <div class="col-md-4">
           <div class="form-group">
                  <label for="exampleInputEmail1"> IPD Charge <span style="color:red">*</span></label>
                  <input type="text" class="form-control text-uppercase"  name="ipd_charge" id="ipd_charge" required placeholder="Enter IPD Charge" value="<?= $row1['ipd_charge']?>" >
            </div>
        </div>
        <div class="col-md-4">
           <div class="form-group">
                  <label for="exampleInputEmail1"> ESI Charges<span style="color:red">*</span></label>
                  <input type="text" class="form-control text-uppercase"  name="esi_charges" id="esi_charges" required placeholder="Enter ESI Charge" value="<?= $row1['esi_amount']?>" >
            </div>
        </div>
        
          <div class="col-md-4">
           <div class="form-group">
                  <label for="exampleInputEmail1"> Doctor Share <span style="color:red">*</span></label>
                  <select class="form-control text-uppercase" name="doctor_share" id="doctor_share" required>
                      <option value="">Select</option>
                      <option <?php if($row1['is_doctor']==1) { echo "selected='selected'"; } ?> value="1">YES</option>
                      <option <?php if($row1['is_doctor']==0) { echo "selected='selected'"; } ?>value="0">NO</option>
                  </select>
            </div>
        </div>
        
         <div class="col-md-4">
           <div class="form-group">
                  <label for="exampleInputEmail1"> Type <span style="color:red">*</span></label>
                  <select class="form-control text-uppercase" name="type" id="type" required>
                      <option value="">Select</option>
                      <option <?php if($row1['type']=='Internal') { echo "selected='selected'"; } ?> value="Internal">Internal</option>
                      <option <?php if($row1['type']=='External') { echo "selected='selected'"; } ?> value="External">External</option>
                  </select>
            </div>
        </div>
        <?php if(isset($_REQUEST['editservice'])){?>
        <div class="col-md-4">
           <div class="form-group">
                  <label for="exampleInputEmail1"> Status <span style="color:red">*</span></label>
                  <select class="form-control text-uppercase" name="status" id="status" required>
                      <option <?php if($row1['status']=='Active') { echo "selected='selected'"; } ?> value="Active">Active</option>
                      <option <?php if($row1['status']=='Inactive') { echo "selected='selected'"; } ?> value="Inactive">Inactive</option>
                  </select>
            </div>
        </div>
         <input type="hidden" name="page" value='<?= $_REQUEST['editservice']?>'>
    <?php } else {?> 
        <input type="hidden" name="page" value='new'>   
    <?php } ?>
    </div>

   
    <div class="row">
            <div class="col-md-12">
            <button type="submit" class="btn btn-success float-right btn-rounded" name="submit" value="service"><span><i class="ri-save-3-line"></i></span> Save</button>
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
                        <h5 class="ml-4">Service Name List</h5>
                    </div>
                    <hr>

                    <div class="table-responsive" id="bedload">
                    <table id="datatable-buttons3" class="table table-bordered display text-center">
                                    <thead class="thead-dark">
                                    <tr >
                                                <th>Sl NO</th>
                                                <th>Service Name</th>
                                                <th>Service Group</th>
                                                <th>ESI Code</th>
                                                <th>OPD Charge</th>
                                                <th>IPD Charge</th>
                                                <th>Doctor Share</th>
                                                <th>Type</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody class="text-center text-black">
                                        <?php 
                                        $bed=$newMaster->getServiceList();
                                        $sn=1;
                                        while($res=mysqli_fetch_assoc($bed))
                                        {
                                        ?>
                                            <tr>
                                                <td><?= $sn?></td>
                                                <td><?= $res['name']?></td>
                                                <td><?= $res['sg_name']?></td>
                                                <td><?=$res['esi_code'];?></td>
                                                <td><?=$res['opd_charge'];?></td>
                                                <td><?=$res['ipd_charge'];?></td>
                                                <td><?php if($res['is_doctor']==1) { echo "YES"; }  else { echo "NO"; } ?></td>
                                                <td><?= $res['type']?></td>
                                              <td>  <?php if ($res['status'] == 'Active') { ?><span class="badge badge-soft-success">Active</span> <?php } else { ?> <span class="badge badge-soft-danger">Inactive</span> <?php } ?></td>
                                               
                                               <td><a href="master.php?editservice=<?= $res['id']?>#pills-ot-tab"><span class=" badge badge-pill badge-primary p-2"><i class="bi bi-pencil mr-2"></i> Edit</span> </a>
                                                </td>
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
<div class="tab-pane fade" id="pills-doct" role="tabpanel" aria-labelledby="pills-doct-tab">
  <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
    <form action="controller/processMaster.php" method="POST">

  <div class="row">

                <div class="col-md-12">
                    <h4>Add Doctor Share</h4><br>
                    
                </div>
            </div> 
            <?php $bed1=$newMaster->getDoctorShareId($_REQUEST['editdoctor_share']);
            $row1=mysqli_fetch_assoc($bed1);
            ?>
            <div class="row">
                
                
        <div class="col-md-4">
           <div class="form-group">
                  <label for="exampleInputEmail1"> Doctor Name <span style="color:red">*</span></label>
                  <select  class="form-control text-uppercase select2search"  name="doctor" id="doctor" required>
                      <option value="">Select Doctor Name</option>
                      <?php
                      $res=$newMaster->getDoctor();
                      while($row=mysqli_fetch_assoc($res))
                      { ?>
                      <option <?php if($row1['doctor_id']==$row['id']) { echo "selected='selected'"; } ?> value="<?=$row['id']?>" ><?=$row['name'].' '.$row['last_name']?></option>
                      <?php } ?>
                  </select>
            </div>
        </div>
        
        
        <div class="col-md-4">
           <div class="form-group">
                  <label for="exampleInputEmail1"> Service Name <span style="color:red">*</span></label>
                  <select type="text" class="form-control text-uppercase select2search"  name="service_name" id="service_name" required>
                     <option value="">Select Service Name</option>
                    <?php
                      $res=$newMaster->getShareServices();
                      while($row=mysqli_fetch_assoc($res))
                      { ?>
                      <option <?php if($row1['service_id']==$row['id']) { echo "selected='selected'"; } ?> value="<?=$row['id']?>" ><?=$row['name']?></option>
                      <?php } ?>
                  </select>
            </div>
        </div>
        
        
        <div class="col-md-4">
           <div class="form-group">
                  <label for="exampleInputEmail1"> Amount Share to Doctor <span style="color:red">*</span></label>
                  <input type="number" class="form-control text-uppercase"  name="amt_share2doctor" id="amt_share2doctor" required onkeyup="changetype(this.value,'amt')" placeholder="Enter Amount" value=<?= $row1['amt_share2doctor']?> >
            </div>
        </div>
        
        
        <div class="col-md-4">
           <div class="form-group">
                  <label for="exampleInputEmail1"> Percent share for Doctor <span style="color:red">*</span></label>
                  <input type="number" class="form-control text-uppercase"  name="perc_share2doctor" max='100' id="perc_share2doctor" required onkeyup="changetype(this.value,'per')" placeholder="Enter Percentage" value=<?= $row1['perc_share2doctor']?> >
            </div>
        </div>
        
        
          <div class="col-md-4">
           <div class="form-group">
                  <label for="exampleInputEmail1"> Percent share for Hospital<span style="color:red">*</span></label>
               <input type="number" class="form-control text-uppercase"  name="perc_share2hospital" max='100' id="perc_share2hospital" required placeholder="Enter Percentage" value=<?= $row1['perc_sahre2hospital']?> >
            </div>
        </div>
        
      
        
    </div>
<?php if(isset($_REQUEST['editdoctor_share'])){?>
    <input type="hidden" name="page" value='<?= $_REQUEST['editdoctor_share']?>'>
    <?php } else {?> 
        <input type="hidden" name="page" value='new'>   
    <?php } ?>
    <div class="row">
            <div class="col-md-12">
            <button type="submit" class="btn btn-success float-right btn-rounded" name="submit" value="doctor"><span><i class="ri-save-3-line"></i></span> Save</button>
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
                        <h5 class="ml-4">Doctor Shares List</h5>
                    </div>
                    <hr>

                    <div class="table-responsive" id="bedload">
                    <table id="datatable-buttons4" class="table table-bordered display text-center">
                                    <thead class="thead-dark">
                                    <tr >
                                                <th>Sl NO</th>
                                                <th>Doctor Name</th>
                                                <th>Service Name</th>
                                                <th>Amount Share2Doctor</th>
                                                <th>Perc Share2Doctor</th>
                                                <th>Perc Share2Hospital</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody class="text-center text-black">
                                        <?php 
                                        $bed=$newMaster->getDoctorShareList();
                                        $sn=1;
                                        while($res=mysqli_fetch_assoc($bed))
                                        {
                                        ?>
                                            <tr>
                                                <td><?= $sn?></td>
                                                <td><?= $res['doctor']?></td>
                                                <td><?= $res['name']?></td>
                                                <td><?=$res['amt_share2doctor'];?></td>
                                                <td><?=$res['perc_share2doctor'];?></td>
                                                <td><?= $res['perc_share2hospital']?></td>
                                                <td>  <?php if ($res['status'] == 'active') { ?><span class="badge badge-soft-success">Active</span> <?php } else { ?> <span class="badge badge-soft-danger">Inactive</span> <?php } ?></td>
                                               <td><a href="master.php?editdoctor_share=<?= $res['id']?>#pills-doct-tab"><span class=" badge badge-pill badge-primary p-2"><i class="bi bi-pencil mr-2"></i> Edit</span> </a>
                                                </td>
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


      <script src="./vendor/select2/js/select2.full.min.js"></script>
    <script src="./js/plugins-init/select2-init.js"></script>
  

    <script>
        $(function () {
  $('[data-toggle="tooltip"]').tooltip()
});

    </script>

<script>
    $(document).ready(function() {
            $('#datatable-buttons4').DataTable({

                dom: 'Bfrtip',
                responsive: true,
                pageLength: 50,
                // lengthMenu: [0, 5, 10, 20, 50, 100, 200, 500],
               

                buttons: [
                     {
                    extend: 'excelHtml5',
                    title: 'Doctor Shares List'
                },
                {
                    extend: 'pdfHtml5',
                    title: 'Doctor Shares List'
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
              

                buttons: [
                         {
                    extend: 'excelHtml5',
                    title: 'Service Group List'
                },
                {
                    extend: 'pdfHtml5',
                    title: 'Service Group List'
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
                    extend: 'excelHtml5',
                    title: 'Service Name List'
                },
                {
                    extend: 'pdfHtml5',
                    title: 'Service Name List'
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
<script>
    function deleteBed(id)
    {
         $.ajax({
            type : 'post',
            url : 'ajax.php', //Here you will fetch records 
            data :  'id='+id+'&page=bed_del', //Pass $id
            success : function(data){
         $('#bedload').load(" #bedload");//Show fetched data from database
            }
        });
    }
    function deleteDept(id)
    {
         $.ajax({
            type : 'post',
            url : 'ajax.php', //Here you will fetch records 
            data :  'id='+id+'&page=dept_del', //Pass $id
            success : function(data){
         $('#loaddept').load(" #loaddept");//Show fetched data from database
            }
        });
    }
    
    $('#myCollapsible').on('hidden.bs.collapse', function () {
  // do something...
})
   function changetype(val,type)
   {
       if(val>0&&val.length>0)
       {
           if(type=='amt')
           {
               document.getElementById('perc_share2doctor').readOnly = true;
               document.getElementById('perc_share2hospital').readOnly = true;
               document.getElementById('perc_share2doctor').required = false;
               document.getElementById('perc_share2hospital').required = false;
           }
           else{
               document.getElementById('amt_share2doctor').readOnly = true;
                document.getElementById('amt_share2doctor').required = false;
                document.getElementById('perc_share2hospital').value=100-document.getElementById('perc_share2doctor').value
           }
       }
       else{
           document.getElementById('perc_share2doctor').required = true;
           document.getElementById('perc_share2hospital').required = true;
           document.getElementById('amt_share2doctor').required = true;
           document.getElementById('perc_share2doctor').readOnly = false;
           document.getElementById('perc_share2hospital').readOnly = false;
               document.getElementById('amt_share2doctor').readOnly = false;
               document.getElementById('perc_share2hospital').value=0;
       }
   }
   
    $(document).ready(function() {
    $('.select2search').select2();
    $('.select2doctor').select2();
    
});
</script>
<script language="javascript">print_country("country");
</script>
<script language="javascript">print_country("country2");
</script>


</body>

</html>