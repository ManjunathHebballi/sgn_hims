<?php
/**
 * Tender register, plus a standalone tender form for purchases raised without
 * a requisition (an emergency buy, or a rate contract renewal).
 */
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set('Asia/Kolkata');
include('model/procurementClass.php');

$proc   = new procurement;
$filter = isset($_GET['status']) ? $_GET['status'] : '';
$list   = $proc->getTenders($filter);
$items  = $proc->storeItems();

$itemOptions = '';
while ($i = mysqli_fetch_assoc($items)) {
    $itemOptions .= '<option value="' . (int) $i['id'] . '">'
                  . htmlspecialchars($i['item_code'] . ' — ' . $i['item_name']
                    . ($i['unit_name'] ? ' (' . $i['unit_name'] . ')' : ''), ENT_QUOTES, 'UTF-8')
                  . '</option>';
}

$page_title = 'Tenders';
include('procurement_head.php');
?>

<div class="row mb-3 ml-1">
    <h4 class="text-uppercase">Tenders</h4>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8"><h5 class="ml-2">Tender Register</h5></div>
                    <div class="col-md-4 text-right">
                        <form method="GET" class="form-inline float-right">
                            <select name="status" class="form-control form-control-sm mr-2" onchange="this.form.submit()">
                                <option value="">All statuses</option>
                                <?php foreach (array('DRAFT','PENDING_APPROVAL','PUBLISHED','UNDER_EVALUATION','AWARDED','CANCELLED') as $s) { ?>
                                    <option value="<?= $s ?>" <?= $filter === $s ? 'selected' : '' ?>>
                                        <?= str_replace('_', ' ', $s) ?>
                                    </option>
                                <?php } ?>
                            </select>
                            <button type="button" class="btn btn-sm btn-success" data-toggle="collapse"
                                    data-target="#newTender">
                                <i class="icofont-plus"></i> New Tender
                            </button>
                        </form>
                    </div>
                </div>
                <hr>
                <div class="table-responsive">
                    <table class="table table-bordered text-center pr-table">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th><th>Tender No.</th><th>Title</th><th>Type</th>
                                <th>From PR</th><th>Items</th><th>Bids</th>
                                <th class="num">Estimated</th><th>Closing</th><th>Status</th><th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $n = 1;
                            while ($t = mysqli_fetch_assoc($list)) { ?>
                                <tr>
                                    <td><?= $n++ ?></td>
                                    <td class="pr-num"><?= pr_e($t['tender_no']) ?></td>
                                    <td class="text-left"><?= pr_e($t['title']) ?></td>
                                    <td><?= pr_e(str_replace('_', ' ', $t['tender_type'])) ?></td>
                                    <td class="pr-num"><?= pr_e($t['requisition_no']) ?></td>
                                    <td><?= (int) $t['item_count'] ?></td>
                                    <td><?= (int) $t['bid_count'] ?></td>
                                    <td class="num"><?= pr_money($t['estimated_value']) ?></td>
                                    <td><?= $t['closing_date'] ? date('d.m.Y', strtotime($t['closing_date'])) : '&mdash;' ?></td>
                                    <td><?= pr_badge($t['status']) ?></td>
                                    <td><a href="tender_details.php?id=<?= (int) $t['id'] ?>"
                                           class="btn btn-sm btn-primary">Open</a></td>
                                </tr>
                            <?php }
                            if ($n === 1) { ?>
                                <tr><td colspan="11" class="text-muted">No tenders yet.</td></tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="collapse" id="newTender">
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h5 class="ml-2">New Tender</h5>
                <p class="text-muted ml-2 mb-2"><small>
                    For a purchase that already has an approved requisition, float it from the
                    requisition instead — that keeps the two linked.
                </small></p>
                <hr>
                <form action="controller/processProcurement.php" method="POST" id="tndForm">
                    <input type="hidden" name="action" value="create_tender">
                    <input type="hidden" name="requisition_id" value="0">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="title" required>
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
                                    <th style="width:20%">Specification</th><th style="width:6%"></th></tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><select name="item_id[]" class="form-control">
                                            <option value="">Select item</option><?= $itemOptions ?>
                                        </select></td>
                                    <td><input type="number" step="0.001" min="0" class="form-control pr-qty" name="quantity[]"></td>
                                    <td><input type="number" step="0.01" min="0" class="form-control pr-rate" name="rate[]"></td>
                                    <td class="num pr-value">0.00</td>
                                    <td><input type="text" class="form-control" name="specification[]"></td>
                                    <td><button type="button" class="btn btn-sm btn-outline-danger"
                                                onclick="prRemoveRow(this,'tndItems')">&times;</button></td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr><th colspan="3" class="text-right">Estimated Value</th>
                                    <th class="num"><span id="pr-total">0.00</span></th>
                                    <th colspan="2"><small id="pr-total-hint" class="text-danger" style="display:none">
                                        Above 50,000 — needs Admin approval.</small></th></tr>
                            </tfoot>
                        </table>
                    </div>
                    <button type="button" class="btn btn-sm btn-light" onclick="prAddRow('tndItems')">
                        <i class="icofont-plus"></i> Add Row
                    </button>

                    <button type="submit" class="btn btn-success btn-rounded float-right">
                        <i class="icofont-file-document"></i> Create Tender
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
</div>

<script>
    document.getElementById('tndForm').addEventListener('submit', function (e) {
        var ok = false;
        $('#tndItems tbody tr').each(function () {
            if ($(this).find('select').val() && parseFloat($(this).find('.pr-qty').val()) > 0) { ok = true; }
        });
        if (!ok) { e.preventDefault(); alert('Add at least one item with a quantity.'); }
    });
</script>

<?php include('procurement_foot.php'); ?>
