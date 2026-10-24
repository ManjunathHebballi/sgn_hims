<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/opClass.php');
$newOP = new addOP;
$dept=$newOP->selectDept();
       $op_from=date("Y-m-d");



        $op_to=date("Y-m-d");


$todayPatientList=$newOP->selectOPregSearch($op_from,$op_to,'');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>OP- Registration</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
   <link rel="stylesheet" href="./vendor/select2/css/select2.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
      <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" ></script>
        <!-- Sweet Alert-->
     <link href="css/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
 
<style>
     #notification {
            position: absolute;
            top: 90px;
           
            right:2px;
            z-index: 99;
        }
</style>

</head>

<body>
<div class="modal fade" id="search1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Search Patient List</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      .<div class="row d-flex align-items-center">

          <div class="col-md-8">
<div class="form-group">
    
                  <lable> Search Patient </lable>
                  <input class="form-control" name="searchpatient" id="searchpatient" placeholder="search by UHID, opd no, name, mobile">
                  
                  
              </div>
              
          </div>
          
          <div class="col-md-4 ">
              <button class="btn btn-success" name="serach" onclick="patientSearchDetail()" >Search</button>
          </div>
          
          
      </div>
      
      <table class="table  table-bordered">
          <thead>
              <tr>
                  <th>Action</th>
                  <th>OPD Number</th>
                  <th> Name</th>
                  <th>Mobile </th>
                  <th>Gender</th>
                  <th>Age</th>
                  <th>Address</th>
                  
              </tr>
          </thead>
          <tbody id="serachdata">
              
          </tbody>
      </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
       
      </div>
    </div>
  </div>
</div>

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
<?php if(isset($_REQUEST['success'])&&$_REQUEST['success']=='1')
{ ?>
    
<div class="alert alert-success solid alert-dismissible fade show">
                                    <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span>
                                    </button>
                                    <strong>Success!</strong> OP Patient inserted
                                </div>
                         <?php   }
elseif(isset($_REQUEST['success'])&&$_REQUEST['success']=='0')
{
      ?>  
<div class="alert alert-danger solid alert-dismissible fade show">
                                    <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span>
                                    </button>
                                    <strong>Failed!</strong> OP Patient not inserted, Please try again
                                </div>
<?php }
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
                   <div class="col-md-8">
                                             <h3 class="page-title text-dark font-weight-bold mb-2 px-4">OP REGISTRATION FORM</h3>
                                      </div>
                                       <div class="col-md-4">
                          
                                  <div class="form-group">
                                      
<div class="input-group mb-3">
  <input list="searchpatients" class="form-control" name="searchpatient" id="searchpatient" oninput="getPatient(this.value)" onchange="getOPDdetails(this.value)" placeholder="Search by Patient Name,Patient Number">

<datalist id="searchpatients">
   
                       </datalist>
  <div class="input-group-append">
  </div>
</div>
                                          

                                  </div>

                         </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
