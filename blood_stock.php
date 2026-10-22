<?php
/**
 * Blood stock — what is on the shelf, what is close to expiry, and what has
 * left inventory. Opening this page also expires anything past its date, so a
 * time-expired bag can never be crossmatched.
 */
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set('Asia/Kolkata');
include('model/bloodBankClass.php');

$newBlood = new addBlood;

// Sweep expiries before reading any figure, so the numbers below are true.
$expired_now = $newBlood->expireStaleUnits();

$summary = $newBlood->getStockSummary();
$matrix  = $newBlood->getStockMatrix();

$f_group     = isset($_GET['group'])     ? $_GET['group']     : '';
$f_component = isset($_GET['component']) ? $_GET['component'] : '';
$f_status    = isset($_GET['status'])    ? $_GET['status']    : 'Released_To_Inventory';
$units       = $newBlood->getStockUnits($f_group, $f_component, $f_status);

$componentRef = $newBlood->getComponentTypes();
$groups = array('A+','A-','B+','B-','AB+','AB-','O+','O-');

$page_title = 'Blood Stock';
include('blood_bank_head.php');

function bb_e($v) { return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8'); }

// Build a group x component grid so empty cells are visible, not just absent.
$grid = array();
while ($m = mysqli_fetch_assoc($matrix)) {
    $grid[$m['blood_group']][$m['component_type']] = $m;
}
?>

<div class="row mb-3 ml-1">
    <h4 class="text-uppercase">Blood Stock</h4>
</div>

<?php if ($expired_now > 0) { ?>
    <div class="alert alert-warning">
        <i class="icofont-warning"></i>
        <?= (int) $expired_now ?> unit(s) passed their expiry date and were moved to <b>Expired</b>.
    </div>
<?php } ?>

<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card"><div class="card-body text-center bb-stat">
            <p class="text-muted mb-1">Available for Issue</p>
            <h2 class="text-success"><?= $summary['available'] ?></h2>
        </div></div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card"><div class="card-body text-center bb-stat">
            <p class="text-muted mb-1">Awaiting Screening</p>
            <h2 class="text-warning"><?= $summary['pending_testing'] ?></h2>
            <p class="text-muted"><small>Not usable until screened</small></p>
        </div></div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card"><div class="card-body text-center bb-stat">
            <p class="text-muted mb-1">Expiring in 7 Days</p>
            <h2 class="text-danger"><?= $summary['expiring_soon'] ?></h2>
        </div></div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card"><div class="card-body text-center bb-stat">
            <p class="text-muted mb-1">Reserved / Issued</p>
            <h2 class="text-primary"><?= $summary['reserved'] + $summary['issued'] ?></h2>
            <p class="text-muted"><small>Reserved <?= $summary['reserved'] ?> &nbsp;|&nbsp; Issued <?= $summary['issued'] ?></small></p>
        </div></div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h5 class="ml-2">Available Units by Group and Component</h5>
                <p class="text-muted ml-2 mb-2"><small>Counts exclude anything unscreened, reserved, issued or expired.</small></p>
                <hr>
                <div class="table-responsive">
                    <table class="table table-bordered text-center">
                        <thead class="thead-dark">
                            <tr>
                                <th>Group</th>
                                <?php foreach ($componentRef as $type => $ref) { ?>
                                    <th><?= bb_e(str_replace('_', ' ', $type)) ?></th>
                                <?php } ?>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($groups as $g) {
                                $rowTotal = 0; ?>
                                <tr>
                                    <td><b class="text-danger"><?= $g ?></b></td>
                                    <?php foreach ($componentRef as $type => $ref) {
                                        $cell = isset($grid[$g][$type]) ? $grid[$g][$type] : null;
                                        $n = $cell ? (int) $cell['units'] : 0;
                                        $soon = $cell ? (int) $cell['expiring_soon'] : 0;
                                        $rowTotal += $n; ?>
                                        <td class="<?= $soon > 0 ? 'bb-expiring' : '' ?>">
                                            <?= $n > 0 ? '<b>' . $n . '</b>' : '<span class="text-muted">0</span>' ?>
                                            <?php if ($soon > 0) { ?>
                                                <br><small class="text-danger"><?= $soon ?> expiring</small>
                                            <?php } ?>
                                        </td>
                                    <?php } ?>
                                    <td><b><?= $rowTotal ?></b></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="blood_stock.php" class="mb-3">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Blood Group</label>
                                <select name="group" class="form-control">
                                    <option value="">All Groups</option>
                                    <?php foreach ($groups as $g) { ?>
                                        <option value="<?= $g ?>" <?= $f_group === $g ? 'selected' : '' ?>><?= $g ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Component</label>
                                <select name="component" class="form-control">
                                    <option value="">All Components</option>
                                    <?php foreach ($componentRef as $type => $ref) { ?>
                                        <option value="<?= bb_e($type) ?>" <?= $f_component === $type ? 'selected' : '' ?>>
                                            <?= bb_e(str_replace('_', ' ', $type)) ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="">All Statuses</option>
                                    <?php foreach (array('Released_To_Inventory','Collected_Unscreened','Under_Testing',
                                                         'Reserved','Issued','Transfused','Testing_Failed',
                                                         'Separated','Discarded','Expired') as $s) { ?>
                                        <option value="<?= $s ?>" <?= $f_status === $s ? 'selected' : '' ?>>
                                            <?= str_replace('_', ' ', $s) ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <div class="form-group w-100">
                                <button type="submit" class="btn btn-success btn-rounded">
                                    <i class="icofont-search-1"></i> Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
                <hr>
                <div class="table-responsive">
                    <table class="table table-bordered text-center bb-table">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Unit Barcode</th>
                                <th>Group</th>
                                <th>Component</th>
                                <th>Volume</th>
                                <th>Donor</th>
                                <th>Collected</th>
                                <th>Expiry</th>
                                <th>Days Left</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            while ($u = $units->fetch_assoc()) {
                                $days = (int) $u['days_to_expiry'];
                                $cls = $days < 0 ? 'bb-expired' : ($days <= 7 ? 'bb-expiring' : ''); ?>
                                <tr class="<?= $cls ?>">
                                    <td><?= $i++ ?></td>
                                    <td class="bb-barcode"><?= bb_e($u['unit_barcode']) ?></td>
                                    <td><b><?= bb_e($u['blood_group']) ?></b></td>
                                    <td><?= bb_e(str_replace('_', ' ', $u['component_type'])) ?></td>
                                    <td><?= (int) $u['volume_ml'] ?> ml</td>
                                    <td class="text-left"><?= bb_e($u['donor_name']) ?></td>
                                    <td><?= $u['collection_date'] ? date('d.m.Y', strtotime($u['collection_date'])) : '' ?></td>
                                    <td><?= $u['expiry_date'] ? date('d.m.Y', strtotime($u['expiry_date'])) : '' ?></td>
                                    <td><?= $days < 0 ? '<span class="text-danger">Expired</span>' : $days ?></td>
                                    <td><span class="badge badge-light"><?= bb_e(str_replace('_', ' ', $u['unit_status'])) ?></span></td>
                                    <td>
                                        <?php if (in_array($u['unit_status'], array('Released_To_Inventory', 'Quarantined'), true)) { ?>
                                            <form action="controller/processBloodBank.php" method="POST"
                                                  onsubmit="return confirm('Discard unit <?= bb_e($u['unit_barcode']) ?>? This cannot be undone.');"
                                                  style="display:inline">
                                                <input type="hidden" name="action" value="discard_unit">
                                                <input type="hidden" name="unit_id" value="<?= (int) $u['unit_id'] ?>">
                                                <input type="hidden" name="discard_reason" value="Discarded from stock screen">
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Discard</button>
                                            </form>
                                        <?php } else { ?>
                                            <span class="text-muted">&mdash;</span>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php }
                            if ($i === 1) { ?>
                                <tr><td colspan="11" class="text-muted">No units match these filters.</td></tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('blood_bank_foot.php'); ?>
