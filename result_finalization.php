<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/labClass.php');
$newOP = new lab;
if(isset($_REQUEST['op_from']))
{
    $op_from=$_REQUEST['op_from'];
}
else{
    $op_from=date("Y-m-d");
}
if(isset($_REQUEST['op_to']))
{
    $op_to=$_REQUEST['op_to'];
}
else{
    $op_to=date("Y-m-d");
}
if(isset($_REQUEST['search']))
{
    $search=$_REQUEST['search'];
}
else{
        $search='';
}
if(isset($_REQUEST['finalization']))
{
    $finalize=$_REQUEST['finalization'];
}
else{
    $finalize='0';
}
$redirect_query = http_build_query(array(
    'op_from' => $op_from,
    'op_to' => $op_to,
    'search' => $search
));
if(isset($_REQUEST['type'])&& $_REQUEST['type']=='submit')
{
    $done = $newOP->updateResultFinalization($_REQUEST['id']);
    if (!$done) {
        echo "<script>alert('Could not finalize: no result has been entered for this sample yet.');</script>";
    }
    echo "<script>window.location='result_finalization.php?$redirect_query'</script>";
}
if(isset($_REQUEST['type'])&& $_REQUEST['type']=='bulk_finalization')
{
    $ok = 0; $refused = 0;
    if(isset($_REQUEST['sample_ids']) && is_array($_REQUEST['sample_ids'])) {
        foreach($_REQUEST['sample_ids'] as $sample_id) {
            if ($newOP->updateResultFinalization($sample_id)) { $ok++; } else { $refused++; }
        }
    }
    if ($refused > 0) {
        echo "<script>alert('Finalized $ok. Skipped $refused with no result entered.');</script>";
    }
    echo "<script>window.location='result_finalization.php?$redirect_query'</script>";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Lab Result</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" ></script>
    <!-- Datatable -->
    <link href="./vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">

    <style>
     #notification {
            position: absolute;
            top: 90px;
           
            right:2px;
            z-index: 99;
        }
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
    <div class="row mb-3">
        <div class="col-md-12">
            <h4 class="text-uppercase">DIRECT PATIENT </h4>
        </div>
    </div>

<div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
  <div class="row">
                <div class="col-md-12">
                    <h4>Select Criteria</h4><br>
                 
                </div>
            </div> 
