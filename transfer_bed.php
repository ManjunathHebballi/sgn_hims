<?php
require_once(__DIR__ . '/model/session_config.php');
 date_default_timezone_set("Asia/Kolkata");  
 include('model/ipClass.php');
$newIP = new addIP;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Transfer Bed</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">



</head>

<body onload="patientDetail('<?= $_REQUEST['id']?>')">

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
            <h4 class="text-uppercase">Transfer Bed</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
<form action="controller/processTransferBed.php" method="POST">

    <div class="row">
                <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1"> Patient ID  <span style="color:red">*</span></label>
                   <input type="text" class="form-control text-uppercase " name="patinetid" id="patinetid" aria-describedby="emailHelp" onkeyup="patientDetail(this.value)" required value="<?= $_REQUEST['id']?>" placeholder="Enter card No">
                   
             </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputEmail1">  Refernce No <span style="color:red">*</span></label>

                <input type="text" class="form-control text-uppercase" name="refno" id="refno" aria-describedby="emailHelp"  placeholder="Enter Card Number">

            </div>
         </div>

         <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1">Patient Name<span style="color:red">*</span></label>
                   <input type="text" class="form-control text-uppercase " name="fname" id="fname" placeholder="Enter Patient Name"  required>
             </div>
         </div>




        <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1">Age<span style="color:red">*</span></label>
                   <input type="number" class="form-control text-uppercase " name="age" id="age" placeholder="Enter Age"  required>
             </div>
         </div>
        
        <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1">Gender<span style="color:red">*</span></label>
                   <input type="text" class="form-control text-uppercase " readonly name="gender" id="gender" placeholder="Enter Gender"  required>
             </div>
         </div>
         
         <div class="col-md-3">
           <div class="form-group">
                  <label for="exampleInputEmail1"> Admission Date <span style="color:red">*</span></label>
                  <input class="form-control text-uppercase" type="datetime-local"  name="admidate" id="admidate"  value=""  readonly>
                  
            </div>
        </div>

        <div class="col-md-3">
           <div class="form-group">
                  <label for="exampleInputEmail1"> Doctor<span style="color:red">*</span></label>
                  <input class="form-control text-uppercase" type="text"  name="doctor" id="doctor"  value=""  readonly>
                  
            </div>
        </div>
        <div class="col-md-3">
           <div class="form-group">
                  <label for="exampleInputEmail1"> Department <span style="color:red">*</span></label>
                  <input class="form-control text-uppercase" type="text"  name="dept" id="dept"  value=""  readonly>
                  
            </div>
        </div>

        <div class="col-md-3">
           <div class="form-group">
                  <label for="exampleInputEmail1"> Present Ward <span style="color:red">*</span></label>
                  <input class="form-control text-uppercase" type="text"  name="pre_ward" id="pre_ward"  value=""  readonly>
                  
            </div>
        </div>
        <div class="col-md-3">
           <div class="form-group">
                  <label for="exampleInputEmail1"> Present Bed <span style="color:red">*</span></label>
                  <input class="form-control text-uppercase" type="text"  name="old_bed" id="old_bed"  value=""  readonly>
                  <input class="form-control text-uppercase" type="hidden"  name="pres_bed" id="pres_bed"  value=""  readonly>
                  
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label> Transfer Ward <span style="color:red">*</span>  </label>

                <select class="form-control text-uppercase " name="ward" id="ward" onchange="getBed(this.value)" required>
                    <option value="">Select Word</option>
                    <?php $ward=$newIP->selectWard(); 
                    while ($res=mysqli_fetch_assoc($ward))
                    {?>
                    <option value="<?= $res['ward']?>"><?= $res['ward']?></option>
                    <?php } ?>

                 
                </select>
            </div>
         </div>
         

                
         <div class="col-md-3">
            <div class="form-group">
                <label>Transfer Bed No <span style="color:red">*</span>  </label>

                <select class="form-control text-uppercase " name="bed" id="bed" required>
                    <option value="">Select Bed No</option>
                    <?php $bed=$newIP->selectBed(); 
                    while ($res=mysqli_fetch_assoc($bed))
                    {?>
                    <option value="<?= $res['id']?>"><?= $res['bed_number']?></option>
                    <?php } ?>

                 
                </select>
            </div>
         </div>
        


       
      

         

         </div>
<div class="row">
         <div class="col-md-12">
          
<input type="hidden" name="deptid" id="deptid" value="">
            <button type="submit" class="btn btn-primary btn-lg float-right" name="submit" value="transfer_bed">Transfer</button>
            
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
        data: 'ip_no='+ip+'&page=transfer_bed',
        async: false,
        success: function(s){
            // alert(s);
            json_events = s.split("|");
            
            $("#pre_ward").val(json_events[21]);
            $("#pres_bed").val(json_events[20]);
            $("#old_bed").val(json_events[34]);
             $("#dept").val(json_events[31]);
            $("#refno").val(json_events[3]);
            $("#fname").val(json_events[5]);
             $("#doctor").val(json_events[18]);
             $("#gender").val(json_events[7]);
            $("#age").val(json_events[6]);
            
           
            $("#diagnosis").val(json_events[22]);
            $("#admidate").val(json_events[4]);
            $("#deptid").val(json_events[11]);
           
          }
        });
        
        
          $.ajax({
      url: 'ajax.php',
        type: 'POST', // Send post data
        data: 'dept='+json_events[11]+'&page=ward',
        async: false,
        success: function(s){
            // alert(s);
           $('#ward').html(s);
        }
         });
  }
  
  function getBed(val)
    {
        dept=document.getElementById('deptid').value;
             $.ajax({
          url: 'ajax.php',
            type: 'POST', // Send post data
            data: 'dept='+dept+'&ward='+val+'&page=bed',
            async: false,
            success: function(s){
                // alert(s);
               $('#bed').html(s);
            }
         });
    }

  </script>
 

</body>

</html>