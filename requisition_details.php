<?php
/**
 * One requisition: its lines, its approval trail, the approve/reject control
 * when the signed-in user can sign the level it is waiting on, and — once
 * approved — the form that turns it into a tender.
 */
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set('Asia/Kolkata');
include('model/procurementClass.php');

$proc = new procurement;
$id   = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$req  = $proc->loadEntity('REQUISITION', $id);

if (!$req) {
    $page_title = 'Requisition';
    include('procurement_head.php');
    echo '<div class="alert alert-danger">Requisition not found.</div>';
    include('procurement_foot.php');
    exit;
}

$lines      = $proc->getRequisitionItems($id);
$history    = $proc->approvalHistory('REQUISITION', $id);
$levels     = $proc->applicableLevels('REQUISITION', $req['_amount']);
$pending    = $proc->pendingLevel('REQUISITION', $id, $req['_amount']);
$canApprove = ($req['status'] === 'SUBMITTED') && $proc->canApprove('REQUISITION', $id, $req['_amount']);

// Approved requisitions can be floated as a tender; keep the lines to hand.
$tenderLines = array();
if ($req['status'] === 'APPROVED') {
    $res = $proc->getRequisitionItems($id);
    while ($l = mysqli_fetch_assoc($res)) { $tenderLines[] = $l; }
}

