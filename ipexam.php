<?php 
require_once(__DIR__ . '/model/session_config.php');
include('model/treatmentClass.php');

$newTreat=new addTreat;
$patient= $newTreat->getIpPatient($_REQUEST['id']);
$dt=mysqli_fetch_assoc($patient);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>IP Treatment</title>
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

<style>
    .col-md-4,.col-md-6{

        margin-bottom:20px;
    }
     .bootstrap-select .btn-light{
    color:black!important
    }
    
    .bootstrap-select .btn-light {
        background-color:white;
    }
     .bootstrap-select .btn-light:hover {
        background-color:white;
    }
</style>

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
            <h4 class="text-uppercase">IP DETAILS</h4>
        </div>
    </div>




    <div class="row">

   
<div class="col-md-12">
    <div class="card">
        <div class="card-body">
  

  <?php $history=$newTreat->getHistory($_REQUEST['id']);
                        $hist=mysqli_fetch_assoc($history);
                        $edit=mysqli_num_rows($history);
                        ?>
  <form action="controller/processTreatment.php" method="POST">

<?php 
$examination=$newTreat->getExamination($_REQUEST['id']);
$exam=mysqli_fetch_assoc($examination);
$edit=mysqli_num_rows($examination);
?>
  <h4 class="text-danger text-uppercase mb-4">  Examination of <span class="text-black"> <?= $dt['name'].' ( '.$_REQUEST['id'].' ) '?></span></h4>

  <div class="row p-3">
    <div class="col-md-12 mb-2">
        <h4 class="text-primary">1. Physical Examination</h4>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <h5>Pulse</h5>
            <input type="text" class="form-control" value="<?php echo $exam['pulse']; ?>"name="pulse" id="pulse">
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <h5>Respiration</h5>
            <input type="text" class="form-control" value="<?php echo $exam['respiration']; ?>"name="p_respiration" id="p_respiration">
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <h5>Temprature</h5>
            <input type="text" class="form-control" value="<?php echo $exam['temp']; ?>"name="p_temp" id="p_temp">
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <h5>Blood Pressure</h5>
            <input type="text" class="form-control"value="<?php echo $exam['bp']; ?>" name="bp" id="bp">
        </div>
    </div>
    
    <div class="col-md-2">
        <div class="form-group">
            <h5>Weight/Height</h5>
            <input type="text" class="form-control"value="<?php echo $exam['wh']; ?>" name="wh" id="wh">
        </div>
    </div>
    
    <div class="col-md-2">
        <div class="form-group">
            <h5>Built</h5>
            <input type="text" class="form-control" value="<?php echo $exam['built']; ?>"name="built" id="built">
        </div>
    </div>
  
    
    <div class="col-md-2">
        <div class="form-group">
            <h5>Scalp</h5>
            <input type="text" class="form-control"value="<?php echo $exam['scalp']; ?>" name="scalp" id="scalp">
        </div>
    </div>
    
    <div class="col-md-2">
        <div class="form-group">
            <h5>Eyes</h5>
            <input type="text" class="form-control" value="<?php echo $exam['eyes']; ?>"name="eyes" id="eyes">
        </div>
    </div>
    
    <div class="col-md-2">
        <div class="form-group">
            <h5>Nose</h5>
            <input type="text" class="form-control" value="<?php echo $exam['nose']; ?>"name="p_nose" id="p_nose">
        </div>
    </div>
    
    <div class="col-md-2">
        <div class="form-group">
            <h5>Oral Cavity</h5>
            <input type="text" class="form-control" value="<?php echo $exam['oral_cavity']; ?>"name="oral_cavity" id="oral_cavity">
        </div>
    </div>

    
    <!--<div class="col-md-2">-->
    <!--    <div class="form-group">-->
    <!--        <h5>Tongue</h5>-->
    <!--        <input type="text" class="form-control" name="tounge" id="tounge">-->
    <!--    </div>-->
    <!--</div>-->
    
    <div class="col-md-2">
        <div class="form-group">
            <h5>Neck Glands</h5>
            <input type="text" class="form-control" value="<?php echo $exam['tounge']; ?>"name="p_neck" id="p_neck">
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <h5>Nails</h5>
            <input type="text" class="form-control"value="<?php echo $exam['neck']; ?>" name="nails" id="nails">
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <h5>Tongue</h5>
            <input type="text" class="form-control" value="<?php echo $exam['nails']; ?>"name="p_tounge" id="p_tounge">
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <h5>Edema</h5>
            <input type="text" class="form-control" value="<?php echo $exam['edema']; ?>"name="edema" id="edema">
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <h5>Lymph Nodes</h5>
            <input type="text" class="form-control" value="<?php echo $exam['lymph']; ?>"name="lymph" id="lymph">
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <h5>Pallor/ Anemia</h5>
            <input type="text" class="form-control"value="<?php echo $exam['anemia']; ?>" name="p_anemia" id="p_anemia">
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <h5> Cyanosis</h5>
            <input type="text" class="form-control"value="<?php echo $exam['cyanosis']; ?>" name="p_cyanosis" id="p_cyanosis">
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <h5>Jaundice</h5>
            <input type="text" class="form-control" value="<?php echo $exam['jaundice']; ?>"name="jaundice" id="jaundice">
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <h5>Pigmentations</h5>
            <input type="text" class="form-control"value="<?php echo $exam['pigmentation']; ?>" name="pigmentation" id="pigmentation">
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <h5>Others</h5>
            <input type="text" class="form-control"value="<?php echo $exam['others']; ?>" name="others" id="others">
        </div>
    </div>






  </div>

  <div class="row px-3">
    <div class="col-md-12 mb-2">
        <h4 class="text-primary">2. Systemic Examination</h4>
        <h5 class="p-2">2.1 Respiratory System</h5>
    </div>

    


  </div>


  <div class="row px-5">
  <div class="col-md-12">
<h5 class="mb-4">a. Inspection</h5>
</div>
<div class="col-md-4">
    <div class="from-group">
        <label> Respiratory Rate & Rhythm</label>
        <input type="text" class="form-control" value="<?php echo $exam['rr']; ?>"name="rr" id="rr">
    </div>
</div>

<div class="col-md-4">
    <div class="from-group">
        <label>Shape and Symmetry of the Chest</label>
        <input type="text" class="form-control"value="<?php echo $exam['chest_shape']; ?>" name="chest_shape" id="chest_shape">
    </div>
</div>
<div class="col-md-4">
    <div class="from-group">
        <label>Nose</label>
        <input type="text" class="form-control"value="<?php echo $exam['r_nose']; ?>" name="r_nose" id="r_nose">
    </div>
</div>

<div class="col-md-4">
    <div class="from-group">
        <label>Throat </label>
        <input type="text" class="form-control"value="<?php echo $exam['r_throat']; ?>" name="r_throat" id="r_throat">
    </div>
</div>

