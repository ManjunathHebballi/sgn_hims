
<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>UID Search</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">



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
        <div class="col-md-12">
            <h4 class="text-uppercase">OP Patient Details</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
<form action="">

    <div class="row">
        <div class="col-md-3">
            
           <div class="form-group">
                  <label for="exampleInputEmail1"> UID Number <span style="color:red">*</span></label>
                  <input type="number" class="form-control" name="uid" id="uid" aria-describedby="emailHelp" required placeholder="Enter UID Number">
                  
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1"> Date  <span style="color:red">*</span></label>
                   <input class="form-control"  name="admdate" id="admdate"  value="<?php echo date('d/m/Y  h:i:s a')?>"  readonly>
                   
                   
             </div>
         </div>

         <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1">Patient Name<span style="color:red">*</span></label>
                   <input type="text" class="form-control" name="fname" id="fname" placeholder="Enter Patient Name"  required>
             </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1">Age<span style="color:red">*</span></label>
                   <input type="number" class="form-control" name="age" id="age" placeholder="Enter Age"  required>
             </div>
         </div>

         <div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputEmail1">  Sex <span style="color:red">*</span></label>

                <select class="form-control" name="sex" id="sex" required>
                    <option value="">Select Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1">  Religion <span style="color:red">*</span></label>
                   
                   <select class="form-control" name="religion" id="religion" required>
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
                <label for="exampleInputEmail1">  Caste <span style="color:red">*</span></label>

                <select class="form-control" name="caste" id="caste" required>
                    <option value="">Select Caste</option>
                    <option value="sc">SC</option>
                    <option value="st">ST</option>
                    <option value="obc">OBC</option>
                    <option value="general">General</option>
                </select>
            </div>
         </div>
         
         <div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputEmail1">  Unit <span style="color:red">*</span></label>

                <input type="text" class="form-control" name="unit" id="unit" aria-describedby="emailHelp" required placeholder="Enter Uint">

            </div>
         </div>
         
           

         

         <div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputEmail1">  Annual Income <span style="color:red">*</span></label>

                <input type="text" class="form-control" name="income" id="income" aria-describedby="emailHelp" required placeholder="Enter Annual Income">

            </div>
         </div>

         <div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputEmail1">  Admission Fee <span style="color:red">*</span></label>
                <input type="text" class="form-control" name="fee" id="fee" aria-describedby="emailHelp" required placeholder="Enter Fee">

            
            </div>
         </div>
         

         
         

                
         


         
</div>
<div class="row">
         <div class="col-md-12">
          

            <button type="submit" class="btn btn-primary btn-lg float-right" name="submit">Save</button>
            
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
    <!-- Required vendors -->
    <script src="./vendor/global/global.min.js"></script>
    <script src="./js/quixnav-init.js"></script>
    <script src="./js/custom.min.js"></script>


  

</body>

</html>