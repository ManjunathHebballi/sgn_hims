<?php
/**
 * Procurement overview and the approval inbox.
 *
 * The inbox lists only what the signed-in user's role can actually sign at the
 * level each document is waiting on, so a storekeeper is not shown items that
 * need an administrator.
 */
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set('Asia/Kolkata');
include('model/procurementClass.php');

$proc   = new procurement;
$counts = $proc->dashboardCounts();
$inbox  = $proc->myPendingApprovals();

$page_title = 'Procurement Dashboard';
include('procurement_head.php');
?>

<div class="row mb-3 ml-1">
    <h4 class="text-uppercase">Tender &amp; Procurement</h4>
</div>

<div class="row">
    <div class="col-xl-2 col-md-4">
        <div class="card"><div class="card-body text-center pr-stat">
            <p class="text-muted mb-1">Requisitions Awaiting Approval</p>
            <h2 class="text-warning"><?= $counts['req_pending'] ?></h2>
        </div></div>
    </div>
    <div class="col-xl-2 col-md-4">
        <div class="card"><div class="card-body text-center pr-stat">
            <p class="text-muted mb-1">Approved, Not Yet Tendered</p>
            <h2 class="text-success"><?= $counts['req_approved'] ?></h2>
        </div></div>
    </div>
    <div class="col-xl-2 col-md-4">
        <div class="card"><div class="card-body text-center pr-stat">
            <p class="text-muted mb-1">Open Tenders</p>
            <h2 class="text-primary"><?= $counts['tender_open'] ?></h2>
        </div></div>
    </div>
    <div class="col-xl-2 col-md-4">
        <div class="card"><div class="card-body text-center pr-stat">
            <p class="text-muted mb-1">Under Evaluation</p>
            <h2 class="text-info"><?= $counts['tender_eval'] ?></h2>
        </div></div>
    </div>
    <div class="col-xl-2 col-md-4">
        <div class="card"><div class="card-body text-center pr-stat">
            <p class="text-muted mb-1">Awards Awaiting Approval</p>
            <h2 class="text-warning"><?= $counts['award_pending'] ?></h2>
        </div></div>
    </div>
    <div class="col-xl-2 col-md-4">
        <div class="card"><div class="card-body text-center pr-stat">
            <p class="text-muted mb-1">Purchase Orders Raised</p>
            <h2 class="text-success"><?= $counts['po_generated'] ?></h2>
        </div></div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h5 class="ml-2">My Approvals
                            <span class="badge badge-warning"><?= count($inbox) ?></span>
                        </h5>
                        <p class="text-muted ml-2 mb-2"><small>
                            Signed in as <b><?= pr_e($_SESSION['role']) ?></b> — only documents you can
                            sign at their current level are listed.
                        </small></p>
                    </div>
                    <div class="col-md-4 text-right">
                        <a href="procurement_requisition.php" class="btn btn-sm btn-success btn-rounded">
                            <i class="icofont-plus"></i> New Requisition
                        </a>
                        <a href="tender_list.php" class="btn btn-sm btn-primary btn-rounded">Tenders</a>
                    </div>
                </div>
                <hr>
                <div class="table-responsive">
                    <table class="table table-bordered text-center">
                        <thead class="thead-dark">
                            <tr>
                                <th>Type</th>
                                <th>Document</th>
                                <th class="num">Amount</th>
                                <th>Raised By</th>
                                <th>Raised</th>
                                <th>Waiting On</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($inbox as $row) {
                                $link = $row['entity_type'] === 'REQUISITION'
                                      ? 'requisition_details.php?id=' . (int) $row['id']
                                      : ($row['entity_type'] === 'TENDER'
                                          ? 'tender_details.php?id=' . (int) $row['id']
                                          : 'tender_details.php?tab=compare&award=' . (int) $row['id']); ?>
                                <tr>
                                    <td><span class="badge badge-light"><?= pr_e($row['entity_type']) ?></span></td>
                                    <td class="pr-num"><?= pr_e($row['doc_no']) ?></td>
                                    <td class="num"><?= pr_money($row['amount']) ?></td>
                                    <td><?= pr_e($row['created_by']) ?></td>
                                    <td><?= $row['created_at'] ? date('d.m.Y', strtotime($row['created_at'])) : '' ?></td>
                                    <td>Level <?= pr_e($row['pending_level']) ?>
                                        &mdash; <?= pr_e($row['pending_role']) ?></td>
                                    <td><a href="<?= $link ?>" class="btn btn-sm btn-primary">Review</a></td>
                                </tr>
                            <?php }
                            if (!$inbox) { ?>
                                <tr><td colspan="7" class="text-muted">Nothing is waiting on you.</td></tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('procurement_foot.php'); ?>
