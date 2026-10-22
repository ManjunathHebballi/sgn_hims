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
<h4 class="text-uppercase">Periodical Summary  Report  </h4>

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
                        <div class="col-md-12">
                            <div class="form-group">
                            <div class="input-group mb-3">
  <div class="input-group-prepend">
   
    <div class="input-group-text">
    Registration Type: 
    </div>
  </div>
  <div class="form-control">
                                        <input type="radio"  class="ml-4"id="typeopd" name="regtype" value="old" checked="" >
                                        OPD (New &amp; Follow Up Total)
                                        <input type="radio" class="ml-4" id="typeopd" name="regtype" value="New Registration" >
                                        New Registration
                                        <input type="radio" class="ml-4" id="typeopd" name="regtype" value="Follow Up" >
                                        Follow up
                                       
                                        <input type="radio" id="typeall" class="ml-4" name="regtype" value="All" >
                                        All
                                    </div>
</div>
                                
                        </div>
                        </div>
                      

                        <div class="col-md-12">
                            <div class="form-group">
                            <div class="input-group mb-3">
  <div class="input-group-prepend">
   
    <div class="input-group-text">
    Registration Type: 
    </div>
  </div>
                                  
                                    <div class="form-control">
                                        <input type="radio" class="ml-4" id="rptype_summary" name="rptype" value="S" checked="" >Summary report
                                        <input type="radio" class="ml-4" id="rptype_detail" name="rptype" value="D">Detail Report
                                    </div>
                                </div>
                            </div> 
                        </div>

                        <div class="col-md-12">  

                        <div class="input-group mb-3">
  <div class="input-group-prepend">
  <div class="input-group-text">
      <input type="checkbox" name="date" aria-label="Checkbox for following text input mr-5">
    </div>
    <span class="input-group-text" id="basic-addon1">Date From</span>
  </div>
  <input type="text" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">
  <div class="input-group-prepend">
    <span class="input-group-text" id="basic-addon1">Date To</span>
  </div>
  <input type="text" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">
  
</div>

                           
                                   
                          
                        </div>

                        <div class="col-sm-6">
                        <div class="input-group mb-3">
  <div class="input-group-prepend">
  <div class="input-group-text">
      <input type="checkbox" name="age" aria-label="Checkbox for following text input mr-5">
    </div>
    <span class="input-group-text" id="basic-addon1">Age From</span>
  </div>
  <select class="form-control" name="agefrom" id="agefrom">

<option value="0">Below 1 year</option>




</select> 
  
  
</div>

                        </div>
                        <div class="col-sm-6">
                        <div class="input-group mb-3">
  <div class="input-group-prepend">

    <span class="input-group-text" id="basic-addon1">Age to</span>
  </div>
  <select class="form-control" name="agefrom" id="agefrom">

                                        <option value="0">Below 1 year</option>
                                      
                                        
                                       
                                        
                                    </select> 
 
</div>

                        </div>
             

                        <div class="col-sm-6">
                        <div class="input-group mb-3">
  <div class="input-group-prepend">
  <div class="input-group-text">
      <input type="checkbox" name="dept" aria-label="Checkbox for following text input mr-5">
    </div>
    <span class="input-group-text" id="basic-addon1">Department:</span>
  </div>
  <select class="form-control" name="cboDept" id="cboDept" >
                                        <option value="0">All</option><option value="72">ART PLUS</option> 
                                    </select>
  
  
</div>

                        </div>
                        <div class="col-sm-6">
                        <div class="input-group mb-3">
  <div class="input-group-prepend">
  <div class="input-group-text">
      <input type="checkbox" name="unit" aria-label="Checkbox for following text input mr-5">
    </div>
    <span class="input-group-text" id="basic-addon1">Unit:</span>
  </div>
  <select class="form-control" name="cboRoom" id="cboRoom" >
                                        <option value="0">All</option><option value="72">ART PLUS</option> 
                                    </select>
  
  
