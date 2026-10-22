<?php
/**
 * Stage 4 — Component separation.
 *
 * A screened whole-blood unit is split into components. The children are
 * ordinary blood_units rows linked back by parent_unit_id, so stock and issue
 * work on them unchanged and a reaction can always be traced to the donation.
 * Each child's expiry runs from the original collection date.
 */
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set('Asia/Kolkata');
include('model/bloodBankClass.php');

$newBlood = new addBlood;

$eligible   = $newBlood->getUnitsForSeparation();
$components = $newBlood->getComponentTypes();
$history    = $newBlood->getSeparationHistory(100);

$page_title = 'Blood Component Separation';
include('blood_bank_head.php');

function bb_e($v) { return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8'); }
?>

<div class="row mb-3 ml-1">
    <h4 class="text-uppercase">Blood Component Separation</h4>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h5 class="ml-2">Separate a Unit</h5>
                <hr>
                <form action="controller/processBloodBank.php" method="POST" id="sepForm">
                    <input type="hidden" name="action" value="separate_components">

                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Whole Blood Unit <span class="text-danger">*</span></label>
                                <select name="parent_unit_id" class="form-control" required>
                                    <option value="">Select a screened whole-blood unit</option>
                                    <?php
                                    $any = false;
                                    while ($u = mysqli_fetch_assoc($eligible)) {
                                        $any = true; ?>
                                        <option value="<?= (int) $u['unit_id'] ?>">
                                            <?= bb_e($u['unit_barcode']) ?> &mdash;
                                            <?= bb_e($u['blood_group']) ?> &mdash;
                                            <?= bb_e($u['donor_name']) ?>
                                            (collected <?= date('d.m.Y', strtotime($u['collection_date'])) ?>)
                                        </option>
                                    <?php } ?>
                                </select>
                                <?php if (!$any) { ?>
                                    <small class="form-text text-danger">
                                        No units available. A unit must pass screening before it can be separated.
                                    </small>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Components to Prepare <span class="text-danger">*</span></label>
                                <?php foreach ($components as $type => $ref) {
                                    if ($type === 'Whole_Blood') { continue; } ?>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name="components[]"
                                               id="cmp_<?= bb_e($type) ?>" value="<?= bb_e($type) ?>">
                                        <label class="custom-control-label" for="cmp_<?= bb_e($type) ?>">
                                            <?= bb_e(str_replace('_', ' ', $type)) ?>
                                            <small class="text-muted">
                                                (<?= (int) $ref['shelf_life_days'] ?> days, <?= bb_e($ref['storage_temp']) ?>)
                                            </small>
                                        </label>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Method</label>
                                <select name="separation_method" class="form-control">
                                    <option value="Centrifugation">Centrifugation</option>
                                    <option value="Apheresis">Apheresis</option>
                                    <option value="Manual">Manual</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Remarks</label>
                                <input type="text" class="form-control" name="remarks" placeholder="Optional">
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info py-2">
                        <i class="icofont-info-circle"></i>
                        The parent unit leaves inventory once separated. Each component's expiry is
                        calculated from the <b>original collection date</b>, not today.
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-success btn-rounded float-right" <?= $any ? '' : 'disabled' ?>>
                                <i class="icofont-test-tube"></i> Separate Components
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
                <h5 class="ml-2">Separation History</h5>
                <hr>
                <div class="table-responsive">
                    <table class="table table-bordered text-center bb-table">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Parent Unit</th>
                                <th>Group</th>
                                <th>Components Made</th>
                                <th>Count</th>
                                <th>Method</th>
                                <th>Separated By</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            while ($h = mysqli_fetch_assoc($history)) { ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td class="bb-barcode"><?= bb_e($h['parent_barcode']) ?></td>
                                    <td><b><?= bb_e($h['blood_group']) ?></b></td>
                                    <td class="text-left"><?= bb_e(str_replace('_', ' ', $h['components_made'])) ?></td>
                                    <td><?= (int) $h['component_count'] ?></td>
                                    <td><?= bb_e($h['separation_method']) ?></td>
                                    <td><?= bb_e($h['separated_by']) ?></td>
                                    <td><?= $h['separated_date'] ? date('d.m.Y H:i', strtotime($h['separated_date'])) : '' ?></td>
                                </tr>
                            <?php }
                            if ($i === 1) { ?>
                                <tr><td colspan="8" class="text-muted">No separations recorded yet.</td></tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // At least one component must be ticked — the browser cannot enforce that
    // on a checkbox group on its own.
    document.getElementById('sepForm').addEventListener('submit', function (e) {
        if (this.querySelectorAll('input[name="components[]"]:checked').length === 0) {
            e.preventDefault();
            alert('Select at least one component to prepare.');
        }
    });
</script>

<?php include('blood_bank_foot.php'); ?>
