<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/certificateClass.php');
$newCerti = new addCerti;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Death Register</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" ></script>
    <!-- Datatable -->
    <link href="./vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">

    <style>
     #notification {
            position: absolute;
            top: 90px;
           
            right:2px;
            z-index: 99;
        }

        

.table th, .table td{
    padding:15px!important;
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
    
  <!-- Modal -->
  <div class="modal fade" id="addnew" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <form methof="POST" action="controller/processCertificate.php">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add New</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="validationCustom02">Search IPD<span style="color: red;">*</span></label>
                    <div class="select-search" id="patient">
                        <div class="select-search__value">
                            <input tabindex="0" placeholder="Search IPD" onkeyup="javascript:patientDetail(this.value)" name="ipd_no"  id="ipd_no" autocomplete="on" class="form-control select-search__input" value="" wtx-context="183886A5-BB4B-4DB4-8980-D03A2A96BDE1">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="validationCustom01"> Patient Name<span style="color: red;">*</span></label>
                    <input type="text" readonly="" class="form-control" required="" name="patient_name" id="patient_name" value="" wtx-context="6572C31F-485E-4CEF-B9E8-C70D035B2876">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="validationCustom01"> Gender<span style="color: red;">*</span></label>
                    <select class="form-control" required="" name="gender" id="gender" wtx-context="615A44D7-831C-4AC7-AC07-BC7B5588A007">
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="validationCustom01"> Patient Contact<span style="color: red;">*</span></label>
                    <input type="text" readonly="" class="form-control" required="" name="mobile_no" id="mobile_no" value="" wtx-context="FAEE6879-131B-40F4-95CF-3DC5035811C1">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="validationCustom01"> Patient ID<span style="color: red;">*</span></label>
                    <input type="text" class="form-control" required="" readonly="" value="" name="patient_id" id="patient_id" wtx-context="629D0D25-0493-4534-B412-43253F6151AE">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="validationCustom01"> Doctor Name<span style="color: red;">*</span></label>
                    <input type="text" class="form-control" required="" readonly="" name="doctor_name" id="doctor_name" value="" wtx-context="13919EC9-0938-4FBF-9163-258B761C98CA">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="validationCustom01"> Date of Death<span style="color: red;">*</span></label>
                    <input type="datetime-local" class="form-control" required="" value="" name="dod" id="dod" wtx-context="686F3BAA-0107-46C8-83EA-D013F625B749">
                </div>
            </div>
            
            
            <div class="col-md-4">
                <div class="form-group">
                    <label for="validationCustom01">Cause of Death<span style="color: red;">*</span></label>
                    <textarea class="form-control" required="" name="cause" id="cause" wtx-context="686F3BAA-0107-46C8-83EA-D013F625B749"></textarea>
                </div>
            </div>
        </div>
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" name="submit" value="death" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
  </form>
</div>
   <!-- Modal -->
  <div class="modal fade" id="editdeath" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <form methof="POST" action="controller/processCertificate.php">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="validationCustom02">Search IPD<span style="color: red;">*</span></label>
                    <div class="select-search" id="patient">
                        <div class="select-search__value">
                            <input tabindex="0" readonly placeholder="Search IPD" onkeyup="javascript:patientDetail(this.value)" name="model_ipd_no"  id="model_ipd_no" autocomplete="on" class="form-control select-search__input" value="" wtx-context="183886A5-BB4B-4DB4-8980-D03A2A96BDE1">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="form-group">
                    <label for="validationCustom01"> Patient Name<span style="color: red;">*</span></label>
                    <input type="text" readonly="" class="form-control" required="" name="model_patient_name" id="model_patient_name" value="" wtx-context="6572C31F-485E-4CEF-B9E8-C70D035B2876">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="validationCustom01"> Gender<span style="color: red;">*</span></label>
                    <select class="form-control" required="" name="model_gender" id="model_gender" wtx-context="615A44D7-831C-4AC7-AC07-BC7B5588A007">
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="validationCustom01"> Patient Contact<span style="color: red;">*</span></label>
                    <input type="text" readonly="" class="form-control" required="" name="model_mobile_no" id="model_mobile_no" value="" wtx-context="FAEE6879-131B-40F4-95CF-3DC5035811C1">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="validationCustom01"> Patient ID<span style="color: red;">*</span></label>
                    <input type="text" class="form-control" required="" readonly="" value="" name="model_patient_id" id="model_patient_id" wtx-context="629D0D25-0493-4534-B412-43253F6151AE">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="validationCustom01"> Doctor Name<span style="color: red;">*</span></label>
                    <input type="text" class="form-control" required="" readonly="" name="model_doctor_name" id="model_doctor_name" value="" wtx-context="13919EC9-0938-4FBF-9163-258B761C98CA">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="validationCustom01"> Date of Death<span style="color: red;">*</span></label>
                    <input type="datetime-local" class="form-control" required="" value="" name="model_dod" id="model_dod" wtx-context="686F3BAA-0107-46C8-83EA-D013F625B749">
                </div>
            </div>
            
            <input type="hidden" name="death_id" id="death_id" value="">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="validationCustom01">Cause of Death<span style="color: red;">*</span></label>
                    <textarea class="form-control" required="" name="model_cause" id="model_cause" wtx-context="686F3BAA-0107-46C8-83EA-D013F625B749"></textarea>
                </div>
            </div>
        </div>
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" name="submit" value="edit_death" class="btn btn-primary">Update</button>
      </div>
    </div>
  </div>
  </form>
</div>

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
            <h4 class="text-uppercase">Death Record</h4>
        </div>
    </div>
    <div class="row"><div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <h4>Death Record Search </h4>
                    <br>
                    </div>
                </div>
        <form method="POST" wtx-context="68607057-6D65-4DBD-98B7-81C16B9389C5">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="exampleInputEmail1"> Search Key</label>
                        <input type="text" class="form-control" name="op_from" id="op_from" required="" value="" wtx-context="9DEB9E74-F80C-43AB-864A-954446227053">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-success  float-right btn-rounded" name="submit" >
                        <span><i class="bi bi-search"></i></span> Search</button></div></div></form></div></div></div></div>

    <div class="row"><div class="col-12"><button class="btn btn-primary mb-4  btn-sm" data-toggle="modal" data-target="#addnew">Add Death Record</button><div class="card"><div class="card-header"></div><div class="card-body"><div class="table-responsive">
        <table class="table table-bordered"><thead class="text-center">
            <tr>
              <th>Sl NO</th>
             <th>IPD No</th>
             <th>Patient ID</th>
             <th>Patient Name</th>
             <th>Doctor</th>
             <th>Gender</th>
             <th>Date</th>
             <th>Cause of Death</th>
             <th>Action</th>
             </tr>
             </thead>
             <tbody class="text-center">
                 <?php 
                        $sn=1;
                        $res=$newCerti->getDeathRecord();
                         while($row=mysqli_fetch_assoc($res))
                         {
                         ?>
                        <tr>
                            <td><?php echo $sn; ?></td>
                            <td><?php echo $row['ipd_no']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['patient_id']; ?></td>
                            <td><?php echo $row['doctor']; ?></td>
                            <td><?php echo $row['gender']; ?></td>
                            <td><?php echo date('d-m-Y h:i:s A', strtotime($row['dod'])); ?></td>
                            <td><?php echo $row['cause']; ?></td>
                            <td><button onclick="edit_D(<?=$row['id']; ?>)" class="btn btn-primary mb-4  btn-sm" data-toggle="modal" data-target="#editdeath">Edit</button></td>
                        </tr>
                        <?php $sn++; } ?>
             </tbody>
             </table>
             </div></div></div></div></div>
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
   <script>
    function patientDetail(ip)
    {
        // alert(ip)
     $.ajax({
      url: 'ajax.php',
        type: 'POST', // Send post data
        data: 'ip_no='+ip+'&page=birthreg',
        async: false,
        success: function(s){
            // alert(s);
            json_events = s.split("|");
            $("#patient_name").val(json_events[5]);
            $("#gender option[value="+json_events[7]+"]").attr("selected", "selected");
            $("#mobile_no").val(json_events[8]);
            $("#patient_id").val(json_events[26]);
            $("#doctor_name").val(json_events[27]);
            
          }
        });
  }
  function edit_D(id)
  {
      $.ajax({
      url: 'ajax.php',
        type: 'POST', // Send post data
        data: 'id='+id+'&page=editD',
        async: false,
        success: function(s){
            // alert(s);
            json_events = s.split("|");
            
            $("#death_id").val(json_events[0]);
             $("#model_ipd_no").val(json_events[1]);
            $("#model_gender option[value="+json_events[3]+"]").attr("selected", "selected");
            $("#model_patient_name").val(json_events[2]);
            $("#model_mobile_no").val(json_events[4]);
            $("#model_patient_id").val(json_events[5]);
            $("#model_doctor_name").val(json_events[6]);
            $("#model_dod").val(json_events[7]);
            $("#model_cause").val(json_events[8]);
        }
        });
  }
 
</script>

  

</body>

</html>