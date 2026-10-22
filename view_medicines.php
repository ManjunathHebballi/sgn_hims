<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/medicineClass.php');
$newMedicine = new medicine;
if(isset($_REQUEST['editid']))
{
    $unit1=$newMedicine->getUnit($_REQUEST['editid']);
    // print_r($unit1);exit;
    $row=mysqli_fetch_assoc($unit1);
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#319197">

    <title>View Medicines</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" ></script>
        <!-- Sweet Alert-->
     <link href="css/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />


     <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
     <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap4.min.css">


    


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
                    <h4> Medicines Lists</h4>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive" >
                                <table class="table table-bordered" id="example">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th>Sl.No.</th>
                                        <th>Medicine Name</th>
                                        <th>Generic Name</th>
                                        <th>Category</th>
                                        <th>Manufacturer</th>
                                        <th>Shelf</th>
                                        <th>Price</th>
                                        <th>Manufacture Price</th>
                                        <th>Strength</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php $medicine=$newMedicine->getMedicine();
                                        $sn=1;
                                        while($row=mysqli_fetch_assoc($medicine)){
                                        ?>
                                       <tr>
                                       <td><?= $sn?></td>
                                        <td><?= $row['name']?></td>
                                        <td><?= $row['generic_name']?></td>
                                        <td><?= $row['category']?></td>
                                        <td><?= $row['manufacure']?></td>
                                        <td><?= $row['shelf']?></td>
                                        <td>Rs. <?= $row['med_price']?></td>
                                        <td>Rs. <?= $row['manuf_price']?></td>
                                        <td><?= $row['med_strenght']?><?= $row['units']?></td>
                                 
                                        <td><a href="add_medicines.php?editid=<?= $row['id']?>"  class="m-2"data-toggle="tooltip"title="Edit Medicine"><span class="badge badge-light-primary "><i class="icofont-edit icofont-1x"></i></span></a></td>
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
    <script src="vendor/global/global.min.js"></script>
    <script src="js/quixnav-init.js"></script>
    <script src="js/custom.min.js"></script>

<script>
    function showCard(val)
    {
        // alert(val)
        if(val=='BPL')
        {
            document.getElementById('cardno').style.display='inline';
        }
        else{
            document.getElementById('cardno').style.display='none';   
        }
    }
    function getSubdept(val)
    {
        // alert(val)
        if(val=='7')
        {
            document.getElementById('subdept').style.display='inline';
        }
        else{
            document.getElementById('subdept').style.display='none';   
        }
    }
    window.setTimeout(function() {
    $(".alert").fadeTo(500, 0).slideUp(500, function(){
        $(this).remove(); 
    });
}, 4000);
</script>



<!-- Sweet Alerts js -->
<script src="css/sweetalert2/sweetalert2.min.js"></script>

<!-- Sweet alert init js-->
<script src="js/sweet-alerts.init.js"></script>
 

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