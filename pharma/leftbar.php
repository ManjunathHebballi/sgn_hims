<?php
require_once(__DIR__ . '/model/session_config.php');
// error_reporting(0);

// if(!isset($_SESSION['loginid']))
// {
// echo "<script>window.location='index.php';</script>";
// }
?>


<link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
<link rel="stylesheet" href="../icofont/icofont.min.css">
 
     <!--**********************************
            Nav header start
        ***********************************-->
        <div class="nav-header">
            <a href="#" class="brand-logo">
                <!-- <img class="logo-abbr" src="./images/fav.png" alt=""> -->
                <img class="logo-compact" src="" alt="sdffd"> N
                <img class="brand-title" src="../images/Nexenstial TM.jpg"  alt="ddd">
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
                       <h5 class="text-light d-sm-none d-md-block">Shri J. G. Co-Operative Ayurvedic Hospital and Research Institute, Ghataprabha</h5>

                        <ul class="navbar-nav header-right">
                            
                            <li class="nav-item dropdown header-profile">
                                <a href="logout.php" class="text-white">
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
                    <li><a href="../dashboard.php" aria-expanded="false"><i class="icofont-dashboard"></i><span class="nav-text">Dashboard</span></a>
                       
                    </li>
                   
                    <!-- <li><a href="../master.php" aria-expanded="false"><i class="icofont-learn"></i><span class="nav-text">Master</span></a> -->
                 
                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i class="icofont-pills"></i><span class="nav-text">Medicine</span></a>
                        <ul aria-expanded="false">
                        <li><a href="./add_category.php">Add Category</a></li>
                            <li><a href="./add_units.php">Add Units</a></li>
                            
                            <li><a href="./add_types.php">Add Types</a></li>
                            <li><a href="./add_medicines.php">Add Medicines</a></li>
                            <li><a href="./view_medicines.php">View Medicines</a></li>


                        </ul>
                    </li>
                    
                


                    
               
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