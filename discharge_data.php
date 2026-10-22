<?php 
require_once(__DIR__ . '/model/session_config.php');
if(!isset($_SESSION['loginid']))
{
echo "<script>window.location='index.php';</script>";
}

date_default_timezone_set("Asia/Kolkata");  
include('model/dbconnect.php');
 
if(isset($_REQUEST['submit']))
{
     $newlogin = new userlogin;                  
  $con=$newlogin->dbconnect();
   $week=date("l",strtotime($_REQUEST['date']));
  $year=date("Y",strtotime($_REQUEST['date']));
 
  $date=$_REQUEST['date']; 
  $discdate=date_create($date);
date_sub($discdate,date_interval_create_from_date_string("1 days"));
$discdate=date_format($discdate,"Y-m-d");
  
  $pd=$ps=$sk=$or=$op=$og=$sg=$en=$me=$em=0;
if(isset($_REQUEST['pd_number']))
{
    $pd=$_REQUEST['pd_number'];
}
if(isset($_REQUEST['ps_number']))
{
    $ps=$_REQUEST['ps_number'];
}

if(isset($_REQUEST['sk_number']))
{
    $sk=$_REQUEST['sk_number'];
}



if(isset($_REQUEST['or_number']))
{
    $or=$_REQUEST['or_number'];
}

if(isset($_REQUEST['op_number']))
{
    $op=$_REQUEST['op_number'];
}

if(isset($_REQUEST['og_number']))
{
    $og=$_REQUEST['og_number'];
}

if(isset($_REQUEST['sg_number']))
{
    $sg=$_REQUEST['sg_number'];
}

if(isset($_REQUEST['en_number']))
{
    $en=$_REQUEST['en_number'];
}

if(isset($_REQUEST['me_number']))
{
    $me=$_REQUEST['me_number'];
}

if(isset($_REQUEST['em_number']))
{
    $em=$_REQUEST['em_number'];
}
$big=max(array($pd,$ps,$sk,$or,$op,$og,$sg,$en,$me,$em));

//  print_r($_REQUEST);exit;
for($i=1;$i<=$big;$i++)
{
    if($i<=$pd){
$sql2="SELECT ipd_no,admission_date,name,age,gender,address,dept_id,bed_id,diagnosis FROM central_ipd WHERE discharge=0 and admission_date < '$discdate' and is_generated=1 and dept_id=2 order by rand() limit 1";
// echo $sql2;exit;
$res2=mysqli_query($con,$sql2);
       $row2=mysqli_fetch_assoc($res2);
       if($row2)
       {
            $query5="INSERT INTO `discharge`(`patient_id`, `patient_name`, `dept_id`, `age`, `sex`, `admission_date`, `discharge_date`, `diagnosis`,`created_by`) VALUES ('$row2[ipd_no]','$row2[name]','$row2[dept_id]','$row2[age]','$row2[gender]','$row2[admission_date]','$date','$row2[diagnosis]','$_SESSION[name]')";
        //   echo $query5;exit;
            mysqli_query($con,$query5);
            $query6="UPDATE bed SET status='active' where id='$row2[bed_id]'";
             mysqli_query($con,$query6);
             
             $query6="UPDATE central_ipd SET discharge=1 where ipd_no='$row2[ipd_no]'";
             mysqli_query($con,$query6);
       }
    }
    if($i<=$me){
$sql2="SELECT ipd_no,admission_date,name,age,gender,address,dept_id,bed_id,diagnosis FROM central_ipd WHERE discharge=0 and admission_date < '$discdate' and is_generated=1 and dept_id=1 order by rand() limit 1";
// echo $sql2;
$res2=mysqli_query($con,$sql2);
       $row2=mysqli_fetch_assoc($res2);
       if($row2)
       {
            $query5="INSERT INTO `discharge`(`patient_id`, `patient_name`, `dept_id`, `age`, `sex`, `admission_date`, `discharge_date`, `diagnosis`,`created_by`) VALUES ('$row2[ipd_no]','$row2[name]','$row2[dept_id]','$row2[age]','$row2[gender]','$row2[admission_date]','$date','$row2[diagnosis]','$_SESSION[name]')";
        //   echo $query5;exit;
            mysqli_query($con,$query5);
            $query6="UPDATE bed SET status='active' where id='$row2[bed_id]'";
             mysqli_query($con,$query6);
             
             $query6="UPDATE central_ipd SET discharge=1 where ipd_no='$row2[ipd_no]'";
             mysqli_query($con,$query6);
       }      
    }
    if($i<=$ps){
$sql2="SELECT ipd_no,admission_date,name,age,gender,address,dept_id,bed_id,diagnosis FROM central_ipd WHERE discharge=0 and admission_date < '$discdate' and is_generated=1 and dept_id=3 order by rand() limit 1";
// echo $sql2;
$res2=mysqli_query($con,$sql2);
    $row2=mysqli_fetch_assoc($res2);  
    if($row2)
       {
            $query5="INSERT INTO `discharge`(`patient_id`, `patient_name`, `dept_id`, `age`, `sex`, `admission_date`, `discharge_date`, `diagnosis`,`created_by`) VALUES ('$row2[ipd_no]','$row2[name]','$row2[dept_id]','$row2[age]','$row2[gender]','$row2[admission_date]','$date','$row2[diagnosis]','$_SESSION[name]')";
        //   echo $query5;exit;
            mysqli_query($con,$query5);
            $query6="UPDATE bed SET status='active' where id='$row2[bed_id]'";
             mysqli_query($con,$query6);
             
             $query6="UPDATE central_ipd SET discharge=1 where ipd_no='$row2[ipd_no]'";
             mysqli_query($con,$query6);
       }    
    }
    if($i<=$sk){
$sql2="SELECT ipd_no,admission_date,name,age,gender,address,dept_id,bed_id,diagnosis FROM central_ipd WHERE discharge=0 and admission_date < '$discdate' and is_generated=1 and dept_id=4 order by rand() limit 1";
// echo $sql2;
$res2=mysqli_query($con,$sql2);
       $row2=mysqli_fetch_assoc($res2);
       if($row2)
       {
            $query5="INSERT INTO `discharge`(`patient_id`, `patient_name`, `dept_id`, `age`, `sex`, `admission_date`, `discharge_date`, `diagnosis`,`created_by`) VALUES ('$row2[ipd_no]','$row2[name]','$row2[dept_id]','$row2[age]','$row2[gender]','$row2[admission_date]','$date','$row2[diagnosis]','$_SESSION[name]')";
        //   echo $query5;exit;
            mysqli_query($con,$query5);
            $query6="UPDATE bed SET status='active' where id='$row2[bed_id]'";
             mysqli_query($con,$query6);
             
             $query6="UPDATE central_ipd SET discharge=1 where ipd_no='$row2[ipd_no]'";
             mysqli_query($con,$query6);
       }    
    }
    if($i<=$sg){
$sql2="SELECT ipd_no,admission_date,name,age,gender,address,dept_id,bed_id,diagnosis FROM central_ipd WHERE discharge=0 and admission_date < '$discdate' and is_generated=1 and dept_id=6 order by rand() limit 1";
// echo $sql2;
$res2=mysqli_query($con,$sql2);
     $row2=mysqli_fetch_assoc($res2);  
     if($row2)
       {
            $query5="INSERT INTO `discharge`(`patient_id`, `patient_name`, `dept_id`, `age`, `sex`, `admission_date`, `discharge_date`, `diagnosis`,`created_by`) VALUES ('$row2[ipd_no]','$row2[name]','$row2[dept_id]','$row2[age]','$row2[gender]','$row2[admission_date]','$date','$row2[diagnosis]','$_SESSION[name]')";
        //   echo $query5;exit;
            mysqli_query($con,$query5);
            $query6="UPDATE bed SET status='active' where id='$row2[bed_id]'";
             mysqli_query($con,$query6);
             
             $query6="UPDATE central_ipd SET discharge=1 where ipd_no='$row2[ipd_no]'";
             mysqli_query($con,$query6);
       }   
    }
    if($i<=$or){
$sql2="SELECT ipd_no,admission_date,name,age,gender,address,dept_id,bed_id,diagnosis FROM central_ipd WHERE discharge=0 and admission_date < '$discdate' and is_generated=1 and dept_id=7 order by rand() limit 1";
// echo $sql2;
$res2=mysqli_query($con,$sql2);
       $row2=mysqli_fetch_assoc($res2);
       if($row2)
       {
            $query5="INSERT INTO `discharge`(`patient_id`, `patient_name`, `dept_id`, `age`, `sex`, `admission_date`, `discharge_date`, `diagnosis`,`created_by`) VALUES ('$row2[ipd_no]','$row2[name]','$row2[dept_id]','$row2[age]','$row2[gender]','$row2[admission_date]','$date','$row2[diagnosis]','$_SESSION[name]')";
        //   echo $query5;exit;
            mysqli_query($con,$query5);
            $query6="UPDATE bed SET status='active' where id='$row2[bed_id]'";
             mysqli_query($con,$query6);
             
             $query6="UPDATE central_ipd SET discharge=1 where ipd_no='$row2[ipd_no]'";
             mysqli_query($con,$query6);
       }  
    }
    if($i<=$op){
$sql2="SELECT ipd_no,admission_date,name,age,gender,address,dept_id,bed_id,diagnosis FROM central_ipd WHERE discharge=0 and admission_date < '$discdate' and is_generated=1 and dept_id=11 order by rand() limit 1";
// echo $sql2;
$res2=mysqli_query($con,$sql2);
     $row2=mysqli_fetch_assoc($res2);  
     if($row2)
       {
            $query5="INSERT INTO `discharge`(`patient_id`, `patient_name`, `dept_id`, `age`, `sex`, `admission_date`, `discharge_date`, `diagnosis`,`created_by`) VALUES ('$row2[ipd_no]','$row2[name]','$row2[dept_id]','$row2[age]','$row2[gender]','$row2[admission_date]','$date','$row2[diagnosis]','$_SESSION[name]')";
        //   echo $query5;exit;
            mysqli_query($con,$query5);
            $query6="UPDATE bed SET status='active' where id='$row2[bed_id]'";
             mysqli_query($con,$query6);
             
             $query6="UPDATE central_ipd SET discharge=1 where ipd_no='$row2[ipd_no]'";
             mysqli_query($con,$query6);
       }    
    }
    if($i<=$og){
$sql2="SELECT ipd_no,admission_date,name,age,gender,address,dept_id,bed_id,diagnosis FROM central_ipd WHERE discharge=0 and admission_date < '$discdate' and is_generated=1 and dept_id=12 order by rand() limit 1";
// echo $sql2;
$res2=mysqli_query($con,$sql2);
     $row2=mysqli_fetch_assoc($res2);  
     if($row2)
       {
            $query5="INSERT INTO `discharge`(`patient_id`, `patient_name`, `dept_id`, `age`, `sex`, `admission_date`, `discharge_date`, `diagnosis`,`created_by`) VALUES ('$row2[ipd_no]','$row2[name]','$row2[dept_id]','$row2[age]','$row2[gender]','$row2[admission_date]','$date','$row2[diagnosis]','$_SESSION[name]')";
        //   echo $query5;exit;
            mysqli_query($con,$query5);
            $query6="UPDATE bed SET status='active' where id='$row2[bed_id]'";
             mysqli_query($con,$query6);
             
             $query6="UPDATE central_ipd SET discharge=1 where ipd_no='$row2[ipd_no]'";
             mysqli_query($con,$query6);
       }
    }
    if($i<=$en){
$sql2="SELECT ipd_no,admission_date,name,age,gender,address,dept_id,bed_id,diagnosis FROM central_ipd WHERE discharge=0 and admission_date < '$discdate' and is_generated=1 and dept_id=18 order by rand() limit 1";
// echo $sql2;
$res2=mysqli_query($con,$sql2);
       $row2=mysqli_fetch_assoc($res2);
       if($row2)
       {
            $query5="INSERT INTO `discharge`(`patient_id`, `patient_name`, `dept_id`, `age`, `sex`, `admission_date`, `discharge_date`, `diagnosis`,`created_by`) VALUES ('$row2[ipd_no]','$row2[name]','$row2[dept_id]','$row2[age]','$row2[gender]','$row2[admission_date]','$date','$row2[diagnosis]','$_SESSION[name]')";
        //   echo $query5;exit;
            mysqli_query($con,$query5);
            $query6="UPDATE bed SET status='active' where id='$row2[bed_id]'";
             mysqli_query($con,$query6);
             
             $query6="UPDATE central_ipd SET discharge=1 where ipd_no='$row2[ipd_no]'";
             mysqli_query($con,$query6);
       } 
    }
    if($i<=$em){
$sql2="SELECT ipd_no,admission_date,name,age,gender,address,dept_id,bed_id,diagnosis FROM central_ipd WHERE discharge=0 and admission_date < '$discdate' and is_generated=1 and dept_id=17 order by rand() limit 1";
// echo $sql2;
$res2=mysqli_query($con,$sql2);
      $row2=mysqli_fetch_assoc($res2); 
      if($row2)
       {
            $query5="INSERT INTO `discharge`(`patient_id`, `patient_name`, `dept_id`, `age`, `sex`, `admission_date`, `discharge_date`, `diagnosis`,`created_by`) VALUES ('$row2[ipd_no]','$row2[name]','$row2[dept_id]','$row2[age]','$row2[gender]','$row2[admission_date]','$date','$row2[diagnosis]','$_SESSION[name]')";
        //   echo $query5;exit;
            mysqli_query($con,$query5);
            $query6="UPDATE bed SET status='active' where id='$row2[bed_id]'";
             mysqli_query($con,$query6);
             
             $query6="UPDATE central_ipd SET discharge=1 where ipd_no='$row2[ipd_no]'";
             mysqli_query($con,$query6);
       }
    }
   }
    echo "<script>window.location='discharge_data.php'</script>";

}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Discharge Data</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" ></script>
        <!-- Sweet Alert-->
     <link href="css/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
 
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
            <h4 class="text-uppercase">Discharge Bulk Patient</h4>
        </div>
    </div>
    <div class="row clearfix">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="card">

