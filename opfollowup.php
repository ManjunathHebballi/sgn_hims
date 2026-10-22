<?php
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");
include('model/opClass.php');
$newOP = new addOP;
$dept=$newOP->selectOPregToday();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>OP Follow Up</title>
    <!-- Favicon icon -->
      <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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

        /* Fields whose value is already on file (or came from the verified ABHA
           profile) — read-only, and visibly so, otherwise a locked box just looks
           like a form that ignores typing. */
        input.abha-locked {
            cursor: not-allowed;
        }
</style>

</head>

<body onload="patientDetail(<?php echo htmlspecialchars(json_encode((string) ($_REQUEST['opd'] ?? ''), JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP), ENT_QUOTES, 'UTF-8'); ?>); applyAbhaFollowupOverride();">


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

<div aria-live="polite" id="notification" aria-atomic="true">
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
         <?php include('leftbar.php');?>

        <!-- Past Follow-Up History modal (opened from the "Past History" button).
             The revisit entry form itself is now inline, not in a modal. -->
        <div class="modal fade text-uppercase" id="historyModal" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered mw-100 w-75">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="historyModalLabel">FOLLOW UP HISTORY <span class="text-danger" id="span"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive" id="revist_modal">
                            <table id="datatable-buttons" class="table table-bordered table-striped display text-center">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Sl NO</th>
                                        <th>Visit Date</th>
                                        <th>Department</th>
                                        <th>Comments</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center text-black">
                                    <tr></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">

            <div class="container-fluid text-uppercase">

                <!-- Page header -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <h3 class="page-title text-dark font-weight-bold mb-2 px-4">OP PATIENT FOLLOW UP</h3>
                    </div>
                </div>

                <form action="controller/processOPRegistration.php" method="post" id="followupForm">
                    <!-- Patient details + inline Follow-Up entry form -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Add Follow Up</h4>
                                </div>
                                <div class="card-body">

                                    <!-- Patient details -->
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label> OP Register No <span style="color:red">*</span></label>
                                                <input type="text" class="form-control" name="opno" id="opno" onkeyup="javascript:patientDetail(this.value)" value="<?php echo isset($_REQUEST['opd']) ? htmlspecialchars($_REQUEST['opd']) : ''; ?>" required placeholder="Enter OP Register No">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label> UHID</label>
                                                <input type="text" class="form-control" name="uhid" id="uhid" placeholder="UHID" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label> Patient Name</label>
                                                <input type="text" class="form-control" name="name" id="name" placeholder="Patient Name" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label> Sex</label>
                                                <input type="text" class="form-control" name="sex" id="sex" placeholder="Sex" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label> Age</label>
                                                <input type="text" class="form-control" name="age" id="age" placeholder="Age" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label> DOB</label>
                                                <input type="text" class="form-control" name="dob" id="dob" placeholder="Not on file" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label> Mobile No <span style="color:red">*</span></label>
                                                <input type="text" class="form-control" name="mobile" id="mobile" placeholder="Mobile No" form="followupForm" inputmode="numeric" maxlength="10" required>
                                                <small id="mobileHint" style="color:#e53e3e;font-size:12px;display:none;"></small>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label> Address <span style="color:red">*</span></label>
                                                <input type="text" class="form-control" name="address" id="address" placeholder="Address" form="followupForm" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label> City</label>
                                                <input type="text" class="form-control" name="city" id="city" placeholder="City" form="followupForm">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label> District</label>
                                                <input type="text" class="form-control" name="district" id="district" placeholder="District" form="followupForm">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label> State</label>
                                                <input type="text" class="form-control" name="state" id="state" placeholder="State" form="followupForm">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label> PIN Code</label>
                                                <input type="text" class="form-control" name="pincode" id="pincode" placeholder="PIN Code" form="followupForm" maxlength="6" inputmode="numeric" pattern="\d{6}" title="6-digit PIN Code">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label> ABHA Number</label>
                                                <input type="text" class="form-control" name="abha_number" id="abha_number" placeholder="ABHA Number" form="followupForm" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label> ABHA Address</label>
                                                <input type="text" class="form-control text-lowercase" name="abha_address" id="abha_address" placeholder="ABHA Address" form="followupForm" readonly style="text-transform:lowercase">
                                                <!-- Shown instead of the input when this ABHA number owns more than one address. -->
                                                <select class="form-control text-lowercase" id="abha_address_dd" form="followupForm" style="display:none;text-transform:lowercase"></select>
                                            </div>
                                        </div>

                                    </div>

                                    <div id="abhaSrcNote" class="alert alert-info py-2 px-3 mb-2" style="display:none;">
                                        <i class="icofont-info-circle"></i> <b id="abhaSrcNoteFields"></b> came from the verified ABHA profile and cannot be edited here. Saving the follow-up will update this patient's record with these values.
                                    </div>

                                    <hr class="my-2">

                                    <!-- Revisit entry form (inline, not in a modal). Mobile / Address /
                                         City above are bound to this form (form="followupForm") so they
                                         save onto the patient record when the follow-up is added. -->
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="date"> Date</label>
                                                <input value="<?php echo date("Y-m-d H:i"); ?>" type="datetime-local" class="form-control" name="date" id="date">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="dept"> Department <span style="color:red">*</span></label>
                                                <select name="dept" id="dept" class="form-control" required>
                                                    <option value="">Select Department</option>
                                                    <?php
                                                    $dept1 = $newOP->selectDept();
                                                    while ($row = mysqli_fetch_assoc($dept1)) {
                                                    ?>
                                                        <option value="<?= $row['id'] ?>"><?= $row['dept_name'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label> Doctor <span style="color:red">*</span></label>
                                                <select class="form-control mySelect2" name="doctor" id="doctor" required onchange="getConsultation(this.value)">
                                                    <option value="">Select Doctor</option>
                                                    <?php
                                                    $docto = $newOP->getDoctor();
                                                    while ($row = mysqli_fetch_assoc($docto)) {
                                                    ?>
                                                        <option value="<?= $row['id'] ?>"><?= $row['name'] . ' ' . $row['last_name'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Refered by</label>
                                                <input type="text" class="form-control" name="referby" id="referby" placeholder="Enter Doctor Name">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="Comments">Comments</label>
                                                <textarea class="form-control" name="cmts" id="cmts" cols="1" placeholder="Add Comments" rows="1"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <input type="hidden" name="opd_no" id="opd_no" value="">
                                            <input type="hidden" name="opd_no1" id="opd_no1">
                                            <input type="hidden" class="form-control" name="consultancy1" id="consultancy1" placeholder="Enter Consultancy Charge">
                                            <button type="button" class="btn btn-info btn-rounded" onclick="viewHistory()" data-toggle="modal" data-target="#historyModal"><i class="ri-history-line"></i> Past History</button>
                                            <button type="submit" name="submit" value="revisit" class="btn btn-success float-right btn-rounded"><i class="ri-add-line"></i> Add Follow Up</button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- OP Follow List -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">OP Follow List</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example" class="table table-striped display text-center">
                                        <thead>
                                            <tr>
                                                <th>Sl NO</th>
                                                <th>COPID</th>
                                                <th>Visit Date</th>
                                                <th>Name</th>
                                                <th>Gender</th>
                                                <th>Address</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            <?php $sn=1;
                                             while($res=mysqli_fetch_assoc($dept))
                                            { ?>
                                            <tr>
                                                <td><?= $sn?></td>
                                                <td><?= $res['opd_no']?></td>
                                                <td><?= date("d.m.Y",strtotime($res['date']))?></td>
                                                <td><?= $res['name']?></td>
                                                <td><?= $res['gender']?></td>
                                                <td><?= $res['address']?></td>
                                                <td><a href="javascript:void(0)" title="Load into Follow Up form" onclick="selectPatient(<?= htmlspecialchars(json_encode($res['opd_no'], JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP), ENT_QUOTES, 'UTF-8') ?>)" class="badge badge-pill badge-primary"><i class="ri-edit-line" style="font-size:15px"></i> </a></td>
                                            </tr>
                                           <?php $sn++; } ?>
                                            </tbody>
                                            </table>
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

        <script src="./vendor/select2/js/select2.full.min.js"></script>
    <script src="./js/plugins-init/select2-init.js"></script>

    <!-- Datatable -->
    <script src="./vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="./js/plugins-init/datatables.init.js"></script>

 <script>
    // Fill the inline patient details for the entered OP Register No and point the
    // follow-up form at that patient.
    function patientDetail(op) {
        if (!op) return;
        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            data: 'op_no=' + op + '&page=ipreg',
            async: false,
            success: function(s) {
                json_events = s.split("|");
                // ajaxClass::getOPDetails() column order for THIS project:
                // name[3], age[4], gender[5], mobile[6], address[7], uhid[25],
                // city[36], dob[39], abha_number[40], abha_address[41],
                // state[42], district[43], pincode[44].
                // Everything from [39] on is appended at the END of that SELECT so
                // the positional indexes the billing pages rely on stay put.
                $("#name").val(json_events[3]);
                $("#age").val(json_events[4]);
                $("#sex").val(json_events[5]);
                $("#mobile").val(json_events[6]);
                $("#address").val(json_events[7]);
                $("#uhid").val(json_events[25]);
                $("#city").val(json_events[36] || '');
                var dob = json_events[39] || '';
                $("#dob").val(dob === '0000-00-00' ? '' : dob);
                $("#abha_number").val(json_events[40] || '');
                $("#abha_address").val((json_events[41] || '').toLowerCase());
                $("#state").val(json_events[42] || '');
                $("#district").val(json_events[43] || '');
                $("#pincode").val(json_events[44] || '');
                // Clear any locks left from a previously-loaded patient, then lock
                // whatever THIS record holds. Only fields with nothing on file stay
                // editable.
                ensureContactEditable();
                showPinInAddress(); // display the stored PIN as part of the address
                applyContactLocks();
            }
        });
        // Target the revisit form at this patient.
        document.getElementById('opd_no').value = op;
        document.getElementById('opd_no1').value = op;
        // Offer a dropdown when this ABHA number owns more than one address.
        loadAbhaAddressOptions($('#abha_number').val());
    }

    // Mobile / Address / City / State / District / PIN start editable, whether the
    // value came from the patient record or from an ABHA profile. Name / Sex / Age
    // / DOB / UHID and the ABHA ids stay read-only throughout.
    var CONTACT_FIELDS = '#mobile, #address, #city, #state, #district, #pincode';

    // ── PIN inside the Address box ────────────────────────────────────────
    // The ABHA card shows "…, KADI - 382715", so the follow-up shows the same
    // complete postal address and stores it exactly as displayed. This helper only
    // rebuilds the display (strip the old suffix before re-appending the current
    // PIN); it never alters what is submitted beyond that.
    function addressWithoutPin() {
        var pin = ($('#pincode').val() || '').replace(/\D/g, '');
        var addr = ($('#address').val() || '').trim();
        if (pin && addr) {
            addr = addr.replace(new RegExp('\\s*-\\s*' + pin + '\\s*$'), '').trim();
        }
        return addr;
    }

    function showPinInAddress() {
        var pin = ($('#pincode').val() || '').replace(/\D/g, '');
        var addr = addressWithoutPin();
        // Never put a bare PIN in an empty Address — a PIN on its own is not an
        // address, and Address is required.
        $('#address').val(pin && addr ? addr + ' - ' + pin : addr);
    }

    function ensureContactEditable() {
        $(CONTACT_FIELDS)
            .prop('readonly', false)
            .prop('disabled', false)
            .removeClass('abha-locked')
            .attr('title', 'Editable — update if the patient gives different details');
    }

    // `readonly` (not `disabled`) — a disabled input is not submitted, and these
    // values must still save onto the patient record.
    function lockContactField(sel, why) {
        $(sel).prop('readonly', true).addClass('abha-locked')
            .attr('title', why || 'Already on file — not editable here');
    }

    // Contact details are read-only on this screen: the follow-up shows what is
    // already on file (or what the verified ABHA profile supplied) and must not
    // become a place where those quietly diverge.
    //
    // A field with NO value stays editable — Mobile and Address are `required`, so
    // locking an empty one would leave the follow-up impossible to save for a
    // patient whose record has neither.
    function applyContactLocks() {
        $(CONTACT_FIELDS).each(function() {
            if (String($(this).val() || '').trim() !== '') {
                lockContactField(this);
            } else {
                $(this).prop('readonly', false).removeClass('abha-locked')
                    .attr('title', 'Not on file — enter it here');
            }
        });
    }

    // Fetch every ABHA address on record for this ABHA number. When more than one
    // exists, show a dropdown (submitting as abha_address) instead of the single
    // read-only input; otherwise keep the input. The currently shown address is
    // pre-selected.
    function loadAbhaAddressOptions(abhaNumber) {
        var $dd = $('#abha_address_dd'),
            $inp = $('#abha_address');

        function showSingle() {
            $dd.hide().removeAttr('name').empty();
            $inp.prop('disabled', false).attr('name', 'abha_address').show();
        }
        abhaNumber = (abhaNumber || '').toString().trim();
        if (!abhaNumber) {
            showSingle();
            return;
        }
        $.ajax({
            url: 'abha_link_patient.php',
            method: 'POST',
            contentType: 'application/json',
            dataType: 'json',
            data: JSON.stringify({
                page: 'list_abha_addresses',
                abha_number: abhaNumber
            }),
            success: function(r) {
                var list = (r && r.success && r.addresses) ? r.addresses : [];
                var cur = ($inp.val() || '').toLowerCase();
                var addrs = list.map(function(x) {
                        return (x.abha_address || '').toLowerCase();
                    })
                    .filter(Boolean);
                // Always include the address currently on the record.
                if (cur && addrs.indexOf(cur) === -1) addrs.unshift(cur);
                addrs = addrs.filter(function(v, i) {
                    return addrs.indexOf(v) === i;
                });
                if (addrs.length > 1) {
                    $dd.html(addrs.map(function(a) {
                        return '<option value="' + a + '"' + (a === cur ? ' selected' : '') + '>' + a + '</option>';
                    }).join(''));
                    // The dropdown becomes the submitting field; disable the input.
                    $dd.attr('name', 'abha_address').show();
                    $inp.prop('disabled', true).removeAttr('name').hide();
                } else {
                    showSingle();
                }
            },
            error: showSingle
        });
    }

    // "Go with HIMS" (from createAabha.php) opens this page with abha_src=1 and the
    // ABHA profile's mobile/address. Override the DB-loaded values so the follow-up
    // saves the ABDM details onto the patient record. Runs after patientDetail().
    function applyAbhaFollowupOverride() {
        var qp = new URLSearchParams(window.location.search);
        if (qp.get('abha_src') !== '1') return;
        var an = qp.get('abha_number'),
            aa = qp.get('abha_address');
        // Use the verified ABHA number/address from the card, not the stored one.
        if (an) $('#abha_number').val(an);
        if (aa) $('#abha_address').val(aa.toLowerCase());

        // Fields ABDM supplied replace whatever the record held. applyContactLocks()
        // then locks every field that ends up with a value, from either source.
        var FROM_ABHA = [
            { key: 'mobile',   sel: '#mobile',   label: 'Mobile' },
            { key: 'address',  sel: '#address',  label: 'Address' },
            { key: 'state',    sel: '#state',    label: 'State' },
            { key: 'district', sel: '#district', label: 'District' },
            { key: 'pincode',  sel: '#pincode',  label: 'PIN Code' }
        ];
        var supplied = [];
        FROM_ABHA.forEach(function(f) {
            var v = qp.get(f.key);
            if (!v) return;
            $(f.sel).val(v);
            supplied.push(f.label);
        });

        // Show the PIN as part of the address, as the ABHA card does, then lock
        // everything that now holds a value (ABDM's or the record's).
        showPinInAddress();
        applyContactLocks();
        if (!supplied.length) return; // nothing came from ABDM — no banner

        $('#mobile, #address, #abha_number, #abha_address, #abha_address_dd, #state, #district, #pincode')
            .css('background-color', '#fff3cd');
        $('#abhaSrcNoteFields').text(supplied.join(', '));
        $('#abhaSrcNote').show();
        // Rebuild the address dropdown for the (possibly overridden) ABHA number so
        // the verified address is pre-selected and every other address stays pickable.
        if (an || aa) loadAbhaAddressOptions($('#abha_number').val());
    }

    // Load a patient from the "OP Follow List" into the inline form.
    function selectPatient(op) {
        document.getElementById('opno').value = op;
        patientDetail(op);
    }

    // Show the patient's past follow-ups inside the history modal.
    function viewHistory() {
        var val = document.getElementById('opno').value;
        document.getElementById('span').innerHTML = '(Patient Id:' + val + ')';
        document.getElementById('opd_no').value = val;
        document.getElementById('opd_no1').value = val;
        $.ajax({
            type: 'post',
            url: 'ajax.php',
            data: 'op_no=' + val + '&page=opd_modal',
            success: function(data) {
                $('#revist_modal').html(data);
            }
        });
    }

    window.setTimeout(function() {
        $(".alert").fadeTo(500, 0).slideUp(500, function(){
            $(this).remove();
        });
    }, 4000);

    $(document).ready(function() {
        $('.select2search').select2();
        ensureContactEditable(); // also covers landing here without an ?opd=
        // Keep the address suffix in step with the PIN field. Nothing rewrites the
        // field on submit — the address is stored exactly as displayed.
        $('#pincode').on('input change', showPinInAddress);
    });

    // PIN Code: digits only, at most 6 (the column stores digits only either way).
    $('#pincode').on('input', function() {
        var digits = (this.value || '').replace(/\D/g, '').slice(0, 6);
        if (digits !== this.value) this.value = digits;
    });

    function getConsultation(val)
    {
        dept=document.getElementById('dept').value;
        $.ajax({
            url: 'ajax.php',
            type: 'POST', // Send post data
            data: 'id='+val+'&page=doctor_cons',
            async: false,
            success: function(s){
                $('#consultancy').val(s);
                $('#consultancy1').val(s);
            }
        });
    }

    $('.mySelect2').select2();

    // Mobile number: digits only, exactly 10, must start with 6-9.
    $('#mobile').on('input', function() {
        var digits = (this.value || '').replace(/\D/g, ''); // no alphabets/symbols
        digits = digits.replace(/^[0-5]+/, '');             // cannot start with 0-5
        digits = digits.slice(0, 10);                       // max 10 digits
        if (digits !== this.value) this.value = digits;
        var $hint = $('#mobileHint');
        if (this.value.length === 0 || this.value.length === 10) {
            $hint.hide().text('');
        } else {
            $hint.text('Mobile number must be 10 digits starting with 6-9.').show();
        }
    });
 </script>

</body>

</html>
