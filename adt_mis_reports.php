<?php
date_default_timezone_set("Asia/Kolkata");  
require_once(__DIR__ . '/model/session_config.php');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>ADT MIS Reports</title>
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
          <h4 class="text-uppercase">ADT MIS Reports</h4>
        </div>


        <ul class="nav nav-pills mb-3 justify-content-around" id="pills-tab" role="tablist">
             <li class="nav-item">
    <a class="nav-link active" id="pills-ward-tab" data-toggle="pill" href="#pills-ward" role="tab" aria-controls="pills-ward" aria-selected="false"> Ward Register Report </a>
  </li>
 
  <li class="nav-item">
    <a class="nav-link" id="pills-ipd-tab" data-toggle="pill" href="#pills-ipd" role="tab" aria-controls="pills-ipd" aria-selected="false">IPD Admission Register Report </a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="pills-discharge-tab" data-toggle="pill" href="#pills-discharge" role="tab" aria-controls="pills-discharge" aria-selected="false">Discharge Register Report </a>
  </li>
   <li class="nav-item">
    <a class="nav-link" id="pills-discharge_sum-tab" data-toggle="pill" href="#pills-discharge_sum" role="tab" aria-controls="pills-discharge_sum" aria-selected="false">Discharge Summery Report </a>
  </li>
 
 
</ul>


    

       

<div class="tab-content" id="pills-tabContent">
 
 <div class="tab-pane fade show active  " id="pills-ward" role="tabpanel" aria-labelledby="pills-ward-tab"><br>

<div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
  <div class="row">
                <div class="col-md-12">
                    <h4>Select Criteria</h4><br>
                 
                </div>
            </div> 
<form action="#" href="#pills-ward" method="POST">
    <div class="row">
        <div class="col-md-4">
           <div class="form-group">
                  <label for="exampleInputEmail1"> From Date </label>
                  <input type="date" class="form-control"  name="op_from" id="op_from" required value="<?= $_REQUEST['op_from']?>">
                  
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                   <label for="exampleInputEmail1"> To Date  </label>
                   <input type="date" class="form-control"  name="op_to" id="op_to" required value="<?= $_REQUEST['op_to']?>">
                   
             </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="">Ward</label>
                <select name="ward" id="" class="form-control" require >
                    <option value="">Select Ward</option>
                    

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
                        <h5 class="ml-4">WARD REGISTER <span class="text-danger">
                            <?php if(isset($_REQUEST['op_from']) && isset($_REQUEST['op_to'])) {
                            echo "Between ".  date('d.m.Y',strtotime($_REQUEST['op_from']))." and ". date('d.m.Y',strtotime($_REQUEST['op_to']));
                             } ?>
                             </span></h5>
                             </div>
<div class="col-md-6">
                             <a href="print_ward_register_report.php" target="_blank" class="float-right badge badge-pill badge-danger" data-toggle="tooltip" data-placement="top" title="Print Lab Report"><i class="icofont-print"></i> Print </a>
                        </div>
                    </div>
                    <hr>

                    <div class="table-responsive">
                    <table id="datatable-buttons" class="table table-hoverde table-bordered display text-center dataTable no-footer" role="grid" aria-describedby="datatable-buttons_info">
                    <tbody><tr>
                <td align="left" colspan="15" >
                    Patients admitted during the period of &nbsp;14/11/2022 to 14/11/2022
                </td>
          </tr>
            <tr align="center" style="font-weight:bold;" class="textsmall">
                <td >Sl No.</td>
                <td >Admission Date &amp; Time</td>
		<td >UID</td>
                   
                <td >Admission Id</td>
                  
                
                <td >Patient Name</td>
                <td >Age</td>
                
                   <td >Address</td>
                 
                <td >Sex</td>
                
                   
              <td >Ward Name<br>
                    
                    
                    
                </td><td >Bed No</td>
                
                <td >Status</td>
            </tr>
         
            <tr>

            </tr>
        
            
            
       
        
        </tbody>    
                                       
                                            </table>  
                                            <table id="datatable-buttons" class="table mt-5 table-hoverde table-bordered display text-center dataTable no-footer" role="grid" aria-describedby="datatable-buttons_info">
                    <tbody><tr>
                <td align="left" colspan="15" >
                Patients admitted before the reporting period but discharged during the period of  14/11/2022 to 14/11/2022
                </td>
          </tr>
            <tr align="center" style="font-weight:bold;" class="textsmall">
                <td >Sl No.</td>
                <td >Admission Date &amp; Time</td>
		
                   
                <td >Admission Id</td>
                  
                
                <td >Patient Name</td>
                <td >Age</td>
                
                   <td >Address</td>
                 
                <td >Sex</td>
                
                   
              <td >Ward Name<br>
                    
                    
                    
                </td><td >Room No/Bed No</td>
                
                <td >Status</td>
            </tr>
         
            <tr>
                
            </tr>
        
            
            
       
        
        </tbody>    
                                       
                                            </table>   
                                            <table id="datatable-buttons" class="mt-5 table table-hoverde table-bordered display text-center dataTable no-footer" role="grid" aria-describedby="datatable-buttons_info">
                    <tbody><tr>
                <td align="left" colspan="15" >
                    
