<?php date_default_timezone_set("Asia/Kolkata");  
require_once(__DIR__ . '/../model/session_config.php');
include('model/billingClass.php');
$newBill = new addBilling;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Revenue Share</title>
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
            <h4 class="text-uppercase">Discharge</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
<form action="controller/processBilling.php" method="POST">

    <div class="row">
                <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1"> Doctor name  <span style="color:red">*</span></label>
                   <select required class="form-control" name="doctor" id="doctor">
                        <option value="">Select Doctor</option>
                        <?php $result=$newBill->selectDoctor();
                        while($row=mysqli_fetch_assoc($result))
                        { ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['name']." ".$row['last_name']; ?></option>
                        
                        <?php } ?>
                       
                    </select>
                   
             </div>
         </div>
          <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1"> Service Desc  <span style="color:red">*</span></label>
                   <select required class="form-control" name="service" id="service">
                        <option value="">Select Service</option>
                        <?php $result=$newBill->getItems();
                        while($row=mysqli_fetch_assoc($result))
                        { ?>
                        <option value="<?php echo $row['id'] ?>"><?php echo $row['name']; ?></option>
                        
                        <?php } ?>
                       
                    </select>
                   
             </div>
         </div>

         <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1">Amount Share Primary Doctor</label>
                   <input type="text" class="form-control " name="primary_doc_amt" id="primary_doc_amt" placeholder="Enter Amount"  >
             </div>
         </div>
         
         <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1">Percent Share Primary Doctor</label>
                   <input type="text" class="form-control " name="primary_doc_percent" id="primary_doc_percent" placeholder="Enter Percentage"  >
             </div>
         </div>
         
         <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1">Percent Share Hospital </label>
                   <input type="text" class="form-control " name="primary_hospital_percent" id="primary_hospital_percent" placeholder="Enter Percentage"  >
             </div>
         </div>
         
         <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1">Amount Share Secondary Doctor</label>
                   <input type="text" class="form-control " name="secondary_doc_amt" id="secondary_doc_amt" placeholder="Enter Amount"  >
             </div>
         </div>
         
         
          <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1">Percent Share Secondary Doctor</label>
                   <input type="text" class="form-control " name="second_doc_percent" id="second_doc_percent" placeholder="Enter Percentage"  >
             </div>
         </div>
        
        
         <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1">Percent Share Hospital </label>
                   <input type="text" class="form-control " name="second_hospital_percent" id="second_hospital_percent" placeholder="Enter Percentage"  >
             </div>
         </div>



         <div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputEmail1">  Active <span style="color:red">*</span></label>

                <select class="form-control " name="status" id="status" required>
                    <option value="">Status</option>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
            </div>
         </div>
         
       
        

         

         </div>
<div class="row">
         <div class="col-md-12">
          

            <button type="submit" class="btn btn-primary btn-lg float-right" name="submit" value='revenue'>Report</button>
            
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

 

</body>

</html>