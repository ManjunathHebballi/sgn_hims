<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/storeClass.php');
$newStore = new store;
if(isset($_REQUEST['editid']))
{
    $item1=$newStore->getItem($_REQUEST['editid']);
    $row=mysqli_fetch_assoc($item1);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#319197">

    <title>Store Item Master</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon.png">
    <link href="css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" ></script>
    <link href="css/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
 
<style>
     #notification {
            position: absolute;
            top: 90px;
            right:2px;
            z-index: 99;
        }
</style>

</head>

<body>

    <div id="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>

<div aria-live="polite" id="notification" aria-atomic="true" >
<?php if(isset($_REQUEST['success']))
{ ?>
<div class="alert alert-success solid alert-dismissible fade show">
    <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span>
    </button>
    <strong>Success!</strong> Item saved successfully
</div>
<?php } ?>
</div>

    <div id="main-wrapper">

  <?php include ('leftbar.php')?>
  
        <div class="content-body">

<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <h4 class="text-uppercase">📋 Store Item Master</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
<form action="controller/processStore.php" method='POST'>

    <div class="row">
    
        <div class="col-md-3">
            <div class="form-group">
                   <label> Item Code <span style="color:red">*</span> </label>
                   <input type="text" class="form-control" name="item_code" id="item_code" value="<?= isset($row['item_code']) ? $row['item_code'] : ''?>" placeholder="Enter Item Code" required pattern="[^'&quot;]+" title="Single and double quotes are not allowed." <?= isset($_REQUEST['editid']) ? 'readonly' : '' ?>> 
             </div>
         </div>
         
         <div class="col-md-5">
            <div class="form-group">
                   <label> Item Name <span style="color:red">*</span> </label>
                   <input type="text" class="form-control" name="item_name" id="item_name" value="<?= isset($row['item_name']) ? $row['item_name'] : ''?>" placeholder="Enter Item Name" required pattern="[^'&quot;]+" title="Single and double quotes are not allowed."> 
             </div>
         </div>
         
         <div class="col-md-2">
            <div class="form-group">
                   <label> Category <span style="color:red">*</span> </label>
                   <select class="form-control" name="category_id" id="category_id" required>
                       <option value="">Select Category</option>
                       <?php 
                       $categories = $newStore->getCategories();
                       while($cat = mysqli_fetch_assoc($categories)) {
                           $selected = (isset($row['category_id']) && $row['category_id'] == $cat['id']) ? 'selected' : '';
                           echo "<option value='{$cat['id']}' {$selected}>{$cat['category_name']}</option>";
                       }
                       ?>
                   </select>
             </div>
         </div>
         
         <div class="col-md-2">
            <div class="form-group">
                   <label> Unit <span style="color:red">*</span> </label>
                   <select class="form-control" name="unit_id" id="unit_id" required>
                       <option value="">Select Unit</option>
                       <?php 
                       $units = $newStore->getUnits();
                       while($unit = mysqli_fetch_assoc($units)) {
                           $selected = (isset($row['unit_id']) && $row['unit_id'] == $unit['id']) ? 'selected' : '';
                           echo "<option value='{$unit['id']}' {$selected}>{$unit['unit_name']}</option>";
                       }
                       ?>
                   </select>
             </div>
         </div>
</div>

