<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Daily Detail Report</title>
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
<h4 class="text-uppercase">Clinical Material Report </h4>

<div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
  <div class="row">
                <div class="col-md-12">
                    <h4>Select Criteria</h4><br>
                 
                </div>
            </div> 
<form action="#" href="#pills-home" method="POST">
    <div class="row">
        <div class="col-md-4">
           <div class="form-group">
                  <label for="exampleInputEmail1">  Date </label>
                  <input type="date" class="form-control"  name="op_from" id="op_from" required value="<?= $_REQUEST['op_from']?>">
                  
            </div>
        </div>

       
         

    </div>
    
    <div class="row">
            <div class="col-md-12">
            <button type="submit" class="btn btn-success  float-right btn-rounded"  name="submit"><span><i class="bi bi-search"></i></span> Search</button>
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
                    <div class="row">
                        <div class="col-md-6">
                        <h5 class="ml-4">Clinical Material Report <span class="text-danger">
                            <?php if(isset($_REQUEST['op_from']) && isset($_REQUEST['op_to'])) {
                            echo "Between ".  date('d.m.Y',strtotime($_REQUEST['op_from']))." and ". date('d.m.Y',strtotime($_REQUEST['op_to']));
                             } ?>
                             </span></h5>
                             </div>
<div class="col-md-6">
                             <a href="print_clinical_maretial_report.php" target="_blank" class="float-right badge badge-pill badge-danger" data-toggle="tooltip" data-placement="top" title="Print Lab Report"><i class="icofont-print"></i> Print </a>
                        </div>
                    </div>
                    <hr>

                    <div class="table-responsive">
                    <table id="datatable-buttons" class="table table-hoverde table-bordered display text-center dataTable no-footer" role="grid" aria-describedby="datatable-buttons_info">
                                    <thead class="thead-nex">
                                   <tr>
                                    <th>Item</th>
                                    <th>On the day of Assessment</th>
                                   </tr>
                                                
                                          
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    O.P.D. Attendance at 2:00PM On First Day
                                                </td>
                                                <td>639</td>

                                            </tr>
                                            <tr>
                                                <td>Casuality Attendance (24hrs)</td>
                                                <td>23</td>
                                            </tr>
                                            <tr>
                                                <td>No. of Admissions</td>
                                                <td>23</td>


                                            </tr>
                                            <tr>
                                                <td>No. of Discharges</td>
                                                <td>23</td>

                                            </tr>
                                            <tr>
                                                <td>Bed Occupancy % at 10:00 AM on first Day</td>
                                                <td>65% (195 patients)</td>

                                            </tr>
                                            <tr>
                                                <th>Operative Work</th>
                                                <th></th>
                                            </tr>
                                            <tr>
                                                <td>
                                                    No. of Major Surgical Operations
                                                </td>
                                                <td>02</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    No. of Minor Surgical Operations
                                                </td>
                                                <td>02</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    No. of MajorNoramal Deliveries
                                                </td>
                                                <td>02</td>

                                            </tr>
                                            <tr>
                                                <td>
                                                    No. of Caesarian Section
                                                </td>
                                                <td>02</td>
                                            </tr>
                                            <tr>
                                                <th>Radiology Investigations</th>
                                                <th>O.P.D. /I.P.D</th>
                                            </tr>
                                            <tr>
                                                <td>X-Ray</td>
                                                <td>71/21</td>
                                            </tr>
                                            <tr>
                                                <td>Ultra Sonography</td>
                                                <td>30/05</td>
                                            </tr>
                                        </tbody>
                                       
                                            </table>   
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

    
    <!-- Datatable -->
    <script src="./vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="./js/plugins-init/datatables.init.js"></script>


  

</body>

</html>