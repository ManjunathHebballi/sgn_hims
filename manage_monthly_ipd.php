<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  

include('model/reportClass.php');
$newReport = new report;

if(isset($_REQUEST['year']))
{
    $year=$_REQUEST['year'];
}
else{
        $year=date("Y");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Manage IPD Registry</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" ></script>
    <!-- Datatable -->
    <link href="./vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">

    <style>
     #notification {
            position: absolute;
            top: 90px;
           
            right:2px;
            z-index: 99;
        }
        
        table th{
        
            font-weight:600;
        }
          .table thead th{
        
            font-weight:600;
        }
        .bg-primary{
            
            background-color:#364277 !important;
            color:white
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
<h4 class="text-uppercase mb-5"> Monthy IPD Registry Report</h4>


   
   <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="#pills-home-tab" method="POST">
    <div class="row">
        <div class="col-md-4">
           <div class="form-group">
                  <label for="exampleInputEmail1"> Year </label>
                  <select aria-hidden="true" name="year" id="year" class="form-control">
								<option value="">--- Select ---</option>
							    <?php 
							    for($i = date("Y")-5; $i <=date("Y"); $i++){ ?>
                                    <option value="<?= $i ?>" <?php if($i==$year) echo 'selected';?>><?=  $i  ?></option>
                                <?php }
                                ?>
						
								</select>
                  
            </div>
        </div>

     

         

    </div>
    
    <div class="row">
            <div class="col-md-12">
            <button type="submit" class="btn btn-success  float-right btn-rounded"  name="submit"><span><i class="bi bi-search"></i></span> Search</button>
            </div>    
    </div>
</form>
<hr>
                   <div class="d-flex justify-content-end">
                       <a href="print_monthly_ipd_registry.php?year=<?= $year?>" class="badge badge-pill badge-danger" target="_blank"data-toggle="tooltip" data-placement="top" title="Print  Report"><i class="icofont-print"></i> Print</a>
                       
                   </div>
                   

                    <div class="table-responsive">
                    <table class="table table-bordered text-center  text-wrap table-hover">
                                        <thead class="bg-primary te">
                                            <tr>
                                            <th rowspan="2">Month</th>
                                            <th colspan="2">ICU</th>
                                            <?php
                                            $count=1;
                                            $res=$newReport->getIPDepartment();
                                            while($row=mysqli_fetch_assoc($res))
                                            {
                                            ?>
                                            <th colspan="2"><?= $row['dept_name']?></th>
                                            <?php
                                            $count++;
                                            } ?>
                                             <th rowspan="2">Total <br>Admission</th>
                                              <th rowspan="2">Total <br>Discharge</th>
                                            </tr>
                                            <tr>
                                              <?php for($i=0;$i<$count;$i++) { ?>  
                                                <th>  Admission </th>
                                              <th>  Discharge </th>
                                              <!--<th>  Remaining </th>-->
                                              
                                              <?php } ?>
                                             
                                              </tr>
                                         
                                        </thead>
                                        <tbody class=" text-black">
                                           <?php 
                                           $gma=$gmd=$pda=$pdd=$pya=$pyd=$ska=$skd=$gsa=$gsd=$ora=$ord=$dna=$dnd=$ana=$and=$opa=$opd=$gya=$gyd=$ema=$emd=$ena=$end=$icua=$icud=0;
                                            if($year==date("Y"))
                                           {
                                               $month=date('n');
                                           }
                                           else{
                                               $month=12;
                                           }
                                           for($i=1;$i<=$month;$i++) {
                                           $totadm=$totdis=0; ?>
                                           <tr>
                                            <th><?= date("M",mktime(0,0,0,$i+1,0,0))?></th>
                                            <?php $rest=$newReport->getDeptIPMonthlyReport($i,$year);
                                            $sn=1;
                                            while($row1=mysqli_fetch_assoc($rest)){
                                            $totadm +=$row1['occupied'];
$totdis +=$row1['discharge'];?> 
                                          <td><?= $row1['occupied']?></td>
                                            
                                             <td><?= $row1['discharge']?></td>
                                         
                                           <?php 
                                           
                                       if($sn==1)
                                       {
                                       $icua += $row1['occupied'];
                                       $icud += $row1['discharge'];
                                       }    
                                       if($sn==2)
                                       {
                                       $gma += $row1['occupied'];
                                       $gmd += $row1['discharge'];
                                       }
                                       if($sn==3)
                                       {
                                       $pda += $row1['occupied'];
                                       $pdd += $row1['discharge'];
                                       }
                                       if($sn==4)
                                       {
                                       $pya += $row1['occupied'];
                                       $pyd += $row1['discharge'];
                                       }
                                       if($sn==5)
                                       {
                                       $ska += $row1['occupied'];
                                       $skd += $row1['discharge'];
                                       }
                                       if($sn==6)
                                       {
                                       $gsa += $row1['occupied'];
                                       $gsd += $row1['discharge'];
                                       }
                                       if($sn==7)
                                       {
                                       $ora += $row1['occupied'];
                                       $ord += $row1['discharge'];
                                       }
                                       
                                       if($sn==8)
                                       {
                                       $opa += $row1['occupied'];
                                       $opd += $row1['discharge'];
                                       }
                                       if($sn==9)
                                       {
                                       $gya += $row1['occupied'];
                                       $gyd += $row1['discharge'];
                                       }
                                      
                                       if($sn==10)
                                       {
                                       $ena += $row1['occupied'];
                                       $end += $row1['discharge'];
                                       }
                                           
                                           $sn++;
                                           } ?>
                                           
                                           <td ><?= $totadm?></td>
                                           <td><?= $totdis?></td>
                                          
                                            
                                            
                                            
                                           </tr>
                                           <?php } ?>
</tbody>
                                             <tfoot>
                                                <tr>
                                                    <th>Total</th>
                                                  <th><?= $icua ?></th>
<th><?= $icud ?></th>
<th><?= $gma ?></th>
<th><?= $gmd ?></th>
<th><?= $pda ?></th>
<th><?= $pdd ?></th>
<th><?= $pya ?></th>
<th><?= $pyd ?></th>
<th><?= $ska ?></th>
<th><?= $skd ?></th>
<th><?= $gsa ?></th>
<th><?= $gsd ?></th>
<th><?= $ora ?></th>
<th><?= $ord ?></th>
<th><?= $opa ?></th>
<th><?= $opd ?></th>
<th><?= $gya ?></th>
<th><?= $gyd ?></th>
<th><?= $ena ?></th>
<th><?= $end ?></th>
<th><?= $gma+$pda+$pya+$ska+$gsa+$ora+$dna+$ana+$opa+$gya+$ema+$ena;?></th>
<th><?= $gmd+$pdd+$pyd+$skd+$gsd+$ord+$dnd+$and+$opd+$gyd+$emd+$end;?></th>
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

    
    <!-- Datatable -->
    <script src="./vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="./js/plugins-init/datatables.init.js"></script>


  

</body>

</html>