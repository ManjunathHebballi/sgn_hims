<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/reportClass.php');
$newReport = new report;
$ip=$newReport->getIPDetailsReport($_REQUEST['id']);
$res=mysqli_fetch_assoc($ip);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Discharge Summary</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" ></script>


    <link href="../../assets/plugins/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
    <!-- DataTables -->
    <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/datatables.net-select-bs4/css//select.bootstrap4.min.css" rel="stylesheet" type="text/css" />

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">


    <style>
    table tbody td{
        font-weight:200px!important;


        
    }
    @media print {
  .step {top:0;margin:0;page-break-before: auto;}
}

</style>
<!-- onload="window.print() -->

</head>

<body style=" font-family: 'Poppins', sans-serif; background-color:#fff" onload="window.print()" >

    <!--*******************
        Preloader start
    ********************-->
   
    <!--*******************
        Preloader end
    ********************-->

    <!--**********************************
        Main wrapper start
  
           row -->




<div class="container-fluid p-5">

    
   
   
    <div class="row">
        <div class="col-md-12">
            
                   
                <div class="row">
                        <div class="col-md-1 mr-3">
                            <img src="assets/logo.png" class="" alt="">

                        </div>
                        <div class="col-md-10  align-items-center">
                        <h5 class="text-center text-black">College Name</h5>
                        <h2 class="text-center text-black">Ayurvedic Medical College Hospital</h2>
                        <h5 class="text-center text-black">College Place</h5>
                        <h5 class="text-center text-black">Phone Number</h5>

                        </div>
                    </div>
                     <br><br>
                    <h4 class="text-center text-black">Discharge Summary of <span class="text-danger"><?= $res['name']?> (<?= $res['ipd_no']?>)</span>
                       
                    </h4>

                    
                

            
        </div>






             <div class="container">
               <div class="row mx-auto">
                 <div class="col-md-12  ">
                 <br>
                 <table class="table ">    
    <tbody><tr>
        <th class="border0">	Central OPD</th>
        <td class="border0"><?= $res['opd_no']?></td>
     
        <th>Central IP</th>
        <td><?= $res['ipd_no']?></td>
        <th>Department</th>
        <td><?= $res['dept_name']?></td>
     
    </tr>
    <tr>
    <th class="border0">Patient Name:</th>
        <td class="border0"><?= $res['name']?></td>
        
       
        <th>Age:   </th>
        <td><?= $res['age']?></td>
        <th>Sex:</th>
        <td><?= $res['gender']?></td>
    </tr>
    
    <tr>
    <th>Caste</th>
       <td><?= $res['caste']?></td>
    <th>Address</th>
        <td><?= $res['address']?></td>
        <th>Date Of Admission:</th>
        <td><?= date("d.m.Y",strtotime($res['admission_date']))?></td>
      
    </tr>
    <tr>
        <th>Time Of Admission:</th>
        <td><?= date("h:i:s a",strtotime($res['admission_date']))?></td>
        <th>Ward:</th>
        <td><?= $res['ward']?></td>
        <th>Bed No:</th>
        <td><?= $res['bed_number']?></td>
     
    </tr>

    <tr>
       
    </tr>
    <tr>
    <th>Date of Discharge:</th>
        <td><?= date("d.m.y",strtotime($res['discharge_date']))?></td>
        <th>Time of Discharge:</th>
        <td><?= date("h:i:s a",strtotime($res['discharge_date']))?></td>
        <th>Type of Discharge</th>
        <th></th>
    </tr>
</tbody></table>
                 </div>
               </div>
             </div><hr>

 
             <div class="container">
               <div class="row mt-2">
                 <div class="col-md-12 " >
                 <h4 class="text-uppercase text-danger mb-3">Step 1: Previous Medical History Details</h4>
                 <h6>Vedana Samuchaya (With duration Chronological order)</h6>
                 <?php $data=$newReport->getIPHistoryReport($_REQUEST['id']);
                 $row=mysqli_fetch_assoc($data);
                 ?>
                 <p>1. <?= $row['sign_symptoms1']?></p>
