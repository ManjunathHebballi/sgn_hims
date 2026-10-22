<?php
require_once(__DIR__ . '/model/session_config.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Pathology  Billing</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">



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

            <div class="position-fixed bottom-0 right-0 p-3" style="z-index: 5; right: 0; bottom: 0;">
  <div id="liveToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true" data-delay="4000">
    <div class="toast-header bg-success text-light">
    <i class="ri-checkbox-circle-line mr-3"></i>
      <strong class="mr-auto">Bill Collected Successfully</strong> <br>
    
      <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="toast-body">
<p class="text-black">     Total Rs. 125 is Collected. </p>
    </div>
  </div>
</div>


<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <h5 class="text-uppercase"> Pathology  Billing</h5>
        </div>
    </div>

    <div class="row">
       <div class="col-md-12">
       <div class="card">
            <div class="card-body">
                
                <div class="row">
                    <div class="col-md-7 border ">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Bill No</th>
                                            <td>RADIO001</td>
                                        </tr>
                                        <tr>
                                            <th>
                                                OPD ID
                                            </th>
                                            <td></td>
                                        </tr>
                                        <!--if IP Patient IPD No. have to add-->
                                        <tr>
                                            <th>
                                                IPD ID
                                            </th>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <th>
                                                Patient Name
                                            </th>
                                            <td>Vekatesh</td>
                                            </tr>
                                         <tr>
                                            <th>
                                                Doctor Name
                                            </th>
                                            <td>Dr. Manju</td>
                                            </tr>
                                            <tr>
                                            <th>
                                                Department
                                            </th>
                                            <td>General Medicine</td>
                                            </tr>
                                             <tr>
                                            <th>
                                                Gender / Age
                                            </th>
                                            <td>Male / 24 year 2 months 13 days </td>
                                        
                                        </tr>
                                        <tr>
                                            <th>
                                               Blood Group
                                            </th>
                                            <td>B+</td>
                                            </tr>
                                            <tr>
                                            <th>
                                               Mobile
                                            </th>
                                            <td>8904239164</td>
                                            </tr>
                                            <tr>
                                            <th>
                                                Report Generated By
                                            </th>
                                            <td>Dr. Abhishek</td>
                                            </tr>
                                        
                                    </thead>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Test</th>
                                            <th> Tax (Rs.)</th>
                                            <th class="text-right">Amount (Rs.)</th>
                                        </tr>
                                        
                                    
                                       <tr>
                                            <td>abc</td>
                                        <td>20.00</td>
                                        <td class="text-right">100.00</td>
                                       </tr>
                                       <tr>
                                           <th colspan="2" class="text-right">Total (Rs.)</th>
                                           <th class="text-right">100.00</th>
                                       </tr>
                                       <tr>
                                           <th colspan="2" class="text-right">Total Tax (Rs.)</th>
                                           <th class="text-right">20.00</th>
                                       </tr>
                                       <tr>
                                           <th colspan="2" class="text-right">Net Amount (Rs.)</th>
                                           <th class="text-right">120.00</th>
                                       </tr>
                                       <tr>
                                           <th colspan="2" class="text-right">Paid Amount (Rs.)</th>
                                           <th class="text-right">100.00</th>
                                       </tr>
                                       <tr>
                                           <th colspan="2" class="text-right">Due Amount (Rs.)</th>
                                           <th class="text-right">20.00</th>
                                       </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <form>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Date</label>
                                    <input type="date" class="form-control" name="pay_date" id="pay_date"/>
                                </div>
                            </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                    <label>Discount(Rs.)</label>
                                    <input type="number" min="0" class="form-control" name="pay_amount" id="pay_amount"/>
                                </div>
                            </div>
                             <div class="col-md-4">
                                <div class="form-group">
                                    <label>Amount (Rs.)</label>
                                    <input type="number" min="0" class="form-control" name="pay_amount" id="pay_amount"/>
                                </div>
                            </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                    <label>Payment Mode</label>
                                   <select class="form-control" name="mode" id="mode">
                                       <option value="Cash">Cash</option>
                                       <option value="Online">Online</option>
                                   </select>
                                </div>
                            </div>
                              <div class="col-md-8">
                                <div class="form-group">
                                    <label>Note</label>
                                    <textarea class='form-control' name="note" id="note" rows="1" cols="2"></textarea>
                            </div>
                            
                        </div>
                         <div class="col-md-12">
                                <button type="sumbit" name="submit" value="pay" class="btn btn-success float-right">Save</button>
                            
                        </div>
                    </div>
                    </form>
                
                </div>
                
            </div>
        </div>
       </div>
    </div>  </div>
    

    <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Transaction History  </h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="tab_logic" class="table table-bordered  nowrap table-hover">
                                        <thead class="thead-dark ">
                                            <tr>
                                              
                                              
                                             
                                               
                                                <th>Invoice Id</th>
                                                <th>Day</th>
                                                <th>Mode </th>
                                                <th>Note</th>
                                                <th class="text-right">Amount (Rs.)</th>
                                                
                                                
                                               <th>Action</th>
                                              
                                                
                                            </tr>
                                        </thead>
                                        <tbody class=" text-black">
                                       
            <tr >
        
                <td width="30%">
                   
                   TRID001
                </td>
                
                <td>
                    23.03.2023
                </td>
                <td>
                   Online
                </td>
                <td>
                   Paid
                </td>
                <td class="text-right">
                  100.00
                </td>
              
                <td class=" text-center">
                
               <a class="badge badge-danger text-light"  type="button" id='print' name='print' data-toggle="tooltip" title="Print"  > <i class="ri-printer-line"></i></a>
                </td>
            </tr>
            
         
            </tbody>
        

                                            </table>   
                                            </div> 
                                          
                                            </div>
                                            </div></div></div>
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
    

  $("#myBtn").click(function(){
    $('.toast').toast('show');
  });

</script>
</body>

</html>