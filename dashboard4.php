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
 
     <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>


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
.icon2{

    width:60%
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
    <div class="col-md-3">
        <div class="card dash-card">
           
          <div class="card-body">
            <div class="row ">
            <div class="col-md-9">
            <h5 class="text-left text-uppercase">
            Today's OPD Patients
            </h5>
          <?php $result=$newOP->getTodayOpd();
          $row=mysqli_fetch_assoc($result); ?>
            <h2><?php echo $row['todayopd'];?> </h2>
             
            </div>              <div class="col-md-3  ">
                <div class="icon">
<span>
<img src="assets/icons/1.png" class="img-fluid" alt="">
</span>
                </div>

              </div>
            </div>
         
              
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card dash-card">
          <div class="card-body">
            <div class="row ">
            <div class="col-md-9">
            <h5 class="text-left text-uppercase">
            Total OPD Patiens
            </h5>
            <?php $result=$newOP->getTotalOpd();
          $row=mysqli_fetch_assoc($result); ?>
            <h2><?php echo $row['totalopd'];?> </h2>
            </div>              <div class="col-md-3  ">
                <div class="icon">
<span>
<img src="assets/icons/patient.png" class="img-fluid" alt="">
</span>
                </div>

              </div>
            </div>
         
              
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card dash-card">
          <div class="card-body">
            <div class="row ">
            <div class="col-md-9">
            <h5 class="text-left text-uppercase">
            Today's Bill Generated
            </h5>
            <h2>0 </h2>
             
            </div>              <div class="col-md-3  ">
                <div class="icon">
<span>
<img src="assets/icons/cash-payment.png" class="img-fluid" alt="">
</span>
                </div>

              </div>
            </div>
            </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card dash-card">
          <div class="card-body">
            <div class="row ">
            <div class="col-md-9">
            <h5 class="text-left text-uppercase">
          Total Bill Generated 
            </h5>
            <h2>0 </h2>
             
            </div>              <div class="col-md-3  ">
                <div class="icon">
<span>
<img src="assets/icons/receipt.png" class="img-fluid" alt="">
</span>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>

      </div>


                    
         


      <div class="row">

      <div class="col-md-3">
        <div class="card dash-card">
          <div class="card-body">
            <div class="row ">
            <div class="col-md-9">
            <h5 class="text-left text-uppercase">
          Today's IP Patients
            </h5>
            <?php $result=$newOP->getTodayIpd();
          $row=mysqli_fetch_assoc($result); ?>
            <h2><?php echo $row['todayipd'];?> </h2>
                <div class="icon">
<span>
<img src="assets/icons/hospital-bed.png" class="img-fluid" alt="">
</span>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card dash-card">
          <div class="card-body">
            <div class="row ">
            <div class="col-md-9">
            <h5 class="text-left text-uppercase">
          Total IP Patients 
            </h5>
            <?php $result=$newOP->getTotalIpd();
          $row=mysqli_fetch_assoc($result); ?>
            <h2><?php echo $row['totalipd'];?> </h2>
                <div class="icon">
<span>
<img src="assets/icons/hospital-bed (1).png" class="img-fluid" alt="">
</span>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card dash-card">
          <div class="card-body">
            <div class="row ">
            <div class="col-md-9">
            <h5 class="text-left text-uppercase">
          Today's Bill Generated 
            </h5>
            <h2>0 </h2>
             
            </div>              <div class="col-md-3  ">
                <div class="icon">
<span>
<img src="assets/icons/cash-payment.png" class="img-fluid" alt="">
</span>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card dash-card">
          <div class="card-body">
            <div class="row ">
            <div class="col-md-9">
            <h5 class="text-left text-uppercase">
          Total Bill Generated 
            </h5>
            <h2>0 </h2>
             
            </div>              <div class="col-md-3  ">
                <div class="icon">
<span>
<img src="assets/icons/receipt.png" class="img-fluid" alt="">
</span>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>    </div>

      <div class="row">
      <div class="col-md-4">
        <div class="card dash-card">
          <div class="card-body">
            <div class="row ">
            <div class="col-md-9">
            <h5 class="text-left text-uppercase">
          Total  Medicines
            </h5>
            <?php $result=$newOP->getTotalMedicine();
          $row=mysqli_fetch_assoc($result); ?>
            <h2><?php echo $row['totmed'];?> </h2>
                <div class="icon2">
<span>
<img src="assets/icons/medicine.png" class="img-fluid" alt="">
</span>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card dash-card">
          <div class="card-body">
            <div class="row ">
            <div class="col-md-9">
            <h5 class="text-left text-uppercase">
          Today's  Pharmacy Collection
            </h5>
            <?php $result=$newOP->getTodayMedicineO();
            $row=mysqli_fetch_assoc($result);

           $result1=$newOP->getTodayMedicineP();
          $row1=mysqli_fetch_assoc($result1);
          $todaymed=$row['todaymedopd'] + $row1['todaymedipd'] ?>
            <h2><?php echo $todaymed; ?> </h2>
                <div class="icon2">
<span>
<img src="assets/icons/report(1).png" class="img-fluid" alt="">
</span>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card dash-card">
          <div class="card-body">
            <div class="row ">
            <div class="col-md-9">
            <h5 class="text-left text-uppercase">
          Total  Pharmacy Collection
            </h5>
            <?php $result=$newOP->getTotalMedicineO();
            $row=mysqli_fetch_assoc($result);

           $result1=$newOP->getTotalMedicineP();
          $row1=mysqli_fetch_assoc($result1);
          $totmed=$row['totalmedopd'] + $row1['totalmedipd'] ?>
            <h2><?php echo $totmed; ?> </h2>
                <div class="icon2">
<span>
<img src="assets/icons/receipt(1).png" class="img-fluid" alt="">
</span>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card dash-card">
          <div class="card-body">
            <div class="row ">
            <div class="col-md-9">
            <h5 class="text-left text-uppercase">
          Today's Lab Patients
            </h5>
             <?php $result=$newOP->getTodayLab();
          $row=mysqli_fetch_assoc($result); ?>
            <h2><?php echo $row['todaylab'];?> </h2>
                <div class="icon2">
<span>
<img src="assets/icons/blood-tube.png" class="img-fluid" alt="">
</span>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card dash-card">
          <div class="card-body">
            <div class="row ">
            <div class="col-md-9">
            <h5 class="text-left text-uppercase">
          Total Lab Patients
            </h5>
           <?php $result=$newOP->getTotalLab();
          $row=mysqli_fetch_assoc($result); ?>
            <h2><?php echo $row['totallab'];?> </h2>   </div>    <div class="col-md-3  ">
                <div class="icon2">
<span>
<img src="assets/icons/blood-analysis.png" class="img-fluid" alt="">
</span>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card dash-card">
          <div class="card-body">
            <div class="row ">
            <div class="col-md-9">
            <h5 class="text-left text-uppercase">
          Total Lab Collection
            </h5>
            <?php $result=$newOP->getTotalCol();
          $row=mysqli_fetch_assoc($result); ?>
            <h2><?php echo $row['col'];?> </h2>
            </div>              <div class="col-md-3  ">
                <div class="icon2">
<span>
<img src="assets/icons/report.png" class="img-fluid" alt="">
</span>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>

      </div>

      <div class="row">
<div class="col-md-6">
  <div class="card p-3">
    <div class="card-header bg-light">
    <h4 class="text-dark">
                Month Wise OPD Patient Data
            </h4>
    </div>
    <div class="card-card">
   

<div id="opd"></div>


      
  </div>
    </div>
  </div>
  <div class="col-md-6">
  <div class="card p-3">
  <div class="card-header bg-light">
    <h4 class="text-dark">
                Month Wise OPD Bill Generated
            </h4>
    </div>
    <div class="card-card">
   

<div id="opdbill"></div>


      
  </div>
    </div>
  </div>
</div>

<div class="row">
<div class="col-md-6">
  <div class="card p-3">
    <div class="card-header bg-light">
    <h4 class="text-dark">
                Month Wise IPD Patient Data
            </h4>
    </div>
    <div class="card-card">
   

<div id="ipd"></div>


      
  </div>
    </div>
  </div>
  <div class="col-md-6">
  <div class="card p-3">
  <div class="card-header bg-light">
    <h4 class="text-dark">
                Month Wise IPD Bill Generated
            </h4>
    </div>
    <div class="card-card">
   

<div id="ipdbill"></div>


      
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
                                    <table class="table table-bordered mb-0">
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
                                    <table class="table table-bordered mb-0">
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
                                    <table class="table table-bordered mb-0">
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
 <?php  $res=$newOP->chartOpdPatient(); 
            while($row4=mysqli_fetch_assoc($res))
            {
            $n_arr[$row4['month']] = $row4['count'];

            }
            for($l=1; $l<=12; $l++)
            {
                if(!isset($n_arr[$l]))
                {
                    $n_arr[$l]=0;
                }
            }
ksort($n_arr);
        $n_a["OPD Patients"] = 
            array(
                "data"=>$n_arr,
         );
 ?>

<?php  $res=$newOP->chartIpdPatient(); 
            while($row4=mysqli_fetch_assoc($res))
            {
            $i_arr[$row4['month']] = $row4['count'];

            }
            for($l=1; $l<=12; $l++)
            {
                if(!isset($i_arr[$l]))
                {
                    $i_arr[$l]=0;
                }
            }
ksort($i_arr);
        $i_a["IPD Patients"] = 
            array(
                "data"=>$i_arr,
         );

 ?> 
 <?php 
         $res=$newOP->chartPaymentOpd();
            while ($row1 = mysqli_fetch_assoc($res)) {
                    // $arr[$row1['month']] = $row1['payment'];
                     
        $data_arr[$row1['month']] = $row1['payment'];

            }

      for($i=1; $i<=12; $i++)
            {
              if(!isset($data_arr[$i]))
              {
                $data_arr[$i]=0;
              }
            }
ksort($data_arr);
    $c_arr["OPD Bill"] = 
        array(
            "data"=>$data_arr,
     );
        // echo $c_arr;
 ?>



<script>
     
var options = {
          series: [{
          name: 'OPD Patients',
          data: [<?= implode(",", $n_a['OPD Patients']['data'])?>]
        },
    ],
          chart: {
          height: 350,
          type: 'bar',
        },
        plotOptions: {
          bar: {
            borderRadius: 10,
            dataLabels: {
              position: 'top', // top, center, bottom
            },
          }
        },
        dataLabels: {
          enabled: true,
          formatter: function (val) {
            return val ;
          },
          offsetY: -20,
          style: {
            fontSize: '12px',
            colors: ["#304758"]
          }
        },
        
        xaxis: {
          categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
          position: 'top',
          axisBorder: {
            show: false
          },
          axisTicks: {
            show: false
          },
          crosshairs: {
            fill: {
              type: 'gradient',
              gradient: {
                colorFrom: '#D8E3F0',
                colorTo: '#BED1E6',
                stops: [0, 100],
                opacityFrom: 0.4,
                opacityTo: 0.5,
              }
            }
          },
          tooltip: {
            enabled: true,
          }
        },
        yaxis: {
          axisBorder: {
            show: false
          },
          axisTicks: {
            show: false,
          },
          labels: {
            show: false,
            formatter: function (val) {
              return val ;
            }
          }
        
        },
        title: {
          text: 'Month Wise OPD Patient Data',
          floating: true,
          offsetY: 330,
          align: 'center',
          style: {
            color: '#444'
          }
        }
        };

        var chart = new ApexCharts(document.querySelector("#opd"), options);
        chart.render();



        // OPD Bill
 
        var options =  {
          series: [{
          name: 'OPD Bill',
          data: [<?= implode(",", $c_arr['OPD Bill']['data'])?>]
        }],
       
          chart: {
          height: 350,
          type: 'area'
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: 'smooth'
        },
        xaxis: {
          
          categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]

        },
        tooltip: {
          x: {
            format: 'dd/MM/yy HH:mm'
          },
        },
        };
        var chart = new ApexCharts(document.querySelector("#opdbill"), options);
        chart.render();


        // ipd patient

        var options = {
          series: [{
          name: 'IPD Patients',
          data: [<?= implode(",", $i_a['IPD Patients']['data'])?>]
        },
    ],
          chart: {
          height: 350,
          type: 'bar',
        },
        plotOptions: {
          bar: {
            borderRadius: 10,
            dataLabels: {
              position: 'top', // top, center, bottom
            },
          }
        },
        dataLabels: {
          enabled: true,
          formatter: function (val) {
            return val;
          },
          offsetY: -20,
          style: {
            fontSize: '12px',
            colors: ["#304758"]
          }
        },
        
        xaxis: {
          categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
          position: 'top',
          axisBorder: {
            show: false
          },
          axisTicks: {
            show: false
          },
          crosshairs: {
            fill: {
              type: 'gradient',
              color:'20BF55',
              gradient: {
               
                colorFrom: '#20BF55', 
                colorTo: '#01BAEF',
                stops: [0, 100],
                opacityFrom: 0.4,
                opacityTo: 0.5,
              }
            }
          },
          tooltip: {
            enabled: true,
          }
        },
        yaxis: {
          axisBorder: {
            show: false
          },
          axisTicks: {
            show: false,
          },
          labels: {
            show: false,
            formatter: function (val) {
              return val ;
            }
          }
        
        },
        
        };

        var chart = new ApexCharts(document.querySelector("#ipd"), options);
        chart.render();



        // IPD Bill

        var options = {
          series: [{
          name: 'IPD Bill',
          data: [31, 40, 28, 51, 42, 109, 100]
        }],
       
          chart: {
          height: 350,
          type: 'area'
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: 'smooth'
        },
        xaxis: {
          
          categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]

        },
        tooltip: {
          x: {
            format: 'dd/MM/yy HH:mm'
          },
        },
        };

        var chart = new ApexCharts(document.querySelector("#ipdbill"), options);
        chart.render();
      
      
</script>


</body>

</html>