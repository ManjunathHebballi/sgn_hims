<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/opClass.php');
$newOP = new addOP;
$dept=$newOP->selectDept();
$patient=$newOP->getPatient($_REQUEST['id']);
$data=mysqli_fetch_assoc($patient);
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
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
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
            <h4 class="text-uppercase">OP Registration Form</h4>
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
                  <input class="form-control" type="date"  name="admdate" id="admdate"  readonly value="<?php echo date('Y-m-d',strtotime($data['date']))?>">
                  
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                   <label > OPD No </label>
                   <input type="text" class="form-control " name="card" id="card"  readonly placeholder="Enter card No" value="<?= $data['opd_no']?>">
                   
             </div>
         </div>

         <div class="col-md-3">
            <div class="form-group">
                   <label >Patient Name</label>
                   <input type="text" class="form-control " name="fname" id="fname" placeholder="Enter Patient Name" required  value="<?= $data['name']?>">
             </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                   <label >Age</label>
                   <input type="text" class="form-control " name="age" id="age" min='0' placeholder="Enter Age"  value="<?= $data['age']?>">
             </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                   <label  >Contact No.</label>
                   <input type="text" class="form-control " name="mobile" id="mobile" placeholder="Enter Mobile No." value="<?= $data['mobile']?>" >
             </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                <label >  Sex</label>

                <select class="form-control " name="sex" id="sex" required >
                    <option value="">Select Gender</option>
                    <option value="Male" <?php if($data['gender']=='Male') echo 'selected';?>>Male</option>
                    <option value="Female" <?php if($data['gender']=='Female') echo 'selected';?>>Female</option>
                    <option value="Other" <?php if($data['gender']=='Other') echo 'selected';?>>Other</option>
                </select>
            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                   <label >  Address</label>
                   <textarea class="form-control" id="address" name="address" cols="1" rows="1" placeholder="Enter Patient Address"><?= $data['address']?></textarea>
             </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                <label >  Religion</label>

                <select class="form-control " name="religion" id="religion" >
                    <option value="">Select Religion</option>
                    <option value="hindu" <?php if($data['religion']=='hindu') echo 'selected'; ?>>Hindu</option>
                    <option value="muslim" <?php if($data['religion']=='muslim') echo 'selected'; ?>>Muslim</option>
                    <option value="christian" <?php if($data['religion']=='christian') echo 'selected'; ?>>Christian</option>
                    <option value="sikh" <?php if($data['religion']=='sikh') echo 'selected'; ?>>Sikh</option>
                    <option value="parse" <?php if($data['religion']=='parse') echo 'selected'; ?>>Parse</option>
                    <option value="other" <?php if($data['religion']=='other') echo 'selected'; ?>>Other</option>

                </select>
            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                <label>  Caste</label>

                <select class="form-control" name="caste" id="caste" >
                    <option value="">Select Caste</option>
                    <option value="sc" <?php if($data['caste']=='sc') echo 'selected';?>>SC</option>
                    <option value="st" <?php if($data['caste']=='st') echo 'selected';?>>ST</option>
                    <option value="obc" <?php if($data['caste']=='obc') echo 'selected';?>>OBC</option>
                    <option value="general" <?php if($data['caste']=='general') echo 'selected';?>>General</option>
                </select>
            </div>
         </div>
         
         <div class="col-md-3">
            <div class="form-group">
                <label>   Parent/Spouse Name</label>

                <input type="text" class="form-control " name="pname" id="pname"  placeholder="Enter Parent Name" value="<?= $data['parent_name']?>">

            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                <label >  Marital Status</label>

                <select class="form-control " name="marital" id="marital" >
                    <option value="">Select Marital Status</option>
                    <option value="married" <?php if($data['marital_status']=='married') echo 'selected'; ?>>Married</option>
                    <option value="Unmarried" <?php if($data['marital_status']=='Unmarried') echo 'selected'; ?>>Unmarried</option>
                 
                </select>
            </div>
         </div>

         

         <div class="col-md-3">
            <div class="form-group">
                <label >  Occupation</label>

                <select class="form-control" name="occupation" id="occupation">
                    <option value="">Select Occupation</option>
                <option value="Engineer" <?php if($data['occupation']=='Engineer') echo 'selected';?>>Engineer</option>
           <option value="Doctor" <?php if($data['occupation']=='Doctor') echo 'selected';?>>Doctor</option>
           <option value="Teacher" <?php if($data['occupation']=='Teacher') echo 'selected';?>>Teacher</option>
           <option value="Govt.Employee" <?php if($data['occupation']=='Govt.Employee') echo 'selected';?>>Govt.Employee</option>
           <option value="Farmer" <?php if($data['occupation']=='Farmer') echo 'selected';?>>Farmer</option>
           <option value="Kooli" <?php if($data['occupation']=='Kooli') echo 'selected';?>>Kooli</option>
           <option value="CA" <?php if($data['occupation']=='CA') echo 'selected';?>>CA</option>
           <option value="Business" <?php if($data['occupation']=='Business') echo 'selected';?>>Business</option>
           <option value="House Wife" <?php if($data['occupation']=='House Wife') echo 'selected';?>>House Wife</option>
           <option value="Labour" <?php if($data['occupation']=='Labour') echo 'selected';?>>Labour</option>
           <option value="Service" <?php if($data['occupation']=='Service') echo 'selected';?>>Service</option>
           <option value="Student" <?php if($data['occupation']=='Student') echo 'selected';?>>Student</option>
           <option value="Others" <?php if($data['occupation']=='Others') echo 'selected';?>>Others</option>
          
           
                </select>
            </div>
         </div>
 

         <div class="col-md-3">
            <div class="form-group">
                <label >  Economic State</label>

                <select class="form-control " name="economic" id="economic" onchange="showCard(this.value)" >
                    <option value="">Select Economic State</option>
                    <option value="general" <?php if($data['economic_state']=='general') echo 'selected';?>>General</option>
                    <option value="BPL" <?php if($data['economic_state']=='BPL') echo 'selected';?>>BPL</option>
                    <option value="ebl" <?php if($data['economic_state']=='ebl') echo 'selected';?>>EBL</option>
                 
                </select>
            </div>
         </div>
         <div class="col-md-3" id='cardno' style="display:none">
            <div class="form-group">
                <label >  Card Number</label>

                <input type="text" class="form-control" name="cardn" id="cardn" value="<?= $data['bpl_no']?>" placeholder="Enter Card Number">

            </div>
         </div>

         <div class="col-md-3">
            <div class="form-group">
                <label >  Department</label>

                <select class="form-control " name="department" id="department" onchange='getSubdept(this.value)' required >
                    <option value="">Select Department</option>
                    <?php 