<form action="controller/processOPRegistration.php" method='POST' id="form1">

    <!-- Shown only when this page was opened from a verified ABHA profile
         (createAabha.php -> "Register in HIMS" adds abha_prefill=1); see the
         prefill script at the bottom of this file. -->
    <div id="abhaSrcNote" class="alert alert-warning py-2 px-3 mb-2" style="display:none;">
        <i class="icofont-info-circle"></i> Prefilled from ABHA. The highlighted
        <b>Mobile</b> and <b>Address</b> are editable — correct them if the patient
        gives different details. Name, DOB, Age, Gender and the ABHA number/address
        are verified and locked.
    </div>

    <div class="row" id="abhaFieldsRow" style="display:none;">
        <div class="col-md-3">
            <div class="form-group">
                <label> ABHA Number</label>
                <input class="form-control" type="text" name="abha_number" id="abha_number" placeholder="ABHA Number" readonly>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label> ABHA Address</label>
                <input class="form-control" type="text" name="abha_address" id="abha_address" placeholder="ABHA Address" readonly>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
           <div class="form-group">
                  <label> Date</label>
                  <input class="form-control" type="date"  name="admdate" id="admdate"  value="<?php echo date('Y-m-d')?>">

            </div>
        </div>

        <div class="col-md-3">
           <div class="form-group">
                  <label> UHID <span class="text-danger">*</span></label>
                  <input class="form-control text-uppercase" type="text" name="uhid" id="uhid" placeholder="Enter UHID" maxlength="50" required oninput="this.value=this.value.replace(/\s+/g,'')">
            </div>
        </div>

        <!--<div class="col-md-3">-->
        <!--    <div class="form-group">-->
        <!--           <label > Card No </label>-->
                   <input type="hidden" class="form-control " name="card" id="card"  placeholder="Enter card No">
                   
         <!--    </div>-->
         <!--</div>-->

         <div class="col-md-3">
            <div class="form-group">
                   <label >Patient Name <span style="color: red">*</span></label>
                   <input type="text" class="form-control text-uppercase" name="fname" id="fname" placeholder="Enter Patient Name" required  >
             </div>
         </div>
         
           <div class="col-md-3">
            <div class="form-group">
                <label >  Sex <span style="color: red">*</span></label>

                <select class="form-control text-uppercase " name="sex" id="sex" required >
                    <option value="">Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                   <label for="dob">DOB</label>
                   <input type="date" class="form-control" name="dob" id="dob" placeholder="Enter DOB" max="<?= date("Y-m-d") ?>" onchange="getAge(this.value)">
             </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                   <label >Age <span style="color: red">*</span></label>
                   <input type="text" class="form-control text-uppercase" name="age" id="age" min='0' placeholder="Enter Age" required >
             </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                <label >  Department <span style="color: red">*</span></label>

                <select class="form-control text-uppercase" name="department" id="department" onchange="getDoctor(this.value)" required >
                    <option value="">Select Department</option>
                    <?php 


while($row=mysqli_fetch_assoc($dept))

{
?>
                    <option value="<?= $row['id']?>"><?= $row['dept_name']?></option>
<?php } ?>
                 
                </select>
            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                <label >  Doctor <span style="color: red">*</span></label>

                <select class="form-control text-uppercase select2doctor" name="doctor"  id="doctor" onchange="getConsultation(this.value)"  required>
                    <option value="">Select Doctor</option>
                    <?php 
$docto=$newOP->getDoctor();

while($row=mysqli_fetch_assoc($docto))

