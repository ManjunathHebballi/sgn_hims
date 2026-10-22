<?php
require_once(__DIR__ . '/model/session_config.php');
// print_r($_SESSION);
// exit;
date_default_timezone_set("Asia/Kolkata");  

include('model/ipClass.php');
$newIP = new addIP;
$data=$newIP->getIPPatient($_REQUEST['id']);
$row=mysqli_fetch_assoc($data);

$cashlessCoverageType = '';
$cashlessInsuranceName = '';
$cashlessPolicy = isset($row['policy']) ? $row['policy'] : '';
$displayRemark = isset($row['remark']) ? (string)$row['remark'] : '';

if (isset($row['remark']) && stripos((string)$row['remark'], 'Cashless Details - ') !== false) {
    $displayRemark = trim(explode('Cashless Details - ', (string)$row['remark'], 2)[0]);
    $displayRemark = rtrim($displayRemark, " |\t\n\r\0\x0B");
    $remarkParts = explode('Cashless Details - ', (string)$row['remark'], 2);
    $cashlessParts = array_map('trim', explode('|', isset($remarkParts[1]) ? $remarkParts[1] : ''));

    foreach ($cashlessParts as $cashlessPart) {
        if ($cashlessPart === '') {
            continue;
        }

        if (stripos($cashlessPart, 'Policy/Claim:') === 0) {
            $cashlessPolicy = trim(substr($cashlessPart, strlen('Policy/Claim:')));
        } elseif ($cashlessCoverageType === '') {
            $cashlessCoverageType = $cashlessPart;
        } elseif ($cashlessInsuranceName === '') {
            $cashlessInsuranceName = $cashlessPart;
        }
    }
}

ini_set('display_errors',1);
error_reporting(E_ALL);

// exit;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>IP- Registration</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/select2/css/select2.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
        <!-- DataTables -->
    
        <link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/datatables.net-select-bs4/css/select.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        
        <link href="assets/libs/datatables.net-select-bs4/css//select.bootstrap4.min.css" rel="stylesheet" type="text/css" />

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <style>
     #notification {
            position: absolute;
            top: 90px;
           
            right:2px;
            z-index: 99;
        }
        
        
        .bootstrap-select .btn-light{
            color:black!important;
            
        }
</style>


</head>

<body onload="getDetails()">

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
    <div aria-live="polite" id="notification" aria-atomic="true" >
    <?php if(isset($_REQUEST['success']))
{ ?>
    
<div class="alert alert-success solid alert-dismissible fade show">
                                    <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span>
                                    </button>
                                    <strong>Success!</strong>Admitted To Department
                                </div>
                         <?php   }
                            ?>
</div>



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
            <h4 class="text-uppercase">IP Registration Form</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
<form action="controller/processIPRegistration.php" method="POST">

    <div class="row">
        <div class="col-md-3">
           <div class="form-group">
                  <label> OP Register No <span style="color:red">*</span>  </label>
                  <input type="text" class="form-control " onkeyup="patientDetail(this.value)" name="opno" id="opno" <?php if($row['discharge']==1 && $row['is_generated']==0) echo 'readonly';?>  placeholder="Enter OP Register No" value="<?= $row['opd_no']?>">
                  
            </div>
        </div>
         <div class="col-md-6">
