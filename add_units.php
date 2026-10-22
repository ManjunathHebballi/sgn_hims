<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/medicineClass.php');
$newMedicine = new medicine;
if(isset($_REQUEST['editid']))
{
    $unit1=$newMedicine->getUnit($_REQUEST['editid']);
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

    <title>Add Units</title>
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
                                    <strong>Success!</strong> New Units inserted
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
            <h4 class="text-uppercase">Add Units</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
<form action="controller/processMedicine.php" method='POST'>

    <div class="row">
    
        <div class="col-md-3">
            <div class="form-group">
                   <label > Units Name <span style="color:red">*</span> </label>
                   <input type="text" class="form-control " name="name" id="name" value="<?= $row['unit_name']?>" placeholder="Enter Units Name"required> 
                   
             </div>
         </div>
         <div class="col-md-3">
             <div class="form-group">
                 <label for="">Status</label> <br>
                 <div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="status" id="status" value="Active" checked> 
  <label class="form-check-label" for="status">Active</label>
</div>
<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="status" id="status" value="Inactive" <?php if($row['status']=='Inactive') echo "checked";?>>
  <label class="form-check-label" for="status"> Inactive</label>
</div>
             </div>
         </div>

         
</div>

<div class="row">
         <div class="col-md-6">
          <?php if(isset($_REQUEST['editid'])) {?>
    <input type="hidden" name="condition" value="edit">
    <input type="hidden" name="editid" value="<?= $_REQUEST['editid']?>">
    <?php }  else {?>
    <input type="hidden" name="condition" value="new">
    <?php } ?>
            <button type="submit" class="btn btn-success  btn-rounded float-right" name="submit" value="units">Save</button>
            
         </div>
  
    </div>
</form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4>Units List</h4>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th>Sl.No.</th>
                                        <th>Units Name</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $unit=$newMedicine->getUnits();
                                        
                                        $sn=1;
                                        while($row1=mysqli_fetch_assoc($unit)){
                                        ?>
                                    <tr>
                                        <td><?= $sn?></td>
                                        <td><?= $row1['unit_name']?></td>
                                        <td><?= $row1['status']?></td>
                                        <td><a href="add_units.php?editid=<?= $row1['id']?>"  class="m-2"data-toggle="tooltip"title="Edit Unit"><span class="badge badge-light-primary "><i class="icofont-edit icofont-1x"></i></span></a></td>
                                    </tr>
                                    <?php 
                                    $sn++; } 
                                    ?>
                                    </tbody>
                                </table>
                            </div>
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
    <!--  vendors -->
    <script src="vendor/global/global.min.js"></script>
    <script src="js/quixnav-init.js"></script>
    <script src="js/custom.min.js"></script>

<script>
    function showCard(val)
    {
        // alert(val)
        if(val=='BPL')
        {
            document.getElementById('cardno').style.display='inline';
        }
        else{
            document.getElementById('cardno').style.display='none';   
        }
    }
    function getSubdept(val)
    {
        // alert(val)
        if(val=='7')
        {
            document.getElementById('subdept').style.display='inline';
        }
        else{
            document.getElementById('subdept').style.display='none';   
        }
    }
    window.setTimeout(function() {
    $(".alert").fadeTo(500, 0).slideUp(500, function(){
        $(this).remove(); 
    });
}, 4000);
</script>



<!-- Sweet Alerts js -->
<script src="css/sweetalert2/sweetalert2.min.js"></script>

<!-- Sweet alert init js-->
<script src="js/sweet-alerts.init.js"></script>
  

</body>

</html>