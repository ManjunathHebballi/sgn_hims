<?php

require_once(__DIR__ . '/../model/session_config.php');
include('model/billingClass.php');
$newBill = new addBilling;

$result = $newBill->getIPPatient($_REQUEST['id'], $_REQUEST['bill_no']);
$row1 = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>View OP Billing</title>
  <!-- base:css -->

  <link rel="stylesheet" href="css/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="images/favicon.png" />

  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
  <link href="./css/style.css" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <!-- Sweet Alert-->
  <link href="css/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
  <style>
    .watermark {
      position: absolute;
      font-size: 50px;
      /* Adjust font size as needed */
      color: rgba(0, 0, 0, 0.5);
      /* Set text color with reduced opacity */
      transform: rotate(-45deg) scaleX(2);
      padding-left: 300px;
      padding-top: 300px;
    }

    a.disabled {
      pointer-events: none;
      cursor: default;
    }
  </style>
</head>

<body onload="getButton()">

  <div id="main-wrapper">
    <?php include('leftbar.php') ?>
    <div class="content-body">
      <div class="container-fluid page-body-wrapper">
        <div class="main-panel">
          <div class="content-wrapper ">
            <div class="row">
              <div class="col-md-8">
                <h3 class="page-title text-dark font-weight-bold mb-2 px-4">VIEW BILLING [<?= $row1['bill_no'] ?>]</h3>
              </div>

            </div>


            <div class="row mb-4 mt-3">
              <div class="col-md-12">


                <div class="card">
                  <div class="card-body">

                    <div class="row">
                      <div class="col-md-3">
                        <div class="form-group">

                          <label for="pname">IPD No</label>
                          <input onchange="javascript:patientDetail(this.value)" class="form-control text-uppercase" type="text" value="<?= $row1['ipd_no'] ?>" readonly>
                          <input type="hidden" name="patient_id" id="patient_id" value="">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">

                          <label for="pname">Patient Name</label>
                          <input class="form-control text-uppercase" type="text" readonly value="<?= $row1['name'] ?>">

                        </div>
                      </div>

                      <div class="col-md-3">
                        <div class="form-group">

                          <label for="pname">Age</label>
                          <input class="form-control text-uppercase" type="text" readonly value="<?= $row1['age'] ?>">

                        </div>
                      </div>

                      <div class="col-md-3">
                        <div class="form-group">

                          <label for="pname">Gender</label>
                          <input class="form-control text-uppercase" type="text" readonly value="<?= $row1['gender'] ?>">

                        </div>
                      </div>

                      <div class="col-md-3">
                        <div class="form-group">

                          <label for="pname">Mobile Number</label>
                          <input class="form-control text-uppercase" type="text" value="<?= $row1['mobile'] ?>" readonly>

                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label>Doctor <small class="text-danger"> *</small></label>
                          <input class="form-control text-uppercase" type="text" value="<?= $row1['doc'] ?>" readonly>


                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label>Departement <small class="text-danger"> *</small></label>

                          <input class="form-control text-uppercase" type="text" value="<?= $row1['dept_name'] ?>" readonly>

                        </div>
                      </div>


                      <input type="hidden" name="patient_type" id="patient_type" value="IP">
                      <input type="hidden" name="service_type" id="service_type" value="IP">

                      <div class="col-md-3">
                        <div class="form-group">
                          <label for="date">Billing Date</label>
                          <input class="form-control text-uppercase" value="<?php echo date("Y-m-d H:i:s", strtotime($row1['bill_date'])); ?>">
                        </div>

                      </div>




                    </div>



                  </div>
                </div>
              </div>
            </div>


            <div class="row">
              <div class='col-sm-12'>
                <button type="button" class="btn btn-primary btn-sm float-right" data-toggle="modal" id="pay_button" style="display:none;" data-target="#exampleModal">
                  Record Payment
                </button>
              </div>
              <div class="col-md-12">
                <!-- Button trigger modal -->



                <div class="card">
                  <div class="card-body table-responsive">
                    <div class="row">
                      <div class="col-md-9">
                        <h3>Billing Summary</h3>
                      </div>
                      <div class="col-md-3">
                        <button type="button" id="pendingBillBtn" class="btn btn-info float-right ml-3 d-print-none" aria-expanded="false" aria-controls="pendingBillCollapse">
                          Pending Bill
                        </button>
                      </div>
                    </div>

                    <div class="collapse mt-3" id="pendingBillCollapse">
                      <div class="card card-body p-0">
                        <div class="table-responsive">
                          <h5>Pending bills for this patient:</h5>
                          <div class="d-flex align-items-center mb-3">
                            <button type="button" id="bulkPayBtn" class="btn btn-success btn-sm mr-2 d-print-none" disabled>Bulk Pay Selected</button>
                            <span id="bulkPaySelectedInfo" class="text-muted small"></span>
                          </div>
                          <table class="table table-bordered mb-0">
                            <thead>
                              <tr>
                                <th><input type="checkbox" id="selectAllPending" title="Select All"></th>
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
                              $pendingSn = 1;
                              $hasPending = false;
                              $pendingRes = $newBill->getBillPatient($row1['ipd_no']);
                              $total_balance = 0;
                              if ($pendingRes) {
                                while ($pendingRow = mysqli_fetch_assoc($pendingRes)) {
                                  if ($pendingRow['outstand_amount'] <= 0) {
                                    continue; // Skip bills with no outstanding amount
                                  }
                                  $hasPending = true;
                              ?>
                                  <tr>
                                    <td><input type="checkbox" class="pending-bill-checkbox"
                                        data-bill-id="<?= $pendingRow['id'] ?>"
                                        data-bill-no="<?= htmlspecialchars($pendingRow['bill_no'], ENT_QUOTES) ?>"
                                        data-balance="<?= !empty($pendingRow['outstand_amount']) ? $pendingRow['outstand_amount'] : 0 ?>"
                                        id="chk_<?= $pendingRow['id'] ?>"></td>
                                    <td><?= $pendingSn ?></td>
                                    <td><?= date("d.m.Y H:i:s", strtotime($pendingRow['date'])) ?></td>
                                    <td><?= $pendingRow['bill_no'] ?></td>
                                    <td><?= !empty($pendingRow['dept_name']) ? $pendingRow['dept_name'] : '-' ?></td>
                                    <td><?= !empty($pendingRow['doctor']) ? $pendingRow['doctor'] : '-' ?></td>
                                    <td><?= !empty($pendingRow['paid_amount']) ? $pendingRow['paid_amount'] : 0 ?></td>
                                    <td class="<?= isset($pendingRow['outstand_amount']) && $pendingRow['outstand_amount'] > 0 ? 'text-danger' : '' ?>"><?= $balance = !empty($pendingRow['outstand_amount']) ? $pendingRow['outstand_amount'] : 0 ?></td>
                                    <td><?= !empty($pendingRow['collected_by']) ? $pendingRow['collected_by'] : '-' ?></td>
                                    <td> <?php if ($pendingRow['service_type'] == 'IP') { ?>
                                        <a href="./view_ipbilling.php?id=<?= $pendingRow['patient_id'] ?>&bill_no=<?= $pendingRow['bill_no'] ?>&bill_id=<?= $pendingRow['id'] ?>&status=<?= $pendingRow['status'] ?>" title="View" class="text-warning"><i class="ri-eye-line" style="font-size:15px" target="_blank"></i>
                                          <a href="./print_ipbill.php?id=<?= $pendingRow['patient_id'] ?>&bill_no=<?= $pendingRow['bill_no'] ?>&type=<?= $pendingRow['service_type'] ?>&status=<?= $pendingRow['status'] ?>" title="Print" class="text-primary"><i class="ri-printer-line" style="font-size:15px" target="_blank"></i> </a>
                                        <?php } elseif ($pendingRow['service_type'] == 'Advance') { ?>
                                          <a href="./print_advance_amount.php?id=<?= $pendingRow['patient_id'] ?>&adv_id=<?= str_replace("ADV", "", $pendingRow['bill_no']) ?>&type=<?= $pendingRow['service_type'] ?>&status=<?= $pendingRow['status'] ?>" title="Print" class="text-primary"><i class="ri-printer-line" style="font-size:15px" target="_blank"></i> </a>
                                        <?php } else { ?>
                                          <a href="./view_billing.php?id=<?= $pendingRow['patient_id'] ?>&bill_no=<?= $pendingRow['bill_no'] ?>&bill_id=<?= $pendingRow['id'] ?>&status=<?= $pendingRow['status'] ?>" title="View" class="text-warning"><i class="ri-eye-line" style="font-size:15px" target="_blank"></i>
                                            <a href="./print_bill.php?id=<?= $pendingRow['patient_id'] ?>&bill_no=<?= $pendingRow['bill_no'] ?>&type=<?= $pendingRow['service_type'] ?>&status=<?= $pendingRow['status'] ?>" title="Print" class="text-primary"><i class="ri-printer-line" style="font-size:15px" target="_blank"></i> </a>
                                          <?php } ?>
                                    </td>
                                  </tr>
                                <?php
                                  $pendingSn++;
                                  $total_balance += $balance;
                                }
                              }

                              $customerUniqueCode = isset($row1['rx_id']) ? trim($row1['rx_id']) : '';
                              $pharmacyApiFailed = false;
                              if (!empty($customerUniqueCode)) {
                                $pendingOrders = array();
                                $paymentStatuses = array('UNPAID', 'PARTIAL_PAID');

                                foreach ($paymentStatuses as $status) {
                                  $apiUrl = 'https://api.retailxpress.ai/v1/external/customer-order?page=1&limit=20&sort_by=createdAt&sort_order=desc&payment_status=' . $status . '&customer_id=' . urlencode($customerUniqueCode);

                                  $curl = curl_init();
                                  curl_setopt_array($curl, array(
                                    CURLOPT_URL => $apiUrl,
                                    CURLOPT_RETURNTRANSFER => true,
                                    CURLOPT_ENCODING => '',
                                    CURLOPT_MAXREDIRS => 10,
                                    // NEVER leave these unbounded. This runs while the page is
                                    // being rendered, so with CURLOPT_TIMEOUT => 0 (wait
                                    // forever) an unreachable or slow pharmacy API held the
                                    // request open until the web server's own gateway timeout
                                    // fired — the browser span and the user got a 504. Two
                                    // statuses are fetched in sequence, so the worst case is
                                    // twice these values, and the page still renders after it.
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
                                  // A failed call must not look like "this patient has no
                                  // pharmacy dues" — that reads as a settled account.
                                  if ($response === false || curl_errno($curl) !== 0) {
                                    $pharmacyApiFailed = true;
                                  }
                                  curl_close($curl);

                                  $decoded = json_decode($response, true);
                                  $statusOrders = array();

                                  if (isset($decoded['data']['items']) && is_array($decoded['data']['items'])) {
                                    $statusOrders = $decoded['data']['items'];
                                  } elseif (isset($decoded['data']) && is_array($decoded['data'])) {
                                    $statusOrders = $decoded['data'];
                                  } elseif (isset($decoded['items']) && is_array($decoded['items'])) {
                                    $statusOrders = $decoded['items'];
                                  }

                                  $pendingOrders = array_merge($pendingOrders, $statusOrders);
                                }

                                foreach ($pendingOrders as $order) {
                                  $hasPending = true;

                                  $orderDate = '-';
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
                                  } elseif (isset($order['pending_amount'])) {
                                    $pendingAmount = $order['pending_amount'];
                                  } elseif (isset($order['amount_due'])) {
                                    $pendingAmount = $order['amount_due'];
                                  } elseif (isset($order['outstanding_amount'])) {
                                    $pendingAmount = $order['outstanding_amount'];
                                  }

                                  $paidAmount = 0;
                                  if (isset($order['paid_amount'])) {
                                    $paidAmount = $order['paid_amount'];
                                  } elseif (isset($order['total_amount']) && isset($order['due_amount'])) {
                                    $paidAmount = $order['total_amount'] - $order['due_amount'];
                                  }
                                ?>
                                  <tr>
                                    <td></td>
                                    <td><?= $pendingSn ?></td>
                                    <td><?= $orderDate ?></td>
                                    <td><?= $billNo ?></td>
                                    <td>Pharmacy</td>
                                    <td>-</td>
                                    <td><?= $paidAmount ?></td>
                                    <td class="text-danger"><?= $pendingAmount ?></td>
                                    <td>RetailXpress</td>
                                    <td>-</td>
                                  </tr>
                                <?php
                                  $pendingSn++;
                                  $total_balance += $pendingAmount;
                                }
                                ?>
                                <tr>
                                  <td colspan="7" class="text-right font-weight-bold">Total Pending Amount:</td>
                                  <td class="text-danger font-weight-bold"><?= $total_balance ?></td>
                                  <td colspan="2"></td>
                                </tr>
                              <?php
                              }

                              if (!$hasPending) {
                              ?>
                                <tr>
                                  <td colspan="10" class="text-center">No pending bills found</td>
                                </tr>
                              <?php }
                              // Say so explicitly when the pharmacy API could not be reached:
                              // the hospital dues above are complete, the pharmacy ones are
                              // simply unknown for this render.
                              if ($pharmacyApiFailed) { ?>
                                <tr>
                                  <td colspan="10" class="text-center text-danger">
                                    Pharmacy dues could not be loaded (RetailXpress did not respond). Hospital bills above are complete.
                                  </td>
                                </tr>
                              <?php } ?>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>


                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th>Sl. NO</th>
                          <th>Items</th>
                          <th>Unit</th>
                          <th>Price</th>
                          <th>Amount</th>
                          <th>Disc Amt</th>
                          <th>Total Amount</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php $result = $newBill->getBill($_REQUEST['id'], $_REQUEST['bill_no']);
                        $gross = 0;
                        $disc = 0;
                        $net = 0;
                        $sn = 1;
                        $collected = '';
                        $disc_amt = 0;
                        while ($row = mysqli_fetch_assoc($result)) {



                        ?>
                          <tr>
                            <td class="text-center"><?php echo $sn; ?></td>
                            <td class="text-left pl-1"><?= $row['service'] ?> <?= $row['doctor_name'] ? "[" . $row['doctor_name'] . "]" : $row['description'] ?></td>
                            <td class="text-center"><?php echo $row['unit']; ?></td>
                            <td class="text-center"><?php echo $row['price']; ?></td>
                            <td class="text-center"><?php echo $row['unit'] * $row['price']; ?></td>
                            <td class="text-center"><?php echo $row['discount']; ?></td>
                            <td class="text-center"><?php echo ($row['unit'] * $row['price']) - $row['discount']; ?></td>


                          </tr>
                          <?php if ($_REQUEST['status'] == 'CANCELLED') { ?> <div class="watermark top-left">CANCELLED</div> <?php } ?>
                        <?php $sn++;
                          $gross += $row['unit'] * $row['price'];
                          $disc += $row['discount'];
                          $net += ($row['unit'] * $row['price']) - $row['discount'];
                          $collected = $row['collected_by'];
                          $disc_amt = $row['discount_amount'];
                          $paid = $row['paid_amount'];
                          $return = $row['returned_amount'];
                          $advance = $row['adjusted_amount'];
                        }  ?>

                      </tbody>
                      <!-- </table>
            <table class="table table-responsive table-bordered"   id="tab_logic"> -->
                      <tfoot>
                        <!-- <tr id='addr110'></tr> -->
                        <tr>

                          <td colspan="5"> </td>
                          <td class="text-center"> Gross Amount </td>
                          <td class="text-center">
                            <?= $gross ?>
                          </td>

                        </tr>
                        <tr>

                          <td colspan="5"> </td>
                          <td class="text-center"> Service Level Discount </td>
                          <td class="text-center">
                            <?= $disc ?>
                          </td>

                        </tr>
                        <tr>

                          <td colspan="5"> </td>
                          <td class="text-center"> Net Amount </td>
                          <td class="text-center">
                            <?= $gross - $disc ?>
                          </td>

                        </tr>


                        <tr>
                          <td colspan="5"> </td>
                          <td class="text-center"> Discount Amount </td>
                          <td class="text-center">
                            <?= $disc_amt ?>
                          </td>
                        </tr>
                        <tr>
                          <td colspan="5"> </td>
                          <td class="text-center"> Grand Total Amount </td>
                          <td class="text-center">
                            <?= $gross - $disc - $disc_amt ?>
                          </td>
                        </tr>
                        <?php if ($advance > 0) { ?>
                          <tr>
                            <td colspan="5"> </td>
                            <td class="text-center"> Advnace Amount </td>
                            <td class="text-center">
                              <?= $advance ?>
                            </td>
                          </tr>
                        <?php } ?>
                        <tr>
                          <td colspan="5"> </td>
                          <td class="text-center"> Amount Paid </td>
                          <td class="text-center">
                            <?= $paid ?>
                          </td>
                        </tr>
                        <?php if ($return != 0) { ?>
                          <tr>
                            <td colspan="5"> </td>
                            <td class="text-center"> Returned Amount </td>
                            <td class="text-center">
                              <?= $return ?>
                            </td>
                          </tr>
                        <?php } ?>
                        <tr>

                          <td colspan="5"> </td>
                          <td class="text-center"> Outstanding amount </td>
                          <td class="text-center">
                            <?= $outstand = $gross ? $gross - $disc - $disc_amt - $paid - abs($advance - $return) : abs($advance - $return) ?>
                          </td>

                        </tr>




                        <input type="hidden" name="id" id="id" value=1>



                      </tfoot>
                    </table>





                  </div>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Payment</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <div class="container mt-4">
                          <form method="post" action="controller/processBilling.php" id="form1">
                            <div class="mb-3">
                              <p><b>Due Amount : </b>Rs. <?= $outstand ?></p>
                            </div>
                            <div class="mb-3">
                              <label for="payment_made" class="form-label">Amount paid</label>
                              <input type="number" class="form-control" id="payment_made" name="payment_made" max="<?= $outstand ?>" placeholder="Enter amount" value="<?= $outstand ?>" required>
                            </div>

                            <div class="mb-3">
                              <label for="payment_date" class="form-label">Payment Date</label>
                              <input type="datetime-local" class="form-control" id="payment_date" name="payment_date" value="<?php echo date("Y-m-d H:i:s"); ?>" required>
                            </div>

                            <div class="mb-3">
                              <label for="payment_method" class="form-label">Payment Method</label>
                              <select class="form-control" id="payment_method" name="payment_method" required>
                                <option value="" disabled selected>Select method</option>
                                <option value="Cash">Cash</option>
                                <option value="Card">Card</option>
                                <option value="UPI">UPI</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                                <option value="Cheque">Cheque</option>
                                <option value="Insurance Claim">Insurance Claim</option>
                              </select>
                            </div>

                            <div class="mb-3">
                              <label for="reference_number" class="form-label">Reference Note</label>
                              <input type="text" class="form-control" id="reference_number" name="reference_note" placeholder="Enter reference note">
                            </div>


                        </div>

                      </div>
                      <div class="modal-footer">
                        <input type="hidden" name="patient_type" id="patient_type" value="IP">
                        <input type="hidden" name="service_type" id="service_type" value="IP">
                        <input type="hidden" name="bill_id" value="<?= $_REQUEST['bill_id'] ?>">
                        <input type="hidden" name="bill_no" value="<?= $_REQUEST['bill_no'] ?>"><input type="hidden" name="id" value="<?= $_REQUEST['id'] ?>">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <input type="hidden" name="submit" value="payment">
                        <button type="submit" id="submitBtn" class="btn btn-primary">Submit</button>
                      </div>
                    </div>
                  </div>
                </div>
                </form>

                <!-- Bulk Payment Modal -->
                <div class="modal fade" id="bulkPaymentModal" tabindex="-1" aria-labelledby="bulkPaymentModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="bulkPaymentModalLabel">Bulk Payment</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      </div>
                      <form method="post" action="controller/processBilling.php" id="bulkPayForm">
                        <div class="modal-body">
                          <!-- Summary table of selected bills -->
                          <table class="table table-bordered table-sm mb-3" id="bulkPaySummaryTable">
                            <thead class="thead-light">
                              <tr>
                                <th>#</th>
                                <th>Bill No</th>
                                <th>Balance</th>
                                <th>Pay Amount</th>
                              </tr>
                            </thead>
                            <tbody id="bulkPaySummaryBody"></tbody>
                            <tfoot>
                              <tr>
                                <td colspan="3" class="text-right font-weight-bold">Total Pay Amount:</td>
                                <td class="font-weight-bold text-success" id="bulkPayTotalDisplay">0.00</td>
                              </tr>
                            </tfoot>
                          </table>

                          <div class="form-group">
                            <label for="bulk_payment_date" class="form-label">Payment Date <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control" id="bulk_payment_date" name="bulk_payment_date" value="<?php echo date('Y-m-d\TH:i'); ?>" required>
                          </div>

                          <div class="form-group">
                            <label for="bulk_payment_method" class="form-label">Payment Method <span class="text-danger">*</span></label>
                            <select class="form-control" id="bulk_payment_method" name="bulk_payment_method" required>
                              <option value="" disabled selected>Select method</option>
                              <option value="Cash">Cash</option>
                              <option value="Card">Card</option>
                              <option value="UPI">UPI</option>
                              <option value="Bank Transfer">Bank Transfer</option>
                              <option value="Cheque">Cheque</option>
                              <option value="Insurance Claim">Insurance Claim</option>
                            </select>
                          </div>

                          <div class="form-group">
                            <label for="bulk_reference_note" class="form-label">Reference Note</label>
                            <input type="text" class="form-control" id="bulk_reference_note" name="bulk_reference_note" placeholder="Enter reference note">
                          </div>
                        </div>
                        <div class="modal-footer">
                          <input type="hidden" name="submit" value="bulk_payment">
                          <input type="hidden" name="ipd_no" value="<?= htmlspecialchars($row1['ipd_no'], ENT_QUOTES) ?>">
                          <input type="hidden" name="redirect_id" value="<?= $_REQUEST['id'] ?>">
                          <input type="hidden" name="redirect_bill_no" value="<?= $_REQUEST['bill_no'] ?>">
                          <input type="hidden" name="redirect_bill_id" value="<?= isset($_REQUEST['bill_id']) ? $_REQUEST['bill_id'] : '' ?>">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                          <button type="submit" id="bulkPaySubmitBtn" class="btn btn-success">Confirm Payment</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
                <!-- /Bulk Payment Modal -->

              </div>
              <div class='col-sm-12'>

                <a href="print_ipbill.php?id=<?= $_REQUEST['id'] ?>&bill_no=<?= $_REQUEST['bill_no'] ?>&type=IP" target="_blank" class="btn btn-primary float-right ml-3 d-print-none <?php if ($outstand + $total_balance > 0) echo 'disabled'; ?>" <?php if (!isset($row1['discharge_date']) && ($outstand + $total_balance <= 0)): ?>onclick="return confirm('Patient has not been discharged. Do you want to continue printing without discharge?');" <?php endif; ?>>Print Bill</a>
                <?php if (!isset($row1['discharge_date'])) { ?>
                  <a href="discharge.php?id=<?= $_REQUEST['id'] ?>&bill_no=<?= $_REQUEST['bill_no'] ?>&bill_id=<?= $_REQUEST['bill_id'] ?>&type=IP" target="_blank" class="btn btn-warning float-right ml-3 d-print-none <?php if ($outstand + $total_balance > 0) echo 'disabled'; ?>">Discharge</a>
                <?php } ?>
                <button class="btn btn-danger mb-4 float-right d-print-none" onclick="window.history.back()"> Go Back</button>
              </div>
            </div>

            <?php $result1 = $newBill->getBillPayment($_REQUEST['bill_id'], $_REQUEST['bill_no']);
            if (mysqli_num_rows($result1) > 0) {
            ?>
              <div class='card'>
                <div class='card-body'>
                  <h5>Payment History</h5>
                  <table class='table table-bordered'>

                    <thead>
                      <tr>
                        <th>Sl no</th>
                        <th>Recipt No.</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Payment Mode</th>
                        <th>Payment Note</th>
                        <th>Status</th>
                        <th>Collected By</th>
                        <th>Action</th>
                      </tr>


                    </thead>

                    <tbody>
                      <?php
                      $sn = 1;
                      while ($res1 = mysqli_fetch_assoc($result1)) {
                      ?>
                        <tr>
                          <td><?= $sn ?></td>
                          <td><?= $res1['bill_receipt'] ?></td>
                          <td><?= date("d.m.Y h:i:s A", strtotime($res1['date'])) ?></td>
                          <td><?= $res1['amount'] ?></td>
                          <td><?= $res1['payment_mode'] ?></td>
                          <td><?= $res1['payment_note'] ?></td>
                          <td><?= $res1['payment_status'] ?></td>
                          <td><?= $res1['collected_by'] ?></td>
                          <td>
                            <?php
                            if ($_SESSION['role'] == 'Admin' || $_SESSION['role'] == 'Accountant' || $res1['collected_by'] == $_SESSION['name']) {
                            ?>
                              <a href="#" title="Edit Bill" class="btn btn-info btn-sm mr-1 text-light edit-bill-btn" data-toggle="modal" data-target="#editBillModal"
                                data-payment-id="<?= $res1['id'] ?>"
                                data-amount="<?= $res1['amount'] ?>"
                                data-mode="<?= htmlspecialchars($res1['payment_mode'], ENT_QUOTES) ?>"
                                data-note="<?= htmlspecialchars($res1['payment_note'], ENT_QUOTES) ?>"
                                data-date="<?= date('Y-m-d\TH:i', strtotime($res1['date'])) ?>"
                                data-status="<?= htmlspecialchars($res1['payment_status'], ENT_QUOTES) ?>"
                                data-collected-by="<?= htmlspecialchars($res1['collected_by'], ENT_QUOTES) ?>">
                                <i class="ri-edit-box-line" style="font-size:15px"></i>
                              </a>
                            <?php } ?>
                            <a href="print_receipt.php?id=<?= $_REQUEST['id'] ?>&bill_no=<?= $_REQUEST['bill_no'] ?>&type=IP&bill_id=<?= $res1['id'] ?>" title="Print" class="btn btn-primary btn-sm mr-1 text-light" target="_blank"><i class="ri-printer-line" style="font-size:15px"></i></a>
                          </td>
                        </tr>
                      <?php $sn++;
                      } ?>
                    </tbody>



                  </table>
                </div>
              </div>

            <?php } ?>

          </div>
          <!-- content-wrapper ends -->
          <!-- partial:partials/_footer.html -->

          <!-- partial -->
        </div>
        <!-- main-panel ends -->
      </div>
      <!-- page-body-wrapper ends -->
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
  <!-- Required vendors -->
  <script src="./vendor/global/global.min.js"></script>
  <script src="./js/quixnav-init.js"></script>
  <script src="./js/custom.min.js"></script>
  <!-- Edit Bill Modal -->
  <div class="modal fade" id="editBillModal" tabindex="-1" aria-labelledby="editBillModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form method="post" action="controller/processBilling.php" id="editBillForm">
          <div class="modal-header">
            <h5 class="modal-title" id="editBillModalLabel">Edit Payment</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
          <div class="modal-body">
            <?php
            $readonly = $_SESSION['role'] != 'Admin' && $_SESSION['role'] != 'Accountant' ? 'readonly' : '';
            ?>
            <input type="hidden" name="payment_id" id="edit_payment_id">
            <input type="hidden" name="bill_id" value="<?= $_REQUEST['bill_id'] ?>">
            <input type="hidden" name="bill_no" value="<?= $_REQUEST['bill_no'] ?>">
            <input type="hidden" name="id" value="<?= $_REQUEST['id'] ?>">
            <div class="form-group">
              <label>Amount</label>
              <input type="number" step="0.01" class="form-control" name="amount" id="edit_amount" <?= $readonly ?> required>
            </div>
            <div class="form-group">
              <label>Payment Date</label>
              <input type="datetime-local" class="form-control" name="date" id="edit_date" <?= $readonly ?> required>
            </div>
            <div class="form-group">
              <label>Payment Method</label>
              <select class="form-control" name="payment_mode" id="edit_mode" required>
                <option value="Cash">Cash</option>
                <option value="Card">Card</option>
                <option value="UPI">UPI</option>
                <option value="Bank Transfer">Bank Transfer</option>
                <option value="Cheque">Cheque</option>
              </select>
            </div>
            <div class="form-group">
              <label>Reference Note</label>
              <input type="text" class="form-control" name="payment_note" id="edit_note">
            </div>

            <?php
            if ($_SESSION['role'] == 'Admin' || $_SESSION['role'] == 'Accountant') {
            ?>
              <div class="form-group">
                <label for="exampleInputEmail1">Collected By</label>
                <select class="form-control" name="collected_by" id="edit_collected_by">
                  <?php $data = $newBill->getUser();
                  while ($row = mysqli_fetch_assoc($data)) { ?>
                    <option value="<?= $row['user_name'] ?>"><?= $row['user_name'] ?></option>
                  <?php } ?>
                </select>
              </div>
            <?php } else { ?>
              <input type="hidden" name="collected_by" id="edit_collected_by" value="<?= $_SESSION['name'] ?>">
            <?php } ?>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="submit" value="update_payment">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- /Edit Bill Modal -->

  <script>
    $('#editBillModal').on('show.bs.modal', function(event) {
      var button = $(event.relatedTarget);
      $('#edit_payment_id').val(button.data('payment-id'));
      $('#edit_amount').val(button.data('amount'));
      $('#edit_mode').val(button.data('mode'));
      $('#edit_note').val(button.data('note'));
      $('#edit_date').val(button.data('date'));
      $('#edit_collected_by').val(button.data('collected-by'));
    });
  </script>
  <script>
    function getButton() {
      let cond = <?= $outstand ?>;
      if (cond > 0) {
        document.getElementById("pay_button").style.display = 'block';
      }
    }
    $('form1').submit(function() {
      document.getElementById("submitBtn").disabled = true;
      document.getElementById("submitBtn").innerText = "Submitting...";
    });
  </script>

  <script>
    $(document).ready(function() {
      // Pending Bill collapse toggle
      $('#pendingBillBtn').on('click', function(e) {
        e.preventDefault();
        $('#pendingBillCollapse').collapse('toggle');
      });
      $('#pendingBillCollapse').on('shown.bs.collapse hidden.bs.collapse', function() {
        var isShown = $(this).hasClass('show');
        $('#pendingBillBtn').attr('aria-expanded', isShown ? 'true' : 'false');
      });

      // ---- Bulk Payment ----

      function refreshBulkBtnState() {
        var checked = $('.pending-bill-checkbox:checked');
        if (checked.length > 0) {
          $('#bulkPayBtn').prop('disabled', false);
          $('#bulkPaySelectedInfo').text(checked.length + ' bill(s) selected');
        } else {
          $('#bulkPayBtn').prop('disabled', true);
          $('#bulkPaySelectedInfo').text('');
        }
      }

      // Select all checkbox
      $('#selectAllPending').on('change', function() {
        $('.pending-bill-checkbox').prop('checked', this.checked);
        refreshBulkBtnState();
      });

      // Individual checkbox
      $(document).on('change', '.pending-bill-checkbox', function() {
        var allChecked = ($('.pending-bill-checkbox:not(:checked)').length === 0);
        $('#selectAllPending').prop('checked', allChecked);
        refreshBulkBtnState();
      });

      function recalculateBulkTotal() {
        var total = 0;
        $('.modal-pay-amount').each(function() {
          var max = parseFloat($(this).data('max')) || 0;
          var val = parseFloat($(this).val()) || 0;
          if (val > max) {
            val = max;
            $(this).val(max.toFixed(2));
          }
          if (val < 0) {
            val = 0;
            $(this).val('0.00');
          }
          total += val;
        });
        $('#bulkPayTotalDisplay').text(total.toFixed(2));
        return total;
      }

      // Edit pay amount inside modal (max = bill balance)
      $(document).on('input', '.modal-pay-amount', function() {
        recalculateBulkTotal();
      });

      // Bulk Pay button clicked -> build modal
      $('#bulkPayBtn').on('click', function() {
        var rows = [];
        $('.pending-bill-checkbox:checked').each(function() {
          var billId = $(this).data('bill-id');
          var billNo = $(this).data('bill-no');
          var balance = parseFloat($(this).data('balance')) || 0;
          rows.push({
            billId: billId,
            billNo: billNo,
            balance: balance
          });
        });

        // Populate summary table
        var tbody = '';

        function escapeHtml(value) {
          return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
        }

        $.each(rows, function(i, r) {
          var safeBillNo = escapeHtml(r.billNo);
          var safeBalance = r.balance.toFixed(2);
          tbody += '<tr>' +
            '<td>' + (i + 1) + '</td>' +
            '<td>' + safeBillNo +
            '<input type="hidden" name="bulk_bill_ids[]" value="' + r.billId + '">' +
            '<input type="hidden" name="bulk_bill_nos[]" value="' + safeBillNo + '">' +
            '</td>' +
            '<td>' + safeBalance + '</td>' +
            '<td><input type="number" class="form-control form-control-sm modal-pay-amount" name="bulk_amounts[]" value="' + safeBalance + '" min="0" max="' + safeBalance + '" data-max="' + safeBalance + '" step="0.01" required></td>' +
            '</tr>';
        });

        $('#bulkPaySummaryBody').html(tbody);
        recalculateBulkTotal();

        $('#bulkPaymentModal').modal('show');
      });

      // Prevent double-submit
      $('#bulkPayForm').on('submit', function(e) {
        var total = recalculateBulkTotal();
        if (total <= 0) {
          e.preventDefault();
          alert('Please enter a valid pay amount.');
          return false;
        }
        $('#bulkPaySubmitBtn').prop('disabled', true).text('Processing...');
      });
    });
  </script>






  <!-- Datatable -->
  <script src="./vendor/datatables/js/jquery.dataTables.min.js"></script>
  <script src="./js/plugins-init/datatables.init.js"></script>
  <script src="./js/plugins-init/datatables.init.js"></script>


  <script src="./vendor/select2/js/select2.full.min.js"></script>
  <script src="./js/plugins-init/select2-init.js"></script>


  <!-- End custom js for this page-->
</body>

</html>