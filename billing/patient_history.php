<?php
require_once(__DIR__ . '/../model/session_config.php');
date_default_timezone_set("Asia/Kolkata");
include('model/billingClass.php');

$newReport = new addBilling;

if (isset($_REQUEST['search'])) {
    $search = $_REQUEST['search'];
} else {
    $search = "";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Patient History</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <!-- Sweet Alert-->
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






        <?php include('leftbar.php') ?>


        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <!-- row -->



            <div class="container-fluid">

                <?php

                $patient = array();
                $data = $newReport->selectOP_seacrh($search);
                $row = mysqli_fetch_assoc($data);
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Patient History</h4>
                            </div>

                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h5>Patient Details</h5>

                                <hr>

                                <div class="table-responsive">
                                    <table id="" class="table display ">
                                        <thead class="">
                                            <tr>
                                                <th>Patient Id.</th>
                                                <th><?= $row['opd_no'] ?></th>
                                                <th>Name</th>
                                                <th><?= $row['name'] ?></th>
                                                <th>Address</th>
                                                <th><?= $row['address'] ?></th>

                                            </tr>
                                            <tr>

                                                <th>Phone</th>
                                                <th><?= $row['mobile'] ?></th>
                                                <th>Department</th>
                                                <th><?= $row['dept_name'] ?></th>
                                                <th>Doctor</th>
                                                <th><?= $row['doctor'] ?></th>
                                            </tr>
                                        </thead>

                                    </table>
                                </div>

                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h5>Billing Details of <span class="text-danger"><?= $row['name'] ?> (<?= $row['ipd_no'] ?>)</span></h5>

                                <hr>

                                <div class="table-responsive">
                                    <table id="" class="table display ">
                                        <thead class="">
                                            <tr>
                                                <th>Sl.No.</th>
                                                <th>Date</th>
                                                <th>Bill No</th>
                                                <th>Department</th>
                                                <th>Doctor</th>
                                                <th>Paid Amount</th>
                                                <th>Balance Amount</th>
                                                <th>Collected By</th>
                                                <th>Action</th>
                                            </tr>

                                        </thead>
                                        <tbody>
                                            <?php
                                            $opd = $newReport->getBillPatient($row['ipd_no']);
                                            $sn = 1;
                                            while ($res = mysqli_fetch_assoc($opd)) {
                                            ?>
                                                <tr>
                                                    <td><?= $sn ?></td>
                                                    <td><?= date("d.m.Y H:i:s", strtotime($res['date'])) ?></td>
                                                    <td><?= $res['bill_no'] ?></td>
                                                    <td><?= $res['dept_name'] ?></td>
                                                    <td><?= $res['doctor'] ?></td>
                                                    <td><?= $res['paid_amount'] ?></td>
                                                    <td class="<?= isset($res['outstand_amount']) && $res['outstand_amount'] > 0 ? 'text-danger' : '' ?> "><?= $res['outstand_amount'] ?></td>
                                                    <td><?= $res['collected_by'] ?></td>
                                                    <td>
                                                        <?php if ($res['service_type'] == 'IP') { ?>
                                                            <a href="./view_ipbilling.php?id=<?= $res['patient_id'] ?>&bill_no=<?= $res['bill_no'] ?>&bill_id=<?= $res['id'] ?>&status=<?= $res['status'] ?>" title="View" class="text-warning"><i class="ri-eye-line" style="font-size:15px" target="_blank"></i>
                                                                <a href="./print_ipbill.php?id=<?= $res['patient_id'] ?>&bill_no=<?= $res['bill_no'] ?>&type=<?= $res['service_type'] ?>&status=<?= $res['status'] ?>" title="Print" class="text-primary"><i class="ri-printer-line" style="font-size:15px" target="_blank"></i> </a>
                                                            <?php } elseif ($res['service_type'] == 'Advance') { ?>
                                                                <a href="./print_advance_amount.php?id=<?= $res['patient_id'] ?>&adv_id=<?= str_replace("ADV", "", $res['bill_no']) ?>&type=<?= $res['service_type'] ?>&status=<?= $res['status'] ?>" title="Print" class="text-primary"><i class="ri-printer-line" style="font-size:15px" target="_blank"></i> </a>
                                                            <?php } else { ?>
                                                                <a href="./view_billing.php?id=<?= $res['patient_id'] ?>&bill_no=<?= $res['bill_no'] ?>&bill_id=<?= $res['id'] ?>&status=<?= $res['status'] ?>" title="View" class="text-warning"><i class="ri-eye-line" style="font-size:15px" target="_blank"></i>
                                                                    <a href="./print_bill.php?id=<?= $res['patient_id'] ?>&bill_no=<?= $res['bill_no'] ?>&type=<?= $res['service_type'] ?>&status=<?= $res['status'] ?>" title="Print" class="text-primary"><i class="ri-printer-line" style="font-size:15px" target="_blank"></i> </a>
                                                                <?php } ?>
                                                    </td>
                                                </tr>
                                                <?php $sn++;
                                                $patient[] = $res['id'];
                                            }

                                            $customerUniqueCode = isset($row['rx_id']) ? trim($row['rx_id']) : '';
                                            // echo $customerUniqueCode."------------------";
                                            if (!empty($customerUniqueCode)) {
                                                $pendingOrders = array();

                                                // Fetch both UNPAID and PARTIALLY_PAID orders
                                                $paymentStatuses = array('UNPAID', 'PARTIAL_PAID');

                                                foreach ($paymentStatuses as $status) {
                                                    $apiUrl = 'https://api.retailxpress.ai/v1/external/customer-order?page=1&limit=20&sort_by=createdAt&sort_order=desc&payment_status=' . $status . '&customer_id=' . urlencode($customerUniqueCode);

                                                    $curl = curl_init();
                                                    curl_setopt_array($curl, array(
                                                        CURLOPT_URL => $apiUrl,
                                                        CURLOPT_RETURNTRANSFER => true,
                                                        CURLOPT_ENCODING => '',
                                                        CURLOPT_MAXREDIRS => 10,
                                                        // Bounded on purpose — see view_ipbilling.php. This runs
                                                        // during page render, so an unbounded timeout lets a slow
                                                        // or unreachable pharmacy API hang the request until the
                                                        // web server returns 504.
                                                        CURLOPT_CONNECTTIMEOUT => 3,
                                                        CURLOPT_TIMEOUT => 8,
                                                        CURLOPT_FOLLOWLOCATION => true,
                                                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                                        CURLOPT_CUSTOMREQUEST => 'GET',
                                                        CURLOPT_HTTPHEADER => array(
                                                            'x-api-key: 8417daa38eb33753f60ecbabe28d359bea4b4589d8c791f563b00b3de7c53543'
                                                        ),
                                                    ));

                                                    $response = curl_exec($curl);
                                                    curl_close($curl);
                                                    // echo $response;
                                                    $decoded = json_decode($response, true);
                                                    $statusOrders = array();

                                                    if (isset($decoded['data']['items']) && is_array($decoded['data']['items'])) {
                                                        $statusOrders = $decoded['data']['items'];
                                                    } elseif (isset($decoded['data']) && is_array($decoded['data'])) {
                                                        $statusOrders = $decoded['data'];
                                                    } elseif (isset($decoded['items']) && is_array($decoded['items'])) {
                                                        $statusOrders = $decoded['items'];
                                                    }

                                                    // Merge orders from this status into pendingOrders
                                                    $pendingOrders = array_merge($pendingOrders, $statusOrders);
                                                }

                                                foreach ($pendingOrders as $order) {
                                                    $orderDate = '';
                                                    if (!empty($order['createdAt'])) {
                                                        $orderDate = date('d.m.Y H:i:s', strtotime($order['createdAt']));
                                                    } elseif (!empty($order['issue_date'])) {
                                                        $orderDate = date('d.m.Y H:i:s', strtotime($order['issue_date']));
                                                    } elseif (!empty($order['date'])) {
                                                        $orderDate = date('d.m.Y H:i:s', strtotime($order['date']));
                                                    }

                                                    $billNo = '-';
                                                    if (!empty($order['invoice_number'])) {
                                                        $billNo = $order['invoice_number'];
                                                    } elseif (!empty($order['order_number'])) {
                                                        $billNo = $order['order_number'];
                                                    } elseif (!empty($order['id'])) {
                                                        $billNo = $order['id'];
                                                    }

                                                    $pendingAmount = 0;
                                                    if (isset($order['due_amount'])) {
                                                        $pendingAmount = $order['due_amount'];
                                                    }
                                                    if (isset($order['total_amount']) && isset($order['due_amount'])) {
                                                        $paidAmount = $order['total_amount'] - $order['due_amount'];
                                                    }
                                                ?>
                                                    <tr>
                                                        <td><?= $sn ?></td>
                                                        <td><?= $orderDate ?></td>
                                                        <td><?= $billNo ?></td>
                                                        <td>Pharmacy</td>
                                                        <td>-</td>
                                                        <td><?= isset($paidAmount) ? $paidAmount : 0 ?></td>
                                                        <td class="<?= isset($pendingAmount) && $pendingAmount > 0 ? 'text-danger' : '' ?> "><?= $pendingAmount ?></td>
                                                        <td>RetailXpress</td>
                                                        <td>-</td>
                                                    </tr>
                                            <?php
                                                    $sn++;
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
                <?php  ?>
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

    <script>
        async function getPatient(val) {
            if (val.length > 4) {
                try {
                    const response = await $.ajax({
                        url: 'ajax.php',
                        type: 'POST',
                        data: 'search=' + val + '&page=getPatient',
                    });

                    if (response.length > 0) {
                        $('#searchpatients').html(response);
                    } else {
                        $('#searchpatients').html("<option value=''>NO Result Found, add New</option>");
                    }
                } catch (error) {
                    console.error('Error in AJAX request:', error);
                }
            } else {
                $('#searchpatients').html("");
            }
        }

        function getOPDdetails(val) {
            data = val.split("-");

            if (data[0].startsWith('OP')) {
                $('#search').val(data[0]);

            }

        }
    </script>





</body>

</html>