<?php 
require_once(__DIR__ . '/model/session_config.php');
// print_r($_SESSION);
// exit;
date_default_timezone_set("Asia/Kolkata");  
include('model/masterClass.php');
$newMaster = new master;
$dept=$newMaster->selectDept();
if(isset($_REQUEST['status'])&&$_REQUEST['page']=='doctor')
{
    if($_REQUEST['status']=='active')
    {
        $newMaster->statusUser($_REQUEST['id'],'active');
    }
    else{
        $newMaster->statusUser($_REQUEST['id'],'inactive');
    }
    echo "<script>window.location='master.php#pills-doctor-tab'</script>";
}
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
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>


    <!-- DataTables -->
    <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/datatables.net-select-bs4/css//select.bootstrap4.min.css" rel="stylesheet" type="text/css" />

<script type= "text/javascript" src = "countries.js"></script>


</head>

<body class="text-uppercase">

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
            <h4 class="text-uppercase">Master</h4>
        </div>
    </div>

    <ul class="nav nav-pills mb-3 nav-justified" id="pills-tab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="pills-bed-tab" data-toggle="pill" href="#pills-bed" role="tab" aria-controls="pills-home" aria-selected="true"> <i class="icofont-bed"></i>&nbsp; Beds</a>
  </li>
  
  <li class="nav-item">
    <a class="nav-link" id="pills-ot-tab" data-toggle="pill" href="#pills-ot" role="tab" aria-controls="pills-contact" aria-selected="false"><i class="icofont-operation-theater"></i> &nbsp; OT Type</a>
  </li>
  
 

  <!--<li class="nav-item">-->
  <!--  <a class="nav-link" id="pills-bills-tab" data-toggle="pill" href="#pills-bills" role="tab" aria-controls="pills-bills" aria-selected="false"> <i class="icofont-drug"></i> &nbsp;Bills</a>-->
  <!--</li>-->
  
  <li class="nav-item">
    <a class="nav-link" id="pills-units-tab" data-toggle="pill" href="#pills-units" role="tab" aria-controls="pills-units" aria-selected="false"> <i class="icofont-patient-bed"></i> &nbsp;Department</a>
  </li>
   <li class="nav-item">
    <a class="nav-link" id="pills-doctor-tab" data-toggle="pill" href="#pills-doctor" role="tab" aria-controls="pills-doctor" aria-selected="false"> <i class="icofont-doctor"></i> &nbsp;Doctor</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="pills-diag-tab" data-toggle="pill" href="#pills-diag" role="tab" aria-controls="pills-diag" aria-selected="false"> <i class="icofont-drug"></i> &nbsp;Diagnosis</a>
  </li>
  
  <li class="nav-item">
    <a class="nav-link" id="pills-diag_icd-tab" data-toggle="pill" href="#pills-diag_icd" role="tab" aria-controls="pills-diag_icd" aria-selected="false"> <i class="icofont-drug"></i> &nbsp;ICD Codes and Diagnosis</a>
  </li>

  <li class="nav-item">
    <a class="nav-link" id="pills-ptype-tab" data-toggle="pill" href="#pills-ptype" role="tab" aria-controls="pills-ptype" aria-selected="false"> <i class="icofont-users-alt-4"></i> &nbsp;Patient Type</a>
  </li>
</ul>



    

       

<div class="tab-content" id="pills-tabContent">
      <div class="tab-pane fade " id="pills-doctor" role="tabpanel" aria-labelledby="pills-doctor-tab">
          <div class="row mb-3">
        <div class="col-md-12">
            <?php
                if(isset($_REQUEST['doctor_editid']))
                {
                    $data=mysqli_fetch_assoc($newMaster->getDoctor($_REQUEST['doctor_editid']));
                    ?>
                   <h4 class="text-uppercase">Edit Doctor</h4> 
            <?php    }
            else{
            ?>
            <h4 class="text-uppercase">Add New Doctor</h4>
            <?php } ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
