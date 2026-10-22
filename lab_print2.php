<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/dbconnect.php');
setlocale(LC_MONETARY,"en_IN");
$newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
if(isset($_REQUEST['opd']))
{
$query="SELECT c.*,concat(d.name,' ',d.last_name) as doctor FROM `central_opd` c,`doctor` d WHERE d.id=c.doctor and c.id='$_REQUEST[opd]' ";    
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
    <title>Lab Report</title>
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
    text-transform: uppercase;
   }

.table th{
    
    font-weight:600;
}

     @media print {
        .outer-tr {
            page-break-inside: avoid;
        }
    .group-title {
            page-break-after: avoid;
        }
        
      
          .footer {
    position: fixed;
    bottom: 0px;
    right:0;
    text-align: right;
  }
    .footer-left {
            position: fixed;
            bottom: 30px;
            left: 0;
            text-align: left;
        }
        .footer-right {
            position: fixed;
            bottom: 30px;
            right: 0;
            text-align: right;
        }   
     }
     
     .border-b
     
     {
         
             border-bottom: 2px solid #000000 !important;
                 border-top: 2px solid #000000 !important;
     }


        .footer {
                position: fixed;
                bottom: 0;
                width: 100%;
                text-align: center;
                font-size: 18px;
                padding: 10px 0;
                background: #fff;
                border-top: 2px solid #000;
            }
</style>

<body style=" font-family: 'Poppins', sans-serif; padding-left:35px;" onload="window.print()" class="bg-white">

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





        <table class="table table-borderless" width="100%">
            <thead class="">
         <tr>
                <td colspan="4">
                     <table class="table " width="100%">
                        <tr>
                            <th rowspan="2" width="20%"><img src="assets/logo.png" class="" alt="" width=150></th>
        
                            <th  class="text-center">
                               <h2>Alva's Health Centre Lab</h2>
                            </th>
                            <td rowspan="2" class="text-center" >
                                <img src="https://api.qrserver.com/v1/create-qr-code/?data=http%3A%2F%2Fwww.alvashospital.com/lab_print.php?<?php if(isset($_REQUEST['ipd'])) echo 'ipd='.$row['ipd_no']; else echo 'opd='.$_REQUEST['opd'];?>&choe=UTF-8" title="Lab Result" width=150 />
                            </td>
                        </tr>
        
                        <tr class="text-center">
                            <td class="text-center">Alva's Hospital Road, Mudbidri, Karnataka<br> 08258-238104 to 238111 (8 lines)</td>
                        </tr>
                 
                      </table>
                </td>
             
             </tr>

         <tr>
             <td colspan="4">
                      <table class="table mb-4" width="100%">
                <tbody><tr>
                    <th width="20%">Patient Id.</th><td align="left" width="30%" id="visit_no"></td>
                    <th width="20%"> Date:</th> <td align="left" width="40%" id="bill"></td>
                </tr>
                
                <tr>
                    <th width="20%">Patient NAME:</th><td align="left" width="30%"><?= $row['name']?></td>
                    <th width="20%">Patient Address: </th><td align="left" width="40%"><?= $row['address']?></td>
                </tr>
                
                <tr>
                    <th width="20%">Age</th><td align="left" width="10%"><?= $row['age']?> Year / <?= $row['gender'].$row['sex']?></td>
                    <th width="15%">Doctor Name:</th><td align="left" width="40%"><?= $row['doctor']?></td>
                </tr>

            </tbody></table>
             </td>
         </tr>
         
            
                                        
                                        
                                             <tr class="border-b mb-4" >
                <th>Test Information</th>
                <th>Result</th>
                <!-- <th>Discount %</th> -->   
                <th>Units</th>
                <th class="text-center">Normal Range</th>

           </tr>
           </thead>
            <tbody>
                
                
                  
            <?php 
     if(isset($_REQUEST['bill_no']))
     {
         $bill=" and bill_no='$_REQUEST[bill_no]'";
     }
     else{
         $bill='';
     }
                    $sql="SELECT lg.`test_name`,lg.`normal_range`,lg.`units`,lr.`test_result`,lr.`id`,lr.`date`,ln.testname,lg.head,ln.testname_header,ln.interpretation,male_range_min,male_range_max, lg.method,visit_no  FROM `lab_report` lr,`labtest_group` lg  left join `lab_test` ln on ln.`testid`=lg.`labid` WHERE lg.`testid` =lr.`lab_result` $bill  and lr.`patient_id`='$id' order by lr.id";
                    // echo $sql;
                        $medicine = mysqli_query($con,$sql);
                        $sn=1;$test;$head;$testname_header;$intr;$group;$group1;
                        while($tab = mysqli_fetch_array($medicine))
                        {
                                 if($test!=$tab['testname'])
                         {
                             echo "</tbody>";
                           
                         }
                        
                         if($testname_header!=$tab['testname_header'])
                         {
                             if($sn>1)
                             {
                                 $group='group-title';
                                 $group1='outer-tr';
                             }
                             echo "<tr class='$group no-page-break'><th colspan=4 class='mt-5 mb-5 text-center'><u>Department of $tab[testname_header]</u></th></tr>";
                         }
                         if($test!=$tab['testname'] )
                         {
                             echo " <tbody class='$group1'><tr ><th colspan=4 class='mt-3 text-left'><u>$tab[testname]</u></th></tr>";
                            
                         }
                         if($head!=$tab['head']&&strlen($tab['head'])>1)
                         {
                             echo "<tr ><th colspan=4 class='mt-5 text-left'>$tab[head]</th></tr>";
                         }
                        ?>
            <tr id='addr1 mt-4'>
                
                <td  style="padding-top:15px;">
                    <?= $tab['test_name']?>
                   <?php if(strlen($tab['method'])>1) echo "<br><p class='small'>Method : $tab[method]</p>";?>
                </td>
               
                <td style="padding-top:15px;">
                <?php
                if (isset($tab['test_result'])) {
                    if ($tab['male_range_max'] < $tab['test_result'] || $tab['male_range_min'] > $tab['test_result']) {
                        // Display test result in bold if male_range_max is outside the normal range
                        echo "<b>" . money_format('%!i', $tab['test_result']) . "</b>";
                    } else {
                        // Display test result normally if male_range_max is within the normal range
                        echo money_format('%!i', $tab['test_result']);
                    }
                } else {
                    // Display nothing if test_result is not set
                    echo "";
                }
                ?>


                </td>
             
                <td style="padding-top:15px;">
                <?= $tab['units']?>
                </td>
               
                <td class="text-center">
                    <?php 
                    
                    echo  "$tab[normal_range]";
                
                ?>

                </td>
                
       
            </tr>
            <?php $sn++; $date=date("d.m.Y",strtotime($tab['date']));
                            $visit_no=$tab['visit_no'];
                            $lab_tech=$tab['lab_tech'];
                            $test=$tab['testname'];
                            $intr=$tab['interpretation'];
                       
                         $head=$tab['head'];
                         $testname_header=$tab['testname_header'];
                         
                        } 
            echo "<script>document.getElementById('bill').innerHTML='$date'</script>";
            echo "<script>document.getElementById('visit_no').innerHTML='$visit_no'</script>";
            ?>

                                 
                
                 <tr>
                   
                    <td colspan="4" class="text-center"><strong class="mt-5">-------------------End of the report-------------------</strong></td>
                
               </tr>
