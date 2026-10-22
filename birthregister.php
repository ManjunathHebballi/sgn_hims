<?php
require_once(__DIR__ . '/model/session_config.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Birth Certificate Form</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
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
                                    <strong>Success!</strong>Patient Revisit added
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
            <h4 class="text-uppercase">Birth Certificate Form</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
<form action="controller/processIPRegistration.php" method="POST">

    <div class="row">
        <div class="col-md-3">
           <div class="form-group">
                  <label> IP Register No <span style="color:red">*</span>  </label>
                  <input type="text" class="form-control " name="ipno" id="ipno" onkeyup="javascript:patientDetail(this.value)" required placeholder="Enter OP Register No">
                  
            </div>
        </div>
       
         <div class="col-md-3">
           <div class="form-group">
                  <label>Date Of Delivery <span style="color:red">*</span>    </label>
                  <input type="date" class="form-control " name="dod" id="dod" required  placeholder="">
                  
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label>  Baby Gender </label>

                <select class="form-control " name="sex" id="sex" >
                    <option value="">Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                
                </select>
            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                   <label >Patient Name <span style="color:red">*</span> </label>
                   <input type="text" class="form-control " name="name" id="name" placeholder="Enter Patient Name" required >
             </div>
         </div>
         
         <div class="col-md-3">
            <div class="form-group">
                   <label >Husband Name <span style="color:red">*</span> </label>
                   <input type="text" class="form-control " name="hname" id="hname" placeholder="Enter Patient Name" required >
             </div>
         </div>
        

         
         <div class="col-md-3">
            <div class="form-group">
                   <label >  Address  </label>
                   <textarea class="form-control " id="address" name="address" cols="1" rows="1" placeholder="Enter Patient Address" ></textarea>
             </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                <label>  Religion  </label>

                <select class="form-control " name="religion" id="religion">
                    <option value="">Select Religion</option>
                    <option value="Hindu">Hindu</option>
                    <option value="Muslim">Muslim</option>
                    <option value="Christian">Christian</option>
                    <option value="Sikh">Sikh</option>
                    <option value="Parse">Parse</option>
                    <option value="Other">Other</option>

                </select>
            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                <label>  Caste  </label>

                <select class="form-control" name="caste" id="caste">
                    <option value="">Select Caste</option>
                    <option value="sc">SC</option>
                    <option value="st">ST</option>
                    <option value="obc">OBC</option>
                    <option value="general">General</option>
                </select>
            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                <label>  Category  </label>

                <input type="text" class="form-control " name="category" id="category"  placeholder="Enter Category">

            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                <label>  Baby Weight  </label>

                <input type="text" class="form-control " name="baby_weight" id="baby_weight"   placeholder="eg 5kg">

            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                <label> Time Of Birth <span style="color:red">*</span> </label>

                <input type="time" class="form-control " name="tob" id="tob"  required  placeholder="eg 5kg">

            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                <label> Husband Education  </label>

                <input type="text" class="form-control " name="heducation" id="heducation"   placeholder="Enter Husband Education">

            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                <label> Wife Education  </label>

                <input type="text" class="form-control " name="weducation" id="weducation"   placeholder="Enter Wife Education">

            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                <label> Husband Occupation  </label>

                <input type="text" class="form-control " name="hoccupation" id="hoccupation"   placeholder="Enter Husband Occupation">

            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                <label> Wife Occupation  </label>

                <input type="text" class="form-control " name="woccupation" id="woccupation"   placeholder="Enter Wife Occupation">

            </div>
         </div>
       
         <div class="col-md-3">
            <div class="form-group">
                <label>  Doctor Name <span style="color:red">*</span>  </label>

                <select class="form-control " name="drname" id="drname" required >
                    <option value="">Select Doctors</option>
                    
                </select>
            </div>
         </div>
       
         

         <div class="col-md-3">
            <div class="form-group">
                <label>  At marriage Time </label>

                <input type="text" class="form-control " name="mtime" id="mtime" placeholder="Enter marriage Time age">

            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                <label>	Present Age  </label>

                <input type="text" class="form-control " name="age" id="age" placeholder="Enter Present age">

            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                <label>	No Of Children </label>

                <input type="number" class="form-control " name="childrens" id="childrens" placeholder="Enter No Of Children">

            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                <label>	Type Of Delivery </label>

                <input type="text" class="form-control " name="delivery_type" id="delivery_type" placeholder="Enter Type Of Delivery">
            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
                <label>	IUD/STILL BORN </label>

                <input type="text" class="form-control " name="iud" id="iud">
            </div>
         </div>


         <div class="col-md-3">
            <div class="form-group">
                <label >  Birth Certificate  </label>

                <select class="form-control  " name="bc" id="bc" >
                <option value="">Select </option>

                <option value="Writen">Writen</option>
                    <option value="Sent">Sent</option>
                    
                 
                </select>
            </div>
         </div>
         

         <div class="col-md-3">
            <div class="form-group">
                <label>  TUBETO MISDET  </label>

                <select class="form-control " name="tubeto" id="tubeto" >
                    <option value="">Select TUBETO MISDET</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                   

                 
                </select>
            </div>
         </div>
         

                
      
         <div class="col-md-3">
            <div class="form-group">
                   <label>  Diagnosis   </label>
                   <textarea class="form-control " id="diagnosis" name="diagnosis" cols="1" rows="1" placeholder="Enter Patient Diagnosis" ></textarea>
             </div>
         </div>

         
</div>
<div class="row">
         <div class="col-md-12">
          

            <button type="submit" class="btn btn-primary btn-lg float-right" name="submit">Save</button>
            
         </div>
  
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
    function patientDetail(op)
    {
        // alert(op)
     $.ajax({
      url: 'ajax.php',
        type: 'POST', // Send post data
        data: 'op_no='+op+'&page=ipreg',
        async: false,
        success: function(s){
            // alert(s);
            json_events = s.split("|");
            $("#name").val(json_events[3]);
            $("#age").val(json_events[4]);
            $("#sex option[value="+json_events[5]+"]").attr("selected", "selected");
            // $("#sex option:not(:selected)").attr("disabled", "disabled");
            $("#address").val(json_events[7]);
            $("#religion option[value="+json_events[13]+"]").attr("selected", "selected");
            // $("#religion option:not(:selected)").attr("disabled", "disabled");
            $("#caste option[value="+json_events[14]+"]").attr("selected", "selected");
            // $("#caste option:not(:selected)").attr("disabled", "disabled");
            $("#category").val(json_events[15]);
            $("#dept option[value="+json_events[9]+"]").attr("selected", "selected");
            $("#annual_inc").val(json_events[11]);
            $("#occupation").val(json_events[19]);
        $("#mobile").val(json_events[6]);
            $("#fname").val(json_events[16]);
          }
        });
  }
  window.setTimeout(function() {
    $(".alert").fadeTo(500, 0).slideUp(500, function(){
        $(this).remove(); 
    });
}, 4000);
</script>
  

</body>

</html>