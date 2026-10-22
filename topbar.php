<?php
// include('model/loginClass.php');
    require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Calcutta");

if(strlen($_SESSION['username'])<2)
{
    header("Location:index.php");
}
// print_r($_SESSION);
// exit;
// exit;
// include('model/addClass.php');
?>
  <style>
      @media (min-width: 992px)
      {
          #ebook{
              margin-top:-87%;
          }
      }
  </style>
  
  <header id="page-topbar">
                <div class="navbar-header">
                    <div class="d-flex">
                          <!-- LOGO -->
                <div class="navbar-brand-box">
                    <a href="home.php" class="logo logo-dark">
                        <span class="logo-sm">
                        <img src="assets/images/Nexenstial Logo.jpg" alt="" height="50">
                            </span>
                        <span class="logo-lg">
                        <img src="assets/images/Nexenstial Logo.jpg" alt="" height="50">
                            </span>
                    </a>

                    <a href="home.php" class="logo logo-light">
                        <span class="logo-sm">
                                                       <img src="assets/images/Nexenstial Logo.jpg" alt="" height="50">
                            </span>
                        <span class="logo-lg">
                                <img src="assets/images/Nexenstial TM.jpg" alt="" height="50">
                            </span>
                    </a>
                </div>

             
                <button type="button" class="btn btn-sm px-3 font-size-24 d-lg-none header-item" data-toggle="collapse" data-target="#topnav-menu-content">
                            <i class="ri-menu-2-line align-middle"></i>
                        </button>

               
                        <div class="dropdown dropdown-mega d-none d-lg-block ml-2">
                          
                            <div class="dropdown-menu ">
                            <ul class="list-unstyled ">
                                                    <li>
                                                        <a href="javascript:void(0);">Lightbox</a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:void(0);">Range Slider</a>
                                                    </li>
</ul>

</div>
</div>

                     
                    </div>
<div class="h3 text-light text-left d-none d-lg-block">Sanjeevini Ayurveda Medical College and Hospital Hubli</div>
                    <div class="d-flex">

                        <div class="dropdown d-inline-block d-lg-none ml-2">
                            <button type="button" class="btn header-item noti-icon  waves-effect" id="page-header-search-dropdown"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="ri-search-line"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-0"
                                aria-labelledby="page-header-search-dropdown">
                    
                                <form class="p-3">
                                    <div class="form-group m-0">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Search ...">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="submit"><i class="ri-search-line"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        
 <div class="dropdown d-none d-sm-inline-block">
                            <button type="button" class="btn header-item waves-effect"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img id='langImg' src="assets/images/flags/us.jpg" alt="Header Language" height="16">
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                    
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item" onclick="translateLanguage('Hindi','india.png');">
                                    <img src="assets/images/flags/india.png" alt="user-image" class="mr-1" height="12"> <span class="align-middle">Hindi</span>
                                </a>

                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item" onclick="translateLanguage('Kannada','karnataka.png');">
                                    <img src="assets/images/flags/karnataka.png" alt="user-image" class="mr-1" height="12"> <span class="align-middle">Kannada</span>
                                </a>
                              
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item" onclick="translateLanguage('English','us.jpg');">
                                    <img src="assets/images/flags/us.jpg" alt="user-image" class="mr-1" height="12"> <span class="align-middle">English</span>
                                </a>
                                <a href="javascript:void(0);" class="dropdown-item notify-item" onclick="translateLanguage('Marathi','india.png');">
                                    <img src="assets/images/flags/india.png" alt="user-image" class="mr-1" height="12"> <span class="align-middle">Marathi</span>
                                </a>
                                 <a href="javascript:void(0);" class="dropdown-item notify-item" onclick="translateLanguage('Tamil','india.png');">
                                    <img src="assets/images/flags/india.png" alt="user-image" class="mr-1" height="12"> <span class="align-middle">Tamil</span>
                                </a>
                                <a href="javascript:void(0);" class="dropdown-item notify-item" onclick="translateLanguage('Telugu','india.png');">
                                    <img src="assets/images/flags/india.png" alt="user-image" class="mr-1" height="12"> <span class="align-middle">Telugu</span>
                                </a>
                                <a href="javascript:void(0);" class="dropdown-item notify-item" onclick="translateLanguage('Malayalam','india.png');">
                                    <img src="assets/images/flags/india.png" alt="user-image" class="mr-1" height="12"> <span class="align-middle">Malayalam</span>
                               </a>
                            </div>
                            <div id="google_translate_element" style="display:none"></div>
                        </div>
                       

                        <div class="dropdown d-none d-lg-inline-block ml-1">
                            <button type="button" class="btn header-item noti-icon waves-effect" data-toggle="fullscreen">
                                <i class="ri-fullscreen-line"></i>
                            </button>
                        </div>


                        <div class="dropdown d-inline-block user-dropdown">
                            <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img class="rounded-circle header-profile-user" src="assets/images/users/avatar-2.jpg"
                                    alt="Header Avatar">
                                <span class="d-none d-xl-inline-block ml-1"><?php echo $_SESSION['username'] ?></span>
                                <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <!-- item-->
                                <form action="staff-profile.php" method="post">
                            <input type="hidden" name="id" value="<?= $_SESSION['id']?>"><button type="submit" class="dropdown-item"><i class="ri-user-line align-middle mr-1"></i> Profile</button></form>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#changepwd"><i class="ri-wallet-2-line align-middle mr-1"></i>Change Password</a>
                               <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" href="logout.php"><i class="ri-shut-down-line align-middle mr-1 text-danger"></i> Logout</a>
                            </div>
                        </div>

                      
            
                    </div>
                </div>
                <style>iframe#\:2\.container {
    display: none;
}.topnav,body{top:0!important;}</style>
                <script type="text/javascript">
 function googleTranslateElementInit() {
        new google.translate.TranslateElement({
            pageLanguage: 'en', 
            autoDisplay: false,
            layout: google.translate.TranslateElement.InlineLayout.SIMPLE
        }, 'google_translate_element');
        
    }
    function translateLanguage(lang,flag) {
            googleTranslateElementInit();
            var $frame = $('.goog-te-menu-frame:first');
          
            $frame.contents().find('.goog-te-menu2-item span.text:contains(' + lang + ')').get(0).click();
            document.getElementById("langImg").src = "assets/images/flags/"+flag;
            return false;
        }

