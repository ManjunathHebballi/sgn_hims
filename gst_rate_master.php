<?php 
require_once(__DIR__ . '/model/session_config.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set("Asia/Kolkata");  
include('model/storeClass.php');
$newStore = new store;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#319197">

    <title>GST Rate Master</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon.png">
    <link href="css/style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

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
                <div class="row mb-3">
                    <div class="col-md-12">
                        <h4 class="text-uppercase">📊 GST Rate Master</h4>
                    </div>
                </div>

                <?php if(isset($_REQUEST['success'])) { ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> GST rate has been saved successfully.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php } ?>

                <form action="controller/processStore.php" method='POST' id="gstForm">

                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h5>Set GST Rate for Category</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Category <span class="text-danger">*</span></label>
                                                <select class="form-control" name="category_id" id="category_id" required>
                                                    <option value="">Select Category</option>
                                                    <?php 
                                    $categories = $newStore->getCategories();
                                    while($cat = mysqli_fetch_assoc($categories)) {
                                        echo "<option value='{$cat['id']}'>{$cat['category_name']}</option>";
                                    }
                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>GST Percentage <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="gst_percent" id="gst_percent" placeholder="e.g., 5, 12, 18" step="0.01" min="0" max="100" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        <button type="submit" class="btn btn-success btn-lg" name="submit" value="store_gst_rate">
                                            <i class="fa fa-save"></i> Save GST Rate
                                        </button>
                                    </div>

                                    <input type="hidden" name="condition" value="new">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    <h5>Active GST Rates</h5>
                                </div>
                                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                                    <table class="table table-sm table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Category</th>
                                                <th>GST %</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                $rates = $newStore->getAllGstRates();
                                if(mysqli_num_rows($rates) > 0) {
                                    while($rate = mysqli_fetch_assoc($rates)) {
                                        echo "<tr>";
                                        echo "<td><small>{$rate['category_name']}</small></td>";
                                        echo "<td><small><span class='badge badge-success'>{$rate['gst_percent']}%</span></small></td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='2' class='text-center'>No rates configured</td></tr>";
                                }
                                ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>

            </div>

        </div>

        <?php include('footer.php') ?>

    </div>

    <script src="vendor/global/global.min.js"></script>
    <script src="js/quixnav-init.js"></script>
    <script src="js/custom.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script>
    $(document).ready(function() {
        $('#category_id').select2({
            placeholder: 'Select Category',
            width: '100%'
        });
    });
    </script>

</body>

</html>
