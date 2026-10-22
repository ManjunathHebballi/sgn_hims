<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/dbconnect.php');
$newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
if(isset($_REQUEST['opd']))
{
$query="SELECT * FROM `central_opd` WHERE id='$_REQUEST[opd]'";    
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
    <title>Lab Bill</title>
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







    <div class="row">

    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="container">
                    <div class="row">
                        <div class="col-md-1 mr-3">
                            <img src="assets/logo.png" class="" alt="">

                        </div>
                        <div class="col-md-10  align-items-center">
                        <h5 class="text-center text-black">College Name</h5>
                        <h2 class="text-center text-black"> Medical College Hospital</h2>
                        <h5 class="text-center text-black">College Place</h5>
                        <h5 class="text-center text-black">Phone Number</h5>

                        </div>
                    </div><br>
                    
                    <h3 class="text-center text-black"> LABORATORY REPORT </h3>

                    
                

            </tbody></table>
        </div>
        <div class="container">
        <div class="row">
            <table class="table" width="100%">
                <tbody><tr>
                    <th width="15%">Patient Id.</th><td align="left" width="30%"><?php if(isset($_REQUEST[ipd])) echo $row['ipd_no']; else echo $row['opd_no'];?></td>
                    <th width="20%"> Date:</th> <td align="left" width="40%" id="bill"> </td>
                </tr>
                
                <tr>
                    <th width="15%">Patient NAME:</th><td align="left" width="30%"><?= $row['name']?></td>
                    <th width="20%">Patient Address: </th><td align="left" width="40%"><?= $row['address']?></td>
                </tr>
                
                <tr>
                    <th width="15%">Age</th><td align="left" width="10%"><?= $row['age']?></td>
                    <th width="15%">Doctor Name:</th><td align="left" width="40%"><?= $row['name']?></td>
                </tr>

            </tbody></table>
        </div>
    </div>


        <div class="table-responsive">
                                    <table class="table table-bordered  nowrap table-hover">
                                        <thead class="thead-dark" >
                                             <tr>
                                                 <th>Sl. No.</th>
                <th>Test Information</th>
                <th>Qty</th>  
                <th>Rate</th>
                <th>Amount</th>

            </tr>
        </thead>
            <tbody>
            <?php 
            
                    $sql="SELECT lt.testname,GROUP_CONCAT(lg.`test_name`) as test_name,lt.`price`,lr.date FROM `lab_report` lr,`labtest_group` lg,`lab_test` lt WHERE lg.`testid` =lr.`lab_result` and lt.testid=lg.labid and lr.`patient_id`='$id' group by lt.testid";
                    // echo $sql;
                        $medicine = mysqli_query($con,$sql);
                        $sn=1;$total=0;
                        while($tab = mysqli_fetch_array($medicine))
                        {
                        ?>
            <tr id='addr1'>

                <td class="text-center">
                    <?= $sn?>
                   
                </td>
                <td>
                    <?= $tab['testname']?>
                   <ul class="text-center">
                       <?php $test=explode(",",$tab['test_name']);
                       foreach($test as $val) { ?>
                       <li><?= $val?></li>
                       <?php } ?>
                   </ul>
                </td>
                <td>
                    1
                </td>
             
                <td>
                <?= $tab['price']?>
                </td>
               
                <td>
                <?= $tab['price']?>

                </td>
                
       
            </tr>
            <?php $sn++; $date=date("d.m.Y",strtotime($tab['date']));$total +=$tab['price'];} 
            echo "<script>document.getElementById('bill').innerHTML='$date'</script>";
            ?></tbody>
<tfoot>
    <tr>
    <th colspan=4 class="text-right"> Total</th><td><?= $total?></td>
    </tr>
    <tr>
    <td style="bottom:0;  padding-top:10%;" colspan="5" class="text-right" id="lab_tec"><h4>LAB TECHNICIAN</h4></td></tr>
</tfoot>
                                           
                                            

                                            

                                           
      
                                            
                           
                                               
                                            
                                            
                                            </table>   
                                            </div>  
                                            
<?php
$date=date("Y-m-d");
$query="UPDATE lab_report SET bill_date='$date', bill_amount='$total' WHERE `patient_id`='$id'";
mysqli_query($con,$query);
// echo $query;
?>
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

    
    <!-- Datatable -->
    <script src="./vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="./js/plugins-init/datatables.init.js"></script>

  
    <!-- Required datatable js -->
    <script src="assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
        <!-- Buttons examples -->
        <script src="assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
        <script src="assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
        <script src="assets/libs/jszip/jszip.min.js"></script>
        <script src="assets/libs/pdfmake/build/pdfmake.min.js"></script>
        <script src="assets/libs/pdfmake/build/vfs_fonts.js"></script>
        <script src="assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
        <script src="assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
        <script src="assets/libs/datatables.net-buttons/js/buttons.colVis.min.js"></script>

        <script src="assets/libs/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
        <script src="assets/libs/datatables.net-select/js/dataTables.select.min.js"></script>
        

        <!-- Datatable init js -->
        <script src="assets/js/pages/datatables.init.js"></script>








</body>

</html>