</p>
                 <p>2. <?= $row['sign_symptoms2']?></p>
                 <p>3. <?= $row['sign_symptoms3']?></p>

                 <p>4. <?= $row['sign_symptoms4']?></p>


                 </div>
               </div>
             </div>

             <div class="container">
               <div class="row">
                 <div class="col-md-12 " style="height:100px">
                 <h6>Atanka Samutpattikrama (History of Present Illness) :</h6>
                 <?= $row['present_illness']?>
                 </div>
               </div>
             </div>

             <div class="container">
               <div class="row">
                 <div class="col-md-12 " style="height:100px">
                 <h6>Poorva Vyadhi Vrittanta :</h6>
                 <?= $row['past_illness']?>
                 </div>
               </div>
             
            
             <!--<div class="row ">-->
             <!--    <div class="col-md-12">-->
             <!--        <table class="table table-borderless mb-3">-->
             <!--        <tbody><tr>-->
             <!--         <td height="30" valign="topm" colspan="5" ><h6>Past Medical/Surgical History :</h6></td>-->
             <!--       </tr>-->
             <!--       <tr>-->
             <!--         <td><b>Childhood:</b><br>-->
             <!--           <br>-->
                      
             <!--         </td>-->
             <!--         <td></td>-->
             <!--         <td><b>Adulthood:</b><br>-->
             <!--           <br>-->
                        
             <!--         </td>-->
             <!--         <td></td>-->
             <!--         <td><b>Surgery:</b><br>-->
             <!--           <br>-->

             <!--         </td>-->
             <!--         <td></td>-->
             <!--         </tr>-->
             <!--         <tr>-->
             <!--         <td><b>Medication:</b><br>-->
             <!--           <br>-->

             <!--         </td>-->
             <!--         <td></td>-->
             <!--         <td><b>Drug Allergies:</b><br>-->
             <!--           <br>-->

             <!--         </td>-->
             <!--         <td></td>-->
             <!--         <td><b>Family History:</b><br>-->
             <!--           <br>-->

             <!--         </td>-->
             <!--         <td></td>-->
             <!--      	</tr>-->
             <!--     </tbody>-->
             <!--        </table>-->
             <!--    </div>-->
             <!--</div>-->

          


               <div class="row ">
                 <div class="col-md-12">
                     <h6>Personal History</h6>
                     <table class="table table-bordered mt-3">
                     <tbody>
                    <tr>
                      <td><h6>Marital Status:</h6></td>
                      <td><?= $row['marital']?></td>

                      <td><h6>Vayaktika Vrittana</h6></td>
                      <td><?= $row['food']?><Mixed/td>

                      <td><h6> Vyasana</h6> </td>
                      <td><?php 
                      if($row['tobacco_chewing']==1)
                      {
                          echo "Tobacco Chewing, ";
                      }
                      if($row['smoking']==1)
                      {
                          echo "Smoking, ";
                      }
                      if($row['alcohol']==1)
                      {
                          echo "Alcohol, ";
                      }if($row['krodha']==1)
                      {
                          echo "Krodha,";
                      }if($row['shoka']==1)
                      {
                          echo "Shoka, ";
                      }
                      if($row['chittogvega']==1)
                      {
                          echo "Chittogvega, ";
                      }
                      if($row['atichintana']==1)
                      {
                          echo "Atichintana, ";
                      }
                      if($row['vegadharana']==1)
                      {
                          echo "Vegadharana, ";
                      }
                      
                      if($row['shift_duties']==1)
                      {
                          echo "Shift Duties, ";
                      }
                      if($row['frequent_journey']==1)
                      {
                          echo "Frequent Journey, ";
                      }
                      if($row['udheerna']==1)
                      {
                          echo "Udheerna";
                      }
                      ?></td>
                      
                      </tr>

                      <tr>
                      <td><h6>Nidhra:</h6> </td>
                      <td><?= $row['nidra']?></td>

                      <td><h6>Agni:</h6></td>
                      <td><?= $row['agni']?></td>

                      <td><h6>Kostash:</h6></td>
                      <td><?= $row['kostash']?></td>

                   	</tr>
                  </tbody>
                     </table>
                 </div>
             </div>


             <div class="row mt-5 mb-4">
                 <div class="col-md-12 ">
                 <h6>Occupational History:
