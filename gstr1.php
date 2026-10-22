<?php
date_default_timezone_set("Asia/Kolkata");  
require_once(__DIR__ . '/model/session_config.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>OT Reports</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">

    <link href="./vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">



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


    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">
<?php 
include('leftbar.php'); 
?>
<!--**********************************
    Content body start
***********************************-->
<div class="content-body">
    <div class="container-fluid">

        <div class="row page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Reports</a></li>
                <li class="breadcrumb-item"><a href="javascript:void(0)">GSTR-1 Report</a></li>
            </ol>
        </div>

        <!-- Nav tabs -->
        <ul class="nav nav-pills mb-3">
            <li class="nav-item"><a href="#b2b" data-bs-toggle="pill" class="nav-link active">B2B</a></li>
            <li class="nav-item"><a href="#b2cl" data-bs-toggle="pill" class="nav-link">B2C Large</a></li>
            <li class="nav-item"><a href="#b2cs" data-bs-toggle="pill" class="nav-link">B2C Small</a></li>
            <li class="nav-item"><a href="#cdnr" data-bs-toggle="pill" class="nav-link">CDNR</a></li>
            <li class="nav-item"><a href="#export" data-bs-toggle="pill" class="nav-link">Export Invoices</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">

            <!-- B2B Tab -->
            <div id="b2b" class="tab-pane fade show active">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="card-title text-white">B2B Invoices</h4>
                        <a href="#" class="btn btn-sm btn-success float-end">Export to Excel</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover text-center display">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Invoice No</th>
                                        <th>Date</th>
                                        <th>Customer GSTIN</th>
                                        <th>Customer Name</th>
                                        <th>Invoice Value (₹)</th>
                                        <th>Taxable Value (₹)</th>
                                        <th>CGST</th>
                                        <th>SGST</th>
                                        <th>IGST</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Sample B2B data loop
                                    for ($i = 1; $i <= 5; $i++) {
                                        echo "<tr>
                                            <td>INV2025B2B$i</td>
                                            <td>2025-06-0$i</td>
                                            <td>29ABCDE1234F2Z5</td>
                                            <td>XYZ Traders Pvt Ltd</td>
                                            <td>₹12,000</td>
                                            <td>₹10,000</td>
                                            <td>₹900</td>
                                            <td>₹900</td>
                                            <td>₹0</td>
                                        </tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- B2C Large -->
            <div id="b2cl" class="tab-pane fade">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="card-title text-white">B2C Large</h4>
                        <a href="#" class="btn btn-sm btn-success float-end">Export to Excel</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover text-center display">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Invoice No</th>
                                        <th>Date</th>
                                        <th>State</th>
                                        <th>Invoice Value (₹)</th>
                                        <th>Taxable Value (₹)</th>
                                        <th>IGST</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    for ($i = 1; $i <= 3; $i++) {
                                        echo "<tr>
                                            <td>INV2025B2CL$i</td>
                                            <td>2025-06-0$i</td>
                                            <td>Karnataka</td>
                                            <td>₹3,00,000</td>
                                            <td>₹2,54,237</td>
                                            <td>₹45,763</td>
                                        </tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- B2C Small -->
            <div id="b2cs" class="tab-pane fade">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="card-title text-white">B2C Small</h4>
                        <a href="#" class="btn btn-sm btn-success float-end">Export to Excel</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover text-center display">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>State</th>
                                        <th>Taxable Value (₹)</th>
                                        <th>CGST</th>
                                        <th>SGST</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    for ($i = 1; $i <= 4; $i++) {
                                        echo "<tr>
                                            <td>2025-06-0$i</td>
                                            <td>Karnataka</td>
                                            <td>₹1,000</td>
                                            <td>₹90</td>
                                            <td>₹90</td>
                                        </tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CDNR -->
            <div id="cdnr" class="tab-pane fade">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="card-title text-white">Credit/Debit Notes - Registered</h4>
                        <a href="#" class="btn btn-sm btn-success float-end">Export to Excel</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover text-center display">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Note No</th>
                                        <th>Date</th>
                                        <th>Customer GSTIN</th>
                                        <th>Customer Name</th>
                                        <th>Note Type</th>
                                        <th>Note Value (₹)</th>
                                        <th>Taxable Value (₹)</th>
                                        <th>CGST</th>
                                        <th>SGST</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    echo "<tr>
                                        <td>CDN001</td>
                                        <td>2025-06-02</td>
                                        <td>29ABCDE1234F2Z5</td>
                                        <td>XYZ Traders</td>
                                        <td>Credit</td>
                                        <td>₹1,180</td>
                                        <td>₹1,000</td>
                                        <td>₹90</td>
                                        <td>₹90</td>
                                    </tr>";
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Export Invoices -->
            <div id="export" class="tab-pane fade">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="card-title text-white">Export Invoices</h4>
                        <a href="#" class="btn btn-sm btn-success float-end">Export to Excel</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover text-center display">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Invoice No</th>
                                        <th>Date</th>
                                        <th>Invoice Value (₹)</th>
                                        <th>Taxable Value (₹)</th>
                                        <th>IGST</th>
                                        <th>Export Type</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    echo "<tr>
                                        <td>EXP001</td>
                                        <td>2025-06-03</td>
                                        <td>₹50,000</td>
                                        <td>₹50,000</td>
                                        <td>₹0</td>
                                        <td>With Payment</td>
                                    </tr>";
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- tab-content end -->

    </div>
</div>
<!--**********************************
    Content body end
***********************************-->
<?php include('footer.php') ?>

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
    <!-- Datatable -->
    <script src="./vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="./js/plugins-init/datatables.init.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const hash = window.location.hash;
    if (hash) {
        const tabTriggerEl = document.querySelector(`a[href="${hash}"]`);
        if (tabTriggerEl) {
            const tab = new bootstrap.Tab(tabTriggerEl);
            tab.show();
        }
    }

    // Update URL hash on tab change
    const tabLinks = document.querySelectorAll('a[data-bs-toggle="pill"]');
    tabLinks.forEach((link) => {
        link.addEventListener("shown.bs.tab", function (e) {
            history.replaceState(null, null, e.target.getAttribute("href"));
        });
    });
});
</script>


  

</body>

</html>
