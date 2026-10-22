<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/reportClass.php');
$newReport = new report;                  


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Auto Clave Report</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" ></script>


    <link href="../../assets/plugins/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
    <!-- DataTables -->
    <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/datatables.net-select-bs4/css//select.bootstrap4.min.css" rel="stylesheet" type="text/css" />

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">


    <style>
     #notification {
            position: absolute;
            top: 90px;
           
            right:2px;
            z-index: 99;
        }

</style>

</head>

<body style=" font-family: 'Poppins', sans-serif; background-color:#fff" onload="window.print()">

    <!--*******************
        Preloader start
    ********************-->
   
    <!--*******************
        Preloader end
    ********************-->

    <!--**********************************
        Main wrapper start
  
            <!-- row -->




<div class="container-fluid p-5">

    
   
   
    <div class="row">
        <div class="col-md-12">
            
                   
                <div class="row">
                        <div class="col-md-1 mr-3">
                            <img src="assets/logo.png" class="" alt="">

                        </div>
                        <div class="col-md-10  align-items-center">
                        <h5 class="text-center text-black">Shri J.G Co-Operative Hospital Society's</h5>
                        <h2 class="text-center text-black">Ayurvedic Medical College Hospital</h2>
                        <h5 class="text-center text-black">Tq:-Gokak,  Dist:-Belagavi, Ghataprabha-591306</h5>
                        <h5 class="text-center text-black">Phone:286262,286933,286243</h5>

                        </div>
                    </div>
                    
                    <h4 class="text-center text-black">Auto Clave Register Report    <?php if($_REQUEST['from'] !='2000-01-01' && $_REQUEST['from'] !='2049-12-01') { 
                        echo "Between ". date("d.m.Y",strtotime($_REQUEST['from'])) ." and ". date("d.m.Y",strtotime($_REQUEST['to'])); 
                         } ?>
                       
                    </h4>

                    
                

            
        </div>

                    <div class="table-responsive">
                    <table  class="table table-hoverde table-bordered   ">

                    <thead>
                    <tr align="center">
              <th>SI-No</th>
              <th>Date</th>
             
              <th>Description</th>
        
            </tr> 
       
                    </thead>

                    <tbody>
                       <?php 
                $data=$newReport->getClave($_REQUEST['from'],$_REQUEST['to']); 
                $sn=1;
                while($row=mysqli_fetch_array($data)){
                ?>
                <tr>
                    <td><?= $sn?></td>
                    <td><?= date("d.m.Y",strtotime($row[1]))?></td>
                    <td><?= $row[2]?></td>
                    
                </tr>
                <?php 
                $sn++; } 
                ?>
                    </tbody>
                                 
                                                </table>
                    </div>
                </div>
            </div>
      
       





   

    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="./vendor/global/global.min.js"></script>
    <script src="./js/quixnav-init.js"></script>
    <script src="./js/custom.min.js"></script>

   


    
   


  

</body>

</html>