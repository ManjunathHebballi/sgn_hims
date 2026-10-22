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

    <title>View Purchase</title>
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


   
    <div class="container mt-5">
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


                        <div class="row">
                                        <div class="col-12">
                                            <div class="text-center text-150">
                                              
                                                <img src="logo2.png" alt="" width="300" srcset="">
                                            </div>
                                        </div>
                                    </div>
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
                    
                                        <div class="text-95 col-sm-6 ">
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