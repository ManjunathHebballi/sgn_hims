<?php date_default_timezone_set("Asia/Kolkata");  
require_once(__DIR__ . '/../model/session_config.php');
include('../model/ipClass.php');
$newIP = new addIP;
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
<link rel="stylesheet" href="./vendor/select2/css/select2.min.css">
 <link href="assets/libs/datatables.net-select-bs4/css/select.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        
        <link href="assets/libs/datatables.net-select-bs4/css//select.bootstrap4.min.css" rel="stylesheet" type="text/css" />

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
<style>
     .bootstrap-select .btn-light{
            color:black!important;
            
        }
</style>

</head>

<body onload="patientDetail('<?= $_REQUEST['id']?>')">

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
<form action="controller/processBilling.php" method="POST" enctype="multipart/form-data">

    <div class="row">
                <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1"> Patient ID  <span style="color:red">*</span></label>
                   <input type="text" class="form-control text-uppercase " name="patinetid" id="patinetid" aria-describedby="emailHelp" onchange="patientDetail(this.value)" required placeholder="Enter card No" value="<?= $_REQUEST['id']?>">
                   
             </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputEmail1">  Refernce No <span style="color:red">*</span></label>

                <input type="text" class="form-control text-uppercase" name="refno" id="refno" aria-describedby="emailHelp"  placeholder="Enter Card Number">

            </div>
         </div>

         <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1">Patient Name<span style="color:red">*</span></label>
                   <input type="text" class="form-control text-uppercase " name="fname" id="fname" placeholder="Enter Patient Name"  required>
             </div>
         </div>




        <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1">Age<span style="color:red">*</span></label>
                   <input type="text" class="form-control text-uppercase " name="age" id="age" placeholder="Enter Age"  required>
             </div>
         </div>

         <div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputEmail1">  Sex <span style="color:red">*</span></label>
                  <input type="text" class="form-control text-uppercase " name="sex" id="sex" placeholder="Enter Sex"  required>
            </div>
         </div>
         <div class="col-md-3">
           <div class="form-group">
                  <label for="exampleInputEmail1"> Admission Date <span style="color:red">*</span></label>
                  <input class="form-control text-uppercase" type="datetime-local"  name="admidate" id="admidate"  value=""  readonly>
                  
            </div>
        </div>
        <div class="col-md-3">
           <div class="form-group">
                  <label for="exampleInputEmail1"> Discharge Date <span style="color:red">*</span></label>
                  <input class="form-control text-uppercase" type="datetime-local" name="disdate" id="disdate"  value="<?php echo date('Y-m-d\TH:i')?>"  >
                  
            </div>
        </div>

        <div class="col-md-3">
           <div class="form-group">
                  <label for="exampleInputEmail1"> Attending Physician (Primary doctor)<span style="color:red">*</span></label>
                  <input class="form-control text-uppercase" type="text" name="primary_doctor" id="primary_doctor"    readonly>
                  
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputEmail1"> Diagnosis </label>
 <select class="form-control selectpicker" multiple data-live-search="true" data-live-search-style="contains" id="diagnosis1" requierd>
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
                <label for="exampleInputEmail1"> Present Diagnosis </label>
                <textarea class="form-control text-uppercase " name="diagnosis" id="diagnosis" cols="1" rows="1"></textarea>
</div>
</div>
<div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputEmail1"> Complaints On Reporting </label>
                <textarea class="form-control text-uppercase " name="complaint" id="complaint" cols="1" rows="1"></textarea>
</div>
</div>
<div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputEmail1"> Investigations </label>
                <textarea class="form-control text-uppercase " name="investigations" id="investigations" cols="1" rows="1"></textarea>
</div>
</div>
<div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputEmail1"> Course Of Treatment in Hospital </label>
                <textarea class="form-control text-uppercase " name="course" id="course" cols="1" rows="1"></textarea>
</div>
</div>
<div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputEmail1"> Advice On Discharge </label>
                <textarea class="form-control text-uppercase " name="advice" id="advice" cols="1" rows="1"></textarea>
</div>
</div>
<div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputEmail1"> Physical Examination </label>
                <textarea class="form-control text-uppercase " name="exam" id="exam" cols="1" rows="1"></textarea>
</div>
</div>

<div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputEmail1"> Discharge Condition </label>
                <select name="dis_condtion" class="form-control text-uppercase" required>
                    <option value="">Select Discharge Condition</option>
                    <option value="Routine Discharge">Routine Discharge</option>
                    <option value="DAMA (Discharge Against Medical Advice)">DAMA (Discharge Against Medical Advice)</option>
                    <option value="DOR (Discharge on Request)">DOR (Discharge on Request)</option>
                    <option value="TRANSFER (Transfer to other unit or hospital)">TRANSFER (Transfer to other unit or hospital)</option>
                    <option value="ABSCOND (Abscond from the Hospital)">ABSCOND (Abscond from the Hospital)</option>
                    <option value="DEATH">DEATH</option>
                </select>
</div>
</div>

<div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputEmail1">Discharge Summary File</label>
                <input type='file' class="form-control text-uppercase " name="file" id="file" accept="application/pdf">
</div>

         

         </div>
<div class="row">
         <div class="col-md-12">
          
<input type="hidden" name="deptid" id="deptid" value="">
<input type="hidden" name="bill_no" id="bill_no" value="<?= $_REQUEST['bill_no']?>">
<input type="hidden" name="bill_id" id="bill_id" value="<?= $_REQUEST['bill_id']?>">
            <button type="submit" class="btn btn-primary btn-lg float-right" name="submit" value="discharge">Report</button>
            
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
      <script src="assets/libs/datatables.net-select/js/dataTables.select.min.js"></script>
        

        <!-- Datatable init js -->
        <script src="assets/js/pages/datatables.init.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

 <script src="./vendor/select2/js/select2.full.min.js"></script>
    <script src="./js/plugins-init/select2-init.js"></script>
    <script>
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
            $("#diagnosis").val(json_events[22]);
            $("#admidate").val(json_events[4]);
            $("#deptid").val(json_events[11]);
            $("#primary_doctor").val(json_events[18]);
           
          }
        });
  }
  
const input = document.getElementById('file')

input.addEventListener('change', (event) => {
  const target = event.target
  	if (target.files && target.files[0]) {

      /*Maximum allowed size in bytes
        5MB Example
        Change first operand(multiplier) for your needs*/
      const maxAllowedSize = 2 * 1024 * 1024;
      if (target.files[0].size > maxAllowedSize) {
      	// Here you can ask your users to load correct file
      	alert("File is too big!, Please make sure file size is less then 2MB");
       	target.value = ''
      }
  }
})
$(document).ready(function(){
    $('#diagnosis1').on('changed.bs.select', function () {
        // Get selected values
        var selected = $(this).val();

        // Join with newline or comma
        var text = selected ? selected.join("\n") : '';

        // Set into textarea
        $('#diagnosis').val(text);
    });
});
  </script>
 

</body>

</html>