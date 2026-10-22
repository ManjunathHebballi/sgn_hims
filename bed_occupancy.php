<?php 
require_once(__DIR__ . '/model/session_config.php');

date_default_timezone_set("Asia/Kolkata"); 

include('model/reportClass.php');
$newReport = new report;
$date=date("Y-m-d");


if(isset($_REQUEST['bed_date']))
{
    $bed_date=$_REQUEST['bed_date'];
}
else{
        $bed_date=$date;
}

$bedDept=$newReport->getBedOcc($bed_date);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Bed O Report</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">

    <link href="./vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">



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

  <!-- View -->


        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <!-- row -->
        <div class="container-fluid">

        <div class="row mb-4 ml-3">
          <h4 class="text-uppercase">Bed Occupancy Report</h4>
        </div>

<ul class="nav nav-pills mb-3 justify-content-around" id="pills-tab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="pills-deptwise-tab" data-toggle="pill" href="#pills-deptwise" role="tab" aria-controls="pills-deptwise" aria-selected="true"> Bed Occupancy Statistics Report</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="pills-deptwise2-tab" data-toggle="pill" href="#pills-deptwise2" role="tab" aria-controls="pills-deptwise2" aria-selected="false"> Bed Occupancy Summary  Report  </a>
  </li>

</ul>


