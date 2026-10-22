<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/userClass.php');
$newOP = new addclass;
$dept=$newOP->selectDept();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Add Doctor</title>
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
                                    <strong>Success!</strong> New Doctor Added Successfully..
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
            <h4 class="text-uppercase">Add New Doctor</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
<form action="#" method='POST'>

    <div class="row">
            <div class="col-md-3">
           <div class="form-group">
                  <label for="dob"> Code</label>
                  <input class="form-control" type="text" name="code" id="code"  >

                  
            </div>
        </div>
        <div class="col-md-3">
           <div class="form-group">
                  <label for="dob"> Date of Birth</label>
                  <input class="form-control" type="date" name="dob" id="dob"  >

                  
            </div>
        </div>

       

         <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1">First Name</label>
                   <input type="text" class="form-control " name="fname" id="fname" placeholder="Enter First Name"  >
             </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1">Last Name</label>
                   <input type="text" class="form-control " name="lname" id="lname" placeholder="Enter Last Name"  >
             </div>
         </div>
           <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1" >Abbreviated Name</label>
                   <input type="text" class="form-control " name="abbname" id="abbname"  placeholder="Enter Abbreviated Name"  >
             </div>
         </div>
          <div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputEmail1">  Sex</label>

                <select class="form-control " name="sex" id="sex" >
                    <option value="">Select Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
            </div>
         </div>
        
         <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1" >Doctor Type</label>
                   <input type="text" class="form-control " name="doctor_type" id="doctor_type"  placeholder="Enter Doctor Type"  >
             </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1" >Speciality</label>
                   <input type="text" class="form-control " name="speciality" id="speciality"  placeholder="Enter speciality"  >
             </div>
         </div>
          <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1" >Schedule Type</label>
                   <input type="text" class="form-control " name="schedule_type" id="schedule_type"  placeholder="Enter Schedule Type"  >
             </div>
         </div>
          <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1" >Registration No.</label>
                   <input type="text" class="form-control " name="registration_no" id="registration_no"  placeholder="Enter Registration No."  >
             </div>
         </div>
          <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1" >Aadhar Number</label>
                   <input type="text" class="form-control " name="aadhar" id="aadhar"  placeholder="Enter Aadhar Number"  >
             </div>
         </div>
          <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1" >Pan Card Number</label>
                   <input type="text" class="form-control " name="pancard" id="pancard"  placeholder="Enter Pan Card Number"  >
             </div>
         </div>
          <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1" >Qualification</label>
                   <input type="text" class="form-control " name="qualification" id="qualification"  placeholder="Enter qualification"  >
             </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1" >Contact No.</label>
                   <input type="text" class="form-control " name="contact" id="contact" placeholder="Enter Mobile No."  >
             </div>
         </div>
        
     
        
         <div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputEmail1">  Qualification</label>

                <input type="text" class="form-control" name="qualification" id="qualification" aria-describedby="emailHelp"  placeholder="Enter Patient Ocupation">

            </div>
         </div>
        
        

         <div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputEmail1">  Department</label>

                <select class="form-control " name="dept" id="dept"  >
                    <option value="">Select Department</option>
              


                   <?php 

// echo "<option value=''>".$dept.'</option>';
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
                <label for="exampleInputEmail1">  Email</label>

                <input type="text" class="form-control" name="email" id="email" aria-describedby="emailHelp"  placeholder="Enter Email ">

            </div>
         </div>
          <div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputEmail1">  Visit Time</label>

                <input type="time" class="form-control" name="visit" id="visit" aria-describedby="emailHelp"  placeholder="Enter Email ">

            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputEmail1">  Password</label>

                <input type="text" class="form-control" name="pass" id="pass" aria-describedby="emailHelp"  placeholder="Enter Password">

            </div>
         </div>
         

        

         
</div>
<div class="row mt-2">
   <div class="col-md-12">
        <h5> Bank Details</h5>
    <hr/>
   </div>
   <div class="col-md-3">
       <div class="form-group">
           <label>Account Type</label>
             <input type="text" class="form-control" name="bank_type" id="bank_type"   placeholder="Enter Bank Account Type">
       </div>
   </div>
   <div class="col-md-3">
       <div class="form-group">
           <label> Bank Account Number </label>
             <input type="text" class="form-control" name="account_no" id="account_no"   placeholder="Enter Bank Acount Number">
       </div>
   </div>
   <div class="col-md-3">
       <div class="form-group">
           <label> IFSC Number </label>
             <input type="text" class="form-control" name="ifsc" id="ifsc"   placeholder="Enter IFSC Number">
       </div>
   </div>
    
</div>

<div class="row">
         <div class="col-md-12">
          

            <button type="submit" class="btn btn-primary btn-lg float-right" name="submit" value="submit">Save</button>
            
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






</body>

</html>