<form action="controller/processmaster.php" method='POST'>

    <div class="row">
          
        <div class="col-md-3">
           <div class="form-group">
                  <label for="dob"> Date of Birth</label>
                  <input class="form-control text-uppercase" type="date" name="dob" id="dob" value="<?= $data['dob']?>" required>

                  
            </div>
        </div>

       

         <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1">First Name</label>
                   <input type="text" class="form-control text-uppercase " name="fname" id="fname" placeholder="Enter First Name" value="<?= $data['name']?>" required>
             </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1">Last Name</label>
                   <input type="text" class="form-control text-uppercase " name="lname" id="lname" placeholder="Enter Last Name" value="<?= $data['last_name']?>" required>
             </div>
         </div>
           <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1" >Abbreviated Name</label>
                   <input type="text" class="form-control text-uppercase " name="abbname" id="abbname"  placeholder="Enter Abbreviated Name" value="<?= $data['abbreviated_name']?>" >
             </div>
         </div>
          <div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputEmail1">  Sex</label>

                <select class="form-control text-uppercase " name="sex" id="sex" required>
                    <option value="">Select Gender</option>
                    <option value="male" <?php if($data['gender']=='male') echo 'selected'; ?>>Male</option>
                    <option value="female" <?php if($data['gender']=='female') echo 'selected'; ?>>Female</option>
                    <option value="other" <?php if($data['gender']=='other') echo 'selected'; ?>>Other</option>
                </select>
            </div>
         </div>
        
         <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1" >Doctor Type</label>
                   <input type="text" class="form-control text-uppercase " name="doctor_type" id="doctor_type"  placeholder="Enter Doctor Type"  value="<?= $data['doctor_type']?>">
             </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1" >Speciality</label>
                   <input type="text" class="form-control text-uppercase " name="speciality" id="speciality"  placeholder="Enter speciality"  value="<?= $data['speciality']?>">
             </div>
         </div>
          <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1" >Schedule Type</label>
                   <input type="text" class="form-control text-uppercase " name="schedule_type" id="schedule_type"  placeholder="Enter Schedule Type"  value="<?= $data['Schedule_type']?>">
             </div>
         </div>
          <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1" >Registration No.</label>
                   <input type="text" class="form-control text-uppercase " name="registration_no" id="registration_no"  placeholder="Enter Registration No."  value="<?= $data['registration_no']?>">
             </div>
         </div>
          <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1" >Aadhar Number</label>
                   <input type="text" class="form-control text-uppercase " name="aadhar" id="aadhar"  placeholder="Enter Aadhar Number"  value="<?= $data['aadhar_no']?>">
             </div>
         </div>
          <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1" >Pan Card Number</label>
                   <input type="text" class="form-control text-uppercase " name="pancard" id="pancard"  placeholder="Enter Pan Card Number" value="<?= $data['pancard']?>" >
             </div>
         </div>
          <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1" >Qualification</label>
                   <input type="text" class="form-control text-uppercase " name="qualification" id="qualification"  placeholder="Enter qualification" value="<?= $data['qualification']?>" >
             </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1" >Contact No.</label>
                   <input type="text" class="form-control text-uppercase " name="contact" id="contact" placeholder="Enter Mobile No." value="<?= $data['mobile']?>" >
             </div>
         </div>
        
     
        
        

         <div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputEmail1">  Department</label>

                <select class="form-control text-uppercase " name="dept" id="dept"  required>
                    <option value="">Select Department</option>
              


                   <?php 

                    while($row=mysqli_fetch_assoc($dept))
                    
                    {
                    ?>
                                        <option value="<?= $row['id']?>" <?php if($data['department']==$row['id']) echo 'selected';?>><?= $row['dept_name']?></option>
                    <?php } ?>
                 
   
                </select>
            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputEmail1">Billing Department</label>

                <select class="form-control text-uppercase " name="bill_dept" id="bill_dept"  required>
                    <option value="">Select Department</option>
              


                   <?php 
$dept2=$newMaster->selectDept1();
                    while($row=mysqli_fetch_assoc($dept2))
                    
                    {
                    ?>
                                        <option value="<?= $row['id']?>" <?php if($data['billing_dept']==$row['id']) echo 'selected';?>><?= $row['dept_name']?></option>
                    <?php } ?>
                 
   
                </select>
            </div>
         </div>

        
         <div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputEmail1">  Email</label>

                <input type="text" class="form-control text-uppercase" name="email" id="email" aria-describedby="emailHelp"  placeholder="Enter Email " value="<?= $data['email']?>">

            </div>
         </div>
          <div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputEmail1">  Visit Time</label>

                <input type="time" class="form-control text-uppercase" name="visit" id="visit" aria-describedby="emailHelp"  placeholder="Enter visit " value="<?= $data['visiting_time']?>">

            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputEmail1">Consultation Charge</label>

                <input type="number" class="form-control text-uppercase" name="fees" id="fees" aria-describedby="emailHelp" required min=0 placeholder="Enter Consultation Charge" value="<?= $data['consultation']?>">

            </div>
         </div>

        

         
</div>

