<?php 
include('model/treatmentClass.php');

$newTreat=new addTreat;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>OP Treatment</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
   
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">

    <link href="../../assets/plugins/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
    <!-- DataTables -->
    <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/datatables.net-select-bs4/css//select.bootstrap4.min.css" rel="stylesheet" type="text/css" />

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">


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

  <?php include ('leftbar.php')?>

        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <!-- row -->




<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <h4 class="text-uppercase">DEPARTMENT OP VISIT DETAILS</h4>
        </div>
    </div>



<div class="container-fluid">

<div class="tab-content" id="v-pills-tabContent">
        <?php $history=$newTreat->getHistory($_REQUEST['id']);
                        $hist=mysqli_fetch_assoc($history);
                        $edit=mysqli_num_rows($history);
                        ?>
                        <form action="controller/processTreatment.php" method="POST">
<div class="row mb-3">
    <div class="col-md-12">
    <div class="nav justify-content-around nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
  <a class="nav-link active" id="v-pills-examination-tab" data-toggle="pill" href="#v-pills-examination" role="tab" aria-controls="v-pills-home" aria-selected="true"><i class="icofont-prescription"></i> Examination</a>
  <a class="nav-link" id="v-pills-investigations-tab" data-toggle="pill" href="#v-pills-investigations" role="tab" aria-controls="v-pills-investigations" aria-selected="false"><i class="icofont-test-tube-alt"></i> Investigations</a>
  <a class="nav-link" id="v-pills-treatment-tab" data-toggle="pill" href="#v-pills-treatment" role="tab" aria-controls="v-pills-treatment" aria-selected="false"> <i class="icofont-stethoscope-alt"></i> Treatment</a>
  <a class="nav-link" id="v-pills-medication-tab" data-toggle="pill" href="#v-pills-medication" role="tab" aria-controls="v-pills-medication" aria-selected="false"><i class="icofont-pills"></i> Medication</a>
</div>
    </div>
    </div>

    <div class="row">

   
<div class="col-md-12">
    <div class="card">
        <div class="card-body">
        <div class="tab-content" id="v-pills-tabContent">
  <div class="tab-pane fade show active  " id="v-pills-examination" role="tabpanel" aria-labelledby="v-pills-examination-tab">

  
  <h4 class="text-danger text-uppercase mb-4">Step 1 : Details Medical History of <span class="text-black"> Praveen</span> </h4>
                                               
                                               <label class="text-black ml-3">PRADHANA VEDANA EVAM KALAVADHI <small>(with Duration Chronological Order)</small></label>

  
      
 
                                               <div class="row p-3 ">
                                             
                                             <div class="col-md-3"><input type="text" class="form-control" name="text1" id="text1" value="<?= $hist['sign_symptoms1']?>"></div>
                                            
                                             <div class="col-md-3"><input type="text" class="form-control" name="text2" id="text2" value="<?= $hist['sign_symptoms2']?>"></div>
                                             <div class="col-md-3"><input type="text" class="form-control" name="text3" id="text3" value="<?= $hist['sign_symptoms3']?>"></div>
                                             <div class="col-md-3"><input type="text" class="form-control" name="text4" id="text4" value="<?= $hist['sign_symptoms4']?>"></div>
                                         
                                         </div>

                                         <div class="row mt-4 p-3">
                                               <div class="col-md-4">
                                                   <div class="form-group">
                                                   <label for="" class="text-black">Atanka Samutpattikrama <small>(History of Present Illness)</small> </label>
                                                <textarea name="vedana" id="vedana" cols="1" rows="1" class="form-control"><?= $hist['present_illness']?></textarea>

                                                   </div>
                                               </div>
                                               <div class="col-md-3">
                                                   <div class="form-group">
                                                   <label for="" class="text-black"> Poorva Vyadhi Vrittanta</label>
                                                   <textarea name="poorva" id="poorva" cols="1" rows="1" class="form-control"><?= $hist['past_illness']?></textarea>

                                                   </div>
                                               </div>
                                               <div class="col-md-3">
                                                   <div class="form-group">
                                                   <label for="" class="text-black"> Chickista Vrittanta</label>
                                                   <textarea name="chikitsa" id="chikitsa" cols="1" rows="1" class="form-control"><?= $hist['old_treatment']?></textarea>

                                                   </div>
                                               </div>
                                               <div class="col-md-2">
                                                   <div class="form-group">
                                                   <label for="" class="text-black">Kutumbika Vrittanta</label>
                                                   <textarea name="kautumbika" id="kautumbika" cols="1" rows="1" class="form-control"><?= $hist['family_history']?></textarea>

                                                   </div>
                                               </div>
                                            </div>

                                            <hr>
                                            <h5 class="text-primary ">Past Medical/Surgical History</h5>
                                            <div class="row p-3 ">

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="" class="text-black">Childhood</label>
                                                    <select name="childhood" id="childhood" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Mumps">Mumps</option>
                      	 <option value="Recurent URTI">Recurent URTI</option>
                      	 <option value="Siezurer">Siezurer</option>
                      	 <option value="Jaundice">Jaundice</option>
                      	 <option value="Delayed milestone">Delayed milestone</option>
                      	 <option value="Immunization">Immunization</option>
                                                    </select>

                                                
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="" class="text-black">Adulthood</label>
                                                    <select name="adulthood" class="form-control" id="adulthood" >
                                                    <option value="">Select</option>
                      	 <option value="Typhoid">Typhoid</option>
                      	 <option value="Malaria">Malaria</option>
                      	 <option value="Trauma">Trauma</option>
                      	 <option value="Falls">Falls</option>
                      	 <option value="DM2">DM2</option>
                      	 <option value="HTN">HTN</option>
                      	 <option value="Obesity">Obesity</option>
                      	 <option value="OA">OA</option>
                      	 <option value="APD">APD</option>
                      	</select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="" class="text-black">Surgery</label>
                                                    <select name="surgery" class="form-control" id="surgery" >
                                                    <option value="">Select</option>
                                                    <option value="Hernia">Hernia</option>
                      	 <option value="Hydrocele">Hydrocele</option>
                      	 <option value="Varicicele">Varicicele</option>
                      	 <option value="Laminectomy">Laminectomy</option>
                      	 <option value="Hystrectomy">Hystrectomy</option>
                      	 
                      	</select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="" class="text-black">Medication</label>
                                                    <select name="medication" class="form-control" id="medication" >
                                                    <option value="">Select</option>
                                                    <option value="NSAIDS">NSAIDS</option>
                      	 <option value="OHA">OHA</option>
                      	 <option value="Steroid">Steroid</option>
                      	 <option value="Anti hypertensive">Anti hypertensive</option>                      	 
                      	</select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="" class="text-black">Drug Allergies</label>
                                                    <select name="allergies" class="form-control" id="allergies" >
                                                    <option value="">Select</option>
                                                    <option value="Sulph drugs">Sulph drugs</option>
                      	 <option value="Penciline">Penciline</option>
                      	 <option value="Others">Others</option>                      	                  	 
                      	</select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="" class="text-black">Medication</label>
                                                    <select name="medication" class="form-control" id="medication" >
                                                    <option value="">Select</option>
                                                    <option value="NSAIDS">NSAIDS</option>
                      	 <option value="OHA">OHA</option>
                      	 <option value="Steroid">Steroid</option>
                      	 <option value="Anti hypertensive">Anti hypertensive</option>                      	 
                      	</select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="" class="text-black">Family History</label>
                                                    <select name="family" class="form-control" id="family" >
                                                    <option value="">Select</option>
                                                    <option value="HTN">HTN</option>
                      	 <option value="DM">DM</option>
                      	 <option value="CAD">CAD</option>
                      	 <option value="IHD">IHD</option>
                      	 <option value="T.B">T.B</option>
                      	 <option value="Asthama">Asthama</option>
                      	 <option value="Cancer">Cancer</option>
                      	 <option value="Joint pain">Joint pain</option>
                      	 <option value="Obecity">Obecity</option>
                      	 <option value="Migrane">Migrane</option>
                      	 <option value="APD">APD</option>
                      	 <option value="Uricaria">Uricaria</option>
                      	 <option value="Dermatitis">Dermatitis</option>
                      	 <option value="Psoriasis">Psoriasis</option>
                      	 <option value="Night blindness">Night blindness</option>
                      	 <option value="Jaundice">Jaundice</option>
                      	 <option value="Calculai">Calculai</option>
                      	 <option value="Thyroid">Thyroid</option>
                      	 <option value="others">others</option>                  	 
                      	</select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="" class="text-black">Ahara</label>
                                                    <select name="ahara" class="form-control" id="ahara" >
                                                    <option value="">Select</option>
                                                    <option value="HTN">HTN</option>
                      	 <option value="DM">DM</option>
                      	 <option value="CAD">CAD</option>
                      	 <option value="IHD">IHD</option>
                      	 <option value="T.B">T.B</option>
                      	 <option value="Asthama">Asthama</option>
                      	 <option value="Cancer">Cancer</option>
                      	 <option value="Joint pain">Joint pain</option>
                      	 <option value="Obecity">Obecity</option>
                      	 <option value="Migrane">Migrane</option>
                      	 <option value="APD">APD</option>
                      	 <option value="Uricaria">Uricaria</option>
                      	 <option value="Dermatitis">Dermatitis</option>
                      	 <option value="Psoriasis">Psoriasis</option>
                      	 <option value="Night blindness">Night blindness</option>
                      	 <option value="Jaundice">Jaundice</option>
                      	 <option value="Calculai">Calculai</option>
                      	 <option value="Thyroid">Thyroid</option>
                      	 <option value="others">others</option>                  	 
                      	</select>
                                                </div>
                                            </div>
</div>
<hr>

<h5 class="text-primary  ">Personnel history</h5>

<div class="row p-3">
                                            <div class="col-md-2">
                                            <label for="" class="text-black text-uppercase">Marital Status</label> <br>
                                            <div class="form-check form-check-inline">
  <input  class="form-check-input"  type="radio" name="marital" checked value="unmarried"> 
  <label class="form-check-label" for="inlineRadio1">Unmarried</label> 

