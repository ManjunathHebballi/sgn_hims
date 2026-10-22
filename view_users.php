<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/userClass.php');
$newOP = new addclass;
$dept=$newOP->selectDept();
if(isset($_REQUEST['status']))
{
    if($_REQUEST['status']=='active')
    {
        $newOP->statusUser($_REQUEST['id'],'active');
    }
    else{
        $newOP->statusUser($_REQUEST['id'],'inactive');
    }
    echo "<script>window.location='view_users.php'</script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>View Users</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" ></script>
        <!-- Sweet Alert-->
        
        
    <!-- DataTables -->
    <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/datatables.net-select-bs4/css//select.bootstrap4.min.css" rel="stylesheet" type="text/css" />




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
                                    <strong>Success!</strong> New User Added Successfully..
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

   <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
              
                    <div class="row">
                        <div class="col-md-6">
                                 <h4>Users List</h4>
                        </div>
                        <div class="col-md-6">
                            <a class="btn btn-success float-right" href="add-user.php">Add New User</a>
                        </div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-md-3 mb-2">
                            <label for="filterRole" class="mb-1">Filter by Role</label>
                            <select id="filterRole" class="form-control">
                                <option value="">All Roles</option>
                                <?php
                                $res = $newOP->getDoctors();
                                $users = array();
                                $roles = array();
                                while($row1 = mysqli_fetch_assoc($res)){
                                    $users[] = $row1;
                                    if(!empty($row1['role'])){
                                        $roles[$row1['role']] = $row1['role'];
                                    }
                                }
                                ksort($roles);
                                foreach($roles as $role){
                                ?>
                                <option value="<?= htmlspecialchars($role) ?>"><?= htmlspecialchars($role) ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="filterStatus" class="mb-1">Filter by Status</label>
                            <select id="filterStatus" class="form-control">
                                <option value="">All Status</option>
                                <option value="Active">Active</option>
                                <option value="In-active">In-active</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-2 d-flex align-items-end">
                            <button type="button" id="clearUserFilters" class="btn btn-secondary btn-block">Clear</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                 <table id="datatable-buttons" class="table table-bordered display text-center">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th>Sl.No.</th>
                                        <th>Name</th>
                                        <th>Role</th>
                                        <th>Designation</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $sn=1;
                                        foreach($users as $row1){
                                        ?>
                                    <tr>
                                        <td><?= $sn?></td>
                                        <td><?= $row1['name']?> <?= $row1['last_name']?></td>
                                       <td><?= $row1['role']?></td>
                                         <td><?= $row1['designation']?></td>
                                         <!--<td><?= $row1['email']?></td>-->
                                         <!--<td><?= $row1['mobile']?></td>-->
                                        <td><?php if( $row1['status']=='active'){?><span class='badge badge-soft-success'>Active</span> <?php } else{?><span class='badge badge-soft-danger'>In-active</span><?php } ?></td>
                                        <td><a href="edit_users.php?id=<?= $row1['id']?>" class="badge badge-pill badge-primary" data-toggle="tootltip" title="Edit User"><i class="ri-edit-line"></i></a>&emsp;<?php if($row1['status']=='active') { ?><a href="view_users.php?id=<?= $row1['id']?>&status=inactive" class="badge badge-pill badge-danger" data-toggle="tootltip" title="Inactive User"><i class="ri-thumb-down-line"></i></a><?php } else{ ?><a href="view_users.php?id=<?= $row1['id']?>&status=active" class="badge badge-pill badge-success" data-toggle="tootltip" title="Active User"><i class="ri-thumb-up-line"></i></a><?php }?></td>
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
                var userTable = $('#datatable-buttons').DataTable();

                function applyUserFilters() {
                    var selectedRole = $('#filterRole').val();
                    var selectedStatus = $('#filterStatus').val();

                    userTable.column(2).search(
                        selectedRole ? '^' + $.fn.dataTable.util.escapeRegex(selectedRole) + '$' : '',
                        true,
                        false
                    );

                    userTable.column(4).search(
                        selectedStatus ? '^' + $.fn.dataTable.util.escapeRegex(selectedStatus) + '$' : '',
                        true,
                        false
                    );

                    userTable.draw();
                }

                $('#filterRole, #filterStatus').on('change', applyUserFilters);

                $('#clearUserFilters').on('click', function() {
                    $('#filterRole').val('');
                    $('#filterStatus').val('');
                    applyUserFilters();
                });
            });
        </script>






</body>

</html>