<div class="accordion" id="accordionExample">
  
  <div class="card">
    <div class="card-header" id="headingThree">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block card-title text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
         Other Details
        </button>
      </h2>
    </div>
    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
      <div class="card-body">
       <div class="row mt-2">
    <div class="col-md-12">
        <h5> Address</h5>
    <hr/>
    </div>
     <div class="col-md-4">
       <div class="form-group">
           <label>Residence Address</label>
             <textarea type="text" class="form-control text-uppercase" name="residence" id="residence"   placeholder="Residence Address"><?= $data['residence_addr']?></textarea>
       </div>
   </div>

   <div class="col-md-2">
       <div class="form-group">
           <label>Country</label>
           <select class="form-control text-uppercase" onchange="print_state('state',this.selectedIndex);" id="country" name ="country"></select>
 
       </div>
   </div>
     <div class="col-md-2">
       <div class="form-group">
           <label>State</label>
           <select class="form-control text-uppercase" name ="state" id ="state"></select>
         
       </div>
   </div>
      <div class="col-md-2">
       <div class="form-group">
           <label>City</label>
             <input type="text" class="form-control text-uppercase" name="city" id="city"  value="<?= $data['city']?>" placeholder="Enter City">
       </div>
   </div>
       <div class="col-md-2">
       <div class="form-group">
           <label>Pin</label>
             <input type="text" class="form-control text-uppercase" name="pin" id="pin"  value="<?= $data['pincode']?>" placeholder="Enter Pin">
       </div>
   </div>

  
   
   </div>
   <div class="row">
   <div class="col-md-4">
       <div class="form-group">
           <label>Clinic Address</label>
             <textarea type="text" class="form-control text-uppercase" name="clinic" id="clinic"   placeholder="clinic Address"><?= $data['clinic_address']?></textarea>
       </div>
   </div>
      <div class="col-md-2">
       <div class="form-group">
           <label>Country</label>
           <select class="form-control text-uppercase" onchange="print_state('state2',this.selectedIndex);" id="country2" name ="c_country"></select>
 
       </div>
   </div>
     <div class="col-md-2">
       <div class="form-group">
           <label>State</label>
           <select class="form-control text-uppercase" name ="c_state" id ="state2"></select>
         
       </div>
   </div>
    <div class="col-md-2">
       <div class="form-group">
           <label>City</label>
             <input type="text" class="form-control text-uppercase" name="c_city" id="c_city" value="<?= $data['clinic_city']?>"  placeholder="Enter City">
       </div>
   </div>
       <div class="col-md-2">
       <div class="form-group">
           <label>Pin</label>
             <input type="text" class="form-control text-uppercase" name="c_pin" id="c_pin" value="<?= $data['clinic_pincode']?>"  placeholder="Enter Pin Code">
       </div>
   </div>
     <div class="col-md-2">
       <div class="form-group">
           <label>Clinic Telephone Number</label>
             <input type="text" class="form-control text-uppercase" name="c_tel" id="c_tel" value="<?= $data['clinic_pincode']?>"  placeholder="Clinic Telephone Number">
       </div>
   </div>
  
  <br/>
  <br/>
  
   
   <div class="col-md-12 mt-3">
        <h5> Bank Details</h5>
    <hr/>
   </div>
   <div class="col-md-3">
       <div class="form-group">
           <label>Account Type</label>
             <input type="text" class="form-control text-uppercase" name="bank_type" id="bank_type" value="<?= $data['account_type']?>"  placeholder="Enter Bank Account Type">
       </div>
   </div>
   <div class="col-md-3">
       <div class="form-group">
           <label> Bank Account Number </label>
             <input type="text" class="form-control text-uppercase" name="account_no" id="account_no" value="<?= $data['account_no']?>"  placeholder="Enter Bank Acount Number">
       </div>
   </div>
   <div class="col-md-3">
       <div class="form-group">
           <label> IFSC Number </label>
             <input type="text" class="form-control text-uppercase" name="ifsc" id="ifsc"  value="<?= $data['ifsc_no']?>" placeholder="Enter IFSC Number">
       </div>
   </div>
    
</div>
      </div>
    </div>
  </div>
</div>



<div class="row">
         <div class="col-md-12">
          <?php 
          if(isset($_REQUEST['doctor_editid']))
          {
          ?>
          <input type="hidden" name="editid" value="<?= $_REQUEST['doctor_editid']?>">
<?php } ?>
            <button type="submit" class="btn btn-primary btn-lg float-right" name="submit" value="doctor">Save</button>
            
         </div>
  
    </div>