</div>
<div class="form-check form-check-inline">
<input type="radio" name="marital" <?php if($hist['marital']=='married') echo "checked";?> value="married"> &nbsp; &nbsp;

  <label class="form-check-label" for="inlineRadio1">Married</label> 

</div>

</div>
<div class="col-md-2">
                                            <label for="" class="text-black text-uppercase">Vayaktika Vrittanta</label> <br>
                                            <div class="form-check form-check-inline">
                                            <input type="radio" name="vayaktika" checked value="vegetarian">  
  <label class="form-check-label" for="inlineRadio1">Vegetarian</label> 

</div>
<div class="form-check form-check-inline">
<input type="radio" name="vayaktika" <?php if($hist['food']=='mixed') echo "checked"; ?> value="mixed"> 

  <label class="form-check-label" for="inlineRadio1">Mixed</label>

</div>

</div>


<div class="col-md-8 ">
<label for="" class="text-black text-uppercase">Vyasana</label> <br>
<div class="form-check form-check-inline">
<input type="checkbox" class="form-check-input" <?php if($hist['tobacco_chewing']==1) echo "checked";?> value="tobacco" name="tobacco" id="tobacco">
  <label class="form-check-label" for="inlineRadio1">Tobacco Chewing </label> &nbsp; &nbsp; &nbsp;

</div>

<div class="form-check form-check-inline">
<input type="checkbox" class="form-check-input" <?php if($hist['smoking']==1) echo "checked";?> value="smoke" name="smok" id="smok">
  <label class="form-check-label" for="inlineRadio1">Smoking</label> &nbsp; &nbsp; &nbsp;

</div>

<div class="form-check form-check-inline">
<input type="checkbox" class="form-check-input" <?php if($hist['alcohol']==1) echo "checked";?> value="alco" name="alco" id="alco">
  <label class="form-check-label" for="inlineRadio1">Alcohal</label> &nbsp; &nbsp; &nbsp;

</div>

<div class="form-check form-check-inline">
<input type="checkbox" class="form-check-input" <?php if($hist['krodha']==1) echo "checked";?> value="krodha" name="krodha" id="krodha">
  <label class="form-check-label" for="inlineRadio1">Krodha </label> &nbsp; &nbsp; &nbsp;

</div>

<div class="form-check form-check-inline">
<input type="checkbox" class="form-check-input" <?php if($hist['shoka']==1) echo "checked";?> value="shoka" name="shoka" id="shoka">
  <label class="form-check-label" for="inlineRadio1">Shoka</label> &nbsp; &nbsp; &nbsp;

</div>

<div class="form-check form-check-inline">
<input type="checkbox" class="form-check-input" <?php if($hist['chittogvega']==1) echo "checked";?> value="chittogvega" name="chittogvega" id="chittovega">
  <label class="form-check-label" for="inlineRadio1">Chittogvega</label> &nbsp; &nbsp; &nbsp;

</div>

<div class="form-check form-check-inline">
<input type="checkbox" class="form-check-input" <?php if($hist['atichintana']==1) echo "checked";?> value="atichintana" name="atichintana" id="atichintana">
  <label class="form-check-label" for="inlineRadio1">Atichintana</label> &nbsp; &nbsp; &nbsp;

</div>

<div class="form-check form-check-inline">
<input type="checkbox" class="form-check-input" <?php if($hist['vegadharana']==1) echo "checked";?> value="vegadharana" name="vegadharana" id="vegadharana">
  <label class="form-check-label" for="inlineRadio1">Vegadharana</label> &nbsp; &nbsp; &nbsp;

</div>

<div class="form-check form-check-inline">
<input type="checkbox" class="form-check-input" <?php if($hist['shift_duties']==1) echo "checked";?> value="shift" name="shift" id="shift">
  <label class="form-check-label" for="inlineRadio1">Shift Duties</label> &nbsp; &nbsp; &nbsp;

</div>

<div class="form-check form-check-inline">
<input type="checkbox" class="form-check-input" <?php if($hist['frequent_journey']==1) echo "checked";?> value="frequent" name="frequent" id="frequent">
  <label class="form-check-label" for="inlineRadio1">Frequent Journey </label> &nbsp; &nbsp; &nbsp;

</div>
<div class="form-check form-check-inline">
<input type="checkbox" class="form-check-input" <?php if($hist['udheerna']==1) echo "checked";?> value="udheerna" name="udheerna" id="udheerna">
  <label class="form-check-label" for="inlineRadio1">Udheerna </label> &nbsp; &nbsp; &nbsp;

</div>
</div>
             
</div>



<div class="row p-3 ">
    
    <div class="col-md-4">
    <h6 class="text-black">NIDRA</h6>
    <div class="form-check form-check-inline">
    <input type="radio" class="form-check-input" checked value="Satisfactory" name="nidra" id="satisfactory"> 
  <label class="form-check-label" for="inlineRadio1">Satisfactory </label> &nbsp; &nbsp; &nbsp;

</div>
<div class="form-check form-check-inline">
&nbsp;<input type="radio" class="form-check-input" <?php if($hist['nidra']=='Disturbed') echo "checked"; ?> value="Disturbed" name="nidra" id="disturbed">
  <label class="form-check-label" for="inlineRadio1">Disturbed </label> &nbsp; &nbsp; &nbsp;

</div>
<div class="form-check form-check-inline">
<input type="radio" class="form-check-input" <?php if($hist['nidra']=='Diwaswapna') echo "checked"; ?> value="Diwaswapna" name="nidra" id="diwaswapna">
  <label class="form-check-label" for="inlineRadio1">Diwaswapna </label> &nbsp; &nbsp; &nbsp;

</div>
<div class="form-check form-check-inline">
<input type="radio" class="form-check-input" <?php if($hist['nidra']=='Ratri Jagarana') echo "checked"; ?> value="Ratri Jagarana" name="nidra" id="atri">
  <label class="form-check-label" for="inlineRadio1">Ratri Jagarana </label> &nbsp; &nbsp; &nbsp;

</div>

    </div>


    <div class="col-md-4">
    <h6 class="text-black">AGNI</h6>
    <div class="form-check form-check-inline">
    <input type="radio" class="form-check-input" checked value="Sama" name="agani" id="sama"> 
  <label class="form-check-label" for="inlineRadio1">Sama </label> &nbsp; &nbsp; &nbsp;

</div>
<div class="form-check form-check-inline">
<input type="radio" class="form-check-input" <?php if($hist['agani']=='Vishama') echo "checked"; ?> value="Vishama" name="agani" id="visham"> 
  <label class="form-check-label" for="inlineRadio1">Vishama </label> &nbsp; &nbsp; &nbsp;

</div>
<div class="form-check form-check-inline">
<input type="radio" class="form-check-input" <?php if($hist['agani']=='Manda') echo "checked"; ?> value="Manda" name="agani" id="manda">
  <label class="form-check-label" for="inlineRadio1">Manda </label> &nbsp; &nbsp; &nbsp;

</div>
<div class="form-check form-check-inline">
<input type="radio" class="form-check-input" <?php if($hist['agani']=='Teekshna') echo "checked"; ?> value="Teekshna" name="agani" id="teekshna">
  <label class="form-check-label" for="inlineRadio1">Teekshna </label> &nbsp; &nbsp; &nbsp;

</div>

    </div>

    <div class="col-md-4">
    <h6 class="text-black">KOSHTAS</h6>
    <div class="form-check form-check-inline">
    <input type="radio" class="form-check-input" checked value="Sama" name="koshtas" id="koshtas"> 
  <label class="form-check-label" for="inlineRadio1">Sama </label> &nbsp; &nbsp; &nbsp;

</div>
<div class="form-check form-check-inline">
<input type="radio" class="form-check-input" <?php if($hist['koshtas']=='Mruda') echo "checked"; ?> value="Mruda" name="koshtas" id="koshtas"> 
  <label class="form-check-label" for="inlineRadio1">Mruda </label> &nbsp; &nbsp; &nbsp;

</div>
<div class="form-check form-check-inline">
<input type="radio" class="form-check-input" <?php if($hist['koshtas']=='Madhya') echo "checked"; ?> value="Madhya" name="koshtas" id="koshtas"> 
  <label class="form-check-label" for="inlineRadio1">Madhya </label> &nbsp; &nbsp; &nbsp;

</div>
<div class="form-check form-check-inline">
<input type="radio" class="form-check-input" <?php if($hist['koshtas']=='Krura') echo "checked"; ?> value="Krura" name="koshtas" id="koshtas">
  <label class="form-check-label" for="inlineRadio1">Krura </label> &nbsp; &nbsp; &nbsp;

</div>

    </div>

</div>

<hr>
<h5 class="text-primary mt-4 ">OCCUPATIONAL HISTORY</h5>

<div class="row p-3 ">

<div class="col-md-8">
<h6 class="text-black text-uppercase">Nature of Work</h6>

<div class="form-check form-check-inline">
<input type="checkbox" class="form-check-input" <?php if($hist['labour']==1) echo "checked";?> value="labour" name="labour" id="labour"> 
  <label class="form-check-label" for="inlineRadio1">Labour </label> &nbsp; &nbsp; &nbsp;
</div>
<div class="form-check form-check-inline">
<input type="checkbox" class="form-check-input" <?php if($hist['field_work']==1) echo "checked";?> value="field work" name="field work" id="field work">
  <label class="form-check-label" for="inlineRadio1">Field Work </label> &nbsp; &nbsp; &nbsp;
</div>
<div class="form-check form-check-inline">
<input type="checkbox" class="form-check-input" <?php if($hist['sedantary']==1) echo "checked";?> value="sedantary" name="sedantary" id="sedantary">
  <label class="form-check-label" for="inlineRadio1">Sedantary </label> &nbsp; &nbsp; &nbsp;
</div>
<div class="form-check form-check-inline">
<input type="checkbox" class="form-check-input" <?php if($hist['intellectual']==1) echo "checked";?> value="intellectual" name="intellectual" id="intellectual">
  <label class="form-check-label" for="inlineRadio1">Intellectual </label> &nbsp; &nbsp; &nbsp;
