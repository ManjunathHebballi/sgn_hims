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
    <title>Print Daily Summary Report </title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
        <link rel="preconnect" href="https://fonts.googleapis.com">

        <link rel="stylesheet" href="print_style.css">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">

</head>

<style>
   td{

    font-size:10px
   }

   @media print {

    .dark{
background-color:black;

-webkit-print-color-adjust:exact;
    }
   }
</style>

<body style=" font-family: 'Poppins', sans-serif;" onload="window.print()">

  

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
       
            <div class="container">
                  <div class="row">
                        <div class="col-md-1 mr-3">
                            <img src="assets/logo.png" class="" width="150" alt="">

                        </div>
                        <div class="col-md-10  align-items-center">
                        <h2 class="text-center text-black"><?= $_SESSION['college_name']?></h2>
                        <h5 class="text-center text-black"><?= $_SESSION['college_location']?></h5>
                         <h5 class="text-center text-black"><?= $_SESSION['college_phone']?></h5> 

                        </div>
                    </div>
                    
                    <h4 class="text-center text-black">Daily Summary Report
                      
                    </h4>

                    <div class="d-flex justify-content-between">
                        <p>Report Period From: 2022-11-06 To 2022-11-14</p> 
                    <p>Report Generated on: 14/11/2022</p></div>
                

          
        </div>



        <div class="table-responsive">
                                    <table class="table table-bordered  text-wrap text-center table-hover">
                                    <thead class="thead-dark">
                                    <tr >
                                    <th>Sl. No.</th>
                                                <th>Deptartment</th>
                                                <th>Unit</th>
                                                <th>New Patient</th>
                                                <th>Revisit Patient</th>
                                                <th>Total  </th>
                                                
                                                
                                                
                                            </tr>
                                        </thead> 
                                    <tbody>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>

                                        </tr>
                                    </tbody>
                                    
                                    </table>   
                                            </div>
                                            
                                            
                                            <div class="row mt-5">
    <div class="col-md-12 text-right">
   <h5> Total No. of Patient : 11992</h5>
<h5>Total No. of New Patient : 7597</h5>
<h5>Total No. of Re-visit Patient : 4395</h5>
    </div>
</div>

                                            <div class="row mt-5">
                                                <div class="col-md-12">
                                                    <button onclick="window.print()"class="btn btn-danger float-right d-print-none"> <i class="ri-printer-line"></i> Print </button>
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
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>
   

 


</body>

</html>