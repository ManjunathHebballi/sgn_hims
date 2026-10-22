<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/storeClass.php');
$newStore = new store;

$issue_id = $_GET['id'];
$issue_res = $newStore->getIssue($issue_id);
$issue = mysqli_fetch_assoc($issue_res);

$items_res = $newStore->getIssueItems($issue_id);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#319197">

    <title>View Issue - <?= $issue['issue_number']?></title>
    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon.png">
    <link href="css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<style>
    .issue-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px;
        border-radius: 10px;
        margin-bottom: 20px;
    }
    .info-box {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 10px;
    }
    @media print {
        .no-print { display: none; }
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

    <div id="main-wrapper">

  <?php include ('leftbar.php')?>
  
        <div class="content-body">

<div class="container-fluid">
    <div class="row mb-3 no-print">
        <div class="col-md-8">
            <h4 class="text-uppercase">📦 Issue Details</h4>
        </div>
        <div class="col-md-4 text-right">
            <button onclick="window.print()" class="btn btn-secondary">
                <i class="fa fa-print"></i> Print
            </button>
            <a href="store_issue_list.php" class="btn btn-primary">
                <i class="fa fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="issue-header">
                <div class="row">
                    <div class="col-md-6">
                        <h3><?= $issue['issue_number']?></h3>
                        <p class="mb-0">Issue Date: <strong><?= date('d-M-Y', strtotime($issue['issue_date']))?></strong></p>
                    </div>
                    <div class="col-md-6 text-right">
                        <h4>Department: <?= $issue['department']?></h4>
                        <?php if($issue['requested_by']): ?>
                        <p class="mb-0">Requested By: <strong><?= $issue['requested_by']?></strong></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5>Issue Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="info-box">
                                <small>Issue Number</small>
                                <h6><?= $issue['issue_number']?></h6>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <small>Issue Date</small>
                                <h6><?= date('d-M-Y', strtotime($issue['issue_date']))?></h6>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <small>Department</small>
                                <h6><?= $issue['department']?></h6>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <small>Status</small>
                                <h6>
                                    <?php if($issue['status'] == 'ISSUED'): ?>
                                        <span class="badge badge-success">ISSUED</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">CANCELLED</span>
                                    <?php endif; ?>
                                </h6>
                            </div>
                        </div>
                    </div>
                    
                    <?php if($issue['requested_by']): ?>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <div class="info-box">
                                <small>Requested By</small>
                                <h6><?= $issue['requested_by']?></h6>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if($issue['remarks']): ?>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <div class="info-box">
                                <small>Remarks</small>
                                <h6><?= $issue['remarks']?></h6>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5>Issued Items</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>S.No</th>
                                    <th>Item Code</th>
                                    <th>Item Name</th>
                                    <th>Batch No</th>
                                    <th>Quantity</th>
                                    <th>Unit</th>
                                    <th>Serial No</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $sno = 1;
                                $total_items = 0;
                                while($item = mysqli_fetch_assoc($items_res)):
                                    $total_items += $item['quantity'];
                                ?>
                                <tr>
                                    <td><?= $sno?></td>
                                    <td><?= $item['item_code']?></td>
                                    <td><?= $item['item_name']?></td>
                                    <td><?= $item['batch_no'] ? $item['batch_no'] : '-'?></td>
                                    <td class="text-right"><strong><?= $item['quantity']?></strong></td>
                                    <td><?= $item['unit_name']?></td>
                                    <td><?= $item['serial_no'] ? $item['serial_no'] : '-'?></td>
                                </tr>
                                <?php 
                                    $sno++;
                                endwhile; 
                                ?>
                            </tbody>
                            <tfoot class="thead-light">
                                <tr>
                                    <td colspan="4" class="text-right"><strong>Total Items Issued:</strong></td>
                                    <td class="text-right"><strong><?= $total_items?></strong></td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <p><strong>Issued By:</strong> <?= $_SESSION['username'] ?? 'System'?></p>
                            <p><strong>Issue Date:</strong> <?= date('d-M-Y H:i', strtotime($issue['created_at']))?></p>
                        </div>
                        <div class="col-md-4 text-center">
                            <p>_____________________</p>
                            <p><strong>Issued By Signature</strong></p>
                        </div>
                        <div class="col-md-4 text-right">
                            <p>_____________________</p>
                            <p><strong>Received By Signature</strong></p>
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

</body>
</html>