</script>
                <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
            </header>

    <?php  if($_SESSION['userrole']=='1' || $_SESSION['userrole']=='8' || $_SESSION['userrole']=='0') {?>
                <div class="topnav">
                    <div class="container-fluid">
                        <nav class="navbar navbar-light navbar-expand-lg topnav-menu">
    
                            <div class="collapse navbar-collapse" id="topnav-menu-content">
                                <ul class="navbar-nav">

                                  
    
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-uielement" role="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="ri-store-2-line mr-2"></i></i> Front Office<div class="arrow-down"></div>
                                        </a>

                                        <div class="dropdown-menu  "
                                            aria-labelledby="topnav-uielement">
                                          
                                            <a href="admission-enqery.php"class="dropdown-item">Admission Enquiry</a>
                        <a href="visitors-log.php"class="dropdown-item">Visitors Book</a>
                        <a href="phonelog.php"class="dropdown-item">Phone Call Log</a>
                        <a href="postaldispatch.php"class="dropdown-item">Postal Dispatch</a>

                        <a href="postalreceive.php"class="dropdown-item">Postal Receive</a>
                        <a href="complain.php"class="dropdown-item">Complaints</a>
                        <a href="setup-office.php"class="dropdown-item">Setup Front Office</a>
                                          

                                        </div>
                                    </li>
    
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-apps" role="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="ri-user-add-fill mr-2"></i>Students <div class="arrow-down"></div>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="topnav-apps">

                                      
                        <a href="students-admission.php"class="dropdown-item">Student Admission</a>
                        <a href="online_admissions.php"class="dropdown-item">Online Admissions</a>

                        <a href="students-details.php"class="dropdown-item">Student Details</a>
                        <a href="disable-students.php"class="dropdown-item">Disable Students</a>
                        
                                                  
                                                        
                                                   
                                             
                                                  
                                        </div>
                                    </li>
                                    
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-layout" role="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="ri-layout-3-line mr-2"></i>HR<div class="arrow-down"></div>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="topnav-layout">

                                      


                                            <a href="add-staff.php" class="dropdown-item">Add New Staff </a>
                                            <a href="view-staff.php" class="dropdown-item">View Staff </a>
                                            <a href="staff-attandence.php" class="dropdown-item">Staff Attendance</a>
                                            <a href="add-role.php" class="dropdown-item">Add Designation</a>
                                            <a href="add-department.php" class="dropdown-item">Add Department</a>
                                            <a href="teachers-feedback.php" class="dropdown-item">Feedback</a>
                                        </div>
                                    </li>
    
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-components" role="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="ri-stack-line mr-2"></i>Accounts <div class="arrow-down"></div>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="topnav-components">
                                            <div class="dropdown">
                                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-form"
                                                    role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Income <div class="arrow-down"></div>
                                                </a>
                                                <div class="dropdown-menu" aria-labelledby="topnav-form">
                                                <a href="view-income.php" class="dropdown-item">View Income  </a>


                                                    <a href="add-income.php" class="dropdown-item">Add Income  </a>
                                                <a href="income-source.php" class="dropdown-item">Add Income Source </a>


                                                   
                                                </div>
                                            </div>
                                            <div class="dropdown">
                                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-table"
                                                    role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Expense <div class="arrow-down"></div>
                                                </a>
                                                <div class="dropdown-menu" aria-labelledby="topnav-table">
                                                <a href="view-expenses.php" class="dropdown-item">View Expense  </a>


<a href="add-expenses.php" class="dropdown-item">Add Expense  </a>
<a href="expencive-source.php" class="dropdown-item">Add Expense Source </a>
                                                </div>
                                            </div>
                                            
                                            
                                            <div class="dropdown">
                                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-table"
                                                    role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Fee Collection <div class="arrow-down"></div>
                                                </a>
                                                <div class="dropdown-menu" aria-labelledby="topnav-table">
                                                <a href="collect-fee.php" class="dropdown-item">Collect Fee</a>
                                                <a href="searchfeepayment.php" class="dropdown-item">Search Fee Payments</a>
                                                <a href="search_fee_due.php" class="dropdown-item">Search Due Fees</a>
                                                <a href="fee_master.php" class="dropdown-item">Fee Master</a>
                                                <a href="fee_group.php" class="dropdown-item">Fee Group</a>
                                                <a href="fee_type.php" class="dropdown-item">Fee Type</a>
                                                <a href="fee_discount.php" class="dropdown-item">Fee Discount</a>
                                                </div>
                                            </div>
                                         
                                        </div>
                                    </li>
                                    
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-uielement" role="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="ri-pencil-ruler-2-line mr-2"></i>Academics<div class="arrow-down"></div>
                                        </a>

                                        <div class="dropdown-menu mega-dropdown-menu px-2 dropdown-mega-menu-xl"
                                            aria-labelledby="topnav-uielement">
                                            <div class="row">
                                                <div class="col-lg-4    ">
                                                    
                                                    <div>
                                                <h5 class="font-size-14 ml-4 text-danger">Curriculum</h5> <hr>


                                                <a href="class-timetable.php" class="dropdown-item">Class Time Table</a>
                        <a href="teacher-time-table.php" class="dropdown-item">Teacher Time Table</a>
                        <!-- <a href="assign-teacher.php" class="dropdown-item">Assign Class Teacher</a> -->
                        <a href="promote_students.php" class="dropdown-item">Promote Student</a>
                        <a href="assign-batches.php" class="dropdown-item">Add Batch</a>
                        <a href="add-subjects.php" class="dropdown-item">Add Subject</a>
                           
                            <a href="add-class.php" class="dropdown-item">Add Class</a>
                                                    
                                                    </div>
                                                </div>

                                                <div class="col-lg-4">
                                                    
                                                    <div>
                                                <h5 class="font-size-14 ml-4 text-danger">Lesson Plan</h5> <hr>


                        <a href="manage_lesson_plan.php" class="dropdown-item">Manage Lesson Plan</a>
                        <a href="syllabus_status.php" class="dropdown-item">Syllabus Status</a>
                        <a href="add_topics.php" class="dropdown-item">Add Topic</a>
                        <a href="add_lessions.php" class="dropdown-item">Add Lesson</a>
            

                      
                                                    
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    
                                                    <div>
                                                        
                                                <h5 class="font-size-14 ml-4 text-danger">Examination</h5> <hr>


                                               
                       
                        <a href="exam_group.php" class="dropdown-item">Exam</a>
                        <a href="exam_schedule.php" class="dropdown-item">Exam Schedule</a>
                        <a href="exam_results.php" class="dropdown-item">Exam Result</a>
            

                      
                                                    
                                                    </div>
                                                </div>
                                                
                                               
                                            </div>

                                        </div>
                                    </li>
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-layout" role="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="ri-layout-3-line mr-2"></i>Attendance <div class="arrow-down"></div>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="topnav-layout">

                                     


                                            <a href="add-attandence.php" class="dropdown-item">Class Attendance</a>
                                            <a href="lab-attandence.php" class="dropdown-item">Lab Attendance</a>
                                            <a href="nl-attandence.php" class="dropdown-item">NL Attendance</a>
                                            <!-- <a href="#" class="dropdown-item">View Attendance</a> -->
                                            <!-- <a href="approve-leave.php" class="dropdown-item">Approve Leave</a> -->
                                        </div>
                                    </li>
    
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-more" role="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="ri-file-copy-2-line mr-2"></i>Document<div class="arrow-down"></div>
                                        </a>

                                        <div class="dropdown-menu" aria-labelledby="topnav-apps">

                                      
</div>
                                        <div class="dropdown-menu" aria-labelledby="topnav-more">
<a href="upload_content.php"class="dropdown-item">Upload Content</a>

                                            <div class="dropdown">
                                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-auth"
                                                    role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Download Content <div class="arrow-down"></div>
                                                </a>
                                                <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                                    <a href="Assignment_logs.php" class="dropdown-item">Assignment</a>
                                                    <a href="download_syllabus.php" class="dropdown-item">Syllabus</a>
                                                    <a href="download_study_material.php" class="dropdown-item">Study Material</a>
                                                    <a href="other_donwload.php" class="dropdown-item">Other Download</a>
                                                </div>
                                            </div>
                                           
                                        </div>
                                    </li>

                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-layout" role="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="ri-layout-3-line mr-2"></i>Reports <div class="arrow-down"></div>

</a>
<div class="dropdown-menu dropdown-menu-right" aria-labelledby="topnav-layout">
<a href="student_information.php" class="dropdown-item">Student Reports</a>
<a href="lesson_plan_report.php" class="dropdown-item">Lesson Plan Reports</a>
<a href="attendance_report.php" class="dropdown-item">Attendance Reports</a>
<a href="staff_reports.php" class="dropdown-item">Staff Reports</a>

                                            
                                           
                                        </div>
                                    </li>
                              <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-more" role="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="ri-book-3-line mr-2"></i>e-Books<div class="arrow-down"></div>
                                        </a>
<div class="dropdown-menu dropdown-menu-right" aria-labelledby="topnav-layout">
<a href="1st Year.php" class="dropdown-item">1st Year</a>
<a href="2nd Year.php" class="dropdown-item">2nd Year</a>
<a href="3rd Year.php" class="dropdown-item">3rd Year</a>
<a href="4th Year.php" class="dropdown-item">4th Year</a>

                                            
                                           
                                        </div>
                                                
                                    </li>



                                    <li class="nav-item dropdown">
                                        <!-- <a class="nav-link dropdown-toggle arrow-none" href="library/controller/processLogin.php?ur=<?=$_SESSION['userrole'];?>" id="topnav-layout" role="button"
                                            > -->
                                            <a class="nav-link dropdown-toggle arrow-none" href="library/home.php" id="topnav-layout" role="button"
                                            >
                                            <i class="ri-layout-3-line mr-2"></i>Library 
                                        </a>
                                        
                                    </li>

                                   
    
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>

                <?php } else if ($_SESSION['userrole']=='3') { ?>


                    <div class="topnav">
                    <div class="container-fluid">
                        <nav class="navbar navbar-light navbar-expand-lg topnav-menu">
    
                            <div class="collapse navbar-collapse" id="topnav-menu-content">
                                <ul class="navbar-nav">

                                  
    
                                 
                                 
                             
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-components" role="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="ri-stack-line mr-2"></i>Income <div class="arrow-down"></div>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="topnav-components">
                                            <div class="dropdown">
                                                
                                                <a href="view-income.php" class="dropdown-item">View Income  </a>


                                                    <a href="add-income.php" class="dropdown-item">Add Income  </a>
                                                <a href="income-source.php" class="dropdown-item">Add Income Source </a>


                                                   
                                                </div>
                                            </div>
                                                                                   <!--</div>-->
                                    </li>
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-components" role="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="ri-stack-line mr-2"></i>Expense <div class="arrow-down"></div>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="topnav-components">
                                            <div class="dropdown">
                                                
                                               <a href="view-expenses.php" class="dropdown-item">View Expense  </a>


                                                <a href="add-expenses.php" class="dropdown-item">Add Expense  </a>
                                                <a href="expencive-source.php" class="dropdown-item">Add Expense Source </a>
                                                   
                                                </div>
                                            </div>
                                                                                   <!--</div>-->
                                    </li>
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-components" role="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="ri-stack-line mr-2"></i>Fee Collection <div class="arrow-down"></div>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="topnav-components">
                                            <div class="dropdown">
                                                
                                               <a href="collect-fee.php" class="dropdown-item">Collect Fee</a>
                                                <a href="searchfeepayment.php" class="dropdown-item">Search Fee Payments</a>
                                                <a href="search_fee_due.php" class="dropdown-item">Search Due Fees</a>
                                                <a href="fee_master.php" class="dropdown-item">Fee Master</a>
                                                <a href="fee_group.php" class="dropdown-item">Fee Group</a>
                                                <a href="fee_type.php" class="dropdown-item">Fee Type</a>
                                                <a href="fee_discount.php" class="dropdown-item">Fee Discount</a>
                                                   
                                                </div>
                                            </div>
                                       </div>
                                    </li>
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>

                <?php } elseif ($_SESSION['userrole']=='2') {?>
                    <div class="topnav">
                    <div class="container-fluid">
                        <nav class="navbar navbar-light navbar-expand-lg topnav-menu">
    
                            <div class="collapse navbar-collapse" id="topnav-menu-content">
                                <ul class="navbar-nav">

                                  
    
    
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-apps" role="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="ri-user-add-fill mr-2"></i>Student Information <div class="arrow-down"></div>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="topnav-apps">

                                      
                      
                        <a href="students-details.php"class="dropdown-item">Student Details</a>
                      
                        
                                                  
                                                        
                                                   
                                             
                                                  
                                        </div>
                                    </li>
                                    
                                   
                                   
                                    
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-uielement" role="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="ri-pencil-ruler-2-line mr-2"></i>Academics<div class="arrow-down"></div>
                                        </a>

                                        <div class="dropdown-menu mega-dropdown-menu px-2 dropdown-mega-menu-xl"
                                            aria-labelledby="topnav-uielement">
                                            <div class="row">
                                                <div class="col-lg-4    ">
                                                    
                                                    <div>
                                                <h5 class="font-size-14 ml-4 text-danger">Curriculum</h5> <hr>


                                                <a href="class-timetable.php" class="dropdown-item">Class Time Table</a>
                        <a href="teacher-time-table.php" class="dropdown-item">Teacher Time Table</a>
                        <!-- <a href="assign-teacher.php" class="dropdown-item">Assign Class Teacher</a> -->
                      
                       
                                                    
                                                    </div>
                                                </div>

                                                <div class="col-lg-4">
                                                    
                                                    <div>
                                                <h5 class="font-size-14 ml-4 text-danger">Lesson Plan</h5> <hr>


                        <a href="manage_lesson_plan.php" class="dropdown-item">Manage Lesson Plan</a>
                        <a href="syllabus_status.php" class="dropdown-item">Syllabus Status</a>
                        <a href="add_topics.php" class="dropdown-item">Add Topic</a>
                        <a href="add_lessions.php" class="dropdown-item">Add Lesson</a>
            

                      
                                                    
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    
                                                    <div>
                                                        
                                                <h5 class="font-size-14 ml-4 text-danger">Examination</h5> <hr>


                                               
                       
                        <a href="exam_group.php" class="dropdown-item">Exam</a>
                        <a href="exam_schedule.php" class="dropdown-item">Exam Schedule</a>
                        <a href="exam_results.php" class="dropdown-item">Exam Result</a>
            

                      
                                                    
                                                    </div>
                                                </div>
                                                
                                               
                                            </div>

                                        </div>
                                    </li>
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-layout" role="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="ri-layout-3-line mr-2"></i>Attendance <div class="arrow-down"></div>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="topnav-layout">

                                     


                                            <a href="add-attandence.php" class="dropdown-item">Class Attendance</a>
                                            <a href="lab-attandence.php" class="dropdown-item">Lab Attendance</a>
                                            <!-- <a href="#" class="dropdown-item">View Attendance</a> -->
                                            <!-- <a href="approve-leave.php" class="dropdown-item">Approve Leave</a> -->
                                        </div>
                                    </li>
    
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-more" role="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="ri-file-copy-2-line mr-2"></i>Document<div class="arrow-down"></div>
                                        </a>

                                        <div class="dropdown-menu" aria-labelledby="topnav-apps">

                                      
</div>
                                        <div class="dropdown-menu" aria-labelledby="topnav-more">
<a href="upload_content.php"class="dropdown-item">Upload Content</a>

                                            <div class="dropdown">
                                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-auth"
                                                    role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Download Content <div class="arrow-down"></div>
                                                </a>
                                                <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                                    <a href="Assignment_logs.php" class="dropdown-item">Assignment</a>
                                                    <a href="download_syllabus.php" class="dropdown-item">Syllabus</a>
                                                    <a href="download_study_material.php" class="dropdown-item">Study Material</a>
                                                    <a href="other_donwload.php" class="dropdown-item">Other Download</a>
                                                </div>
                                            </div>
                                           
                                        </div>
                                    </li>

                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-layout" role="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="ri-layout-3-line mr-2"></i>Reports <div class="arrow-down"></div>

</a>
<div class="dropdown-menu dropdown-menu-right" aria-labelledby="topnav-layout">
<a href="student_information.php" class="dropdown-item">Student Reports</a>
<a href="lesson_plan_report.php" class="dropdown-item">Lesson Plan Reports</a>
<a href="attendance_report.php" class="dropdown-item">Attendance Reports</a>
<!--<a href="staff_reports.php" class="dropdown-item">Staff Reports</a>-->

                                            
                                           
                                        </div>
                                    </li>
                              <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-more" role="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="ri-book-3-line mr-2"></i>e-Books<div class="arrow-down"></div>
                                        </a>
<div class="dropdown-menu dropdown-menu-right" aria-labelledby="topnav-layout">
<a href="1st Year.php" class="dropdown-item">1st Year</a>
<a href="2nd Year.php" class="dropdown-item">2nd Year</a>
<a href="3rd Year.php" class="dropdown-item">3rd Year</a>
<a href="4th Year.php" class="dropdown-item">4th Year</a>

                                            
                                           
                                        </div>
                                    </li>
                                   
    
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>

                <?php } elseif ($_SESSION['userrole']=='6') {?>
                    <div class="topnav">
                    <div class="container-fluid">
                        <nav class="navbar navbar-light navbar-expand-lg topnav-menu">
    
                            <div class="collapse navbar-collapse" id="topnav-menu-content">
                                <ul class="navbar-nav">

                               
    

                                    <li class="nav-item ">
                                   <a href="admission-enqery.php"class="nav-link"> <i class="ri-customer-service-2-fill mr-2"></i>Admission Enquiry</a>
                                   </li>
                                   <li class="nav-item ">
                                   <a href="visitors-log.php"class="nav-link"><i class="ri-team-line mr-2"></i>Visitors Book</a>
                                   </li>
                                   <li class="nav-item ">
                                   <a href="phonelog.php"class="nav-link"><i class="ri-phone-line  mr-2"></i>Phone Call Log</a>
                                   </li>
                                   <li class="nav-item ">
                                   <a href="postaldispatch.php"class="nav-link"><i class="ri-file-upload-line mr-2"></i>Postal Dispatch</a>
                                   </li>
                                   <li class="nav-item ">
                                   <a href="postalreceive.php"class="nav-link"> <i class="ri-file-download-line mr-2"></i>Postal Receive</a>
                                   </li>

                                   <li class="nav-item ">
                                   <a href="complain.php"class="nav-link"><i class="ri-chat-voice-line mr-2"></i>Complaints</a>
                                   </li>
                                   <li class="nav-item ">
                                   <a href="setup-office.php"class="nav-link"><i class="ri-equalizer-line mr-2"></i>Setup Front Office</a>
                                   </li>

    
    
                            
                                   
    
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>
                <?php }  elseif ($_SESSION['userrole']=='9') { ?>

                    <div class="topnav">
                    <div class="container-fluid">
                        <nav class="navbar navbar-light navbar-expand-lg topnav-menu">
    
                            <div class="collapse navbar-collapse" id="topnav-menu-content">
                                <ul class="navbar-nav">

                                  
    
                                    
    
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-apps" role="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="ri-user-add-fill mr-2"></i>Student Information <div class="arrow-down"></div>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="topnav-apps">

                                      
                        <a href="students-admission.php"class="dropdown-item">Student Admission</a>
                        <a href="students-details.php"class="dropdown-item">Student Details</a>
                        <a href="disable-students.php"class="dropdown-item">Disable Students</a>
                        
                                                  
                                                        
                                                   
                                             
                                                  
                                        </div>
                                    </li>
                                    
                                    
    
                                    
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-uielement" role="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="ri-pencil-ruler-2-line mr-2"></i>Academics<div class="arrow-down"></div>
                                        </a>

                                        <div class="dropdown-menu mega-dropdown-menu px-2 dropdown-mega-menu-xl"
                                            aria-labelledby="topnav-uielement">
                                            <div class="row">
                                                <div class="col-lg-4    ">
                                                    
                                                    <div>
                                                <h5 class="font-size-14 ml-4 text-danger">Curriculum</h5> <hr>


                                                <a href="class-timetable.php" class="dropdown-item">Class Time Table</a>
                        <a href="teacher-time-table.php" class="dropdown-item">Teacher Time Table</a>
                        <!-- <a href="assign-teacher.php" class="dropdown-item">Assign Class Teacher</a> -->
                        <a href="promote_students.php" class="dropdown-item">Promote Student</a>
                        <a href="assign-batches.php" class="dropdown-item">Add Batch</a>
                        <a href="add-subjects.php" class="dropdown-item">Add Subject</a>
                           
                            <a href="add-class.php" class="dropdown-item">Add Class</a>
                                                    
                                                    </div>
                                                </div>

                                                <div class="col-lg-4">
                                                    
                                                    <div>
                                                <h5 class="font-size-14 ml-4 text-danger">Lesson Plan</h5> <hr>


                        <a href="manage_lesson_plan.php" class="dropdown-item">Manage Lesson Plan</a>
                        <a href="syllabus_status.php" class="dropdown-item">Syllabus Status</a>
                        <a href="add_topics.php" class="dropdown-item">Add Topic</a>
                        <a href="add_lessions.php" class="dropdown-item">Add Lesson</a>
            

                      
                                                    
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    
                                                    <div>
                                                        
                                                <h5 class="font-size-14 ml-4 text-danger">Examination</h5> <hr>


                                               
                       
                        <a href="exam_group.php" class="dropdown-item">Exam</a>
                        <a href="exam_schedule.php" class="dropdown-item">Exam Schedule</a>
                        <a href="exam_results.php" class="dropdown-item">Exam Result</a>
            

                      
                                                    
                                                    </div>
                                                </div>
                                                
                                               
                                            </div>

                                        </div>
                                    </li>
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-layout" role="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="ri-layout-3-line mr-2"></i>Attendance <div class="arrow-down"></div>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="topnav-layout">

                                     


                                            <a href="add-attandence.php" class="dropdown-item">Class Attendance</a>
                                            <a href="lab-attandence.php" class="dropdown-item">Lab Attendance</a>
                                            <!-- <a href="#" class="dropdown-item">View Attendance</a> -->
                                            <!-- <a href="approve-leave.php" class="dropdown-item">Approve Leave</a> -->
                                        </div>
                                    </li>
    
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-more" role="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="ri-file-copy-2-line mr-2"></i>Document<div class="arrow-down"></div>
                                        </a>

                                        <div class="dropdown-menu" aria-labelledby="topnav-apps">

                                      
</div>
                                        <div class="dropdown-menu" aria-labelledby="topnav-more">
<a href="upload_content.php"class="dropdown-item">Upload Content</a>

                                            <div class="dropdown">
                                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-auth"
                                                    role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Download Content <div class="arrow-down"></div>
                                                </a>
                                                <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                                    <a href="Assignment_logs.php" class="dropdown-item">Assignment</a>
                                                    <a href="download_syllabus.php" class="dropdown-item">Syllabus</a>
                                                    <a href="download_study_material.php" class="dropdown-item">Study Material</a>
                                                    <a href="other_donwload.php" class="dropdown-item">Other Download</a>
                                                </div>
                                            </div>
                                           
                                        </div>
                                    </li>

                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-layout" role="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="ri-layout-3-line mr-2"></i>Reports <div class="arrow-down"></div>

</a>
<div class="dropdown-menu dropdown-menu-right" aria-labelledby="topnav-layout">
<a href="student_information.php" class="dropdown-item">Student Reports</a>
<a href="lesson_plan_report.php" class="dropdown-item">Lesson Plan Reports</a>
<a href="attendance_report.php" class="dropdown-item">Attendance Reports</a>
<a href="staff_reports.php" class="dropdown-item">Staff Reports</a>

                                            
                                           
                                        </div>
                                    </li>
                              <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-more" role="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="ri-book-3-line mr-2"></i>e-Books<div class="arrow-down"></div>
                                        </a>
<div class="dropdown-menu dropdown-menu-right" aria-labelledby="topnav-layout">
<a href="1st Year.php" class="dropdown-item">1st Year</a>
<a href="2nd Year.php" class="dropdown-item">2nd Year</a>
<a href="3rd Year.php" class="dropdown-item">3rd Year</a>
<a href="4th Year.php" class="dropdown-item">4th Year</a>

                                            
                                           
                                        </div>
                                    </li>
                                   
    
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>
                <?php }
                else{
                echo "<script>window.location='index.php'</script>";
                }
              ?>  
              <div class="modal fade" id="changepwd" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Change Password</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <!--<form action="" method="post">-->
       <div class="row">
           <div class="col-md-12">
             
                   <div class="form-group">
                       <label for="currentpwd"> Current Password <small class="text-danger">*</small></label>
                       <input type="password" class="form-control" name="currentpwd" id="currentpwd" placeholder="Enter Current Password" required>
                   </div>
           </div>
           <div class="col-md-12">
             
             <div class="form-group">
                 <label for="newpwd"> New Password <small class="text-danger">*</small></label>
                 <input type="password" class="form-control" name="newpwd" id="newpwd" placeholder="Enter New Password" required>
             </div>
     </div>
     <div class="col-md-12">
             
             <div class="form-group">
                 <label for="confirmpwd"> Confirm Password <small class="text-danger">*</small></label>
                 <input type="password" class="form-control" name="confirmpwd" id="confirmpwd" placeholder="Confirm Password" required>
             </div>
     </div>
     
       </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" name="submit" onclick="changePassword()">Save</button>
       <!--</form>-->

      </div>
    </div>
  </div>
  <script>
      function changePassword()
      {
          var opwd=document.getElementById('currentpwd').value;
          var npwd=document.getElementById('newpwd').value;
          var cpwd=document.getElementById('confirmpwd').value;
          if(opwd =='' || npwd =='' || cpwd =='')
          {
              alert('Please enter all fields');
              return false;
          }
          if(npwd==cpwd)
          {
           $.ajax({
              url: 'ajax.php',
                      type: 'POST', // Send post data
                      data: 'opwd='+opwd+'&npwd='+npwd+'&page=change_pwd',
                      async: false,
                      success: function(s){
                        // alert(s);
                        json_events = s;
                        alert(json_events);
                       window.location.reload()
        
                      }
                });
          }
          else{
              alert("password and confirm password not matching");
          }
      }
  </script>
</div>  
                
           


