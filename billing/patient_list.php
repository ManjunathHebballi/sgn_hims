<?php 
require_once(__DIR__ . '/../model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/dashClass.php');
$newOP = new dashboard;

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
if(isset($_REQUEST['search']))
{
    $search=$_REQUEST['search'];
}
else{
    $search="";
}
if(isset($_REQUEST['doctor']))
{
    $doctor=$_REQUEST['doctor'];
}
else{
    $doctor="";
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Patient List</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
      <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
      <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" ></script>
    <!-- Datatable -->
    <link href="./vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
    
    <!-- DataTables -->
    
        <link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/datatables.net-select-bs4/css/select.bootstrap4.min.css" rel="stylesheet" type="text/css" />

    <style>
     #notification {
            position: absolute;
            top: 90px;
           
            right:2px;
            z-index: 99;
        }
</style>

</head>

<body class="text-uppercase">

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
        <div class="col-md-12">
            <h4 class="text-uppercase">OP PATIENT LIST</h4>
        </div>
    </div>
    



<div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
  <div class="row">
                <div class="col-md-12">
                    <h4>OP Patient </h4><br>
                 
                </div>
            </div> 
<form action="" method="POST">
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
                   <label> Doctor  </label>
                   <select  class="form-control select2doctor"  name="doctor" id="doctor">
                       <option value="">All</option>
                       <?php $res=$newOP->getDoctor();
                       while($row=mysqli_fetch_assoc($res))
                       { ?>
                       <option value="<?= $row['name']?>" <?php if($row['name']==$doctor) echo 'selected';?>><?= $row['name']?></option>
                           <?php
                           
                       }
                   ?>
                   </select>
             </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1"> Search  </label>
                   <input type="text" class="form-control"  name="search" id="search"  value="<?= $search?>" placeholder="Search by name, phone, opd...etc">
                   
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
                            <div class="card-header">
                                <h4 class="card-title">OP Patient List</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped  text-center" id="datatable-buttons4">
                                        <thead>
                                            <tr>
                                                <th>Sl NO</th>
                                                <th>COPID</th>
                                                <th>Visit Date</th>
                                                <th>Name</th>
                                                <th>Gender</th>
                                                <th>Address</th>
                                                <th>Doctor</th>
                                                <th>Department</th>
                                                <th>status</th>
                                                <th>Action</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            <?php 
                                            $sn=1;
//                                             $limit = 25;
// $page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
// $page  = max($page, 1);
// $offset = ($page - 1) * $limit;

// $totalRecords = $newOP->countOPreg($op_from, $op_to, $search, $doctor);
// $totalPages   = ceil($totalRecords / $limit);
// $sn = $offset + 1;

$dept = $newOP->selectOPreg_seacrh(
    $op_from,
    $op_to,
    $search,
    $doctor,
    $limit,
    $offset
);
                                             while($res=mysqli_fetch_assoc($dept))
                                            { ?>
                                            <tr>
    <td><?= $sn ?></td>
    <td><?= $res['opd_no'] ?></td>
    <td><?= date("d.m.Y", strtotime($res['date'])) ?></td>
    <td><?= $res['name'] ?></td>
    <td><?= $res['gender'] ?></td>
    <td><?= $res['address'] ?></td>
    <td><?= $res['doctor'] ?></td>
    <td><?= $res['dept_name'] ?></td>
    <td><?= ($res['bill'] > 0) ? 'Completed' : 'Pending' ?></td>
    <td>
        <div class="d-flex">
            <a href="../opcasesheet.php?id=<?=  $res['id']?>"  target="_blank" title="Print Casesheet" class="badge badge-pill badge-secondary mr-2"><i class="ri-file-list-3-line" style="font-size:15px"></i> </a>
            <a href="addOP_billing.php?opd=<?= $res['opd_no'] ?>" class="badge badge-warning ml-2">OPD Bill</a>
            <a href="pathology_billing.php?opd=<?= $res['opd_no'] ?>" class="badge badge-info ml-2">Lab Bill</a>
            <a href="radiology_billing.php?opd=<?= $res['opd_no'] ?>" class="badge badge-danger ml-2">Radio Bill</a>
            <a href="blood_billing.php?opd=<?= $res['opd_no'] ?>" class="badge badge-primary ml-2">Blood Bill</a>
            <a href="dialysis_billing.php?opd=<?= $res['opd_no'] ?>" class="badge badge-dark ml-2">Dialysis Bill</a>
        </div>
    </td>
</tr>
                                           <?php $sn++; } 
                                           
                                          ?>
                                            </tbody>
                                            </table>   
                                            
                                            <?php
