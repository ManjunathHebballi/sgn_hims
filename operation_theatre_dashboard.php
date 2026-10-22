<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Operation Theatre Booking</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="icofont/icofont.min.css">

    <!-- <link href="./vendor/pg-calendar/css/pignose.calendar.min.css" rel="stylesheet"> -->
    <link href="./vendor/chartist/css/chartist.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" ></script>
        <!-- Sweet Alert-->
    <link href="css/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>


     <style>
         .badge-light-info {
    background-color: #f8f5ff;
    color: #7239ea;
}
 table.dataTable tbody td {
    padding: 2px !important;
  }
.badge-light-danger {
    background-color: #fff5f8;
    color: #f1416c;}
    .badge-light-success {
    background-color: #e8fff3;
    color: #50cd89;
}

.badge-light-warning {
    background-color: #fbf5eb;
    color: #ffaa16;
}
.icon2{

    width:60%
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

    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">


    

    

  <?php include "leftbar.php"; ?>
  

        <!--**********************************
            Content body start
        ***********************************-->
        <div style='margin-top:13%;' class="mx-3">
            <h3 class="mb-4">Operation Theater Dashboard</h3>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div>
                                <input id="billing-update" type="radio" name="type">
                                <label for="billing-update">Billing Update</label>
                                &nbsp;&nbsp;&nbsp;
                                <input id="surgery-complete" type="radio" name="type">
                                <label for="surgery-complete">Surgery Complete</label>
                            </div>
                            <br />
                            <div class="row">
                                <div class="col-3">
                                    <label for="exampleFormControlInput1" class="form-label">Operation Theater</label>
                                    <select class="form-control form-select" aria-label="Default select example">
                                        <option selected>All</option>
                                        <option value="operation-room-3f">Operation Room 3F</option>
                                        <option value="operation-room-8f">Operatin Room 8F</option>
                                    </select>
                                </div>
                                
                                <div class="col-3">
                                    <div class="mb-3">
                                        <label for="exampleFormControlInput1" class="form-label">From Date</label>
                                        <input type="date" class="form-control" id="exampleFormControlInput1"  required>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="mb-3">
                                        <label for="exampleFormControlInput1" class="form-label">To Date</label>
                                        <input type="date" class="form-control" id="exampleFormControlInput1"  required>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <button class="btn btn-primary mt-4">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
            <div class="mx-3">
                <div class="row">
                    <div class="col-12" >
                        <div class="card">
                            <div class="card-body">
                            <div class="table-responsive">
                                        <table id='datatable-buttons2' class="table table-bordered mb-0">
                                            <thead>
                                                <tr class="text-center font-weighted-bold">
                                                    <th>SN</th>
                                                    <th>Booking Number</th>
                                                    <th>Booking Date</th>
                                                    <th>Operation Theater</th>
                                                    <th>Admission No.</th>
                                                    <th>UHID</th>
                                                    <th>Patient Name</th>
                                                    <th>Mobile</th>
                                                    <th>DOB</th>
                                                    <th>Gender</th>
                                                    <th>From Time</th>
                                                    <th>To Time</th>
                                                    <th>Notifications</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-center">
                                                <tr>
                                                <td>1</td>
                                                <td>BDJJSJ6839</td>
                                                <td>01-01-2001</td>
                                                <td>Test</td>
                                                <td>7398</td>
                                                <td>PLKD3444</td>
                                                <td>test name</td>
                                                <td>8989897654</td>
                                                <td>01-01-2003</td>
                                                <td>Male</td>
                                                <td>03-03-2003</td>
                                                <td>04-04-2004</td>
                                                <td>test</td>
                                                <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        Action
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="#">Anaesthetic Assessment</a></li>
                                                        <li><a class="dropdown-item" href="#">Anaesthetic Record Form</a></li>
                                                        <li><a class="dropdown-item" href="#">Anaesthetic Record Form (Graph)</a></li>
                                                        <li><a class="dropdown-item" href="#">Pre-Operative Verification Checklist</a></li>
                                                        <li><a class="dropdown-item" href="#">Vital Details</a></li>
                                                        <li><a class="dropdown-item" href="#">Blood Request</a></li>
                                                        <li><a class="dropdown-item" href="#">Pre-Operative Note</a></li>
                                                        <li><a class="dropdown-item" href="#">Blood Receive</a></li>
                                                        <li><a class="dropdown-item" href="#">Intra Operating Note</a></li>
                                                        <li><a class="dropdown-item" href="#">Medicine/Drug Request</a></li>
                                                        <li><a class="dropdown-item" href="#">Post-Operative Note</a></li>
                                                        <li><a class="dropdown-item" href="#">Checklist on Leaving Theatre</a></li>
                                                        <li><a class="dropdown-item" href="#">Minor OT Case</a></li>
                                                        <li><a class="dropdown-item" href="#">Doctor Post Operative Notes</a></li>
                                                        <li><a class="dropdown-item" href="#">Billing Update</a></li>
                                                        <li><a class="dropdown-item" href="#">Diagnosis Details</a></li>
                                                        <li><a class="dropdown-item" href="#">Blood Return</a></li>
                                                        <li><a class="dropdown-item" href="#">Essential Obstetric Care</a></li>
                                                        <li><a class="dropdown-item" href="#">Anaesthetic Record Entry</a></li>
                                                        <li><a class="dropdown-item" href="#">CSSD-Request</a></li>
                                                    </ul>
                                                </div>
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
        <!--**********************************
            Content body end
        ***********************************-->


        <!--**********************************
            Footer start
        ***********************************-->
        <?php include "footer.php"; ?>
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
 

<!-- Sweet Alerts js -->
<script src="css/sweetalert2/sweetalert2.min.js"></script>

<!-- Sweet alert init js-->
<script src="js/sweet-alerts.init.js"></script>

<!-- <script src="./vendor/chartist/js/chartist.min.js"></script> -->

<script src="./vendor/moment/moment.min.js"></script>
<!-- <script src="./vendor/pg-calendar/js/pignose.calendar.min.js"></script> -->


<!-- <script src="./js/dashboard/dashboard-2.js"></script> -->
  
<!--<script src="charts.js"></script>-->


</body>

</html>