{
?>
                    <option value="<?= $row['id']?>"><?= $row['name'].' '.$row['last_name']?></option>
<?php } ?>
                 
                </select>
            </div>
         </div>
         
         
 
         <div class="col-md-3">
            <div class="form-group">
                <label>   Parent/Spouse Name </label>

                <input type="text" class="form-control text-uppercase" name="pname" id="pname"  placeholder="Enter Parent Name" >

            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                <label >  Marital Status</label>

                <select class="form-control text-uppercase" name="marital" id="marital" >
                    <option value="">Select Marital Status</option>
                    <option value="married">Married</option>
                    <option value="Unmarried">Unmarried</option>
                 
                </select>
            </div>
         </div>
       
         
         <div class="col-md-3">
            <div class="form-group">
                <label >  Religion</label>

                <select class="form-control text-uppercase" name="religion" id="religion" >
                    <option value="">Select Religion</option>
                    <option value="hindu">Hindu</option>
                    <option value="muslim">Muslim</option>
                    <option value="christian">Christian</option>
                    <option value="sikh">Sikh</option>
                    <option value="parse">Parse</option>
                    <option value="other">Other</option>

                </select>
            </div>
         </div>
          <div class="col-md-3">
            <div class="form-group">
                   <label  >Contact No. <span style="color: red">*</span></label>
                   <input type="text" class="form-control " name="mobile" id="mobile" placeholder="Enter Mobile No." required maxlength="10" minlength="10" pattern="[0-9]{10}" oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,10)" >
             </div>
         </div>
        
         
         
        
         
         <div class="col-md-3">
            <div class="form-group">
                   <label >  Address</label>
                   <textarea class="form-control text-uppercase" id="address" name="address" cols="1" rows="1" placeholder="Enter Patient Address" ></textarea>
             </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                <label >  City</label>

                <select class="form-control text-uppercase select2city" name="city" id="city" >
                    <option value="">Select City</option>
           <?php
           $data=$newOP->getCity();
           while($row=mysqli_fetch_assoc($data))
           {
           ?>
           <option value="<?= $row['name']?>"><?= $row['name']?></option>
          <?php } ?>

                </select>
            </div>
         </div>

         <!-- Postal parts of the address. A verified ABHA profile supplies all
              three, and composeAddress() keeps the Address line ending with them. -->
         <div class="col-md-3">
            <div class="form-group">
                   <label >District</label>
                   <input type="text" class="form-control text-uppercase" name="district" id="district" placeholder="Enter District">
             </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                   <label >State</label>
                   <input type="text" class="form-control text-uppercase" name="state" id="state" placeholder="Enter State">
             </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                   <label >PIN Code</label>
                   <input type="text" class="form-control" name="pincode" id="pincode" placeholder="Enter PIN Code" inputmode="numeric" maxlength="6">
             </div>
         </div>


         

                <input type='hidden' class="form-control" name="occupation" id="occupation">
                 
           
     
                <input type='hidden' class="form-control" name="caste" id="caste" >
            
         
         
 <div class="col-md-3">
            <div class="form-group">
                   <label >Patient Type</label>
                   <select name="ptype" id="ptype" class="form-control text-uppercase" >
                     <?php $data=$newOP->getPatientType();
                        while($row=mysqli_fetch_assoc($data))
                        {   
                            ?>
                            <option value="<?= $row['patient_type']?>"><?= $row['patient_type']?></option>  
                        <?php } ?>
                   </select>
             </div>
         </div>
         
          <div class="col-md-3" id="comment_type" style="display:none">
            <div class="form-group">
                <label for="exampleInputEmail1">Patient Type Comment</label>
                <input type="text" class="form-control " name="pcomment" id="pcomment" placeholder="Enter Comment"  >
            </div>
         </div>
 

         

                <input type='hidden' class="form-control " name="economic" id="economic" onchange="showCard(this.value)" >
                    
                 
           
         <div class="col-md-3" id='cardno' style="display:none">
            <div class="form-group">
                <label >  Card Number</label>

                <input type="text" class="form-control" name="cardn" id="cardn"  placeholder="Enter Card Number">

            </div>
         </div>

         
         <div class="col-md-3">
            <div class="form-group">
                <label >PAN Card No.</label>

                <input type="text" class="form-control text-uppercase" name="pan" id="pan"   placeholder="Enter PAN Card number">

            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                <label >Aadhar Card No.</label>

                <input type="text" class="form-control" name="aadhar" id="aadhar"   placeholder="Enter Aadhar Card number">

            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                <label >Policy No.</label>

                <input type="text" class="form-control" name="policy" id="policy"   placeholder="Enter Policy number">

            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                <label>  MLC</label>

                <select class="form-control" name="mlc" id="mlc" required>
                  
                    <option value="0">NO</option>
                    <option value="1">YES</option>
                    
                </select>
            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputEmail1"> Comment </label>

                <textarea class="form-control text-uppercase" name="diagnosis" id="diagnosis" cols="1" rows="1"></textarea>
</div>
</div>

         <!--<div class="col-md-3">-->
            <!--<div class="form-group">-->
                <!--<label >Consultancy</label>-->
                  <?php  $current_time = strtotime(date("H:i")); ?>
                <input type="hidden" class="form-control" name="consultancy" id="consultancy"  value="<?php if ($current_time >= strtotime('08:00') && $current_time <= strtotime('13:00')) {
                                                                                                                echo "20";
                                                                                                            }
                                                                                                            
                                                                                                            elseif ($current_time >= strtotime('16:00') && $current_time <= strtotime('19:00')) {
                                                                                                                echo "20";
                                                                                                            }
                                                                                                            else {
                                                                                                                echo "50";
                                                                                                            }?>"  placeholder="Enter Consultancy Charge">

            <!--</div>-->
         <!--</div>-->
</div>

<div class="row">
         <div class="col-md-12">
          
<input type="hidden" name="opd_no" id="opd_no" value="">
<input type="hidden" id="submit" name="submit" value="opreg">
            <button type="submit" class="btn btn-primary btn-lg float-right">Save</button>
            
         </div>
  
    </div>
</form>
                </div>
               
            </div>
        </div>
    </div>

