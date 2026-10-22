<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/manufactureClass.php');
$newManu = new manufacture;
if(isset($_REQUEST['editid']))
{
    $manufacture=$newManu->getManufac($_REQUEST['editid']);
    $row=mysqli_fetch_assoc($manufacture);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#319197">

    <title>Add Distributor</title>
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
                                    <strong>Success!</strong> New Manufacturer Added Successfully.
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

         <form action="">
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
        <button type="submit" class="btn btn-primary">Submit</button>
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
            <h4 class="text-uppercase">Add Distributor</h4>

        </div>
        <div class="col-md-6">
      <a href="view_manufacturer.php">  <button class="btn btn-success btn-sm float-lg-right ml-lg-3 mt-lg-3" ><i class="ri-list-check"></i> View Distributor </button> </a>

       
        <!--<button class="btn btn-sm  btn-primary float-lg-right mt-sm-3" data-toggle="modal" data-target="#csv" ><i class="ri-upload-cloud-fill"></i>-->
        <!--Upload CSV</button>-->

        </div>
        <?php } else { ?>
         <div class="col-md-6">
            <h4 class="text-uppercase">Edit Distributor</h4>

        </div>
        <?php } ?>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
<form action="controller/processManufacture.php" method='POST'>

    <div class="row">
    
        <div class="col-md-3">
            <div class="form-group">
                   <label > Distributor Name <span style="color:red">*</span> </label>
                   <input type="text" class="form-control " name="name" id="name"  placeholder="Enter Distributor Name" value="<?= $row['manuf_name']?>" required> 
                   
             </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                   <label > Mobile No. <span style="color:red">*</span> </label>
                   <input type="number" class="form-control " name="mobile" id="mobile"  placeholder="Enter Mobile Number" value="<?= $row['manuf_mobile']?>" required> 
                   
             </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                   <label > Email  </label>
                   <input type="email" class="form-control " name="email" id="email"  placeholder="Enter Email ID" value="<?= $row['manuf_email']?>"> 
                   
             </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                   <label > Address  </label>
                   <textarea name="address" class="form-control" id="address" cols="1" rows="1" ><?= $row['manuf_address']?></textarea>
                   
             </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                   <label > Country <span style="color:red">*</span> </label>
                   <select class="form-control selectpicker country " data-live-search="true" id="country" name="country" onchange="changeState(this.value)" required>
                       <option value="">Select Country</option>
       <?php $country=$newManu->getCountry();
       while($row1=mysqli_fetch_assoc($country)){?>
       <option value="<?= $row1['id']?>" <?php if( $row1['id']== $row['country_id']) echo 'selected';?>><?= $row1['name']?></option>
       <?php } ?>

    </select>
             </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                   <label > State <span style="color:red">*</span> </label>
                   <select class="form-control selectpicker country " data-live-search="true" id="state" name="state" onchange="changeCity(this.value)" required>
       <option value="">Select State</option>
       <?php 
       if(isset($_REQUEST['editid']))
       {
        echo "<option value='".$row['state_id']."' selected>".$row['state']."</option>";   
       }
       ?>

    </select>
             </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                   <label > City <span style="color:red">*</span> </label>
                   <select class="form-control selectpicker country " data-live-search="true" id="city" name="city" required>
       <option value="">Select City</option>
        <?php 
       if(isset($_REQUEST['editid']))
       {
        echo "<option value='".$row['manuf_city']."' selected>".$row['city']."</option>";   
       }
       ?>

    </select>
             </div>
         </div>
           <div class="col-md-3">
            <div class="form-group">
                   <label > GST Number  </label>
                   <input type="text" class="form-control " name="gst" id="gst" value="" placeholder="Enter GST No "> 
                   
             </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                   <label > Previous Balance  </label>
                   <input type="text" class="form-control " name="balance" id="balance" value="<?= $row['prev_balance']?>" placeholder="Enter Previous Balance "> 
                   
             </div>
         </div>
       <?php 
       if(isset($_REQUEST['editid']))
       { ?>
         <div class="col-md-3">
            <div class="form-group">
                   <label > Status <span style="color:red">*</span> </label>
                   <select class="form-control selectpicker country " data-live-search="true" id="status" name="status">
       <option value="Active">Active</option>
       <option value="Inactive">Inactive</option>

    </select>
             </div>
         </div>
         <?php } ?>
</div>

<div class="row">
         <div class="col-md-12">
   <?php       
 if(isset($_REQUEST['editid']))
       { ?>
       <input type="hidden" name="editid" value="<?= $_REQUEST['editid']?>" />
        <button type="submit" class="btn btn-success  float-right" name="submit" value="edit">Update</button> 
      <?php } else {?>
            <button type="submit" class="btn btn-success  float-right" name="submit" value="new">Save</button>
         <?php } ?>   
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
        <?php include('./footer.php') ?>
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





<!-- Sweet Alerts js -->
<script src="./css/sweetalert2/sweetalert2.min.js"></script>

<!-- Sweet alert init js-->
<script src="/js/sweet-alerts.init.js"></script>


<script>
     function changeState(val)
    {
        $.ajax({
            type : 'post',
            url : 'ajax.php', //Here you will fetch records 
            data :  'country='+val+'&page=state', //Pass $id
            success : function(data){
            $('#state').html(data);//Show fetched data from database
            }
        });
    }
     function changeCity(val)
    {
        $.ajax({
            type : 'post',
            url : 'ajax.php', //Here you will fetch records 
            data :  'state='+val+'&page=city', //Pass $id
            success : function(data){
            $('#city').html(data);//Show fetched data from database
            }
        });
    }
</script>
  

</body>

</html>