</div>
         <div class="col-md-3">
           <div class="form-group">
                  <label>IP Registration Number   </label>
                  <input type="text" class="form-control " name="ipcardno" id="ipcardno" aria-describedby="emailHelp" readonly  placeholder="Enter IP Card No" value="<?= $row['ipd_no']?>">
                  
            </div>
        </div>
        </div>

        
        <div class="row">
         <div class="col-md-3">
            <div class="form-group">
                   <label >Patient Name <span style="color:red">*</span> </label>
                   <input type="text" class="form-control " name="name" id="name" placeholder="Enter Patient Name" <?php //if($row['discharge']==1 && $row['is_generated']==0) echo 'readonly';?> value="<?= $row['name']?>">
             </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                   <label >Age <span style="color:red">*</span> </label>
                   <input type="text" class="form-control " name="age" id="age" placeholder="Enter Age" <?php //if($row['discharge']==1 && $row['is_generated']==0) echo 'readonly';?> value="<?= $row['age']?>">
             </div>
         </div>

         <div class="col-md-3">
            <div class="form-group">
                <label>  Sex <span style="color:red">*</span>  </label>

                <input class="form-control " name="sex" id="sex" <?php //if($row['discharge']==1 && $row['is_generated']==0) echo 'readonly';?> value="<?= $row['gender']?>">
                   
              
            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                   <label >  Address  </label>
                   <textarea class="form-control " id="address" name="address" cols="1" rows="1" placeholder="Enter Patient Address" <?php //if($row['discharge']==1 && $row['is_generated']==0) echo 'readonly';?>><?= $row['address']?></textarea>
             </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                <label>  Religion  </label>

                <input class="form-control " name="religion" id="religion" <?php //if($row['discharge']==1 && $row['is_generated']==0) echo 'readonly';?> value="<?= $row['religion']?>">
                
            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                <label>  Caste  </label>

                <input class="form-control" name="caste" id="caste" <?php //if($row['discharge']==1 && $row['is_generated']==0) echo 'readonly';?> value="<?= $row['caste']?>">
                 
            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                <label>  Category  </label>

                <input type="text" class="form-control " name="category" id="category"  placeholder="Enter Category" value="<?= $row['category']?>">

            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                <label>  Annual Income:  </label>

                <input type="text" class="form-control " name="annual_inc" id="annual_inc"   placeholder="Enter Income" value="<?= $row['annual_income']?>">

            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                <label> Occupation  </label>

                <input type="text" class="form-control " name="occupation" id="occupation"   placeholder="Enter Category" value="<?= $row['occupation']?>">

            </div>
         </div>

         <div class="col-md-3">
            <div class="form-group">
                <label> CMO <span style="color:red">*</span>  </label>

                <select class="form-control " name="cmo" id="cmo" required >
                    
                    <option value="CMO">CMO</option>
                </select>
            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                <label>  Department <span style="color:red">*</span>  </label>

                <select class="form-control " name="dept" id="dept" onchange="getdoctor(this.value)" required>
                    <option value="">Select Department</option>
                    <?php $dept=$newIP->selectDept(); 
                    while ($res=mysqli_fetch_assoc($dept))
                    {?>
                    <option value="<?= $res['id']?>" <?php if($res['id']==$row['dept_id']){ echo 'selected';}?>><?= $res['dept_name']?></option>
                    <?php } ?>
                </select>
            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                <label>  Doctor Name <span style="color:red">*</span>  </label>

                <select class="form-control text-uppercase select2search" name="drname" id="drname" required >
                    <option value="">Select Doctors</option>
                    <?php $doctor=$newIP->selectDoctor(); 
                    while ($res=mysqli_fetch_assoc($doctor))
                    {?>
                    <option value="<?= $res['name'].' '.$res['last_name']?>" <?php if($res['name'].' '.$res['last_name']==$row['doctor']) { echo 'selected'; }?>><?= $res['name'].' '.$res['last_name']?></option>
                    <?php } ?>
                </select>
            </div>
         </div>

        
         
         <div class="col-md-3">
            <div class="form-group">
                <label>  Admission Date  </label>
                <input class="form-control"  name="admdate" id="admdate" type="datetime-local"  value="<?php echo date("Y-m-d\TH:i:s",strtotime($row['admission_date']))?>" readonly>

               
            </div>
         </div>

         

         <div class="col-md-3">
            <div class="form-group">
                <label>  Father Name  </label>

                <input type="text" class="form-control " name="fname" id="fname" placeholder="Enter Patient Ocupation" value="<?= $row['parent_name']?>">

            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                <label> Contact Number   </label>

                <input type="text" class="form-control " name="mobile" id="mobile" placeholder="Enter Patient Contact" value="<?= $row['mobile']?>">

            </div>
         </div>

         <div class="col-md-3">
            <div class="form-group">
                <label >  MLC  </label>

                <select class="form-control  " name="mlc" id="mlc" required>
                <option value="0" <?= $row['mlc']==0 ? 'selected' : '' ?>>No</option>
                    <option value="1" <?= $row['mlc']==1 ? 'selected' : '' ?>>Yes</option>
                    
                 
                </select>
            </div>
         </div>
         

         <div class="col-md-3">
            <div class="form-group">
                <label>  Ward <span style="color:red">*</span>  </label>

                <select class="form-control " name="ward" id="ward" onchange="getBed(this.value)" required>
                    <option value="">Select Word</option>
                    <?php $ward=$newIP->selectWard(); 
                    while ($res=mysqli_fetch_assoc($ward))
                    {?>
                    <option value="<?= $res['ward']?>" <?php if($res['ward']==$row['ward']) { echo 'selected'; } ?>><?= $res['ward']?></option>
                    <?php } ?>

                 
                </select>
            </div>
         </div>
         

                
         <div class="col-md-3">
            <div class="form-group">
                <label> Bed No <span style="color:red">*</span>  </label>

                <select class="form-control " name="bed" id="bed" required>
                    <option value="">Select Bed No</option>
                   
                    <option value="<?= $row['bed_id']?>" selected>BED <?= $row['bed_id']?></option>
                 
                </select>
            </div>
         </div>
          <div class="col-md-3">
            <div class="form-group">
                   <label >Patient Type <span style="color: red">*</span></label>
                   <select name="ptype" id="ptype" class="form-control text-uppercase" onchange="toggleCashlessFields(this.value)">
                      <?php $patientTypeData=$newIP->getPatientType();
                        while($patientTypeRow=mysqli_fetch_assoc($patientTypeData))
                        { ?>
                            <option value="<?= $patientTypeRow['patient_type']?>" <?= $patientTypeRow['patient_type']==$row['patient_type'] ? 'selected' : '' ?>><?= $patientTypeRow['patient_type']?></option>
                        <?php } ?>
                   </select>
             </div>
         </div>

         <div class="col-md-3 cashless-section" id="coverage_type_group" style="display:none;">
            <div class="form-group">
                   <label>Coverage Type</label>
                   <select name="coverage_type" id="coverage_type" class="form-control text-uppercase" onchange="updateCashlessProviderLabel()">
                        <option value="">Select Coverage Type</option>
                        <option value="Insurance" <?= $cashlessCoverageType == 'Insurance' ? 'selected' : '' ?>>Insurance</option>
                        <option value="TPA" <?= $cashlessCoverageType == 'TPA' ? 'selected' : '' ?>>TPA</option>
                   </select>
             </div>
         </div>

         <div class="col-md-3 cashless-section" id="insurance_name_group" style="display:none;">
            <div class="form-group">
                   <label id="insurance_name_label">Insurance / TPA Name</label>
                   <select name="insurance_name" id="insurance_name" class="form-control text-uppercase select2search">
                        <option value="">Select Insurance / TPA Name</option>
                        <?php $tpaList=$newIP->getTPAList();
                        while($tpaRow=mysqli_fetch_assoc($tpaList))
                        { ?>
                            <option value="<?= htmlspecialchars($tpaRow['name'], ENT_QUOTES, 'UTF-8')?>" <?= $cashlessInsuranceName == $tpaRow['name'] ? 'selected' : '' ?>><?= $tpaRow['name']?></option>
                        <?php } ?>
                   </select>
             </div>
         </div>

         <div class="col-md-3 cashless-section" id="policy_group" style="display:none;">
            <div class="form-group">
                   <label>Claim No / Ins ID Card No / Policy No</label>
                   <input type="text" class="form-control text-uppercase" name="policy" id="policy" placeholder="Enter Claim No / Insurance ID / Policy No" value="<?= htmlspecialchars($cashlessPolicy, ENT_QUOTES, 'UTF-8') ?>">
             </div>
         </div>

         <div class="col-md-3">
            <div class="form-group">
                   <label>  Diagnosis   </label>
                   <select class="form-control selectpicker" multiple data-live-search="true" data-live-search-style="contains" id="diagnosis" width='80%' requierd>
                   <?php $icd=$newIP->getIcdCodes(); 
                    while ($res1=mysqli_fetch_assoc($icd))
                    {?>
                    <option value="<?= $res1['code']." - ".$res1['details']?>"><?= $res1['details']." - ".$res1['code']?></option>
                    <?php } ?>
                    </select>
             </div>
         </div>
         
         <div class="col-md-3">
            <div class="form-group">
                <label>  Refered by </label>
                <input class="form-control" type="text"  name="referby" id="referby"  value="<?= $row['refer_by']?>">

               
            </div>
         </div>
           <div class="col-md-3">
            <div class="form-group">
                   <label > Present Diagnosis  </label>
                   <textarea class="form-control "  cols="1" rows="3" name="diagnosis[]" readonly id="presentDiagnosis"><?= $row['diagnosis']?></textarea>
             </div>
         </div>
         
         
         
         <div class="col-md-3">
            <div class="form-group">
                <label> Remark </label>
                <input class="form-control" type="text"  name="remark" id="remark"  value="<?= htmlspecialchars($displayRemark, ENT_QUOTES, 'UTF-8') ?>">

               
            </div>
         </div>
  <!--       <div class="col-md-3">-->
  <!--          <div class="form-group">-->
  <!--              <label> Advance Amount <span class="text-danger">*</span> </label>-->
  <!--              <div class="input-group mb-3">-->
  <!--<div class="input-group-prepend">-->
  <!--  <span class="input-group-text" id="basic-addon1">Rs.</span>-->
  <!--</div>-->
  <input class="form-control" type="hidden"  name="editid" required id="editid"  value=<?= $_REQUEST['id']?>>
            <input type="hidden" name="from" value="<?=$_REQUEST['from']?>">
         <input type="hidden" name="to" value="<?=$_REQUEST['to']?>">
         <input type="hidden" name="search" value="<?=$_REQUEST['search']?>">