while($row=mysqli_fetch_assoc($dept))

{
?>
                    <option value="<?= $row['id']?>" <?php if($data['dept_id']==$row['id']) echo'selected';?>><?= $row['dept_name']?></option>
<?php } ?>
                 
                </select>
            </div>
         </div>
         

                
 
 <div class="col-md-3">
            <div class="form-group">
                <label >  Doctor</label>

                <select class="form-control " name="doctor" id="doctor" required >
                    <option value="">Select Doctor</option>
                    <?php 
$docto=$newOP->getDoctor();

while($row=mysqli_fetch_assoc($docto))

{
?>
                    <option value="<?= $row['id']?>" <?php if($data['doctor']==$row['id']) echo'selected';?>><?= $row['name'].' '.$row['last_name']?></option>
<?php } ?>
                 
                </select>
            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputEmail1"> Diagnosis </label>

                <textarea class="form-control " name="diagnosis" id="diagnosis" cols="1" rows="1"><?=  $data['diagnosis']?></textarea>
</div>
</div>
<div class="col-md-3">
            <div class="form-group">
                <label >PAN Card No.</label>

                <input type="text" class="form-control" name="pan" id="pan"   placeholder="Enter PAN Card number" value="<?=  $data['pan']?>" >

            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                <label >Aadhar Card No.</label>

                <input type="text" class="form-control" name="aadhar" id="aadhar"   placeholder="Enter Aadhar Card number" value="<?=  $data['aadhar']?>" >

            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                <label >Policy No.</label>

                <input type="text" class="form-control" name="policy" id="policy"   placeholder="Enter Policy number" value="<?=  $data['policy']?>" >

            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                <label >Note</label>

                <input type="text" class="form-control" name="remark" id="remark"   placeholder="Enter Patinet Note" value="" >

            </div>
         </div>
         <!--<div class="col-md-3">-->
         <!--   <div class="form-group">-->
         <!--       <label >Consultancy</label>-->

                <input type="hidden" class="form-control" name="consultancy" id="consultancy"  placeholder="Enter Consultancy Charge">

            <!--</div>-->
         <!--</div>-->
</div>

<div class="row">
         <div class="col-md-12">
          
<input type='hidden' name='editid' value="<?= $_REQUEST['id']?>">
            <button type="submit" class="btn btn-primary btn-lg float-right" id="submit" name="submit" value="editopreg">Save</button>
            
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



<!-- Sweet Alerts js -->
<script src="css/sweetalert2/sweetalert2.min.js"></script>

<!-- Sweet alert init js-->
<script src="js/sweet-alerts.init.js"></script>
  

</body>

</html>