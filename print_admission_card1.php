<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");
include("model/reportClass.php");
$newReport= new report;
  $opno=$_REQUEST['id'];
$dept=$newReport->getIPDetailsForSlip($opno);
$res=mysqli_fetch_assoc($dept);
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admission Report</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      /* background: #fff; */
      font-size: 13px;
      padding: 15px;
      text-transform : uppercase;
    }
    .a4-sheet {
      width: 210mm;
      min-height: 297mm;
      margin: auto;
      padding: 20mm;
     
    }
    .form-title {
      text-align: center;
      font-weight: bold;
      font-size: 16px;
      text-transform: uppercase;
    }
    .sub-title {
      text-align: center;
      font-size: 13px;
    }
    .section-title {
      font-weight: bold;
      margin-top: 15px;
      margin-bottom: 5px;
      text-transform: uppercase;
      font-size: 14px;
    }
    .line-input {
      border-bottom: 1px solid #000;
      display: inline-block;
      min-width: 100px;
      padding: 2px;
    }
    textarea {
      border: 1px solid #000 !important;
    }
    .result-box input {
      margin-right: 4px;
      margin-left: 10px;
    }
.table th, .table td {
  
    border: 1px solid #000000 !important;
}
    /* Print setup */
    @page {
      size: A4;
      margin: 5mm;
    }
    @media print {
      body {
        margin: 0;
        background: #fff;
      }
      .a4-sheet {
        border: 1px;
        margin: 0;
        padding: 0;
        width: auto;
        min-height: auto;
      }
    }
  </style>
</head>
<body onload="window.print()">

<div class="a4-sheet">
   
  
  <table class="table table-bordered">
    <tbody>
        <div class="row ">
            <div class="col-12 col-lg-12">
                <div class="row">
                        <div class="col-sm-1 mr-3">
                            <img src="assets/logo.png" class="" alt="" width="75px" height="75px">

                        </div>
                        <div class="col-sm-10  align-items-center">
                        <h4 class="text-center text-black"><?= $_SESSION['college_name']?></h4>
                        <p class="text-center text-black"><?= $_SESSION['college_location']?>,<br> <?= $_SESSION['college_phone']?> <br>
                       
                         (ISO CERTIFIED 9001:2015; QACS-A-HC-08.16.022)
 </p>

                        </div>
                    </div>
                </div>
                <!-- .row -->

               
                    
                   </div>
        <tr>
           
            <tr class="text-center"><th colspan="12">ADMISSION REPORT</th></tr>
            
            
            <td colspan="3" width="100%">
                <!-- Patient Info -->
  <div class="row mb-3">
    <div class="col-6">Patient’s Name: <span class="line-input"><strong><?= ucwords(strtolower($res['name'])) ?></strong></span></div>
    <div class="col-3">Age: <span class="line-input"><strong><?= $res['age']?></strong></span></div>
    <div class="col-3">Sex: <span class="line-input"><strong><?= $res['gender']?></strong></span></div>
  </div>

  

  <div class="mb-3">
  Marital Status: 
  <span class="line-input"><strong><?= $res['marital_status']?></strong>
  </div>
 

  <div class="row mb-3">
    <div class="col-4">Department: <span class="line-input"><strong><?= $res['dept_name']?></strong></span></div>
    <div class="col-4">UHID No: <span class="line-input"><strong><?= $res['uhid']?></strong></span></div>
    <div class="col-4">IP No: <span class="line-input"><strong><?= $res['ipd_no']?></strong></span></div>
  </div>

  <div class="row mb-3 mt-2">
    <div class="col-6">Ward: <span class="line-input"><strong><?= $res['ward']?></strong></span></div>
    <div class="col-6">Bed No: <span class="line-input"><strong><?= $res['bed_number']?></strong></span></div>
  </div>

  <div class="mb-3">Father’s/Husband’s Name: <span class="line-input"><strong><?= $res['parent_name']?></strong></span></div>

  <div class="mb-3">Address: <span class="line-input"><strong><?= $res['address']?></strong></span></div>
<div class="d-flex  flex-row  mb-3">
    <!--<div class="col-3">Tel.No. <span class="line-input"></span></div>-->
    <!--<div class="col-3">Off : <span class="line-input"></span></div>-->
    <div class="col-3">Mobile: <span class="line-input"><strong><?= $res['mobile']?></strong></span></div>
    <!--<div class="col-3">Pin Code: <span class="line-input"></span></div>-->

  </div>
  <div class="row mb-3">
    <div class="col-12">Occupation: <span class="line-input"><strong><?= $res['occupation']?></strong></span></div>
    <div class="mb-3 mt-3">
 
   <div class="col-3">Religion: <span class="line-input"><strong><?= $res['religion']?></strong></span></div>
  </div>
   
  </div>

  <div class="mb-3">Admitting Doctor: <span class="line-input"><strong><?= $res['doctor_name']?></strong></span></div>

  <div class="row mb-3">
    <div class="col-6">Date  of Admission: <span class="line-input"><strong><?= date("d.m.Y",strtotime($res['admission_date']))?> <?php if($res['is_generated']==0) { echo date("h:i:s A",strtotime($res['admission_date'])); } ?> </strong></span></div>
    <div class="col-6">No of Hosp Days: <span class="line-input"></span></div>
  </div>

  <div class="row mb-3">
    <div class="col-6">Date of Discharge: <span class="line-input"></span></div>
    <div class="col-6">Signature of Patient/Relative: <span class="line-input"></span></div>
  </div>

  
  
</td>
        </tr>
        <tr>
            <th colspan="2" width="30%">
                Exclusive for Doctors Use Only
            </th>
        <th>
               Internation Code No (ICD)
            </th>
        </tr>
        <tr>
            <td height="40px">Provisional Diagnosis</td>
            <td width="35%"></td>
            <td></td>
        </tr>
        <tr>
            <td height="80px">Final Diagnosis</td>
            <td width="35%"></td>
            <td></td>
        </tr>
        <tr>
            <td height="80px">Operation / Procedures with dates</td>
            <td width="35%"></td>
            <td></td>
        </tr>

        <tr>
            <td colspan="3">
                  <div class="mb-3">
<strong>
    Result :
</strong>
    <span class="result-box">
      <input type="checkbox"> Recovered/ Cured
          <input type="checkbox"> Improved
      <input type="checkbox"> Referred
      <input type="checkbox"> Expired
      <input type="checkbox"> Absconded
      <input type="checkbox"> DAMA


     
    </span>
  </div>
            </td>
        </tr>
 
            
    </tbody>
  </table>
   <div class="row mt-5">
    <!--<div class="col-6"><strong>Operator:</strong> <span class="line-input">tiki</span></div>-->
    <div class="col-12 text-end"><strong>Signature of Unit Chief:</strong> </div>
  </div>
  <div class="row">
                                                <div class="col-md-12">
                                                    <button onclick="window.print()"class="btn btn-danger align-right d-print-none"> <i class="ri-printer-line"></i> Print </button>
                                                </div>
                                            </div>
</div>
<script>
    window.addEventListener('afterprint', function () {
        // Close the window after printing
        window.close();
      }, { once: true });
</script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>
</body>
</html>