</h6>
<table class="table table-borderless mt-3">
    <tbody>
        <tr>
            <th width="150px"><h6>Nature of Work :</h6></th>
            <td >
                <?php
                if($row['labour']==1)
                  {
                      echo "Labour, ";
                  }
                  if($row['field_work']==1)
                  {
                      echo "Field Work, ";
                  }
                  if($row['sedantary']==1)
                  {
                      echo "Sedentary, ";
                  }
                  if($row['intellectual']==1)
                  {
                      echo "Intellectual, ";
                  }
                  if($row['job_stress']==1)
                  {
                      echo "Job Stress ";
                  }
                ?>
                
            </td>
        </tr>
    </tbody>
</table>

                 </div>
               </div>

               
               <div class="row  ">
                 <div class="col-md-12">
                     <h6>OBG History</h6>
                     <table class="table table-bordered mt-3">
                     <tbody>
                    <tr>
                      <td><h6>Menarche at:</h6></td>
                      <td><?= $row['menarche']?></td>

                      <td><h6>Day Cycle:</h6></td>
                      <td><?= $row['day_cycle']?></td>

                      <td><h6> Live:</h6> </td>
                      <td>
                          <?php
                if($row['ftnd']==1)
                  {
                      echo "FTND, ";
                  }
                  if($row['lscs']==1)
                  {
                      echo "LSCS, ";
                  }
                  if($row['fthd']==1)
                  {
                      echo "FTHD ";
                  }
                  
                ?></td>
                      
                      </tr>

                      <tr>
                      <td><h6>Period:</h6> </td>
                      <td><?php
                      if($row['period_regular']==1)
                      {
                          echo "Regular, ";
                      }
                      if($row['period_painful']==1)
                      {
                          echo "Painful, ";
                      }
                      if($row['period_irregular']==1)
                      {
                          echo "Irregular, ";
                      }
                      if($row['period_scanty']==1)
                      {
                          echo "Scanty,  ";
                      }
                      if($row['period_withclot']==1)
                      {
                          echo "with Clots  ";
                      }
                      ?></td>

                    

                   	</tr>
                  </tbody>
                     </table>
                 </div>
             </div>
<br><br><br><br>
<br><br>
<br><br><br><br><br><br>
<br><br>
<br> <br><br><br>         <h4 class="mt-5 text-danger step">STEP 2 : INVESTIGATIONS</h4>
<?php $data=$newReport->getIPInvestigationReport($_REQUEST['id']);
                 $row=mysqli_fetch_assoc($data);
                 ?>

             <div class="row mt-5 mb-4">
                 <div class="col-md-12 ">
                 <h6>Occupational History:
</h6>
<table class="table table-borderless mt-3">
    <tbody>
        <tr>
            <th width="250px"><h6>General Examination :</h6></th>
            <td ><?= $row['general_exam']?></td>
        </tr>
    </tbody>
</table>

                 </div>
               </div>





               <div class="row mb-4">
                 <div class="col-md-12 ">
                 <h6>Vittal Data
</h6>
<table class="table table-bordered mt-3">
    <tbody>
        <tr>
            <th width="100px" ><h6>Pulse Rate:</h6></th>
            <td width="100px" ><?= $row['pulse_rate']?></td>
            <th width="100px" ><h6>Heart Rate:</h6></th>
            <td width="100px" ><?= $row['heart_rate']?></td>
            <th width="150px" ><h6>Respiratory  Rate:</h6></th>
            <td width="100px"><?= $row['respiration_rate']?></td>
            <th width="100px" ><h6>Temprature:</h6></th>
            <td width="100px" ><?= $row['temp']?></td>
            <th width="150px" ><h6>Blood Pressure:</h6></th>
            <td ><?= $row['blood_pressure']?></td>
        </tr>
    </tbody>
</table>

                 </div>
               </div>


               <div class="row mb-4">
                 <div class="col-md-12 ">
                 <h6>Astasthana Pareeksha 
</h6>
<table class="table table-bordered mt-3">
    <tbody>
        <tr>
            <th width="100px" ><h6>Nadi:</h6></th>
            <td width="100px" ><?= $row['nadi']?></td>
            <th width="100px" ><h6>Mutra:</h6></th>
            <td width="100px" ><?= $row['mutra']?></td>
            <th width="150px" ><h6>Mala:</h6></th>
            <td width="100px"><?= $row['mala']?></td>
           
        </tr>
    </tbody>
