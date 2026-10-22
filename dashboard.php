<?php 
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Force session path before starting session
require_once('./model/session_config.php');
// print_r($_SESSION);exit;
date_default_timezone_set("Asia/Kolkata");  
include('model/dashClass.php');
$newOP = new dashboard;
$tot_opd=$newOP->getTotalopd();
$tot_ipd=$newOP->getTotalipd();
$data=$newOP->getTotalpatient();
// echo $data;
$deta=explode("|",$data);
$ipd=$deta[1];
$opd=$deta[0];
$today=$ipd+$opd;


$dept=$newOP->getBedList();
// print_r($_SESSION);

// exit;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Dashboard</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="icofont/icofont.min.css">

    <!-- <link href="./vendor/pg-calendar/css/pignose.calendar.min.css" rel="stylesheet"> -->
    <link href="./vendor/chartist/css/chartist.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" ></script>
        <!-- Sweet Alert-->
     <link href="css/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
 <link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
     <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>


     <style>
         .badge-light-info {
    background-color: #f8f5ff;
    color: #7239ea;
}
 table.dataTable tbody td {
    padding: 2px !important;
  }
.badge-light-danger {
    background-color: #fff5f8;
    color: #f1416c;}
    .badge-light-success {
    background-color: #e8fff3;
    color: #50cd89;
}

.badge-light-warning {
    background-color: #fbf5eb;
    color: #ffaa16;
}
.icon2{

    width:60%
}
.btn-bill-view {
    display: block;
    margin-top: 5px;
    text-align: center;
}

/* Search form styling */
.form-search {
    /*background: #f8f9fa;*/
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    /*border: 1px solid #e9ecef;*/
}

.form-search .form-control {
    border-radius: 4px;
    border: 1px solid #ced4da;
    padding: 8px 12px;
    font-size: 14px;
}

.form-search .form-control:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.detailed_search {
    border-top: 1px solid #dee2e6;
    padding-top: 15px;
    margin-top: 15px;
}

.btn-outline-info {
    color: #17a2b8;
    border-color: #17a2b8;
}

.btn-outline-info:hover {
    color: #fff;
    background-color: #17a2b8;
    border-color: #17a2b8;
}

.has_report { color: orange; font-weight:600; margin:2px 0; }
.has_no_report { color: #000; margin:2px 0; }


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



<div class="grey-bg container-fluid">
  <section id="minimal-statistics">
       <div class="row">
      <div class="col-12 mt-3 mb-1">
        <h4 class="text-uppercase">Dashboard</h4>
        
      </div>
    </div>
      <?php if($_SESSION['role']=='Lab' || $_SESSION['role']=='Lab Admin')
{
    // Fetch dynamic data for dropdowns
    $doctors = $newOP->getDoctors();
    $referenceCenters = $newOP->getReferenceCenters();
    $labCategories = $newOP->getLabCategories();
    $outsourceLabs = $newOP->getOutsourceLabs();
    $testNames = $newOP->getTestNames();
    ?>
<div class='card'>
    <div class='card-body'>
        <!-- Search form -->
        <form class="form-search form-search-workspace" id="prescription_search" action="dashboard.php" accept-charset="UTF-8" method="get">
            <div class="row mb-3">
                <div class="col-md-6">
                    <h5 class="mb-0"><i class="fa fa-search"></i> Search Lab Reports</h5>
                    <div class="search-results-info"></div>
                </div>
                <div class="col-md-6 text-right">
                 
                    <input type="submit" name="commit" value="Search" class="btn btn-primary btn-sm" data-disable-with="Searching...">
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-2">
                    <input placeholder="Patient Name" class="form-control" type="text" name="q[patient_name_cont]" id="q_patient_name_cont" value="<?= $_GET['q']['patient_name_cont'] ?? '' ?>">
                </div>
                <div class="col-md-2">
                    <input placeholder="Lab No" class="form-control" type="text" name="q[lab_no_eq]" id="q_lab_no_eq" value="<?= $_GET['q']['lab_no_eq'] ?? '' ?>">
                </div>
                <div class="col-md-2">
                    <input placeholder="Bill No" class="form-control" type="text" name="q[bill_bill_no_eq]" id="q_bill_bill_no_eq" value="<?= $_GET['q']['bill_bill_no_eq'] ?? '' ?>">
                </div>
                <div class="col-md-2">
                    <input placeholder="Mobile No" class="form-control" type="text" name="q[patient_mobile_eq]" id="q_patient_mobile_eq" value="<?= $_GET['q']['patient_mobile_eq'] ?? '' ?>">
                </div>
                <div class="col-md-2">
                    <select class="form-control" name="q[status_eq]" id="q_status_eq">
                        <option value="">Status</option>
                        <option value="sample_collected" <?= ($_GET['q']['status_eq'] ?? '') == 'sample_collected' ? 'selected' : '' ?>>Sample Collected</option>
                        <option value="dept_acknowledged" <?= ($_GET['q']['status_eq'] ?? '') == 'dept_acknowledged' ? 'selected' : '' ?>>Dept Acknowledged</option>
                        <option value="result_entered" <?= ($_GET['q']['status_eq'] ?? '') == 'result_entered' ? 'selected' : '' ?>>Result Entered</option>
                        <option value="result_finalized" <?= ($_GET['q']['status_eq'] ?? '') == 'result_finalized' ? 'selected' : '' ?>>Result Finalized</option>
                        <option value="printed" <?= ($_GET['q']['status_eq'] ?? '') == 'printed' ? 'selected' : '' ?>>Printed</option>
                        <option value="dispatched" <?= ($_GET['q']['status_eq'] ?? '') == 'dispatched' ? 'selected' : '' ?>>Dispatched</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-control" name="q[test_name_eq]" id="q_test_name_eq">
                        <option value="">Test Name</option>
                        <?php while($test = mysqli_fetch_assoc($testNames)) { ?>
                            <option value="<?= $test['testid'] ?>" <?= ($_GET['q']['test_name_eq'] ?? '') == $test['testid'] ? 'selected' : '' ?>>
                                <?= $test['testname'] ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            
            <div class="row mt-2">
                <div class="col-md-2">
                    <input placeholder="IP No" class="form-control" type="text" name="q[ip_no_eq]" id="q_ip_no_eq" value="<?= $_GET['q']['ip_no_eq'] ?? '' ?>">
                </div>
                <div class="col-md-2">
                    <input placeholder="OP No" class="form-control" type="text" name="q[op_no_eq]" id="q_op_no_eq" value="<?= $_GET['q']['op_no_eq'] ?? '' ?>">
                </div>
                <div class="col-md-2">
                    <select class="form-control" name="q[lab_category_eq]" id="q_lab_category_eq">
                        <option value="">All Category</option>
                        <?php while($category = mysqli_fetch_assoc($labCategories)) { ?>
                            <option value="<?= $category['lab_category_name'] ?>" <?= ($_GET['q']['lab_category_eq'] ?? '') == $category['lab_category_name'] ? 'selected' : '' ?>>
                                <?= $category['lab_category_name'] ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-control" name="q[doctor_id_eq]" id="q_doctor_id_eq">
                        <option value="">Select Referred By</option>
                        <?php while($doctor = mysqli_fetch_assoc($doctors)) { ?>
                            <option value="<?= $doctor['doctor_name'] ?>" <?= ($_GET['q']['doctor_id_eq'] ?? '') == $doctor['id'] ? 'selected' : '' ?>>
                                <?= $doctor['doctor_name'] ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-control" name="q[referral_center_eq]" id="q_referral_center_eq">
                        <option value="">Select Ref. Center</option>
                        <option value="0" <?= ($_GET['q']['referral_center_eq'] ?? '') == '0' ? 'selected' : '' ?>>Without Reference center</option>
                        <?php while($center = mysqli_fetch_assoc($referenceCenters)) { ?>
                            <option value="<?= $center['id'] ?>" <?= ($_GET['q']['referral_center_eq'] ?? '') == $center['id'] ? 'selected' : '' ?>>
                                <?= $center['name'] ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
          
                    
                    <div class="col-md-2">
                        <select class="form-control" name="q[outsource_lab_eq]" id="q_outsource_lab_eq">
                            <option value="">All Outsource Labs</option>
                            <?php while($lab = mysqli_fetch_assoc($outsourceLabs)) { ?>
                                <option value="<?= $lab['id'] ?>" <?= ($_GET['q']['outsource_lab_eq'] ?? '') == $lab['id'] ? 'selected' : '' ?>>
                                    <?= $lab['name'] ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    
                </div>
                
           

        </form>
 </div>
</div>
<div class='card'>
    <div class='card-body'>
        <div class="row">
     <div class="col-md-12">

                                                        
         <i class="fa fa-square chkcolorBdr ml20" style="color: #EE82EE; margin-left: 0px; margin-right: 5px;"></i>Sample Collected &nbsp;&nbsp;
         <i class="fa fa-square chkcolorBdr ml20" style="color: DarkCyan; margin-left: 0px; margin-right: 5px;"></i>Dept. Acknowledged &nbsp;&nbsp;
         <i class="fa fa-square chkcolorBdr ml20" style="color: #8B008B; margin-left: 0px; margin-right: 5px;"></i>Result Entered &nbsp;&nbsp;
         <i class="fa fa-square chkcolorBdr ml20" style="color: #3CB371; margin-left: 0px; margin-right: 5px;"></i>Result Finalized &nbsp;&nbsp;
         <i class="fa fa-square chkcolorBdr ml20" style="color: #48D1CC; margin-left: 0px; margin-right:5px;"></i>Dispatched &nbsp;&nbsp;
         <i class="fa fa-square chkcolorBdr ml20" style="color: #FFA500; margin-left: 0px; margin-right:5px;"></i>Printed &nbsp;&nbsp;
                                                                

    
        <div class="table-responsive mt-3">
                    <table id="datatable-buttons" class="table table-bordered table-hoverde display ">
                                    <thead class="thead-primary">
                                    <tr>
   <th scope="col" >
Sl. No.
   </th>
   <th scope="col" >Patient Type</th>

   <th scope="col">Patient No</th>
   <th scope="col">Visit No.</th>
   <th scope="col">Patient Name</th>
   <th scope="col">Age / Gender</th>
   <th scope="col">Referred By</th>
   <th scope="col">Referred Center</th>
   <th scope="col">Bill NO</th>
   <th scope="col">Payment Status</th>
   <th align="right" scope="col">Status</th>
      <th align="right" scope="col">Action</th>
   <!--<th style="width:6%;">Action</th>-->
</tr>
                                        </thead>
                                        <tbody>
                                              <?php 
                                              // Get search parameters from form
                                              $searchParams = [];
                                              if (isset($_GET['q']) && is_array($_GET['q'])) {
                                                  $searchParams = $_GET['q'];
                                              }
                                              // Pagination params
                                              $perPage = 50;
                                              $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
                                              $offset = ($page - 1) * $perPage;

                                              $totalRows = $newOP->getLabpatientCount($searchParams);
                                              $totalPages = max(1, ceil($totalRows / $perPage));

                                              $data=$newOP->getLabpatient($searchParams, $perPage, $offset);
                                              if(mysqli_num_rows($data)>0)
                                              {
                                                $i=$totalRows-$offset;
                                              while($row=mysqli_fetch_assoc($data))
                                              {
                                              ?>
                                              <tr>
                                                  <td><?= $i--?></td>
                                                  <td><?= $row['patient_type']?></td>
                                                  <td><?= $row['ipd_no'] ? $row['ipd_no'] : $row['opd_no']?></td>
                                                  <td><?= $row['visit_no']?></td>
                                                  <td><?= $row['name']?></td>
                                                  <td><?= $row['age']?> / <?= $row['gender']?></td>
                                                  <td><?= $row['doctor']?></td>
                                                  <td><?= $row['referal_center_name']?></td>
                                                  <td><?= $row['bill_no'].'<br>'.date("h:i:s A",strtotime($row['date']))?></td>
                                                   <td class="text-center">
                                                      <?php
                                                          // Payment status badge
                                                          if (!empty($row['outstand_amount']) && $row['outstand_amount'] > 0) {
                                                              echo "<span class='badge badge-soft-danger'>Unpaid</span>";
                                                          } else {
                                                              echo "<span class='badge badge-soft-success'>Paid</span>";
                                                          }

                                                          // Bill view link
                                                          $isOpd = strtolower($row['patient_type']) === "op";
                                                          $billPage = $isOpd ? "billing/view_billing.php" : "billing/view_ipbilling.php";
                                                          $billId =  $row['billid'] ;
                                                          $patientId = isset($row['patient_id']) ? $row['patient_id'] : ($isOpd ? $row['id'] : $row['ipd_no']);
                                                          $paidAmount = isset($row['paid_amount']) ? $row['paid_amount'] : 0;
                                                          $outstand_amount = isset($row['outstand_amount']) ? $row['outstand_amount'] : 0;
                                                          $status = isset($row['status']) ? $row['status'] : '';
                                                          $billNo = isset($row['bill_no']) ? $row['bill_no'] : '';

                                                          $link = $billPage . "?id=" . urlencode($patientId) . "&bill_no=" . urlencode($billNo) . "&bill_id=" . urlencode($billId) . "&status=" . urlencode($status);
                                                      ?>
                                                      <!--<div class="btn-bill-view">--><br>
                                                      <!--<?= ($row['is_generated']==1) ? 'btn-primary' : 'btn-outline-primary' ?>-->
                                                          <a href="<?= $link ?>" class="btn btn-sm btn-outline-primary" title="View Bill">
                                                              <i class="fa fa-file-invoice"></i>
                                                              <?= number_format($outstand_amount > 0 ? $outstand_amount : $paidAmount, 2) ?>
                                                          </a>
                                                      <!--</div>-->
                                                  </td>
                                                
                                                  <td><?php
                                                  $tests_map = json_decode($row['tests_map_by_name'], true);

// build the inner HTML for the popover
$pieces = [];
if (!empty($tests_map) && is_array($tests_map)) {
    foreach ($tests_map as $testname => $meta) {
        $has = !empty($meta['has_result']) ? true : false;
        // choose class: orange for results present, black otherwise
        $cls = $has ? 'has_report' : 'has_no_report';
        $has_out = !empty($meta['has_outsource']) ? true : false;
        $tag= $has_out ? "<i class='fa fa-exchange'></i>" : "";
        // escape the test name for HTML
        $safeName = htmlspecialchars($testname, ENT_QUOTES, 'UTF-8');
        $pieces[] = "<div class=\"{$cls}\">{$tag} {$safeName}</div>";
    }
}
$popoverContent = implode('', $pieces);

// escape for attribute (quotes) — using ENT_QUOTES to escape both single+double quotes
$attrContent = htmlspecialchars($popoverContent, ENT_QUOTES, 'UTF-8');
$attrTitle   = htmlspecialchars('<b> Lab Tests </b>', ENT_QUOTES, 'UTF-8');
                                                  ?>
                                                  
                                                  <a class="badge badge-light"
   data-content="<?php echo $attrContent; ?>"
   data-original-title="<?php echo $attrTitle; ?>"
   data-placement="left"
   data-trigger="hover"
   data-html="true"
   rel="popover"
>Lab Tests</a><br>
                                                  <?php
                                                  // Color code and status name (without square box)
                                                //   echo isset($row['patient_id']) ? 'Yes' : $isOpd;
                                                        if ($row['is_finalized']>0) {
                                                            // Result Finalized
                                                            echo '<span class="badge badge-success" style="background-color: #3CB371;">Result Finalized</span>';
                                                        } elseif (!empty($row['is_result_entered']) && $row['is_result_entered']>0 ) {
                                                            // Result Entered
                                                            echo '<span class="badge badge-primary" style="background-color: #EE82EE; color: #fff;">Result Entered</span>';
                                                        } elseif (!empty($row['is_outsourced'])&& $row['is_outsourced']>0) {
                                                            // Dispatched
                                                            echo '<span class="badge badge-warning" style="background-color: #48D1CC; color: #fff;">Outsourced</span>';
                                                        } elseif (!empty($row['is_sample_acknowledge']) && $row['is_sample_acknowledge']>0) {
                                                            // Dept. Acknowledged
                                                            echo '<span class="badge badge-info" style="background-color: DarkCyan; color: #fff;">Dept. Acknowledged</span>';
                                                        }  else {
                                                            // Pending
                                                            echo '<span class="badge badge-secondary">Pending</span>';
                                                        }?></td>
                                                   <td>
                                                      <a href="lab_report.php?id=<?= urlencode($row['id']) ?>&bill_no=<?= urlencode($row['bill_no']) ?>" 
                                                         class="btn btn-sm btn-info" 
                                                         title="View Detailed Lab Report">
                                                          <i class="fa fa-list-alt"></i> Detailed
                                                      </a>
                                                  </td>
                                              </tr>
                                              <?php } } else { ?>
                                              <tr>
                                                  <td colspan="12" class="text-center">
                                                      <img src="assets/addnewitem.svg" alt="No data" width="200"> <br>
                                                      <br>
                                                      <span class="text-success bolds"><i class="fa fa-arrow-left"></i> Add new record or search with different criteria.</span>
                                                  </td>
                                              </tr>
                                            <?php } ?>
                                          </tbody>



                                                              
                                        
                                            </table>
                                            <?php if ($totalPages > 1) { ?>
                                            <nav aria-label="Lab pagination" class="mt-2">
                                              <ul class="pagination pagination-sm justify-content-end">
                                                <?php 
                                                  $query = $_GET;
                                                  // Ensure page param exists for building links
                                                  for ($p = 1; $p <= $totalPages; $p++) {
                                                    $query['page'] = $p;
                                                    $url = 'dashboard.php?' . http_build_query($query);
                                                    $active = ($p === $page) ? ' active' : '';
                                                    echo '<li class="page-item'.$active.'"><a class="page-link" href="'.$url.'">'.$p.'</a></li>';
                                                  }
                                                ?>
                                              </ul>
                                            </nav>
                                            <?php } ?>   
                    </div>
    </div>
</div>
    </div>
</div>
<?php } 
if($_SESSION['role']!='Lab' && $_SESSION['role']!='Lab Admin') { ?>
   
    <div class="row">
    <div class="col-md-3">
        <div class="card dash-card">
           
          <div class="card-body">
            <div class="row ">
            <div class="col-md-9">
            <h5 class="text-left text-uppercase">
            Today's OPD Patients
            </h5>
            <h2><?= $opd
            
            ?></h2>
             
            </div>              <div class="col-md-3  ">
                <div class="icon">
<span>
<img src="assets/icons/1.png" class="img-fluid" alt="">
</span>
                </div>

              </div>
            </div>
         
              
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card dash-card">
          <div class="card-body">
            <div class="row ">
            <div class="col-md-9">
            <h5 class="text-left text-uppercase">
            Total OPD Patients
            </h5>
            <h2> <?= $tot_opd
            
            ?></h2>
             
            </div>              <div class="col-md-3  ">
                <div class="icon">
<span>
<img src="assets/icons/patient.png" class="img-fluid" alt="">
</span>
                </div>

              </div>
            </div>
         
              
          </div>
        </div>
      </div>
       <div class="col-md-3">
        <div class="card dash-card">
          <div class="card-body">
            <div class="row ">
            <div class="col-md-9">
            <h5 class="text-left text-uppercase">
          Today's IP Patients
            </h5>
            <h2><?= $ipd
            
            ?> </h2>
             
            </div>              <div class="col-md-3  ">
                <div class="icon">
<span>
<img src="assets/icons/hospital-bed.png" class="img-fluid" alt="">
</span>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card dash-card">
          <div class="card-body">
            <div class="row ">
            <div class="col-md-9">
            <h5 class="text-left text-uppercase">
          Total IP Patients 
            </h5>
            <h2><?= $tot_ipd?></h2>
             
            </div>              <div class="col-md-3  ">
                <div class="icon">
<span>
<img src="assets/icons/hospital-bed (1).png" class="img-fluid" alt="">
</span>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>

      </div>


      <div class="row">
<div class="col-md-6">
  <div class="card p-3">
    <div class="card-header bg-light">
    <h4 class="text-dark">
                Month Wise OPD Patient Data of the Year <?php echo date('Y'); ?>
            </h4>
    </div>
    <div class="card-card">
   

<div id="opd"></div>


      
  </div>
    </div>
  </div>
<!--  <div class="col-md-6">-->
<!--  <div class="card p-3">-->
<!--  <div class="card-header bg-light">-->
<!--    <h4 class="text-dark">-->
<!--                Month Wise OPD Bill Generated-->
<!--            </h4>-->
<!--    </div>-->
<!--    <div class="card-card">-->
   

<!--<div id="opdbill"></div>-->


      
<!--  </div>-->
<!--    </div>-->
<!--  </div>-->
<!--</div>-->

<!--<div class="row">-->
<div class="col-md-6">
  <div class="card p-3">
    <div class="card-header bg-light">
    <h4 class="text-dark">
                Month Wise IPD Patient Data of the Year <?php echo date('Y'); ?>
            </h4>
    </div>
    <div class="card-card">
   

<div id="ipd"></div>


      
  </div>
    </div>
  </div>
<!--  <div class="col-md-6">-->
<!--  <div class="card p-3">-->
<!--  <div class="card-header bg-light">-->
<!--    <h4 class="text-dark">-->
<!--                Month Wise IPD Bill Generated-->
<!--            </h4>-->
<!--    </div>-->
<!--    <div class="card-card">-->
   

<!--<div id="ipdbill"></div>-->


      
<!--  </div>-->
<!--    </div>-->
<!--  </div>-->
</div>


 
                <div class="row">
                    
               
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Today's Bed Lists</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id='datatable-buttons2' class="table table-bordered mb-0">
                                        <thead>
                                            <tr class="text-center font-weighted-bold">
                                             
                                               
                                                <th>Department</th>
                                                <th>Units</th>
                                                <th>Total Beds</th>
                                                <th>Occupied</th>

                                                <th>Available </th>
                                                <th>Male </th>
                                                <th>Female </th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            <?php 
                                            $tot_unit=$tot_bed=$tot_occup=0;
                                            while($row=mysqli_fetch_assoc($dept))
                                            {
                                            ?>
                                            <tr >
                                               
                                                <td><span><?= $row['dept_name']?></span></td>
                                                <td><span><?= $row['unit']?></span></td>
                                                <td><span class="badge badge-light-info"><?= $row['bed']?></span></td>

                                                <td><span class="badge badge-light-danger"><?= $row['occupied']?></span></td>


                                                                                                <td><span class="badge badge-light-success"><?= $row['bed']-$row['occupied']?></span></td>
                                                <td><span class="badge badge-light-success"><?= $row['male']?></span></td>
                                                <td><span class="badge badge-light-success"><?= $row['female']?></span></td>
                                            </tr>
                                          
                                           <?php $tot_unit +=$row['unit'];
                                                $tot_bed +=$row['bed'];
                                                $tot_occup +=$row['occupied'];
                                                } ?>
                                                <tr>
                                                    <th>Total</th>
                                                    <th><?= $tot_unit?></th>
                                                    <th><?= $tot_bed?></th>
                                                    <th><?= $tot_occup?></th>
                                                    <th><?= $tot_bed-$tot_occup?></th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                        </tbody>
                                    
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Month-<?= date("F");?> Bed Occupency Report</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0">
                                        <thead>
                                            <tr class="text-center font-weighted-bold">
                                             
                                               
                                                <th>Department</th>
                                                <th>Admissions</th>
                                                <th>Discharge</th>

                                                <th>Total </th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            <?php 
                                            $data=$newOP->getLastMonthBedList();
                                            // print_r($data);
                                            while($row=mysqli_fetch_assoc($data))
                                            {
                                            ?>
                                            <tr >
                                               
                                                <td><span><?= $row['dept_name']?></span></td>
                                                <td><span class="badge badge-light-info"><?= $row['occupied']?></span></td>
                                                <td><span class="badge badge-light-warning"><?= $row['discharge']?></span></td>
                                                <td><span class="badge badge-light-success"><?= $row['occupied']+$row['discharge']?></span></td>

                                            </tr>
                                          
                                           <?php } ?>
                                      
                                          
                                           
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>


                   
                </div>

<?php } ?>
            
</section>
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
<script>
    // Search form functionality
    $(document).ready(function() {
        // Advanced search toggle
       
        
        // Auto-submit on category change
        $('#q_lab_category_eq').change(function() {
            if ($(this).val() !== '') {
                $('#prescription_search').submit();
            }
        });
        
        // Show search results count
        var resultCount = $('tbody tr').length - 1; // Subtract 1 for header
        if (resultCount > 0) {
            $('.search-results-info').html('<small class="text-muted">Found ' + resultCount + ' results</small>');
        }
    });

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
$(document).ready(function() {
            $('#datatable-buttons2').DataTable({

                dom: 'Bfrtip',
                // responsive: false,
                // pageLength: 25,
                paging: false,
                // lengthMenu: [0, 5, 10, 20, 50, 100, 200, 500],
                searching: false,
                 info: false,
                 ordering: false,
                 columnDefs: [
                    { targets: '_all', className: 'no-padding' } // Remove padding for td elements
                  ],
                buttons: [
            
            {
                extend: 'pdfHtml5',
                title: "<?= $_SESSION['college_name'].",".$_SESSION['college_location']?>\n BED Todays Report",
               
                orientation: 'portrait',
                pageSize: 'A4',
                
            }
        ]

            });
});    
</script>




 <?php  $res=$newOP->chartOpdPatient(); 
            while($row4=mysqli_fetch_assoc($res))
            {
            $n_arr[$row4['month']] = $row4['count'];

            }
            for($l=1; $l<=12; $l++)
            {
                if(!isset($n_arr[$l]))
                {
                    $n_arr[$l]=0;
                }
            }
ksort($n_arr);
        $n_a["OPD Patients"] = 
            array(
                "data"=>$n_arr,
         );
 ?>
 <?php  $res=$newOP->chartIpdPatient(); 
            while($row4=mysqli_fetch_assoc($res))
            {
            $i_arr[$row4['month']] = $row4['count'];

            }
            for($l=1; $l<=12; $l++)
            {
                if(!isset($i_arr[$l]))
                {
                    $i_arr[$l]=0;
                }
            }
ksort($i_arr);
        $i_a["IPD Patients"] = 
            array(
                "data"=>$i_arr,
         );

 ?> 
 
 
 
<script>
    var options = {
          series: [{
          name: 'OPD Patients',
          data: [<?= implode(",", $n_a['OPD Patients']['data'])?>]
        },
    ],
          chart: {
          height: 350,
          type: 'bar',
        },
        plotOptions: {
          bar: {
            borderRadius: 10,
            dataLabels: {
              position: 'top', // top, center, bottom
            },
          }
        },
        dataLabels: {
          enabled: true,
          formatter: function (val) {
            return val ;
          },
          offsetY: -20,
          style: {
            fontSize: '12px',
            colors: ["#304758"]
          }
        },
        
        xaxis: {
          categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
          position: 'top',
          axisBorder: {
            show: false
          },
          axisTicks: {
            show: false
          },
          crosshairs: {
            fill: {
              type: 'gradient',
              gradient: {
                colorFrom: '#D8E3F0',
                colorTo: '#BED1E6',
                stops: [0, 100],
                opacityFrom: 0.4,
                opacityTo: 0.5,
              }
            }
          },
          tooltip: {
            enabled: true,
          }
        },
        yaxis: {
          axisBorder: {
            show: false
          },
          axisTicks: {
            show: false,
          },
          labels: {
            show: false,
            formatter: function (val) {
              return val ;
            }
          }
        
        },
        title: {
          text: 'OPD Patient Data',
          floating: true,
          offsetY: 330,
          align: 'center',
          style: {
            color: '#444'
          }
        }
        };

        var chart = new ApexCharts(document.querySelector("#opd"), options);
        chart.render();
        
        
        
                var options = {
          series: [{
          name: 'IPD Patients',
          data: [<?= implode(",", $i_a['IPD Patients']['data'])?>]
        },
    ],
          chart: {
          height: 350,
          type: 'bar',
        },
        plotOptions: {
          bar: {
            borderRadius: 10,
            dataLabels: {
              position: 'top', // top, center, bottom
            },
          }
        },
        dataLabels: {
          enabled: true,
          formatter: function (val) {
            return val;
          },
          offsetY: -20,
          style: {
            fontSize: '12px',
            colors: ["#304758"]
          }
        },
        
        xaxis: {
          categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
          position: 'top',
          axisBorder: {
            show: false
          },
          axisTicks: {
            show: false
          },
          crosshairs: {
            fill: {
              type: 'gradient',
              color:'20BF55',
              gradient: {
               
                colorFrom: '#20BF55', 
                colorTo: '#01BAEF',
                stops: [0, 100],
                opacityFrom: 0.4,
                opacityTo: 0.5,
              }
            }
          },
          tooltip: {
            enabled: true,
          }
        },
        yaxis: {
          axisBorder: {
            show: false
          },
          axisTicks: {
            show: false,
          },
          labels: {
            show: false,
            formatter: function (val) {
              return val ;
            }
          }
        
        },
        title: {
          text: 'IPD Patient Data',
          floating: true,
          offsetY: 330,
          align: 'center',
          style: {
            color: '#444'
          }
        }
        
        };

        var chart = new ApexCharts(document.querySelector("#ipd"), options);
        chart.render();
$(function(){
  $('[rel="popover"]').popover({
    html: true,
    trigger: 'hover',
    placement: 'left',
    // If using Bootstrap 4+ you may want sanitize:false to allow arbitrary HTML:
    // sanitize: false
  });
});
</script>

<!-- Sweet Alerts js -->
<script src="css/sweetalert2/sweetalert2.min.js"></script>

<!-- Sweet alert init js-->
<script src="js/sweet-alerts.init.js"></script>

<!-- <script src="./vendor/chartist/js/chartist.min.js"></script> -->

<script src="./vendor/moment/moment.min.js"></script>
<!-- <script src="./vendor/pg-calendar/js/pignose.calendar.min.js"></script> -->


<!-- <script src="./js/dashboard/dashboard-2.js"></script> -->
  
<!--<script src="charts.js"></script>-->


</body>

</html>