</div>
<div class="form-check form-check-inline">
<input type="checkbox" class="form-check-input" <?php if($hist['job_stress']==1) echo "checked";?> value="job Stress" name="job Stress" id="job Stress">
  <label class="form-check-label" for="inlineRadio1">Job Stress </label> &nbsp; &nbsp; &nbsp;
</div>


</div>

</div>
<hr>

<h5 class="text-primary mt-4 ">OBG HISTORY</h5>

<div class="row p-3 ">
    <div class="col-md-4">
        <div class="form-group">
            <label for="" class="text-black">Menarche at </label>
            <input type="text" name="menarche" class="form-control" value="<?= $hist['menarche']?>">
       
        </div>
        <h6 class="text-black text-uppercase">Periods</h6>
        <div class="form-check form-check-inline">
        <input type="checkbox" class="form-check-input" <?php if($hist['period_regular']==1) echo "checked";?> value="regular"  name="regular" id="regular"> 
  <label class="form-check-label" for="inlineRadio1">Regular</label> &nbsp; &nbsp; &nbsp;
</div>
<div class="form-check form-check-inline">
<input type="checkbox" class="form-check-input" <?php if($hist['period_irregular']==1) echo "checked";?> value="irregular" name="irregular" id="irregular">
  <label class="form-check-label" for="inlineRadio1">Irregular </label> &nbsp; &nbsp; &nbsp;
</div>
<div class="form-check form-check-inline">
<input type="checkbox" class="form-check-input" <?php if($hist['period_painful']==1) echo "checked";?> value="painful" name="painful" id="painful">
  <label class="form-check-label" for="inlineRadio1">Painful</label> &nbsp; &nbsp; &nbsp;
</div>
<div class="form-check form-check-inline">
<input type="checkbox" class="form-check-input" <?php if($hist['period_scanty']==1) echo "checked";?> value="scanty" name="scanty" id="scanty">
  <label class="form-check-label" for="inlineRadio1">Scanty</label> &nbsp; &nbsp; &nbsp;
</div>
<div class="form-check form-check-inline">
<input type="checkbox" class="form-check-input" <?php if($hist['period_withclot']==1) echo "checked";?> value="clots" name="clots" id="clots">
  <label class="form-check-label" for="inlineRadio1">with Clots </label> &nbsp; &nbsp; &nbsp;
</div>

    </div>

<div class="col-md-4">
<div class="form-group">
            <label for="" class="text-black">Day Cycle </label>
            <input type="text" name="day" class="form-control" value="<?= $hist['day_cycle']?>">
       
        </div>

        <div class="form-check form-check-inline">
        <input type="checkbox" class="form-check-input" <?php if($hist['gravida']==1) echo "checked";?> value="gravida"  name="gravida" id="gravida"> 
  <label class="form-check-label" for="inlineRadio1">Gravida</label> &nbsp; &nbsp; &nbsp;
</div>
<div class="form-check form-check-inline">
<input type="checkbox" class="form-check-input" <?php if($hist['parity']==1) echo "checked";?> value="parity" name="parity" id="parity">
  <label class="form-check-label" for="inlineRadio1">Parity</label> &nbsp; &nbsp; &nbsp;
</div>
<div class="form-check form-check-inline">
<input type="checkbox" class="form-check-input" <?php if($hist['abortion']==1) echo "checked";?> value="abortion" name="abortion" id="abortion">
  <label class="form-check-label" for="inlineRadio1">Abortion</label> &nbsp; &nbsp; &nbsp;
</div>

</div>
<div class="col-md-4">
<h6 class="text-black text-uppercase">LIVE</h6>
<div class="form-check form-check-inline">
<input type="checkbox" class="form-check-input" <?php if($hist['ftnd']==1) echo "checked";?> value="ftnd" name="ftnd" id="ft"> 
  <label class="form-check-label" for="inlineRadio1">FTND</label> &nbsp; &nbsp; &nbsp;
</div>
<div class="form-check form-check-inline">
<input type="checkbox" class="form-check-input" <?php if($hist['lscs']==1) echo "checked";?> value="lscs" name="lscs" id="ls">
  <label class="form-check-label" for="inlineRadio1">LSCS</label> &nbsp; &nbsp; &nbsp;
</div>
<div class="form-check form-check-inline">
<input type="checkbox" class="form-check-input" <?php if($hist['fthd']==1) echo "checked";?> value="fthd" name="fthd" id="fd">
  <label class="form-check-label" for="inlineRadio1">FTHD</label> &nbsp; &nbsp; &nbsp;
</div>

</div>
    

</div>


<div class="row p-3 ">
    <div class="col-md-12  ">
        <button class="btn btn-success float-right">Save</button>
        <button class="btn btn-primary float-right mr-3">Next</button>
        <button class="btn btn-primary float-right mr-3">Prvious</button>


    </div>
</div> 



  

  </div>

  <?php 
                                                             $examination=$newTreat->getExamination($_REQUEST['id']);
                                                             $exam=mysqli_fetch_assoc($examination);
                                                             $edit=mysqli_num_rows($examination);
                                                             ?>
  <div class="tab-pane fade " id="v-pills-investigations" role="tabpanel" aria-labelledby="v-pills-investigations-tab">
  <h4 class="text-danger text-uppercase mb-4">Step 2 :  Rogi Pariksha<span class="text-black"> Praveen</span> </h4>

  <div class="row p-3 ">

  <div class="col-md-4">
<h5 class="text-primary  text-uppercase">General Examination</h5>
<div class="form-check form-check-inline">
 <input class="form-check-input" type="radio" checked id="inlineCheckbox1" name="general" value="Obese">
 <label class="form-check-label text-black" for="inlineCheckbox1">Obese</label>

</div>
<div class="form-check form-check-inline">
 <input class="form-check-input" type="radio" <?php if($exam['general_exam']=='Morderate') echo "checked";?> name="general" id="inlineCheckbox2" value="Morderate">
 <label class="form-check-label text-black" for="inlineCheckbox2">Morderate</label>
</div>
<div class="form-check form-check-inline">
 <input class="form-check-input" type="radio" <?php if($exam['general_exam']=='Lean') echo "checked";?> id="inlineCheckbox3" value="Lean" name="general">
 <label class="form-check-label text-black" for="inlineCheckbox3">Lean</label>
</div>

  </div>


  <div class="col-md-8">
<h5 class="text-primary  text-uppercase">Vital Data</h5>
<div class="row">
    <div class="col-md-2">
    <div class="form-group">
                                                                          <label for="pr" class="text-black">Pulse Rate</label>
                                                                          <input type="text" name="pr" id="pr" value="<?= $exam['pulse_rate']?>" class="form-control " placeholder="e.g.15">
                                                                      </div> 
    </div>

    <div class="col-md-2">
                                                                      <div class="form-group" >
                                                                          <label for="pr" class="text-black">Heart Rate</label>
                                                                          <input type="text" name="hr" id="hr" value="<?= $exam['heart_rate']?>" class="form-control " placeholder="e.g.15">
                                                                      </div>                                                                      
                                                                  </div> 
                                                                  <div class="col-md-2">
                                                                      <div class="form-group">
                                                                          <label for="pr" class="text-black">Respiratory Rate</label>
                                                                          <input type="text" name="rr" id="rr" value="<?= $exam['respiration_rate']?>" class="form-control " placeholder="e.g.15">
                                                                      </div>                                                                      
                                                                  </div> 

                                                                  <div class="col-md-2">
                                                                      <div class="form-group">
                                                                          <label for="temp" class="text-black">Temprature</label>
                                                                          <input type="text" name="temp" id="temp" value="<?= $exam['temp']?>" class="form-control " placeholder="e.g.15">
                                                                      </div>                                                                      
                                                                  </div>

                                                                  <div class="col-md-2">
                                                                      <div class="form-group">
                                                                          <label for="bp" class="text-black">Blood Pressure</label>
                                                                          <input type="text" name="bp" id="bp" value="<?= $exam['blood_pressure']?>" class="form-control " placeholder="e.g.15">
                                                                      </div>                                                                      
                                                                  </div>

</div>



  </div>


  

 


</div>

<div class="row p-3 pt-0">

<div class="col-md-4">
<h5 class="text-primary  text-uppercase">Astasthana pareeksha</h5>

<div class="row">
                                                                  <div class="col-md-4">
                                                                      <div class="form-group">
                                                                          <label for="nadi" class="text-black">Nadi</label>
                                                                          <input type="text" name="nadi" id="nadi" value="<?= $exam['nadi']?>" class="form-control " placeholder="e.g.15">
                                                                      </div>                                                                      
                                                                  </div>

                                                                  <div class="col-md-4">
                                                                      <div class="form-group">
                                                                          <label for="mutra" class="text-black">Mutra</label>
                                                                          <input type="text" name="mutra" id="mutra" value="<?= $exam['mutra']?>" class="form-control " placeholder="e.g.15">
                                                                      </div>                                                                      
                                                                  </div> 
                                                                  <div class="col-md-4">
                                                                      <div class="form-group">
                                                                          <label for="mala" class="text-black">Mala</label>
                                                                          <input type="text" name="mala" id="mala" value="<?= $exam['mala']?>" class="form-control " placeholder="e.g.15">
                                                                      </div>                                                                      
                                                                  </div> 
                                                                </div>

</div>

<div class="col-md-4">
<h5 class="text-primary  text-uppercase mb-4">Jivha</h5>

<div class="form-check form-check-inline">
 <input class="form-check-input" type="checkbox" <?php if($exam['jivha_upalepita']=='1') echo "checked"?> id="upalepita" name="upalepita" value="upalepita">
 <label class="form-check-label" for="upalepita">Upalepita</label>
</div>
<div class="form-check form-check-inline">
 <input class="form-check-input" type="checkbox" <?php if($exam['jivha_ragayukta']=='1') echo "checked"?> name="ragayukta" id="Ragayukta" value="ragayukta">
 <label class="form-check-label" for="Ragayukta">Ragayukta</label>
