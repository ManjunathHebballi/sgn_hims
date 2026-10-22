<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/labClass.php');
$newLab = new lab;   
if(isset($_REQUEST['type'])&& $_REQUEST['type']=='sample_collection')
{
    $newLab->insertSampleCollect($_REQUEST['bill_no'],$_REQUEST['id'],$_REQUEST['test_id'],$_REQUEST['visit_no']);
    echo "<script>window.location='lab_report.php?id=$_REQUEST[id]&bill_no=$_REQUEST[bill_no]'</script>";
}
if(isset($_REQUEST['type'])&& $_REQUEST['type']=='sample_dispatch')
{
    $newLab->updateSampleDispatch($_REQUEST['sample_id']);
    echo "<script>window.location='lab_report.php?id=$_REQUEST[id]&bill_no=$_REQUEST[bill_no]'</script>";
}
if(isset($_REQUEST['type'])&& $_REQUEST['type']=='acknowledge')
{
    $newLab->updateSampleCollect($_REQUEST['sample_id']);
    echo "<script>window.location='lab_report.php?id=$_REQUEST[id]&bill_no=$_REQUEST[bill_no]'</script>";
}
if(isset($_REQUEST['type'])&& $_REQUEST['type']=='finalization')
{
    $done = $newLab->updateResultFinalization($_REQUEST['sample_id']);
    if (!$done) {
        echo "<script>alert('Could not finalize: no result has been entered for this sample yet.');</script>";
    }
    echo "<script>window.location='lab_report.php?id=$_REQUEST[id]&bill_no=$_REQUEST[bill_no]'</script>";
}
if(isset($_REQUEST['type'])&& $_REQUEST['type']=='bulk_finalization')
{
    if(isset($_REQUEST['sample_ids']) && is_array($_REQUEST['sample_ids'])) {
        $ok = 0; $refused = 0;
        foreach($_REQUEST['sample_ids'] as $sample_id) {
            if ($newLab->updateResultFinalization($sample_id)) { $ok++; } else { $refused++; }
        }
        if ($refused > 0) {
            echo "<script>alert('Finalized $ok. Skipped $refused with no result entered.');</script>";
        }
    }
    echo "<script>window.location='lab_report.php?id=$_REQUEST[id]&bill_no=$_REQUEST[bill_no]'</script>";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Lab Report</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <style>
    /* Wider popover so the test details table doesn’t overflow */
    .popover.test-details-popover {
        max-width: 90vw;
        width: 820px;
        overflow: hidden;
    }

    .popover.test-details-popover .popover-body {
        max-height: 600px;
        overflow: auto;
    }

    .popover.test-details-popover table {
        width: 100%;
        table-layout: fixed;
    }

    .popover.test-details-popover table td,
    .popover.test-details-popover table th {
        word-wrap: break-word;
        white-space: normal;
    }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <!-- Datatable -->
    <link href="./vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/dompurify@3.0.8/dist/purify.min.js"></script>
    <style>
    #notification {
        position: absolute;
        top: 90px;

        right: 2px;
        z-index: 99;
    }

    .summernote-smaller~.note-editor .note-toolbar .btn,
    .note-editor .note-toolbar .btn {
        padding: 0.15rem 0.4rem !important;
        font-size: 0.85rem !important;
        line-height: 1.2 !important;
        border-radius: 0.15rem !important;
    }

    .summernote-smaller~.note-editor .note-toolbar .note-btn-group,
    .note-editor .note-toolbar .note-btn-group {
        margin-right: 0.2rem !important;
    }

    .summernote-smaller~.note-editor .note-toolbar .note-btn,
    .note-editor .note-toolbar .note-btn {
        min-width: 1.5rem !important;
        min-height: 1.5rem !important;
    }

    /* Ensure modal displays properly */
    .modal {
        z-index: 1050 !important;
    }

    .modal-backdrop {
        z-index: 1040 !important;
    }

    /* Ensure modal is hidden by default */
    #reportDispatchModal {
        display: none !important;
    }

    #reportDispatchModal.show {
        display: block !important;
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
        <?php include ('leftbar.php')?>

        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <!-- row -->




            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="text-uppercase">Patient Visit Details</h4>
                                <table class="table table-bordered patient_data" style="font-size: 12px;">
                                    <tbody>
                                        <?php 
                            $result=$newLab->getPatientLabVisitsDate($_REQUEST['id']);
                          while($row=mysqli_fetch_assoc($result)){
                                // Display patient name
                              
                            ?>
                                        <tr class="details">

                                            <td class="patient">
                                                <a
                                                    href="lab_report.php?id=<?= $_REQUEST['id']?>&bill_no=<?= $row['bill_no']?> ">

                                                    <?php   if(isset($row['date'])) echo strtoupper(date("d.m.Y h:i:s A",strtotime($row['date'])));?>
                                                </a>

                                            </td>

                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsives mb-4 ">
                                    <table class="table table-bordered patient_data" style="font-size: 12px;">
                                        <tbody>
                                            <tr class="details">

                                                <td class="patient">
                                                    Patient Name :
                                                    <?php 
                            $result=$newLab->getPatientLabVisits($_REQUEST['id']);
                            $patient=mysqli_fetch_assoc($result);
                                // Display patient name
                                if(isset($patient['name'])) echo strtoupper($patient['name']);
                            ?>

                                                </td>
                                                <td>
                                                    Patient No. :
                                                    <?php if(isset($patient['opd_no'])) echo $patient['opd_no']; ?>
                                                </td>
                                                <td>
                                                    Age :
                                                    <?php 
                            // Display age or DOB
                            if(isset($patient['age'])) {
                                echo $patient['age'] . " Yrs";
                            } elseif(isset($patient['dob'])) {
                                // Calculate age from DOB
                                $dob = new DateTime($patient['dob']);
                                $now = new DateTime();
                                $age = $now->diff($dob)->y;
                                echo $age . " Yrs";
                            }
                        ?>
                                                </td>
                                                <td>
                                                    Gender :
                                                    <?php if(isset($patient['gender'])) echo ucfirst($patient['gender']); ?>
                                                </td>
                                                <td>
                                                    Lab No. :
                                                    <?php if(isset($patient['visit_no'])) echo $patient['visit_no']; ?>
                                                </td>
                                                <td>
                                                    Doctor :
                                                    <?php 
                            if(isset($patient['doctor'])) {
                              echo $patient['doctor']."<br>";
                            }
                        ?>
                                                </td>
                                                <td>
                                                    Ref. Center :
                                                    <?php if(isset($patient['ref_center'])) echo $patient['ref_center']; ?>
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <h4 class="text-uppercase">Lab Orders</h4>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <div class="btn-group" role="group">
                                            <button type="button" id="bulkInterimBtn"
                                                class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false" disabled>
                                                Print Selected Interim (0)
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right" style="min-width: 200px;">
                                                <a class="dropdown-item" href="#"
                                                    onclick="printBulkInterim('standard'); return false;">
                                                    <i class="bi-printer"></i> Standard (With Header)
                                                </a>
                                                <a class="dropdown-item" href="#"
                                                    onclick="printBulkInterim('letterhead'); return false;">
                                                    <i class="bi-printer"></i> Letterhead (No Header)
                                                </a>
                                            </div>
                                        </div>
                                        <?php if($_SESSION['role']=='Lab Admin' || $_SESSION['role']=='Admin' || $_SESSION['role']=='MRD') { ?>
                                        <button type="button" id="bulkFinalizeBtn" class="btn btn-success btn-sm"
                                            onclick="bulkFinalize()" disabled>
                                            Finalize Selected (0)
                                        </button>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-hover" width=100%>
                                            <thead>
                                                <tr>
                                                    <th width="40px">
                                                        <input type="checkbox" id="selectAllInterim"
                                                            onclick="toggleSelectAllInterim(this)">
                                                    </th>
                                                    <th>Lab Test</th>
                                                    <th>Sample</th>
                                                    <th>Action</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 

                        $medicine = $newLab->getLabTest($_REQUEST['id'],$_REQUEST['bill_no']);
                        $sn=1;
                        while($tab = mysqli_fetch_array($medicine))
                        {
                            // Check if user has permission for this test's department
                            $hasFullAccess = true;
                            if(isset($_SESSION['lab_dept']) && !empty($_SESSION['lab_dept'])) {
                                $userDepartments = json_decode($_SESSION['lab_dept'], true);
                                $hasFullAccess = in_array($tab['testname_header'], $userDepartments);
                            }
                            
                            // Pre-fetch test details for popover
                            $testDetails = $newLab->getLabTestDetails($_REQUEST['id'], $_REQUEST['bill_no'], $tab['testid'], $patient['gender']);
                            $popoverHtml = '';
                            $popoverHtml .= '<table class="table table-sm table-bordered mb-0" style="font-size: 0.85rem;">';
                            $popoverHtml .= '<thead><tr><th style="width: 50%;">Parameter</th><th style="width: 25%;">Result</th><th style="width: 25%;">Normal Range</th></tr></thead><tbody>';
                            
                            $hasData = false;
                            foreach($testDetails as $detail) {
                                if($detail['lab_report_id'] > 0) {
                                    $hasData = true;
                                    $type = $detail['type'];
                                    
                                    if($type == 'heading') {
                                        $popoverHtml .= '<tr><td colspan="3" class="font-weight-bold bg-light">'.htmlspecialchars($detail['testname']).'</td></tr>';
                                    } 
                                    elseif($type == 'parameter') {
                                        $popoverHtml .= '<tr>';
                                        $popoverHtml .= '<td>'.htmlspecialchars($detail['testname']).'</td>';
                                        $popoverHtml .= '<td>'.htmlspecialchars($detail['test_result'] ?: '-').'</td>';
                                        
                                        $normalRange = '';
                                        if($detail['normal_value']) {
                                            $normalRange = htmlspecialchars($detail['normal_value']).' '.htmlspecialchars($detail['unit']);
                                        } elseif($detail['lower_limit'] !== '' && $detail['upper_limit'] !== '') {
                                            $normalRange = htmlspecialchars($detail['lower_limit']).' - '.htmlspecialchars($detail['upper_limit']).' '.htmlspecialchars($detail['unit']);
                                        } else {
                                            $normalRange = '-';
                                        }
                                        $popoverHtml .= '<td>'.$normalRange.'</td>';
                                        $popoverHtml .= '</tr>';
                                    }
                                    elseif($type == 'free_text') {
                                        $popoverHtml .= '<tr><td colspan="3"><div style="max-height: 150px; overflow-y: auto;">'.strip_tags($detail['test_result'], '<b><i><u><br><p>').'</div></td></tr>';
                                    }
                                }
                            }
                            
                            if(!$hasData) {
                                $popoverHtml .= '<tr><td colspan="3" class="text-center text-muted">No results available</td></tr>';
                            }
                            
                            $popoverHtml .= '</tbody></table>';
                            $popoverData = htmlspecialchars($popoverHtml, ENT_QUOTES);
                        ?>
                                                <tr id='addr1'>

                                                    <td>
                                                        <?php if($hasFullAccess && $tab['is_sample_acknowledge']==1 && $tab['is_finalized']==0) { ?>
                                                        <input type="checkbox" class="interim-checkbox"
                                                            name="interim_tests[]" value="<?= $tab['testid']?>"
                                                            data-sampleid="<?= $tab['sample_id']?>"
                                                            onchange="updateInterimButton()">
                                                        <?php } ?>
                                                    </td>

                                                    <td class="test-name-cell" data-toggle="popover" data-html="true"
                                                        data-content="<?= $popoverData ?>"
                                                        title="<?= htmlspecialchars($tab['testname'], ENT_QUOTES) ?>"
                                                        style="cursor: pointer;">
                                                        <?= $tab['testname']?>
                                                    </td>
                                                    <td>
                                                        <?= $tab['samples']?>
                                                    </td>
                                                    <td>

                                                        <?php
                    if($hasFullAccess && $tab['is_sample_acknowledge']==1 && ($tab['is_finalized']==0 || $_SESSION['role']=='MRD' || $_SESSION['role']=='Admin' || $_SESSION['name']==$tab['finalized_by'] || $tab['finalized_by']=='')) {
                        // Check if results exist for this test
                        $hasResults = $newLab->checkTestResults($tab['testid'], $_REQUEST['id'], $_REQUEST['bill_no']);
                        $buttonText = $hasResults ? 'Edit Result' : 'Enter Result';
                        $buttonClass = $hasResults ? 'badge-warning' : 'badge-success';
                    ?>
                                                        <button class="badge <?= $buttonClass ?>"
                                                            onclick="addTest(<?= $tab['testid']?>,'<?=$patient['gender']?>')"><?= $buttonText ?></button>
                                                        <?php } 
                    if($hasFullAccess && !isset($tab['is_sample_collected'])) { 
                         $arr['test_id'][]=$tab['testid'];
                         ?>
                                                        <a href="lab_report.php?id=<?= $_REQUEST['id']?>&bill_no=<?= $_REQUEST['bill_no']?>&<?= http_build_query($arr)?>&type=sample_collection"
                                                            class="badge badge-success">Collect</a>
                                                    </td>
                                                    <?php }
                                            elseif($hasFullAccess && $tab['is_sample_collected']==1 && $tab['is_sample_dispatch']==0 ) { 
                                                ?>
                                                    <a href="lab_report.php?id=<?= $_REQUEST['id']?>&bill_no=<?= $_REQUEST['bill_no']?>&sample_id=<?= $tab['sample_id']?>&type=sample_dispatch"
                                                        class="badge badge-success">Dispatch</a>
                                                        <?php
                                            }        
                                                    elseif($hasFullAccess && $tab['is_sample_dispatch']==1 && $tab['is_sample_acknowledge']==0) { ?>
                                                    <a href="lab_report.php?id=<?= $_REQUEST['id']?>&bill_no=<?= $_REQUEST['bill_no']?>&sample_id=<?= $tab['sample_id']?>&type=acknowledge"
                                                        class="badge badge-success">Acknowledge</a>
                                                    <?php } elseif($hasFullAccess && $tab['is_sample_acknowledge']==1 && $tab['is_outsourced']==0 ) { ?>
                                                    <?php if($tab['is_finalized']==0 ) { 
                                                    if($tab['is_result_entered']==0) {
                                                    ?>
                                                    <button type="button"
                                                        class="badge badge-warning badge-outsource-lab"
                                                        data-visit_no="<?= htmlspecialchars($patient['visit_no']) ?>"
                                                        data-testid="<?= htmlspecialchars($tab['testid']) ?>"
                                                        data-testname="<?= htmlspecialchars($tab['testname']) ?>"
                                                        data-sample_id="<?= htmlspecialchars($tab['sample_id']) ?>">Outsource
                                                        Lab</button>
                                                    <?php } ?>

                                                    <a href="#"
                                                        onclick="viewTest(<?= $tab['testid']?>,'<?=$patient['gender']?>','<?= $tab['sample_id']?>')"
                                                        class="badge badge-secondary">View</a>

                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="badge badge-info dropdown-toggle"
                                                            data-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false" style="border: none;">
                                                            Interim
                                                        </button>
                                                        <div class="dropdown-menu" style="min-width: 180px;">
                                                            <a class="dropdown-item"
                                                                href="lab_print1.php?id=<?= $_REQUEST['id']?>&bill=<?= $_REQUEST['bill_no']?>&testid=<?= $tab['testid']?>"
                                                                target="_blank">
                                                                <i class="bi-printer"></i> Standard (With Header)
                                                            </a>
                                                            <a class="dropdown-item"
                                                                href="lab_print1_letterhead.php?id=<?= $_REQUEST['id']?>&bill=<?= $_REQUEST['bill_no']?>&testid=<?= $tab['testid']?>"
                                                                target="_blank">
                                                                <i class="bi-printer"></i> Letterhead (No Header)
                                                            </a>
                                                        </div>
                                                    </div>

                                                    <?php if(($_SESSION['role']=='Lab Admin' || $_SESSION['role']=='Admin' || $_SESSION['role']=='MRD') && $tab['is_result_entered']==1) { ?>
                                                    <a href="lab_report.php?type=finalization&id=<?= $_REQUEST['id']?>&sample_id=<?= $tab['sample_id']?>&bill_no=<?= $_REQUEST['bill_no']?>"
                                                        class="badge badge-primary">Finalize</a> <?php } ?>
                                                    <?php } 
                                               else{ ?>
                                                    <a href="#"
                                                        onclick="viewTest(<?= $tab['testid']?>,'<?=$patient['gender']?>','')"
                                                        class="badge badge-secondary">View</a>
                                                    <?php } ?>
                                                    <!--  <div class="form-check d-flex justify-content-end align-items-end" style="height:100%;">-->
                                                    <!--    <input class="form-check-input" type="checkbox" id="confirm_result_<?= $tab['testid'] ?>" name="confirm_result_<?= $tab['testid'] ?>">-->
                                                    <!--</div>-->
                                                    <?php }
            
                else {?>
                                                    <?php } 
                
                ?>
                                                    </td>


                                                </tr>
                                                <?php $sn++; } ?>

                                            </tbody>
                                            <!-- </table>
            <table class="table table-responsive table-bordered"   id="tab_logic"> -->

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php $medicine1 = $newLab->getLabTest1($_REQUEST['id'],$_REQUEST['bill_no']);
  if(mysqli_num_rows($medicine1)) { ?>
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <h4 class="text-uppercase">Lab Result</h4>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-hover" width=100%>
                                            <thead>
                                                <tr>
                                                    <th>Lab Test</th>
                                                    <th>Action</th>
                                                    <th>Signatures</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 

                        
                        $sn=1;
                        while($tab1 = mysqli_fetch_array($medicine1))
                        {
                            $sample_time= $tab1['sample_received_date'] ?? $tab1['sample_collected_at'];
                            $reporting_time=$tab1['reporting_time'] ?? date("Y-m-d H:i:s");
                        ?>
                                                <tr id='addr1'>

                                                    <td>
                                                        <?= $tab1['testname']?>

                                                    </td>
                                                    <td>

                                                        <div class="btn-group" role="group">
                                                            <button type="button"
                                                                class="badge badge-primary dropdown-toggle"
                                                                data-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false" style="border: none;">
                                                                <i class="bi-printer" style="font-size:15px"></i> Print
                                                            </button>
                                                            <div class="dropdown-menu" style="min-width: 180px;">
                                                                <a class="dropdown-item"
                                                                    href="lab_print1.php?id=<?= $_REQUEST['id']?>&bill=<?= $_REQUEST['bill_no']?>&testid=<?= $tab1['testid']?>"
                                                                    target="_blank">
                                                                    <i class="bi-printer"></i> Standard (With Header)
                                                                </a>
                                                                <a class="dropdown-item"
                                                                    href="lab_print1_letterhead.php?id=<?= $_REQUEST['id']?>&bill=<?= $_REQUEST['bill_no']?>&testid=<?= $tab1['testid']?>"
                                                                    target="_blank">
                                                                    <i class="bi-printer"></i> Letterhead (No Header)
                                                                </a>
                                                            </div>
                                                        </div>

                                                        <a href="#"
                                                            onclick="openReportDispatchModal('<?= $tab1['testid']?>','<?= $tab1['dispatch_id'] ?? 0?>', '<?= htmlspecialchars($tab1['testname'], ENT_QUOTES) ?>','<?= date("Y-m-d H:i:s",strtotime($sample_time)) ?>','<?= $reporting_time?>','<?= $tab1['left_signature']?>','<?= $tab1['right_signature']?>','<?= $tab1['left_e_signature']?>','<?= $tab1['right_e_signature']?>')"
                                                            title="Report Dispatch"
                                                            class="badge badge-dispatch badge-info"><i class="bi-send"
                                                                style="font-size:15px"></i></a>

                                                    </td>

                                                    <td>
                                                        <?php
           $badgeL=$badgeR='badge-light';
           if($tab1['left_signature']>0){
               $badgeL='badge-success';
           }
           if($tab1['right_signature']>0){
               $badgeR='badge-success';
           }
           ?>
                                                        <span class="btn badge <?= $badgeL ?>">L </span>

                                                        <span class="btn badge <?= $badgeR ?>">R </span>

                                                    </td>
                                                </tr>
                                                <?php $sn++; } ?>

                                            </tbody>
                                            <!-- </table>
            <table class="table table-responsive table-bordered"   id="tab_logic"> -->

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.js"></script>
    <div class="modal fade" id="outsourceLabModal" tabindex="-1" aria-labelledby="outsourceLabModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="outsourceLabModalLabel">Outsource Lab Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="outsourceLabDetails">
                        <form id="outsourceLabForm">
                            <div class="form-group">
                                <label for="outsource_lab_id">Select Outsource Lab</label>
                                <select class="form-control" id="outsource_lab_id" name="outsource_lab_id" required>
                                    <option value="">-- Select Lab --</option>
                                    <?php
                    $labs = $newLab->getOutsourceLab();
                    if ($labs && mysqli_num_rows($labs) > 0) {
                        while ($lab = mysqli_fetch_assoc($labs)) {
                            echo '<option value="' . htmlspecialchars($lab['id']) . '">' . htmlspecialchars($lab['name']) . '</option>';
                        }
                    }
                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="lab_price">Lab Price</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="lab_price"
                                    name="lab_price" placeholder="Enter price" required>
                            </div>
                            <input type="hidden" name="sample_id" id="modal_sample_id" value="">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                        <script>
                        $('#outsourceLabModal').on('show.bs.modal', function(event) {
                            var button = $(event.relatedTarget);
                            var sample_id = button.data('sample_id');
                            $('#modal_sample_id').val(sample_id);
                        });
                        $('#outsourceLabForm').on('submit', function(e) {
                            e.preventDefault();
                            $.ajax({
                                url: 'controller/processLab.php',
                                type: 'POST',
                                data: $(this).serialize() + '&submit=save_outsource_lab',
                                success: function(response) {
                                    $('#outsourceLabModal').modal('hide');
                                    location.reload();
                                },
                                error: function() {
                                    alert('Failed to save outsource lab details.');
                                }
                            });
                        });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    $(document).on('click', '.badge-outsource-lab', function() {
        var sample_id = $(this).data('sample_id');
        $('#modal_sample_id').val(sample_id);
        $('#outsourceLabModal').modal('show');
    });

    // Initialize Bootstrap popover for test name cells - no AJAX needed, data is preloaded
    $(document).ready(function() {
        $('.test-name-cell').popover({
            html: true,
            sanitize: false,
            trigger: 'hover focus',
            placement: 'right',
            fallbackPlacement: ['left'],
            boundary: 'window',
            container: 'body',
            delay: {
                show: 100,
                hide: 150
            },
            offset: '12,10',
            template: '<div class="popover test-details-popover" role="tooltip"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>'
        });
    });

    $('#outsourceLabForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: 'controller/processLab.php',
            type: 'POST',
            data: $(this).serialize() + '&submit=save_outsource_lab',
            success: function(response) {
                $('#outsourceLabModal').modal('hide');
                location.reload();
            },
            error: function() {
                alert('Failed to save outsource lab details.');
            }
        });
    });

    function addTest(testid, gender) {
        // Fetch test details via AJAX (you can adjust the endpoint as needed)
        $.ajax({
            url: 'ajax.php', // You need to create this endpoint
            type: 'POST',
            data: {
                page: 'lab_report',
                testid: testid,
                patient_id: '<?= isset($_REQUEST['id']) ? $_REQUEST['id'] : '' ?>',
                bill_no: '<?= isset($_REQUEST['bill_no']) ? $_REQUEST['bill_no'] : '' ?>',
                gender: gender
            },
            success: function(html) {
                $('#labResultModalBody').html(html);

                $('#labResultModal').modal('show');
                $('#labResultModalBody .summernote').summernote({
                    height: 100,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'underline', 'clear']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['height', ['height']],
                        ['insert', ['link']],
                        ['view', ['fullscreen', 'codeview', 'help']]
                    ],
                    callbacks: {
                        onPaste: function(e) {
                            // Optional: add your paste sanitization here
                        }
                    }
                });
            },
            error: function() {
                alert('Failed to load test details.');
            }
        });
    }

    // Function to view test results in read-only mode
    function viewTest(testid, gender, sampleid) {
        // Fetch test details via AJAX for view mode
        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            data: {
                page: 'lab_report_view',
                testid: testid,
                patient_id: '<?= isset($_REQUEST['id']) ? $_REQUEST['id'] : '' ?>',
                bill_no: '<?= isset($_REQUEST['bill_no']) ? $_REQUEST['bill_no'] : '' ?>',
                gender: gender
            },
            success: function(html) {
                $('#viewLabTestModalBody').html(html);
                $('#viewLabTestModal').modal('show');

                // Store sample_id for finalization
                $('#viewModalFinalizeBtn').data('sampleid', sampleid);
                $('#viewModalFinalizeBtn').data('testid', testid);

                // Show finalize button if user has permission and test is not finalized
                if (sampleid) {
                    $('#viewModalFinalizeBtn').show();
                } else {
                    $('#viewModalFinalizeBtn').hide();
                }
            },
            error: function() {
                alert('Failed to load test details for viewing.');
            }
        });
    }

    function finalizeFromViewModal() {
        var sampleId = $('#viewModalFinalizeBtn').data('sampleid');
        var testId = $('#viewModalFinalizeBtn').data('testid');

        if (confirm('Are you sure you want to finalize this test?')) {
            window.location.href = 'lab_report.php?type=finalization&id=<?= $_REQUEST['id']?>&sample_id=' + sampleId +
                '&bill_no=<?= $_REQUEST['bill_no']?>';
        }
    }
    // Define isPasting to prevent multiple paste events
    // Initialize all .summernote editors under #labResultModalBody
    // Try to catch all paste events and always show alert
    // Use event delegation in case editors are dynamically created
    // $(document).on('paste', '.note-editable', function(e) {
    //     alert('Global paste event triggered!');
    // });

    // Also initialize Summernote as before, but keep the alert simple
    // Global paste alert for all Summernote editors (and any .note-editable)
    $(document).off('paste.globalPasteAlert').on('paste.globalPasteAlert', '.note-editable', function(e) {
        // alert('Global paste event triggered!');
        // Get the textarea id associated with this .note-editable div
        var textareaId = $(this).closest('.note-editor').prev('textarea').attr('id');
        // alert(textareaId);
        var $editor = $('#' + textareaId);
        // Optionally, you can prevent default and sanitize here if needed
        e.preventDefault();
        var clipboardData = (e.originalEvent || e).clipboardData || window.clipboardData;
        var htmlData = clipboardData.getData('text/html');
        var textData = clipboardData.getData('text');
        var pasteContent = htmlData || textData;
        // Sanitize HTML, allow only safe tags (including table)
        var clean = DOMPurify.sanitize(pasteContent, {
            ALLOWED_TAGS: ['table', 'thead', 'tbody', 'tr', 'td', 'th', 'b', 'strong', 'i', 'u', 'ul',
                'ol', 'li', 'p', 'br', 'span'
            ],
            ALLOWED_ATTR: ['colspan', 'rowspan']
        });
        $editor.summernote('pasteHTML', clean);
        // ... (sanitization logic if required)
    });

    // Initialize Summernote editors as usual (without per-editor onPaste)
    $('#labResultModalBody .summernote').each(function() {
        $(this).summernote({
            height: 250,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['insert', ['link', 'picture', 'video', 'table']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
            // No per-editor onPaste callback needed for global alert
        });
    });



    // Report Dispatch Modal Functions
    function openReportDispatchModal(testId, dispatchId, testName, datetime, reporttime, leftSign, rightSign, leftESign,
        rightESign) {
        try {
            console.log('Opening modal for test:', testId, testName);

            // Check if modal element exists
            var modal = document.getElementById('reportDispatchModal');
            if (!modal) {
                console.error('Modal element not found!');
                alert('Modal element not found!');
                return;
            }
            console.log('Modal element found:', modal);

            // Set the test ID and name in the modal
            document.getElementById('dispatch_test_id').value = testId;
            document.getElementById('test_name_display').textContent = testName;
            document.getElementById('report_ids_').value = testId;
            document.getElementById('report_dispatch_sample_received_date').value = datetime.replace(" ", "T");
            document.getElementById('report_dispatch_reporting_time').value = reporttime.replace(" ", "T");
            document.getElementById('dispatch_id').value = dispatchId;
            // Get patient name from the existing patient data on the page
            var patientName = '<?= isset($patient['name']) ? strtoupper($patient['name']) : 'Patient' ?>';
            document.getElementById('patient_name_display').textContent = patientName;
            document.getElementById('report_dispatch_left_signature').value = leftSign;
            document.getElementById('report_dispatch_right_signature').value = rightSign;
            // If leftSign or rightSign exists (not empty/null/undefined), show the hidden signature fields
            if (leftSign) {
                left_e_signature_hide_show($('#report_dispatch_left_signature'));
            }
            if (rightSign) {
                right_e_signature_hide_show($('#report_dispatch_right_signature'));
            }
            // Set the checked property for left and right e-signature checkboxes based on value
            var leftESignCheckbox = document.getElementById('report_dispatch_left_e_signature');
            if (leftESignCheckbox) {
                leftESignCheckbox.checked = (leftESign == 1);
            }
            var rightESignCheckbox = document.getElementById('report_dispatch_right_e_signature');
            if (rightESignCheckbox) {
                rightESignCheckbox.checked = (rightESign == 1);
            }
            // Show the modal using Bootstrap
            console.log('Showing modal...');
            $('#reportDispatchModal').modal('show');

            console.log('Modal should be visible now');
        } catch (error) {
            console.error('Error opening modal:', error);
            alert('Error opening modal: ' + error.message);
        }
    }

    function saveReportDispatch() {
        // Get form data
        var formData = new FormData(document.getElementById('edit_report_dispatch_form'));

        // Add current timestamp
        var now = new Date();
        var timestamp = now.getDate().toString().padStart(2, '0') + '-' +
            (now.getMonth() + 1).toString().padStart(2, '0') + '-' +
            now.getFullYear() + ' ' +
            now.getHours().toString().padStart(2, '0') + ':' +
            now.getMinutes().toString().padStart(2, '0') + ':' +
            now.getSeconds().toString().padStart(2, '0');

        formData.append('timestamp', timestamp);

        // Include submit action for controller routing
        formData.append('submit', 'save_report_dispatch');

        // Send AJAX request to save the dispatch data
        $.ajax({
            url: 'controller/processLab.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                try {
                    var result = JSON.parse(response);
                    // alert(result.success);
                    if (result.success) {
                        alert('Report dispatch saved successfully!');
                        $('#reportDispatchModal').modal('hide');
                        // Optionally refresh the page or update the UI
                        location.reload();
                    } else {
                        alert('Error: ' + (result.message || 'Failed to save report dispatch'));
                    }
                } catch (e) {
                    alert('Report dispatch saved successfully!');
                    $('#reportDispatchModal').modal('hide');
                }
            },
            error: function() {
                alert('Error occurred while saving report dispatch');
            }
        });
    }

    // E-signature hide/show functions
    function left_e_signature_hide_show(selectElement) {
        var leftESigDiv = $('.left_e_sig');
        if (selectElement.val()) {
            leftESigDiv.show();
        } else {
            leftESigDiv.hide();
        }
    }

    function right_e_signature_hide_show(selectElement) {
        var rightESigDiv = $('.right_e_sig');
        if (selectElement.val()) {
            rightESigDiv.show();
        } else {
            rightESigDiv.hide();
        }
    }

    // Initialize e-signature visibility on page load
    $(document).ready(function() {
        left_e_signature_hide_show($('#report_dispatch_left_signature'));
        right_e_signature_hide_show($('#report_dispatch_right_signature'));

        // Ensure modal is hidden on page load
        $('#reportDispatchModal').modal('hide');

        // Add event listeners for modal
        $('#reportDispatchModal').on('hidden.bs.modal', function() {
            console.log('Modal hidden');
        });

        $('#reportDispatchModal').on('show.bs.modal', function() {
            console.log('Modal showing');
        });

        // Add event listener for modal close button
        $(document).on('click', '[data-dismiss="modal"]', function() {
            closeModal();
        });

        // Add event listener for escape key
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });

        // Add click outside modal to close
        $(document).on('click', function(e) {
            if ($(e.target).hasClass('modal') && !$(e.target).hasClass('modal-dialog')) {
                closeModal();
            }
        });
    });

    // Function to close modal
    function closeModal() {
        $('#reportDispatchModal').modal('hide');
    }

    // Multi-select interim report functions
    function toggleSelectAllInterim(source) {
        var checkboxes = document.querySelectorAll('.interim-checkbox');
        for (var i = 0; i < checkboxes.length; i++) {
            checkboxes[i].checked = source.checked;
        }
        updateInterimButton();
        updateFinalizeButton();
    }

    function updateInterimButton() {
        var checkboxes = document.querySelectorAll('.interim-checkbox:checked');
        var count = checkboxes.length;
        var btn = document.getElementById('bulkInterimBtn');

        if (count > 0) {
            btn.disabled = false;
            btn.textContent = 'Print Selected Interim (' + count + ')';
        } else {
            btn.disabled = true;
            btn.textContent = 'Print Selected Interim (0)';
        }

        updateFinalizeButton();
    }

    function printBulkInterim(type) {
        var checkboxes = document.querySelectorAll('.interim-checkbox:checked');
        var testIds = [];

        for (var i = 0; i < checkboxes.length; i++) {
            testIds.push(checkboxes[i].value);
        }

        if (testIds.length === 0) {
            alert('Please select at least one test to print');
            return false;
        }

        var url = '';
        if (type === 'standard') {
            url = 'lab_print1.php?id=<?= $_REQUEST['id']?>&bill=<?= $_REQUEST['bill_no']?>&testid=' + testIds.join(',');
        } else if (type === 'letterhead') {
            url = 'lab_print1_letterhead.php?id=<?= $_REQUEST['id']?>&bill=<?= $_REQUEST['bill_no']?>&testid=' + testIds
                .join(',');
        }

        window.open(url, '_blank');
        return false;
    }

    // Multi-select finalization functions (using same checkboxes as interim)
    function updateFinalizeButton() {
        var checkboxes = document.querySelectorAll('.interim-checkbox:checked');
        var count = checkboxes.length;
        var btn = document.getElementById('bulkFinalizeBtn');

        if (btn) {
            if (count > 0) {
                btn.disabled = false;
                btn.textContent = 'Finalize Selected (' + count + ')';
            } else {
                btn.disabled = true;
                btn.textContent = 'Finalize Selected (0)';
            }
        }
    }

    function bulkFinalize() {
        var checkboxes = document.querySelectorAll('.interim-checkbox:checked');
        var sampleIds = [];

        for (var i = 0; i < checkboxes.length; i++) {
            sampleIds.push(checkboxes[i].getAttribute('data-sampleid'));
        }

        if (sampleIds.length === 0) {
            alert('Please select at least one test to finalize');
            return false;
        }

        if (confirm('Are you sure you want to finalize ' + sampleIds.length + ' selected test(s)?')) {
            // Create a form and submit it
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = 'lab_report.php';

            // Add hidden fields
            var typeInput = document.createElement('input');
            typeInput.type = 'hidden';
            typeInput.name = 'type';
            typeInput.value = 'bulk_finalization';
            form.appendChild(typeInput);

            var idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = 'id';
            idInput.value = '<?= $_REQUEST['id'] ?>';
            form.appendChild(idInput);

            var billInput = document.createElement('input');
            billInput.type = 'hidden';
            billInput.name = 'bill_no';
            billInput.value = '<?= $_REQUEST['bill_no'] ?>';
            form.appendChild(billInput);

            // Add sample IDs
            for (var i = 0; i < sampleIds.length; i++) {
                var sampleInput = document.createElement('input');
                sampleInput.type = 'hidden';
                sampleInput.name = 'sample_ids[]';
                sampleInput.value = sampleIds[i];
                form.appendChild(sampleInput);
            }

            document.body.appendChild(form);
            form.submit();
        }

        return false;
    }
    </script>
    <script>
    // Replace select with input on demand (Add more)
    $(document).on('click', '.btn-add-more', function(e) {
        e.preventDefault();
        var selectId = $(this).data('target');
        var $select = $('#' + selectId);
        if ($select.length) {
            var name = $select.attr('name');
            var value = $select.val() || '';
            var $input = $('<input type="text" class="form-control"/>');
            $input.attr('id', selectId);
            $input.attr('name', name);
            $input.val(value);
            $select.replaceWith($input);
            $(this).remove();
        }
    });
    </script>

    <!-- Modal HTML -->
    <div class="modal fade" id="labResultModal" tabindex="-1" role="dialog" aria-labelledby="labResultModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="labResultModalLabel">Lab Result</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="controller/processLab.php" method="post" novalidate="novalidate"
                        accept-charset="UTF-8" method="post" enctype="multipart/form-data">
                        <table class="table table-bordered table-hover" width=100%>
                            <thead>
                                <tr>
                                    <th>Lab Test</th>
                                    <th>Result</th>
                                    <th>Normal Value</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="labResultModalBody">
                                <!-- Content will be loaded here via AJAX -->
                            </tbody>
                        </table>
                        <input type="hidden" name="test_id" id="test_id"
                            value="<?= isset($_REQUEST['id']) ? $_REQUEST['id'] : '' ?>">
                        <input type="hidden" name="bill_no" id="bill_no"
                            value="<?= isset($_REQUEST['bill_no']) ? $_REQUEST['bill_no'] : '' ?>">
                        <button class="btn btn-success btn-sm" type="submit" name="submit"
                            value="save_result">Save</button>
                    </form>
                    <!-- Content will be loaded here via AJAX -->
                </div>
            </div>
        </div>
    </div>
    <!-- Report Dispatch Modal -->
    <div class="modal fade" id="reportDispatchModal" tabindex="-1" role="dialog"
        aria-labelledby="reportDispatchModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reportDispatchModalLabel">Report Dispatch</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="simple_form edit_report_dispatch" id="edit_report_dispatch_form" action="proce"
                        method="post" accept-charset="UTF-8">
                        <input type="hidden" name="_method" value="patch" autocomplete="off">
                        <input type="hidden" name="test_id" id="dispatch_test_id" value="">
                        <input type="hidden" name="patient_id" id="dispatch_patient_id" value="<?= $_REQUEST['id'] ?>">
                        <input type="hidden" name="bill_no" id="dispatch_bill_no" value="<?= $_REQUEST['bill_no'] ?>">

                        <h6>Table format reports</h6>
                        <div id="lab_test_sort" class="ui-sortable">
                            <div id="report_test">
                                <label class="checkbox">
                                    <input type="checkbox" name="report_ids[]" id="report_ids_" value=""
                                        checked="checked">
                                    <span id="test_name_display"></span>
                                </label>
                                <table class="table"
                                    style="margin-left: 20px; width: 90%; margin-bottom: 5px; font-size: 90%; color: #777">
                                    <tbody>
                                        <tr>
                                            <td id="patient_name_display">Patient Name</td>

                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <br>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>

                                        <td>
                                            <div class="form-group">
                                                <label for="report_dispatch_sample_received_date">Sample received
                                                    date</label>
                                                <input class="form-control" type="datetime-local"
                                                    name="report_dispatch[sample_received_date]"
                                                    id="report_dispatch_sample_received_date">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <label for="report_dispatch_reporting_time">Reporting time</label>
                                                <input value="<?= date('d-m-Y h:i:s A') ?>" class="form-control"
                                                    type="datetime-local" name="report_dispatch[reporting_time]"
                                                    id="report_dispatch_reporting_time">
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <div class="form-group">
                                                <label for="report_dispatch_left_signature">Left signature</label>
                                                <select class="form-control" name="report_dispatch[left_signature]"
                                                    id="report_dispatch_left_signature"
                                                    onchange="left_e_signature_hide_show($(this));">
                                                    <option value="" label=" "></option>
                                                    <?php
                                                $signs = $newLab->getEsignatures('left');
                                                if ($signs && mysqli_num_rows($signs) > 0) {
                                                    while ($sign = mysqli_fetch_assoc($signs)) {
                                                       $selec=$sign['id']==$_SESSION['loginid'] ? "selected":"";
                                                        echo '<option value="' . htmlspecialchars($sign['id']) . '" '.$selec.'>' . htmlspecialchars($sign['user_name']) . '</option>';
                                                    }
                                                }
                                            ?>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <label for="report_dispatch_right_signature">Right signature</label>
                                                <select class="form-control" name="report_dispatch[right_signature]"
                                                    id="report_dispatch_right_signature"
                                                    onchange="right_e_signature_hide_show($(this));">
                                                    <option value="" label=" "></option>
                                                    <?php
                    $signs = $newLab->getEsignatures('right');
                    if ($signs && mysqli_num_rows($signs) > 0) {
                        while ($sign = mysqli_fetch_assoc($signs)) {
                            echo '<option value="' . htmlspecialchars($sign['id']) . '">' . htmlspecialchars($sign['user_name']) . '</option>';
                        }
                    }
                    ?>
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label class="checkbox">
                                                <div class="left_e_sig">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="1"
                                                            checked="checked" name="report_dispatch[left_e_signature]"
                                                            id="report_dispatch_left_e_signature">
                                                        <label class="form-check-label"
                                                            for="report_dispatch_left_e_signature">
                                                            Print E signature.
                                                        </label>
                                                    </div>
                                                </div>
                                            </label>
                                        </td>
                                        <td>
                                            <label class="checkbox">
                                                <div class="right_e_sig">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="1"
                                                            checked="checked" name="report_dispatch[right_e_signature]"
                                                            id="report_dispatch_right_e_signature">
                                                        <label class="form-check-label"
                                                            for="report_dispatch_right_e_signature">
                                                            Print E signature.
                                                        </label>
                                                    </div>
                                                </div>
                                            </label>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <input type="hidden" name="dispatch_id" id="dispatch_id" value="0">
                    </form>
                </div>
                <div class="modal-footer">

                    <button type="submit" name="commit" value="Save" class="btn btn-success"
                        onclick="saveReportDispatch()" data-disable-with="Saving...">Save</button>
                    <a class="btn btn-secondary" data-dismiss="modal" href="#">Close</a>
                </div>
            </div>
        </div>
    </div>

    <!-- View Lab Test Modal -->
    <div class="modal fade" id="viewLabTestModal" tabindex="-1" role="dialog" aria-labelledby="viewLabTestModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewLabTestModalLabel">View Lab Test Result</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="viewLabTestModalBody">
                        <!-- Content will be loaded here via AJAX -->
                    </div>
                </div>
                <div class="modal-footer">
                    <?php if($_SESSION['role']=='Lab Admin' || $_SESSION['role']=='Admin' || $_SESSION['role']=='MRD') { ?>
                    <button type="button" class="btn btn-success" id="viewModalFinalizeBtn"
                        onclick="finalizeFromViewModal()" style="display:none;">Finalize</button>
                    <?php } ?>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

</body>

</html>