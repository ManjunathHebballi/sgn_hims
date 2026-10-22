<?php 
require_once(__DIR__ . '/../model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/reportClass.php');
$newReport = new report;


if(isset($_REQUEST['op_from']))
{
    $op_from=$_REQUEST['op_from'];
}
else{
        $op_from=date("Y-m-d");
}
if(isset($_REQUEST['op_to']))
{
    $op_to=$_REQUEST['op_to'];
}
else{
        $op_to=date("Y-m-d");
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Service wise Report</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">

    <link href="../vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" ></script>
    <!-- Datatable -->
      <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="../vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">




    <style>
     #notification {
            position: absolute;
            top: 90px;
           
            right:2px;
            z-index: 99;
        }
</style>

</head>

<body >

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
            <h4 class="text-uppercase">Service wise Report </h4>
        </div>
      
    </div>
    



<div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
  <div class="row">
                <div class="col-md-12">
                    <!--<h4>OP Patient </h4><br>-->
                 
                </div>
            </div> 
<form action="" method="GET">
    <div class="row">

        <div class="col-md-3">
           <div class="form-group">
                  <label for="exampleInputEmail1"> From Date </label>
                  <input type="date" class="form-control"  name="op_from" id="op_from" required value="<?= $op_from?>">
                  
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1"> To Date  </label>
                   <input type="date" class="form-control"  name="op_to" id="op_to" required value="<?= $op_to?>">
                   
             </div>
        </div>
       
       <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1"> Department  </label>
                   <select  class="form-control select2doctor"  name="search" id="search" >
                       <option value="">All</option>
                       <?php $data=$newReport->allDepartment();
                       while($row=mysqli_fetch_assoc($data)){ ?>
                       <option value="<?= $row['id']?>" <?php if($row['id']==$_REQUEST['search']) echo 'selected';?>><?= $row['dept_name']?></option>
                       <?php } ?>
                   </select>
                   
             </div>
        </div>
         <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1"> Service List  </label>
                   <select  class="form-control select2doctor"  name="service" id="service" >
                       <option value="">All</option>
                       <?php $data=$newReport->getServiceList();
                       while($row=mysqli_fetch_assoc($data)){ ?>
                       <option value="<?= $row['id']?>" <?php if($row['id']==$_REQUEST['service']) echo 'selected';?>><?= $row['name']?></option>
                       <?php } ?>
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
                    <div class="col-12">
                        <div class="card">
                          
                  
                            <div class="card-body">
                                  <div class="row">
                                  <div class="col-md-6">
                                        <h4 class="card-title"><?=$_REQUEST['type']?> Billing List</h4>
                                  </div>
                                  <div class="col-md-6">
                     <a href='service_wise_report_print.php?from=<?= $op_from?>&to=<?= $op_to?>&service=<?= $_REQUEST['service']?>' class="btn btn-warning btn-sm mr-2 float-right">Print</a>
        </div>
                   
                            </div>
                                
                                
                                <table id="datatable-buttons4" class="table text-wrap text-left table-bordered mt-2">
                                       <thead class="thead-dark" >
                                            <tr class="dark">
                                               <th>Sl NO</th>
                                               <th>Date</th>
                                                <th>Bill NO</th>
                                                <th>Patient Reg No</th>
                                                <th>Name</th>
                                                <th>Gender</th>
                                                <th>Department</th>
                                                <th>Service Name</th>
                                                <th>Service Amt</th>
                                                <th>Service Level Disc</th>
                                                <th>Net Amount</th>
                                                 <th>Collected By</th>
                                                
                                                
                                               
                                              
                                                
                                            </tr>
                                        </thead>
                                        <tbody class=" text-black">
                                        <?php $cent=$newReport->getServiceDetails($op_from,$op_to,$_REQUEST['service'],isset($_REQUEST['search'])?intval($_REQUEST['search']):0);
                $sn=1;$total=$grand=$count=$allcount=$serv=$allserv=$disc=$alldisc=$net=$allnet=$doctor=$alldoctor=$hospital=$allhospital=0;
                while($row=mysqli_fetch_assoc($cent)){
                 ?>
                <tr>
                <td><?= $sn?></td>
                <td><?= $row['date']?></td>
                <td><?= $row['bill_no']?></td>
                <td><?= $row['opd']?></td>
                <td><?= $row['patient']?></td>
                <td><?= $row['gender']?></td>
                <td><?= $row['dept_name']?></td>
                <td><?= $row['name']?></td>
                <td><?= $row['amount']?></td>
                <td><?= $row['discount']?></td>
                <td><?= $net1=$row['amount']-$row['discount']?></td>
                <td><?= $row['collected_by']?></td>
                </tr>
                                        <?php $sn++;
                                        $allcount +=$row['count'];
                                        $serv +=$row['amount'];
                                        $allserv +=$row['amount'];
                                        $disc +=$row['discount'];
                                        $alldisc +=$row['discount'];
                                        $net +=$net1;
                                        $allnet +=$net1;
                                        $doctor +=round($row['share_amt']);
                                        $alldoctor +=round($row['share_amt']);
                                        $hospital +=$net1-$row['share_amt'];
                                        $allhospital += $net1-$row['share_amt'];
                                        
                                        $dr=$row['doctor'];
                                        } ?>
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th colspan=8 class="text-center">Grand Total</th>
                                            <th><?= $allserv?></th>
                                            <th><?= $alldisc?></th>
                                            <th><?= $allnet?></th>
                                            <th></th>
                                        </tr>
                                        </tfoot>
                                        </table>
                               
                                            </div>
                                            </div></div></div>
        </div>
</div>
        <!--**********************************
            Content body end
        ***********************************-->


        <!--**********************************
            Footer start
        ***********************************-->
        <?php include('footer.php');
       ?>
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

    <!--**********************************ed
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="./vendor/global/global.min.js"></script>
    <script src="./js/quixnav-init.js"></script>
    <script src="./js/custom.min.js"></script>
       <script src="./vendor/select2/js/select2.full.min.js"></script>
    <script src="./js/plugins-init/select2-init.js"></script>
   
   
   
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
        

        <!-- Datatable init js - removed to prevent conflict with custom init -->

    <script>
    $('.select2doctor').select2();
    $(document).ready(function() {
        $('#datatable-buttons4').DataTable({
            dom: 'Bfrtip',
            responsive: true,
            pageLength: 25,
            buttons: [
                {
                    extend: 'pdfHtml5',
                    title: 'Service Wise Report',
                    orientation: 'landscape',
                    pageSize: 'LEGAL'
                },
                {
                    extend: 'excelHtml5',
                    title: 'Service Wise Report',
                    filename: 'service_wise_report'
                },
                {
                    extend: 'csvHtml5',
                    title: 'Service Wise Report',
                    filename: 'service_wise_report'
                },
                'print'
            ]
        });
    });
</script>
       
</body>

</html>