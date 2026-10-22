<?php 
require_once(__DIR__ . '/model/session_config.php');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Patient Billing</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">

    <!-- Datatable -->
    <link href="./vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">

            <!-- DataTables -->
            <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/datatables.net-select-bs4/css//select.bootstrap4.min.css" rel="stylesheet" type="text/css" />

        <!-- Responsive datatable examples -->
        <link href="assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />   



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

            <div class="position-fixed bottom-0 right-0 p-3" style="z-index: 5; right: 0; bottom: 0;">
  <div id="liveToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true" data-delay="4000">
    <div class="toast-header bg-success text-light">
    <i class="ri-checkbox-circle-line mr-3"></i>
      <strong class="mr-auto">Bill Collected Successfully</strong> <br>
    
      <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="toast-body">
<p class="text-black">     Total Rs. 125 is Collected. </p>
    </div>
  </div>
</div>


<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <h4 class="text-uppercase"> PATIENT Billing</h4>
        </div>
    </div>

    <div class="row">
       <div class="col-md-12">
       <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">COIPD</label>
                            <input type="text" class="form-control" name="coipd" value="12364" readonly >
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Patient Name</label>
                            <input type="text" class="form-control" value="Manju" readonly  >
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Gender</label>
                            <input type="text" class="form-control" value="Male" readonly  >
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Age</label>
                            <input type="text" class="form-control" value="42" readonly  >
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
       </div>
    </div>
    

    <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Patient Billing List</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="datatable" class="table table-bordered  nowrap table-hover">
                                        <thead class="thead-dark ">
                                            <tr>
                                                <th>Sl NO</th>
                                              
                                             
                                               
                                                <th>Particulers</th>
                                                <th>Amount</th>
                                                <th>Total</th>
                                                
                                                
                                               
                                              
                                                
                                            </tr>
                                        </thead>
                                        <tbody class=" text-black">
                                            <tr>
                                                <td>01</td>
                                            
                                              
<td>Dolo 650 </td>
                                               <td>Rs.123.00</td>

                                               <td>Rs. 123.00</td>
                                              
                                            </tr>

                                            
                                            <tr>
                                                <th colspan="3" class="text-right">Total</th>
<td>Rs.123.00</td>
                                            </tr>

                                            <tr>
                                                <th colspan="3" class="text-right">Discount</th>
                                               <td><input type="number"  id="number" class="form-control" placeholder="100.00"></td>

                                            </tr>
                                            <tr>
                                                <th colspan="3" class="text-right">Grand Total
                                                <td> Rs.<span id="total">125</span></td>
      
                                                </tr>


                                                <tr>
                                                                                                 <th colspan="3" class="text-right">In Words </th>
                                                                                                 <td class="text-danger"><span id="words"></span></td>

                                                </tr>
                                                <tr>
                                                <th colspan="3" class="text-right">Amount Paid
                                                <td><input type="number" class="form-control" placeholder="100.00"></td>
      
                                                </tr>
                                                <tr>
                                                <td colspan="4" class="text-right"><button type="button" class="btn btn-primary" id="myBtn"><i class="bi bi-cash-stack"></i> Paid</button></td>

                                                </tr>
                                            <!-- <tr>
                                                <td colspan="8" >
                                                <div align="center" class="text-danger">No data available in table <br> <br><img src="images/addnewitem.svg" width="200"><br><br> <span class="text-success bolds"><i class="fa fa-arrow-left"></i> Add new record or search with different criteria.</span><div></div></div>
                                                </td>
                                            </tr> -->
                                            
                                            </tbody>
                                            </table>   
                                            </div>  
                                            </div>
                                            </div></div></div>
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
  
<script>
    $(document).ready(function() {
            $('#datatable-buttons2').DataTable({

                dom: 'Bfrtip',
                responsive: true,
                pageLength: 25,
                // lengthMenu: [0, 5, 10, 20, 50, 100, 200, 500],

                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]

            });
        });
</script>


<script>
    var a = ['','one ','two ','three ','four ', 'five ','six ','seven ','eight ','nine ','ten ','eleven ','twelve ','thirteen ','fourteen ','fifteen ','sixteen ','seventeen ','eighteen ','nineteen '];
var b = ['', '', 'twenty','thirty','forty','fifty', 'sixty','seventy','eighty','ninety'];

function inWords (num) {
    if ((num = num.toString()).length > 9) return 'overflow';
    n = ('000000000' + num).substr(-9).match(/^(\d{2})(\d{2})(\d{2})(\d{1})(\d{2})$/);
    if (!n) return; var str = '';
    str += (n[1] != 0) ? (a[Number(n[1])] || b[n[1][0]] + ' ' + a[n[1][1]]) + 'crore ' : '';
    str += (n[2] != 0) ? (a[Number(n[2])] || b[n[2][0]] + ' ' + a[n[2][1]]) + 'lakh ' : '';
    str += (n[3] != 0) ? (a[Number(n[3])] || b[n[3][0]] + ' ' + a[n[3][1]]) + 'thousand ' : '';
    str += (n[4] != 0) ? (a[Number(n[4])] || b[n[4][0]] + ' ' + a[n[4][1]]) + 'hundred ' : '';
    str += (n[5] != 0) ? ((str != '') ? 'and ' : '') + (a[Number(n[5])] || b[n[5][0]] + ' ' + a[n[5][1]]) + 'only ' : '';
    return str;
}

// document.getElementById('number').onkeyup = function () {
    document.getElementById('words').innerHTML = inWords(document.getElementById('total').innerHTML);
// };
</script>



<script>
    

  $("#myBtn").click(function(){
    $('.toast').toast('show');
  });

</script>
</body>

</html>