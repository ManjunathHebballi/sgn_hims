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
    <title>Manage Bed Occupacny</title>
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
<h4 class="text-uppercase mb-5"> Monthly Bed Occupancy Report</h4>


   
   <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="#pills-home-tab" method="POST">
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
                       <a href="print_monthly_bed_occupancy.php?year=<?= $year?>" class="badge badge-pill badge-danger" target="_blank"data-toggle="tooltip" data-placement="top" title="Print  Report"><i class="icofont-print"></i> Print</a>
                       
                   </div>
                   

                    <div class="table-responsive">
                    <table class="table table-bordered text-center  text-wrap table-hover">
                                           <thead class="bg-primary te">
                                            <tr>
                                            <th rowspan="2">Month</th>
                                             <th>ICU (20)</th>
                                            <?php
                                            $count=0;$bed=0;$data=array();$data1=array();
                                            $res=$newReport->getIPDepartment();
                                            while($row=mysqli_fetch_assoc($res))
                                            {
                                            ?>
                                            <th ><?= $row['dept_name'].' ('.$row['bed'].')'?></th>
                                            <?php
                                            $bed +=$row['bed'];
                                            $count++;
                                            // array_push($data,$row['id']);
                                            $data1[$row['id']]=$row['bed'];
                                            } 
                                            $data1[0]=20;
                                            ?>
                                           
                                             <th >Total (<?= $bed=$bed+20?>)</th>
                                              
                                            </tr>
                                           
                                         
                                        </thead>
                                        <tbody class=" text-black">
                                           <?php 
                                            if($year==date("Y"))
                                           {
                                               $month=date('n');
                                           }
                                           else{
                                               $month=12;
                                           }
                                           
                                           for($i=1;$i<=$month;$i++) {
                                           $totadm=$totdis=0; ?>
                                           <tr>
                                            <th><?= date("M",mktime(0,0,0,$i+1,0,0))?></th>
                                            <?php $rest=$newReport->getDeptIPReport($i,$year);
                                            while($row1=mysqli_fetch_assoc($rest)){
                                            $totadm +=$row1['bed'];
                                            $data[$row1['id']] +=$row1['bed'];
                                            $day=$row1['daycount'];
                                            

?> 
                                          
                                             <td><?= $row1['bed']?></td>
                                         
                                           <?php } 
                                           
                                           ?>
                                           
                                           <td ><?= $totadm?></td>
                                          
                                          
                                            
                                            
                                            
                                           </tr>
                                           <?php 
                                           
                                           $days +=$day;
                                           } ?>
</tbody>
                                             <tfoot>
                                                <tr>
                                                    <th>Total</th>
                                                    
                                                  <?php 
                                                  $tdept=0;
                                                  foreach ($data as $key => $value) {
    echo "<th>$value</th>" . PHP_EOL;
    $tdept +=$value;
} ?>
<th><?= $tdept?></th>
                                                </tr>
                                                <tr>
                                                    <th>AVG</th>
<?php      $tdept=0;$count=count($data); 

foreach ($data as $key => $value) {
             if (!empty($days) && !empty($data1[$key]) && is_numeric($days) && is_numeric($data1[$key]))
            {
            $val = round(($value * 100) / ($days * $data1[$key]), 2);
             } else 
             {
            $val = 0; 
             }
            echo "<th>$val</th>" . PHP_EOL;
            $tdept +=$val;
} ?>
<th><?= round($tdept/$count,2)?></th>
                                                </tr>
                                                <!--<tr>-->
                                                <!--    <th colspan=11>OVAER ALL AVG</th>-->
                                                <!--    <th><?= round($tdept/$count,2)?></th>-->
                                                <!--</tr>-->
                                            </tfoot>
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