</tbody>
</table>

<!-- Consistent Footer for All Pages -->
<div class="footer d-print-block">
    <div class="footer-left">
        LAB TECHNICIAN
    </div>
    <div class="footer-right">
        <?php 
        if ($testname_header == 'PATHOLOGY') {
            echo 'PATHOLOGIST';
        } elseif ($testname_header == 'BIOCHEMISTRY') {
            echo 'BIOCHEMIST';
        } elseif ($testname_header == 'MICROBIOLOGY') {
            echo 'MICROBIOLOGIST';
        } else {
            echo '&nbsp;';
        }
        ?>
    </div>
</div>

<style>
@media print {
    .footer {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background: #fff;
        border-top: 2px solid #000;
        font-size: 18px;
        padding: 10px 0;
        display: flex;
        justify-content: space-between;
        z-index: 9999;
    }
    .footer-left, .footer-right {
        width: 50%;
        padding: 0 30px;
    }
}
.footer {
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    background: #fff;
    border-top: 2px solid #000;
    font-size: 18px;
    padding: 10px 0;
    display: flex;
    justify-content: space-between;
    z-index: 9999;
}
.footer-left, .footer-right {
    width: 50%;
    padding: 0 30px;
}
</style>
                                           

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <button onclick="window.print()"class="btn btn-danger float-right d-print-none"> <i class="ri-printer-line"></i> Print </button>
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



 <script>
        document.addEventListener("DOMContentLoaded", function () {
            var testname = "<?php echo $test; ?>";
            document.getElementById("suggested-filename").textContent = testname || "Lab_Report";
        });

        function printDocument() {
            window.print();
        }
    </script>





</body>

</html>