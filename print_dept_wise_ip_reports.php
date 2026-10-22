<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata"); 


include('model/reportClass.php');
$newReport = new report;




if(isset($_REQUEST['from']))
{
    $dept_from=$_REQUEST['from'];
}
else{
        $dept_from='2000-01-01';
}
if(isset($_REQUEST['to']))
{
    $dept_to=$_REQUEST['to'];
}
else{
        $dept_to='2049-12-01';
}




?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Department Wise IP Report</title>
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
                            <img src="assets/logo.png" class="" alt="" width='150'>

                        </div>
                        <div class="col-md-10  align-items-center">
                        <h2 class="text-center text-black"><?= $_SESSION['college_name']?></h2>
                        <h5 class="text-center text-black"><?= $_SESSION['college_location']?></h5>
                        <h5 class="text-center text-black"><?= $_SESSION['college_phone']?></h5>

                        </div>
                    </div>
                    
                    <h4 class="text-center text-black">Department Wise IP Report
                        <?php if($_REQUEST['from'] !='2000-01-01' && $_REQUEST['to'] !='2049-12-01') { 
                        echo "Between ". date("d.m.Y",strtotime($_REQUEST['from'])) ." and ". date("d.m.Y",strtotime($_REQUEST['to'])); 
                         } ?>
                    </h4>

                    
                

            </tbody></table>
        </div>

 <div class="row mb-4">
                                                <div class="col-md-12">
                                                    <button onclick="window.print()"class="btn btn-danger float-right d-print-none"> <i class="ri-printer-line"></i> Print </button>
                                                </div>
                                            </div>

      
                               <table id="" class="table table-hoverde  text-center">
                                    <thead class="thead-dark">
                                    <tr >
                                                <th>Sl NO</th>
                                                <th>Department Name</th>
                                                <th>No.Of New patients Visited</th>
                                            
                                                
                                                
                                                
                                                
                                            </tr>
                                        </thead>
                                        <tbody class="text-center text-black">
                                            
                                    <?php $dept=$newReport->getDept_IP($dept_from,$dept_to); 
                                    $sn=1;$total=0;
                                    while($row=mysqli_fetch_assoc($dept)){
                                    ?>
                                            <tr>
                                                <td><?= $sn?></td>
                                                <td><?= $row['dept_name']?></td>
                                                <td><?= $row['num']?></td>
                                                
                                                
                                                </tr>
                                                <?php $sn++; $total +=$row['num']; } ?>
                                            
                                            
                                            </tbody>
                                            <tfoot>
                                                <th colspan=2 class="text-right">Total</th>
                                                <th><?= $total?></th>
                                            </tfoot>
                                            </table>   
                                            
                                                <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <h5 class="ml-4">DEPARTMENT WISE Gender wise Count REPORT<span class="text-danger"> 
                         <?php if(isset($_REQUEST['dept_form'])&&isset($_REQUEST['dept_to'])){ ?>
                            Between <?= $_REQUEST['dept_form']?> and <?= $_REQUEST['dept_to']?>
                            <?php } ?>
                            </span></h5>
                    </div>
                    <hr>

                 
                    <table id="" class="table table-hoverde  text-center">
                                    <thead class="thead-dark">
                                    <tr>
                                                <th>Sl NO</th>
                                                <th>Department Name</th>
                                                <th>Male Count</th>
                                                <th>Female Count</th>
                                                <th>Total</th>
                                                
                                                
                                                
                                            </tr>
                                        </thead>
                                        <tbody class="text-center text-black">
                                            <?php $dept=$newReport->getGenderDept_IP($dept_from,$dept_to); 
                                    $sn=1;$male=$female=0;
                                    while($row=mysqli_fetch_assoc($dept)){
                                    ?>
                                            <tr>
                                                <td><?= $sn?></td>
                                                <td><?= $row['dept_name']?></td>
                                                 <td><?= $row['male']?></td>
                                                <td><?= $row['female']?></td>
                                                <td ><?= $row['male'] + $row['female']?></td>
                                                
                                                </tr>
                                                <?php $sn++; $male += $row['male']; $female +=$row['female'];} ?>
                                            
                                               
                                                
                                                
                                            
                                            
                                            </tbody>
                                            <tfoot>
                                                <th colspan=2 class="text-right">Total</th>
                                                <th><?= $male?></th>
                                                <th><?= $female?></th>
                                                <th><?= $male+$female?></th>
                                            </tfoot>
                                            </table>   
                   
                </div>
            </div>
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
  

</body>

</html>