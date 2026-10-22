<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/dbconnect.php');
$newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
if(isset($_REQUEST['count']))
{
    // print_r($_REQUEST);
    // exit;
    for ($i=1; $i < $_REQUEST['count']; $i++) { 
        $res=$_REQUEST['result'.$i];
        $id=$_REQUEST['id'.$i];
      
            
            $sql="UPDATE `lab_report` SET test_result='$res' WHERE id= $id";
        
      
        
        $qsqlpat = mysqli_query($con,$sql);
    }
    // if(isset($_REQUEST[]))
    
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Radiology</title>
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
    <!--<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">-->
    <link href="assets/libs/summernote/summernote-bs4.min.css" rel="stylesheet" type="text/css" />

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
            <h4 class="text-uppercase">Radiology Report</h4>
        </div>
    </div>
    <form action="" method="post">
    <div class="row">
        <div class="col-md-12"> 
            <table class="table table-bordered table-hover"  width=100% >
     <thead>
            <tr>
                <th class="text-center">Test Information</th>
                <th class="text-center">Result</th>
               

            </tr>
        </thead>
            <tbody>
            <?php 
            $cond='';
         
                $cond="and lg.labid in (8,9,10,11)";   
            
                    $sql="SELECT lg.`test_name`,lg.`normal_range`,lg.`units`,lr.`test_result`,lr.`id` FROM `lab_report` lr,`labtest_group` lg WHERE lg.`testid` =lr.`lab_result` and lr.`patient_id`='$_REQUEST[id]' $cond";
                    // echo $sql;
                        $medicine = mysqli_query($con,$sql);
                        $sn=1;
                        while($tab = mysqli_fetch_array($medicine))
                        {
                        ?>
            <tr id='addr1'>

                <td class="text-center">
                    <?= $tab['test_name']?>
                   
                </td>
               
                <td>
                    
                      <!--<pre><textarea name="area" id="elm1"  placeholder="Type Here...."></textarea></pre> -->
                      <textarea id="" class="summernote" name="result<?= $sn?>" name="editordata" required><?= $tab['test_result']?></textarea>
                    <!--<input type="text" class="form-control" name="result<?= $sn?>" id="result<?= $sn?>" placeholder="0" value="<?= $tab['test_result']?>" required>-->
                    <input type="hidden" class="form-control" name="id<?= $sn?>" id="id<?= $sn?>" value="<?= $tab['id']?>" required>
                </td>
             
            
                
       
            </tr>
            <?php $sn++; } ?>

            </tbody>
            <!-- </table>
            <table class="table table-responsive table-bordered"   id="tab_logic"> -->
           
            </table>
        </div>
        </div>
        <input type="hidden" name="count" value="<?= $sn?>">

        <div class="row">
            <div class="col-md-12">
       <button class="btn btn-success text-white float-right" type="submit">Save</button>

       </div>
        </div></div>
        </div>
        </form>

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

<!--<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>-->

 <!-- Summernote js -->
        <script src="assets/libs/summernote/summernote-bs4.min.js"></script>

        <!--tinymce js-->
        <script src="assets/libs/tinymce/tinymce.min.js"></script>

        <!-- init js -->
        <script src="assets/js/pages/form-editor.init.js"></script>
  

<script>
    $(document).ready(function() {
  $('.summernote').summernote({
 
  height:200,                 // Set focus to editable area after initializing summernote
      callbacks: {
        onPaste: function(e) {
          var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
          e.preventDefault();
          document.execCommand('insertText', false, bufferText);
        }
      }
});
});
</script>
</body>

</html>