<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/reportClass.php');

$newReport = new report;

if(isset($_REQUEST['search']))
{
    $search=$_REQUEST['search'];
}
else{
    $search="";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Patient History</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" ></script>
        <!-- Sweet Alert-->
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
<div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
  <div class="row">
                <div class="col-md-12">
                    <h4>OP Patient </h4><br>
                 
                </div>
            </div> 
<form action="" method="POST">
    <div class="row">
    
        <div class="col-md-4">
            <div class="form-group">
                   <label for="exampleInputEmail1"> Search  </label>
                  <input list="searchpatients" class="form-control" required name="searchpatient" id="searchpatient" oninput="getPatient(this.value)" onchange="getOPDdetails(this.value)" placeholder="Search by Patient Name,Patient Number">

<datalist id="searchpatients">
   
                       </datalist>
   <input type="hidden" name="search" id="search" value="">
             </div>
        </div>
         

    </div>
    
    <div class="row">
            <div class="col-md-12">
            <button type="submit" class="btn btn-success  float-right btn-rounded"  name="submit"><span><i class="bi bi-search"></i></span> Search</button>
            </div>    
    </div>
</form>
        
    </div>
            </div>
        </div>
    </div>
    <?php
    if(isset($_REQUEST['submit']))
{
    $patient=array();
    $data=$newReport->selectOP_seacrh($search);
    $row=mysqli_fetch_assoc($data);
?>
   <div class="row">
        <div class="col-md-12">
             <div class="row">
                        <div class="col-md-6">
                                 <h4>Patient History</h4>
                        </div>
                        
                    </div>
            <div class="card">
                <div class="card-body">
               <h5>Patient Details</h5>
                   
                    <hr>
                
                            <div class="table-responsive">
                                 <table id="" class="table display ">
                                    <thead class="">
                                    <tr>
                                        <th>Patient Id.</th>
                                        <th><?= $row['opd_no']?></th>
                                        <th>Name</th>
                                        <th><?= $row['name']?></th>
                                        <th>Address</th>
                                         <th><?= $row['address']?></th>
                                       
                                        </tr>
                                        <tr>
                                        
                                        <th>Phone</th>
                                         <th><?= $row['mobile']?></th>
                                        <th>Department</th>
                                         <th><?= $row['dept_name']?></th>
                                        <th>Doctor</th>
                                         <th><?= $row['doctor']?></th>
                                    </tr>
                                    </thead>
                                    
                                </table>
                            </div>
                      
                </div>
            </div>
           
                <div class="card">
                <div class="card-body">
               <h5>OPD Details of <span class="text-danger"><?= $row['name']?> (<?= $row['opd_no']?>)</span></h5>
                   
                    <hr>
                
                            <div class="table-responsive">
                                 <table id="" class="table display ">
                                    <thead class="">
                                    <tr>
                                        <th>Sl.No.</th>
                                      
                                        <th>Date</th>
                                        <th>Department</th>
                                         <th>Doctor</th>
                                        <th>Diagnosis</th>
                                        <th>Added By</th>
                                        </tr>
                                       
                                    </thead>
                                    <tbody>
                                        <?php $opd=$newReport->getOPDPatient($row['opd_no']);
                                        $sn=1;
                                        while($res=mysqli_fetch_assoc($opd)){
                                        ?>
                                        <tr>
                                            <td><?= $sn?></td>
                                            <td><?= date("d.m.Y",strtotime($res['date']))?></td>
                                            <td><?= $res['dept_name']?></td>
                                            <td><?= $res['doctor']?></td>
                                            <td><?= $res['diagnosis']?></td>
                                            <td><?= $res['created_by']?></td>
                                        </tr>
                                        <?php $sn++;
                                        $patient[]=$res['id'];
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                      
                </div>
            </div>
             <div class="card">
                <div class="card-body">
               <h5>IPD Details of <span class="text-danger"><?= $row['name']?> (<?= $row['opd_no']?>)</span></h5>
                   
                    <hr>
                
                            <div class="table-responsive">
                                 <table id="" class="table display ">
                                    <thead class="">
                                    <tr>
                                        <th>Sl.No.</th>
                                       <th>Admission Date</th>
                                        <th>IPD No</th>
                                        <th>Department</th>
                                         <th>Doctor</th>
                                       <th>Diagnosis</th>
                                       <th>Admitted By</th>
                                       <th>Dischaged Date</th>
                                       <th>Discharged By</th>
                                        </tr>
                                       
                                    </thead>
                                     <tbody>
                                        <?php $opd=$newReport->getIPDPatient($row['opd_no']);
                                        $sn=1;
                                        while($res=mysqli_fetch_assoc($opd)){
                                        ?>
                                        <tr>
                                            <td><?= $sn?></td>
                                            <td><?= date("d.m.Y H:i:s",strtotime($res['admission_date']))?></td>
                                            <td><?= $res['ipd_no']?></td>
                                            <td><?= $res['dept_name']?></td>
                                            <td><?= $res['doctor']?></td>
                                            <td><?= $res['diagnosis']?></td>
                                            <td><?= $res['created_by']?></td>
                                            <td><?= $res['discharge_date']>'0000-00-00' ? date("d.m.Y H:i:s",strtotime($res['discharge_date'])) : '';?></td>
                                            <td><?= $res['discharged_by']?></td>
                                        </tr>
                                        <?php $sn++; 
                                        $patient[]=$res['ipd_no'];
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                      
                </div>
            </div>
             <div class="card">
                <div class="card-body">
               <h5>Billing Details of <span class="text-danger"><?= $row['name']?> (<?= $row['opd_no']?>)</span></h5>
                   
                    <hr>
                
                            <div class="table-responsive">
                                 <table id="" class="table display ">
                                    <thead class="">
                                    <tr>
                                        <th>Sl.No.</th>
                                        <th>Date</th>
                                        <th>Bill No</th>
                                        <th>Department</th>
                                        <th>Doctor</th>
                                        <th>Paid Amount</th>
                                        <th>Balance Amount</th>
                                        <th>Collected By</th>
                                        <th>Action</th>
                                        </tr>
                                       
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $patient1="'".implode("','",$patient)."'";
                                        $opd=$newReport->getBillPatient($patient1);
                                        $sn=1;
                                        while($res=mysqli_fetch_assoc($opd)){
                                        ?>
                                        <tr>
                                            <td><?= $sn?></td>
                                            <td><?= date("d.m.Y H:i:s",strtotime($res['date']))?></td>
                                            <td><?= $res['bill_no']?></td>
                                            <td><?= $res['dept_name']?></td>
                                            <td><?= $res['doctor']?></td>
                                            <td><?= $res['paid_amount']?></td>
                                            <td><?= $res['outstand_amount']?></td>
                                            <td><?= $res['collected_by']?></td>
                                            <td>
                                                <?php if($res['service_type']=='IP') { ?>
                                                 <a href="billing/view_ipbilling.php?id=<?= $res['patient_id']?>&bill_no=<?= $res['bill_no']?>&bill_id=<?= $res['id']?>&status=<?=$res['status']?>" title="View" class="text-warning"><i class="ri-eye-line" style="font-size:15px" target="_blank"></i> 
                                                <a href="billing/print_ipbill.php?id=<?= $res['patient_id']?>&bill_no=<?= $res['bill_no']?>&type=<?= $res['service_type']?>&status=<?= $res['status']?>" title="Print" class="text-primary"><i class="ri-printer-line" style="font-size:15px" target="_blank"></i> </a>
                                                <?php } elseif($res['service_type']=='Advance') {?>
                                                <a href="billing/print_advance_amount.php?id=<?= $res['patient_id']?>&adv_id=<?= str_replace("ADV","",$res['bill_no'])?>&type=<?= $res['service_type']?>&status=<?= $res['status']?>" title="Print" class="text-primary"><i class="ri-printer-line" style="font-size:15px" target="_blank"></i> </a>
                                                <?php } else{ ?>
                                                <a href="billing/view_billing.php?id=<?= $res['patient_id']?>&bill_no=<?= $res['bill_no']?>&bill_id=<?= $res['id']?>&status=<?=$res['status']?>" title="View" class="text-warning"><i class="ri-eye-line" style="font-size:15px" target="_blank"></i> 
                                               <a href="billing/print_bill.php?id=<?= $res['patient_id']?>&bill_no=<?= $res['bill_no']?>&type=<?= $res['service_type']?>&status=<?= $res['status']?>" title="Print" class="text-primary"><i class="ri-printer-line" style="font-size:15px" target="_blank"></i> </a>
                                                <?php } ?>
                                                </td>
                                        </tr>
                                        <?php $sn++;
                                        $patient[]=$res['id'];
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                      
                </div>
            </div>
           <div class="row">
                <div class="col-md-4">
                 <div class="card">
                <div class="card-body">
               <h5>Radiology Details of<span class="text-danger"><?= $row['name']?> (<?= $row['opd_no']?>)</span></h5>
                   
                    <hr>
                
                            <div class="table-responsive">
                                 <table id="" class="table display ">
                                    <thead class="">
                                    <tr>
                                        <th>Sl.No.</th>
                                   <th>Date</th>
                                         <th>Test</th>
                                        
                                       
                                        </tr>
                                       
                                    </thead>
                                    
                                </table>
                            </div>
                      
                </div>
            </div>
            </div>
            
            <div class="col-md-4">
                 <div class="card">
                <div class="card-body">
               <h5>Pathology Details of <span class="text-danger"><?= $row['name']?> (<?= $row['opd_no']?>)</span></h5>
                   
                    <hr>
                
                            <div class="table-responsive">
                                 <table id="" class="table display ">
                                    <thead class="">
                                    <tr>
                                        <th>Sl.No.</th>
                                   <th>Date</th>
                                         <th>Test</th>
                                        
                                       
                                        </tr>
                                       
                                    </thead>
                                    
                                </table>
                            </div>
                      
                </div>
            </div>
            </div>
             <div class="col-md-4">
                 <div class="card">
                <div class="card-body">
               <h5>OT Details of <span class="text-danger"><?= $row['name']?> (<?= $row['opd_no']?>)</span></h5>
                   
                    <hr>
                
                            <div class="table-responsive">
                                 <table id="" class="table display ">
                                    <thead class="">
                                    <tr>
                                        <th>Sl.No.</th>
                                   <th>Date</th>
                                         <th>OT Name</th>
                                        
                                       
                                        </tr>
                                       
                                    </thead>
                                    
                                </table>
                            </div>
                      
                </div>
            </div>
            </div>
           </div>
            </div>
        </div>
        <?php } ?>
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
    <!--  vendors -->
    <script src="./vendor/global/global.min.js"></script>
    <script src="./js/quixnav-init.js"></script>
    <script src="./js/custom.min.js"></script>
    
<script>
 async function getPatient(val) {
  if (val.length > 4) {
    try {
      const response = await $.ajax({
        url: 'ajax.php',
        type: 'POST',
        data: 'search=' + val + '&page=getPatient',
      });

      if (response.length > 0) {
        $('#searchpatients').html(response);
      } else {
        $('#searchpatients').html("<option value=''>NO Result Found, add New</option>");
      }
    } catch (error) {
      console.error('Error in AJAX request:', error);
    }
  } else {
    $('#searchpatients').html("");
  }
}
 function getOPDdetails(val)
    {
        data=val.split("-");
     
        if(data[0].startsWith('OP'))
        {
            $('#search').val(data[0]);
         
        }
        
    }
</script>





</body>

</html>