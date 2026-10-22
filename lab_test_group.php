<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/labClass.php');

$lab = new lab;
$filter_department = isset($_GET['filter_department']) ? $_GET['filter_department'] : '';
$filter_name = isset($_GET['filter_name']) ? $_GET['filter_name'] : '';
$res = $lab->getLabTests($filter_department, $filter_name);
if(isset($_POST['deleteid'])) {
    $lab = new lab;
    $deleteid = intval($_POST['deleteid']);
    $lab->deleteLabTest($deleteid);
    exit;
}
if(isset($_REQUEST['submit']))
{
    if($_REQUEST['submit']=='Submit')
    {
       $id= $lab->insertLabTest(
            $_REQUEST['testname'],
            $_REQUEST['testprice'],
            $_REQUEST['type'],
            isset($_REQUEST['sample_custom']) && $_REQUEST['sample'] == '__custom__' ? $_REQUEST['sample_custom'] : $_REQUEST['sample'],
            isset($_REQUEST['tat_time']) ? $_REQUEST['tat_time'] : '',
            isset($_REQUEST['is_nabl']) ? 1 : 0
        );
         echo "<script>window.location='addTest1.php?id=$id&name=$_REQUEST[testname]'</script>";
    }
    elseif($_REQUEST['submit']=='Update')
    {
        $lab->updateLabTest(
            $_REQUEST['editid'],
            $_REQUEST['testname'],
            $_REQUEST['testprice'],
            $_REQUEST['type'],
            isset($_REQUEST['sample_custom']) && $_REQUEST['sample'] == '__custom__' ? $_REQUEST['sample_custom'] : $_REQUEST['sample'],
            isset($_REQUEST['tat_time']) ? $_REQUEST['tat_time'] : '',
            isset($_REQUEST['is_nabl']) ? 1 : 0
        );
         header('location:lab_test_group.php');
    }
   
}
if(isset($_REQUEST['editid']))
{
    $row_res = $lab->getLabTestById($_REQUEST['editid']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Add Test </title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" ></script>
        <!-- Sweet Alert-->
     <link href="css/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
     



<!-- Summernote CSS & JS -->
<!--<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">-->

<style>
     #notification {
            position: absolute;
            top: 90px;
           
            right:2px;
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

<div aria-live="polite" id="notification" aria-atomic="true">
<?php if(isset($_REQUEST['success']))
{ ?>
    
<div class="alert alert-success solid alert-dismissible fade show">
                                    <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span>
                                    </button>
                                    <strong>Success!</strong>Test inserted
                                </div>
                         <?php   }
                            ?>
</div>

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
        <div class="col-md-12 d-flex justify-content-between align-items-center">
            <h4 class="text-uppercase">Add Test </h4>
            <button class="btn btn-success" id="toggleFormBtn" type="button" data-toggle="collapse" data-target="#testFormCollapse" aria-expanded="false" aria-controls="testFormCollapse">Add New Test </button>
        </div>
    </div>
    
    <div class="collapse" id="testFormCollapse">
        <div class="card card-body mb-3">
            <form method="post" action="" name="frmtreat" id="testForm">
                <input type="hidden" name="editid" id="editid">
                <div class="row">
                    <div class="col-sm-3 col-xs-12">
                        <div class="form-group">
                            <label for="type">Test Department</label>
                            <div class="form-line">
                                <select class="form-control" name="type" id="type" required>
                                    <option value="">Select Type</option>
                                    <?php 
                                    $data=$lab->getLabDepartment();
                                    while($row1=mysqli_fetch_assoc($data))
                                    {
                                    ?>
                                    <option><?= $row1['dept_name']?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3 col-xs-12">
                        <div class="form-group">
                            <label for="testname">Test Name</label>
                            <div class="form-line">
                                <input type="text" class="form-control" name="testname" id="testname" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3 col-xs-12">
                        <div class="form-group">
                            <label for="testprice">Test Cost</label>
                            <div class="form-line">
                                <input type="text" class="form-control" name="testprice" id="testprice" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3 col-xs-12">
                        <div class="form-group">
                            <label for="sample">Test Sample</label>
                            <div class="form-line">
                                <select class="form-control" name="sample" id="sample" required>
                                    <option value="">Select Sample</option>
                                    <option value='Blood'>Blood</option>
                                    <option value='Tissue biopsy'>Tissue biopsy</option>
                                    <option value='Bone marrow'>Bone marrow</option>
                                    <option value='Saliva'>Saliva</option>
                                    <option value='Urine'>Urine</option>
                                    <option value='Stool'>Stool</option>
                                    <option value='Sputum'>Sputum</option>
                                    <option value='Semen'>Semen</option>
                                    <option value='Serum'>Serum</option>
                                    <option value='Oral fluid'>Oral fluid</option>
                                    <option value='Sweat'>Sweat</option>
                                    <option value='Amniotic fluid'>Amniotic fluid</option>
                                    <option value='Cerebrospinal fluid (CSF)'>Cerebrospinal fluid (CSF)</option>
                                    <option value='Other'>Other</option>
                                    <option value='__custom__'>Custom...</option>
                                </select>
                                <input type="text" class="form-control mt-2" name="sample_custom" id="sample_custom" placeholder="Enter custom sample" style="display:none;" />

                                
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3 col-xs-12">
                        <div class="form-group">
                            <label for="tat_time">TAT Time (Hours)</label>
                            <div class="form-line">
                                <input type="number" class="form-control" name="tat_time" id="tat_time" placeholder="Enter turnaround time" step="0.5" min="0">
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-3 col-xs-12 mb-3">
                        <div class="form-group">
                            <label class="d-block mb-2">&nbsp;</label>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="isNabl" name="is_nabl" value="1">
                                <label class="custom-control-label" for="isNabl">This test comes under NABL</label>
                            </div>
                        </div>
                    </div>
                    </div>
                    <div class="col-sm-12">
                        <input type="submit" name="submit" id="submitBtn" value="Submit" class="btn btn-success float-right">
                        <button type="button" class="btn btn-secondary float-right mr-2" id="closeFormBtn">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <h5 class="ml-4">Test List</h5>
                    </div>
                    <form method="get" id="filterForm">
    <div class="row mb-3">
        <div class="col-md-3">
            <label for="filter_department">Department</label>
            <select class="form-control" name="filter_department" id="filter_department">
                <option value="">All Departments</option>
                <?php 
                                    $data=$lab->getLabDepartment();
                                    while($row1=mysqli_fetch_assoc($data))
                                    {
                                    ?>
                                    <option <?php if($_REQUEST['filter_department']==$row1['dept_name']) echo 'selected'; ?>><?= $row1['dept_name']?></option>
                                    <?php } ?>

            </select>
        </div>
        <div class="col-md-3">
            <label for="filter_name">Test Name</label>
            <input type="text" class="form-control" name="filter_name" id="filter_name" placeholder="Search by name" value="<?= isset($_GET['filter_name']) ? htmlspecialchars($_GET['filter_name']) : '' ?>">
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="lab_test_group.php" class="btn btn-secondary ml-2">Reset</a>
        </div>
    </div>
</form>
                    <hr>

                    <div class="table-responsive">
                    <table id="datatable-buttons" class="table table-bordered table-hoverde display text-center">
                                    <thead class="thead-dark">
                                    <tr >
                                                <th>Sl NO</th>
                                                <th>Department</th>
                                                <th>Test Name</th>
                                                
                                                <th>Test Cost</th>
                                                <th>Samples</th>
                                                <th>TAT (Hours)</th>
                                                <th>Action</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody class="text-center text-black">
                                        <?php $sn=1; while($row=mysqli_fetch_assoc($res)){?>
                                            <tr>
                                                <td><?= $sn?></td>
                                                <td><?= $row['testname_header']?></td>
                                                <td><?= $row['testname']?></td>
                                                <td><?= $row['price']?></td>
                                                <td><?= $row['samples']?></td>
                                                <td><?= isset($row['tat_time']) && $row['tat_time'] ? $row['tat_time'] : '-'?></td>
                                                <td><button type="button" class="btn btn-primary btn-sm editBtn"
    data-id="<?= $row['testid']?>"
    data-type="<?= htmlspecialchars($row['testname_header'])?>"
    data-testname="<?= htmlspecialchars($row['testname'])?>"
    data-testprice="<?= htmlspecialchars($row['price'])?>"
    data-sample="<?= htmlspecialchars($row['samples'])?>"
    data-tat="<?= htmlspecialchars(isset($row['tat_time']) ? $row['tat_time'] : '')?>"
    data-nabl="<?= $row['is_nabl'] ?>"
>Edit</button> 
<button type="button" class="btn btn-danger btn-sm deleteBtn" data-id="<?= $row['testid']?>">Delete</button>
<a href="addTest1.php?id=<?= urlencode($row['testid']) ?>&name=<?= urlencode($row['testname']) ?>"><span class="btn btn-success btn-sm">Parameter</span> </a></td>


                                               </tr>
                                           
                                             <?php $sn++; } ?>                      
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

<script>
   
    window.setTimeout(function() {
    $(".alert").fadeTo(500, 0).slideUp(500, function(){
        $(this).remove(); 
    });
}, 4000);



</script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<!-- Bootstrap 4 JS + Popper -->
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- Summernote JS -->
<!--<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>-->

<!-- Summernote Initialization -->
<script>
//   $(document).ready(function() {
//     $('#summernote').summernote({
//       height: 200,
//       placeholder: 'Start typing here...',
//     });
//   });
</script>


<!-- Sweet Alerts js -->
<script src="css/sweetalert2/sweetalert2.min.js"></script>

<!-- Sweet alert init js-->
<script src="js/sweet-alerts.init.js"></script>
<script>
$(document).ready(function() {
    // $('#summernote').summernote({
    //     height: 200,
    //     placeholder: 'Start typing here...'
    // });
    // Add Test button
    $('#toggleFormBtn').click(function() {
        $('#testForm')[0].reset();
        $('#editid').val('');
        $('#submitBtn').val('Submit');
        $('#testFormCollapse').collapse('show');
    });
    // Edit button
    $(document).on('click', '.editBtn', function() {
        $('#editid').val($(this).data('id'));
        $('#type').val($(this).data('type'));
        $('#testname').val($(this).data('testname'));
        $('#testprice').val($(this).data('testprice'));
        $('#sample').val($(this).data('sample'));
        $('#tat_time').val($(this).data('tat'));
        $('#isNabl').prop('checked', $(this).data('nabl') == 1);
        $('#submitBtn').val('Update');
        $('#testFormCollapse').collapse('show');
        $('html, body').animate({scrollTop: $('#testFormCollapse').offset().top - 100}, 500);
    });
    // Close button
    $('#closeFormBtn').click(function() {
        $('#testFormCollapse').collapse('hide');
    });
    $(document).on('click', '.deleteBtn', function() {
    var testid = $(this).data('id');
    if(confirm('Are you sure you want to delete this test ?')) {
        $.ajax({
            url: '', // same page
            type: 'POST',
            data: { deleteid: testid },
            success: function(response) {
                location.reload();
            },
            error: function() {
                alert('Failed to delete test .');
            }
        });
    }
});
});
                                $(document).ready(function() {
                                    $('#sample').change(function() {
                                        if ($(this).val() === '__custom__') {
                                            $('#sample_custom').show().prop('required', true);
                                        } else {
                                            $('#sample_custom').hide().prop('required', false);
                                        }
                                    });

                                    // On form submit, if custom, set value to select
                                    $('#testForm').submit(function(e) {
                                        if ($('#sample').val() === '__custom__') {
                                            var customVal = $('#sample_custom').val().trim();
                                            if (customVal !== '') {
                                                // Set the custom value to the select so it gets submitted
                                                $('<input>').attr({
                                                    type: 'hidden',
                                                    name: 'sample',
                                                    value: customVal
                                                }).appendTo('#testForm');
                                            }
                                        }
                                    });
                                });
                                </script>

</body>

</html>