<div class="col-md-4">
    <div class="from-group">
        <label>Cyanosis  </label>
        <input type="text" class="form-control"value="<?php echo $exam['r_cyanosis']; ?>" name="r_cyanosis" id="r_cyanosis">
    </div>
</div>


<div class="col-md-4">
    <div class="from-group">
        <label>Others  </label>
        <input type="text" class="form-control"value="<?php echo $exam['others_a']; ?>" name="others_a" id="others_a">
    </div>
</div>

</div>

<div class="row px-5 mt-3">
  <div class="col-md-12">
<h5 class="mb-4">b. Palpation</h5>
</div>
<div class="col-md-4">
    <div class="from-group">
        <label>Confirmation of Respiratory Moments</label>
        <input type="text" class="form-control" value="<?php echo $exam['resp_moment']; ?>" name="resp_moment" id="resp_moment">
    </div>
</div>

<div class="col-md-4">
    <div class="from-group">
        <label>Position of Mediastinum</label>
        <input type="text" class="form-control" value="<?php echo $exam['mediastinum']; ?>" name="mediastinum" id="mediastinum">
    </div>
</div>
<div class="col-md-4">
    <div class="from-group">
        <label>Tenderness</label>
        <input type="text" class="form-control" value="<?php echo $exam['tenderness']; ?>" name="tenderness" id="tenderness">
    </div>
</div>
<div class="col-md-4">
    <div class="from-group">
        <label>Others  </label>
        <input type="text" class="form-control" value="<?php echo $exam['others_b']; ?>" name="others_b" id="others_b">
    </div>
</div>

</div>


<div class="row px-5 mt-3">
  <div class="col-md-12">
<h5 class="mb-4">c. Percussion</h5>
</div>


<div class="row px-5">
<div class="col-md-6">
    <h5>Resonant</h5>

    <div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="resonant" <?php if($exam['resonant']=='Normal') { echo 'checked'; } ?>id="res_normal" value="Normal">
  <label class="form-check-label" for="res_normal">Normal</label>
</div>
<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="resonant" <?php if($exam['resonant']=='Abnormal') { echo 'checked'; } ?> id="res_abnoraml" value="Abnormal">
  <label class="form-check-label" for="res_abnoraml">Abnormal</label>
</div>


</div>

<div class="col-md-6">
    <h5>Cardiac Dullness</h5>

    <div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="cardiac_dullness" <?php if($exam['cardiac_dullness']=='Normal') { echo 'checked'; } ?> id="car_normal" value="Normal">
  <label class="form-check-label" for="car_normal">Normal</label>
</div>
<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="cardiac_dullness" <?php if($exam['cardiac_dullness']=='Abnormal') { echo 'checked'; } ?> id="car_abnoraml" value="Abnormal">
  <label class="form-check-label" for="car_abnoraml">Abnormal</label>
</div>


</div>

<div class="col-md-6 mt-2">
    <h5>Liver Dullness</h5>

    <div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="liver_dullness" <?php if($exam['liver_dullness']=='Normal') { echo 'checked'; } ?> id="liver_normal" value="Normal">
  <label class="form-check-label" for="liver_normal">Normal</label>
</div>
<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="liver_dullness" <?php if($exam['liver_dullness']=='Abnormal') { echo 'checked'; } ?> id="liver_abnoraml" value="Abnormal">
  <label class="form-check-label" for="liver_abnoraml">Abnormal</label>
</div>


</div>


<div class="col-md-6 mt-2">
    <h5>Dullness in other field indicates</h5>

    <div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="other_dullness" id="other_normal" <?php if($exam['other_dullness']=='Pleural Effusion') { echo 'checked'; } ?> value="Pleural Effusion">
  <label class="form-check-label" for="other_normal">Pleural Effusion</label>
</div>
<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="other_dullness" id="other_abnoraml" <?php if($exam['other_dullness']=='Lobar Pneumonia') { echo 'checked'; } ?> value="Lobar Pneumonia">
  <label class="form-check-label" for="other_abnoraml">Lobar Pneumonia</label>
</div>

</div>


<div class="col-md-6 mt-2">
    <h5>Hyper Resonance Indicates</h5>

    <div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="hyper" id="hyper_normal" <?php if($exam['hyper']=='Pneumothorax') { echo 'checked'; } ?> value="Pneumothorax">
  <label class="form-check-label" for="hyper_normal">Pneumothorax</label>
</div>
<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="hyper" id="hyper_abnoraml" <?php if($exam['hyper']=='Emphysema') { echo 'checked'; } ?> value="Emphysema">
  <label class="form-check-label" for="hyper_abnoraml">Emphysema</label>
</div>

</div>

    
</div>






<div class="row px-4 mt-3">
  <div class="col-md-12">
<h5 class="mb-4">d. Auscultation</h5>
</div>
<div class="col-md-6">
    <div class="from-group">
        <label>Breath sounds Vesicular</label>
        <input type="text" class="form-control" value="<?php echo $exam['a_breath_sounds']; ?>" name="a_breath_sounds" id="a_breath_sounds">
    </div>
</div>

<div class="col-md-6">
    <div class="from-group">
        <label>Bronchovesicular</label>
        <input type="text" class="form-control" value="<?php echo $exam['broncho']; ?>" name="broncho" id="broncho">
    </div>
</div>

    <div class="col-md-6 mt-2">
    <h5>Bronchial Breathing Sounds</h5>

    <div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="breath_sounds" <?php if($exam['breath_sounds']=='Tubular') { echo 'checked'; } ?> id="Tubular" value="Tubular">
  <label class="form-check-label" for="Tubular">Tubular</label>
</div>
    <div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="breath_sounds" <?php if($exam['breath_sounds']=='Cavernous') { echo 'checked'; } ?> id="Cavernous" value="Cavernous">
  <label class="form-check-label" for="Cavernous">Cavernous</label>
</div>
<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="breath_sounds" <?php if($exam['breath_sounds']=='Amphoric') { echo 'checked'; } ?> id="Amphoric" value="Amphoric">
  <label class="form-check-label" for="Amphoric">Amphoric</label>
</div>


</div>


</div>



    <div class="col-md-6 mt-2">
    <h5>Added Sounds </h5>

    <div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="added_sounds" id="Crepitations" <?php if($exam['added_sounds']=='Crepitations') { echo 'checked'; } ?> value="Crepitations">
  <label class="form-check-label" for="Crepitations">Crepitations</label>
</div>
    <div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="added_sounds" id="Rhonchi" <?php if($exam['added_sounds']=='Rhonchi') { echo 'checked'; } ?> value="Rhonchi">
  <label class="form-check-label" for="Rhonchi">Rhonchi</label>
</div>
<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="added_sounds" id="Pleural rub " <?php if($exam['added_sounds']=='Pleural rub') { echo 'checked'; } ?> value="Pleural rub">
  <label class="form-check-label" for="Pleural rub ">Pleural rub </label>
</div>

