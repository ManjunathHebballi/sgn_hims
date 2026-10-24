<?php
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");
include('./model/ipClass.php');
$newOP = new addIP;
if (isset($_REQUEST['op_from'])) {
    $op_from = $_REQUEST['op_from'];
} else {
    $op_from = date("Y-m-d");
}
if (isset($_REQUEST['op_to'])) {
    $op_to = $_REQUEST['op_to'];
} else {
    $op_to = date("Y-m-d");
}

if (isset($_REQUEST['search'])) {
    $search = $_REQUEST['search'];
} else {
    $search = '';
}
$dept = $newOP->selectIPreg1_search($op_from, $op_to, $search);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>IP Patient</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <!-- Datatable -->
    <link href="./vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">

    <link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/libs/datatables.net-select-bs4/css/select.bootstrap4.min.css" rel="stylesheet" type="text/css" />


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
        <div class="content-body text-uppercase">
            <!-- row -->


            <div class="modal fade" id="advance_amount" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" style="max-width: 35%;" role="document">
                    <div class="modal-content" width="150%">
                        <div class="modal-header">


                            <h5 class="modal-title" id="exampleModalLabel">Add Advance Amount of <span class="text-uppercase text-danger" id="ipd_name"></span> ( <span class="text-uppercase text-danger" id="ipd_dr"></span> )</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="billing/controller/processBilling.php" method="post">

                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-7">
                                            <div class="form-group">
                                                <label for="amount">Amount</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1">Rs.</span>
                                                    </div>
                                                    <div>
                                                        <input class="form-control" placeholder="Enter Amount" type="number" name="advance_amount" required id="advance_amount">
                                                    </div>
                                                    <input class="form-control" type="hidden" name="ipd" required id="ipd">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Payment Mode <small class="text-danger"> *</small></label>

                                                <select required class="form-control" name="payment_mode" id="payment_mode">
                                                    <option value="">Payment Mode</option>
                                                    <option value="Cash">Cash</option>
                                                    <option value="UPI">UPI</option>
                                                    <option value="Card">Card</option>
                                                    <option value="Cheque">Cheque</option>
                                                    <option valie="Bank Transfer">Bank Transfer</option>

                                                </select>

                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="ref_note">Ref Note</label>

                                                <textarea class="form-control" placeholder="Enter Ref Note" type="text" name="ref_note" id="ref_note"></textarea>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" name="submit" value="advance">Save </button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>

            <div class="container-fluid">

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <!--<div class="row mb-3">-->
                                    <div class="col-md-12">
                                        <h4 class="text-uppercase">IP PATIENT</h4>
                                        <!--</div>-->
                                    </div>
                                </div>
                                <form action="" method="POST">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"> From Date </label>
                                                <input type="date" class="form-control" name="op_from" id="op_from" required value="<?= $op_from ?>">

                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"> To Date </label>
                                                <input type="date" class="form-control" name="op_to" id="op_to" required value="<?= $op_to ?>">

                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"> Search </label>
                                                <input type="text" class="form-control" name="search" id="search" placeholder="Search by Name, mobile, IPD No, Doctor, Address,OPD No etc" value="<?= $search ?>">

                                            </div>
                                        </div>


                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-success  float-right btn-rounded" name="submit"><span><i class="bi bi-search"></i></span> Search</button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="text-uppercase">IP List</h4>

                            </div>
                            <div class="card-body">
                                <a href="print_bulk_address_graph.php?fromdate=<?= $op_from ?>&todate=<?= $op_to ?>" target="_blank" title="Print Address Graph" class="badge badge-info float-right mr-3"><i class="ri-file-list-2-line" style="font-size:15px"></i>Bulk Address Graph </a>&nbsp;
                                <div class="table-responsive">
                                    <table class="table table-striped display text-center" id="datatable-buttons4">
                                        <thead>
                                            <tr>
                                                <th>Sl NO</th>
                                                <th>COPID</th>
                                                <th>IPD No.</th>
                                                <th>Visit Date</th>
                                                <th>Name</th>
                                                <th>Age</th>
                                                <th>Gender</th>
                                                <th>Address</th>
                                                <th>Mobile</th>
                                                <th>Diagnosis</th>
                                                <th>Department</th>
                                                <th>Doctor</th>
                                                <th>Bed</th>
                                                <th>Remarks</th>
                                                <th>Admitted By</th>
                                                <th>Action</th>

                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            <?php $sn = 1;
                                            $row = mysqli_num_rows($dept);
                                            while ($res = mysqli_fetch_assoc($dept)) { ?>
                                                <tr <?php if ($res['discharge'] == 1) {
                                                        echo "class='table-success'";
                                                    } ?>>
                                                    <td><?= $sn ?></td>
                                                    <td><?= $res['opd_no'] ?></td>
                                                    <td><?= $res['ipd_no'] ?></td>
                                                    <td><?= date("d.m.Y", strtotime($res['admission_date'])) ?></td>
                                                    <td><?= $res['name'] ?></td>
                                                    <td><?= $res['age'] ?></td>
                                                    <td><?= $res['gender'] ?></td>
                                                    <td><?= $res['address'] ?></td>
                                                    <td><?= $res['mobile'] ?></td>
                                                    <td><?= $res['diagnosis'] ?></td>
                                                    <td><?= $res['dept_name'] ?></td>
                                                    <td><?= $res['doctor'] ?></td>
                                                    <td><?= $res['bed'] ?></td>
                                                    <td><?= $res['remark'] ?></td>
                                                    <td><?= $res['created_by'] ?></td>
                                                    <td>
                                                        <div class="d-flex">
                                                            <?php if ($_REQUEST['id'] == 1 && $res['discharge'] == 0) { ?>
                                                                <a href="iphistory.php?id=<?= $res['ipd_no'] ?>" title="Add Re-Entry" class="badge badge-pill badge-primary">Edit</a>&nbsp; <!--<a href="print_admission_card.php" title="Print" class="badge badge-pill badge-primary"><i class="ri-printer-line" style="font-size:15px"></i> </a>-->
                                                            <?php } elseif ($_REQUEST['id'] == 2 && $res['discharge'] == 0) { ?>
                                                                <a href="ipexam.php?id=<?= $res['ipd_no'] ?>" title="Add Re-Entry" class="badge badge-pill badge-primary">Edit</a>&nbsp; <!--<a href="print_admission_card.php" title="Print" class="badge badge-pill badge-primary"><i class="ri-printer-line" style="font-size:15px"></i> </a>-->
                                                            <?php } elseif ($_REQUEST['id'] == 3 && $res['discharge'] == 0) { ?>
                                                                <a href="iplab.php?id=<?= $res['ipd_no'] ?>" title="Add Re-Entry" class="badge badge-pill badge-primary">Edit</a>&nbsp; <!--<a href="print_admission_card.php" title="Print" class="badge badge-pill badge-primary"><i class="ri-printer-line" style="font-size:15px"></i> </a>-->
                                                            <?php } elseif ($_REQUEST['id'] == 4) { ?>
                                                                <a href="treatment.php?id=<?= $res['ipd_no'] ?>" title="Add Re-Entry" class="badge badge-pill badge-primary">Edit</a>&nbsp; <!--<a href="print_admission_card.php" title="Print" class="badge badge-pill badge-primary"><i class="ri-printer-line" style="font-size:15px"></i> </a>-->
                                                                <?php } else {

                                                                if ($_SESSION['role'] != 'Generated' && $_SESSION['role'] != 'Doctor') {
                                                                ?>

                                                                    <a href="editIPPatient.php?id=<?= $res['id'] ?>&from=<?= $op_from ?>&to=<?= $op_to ?>&search=<?= $search ?>" title="Add Re-Entry" class="badge badge-primary">Edit</a>&nbsp;
                                                                    <a href="patient_documents.php?ipd_no=<?= urlencode($res['ipd_no']) ?>" title="Patient Documents" class="badge badge-pill badge-light">Documents</a>&nbsp;
                                                                <?php }
                                                                ?>
                                                                <a href="print_admission_slip.php?id=<?= $res['id'] ?>" target="_blank" title="Print Admission Card" class="badge badge-warning">Print</a>&nbsp;
                                                                <a href="print_address_graph.php?id=<?= $res['id'] ?>" target="_blank" title="Print Address Graph" class="badge badge-pill badge-success">Address Graph</a>&nbsp;
                                                                <?php if ($res['discharge'] == 0) { ?><a href="discharge.php?id=<?= $res['ipd_no'] ?>" title="Add Re-Entry" class="badge badge-pill badge-secondary ">Discharge </a>&nbsp;<?php } ?>
                                                                    <?php if ($res['discharge'] == 0 && $res['final_bill'] == 0 && $_SESSION['role'] != 'Generated' && $_SESSION['role'] != 'Doctor') { ?><a href="billing/ip_billing.php?id=<?= $res['ipd_no'] ?>" title="Add Re-Entry" class="badge badge-pill badge-info ">Save IP Bill </a>&nbsp;<?php } ?>
                                                                        <?php if ($res['discharge'] == 0 && $res['final_bill'] == 0 && $_SESSION['role'] != 'Generated' && $_SESSION['role'] != 'Doctor') { ?><a href="transfer_bed.php?id=<?= $res['ipd_no'] ?>" title="Add Re-Entry" class="badge badge-pill badge-dark">Transfer Bed </a>&nbsp;<?php } ?>

                                                                            <?php if ($res['discharge'] == 0 && $res['final_bill'] == 0 && $_SESSION['role'] != 'Generated' && $_SESSION['role'] != 'Doctor') { ?><a type="button" data-toggle="modal" data-target="#advance_amount" onclick="modalAdvance('<?= $res['ipd_no'] ?>','<?= $res['name'] ?>')" title="Add Advance Amount" class="badge badge-pill badge-danger ">Add Advance</a><?php } ?>
                                                                        <?php } ?>
                                                        </div>


                                                    </td>

                                                </tr>
                                            <?php $sn++;
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
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


    <!-- Required datatable js -->
    <script src="assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <!-- Buttons examples -->
    <script src="assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
    <script src="assets/libs/jszip/jszip.min.js"></script>
    <script src="assets/libs/pdfmake/build/pdfmake.min.js"></script>
    <script src="assets/libs/pdfmake/build/vfs_fonts.js"></script>
    <script src="assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="assets/libs/datatables.net-buttons/js/buttons.colVis.min.js"></script>

    <script src="assets/libs/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
    <script src="assets/libs/datatables.net-select/js/dataTables.select.min.js"></script>


    <!-- Datatable init js -->
    <script src="assets/js/pages/datatables.init.js"></script>


    <script>
        $(document).ready(function() {
            $('#datatable-buttons4').DataTable({

                dom: 'Bfrtip',
                responsive: true,
                pageLength: 25,
                // lengthMenu: [0, 5, 10, 20, 50, 100, 200, 500],

                buttons: [

                    {
                        extend: 'pdfHtml5',
                        title: 'Center OP',
                        orientation: 'landscape',
                        pageSize: 'LEGAL'
                    }
                ]

            });
        });
    </script>

    <script>
        function modalAdvance(val, name) {
            document.getElementById('ipd').value = val;
            document.getElementById('ipd_dr').innerHTML = val;
            document.getElementById('ipd_name').innerHTML = name;
        }
    </script>

</body>

</html>