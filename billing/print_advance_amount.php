<?php 

require_once(__DIR__ . '/../model/session_config.php');
date_default_timezone_set("Asia/Kolkata");
include('model/billingClass.php');
$newBill = new addBilling;
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

            padding:10px;
            font-weight:600;
            font-family: 'Poppins', sans-serif;

        }
        .align td{
            vertical-align:top !important;
        } 
          @page {
            margin: 0;
            size: A4; /* Set page size to A4 */
        }

        @media print {
            .watermark {
                position: absolute;
                font-size: 50px; /* Adjust font size as needed */
                color: rgba(0, 0, 0, 0.5); /* Set text color with reduced opacity */
                transform: rotate(-45deg) scaleX(2);
            }

            .top-left {
                top: 65%; /* Position the top-left watermark at 10% from the top */
                left: 30%;
               /* transform: rotate(-45deg); /* Rotate the text */
            }

            .bottom-right {
                bottom: 40%; /* Position the bottom-right watermark at 10% from the bottom */
                right: 40%;
               /* transform: rotate(-45deg); /* Rotate the text */
            }
        }
    </style>
     

</head>

<body class="text-uppercase" onload="window.print()">


  


    <!--**********************************
        Main wrapper start
    ***********************************-->
   
   
    




<div class="container mt-3 ">


    <div class="row">
        <div class="col-md-12">
            
            <div class="card">
                <div class="card-body">
           
    <div class="container p-4">
        <div class="row mt-4">
            <div class="col-12 col-lg-12">
                <div class="row mt-4">
            <div class="col-12 col-lg-12">
                <div class="row">
                <div class="col-sm-2">
                            <img src="assets/logo.png" class="" width="100" alt="">
                        </div>
                        <div class="col-sm-8 text-center">
                          
                          <h5><?= $_SESSION['college_society']?></h5>
<h2> <?= $_SESSION['college_name']?></h2>
<h5><?= $_SESSION['college_location']?></h5>
                        </div>
                        <div class="col-sm-2">
                             <h6 class="text-right"> Patient Copy </h6>      

             
                        </div>
                    </div>
                </div>
                <!-- .row -->

                <hr class="row brc-default-l1 mx-n1 mb-4" />
               
<?php   $data=$newBill->getIPD2($_REQUEST['id']);
$row=mysqli_fetch_assoc($data);
?>
              <p>Adv No: <?php echo $_REQUEST['adv_id']; ?> </p>   

                   <table class="table table-bordered">
                   
                    <tr>
                        <th >IPD No. :</th>
                        <td><?= $row['ipd_no']?></td>
                        <th  >Patient Name :</th>
                        <td><?= $row['name']?></td>
                        <th  >Date :</th>
                        <td><?= date("d.m.y h:i:s a",strtotime($row['adv_date']))?></td>
                        <th>Gender :</th>
                        <td><?= $row['gender']?></td>
                    </tr>
                    <tr  >
                      
                        <th >Address :</th>
                        <td><?= $row['address']?></td>
                        <th>Mobile :</th>
                        <td><?= $row['mobile']?></td>
                          <th>Dept:</th>
                        <td><?= $row['dept_name']?></td>
                          <th>Doctor :</th>
                        <td><?= $row['doctor']?></td>
                    </tr>
                    
                   </table>
                   <hr />

                   <table class="table table-bordered">
                    <tr class="text-center" >
                        <th>Sl.No.</th>
                        <th>IPD Services Details</th>
                        <th>Qty</th>
                        <th>Rate</th>
                        <th class="text-center">Amount</th>
                    </tr>
                    <tbody height="100vh" class="align">
                     <?php  $sn=1; $tot=0; $collected_by='';
                            $data1=$newBill->getAdvanceAmt($_REQUEST['id'],$_REQUEST['adv_id']);
                            while($row1=mysqli_fetch_assoc($data1))
                            { 
                            $collected_by=$row1['created_by'];
                            ?>
                    <tr>
                        <td class="text-center"><?php echo $sn; ?></td>
                        <td class="text-center">Advance Amount</td>
                        <td class="text-center">1</td>
                        <td class="text-center"><?= $row1['amount'];?></td>
                        <td class="text-center"><?= $row1['amount'];?></td>
                    </tr>
                     <?php if($_REQUEST['status']=='CANCELLED') { ?>    <div class="watermark top-left">CANCELLED</div> <?php } ?>
                    <?php $sn++; $tot += $row1['amount']; 
                    $pay=$row1['payment_type'];
                    } ?>
                    </tbody>
                   <tr>
                       <td class="text-right">Payment Mode</td>
                    <td class="text-center" id="total"><?= $pay;?></td>
                     <td colspan="2" class="text-right">Net Amt.   </td>
                    <td class="text-center" id="total"><?= $tot;?></td>
                     </tr>
                    <tr>
                    <td colspan="4" class="text-right">Collected By   </td>
                    <td class="text-center" id="total"><?= $collected_by;?></td>
                    </tr>
                   
                   </table>
               
                    
                

                    <!-- or use a table instead -->
                    
          
            
           

                   
                    <hr >

                    <div>
                        <span class="text-secondary-d1 text-105 mt-4">Thank you for Visit</span>
                        
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
                            
                 <div class="col-md-12">
                            <a href="#" onclick="window.print();return false;"  data-toggle="tooltip" title="Print  Invoice" class=" d-print-none btn btn-danger btn-bold  float-right mt-3 mt-lg-0 ml-3"><i class="icofont-print"> </i> Print Bill </a>
                        <!--<a href="opcasesheet.php?id=<?= $_REQUEST['id']?>"   data-toggle="tooltip" title="Print  Case Sheet" class=" d-print-none btn btn-danger btn-bold  float-right mt-3 mt-lg-0 ml-3">Print Case Sheet</a>-->
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
    window.addEventListener('afterprint', function () {
        // Close the window after printing
        window.close();
      }, { once: true });

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