</div>
<div class="form-check form-check-inline">
 <input class="form-check-input" type="checkbox" <?php if($exam['jivha_others']=='1') echo "checked"?> id="Others" value="Others" name="jivaothers">
 <label class="form-check-label" for="Others">Others</label>
</div>
<div class="form-check form-check-inline">
 <input class="form-check-input" type="checkbox" <?php if($exam['jivha_paka']=='1') echo "checked"?> id="Paka" value="Paka" name="paka">
 <label class="form-check-label" for="Paka">Paka</label>
</div>
<div class="form-check form-check-inline">
 <input class="form-check-input" type="checkbox" <?php if($exam['jivha_centrally_coated']=='1') echo "checked"?> id="cc" value="Centrally Coated" name="cc">
 <label class="form-check-label" for="cc">Centrally Coated</label>
</div>





</div>


<div class="col-md-4">
<div class="row mt-4">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="Shabda" class="text-black">Shabda</label>
                                                                <input type="text" class="form-control"value=" <?= $exam['shabda']?>" name="shabda" id="shabda">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="sparsha"  class="text-black">Sparsha</label>
                                                                <input type="text" class="form-control" value="<?= $exam['sparsha']?>" name="sparsha" id="sparsha">
                                                            </div>
                                                        </div>
                                                    </div>


</div>




  </div>


 <div class="row p-3 ">
     <div class="col-md-4">
<h5 class="text-primary  text-uppercase ">Varna</h5>
<div class="form-check form-check-inline">
 <input class="form-check-input" type="checkbox" id="ruksha" <?php if($exam['varna_ruksha']=='1') echo "checked"?> name="ruksha" value="Ruksha">
 <label class="form-check-label" for="ruksha">Ruksha</label>
</div>
<div class="form-check form-check-inline">
 <input class="form-check-input" type="checkbox" name="snigdha" <?php if($exam['varna_snigdha']=='1') echo "checked"?> id="snigdha" value="snigdha">
 <label class="form-check-label" for="snigdha">Snigdha</label>
</div>
<div class="form-check form-check-inline">
 <input class="form-check-input" type="checkbox" id="Tanu" <?php if($exam['varna_tanu']=='1') echo "checked"?> value="Tanu" name="tanu">
 <label class="form-check-label" for="Tanu">Tanu</label>
</div>
<div class="form-check form-check-inline">
 <input class="form-check-input" type="checkbox" id="Sthula" <?php if($exam['varna_sthula']=='1') echo "checked"?> value="Sthula" name="sthula">
 <label class="form-check-label" for="Sthula">Sthula</label>
</div>
<div class="form-check form-check-inline">
 <input class="form-check-input" type="checkbox" id="Ushna" <?php if($exam['varna_ushna']=='1') echo "checked"?> value="Ushna" name="ushna">
 <label class="form-check-label" for="Ushna">Ushna</label>
</div>

     </div>

     <div class="col-md-4">
<h5 class="text-primary  text-uppercase ">Drik</h5>

<div class="form-check form-check-inline">
 <input class="form-check-input" type="checkbox" id="Pale" <?php if($exam['drik_pale']=='1') echo "checked"?> name="pale" value="Pale">
 <label class="form-check-label" for="Pale">Pale</label>
</div>
<div class="form-check form-check-inline">
 <input class="form-check-input" type="checkbox" name="conjested" <?php if($exam['drik_conjested']=='1') echo "checked"?> id="Conjested" value="Conjested">
 <label class="form-check-label" for="Conjested">Conjested</label>
</div>
<div class="form-check form-check-inline">
 <input class="form-check-input" type="checkbox" id="Kalushita" <?php if($exam['drik_kalushita']=='1') echo "checked"?> value="Kalushita" name="kalushita">
 <label class="form-check-label" for="Kalushita">Kalushita</label>
</div>
<div class="form-check form-check-inline">
 <input class="form-check-input" type="checkbox" id="Akshimalasrava" <?php if($exam['drik_akshimalasrava']=='1') echo "checked"?> value="Akshimalasrava" name="akshimalasrava">
 <label class="form-check-label" for="Akshimalasrava">Akshimalasrava</label>
</div>
<div class="form-check form-check-inline">
 <input class="form-check-input" type="checkbox" id="Jalasrava" <?php if($exam['drik_jalasrava']=='1') echo "checked"?> value="Jalasrava" name="jalasrava">
 <label class="form-check-label" for="Jalasrava">Jalasrava</label>
</div>
<div class="form-check form-check-inline">
 <input class="form-check-input" type="checkbox" id="Raga" <?php if($exam['drik_raga']=='1') echo "checked"?> value="Raga" name="raga">
 <label class="form-check-label" for="Raga">Raga</label>
</div>



     </div>

     <div class="col-md-4">
<h5 class="text-primary  text-uppercase ">Akriti</h5>
<div class="form-check form-check-inline">
 <input class="form-check-input" type="radio" checked id="Stula" name="akriti" value="Stula">
 <label class="form-check-label" for="Stula">Stula</label>
</div>
<div class="form-check form-check-inline">
 <input class="form-check-input" type="radio" name="akriti" <?php if($exam['akriti']=='Krisha') echo "checked";?> id="Krisha" value="Krisha">
 <label class="form-check-label" for="Krisha">Krisha</label>
</div>
<div class="form-check form-check-inline">
 <input class="form-check-input" type="radio" id="Akriti" <?php if($exam['akriti']=='Deergha') echo "checked";?> value="Deergha" name="akriti">
 <label class="form-check-label" for="Deergha">Deergha</label>
</div>
<div class="form-check form-check-inline">
 <input class="form-check-input" type="radio" id="Akriti" <?php if($exam['akriti']=='Hrasva') echo "checked";?> value="Hrasva" name="akriti">
 <label class="form-check-label" for="Hrasva">Hrasva</label>
</div>

     </div>

 
 </div>

 <div class="row p-3 ">
<div class="col-md-6">
<h5 class="text-primary  text-uppercase ">Atutabala Pramana Pareeksha</h5>

<div class="table-responsive">
                                                                    <table class="table table-bordered " width="100%">
                                                                        <thead class="thead-dark">
                                                                            <tr>
                                                                                <th class="text-center">Bhava</th>
                                                                                <th class="text-center">Type</th>
                                                                                <th class="text-center">Assement(Pravar/Madya/Avar)</th>
                                                                            </tr>
                                                                        </thead>

                                                                        <tbody class="text-black text-center    ">
                                                                            <tr>
                                                                                <th>Prakruti</th>
                                                                                <td><input type="text" class="form-control" value="<?= $exam['prakruti_type']?>" name="praktuti_type"></td>
                                                                                <td><input type="text" class="form-control" value="<?= $exam['prakruti_assess']?>" name="praktuti_assement"></td>

                                                                            </tr>

                                                                            <tr>
                                                                                <th>Saara</th>
                                                                                <td><input type="text" class="form-control" value="<?= $exam['saara_type']?>" name="saara_type"></td>
                                                                                <td><input type="text" class="form-control" value="<?= $exam['saara_assess']?>" name="saara_assement"></td>

                                                                            </tr>
                                                                            <tr>
                                                                                <th>Smhanana</th>
                                                                                <td><input type="text" class="form-control" value="<?= $exam['smhanana_type']?>" name="smhanana_type"></td>
                                                                                <td><input type="text" class="form-control" value="<?= $exam['smhanana_assess']?>" name="smhanana_assement"></td>

                                                                            </tr>
                                                                            <tr>
                                                                                <th>Pramana</th>
                                                                                <td><input type="text" class="form-control" value="<?= $exam['pramana_type']?>" name="pramana_type"></td>
                                                                                <td><input type="text" class="form-control" value="<?= $exam['pramana_assess']?>" name="pramana_assement"></td>

                                                                            </tr>

                                                                            <tr>
                                                                                <th>Vayah</th>
                                                                                <td><input type="text" class="form-control" value="<?= $exam['vayah_type']?>" name="vayah_type"></td>
                                                                                <td><input type="text" class="form-control" value="<?= $exam['vayah_assess']?>" name="vayah_assement"></td>

                                                                            </tr>
                                                                         
                                                                        </tbody>
                                                                    </table>
                                                                </div>

</div>

<div class="col-md-6">
    
<div class="table-responsive">
                                                                    <table class="table table-bordered  mt-4" width="100%">
                                                                        <thead class="thead-dark">
                                                                            <tr>
                                                                                <th class="text-center">Bhava</th>
                                                                                <th class="text-center">Type</th>
                                                                                <th class="text-center">Assement(Pravar/Madya/Avar)</th>
                                                                            </tr>
                                                                        </thead>

                                                                        <tbody class="text-black text-center    ">
                                                                          
                                                                            <tr>
                                                                                <th>Satmya</th>
                                                                                <td><input type="text" class="form-control" value="<?= $exam['satmya_type']?>" name="satmya_type"></td>
                                                                                <td><input type="text" class="form-control" value="<?= $exam['satmya_assess']?>" name="satmya_assement"></td>

                                                                            </tr>
                                                                            <tr>
                                                                                <th>Satwa</th>
                                                                                <td><input type="text" class="form-control" value="<?= $exam['satwa_type']?>" name="satwa_type"></td>
                                                                                <td><input type="text" class="form-control" value="<?= $exam['satwa_assess']?>" name="satwa_assement"></td>

                                                                            </tr>

                                                                            <tr>
                                                                                <th>Ahara Shakti</th>
                                                                                <td><input type="text" class="form-control" value="<?= $exam['ahara_shakti_type']?>" name="aharashakti_type"></td>
                                                                                <td><input type="text" class="form-control" value="<?= $exam['ahara_shakti_assess']?>" name="aharashakti_assement"></td>

                                                                            </tr>

                                                                            <tr>
                                                                                <th>Vyayama Shakti</th>
                                                                                <td><input type="text" class="form-control" value="<?= $exam['vyayama_shakti_type']?>" name="vyayama_shakti_type"></td>
                                                                                <td><input type="text" class="form-control" value="<?= $exam['vyayama_shakti_assess']?>" name="vyayama_shakti_assement"></td>

                                                                            </tr>

                                                                            
                                                                        </tbody>
                                                                    </table>
                                                                </div>
