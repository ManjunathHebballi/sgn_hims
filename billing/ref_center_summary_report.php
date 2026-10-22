<?php 
require_once(__DIR__ . '/../model/session_config.php');
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
    <title><?= $_REQUEST['type']?> Report</title>
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
   td{

    font-size:13px
   }

   @media print {
        @page {
    size: A4;
  }
  img{
      width:40%;
  }
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
                        <div class="col-sm-3 ">
                            <img src="assets/logo.png" class="img-fluid" width="150"  alt="">

                        </div>
                        <div class="col-sm-9  align-items-center">
                        <!--<h5 class="text-center text-black"><?= $_SESSION['college_name']?></h5>-->
                        <h2 class="text-center text-black"><?= $_SESSION['college_name']?></h2>
                        <h5 class="text-center text-black"><?= $_SESSION['college_mail']?></h5>
                        <h5 class="text-center text-black"><?= $_SESSION['college_phone']?></h5>

                        </div>
                    </div>
                    
                    <h4 class="text-center text-black"> <?= $_REQUEST['type']?> Ref. Center Report of <?=$_REQUEST['ref_center']?>
                    <?php if($_REQUEST['from'] !='2000-01-01' && $_REQUEST['from'] !='2049-12-01') { 
                        echo "Between ". date("d.m.Y",strtotime($_REQUEST['from'])) ." and ". date("d.m.Y",strtotime($_REQUEST['to'])); 
                         } ?>
                    </h4>

        </div>



        <div >
                                       
                    <table id="datatable-buttons2" class="table table-bordered " role="grid" aria-describedby="datatable-buttons_info">
                                    <thead class="thead-dark">
                                    <tr  >
                                                <th>Sl NO</th>
                                                <th>Date</th>
                                                <th>Bill NO</th>
                                                <th>UHID</th>
                                                <th>OP Reg No</th>
                                                <th>Name</th>
                                                <th>Gender</th>
                                                <th>Mobile</th>
                                                <th>Address</th>
                                                 <th>Gross Amt</th>
                                                 <th>Returned Amt</th>
                                                 <th>Bill Amount</th>
                                                <th>Collected By</th> 
                                                
                                                
                                            </tr>
                                        </thead>
                                        <tbody class="text-center text-black">
                <?php $ot=$newReport->getOPDetailsRef($_REQUEST['from'],$_REQUEST['to'],$_REQUEST['ref_id']);
                $sn=1;$amount=$serv_disc=$paid=0;
                while($row=mysqli_fetch_assoc($ot)){
                ?>
                                            <tr>
                                                <td><?= $sn?></td>
                                                <td><?= date("d.m.Y",strtotime($row['date']))?></td>
                                                <td><?= $row['bill_no']?></td>
                                                <td><?= $row['uhid']?></td>
                                                <td><?= $row['opd_no']?></td>
                                                <td><?= $row['name']?></td>
                                                <td ><?= $row['gender']?></td>
                                                <td ><?= $row['mobile']?></td>
                                                <td class="text-wrap" ><?= $row['address']?></td>
                                                <td ><?= $row['amount'] + $row['returned_amount']?></td>
                                                <td ><?= $row['returned_amount']?></td>
                                                 <td ><?= $row['paid_amount'] - $row['returned_amount']?></td>
                                                 <td ><?= $row['collected_by']?></td>
                                               
                                            </tr>
                     <?php $sn++;
                     $amount +=$row['amount'] + $row['returned_amount'];
                     $serv_disc +=$row['returned_amount'];
                     $paid +=$row['paid_amount'] - $row['returned_amount'];
                     } ?>                       
                                            </tbody>
                                            <tfoot>
                                                <th class="text-right" colspan="9">Total</th>
                                                <th class="text-center"><?= $amount?></th>
                                                <th class="text-center"><?= $serv_disc?></th>
                                                <th class="text-center"><?= $paid?></th>
                                                <th></th>
                                            </tfoot>
                                            </table>    
                                            </div>  

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <button onclick="window.print()"class="btn btn-primary float-right d-print-none"> <i class="ri-printer-line"></i> Print </button>
                                                     <button onclick="window.history.go(-1)" class="btn btn-danger float-right mr-2 d-print-none"> <i class="ri-printer-line"></i> Back </button>
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