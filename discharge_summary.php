<?php 

require_once(__DIR__ . '/model/session_config.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#319197">

    <title>Discharge Summary</title>
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


     <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
     <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap4.min.css">


    
    


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
        <div class="content-body ">
            <!-- row -->
      
            <div class="container-fluid ">

         

        <div class="card">
            <div class="card-body">
            <a href="print_discharge_summary.php" class="btn btn-danger float-right"><i class="ri-printer-line"></i> Print</a>

    
<h4 class=" text-center">Discharge Summary of <span class="text-danger">
KOMAL SHETTY (IP220506)
</span> </h4>

<div class="row">







<div class="container">
<div class="row">
 <div class="col-md-12  ">
 <br>
 <table class="table ">    
<tbody><tr>
<th class="border0">	Central OPD</th>
<td class="border0">2206580</td>

<th>Central IP</th>
<td>IP220506</td>
<th>Department</th>
<td>Panchakarma</td>

</tr>
<tr>
<th class="border0">Patient Name:</th>
<td class="border0">KOMAL SHETTY</td>


<th>Age:   </th>
<td>40</td>
<th>Sex:</th>
<td>Male</td>
</tr>

<tr>
<th>Caste</th>
<td>Genaral</td>
<th>Address</th>
<td>ALEVOOR 0</td>
<th>Date Of Admission:</th>
<td>25-02-2022</td>

</tr>
<tr>
<th>Time Of Admission:</th>
<td>12:20:20 pm</td>
<th>Ward:</th>
<td>Male</td>
<th>Bed No:</th>
<td>20</td>

</tr>

<tr>

</tr>
<tr>
<th>Date of Discharge:</th>
<td>10-03-2022</td>
<th>Time of Discharge:</th>
<td>12:58:19 PM</td>
<th>Type of Discharge</th>
<th></th>
</tr>
</tbody></table>
 </div>
</div>
</div><hr>


<div class="container">
<div class="row mt-3">
 <div class="col-md-12 " >
 <h4 class="mt-5 text-danger mb-3">STEP 1 : Previous Medical History Details </h4>
 <h6>Vedana Samuchaya (With duration Chronological order)</h6>
 <p>1. c/opain in low back region since 2years
</p>
 <p>2. increased one month along with stiffness</p>
 <p>3.</p>

 <p>4.</p>


 </div>
</div>
</div>

<div class="container">
<div class="row">
 <div class="col-md-12 " style="height:150px">
 <h6>Atanka Samutpattikrama (History of Present Illness) :</h6>
 </div>
</div>
</div>

<div class="container">
<div class="row">
 <div class="col-md-12 " style="height:150px">
 <h6>Present compalint treatment history :</h6>
 </div>
</div>


<div class="row ">
 <div class="col-md-12">
 <h6>Past Medical/Surgical History :</h6>
     <table class="table table-bordered mb-3">
     <tbody><tr>
    
    </tr>
    <tr>
      <td><h6>Childhood:<h6>
       
      
      </td>
      <td width="200px"></td>
      <td><h6>Adulthood:</h6>
        
        
      </td>
      <td width="200px"></td>
      <td><h6>Surgery:</h6>

      </td>
      <td width="200px"></td>
      </tr>
      <tr>
      <td><h6>Medication:</h6>

      </td>
      <td width="200px"></td>
      <td><h6>Drug Allergies:</h6>
      </td>
      <td width="200px"></td>
      <td><h6>Family History:</h6>
      </td>
      <td width="200px"></td>
       </tr>
  </tbody>
     </table>
 </div>
</div>




<div class="row mt-3 ">
 <div class="col-md-12">
     <h6>Personal History</h6>
     <table class="table table-bordered mt-3">
     <tbody>
    <tr>
      <td><h6>Marital Status:</h6></td>
      <td>Unmaried</td>

      <td><h6>Vayaktika Vrittana</h6></td>
      <td>Mixed<Mixed/td>

      <td><h6> Vyasana</h6> </td>
      <td>Smoking, Tobaco</td>
      
      </tr>

      <tr>
      <td><h6>Nidhra:</h6> </td>
      <td>Disturbed</td>

      <td><h6>Agni:</h6></td>
      <td>Sama</td>

      <td><h6>Kostash:</h6></td>
      <td>Mruda</td>

       </tr>
  </tbody>
     </table>
 </div>
</div>


<div class="row mt-5 mb-4">
 <div class="col-md-12 ">
 <h6>Occupational History
</h6>
<table class="table table-borderless mt-3">
<tbody>
<tr>
<th width="150px"><h6>Nature of Work :</h6></th>
<td ><p>Labor, Job Stress</p></td>
</tr>
</tbody>
</table>

 </div>
</div>


<div class="row  step">
 <div class="col-md-12">
     <h6>OBG History</h6>
     <table class="table table-bordered mt-3">
     <tbody>
    <tr>
      <td><h6>Menarche at:</h6></td>
      <td width="200px"></td>

      <td><h6>Day Cycle:</h6></td>
      <td width="200px"></td>


      <td><h6> Live:</h6> </td>
      <td width="200px"></td>

      
      </tr>

      <tr>
      <td><h6>Period:</h6> </td>
      <td width="200px"></td>


    

       </tr>
  </tbody>
     </table>
 </div>
</div>


<h4 class="mt-5 text-danger">STEP 2 : INVESTIGATIONS</h4>


<div class="row mt-5 mb-4">
 <div class="col-md-12 ">
 <h6>Occupational History:
</h6>
<table class="table table-borderless mt-3">
<tbody>
<tr>
<th width="250px"><h6>Genaral Examination :</h6></th>
<td ><p></p></td>
</tr>
</tbody>
</table>

 </div>
</div>





<div class="row mb-4">
 <div class="col-md-12 ">
 <h6>Vittal Data
</h6>
<table class="table table-bordered mt-3">
<tbody>
<tr>
<th width="100px" ><h6>Pulse Rate:</h6></th>
<td width="100px" ></td>
<th width="100px" ><h6>Heart Rate:</h6></th>
<td width="100px" ></td>
<th width="150px" ><h6>Respiratory  Rate:</h6></th>
<td width="100px"></td>
<th width="100px" ><h6>Temprature:</h6></th>
<td width="100px" ></td>
<th width="150px" ><h6>Blood Pressure:</h6></th>
<td ></td>
</tr>
</tbody>
</table>

 </div>
</div>


<div class="row mb-4">
 <div class="col-md-12 ">
 <h6>Astasthana Pareeksha 
</h6>
<table class="table table-bordered mt-3">
<tbody>
<tr>
<th width="100px" ><h6>Nadi:</h6></th>
<td width="100px" ></td>
<th width="100px" ><h6>Mutra:</h6></th>
<td width="100px" ></td>
<th width="150px" ><h6>Mala:</h6></th>
<td width="100px"></td>

</tr>
</tbody>
</table>

 </div>
</div>

<div class="row mb-4">
 <div class="col-md-4 ">
 <h6>Jivha
</h6>
<p>Upalepita, Ragayukta</p>

 </div>

 <div class="col-md-4 ">
 <h6>Shabda
</h6>
<p>Clear</p>

 </div>

 <div class="col-md-4 ">
 <h6>Sparsha
</h6>
<p>Clear</p>

 </div>
 



</div>


<div class="row mb-4">
 <div class="col-md-4 ">
 <h6>Varna
</h6>
<p>Ruksha, Snigdha</p>

 </div>

 <div class="col-md-4 ">
 <h6>Drik
</h6>
<p>Pale, Conjested</p>

 </div>

 <div class="col-md-4 ">
 <h6>Akriti
</h6>
<p>Stula</p>

 </div>
</div>


<div class="row mb-4">
 <div class="col-md-6 ">
 <h6>Vittal Data
</h6>
<table class="table table-bordered mt-3">
<thead class="thead-dark">
<tr>
<th>
BHAVA
</th>
<th>TYPE</th>
<th>ASSEMENT(PRAVAR/MADYA/AVAR)</th>
</tr>

<tbody>
<tr>
<th><h6>Prakruti</h6></th>
<td width="150"></td>

<td></td>


</tr>
<tr>
<th><h6>Saara</h6></th>
<td width="150"></td>

<td></td>


</tr>

<tr>
<th><h6>Smhanana</h6></th>
<td width="150"></td>

<td></td>


</tr>

<tr>
<th><h6>Pramana</h6></th>
<td width="150"></td>

<td></td>


</tr>

<tr>
<th><h6>Vayah</h6></th>
<td width="150"></td>

<td></td>
</tr>
</tbody>
</table>

 </div>

 <div class="col-md-6">
 <h6 class="text-white">.
</h6>
 <table class="table table-bordered mt-3">
 <thead class="thead-dark">
<tr>
<th>
BHAVA
</th>
<th>TYPE</th>
<th width="500px">ASSEMENT(PRAVAR/MADYA/AVAR)</th>
</tr>
</thead>

<tr>
<th><h6>Satmya</h6></th>
<td width="150"></td>

<td></td>


</tr>

<tr>
<th><h6>Satwa</h6></th>
<td width="150"></td>

<td></td>


</tr>

<tr>
<th><h6>Ahara Shakti	</h6></th>
<td width="150"></td>

<td></td>


</tr>

<tr>
<th width="250"><h6>Vyayama Shakti</h6></th>
<td width="150"></td>

<td></td>
</tr>
</tbody>
</table>

 </div>
 </div>


 <div class="row">
 <div class="col-md-12">
     <table class="table table-bordered"> 
         <tr>
            <td><h6>Manah</h6></td>
             <td width="250px"></td>
             <td><h6>Buddhi</h6></td>

             <td width="250px"></td>
             <td><h6>Sanjna</h6></td>

             <td width="250px"></td>
                                 

         </tr>
         <tr>
         <td><h6>Jnana</h6></td>
             <td width="250px"></td>
             <td><h6>Bhakti</h6></td>
             <td width="250px"></td>
             <td><h6>Sheela</h6></td>
             <td width="250px"></td>
         </tr>
         <tr>
         <td><h6>Chesta</h6></td>
             <td width="250px"></td>
             <td><h6>Achara</h6></td>
             <td width="250px"></td>
             <td><h6>Achara</h6></td>
             <td width="250px"></td>
         </tr>
     </table>

 </div>
 </div>



 <div class="row mt-3">
     <div class="col-md-12">
         <table class="table table-bordered text-center">
             <thead class="thead-dark">
                 <tr class="">
                     <th width="300px">Storus</th>
                     <th width="300px"> Type of Dusti Dustilakshna</th>
                 </tr>
             </thead>
             <tbody>
                 <tr height="30px">
                     <td></td>
                     <td></td>
                 </tr>
                 <tr height="30px">
                     <td></td>
                     <td></td>
                 </tr>
                 <tr height="30px">
                     <td></td>
                     <td></td>
                 </tr>
             </tbody>
         </table>
     </div>
 </div>
 <div class="row mt-4">
     <div class="col-md-12">
         <table class="table table-bordered">
             <tbody>
                 <tr>
                     <td width="200px"><h6>Local Examination</h6></td>
                     <td width="500px"></td>
                     <td><h6>Roga Type</h6></td>
                     <td>ADHD</td>
                 </tr>
             </tbody>
         </table>
     </div>
 </div>

 <div class="row mt-4 step">
 <div class="col-md-12">
     <h5>Systematic Examination</h5>
     <table class="table table-bordered mt-3"> 
         <tbody>
         <tr>
            <td><h6>CVS:</h6></td>
             <td width="250px"></td>
             <td><h6>RS:</h6></td>

             <td width="250px"></td>
             <td><h6> 	P/A:</h6></td>

             <td width="250px"></td>
             <td><h6>CNS:</h6></td>
             <td width="250px"></td>
                                 

         </tr>
         <tr>
      
             <td><h6>Power:</h6></td>
             <td width="250px"></td>
             <td><h6>Reflexes:</h6></td>
             <td width="250px"></td>
             <td><h6>Tone:  </h6></td>
             <td width="250px"></td>
             <td><h6>others:</h6></td>
             <td width="250px"></td>
         </tr>
         <tr>
        
             <td><h6>GUS</h6></td>
             <td width="250px"></td>
             <td><h6>MSS</h6></td>
             <td width="250px"></td>
             <td><h6>Skin</h6></td>
             <td colspan="3"></td>
         </tr>
         </tbody>
     </table>

 </div>
 </div>

 
<div class="row mt-5 ">
<h4 class="text-uppercase text-danger">Step 3 : Treatment</h4 class="text-uppercase">
<div class="col-md-12 mt-3">
<h5 class="text-uppercase">Lab Tests</h5>
<table class="table table-bordered text-center">
   <thead class="thead-dark">
       <tr>
           <th width="150px">Sl No.</th>
           <th>Test Name</th>
           <th>Test Values</th>
       </tr>
   </thead>
   <tbody>
       <tr height="25px">
           <td></td>
           <td></td>
           <td></td>
       </tr>
   </tbody>
</table>
</div>
</div>

<div class="row ">

<div class="col-md-12 mt-3">
<h5>ROGA PAREEKSHA</h5>
<table class="table table-bordered">
   
   <tbody>
       <tr >
           <td><h6>Nindana:<h6></td>
           <td width="150px"></td>
           <td><h6>Poorvapoorva:<h6></td>
            <td width="150px"></td>
           <td><h6>Roopa:<h6></td>
            <td width="150px"></td>
           <td>
<h6>Upashaya/Anupashaya:<h6></td>
           <td width="150px"></td>
       </tr>
   </tbody>
</table>
</div>
</div>

<div class="row mt-3 ">

<div class="col-md-12 ">
<h5 class="text-uppercase">Sampraptighatakas</h5>
<table class="table table-bordered text-wrap mb-5">
   
   <tbody>
       <tr >
           <td><h6>Dosha:</h6></td>
           <td width="150px"></td>
           <td><h6>Dushy:</h6></td>
            <td width="150px"></td>
           <td><h6>Agni:</h6></td>
            <td width="150px"></td>
           <td>
           <h6>Ama/Nirama:</h6></td>
           <td width="150px"></td>
           <td>
           <h6>Srotas:</h6></td>
           <td width="150px"></td>
       </tr>
       </tr>
       <tr >
           <td><h6>Srotodushtiprakarana:</h6></td>
           <td width="150px"></td>
           <td><h6>Srotodushtiprakarana:<</h6></td>
            <td width="150px"></td>
           <td><h6>VyadhiUdbhavasthana:</h6></td>
            <td width="150px"></td>
           <td>
           <h6>Sancharasthana:</h6></td>
           <td width="150px"></td>
           <td>
           <h6>Vyaktasthana:</h6></td>
           <td width="150px"></td>
       </tr>
       <tr >
           <td><h6>Vyadhiadhisthana:</h6></td>
           <td width="150px"> </td>
           <td><h6>Rogamarga:</h6></td>
            <td width="150px"></td>
           <td><h6>Rogabala:</h6></td>
            <td width="150px"></td>
           <td>
           <h6>Rogibala:</h6></td>
           <td width="150px"></td>
           <td>
           <h6>Rogaswabhava:</h6></td>
           <td width="150px"></td>
       </tr>
       <tr >
           <td><h6>Upadrava:</h6></td>
           <td width="150px"></td>
           <td><h6>Arista lakshna:</h6></td>
            <td width="150px"></td>
           <td><h6>Samprapti:</h6></td>
            <td width="150px"></td>
           <td>
           <h6>Sapeksha/Nidana:</h6></td>
           <td width="150px" ></td>
           <td>
           <h6>Vyavachedaka/Nidana:</h6></td>
           <td width="150px" ></td>
           
       
          
       </tr>
       <tr>
       <td>
          <h6> Vyadhi / Vinishaya : </h6></td>
           <td width="150px" colspan="2" ></td>
       </tr>
   </tbody>
</table>
</div>
</div>


<div class="row mt-5 ">
<h4 class="text-uppercase text-danger">Step 4 : Medication </h4 class="text-uppercase">
<div class="col-md-12 mt-3">
<h5 class="text-uppercase">PROCEDURES/THERAPIES</h5>
<table class="table table-bordered text-center">
<thead class="thead-dark">
                                                            <tr>
                                                                <th class="text-center" width="50px">Sl.No.</th>
                                                                <th>Date</th>
                                                                <th>Procedure</th>
                                                                <th>Sub Procedure</th>
                                                                <th class="text-center">Procedure Medicine</th>
                                                                <th>Dose (days/ml grms)</th>
                                                                <th>Duration</th>
                                                                <th>Observation</th>
                                                              
                                                                
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr height="20px">
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


<div class="row mt-3 ">

<div class="col-md-12 ">
<h5 class="text-uppercase">ORAL MEDICINES</h5>
<table class="table table-bordered text-center">
<thead class="thead-dark">
<tr>
                                                                <th class="text-center" width="50px">Sl.No.</th>
                                                                <th>Date</th>
                                                                <th>Medicine Name</th>
                                                                
                                                                <th>Dose (days/ml grms)</th>
                                                                <th>Duration</th>
                                                              
                                                                <th>Anupana</th>
                                                             
                                                                
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr height="20px">
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


<div class="row mt-3 ">

<div class="col-md-12 ">
<h5 class="text-uppercase">IP DAILY ENTRY</h5>
<table class="table table-bordered text-center">
<thead class="thead-dark">
<tr>
                                                                <th class="text-center" width="50px">Sl.No.</th>
                                                                <th>Date</th>
                                                                <th>Pulse</th>
                                                                
                                                                <th>BP</th>
                                                                <th>Temp</th>
                                                              
                                                                <th>Nadi</th>
                                                                <th>Diet</th>
                                                                <th>Medicines</th>
                                                                <th>Progress</th>
                                                              
                                                                
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr height="20px">
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


<div class="row mt-3 ">

<div class="col-md-12 ">
<h5 class="text-uppercase">OPERATION THEATER</h5>
<table class="table table-bordered text-center">
<thead class="thead-dark">
<tr>
                                                                <th class="text-center" width="50px">Sl.No.</th>
                                                                <th>Date</th>
                                                                <th>Ot Type</th>
                                                                <th>Procedure</th>
                                                                <th>
                                                                Comments
                                                                </th>
                                                                
                                                              
                                                             
                                                                
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr height="20px">
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
        </div>
          
        </div>
        <!--**********************************
            Content body end
        ***********************************-->


        <!--**********************************
            Footer start
        ***********************************-->

   <?php include('/footer.php') ?> 
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
    <!--  vendors -->
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
<script src="../css/sweetalert2/sweetalert2.min.js"></script>

<!-- Sweet alert init js-->
<script src="../js/sweet-alerts.init.js"></script>
 

<script>
 $(document).ready(function() {
    var table = $('#example').DataTable( {
        lengthChange: false,
        buttons: [ 'copy', 'excel', 'pdf', 'colvis' ]
    } );
 
    table.buttons().container()
        .appendTo( '#example_wrapper .col-md-6:eq(0)' );
} );
</script>

</body>

</html>