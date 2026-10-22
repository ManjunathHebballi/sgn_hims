<?php 
require_once(__DIR__ . '/model/session_config.php');

date_default_timezone_set("Asia/Kolkata");  
include('model/labClass.php');
    $newLab = new lab;
if(isset($_REQUEST['from']))
{
    $op_from=$_REQUEST['from'];
}
else{
    $op_from=date("Y-m-d");
}
if(isset($_REQUEST['to']))
{
    $op_to=$_REQUEST['to'];
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
if(isset($_REQUEST['collection']))
{
    $collection=$_REQUEST['collection'];
}
else{
        $collection=0;
}

if(isset($_REQUEST['type'])&& $_REQUEST['type']=='submit')
{
    $newLab->insertSampleCollect($_REQUEST['bill_no'],$_REQUEST['patient_id'],$_REQUEST['test_id'],$_REQUEST['visit_no']);
    echo "<script>window.location='sample_collection.php'</script>";
}

if(isset($_REQUEST['type'])&& $_REQUEST['type']=='bulk_collect')
{
    // print_r($_REQUEST);exit;
    if(isset($_REQUEST['selected_samples']) && is_array($_REQUEST['selected_samples'])) {
        foreach($_REQUEST['selected_samples'] as $sample) {
            $parts = explode('|', $sample);
            $bill_no = $parts[0];
            $patient_id = $parts[1];
            $test_ids = explode(',', $parts[2]);
            $visit_no = $parts[3];
            $newLab->insertSampleCollect($bill_no, $patient_id, $test_ids, $visit_no);
        }
        // exit;
        echo "<script>alert('Samples collected successfully!'); window.location='sample_collection.php'</script>";
    } else {
        echo "<script>alert('Please select at least one sample to collect'); window.location='sample_collection.php'</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Sample Collection</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">

    <link href="./css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>




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
                        <h4 class="text-uppercase"> Sample Collection </h4>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-12">
                        <div class="card">

                            <div class="card-body">
                                <h5 class="card-title">Select Criteria</h5>
                                <form>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">From Date</label>

                                                <input type="date" name="from" value="<?php echo date('Y-m-d'); ?>"
                                                    class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">To Date</label>
                                                <input type="date" name="to" value="<?php echo date('Y-m-d'); ?>"
                                                    class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"> Search </label>
                                                <input type="text" class="form-control" name="search" id="search"
                                                    Placeholder="Search By OPD No., Name, Mobile, address"
                                                    value="<?= $search?>">

                                            </div>
                                        </div>

                                                           <div class="col-md-3">
                                                               <div class="group-form">
                                                                   <label for="">Collection</label>
                                                                   <select name="collection" class="form-control" id="order_type">
                                           <option value="">All</option>
                                           <option value="0" <?php echo ($collection == '0') ? 'selected' : ''; ?>>Pending</option>
                                           <option value="1" <?php echo ($collection == '1') ? 'selected' : ''; ?>>Collected</option>
                                        </select>
                                                               </div>
                                                           </div>
                                        <div class="col-md-12">
                                            <button class="btn btn-success float-right">Search</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">

                    <div class="col-md-12">

                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="ml-4"> List</h5>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="button" id="bulkCollectBtn" class="btn btn-success float-right"
                                            onclick="bulkCollect()">
                                            <i class="fa fa-check-circle"></i> Collect Selected
                                        </button>
                                    </div>
                                </div>
                                <hr>

                                <div class="table-responsive">
                                    <form id="bulkCollectForm" method="POST" action="sample_collection.php">
                                        <input type="hidden" name="type" value="bulk_collect">
                                        <table id="datatable-buttons"
                                            class="table table-bordered table-hoverde display ">
                                            <thead class="thead-primary">
                                                <tr>
                                                    <th><input type="checkbox" id="selectAll"
                                                            onclick="toggleSelectAll(this)"></th>
                                                    <th>Sl No.</th>
                                                    <th scope="col">Lab No.</th>
                                                    <th scope="col">Order Date</th>
                                                    <th scope="col">Patient Name</th>
                                                    <th scope="col">Age/Gender</th>
                                                    <th scope="col">Total Inv. Count</th>
                                                    <th scope="col">Group Name</th>
                                                    <th scope="col">Sample</th>
                                                    <th scope="col">collected</th>
                                                    <th scope="col">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php     $sn=1;
                                            $lab=$newLab->labPatientResult($op_from,$op_to,$search,$collection);
                                            if(mysqli_num_rows($lab)>0){
                                             while($res=mysqli_fetch_assoc($lab))
                                            { 
                                          
                                            ?>
                                                <tr>
                                                    <td>
                                                        <?php if(strlen($res['sample_status']) <= 1) { ?>
                                                        <input type="checkbox" class="sample-checkbox"
                                                            name="selected_samples[]"
                                                            value="<?= $res['bill_no']?>|<?= $res['ipd_no'] ? $res['ipd_no'] : $res['id']?>|<?= $res['testid']?>|<?= $res['visit_no'] ?>">
                                                        <?php } else { echo '-'; } ?>
                                                    </td>
                                                    <td><?= $sn?></td>
                                                    <td><?= $res['visit_no']?></td>
                                                    <td><?= date('d.m.Y',strtotime($res['date']))?></td>
                                                    <td><?= $res['name']?></td>
                                                    <td><?= $res['age'].'/'.$res['gender']?></td>

                                                    <td><?= $res['testcount']?></td>
                                                    <td><?= $res['testname']?></td>
                                                    <td><?= $res['samples']?></td>
                                                    <td><?= $res['sample_status']?></td>
                                                    <?php $arr=explode(',',$res['testid']);?>

                                                    <td>
                                                        <?php if(strlen($res['sample_status'])>1) {
                                                        ?>
                                                        <a href="sample_collected_barcode.php?name=<?= $res['name'] ?>&visit_no=<?= $res['visit_no'] ?>&age=<?= $res['age']?>&gender=<?= $res['gender']?>"
                                                            class="badge badge-primary">Print Barcode</a>
                                                    </td>
                                                    <?php
                                                    } 
                                                    else{ ?>
                                                    <a href="sample_collection.php?type=submit&bill_no=<?= $res['bill_no']?>&patient_id=<?= $res['ipd_no'] ? $res['ipd_no'] : $res['id']?>&<?= http_build_query(array('test_id' => $arr));?>&visit_no=<?= $res['visit_no'] ?>"
                                                        class="badge badge-success" onclick="disableBulkCollectBtn()">Collect</a></td>
                                                    <?php } ?>
                                                </tr>
                                                <?php $sn++; } 
                                            
                                            }else {
                                            ?>
                                                <tr>
                                                    <td colspan="15" class="text-center">
                                                        <img src="assets/addnewitem.svg" alt="No data" width=200> <br>
                                                        <br>
                                                        <span class="text-success bolds"><i
                                                                class="fa fa-arrow-left"></i> Add new record or search
                                                            with different criteria.</span>
                                                    </td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>





                                        </table>
                                    </form>
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
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
    // Toggle all checkboxes
    function toggleSelectAll(source) {
        var checkboxes = document.querySelectorAll('.sample-checkbox');
        for (var i = 0; i < checkboxes.length; i++) {
            checkboxes[i].checked = source.checked;
        }
        updateBulkCollectButton();
    }

    // Update bulk collect button state
    function updateBulkCollectButton() {
        var checkboxes = document.querySelectorAll('.sample-checkbox:checked');
        var btn = document.getElementById('bulkCollectBtn');
        if (checkboxes.length > 0) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa fa-check-circle"></i> Collect Selected (' + checkboxes.length + ')';
        } else {
            btn.disabled = true;
            btn.innerHTML = '<i class="fa fa-check-circle"></i> Collect Selected';
        }
    }

    // Bulk collect function
    function bulkCollect() {
        var checkboxes = document.querySelectorAll('.sample-checkbox:checked');
        if (checkboxes.length === 0) {
            alert('Please select at least one sample to collect');
            return false;
        }

        if (confirm('Are you sure you want to collect ' + checkboxes.length + ' sample(s)?')) {
            document.getElementById('bulkCollectBtn').disabled = true;
            document.getElementById('bulkCollectForm').submit();
        }
    }

    // Add change event listeners to all checkboxes
    document.addEventListener('DOMContentLoaded', function() {
        var checkboxes = document.querySelectorAll('.sample-checkbox');
        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', updateBulkCollectButton);
        });
        updateBulkCollectButton();
    });
    function disableBulkCollectBtn() {
        document.getElementById('bulkCollectBtn').disabled = true;
    }
    </script>





</body>

</html>