</table>

                 </div>
               </div>

               <div class="row mb-4">
                 <div class="col-md-4 ">
                 <h6>Jivha
</h6>
<p><?php
                      if($row['jivha_upalepita']==1)
                      {
                          echo "Upalepita, ";
                      }
                      if($row['jivha_ragayukta']==1)
                      {
                          echo "Ragayukta, ";
                      }
                      if($row['jivha_others']==1)
                      {
                          echo "Others, ";
                      }
                      if($row['jivha_paka']==1)
                      {
                          echo "Paka,  ";
                      }
                      if($row['jivha_centrally_coated']==1)
                      {
                          echo "Centrally Coated  ";
                      }
                      ?></p>

                 </div>

                 <div class="col-md-4 ">
                 <h6>Shabda
</h6>
<p><?= $row['shabda']?></p>

                 </div>

                 <div class="col-md-4 ">
                 <h6>Sparsha
</h6>
<p><?= $row['sparsha']?></p>

                 </div>
                 



               </div>


               <div class="row mb-4">
                 <div class="col-md-4 ">
                 <h6>Varna
</h6>
<p><?php
                      if($row['varna_ruksha']==1)
                      {
                          echo "Ruksha, ";
                      }
                      if($row['varna_snigdha']==1)
                      {
                          echo "Snigdha, ";
                      }
                      if($row['varna_tanu']==1)
                      {
                          echo "Tanu, ";
                      }
                      if($row['varna_sthula']==1)
                      {
                          echo "Sthula,  ";
                      }
                      if($row['varna_ushna']==1)
                      {
                          echo "Ushna  ";
                      }
                      ?></p>

                 </div>

                 <div class="col-md-4 ">
                 <h6>Drik
</h6>
<p><?php
                      if($row['drik_pale']==1)
                      {
                          echo "Pale, ";
                      }
                      if($row['drik_conjested']==1)
                      {
                          echo "Conjested, ";
                      }
                      if($row['drik_kalushita']==1)
                      {
                          echo "Kalushita, ";
                      }
                      if($row['drik_akshimalasrava']==1)
                      {
                          echo "Akshimalasrava,  ";
                      }
                      if($row['drik_jalasrava']==1)
                      {
                          echo "Jalasrava,  ";
                      }
                      if($row['drik_raga']==1)
                      {
                          echo "Raga  ";
                      }
                      ?></p>

                 </div>

                 <div class="col-md-4 ">
                 <h6>Akriti
</h6>
<p><?= $row['akriti']?></p>

                 </div>
                </div>


                <div class="row mb-4">
                 <div class="col-md-6 ">
                 <h6>Vittal Data
</h6>
<table class="table table-bordered mt-3">
    <thead class="thead-dark">
    <tr>
        <th>
        BHAVA
        </th>
        <th>TYPE</th>
        <th>ASSEMENT(PRAVAR/MADYA/AVAR)</th>
    </tr>
    
    <tbody>
        <tr>
            <th><h6>Prakruti</h6></th>
            <td width="150"><?= $row['prakruti_type']?></td>

            <td><?= $row['prakruti_assess']?></td>
           

        </tr>
        <tr>
        <th><h6>Saara</h6></th>
        <td width="150"><?= $row['saara_type']?></td>

            <td><?= $row['saara_assess']?></td>

            
        </tr>

        <tr>
        <th><h6>Smhanana</h6></th>
        <td width="150"><?= $row['smhanana_type']?></td>

            <td><?= $row['smhanana_assess']?></td>

           
        </tr>

        <tr>
        <th><h6>Pramana</h6></th>
        <td width="150"><?= $row['pramana_type']?></td>

            <td><?= $row['pramana_assess']?></td>

          
        </tr>

        <tr>
        <th><h6>Vayah</h6></th>
        <td width="150"><?= $row['vayah_type']?></td>

            <td><?= $row['vayah_assess']?></td>
        </tr>
    </tbody>
</table>

                 </div>

                 <div class="col-md-6">
                 <h6 class="text-white">.
