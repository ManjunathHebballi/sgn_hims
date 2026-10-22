<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/opClass.php');
$newOP = new addOP;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Add Ambulance</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" ></script>
    <!-- Datatable -->
    <link href="./vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">

    <style>
     #notification {
            position: absolute;
            top: 90px;
           
            right:2px;
            z-index: 99;
        }

        

.table th, .table td{
    padding:15px!important;
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
            <h4 class="text-uppercase">Add Ambulance</h4>
        </div>
    </div>
    
<form action="controller/processAmbulance.php" method="post">

    <div class="card">
	<div class="card-body">
		<div class="card-title">
			<h4 class="card-title">Ambulance Details</h4>
		</div>
		<hr>
	<div class="row">
		<div class="col-md-4">
			<div class="form-group">
				<label for="validationCustom01"> Ambulane Number<span style="color: red;">*</span></label>
				<input type="text" class="form-control" required="" name="number" value="" wtx-context="08676296-4CC0-4A86-9DEB-EC727C39E7B7">
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				<label for="validationCustom01">Charge/KM<span style="color: red;">*</span></label>
				<input type="number" class="form-control" required="" name="charge" placeholder="Price" value="" wtx-context="02C40A74-5EF7-4634-94D6-65CC8B177036">
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				<label for="validationCustom01">Driver Name<span style="color: red;">*</span></label>
				<input type="text" class="form-control" required="" name="name" value="" wtx-context="85590E44-C807-46CB-87A1-9EE15C629EFD">
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				<label for="validationCustom01">Driver License<span style="color: red;">*</span></label>
				<input type="text" class="form-control" required="" name="license" value="" wtx-context="85590E44-C807-46CB-87A1-9EE15C629EFD">
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				<label for="validationCustom01"> Assistant Name<span style="color: red;">*</span></label>
				<input type="text" class="form-control" required="" name="assistant" value="" wtx-context="67899C0B-7E69-4771-8FC3-DF3BDAEDB544">
			</div>
		</div>
	<div class="col-md-4">
		<div class="form-group">
			<label for="validationCustom01">Type<span style="color: red;">*</span></label>
			<select name="vehicle_type" class="form-control" autocomplete="off">
                                            <option value="">Select</option>
                                            <option value="Owned">Owned</option>
                                            <option value="Contractual">Contractual</option>
                                        </select>
		</div>
	</div>
	<div class="col-md-4">
		<div class="form-group"><label for="validationCustom01"> Driver Contact<span style="color: red;">*</span></label>
			<input type="text" class="form-control" required="" name="contact" value="" wtx-context="1D11952B-8CCB-403B-A7B0-3CB23C10AB74">
		</div>
	</div> 
</div>
<button type="submit" class="btn btn-primary float-right" name="submit" value="add">Save</button>
</div>
</div>
</form>
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

    
    <!-- Datatable -->
    <script src="./vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="./js/plugins-init/datatables.init.js"></script>


  

</body>

</html>