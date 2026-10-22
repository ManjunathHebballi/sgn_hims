<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");

include('model/labClass.php');
$lab= new lab;
if(isset($_REQUEST['editid']))
{
    $data=$lab->getEditLabDepartment($_REQUEST['editid']);
    $row=mysqli_fetch_assoc($data);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Lab Department</title>
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
     



<!-- Summernote CSS & JS -->
<!--<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">-->

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
        <div class="col-md-6">
            <h4 class="text-uppercase">Lab Department</h4>
        </div>
        <div class="col-md-6">
           <!--<a href='packageList.php' class='btn btn-sm btn-primary float-right'>Create  New</a>-->
        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
            <div class="card">
               
                <div class="card-body">
                <h5 class="card-title">Select Criteria</h5>
<form method="post" action="controller/processLab.php" id='package-form' enctype="multipart/form-data">
    <div class="row">
       <div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputEmail1">Department Name <span class='text-danger'>*</span>  </label>
                <input type="text" class="form-control"  name="dept_name" id="dept_name" placeholder='Enter User Name..' value='<?= $row['dept_name']?>' required />
            </div>
        </div>
   <?php if(isset($_REQUEST['editid'])) { ?> 
        <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select class="form-control" name="status" >
                                            <option value="Active" <?= $row['status']=='Active' ? 'selected' : ''?>>Active</option>
                                            <option value="Inactive" <?= $row['status']=='Inactive' ? 'selected' : ''?>>Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <input type="hidden" name="editid" value="<?= $_REQUEST['editid']?>">
                                   <?php } ?>
     
        <div class="col-md-12 mt-4">
            <?php if(isset($_REQUEST['editid'])) { ?> 
            <button type="submit" name="submit" value="lab_department" class="btn btn-success float-right" >Update</button>
            <?php } else { ?>
            <button type="submit" name="submit" value="lab_department" class="btn btn-success float-right">Save</button>
            <?php } ?>
        </div>
    </div>
</form>
                </div>
            </div>
        </div>
    </div>
    
    <div class='card'>
        <div class='card-body'>
            <table class='table table-bordered'>
                <thead>
                    <tr>
                        <th>Sl No.</th>
                        <th>Department Name</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                
                <tbody>
                    <?php
                    $result=$lab->getLabDepartment();
                    $sn=1;
                    while($row=mysqli_fetch_assoc($result))
                    {
                        echo "<tr>";
                        echo "<td>$sn</td>";
                        echo "<td>".$row['dept_name']."</td>";
                        echo "<td>".$row['status']."</td>";
                        echo "<td> 
                            <a href='lab_department.php?editid=$row[id]' class='btn btn-success btn-sm' >Edit</a>
                        </td>";
                        echo "</tr>";
                        $sn++;
                    }
                    ?>
                </tbody>
            </table>
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

    <!-- jQuery first -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Popper.js (required for Bootstrap) -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>

</body>

</html>