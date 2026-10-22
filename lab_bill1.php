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
$name='opd';
}
else{
$query="SELECT * FROM `central_ipd` WHERE ipd_no='$_REQUEST[ipd]'";      
$id=$_REQUEST['ipd'];
$name='ipd';
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
    <meta name="theme-color" content="#319197">

    <title>Invoice Bill</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
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
        <div class="content-body ">
            <!-- row -->



<div class="container ">


    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                  

                <div class="page-content container">
    

    <div class="container px-0">
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
            ?>
<tfoot>
    <tr>
    <th colspan=4 class="text-right"> Total</th><td><?= $total?></td>
    </tr>
   
</tfoot>
                                           
                                            

                                            

                                           
      
                                            
                           
                                               
                                            
                                            </tbody>
                                            </table>   
                                            </div>  
                                            

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <a href="lab_bill.php?<?= $name.'='.$id?>"  class="btn btn-danger float-right d-print-none mt-1"> <i class="ri-bank-card-line"></i> Paid </a>
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

   <?php include('/footer.php') ?> 
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
<script src="../css/sweetalert2/sweetalert2.min.js"></script>

<!-- Sweet alert init js-->
<script src="../js/sweet-alerts.init.js"></script>
 

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