<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Casuality</title>
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
                                    <strong>Success!</strong>  Patient Added
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
            <h4 class="text-uppercase">Casuality Form</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
<form action="#" method='POST' >

    <div class="row">
        <div class="col-md-3">
           <div class="form-group">
                  <label> Date</label>
                  <input class="form-control"  name="admdate1" id="admdate1"  value="<?php echo date('d/m/Y')?>">
                  <input class="form-control" type='hidden' name="admdate" id="admdate"  value="<?php echo date('Y-m-d')?>">
                  
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                   <label >Title </label>
                 <select name="title" class="form-control">
                     <option value="Mr.">Mr.</option>
                       <option value="Ms.">Ms.</option>
                     <option value="Mrs.">Mrs.</option>
                     <option value="Dr.">Dr.</option>
                     <option value="Kumar.">Kumar.</option>
                     <option value="Kumari.">Kumari.</option>
                 </select>
                   
             </div>
         </div>

         <div class="col-md-3">
            <div class="form-group">
                   <label >First Name</label>
                   <input type="text" class="form-control " name="fname" id="fname" placeholder="Enter First Name" required  >
             </div>
         </div>
         
         <div class="col-md-3">
            <div class="form-group">
                   <label >Last Name</label>
                   <input type="text" class="form-control " name="lname" id="lname" placeholder="Enter Last Name" required  >
             </div>
         </div>
          <div class="col-md-3">
            <div class="form-group">
             
                   <label >Date of Birth <small class="text-danger">*</small></label>
                   <input type="date" class="form-control " name="dob" id="dob" min='0' placeholder="Enter Age in yrs" required  >
                  
             </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
             
                   <label >Age (in yrs)</label>
                   <input type="number" class="form-control " name="age_yrs" id="age_yrs" min='0' placeholder="Enter Age in yrs" required  >
                  
             </div>
         </div>
          <div class="col-md-3">
            <div class="form-group">
             
                   <label >Age (in months)</label>
                   <input type="number" class="form-control " name="age_months" id="age_yrs" min='0' placeholder="Enter Age in months" required  >
                  
             </div>
         </div>
          <div class="col-md-3">
            <div class="form-group">
             
                   <label >Age (in days)</label>
                   <input type="number" class="form-control " name="age_days" id="age_yrs" min='0' placeholder="Enter Age in days" required  >
                  
             </div>
         </div>
            <div class="col-md-3">
            <div class="form-group">
                <label >  Sex</label>

                <select class="form-control " name="sex" id="sex" required >
                    <option value="">Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
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
                <label >  State</label>

                <select class="form-control " name="state" id="state" required >
                    <option value="">Select State</option>
                    <option value="Andhra Pradesh">Andhra Pradesh</option>
<option value="Andaman and Nicobar Islands">Andaman and Nicobar Islands</option>
<option value="Arunachal Pradesh">Arunachal Pradesh</option>
<option value="Assam">Assam</option>
<option value="Bihar">Bihar</option>
<option value="Chandigarh">Chandigarh</option>
<option value="Chhattisgarh">Chhattisgarh</option>
<option value="Dadar and Nagar Haveli">Dadar and Nagar Haveli</option>
<option value="Daman and Diu">Daman and Diu</option>
<option value="Delhi">Delhi</option>
<option value="Lakshadweep">Lakshadweep</option>
<option value="Puducherry">Puducherry</option>
<option value="Goa">Goa</option>
<option value="Gujarat" selected>Gujarat</option>
<option value="Haryana">Haryana</option>
<option value="Himachal Pradesh">Himachal Pradesh</option>
<option value="Jammu and Kashmir">Jammu and Kashmir</option>
<option value="Jharkhand">Jharkhand</option>
<option value="Karnataka">Karnataka</option>
<option value="Kerala">Kerala</option>
<option value="Madhya Pradesh">Madhya Pradesh</option>
<option value="Maharashtra">Maharashtra</option>
<option value="Manipur">Manipur</option>
<option value="Meghalaya">Meghalaya</option>
<option value="Mizoram">Mizoram</option>
<option value="Nagaland">Nagaland</option>
<option value="Odisha">Odisha</option>
<option value="Punjab">Punjab</option>
<option value="Rajasthan">Rajasthan</option>
<option value="Sikkim">Sikkim</option>
<option value="Tamil Nadu">Tamil Nadu</option>
<option value="Telangana">Telangana</option>
<option value="Tripura">Tripura</option>
<option value="Uttar Pradesh">Uttar Pradesh</option>
<option value="Uttarakhand">Uttarakhand</option>
<option value="West Bengal">West Bengal</option>
                </select>
            </div>
         </div>
       <div class="col-md-3">
            <div class="form-group">
                <label >  Pin Code</label>

                <input type="text" class="form-control" name="pincode" id="pincode"   placeholder="Enter Pincode">

            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                   <label >  Address</label>
                   <textarea class="form-control" id="address" name="address" cols="1" rows="1" placeholder="Enter Patient Address"></textarea>
             </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                <label >  Religion</label>

                <select class="form-control " name="religion" id="religion" >
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
                <label >  Category</label>

                <input type="text" class="form-control" name="category" id="category"   placeholder="Enter Category">

            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                <label >  Annual Income</label>

                <input type="text" class="form-control" name="annual" id="annual"  placeholder="Enter Anual Income">

            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                <label >  Education </label>

                <select class="form-control " name="edu" id="edu" >
                    <option value="">Select Education Details</option>
                    <option value="post graduat">Post Graduate</option>
                    <option value="graduate">Graduate</option>
                    <option value="diploma">Diploma</option>
                    <option value="puc">PUC</option>
                    <option value="sslc">SSLC</option>
                    <option value="elementrary school">Elementrary School</option>
                    <option value="other">Other</option>
                </select>
            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                <label>   Guardian Name</label>

                <input type="text" class="form-control " name="gname" id="gname"  placeholder="Enter Guardian Name">

            </div>
         </div>
          <div class="col-md-3">
            <div class="form-group">
                <label>   Guardian Relation</label>

                <input type="text" class="form-control " name="gname" id="gname"  placeholder="Enter Guardian Relation with Patiant">

            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                <label >  Marital Status</label>

                <select class="form-control " name="marital" id="marital" >
                    <option value="">Select Marital Status</option>
                    <option value="married">Married</option>
                    <option value="Unmarried">Unmarried</option>
                 
                </select>
            </div>
         </div>

         

         <div class="col-md-3">
            <div class="form-group">
                <label >  Occupation</label>

                <select class="form-control" name="occupation" id="occupation">
                <option value="Engineer">Engineer</option>
           <option value="Doctor">Doctor</option>
           <option value="Teacher">Teacher</option>
           <option value="Govt.Employee">Govt.Employee</option>
           <option value="Farmer">Farmer</option>
           <option value="Kooli">Kooli</option>
           <option value="CA">CA</option>
           <option value="Business">Business</option>
           <option value="House Wife">House Wife</option>
           <option value="Labour">Labour</option>
           <option value="Service">Service</option>
           <option value="Student">Student</option>
           <option value="Others">Others</option>
          
           
                </select>
            </div>
         </div>
 

       
         <div class="col-md-3" id='cardno' style="display:none">
            <div class="form-group">
                <label >  Card Number</label>

                <input type="text" class="form-control" name="cardn" id="cardn"  placeholder="Enter Card Number">

            </div>
         </div>


      

       
</div>

<div class="row">
         <div class="col-md-12">
          

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

  

</body>

</html>