</form>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
                <div class="card-body">
                    <div class="row">
                        <h5 class="ml-4">Doctors List</h5>
                    </div>
                    <hr>

                    <div class="table-responsive" id="">
                    <table id="datatable-buttons4" class="table table-hoverde display text-center">
                                    <thead class="thead-dark">
                                    <tr >
                                                <th>Sl NO</th>
                                                <th>Doctor Name</th>
                                                <th>Gender</th>
                                                <th>DOB</th>
                                                <th>Type</th>
                                                <th>Dept</th>
                                                <th>Billing Dept</th>
                                                <th>Contact</th>
                                                <th>Visit Time</th>
                                                <th>Schedule Type</th>
                                                <th>Pan</th>
                                                <th>Aadhar</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody class="text-center text-black">
                                                <?php 
                                                $sn=1;
                                                $data=$newMaster->getDoctorRegistration();
                                                while($row=mysqli_fetch_assoc($data))
                                                { ?>
                                                    <tr>
                                                        <td><?= $sn?></td>
                                                        <td><?= $row['name'].' '.$row['last_name']?></td>
                                                        <td><?= $row['gender']?></td>
                                                        <td><?= $row['dob']?></td>
                                                        <td><?= $row['doctor_type']?></td>
                                                        <td><?= $row['dept_name']?></td>
                                                        <td><?= $row['bill_dept']?></td>
                                                        <td><?= $row['mobile']?></td>
                                                        <td><?= $row['visiting_time']?></td>
                                                        <td><?= $row['schedule_type']?></td>
                                                        <td><?= $row['pancard']?></td>
                                                        <td><?= $row['aadhar_no']?></td>
                                                        <td><?= $row['status']?></td>
                                                       <td><a href="master.php?doctor_editid=<?= $row['id']?>#pills-doctor-tab" class="badge badge-pill badge-primary" data-toggle="tootltip" title="Edit User"><i class="ri-edit-line"></i></a>&emsp;<?php if($row['status']=='active') { ?><a href="master.php?id=<?= $row['id']?>&status=inactive&page=doctor" class="badge badge-pill badge-danger" data-toggle="tootltip" title="Inactive User"><i class="ri-thumb-down-line"></i></a><?php } else{ ?><a href="master.php?id=<?= $row['id']?>&status=active&page=doctor" class="badge badge-pill badge-success" data-toggle="tootltip" title="Active User"><i class="ri-thumb-up-line"></i></a><?php }?></td>
                                                    
                                                    </tr>
                                                <?php $sn++; }
                                                ?>
                                                                   
                                            </tbody>
                                            </table>   
                    </div>
                </div>
            </div>
      </div>
  <div class="tab-pane fade show active" id="pills-bed" role="tabpanel" aria-labelledby="pills-home-tab">
  <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
    <form action="controller/processmaster.php" method="POST">

  <div class="row">

                <div class="col-md-12">
                    <h4>Add Beds</h4><br>
                    
                </div>
            </div> 
            <?php $bed=$newMaster->getBedId($_REQUEST['editbed']);
            $row=mysqli_fetch_assoc($bed);
            ?>
            <div class="row">
        <div class="col-md-4">
           <div class="form-group">
                  <label for="exampleInputEmail1"> Bed No <span style="color:red">*</span></label>
                  <input type="text" class="form-control text-uppercase"  name="bed" id="bed" placeholder="Enter Bed Number" value=<?= $row['bed_number']?> >
                  
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                   <label for="exampleInputEmail1"> Ward  <span style="color:red">*</span></label>
                   <input type="text" class="form-control text-uppercase " name="ward" id="ward" placeholder="Enter Ward" value=<?= $row['ward']?>>
                   
             </div>
        </div>

         <div class="col-md-4">
            <div class="form-group">
                   <label for="exampleInputEmail1">Department<span style="color:red">*</span></label>
                   <select class="form-control text-uppercase " name="department" id="department" required>
                    <option value="">Select Department</option>
                    <?php 

// echo "<option value=''>".$dept.'</option>';
$dept2=$newMaster->selectDept();
while($row1=mysqli_fetch_assoc($dept2))

{
?>
                    <option value="<?= $row1['id']?>" <?php if($row1['id']==$row['department']) {echo "selected";}?>><?= $row1['dept_name']?></option>
<?php } ?>
                 
                </select>
             </div>
         </div>

    </div>