<div class="modal fade modal2" id="add" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered mw-100 w-75">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">
OP RE-VISIT DETAILS <span class="text-danger" id="span"></span></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
    <form action="controller/processOPRegistration.php" method="post">
       <div class="row">
           <div class="col-md-3">
               <div class="form-group">
                   <label for="date"> Date</label>
                   <input value="<?php  echo date("d-m-Y H:i:s"); ?>" class="form-control" name="date"id="date" readonly >
               </div>
           </div>
           <div class="col-md-3">
               <div class="form-group">
                   <label for="dept"> Department</label>
                    <select name="dept" id="dept" class="form-control" onchange="getDoctor(this.value)" required>
                        <option value="">Select Department</option>
                        <?php 
                        $dept1=$newOP->selectDept();
                        while($row=mysqli_fetch_assoc($dept1)) {
                        ?>
                        <option value="<?= $row['id']?>"><?= $row['dept_name']?></option>
                        <?php } ?>
                    </select>               
                </div>
           </div>
           <div class="col-md-3">
            <div class="form-group">
                <label >  Doctor</label>

                <select class="form-control select2search2 " name="doctor" id="doctor2" required  onchange="getConsultation(this.value);">
                    <option value="">Select Doctor</option>
                    <?php 
$docto=$newOP->getDoctor();

while($row=mysqli_fetch_assoc($docto))

{
?>
                    <option value="<?= $row['id']?>"><?= $row['name'].' '.$row['last_name']?></option>
<?php } ?>
                 
                </select>
            </div>
         </div>

         <div class="col-md-3">
            <div class="form-group">
                <label >Refered by</label>

                <input type="text" class="form-control" name="referby" id="referby"  placeholder="Enter Doctor Name">

            </div>
         </div>
       </div>
       <div class="row">
           <div class="col-md-12">
               <div class="form-group">
                   <input type="hidden" name="opd_no1" id="opd_no1" value="">
                   <input type="hidden" class="form-control" name="consultancy1" id="consultancy1"  placeholder="Enter Consultancy Charge">
                   <button type="submit" name="submit" value="revisit" class="btn btn-success float-right btn-rounded" onclick="this.style.display='none';"><i class="ri-add-line"></i> Add</button>
               </div>
           </div>
       </div>
    </form>
    </div>

<div class="row mt-4">
    <div class="col-md-12">
    <div class="table-responsive" id="revist_modal">
                                    <table id="datatable-buttons" class="table table-bordered table-striped display text-center">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>Sl NO</th>
                                              
                                                <th>Visit Date</th>
                                                <th>Department</th>
                                                <th>Doctor Name</th>
                                                <th>Refered By</th>


                                             
                                                <th>Comments</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody class="text-center text-black">
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


<div class="row mb-3">
        <div class="col-md-12">
    <div class="card">
        <div class="card-body">
           
            <div class="row">
                <div class="col-md-4">
                    <label> OP Follow Up No <span style="color:red">*</span>  </label>
                    <div class="input-group mb-3">
 
                  <input type="text" class="form-control " name="search" id="search" onkeyup="javascript:patientDetail(this.value)" required placeholder="Search By OP No">
                  
  <div class="input-group-append">
    <button class="btn btn-outline-secondary" type="button" data-target="#search1" data-toggle="modal" id="button-addon2">Search</button>
  </div>
</div>
           <div class="form-group">
                 
            </div>
        </div>
                  <div class="col-md-4">
                    <div class="form-group">
                        <label> Patient Name</label>
                        <input type="text" class="form-control" name="name1" id="name1"readonly>
                          <input type="hidden" class="form-control" name="opno" id="opno"readonly>
                    </div>
                </div>
                
   
                
         <!--<div class="col-md-4">-->
         <!--           <div class="form-group">-->
         <!--               <label> Sex</label>-->
         <!--               <input type="hidden" class="form-control" name="sex" id="sex" readonly>-->
                        
         <!--           </div>-->
         <!--       </div>-->
                 <div class="col-md-4">
                    <div class="form-group">
                        <label> Age</label>
                        <input type="text" class="form-control" name="age" id="age1" readonly>
                        
                    </div>
                </div>
             <div class="col-md-4">
                    <div class="form-group">
                        <label> Address</label>
                        <input type="text" class="form-control" name="address" id="address1" readonly>
                        
                    </div>
                </div>
           
          
              <div class="col-md-4">
                    <div class="form-group">
                       
                        <button type="button" class="btn btn-success float-left mt-4" name="patientsrc" id="patientsrc" onclick="goToFollowUp()" value="patientsrc" title="Enter an OP No to add a follow up" disabled> Add Follow up</button>
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
    <!--  vendors -->
    <script src="./vendor/global/global.min.js"></script>
    <script src="./js/quixnav-init.js"></script>
    <script src="./js/custom.min.js"></script>

 <script src="./vendor/select2/js/select2.full.min.js"></script>
    <script src="./js/plugins-init/select2-init.js"></script>
    