Patients admitted before the reporting period and present throughout the period during  14/11/2022 to 14/11/2022
                </td>
          </tr>
            <tr align="center" style="font-weight:bold;" class="textsmall">
                <td >Sl No.</td>
                <td >Admission Date &amp; Time</td>
		<td >UID</td>
                   
                <td >Admission Id</td>
                  
                
                <td >Patient Name</td>
                <td >Age</td>
                
                   <td >Address</td>
                 
                <td >Sex</td>
                
                   
              <td >Ward Name<br>
                    
                    
                    
                </td><td >Bed No</td>
                
                <td >Status</td>
            </tr>
         
            <tr>

            </tr>
        
            
            
       
        
        </tbody>    
                                       
                                            </table> 
                                            <table class="table mt-5 table-bordered">
                                            <tbody><tr>
                <td height="30px"  colspan="9" class="textheading">
                    Summary
                </td>
            </tr>
            <tr>
                <td align="left" colspan="8" >
                    Total Number of Patients admitted during the period  &nbsp;14/11/2022 to 14/11/2022 is 
                </td>
                <td >
                    0
                </td>
            </tr>
            		 
			<tr>
                <td align="left" colspan="8" >
                   Total Number of  Patients admitted before the reporting period but discharged during the period of &nbsp;
                 14/11/2022 to 14/11/2022 is
                </td>
                <td >
                    0
                        <input type="hidden" name="NICEHOSSecurity" value="IJTY-Y6BH-EHCX-KSWI-KGXJ-PTV4-Q7QJ-58MY" wtx-context="E552E9C4-6851-4BBC-8D84-1A46701E1B73"> 
                </td>
            </tr>
            <tr>
                <td align="left" colspan="8" >
                   Total Number of Patients admitted before the reporting period and present throughout the period during  &nbsp;
                   14/11/2022 to 14/11/2022is
                </td>
                <td >
                   0
                </td>
            </tr>
            
        </tbody>
                                            </table> 
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
    <!--end of Adult Minor report tab-->
<div class="tab-pane fade   " id="pills-ipd" role="tabpanel" aria-labelledby="pills-ipd-tab"><br>
<div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
  <div class="row">
                <div class="col-md-12">
                    <h4>Select Criteria</h4><br>
                 
                </div>
            </div> 
<form action="#" href="#pills-ipd" method="POST">
    <div class="row">
        <div class="col-md-3">
           <div class="form-group">
                  <label for="exampleInputEmail1"> From Date </label>
                  <input type="date" class="form-control"  name="op_from" id="op_from" required value="<?= $_REQUEST['op_from']?>">
                  
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1"> To Date  </label>
                   <input type="date" class="form-control"  name="op_to" id="op_to" required value="<?= $_REQUEST['op_to']?>">
                   
             </div>
        </div>
    
      <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1"> Department  </label>
                   <select name="dept" class="form-control">
                       <option value="">Select Department </option>
                   </select>
                   
             </div>
        </div>
             <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1"> Ward  </label>
                   <select name="ward" class="form-control">
                       <option value="">Select ward </option>
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
                        <h5 class="ml-4">IPD Patient Periodical Register Report <span class="text-danger">
                            <?php if(isset($_REQUEST['op_from']) && isset($_REQUEST['op_to'])) {
                            echo "Between ".  date('d.m.Y',strtotime($_REQUEST['op_from']))." and ". date('d.m.Y',strtotime($_REQUEST['op_to']));
                             } ?>
                             </span></h5>
                             </div>
