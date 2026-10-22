<?php
require_once(__DIR__ . '/model/session_config.php');
if (!isset($_SESSION['loginid'])) {
    // echo 'here';exit;
    echo "<script>window.location='index.php';</script>";
    exit;
}

?>


<link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
<link rel="stylesheet" href="icofont/icofont.min.css">
<!--<link rel="stylesheet" href="css/app.css">-->
<style>
    .bootstrap-select .btn-light {
        background-color: white !important;
    }

    .content-body .container-fluid {
        margin-top: 50px;
    }

    .dropdown-menu {

        overflow-y: auto;
    }

    .dropdown-menu-right {

        transform: translate3d(-60px, -13px, 0px) !important;
    }

    .select2-container--default .select2-selection--single {
        border: 1px solid #000000 !important;
    }
</style>

<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="dashboard.php" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="images/Nexenstial TM.jpg" alt="" height="50">
                    </span>
                    <span class="logo-lg">
                        <img src="images/Nexenstial TM.jpg" alt="" height="50">
                    </span>
                </a>

                <a href="dashboard.php" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="images/Nexenstial TM.jpg" alt="" height="50">
                    </span>
                    <span class="logo-lg">
                        <img src="images/Nexenstial TM.jpg" alt="" height="50">
                    </span>
                </a>
            </div>


            <button type="button" class="btn btn-sm px-3 font-size-24 d-lg-none header-item" data-toggle="collapse"
                data-target="#topnav-menu-content">
                <i class="ri-menu-2-line align-middle mr-2"></i>
            </button>


            <div class="dropdown dropdown-mega d-none d-lg-block ml-2">

                <div class="dropdown-menu ">
                    <ul class="list-unstyled ">
                        <li>
                            <a href="javascript:void(0);">Lightbox</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">Range Slider</a>
                        </li>
                    </ul>

                </div>
            </div>


        </div>
        <div class="h4 text-light text-left d-none d-lg-block"><?= $_SESSION['college_name'] ?></div>
        <div class="d-flex">






            <div class="dropdown d-none d-lg-inline-block ml-1">
                <button type="button" class="btn header-item noti-icon waves-effect" data-toggle="fullscreen">
                    <i class="ri-fullscreen-line mr-2"></i>
                </button>
            </div>


            <div class="dropdown d-inline-block user-dropdown">
                <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="rounded-circle header-profile-user" src="assets/avatar-2.jpg" alt="Header Avatar">
                    <span class="d-none d-xl-inline-block ml-1"><?php echo $_SESSION['name'] ?></span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block mr-2"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <!-- item-->
                    <!--    <form action="staff-profile.php" method="post">-->
                    <!--<input type="hidden" name="id" value="<?= $_SESSION['id'] ?>"><button type="submit" class="dropdown-item"><i class="ri-user-line align-middle mr-1 mr-2"></i> Profile</button></form>-->
                    <?php
                    if ($_SESSION['role'] == 'Admin') {
                        echo "<a class='dropdown-item' href='db_backup.php'><i class='icofont-database mr-1 mr-2'></i>Database Backup</a>";
                    }
                    ?>
                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#changepwd"><i
                            class="ri-wallet-2-line align-middle mr-1 mr-2"></i>Change Password</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="logout.php"><i
                            class="ri-shut-down-line align-middle mr-1 text-danger mr-2"></i> Logout</a>
                </div>
            </div>



        </div>
    </div>


</header>


<div class="topnav" style="
    margin-top: 50px;
