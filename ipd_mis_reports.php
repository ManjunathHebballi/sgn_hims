<?php
date_default_timezone_set("Asia/Kolkata");  
require_once(__DIR__ . '/model/session_config.php');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>IPD MIS Reports</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">

    <link href="./vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">



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

  <!-- View -->


        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <!-- row -->
        <div class="container-fluid">

        <div class="row mb-4 ml-3">
          <h4 class="text-uppercase">IPD MIS Reports</h4>
        </div>


        <ul class="nav nav-pills mb-3 justify-content-around" id="pills-tab" role="tablist">
             <li class="nav-item">
    <a class="nav-link active" id="pills-daily-tab" data-toggle="pill" href="#pills-daily" role="tab" aria-controls="pills-daily" aria-selected="false">Daily Register Report </a>
  </li>
 
  <li class="nav-item">
    <a class="nav-link" id="pills-register-tab" data-toggle="pill" href="#pills-register" role="tab" aria-controls="pills-register" aria-selected="false">Periodical  Register Report </a>
  </li>
   <li class="nav-item">
    <a class="nav-link" id="pills-summary-tab" data-toggle="pill" href="#pills-summary" role="tab" aria-controls="pills-summary" aria-selected="false">Summary  Register Report </a>
  </li>
 
 
</ul>


    

       

<div class="tab-content" id="pills-tabContent">
 
 <div class="tab-pane fade show active  " id="pills-daily" role="tabpanel" aria-labelledby="pills-daily-tab"><br>

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
                        <h5 class="ml-4">IPD Patient Register Report <span class="text-danger">
                            <?php if(isset($_REQUEST['op_from']) && isset($_REQUEST['op_to'])) {
                            echo "Between ".  date('d.m.Y',strtotime($_REQUEST['op_from']))." and ". date('d.m.Y',strtotime($_REQUEST['op_to']));
                             } ?>
                             </span></h5>
                             </div>
<div class="col-md-6">
                             <a href="print_ipd_daily_report.php" target="_blank" class="float-right badge badge-pill badge-danger" data-toggle="tooltip" data-placement="top" title="Print Lab Report"><i class="icofont-print"></i> Print </a>
                        </div>
                    </div>
                    <hr>

                    <div class="table-responsive">
                    <table id="datatable-buttons" class="table table-hoverde table-bordered display text-center dataTable no-footer" role="grid" aria-describedby="datatable-buttons_info">
                                    <thead class="thead-nex">
                                    <tr>
               <th width="36" ><strong class="style3">SL.</strong></th>
               <th width="132" ><strong class="style3">UHID  . </strong></th>
               <th width="143" ><strong class="style3">Adm ID/Mrd No  </strong></th>
               <th width="90" ><strong class="style3">Patient name </strong></th>
		<th width="66" ><strong class="style3">Age/Sex</strong></th>
		<th width="133" ><strong class="style3">Father/Guardian Name/Address </strong></th>
		<th width="126" ><strong class="style3">Admission Date </strong></th>
                <th width="126" ><strong class="style3">Discharge Date </strong></th>
		<th width="128" ><strong class="style3">Dept/Unit/Ward </strong></th>
                
                <!--<th width="133" ><strong class="style3">Billing Type </strong></th>-->
                <th width="133" ><strong class="style3">Bed No </strong></th>
                
                 <th width="133" ><strong class="style3">Status </strong></th>
              


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
    <!--end of Adult Minor report tab-->
<div class="tab-pane fade   " id="pills-register" role="tabpanel" aria-labelledby="pills-register-tab"><br>
<div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
  <div class="row">
                <div class="col-md-12">
                    <h4>Select Criteria</h4><br>
                 
                </div>
            </div> 