<div class="col-md-6">
                             <a href="print_ip_admission_report.php" target="_blank" class="float-right badge badge-pill badge-danger" data-toggle="tooltip" data-placement="top" title="Print Lab Report"><i class="icofont-print"></i> Print </a>
                        </div>
                    </div>
                    <hr>

                    <div class="table-responsive">
                    <table id="datatable-buttons" class="table table-hoverde table-bordered display text-center dataTable no-footer" role="grid" aria-describedby="datatable-buttons_info">
                                    <thead class="thead-nex">
                                    <tr>
               <th width="36" ><strong class="style3">SL.</strong></th>
               <th width="132" ><strong class="style3">Reg. No. </strong></th>
               <th width="143" ><strong class="style3">IP No  </strong></th>
               <th width="90" ><strong class="style3">Patient name </strong></th>
		<th width="66" ><strong class="style3">Age/Sex</strong></th>
		<th width="133" ><strong class="style3">MRD No </strong></th>
		<th width="126" ><strong class="style3">MLC No.</strong></th>
			<th width="126" ><strong class="style3">Father / Husband Name</strong></th>
				<th width="126" ><strong class="style3">Mobile No.</strong></th>
					<th width="126" ><strong class="style3">Merital Status</strong></th>
						<th width="126" ><strong class="style3">Ration Card Type</strong></th>
							<th width="126" ><strong class="style3">Ration Card Type</strong></th>
								<th width="126" ><strong class="style3">Address</strong></th>
									<th width="126" ><strong class="style3">Admission On.</strong></th>
                <th width="126" ><strong class="style3">Dept</strong></th>
		<th width="128" ><strong class="style3">Ward </strong></th>
                
                <th width="133" ><strong class="style3">Current Status </strong></th>
                <th width="133" ><strong class="style3">Treating Doctor </strong></th>
                
                 <th width="133" ><strong class="style3">Diagnosis </strong></th>
                 <th width="133" ><strong class="style3">Condition During Discharge </strong></th>
              


             </tr>               
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>

                                            </tr>
                                        </tbody>
                                       
                                            </table>   
                    </div>
                </div>
            </div>
        </div>
    </div>

       

</div>
    <!--end of Summary report tab-->
    <div class="tab-pane fade   " id="pills-discharge" role="tabpanel" aria-labelledby="pills-discharge-tab"><br>
<div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
  <div class="row">
                <div class="col-md-12">
                    <h4>Select Criteria</h4><br>
                 
                </div>
            </div> 
<form action="#" href="#pills-discharge" method="POST">
    <div class="row">
        <div class="col-md-3">
           <div class="form-group">
                  <label for="exampleInputEmail1"> From Date </label>
                  <input type="date" class="form-control"  name="op_from" id="op_from" required value="<?= $_REQUEST['op_from']?>">
                  
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1"> To Date  </label>
                   <input type="date" class="form-control"  name="op_to" id="op_to" required value="<?= $_REQUEST['op_to']?>">
                   
             </div>
        </div>
    
      <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1"> Report Type  </label>
                  <select class="form-control" name="type"><option valu="">--SELECT ALL--</option>
                                      
                                       
                                         <option value="A" title="Absconded">Absconded</option><option value="B" title="Denied admission after BHT issue">Denied admission after BHT issue</option><option value="C" title="BHT Cancelled">BHT Cancelled</option><option value="D" title="Discharged">Discharged</option><option value="E" title="Expired">Expired</option><option value="F" title="Discharge by Referral">Discharge by Referral</option><option value="I" title="Transferred to Cabin/ICU or other Medical Department/Ward/Unit">Transferred to Cabin/ICU or other Medical Department/Ward/Unit</option><option value="L" title="Left against medical advice (LAMA)">Left against medical advice (LAMA)</option><option value="M" title="Missing from IPD">Missing from IPD</option><option value="N" title="Not present in ward">Not present in ward</option><option value="R" title="Discharged on request (DOR)">Discharged on request (DOR)</option>
                                     
                                    </select>
                   
             </div>
        </div>
             <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1"> Ward  </label>
                   <select name="ward" class="form-control">
                       <option value="">Select ward </option>
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
                        <h5 class="ml-4">Discharge Register Report <span class="text-danger">
                            <?php if(isset($_REQUEST['op_from']) && isset($_REQUEST['op_to'])) {
                            echo "Between ".  date('d.m.Y',strtotime($_REQUEST['op_from']))." and ". date('d.m.Y',strtotime($_REQUEST['op_to']));
                             } ?>
                             </span></h5>
                             </div>