$adjacent = 2; // pages before and after current
?>

<!-- <nav>
<ul class="pagination justify-content-center"> -->

<!-- Previous -->
<?php //if ($page > 1): ?>
<!-- <li class="page-item">
    <a class="page-link" href="?page=<?= $page - 1 ?>&op_from=<?= $_REQUEST['op_from']?>&op_to=<?= $_REQUEST['op_to']?>&search=<?= $_REQUEST['search']?>&doctor=<?= $_REQUEST['doctor']?>">Previous</a>
</li> -->
<?php //endif; ?>

<!-- First Page -->
<!-- <li class="page-item <?= ($page == 1) ? 'active' : '' ?>">
    <a class="page-link" href="?page=1&op_from=<?= $_REQUEST['op_from']?>&op_to=<?= $_REQUEST['op_to']?>&search=<?= $_REQUEST['search']?>&doctor=<?= $_REQUEST['doctor']?>">1</a>
</li> -->

<!-- Left Ellipsis -->
<?php //if ($page > ($adjacent + 2)): ?>
<!-- <li class="page-item disabled">
    <span class="page-link">...</span>
</li> -->
<?php //endif; ?>

<!-- Middle Pages -->
<?php
// $start = max(2, $page - $adjacent);
// $end   = min($totalPages - 1, $page + $adjacent);

// for ($i = $start; $i <= $end; $i++):
?>
<!-- <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
    <a class="page-link" href="?page=<?= $i ?>&op_from=<?= $_REQUEST['op_from']?>&op_to=<?= $_REQUEST['op_to']?>&search=<?= $_REQUEST['search']?>&doctor=<?= $_REQUEST['doctor']?>"><?= $i ?></a>
</li> -->
<?php //endfor; ?>

<!-- Right Ellipsis -->
<?php //if ($page < ($totalPages - $adjacent - 1)): ?>
<!-- <li class="page-item disabled">
    <span class="page-link">...</span>
</li> -->
<?php //endif; ?>

<!-- Last Page -->
<?php //if ($totalPages > 1): ?>
<!-- <li class="page-item <?= ($page == $totalPages) ? 'active' : '' ?>">
    <a class="page-link" href="?page=<?= $totalPages ?>&op_from=<?= $_REQUEST['op_from']?>&op_to=<?= $_REQUEST['op_to']?>&search=<?= $_REQUEST['search']?>&doctor=<?= $_REQUEST['doctor']?>"><?= $totalPages ?></a>
</li> -->
<?php //endif; ?>

<!-- Next -->
<?php //if ($page < $totalPages): ?>
<!-- <li class="page-item">
    <a class="page-link" href="?page=<?= $page + 1 ?>&op_from=<?= $_REQUEST['op_from']?>&op_to=<?= $_REQUEST['op_to']?>&search=<?= $_REQUEST['search']?>&doctor=<?= $_REQUEST['doctor']?>">Next</a>
</li> -->
<?php //endif; ?>

<!-- </ul>
</nav> -->

                                            </div>  
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
        
  <script src="./vendor/select2/js/select2.full.min.js"></script>
    <script src="./js/plugins-init/select2-init.js"></script>
        <!-- Datatable init js -->
        <script src="assets/js/pages/datatables.init.js"></script>


        <script>
        $('.select2doctor').select2();
    $(document).ready(function() {
            $('#datatable-buttons4').DataTable({

                dom: 'Bfrtip',
                responsive: true,
                // paging: false,      // ❌ Disable pagination
        // info: false,        // ❌ Hide "Showing X of Y"
        // lengthChange: false, // ❌ Hide page length dropdown

                buttons: [
           
            {
                extend: 'pdfHtml5',
                title: 'Center OP',
                 orientation: 'landscape',
                pageSize: 'LEGAL'
            }
        ]

            });
        });
</script>
   

</body>

</html>