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
  <h4 class="text-danger text-uppercase mb-4"> Details Medical History of <span class="text-black"> <?= $dt['name'].' ( '.$_REQUEST['id'].' ) '?></span> </h4>
                                               


  
      
 
                                               <div class="row p-3 ">
                                                <div class="col-md-12 mb-2">
  <h5 class="text-dark ">Presenting Complaint</h5>

                                                </div>
                                             
                                             <div class="col-md-4">
                                               <select name="sign_symptoms1[]"  class="form-control selectpicker" multiple data-live-search="true" data-live-search-style="startsWith" id="">
                                                    
                                                  <?php $icd=$newTreat->getIcdCodes(); 
                    while ($res1=mysqli_fetch_assoc($icd))
                    {?>
                    <option value="<?= $res1['id']?>"><?= $res1['code']." - ".$res1['details']?></option>
                    <?php } ?>
                                                </select>
                                                 </div>
                                               <div class="col-md-4">
                                               <select name="sign_symptoms2[]"  class="form-control selectpicker" multiple data-live-search="true" data-live-search-style="startsWith" id="">
                                                    
                                                  <?php $icd=$newTreat->getIcdCodes(); 
                    while ($res1=mysqli_fetch_assoc($icd))
                    {?>
                    <option value="<?= $res1['id']?>"><?= $res1['code']." - ".$res1['details']?></option>
                    <?php } ?>
                                                </select>
                                                 </div>
                                                   <div class="col-md-4">
                                               <select name="sign_symptoms3[]"  class="form-control selectpicker" multiple data-live-search="true" data-live-search-style="startsWith" id="">
                                                    
                                                  <?php $icd=$newTreat->getIcdCodes(); 
                    while ($res1=mysqli_fetch_assoc($icd))
                    {?>
                    <option value="<?= $res1['id']?>"><?= $res1['code']." - ".$res1['details']?></option>
                    <?php } ?>
                                                </select>
                                                 </div>
                                         </div>
                                         <!-- end of Presenting Complaints -->

                                 

  


<div class="row p-3 ">
                                                <div class="col-md-12 mb-2">
  <h5 class="text-dark ">Associated Complaints <small>(related to main complaints)</small></h5>

                                                </div>
                                             
                                               <div class="col-md-4">
                                               <select name="assoc_comp1[]"  class="form-control selectpicker" multiple data-live-search="true" data-live-search-style="startsWith" id="">
                                                    
                                                  <?php $icd=$newTreat->getIcdCodes(); 
                    while ($res1=mysqli_fetch_assoc($icd))
                    {?>
                    <option value="<?= $res1['id']?>"><?= $res1['code']." - ".$res1['details']?></option>
                    <?php } ?>
                                                </select>
                                                 </div>
                                                  <div class="col-md-4">
                                               <select name="assoc_comp2[]"  class="form-control selectpicker" multiple data-live-search="true" data-live-search-style="startsWith" id="">
                                                    
                                                  <?php $icd=$newTreat->getIcdCodes(); 
                    while ($res1=mysqli_fetch_assoc($icd))
                    {?>
                    <option value="<?= $res1['id']?>"><?= $res1['code']." - ".$res1['details']?></option>
                    <?php } ?>
                                                </select>
                                                 </div>
                                                  <div class="col-md-4">
                                               <select name="assoc_comp3[]"  class="form-control selectpicker" multiple data-live-search="true" data-live-search-style="startsWith" id="">
                                                    
                                                  <?php $icd=$newTreat->getIcdCodes(); 
                    while ($res1=mysqli_fetch_assoc($icd))
                    {?>
                    <option value="<?= $res1['id']?>"><?= $res1['code']." - ".$res1['details']?></option>
                    <?php } ?>
                                                </select>
                                                 </div>
                                         
                                         </div>
                                         

<!-- end of Associated Complaints (related to main complaints) -->

<div class="row p-3">
   <div class="col-md-6">
   <div class="row ">
    <div class="col-md-12 mb-2">
  <h5 class="text-dark ">History of Present illness</h5></div>
   <div class="col-md-12">
                                               <select name="present_illness[]"  class="form-control selectpicker" multiple data-live-search="true" data-live-search-style="startsWith" id="">
                                                    
                                                  <?php $icd=$newTreat->getIcdCodes(); 
                    while ($res1=mysqli_fetch_assoc($icd))
                    {?>
                    <option value="<?= $res1['id']?>"><?= $res1['code']." - ".$res1['details']?></option>
                    <?php } ?>
                                                </select>
                                                 </div>
