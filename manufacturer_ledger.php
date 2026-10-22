<?php 
require_once(__DIR__ . '/model/session_config.php');
include('model/medicineClass.php');
$medic= new medicine;
date_default_timezone_set('Asia/Calcutta');
if(isset($_REQUEST['from']))
{
    $from=$_REQUEST['from'];
}
else{
    $from=date("Y-m-d");
}
if(isset($_REQUEST['to']))
{
    $to=$_REQUEST['to'];
}
else{
    $to=date("Y-m-d");
}
if(isset($_REQUEST['man']))
{
    $manu=$_REQUEST['man'];
}
else{
    $manu='';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#319197">

    <title>Manufacturer Ledger</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" ></script>
        <!-- Sweet Alert-->
     <link href="css/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
 


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
        <p>Please wait</p>
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
            <h4 class="text-uppercase">Manufacturer Ledger</h4>
        </div>
    </div>

    <div class="row">

    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
              <h4>  Select Criteria</h4> <br>
              <form action="#" method="POST">
              <div class="row">
                  
                  <div class="col-md-4">
                      <div class="form-group">
                          <label for="">Select Manufacturer <small class="text-danger">*</small></label>
                          <Select class="form-control" name="man" id="man" required>
                              <option value="">Select</option>
                              <?php $res=$medic->getManufacture();
                                while($row=mysqli_fetch_assoc($res)) {?>
                                <option value="<?= $row['id']?>" <?= $row['id']=$manu ? 'selected' : '' ?>><?= $row['manuf_name']?></option>
                                <?php } ?>
                          </Select>
                      </div>
                  </div>
                  <div class="col-md-4">
                      <div class="form-group">
                          <label for="">From Date </label>
                          <input type="date" name="from" id="from" class="form-control" value="<?= $from?>">
                          
                          
                      </div>
                  </div>
                  <div class="col-md-4">
                      <div class="form-group">
                          <label for="">To Date </label>
                          <input type="date" name="to" id="to" class="form-control" value="<?= $to?>">
                          
                          
                      </div>
                  </div>
                  <div class="col-md-12">
                   <button type="submit" class="btn btn-success float-right m-1"> Search</button> 
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
                    <?php $data=$medic->getManufactureBalance($manu,$from);?>
                    <h3>Pre Balance : <span id="prebal"><?= $data?></span> </h3>
                    <h3>Current Balance : <span id="curbal">0.00 </span></h3>
                    <div class="row p-3">
                        
                        <div class="col-md-12 border rounded border-dark" >
      <h4 class="text-center m-3">Manufacturer Ledger- Account Payable <span class="text-danger">( <?= date('d.m.Y',strtotime($from))?> To <?= date('d.m.Y',strtotime($to))?>)</span></h4>

                          
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="thead-dark">
                                        
                                    <tr>
                                        <th>Sl.No.</th>
                                        <th>Date</th>
                                        <th>Description</th>
                                        <th>Debit</th>
                                        <th>Credit</th>
                                        <th>Balance</th>
                                   </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sn=1;
                                        $debit=$credit=0;
                                        $res=$medic->getManufactureLedger($manu,$from,$to);
                                        while($row=mysqli_fetch_assoc($res)) {
                                        ?>
                                       <tr>
                                           <td><?= $sn?></td>
                                           <td><?= date("d.m.Y",strtotime($row['date']))?></td>
                                           <td><?= $row['desc']?> </td>
                                           <td><?= $debit +=$row['debit']?></td>
                                           <td><?= $credit +=$row['credit']?></td>
                                           <td><?= $row['balance']?></td>
                                        </tr>
                                    
                                    <?php $sn++; } ?>
                                    </tbody>
                                    <tfoot>
                                         <tr> <b>
                                            <td colspan="3" class="text-right">Total</td>
                                            <td><?= $debit?></td>
                                            <td><?= $credit?></td>
                                            <td><?= $bal=$debit-$credit?></td>
                                           
                                                <?php 
                                                if($debit==0 &&$credit==0)
                                                {
                                                  echo "<script>document.getElementById('curbal').innerHTML=document.getElementById('prebal').innerHTML</script>";  
                                                }
                                                else{
                                                    echo "<script>document.getElementById('curbal').innerHTML=$bal</script>";
                                                }
                                                ?>
                                            </b>
                                        </tr>
                                    </tfoot>
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
        <!--**********************************
            Content body end
        ***********************************-->


        <!--**********************************
            Footer start
        ***********************************-->
        <?php include('../footer.php') ?>
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
    <!--  vendors -->
    <script src="vendor/global/global.min.js"></script>
    <script src="js/quixnav-init.js"></script>
    <script src="js/custom.min.js"></script>

<script>
    function showCard(val)
    {
        // alert(val)
        if(val=='BPL')
        {
            document.getElementById('cardno').style.display='inline';
        }
        else{
            document.getElementById('cardno').style.display='none';   
        }
    }
    function getSubdept(val)
    {
        // alert(val)
        if(val=='7')
        {
            document.getElementById('subdept').style.display='inline';
        }
        else{
            document.getElementById('subdept').style.display='none';   
        }
    }
    window.setTimeout(function() {
    $(".alert").fadeTo(500, 0).slideUp(500, function(){
        $(this).remove(); 
    });
}, 4000);
</script>



<!-- Sweet Alerts js -->
<script src="css/sweetalert2/sweetalert2.min.js"></script>

<!-- Sweet alert init js-->
<script src="js/sweet-alerts.init.js"></script>
  

</body>

</html>