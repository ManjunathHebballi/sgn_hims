<?php 

require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");
include('model/ipClass.php');
$newOP = new addIP;
  $from=$_REQUEST['fromdate'];
   $to=$_REQUEST['todate'];
$dept=$newOP->selectIPreg1($from,$to);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#319197">

    <title>Print Address Graph</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">

    <link href="./css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" ></script>
 <script>window.onload = function () {
    window.print();

} 
</script> 

    

    <style>
        p{
            font-size:0.8rem;
            font-weight:600;
          
    line-height: 0.9;

        }
        body{
            background-color:white;
        }
        
        .mb{
            
            margin-bottom: 1.9rem !important
        }
        
        .mt{
            
            margin-top: 0.9rem !important
        }
    </style>
     

</head>

<body>


  


    <!--**********************************
        Main wrapper start
    ***********************************-->
   
   
    



              
<div class="container-fluid mt-5">
    
    <div class="row">
        <div class="col-md-12">
             <a href="#" onclick="window.print();return false;"    data-toggle="tooltip" title="Print  Invoice" class=" d-print-none btn btn-danger btn-bold  float-right mt-3 mt-lg-0"><i class="icofont-print"></i> Print</a>
     
        </div>
        
            <?php 
           
            
                                             while($res=mysqli_fetch_assoc($dept))
                                         
                                            { ?>
        <div class="col-sm-4 mb p-3 mt ">
            <div class="d-flex justify-content-between">
                
                <p>MRD IP No. <?= $res['ipd_no']?></p>
            </div>
            <div class="d-flex justify-content-start">
               <p  class="mr-5"><?= $res['name']?></p>  
                
                <p><?= substr($res['gender'],0,1)."/". $res['age']?></p>
            </div>
            <p><?= $res['unit']?></p>
           
            <p> Adm.Date : <?= date("d.m.Y",strtotime($res['admission_date']))?></p>
            <!--<p>Trf. To Bed No: _________/__________ /___________</p>-->
        </div>
        
        <?php  }?>
        
     
    </div>
    
</div>
    </div>

</div>


    <!--**********************************
        Scripts
    ***********************************-->
    <!--  vendors -->

    <script src="./js/custom.min.js"></script>



</body>

</html>