<!--</div>-->
             

               
            </div>
         </div>
         
</div>
<div class="row">
         <div class="col-md-12">
          

            <button type="submit" class="btn btn-primary btn-lg float-right" name="submit">Save</button>
            
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
       
   
   
    <!-- Required datatable js -->
    <script src="assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
        <!-- Buttons examples -->
        <script src="assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
        <script src="assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
        <script src="assets/libs/jszip/jszip.min.js"></script>
        <script src="assets/libs/pdfmake/build/pdfmake.min.js"></script>
        <script src="assets/libs/pdfmake/build/vfs_fonts.js"></script>
        <script src="assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
        <script src="assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
        <script src="assets/libs/datatables.net-buttons/js/buttons.colVis.min.js"></script>

        <script src="assets/libs/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
        <script src="assets/libs/datatables.net-select/js/dataTables.select.min.js"></script>
        

        <!-- Datatable init js -->
        <script src="assets/js/pages/datatables.init.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
    <script src="./vendor/select2/js/select2.full.min.js"></script>
    <script src="./js/plugins-init/select2-init.js"></script>


       
 <script>
    $(document).ready(function() {
            $('#datatable-buttons2').DataTable({

                dom: 'Bfrtip',
                responsive: true,
                pageLength: 25,
                // lengthMenu: [0, 5, 10, 20, 50, 100, 200, 500],
                
                buttons: [
            {
                extend: 'excelHtml5',
                title: 'OP Report'
            },
            {
                extend: 'pdfHtml5',
                title: 'OP Report',
               
                orientation: 'landscape',
                pageSize: 'LEGAL'
            }
        ]

            });
        });