">
    <div class="container-fluid">
        <nav class="navbar navbar-light navbar-expand-lg topnav-menu">

            <div class="collapse navbar-collapse" id="topnav-menu-content">
                <ul class="navbar-nav d-flex flex-wrap justify-content-around">



                    <?php
                    if ($_SESSION['role'] == 'Admin') {
                    ?>
                        <style>
                            .form-control {
                                text-transform: uppercase;
                                border: 1px solid #000000 !important;
                            }

                            .content-body {
                                text-transform: uppercase;
                            }

                            .content-body .no-uppercase {
                                text-transform: none;
                            }
                        </style>
                        <li class="nav-item"><a class="nav-link" href="./dashboard.php" aria-expanded="false"><i
                                    class="icofont-dashboard mr-2"></i><span class="nav-text">Dashboard</span></a>

                        </li>


                        <li class="nav-item"><a class="nav-link" href="./master.php" aria-expanded="false"><i
                                    class="icofont-learn mr-2"></i><span class="nav-text">Master</span></a></li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false"
                                role="button" data-toggle="dropdown" aria-haspopup="true"><i
                                    class="icofont-certificate-alt-1 mr-2"></i><span class="nav-text">ABHA </span></a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="./createAabha.php">ABHA</a>
                                <a class="dropdown-item" href="./careContextLink.php">Care Linking</a>
                                <a class="dropdown-item" href="./fetchHealthRecords.php">Fetch Records</a>
                                <a class="dropdown-item" href="./abha_reports.php">ABHA Report</a>
                            </div>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps"
                                role="button" data-toggle="dropdown" aria-haspopup="true"><i
                                    class="icofont-user-alt-1 mr-2"></i><span class="nav-text">Out Patient </span></a>
                            <div class="dropdown-menu  " aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./opregistration.php">OP Registration</a>
                                <a class="dropdown-item" href="./opfollowup.php">OP Follow Up</a>
                                <a class="dropdown-item" href="./oppatient.php">Patient List</a>
                                <a class="dropdown-item" href="./patient_history.php">Patient History</a>
                                <a class="dropdown-item" href="./bulk_registration.php">Bulk OPD / IPD Upload</a>
                            </div>
                        </li>




                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps"
                                role="button" data-toggle="dropdown" aria-haspopup="true"><i
                                    class="icofont-crutch mr-2"></i><span class="nav-text">IN Patient</span></a>
                            <div class="dropdown-menu  " aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./ipregistration.php">IP Registration</a>
                                <a class="dropdown-item" href="./bulk_registration.php">Bulk OPD / IPD Upload</a>
                                <a class="dropdown-item" href="./ippatient.php">IP Patient List</a>
                                <a class="dropdown-item" href="./ippatient.php?id=3">IP LAB</a>
                                <a class="dropdown-item" href="./ippatient.php?id=4">IP Treatment</a>
                                <a class="dropdown-item" href="./transfer_bed.php">Transfer Bed</a>
                                <a class="dropdown-item" href="./discharge.php">Discharge</a>
                                <a class="dropdown-item" href="./dischargelist.php">Discharge List</a>


                            </div>



                        </li>
                        <li class="nav-item  d-block d-md-none"><a class="nav-link" href="./discharge.php"
                                aria-expanded="false"><i class="icofont-dashboard mr-2"></i><span
                                    class="nav-text">Discharge</span></a>

                        </li>


                        <!--<li class="nav-item dropdown">-->
                        <!--    <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps"-->
                        <!--        role="button" data-toggle="dropdown" aria-haspopup="true"><i-->
                        <!--            class="icofont-crutch mr-2"></i><span class="nav-text">Radiology </span></a>-->
                        <!--    <div class="dropdown-menu  " aria-labelledby="topnav-uielement">-->
                        <!--<a class="dropdown-item" href="./radiology_test.php">Radiology Test</a>-->
                        <!--<a class="dropdown-item" href="./view_radiology_test.php">View Radiology Test</a>-->
                        <!--        <a class="dropdown-item" href="./radiology_op_patients.php">OP Patients</a>-->
                        <!--        <a class="dropdown-item" href="./radiology_ip_patients.php">IP Patients</a>-->
                        <!--        <a class="dropdown-item" href="./radiology_report.php">Radiology Report</a>-->





                        <!--    </div>-->



                        <!--</li>-->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false"
                                role="button" data-toggle="dropdown" aria-haspopup="true"><i
                                    class="icofont-file-document mr-2"></i><span class="nav-text">Procurement </span></a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="./procurement_dashboard.php"><i class="ri-dashboard-line mr-1"></i> Dashboard &amp; Approvals</a>
                                <a class="dropdown-item" href="./procurement_requisition.php"><i class="ri-file-list-line mr-1"></i> Purchase Requisition</a>
                                <a class="dropdown-item" href="./tender_list.php"><i class="ri-auction-line mr-1"></i> Tenders</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps"
                                role="button" data-toggle="dropdown" aria-haspopup="true"><i
                                    class="icofont-blood-drop mr-2"></i><span class="nav-text">Blood Bank </span></a>
                            <div class="dropdown-menu  " aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./add_blood_donor.php"><i class="ri-user-add-line mr-1"></i> Add Donor</a>
                                <a class="dropdown-item" href="./blood_donor_list.php"><i class="ri-team-line mr-1"></i> Donor List</a>
                                <a class="dropdown-item" href="./blood_donor_pending_re_evaluation.php"><i class="ri-refresh-line mr-1"></i> Pending Re-Evaluation</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="./blood_collection.php"><i class="ri-syringe-line mr-1"></i> Blood Collection</a>
                                <a class="dropdown-item" href="./blood_screening.php"><i class="ri-flask-line mr-1"></i> Blood Screening (TTI)</a>
                                <a class="dropdown-item" href="./blood_component.php"><i class="ri-test-tube-line mr-1"></i> Blood Component</a>
                                <a class="dropdown-item" href="./blood_issue.php"><i class="ri-arrow-right-circle-line mr-1"></i> Blood Issue</a>
                                <a class="dropdown-item" href="./blood_stock.php"><i class="ri-dashboard-line mr-1"></i> Blood Stock</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">

                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps"
                                role="button" data-toggle="dropdown" aria-haspopup="true">
                                <i class="icon icon-layout-25"></i>
                                <span class="nav-text">Store Module</span>
                            </a>
                            <div class="dropdown-menu  " aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="add_store_category.php">Category Master</a>
                                <a class="dropdown-item" href="add_store_unit.php">Unit Master</a>
                                <a class="dropdown-item" href="add_store_supplier.php">Supplier Master</a>
                                <a class="dropdown-item" href="add_store_item.php">Item Master</a>
                                <a class="dropdown-item" href="add_sub_store.php">Sub Store Master</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="add_store_po.php">Add Purchase Order</a>
                                <a class="dropdown-item" href="list_store_po.php">Purchase Order List</a>
                                <a class="dropdown-item" href="add_store_grn.php">Add GRN</a>
                                <a class="dropdown-item" href="list_store_grn.php">GRN List</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="add_store_invoice.php">Add Invoice</a>
                                <a class="dropdown-item" href="list_store_invoice.php">Invoice List</a>
                                <a class="dropdown-item" href="add_store_payment.php">Add Payment</a>
                                <a class="dropdown-item" href="list_store_payment.php">Payment List</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="add_store_issue.php">Issue to Department</a>
                                <a class="dropdown-item" href="store_issue_list.php">Issue List</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="./add_store_adjustment.php">Add Adjustment</a>
                                <a class="dropdown-item" href="./list_store_adjustments.php">Adjustment List</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="store_stock_report.php">Stock Report</a>
                                <a class="dropdown-item" href="store_transaction_report.php">Purchase/Issue Reports</a>
                                <a class="dropdown-item" href="supplier_ledger_report.php">Supplier Ledger</a>
                            </div>
                        </li>
                        <!-- <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-cathlab"
                            role="button" data-toggle="dropdown" aria-haspopup="true">
                            <i class="icofont-hospital mr-2"></i>
                            <span class="nav-text">CathLab</span>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-cathlab">
                            <a class="dropdown-item" href="add_cathlab_store_item.php">Item Master</a>
                            <a class="dropdown-item" href="cathlab_store_dashboard.php">Dashboard</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="list_cathlab_grn.php">GRN</a>
                             <a class="dropdown-item" href="list_cathlab_procedure.php">Procedure List</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="cathlab_stock_report.php">Stock Report</a>
                            <a class="dropdown-item" href="cathlab_cost_vs_revenue.php">Cost vs Revenue</a>
                            <a class="dropdown-item" href="cathlab_expiry_alerts.php">Expiry Alerts</a>
                        </div>
                    </li> -->

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps"
                                role="button" data-toggle="dropdown" aria-haspopup="true"><i
                                    class="icofont-test-tube mr-2"></i><span class="nav-text">Lab Master</span></a>
                            <div class="dropdown-menu  " aria-labelledby="topnav-uielement">
                                <!--<a class="dropdown-item" href="../lab_test_group.php">Add Test Group</a>-->
                                <a class="dropdown-item" href="./lab_test_group.php">Add Test </a>
                                <a class="dropdown-item" href="./view-test-list.php">Test List </a>
                                <a class="dropdown-item" href="./packageList.php">Packages </a>
                                <a class="dropdown-item" href="./referenceCenter.php">Ref Center</a>
                                <a class="dropdown-item" href="./outSourceLab.php">Outsource Lab</a>
                                <a class="dropdown-item" href="./eSignatures.php">E-signature</a>
                                <a class="dropdown-item" href="./view_users.php">View Users</a>
                                <a class="dropdown-item" href="./lab_department.php">Lab Department</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps"
                                role="button" data-toggle="dropdown" aria-haspopup="true"><i
                                    class="ri-microscope-line mr-2"></i><span class="nav-text">Lab</span></a>
                            <div class="dropdown-menu  " aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./sample_collection.php">Sample Collection</a>
                                <a class="dropdown-item" href="./sample_dispatch.php">Sample Dispatch</a>
                                <a class="dropdown-item" href="./sample_acknowledge.php">Sample Acknowledge</a>
                                <!--<a class="dropdown-item" href="./sample_status.php">Sample Status</a>-->
                                <a class="dropdown-item" href="./lab_result.php">Lab Result </a>
                                <a class="dropdown-item" href="./result_finalization.php">Result Finalization </a>

                                <a class="dropdown-item" href="./lab_result_print.php">Lab Result Print </a>
                            </div>



                        </li>
                        <!--<li class="nav-item dropdown">-->
                        <!--                   <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps" role="button" data-toggle="dropdown" aria-haspopup="true"><i-->
                        <!--            class="icon icon-chart-bar-33 mr-2"></i><span class="nav-text">Pharmacy</span></a>-->
                        <!--   <div class="dropdown-menu  "-->
                        <!--                        aria-labelledby="topnav-uielement">-->
                        <!--             <div class=" dropdown">-->
                        <!--                   <a class="dropdown-item " href="#" id="topnav-auth"-->
                        <!--                                role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icofont-users-alt-3 mr-2"></i>Manufacturers<div class="arrow-down"></div></a>-->
                        <!--   <div class="dropdown-menu  "-->
                        <!--                        aria-labelledby="topnav-uielement">-->
                        <!--    <a class="dropdown-item" href="./add_manufacturer.php">Add Manufacturers</a>-->
                        <!--        <a class="dropdown-item" href="./view_manufacturer.php">View Manufacturers</a>-->

                        <!--        <a class="dropdown-item" href="./manufacturer_ledger.php">Manufacturers Ledger</a>-->

                        <!--    </div></div>-->
                        <!--    <div class=" dropdown">-->
                        <!--                   <a class="dropdown-item " href="#" id="topnav-auth" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icofont-pills mr-2"></i>Medicine<div class="arrow-down"></div></a>-->
                        <!--   <div class="dropdown-menu  "                                            aria-labelledby="topnav-uielement">-->
                        <!--    <a class="dropdown-item" href="./add_category.php">Add Category</a>-->
                        <!--        <a class="dropdown-item" href="./add_units.php">Add Units</a>-->

                        <!--        <a class="dropdown-item" href="./add_types.php">Add Types</a>-->
                        <!--        <a class="dropdown-item" href="./add_leaf.php">Leaf Setting</a>-->
                        <!--        <a class="dropdown-item" href="./add_medicines.php">Add Medicines</a>-->
                        <!--        <a class="dropdown-item" href="./view_medicines.php">View Medicines</a>-->

                        <!--    </div>-->
                        <!--    </div>-->
                        <!--    <div class=" dropdown">-->
                        <!--                   <a class="dropdown-item " href="#" id="topnav-auth"-->
                        <!--                                role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icofont-cart mr-2"></i>Purchase<div class="arrow-down"></div></a>-->
                        <!--   <div class="dropdown-menu  "-->
                        <!--                        aria-labelledby="topnav-uielement">-->
                        <!--    <a class="dropdown-item" href="./add_purchase.php">Add Purchase</a>-->
                        <!--        <a class="dropdown-item" href="./view_purchase.php">Purchase List</a>-->

                        <!--    </div></div>-->
                        <!--    <div class=" dropdown">-->

                        <!--    <a class="dropdown-item" href="./opd_pharma.php">OPD Patient</a>-->
                        <!--            <a class="dropdown-item" href="./phbilling.php">IPD Patient</a>-->
                        <!--            <a class="dropdown-item" href="./directsale.php">Walkin Patient</a>-->
                        <!--            <a class="dropdown-item" href="./invoice_list.php">Invoice List</a>-->


                        <!--    </div>-->
                        <!--    </div>-->
                        <!--</li>-->






                        <!--<li class="nav-item dropdown">-->
                        <!--                               <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps" role="button" data-toggle="dropdown" aria-haspopup="true"><i class="icofont-bill-alt mr-2"></i><span class="nav-text">Billing</span></a>-->
                        <!--               <div class="dropdown-menu  "-->
                        <!--                                    aria-labelledby="topnav-uielement">-->
                        <!--                    <a class="dropdown-item" href="./ippatient_billing.php">IP Billing</a>-->

                        <!--                </div>-->
                        <!--            </li>-->
                        <!--             <li class="nav-item">-->
                        <!--                               <a class="nav-link" href="#" aria-expanded="false" id="topnav-aps" role="button" data-toggle="dropdown" aria-haspopup="true"><i class="icofont-ui-camera mr-2"></i><span class="nav-text">CCTV</span></a>-->

                        <!--            </li>-->

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps"
                                role="button" data-toggle="dropdown" aria-haspopup="true"><i
                                    class="icofont-prescription mr-2"></i><span class="nav-text">Reports</span></a>
                            <div class="dropdown-menu  " aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./daily_reports.php">Daily Reports</a>
                                <a class="dropdown-item" href="./periodical_reports.php">Periodical Reports</a>
                                <!--<a class="dropdown-item" href="./ipd_mis_reports.php">IPD MIS Reports</a>-->
                                <!--<a class="dropdown-item" href="./adt_mis_reports.php">ADT MIS Reports</a>-->
                                <a class="dropdown-item" href="./mis_reports.php">MIS Reports</a>
                                <a class="dropdown-item" href="./opreports.php">Out Patient</a>
                                <a class="dropdown-item" href="./ipreports.php">IN Patient</a>
                                <a class="dropdown-item" href="./manage_bed_occupancy.php">Monthly Bed Occupancy Report</a>

                                <a class="dropdown-item" href="./manage_monthly_opd.php">Monthly OPD Report</a>
                                <a class="dropdown-item" href="./manage_monthly_ipd.php">Monthly IPD Report</a>
                                <a class="dropdown-item" href="./manage_daily_opd.php">Daily OPD Report</a>
                                <a class="dropdown-item" href="./manage_daily_ipd.php">Daily IPD Report</a>
                                <a class="dropdown-item" href="./assesment_report.php">Assesment Report</a>
                                <a class="dropdown-item" href="./otreports.php">Operation Theater</a>
                                <!--<a class="dropdown-item" href="./lab_report_monthly.php">Monthly Lab Reports</a> -->
                                <a class="dropdown-item" href="./generated.php">Generated Reports</a>
                                <a class="dropdown-item" href="./lab_reports.php">Lab Reports</a>
                                <a class="dropdown-item" href="./bed_occupancy.php">Bed Occupancy</a>
                                <a class="dropdown-item" href="./abha_reports.php">ABHA Registration Report</a>


                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps"
                                role="button" data-toggle="dropdown" aria-haspopup="true"><i
                                    class="icofont-settings-alt mr-2"></i><span class="nav-text">Settings</span></a>
                            <div class="dropdown-menu  " aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./edit-timetable.php">Staff TimeTable</a>
                                <!--<a class="dropdown-item" href="./add-user.php">Users</a>-->
                                <a class="dropdown-item" href="./view_users.php">View Users</a>


                            </div>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="billing/dashboard.php" aria-expanded="false"><i
                                    class="icofont-money-bag mr-2"></i><span class="nav-text">Billing</span></a></li>


                    <?php } else if ($_SESSION['role'] == 'Phlebotomy') {
                    ?>
                        <style>
                            .form-control {
                                text-transform: uppercase;
                                border: 1px solid #000000 !important;
                            }

                            .content-body {
                                text-transform: uppercase;
                            }
                        </style>
                        <li class="nav-item"><a class="nav-link" href="./dashboard.php" aria-expanded="false"><i
                                    class="icofont-dashboard mr-2"></i><span class="nav-text">Dashboard</span></a>

                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./sample_collection.php" aria-expanded="false"><i
                                    class="icofont-blood-test"></i><span class="nav-text">Sample Collection</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./sample_dispatch.php" aria-expanded="false"><i
                                    class="icofont-test-tube"></i><span class="nav-text">Sample Dispatch</span></a>
                        </li>
                        <li class="nav-item"> <a class="nav-link" href="./sample_acknowledge.php" aria-expanded="false"><i
                                    class="icofont-check-circled"></i><span class="nav-text">Sample Acknowledge</span></a>
                        </li>
                    <?php
                    } else if ($_SESSION['role'] == 'MRD') {
                    ?>
                        <style>
                            .form-control {
                                text-transform: uppercase;
                                border: 1px solid #000000 !important;
                            }

                            .content-body {
                                text-transform: uppercase;
                            }
                        </style>
                        <li class="nav-item"><a class="nav-link" href="./dashboard.php" aria-expanded="false"><i
                                    class="icofont-dashboard mr-2"></i><span class="nav-text">Dashboard</span></a>

                        </li>


                        <li class="nav-item"><a class="nav-link" href="./master.php" aria-expanded="false"><i
                                    class="icofont-learn mr-2"></i><span class="nav-text">Master</span></a></li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false"
                                role="button" data-toggle="dropdown" aria-haspopup="true"><i
                                    class="icofont-certificate-alt-1 mr-2"></i><span class="nav-text">ABHA </span></a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="./createAabha.php">ABHA</a>
                                <a class="dropdown-item" href="./careContextLink.php">Care Linking</a>
                                <a class="dropdown-item" href="./fetchHealthRecords.php">Fetch Records</a>
                                <a class="dropdown-item" href="./abha_reports.php">ABHA Report</a>
                            </div>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps"
                                role="button" data-toggle="dropdown" aria-haspopup="true"><i
                                    class="icofont-user-alt-1 mr-2"></i><span class="nav-text">Out Patient </span></a>
                            <div class="dropdown-menu  " aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./opregistration.php">OP Registration</a>
                                <a class="dropdown-item" href="./opfollowup.php">OP Follow Up</a>
                                <a class="dropdown-item" href="./oppatient.php">Patient List</a>
                                <a class="dropdown-item" href="./patient_history.php">Patient History</a>
                            </div>
                        </li>




                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps"
                                role="button" data-toggle="dropdown" aria-haspopup="true"><i
                                    class="icofont-crutch mr-2"></i><span class="nav-text">IN Patient</span></a>
                            <div class="dropdown-menu  " aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./ipregistration.php">IP Registration</a>
                                <a class="dropdown-item" href="./ippatient.php">IP Patient List</a>
                                <a class="dropdown-item" href="./ippatient.php?id=3">IP LAB</a>
                                <a class="dropdown-item" href="./ippatient.php?id=4">IP Treatment</a>
                                <a class="dropdown-item" href="./transfer_bed.php">Transfer Bed</a>
                                <a class="dropdown-item" href="./discharge.php">Discharge</a>
                                <a class="dropdown-item" href="./dischargelist.php">Discharge List</a>


                            </div>



                        </li>
                        <li class="nav-item  d-block d-md-none"><a class="nav-link" href="./discharge.php"
                                aria-expanded="false"><i class="icofont-dashboard mr-2"></i><span
                                    class="nav-text">Discharge</span></a>

                        </li>


                        <!--<li class="nav-item dropdown">-->
                        <!--    <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps"-->
                        <!--        role="button" data-toggle="dropdown" aria-haspopup="true"><i-->
                        <!--            class="icofont-crutch mr-2"></i><span class="nav-text">Radiology </span></a>-->
                        <!--    <div class="dropdown-menu  " aria-labelledby="topnav-uielement">-->
                        <!--<a class="dropdown-item" href="./radiology_test.php">Radiology Test</a>-->
                        <!--<a class="dropdown-item" href="./view_radiology_test.php">View Radiology Test</a>-->
                        <!--        <a class="dropdown-item" href="./radiology_op_patients.php">OP Patients</a>-->
                        <!--        <a class="dropdown-item" href="./radiology_ip_patients.php">IP Patients</a>-->
                        <!--        <a class="dropdown-item" href="./radiology_report.php">Radiology Report</a>-->





                        <!--    </div>-->



                        <!--</li>-->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps"
                                role="button" data-toggle="dropdown" aria-haspopup="true"><i
                                    class="icofont-test-tube mr-2"></i><span class="nav-text">Lab Master</span></a>
                            <div class="dropdown-menu  " aria-labelledby="topnav-uielement">
                                <!--<a class="dropdown-item" href="../lab_test_group.php">Add Test Group</a>-->
                                <a class="dropdown-item" href="./lab_test_group.php">Add Test </a>
                                <a class="dropdown-item" href="./view-test-list.php">Test List </a>
                                <a class="dropdown-item" href="./packageList.php">Packages </a>
                                <a class="dropdown-item" href="./referenceCenter.php">Ref Center</a>
                                <a class="dropdown-item" href="./outSourceLab.php">Outsource Lab</a>
                                <a class="dropdown-item" href="./eSignatures.php">E-signature</a>
                                <a class="dropdown-item" href="./view_users.php">View Users</a>
                                <a class="dropdown-item" href="./lab_department.php">Lab Department</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps"
                                role="button" data-toggle="dropdown" aria-haspopup="true"><i
                                    class="ri-microscope-line mr-2"></i><span class="nav-text">Lab</span></a>
                            <div class="dropdown-menu  " aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./sample_collection.php">Sample Collection</a>
                                <a class="dropdown-item" href="./sample_dispatch.php">Sample Dispatch</a>
                                <a class="dropdown-item" href="./sample_acknowledge.php">Sample Acknowledge</a>
                                <!--<a class="dropdown-item" href="./sample_status.php">Sample Status</a>-->
                                <a class="dropdown-item" href="./lab_result.php">Lab Result </a>
                                <a class="dropdown-item" href="./result_finalization.php">Result Finalization </a>

                                <a class="dropdown-item" href="./lab_result_print.php">Lab Result Print </a>
                            </div>



                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps"
                                role="button" data-toggle="dropdown" aria-haspopup="true"><i
                                    class="icofont-baby mr-2"></i><span class="nav-text">Medical Records</span></a>
                            <div class="dropdown-menu  " aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./birth_record.php">Birth Record</a>
                                <a class="dropdown-item" href="./death_record.php">Death Record</a>

                            </div>
                        </li>


                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps"
                                role="button" data-toggle="dropdown" aria-haspopup="true"><i
                                    class="icofont-prescription mr-2"></i><span class="nav-text">Reports</span></a>
                            <div class="dropdown-menu  " aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./daily_reports.php">Daily Reports</a>
                                <a class="dropdown-item" href="./periodical_reports.php">Periodical Reports</a>
                                <!--<a class="dropdown-item" href="./ipd_mis_reports.php">IPD MIS Reports</a>-->
                                <!--<a class="dropdown-item" href="./adt_mis_reports.php">ADT MIS Reports</a>-->
                                <a class="dropdown-item" href="./mis_reports.php">MIS Reports</a>
                                <a class="dropdown-item" href="./opreports.php">Out Patient</a>
                                <a class="dropdown-item" href="./ipreports.php">IN Patient</a>
                                <a class="dropdown-item" href="./manage_bed_occupancy.php">Monthly Bed Occupancy Report</a>

                                <a class="dropdown-item" href="./manage_monthly_opd.php">Monthly OPD Report</a>
                                <a class="dropdown-item" href="./manage_monthly_ipd.php">Monthly IPD Report</a>
                                <a class="dropdown-item" href="./manage_daily_opd.php">Daily OPD Report</a>
                                <a class="dropdown-item" href="./manage_daily_ipd.php">Daily IPD Report</a>
                                <a class="dropdown-item" href="./assesment_report.php">Assesment Report</a>
                                <a class="dropdown-item" href="./otreports.php">Operation Theater</a>
                                <!--<a class="dropdown-item" href="./lab_report_monthly.php">Monthly Lab Reports</a> -->
                                <a class="dropdown-item" href="./generated.php">Generated Reports</a>
                                <a class="dropdown-item" href="./lab_reports.php">Lab Reports</a>
                                <a class="dropdown-item" href="./bed_occupancy.php">Bed Occupancy</a>
                                <a class="dropdown-item" href="./abha_reports.php">ABHA Registration Report</a>


                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps"
                                role="button" data-toggle="dropdown" aria-haspopup="true"><i
                                    class="icofont-settings-alt mr-2"></i><span class="nav-text">Settings</span></a>
                            <div class="dropdown-menu  " aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./edit-timetable.php">Staff TimeTable</a>
                                <!--<a class="dropdown-item" href="./add-user.php">Users</a>-->
                                <a class="dropdown-item" href="./view_users.php">View Users</a>


                            </div>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="billing/dashboard.php" aria-expanded="false"><i
                                    class="icofont-money-bag mr-2"></i><span class="nav-text">Billing</span></a></li>

                    <?php
                    } else if ($_SESSION['role'] == 'IT') {
                    ?>
                        <style>
                            .form-control {
                                text-transform: uppercase;
                                border: 1px solid #000000 !important;
                            }

                            .content-body {
                                text-transform: uppercase;
                            }
                        </style>
                        <li class="nav-item"><a class="nav-link" href="./dashboard.php" aria-expanded="false"><i
                                    class="icofont-dashboard mr-2"></i><span class="nav-text">Dashboard</span></a>

                        </li>


                        <li class="nav-item"><a class="nav-link" href="./master.php" aria-expanded="false"><i
                                    class="icofont-learn mr-2"></i><span class="nav-text">Master</span></a></li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false"
                                role="button" data-toggle="dropdown" aria-haspopup="true"><i
                                    class="icofont-certificate-alt-1 mr-2"></i><span class="nav-text">ABHA </span></a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="./createAabha.php">ABHA</a>
                                <a class="dropdown-item" href="./careContextLink.php">Care Linking</a>
                                <a class="dropdown-item" href="./fetchHealthRecords.php">Fetch Records</a>
                                <a class="dropdown-item" href="./abha_reports.php">ABHA Report</a>
                            </div>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps"
                                role="button" data-toggle="dropdown" aria-haspopup="true"><i
                                    class="icofont-user-alt-1 mr-2"></i><span class="nav-text">Out Patient </span></a>
                            <div class="dropdown-menu  " aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./opregistration.php">OP Registration</a>
                                <a class="dropdown-item" href="./opfollowup.php">OP Follow Up</a>
                                <a class="dropdown-item" href="./oppatient.php">Patient List</a>
                                <a class="dropdown-item" href="./patient_history.php">Patient History</a>
                            </div>
                        </li>




                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps"
                                role="button" data-toggle="dropdown" aria-haspopup="true"><i
                                    class="icofont-crutch mr-2"></i><span class="nav-text">IN Patient</span></a>
                            <div class="dropdown-menu  " aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./ipregistration.php">IP Registration</a>
                                <a class="dropdown-item" href="./ippatient.php">IP Patient List</a>
                                <a class="dropdown-item" href="./ippatient.php?id=3">IP LAB</a>
                                <a class="dropdown-item" href="./ippatient.php?id=4">IP Treatment</a>
                                <a class="dropdown-item" href="./transfer_bed.php">Transfer Bed</a>
                                <a class="dropdown-item" href="./discharge.php">Discharge</a>
                                <a class="dropdown-item" href="./dischargelist.php">Discharge List</a>


                            </div>



                        </li>
                        <li class="nav-item  d-block d-md-none"><a class="nav-link" href="./discharge.php"
                                aria-expanded="false"><i class="icofont-dashboard mr-2"></i><span
                                    class="nav-text">Discharge</span></a>

                        </li>


                        <!--<li class="nav-item dropdown">-->
                        <!--    <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps"-->
                        <!--        role="button" data-toggle="dropdown" aria-haspopup="true"><i-->
                        <!--            class="icofont-crutch mr-2"></i><span class="nav-text">Radiology </span></a>-->
                        <!--    <div class="dropdown-menu  " aria-labelledby="topnav-uielement">-->
                        <!--<a class="dropdown-item" href="./radiology_test.php">Radiology Test</a>-->
                        <!--<a class="dropdown-item" href="./view_radiology_test.php">View Radiology Test</a>-->
                        <!--        <a class="dropdown-item" href="./radiology_op_patients.php">OP Patients</a>-->
                        <!--        <a class="dropdown-item" href="./radiology_ip_patients.php">IP Patients</a>-->
                        <!--        <a class="dropdown-item" href="./radiology_report.php">Radiology Report</a>-->





                        <!--    </div>-->



                        <!--</li>-->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps"
                                role="button" data-toggle="dropdown" aria-haspopup="true"><i
                                    class="icofont-test-tube mr-2"></i><span class="nav-text">Lab Master</span></a>
                            <div class="dropdown-menu  " aria-labelledby="topnav-uielement">
                                <!--<a class="dropdown-item" href="../lab_test_group.php">Add Test Group</a>-->
                                <a class="dropdown-item" href="./lab_test_group.php">Add Test </a>
                                <a class="dropdown-item" href="./view-test-list.php">Test List </a>
                                <a class="dropdown-item" href="./packageList.php">Packages </a>
                                <a class="dropdown-item" href="./referenceCenter.php">Ref Center</a>
                                <a class="dropdown-item" href="./outSourceLab.php">Outsource Lab</a>
                                <a class="dropdown-item" href="./eSignatures.php">E-signature</a>
                                <a class="dropdown-item" href="./view_users.php">View Users</a>
                                <a class="dropdown-item" href="./lab_department.php">Lab Department</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps"
                                role="button" data-toggle="dropdown" aria-haspopup="true"><i
                                    class="ri-microscope-line mr-2"></i><span class="nav-text">Lab</span></a>
                            <div class="dropdown-menu  " aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./sample_collection.php">Sample Collection</a>
                                <a class="dropdown-item" href="./sample_dispatch.php">Sample Dispatch</a>
                                <a class="dropdown-item" href="./sample_acknowledge.php">Sample Acknowledge</a>
                                <!--<a class="dropdown-item" href="./sample_status.php">Sample Status</a>-->
                                <a class="dropdown-item" href="./lab_result.php">Lab Result </a>
                                <a class="dropdown-item" href="./result_finalization.php">Result Finalization </a>

                                <a class="dropdown-item" href="./lab_result_print.php">Lab Result Print </a>
                            </div>



                        </li>



                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps"
                                role="button" data-toggle="dropdown" aria-haspopup="true"><i
                                    class="icofont-prescription mr-2"></i><span class="nav-text">Reports</span></a>
                            <div class="dropdown-menu  " aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./daily_reports.php">Daily Reports</a>
                                <a class="dropdown-item" href="./periodical_reports.php">Periodical Reports</a>
                                <!--<a class="dropdown-item" href="./ipd_mis_reports.php">IPD MIS Reports</a>-->
                                <!--<a class="dropdown-item" href="./adt_mis_reports.php">ADT MIS Reports</a>-->
                                <a class="dropdown-item" href="./mis_reports.php">MIS Reports</a>
                                <a class="dropdown-item" href="./opreports.php">Out Patient</a>
                                <a class="dropdown-item" href="./ipreports.php">IN Patient</a>
                                <a class="dropdown-item" href="./manage_bed_occupancy.php">Monthly Bed Occupancy Report</a>

                                <a class="dropdown-item" href="./manage_monthly_opd.php">Monthly OPD Report</a>
                                <a class="dropdown-item" href="./manage_monthly_ipd.php">Monthly IPD Report</a>
                                <a class="dropdown-item" href="./manage_daily_opd.php">Daily OPD Report</a>
                                <a class="dropdown-item" href="./manage_daily_ipd.php">Daily IPD Report</a>
                                <a class="dropdown-item" href="./assesment_report.php">Assesment Report</a>
                                <a class="dropdown-item" href="./otreports.php">Operation Theater</a>
                                <!--<a class="dropdown-item" href="./lab_report_monthly.php">Monthly Lab Reports</a> -->
                                <a class="dropdown-item" href="./generated.php">Generated Reports</a>
                                <a class="dropdown-item" href="./lab_reports.php">Lab Reports</a>
                                <a class="dropdown-item" href="./bed_occupancy.php">Bed Occupancy</a>
                                <a class="dropdown-item" href="./abha_reports.php">ABHA Registration Report</a>


                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps"
                                role="button" data-toggle="dropdown" aria-haspopup="true"><i
                                    class="icofont-settings-alt mr-2"></i><span class="nav-text">Settings</span></a>
                            <div class="dropdown-menu  " aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./edit-timetable.php">Staff TimeTable</a>
                                <!--<a class="dropdown-item" href="./add-user.php">Users</a>-->
                                <a class="dropdown-item" href="./view_users.php">View Users</a>


                            </div>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="billing/dashboard.php" aria-expanded="false"><i
                                    class="icofont-money-bag mr-2"></i><span class="nav-text">Billing</span></a></li>

                    <?php
                    } else if ($_SESSION['role'] == 'Doctor') { ?>
                        <style>
                            .form-control {
                                text-transform: uppercase;
                                border: 1px solid #000000 !important;
                            }

                            .content-body {
                                text-transform: uppercase;
                            }
                        </style>
                        <li class="nav-item"><a class="nav-link" href="./dashboard.php" aria-expanded="false"><i
                                    class="icofont-dashboard mr-2"></i><span class="nav-text">Dashboard</span></a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps"
                                role="button" data-toggle="dropdown" aria-haspopup="true"><i
                                    class="icofont-user-alt-1 mr-2"></i><span class="nav-text">Out Patient </span></a>
                            <div class="dropdown-menu  " aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./oppatient.php">OP Patient List</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps"
                                role="button" data-toggle="dropdown" aria-haspopup="true"><i
                                    class="icofont-crutch mr-2"></i><span class="nav-text">IP Patient</span></a>
                            <div class="dropdown-menu  " aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./ippatient.php">Treatment</a>
                                <a class="dropdown-item" href="./transfer_bed.php">Transfer Bed</a>
                                <a class="dropdown-item" href="./discharge.php">Discharge</a>
                                <a class="dropdown-item" href="./dischargelist.php">Discharge List</a>
                                <a class="dropdown-item" href="./anaesthesia.php">Anaesthesia</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps"
                                role="button" data-toggle="dropdown" aria-haspopup="true"><i
                                    class="icofont-prescription mr-2"></i><span class="nav-text">Reports</span></a>
                            <div class="dropdown-menu  " aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./opreports.php">Out Patient</a>
                                <a class="dropdown-item" href="./ipreports.php">IN Patient</a>
                                <a class="dropdown-item" href="./lab_reports.php">Laboratory</a>
                                <!--<a class="dropdown-item" href="./lab.php">Pharmacy</a>-->
                                <a class="dropdown-item" href="./otreports.php">Operation Theater</a>
                            </div>
                        </li>
                    <?php } else if ($_SESSION['role'] == 'Nursing') { ?>
                        <style>
                            .form-control {
                                text-transform: uppercase;
                                border: 1px solid #000000 !important;
                            }

                            .content-body {
                                text-transform: uppercase;
                            }
                        </style>
                        <li class="nav-item"><a class="nav-link" href="./dashboard.php" aria-expanded="false"><i
                                    class="icofont-dashboard mr-2"></i><span class="nav-text">Dashboard</span></a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps"
                                role="button" data-toggle="dropdown" aria-haspopup="true"><i
                                    class="icofont-user-alt-1 mr-2"></i><span class="nav-text">Out Patient </span></a>
                            <div class="dropdown-menu  " aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./oppatient.php">OP Patient List</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps"
                                role="button" data-toggle="dropdown" aria-haspopup="true"><i
                                    class="icofont-crutch mr-2"></i><span class="nav-text">IP Patient</span></a>
                            <div class="dropdown-menu  " aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./ippatient.php?id=4">Treatment</a>
                                <a class="dropdown-item" href="./transfer_bed.php">Transfer Bed</a>
                                <a class="dropdown-item" href="./discharge.php">Discharge</a>
                                <a class="dropdown-item" href="./dischargelist.php">Discharge List</a>
                                <a class="dropdown-item" href="./anaesthesia.php">Anaesthesia</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps"
                                role="button" data-toggle="dropdown" aria-haspopup="true"><i
                                    class="icofont-prescription mr-2"></i><span class="nav-text">Reports</span></a>
                            <div class="dropdown-menu  " aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./opreports.php">Out Patient</a>
                                <a class="dropdown-item" href="./ipreports.php">IN Patient</a>
                                <a class="dropdown-item" href="./lab_reports.php">Laboratory</a>
                                <!--<a class="dropdown-item" href="./lab.php">Pharmacy</a>-->
                                <a class="dropdown-item" href="./otreports.php">Operation Theater</a>
                            </div>
                        </li>
                    <?php } else if ($_SESSION['role'] == 'Lab' || $_SESSION['role'] == 'Lab Admin') { ?>
                        <li class="nav-item"><a class="nav-link" href="./dashboard.php" aria-expanded="false"><i
                                    class="icofont-dashboard mr-2"></i><span class="nav-text">Dashboard</span></a>

                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps"
                                role="button" data-toggle="dropdown" aria-haspopup="true"><i
                                    class="icofont-laboratory mr-2"></i><span class="nav-text">Lab Billing</span></a>
                            <div class="dropdown-menu  " aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="billing/pathology_billing.php">Lab Billing</a>
                                <a class="dropdown-item" href="billing/bill_list.php?type=Pathology">Lab Bill List</a>
                                <a class="dropdown-item" href="billing/bill_list.php">Lab Bill Report</a>





                            </div>



                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps"
                                role="button" data-toggle="dropdown" aria-haspopup="true"><i
                                    class="icofont-user-alt-1 mr-2"></i><span class="nav-text">Patient </span></a>
                            <div class="dropdown-menu  " aria-labelledby="topnav-uielement">


                                <a class="dropdown-item" href="./oppatient.php">OP Patient List</a>
                                <a class="dropdown-item" href="./ippatient.php?id=3">IP Patient List</a>

                            </div>
                        </li>

                        <?php if ($_SESSION['role'] == 'Lab Admin' || $_SESSION['loginid'] == 24) { ?>

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps"
                                    role="button" data-toggle="dropdown" aria-haspopup="true"><i
                                        class="icofont-test-tube mr-2"></i><span class="nav-text">Lab Master</span></a>
                                <div class="dropdown-menu  " aria-labelledby="topnav-uielement">
                                    <!--<a class="dropdown-item" href="../lab_test_group.php">Add Test Group</a>-->
                                    <a class="dropdown-item" href="./lab_test_group.php">Add Test </a>
                                    <a class="dropdown-item" href="./view-test-list.php">Test List </a>
                                    <a class="dropdown-item" href="./packageList.php">Packages </a>
                                    <a class="dropdown-item" href="./referenceCenter.php">Ref Center</a>
                                    <a class="dropdown-item" href="./outSourceLab.php">Outsource Lab</a>
                                    <a class="dropdown-item" href="./eSignatures.php">E-signature</a>
                                    <a class="dropdown-item" href="./view_users.php">View Users</a>
                                    <a class="dropdown-item" href="./lab_department.php">Lab Department</a>
                                </div>
                            </li>
                        <?php } ?>
                        <li class="nav-item"><a class="nav-link" href="./lab_orders.php" aria-expanded="false"><i
                                    class="icofont-dashboard mr-2"></i><span class="nav-text">Lab Order</span></a>

                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps"
                                role="button" data-toggle="dropdown" aria-haspopup="true"><i
                                    class="icofont-crutch mr-2"></i><span class="nav-text">Phlebotomy</span></a>
                            <div class="dropdown-menu  " aria-labelledby="topnav-uielement">


                                <a class="dropdown-item" href="./sample_collection.php">Sample Collection</a>
                                <a class="dropdown-item" href="./sample_dispatch.php">Sample Dispatch</a>
                            </div>



                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps"
                                role="button" data-toggle="dropdown" aria-haspopup="true"><i
                                    class="ri-microscope-line mr-2"></i><span class="nav-text">Lab</span></a>
                            <div class="dropdown-menu  " aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./sample_acknowledge.php">Sample Acknowledge</a>
                                <!--<a class="dropdown-item" href="./sample_status.php">Sample Status</a>-->
                                <a class="dropdown-item" href="./lab_result.php">Lab Result </a>
                                <a class="dropdown-item" href="./result_finalization.php">Result Finalization </a>

                                <a class="dropdown-item" href="./lab_result_print.php">Lab Result Print </a>
                            </div>



                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps"
                                role="button" data-toggle="dropdown" aria-haspopup="true"><i
                                    class="icofont-user-alt-1 mr-2"></i><span class="nav-text">Lab Report </span></a>

                            <div class="dropdown-menu  " aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./lab_reports.php">Laboratory</a>




                            </div>
                        </li>
                    <?php } else if ($_SESSION['role'] == 'Radiology') { ?>
                        <style>
                            .form-control {
                                text-transform: uppercase;
                                border: 1px solid #000000 !important;
                            }

                            .content-body {
                                text-transform: uppercase;
                            }
                        </style>
                        <li class="nav-item"><a class="nav-link" href="./dashboard.php" aria-expanded="false"><i
                                    class="icofont-dashboard mr-2"></i><span class="nav-text">Dashboard</span></a>

                        </li>


                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps"
                                role="button" data-toggle="dropdown" aria-haspopup="true"><i
                                    class="icofont-user-alt-1 mr-2"></i><span class="nav-text">Patient </span></a>
                            <div class="dropdown-menu  " aria-labelledby="topnav-uielement">

                                <a class="dropdown-item" href="./opregistration.php">OP Registration</a>
                                <a class="dropdown-item" href="./oppatient.php">OP Patient List</a>
                                <a class="dropdown-item" href="./ippatient.php?id=3">IP Patient List</a>

                            </div>
                        </li>



                        <!--    <li class="nav-item dropdown">-->
                        <!--                   <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps" role="button" data-toggle="dropdown" aria-haspopup="true"><i class="icofont-user-alt-1 mr-2"></i><span class="nav-text">Lab </span></a>-->
                        <!--   <div class="dropdown-menu  "-->
                        <!--                        aria-labelledby="topnav-uielement">-->

                        <!--      <a class="dropdown-item" href="./lab_test_group.php">Add Test Group</a>-->
                        <!--        <a class="dropdown-item" href="./lab_test_name.php">Add Test Name</a>-->
                        <!--        <a class="dropdown-item" href="./view-test-list.php">Test List </a>-->
                        <!--    </div>-->
                        <!--</li>-->


                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps"
                                role="button" data-toggle="dropdown" aria-haspopup="true"><i
                                    class="icofont-user-alt-1 mr-2"></i><span class="nav-text"> Result </span></a>

                            <div class="dropdown-menu  " aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./oplab_result.php">OP Radiology</a>
                                <a class="dropdown-item" href="./iplab_result.php">IP Radiology</a>
                            </div>

                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps"
                                role="button" data-toggle="dropdown" aria-haspopup="true"><i
                                    class="icofont-user-alt-1 mr-2"></i><span class="nav-text">Report </span></a>

                            <div class="dropdown-menu  " aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./lab_reports.php">Radiology</a>




                            </div>
                        </li>
                    <?php } else if ($_SESSION['role'] == 'Receptionist') { ?>
                        <style>
                            .form-control {
                                text-transform: uppercase;
                                border: 1px solid #000000 !important;
                            }

                            .content-body {
                                text-transform: uppercase;
                            }
                        </style>
                        <li class="nav-item"><a class="nav-link" href="./dashboard.php" aria-expanded="false"><i
                                    class="icofont-dashboard mr-2"></i><span class="nav-text">Dashboard</span></a>

                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false"
                                role="button" data-toggle="dropdown" aria-haspopup="true"><i
                                    class="icofont-certificate-alt-1 mr-2"></i><span class="nav-text">ABHA </span></a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="./createAabha.php">ABHA</a>
                                <a class="dropdown-item" href="./careContextLink.php">Care Linking</a>
                                <a class="dropdown-item" href="./fetchHealthRecords.php">Fetch Records</a>
                                <a class="dropdown-item" href="./abha_reports.php">ABHA Report</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps"
                                role="button" data-toggle="dropdown" aria-haspopup="true"><i
                                    class="icofont-user-alt-1 mr-2"></i><span class="nav-text">Patient List</span></a>
                            <div class="dropdown-menu  " aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="billing/patient_list.php">OPD List</a>
                                <a class="dropdown-item" href="billing/ippatient_list.php">IPD List</a>
                            </div>
                        </li>

                        <li class="nav-item"><a class="nav-link" href="./patient_history.php" aria-expanded="false"><i
                                    class="icofont-learn mr-2"></i><span class="nav-text">Patient History</span></a></li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps"
                                role="button" data-toggle="dropdown" aria-haspopup="true"><i
                                    class="icofont-user-alt-1 mr-2"></i><span class="nav-text">Out Patient </span></a>
                            <div class="dropdown-menu  " aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./opregistration.php">OP Registration</a>
                                <a class="dropdown-item" href="./oppatient.php">OP Patient List</a>
                                <a class="dropdown-item" href="billing/addOP_billing.php">OP Billing</a>
                                <a class="dropdown-item" href="billing/bill_list.php?type=OP">OP Bill List</a>
                            </div>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps"
                                role="button" data-toggle="dropdown" aria-haspopup="true"><i
                                    class="icofont-crutch mr-2"></i><span class="nav-text">IN Patient</span></a>
                            <div class="dropdown-menu  " aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./ipregistration.php">IP Registration</a>
                                <a class="dropdown-item" href="./ippatient.php">IP Patient List</a>
                                <a class="dropdown-item" href="./ippatient.php?id=4">Add OT </a>
                                <a class="dropdown-item" href="./transfer_bed.php">Transfer Bed</a>
                                <a class="dropdown-item" href="./discharge.php">Discharge</a>
                                <a class="dropdown-item" href="./dischargelist.php">Discharge List</a>
                                <a class="dropdown-item" href="billing/ip_billing.php">IP Billing</a>
                                <a class="dropdown-item" href="billing/ipbill_list.php?type=IP">IP Bill List</a>
                                <a class="dropdown-item" href="billing/advance_list.php">IP Advance List</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps"
                                role="button" data-toggle="dropdown" aria-haspopup="true"><i
                                    class="icofont-xray mr-2"></i><span class="nav-text">Billing</span></a>
                            <div class="dropdown-menu  " aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="billing/radiology_billing.php">Radiology Billing</a>
                                <a class="dropdown-item" href="billing/bill_list.php?type=Radiology">Radiology Bill List</a>
                                <a class="dropdown-item" href="billing/pathology_billing.php">Lab Billing</a>
                                <a class="dropdown-item" href="billing/bill_list.php?type=Pathology">Lab Bill List</a>
                                <a class="dropdown-item" href="billing/dialysis_billing.php">Dialysis Bill</a>
                                <a class="dropdown-item" href="billing/bill_list.php?type=Dialysis">Dialysis Bill List</a>
                                <a class="dropdown-item" href="billing/blood_billing.php">Blood Centre Bill</a>
                                <a class="dropdown-item" href="billing/bill_list.php?type=Blood Bank">Blood Centre Bill List</a>

                            </div>
                        </li>
                        <li class="nav-item dropdown"><a class="nav-link dropdown-toggle arrow-none" href="#"
                                aria-expanded="false" id="topnav-aps" role="button" data-toggle="dropdown"
                                aria-haspopup="true"><i class="icofont-prescription mr-2"></i><span
                                    class="nav-text">Reports</span></a>
                            <div class="dropdown-menu  " aria-labelledby="topnav-uielement">

                                <a class="dropdown-item" href="./opreports.php">Out Patient</a>
                                <a class="dropdown-item" href="./ipreports.php">IN Patient</a>
                                <a class="dropdown-item" href="./otreports.php">Operation Theater</a>
                                <a class="dropdown-item" href="./op_reports.php">OPD Billing Report</a>
                                <a class="dropdown-item" href="billing/ip_reports.php">IPD Billing Report</a>
                                <a class="dropdown-item" href="billing/pathoreport.php">Lab Billing Report</a>
                                <a class="dropdown-item" href="billing/radio_reports.php">Radiology Billing Report</a>
                                <a class="dropdown-item" href="billing/radio_reports1.php">Radiology-2 Billing Report</a>
                                <a class="dropdown-item" href="billing/dialysis_reports.php">Dialysis Billing Report</a>
                                <a class="dropdown-item" href="billing/blood_reports.php">Blood Centre Billing Report</a>
                                <a class="dropdown-item" href="billing/user_wise_collection.php">User Collection Report</a>
                                <a class="dropdown-item" href="billing/doctor_service_list.php">Doctor Ledger Report</a>
                            </div>
                        </li>


                    <?php } else if ($_SESSION['role'] == 'Store') { ?>
                        <style>
                            .form-control {
                                text-transform: uppercase;
                                border: 1px solid #000000 !important;
                            }

                            .content-body {
                                text-transform: uppercase;
                            }
                        </style>
                        <li class="nav-item"><a class="nav-link" href="./store_dashboard.php" aria-expanded="false"><i
                                    class="icofont-dashboard mr-2"></i><span class="nav-text">Dashboard</span></a>

                        </li>

                        <!-- Master Data Section -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps"
                                role="button" data-toggle="dropdown" aria-haspopup="true"><i
                                    class="icofont-learn mr-2"></i><span class="nav-text">Master Data</span></a>
                            <div class="dropdown-menu  " aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./add_store_category.php">Category</a>
                                <a class="dropdown-item" href="./add_store_unit.php">Unit</a>
                                <a class="dropdown-item" href="./add_store_supplier.php">Supplier</a>
                                <a class="dropdown-item" href="./add_store_item.php">Item</a>
                                <a class="dropdown-item" href="./add_sub_store.php">Sub Store Master</a>
                            </div>
                        </li>

                        <!-- Procurement Section -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps"
                                role="button" data-toggle="dropdown" aria-haspopup="true"><i
                                    class="icofont-cart mr-2"></i><span class="nav-text">Procurement</span></a>
                            <div class="dropdown-menu  " aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./add_store_po.php">Add PO</a>
                                <a class="dropdown-item" href="./list_store_po.php">PO List</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="./add_store_grn.php">Add GRN</a>
                                <a class="dropdown-item" href="./list_store_grn.php">GRN List</a>
                            </div>
                        </li>

                        <!-- Financial Section -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps"
                                role="button" data-toggle="dropdown" aria-haspopup="true"><i
                                    class="icofont-money-bag mr-2"></i><span class="nav-text">Financial</span></a>
                            <div class="dropdown-menu  " aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./add_store_invoice.php">Add Invoice</a>
                                <a class="dropdown-item" href="./store_invoice_list.php">Invoice List</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="./add_store_payment.php">Add Payment</a>
                                <a class="dropdown-item" href="./store_payment_list.php">Payment List</a>
                            </div>
                        </li>

                        <!-- Inventory Section -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps"
                                role="button" data-toggle="dropdown" aria-haspopup="true"><i
                                    class="icofont-package mr-2"></i><span class="nav-text">Inventory</span></a>
                            <div class="dropdown-menu  " aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./add_store_issue.php">Issue to Department</a>
                                <a class="dropdown-item" href="./store_issue_list.php">Issue List</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="./add_store_adjustment.php">Add Adjustment</a>
                                <a class="dropdown-item" href="./list_store_adjustments.php">Adjustment List</a>
                            </div>
                        </li>

                        <!-- Reports Section -->
                        <li class="nav-item dropdown"><a class="nav-link dropdown-toggle arrow-none" href="#"
                                aria-expanded="false" id="topnav-aps" role="button" data-toggle="dropdown"
                                aria-haspopup="true"><i class="icofont-prescription mr-2"></i><span
                                    class="nav-text">Reports</span></a>
                            <div class="dropdown-menu  " aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./store_stock_report.php">Stock Report</a>
                                <a class="dropdown-item" href="./store_transaction_report.php">Purchase/Issue Reports</a>
                                <a class="dropdown-item" href="./supplier_ledger_report.php">Supplier Ledger</a>
                            </div>
                        </li>



                    <?php } else if ($_SESSION['role'] == 'Pharmacist') { ?>
                        <style>
                            .form-control {
                                text-transform: uppercase;
                                border: 1px solid #000000 !important;
                            }

                            .content-body {
                                text-transform: uppercase;
                            }
                        </style>
                        <li class="nav-item"><a class="nav-link" href="./dashboard.php" aria-expanded="false"><i
                                    class="icofont-dashboard mr-2"></i><span class="nav-text">Dashboard</span></a>

                        </li>


                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps"
                                role="button" data-toggle="dropdown" aria-haspopup="true"><i class="icofont-cart mr-2"></i>
                                <span class="nav-text"> Indent </span></a>
                            <div class="dropdown-menu  " aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./add_indent.php">Add Indent</a>
                                <a class="dropdown-item" href="./view_indent.php">Indent List</a>
                                <a class="dropdown-item" href="./view_purchase.php">Indent Received</a>


                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps"
                                role="button" data-toggle="dropdown" aria-haspopup="true"><i
                                    class="icofont-retweet mr-2"></i> <span class="nav-text"> Return </span></a>
                            <div class="dropdown-menu  " aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./add_returns.php">Add Return</a>
                                <a class="dropdown-item" href="./invoice_return_list.php">Invoice Return List</a>
                                <a class="dropdown-item" href="./manufacturer_invoice_list.php">Store Return List</a>



                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps"
                                role="button" data-toggle="dropdown" aria-haspopup="true"><i
                                    class="icofont-patient-file mr-2"></i> <span class="nav-text"> Sales </span></a>
                            <div class="dropdown-menu  " aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./opd_pharma.php">OPD Patient</a>
                                <a class="dropdown-item" href="./phbilling.php">IPD Patient</a>
                                <a class="dropdown-item" href="./walkin_pharma_billing.php">Walkin Patient</a>
                                <a class="dropdown-item" href="./invoice_list.php">Invoice List</a>
                            </div>
                        </li>



                        <li class="nav-item dropdown"><a class="nav-link dropdown-toggle arrow-none" href="#"
                                aria-expanded="false" id="topnav-aps" role="button" data-toggle="dropdown"
                                aria-haspopup="true"><i class="icofont-prescription mr-2"></i><span
                                    class="nav-text">Reports</span></a>
                            <div class="dropdown-menu  " aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./sales_report.php">Sales Report</a>
                                <a class="dropdown-item" href="./purchase_report.php">Purchase Report</a>
                            </div>
                        </li>



                    <?php } else if ($_SESSION['role'] == 'OPD Register') { ?>
                        <style>
                            .form-control {
                                text-transform: uppercase;
                                border: 1px solid #000000 !important;
                            }

                            .content-body {
                                text-transform: uppercase;
                            }
                        </style>
                        <li class="nav-item"><a class="nav-link" href="./dashboard.php" aria-expanded="false"><i
                                    class="icofont-dashboard mr-2"></i><span class="nav-text">Dashboard</span></a>

                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps"
                                role="button" data-toggle="dropdown" aria-haspopup="true"><i
                                    class="icofont-user-alt-1 mr-2"></i><span class="nav-text">Out Patient </span></a>
                            <div class="dropdown-menu  " aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./opregistration.php">OP Registration</a>
                                <a class="dropdown-item" href="./opfollowup.php">OP Follow Up</a>

                                <a class="dropdown-item" href="./oppatient.php">OP Patient List</a>
                            </div>
                        </li>


                        <li class="nav-item dropdown"><a class="nav-link dropdown-toggle arrow-none" href="#"
                                aria-expanded="false" id="topnav-aps" role="button" data-toggle="dropdown"
                                aria-haspopup="true"><i class="icofont-prescription mr-2"></i><span
                                    class="nav-text">Reports</span></a>
                            <div class="dropdown-menu  " aria-labelledby="topnav-uielement">

                                <a class="dropdown-item" href="./opreports.php">Out Patient</a>
                                <a class="dropdown-item" href="./ipreports.php">IN Patient</a>

                            </div>
                        </li>



                    <?php } elseif ($_SESSION['role'] == 'Operation Theater') {
                    ?>
                        <style>
                            .form-control {
                                text-transform: uppercase;
                                border: 1px solid #000000 !important;
                            }

                            .content-body {
                                text-transform: uppercase;
                            }
                        </style>
                        <li class="nav-item"><a class="nav-link" href="./dashboard.php" aria-expanded="false"><i
                                    class="icofont-dashboard mr-2"></i><span class="nav-text">Dashboard</span></a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps"
                                role="button" data-toggle="dropdown" aria-haspopup="true"><i
                                    class="icofont-icu mr-2"></i><span class="nav-text">Operation Theater </span></a>
                            <div class="dropdown-menu  " aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./oppatient.php">OP Patient </a>

                                <a class="dropdown-item" href="./ippatient.php?id=4">IP Patient </a>
                            </div>
                        </li>
                        <li class="nav-item dropdown"><a class="nav-link dropdown-toggle arrow-none" href="#"
                                aria-expanded="false" id="topnav-aps" role="button" data-toggle="dropdown"
                                aria-haspopup="true"><i class="icofont-prescription mr-2"></i><span
                                    class="nav-text">Reports</span></a>
                            <div class="dropdown-menu  " aria-labelledby="topnav-uielement">

                                <a class="dropdown-item" href="./opreports.php">Out Patient</a>
                                <a class="dropdown-item" href="./ipreports.php">IN Patient</a>
                                <a class="dropdown-item" href="./otreports.php">Operation Theater</a>
                            </div>
                        </li>
                    <?php
                    } else if ($_SESSION['role'] == 'Generated') { ?>
                        <style>
                            .form-control {
                                text-transform: uppercase;
                                border: 1px solid #000000 !important;
                            }

                            .content-body {
                                text-transform: uppercase;
                            }
                        </style>
                        <li class="nav-item"><a class="nav-link" href="./dashboard.php" aria-expanded="false"><i
                                    class="icofont-dashboard mr-2"></i><span class="nav-text">Dashboard</span></a>

                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps"
                                role="button" data-toggle="dropdown" aria-haspopup="true"><i
                                    class="icofont-user-alt-1 mr-2"></i><span class="nav-text">Patient List</span></a>
                            <div class="dropdown-menu  " aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./oppatient.php">OP Patient List</a>
                                <a class="dropdown-item" href="./ippatient.php">IP Patient List</a>
                            </div>
                        </li>


                        <li class="nav-item dropdown"><a class="nav-link dropdown-toggle arrow-none" href="#"
                                aria-expanded="false" id="topnav-aps" role="button" data-toggle="dropdown"
                                aria-haspopup="true"><i class="icofont-prescription mr-2"></i><span
                                    class="nav-text">Reports</span></a>
                            <div class="dropdown-menu  " aria-labelledby="topnav-uielement">

                                <a class="dropdown-item" href="./generated.php">Generated Patient Report</a>
                                <a class="dropdown-item" href="./lab_reports.php">Lab Reports</a>

                            </div>
                        </li>



                    <?php } else if ($_SESSION['role'] == 'Reports') {
                    ?>
                        <style>
                            .form-control {
                                text-transform: uppercase;
                                border: 1px solid #000000 !important;
                            }

                            .content-body {
                                text-transform: uppercase;
                            }
                        </style>
                        <li class="nav-item"><a class="nav-link" href="./dashboard.php" aria-expanded="false"><i
                                    class="icofont-dashboard mr-2"></i><span class="nav-text">Dashboard</span></a>

                        </li>




                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps"
                                role="button" data-toggle="dropdown" aria-haspopup="true"><i
                                    class="icofont-prescription mr-2"></i><span class="nav-text">Reports</span></a>
                            <div class="dropdown-menu  " aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./daily_reports.php">Daily Reports</a>
                                <a class="dropdown-item" href="./periodical_reports.php">Periodical Reports</a>
                                <!--<a class="dropdown-item" href="./ipd_mis_reports.php">IPD MIS Reports</a>-->
                                <!--<a class="dropdown-item" href="./adt_mis_reports.php">ADT MIS Reports</a>-->
                                <a class="dropdown-item" href="./mis_reports.php">MIS Reports</a>
                                <a class="dropdown-item" href="./opreports.php">Out Patient</a>
                                <a class="dropdown-item" href="./ipreports.php">IN Patient</a>
                                <a class="dropdown-item" href="./manage_bed_occupancy.php">Monthly Bed Occupancy Report</a>

                                <a class="dropdown-item" href="./manage_monthly_opd.php">Monthly OPD Report</a>
                                <a class="dropdown-item" href="./manage_monthly_ipd.php">Monthly IPD Report</a>
                                <a class="dropdown-item" href="./manage_daily_opd.php">Daily OPD Report</a>
                                <a class="dropdown-item" href="./manage_daily_ipd.php">Daily IPD Report</a>
                                <a class="dropdown-item" href="./assesment_report.php">Assesment Report</a>
                                <a class="dropdown-item" href="./otreports.php">Operation Theater</a>

                                <a class="dropdown-item" href="./bed_occupancy.php">Bed Occupancy</a>
                                <a class="dropdown-item" href="./abha_reports.php">ABHA Registration Report</a>


                            </div>
                        </li>

                    <?php } else if ($_SESSION['role'] == 'Billing') { ?>
                        <style>
                            .form-control {
                                text-transform: uppercase;
                                border: 1px solid #000000 !important;
                            }

                            .content-body {
                                text-transform: uppercase;
                            }
                        </style>
                        <script>
                            window.location = "billing/dashboard.php"
                        </script>
                    <?php } ?>
                </ul>
            </div>

        </nav>
    </div>
