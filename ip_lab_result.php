<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/dbconnect.php');
$newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        if(isset($_REQUEST['date']))
        {
            $cond="and lr.date='$_REQUEST[date]'";
        }
        else
        {
            $cond="";
        }
         if(isset($_REQUEST['visit_no']))
        {
            $visit_no="and lr.visit_no='$_REQUEST[visit_no]'";
        }
        else
        {
            $visit_no="";
        }
if(isset($_REQUEST['count']))
{
    for ($i=1; $i < $_REQUEST['count']; $i++) { 
        $res=$_REQUEST['result'.$i];
        $id=$_REQUEST['id'.$i];
        
        $date=$_REQUEST[date];
         if(strlen($res)>0)
         {
             $print=1;
         }else{
             $print=0;
         }
      
          $sql="UPDATE `lab_report` SET test_result='$res',print_flag='$print' WHERE id= $id"; 
       
        
        $qsqlpat = mysqli_query($con,$sql);
    }
    
   
         echo "<script>window.location='ippatient.php?op_from=$_REQUEST[from]&op_to=$_REQUEST[from]&search=$_REQUEST[search]'</script>";  
    
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>OP Follow Up</title>
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
            <h4 class="text-uppercase"> PATIENT Lab Result</h4>
        </div>
    </div>
    <form action="" method="post">
    <div class="row">
        <div class="col-md-12"> 
            <table class="table table-bordered table-hover"  width=100%  id="loadLab">
     <thead>
            <tr>
                <th>Test Information</th>
                <th>Result</th>
                <!-- <th>Discount %</th> -->
                <th>Normal Range</th>
                <th>Units</th>
                
                <th class="text-center">Delete Test</th>
            </tr>
        </thead>
            <tbody>
            <?php 
                    $sql="SELECT lp.`name`,lr.`id`,lr.`date` as ldate,lp.unit,lr.test_result,ltr.normal_value FROM `lab_report` lr,lab_test lt,`lab_test_parameter` lp 
                    LEFT JOIN lab_test_range ltr ON parameter_id = lp.id
                            AND ltr.status = 'active' 
                            WHERE lt.`testid` =lp.`test_id` and lp.id=lr.parameter_id and lr.`patient_id`='$_REQUEST[id]' $cond $visit_no group by lp.id";
                    // echo $sql;
                        $medicine = mysqli_query($con,$sql);
                        $sn=1;
                        while($tab = mysqli_fetch_array($medicine))
                        {
                        ?>
            <tr id='addr1'>

                <td>
                    <?= $tab['name']?>
                   
                </td>
               
                <td>
                    <input type="text" class="form-control" name="result<?= $sn?>" id="result<?= $sn?>" placeholder="0" value="<?= $tab['test_result']?>" required>
                    <input type="hidden" class="form-control" name="id<?= $sn?>" id="id<?= $sn?>" value="<?= $tab['id']?>" required>
                    <input type="hidden" name="date" value="<?=$tab['ldate']?>">
                </td>
             
                <td>
                    <?= $tab['normal_value']?>
               
                </td>
               
                <td>
                
 <?= $tab['unit']?>
                </td>
        <td class="text-center"><a href="#" onclick="deleteLab(<?= $tab['id']?>)" data-toggle="tooltip" title="Delete Test" class="badge badge-pill badge-danger"><i class="ri-close-line "></i></a></td>
                                                                                
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
    <script type="text/javascript">
            function deleteLab(val)
        {
             $.ajax({
                type : 'post',
                url : 'ajax.php', //Here you will fetch records 
                data :  'id='+val+'&page=lab_del', //Pass $id
                success : function(data){
             $('#loadLab').load(" #loadLab");//Show fetched data from database
                }
            });
        }
        </script>

  

</body>

</html>