<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="added_sounds" id="added_other" <?php if($exam['added_sounds']=='Other') { echo 'checked'; } ?> value="Other">
  <label class="form-check-label" for="added_other">Others </label>
</div>


</div>


</div>







<div class="row px-3 mt-4">
    <div class="col-md-12 mb-2">
        
        <h5 class="p-2">2.2 Cardiovascular System –</h5>
    </div>
 </div>


  <div class="row px-5">
 
<div class="col-md-2">
    <div class="from-group">
        <label> Pulse rate</label>
        <input type="text" class="form-control"value="<?php echo $exam['pr']; ?>" name="pr" id="pr">
    </div>
</div>

<div class="col-md-2">
    <div class="from-group">
        <label>Rhythm</label>
        <input type="text" class="form-control"value="<?php echo $exam['rythom']; ?>" name="rythom" id="rythom">
    </div>
</div>
<div class="col-md-2">
    <div class="from-group">
        <label>Volume</label>
        <input type="text" class="form-control" value="<?php echo $exam['volume']; ?>"name="volume" id="volume">
    </div>
</div>

<div class="col-md-2">
    <div class="from-group">
        <label>Character </label>
        <input type="text" class="form-control"value="<?php echo $exam['character']; ?>" name="character" id="character">
    </div>
</div>

<div class="col-md-2">
    <div class="from-group">
        <label>Blood Pressure  </label>
        <input type="text" class="form-control"value="<?php echo $exam['bp2']; ?>" name="bp2" id="bp2">
    </div>
</div>

<div class="col-md-3 mt-3">
    <div class="from-group">
        <label>Sign of CCF – Raised JVP  </label>
        <input type="text" class="form-control"value="<?php echo $exam['ccf']; ?>" name="ccf" id="ccf">
    </div>
</div>
<div class="col-md-3 mt-3">
    <div class="from-group">
        <label>Tender hepatomegaly </label>
        <input type="text" class="form-control"value="<?php echo $exam['tender']; ?>" name="tender" id="tender">
    </div>
</div>
<div class="col-md-3 mt-3">
    <div class="from-group">
        <label>Pedal edema </label>
        <input type="text" class="form-control" value="<?php echo $exam['pedal']; ?>"name="pedal" id="pedal">
    </div>
</div>
<div class="col-md-3 mt-3">
    <div class="from-group">
        <label>Others – Clubbing </label>
        <input type="text" class="form-control"value="<?php echo $exam['other_clubing']; ?>" name="other_clubing" id="other_clubing">
    </div>
</div>
<div class="col-md-3 mt-3">
    <div class="from-group">
        <label>Cyanosis</label>
        <input type="text" class="form-control"value="<?php echo $exam['c_cyanosis']; ?>" name="c_cyanosis" id="c_cyanosis">
    </div>
</div>
<div class="col-md-3 mt-3">
    <div class="from-group">
        <label>Anemia </label>
        <input type="text" class="form-control" value="<?php echo $exam['c_anemia']; ?>"name="c_anemia" id="c_anemia">
    </div>
</div>

</div>


<div class="row px-5 mt-5">
    <h5 class="">Inspection </h5>

</div>

<div class="row px-5 mt-2">
    <div class="col-md-4">
        <div class="form-group">
            <label>Chest Wall shape</label>
            <input type="text" name="chest_wall" value="<?php echo $exam['chest_wall']; ?>"id="chest_wall" class="form-control">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label> Apex beat</label>
            <input type="text" name="apex_beat"value="<?php echo $exam['apex_beat']; ?>" id="apex_beat" class="form-control">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label> Other Pulsations</label>
            <input type="text" name="pulsation"value="<?php echo $exam['pulsation']; ?>" id="pulsation" class="form-control">
        </div>
    </div>
</div>

<div class="row px-5 ">
    <h5 class="">Palpation  </h5>

</div>

<div class="row px-5 mt-2">
    <div class="col-md-4">
        <div class="form-group">
            <label>Apex beat location</label>
            <input type="text" name="beat_loaction" value="<?php echo $exam['beat_loaction']; ?>"id="beat_loaction" class="form-control">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label> Character </label>
            <input type="text" name="charecter"value="<?php echo $exam['i_character']; ?>" id="charecter" class="form-control">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label> Tender areas</label>
            <input type="text" name="tender_areas" value="<?php echo $exam['tender_areas']; ?>"id="tender_areas" class="form-control">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label> Any other palpation location</label>
            <input type="text" name="other_location"value="<?php echo $exam['other_location']; ?>" id="other_location" class="form-control">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label> Character</label>
            <input type="text" name="charecter2" value="<?php echo $exam['i_character2']; ?>"id="charecter2" class="form-control">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label> Tender areas</label>
            <input type="text" name="tender_areas2"value="<?php echo $exam['tender_areas2']; ?>" id="tender_areas2" class="form-control">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label> Thrills – Systolic</label>
            <input type="text" name="thrills"value="<?php echo $exam['thrills']; ?>" id="thrills" class="form-control">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label> Diastolic </label>
            <input type="text" name="diastolic"value="<?php echo $exam['diastolic']; ?>" id="diastolic" class="form-control">
        </div>
    </div>
    
</div>


<div class="row px-5 ">
    <h5 class="">Percussion  </h5>

</div>

<div class="row px-5 mt-2">
    <div class="col-md-4">
        <div class="form-group">
            <label>Dull note – Location</label>
            <input type="text" name="dull_loaction"value="<?php echo $exam['dull_loaction']; ?>" id="dull_loaction" class="form-control">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label> Character </label>
            <input type="text" name="dull_charecter"value="<?php echo $exam['dull_character']; ?>" id="dull_charecter" class="form-control">
        </div>
    </div>

</div>

<div class="row px-5 ">
    <h5 class="">Auscultation   </h5>

</div>

<div class="row px-5 mt-2">
    <div class="col-md-3">
        <div class="form-group">
            <label>Heart Sounds 1st</label>
            <input type="text" name="sound1"value="<?php echo $exam['sound1']; ?>" id="sound1" class="form-control">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label> Heart Sounds 2nd </label>
            <input type="text" name="sound2"value="<?php echo $exam['sound2']; ?>" id="sound2" class="form-control">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label> Splitting </label>
            <input type="text" name="splitting"value="<?php echo $exam['splitting']; ?>" id="splitting" class="form-control">
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label> Added sounds </label>
            <input type="text" name="a_added_sounds" value="<?php echo $exam['a_added_sounds']; ?>"id="a_added_sounds" class="form-control">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label> Murmurs – Site </label>
            <input type="text" name="site" id="site" value="<?php echo $exam['site']; ?>"class="form-control">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label> Timing </label>
            <input type="text" name="heart_timing"value="<?php echo $exam['heart_timing']; ?>" id="heart_timing" class="form-control">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label> Character </label>
            <input type="text" name="heart_char"value="<?php echo $exam['heart_char']; ?>" id="heart_char" class="form-control">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label> Pitch </label>
            <input type="text" name="pitch"value="<?php echo $exam['pitch']; ?>" id="pitch" class="form-control">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label> Grade </label>
            <input type="text" name="grade" value="<?php echo $exam['grade']; ?>"id="grade" class="form-control">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label> Pericardial rub –Present </label>
            <input type="text" name="present" value="<?php echo $exam['present']; ?>"id="present" class="form-control">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label> Absent </label>
            <input type="text" name="absent" value="<?php echo $exam['absent']; ?>"id="absent" class="form-control">
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="form-group">
            <label> Other </label>
            <input type="text" name="other_heart" value="<?php echo $exam['other_heart']; ?>"id="other_heart" class="form-control">
        </div>
    </div>

