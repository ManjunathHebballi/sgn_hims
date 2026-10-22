<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/ipClass.php');
$newIP = new addIP;

$res=$newIP->getTransferBedDetails($_REQUEST['ipd_no'])
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

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
     

    <style>
        .table th{

            padding:1px;
            font-weight:600;
            font-family: 'Poppins', sans-serif;

        }
        body{
            background-color:white!important;
        }
    </style>
     

</head>

<body>


  


    <!--**********************************
        Main wrapper start
    ***********************************-->
   
   
    








             
    
    <div class="container p-4">
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="row">
            <div class="col-12 col-lg-12">
                <div class="row">
                <div class="col-md-1 mr-3">
                            <!--<img src="" height="100px" width="100px"class="" alt="">-->

                        </div>
                        <div class="col-md-10 text-center">
                          
                        
<h2> In-Patient Transfer Intimation </h2>

                        </div>
                    </div>
                </div>
                <!-- .row -->

                <hr class="row brc-default-l1 mx-n1 mb-4" />
                 <?php $row=mysqli_fetch_assoc($res);?>

                   <table class="table">
                    <tr>
                        <th>
                        Transfer No :
                        </th>
                        <td><?=$row['transfer_id']?></td>
                        <th>OP NO </th>
                        <td><?=$row['opd_no']?></td>
                    </tr>
                    <tr>
                        <th>
                        In-Patient No :
                        </th>
                        <td><?=$row['ipd_no']?></td>
                        <th>Trnf Date</th>
                        <td><?= date("d.m.Y",strtotime($row['transfered_date']))?></td>
                    </tr>
                    <tr>
                       
                        <th>Admission Date</th>
                        <td><?= date("d.m.Y",strtotime($row['admission_date']))?></td>
                        <th>Trns UsrId:</th>
                        <td><?=$row['transfered_by']?></td>
                    </tr>
                    
                  
                   
                    <tr>
                         <th  >Patient Name :</th>
                        <td><?=$row['name']?></td>
                         <th>Doctor :</th>
                        <td><?=$row['doctor']?></td>
                   </tr>
                   <tr>
                    <th colspan="2">TRANSFER FROM </th>
                    <th colspan="2">TRANSFER TO </th>

                   </tr>
                   <tr>
                    <th>
                    Bed No
                    </th>
                    <td><?=$row['o_bed']?></td>
                    <th>
                    Bed No
                    </th>
                    <td><?=$row['n_bed']?></td>
                   </tr>
                   <tr>
                    <th>
                    Ward
                    </th>
                    <td><?=$row['old_ward']?></td>
                    <th>
                   Ward
                    </th>
                    <td><?=$row['new_ward']?></td>
                   </tr>
                   
                    
                   </table>
                   <hr />
<br>
<br>
                   <p class="mt-3"><span style="margin-left:100px">I</span>, the undersigned desire to transfer my patient from Bed No.<?=$row['o_bed']?> of Ward <?=$row['old_ward']?>
 To Bed No. <?=$row['n_bed']?> of Ward <?=$row['new_ward']?> and agree to pay the service charges chargeable to the
 various services and accommodation on account of transfer of the above
 patient.</p>
                 <br><br>    

                    <!-- or use a table instead -->
                    
          
            
           
                   
                    <hr />

                      
                        
                    </div>
                </div>
            </div>
            <div class="row">
            <div class="col-md-6">
               <h6> Approved by </h6> 
<h6> Date/Time : <?= date("d.m.Y h:i:s A",strtotime($row['transfered_date']))?></h6>
            </div>
            <div class="col-md-6">
                <h6>Signature of the patient/Guardian</h6>
                <h6>Name (In Block Letters) <span class="text-uppercase"></span>  </h6>
            </div>
           </div>

        </div>
        <div class="row p-5">
                            
                 <div class="col-md-12">
                            <a href="#" onclick="window.print();return false;"  data-toggle="tooltip" title="Print  Invoice" class=" d-print-none btn btn-danger btn-bold  float-right mt-3 mt-lg-0 ml-3"><i class="icofont-print"> </i> Print Bill </a>
                        
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