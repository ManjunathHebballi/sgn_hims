<?php 

require_once(__DIR__ . '/model/session_config.php');
include("model/medicineClass.php");
$newMedicine= new medicine();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Indent Details</title>
    <!-- base:css -->
    <link rel="stylesheet" href="vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="vendors/base/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="css/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="images/favicon.png" />
  <link rel="stylesheet" href="./vendor/select2/css/select2.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap4.min.css">
  </head>
  <body>
  <div id="main-wrapper">

<?php include ('leftbar.php')?>
<div class="content-body">


    <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <div class="main-panel">
                
                <div class="content-wrapper ">
                                    <h3 class="page-title text-dark font-weight-bold mb-2 px-4">Indent Details</h3>



          
                                    <form action="controller/processIndent.php" method="POST">
                                    
            <div class="row mb-4 mt-3">
                <div class="col-md-12">
                  
                            
            <div class="card">
                <div class="card-body">

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">

                    <label for="pname">From Store Name</label>
                     <select class="form-control" name="sub_store" id="sub_store" disabled>
                        <?php
                        $data=$newMedicine->getSubstore();
                        while($row=mysqli_fetch_assoc($data)){
                        ?>
                        <option value="<?= $row['id']?>"><?= $row['store_name']?></option>
                        <?php } ?>
                    </select>

                        </div>
                    </div>

                     <div class="col-md-3">
                        <div class="form-group">

                    <label for="pname">To Store Name</label>
                    <input class="form-control" type="text" id="store" value="Main Store"  readonly>

                        </div>
                    </div>
                  <div class="col-md-3">
                        <div class="form-group">

                    <label for="pname">Priority</label>
                    
             <input class="form-control" type="text" id="priority" value=""  readonly>
                        </div>
                    </div>

                            <div class="col-md-3">
                   <div class="form-group">
                      <label for="date">Indent Date</label>
                      <input class="form-control" type="text" id="date" value=""  readonly>
                    </div>
                    
                   </div>
                    
                <div class="col-md-3">
                   <div class="form-group">
                      <label for="date">Indent No</label>
                      <input class="form-control" type="text" id="indent_no" value="<?php echo $_REQUEST['id']?>"  readonly>
                    </div>
                    
                   </div>
                     <div class="col-md-3">
                   <div class="form-group">
                      <label for="date">Indent Status</label>
                      <input class="form-control" type="text" id="status" value=""  readonly>
                    </div>
                    
                   </div>
                
                </div>
               

            
                </div>
            </div>
                </div>
            </div>
            

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body table-responsive">
     <form action="controller/processIndent.php" method="POST">        

              <table class="table  table-bordered"   id="tab_logic">
     <thead>
            <tr>
                <th >Sl NO</th>
                  <th width='30%'>Medicine Information</th>
                <th width='20%'>Batch</th>
                <th>Exp Date</th>
                <th>Req Qty</th>
                <th>Issue Qty</th>
            </tr>
        </thead>
            <tbody>
            <?php 
            $res=$newMedicine->getIndentByNo($_REQUEST['id']);
            $sn=1;
            while($row=mysqli_fetch_assoc($res)) {?>
            <tr id='addr1'>
                <td><?= $sn?></td>
                <td><?= $row['name']?></td>
                <td><?= $row['batch']?></td>
                <td><?= $row['exp_date']!='0000-00-00' ? date("Y M", strtotime($row['exp_date'])) : ''?></td>
                <td><?= $row['req_qty']?></td>
                <td><?= $row['issue_qty']?></td>
                <input type="hidden" name="indent_id" value="<?= $row['id']?>">
            </tr>
            <?php $sn++; 
            $priotiy=$row['priority'];
            $status=$row['status'];
            $date=date("d.m.Y h:i:s A",strtotime($row['indent_date']));
            $store=$row['store_id'];
            } 
            
            ?>
            </tbody>
            <!-- </table>
            <table class="table table-responsive table-bordered"   id="tab_logic"> -->
            <tfoot> 
           
          
       
               
            
        <input type="hidden" name="id" id="id" value=1>
         

            <!-- <tr>
                <td colspan="8" class="">  <?= $status?></td>
               
                
            </tr> -->
        </tfoot>
            </table>
            <?php if($_SESSION['role']=='Pharmacist'  && $status=='ISSUED')
            { ?>
     <div class="col-md-12 d-flex justify-content-end m-4 ">
    <button type="submit" class="btn btn-success  font-weight-normal" name="submit" value="receive_indent">Received Indent</button>
</div>       
<?php } ?>
</form>
            </div>
        </div>

    </div>
</div>


                </div>
                <!-- content-wrapper ends -->
                <!-- partial:partials/_footer.html -->
        
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
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




 <script>
                document.getElementById('priority').value='<?= $priotiy?>';
                document.getElementById('status').value='<?= $status?>';
                document.getElementById('date').value='<?= $date?>';
                document.getElementById('sub_store').value='<?= $store?>';
            </script>



<!-- Datatable -->
<script src="./vendor/datatables/js/jquery.dataTables.min.js"></script>
<script src="./js/plugins-init/datatables.init.js"></script>
<script src="./js/plugins-init/datatables.init.js"></script>


        <!-- container-scroller -->
    <!-- base:js -->
    <script src="vendors/base/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page-->
    <!-- End plugin js for this page-->
    <!-- inject:js -->
    <script src="js/template.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js" integrity="sha384-VHvPCCyXqtD5DqJeNxl2dtTyhF78xXNXdkwX1CZeRusQfRKp+tA7hAShOK/B/fQ2" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.1.1.min.js"> </script>
    <!-- endinject -->
     <script src="./vendor/select2/js/select2.full.min.js"></script>
    <script src="./js/plugins-init/select2-init.js"></script>





    <!-- End custom js for this page-->
  </body>
</html>