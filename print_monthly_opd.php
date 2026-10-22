<?php date_default_timezone_set("Asia/Kolkata"); 

require_once(__DIR__ . '/model/session_config.php');
include('model/reportClass.php');
$newReport = new report;

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Department Wise Report</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

        <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">

</head>

<style>
.container-fulid{
    padding-left:3rem;
}
.table td, .table th{
    padding:0.25rem;
}
   td{

    font-size:15px
   }

   @media print {

    .dark{
background-color:black;

-webkit-print-color-adjust:exact;
    }
   }
</style>

<body style=" font-family: 'Poppins', sans-serif;" onload="window.print()">

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





  

        <!--**********************************
            Content body start
        ***********************************-->
        
            <!-- row -->





   <div class="container-fluid">

    <div class="row">

    <div class="col-md-12">
     
             
                   <div class="row">
                        <div class="col-md-1 mr-3">
                            <img src="assets/logo.png" width="100" alt="">

                        </div>
                        <div class="col-md-10  align-items-center">
                        <h6 class="text-center text-black"><?= $_SESSION['college_society']?></h6>
                        <h3 class="text-center text-black"><?= $_SESSION['college_name']?></h3>
                        <h6 class="text-center text-black"><?= $_SESSION['college_location']?></h6>
                        <h6 class="text-center text-black"><?= $_SESSION['college_phone']?></h6>

                        </div>
                    </div>
                   
                    <h4 class="text-center text-black mt-3"><?php echo ucfirst($_REQUEST['type']); ?> Patient List of Department <?php echo "$_REQUEST[dept_name]"; ?> of <?php echo date("F", mktime(0, 0, 0, $_REQUEST['i'], 10))." ".$_REQUEST['year']; ?> </h4>
                    
                   
                

            </tbody></table>
   



        <div class="table-responsive">
                                    <table class="table table-bordered  text-wrap table-hover">
                                        <thead class="" >
                                            <tr class="dark">
                                                <th >Sl NO</th>
                                                <th>Date</th>
                                                <th>COPID</th>
                                                <!-- <th>Dept COPID</th> -->
                                                                    
                                               
                                                <th>Patient Name</th>
                                                <th>Gender </th>
                                                <th >Age</th>
                                                <th>Address</th>
                                                <th>Diagnosis</th>
                                                <th>Department Name</th>
                                                
                                                
                                                
                                               
                                              
                                                
                                            </tr>
                                        </thead>
                                        <tbody class=" text-black">
                                        <?php $ot=$newReport->getDeptReportDetails($_REQUEST['i'],$_REQUEST['year'],$_REQUEST['dept'],$_REQUEST['type']);
                $sn=1;
                while($row=mysqli_fetch_assoc($ot)){
                ?>
                                            <tr>
                                                <td><?= $sn?></td>
                                                <td><?= date("d.m.Y",strtotime($row['date']))?></td>
                                                <td><?= $row['opd_no']?></td>
                                                <td><?= $row['name']?></td>
                                                <td ><?= $row['gender']?></td>
                                                <td ><?= $row['age']?></td>
                                                <td ><?= $row['address']?></td>
                                                <td ><?= $row['diagnosis'] ?></td>
                                                <td><?= $row['dept_name']?></td>
                                               
                                            </tr>
                     <?php $sn++; } ?>     

                                            
                                            

                                            

                                           
      
                                            
                           
                                               
                                            
                                            </tbody>
                                            </table>   
                                            </div>  

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <button onclick="window.print()"class="btn btn-danger float-right d-print-none"> <i class="ri-printer-line"></i> Print </button>
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
  
 
     <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>




</body>

</html>