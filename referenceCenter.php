<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/labClass.php');
    $newLab = new lab;
if(isset($_REQUEST['from']))
{
    $op_from=$_REQUEST['from'];
}
else{
    $op_from=date("Y-m-d");
}
if(isset($_REQUEST['to']))
{
    $op_to=$_REQUEST['to'];
}
else{
    $op_to=date("Y-m-d");
}
if(isset($_REQUEST['search']))
{
    $search=$_REQUEST['search'];
}
else{
        $search='';
}
if(isset($_REQUEST['type'])&& $_REQUEST['type']=='submit')
{
    $newLab->updateSampleCollect($_REQUEST['id']);
    echo "<script>window.location='sample_acknowledge.php'</script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title> Reference Center</title>
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
            <h4 class="text-uppercase"> Reference Centers </h4>
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
        <h5 class="modal-title" id="exampleModalLabel">Create New Reference Center</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <div class='container'>
            <form method="post" action="controller/processLab.php" enctype="multipart/form-data">
    

    <div class="row mb-3">
      <div class="col-md-6">
        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control" required name="name" id="name">
      </div>
      <div class="col-md-6">
        <label for="contact_name" class="form-label">Contact Name</label>
        <input type="text" class="form-control" name="contact_name" id="contact_name">
      </div>
   
     
   
      <div class="col-md-6">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" name="email" id="email">
      </div>
    
    
      <div class="col-md-6">
        <label for="contact_number" class="form-label">Mobile</label>
        <input type="text" class="form-control" name="contact_number" id="contact_number">
      </div>
      <div class="col-md-6">
        <label for="bill_type" class="form-label">Bill Type</label>
        <select class="form-control" name="bill_type" id="bill_type">
          <option value="">Select Bill Type</option>
          <option value="cash">Cash</option>
          <option value="cheque">Cheque</option>
          <option value="credit">Credit</option>
          <option value="card">Card</option>
        </select>
      </div>
   
      <!-- <div class="col-md-6">
        <label for="account_id" class="form-label">Account</label>
        <select class="form-control" name="account_id" id="account_id">
          <option value=""> </option>
        </select>
      </div> -->
       <div class="col-md-12">
        <label for="address" class="form-label">Address</label>
        <textarea class="form-control" name="address" id="address" rows="2"></textarea>
      </div>
      <!-- <div class="col-md-6">
        <label for="with_or_without_lh" class="form-label">Email Report Configuration</label>
        <select class="form-control" name="with_or_without_lh" id="with_or_without_lh" onclick="letterhead_check($(this));">
          <option value="wlh">With letterhead</option>
          <option value="wolh">Without letterhead</option>
          <option value="rclh">Reference center letterhead</option>
        </select>
      </div> -->
    </div>

    <!-- <div class="row mb-3">
      <div class="col-md-6 form-check">
        <input class="form-check-input" type="checkbox" name="prescription_reference[send_email]" id="send_email" onclick="check_email_entered_ref_center($(this));">
        <label class="form-check-label" for="send_email">Send Report Mail</label>
      </div>
      <div class="col-md-6 form-check">
        <input class="form-check-input" type="checkbox" name="prescription_reference[bill_mail_patient]" id="bill_mail_patient" checked>
        <label class="form-check-label" for="bill_mail_patient">Send Bill Mail to Patient</label>
      </div>
    </div> -->

    <!-- <div class="row mb-3 letter_head d-none">
      <div class="col-md-6">
        <label for="letterhead" class="form-label">Letterhead Header Image (png, jpeg)</label>
        <input type="file" class="form-control" name="prescription_reference[letterhead]" id="letterhead">
      </div>
    </div>

    <div class="row mb-3 letter_head_footer d-none">
      <div class="col-md-6">
        <label for="footer_image" class="form-label">Letterhead Footer Image (png, jpeg)</label>
        <input type="file" class="form-control" name="footer_image" id="footer_image">
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-md-6">
        <label for="billing_type" class="form-label">Payment Type</label>
        <select class="form-control" name="billing_type" id="billing_type" onclick="if($(this).val() == 'postpaid'){$('.stop_billing').hide()}else{$('.stop_billing').show()}">
          <option value="postpaid" selected>Post Paid</option>
          <option value="prepaid">Pre Paid</option>
        </select>
      </div>
      <div class="col-md-6 stop_billing" style="display: none;">
        <div class="form-check">
          <input type="radio" class="form-check-input" name="stop_billing_advn" value="true" id="stop_billing_true">
          <label class="form-check-label" for="stop_billing_true">Stop Billing when advance is Exhausted</label>
        </div>
        <div class="form-check">
          <input type="radio" class="form-check-input" name="stop_billing_advn" value="false" id="stop_billing_false" checked>
          <label class="form-check-label" for="stop_billing_false">Allow Billing when advance is Exhausted</label>
        </div>
      </div>
    </div> -->

    <div class="row mt-4">
      <div class="col-md-12 text-right">
        <input type="hidden" name="submit" value="create_reference_center">
        <input type="submit" class="btn btn-success"  value="Save">
       
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
<th>Email</th>
<th>Actions</th>
</tr>
<?php
$referenceCenters = $newLab->getReferenceCenter();
$sl = 1;
while ($row = mysqli_fetch_assoc($referenceCenters)) {
?>
<tr id="prescription_reference-<?php echo htmlspecialchars($row['id']); ?>">
  <td><?php echo $sl++; ?></td>
  <td><?php echo htmlspecialchars($row['name']); ?></td>
  <td><?php echo htmlspecialchars($row['address'] ?? ''); ?></td>
  <td><?php echo htmlspecialchars($row['contact_name'] ?? ''); ?></td>
  <td><?php echo htmlspecialchars($row['contact_number'] ?? ''); ?></td>
  <td><?php echo htmlspecialchars($row['email'] ?? ''); ?></td>
  <td>
    <button 
      class="btn btn-sm btn-success btn-edit-reference" 
      data-toggle="modal" 
      data-target="#editReferenceCenterModal"
      data-id="<?php echo htmlspecialchars($row['id']); ?>"
      data-name="<?php echo htmlspecialchars($row['name']); ?>"
      data-venue="<?php echo htmlspecialchars($row['venue'] ?? ''); ?>"
      data-address="<?php echo htmlspecialchars($row['address'] ?? ''); ?>"
      data-contact_name="<?php echo htmlspecialchars($row['contact_name'] ?? ''); ?>"
      data-contact_number="<?php echo htmlspecialchars($row['contact_number'] ?? ''); ?>"
      data-email="<?php echo htmlspecialchars($row['email'] ?? ''); ?>"
    >Edit</button>
    <button class="btn btn-sm btn-danger btn-delete-reference" onclick="deleteReferenceCenter('<?php echo htmlspecialchars($row['id']); ?>')">Delete</button>
  </td>
</tr>
<?php
}
?>
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

<!-- Edit Reference Center Modal -->
<div class="modal fade" id="editReferenceCenterModal" tabindex="-1" aria-labelledby="editReferenceCenterLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editReferenceCenterLabel">Edit Reference Center</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" action="controller/processLab.php">
        <div class="modal-body">
          <input type="hidden" name="id" id="edit_id">
          <div class="row mb-3">
            <div class="col-md-6">
              <label for="edit_name" class="form-label">Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" required name="name" id="edit_name">
            </div>
            <div class="col-md-6">
              <label for="edit_contact_name" class="form-label">Contact Name</label>
              <input type="text" class="form-control" name="contact_name" id="edit_contact_name">
            </div>
            <div class="col-md-6">
              <label for="edit_email" class="form-label">Email</label>
              <input type="email" class="form-control" name="email" id="edit_email">
            </div>
            <div class="col-md-6">
              <label for="edit_contact_number" class="form-label">Mobile</label>
              <input type="text" class="form-control" name="contact_number" id="edit_contact_number">
            </div>
            <div class="col-md-6">
              <label for="edit_bill_type" class="form-label">Bill Type</label>
              <select class="form-control" name="bill_type" id="edit_bill_type">
                <option value="">Select Bill Type</option>
                <option value="cash">Cash</option>
                <option value="cheque">Cheque</option>
                <option value="credit">Credit</option>
                <option value="card">Card</option>
              </select>
            </div>
            <div class="col-md-12">
              <label for="edit_address" class="form-label">Address</label>
              <textarea class="form-control" name="address" id="edit_address" rows="2"></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="submit" value="edit_reference_center">
          <button type="submit" class="btn btn-success">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>

// Edit modal population
$(document).on('click', '.btn-edit-reference', function() {
  $('#edit_id').val($(this).data('id'));
  $('#edit_name').val($(this).data('name'));
  $('#edit_venue').val($(this).data('venue'));
  $('#edit_address').val($(this).data('address'));
  $('#edit_contact_name').val($(this).data('contact_name'));
  $('#edit_contact_number').val($(this).data('contact_number'));
  $('#edit_email').val($(this).data('email'));
  $('#edit_bill_type').val($(this).data('bill_type'));
  $('#editReferenceCenterModal').modal('show');
});

function deleteReferenceCenter(id) {
  if(confirm('Are you sure you want to delete this reference center?')) {
    $.ajax({
      url: 'ajax.php',
      type: 'POST',
      data: { id: id, page: 'delete_reference_center' },
      success: function(response) {
        // Optionally, you can check response for success/failure
        // Remove the row from the table
        $("#prescription_reference-" + id).remove();
        // Optionally, show a notification
        alert('Reference center deleted successfully.');
      },
      error: function() {
        alert('Error deleting reference center.');
      }
    });
  }
}
</script>

</body>

</html>