<script>
    function showCard(val)
    {
        // alert(val)
        if(val=='BPL')
        {
            document.getElementById('cardno').style.display='inline';
        }
        else{
            document.getElementById('cardno').style.display='none';   
        }
    }
  
    
    
    
        
    window.setTimeout(function() {
    $(".alert").fadeTo(500, 0).slideUp(500, function(){
        $(this).remove(); 
    });
}, 4000);
</script>

<script>
    let patientSearchTimer = null;
    let patientSearchRequest = null;
    let handledPatientSelection = '';
    const revisitModalCache = {};

     function getComment(val)
    {
        if(val=='Refreance' || val=='Campus' || val=='Company')
        {
            document.getElementById('comment_type').style.display='inline';
            document.getElementById("pcomment").required = true;
        }
        else{
            document.getElementById('comment_type').style.display='none';   
            document.getElementById("pcomment").required = false;
        }
    } 
    function getConsultation(val)
    {
       
        
        dept=document.getElementById('department').value;
        dept1=document.getElementById('dept').value;
           $.ajax({
      url: 'ajax.php',
        type: 'POST', // Send post data
        data: 'id='+val+'&page=doctor_cons',
        success: function(s){
            // alert(s);
            const currentHour = new Date().getHours();

        // Check if the current hour is not between 8 to 1 or 4 to 7
        // if (((currentHour < 8 || currentHour >= 13) && (currentHour < 16 || currentHour >= 19)&&s<50) || (dept==17 || dept1==17)) {
        //     s=200;
        //       $('#consultancy').val(s);
        //   $('#consultancy1').val(s);
        // } else {
            $('#consultancy').val(s);
           $('#consultancy1').val(s);
        // }
          
        }
         });
         
        //  if(document.getElementById('department').value=='' && document.getElementById('dept').value=='')
        //  {
                   $.ajax({
      url: 'ajax.php',
        type: 'POST', // Send post data
        data: 'id='+val+'&page=doctor_dept',
        success: function(s){
            
        // Set the department value
        document.getElementById('department').value=s;
        document.getElementById('dept').value=s;
        
        // Filter department dropdown - disable all except the one for this doctor
        // const deptSelect = document.getElementById('department');
        // const deptSelect2 = document.getElementById('dept');
        
        // Disable all options first
        // for (let i = 0; i < deptSelect.options.length; i++) {
        //     deptSelect.options[i].disabled = (deptSelect.options[i].value !== s && deptSelect.options[i].value !== '');
        // }
        // for (let i = 0; i < deptSelect2.options.length; i++) {
        //     deptSelect2.options[i].disabled = (deptSelect2.options[i].value !== s && deptSelect2.options[i].value !== '');
        // }
        }
         });
        //  }
    }
    
    function resetDepartmentFilter()
    {
        // Re-enable all department options when doctor is cleared
        const deptSelect = document.getElementById('department');
        // const deptSelect2 = document.getElementById('dept');
        
        for (let i = 0; i < deptSelect.options.length; i++) {
            deptSelect.options[i].disabled = false;
        }
        // for (let i = 0; i < deptSelect2.options.length; i++) {
        //     deptSelect2.options[i].disabled = false;
        // }
        
        // Clear department selection
        deptSelect.value = '';
        deptSelect2.value = '';
    }
    
    
    function patientSearchDetail()
    {
        // alert(op)
        op=document.getElementById('searchpatient').value;
     $.ajax({
      url: 'ajax.php',
        type: 'POST', // Send post data
        data: 'op_no='+op+'&page=serach',
        success: function(s){
            // alert(s);
            json_events = s;
           $("#serachdata").html(json_events);
           
          }
        });
  }
  

  
     function modalView(val)
    {
        if(val==0)
        {
        val=document.getElementById('opno').value;
        }

        if(!val)
        {
            return;
        }

        document.getElementById('span').innerHTML='(Patient Id:'+val+')';
        document.getElementById('opd_no1').value=val;

        if (revisitModalCache[val]) {
            $('#revist_modal').html(revisitModalCache[val]);
            return;
        }

        $('#revist_modal').html('<div class="text-center py-4 font-weight-bold text-primary">Loading follow up details...</div>');
       
        $.ajax({
            type : 'post',
            url : 'ajax.php', //Here you will fetch records 
            data :  'op_no='+val+'&page=opd_modal', //Pass $id
            success : function(data){
            revisitModalCache[val] = data;
            $('#revist_modal').html(data);//Show fetched data from database
            }
        });
    }
    
  // "Add Follow up" stays disabled until the OP No lookup has actually resolved a
  // patient (#opno is only filled by a successful ipregnew lookup), so the button
  // can never navigate to opfollowup.php with an empty or half-typed OP No.
  function refreshFollowUpBtn()
    {
        var opno  = ($("#opno").val() || '').trim();
        var typed = ($("#search").val() || '').trim();
        var ok    = opno !== '' && typed !== '';
        $("#patientsrc")
            .prop('disabled', !ok)
            .attr('title', ok ? 'Add Re-Entry' : 'Enter an OP No to add a follow up');
    }

  // Open the follow-up page for the looked-up patient. opfollowup.php reads ?opd=
  // on load and fills the demographic block from central_opd itself.
  function goToFollowUp()
    {
        var opno = ($("#opno").val() || '').trim();
        if (!opno) { return; }
        window.location.href = 'opfollowup.php?opd=' + encodeURIComponent(opno);
    }

  function patientDetail(op)
    {
        // alert(op)
        // Nothing typed: clear the patient block and keep the button disabled.
        if (!(op || '').trim()) {
            $("#name1, #age1, #address1, #opno, #sex1").val('');
            refreshFollowUpBtn();
            return;
        }
     $.ajax({
      url: 'ajax.php',
        type: 'POST', // Send post data
        data: 'op_no='+op+'&page=ipregnew',
        success: function(s){
            // alert(s);
            json_events = s.split("|");
            $("#name1").val(json_events[3]);
            $("#age1").val(json_events[4]);
            $("#opno").val(json_events[1]);
            // $("#search").val(json_events[1]);
             $("#sex1").val(json_events[5]);
           // $("#sex option[value="+json_events[5]+"]").attr("selected", "selected");
            // $("#sex option:not(:selected)").attr("disabled", "disabled");
            $("#address1").val(json_events[7]);
            refreshFollowUpBtn();

          }
        });
        $('.select2doctor').select2();
  }
  
   $(document).ready(function() {

    $('.select2search').select2();
    // 
     $('.select2city').select2();
      $('.select2doctor').select2();
     
       $('.select2search2').select2({
        dropdownParent: $('.modal2')
    });
     
});
function getDoctor(val)
    {
        $.ajax({
            type : 'post',
            url : 'ajax.php', //Here you will fetch records 
            data :  'dept_id='+val+'&page=dept_doctor', //Pass $id
            success : function(data){
            $('#doctor2').html(data);//Show fetched data from database
            $('#doctor').html(data);
            }
        });
    }
  function getOPDdetails(val)
    {
        if (!val) {
            return;
        }

        // Already applied this exact selection and the card still shows it —
        // nothing to redo. (This used to test whether the follow-up modal was
        // open; that modal is no longer opened from here.)
        if (handledPatientSelection === val && ($("#opno").val() || '').trim() !== '') {
            return;
        }

        const selectedOption = $('#searchpatients option').filter(function() {
            return this.value === val;
        }).first();

        let opNo = '';
        let patientName = '';
        let patientAge = '';
        let patientAddress = '';

        if (selectedOption.length) {
            opNo = selectedOption.attr('data-opd') || '';
            patientName = selectedOption.attr('data-name') || '';
            patientAge = selectedOption.attr('data-age') || '';
            patientAddress = selectedOption.attr('data-address') || '';
        } else {
            const data = val.split("-");
            opNo = data[0] ? data[0].trim() : '';
        }

        if (opNo !== '') {
            handledPatientSelection = val;
            $('#search').val(opNo);
            $('#opno').val(opNo);
            $('#name1').val(patientName);
            $('#age1').val(patientAge);
            $('#address1').val(patientAddress);
            // Picking a patient from the search list fills the follow-up card and
            // arms the button; the follow-up itself is added on opfollowup.php,
            // so no modal is opened here.
            refreshFollowUpBtn();
        }
        
    }  
