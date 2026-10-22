<?php
/**
 * Tender & Procurement action handler.
 *
 * Every screen posts here with an `action`; each branch redirects back with a
 * flash message rather than rendering anything. The store module has its own
 * controllers and is not touched by any of this — the only store write is the
 * purchase order generated from an approved award.
 */
require_once(__DIR__ . '/../model/session_config.php');
include('../model/procurementClass.php');

if (!isset($_SESSION['loginid'])) {
    header('Location: ../index.php');
    exit;
}

$proc = new procurement;

function req($key, $default = '')
{
    return isset($_REQUEST[$key]) ? $_REQUEST[$key] : $default;
}

function reqArr($key)
{
    if (!isset($_REQUEST[$key])) { return array(); }
    return is_array($_REQUEST[$key]) ? $_REQUEST[$key] : array($_REQUEST[$key]);
}

function backTo($page, $ok, $message)
{
    $key = $ok ? 'success' : 'error';
    header('Location: ../' . $page . (strpos($page, '?') === false ? '?' : '&')
           . $key . '=' . rawurlencode($message));
    exit;
}

/**
 * Item rows arrive as parallel arrays (item_id[], quantity[], rate[]…) because
 * that is how the add-a-row tables in this project post. Zip them back up.
 */
function itemRows()
{
    $ids   = reqArr('item_id');
    $qty   = reqArr('quantity');
    $rate  = reqArr('rate');
    $spec  = reqArr('specification');
    $rem   = reqArr('item_remarks');
    $out = array();
    foreach ($ids as $i => $id) {
        $out[] = array(
            'item_id'       => $id,
            'quantity'      => isset($qty[$i])  ? $qty[$i]  : 0,
            'rate'          => isset($rate[$i]) ? $rate[$i] : 0,
            'specification' => isset($spec[$i]) ? $spec[$i] : '',
            'remarks'       => isset($rem[$i])  ? $rem[$i]  : '',
        );
    }
    return $out;
}

$action = req('action');

