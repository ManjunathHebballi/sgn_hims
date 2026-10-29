<?php
// session_config.php forces the project session save path and starts the session.
// Calling session_start() ahead of it would bind this page to PHP's default save
// path, leaving $_SESSION empty and bouncing a logged-in user back to index.php.
require_once __DIR__ . '/model/session_config.php';
require_once __DIR__ . '/config/abdm_config.php';
date_default_timezone_set(env('TZ', 'Asia/Kolkata'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Link Care Contexts — ABHA</title>
  <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
  <link href="./css/style.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    .content-body { background: #eef2fb; font-family: 'Inter', system-ui, sans-serif; }

    /* ── Hero ── */
    .cc-hero {
      background: linear-gradient(135deg, #1a3a6b 0%, #1565c0 60%, #1976d2 100%);
      border-radius: 16px; margin: 20px 20px 0;
      padding: 18px 32px; display: flex; align-items: center;
      justify-content: space-between; gap: 16px; position: relative; overflow: hidden;
    }
    .cc-hero::before {
      content: ''; position: absolute; width: 240px; height: 240px;
      background: rgba(255,255,255,.06); border-radius: 50%;
      top: -80px; right: 80px; pointer-events: none;
    }
    .cc-hero-badge {
      display: inline-flex; align-items: center; gap: 6px;
      background: rgba(255,255,255,.15); border-radius: 20px;
      padding: 3px 12px; font-size: 11px; font-weight: 600;
      color: #bbdefb; margin-bottom: 6px; letter-spacing: .4px;
    }
    .cc-hero h2 { color: #fff; margin: 4px 0 2px; font-size: 20px; font-weight: 700; }
    .cc-hero p  { color: rgba(255,255,255,.75); margin: 0; font-size: 13px; }

    /* ── Workspace ── */
    .cc-workspace {
      display: grid;
      grid-template-columns: 340px 1fr;
      gap: 18px;
      padding: 18px 20px 30px;
      align-items: start;
    }

    /* ── Cards ── */
    .cc-card {
      background: #fff; border-radius: 14px;
      box-shadow: 0 2px 12px rgba(21,101,192,.08);
      overflow: hidden;
    }
    .cc-card-head {
      background: linear-gradient(90deg, #1a3a6b, #1565c0);
      padding: 12px 18px; display: flex; align-items: center; gap: 10px;
    }
    .cc-card-head i { color: #90caf9; font-size: 18px; }
    .cc-card-head span { color: #fff; font-size: 14px; font-weight: 600; }
    .cc-card-body { padding: 16px 18px; }

    /* ── Search ── */
    .cc-search-row {
      display: flex; gap: 8px; align-items: stretch; flex-wrap: wrap;
    }
    .cc-search-select {
      border: 1.5px solid #d0d9ee; border-radius: 8px; padding: 8px 10px;
      font-size: 13px; color: #1a3a6b; font-family: inherit; background: #f7faff;
      outline: none; flex: 0 0 140px;
    }
    .cc-search-input {
      flex: 1; min-width: 160px; border: 1.5px solid #d0d9ee;
      border-radius: 8px; padding: 8px 12px; font-size: 13px;
      font-family: inherit; color: #1a3a6b; background: #f7faff; outline: none;
    }
    .cc-search-input:focus, .cc-search-select:focus {
      border-color: #1565c0; background: #fff;
    }
    .btn-cc-search {
      background: linear-gradient(135deg, #1565c0, #1976d2);
      color: #fff; border: none; border-radius: 8px;
      padding: 8px 18px; font-size: 13px; font-weight: 600;
      cursor: pointer; display: flex; align-items: center; gap: 6px;
      white-space: nowrap;
    }
    .btn-cc-search:hover { background: linear-gradient(135deg, #0d47a1, #1565c0); }

    /* ── Patient info ── */
    .pi-row { display: flex; gap: 6px; padding: 7px 0; border-bottom: 1px solid #f0f4fb; }
    .pi-row:last-child { border-bottom: none; }
    .pi-lbl { font-size: 11px; font-weight: 700; color: #888; min-width: 100px; text-transform: uppercase; letter-spacing: .4px; }
    .pi-val { font-size: 13px; font-weight: 600; color: #1a3a6b; word-break: break-all; }
    .pi-abha-chip {
      display: inline-block; background: #e3f2fd; color: #1565c0;
      border-radius: 20px; padding: 2px 10px; font-size: 12px; font-weight: 700;
    }
    .pi-no-abha {
      font-size: 12px; color: #e57373; font-weight: 600;
      background: #fff3f3; border-radius: 20px; padding: 2px 10px;
    }

    /* ── Care context groups ── */
    .cc-group { margin-bottom: 12px; border: 1.5px solid #e3eaf6; border-radius: 10px; overflow: hidden; }
    .cc-group-head {
      display: flex; align-items: center; gap: 8px;
      padding: 9px 14px; background: #f5f8ff; cursor: pointer;
      user-select: none;
    }
    .cc-group-head:hover { background: #e8f0fe; }
    .cc-group-head-left { display: flex; align-items: center; gap: 8px; flex: 1; }
    .cc-group-check { width: 16px; height: 16px; cursor: pointer; accent-color: #1565c0; }
    .cc-group-icon { font-size: 16px; color: #1565c0; }
    .cc-group-title { font-size: 13px; font-weight: 700; color: #1a3a6b; }
    .cc-group-count {
      font-size: 11px; background: #1565c0; color: #fff;
      border-radius: 10px; padding: 1px 8px; font-weight: 700;
    }
    .cc-group-toggle { font-size: 12px; color: #888; }
    .cc-group-body { padding: 4px 14px 10px; display: none; }
    .cc-group-body.open { display: block; }
    .cc-item { display: flex; align-items: flex-start; gap: 8px; padding: 6px 0; border-bottom: 1px solid #f0f4fb; }
    .cc-item:last-child { border-bottom: none; }
    .cc-item-check { width: 14px; height: 14px; margin-top: 2px; cursor: pointer; accent-color: #1565c0; flex-shrink: 0; }
    .cc-item-text { font-size: 12px; color: #334; line-height: 1.4; }
    .cc-item-ref { font-size: 10px; color: #aaa; margin-top: 1px; }

    /* ── Link button ── */
    .btn-cc-link {
      background: linear-gradient(135deg, #1b5e20, #2e7d32);
      color: #fff; border: none; border-radius: 10px;
      padding: 10px 22px; font-size: 14px; font-weight: 700;
      cursor: pointer; display: flex; align-items: center; gap: 8px;
      width: 100%; justify-content: center; margin-top: 14px;
    }
    .btn-cc-link:hover { background: linear-gradient(135deg, #1a4c1e, #256427); }
    .btn-cc-link:disabled { background: #ccc; cursor: not-allowed; }

    /* ── Status log ── */
    .cc-status-log {
      margin-top: 12px; padding: 10px 14px;
      background: #f5f8ff; border-radius: 8px;
      font-size: 12px; line-height: 1.7; color: #334;
      display: none;
    }
    .cc-status-log.visible { display: block; }
    .log-ok   { color: #2e7d32; font-weight: 700; }
    .log-err  { color: #c62828; font-weight: 700; }
    .log-info { color: #1565c0; }

    /* ── Empty / loader states ── */
    .cc-empty {
      text-align: center; padding: 32px 16px;
      color: #aaa; font-size: 13px;
    }
    .cc-empty i { font-size: 36px; display: block; margin-bottom: 8px; color: #d0d9ee; }

    /* ── Loader overlay ── */
    #cc-loader {
      display: none; position: fixed; inset: 0;
      background: rgba(0,0,0,.35); z-index: 9999;
      align-items: center; justify-content: center;
    }
    #cc-loader.show { display: flex; }
    .cc-spin {
      width: 44px; height: 44px; border: 4px solid rgba(255,255,255,.3);
      border-top-color: #fff; border-radius: 50%;
      animation: spin .7s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }

    /* ── Selection summary badge ── */
    .cc-sel-badge {
      display: none; align-items: center; gap: 8px;
      background: #e8f5e9; border: 1.5px solid #a5d6a7;
      border-radius: 8px; padding: 7px 14px; margin-bottom: 10px;
      font-size: 12px; color: #2e7d32; font-weight: 600;
    }
    .cc-sel-badge.visible { display: flex; }
  </style>
</head>
<body>
<div id="preloader">
  <div class="sk-three-bounce">
    <div class="sk-child sk-bounce1"></div>
    <div class="sk-child sk-bounce2"></div>
    <div class="sk-child sk-bounce3"></div>
  </div>
</div>

<!-- Loader overlay -->
<div id="cc-loader"><div class="cc-spin"></div></div>

<div id="main-wrapper">
  <?php include('leftbar.php'); ?>

  <div class="content-body">

    <!-- Hero -->
    <div class="cc-hero">
      <div style="position:relative;z-index:1;">
        <div class="cc-hero-badge"><i class="ri-links-line"></i> ABDM — HIP Linking</div>
        <h2>Care Context Linking</h2>
        <p>Link patient health records to their ABHA address via ABDM</p>
      </div>
    </div>

    <!-- Workspace -->
    <div class="cc-workspace">

      <!-- Left column: Search + Patient Info -->
      <div>
        <!-- Search Card -->
        <div class="cc-card" style="margin-bottom:16px;">
          <div class="cc-card-head">
            <i class="ri-search-line"></i>
            <span>Search Patient</span>
          </div>
          <div class="cc-card-body">
            <div class="cc-search-row">
              <select id="cc_id_type" class="cc-search-select">
                <option value="opd_no">OPD No</option>
                <option value="ipd_no">IPD No</option>
                <option value="mobile">Mobile</option>
                <option value="abha_number">ABHA Number</option>
                <option value="abha_address">ABHA Address</option>
              </select>
              <input type="text" id="cc_identifier" class="cc-search-input" placeholder="e.g. OP10045">
              <button class="btn-cc-search" id="cc_search_btn">
                <i class="ri-search-line"></i> Search
              </button>
            </div>
            <div id="cc_search_error" style="margin-top:8px;font-size:12px;color:#e53e3e;display:none;"></div>
          </div>
        </div>

        <!-- Patient Info Card -->
        <div class="cc-card" id="cc_patient_card" style="display:none;">
          <div class="cc-card-head">
            <i class="ri-user-3-line"></i>
            <span>Patient Information</span>
          </div>
          <div class="cc-card-body">
            <div class="pi-row"><span class="pi-lbl">Name</span><span class="pi-val" id="pi_name">-</span></div>
            <div class="pi-row"><span class="pi-lbl">Gender</span><span class="pi-val" id="pi_gender">-</span></div>
            <div class="pi-row"><span class="pi-lbl">Date of Birth</span><span class="pi-val" id="pi_dob">-</span></div>
            <div class="pi-row"><span class="pi-lbl">Mobile</span><span class="pi-val" id="pi_mobile">-</span></div>
            <div class="pi-row">
              <span class="pi-lbl">ABHA Number</span>
              <span class="pi-val" id="pi_abha_number">-</span>
            </div>
            <div class="pi-row">
              <span class="pi-lbl">ABHA Address</span>
              <span class="pi-val" id="pi_abha_address">-</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Right column: Care Contexts -->
      <div>
        <div class="cc-card">
          <div class="cc-card-head">
            <i class="ri-file-list-3-line"></i>
            <span>Care Contexts</span>
          </div>
          <div class="cc-card-body">
            <!-- Initial empty state -->
            <div class="cc-empty" id="cc_empty_state">
              <i class="ri-search-2-line"></i>
              Search for a patient to see their available care contexts
            </div>

            <!-- Care context list (populated via JS) -->
            <div id="cc_contexts_area" style="display:none;">
              <div class="cc-sel-badge" id="cc_sel_badge">
                <i class="ri-checkbox-circle-line"></i>
                <span id="cc_sel_count">0</span> care context(s) selected
              </div>
              <div id="cc_groups_list"></div>
              <button class="btn-cc-link" id="cc_link_btn" disabled>
                <i class="ri-links-line"></i> Link Selected to ABHA
              </button>
              <div class="cc-status-log" id="cc_status_log"></div>
            </div>
          </div>
        </div>
      </div>

    </div><!-- /.cc-workspace -->

  </div><!-- /.content-body -->
</div><!-- /#main-wrapper -->

<script src="./vendor/global/global.min.js"></script>
<script src="./js/quixnav-init.js"></script>
<script src="./js/custom.min.js"></script>
<script>
/* ── HI type display config ── */
const HI_TYPE_CONFIG = {
  OPConsultation:       { label: 'OPD Consultations',    icon: 'ri-stethoscope-line' },
  WellnessRecord:       { label: 'Wellness Records',      icon: 'ri-heart-pulse-line' },
  Prescription:         { label: 'Prescriptions',         icon: 'ri-capsule-line' },
  ImmunizationRecord:   { label: 'Immunization Records',  icon: 'ri-syringe-line' },
  DischargeSummary:     { label: 'Discharge Summaries',   icon: 'ri-hospital-line' },
  DiagnosticReport:     { label: 'Diagnostic Reports',    icon: 'ri-test-tube-line' },
  HealthDocumentRecord: { label: 'Health Documents',      icon: 'ri-file-text-line' },
  Invoice:              { label: 'Invoices',              icon: 'ri-bill-line' },
};

let patientData    = null;  // patient demographics from search
let careContextData = [];   // array of { referenceNumber, display, hiType, careContexts[] }
let accessToken    = '';    // ABDM access token

function showLoader()  { document.getElementById('cc-loader').classList.add('show'); }
function hideLoader()  { document.getElementById('cc-loader').classList.remove('show'); }

function logStatus(html) {
  const el = document.getElementById('cc_status_log');
  el.innerHTML += html + '<br>';
  el.classList.add('visible');
}

/* ── Get ABDM access token ── */
function ensureAccessToken(cb) {
  if (accessToken) { cb(accessToken); return; }
  $.post('abha_integration.php', { page: 'get_token' }, function(res) {
    const data = typeof res === 'string' ? JSON.parse(res) : res;
    const t = data?.data?.accessToken || data?.accessToken || '';
    if (!t) { alert('Failed to obtain ABDM access token. Please reload and try again.'); return; }
    accessToken = t;
    cb(accessToken);
  }).fail(function() { alert('Token request failed. Check network.'); });
}

/* ── Search ── */
$('#cc_search_btn').on('click', function() {
  const identifier = $('#cc_identifier').val().trim();
  const idType     = $('#cc_id_type').val();
  if (!identifier) {
    $('#cc_search_error').text('Please enter a search value.').show();
    return;
  }
  $('#cc_search_error').hide();
  showLoader();

  $.ajax({
    url: 'abha_integration.php',
    method: 'POST',
    contentType: 'application/json',
    dataType: 'json',
    data: JSON.stringify({ page: 'get_patient_care_contexts', identifier, identifier_type: idType }),
    success: function(res) {
      hideLoader();
      if (!res.success) {
        $('#cc_search_error').text(res.error || 'Patient not found.').show();
        return;
      }
      renderPatient(res.patient);
      renderCareContexts(res.careContexts || []);
    },
    error: function(xhr) {
      hideLoader();
      $('#cc_search_error').text('Search failed: ' + (xhr.responseText || 'unknown error')).show();
    }
  });
});

$('#cc_identifier').on('keydown', function(e) {
  if (e.key === 'Enter') $('#cc_search_btn').click();
});

/* ── Placeholder hint per identifier type ── */
$('#cc_id_type').on('change', function() {
  const hints = {
    opd_no: 'e.g. OP10045',
    ipd_no: 'e.g. IP10045',
    mobile: 'e.g. 9876543210',
    abha_number: 'e.g. 91-1234-5678-9012',
    abha_address: 'e.g. name@abdm',
  };
  $('#cc_identifier').attr('placeholder', hints[$(this).val()] || '');
});

/* ── Deep link: careContextLink.php?opd_no=XXX (or ?ipd_no=XXX) auto-searches ── */
$(function() {
  const params = new URLSearchParams(window.location.search);
  const idType = ['opd_no', 'ipd_no'].find(function(t) { return params.get(t); });
  if (!idType) return;
  $('#cc_id_type').val(idType).trigger('change');
  $('#cc_identifier').val(params.get(idType).trim());
  $('#cc_search_btn').click();
});

/* ── Render patient info ── */
function renderPatient(p) {
  patientData = p;
  const genderMap = { M: 'Male', F: 'Female', O: 'Other', m: 'Male', f: 'Female' };
  $('#pi_name').text(p.name || '-');
  $('#pi_gender').text(genderMap[p.gender] || p.gender || '-');
  $('#pi_dob').text(p.dob || '-');
  $('#pi_mobile').text(p.mobile || '-');

  const abhaNum = (p.abha_number || '').trim();
  const abhaAddr = (p.abha_address || '').trim();

  $('#pi_abha_number').html(abhaNum
    ? '<span class="pi-abha-chip">' + abhaNum + '</span>'
    : '<span class="pi-no-abha">Not linked</span>');
  $('#pi_abha_address').html(abhaAddr
    ? '<span class="pi-abha-chip">' + abhaAddr + '</span>'
    : '<span class="pi-no-abha">Not linked</span>');

  $('#cc_patient_card').show();
}

/* ── Render care contexts ── */
function renderCareContexts(groups) {
  careContextData = groups;
  const $list = $('#cc_groups_list').empty();

  if (!groups || groups.length === 0) {
    $list.html('<div class="cc-empty"><i class="ri-folder-open-line"></i>No care contexts found for this patient</div>');
    $('#cc_contexts_area').show();
    $('#cc_empty_state').hide();
    $('#cc_link_btn').prop('disabled', true);
    return;
  }

  groups.forEach(function(grp, gi) {
    const cfg   = HI_TYPE_CONFIG[grp.hiType] || { label: grp.hiType, icon: 'ri-file-line' };
    const count = grp.careContexts ? grp.careContexts.length : 0;
    const $grp  = $('<div class="cc-group"></div>');

    const $head = $('<div class="cc-group-head"></div>');
    const $hl   = $('<div class="cc-group-head-left"></div>');
    $hl.append(
      $('<input type="checkbox" class="cc-group-check">').attr('id', 'grp_' + gi).attr('data-group', gi),
      $('<label>').attr('for', 'grp_' + gi).css('cursor','pointer').append(
        $('<i>').addClass('cc-group-icon ' + cfg.icon),
        $('<span class="cc-group-title">').text(' ' + cfg.label),
        $('<span class="cc-group-count">').text(count),
        $('<span>').attr('style',
          'margin-left:8px;display:inline-block;padding:2px 9px;border-radius:10px;font-size:10px;font-weight:700;vertical-align:middle;color:#fff;background:'
          + (grp.linked ? '#1b7a35' : '#9e9e9e')).text(grp.linked ? 'Linked' : 'Not linked')
      )
    );
    $head.append($hl, $('<span class="cc-group-toggle">▼</span>'));
    $grp.append($head);

    const $body = $('<div class="cc-group-body open"></div>');
    (grp.careContexts || []).forEach(function(cc, ci) {
      const $item = $('<div class="cc-item"></div>');
      $item.append(
        $('<input type="checkbox" class="cc-item-check">').attr('id', 'cc_' + gi + '_' + ci)
          .attr('data-group', gi).attr('data-item', ci),
        $('<label>').attr('for', 'cc_' + gi + '_' + ci).css('cursor','pointer').append(
          $('<div class="cc-item-text">').text(cc.display || cc.referenceNumber),
          $('<div class="cc-item-ref">').text('Ref: ' + (cc.referenceNumber || '-'))
        )
      );
      $body.append($item);
    });
    $grp.append($body);
    $list.append($grp);

    // Group header toggles body visibility
    $head.on('click', function(e) {
      if ($(e.target).hasClass('cc-group-check') || $(e.target).hasClass('cc-item-check')) return;
      $body.toggleClass('open');
      $head.find('.cc-group-toggle').text($body.hasClass('open') ? '▼' : '▶');
    });
  });

  // Group checkbox → check/uncheck all items in group
  $(document).on('change', '.cc-group-check', function() {
    const gi = $(this).data('group');
    const checked = this.checked;
    $('[data-group="' + gi + '"].cc-item-check').prop('checked', checked);
    updateLinkBtn();
  });

  // Individual item → update group checkbox state + link btn
  $(document).on('change', '.cc-item-check', function() {
    const gi = $(this).data('group');
    const all  = $('[data-group="' + gi + '"].cc-item-check').length;
    const done = $('[data-group="' + gi + '"].cc-item-check:checked').length;
    $('#grp_' + gi).prop('checked', done === all).prop('indeterminate', done > 0 && done < all);
    updateLinkBtn();
  });

  $('#cc_empty_state').hide();
  $('#cc_contexts_area').show();
  updateLinkBtn();
}

function updateLinkBtn() {
  const count = $('.cc-item-check:checked').length;
  $('#cc_sel_count').text(count);
  if (count > 0) {
    $('#cc_sel_badge').addClass('visible');
    $('#cc_link_btn').prop('disabled', false);
  } else {
    $('#cc_sel_badge').removeClass('visible');
    $('#cc_link_btn').prop('disabled', true);
  }
}

/* ── Link selected care contexts ── */
$('#cc_link_btn').on('click', function() {
  if (!patientData) return;

  const abhaNumber  = (patientData.abha_number  || '').trim();
  const abhaAddress = (patientData.abha_address || '').trim();

  if (!abhaNumber || !abhaAddress) {
    alert('This patient does not have an ABHA Number and ABHA Address linked.\nPlease link ABHA first via the Create ABHA page.');
    return;
  }

  // Collect selected care contexts grouped by hiType
  const selectedGroups = [];
  careContextData.forEach(function(grp, gi) {
    const selectedItems = [];
    $('[data-group="' + gi + '"].cc-item-check:checked').each(function() {
      const ci = $(this).data('item');
      selectedItems.push(grp.careContexts[ci]);
    });
    if (selectedItems.length > 0) {
      selectedGroups.push({
        referenceNumber: grp.referenceNumber,
        display:         grp.display,
        hiType:          grp.hiType,
        careContexts:    selectedItems,
        // `count` is part of the ABDM payload and is sent by every auto-link
        // path. Rebuilt from the selection rather than copied from the group,
        // which counts the contexts offered, not the ones being linked.
        count:           ('0' + Math.min(selectedItems.length, 20)).slice(-2),
      });
    }
  });

  if (selectedGroups.length === 0) { alert('No care contexts selected.'); return; }

  // Clear previous status log
  const $log = $('#cc_status_log').html('').addClass('visible');

  showLoader();
  logStatus('<span class="log-info">▶ Obtaining ABDM access token…</span>');

  ensureAccessToken(function(token) {
    // Step 1: generate link token
    logStatus('<span class="log-info">▶ Generating link token…</span>');

    const genderMap = { Male: 'M', Female: 'F', Other: 'O', M: 'M', F: 'F', O: 'O' };
    const gender = genderMap[patientData.gender] || 'U';
    const yearOfBirth = (patientData.year_of_birth || '').toString();

    $.ajax({
      url: 'abha_integration.php',
      method: 'POST',
      contentType: 'application/json',
      dataType: 'json',
      data: JSON.stringify({
        page:        'link_token',
        accessToken: token,
        abhaNumber,
        abhaAddress,
        name:        patientData.name || '',
        gender,
        yearOfBirth,
      }),
      success: function(res) {
        // Node.js returns { success, linkToken } or { success, message, linkToken }
        const linkToken = res.linkToken || res.data?.linkToken || '';
        if (!res.success && !linkToken) {
          hideLoader();
          // ABDM-1092 means a token is already live for this ABHA — it is a
          // "wait and retry", not a configuration fault. The gateway already
          // tried to reuse the last token it was issued; raw ABDM JSON here just
          // sends the operator hunting for a problem that will clear on its own.
          const dup = res.code === 'ABDM-1092' ||
            /1092|duplicate link token/i.test(JSON.stringify(res.error || res));
          if (dup) {
            const msg = res.message ||
              'A link token is already active for this ABHA. Wait a few minutes for it to expire, then retry.';
            logStatus('<span class="log-err">✗ ' +
              $('<div>').text(msg).html() + '</span>');
            return;
          }
          logStatus('<span class="log-err">✗ Link token generation failed: ' + JSON.stringify(res.error || res) + '</span>');
          return;
        }
        if (!linkToken) {
          // ABDM webhook may be delayed — inform user
          hideLoader();
          logStatus('<span class="log-info">⏳ Link token request submitted (requestId: ' + (res.requestId || '') + '). ABDM webhook may take a moment. Please retry in a few seconds.</span>');
          return;
        }

        logStatus('<span class="log-ok">✓ Link token obtained</span>');

        // Step 2: link care contexts
        logStatus('<span class="log-info">▶ Linking ' + selectedGroups.reduce((a,g) => a + g.careContexts.length, 0) + ' care context(s)…</span>');

        $.ajax({
          url: 'abha_integration.php',
          method: 'POST',
          contentType: 'application/json',
          dataType: 'json',
          data: JSON.stringify({
            page:        'link_care_contexts',
            accessToken: token,
            linkToken,
            abhaNumber,
            abhaAddress,
            patient:     selectedGroups,
          }),
          success: function(linkRes) {
            hideLoader();
            if (linkRes.success) {
              logStatus('<span class="log-ok">✓ Care context linking request submitted to ABDM.</span>');
              logStatus('<span class="log-info">ABDM will send a confirmation to the patient\'s PHR app.</span>');
              selectedGroups.forEach(function(g) {
                logStatus('&nbsp;&nbsp;• ' + (HI_TYPE_CONFIG[g.hiType]?.label || g.hiType) + ': ' + g.careContexts.length + ' context(s)');
              });
            } else {
              logStatus('<span class="log-err">✗ Linking failed: ' + JSON.stringify(linkRes.error || linkRes) + '</span>');
            }
          },
          error: function(xhr) {
            hideLoader();
            logStatus('<span class="log-err">✗ link_care_contexts call failed: ' + xhr.responseText + '</span>');
          }
        });
      },
      error: function(xhr) {
        hideLoader();
        logStatus('<span class="log-err">✗ link_token call failed: ' + xhr.responseText + '</span>');
      }
    });
  });
});
</script>
<script src="js/field_validation.js?v=<?= @filemtime(__DIR__ . '/js/field_validation.js') ?: 1 ?>"></script>
</body>
</html>
