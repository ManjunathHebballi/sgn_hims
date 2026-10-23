<?php
require_once(__DIR__ . '/model/session_config.php');
// print_r($_SESSION);
// exit;
date_default_timezone_set("Asia/Kolkata");  

include('model/ipClass.php');
$newIP = new addIP;

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

<body onload="patientDetail('<?= $_REQUEST['opd']?>')">

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
<form action="controller/processIPRegistration.php" method="POST" onsubmit="return validateMyForm();">

    <div class="row">
        <div class="col-md-3">
           <div class="form-group">
                  <label> OP Register No <span style="color:red">*</span>  </label>
                  <input type="text" class="form-control text-uppercase " name="opno" id="opno" onkeyup="javascript:patientDetail(this.value)" value="<?= $_REQUEST['opd']?>" required placeholder="Enter OP Register No">
                  
            </div>
        
         
          <input type='hidden' class="form-control text-uppercase " name="ipcardno" id="ipcardno" aria-describedby="emailHelp"  placeholder="Enter IP Card No">
                  
                
            </div> 
         <div class="col-md-3">
            <div class="form-group">
                   <label >Patient Name <span style="color:red">*</span> </label>
                   <input type="text" class="form-control text-uppercase " name="name" id="name" placeholder="Enter Patient Name" required >
             </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                   <label >Age <span style="color:red">*</span> </label>
                   <input type="text" class="form-control text-uppercase " name="age" id="age" placeholder="Enter Age" reauired >
             </div>
         </div>

         <div class="col-md-3">
            <div class="form-group">
                <label>  Sex <span style="color:red">*</span>  </label>

                <select class="form-control text-uppercase " name="sex" id="sex" required>
                    <option value="">Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                   <label >  Address  </label>
                   <textarea class="form-control text-uppercase " id="address" name="address" cols="1" rows="1" placeholder="Enter Patient Address" ></textarea>
             </div>
         </div>
        

                <input type='hidden' class="form-control text-uppercase " name="religion" id="religion">
                <input type='hidden' class="form-control text-uppercase" name="caste" id="caste">
                <input type='hidden' class="form-control text-uppercase " name="category" id="category"  placeholder="Enter Category">
                <input type='hidden' class="form-control text-uppercase " name="annual_inc" id="annual_inc"   placeholder="Enter Category">
                <input type='hidden' class="form-control text-uppercase " name="occupation" id="occupation"   placeholder="Enter Category">
                <input type='hidden' class="form-control text-uppercase " name="cmo" id="cmo" value="CMO" required >
             
         <div class="col-md-3">
            <div class="form-group">
                <label>  Department <span style="color:red">*</span>  </label>

                <select class="form-control text-uppercase " name="dept" id="dept" onchange="getdoctor(this.value)" required>
                    <option value="">Select Department</option>
                    <?php $dept=$newIP->selectDept(); 
                    while ($res=mysqli_fetch_assoc($dept))
                    {?>
                    <option value="<?= $res['id']?>"><?= $res['dept_name']?></option>
                    <?php } ?>
                </select>
            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                <label>  Doctor Name  <span style="color:red">*</span></label>

                <select class="form-control text-uppercase select2search " name="drname"  id="drname"  required>
                    <option value="">Select Doctors</option>
                    <?php $doctor=$newIP->selectDoctor(); 
                    while ($res=mysqli_fetch_assoc($doctor))
                    {?>
                    <option value="<?= $res['name'].' '.$res['last_name']?>"><?= $res['name'].' '.$res['last_name']?></option>
                    <?php } ?>
                </select>
            </div>
         </div>

        
         
         <div class="col-md-3">
            <div class="form-group">
                <label>  Admission Date  </label>
                <input class="form-control text-uppercase"  name="admdate" id="admdate" type="datetime-local" <?php if($_SESSION['role'] !='Admin') echo ''; ?> value="<?php echo date('Y-m-d\TH:i')?>"  >

               
            </div>
         </div>

         

         <div class="col-md-3">
            <div class="form-group">
                <label>  Guradian Name  </label>

                <input type="text" class="form-control text-uppercase " name="fname" id="fname" placeholder="Enter Guradian Name">

            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                <label> Contact Number <span style="color:red">*</span>  </label>

                <input type="text" class="form-control text-uppercase " name="mobile" id="mobile" placeholder="Enter Patient Contact" maxlength="10" minlength="10" pattern="[0-9]{10}" oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,10)" required>

            </div>
         </div>

         <div class="col-md-3">
            <div class="form-group">
                <label >  MLC  </label>

                <select class="form-control text-uppercase  " name="mlc" id="mlc" required>
                <option value="0">No</option>
                    <option value="1">Yes</option>
                    
                 
                </select>
            </div>
         </div>
         

         <div class="col-md-3">
            <div class="form-group">
                <label>  Ward <span style="color:red">*</span>  </label>

                <select class="form-control text-uppercase " name="ward" id="ward" onchange="getBed(this.value)" required>
                    <option value="">Select Word</option>
                    <?php $ward=$newIP->selectWard(); 
                    while ($res=mysqli_fetch_assoc($ward))
                    {?>
                    <option value="<?= $res['ward']?>" <?php if($res['ward']=='General Ward') echo 'selected';?>><?= $res['ward']?></option>
                    <?php } ?>

                 
                </select>
            </div>
         </div>
         

                
         <div class="col-md-3">
            <div class="form-group">
                <label> Bed No <span style="color:red">*</span>  </label>

                <select class="form-control text-uppercase " name="bed" id="bed" required>
                    <option value="">Select Bed No</option>
                    <?php $bed=$newIP->selectBed(); 
                    while ($res=mysqli_fetch_assoc($bed))
                    {?>
                    <option value="<?= $res['id']?>"><?= $res['bed_number']?></option>
                    <?php } ?>

                 
                </select>
            </div>
         </div>
          <div class="col-md-3">
            <div class="form-group">
                   <label >Patient Type <span style="color: red">*</span></label>
                   <select name="ptype" id="ptype" class="form-control text-uppercase" onchange="toggleCashlessFields(this.value)">
                      <?php $data=$newIP->getPatientType();
                        while($row=mysqli_fetch_assoc($data))
                        {   
                            ?>
                            <option value="<?= $row['patient_type']?>"><?= $row['patient_type']?></option>  
                        <?php } ?>
                   </select>
             </div>
         </div>

         <div class="col-md-3 cashless-section" id="coverage_type_group" style="display:none;">
            <div class="form-group">
                   <label>Coverage Type</label>
                   <select name="coverage_type" id="coverage_type" class="form-control text-uppercase" onchange="updateCashlessProviderLabel()">
                        <option value="">Select Coverage Type</option>
                        <option value="Insurance">Insurance</option>
                        <option value="TPA">TPA</option>
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
                            <option value="<?= htmlspecialchars($tpaRow['name'], ENT_QUOTES, 'UTF-8')?>"><?= $tpaRow['name']?></option>
                        <?php } ?>
                   </select>
             </div>
         </div>

         <div class="col-md-3 cashless-section" id="policy_group" style="display:none;">
            <div class="form-group">
                   <label>Claim No / Ins ID Card No / Policy No</label>
                   <input type="text" class="form-control text-uppercase" name="policy" id="policy" placeholder="Enter Claim No / Insurance ID / Policy No">
             </div>
         </div>
         
         <div class="col-md-3">
            <div class="form-group">
                   <label>  Diagnosis   </label>
                   <select class="form-control text-uppercase selectpicker" multiple data-live-search="true" data-live-search-style="contains" id="diagnosis" name="diagnosis[]" >
                   <?php $icd=$newIP->getIcdCodes(); 
                    while ($res1=mysqli_fetch_assoc($icd))
                    {?>
                    <option value="<?= $res1['code']." - ".$res1['details']?>"><?= $res1['data']." - ".$res1['code']?></option>
                    <?php } ?>
                    </select>
             </div>
         </div>
         
         <div class="col-md-3">
            <div class="form-group">
                <label>  Refered by </label>
                <input class="form-control text-uppercase" type="text"  name="referby" id="referby"  >

               
            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                <label> Remark </label>
                <input class="form-control text-uppercase" type="text"  name="remark" id="remark"  >

               
            </div>
         </div>
  <!--       <div class="col-md-3">-->
  <!--          <div class="form-group">-->
  <!--              <label> Advance Amount <span class="text-danger">*</span> </label>-->
  <!--              <div class="input-group mb-3">-->
  <!--<div class="input-group-prepend">-->
  <!--  <span class="input-group-text" id="basic-addon1">Rs.</span>-->
  <!--</div>-->
  <input class="form-control text-uppercase" type="hidden"  name="advance" required id="advance"  >
  
  <input class="form-control text-uppercase" type="hidden"  name="is_generated" required id="is_generated"  >