<div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-deptwise" role="tabpanel" aria-labelledby="pills-deptwise-tab">
                            
                            <div class="card">
                                <div class="card-body">
                                     <form action="#pills-deptwise-tab" method="POST">
                                    <div class="row d-flex align-items-center">
                                       
                                            <div class="col-md-3">
                                                
                                                <div class="form-group">
                                                    <label>Select Date</label>
                                                    <input class="form-control" type="date" name="bed_date" id="bed_date" value="<?= $bed_date?>">
                                                </div>
                                            </div>
                                             <div class="col-md-3">
                                                
                                                
                                                    
                                                    <input class="btn btn-success mt-2" type="submit" name="search1" id="search" value="Search" >
                                                
                                            </div>
                                       
                                    </div>
                                     </form>
                                </div>
                            </div>
                    
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    
                    <div class="row">
                       <div class="col-md-6">
                            <h5 class="ml-4">Bed Occupancy On<span class="text-danger"> <?= date("d.m.Y",strtotime($bed_date))?></span></h5>
                            
                       </div>
                       <div class="col-md-6">
                             <a href="print_bed_occupancy.php?date=<?= $bed_date?>" data-toggle="tooltip" title="Print Bed Occupancy" target="_blank" class="float-right badge badge-pill badge-danger"><i class="icofont-print"></i> Print </a>
                        </div>
                    </div>
                    
                    <hr>

                    <div class="table-responsive">
                    <table id="example3" class="table table-hoverde display text-center">
                                    <thead class="thead-dark">
                                    <tr >
                                                <th>Sl NO</th>
                                                <th>DEPARTMENT & PATIENT NAME</th>
                                                <th>Total Bed</th>
                                                <th>CUR_BED OCC.</th>
                                                <th>FLOOR_BED OCC.</th>
                                                <th>BEDOCC.(%)</th>
                                   </tr>
                                        </thead>
                                        <tbody class="text-center text-black">
                                            
                                            <?php $sn=1;
                                            $total=$total_occ=0;
                                            
                                            while($row=mysqli_fetch_assoc($bedDept)){
                                            
                                            ?>
                                            <tr>
                                                <td><?= $sn?></td>
                                                <td><?= $row['dept_name']." - ". $row['ward']." - ". $row['unit']?></td>
                                                <td><?= $row['bed_count']?></td>
                                                <td><?= $row['occupied']?></td>
                                                <td><?= ""?></td>
                                                
                                                <td><?=round(($row['occupied']*100)/$row['bed_count'],2)?></td>
                                              </tr>
                                              <?php $sn++; 
                                                    $total +=$row['bed_count'];
                                                    $total_occ +=$row['occupied'];
                                                    $total_percent +=round(($row['occupied']*100)/$row['bed_count'],2);
                                              } ?>
                                            <tr >
                                                <td></td>
                                                <td class="font-weight-bold">Total</td>
                                                <td class="font-weight-bold"><?= $total?></td>
                                                <td class="font-weight-bold"><?= $total_occ?></td>
                                                <td class="font-weight-bold"><?= ''?></td>
                                                <td class="font-weight-bold"><?= round(($total_occ*100)/$total,2)?></td> 
                                              </tr>    
                                                
                                            
                                            
                                            </tbody>
                                            </table>   
                    </div>
                    
                      </div>
                       </div>
                    
                      </div>
                      </div>
                      </div>
                       <div class="tab-pane fade" id="pills-deptwise2" role="tabpanel" aria-labelledby="pills-deptwise2-tab">
                           
                            <div class="card">
                                <div class="card-body">
                                    <form action="#pills-deptwise2-tab" method="POST">
                                    <div class="row d-flex align-items-center">
                                       
                                            <div class="col-md-3">
                                                
                                                <div class="form-group">
                                                    <label>Select Date</label>
                                                    <input class="form-control" type="date" name="bed_date" id="bed_date" value="<?= $bed_date?>">
                                                </div>
                                            </div>
                                             <div class="col-md-3">
                                                
                                                
                                                    
                                                    <input class="btn btn-success mt-2" type="submit" name="search1" id="search" value="Search" >
                                                
                                            </div>
                                       
                                    </div>
                                     </form>
                                </div>
                            </div>
                            
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                       <div class="col-md-6">
                          
                             <h5 class="ml-4">Detailed Bed Occupancy On<span class="text-danger"> <?=date('d.m.Y',strtotime($bed_date))?></span></h5>
                       </div>
                       <div class="col-md-6">
                             <a href="print_bed_occupancey_summary.php?date=<?= $bed_date?>" data-toggle="tooltip" title="Print Bed Occupancy Summary" target="_blank" class="float-right badge badge-pill badge-danger"><i class="icofont-print"></i> Print </a>
                        </div>
                    </div>
                    
                    <hr>
                                        <div class="table-responsive">
                    <table id="example3" class="table table-hoverde display text-center">
                                    <thead class="thead-dark">
                                    <tr >
                                        <th>Sl No</th>
                                        <th>IPD No</th>
                                        <th>OPD No</th>
                                        <th>Department</th>
                                        <th>Patient Name</th>  
                                        <th>Age</th>
                                        <th>Sex</th>
                                   </tr>
                                        </thead>
                                        <tbody class="text-center text-black">
                                            
                                        <?php
                                        
                                        $sn=1;
                                        $flag='';
                                        $res=$newReport->getBedOccDetails($bed_date);
                                               while($row=mysqli_fetch_assoc($res))
                                               { 
                                                 
                                               if($flag!=$row['ward']) {?>
                        
                        <tr ><td class="text-capitalize text-center font-weight-bold" colspan="5" ><?= $row['ward']?></td></tr>
                        
                        
                        <?php
                    } ?>
                                            <tr>
                                                <td><?=$sn;?></td>
                                                <td><?=$row['ipd_no'];?></td>
                                                <td><?=$row['opd_no'];?></td>
                                                <td><?=$row['dept_name'];?></td>
                                                <td><?=$row['name'];?></td>
                                                <td><?=$row['age'];?></td>
                                                <td><?=$row['gender'];?></td>
                                            </tr>
                                                
                                           <?php $sn++;$flag=$row['ward'];  } ?>
                                            
                                            </tbody>
                                            </table>   
                    </div>
                                    </div>
                                </div>
                            </div>
                    
                    </div>
                    
                    <br>
                    <br>
                    <br>
                    
               
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
<script>
    if(window.location.href.split("#")[1]!=undefined)
        {
            var collection=document.getElementsByClassName(' show active');
       for (let i = 0; i < collection.length; i++) {
        // collection[i].classList.remove("show"); 
        collection[i].classList.remove("active");
            }
       var collection1=document.getElementsByClassName(' active');
       for (let i = 0; i < collection1.length; i++) {
  collection1[i].classList.remove("active");
            }
           var id= window.location.href.split("#")[1];
       document.getElementById(id).className += " active";
    //    alert(.length);
    var int=id.split("-");
    var flag=0;
    var element='';
    for (let index = 0; index < int.length - 1; index++) {
        if(flag==1)
        {
            element +='-';
        }
         element += int[index];
         flag=1;
        
    }
    // alert()
       document.getElementById(element).className += " show active";
        }
</script>

  

</body>

</html>