</div>

 </div>

 <hr>

<div class="row p-3">
<div class="col-md-2">
                                                           <div class="form-group">
                                                               <label for="Shabda" class="text-black">Manah</label>
                                                               <input type="text" class="form-control" value="<?= $exam['manah']?>" name="manah" id="manah">
                                                           </div>
                                                       </div>

                                                       <div class="col-md-2">
                                                           <div class="form-group">
                                                               <label for="sparsha" class="text-black">Buddhi</label>
                                                               <input type="text" class="form-control" value="<?= $exam['buddhi']?>" name="buddhi" id="buddhi">
                                                           </div>
                                                       </div>
                                                       <div class="col-md-2">
                                                           <div class="form-group">
                                                               <label for="sparsha" class="text-black">Sanjna</label>
                                                               <input type="text" class="form-control" value="<?= $exam['sanjna']?>" name="sanjna" id="sanjna">
                                                           </div>
                                                       </div>
                                                       <div class="col-md-2">
                                                           <div class="form-group">
                                                               <label for="sparsha" class="text-black">Jnana</label>
                                                               <input type="text" class="form-control" value="<?= $exam['jnana']?>" name="jnana" id="jnana">
                                                           </div>
                                                       </div>
                                                       <div class="col-md-2">
                                                           <div class="form-group">
                                                               <label for="sparsha" class="text-black">Bhakti</label>
                                                               <input type="text" class="form-control" value="<?= $exam['bhakti']?>" name="bhakti" id="bhakti">
                                                           </div>
                                                       </div>
                                                       <div class="col-md-2">
                                                           <div class="form-group">
                                                               <label for="sparsha" class="text-black">Sheela</label>
                                                               <input type="text" class="form-control" value="<?= $exam['sheela']?>" name="sheela" id="sheela">
                                                           </div>
                                                       </div>
                                                       <div class="col-md-2">
                                                           <div class="form-group">
                                                               <label for="sparsha" class="text-black">Chesta</label>
                                                               <input type="text" class="form-control" value="<?= $exam['chesta']?>" name="chesta" id="chesta">
                                                           </div>
                                                       </div>
                                                       <div class="col-md-2">
                                                           <div class="form-group">
                                                               <label for="sparsha" class="text-black">Achara</label>
                                                               <input type="text" class="form-control" value="<?= $exam['achara']?>" name="achara" id="achara">
                                                           </div>
                                                       </div>
                                                       <div class="col-md-2">
                                                           <div class="form-group">
                                                               <label for="sparsha" class="text-black">Smriti</label>
                                                               <input type="text" class="form-control" value="<?= $exam['smriti']?>" name="smriti" id="smriti">
                                                           </div>
                                                       </div>
                                                       
                                          



</div>


<hr>
<div class="row p-3">

<div class="col-md-6">
<h5 class="text-primary  text-uppercase ">Dustilakshna</h5>
<div class="row">

<div class="col-md-6">
                                                                              <div class="table-responsive">
                                                                   <table class="table table-bordered " width="100%">
                                                                       <thead class="thead-dark">
                                                                           <tr>
                                                                               <th class="text-center">Storus</th>
                                                                               <th class="text-center">Type of Dusti Dustilakshna</th>
                                                                           </tr>
                                                                       </thead>

                                                                       <tbody class="text-black text-center    ">
                                                                           <tr>
                                                                              
                                                                               <td><input type="text" class="form-control"  value="<?= $exam['storus1']?>" name="storus1"></td>
                                                                               <td><input type="text" class="form-control"  value="<?= $exam['dustilakshna1']?>" name="type1"></td>

                                                                           </tr>
                                                                           <tr>
                                                                              
                                                                              <td><input type="text" class="form-control"  value="<?= $exam['storus2']?>" name="storus2"></td>
                                                                              <td><input type="text" class="form-control" value="<?= $exam['dustilakshna2']?>"  name="type2"></td>

                                                                          </tr>
                                                                          <tr>
                                                                              
                                                                              <td><input type="text" class="form-control" value="<?= $exam['storus3']?>"  name="storus3"></td>
                                                                              <td><input type="text" class="form-control" value="<?= $exam['dustilakshna3']?>"  name="type3"></td>

                                                                          </tr>

                                                        
                                                                       </tbody>
                                                                   </table>
                                                               </div>

                                                                              </div>
                                                                              <div class="col-md-3">
                                                                                          <div class="form-group">
                                                                                              <label for="" class="text-black">Local Examination</label>
                                                                                              <textarea name="local_exam" class="form-control" id="" cols="2" rows="9"><?= $exam['local_exam']?></textarea>
                                                                                          </div>
                                                                                      </div>

                                                                                      <div class="col-md-3">
                                                                                          <div class="form-group">
                                                                                              <label for="" class="text-black">Roge Type</label>
                                                                                              <!-- <input type="text" name="rogatype" id="rogatype" class="form-control"> -->
                                                                                              <select name="rogatype"  id="rogatype" class="form-control selectpicker"  data-live-search="true">
                                                                                               <option value=""></option>
                                                                                               <?php $roga=$newTreat->getRoga();
                                                                                               while($row=mysqli_fetch_assoc($roga))
                                                                                               {
                                                                                               ?>   
                                                                                               <option value="<?= $row['id']?>" <?php if($exam['roga_type']== $row['id']) echo "selected"; ?>><?= $row['roga_name']?></option>
                                                                                               <?php } ?>
                                                                                               </select>
                                                                                          </div>
                                                                                          </div>
                                                                                      


</div>





</div>

<div class="col-md-6">
<h5 class="text-primary  text-uppercase ">Systematic Examination</h5>
<div class="row">
                                                                               <div class="col-md-3">
                                                                                   <div class="form-group">
                                                                             <label for=""class="text-black">CVS</label>
                                                                             <input type="text" name="csv" value="<?= $exam['cvc']?>" id="csv" class="form-control">
                                                                               </div>
                                                                               </div>
                                                                               <div class="col-md-3">
                                                                                   <div class="form-group">
                                                                             <label for=""class="text-black">RS</label>
                                                                             <input type="text" name="rs" value="<?= $exam['rs']?>" id="rs" class="form-control">
                                                                               </div>
                                                                               </div>
                                                                               <div class="col-md-3">
                                                                                   <div class="form-group">
                                                                             <label for="" class="text-black">P/A</label>
                                                                             <input type="text" name="pa" value="<?= $exam['pa']?>" id="pa" class="form-control">
                                                                               </div>
                                                                               </div>
                                                                               <div class="col-md-3">
                                                                                   <div class="form-group">
                                                                             <label for="" class="text-black">CNS</label>
                                                                             <input type="text" name="cns" value="<?= $exam['cns']?>" id="cns" class="form-control">
                                                                               </div>
                                                                               </div>
                                                                               <div class="col-md-3">
                                                                                   <div class="form-group">
                                                                             <label for="" class="text-black">Power</label>
                                                                             <input type="text" name="power" value="<?= $exam['power']?>" id="power" class="form-control">
                                                                               </div>
                                                                               </div>
                                                                               <div class="col-md-3">
                                                                                   <div class="form-group">
                                                                             <label for="" class="text-black">Reflexes</label>
                                                                             <input type="text" name="reflexes" value="<?= $exam['reflexes']?>" id="reflexes" class="form-control">
                                                                               </div>
                                                                               </div>
                                                                               <div class="col-md-3">
                                                                                   <div class="form-group">
                                                                             <label for="" class="text-black">Tone</label>
                                                                             <input type="text" name="tone" value="<?= $exam['tone']?>" id="tone" class="form-control">
                                                                               </div>
                                                                               </div>
                                                                               <div class="col-md-3">
                                                                                   <div class="form-group">
                                                                             <label for="" class="text-black">others</label>
                                                                             <input type="text" name="others" value="<?= $exam['others']?>" id="others" class="form-control">
                                                                               </div>
                                                                               </div>
                                                                               <div class="col-md-3">
                                                                                   <div class="form-group">
                                                                             <label for="" class="text-black">GUS</label>
                                                                             <input type="text" name="gus" value="<?= $exam['gus']?>" id="gus" class="form-control">
                                                                               </div>
                                                                               </div>
                                                                               <div class="col-md-3">
                                                                                   <div class="form-group">
                                                                             <label for="" class="text-black">MSS</label>
                                                                             <input type="text" name="mss" value="<?= $exam['mss']?>" id="mss" class="form-control">
                                                                               </div>
                                                                               </div>
                                                                               <div class="col-md-3">
                                                                                   <div class="form-group">
                                                                             <label for="" class="text-black">Skin</label>
                                                                             <input type="text" name="skin" value="<?= $exam['skin']?>" id="skin" class="form-control">
                                                                               </div>
                                                                               </div>
                                                                           </div>


</div>



</div>



<hr>


  <div class="row p-3 ">
    <div class="col-md-12  ">
        <button class="btn btn-success float-right" >Save</button>
        <button class="btn btn-primary float-right mr-3">Next</button>
        <button class="btn btn-primary float-right mr-3" >Prvious</button>


    </div>
</div> 
  </div>
  <div class="tab-pane fade " id="v-pills-treatment" role="tabpanel" aria-labelledby="v-pills-treatment-tab">

  <h4 class="text-danger text-uppercase mb-4">Step 3 : Investigation </h4>
  <div class="row p-3">

<div class="col-md-6">
<h5 class="text-primary  text-uppercase  mb-3">Lab Tests</h5>
                                                                <div class="form-group">
                                                                    <label for="" class="text-black">Select Tests</label>
                                                                    <select name="" multiple  id="" class="form-control selectpicker"  data-live-search="true">
<optgroup label="CLINICAL HAEMOTOLOGY">

