<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/dbconnect.php');
$newlogin = new userlogin;                  
$con=$newlogin->dbconnect();

if(isset($_REQUEST['submit']))
{
    $sql="INSERT INTO `anaesthesia`(`ip_num`, `anaesthesia_type`, `dose`, `surgeon_name`, `date`) VALUES ('$_REQUEST[card]','$_REQUEST[anast]','$_REQUEST[dose]','$_REQUEST[surgeon]','$_REQUEST[admdate]')";
        $qsqlpat = mysqli_query($con,$sql);
        header("Refresh:0; url='anaesthesia.php'");
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Anaesthesia Register</title>
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
            <h4 class="text-uppercase">Anaesthesia Register</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
<form action="" method="POST">

    <div class="row">
        <div class="col-md-3">
           <div class="form-group">
                  <label for="exampleInputEmail1"> Date <span style="color:red">*</span></label>
                  <input class="form-control" type="datetime" name="admdate" id="admdate"  value="<?php echo date('d/m/Y  h:i:s a')?>">
                  
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1"> IN Patient No <span style="color:red">*</span></label>
                   <input type="text" class="form-control " name="card" id="card" aria-describedby="emailHelp" onkeyup="patientDetail(this.value)" required placeholder="Enter card No">
                   
             </div>
         </div>

         <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1">Patient Name<span style="color:red">*</span></label>
                   <input type="text" class="form-control " name="fname" id="fname" placeholder="Enter Patient Name"  required>
             </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1">Age<span style="color:red">*</span></label>
                   <input type="number" class="form-control " name="age" id="age" placeholder="Enter Age"  required>
             </div>
         </div>

         <div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputEmail1">  Sex <span style="color:red">*</span></label>

                <select class="form-control " name="sex" id="sex" required>
                    <option value="">Select Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputEmail1"> Diagnosis </label>

                <textarea class="form-control " name="diagnosis" id="diagnosis" cols="1" rows="1"></textarea>
</div>
</div>
         
         <div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputEmail1">  Anaesthesia Type <span style="color:red">*</span></label>

                <input type="text" class="form-control " name="anast" id="anast" required>
                    
            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputEmail1">  Dose <span style="color:red">*</span></label>
                <textarea class="form-control " name="dose" id="dose" cols="1" rows="1"></textarea>

                
            </div>
         </div>
         
         

         <div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputEmail1">  Surgeon Name <span style="color:red">*</span></label>

                <input type="text" class="form-control" name="surgeon" id="surgeon" aria-describedby="emailHelp" required placeholder="Enter Patient Ocupation">

            </div>
         </div>
         </div>

         
<div class="row">
         <div class="col-md-12">
          

            <button type="submit" class="btn btn-primary btn-lg float-right" name="submit" > <span><i class="bi bi-node-plus-fill"></i></span> ADD</button>
            
         </div>
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
    <!-- Required vendors -->
    <script src="./vendor/global/global.min.js"></script>
    <script src="./js/quixnav-init.js"></script>
    <script src="./js/custom.min.js"></script>
<script>
  function patientDetail(ip)
    {
        // alert(ip)
     $.ajax({
      url: 'ajax.php',
        type: 'POST', // Send post data
        data: 'ip_no='+ip+'&page=otreg',
        async: false,
        success: function(s){
            // alert(s);
            json_events = s.split("|");
            $("#fname").val(json_events[5]);
            $("#age").val(json_events[6]);
            $("#sex").val(json_events[7]);
            $("#diagnosis").val(json_events[22]);
           
          }
        });
  }
  
</script>
</body>

</html>