<!--</div>-->
             

               
            </div>
         </div>
         

<div class="row">
         <div class="col-md-12">
          <input type="hidden" name="ipd" id="ipd" value="">

            <button type="submit" class="btn btn-primary btn-lg float-right" name="submit"  id="submit">Save</button>
            
         </div>
  
    </div>
</form>
                </div>
  </div>          </div>
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
async function patientDetail(op) {
  if (op.length > 8) {
    try {
      const response = await $.ajax({
        url: 'ajax.php',
        type: 'POST',
        data: { op_no: op, page: 'ipreg' }
      });

      if (response && response.length > 0) {
        const json_events = response.split("|");

        $("#name").val(json_events[3]);
        $("#age").val(json_events[4]);
        $("#is_generated").val(json_events[28]);
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
        $("#ipd").val(json_events[29]);
        $("#ptype").val(json_events[33]);
        $("#policy").val(json_events[30]);
        $("#mlc").val(json_events[35]);
        $("#ipcardno").val(json_events[38]);
        toggleCashlessFields(json_events[33]);

        // Call function after department is set
        getdoctor(json_events[9]);
      }
    } catch (error) {
      console.error("Error in AJAX request:", error);
    }
  }
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
        type: 'POST', // Send post data
        data: 'dept='+val+'&page=doctor1',
        async: false,
        success: function(s){
            // alert(s);
          $('#drname').html(s);
        }
         });
    
          $.ajax({
      url: 'ajax.php',
        type: 'POST', // Send post data
        data: 'dept='+val+'&page=ward',
        async: false,
        success: function(s){
            // alert(s);
           $('#ward').html(s);
           // General Ward comes back pre-selected; load its beds straight away
           if ($('#ward').val()) getBed($('#ward').val());
        }
         });
    }
    function getBed(val)
    {
        dept=document.getElementById('dept').value;
             $.ajax({
          url: 'ajax.php',
            type: 'POST', // Send post data
            data: 'dept='+dept+'&ward='+val+'&page=bed',
            async: false,
            success: function(s){
                // alert(s);
               $('#bed').html(s);
            }
         });
    }
    
    function validateMyForm(){
        if(document.getElementById('ipd').value==0)
        {
            var btn = document.getElementById("submit");
            if(btn.disabled) return false;
            btn.disabled = true;
            btn.innerHTML = 'Saving...';
            return true;
        }
        else{
            alert("IPD is already Generated. Please check and Re-submit");
            return false;
        }
    }
    
       $(document).ready(function() {
    $('.select2search').select2();
     $('.select2city').select2();
     toggleCashlessFields($('#ptype').val());
     updateCashlessProviderLabel();
});
  
</script>
  

</body>

</html>