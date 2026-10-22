<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/dbconnect.php');

$newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        

if(isset($_REQUEST['submit']))
{
    // print_r($_REQUEST);exit;
    
      if($_REQUEST['submit']=='Submit')
    {
    $query = "INSERT INTO `labtest_group` (
        `labid`, 
        `test_name`, 
        `units`, 
        `field`, 
        `formula`, 
        `method`, 
        `normal_range`, 
        `male_range_min`, 
        `male_range_max`, 
        `female_range_min`, 
        `female_range_max`, 
        `child_range_min`, 
        `child_range_max`, 
        `head`,
        `antibiotics`
    ) VALUES (
        '{$_REQUEST['id']}',
        '{$_REQUEST['testname']}',
        '{$_REQUEST['units']}',
        '{$_REQUEST['field']}',
        '{$_REQUEST['formula']}',
        '{$_REQUEST['method']}',
        '{$_REQUEST['normal_range']}',
        '{$_REQUEST['male_range_min']}',
        '{$_REQUEST['male_range_max']}',
        '{$_REQUEST['female_range_min']}',
        '{$_REQUEST['female_range_max']}',
        '{$_REQUEST['child_range_min']}',
        '{$_REQUEST['child_range_max']}',
        '{$_REQUEST['head']}',
        '{$_REQUEST['antibiotics']}'
    )";
    }
    elseif($_REQUEST['submit']=='Update')
    {
        $query = "UPDATE `labtest_group` SET 
            `labid` = '{$_REQUEST['id']}',
            `test_name` = '{$_REQUEST['testname']}',
            `units` = '{$_REQUEST['units']}',
            `field` = '{$_REQUEST['field']}',
            `formula` = '{$_REQUEST['formula']}',
            `method` = '{$_REQUEST['method']}',
            `normal_range` = '{$_REQUEST['normal_range']}',
            `male_range_min` = '{$_REQUEST['male_range_min']}',
            `male_range_max` = '{$_REQUEST['male_range_max']}',
            `female_range_min` = '{$_REQUEST['female_range_min']}',
            `female_range_max` = '{$_REQUEST['female_range_max']}',
            `child_range_min` = '{$_REQUEST['child_range_min']}',
            `child_range_max` = '{$_REQUEST['child_range_max']}',
            `head` = '{$_REQUEST['head']}',
            `antibiotics` = '{$_REQUEST['antibiotics']}'
        WHERE `testid` = '{$_REQUEST['editid']}'";
    }
    // echo $query;exit;
        mysqli_query($con,$query);
        header('location:lab_test_name.php');
}
$sql="SELECT * FROM lab_test where testid='$_REQUEST[id]'";
$data=mysqli_fetch_assoc(mysqli_query($con,$sql));

$sql1="SELECT * FROM `labtest_group` where labid='$_REQUEST[id]'and status='active'";
        

if(isset($_REQUEST['editid']))
{
    $sql_res="SELECT * FROM `labtest_group` WHERE `testid`='$_REQUEST[editid]'";
    $data_res=mysqli_query($con,$sql_res);
    $row_res=mysqli_fetch_assoc($data_res);
}
        
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Test Name</title>
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
 <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.css" rel="stylesheet">
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