switch ($action) {

    // ------------------------------------------------------- Requisitions
    case 'create_requisition':
        $res = $proc->createRequisition(array(
            'store_id'      => (int) req('store_id'),
            'department'    => req('department'),
            'required_by'   => req('required_by'),
            'priority'      => req('priority', 'Normal'),
            'justification' => req('justification'),
        ), itemRows());

        if (!$res['ok']) { backTo('procurement_requisition.php', false, $res['error']); }

        // "Save & submit" sends it straight into the approval queue.
        if (req('submit_now') === '1') {
            $proc->submitRequisition($res['id']);
            backTo('requisition_details.php?id=' . $res['id'], true,
                   $res['requisition_no'] . ' created and submitted for approval.');
        }
        backTo('requisition_details.php?id=' . $res['id'], true,
               $res['requisition_no'] . ' saved as a draft.');
        break;

    case 'submit_requisition':
        $id  = (int) req('id');
        $res = $proc->submitRequisition($id);
        backTo('requisition_details.php?id=' . $id, $res['ok'],
               $res['ok'] ? 'Submitted for approval.' : $res['error']);
        break;

    // ------------------------------------------------------------ Tenders
    case 'create_tender':
        $res = $proc->createTender(array(
            'title'            => req('title'),
            'description'      => req('description'),
            'tender_type'      => req('tender_type', 'Open'),
            'published_date'   => req('published_date'),
            'closing_date'     => req('closing_date'),
            'opening_date'     => req('opening_date'),
            'emd_amount'       => req('emd_amount', 0),
            'terms_conditions' => req('terms_conditions'),
        ), itemRows(), (int) req('requisition_id'));

        if (!$res['ok']) {
            $back = ((int) req('requisition_id') > 0)
                  ? 'requisition_details.php?id=' . (int) req('requisition_id')
                  : 'tender_list.php';
            backTo($back, false, $res['error']);
        }
        backTo('tender_details.php?id=' . $res['id'], true, $res['tender_no'] . ' created as a draft.');
        break;

    case 'submit_tender':
        $id  = (int) req('id');
        $res = $proc->submitTender($id);
        backTo('tender_details.php?id=' . $id, $res['ok'],
               $res['ok'] ? 'Tender submitted for approval.' : $res['error']);
        break;

    case 'close_tender':
        $id  = (int) req('id');
        $res = $proc->closeTender($id);
        backTo('tender_details.php?id=' . $id, $res['ok'],
               $res['ok'] ? 'Bidding closed — the tender is now under evaluation.' : $res['error']);
        break;

    case 'cancel_tender':
        $id  = (int) req('id');
        $res = $proc->cancelTender($id, req('cancel_reason', 'Cancelled by user'));
        backTo('tender_details.php?id=' . $id, $res['ok'],
               $res['ok'] ? 'Tender cancelled.' : $res['error']);
        break;

    // --------------------------------------------------------------- Bids
    case 'save_bid':
        $tender_id = (int) req('tender_id');
        // Bid lines post keyed by tender_item_id: rate[<id>], gst[<id>]…
        $rates = array();
        foreach (reqArr('bid_rate') as $tiId => $rate) {
            $rates[$tiId] = array(
                'rate'        => $rate,
                'gst_percent' => req('bid_gst')[$tiId] ?? 0,
                'brand'       => req('bid_brand')[$tiId] ?? '',
                'remarks'     => req('bid_remarks')[$tiId] ?? '',
            );
        }
        $res = $proc->saveBid($tender_id, array(
            'supplier_id'      => (int) req('supplier_id'),
            'bid_reference'    => req('bid_reference'),
            'bid_date'         => req('bid_date'),
            'delivery_days'    => (int) req('delivery_days'),
            'payment_terms'    => req('payment_terms'),
            'warranty_terms'   => req('warranty_terms'),
            'emd_received'     => req('emd_received'),
            'technical_status' => req('technical_status', 'Pending'),
            'remarks'          => req('bid_note'),
        ), $rates);

        backTo('tender_details.php?id=' . $tender_id . '&tab=bids', $res['ok'],
               $res['ok'] ? 'Bid recorded — total ' . number_format($res['total'], 2) : $res['error']);
        break;

    case 'bid_technical':
        $tender_id = (int) req('tender_id');
        $res = $proc->setBidTechnicalStatus((int) req('bid_id'), req('technical_status'),
                                            req('disqualify_reason'));
        backTo('tender_details.php?id=' . $tender_id . '&tab=compare', $res['ok'],
               $res['ok'] ? 'Technical evaluation saved.' : $res['error']);
        break;

    // -------------------------------------------------------------- Award
    case 'create_award':
        $tender_id = (int) req('tender_id');
        $res = $proc->createAward($tender_id, (int) req('bid_id'), req('deviation_reason'));
        backTo('tender_details.php?id=' . $tender_id . '&tab=compare', $res['ok'],
               $res['ok']
                 ? $res['award_no'] . ' raised' . ($res['is_lowest'] ? ' (lowest bid).' : ' — NOT the lowest bid.')
                 : $res['error']);
        break;

    case 'generate_po':
        $award_id = (int) req('award_id');
        $res = $proc->generatePurchaseOrder($award_id);
        if (!$res['ok']) { backTo('tender_details.php?id=' . (int) req('tender_id'), false, $res['error']); }
        backTo('tender_details.php?id=' . (int) req('tender_id'), true,
               'Purchase order ' . $res['po_number'] . ' created with ' . $res['lines']
               . ' line(s) — it now appears in the store module.');
        break;

    // ----------------------------------------------------------- Approval
    case 'approve':
    case 'reject':
        $type = req('entity_type');
        $id   = (int) req('entity_id');
        $act  = ($action === 'approve') ? 'APPROVED' : 'REJECTED';
        $res  = $proc->recordApproval($type, $id, $act, req('remarks'));

        $back = 'procurement_dashboard.php';
        if ($type === 'REQUISITION') { $back = 'requisition_details.php?id=' . $id; }
        elseif ($type === 'TENDER')  { $back = 'tender_details.php?id=' . $id; }
        elseif ($type === 'AWARD')   {
            $award = $proc->loadEntity('AWARD', $id);
            $back  = $award ? 'tender_details.php?id=' . (int) $award['tender_id'] . '&tab=compare'
                            : 'procurement_dashboard.php';
        }

        if (!$res['ok']) { backTo($back, false, $res['error']); }
        if (!empty($res['rejected'])) { backTo($back, true, 'Rejected.'); }
        if (!empty($res['fully_approved'])) { backTo($back, true, 'Approved — no further sign-off needed.'); }
        backTo($back, true, 'Approved at level ' . req('level', '')
                          . ' — now waiting on ' . $res['next_role'] . '.');
        break;

    default:
        backTo('procurement_dashboard.php', false, 'Unknown action.');
}