</div>


<div class="row px-3 mt-3">

<h4 class="text-primary px-4 ">
3. Gastrointestinal System/Per-abdominal Examination
</h4>

<div class="col-md-12 px-5 mt-2">
    <h5>Oral Cavity</h5>


</div>


</div>


<div class="row px-5">


<div class="col-md-6">
    <div class="row">
    <h5 class="px-3 mt-3">
    Lips
</h5>
    <div class="col-md-12">
<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="lips" id="Fissure" <?php if($exam['lips']=='Fissure') { echo 'checked'; } ?>  value="Fissure">
  <label class="form-check-label" for="Fissure">Fissure</label>
</div>
    <div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="lips" id="Cracks" <?php if($exam['lips']=='Cracks') { echo 'checked'; } ?>  value="Cracks">
  <label class="form-check-label" for="Cracks">Cracks</label>
</div>
<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="lips" id="Discoloration " <?php if($exam['lips']=='Discoloration') { echo 'checked'; } ?>  value="Discoloration ">
  <label class="form-check-label" for="Discoloration ">Discoloration</label>
</div>

</div>
    </div>
</div>

<div class="col-md-6">
    <div class="row">
    <h5 class="px-3 mt-3">
    Teeth – Dental caries
</h5>
    <div class="col-md-12">
<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="teeth" id="teeth_present" <?php if($exam['teeth']=='present') { echo 'checked'; } ?> value="present">
  <label class="form-check-label" for="teeth_present">Present</label>
</div>
    <div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="teeth" id="teeth_absent" <?php if($exam['teeth']=='absent') { echo 'checked'; } ?> value="absent">
  <label class="form-check-label" for="teeth_absent">Absent</label>
</div>


</div>
    </div>
</div>


</div>


<div class="row px-5">


<div class="col-md-6">
    <div class="row">
    <h5 class="px-3 mt-3">
    Tongue
</h5>
    <div class="col-md-12">
<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="g_toungue" id="Coating" <?php if($exam['g_toungue']=='Coating') { echo 'checked'; } ?> value="Coating">
  <label class="form-check-label" for="Coating">Coating</label>
</div>
    <div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="g_toungue" id="t_Fasciculation" <?php if($exam['g_toungue']=='Fasciculation') { echo 'checked'; } ?> value="Fasciculation">
  <label class="form-check-label" for="t_Fasciculation">Fasciculation</label>
</div>
<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="g_toungue" id="Cracks " <?php if($exam['g_toungue']=='Cracks') { echo 'checked'; } ?> value="Cracks">
  <label class="form-check-label" for="Cracks ">Cracks</label>
</div>
<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="g_toungue" id="Wasting " <?php if($exam['g_toungue']=='Wasting') { echo 'checked'; } ?> value="Wasting">
  <label class="form-check-label" for="Wasting ">Wasting</label>
</div>

</div>
    </div>
</div>

<div class="col-md-6">
    <div class="row px-3 ">
    <h5 class=" mt-3">
    Tonsils
</h5>
    <div class="col-md-12">
<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="tonsils" id="Enlargement" <?php if($exam['tonsils']=='nlargement') { echo 'checked'; } ?> value="Enlargement">
  <label class="form-check-label" for="Enlargement">Enlargement</label>
</div>
    <div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="tonsils" id="Normal"<?php if($exam['tonsils']=='Normal') { echo 'checked'; } ?>  value="Normal">
  <label class="form-check-label" for="Normal">Normal</label>
</div>


</div>
    </div>
</div>

</div>



<div class="row px-3">
<div class="col-md-12 px-5 mt-3">
    <h5>Per Abdomen</h5>


</div>


<div class="col-md-6">
    <div class="row px-5">
    <h5 class="px-3 mt-3">
    Inspection
</h5>
    <div class="col-md-12">
<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="shape" id="Normal" <?php if($exam['shape']=='Normal') { echo 'checked'; } ?>  value="Normal">
  <label class="form-check-label" for="Normal">Shape - Normal</label>
</div>
    <div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="shape" id="Abnormal" <?php if($exam['shape']=='Abnormal') { echo 'checked'; } ?>  value="Abnormal">
  <label class="form-check-label" for="Abnormal">Abnormal</label>
</div>


</div>
    </div>
</div>
    

<div class="col-md-6">
    <div class="form-group">
        <label for="">Movements with respiration</label>
        <input type="text" name="g_respiration" id="g_respiration" value="<?php echo $exam['g_respiration']; ?>" class="form-control">
    </div>
</div>


<div class="col-md-6">
    <div class="row px-5">
    <h5 class="px-3 mt-3">
    Umbilicus
</h5>
    <div class="col-md-12">
<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="umbilicus" id="Inverted" <?php if($exam['umbilicus']=='Inverted') { echo 'checked'; } ?> value="Inverted">
  <label class="form-check-label" for="Inverted">Inverted</label>
</div>
    <div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="umbilicus" id="Everted" <?php if($exam['umbilicus']=='Everted') { echo 'checked'; } ?> value="Everted">
  <label class="form-check-label" for="Everted">Everted</label>
</div>


</div>
    </div>
</div>

<div class="col-md-6">
    <div class="row ">
    <h5 class="px-3 mt-3">
    Hernial orifices
</h5>
    <div class="col-md-12">
<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="hernial" id="Epigastric" <?php if($exam['hernial']=='Epigastric') { echo 'checked'; } ?>  value="Epigastric">
  <label class="form-check-label" for="Epigastric">Epigastric</label>
</div>
    <div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="hernial" id="Umbilical" <?php if($exam['hernial']=='Umbilical') { echo 'checked'; } ?>  value="Umbilical">
  <label class="form-check-label" for="Umbilical">Umbilical</label>
</div>
<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="hernial" id="Inguinal" <?php if($exam['hernial']=='Inguinal') { echo 'checked'; } ?>  value="Inguinal">
  <label class="form-check-label" for="Inguinal">Inguinal</label>
</div>


<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="hernial" id="Femoral" <?php if($exam['hernial']=='Femoral') { echo 'checked'; } ?>  value="Femoral">
  <label class="form-check-label" for="Femoral">Femoral</label>