<div class="row">
         <div class="col-md-2">
            <div class="form-group">
                   <label> Item Type <span style="color:red">*</span> </label>
                   <select class="form-control" name="item_type" id="item_type" required>
                       <option value="CONSUMABLE" <?= (isset($row['item_type']) && $row['item_type']=='CONSUMABLE') ? 'selected' : '' ?>>CONSUMABLE</option>
                       <option value="ASSET" <?= (isset($row['item_type']) && $row['item_type']=='ASSET') ? 'selected' : '' ?>>ASSET</option>
                       <option value="PARTS" <?= (isset($row['item_type']) && $row['item_type']=='PARTS') ? 'selected' : '' ?>>PARTS</option>
                       <option value="SERVICE" <?= (isset($row['item_type']) && $row['item_type']=='SERVICE') ? 'selected' : '' ?>>SERVICE</option>
                   </select>
             </div>
         </div>
         
         <div class="col-md-2">
            <div class="form-group">
                   <label> Min Stock </label>
                   <input type="number" class="form-control" name="min_stock" id="min_stock" value="<?= isset($row['min_stock']) ? $row['min_stock'] : '0'?>" placeholder="0"> 
             </div>
         </div>
         
         <div class="col-md-2">
            <div class="form-group">
                   <label> Reorder Level </label>
                   <input type="number" class="form-control" name="reorder_level" id="reorder_level" value="<?= isset($row['reorder_level']) ? $row['reorder_level'] : '0'?>" placeholder="0"> 
             </div>
         </div>
         
         <div class="col-md-2">
             <div class="form-group">
                 <label for="">Expiry Required?</label> <br>
                 <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="is_expiry_required" id="exp_yes" value="1" <?php echo (isset($row['is_expiry_required']) && $row['is_expiry_required']=='1') ? 'checked' : ''; ?>> 
                  <label class="form-check-label" for="exp_yes">Yes</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="is_expiry_required" id="exp_no" value="0" <?php echo (!isset($row['is_expiry_required']) || $row['is_expiry_required']=='0') ? 'checked' : ''; ?>>
                  <label class="form-check-label" for="exp_no"> No</label>
                </div>
             </div>
         </div>
         
         <div class="col-md-2">
             <div class="form-group">
                 <label for="">Serial Required?</label> <br>
                 <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="is_serial_required" id="ser_yes" value="1" <?php echo (isset($row['is_serial_required']) && $row['is_serial_required']=='1') ? 'checked' : ''; ?>> 
                  <label class="form-check-label" for="ser_yes">Yes</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="is_serial_required" id="ser_no" value="0" <?php echo (!isset($row['is_serial_required']) || $row['is_serial_required']=='0') ? 'checked' : ''; ?>>
                  <label class="form-check-label" for="ser_no"> No</label>
                </div>
             </div>
         </div>
         
         <div class="col-md-2">
             <div class="form-group">
                 <label for="">Status</label> <br>
                 <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="status" id="status1" value="1" <?php echo (!isset($row['status']) || $row['status']=='1') ? 'checked' : ''; ?>> 
                  <label class="form-check-label" for="status1">Active</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="status" id="status0" value="0" <?php echo (isset($row['status']) && $row['status']=='0') ? 'checked' : ''; ?>>
                  <label class="form-check-label" for="status0"> Inactive</label>
                </div>
             </div>
         </div>
</div>

<div class="row">
         <div class="col-md-12">
          <?php if(isset($_REQUEST['editid'])) {?>
            <input type="hidden" name="condition" value="edit">
            <input type="hidden" name="editid" value="<?= $_REQUEST['editid']?>">
            <button type="submit" class="btn btn-primary btn-rounded float-right" name="submit" value="store_item">Update</button>
            <?php }  else {?>
            <input type="hidden" name="condition" value="new">
            <button type="submit" class="btn btn-success btn-rounded float-right" name="submit" value="store_item">Save</button>
            <?php } ?>
         </div>
  
    </div>
</form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4>Item List</h4>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-sm" id="itemTable">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th>Sl.No.</th>
                                        <th>Item Code</th>
                                        <th>Item Name</th>
                                        <th>Category</th>
                                        <th>Unit</th>
                                        <th>Type</th>
                                        <th>Min Stock</th>
                                        <th>Reorder</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $item=$newStore->getItems();
                                        
                                        $sn=1;
                                        while($row1=mysqli_fetch_assoc($item)){
                                        ?>
                                    <tr>
                                        <td><?= $sn?></td>
                                        <td><?= $row1['item_code']?></td>
                                        <td><?= $row1['item_name']?></td>
                                        <td><?= $row1['category_name']?></td>
                                        <td><?= $row1['unit_name']?></td>
                                        <td><?= $row1['item_type']?></td>
                                        <td><?= $row1['min_stock']?></td>
                                        <td><?= $row1['reorder_level']?></td>
                                        <td>
                                            <?php if($row1['status']==1) { ?>
                                                <span class="badge badge-success">Active</span>
                                            <?php } else { ?>
                                                <span class="badge badge-danger">Inactive</span>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <a href="add_store_item.php?editid=<?= $row1['id']?>" class="btn btn-sm btn-primary" data-toggle="tooltip" title="Edit Item">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php 
                                    $sn++; } 
                                    ?>
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

        <?php include('footer.php') ?>

    </div>

    <script src="vendor/global/global.min.js"></script>
    <script src="js/quixnav-init.js"></script>
    <script src="js/custom.min.js"></script>
    
    <script src="vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/js/dataTables.bootstrap4.min.js"></script>

<script>
    function sanitizeNoQuotes(input) {
        input.value = input.value.replace(/['"]/g, '');
    }

    $(document).ready(function() {
        $('#itemTable').DataTable({
            "pageLength": 25,
            "ordering": true,
            "searching": true,
            "order": [[1, 'asc']]
        });

        $('#item_code, #item_name').on('input', function() {
            sanitizeNoQuotes(this);
        });
    });

    window.setTimeout(function() {
        $(".alert").fadeTo(500, 0).slideUp(500, function(){
            $(this).remove(); 
        });
    }, 4000);
</script>

<script src="css/sweetalert2/sweetalert2.min.js"></script>

</body>
</html>
