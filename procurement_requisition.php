<?php
/**
 * Raise a purchase requisition, and list existing ones.
 * Items come from the store item master (read-only); nothing here writes to the
 * store module.
 */
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set('Asia/Kolkata');
include('model/procurementClass.php');

$proc = new procurement;

$filter = isset($_GET['status']) ? $_GET['status'] : '';
$list   = $proc->getRequisitions($filter);

$items  = $proc->storeItems();
$stores = $proc->subStores();

$itemOptions = '';
while ($i = mysqli_fetch_assoc($items)) {
    $itemOptions .= '<option value="' . (int) $i['id'] . '">'
                  . htmlspecialchars($i['item_code'] . ' — ' . $i['item_name']
                    . ($i['unit_name'] ? ' (' . $i['unit_name'] . ')' : ''), ENT_QUOTES, 'UTF-8')
                  . '</option>';
}

$page_title = 'Purchase Requisition';
include('procurement_head.php');
?>

<div class="row mb-3 ml-1">
    <h4 class="text-uppercase">Purchase Requisition</h4>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h5 class="ml-2">New Requisition</h5>
                <hr>
                <form action="controller/processProcurement.php" method="POST" id="reqForm">
                    <input type="hidden" name="action" value="create_requisition">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Store <span class="text-danger">*</span></label>
                                <select name="store_id" class="form-control" required>
                                    <option value="">Select store</option>
                                    <?php while ($s = mysqli_fetch_assoc($stores)) { ?>
                                        <option value="<?= (int) $s['id'] ?>"><?= pr_e($s['store_name']) ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Department</label>
                                <input type="text" class="form-control text-uppercase" name="department"
                                       placeholder="Requesting department">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Required By</label>
                                <input type="date" class="form-control" name="required_by">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Priority</label>
                                <select name="priority" class="form-control">
                                    <option value="Normal">Normal</option>
                                    <option value="Urgent">Urgent</option>
                                    <option value="Emergency">Emergency</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Estimated Total</label>
                                <input type="text" class="form-control" id="pr-total-box" readonly
                                       value="0.00" style="font-weight:600">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Justification</label>
                                <textarea class="form-control" name="justification" rows="2"
                                          placeholder="Why is this purchase needed?"></textarea>
                            </div>
                        </div>
                    </div>

                    <h6 class="text-uppercase text-muted mt-2">Items</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="reqItems">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width:32%">Item</th>
                                    <th style="width:12%">Quantity</th>
                                    <th style="width:12%">Est. Rate</th>
                                    <th style="width:12%" class="num">Value</th>
                                    <th style="width:26%">Specification</th>
                                    <th style="width:6%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><select name="item_id[]" class="form-control">
                                            <option value="">Select item</option>
                                            <?= $itemOptions ?>
                                        </select></td>
                                    <td><input type="number" step="0.001" min="0" class="form-control pr-qty" name="quantity[]"></td>
                                    <td><input type="number" step="0.01" min="0" class="form-control pr-rate" name="rate[]"></td>
                                    <td class="num pr-value">0.00</td>
                                    <td><input type="text" class="form-control" name="specification[]" placeholder="Optional"></td>
                                    <td><button type="button" class="btn btn-sm btn-outline-danger"
                                                onclick="prRemoveRow(this,'reqItems')">&times;</button></td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-right">Estimated Total</th>
                                    <th class="num"><span id="pr-total">0.00</span></th>
                                    <th colspan="2">
                                        <small id="pr-total-hint" class="text-danger" style="display:none">
                                            Above 50,000 — this will need an additional Admin approval.
                                        </small>
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <button type="button" class="btn btn-sm btn-light" onclick="prAddRow('reqItems')">
                        <i class="icofont-plus"></i> Add Row
                    </button>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-success btn-rounded float-right ml-2"
                                    name="submit_now" value="1">
                                <i class="icofont-paper-plane"></i> Save &amp; Submit for Approval
                            </button>
                            <button type="submit" class="btn btn-light btn-rounded float-right">Save as Draft</button>
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
                <div class="row">
                    <div class="col-md-8"><h5 class="ml-2">Requisitions</h5></div>
                    <div class="col-md-4 text-right">
                        <form method="GET" class="form-inline float-right">
                            <select name="status" class="form-control form-control-sm mr-2"
                                    onchange="this.form.submit()">
                                <option value="">All statuses</option>
                                <?php foreach (array('DRAFT','SUBMITTED','APPROVED','REJECTED','TENDERED','CLOSED') as $s) { ?>
                                    <option value="<?= $s ?>" <?= $filter === $s ? 'selected' : '' ?>><?= $s ?></option>
                                <?php } ?>
                            </select>
                        </form>
                    </div>
                </div>
                <hr>
                <div class="table-responsive">
                    <table class="table table-bordered text-center pr-table">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Requisition No.</th>
                                <th>Store / Dept</th>
                                <th>Priority</th>
                                <th>Items</th>
                                <th class="num">Estimated</th>
                                <th>Status</th>
                                <th>Raised By</th>
                                <th>Date</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $n = 1;
                            while ($r = mysqli_fetch_assoc($list)) { ?>
                                <tr>
                                    <td><?= $n++ ?></td>
                                    <td class="pr-num"><?= pr_e($r['requisition_no']) ?></td>
                                    <td class="text-left"><?= pr_e($r['store_name']) ?><br>
                                        <small class="text-muted"><?= pr_e($r['department']) ?></small></td>
                                    <td><?= pr_e($r['priority']) ?></td>
                                    <td><?= (int) $r['item_count'] ?></td>
                                    <td class="num"><?= pr_money($r['estimated_amount']) ?></td>
                                    <td><?= pr_badge($r['status']) ?></td>
                                    <td><?= pr_e($r['created_by']) ?></td>
                                    <td><?= $r['created_at'] ? date('d.m.Y', strtotime($r['created_at'])) : '' ?></td>
                                    <td><a href="requisition_details.php?id=<?= (int) $r['id'] ?>"
                                           class="btn btn-sm btn-primary">Open</a></td>
                                </tr>
                            <?php }
                            if ($n === 1) { ?>
                                <tr><td colspan="10" class="text-muted">No requisitions yet.</td></tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Mirror the running total into the header box.
    $(document).on('input', '.pr-qty, .pr-rate', function () {
        $('#pr-total-box').val($('#pr-total').text());
    });
    // At least one complete item row before the form may be submitted.
    document.getElementById('reqForm').addEventListener('submit', function (e) {
        var ok = false;
        $('#reqItems tbody tr').each(function () {
            if ($(this).find('select').val() && parseFloat($(this).find('.pr-qty').val()) > 0) { ok = true; }
        });
        if (!ok) { e.preventDefault(); alert('Add at least one item with a quantity.'); }
    });
</script>

<?php include('procurement_foot.php'); ?>