<div class="col-md-6">
                             <a href="print_discharge_register_report.php" target="_blank" class="float-right badge badge-pill badge-danger" data-toggle="tooltip" data-placement="top" title="Print Lab Report"><i class="icofont-print"></i> Print </a>
                        </div>
                    </div>
                    <hr>

                    <div class="table-responsive">
                  <table align="center" class="mt-5 table table-bordered" width="100%" bgcolor="#000000" cellspacing="1" cellpadding="2" border="0">
                <tbody><tr>
                    <td align="left" colspan="15" bgcolor="#FFFFFF">
                        Patients admitted and &nbsp; Absconded &nbsp; during the period of &nbsp;14/11/2022 to 14/11/2022
                    </td>
                </tr>
                <tr align="center" style="background-color:White;font-weight:bold;" class="textsmall">
                    <td bgcolor="#FFFFFF">Sl No.</td>
                            
                        <td bgcolor="#FFFFFF">MRD No.</td>
                         <td bgcolor="#FFFFFF">ICD Code</td>
                          <td bgcolor="#FFFFFF">Days Of Stay</td>
                            
                    
                    <td bgcolor="#FFFFFF">Discharge Date &amp; Time</td>
                    
                    <td bgcolor="#FFFFFF">CR No</td>
                    
                    <td bgcolor="#FFFFFF">UHID</td>
                    <td bgcolor="#FFFFFF">Patient Name</td>
                    <td bgcolor="#FFFFFF"> Age</td>
                    <td bgcolor="#FFFFFF">Sex</td>
                    <td bgcolor="#FFFFFF">Ward Name</td>
                    
                    

                    
                    <td bgcolor="#FFFFFF">Status</td>
                          
                        <td bgcolor="#FFFFFF">Unit</td>
                         <td bgcolor="#FFFFFF">Other Disease Code</td>
                         
                    

                    
                    <td bgcolor="#FFFFFF">Admission Date &amp; Time</td>
                    
                    

                </tr>
              
                    
                   
                        
                         
                
            </tbody></table>
            <table align="center" class="mt-5 table table-bordered" width="100%" bgcolor="#000000" cellspacing="1" cellpadding="2" >
                <tbody><tr>
                    <td align="left" colspan="15" bgcolor="#FFFFFF">Patients admitted before the reporting period but &nbsp; Absconded&nbsp; during the period of &nbsp;14/11/2022 to 14/11/2022</td>
              </tr>
                    <tr align="center" style="background-color:White;font-weight:bold;" class="textsmall">
                        <td bgcolor="#FFFFFF">Sl No.</td>
                            
                        <td bgcolor="#FFFFFF">MRD No.</td>
                         <td bgcolor="#FFFFFF">ICD Code</td>
                           <td bgcolor="#FFFFFF">Days Of Stay</td>
                         
                       
                    <td bgcolor="#FFFFFF">Discharge Date &amp; Time</td>
                    
                        <td bgcolor="#FFFFFF">CR No</td>
                        
                        <td bgcolor="#FFFFFF">UHID</td>
                        <td bgcolor="#FFFFFF">Patient Name</td>
                        <td bgcolor="#FFFFFF">Age</td>
                        <td bgcolor="#FFFFFF">Sex</td>
                        <td bgcolor="#FFFFFF">Ward Name</td>
               
                    
                    <td bgcolor="#FFFFFF">Status</td>
                         
                        <td bgcolor="#FFFFFF">Unit</td>
                         <td bgcolor="#FFFFFF">Other Disease Code</td>
                         

                    
                    <td bgcolor="#FFFFFF">Admission Date &amp; Time</td>
                    

                    
                </tr>
               
                        
               
            </tbody></table>
            
            <table bgcolor="#000000"class="mt-5 table table-bordered" cellspacing="1" cellpadding="3" >
                <tbody><tr>
                    <td height="30px" bgcolor="#FFFFFF" colspan="9" class="textheading">
                        Summary
                        <input type="hidden" name="NICEHOSSecurity" value="AICR-PI9Q-JH3Y-MUJK-SHW3-VA85-ARZ0-H6EQ" wtx-context="B765B29C-646F-45C1-A844-66EFACD68560"> 
                    </td>
                </tr>
                <tr>
                    <td align="left" colspan="8" bgcolor="#FFFFFF">
                        Total Number of Patients admitted and &nbsp; Absconded &nbsp; during the period of &nbsp;14/11/2022 to 14/11/2022 is 
                  </td>
                        <td bgcolor="#FFFFFF">
                        0
                    </td>
                </tr>
                <tr>
                    <td align="left" colspan="8" bgcolor="#FFFFFF">
                        Total Number of Patients admitted before the reporting period but &nbsp; Absconded &nbsp; during the period of &nbsp;14/11/2022 to 14/11/2022 is
                  </td>
                        <td bgcolor="#FFFFFF">
                        0
                    </td>
                </tr>

            </tbody></table>
                    </div>
                </div>
            </div>
        </div>
    </div>

       

