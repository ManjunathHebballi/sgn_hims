<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  

include('model/reportClass.php');
$newReport = new report;

if(isset($_REQUEST['year']))
{
    $year=$_REQUEST['year'];
}
else{
        $year=date("Y");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Manage OPD Registry</title>
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
        
        table th{
        
            font-weight:600;
        }
          .table thead th{
        
            font-weight:600;
        }
        .bg-primary{
            
            background-color:#364277 !important;
            color:white
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
<h4 class="text-uppercase mb-5"> Monthy LAB Registry Report</h4>


   
   <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="" method="POST">
    <div class="row">
        <div class="col-md-4">
           <div class="form-group">
                  <label for="exampleInputEmail1"> Year </label>
                  <select aria-hidden="true" name="year" id="year" class="form-control">
								<option value="">--- Select ---</option>
							    <?php 
							    for($i = date("Y")-5; $i <=date("Y"); $i++){ ?>
                                    <option value="<?= $i ?>" <?php if($i==$year) echo 'selected';?>><?=  $i  ?></option>
                                <?php }
                                ?>
						
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
<hr>
                   <div class="d-flex justify-content-end">
                       <a href="print_lab_report_monthly.php?year=<?= $year?>" class="badge badge-pill badge-danger" target="_blank"data-toggle="tooltip" data-placement="top" title="Print  Report"><i class="icofont-print"></i> Print</a>
                       
                   </div>
                   

                    <div class="table-responsive">
                    <table class="table table-bordered text-center  text-wrap table-hover">
                                        <thead class="bg-primary te">
                                            <tr>
                                            <th rowspan=2>
                                            Group
                                           </th>
                                      
                                            <th colspan=2>JAN</th>
                                            
                                            <th colspan=2>FEB</th>
                                            <th colspan=2>MAR</th>
                                            <th colspan=2>APR</th>
                                            
                                            <th colspan=2>MAY</th>
                                            <th colspan=2>JUNE</th>
                                            <th colspan=2>JULY</th>
                                            <th colspan=2>AUG</th>
                                            
                                            <th colspan=2>SEP</th>
                                            <th colspan=2>OCT</th>
                                            <th colspan=2>NOV</th>
                                            <th colspan=2>DEC</th>
                                        </tr>
                                        <tr>
                                            <th>Patient</th>
                                            <th>data</th>
                                            <th>Patient</th>
                                            <th>data</th>
                                            <th>Patient</th>
                                            <th>data</th>
                                            <th>Patient</th>
                                            <th>data</th>
                                            <th>Patient</th>
                                            <th>data</th>
                                            <th>Patient</th>
                                            <th>data</th>
                                            <th>Patient</th>
                                            <th>data</th>
                                            <th>Patient</th>
                                            <th>data</th>
                                            <th>Patient</th>
                                            <th>data</th>
                                            <th>Patient</th>
                                            <th>data</th>
                                            <th>Patient</th>
                                            <th>data</th>
                                            <th>Patient</th>
                                            <th>data</th>
                                            
                                        </tr>
                                        </thead>
                                        <tbody class=" text-black">
                                            <?php $res=$newReport->getMonthlyLab($year);
                                            while($row=mysqli_fetch_assoc($res)) { ?>
                                           <tr>
                                               <td><?=$row['testname'];?></td>
                                                <td><?=$row['jan_distinct'] ?></td><td><?= $row['jan_lab_results']?></td>
                                                
                                                <td><?=$row['feb_distinct']?></td><td><?= $row['feb_lab_results']?></td>
                                                
                                                <td><?=$row['mar_distinct'] ?></td><td><?= $row['mar_lab_results']?></td>
                                                
                                                <td><?=$row['apr_distinct'] ?></td><td><?= $row['apr_lab_results']?></td>
                                                
                                                <td><?=$row['may_distinct'] ?></td><td><?= $row['may_lab_results']?></td>
                                                
                                                <td><?=$row['june_distinct'] ?></td><td><?= $row['june_lab_results']?></td>
                                                
                                                <td><?=$row['july_distinct'] ?></td><td><?= $row['july_lab_results']?></td>
                                                
                                                <td><?=$row['aug_distinct'] ?></td><td><?= $row['aug_lab_results']?></td>
                                                
                                                <td><?=$row['sep_distinct'] ?></td><td><?= $row['sep_lab_results']?></td>
                                                
                                                <td><?=$row['oct_distinct'] ?></td><td><?= $row['oct_lab_results']?></td>
                                                
                                                <td><?=$row['nov_distinct'] ?></td><td><?= $row['nov_lab_results']?></td>
                                                
                                                <td><?=$row['dec_distinct'] ?></td><td><?= $row['dec_lab_results']?></td>
                                           </tr>
                                           <?php } ?>
</tbody>
                                            <!-- <tfoot>
                                                <tr>
                                                    <th>Total</th>
                                                    <th>17</th>
                                                    <th>15</th>
                                                    <th>32</th>
                                                    <th>0</th>
                                                    <th>0</th>
                                                </tr>
                                            </tfoot> -->
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

    
    <!-- Datatable -->
    <script src="./vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="./js/plugins-init/datatables.init.js"></script>


  

</body>

</html>