</script>
 <script>
    $(document).ready(function() {
            $('#datatable-buttons3').DataTable({

                dom: 'Bfrtip',
                responsive: true,
                pageLength: 25,
                // lengthMenu: [0, 5, 10, 20, 50, 100, 200, 500],
                
                buttons: [
            {
                extend: 'excelHtml5',
                title: 'Department wise OP Report'
            },
            {
                extend: 'pdfHtml5',
                title: 'Department wise OP Report',
                 orientation: 'landscape',
                pageSize: 'LEGAL'
            }
        ]

            });
        });
</script>
<script>
    function patientDetail(op)
    {
     $.ajax({
      url: 'ajax.php',
        type: 'POST',
        data: 'op_no='+op+'&page=ipreg',
        async: false,
        success: function(s){
            json_events = s.split("|");
            $("#name").val(json_events[3]);
            $("#age").val(json_events[4]);
            $("#sex").val(json_events[5]);
            $("#address").val(json_events[7]);
            $("#religion").val(json_events[13]);
            $("#caste").val(json_events[14]);
            $("#category").val(json_events[15]);
            $("#dept").val(json_events[9]);
            $("#annual_inc").val(json_events[11]);
            $("#occupation").val(json_events[19]);
            $("#mobile").val(json_events[6]);
            $("#fname").val(json_events[16]);
            $("#ptype").val(json_events[33]);
            $("#policy").val(json_events[30]);
            $("#mlc").val(json_events[35]);
            $("#ipcardno").val(json_events[38]);
            toggleCashlessFields(json_events[33]);
          }
        });
  }

