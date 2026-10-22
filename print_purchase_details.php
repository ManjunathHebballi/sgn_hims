<?php 
require_once(__DIR__ . '/model/session_config.php');
include('model/medicineClass.php');
$medic= new medicine;
$res=$medic->getBillingDetails($_REQUEST['id']);
$data=mysqli_fetch_assoc($res);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#319197">

    <title> Purchase Bill</title>
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


    <script>window.onload = function () {
    window.print();
}
</script>


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
   
   
     
        <!--**********************************
            Content body start
        ***********************************-->
    
            <!-- row -->



<div class="container-fluid ">


    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                  

                <div class="page-content container">
    <div class="page-header text-blue-d2">
        <h1 class="page-title text-secondary-d1">
            Invoice
            <small class="page-info">
                <i class="fa fa-angle-double-right text-80"></i>
                ID: <?= $data['invoice']?>
            </small>
        </h1>

        
    </div>

    <div class="container px-0">
        <div class="row mt-4">
            <div class="col-12 col-lg-12">
                <div class="row">
                    <div class="col-12">
                        <div class="text-center text-150">
                          
                            <img src="images/logo.png" alt="" width="200" srcset="">
                        </div>
                    </div>
                </div>
                <!-- .row -->

                <hr class="row brc-default-l1 mx-n1 mb-4" />

                <div class="row">
                    <div class="col-sm-6">
                        <div>
                            <h3 class="text-sm text-success align-middle">Billing From:</h3>
      
                        </div>
                        <div class="text-grey-m2">
                            <div class="my-1 font-weight-bold">
                            <?= $data['manuf_name']?>
                            </div>
                            <div class="my-1">
                                
           <?= $data['manuf_address']?>                        
                            </div>
                            <div class="my-1">
                                
                            <?= $data['manuf_email']?>                    
                            </div>
                            <div class="my-2"><b class="text-600">mobile: <?= $data['manuf_mobile']?> 
</b></div>

                        </div>
                    </div>
                    <!-- /.col -->

                    <div class="text-95 col-sm-6 align-self-start d-sm-flex justify-content-end">
                        <hr class="d-sm-none" />
                        <div class="text-grey-m2">
                        <h3 class="text-sm text-success align-middle text-right ">Billing To:</h3>

 <div class="my-2 text-right   font-weight-bold">
                            <?= $_SESSION['college_name']?> 
                            </div>
                            <div class="my-1 text-right ">
                                
          <?= $_SESSION['college_location']?>                       
                            </div>
                            <div class="my-1 text-right ">
                                
                            <?= $_SESSION['college_mail']?>                    
                            </div>

                            <div class="my-2 text-right"><b class="text-600">mobile: <?= $_SESSION['college_phone']?>
</b></div>   </div>
                    </div>
                    <!-- /.col -->
                </div>

               

                    <div class="row border-b-2 brc-default-l2"></div>

                    <!-- or use a table instead -->
                    
            <div class="table-responsive">
                <table class="table  brc-default-l1">
                    <thead class="thead-dark bgc-default-tp1">
                        <tr class="text-white">
                            <th class="opacity-2">#</th>
                            <th>Medicine Name</th>
                            <th>Pack Qty</th>
                            <th>QTY(BOX)</th>
                            <th>PCS</th>
                            <th>Batch Id</th>
                            <th>Expire date</th>
                            <th>Rate</th>
                            <th>MRP</th>
                            <th>GST	</th>
                            <th>Total Amount</th>
                        
                        </tr>
                    </thead>

                    <tbody class="text-95 text-secondary-d3">
                        <?php 
                        $res=$medic->getPurchaseDetils1($_REQUEST['id']);
                        $sn=1;$total=$tax=0;
                        while($row=mysqli_fetch_assoc($res))
                        {
                        ?>
                    <tr>
                            <td><?= $sn?></td>
                            <td><?= $row['name']?></td>
                            <td><?= $pack=(($row['total_qty']-$row['free_qty'])/$row['box_qty']);?></td>
                            <td><?= $row['box_qty'].'+'. $row['free_qty']/$pack?></td></td>
                            <td><?= $row['total_qty']?></td>
                            <td><?= $row['batch_id']?></td>
                            <td><?= date("m/Y",strtotime($row['exp_date']))?></td>
                            <td><?= $row['manf_price']?></td>
                            <td><?= $row['box_mrp']?></td>
                            <td><?= $row['tax']?> %</td>
                            <td class="text-right"><?= $row['manf_price']*$row['box_qty']?></td>
                        </tr> 
                       <?php $sn++; 
                       $total +=$row['manf_price']*$row['box_qty'];
                       $tax +=($row['manf_price']*$row['box_qty'])*$row['tax']/100;
                       } ?>
                        <tr>
                            <td colspan="10" class="text-right">Sub Total</td>
                            <td class="text-right"><?= round($total,2)?></td>
                        </tr>
                        <tr>
                            <td colspan="10" class="text-right">GST Tax</td>
                            <td class="text-right"><?= round($tax,2)?></td>
                        </tr>
                        <tr>
                            <td colspan="10" class="text-right">Grand Amount 	</td>
                            <td class="text-right"><?= round($total+$tax,2)?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
           

                   
                    <hr />

                    <div>
                        <span class="text-secondary-d1 text-105">Thank you for your business</span>
                        <!-- <a href="#" onclick="window.print()" data-toggle="tooltip" title="Print Purchase Invoice" class=" d-print-none btn btn-danger btn-bold  float-right mt-3 mt-lg-0"><i class="icofont-print"></i> Print</a> -->
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
        Scripts
    ***********************************-->
    <!--  vendors -->
    <script src="./vendor/global/global.min.js"></script>
    <script src="./js/quixnav-init.js"></script>
    <script src="./js/custom.min.js"></script>








</body>

</html>