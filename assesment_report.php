<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  

include('model/reportClass.php');
$newReport = new report;
$date=date("Y-m-d");
if(isset($_REQUEST['op_from']))
{
    $op_from=$_REQUEST['op_from'];
}
else{
        $op_from=$date;
}
if(isset($_REQUEST['op_to']))
{
    $op_to=$_REQUEST['op_to'];
}
else{
        $op_to=$date;
}
if(isset($_REQUEST['from']))
{
    $from=$_REQUEST['from'];
}
else{
        $from=$date;
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Assessment Report</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">

    <!-- DataTables -->
    
        <link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/datatables.net-select-bs4/css/select.bootstrap4.min.css" rel="stylesheet" type="text/css" />




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
          <h4 class="text-uppercase">Assessment Report</h4>
        </div>


        <ul class="nav nav-pills mb-3 justify-content-around" id="pills-tab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="pills-clinical-tab" data-toggle="pill" href="#pills-clinical" role="tab" aria-controls="pills-clinical" aria-selected="true">  Clinical Material</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="pills-lab-tab" data-toggle="pill" href="#pills-lab" role="tab" aria-controls="pills-lab" aria-selected="false"> Lab Report</a>
  </li>
 
</ul>


    

       

<div class="tab-content" id="pills-tabContent">
 
<div class="tab-pane fade  show active  " id="pills-clinical" role="tabpanel" aria-labelledby="pills-clinical-tab"><br>
<div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                     <form action="#pills-clinical-tab" method="POST">
  <div class="row">
                <div class="col-md-12">
                    <h4>Clinical Material Report </h4><br>
                   
                </div>
            </div> 

            <div class="row">
            
           
        <div class="col-md-4">
           <div class="form-group">
                  <label for="exampleInputEmail1">  Date </label>
                  <input type="date" class="form-control"  name="from" id="from" value="<?= $from?>">
                  
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
                        <h5 class="ml-4">Clinical Material Report <span class="text-danger">
                            <?php if(isset($from) && isset($to)) {
                            echo "of ".  date('d.m.Y',strtotime($from));
                             } ?>
                             </span></h5>
                             </div>
<div class="col-md-6">
                             <a href="print_clinical_maretial_report.php?date=<?= $from?>" target="_blank" class="float-right badge badge-pill badge-danger" data-toggle="tooltip" data-placement="top" title="Print Lab Report"><i class="icofont-print"></i> Print </a>
                        </div>
                    </div>
                    <hr>

                    <div class="table-responsive">
                    <table id="datatable-buttons4" class="table table-hoverde table-bordered text-wrap text-center" >
                                    <thead class="thead-nex">
                                   <tr>
                                    <th>Item</th>
                                    <th colspan="2">On the day of Assessment</th>
                                   </tr>
                                                
                                          
                                        </thead>
                                        <tbody>
                                            <?php $data=$newReport->overalldetails($from);
                                            $res=mysqli_fetch_assoc($data);
                                            ?>
                                            <tr>
                                                <td>
                                                    O.P.D. Attendance 
                                                </td>
                                                <td colspan="2"><?= $res['opd']?></td>

                                            </tr>
                                            <tr>
                                                <td>Casuality Attendance (24hrs)</td>
                                                <td colspan="2"><?= $res['casuality']?></td>
                                            </tr>
                                            <tr>
                                                <td>No. of Admissions</td>
                                                <td colspan="2"><?= $res['admission']?></td>


                                            </tr>
                                            <tr>
                                                <td>No. of Discharges</td>
                                                <td colspan="2"><?= $res['discharge']?></td>

                                            </tr>
                                            <tr>
                                                <td>Bed Occupancy % </td>
                                                <td colspan="2"><?= round($res['occupied']*100/$res['bed'],2)?>% (<?= $res['occupied']?> patients)</td>

                                            </tr>
                                            <tr>
                                                <th><br><u><b>Operative Work</b></u></th>
                                                <th colspan="2"></th>
                                            </tr>
                                            <?php
                                            $res=$newReport->otTypeReport($from,$from);
                                            while($row=mysqli_fetch_assoc($res))
                                            {
                                            ?>
                                            
                                           
                                            <tr>
                                                <td>
                                                    No. of <?= $row['ot_name']?>
                                                </td>
                                                <td colspan="2"><?= $row['val']?></td>
                                            </tr>
                                            
                                            <?php } ?>
                                            <tr>
                                                <th>Radiology Investigations</th>
                                                <th colspan=>O.P.D.</th>
                                                <th>I.P.D</th>
                                            </tr>
                                            <?php 
                                            $data=$newReport->radioReport($from,$from);
                                            while($row=mysqli_fetch_assoc($data))
                                            {
                                            ?>
                                            <tr>
                                                <td><?= $row['testname']?></td>
                                                <td><?= $row['OP']?></td>
                                                <td><?= $row['IP']?></td>
                                            </tr>
                                            <?php } ?>
                            
                                    <tr >
                                               
                                                <th><u>Laboratory Investigations-No of Tests</u></th>
                                                <th>O.P.D</th>
                                                <th>I.P.D</th>
                                                
                                                
                                                
                                                
                                            </tr>
                                      
                                            <?php $data=$newReport->labReport($from,$from);
                                            while($row=mysqli_fetch_assoc($data)) {
                                            ?>
                                            <tr>
                                                <td>TOTAL NO OF <?= $row['testname']?></td>
                                                <td><?= $row['OP']?></td>
                                                <td><?= $row['IP']?></td>
                                                
                                                <!--<td> <a href="iplabprint.php?from=<?= $op_from?>&to=<?= $op_to?>&id=<?= $row['testid']?>" target="_blank" class="text-primary"><?= $row['IP']?></a></td>-->
                                                <!--<td><a href="oplabprint.php?from=<?= $op_from?>&to=<?= $op_to?>&id=<?= $row['testid']?>" target="_blank" class="text-primary"><?= $row['OP']?></a></td>-->
                                               
                                            </tr>
                                            <?php } ?>
                                            </tbody>
                                            </table>      
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="tab-pane fade   " id="pills-lab" role="tabpanel" aria-labelledby="pills-lab-tab"><br>

<form action="#pills-lab-tab" href="#pills-home" method="POST">
    
  <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

  <div class="row">
                <div class="col-md-12">
                    <h4>Select Criteria</h4><br>
                 
                </div>
            </div> 

    <div class="row">
        <div class="col-md-4">
           <div class="form-group">
                  <label for="exampleInputEmail1"> From Date </label>
                  <input type="date" class="form-control"  name="op_from" id="op_from" required value="<?= $op_from?>">
                  
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                   <label for="exampleInputEmail1"> To Date  </label>
                   <input type="date" class="form-control"  name="op_to" id="op_to" required value="<?= $op_to?>">
                   
             </div>
        </div>

         

    </div>
    
    <div class="row">
            <div class="col-md-12">
            <button type="submit" class="btn btn-success  float-right btn-rounded"  name="submit"><span><i class="bi bi-search"></i></span> Search</button>
            </div>    
    </div>

        
    </div>
            </div>
        </div>
    </div>
</form>   
   
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                        <h5 class="ml-4">Lab Report Lists <span class="text-danger">
                            <?php if(isset($op_from) && isset($op_to)) {
                            echo "Between ".  date('d.m.Y',strtotime($op_from))." and ". date('d.m.Y',strtotime($op_to));
                             } ?>
                             </span></h5>
                             </div>
<div class="col-md-6">
                             <a href="testwiselabprint.php?from=<?= $op_from?>&to=<?= $op_to?>" target="_blank" class="float-right badge badge-pill badge-danger" data-toggle="tooltip" data-placement="top" title="Print Lab Report"><i class="icofont-print"></i> Print </a>
                        </div>
                    </div>
                    <hr>

                    <div class="table-responsive">
                    <table id="datatable-buttons4" class="table table-hoverde table-bordered text-wrap text-center " >
                                    <thead class="thead-dark">
                                    <tr >
                                               
                                                <th>Test Name</th>
                                                <th>IP</th>
                                                <th>OP</th>
                                                
                                                
                                                
                                            </tr>
                                        </thead>
                                        <tbody class="text-center text-black">
                                            <?php $data=$newReport->labReport($op_from,$op_to);
                                            while($row=mysqli_fetch_assoc($data)) {
                                            ?>
                                            <tr>
                                                <td>TOTAL NO OF <?= $row['testname']?></td>
                                                <td><?= $row['IP']?></td>
                                                <td><?= $row['OP']?></td>
                                                <!--<td> <a href="iplabprint.php?from=<?= $op_from?>&to=<?= $op_to?>&id=<?= $row['testid']?>" target="_blank" class="text-primary"><?= $row['IP']?></a></td>-->
                                                <!--<td><a href="oplabprint.php?from=<?= $op_from?>&to=<?= $op_to?>&id=<?= $row['testid']?>" target="_blank" class="text-primary"><?= $row['OP']?></a></td>-->
                                               
                                            </tr>
                                            <?php } ?>
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


       
 <script>
    $(document).ready(function() {
            $('#datatable-buttons2').DataTable({

                dom: 'Bfrtip',
                responsive: true,
                pageLength: 25,
                // lengthMenu: [0, 5, 10, 20, 50, 100, 200, 500],
                
                buttons: [
          
            {
                extend: 'pdfHtml5',
                title: 'Clinical Material Report',
               
                orientation: 'landscape',
                pageSize: 'LEGAL'
            }
        ]

            });
        });
</script>

 <script>
    $(document).ready(function() {
            $('#datatable-buttons4').DataTable({

                dom: 'Bfrtip',
                responsive: true,
                pageLength: 25,
                // lengthMenu: [0, 5, 10, 20, 50, 100, 200, 500],
                
                buttons: [
          
            {
                extend: 'pdfHtml5',
                title: 'Clinical Material Report',
               
                orientation: 'landscape',
                pageSize: 'LEGAL'
            }
        ]

            });
        });
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