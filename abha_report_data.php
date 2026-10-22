<?php
/**
 * Row source for the ABHA Registration Report's detail tabs.
 *
 * abha_reports.php used to inline every OPD and IPD row for the period, which
 * on a busy month meant a multi-megabyte page and three DataTables parsing the
 * whole DOM before the summary was usable. The detail tabs now fetch their rows
 * from here the first time they are opened, so a page load only carries the
 * summary.
 *
 * Echoes <tr> rows only -- the caller drops them into an existing <tbody>.
 * ?section=opd|ipd|day plus the same ab_from / ab_to / ab_dept / ab_status
 * filters the report page uses.
 */
require_once(__DIR__ . '/model/session_config.php');
if (!isset($_SESSION['loginid'])) {
    http_response_code(401);
    exit;
}
date_default_timezone_set("Asia/Kolkata");

include('model/reportClass.php');
$newReport = new report;

$section = isset($_REQUEST['section']) ? $_REQUEST['section'] : '';
$ab_from = isset($_REQUEST['ab_from']) && $_REQUEST['ab_from'] !== ''
    ? $_REQUEST['ab_from'] : date('Y-m-01');
$ab_to   = isset($_REQUEST['ab_to']) && $_REQUEST['ab_to'] !== ''
    ? $_REQUEST['ab_to']   : date('Y-m-d');
$ab_dept   = isset($_REQUEST['ab_dept'])   ? (int) $_REQUEST['ab_dept'] : 0;
$ab_status = isset($_REQUEST['ab_status']) ? $_REQUEST['ab_status']     : '';
if (!in_array($ab_status, array('', 'with', 'without'), true)) {
    $ab_status = '';
}

if (!function_exists("ab_e")) {
    /** Escape for HTML output. */
    function ab_e($v) { return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8'); }
}

if ($section === 'opd') {

    $res = $newReport->abhaOPDetails($ab_from, $ab_to, $ab_dept, $ab_status);
    $i = 1;
    while ($r = $res->fetch_assoc()) { ?>
        <tr>
            <td><?= $i++ ?></td>
            <td><?= ab_e($r['uhid']) ?></td>
            <td><?= ab_e($r['opd_no']) ?></td>
            <td><?= $r['date'] ? date('d.m.Y', strtotime($r['date'])) : '' ?></td>
            <td class="text-left"><?= ab_e($r['name']) ?></td>
            <td><?= ab_e($r['age']) ?> / <?= ab_e($r['gender']) ?></td>
            <td><?= ab_e($r['mobile']) ?></td>
            <td><?= ab_e($r['dept_name']) ?></td>
            <td><?= ab_e($r['patient_type']) ?></td>
            <td class="<?= $r['abha_number'] ? 'abha-yes' : 'abha-no' ?>">
                <?= $r['abha_number'] ? ab_e($r['abha_number']) : 'Not Registered' ?>
            </td>
            <td><?= ab_e($r['abha_address']) ?></td>
            <td class="<?= ((int) $r['abha_linked'] === 1) ? 'abha-yes' : 'abha-no' ?>"><?= ((int) $r['abha_linked'] === 1) ? 'Linked' : 'Not Linked' ?></td>
            <td><?= ab_e($r['created_by']) ?></td>
        </tr>
    <?php }
    if ($i === 1) { ?>
        <tr><td colspan="13" class="text-muted">No OPD registrations for the selected criteria.</td></tr>
    <?php }

} elseif ($section === 'ipd') {

    $res = $newReport->abhaIPDetails($ab_from, $ab_to, $ab_dept, $ab_status);
    $i = 1;
    while ($r = $res->fetch_assoc()) { ?>
        <tr>
            <td><?= $i++ ?></td>
            <td><?= ab_e($r['uhid']) ?></td>
            <td><?= ab_e($r['ipd_no']) ?></td>
            <td><?= $r['admission_date'] ? date('d.m.Y', strtotime($r['admission_date'])) : '' ?></td>
            <td class="text-left"><?= ab_e($r['name']) ?></td>
            <td><?= ab_e($r['age']) ?> / <?= ab_e($r['gender']) ?></td>
            <td><?= ab_e($r['mobile']) ?></td>
            <td><?= ab_e($r['dept_name']) ?></td>
            <td><?= ab_e($r['patient_type']) ?></td>
            <td><?= ab_e($r['ward']) ?></td>
            <td class="<?= $r['abha_number'] ? 'abha-yes' : 'abha-no' ?>">
                <?= $r['abha_number'] ? ab_e($r['abha_number']) : 'Not Registered' ?>
            </td>
            <td class="<?= ((int) $r['abha_linked'] === 1) ? 'abha-yes' : 'abha-no' ?>"><?= ((int) $r['abha_linked'] === 1) ? 'Linked' : 'Not Linked' ?></td>
            <td><?= ab_e($r['created_by']) ?></td>
        </tr>
    <?php }
    if ($i === 1) { ?>
        <tr><td colspan="13" class="text-muted">No IPD admissions for the selected criteria.</td></tr>
    <?php }

} elseif ($section === 'day') {

    $days = $newReport->abhaDayWise($ab_from, $ab_to);
    foreach ($days as $d) {
        $dt = $d['op_total'] + $d['ip_total'];
        $da = $d['op_abha'] + $d['ip_abha']; ?>
        <tr>
            <td><?= date('d.m.Y', strtotime($d['date'])) ?></td>
            <td><?= $d['op_total'] ?></td>
            <td class="abha-yes"><?= $d['op_abha'] ?></td>
            <td><?= $d['ip_total'] ?></td>
            <td class="abha-yes"><?= $d['ip_abha'] ?></td>
            <td><?= $dt ?></td>
            <td class="abha-yes"><?= $da ?></td>
            <td><?= $dt > 0 ? round(($da * 100) / $dt, 2) : 0 ?>%</td>
        </tr>
    <?php }
    if (empty($days)) { ?>
        <tr><td colspan="8" class="text-muted">No registrations in this period.</td></tr>
    <?php }

} else {
    http_response_code(400);
    echo '<tr><td class="text-danger">Unknown report section.</td></tr>';
}
