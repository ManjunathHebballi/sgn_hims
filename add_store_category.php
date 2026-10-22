<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/storeClass.php');
$newStore = new store;
if(isset($_REQUEST['editid']))
{
    $category1=$newStore->getCategory($_REQUEST['editid']);
    $row=mysqli_fetch_assoc($category1);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#319197">

    <title>Store Category Master</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <!-- Sweet Alert-->
    <link href="css/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />

    <style>
    #notification {
        position: absolute;
        top: 90px;
        right: 2px;
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

    <div aria-live="polite" id="notification" aria-atomic="true">
        <?php if(isset($_REQUEST['success']))
{ ?>
        <div class="alert alert-success solid alert-dismissible fade show">
            <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i
                        class="mdi mdi-close"></i></span>
            </button>
            <strong>Success!</strong> Category saved successfully
        </div>
        <?php } ?>
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
                <div class="row mb-3">
                    <div class="col-md-12">
                        <h4 class="text-uppercase">🏥 Store Category Master</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="controller/processStore.php" method='POST'>

                                    <div class="row">

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label> Category Name <span style="color:red">*</span> </label>
                                                <input type="text" class="form-control" name="name" id="name"
                                                    value="<?= isset($row['category_name']) ? $row['category_name'] : ''?>"
                                                    placeholder="Enter Category Name" required>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label> Category Type <span style="color:red">*</span> </label>
                                                <select class="form-control" name="category_type" required>
                                                    <option value="CONSUMABLE" <?= isset($row['category_type']) && $row['category_type']=='CONSUMABLE' ? 'selected' : ''?>>Consumable</option>
                                                    <option value="ASSET" <?= isset($row['category_type']) && $row['category_type']=='ASSET' ? 'selected' : ''?>>Asset</option>
                                                    <option value="SERVICE" <?= isset($row['category_type']) && $row['category_type']=='SERVICE' ? 'selected' : ''?>>Service</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Status</label> <br>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="status"
                                                        id="status1" value="1"
                                                        <?php echo (!isset($row['status']) || $row['status']=='1') ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="status1">Active</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="status"
                                                        id="status0" value="0"
                                                        <?php echo (isset($row['status']) && $row['status']=='0') ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="status0"> Inactive</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <?php if(isset($_REQUEST['editid'])) {?>
                                            <input type="hidden" name="condition" value="edit">
                                            <input type="hidden" name="editid" value="<?= $_REQUEST['editid']?>">
                                            <button type="submit" class="btn btn-primary btn-rounded" name="submit"
                                                value="store_category" style="margin-top: 30px;">Update</button>
                                            <?php }  else {?>
                                            <input type="hidden" name="condition" value="new">
                                            <button type="submit" class="btn btn-success btn-rounded" name="submit"
                                                value="store_category" style="margin-top: 30px;">Save</button>
                                            <?php } ?>
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
                                <h4>Category List</h4>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover" id="categoryTable">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th>Sl.No.</th>
                                                        <th>Category Name</th>
                                                        <th>Category Type</th>
                                                        <th>Status</th>
                                                        <th>Created Date</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                        $category=$newStore->getCategories();
                                        
                                        $sn=1;
                                        while($row1=mysqli_fetch_assoc($category)){
                                        ?>
                                                    <tr>
                                                        <td><?= $sn?></td>
                                                        <td><?= $row1['category_name']?></td>
                                                        <td>
                                                            <?php 
                                                            $type_class = '';
                                                            if($row1['category_type']=='CONSUMABLE') $type_class = 'badge-info';
                                                            elseif($row1['category_type']=='ASSET') $type_class = 'badge-warning';
                                                            elseif($row1['category_type']=='SERVICE') $type_class = 'badge-secondary';
                                                            ?>
                                                            <span class="badge <?= $type_class?>"><?= $row1['category_type']?></span>
                                                        </td>
                                                        <td>
                                                            <?php if($row1['status']==1) { ?>
                                                            <span class="badge badge-success">Active</span>
                                                            <?php } else { ?>
                                                            <span class="badge badge-danger">Inactive</span>
                                                            <?php } ?>
                                                        </td>
                                                        <td><?= date('d-m-Y', strtotime($row1['created_at']))?></td>
                                                        <td>
                                                            <a href="add_store_category.php?editid=<?= $row1['id']?>"
                                                                class="btn btn-sm btn-primary" data-toggle="tooltip"
                                                                title="Edit Category">
                                                                <i class="fa fa-edit"></i> Edit
                                                            </a>
                                                        </td>
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

    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!--**********************************
        Scripts
    ***********************************-->
    <script src="vendor/global/global.min.js"></script>
    <script src="js/quixnav-init.js"></script>
    <script src="js/custom.min.js"></script>

    <!-- Datatable -->
    <script src="vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/js/dataTables.bootstrap4.min.js"></script>

    <script>
    $(document).ready(function() {
        $('#categoryTable').DataTable({
            "pageLength": 25,
            "ordering": true,
            "searching": true
        });
    });

    window.setTimeout(function() {
        $(".alert").fadeTo(500, 0).slideUp(500, function() {
            $(this).remove();
        });
    }, 4000);
    </script>

    <!-- Sweet Alerts js -->
    <script src="css/sweetalert2/sweetalert2.min.js"></script>
    <script src="js/sweet-alerts.init.js"></script>

</body>

</html>