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
            <h3 class="mb-4">OT Resources Master</h3>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-3">
                                    <div class="mb-3">
                                        <label for="exampleFormControlInput1" class="form-label">Resources</label>
                                        <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Enter Resources" required>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <label for="exampleFormControlInput1" class="form-label">Employee Type</label>
                                    <select class="form-control form-select" aria-label="Default select example">                   
                                        <option selected disabled value="">Select</option>
                                        <option value="X-Ray Technician">X-Ray Technician</option>
                                        <option value="Visceral Surgeon">Visceral Surgeon</option>
                                        <option value="Urologist">Urologist</option>
                                        <option value="Thorax Surgeon">Thorax Surgeon</option>
                                        <option value="Team">Team</option>
                                        <option value="System Administrator">System Administrator</option>
                                        <option value="Surgeon">Surgeon</option>
                                        <option value="Stock Controller">Stock Controller</option>
                                        <option value="Stock Assistant">Stock Assistant</option>
                                        <option value="Senior ER-ICU Specialist">Senior ER-ICU Specialist</option>
                                        <option value="RADIOLOGY TECHNICIAN">RADIOLOGY TECHNICIAN</option>
                                        <option value="Radiology Technician">Radiology Technician</option>
                                        <option value="RADIOLOGY SUPERVISOR">RADIOLOGY SUPERVISOR</option>
                                        <option value="RADIOLOGY INCHARGE">RADIOLOGY INCHARGE</option>
                                        <option value="RADIOLOGY DOCTOR">RADIOLOGY DOCTOR</option>
                                        <option value="Radiology Doctor">Radiology Doctor</option>
                                        <option value="RADIOLOGY DIRECTOR">RADIOLOGY DIRECTOR</option>
                                        <option value="Radiologist">Radiologist</option>
                                        <option value="Psychiatrist">Psychiatrist</option>
                                        <option value="Pneumologist">Pneumologist</option>
                                        <option value="Plastic Surgeon">Plastic Surgeon</option>
                                        <option value="Physiotherapist">Physiotherapist</option>
                                        <option value="Pharmacist">Pharmacist</option>
                                        <option value="Pediatrician">Pediatrician</option>
                                        <option value="Orthopedic Surgeon">Orthopedic Surgeon</option>
                                        <option value="Opthalmologist">Opthalmologist</option>
                                        <option value="Oncologist">Oncologist</option>
                                        <option value="OB-GYN Specialist">OB-GYN Specialist</option>
                                        <option value="Nurse">Nurse</option>
                                        <option value="Neuro-Surgeon">Neuro-Surgeon</option>
                                        <option value="Neuro-Paediatrican">Neuro-Paediatrican</option>
                                        <option value="Neurologist">Neurologist</option>
                                        <option value="Nephrologist">Nephrologist</option>
                                        <option value="Neonatologist">Neonatologist</option>
                                        <option value="Midwife">Midwife</option>
                                        <option value="Medical Doctor">Medical Doctor</option>
                                        <option value="Medical Director">Medical Director</option>
                                        <option value="Maxilofacial Surgeon">Maxilofacial Surgeon</option>
                                        <option value="Marketing Personnel">Marketing Personnel</option>
                                        <option value="Maintenance Technician">Maintenance Technician</option>
                                        <option value="Laparoscpoic Surgeon">Laparoscpoic Surgeon</option>
                                        <option value="LABORATORY TECHNICIAN">LABORATORY TECHNICIAN</option>
                                        <option value="LABORATORY SUPERVISOR">LABORATORY SUPERVISOR</option>
                                        <option value="LABORATORY INCHARGE">LABORATORY INCHARGE</option>
                                        <option value="LABORATORY DOCTOR">LABORATORY DOCTOR</option>
                                        <option value="LABORATORY DIRECTOR">LABORATORY DIRECTOR</option>
                                        <option value="IT Personnel">IT Personnel</option>
                                        <option value="Internist">Internist</option>
                                        <option value="Insurance Billing">Insurance Billing</option>
                                        <option value="Infectious Disease Specialist">Infectious Disease Specialist</option>
                                        <option value="HR Personnel">HR Personnel</option>
                                        <option value="Housekeeper">Housekeeper</option>
                                        <option value="Hospital Director">Hospital Director</option>
                                        <option value="Hematologist">Hematologist</option>
                                        <option value="GP Ultrasound">GP Ultrasound</option>
                                        <option value="GI Specialist">GI Specialist</option>
                                        <option value="Front Desk">Front Desk</option>
                                        <option value="Family Doctor">Family Doctor</option>
                                        <option value="ER-ICU Specialist">ER-ICU Specialist</option>
                                        <option value="ENT Specialist">ENT Specialist</option>
                                        <option value="Endocrinologist">Endocrinologist</option>
                                        <option value="Employee">Employee</option>
                                        <option value="Driver">Driver</option>
                                        <option value="Doctor">Doctor</option>
                                        <option value="Dermatologist">Dermatologist</option>
                                        <option value="Consultant">Consultant</option>
                                        <option value="Cashier">Cashier</option>
                                        <option value="Cardiologist">Cardiologist</option>
                                        <option value="Assistant Doctor">Assistant Doctor</option>
                                        <option value="Anaesthetist">Anaesthetist</option>
                                        <option value="Admin">Admin</option>
                                        <option value="Accountant">Accountant</option>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label for="exampleFormControlInput1" class="form-label">Status</label>
                                    <select class="form-control form-select" aria-label="Default select example">
                                        <option selected disabled>Select Option</option>
                                        <option value="ACTIVE">Active</option>
                                        <option value="INACTIVE">Inactive</option>
                                    </select>
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
                                                    <th>Resources</th>
                                                    <th>Employee Type</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-center">
                                                <tr>
                                                <td>Test</td>
                                                <td>Employee Type - Test</td>
                                                <td>Active</td>
                                                <td>
                                                    <button class="btn btn-primary">Edit</button>&nbsp;<button class="btn btn-danger">Trash</button>
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