function getPatient(val) {
    clearTimeout(patientSearchTimer);

    if (!val) {
        handledPatientSelection = '';
        $('#searchpatients').html('');
        return;
    }

    const selectedOption = $('#searchpatients option').filter(function() {
        return this.value === val;
    }).first();

    if (selectedOption.length) {
        getOPDdetails(val);
        return;
    }

    handledPatientSelection = '';

    if (val.length > 2) {
        patientSearchTimer = setTimeout(function() {
            if (patientSearchRequest && patientSearchRequest.readyState !== 4) {
                patientSearchRequest.abort();
            }

            patientSearchRequest = $.ajax({
                url: 'ajax.php',
                type: 'POST',
                data: 'search=' + encodeURIComponent(val) + '&page=getPatient',
                success: function(response) {
                    if (response.length > 0) {
                        $('#searchpatients').html(response);
                    } else {
                        $('#searchpatients').html("<option value=''>NO Result Found, add New</option>");
                    }
                },
                error: function(xhr, status, error) {
                    if (status !== 'abort') {
                        console.error('Error in AJAX request:', error);
                    }
                }
            });
        }, 250);
    } else {
        $('#searchpatients').html("");
    }
}
$('#form1').submit(function(){
    $(this).find(':input[type=submit]').prop('disabled', true);
});