<form action="#" href="#pills-register" method="POST">
    <div class="row">
        <div class="col-md-4">
           <div class="form-group">
                  <label for="exampleInputEmail1"> From Date </label>
                  <input type="date" class="form-control"  name="op_from" id="op_from" required value="<?= $_REQUEST['op_from']?>">
                  
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                   <label for="exampleInputEmail1"> To Date  </label>
                   <input type="date" class="form-control"  name="op_to" id="op_to" required value="<?= $_REQUEST['op_to']?>">
                   
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
                        <h5 class="ml-4">IPD Patient Periodical Register Report <span class="text-danger">
                            <?php if(isset($_REQUEST['op_from']) && isset($_REQUEST['op_to'])) {
                            echo "Between ".  date('d.m.Y',strtotime($_REQUEST['op_from']))." and ". date('d.m.Y',strtotime($_REQUEST['op_to']));
                             } ?>
                             </span></h5>
                             </div>
<div class="col-md-6">
                             <a href="print_ipd_periodic_report.php" target="_blank" class="float-right badge badge-pill badge-danger" data-toggle="tooltip" data-placement="top" title="Print Lab Report"><i class="icofont-print"></i> Print </a>
                        </div>
                    </div>
                    <hr>

                    <div class="table-responsive">
                    <table id="datatable-buttons" class="table table-hoverde table-bordered display text-center dataTable no-footer" role="grid" aria-describedby="datatable-buttons_info">
                                    <thead class="thead-nex">
                                    <tr>
               <th width="36" ><strong class="style3">SL.</strong></th>
               <th width="132" ><strong class="style3">UHID  . </strong></th>
               <th width="143" ><strong class="style3">Adm ID/Mrd No  </strong></th>
               <th width="90" ><strong class="style3">Patient name </strong></th>
		<th width="66" ><strong class="style3">Age/Sex</strong></th>
		<th width="133" ><strong class="style3">Father/Guardian Name/Address </strong></th>
		<th width="126" ><strong class="style3">Admission Date </strong></th>
                <th width="126" ><strong class="style3">Discharge Date </strong></th>
		<th width="128" ><strong class="style3">Dept/Unit/Ward </strong></th>
                
                <!--<th width="133" ><strong class="style3">Billing Type </strong></th>-->
                <th width="133" ><strong class="style3">Bed No </strong></th>
                
                 <th width="133" ><strong class="style3">Status </strong></th>
              


             </tr>               
                                                
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
    <!--end of Summary report tab-->
    
<div class="tab-pane fade" id="pills-summary" role="tabpanel" aria-labelledby="pills-summary-tab"><br>
  <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
  <div class="row">
                <div class="col-md-12">
                    <h4>Select Criteria  </h4><br>
                 
                </div>
            </div> 
<form action="#pills-summary-tab" method="POST" wtx-context="FF6BF599-C405-4F51-B0D6-63DA98437C9A">
    <div class="row">
        <div class="col-md-4">
           <div class="form-group">
                  <label for="exampleInputEmail1"> From Date </label>
                  <input type="date" class="form-control" name="op_from" id="op_from" required="" value="" wtx-context="D6B537FC-E6D7-45BA-889B-3AAC9A1F39C5">
                  
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                   <label for="exampleInputEmail1"> To Date  </label>
                   <input type="date" class="form-control" name="op_to" id="op_to" required="" value="" wtx-context="84876ABA-AD42-4470-A108-B4BA209E9A41">
                   
             </div>
        </div>
      

         

    </div>
    
    <div class="row">
            <div class="col-md-12">
            <button type="submit" class="btn btn-success  float-right btn-rounded" name="submit"><span><i class="bi bi-search"></i></span> Search</button>
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
                        <h5 class="ml-4">Summary Register Report<span class="text-danger">
                                                         </span></h5>
                             </div>