</div>

<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="hernial" id="Incisional" <?php if($exam['hernial']=='Incisional') { echo 'checked'; } ?>  value="Incisional">
  <label class="form-check-label" for="Incisional">Incisional</label>
</div>

</div>
    </div>

    
</div>


<div class="col-md-6">
    <div class="row px-5 ">
    <h5 class=" mt-3">
    Visible Blood Vessels
</h5>
    <div class="col-md-12">
<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="vessels" id="present" <?php if($exam['vessels']=='Present') { echo 'checked'; } ?> value="Present">
  <label class="form-check-label" for="present">Present</label>
</div>
    <div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="vessels" id="absent" <?php if($exam['vessels']=='Absent') { echo 'checked'; } ?> value="Absent">
  <label class="form-check-label" for="absent">Absent</label>
</div>


</div>
    </div>
</div>

<div class="col-md-6">
    <div class="row px-3 ">
    <h5 class=" mt-3">
    Striae
</h5>
    <div class="col-md-12">
<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="striae" id="s_present" <?php if($exam['striae']=='Present') { echo 'checked'; } ?> value="Present">
  <label class="form-check-label" for="s_present">Present</label>
</div>
    <div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="striae" id="s_absent" <?php if($exam['striae']=='Absent') { echo 'checked'; } ?> value="Absent">
  <label class="form-check-label" for="s_absent">Absent</label>
</div>
<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="striae" id="s_others" <?php if($exam['striae']=='Others') { echo 'checked'; } ?> value="Others">
  <label class="form-check-label" for="s_others">Others</label>
</div>


</div>
    </div>
</div>




</div>

<div class="row px-5 mt-3">
    <div class="col-md-12">
        <h5 class="px-1">Palpation</h5>
    </div>
    <div class="col-md-6">
        <div class="from-group">
            <label for="tenderness">Tenderness</label>
            <input type="text" name="p_tenderness"id="p_tenderness" value="<?php echo $exam['p_tenderness']; ?>" class="form-control">
        </div>

    </div>
    <div class="col-md-6">
        <div class="from-group">
            <label for="rigidity">Rigidity</label>
            <input type="text" name="rigidity"id="rigidity" value="<?php echo $exam['rigidity']; ?>" class="form-control">
        </div>

    </div>


</div>


<div class="row px-5 mt-3">
<div class="col-md-12">
<h4 class="">Liver</h4>

</div>
<div class="col-md-3">
    <div class="from-group">
        <label for="">Size</label>
        <input type="text" name="liver_size"  value="<?php echo $exam['liver_size']; ?>"class="form-control">
    </div>
</div>

<div class="col-md-3">
    <div class="from-group">
        <label for="">Tender</label>
        <input type="text" name="liver_tender" value="<?php echo $exam['liver_tender']; ?>" class="form-control">
    </div>
</div>

<div class="col-md-3">
    <div class="from-group">
        <label for="">Margin</label>
        <input type="text" name="liver_margin" value="<?php echo $exam['liver_margin']; ?>" class="form-control">
    </div>
</div>

<div class="col-md-3">
    <div class="from-group">
        <label for="">Consistency</label>
        <input type="text" name="liver_cons" value="<?php echo $exam['liver_cons']; ?>" class="form-control">
    </div>
</div>


</div>




<div class="row px-5 mt-3">
<div class="col-md-12">
<h4 class="">Spleen</h4>

</div>
<div class="col-md-3">
    <div class="from-group">
        <label for="">Size</label>
        <input type="text" name="spleen_size" value="<?php echo $exam['spleen_size']; ?>" class="form-control">
    </div>
</div>

<div class="col-md-3">
    <div class="from-group">
        <label for="">Tender</label>
        <input type="text" name="spleen_tender" value="<?php echo $exam['spleen_tender']; ?>" class="form-control">
    </div>
</div>

<div class="col-md-3">
    <div class="from-group">
        <label for="">Margin</label>
        <input type="text" name="spleen_margin" value="<?php echo $exam['spleen_margin']; ?>" class="form-control">
    </div>
</div>

<div class="col-md-3">
    <div class="from-group">
        <label for="">Consistency</label>
        <input type="text" name="spleen_cons" value="<?php echo $exam['spleen_cons']; ?>" class="form-control">
    </div>
</div>


</div>


<div class="row px-3 mt-4">
<div class="col-md-6">
    
    <div class="row px-5 mt-3">
    <div class="col-md-12">
    <h4 class="">Swelling</h4>
    
    </div>
    <div class="col-md-12">
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="swelling" id="swell_present" <?php if($exam['swelling']=='Present') { echo 'checked'; } ?> value="Present">
      <label class="form-check-label" for="swell_present">Present</label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="swelling" id="swell_absent" <?php if($exam['swelling']=='Absent') { echo 'checked'; } ?> value="Absent">
      <label class="form-check-label" for="swell_absent">Absent</label>
    </div>
    
    </div>
    
    
    </div>
    </div>
    
    
    <div class="col-md-6">
    <div class="row px-5 mt-3">
    <div class="col-md-12">
    <h4 class="">Rebound Tenderness</h4>
    
    </div>
    <div class="col-md-12">
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="rebound" id="rebound_present" <?php if($exam['rebound']=='Present') { echo 'checked'; } ?> value="Present">
      <label class="form-check-label" for="rebound_present">Present</label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="rebound" id="rebound_absent" <?php if($exam['rebound']=='Absent') { echo 'checked'; } ?> value="Absent">
      <label class="form-check-label" for="rebound_absent">Absent</label>
    </div>
    
    </div>
    
    
    </div>
    </div>

    <div class="col-md-6">
    <div class="row px-5 mt-3">
    <div class="col-md-12">
    <h4 class="">Percussion – Free fluid </h4>
    
    </div>
    <div class="col-md-12">
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="precussion" id="precussion_present" <?php if($exam['precussion']=='Present') { echo 'checked'; } ?> value="Present">
      <label class="form-check-label" for="precussion_present">Present</label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="precussion" id="precussion_absent" <?php if($exam['precussion']=='Absent') { echo 'checked'; } ?> value="Absent">
      <label class="form-check-label" for="precussion_absent">Absent</label>
    </div>
    
    </div>
    
    
    </div>
    </div>
</div>


