<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");
include("model/reportClass.php");
$newReport = new report;

$rows = array();
$selectedIds = array();

if (isset($_REQUEST['id'])) {
    $rawIds = is_array($_REQUEST['id']) ? $_REQUEST['id'] : explode(',', (string)$_REQUEST['id']);
    foreach ($rawIds as $rawId) {
        $rawId = trim((string)$rawId);
        if ($rawId !== '' && ctype_digit($rawId)) {
            $selectedIds[] = (int)$rawId;
        }
    }
    $selectedIds = array_values(array_unique($selectedIds));
}

foreach ($selectedIds as $ipId) {
    $dept = $newReport->getIPDetailsForSlip($ipId);
    if ($dept && mysqli_num_rows($dept) > 0) {
        $rows[] = mysqli_fetch_assoc($dept);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#319197">

    <title>Print Admission </title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">

    <link href="./css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" ></script>

 
      <script>window.onload = function () {
    window.print();
      window.addEventListener('afterprint', function () {
        // Close the window after printing
        window.close();
      }, { once: true });

} 
</script> 

    <style>
        .table th{
            padding:5px;
            font-size:15px;
            font-weight:600;
        }
        .table td{
            padding:5px;
            font-size:14px;
        }
        .section-title th,
        .section-title td{
            background:#f5f5f5;
            font-weight:700;
        }
        .page-break{
            page-break-before: always;
            break-before: page;
        }
        body{
            margin-top:20px;
            background-color:white;
        }
        @media print{
            body{
                margin-top:0;
            }
            .page-break{
                page-break-before: always;
                break-before: page;
            }
        }
    </style>
     

</head>

<body>


  


    <!--**********************************
        Main wrapper start
    ***********************************-->
   
   
    




<div class="container ">
<?php if (!empty($rows)) { ?>
<?php foreach ($rows as $index => $res) { ?>
    <div class="container px-0 p-4">
        <div class="row ">
            <div class="col-12 col-lg-12">
                <div class="row">
                        <div class="col-sm-1 mr-3">
                            <img src="assets/logo.png" class="" alt="" width=150>

                        </div>
                        <div class="col-sm-10  align-items-center">
                        <h2 class="text-center text-black"><?= $_SESSION['college_name']?></h2>
                        <p class="text-center text-black"><?= $_SESSION['college_location']?>, <?= $_SESSION['college_phone']?></p>
                       

                        </div>
                    </div>
                </div>
                <!-- .row -->

               
                    
                   </div>

                    <!-- or use a table instead -->
                    
          <hr>
            <h2 class="text-center m-3">IP Admission Slip</h2>
            <hr>
           <table class="table table-bordered  text-uppercase">
                                            <tr>
                                                <th>Date of Admission </th>
                <td><?= date("d.m.Y h:i:s A",strtotime($res['admission_date']))?></td>
                 <th>UHID</th>
        
                
         <td > <?= $res['uhid']?></td>
                                            </tr>
            <tr>
                
                <th>MRD IP No. </th>
                <td><?= $res['ipd_no']?></td>
                <th>OPD Reg. No. </th>
                <td><?= $res['opd_no']?></td>
                
                
            </tr>
            <tr>
                <th>Patient Name</th>
              <td colspan="4"><?= $res['name']?></td>
              </tr>
              <tr>
                   <th>Address</th>
              <td colspan="4"><?= $res['address']?></td>
              </tr>
              <tr>
                <th>Mobile</th>
                 <td><?= $res['mobile']?></td>
                 <th>Gender</th>
                  <td><?= $res['gender']?></td>
               
            </tr>
            <tr> <th>Age</th>
              <td><?= $res['age']?></td>
                <th>Charge Type</th>
                 <td><?= $res['patient_type']?></td>
       
       </tr>
       <tr>
           <th>Consultant</th>
                
         <td><?= $res['doctor_name']?></td>
            <th>Department</th>
               <td><?= $res['dept_name']?></td>
               
       </tr>
       <tr>
            <th>MLC  </th>
                <td><?= $res['mlc']?></td>   
         <th>Bed Number</th>
               <td><?= $res['bed_number']?></td>
            </tr>
      <tr>
          
            <th>Patient Type</th>
               <td>ADMITTING</td>
              <th>Remark</th>
               <td><?= $res['remark']?></td> 
       </tr>
       
          
           </table>


    <table class="table table-bordered mt-4">
    <thead>
        <tr>
            <th colspan="4" class="text-center" style="font-size:20px">
                BILLING INFORMATION
            </th>
        </tr>
        
    </thead>
    <tbody>
        <tr>
            <th width="25%">Deposit</th>
            <td></td>
            <th width="25%">Infusion</th>
            <td></td>
        </tr>
         <tr>
            <th>Case Regd. Charg. </th>
            <td></td>
            <th>BT Charge.
    </th>
            <td></td>
        </tr>
         <tr>
            <th>Operation Charg. </th>
            <td></td>
            <th>Dressing Charge.
    </th>
            <td></td>
        </tr>
         <tr>
            <th>Anesthesia Charg. </th>
            <td></td>
            <th>Inj.S.K
    </th>
            <td></td>
        </tr>
         <tr>
            <th>Implant Charg. </th>
            <td></td>
            <th>Intubation
    </th>
            <td></td>
        </tr>
         <tr>
            <th>Laboratory <br>
    Investigation
    </th>
                <td></td>
                <th>Sonography <br>
    Charge.
    </th>
            <td></td>
        </tr>
         <tr>
            <th>Oxygen Charg.</th>
            <td></td>
            <th>RBS</th>
            <td></td>
        </tr>
         <tr>
            <th>General Ward Charg.</th>
            <td></td>
            <th>Nebulizer</th>
            <td></td>
        </tr>
         <tr>
            <th>ICU Ward Charg.</th>
            <td></td>
            <th>Ventilator</th>
            <td></td>
        </tr>
         <tr>
            <th>Sp Room Charge
    </th>
                <td></td>
                <th>Catheteration
    Charg.</th>
                <td></td>
            </tr>
            <tr>
                <th>Isolation Ward Charg.</th>
                <td></td>
                <th>Nursing Charg.
    </th>
            <td></td>
        </tr>
         <tr>
            <th>Medical Bill </th>
            <td></td>
            <th>ECG Charg.
    </th>
                <td></td>
            </tr>
            <tr>
                <th>MLC</th>
                <td></td>
                <th>BIPAP
    </th>
            <td></td>
        </tr>
         <tr>
            <th>MLC DR</th>
            <td></td>
            <th>Swiper Charg.</th>
            <td></td>
        </tr>
     
      <tr>
            <th height="30px"></th>
            <th></th>
              <th></th>
                <th></th>
                </tr>
                 <tr>
            <th height="30px"></th>
            <th></th>
              <th></th>
                <th></th>
                </tr>
        
     
        
    </tbody>
    </table>

    <table class="table table-bordered mt-4">
    <thead>
        <tr>
            <th>
                Visiting Dr.
            </th>
            <th>Day 1</th>
             <th>Day 2</th>
              <th>Day 3</th>
              
               <th>Day 4</th>
                <th>Day 5</th>
                 <th>Day 6</th>
                  <th>Total</th>
        </tr>
        
    </thead>
    <tbody>
   <?php for ($i=1;$i<5;$i++){ ?>
        <tr>
            <th height="30px"></th>
            <th></th>
              <th></th>
                <th></th>
                  <th></th>
                    <th></th>
                      <th></th>
                        <th></th>
                      
        </tr>

   <?php } ?>
        </tbody>
        </table>
                   
                    <hr />
    <div>
        <p>Created by : <?= $res['created_by']?></p>
    </div>
                    <div>
                        <a href="#" onclick="window.print();return false;" data-toggle="tooltip" title="Print" class="d-print-none btn btn-danger btn-bold float-right mt-3 mt-lg-0"><i class="icofont-print"></i> Print</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php if ($index < count($rows) - 1) { ?>
        <div class="page-break"></div>
    <?php } ?>
<?php } ?>
<?php } else { ?>
    <div class="alert alert-warning d-print-none mt-3">
        No valid IP admission records were found for bulk printing.
    </div>
<?php } ?>
</div>
         


    <!--**********************************
        Scripts
    ***********************************-->
    <!--  vendors -->

</script>
</body>

</html>