</div>
   </div>
   <div class="col-md-6">
   <div class="row ">
    <div class="col-md-12 mb-2">
  <h5 class="text-dark ">History of Past illness</h5></div>
   <div class="col-md-12">
                                               <select name="past_illness[]"  class="form-control selectpicker" multiple data-live-search="true" data-live-search-style="startsWith" id="">
                                                    
                                                  <?php $icd=$newTreat->getIcdCodes(); 
                    while ($res1=mysqli_fetch_assoc($icd))
                    {?>
                    <option value="<?= $res1['id']?>"><?= $res1['code']." - ".$res1['details']?></option>
                    <?php } ?>
                                                </select>
                                                 </div>
</div>
   </div>


</div>


<div class="row p-3">
   <div class="col-md-6">
   <div class="row ">
    <div class="col-md-12 mb-2">
  <h5 class="text-dark ">Family History</h5></div>
 <div class="col-md-12">
                                               <select name="family_history[]"  class="form-control selectpicker" multiple data-live-search="true" data-live-search-style="startsWith" id="">
                                                    
                                                  <?php $icd=$newTreat->getIcdCodes(); 
                    while ($res1=mysqli_fetch_assoc($icd))
                    {?>
                    <option value="<?= $res1['id']?>"><?= $res1['code']." - ".$res1['details']?></option>
                    <?php } ?>
                                                </select>
                                                 </div>
</div>
   </div>
   <div class="col-md-6">
   <div class="row ">
    <div class="col-md-12 mb-2">
  <h5 class="text-dark ">Personal History</h5></div>
  <div class="col-md-12">
                                               <select name="personal_history[]"  class="form-control selectpicker" multiple data-live-search="true" data-live-search-style="startsWith" id="">
                                                    
                                                  <?php $icd=$newTreat->getIcdCodes(); 
                    while ($res1=mysqli_fetch_assoc($icd))
                    {?>
                    <option value="<?= $res1['id']?>"><?= $res1['code']." - ".$res1['details']?></option>
                    <?php } ?>
                                                </select>
                                                 </div>
</div>
   </div>
</div>

<div class="row p-3">
   <div class="col-md-6">
   <div class="row ">
    <div class="col-md-12 mb-2">
  <h5 class="text-dark ">Treatment History</h5></div>
 <div class="col-md-12">
                                               <select name="treatment_history[]"  class="form-control selectpicker" multiple data-live-search="true" data-live-search-style="startsWith" id="">
                                                    
                                                  <?php $icd=$newTreat->getIcdCodes(); 
                    while ($res1=mysqli_fetch_assoc($icd))
                    {?>
                    <option value="<?= $res1['id']?>"><?= $res1['code']." - ".$res1['details']?></option>
                    <?php } ?>
                                                </select>
                                                 </div>
</div>
   </div>
   <div class="col-md-6">
   <div class="row ">
    <div class="col-md-12 mb-2">
  <h5 class="text-dark ">Sexual/Social/Occupational etc.</h5></div>
  <div class="col-md-12">
                                               <select name="occp_history[]"  class="form-control selectpicker" multiple data-live-search="true" data-live-search-style="startsWith" id="">
                                                    
                                                  <?php $icd=$newTreat->getIcdCodes(); 
                    while ($res1=mysqli_fetch_assoc($icd))
                    {?>
                    <option value="<?= $res1['id']?>"><?= $res1['code']." - ".$res1['details']?></option>
                    <?php } ?>
                                                </select>
                                                 </div>
</div>
   </div>
</div>

<div class="row p-3 ">
    <div class="col-md-12  ">
    <input type="hidden" name="pid" value="<?= $_REQUEST['id']?>">
                                    <input type="hidden" name="edit" value="<?= $edit?>">
        <button class="btn btn-success float-right" name="submit" value="history">Save</button>
      
  <!-- <a class="nav-link" id="v-pills-investigations-tab" data-toggle="pill" href="#v-pills-investigations" role="tab" aria-controls="v-pills-investigations" aria-selected="false"><i class="icofont-test-tube-alt"></i> Investigations</a> -->
        
       
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