<?php if(isset($_REQUEST['editbed'])){?>
    <input type="hidden" name="page" value='<?= $_REQUEST['editbed']?>'>
    <?php } else {?> 
        <input type="hidden" name="page" value='new'>   
    <?php } ?>
    <div class="row">
            <div class="col-md-12">
            <button type="submit" class="btn btn-success float-right btn-rounded" name="submit" value="bed"><span><i class="ri-save-3-line"></i></span> Save</button>
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
                        <h5 class="ml-4">Bed List</h5>
                    </div>
                    <hr>

                    <div class="table-responsive" id="bedload">
                    <table id="datatable-buttons" class="table table-hoverde display text-center">
                                    <thead class="thead-dark">
                                    <tr >
                                                <th>Sl NO</th>
                                                <th>Bed No</th>
                                                
                                                <th>Ward No</th>
                                                <th>Departments</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody class="text-center text-black">
                                        <?php 
                                        $bed=$newMaster->getBed();
                                        $sn=1;
                                        while($res=mysqli_fetch_assoc($bed))
                                        {
                                        ?>
                                            <tr>
                                                <td><?= $sn?></td>
                                                <td><?= $res['bed_number']?></td>
                                                <td><?= $res['ward']?></td>
                                                <td ><?= $res['dept_name']?></td>
                                                <td > <?php if($res['status']=='active'){ ?> <span class=" badge badge-success">Vacant</span><?php } else { ?><span class=" badge badge-danger">Occupied</span><?php } ?></td>
                                                <td><a href="master.php?editbed=<?= $res['id']?>#pills-bed-tab"><span class=" badge badge-pill badge-primary p-2"><i class="bi bi-pencil mr-2"></i> Edit</span> </a>
                                                <a href="#" onclick="deleteBed('<?= $res['id']?>')" data-toggle="tooltip" title="Delete Bed No <?= $res['bed_number']?>" class="badge badge-pill badge-danger"><i class="ri-close-line "></i></a></td>
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
<div class="tab-pane fade " id="pills-units" role="tabpanel" aria-labelledby="pills-units-tab">
  <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
    <form action="controller/processmaster.php" method="POST">

  <div class="row">

                <div class="col-md-12">
                    <h4>Add Department</h4><br>
                    
                </div>
            </div> 
        <?php $department=$newMaster->getDeptId($_REQUEST['editdept']);
            $depart=mysqli_fetch_assoc($department);
            ?>
            <div class="row">
        <div class="col-md-3">
           <div class="form-group">
                  <label for="exampleInputEmail1"> Department Name <span style="color:red">*</span></label>
                  <input type="text" class="form-control text-uppercase"  name="dept" id="dept" placeholder="Enter Unit Number" value="<?= $depart['dept_name']?>" >
                  
            </div>
        </div>
        <div class="col-md-3">
           <div class="form-group">
                  <label for="exampleInputEmail1"> Total Number Of Bed <span style="color:red">*</span></label>
                  <input type="text" class="form-control text-uppercase"  name="bed_no" id="bed_no" placeholder="Enter Unit Number" value="<?= $depart['bed']?>" >
                  
            </div>
        </div>
       <div class="col-md-3">
           <div class="form-group">
                  <label for="exampleInputEmail1"> Unit  No. <span style="color:red">*</span></label>
                  <input type="text" class="form-control text-uppercase"  name="unit_no" id="unit_no" placeholder="Enter Unit Number" value="<?= $depart['unit']?>" >
                  
            </div>
        </div>
     <div class="col-md-3">
           <div class="form-group">
                  <label for="exampleInputEmail1"> Department Type <span style="color:red">*</span></label>
                  <select name="dept_type" id="dept_type" class="form-control text-uppercase" required>
                      
                      <option <?php if($depart['dept_type']=='OPD'){ echo 'selected'; } ?> value="OPD">OPD</option>
                       <option <?php if($depart['dept_type']=='IPD'){ echo 'selected'; } ?> value="IPD">IPD</option>
                        <option <?php if($depart['dept_type']=='BOTH'){ echo 'selected'; } ?> value="BOTH">BOTH</option>
                  
                  </select>
            </div>
        </div>

    </div>
<?php if(isset($_REQUEST['editdept'])){?>
    <input type="hidden" name="page" value='<?= $_REQUEST['editdept']?>'>
    <?php } else {?> 
        <input type="hidden" name="page" value='new'>   
    <?php } ?>
    <div class="row">
            <div class="col-md-12">
            <button type="submit" class="btn btn-success float-right btn-rounded" name="submit" value="department"><span><i class="ri-save-3-line"></i></span> Save</button>
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
                        <h5 class="ml-4"> List</h5>
                    </div>
                    <hr>

                    <div class="table-responsive" id="loaddept">
                    <table id="datatable-buttons" class="table table-hoverde display text-center">
                                    <thead class="thead-dark">
                                    <tr >
                                                <th>Sl NO</th>
                                                 <th>Departments</th>
                                                 <th>Total Bed</th>
                                                  <th>Number Of Unit </th>
                                                   <th>Status</th>
                                                <th>Action</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                             $dept=$newMaster->selectDept();
                                        $sn=1;
                                        while($res=mysqli_fetch_assoc($dept))
                                        {
                                        ?>
                                        <tr>
                                            <td><?= $sn?></td>
                                            <td><?= $res['dept_name']?></td>
                                            <td><?= $res['bed']?></td>
                                            <td><?= $res['unit']?></td>
                                            <td><?= $res['status']?></td>
                                            <td><a href="master.php?editdept=<?= $res['id']?>#pills-units-tab"><span class=" badge badge-pill badge-primary p-2"><i class="bi bi-pencil mr-2"></i> Edit</span> </a>
                                            <a href="#" onclick="deleteDept('<?= $res['id']?>')" data-toggle="tooltip" title="Delete Dept <?= $res['dept_name']?>" class="badge badge-pill badge-danger"><i class="ri-close-line "></i></a></td>
                                            
                                            </tr>
                                            <?php  $sn++; } ?>
                                        </tbody>
                                       
                                            </table>   
                    </div>
                </div>
            </div>
        </div>
    </div>


         


</div>
<div class="tab-pane fade  " id="pills-ot" role="tabpanel" aria-labelledby="pills-ot-tab">
    
<div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
    <form action="controller/processmaster.php" method="POST">

  <div class="row">
                <div class="col-md-12">
                    <h4>Add OT Type</h4><br>
                    
                </div>
            </div> 
<?php $ot=$newMaster->getOTId($_REQUEST['editOT']);
$row=mysqli_fetch_assoc($ot);
?>
            <div class="row">
        <div class="col-md-4"> 
           <div class="form-group">
                  <label for="exampleInputEmail1"> OT Type <span style="color:red">*</span></label>
                  <input type="text" class="form-control text-uppercase"  name="ot" id="ot" placeholder="Enter OT Type Name" value="<?= $row['ot_name']?>" >
                  
            </div>
        </div>

       
    </div>
    <?php if(isset($_REQUEST['editOT'])){?>
                    <input type="hidden" name="page" value="<?= $row['id']?>">
                    <?php } else {?> 
                        <input type="hidden" name="page" value='new'>   
                    <?php } ?>
    
    <div class="row">
            <div class="col-md-6">
            <button type="submit" class="btn btn-success float-right btn-rounded" name="submit" value="ot_type"><span><i class="ri-save-3-line"></i></span> Save</button>
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
                        <h5 class="ml-4">OT Type List</h5>
                    </div>
                    <hr>

                    <div class="table-responsive">
                    <table id="datatable-buttons5" class="table table-border table-hoverde  text-center">
                                    <thead class="thead-dark">
                                    <tr >
                                                <th>Sl NO</th>
                                                <th>OT Type</th>
                                                <th>Action</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody class="text-center text-black">
                                        <?php 
                                        $bed=$newMaster->getOt_type();
                                        $sn=1;
                                        while($res=mysqli_fetch_assoc($bed))
                                        { ?>
                                            <tr>
                                                <td><?= $sn?></td>
                                                <td><?= $res['ot_name']?></td>
                                                  <td><a href="master.php?editOT=<?= $res['id']?>#pills-ot-tab"><span class=" badge badge-pill badge-primary p-2"><i class="bi bi-pencil mr-2"></i> Edit</span> </a></td>
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



    <div class="tab-pane fade " id="pills-diag" role="tabpanel" aria-labelledby="pills-diag-tab">
  <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
    <form action="controller/processmaster.php" method="POST">

  <div class="row">

                <div class="col-md-12">
                    <h4>Add Diagnosis</h4><br>
                    
                </div>
            </div> 
         <?php   $diag_edit=$newMaster->getDiagEdit($_REQUEST['editdiag']);
                        $diag=mysqli_fetch_assoc($diag_edit); ?>
            <div class="row"> 
        <div class="col-md-4">
           <div class="form-group">
                  <label for="exampleInputEmail1">Diagnosis Name <span style="color:red">*</span></label>
                  <input type="text" class="form-control text-uppercase"  name="diag_name" id="diag_name" placeholder="Enter Diagnosis Name" value="<?= $diag['name']?>" required >
                  
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                   <label for="exampleInputEmail1"> Days in Hospital  <span style="color:red">*</span></label>
                   <input type="text" class="form-control text-uppercase " name="dih" id="dih" placeholder="Enter DIH" required  value="<?= $diag['days']?>">
                   
             </div>
        </div>
        
        <div class="col-md-4">
            <div class="form-group">
                   <label for="exampleInputEmail1"> Min Age  <span style="color:red">*</span></label>
                   <input type="text" class="form-control text-uppercase " name="min_age" id="min_age" placeholder="Enter Min Age" required  value="<?= $diag['min_age']?>">
                   
             </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                   <label for="exampleInputEmail1"> Max Age <span style="color:red">*</span></label>
                   <input type="text" class="form-control text-uppercase " name="max_age" id="max_age" placeholder="Enter Max Age" required  value="<?= $diag['max_age']?>">
                   
             </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                   <label for="exampleInputEmail1">Gender<span style="color:red">*</span></label>
                   <select class="form-control text-uppercase " name="gender" id="gender"  required>
                       <option value="both" <?= $diag['sex']=='both' ? 'selected' : '' ?>>Both</option>
                       <option value="male" <?= $diag['sex']=='male' ? 'selected' : '' ?>>Male</option>
                       <option value="female" <?= $diag['sex']=='female' ? 'selected' : '' ?>>Female</option>
                   </select>
                   
             </div>
        </div>
         <div class="col-md-4">
            <div class="form-group">
                <label for="exampleInputEmail1">  Department</label>

                <select class="form-control text-uppercase " name="dept" id="dept"  required>
                    <option value="">Select Department</option>
              


                   <?php 
$dept=$newMaster->selectDept();
                    while($row=mysqli_fetch_assoc($dept))
                    
                    {
                    ?>
                                        <option value="<?= $row['id']?>" <?php if($diag['dept']==$row['id']) echo 'selected';?>><?= $row['dept_name']?></option>
                    <?php } ?>
                 
   
                </select>
            </div>
         </div>
    </div>
<?php if(isset($_REQUEST['editdiag'])){?>
    <input type="hidden" name="page" value='<?= $_REQUEST['editdiag']?>'>
    <?php } else {?> 
        <input type="hidden" name="page" value='new'>   
    <?php } ?>
    <div class="row">
            <div class="col-md-12">
            <button type="submit" class="btn btn-success float-right btn-rounded" name="submit" value="diagnosis"><span><i class="ri-save-3-line"></i></span> Save</button>
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
                        <h5 class="ml-4">Diagnosis List</h5>
                    </div>
                    <hr>

                    <div class="table-responsive">
                    <table id="datatable-buttons" class="table table-hoverde display text-center">
                                    <thead class="thead-dark">
                                    <tr >
                                                <th>Sl NO</th>
                                                <th>Diagnosis Name</th>
                                                
                                                <th>DIH</th>
                                                <th>Min Age</th>
                                                <th>Max Age</th>
                                                <th>Gender</th>
                                                <th>Department</th>
                                                <th>Action</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody class="text-center text-black">
                                        <?php 
                                        $diagno=$newMaster->getDiagnosis();
                                        $sn=1;
                                        while($res=mysqli_fetch_assoc($diagno))
                                        {
                                        ?>
                                            <tr>
                                                <td><?= $sn?></td>
                                                <td><?= $res['name']?></td>
                                                <td><?= $res['days']?></td>
                                                <td><?= $res['min_age']?></td>
                                                <td><?= $res['max_age']?></td>
                                                <td><?= $res['sex']?></td>
                                                <td><?= $res['dept_name']?></td>
                                                <td><a href="master.php?editdiag=<?= $res['id']?>#pills-diag-tab"><span class=" badge badge-pill badge-primary p-2 text-light"><i class="bi bi-pencil mr-2"></i> Edit</span> </a></td>
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
   
   
   <div class="tab-pane fade " id="pills-diag_icd" role="tabpanel" aria-labelledby="pills-diag_icd-tab">
  <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
    <form action="controller/processmaster.php" method="POST">

  <div class="row">

                <div class="col-md-12">
                    <h4>Add Diagnosis</h4><br>
                    
                </div>
            </div> 
         <?php   $diagIcd_edit=$newMaster->getIcdDiagEdit($_REQUEST['editIcddiag']);
                        $diag_Icd=mysqli_fetch_assoc($diagIcd_edit); ?>
            <div class="row"> 
        <div class="col-md-4">
           <div class="form-group">
                  <label for="exampleInputEmail1">ICD Code <span style="color:red">*</span></label>
                  <input type="text" class="form-control text-uppercase"  name="icd" id="icd" placeholder="Enter ICD Code" value="<?= $diag['code']?>" required >
                  
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                   <label for="exampleInputEmail1">Diagnosis  <span style="color:red">*</span></label>
                   <input type="text" class="form-control text-uppercase " name="details" id="details" placeholder="Enter Diagnosi" required  value="<?= $diag['details']?>">
                   
             </div>
        </div>
        
       
       
        
       
    </div>
<?php if(isset($_REQUEST['editIcddiag'])){?>
    <input type="hidden" name="page" value='<?= $_REQUEST['editIcddiag']?>'>
    <?php } else {?> 
        <input type="hidden" name="page" value='new'>   
    <?php } ?>
    <div class="row">
            <div class="col-md-12">
            <button type="submit" class="btn btn-success float-right btn-rounded" name="submit" value="diagnosis_icd"><span><i class="ri-save-3-line"></i></span> Save</button>
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
                        <h5 class="ml-4">Diagnosis List</h5>
                    </div>
                    <hr>

                    <div class="table-responsive">
                    <table id="datatable-buttons" class="table table-hoverde display text-center">
                                    <thead class="thead-dark">
                                    <tr >
                                                <th>Sl NO</th>
                                                <th>Diagnosis Name</th>
                                                
                                                <th>DIH</th>
                                                <th>Min Age</th>
                                                <th>Max Age</th>
                                                <th>Gender</th>
                                                <th>Department</th>
                                                <th>Action</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody class="text-center text-black">
                                       
                                                                   
                                            </tbody>
                                            </table>   
                    </div>
                </div>
            </div>
        </div>
    </div>


         


</div>

<div class="tab-pane fade " id="pills-ptype" role="tabpanel" aria-labelledby="pills-ptype-tab">
<?php
$ptEdit = isset($_REQUEST['editPT']) ? (string) $_REQUEST['editPT'] : '';
$ptErrors = array(
    'empty'     => 'Please enter a patient type.',
    'duplicate' => 'That patient type already exists.',
    'failed'    => 'Could not save the patient type. Please try again.',
);
?>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <?php if (isset($_REQUEST['pt_error']) && isset($ptErrors[$_REQUEST['pt_error']])) { ?>
                        <div class="alert alert-danger"><?= $ptErrors[$_REQUEST['pt_error']] ?></div>
                    <?php } elseif (isset($_REQUEST['pt_saved'])) { ?>
                        <div class="alert alert-success">Patient type saved.</div>
                    <?php } ?>
                    <form action="controller/processmaster.php" method="POST">
                        <h4><?= $ptEdit !== '' ? 'Edit Patient Type' : 'Add Patient Type' ?></h4><br>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="patient_type">Patient Type <span style="color:red">*</span></label>
                                    <input type="text" class="form-control text-uppercase" name="patient_type" id="patient_type"
                                           placeholder="e.g. GENERAL, CASHLESS, ESI" maxlength="100" required
                                           value="<?= htmlspecialchars($ptEdit, ENT_QUOTES, 'UTF-8') ?>">
                                    <?php if ($ptEdit !== '') { ?>
                                        <small class="text-muted">Renaming does not change patients already registered with the old name.</small>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <?php if ($ptEdit !== '') { ?>
                            <input type="hidden" name="old_patient_type" value="<?= htmlspecialchars($ptEdit, ENT_QUOTES, 'UTF-8') ?>">
                        <?php } ?>
                        <div class="row">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-success float-right btn-rounded" name="submit" value="patient_type"><span><i class="ri-save-3-line"></i></span> Save</button>
                                <?php if ($ptEdit !== '') { ?>
                                    <a href="master.php#pills-ptype-tab" class="btn btn-light float-right btn-rounded mr-2">Cancel</a>
                                <?php } ?>
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
                        <h5 class="ml-4">Patient Type List</h5>
                    </div>
                    <hr>
                    <div class="table-responsive">
                        <table id="datatable-ptype" class="table table-border table-hoverde text-center">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Sl NO</th>
                                    <th>Patient Type</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="text-center text-black">
                            <?php
                            $ptypes = $newMaster->getPatientTypes();
                            $sn = 1;
                            while ($pt = mysqli_fetch_assoc($ptypes)) { ?>
                                <tr>
                                    <td><?= $sn++ ?></td>
                                    <td><?= htmlspecialchars($pt['patient_type'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><a href="master.php?editPT=<?= urlencode($pt['patient_type']) ?>#pills-ptype-tab"><span class=" badge badge-pill badge-primary p-2"><i class="bi bi-pencil mr-2"></i> Edit</span> </a></td>
                                </tr>
                            <?php } ?>
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
                     'excel', 'pdf', 'print'
                ]

            });
        });
</script>
<script>
    $(document).ready(function() {
            $('#datatable-buttons2').DataTable({

                dom: 'Bfrtip',
                responsive: true,
                pageLength: 25,
                // lengthMenu: [0, 5, 10, 20, 50, 100, 200, 500],

                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
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
                    'copy', 'csv', 'excel', 'pdf', 'print'
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
                    'copy', 'csv', 'excel', 'pdf', 'print'
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
       document.getElementById(element).className += "show active";
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
    
</script>
<script language="javascript">print_country("country");
</script>
<script language="javascript">print_country("country2");
</script>


</body>

</html>