<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");
include("model/reportClass.php");
$newReport= new report;
  $opno=$_REQUEST['id'];
$dept=$newReport->getOPDetailsForSlip($opno);
  $res=mysqli_fetch_assoc($dept);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#319197">

    <title>Print OPD Slip</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">

    <link href="./css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" ></script>

 
      <script>window.onload = function () {
    window.print();

} 
</script> 

    <style>
        .table th{

            padding:5px;
            font-size:13px;
            font-weight:600;

        }
        body{
            margin-top:50px;
            background-color:white;
        }
    </style>
     

</head>

<body>


  


    <!--**********************************
        Main wrapper start
    ***********************************-->
   
   
    




<div class="container ">




             

    <div class="container px-0 p-4">
        <div class="row mt-5">
            <div class="col-12 col-lg-12 ">
                <div class="row">
                        <div class="col-sm-1 mr-3">
                            <img src="assets/logo.png" class="" alt="" width=150>

                        </div>
                        <div class="col-sm-10  align-items-center">
                        <h2 class="text-center text-black"><?= $_SESSION['college_name']?></h2>
                        <h5 class="text-center text-black"><?= $_SESSION['college_location']?></h5>
                         <h5 class="text-center text-black"><?= $_SESSION['college_phone']?></h5> 

                        </div>
                    </div>
                </div>
                <!-- .row -->

               
                    
                   </div>

                    <!-- or use a table instead -->
                    
          
            <h5 class="text-center m-3">Patient Registration Details</h5><span>User id: <?= $res['created_by']?></span> <h5 class="text-right d-flex justify-content-end"></h5>
             <hr />
           <table class="table table-borderless mt-5 text-uppercase">
                
                                            
                                            <tr>
                                                <th>OPD Reg Date </th>
                <td><?= date("d.m.Y",strtotime($res['date']))?></td>
                
                                      
                <th>OPD Reg. No. </th>
                <td><?= $res['opd_no']?></td>
                
                
            </tr>
            <tr>
                <th>Patient Name</th>
              <td><?= $res['name']?></td>
                <th>AGE </th>
                <td><?= $res['age']?></td>
              </tr>
              <tr>
                   <th>Address</th>
              <td colspan="4"><?= $res['address']?></td>
              </tr>
              <tr>
           <th>Consultant</th>
                
         <td><?= $res['fname']. " ".$res['last_name']?></td>
            <th>Department</th>
               <td><?= $res['dept_name']?></td>
               
       </tr>

              <tr>
                <th>Mobile</th>
                 <td><?= $res['mobile']?></td>
                 <th>Gender</th>
                  <td><?= $res['gender']?></td>
               
            </tr>
            <tr>
                <th>Occupation</th>
                 <td><?= $res['occupation']?></td>
                  <th>Merital Status</th>
                 <td><?= $res['marital_status']?></td>
       
       </tr>
       <tr>
                <th>Education</th>
                 <td><?= $res['education']?></td>
                  <th>religion</th>
                 <td><?= $res['religion']?></td>
       
       </tr>
       <tr>
                <th>PAN</th>
                 <td><?= $res['pan']?></td>
                  <th>Aadhar</th>
                 <td><?= $res['aadhar']?></td>
       
       </tr>
       <tr>
                <th>Patient Type</th>
                 <td><?= $res['patient_type']?></td>
                  <th>Type Comment</th>
                 <td><?= $res['patient_type_comment']?></td>
       
       </tr>
       
           </table>

                   
                   

                    <div>
                       
                        <a href="#" onclick="window.print();return false;"    data-toggle="tooltip" title="Print" class=" d-print-none btn btn-danger btn-bold  float-right mt-3 mt-lg-0"><i class="icofont-print"></i> Print</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

                </div>
         


    <!--**********************************
        Scripts
    ***********************************-->
    <!--  vendors -->

</script>
</body>

</html>