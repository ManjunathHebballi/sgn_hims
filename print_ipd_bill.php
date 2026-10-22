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

    <title>Print IPD Bill</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">

    <link href="./css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" ></script>


      <!-- <script>window.onload = function () {
    window.print();
} 
</script> -->

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
                    <div class="col-md-2 text-center">
<img src="images/nexround.JPG" class="img-fluid" alt="">
                    </div>
                    <div class="col-10">
                        <div class="text-center text-150">
                          
                          <!--<h5>HUBLI AYURVEDA SEVA SAMITHI'S </h5>-->
<h2><?= $_SESSION['college_name']?></h2>
<h5><?= $_SESSION['college_location']?></h5>
                        </div>
                    </div>
                </div>
                <!-- .row -->

                <hr class="row brc-default-l1 mx-n1 mb-4" />
                <h3 class="text-center">IPD Final Bill</h3>
<?php $data=$newReport->getIPPatient($_REQUEST['id']);
$row=mysqli_fetch_assoc($data);
?>
                   <div class="table-responsive">

                   <table class="table">
                    <tr>
                        <th width="15%">OP No. :</th>
                        <td><?= $row['opd_no']?></td>
                        <th width="15%" >Patient Name :</th>
                        <td><?= $row['name']?></td>
                        <th  width="15%">Addmission :</th>
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
                    <tr  >
                        <th width="10%">Department: :</th>
                        <td><?= $row['gender']?></td>
                        <th width="20%" >Doctor :</th>
                        <td><?= $row['doctor']?></td>
                        <th>Discharge :</th>
                        <td><?= $row['discharge']?></td>
                    </tr>
                    
                   </table>
                   <hr />
<?php 

$total_amount=100; ?>
                   <table class="table table-bordered ma">
                    <tr class="text-center" style="vertical-align: initial!important;">
                        <th>Sl.No.</th>
                        <th>IPD Services Details</th>
                        <th>Qty</th>
                        <th>Rate</th>
                        <th class="text-right">Amount (in Rs.)</th>
                    </tr>
                    <tbody height="100vh" >
                    <tr  >
                        <td class="text-center">01</td>
                        <td class="text-center">Consultation Charge</td>
                        <td class="text-center">1</td>
                        <td class="text-center">100.00</td>
                        <td class="text-right">100.00</td>
                    </tr>
                    
                   
                    </tbody>
                    <tr class="p-3">

                   
                    <th colspan="4" class="text-center">Total Amount (in Rs.)   </th>
                    <td class="text-right" ><b> <span id="total">100</span> </b></td>
                    </tr>
                    <tr>
                        <th class="text-right" colspan="5">Rupees <span id="words"></span> Only </th>
                    </tr>
                    
                   </table>

                   <table class="table table-bordered mt-5">
                    <tr class="text-center" style="vertical-align: initial!important;">
                        <th>Sl.No.</th>
                        <th>Date</th>
                        
                        <th class="text-right">Advance Amount (in Rs.) </th>
                    </tr>
                    <?php $res=$newReport->getIPAdvance($_REQUEST['id']);
 $sn=1;
 $advance=0;
 while ($adv=mysqli_fetch_assoc($res)) {
?>
                    <tbody height="100vh" >
                    <tr  >
                        <td class="text-center"><?= $sn;?></td>
                        <td class="text-center"><?= $adv['date']?></td>
                    
                        <td class="text-right"><?= $adv['amount']?></td>
                    </tr>
                    </tbody>
                    <?php $sn++;  $advance += $adv['amount'] ;    } ?>
                  
                    
                    <tr class="">
                  
                  
                    <th colspan="2" class="text-center">Total Advance Amount (in Rs.)    </th>
                    
                    <td class="text-right" ><b><?=$advance;?> </b></td>
               
                    </tr>


                  
                   </table>
                   <table class="table table-bordered mt-5">
                    <tr>
                        <th class="text-center">Total Advance Amount (in Rs.) </th>
                        <th class="text-center">Total IPD Bill</th>
                        <th class="text-right">Remaining Amount (in Rs.) </th>
                    </tr>
                    <tr>
                    <th class="text-center"><?=$advance;?></th>
                    <th class="text-center"> <?php echo $total_amount;?> </th>
                    <th class="text-right"><?=$advance - $total_amount;?></th>


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
</body>

</html>