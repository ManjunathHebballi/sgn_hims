<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/dbconnect.php');
$newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
if(isset($_REQUEST['count']))
{
    for ($i=1; $i < $_REQUEST['count']; $i++) { 
        $res=$_REQUEST['result'.$i];
        $id=$_REQUEST['id'.$i];
      
            
            $sql="UPDATE `lab_report` SET test_result='$res' WHERE id= $id";
        
      
        
        $qsqlpat = mysqli_query($con,$sql);
    }
    // if(isset($_REQUEST[]))
    
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>IP Diet Chart</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" ></script>


    <link href="../../assets/plugins/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
    <!-- DataTables -->
    <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/datatables.net-select-bs4/css//select.bootstrap4.min.css" rel="stylesheet" type="text/css" />

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">


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
<h4 class="text-uppercase">IP Diet Chart</h4>

<div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
  <div class="row">
                <div class="col-md-12">
                    <h4>Select Criteria</h4><br>
                 
                </div>
            </div> 
<form action="#" href="#pills-home" method="POST">
    <div class="row">
        <div class="col-md-4">
           <div class="form-group">
                  <label for="exampleInputEmail1"> Select IP Number </label>
                  <select name="rogatype"  id="rogatype" class="form-control selectpicker"  data-live-search="true">
                                                                                                <option value="">IP-1236548 (Manju)</option>
                                                                                                
                                                                                                <option value="<?= $row['id']?>" <?php if($exam['roga_type']== $row['id']) echo "selected"; ?>><?= $row['roga_name']?></option>
                                                                                               
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
        
    </div>
            </div>
        </div>
    </div>
   
   
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                        <h5 class="ml-4">Diet Chart of  <span class="text-danger">
                            Manju
                             </span></h5>
                             </div>
<div class="col-md-6">
                             <a href="testwiselabprint.php?from=<?= $op_from?>&to=<?= $op_to?>" target="_blank" class="float-right badge badge-pill badge-danger" data-toggle="tooltip" data-placement="top" title="Print Lab Report"><i class="icofont-print"></i> Print </a>
                        </div>
                    </div>
                    <hr>

                    <div class="table-responsive">
                    <table  class="table table-hoverde table-bordered   ">
                                  <tbody>


                                  <tr>
                                     <td>  <h5>Name: <span class="font-weight-light">Manju</span></h5> </td>
                                     <td>  <h5>Central OPD No: <span class="font-weight-light">2206125</span></h5> </td>

                                     <td>  <h5>Central Ward: <span class="font-weight-light">Female</span></h5> </td>
                                     
                                     <td>  <h5>Age: <span class="font-weight-light">49</span></h5> </td>

                                     
                                  </tr>
                                  <tr>
                                     <td>  <h5>Dept OPD No: <span class="font-weight-light">Manju</span></h5> </td>
                                     <td>  <h5>Bed No: <span class="font-weight-light">129</span></h5> </td>

                                     <td>  <h5>Sex: <span class="font-weight-light">Female</span></h5> </td>
                                     
                                     <td>  <h5>Central IPD No: <span class="font-weight-light">IP220457</span></h5> </td>

                                     
                                  </tr>
                                  <tr>
                                     <td>  <h5>	Doctor's Name: <span class="font-weight-light">Praveen</span></h5> </td>
                                     <td>  <h5>D.O.A: <span class="font-weight-light">23-02-2022</span></h5> </td>

                                     <td>  <h5>Dept IPD No: <span class="font-weight-light">1</span></h5> </td>
                                     
                                     <td>  <h5>Clinical Register Name: <span class="font-weight-light">49</span></h5> </td>

                                     
                                  </tr>
                                     
                                     
                                  </tbody>
                                 
                                            </table>  
                                            <br>
                                            
                                                <table class="table table-hoverde table-bordered display nospacing  ">
                                                    <tbody>
                                                    <tr>
                                        <td>
                                            Type of Diet
                                        </td>
                                        <td>Rice</td>
                                        <td>Dal</td>
                                        <td>Salt</td>
                                        <td>Tamarind</td>
                                        <td>Onion</td>
                                        <td>	Chilli Powder</td>
                                        <td>Oil</td>
                                        <td>Vegitable</td>
                                        <td>Sugar</td>
                                        <td>Tea/Coffee</td>
                                        <td>Milk</td>
                                        <td>Bread</td>
                                    </tr>
                                    <tr>
                                        <td>BD: Basic Diet</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>

                                    </tr>
                                    <tr>
                                        <td>C.M.D(CMB)</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>

                                    </tr>
                                    <tr>
                                        <td>1/2 C.M.D(1/2CMB)</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>

                                    </tr>
                                    <tr>
                                        <td>C.M.D: Children Half Diet(1/2 B.D)</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>

                                    </tr>
                                    <tr>
                                        <td>M.D Milk Diet(CMB)</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>

                                    </tr>
                                                    </tbody>
                                                </table>
                                            <br>

                                                <table class="table table-hoverde table-bordered display  ">
                                                    <tbody>
                                                    <tr>
                                        <td>
                                           Day
                                        </td>
                                        <td>1</td>
                                        <td>2</td>
                                        <td>3</td>
                                        <td>4</td>
                                        <td>5</td>
                                        <td>6</td>
                                        <td>7</td>
                                        <td>8</td>
                                        <td>9</td>
                                        <td>10</td>
                                        <td>11</td>
                                        <td>12</td>
                                        <td>13</td>
                                        <td>14</td>
                                        <td>15</td>
                                        <td>16</td>
                                        <td>17</td>
                                        <td>18</td>
                                        <td>18</td>
                                        <td>19</td>
                                        <td>20</td>
                                        <td>21</td>
                                        <td>22</td>
                                        <td>23</td>
                                        <td>24</td>
                                        <td>25</td>
                                        <td>26</td>
                                        <td>27</td>
                                        <td>28</td>
                                        <td>29</td>
                                        <td>30</td>
                                        <td>31</td>


                                     
                                    </tr>
                                    <tr>
                                        <td>Signature of the Doctor</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        

                                    </tr>
                                    <tr>
                                        <td>Signature of the Junior Doctor</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        

                                    </tr>
                                    <tr>
                                        <td>Signature of the Staff Nurse</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        

                                    </tr>
                                    <tr>
                                        <td>Signature of Matron</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        

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

     <!-- Datatable -->
     <script src="./vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="./js/plugins-init/datatables.init.js"></script>
    <script src="./js/plugins-init/datatables.init.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>


    
   


  

</body>

</html>