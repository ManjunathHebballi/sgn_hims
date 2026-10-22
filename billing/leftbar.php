<?php
require_once(__DIR__ . '/../model/session_config.php');
// session_destroy();
if (!isset($_SESSION['loginid'])) {
    echo "<script>window.location='index.php';</script>";
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

    .form-control {
        text-transform: uppercase;
        border: 1px solid #000000 !important;
    }

    .content-body {
        text-transform: uppercase;
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
                <a href="<?php if ($_SESSION['role'] == 'Accountant') { ?>dashboard.php<?php } else { ?>../dashboard.php<?php } ?>" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="images/Nexenstial TM.jpg" alt="" height="50">
                    </span>
                    <span class="logo-lg">
                        <img src="images/Nexenstial TM.jpg" alt="" height="50">
                    </span>
                </a>

                <a href="<?php if ($_SESSION['role'] == 'Accountant') { ?>dashboard.php<?php } else { ?>../dashboard.php<?php } ?>" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="images/Nexenstial TM.jpg" alt="" height="50">
                    </span>
                    <span class="logo-lg">
                        <img src="images/Nexenstial TM.jpg" alt="" height="50">
                    </span>
                </a>
            </div>


            <button type="button" class="btn btn-sm px-3 font-size-24 d-lg-none header-item" data-toggle="collapse" data-target="#topnav-menu-content">
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
                    <img class="rounded-circle header-profile-user" src="assets/avatar-2.jpg"
                        alt="Header Avatar">
                    <span class="d-none d-xl-inline-block ml-1"><?php echo $_SESSION['name'] ?></span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block mr-2"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <!-- item-->
                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#changepwd"><i class="ri-wallet-2-line align-middle mr-1 mr-2"></i>Change Password</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="../logout.php"><i class="ri-shut-down-line align-middle mr-1 text-danger mr-2"></i> Logout</a>
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



                    <?php if ($_SESSION['role'] == 'Admin' || $_SESSION['role'] == 'Accountant' || $_SESSION['role'] == 'IT' || $_SESSION['role'] == 'MRD') { ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps" role="button" data-toggle="dropdown" aria-haspopup="true"><i class="icofont-user-alt-1 mr-2"></i><span class="nav-text">Patient List</span></a>
                            <div class="dropdown-menu  "
                                aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./patient_list.php">OPD List</a>
                                <a class="dropdown-item" href="./ippatient_list.php">IPD List</a>
                            </div>
                        </li>



                        <li class="nav-item"><a class="nav-link" href="./master.php" aria-expanded="false"><i class="icofont-learn mr-2"></i><span class="nav-text">Master</span></a></li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps" role="button" data-toggle="dropdown" aria-haspopup="true"><i class="icofont-user-alt-1 mr-2"></i><span class="nav-text">OP Billing </span></a>
                            <div class="dropdown-menu  "
                                aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./addOP_billing.php">OP Billing</a>
                                <a class="dropdown-item" href="./bill_list.php?type=OP">OP Bill List</a>
                                <a class="dropdown-item" href="./op_bill_report.php">OP Billing Report</a>
                                <a class="dropdown-item" href="./op_cancel_list.php">OP Bill Cancellation</a>
                            </div>
                        </li>




                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps" role="button" data-toggle="dropdown" aria-haspopup="true"><i class="icofont-crutch mr-2"></i><span class="nav-text">IP Billing</span></a>
                            <div class="dropdown-menu  "
                                aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./ip_billing.php">IP Billing</a>
                                <a class="dropdown-item" href="./ipbill_list.php?type=IP">IP Bill List</a>
                                <a class="dropdown-item" href="./advance_list.php">IP Advance List</a>
                                <a class="dropdown-item" href="./ipbill_list.php">IP Advance Cancellation</a>
                                <a class="dropdown-item" href="./ip_bill_report.php">IP Billing Report</a>
                                <a class="dropdown-item" href="./ip_cancel_list.php">IP Bill Cancellation</a>
                            </div>



                        </li>



                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps" role="button" data-toggle="dropdown" aria-haspopup="true"><i class="icofont-xray mr-2"></i><span class="nav-text">Radio</span></a>
                            <div class="dropdown-menu  "
                                aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./radiology_billing.php">Radiology Billing</a>
                                <a class="dropdown-item" href="./bill_list.php?type=Radiology">Radiology Bill List</a>
                                <a class="dropdown-item" href="./bill_list.php">Radiology Bill Report</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps" role="button" data-toggle="dropdown" aria-haspopup="true"><i class="icofont-laboratory mr-2"></i><span class="nav-text">Lab</span></a>
                            <div class="dropdown-menu  "
                                aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./pathology_billing.php">Lab Billing</a>
                                <a class="dropdown-item" href="./bill_list.php?type=Pathology">Lab Bill List</a>
                                <a class="dropdown-item" href="./bill_list.php">Lab Bill Report</a>





                            </div>



                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps" role="button" data-toggle="dropdown" aria-haspopup="true"><i class="icofont-blood mr-2"></i><span class="nav-text">Blood Centre</span></a>
                            <div class="dropdown-menu  "
                                aria-labelledby="topnav-uielement">

                                <a class="dropdown-item" href="./blood_billing.php">Blood Centre Bill</a>
                                <a class="dropdown-item" href="./bill_list.php?type=Blood Bank">Blood Centre Bill List</a>
                                <a class="dropdown-item" href="./bill_list.php">Blood Centre Bill Report</a>





                            </div>



                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps" role="button" data-toggle="dropdown" aria-haspopup="true"><i class="icofont-computer mr-2"></i><span class="nav-text">Dialysis</span></a>
                            <div class="dropdown-menu  "
                                aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./dialysis_billing.php">Dialysis Bill</a>
                                <a class="dropdown-item" href="./bill_list.php?type=Dialysis">Dialysis Bill List</a>
                                <a class="dropdown-item" href="./bill_list.php">Dialysis Bill Report</a>





                            </div>



                            <!--</li>-->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps" role="button" data-toggle="dropdown" aria-haspopup="true"><i class="icofont-file-alt mr-2"></i><span class="nav-text">Claim</span></a>
                            <div class="dropdown-menu  "
                                aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./claim_billing.php">Claim Bill</a>
                                <a class="dropdown-item" href="./claimbill_list.php?type=Claim">Claim Bill List</a>





                            </div>



                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps" role="button" data-toggle="dropdown" aria-haspopup="true"><i class="icofont-files-stack mr-2"></i><span class="nav-text">ESI</span></a>
                            <div class="dropdown-menu  "
                                aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./esi_billing.php">ESI Bill</a>
                                <a class="dropdown-item" href="./esibill_list.php?type=TPA">ESI Bill List</a>





                            </div>



                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps" role="button" data-toggle="dropdown" aria-haspopup="true"><i
                                    class="icofont-prescription mr-2"></i><span class="nav-text">Reports</span></a>
                            <div class="dropdown-menu  "
                                aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./mis_report.php">MIS Report</a>
                                <a class="dropdown-item" href="./op_reports.php">OPD Billing Report</a>
                                <a class="dropdown-item" href="./ip_reports.php">IPD Billing Report</a>
                                <a class="dropdown-item" href="./pathoreport.php">Lab Billing Report</a>
                                <a class="dropdown-item" href="./radio_reports.php">Radiology Billing Report</a>
                                <a class="dropdown-item" href="./radio_reports1.php">Radiology-2 Billing Report</a>
                                <a class="dropdown-item" href="./dialysis_reports.php">Dialysis Billing Report</a>
                                <a class="dropdown-item" href="./blood_reports.php">Blood Centre Billing Report</a>
                                <a class="dropdown-item" href="user_wise_collection.php">User Collection Report</a>
                                <a class="dropdown-item" href="doctor_revenue.php">Doctor Revenue Report</a>
                                <a class="dropdown-item" href="doctor_service_list.php">Doctor Ledger Report</a>
                                <a class="dropdown-item" href="service_wise_report.php">Service Wise Report</a>
                                <a class="dropdown-item" href="dept_wise_report.php">Department Wise Report</a>
                                <a class="dropdown-item" href="bill_cancellation_report.php">Cancelled Bill Report</a>
                                <a class="dropdown-item" href="ip_balance_report.php">Outstanding Bill Report</a>
                                <a class="dropdown-item" href="generated.php">Generated Report</a>


                            </div>
                        </li>


                    <?php } elseif ($_SESSION['role'] == 'Receptionist') {
                    ?>
                        <li class="nav-item"><a class="nav-link" href="./dashboard.php" aria-expanded="false"><i class="icofont-dashboard mr-2"></i><span class="nav-text">Dashboard</span></a></li>

                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps" role="button" data-toggle="dropdown" aria-haspopup="true"><i class="icofont-user-alt-1 mr-2"></i><span class="nav-text">Patient List</span></a>
                            <div class="dropdown-menu  "
                                aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./patient_list.php">OPD List</a>
                                <a class="dropdown-item" href="./ippatient_list.php">IPD List</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false"
                                role="button" data-toggle="dropdown" aria-haspopup="true"><i
                                    class="icofont-certificate-alt-1 mr-2"></i><span class="nav-text">ABHA </span></a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="../createAabha.php">ABHA</a>
                                <a class="dropdown-item" href="../careContextLink.php">Care Linking</a>
                                <a class="dropdown-item" href="../fetchHealthRecords.php">Fetch Records</a>
                            </div>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="../patient_history.php" aria-expanded="false"><i class="icofont-learn mr-2"></i><span class="nav-text">Patient History</span></a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps" role="button" data-toggle="dropdown" aria-haspopup="true"><i class="icofont-user-alt-1 mr-2"></i><span class="nav-text">OP Billing </span></a>
                            <div class="dropdown-menu  "
                                aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="../opregistration.php">OP Registration</a>
                                <a class="dropdown-item" href="../oppatient.php">OP Patient List</a>
                                <a class="dropdown-item" href="./addOP_billing.php">OP Billing</a>
                                <a class="dropdown-item" href="./bill_list.php?type=OP">OP Bill List</a>
                            </div>
                        </li>




                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps" role="button" data-toggle="dropdown" aria-haspopup="true"><i class="icofont-crutch mr-2"></i><span class="nav-text">IP Billing</span></a>
                            <div class="dropdown-menu  "
                                aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="../ipregistration.php">IP Registration</a>
                                <a class="dropdown-item" href="../ippatient.php">IP Patient List</a>
                                <a class="dropdown-item" href="../ippatient.php?id=4">Add OT </a>
                                <a class="dropdown-item" href="../transfer_bed.php?id=4">Transfer Bed</a>
                                <a class="dropdown-item" href="../discharge.php">Discharge</a>
                                <a class="dropdown-item" href="../dischargelist.php">Discharge List</a>
                                <a class="dropdown-item" href="./ip_billing.php">IP Billing</a>
                                <a class="dropdown-item" href="./ipbill_list.php?type=IP">IP Bill List</a>
                                <a class="dropdown-item" href="./advance_list.php">IP Advance List</a>

                            </div>



                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps" role="button" data-toggle="dropdown" aria-haspopup="true"><i class="icofont-xray mr-2"></i><span class="nav-text">Billing</span></a>
                            <div class="dropdown-menu  "
                                aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./radiology_billing.php">Radiology Billing</a>
                                <a class="dropdown-item" href="./bill_list.php?type=Radiology">Radiology Bill List</a>
                                <a class="dropdown-item" href="./pathology_billing.php">Lab Billing</a>
                                <a class="dropdown-item" href="./bill_list.php?type=Pathology">Lab Bill List</a>
                                <a class="dropdown-item" href="./dialysis_billing.php">Dialysis Bill</a>
                                <a class="dropdown-item" href="./bill_list.php?type=Dialysis">Dialysis Bill List</a>
                                <a class="dropdown-item" href="./blood_billing.php">Blood Centre Bill</a>
                                <a class="dropdown-item" href="./bill_list.php?type=Blood Bank">Blood Centre Bill List</a>

                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps" role="button" data-toggle="dropdown" aria-haspopup="true"><i
                                    class="icofont-prescription mr-2"></i><span class="nav-text">Reports</span></a>
                            <div class="dropdown-menu  "
                                aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="../opreports.php">Out Patient</a>
                                <a class="dropdown-item" href="../ipreports.php">IN Patient</a>
                                <a class="dropdown-item" href="../otreports.php">Operation Theater</a>
                                <a class="dropdown-item" href="./op_reports.php">OPD Billing Report</a>
                                <a class="dropdown-item" href="./ip_reports.php">IPD Billing Report</a>
                                <a class="dropdown-item" href="./pathoreport.php">Lab Billing Report</a>
                                <a class="dropdown-item" href="./radio_reports.php">Radiology Billing Report</a>
                                <a class="dropdown-item" href="./radio_reports1.php">Radiology-2 Billing Report</a>
                                <a class="dropdown-item" href="./dialysis_reports.php">Dialysis Billing Report</a>
                                <a class="dropdown-item" href="./blood_reports.php">Blood Centre Billing Report</a>
                                <a class="dropdown-item" href="user_wise_collection.php">User Collection Report</a>
                                <a class="dropdown-item" href="doctor_service_list.php">Doctor Ledger Report</a>
                                <a class="dropdown-item" href="service_wise_report.php">Service Wise Report</a>
                            </div>
                        </li>


                    <?php
                    } else if ($_SESSION['role'] == 'Billing') { ?>
                        <li class="nav-item"><a class="nav-link" href="./dashboard.php" aria-expanded="false"><i class="icofont-dashboard mr-2"></i><span class="nav-text">Dashboard</span></a>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps" role="button" data-toggle="dropdown" aria-haspopup="true"><i class="icofont-user-alt-1 mr-2"></i><span class="nav-text">Patient List</span></a>
                            <div class="dropdown-menu  "
                                aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./patient_list.php">OPD List</a>
                                <a class="dropdown-item" href="./ippatient_list.php">IPD List</a>
                            </div>
                        </li>

                        </li>


                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps" role="button" data-toggle="dropdown" aria-haspopup="true"><i class="icofont-user-alt-1 mr-2"></i><span class="nav-text">OP Bill</span></a>
                            <div class="dropdown-menu  "
                                aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./addOP_billing.php">OP Billing</a>
                                <a class="dropdown-item" href="./bill_list.php?type=OP">OP Bill List</a>
                                <a class="dropdown-item" href="./op_bill_report.php">OP Billing Report</a>
                                <a class="dropdown-item" href="./op_cancel_list.php">OP Bill Cancellation</a>
                            </div>
                        </li>




                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps" role="button" data-toggle="dropdown" aria-haspopup="true"><i class="icofont-crutch mr-2"></i><span class="nav-text">IP Bill</span></a>
                            <div class="dropdown-menu  "
                                aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./ip_billing.php">IP Billing</a>
                                <a class="dropdown-item" href="./ipbill_list.php?type=IP">IP Bill List</a>
                                <a class="dropdown-item" href="./advance_list.php">IP Advance List</a>
                                <a class="dropdown-item" href="./ipbill_list.php">IP Advance Cancellation</a>
                                <a class="dropdown-item" href="./ip_bill_report.php">IP Billing Report</a>
                                <a class="dropdown-item" href="./ip_cancel_list.php">IP Bill Cancellation</a>
                            </div>



                        </li>



                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps" role="button" data-toggle="dropdown" aria-haspopup="true"><i class="icofont-xray mr-2"></i><span class="nav-text">Radio</span></a>
                            <div class="dropdown-menu  "
                                aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./radiology_billing.php">Radiology Billing</a>
                                <a class="dropdown-item" href="./bill_list.php?type=Radiology">Radiology Bill List</a>
                                <a class="dropdown-item" href="./bill_list.php">Radiology Bill Report</a>






                            </div>



                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps" role="button" data-toggle="dropdown" aria-haspopup="true"><i class="icofont-laboratory mr-2"></i><span class="nav-text">Lab</span></a>
                            <div class="dropdown-menu  "
                                aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./pathology_billing.php">Lab Billing</a>
                                <a class="dropdown-item" href="./bill_list.php?type=Pathology">Lab Bill List</a>
                                <a class="dropdown-item" href="./bill_list.php">Lab Bill Report</a>

                                <a class="dropdown-item" href="./lab_test_group.php">Lab Master</a>



                            </div>



                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps" role="button" data-toggle="dropdown" aria-haspopup="true"><i class="icofont-blood mr-2"></i><span class="nav-text">Blood Centre</span></a>
                            <div class="dropdown-menu  "
                                aria-labelledby="topnav-uielement">

                                <a class="dropdown-item" href="./blood_billing.php">Blood Centre Bill</a>
                                <a class="dropdown-item" href="./bill_list.php?type=Blood Bank">Blood Centre Bill List</a>
                                <a class="dropdown-item" href="./bill_list.php">Blood Centre Bill Report</a>





                            </div>



                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps" role="button" data-toggle="dropdown" aria-haspopup="true"><i class="icofont-computer mr-2"></i><span class="nav-text">Dialysis</span></a>
                            <div class="dropdown-menu  "
                                aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./dialysis_billing.php">Dialysis Bill</a>
                                <a class="dropdown-item" href="./bill_list.php?type=Dialysis">Dialysis Bill List</a>
                                <a class="dropdown-item" href="./bill_list.php">Dialysis Bill Report</a>





                            </div>



                            <!--</li>-->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps" role="button" data-toggle="dropdown" aria-haspopup="true"><i class="icofont-file-alt mr-2"></i><span class="nav-text">Claim</span></a>
                            <div class="dropdown-menu  "
                                aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./claim_billing.php">Claim Bill</a>
                                <a class="dropdown-item" href="./claimbill_list.php?type=Claim">Claim Bill List</a>





                            </div>



                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps" role="button" data-toggle="dropdown" aria-haspopup="true"><i class="icofont-files-stack mr-2"></i><span class="nav-text">ESI</span></a>
                            <div class="dropdown-menu  "
                                aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./esi_billing.php">ESI Bill</a>
                                <a class="dropdown-item" href="./esibill_list.php?type=TPA">ESI Bill List</a>

                                <a class="dropdown-item" href="./dischargelist.php">Discharge List</a>



                            </div>



                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps" role="button" data-toggle="dropdown" aria-haspopup="true"><i
                                    class="icofont-prescription mr-2"></i><span class="nav-text">Reports</span></a>
                            <div class="dropdown-menu  "
                                aria-labelledby="topnav-uielement">
                                <!--<a class="dropdown-item" href="./mis_reports.php">MIS Report</a>-->
                                <a class="dropdown-item" href="./op_reports.php">OPD Billing Report</a>
                                <a class="dropdown-item" href="./ip_reports.php">IPD Billing Report</a>
                                <a class="dropdown-item" href="./pathoreport.php">Pathology Billing Report</a>
                                <a class="dropdown-item" href="./radio_reports.php">Radiology Billing Report</a>
                                <a class="dropdown-item" href="./radio_reports1.php">Radiology-2 Billing Report</a>
                                <a class="dropdown-item" href="./dialysis_reports.php">Dialysis Billing Report</a>
                                <a class="dropdown-item" href="./blood_reports.php">Blood Centre Billing Report</a>
                                <a class="dropdown-item" href="user_wise_collection.php">User Collection Report</a>





                            </div>
                        </li>


                    <?php } else if ($_SESSION['role'] == 'Lab' || $_SESSION['role'] == 'Lab Admin') { ?>
                        <li class="nav-item"><a class="nav-link" href="../dashboard.php" aria-expanded="false"><i class="icofont-dashboard mr-2"></i><span class="nav-text">Dashboard</span></a>

                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps" role="button" data-toggle="dropdown" aria-haspopup="true"><i class="icofont-laboratory mr-2"></i><span class="nav-text">Lab Billing</span></a>
                            <div class="dropdown-menu  "
                                aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="./pathology_billing.php">Lab Billing</a>
                                <a class="dropdown-item" href="./bill_list.php?type=Pathology">Lab Bill List</a>
                                <a class="dropdown-item" href="./pathoreport.php">Lab Bill Report</a>






                            </div>



                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps" role="button" data-toggle="dropdown" aria-haspopup="true"><i class="icofont-user-alt-1 mr-2"></i><span class="nav-text">Patient </span></a>
                            <div class="dropdown-menu  "
                                aria-labelledby="topnav-uielement">


                                <a class="dropdown-item" href="../oppatient.php">OP Patient List</a>
                                <a class="dropdown-item" href="../ippatient.php?id=3">IP Patient List</a>

                            </div>
                        </li>



                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps" role="button" data-toggle="dropdown" aria-haspopup="true"><i class="icofont-test-tube mr-2"></i><span class="nav-text">Lab Master</span></a>
                            <div class="dropdown-menu  "
                                aria-labelledby="topnav-uielement">

                                <a class="dropdown-item" href="../lab_test_group.php">Add Test Group</a>
                                <!--<a class="dropdown-item" href="../lab_test_name.php">Add Test Name</a>-->
                                <a class="dropdown-item" href="../view-test-list.php">Test List </a>
                                <a class="dropdown-item" href="../packageList.php">Packages </a>
                                <a class="dropdown-item" href="../referenceCenter.php">Ref Center</a>
                                <a class="dropdown-item" href="../outSourceLab.php">Outsource Lab</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps" role="button" data-toggle="dropdown" aria-haspopup="true"><i class="icofont-crutch mr-2"></i><span class="nav-text">Phlebotomy</span></a>
                            <div class="dropdown-menu  "
                                aria-labelledby="topnav-uielement">


                                <a class="dropdown-item" href="../sample_collection.php">Sample Collection</a>
                                <!--<a class="dropdown-item" href="./sample_dispatch.php">Sample Dispatch</a>-->
                            </div>



                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps" role="button" data-toggle="dropdown" aria-haspopup="true"><i class="ri-microscope-line mr-2"></i><span class="nav-text">Lab</span></a>
                            <div class="dropdown-menu  "
                                aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="../sample_acknowledge.php">Sample Acknowledge</a>
                                <!--<a class="dropdown-item" href="../sample_status.php">Sample Status</a>-->
                                <a class="dropdown-item" href="../lab_result.php">lab Result </a>
                                <a class="dropdown-item" href="../result_finalization.php">Result Finalization </a>

                                <a class="dropdown-item" href="../lab_result_print.php">lab Result Print </a>
                            </div>



                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" aria-expanded="false" id="topnav-aps" role="button" data-toggle="dropdown" aria-haspopup="true"><i class="icofont-user-alt-1 mr-2"></i><span class="nav-text">Lab Report </span></a>

                            <div class="dropdown-menu  "
                                aria-labelledby="topnav-uielement">
                                <a class="dropdown-item" href="../lab_reports.php">Laboratory</a>




                            </div>
                        </li>
                    <?php }
                    ?>
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
                            <input type="password" class="form-control" name="currentpwd" id="currentpwd" placeholder="Enter Current Password" required>
                        </div>
                    </div>
                    <div class="col-md-12">

                        <div class="form-group">
                            <label for="newpwd"> New Password <small class="text-danger">*</small></label>
                            <input type="password" class="form-control" name="newpwd" id="newpwd" placeholder="Enter New Password" required>
                        </div>
                    </div>
                    <div class="col-md-12">

                        <div class="form-group">
                            <label for="confirmpwd"> Confirm Password <small class="text-danger">*</small></label>
                            <input type="password" class="form-control" name="confirmpwd" id="confirmpwd" placeholder="Confirm Password" required>
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
            if (e.key !== 'Enter') return;

            const el = e.target;
            const tag = el.tagName.toLowerCase();
            const type = (el.type || '').toLowerCase();

            // If submit button → allow only ONE submit
            if (type === 'submit' || tag === 'button') {
                return; // let normal submit happen
            }

            // Apply Enter-as-Tab only for inputs
            if (tag === 'input' || tag === 'select' || tag === 'textarea') {
                e.preventDefault();

                const focusable = Array.from(
                    document.querySelectorAll('input, select, textarea, button')
                ).filter(el =>
                    !el.disabled &&
                    el.type !== 'hidden' &&
                    el.offsetParent !== null
                );

                const index = focusable.indexOf(el);
                if (index > -1 && index < focusable.length - 1) {
                    focusable[index + 1].focus();
                }
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

    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
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