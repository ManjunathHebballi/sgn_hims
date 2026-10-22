<?php 
   require_once(__DIR__ . '/model/session_config.php');
   date_default_timezone_set("Asia/Kolkata");  
   include('model/opClass.php');
   $newOP = new addOP;
   
   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width,initial-scale=1">
      <title>Blood Bank</title>
      <!-- Favicon icon -->
      <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
      <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
      <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
      <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
      <link href="./css/style.css" rel="stylesheet">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" ></script>
      <!-- Datatable -->
      <link href="./vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
      <style>
         #notification {
         position: absolute;
         top: 90px;
         right:2px;
         z-index: 99;
         }
         .table th, .table td{
         padding:15px!important;
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
      <!-- Modal -->
      <div class="modal fade" id="addnew" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Add New Donar</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-body">
                  <div class="row mt-1">
                     <div class="col-xl-12">
                        <div class="card">
                           <div class="card-body">
                              <div class="card-title">
                                 <h4 class="card-title">Donor Details</h4>
                              </div>
                              <hr>
                              <form method="POST" action="controller/processBloodBank.php">
                              <div class="row">
                                 <div class="col-md-4">
                                    <div class="form-group"><label for="validationCustom02">Name<span style="color: red;">*</span></label><input name="d_name" id="d_name" type="text" class="form-control" value="" wtx-context="5DDF71C0-2A38-45F6-84D9-77BE886FD5E0"></div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label for="validationCustom01">Gender <span style="color: red;">*</span></label>
                                       <select class="form-control" required name="gender" id="gender" wtx-context="2A084614-D4FB-44EC-AB37-0AF9E4C3981D">
                                          <option value="">Select</option>
                                          <option value="Male">Male</option>
                                          <option value="Female">Female</option>
                                       </select>
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group"><label for="validationCustom01"> Father's Name<span style="color: red;">*</span></label><input type="text" class="form-control" required="" value="" name="f_name" id="f_name" wtx-context="A2B8CB37-5BF2-49F2-9759-D3BA1BE01295"></div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group"><label for="validationCustom01"> Mother's Name<span style="color: red;">*</span></label><input type="text" class="form-control" required="" name="m_name" id="m_name" value="" wtx-context="B66CA6B2-042A-4AAC-B6D2-377C742A1BF0"></div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group"><label for="validationCustom01">DOB<span style="color: red;">*</span></label><input type="date" class="form-control" required="" value=""name="dob" id="dob"  wtx-context="747ADFB7-B2FA-4F65-973B-31513E4282E9"></div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group"><label for="validationCustom01">Contact<span style="color: red;">*</span></label><input type="text" class="form-control" id="contact" name="contact" value="" wtx-context="2D85796D-0E0A-46F1-8E15-4115FFF09416"></div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group"><label for="validationCustom01">Address</label><input type="text" class="form-control" name="address" id="address" value="" wtx-context="5F16C5B5-0370-421F-B599-9E6C6B1EFEAE"></div>
                                 </div>
                              </div>
                              </form>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="submit" name="submit" value="" class="btn btn-primary">Save changes</button>
               </div>
            </div>
         </div>
      </div>
      <!-- Modal -->
      <div class="modal fade" id="view" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">View Donar</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-body">
                  <div class="row">
                     <div class="col-md-12">
                        <div class="card">
                           <div class="card-body">
                              <form method="POST" wtx-context="09882A90-F543-428E-823E-81972C577AC7">
                                 <div class="row">
                                    <div class="col-md-4">
                                       <div class="form-group"><label for="exampleInputEmail1"> Search Key</label><input type="text" class="form-control" required="" value="" wtx-context="2621121B-63AC-41F2-86B6-F1489A1ECA3C"></div>
                                    </div>
                                 </div>
                                 <div class="row">
                                    <div class="col-md-12"><span><i class="bi bi-search"></i></span> Search</button></div>
                                 </div>
                              </form>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-12">
                        <div class="card">
                           <div class="card-body">
                              <div class="table-responsive">
                                 <table class="table table-bordered">
                                    <thead class="text-center">
                                       <tr>
                                          <th>Sl NO</th>
                                          <th>ID</th>
                                          <th> Name</th>
                                          <th> Contact</th>
                                          <th> Address</th>
                                          <th> Age</th>
                                       </tr>
                                    </thead>
                                    <tbody class="text-center"></tbody>
                                    <tr>
                                       <td>1</td>
                                       <td>DONOR-2022-9-20-46806</td>
                                       <td>Srinivas </td>
                                       <td>9986474446</td>
                                       <td>Dharwad</td>
                                       <td>35</td>
                                    </tr>
                                    <tr>
                                       <td>2</td>
                                       <td>DONOR-2022-9-20-75627</td>
                                       <td>Guru</td>
                                       <td>5678998763</td>
                                       <td>Hubli</td>
                                       <td>23</td>
                                    </tr>
                                    <tr>
                                       <td>3</td>
                                       <td>DONOR-2022-10-12-93805</td>
                                       <td>Guru</td>
                                       <td>9591392656</td>
                                       <td>Hubli</td>
                                       <td>23</td>
                                    </tr>
                                    <tr>
                                       <td>4</td>
                                       <td>DONOR-2022-10-12-1031</td>
                                       <td>Guru</td>
                                       <td>6361291001</td>
                                       <td>Hubli, VD Nagar</td>
                                       <td>23</td>
                                    </tr>
                                 </table>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary">Save changes</button>
               </div>
            </div>
         </div>
      </div>
      <!-- Modal -->
      <div class="modal fade" id="donation" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Add New Donation</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-body">
                  <div class="row">
                     <div class="col-xl-12">
                        <div class="card">
                           <div class="card-body">
                              <div class="card-title"></div>
                              <div class="row mt-1">
                                 <div class="col-xl-12">
                                    <div class="card">
                                       <div class="card-body">
                                          <div class="card-title">
                                             <h4 class="card-title">Donor Details</h4>
                                          </div>
                                          <hr>
                                          <div class="row">
                                             <div class="col-md-4">
                                                <div class="form-group">
                                                   <label for="validationCustom02">Search Donor<span style="color: red;">*</span></label>
                                                   <div class="select-search" id="doctor">
                                                      <div class="select-search__value"><input tabindex="0" placeholder="Search Donor" autocomplete="on" class="select-search__input" value="" wtx-context="AF3F6256-C187-4023-8DAA-F9707BE12520"></div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-4">
                                                <div class="form-group"><label for="validationCustom01"> Blood Group<span style="color: red;">*</span></label><input type="text" readonly="" class="form-control" required="" value="A+" wtx-context="68614199-AED2-4A1D-832F-A484B6C8BF04"></div>
                                             </div>
                                             <div class="col-md-4">
                                                <div class="form-group"><label for="validationCustom01"> Donor Name<span style="color: red;">*</span></label><input type="text" readonly="" class="form-control" required="" value="" wtx-context="2F0DC942-7FD7-462B-9924-2CA86BDC3FD1"></div>
                                             </div>
                                             <div class="col-md-4">
                                                <div class="form-group"><label for="validationCustom01"> Donor ID<span style="color: red;">*</span></label><input type="text" readonly="" class="form-control" required="" value="" wtx-context="4DAC22CC-1CA8-4813-873D-ECFCF8C8D186"></div>
                                             </div>
                                             <div class="col-md-4">
                                                <div class="form-group"><label for="validationCustom01">Bags<span style="color: red;">*</span></label><input type="number" class="form-control" required="" value="0" wtx-context="215BCC14-04F1-4F3B-8480-2289D043D1E2"></div>
                                             </div>
                                             <div class="col-md-4">
                                                <div class="form-group">
                                                   <label for="validationCustom01">Unit<span style="color: red;">*</span></label>
                                                   <select type="text" class="form-control" id="sContact" name="sContact" wtx-context="FCE4067E-6913-4156-8255-446777176AFA">
                                                      <option value="ml">ml</option>
                                                      <option value="l">l</option>
                                                   </select>
                                                </div>
                                             </div>
                                             <div class="col-md-4">
                                                <div class="form-group"><label for="validationCustom01">Quantity Per Bag</label><input type="number" class="form-control" value="0" wtx-context="0474D977-8C86-4A9C-A758-209932ECB687"></div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="row pl-5">
                        <div></div>
                     </div>
                  </div>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary">Save changes</button>
               </div>
            </div>
         </div>
      </div>
      <!-- Modal -->
      <div class="modal fade" id="component" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Add New Component</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-body">
                  <div class="row">
                     <div class="col-xl-12">
                        <div class="card">
                           <div class="card-body">
                              <div class="card-title"></div>
                              <div class="row mt-1">
                                 <div class="col-xl-12">
                                    <div class="card">
                                       <div class="card-body">
                                          <div class="card-title">
                                             <h4 class="card-title">Donor Details</h4>
                                          </div>
                                          <hr>
                                          <div class="row">
                                             <div class="col-md-4">
                                                <div class="form-group">
                                                   <label for="validationCustom02">Search Donor<span style="color: red;">*</span></label>
                                                   <div class="select-search" id="doctor">
                                                      <div class="select-search__value"><input tabindex="0" placeholder="Search Donor" autocomplete="on" class="select-search__input" value="" wtx-context="899541A7-D720-4A3D-8B8E-B1B2BAE08E94"></div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-4">
                                                <div class="form-group"><label for="validationCustom01"> Blood Group<span style="color: red;">*</span></label><input type="text" readonly="" class="form-control" required="" value="A+" wtx-context="5F1BF908-5F9E-4423-B72D-A5C8E1B92D4A"></div>
                                             </div>
                                             <div class="col-md-4">
                                                <div class="form-group">
                                                   <label for="validationCustom01"> Component<span style="color: red;">*</span></label>
                                                   <select type="text" class="form-control" required="" wtx-context="04B5F447-D92E-4882-8DF4-0F755A75B905">
                                                      <option value="select">select</option>
                                                      <option value="Platelets">Platelets</option>
                                                      <option value="Plasma">Plasma</option>
                                                      <option value="Cryo">Cryo</option>
                                                      <option value="White Cells &amp; Granulocytes">White Cells &amp; Granulocytes</option>
                                                      <option value="Red Cells">Red Cells</option>
                                                   </select>
                                                </div>
                                             </div>
                                             <div class="col-md-4">
                                                <div class="form-group"><label for="validationCustom01"> Donor Name<span style="color: red;">*</span></label><input type="text" readonly="" class="form-control" required="" value="" wtx-context="940EB45E-C196-41FF-8901-CF6443F0C1B4"></div>
                                             </div>
                                             <div class="col-md-4">
                                                <div class="form-group"><label for="validationCustom01"> Donor ID<span style="color: red;">*</span></label><input type="text" readonly="" class="form-control" required="" value="" wtx-context="DAAC31D1-A089-4DD0-80C8-AA1EB32E1B67"></div>
                                             </div>
                                             <div class="col-md-4">
                                                <div class="form-group"><label for="validationCustom01">Bags<span style="color: red;">*</span></label><input type="number" class="form-control" required="" value="0" wtx-context="592D35A5-830D-4F50-AC32-25C7B86138A8"></div>
                                             </div>
                                             <div class="col-md-4">
                                                <div class="form-group">
                                                   <label for="validationCustom01">Unit<span style="color: red;">*</span></label>
                                                   <select type="text" class="form-control" id="sContact" name="sContact" wtx-context="1BA25981-1C85-4214-954C-0BC11FCA1DF3">
                                                      <option value="ml">ml</option>
                                                      <option value="l">l</option>
                                                   </select>
                                                </div>
                                             </div>
                                             <div class="col-md-4">
                                                <div class="form-group"><label for="validationCustom01">Quantity Per Bag</label><input type="number" class="form-control" value="0" wtx-context="26FF58FF-7416-410D-9B21-B212BF493AC0"></div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="row pl-5">
                        <div></div>
                     </div>
                  </div>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary">Save changes</button>
               </div>
            </div>
         </div>
      </div>
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
                  <h4 class="text-uppercase">Blood bank</h4>
               </div>
            </div>
            <div class="row mt-3">
               <div class="col-xl-12 ">
                  <div class="card">
                     <div class="card-body d-flex justify-content-around"><button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addnew">Add New Donor</button><button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#view">View Donors</button><button class="btn btn-primary btn-sm"data-toggle="modal" data-target="#donation">Add Donation</button><button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#component">Add Components</button></div>
                  </div>
               </div>
            </div>
            <div class="row mt-3">
               <div class="col-1 ml-5">
                  <div class="card">
                     <div class="card-body">
                        <div class="">
                           <table class="table table-bordered">
                              <thead class="text-center">
                                 <tr>
                                    <th class="text-center">Blood Group</th>
                                 </tr>
                              </thead>
                              <tbody class="text-center"></tbody>
                              <tr>
                                 <td class="text-center cursor-pointer bg-light text-dark&quot; bg-dark text-white text-bold" style="cursor: pointer;">A+</td>
                              </tr>
                              <tr>
                                 <td class="text-center cursor-pointer bg-light text-dark&quot; " style="cursor: pointer;">A-</td>
                              </tr>
                              <tr>
                                 <td class="text-center cursor-pointer bg-light text-dark&quot; " style="cursor: pointer;">B+</td>
                              </tr>
                              <tr>
                                 <td class="text-center cursor-pointer bg-light text-dark&quot; " style="cursor: pointer;">B-</td>
                              </tr>
                              <tr>
                                 <td class="text-center cursor-pointer bg-light text-dark&quot; " style="cursor: pointer;">AB+</td>
                              </tr>
                              <tr>
                                 <td class="text-center cursor-pointer bg-light text-dark&quot; " style="cursor: pointer;">AB-</td>
                              </tr>
                              <tr>
                                 <td class="text-center cursor-pointer bg-light text-dark&quot; " style="cursor: pointer;">O+</td>
                              </tr>
                              <tr>
                                 <td class="text-center cursor-pointer bg-light text-dark&quot; " style="cursor: pointer;">O-</td>
                              </tr>
                           </table>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-5 ml-2">
                  <div class="card">
                     <div class="card-title"></div>
                     <div class="card-body">
                        <div class="table-responsive">
                           <table class="table table-bordered">
                              <thead class="text-center">
                                 <tr class="text-center">
                                    <th>Sl NO</th>
                                    <th>Display ID</th>
                                    <th>Bags</th>
                                    <th>Quantity Per Bag</th>
                                    <th>Unit</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                 </tr>
                              </thead>
                              <tbody class="text-center">
                                 <tr>
                                    <td>1</td>
                                    <td>BG-1-2022-9-20-55673</td>
                                    <td>1</td>
                                    <td>200</td>
                                    <td>ml</td>
                                    <td>10/20/2022</td>
                                    <td>
                                       <button>
                                          <svg stroke="currentColor" fill="currentColor" stroke-width="0" version="1.1" viewBox="0 0 16 16" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                             <path d="M14.942 12.57l-4.942-8.235v-3.335h0.5c0.275 0 0.5-0.225 0.5-0.5s-0.225-0.5-0.5-0.5h-5c-0.275 0-0.5 0.225-0.5 0.5s0.225 0.5 0.5 0.5h0.5v3.335l-4.942 8.235c-1.132 1.886-0.258 3.43 1.942 3.43h10c2.2 0 3.074-1.543 1.942-3.43zM3.766 10l3.234-5.39v-3.61h2v3.61l3.234 5.39h-8.468z"></path>
                                          </svg>
                                       </button>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td>2</td>
                                    <td>BG-1-2022-9-20-28312</td>
                                    <td>1</td>
                                    <td>200</td>
                                    <td>ml</td>
                                    <td>10/20/2022</td>
                                    <td>
                                       <button>
                                          <svg stroke="currentColor" fill="currentColor" stroke-width="0" version="1.1" viewBox="0 0 16 16" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                             <path d="M14.942 12.57l-4.942-8.235v-3.335h0.5c0.275 0 0.5-0.225 0.5-0.5s-0.225-0.5-0.5-0.5h-5c-0.275 0-0.5 0.225-0.5 0.5s0.225 0.5 0.5 0.5h0.5v3.335l-4.942 8.235c-1.132 1.886-0.258 3.43 1.942 3.43h10c2.2 0 3.074-1.543 1.942-3.43zM3.766 10l3.234-5.39v-3.61h2v3.61l3.234 5.39h-8.468z"></path>
                                          </svg>
                                       </button>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td>3</td>
                                    <td>BG-1-2022-10-12-83983</td>
                                    <td>1</td>
                                    <td>500</td>
                                    <td>ml</td>
                                    <td>11/12/2022</td>
                                    <td>
                                       <button>
                                          <svg stroke="currentColor" fill="currentColor" stroke-width="0" version="1.1" viewBox="0 0 16 16" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                             <path d="M14.942 12.57l-4.942-8.235v-3.335h0.5c0.275 0 0.5-0.225 0.5-0.5s-0.225-0.5-0.5-0.5h-5c-0.275 0-0.5 0.225-0.5 0.5s0.225 0.5 0.5 0.5h0.5v3.335l-4.942 8.235c-1.132 1.886-0.258 3.43 1.942 3.43h10c2.2 0 3.074-1.543 1.942-3.43zM3.766 10l3.234-5.39v-3.61h2v3.61l3.234 5.39h-8.468z"></path>
                                          </svg>
                                       </button>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td>4</td>
                                    <td>BG-1-2022-10-12-30222</td>
                                    <td>1</td>
                                    <td>1</td>
                                    <td>ml</td>
                                    <td>11/12/2022</td>
                                    <td>
                                       <button>
                                          <svg stroke="currentColor" fill="currentColor" stroke-width="0" version="1.1" viewBox="0 0 16 16" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                             <path d="M14.942 12.57l-4.942-8.235v-3.335h0.5c0.275 0 0.5-0.225 0.5-0.5s-0.225-0.5-0.5-0.5h-5c-0.275 0-0.5 0.225-0.5 0.5s0.225 0.5 0.5 0.5h0.5v3.335l-4.942 8.235c-1.132 1.886-0.258 3.43 1.942 3.43h10c2.2 0 3.074-1.543 1.942-3.43zM3.766 10l3.234-5.39v-3.61h2v3.61l3.234 5.39h-8.468z"></path>
                                          </svg>
                                       </button>
                                    </td>
                                 </tr>
                              </tbody>
                           </table>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-5">
                  <div class="card">
                     <div class="card-title"></div>
                     <div class="card-body">
                        <div class="table-responsive">
                           <table class="table table-bordered">
                              <thead class="text-center">
                                 <tr class="text-center">
                                    <th>Sl NO</th>
                                    <th>Display ID</th>
                                    <th>Component</th>
                                    <th>Bags</th>
                                    <th>Quantity Per Bag</th>
                                    <th>Unit</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                 </tr>
                              </thead>
                              <tbody class="text-center">
                                 <tr>
                                    <td>1</td>
                                    <td>BG-1-2022-9-20-17304</td>
                                    <td>Platelets</td>
                                    <td>2</td>
                                    <td>200</td>
                                    <td>ml</td>
                                    <td>10/20/2022</td>
                                    <td>
                                       <button>
                                          <svg stroke="currentColor" fill="currentColor" stroke-width="0" version="1.1" viewBox="0 0 16 16" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                             <path d="M14.942 12.57l-4.942-8.235v-3.335h0.5c0.275 0 0.5-0.225 0.5-0.5s-0.225-0.5-0.5-0.5h-5c-0.275 0-0.5 0.225-0.5 0.5s0.225 0.5 0.5 0.5h0.5v3.335l-4.942 8.235c-1.132 1.886-0.258 3.43 1.942 3.43h10c2.2 0 3.074-1.543 1.942-3.43zM3.766 10l3.234-5.39v-3.61h2v3.61l3.234 5.39h-8.468z"></path>
                                          </svg>
                                       </button>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td>2</td>
                                    <td>BG-1-2022-10-12-2898</td>
                                    <td>Platelets</td>
                                    <td>1</td>
                                    <td>250</td>
                                    <td>ml</td>
                                    <td>11/12/2022</td>
                                    <td>
                                       <button>
                                          <svg stroke="currentColor" fill="currentColor" stroke-width="0" version="1.1" viewBox="0 0 16 16" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                             <path d="M14.942 12.57l-4.942-8.235v-3.335h0.5c0.275 0 0.5-0.225 0.5-0.5s-0.225-0.5-0.5-0.5h-5c-0.275 0-0.5 0.225-0.5 0.5s0.225 0.5 0.5 0.5h0.5v3.335l-4.942 8.235c-1.132 1.886-0.258 3.43 1.942 3.43h10c2.2 0 3.074-1.543 1.942-3.43zM3.766 10l3.234-5.39v-3.61h2v3.61l3.234 5.39h-8.468z"></path>
                                          </svg>
                                       </button>
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
   </body>
</html>