<div class="row px-3 mt-4">
<div class="col-md-12">
    
    <div class="row px-5 mt-3">
    <div class="col-md-12">
    <h4 class="">Dullness Grade</h4>
    
    </div>
    <div class="col-md-3">
      <input class="form-control" type="text" name="dullness_grade1" value=<?php echo $exam['dullness_grade1'];?> id="dullness_grade1" >
        
    </div>
    <div class="col-md-3">
      <input class="form-control" type="text" name="dullness_grade2" value=<?php echo $exam['dullness_grade2'];?> id="dullness_grade2" >
        
    </div>
    <div class="col-md-3">
      <input class="form-control" type="text" name="dullness_grade3" value=<?php echo $exam['dullness_grade3'];?> id="dullness_grade3" >
        
    </div>
    <div class="col-md-3">
  
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="dullness_grade"  <?php if($exam['dullness_grade']=='Present Shifting') { echo 'checked'; } ?>  id="dullness_grade4" value="Present Shifting">
      <label class="form-check-label" for="dullness_grade4">Present Shifting</label>
    </div>
    
    </div>
    
    
    </div>
    </div>
    
    
    <div class="col-md-6">
    <div class="row px-5 mt-3">
    <div class="col-md-12">
    <h4 class="">Auscultation–Peristaltic sounds</h4>
    
    </div>
    <div class="col-md-12">
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="peristalitic" id="ausc_present" <?php if($exam['peristalitic']=='Present') { echo 'checked'; } ?> value="Present">
      <label class="form-check-label" for="ausc_present">Present</label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="peristalitic" id="ausc_absent"<?php if($exam['peristalitic']=='Absent') { echo 'checked'; } ?>  value="Absent">
      <label class="form-check-label" for="ausc_absent">Absent</label>
    </div>
    
    </div>
    
    
    </div>
    </div>

    <div class="col-md-6">
    <div class="row px-5 mt-3">
    <div class="col-md-12">
    <h4 class="">Rubs</h4>
    
    </div>
    <div class="col-md-12">
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="rubs" id="hepetic" <?php if($exam['rubs']=='Hepatic') { echo 'checked'; } ?> value="Hepatic">
      <label class="form-check-label" for="hepetic">Hepatic</label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="rubs" id="Splenic" <?php if($exam['rubs']=='Splenic') { echo 'checked'; } ?> value="Splenic">
      <label class="form-check-label" for="Splenic">Splenic</label>
    </div>
    
    </div>
    
    
    </div>
    </div>

   
</div>

<div class="row px-5">
    <div class="col-md-6">
<h5 class="px-3">Others</h5>
        <div class="form-group px-3">
            <label for="">Per Rectal examination</label>
            <input type="text" class="form-control" value="<?php echo $exam['rectal']; ?>"name="rectal">
            
        </div>
    </div>
</div>


<div class="row px-3">
<div class="col-md-12">
<h4 class="text-primary px-4">2.4 Central Nervous System –</h4>

</div>
</div>



<div class="row px-3 mt-3">

    <div class="col-md-8">
        <div class="row px-5">
            <div class="col-md-12">
                <h4>Mental Status</h4>
               
            </div>
           <div class="col-md-12">
           <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="mental_status" id="Consciousness – Drowsiness" <?php if($exam['mental_status']=='Consciousness – Drowsiness') { echo 'checked'; } ?> value="Consciousness – Drowsiness">
      <label class="form-check-label" for="Consciousness – Drowsiness">Consciousness – Drowsiness</label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="mental_status" id="Stupor" <?php if($exam['mental_status']=='Stupor') { echo 'checked'; } ?> value="Stupor">
      <label class="form-check-label" for="Stupor">Stupor</label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="mental_status" id="Semi-Coma" <?php if($exam['mental_status']=='Semi-Coma') { echo 'checked'; } ?> value="Semi-Coma">
      <label class="form-check-label" for="Semi-Coma">Semi-Coma</label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="mental_status" id="Coma" <?php if($exam['mental_status']=='Coma') { echo 'checked'; } ?> value="Coma">
      <label class="form-check-label" for="Coma">Coma</label>
    </div>
           </div>
        </div>
    </div>

    <div class="col-md-4">
    <div class="row ">
            <div class="col-md-12">
                <h4>Memory </h4>
               
            </div>
            <div class="col-md-12">
            <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="memory" id="Loss"  <?php if($exam['memory']=='Loss') { echo 'checked'; } ?> value="Loss">
      <label class="form-check-label" for="Loss">Loss</label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="memory" id="mental_Normal"  <?php if($exam['memory']=='Normal') { echo 'checked'; } ?> value="Normal">
      <label class="form-check-label" for="mental_Normal">Normal</label>
    </div>
            </div>
   
        </div>

    </div>

    <div class="col-md-6">
    <div class="row px-5 ">
            <div class="col-md-12">
                <h4>Orientation of Time and Place </h4>
               
            </div>
            <div class="col-md-12">
            <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="orientation" id="time_present" <?php if($exam['orientation']=='Present') { echo 'checked'; } ?> value="Present">
      <label class="form-check-label" for="time_present">Present</label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="orientation" id="time_absent" <?php if($exam['orientation']=='Absent') { echo 'checked'; } ?> value="Absent">
      <label class="form-check-label" for="time_absent">Absent</label>
    </div>
            </div>
   
        </div>

    </div>

    <div class="col-md-6">
    <div class="row">
            <div class="col-md-12">
                <h4>Speech </h4>
               
            </div>
            <div class="col-md-12">
            <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="speech" id="Dysarthria" <?php if($exam['speech']=='Dysarthria') { echo 'checked'; } ?> value="Dysarthria">
      <label class="form-check-label" for="Dysarthria">Dysarthria</label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="speech" id="Dysphasia" <?php if($exam['speech']=='"') { echo 'checked'; } ?>  value="Dysphasia">
      <label class="form-check-label" for="Dysphasia">Dysphasia</label>
    </div>
            </div>
   
        </div>

    </div>

    <div class="col-md-6">
    <div class="row px-5 mt-3">
            <div class="col-md-12">
                <h4>General Behavior </h4>
               
            </div>
            <div class="col-md-12">
            <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="behavior" id="behavior_normal" <?php if($exam['behavior']=='Normal') { echo 'checked'; } ?> value="Normal">
      <label class="form-check-label" for="behavior_normal">Normal</label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="behavior" id="behavior_abnormal" <?php if($exam['behavior']=='Abnoraml') { echo 'checked'; } ?> value="Abnoraml">
      <label class="form-check-label" for="behavior_abnormal">Abnoraml</label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="behavior" id="delusions" <?php if($exam['behavior']=='Hallucinations and delusions') { echo 'checked'; } ?> value="Hallucinations and delusions">
      <label class="form-check-label" for="delusions">Hallucinations and delusions</label>
    </div>
            </div>
   
        </div>

    </div>

    <div class="col-md-6">
    <div class="row mt-3">
            <div class="col-md-12">
                <h4>Gait</h4>
               
            </div>
            <div class="col-md-12">
            <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="gait" id="Ataxic gait" <?php if($exam['gait']=='Ataxic gait') { echo 'checked'; } ?> value="Ataxic gait">
      <label class="form-check-label" for="Ataxic gait">Ataxic gait</label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="gait" id="Antalgic gait" <?php if($exam['gait']=='Antalgic gait') { echo 'checked'; } ?> value="Antalgic gait">
      <label class="form-check-label" for="Antalgic gait">Antalgic gait</label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="gait" id=" Cerebellar gait" <?php if($exam['gait']=='Cerebellar gait') { echo 'checked'; } ?> value=" Cerebellar gait">
      <label class="form-check-label" for=" Cerebellar gait"> Cerebellar gait</label>
    </div>
            </div>
   
        </div>

    </div>

    
