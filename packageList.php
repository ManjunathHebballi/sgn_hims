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
    <title> Packages</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
        
    <link href="./css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


    
 <style>
     
     table td,table th {
         padding:5px;
     }
     
     .list li{
         list-style:disc;
         margin-left:20px;
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
            <h4 class="text-uppercase"> Create Package </h4>
        </div>
        <div class="col-md-6">
           <a href='createPackage.php' class='btn btn-sm btn-primary float-right'>Create New Packages</a>
        </div>
    </div>


<div class='card'>
    <div class='card-body'>
        <table id="packages-table " class='table table-bordered'>
    <thead>
      <tr>
        <th>#</th>
        <th>Package Name</th>
        <th>No. of Tests</th>
        <th>Total Price (₹)</th>
        <th> Test List</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
<?php
$packages = $newLab->getLabPackage();
$i = 1;
while($row = mysqli_fetch_assoc($packages)) {
    // Fetch tests for this package
    $testList = explode(",", $row['test_names']);
    
    $testCount = count($testList);
    $testNamesHtml = '';
    foreach($testList as $testName) {
        $testNamesHtml .= "<li>" . htmlspecialchars($testName) . "</li>";
    }
    echo "<tr>
        <td>{$i}</td>
        <td>" . htmlspecialchars($row['package_name']) . "</td>
        <td>{$testCount}</td>
        <td>" . htmlspecialchars($row['package_price']) . "</td>
        <td><ol class='list'>{$testNamesHtml}</ol></td>
        <td>
            <a href='editPackage.php?id=" . urlencode($row['id']) . "' class='btn btn-success btn-sm'>Edit</a>
  <a href='#' class='btn btn-danger btn-sm' onclick='deletePackage(" . $row['id'] . ")'>Delete</a>        </td>
    </tr>";
    $i++;
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
<script>
    function deletePackage(val)
    {
        if(confirm("Are you sure you want to delete this package?")) {
            $.ajax({
            url: "ajax.php",
            method: "POST",
            data: { id: val, page: "deletePackage" },
            success: function(response) {
                // Optionally, handle response or reload table
                location.reload();
            },
            error: function(xhr, status, error) {
                alert("Failed to delete package.");
            }
            });
        }
    }
</script>


  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>




</body>

</html>