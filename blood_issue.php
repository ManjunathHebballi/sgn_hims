<?php
/**
 * Stage 5 — Request, crossmatch, issue and transfusion outcome.
 *
 * Three views on one screen:
 *   default            new request form + outstanding requests
 *   ?request_id=N      crossmatch and issue units against one request
 *   ?tab=issues        the issue register, where outcomes are closed out
 *
 * A unit can only be issued after a Compatible crossmatch is on record, and
 * crossmatching reserves the bag so two requests cannot claim it.
 */
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set('Asia/Kolkata');
include('model/bloodBankClass.php');

$newBlood = new addBlood;

$request_id = isset($_GET['request_id']) ? (int) $_GET['request_id'] : 0;
$tab        = isset($_GET['tab']) ? $_GET['tab'] : '';

$request      = $request_id > 0 ? $newBlood->getRequest($request_id) : null;
$componentRef = $newBlood->getComponentTypes();
$groups       = array('A+','A-','B+','B-','AB+','AB-','O+','O-');

$page_title = 'Blood Issue';
include('blood_bank_head.php');

function bb_e($v) { return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8'); }

function bb_urgency_badge($u)
{
    $map = array('Emergency' => 'badge-danger', 'Urgent' => 'badge-warning', 'Routine' => 'badge-secondary');
    $cls = isset($map[$u]) ? $map[$u] : 'badge-light';
    return '<span class="badge ' . $cls . '">' . htmlspecialchars((string) $u, ENT_QUOTES, 'UTF-8') . '</span>';
}
?>

<div class="row mb-3 ml-1">
    <h4 class="text-uppercase">Blood Issue</h4>
</div>

<ul class="nav nav-pills mb-3 justify-content-around">
    <li class="nav-item">
        <a class="nav-link <?= ($tab === '' && !$request) ? 'active' : '' ?>" href="blood_issue.php">Requests</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $tab === 'issues' ? 'active' : '' ?>" href="blood_issue.php?tab=issues">Issue Register</a>
    </li>
</ul>

<?php if ($request) {
    // ------------------------------------------------ one request: crossmatch + issue
    $compatible  = $newBlood->getCompatibleUnits($request['blood_group'], $request['component_type']);
    $crossmatches = $newBlood->getCrossmatches($request_id);
    $donorGroups = $newBlood->compatibleDonorGroups($request['blood_group']);
?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h5><?= bb_e($request['request_number']) ?>
                            <?= bb_urgency_badge($request['urgency']) ?>
                            <span class="badge badge-info"><?= bb_e(str_replace('_', ' ', $request['request_status'])) ?></span>
                        </h5>
                    </div>
                    <div class="col-md-4 text-right">
                        <a href="blood_issue.php" class="btn btn-sm btn-light">&larr; All Requests</a>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-3"><small class="text-muted">Patient</small><br>
                        <b><?= bb_e($request['patient_name']) ?></b><br>
                        <small><?= bb_e($request['patient_no']) ?> &middot; <?= bb_e($request['patient_type']) ?></small></div>
                    <div class="col-md-2"><small class="text-muted">Group / Component</small><br>
                        <b class="text-danger"><?= bb_e($request['blood_group']) ?></b><br>
                        <small><?= bb_e(str_replace('_', ' ', $request['component_type'])) ?></small></div>
                    <div class="col-md-2"><small class="text-muted">Units</small><br>
                        <b><?= (int) $request['units_issued'] ?> / <?= (int) $request['units_required'] ?></b> issued</div>
                    <div class="col-md-2"><small class="text-muted">Ward</small><br>
                        <?= bb_e($request['ward']) ?></div>
                    <div class="col-md-3"><small class="text-muted">Requested By</small><br>
                        <?= bb_e($request['requested_by']) ?><br>
                        <small><?= bb_e($request['indication']) ?></small></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5>Crossmatch a Unit</h5>
                <p class="text-muted mb-2"><small>
                    Compatible donor groups for <b><?= bb_e($request['blood_group']) ?></b>:
                    <?= bb_e(implode(', ', $donorGroups)) ?>
                </small></p>
                <hr>
                <?php if (!$compatible) { ?>
                    <div class="alert alert-warning mb-0">
                        <i class="icofont-warning"></i> No compatible
                        <?= bb_e(str_replace('_', ' ', $request['component_type'])) ?> units are in inventory.
                    </div>
                <?php } else { ?>
                <form action="controller/processBloodBank.php" method="POST">
                    <input type="hidden" name="action" value="save_crossmatch">
                    <input type="hidden" name="request_id" value="<?= $request_id ?>">
                    <div class="form-group">
                        <label>Unit <span class="text-danger">*</span></label>
                        <select name="unit_id" class="form-control" required>
                            <option value="">Select a compatible unit</option>
                            <?php foreach ($compatible as $u) { ?>
                                <option value="<?= (int) $u['unit_id'] ?>">
                                    <?= bb_e($u['unit_barcode']) ?> &mdash; <?= bb_e($u['blood_group']) ?>
                                    &mdash; expires <?= date('d.m.Y', strtotime($u['expiry_date'])) ?>
                                    (<?= (int) $u['days_to_expiry'] ?> days)
                                </option>
                            <?php } ?>
                        </select>
                        <small class="form-text text-muted">Oldest-expiring first, so stock rotates.</small>
                    </div>
                    <div class="form-group">
                        <label>Method</label>
                        <select name="method" class="form-control">
                            <option value="AHG_Coombs">AHG / Coombs</option>
                            <option value="Saline">Saline</option>
                            <option value="Gel_Card">Gel Card</option>
                            <option value="Electronic">Electronic</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Result <span class="text-danger">*</span></label>
                        <select name="result" class="form-control" required>
                            <option value="Compatible">Compatible</option>
                            <option value="Incompatible">Incompatible</option>
                            <option value="Pending">Pending</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Note</label>
                        <input type="text" class="form-control" name="incompatibility_note"
                               placeholder="Required if incompatible">
                    </div>
                    <button type="submit" class="btn btn-success btn-rounded float-right">
                        <i class="icofont-check"></i> Save Crossmatch
                    </button>
                </form>
                <?php } ?>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5>Crossmatch Results</h5>
                <hr>
                <div class="table-responsive">
                    <table class="table table-bordered text-center">
                        <thead class="thead-dark">
                            <tr><th>Unit</th><th>Method</th><th>Result</th><th>By</th><th>Issue</th></tr>
                        </thead>
                        <tbody>
                            <?php
                            $any = false;
                            while ($x = $crossmatches->fetch_assoc()) {
                                $any = true; ?>
                                <tr>
                                    <td class="bb-barcode"><?= bb_e($x['unit_barcode']) ?><br>
                                        <small class="text-muted"><?= bb_e($x['blood_group']) ?></small></td>
                                    <td><small><?= bb_e(str_replace('_', ' ', $x['method'])) ?></small></td>
                                    <td>
                                        <span class="badge badge-<?= $x['result'] === 'Compatible' ? 'success' : ($x['result'] === 'Incompatible' ? 'danger' : 'secondary') ?>">
                                            <?= bb_e($x['result']) ?>
                                        </span>
                                        <?php if ($x['incompatibility_note']) { ?>
                                            <br><small class="text-danger"><?= bb_e($x['incompatibility_note']) ?></small>
                                        <?php } ?>
                                    </td>
                                    <td><small><?= bb_e($x['performed_by']) ?></small></td>
                                    <td>
                                        <?php if ($x['result'] === 'Compatible' && $x['unit_status'] === 'Reserved') { ?>
                                            <form action="controller/processBloodBank.php" method="POST" style="display:inline">
                                                <input type="hidden" name="action" value="issue_unit">
                                                <input type="hidden" name="request_id" value="<?= $request_id ?>">
                                                <input type="hidden" name="unit_id" value="<?= (int) $x['unit_id'] ?>">
                                                <input type="text" name="collected_by" class="form-control form-control-sm mb-1"
                                                       placeholder="Collected by" required>
                                                <button type="submit" class="btn btn-sm btn-primary">Issue</button>
                                            </form>
                                        <?php } elseif ($x['unit_status'] === 'Issued') { ?>
                                            <span class="badge badge-primary">Issued</span>
                                        <?php } else { ?>
                                            <span class="text-muted">&mdash;</span>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php }
                            if (!$any) { ?>
                                <tr><td colspan="5" class="text-muted">No crossmatch recorded yet.</td></tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php } elseif ($tab === 'issues') {
    // ------------------------------------------------------------- issue register
    $issues = $newBlood->getIssues();
?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h5 class="ml-2">Issue Register</h5>
                <p class="text-muted ml-2 mb-2"><small>Close out each issued unit — transfused, returned to stock, or discarded.</small></p>
                <hr>
                <div class="table-responsive">
                    <table class="table table-bordered text-center bb-table">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Issue No.</th>
                                <th>Request</th>
                                <th>Patient</th>
                                <th>Unit</th>
                                <th>Group</th>
                                <th>Issued</th>
                                <th>Status</th>
                                <th>Outcome</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            while ($is = $issues->fetch_assoc()) { ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= bb_e($is['issue_number']) ?></td>
                                    <td><?= bb_e($is['request_number']) ?><br>
                                        <small class="text-muted"><?= bb_e($is['ward']) ?></small></td>
                                    <td class="text-left"><?= bb_e($is['patient_name']) ?><br>
                                        <small class="text-muted"><?= bb_e($is['patient_no']) ?></small></td>
                                    <td class="bb-barcode"><?= bb_e($is['unit_barcode']) ?><br>
                                        <small class="text-muted"><?= bb_e(str_replace('_', ' ', $is['component_type'])) ?></small></td>
                                    <td><b><?= bb_e($is['blood_group']) ?></b></td>
                                    <td><?= $is['issued_date'] ? date('d.m.Y H:i', strtotime($is['issued_date'])) : '' ?><br>
                                        <small class="text-muted"><?= bb_e($is['issued_by']) ?></small></td>
                                    <td>
                                        <span class="badge badge-<?= $is['transfusion_status'] === 'Transfused' ? 'success' : ($is['transfusion_status'] === 'Issued' ? 'primary' : 'secondary') ?>">
                                            <?= bb_e($is['transfusion_status']) ?>
                                        </span>
                                        <?php if ((int) $is['reaction_flag'] === 1) { ?>
                                            <br><span class="badge badge-danger">Reaction</span>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php if ($is['transfusion_status'] === 'Issued') { ?>
                                            <button class="btn btn-sm btn-outline-primary" data-toggle="modal"
                                                    data-target="#outcome<?= (int) $is['issue_id'] ?>">Record</button>

                                            <div class="modal fade" id="outcome<?= (int) $is['issue_id'] ?>" tabindex="-1">
                                              <div class="modal-dialog"><div class="modal-content">
                                                <form action="controller/processBloodBank.php" method="POST">
                                                  <input type="hidden" name="action" value="record_outcome">
                                                  <input type="hidden" name="issue_id" value="<?= (int) $is['issue_id'] ?>">
                                                  <div class="modal-header">
                                                    <h5 class="modal-title">Outcome &mdash; <?= bb_e($is['unit_barcode']) ?></h5>
                                                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                                  </div>
                                                  <div class="modal-body text-left">
                                                    <div class="form-group">
                                                      <label>Outcome</label>
                                                      <select name="transfusion_status" class="form-control" required>
                                                        <option value="Transfused">Transfused</option>
                                                        <option value="Returned">Returned to blood bank</option>
                                                        <option value="Discarded">Discarded</option>
                                                      </select>
                                                    </div>
                                                    <div class="form-group">
                                                      <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input"
                                                               id="back<?= (int) $is['issue_id'] ?>" name="back_to_stock" value="1">
                                                        <label class="custom-control-label" for="back<?= (int) $is['issue_id'] ?>">
                                                          Cold chain maintained &mdash; return to stock
                                                        </label>
                                                      </div>
                                                      <small class="form-text text-muted">
                                                        Only tick this for a return where the unit stayed within temperature.
                                                        Otherwise it is discarded.
                                                      </small>
                                                    </div>
                                                    <div class="form-group">
                                                      <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input"
                                                               id="rx<?= (int) $is['issue_id'] ?>" name="reaction_flag" value="1">
                                                        <label class="custom-control-label" for="rx<?= (int) $is['issue_id'] ?>">
                                                          Transfusion reaction occurred
                                                        </label>
                                                      </div>
                                                    </div>
                                                    <div class="form-group">
                                                      <label>Reaction Type</label>
                                                      <input type="text" class="form-control" name="reaction_type"
                                                             placeholder="Febrile, allergic, haemolytic…">
                                                    </div>
                                                    <div class="form-group">
                                                      <label>Notes / Return Reason</label>
                                                      <textarea class="form-control" name="reaction_notes" rows="2"></textarea>
                                                      <input type="hidden" name="return_reason" value="">
                                                    </div>
                                                  </div>
                                                  <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-success">Save Outcome</button>
                                                  </div>
                                                </form>
                                              </div></div>
                                            </div>
                                        <?php } else { ?>
                                            <span class="text-muted"><?= $is['transfusion_date'] ? date('d.m.Y', strtotime($is['transfusion_date'])) : '&mdash;' ?></span>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php }
                            if ($i === 1) { ?>
                                <tr><td colspan="9" class="text-muted">No units issued yet.</td></tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php } else {
    // ---------------------------------------------------------- new request + list
    $requests = $newBlood->getRequests();
?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h5 class="ml-2">New Blood Request</h5>
                <hr>
                <form action="controller/processBloodBank.php" method="POST">
                    <input type="hidden" name="action" value="create_request">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Patient Type</label>
                                <select name="patient_type" class="form-control">
                                    <option value="IPD">IPD</option>
                                    <option value="OPD">OPD</option>
                                    <option value="External">External</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Patient No.</label>
                                <input type="text" class="form-control" name="patient_no" placeholder="IP / OP number">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Patient Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" name="patient_name" required>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label>Age</label>
                                <input type="text" class="form-control" name="patient_age">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Gender</label>
                                <select name="patient_gender" class="form-control">
                                    <option value="">Select</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Ward</label>
                                <input type="text" class="form-control" name="ward">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Blood Group <span class="text-danger">*</span></label>
                                <select name="blood_group" class="form-control" required>
                                    <option value="">Select</option>
                                    <?php foreach ($groups as $g) { ?>
                                        <option value="<?= $g ?>"><?= $g ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Component <span class="text-danger">*</span></label>
                                <select name="component_type" class="form-control" required>
                                    <?php foreach ($componentRef as $type => $ref) { ?>
                                        <option value="<?= bb_e($type) ?>"><?= bb_e(str_replace('_', ' ', $type)) ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label>Units <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="units_required" value="1" min="1" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Urgency</label>
                                <select name="urgency" class="form-control">
                                    <option value="Routine">Routine</option>
                                    <option value="Urgent">Urgent</option>
                                    <option value="Emergency">Emergency</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Requested By <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="requested_by" placeholder="Doctor" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Indication</label>
                                <input type="text" class="form-control" name="indication" placeholder="Clinical reason">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-success btn-rounded float-right">
                                <i class="icofont-plus"></i> Create Request
                            </button>
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
                <h5 class="ml-2">Blood Requests</h5>
                <p class="text-muted ml-2 mb-2"><small>Emergency first, then urgent, then routine.</small></p>
                <hr>
                <div class="table-responsive">
                    <table class="table table-bordered text-center bb-table">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Request No.</th>
                                <th>Patient</th>
                                <th>Ward</th>
                                <th>Group</th>
                                <th>Component</th>
                                <th>Units</th>
                                <th>Urgency</th>
                                <th>Status</th>
                                <th>Requested</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            while ($r = mysqli_fetch_assoc($requests)) { ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= bb_e($r['request_number']) ?></td>
                                    <td class="text-left"><?= bb_e($r['patient_name']) ?><br>
                                        <small class="text-muted"><?= bb_e($r['patient_no']) ?></small></td>
                                    <td><?= bb_e($r['ward']) ?></td>
                                    <td><b class="text-danger"><?= bb_e($r['blood_group']) ?></b></td>
                                    <td><?= bb_e(str_replace('_', ' ', $r['component_type'])) ?></td>
                                    <td><?= (int) $r['units_issued'] ?>/<?= (int) $r['units_required'] ?></td>
                                    <td><?= bb_urgency_badge($r['urgency']) ?></td>
                                    <td><span class="badge badge-light"><?= bb_e(str_replace('_', ' ', $r['request_status'])) ?></span></td>
                                    <td><?= $r['created_date'] ? date('d.m.Y H:i', strtotime($r['created_date'])) : '' ?></td>
                                    <td>
                                        <?php if (!in_array($r['request_status'], array('Issued', 'Cancelled'), true)) { ?>
                                            <a href="blood_issue.php?request_id=<?= (int) $r['request_id'] ?>"
                                               class="btn btn-sm btn-primary">Crossmatch</a>
                                            <form action="controller/processBloodBank.php" method="POST"
                                                  onsubmit="return confirm('Cancel this request and release any reserved units?');"
                                                  style="display:inline">
                                                <input type="hidden" name="action" value="cancel_request">
                                                <input type="hidden" name="request_id" value="<?= (int) $r['request_id'] ?>">
                                                <input type="hidden" name="cancel_reason" value="Cancelled from request list">
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Cancel</button>
                                            </form>
                                        <?php } else { ?>
                                            <a href="blood_issue.php?request_id=<?= (int) $r['request_id'] ?>"
                                               class="btn btn-sm btn-outline-secondary">View</a>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php }
                            if ($i === 1) { ?>
                                <tr><td colspan="11" class="text-muted">No requests raised yet.</td></tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<?php include('blood_bank_foot.php'); ?>
