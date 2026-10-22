<?php
/**
 * Blood bank action handler — stages 3 to 5.
 *
 * Every screen posts here with an `action`; each branch redirects back with a
 * success or error message rather than rendering anything itself.
 * Stage 1 (donor) posts to processBloodDonor.php and stage 2 (collection) to
 * processBloodCollection.php; this file picks up from screening onwards.
 */
require_once(__DIR__ . '/../model/session_config.php');
include('../model/bloodBankClass.php');

if (!isset($_SESSION['loginid'])) {
    header('Location: ../index.php');
    exit;
}

$newBlood = new addBlood;

function req($key, $default = '')
{
    return isset($_REQUEST[$key]) ? $_REQUEST[$key] : $default;
}

/** Bounce back to a page with a flash message on the query string. */
function backTo($page, $ok, $message)
{
    $key = $ok ? 'success' : 'error';
    header('Location: ../' . $page . (strpos($page, '?') === false ? '?' : '&')
           . $key . '=' . rawurlencode($message));
    exit;
}

$action = req('action');

switch ($action) {

    // ---------------------------------------------------------------- Stage 3
    case 'save_testing':
        $unit_id = (int) req('unit_id');
        $res = $newBlood->saveTesting($unit_id, array(
            'hiv'           => req('hiv'),
            'hbsag'         => req('hbsag'),
            'hcv'           => req('hcv'),
            'syphilis'      => req('syphilis'),
            'malaria'       => req('malaria'),
            'method'        => req('test_method', 'ELISA'),
            'kit_lot'       => req('kit_lot_number'),
            'abo_confirmed' => req('abo_group_confirmed'),
            'rh_confirmed'  => req('rh_factor_confirmed'),
            'remarks'       => req('remarks'),
        ));

        if (!$res['ok']) {
            backTo('blood_screening.php?unit_id=' . $unit_id, false, $res['error']);
        }
        if ($res['result'] === 'Passed') {
            backTo('blood_screening.php', true, 'Unit passed screening and is now in inventory.');
        }
        if ($res['result'] === 'Failed') {
            backTo('blood_screening.php', true, 'Unit FAILED screening and has been discarded. ' . $res['reason']);
        }
        backTo('blood_screening.php?unit_id=' . $unit_id, true, 'Partial results saved — unit is still under testing.');
        break;

    // ---------------------------------------------------------------- Stage 4
    case 'separate_components':
        $parent = (int) req('parent_unit_id');
        $components = req('components', array());
        if (!is_array($components)) { $components = array($components); }

        $res = $newBlood->separateComponents(
            $parent, $components, req('separation_method', 'Centrifugation'), req('remarks')
        );
        backTo('blood_component.php', $res['ok'],
               $res['ok'] ? 'Separated into: ' . implode(', ', $res['components'])
                          : $res['error']);
        break;

    // ---------------------------------------------------------------- Stage 5
    case 'create_request':
        $res = $newBlood->createRequest(array(
            'patient_type'   => req('patient_type', 'IPD'),
            'patient_no'     => req('patient_no'),
            'patient_name'   => req('patient_name'),
            'patient_age'    => req('patient_age'),
            'patient_gender' => req('patient_gender'),
            'ward'           => req('ward'),
            'blood_group'    => req('blood_group'),
            'component_type' => req('component_type', 'Whole_Blood'),
            'units_required' => (int) req('units_required', 1),
            'urgency'        => req('urgency', 'Routine'),
            'indication'     => req('indication'),
            'requested_by'   => req('requested_by'),
        ));
        backTo('blood_issue.php', $res['ok'],
               $res['ok'] ? 'Request ' . $res['request_number'] . ' created.' : $res['error']);
        break;

    case 'save_crossmatch':
        $request_id = (int) req('request_id');
        $res = $newBlood->saveCrossmatch(
            $request_id, (int) req('unit_id'),
            req('method', 'AHG_Coombs'), req('result', 'Pending'), req('incompatibility_note')
        );
        backTo('blood_issue.php?request_id=' . $request_id, $res['ok'],
               $res['ok'] ? 'Crossmatch recorded.' : $res['error']);
        break;

    case 'issue_unit':
        $request_id = (int) req('request_id');
        $res = $newBlood->issueUnit($request_id, (int) req('unit_id'), req('collected_by'));
        backTo('blood_issue.php?request_id=' . $request_id, $res['ok'],
               $res['ok'] ? 'Unit issued — ' . $res['issue_number'] : $res['error']);
        break;

    case 'record_outcome':
        $status = req('transfusion_status', 'Transfused');
        $res = $newBlood->recordOutcome((int) req('issue_id'), $status, array(
            'reaction_flag'  => req('reaction_flag'),
            'reaction_type'  => req('reaction_type'),
            'reaction_notes' => req('reaction_notes'),
            'return_reason'  => req('return_reason'),
        ), req('back_to_stock') === '1');
        backTo('blood_issue.php?tab=issues', $res['ok'],
               $res['ok'] ? 'Outcome recorded.' : $res['error']);
        break;

    case 'cancel_request':
        $res = $newBlood->cancelRequest((int) req('request_id'), req('cancel_reason', 'Cancelled by user'));
        backTo('blood_issue.php', $res['ok'],
               $res['ok'] ? 'Request cancelled and reserved units released.' : $res['error']);
        break;

    case 'discard_unit':
        $ok = $newBlood->discardUnit((int) req('unit_id'), req('discard_reason', 'Discarded by user'));
        backTo('blood_stock.php', (bool) $ok,
               $ok ? 'Unit discarded.' : 'Could not discard the unit.');
        break;

    default:
        backTo('blood_stock.php', false, 'Unknown action.');
}
