<?php 
require_once(__DIR__ . '/model/session_config.php');

date_default_timezone_set("Asia/Kolkata");  

include('model/ipClass.php');
$newOP = new addIP;

include_once('model/dbconnect.php');
$newlogin = new userlogin;                  
$con = $newlogin->dbconnect();



if(isset($_REQUEST['admission_date'])) {
    $admission_date = $_REQUEST['admission_date'];
} else {
    $admission_date = date("Y-m-d");
}
$dept = $newOP->selectIPreg2($admission_date);

if(isset($_REQUEST['count'])) {

    for ($i = 1; $i <= $_REQUEST['count']; $i++) 
    { 
        $dis_date = mysqli_real_escape_string($con, $_REQUEST['dis_date'.$i]);
        $id = (int)$_REQUEST['id'.$i];  

        if(!empty($dis_date) && $id > 0) {
            $sql = "UPDATE `central_ipd` SET dis_date='$dis_date' WHERE id=$id";
            $qsqlpat = mysqli_query($con, $sql);

            if(!$qsqlpat) {
                die("MySQL Error: " . mysqli_error($con) . " in query: $sql");
            }
        }
    }

    echo "<script>window.location='ippatient.php'</script>";  
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>IP Patient</title>
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
    
        <link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/datatables.net-select-bs4/css/select.bootstrap4.min.css" rel="stylesheet" type="text/css" />


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
        <div class="content-body text-uppercase">
            <!-- row -->




<div class="container-fluid">
  
  <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
  <div class="row">
                  <!--<div class="row mb-3">-->
        <div class="col-md-12">
            <h4 class="text-uppercase">IP PATIENT</h4>
        <!--</div>-->
    </div>
            </div> 
<form action="" method="POST">
    <div class="row">
        <div class="col-md-4">
           <div class="form-group">
                  <label for="exampleInputEmail1">Admission Date </label>
                  <input type="date" class="form-control"  name="admission_date" id="admission_date" required value="<?= $admission_date?>">
                  
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
<form action="#" method='POST'>
    <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="text-uppercase">IP List</h4>
                                 
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered  text-center" >
                                        <thead>
                                            <tr>
                                                <th>Sl NO</th>
                                                <th>COPID</th>
                                                <th>IPD No.</th>
                                                <th>Visit Date</th>
                                                <th>Name</th>
                                                <th>Gender</th>
                                                <th>Address</th>
                                                <th>Diagnosis</th>
                                                <th>Department</th>
                                                <th>Doctor</th>
                                                <th>Bed</th>
                                                <th>Admitted By</th>
                                                <th>Discharge Date</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            <?php $sn=1;
                                            $row=mysqli_num_rows($dept);
                                             while($res=mysqli_fetch_assoc($dept))
                                            { ?>
                                            <tr <?php if($res['discharge']==1) { echo "class='table-success'"; }?>>
                                                <td><?= $sn?></td>
                                                <td><?= $res['opd_no']?></td>
                                                <td><?= $res['ipd_no']?></td>
                                                <td><?= date("d.m.Y h:i:s a",strtotime($res['admission_date']))?></td>
                                                <td><?= $res['name']?></td>
                                                <td><?= $res['gender']?></td>
                                                <td><?= $res['address']?></td>
                                                <td><?= $res['diagnosis']?></td>
                                                <td><?= $res['dept_name']?></td>
                                                <td><?= $res['doctor']?></td>
                                                <td><?= $res['bed']?></td>
                                                <td><?= $res['created_by']?></td>
                                                <td><input class="form-control" name="dis_date<?=$sn?>" type="date" value="<?=$res['dis_date']?>" <?php if($res['discharge']==1) { echo "readonly"; } ?> ></td>
                                                <input name="id<?=$sn?>" type="hidden" value="<?=$res['id']?>" >
                                                
                                            </tr>
                                           <?php $sn++; } ?>
                                            </tbody>
                                            </table>   
                                            </div>  
                                            </div>
                                            </div></div></div>
                                            <input type="hidden" name="count" value="<?= $sn?>">
                                            
<div class="row">
         <div class="col-md-12">
          

            <button type="submit" class="btn btn-primary btn-lg float-right" name="submit" value="submit">Save</button>
            
         </div>
  
    </div>
</form>
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

    
    <!-- Required datatable js -->
    <script src="assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
        <!-- Buttons examples -->
        <script src="assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
        <script src="assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
        <script src="assets/libs/jszip/jszip.min.js"></script>
        <script src="assets/libs/pdfmake/build/pdfmake.min.js"></script>
        <script src="assets/libs/pdfmake/build/vfs_fonts.js"></script>
        <script src="assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
        <script src="assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
        <script src="assets/libs/datatables.net-buttons/js/buttons.colVis.min.js"></script>

        <script src="assets/libs/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
        <script src="assets/libs/datatables.net-select/js/dataTables.select.min.js"></script>
        

        <!-- Datatable init js -->
        <script src="assets/js/pages/datatables.init.js"></script>


        <script>
    $(document).ready(function() {
            $('#datatable-buttons4').DataTable({

                dom: 'Bfrtip',
                responsive: true,
                pageLength: 25,
                // lengthMenu: [0, 5, 10, 20, 50, 100, 200, 500],
                
                buttons: [
           
            {
                extend: 'pdfHtml5',
                title: 'Center OP',
                 orientation: 'landscape',
                pageSize: 'LEGAL'
            }
        ]

            });
        });
</script>
   
  <script>
     function modalAdvance(val,name)
    {
      document.getElementById('ipd').value=val;  
      document.getElementById('ipd_dr').innerHTML=val;
      document.getElementById('ipd_name').innerHTML=name;
    }
</script>

</body>

</html>