<option value="Heamoglobin">Heamoglobin</option>
<option value="Total Leucocyte count"> Total Leucocyte count</option>
<option value="Differential Leucocyte count"> Differential Leucocyte count</option>
<option value="Lymphocytes">Lymphocytes</option>
<option value="Eosinophils">Eosinophils</option>
<option value="Monocytes">Monocytes</option>
<option value="Basophils">Basophils</option>
<option value="ERYTHROCYTE SEDIMENTATION COUNT(ESR)">ERYTHROCYTE SEDIMENTATION COUNT(ESR)</option>
<option value="P S For Malaria Parasites">P S For Malaria Parasites</option>
<option value="PF/PV Antigen">PF/PV Antigen</option>
<option value="Bleeding Time(BT)">Bleeding Time(BT)</option>
<option value="Clotting Time(CT)">Clotting Time(CT)</option>
<option value="RBC Count">RBC Count</option>
<option value="Reticulocyte Count">Reticulocyte Count</option>
<option value="Platelet Count">Platelet Count</option>
<option value="Abs. Eosinophils Count">Abs. Eosinophils Count</option>
<option value="Mantoux Text(P.P.D)">Mantoux Text(P.P.D)</option>
<option value="V.D.R.L">V.D.R.L</option>
</optgroup>

<optgroup label="SEROLOGY AND IMMUNOLOGY" >
    <option value="IDV">IDV</option>
    <option value="Widal Test">Widal Test</option>
    <option value="Sal TyphinO">Sal TyphinO</option>
    <option value="Sal. Typhi H">Sal. Typhi H</option>
    <option value="Para Typhi AH">Para Typhi AH</option>
    <option value="Para Typhi BH">Para Typhi BH</option>
    <option value="Blood Group">Blood Group</option>
    <option value="RH">RH</option>
    <option value="R.A. Test">R.A. Test</option>
    <option value="A.S.L.O Titre">A.S.L.O Titre</option>
    <option value="C.R.P">C.R.P</option>
    <option value="Coombs Test(Direct/Indirect)">Coomb's Test(Direct/Indirect)</option>
    <option value="Brucella Agglutination Test">Brucella Agglutination Test</option>
    <option value="H.C.V.">H.C.V.</option>
    <option value="Dengu Test">Dengu Test</option>


</optgroup>

<optgroup label="URINE ANALYSIS">
    <option value="Pregnancy Test">Pregnancy Test</option>
    <option value="Appear">Appear</option>
    <option value="Bile Pigments">Bile Pigments</option>
    <option value="Urobillinogen">Urobillinogen</option>
    <option value="Ketone Bodies">Ketone Bodies</option>
    <option value="Albumin">Albumin</option>
    <option value="Sugar">Sugar</option>
    <option value="Microscopy">Microscopy</option>
    <option value="FBS(65-110%)">FBS(65-110%)</option>
</optgroup>

<optgroup label="BIOCHEMICAL INVESTIGATION">

<option value="PPBS(90-130%)">PPBS(90-130%)</option>
<option value="">FUS</option>
<option value="PPUS">PPUS</option>
<option value="RBS">RBS</option>
<option value="Blood Urea">Blood Urea</option>
<option value="Creatinine">Creatinine</option>
<option value="S.Cholestrol	">S.Cholestrol	</option>
<option value="Triglycerides">Triglycerides</option>
<option value="HDL Cholestrol">HDL Cholestrol</option>
<option value="LDL Cholestrol">LDL Cholestrol</option>
<option value="VLDL">VLDL</option>
<option value="S.Sodium">S.Sodium</option>
<option value="S.Potassium">S.Potassium</option>
<option value="S.Chloride">S.Chloride</option>
<option value="SERUM BILIRUBIN">SERUM BILIRUBIN</option>
<option value="Total">Total</option>
<option value="Direct">Direct</option>
<option value="SGOT">SGOT</option>
<option value="SGPT">SGPT</option>
<option value="Alk.Phosphotase">Alk.Phosphotase</option>
<option value="Total Proteins">Total Proteins</option>
<option value="Albumin">Albumin</option>
<option value="A/G Ratio">A/G Ratio</option>
<option value="Globulin">Globulin</option>
<option value="Prothrombin Time">Prothrombin Time</option>
<option value="S.Calcum">S.Calcum</option>
<option value="S.Amalyse">S.Amalyse</option>
<option value="1 st Hr">1 st Hr</option>
<option value="HB's Ag">HB's Ag</option>
<option value="GTT">GTT</option>
</optgroup>

<optgroup label="XRAY">
	
		
		
		
		
	

    <option value="Skull AP Lat">Skull AP Lat</option>

    <option value="Nesal Bone AP Lat">Nesal Bone AP Lat</option>
    <option value="Mandibule Car R and C">Mandibule Car R and C</option>

    <option value="Monstride Both">Monstride Both</option>

    <option value="P.N.S">P.N.S</option>

    <option value="C.Spine AP Cat(Cervical Spine)">C.Spine AP Cat(Cervical Spine)</option>

    <option value="Shoulder AP Lat R and L">Shoulder AP Lat R and L</option>
    <option value="Arm AP Lat R and L">Arm AP Lat R and L</option>
    <option value="Arm AP Lat">Arm AP Lat</option>
    <option value="Elbow Joint AP Lat">Elbow Joint AP Lat</option>
    <option value="Forearm AP Lat R nd L">Forearm AP Lat R nd L</option>
    <option value="Wrist Joint AP Lat R and L">Wrist Joint AP Lat R and L</option>
    <option value="Hand AP Lat R and L">Hand AP Lat R and L</option>
    <option value="Chest PA. AP">Chest PA. AP</option>
    <option value="T.H Spine AP(Thorasic Cambar)">T.H Spine AP(Thorasic Cambar)</option>
    <option value="H.S Spine AP(Thorasic Seoral)">H.S Spine AP(Thorasic Seoral)</option>
    <option value="Caciox Secom AP he">Caciox Secom AP he</option>
    <option value="Hip Joint AP R and L">Hip Joint AP R and L</option>
    <option value="Pelvis with both hip and AP">Pelvis with both hip and AP</option>
    <option value="K.G.B Regen">K.G.B Regen</option>
    <option value="Hip with femur AW R and L">Hip with femur AW R and L</option>
    <option value="Leg AP Let R and L">Leg AP Let R and L</option>
    <option value="Ankle AP Let R and L">Ankle AP Let R and L</option>
    <option value="Foot AP Cat R and L">Foot AP Cat R and L</option>
    <option value="Scpula AP">Scpula AP</option>
    <option value="hecl Calcenum cat">hecl Calcenum cat</option>
    <option value="Clavical AP">Clavical AP</option>

</optgroup>

<optgroup label="SPECIAL INVESTIGATION TEST">
    <option value="TSH">TSH</option>
    <option value="Karyotyping">Karyotyping</option>
    <option value="Anti Phospolipid Antibodies">Anti Phospolipid Antibodies</option>
    <option value="Anti B2 Glycoprotein">Anti B2 Glycoprotein</option>

    <option value="Lupus Anti Coagulant">Lupus Anti Coagulant</option>

    <option value="PAPP-A">PAPP-A</option>

    <option value="Quadraple marker test">Quadraple marker test</option>

    <option value="PCR-KOCH'S-TB">PCR-KOCH'S-TB</option>
    <option value="Serum Projestron">Serum Projestron</option>
    <option value="HbA1C">HbA1C</option>
    <option value="T3,T4,TSH">T3,T4,TSH</option>
    <option value="Serum Prolactine(PRL)">Serum Prolactine(PRL)</option>
    <option value="Serum FSH">Serum FSH</option>
    <option value="Serum LH">Serum LH</option>
    <option value="Serum Testosterone">Serum Testosterone</option>
    <option value="Rubella IgG,IgM">Rubella IgG,IgM</option>
    <option value="FT3,FT4">FT3,FT4</option>
    <option value="Anti HbsAg Anti Bodies">Anti HbsAg Anti Bodies</option>
    <option value="Vitamin B12">Vitamin B12</option>
    <option value="Vitamin D3">Vitamin D3</option>
    <option value="Beata HCG">Beata HCG</option>
    <option value="CA-125">CA-125</option>
    <option value="CA-19-9">CA-19-9</option>
    <option value="AMH">AMH</option>
    <option value="TPO Antibodies">TPO Antibodies</option>
    <option value="Anti cardiolipid antibodies.">Anti cardiolipid antibodies.</option>
    <option value="OGCT">OGCT</option>
</optgroup>

<optgroup label="ECG">
    <option value="ECG">ECG</option>
</optgroup>

<optgroup label="USG
SCANNING">
<option value="ABDOMINAL SCAN">ABDOMINAL SCAN</option>
<option value="Pelvic Scan">Pelvic Scan</option>
<option value="Follicular Study">Follicular Study</option>
<option value="HCG">HCG</option>
<option value="KUB">KUB</option>
<option value="2DECHO CARDIA GRAM">2DECHO CARDIA GRAM</option>
<option value="Others">Others</option>

</optgroup>

        </select>
                                                                </div>
                                                                
                                                            </div>

                                                            <div class="col-md-2 mt-5">

                                                            <button class="btn btn-success mt-2 btn-block"> Save</button>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="table-responsive">
                                                                    <table class="table table-bordered">
                                                                        <thead class="thead-dark">
                                                                            <tr>
                                                                                <th>Sl.No.</th>
                                                                                <th>Selected Tests</th>
                                                                                <th class="text-center">Action</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>1.</td>
                                                                                <td>
                                                                                <p>ABDOMINAL SCAN</p>
                                                                                </td>
                                                                                <td class="text-center"><a href="" class="badge badge-danger badge-pill "data-toggle="tooltip" title="Delete Test"><i class="ri-close-line"></i></a></td>
                                                                                
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                           




  </div>

  <div class="row p-3">
      <div class="col-md-12">
<h5 class="text-primary  text-uppercase  mb-3">ROGA PAREEKSHA</h5>

      </div>