<form action="#" href="#pills-home" method="POST">
    <div class="row">
        <div class="col-md-3">
           <div class="form-group">
                  <label for="exampleInputEmail1"> From Date </label>
                  <input type="date" class="form-control"  name="op_from" id="op_from" required value="<?= $op_from?>">
                  
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1"> To Date  </label>
                   <input type="date" class="form-control"  name="op_to" id="op_to" required value="<?= $op_to?>">
                   
             </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1"> Search  </label>
                   <input type="text" class="form-control"  name="search" id="search" Placeholder="Search By OPD No., Name, Mobile, address" value="<?= $search?>">
                   
             </div>
        </div>
         <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1">Finalization</label><br>
                   <select class="form-control" name="finalization" id="finalization">
                       <option value="">All</option>
                       <option value="1" <?= isset($finalize) && $finalize=='1' ? 'selected' : '' ?>>Finalized</option>
                       <option value="0" <?= isset($finalize) && $finalize=='0' ? 'selected' : '' ?>>Not Finalized</option>
                   </select>
             </div>
        </div>
    </div>
    
    <div class="row">
            <div class="col-md-12">
            <button type="submit" class="btn btn-success  float-right btn-rounded"  name="submit"><span><i class="bi bi-search"></i></span> Search</button>
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
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <h4 class="card-title mb-0">Direct List</h4>
                                <?php if($_SESSION['role']=='Lab Admin' || $_SESSION['role']=='Admin' || $_SESSION['role']=='MRD') { ?>
                                <button type="button" id="bulkFinalizeBtn" class="btn btn-success btn-sm"
                                    onclick="bulkFinalize()" disabled>
                                    Finalize Selected (0)
                                </button>
                                <?php } ?>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example" class="table table-striped display text-center">
                                        <thead>
                                            <tr>
                                                <th width="40px">
                                                    <input type="checkbox" id="selectAllFinal"
                                                        onclick="toggleSelectAllFinal(this)">
                                                </th>
                                                <th>Sl NO</th>
                                                <th>Visit Data</th>
                                                <th>Name</th>
                                                <th>Gender</th>
                                                <th>Address</th>
                                                <th>Mobile</th>
                                                <th>testname</th>
                                                <th>Action</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            <?php $sn=1;
                                            $lab=$newOP->labPatientResult4($op_from,$op_to,$search,$finalize);
                                             while($res=mysqli_fetch_assoc($lab))
                                            {
                                                $patient_id = $res['ipd_no'] ? $res['ipd_no'] : $res['id'];
                                                $testDetails = $newOP->getLabTestDetails($patient_id, $res['bill_no'], $res['testid'], $res['gender']);
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
                                            <tr>
                                                <td>
                                                    <?php if($_SESSION['role']=='Lab Admin' || $_SESSION['role']=='Admin' || $_SESSION['role']=='MRD') { ?>
                                                    <?php if($res['is_finalized']==0) { ?>
                                                    <input type="checkbox" class="finalize-checkbox"
                                                        data-sampleid="<?= $res['sample_id']?>"
                                                        onchange="updateFinalizeButton()">
                                                    <?php } ?>
                                                    <?php } ?>
                                                </td>
                                                <td><?= $sn?></td>
                                                <td><?= date("d.m.Y",strtotime($res['date']))?></td>
                                                <td><?= $res['name']?></td>
                                                <td><?= $res['gender']?></td>
                                                <td><?= $res['address']?></td>
                                                 <td><?= $res['mobile']?></td>
                                                 <td class="test-name-cell" data-toggle="popover" data-html="true"
                                                     data-content="<?= $popoverData ?>"
                                                     title="<?= htmlspecialchars($res['testname'], ENT_QUOTES) ?>"
                                                     style="cursor: pointer;">
                                                     <?= $res['testname']?>
                                                 </td>
                                                <td> 
                                              <?php if(($res['is_finalized']==0) && ($_SESSION['role']=='Lab Admin' || $_SESSION['role']=='Admin' || $_SESSION['role']=='MRD')) {?>
                                               <a href="lab_report.php?id=<?= $res['ipd_no'] ? $res['ipd_no'] : $res['id']?>&testid=<?= $res['testid']?>&type=direct&bill_no=<?= $res['bill_no']?>" class="badge badge-success">View</a>
                                               <a href="result_finalization.php?type=submit&id=<?= $res['sample_id']?>&<?= $redirect_query?>" class="badge badge-primary">Finalize</a>
                                               <?php } ?>
                                                <?php if($res['is_finalized']==1) {?>
                                                <a href="lab_print1.php?id=<?= $res['ipd_no'] ? $res['ipd_no'] : $res['id']?>&bill=<?= $res['lab_count']?>&testid=<?= $res['test_id']?>" target="_blank" title="Add Re-Entry" class="badge badge-print badge-primary"><i class="bi-printer" style="font-size:15px"></i> </a>
                                                <?php } ?>
                                                
                                                </td>
                                            </tr>
                                           <?php $sn++; } ?>
                                            </tbody>
                                            </table>   
                                            </div>  
                                            </div>
                                            </div></div></div>
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

    <script>
    function toggleSelectAllFinal(checkbox) {
        var checkboxes = document.querySelectorAll('.finalize-checkbox');
        for (var i = 0; i < checkboxes.length; i++) {
            checkboxes[i].checked = checkbox.checked;
        }
        updateFinalizeButton();
    }

    function updateFinalizeButton() {
        var checkboxes = document.querySelectorAll('.finalize-checkbox:checked');
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
        var checkboxes = document.querySelectorAll('.finalize-checkbox:checked');
        var sampleIds = [];

        for (var i = 0; i < checkboxes.length; i++) {
            sampleIds.push(checkboxes[i].getAttribute('data-sampleid'));
        }

        if (sampleIds.length === 0) {
            alert('Please select at least one test to finalize');
            return false;
        }

        if (confirm('Are you sure you want to finalize ' + sampleIds.length + ' selected test(s)?')) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = 'result_finalization.php';

            var typeInput = document.createElement('input');
            typeInput.type = 'hidden';
            typeInput.name = 'type';
            typeInput.value = 'bulk_finalization';
            form.appendChild(typeInput);

            var fromInput = document.createElement('input');
            fromInput.type = 'hidden';
            fromInput.name = 'op_from';
            fromInput.value = '<?= $op_from ?>';
            form.appendChild(fromInput);

            var toInput = document.createElement('input');
            toInput.type = 'hidden';
            toInput.name = 'op_to';
            toInput.value = '<?= $op_to ?>';
            form.appendChild(toInput);

            var searchInput = document.createElement('input');
            searchInput.type = 'hidden';
            searchInput.name = 'search';
            searchInput.value = '<?= htmlspecialchars($search, ENT_QUOTES) ?>';
            form.appendChild(searchInput);

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

    $(document).ready(function() {
        var popoverOptions = {
            html: true,
            sanitize: false,
            trigger: 'hover focus',
            placement: 'left',
            fallbackPlacement: ['right'],
            boundary: 'window',
            container: 'body',
            delay: { show: 100, hide: 150 },
            offset: '12,10',
            template: '<div class="popover test-details-popover" role="tooltip"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>'
        };

        // Initial bind for page 1
        $('.test-name-cell').popover(popoverOptions);

        // Rebind on every DataTable page change / draw (new rows injected into DOM)
        $('#example').on('draw.dt', function() {
            $('.test-name-cell').popover(popoverOptions);
        });
    });
    </script>


  

</body>

</html>