// ── District / State / PIN Code inside the Address line ──────────────────────
// The ABHA card shows "…, KADI, GUJARAT - 382715", so registration shows and
// stores the same complete postal address. The three fields also persist in
// their own columns; the Address line is what most of the app prints.

// Drop trailing segments that merely repeat District / State / PIN, so
// re-running composeAddress() never stacks them up.
function trimAddressDupes(addr, dupes) {
    var segs = String(addr || '').split(',').map(function(s) { return s.trim(); }).filter(Boolean);
    var norm = function(s) { return String(s || '').trim().toLowerCase().replace(/\s+/g, ' '); };
    var dupSet = dupes.map(norm).filter(Boolean);
    // Keep at least one segment so the field is never emptied.
    while (segs.length > 1 && dupSet.indexOf(norm(segs[segs.length - 1])) !== -1) {
        segs.pop();
    }
    return segs.join(', ');
}

function addressPin() {
    return ($('#pincode').val() || '').replace(/\D/g, '');
}

// The three values that belong at the end of the address line.
function addressTail() {
    return {
        district: String($('#district').val() || '').trim(),
        state: String($('#state').val() || '').trim(),
        pincode: addressPin()
    };
}

// What the box last had appended. Empty for a counter registration; seeded from
// the profile for an ABHA prefill, whose line already ends with them.
var appendedTail = { district: '', state: '', pincode: '' };

// Strip any tail the line already carries — matched against BOTH what we
// appended last time and what the fields hold now. The first covers correcting
// District from "X" to "Y" (the stale "X" still comes off); the second covers an
// operator who typed ", Kadi, Gujarat" themselves.
function stripAddressTail(value, tails) {
    var v = String(value || '').trim();
    var dupes = [];
    tails.forEach(function(t) {
        if (t.pincode) {
            v = v.replace(new RegExp('\\s*-\\s*' + t.pincode + '\\s*$'), '').trim();
        }
        dupes.push(t.district, t.state, t.pincode);
    });
    return trimAddressDupes(v, dupes);
}

// Rebuild the line from whatever the box holds now. Re-running is safe: the old
// tail comes off before the new one goes on, so nothing stacks up.
function composeAddress() {
    var t = addressTail();
    var base = stripAddressTail($('#address').val(), [appendedTail, t]);
    var line = [base, t.district, t.state].filter(Boolean).join(', ');
    if (t.pincode) line = (line ? line + ' - ' : '') + t.pincode;
    $('#address').val(line);
    appendedTail = t;
}

