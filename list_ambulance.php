<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/ambulanceClass.php');
$ambulance=new ambulance;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>List Ambulance</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" ></script>
 
   <!-- DataTables -->
 <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/datatables.net-select-bs4/css//select.bootstrap4.min.css" rel="stylesheet" type="text/css" />

        <!-- Responsive datatable examples -->
        <link href="assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />  

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
  
  
     
      <!--Add Ambulance-->

<div class="modal fade" id="addambulance" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add New Ambulance</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
     <form action="controller/processAmbulance.php" method="post">

  
	
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



      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary"  name="submit" value="add">Save</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!--Edit Ambulance-->
<div class="modal fade" id="editambulance" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Ambulance</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
     <form action="controller/processAmbulance.php" method="post">

  
	
	<div class="row">
		<div class="col-md-4">
			<div class="form-group">
				<label for="validationCustom01"> Ambulane Number<span style="color: red;">*</span></label>
				<input type="text" class="form-control" required="" name="number" value="" >
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				<label for="validationCustom01">Charge/KM<span style="color: red;">*</span></label>
				<input type="number" class="form-control" required="" name="charge" placeholder="Price" value="" >
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				<label for="validationCustom01">Driver Name<span style="color: red;">*</span></label>
				<input type="text" class="form-control" required="" name="name" value="">
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				<label for="validationCustom01">Driver License<span style="color: red;">*</span></label>
				<input type="text" class="form-control" required="" name="license" value="">
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				<label for="validationCustom01"> Assistant Name<span style="color: red;">*</span></label>
				<input type="text" class="form-control" required="" name="assistant" value="">
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
			<input type="text" class="form-control" required="" name="contact" value="">
		</div>
	</div> 
</div>



      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary"  name="submit" value="add">Save Changes</button>
        </form>
      </div>
    </div>
  </div>
</div>



        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <!-- row -->




<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-6">
            <h4 class="text-uppercase"> Ambulance List</h4>
        </div>
        <div class="col-md-6">
            <!-- Button trigger modal -->
<button type="button" class="btn btn-danger btn-sm float-right" data-toggle="modal" data-target="#addambulance">
  Add New Ambulance
</button>
        </div>
    </div>
    


<!--   <div class="row">-->
<!--   <div class="col-md-12">-->
<!--      <div class="card">-->
<!--         <div class="card-body">-->
<!--            <div class="row mt-3 mb-2">-->
<!--               <div class="col-md-12">-->
<!--                  <h4>Search Ambulances </h4>-->
<!--                  <br>-->
<!--               </div>-->
<!--            </div>-->
<!--            <form method="POST" >-->
<!--               <div class="row d-flex align-items-center">-->
<!--                  <div class="col-md-4">-->
<!--                     <div class="form-group">-->
<!--                         <label for="exampleInputEmail1"> Vehicle Number </label>-->
<!--                         <input type="text" class="form-control" required="" name="vehicle_number" value="" placeholder="Search by Vehicle Number" ></div>-->
<!--                  </div>-->
<!--                    <div class="col-md-4"><button type="submit" class="btn btn-success  btn-rounded" name="submit"><span><i class="bi bi-search"></i></span> Search</button>-->
<!--               </div>-->
              
<!--               </div>-->
<!--            </form>-->
<!--         </div>-->
<!--      </div>-->
<!--   </div>-->
<!--</div>-->
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-header">
            <h4 class="card-title"> List</h4>
         </div>
         <div class="card-body">
            <div class="table-responsive">
                 <table id="datatable-buttons" class="table table-bordered  text-wrap table-hover " style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                  <thead class="text-center">
                     <tr>
                        <th>Sl NO</th>
                        <th>Number</th>
                        <th>Type</th>
                        <th>Price(per KM)</th>
                        <th>Driver Name</th>
                        <th>Assistant Name</th>
                        <th>Driver Contact</th>
                        <th>Driver Licence</th>
                        <th>Status</th>
                        <th>Action</th>
                     </tr>
                  </thead>
                  <tbody class="text-center">
                      <?php 
                      $res=$ambulance->getAmbulance();
                      $sn=1;
                      while($row=mysqli_fetch_assoc($res)){
                      ?>
                      <tr>
                          <td><?= $sn ?></td>
                          <td><?= $row['vehicle_no'] ?></td>
                          <td><?= $row['type']?></td>
                          <td><?= $row['charge']?></td>
                          <td><?= $row['driver_name']?></td>
                          <td><?= $row['assistant_name']?></td>
                          <td><?= $row['driver_licence']?></td>
                          <td><?= $row['driver_contact']?></td>
                          <td><?php if($row['status']=='ACTIVE') { ?><span class="badge badge-pill badge-soft-success"><?= $row['status']?></span><?php } elseif($row['status']=='OCCUPIED'){ ?><span class="badge badge-pill badge-soft-warning"><?= $row['status']?></span> <?php }else { ?> <span class="badge badge-pill badge-soft-danger">INACTIVE</span><?php } ?> </td>
                          <td><a class="badge badge-light mr-2" data-toggle="modal" title="Edit Ambulance Details" data-target="#editambulance"><i class="ri-edit-line ri-2"></i></a><?php if($row['status']=='ACTIVE' || $row['status']=='OCCUPIED') { ?><a class="badge badge-light mr-2 text-danger"  title="Inactive Ambulance " ><i class="ri-thumb-down-line"></i></a> <?php } else { ?> <a class="badge badge-light mr-2 text-success"  title="Active Ambulance " ><i class="ri-thumb-up-line"></i></a><?php }?> </td>
                      </tr>
                      <?php $sn++; } ?>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
</div></div>
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

    
   <!-- Required datatable js -->
    <script src="assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>

    <!-- Responsive examples -->
    <script src="assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>

    <script src="assets/js/pages/dashboard.init.js"></script>

   
    
 <!-- Required datatable js -->
 <script src="assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <!-- Buttons examples -->
    <script src="assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
    <script src="assets/libs/jszip/jszip.min.js"></script>
    <script src="assets/libs/pdfmake/build/pdfmake.min.js"></script>
    <script src="assets/libs/pdfmake/build/vfs_fonts.js"></script>
    <script src="assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="assets/libs/datatables.net-buttons/js/buttons.colVis.min.js"></script>

    <script src="assets/libs/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
    <script src="assets/libs/datatables.net-select/js/dataTables.select.min.js"></script>
 <!-- Datatable init js -->
    <script src="assets/js/pages/datatables.init.js"></script>
  


  

</body>

</html>