</div>

                        </div>

                        <div class="col-sm-6">
                        <div class="input-group mb-3">
  <div class="input-group-prepend">
  <div class="input-group-text">
      <input type="checkbox" name="clinic" aria-label="Checkbox for following text input mr-5">
    </div>
    <span class="input-group-text" id="basic-addon1">Clinic:</span>
  </div>
  <select id="cboClinic" name="cboClinic" class="form-control">
                                        <option value="">--Select--</option>
                                        <option value="0">All</option>
                                        <option value="0">--Select--</option><option value="1">ART CENTER </option><option value="28">CANCER TREATMENT WING</option><option value="25">CARDIOLOGY </option><option value="31">COVID 19</option><option value="30">CVTS</option><option value="2">DENTISTRY </option><option value="3">EMERGENCY MEDICINE </option><option value="33">ENDICRIONOLOGY </option><option value="4">ENT </option><option value="5">GENERAL MEDICINE </option><option value="6">GENERAL SURGERY </option><option value="32">GYNECOLOGY ONCOLOGY</option><option value="20">IMMUNISATION-VACCINATION-EPIDEMILOGY-MAL</option><option value="19">LEPROSY </option><option value="22">MAXILOFACIAL</option><option value="8">NEPHROLOGY </option><option value="26">NEUROLOGY </option><option value="7">NEURO SURGERY </option><option value="9">OBSTETRICS AND GYNECOLOGY</option><option value="10">OPHTHALMOLOGY </option><option value="11">ORTHOPEDICS </option><option value="13">PAEDIATRCIS SURGERY </option><option value="12">PAEDIATRICS </option><option value="14">PLASTIC SURGERY </option><option value="34">PMR</option><option value="23">PMR</option><option value="29">PMSSY SUPER SPECIFICITY </option><option value="15">PSYCHIATRY </option><option value="16">PULMONARY MEDICINE </option><option value="24">RADIOTHERAPY </option><option value="17">SKIN STD</option><option value="27"> SURGICAL GASTROENTEROLOGY</option><option value="21">SURGICAL ONCOLOGY </option><option value="18">UROLOGY </option>
                                    </select>
  
  
</div>

                        </div>
                   
                        <div class="col-sm-6">
                        <div class="input-group mb-3">
  <div class="input-group-prepend">
  <div class="input-group-text">
      <input type="checkbox" name="sex" aria-label="Checkbox for following text input mr-5">
    </div>
    <span class="input-group-text" id="basic-addon1">Gender :</span>
  </div>
  <select class="form-control" id="ddlgender" name="ddlgender" tabindex="13" >
                                        <option value="0">All</option>
                                        <option value="1">Male</option>
                                        <option value="2">Female</option>
                                        <option value="4">Others</option>
                                    </select> 
  
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
                        <h5 class="ml-4">Periodical Summary  Report  <span class="text-danger">
                            <?php if(isset($_REQUEST['op_from']) && isset($_REQUEST['op_to'])) {
                            echo "Between ".  date('d.m.Y',strtotime($_REQUEST['op_from']))." and ". date('d.m.Y',strtotime($_REQUEST['op_to']));
                             } ?>
                             </span></h5>
                             </div>
<div class="col-md-6">
                             <a href="print_periodical_summary_report.php" target="_blank" class="float-right badge badge-pill badge-danger" data-toggle="tooltip" data-placement="top" title="Print Lab Report"><i class="icofont-print"></i> Print </a>
                        </div>
                    </div>
                    <hr>

                    <div class="table-responsive">
                    <table id="datatable-buttons" class="table table-hoverde table-bordered display text-center dataTable no-footer" role="grid" aria-describedby="datatable-buttons_info">
                                    <thead class="thead-nex">
                                    <tr >
                                    <th>Sl.No.</th>
                                
                            <th>Department</th>
                            <th>Unit</th>
                            <th>Male</th>
                            <th>Female</th>
                            <th>Transgender</th>
                            <th>Total Minor Male</th>
                            <th>Total Minor Female</th>
                            <th>Total Patient</th>
                                                
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>

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