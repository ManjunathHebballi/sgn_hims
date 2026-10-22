<?php
require_once(__DIR__ . '/model/session_config.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Pathology Test</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" ></script>
   
    
    <!-- DataTables -->
    <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/datatables.net-select-bs4/css//select.bootstrap4.min.css" rel="stylesheet" type="text/css" />

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">



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
                                    <strong> Data is addedd successfully!</strong> 
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
        <div class="col-md-6">
            <h4 class="text-uppercase">Pathology Test List</h4>
        </div>
        <div class="col-md-6">
            <a href="pathology_test.php" class="btn btn-sm btn-success float-right " > Add Pathology Test</a>
        </div>
    </div>
  

 
  <div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <h6> List</h6>
                <hr>
            </div>
        </div>
    <table id="datatable-buttons" class="table table-hoverde display ">
           <thead>
            <tr>
                <th >Test Name</th>
                <th>Short Name</th>
                <th>Test type</th>
                <th>Category</th>
                <th>Sub Category</th>
                <th>Method</th>
                <th>Report Days</th>
                <th>Tax (%)</th>
                <th>Charge (Rs.)</th>
                <th>Amount (Rs.)</th>
                
                <th class="text-center">Action</th>
            </tr>
           </thead>
           <tbody>
            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>

            

             
                                                                                        <td class="text-center">

                                                <a  class="btn btn-default btn-xs editcharge"  data-toggle="tooltip" title="Edit" ;>
                                                        <i class="fa fa-edit"></i>
                                                    </a>

                                                                                                    <a  class="btn btn-default text-danger btn-xs" data-toggle="tooltip" title="Delete" ;>
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                                                            </td>
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
    <!-- Required vendors -->
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

        <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>


        <script>
        $(document).ready(function(){
   var i=1;
   
  $("#add_row").click(function(){
   $("#addr"+i).html(" <td><select class='form-control ' id='test[]'name='test[]'required><option>Select Test </option></select></td><td><input type='text' name='range[]' class='form-control '   id='range[]'  placeholder='Enter Range' > </td><td><input type='text' name='unit[]' class='form-control '   id='unit[]'  placeholder='Enter Unit' > </td><td class='text-center'><a id='delete_row' name='dlt[]' onclick='dlt("+i+")' class='text-center text-light btn btn-danger' type='button'  data-toggle='tooltip' title='Delete'> <i class='icofont-ui-delete ' ></i></a></td>");
   
   $('#tab_logic').append('<tr id="addr'+(i+1)+'"></tr>');


   i++; 
});
  $("#delete_row").click(function(){
      if(i>1){
      $("#addr"+(i-1)).html('');
      i--;
      }
  });

});
function dlt(id){
      
      $("#addr"+(id)).html('');
     
  };
 </script>


  

</body>

</html>