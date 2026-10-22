<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/otClass.php');
$newOT = new addOT;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>OT Register </title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">

<link rel="stylesheet" href="./vendor/select2/css/select2.min.css">
 <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
            <h4 class="text-uppercase">OT Register </h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
<form action="controller/processOT.php" method="POST">

<div class="row">
                    <div class="col-md-12">
                        <div class="card border shadow-none">
                            <div class="card-body">
                                <h4 class="text-danger">OPERATION THEATER</h4>
                                <div class="row">
                                
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="">OP No</label>
                                        <input type="text" name="op_no" id="op_no" onchange="patientDetail(this.value)" value="<?= $_REQUEST['id']?>" class="form-control">
                                    </div>

                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="">Name</label>
                                        <input type="text" name="name" id="name" class="form-control">
                                    </div>

                                </div>  
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="">Age</label>
                                        <input type="text" name="age" id="age" class="form-control">
                                    </div>

                                </div>  
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="">Sex</label>
                                        <input type="text" name="sex" id="sex" class="form-control">
                                    </div>

                                </div>  
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="">Diagnosis</label>
                                        <input type="text" name="diagnosis" id="diagnosis" class="form-control">
                                    </div>

                                </div>        
                                <div class="col-sm-3">
                                                  <div class="form-group">
                                                      <label for="">Date</label>
                                                      <input type="datetime-local" name="otdate" id="otdate" class="form-control" value="<?= date("Y-m-d\TH:i:s") ?>">
                                                  </div>

                                              </div>
                                              <div class="col-md-3">
                                                  <div class="form-group">
                                                      <label for="">Doctor</label>
                                                      <select name="doctor" id="doctor" class="form-control select2doctor" required>
                                                         <option value="">Select Doctor</option>
                                                         <?php 
                                                      $doctor=$newOT->getdoctor();
                                                      while($row=mysqli_fetch_assoc($doctor)){
                                                      ?>
                                                      <option value="<?= $row['name'].' '.$row['last_name']?>"><?= $row['name'].' '.$row['last_name']?></option>
                                                      <?php } ?>
                                                      </select>
                                                  </div>
                                              </div>
                                              <div class="col-md-3">
                                                  <div class="form-group">
                                                      <label for="">OT Type</label>
                                                      <select name="ottype" id="ottype" class="form-control" onchange="getDetailOT()" required>
                                                         <option value="">Select OT Type</option>
                                                      <?php 
                                                      $ot_type=$newOT->getOT_type();
                                                      while($row=mysqli_fetch_assoc($ot_type)){
                                                      ?>
                                                      <option value="<?= $row['id']?>"><?= $row['ot_name']?></option>
                                                      <?php } ?>
                                                      </select>
                                                  </div>
                                              </div>
                                             

                                              <div class="col-md-3" id="major_ot" style="display: none">
                                                  <div class="form-group">
                                                      <label for=""> Major Operative Procedures </label>
                                                      <select name="major_ot" id="ottype" class="form-control selectpicker" data-live-search="true">
                                                        
                                                      <option value="">Select Proceduer</option>
                                                      <?php 
                                                      $major_ot=$newOT->getOT_details(1);
                                                      while($row=mysqli_fetch_assoc($major_ot)){
                                                      ?>
                                                      <option value="<?= $row['id']?>"><?= $row['ot_procedure']?></option>
                                                      <?php } ?>
                                                       
                                                        </select>
                                                    </div>
                                                </div>
                                              <div class="col-md-3" id="minor_ot" style="display: none">
                                                  <div class="form-group">
                                                      <label for=""> Minor Operative Procedures </label>
                                                      <select name="minor_ot" id="ottype" class="form-control selectpicker" data-live-search="true">
                                                          <option value="">Select Proceduer</option>
                                                         
                                                          <?php 
                                                      $major_ot=$newOT->getOT_details(2);
                                                      while($row=mysqli_fetch_assoc($major_ot)){
                                                      ?>
                                                      <option value="<?= $row['id']?>"><?= $row['ot_procedure']?></option>
                                                      <?php } ?>
        

                                                    
                                                      </select>
                                                  </div>
                                              </div>
                                              

                                              <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">Comments</label>
                                                    <textarea name="comments" id="comments" cols="1" rows="1" class="form-control"></textarea>
                                                </div>
                                              </div>
                                              

                                             

         

         

                </div>
            </div>
        </div>
       
        <div class="row">
         <div class="col-md-12">
          <input type="hidden" name="id" id="id" value="<?= $_REQUEST['id'] ?>">

            <button type="submit" class="btn btn-primary btn-lg float-right" name="submit" value="ot_reg"><span><i class="bi bi-node-plus-fill"></i></span> ADD</button>
            
         </div>
         </div>
  
    </div>
</form>
    </div>
     <hr>
    <div class="col-md-12">
                                    <div class="table-responsive">
                                                                    <table class="table table-bordered " >
                                                                        <thead class="thead-dark  text-center ">
                                                                            <tr>
                                                                                <th class="text-center" width="50px">Sl.No.</th>
                                                                                <th>Date</th>
                                                                                <th>Doctor</th>
                                                                                <th>Ot Type</th>
                                                                                <th>Procedure</th>
                                                                                <th>
                                                                                Comments
                                                                                </th>
                                                                                
                                                                                 <th >Action</th> 
                                                                               
                                                                            </tr>
                                                                        </thead>

                                                                        <tbody class="text-black text-center">
                                                                             <?php $ot=$newOT->getOTReg($_REQUEST['id']);
                                                                            $sn=1;
                                                                            while($row=mysqli_fetch_assoc($ot)){?>
                                                                            <tr>
                                                                               <td><?= $sn?></td>
                                                                               <td><?= date("d.m.Y",strtotime($row['date']))?></td>

                                                                               <td><?php echo $row['doctor'] ?></td>
                                                                               <td><?= $row['ot_name']?></td>
                                                                               <td><?= $row['ot_procedure']?></td>
                                                                               <td><?= $row['comment']?></td>
                                                                                 <td>
                                                                             
                                                                                <a href="#" class="badge badge-pill badge-danger" onclick="delOT(<?= $row['id']?>)" data-toggle="tooltip" title="Delete Entry"><i class=" ri-delete-bin-line"></i></a>
                                                                                </td> 
                                                                            
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
     <script src="./vendor/select2/js/select2.full.min.js"></script>
    <script src="./js/plugins-init/select2-init.js"></script>
<script>
    $(document).ready(function() {
        $('.select2doctor').select2({
            placeholder: "Select an option",
            allowClear: true
        });
    });

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
    function patientDetail(ip)
    {
        // alert(ip)
     $.ajax({
      url: 'ajax.php',
        type: 'POST', // Send post data
        data: 'op_no='+ip+'&page=otreg1',
        async: false,
        success: function(s){
            // alert(s);
            json_events = s.split("|");
            $("#op_no").val(json_events[1]);
            $("#name").val(json_events[3]);
            $("#age").val(json_events[4]);
            $("#sex").val(json_events[5]);
            $("#diagnosis").val(json_events[8]);
           
          }
        });
  }
  function delOT(id)
    {
        $.ajax({
            type:'post',
             url : 'ajax.php', //Here you will fetch records 
            data :  'id='+id+'&page=deleteOT',
              success : function(response){
           if (response == 1) {
            location.reload();
            } else {
            alert('OT is not deleted');
                }
            },
            error: function() {
                alert('Error!');
            }
        });
    }
</script>

  

</body>

</html>