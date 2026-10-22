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
if(isset($_REQUEST['dispatch']))
{
    $dispatch=$_REQUEST['dispatch'];
}
else{
        $dispatch=0;
}

if(isset($_REQUEST['type'])&& $_REQUEST['type']=='submit')
{
    $newLab->updateSampleDispatch($_REQUEST['id']);
    echo "<script>window.location='sample_dispatch.php'</script>";
}

if(isset($_REQUEST['type'])&& $_REQUEST['type']=='bulk_dispatch')
{
    if(isset($_REQUEST['selected_samples']) && is_array($_REQUEST['selected_samples'])) {
        $ok = 0; $refused = 0;
        foreach($_REQUEST['selected_samples'] as $sample_id) {
            if ($newLab->updateSampleDispatch($sample_id)) { $ok++; } else { $refused++; }
        }
        $msg = $refused > 0 ? "Dispatched $ok. Skipped $refused already dispatched or not yet collected." : 'Samples dispatched successfully!';
        echo "<script>alert('$msg'); window.location='sample_dispatch.php'</script>";
    } else {
        echo "<script>alert('Please select at least one sample to dispatch'); window.location='sample_dispatch.php'</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Sample Dispatch</title>
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

    <div aria-live="polite" id="notification" aria-atomic="true">

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
                            <h4 class="text-uppercase"> Sample Dispatch </h4>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">

                                <div class="card-body">
                                    <h5 class="card-title">Select Criteria</h5>
                                    <form method="post">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Search (Name/Mobile/Bill No.)</label>
                                                    <input type="text" class="form-control" name="search" id="search"
                                                        value="<?= $search?>">

                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">From Date</label>

                                                    <input type="date" name="from" value="<?php echo date('Y-m-d')?>"
                                                        value="<?= $op_from?>" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">To Date</label>
                                                    <input type="date" name="to" value="<?php echo date('Y-m-d')?>"
                                                        value="<?= $op_to?>" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">Dispatch</label>
                                                    <select  name="dispatch" class="form-control">
                                                        <option value="">All</option>
                                                        <option value="1" <?= $dispatch=='1'?'selected':''?>>Dispatched</option>
                                                        <option value="0" <?= $dispatch=='0'?'selected':''?>>Not Dispatched</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12 text-right">
                                                <button type="submit"
                                                    class="btn btn-success">Search</button>
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
                                            <h5 class="ml-4">Collected Samples Ready for Dispatch</h5>
                                        </div>
                                        <div class="col-md-6">
                                            <button type="button" id="bulkDispatchBtn"
                                                class="btn btn-success float-right" onclick="bulkDispatch()">
                                                <i class="fa fa-send"></i> Dispatch Selected
                                            </button>
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="table-responsive">
                                        <form id="bulkDispatchForm" method="POST" action="sample_dispatch.php">
                                            <input type="hidden" name="type" value="bulk_dispatch">
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
                                                        <th scope="col">Total Tests</th>
                                                        <th scope="col">Test Name</th>
                                                        <th scope="col">Sample Type</th>
                                                        <th scope="col">Collected By</th>
                                                        <th scope="col">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php     $sn=1;
                                            $lab=$newLab->getCollectedSamples($op_from,$op_to,$search,$dispatch);
                                            if(mysqli_num_rows($lab)>0){
                                             while($res=mysqli_fetch_assoc($lab))
                                            { 
                                          
                                            ?>
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" class="sample-checkbox"
                                                                name="selected_samples[]"
                                                                value="<?= $res['sample_id']?>">
                                                        </td>
                                                        <td><?= $sn?></td>
                                                        <td><?= $res['visit_no']?></td>
                                                        <td><?= date('d.m.Y',strtotime($res['date']))?></td>
                                                        <td><?= $res['name']?></td>
                                                        <td><?= $res['age'].'/'.$res['gender']?></td>

                                                        <td><?= $res['testcount']?></td>
                                                        <td><?= $res['testname']?></td>
                                                        <td><?= $res['samples']?></td>
                                                        <td><?php  echo $res['sample_collected_by']; ?></td>

                                                        <td>
                                                            <?php if($res['is_sample_dispatch']==0){?>
                                                            <a href="sample_dispatch.php?type=submit&id=<?= $res['sample_id']?>"
                                                                class="badge badge-success" onclick="return confirm('Dispatch this sample?')">Dispatch</a>
                                                        <?php } else{?>
                                                            <span class="badge badge-secondary">Dispatched</span>   
                                                        <?php } ?>
                                                            </td>
                                                    </tr>
                                                    <?php $sn++; } 
                                            
                                            }else {
                                            ?>
                                                    <tr>
                                                        <td colspan="15" class="text-center">
                                                            <img src="assets/addnewitem.svg" alt="No data" width=200>
                                                            <br>
                                                            <br>
                                                            <span class="text-success bolds"><i
                                                                    class="fa fa-arrow-left"></i> No samples ready for dispatch or
                                                                search with different criteria.</span>
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
        // Bulk Dispatch Functions
        function toggleSelectAll(source) {
            var checkboxes = document.querySelectorAll('.sample-checkbox');
            for (var i = 0; i < checkboxes.length; i++) {
                checkboxes[i].checked = source.checked;
            }
            updateBulkDispatchButton();
        }

        function updateBulkDispatchButton() {
            var checkboxes = document.querySelectorAll('.sample-checkbox:checked');
            var btn = document.getElementById('bulkDispatchBtn');
            if (checkboxes.length > 0) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa fa-send"></i> Dispatch Selected (' + checkboxes.length + ')';
            } else {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa fa-send"></i> Dispatch Selected';
            }
        }

        function bulkDispatch() {
            var checkboxes = document.querySelectorAll('.sample-checkbox:checked');
            if (checkboxes.length === 0) {
                alert('Please select at least one sample to dispatch');
                return false;
            }

            if (confirm('Are you sure you want to dispatch ' + checkboxes.length + ' sample(s)?')) {
                document.getElementById('bulkDispatchForm').submit();
            }
        }

        // Add change event listeners to all checkboxes
        document.addEventListener('DOMContentLoaded', function() {
            var checkboxes = document.querySelectorAll('.sample-checkbox');
            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', updateBulkDispatchButton);
            });
            updateBulkDispatchButton();
        });
        </script>




</body>

</html>
