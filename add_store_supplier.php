<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/storeClass.php');
$newStore = new store;
if(isset($_REQUEST['editid']))
{
    $supplier1=$newStore->getSupplier($_REQUEST['editid']);
    $row=mysqli_fetch_assoc($supplier1);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#319197">

    <title>Store Supplier Master</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon.png">
    <link href="css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
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

    <div id="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>

    <div aria-live="polite" id="notification" aria-atomic="true">
        <?php if(isset($_REQUEST['success']))
{ ?>
        <div class="alert alert-success solid alert-dismissible fade show">
            <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i
                        class="mdi mdi-close"></i></span>
            </button>
            <strong>Success!</strong> Supplier saved successfully
        </div>
        <?php } ?>
    </div>

    <div id="main-wrapper">

        <?php include ('leftbar.php')?>

        <div class="content-body">

            <div class="container-fluid">
                <div class="row mb-3">
                    <div class="col-md-12">
                        <h4 class="text-uppercase">🏭 Store Supplier Master</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="controller/processStore.php" method='POST'>

                                    <div class="row">

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label> Supplier Name <span style="color:red">*</span> </label>
                                                <input type="text" class="form-control" name="name" id="name"
                                                    value="<?= isset($row['supplier_name']) ? $row['supplier_name'] : ''?>"
                                                    placeholder="Enter Supplier Name" required>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label> Contact Person </label>
                                                <input type="text" class="form-control" name="contact_person"
                                                    id="contact_person"
                                                    value="<?= isset($row['contact_person']) ? $row['contact_person'] : ''?>"
                                                    placeholder="Enter Contact Person">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label> Phone <span style="color:red">*</span> </label>
                                                <input type="text" class="form-control" name="phone" id="phone"
                                                    value="<?= isset($row['phone']) ? $row['phone'] : ''?>"
                                                    placeholder="Enter Phone Number" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label> Email </label>
                                                <input type="email" class="form-control" name="email" id="email"
                                                    value="<?= isset($row['email']) ? $row['email'] : ''?>"
                                                    placeholder="Enter Email">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label> GST Number </label>
                                                <input type="text" class="form-control" name="gst_number"
                                                    id="gst_number"
                                                    value="<?= isset($row['gst_number']) ? $row['gst_number'] : ''?>"
                                                    placeholder="Enter GST Number">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
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
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label> Address </label>
                                                <textarea class="form-control" name="address" id="address" rows="3"
                                                    placeholder="Enter Address"><?= isset($row['address']) ? $row['address'] : ''?></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <?php if(isset($_REQUEST['editid'])) {?>
                                            <input type="hidden" name="condition" value="edit">
                                            <input type="hidden" name="editid" value="<?= $_REQUEST['editid']?>">
                                            <button type="submit" class="btn btn-primary btn-rounded float-right"
                                                name="submit" value="store_supplier">Update</button>
                                            <?php }  else {?>
                                            <input type="hidden" name="condition" value="new">
                                            <button type="submit" class="btn btn-success btn-rounded float-right"
                                                name="submit" value="store_supplier">Save</button>
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
                                <h4>Supplier List</h4>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover table-sm" id="supplierTable">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th>Sl.No.</th>
                                                        <th>Supplier Name</th>
                                                        <th>Contact Person</th>
                                                        <th>Phone</th>
                                                        <th>Email</th>
                                                        <th>GST Number</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                        $supplier=$newStore->getSuppliers();
                                        
                                        $sn=1;
                                        while($row1=mysqli_fetch_assoc($supplier)){
                                        ?>
                                                    <tr>
                                                        <td><?= $sn?></td>
                                                        <td><?= $row1['supplier_name']?></td>
                                                        <td><?= $row1['contact_person']?></td>
                                                        <td><?= $row1['phone']?></td>
                                                        <td><?= $row1['email']?></td>
                                                        <td><?= $row1['gst_number']?></td>
                                                        <td>
                                                            <?php if($row1['status']==1) { ?>
                                                            <span class="badge badge-success">Active</span>
                                                            <?php } else { ?>
                                                            <span class="badge badge-danger">Inactive</span>
                                                            <?php } ?>
                                                        </td>
                                                        <td>
                                                            <a href="add_store_supplier.php?editid=<?= $row1['id']?>"
                                                                class="btn btn-sm btn-primary" data-toggle="tooltip"
                                                                title="Edit Supplier">
                                                                <i class="fa fa-edit"></i>
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

        <?php include('footer.php') ?>

    </div>

    <script src="vendor/global/global.min.js"></script>
    <script src="js/quixnav-init.js"></script>
    <script src="js/custom.min.js"></script>

    <script src="vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/js/dataTables.bootstrap4.min.js"></script>

    <script>
    $(document).ready(function() {
        $('#supplierTable').DataTable({
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

    <script src="css/sweetalert2/sweetalert2.min.js"></script>

</body>

</html>