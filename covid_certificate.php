<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/dbconnect.php');
$newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
if(isset($_REQUEST['opd']))
{
$query="SELECT c.*,d.doctor FROM `central_opd` c,`doctor` d WHERE d.id=c.doctor and c.id='$_REQUEST[opd]'";    
$id=$_REQUEST['opd'];
}
else{
$query="SELECT * FROM `central_ipd` WHERE ipd_no='$_REQUEST[ipd]'";      
$id=$_REQUEST['ipd'];
}
$res=mysqli_query($con,$query);
$row=mysqli_fetch_assoc($res);
// print_r($row);exit;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Covid Report</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">

    <!-- Datatable -->
    <link href="./vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">

            <!-- DataTables -->
            <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/datatables.net-select-bs4/css//select.bootstrap4.min.css" rel="stylesheet" type="text/css" />

        <!-- Responsive datatable examples -->
        <link href="assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />   

        <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">

</head>

<style>
   th,td{

    font-size:20px !important;
   }

  p{
      line-height:normal;
  }
   
   @media print {
  .footer {
    position: fixed;
    bottom: 0;
  }
  .sign {
    position: fixed;
    bottom: 10px;
    top:85%;
    align:right
  }
}


@media only screen and (max-width: 600px) {
 
  .logo{
      
      width:50px;
  }
  .name h2{
      font-size:0.6rem;
      
  }
  .name h5{
      font-size:0.456rem;
      
  }
  .qr {
      
      width:70px;
  }
  
   th,td{

    font-size:10px !important;
   }
  h3 {
       font-size:1rem!important;
   }
}
</style>

<body style=" font-family: 'Poppins', sans-serif;" onload="window.print()" >

    <!--*******************
        Preloader start
    ********************-->
 
    <!--*******************
        Preloader end
    ********************-->


    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">





  

        <!--**********************************
            Content body start
        ***********************************-->
        
            <!-- row -->







    <div class="row">

    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="container mt-5">
                    <div class="d-flex justify-content-between">
                    
                            <img src="assets/logo.png" class="logo" alt="" width=150>

                       
                        <!--<h5 class="text-center text-black">College Name</h5>-->
                        <div class="name">
                            <h2 class="text-center text-black">SGN Hospital</h2>
                        <h5 class="text-center text-black">Jalna</h5>
                        <h5 class="text-center text-black">+91 7966115698</h5>

                        </div>
                            <img class="qr" src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=http%3A%2F%2Fwww.bmsh1985.in/covid_certificate.php?ipd=<?php if(isset($_REQUEST['ipd'])) echo $row['ipd_no']; else echo $row['opd_no'];?>&choe=UTF-8" title="Lab Result" width=150 />
                       
                    </div><br>
                    
                 

                    
                

           
        </div>
        <div class="container mb-5">
        <div class="row">
            <table class="table" width="100%">
                <thead><tr>
                    <th width="15%">Patient Id.</th><td align="left" width="30%"><?php if(isset($_REQUEST['ipd'])) echo $row['ipd_no']; else echo $row['opd_no'];?></td>
                    <th width="20%"> Date:</th> <td align="left" width="40%" id="bill"><?= date("d.m.Y",strtotime($row['admission_date']))?> </td>
                </tr>
                
                <tr>
                    <th width="15%">Patient NAME:</th><td align="left" width="30%"><?= $row['name']?></td>
                    <th width="20%">Patient Address: </th><td align="left" width="40%"><?= $row['address']?></td>
                </tr>
                
                <tr>
                    <th width="15%">Age</th><td align="left" width="10%"><?= $row['age']?></td>
                    <th width="15%">Doctor Name:</th><td align="left" width="40%"><?= $row['doctor']?></td>
                </tr>
<tr><td></td><td></td><td></td><td></td></tr>
            </thead></table>
        </div>
    </div>
<?php 
                    $sql="SELECT * FROM `cenrtificate`  WHERE `patient_id`='$id'";
                    // echo $sql;
                        $medicine = mysqli_query($con,$sql);
                      $tab = mysqli_fetch_array($medicine)
                        ?>

    <div class="row"><div class="col-md-12 mt-5 mb-5 "><h3><?= $tab['details']?></h3></div></div>
<div class="row  sign">
    <div class="col-md-12 sign">
        <h5 class="text-right ">(Doctor Name & Signature)</h5>
    </div>  
</div>
        <!--<div class="table-responsive">-->
        <!--                            <table class="table table-bordered  text-wrap table-hover">-->
        <!--                               <thead>-->
                                            
        <!--                                     <tr>-->
        <!--        <th>Temprature</th><td><?= $tab['temp']?></td>-->
        <!--        <th>Pulse</th><td><?= $tab['pulse']?></td>-->
                <!-- <th>Discount %</th> -->  
        <!--        <th>Respiration</th> <td></td>-->
                

        <!--    </tr>-->
        <!--    <tr>-->
        <!--        <th>Blood Pressure</th><td><?= $tab['bp']?></td>-->
        <!--        <th>Allergy</th><td><?= $tab['bp']?></td>-->
                
        <!--        <td></td>-->
        <!--          <td></td>  -->
        <!--    </tr>-->
            
        <!--    <tr>-->
        <!--        <th rowspan="2">Nutritional Screening</th>-->
        <!--        <th>Weight</th>-->
        <!--        <th>Height</th>-->
        <!--        <th>Weight Loss</th>-->
        <!--        <th>BMI</th>-->
        <!--        <th>Appetite</th>-->
        <!--    </tr>-->
        <!--    <tr>-->
        <!--        <td><?= $tab['weight']?></td>-->
        <!--         <td><?= $tab['height']?></td> <td></td> <td></td> <td></td>-->
        <!--    </tr>-->
        <!--                               </thead>-->

           
        <!--                                    </table>   -->
        <!--                                    </div>  -->
                                            

                                            
                                            
                                                <div class="footer">     
                                            <div class="row " >
                                                                                   <div class="col-md-12 text-right ">
                                                    <p>An ISO 9001 : 2008 Hospital</p>
                                                    <p>Jalna, Maharashtra</p>

                                                </div>
                                            </div>
                                            </div>
                                            
                                            <div class="row ">
                                                <div class="col-md-12">
                                                    <button onclick="window.print()"class="btn btn-danger float-right d-print-none"> <i class="ri-printer-line"></i> Print </button>
                                                </div>
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

    
 








</body>

</html>