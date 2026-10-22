<?php
/**
 * One tender, across four tabs: its items, bid entry, the comparative
 * statement, and the award (with its own approval and the purchase order it
 * produces).
 */
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set('Asia/Kolkata');
include('model/procurementClass.php');

$proc = new procurement;
$id   = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$tab  = isset($_GET['tab']) ? $_GET['tab'] : 'items';
$t    = $proc->loadEntity('TENDER', $id);

if (!$t) {
    $page_title = 'Tender';
    include('procurement_head.php');
    echo '<div class="alert alert-danger">Tender not found.</div>';
    include('procurement_foot.php');
    exit;
}

$levels     = $proc->applicableLevels('TENDER', $t['_amount']);
$history    = $proc->approvalHistory('TENDER', $id);
$pending    = $proc->pendingLevel('TENDER', $id, $t['_amount']);
$canApprove = ($t['status'] === 'PENDING_APPROVAL') && $proc->canApprove('TENDER', $id, $t['_amount']);

$cmp     = $proc->comparison($id);
$awards  = $proc->getAwards($id);
$award   = null;
while ($a = mysqli_fetch_assoc($awards)) { if ($a['status'] !== 'REJECTED') { $award = $a; break; } }

$page_title = 'Tender ' . $t['tender_no'];
include('procurement_head.php');

