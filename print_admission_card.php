<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");
include("model/reportClass.php");
$newReport= new report;
  $opno=$_REQUEST['id'];
$dept=$newReport->getIPDetailsForSlip($opno);
$res=mysqli_fetch_assoc($dept);
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

 <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
      <script>
      //window.onload = function () {
//     window.print();
//       window.addEventListener('afterprint', function () {
//         // Close the window after printing
//         window.close();
//       }, { once: true });

// } 
</script> 

    <style>
    @media print {
        @page {
    margin: 2mm 2mm 2mm 2mm;
}

.table th, .table td {
  
    border: 1px solid #000000 !important;
}
    }
    .table th,.table td {
        font-family: "Kanit", sans-serif;
        
    }
    
    .table td{
         font-size:16px;
         font-weight:300;
    }
        .table th{

            padding:5px;
            font-size:17px;
            font-weight:500;

        }
        p{
            font-size:15px;
        }
        body{
            /*margin-top:20px;*/
            background-color:white;
      font-family: "Kanit", sans-serif;
            font-size:22px;
            padding:10px;
        }
        
        ul {
  list-style-type: disc;
  padding-left: 20px;
}
li {
  list-style-type: disc;  /* bullet point */
  margin-bottom: 5px;     /* spacing between items */
}
        
        
    </style>
     

</head>