</div>
    <!--end of Summary report tab-->
    <div class="tab-pane fade   " id="pills-discharge_sum" role="tabpanel" aria-labelledby="pills-discharge_sum-tab"><br>
<div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
  <div class="row">
                <div class="col-md-12">
                    <h4>Select Criteria</h4><br>
                 
                </div>
            </div> 
<form action="#" href="#pills-discharge_sum" method="POST">
    <div class="row">
        <div class="col-md-3">
           <div class="form-group">
                  <label for="exampleInputEmail1"> From Date </label>
                  <input type="date" class="form-control"  name="op_from" id="op_from" required value="<?= $_REQUEST['op_from']?>">
                  
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1"> To Date  </label>
                   <input type="date" class="form-control"  name="op_to" id="op_to" required value="<?= $_REQUEST['op_to']?>">
                   
             </div>
        </div>
    
      <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1"> Department  </label>
                   <select name="dept" class="form-control">
                       <option value="">Select Department </option>
                   </select>
                   
             </div>
        </div>
             <div class="col-md-3">
            <div class="form-group">
                   <label for="exampleInputEmail1"> Ward  </label>
                   <select name="ward" class="form-control">
                       <option value="">Select ward </option>
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
                        <h5 class="ml-4">Discharge Summary Report <span class="text-danger">
                            <?php if(isset($_REQUEST['op_from']) && isset($_REQUEST['op_to'])) {
                            echo "Between ".  date('d.m.Y',strtotime($_REQUEST['op_from']))." and ". date('d.m.Y',strtotime($_REQUEST['op_to']));
                             } ?>
                             </span></h5>
                             </div>
<div class="col-md-6">
                             <a href="print_discharge_summary_report.php" target="_blank" class="float-right badge badge-pill badge-danger" data-toggle="tooltip" data-placement="top" title="Print discharge summary Report"><i class="icofont-print"></i> Print </a>
                        </div>
                    </div>
                    <hr>

                      <table width="1121"class="table table-bordered text-center">
                        
                        <thead>
                            <tr >
                                <th rowspan="2">Sl. No.</th>
                                 <th rowspan="2">Dept./Unit</th>
                                  <th rowspan="2">Total Discharge</th>
                                <th colspan="2" >Adult</th>
                                <th colspan="2" >Minor</th>
                                <th rowspan="2" >Others</th>
                            </tr>
                            <tr>
                                <th>Male</th>
                                 <th>Female</th>
                                 <th>Male</th>
                                 <th>Female</th>
                            </tr>
                        </thead>
	  
      <tbody>
      
      <tr>
	   <td align="center" class="style4">1</td>
        <td align="center" class="style4">CARDIOLOGY-UNIT A</td>
		 <td align="center" class="style4">11</td>
    
         
            <td align="center" >6</td>
            <td align="center" >5</td>
              <td align="center" >6</td>
            <td align="center" >5</td>
              <td align="center" >6</td>
            
          </tr>
        
       
    </tbody></table>
                </div>
            </div>
        </div>
    </div>

       

</div>
    <!--end of Summary report tab-->
    
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