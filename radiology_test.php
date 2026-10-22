<?php
require_once(__DIR__ . '/model/session_config.php');
include('model/radiologyClass.php');
$newRadio = new addRadio;


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Radiology Test</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" ></script>
   
    
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

    <div aria-live="polite" id="notification" aria-atomic="true" >
<?php if(isset($_REQUEST['success']))
{ ?>
    
<div class="alert alert-success solid alert-dismissible fade show">
                                    <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span>
                                    </button>
                                    <strong> Data is addedd successfully!</strong> 
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
        <div class="col-md-6">
            <h4 class="text-uppercase">Radiology Test</h4>
        </div>
        <div class="col-md-6">
            <a href="view_radiology_test.php" class="btn btn-sm btn-success float-right " > View Radiology Test</a>
        </div>
    </div>
  
  <div class="card">
    <div class="card-body">
    <div class="row">
                  
        <div class="col-md-12 mb-3">
            <h6 class="text-uppercase">Select Criteria</h6>
    
    </div>
    </div>
        <form method="POST" action="controller/processRadiology.php">
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <label>Test Name</label>
                    <small class="text-danger"> *</small> 
                    <input type="text" name="test_name" class="form-control" >
                    <span class="text-danger"></span>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label>Short Name</label>
                    <small class="text-danger"> *</small> 
                    <input type="text" name="short_name" class="form-control">
                    <span class="text-danger"></span>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label>Test Type</label>                    
                    <input type="text" name="test_type" class="form-control" >
                    <span class="text-danger"></span>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="exampleInputFile">
                        Category Name</label>
                    <small class="text-danger"> *</small> 
                    <div>
                        <select class="form-control " style="width: 100%" name="radiology_category_id" >
                            <option value="">Select</option>
                            <?php $res=$newRadio->getRadioCat();
                            while($row=mysqli_fetch_assoc($res))
                            { ?>
                                <option value="<?php echo $row['id']; ?>"><?php echo $row['category']; ?></option>
                           <?php } ?>
                        </select>
                    </div>
                    <span class="text-danger"></span>
                </div>
            </div>                           
            <div class="col-sm-3">
                <div class="form-group">
                    <label>Sub Category</label>
                    <input type="text" name="sub_category" class="form-control" >
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="Method">Method</label>
                    <input name="method" type="text" class="form-control" >
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label>Report Days</label>
                    <input type="number" min="0" value="0" name="report_days" class="form-control">
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="exampleInputFile">Charge Category</label>
                    <small class="text-danger">*</small> 
                    <div>
                        <select class="form-control " name="charge_category_id" >
                            <option value="">Select</option>
                                                            
                                                    </select>
                    </div>
                    <span class="text-danger"></span>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="exampleInputFile">Charge Name</label>
                    <small class="text-danger">*</small> 
                    <div>
                        <select class="form-control select2 charge select2-hidden-accessible" style="width: 100%" name="charge_name" id="charge_name" >
                            <option value="">Select</option>
                        </select>          </div>
                    <span class="text-danger"></span>
                </div>
            </div>    
            <div class="col-sm-3">

            
                <div class="form-group"> 
                     <label for="exampleInputFile">Tax (%)</label>
                        <div class="input-group">
                        <input type="text" class="form-control right-border-none" readonly="" name="tax" id="tax" >
                       
                        <div class="input-group-append"> <span class="input-group-text "> %</span> </div>
                        </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="exampleInputFile">Standard Charge</label> (Rs.)                    <small class="text-danger">*</small> 
                    <div>
                        <input readonly="" class="form-control" name="standard_charge" id="standard_charge" >
                    </div>
                    <span class="text-danger"></span>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="exampleInputFile">Amount( Rs.)</label>
                    <small class="text-danger">*</small> 
                    <div>
                        <input readonly="" class="form-control" name="amount" id="amount"> 
                    </div>
                    <span class="text-danger"></span>
                </div>
            </div>  
           
        </div>
        

        <table class="table table-bordered text-nowrap" width="100%"   id="tab_logic">
     <thead class="thead-dark">
            <tr class="text-center"> 
                <th>Test Parameter Name <span class="text-danger">*</span> </th>
                <th>Range <span class="text-danger">*</span></th>
                <th>Unit <span class="text-danger">*</span></th>
               

              
                <th>Action</th>
            </tr>

            
        </thead>
            <tbody>
            <tr id='addr0'>
            <td>
            <select onchange="setRange(this.value,0)" class="form-control  " id="parameter0" name="parameter0" required>
                <option value="">Select Test</option>
             <?php $res=$newRadio->getRadioPara();
                  while($row=mysqli_fetch_assoc($res))
                  { ?>
                     <option value="<?php echo $row['id']; ?>"><?php echo $row['parameter']; ?></option> 
               <?php   } ?>


    </select></td>
                <td>
                    <input type="text" name="range0" id="range0"class="form-control" placeholder="Enter Range" required>
                </td>
                
                <td>
                    <input type="text" name="unit0" id="unit0"class="form-control" placeholder="Enter Unit" required>
                </td>
               
                
                <td class=" text-center">
                
               <a class="btn btn-danger m-1 text-light"  type="button" id='delete_row' name='dlt"+i+"' onclick='dlt(0)' >  <i class="icofont-ui-delete "  data-toggle="tooltip" data-placement="top" title="Delete"></i></a>
                </td>
            </tr>
            
            <tr id='addr1'></tr>
            <input type="hidden" name="id" id="id" value=1>
            </tbody>
            <!-- </table>
            <table class="table table-responsive table-bordered"   id="tab_logic"> -->
            <tfoot> 
           
            
            
          
            <tr>
              
                <td colspan="5"><a id="add_row" type="button" data-toggle="tooltip" title="Add New Row" class="btn btn-success text-light float-right m-1 btn-sm"><i class="icofont-plus" ></i>Add </a></td>
            </tr>
           
        </tfoot>
            </table>
            <br>
            <div class="row">
            <div class="col-md-12">
                <button class="btn btn-success float-right" type="submit" name="submit" value="add_test" >Save</button>
            </div>
        </div>
        </form>
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

    

    <!-- Required datatable js -->
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
        

        <!-- Datatable init js -->
        <script src="assets/js/pages/datatables.init.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>


        <script>
        $(document).ready(function(){
   var i=1;
   
  $("#add_row").click(function(){
  $("#addr"+i).html(" <td><select class='form-control '  onchange='setRange(this.value,"+i+")' id='parameter"+i+"'name='parameter"+i+"'required><option>Select Test </option><?php $res=$newRadio->getRadioPara(); while($row=mysqli_fetch_assoc($res))  { ?> <option value='<?php echo $row['id']; ?>'><?php echo $row['parameter']; ?></option>  <?php   } ?></select></td><td><input type='text' name='range"+i+"' class='form-control '   id='range"+i+"'  placeholder='Enter Range' > </td><td><input type='text' name='unit"+i+"' class='form-control '   id='unit"+i+"'  placeholder='Enter Unit' > </td><td class='text-center'><a id='delete_row' name='dlt"+i+"' onclick='dlt("+i+")' class='text-center text-light btn btn-danger' type='button'  data-toggle='tooltip' title='Delete'> <i class='icofont-ui-delete ' ></i></a></td>");
   
   $('#tab_logic').append('<tr id="addr'+(i+1)+'"></tr>');


   i++; 
   document.getElementById('id').value=i;
});
  $("#delete_row").click(function(){
      if(i>1){
      $("#addr"+(i-1)).html('');
      i--;
      document.getElementById('id').value=i;
      }
  });

});
function dlt(id){
      
      $("#addr"+(id)).html('');
     
  };
 </script>

<script>
    function setRange(id,i)
    {
        $.ajax({
      url: 'ajax.php',
        type: 'POST', // Send post data
        data: 'id='+id+'&page=range',
        async: false,
        success: function(s){
        //   alert(s);
          json_events = s.split("|");
        //   alert(json_events);
          $("#range"+i).val(json_events[2]);
          $("#unit"+i).val(json_events[4]);
        }
         });
    }
</script>
  

</body>

</html>