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
    <title>Manage OPD Registry</title>
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
<h4 class="text-uppercase mb-5"> Monthy OPD Registry Report</h4>


   
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
                       <a href="print_monthly_opd_registry.php?year=<?= $year?>" class="badge badge-pill badge-danger" target="_blank"data-toggle="tooltip" data-placement="top" title="Print  Report"><i class="icofont-print"></i> Print</a>
                       
                   </div>
                   

                    <div class="table-responsive">
                    <table class="table table-bordered text-center  text-wrap table-hover">
                                        <thead class="bg-primary te">
                                            <tr>
                                            <th rowspan="2">Month</th>
                                            <?php
                                            $count=0;
                                            $res=$newReport->getDepartment();
                                            while($row=mysqli_fetch_assoc($res))
                                            {
                                            ?>
                                            <th colspan="3"><?= $row['dept_name']?></th>
                                            <?php
                                            $count++;
                                            } ?>
                                            <th rowspan="3">Total</th>
                                            </tr>
                                            <tr>
                                              <?php for($i=0;$i<$count;$i++) { ?>  
                                                <th>  New </th>
                                              <th>  Old </th>
                                              <th>Tot</th>
                                              <?php } ?>
                                              </tr>
                                         
                                        </thead>
                                        <tbody class=" text-black">
                                           <?php 
                                           $gm_male_new=$gm_male_old=$gm_female_new=$gm_female_old=$pd_male_new=$pd_male_old=$pd_female_new=$pd_female_old=$ps_male_new=$ps_male_old=$ps_female_new=$ps_female_old=$sk_male_new=$sk_male_old=0;
                                          $sk_female_new=$sk_female_old=$tb_male_new=$tb_male_old=$tb_female_new=$tb_female_old=$gs_male_new=$gs_male_old=$gs_female_new=$gs_female_old=$or_male_new=$or_male_old=$or_female_new=$or_female_old=0;
                                          $op_male_new=$op_male_old=$op_female_new=$op_female_old=$obg_male_new=$obg_male_old=$obg_female_new=$obg_female_old=$em_male_new=$em_male_old=$em_female_new=$em_female_old=$en_male_new=0;
                                          $en_male_old=$en_female_new=$en_female_old=$dn_male_new=$dn_male_old=$dn_female_new=$dn_female_old=$ph_male_new=$ph_male_old=$ph_female_new=$ph_female_old=0;
                                          $total=0;
                                           if($year==date("Y"))
                                           {
                                               $month=date('n');
                                           }
                                           else{
                                               $month=12;
                                           }
                                               for($i=1;$i<=$month;$i++) {
                                           $gm=$pd=$ps=$sk=$tb=$gs=$or=$op=$obg=$em=$en=$dn=$ph=0; ?>
                                           <tr>
                                            <th><?= date("M",mktime(0,0,0,$i+1,0,0))?></th>
                                            <?php $rest=$newReport->getDeptReport($i,$year);
                                            $row1=mysqli_fetch_assoc($rest)?> 
                                          
                                          <td><a href="print_monthly_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=1&type=new&dept_name=General Medicine" target="_blank"><?= $row1['gm_male_old'] + $row1['gm_female_old']?></a></td>
                                          <td><a href="print_monthly_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=1&type=old&dept_name=General Medicine" target="_blank"><?= $row1['gm_male_new'] + $row1['gm_female_new']?></a></td>
                                          <th><a href="print_monthly_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=1&type=total&dept_name=General Medicine" target="_blank"><?= $gm=$row1['gm_male_new']+$row1['gm_female_new']+$row1['gm_male_old']+$row1['gm_female_old']?></a></th>
                                           
                                           
                                          
                                            
                                            <td><a href="print_monthly_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=2&type=new&dept_name=Pediatrics" target="_blank"><?= $row1['pd_male_old']+$row1['pd_female_old']?></a></td>
                                            <td><a href="print_monthly_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=2&type=old&dept_name=Pediatrics" target="_blank"><?= $row1['pd_male_new']+$row1['pd_female_new']?></a></td>
                                           <th><a href="print_monthly_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=2&type=total&dept_name=Pediatrics" target="_blank"><?= $pd=$row1['pd_male_new']+$row1['pd_female_new']+$row1['pd_male_old']+$row1['pd_female_old']?></a></th>
                                           
                                           
                                            <td><a href="print_monthly_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=3&type=new&dept_name=Psychiatry" target="_blank"><?= $row1['ps_male_old']+$row1['ps_female_old']?></a></td>
                                            <td><a href="print_monthly_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=3&type=old&dept_name=Psychiatry" target="_blank"><?= $row1['ps_male_new']+$row1['ps_female_new']?></a></td>
                                            <th><a href="print_monthly_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=3&type=total&dept_name=Psychiatry" target="_blank"><?= $ps=$row1['ps_male_new']+$row1['ps_female_new']+$row1['ps_male_old']+$row1['ps_female_old']?></a></th>
                                           
                                           
                                            <td><a href="print_monthly_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=4&type=new&dept_name=SKIN" target="_blank"><?= $row1['sk_male_old'] + $row1['sk_female_old']?></a></td>
                                            <td><a href="print_monthly_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=4&type=old&dept_name=SKIN" target="_blank"><?= $row1['sk_male_new'] + $row1['sk_female_new']?></a></td>
                                           <th><a href="print_monthly_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=4&type=total&dept_name=SKIN" target="_blank"><?= $sk=$row1['sk_male_new']+$row1['sk_female_new']+$row1['sk_male_old']+$row1['sk_female_old']?></a></th>
                                           
                                           
                                            <td><a href="print_monthly_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=6&type=new&dept_name=General Surgery" target="_blank"><?= $row1['gs_male_old'] + $row1['gs_female_old']?></a></td>
                                            <td><a href="print_monthly_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=6&type=old&dept_name=General Surgery" target="_blank"><?= $row1['gs_male_new'] + $row1['gs_female_new']?></a></td>
                                            <th><a href="print_monthly_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=6&type=total&dept_name=General Surgery" target="_blank"><?= $gs=$row1['gs_male_new']+$row1['gs_female_new']+$row1['gs_male_old']+$row1['gs_female_old']?></a></th>
                                            
                                            
                                              <td><a href="print_monthly_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=7&type=new&dept_name=Orthopedics" target="_blank"><?= $row1['or_male_old'] + $row1['or_female_old']?></a></td>
                                            <td><a href="print_monthly_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=7&type=old&dept_name=Orthopedics" target="_blank"><?= $row1['or_male_new'] + $row1['or_female_new']?></a></td>
                                            <th><a href="print_monthly_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=7&type=total&dept_name=Orthopedics" target="_blank"><?= $or=$row1['or_male_new']+$row1['or_female_new']+$row1['or_male_old']+$row1['or_female_old'] ?></a></th>
                                            
                                              <td><a href="print_monthly_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=8&type=new&dept_name=Dentistry" target="_blank"><?= $row1['dn_male_old'] + $row1['dn_female_old']?></a></td>
                                            <td><a href="print_monthly_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=8&type=old&dept_name=Dentistry" target="_blank"><?= $row1['dn_male_new'] + $row1['dn_female_new']?></a></td>
                                            <th><a href="print_monthly_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=8&type=total&dept_name=Dentistry" target="_blank"><?= $dn=$row1['dn_male_new']+$row1['dn_female_new']+$row1['dn_male_old']+$row1['dn_female_old'] ?></a></th>
                                         
                                              <td><a href="print_monthly_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=11&type=new&dept_name=Ophthalmology" target="_blank"><?= $row1['op_male_old'] + $row1['op_female_old']?></a></td>
                                            <td><a href="print_monthly_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=11&type=old&dept_name=Ophthalmology" target="_blank"><?= $row1['op_male_new'] + $row1['op_female_new']?></a></td>
                                            <th><a href="print_monthly_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=11&type=total&dept_name=Ophthalmology" target="_blank"><?= $op=$row1['op_male_new']+$row1['op_female_new']+$row1['op_male_old']+$row1['op_female_old'] ?></a></th>
                                            
                                            <td><a href="print_monthly_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=12&type=new&dept_name=Obstetrics and gynaecology" target="_blank"><?= $row1['obg_male_old'] + $row1['obg_female_old']?></a></td>
                                            <td><a href="print_monthly_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=12&type=old&dept_name=Obstetrics and gynaecology" target="_blank"><?= $row1['obg_male_new'] + $row1['obg_female_new']?></a></td>
                                            <th><a href="print_monthly_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=12&type=total&dept_name=Obstetrics and gynaecology" target="_blank"><?= $obg=$row1['obg_male_old']+$row1['obg_female_old']+$row1['obg_male_new']+$row1['obg_female_new']?></a></th>
                                            
                                            <td><a href="print_monthly_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=17&type=new&dept_name=Emergency" target="_blank"><?= $row1['em_male_old'] + $row1['em_female_old']?></a></td>
                                            <td><a href="print_monthly_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=17&type=old&dept_name=Emergency" target="_blank"><?= $row1['em_male_new'] + $row1['em_female_new']?></a></td>
                                            <th><a href="print_monthly_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=17&type=total&dept_name=Emergency" target="_blank"><?= $em=$row1['em_male_new']+$row1['em_female_new']+$row1['em_male_old']+$row1['em_female_old']?></a></th>
                                           
                                           <td><a href="print_monthly_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=18&type=new&dept_name=ENT" target="_blank"><?= $row1['en_male_old'] + $row1['en_female_old']?></a></td>
                                            <td><a href="print_monthly_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=18&type=old&dept_name=ENT" target="_blank"><?= $row1['en_male_new'] + $row1['en_female_new']?></a></td>
                                            <th><a href="print_monthly_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=18&type=total&dept_name=ENT" target="_blank"><?= $en=$row1['en_male_new']+$row1['en_female_new']+$row1['en_male_old']+$row1['en_female_old']?></a></th>
                                           
                                        <td><a href="print_monthly_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=20&type=new&dept_name=Physiotherapy" target="_blank"><?= $row1['ph_male_old'] + $row1['ph_female_old']?></a></td>
                                            <td><a href="print_monthly_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=20&type=old&dept_name=Physiotherapy" target="_blank"><?= $row1['ph_male_new'] + $row1['ph_female_new']?></a></td>
                                            <th><a href="print_monthly_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=20&type=total&dept_name=Physiotherapy" target="_blank"><?= $ph=$row1['ph_male_new']+$row1['ph_female_new']+$row1['ph_male_old']+$row1['ph_female_old']?></a></th>
                                           
                                       
                                             <th><?= $gm+$pd+$ps+$sk+$tb+$gs+$or+$op+$obg+$em+$en+$dn +$ph?></th>
                                           </tr>
                                           <?php 
                                               
                                     $total +=$gm+$pd+$ps+$sk+$tb+$gs+$or+$op+$obg+$em+$en+$dn+$ph;       
                                           $gm_male_new +=$row1['gm_male_old'];
$gm_male_old +=$row1['gm_male_new'];
$gm_female_new +=$row1['gm_female_old'];
$gm_female_old +=$row1['gm_female_new'];
$pd_male_new +=$row1['pd_male_old'];
$pd_male_old +=$row1['pd_male_new'];
$pd_female_new +=$row1['pd_female_old'];
$pd_female_old +=$row1['pd_female_new'];
$ps_male_new +=$row1['ps_male_old'];
$ps_male_old +=$row1['ps_male_new'];
$ps_female_new +=$row1['ps_female_old'];
$ps_female_old +=$row1['ps_female_new'];
$sk_male_new +=$row1['sk_male_old'];
$sk_male_old +=$row1['sk_male_new'];
$sk_female_new +=$row1['sk_female_old'];
$sk_female_old +=$row1['sk_female_new'];
$gs_male_new +=$row1['gs_male_old'];
$gs_male_old +=$row1['gs_male_new'];
$gs_female_new +=$row1['gs_female_old'];
$gs_female_old +=$row1['gs_female_new'];
$or_male_new +=$row1['or_male_old'];
$or_male_old +=$row1['or_male_new'];
$or_female_new +=$row1['or_female_old'];
$or_female_old +=$row1['or_female_new'];
$op_male_new +=$row1['op_male_old'];
$op_male_old +=$row1['op_male_new'];
$op_female_new +=$row1['op_female_old'];
$op_female_old +=$row1['op_female_new'];
$obg_male_new +=$row1['obg_male_old'];
$obg_male_old +=$row1['obg_male_new'];
$obg_female_new +=$row1['obg_female_old'];
$obg_female_old +=$row1['obg_female_new'];
$em_male_new +=$row1['em_male_old'];
$em_male_old +=$row1['em_male_new'];
$em_female_new +=$row1['em_female_old'];
$em_female_old +=$row1['em_female_new'];
$en_male_new +=$row1['en_male_old'];
$en_male_old += $row1['en_male_new'];
$en_female_new +=$row1['en_female_old'];
$en_female_old += $row1['en_female_new'];
$dn_male_new +=$row1['dn_male_old'];
$dn_male_old +=$row1['dn_male_new'];
$dn_female_new +=$row1['dn_female_old'];
$dn_female_old +=$row1['dn_female_new'];
$ph_male_new +=$row1['ph_male_old'];
$ph_male_old +=$row1['ph_male_new'];
$ph_female_new +=$row1['ph_female_old'];
$ph_female_old +=$row1['ph_female_new'];
                                           
                                           
                                           } ?>
</tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>Total</th>
                                                   <th><?= $gm_male_new + $gm_female_new?></th>
<th><?= $gm_male_old + $gm_female_old?></th>
<th><?= $gm_male_new+$gm_male_old+$gm_female_new+$gm_female_old?></th>

<th><?= $pd_male_new + $pd_female_new?></th>
<th><?= $pd_male_old + $pd_female_old?></th>
<th><?= $pd_male_new+$pd_male_old+$pd_female_new+$pd_female_old?></th>

<th><?= $ps_male_new  + $ps_female_new?></th>
<th><?= $ps_male_old + $ps_female_old?></th>
<th><?= $ps_male_new+$ps_male_old+$ps_female_new+$ps_female_old?></th>

<th><?= $sk_male_new  +$sk_female_new?></th>
<th><?= $sk_male_old  + $sk_female_old?></th>
<th><?= $sk_male_new+$sk_male_old+$sk_female_new+$sk_female_old?></th>

<th><?= $gs_male_new  + $gs_female_new?></th>
<th><?= $gs_male_old + $gs_female_old?></th>
<th><?= $gs_male_new+$gs_male_old+$gs_female_new+$gs_female_old?></th>

<th><?= $or_male_new  + $or_female_new?></th>
<th><?= $or_male_old +  $or_female_old?></th>
<th><?= $or_male_new+$or_male_old+$or_female_new+$or_female_old?></th>