/** Tabs keep the id and land back on the same view after an action. */
function prTab($id, $key, $current, $label)
{
    $active = ($key === $current) ? ' active' : '';
    echo '<li class="nav-item"><a class="nav-link' . $active . '" href="tender_details.php?id='
       . (int) $id . '&tab=' . $key . '">' . $label . '</a></li>';
}
?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h4 class="pr-num"><?= pr_e($t['tender_no']) ?> <?= pr_badge($t['status']) ?></h4>
                        <p class="mb-0"><?= pr_e($t['title']) ?></p>
                    </div>
                    <div class="col-md-4 text-right">
                        <a href="tender_list.php" class="btn btn-sm btn-light">&larr; All Tenders</a>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-2"><small class="text-muted">Type</small><br>
                        <?= pr_e(str_replace('_', ' ', $t['tender_type'])) ?></div>
                    <div class="col-md-2"><small class="text-muted">Published</small><br>
                        <?= $t['published_date'] ? date('d.m.Y', strtotime($t['published_date'])) : '&mdash;' ?></div>
                    <div class="col-md-2"><small class="text-muted">Closing</small><br>
                        <?= $t['closing_date'] ? date('d.m.Y', strtotime($t['closing_date'])) : '&mdash;' ?></div>
                    <div class="col-md-2"><small class="text-muted">EMD</small><br>
                        <?= pr_money($t['emd_amount']) ?></div>
                    <div class="col-md-2"><small class="text-muted">Estimated</small><br>
                        <b><?= pr_money($t['estimated_value']) ?></b></div>
                    <div class="col-md-2"><small class="text-muted">Bids Received</small><br>
                        <b><?= count($cmp['bids']) ?></b></div>
                </div>

                <hr>
                <div class="row">
                    <div class="col-md-8">
                        <?php foreach ($levels as $lv) {
                            $done = null;
                            foreach ($history as $h) { if ((int) $h['level'] === (int) $lv['level']) { $done = $h; break; } } ?>
                            <span class="mr-3">
                                <small class="text-muted">L<?= (int) $lv['level'] ?> <?= pr_e($lv['approver_role']) ?>:</small>
                                <?php if ($done) { ?>
                                    <span class="badge badge-<?= $done['action'] === 'APPROVED' ? 'success' : 'danger' ?>">
                                        <?= pr_e($done['action']) ?></span>
                                    <small class="text-muted"><?= pr_e($done['acted_by']) ?></small>
                                <?php } else { ?>
                                    <span class="badge badge-light">pending</span>
                                <?php } ?>
                            </span>
                        <?php } ?>
                    </div>
                    <div class="col-md-4 text-right">
                        <?php if ($t['status'] === 'DRAFT') { ?>
                            <form action="controller/processProcurement.php" method="POST" style="display:inline">
                                <input type="hidden" name="action" value="submit_tender">
                                <input type="hidden" name="id" value="<?= $id ?>">
                                <button class="btn btn-sm btn-primary">Submit for Approval</button>
                            </form>
                        <?php } ?>
                        <?php if ($canApprove) { ?>
                            <form action="controller/processProcurement.php" method="POST" style="display:inline">
                                <input type="hidden" name="entity_type" value="TENDER">
                                <input type="hidden" name="entity_id" value="<?= $id ?>">
                                <input type="hidden" name="level" value="<?= (int) $pending['level'] ?>">
                                <button name="action" value="approve" class="btn btn-sm btn-success">
                                    Approve L<?= (int) $pending['level'] ?></button>
                                <button name="action" value="reject" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Reject this tender?');">Reject</button>
                            </form>
                        <?php } ?>
                        <?php if ($t['status'] === 'PUBLISHED') { ?>
                            <form action="controller/processProcurement.php" method="POST" style="display:inline"
                                  onsubmit="return confirm('Close bidding? No further bids can be recorded.');">
                                <input type="hidden" name="action" value="close_tender">
                                <input type="hidden" name="id" value="<?= $id ?>">
                                <button class="btn btn-sm btn-warning">Close Bidding</button>
                            </form>
                        <?php } ?>
                    </div>
                </div>
                <?php if ($t['status'] === 'CANCELLED' && $t['cancel_reason']) { ?>
                    <div class="alert alert-danger mt-3 mb-0"><b>Cancelled:</b> <?= pr_e($t['cancel_reason']) ?></div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<ul class="nav nav-pills mb-3 justify-content-around">
    <?php prTab($id, 'items',   $tab, 'Items');
          prTab($id, 'bids',    $tab, 'Bids (' . count($cmp['bids']) . ')');
          prTab($id, 'compare', $tab, 'Comparison &amp; Award'); ?>
</ul>

<?php if ($tab === 'items') { ?>
<div class="row"><div class="col-md-12"><div class="card"><div class="card-body">
    <h5 class="ml-2">Tender Items</h5><hr>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="thead-dark"><tr>
                <th>#</th><th>Item</th><th class="num">Quantity</th>
                <th class="num">Est. Rate</th><th class="num">Est. Value</th><th>Specification</th>
            </tr></thead>
            <tbody>
                <?php $items = $proc->getTenderItems($id); $n = 1;
                while ($i = mysqli_fetch_assoc($items)) { ?>
                    <tr>
                        <td><?= $n++ ?></td>
                        <td><?= pr_e($i['item_name']) ?><br>
                            <small class="text-muted"><?= pr_e($i['item_code']) ?></small></td>
                        <td class="num"><?= rtrim(rtrim(number_format((float) $i['quantity'], 3), '0'), '.') ?>
                            <small class="text-muted"><?= pr_e($i['unit_name']) ?></small></td>
                        <td class="num"><?= pr_money($i['estimated_rate']) ?></td>
                        <td class="num"><?= pr_money((float) $i['quantity'] * (float) $i['estimated_rate']) ?></td>
                        <td><small><?= pr_e($i['specification']) ?></small></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <?php if ($t['description'] || $t['terms_conditions']) { ?>
        <div class="row mt-3">
            <div class="col-md-6"><small class="text-muted">Description</small><br><?= nl2br(pr_e($t['description'])) ?></div>
            <div class="col-md-6"><small class="text-muted">Terms &amp; Conditions</small><br><?= nl2br(pr_e($t['terms_conditions'])) ?></div>
        </div>
    <?php } ?>
</div></div></div></div>

<?php } elseif ($tab === 'bids') { ?>
<div class="row">
    <div class="col-md-12">
        <div class="card"><div class="card-body">
            <h5 class="ml-2">Bids Received</h5><hr>
            <div class="table-responsive">
                <table class="table table-bordered text-center">
                    <thead class="thead-dark"><tr>
                        <th>Supplier</th><th>Ref</th><th>Date</th><th class="num">Total</th>
                        <th>Delivery</th><th>EMD</th><th>Technical</th>
                    </tr></thead>
                    <tbody>
                        <?php $bids = $proc->getBids($id); $any = false;
                        while ($b = mysqli_fetch_assoc($bids)) { $any = true; ?>
                            <tr>
                                <td class="text-left"><?= pr_e($b['supplier_name']) ?></td>
                                <td class="pr-num"><?= pr_e($b['bid_reference']) ?></td>
                                <td><?= $b['bid_date'] ? date('d.m.Y', strtotime($b['bid_date'])) : '' ?></td>
                                <td class="num"><b><?= pr_money($b['total_amount']) ?></b></td>
                                <td><?= (int) $b['delivery_days'] ?> days</td>
                                <td><?= ((int) $b['emd_received'] === 1) ? 'Yes' : 'No' ?></td>
                                <td><span class="badge badge-<?= $b['technical_status'] === 'Qualified' ? 'success'
                                        : ($b['technical_status'] === 'Disqualified' ? 'danger' : 'secondary') ?>">
                                        <?= pr_e($b['technical_status']) ?></span></td>
                            </tr>
                        <?php }
                        if (!$any) { ?>
                            <tr><td colspan="7" class="text-muted">No bids recorded yet.</td></tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div></div>
    </div>
</div>

<?php if (in_array($t['status'], array('PUBLISHED', 'UNDER_EVALUATION'), true)) { ?>
<div class="row"><div class="col-md-12"><div class="card"><div class="card-body">
    <h5 class="ml-2">Record a Bid</h5>
    <p class="text-muted ml-2 mb-2"><small>
        Re-entering a supplier replaces their previous bid, so a revised quotation does not
        create a second one.
    </small></p>
    <hr>
    <form action="controller/processProcurement.php" method="POST">
        <input type="hidden" name="action" value="save_bid">
        <input type="hidden" name="tender_id" value="<?= $id ?>">
        <div class="row">
            <div class="col-md-3"><div class="form-group">
                <label>Supplier <span class="text-danger">*</span></label>
                <select name="supplier_id" class="form-control" required>
                    <option value="">Select supplier</option>
                    <?php $sup = $proc->storeSuppliers();
                    while ($s = mysqli_fetch_assoc($sup)) { ?>
                        <option value="<?= (int) $s['id'] ?>"><?= pr_e($s['supplier_name']) ?></option>
                    <?php } ?>
                </select>
            </div></div>
            <div class="col-md-2"><div class="form-group">
                <label>Quotation Ref</label>
                <input type="text" class="form-control" name="bid_reference">
            </div></div>
            <div class="col-md-2"><div class="form-group">
                <label>Bid Date</label>
                <input type="date" class="form-control" name="bid_date" value="<?= date('Y-m-d') ?>">
            </div></div>
            <div class="col-md-2"><div class="form-group">
                <label>Delivery (days)</label>
                <input type="number" min="0" class="form-control" name="delivery_days" value="7">
            </div></div>
            <div class="col-md-3"><div class="form-group">
                <label>Payment Terms</label>
                <input type="text" class="form-control" name="payment_terms" placeholder="e.g. 30 days">
            </div></div>
        </div>
        <div class="row">
            <div class="col-md-3"><div class="form-group">
                <label>Warranty</label>
                <input type="text" class="form-control" name="warranty_terms">
            </div></div>
            <div class="col-md-2"><div class="form-group">
                <label>EMD Received</label>
                <select name="emd_received" class="form-control">
                    <option value="1">Yes</option><option value="0">No</option>
                </select>
            </div></div>
            <div class="col-md-3"><div class="form-group">
                <label>Technical Evaluation</label>
                <select name="technical_status" class="form-control">
                    <option value="Pending">Pending</option>
                    <option value="Qualified">Qualified</option>
                    <option value="Disqualified">Disqualified</option>
                </select>
            </div></div>
            <div class="col-md-4"><div class="form-group">
                <label>Notes</label>
                <input type="text" class="form-control" name="bid_note">
            </div></div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="thead-light"><tr>
                    <th>Item</th><th class="num">Qty</th><th style="width:12%">Rate</th>
                    <th style="width:10%">GST %</th><th style="width:16%">Brand</th><th style="width:20%">Remarks</th>
                </tr></thead>
                <tbody>
                    <?php $items = $proc->getTenderItems($id);
                    while ($i = mysqli_fetch_assoc($items)) { ?>
                        <tr>
                            <td><?= pr_e($i['item_name']) ?><br>
                                <small class="text-muted"><?= pr_e($i['item_code']) ?></small></td>
                            <td class="num"><?= rtrim(rtrim(number_format((float) $i['quantity'], 3), '0'), '.') ?></td>
                            <td><input type="number" step="0.01" min="0" class="form-control"
                                       name="bid_rate[<?= (int) $i['id'] ?>]"></td>
                            <td><input type="number" step="0.01" min="0" class="form-control"
                                       name="bid_gst[<?= (int) $i['id'] ?>]" value="0"></td>
                            <td><input type="text" class="form-control" name="bid_brand[<?= (int) $i['id'] ?>]"></td>
                            <td><input type="text" class="form-control" name="bid_remarks[<?= (int) $i['id'] ?>]"></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <button type="submit" class="btn btn-success btn-rounded float-right">
            <i class="icofont-check"></i> Save Bid
        </button>
    </form>
</div></div></div></div>
<?php } ?>

<?php } else { /* ---------------------------------------- comparison + award */ ?>
<div class="row"><div class="col-md-12"><div class="card"><div class="card-body">
    <h5 class="ml-2">Comparative Statement</h5>
    <p class="text-muted ml-2 mb-2"><small>
        Rates include GST. The lowest qualified rate on each line is highlighted; a technically
        disqualified bid is shown struck through and never ranks.
    </small></p>
    <hr>
    <?php if (!$cmp['bids']) { ?>
        <div class="alert alert-info mb-0">No bids to compare yet.</div>
    <?php } else { ?>
    <div class="table-responsive">
        <table class="table table-bordered text-center">
            <thead class="thead-dark">
                <tr>
                    <th class="text-left">Item</th><th class="num">Qty</th>
                    <?php foreach ($cmp['bids'] as $bid) { ?>
                        <th class="<?= $bid['technical_status'] === 'Disqualified' ? 'pr-dq' : '' ?>">
                            <?= pr_e($bid['supplier_name']) ?>
                            <?php if ((int) $cmp['l1_bid_id'] === (int) $bid['id']) { ?>
                                <br><span class="badge badge-success">L1</span>
                            <?php } ?>
                        </th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cmp['items'] as $line) { ?>
                    <tr>
                        <td class="text-left"><?= pr_e($line['item_name']) ?><br>
                            <small class="text-muted"><?= pr_e($line['item_code']) ?></small></td>
                        <td class="num"><?= rtrim(rtrim(number_format((float) $line['quantity'], 3), '0'), '.') ?></td>
                        <?php foreach ($cmp['bids'] as $bid) {
                            $q = isset($line['bids'][$bid['id']]) ? $line['bids'][$bid['id']] : null;
                            $dq = ($bid['technical_status'] === 'Disqualified');
                            $best = (!$dq && $q && (int) $line['lowest_bid_id'] === (int) $bid['id']); ?>
                            <td class="num <?= $dq ? 'pr-dq' : ($best ? 'pr-l1' : '') ?>">
                                <?= $q ? pr_money($q['net_rate']) : '<span class="text-muted">—</span>' ?>
                                <?php if ($q && $q['brand']) { ?><br><small class="text-muted"><?= pr_e($q['brand']) ?></small><?php } ?>
                            </td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2" class="text-right">Bid Total</th>
                    <?php foreach ($cmp['bids'] as $bid) { ?>
                        <th class="num <?= $bid['technical_status'] === 'Disqualified' ? 'pr-dq'
                                        : ((int) $cmp['l1_bid_id'] === (int) $bid['id'] ? 'pr-l1' : '') ?>">
                            <?= pr_money($bid['total_amount']) ?>
                        </th>
                    <?php } ?>
                </tr>
                <tr>
                    <th colspan="2" class="text-right">Technical</th>
                    <?php foreach ($cmp['bids'] as $bid) { ?>
                        <th>
                            <form action="controller/processProcurement.php" method="POST">
                                <input type="hidden" name="action" value="bid_technical">
                                <input type="hidden" name="tender_id" value="<?= $id ?>">
                                <input type="hidden" name="bid_id" value="<?= (int) $bid['id'] ?>">
                                <select name="technical_status" class="form-control form-control-sm"
                                        onchange="this.form.submit()">
                                    <?php foreach (array('Pending','Qualified','Disqualified') as $ts) { ?>
                                        <option value="<?= $ts ?>" <?= $bid['technical_status'] === $ts ? 'selected' : '' ?>>
                                            <?= $ts ?></option>
                                    <?php } ?>
                                </select>
                            </form>
                        </th>
                    <?php } ?>
                </tr>
            </tfoot>
        </table>
    </div>
    <?php } ?>
</div></div></div></div>

<?php if ($award) { ?>
<div class="row"><div class="col-md-12"><div class="card"><div class="card-body">
    <?php
    $aLevels  = $proc->applicableLevels('AWARD', $award['award_amount']);
    $aHistory = $proc->approvalHistory('AWARD', $award['id']);
    $aPending = $proc->pendingLevel('AWARD', $award['id'], $award['award_amount']);
    $aCan     = ($award['status'] === 'PENDING_APPROVAL')
                && $proc->canApprove('AWARD', $award['id'], $award['award_amount']);
    ?>
    <h5 class="ml-2">Award <span class="pr-num"><?= pr_e($award['award_no']) ?></span>
        <?= pr_badge($award['status']) ?></h5>
    <hr>
    <div class="row">
        <div class="col-md-3"><small class="text-muted">Supplier</small><br>
            <b><?= pr_e($award['supplier_name']) ?></b></div>
        <div class="col-md-2"><small class="text-muted">Amount</small><br>
            <b><?= pr_money($award['award_amount']) ?></b></div>
        <div class="col-md-2"><small class="text-muted">Lowest Bid?</small><br>
            <?= ((int) $award['is_lowest'] === 1)
                ? '<span class="badge badge-success">Yes</span>'
                : '<span class="badge badge-warning">No</span>' ?></div>
        <div class="col-md-5"><small class="text-muted">Reason for deviation</small><br>
            <?= pr_e($award['deviation_reason']) ?: '&mdash;' ?></div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-8">
            <?php foreach ($aLevels as $lv) {
                $done = null;
                foreach ($aHistory as $h) { if ((int) $h['level'] === (int) $lv['level']) { $done = $h; break; } } ?>
                <span class="mr-3">
                    <small class="text-muted">L<?= (int) $lv['level'] ?> <?= pr_e($lv['approver_role']) ?>:</small>
                    <?php if ($done) { ?>
                        <span class="badge badge-<?= $done['action'] === 'APPROVED' ? 'success' : 'danger' ?>">
                            <?= pr_e($done['action']) ?></span>
                        <small class="text-muted"><?= pr_e($done['acted_by']) ?></small>
                    <?php } else { ?><span class="badge badge-light">pending</span><?php } ?>
                </span>
            <?php } ?>
        </div>
        <div class="col-md-4 text-right">
            <?php if ($aCan) { ?>
                <form action="controller/processProcurement.php" method="POST" style="display:inline">
                    <input type="hidden" name="entity_type" value="AWARD">
                    <input type="hidden" name="entity_id" value="<?= (int) $award['id'] ?>">
                    <input type="hidden" name="level" value="<?= (int) $aPending['level'] ?>">
                    <button name="action" value="approve" class="btn btn-sm btn-success">
                        Approve L<?= (int) $aPending['level'] ?></button>
                    <button name="action" value="reject" class="btn btn-sm btn-outline-danger"
                            onclick="return confirm('Reject this award?');">Reject</button>
                </form>
            <?php } ?>
            <?php if ($award['status'] === 'APPROVED') { ?>
                <form action="controller/processProcurement.php" method="POST" style="display:inline"
                      onsubmit="return confirm('Generate the purchase order? It will appear in the store module.');">
                    <input type="hidden" name="action" value="generate_po">
                    <input type="hidden" name="award_id" value="<?= (int) $award['id'] ?>">
                    <input type="hidden" name="tender_id" value="<?= $id ?>">
                    <button class="btn btn-sm btn-primary"><i class="icofont-cart"></i> Generate Purchase Order</button>
                </form>
            <?php } ?>
            <?php if ($award['status'] === 'PO_GENERATED') { ?>
                <span class="badge badge-success">PO <?= pr_e($award['po_number']) ?></span><br>
                <small class="text-muted">Raised
                    <?= $award['po_generated_at'] ? date('d.m.Y H:i', strtotime($award['po_generated_at'])) : '' ?>
                    — now in the store module.</small>
            <?php } ?>
        </div>
    </div>
</div></div></div></div>

<?php } elseif ($t['status'] === 'UNDER_EVALUATION' && $cmp['bids']) { ?>
<div class="row"><div class="col-md-12"><div class="card"><div class="card-body">
    <h5 class="ml-2">Award the Tender</h5>
    <hr>
    <form action="controller/processProcurement.php" method="POST">
        <input type="hidden" name="action" value="create_award">
        <input type="hidden" name="tender_id" value="<?= $id ?>">
        <div class="row">
            <div class="col-md-5"><div class="form-group">
                <label>Winning Bid <span class="text-danger">*</span></label>
                <select name="bid_id" class="form-control" id="awardBid" required>
                    <option value="">Select a bid</option>
                    <?php foreach ($cmp['bids'] as $bid) {
                        if ($bid['technical_status'] === 'Disqualified') { continue; } ?>
                        <option value="<?= (int) $bid['id'] ?>"
                                data-lowest="<?= ((int) $cmp['l1_bid_id'] === (int) $bid['id']) ? '1' : '0' ?>">
                            <?= pr_e($bid['supplier_name']) ?> — <?= pr_money($bid['total_amount']) ?>
                            <?= ((int) $cmp['l1_bid_id'] === (int) $bid['id']) ? ' (L1)' : '' ?>
                        </option>
                    <?php } ?>
                </select>
                <small class="form-text text-muted">Disqualified bids are not listed.</small>
            </div></div>
            <div class="col-md-7"><div class="form-group">
                <label>Reason, if not the lowest bid</label>
                <input type="text" class="form-control" name="deviation_reason" id="devReason"
                       placeholder="Required when awarding above L1">
                <small class="form-text text-muted">
                    Awarding anything other than the lowest qualified bid has to be justified on record.
                </small>
            </div></div>
        </div>
        <button type="submit" class="btn btn-success btn-rounded float-right">
            <i class="icofont-check-circled"></i> Raise Award for Approval
        </button>
    </form>
</div></div></div></div>
<script>
    // Make the reason mandatory the moment a non-L1 bid is chosen.
    $('#awardBid').on('change', function () {
        var lowest = $(this).find(':selected').data('lowest') === 1
                  || $(this).find(':selected').data('lowest') === '1';
        $('#devReason').prop('required', !lowest)
                       .attr('placeholder', lowest ? 'Not required — this is the lowest bid'
                                                   : 'Required — this is not the lowest bid');
    });
</script>
<?php } ?>

<?php } ?>

<?php include('procurement_foot.php'); ?>