function toggleCashlessFields(value)
    {
        var isCashless = ((value || '') + '').toUpperCase().indexOf('CASHLESS') !== -1;
        $('.cashless-section').toggle(isCashless);
        $('#coverage_type, #insurance_name, #policy').prop('required', isCashless);
        updateCashlessProviderLabel();
    }

    function updateCashlessProviderLabel()
    {
        var selectedType = ($('#coverage_type').val() || '').toUpperCase();
        var label = 'Insurance / TPA Name';

        if(selectedType === 'INSURANCE')
        {
            label = 'Insurance Name';
        }
        else if(selectedType === 'TPA')
        {
            label = 'TPA Name';
        }

        $('#insurance_name_label').text(label);
    }

  window.setTimeout(function() {
    $(".alert").fadeTo(500, 0).slideUp(500, function(){
        $(this).remove(); 
    });
}, 4000);

function getdoctor(val)
    {
         $.ajax({
      url: 'ajax.php',
        type: 'POST',
        data: 'dept='+val+'&page=doctor1',
        async: false,
        success: function(s){
          $('#drname').html(s);
        }
         });
    
          $.ajax({
      url: 'ajax.php',
        type: 'POST',
        data: 'dept='+val+'&page=ward',
        async: false,
        success: function(s){
           $('#ward').html(s);
        }
         });
    }
    function getBed(val)
    {
        dept=document.getElementById('dept').value;
             $.ajax({
          url: 'ajax.php',
            type: 'POST',
            data: 'dept='+dept+'&ward='+val+'&page=bed',
            async: false,
            success: function(s){
               $('#bed').html(s);
            }
         });
    }
  function getDetails()
    {
       dept=document.getElementById('dept').value;
       drname=document.getElementById('drname').value;
       ward=document.getElementById('ward').value;
       bed=document.getElementById('bed').value;
         $.ajax({
      url: 'ajax.php',
        type: 'POST',
        data: 'dept='+dept+'&page=doctor1',
        async: false,
        success: function(s){
          $('#drname').html(s);
          document.getElementById("drname").value = drname;
        }
         });
    
          $.ajax({
      url: 'ajax.php',
        type: 'POST',
        data: 'dept='+dept+'&page=ward',
        async: false,
        success: function(s){
           $('#ward').html(s);
           document.getElementById("ward").value = ward;
        }
         });
         
          $.ajax({
          url: 'ajax.php',
            type: 'POST',
            data: 'dept='+dept+'&ward='+ward+'&page=bed',
            async: false,
            success: function(s){
                s=s.concat("<option value='"+bed+"'>BED "+bed+"</option>")
              $('#bed').html(s);
              document.getElementById("bed").value = bed;
            }
         });
    }

       $(document).ready(function() {
    $('.select2search').select2();
     toggleCashlessFields($('#ptype').val());
     updateCashlessProviderLabel();
});
</script>
  
<script>
$(document).ready(function(){
    $('#diagnosis').on('changed.bs.select', function () {
        // Get selected values
        var selected = $(this).val();

        // Join with newline or comma
        var text = selected ? selected.join("\n") : '';

        // Set into textarea
        $('#presentDiagnosis').val(text);
    });
});
</script>
</body>

</html>