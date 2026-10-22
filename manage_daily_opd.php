<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  

include('model/reportClass.php');
$newReport = new report;

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
   <link href="./vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">


       <!-- DataTables -->
    
        <link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/datatables.net-select-bs4/css/select.bootstrap4.min.css" rel="stylesheet" type="text/css" />

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
<h4 class="text-uppercase mb-5"> Daily OPD Registry Report</h4>


   
   <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                  <form action="#pills-home-tab" method="POST">
    <div class="row">
        <div class="col-md-4">
           <div class="form-group">
                  <label for="exampleInputEmail1"> From Date </label>
                  <input type="date" class="form-control"  name="from" id="from" required value="<?= $from?>">
                  
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                   <label for="exampleInputEmail1"> To Date  </label>
                   <input type="date" class="form-control"  name="to" id="to" required value="<?= $to?>">
                   
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
                       <a href="print_daily_opd_registry.php?from=<?= $from?>&to=<?= $to?>" class="badge badge-pill badge-danger" target="_blank"data-toggle="tooltip" data-placement="top" title="Print  Report"><i class="icofont-print"></i> Print</a>
                       
                   </div>
                   

                    <div class="table-responsive">
                    <table id="datatable-buttons2" class="table text-wrap table-bordered text-center  no-footer " role="grid" aria-describedby="datatable-buttons_info">
                                        <thead >
                                            <tr role="row">
                                            <th rowspan="1" colspan="1"></th>
                                            <?php
                                            $count=0;
                                            $res=$newReport->getDepartment();
                                            while($row=mysqli_fetch_assoc($res))
                                            {
                                            ?>
                                            <th  rowspan="1"><?= $row['dept_name']?></th>
                                            <th></th>
                                            <th></th>
                                            <?php
                                            
                                            $count++;
                                            } ?>
                                            
                                            <th colspan="3" rowspan="1">Total</th>
                                            </tr>
                                            <tr role="row">
                                            <th rowspan="1" colspan="1">Date</th>
                                              <?php for($i=0;$i<=$count;$i++) { ?>  
                                                <th rowspan="1" colspan="1">  New </th>
                                              <th rowspan="1" colspan="1">  Old </th>
                                              <th rowspan="1" colspan="1">Tot</th>
                                              <?php } ?>
                                              </tr>
                                         
                                        </thead>
                                        <tbody class=" text-black">
                                           <?php 
                                           $totgm=$totpd=$totps=$totsk=$tottb=$totgs=$totor=$totop=$totobg=$totem=$toten=$totdn=$totan=$totmph=$newtot=$oldtot=$total=0;
                                           $totgmo=$totpdo=$totpso=$totsko=$tottbo=$totgso=$totoro=$totopo=$totobgo=$totemo=$toteno=$totdno=$totano=$totmpho=0;
                                           $totgmn=$totpdn=$totpsn=$totskn=$tottbn=$totgsn=$totorn=$totopn=$totobgn=$totemn=$totenn=$totdnn=$totann=$totmphn=0;
                                            $dates = $newReport->getBetweenDates($from, $to);
                                               for($i=0;$i<count($dates);$i++) {
                                           $gm=$pd=$ps=$sk=$tb=$gs=$or=$op=$obg=$em=$en=$dn=$an=$ph=0; ?>
                                           <tr role="row">
                                            <th rowspan="1" colspan="1"><?= date("d.m.Y",strtotime($dates[$i]))?></th>
                                            <?php $rest=$newReport->getDeptDailyReport($dates[$i]);
                                            $row1=mysqli_fetch_assoc($rest);
                                            $year = date("Y", strtotime($dates[$i]));
                                            ?> 
                                          
                                          <td rowspan="1" colspan="1"><a href="print_daily_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=1&type=new&dept_name=General Medicine" target="_blank"><?= $row1['gm_male_old'] + $row1['gm_female_old']?></a></td>
                                          <td rowspan="1" colspan="1"><a href="print_daily_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=1&type=old&dept_name=General Medicine" target="_blank"><?= $row1['gm_male_new'] + $row1['gm_female_new']?></a></td>
                                          <th rowspan="1" colspan="1"><a href="print_daily_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=1&type=total&dept_name=General Medicine" target="_blank"><?= $gm=$row1['gm_male_new']+$row1['gm_female_new']+$row1['gm_male_old']+$row1['gm_female_old']?></a></th>
                                           
                                           
                                          
                                            
                                            <td rowspan="1" colspan="1"><a href="print_daily_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=2&type=new&dept_name=Pediatrics" target="_blank"><?= $row1['pd_male_old']+$row1['pd_female_old']?></a></td>
                                            <td rowspan="1" colspan="1"><a href="print_daily_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=2&type=old&dept_name=Pediatrics" target="_blank"><?= $row1['pd_male_new']+$row1['pd_female_new']?></a></td>
                                           <th rowspan="1" colspan="1"><a href="print_daily_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=2&type=total&dept_name=Pediatrics" target="_blank"><?= $pd=$row1['pd_male_new']+$row1['pd_female_new']+$row1['pd_male_old']+$row1['pd_female_old']?></a></th>
                                           
                                           
                                            <td rowspan="1" colspan="1" ><a href="print_daily_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=3&type=new&dept_name=Psychiatry" target="_blank"><?= $row1['ps_male_old']+$row1['ps_female_old']?></a></td>
                                            <td rowspan="1" colspan="1"><a href="print_daily_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=3&type=old&dept_name=Psychiatry" target="_blank"><?= $row1['ps_male_new']+$row1['ps_female_new']?></a></td>
                                            <th rowspan="1" colspan="1"><a href="print_daily_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=3&type=total&dept_name=Psychiatry" target="_blank"><?= $ps=$row1['ps_male_new']+$row1['ps_female_new']+$row1['ps_male_old']+$row1['ps_female_old']?></a></th>
                                           
                                           
                                            <td rowspan="1" colspan="1"><a href="print_daily_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=4&type=new&dept_name=SKIN" target="_blank"><?= $row1['sk_male_old'] + $row1['sk_female_old']?></a></td>
                                            <td rowspan="1" colspan="1"><a href="print_daily_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=4&type=old&dept_name=SKIN" target="_blank"><?= $row1['sk_male_new'] + $row1['sk_female_new']?></a></td>
                                           <th rowspan="1" colspan="1"><a href="print_daily_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=4&type=total&dept_name=SKIN" target="_blank"><?= $sk=$row1['sk_male_new']+$row1['sk_female_new']+$row1['sk_male_old']+$row1['sk_female_old']?></a></th>
                                           
                                           
                                            <td rowspan="1" colspan="1"><a href="print_daily_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=6&type=new&dept_name=General Surgery" target="_blank"><?= $row1['gs_male_old'] + $row1['gs_female_old']?></a></td>
                                            <td rowspan="1" colspan="1"><a href="print_daily_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=6&type=old&dept_name=General Surgery" target="_blank"><?= $row1['gs_male_new'] + $row1['gs_female_new']?></a></td>
                                            <th rowspan="1" colspan="1"><a href="print_daily_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=6&type=total&dept_name=General Surgery" target="_blank"><?= $gs=$row1['gs_male_new']+$row1['gs_female_new']+$row1['gs_male_old']+$row1['gs_female_old']?></a></th>
                                            
                                            
                                              <td rowspan="1" colspan="1"><a href="print_daily_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=7&type=new&dept_name=Orthopedics" target="_blank"><?= $row1['or_male_old'] + $row1['or_female_old']?></a></td>
                                            <td rowspan="1" colspan="1"><a href="print_daily_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=7&type=old&dept_name=Orthopedics" target="_blank"><?= $row1['or_male_new'] + $row1['or_female_new']?></a></td>
                                            <th rowspan="1" colspan="1"><a href="print_daily_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=7&type=total&dept_name=Orthopedics" target="_blank"><?= $or=$row1['or_male_new']+$row1['or_female_new']+$row1['or_male_old']+$row1['or_female_old'] ?></a></th>
                                            
                                              <td rowspan="1" colspan="1"><a href="print_daily_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=8&type=new&dept_name=Dentistry" target="_blank"><?= $row1['dn_male_old'] + $row1['dn_female_old']?></a></td>
                                            <td rowspan="1" colspan="1"><a href="print_daily_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=8&type=old&dept_name=Dentistry" target="_blank"><?= $row1['dn_male_new'] + $row1['dn_female_new']?></a></td>
                                            <th rowspan="1" colspan="1"><a href="print_daily_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=8&type=total&dept_name=Dentistry" target="_blank"><?= $dn=$row1['dn_male_new']+$row1['dn_female_new']+$row1['dn_male_old']+$row1['dn_female_old'] ?></a></th>
                                            
                                              <td rowspan="1" colspan="1"><a href="print_daily_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=11&type=new&dept_name=Ophthalmology" target="_blank"><?= $row1['op_male_old'] + $row1['op_female_old']?></a></td>
                                            <td rowspan="1" colspan="1"><a href="print_daily_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=11&type=old&dept_name=Ophthalmology" target="_blank"><?= $row1['op_male_new'] + $row1['op_female_new']?></a></td>
                                            <th rowspan="1" colspan="1"><a href="print_daily_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=11&type=total&dept_name=Ophthalmology" target="_blank"><?= $op=$row1['op_male_new']+$row1['op_female_new']+$row1['op_male_old']+$row1['op_female_old'] ?></a></th>
                                            
                                            <td rowspan="1" colspan="1"><a href="print_daily_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=12&type=new&dept_name=Obstetrics and gynaecology" target="_blank"><?= $row1['obg_male_old'] + $row1['obg_female_old']?></a></td>
                                            <td rowspan="1" colspan="1"><a href="print_daily_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=12&type=old&dept_name=Obstetrics and gynaecology" target="_blank"><?= $row1['obg_male_new'] + $row1['obg_female_new']?></a></td>
                                            <th rowspan="1" colspan="1"><a href="print_daily_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=12&type=total&dept_name=Obstetrics and gynaecology" target="_blank"><?= $obg=$row1['obg_male_old']+$row1['obg_female_old']+$row1['obg_male_new']+$row1['obg_female_new']?></a></th>
                                            
                                            <td rowspan="1" colspan="1"><a href="print_daily_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=17&type=new&dept_name=Emergency" target="_blank"><?= $row1['em_male_old'] + $row1['em_female_old']?></a></td>
                                            <td rowspan="1" colspan="1"><a href="print_daily_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=17&type=old&dept_name=Emergency" target="_blank"><?= $row1['em_male_new'] + $row1['em_female_new']?></a></td>
                                            <th rowspan="1" colspan="1"><a href="print_daily_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=17&type=total&dept_name=Emergency" target="_blank"><?= $em=$row1['em_male_new']+$row1['em_female_new']+$row1['em_male_old']+$row1['em_female_old']?></a></th>
                                           
                                           <td rowspan="1" colspan="1"><a href="print_daily_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=18&type=new&dept_name=ENT" target="_blank"><?= $row1['en_male_old'] + $row1['en_female_old']?></a></td>
                                            <td rowspan="1" colspan="1"><a href="print_daily_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=18&type=old&dept_name=ENT" target="_blank"><?= $row1['en_male_new'] + $row1['en_female_new']?></a></td>
                                            <th rowspan="1" colspan="1"><a href="print_daily_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=18&type=total&dept_name=ENT" target="_blank"><?= $en=$row1['en_male_new']+$row1['en_female_new']+$row1['en_male_old']+$row1['en_female_old']?></a></th>
                                           
                                       <td rowspan="1" colspan="1"><a href="print_daily_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=20&type=new&dept_name=Physiotherapy" target="_blank"><?= $row1['ph_male_old'] + $row1['ph_female_old']?></a></td>
                                            <td rowspan="1" colspan="1"><a href="print_daily_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=20&type=old&dept_name=Physiotherapy" target="_blank"><?= $row1['ph_male_new'] + $row1['ph_female_new']?></a></td>
                                            <th rowspan="1" colspan="1"><a href="print_daily_opd.php?i=<?= $i; ?>&year=<?=$year; ?>&dept=20&type=total&dept_name=Physiotherapy" target="_blank"><?= $ph=$row1['ph_male_new']+$row1['ph_female_new']+$row1['ph_male_old']+$row1['ph_female_old']?></a></th>
                                           
                                        <th rowspan="1" colspan="1"><?= $row1['gm_male_old'] + $row1['gm_female_old'] + $row1['pd_male_old']+$row1['pd_female_old'] + $row1['ps_male_old']+$row1['ps_female_old'] + $row1['sk_male_old'] + $row1['sk_female_old'] + $row1['gs_male_old'] + $row1['gs_female_old'] + $row1['or_male_old'] + $row1['or_female_old'] + $row1['dn_male_old'] + $row1['dn_female_old'] + $row1['an_male_old'] + $row1['an_female_old'] + $row1['op_male_old'] + $row1['op_female_old'] + $row1['obg_male_old'] + $row1['obg_female_old'] + $row1['em_male_old'] + $row1['em_female_old'] + $row1['en_male_old'] + $row1['en_female_old'] + $row1['ph_male_old'] + $row1['ph_female_old'] ?></th>
                                        <th rowspan="1" colspan="1"><?= $row1['gm_male_new'] + $row1['gm_female_new'] + $row1['pd_male_new']+$row1['pd_female_new'] + $row1['ps_male_new']+$row1['ps_female_new'] + $row1['sk_male_new'] + $row1['sk_female_new'] + $row1['gs_male_new'] + $row1['gs_female_new'] + $row1['or_male_new'] + $row1['or_female_new'] + $row1['dn_male_new'] + $row1['dn_female_new'] + $row1['an_male_new'] + $row1['an_female_new'] + $row1['op_male_new'] + $row1['op_female_new'] + $row1['obg_male_new'] + $row1['obg_female_new'] + $row1['em_male_new'] + $row1['em_female_new'] + $row1['en_male_new'] + $row1['en_female_new'] + $row1['ph_male_new'] + $row1['ph_female_new'] ?></th>  
                                          <th rowspan="1" colspan="1"><?= $gm+$pd+$ps+$sk+$tb+$gs+$or+$op+$obg+$em+$en+$dn+$an+$ph?></th>
                                           </tr>
                                           <?php 
                                           $totgmo +=$row1['gm_male_old'] + $row1['gm_female_old'];
$totpdo +=$row1['pd_male_old']+$row1['pd_female_old'] ;
$totpso +=$row1['ps_male_old']+$row1['ps_female_old'];
$totsko +=$row1['sk_male_old'] + $row1['sk_female_old'] ;
$totgso +=$row1['gs_male_old'] + $row1['gs_female_old'] ;
$totoro +=$row1['or_male_old'] + $row1['or_female_old'];
$totdno +=$row1['dn_male_old'] + $row1['dn_female_old'];
$totopo +=$row1['op_male_old'] + $row1['op_female_old'];
$totobgo +=$row1['obg_male_old'] + $row1['obg_female_old'] ;
$totemo +=$row1['em_male_old'] + $row1['em_female_old'];
$toteno +=$row1['en_male_old'] + $row1['en_female_old'];
$totano +=$row1['an_male_old'] + $row1['an_female_old'];
$totmpho +=$row1['ph_male_old'] + $row1['ph_female_old'];
$totgmn +=$row1['gm_male_new'] + $row1['gm_female_new'];
$totpdn +=$row1['pd_male_new']+$row1['pd_female_new'] ;
$totpsn +=$row1['ps_male_new']+$row1['ps_female_new'];
$totskn +=$row1['sk_male_new'] + $row1['sk_female_new'] ;
$totgsn +=$row1['gs_male_new'] + $row1['gs_female_new'] ;
$totorn +=$row1['or_male_new'] + $row1['or_female_new'];
$totdnn +=$row1['dn_male_new'] + $row1['dn_female_new'];
$totopn +=$row1['op_male_new'] + $row1['op_female_new'];
$totobgn +=$row1['obg_male_new'] + $row1['obg_female_new'] ;
$totemn +=$row1['em_male_new'] + $row1['em_female_new'];
$totenn +=$row1['en_male_new'] + $row1['en_female_new'];
$totann +=$row1['an_male_new'] + $row1['an_female_new'];
$totmphn +=$row1['ph_male_new'] + $row1['ph_female_new'];

                                           } 
                                           $oldtot=$totgmo+$totpdo+$totpso+$totsko+$totdno+$totgso+$totoro+$totopo+$totobgo+$totemo+$toteno+$totano+$totmpho;
                                           $newtot =$totgmn+$totpdn+$totpsn+$totskn+$totdnn+$totgsn+$totorn+$totopn+$totobgn+$totemn+$totenn+$totann+$totmphn;

                                           ?>
                                           <tr role="row">
                                               <th rowspan="1" colspan="1">Total</th>
                                               <th rowspan="1" colspan="1"><?= $totgmo?></th>
                                               <th rowspan="1" colspan="1"><?= $totgmn?></th>
                                               <th rowspan="1" colspan="1"><?php $total +=$totgmo+$totgmn; echo $totgmo+$totgmn;?></th>
                                               <th rowspan="1" colspan="1"><?= $totpdo?></th>
                                               <th rowspan="1" colspan="1"><?= $totpdn?></th>
                                               <th rowspan="1" colspan="1"><?php $total +=$totpdo+$totpdn; echo $totpdo+$totpdn;?></th>
                                               <th rowspan="1" colspan="1"><?= $totpso?></th>
                                               <th rowspan="1" colspan="1"><?= $totpsn?></th>
                                               <th rowspan="1" colspan="1"><?php $total +=$totpso+$totpsn; echo $totpso+$totpsn;?></th>
                                               <th rowspan="1" colspan="1"><?= $totsko?></th>
                                               <th rowspan="1" colspan="1"><?= $totskn?></th>
                                              <th rowspan="1" colspan="1"><?php $total +=$totsko+$totskn; echo $totsko+$totskn;?></th>
                                               <th rowspan="1" colspan="1"><?= $totgso?></th>
                                               <th rowspan="1" colspan="1"><?= $totgsn?></th>
                                               <th rowspan="1" colspan="1"><?php $total +=$totgso+$totgsn; echo $totgso+$totgsn;?></th>
                                               <th rowspan="1" colspan="1"><?= $totoro?></th>
                                               <th rowspan="1" colspan="1"><?= $totorn?></th>
                                               <th rowspan="1" colspan="1"><?php $total +=$totoro+$totorn; echo $totoro+$totorn;?></th>
                                               <th rowspan="1" colspan="1"><?= $totdno?></th>
                                               <th rowspan="1" colspan="1"><?= $totdnn?></th>
                                               <th rowspan="1" colspan="1"><?php $total +=$totdno+$totdnn; echo $totdno+$totdnn;?></th>
                                              
                                               <th rowspan="1" colspan="1"><?= $totopo?></th>
                                               <th rowspan="1" colspan="1"><?= $totopn?></th>
                                               <th rowspan="1" colspan="1"><?php $total +=$totopo+$totopn; echo $totopo+$totopn;?></th>
                                               <th rowspan="1" colspan="1"><?= $totobgo?></th>
                                               <th rowspan="1" colspan="1"><?= $totobgn?></th>
                                               <th rowspan="1" colspan="1"><?php $total +=$totobgo+$totobgn; echo $totobgo+$totobgn;?></th>
                                               <th rowspan="1" colspan="1"><?= $totemo?></th>
                                               <th rowspan="1" colspan="1"><?= $totemn?></th>
                                               <th rowspan="1" colspan="1"><?php $total +=$totemo+$totemn; echo $totemo+$totemn;?></th>
                                               <th rowspan="1" colspan="1"><?= $toteno?></th>
                                               <th rowspan="1" colspan="1"><?= $totenn?></th>
                                               <th rowspan="1" colspan="1"><?php $total +=$toteno+$totenn; echo $toteno+$totenn;?></th>
                                               <th rowspan="1" colspan="1"><?= $totmpho?></th>
                                               <th rowspan="1" colspan="1"><?= $totmphn?></th>
                                               <th rowspan="1" colspan="1"><?php $total +=$totmpho+$totmphn; echo $totmpho+$totmphn;?></th>
                                               <th rowspan="1" colspan="1"><?= $oldtot?></th>
                                               <th rowspan="1" colspan="1"><?= $newtot?></th>
                                               <th rowspan="1" colspan="1"><?= $total?></th>
                                           </tr>
