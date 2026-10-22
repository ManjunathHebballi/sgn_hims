<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Out Source Test List</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
        
    <link href="./css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


    
 <style>
     
     table td,table th {
         padding:5px;
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
            <h4 class="text-uppercase"> Out Source Test List </h4>
        </div>
        <div class="col-md-6">
           <a href='outSourceLab.php' class='btn btn-sm btn-primary float-right'>View </a>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
            <div class="card">
               
                <div class="card-body">
             
<form method="post" id='package-form'>
                <div class="row">
 
        
         <div class='col-sm-12 mt-4'>
             <label>
                 Select Test
             </label>
               <select id="test-select" style="width: 300px;">
    <option value="">Select Test</option>
    <option value="1" data-name="CBC" data-price="250">CBC</option>
    <option value="2" data-name="Blood Sugar" data-price="150">Blood Sugar</option>
    <option value="3" data-name="LFT" data-price="500">LFT</option>
    <option value="4" data-name="KFT" data-price="450">KFT</option>
  </select>

  <table id="test-table" style="margin-top:20px; width: 100%;">
    <thead>
      <tr>
        <th>Test Name</th>
        <th>Price</th>
        <th>Remove</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>
         </div>
                    
                    <div class="col-md-12 mt-4">
                        <button type="submit" class="btn btn-success float-right">Save</button>
                    </div>
                </div>
</form>
                </div>
            </div>
        </div>
    </div>
    
    <div class='card'>
        <div class='card-body'>
            <h5> Test List</h5>
             <table id="table table-bordered" style="margin-top:20px; width: 100%;">
    <thead>
      <tr>
          <th>Sl. No.</th>
        <th>Test Name</th>
        <th>Price</th>
        <th>Remove</th>
      </tr>
    </thead>
    <tbody>
        
         <tr>
             <th>01</th>
        <th>Test Name</th>
        <th>Price</th>
        <th><button class='btn btn-sm btn-danger' role='button'>Remove</button> </th>
      </tr>
    </tbody>
  </table>
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



  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <script>
    $(document).ready(function () {
      $('#test-select').select2();

      $('#test-select').on('select2:select', function (e) {
        const selected = e.params.data;
        const option = $('#test-select option[value="' + selected.id + '"]');
        const name = option.data('name');
        const price = option.data('price');

        // Prevent duplicates
        if ($('#test-table tbody tr[data-id="' + selected.id + '"]').length > 0) {
          alert('Test already added.');
          return;
        }

        const row = `<tr data-id="${selected.id}">
                      <td width='60%'>${name}</td>
                      <td><input type='number' name='amount' class='form-control' placeholder='Enter Amount..' /></td>
                      <td><button class="remove-btn btn btn-danger btn-sm">Remove</button></td>
                    </tr>`;
        $('#test-table tbody').append(row);

        // Clear selection for next
        $('#test-select').val(null).trigger('change');
      });

      // Remove row
      $(document).on('click', '.remove-btn', function () {
        $(this).closest('tr').remove();
      });
      
        $('#package-form').on('submit', function (e) {
      if ($('#test-table tbody tr').length === 0) {
        alert('Please add at least one test to the package.');
        e.preventDefault(); // stop form from submitting
        return false;
      }
    });
    });
  </script>



</body>

</html>