</div>

<div class="modal fade" id="changepwd" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Change Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!--<form action="" method="post">-->
                <div class="row">
                    <div class="col-md-12">

                        <div class="form-group">
                            <label for="currentpwd"> Current Password <small class="text-danger">*</small></label>
                            <input type="password" class="form-control" name="currentpwd" id="currentpwd"
                                placeholder="Enter Current Password" required>
                        </div>
                    </div>
                    <div class="col-md-12">

                        <div class="form-group">
                            <label for="newpwd"> New Password <small class="text-danger">*</small></label>
                            <input type="password" class="form-control" name="newpwd" id="newpwd"
                                placeholder="Enter New Password" required>
                        </div>
                    </div>
                    <div class="col-md-12">

                        <div class="form-group">
                            <label for="confirmpwd"> Confirm Password <small class="text-danger">*</small></label>
                            <input type="password" class="form-control" name="confirmpwd" id="confirmpwd"
                                placeholder="Confirm Password" required>
                        </div>
                    </div>

                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" name="submit" onclick="changePassword()">Save</button>
                <!--</form>-->

            </div>
        </div>
    </div>


    <script>
        function changePassword() {
            var opwd = document.getElementById('currentpwd').value;
            var npwd = document.getElementById('newpwd').value;
            var cpwd = document.getElementById('confirmpwd').value;
            if (opwd == '' || npwd == '' || cpwd == '') {
                alert('Please enter all fields');
                return false;
            }
            if (npwd == cpwd) {
                $.ajax({
                    url: 'ajax.php',
                    type: 'POST', // Send post data
                    data: 'opwd=' + opwd + '&npwd=' + npwd + '&page=change_pwd',
                    async: false,
                    success: function(s) {
                        // alert(s);
                        json_events = s;
                        alert(json_events);
                        window.location.reload()

                    }
                });
            } else {
                alert("password and confirm password not matching");
            }
        }

        document.addEventListener('keydown', function(e) {
            const tag = e.target.tagName.toLowerCase();
            const type = e.target.type?.toLowerCase();

            if (e.key === 'Enter') {
                if ((tag === 'input' || tag === 'select' || tag === 'textarea') && type !== 'submit') {
                    e.preventDefault();

                    // Get all focusable fields
                    const focusable = Array.from(document.querySelectorAll('input, select, textarea, button'))
                        .filter(el => !el.disabled && el.type !== 'hidden' && el.offsetParent !== null);

                    const index = focusable.indexOf(e.target);
                    if (index > -1 && index < focusable.length - 1) {
                        focusable[index + 1].focus();
                    }
                }
                // Don't prevent default on buttons to allow form submission
            }
        });
        document.addEventListener("keydown", function(e) {
            if (document.activeElement.type === "number") {
                if (e.key === "ArrowUp" || e.key === "ArrowDown") {
                    e.preventDefault();
                }
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    <script>
        // Prevent mouse/trackpad scroll from changing number input values
        document.addEventListener('wheel', function(e) {
            if (document.activeElement && document.activeElement.type === 'number') {
                document.activeElement.blur();
            }
        }, {
            passive: false
        });
    </script>


</div>