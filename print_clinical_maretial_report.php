<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata"); 


include('model/reportClass.php');
$newReport = new report;
$from=$_REQUEST['date'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Print Clinical Material  Report  </title>
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



   .txt{

    font-size:0.8rem;
    font-weight:500
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
                            <img src="assets/logo.png" class="" alt="" width=150>

                        </div>
                        <div class="col-md-10  align-items-center">
                        <h2 class="text-center text-black"><?= $_SESSION['college_name']?></h2>
                        <h5 class="text-center text-black"><?= $_SESSION['college_location']?></h5>
                         <h5 class="text-center text-black"><?= $_SESSION['college_phone']?></h5> 

                        </div>
                    </div>
                    
                    <h4 class="text-center text-black">Clinical Material  Report 
                      
                    </h4>

                 

          
        </div>

        <div class="d-flex justify-content-between">
                        <p class="mr-4 txt ">Date of Assessment: <?= date("d.m.Y",strtotime($from))?></p> 
                       

                </div>
              
        <div class="">
            
                                    <table id="datatable-buttons4" class="table table-hoverde table-bordered text-wrap text-center" >
                                    <thead class="thead-nex">
                                   <tr>
                                    <th>Item</th>
                                    <th colspan="2">On the day of Assessment</th>
                                   </tr>
                                                
                                          
                                        </thead>
                                        <tbody>
                                            <?php $data=$newReport->overalldetails($from);
                                            $res=mysqli_fetch_assoc($data);
                                            ?>
                                            <tr>
                                                <td>
                                                    O.P.D. Attendance 
                                                </td>
                                                <td colspan="2"><?= $res['opd']?></td>

                                            </tr>
                                            <tr>
                                                <td>Casuality Attendance (24hrs)</td>
                                                <td colspan="2"><?= $res['casuality']?></td>
                                            </tr>
                                            <tr>
                                                <td>No. of Admissions</td>
                                                <td colspan="2"><?= $res['admission']?></td>


                                            </tr>
                                            <tr>
                                                <td>No. of Discharges</td>
                                                <td colspan="2"><?= $res['discharge']?></td>

                                            </tr>
                                            <tr>
                                                <td>Bed Occupancy % </td>
                                                <td colspan="2"><?= round($res['occupied']*100/$res['bed'],2)?>% (<?= $res['occupied']?> patients)</td>
                                            
                                            </tr>
                                            <tr>
                                                <th><br><u><b>Operative Work</b></u></th>
                                                <th colspan="2"></th>
                                            </tr>
                                            <?php
                                            $res=$newReport->otTypeReport($from,$from);
                                            while($row=mysqli_fetch_assoc($res))
                                            {
                                            ?>
                                            
                                           
                                            <tr>
                                                <td>
                                                    No. of <?= $row['ot_name']?>
                                                </td>
                                                <td colspan="2"><?= $row['val']?></td>
                                            </tr>
                                            
                                            <?php } ?>
                                            <tr>
                                                <th>Radiology Investigations</th>
                                                <th colspan=>O.P.D.</th>
                                                <th>I.P.D</th>
                                            </tr>
                                            <?php 
                                            $data=$newReport->radioReport($from,$from);
                                            while($row=mysqli_fetch_assoc($data))
                                            {
                                            ?>
                                            <tr>
                                                <td><?= $row['testname']?></td>
                                                <td><?= $row['OP']?></td>
                                                <td><?= $row['IP']?></td>
                                            </tr>
                                            <?php } ?>
                            
                                    <tr >
                                               
                                                <th><u>Laboratory Investigations-No of Tests</u></th>
                                                <th>O.P.D</th>
                                                <th>I.P.D</th>
                                                
                                                
                                                
                                                
                                            </tr>
                                      
                                            <?php $data=$newReport->labReport($from,$from);
                                            while($row=mysqli_fetch_assoc($data)) {
                                            ?>
                                            <tr>
                                                <td>TOTAL NO OF <?= $row['testname']?></td>
                                                <td><?= $row['OP']?></td>
                                                <td><?= $row['IP']?></td>
                                                
                                                <!--<td> <a href="iplabprint.php?from=<?= $op_from?>&to=<?= $op_to?>&id=<?= $row['testid']?>" target="_blank" class="text-primary"><?= $row['IP']?></a></td>-->
                                                <!--<td><a href="oplabprint.php?from=<?= $op_from?>&to=<?= $op_to?>&id=<?= $row['testid']?>" target="_blank" class="text-primary"><?= $row['OP']?></a></td>-->
                                               
                                            </tr>
                                            <?php } ?>
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