<?php
require_once(__DIR__ . '/model/session_config.php');
// print_r($_SESSION);exit;
include('model/masterClass.php');
$newMaster = new master;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Charge Category List</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" ></script>
   
    
    <!-- DataTables -->
    <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/datatables.net-select-bs4/css//select.bootstrap4.min.css" rel="stylesheet" type="text/css" />




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

<!-- Modal -->
<div class="modal fade" id="addnewcharge" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Charge Category</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save </button>
      </div>
    </div>
  </div>
</div>


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
            <h4 class="text-uppercase">Charge Category List</h4>
        </div>
        <div class="col-md-6">
            <!-- <button class="btn btn-sm btn-success float-right " data-toggle="modal" data-target="#addnewcharge"> Add Charge Category</button> -->
        </div>
    </div>
  
  <div class="card">
    <div class="card-body">
    <div class="row">
                  <!--<div class="row mb-3">-->
        <div class="col-md-12 mb-3">
            <h6 class="text-uppercase">Select Criteria</h6>
        <!--</div>-->
    </div>
    </div>
        <form method="POST" action="controller/processmaster.php">
    <div class="row d-flex align-items-center">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Charge Type</label>
                    <select name="charge_type" id="charge_type" class="form-control">
                        <option value="">Select Charge Type</option>
                        <?php $res=$newMaster->getChargeType();
                        while($row=mysqli_fetch_assoc($res)){ ?>
                            <option value="<?php echo $row['id']; ?>"><?php echo $row['charge_name']; ?></option>
                       <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Name</label>
                    <input type="text" class="form-control" name="chargename" id="chargename">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Description</label>
                    <input type="text"  class="form-control" name="description" id="description">
                </div>
            </div>
            <div class="col-md-3">
                <button type="submit" name="submit" value="charge" class="btn btn-success">Save</button>
            </div>
        </div>
        </form>
    </div>
  </div>
    
  <div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <h6>Charge List</h6>
                <hr>
            </div>
        </div>
    <table id="datatable-buttons" class="table table-hoverde display text-center">
            <thead>
                <tr>
                    <th>Sl No.</th>
                    <th>Name</th>
                    <th>Charge Type</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php $sn=1;
                $res=$newMaster->getCharge();
                while($row=mysqli_fetch_assoc($res))
                { ?>
                <tr>
                    <td><?php echo $sn; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['charge_name']; ?></td>
                    <td><?php echo $row['description']; ?></td>
                </tr>
                <?php $sn++; } ?>
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

    

    <!-- Required datatable js -->
    <script src="assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
        <!-- Buttons examples -->
        <script src="assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
        <script src="assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
        <script src="assets/libs/jszip/jszip.min.js"></script>
        <script src="assets/libs/pdfmake/build/pdfmake.min.js"></script>
        <script src="assets/libs/pdfmake/build/vfs_fonts.js"></script>
        <script src="assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
        <script src="assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
        <script src="assets/libs/datatables.net-buttons/js/buttons.colVis.min.js"></script>

        <script src="assets/libs/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
        <script src="assets/libs/datatables.net-select/js/dataTables.select.min.js"></script>
        

        <!-- Datatable init js -->
        <script src="assets/js/pages/datatables.init.js"></script>




  

</body>

</html>