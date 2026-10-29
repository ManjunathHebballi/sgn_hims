<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata"); 
include('model/dischargeClass.php');
$newDischarge = new discharge;

if(isset($_REQUEST['max']))
{
    $max=$_REQUEST['max'];
}
else{
    $max=date('Y-m-d');
}
if(isset($_REQUEST['min']))
{
    $min=$_REQUEST['min'];
}
else{
    $min=date('Y-m-d');
}
if(isset($_REQUEST['search']))
{
    $search=$_REQUEST['search'];
}
else{
    $search="";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Discharge List</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">

    <link href="./vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">

    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.1.1/css/dataTables.dateTime.min.css "> -->

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




<div class="container-fluid text-uppercase">
    <div class="row mb-3">
        <div class="col-md-12">
            <h4 class="text-uppercase">DISCHARGE PATINETS LIST</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
<form action="#" method="POST">

    <div class="row">
        <div class="col-md-3">
            
           <div class="form-group">
                  <label for="exampleInputEmail1"> From Date  </label>
                  <input type="date" class="form-control" value="<?= $min?>" name="min" id="min">
                  
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1">To Date</label>
                   <input type="date" class="form-control" value="<?= $max?>" name="max" id="max">
                   
                   
             </div>
</div>
 <div class="col-md-3">
            
           <div class="form-group">
                  <label for="exampleInputEmail1">Search  </label>
                  <input type="text" class="form-control" value="<?= $_REQUEST['search']?>" name="search" id="search" Placeholder="Serach by IPD No., Name, diagnosis etc">
                  
            </div>
        </div>
    </div>
<div class="row">
<div class="col-md-12">

            <button type="submit"  class="btn btn-primary btn-lg d-flex float-right mt-4" name="submit"><i class="bi bi-search"></i>Search</button>
                   
             </div>
         

    

                </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="card card-body">
                                <div class="table-responsive">
                                    <table id="example" class="table table-striped display text-center">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>Sl NO</th>
                                                <th>IPD</th>
                                                <th>Visit Data</th>
                                                <th>Discharge Data</th>
                                                <th>Name</th>
                                                <th>age</th>
                                                <th>Gender</th>
                                                <th>Diagnosis</th>
                                                <?php if($_SESSION['role']=='Admin' || $_SESSION['role']=='MRD') {?>
                                                <th>Discharge By</th>
                                                <th>Action</th>
                                                <?php } ?>
                                                
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            <?php $discharge=$newDischarge->getDischarge($min,$max,$_REQUEST['search']);
                                            $sn=1;
                                            while($row=mysqli_fetch_assoc($discharge))
                                            {
                                            ?>
                                            <tr>
                                                <td><?= $sn?></td>
                                                <td><?= $row['patient_id']?></td>
                                                <td><?= date("d.m.Y",strtotime($row['admission_date']))?></td>
                                                <td><?= date("d.m.Y",strtotime($row['discharge_date']))?></td>
                                                <td><?= $row['patient_name']?></td>
                                                <td><?= $row['age']?></td>
                                                <td><?= $row['sex']?></td>
                                                <td><?= $row['diagnosis']?></td>
                                                <?php if($_SESSION['role']=='Admin' || $_SESSION['role']=='MRD') { ?>
                                                <td><?= $row['createdby']?></td>
                                                <td> <a href="editDischarge.php?id=<?= $row['id']?>" title="Edit" class="badge badge-pill badge-primary mr-2"><i class="ri-edit-line" style="font-size:15px"></i> </a>
                                                <a href="<?= $row['discharge_file']?>" title="View" class="badge badge-pill badge-warning mr-2"><i class="ri-eye-line" style="font-size:15px"></i> </a>
                                                <a href="print_discharge_summary_ip.php?id=<?= $row['patient_id']?>" target="_blank" title="Print Discharge Summary" class="badge badge-pill badge-light mr-2"><i class="ri-file-list-3-line" style="font-size:15px"></i> </a>
                                                <?php if(!empty($row['opdno'])) { ?>
                                                <a href="careContextLink.php?opd_no=<?= urlencode($row['opdno'])?>" target="_blank" title="Care Linking" class="badge badge-pill badge-info mr-2"><i class="ri-links-line" style="font-size:15px"></i> </a>
                                                <?php } ?>
                                                </td>
                                                 
                                                 <?php } ?>
                                            </tr>
                                            <?php $sn++; } ?>
                                            
                                            </tbody>
                                            </table>   
                                            </div>  
                                            </div>
                                            </div></div>
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