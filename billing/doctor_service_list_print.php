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
    <title>Doctor Revenue Report</title>
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

    font-size:15px
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

<body style=" font-family: 'Poppins', sans-serif;" class="text-uppercase"  onload="window.print()">

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
                    
                    <h4 class="text-center text-black"> Doctor Revenue Report 
                    <?php if($_REQUEST['from'] !='2000-01-01' && $_REQUEST['from'] !='2049-12-01') { 
                        echo "Between ". date("d.m.Y",strtotime($_REQUEST['from'])) ." and ". date("d.m.Y",strtotime($_REQUEST['to'])); 
                         } ?>
                    </h4>

                    
                

            </tbody></table>
        </div>



        <div class="table-responsive">
                                    <table class="table table-bordered   table-hover" width="80">
                                        <thead class="thead-dark" >
                                            <tr class="dark">
                                               <th>Sl NO</th>
                                               <th>Date</th>
                                                <th>Bill NO</th>
                                                <th>Patient Reg No</th>
                                                <th>Name</th>
                                                <th>Gender</th>
                                                <th>Service Name</th>
                                                <th>Service Amt</th>
                                                <th>Service Level Disc</th>
                                                <th>Net Amount</th>
                                                 <th>Billed By</th>
                                                 <!--<th>Payment Mode</th>-->
                                                
                                                
                                               
                                              
                                                
                                            </tr>
                                        </thead>
                                        <tbody class="text-black">
<?php 
$cent = $newReport->getDoctorCollectionDetails($_REQUEST['from'],$_REQUEST['to'],$_REQUEST['search'],$_REQUEST['service'],$_REQUEST['bill_type']);

$sn = 1;
$total = $grand = $count = $allcount = $serv = $allserv = $disc = $alldisc = $net = $allnet = $doctor = $alldoctor = $hospital = $allhospital = 0;
$dr = '';

$data = [];
while ($row = mysqli_fetch_assoc($cent)) {
    // Group under doctor first
    $data[$row['doctor']][] = $row;
}

foreach ($data as $doctorName => $doctorRows) {
    // doctor heading
    echo "<tr><th colspan='11'>{$doctorName}</th></tr>";

    // Now group inside each doctor by (date,bill_no,patient,gender)
    $grouped = [];
    foreach ($doctorRows as $row) {
        $key = $row['date'].'|'.$row['bill_no'].'|'.$row['patient'].'|'.$row['gender'];
        $grouped[$key][] = $row;
    }

    $serv = $disc = $net = $doctorShare = $hospitalShare = 0;

    foreach ($grouped as $key => $rows) {
        $rowspan = count($rows);
        $first = true;
        foreach ($rows as $row) {
            $net1 = $row['amount'] - $row['discount'];

            echo "<tr>";
            if ($first) {
                echo "<td rowspan='$rowspan'>{$sn}</td>";
                echo "<td rowspan='$rowspan'>{$row['date']}</td>";
                echo "<td rowspan='$rowspan'>{$row['bill_no']}</td>";
                echo "<td rowspan='$rowspan'>{$row['opd']}</td>";
                echo "<td rowspan='$rowspan'>{$row['patient']}</td>";
                echo "<td rowspan='$rowspan'>{$row['gender']}</td>";
            }

            echo "<td>{$row['name']}</td>";
            echo "<td>{$row['amount']}</td>";
            echo "<td>{$row['discount']}</td>";
            echo "<td>{$net1}</td>";
         if ($first) {
            echo "<td rowspan='$rowspan'>{$row['collected_by']}</td>";
            }
            // echo "<td>{$row['payment_mode']}</td>";
            echo "</tr>";

            $first = false;

            // subtotal calculations
            $serv += $row['amount'];
            $disc += $row['discount'];
            $net += $net1;
            $doctorShare += round($row['share_amt']);
            $hospitalShare += $net1 - $row['share_amt'];
        }
        $sn++;
    }

    // subtotal row per doctor
    echo "<tr>
        <th colspan='7' class='text-center'>Total</th>
        <th>{$serv}</th>
        <th>{$disc}</th>
        <th>{$net}</th>
        <th></th>
    </tr>";

    $allserv += $serv;
    $alldisc += $disc;
    $allnet += $net;
    $alldoctor += $doctorShare;
    $allhospital += $hospitalShare;
}

// Grand total row
echo "<tr>
    <th colspan='7' class='text-center'>Grand Total</th>
    <th>{$allserv}</th>
    <th>{$alldisc}</th>
    <th>{$allnet}</th>
    <th></th>
</tr>";
?>
</tbody>

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


  <script>
     $(document).ready(function() {
    $('table.display').DataTable();
} );
  </script>
  
<script>
    $(document).ready(function() {
            $('#datatable-buttons2').DataTable({

                dom: 'Bfrtip',
                responsive: true,
                pageLength: 25,
                // lengthMenu: [0, 5, 10, 20, 50, 100, 200, 500],

                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]

            });
        });
</script>



</body>

</html>