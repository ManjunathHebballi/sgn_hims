<?php
/**
 * Stage 3 — TTI screening.
 *
 * This is the gate the whole flow hangs on: a collected unit stays unusable
 * (is_usable = 0) until all five mandatory markers come back Non-Reactive and
 * the re-grouped ABO/Rh matches the collection record. Passing releases the
 * unit into inventory; failing discards it.
 *
 * ?unit_id=N opens the form for one unit; without it, the pending queue.
 */
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set('Asia/Kolkata');
include('model/bloodBankClass.php');

$newBlood = new addBlood;

$unit_id = isset($_GET['unit_id']) ? (int) $_GET['unit_id'] : 0;
$unit    = $unit_id > 0 ? $newBlood->getUnit($unit_id) : null;
$pending = $newBlood->getUnitsPendingTesting();

$page_title = 'Blood Screening';
include('blood_bank_head.php');

function bb_e($v) { return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8'); }

/** The five mandatory markers, in the order the panel is usually reported. */
$markers = array(
    'hiv'      => 'HIV 1 &amp; 2',
    'hbsag'    => 'HBsAg (Hepatitis B)',
    'hcv'      => 'HCV (Hepatitis C)',
    'syphilis' => 'Syphilis (VDRL / RPR)',
    'malaria'  => 'Malaria Antigen',
);
?>

<div class="row mb-3 ml-1">
    <h4 class="text-uppercase">Blood Screening &mdash; TTI Panel</h4>
</div>

<?php if ($unit) { ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h5 class="ml-2">
                    Unit <span class="bb-barcode"><?= bb_e($unit['unit_barcode']) ?></span>
                    &nbsp;<span class="badge badge-danger"><?= bb_e($unit['blood_group']) ?></span>
                </h5>
                <hr>
                <div class="row mb-3">
                    <div class="col-md-3"><small class="text-muted">Donor</small><br>
                        <b><?= bb_e($unit['donor_name']) ?></b><br>
                        <small><?= bb_e($unit['donor_unique_id']) ?></small></div>
                    <div class="col-md-3"><small class="text-muted">Collection</small><br>
                        <b><?= bb_e($unit['collection_number']) ?></b><br>
                        <small><?= $unit['collection_date'] ? date('d.m.Y', strtotime($unit['collection_date'])) : '' ?></small></div>
                    <div class="col-md-3"><small class="text-muted">Volume / Expiry</small><br>
                        <b><?= bb_e($unit['volume_ml']) ?> ml</b><br>
                        <small><?= $unit['expiry_date'] ? date('d.m.Y', strtotime($unit['expiry_date'])) : '' ?></small></div>
                    <div class="col-md-3"><small class="text-muted">Current Status</small><br>
                        <b><?= bb_e(str_replace('_', ' ', $unit['unit_status'])) ?></b></div>
                </div>

                <form action="controller/processBloodBank.php" method="POST">
                    <input type="hidden" name="action" value="save_testing">
                    <input type="hidden" name="unit_id" value="<?= (int) $unit['unit_id'] ?>">

                    <h6 class="text-uppercase text-muted">Mandatory markers</h6>
                    <div class="row">
                        <?php foreach ($markers as $key => $label) {
                            $current = isset($unit[$key . '_result']) ? $unit[$key . '_result'] : 'Pending'; ?>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><?= $label ?> <span class="text-danger">*</span></label>
                                    <select name="<?= $key ?>" class="form-control" required>
                                        <?php foreach (array('Pending', 'Non_Reactive', 'Reactive') as $opt) { ?>
                                            <option value="<?= $opt ?>" <?= $current === $opt ? 'selected' : '' ?>>
                                                <?= str_replace('_', '-', $opt) ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        <?php } ?>
                    </div>

                    <h6 class="text-uppercase text-muted mt-2">Blood grouping (re-confirmation)</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>ABO Group Confirmed</label>
                                <select name="abo_group_confirmed" class="form-control">
                                    <option value="">Not done yet</option>
                                    <?php foreach (array('A+','A-','B+','B-','AB+','AB-','O+','O-') as $g) { ?>
                                        <option value="<?= $g ?>" <?= ($unit['abo_group_confirmed'] ?? '') === $g ? 'selected' : '' ?>><?= $g ?></option>
                                    <?php } ?>
                                </select>
                                <small class="form-text text-muted">
                                    Collection recorded <b><?= bb_e($unit['blood_group']) ?></b>. A mismatch fails the unit.
                                </small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Rh Factor Confirmed</label>
                                <select name="rh_factor_confirmed" class="form-control">
                                    <option value="">Not done yet</option>
                                    <?php foreach (array('Positive', 'Negative') as $rh) { ?>
                                        <option value="<?= $rh ?>" <?= ($unit['rh_factor_confirmed'] ?? '') === $rh ? 'selected' : '' ?>><?= $rh ?></option>
                                    <?php } ?>
                                </select>
                                <small class="form-text text-muted">Collection recorded <b><?= bb_e($unit['rh_factor']) ?></b>.</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Test Method</label>
                                <select name="test_method" class="form-control">
                                    <?php foreach (array('ELISA', 'Rapid', 'CLIA', 'NAT') as $m) { ?>
                                        <option value="<?= $m ?>" <?= ($unit['test_method'] ?? 'ELISA') === $m ? 'selected' : '' ?>><?= $m ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Kit Lot Number</label>
                                <input type="text" class="form-control" name="kit_lot_number"
                                       value="<?= bb_e($unit['kit_lot_number'] ?? '') ?>" placeholder="Kit / lot reference">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Remarks</label>
                                <input type="text" class="form-control" name="remarks"
                                       value="<?= bb_e($unit['remarks'] ?? '') ?>" placeholder="Optional">
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-warning py-2">
                        <i class="icofont-warning"></i>
                        A unit is released to inventory only when <b>all five markers are Non-Reactive</b>
                        and the confirmed group matches the collection record. Any reactive marker
                        discards the unit permanently.
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <a href="blood_screening.php" class="btn btn-light btn-rounded">Cancel</a>
                            <button type="submit" class="btn btn-success btn-rounded float-right">
                                <i class="icofont-check-circled"></i> Save Screening Result
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h5 class="ml-2">Units Awaiting Screening</h5>
                <p class="text-muted ml-2 mb-2"><small>Oldest collection first — units are screened in the order they were drawn.</small></p>
                <hr>
                <div class="table-responsive">
                    <table class="table table-bordered text-center bb-table">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Unit Barcode</th>
                                <th>Collection No.</th>
                                <th>Donor</th>
                                <th>Group</th>
                                <th>Collected</th>
                                <th>Expiry</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            while ($row = mysqli_fetch_assoc($pending)) { ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td class="bb-barcode"><?= bb_e($row['unit_barcode']) ?></td>
                                    <td><?= bb_e($row['collection_number']) ?></td>
                                    <td class="text-left">
                                        <?= bb_e($row['donor_name']) ?><br>
                                        <small class="text-muted"><?= bb_e($row['donor_unique_id']) ?></small>
                                    </td>
                                    <td><b><?= bb_e($row['blood_group']) ?></b></td>
                                    <td><?= $row['collection_date'] ? date('d.m.Y', strtotime($row['collection_date'])) : '' ?></td>
                                    <td><?= $row['expiry_date'] ? date('d.m.Y', strtotime($row['expiry_date'])) : '' ?></td>
                                    <td><span class="badge badge-<?= $row['unit_status'] === 'Under_Testing' ? 'info' : 'secondary' ?>">
                                        <?= bb_e(str_replace('_', ' ', $row['unit_status'])) ?></span></td>
                                    <td>
                                        <a href="blood_screening.php?unit_id=<?= (int) $row['unit_id'] ?>"
                                           class="btn btn-sm btn-primary">Screen</a>
                                    </td>
                                </tr>
                            <?php }
                            if ($i === 1) { ?>
                                <tr><td colspan="9" class="text-muted">Nothing awaiting screening.</td></tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('blood_bank_foot.php'); ?>