</tbody>
                                          
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

        <!-- Datatable init js -->
        <script src="assets/js/pages/datatables.init.js"></script>
     <script>
$(document).ready(function() {
    $('#datatable-buttons2').DataTable({
        dom: 'Bfrtip',
        responsive: true,
        pageLength: 25,
        buttons: [
            {
                text: 'Export to Excel',
                action: function () {
                    var wb = XLSX.utils.book_new();
                    wb.SheetNames.push('Sheet1');
                    var wsData = [['', 'General Medicine', '', '', 'Pediatrics', '', '', 'Psychiatry', '', '', 'SKIN', '', '', 'Surgery', '', '', 'Orthopedics', '', '', 'Dentistry', '', '', 'Anestheslogy', '', '', 'Ophthalmology', '', '', 'Obstetrics and gynaecology', '', '', 'Emergency', '', '', 'ENT', '', '', 'Physiotherapy', '', '', 'Total']];
                    $('#datatable-buttons2 thead tr').each(function() {
                        var row = [];
                        $(this).find('th').each(function() {
                            row.push($(this).text());
                        });
                        wsData.push(row);
                    });
                    $('#datatable-buttons2 tbody tr').each(function() {
                        var row = [];
                        $(this).find('th, td').each(function() {
                            row.push($(this).text());
                        });
                        wsData.push(row);
                    });
                    var ws = XLSX.utils.aoa_to_sheet(wsData);
                    wb.Sheets['Sheet1'] = ws;
                    var wbout = XLSX.write(wb, {bookType: 'xlsx', type: 'binary'});
                    function s2ab(s) {
                        var buf = new ArrayBuffer(s.length);
                        var view = new Uint8Array(buf);
                        for (var i=0; i<s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
                        return buf;
                    }
                    saveAs(new Blob([s2ab(wbout)],{type:"application/octet-stream"}), 'OP_Report.xlsx');
                }
            }
        ]
    });
});


</script>


  

</body>

</html> 