<div class="card-body">

				<form method="post" action="" name="frmtreat" id="frmtreat">
					<div class="row">
						<div class="col-sm-4 col-xs-12">
							<div class="form-group">
								<label for="type">Number of <span class="text-danger"> Pediatrics </span> Discharge</label>
								<div class="form-line">
									<input type="number" class="form-control" name="pd_number" id="pd_number" min="0" value="" onkeyup="getTotal()">
								</div>
							</div>
						</div>
						<div class="col-sm-4 col-xs-12">
							<div class="form-group">
								<label for="type">Number of <span class="text-danger"> Psychiatry </span> Discharge</label>
								<div class="form-line">
									<input type="number" class="form-control" name="ps_number" id="ps_number" min="0" value="" onkeyup="getTotal()">
								</div>
							</div>
						</div>
						<div class="col-sm-4 col-xs-12">
							<div class="form-group">
								<label for="type">Number of <span class="text-danger"> Skin </span> Discharge</label>
								<div class="form-line">
									<input type="number" class="form-control" name="sk_number" id="sk_number" min="0" value="" onkeyup="getTotal()">
								</div>
							</div>
						</div>
						
						<div class="col-sm-4 col-xs-12">
							<div class="form-group">
								<label for="type">Number of <span class="text-danger"> Ortho </span> Discharge</label>
								<div class="form-line">
									<input type="number" class="form-control" name="or_number" id="or_number" min="0" value="" onkeyup="getTotal()">
								</div>
							</div>
						</div>
						<div class="col-sm-4 col-xs-12">
							<div class="form-group">
								<label for="type">Number of <span class="text-danger"> Ophto </span> Discharge</label>
								<div class="form-line">
									<input type="number" class="form-control" name="op_number" id="op_number" min="0" value="" onkeyup="getTotal()">
								</div>
							</div>
						</div><div class="col-sm-4 col-xs-12">
							<div class="form-group">
								<label for="type">Number of <span class="text-danger"> OBG </span> Discharge</label>
								<div class="form-line">
									<input type="number" class="form-control" name="og_number" id="og_number" min="0" value="" onkeyup="getTotal()">
								</div>
							</div>
						</div><div class="col-sm-4 col-xs-12">
							<div class="form-group">
								<label for="type">Number of <span class="text-danger"> General Surgery </span> Discharge</label>
								<div class="form-line">
									<input type="number" class="form-control" name="sg_number" id="sg_number" min="0" value="" onkeyup="getTotal()">
								</div>
							</div>
						</div><div class="col-sm-4 col-xs-12">
							<div class="form-group">
								<label for="type">Number of <span class="text-danger"> ENT </span> Discharge</label>
								<div class="form-line">
									<input type="number" class="form-control" name="en_number" id="en_number" min="0" value="" onkeyup="getTotal()">
								</div>
							</div>
						</div>
						<div class="col-sm-4 col-xs-12">
							<div class="form-group">
								<label for="type">Number of <span class="text-danger"> General Medicine </span> Discharge</label>
								<div class="form-line">
									<input type="number" class="form-control" name="me_number" id="me_number" min="0" value="" onkeyup="getTotal()">
								</div>
							</div>
						</div>
						<div class="col-sm-4 col-xs-12">
							<div class="form-group">
								<label for="type">Number of <span class="text-danger"> Emergancy </span> Discharge</label>
								<div class="form-line">
									<input type="number" class="form-control" name="em_number" id="em_number" min="0" value="" onkeyup="getTotal()">
								</div>
							</div>
						</div>
							<div class="col-sm-4 col-xs-12">
							<div class="form-group">
								<label for="type">Date</label>
								<div class="form-line">
									<input type="date" class="form-control" name="date" id="date" value="<?= date("Y-m-d");?>">
								</div>
							</div>
						</div>
						</div>
										<div class="col-sm-12 ">
										    <p class="h2">Total : <span id="total"></span></p>
						<input type="submit" name="submit" id="submit" value="Submit" class="btn btn-success float-right">
					</div>

						</form>
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

<script>
    history.pushState(null, null, location.href);
        window.onpopstate = function () {
            history.go(1);
        };
        function getTotal()
      {
          const form = document.getElementById("frmtreat");
        let sum = 0;
        
        form.querySelectorAll("input[type='number']").forEach(input => {
          sum += parseInt(input.value) || 0;
        });
        document.getElementById('total').innerHTML=sum;
      }
</script>



<!-- Sweet Alerts js -->
<script src="css/sweetalert2/sweetalert2.min.js"></script>

<!-- Sweet alert init js-->
<script src="js/sweet-alerts.init.js"></script>
  

</body>

</html>