<div aria-live="polite" id="notification" aria-atomic="true" >
<?php if(isset($_REQUEST['success']))
{ ?>
    
<div class="alert alert-success solid alert-dismissible fade show">
                                    <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span>
                                    </button>
                                    <strong>Success!</strong> test inserted
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
            <h4 class="text-uppercase">Add Test for <span class='text-danger'><?= $data['testname']?> </span></h4>
            <button class="btn btn-success" id="toggleFormBtn" type="button" data-toggle="collapse" data-target="#testFormCollapse" aria-expanded="false" aria-controls="testFormCollapse">Add Test</button>
        </div>
    </div>
    <div class="collapse" id="testFormCollapse">
        <div class="card card-body mb-3">
            <form method="post" action="" name="frmtreat" id="testForm">
                <input type="hidden" name="editid" id="editid" value="<?= isset($row_res['testid']) ? $row_res['testid'] : '' ?>">
                <div class="row d-flex align-items-center">
                    <div class="col-sm-3 col-xs-12">
                        <div class="form-group">
                            <label for="testname">Test Name</label>
                            <div class="form-line">
                                <input type="text" class="form-control" name="testname" id="testname" value="<?= $row_res['test_name']?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3 col-xs-12">
                        <div class="form-group">
                            <label for="units">Units</label>
                            <div class="form-line">
                                <input type="text" class="form-control" name="units" id="units" value="<?= $row_res['units']?>">
                            </div>
                        </div>
                    </div>
                    <!--<div class="col-sm-3 col-xs-12">-->
                    <!--    <div class="form-group">-->
                    <!--        <label for="field">Field</label>-->
                    <!--        <div class="form-line">-->
                                <input type="hidden" class="form-control" name="field" id="field" value="<?= $row_res['field']?>">
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--</div>-->
                    <div class="col-sm-3 col-xs-12">
                        <div class="form-group">
                            <label for="formula">Formula</label>
                            <div class="form-line">
                                <input type="text" class="form-control" name="formula" id="formula" 
                                    value="<?=$row_res['formula'] ?>" 
                                    autocomplete="off" list="testname_suggestions">
                                <datalist id="testname_suggestions">
                                    <?php
                                    // Suggest test names from $res1
                                    $res1=mysqli_query($con,$sql1);
                                    mysqli_data_seek($res1, 0); // reset pointer if already used
                                    while($row = mysqli_fetch_assoc($res1)) {
                                        $suggestion = str_replace(' ', '_', $row['test_name']);
                                        echo "<option value=\"{$suggestion}\"></option>";
                                    }
                                    ?>
                                </datalist>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3 col-xs-12">
                        <div class="form-group">
                            <label for="method">Method</label>
                            <div class="form-line">
                                <input type="text" class="form-control" name="method" id="method" value="<?= $row_res['method']?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3 col-xs-12">
                        <div class="form-group">
                            <label for="normal_range">Display Normal Range</label>
                            <div class="form-line">
                                <input type="text" class="form-control" name="normal_range" id="normal_range" value="<?= $row_res['normal_range']?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3 col-xs-12">
                        <div class="form-group">
                            <label for="male_range_min">Male Min Range</label>
                            <div class="form-line">
                                <input type="text" class="form-control" name="male_range_min" id="male_range_min" value="<?= $row_res['male_range_min']?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3 col-xs-12">
                        <div class="form-group">
                            <label for="male_range_max">Male Max Range</label>
                            <div class="form-line">
                                <input type="text" class="form-control" name="male_range_max" id="male_range_max" value="<?= $row_res['male_range_max']?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3 col-xs-12">
                        <div class="form-group">
                            <label for="female_range_min">Female Min Range</label>
                            <div class="form-line">
                                <input type="text" class="form-control" name="female_range_min" id="female_range_min" value="<?= $row_res['female_range_min']?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3 col-xs-12">
                        <div class="form-group">
                            <label for="female_range_max">Female Max Range</label>
                            <div class="form-line">
                                <input type="text" class="form-control" name="female_range_max" id="female_range_max" value="<?= $row_res['female_range_max']?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3 col-xs-12">
                        <div class="form-group">
                            <label for="child_range_min">Child Min Range</label>
                            <div class="form-line">
                                <input type="text" class="form-control" name="child_range_min" id="child_range_min" value="<?= $row_res['child_range_min']?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3 col-xs-12">
                        <div class="form-group">
                            <label for="child_range_max">Child Max Range</label>
                            <div class="form-line">
                                <input type="text" class="form-control" name="child_range_max" id="child_range_max" value="<?= $row_res['child_range_max']?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3 col-xs-12">
                        <div class="form-group">
                            <label for="head">Header</label>
                            <div class="form-line">
                                <input type="text" class="form-control" name="head" id="head" value="<?= $row_res['head']?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="antibiotics">Antibiotics</label>
                            <textarea class="form-control" id="antibiotics" name="antibiotics"><?= isset($row_res['antibiotics']) ? htmlspecialchars($row_res['antibiotics']) : '' ?></textarea>
                        </div>
                    </div>
                   
                    <div class="col-sm-12">
                        <input type="submit" name="submit" id="submit" value="Save" class="btn btn-success float-right">
                        <button type="button" class="btn btn-secondary float-right mr-2" id="closeFormBtn">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
	
</div>


<div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <h5 class="ml-4">Test List of <span class='text-danger'><?= $data['testname']?> </span></h5>
                    </div>
                    <hr>

                    <div class="table-responsive">
                    <table id="datatable-buttons" class="table table-bordered table-hoverde display text-center">
                                    <thead class="thead-dark">
                                    <tr >
                                                <th>Sl NO</th>
                                                 <th>Test Name</th>

                                                <th>Normal Range</th>
                                                <th>Units</th>
                                             <th>Methods</th>
                                                <th>Header</th>
                                                
                                                <th>Action</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody class="text-center  text-black">
                                        <?php $sn=1;
                                        $res1=mysqli_query($con,$sql1);
                                         while($row=mysqli_fetch_assoc($res1)){?>
                                            <tr>
                                                <td><?= $sn;?></td>
                                              
                                                <td><?= $row['test_name']?></td>
                                             
                                                <td><?= $row['normal_range']?><br>
                                                 </td>
                                               <td><?= $row['units']?></td>
                                                <td><?= $row['method']?></td>
                                                 <td><?= $row['head']?></td>
                                                
                                                <td><button type="button" class="btn btn-primary btn-sm editBtn"
    data-id="<?= $row['testid']?>"
    data-testname="<?= htmlspecialchars($row['test_name'])?>"
    data-units="<?= htmlspecialchars($row['units'])?>"
    data-field="<?= htmlspecialchars($row['field'])?>"
    data-formula="<?= htmlspecialchars($row['formula'])?>"
    data-method="<?= htmlspecialchars($row['method'])?>"
    data-normal_range="<?= htmlspecialchars($row['normal_range'])?>"
    data-male_range_min="<?= htmlspecialchars($row['male_range_min'])?>"
    data-male_range_max="<?= htmlspecialchars($row['male_range_max'])?>"
    data-female_range_min="<?= htmlspecialchars($row['female_range_min'])?>"
    data-female_range_max="<?= htmlspecialchars($row['female_range_max'])?>"
    data-child_range_min="<?= htmlspecialchars($row['child_range_min'])?>"
    data-child_range_max="<?= htmlspecialchars($row['child_range_max'])?>"
    data-head="<?= htmlspecialchars($row['head'])?>"
>Edit</button>
<button type="button" class="btn btn-danger btn-sm deleteBtn"
    data-id="<?= $row['testid']?>"
    data-testname="<?= htmlspecialchars($row['test_name'])?>"
    onclick="if(confirm('Are you sure you want to delete this test?')) { window.location='controller/processLab.php?id=<?= $row['testid']?>&testid=<?= $_REQUEST['id']?>&submit=deleteTest'; }">Delete</button>
</td>

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
    function showCard(val)
    {
        // alert(val)
        if(val=='BPL')
        {
            document.getElementById('cardno').style.display='inline';
        }
        else{
            document.getElementById('cardno').style.display='none';   
        }
    }
    function getSubdept(val)
    {
        // alert(val)
        if(val=='7')
        {
            document.getElementById('subdept').style.display='inline';
        }
        else{
            document.getElementById('subdept').style.display='none';   
        }
    }
    window.setTimeout(function() {
    $(".alert").fadeTo(500, 0).slideUp(500, function(){
        $(this).remove(); 
    });
}, 4000);
</script>



<!-- Sweet Alerts js -->
<script src="css/sweetalert2/sweetalert2.min.js"></script>

<!-- Sweet alert init js-->
<script src="js/sweet-alerts.init.js"></script>
  

<script>
    $('.selectpicker').selectpicker({
    liveSearch: true
 });
</script>
<script>
$(document).ready(function() {
    // Open for add
    $('#toggleFormBtn').click(function() {
        $('#testForm')[0].reset();
        $('#editid').val('');
        $('#submit').val('Save');
        $('#testFormCollapse').collapse('show');
    });
    // Edit button in table
    $(document).on('click', '.editBtn', function(e) {
        e.preventDefault();
        $('#editid').val($(this).data('id'));
        $('#testname').val($(this).data('testname'));
        $('#units').val($(this).data('units'));
        $('#field').val($(this).data('field'));
        $('#formula').val($(this).data('formula'));
        $('#method').val($(this).data('method'));
        $('#normal_range').val($(this).data('normal_range'));
        $('#male_range_min').val($(this).data('male_range_min'));
        $('#male_range_max').val($(this).data('male_range_max'));
        $('#female_range_min').val($(this).data('female_range_min'));
        $('#female_range_max').val($(this).data('female_range_max'));
        $('#child_range_min').val($(this).data('child_range_min'));
        $('#child_range_max').val($(this).data('child_range_max'));
        $('#head').val($(this).data('head'));
        $('#submit').val('Update');
        $('#testFormCollapse').collapse('show');
        $('html, body').animate({scrollTop: $('#testFormCollapse').offset().top - 100}, 500);
    });
    // Close button
    $('#closeFormBtn').click(function() {
        $('#testFormCollapse').collapse('hide');
    });
});
document.getElementById('formula').addEventListener('input', function(e) {
                                    // Replace spaces with underscores as user types
                                    let val = this.value.replace(/ /g, '_');
                                    if (this.value !== val) this.value = val;
                                });
                                 $(document).ready(function() {
                        $('#antibiotics').summernote({
                            height: 120
                        });
                    });
</script>
 
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.js"></script>
              
</body>

</html>