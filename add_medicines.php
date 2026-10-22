<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/medicineClass.php');
$newMedicine = new medicine;
if(isset($_REQUEST['editid']))
{
    $unit1=$newMedicine->getMedicineDetial($_REQUEST['editid']);
    // print_r($unit1);exit;
    $row=mysqli_fetch_assoc($unit1);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#319197">

    <title>Add Medicines</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" ></script>
        <!-- Sweet Alert-->
     <link href="css/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />


     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">

 
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
                                    <strong>Success!</strong> New Medicine is Added Successfully.
                                </div>
                         <?php   }
                            ?>
</div>

    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">


    <!-- CSV -->
<div class="modal fade" id="csv" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Upload CSV</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div class="row">
          <div class="col-md-12">
              <button class="btn btn-primary"><i class="ri-download-cloud-2-fill"></i> Download Sample</button>
          </div> 
          </div> <br>      <div class="row">

<form action="controller/processMedicine.php" method="post" enctype="multipart/form-data">
          <div class="col-md-12">
              <div class="form-group">
                  <label for="">Upload File <small class="text-danger">*</small></label>
                  <input type="file" class="form-control" placeholder="Upload CSV File" name="csv" id="csv" required>
              </div>
          </div>
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" name="submit" value="upload">Submit</button>
        </form>
      </div>
    </div>
  </div>
</div>


    

  <?php include ('leftbar.php')?>
  

        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <!-- row -->



<div class="container-fluid">
    <div class="row mb-3">
          <?php if(!isset($_REQUEST['editid'])) {?>
        <div class="col-md-6">
            <h4 class="text-uppercase">Add Medicines</h4>

        </div>
        <div class="col-md-6">
      <a href="view_medicines.php">  <button class="btn btn-success float-lg-right ml-lg-3 mt-lg-3" ><i class="ri-list-check"></i> View Medicines </button> </a>

       
        <button class="btn btn-primary float-lg-right mt-sm-3" data-toggle="modal" data-target="#csv" ><i class="ri-upload-cloud-fill"></i>
        Upload CSV</button>

        </div>
        <?php } else {?>
        <div class="col-md-6">
            <h4 class="text-uppercase">Edit Medicines</h4>

        </div>
          <?php } ?>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
<form action="controller/processMedicine.php" method='POST'>

    <div class="row">
    
        <div class="col-md-3">
            <div class="form-group">
                   <label > Medicine Name <span style="color:red">*</span> </label>
                   <input type="text" class="form-control " name="name" id="name"  value="<?=  $row['name']?>" placeholder="Enter Type Name"required> 
                   
             </div>
         </div>

         <div class="col-md-3">
            <div class="form-group">
                   <label > Strength  </label>
                   <input type="text" class="form-control " name="strength" id="strength" value="<?= $row['med_strenght']?>"  placeholder="Enter Strength"> 
                   
             </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                   <label >Generic Name <span style="color:red">*</span> </label>
                   <input type="text" class="form-control " name="geniric" id="geniric" value="<?= $row['generic_name']?>"  placeholder="Enter Generic Name">
                   
             </div>
         </div>

         <div class="col-md-3">
            <div class="form-group">
                   <label > Unit  <span style="color:red">*</span> </label>
<select name="unit" id="unit" class="form-control" data-live-search="true"  required>
<option value="" > Select Unit </option>
 
   <?php $units=$newMedicine->getUnits1();
while($row1=mysqli_fetch_assoc($units))
{
?>
<option value="<?= $row1['id']?>" <?php if($row['med_unit']==$row1['id']) echo 'selected';?>><?= $row1['unit_name']?></option>
<?php } ?>

</select>                   
             </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                   <label >Shelf</label>
                   <input type="text" class="form-control " name="shelf" id="shelf" value="<?= $row['shelf']?>"  placeholder="Enter Shelf Name">
                   
             </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                   <label >Medical Details</label>
                   <input type="text" class="form-control " name="details" id="details" value="<?= $row['med_details']?>"  placeholder="Enter Medical Details">
                   
             </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                   <label > Category  <span style="color:red">*</span> </label>
<select name="category" id="category" class="form-control " data-live-search="true"   required>
<option value="" > Select Category</option>

   <?php $category=$newMedicine->getCategories1();
while($row1=mysqli_fetch_assoc($category))
{
?>
<option value="<?= $row1['id']?>" <?php if($row['med_category']==$row1['id']) echo 'selected';?>><?= $row1['cat_name']?></option>
<?php } ?>

</select>                   
             </div>
         </div>

         <div class="col-md-3">
            <div class="form-group">
                   <label > Medicine Type  <span style="color:red">*</span> </label>
<select name="type" id="type" class="form-control " data-live-search="true"   required>
    <option value="" > Select Medicine Type</option>
  <?php $types=$newMedicine->getTypes1();
while($row1=mysqli_fetch_assoc($types))
{
?>
<option value="<?= $row1['id']?>" <?php if($row['med_type']==$row1['id']) echo 'selected';?> ><?= $row1['type_name']?></option>
<?php } ?>

</select>                   
             </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                   <label > Distributor  <span style="color:red">*</span> </label>
<select name="manufacture" id="manufacture" class="form-control bg-white" data-live-search="true"   required>
<option value="" > Select Distributor</option>
<?php $manufacture=$newMedicine->getManufacture();
while($row1=mysqli_fetch_assoc($manufacture))
{
?>
<option value="<?= $row1['id']?>" <?php if($row['med_manuf']==$row1['id']) echo 'selected';?> ><?= $row1['manuf_name']?></option>
<?php } ?>
</select>                   
             </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                   <label >Box Price<span style="color:red">*</span> </label>
                   <input type="text" class="form-control " name="price" id="price"  value="<?= $row['med_price']?>" placeholder="Enter Price ">
                   
             </div>
         </div>
         
         <div class="col-md-3">
            <div class="form-group">
                   <label >Distributor Price<span style="color:red">*</span> </label>
                   <input type="text" class="form-control " name="mprice" id="mprice"  value="<?= $row['manuf_price']?>" placeholder="Enter Price ">
                   
             </div>
         </div>
          <?php if(isset($_REQUEST['editid'])) {?>
         <div class="col-md-3">
            <div class="form-group">
                   <label >Status</label>
                   <select  class="form-control " name="status" id="status"  >
                  <option value="Active">Active</option> 
                  <option value="Inactive" <?php if($row['status']=='Inactive') echo 'selected';?>>Inactive</option>
                   </select>
             </div>
         </div>
        <?php } ?>
         
</div><br>

<div class="row">
         <div class="col-md-12">
    <?php if(isset($_REQUEST['editid'])) {?>
    <input type="hidden" name="condition" value="edit">
    <input type="hidden" name="editid" value="<?= $_REQUEST['editid']?>">
    <?php }  else {?>
    <input type="hidden" name="condition" value="new">
    <?php } ?>

            <button type="submit" class="btn btn-success  float-right" name="submit" value="medicine">Save</button>
            
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
    <script src="vendor/global/global.min.js"></script>
    <script src="js/quixnav-init.js"></script>
    <script src="js/custom.min.js"></script>

<script>
  
    window.setTimeout(function() {
    $(".alert").fadeTo(500, 0).slideUp(500, function(){
        $(this).remove(); 
    });
}, 4000);
</script>




<!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
  

</body>

</html>