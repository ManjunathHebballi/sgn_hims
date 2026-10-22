<?php 
require_once(__DIR__ . '/../model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/billingClass.php');
$newOP = new addBilling;
$dept=$newOP->selectDept();
       $op_from=date("Y-m-d");



        $op_to=date("Y-m-d");


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
                                             <h3 class="page-title text-dark font-weight-bold mb-2 px-4">OP REGISTRATION FORM</h3>
                                      </div>
                                      
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
<form action="controller/processOPRegistration.php" method='POST' >

    <div class="row">
        <div class="col-md-3">
           <div class="form-group">
                  <label> Date</label>
                  <input class="form-control" type="date"  name="admdate" id="admdate"  value="<?php echo date('Y-m-d')?>">
                  
            </div>
        </div>

  

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
                   <label >Age <span style="color: red">*</span></label>
                   <input type="text" class="form-control text-uppercase" name="age" id="age" min='0' placeholder="Enter Age" required >
             </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                   <label  >Contact No.</label>
                   <input type="text" class="form-control " name="mobile" id="mobile" placeholder="Enter Mobile No."  >
             </div>
         </div>
        <div class="col-md-3">
            <div class="form-group">
                   <label >  Address </label>
                   <textarea class="form-control text-uppercase" id="address" name="address" cols="1" rows="1" placeholder="Enter Patient Address" ></textarea>
             </div>
         </div>
                  <div class="col-md-3">
            <div class="form-group">
                <label >  Department <span style="color: red">*</span></label>

                <select class="form-control text-uppercase" name="department" id="department" onchange='getSubdept(this.value)' required >
                    <option value="">Select Department</option>
                    <?php 


while($row=mysqli_fetch_assoc($dept))

{
?>
                    <option value="<?= $row['id']?>" <?php if($row['id']==1) echo 'selected';?>><?= $row['dept_name']?></option>
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
                    <option value="<?= $row['id']?>" <?php if($row['name']=='Hospital') echo 'selected';?>><?= $row['name'].' '.$row['last_name']?></option>
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
                <label >  City<span style="color: red">*</span></label>

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
         

 <div class="col-md-3">
            <div class="form-group">
                   <label >Patient Type <span style="color: red">*</span></label>
                   <select name="ptype" id="ptype" class="form-control"  required>
                      <option value="BGH STD - STD">BGH STD - STD</option>
                    <option value="BGH STD - CASHLESS">BGH STD - CASHLESS</option>
                    <option value="BGH STD - DLSS">BGH STD - DLSS</option>
                    <option value="BGH STD - DISPENSARY VAN">BGH STD - DISPENSARY VAN</option>
                    <option value="PMJAY">PMJAY</option>
                   </select>
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

         <input type="hidden" name="patient_type" value="<?= $_REQUEST['data']?>">
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
            <button type="submit" class="btn btn-primary btn-lg float-right" id="submit" name="submit" value="opreg">Save</button>
            
         </div>
  
    </div>
</form>
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
    function getSubdept(val)
    {
        // alert(val)
        if(val=='7')
        {
            document.getElementById('subdept').style.display='inline';
        }
        else{
            document.getElementById('subdept').style.display='none';   
        }
         $.ajax({
      url: 'ajax.php',
        type: 'POST', // Send post data
        data: 'dept='+val+'&page=doctor',
        async: false,
        success: function(s){
            // alert(s);
           $('#doctor').html(s);
        }
         });
    }
    
    
    
        
    window.setTimeout(function() {
    $(".alert").fadeTo(500, 0).slideUp(500, function(){
        $(this).remove(); 
    });
}, 4000);
</script>

<script>
 
    function getConsultation(val)
    {
        dept=document.getElementById('department').value;
           $.ajax({
      url: 'ajax.php',
        type: 'POST', // Send post data
        data: 'id='+val+'&page=doctor_cons',
        async: false,
        success: function(s){
            // alert(s);
            const currentHour = new Date().getHours();

        // Check if the current hour is not between 8 to 1 or 4 to 7
        if (((currentHour < 8 || currentHour >= 13) && (currentHour < 16 || currentHour >= 19)&&s<50)|| dept==17) {
            s=50;
              $('#consultancy').val(s);
           $('#consultancy1').val(s);
        } else {
            $('#consultancy').val(s);
           $('#consultancy1').val(s);
        }
          
        }
         });
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


   
</script>

<script src="css/sweetalert2/sweetalert2.min.js"></script>
  
<script>
  
</script>
</body>

</html>