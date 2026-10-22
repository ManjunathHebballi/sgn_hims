<?php
require_once(__DIR__ . '/model/session_config.php');
if(!isset($_SESSION['loginid']))
{
echo "<script>window.location='index.php';</script>";
}
?>


<link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
<link rel="stylesheet" href="icofont/icofont.min.css">
 
     <!--**********************************
            Nav header start
        ***********************************-->
        <div class="nav-header">
            <a href="dashboard.php" class="brand-logo">
                 <img class="logo-abbr" src="../images/favicon.png" alt=""> 
                <img class="logo-compact" src="" alt="sdffd"> 
                <img class="brand-title" src="./images/Nexenstial TM.jpg"  alt="ddd">
            </a>

            <div class="nav-control">
                <div class="hamburger">
                    <span class="line"></span><span class="line"></span><span class="line"></span>
                </div>
            </div>
        </div>
        <!--**********************************
            Nav header end
        ***********************************-->

        <!--**********************************
            Header start
        ***********************************-->
        <div class="header">
            <div class="header-content">
                <nav class="navbar navbar-expand">
                    <div class="collapse navbar-collapse justify-content-between">
                       <h5 class="text-light d-none d-sm-block">Swaminarayan University, Kalol </h5>

                        <ul class="navbar-nav header-right float-right">
                            
                            <li class="nav-item dropdown header-profile ">
                                <a href="logout.php" class="text-white ">
                                    <i class="ri-shut-down-line ri-1x"></i>  <span class="ml-2 ">Logout </span>
                                </a>
                               
                               
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
        <!--**********************************
            Header end ti-comment-alt
        ***********************************-->

        <!--**********************************
            Sidebar start
        ***********************************-->
        <div class="quixnav">
            <div class="quixnav-scroll">
                <ul class="metismenu" id="menu">
                    <li class="nav-label first text-light">Main Menu</li>
                    <?php if($_SESSION['role']=='Admin') {?>
                    <li><a href="./dashboard.php" aria-expanded="false"><i class="icofont-dashboard"></i><span class="nav-text">Dashboard</span></a>
                       
                    </li>
                   
                    <li><a href="./master.php" aria-expanded="false"><i class="icofont-learn"></i><span class="nav-text">Master</span></a>
                 
                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i class="icofont-user-alt-1"></i><span class="nav-text">Out Patient </span></a>
                        <ul aria-expanded="false">
                        <li><a href="./opregistration.php">OP Registration</a></li>
                            <li><a href="./opfollowup.php">OP Follow Up</a></li>
                            
                            <!--<li><a href="./todayoppatient.php">OP Patient List</a></li>-->
                            <li><a href="./oppatient.php">Patient List</a></li>
                        </ul>
                    </li>
                    
                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i class="icofont-crutch"></i><span class="nav-text">IN Patient</span></a>
                        <ul aria-expanded="false">
                            <li><a href="./ipregistration.php">IP Registration</a></li>
                            <li><a href="./ippatient.php">Treatment</a></li>
                            <li><a href="./discharge.php">Discharge</a></li>
                            <li><a href="./dischargelist.php">Discharge List</a></li>
                            <!--<li><a href="#">Diet Chart</a></li>-->
                            <!--<li><a href="#">Daily Diet Reqirment</a></li>-->
                            <li><a href="./anaesthesia.php">Anaesthesia</a></li>
                            </li>                     
                            
                            
                   
    </ul>     
                            
                    </li>
                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i class="icofont-test-tube-alt"></i><span class="nav-text">Lab</span></a>
                        <ul aria-expanded="false">
                            <li><a href="./lab_test_group.php">Add Test Group</a></li>
                            <li><a href="./lab_test_name.php">Add Test Name</a></li>
                            <li><a href="./view-test-list.php">Test List </a></li>
                                               
                            
                            
                   
    </ul>     
                            
                    </li>
                            
                    </li>
                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i
                                class="icofont-hospital "></i><span class="nav-text">Lab Result</span></a>
                        <ul aria-expanded="false">
                            <li><a href="./oplab_result.php">OP Lab</a></li>
                            <li><a href="./iplab_result.php">IP Lab</a></li>
                        </ul>
                            
                    </li>
                    
  <!--<li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i-->
  <!--                              class="icon icon-chart-bar-33"></i><span class="nav-text">Pharmacy</span></a>-->
  <!--                      <ul aria-expanded="false">-->
  <!--                           <li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i class="icofont-users-alt-3"></i><span class="nav-text">Manufacturers</span></a>-->
  <!--                      <ul aria-expanded="false">-->
  <!--                      <li><a href="./add_manufacturer.php">Add Manufacturers</a></li>-->
  <!--                          <li><a href="./view_manufacturer.php">View Manufacturers</a></li>-->
                            
                            <!--<li><a href="./manufacturer_ledger.php">Manufacturers Ledger</a></li>-->
                            
  <!--                      </ul>-->
  <!--                  </li>-->
  <!--                  <li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i class="icofont-pills"></i><span class="nav-text">Medicine</span></a>-->
  <!--                      <ul aria-expanded="false">-->
  <!--                      <li><a href="./add_category.php">Add Category</a></li>-->
  <!--                          <li><a href="./add_units.php">Add Units</a></li>-->
                            
  <!--                          <li><a href="./add_types.php">Add Types</a></li>-->
  <!--                          <li><a href="./add_medicines.php">Add Medicines</a></li>-->
  <!--                          <li><a href="./view_medicines.php">View Medicines</a></li>-->


  <!--                      </ul>-->
  <!--                  </li>-->

                    <!--<li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i class="icofont-cart"></i> <span class="nav-text"> Purchase </span></a>-->
                    <!--    <ul aria-expanded="false">-->
                    <!--    <li><a href="./add_purchase.php">Add Purchase</a></li>-->
                    <!--        <li><a href="./view_purchase.php">Purchase List</a></li>-->
                            

                            
                    <!--    </ul>-->
                    <!--</li>-->
                    <!--<li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i class="icofont-retweet"></i> <span class="nav-text"> Return </span></a>-->
                    <!--    <ul aria-expanded="false">-->
                    <!--    <li><a href="./add_returns.php">Add Return</a></li>-->
                    <!--    <li><a href="./invoice_return_list.php">Invoice Return List</a></li>-->
                    <!--      <li><a href="./manufacturer_invoice_list.php">Manufacturer Return List</a></li>-->
                        
                     
                        
                            
                            

                            
                    <!--    </ul>-->
                    <!--</li>-->
  <!--                          <li><a href="./opd_pharma.php">OPD Patient</a></li>-->
  <!--                              <li><a href="./phbilling.php">IPD Patient</a></li>-->
  <!--                              <li><a href="./directsale.php">Walkin Patient</a></li>-->
                                <!--<li><a href="./invoice_list.php">Invoice List</a></li>-->
  <!--                          </ul>-->
  <!--                  </li>    -->
                  
                   <li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i
                                class="icon icon-app-store"></i><span class="nav-text">OT Registers</span></a>
                        <ul aria-expanded="false">
                        
                            <li><a href="./stockragister.php">Stock Register</a></li>
                            <li><a href="./padsregister.php">Pads Register</a></li>
                            <li><a href="./swabregister.php">Swab Register</a></li>
                            <li><a href="./fumigation.php">Fumigation Register</a></li>
                            <li><a href="./auto.php">Auto Clave Register</a></li>
                            <li><a href="./ot.php">OT Register</a></li>

    </ul></li>


                    
                    
    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i class="icofont-bill-alt"></i><span class="nav-text">Billing</span></a>
                        <ul aria-expanded="false">
                            <li><a href="./ippatient_billing.php">IP Billing</a></li>
                          
                        </ul>
                    </li>

                <li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i
                                class="icofont-prescription"></i><span class="nav-text">Reports</span></a>
                        <ul aria-expanded="false">
                            <li><a href="./opreports.php">Out Patient</a></li>
                            <li><a href="./ipreports.php">IN Patient</a></li>
                            <li><a href="./lab_reports.php">Laboratory</a></li>
                            <!--<li><a href="./lab.php">Pharmacy</a></li>-->
                            <li><a href="./otreports.php">Operation Theater</a></li>
                              <li><a href="./others_reports.php">Others Reports</a></li>  
                            
                        </ul>
                    </li>
                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i
                                class="icofont-settings-alt"></i><span class="nav-text">Settings</span></a>
                        <ul aria-expanded="false">
                            <li><a href="./edit-timetable.php">Staff TimeTable</a></li>
                            <li><a href="./add-user.php">Users</a></li>
                          
                            
                        </ul>
                    </li>

<?php } else if($_SESSION['role']=='Doctor') {?>
         <li>
             <a href="./dashboard.php" aria-expanded="false"><i class="icofont-dashboard"></i><span class="nav-text">Dashboard</span></a>
        </li>
        <li>
            <a class="has-arrow" href="javascript:void()" aria-expanded="false"><i class="icofont-user-alt-1"></i><span class="nav-text">Out Patient </span></a>
            <ul aria-expanded="false">
                <li><a href="./oppatient.php">OP Patient List</a></li>
            </ul>
        </li>
        <li>
            <a class="has-arrow" href="javascript:void()" aria-expanded="false"><i class="icofont-crutch"></i><span class="nav-text">IN Patient</span></a>
            <ul aria-expanded="false">
                <li><a href="./ippatient.php">Treatment</a></li>
                <li><a href="./discharge.php">Discharge</a></li>
                <li><a href="./dischargelist.php">Discharge List</a></li>
                <li><a href="./anaesthesia.php">Anaesthesia</a></li>
            </ul>     
        </li>
        <li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i
                                        class="icofont-prescription"></i><span class="nav-text">Reports</span></a>
            <ul aria-expanded="false">
                <li><a href="./opreports.php">Out Patient</a></li>
                <li><a href="./ipreports.php">IN Patient</a></li>
                <li><a href="./lab_reports.php">Laboratory</a></li>
                <li><a href="./lab.php">Pharmacy</a></li>
                <li><a href="./otreports.php">Operation Theater</a></li>  
            </ul>
        </li>     
<?php } else if($_SESSION['role']=='Lab'){ ?> 
<li><a href="./dashboard.php" aria-expanded="false"><i class="icofont-dashboard"></i><span class="nav-text">Dashboard</span></a>
                       
                    </li>
<li>
<a class="has-arrow" href="javascript:void()" aria-expanded="false"><i class="icofont-user-alt-1"></i><span class="nav-text">Patients </span></a>
            <ul aria-expanded="false">
                <li><a href="./oppatient.php">OP Patient List</a></li>
<li><a href="./ippatient.php">IP Patient List</a></li>
</ul>     
        </li>

                   
 <li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i class="icofont-test-tube-alt"></i><span class="nav-text">Lab</span></a>
                        <ul aria-expanded="false">
                            <li><a href="./lab_test_group.php">Add Test Group</a></li>
                            <li><a href="./lab_test_name.php">Add Test Name</a></li>
                            <li><a href="./view-test-list.php">Test List </a></li>
                                               
                            
                            
                   
    </ul>     
                            
                    </li>
<li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i
                                class="icofont-hospital "></i><span class="nav-text">Lab Result</span></a>
                        <ul aria-expanded="false">
                            <li><a href="./oplab_result.php">OP Lab</a></li>
                            <li><a href="./iplab_result.php">IP Lab</a></li>
                        </ul>
                            
                    </li>
<li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i
                                        class="icofont-prescription"></i><span class="nav-text">Reports</span></a>
            <ul aria-expanded="false">
                <li><a href="./lab_reports.php">Laboratory</a></li>
                <li><a href="./opreports.php">Out Patient</a></li>
                <li><a href="./ipreports.php">IN Patient</a></li>
                <li><a href="./lab.php">Pharmacy</a></li>
                <li><a href="./otreports.php">Operation Theater</a></li> 
              
                
                
            </ul>
        </li> 
<?php } else if($_SESSION['role']=='Receptionist') {?>
<li><a href="./dashboard.php" aria-expanded="false"><i class="icofont-dashboard"></i><span class="nav-text">Dashboard</span></a>
                       
                    </li>
                   
                    <li><a href="./master.php" aria-expanded="false"><i class="icofont-learn"></i><span class="nav-text">Master</span></a>
                 
                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i class="icofont-user-alt-1"></i><span class="nav-text">Out Patient </span></a>
                        <ul aria-expanded="false">
                        <li><a href="./opregistration.php">OP Registration</a></li>
                            <li><a href="./opfollowup.php">OP Follow Up</a></li>
                            
                            <li><a href="./oppatient.php">OP Patient List</a></li>
                        </ul>
                    </li>
                    
                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i class="icofont-crutch"></i><span class="nav-text">IN Patient</span></a>
                        <ul aria-expanded="false">
                            <li><a href="./ipregistration.php">IP Registration</a></li>
                            <li><a href="./ippatient.php">Treatment</a></li>
                            <li><a href="./discharge.php">Discharge</a></li>
                            <li><a href="./dischargelist.php">Discharge List</a></li>
                        </ul>
                    </li>
<li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i
                                        class="icofont-prescription"></i><span class="nav-text">Reports</span></a>
            <ul aria-expanded="false">
                
                <li><a href="./opreports.php">Out Patient</a></li>
                <li><a href="./ipreports.php">IN Patient</a></li>
                <li><a href="./lab_reports.php">Laboratory</a></li>
                <li><a href="./lab.php">Pharmacy</a></li>
                <li><a href="./otreports.php">Operation Theater</a></li>  
            </ul>
        </li>

       
         <li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i
                                class="icofont-settings-alt"></i><span class="nav-text">Settings</span></a>
                        <ul aria-expanded="false">
                            <li><a href="./edit-timetable.php">Staff TimeTable</a></li>
                          
                            
                        </ul>
                    </li>
<?php } else if($_SESSION['role']=='Pharmacist') {?>
<li><a href="./dashboard.php" aria-expanded="false"><i class="icofont-dashboard"></i><span class="nav-text">Dashboard</span></a>
                       
                    </li>
<!--<li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i-->
<!--                                class="icon icon-chart-bar-33"></i><span class="nav-text">Pharmacy</span></a>-->
<!--                        <ul aria-expanded="false">-->
<!--                            <li><a href="./patient_billing.php">Prescription</a></li>-->
<!--                            <li><a href="./phbilling.php">Patient Sales</a></li>-->
<!--                            <li><a href="./directsale.php">Direct Sales</a></li>-->
<!--                            <li><a href="./salehistory.php">Sales History</a></li>-->
<!--                            <li><a href="./salesreturn.php">Sales Retures</a></li>-->
<!--                            <li><a href="./returnhistory.php">Sales Returen History</a></li>-->
<!--                            <li><a href="./chart-flot.html">Store Indent</a></li>-->
<!--                            <li><a href="./chart-morris.html">Receive Indent</a></li>-->
<!--                            <li><a href="./chart-sparkline.html">Indent Stock Details </a></li>-->
<!--                            <li><a href="./chart-chartjs.html">Stock Transfer</a></li>-->
<!--                            <li><a href="./chart-chartist.html">Reject stock transfer</a></li>-->
<!--                            <li><a href="./chart-peity.html">Item Consumption</a></li>-->
<!--                            </ul>-->
<!--                    </li>    -->
                     <li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i class="icofont-users-alt-3"></i><span class="nav-text">Manufacturers</span></a>
                        <ul aria-expanded="false">
                        <li><a href="./add_manufacturer.php">Add Manufacturers</a></li>
                            <li><a href="./view_manufacturer.php">View Manufacturers</a></li>
                            
                            <li><a href="./manufacturer_ledger.php">Manufacturers Ledger</a></li>
                            
                        </ul>
                    </li>
                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i class="icofont-pills"></i><span class="nav-text">Medicine</span></a>
                        <ul aria-expanded="false">
                        <li><a href="./add_category.php">Add Category</a></li>
                            <li><a href="./add_units.php">Add Units</a></li>
                            
                            <li><a href="./add_types.php">Add Types</a></li>
                            <li><a href="./add_medicines.php">Add Medicines</a></li>
                            <li><a href="./view_medicines.php">View Medicines</a></li>


                        </ul>
                    </li>

                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i class="icofont-cart"></i> <span class="nav-text"> Purchase </span></a>
                        <ul aria-expanded="false">
                        <li><a href="./add_purchase.php">Add Purchase</a></li>
                            <li><a href="./view_purchase.php">Purchase List</a></li>
                            

                            
                        </ul>
                    </li>
                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i class="icofont-retweet"></i> <span class="nav-text"> Return </span></a>
                        <ul aria-expanded="false">
                        <li><a href="./add_returns.php">Add Return</a></li>
                        <li><a href="./invoice_return_list.php">Invoice Return List</a></li>
                          <li><a href="./manufacturer_invoice_list.php">Manufacturer Return List</a></li>
                            

                            
                        </ul>
                        
                                                  <li><a href="./patient_billing.php">Prescription</a></li>
                                <li><a href="./phbilling.php">Patient Sales</a></li>
                                <li><a href="./directsale.php">Direct Sales</a></li>
                                <li><a href="./invoice_list.php">Invoice List</a></li>
                    </li>
                    
                    
                        
<li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i
                                        class="icofont-prescription"></i><span class="nav-text">Reports</span></a>
            <ul aria-expanded="false">
                <li><a href="./lab.php">Pharmacy</a></li>
                <li><a href="./opreports.php">Out Patient</a></li>
                <li><a href="./ipreports.php">IN Patient</a></li>
                <li><a href="./lab_reports.php">Laboratory</a></li>
                <li><a href="./otreports.php">Operation Theater</a></li>  
            </ul>
        </li>
        
       
        
<?php } else {?>
<li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i
                                        class="icofont-prescription"></i><span class="nav-text">Reports</span></a>
            <ul aria-expanded="false">
                <!--<li><a href="./lab.php">Pharmacy</a></li>-->
                <li><a href="./opreports.php">Out Patient</a></li>
                <li><a href="./ipreports.php">IN Patient</a></li>
                <li><a href="./lab_reports.php">Laboratory</a></li>
                <li><a href="./otreports.php">Operation Theater</a></li>  
            </ul>
        </li>
<?php } ?>
    </ul> 
            </div>


        </div>
        <!--**********************************
            Sidebar end
        ***********************************-->

        <script>
            $(function () {
  $('[data-toggle="tooltip"]').tooltip();
  $('#a').tooltip('show')
})
        </script>