</h6>
                 <table class="table table-bordered mt-3">
                 <thead class="thead-dark">
    <tr>
        <th>
        BHAVA
        </th>
        <th>TYPE</th>
        <th width="500px">ASSEMENT(PRAVAR/MADYA/AVAR)</th>
    </tr>
    </thead>
        
        <tr>
        <th><h6>Satmya</h6></th>
        <td width="150"><?= $row['satmya_type']?></td>

            <td><?= $row['satmya_assess']?></td>

            
        </tr>

        <tr>
        <th><h6>Satwa</h6></th>
        <td width="150"><?= $row['satwa_type']?></td>

            <td><?= $row['satwa_assess']?></td>

           
        </tr>

        <tr>
        <th><h6>Ahara Shakti	</h6></th>
        <td width="150"><?= $row['ahara_shakti_type']?></td>

            <td><?= $row['ahara_shakti_assess']?></td>

          
        </tr>

        <tr>
        <th width="250"><h6>Vyayama Shakti</h6></th>
        <td width="150"><?= $row['vyayama_shakti_type']?></td>

            <td><?= $row['vyayama_shakti_assess']?></td>
        </tr>
    </tbody>
</table>

                 </div>
                 </div>


                 <div class="row">
                 <div class="col-md-12">
                     <table class="table table-bordered"> 
                         <tr>
                            <td><h6>Manah</h6></td>
                             <td width="250px"><?= $row['manah']?></td>
                             <td><h6>Buddhi</h6></td>

                             <td width="250px"><?= $row['buddhi']?></td>
                             <td><h6>Sanjna</h6></td>

                             <td width="250px"><?= $row['sanjna']?></td>
                                                 

                         </tr>
                         <tr>
                         <td><h6>Jnana</h6></td>
                             <td width="250px"><?= $row['jnana']?></td>
                             <td><h6>Bhakti</h6></td>
                             <td width="250px"><?= $row['bhakti']?></td>
                             <td><h6>Sheela</h6></td>
                             <td width="250px"><?= $row['sheela']?></td>
                         </tr>
                         <tr>
                         <td><h6>Chesta</h6></td>
                             <td width="250px"><?= $row['chesta']?></td>
                             <td><h6>Achara</h6></td>
                             <td width="250px"><?= $row['achara']?></td>
                             <td><h6>Smriti</h6></td>
                             <td width="250px"><?= $row['smriti']?></td>
                         </tr>
                     </table>
                
                 </div>
                 </div>



                 <div class="row mt-3">
                     <div class="col-md-12">
                         <table class="table table-bordered text-center">
                             <thead class="thead-dark">
                                 <tr class="">
                                     <th width="300px">Storus</th>
                                     <th width="300px"> Type of Dusti Dustilakshna</th>
                                 </tr>
                             </thead>
                             <tbody>
                                 <tr height="30px">
                                     <td><?= $row['storus1']?></td>
                                     <td><?= $row['dustilakshna1']?></td>
                                 </tr>
                                 <tr height="30px">
                                     <td><?= $row['storus2']?></td>
                                     <td><?= $row['dustilakshna2']?></td>
                                 </tr>
                                 <tr height="30px">
                                     <td><?= $row['storus3']?></td>
                                     <td><?= $row['dustilakshna3']?></td>
                                 </tr>
                             </tbody>
                         </table>
                     </div>
                 </div>
                 <div class="row mt-4">
                     <div class="col-md-12">
                         <table class="table table-bordered">
                             <tbody>
                                 <tr>
                                     <td width="200px"><h6>Local Examination</h6></td>
                                     <td width="500px"><?= $row['local_exam']?></td>
                                     <td><h6>Roga Type</h6></td>
                                     <td><?= $row['roga_name']?></td>
                                 </tr>
                             </tbody>
                         </table>
                     </div>
                 </div>

                 <div class="row mt-4 ">
                 <div class="col-md-12">
                     <h5>Systematic Examination</h5>
                     <table class="table table-bordered mt-3"> 
                         <tr>
                            <td><h6>CVS:</h6></td>
                             <td width="250px"><?= $row['cvs']?></td>
                             <td><h6>RS:</h6></td>

                             <td width="250px"><?= $row['rs']?></td>
                             <td><h6> 	P/A:</h6></td>

                             <td width="250px"><?= $row['pa']?></td>
                             <td><h6>CNS:</h6></td>
                             <td width="250px"><?= $row['cns']?></td>
                                                 

                         </tr>
                         <tr>
                      
                             <td><h6>Power:</h6></td>
                             <td width="250px"><?= $row['power']?></td>
                             <td><h6>Reflexes:</h6></td>
                             <td width="250px"><?= $row['reflexes']?></td>
                             <td><h6>Tone:  </h6></td>
                             <td width="250px"><?= $row['tone']?></td>
                             <td><h6>others:</h6></td>
                             <td width="250px"><?= $row['others']?></td>
                         </tr>
                         <tr>
                        
                             <td><h6>GUS</h6></td>
                             <td width="250px"><?= $row['gus']?></td>
                             <td><h6>MSS</h6></td>
                             <td width="250px"><?= $row['mss']?></td>
                             <td><h6>Skin</h6></td>
                             <td colspan="3"><?= $row['skin']?></td>
                         </tr>
                     </table>
                
                 </div>
                 </div>