<body>


    <div class="container-fluid">
        <div class="row ">
            <div class="col-12 col-lg-12">
                <div class="row">
                        <div class="col-sm-1 mr-3">
                            <img src="assets/logo.png" class="" alt="" width=100>

                        </div>
                        <div class="col-sm-10  align-items-center">
                        <h2 class="text-center text-black"><?= $_SESSION['college_name']?></h2>
                        <p class="text-center text-black"><?= $_SESSION['college_location']?>, <?= $_SESSION['college_phone']?> <br>
                       
                         (ISO CERTIFIED 9001:2015; QACS-A-HC-08.16.022)<br>
 </p>

                        </div>
                    </div>
                </div>
                <!-- .row -->

               
                    
                   </div>

                    <!-- or use a table instead -->
                    
          <hr>
          


  <table class='table table-bordered'>
    <tr class="section-title"><th colspan="4">Registration Details</th></tr>
    <tr>
      <td>Admission No: <?= $res['ipd_no']?></td>
      <td>Admit Date: <?= date("d.m.Y",strtotime($res['admission_date']))?></td>
      <td>Admit Time: <?= date("h:i:s A",strtotime($res['admission_date']))?></td>
      <td>UHD No: <?= $res['uhid']?></td>
    </tr>

    <tr class="section-title"><th colspan="4">Patient Details</th></tr>
    <tr>
      <td colspan="2">Patient Name: <strong><span style='font-size:22px'><?= $res['name']?></span></strong></td>
      <td>Gender: <?= $res['gender']?></td>
      <td>Age: <?= $res['age']?></td>
    </tr>
    <tr>
      <td colspan="2">Address: <?= $res['address']?>L</td>
      <td>Religion:<?= $res['religion']?></td>
      <td>Marital Status:</td>
    </tr>
    <tr>
      <td colspan="2">Phone No: <?= $res['mobile']?></td>
      <td colspan="2"></td>
    </tr>

    <tr class="section-title"><th colspan="4">Admission Details</th></tr>
    <tr>
      <td>Billing Category: </td>
      <td>Bed No: <?= $res['bed_number']?></td>
      <td>Ward Name: <?= $res['ward']?></td>
      <td>Admission Type: </td>
    </tr>

    <tr class="section-title"><th colspan="4">Doctor Details</th></tr>
    <tr>
      <td colspan="2">Doctor's Name: <?= $res['doctor_name']?></td>
      <td>Specialization: <?= $res['sepeciality']?></td>
      <td>Phone No: <?= $res['doctor_mobile']?></td>
    </tr>
    <tr>
      <td colspan="4">Referral Doctor: SELF</td>
    </tr>

    <tr class="section-title"><th colspan="4">Payment Details</th></tr>
    <tr>
      <td colspan="2">Payment Mode:</td>
      <td colspan="2">Payer Name:</td>
    </tr>

    <tr class="section-title"><th colspan="4">Diagnosis & Procedure</th></tr>
    <tr>
      <td colspan="2">Provisional Diagnosis:</td>
      <td colspan="2">Final Diagnosis:</td>
    </tr>
    <tr>
      <th colspan="4">Procedure:</th>
    </tr>

    <tr class="section-title"><td colspan="4"><b>Condition on Discharge </b><br>
        <input type="checkbox" name='improved'> Improved &nbsp;&nbsp;
        <input type="checkbox" name='changed'> Unchanged &nbsp;&nbsp;
        <input type="checkbox" name='dama'> DAMA &nbsp;&nbsp;
        <input type="checkbox" name='lama'> LAMA &nbsp;&nbsp;
        <input type="checkbox" name='transfer'> Transfer &nbsp;&nbsp;
        <input type="checkbox" name='other'> Other &nbsp;&nbsp;
      </td>
    </tr>

    <tr>
      <th>Discharge Date:</th>
      <th>Discharge Time:</th>
      <th>Sign of SR:</th>
      <th>Sign of Consultant <br> Name:</th>
    </tr>

    <tr class="section-title"><th colspan="2" width='50%'>Hospital Rules & Regulations</th> 
    <th colspan="2">I/We have been briefed on my/our Rights</th> 
    </tr>
  <tr>
      <td colspan="2">
        <ul>
          <li>Allotment of beds will be based on availability of beds.</li>
          <li>Purchase all your medicines from SGN Pharmacy located in the premises & outside medicines are not permissible for safety reason.</li>
          <li>If the patient has opted for general ward at the time of admission and later requested to higher category ward then the charges will be as per higher category charges.</li>
          <li>Patients who are shifted to ICU or OT relatives are required to vacate the room to facilitate the room for other patients’ accommodation.</li>
          <li>Payment through NEFT, RTGS is not accepted.</li>
          <li>No smoking/Alcohol/Chewing tobacco in the hospital premises.</li>
          <li>Management will not be responsible for your personal belongings.</li>
        </ul>
      </td>
   
      <td colspan="2">
        <ul>
          <li>I will be treated with respect & non-discrimination.</li>
          <li>I can avail information related to my/my relative’s treatment.</li>
          <li>I will be involved in decision making related to treatment.</li>
          <li>My privacy and confidentiality will be considered at all levels.</li>
          <li>I will be educated on the medications, nutritional requirements, pain management techniques etc.</li>
          <li>My safety will be taken care of.</li>
          <li>My social needs will be fulfilled to the extent possible.</li>
          <li>I can voice my complaints & concerns freely to any of the staff members.</li>
        </ul>
      </td>
    </tr>

    <tr>
      <td colspan="2">
        <input type="checkbox" checked /> I/We have been explained about proposed care, expected results along with the possible complications.<br>
       
      </td>
      <th>Declaration</th>
      <td> <input type="checkbox" checked /> I/We have been explained about the expected costs.</td>
    </tr>

  
    <tr>
      <td colspan="4">
        I hereby knowingly express my consent for admission of <strong><span style='font-size:22px'><?= $res['name']?></span></strong> to SGN Hospital and agree to allow doctors nurses and other staff employed by the hospital or consultants to attend to complete diagnosis & therapeutic procedure as deemed necessary according to the health condition of the patient.<br><br>
        I agree to pay fully for all expenses incurred on behalf of the above named person for services rendered by SGN Hospital and the consultant doctors, nurses and other staff caring for me/patient during my/his/her treatment. I understand that I am financially responsible for all balances that are not covered by my/his/her health insurance plan, for example, the payment of Non-covered service deductible and co-payments are considered to be a patient’s responsibility, which I agree to pay on behalf of patient/by myself.
      </td>
    </tr>
    
     <tr>
      <th>Signature of the Patient </th>
      <th>Signature of the Patient Realtive</th>
      <th colspan='2'>Staff Signature <br>
       Date: </th>
    
    </tr>
  </table>
                    <hr />
<div>
    <p>Created by : <?= $res['created_by']?></p>
</div>
                    <div>
                       
                        <!--<a href="#" onclick="window.print();return false;"    data-toggle="tooltip" title="Print " class=" d-print-none btn btn-danger btn-bold  float-right mt-3 mt-lg-0"><i class="icofont-print"></i> Print</a>-->
                    </div>
                </div>
        

            



</body>

</html>