<?php
/**
 * Blood Collection register — every collection event with its unit and where
 * that unit has reached in the lifecycle. The "Add Collection" action starts a
 * new collection (add_blood_collection.php); "Screen" carries a freshly
 * collected unit into stage 3.
 */
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set('Asia/Kolkata');
include('model/bloodBankClass.php');

$newBlood = new addBlood;

$from   = isset($_GET['from'])   && $_GET['from']   !== '' ? $_GET['from']   : date('Y-m-01');
$to     = isset($_GET['to'])     && $_GET['to']     !== '' ? $_GET['to']     : date('Y-m-d');
$status = isset($_GET['status']) ? $_GET['status'] : '';

$collections = $newBlood->getCollections($from, $to, $status);

$page_title = 'Blood Collection';
include('blood_bank_head.php');

function bb_e($v) { return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8'); }

/** Colour a unit status so its state reads at a glance. */
function bb_status_badge($status)
{
    $map = array(
        'Collected_Unscreened'  => 'badge-secondary',
        'Under_Testing'         => 'badge-info',
        'Testing_Passed'        => 'badge-primary',
        'Released_To_Inventory' => 'badge-success',
        'Reserved'              => 'badge-warning',
        'Issued'                => 'badge-primary',
        'Transfused'            => 'badge-dark',
        'Testing_Failed'        => 'badge-danger',
        'Quarantined'           => 'badge-warning',
        'Discarded'             => 'badge-danger',
        'Expired'               => 'badge-danger',
        'Separated'             => 'badge-info',
    );
    $cls = isset($map[$status]) ? $map[$status] : 'badge-light';
    return '<span class="badge ' . $cls . '">' . htmlspecialchars(str_replace('_', ' ', (string) $status), ENT_QUOTES, 'UTF-8') . '</span>';
}
?>

<div class="row mb-3 ml-1">
    <h4 class="text-uppercase">Blood Collection</h4>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="blood_collection.php">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>From Date</label>
                                <input type="date" class="form-control" name="from" value="<?= bb_e($from) ?>" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>To Date</label>
                                <input type="date" class="form-control" name="to" value="<?= bb_e($to) ?>" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Unit Status</label>
                                <select name="status" class="form-control">
                                    <option value="">All</option>
                                    <?php foreach (array('Collected_Unscreened','Under_Testing','Released_To_Inventory',
                                                         'Testing_Failed','Reserved','Issued','Transfused',
                                                         'Separated','Discarded','Expired') as $s) { ?>
                                        <option value="<?= $s ?>" <?= $status === $s ? 'selected' : '' ?>>
                                            <?= str_replace('_', ' ', $s) ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <div class="form-group w-100">
                                <button type="submit" class="btn btn-success btn-rounded mr-2">
                                    <i class="icofont-search-1"></i> Search
                                </button>
                                <a href="add_blood_collection.php" class="btn btn-danger btn-rounded">
                                    <i class="icofont-plus"></i> New Collection
                                </a>
                            </div>
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
                <h5 class="ml-2">Collection Register
                    <span class="text-danger">
                        <?= date('d.m.Y', strtotime($from)) ?> to <?= date('d.m.Y', strtotime($to)) ?>
                    </span>
                </h5>
                <hr>
                <div class="table-responsive">
                    <table class="table table-bordered text-center bb-table">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Collection No.</th>
                                <th>Date</th>
                                <th>Donor</th>
                                <th>Unit Barcode</th>
                                <th>Group</th>
                                <th>Component</th>
                                <th>Volume</th>
                                <th>Expiry</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            while ($row = $collections->fetch_assoc()) { ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= bb_e($row['collection_number']) ?></td>
                                    <td><?= $row['collection_date'] ? date('d.m.Y', strtotime($row['collection_date'])) : '' ?></td>
                                    <td class="text-left">
                                        <?= bb_e($row['donor_name']) ?><br>
                                        <small class="text-muted"><?= bb_e($row['donor_unique_id']) ?></small>
                                    </td>
                                    <td class="bb-barcode"><?= bb_e($row['unit_barcode']) ?></td>
                                    <td><b><?= bb_e($row['blood_group']) ?></b></td>
                                    <td><?= bb_e(str_replace('_', ' ', $row['component_type'])) ?></td>
                                    <td><?= bb_e($row['volume_collected_ml']) ?> ml</td>
                                    <td><?= $row['expiry_date'] ? date('d.m.Y', strtotime($row['expiry_date'])) : '' ?></td>
                                    <td><?= bb_status_badge($row['unit_status']) ?></td>
                                    <td>
                                        <?php if (in_array($row['unit_status'], array('Collected_Unscreened', 'Under_Testing'), true)) { ?>
                                            <a href="blood_screening.php?unit_id=<?= (int) $row['unit_id'] ?>"
                                               class="btn btn-sm btn-primary">Screen</a>
                                        <?php } elseif ($row['unit_status'] === 'Released_To_Inventory') { ?>
                                            <a href="blood_stock.php" class="btn btn-sm btn-outline-success">In Stock</a>
                                        <?php } else { ?>
                                            <span class="text-muted">&mdash;</span>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php }
                            if ($i === 1) { ?>
                                <tr><td colspan="11" class="text-muted">No collections in this period.</td></tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('blood_bank_foot.php'); ?>