<div class="col-md-6">
                             <a href="print_mis_report.php?from=2022-11-15&amp;to=2022-11-15" target="_blank" class="float-right badge badge-pill badge-danger"><i class="icofont-print"></i> Print </a>
                        </div>
                    </div>
                    <hr>

                    <div class="table-responsive">
                    <table class="table table-bordered text-center  nowrap table-hover">
                                        <thead class="thead-nex">
                                          
                                            <tr>
                                            <th rowspan="2">Department</th>
                                              <th colspan="3">  New Patients</th>
                                              <th colspan="3">  Old Patients</th>
                                              <th rowspan="2">Total</th>
                                            </tr>
                                            <tr class="thead-nex">
                                         
                                           
                                               
                                                
                                                <th> Male </th>
                                                <th> Female </th>

                                                <th>Total New </th>
                                                <th> Male </th>
                                                <th> Female </th>

                                                <th>Total Old </th>
                                                
                        
                                              
                                                
                                            </tr>
                                        </thead>
                                        <tbody class=" text-black">
                                        
                                            <!--                                                 <tr>-->
                                            <!--<td>KAYA CHIKITSA</td>-->
                                            <!--<td>4</td>-->
                                            <!--<td>0</td>-->
                                            <!--<th>4</th>-->

                                            <!--<td>1</td>-->
                                            <!--<td>0</td>-->
                                            <!--<th>1</th>-->
                                            <!--<th>5</th>-->
                                            <!--                                            </tr>-->
                                            <!--                                            <tr>-->
                                            <!--<td>SWASTHAVRITTA</td>-->
                                            <!--<td>1</td>-->
                                            <!--<td>1</td>-->
                                            <!--<th>2</th>-->

                                            <!--<td>0</td>-->
                                            <!--<td>0</td>-->
                                            <!--<th>0</th>-->
                                            <!--<th>2</th>-->
                                            <!--                                            </tr>-->
                                            <!--                                            <tr>-->
                                            <!--<td>Physician</td>-->
                                            <!--<td>0</td>-->
                                            <!--<td>2</td>-->
                                            <!--<th>2</th>-->

                                            <!--<td>0</td>-->
                                            <!--<td>0</td>-->
                                            <!--<th>0</th>-->
                                            <!--<th>2</th>-->
                                            <!--                                            </tr>-->
                                            <!--                                            <tr>-->
                                            <!--<td>SHALYA TANTRA</td>-->
                                            <!--<td>3</td>-->
                                            <!--<td>0</td>-->
                                            <!--<th>3</th>-->

                                            <!--<td>0</td>-->
                                            <!--<td>0</td>-->
                                            <!--<th>0</th>-->
                                            <!--<th>3</th>-->
                                            <!--                                    </tr>-->
                                            

                                            

                                           
      
                                            
                           
                                               
                                            
                                            </tbody>
                                        <!--    <tfoot>-->
                                        <!--        <tr>-->
                                        <!--            <th>Total</th>-->
                                        <!--            <th>8</th>-->
                                        <!--            <th>3</th>-->
                                        <!--            <th>11</th>-->
                                        <!--            <th>1</th>-->
                                        <!--            <th>0</th>-->
                                        <!--            <th>1</th>-->
                                        <!--            <th>12</th>-->


                                        <!--        </tr>-->
                                        <!--    </tfoot>-->
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

<script>
    if(window.location.href.split("#")[1]!=undefined)
        {
            var collection=document.getElementsByClassName(' show active');
       for (let i = 0; i < collection.length; i++) {
        // collection[i].classList.remove("show"); 
        collection[i].classList.remove("active");
            }
       var collection1=document.getElementsByClassName(' active');
       for (let i = 0; i < collection1.length; i++) {
  collection1[i].classList.remove("active");
            }
           var id= window.location.href.split("#")[1];
       document.getElementById(id).className += " active";
    //    alert(.length);
    var int=id.split("-");
    var flag=0;
    var element='';
    for (let index = 0; index < int.length - 1; index++) {
        if(flag==1)
        {
            element +='-';
        }
         element += int[index];
         flag=1;
        
    }
    // alert()
       document.getElementById(element).className += " show active";
        }
</script>
  

</body>

</html>