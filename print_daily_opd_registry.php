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
    <title>Print Daily OPD Report  </title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
        <link rel="preconnect" href="https://fonts.googleapis.com">

        <link rel="stylesheet" href="print_style.css">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">

</head>


<style>
   td{

    font-size:14px
   }

  @media print{
      
      @page {
      
      size: landscape}

    .dark{
background-color:black;

-webkit-print-color-adjust:exact;
    }
   }
   
   th{
       font-weight:600;
   }
   
   .table th, .table td {
       
       padding:3px;
       font-size:0.8rem;
      text-align: center; 
    vertical-align: middle;
   }
</style>

<body style=" font-family: 'Poppins', sans-serif;" onload="window.print()">

  

    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">





  

        <!--**********************************
            Content body start
        ***********************************-->
        
            <!-- row -->







    <div class="row">

    <div class="col-md-12">
       
            <div class="container">
            
                   
                        <div class="d-flex align-items-center ml-3">
                            <img src="assets/logo.png" class="" alt="" width="150">

                        
                        <div class=" ml-5">
                        <h5 class="text-center text-black"><?= $_SESSION['college_society']?></h5>
                        <h3 class="text-center text-black"><?= $_SESSION['college_name']?></h3>
                        <h5 class="text-center text-black"><?= $_SESSION['college_location']?></h5>
                        <h5 class="text-center text-black"><?= $_SESSION['college_phone']?></h5>

                        </div>
                   </div>

                    
                    <h4 class="text-center text-black">Daily OPD Report 
                      
                    </h4>

                    <div class="d-flex justify-content-between">
                        <p>Report Period From: <?= date("d.m.Y",strtotime($from))?> To <?= date("d.m.Y",strtotime($to))?></p> 
                    <p>Report Generated on: <?= date("d.m.Y")?></p></div>
                

          
        </div>



    
                                     <table class="table text-wrap table-bordered text-center  no-footer " role="grid" aria-describedby="datatable-buttons_info">
                                        <thead >
                                            <tr role="row">
                                            <th></th>
                                            <?php
                                            $count=0;
                                            $res=$newReport->getDepartment();
                                            while($row=mysqli_fetch_assoc($res))
                                            {
                                            ?>
                                            <th  colspan="3"><?= $row['dept_name']?></th>
                                           
                                            <?php
                                            
                                            $count++;
                                            } ?>
                                            
                                            <th colspan="3" rowspan="1">Total</th>
                                            </tr>
                                            <tr role="row">
                                            <th >Date</th>
                                              <?php for($i=0;$i<=$count;$i++) { ?>  
                                                <th >  New </th>
                                              <th >  Old </th>
                                              <th >Tot</th>
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
                                            <th ><?= date("d.m.Y",strtotime($dates[$i]))?></th>
                                            <?php $rest=$newReport->getDeptDailyReport($dates[$i]);
                                            $row1=mysqli_fetch_assoc($rest)?> 
                                          
                                          <td ><?= $row1['gm_male_old'] + $row1['gm_female_old']?></td>
                                          <td ><?= $row1['gm_male_new'] + $row1['gm_female_new']?></td>
                                          <th ><?= $gm=$row1['gm_male_new']+$row1['gm_female_new']+$row1['gm_male_old']+$row1['gm_female_old']?></th>
                                           
                                           
                                          
                                            
                                            <td ><?= $row1['pd_male_old']+$row1['pd_female_old']?></td>
                                            <td ><?= $row1['pd_male_new']+$row1['pd_female_new']?></td>
                                           <th ><?= $pd=$row1['pd_male_new']+$row1['pd_female_new']+$row1['pd_male_old']+$row1['pd_female_old']?></th>
                                           
                                           
                                            <td  ><?= $row1['ps_male_old']+$row1['ps_female_old']?></td>
                                            <td ><?= $row1['ps_male_new']+$row1['ps_female_new']?></td>
                                            <th ><?= $ps=$row1['ps_male_new']+$row1['ps_female_new']+$row1['ps_male_old']+$row1['ps_female_old']?></th>
                                           
                                           
                                            <td ><?= $row1['sk_male_old'] + $row1['sk_female_old']?></td>
                                            <td ><?= $row1['sk_male_new'] + $row1['sk_female_new']?></td>
                                           <th ><?= $sk=$row1['sk_male_new']+$row1['sk_female_new']+$row1['sk_male_old']+$row1['sk_female_old']?></th>
                                           
                                           
                                            <td ><?= $row1['gs_male_old'] + $row1['gs_female_old']?></td>
                                            <td ><?= $row1['gs_male_new'] + $row1['gs_female_new']?></td>
                                            <th ><?= $gs=$row1['gs_male_new']+$row1['gs_female_new']+$row1['gs_male_old']+$row1['gs_female_old']?></th>
                                            
                                            
                                              <td ><?= $row1['or_male_old'] + $row1['or_female_old']?></td>
                                            <td ><?= $row1['or_male_new'] + $row1['or_female_new']?></td>
                                            <th ><?= $or=$row1['or_male_new']+$row1['or_female_new']+$row1['or_male_old']+$row1['or_female_old'] ?></th>
                                            
                                              <td ><?= $row1['dn_male_old'] + $row1['dn_female_old']?></td>
                                            <td ><?= $row1['dn_male_new'] + $row1['dn_female_new']?></td>
                                            <th ><?= $dn=$row1['dn_male_new']+$row1['dn_female_new']+$row1['dn_male_old']+$row1['dn_female_old'] ?></th>
                                            
                                              <td ><?= $row1['op_male_old'] + $row1['op_female_old']?></td>
                                            <td ><?= $row1['op_male_new'] + $row1['op_female_new']?></td>
                                            <th ><?= $op=$row1['op_male_new']+$row1['op_female_new']+$row1['op_male_old']+$row1['op_female_old'] ?></th>
                                            
                                            <td ><?= $row1['obg_male_old'] + $row1['obg_female_old']?></td>
                                            <td ><?= $row1['obg_male_new'] + $row1['obg_female_new']?></td>
                                            <th ><?= $obg=$row1['obg_male_old']+$row1['obg_female_old']+$row1['obg_male_new']+$row1['obg_female_new']?></th>
                                            
                                            <td ><?= $row1['em_male_old'] + $row1['em_female_old']?></td>
                                            <td ><?= $row1['em_male_new'] + $row1['em_female_new']?></td>
                                            <th ><?= $em=$row1['em_male_new']+$row1['em_female_new']+$row1['em_male_old']+$row1['em_female_old']?></th>
                                           
                                           <td ><?= $row1['en_male_old'] + $row1['en_female_old']?></td>
                                            <td ><?= $row1['en_male_new'] + $row1['en_female_new']?></td>
                                            <th ><?= $en=$row1['en_male_new']+$row1['en_female_new']+$row1['en_male_old']+$row1['en_female_old']?></th>
                                           
                                       <td ><?= $row1['ph_male_old'] + $row1['ph_female_old']?></td>
                                            <td ><?= $row1['ph_male_new'] + $row1['ph_female_new']?></td>
                                            <th ><?= $ph=$row1['ph_male_new']+$row1['ph_female_new']+$row1['ph_male_old']+$row1['ph_female_old']?></th>
                                           
                                        <th ><?= $row1['gm_male_old'] + $row1['gm_female_old'] + $row1['pd_male_old']+$row1['pd_female_old'] + $row1['ps_male_old']+$row1['ps_female_old'] + $row1['sk_male_old'] + $row1['sk_female_old'] + $row1['gs_male_old'] + $row1['gs_female_old'] + $row1['or_male_old'] + $row1['or_female_old'] + $row1['dn_male_old'] + $row1['dn_female_old'] + $row1['an_male_old'] + $row1['an_female_old'] + $row1['op_male_old'] + $row1['op_female_old'] + $row1['obg_male_old'] + $row1['obg_female_old'] + $row1['em_male_old'] + $row1['em_female_old'] + $row1['en_male_old'] + $row1['en_female_old'] + $row1['ph_male_old'] + $row1['ph_female_old'] ?></th>
                                        <th ><?= $row1['gm_male_new'] + $row1['gm_female_new'] + $row1['pd_male_new']+$row1['pd_female_new'] + $row1['ps_male_new']+$row1['ps_female_new'] + $row1['sk_male_new'] + $row1['sk_female_new'] + $row1['gs_male_new'] + $row1['gs_female_new'] + $row1['or_male_new'] + $row1['or_female_new'] + $row1['dn_male_new'] + $row1['dn_female_new'] + $row1['an_male_new'] + $row1['an_female_new'] + $row1['op_male_new'] + $row1['op_female_new'] + $row1['obg_male_new'] + $row1['obg_female_new'] + $row1['em_male_new'] + $row1['em_female_new'] + $row1['en_male_new'] + $row1['en_female_new'] + $row1['ph_male_new'] + $row1['ph_female_new'] ?></th>  
                                          <th ><?= $gm+$pd+$ps+$sk+$tb+$gs+$or+$op+$obg+$em+$en+$dn+$an+$ph?></th>
                                           </tr>
                                           <?php 
                                           $totgmo +=$row1['gm_male_old'] + $row1['gm_female_old'];
$totpdo +=$row1['pd_male_old']+$row1['pd_female_old'] ;
$totpso +=$row1['ps_male_old']+$row1['ps_female_old'];
$totsko +=$row1['sk_male_old'] + $row1['sk_female_old'] ;
$totdno +=$row1['gs_male_old'] + $row1['gs_female_old'] ;
$totgso +=$row1['or_male_old'] + $row1['or_female_old'];
$totoro +=$row1['dn_male_old'] + $row1['dn_female_old'];
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
$totdnn +=$row1['gs_male_new'] + $row1['gs_female_new'] ;
$totgsn +=$row1['or_male_new'] + $row1['or_female_new'];
$totorn +=$row1['dn_male_new'] + $row1['dn_female_new'];
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
                                               <th >Total</th>
                                               <th ><?= $totgmo?></th>
                                               <th ><?= $totgmn?></th>
                                               <th ><?= $totgmo+$totgmn?></th>
                                               <th ><?= $totpdo?></th>
                                               <th ><?= $totpdn?></th>
                                               <th ><?= $totpdo+$totpdn?></th>
                                               <th ><?= $totpso?></th>
                                               <th ><?= $totpsn?></th>
                                               <th ><?= $totpso+$totpsn?></th>
                                               <th ><?= $totsko?></th>
                                               <th ><?= $totskn?></th>
                                              <th ><?= $totsko+$totskn?></th>
                                               <th ><?= $totgso?></th>
                                               <th ><?= $totgsn?></th>
                                               <th ><?= $totgso+$totgsn?></th>
                                               <th ><?= $totoro?></th>
                                               <th ><?= $totorn?></th>
                                               <th ><?= $totoro+$totorn?></th>
                                               <th ><?= $totdno?></th>
                                               <th ><?= $totdnn?></th>
                                               <th ><?= $totdno+$totdnn?></th>
                                               <th ><?= $totopo?></th>
                                               <th ><?= $totopn?></th>
                                               <th ><?= $totopo+$totopn?></th>
                                               <th ><?= $totobgo?></th>
                                               <th ><?= $totobgn?></th>
                                               <th ><?= $totobgo+$totobgn?></th>
                                               <th ><?= $totemo?></th>
                                               <th ><?= $totemn?></th>
                                               <th ><?= $totemo+$totemn?></th>
                                               <th ><?= $toteno?></th>
                                               <th ><?= $totenn?></th>
                                               <th ><?= $toteno+$totenn?></th>
                                               <th ><?= $totmpho?></th>
                                               <th ><?= $totmphn?></th>
                                               <th ><?= $totmpho+$totmphn?></th>
                                               <th ><?= $oldtot?></th>
                                               <th ><?= $newtot?></th>
                                               <th ><?= $total=$oldtot+$newtot?></th>
                                           </tr>
</tbody>
                                          
                                            </table>
                                            </div>
                                           
                                            <div class="row mt-5">
                                                <div class="col-md-12">
                                                    <button onclick="window.print()"class="btn btn-danger float-right d-print-none"> <i class="ri-printer-line"></i> Print </button>
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
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>
   

 


</body>

</html>