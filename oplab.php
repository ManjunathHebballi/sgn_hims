<?php 
require_once(__DIR__ . '/model/session_config.php');
include('model/optreatmentClass.php');

$newTreat=new addOTTreat;
$patient=$newTreat->getPatient($_REQUEST['id']);
$res=mysqli_fetch_assoc($patient);
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
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet"/>


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
    .select2-results__group{
        
        cursor: pointer!important;
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
    <div class="row">

   
<div class="col-md-12">
    <div class="card">
        <div class="card-body">
  

  <form action="controller/processOPTreat.php" method="post">
  <h4 class="text-danger text-uppercase mb-4"><?php if($_SESSION['role']=='Radiology') { echo "Radiology";} else{?>Lab Investigation <?php } ?> <span class="text-black"> <?= $res['name'].' ( '.$res['opd_no'].' ) '?></span></h4>
  <div class="row p-3">

<div class="col-md-6">
<h5 class="text-primary  text-uppercase  mb-3"> Tests</h5>
                                                                <div class="form-group">
                                                                    <label for="" class="text-black">Select Tests</label>
<select  name="lab_test[]" multiple  id="lab"  class="form-control selectpicker"  data-live-search="true">

<?php 
    $lab=$newTreat->getLabTests(); 
    while($row=mysqli_fetch_assoc($lab)){ ?>
    <option value="<?= $row['testid']?>"><?= $row['testname']?></option>
    <?php } ?>
</select>
                                                                </div>
                                                                
                                                            </div>
                                                    

                                                            <div class="col-md-2 mt-5">

                                                            <button class="btn btn-success float-right" name="submit" value="invest">Add</button>
  <input type="hidden" name="pid" value="<?= $_REQUEST['id']?>"> 
  <input type="hidden" name="from" value="<?= $_REQUEST['from']?>"> 
  <input type="hidden" name="to" value="<?= $_REQUEST['to']?>"> 
  <input type="hidden" name="search" value="<?= $_REQUEST['search']?>"> 
  </div></div>
                </form> 
                                                            
                                                            <div class="col-md-12">
                                                                <div class="table-responsive">
                                                                    <table class="table table-bordered" id="loadLab">
                                                                        <thead class="thead-dark">
                                                                            <tr class="text-center">
                                                                                <th>Sl.No.</th>
                                                                                <th>Selected Tests</th>
                                                                                <th>Date</th>
                                                                                <th>Visit No</th>
                                                                                 <!--<th class="text-center">Action</th> -->
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        <?php
                                                                            $sn=1;
                                                                             $lab=$newTreat->getPatientLab1($_REQUEST['id']);
                                                                            while($row=mysqli_fetch_assoc($lab)){ ?>
                                                                            <tr class="text-center">
                                                                              <td><?= $sn?></td>
                                                                                <td>
                                                                                <p><?= $row['testname']?></p>
                                                                                </td>
                                                                                <td><?= date("d.m.Y",strtotime($row['ldate']))?></td>
                                                                                <td><?= $row['visit_no']?></td>
                                                                                <!--<td class="text-center"><a href="#" onclick="deleteLab('<?= $row['id']?>','<?= $_REQUEST['id']?>')" data-toggle="tooltip" title="Delete Test" class="badge badge-pill badge-danger"><i class="ri-close-line "></i></a></td>-->
                                                                                
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script>


<script>
$('#lab').select2({
    placeholder: "Select test",
    allowClear: true,
    width: '100%',
    tags: false
});
     

    function deleteLab(val,id)
    {
         $.ajax({
            type : 'post',
            url : 'ajax.php', //Here you will fetch records 
            data :  'id='+val+'&page=lab_del', //Pass $id
            success : function(data){
         $('#loadLab').load(" #loadLab");//Show fetched data from database
            }
        });
    }
</script> 

  

</body>

</html>