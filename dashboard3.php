<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/dashClass.php');
$newOP = new dashboard;
// $dept=$newOP->selectDept();
$data=$newOP->getTotalpatient();
// echo $data;
$deta=explode("|",$data);
$ipd=$deta[1];
$opd=$deta[0];
$today=$ipd+$opd;


$dept=$newOP->getBedList();
// print_r($_SESSION);

// exit;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Dashboard</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="icofont/icofont.min.css">

    <!-- <link href="./vendor/pg-calendar/css/pignose.calendar.min.css" rel="stylesheet"> -->
    <link href="./vendor/chartist/css/chartist.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" ></script>
        <!-- Sweet Alert-->
     <link href="css/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
 


     <style>
         .badge-light-info {
    background-color: #f8f5ff;
    color: #7239ea;
}

.badge-light-danger {
    background-color: #fff5f8;
    color: #f1416c;}
    .badge-light-success {
    background-color: #e8fff3;
    color: #50cd89;
}

.badge-light-warning {
    background-color: #fbf5eb;
    color: #ffaa16;
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
                                    <strong>Success!</strong> OP Patient inserted
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



<div class="grey-bg container-fluid">
  <section id="minimal-statistics">
    <div class="row">
      <div class="col-12 mt-3 mb-1">
        <h4 class="text-uppercase">Dashboard</h4>
        
      </div>
    </div>
    <div class="row">
                    <div class="col-lg-3 col-sm-6">
                        <div class="card">
                            <div class="stat-widget-one card-body">
                                <div class="stat-icon d-inline-block">
                                <i class="icofont-people text-primary"></i>

                                </div>
                                <div class="stat-content d-inline-block">
                                    <div class="stat-text">Today Patients</div>
                                    <div class="stat-digit"><?= $today?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="card">
                            <div class="stat-widget-one card-body">
                                <div class="stat-icon d-inline-block">
                                    <i class="ti-user text-primary  text-warning"></i>
                                </div>
                                <div class="stat-content d-inline-block">
                                    <div class="stat-text">Today's IP</div>
                                    <div class="stat-digit"><?= $ipd?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="card">
                            <div class="stat-widget-one card-body">
                                <div class="stat-icon d-inline-block">
                                <i class="icofont-users-alt-5 text-dark"></i>
                                </div>
                                <div class="stat-content d-inline-block">
                                    <div class="stat-text">Today's OP</div>
                                    <div class="stat-digit"><?= $opd?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="card">
                            <div class="stat-widget-one card-body">
                                <div class="stat-icon d-inline-block">
                                <i class="icofont-users-social text-success"></i>
                                </div>
                                <div class="stat-content d-inline-block">
                                    <div class="stat-text">Total Patients</div>
                                    <div class="stat-digit"><?= $newOP->getTotal();?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

               


                <div class="row">
               
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Bed Lists</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table mb-0">
                                        <thead>
                                            <tr class="text-center font-weighted-bold">
                                             
                                               
                                                <th>Department</th>
                                                <th>Total Beds</th>
                                                <th>Occupied</th>

                                                <th>Available </th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            <?php 
                                            while($row=mysqli_fetch_assoc($dept))
                                            {
                                            ?>
                                            <tr >
                                               
                                                <td><span><?= $row['dept_name']?></span></td>
                                                <td><span class="badge badge-light-info"><?= $row['bed']?></span></td>

                                                <td><span class="badge badge-light-danger"><?= $row['occupied']?></span></td>


                                                                                                <td><span class="badge badge-light-success"><?= $row['bed']-$row['occupied']?></span></td>

                                            </tr>
                                          
                                           <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Month-<?= date("F",strtotime("-1 month"));?> Bed Occupency Report</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table mb-0">
                                        <thead>
                                            <tr class="text-center font-weighted-bold">
                                             
                                               
                                                <th>Department</th>
                                                <th>Admissions</th>
                                                <th>Discharge</th>

                                                <th>Total </th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            <?php 
                                            $data=$newOP->getLastMonthBedList();
                                            // print_r($data);
                                            while($row=mysqli_fetch_assoc($data))
                                            {
                                            ?>
                                            <tr >
                                               
                                                <td><span><?= $row['dept_name']?></span></td>
                                                <td><span class="badge badge-light-info"><?= $row['occupied']?></span></td>
                                                <td><span class="badge badge-light-warning"><?= $row['discharge']?></span></td>
                                                <td><span class="badge badge-light-success"><?= $row['occupied']+$row['discharge']?></span></td>

                                            </tr>
                                          
                                           <?php } ?>
                                      
                                          
                                           
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>


                   
                </div>


                <div class="row">
                <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Time Table</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table mb-0">
                                        <thead>
                                            <tr class="text-center font-weighted-bold">
                                             
                                               <th></th>
                                                <th>Monday</th>
                                                <th>Tuseday</th>
                                                <th>wednesday</th>

                                                <th>Thursday </th>
                                                <th>Friday</th>
                                                <th>Saturday</th>
                                                <th>Sunday</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            
                                   <tr >
                                                     
                                               
                                                     <td><span style="color:#53D78B">KAYA CHIKITSA</span></td>
                                                     <?php $dept=$newOP->getTimeTable('KAYA CHIKITSA');
                                  while($row=mysqli_fetch_assoc($dept)){ 
                                  if($row['day']=='Monday') {
                                  ?>
                                  
                                   <td>
                                       
                                       <?php 
                                   
                                       
                                    
                                      $doctor=explode(",",$row['doctor']);
                                          foreach($doctor as $value)
                                          {
                                              echo "$value<br>";
                                          }
                                      ?>
                                       
                                       
                                   </td>
                                   <?php } else if($row['day']=='Tuesday') {?>
                                   <td>
                                   <?php 
                                       
                                        $doctor=explode(",",$row['doctor']);
                                          foreach($doctor as $value)
                                          {
                                              echo "$value<br>";
                                          }
                                        ?> 
                                       
                                   </td>
                                   <?php } else if($row['day']=='Wednesday') {?>
                                   <td><?php 
                                       
                                       $doctor=explode(",",$row['doctor']);
                                          foreach($doctor as $value)
                                          {
                                              echo "$value<br>";
                                          }
                                        ?> 
                                   </td>
                                   <?php } else if($row['day']=='Thursday') {?>
                                   <td><?php 
                                       
                                       $doctor=explode(",",$row['doctor']);
                                          foreach($doctor as $value)
                                          {
                                              echo "$value<br>";
                                          }
                                        ?>
                                   </td>
                                    <?php } else if($row['day']=='Friday') {?>
                                   <td><?php 
                                       
                                       $doctor=explode(",",$row['doctor']);
                                          foreach($doctor as $value)
                                          {
                                              echo "$value<br>";
                                          }
                                        ?>
                                   </td>
                                   <?php } else if($row['day']=='Saturday') {?>
                                   <td><?php 
                                       
                                        $doctor=explode(",",$row['doctor']);
                                          foreach($doctor as $value)
                                          {
                                              echo "$value<br>";
                                          }
                                        ?>
                                   </td>
                                   <?php } else{?>
                                   <td>
                                        <?php 
                                       
                                        $doctor=explode(",",$row['doctor']);
                                          foreach($doctor as $value)
                                          {
                                              echo "$value<br>";
                                          }
                                        ?>
                                    </td>
                                  
                                <?php }
                                } ?>
     
     
                                                 </tr>
     
     
     
                                  
     
     
     
                                                 </tr>
                                                 <tr >
                                                     
                                               
                                                     <td><span style="color:#2C97DE">PANCHAKARMA</span></td>
                                  <?php $dept=$newOP->getTimeTable('PANCHAKARMA');
                                  while($row=mysqli_fetch_assoc($dept)){ 
                                  if($row['day']=='Monday') {
                                  ?>
                                  
                                   <td>
                                       
                                       <?php 
                                   
                                       
                                    
                                      $doctor=explode(",",$row['doctor']);
                                          foreach($doctor as $value)
                                          {
                                              echo "$value<br>";
                                          }
                                      ?>
                                       
                                       
                                   </td>
                                   <?php } else if($row['day']=='Tuesday') {?>
                                   <td>
                                   <?php 
                                       
                                        $doctor=explode(",",$row['doctor']);
                                          foreach($doctor as $value)
                                          {
                                              echo "$value<br>";
                                          }
                                        ?> 
                                       
                                   </td>
                                   <?php } else if($row['day']=='Wednesday') {?>
                                   <td><?php 
                                       
                                       $doctor=explode(",",$row['doctor']);
                                          foreach($doctor as $value)
                                          {
                                              echo "$value<br>";
                                          }
                                        ?> 
                                   </td>
                                   <?php } else if($row['day']=='Thursday') {?>
                                   <td><?php 
                                       
                                       $doctor=explode(",",$row['doctor']);
                                          foreach($doctor as $value)
                                          {
                                              echo "$value<br>";
                                          }
                                        ?>
                                   </td>
                                    <?php } else if($row['day']=='Friday') {?>
                                   <td><?php 
                                       
                                       $doctor=explode(",",$row['doctor']);
                                          foreach($doctor as $value)
                                          {
                                              echo "$value<br>";
                                          }
                                        ?>
                                   </td>
                                   <?php } else if($row['day']=='Saturday') {?>
                                   <td><?php 
                                       
                                        $doctor=explode(",",$row['doctor']);
                                          foreach($doctor as $value)
                                          {
                                              echo "$value<br>";
                                          }
                                        ?>
                                   </td>
                                   <?php } else{?>
                                   <td>
     
                                    </td>
                                  
                                <?php }
                                } ?>
     
                                  
     
     
     
                                                 </tr>
                                                 <tr >
                                                     
                                               
                                                     <td><span style="color:#FF69B4">PRASOOTI TANTRA</span></td>
                                   <?php $dept=$newOP->getTimeTable('PRASOOTI TANTRA');
                                  while($row=mysqli_fetch_assoc($dept)){ 
                                  if($row['day']=='Monday') {
                                  ?>
                                  
                                   <td>
                                       
                                       <?php 
                                   
                                       
                                    
                                      $doctor=explode(",",$row['doctor']);
                                          foreach($doctor as $value)
                                          {
                                              echo "$value<br>";
                                          }
                                      ?>
                                       
                                       
                                   </td>
                                   <?php } else if($row['day']=='Tuesday') {?>
                                   <td>
                                   <?php 
                                       
                                        $doctor=explode(",",$row['doctor']);
                                          foreach($doctor as $value)
                                          {
                                              echo "$value<br>";
                                          }
                                        ?> 
                                       
                                   </td>
                                   <?php } else if($row['day']=='Wednesday') {?>
                                   <td><?php 
                                       
                                       $doctor=explode(",",$row['doctor']);
                                          foreach($doctor as $value)
                                          {
                                              echo "$value<br>";
                                          }
                                        ?> 
                                   </td>
                                   <?php } else if($row['day']=='Thursday') {?>
                                   <td><?php 
                                       
                                       $doctor=explode(",",$row['doctor']);
                                          foreach($doctor as $value)
                                          {
                                              echo "$value<br>";
                                          }
                                        ?>
                                   </td>
                                    <?php } else if($row['day']=='Friday') {?>
                                   <td><?php 
                                       
                                       $doctor=explode(",",$row['doctor']);
                                          foreach($doctor as $value)
                                          {
                                              echo "$value<br>";
                                          }
                                        ?>
                                   </td>
                                   <?php } else if($row['day']=='Saturday') {?>
                                   <td><?php 
                                       
                                        $doctor=explode(",",$row['doctor']);
                                          foreach($doctor as $value)
                                          {
                                              echo "$value<br>";
                                          }
                                        ?>
                                   </td>
                                   <?php } else{?>
                                   <td>
     
                                    </td>
                                  
                                <?php }
                                } ?>
     
                                  
     
     
     
                                                 </tr>
                                                 <tr >
                                                     
                                               
                                                     <td><span style="color:#E67F22">SHALAYAKYA TANTRA</span></td>
                                  <?php $dept=$newOP->getTimeTable('SHALAYAKYA TANTRA');
                                  while($row=mysqli_fetch_assoc($dept)){ 
                                  if($row['day']=='Monday') {
                                  ?>
                                  
                                   <td>
                                       
                                       <?php 
                                   
                                       
                                    
                                      $doctor=explode(",",$row['doctor']);
                                          foreach($doctor as $value)
                                          {
                                              echo "$value<br>";
                                          }
                                      ?>
                                       
                                       
                                   </td>
                                   <?php } else if($row['day']=='Tuesday') {?>
                                   <td>
                                   <?php 
                                       
                                        $doctor=explode(",",$row['doctor']);
                                          foreach($doctor as $value)
                                          {
                                              echo "$value<br>";
                                          }
                                        ?> 
                                       
                                   </td>
                                   <?php } else if($row['day']=='Wednesday') {?>
                                   <td><?php 
                                       
                                       $doctor=explode(",",$row['doctor']);
                                          foreach($doctor as $value)
                                          {
                                              echo "$value<br>";
                                          }
                                        ?> 
                                   </td>
                                   <?php } else if($row['day']=='Thursday') {?>
                                   <td><?php 
                                       
                                       $doctor=explode(",",$row['doctor']);
                                          foreach($doctor as $value)
                                          {
                                              echo "$value<br>";
                                          }
                                        ?>
                                   </td>
                                    <?php } else if($row['day']=='Friday') {?>
                                   <td><?php 
                                       
                                       $doctor=explode(",",$row['doctor']);
                                          foreach($doctor as $value)
                                          {
                                              echo "$value<br>";
                                          }
                                        ?>
                                   </td>
                                   <?php } else if($row['day']=='Saturday') {?>
                                   <td><?php 
                                       
                                        $doctor=explode(",",$row['doctor']);
                                          foreach($doctor as $value)
                                          {
                                              echo "$value<br>";
                                          }
                                        ?>
                                   </td>
                                   <?php } else{?>
                                   <td>
     
                                    </td>
                                  
                                <?php }
                                } ?>
     
     
                                  
     
     
     
                                                 </tr>

                                                 <tr >
                                                     
                                               
                                                     <td><span style="color:#2D3E50">SHALYA TANTRA</span></td>
                                  <?php $dept=$newOP->getTimeTable('SHALYA TANTRA');
                                  while($row=mysqli_fetch_assoc($dept)){ 
                                  if($row['day']=='Monday') {
                                  ?>
                                  
                                   <td>
                                       
                                       <?php 
                                   
                                       
                                    
                                      $doctor=explode(",",$row['doctor']);
                                          foreach($doctor as $value)
                                          {
                                              echo "$value<br>";
                                          }
                                      ?>
                                       
                                       
                                   </td>
                                   <?php } else if($row['day']=='Tuesday') {?>
                                   <td>
                                   <?php 
                                       
                                        $doctor=explode(",",$row['doctor']);
                                          foreach($doctor as $value)
                                          {
                                              echo "$value<br>";
                                          }
                                        ?> 
                                       
                                   </td>
                                   <?php } else if($row['day']=='Wednesday') {?>
                                   <td><?php 
                                       
                                       $doctor=explode(",",$row['doctor']);
                                          foreach($doctor as $value)
                                          {
                                              echo "$value<br>";
                                          }
                                        ?> 
                                   </td>
                                   <?php } else if($row['day']=='Thursday') {?>
                                   <td><?php 
                                       
                                       $doctor=explode(",",$row['doctor']);
                                          foreach($doctor as $value)
                                          {
                                              echo "$value<br>";
                                          }
                                        ?>
                                   </td>
                                    <?php } else if($row['day']=='Friday') {?>
                                   <td><?php 
                                       
                                       $doctor=explode(",",$row['doctor']);
                                          foreach($doctor as $value)
                                          {
                                              echo "$value<br>";
                                          }
                                        ?>
                                   </td>
                                   <?php } else if($row['day']=='Saturday') {?>
                                   <td><?php 
                                       
                                        $doctor=explode(",",$row['doctor']);
                                          foreach($doctor as $value)
                                          {
                                              echo "$value<br>";
                                          }
                                        ?>
                                   </td>
                                   <?php } else{?>
                                   <td>
     
                                    </td>
                                  
                                <?php }
                                } ?>
     
     
                                                 </tr>
                                           <tr >
                                                     
                                               
                                                     <td><span style="color:#9C56B8">SWASTHAVRITTA</span></td>
                                  <?php $dept=$newOP->getTimeTable('SWASTHAVRITTA');
                                  while($row=mysqli_fetch_assoc($dept)){ 
                                  if($row['day']=='Monday') {
                                  ?>
                                  
                                   <td>
                                       
                                       <?php 
                                   
                                       
                                    
                                      $doctor=explode(",",$row['doctor']);
                                          foreach($doctor as $value)
                                          {
                                              echo "$value<br>";
                                          }
                                      ?>
                                       
                                       
                                   </td>
                                   <?php } else if($row['day']=='Tuesday') {?>
                                   <td>
                                   <?php 
                                       
                                        $doctor=explode(",",$row['doctor']);
                                          foreach($doctor as $value)
                                          {
                                              echo "$value<br>";
                                          }
                                        ?> 
                                       
                                   </td>
                                   <?php } else if($row['day']=='Wednesday') {?>
                                   <td><?php 
                                       
                                       $doctor=explode(",",$row['doctor']);
                                          foreach($doctor as $value)
                                          {
                                              echo "$value<br>";
                                          }
                                        ?> 
                                   </td>
                                   <?php } else if($row['day']=='Thursday') {?>
                                   <td><?php 
                                       
                                       $doctor=explode(",",$row['doctor']);
                                          foreach($doctor as $value)
                                          {
                                              echo "$value<br>";
                                          }
                                        ?>
                                   </td>
                                    <?php } else if($row['day']=='Friday') {?>
                                   <td><?php 
                                       
                                       $doctor=explode(",",$row['doctor']);
                                          foreach($doctor as $value)
                                          {
                                              echo "$value<br>";
                                          }
                                        ?>
                                   </td>
                                   <?php } else if($row['day']=='Saturday') {?>
                                   <td><?php 
                                       
                                        $doctor=explode(",",$row['doctor']);
                                          foreach($doctor as $value)
                                          {
                                              echo "$value<br>";
                                          }
                                        ?>
                                   </td>
                                   <?php } else{?>
                                   <td>
     
                                    </td>
                                  
                                <?php }
                                } ?>
     
                                                 </tr>
                                              <tr >
                                                     
                                               
                                                     <td><span style="color:#30D5C8">KAUMARA BRUTYA</span></td>
                                   <?php $dept=$newOP->getTimeTable('KAUMARA BRUTYA');
                                  while($row=mysqli_fetch_assoc($dept)){ 
                                  if($row['day']=='Monday') {
                                  ?>
                                  
                                   <td>
                                       
                                       <?php 
                                   
                                       
                                    
                                      $doctor=explode(",",$row['doctor']);
                                          foreach($doctor as $value)
                                          {
                                              echo "$value<br>";
                                          }
                                      ?>
                                       
                                       
                                   </td>
                                   <?php } else if($row['day']=='Tuesday') {?>
                                   <td>
                                   <?php 
                                       
                                        $doctor=explode(",",$row['doctor']);
                                          foreach($doctor as $value)
                                          {
                                              echo "$value<br>";
                                          }
                                        ?> 
                                       
                                   </td>
                                   <?php } else if($row['day']=='Wednesday') {?>
                                   <td><?php 
                                       
                                       $doctor=explode(",",$row['doctor']);
                                          foreach($doctor as $value)
                                          {
                                              echo "$value<br>";
                                          }
                                        ?> 
                                   </td>
                                   <?php } else if($row['day']=='Thursday') {?>
                                   <td><?php 
                                       
                                       $doctor=explode(",",$row['doctor']);
                                          foreach($doctor as $value)
                                          {
                                              echo "$value<br>";
                                          }
                                        ?>
                                   </td>
                                    <?php } else if($row['day']=='Friday') {?>
                                   <td><?php 
                                       
                                       $doctor=explode(",",$row['doctor']);
                                          foreach($doctor as $value)
                                          {
                                              echo "$value<br>";
                                          }
                                        ?>
                                   </td>
                                   <?php } else if($row['day']=='Saturday') {?>
                                   <td><?php 
                                       
                                        $doctor=explode(",",$row['doctor']);
                                          foreach($doctor as $value)
                                          {
                                              echo "$value<br>";
                                          }
                                        ?>
                                   </td>
                                   <?php } else{?>
                                   <td>
                                     <?php 
                                       
                                        $doctor=explode(",",$row['doctor']);
                                          foreach($doctor as $value)
                                          {
                                              echo "$value<br>";
                                          }
                                        ?>
                                    </td>
                                  
                                <?php }
                                } ?>
     
                                                 </tr>
                                           
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
</section>
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

<!-- <script src="./vendor/chartist/js/chartist.min.js"></script> -->

<script src="./vendor/moment/moment.min.js"></script>
<!-- <script src="./vendor/pg-calendar/js/pignose.calendar.min.js"></script> -->


<!-- <script src="./js/dashboard/dashboard-2.js"></script> -->
  



</body>

</html>