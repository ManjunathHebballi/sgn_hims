<?php
require_once(__DIR__ . '/../model/session_config.php');
include('model/billingClass.php');
$newBill = new addBilling;

$result = null;
$dcInput = '';
$apiBaseUrl = 'https://api.retailxpress.ai';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dcInput = isset($_POST['dc_numbers']) ? trim($_POST['dc_numbers']) : '';
    $apiBaseUrl = isset($_POST['api_base_url']) && $_POST['api_base_url'] != '' ? trim($_POST['api_base_url']) : 'https://api.retailxpress.ai';
    $result = $newBill->updateAlreadyBilledDc($dcInput, $apiBaseUrl);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Update Already Billed DC</title>
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <style>
        #main-wrapper {
            opacity: 1 !important;
        }

        .result-box pre {
            white-space: pre-wrap;
            word-break: break-word;
            margin: 0;
            font-size: 12px;
        }

        .small-note {
            font-size: 12px;
            color: #6c757d;
        }
    </style>
</head>

<body>
    <div id="main-wrapper">
        <?php include('leftbar.php'); ?>

        <div class="content-body">
            <div class="container-fluid">
                <div class="row page-titles mx-0 mt-5">
                    <div class="col-sm-6 p-md-0">
                        <div class="welcome-text">
                            <h4>Update Already Billed DC</h4>
                            <span>Use this page to re-send billed delivery challans.</span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">DC Update Form</h4>
                            </div>
                            <div class="card-body">
                                <form method="post">
                                    <div class="form-group">
                                        <label>API Base URL</label>
                                        <input type="text" name="api_base_url" class="form-control" value="<?= htmlspecialchars($apiBaseUrl) ?>">
                                        <div class="small-note">Default: `https://api.retailxpress.ai`</div>
                                    </div>

                                    <div class="form-group">
                                        <label>DC Numbers</label>
                                        <textarea name="dc_numbers" rows="8" class="form-control" placeholder="(DC/11/2627/715)
DC/11/2627/716
DC/11/2627/717"><?= htmlspecialchars($dcInput) ?></textarea>
                                        <div class="small-note">Only values starting with <b>DC/</b> will be processed. <b>DCR/</b> values are ignored.</div>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Update Billed DC</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if ($result !== null) { ?>
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Update Result</h4>
                                </div>
                                <div class="card-body result-box">
                                    <div class="alert alert-<?php echo ($result['status'] == 'success') ? 'success' : 'danger'; ?>">
                                        <?= htmlspecialchars($result['message']) ?>
                                    </div>

                                    <div class="mb-3">
                                        <b>Total DC Entered:</b> <?= count($result['dc_numbers']) ?><br>
                                        <b>Matched in `ip_medicine`:</b> <?= count($result['matched']) ?><br>
                                        <b>Not Found:</b> <?= count($result['not_found']) ?>
                                    </div>

                                    <?php if (!empty($result['matched'])) { ?>
                                        <h5>Matched DC Numbers</h5>
                                        <div class="table-responsive mb-4">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>DC No</th>
                                                        <th>RX SO ID</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $i = 1;
                                                    foreach ($result['matched'] as $dcNo => $rxSoId) { ?>
                                                        <tr>
                                                            <td><?= $i++ ?></td>
                                                            <td><?= htmlspecialchars($dcNo) ?></td>
                                                            <td><?= htmlspecialchars($rxSoId) ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php } ?>

                                    <?php if (!empty($result['not_found'])) { ?>
                                        <h5>Not Found DC Numbers</h5>
                                        <div class="alert alert-warning">
                                            <?= htmlspecialchars(implode(', ', $result['not_found'])) ?>
                                        </div>
                                    <?php } ?>

                                    <?php if (!empty($result['api_results'])) { ?>
                                        <h5>API Response</h5>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>DC No</th>
                                                        <th>RX SO ID</th>
                                                        <th>HTTP Code</th>
                                                        <th>Response</th>
                                                        <th>Error</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($result['api_results'] as $row) { ?>
                                                        <tr>
                                                            <td><?= htmlspecialchars($row['dc_no']) ?></td>
                                                            <td><?= htmlspecialchars($row['rx_so_id']) ?></td>
                                                            <td><?= htmlspecialchars($row['http_code']) ?></td>
                                                            <td>
                                                                <pre><?= htmlspecialchars($row['response']) ?></pre>
                                                            </td>
                                                            <td><?= htmlspecialchars($row['error']) ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</body>

</html>