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

    <title>Return to Manufacturer</title>
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
 
<style>
     #notification {
            position: absolute;
            top: 90px;
           
            right:2px;
            z-index: 99;
        }
</style>

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

<div aria-live="polite" id="notification" aria-atomic="true" >
<?php if(isset($_REQUEST['success']))
{ ?>
    
<div class="alert alert-success solid alert-dismissible fade show">
                                    <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span>
                                    </button>
                                    <strong>Success!</strong>Record Saved Successfully!!
                                </div>
                         <?php   }
                            ?>
</div>

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
            <h4 class="text-uppercase"> Return To Manufacturer</h4>
        </div>
    </div>
    

    <div class="row">
   <div class="col-md-12">
       <div class="card">
          
           <div class="card-body">
               <div class="row">
                   <div class="col-md-4">
                       <div class="form-group">
                           <label for="">Manufacturer Name </label>
                           <input type="text" name="name" class="form-control" id="name" value="Cosmic"disabled>
                       </div>
                   </div>
                   <div class="col-md-4">
                       <div class="form-group">
                           <label for="">Date</label>
                           <input type="text" name="date" class="form-control" id="date" value="20/02/2022"disabled>
                       </div>
                   </div>
               </div>

               <div class="row">
                   <div class="col-md-12">
                       <table class="table table-bordered">
                           <thead class="thead-dark">
                               <tr>
                                   <th>Medicine Name </th>
                                   <th>Purchase QTY</th>
                                   <th>Stock </th>

                                   <th>Ret Qty<snap class="text-danger">*</snap></th>
                                   <th>Purchase Price </th>
                                   <th>Deduction</th>
                                   <th>Total</th>
                                   <th>Check Return<snap class="text-danger">*</snap></th>
                               </tr>
                           </thead>
                           <tbody>
                               <tr> 
                                   <td><input type="text" name="medicine" id="medicine" class="form-control" disabled value="Fona plus - 300ml  "></td>
                                   <td><input type="text" name="purch_qty" id="purch_qty" class="form-control" disabled value="13500"></td>
                                   <td><input type="text" name="stock" id="s tock" class="form-control"   disabled value="13483"> </td>
                                   <td><input type="text" name="ret_qty" id="ret_qty" class="form-control"  value="00"></td>
                                   <td><input type="text" name="purch_price" id="purch_price" class="form-control"  value="0.00" disabled ></td>
                                   <td><input type="text" name="deduction" id="deduction" class="form-control"  value="0.00"></td>

                                   <td><input type="text" name="totall" id="totall" class="form-control" disabled value="20"></td>
                                   <td><input type="checkbox" name="check" id="check" class="form-control"  value="return"></td>

                                  
                                </tr>
                                <tr >
                                    <td  colspan="5" rowspan="2"> <textarea name="comment" class="form-control" id="comment" cols="3" rows="3"></textarea></td>
                                    <td class="border-right" >Total Deduction:</td>
                                    <td class="border-right" ><input type="text" name="total_deduction" id="total_deduction" class="form-control"  value="0.00" disabled></td>

                                </tr>
          
                                <tr><td class="border-right" >Net Return:</td>
                                    <td class="border-right" ><input type="text" name="net_return" id="net_return" class="form-control"  value="0.00" disabled></td>
</tr>
                           </tbody>
                       </table>

                   <a href="add_returns.php"> <button type="submit" class="btn btn-success float-right">Return</button></a>
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
<script src="../css/sweetalert2/sweetalert2.min.js"></script>

<!-- Sweet alert init js-->
<script src="../js/sweet-alerts.init.js"></script>
  

</body>

</html>