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
    <title>Add Indent</title>
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
                                    <h3 class="page-title text-dark font-weight-bold mb-2 px-4">Add Indent</h3>



          
                                    <form action="controller/processIndent.php" method="POST">
                                    
            <div class="row mb-4 mt-3">
                <div class="col-md-12">
                  
                            
            <div class="card">
                <div class="card-body">

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">

                    <label for="pname">From Store Name</label>
                    <select class="form-control" name="sub_store" id="sub_store" required>
                        <?php
                        $data=$newMedicine->getSubstore();
                        while($row=mysqli_fetch_assoc($data)){
                            if($_SESSION['storeid']==$row['id'] && $_SESSION['role']=='Pharmacist') {
                        ?>
                        <option value="<?= $row['id']?>"><?= $row['store_name']?></option>
                        <?php } else{ 
                            ?>
                            <option value="<?= $row['id']?>"><?= $row['store_name']?></option>
                            <?php
                        }
                        } ?>
                    </select>

                        </div>
                    </div>

                     <div class="col-md-3">
                        <div class="form-group">

                    <label for="pname">To Store Name</label>
                    <select class="form-control" name="main_store" id="main_store" required>
                       
                        <option value="main_store">Main Store</option>
                       
                    </select>

                        </div>
                    </div>
                  <div class="col-md-3">
                        <div class="form-group">

                    <label for="pname">Priority</label>
                    <select class="form-control" name="priority" id="priority" required>
                       
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                       
                    </select>

                        </div>
                    </div>

                            <div class="col-md-3">
                   <div class="form-group">
                      <label for="date">Indent Date</label>
                      <input class="form-control" type="datetime-local" name="date" id="date" value="<?php echo date("Y-m-d H:i:s")?>"  >
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
            

              <table class="table  table-bordered"   id="tab_logic">
     <thead>
            <tr>
                <th width='60%'>Medicine Information</th>
                <th>Req Qty</th>
                <th>Action</th>
            </tr>
        </thead>
            <tbody>

            <tr id='addr1'>
                <td>    

                    <select class="form-control selectpicker medicine1 bg-white" name="madicine[]" id="madicine1" onchange="showResult(this.value,1)" autocomplete="off" placeholder="Enter Medicine Name">
              
<option value="">Select Medicine</option>

<?php  
$result=$newMedicine->getMedicine1();

while ($tab=mysqli_fetch_array($result)) 
    { 

    
                             $end = time(); // or your date as well
                        $start = strtotime($tab['exp_date']);
                        $day=ceil(abs($end - $start) / 86400);
                        if($day < 35)
                        {
                            $col="red";
                        }
                        else if($day < 65){
                            $col="yellow";
                        }
                        else if($day < 95)
                        {
                            $col="green";
                        }
                        else{
                            $col="white";
                        }
                        ?>
                        <option value="<?= $tab['id']?>"><?= $tab['name']?></option>
        <?php } ?>
                    </select>
                </td>
        
             
                 <td>
                    <input type="number" class="form-control" name="req_qty[]" id="req_qty1" placeholder="0"  required>
                </td>
               
                <td class=" text-center">
               <a class="pull-right btn btn-danger"  id='delete_row' name='dlt1' onclick='dlt(1)' >  <i class="icofont-ui-delete "  data-toggle="tooltip" data-placement="top" title="Delete">Delete</i></a>
                </td>
            </tr>
             <tr id='addr2'></tr> 
            </tbody>
            <!-- </table>
            <table class="table table-responsive table-bordered"   id="tab_logic"> -->
            <tfoot> 
            
          
       
               
            
        <input type="hidden" name="id" id="id" value=1>
         

            <!-- <tr>
                <td colspan="8" class="">  <button type="submit" class="btn btn-success text-white  d-flex justify-content-end" name="submit" value="submit">Save</button></td>
               
                
            </tr> -->
        </tfoot>
            </table>
     <div class="col-md-12 d-flex justify-content-end m-4 ">
    <button type="submit" class="btn btn-success  font-weight-normal" name="submit" value="add_indent">Save</button>
</div>          



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

    <script>
        $(document).ready(function(){
   var i=2;

  $("#delete_row").click(function(){
      if(i>1){
      $("#addr"+(i-1)).html('');
      i--;
      }
  });
  document.getElementById('id').value=i;

});

  function addNewrow(i){
     i++
     var j=parseInt(document.getElementById('id').value);
    //  alert(i+" "+j)
 if(i<j)
 {
     return;
 }
   $("#addr"+i).html("<td><select type='text' class='form-control selectpicker medicine1 bg-white' name='madicine[]' id='madicine"+i+"' onchange='showResult(this.value,"+i+")' placeholder='Enter Medicine Name'><option value=''>Select Medicine</option><?php $result=$newMedicine->getMedicine1(); while($tab=mysqli_fetch_assoc($result)){ ?><option value='<?= $tab['id']?>'><?= $tab['name']?></option><?php } ?>    </select></td>   <td> <input type='number' class='form-control' name='req_qty[]' id='req_qty"+i+"' placeholder='0'  required> </td><td class=' text-center'><a id='delete_row' name='dlt"+i+"' onclick='dlt("+i+")' class='pull-right btn btn-danger'><i class='icofont-ui-delete' data-toggle='tooltip' data-placement='top' title='Delete'>Delete</i></a></td>");
 $('.medicine1').select2();
   $('#tab_logic').append('<tr id="addr'+(i+1)+'"></tr>');
   i++; 
   document.getElementById('id').value=i;
}
function dlt(id){
      
      $("#addr"+(id)).html('');
      document.getElementById('id').value=id;

     
  };
  function showResult(str,i) {
    this.addNewrow(i)
}


$(document).ready(function() {
    $('.medicine1').select2();
    
   
});

 </script>

 <script>
    $(document).ready(function() {
        var table = $('#example').DataTable( {
            lengthChange: false,
            buttons: [ 'copy', 'excel', 'csv', 'pdf','print', 'colvis' ]
        } );
     
        table.buttons().container()
            .appendTo( '#example_wrapper .col-md-6:eq(0)' );
    } );
     </script>



    <!-- End custom js for this page-->
  </body>
</html>