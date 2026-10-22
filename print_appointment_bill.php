<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");
include("model/reportClass.php");
$newReport= new report;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#319197">

    <title>Print Appointment Bill</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">

    <link href="./css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" ></script>


      <script>window.onload = function () {
    window.print();
} 
</script>

    <style>
        .table th{

            padding:20px;

        }
    </style>
     

</head>

<body>


  


    <!--**********************************
        Main wrapper start
    ***********************************-->
   
   
    




<div class="container mt-5">


    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                  

             

    <div class="container px-0">
        <div class="row mt-4">
            <div class="col-12 col-lg-12">
                <div class="row">
                    <div class="col-12">
                        <div class="text-center text-150">
                          
                          <!--<h5>HUBLI AYURVEDA SEVA SAMITHI'S </h5>-->
<h2><?= $_SESSION['college_name']?></h2>
<h5><?= $_SESSION['college_location']?></h5>
                        </div>
                    </div>
                </div>
                <!-- .row -->

                <hr class="row brc-default-l1 mx-n1 mb-4" />
<?php $data=$newReport->getOPD($_REQUEST['id']);
$row=mysqli_fetch_assoc($data);
?>
                   <div class="table-responsive">

                   <table class="table">
                    <tr>
                        <th width="15%">OP No. :</th>
                        <td><?= $row['opd_no']?></td>
                        <th width="15%" >Patient Name :</th>
                        <td><?= $row['name']?></td>
                        <th  width="15%">Date :</th>
                        <td><?= date("d.m.y h:i:s a")?></td>
                    </tr>
                    <tr  >
                        <th width="10%">Gender :</th>
                        <td><?= $row['gender']?></td>
                        <th width="20%" >Address :</th>
                        <td><?= $row['address']?></td>
                        <th>Mobile :</th>
                        <td><?= $row['mobile']?></td>
                    </tr>
                    
                   </table>
                   <hr />

                   <table class="table table-bordered ma">
                    <tr class="text-center" style="vertical-align: initial!important;">
                        <th>Sl.No.</th>
                        <th>OPD Services Details</th>
                        <th>Qty</th>
                        <th>Rate</th>
                        <th class="text-right">Amount</th>
                    </tr>
                    <tbody height="150vh">
                    <tr >
                        <td class="text-center">01</td>
                        <td class="text-center">Consultation Charge</td>
                        <td class="text-center">1</td>
                        <td class="text-center"><?= $row['consultancy']?></td>
                        <td class="text-right"><?= $row['consultancy']?></td>
                    </tr>
                    </tbody>
                    <tr>

                    <td colspan="2">Amount Recived in Cash <br> <b id="word"> </b></td>
                    <td colspan="2" class="text-center">Net Amt.   </td>
                    <td class="text-right" id="total"><?= $row['consultancy']?></td>
                    </tr>
                   </table>
               
                    
                   </div>

                    <!-- or use a table instead -->
                    
          
            
           

                   
                    <hr />

                    <div>
                        <span class="text-secondary-d1 text-105">Thank you for Visit</span>
                        <a href="#" onclick="window.print();return false;"    data-toggle="tooltip" title="Print  Invoice" class=" d-print-none btn btn-danger btn-bold  float-right mt-3 mt-lg-0"><i class="icofont-print"></i> Print</a>
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

    <script src="./js/custom.min.js"></script>


<script>
    var a = ['','one ','two ','three ','four ', 'five ','six ','seven ','eight ','nine ','ten ','eleven ','twelve ','thirteen ','fourteen ','fifteen ','sixteen ','seventeen ','eighteen ','nineteen '];
var b = ['', '', 'twenty','thirty','forty','fifty', 'sixty','seventy','eighty','ninety'];

function inWords (num) {
    // alert(num)
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
document.getElementById('words').innerHTML = inWords(parseInt(document.getElementById('total').innerHTML));
</script>
</body>

</html>