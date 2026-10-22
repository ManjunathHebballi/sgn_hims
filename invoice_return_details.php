<?php 
require_once(__DIR__ . '/model/session_config.php');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#319197">

    <title>Print  Invoice Return </title>
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
    <div class="page-header text-blue-d2">
        <h1 class="page-title text-secondary-d1">
            Invoice
            <small class="page-info">
                <i class="fa fa-angle-double-right text-80"></i>
                ID: #111-222
            </small>
        </h1>

        
    </div>

    <div class="container px-0">
        <div class="row mt-4">
            <div class="col-12 col-lg-12">
                <div class="row">
                    <div class="col-12">
                        <div class="text-center text-150">
                          
                            <img src="logo2.png" alt="" width="300" srcset="">
                        </div>
                    </div>
                </div>
                <!-- .row -->

                <hr class="row brc-default-l1 mx-n1 mb-4" />

                <div class="row">
                    <div class="col-sm-6">
                        <div>
                            <h3 class="text-sm text-success align-middle">Invoiced From:</h3>
      
                        </div>
                        <div class="text-grey-m2">
                            <div class="my-1 font-weight-bold">
                            Nexenstial 
                            </div>
                            <div class="my-1">
                                
           Demo Address                        
                            </div>
                            <div class="my-1">
                                
                            beximco@gmail.com                     
                            </div>
                            <div class="my-2"><b class="text-600">mobile: 8975641230 
</b></div>

                        </div>
                    </div>
                    <!-- /.col -->

                    <div class="text-95 col-sm-6 align-self-start d-sm-flex justify-content-end">
                        <hr class="d-sm-none" />
                        <div class="text-grey-m2">
                        <h3 class="text-sm text-success align-middle text-right ">Invoiced To:</h3>

 <div class="my-2 text-right   font-weight-bold">
                            Manju 
                            </div>
                            <div class="my-1 text-right ">
                                
           Demo Address                        
                            </div>
                            <div class="my-1 text-right ">
                                
                            beximco@gmail.com                     
                            </div>

                            <div class="my-2 text-right"><b class="text-600">mobile: 8975641230 
</b></div>   </div>
                    </div>
                    <!-- /.col -->
                </div>

               

                    <div class="row border-b-2 brc-default-l2"></div>

                    <!-- or use a table instead -->
                    
            <div class="table-responsive">
                <table class="table table-bordered  brc-default-l1">
                    <thead class="thead-dark bgc-default-tp1">
                        <tr class="text-white">
                            <th class="opacity-2">#</th>
                            <th> 	Medicine Name</th>
                            <th>QTY</th>
                            <th>Deduction %</th>
                         
                            <th>Price</th>
                         
                            <th>Total Amount</th>
                        
                        </tr>
                    </thead>

                    <tbody class="text-95 text-secondary-d3">
                    <tr>
                            <td>1</td>
                            <td> 	Fona plus ( 300ml )</td>
                            <td> 	90 </td>
                       
                  
                            <td>700 	</td>
                          
                            <td>700.00 	</td>
                        
                            <td>63,000.00</td>
                        </tr> 

                        <tr>
                            <th>Total</th>
                            <th></th>
                            <th>1</th>
                            <th></th>
                            <th></th>
                            <th>63,000.00</th>
                        </tr>
                       
                        
                       
                    </tbody>


                </table>

            </div>

            <div class="row font-weight-bold" >
                    <div class="col-md-8">
                        <p> Note:</p>
                    </div>
                    <div class="col-md-2 border-top">
                        <p class="text-right">Grand Total:</p>

                    </div>
                    <div class="col-md-2 border-top">
                        <p class="s">63,000.00</p>

                    </div>
                </div>
           

                   
                    <hr />

                    <div>
                        <span class="text-secondary-d1 text-105">Thank you for your business</span>
                        <a href="print_invoice_return.php" target="_blank" data-toggle="tooltip" title="Print Return Invoice" class=" d-print-none btn btn-danger btn-bold  float-right mt-3 mt-lg-0"><i class="icofont-print"></i> Print</a>
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