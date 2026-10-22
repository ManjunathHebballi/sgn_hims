<?php
date_default_timezone_set("Asia/Kolkata");  
require_once(__DIR__ . '/model/session_config.php');
include('model/reportClass.php');
$date=date("Y-m-d");
$newReport = new report;
if(isset($_REQUEST['ip_from']))
{
    $ip_from=$_REQUEST['ip_from'];
}
else{
        $ip_from=$date;
}
if(isset($_REQUEST['ip_to']))
{
    $ip_to=$_REQUEST['ip_to'];
}
else{
        $ip_to=$date;
}

if(isset($_REQUEST['dept_from']))
{
    $dept_from=$_REQUEST['dept_from'];
}
else{
        $dept_from=$date;
}
if(isset($_REQUEST['dept_to']))
{
    $dept_to=$_REQUEST['dept_to'];
}
else{
        $dept_to=$date;
}


if(isset($_REQUEST['from']))
{
    $from=$_REQUEST['from'];
}
else{
        $from=$date;
}
if(isset($_REQUEST['to']))
{
    $to=$_REQUEST['to'];
}
else{
        $to=$date;
}
if(isset($_REQUEST['dept']))
{
    $dept=$_REQUEST['dept'];
}
else{
        $dept='';
}
if(isset($_REQUEST['ot_type']))
{
    $ot_type=$_REQUEST['ot_type'];
}
else{
        $ot_type='';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>OT Reports</title>
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
          <h4 class="text-uppercase">OT Reports</h4>
        </div>


        <ul class="nav nav-pills mb-3 justify-content-around" id="pills-tab" role="tablist">
  <!--<li class="nav-item">-->
  <!--  <a class="nav-link " id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true"> OT Procedure Report</a>-->
  <!--</li>-->
  <li class="nav-item">
    <a class="nav-link active" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false"> OT Type Report </a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">Department Wise OT Register</a>
  </li>

</ul>


    

       

<div class="tab-content" id="pills-tabContent">

<div class="tab-pane fade show active " id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab"><br>
<div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
  <div class="row">
                <div class="col-md-12">
                    <h4>OT Type Report  </h4><br>
                    
                </div>
            </div> 
<form action="#pills-profile-tab" method="POST">
            <div class="row">
        <div class="col-md-4">
           <div class="form-group">
                  <label for="exampleInputEmail1"> From Date </label>
                  <input type="date" class="form-control"  name="dept_from" id="dept_from" value="<?= $dept_from?>" required>
                  
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                   <label for="exampleInputEmail1"> To Date  </label>
                   <input type="date" class="form-control"  name="dept_to" id="dept_to" value="<?= $dept_to?>" required>
                   
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
                        <h5 class="ml-4">OT Type Report  Count <span class="text-danger">
                            <?php if(isset($dept_from)&&isset($dept_to)){ ?>
                            Between <?= $dept_from?> and <?= $dept_to?>
                            <?php } ?>
                            </span></h5>
                            </div>
                             <div class="col-md-6">
                             <a href="print_ot_type_reports.php?from=<?= $dept_from?>&to=<?= $dept_to?>" data-toggle="tooltip" title="Print OT Type Report" target="_blank" class="float-right badge badge-pill badge-danger"><i class="icofont-print"></i> Print </a>
                        </div>
                    </div>
                    <hr>

                    <div class="table-responsive">
                    <table id="example3" class="table table-hoverde display text-center">
                                    <thead class="thead-dark">
                                    <tr >
                                                <th>Sl NO</th>
                                                <th>Procdure Name</th>
                                                <th>Count</th>
                                            
                                                
                                                
                                                
                                                
                                            </tr>
                                        </thead>
                                        <tbody class="text-center text-black">
                                            
                                        <?php $sn=1;
                                       $data=$newReport->otTypeReport($dept_from,$dept_to);
                                       while($row=mysqli_fetch_assoc($data)){
                                       ?>
                                            <tr>
                                                <td><?= $sn?></td>
                                             <td><?= $row['ot_name']?></td>
                                             <td><a href="print_ot_procedure_details.php?from=<?= $dept_from?>&to=<?= $dept_to?>&id=<?= $row['id']?>&name=type" target="_blank" class="text-primary"><?= $row['val']?></a></td>
                                                </tr>
                                               <?php $sn++; } ?>
                                              
                                            
                                            
                                            </tbody>
                                            </table>   
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="tab-pane fade  " id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab"><br>
<div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
  <div class="row">
                <div class="col-md-6">
                    <h4>DEPARTMENT WISE OT REPORT </h4><br>
                    
                </div>
                
            </div> 
<form action="#pills-contact-tab" method="POST">
            <div class="row">
            
        <div class="col-md-3">
           <div class="form-group">
                  <label for="exampleInputEmail1"> From Date </label>
                  <input type="date" class="form-control"  name="from" id="from" value="<?= $from?>" required>
                  
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1"> To Date  </label>
                   <input type="date" class="form-control"  name="to" id="to" value="<?= $to?>" required>
                   
             </div>
        </div>
        <div class="col-md-3">
           <div class="form-group">
                  <label for="exampleInputEmail1"> Select Department </label>
                 <select name="dept" class="form-control" id="dept" >
                     <option value="">All</option>
                     <?php $dept1=$newReport->getDept();
                     while($res=mysqli_fetch_assoc($dept1)){?>
                     <option value="<?= $res['id']?>" <?php if($res['id']==$_REQUEST['dept']) echo 'selected';?>><?= $res['dept_name']?></option>
                     <?php }?>
                 </select>
                  
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1">OT Type  </label>
                   <select class="form-control"  name="ot_type" id="ot_type" >
                       <option value="">ALL</option>
                       <?php $data1=$newReport->getottype();
                       while($res=mysqli_fetch_assoc($data1)){?>
                       <option value="<?= $res['id']?>" <?php if($res['id']==$_REQUEST['ot_type']) echo 'selected';?>><?= $res['ot_name']?></option>
                     <?php }?>
                       </select>
                   
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
                       <h5 class="ml-4">DEPARTMENT WISE OT REPORT  <span class="text-danger">
                            <?php if(isset($_REQUEST['form'])&&isset($_REQUEST['to'])){ ?>
                            Between <?= $_REQUEST['form']?> and <?= $_REQUEST['to']?>
                            <?php } ?>
                            </span></h5>
                       </div>

                            <div class="col-md-6">
                             <a href="print_deptwise_ot_patient_detalis.php?dept=<?= $dept?>&from=<?= $from?>&to=<?= $to?>&ot_type=<?= $ot_type?>" data-toggle="tooltip" title="Print Department Wise OT Report" target="_blank" class="float-right badge badge-pill badge-danger"><i class="icofont-print"></i> Print </a>
                        </div>
                    </div>
                    <hr>

                    <div class="table-responsive">
                    <table id="example3" class="table table-hoverde table-bordered display text-center">
                                    <thead class="thead-dark">
                                    <tr >
                                    <th>Sl.No</th>
                          <th>Date</th>
                          <th>OP No</th>
                          <th>IP No</th>
                          <th>Patient Name</th>
                          <th>Gender</th>
                          <th>Age</th>
                          <th>OT Name</th>
                          <th>OT Procedure</th>
                          <!-- <th>Diagnosis</th> -->
                          <th>Department</th>
                          <th>Attended by</th>
                          <th>Attended Time</th>
                            <th>Created By</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody class="text-center text-black">
                            <?php $sn=1;
                            $data=$newReport->otDeptReports($from,$to,$dept,$ot_type);
                            while($row=mysqli_fetch_assoc($data)){
                            ?>                
                        <tr height="25">
                          <td><?= $sn?></td>
                        <td><?= date("d.m.Y",strtotime($row['date']))?></td>
                        <td><?= $row['opd_no']?></td>
                        <td><?= $row['ipd_no']?></td>
                        <td><?= $row['name']?></td>
                        <td><?= $row['gender']?></td>
                        <td><?= $row['age']?></td>
                        <td><?= $row['ot_name']?></td>
                        <td><?= $row['comment']?></td>
                        <!-- <td><?= $row['diagnosis']?></td> -->
                        <td><?= $row['dept_name']?></td>
                        <td><?= $row['doctor']?></td>
                        <td><?= date("h:i:s a",strtotime($row['date']))?></td>
                        <td><?= $row['created_by']?></td>
                          </tr>
                          <?php $sn++;} ?>
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