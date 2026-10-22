<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata"); 
include('model/reportClass.php');
$newReport = new report;
$ip=$newReport->getIPDetailsReport($_REQUEST['id']);
$res=mysqli_fetch_assoc($ip);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Discharge Summary</title>
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
    table tbody td{
        font-weight:200px!important;
    }
    @media print {
        .step {top:0;margin:0;page-break-before: auto;}
    }

    /* Summernote-aware discharge section styles */
    .discharge-section {
        margin-bottom: 0;
        padding: 10px 0 10px 0;
        border-bottom: 1px dashed #bbb;
    }
    .discharge-section:last-of-type {
        border-bottom: none;
    }
    .discharge-section-label {
        font-weight: 700;
        font-size: 1rem;
        color: #222;
        margin-bottom: 4px;
    }
    .discharge-section-content {
        font-size: 0.95rem;
        color: #333;
        margin-left: 4px;
    }
    .discharge-section-content p {
        margin-bottom: 4px;
    }
    .discharge-section-content ul,
    .discharge-section-content ol {
        padding-left: 1.4rem;
        margin-bottom: 4px;
    }
</style>
<!-- onload="window.print() -->

</head>

<body style=" font-family: 'Poppins', sans-serif; background-color:#fff" onload="window.print()" >

    <!--*******************
        Preloader start
    ********************-->
   
    <!--*******************
        Preloader end
    ********************-->

    <!--**********************************
        Main wrapper start
  
           row -->




<div class="container-fluid p-5">

    
   
   
    <div class="row">
        <div class="col-md-12">
            
                   
                <div class="row">
                        <div class="col-sm-2">
                            <img src="images/logo.png" class=" mr-3" width="100%" alt="">

                        </div>
                            <div class="col-sm-9  align-items-center">
                        <h2 class="text-center text-black text-uppercase font-weight-bold"><?= $_SESSION['college_name']?></h2>
                        <h5 class="text-center text-black"><?= $_SESSION['college_location']?></h5>
                        <h5 class="text-center text-black"><?= $_SESSION['college_phone']?></h5>

                       
                        </div>
                        
                       
                    </div>
                     <br><br>
                    <h4 class="text-center text-black">Discharge Summary of <span class="text-danger"><?= $res['name']?> (<?= $res['ipd_no']?>)</span>
                       
                    </h4>

                    
                

            
        </div>






             <div class="container">
               <div class="row mx-auto">
                 <div class="col-md-12  ">
                 <br>
                 <table class="table ">    
    <tbody><tr>
        <th class="border0">	Central OPD</th>
        <td class="border0"><?= $res['opd_no']?></td>
     
        <th>Central IP</th>
        <td><?= $res['ipd_no']?></td>
        <th>Department</th>
        <td><?= $res['dept_name']?></td>
     
    </tr>
    <tr>
    <th class="border0">Patient Name:</th>
        <td class="border0"><?= $res['name']?></td>
        
       
        <th>Age:   </th>
        <td><?= $res['age']?></td>
        <th>Sex:</th>
        <td><?= $res['gender']?></td>
    </tr>
    
    <tr>
 
    <th>Address</th>
        <td><?= $res['address']?></td>
        <th>Date Of Admission:</th>
        <td><?= date("d.m.Y",strtotime($res['admission_date']))?></td>
         <th>Date of Discharge:</th>
        <td><?= date("d.m.y",strtotime($res['discharge_date']))?></td>
      
    </tr>
    <tr>
       
        <th>Ward:</th>
        <td><?= $res['ward']?></td>
        <th>Bed No:</th>
        <td><?= $res['bed_number']?></td>
         <th>Consultant</th>
       <td><?= $res['doctor']?></td>
     
    </tr>

  
</tbody></table>
                 </div>
               </div>
             </div><hr>
                
               </div>
 </div>
 <?php $dis=$newReport->getDisSummary($_REQUEST['id']);
        $row=mysqli_fetch_assoc($dis); ?>
            
        <div class="px-4">
            <div class="container-fluid">
                <div class="col-md-12">

                    <div class="discharge-section">
                        <div class="discharge-section-label">Present Diagnosis</div>
                        <div class="discharge-section-content"><?= $row['diagnosis'] ?></div>
                    </div>

                    <div class="discharge-section">
                        <div class="discharge-section-label">Complaints On Reporting</div>
                        <div class="discharge-section-content"><?= $row['complaints'] ?></div>
                    </div>

                    <div class="discharge-section">
                        <div class="discharge-section-label">History of Presenting Illness</div>
                        <div class="discharge-section-content"><?= $row['history'] ?></div>
                    </div>

                    <div class="discharge-section">
                        <div class="discharge-section-label">Past Medical / Surgical History</div>
                        <div class="discharge-section-content"><?= $row['past_history'] ?></div>
                    </div>

                    <div class="discharge-section">
                        <div class="discharge-section-label">On Examination</div>
                        <div class="discharge-section-content"><?= $row['on_examination'] ?></div>
                    </div>

                    <div class="discharge-section">
                        <div class="discharge-section-label">Physical Examination</div>
                        <div class="discharge-section-content"><?= $row['physical_examination'] ?></div>
                    </div>

                    <div class="discharge-section">
                        <div class="discharge-section-label">Investigation</div>
                        <div class="discharge-section-content"><?= $row['investigation'] ?></div>
                    </div>

                    <div class="discharge-section">
                        <div class="discharge-section-label">Course Of Treatment in Hospital</div>
                        <div class="discharge-section-content"><?= $row['course_treatment'] ?></div>
                    </div>

                    <div class="discharge-section">
                        <div class="discharge-section-label">Advice On Discharge</div>
                        <div class="discharge-section-content"><?= $row['advice_discharge'] ?></div>
                    </div>

                    <div class="discharge-section">
                        <div class="discharge-section-label">Discharge Status</div>
                        <div class="discharge-section-content"><?= $row['discharge_condition'] ?></div>
                    </div>

                    <div class="discharge-section">
                        <div class="discharge-section-label">Review On</div>
                        <div class="discharge-section-content"><?= $row['review_on'] ?></div>
                    </div>

                </div>
            </div>
            <br><br><br>
            <div class="container-fluid mt-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="text-black">Checked By</h5>
                        <span></span>
                    </div>
                    <div class="text-right">
                        <h5 class="text-black">Department Head</h5>
                        <span></span>
                    </div>
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