$(function() {
    // Filling or correcting District / State / PIN Code updates the address.
    $('#district, #state, #pincode').on('input change', composeAddress);
    // PIN Code: digits only, at most 6.
    $('#pincode').on('input', function() {
        var digits = (this.value || '').replace(/\D/g, '').slice(0, 6);
        if (digits !== this.value) this.value = digits;
    });
});

// Fill Age from a picked DOB, in this project's stored format: "54Y" for a whole
// number of years, "00Y3M12D" while the patient is under one (both shapes already
// exist in central_opd.age). Age stays a free-text field, so it can still be typed
// directly when the patient does not know their date of birth.
function getAge(birthDate) {
    var ageField = document.getElementById('age');
    if (!ageField || !birthDate) { return; }

    var today = new Date();
    var birth = new Date(birthDate);
    if (isNaN(birth.getTime()) || birth > today) { return; }

    var years  = today.getFullYear() - birth.getFullYear();
    var months = today.getMonth() - birth.getMonth();
    var days   = today.getDate() - birth.getDate();

    if (days < 0) {
        months--;
        days += new Date(today.getFullYear(), today.getMonth(), 0).getDate();
    }
    if (months < 0) {
        years--;
        months += 12;
    }

    ageField.value = (years > 0 && months === 0 && days === 0)
        ? years + 'Y'
        : (years < 10 ? '0' + years : years) + 'Y' + months + 'M' + days + 'D';
}

// Prefill the registration form from a verified ABHA profile — createAabha.php's
// "Register in HIMS" button opens this page with abha_prefill=1 plus the profile
// fields. Without that flag nothing here runs and the form behaves as before.
$(function () {
    var qp = new URLSearchParams(window.location.search);
    if (qp.get('abha_prefill') !== '1') { return; }

    function setVal(id, key) {
        var v = qp.get(key);
        if (v !== null && v !== '') { $('#' + id).val(v); }
    }

    setVal('abha_number', 'abha_number');
    setVal('abha_address', 'abha_address');
    $('#abha_address').val(($('#abha_address').val() || '').toLowerCase());
    setVal('fname', 'name');
    setVal('mobile', 'mobile');
    setVal('address', 'address');
    setVal('age', 'age');
    setVal('state', 'state');
    setVal('district', 'district');
    setVal('pincode', 'pincode');

    // The ABHA line already ends with District / State / PIN, so seed the tail
    // with them before composing — otherwise they get appended a second time.
    appendedTail = addressTail();
    composeAddress();

    // ABDM can return gender bilingually ("Male / ગંડુ" — its localizedDetails).
    // Match on the English part only; anything unrecognised leaves the select on
    // "Select Gender" rather than silently blanking it.
    var sex = qp.get('gender');
    if (sex) {
        var map = { m: 'Male', male: 'Male', f: 'Female', female: 'Female', o: 'Other', other: 'Other' };
        var key = String(sex).split('/')[0].trim().toLowerCase();
        if (map[key]) { $('#sex').val(map[key]); }
    }

    var dob = qp.get('dob');
    if (dob) {
        $('#dob').val(dob);
        getAge(dob);
    }

    $('#abhaSrcNote, #abhaFieldsRow').show();

    // Registering FROM ABHA means the identity fields are already verified, so
    // lock them. A <select> cannot be readonly, so disable Gender and mirror its
    // value into a hidden input that still submits.
    ['fname', 'dob', 'age', 'abha_number', 'abha_address'].forEach(function (id) {
        $('#' + id).prop('readonly', true);
    });
    var $sex = $('#sex').prop('disabled', true);
    if (!$('#sex_locked').length) {
        $('<input type="hidden" id="sex_locked" name="sex">').val($sex.val()).insertAfter($sex);
    }

    // Contact / location details are what staff most often need to correct (the
    // patient may give a different number or current address), so keep them
    // editable and mark them as ABHA-sourced so it is obvious they CAN be changed.
    $('#mobile, #address, #state, #district, #pincode')
        .prop('readonly', false).prop('disabled', false)
        .css('background-color', '#fff8e1')
        .attr('title', 'Prefilled from ABHA — edit if the patient gives different details');
});

</script>

<script src="css/sweetalert2/sweetalert2.min.js"></script>
  
<script>
  
</script>
</body>

</html>