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
if(isset($_REQUEST['acknowledge']))
{
    $acknowledge=$_REQUEST['acknowledge'];
}
else{
        $acknowledge='';
}
if(isset($_REQUEST['type'])&& $_REQUEST['type']=='submit')
{
    $newLab->updateSampleCollect($_REQUEST['id']);
    echo "<script>window.location='sample_acknowledge.php'</script>";
}

if(isset($_REQUEST['type'])&& $_REQUEST['type']=='bulk_acknowledge')
{
    if(isset($_REQUEST['selected_samples']) && is_array($_REQUEST['selected_samples'])) {
        $ok = 0; $refused = 0;
        foreach($_REQUEST['selected_samples'] as $sample_id) {
            if ($newLab->updateSampleCollect($sample_id)) { $ok++; } else { $refused++; }
        }
        $msg = $refused > 0 ? "Acknowledged $ok. Skipped $refused already acknowledged or not yet dispatched." : 'Samples acknowledged successfully!';
        echo "<script>alert('$msg'); window.location='sample_acknowledge.php'</script>";
    } else {
        echo "<script>alert('Please select at least one sample to acknowledge'); window.location='sample_acknowledge.php'</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Sample Acknowledge</title>
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
                            <h4 class="text-uppercase"> Sample Acknowledge </h4>
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
                                                    <label for="exampleInputEmail1"> Scan Barcode </label>
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
                                                                   <div class="group-form">
                                                                       <label for="">Acknowledge</label>
                                                                       <select name="acknowledge" class="form-control" id="order_type">
                                               <option value="">All</option>
                                               <option value="0" <?= ($acknowledge==='0') ? 'selected' : '' ?>>Pending</option>
                                               <option value="1" <?= ($acknowledge==='1') ? 'selected' : '' ?>>Acknowledged</option>
                                            </select>
                                                                   </div>
                                                               </div>
                                            <div class="col-md-12">
                                                <button type="submit"
                                                    class="btn btn-success float-right">Search</button>
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
                                            <h5 class="ml-4">Test List</h5>
                                        </div>
                                        <div class="col-md-6">
                                            <button type="button" id="bulkAcknowledgeBtn"
                                                class="btn btn-success float-right" onclick="bulkAcknowledge()">
                                                <i class="fa fa-check-circle"></i> Acknowledge Selected
                                            </button>
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="table-responsive">
                                        <form id="bulkAcknowledgeForm" method="POST" action="sample_acknowledge.php">
                                            <input type="hidden" name="type" value="bulk_acknowledge">
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
                                                        <th scope="col">Acknowledged By</th>
                                                        <th scope="col">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php     $sn=1;
                                            $lab=$newLab->labPatientResult2($op_from,$op_to,$search,$acknowledge);
                                            if(mysqli_num_rows($lab)>0){
                                             while($res=mysqli_fetch_assoc($lab))
                                            { 
                                          
                                            ?>
                                                    <tr>
                                                        <td>
                                                            <?php if($res['is_sample_acknowledge'] != 1 && $res['is_outsourced'] != 1) { ?>
                                                            <input type="checkbox" class="sample-checkbox"
                                                                name="selected_samples[]"
                                                                value="<?= $res['sample_id']?>">
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
                                                        <td><?php  echo $res['sample_acknowledged_by']; ?></td>
                                                        <?php $arr=explode(',',$res['testid']);?>

                                                        <td>
                                                            <?php
                                                    if($res['is_outsourced']==1)
                                                    {
                                                        echo "Test Outsourced";
                                                    }
                                                    elseif($res['is_sample_acknowledge']==1 ) {
                                                       
                                                        // Show Outsource Lab button only after acknowledge
                                                    ?>
                                                            <button type="button"
                                                                class="btn btn-sm btn-warning btn-outsource-lab"
                                                                data-visit_no="<?= htmlspecialchars($res['visit_no']) ?>"
                                                                data-testid="<?= htmlspecialchars($res['testid']) ?>"
                                                                data-testname="<?= htmlspecialchars($res['testname']) ?>"
                                                                data-sample_id="<?= htmlspecialchars($res['sample_id']) ?>">Outsource
                                                                Lab</button>
                                                            <?php 
                                                    } 
                                                    
                                                    else { ?>
                                                            <a href="sample_acknowledge.php?type=submit&id=<?= $res['sample_id']?>"
                                                                class="badge badge-success">Acknowledge</a>
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
                                                                    class="fa fa-arrow-left"></i> Add new record or
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

        <!-- Outsource Lab Modal -->
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
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
        $(document).on('click', '.btn-outsource-lab', function() {
            var sample_id = $(this).data('sample_id');
            $('#modal_sample_id').val(sample_id);
            $('#outsourceLabModal').modal('show');
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

        // Bulk Acknowledge Functions
        function toggleSelectAll(source) {
            var checkboxes = document.querySelectorAll('.sample-checkbox');
            for (var i = 0; i < checkboxes.length; i++) {
                checkboxes[i].checked = source.checked;
            }
            updateBulkAcknowledgeButton();
        }

        function updateBulkAcknowledgeButton() {
            var checkboxes = document.querySelectorAll('.sample-checkbox:checked');
            var btn = document.getElementById('bulkAcknowledgeBtn');
            if (checkboxes.length > 0) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa fa-check-circle"></i> Acknowledge Selected (' + checkboxes.length + ')';
            } else {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa fa-check-circle"></i> Acknowledge Selected';
            }
        }

        function bulkAcknowledge() {
            var checkboxes = document.querySelectorAll('.sample-checkbox:checked');
            if (checkboxes.length === 0) {
                alert('Please select at least one sample to acknowledge');
                return false;
            }

            if (confirm('Are you sure you want to acknowledge ' + checkboxes.length + ' sample(s)?')) {
                document.getElementById('bulkAcknowledgeForm').submit();
            }
        }

        // Add change event listeners to all checkboxes
        document.addEventListener('DOMContentLoaded', function() {
            var checkboxes = document.querySelectorAll('.sample-checkbox');
            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', updateBulkAcknowledgeButton);
            });
            updateBulkAcknowledgeButton();
        });
        </script>




</body>

</html>