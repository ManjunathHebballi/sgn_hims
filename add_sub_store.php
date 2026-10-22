<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/storeClass.php');
$newStore = new store;

$success = isset($_REQUEST['success']) ? $_REQUEST['success'] : 0;
$edit_id = isset($_REQUEST['editid']) ? $_REQUEST['editid'] : 0;
$edit_data = null;

if($edit_id) {
    $res = $newStore->getSubStore($edit_id);
    if($res && mysqli_num_rows($res) > 0) {
        $edit_data = mysqli_fetch_assoc($res);
    }
}

$sub_stores = $newStore->getSubStores();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#319197">

    <title>Sub Store Master</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon.png">
    <link href="css/style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet" />

</head>

<body>

    <div id="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>

    <div id="main-wrapper">

        <?php include ('leftbar.php')?>

        <div class="content-body">

            <div class="container-fluid">

                <div class="row page-titles mx-0">
                    <div class="col-sm-6 p-md-0">
                        <div class="welcome-text">
                            <h4>Sub Store Master</h4>
                        </div>
                    </div>
                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                        <a href="add_sub_store.php" class="btn btn-primary">
                            <i class="fa fa-plus-circle"></i> Add New Sub Store
                        </a>
                    </div>
                </div>

                <!-- Success Message -->
                <?php if($success == 1): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> Sub Store saved successfully.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php endif; ?>

                <!-- Form Section -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">
                                    <?= $edit_id ? 'Edit Sub Store' : 'Add New Sub Store' ?>
                                </h4>
                            </div>
                            <div class="card-body">
                                <form action="controller/processStore.php" method="POST" id="subStoreForm">
                                <div class="row">    
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Sub Store Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="store_name" 
                                            value="<?= $edit_data ? htmlspecialchars($edit_data['store_name']) : '' ?>" 
                                            placeholder="e.g., Emergency Department, ICU" required>
                                    </div>
                                    </div>
                                    <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Contact Person</label>
                                        <input type="text" class="form-control" name="contact_person" 
                                            value="<?= $edit_data ? htmlspecialchars($edit_data['contact_person']) : '' ?>" 
                                            placeholder="Name of contact person">
                                    </div>
                                    </div>
                                    <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Phone Number</label>
                                        <input type="tel" class="form-control" name="phone" 
                                            value="<?= $edit_data ? htmlspecialchars($edit_data['phone']) : '' ?>" 
                                            placeholder="Phone number">
                                    </div>
                                    </div>
                                    <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Email Address</label>
                                        <input type="email" class="form-control" name="email" 
                                            value="<?= $edit_data ? htmlspecialchars($edit_data['email']) : '' ?>" 
                                            placeholder="Email address">
                                    </div>
                                    </div>
                                    <div class="col-md-6">  
                                    <div class="form-group">
                                        <label>Address</label>
                                        <textarea class="form-control" name="address" rows="3" 
                                            placeholder="Sub Store location/address"><?= $edit_data ? htmlspecialchars($edit_data['address']) : '' ?></textarea>
                                    </div>
                                    </div>
                                    <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Status <span class="text-danger">*</span></label>
                                        <select class="form-control" name="status" required>
                                            <option value="">Select Status</option>
                                            <option value="active" <?= ($edit_data && $edit_data['status'] == 'active') ? 'selected' : '' ?>>Active</option>
                                            <option value="inactive" <?= ($edit_data && $edit_data['status'] == 'inactive') ? 'selected' : '' ?>>Inactive</option>
                                        </select>
                                    </div>
                                    </div>  
                                    <input type="hidden" name="condition" value="<?= $edit_id ? 'edit' : 'new' ?>">
                                    <input type="hidden" name="editid" value="<?= $edit_id ?>">
                                </div>
                                <div class="form-group mt-4 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-success mr-2" name="submit" value="sub_store">
                                            <i class="fa fa-save"></i> <?= $edit_id ? 'Update Sub Store' : 'Save Sub Store' ?>
                                        </button>
                                        <a href="add_sub_store.php" class="btn btn-secondary">
                                            <i class="fa fa-refresh"></i> Reset
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- List Section -->
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">All Sub Stores</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped" id="subStoreTable">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Store Name</th>
                                                <th>Contact Person</th>
                                                <th>Phone</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            if($sub_stores && mysqli_num_rows($sub_stores) > 0) {
                                                while($row = mysqli_fetch_assoc($sub_stores)) {
                                                    $status_badge = $row['status'] == 'active' ? 
                                                        '<span class="badge badge-success">Active</span>' : 
                                                        '<span class="badge badge-danger">Inactive</span>';
                                            ?>
                                            <tr>
                                                <td><strong><?= htmlspecialchars($row['store_name']) ?></strong></td>
                                                <td><?= htmlspecialchars($row['contact_person'] ?? '-') ?></td>
                                                <td><?= htmlspecialchars($row['phone'] ?? '-') ?></td>
                                                <td><?= $status_badge ?></td>
                                                <td>
                                                    <a href="add_sub_store.php?editid=<?= $row['id'] ?>" class="btn btn-sm btn-primary" title="Edit">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <a href="javascript:void(0)" onclick="deleteSubStore(<?= $row['id'] ?>)" class="btn btn-sm btn-danger" title="Delete">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php 
                                                }
                                            } else {
                                                echo '<tr><td colspan="5" class="text-center text-muted">No sub stores found</td></tr>';
                                            }
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

        <?php include('footer.php') ?>

    </div>

    <script src="vendor/global/global.min.js"></script>
    <script src="js/quixnav-init.js"></script>
    <script src="js/custom.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#subStoreTable').DataTable({
                "order": [[0, "asc"]],
                "pageLength": 10
            });
        });

        function deleteSubStore(id) {
            if(confirm('Are you sure you want to delete this sub store?')) {
                window.location = 'controller/processStore.php?action=delete_sub_store&id=' + id;
            }
        }
    </script>

</body>

</html>
