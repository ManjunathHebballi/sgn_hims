<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/dbconnect.php');

$newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        

if(isset($_REQUEST['submit']))
{
    // print_r($_REQUEST);exit;
    
      if($_REQUEST['submit']=='Submit')
    {
    $query="INSERT INTO `labtest_group`(`labid`, `test_name`, `normal_range`, `units`) VALUES ('$_REQUEST[tgroup]','$_REQUEST[testname]','$_REQUEST[range]','$_REQUEST[unints]')";
    }
    elseif($_REQUEST['submit']=='Update')
    {
        $query="UPDATE `labtest_group` SET `labid`='$_REQUEST[tgroup]', `test_name`='$_REQUEST[testname]', `normal_range`='$_REQUEST[range]', `units`='$_REQUEST[unints]' WHERE testid='$_REQUEST[editid]'";
    }
    // echo $query;exit;
        mysqli_query($con,$query);
        header('location:lab_test_name.php');
}
$sql="SELECT * FROM `lab_test` WHERE `status`='active'";
        $res=mysqli_query($con,$sql);
$sql1="SELECT * FROM `labtest_group` ";
        $res1=mysqli_query($con,$sql1);

if(isset($_REQUEST['editid']))
{
    $sql_res="SELECT * FROM `labtest_group` WHERE `testid`='$_REQUEST[editid]'";
    $data_res=mysqli_query($con,$sql_res);
    $row_res=mysqli_fetch_assoc($data_res);
}
        
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Test Name</title>
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

<div aria-live="polite" id="notification" aria-atomic="true" >
<?php if(isset($_REQUEST['success']))
{ ?>
    
<div class="alert alert-success solid alert-dismissible fade show">
                                    <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span>
                                    </button>
                                    <strong>Success!</strong> OP Patient inserted
                                </div>
                         <?php   }
                            ?>
</div>

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
            <h4 class="text-uppercase">Add Test Name</h4>
        </div>
    </div>
    <div class="row clearfix">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="card">

<div class="card-body">

<form method="post" action="" name="frmtreat" onsubmit="return validateform()">
					<div class="row">
						<div class="col-sm-3 col-xs-12">
							<div class="form-group">
								<label for="type">Test Name</label>
								<div class="form-line">
									<input type="text" class="form-control" name="testname" id="testname" value="<?= $row_res['test_name']?>">
								</div>
							</div>
						</div>
						<div class="col-sm-3 col-xs-12">
							<div class="form-group">
								<label for="type">Select Test Group</label>
								<div class="form-line">
                                    <select name="tgroup" class="form-control selectpicker" data-live-search="true" id="tgroup">
<option value="">Select Test Group </option>
                                    <?php while($row=mysqli_fetch_assoc($res)){?>
                                        <option value="<?= $row['testid']?>" <?php if(isset($row_res['labid']) && $row_res['labid']==$row['testid']) echo 'selected';?>><?= $row['testname']?></option> 
                                        <?php } ?>
                                    </select>
								</div>
							</div>
						</div>

                        <div class="col-sm-3 col-xs-12">
							<div class="form-group">
								<label for="type">Units</label>
								<div class="form-line">
									<input type="text" class="form-control" name="unints" id="units" value="<?= $row_res['units']?>">
								</div>
							</div>
						</div>
						
                        <div class="col-sm-3 col-xs-12">
							<div class="form-group">
								<label for="type">Normal Range</label>
								<div class="form-line">
									<input type="text" class="form-control" name="range" id="range" value="<?= $row_res['normal_range']?>">
								</div>
							</div>
						</div>
						
						
					
				
										<div class="col-sm-12 ">
						<?php if(isset($_REQUEST['editid'])) {?>
						<input type="submit" name="submit" id="submit" value="Update" class="btn btn-success float-right">
						<?php } else {?>
						<input type="submit" name="submit" id="submit" value="Submit" class="btn btn-success float-right">
						<?php  } ?>
					</div>

				

			</div></form>
            </div>
		</div>
	</div>
	
</div>


<div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <h5 class="ml-4">Test List</h5>
                    </div>
                    <hr>

                    <div class="table-responsive">
                    <table id="datatable-buttons" class="table table-bordered table-hoverde display text-center">
                                    <thead class="thead-dark">
                                    <tr >
                                                <th>Sl NO</th>
                                                 <th>Test Name</th>

                                                <th>Normal Range</th>
                                                <th>Units</th>
                                             
                                                
                                                <th>Action</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody class="text-center  text-black">
                                        <?php $sn=1;
                                         while($row=mysqli_fetch_assoc($res1)){?>
                                            <tr>
                                                <td><?= $sn;?></td>
                                              
                                                <td><?= $row['test_name']?></td>
                                             
                                                <td><?= $row['normal_range']?><br>
                                                 </td>
                                               <td><?= $row['units']?></td>

                                                
                                                <td><a href="lab_test_name.php?editid=<?= $row['testid']?>"><span class=" badge badge-pill badge-primary p-2"><i class="bi bi-pencil mr-2"></i> Edit</span> </a></td>

                                               </tr> 
                                        <?php $sn++; } ?>
                                           
                                           
                                                                   
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
  

<script>
    $('.selectpicker').selectpicker({
    liveSearch: true
 });
</script>

</body>

</html>