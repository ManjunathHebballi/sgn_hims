<?php 
date_default_timezone_set("Asia/Kolkata"); 
require_once(__DIR__ . '/model/session_config.php');
$newReport = new report;

if(isset($_REQUEST['year']))
{
    $year=$_REQUEST['year'];
}
else{
        $year=date("Y");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Print Montly Bed Occupancy Report</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

        <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">

</head>

<style>
   td{

    font-size:14px
   }

  @media print{
      
      @page {
      
      size: landscape}

    .dark{
background-color:black;

-webkit-print-color-adjust:exact;
    }
   }
   
   th{
       font-weight:600;
   }
   
   .table th, .table td {
       
       padding:3px;
       font-size:0.8rem;
      text-align: center; 
    vertical-align: middle;
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







    <div class="row">

    <div class="col-md-12">
      
                <div class="container">
                   
                        <div class="d-flex align-items-center ml-3">
                            <img src="assets/logo.png" class="" alt="" width=150>

                        
                        <div class=" ml-5">
                        <h5 class="text-center text-black"><?= $_SESSION['college_society']?></h5>
                        <h3 class="text-center text-black"><?= $_SESSION['college_name']?></h3>
                        <h5 class="text-center text-black"><?= $_SESSION['college_location']?></h5>
                        <h5 class="text-center text-black"><?= $_SESSION['college_phone']?></h5>

                        </div>
                   </div>


                    
                

          
        </div>

                    <hr/>
                    <h4 class="text-center text-black mb-3 mt-5">Monthly Bed Occupancy Report
                       
                    </h4>

                              
                     <table class="table table-bordered text-center  text-wrap table-hover">
                                        <thead class="bg-primary te">
                                            <tr>
                                            <th rowspan="2">Month</th>
                                             <th>ICU (20)</th>
                                            <?php
                                            $count=0;$bed=0;$data=array();$data1=array();
                                            $res=$newReport->getIPDepartment();
                                            while($row=mysqli_fetch_assoc($res))
                                            {
                                            ?>
                                            <th ><?= $row['dept_name'].' ('.$row['bed'].')'?></th>
                                            <?php
                                            $bed +=$row['bed'];
                                            $count++;
                                            // array_push($data,$row['id']);
                                            $data1[$row['id']]=$row['bed'];
                                            } 
                                            $data1[0]=20;
                                            ?>
                                           
                                             <th >Total (<?= $bed=$bed+20?>)</th>
                                              
                                            </tr>
                                           
                                         
                                        </thead>
                                        <tbody class=" text-black">
                                           <?php 
                                            if($year==date("Y"))
                                           {
                                               $month=date('n');
                                           }
                                           else{
                                               $month=12;
                                           }
                                           
                                           for($i=1;$i<=$month;$i++) {
                                           $totadm=$totdis=0; ?>
                                           <tr>
                                            <th><?= date("M",mktime(0,0,0,$i+1,0,0))?></th>
                                            <?php $rest=$newReport->getDeptIPReport($i,$year);
                                            while($row1=mysqli_fetch_assoc($rest)){
                                            $totadm +=$row1['bed'];
                                            $data[$row1['id']] +=$row1['bed'];
                                            $day=$row1['daycount'];
                                            

?> 
                                          
                                             <td><?= $row1['bed']?></td>
                                         
                                           <?php } 
                                           
                                           ?>
                                           
                                           <td ><?= $totadm?></td>
                                          
                                          
                                            
                                            
                                            
                                           </tr>
                                           <?php 
                                           
                                           $days +=$day;
                                           } ?>
</tbody>
                                             <tfoot>
                                                <tr>
                                                    <th>Total</th>
                                                    
                                                  <?php 
                                                  $tdept=0;
                                                  foreach ($data as $key => $value) {
    echo "<th>$value</th>" . PHP_EOL;
    $tdept +=$value;
} ?>
<th><?= $tdept?></th>
                                                </tr>
                                                <tr>
                                                    <th>AVG</th>
<?php      $tdept=0;$count=count($data); 

foreach ($data as $key => $value) {
    $val=round(($value*100)/($days*$data1[$key]),2);
    if (!empty($days) && !empty($data1[$key]) && is_numeric($days) && is_numeric($data1[$key])) {
    $val = round(($value * 100) / ($days * $data1[$key]), 2);
} else {
    $val = 0; // or handle it differently
}
    echo "<th>$val</th>" . PHP_EOL;
    $tdept +=$val;
} ?>
<th><?= round($tdept/$count,2)?></th>
                                                </tr>
                                                <!--<tr>-->
                                                <!--    <th colspan=11>OVAER ALL AVG</th>-->
                                                <!--    <th><?= round($tdept/$count,2)?></th>-->
                                                <!--</tr>-->
                                            </tfoot>
                                            </table>
             
                                            <div class="row">
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
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>

</body>

</html>