<div class="col-md-3">

                                                                           <div class="form-group">
                                                                               <label for="" class="text-black">Nindana</label>
                                                                               <input type="text" name="nindana"  id="nindana"class="form-control">
                                                                           </div>
                                                                       </div>
                                                                       <div class="col-md-3">
                                                                           <div class="form-group">
                                                                               <label for="" class="text-black">Poorvapoorva</label>
                                                                               <input type="text" name="poorvapoorva"  id="poorvapoorva"class="form-control">
                                                                           </div>
                                                                       </div>
                                                                       <div class="col-md-3">
                                                                           <div class="form-group">
                                                                               <label for="" class="text-black">Roopa</label>
                                                                               <input type="text" name="roopa"  id="roopa"class="form-control">
                                                                           </div>
                                                                       </div>
                                                                       <div class="col-md-3">
                                                                           <div class="form-group">
                                                                               <label for="" class="text-black">Upashaya/Anupashaya</label>
                                                                               <input type="text" name="upashaya"  id="upashaya"class="form-control">
                                                                           </div>
                                                                       </div>

  </div>



  <div class="row p-3">
      <div class="col-md-12">
<h5 class="text-primary  text-uppercase  mb-3">Sampraptighatakas</h5>

      </div>
      <div class="col-md-2">
                                                                           <div class="form-group">
                                                                               <label class="text-black" for="">Dosha</label>
                                                                               <input type="text" name="dosha"  id="dosha"class="form-control">
                                                                           </div>
                                                                       </div>
                                                                       <div class="col-md-2">
                                                                           <div class="form-group">
                                                                               <label class="text-black" for="">Dushy</label>
                                                                               <input type="text" name="dushy"  id="dushy"class="form-control">
                                                                           </div>
                                                                       </div>
                                                                       <div class="col-md-2">
                                                                           <div class="form-group">
                                                                               <label class="text-black" for="">Agni</label>
                                                                               <input type="text" name="agni"  id="agni"class="form-control">
                                                                           </div>
                                                                       </div>
                                                                       <div class="col-md-2">
                                                                           <div class="form-group">
                                                                               <label class="text-black" for="">Srotodushtiprakarana</label>
                                                                               <input type="text" name="sroto"  id="sroto"class="form-control">
                                                                           </div>
                                                                       </div>
                                                                       <div class="col-md-2">
                                                                           <div class="form-group">
                                                                               <label class="text-black" for="">	Srotodustilakshana</label>
                                                                               <input type="text" name="lakshana"  id="lakshana"class="form-control">
                                                                           </div>
                                                                       </div>
                                                                       <div class="col-md-2">
                                                                           <div class="form-group">
                                                                               <label class="text-black" for="">VyadhiUdbhavasthana</label>
                                                                               <input type="text" name="vyadhi"  id="vyadhi"class="form-control">
                                                                           </div>
                                                                       </div>
                                                                       <div class="col-md-2">
                                                                           <div class="form-group">
                                                                               <label class="text-black" for="">Sancharasthana</label>
                                                                               <input type="text" name="sanchara"  id="sanchara"class="form-control">
                                                                           </div>
                                                                       </div>
                                                                       <div class="col-md-2">
                                                                           <div class="form-group">
                                                                               <label class="text-black" for="">Vyaktasthana</label>
                                                                               <input type="text" name="vyaktasthana"  id="vyaktasthana"class="form-control">
                                                                           </div>
                                                                       </div>
                                                                       <div class="col-md-2">
                                                                           <div class="form-group">
                                                                               <label class="text-black" for="">Vyadhiadhisthana</label>
                                                                               <input type="text" name="vyadhiadhisthana"  id="vyadhiadhisthana"class="form-control">
                                                                           </div>
                                                                       </div>
                                                                       <div class="col-md-2">
                                                                           <div class="form-group">
                                                                               <label class="text-black" for="">Rogamarga</label>
                                                                               <input type="text" name="rogamarga"  id="rogamarga"class="form-control">
                                                                           </div>
                                                                       </div>
                                                                       <div class="col-md-2">
                                                                           <div class="form-group">
                                                                               <label class="text-black" for="">Rogabala</label>
                                                                               <input type="text" name="rogabala"  id="rogabala"class="form-control">
                                                                           </div>
                                                                       </div>
                                                                       <div class="col-md-2">
                                                                           <div class="form-group">
                                                                               <label class="text-black" for="">Rogibala</label>
                                                                               <input type="text" name="rogibala"  id="rogibala"class="form-control">
                                                                           </div>
                                                                       </div>
                                                                       <div class="col-md-2">
                                                                           <div class="form-group">
                                                                               <label class="text-black" for="">Rogaswabhava</label>
                                                                               <input type="text" name="rogaswabhava"  id="rogaswabhava"class="form-control">
                                                                           </div>
                                                                       </div>
                                                                       <div class="col-md-2">
                                                                           <div class="form-group">
                                                                               <label class="text-black" for="">Upadrava</label>
                                                                               <input type="text" name="upadrava"  id="upadrava"class="form-control">
                                                                           </div>
                                                                       </div>
                                                                       <div class="col-md-2">
                                                                           <div class="form-group">
                                                                               <label class="text-black" for="">Arista lakshna</label>
                                                                               <input type="text" name="arista"  id="arista"class="form-control">
                                                                           </div>
                                                                       </div>
                                                                       <div class="col-md-2">
                                                                           <div class="form-group">
                                                                               <label class="text-black" for="">Samprapti</label>
                                                                               <input type="text" name="samprapti"  id="samprapti"class="form-control">
                                                                           </div>
                                                                       </div>
                                                                       <div class="col-md-2">
                                                                           <div class="form-group">
                                                                               <label class="text-black" for="">Sapeksha/Nidana</label>
                                                                               <input type="text" name="sapeksha"  id="sapeksha"class="form-control">
                                                                           </div>
                                                                       </div>
                                                                       <div class="col-md-2">
                                                                           <div class="form-group">
                                                                               <label class="text-black" for="">Vyavachedaka/Nidana</label>
                                                                               <input type="text" name="nidana"  id="nidana"class="form-control">
                                                                           </div>
                                                                       </div>
                                                                       <div class="col-md-2">
                                                                           <div class="form-group">
                                                                               <label class="text-black" for=""> Vyadhi / Vinishaya  </label>
                                                                               <input type="text" name="vyadi"  id="vyadi"class="form-control">
                                                                           </div>
                                                                       </div>


      </div>


 <hr>


<div class="row p-3 ">
  <div class="col-md-12  ">
      <button class="btn btn-success float-right">Save</button>
      <button class="btn btn-primary float-right mr-3">Next</button>
      <button class="btn btn-primary float-right mr-3">Prvious</button>


  </div>
</div>   
  </div>
  <div class="tab-pane fade" id="v-pills-medication" role="tabpanel" aria-labelledby="v-pills-medication-tab">
     
  <h4 class="text-danger mb-4 text-uppercase">Step 4: Medication </h4>


  <div class="row p-3">
  <div class="col-md-12">