<div class="col-md-8 px-5">
    <h5 class="mt-3">Cranial Nerve Examination – from 1st to 12thcranial nerve finding details</h5>
    <textarea name="nerve_exam" id="nerve_exam" cols="1" rows="2" class="form-control"><?php echo $exam['nerve_exam']; ?></textarea>

</div>
    

</div>

<div class="row px-2 mt-5">
<div class="col-md-12">
   <h4 class="text-primary px-3">
    Motor system 
    </h4>
   </div>
</div>
<div class="row px-5 mt-3">
 
    <div class="col-md-6">
        <div class="form-group">
            <h5>Nutrition /Muscle bulk</h5>
            <input type="text" class="form-control" value="<?php echo $exam['nutrition']; ?>" name="nutrition" id="nutrition">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <h5>Muscle power / strength score </h5>
            <input type="number" minvalue="1" maxvalue="5" value="<?php echo $exam['muscle_score']; ?>"placeholder="0-5" class="form-control" name="muscle_score" id="muscle_score">
        </div>
    </div>
    
        <div class="col-md-6">
            <h5>Muscle tone</h5>
        <div class="row">
            <div class="col-md-12"> 
                       <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="muscle_tone" id="Hypotonia" <?php if($exam['muscle_tone']=='Hypotonia') { echo 'checked'; } ?> value="Hypotonia ">
      <label class="form-check-label" for="Hypotonia">Hypotonia </label>
    </div>
          <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="muscle_tone" id="Hypertonia" <?php if($exam['muscle_tone']=='Hypertonia') { echo 'checked'; } ?> value="Hypertonia">
      <label class="form-check-label" for="Hypertonia">Hypertonia </label>
    </div>
         <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="muscle_tone" id="Rigidity" <?php if($exam['muscle_tone']=='Rigidity') { echo 'checked'; } ?> value="Rigidity ">
      <label class="form-check-label" for="Rigidity">Rigidity </label>
    </div>
        </div>
        </div>

    </div>
    <div class="col-md-6">
            <h5>Co-ordination</h5>
        <div class="row">
            <div class="col-md-12"> 
                       <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="coordinations" id="Finger nose test" <?php if($exam['coordinations']=='UL-Finger nose test') { echo 'checked'; } ?> value="UL-Finger nose test">
      <label class="form-check-label" for="Finger nose test">UL-Finger nose test </label>
    </div>
          <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="coordinations" id="Finger test" <?php if($exam['coordinations']=='Finger test') { echo 'checked'; } ?> value="Finger test">
      <label class="form-check-label" for="Finger test">Finger test </label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="coordinations" id="knee test" <?php if($exam['coordinations']=='LL- Knee heal test') { echo 'checked'; } ?> value="LL- Knee heal test">
      <label class="form-check-label" for="knee test">LL- Knee heal test</label>
    </div>
         
         
        </div>
        </div>

    </div>
    <div class="col-md-6">
            <h5>Involuntary Movement</h5>
        <div class="row">
            <div class="col-md-12"> 
                       <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="involuntary" id="Fasciculation" <?php if($exam['involuntary']=='UL-FFasciculation') { echo 'checked'; } ?> value="UL-FFasciculation">
      <label class="form-check-label" for="Fasciculation">Fasciculation </label>
    </div>
          <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="involuntary" id="Tremors" <?php if($exam['involuntary']=='Tremors') { echo 'checked'; } ?> value="Tremors">
      <label class="form-check-label" for="Tremors">Tremors </label>
    </div>

    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="involuntary" id="Chorea" <?php if($exam['involuntary']=='Chorea') { echo 'checked'; } ?> value="Chorea">
      <label class="form-check-label" for="Chorea">Chorea </label>
    </div>
   
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="involuntary" id="Athetosis" <?php if($exam['involuntary']=='Athetosis') { echo 'checked'; } ?> value="Athetosis">
      <label class="form-check-label" for="Athetosis">Athetosis </label>
    </div>
         
         
        </div>
        </div>

    </div>
</div>


<div class="row px-5 mt-4">
    <h4 class="text-primary">Sensory System – </h4>
    
</div>

<div class="row px-5 mt-3">
    <div class="col-md-4">
        <div class="form-group">
            <h5>Superficial – Touch</h5>
            <input type="text" name="touch" id="touch"value="<?php echo $exam['touch']; ?>" class="form-control">
        </div>

    </div>
    <div class="col-md-4">
        <div class="form-group">
            <h5>Pain</h5>
            <input type="text" name="pain" id="pain" value="<?php echo $exam['pain']; ?>"class="form-control">
        </div>

    </div>
    <div class="col-md-4">
        <div class="form-group">
            <h5>Temperature</h5>
            <input type="text" name="sup_temp" id="sup_temp" value="<?php echo $exam['sup_temp']; ?>"class="form-control">
        </div>

    </div>
    
</div>
<div class="row px-5 mt-1">
    <div class="col-md-4">
        <div class="form-group">
            <h5>Deep - Crude touch</h5>
            <input type="text" name="crude_touch" id="crude_touch" value="<?php echo $exam['crude_touch']; ?>"class="form-control">
        </div>

    </div>
    <div class="col-md-4">
        <div class="form-group">
            <h5>Vaibration</h5>
            <input type="text" name="vaibration" id="vaibration"value="<?php echo $exam['vibration']; ?>" class="form-control">
        </div>

    </div>
    <div class="col-md-4">
        <div class="form-group">
            <h5>Joint sense</h5>
            <input type="text" name="joint_sense" id="joint_sense" value="<?php echo $exam['joint_sense']; ?>"class="form-control">
        </div>

    </div>
    
</div>

<div class="row px-5 mt-1">
    <div class="col-md-3">
        <div class="form-group">
            <h5>Cortical – Tactile localization</h5>
            <input type="text" name="tactile_l" id="tactile_l"value="<?php echo $exam['tactile_l']; ?>" class="form-control">
        </div>

    </div>

    <div class="col-md-3">
        <div class="form-group">
            <h5>Tactile discrimination</h5>
            <input type="text" name="tactile_d" id="tactile_d"value="<?php echo $exam['tactile_d']; ?>" class="form-control">
        </div>

    </div>
    <div class="col-md-3">
        <div class="form-group">
            <h5>Tactile extinction</h5>
            <input type="text" name="tactile_e" id="tactile_e" value="<?php echo $exam['tactile_e']; ?>"class="form-control">
        </div>

    </div>
    <div class="col-md-3">
        <div class="form-group">
            <h5>Others</h5>
            <input type="text" name="tactile_o" id="tactile_o" value="<?php echo $exam['tactile_o']; ?>"class="form-control">
        </div>

    </div>
   
    