$page_title = 'Requisition ' . $req['requisition_no'];
include('procurement_head.php');
?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h4 class="pr-num"><?= pr_e($req['requisition_no']) ?>
                            <?= pr_badge($req['status']) ?>
                            <?php if ($req['priority'] !== 'Normal') { ?>
                                <span class="badge badge-danger"><?= pr_e($req['priority']) ?></span>
                            <?php } ?>
                        </h4>
                    </div>
                    <div class="col-md-4 text-right">
                        <a href="procurement_requisition.php" class="btn btn-sm btn-light">&larr; All Requisitions</a>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-3"><small class="text-muted">Department</small><br>
                        <b><?= pr_e($req['department']) ?></b></div>
                    <div class="col-md-2"><small class="text-muted">Required By</small><br>
                        <?= $req['required_by'] ? date('d.m.Y', strtotime($req['required_by'])) : '&mdash;' ?></div>
                    <div class="col-md-2"><small class="text-muted">Estimated</small><br>
                        <b><?= pr_money($req['estimated_amount']) ?></b></div>
                    <div class="col-md-2"><small class="text-muted">Raised By</small><br>
                        <?= pr_e($req['created_by']) ?></div>
                    <div class="col-md-3"><small class="text-muted">Justification</small><br>
                        <?= pr_e($req['justification']) ?></div>
                </div>
                <?php if ($req['status'] === 'REJECTED' && $req['reject_reason']) { ?>
                    <div class="alert alert-danger mt-3 mb-0">
                        <b>Rejected:</b> <?= pr_e($req['reject_reason']) ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h5 class="ml-2">Items</h5>
                <hr>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th><th>Item</th><th class="num">Qty</th>
                                <th class="num">Est. Rate</th><th class="num">Value</th><th>Specification</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $n = 1;
                            while ($l = mysqli_fetch_assoc($lines)) { ?>
                                <tr>
                                    <td><?= $n++ ?></td>
                                    <td><?= pr_e($l['item_name']) ?><br>
                                        <small class="text-muted"><?= pr_e($l['item_code']) ?></small></td>
                                    <td class="num"><?= rtrim(rtrim(number_format((float) $l['quantity'], 3), '0'), '.') ?>
                                        <small class="text-muted"><?= pr_e($l['unit_name']) ?></small></td>
                                    <td class="num"><?= pr_money($l['estimated_rate']) ?></td>
                                    <td class="num"><?= pr_money($l['estimated_value']) ?></td>
                                    <td><small><?= pr_e($l['specification']) ?></small></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr><th colspan="4" class="text-right">Total</th>
                                <th class="num"><?= pr_money($req['estimated_amount']) ?></th><th></th></tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5>Approval</h5>
                <p class="text-muted mb-2"><small>
                    <?= count($levels) ?> level(s) apply at <?= pr_money($req['_amount']) ?>.
                </small></p>
                <hr>
                <ul class="pr-timeline">
                    <?php foreach ($levels as $lv) {
                        $done = null;
                        foreach ($history as $h) {
                            if ((int) $h['level'] === (int) $lv['level']) { $done = $h; break; }
                        } ?>
                        <li>
                            <b>Level <?= (int) $lv['level'] ?></b> &mdash; <?= pr_e($lv['approver_role']) ?>
                            <?php if ($done) { ?>
                                <span class="badge badge-<?= $done['action'] === 'APPROVED' ? 'success' : 'danger' ?> float-right">
                                    <?= pr_e($done['action']) ?>
                                </span>
                                <br><small class="text-muted">
                                    <?= pr_e($done['acted_by']) ?> ·
                                    <?= date('d.m.Y H:i', strtotime($done['acted_at'])) ?>
                                    <?= $done['remarks'] ? ' · ' . pr_e($done['remarks']) : '' ?>
                                </small>
                            <?php } elseif ($pending && (int) $pending['level'] === (int) $lv['level']) { ?>
                                <span class="badge badge-warning float-right">Waiting</span>
                            <?php } else { ?>
                                <span class="badge badge-light float-right">Not reached</span>
                            <?php } ?>
                        </li>
                    <?php } ?>
                </ul>

                <?php if ($canApprove) { ?>
                    <hr>
                    <form action="controller/processProcurement.php" method="POST">
                        <input type="hidden" name="entity_type" value="REQUISITION">
                        <input type="hidden" name="entity_id" value="<?= $id ?>">
                        <input type="hidden" name="level" value="<?= (int) $pending['level'] ?>">
                        <div class="form-group">
                            <label>Remarks</label>
                            <input type="text" class="form-control" name="remarks" placeholder="Optional">
                        </div>
                        <button type="submit" name="action" value="approve" class="btn btn-success btn-block">
                            <i class="icofont-check"></i> Approve (Level <?= (int) $pending['level'] ?>)
                        </button>
                        <button type="submit" name="action" value="reject" class="btn btn-outline-danger btn-block"
                                onclick="return confirm('Reject this requisition?');">Reject</button>
                    </form>
                <?php } elseif ($req['status'] === 'SUBMITTED' && $pending) { ?>
                    <hr>
                    <div class="alert alert-info mb-0"><small>
                        Waiting on the <b><?= pr_e($pending['approver_role']) ?></b> role at level
                        <?= (int) $pending['level'] ?>. You are signed in as
                        <b><?= pr_e($_SESSION['role']) ?></b>.
                    </small></div>
                <?php } ?>

                <?php if ($req['status'] === 'DRAFT' || $req['status'] === 'REJECTED') { ?>
                    <hr>
                    <form action="controller/processProcurement.php" method="POST">
                        <input type="hidden" name="action" value="submit_requisition">
                        <input type="hidden" name="id" value="<?= $id ?>">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="icofont-paper-plane"></i>
                            <?= $req['status'] === 'REJECTED' ? 'Re-submit for Approval' : 'Submit for Approval' ?>
                        </button>
                        <?php if ($req['status'] === 'REJECTED') { ?>
                            <small class="text-muted">Re-submitting clears the previous approval trail.</small>
                        <?php } ?>
                    </form>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<?php if ($req['status'] === 'APPROVED') { ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h5 class="ml-2">Float as a Tender</h5>
                <p class="text-muted ml-2 mb-2"><small>
                    Lines are carried over from the requisition. Adjust quantities or estimates here if needed.
                </small></p>
                <hr>
                <form action="controller/processProcurement.php" method="POST">
                    <input type="hidden" name="action" value="create_tender">
                    <input type="hidden" name="requisition_id" value="<?= $id ?>">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="title" required
                                       value="<?= pr_e($req['department'] . ' — ' . $req['requisition_no']) ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Type</label>
                                <select name="tender_type" class="form-control">
                                    <option value="Open">Open</option>
                                    <option value="Limited">Limited</option>
                                    <option value="Single">Single</option>
                                    <option value="Rate_Contract">Rate Contract</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Published</label>
                                <input type="date" class="form-control" name="published_date" value="<?= date('Y-m-d') ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Closing</label>
                                <input type="date" class="form-control" name="closing_date"
                                       value="<?= date('Y-m-d', strtotime('+14 days')) ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>EMD Amount</label>
                                <input type="number" step="0.01" min="0" class="form-control" name="emd_amount" value="0">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control" name="description" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Terms &amp; Conditions</label>
                                <textarea class="form-control" name="terms_conditions" rows="2"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered" id="tndItems">
                            <thead class="thead-light">
                                <tr><th style="width:34%">Item</th><th style="width:14%">Quantity</th>
                                    <th style="width:14%">Est. Rate</th><th style="width:12%" class="num">Value</th>
                                    <th style="width:26%">Specification</th></tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tenderLines as $l) { ?>
                                    <tr>
                                        <td>
                                            <input type="hidden" name="item_id[]" value="<?= (int) $l['item_id'] ?>">
                                            <?= pr_e($l['item_name']) ?>
                                            <small class="text-muted">(<?= pr_e($l['item_code']) ?>)</small>
                                        </td>
                                        <td><input type="number" step="0.001" min="0" class="form-control pr-qty"
                                                   name="quantity[]" value="<?= (float) $l['quantity'] ?>"></td>
                                        <td><input type="number" step="0.01" min="0" class="form-control pr-rate"
                                                   name="rate[]" value="<?= (float) $l['estimated_rate'] ?>"></td>
                                        <td class="num pr-value"><?= pr_money($l['estimated_value']) ?></td>
                                        <td><input type="text" class="form-control" name="specification[]"
                                                   value="<?= pr_e($l['specification']) ?>"></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr><th colspan="3" class="text-right">Estimated Value</th>
                                    <th class="num"><span id="pr-total"><?= pr_money($req['estimated_amount']) ?></span></th>
                                    <th><small id="pr-total-hint" class="text-danger" style="display:none">
                                        Above 50,000 — needs Admin approval.</small></th></tr>
                            </tfoot>
                        </table>
                    </div>

                    <button type="submit" class="btn btn-success btn-rounded float-right">
                        <i class="icofont-file-document"></i> Create Tender
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<?php include('procurement_foot.php'); ?>