<h5 class="text-primary  text-uppercase  mb-3">PROCEDURES/THERAPIES</h5>

      </div>
    
    
    
     
      <div class="col-md-3">
          
                                        <div class="form-group">
                                              <label class="text-black" for="">Date</label>
                                            <input type="date" class="form-control" name="pdate" id="pdate">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                              <label class="text-black" for="">Procedure</label>
                                            
                                            <select  name="procedure" id="procedure" class="form-control selectpicker" onchange="showSubProce(this.value)"  data-live-search="true">
                                                <option value="">Select Procedure</option>
                                            <?php $proce=$newTreat->getProce();
                                            while($row=mysqli_fetch_assoc($proce))
                                            {
                                            ?>
                                            <option value="<?= $row['id']?>"><?= $row['procedure_name']?></option>
                                            <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                              <label class="text-black" for="">Sub-Procedure</label>
                                            
                                            <select  name="subprocedure" id="subprocedure" class="form-control selectpicker"  data-live-search="true">
                                            <option value="">Select Sub-Procedure</option>
                                            <option value="">a</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                    </div>

                                  

                                    

<div class="col-md-3">
    <div class="form-group">
          <label class="text-black" for="">Procedure Medicine</label>
        
        <select  name="Medicine" id="Medicine" class="form-control selectpicker"  data-live-search="true">
        <option value="">Select Medicine</option>
        <option value="">a</option>
        </select>
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
          <label class="text-black" for="">Days/ml grms</label>
        <input type="text" class="form-control" name="dose" id="dose" placeholder="e.g. 10/100">
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
          <label class="text-black" for="">Total</label>
        <input type="text" class="form-control" name="total" id="total" readonly>
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
      <label class="text-black" for="">Morning</label>

    <select name="morning" id="morning" class="form-control "  >

<option value="none" >none</option>
<option value="after">After</option>
<option value="before">Before</option>
<option value="every 1 hour">Every 1 hour</option>
<option value="frequently">Frequently</option>
<option value="every 6 hour">Every 6 hour</option>
</select>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
      <label class="text-black" for="">Afternoon</label>

    <select name="afternoon" id="afternoon" class="form-control "  >

<option value="none" >none</option>
<option value="after">After</option>
<option value="before">Before</option>
<option value="every 1 hour">Every 1 hour</option>
<option value="frequently">Frequently</option>
<option value="every 6 hour">Every 6 hour</option>
</select>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
      <label class="text-black" for="">Evening</label>

    <select name="evening" id="evening" class="form-control "  >

<option value="none" >none</option>
<option value="after">After</option>
<option value="before">Before</option>
<option value="every 1 hour">Every 1 hour</option>
<option value="frequently">Frequently</option>
<option value="every 6 hour">Every 6 hour</option>
</select>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
      <label class="text-black" for="">Night</label>

    <select name="night" id="night" class="form-control "  >

<option value="none" >none</option>
<option value="after">After</option>
<option value="before">Before</option>
<option value="every 1 hour">Every 1 hour</option>
<option value="frequently">Frequently</option>
<option value="every 6 hour">Every 6 hour</option>
</select>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
          <label class="text-black" for="">Observation</label>
    <textarea name="observation" class="form-control" id="observation" cols="1" rows="1"></textarea>

    </div>
</div>

<div class="col-md-12 ">
<button class="btn btn-success float-right mb-3">Add</button>
</div>

<div class="col-md-12">
                                    <div class="table-responsive">
                                                                    <table class="table table-bordered " >
                                                                        <thead class="thead-dark">
                                                                            <tr>
                                                                                <th class="text-center" width="50px">Sl.No.</th>
                                                                                <th>Date</th>
                                                                                <th>Procedure</th>
                                                                                <th>Sub Procedure</th>
                                                                                <th class="text-center">Procedure Medicine</th>
                                                                                <th >Dose (days/ml grms)</th>
                                                                                <th>Duration</th>
                                                                               <th>Doctor Name</th>
                                                                                <th >Observation</th>
                                                                                <th >Action</th>
                                                                                
                                                                            </tr>
                                                                        </thead>

                                                                        <tbody class="text-black text-center    ">
                                                                            <tr>
                                                                               <td>1</td>
                                                                               <td>21/02/2022</td>
                                                                               <td>sdfsdf</td>
                                                                               <td>sdv sdf</td>
                                                                               <td>sdv sdf</td>

                                                                               <td>10/100</td>
                                                                               <td>Morning:after; Afternoon:after; Night:after;</td>
                                                                               <td>Dr. Manju</td>
                                                                               <td>dsfsdf</td>
                                                                              
                                                                                
                                                                                
                                                                                <td>
                                                                             
                                                                                    <a href="" class="badge badge-pill badge-danger " data-toggle="tooltip" title="Delete Medicine"><i class=" ri-close-line"></i></a>
                                                                                </td>
                                                                            </tr>
                                                    
                                                         
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                    </div>


</div>
 
 <hr>

 
 <div class="row p-3">
                                    <div class="col-md-12">
<h5 class="text-primary  text-uppercase  mb-3">OPERATION THEATER</h5>

      </div>

      <div class="col-sm-3">
                                                  <div class="form-group">
                                                      <label for="" class="text-black">Date</label>
                                                      <input type="date" name="otdate" id="otdate" class="form-control">
                                                  </div>

                                              </div>
                                              <div class="col-md-3">
                                                  <div class="form-group">
                                                      <label for="" class="text-black">OT Type</label>
                                                      <select name="ottype" id="ottype" class="form-control" required>
                                                         <option value="">Select OT Type</option>
                                                      <option value="LT">LT</option>
                                                      <option value="Major OT"> Major OT</option>
                                                      <option value="Minor OT"> Miner OT</option>
                                                      </select>
                                                  </div>
                                              </div>


                                              <div class="col-md-3">
                                                  <div class="form-group">
                                                      <label for="" class="text-black"> Major Operative Procedures </label>
                                                      <select name="ottype" id="ottype" class="form-control selectpicker" data-live-search="true" required>
                                                        <option value="Hemorrhoidectomy">Hemorrhoidectomy</option>
                                                        <option value="Complex Fistula">Complex Fistula</option>
                                                        <option value="Suprapubic cystolithotomy">Suprapubic cystolithotomy</option>
                                                        <option value="Umbilical Granuloma Excision">Umbilical Granuloma Excision</option>
                                                        <option value="Umbilical Hernia Repair">Umbilical Hernia Repair</option>
                                                        <option value="Adult Unilateral Hernia repair">Adult Unilateral Hernia repair</option>
                                                        <option value="Femoral Repair – Unilateral Hernia">Femoral Repair – Unilateral Hernia</option>
                                                        <option value="Femoral Repair – Bilateral Hernia">Femoral Repair – Bilateral Hernia</option>
                                                        <option value="Inguinal Hernia Repair- Bilateral & Unilateral">Inguinal Hernia Repair- Bilateral & Unilateral</option>
                                                        <option value="Epigastric hernia Repair">Epigastric hernia Repair</option>
                                                        <option value="Varicocele Repair- Inguinal">Varicocele Repair- Inguinal</option>
                                                        <option value="Simple Scrotal Orchidectomy – Unilateral">Simple Scrotal Orchidectomy – Unilateral</option>

                                                        </select>
                                                    </div>
                                                </div>
                                              <div class="col-md-3">
                                                  <div class="form-group">
                                                      <label for="" class="text-black"> Minor Operative Procedures </label>
                                                      <select name="ottype" id="ottype" class="form-control selectpicker" data-live-search="true" required>
                                                          <option value="">Select Proceduer</option>
                                                         
                                                      <option value="Granthi (Excision)">Granthi (Excision)</option>
                                                      <option value="Arbud (Excision) ">Arbud (Excision) </option>

                                                      <option value="Nadivran (Excision)">Nadivran (Excision)</option>

                                                      <option value="Kunakh (Excision)">Kunakh (Excision)</option>

                                                      <option value="Kunakh (Excision)">Kunakh (Excision)</option>

                                                      <option value="Vidradhi Bhedan">Vidradhi Bhedan</option>
                                                      <option value="Mutravrudhhi">Mutravrudhhi</option>
                                                      <option value="Mutravrudhhi">Mutravrudhhi</option>
                                                      <option value=" Debridement of infected wound/fistula/sinus"> Debridement of infected wound/fistula/sinus</option>
                                                      <option value="Circumcision">Circumcision</option>
                                                      <option value="Paracentesis">Paracentesis</option>
                                                      <option value="Anal Dilation">Anal Dilation</option>
                                                      <option value="Anal Sphincterotomy">Anal Sphincterotomy</option>
                                                      <option value="Excision of Anal Tag">Excision of Anal Tag</option>
                                                      <option value="Pilonidal Abscess">Pilonidal Abscess</option>
                                                      <option value="Proctosigmoidoscopy">Proctosigmoidoscopy</option>
                                                      <option value="Contused Lacerated Wound (CLW) suturing">Contused Lacerated Wound (CLW) suturing</option>
                                                      <option value="Lobuloplasty">Lobuloplasty</option>
                                                      <option value="Low Anal Fistula -Fistulectomy">Low Anal Fistula -Fistulectomy</option>
                                                      <option value="Peri Anal Abscess Incision and Drainage">Peri Anal Abscess Incision and Drainage</option>
                                                      <option value="Urethral Stricture – Dilatation">Urethral Stricture – Dilatation</option>

                                                      <option value="Rectal Prolapse-Thiersch procedure">Rectal Prolapse-Thiersch procedure</option>
                                                      <option value="Excision of Ganglion – Wrist / Hand / Finger">Excision of Ganglion – Wrist / Hand / Finger</option>
                                                      <option value="Removal of Foreign Body (simple)">Removal of Foreign Body (simple)</option>
                                                      <option value="Excision of Epididymal Cyst, Spermatocele, or Epididymectomy – Unilateral & Bilateral">Excision of Epididymal Cyst, Spermatocele, or Epididymectomy – Unilateral & Bilateral</option>
                                                      <option value="Dorsal Slit for Paraphimosis">Dorsal Slit for Paraphimosis</option>
                                                      <option value="Bartholins Cyst / Abscess Surgery">Bartholins Cyst / Abscess Surgery</option>
                                                      <option value="Cautery or Laser Destruction of Vulval Warts">Cautery or Laser Destruction of Vulval Warts</option>
                                                      

                                                    
                                                      </select>
                                                  </div>
                                              </div>
                                              

                                              <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="" class="text-black">Comments</label>
                                                    <textarea name="comments" id="comments" cols="1" rows="1" class="form-control"></textarea>
                                                </div>
                                              </div>
                                              

                                              <div class="col-md-12 mb-3">
                                                <div class="form-group">
                                                    <button class="btn btn-success float-right">Add</button>
                                                   </div>
                                              </div>

                                              <div class="col-md-12">
                                    <div class="table-responsive">
                                                                    <table class="table table-bordered " >
                                                                        <thead class="thead-dark  text-center ">
                                                                            <tr>
                                                                                <th class="text-center" width="50px">Sl.No.</th>
                                                                                <th>Date</th>
                                                                                <th>Ot Type</th>
                                                                                <th>Procedure</th>
                                                                                <th>
                                                                                Comments
                                                                                </th>
                                                                                
                                                                              
                                                                                <th >Action</th>
                                                                                
                                                                            </tr>
                                                                        </thead>

                                                                        <tbody class="text-black text-center    ">
                                                                            <tr>
                                                                               <td>1</td>
                                                                               <td>21/02/2022</td>
                                                                           
                                                                            <td></td>
                                                                            <td></td>
                                                                              <td></td>
                                                                                
                                                                                
                                                                                <td>
                                                                             
                                                                                    <a href="" class="badge badge-pill badge-danger  " data-toggle="tooltip" title="Delete Entry"><i class=" ri-close-line"></i></a>
                                                                                </td>
                                                                            </tr>
                                                    
                                                         
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                    </div>



      </div>
 
<hr>
<div class="row p-3 ">
  <div class="col-md-12  ">
      <button class="btn btn-success float-right">Save</button>
      <button class="btn btn-primary float-right mr-3 " disabled>Next</button>
      <button class="btn btn-primary float-right mr-3">Prvious</button>


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


</div>

        <!--**********************************
            Content body end
        ***********************************-->


        <!--**********************************
            Footer start
        ***********************************-->
        <?php include('footer.php') ?>
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

    <!-- <script src="assets/js/common.min.js"></script> -->
    <!-- <script src="assets/js/bootstrap-select"></script> -->


    <!-- <script src="assets/bootstrap-multiselect/js/bootstrap-multiselect.js"></script>
    <script src="assets/js/bootstrap-multiselect-init.js"></script>
    <script src="assets/js/bootstrap-select-init.js"></script> -->
  

    


    

    
    <!-- Datatable -->
    <script src="./vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="./js/plugins-init/datatables.init.js"></script>
    <script src="./js/plugins-init/datatables.init.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>


<script>
    function showSubProce(val)
    {
        $.ajax({
            type : 'post',
            url : 'ajax.php', //Here you will fetch records 
            data :  'proce='+val+'&page=sub_proce', //Pass $id
            success : function(data){
            $('#subprocedure').html(data);//Show fetched data from database
            }
        });
    }

</script> 

  

</body>

</html>