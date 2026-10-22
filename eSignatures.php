<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");

include('model/labClass.php');
$lab= new lab;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>E Signature</title>
    <!-- Favicon icon -->
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <!-- Sweet Alert-->
    <link href="css/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />




    <!-- Summernote CSS & JS -->
    <!--<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">-->

    <style>
    #notification {
        position: absolute;
        top: 90px;

        right: 2px;
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
                        <h4 class="text-uppercase"> E Signature </h4>
                    </div>
                    <div class="col-md-6">
                        <!--<a href='packageList.php' class='btn btn-sm btn-primary float-right'>Create  New</a>-->
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-12">
                        <div class="card">

                            <div class="card-body">
                                <h5 class="card-title">Select Criteria</h5>
                                <form method="post" action="controller/processLab.php" id='package-form'
                                    enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"> User <span class='text-danger'>*</span>
                                                </label>
                                                <select class="form-control" name="user_id" id="user_id" required
                                                    onchange="fillUserDetails(this)">
                                                    <option value="">Select User</option>
                                                    <?php
                                                    $users = $lab->getLabUsers();
                                                    while($user = mysqli_fetch_assoc($users)) {
                                                        echo "<option value='".$user['id']."' data-name='".$user['full_name']."' data-designation='".$user['designation']."' 
                                                        data-qualification='".$user['qualification']."'>".$user['full_name']."</option>";
                                                    }
                                                    ?>
                                                </select>
                                                <input type="hidden" name="user_name" id="user_name" />
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"> Designation <span
                                                        class='text-danger'>*</span> </label>
                                                <input type="text" class="form-control" name="designation"
                                                    id="designation" placeholder='Enter Designation..' required />
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Qualification</label>
                                                <input type="text" class="form-control" name="qualification" value=""
                                                    placeholder='Enter Qualification...' />
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Position</label>
                                                <select class="form-control" name="position">
                                                    <option value=""></option>
                                                    <option value="left">Left</option>
                                                    <option value="right">Right</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Upload Signature</label>
                                                <input type="file" class="form-control" name="signature" value=""
                                                    accept="image/png, image/jpg, image/jpeg" />
                                            </div>
                                        </div>

                                        <div class="col-md-12 mt-4">
                                            <button type="submit" name="submit" value="eSignature"
                                                class="btn btn-success float-right">Save</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class='card'>
                    <div class='card-body'>
                        <table class='table table-bordered'>
                            <thead>
                                <tr>
                                    <th>Sl No.</th>
                                    <th>Name</th>
                                    <th>Designation</th>
                                    <th>Qualification</th>
                                    <th>Position</th>
                                    <th>Signature</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                    $result=$lab->getESignature();
                    $sn=1;
                    while($row=mysqli_fetch_assoc($result))
                    {
                        echo "<tr>";
                        echo "<td>$sn</td>";
                        echo "<td>".$row['user_name']."</td>";
                        echo "<td>".$row['designation']."</td>";
                        echo "<td>".$row['qualification']."</td>";
                        echo "<td>".$row['position']."</td>";
                        echo "<td><img src='".$row['signature_image']."' width='150' height='100'></td>";
                        echo "<td> 
                            <button type='button' class='btn btn-success btn-sm' onclick='editSignature(".$row['id'].")'>Edit</button>
                            <button type='button' class='btn btn-danger btn-sm' onclick='deleteSignature(".$row['id'].")'>Delete</button>
                        </td>";
                        echo "</tr>";
                        $sn++;
                    }
                    ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Edit Modal -->
                <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">Edit E Signature</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form method="post" action="controller/processLab.php" enctype="multipart/form-data"
                                id="editForm">
                                <div class="modal-body">
                                    <input type="hidden" name="id" id="edit_id">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="edit_user_name">Name <span
                                                        class='text-danger'>*</span></label>
                                                <input type="text" class="form-control" name="user_name"
                                                    id="edit_user_name" required />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="edit_designation">Designation <span
                                                        class='text-danger'>*</span></label>
                                                <input type="text" class="form-control" name="designation"
                                                    id="edit_designation" required />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="edit_qualification">Qualification</label>
                                                <input type="text" class="form-control" name="qualification"
                                                    id="edit_qualification" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="edit_position">Position</label>
                                                <select class="form-control" name="position" id="edit_position">
                                                    <option value="">Select Position</option>
                                                    <option value="left">Left</option>
                                                    <option value="right">Right</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="edit_signature">Upload New Signature (Optional)</label>
                                                <input type="file" class="form-control" name="signature"
                                                    accept="image/png, image/jpg, image/jpeg" />
                                                <small class="form-text text-muted">Leave empty to keep existing
                                                    signature</small>
                                            </div>
                                        </div>

                                        <!-- Add this new section for signature preview -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Current Signature</label>
                                                <div class="text-center">
                                                    <img id="edit_signature_preview" src="" alt="Current Signature"
                                                        style="max-width: 200px; max-height: 100px; border: 1px solid #ddd; padding: 5px; display: none;" />
                                                    <div id="no_signature_message"
                                                        style="color: #999; font-style: italic;">No signature uploaded
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" name="submit" value="editESignature"
                                        class="btn btn-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Delete Confirmation Modal -->
                <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to delete this eSignature?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <form method="post" action="controller/processLab.php" style="display: inline;">
                                    <input type="hidden" name="id" id="delete_id">
                                    <button type="submit" name="submit" value="deleteESignature"
                                        class="btn btn-danger">Delete</button>
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

    <!-- jQuery first -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Popper.js (required for Bootstrap) -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>

    <script>
    function editSignature(id) {
        // Fetch signature data via AJAX
        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            data: {
                id: id,
                page: 'get_e_signature_data'
            },
            dataType: 'json',
            success: function(response) {
                // Check what is coming in response
                // Check if response is valid and contains required fields
                // Defensive: try to parse if string, and check for id as string or number
                let resp = response;
                if (typeof resp === 'string') {
                    try {
                        resp = JSON.parse(resp);
                    } catch (e) {
                        alert('Error parsing signature data');
                        return;
                    }
                }
                // Accept id as string or number, and not empty
                if (resp && (resp.id !== undefined && resp.id !== null && resp.id !== '')) {
                    $('#edit_id').val(resp.id || '');
                    $('#edit_user_name').val(resp.user_name || '');
                    $('#edit_designation').val(resp.designation || '');
                    $('#edit_qualification').val(resp.qualification || '');
                    $('#edit_position').val(resp.position || '');

                    // Handle signature preview
                    if (resp.signature_image && resp.signature_image.trim() !== '') {
                        $('#edit_signature_preview').attr('src', resp.signature_image).show();
                        $('#no_signature_message').hide();
                    } else {
                        $('#edit_signature_preview').hide();
                        $('#no_signature_message').show();
                    }

                    $('#editModal').modal('show');
                } else {
                    alert('Error loading signature data');
                }
            },
            error: function() {
                alert('Error loading signature data');
            }
        });
    }

    function deleteSignature(id) {
        $('#delete_id').val(id);
        $('#deleteModal').modal('show');
    }

    function fillUserDetails(select) {
        var selectedOption = select.options[select.selectedIndex];
        if (selectedOption.value) {
            $('#user_name').val(selectedOption.getAttribute('data-name'));
            $('#designation').val(selectedOption.getAttribute('data-designation') || '');
            $('#qualification').val(selectedOption.getAttribute('data-qualification') || '');
        } else {
            $('#user_name').val('');
            $('#designation').val('');
            $('#qualification').val('');
        }
    }
    </script>

</body>

</html>