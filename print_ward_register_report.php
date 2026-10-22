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
    <title>Print Ward Register Report </title>
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
                            <img src="assets/logo.png" class="" alt="">

                        </div>
                        <div class="col-md-10  align-items-center">
                        <h2 class="text-center text-black"><?= $_SESSION['college_name']?></h2>
                        <h5 class="text-center text-black"><?= $_SESSION['college_location']?></h5>
                        <!-- <h5 class="text-center text-black"><?= $_SESSION['college_phone']?></h5> -->

                        </div>
                    </div>
                    
                    <h4 class="text-center text-black">Ward Register Report
                      
                    </h4>

                    <div class="d-flex justify-content-between">
                        <p>Report Period From: 2022-11-06 To 2022-11-14</p> 
                    <p>Report Generated on: 14/11/2022</p></div>
                

          
        </div>
        <p class="text-center">Ward Name:   104 Pediatrics Ward
</p>



        <div class="table-responsive">
        <table id="datatable-buttons" class="table table-hoverde table-bordered display text-center dataTable no-footer" role="grid" aria-describedby="datatable-buttons_info">
                    <tbody><tr>
                <td align="left" colspan="15" >
                    Patients admitted during the period of &nbsp;14/11/2022 to 14/11/2022
                </td>
          </tr>
            <tr align="center" style="font-weight:bold;" class="textsmall">
                <td >Sl No.</td>
                <td >Admission Date &amp; Time</td>
		<td >UID</td>
                   
                <td >Admission Id</td>
                  
                
                <td >Patient Name</td>
                <td >Age</td>
                
                   <td >Address</td>
                 
                <td >Sex</td>
                
                   
              <td >Ward Name<br>
                    
                    
                    
                </td><td >Bed No</td>
                
                <td >Status</td>
            </tr>
         
            <tr>

            </tr>
        
            
            
       
        
        </tbody>    
                                       
                                            </table>  
                                            <table id="datatable-buttons" class="table mt-5 table-hoverde table-bordered display text-center dataTable no-footer" role="grid" aria-describedby="datatable-buttons_info">
                    <tbody><tr>
                <td align="left" colspan="15" >
                Patients admitted before the reporting period but discharged during the period of  14/11/2022 to 14/11/2022
                </td>
          </tr>
            <tr align="center" style="font-weight:bold;" class="textsmall">
                <td >Sl No.</td>
                <td >Admission Date &amp; Time</td>
		
                   
                <td >Admission Id</td>
                  
                
                <td >Patient Name</td>
                <td >Age</td>
                
                   <td >Address</td>
                 
                <td >Sex</td>
                
                   
              <td >Ward Name<br>
                    
                    
                    
                </td><td >Room No/Bed No</td>
                
                <td >Status</td>
            </tr>
         
            <tr>
                
            </tr>
        
            
            
       
        
        </tbody>    
                                       
                                            </table>   
                                            <table id="datatable-buttons" class="mt-5 table table-hoverde table-bordered display text-center dataTable no-footer" role="grid" aria-describedby="datatable-buttons_info">
                    <tbody><tr>
                <td align="left" colspan="15" >
                    
Patients admitted before the reporting period and present throughout the period during  14/11/2022 to 14/11/2022
                </td>
          </tr>
            <tr align="center" style="font-weight:bold;" class="textsmall">
                <td >Sl No.</td>
                <td >Admission Date &amp; Time</td>
		<td >UID</td>
                   
                <td >Admission Id</td>
                  
                
                <td >Patient Name</td>
                <td >Age</td>
                
                   <td >Address</td>
                 
                <td >Sex</td>
                
                   
              <td >Ward Name<br>
                    
                    
                    
                </td><td >Bed No</td>
                
                <td >Status</td>
            </tr>
         
            <tr>

            </tr>
        
            
            
       
        
        </tbody>    
                                       
                                            </table> 
                                            <table class="table mt-5 table-bordered">
                                            <tbody><tr>
                <td height="30px"  colspan="9" class="textheading">
                    Summary
                </td>
            </tr>
            <tr>
                <td align="left" colspan="8" >
                    Total Number of Patients admitted during the period  &nbsp;14/11/2022 to 14/11/2022 is 
                </td>
                <td >
                    0
                </td>
            </tr>
            		 
			<tr>
                <td align="left" colspan="8" >
                   Total Number of  Patients admitted before the reporting period but discharged during the period of &nbsp;
                 14/11/2022 to 14/11/2022 is
                </td>
                <td >
                    0
                        <input type="hidden" name="NICEHOSSecurity" value="IJTY-Y6BH-EHCX-KSWI-KGXJ-PTV4-Q7QJ-58MY" wtx-context="E552E9C4-6851-4BBC-8D84-1A46701E1B73"> 
                </td>
            </tr>
            <tr>
                <td align="left" colspan="8" >
                   Total Number of Patients admitted before the reporting period and present throughout the period during  &nbsp;
                   14/11/2022 to 14/11/2022is
                </td>
                <td >
                   0
                </td>
            </tr>
            
        </tbody>
                                            </table>  
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