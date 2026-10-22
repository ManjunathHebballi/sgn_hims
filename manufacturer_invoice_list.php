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

    <title>Manufacturer Return List</title>
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
        <div class="content-body">
            <!-- row -->



<div class="container-fluid">

<div class="row mb-3">
        <div class="col-md-12">
            <h4 class="text-uppercase">Manufacturer Return List</h4>
        </div>
    </div>
<div class="row">

<div class="col-md-12">
    <div class="card">
        <div class="card-body">

        <div class="row">
        <div class="col-md-6">
        <h4>  Select Criterea</h4>
        </div>

        <div class="col-md-6">
              <a href="add_returns.php" type="button" class="btn btn-success btn-sm float-right "> <i class="icofont-plus"></i> Add Returns</a>

              </div>
              
        </div>
           <br>
          <form action="">
          <div class="row">
              
              
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="">From Date </label>
                      <input type="date" name="from" id="from" class="form-control">
                      
                      
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="">To Date </label>
                      <input type="date" name="to" id="to" class="form-control">
                      
                      
                  </div>
              </div>
              </form>
          </div>
        </div>
    </div>
</div>
</div>


    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                   <div class="row m-2 justify-content-between">
                   <h4> Invoice Return List</h4> 
                    </div><hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive" >
                                <table class="table table-bordered" id="example">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th>Sl.No.</th>
                                        <th>Purchase Id</th>
                                        <th>	Manufacturer Name</th>
                                        <th>Date</th>
                                        <th>Total Amount</th>
                                        
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                       <tr>
                                       <td>1</td>
                                        <td>	87666</td>
                                        <td>	Amnju</td>
                                        
                                        <td>2021-12-27</td>
                                      
                                        <td>Rs. 236</td>
                                       
                                 
                                        <td><a href="manufacturer_invoice_detail.php"  class="m-2"data-toggle="tooltip"title="View Return Details "><span class="badge badge-light-warning "><i class="icofont-eye-alt icofont-1x"></i></span></a></td>
                                       </tr>
                                      
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
<script src="./css/sweetalert2/sweetalert2.min.js"></script>

<!-- Sweet alert init js-->
<script src="./js/sweet-alerts.init.js"></script>
 

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