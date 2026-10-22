<?php
require_once(__DIR__ . '/model/session_config.php');
include('model/medicineClass.php');
$medic= new medicine;
date_default_timezone_set('Asia/Calcutta');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Purchase List</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">

  
            <!-- DataTables -->
            <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/datatables.net-select-bs4/css//select.bootstrap4.min.css" rel="stylesheet" type="text/css" />





</head>

<body>


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


    

    

  <?php include ('leftbar.php')?>
  

        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <!-- row -->



<div class="container-fluid">


    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                   <div class="row m-2 justify-content-between">
                   <h4> Purchase Lists</h4> 
                    <a href="add_purchase.php" type="button" class="btn btn-success btn-sm "> <i class="icofont-plus"></i> Add Purchase</a>
                    </div><hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive" >
                                <table class="table table-bordered" id="example">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th>Sl.No.</th>
                                        <th>Invoice No</th>
                                        <th>	Purchase Id</th>
                                        <th>Manufacturer Name</th>
                                        <th>Date</th>
                                        <th>Total Amount</th>
                                        
                                        <th class="text-center">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sn=1;
                                        $res=$medic->getPurchaseDetails();
                                        while($row=mysqli_fetch_assoc($res))
                                        {
                                        ?>
                                       <tr>
                                       <td><?= $sn?></td>
                                        <td>	<?= $row['invoice']?></td>
                                        <td>	<?= $row['purchase_id']?></td>
                                        <td><?= $row['manuf_name']?></td>
                                        <td><?= date("d.m.Y",strtotime($row['date']))?></td>
                                      
                                        <td>Rs. <?= $row['credit']?></td>
                                       
                                 
                                        <td>
                                            <a href=""  class="m-2"data-toggle="tooltip"title="Edit Purchase"><span class="badge badge-light-primary "><i class="icofont-edit icofont-1x"></i></span></a>
                                            <a href="purchase_details.php?id=<?= $row['purchase_id']?>"  class="m-2"data-toggle="tooltip"title="View Purchase"><span class="badge badge-light-warning "><i class="icofont-eye-alt icofont-1x"></i></span></a>
                                            <a href=""  class="m-2 text-danger"data-toggle="tooltip"title="Delete Purchase"><span class="badge badge-light-danger "><i class="icofont-ui-delete icofont-1x"></i></span></a>
                                        </td>
                                       </tr>
                                      <?php $sn++; } ?>
                                    </tbody>
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
        <!--**********************************
            Content body end
        ***********************************-->


        <!--**********************************
            Footer start
        ***********************************-->
        <?php include('footer.php') ?>
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
    <!--  vendors -->
    <script src="./vendor/global/global.min.js"></script>
    <script src="./js/quixnav-init.js"></script>
    <script src="./js/custom.min.js"></script>

  
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
    var table = $('#example').DataTable( {
        lengthChange: false,
        buttons: [ 'copy', 'excel', 'pdf', 'colvis' ]
    } );
 
    table.buttons().container()
        .appendTo( '#example_wrapper .col-md-6:eq(0)' );
} );
</script>

</body>

</html>