<br><br><br><br>
<br><br><br>
<br><br><br><br><br><br>
<br><br><br>
<br><br><br><br>                
       <div class="row mt-5 step">
           <h4 class="text-uppercase text-danger">Step 3 : Treatment</h4 class="text-uppercase">
           
           <div class="col-md-12 mt-3">
               <h5 class="text-uppercase">Lab Tests</h5>
               <table class="table table-bordered text-center">
                   <thead class="thead-dark">
                       <tr>
                           <th width="150px">Sl No.</th>
                           <th>Test Name</th>
                           <th>Test Values</th>
                       </tr>
                   </thead>
                   <tbody>
                       <?php $data=$newReport->getIPlabReport($_REQUEST['id']);
                       $sn=1;
                 while($row=mysqli_fetch_assoc($data)){
                 ?>
                       <tr height="25px">
                           <td><?= $sn?></td>
                           <td><?= $row['test_name']?></td>
                           <td><?= $row['test_result']?></td>
                       </tr>
                       <?php $sn++; } ?>
                   </tbody>
               </table>
           </div>
       </div>

       <div class="row ">
           <?php 
                                            $data=$newReport->getIPTreatReport($_REQUEST['id']);
                                            $row=mysqli_fetch_assoc($data);
                                            ?>
           <div class="col-md-12 mt-3">
               <h5>ROGA PAREEKSHA</h5>
               <table class="table table-bordered">
                   
                   <tbody>
                       <tr >
                           <td>Nindana:</td>
                           <td width="150px"><?= $row['nindana']?></td>
                           <td>Poorvapoorva:</td>
                            <td width="150px"><?= $row['poorvapoorva']?></td>
                           <td>Roopa:</td>
                            <td width="150px"><?= $row['roopa']?></td>
                           <td>
