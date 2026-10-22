<?php date_default_timezone_set("Asia/Kolkata");  
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata"); 
include('model/dischargeClass.php');
$newDischarge = new discharge;
if(isset($_REQUEST['id']))
{
    $data=$newDischarge->getDischargeList($_REQUEST['id']);
    $row=mysqli_fetch_assoc($data);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Discharge</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
        <link href="assets/libs/datatables.net-select-bs4/css//select.bootstrap4.min.css" rel="stylesheet" type="text/css" />

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.css" rel="stylesheet">
<style>
 .bootstrap-select .btn-light{
            color:black!important;
            
        }
    .bootstrap-select .dropdown-menu{
  width: 100% !important;
}

.bootstrap-select .dropdown-item {
  white-space: normal !important;
  word-wrap: break-word !important;
  display: block !important;
  max-height: 60px;
  overflow-y: auto;
}

.note-editor .note-editable {
    min-height: 120px;
    text-transform: none !important;
}

.note-editor {
    border-color: #dee2e6;
}

.discharge-note-block {
    margin-top: 10px;
    padding-top: 12px;
    border-top: 1px dashed #dfe4ea;
}

.discharge-note-first {
    margin-top: 0;
    padding-top: 0;
    border-top: 0;
}

.discharge-note-block .form-group {
    margin-bottom: 0;
}
</style>

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
            <h4 class="text-uppercase">Discharge</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
<form action="controller/processDischarge.php" method="POST" enctype="multipart/form-data">

    <div class="row">
                <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1"> Patient ID  <span style="color:red">*</span></label>
                   <input type="text" class="form-control " name="patinetid" id="patinetid" aria-describedby="emailHelp" readonly placeholder="Enter card No" value="<?= $row['patient_id']?>">
                   
             </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputEmail1">  Refernce No <span style="color:red">*</span></label>

                <input type="text" class="form-control" name="refno" id="refno" aria-describedby="emailHelp"  placeholder="Enter Card Number" value="<?= $row['ref_no']?>">

            </div>
         </div>

         <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1">Patient Name<span style="color:red">*</span></label>
                   <input type="text" class="form-control " name="fname" id="fname" placeholder="Enter Patient Name"  readonly value="<?= $row['patient_name']?>">
             </div>
         </div>




        <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1">Age<span style="color:red">*</span></label>
                   <input type="text" class="form-control " name="age" id="age" placeholder="Enter Age"  readonly value="<?= $row['age']?>">
             </div>
         </div>

         <div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputEmail1">  Sex <span style="color:red">*</span></label>
                  <input type="text" class="form-control " name="sex" id="sex" placeholder="Enter Sex"  readonly value="<?= $row['sex']?>">
            </div>
         </div>
         <div class="col-md-3">
           <div class="form-group">
                  <label for="exampleInputEmail1"> Admission Date <span style="color:red">*</span></label>
                  <input class="form-control" type="date"  name="admidate" id="admidate"  value="<?= date("Y-m-d",strtotime($row['admission_date']))?>"  readonly>
                  
            </div>
        </div>
        <div class="col-md-3">
           <div class="form-group">
                  <label for="exampleInputEmail1"> Discharge Date <span style="color:red">*</span></label>
                  <input class="form-control" type="datetime-local" name="disdate" id="disdate" value="<?php echo date('Y-m-d\TH:i:s',strtotime($row['discharge_date']))?>"  >
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputEmail1"> Diagnosis </label>
 <select class="form-control selectpicker" multiple data-live-search="true" data-live-search-style="contains" id="diagnosis1" requierd>
                   <?php $icd=$newDischarge->getIcdCodes(); 
                    while ($res1=mysqli_fetch_assoc($icd))
                    {?>
                    <option value="<?= $res1['code']." - ".$res1['details']?>"><?= $res1['details']." - ".$res1['code']?></option>
                    <?php } ?>
                    </select>
                    </div>
</div>
        <div class="col-md-12 discharge-note-block discharge-note-first">
            <div class="form-group">
                <label for="exampleInputEmail1">Present Diagnosis </label>

                <textarea class="form-control rich-editor" name="diagnosis" id="diagnosis" cols="1" rows="1"><?= htmlspecialchars($row['diagnosis'], ENT_QUOTES)?></textarea>
</div>
</div>
<div class="col-md-12 discharge-note-block">
            <div class="form-group">
                <label for="exampleInputEmail1"> Complaints On Reporting </label>
                <textarea class="form-control rich-editor" name="complaint" id="complaint" cols="1" rows="1"><?= htmlspecialchars($row['complaints'], ENT_QUOTES)?></textarea>
</div>
</div>

<div class="col-md-12 discharge-note-block">
            <div class="form-group">
                <label for="exampleInputEmail1"> History of Presenting Illness </label>
                <textarea class="form-control rich-editor" name="illness" id="illness" cols="1" rows="1"><?= htmlspecialchars($row['history'], ENT_QUOTES)?></textarea>
</div>
</div>


<div class="col-md-12 discharge-note-block">
            <div class="form-group">
                <label for="exampleInputEmail1"> Past Medical / Surgical History </label>
                <textarea class="form-control rich-editor" name="past" id="past" cols="1" rows="1"><?= htmlspecialchars($row['past_history'], ENT_QUOTES)?></textarea>
</div>
</div>


<div class="col-md-12 discharge-note-block">
            <div class="form-group">
                <label for="exampleInputEmail1"> On Examination </label>
                <textarea class="form-control rich-editor" name="examination" id="examination" cols="1" rows="1"><?= htmlspecialchars($row['on_examination'], ENT_QUOTES)?></textarea>
</div>
</div>






<div class="col-md-12 discharge-note-block">
            <div class="form-group">
                <label for="exampleInputEmail1"> Investigations </label>
                <textarea class="form-control rich-editor" name="investigations" id="investigations" cols="1" rows="1"><?= htmlspecialchars($row['investigation'], ENT_QUOTES)?></textarea>
</div>
</div>
<div class="col-md-12 discharge-note-block">
            <div class="form-group">
                <label for="exampleInputEmail1"> Course Of Treatment in Hospital </label>
                <textarea class="form-control rich-editor" name="course" id="course" cols="1" rows="1"><?= htmlspecialchars($row['course_treatment'], ENT_QUOTES)?></textarea>
</div>
</div>
<div class="col-md-12 discharge-note-block">
            <div class="form-group">
                <label for="exampleInputEmail1"> Advice On Discharge </label>
                <textarea class="form-control rich-editor" name="advice" id="advice" cols="1" rows="1"><?= htmlspecialchars($row['advice_discharge'], ENT_QUOTES)?></textarea>
</div>
</div>
<div class="col-md-12 discharge-note-block">
            <div class="form-group">
                <label for="exampleInputEmail1"> Physical Examination </label>
                <textarea class="form-control rich-editor" name="exam" id="exam" cols="1" rows="1"><?= htmlspecialchars($row['physical_examination'], ENT_QUOTES)?></textarea>
</div>
</div>



<div class="col-md-12 discharge-note-block">
            <div class="form-group">
                <label for="exampleInputEmail1"> Review On </label>
                <textarea class="form-control rich-editor" name="review" id="review" cols="1" rows="1"><?= htmlspecialchars($row['review_on'], ENT_QUOTES)?></textarea>
</div>
</div>

<div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputEmail1"> Discharge Condition </label>
                <select name="dis_condtion" class="form-control">
                    <option>Select Discharge Condition</option>
                    <option value="Routine Discharge" <?php if($row['discharge_condition']=='Routine Discharge') echo 'selected';?>>Routine Discharge</option>
                    <option value="DAMA (Discharge Against Medical Advice)" <?php if($row['discharge_condition']=='DAMA (Discharge Against Medical Advice)') echo 'selected';?>>DAMA (Discharge Against Medical Advice)</option>
                    <option value="DAOR (Discharge at own Risk)" <?php if($row['discharge_condition']=='DAOR (Discharge at own Risk)') echo 'selected';?>>DAOR (Discharge at own Risk)</option>
                    <option value="TRANSFER (Transfer to other unit or hospital)" <?php if($row['discharge_condition']=='TRANSFER (Transfer to other unit or hospital)') echo 'selected';?>>TRANSFER (Transfer to other unit or hospital)</option>
                    <option value="ABSCOND (Abscond from the Hospital)" <?php if($row['discharge_condition']=='ABSCOND (Abscond from the Hospital)') echo 'selected';?>>ABSCOND (Abscond from the Hospital)</option>
                    <option value="DEATH" <?php if($row['discharge_condition']=='DEATH') echo 'selected';?>>DEATH</option>
                </select>
</div>
</div>


<div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputEmail1">Discharge Summary File</label>
                <input type='file' class="form-control text-uppercase " name="file" id="file" accept="application/pdf">
</div>

         
</div>
         </div>
<div class="row">
         <div class="col-md-12">
<input type="hidden" name="editid" id="editid" value="<?= $_REQUEST['id']?>">          
<input type="hidden" name="deptid" id="deptid" value="<?= $row['dept_id']?>">
            <button type="submit" class="btn btn-primary btn-lg float-right" name="submit">Report</button>
            
         </div>
  
    </div>
    </div>
</form>
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
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.js"></script>

 <script src="./vendor/select2/js/select2.full.min.js"></script>
    <script src="./js/plugins-init/select2-init.js"></script>
    <script>
    function setFieldValue(fieldId, value)
    {
        if ($('#'+fieldId).next('.note-editor').length) {
            $('#'+fieldId).summernote('code', value || '');
        } else {
            $('#'+fieldId).val(value || '');
        }
    }

    function patientDetail(ip)
    {
        // alert(ip)
     $.ajax({
      url: 'ajax.php',
        type: 'POST', // Send post data
        data: 'ip_no='+ip+'&page=otreg',
        async: false,
        success: function(s){
            // alert(s);
            json_events = s.split("|");
            $("#refno").val(json_events[3]);
            $("#fname").val(json_events[5]);
            $("#age").val(json_events[6]);
            $("#sex").val(json_events[7]);
                        setFieldValue('diagnosis', json_events[22]);
            $("#admidate").val(json_events[4]);
            $("#deptid").val(json_events[11]);
            $("#primary_doctor").val(json_events[18]);
           
          }
        });
  }

    $(document).ready(function(){
        $('.rich-editor').summernote({
                height: 130,
                toolbar: [
                        ['style', ['bold', 'italic', 'underline', 'clear']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['insert', ['link']],
                        ['view', ['codeview']]
                ]
        });

        $('#diagnosis1').on('changed.bs.select', function () {
                var selected = $(this).val();
                var text = selected ? selected.join("<br>") : '';
                setFieldValue('diagnosis', text);
        });
    });
  </script>
 

</body>

</html>