<?php
require_once(__DIR__ . '/model/session_config.php');
include('model/dbconnect.php');
$newlogin = new userlogin;
$con = $newlogin->dbconnect();
$sql = "SELECT c.*,d.`dept_name`,concat(doc.name,' ',doc.last_name) as doctor FROM `central_opd` c,`department` d,doctor doc WHERE c.`dept_id`=d.`id` and c.`id`='$_REQUEST[id]' and doc.id=c.doctor";
$res = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($res);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>OPD Casesheet</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&family=Vina+Sans&display=swap" rel="stylesheet">
</head>



<style>
    @media print {

        .table td,
        .table th {

            padding: 5px;
        }

        .table th,
        .table td {

            border: 1px solid #000000 !important;
        }

        .table-bordered td,
        .table-bordered th {
            border: 5px solid black;
        }

        @page :footer {
            display: none
        }

        @page :header {
            display: none
        }
    }
</style>

<body onload='window.print()' style=" font-family: 'Poppins', sans-serif; background-color:white">

    <!--*******************
        Preloader start
    ********************-->

    <!--*******************
        Preloader end
    ********************-->


    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">







        <!--**********************************
            Content body start
        ***********************************-->

        <!-- row -->







        <div class="row">

            <!--<div class="col-md-12 px-3">  <button onclick="window.print()"class="btn btn-danger float-right d-print-none"> <i class="ri-printer-line"></i> Print </button></div>-->
            <div class="col-md-12">


                <div class="container-fluid m-2">
                    <div class="row">
                        <div class="col-sm-2">
                            <img src="images/logo.png" class=" mr-3" width="100%" alt="">

                        </div>
                        <div class="col-sm-9  align-items-center">
                            <h1 class="text-center text-black text-uppercase font-weight-bold"><?= $_SESSION['college_name'] ?></h1>
                            <h5 class="text-center text-black"><?= $_SESSION['college_location'] ?></h5>
                            <h5 class="text-center text-black"><?= $_SESSION['college_phone'] ?></h5>


                        </div>


                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-sm-10">
                            <table class="table table-bordered text-uppercase" width="100%">
                                <tbody>
                                    <tr>
                                        <th width="20">Date:</th>
                                        <td><?= date("d.m.Y", strtotime($row['date'])) ?> <?= date("h:i A", strtotime($row['created_at'])) ?></td>
                                        <th width="100">UHID:</th>
                                        <td><?= $row['uhid'] ?></td>



                                    </tr>

                                    <tr>
                                        <th width="20">Name:</th>
                                        <td><?= $row['name'] . ' [' . $row['age'] . ' / ' . $row['gender'] . ']' ?></td>
                                        <th width="100">O.P.D. No:</th>
                                        <td><?= $row['opd_no'] ?></td>
                                    </tr>
                                    <tr>
                                        <th width="100">Department:</th>
                                        <td><?= $row['dept_name'] ?></td>
                                        <th>Doctor :</th>
                                        <td><?= $row['doctor'] ?></td>

                                    </tr>
                                    <tr>
                                        <th width="100">Mobile no:</th>
                                        <td><?= $row['mobile'] ?></td>
                                        <th width="100">Address:</th>
                                        <td><?= $row['address'] ?></td>

                                    </tr>


                                </tbody>


                            </table>
                        </div>
                        <div class="col-sm-2 text-center">

                            <h5><b>TOKEN</b></h5>
                            <h1><?= $row['token'] ?></h1>
                        </div>
                    </div>

                    <hr>





                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered text-center mb-5" width="100%">

                                <tr>
                                    <th>Date</th>
                                    <th>Findings</th>
                                    <th>Treatment</th>

                                </tr>


                                <tbody>
                                    <tr height="600px">
                                        <td></td>
                                        <td></td>

                                        <td></td>

                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                    <table class="table table-bordered">
                        <tr>
                            <th colspan="6" class="text-center">INVESTIGATION</th>
                            <th></th>
                        </tr>
                        <tr>
                            <td width="8%">CBC</td>
                            <td width="8%"></td>
                            <td width="8%">SGPT</td>
                            <td width="8%"></td>
                            <td width="8%">CT Scan</td>
                            <td width="8%"></td>
                            <td rowspan="5"></td>
                        </tr>
                        <tr>
                            <td>ESR</td>
                            <td></td>
                            <td>S. Bilirubin</td>
                            <td></td>
                            <td>ECG</td>
                            <td></td>
                        </tr>
                        <tr>

                            <td>BG</td>
                            <td></td>
                            <td>S. Creat</td>
                            <td></td>
                            <td>X-Ray</td>
                            <td></td>

                        </tr>
                        <tr>
                            <td>URINE-RM</td>
                            <td></td>
                            <td>UREA</td>
                            <td></td>
                            <td>ECHO</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>RBS</td>
                            <td></td>
                            <td>S. Electrolytes</td>
                            <td></td>
                            <td>USG</td>
                            <td></td>
                        </tr>

                    </table>
                    <h5>Plans:</h5><br />


                    <div class="row">
                        <div class="col-md-4">
                            <p>Created by : <?= $row['created_by'] ?></p>
                        </div>
                        <div class="col-md-8">
                            <h5 class="text-right ">Signature of Consultant</h5>

                            <button onclick="window.print()" class="btn btn-danger float-right d-print-none"> <i class="ri-printer-line"></i> Print </button>
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

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>

    <script>
        window.addEventListener('afterprint', function() {
            // Close the window after printing
            window.close();
        }, {
            once: true
        });
    </script>
</body>

</html>