Upashaya/Anupashaya:</td>
                           <td width="150px"><?= $row['anupashaya']?></td>
                       </tr>
                   </tbody>
               </table>
           </div>
       </div>

       <div class="row mt-3 ">

           <div class="col-md-12 ">
               <h5 class="text-uppercase">Sampraptighatakas</h5>
               <table class="table table-bordered text-wrap mb-5">
                   
                   <tbody>
                       <tr >
                           <td>Dosha:</td>
                           <td width="150px"><?= $row['dosha']?></td>
                           <td>Dushy:</td>
                            <td width="150px"><?= $row['dushy']?></td>
                           <td>Agni:</td>
                            <td width="150px"><?= $row['agni']?></td>
                           <td>
                           Ama/Nirama:</td>
                           <td width="150px"><?= $row['ama']?></td>
                           <td>
                           Srotas:</td>
                           <td width="150px"><?= $row['srotas']?></td>
                       </tr>
                       </tr>
                       <tr >
                           <td>Srotodushtiprakarana:</td>
                           <td width="150px"><?= $row['srotodushtiprakarana']?></td>
                           <td>Srotodushtiprakarana:</td>
                            <td width="150px"><?= $row['srotodushtiprakarana']?></td>
                           <td>VyadhiUdbhavasthana:</td>
                            <td width="150px"><?= $row['vyadhiudbhavasthana']?></td>
                           <td>
                           Sancharasthana:</td>
                           <td width="150px"><?= $row['sancharasthana']?></td>
                           <td>
                           Vyaktasthana:</td>
                           <td width="150px"><?= $row['vyaktasthana']?></td>
                       </tr>
                       <tr >
                           <td>Vyadhiadhisthana:</td>
                           <td width="150px"> <?= $row['vyadhiadhisthana']?></td>
                           <td>Rogamarga:</td>
                            <td width="150px"><?= $row['rogamarga']?></td>
                           <td>Rogabala:</td>
                            <td width="150px"><?= $row['rogabala']?></td>
                           <td>
                           Rogibala:</td>
                           <td width="150px"><?= $row['rogibala']?></td>
                           <td>
                           Rogaswabhava:</td>
                           <td width="150px"><?= $row['rogaswabhava']?></td>
                       </tr>
                       <tr >
                           <td>Upadrava:</td>
                           <td width="150px"><?= $row['upadrava']?></td>
                           <td>Arista lakshna:</td>
                            <td width="150px"><?= $row['arista_lakshna']?></td>
                           <td>Samprapti:</td>
                            <td width="150px"><?= $row['samprapti']?></td>
                           <td>
                           Sapeksha/Nidana:</td>
                           <td width="150px" ><?= $row['sapeksha']?></td>
                           <td>
                           Vyavachedaka/Nidana:</td>
                           <td width="150px" ><?= $row['nidana']?></td>
                           
                       
                          
                       </tr>
                       <tr>
                       <td>
                           Vyadhi / Vinishaya :</td>
                           <td width="150px" colspan="2" ><?= $row['vinischaya']?></td>
                       </tr>
                   </tbody>
               </table>
           </div>
       </div>


       <div class="row mt-5 ">
           <h4 class="text-uppercase text-danger">Step 4 : Medication </h4 class="text-uppercase">
           <div class="col-md-12 mt-3">
               <h5 class="text-uppercase">PROCEDURES/THERAPIES</h5>
               <table class="table table-bordered text-center">
               <thead class="thead-dark">
                                                                            <tr>
                                                                                <th class="text-center" width="50px">Sl.No.</th>
                                                                                <th>Date</th>
                                                                                <th>Procedure</th>
                                                                                <th>Sub Procedure</th>
                                                                                <th class="text-center">Procedure Medicine</th>
                                                                                <th>Dose (days/ml grms)</th>
                                                                                <th>Duration</th>
                                                                                <th>Observation</th>
                                                                              
                                                                                
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php 
                                                                            $sn=1;
                                                                            $data=$newReport->getIPTherapies($_REQUEST['id']);
                                                                            while($row=mysqli_fetch_assoc($data)){
                                                                            ?>
                                                                            <tr height="20px">
                                                                                <td><?= $sn?></td>
                                                                                 <td><?= date("d.m.Y",strtotime($row['date']))?></td>
                                                                           
                                                                               <td><?= $row['procedure_name']?></td>
                                                                               <td><?= $row['subprocedure_name']?></td>
                                                                               <td><?= $row['proce_medic']?></td>
                                                                               <td><?= $row['days'].'/'.$row['qty']?></td>
                                                                               <td><?php if($row['morning'] != 'none') echo "Morning:$row[morning]";
                                                                               if($row['afternoon'] != 'none') echo "<br>Afternoon:$row[afternoon]";
                                                                               if($row['evening'] != 'none') echo "<br>Evening:$row[evening]";
                                                                               if($row['night'] != 'none') echo "<br>Night:$row[night]";
                                                                               
                                                                               
                                                                               ?></td>
                                                                               <td><?= $row['anupana']?></td>
                                                                              
                                                                                
                                                                                
                                                                                
                                                                                
                                                                            </tr>
                                                                            <?php $sn++; } ?>
                                                                        </tbody>
               </table>
           </div>
       </div>


       <div class="row mt-3 ">
           
           <div class="col-md-12 ">
               <h5 class="text-uppercase">ORAL MEDICINES</h5>
               <table class="table table-bordered text-center">
               <thead class="thead-dark">
               <tr>
                                                                                <th class="text-center" width="50px">Sl.No.</th>
                                                                                <th>Date</th>
                                                                                <th>Medicine Name</th>
                                                                                
                                                                                <th>Dose (days/ml grms)</th>
                                                                                <th>Duration</th>
                                                                              
                                                                                <th>Anupana</th>
                                                                             
                                                                                
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                              <?php $md=$newReport->getPatienMedic($_REQUEST['id']);
                                                                            $sn=1;
                                                                            while($row=mysqli_fetch_assoc($md)) {?>
                                                                            <tr>
                                                                            <td><?= $sn?></td>
                                                                               <td><?= date("d.m.Y",strtotime($row['date']))?></td>
                                                                           
                                                                               <td><?= $row['name']?></td>
                                                                               

                                                                               <td><?= $row['days'].'/'.$row['qty']?></td>
                                                                               <td><?php if($row['morning'] != 'none') echo "Morning:$row[morning]";
                                                                               if($row['afternoon'] != 'none') echo "<br>Afternoon:$row[afternoon]";
                                                                               if($row['evening'] != 'none') echo "<br>Evening:$row[evening]";
                                                                               if($row['night'] != 'none') echo "<br>Night:$row[night]";
                                                                               
                                                                               
                                                                               ?></td>
                                                                               <td><?= $row['anupana']?></td>
                                                                             
                                                                            </tr>
                                                    
                                                                        <?php $sn++; } ?>
                                                                        </tbody>
               </table>
           </div>
       </div>


       <div class="row mt-3 ">
           
           <div class="col-md-12 ">
               <h5 class="text-uppercase">IP DAILY ENTRY</h5>
               <table class="table table-bordered text-center">
               <thead class="thead-dark">
               <tr>
                                                                                <th class="text-center" width="50px">Sl.No.</th>
                                                                                <th>Date</th>
                                                                                <th>Pulse</th>
                                                                                
                                                                                <th>BP</th>
                                                                                <th>Temp</th>
                                                                              
                                                                                <th>Nadi</th>
                                                                                <th>Diet</th>
                                                                                <th>Medicines</th>
                                                                                <th>Progress</th>
                                                                              
                                                                                
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                              <?php $daily=$newReport->getDaily($_REQUEST['id']);
                                                                            $sn=1;
                                                                            while($row=mysqli_fetch_assoc($daily)){?>
                                                                            <tr>
                                                                               <td><?= $sn?></td>
                                                                               <td><?= $row['date']?></td>

                                                                               <td><?= $row['pulse']?></td>
                                                                           
                                                                               <td><?= $row['temp']?></td>
                                                                               <td><?= $row['bp']?></td>
                                                                               <td><?= $row['nadi']?></td>
                                                                               <td><?= $row['diet']?></td>
                                                                               <td><?= $row['medicine']?></td>
                                                                               <td><?= $row['progress']?></td>
                                                                            </tr>
                                                                        <?php $sn++;} ?>
                                                                        </tbody>
               </table>
           </div>
       </div>

       
       <div class="row mt-3 ">
           
           <div class="col-md-12 ">
               <h5 class="text-uppercase">OPERATION THEATER</h5>
               <table class="table table-bordered text-center">
               <thead class="thead-dark">
               <tr>
                                                                                <th class="text-center" width="50px">Sl.No.</th>
                                                                                <th>Date</th>
                                                                                <th>Ot Type</th>
                                                                                <th>Procedure</th>
                                                                                <th>
                                                                                Comments
                                                                                </th>
                                                                                
                                                                              
                                                                             
                                                                                
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php $ot=$newReport->getOTReg($_REQUEST['id']);
                                                                            $sn=1;
                                                                            while($row=mysqli_fetch_assoc($ot)){?>
                                                                            <tr>
                                                                               <td><?= $sn?></td>
                                                                               <td><?= date("d.m.Y",strtotime($row['date']))?></td>

                                                                               <td><?php echo $row['name']." ".$row['last_name']; ?></td>
                                                                               <td><?= $row['ot_name']?></td>
                                                                               <td><?= $row['ot_procedure']?></td>
                                                                               <td><?= $row['comment']?></td>
                                                                            </tr>
                                                                        <?php $sn++;} ?>
                                                                        </tbody>
               </table>
           </div>
       </div>

                 
               </div>





             </div>

                
                
         
            
        



    </div>
    
   
</div>
        
            
      
       





   

    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="./vendor/global/global.min.js"></script>
    <script src="./js/quixnav-init.js"></script>
    <script src="./js/custom.min.js"></script>

   


    
   


  

</body>

</html>