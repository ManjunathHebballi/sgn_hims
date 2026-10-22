<?php
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");
include('model/labClass.php');
$lab = new lab;

// Get package ID from query string
$package_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($package_id <= 0) {
    echo "<script>alert('Invalid package ID.');window.location='packageList.php';</script>";
    exit;
}
$allTests = $lab->getLabGroup();
// Fetch package details
$package = $lab->getLabPackageById($package_id);
if (!$package) {
    echo "<script>alert('Package not found.');window.location='packageList.php';</script>";
    exit;
}

// should return array of test IDs

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $package_name = $_POST['package_name'] ?? '';
    $package_price = $_POST['package_price'] ?? '';
    $package_description = $_POST['package_description'] ?? '';
    $test_ids = isset($_POST['test_ids']) ? explode(',', $_POST['test_ids']) : [];
    $result = $lab->updatePackage($package_id, $package_name, $package_price, $package_description, $test_ids);
    if ($result) {
        echo "<script>alert('Package updated successfully!');window.location='packageList.php';</script>";
        exit;
    } else {
        echo "<script>alert('Failed to update package. Please try again.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Edit Package</title>
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link href="./css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        table td,table th { padding:5px; }
    </style>
</head>
<body>
<div id="main-wrapper">
    <?php include('leftbar.php'); ?>
    <div class="content-body">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-md-6">
                    <h4 class="text-uppercase"> Edit Package </h4>
                </div>
                <div class="col-md-6">
                    <a href='packageList.php' class='btn btn-sm btn-primary float-right'>View Packages</a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Edit Package Details</h5>
                            <form method="post" id='package-form'>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Package Name <span class='text-danger'>*</span></label>
                                            <input type="text" class="form-control" name="package_name" value="<?= htmlspecialchars($package['package_name']) ?>" required />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Package Price (INR) <span class='text-danger'>*</span></label>
                                            <input type="text" class="form-control" name="package_price" value="<?= htmlspecialchars($package['package_price']) ?>" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Package Description</label>
                                            <input type="text" class="form-control" name="package_description" value="<?= htmlspecialchars($package['package_description']) ?>" />
                                        </div>
                                    </div>
                                    <div class='col-sm-12 mt-4'>
                                        <label>Select Test</label>
                                        <select id="test-select" style="width: 300px;">
                                            <option value="">Select Test</option>
                                            <?php while($row = mysqli_fetch_assoc($allTests)) { ?>
                                                <option value="<?= $row['testid'] ?>" data-name="<?= htmlspecialchars($row['testname']) ?>" data-price="<?= htmlspecialchars($row['price']) ?>" ><?= $row['testname'] ?></option>
                                            <?php } ?>
                                        </select>
                                        <input type="hidden" name="test_ids" id="test-ids" value="<?= $package['test_group_ids']?>" />
                                        <table id="test-table" style="margin-top:20px; width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th>Test Name</th>
                                                    <th>Price</th>
                                                    <th>Remove</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            // Pre-populate selected tests
                                            $selectedTestNames=explode(",",$package['test_names']);
                                            $selectedTestIds=explode(",",$package['test_group_ids']);
                                            $price=explode(",",$package['test_prices']);
                                            foreach ($selectedTestIds as $key => $testId) {
                                                // You may want to fetch test name/price by ID here
                                                // For now, just show test ID
                                                echo "<tr data-id='$testId'><td>$selectedTestNames[$key]</td><td>" . htmlspecialchars($price[$key]) . "</td><td><button class='remove-btn btn btn-danger btn-sm'>Remove</button></td></tr>";
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-12 mt-4">
                                        <button type="submit" class="btn btn-success float-right">Update</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include('footer.php'); ?>
</div>
<script src="./vendor/global/global.min.js"></script>
<script src="./js/quixnav-init.js"></script>
<script src="./js/custom.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function () {
    $('#test-select').select2();
    // Pre-populate table with selected tests
    $('#test-select').val($('#test-ids').val().split(',')).trigger('change');
    $('#test-select').on('select2:select', function (e) {
        const selected = e.params.data;
        const option = $('#test-select option[value="' + selected.id + '"]');
        const name = option.data('name');
        const price = option.data('price');
        if ($('#test-table tbody tr[data-id="' + selected.id + '"]').length > 0) {
            alert('Test already added.');
            return;
        }
        const row = `<tr data-id="${selected.id}">
                        <td>${name}</td>
                        <td>${price}</td>
                        <td><button class="remove-btn btn btn-danger btn-sm">Remove</button></td>
                    </tr>`;
        $('#test-table tbody').append(row);
        updateTestIds();
    });
    $(document).on('click', '.remove-btn', function () {
        $(this).closest('tr').remove();
        updateTestIds();
    });
    function updateTestIds() {
        let ids = [];
        $('#test-table tbody tr').each(function () {
            ids.push($(this).attr('data-id'));
        });
        $('#test-ids').val(ids.join(','));
    }
    $('#package-form').on('submit', function (e) {
        if ($('#test-table tbody tr').length === 0) {
            alert('Please add at least one test to the package.');
            e.preventDefault();
            return false;
        }
        updateTestIds();
    });
});
</script>
</body>
</html>
