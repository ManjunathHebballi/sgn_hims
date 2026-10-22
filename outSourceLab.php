<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/labClass.php');
    $newLab = new lab;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title> Out Source Lab</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
        
    <link href="./css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


    
 <style>
     
     table td,table th {
         padding:5px;
     }
     
     .col-md-6,.col-md-12{
         margin-top:15px;
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

<div aria-live="polite" id="notification" aria-atomic="true" >

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
        <div class="col-md-6">
            <h4 class="text-uppercase"> Out Source Lab </h4>
        </div>
        <div class="col-md-6">
            <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#createNew">
Create New
</button>
          
        </div>
    </div>
    <!-- Modal -->
<div class="modal fade" id="createNew" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">New Out Source Lab</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <div class='container'>
      <form action="controller/processLab.php" method="post" id="outsource_lab_form">
       
    <div class="row mb-3">
      <div class="col-md-6">
        <label for="outsource_lab_name" class="form-label">Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control" required name="outsource_lab_name" id="outsource_lab_name">
        <div class="form-check mt-2">
          <input type="checkbox" class="form-check-input" name="nabl" id="outsource_lab_nabl" value="1">
         
          <label class="form-check-label" for="outsource_lab_nabl">NABL</label>
        </div>
      </div>
      

    
      <div class="col-md-6">
        <label for="outsource_lab_city" class="form-label">City</label>
        <input type="text" class="form-control" name="outsource_lab_city" id="outsource_lab_city">
      </div>
      </div>
<div class="row mb-3">
      <div class="col-md-6">
        <label for="outsource_lab_contact_person" class="form-label">Contact Person</label>
        <input type="text" class="form-control" name="outsource_lab_contact_person" id="outsource_lab_contact_person">
      </div>
    
      <div class="col-md-6">
        <label for="outsource_lab_contact_no" class="form-label">Contact No</label>
        <input type="text" class="form-control" name="outsource_lab_contact_no" id="outsource_lab_contact_no">
      </div>
    </div>
</div>
    <div class="row mb-4">
      <div class="col-md-12">
        <label for="outsource_lab_address" class="form-label">Address</label>
        <textarea class="form-control" rows="3" name="outsource_lab_address" id="outsource_lab_address"></textarea>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12 text-right">
         <input type="hidden" name="submit" value="create_outsource_lab">
        <button type="submit" class="btn btn-success">Save</button>
      </div>
    </div>
  </form>
       </div>
      </div>
      
    </div>
  </div>
</div>


<div class='card'>
    <div class='card-body'>
       <table class="table table-bordered table-hover">
<tbody><tr class="trheader">
<th>Sl no.</th>
<th>Name</th>

<th>Address</th>
<th>Contact Name</th>
<th>Contact Number</th>
<th>Actions</th>
</tr>
<?php 

$res=$newLab->getOutsourceLab();
$i=0; 
while($row=mysqli_fetch_assoc($res))
{  $i++;
  ?>
<tr id="prescription_reference-<?= $row['id']?>">
<td><?= $i ?></td>
<td><?= htmlspecialchars($row['name']) ?></td>
<td><?= htmlspecialchars($row['address']) ?></td>
<td><?= htmlspecialchars($row['contact_person']) ?></td>
<td><?= htmlspecialchars($row['contact_number']) ?></td>
<td>
    <!-- <a href='outSourceTestList.php?id=<?= $row['id']?>' class="btn btn-sm btn-primary" >Test List</a> -->
    <!-- <button class="btn btn-sm btn-success" data-remote="true"  data-toggle="modal" data-target="#createNew" >View</button> -->
    <button class="btn btn-sm btn-info btn-edit-outsource" 
        data-id="<?= htmlspecialchars($row['id']) ?>"
        data-name="<?= htmlspecialchars($row['name']) ?>"
        data-address="<?= htmlspecialchars($row['address']) ?>"
        data-contact_person="<?= htmlspecialchars($row['contact_person']) ?>"
        data-contact_number="<?= htmlspecialchars($row['contact_number']) ?>"
        data-city="<?= htmlspecialchars($row['city'] ?? '') ?>"
        data-nabl="<?= htmlspecialchars($row['nabl'] ?? '') ?>"
        data-toggle="modal" data-target="#editOutsourceLabModal"
    >Edit</button>
    <button  class="btn btn-sm btn-danger" onclick="deleteOutsourceLab('<?= $row['id']?>')">Delete</button>
</td>
</tr>
 <?php

} ?>   
     
    </tbody>
  </table>

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



  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Edit Out Source Lab Modal -->
<div class="modal fade" id="editOutsourceLabModal" tabindex="-1" aria-labelledby="editOutsourceLabLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editOutsourceLabLabel">Edit Out Source Lab</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" action="controller/processLab.php">
        <div class="modal-body">
          <input type="hidden" name="id" id="edit_outsource_id">
          <div class="row mb-3">
            <div class="col-md-6">
              <label for="edit_outsource_name" class="form-label">Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" required name="outsource_lab_name" id="edit_outsource_name">
              <div class="form-check mt-2">
                <input type="checkbox" class="form-check-input" name="nabl" id="edit_outsource_nabl" value="1">
                <label class="form-check-label" for="edit_outsource_nabl">NABL</label>
              </div>
            </div>
            <div class="col-md-6">
              <label for="edit_outsource_city" class="form-label">City</label>
              <input type="text" class="form-control" name="outsource_lab_city" id="edit_outsource_city">
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-6">
              <label for="edit_outsource_contact_person" class="form-label">Contact Person</label>
              <input type="text" class="form-control" name="outsource_lab_contact_person" id="edit_outsource_contact_person">
            </div>
            <div class="col-md-6">
              <label for="edit_outsource_contact_no" class="form-label">Contact No</label>
              <input type="text" class="form-control" name="outsource_lab_contact_no" id="edit_outsource_contact_no">
            </div>
          </div>
          <div class="row mb-4">
            <div class="col-md-12">
              <label for="edit_outsource_address" class="form-label">Address</label>
              <textarea class="form-control" rows="3" name="outsource_lab_address" id="edit_outsource_address"></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="submit" value="edit_outsource_lab">
          <button type="submit" class="btn btn-success">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
$(document).on('click', '.btn-edit-outsource', function() {
  $('#edit_outsource_id').val($(this).data('id'));
  $('#edit_outsource_name').val($(this).data('name'));
  $('#edit_outsource_address').val($(this).data('address'));
  $('#edit_outsource_contact_person').val($(this).data('contact_person'));
  $('#edit_outsource_contact_no').val($(this).data('contact_number'));
  $('#edit_outsource_city').val($(this).data('city'));
  if($(this).data('nabl') == '1') {
    $('#edit_outsource_nabl').prop('checked', true);
  } else {
    $('#edit_outsource_nabl').prop('checked', false);
  }
});
function deleteOutsourceLab(id) {
  if(confirm('Are you sure you want to delete this Out Source Lab?')) {
    $.ajax({
      url: 'ajax.php',
      type: 'POST',
      data: { id: id, page: 'delete_outsource_lab' },
      success: function(response) {
        $("#prescription_reference-" + id).remove();
        alert('Out Source Lab deleted successfully.');
      },
      error: function() {
        alert('Error deleting Out Source Lab.');
      }
    });
  }
}
</script>

</body>

</html>