<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/storeClass.php');
$newStore = new store;

// Get selected supplier and date range
$supplier_id = isset($_GET['supplier_id']) ? $_GET['supplier_id'] : '';
$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : date('Y-m-01'); // First day of current month
$to_date = isset($_GET['to_date']) ? $_GET['to_date'] : date('Y-m-d'); // Today
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#319197">

    <title>Supplier Ledger Report</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon.png">
    <link href="css/style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <style>
    .ledger-table th {
        background-color: #343a40;
        color: white;
        font-size: 13px;
        position: sticky;
        top: 0;
    }

    .ledger-table td {
        font-size: 12px;
    }

    .debit-col {
        color: #dc3545;
        font-weight: bold;
    }

    .credit-col {
        color: #28a745;
        font-weight: bold;
    }

    .balance-col {
        background-color: #f8f9fa;
        font-weight: bold;
    }

    .summary-box {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
    }

    @media print {
        .no-print {
            display: none;
        }

        .ledger-table th {
            background-color: #343a40 !important;
            -webkit-print-color-adjust: exact;
        }
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
                    <div class="col-md-12">
                        <h4 class="text-uppercase">📊 Supplier Ledger Report</h4>
                    </div>
                </div>

                <!-- Filter Section -->
                <div class="row no-print">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5>Filter Options</h5>
                            </div>
                            <div class="card-body">
                                <form method="GET" action="">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Supplier <span class="text-danger">*</span></label>
                                                <select class="form-control select2" name="supplier_id" required>
                                                    <option value="">Select Supplier</option>
                                                    <?php 
                                        $suppliers = $newStore->getActiveSuppliers();
                                        while($sup = mysqli_fetch_assoc($suppliers)) {
                                            $selected = ($sup['id'] == $supplier_id) ? 'selected' : '';
                                            echo "<option value='{$sup['id']}' $selected>{$sup['supplier_name']}</option>";
                                        }
                                        ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>From Date</label>
                                                <input type="date" class="form-control" name="from_date"
                                                    value="<?= $from_date?>">
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>To Date</label>
                                                <input type="date" class="form-control" name="to_date"
                                                    value="<?= $to_date?>">
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>&nbsp;</label>
                                                <button type="submit" class="btn btn-success btn-block">
                                                    <i class="fa fa-search"></i> Show Ledger
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if($supplier_id): 
        // Get supplier details
        $supplier_details = $newStore->getSupplierById($supplier_id);
        $supplier_info = mysqli_fetch_assoc($supplier_details);
        
        // Get ledger entries
        $ledger_entries = $newStore->getSupplierLedger($supplier_id, $from_date, $to_date);
        
        // Calculate totals
        $total_debit = 0;
        $total_credit = 0;
        $opening_balance = 0;
    ?>

                <!-- Supplier Info -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="summary-box">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4><?= $supplier_info['supplier_name']?></h4>
                                    <p class="mb-0"><i class="fa fa-phone"></i> <?= $supplier_info['phone']?></p>
                                    <p class="mb-0"><i class="fa fa-envelope"></i> <?= $supplier_info['email']?></p>
                                    <p class="mb-0"><i class="fa fa-map-marker"></i> <?= $supplier_info['address']?></p>
                                </div>
                                <div class="col-md-6 text-right">
                                    <h6>Period: <?= date('d-M-Y', strtotime($from_date))?> to
                                        <?= date('d-M-Y', strtotime($to_date))?></h6>
                                    <?php 
                        $current_balance = $newStore->getSupplierBalance($supplier_id);
                        ?>
                                    <h3>Current Outstanding: ₹<?= number_format($current_balance, 2)?></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ledger Table -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header no-print">
                                <h5>Ledger Entries</h5>
                                <button class="btn btn-secondary btn-sm float-right" onclick="window.print()">
                                    <i class="fa fa-print"></i> Print
                                </button>
                                <button class="btn btn-success btn-sm float-right mr-2" onclick="exportToExcel()">
                                    <i class="fa fa-file-excel-o"></i> Export Excel
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered ledger-table" id="ledgerTable">
                                        <thead>
                                            <tr>
                                                <th width="10%">Date</th>
                                                <th width="15%">Refrence No</th>
                                                <th width="25%">Particulars</th>
                                                <th width="10%" class="text-right">Debit (₹)</th>
                                                <th width="10%" class="text-right">Credit (₹)</th>
                                                <th width="10%" class="text-right">Balance (₹)</th>
                                                <th width="10%">Status</th>
                                                <th width="10%" class="no-print">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                $running_balance = 0;
                                
                                if(mysqli_num_rows($ledger_entries) > 0):
                                    while($entry = mysqli_fetch_assoc($ledger_entries)):
                                        $debit = $entry['debit'];
                                        $credit = $entry['credit'];
                                        
                                        $running_balance += $debit - $credit;
                                        $total_debit += $debit;
                                        $total_credit += $credit;
                                        
                                        $view_link = '';
                                        if($entry['transaction_type'] == 'INVOICE') {
                                            $view_link = "view_store_invoice.php?id=" . $entry['reference_id'];
                                        } else {
                                            $view_link = "view_store_payment.php?id=" . $entry['reference_id'];
                                        }
                                ?>
                                            <tr>
                                                <td><?= date('d-M-Y', strtotime($entry['transaction_date']))?></td>
                                                <td><?= $entry['reference_number']?></td>
                                                <td><?= $entry['transaction_type']?></td>
                                                <td class="text-right debit-col">
                                                    <?= $debit > 0 ? number_format($debit, 2) : '-'?>
                                                </td>
                                                <td class="text-right credit-col">
                                                    <?= $credit > 0 ? number_format($credit, 2) : '-'?>
                                                </td>
                                                <td class="text-right balance-col">
                                                    <?= number_format($running_balance, 2)?>
                                                </td>
                                                <td>
                                                    <?php if($entry['payment_status']): ?>
                                                    <span
                                                        class="badge badge-<?= $entry['payment_status'] == 'PAID' ? 'success' : ($entry['payment_status'] == 'PARTIALLY_PAID' ? 'warning' : 'danger')?>">
                                                        <?= $entry['payment_status']?>
                                                    </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="no-print">
                                                    <a href="<?= $view_link?>" class="btn btn-info btn-xs">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php 
                                    endwhile;
                                else:
                                ?>
                                            <tr>
                                                <td colspan="8" class="text-center">No transactions found for the
                                                    selected period</td>
                                            </tr>
                                            <?php endif; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr class="table-dark">
                                                <td colspan="3" class="text-right"><strong>TOTAL:</strong></td>
                                                <td class="text-right debit-col">
                                                    <strong><?= number_format($total_debit, 2)?></strong>
                                                </td>
                                                <td class="text-right credit-col">
                                                    <strong><?= number_format($total_credit, 2)?></strong>
                                                </td>
                                                <td class="text-right balance-col">
                                                    <strong><?= number_format($running_balance, 2)?></strong>
                                                </td>
                                                <td colspan="2"></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Summary Cards -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="card bg-danger text-white">
                            <div class="card-body">
                                <h5>Total Invoices (Debit)</h5>
                                <h3>₹<?= number_format($total_debit, 2)?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h5>Total Payments (Credit)</h5>
                                <h3>₹<?= number_format($total_credit, 2)?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <h5>Net Balance</h5>
                                <h3>₹<?= number_format($running_balance, 2)?></h3>
                            </div>
                        </div>
                    </div>
                </div>

                <?php endif; ?>

            </div>

        </div>

        <?php include('footer.php') ?>

    </div>

    <script src="vendor/global/global.min.js"></script>
    <script src="js/quixnav-init.js"></script>
    <script src="js/custom.min.js"></script>

    <script>
    $(document).ready(function() {
        $('.select2').select2();
    });

    function exportToExcel() {
        var table = document.getElementById('ledgerTable');
        var html = table.outerHTML;

        // Create a blob with the HTML content
        var blob = new Blob([html], {
            type: 'application/vnd.ms-excel'
        });

        // Create download link
        var a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = 'supplier_ledger_' + new Date().getTime() + '.xls';
        a.click();
    }
    </script>

</body>

</html>