</div>





<div class="row px-5 mt-1">
<div class="col-md-6">
        <div class="row">
        <div class="col-md-12">
            <h5>Meningeal signs</h5>
        <div class="row">
            <div class="col-md-12"> 
       
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="checkbox" name="signs1" id="Neck stiffness"<?php if($exam['signs1']==1) { echo 'checked'; } ?> value="Neck stiffness">
      <label class="form-check-label" for="Neck stiffness">Neck stiffness</label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="checkbox" name="signs2" id="Kernig’s sign"<?php if($exam['signs2']==1) { echo 'checked'; } ?> value="Kernig’s sign">
      <label class="form-check-label" for="Kernig’s sign">Kernig’s sign</label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="checkbox" name="signs3" id="Brudzunski’s sign"<?php if($exam['signs3']==1) { echo 'checked'; } ?> value="Brudzunski’s sign">
      <label class="form-check-label" for="Brudzunski’s sign">Brudzunski’s sign</label>
    </div>
        </div>
        </div>

    </div>
        </div>
    </div>
    
    

    <div class="col-md-6">
        <div class="row">
        <div class="col-md-12">
            <h5>Reflexes / Jerks</h5>
        <div class="row">
            <div class="col-md-12"> 
            <div class="form-check form-check-inline">
      <input class="form-check-input" type="checkbox" name="jerks1" id="Glabellar Tap" <?php if($exam['jerks1']==1) { echo 'checked'; } ?> value="Glabellar Tap">
      <label class="form-check-label" for="Glabellar Tap">Glabellar Tap </label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="checkbox" name="jerks2" id="Corneal and Conjunctival reflex" <?php if($exam['jerks2']==1) { echo 'checked'; } ?> value="Corneal and Conjunctival reflex">
      <label class="form-check-label" for="Corneal and Conjunctival reflex">Corneal and Conjunctival reflex</label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="checkbox" name="jerks3" id="Palatal" <?php if($exam['jerks3']==1) { echo 'checked'; } ?> value="Palatal">
      <label class="form-check-label" for="Palatal">Palatal</label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="checkbox" name="jerks4" id="Pharyngeal" <?php if($exam['jerks4']==1) { echo 'checked'; } ?> value="Pharyngeal">
      <label class="form-check-label" for="Pharyngeal">Pharyngeal</label>
    </div>
        </div>
        </div>

    </div>
        </div>
    </div>
    
    
</div>


<div class="row px-5 mt-1">
    <div class="col-md-12">
        <h5>Abdominal Reflex</h5>
    </div>
    <div class="col-md-6">
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="abdominal" id="Upper" <?php if($exam['abdominal']=='Upper') { echo 'checked'; } ?> value="Upper">
      <label class="form-check-label" for="Upper">Upper </label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="abdominal" id="Middle" <?php if($exam['abdominal']=='Middle') { echo 'checked'; } ?> value="Middle">
      <label class="form-check-label" for="Middle">Middle </label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="abdominal" id="Lower" <?php if($exam['abdominal']=='Lower') { echo 'checked'; } ?> value="Lower">
      <label class="form-check-label" for="Lower">Lower </label>
    </div>

    </div>
    <div class="col-md-6">
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="checkbox" name="plantar" id="Plantar Reflex" <?php if($exam['plantar']==1) { echo 'checked'; } ?> value="Plantar Reflex">
      <label class="form-check-label" for="Plantar Reflex">Plantar Reflex </label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="checkbox" name="jaw_jerk" id="jaw_jerk" <?php if($exam['jaw_jerk']==1) { echo 'checked'; } ?> value="jaw_jerk">
      <label class="form-check-label" for="jaw_jerk">Jaw Jerk </label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="checkbox" name="knee_reflex" id="Knee reflex" <?php if($exam['knee_reflex']==1) { echo 'checked'; } ?> value="Knee reflex">
      <label class="form-check-label" for="Knee reflex">Knee reflex </label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="checkbox" name="ankle_reflex" id="Ankle reflex" <?php if($exam['ankle_reflex']==1) { echo 'checked'; } ?> value="Ankle reflex">
      <label class="form-check-label" for="Ankle reflex">Ankle reflex </label>
    </div>

    </div>

   
     
    
</div>


<div class="row px-5">
    <div class="col-md-12">
        <div class="form-group">
    <h4 class="px-1 text-primary">Other</h4>

            <input type="text" name="any_other" id="any_other" value="<?php echo $exam['any_other']; ?>" class="form-control">
        </div>
    </div>
</div>




<div class="row p-3 ">
    <div class="col-md-12  ">
    <input type="hidden" name="pid" value="<?= $_REQUEST['id']?>">
                                    <input type="hidden" name="edit" value="<?= $edit?>">
        <button class="btn btn-success float-right" name="submit" value="examination">Save</button>
       
        </form>
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
         $.ajax({
            type : 'post',
            url : 'ajax.php', //Here you will fetch records 
            data :  'proce='+val+'&page=proce_medicine', //Pass $id
            success : function(data){
            $('#proce_medicine').html(data);//Show fetched data from database
            }
        });
    }
  function changeTot1(val)
    {
        document.getElementById('total').value= val * document.getElementById('dose').value;
    }
    function changeTot(val)
    {
        document.getElementById('total1').value= val * document.getElementById('dose1').value;
    }
      if(window.location.href.split("#")[1]!=undefined)
        {
            var collection=document.getElementsByClassName(' show active');
       for (let i = 0; i < collection.length; i++) {
        collection[i].classList.remove("active");
            }
       var collection1=document.getElementsByClassName(' active');
       for (let i = 0; i < collection1.length; i++) {
  collection1[i].classList.remove("active");
            }
           var id= window.location.href.split("#")[1];
       document.getElementById(id).className += " active";
        // alert(id);
    var int=id.split("-");
    var flag=0;
    var element='';
    for (let index = 0; index < int.length - 1; index++) {
        if(flag==1)
        {
            element +='-';
        }
         element += int[index];
         flag=1;
        
    }
    // alert(element)
       document.getElementById(element).className += " show active";
        }
         function getDetailOT()
    {
        var val=document.getElementById('ottype').value;
        if(val==1)
        {
            document.getElementById('major_ot').style.display="block";
            document.getElementById('minor_ot').style.display="none";

        }
        else if(val==2)
        {
            document.getElementById('minor_ot').style.display="block";
            document.getElementById('major_ot').style.display="none";

        }
        else{
            document.getElementById('minor_ot').style.display="none";
            document.getElementById('major_ot').style.display="none";

        }
    }
    function getIcdCode(id,type)
    {
        $.ajax({
            type:'post',
             url : 'ajax.php', //Here you will fetch records 
            data :  'icd='+id+'&page=icd_code',
              success : function(data){
           document.getElementById(type).value=data;
            }
        });
    }
</script> 

  

</body>

</html>