<th><?= $op_male_new  + $op_female_new?></th>
<th><?= $op_male_old +  $op_female_old?></th>
<th><?= $op_male_new+$op_male_old+$op_female_new+$op_female_old?></th>

<th><?= $obg_male_new + $obg_female_new?></th>
<th><?= $obg_male_old + $obg_female_old?></th>
<th><?= $obg_male_new+$obg_male_old+$obg_female_new+$obg_female_old?></th>

<th><?= $em_male_new + $em_female_new?></th>
<th><?= $em_male_old  + $em_female_old?></th>
<th><?= $em_male_new+$em_male_old+$em_female_new+$em_female_old?></th>

<th><?= $en_male_new  + $en_female_new?></th>
<th><?= $en_male_old  + $en_female_old?></th>
<th><?= $en_male_new+$en_male_old+$en_female_new+$en_female_old?></th>

<th><?= $dn_male_new + $dn_female_new?></th>
<th><?= $dn_male_old  + $dn_female_old?></th>
<th><?= $dn_male_new+$dn_male_old+$dn_female_new+$dn_female_old?></th>

<th><?= $ph_male_new +  $ph_female_new?></th>
<th><?= $ph_male_old  + $ph_female_old?></th>
<th><?= $ph_male_new+$ph_male_old+$ph_female_new+$ph_female_old?></th>

<th><?= $total?></th>
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