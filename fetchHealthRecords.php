<?php
// Milestone 3 (HIU) — staff page to request a patient's external health records
// via consent and view the returned decrypted FHIR bundles (view-only).
// session_config.php is this project's equivalent of HIMS's session_init.php: it
// forces the session save path and starts the session before $_SESSION is read.
require_once __DIR__ . '/model/session_config.php';
require_once __DIR__ . '/config/abdm_config.php';
date_default_timezone_set(env('TZ', 'Asia/Kolkata'));

// Defensively re-seed the ABDM session vars (normally set in index.php).
$_SESSION['base_url']     = $_SESSION['base_url']     ?? ABDM_BASE_URL;
$_SESSION['clientid']     = $_SESSION['clientid']     ?? ABDM_CLIENT_ID;
$_SESSION['clientSecret'] = $_SESSION['clientSecret'] ?? ABDM_CLIENT_SECRET;
$_SESSION['x_cm_id']      = $_SESSION['x_cm_id']      ?? ABDM_CM_ID;
$_SESSION['hiuId']        = $_SESSION['hiuId']        ?? ABDM_HIU_ID;
if (empty($_SESSION['abha_api'])) {
  header('Location: index.php');
  exit;
}
$defaultFrom = date('Y-m-d\T00:00', strtotime('-1 month'));
$defaultTo   = date('Y-m-d\TH:i');
$defaultErase = date('Y-m-d\TH:i', strtotime('+30 days'));
// A consent that expires the moment it is granted is useless: the patient needs
// time to approve it in their PHR app and the HIP time to push the records. Hold
// the expiry to at least MIN_EXPIRY_MINUTES from now.
const MIN_EXPIRY_MINUTES = 5;
$minErase = date('Y-m-d\TH:i', strtotime('+' . MIN_EXPIRY_MINUTES . ' minutes'));
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Fetch Health Records — HIU</title>
  <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
  <link href="./css/style.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    .content-body {
      background: #eef2fb;
      font-family: 'Inter', system-ui, sans-serif;
    }

    .cc-hero {
      background: linear-gradient(135deg, #1a3a6b 0%, #1565c0 60%, #1976d2 100%);
      border-radius: 16px;
      margin: 20px 20px 0;
      padding: 18px 32px;
    }

    .cc-hero-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: rgba(255, 255, 255, .15);
      border-radius: 20px;
      padding: 3px 12px;
      font-size: 11px;
      font-weight: 600;
      color: #bbdefb;
      margin-bottom: 6px;
    }

    .cc-hero h2 {
      color: #fff;
      margin: 4px 0 2px;
      font-size: 20px;
      font-weight: 700;
    }

    .cc-hero p {
      color: rgba(255, 255, 255, .75);
      margin: 0;
      font-size: 13px;
    }

    .cc-workspace {
      display: grid;
      /* minmax(0,…) lets both tracks actually shrink — a hard 420px track kept its
         width on a narrow screen and squeezed the fields inside it instead. */
      grid-template-columns: minmax(0, 420px) minmax(0, 1fr);
      gap: 18px;
      padding: 18px 20px 30px;
      align-items: start;
    }

    /* Below this the two columns no longer fit side by side: stack them so the
       consent form gets the full width (and its date pickers stay usable). */
    @media (max-width: 1100px) {
      .cc-workspace {
        grid-template-columns: minmax(0, 1fr);
        padding: 14px;
      }
    }

    .cc-card {
      background: #fff;
      border-radius: 14px;
      box-shadow: 0 2px 12px rgba(21, 101, 192, .08);
      overflow: hidden;
      margin-bottom: 16px;
    }

    .cc-card-head {
      background: linear-gradient(90deg, #1a3a6b, #1565c0);
      padding: 12px 18px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .cc-card-head i {
      color: #90caf9;
      font-size: 18px;
    }

    .cc-card-head span {
      color: #fff;
      font-size: 14px;
      font-weight: 600;
    }

    .cc-card-body {
      padding: 16px 18px;
    }

    .fld {
      margin-bottom: 12px;
    }

    .fld label {
      display: block;
      font-size: 11px;
      font-weight: 700;
      color: #5f6e7d;
      text-transform: uppercase;
      letter-spacing: .4px;
      margin-bottom: 4px;
    }

    .inp,
    .sel {
      width: 100%;
      border: 1.5px solid #d0d9ee;
      border-radius: 8px;
      padding: 8px 10px;
      font-size: 13px;
      color: #1a3a6b;
      background: #f7faff;
      font-family: inherit;
      outline: none;
    }

    .inp:focus,
    .sel:focus {
      border-color: #1565c0;
      background: #fff;
    }

    .row2 {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
    }

    /* A datetime-local needs roughly 165px to show "04/08/2026, 04:48 PM" AND its
       native calendar button; below that Chrome clips the button off. Give each
       field that as its basis so a pair drops onto separate lines instead of being
       squeezed, and min-width:0 so they can still shrink inside the grid. */
    .row2>* {
      flex: 1 1 165px;
      min-width: 0;
    }

    /* The native picker button must never be the thing that gets cut off. */
    input[type="datetime-local"].inp,
    input[type="date"].inp {
      padding-right: 6px;
    }

    .btn-pri {
      background: linear-gradient(135deg, #1565c0, #1976d2);
      color: #fff;
      border: none;
      border-radius: 8px;
      padding: 9px 18px;
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }

    .btn-pri:hover {
      background: linear-gradient(135deg, #0d47a1, #1565c0);
    }

    .btn-go {
      background: linear-gradient(135deg, #1b5e20, #2e7d32);
      color: #fff;
      border: none;
      border-radius: 10px;
      padding: 10px 22px;
      font-size: 14px;
      font-weight: 700;
      cursor: pointer;
      width: 100%;
    }

    .btn-go:disabled {
      background: #ccc;
      cursor: not-allowed;
    }

    .pi-row {
      display: flex;
      gap: 6px;
      padding: 6px 0;
      border-bottom: 1px solid #f0f4fb;
    }

    .pi-lbl {
      font-size: 11px;
      font-weight: 700;
      color: #888;
      min-width: 96px;
      text-transform: uppercase;
    }

    .pi-val {
      font-size: 13px;
      font-weight: 600;
      color: #1a3a6b;
      word-break: break-all;
    }

    .chip {
      display: inline-block;
      background: #e3f2fd;
      color: #1565c0;
      border-radius: 20px;
      padding: 2px 10px;
      font-size: 12px;
      font-weight: 700;
    }

    .chip-no {
      background: #fff3f3;
      color: #e57373;
    }

    .hitypes {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 4px 10px;
    }

    .hitypes label {
      font-size: 12px;
      font-weight: 500;
      color: #334;
      text-transform: none;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .status-badge {
      display: inline-block;
      padding: 3px 12px;
      border-radius: 12px;
      font-size: 12px;
      font-weight: 700;
      color: #fff;
    }

    .log {
      margin-top: 12px;
      padding: 10px 14px;
      background: #f5f8ff;
      border-radius: 8px;
      font-size: 12px;
      line-height: 1.7;
      color: #334;
      max-height: 220px;
      overflow: auto;
    }

    .log .ok {
      color: #2e7d32;
      font-weight: 700;
    }

    .log .err {
      color: #c62828;
      font-weight: 700;
    }

    .log .info {
      color: #1565c0;
    }

    .empty {
      text-align: center;
      padding: 32px 16px;
      color: #aaa;
      font-size: 13px;
    }

    .empty i {
      font-size: 36px;
      display: block;
      margin-bottom: 8px;
      color: #d0d9ee;
    }

    .grp {
      margin-bottom: 12px;
      border: 1.5px solid #e3eaf6;
      border-radius: 10px;
      overflow: hidden;
    }

    .grp-head {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 9px 14px;
      background: #f5f8ff;
      cursor: pointer;
    }

    .grp-head:hover {
      background: #e8f0fe;
    }

    .grp-title {
      font-size: 13px;
      font-weight: 700;
      color: #1a3a6b;
      flex: 1;
    }

    .grp-body {
      padding: 6px 14px 10px;
    }

    /* ── List-first page ──────────────────────────────────────────────────── */
    .cc-page {
      padding: 18px 20px 30px;
    }

    .cc-hero-row {
      display: flex;
      align-items: flex-end;
      gap: 12px;
      flex-wrap: wrap;
    }

    .cc-hero-txt {
      flex: 1;
      min-width: 0;
    }

    .btn-newc {
      width: auto;
      padding: 9px 18px;
      white-space: nowrap;
    }

    .btn-back {
      background: rgba(255, 255, 255, .18);
      border: 1px solid rgba(255, 255, 255, .5);
      color: #fff;
      border-radius: 8px;
      padding: 8px 16px;
      font-size: 13px;
      cursor: pointer;
      white-space: nowrap;
    }

    .btn-back:hover {
      background: rgba(255, 255, 255, .3);
    }

    .btn-icon {
      border: 1.5px solid #d8e2f2;
      background: #fff;
      border-radius: 8px;
      width: 34px;
      height: 34px;
      cursor: pointer;
      color: #2a63b0;
      margin-left: auto;
    }

    .btn-icon:hover {
      background: #eef4ff;
      border-color: #2a63b0;
    }

    /* ── New-consent dialog ────────────────────────────────────────────────── */
    .nc-step {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 13px;
      font-weight: 700;
      color: #1a3a6b;
      margin: 16px 0 10px;
    }

    .nc-step:first-child {
      margin-top: 0;
    }

    .nc-num {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 20px;
      height: 20px;
      border-radius: 50%;
      background: #2a63b0;
      color: #fff;
      font-size: 11px;
    }

    .nc-patient {
      background: #f5f8ff;
      border: 1px solid #e3eaf6;
      border-radius: 10px;
      padding: 8px 12px;
    }

    /* ── Toast ─────────────────────────────────────────────────────────────── */
    #toastWrap {
      position: fixed;
      right: 18px;
      bottom: 18px;
      z-index: 10001;
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .toast {
      background: #263238;
      color: #fff;
      padding: 11px 16px;
      border-radius: 9px;
      font-size: 13px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, .25);
      max-width: 340px;
      border-left: 4px solid #607d8b;
    }

    .toast.ok {
      border-left-color: #35c46a;
    }

    .toast.err {
      border-left-color: #ef5350;
    }

    .toast.info {
      border-left-color: #42a5f5;
    }

    /* ── Consents list ────────────────────────────────────────────────────── */
    /* Rows are clickable; the action buttons stop the event themselves. */
    table.ct tbody tr {
      cursor: pointer;
    }

    table.ct thead th {
      position: sticky;
      top: 0;
      background: #fff;
      z-index: 1;
    }

    .ct-scroll {
      max-height: 62vh;
      overflow-y: auto;
    }

    /* Skeleton rows while the list loads — a blank card reads as "no consents". */
    .sk {
      display: block;
      height: 11px;
      border-radius: 5px;
      background: linear-gradient(90deg, #eef2f7 25%, #e2e9f2 37%, #eef2f7 63%);
      background-size: 400% 100%;
      animation: skmove 1.2s ease-in-out infinite;
    }

    @keyframes skmove {
      0% { background-position: 100% 50%; }
      100% { background-position: 0 50%; }
    }

    .ct-toolbar {
      display: flex;
      align-items: center;
      gap: 12px;
      flex-wrap: wrap;
      margin-bottom: 12px;
    }

    .ct-search {
      flex: 0 1 280px;
      min-width: 0;
      padding: 8px 12px;
      border: 1.5px solid #d8e2f2;
      border-radius: 8px;
      font-size: 13px;
      outline: none;
    }

    .ct-search:focus {
      border-color: #2a63b0;
    }

    .ct-total {
      font-size: 13px;
      color: #52606d;
      font-weight: 600;
    }

    /* The table itself scrolls rather than the page. */
    .ct-scroll {
      overflow-x: auto;
    }

    table.ct {
      width: 100%;
      border-collapse: collapse;
      font-size: 12.5px;
      min-width: 900px;
    }

    table.ct th {
      text-align: left;
      padding: 10px 12px;
      color: #52606d;
      font-weight: 700;
      border-bottom: 2px solid #e3eaf6;
      white-space: nowrap;
    }

    table.ct td {
      padding: 10px 12px;
      border-bottom: 1px solid #eef3fa;
      color: #37474f;
      vertical-align: middle;
      white-space: nowrap;
      /* The theme upper-cases table cells. An ABHA address is shown as the
         patient registered it, so opt out. */
      text-transform: none;
    }

    table.ct tbody tr:hover {
      background: #f7faff;
    }

    .ct-abha {
      color: #2a63b0;
      font-weight: 600;
    }

    .ct-empty {
      padding: 24px 12px;
      text-align: center;
      color: #78909c;
      font-size: 13px;
    }

    .ct-act {
      border: 1.5px solid #d8e2f2;
      background: #fff;
      border-radius: 7px;
      width: 30px;
      height: 30px;
      cursor: pointer;
      color: #2a63b0;
      margin-right: 5px;
      line-height: 1;
    }

    .ct-act:hover {
      background: #eef4ff;
      border-color: #2a63b0;
    }

    .ct-act:disabled {
      opacity: .35;
      cursor: not-allowed;
    }

    .ct-foot {
      display: flex;
      align-items: center;
      justify-content: flex-end;
      gap: 14px;
      padding-top: 12px;
      font-size: 12.5px;
      color: #52606d;
      flex-wrap: wrap;
      text-transform: none;
    }

    .ct-foot select {
      padding: 4px 8px;
      border: 1.5px solid #d8e2f2;
      border-radius: 6px;
      font-size: 12.5px;
    }

    .ct-page {
      border: 1.5px solid #d8e2f2;
      background: #fff;
      border-radius: 6px;
      min-width: 28px;
      height: 28px;
      cursor: pointer;
      color: #37474f;
    }

    .ct-page.on {
      background: #2a63b0;
      border-color: #2a63b0;
      color: #fff;
      font-weight: 700;
    }

    .ct-page:disabled {
      opacity: .4;
      cursor: not-allowed;
    }

    /* ── Consent information dialog ─────────────────────────────────────── */
    .ci-back {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(15, 30, 55, .45);
      z-index: 10000;
      padding: 24px;
      overflow-y: auto;
    }

    .ci-box {
      background: #fff;
      border-radius: 12px;
      max-width: 720px;
      margin: 0 auto;
      box-shadow: 0 18px 50px rgba(0, 0, 0, .28);
    }

    .ci-head {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 16px 22px;
      border-bottom: 1px solid #e3eaf6;
    }

    .ci-head h3 {
      margin: 0;
      font-size: 17px;
      color: #1a3a6b;
    }

    .ci-x {
      border: none;
      background: none;
      font-size: 24px;
      line-height: 1;
      cursor: pointer;
      color: #78909c;
    }

    .ci-body {
      padding: 18px 22px;
      max-height: 68vh;
      overflow-y: auto;
    }

    .ci-sec {
      font-size: 14px;
      font-weight: 700;
      color: #2a63b0;
      margin: 18px 0 10px;
    }

    .ci-sec:first-child {
      margin-top: 0;
    }

    .ci-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px 20px;
    }

    @media (max-width:560px) {
      .ci-grid {
        grid-template-columns: 1fr;
      }
    }

    .ci-lbl {
      font-size: 11px;
      color: #78909c;
      margin-bottom: 2px;
    }

    .ci-val {
      font-size: 13px;
      color: #263238;
      word-break: break-word;
    }

    .ci-hr {
      border: none;
      border-top: 1px solid #eef3fa;
      margin: 16px 0 0;
    }

    .ci-foot {
      display: flex;
      justify-content: flex-end;
      gap: 10px;
      padding: 14px 22px;
      border-top: 1px solid #e3eaf6;
    }

    .ci-btn {
      padding: 8px 16px;
      border-radius: 8px;
      border: 1.5px solid #d8e2f2;
      background: #fff;
      color: #37474f;
      font-size: 13px;
      cursor: pointer;
    }

    .ci-btn.pri {
      background: #2a63b0;
      border-color: #2a63b0;
      color: #fff;
      font-weight: 600;
    }

    .ci-btn:hover {
      filter: brightness(.97);
    }

    .ci-note {
      font-size: 11.5px;
      color: #8a6d00;
      background: #fff8e6;
      border: 1px solid #f0e0b0;
      border-radius: 6px;
      padding: 6px 9px;
      margin-top: 10px;
    }

    #loader {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, .35);
      z-index: 9999;
      align-items: center;
      justify-content: center;
    }

    #loader.show {
      display: flex;
    }

    .spin {
      width: 44px;
      height: 44px;
      border: 4px solid rgba(255, 255, 255, .3);
      border-top-color: #fff;
      border-radius: 50%;
      animation: spin .7s linear infinite;
    }

    @keyframes spin {
      to {
        transform: rotate(360deg);
      }
    }

    /* ── Record viewer (PHR-style cards) ─────────────────────────────────── */
    .rec-svg {
      flex: 0 0 auto;
      vertical-align: middle;
    }

    .rec-card {
      background: #fff;
      border-radius: 14px;
      box-shadow: 0 2px 14px rgba(16, 60, 106, .09);
      overflow: hidden;
      margin-bottom: 18px;
    }

    .rec-head {
      background: linear-gradient(135deg, #10907c 0%, #0a7565 100%);
      padding: 14px 18px;
      display: flex;
      align-items: flex-start;
      gap: 12px;
    }

    .rec-head-main {
      flex: 1;
      min-width: 0;
    }

    .rec-head-title {
      display: flex;
      align-items: center;
      gap: 9px;
      color: #fff;
      font-size: 15px;
      font-weight: 700;
      line-height: 1.3;
    }

    .rec-head-title .rec-svg {
      color: rgba(255, 255, 255, .85);
    }

    .rec-head-sub {
      color: rgba(255, 255, 255, .72);
      font-size: 11.5px;
      line-height: 1.55;
      margin-left: 25px;
      word-break: break-all;
    }

    .rec-date-pill {
      background: rgba(255, 255, 255, .18);
      color: #fff;
      font-size: 11.5px;
      font-weight: 600;
      padding: 4px 12px;
      border-radius: 14px;
      white-space: nowrap;
    }

    .rec-warn {
      background: #fff8e8;
      color: #8a5a00;
      font-size: 12px;
      font-weight: 600;
      padding: 8px 18px;
      border-bottom: 1px solid #f4e5c2;
    }

    .rec-meta {
      background: #f7f8fb;
      padding: 13px 18px;
      display: flex;
      flex-direction: column;
      gap: 11px;
    }

    .rec-meta-row {
      display: flex;
      align-items: flex-start;
      gap: 11px;
    }

    .rec-ic {
      width: 30px;
      height: 30px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      flex: 0 0 30px;
      margin-top: 2px;
    }

    .ic-pat {
      background: #e7eefd;
      color: #4a72e8;
    }

    .ic-doc {
      background: #f4e9fd;
      color: #9257d4;
    }

    .ic-fac {
      background: #fdeadf;
      color: #ef7b3a;
    }

    .rec-meta-lbl {
      font-size: 10.5px;
      color: #9aa4b2;
      line-height: 1.4;
    }

    .rec-meta-val {
      font-size: 14px;
      font-weight: 700;
      color: #1f2a37;
      line-height: 1.35;
      word-break: break-word;
    }

    .rec-meta-sub {
      font-size: 11.5px;
      color: #8b95a6;
      line-height: 1.4;
    }

    .rec-sec {
      border-top: 1px solid #eef1f5;
    }

    .rec-sec-head {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 12px 18px;
      cursor: pointer;
      user-select: none;
    }

    .rec-sec-head:hover {
      background: #fafbfd;
    }

    .rec-sec-icon {
      color: #10907c;
      display: flex;
    }

    .rec-sec-title {
      flex: 1;
      font-size: 13px;
      font-weight: 600;
      color: #3c4757;
    }

    .rec-sec-count {
      background: #eef1f5;
      color: #5b6675;
      font-size: 11px;
      font-weight: 700;
      border-radius: 10px;
      padding: 1px 8px;
    }

    .rec-chev {
      color: #b6bfcc;
      display: flex;
      transition: transform .15s ease;
    }

    .rec-sec.closed .rec-chev {
      transform: rotate(-90deg);
    }

    .rec-sec-body {
      padding: 0 14px 12px;
    }

    .rec-item {
      border: 1px solid #edf0f4;
      border-radius: 9px;
      padding: 11px 13px;
      margin-bottom: 8px;
    }

    .rec-item:last-child {
      margin-bottom: 0;
    }

    .rec-item-title {
      font-size: 13.5px;
      font-weight: 700;
      color: #1f2a37;
      line-height: 1.4;
      word-break: break-word;
    }

    .rec-item-line {
      font-size: 12.5px;
      color: #6a7580;
      margin-top: 3px;
      line-height: 1.5;
      word-break: break-word;
    }

    /* Referenced by the sender but absent from the bundle — greyed and italic so
       it reads as a gap in the record, not as recorded clinical content. */
    .rec-item-missing .rec-item-title {
      color: #93a1b0;
      font-weight: 600;
    }

    .rec-item-missing .rec-item-line {
      font-style: italic;
      color: #93a1b0;
    }

    .rec-item-when {
      font-size: 11.5px;
      color: #b3bcc6;
      margin-top: 6px;
    }

    .rec-narr {
      font-size: 12.5px;
      color: #4a5561;
      line-height: 1.6;
      white-space: pre-wrap;
      word-break: break-word;
    }

    .rec-docrow {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 10px;
      border: 1px solid #edf0f4;
      border-radius: 9px;
      padding: 11px 13px;
      margin-bottom: 8px;
    }

    .rec-docrow:last-child {
      margin-bottom: 0;
    }

    .rec-doc-lbl {
      font-size: 12.5px;
      color: #3c4757;
      word-break: break-word;
    }

    .rec-dl {
      color: #10907c;
      font-size: 12.5px;
      font-weight: 600;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 5px;
      white-space: nowrap;
      text-decoration: none;
    }

    .rec-dl:hover {
      color: #0a7565;
      text-decoration: none;
    }

    .rec-sec-empty {
      font-size: 12px;
      color: #aab3bf;
      padding: 2px 0 4px;
    }

    .rec-foot {
      display: flex;
      align-items: center;
      gap: 12px;
      border-top: 1px solid #eef1f5;
      padding: 8px 18px;
    }

    .rec-counts {
      font-size: 10.5px;
      color: #aab3bf;
      flex: 1;
      word-break: break-word;
    }

    .rec-total {
      font-size: 12px;
      font-weight: 700;
      color: #5f6e7d;
      text-transform: uppercase;
      letter-spacing: .4px;
      margin: 2px 0 10px;
    }

    /* "· 3 duplicate copies hidden" — secondary to the record count. */
    .rec-total-note {
      font-weight: 600;
      color: #93a1b0;
      text-transform: none;
      letter-spacing: 0;
    }
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
  <div id="loader">
    <div class="spin"></div>
  </div>
  <div id="toastWrap"></div>

  <!-- New consent dialog: find the patient, then request consent. Everything
       here used to sit permanently in the left rail; the list is the page now. -->
  <div class="ci-back" id="ncBack">
    <div class="ci-box">
      <div class="ci-head">
        <h3>New consent request</h3>
        <button class="ci-x" id="ncClose" title="Close">&times;</button>
      </div>
      <div class="ci-body">
        <div class="nc-step"><span class="nc-num">1</span> Find the patient</div>
        <div class="row2" style="margin-bottom:8px;">
          <select id="idType" class="sel" style="flex:0 0 130px;">
            <option value="abha_address">ABHA Address</option>
            <option value="abha_number">ABHA Number</option>
            <option value="mobile">Mobile</option>
            <option value="opd_no">OPD No</option>
            <option value="ipd_no">IPD No</option>
          </select>
          <div id="idWrap" style="flex:1;display:flex;align-items:stretch;">
            <input type="text" id="identifier" class="inp" style="flex:1;min-width:0;" placeholder="e.g. name">
            <span id="idSuffix" style="display:inline-flex;align-items:center;padding:0 12px;background:#eef1f5;border:1px solid #d9dee6;border-left:0;border-radius:0 6px 6px 0;color:#555;font-weight:700;white-space:nowrap;">@<?php echo htmlspecialchars(ABDM_CM_ID); ?></span>
          </div>
        </div>
        <button class="btn-pri" id="searchBtn"><i class="ri-search-line"></i> Search</button>
        <div id="searchErr" style="margin-top:8px;font-size:12px;color:#e53e3e;display:none;"></div>

        <div id="patientCard" style="display:none;margin-top:14px;">
          <div class="nc-patient">
            <div class="pi-row"><span class="pi-lbl">Name</span><span class="pi-val" id="pName">-</span></div>
            <div class="pi-row"><span class="pi-lbl">Gender</span><span class="pi-val" id="pGender">-</span></div>
            <div class="pi-row"><span class="pi-lbl">ABHA Addr</span><span class="pi-val" id="pAddr">-</span></div>
            <div class="pi-row"><span class="pi-lbl">ABHA No</span><span class="pi-val" id="pNum">-</span></div>
          </div>
        </div>

        <div id="consentCard" style="display:none;">
          <div class="nc-step"><span class="nc-num">2</span> Consent terms</div>
          <div class="fld">
            <label style="display:flex;align-items:center;justify-content:space-between;">HI Types
              <span style="font-weight:400;font-size:12px;cursor:pointer;"><input type="checkbox" id="hiSelectAll"> Select All</span>
            </label>
            <div class="hitypes" id="hiTypes"></div>
          </div>
          <div class="fld row2">
            <div><label>From</label><input type="datetime-local" id="fromDate" class="inp" value="<?= $defaultFrom ?>"></div>
            <div><label>To</label><input type="datetime-local" id="toDate" class="inp" value="<?= $defaultTo ?>"></div>
          </div>
          <div class="fld row2">
            <div>
              <label>Purpose</label>
              <select id="purpose" class="sel">
                <option value="CAREMGT|Care Management">Care Management</option>
                <option value="BTG|Break the Glass">Break the Glass</option>
                <option value="PUBHLTH|Public Health">Public Health</option>
                <option value="HPAYMT|Healthcare Payment">Healthcare Payment</option>
                <option value="DSRCH|Disease Specific Research">Disease Specific Research</option>
              </select>
            </div>
            <div><label>Expiry Date</label><input type="datetime-local" id="eraseDate" class="inp" value="<?= $defaultErase ?>" min="<?= $minErase ?>" title="At least <?= MIN_EXPIRY_MINUTES ?> minutes from now"></div>
          </div>
        </div>
      </div>
      <div class="ci-foot">
        <button class="ci-btn" id="ncCloseBtn">Cancel</button>
        <button class="btn-go" id="consentBtn" style="width:auto;padding:8px 18px;">Request Consent</button>
      </div>
    </div>
  </div>

  <!-- Consent information dialog (opened from the Consents list) -->
  <div class="ci-back" id="ciBack">
    <div class="ci-box">
      <div class="ci-head">
        <h3>Consent information</h3>
        <button class="ci-x" id="ciClose" title="Close">&times;</button>
      </div>
      <div class="ci-body" id="ciBody"></div>
      <div class="ci-foot">
        <button class="ci-btn" id="ciCloseBtn">Close</button>
        <button class="ci-btn" id="ciRefresh">Refresh</button>
        <button class="ci-btn pri" id="ciView">View Records</button>
      </div>
    </div>
  </div>
  <div id="main-wrapper">
    <?php include('leftbar.php'); ?>
    <div class="content-body">
      <div class="cc-hero">
        <div class="cc-hero-badge"><i class="ri-download-cloud-line"></i> ABDM — HIU Consent &amp; Fetch</div>
        <div class="cc-hero-row">
          <div class="cc-hero-txt">
            <h2 id="pageTitle">Consents</h2>
            <p id="pageSub">Request a patient's records from other facilities via ABDM consent, then view the returned data.</p>
          </div>
          <button class="btn-back" id="backToList" style="display:none;"><i class="ri-arrow-left-line"></i> Back</button>
          <button class="btn-go btn-newc" id="newConsentBtn"><i class="ri-add-line"></i> Consent</button>
        </div>
      </div>

      <div class="cc-page">
        <!-- The consents list is the page. -->
        <div id="listPane">
          <div class="cc-card" id="consentHistoryCard">
            <div class="cc-card-body">
              <div class="ct-toolbar">
                <input type="text" id="ctSearch" class="ct-search" placeholder="Search ABHA Address">
                <span class="ct-total" id="ctTotal"></span>
                <button class="btn-icon" id="ctReload" title="Reload consents"><i class="ri-refresh-line"></i></button>
              </div>
              <div class="ct-scroll">
                <table class="ct">
                  <thead>
                    <tr>
                      <th>S.No</th>
                      <th>ABHA Address</th>
                      <th>Status</th>
                      <th>Purpose</th>
                      <th>Requested On</th>
                      <th>Approved On</th>
                      <th>Expiry</th>
                      <th>Grant / Revoke / Deny</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody id="consentHistoryBody"></tbody>
                </table>
              </div>
              <div class="ct-foot" id="ctFoot"></div>
            </div>
          </div>
        </div>

        <!-- Records for one consent — opened from a row, hidden until then. -->
        <div id="recordsPane" style="display:none;">
          <div id="cachedArea"></div>
          <div class="cc-card">
            <div class="cc-card-head"><i class="ri-file-list-3-line"></i><span>Consent &amp; Records</span></div>
            <div class="cc-card-body">
              <div class="empty" id="emptyState"><i class="ri-search-2-line"></i>Open a granted consent to view its records.</div>
              <div id="statusArea" style="display:none;">
                <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                  <span style="font-size:12px;font-weight:700;color:#5f6e7d;text-transform:uppercase;">Status</span>
                  <span class="status-badge" id="statusBadge" style="background:#9e9e9e;">—</span>
                  <span style="font-size:11px;color:#8a97a6;" id="reqIdLbl"></span>
                  <button class="btn-pri" id="refreshBtn" style="margin-left:auto;padding:5px 12px;font-size:12px;display:none;"><i class="ri-refresh-line"></i> Refresh</button>
                  <button class="btn-go" id="fetchBtn" style="width:auto;padding:7px 16px;display:none;">Fetch Records</button>
                </div>
                <div class="log" id="log"></div>
              </div>
              <div id="results" style="margin-top:14px;"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="./vendor/global/global.min.js"></script>
  <script src="./js/quixnav-init.js"></script>
  <script src="./js/custom.min.js"></script>
  <script>
    const HI_TYPES = ['OPConsultation', 'Prescription', 'DiagnosticReport', 'DischargeSummary', 'ImmunizationRecord', 'WellnessRecord', 'HealthDocumentRecord', 'Invoice'];
    let patient = null,
      accessToken = '',
      requestId = '',
      consentId = '',
      statusTimer = null,
      dataTimer = null,
      statusTries = 0,
      dataTries = 0,
      viewFrom = '', // date range of the consent currently being viewed (for retry)
      viewTo = '',
      pendingCoverageWarning = null; // "0 care-contexts" notice, held until proven

    // ── Consent expiry floor ──────────────────────────────────────────────────
    // The expiry (dataEraseAt) must leave enough time for the patient to approve
    // the request and the source facility to push data. Anything sooner produces a
    // consent that is already dead by the time it is granted.
    const MIN_EXPIRY_MINUTES = <?= (int) MIN_EXPIRY_MINUTES ?>;

    // Earliest acceptable expiry, in ms. "Now" is floored to the minute first:
    // a datetime-local picker has minute granularity, so at 10:00:30 the operator
    // picking 10:05 means 10:05:00 — comparing against 10:05:30 would reject a
    // choice that is exactly MIN_EXPIRY_MINUTES away.
    function minExpiryMs() {
      const t = new Date();
      t.setSeconds(0, 0);
      return t.getTime() + MIN_EXPIRY_MINUTES * 60000;
    }

    function toLocalInputValue(ms) {
      const d = new Date(ms);
      const pad = n => ('0' + n).slice(-2);
      return d.getFullYear() + '-' + pad(d.getMonth() + 1) + '-' + pad(d.getDate()) +
        'T' + pad(d.getHours()) + ':' + pad(d.getMinutes());
    }

    // Re-floor the picker. The page is often left open between patients, so the
    // server-rendered min goes stale and the browser would accept a past time.
    function refreshEraseMin() {
      const floor = minExpiryMs();
      $('#eraseDate').attr('min', toLocalInputValue(floor));
      const cur = $('#eraseDate').val();
      if (cur && new Date(cur).getTime() < floor) {
        $('#eraseDate').val(toLocalInputValue(floor));
      }
    }
    $(document).on('focus change', '#eraseDate', refreshEraseMin);

    function showLoader() {
      $('#loader').addClass('show');
    }

    function hideLoader() {
      $('#loader').removeClass('show');
    }

    function log(html) {
      const el = $('#log');
      el.append(html + '<br>');
      el.scrollTop(el[0].scrollHeight);
    }

    function esc(s) {
      return $('<div>').text(s == null ? '' : s).html();
    }

    // Build HI type checkboxes
    HI_TYPES.forEach(function(t) {
      $('#hiTypes').append('<label><input type="checkbox" class="hiChk" value="' + t + '"' + (t === 'Prescription' ? ' checked' : '') + '> ' + t + '</label>');
    });

    // "Select All" toggles every HI type; keep it in sync with the individual boxes
    // (checked when all are, indeterminate when only some are).
    function syncHiSelectAll() {
      var total = $('.hiChk').length,
        checked = $('.hiChk:checked').length;
      $('#hiSelectAll').prop('checked', total > 0 && checked === total)
        .prop('indeterminate', checked > 0 && checked < total);
    }
    $('#hiSelectAll').on('change', function() {
      $('.hiChk').prop('checked', $(this).is(':checked'));
    });
    $(document).on('change', '.hiChk', syncHiSelectAll);
    syncHiSelectAll();

    // Reuse the M2 token endpoint (get_token) verbatim.
    function ensureToken(cb) {
      if (accessToken) {
        cb(accessToken);
        return;
      }
      $.post('abha_integration.php', {
        page: 'get_token'
      }, function(res) {
        const d = typeof res === 'string' ? JSON.parse(res) : res;
        const t = d?.data?.accessToken || d?.accessToken || '';
        if (!t) {
          alert('Failed to obtain ABDM token. Re-login and try again.');
          return;
        }
        accessToken = t;
        cb(t);
      }).fail(function() {
        alert('Token request failed.');
      });
    }

    // ABHA-address domain for this environment (@sbx sandbox / @abdm prod), from .env.
    const ABHA_DOMAIN = <?php echo json_encode(ABDM_CM_ID); ?>;

    // ── Search (reuses M2 get_patient_care_contexts; we only need the patient) ──
    $('#searchBtn').on('click', function() {
      let identifier = $('#identifier').val().trim();
      const identifier_type = $('#idType').val();
      if (!identifier) {
        $('#searchErr').text('Enter a search value.').show();
        return;
      }
      // M3-001: validate ABHA format up-front with a specific message (rather
      // than a generic "Patient not found" after a pointless server round-trip).
      if (identifier_type === 'abha_number') {
        if (identifier.replace(/\D/g, '').length !== 14) {
          $('#searchErr').text('Invalid ABHA Number — it must be 14 digits (e.g. 91-1234-5678-9012).').show();
          return;
        }
      } else if (identifier_type === 'abha_address') {
        // The "@sbx"/"@abdm" domain is fixed at the input's end, so the user types
        // only the username. Append this environment's domain for the actual search
        // (unless they already typed a full "user@domain"). Keep the field untouched.
        identifier = identifier.toLowerCase();
        if (identifier.indexOf('@') === -1) {
          identifier = identifier + '@' + ABHA_DOMAIN;
        }
        if (!/^[a-zA-Z0-9._-]+@[a-zA-Z0-9]+$/.test(identifier)) {
          $('#searchErr').text('Invalid ABHA Address — enter a valid username (e.g. name).').show();
          return;
        }
      }
      $('#searchErr').hide();
      showLoader();
      $.ajax({
        url: 'abha_integration.php',
        method: 'POST',
        contentType: 'application/json',
        dataType: 'json',
        data: JSON.stringify({
          page: 'get_patient_care_contexts',
          identifier,
          identifier_type
        }),
        success: function(res) {
          hideLoader();
          if (!res.success) {
            $('#searchErr').text(res.error || 'Patient not found.').show();
            return;
          }
          renderPatient(res.patient);
        },
        error: function(x) {
          hideLoader();
          $('#searchErr').text('Search failed: ' + (x.responseText || 'error')).show();
        }
      });
    });
    $('#identifier').on('keydown', e => {
      if (e.key === 'Enter') $('#searchBtn').click();
    });
    // Show the "@sbx"/"@abdm" suffix only for ABHA Address; the user types just the
    // username and the domain is fixed at the input's end.
    function updateIdType() {
      const t = $('#idType').val();
      const isAddr = (t === 'abha_address');
      $('#idSuffix').toggle(isAddr);
      $('#identifier').css({
        'border-top-right-radius': isAddr ? '0' : '',
        'border-bottom-right-radius': isAddr ? '0' : ''
      });
      const h = {
        abha_address: 'e.g. name',
        abha_number: 'e.g. 91-1234-5678-9012',
        mobile: 'e.g. 9876543210',
        opd_no: 'e.g. OP10045',
        ipd_no: 'e.g. IP10045'
      };
      $('#identifier').attr('placeholder', h[t] || '');
    }
    $('#idType').on('change', updateIdType);
    updateIdType();

    // Tear down any in-flight consent/data flow from a previously-searched
    // patient so switching patients starts clean without a full page refresh.
    function resetFlow() {
      clearInterval(statusTimer);
      clearInterval(dataTimer);
      statusTimer = dataTimer = null;
      requestId = consentId = '';
      viewFrom = viewTo = '';
      pendingCoverageWarning = null;
      statusTries = dataTries = 0;
      $('#statusArea').hide();
      $('#results').empty();
      $('#log').empty();
      $('#reqIdLbl').text('');
      $('#fetchBtn').hide().prop('disabled', false);
      $('#refreshBtn').hide();
      setStatus('—', '#9e9e9e');
      $('#cachedArea').empty();
      $('#consentHistoryBody').empty();
      $('#consentHistoryCard').hide();
      $('#emptyState').show();
    }

    function renderPatient(p) {
      resetFlow();
      patient = p;
      const g = {
        M: 'Male',
        F: 'Female',
        O: 'Other',
        m: 'Male',
        f: 'Female'
      };
      $('#pName').text(p.name || '-');
      $('#pGender').text(g[p.gender] || p.gender || '-');
      const addr = (p.abha_address || '').trim(),
        num = (p.abha_number || '').trim();
      $('#pAddr').html(addr ? '<span class="chip">' + esc(addr) + '</span>' : '<span class="chip chip-no">Not linked</span>');
      $('#pNum').html(num ? '<span class="chip">' + esc(num) + '</span>' : '<span class="chip chip-no">Not linked</span>');
      $('#patientCard').show();
      if (addr) {
        $('#consentCard').show();
        // Scope the list behind the dialog to this patient, so closing it lands
        // on their consents rather than everyone's.
        consentListFilter = addr;
        loadConsentHistory(addr, { quiet: true });
      } else {
        $('#consentCard').hide();
        $('#searchErr').text('Patient has no ABHA address — cannot request records.').show();
      }
    }

    // ── Consent request history (M3-004/006/008) ──
    // dd/mm/yyyy, hh:mm AM/PM. toLocaleString() follows the BROWSER's locale, so the
    // same consent read as "8/4/2026" on one machine and "04/08/2026" on another —
    // ambiguous for a date that decides when a consent lapses. Pin the format.
    // The time is kept because these values are time-of-day sensitive (the expiry
    // must be at least MIN_EXPIRY_MINUTES away, and the range bounds an HI request).
    function fmtDt(v) {
      if (!v) return '—';
      const d = new Date(v);
      if (isNaN(d.getTime())) return '—';
      const pad = n => ('0' + n).slice(-2);
      let h = d.getHours();
      const ap = h >= 12 ? 'PM' : 'AM';
      h = h % 12 || 12;
      return pad(d.getDate()) + '/' + pad(d.getMonth() + 1) + '/' + d.getFullYear() +
        ', ' + pad(h) + ':' + pad(d.getMinutes()) + ' ' + ap;
    }

    function statusColor(s) {
      s = (s || '').toUpperCase();
      if (s === 'GRANTED') return '#1b7a35';
      if (['DENIED', 'REVOKED', 'EXPIRED', 'ERRORED'].indexOf(s) !== -1) return '#c62828';
      if (s === 'REQUESTED') return '#1565c0';
      return '#9e9e9e';
    }

    // The consents list is the page, so it loads for EVERY patient on arrival —
    // an empty abhaAddress lists them all. Passing one narrows the list to that
    // patient, which is what happens right after a consent is requested.
    let consentLoading = false;
    let consentQueued = null;   // a reload asked for while one was in flight
    let consentSeq = 0;         // only the newest response may paint the table

    function loadConsentHistory(abha, opts) {
      opts = opts || {};
      // A reload asked for mid-flight used to be dropped on the floor, which is
      // how a freshly created consent could fail to appear: the patient search
      // in the dialog starts a load, and the post-create refresh lands on top of
      // it. Remember it instead and run it as soon as the current one finishes.
      if (consentLoading) {
        consentQueued = { abha: abha, opts: opts };
        return;
      }
      consentLoading = true;
      const seq = ++consentSeq;
      if (!opts.quiet) showConsentSkeleton();
      $.ajax({
        url: 'hiu_integration.php',
        method: 'POST',
        contentType: 'application/json',
        dataType: 'json',
        data: JSON.stringify({
          page: 'hiu_consent_list',
          abhaAddress: abha || ''
        }),
        success: function(r) {
          // A slower earlier response must never overwrite a newer list.
          if (seq !== consentSeq) return;
          if (r && r.success) {
            renderConsentHistory(r.data || []);
          } else {
            renderConsentHistory([]);
            toast((r && r.error) || 'Could not load consents.', 'err');
          }
        },
        error: function() {
          if (seq !== consentSeq) return;
          renderConsentHistory([]);
          toast('Could not reach the consent service.', 'err');
        },
        complete: function() {
          consentLoading = false;
          const q = consentQueued;
          consentQueued = null;
          if (q) loadConsentHistory(q.abha, q.opts);
        }
      });
    }

    // Refresh right after a consent is created. The gateway stores the row
    // before it calls ABDM, so the first read always shows it — but ABDM's
    // on-init callback (which fills in consentRequestId) lands a moment later,
    // so read again shortly after to pick that up.
    function refreshConsentsAfterCreate() {
      loadConsentHistory(consentListFilter);
      setTimeout(function() {
        loadConsentHistory(consentListFilter, { quiet: true });
      }, 3000);
    }

    // A blank card reads as "this patient has no consents"; show the shape of
    // the table instead while it loads.
    function showConsentSkeleton() {
      const $b = $('#consentHistoryBody').empty();
      for (let r = 0; r < 5; r++) {
        let tds = '';
        for (let c = 0; c < 9; c++) {
          tds += '<td><span class="sk" style="width:' + (c === 0 ? 24 : (c === 1 ? 130 : 82)) + 'px"></span></td>';
        }
        $b.append('<tr>' + tds + '</tr>');
      }
      $('#ctTotal').text('Loading…');
      $('#ctFoot').empty();
    }

    function toast(msg, kind) {
      const $t = $('<div class="toast"></div>').addClass(kind || 'info').text(msg);
      $('#toastWrap').append($t);
      setTimeout(function() {
        $t.fadeOut(250, function() { $t.remove(); });
      }, kind === 'err' ? 6000 : 3500);
    }

    // ── Consents list ────────────────────────────────────────────────────────
    // Rows exactly as the gateway returned them, plus the list's own view state.
    let CONSENT_ROWS = [];
    let ctQuery = '';
    let ctPage = 1;
    let ctRows = 15;
    let ciCurrent = null;

    const PURPOSE_TEXT = {
      CAREMGT: 'Care Management',
      BTG: 'Break the Glass',
      PUBHLTH: 'Public Health',
      HPAYMT: 'Healthcare Payment',
      DSRCH: 'Disease Specific Healthcare Research',
      PATRQT: 'Self Requested'
    };

    // Everything the list and the dialog need about one consent, resolved once.
    //
    // The patient can EDIT the request while granting it in their PHR app —
    // narrowing the date range, shortening the expiry, dropping HI types. Those
    // values live on the artefact (c.granted); the request row only records what
    // we ASKED for. Show what was actually granted, and keep the requested values
    // so the dialog can say what changed.
    //
    // ABDM never pushes a status change when a consent simply lapses, so a stored
    // status stays as it was forever. Once the effective expiry has passed the
    // consent is dead whichever way it was heading, so a still-live status reads
    // EXPIRED — GRANTED (otherwise it keeps offering records that fetch nothing)
    // and REQUESTED (a pending request that can no longer be granted in time).
    // Terminal outcomes keep their own status: HOW a consent ended is more
    // informative than the fact it later lapsed.
    function consentView(c) {
      const stored = (c.status || 'UNKNOWN').toUpperCase();
      const g = c.granted || {};
      const t = c.transfer || null;
      const init = (c.initRequestBody && c.initRequestBody.consent) || {};
      const purpose = init.purpose || {};
      const requester = init.requester || {};
      const permission = init.permission || {};

      const effFrom = g.fromDate || c.fromDate;
      const effTo = g.toDate || c.toDate;
      const effErase = g.dataEraseAt || c.dataEraseAt;
      const effHiTypes = (g.hiTypes && g.hiTypes.length) ? g.hiTypes : c.hiTypes;

      const LIVE = ['GRANTED', 'REQUESTED'];
      const eraseAt = effErase ? new Date(effErase) : null;
      const lapsed = LIVE.indexOf(stored) !== -1 && eraseAt &&
        !isNaN(eraseAt.getTime()) && eraseAt.getTime() <= Date.now();

      const code = c.purposeCode || purpose.code || '';
      return {
        raw: c,
        stored: stored,
        status: lapsed ? 'EXPIRED' : stored,
        lapsed: lapsed,
        abha: c.patientAbhaAddress || '',
        name: c.patientName || '',
        abhaNumber: c.patientAbhaNumber || '',
        purposeCode: code,
        purposeText: purpose.text || PURPOSE_TEXT[code] || '',
        purposeRef: purpose.refUri || '',
        requester: requester.name || '',
        accessMode: g.accessMode || permission.accessMode || '',
        requestedAt: c.createdAt,
        // "Approved consent time" — when the artefact reached us. Falls back to
        // the status transition for rows stored before the gateway sent it.
        approvedAt: g.approvedAt || (stored === 'GRANTED' ? c.statusChangedAt : null),
        statusChangedAt: c.statusChangedAt,
        reqFrom: c.fromDate,
        reqTo: c.toDate,
        reqErase: c.dataEraseAt,
        reqHiTypes: c.hiTypes,
        from: effFrom,
        to: effTo,
        erase: effErase,
        hiTypes: effHiTypes,
        consentId: g.consentId || '',
        // A grant notification often carries only the artefact id. Until the
        // artefact detail is fetched from ABDM its `permission` is empty, so the
        // patient's real terms are simply unknown — and echoing the REQUESTED
        // values under an "Approved" heading is worse than saying so. Anything
        // downstream that needs the granted terms must check this first.
        hasGrantedTerms: !!(g.fromDate || g.toDate || g.dataEraseAt),
        artefactFetched: g.fetched === true,
        careContexts: g.careContexts,
        hipId: g.hipId || '',
        transfer: t,
        updatedAt: c.updatedAt,
        // The dialog flags terms the patient altered while granting. Only a
        // fetched artefact can tell us that — gate on the terms being present,
        // not merely on an artefact id existing.
        hiChanged: !!(g.hiTypes && g.hiTypes.length) && listHi(effHiTypes) !== listHi(c.hiTypes),
        rangeChanged: !!(g.fromDate || g.toDate) &&
          (String(effFrom || '') !== String(c.fromDate || '') ||
            String(effTo || '') !== String(c.toDate || '')),
        eraseChanged: !!g.dataEraseAt && String(effErase || '') !== String(c.dataEraseAt || '')
      };
    }

    function listHi(v) {
      return Array.isArray(v) ? v.join(', ') : (v || '');
    }

    function renderConsentHistory(rows) {
      CONSENT_ROWS = (rows || []).map(consentView);
      // The card is the page now, so it always stays up — an empty list gets an
      // empty state rather than disappearing.
      $('#consentHistoryCard').show();
      ctPage = 1;
      renderConsentTable();
      schedulePendingRefresh();
    }

    // A REQUESTED consent changes when the patient acts in their PHR app, and
    // nothing tells this page about it. Poll while any row is still pending, and
    // stop as soon as none are — an idle list must not keep hitting the gateway.
    let pendingTimer = null;

    function schedulePendingRefresh() {
      if (pendingTimer) {
        clearTimeout(pendingTimer);
        pendingTimer = null;
      }
      const pending = CONSENT_ROWS.some(function(v) {
        return v.status === 'REQUESTED';
      });
      if (!pending) return;
      pendingTimer = setTimeout(function() {
        // Quiet: no skeleton, so the list does not flash every 20 seconds.
        loadConsentHistory(consentListFilter, { quiet: true });
      }, 20000);
    }

    function consentMatches(v, q) {
      if (!q) return true;
      q = q.toLowerCase();
      return (v.abha || '').toLowerCase().indexOf(q) !== -1 ||
        (v.name || '').toLowerCase().indexOf(q) !== -1 ||
        (v.abhaNumber || '').toLowerCase().indexOf(q) !== -1;
    }

    function renderConsentTable() {
      const all = CONSENT_ROWS.filter(function(v) {
        return consentMatches(v, ctQuery);
      });
      const total = all.length;
      const pages = Math.max(1, Math.ceil(total / ctRows));
      if (ctPage > pages) ctPage = pages;
      const start = (ctPage - 1) * ctRows;
      const page = all.slice(start, start + ctRows);

      $('#ctTotal').text('Total Records: ' + total);

      const $b = $('#consentHistoryBody').empty();
      if (!total) {
        // "Nothing here yet" and "your search matched nothing" are different
        // problems and need different next steps.
        $b.append(CONSENT_ROWS.length
          ? '<tr><td colspan="9" class="ct-empty">No consents match &ldquo;' + esc(ctQuery) + '&rdquo;.</td></tr>'
          : '<tr><td colspan="9" class="ct-empty">No consent requests yet.<br>' +
            'Use <b>+ Consent</b> to request a patient’s records.</td></tr>');
        $('#ctFoot').empty();
        return;
      }

      page.forEach(function(v, i) {
        const idx = CONSENT_ROWS.indexOf(v);
        const $tr = $('<tr></tr>');
        $tr.append('<td>' + (start + i + 1) + '</td>');
        $tr.append('<td class="ct-abha">' + esc(v.abha || '—') + '</td>');
        $tr.append('<td><span class="status-badge" style="background:' +
          statusColor(v.status) + ';">' + esc(v.status) + '</span></td>');
        $tr.append('<td>' + esc(v.purposeText || v.purposeCode || '—') + '</td>');
        $tr.append('<td>' + esc(fmtDt(v.requestedAt)) + '</td>');
        $tr.append('<td>' + esc(v.approvedAt ? fmtDt(v.approvedAt) : '-') + '</td>');
        $tr.append('<td>' + esc(fmtDt(v.erase)) + '</td>');
        $tr.append('<td>' + esc(v.statusChangedAt ? fmtDt(v.statusChangedAt) : '-') + '</td>');

        const $act = $('<td></td>');
        const $info = $('<button class="ct-act" title="Consent information">&#9432;</button>');
        $info.on('click', function(e) { e.stopPropagation(); openConsentInfo(idx); });
        // Only a live GRANTED consent has records to pull; past its expiry there
        // is nothing left to fetch.
        const $open = $('<button class="ct-act" title="View records">&#8599;</button>');
        if (v.status === 'GRANTED') {
          $open.on('click', function(e) { e.stopPropagation(); openRecords(v); });
        } else {
          $open.prop('disabled', true);
        }
        $act.append($info).append($open);
        $tr.append($act);
        // The whole row opens the details — the icons are a small target, and
        // every row has something worth reading behind it.
        $tr.on('click', function() { openConsentInfo(idx); });
        $b.append($tr);
      });

      // Footer: "1–15 of 214", rows-per-page, and the page buttons.
      const $f = $('#ctFoot').empty();
      $f.append('<span>' + (start + 1) + '–' + Math.min(start + ctRows, total) + ' of ' + total + '</span>');
      const $sel = $('<select title="Rows per page"></select>');
      [15, 25, 50, 100].forEach(function(n) {
        $sel.append('<option value="' + n + '"' + (n === ctRows ? ' selected' : '') + '>' + n + '</option>');
      });
      $sel.on('change', function() {
        ctRows = parseInt($(this).val(), 10) || 15;
        ctPage = 1;
        renderConsentTable();
      });
      $f.append($('<span>Rows: </span>').append($sel));

      const mkPage = function(label, target, on, disabled) {
        const $p = $('<button class="ct-page"></button>').html(label);
        if (on) $p.addClass('on');
        if (disabled) $p.prop('disabled', true);
        else $p.on('click', function() { ctPage = target; renderConsentTable(); });
        return $p;
      };
      $f.append(mkPage('&lsaquo;', ctPage - 1, false, ctPage <= 1));
      // First, last and a window around the current page — 15 pages of buttons
      // would wrap the footer.
      const nums = [];
      for (let p = 1; p <= pages; p++) {
        if (p === 1 || p === pages || Math.abs(p - ctPage) <= 1) nums.push(p);
      }
      let prev = 0;
      nums.forEach(function(p) {
        if (prev && p - prev > 1) $f.append('<span>…</span>');
        $f.append(mkPage(String(p), p, p === ctPage, false));
        prev = p;
      });
      $f.append(mkPage('&rsaquo;', ctPage + 1, false, ctPage >= pages));
    }

    // ── Consent information dialog ───────────────────────────────────────────
    function ciField(label, value) {
      return '<div><div class="ci-lbl">' + esc(label) + '</div>' +
        '<div class="ci-val">' + esc(value === '' || value == null ? '-' : value) + '</div></div>';
    }

    function ciChips(list) {
      const arr = Array.isArray(list) ? list : (list ? [list] : []);
      if (!arr.length) return '<div class="ci-val">-</div>';
      return '<div>' + arr.map(function(h) {
        return '<span class="chip">' + esc(h) + '</span>';
      }).join(' ') + '</div>';
    }

    function openConsentInfo(idx) {
      const v = CONSENT_ROWS[idx];
      if (!v) return;
      ciCurrent = v;

      let h = '';
      h += '<div class="ci-grid">' +
        ciField('ABHA address', v.abha) +
        '<div><div class="ci-lbl">Status</div><div class="ci-val">' +
        '<span class="status-badge" style="background:' + statusColor(v.status) + ';">' +
        esc(v.status) + '</span></div></div>' +
        '</div>';

      h += '<div class="ci-sec">Purpose</div><div class="ci-grid">' +
        ciField('Code', v.purposeCode) +
        ciField('Text', v.purposeText) +
        '</div>' +
        '<div style="margin-top:12px;">' + ciField('Reference URI', v.purposeRef) + '</div>';

      h += '<div class="ci-sec">Requester</div><div class="ci-grid">' +
        ciField('Name', v.requester) +
        ciField('Patient', v.name) +
        '</div>';

      h += '<div class="ci-sec">Requested</div><div class="ci-grid">' +
        ciField('Request consent time', fmtDt(v.requestedAt)) +
        ciField('Date range From', fmtDt(v.reqFrom)) +
        ciField('Date range To', fmtDt(v.reqTo)) +
        ciField('Expired At', fmtDt(v.reqErase)) +
        ciField('Access mode', v.accessMode) +
        ciField('Created At', fmtDt(v.requestedAt)) +
        '</div>' +
        '<div style="margin-top:12px;"><div class="ci-lbl">HI types</div>' + ciChips(v.reqHiTypes) + '</div>';

      // Only print granted terms we actually have. Without the artefact detail
      // these would silently repeat the request — which is how a consent the
      // patient granted until 20-Aug displayed the requested 05-Sep instead.
      const gv = function(value) {
        return v.hasGrantedTerms ? fmtDt(value) : '-';
      };
      h += '<div class="ci-sec">Approved</div><div class="ci-grid">' +
        ciField('Approved consent time', v.approvedAt ? fmtDt(v.approvedAt) : '-') +
        ciField('Approved From', gv(v.from)) +
        ciField('Approved To', gv(v.to)) +
        ciField('Approved Expiry', gv(v.erase)) +
        ciField('Grant Date', v.stored === 'GRANTED' ? fmtDt(v.statusChangedAt) : '-') +
        ciField('Denied Date', v.stored === 'DENIED' ? fmtDt(v.statusChangedAt) : '-') +
        ciField('Revoked Date', v.stored === 'REVOKED' ? fmtDt(v.statusChangedAt) : '-') +
        ciField('Consent ID', v.consentId) +
        '</div>' +
        '<div style="margin-top:12px;"><div class="ci-lbl">HI types</div>' +
        ciChips(v.hasGrantedTerms ? v.hiTypes : []) + '</div>';

      if (v.stored === 'GRANTED' && v.consentId && !v.hasGrantedTerms) {
        h += '<div class="ci-note" id="ciPendingTerms"><b>Granted terms not fetched yet.</b> ' +
          'ABDM sends only the consent id when the patient approves; the dates and ' +
          'HI types they actually agreed to arrive separately. Fetching now — press ' +
          '<b>Refresh</b> in a moment.</div>';
      } else if (v.hiChanged || v.rangeChanged || v.eraseChanged) {
        h += '<div class="ci-note"><b>Note:</b> the patient changed these terms while ' +
          'granting — the Approved values above are what was actually granted.</div>';
      }

      h += '<div class="ci-sec">Data Transfer</div>';
      if (v.transfer) {
        const t = v.transfer;
        h += '<div class="ci-grid">' +
          '<div><div class="ci-lbl">Data Status</div><div class="ci-val">' +
          '<span class="status-badge" style="background:' +
          (t.status === 'DECRYPTED' ? '#1b7a35' : (t.status === 'FAILED' ? '#c62828' : '#1565c0')) +
          ';">' + esc(t.status) + '</span></div></div>' +
          ciField('Received At', t.receivedAt ? fmtDt(t.receivedAt) : '-') +
          ciField('Decrypted At', t.decryptedAt ? fmtDt(t.decryptedAt) : '-') +
          ciField('Bundles received', t.bundles == null ? '-' :
            (t.bundles + (t.failedBundles ? ' (' + t.failedBundles + ' failed)' : ''))) +
          '</div>';
      } else {
        h += '<div class="ci-val">No health-information request has been made against this consent yet.</div>';
      }

      h += '<div class="ci-sec">Modified Information</div><div class="ci-grid">' +
        ciField('Date Entered', fmtDt(v.requestedAt)) +
        ciField('Date Modified', fmtDt(v.updatedAt)) +
        '</div>';

      $('#ciBody').html(h);
      $('#ciView').prop('disabled', v.status !== 'GRANTED')
        .css('opacity', v.status === 'GRANTED' ? '' : '.45');
      $('#ciBack').css('display', 'block');
      // Opening a grant whose terms are still unknown kicks off the fetch, so
      // the answer is already on its way by the time the note is read.
      if (v.stored === 'GRANTED' && v.consentId && !v.hasGrantedTerms) {
        fetchConsentArtefact(v.consentId, false);
      }
    }

    function closeConsentInfo() {
      $('#ciBack').hide();
      ciCurrent = null;
    }

    // Ask ABDM for a granted artefact's detail. ABDM answers on the on-fetch
    // webhook, so nothing is available synchronously — re-read the list a moment
    // later and the real granted terms are there.
    const artefactFetchTried = {};

    function fetchConsentArtefact(consentId, announce) {
      if (!consentId || artefactFetchTried[consentId]) return;
      artefactFetchTried[consentId] = true;
      ensureToken(function(token) {
        $.ajax({
          url: 'hiu_integration.php',
          method: 'POST',
          contentType: 'application/json',
          dataType: 'json',
          data: JSON.stringify({
            page: 'hiu_fetch_consent',
            accessToken: token,
            consentId: consentId
          }),
          success: function(r) {
            if (!r || !r.success) {
              if (announce) toast('Could not fetch the granted terms.', 'err');
              // Let a later attempt retry rather than latching the failure.
              delete artefactFetchTried[consentId];
              return;
            }
            // Give the on-fetch webhook a moment to land, then re-read.
            setTimeout(function() {
              loadConsentHistory(consentListFilter, { quiet: true });
              if (announce) toast('Granted terms updated.', 'ok');
            }, 2500);
          },
          error: function() {
            delete artefactFetchTried[consentId];
            if (announce) toast('Could not fetch the granted terms.', 'err');
          }
        });
      });
    }

    // ── Panes: the list is the page; records open over it ────────────────────
    // Which patient the list is currently scoped to ('' = everyone).
    let consentListFilter = '';

    function showList() {
      $('#recordsPane').hide();
      $('#listPane').show();
      $('#backToList').hide();
      $('#newConsentBtn').show();
      $('#pageTitle').text('Consents');
      $('#pageSub').text("Request a patient's records from other facilities via ABDM consent, then view the returned data.");
    }

    function openRecords(v) {
      $('#listPane').hide();
      $('#recordsPane').show();
      $('#backToList').show();
      $('#newConsentBtn').hide();
      $('#pageTitle').text('Records');
      $('#pageSub').text(v && v.abha
        ? (v.name ? v.name + ' — ' + v.abha : v.abha)
        : 'Consented records');
      if (v) viewRecordsForRequest(v.raw.requestId, v.from, v.to);
      $('html, body').animate({ scrollTop: 0 }, 150);
    }

    function openNewConsent() {
      $('#ncBack').css('display', 'block');
      // The page is often left open for hours, so the server-rendered expiry
      // floor has gone stale by now.
      refreshEraseMin();
      setTimeout(function() { $('#identifier').focus(); }, 60);
    }

    function closeNewConsent() {
      $('#ncBack').hide();
    }

    // The form's initial state, captured once so the dialog can be returned to it.
    const NC_DEFAULTS = {
      idType: $('#idType').val(),
      from: $('#fromDate').val(),
      to: $('#toDate').val(),
      erase: $('#eraseDate').val(),
      purpose: $('#purpose').val()
    };

    // Put the dialog back to a blank "new request" after one is submitted —
    // reopening it should not still be showing the patient and terms of the
    // consent just created. Cancelling does NOT reset, so an accidental close
    // does not throw away what was typed.
    function resetNewConsentForm() {
      patient = null;
      $('#identifier').val('');
      $('#idType').val(NC_DEFAULTS.idType);
      updateIdType();
      $('#searchErr').hide().text('');
      $('#patientCard').hide();
      $('#consentCard').hide();
      $('#pName').text('-');
      $('#pGender').text('-');
      $('#pAddr').text('-');
      $('#pNum').text('-');
      // Prescription is the shipped default selection.
      $('.hiChk').each(function() {
        this.checked = (this.value === 'Prescription');
      });
      syncHiSelectAll();
      $('#fromDate').val(NC_DEFAULTS.from);
      $('#toDate').val(NC_DEFAULTS.to);
      $('#purpose').val(NC_DEFAULTS.purpose);
      $('#eraseDate').val(NC_DEFAULTS.erase);
      refreshEraseMin();
    }

    $(function() {
      // The list loads for every patient on arrival — this page IS the list.
      loadConsentHistory('');
      showList();

      $('#newConsentBtn').on('click', openNewConsent);
      $('#ncClose, #ncCloseBtn').on('click', closeNewConsent);
      $('#ncBack').on('click', function(e) {
        if (e.target === this) closeNewConsent();
      });
      $('#backToList').on('click', showList);
      $('#ctReload').on('click', function() {
        loadConsentHistory(consentListFilter);
      });

      $('#ctSearch').on('input', function() {
        ctQuery = $(this).val().trim();
        ctPage = 1;
        renderConsentTable();
      });
      $('#ciClose, #ciCloseBtn').on('click', closeConsentInfo);
      // Clicking the backdrop closes; clicking inside the panel must not.
      $('#ciBack').on('click', function(e) {
        if (e.target === this) closeConsentInfo();
      });
      $(document).on('keydown', function(e) {
        if (e.key !== 'Escape') return;
        // Innermost first: the info dialog can be opened over the list, and the
        // new-consent dialog is never open at the same time.
        if ($('#ciBack').is(':visible')) closeConsentInfo();
        else if ($('#ncBack').is(':visible')) closeNewConsent();
      });
      $('#ciRefresh').on('click', function() {
        // Refresh on a grant with unknown terms means "go and get them", not
        // just "re-read what we already have".
        if (ciCurrent && ciCurrent.stored === 'GRANTED' && ciCurrent.consentId && !ciCurrent.hasGrantedTerms) {
          delete artefactFetchTried[ciCurrent.consentId];
          fetchConsentArtefact(ciCurrent.consentId, true);
          toast('Fetching the granted terms from ABDM…', 'info');
          return;
        }
        loadConsentHistory(consentListFilter);
        toast('Refreshing consents…', 'info');
      });
      $('#ciView').on('click', function() {
        if (!ciCurrent || ciCurrent.status !== 'GRANTED') return;
        const v = ciCurrent;
        closeConsentInfo();
        openRecords(v);
      });
    });

    // Show what a granted consent actually covers. Zero care-contexts is one reason
    // "no data" arrives — there is nothing for any source facility to push.
    //
    // The artefact lookup resolves BEFORE the records do, so a zero here is not yet
    // evidence of anything: records already pushed still turn up moments later. The
    // warning is therefore parked in pendingCoverageWarning and only printed once
    // the poll gives up with nothing; renderBundles() discards it when records show.
    function flushCoverageWarning() {
      if (!pendingCoverageWarning) return;
      log('<span class="err">' + pendingCoverageWarning + '</span>');
      pendingCoverageWarning = null;
    }

    function logConsentCoverage(cid, token) {
      $.ajax({
        url: 'hiu_integration.php',
        method: 'POST',
        contentType: 'application/json',
        dataType: 'json',
        data: JSON.stringify({
          page: 'hiu_consent_artefact',
          accessToken: token,
          consentId: cid
        }),
        success: function(a) {
          if (!a || !a.success) return;
          const n = a.careContexts || 0;
          pendingCoverageWarning = null;
          if (n > 0) {
            log('<span class="info">ℹ Consent covers ' + n + ' care-context(s)' + (a.hipId ? (' at HIP ' + esc(a.hipId)) : '') + '.</span>');
          } else if (a.fetched !== false) {
            // Only a fetched artefact reporting zero says anything. "Not fetched
            // yet" is a timing artefact and must never be reported as a problem.
            pendingCoverageWarning = '⚠ Consent covers 0 care-contexts — the source facility has nothing to push, so no records will arrive.';
          }
        }
      });
    }

    // ── View records for a specific GRANTED consent (from the history list) ──────
    // Resolve the artefact consentId from the requestId, then show the already-
    // received bundles; if none have arrived yet, send an HI request and poll.
    function viewRecordsForRequest(requestId, from, to) {
      if (!requestId) {
        alert('Missing request id for this consent.');
        return;
      }
      $('#emptyState').hide();
      $('#statusArea').show();
      $('#results').empty();
      setStatus('GRANTED', '#1b7a35');
      showLoader();
      $('#log').empty();
      log('<span class="info">▶ Resolving consent for records…</span>');
      // Reading already-stored data uses the gateway API key, NOT an ABDM token, so
      // do NOT mint a token here — otherwise an ABDM token outage would hide records
      // that are already in the gateway DB. A token is only obtained if we have to
      // send a fresh HI request to ABDM (the no-data fallback below).
      // 1) requestId → artefact consentId (same endpoint the status poller uses).
      $.ajax({
        url: 'hiu_integration.php',
        method: 'POST',
        contentType: 'application/json',
        dataType: 'json',
        data: JSON.stringify({
          page: 'hiu_consent_status',
          accessToken: accessToken,
          requestId: requestId
        }),
        success: function(s) {
          const arts = (s && s.artefacts) || [];
          const cid = arts.length ? (arts[0].consentId || '') : '';
          if (!cid) {
            hideLoader();
            log('<span class="err">✗ No granted consent artefact found for this request.</span>');
            return;
          }
          consentId = cid;
          viewFrom = from || $('#fromDate').val();
          viewTo = to || $('#toDate').val();
          $('#reqIdLbl').text('req: ' + requestId);
          // Surface the Fetch/Retry button so the operator can re-pull if the
          // source facility is slow to push data (the auto-poll gives up after a while).
          $('#fetchBtn').show().prop('disabled', false);
          logConsentCoverage(cid, accessToken); // show how many care-contexts this consent covers

          // 2) Show already-received data if present.
          $.ajax({
            url: 'hiu_integration.php',
            method: 'POST',
            contentType: 'application/json',
            dataType: 'json',
            data: JSON.stringify({
              page: 'hiu_get_data',
              accessToken: accessToken,
              consentId: cid
            }),
            success: function(r) {
              if (r.blocked) {
                // Consent no longer active → records are withheld by the gateway.
                hideLoader();
                setStatus(r.consentStatus || 'EXPIRED', '#c62828');
                log('<span class="err">✗ ' + esc(r.message || ('Records withheld — consent is ' + (r.consentStatus || 'not active') + '.')) + '</span>');
                return;
              }
              if ((r.entries || []).length) {
                // Show whatever bundles have already been received (even if the HI
                // request isn't marked terminal yet).
                hideLoader();
                log('<span class="ok">✓ ' + r.entries.length + ' record(s).</span>');
                renderBundles(r.entries);
              } else {
                // 3) Nothing received yet → request health info (needs an ABDM token),
                //    then poll for the pushed data.
                log('<span class="info">▶ Requesting health information…</span>');
                ensureToken(function(token) {
                  $.ajax({
                    url: 'hiu_integration.php',
                    method: 'POST',
                    contentType: 'application/json',
                    dataType: 'json',
                    data: JSON.stringify({
                      page: 'hiu_hi_request',
                      accessToken: token,
                      consentId: cid,
                      from: from || $('#fromDate').val(),
                      to: to || $('#toDate').val()
                    }),
                    success: function(h) {
                      hideLoader();
                      if (!h.success) {
                        log('<span class="err">✗ HI request failed: ' + esc(JSON.stringify(h.error || h.node)) + '</span>');
                        return;
                      }
                      log('<span class="ok">✓ HI request sent.</span> Waiting for the source facility to push data…');
                      startDataPoll();
                    },
                    error: function(x) {
                      hideLoader();
                      log('<span class="err">✗ ' + esc(x.responseText || 'error') + '</span>');
                    }
                  });
                });
              }
            },
            error: function(x) {
              hideLoader();
              log('<span class="err">✗ ' + esc(x.responseText || 'error') + '</span>');
            }
          });
        },
        error: function(x) {
          hideLoader();
          log('<span class="err">✗ ' + esc(x.responseText || 'error') + '</span>');
        }
      });
    }

    // ── Request consent ──
    $('#consentBtn').on('click', function() {
      if (!patient || !patient.abha_address) {
        alert('No patient / ABHA address.');
        return;
      }
      const hiTypes = $('.hiChk:checked').map(function() {
        return this.value;
      }).get();
      if (hiTypes.length === 0) {
        alert('Select at least one HI type.');
        return;
      }
      // Expiry (data-erase) must be at least MIN_EXPIRY_MINUTES away — a consent
      // that lapses immediately can never be approved, let alone fetched against.
      const eraseVal = $('#eraseDate').val();
      const eraseAt = eraseVal ? new Date(eraseVal).getTime() : NaN;
      if (!eraseVal || isNaN(eraseAt) || eraseAt < minExpiryMs()) {
        $('#searchErr').hide();
        alert('Expiry Date must be at least ' + MIN_EXPIRY_MINUTES +
          ' minutes from now — pick a later date/time.');
        refreshEraseMin(); // the page may have been open a while; re-floor the picker
        $('#eraseDate').focus();
        return;
      }
      const [purposeCode, purposeText] = $('#purpose').val().split('|');
      viewFrom = viewTo = ''; // a fresh consent uses the form's date range, not a viewed one
      $('#emptyState').hide();
      $('#statusArea').show();
      $('#results').empty();
      $('#log').empty();
      setStatus('REQUESTED', '#1565c0');
      showLoader();
      log('<span class="info">▶ Obtaining ABDM token…</span>');
      ensureToken(function(token) {
        log('<span class="info">▶ Sending consent request…</span>');
        $.ajax({
          url: 'hiu_integration.php',
          method: 'POST',
          contentType: 'application/json',
          dataType: 'json',
          data: JSON.stringify({
            page: 'hiu_consent_init',
            accessToken: token,
            abhaAddress: patient.abha_address,
            patientName: patient.name || '',
            patientAbhaNumber: patient.abha_number || '',
            hiTypes,
            from: $('#fromDate').val(),
            to: $('#toDate').val(),
            dataEraseAt: $('#eraseDate').val(),
            purposeCode,
            purposeText
          }),
          success: function(r) {
            hideLoader();
            if (!r.success) {
              log('<span class="err">✗ Consent init failed: ' + esc(JSON.stringify(r.error || r.node)) + '</span>');
              setStatus('ERRORED', '#c62828');
              toast('Consent request failed.', 'err');
              return;
            }
            requestId = r.requestId;
            $('#reqIdLbl').text('req: ' + requestId);
            log('<span class="ok">✓ Consent request submitted.</span> Requester: ' + esc(r.requester || ''));
            // Say plainly whose records this consent covers — an unscoped consent
            // returns every facility the patient has linked, which is rarely what
            // the counter expects.
            log(r.hipScope
              ? '<span class="info">Scope: records from this facility only (HIP ' + esc(r.hipScope) + ').</span>'
              : '<span class="info">Scope: <b>all</b> facilities this patient has linked.</span>');
            log('<span class="info">Waiting for the patient to approve in their PHR app…</span>');
            // Back to the list: the new row is the confirmation, and it will
            // flip to GRANTED on its own once the patient approves.
            //
            // Scope the list to this patient BEFORE resetting the form —
            // resetNewConsentForm() clears `patient`.
            consentListFilter = patient.abha_address || '';
            closeNewConsent();
            resetNewConsentForm();   // next "+ Consent" starts blank
            toast('Consent requested — waiting for the patient to approve.', 'ok');
            // Clear any search term still narrowing the table, or the new row
            // would be filtered straight back out.
            ctQuery = '';
            $('#ctSearch').val('');
            refreshConsentsAfterCreate();
            startStatusPoll();
          },
          error: function(x) {
            hideLoader();
            log('<span class="err">✗ ' + esc(x.responseText || 'error') + '</span>');
            setStatus('ERRORED', '#c62828');
            toast('Consent request failed.', 'err');
          }
        });
      });
    });

    function setStatus(txt, color) {
      $('#statusBadge').text(txt).css('background', color || '#9e9e9e');
    }

    // ── Poll consent status (webhook-driven state on the gateway) ──
    // A failing request here returns an HTML page, not JSON — a cPanel/LiteSpeed
    // "508 Resource Limit Is Reached", a gateway error, or a PHP fatal. dataType
    // 'json' can't parse those, so they land in the ajax error handler. Turn them
    // into something the operator can act on.
    function serverErrMsg(xhr) {
      var code = (xhr && xhr.status) || 0;
      var body = (xhr && xhr.responseText) || '';
      if (code === 508 || /Resource Limit Is Reached/i.test(body)) {
        return 'The server hit its hosting resource limit (508). Too many requests in a short time — wait a minute, then retry.';
      }
      if (code === 0) return 'Network request failed (connection lost, blocked, or the request was cancelled).';
      if (code === 504 || code === 502) return 'The gateway timed out (HTTP ' + code + ').';
      if (/^\s*<(!doctype|html)/i.test(body)) return 'Server returned an error page (HTTP ' + code + ') instead of data.';
      return 'Server error (HTTP ' + (code || '?') + ').';
    }

    // Consecutive-failure counters + in-flight guards for the two pollers. Without
    // these a persistent server error polls forever every 5s, which is exactly what
    // keeps a resource limit tripped.
    var statusErrors = 0,
      dataErrors = 0,
      statusInFlight = false,
      dataInFlight = false;
    const POLL_MAX_ERRORS = 3;

    function stopStatusPoll() {
      clearInterval(statusTimer);
      statusTimer = null;
      statusInFlight = false;
      $('#refreshBtn').show();
    }

    function stopDataPoll() {
      clearInterval(dataTimer);
      dataTimer = null;
      dataInFlight = false;
      $('#fetchBtn').prop('disabled', false);
    }

    function startStatusPoll() {
      clearInterval(statusTimer);
      statusTries = 0;
      statusErrors = 0;
      statusInFlight = false;
      $('#refreshBtn').hide();
      statusTimer = setInterval(pollStatus, 5000);
      pollStatus();
    }

    function pollStatus() {
      if (!requestId) return;
      if (statusInFlight) return; // never stack requests on a slow / failing server
      statusTries++;
      statusInFlight = true;
      $.ajax({
        url: 'hiu_integration.php',
        method: 'POST',
        contentType: 'application/json',
        dataType: 'json',
        data: JSON.stringify({
          page: 'hiu_consent_status',
          accessToken,
          requestId
        }),
        success: function(r) {
          statusErrors = 0; // a good reply clears the consecutive-failure streak
          const st = r.status || 'UNKNOWN';
          if (st === 'GRANTED') {
            setStatus('GRANTED', '#1b7a35');
            const arts = r.artefacts || [];
            const cid = arts.length ? (arts[0].consentId || '') : '';
            if (cid) {
              clearInterval(statusTimer);
              statusTimer = null;
              consentId = cid;
              log('<span class="ok">✓ Consent GRANTED.</span> consentId ' + esc(cid));
              $('#fetchBtn').show();
              logConsentCoverage(cid, accessToken); // how many care-contexts were granted
              toast('Consent granted — records can now be fetched.', 'ok');
              // Quiet: the list is behind the records pane, so no skeleton flash.
              loadConsentHistory(consentListFilter, { quiet: true });
            } else {
              // Granted but the artefact id hasn't been fetched yet — keep polling.
              log('<span class="info">Consent granted; waiting for the artefact id…</span>');
              if (statusTries >= 24) {
                clearInterval(statusTimer);
                statusTimer = null;
                log('<span class="info">No artefact id yet — use Refresh.</span>');
                $('#refreshBtn').show();
              }
            }
          } else if (['DENIED', 'EXPIRED', 'REVOKED', 'ERRORED'].includes(st)) {
            clearInterval(statusTimer);
            statusTimer = null;
            setStatus(st, '#c62828');
            log('<span class="err">✗ Consent ' + st + '.</span>');
            $('#refreshBtn').show();
            toast('Consent ' + st.toLowerCase() + '.', 'err');
            loadConsentHistory(consentListFilter, { quiet: true });
          } else {
            setStatus(st, '#1565c0');
            if (statusTries >= 24) {
              clearInterval(statusTimer);
              statusTimer = null;
              log('<span class="info">Still pending — stopped auto-poll. Use Refresh.</span>');
              $('#refreshBtn').show();
            }
          }
        },
        error: function(xhr) {
          statusErrors++;
          if (statusErrors >= POLL_MAX_ERRORS) {
            stopStatusPoll();
            log('<span class="err">✗ ' + esc(serverErrMsg(xhr)) + ' Stopped auto-poll — use Refresh.</span>');
          }
        },
        complete: function() {
          statusInFlight = false;
          // The attempt cap must apply on failures too, otherwise a server that
          // never returns JSON is polled indefinitely.
          if (statusTimer && statusTries >= 24) {
            stopStatusPoll();
            log('<span class="info">Stopped auto-poll after 24 attempts — use Refresh.</span>');
          }
        }
      });
    }
    $('#refreshBtn').on('click', function() {
      startStatusPoll();
    });

    // ── Fetch records ──
    $('#fetchBtn').on('click', function() {
      if (!consentId) {
        alert('No granted consent id.');
        return;
      }
      $('#fetchBtn').prop('disabled', true);
      showLoader();
      log('<span class="info">▶ Requesting health information…</span>');
      ensureToken(function(token) {
        $.ajax({
          url: 'hiu_integration.php',
          method: 'POST',
          contentType: 'application/json',
          dataType: 'json',
          data: JSON.stringify({
            page: 'hiu_hi_request',
            accessToken: token,
            consentId,
            from: viewFrom || $('#fromDate').val(),
            to: viewTo || $('#toDate').val()
          }),
          success: function(r) {
            hideLoader();
            if (!r.success) {
              log('<span class="err">✗ HI request failed: ' + esc(JSON.stringify(r.error || r.node)) + '</span>');
              $('#fetchBtn').prop('disabled', false);
              return;
            }
            log('<span class="ok">✓ HI request sent.</span> Waiting for the source facility to push data…');
            startDataPoll();
          },
          error: function(x) {
            hideLoader();
            log('<span class="err">✗ ' + esc(x.responseText || 'error') + '</span>');
            $('#fetchBtn').prop('disabled', false);
          }
        });
      });
    });

    function startDataPoll() {
      clearInterval(dataTimer);
      dataTries = 0;
      dataErrors = 0;
      dataInFlight = false;
      dataTimer = setInterval(pollData, 5000);
      pollData();
    }

    function pollData() {
      if (dataInFlight) return; // never stack requests on a slow / failing server
      dataTries++;
      dataInFlight = true;
      $.ajax({
        url: 'hiu_integration.php',
        method: 'POST',
        contentType: 'application/json',
        dataType: 'json',
        data: JSON.stringify({
          page: 'hiu_get_data',
          accessToken,
          consentId
        }),
        success: function(r) {
          dataErrors = 0; // a good reply clears the consecutive-failure streak
          if (r.ready && (r.entries || []).length) {
            stopDataPoll();
            log('<span class="ok">✓ Received ' + r.entries.length + ' record(s).</span>');
            renderBundles(r.entries);
          }
        },
        error: function(xhr) {
          // The 508 hosting-resource-limit page (and any gateway error / PHP fatal)
          // is HTML, so it lands here rather than in success. Back off after a few
          // consecutive failures — polling on regardless is what keeps the limit
          // tripped and made the endpoint look like it was being hit repeatedly.
          dataErrors++;
          if (dataErrors >= POLL_MAX_ERRORS) {
            stopDataPoll();
            log('<span class="err">✗ ' + esc(serverErrMsg(xhr)) +
              ' Stopped auto-poll after ' + dataErrors + ' failed attempts — click Fetch Records to retry.</span>');
          }
        },
        complete: function() {
          dataInFlight = false;
          // The attempt cap must apply on failures too, otherwise a server that
          // never returns JSON is polled indefinitely.
          if (dataTimer && dataTries >= 30) {
            stopDataPoll();
            // Nothing arrived — now a zero-care-context consent is worth reporting.
            flushCoverageWarning();
            log('<span class="info">No data pushed by the source facility yet — stopped auto-poll. ' +
              'Click Fetch Records to retry.</span>');
          }
        }
      });
    }

    // ── Read-only viewer (PHR-style record cards) ─────────────────────────────
    // One card per received bundle: a header (record title, record no,
    // care-context reference, source HIP, date), a Patient / Practitioner /
    // Facility strip, then one collapsible block per Composition section whose
    // referenced FHIR resources are resolved and formatted into readable rows.

    const MONTHS = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

    // Inline icons — css/remixicon is not shipped with this build, so the ri-*
    // classes render nothing. These always draw.
    const ICONS = {
      doc: '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>',
      user: '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
      users: '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
      stetho: '<path d="M4 3v6a4.5 4.5 0 0 0 9 0V3"/><path d="M8.5 13.5V16a5 5 0 0 0 10 0v-2.6"/><circle cx="18.5" cy="9.5" r="2.2"/>',
      hospital: '<path d="M3 21h18"/><path d="M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16"/><path d="M12 8v6"/><path d="M9 11h6"/>',
      pulse: '<polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>',
      capsule: '<path d="M10.5 20.5 3.5 13.5a5 5 0 0 1 7-7l7 7a5 5 0 0 1-7 7z"/><line x1="7" y1="10" x2="14" y2="17"/>',
      alert: '<circle cx="12" cy="12" r="9"/><line x1="12" y1="8" x2="12" y2="12.5"/><line x1="12" y1="16" x2="12.01" y2="16"/>',
      link: '<path d="M10 13a5 5 0 0 0 7.5.5l3-3a5 5 0 0 0-7-7L12 5"/><path d="M14 11a5 5 0 0 0-7.5-.5l-3 3a5 5 0 0 0 7 7L12 19"/>',
      flask: '<path d="M9 3h6"/><path d="M10 3v6l-5.5 9A2 2 0 0 0 6.3 21h11.4a2 2 0 0 0 1.8-3L14 9V3"/><line x1="7.5" y1="15" x2="16.5" y2="15"/>',
      clip: '<path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/>',
      heart: '<path d="M20.8 6.6a5 5 0 0 0-7.1 0L12 8.3l-1.7-1.7a5 5 0 0 0-7.1 7.1l8.8 8.8 8.8-8.8a5 5 0 0 0 0-7.1z"/>',
      clipboard: '<path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1"/>',
      calendar: '<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="16" y1="2" x2="16" y2="6"/>',
      money: '<rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2.5"/><line x1="6" y1="12" x2="6.01" y2="12"/><line x1="18" y1="12" x2="18.01" y2="12"/>',
      chevron: '<polyline points="6 9 12 15 18 9"/>',
      download: '<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>'
    };

    function svgIcon(name, size) {
      const p = ICONS[name] || ICONS.pulse;
      const s = size || 16;
      return '<svg class="rec-svg" width="' + s + '" height="' + s + '" viewBox="0 0 24 24" fill="none" ' +
        'stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">' + p + '</svg>';
    }

    // ── Formatting helpers ────────────────────────────────────────────────────
    // FHIR dates arrive as dateTime, date, yyyy-mm, yyyy or a Period. Render them
    // the way a clinician reads them — "04 May 2026, 06:00 pm" — and never invent
    // a time for a date-only value.
    function fmtWhen(v) {
      if (!v) return '';
      if (typeof v === 'object') {
        if (v.start || v.end) {
          const a = fmtWhen(v.start),
            b = fmtWhen(v.end);
          return (a && b) ? (a + ' → ' + b) : (a || b);
        }
        return fmtWhen(v.dateTime || v.date || v.value || '');
      }
      const s = String(v).trim();
      if (!s) return '';
      if (/^\d{4}$/.test(s)) return s;
      if (/^\d{4}-\d{2}$/.test(s)) return MONTHS[parseInt(s.slice(5, 7), 10) - 1] + ' ' + s.slice(0, 4);
      const dateOnly = /^\d{4}-\d{2}-\d{2}$/.test(s);
      const d = new Date(dateOnly ? (s + 'T00:00:00') : s.replace(' ', 'T'));
      if (isNaN(d.getTime())) return s;
      const pad = n => ('0' + n).slice(-2);
      const day = pad(d.getDate()) + ' ' + MONTHS[d.getMonth()] + ' ' + d.getFullYear();
      if (dateOnly) return day;
      let h = d.getHours();
      const ap = h >= 12 ? 'pm' : 'am';
      h = h % 12 || 12;
      return day + ', ' + pad(h) + ':' + pad(d.getMinutes()) + ' ' + ap;
    }

    // CodeableConcept → text. Keeps the coded display when it adds meaning, e.g.
    // "A T Z SYP (Multivitamin agent (substance))".
    function ccText(c) {
      if (!c) return '';
      if (Array.isArray(c)) return c.map(ccText).filter(Boolean).join(', ');
      if (typeof c === 'string') return c;
      const t = String(c.text || '').trim();
      const co = (c.coding && c.coding[0]) || {};
      const d = String(co.display || '').trim();
      if (t && d && t.toLowerCase().indexOf(d.toLowerCase()) === -1) return t + ' (' + d + ')';
      return t || d || String(co.code || '').trim();
    }

    function qtyText(q) {
      if (!q) return '';
      if (q.value == null) return ccText(q.unit || q.code || '');
      const u = q.unit || q.code || '';
      return String(q.value) + (u ? ' ' + u : '');
    }

    function capFirst(s) {
      s = String(s || '');
      return s ? s.charAt(0).toUpperCase() + s.slice(1) : '';
    }

    // "MedicationRequest" → "Medication Request"
    function humanType(t) {
      return String(t || '').replace(/([a-z])([A-Z])/g, '$1 $2');
    }

    function humanName(n) {
      const h = Array.isArray(n) ? n[0] : n;
      if (!h) return '';
      if (typeof h === 'string') return h.trim();
      if (h.text) return String(h.text).trim();
      const part = k => (h[k] ? [].concat(h[k]).join(' ') : '');
      return [part('prefix'), part('given'), h.family || '', part('suffix')]
        .filter(Boolean).join(' ').trim();
    }

    function ageFrom(birthDate) {
      if (!birthDate) return '';
      const d = new Date(String(birthDate));
      if (isNaN(d.getTime())) return '';
      const now = new Date();
      let a = now.getFullYear() - d.getFullYear();
      const m = now.getMonth() - d.getMonth();
      if (m < 0 || (m === 0 && now.getDate() < d.getDate())) a--;
      return (a < 0 || a > 130) ? '' : a + ' yrs';
    }

    // ── Reference resolution ──────────────────────────────────────────────────
    // Bundles reference resources as urn:uuid:…, "Type/id" or an absolute URL, so
    // index every form once and resolve against all of them.
    function indexBundle(b) {
      const idx = {
        byKey: {},
        all: []
      };
      const put = function(k, r) {
        if (k && !idx.byKey[k]) idx.byKey[k] = r;
      };
      ((b && b.entry) || []).forEach(function(x) {
        const r = x && x.resource;
        if (!r) return;
        idx.all.push(r);
        put(x.fullUrl, r);
        if (r.resourceType && r.id) put(r.resourceType + '/' + r.id, r);
        if (r.id) put('urn:uuid:' + r.id, r);
        (r.contained || []).forEach(function(c) {
          if (c && c.id) put('#' + c.id, c);
        });
      });
      return idx;
    }

    function deref(idx, ref) {
      if (!ref) return null;
      const s = String(typeof ref === 'string' ? ref : (ref.reference || '')).trim();
      if (!s) return null;
      if (idx.byKey[s]) return idx.byKey[s];
      const m = s.match(/([A-Za-z]+)\/([A-Za-z0-9._-]+)$/);
      if (m && idx.byKey[m[1] + '/' + m[2]]) return idx.byKey[m[1] + '/' + m[2]];
      const u = s.match(/([0-9a-fA-F-]{8,})$/);
      if (u && idx.byKey['urn:uuid:' + u[1]]) return idx.byKey['urn:uuid:' + u[1]];
      return null;
    }

    // A reference's readable label: the resolved resource's own name/code, else
    // whatever display the sender put on the reference itself.
    function refLabel(ref, idx) {
      const r = deref(idx, ref);
      if (r) {
        const lbl = humanName(r.name) || ccText(r.code) || ccText(r.medicationCodeableConcept) ||
          ccText(r.vaccineCode) || r.title || '';
        if (lbl) return lbl;
      }
      return (ref && ref.display) ? String(ref.display) : '';
    }

    function refLabels(refs, idx) {
      if (!refs) return '';
      return [].concat(refs).map(function(x) {
        return refLabel(x, idx);
      }).filter(Boolean).join(', ');
    }

    // Dosage → one readable line. Prefer the sender's own text ("One tablet at
    // once"); only compose from the structured fields when there is none.
    function dosageText(dosage) {
      const d = Array.isArray(dosage) ? dosage[0] : dosage;
      if (!d) return '';
      if (d.text) return String(d.text).trim();
      const UNITS = {
        s: 'sec',
        min: 'min',
        h: 'hour',
        d: 'day',
        wk: 'week',
        mo: 'month',
        a: 'year'
      };
      const bits = [];
      const dr = (d.doseAndRate && d.doseAndRate[0]) || null;
      if (dr && dr.doseQuantity) bits.push(qtyText(dr.doseQuantity));
      else if (dr && dr.doseRange) bits.push(qtyText(dr.doseRange.low) + ' – ' + qtyText(dr.doseRange.high));
      const rep = (d.timing && d.timing.repeat) || null;
      if (rep && rep.frequency && rep.period) {
        bits.push(rep.frequency + '× every ' + rep.period + ' ' + (UNITS[rep.periodUnit] || rep.periodUnit || ''));
      } else if (d.timing && d.timing.code) {
        bits.push(ccText(d.timing.code));
      }
      if (rep && rep.boundsDuration) bits.push('for ' + qtyText(rep.boundsDuration));
      if (d.route) bits.push(ccText(d.route));
      if (d.method) bits.push(ccText(d.method));
      if (d.additionalInstruction) bits.push(ccText(d.additionalInstruction));
      return bits.filter(Boolean).join(' · ');
    }

    function noteLines(notes) {
      return [].concat(notes || []).map(function(n) {
        return (n && n.text) ? String(n.text).trim() : '';
      }).filter(Boolean);
    }

    function obsValue(r) {
      if (r.valueQuantity) return qtyText(r.valueQuantity);
      if (r.valueString) return String(r.valueString);
      if (r.valueCodeableConcept) return ccText(r.valueCodeableConcept);
      if (typeof r.valueBoolean === 'boolean') return r.valueBoolean ? 'Yes' : 'No';
      if (r.valueInteger != null) return String(r.valueInteger);
      if (r.valueRatio) return qtyText(r.valueRatio.numerator) + ' / ' + qtyText(r.valueRatio.denominator);
      if (r.valueRange) return [qtyText(r.valueRange.low), qtyText(r.valueRange.high)].filter(Boolean).join(' – ');
      if (r.valueDateTime) return fmtWhen(r.valueDateTime);
      if (r.valuePeriod) return fmtWhen(r.valuePeriod);
      if (r.dataAbsentReason) return ccText(r.dataAbsentReason);
      return '';
    }

    // ── Attachments ───────────────────────────────────────────────────────────
    // Base64 payloads stay out of the DOM: rows carry an index into ATTACH and the
    // file is built as a Blob on click.
    const ATTACH = [];

    function extFor(mime) {
      const m = String(mime || '').toLowerCase();
      if (m.indexOf('pdf') !== -1) return 'pdf';
      if (m.indexOf('jpeg') !== -1 || m.indexOf('jpg') !== -1) return 'jpg';
      if (m.indexOf('png') !== -1) return 'png';
      if (m.indexOf('json') !== -1) return 'json';
      if (m.indexOf('xml') !== -1) return 'xml';
      if (m.indexOf('html') !== -1) return 'html';
      if (m.indexOf('text') !== -1) return 'txt';
      return 'bin';
    }

    function pushAttachment(att, fallbackLabel, out) {
      if (!att || (!att.data && !att.url)) return;
      const label = String(att.title || fallbackLabel || 'Supporting Document').trim() || 'Supporting Document';
      const safe = label.replace(/[^\w \-.]+/g, '').trim() || 'document';
      out.push(ATTACH.length);
      ATTACH.push({
        contentType: att.contentType || '',
        data: att.data || '',
        url: att.url || '',
        label: label,
        filename: /\.[a-z0-9]{2,5}$/i.test(safe) ? safe : (safe + '.' + extFor(att.contentType))
      });
    }

    // Every place a FHIR resource can carry a file: DocumentReference.content,
    // DiagnosticReport.presentedForm, Media.content and a bare Binary.
    function collectAttachments(r, out, contextLabel) {
      if (!r) return;
      const label = ccText(r.type) || r.description || ccText(r.code) ||
        String(contextLabel || '').trim() || 'Supporting Document';

      // Binary keeps contentType/data at the TOP level, not under
      // content.attachment. The NRCES PrescriptionRecord section is a closed
      // slice that allows Binary but FORBIDS DocumentReference, so a
      // prescription's printable copy always arrives as a Binary — which is why
      // it was counted in the bundle ("Binary 1") but never offered here.
      if (r.resourceType === 'Binary') {
        if (r.data || r.url) {
          pushAttachment({
            contentType: r.contentType || '',
            data: r.data || '',
            url: r.url || '',
            title: label
          }, label, out);
        }
        return;
      }

      if (Array.isArray(r.content)) {
        r.content.forEach(function(c) {
          pushAttachment(c && c.attachment, label, out);
        });
      } else if (r.content && typeof r.content === 'object') {
        pushAttachment(r.content.attachment || r.content, label, out); // Media
      }
      (r.presentedForm || []).forEach(function(a) {
        pushAttachment(a, ccText(r.code) || 'Report document', out);
      });
    }

    function downloadAttachment(i) {
      const a = ATTACH[i];
      if (!a) return;
      if (!a.data && a.url) {
        window.open(a.url, '_blank');
        return;
      }
      try {
        const bin = atob(String(a.data).replace(/\s/g, ''));
        const buf = new Uint8Array(bin.length);
        for (let k = 0; k < bin.length; k++) buf[k] = bin.charCodeAt(k);
        const url = URL.createObjectURL(new Blob([buf], {
          type: a.contentType || 'application/octet-stream'
        }));
        const el = document.createElement('a');
        el.href = url;
        el.download = a.filename;
        document.body.appendChild(el);
        el.click();
        setTimeout(function() {
          URL.revokeObjectURL(url);
          $(el).remove();
        }, 1000);
      } catch (err) {
        alert('This attachment could not be decoded.');
      }
    }
    $(document).on('click', '.rec-dl', function() {
      downloadAttachment(parseInt($(this).attr('data-att'), 10));
    });

    // ── Resource → readable row ───────────────────────────────────────────────
    // Returns {title, lines[], when} — or null for resources already shown
    // elsewhere on the card (patient / practitioner / facility / the Composition).
    const SKIP_TYPES = ['Composition', 'Patient', 'Practitioner', 'PractitionerRole',
      'Organization', 'Location', 'Provenance', 'Encounter', 'Binary'
    ];

    function describeResource(r, idx) {
      if (!r || !r.resourceType || SKIP_TYPES.indexOf(r.resourceType) !== -1) return null;
      const out = {
        title: '',
        lines: [],
        when: ''
      };
      const t = r.resourceType;

      if (t === 'MedicationRequest' || t === 'MedicationStatement') {
        out.title = ccText(r.medicationCodeableConcept) || refLabel(r.medicationReference, idx) || 'Medication';
        const reason = ccText(r.reasonCode) || refLabels(r.reasonReference, idx);
        if (reason) out.lines.push('Reason: ' + reason);
        const dz = dosageText(r.dosageInstruction || r.dosage);
        if (dz) out.lines.push(dz);
        if (r.status && ['active', 'completed', 'unknown'].indexOf(r.status) === -1) out.lines.push('Status: ' + capFirst(r.status));
        out.lines = out.lines.concat(noteLines(r.note));
        out.when = fmtWhen(r.authoredOn || r.dateAsserted || r.effectiveDateTime || r.effectivePeriod);

      } else if (t === 'Condition') {
        out.title = ccText(r.code) || 'Condition';
        const cs = ccText(r.clinicalStatus);
        if (cs) out.lines.push(cs);
        const sev = ccText(r.severity);
        if (sev) out.lines.push('Severity: ' + sev);
        const site = ccText(r.bodySite);
        if (site) out.lines.push('Site: ' + site);
        const onset = fmtWhen(r.onsetDateTime || r.onsetPeriod || r.onsetString);
        if (onset) out.lines.push('Onset: ' + onset);
        out.lines = out.lines.concat(noteLines(r.note));
        out.when = fmtWhen(r.recordedDate || r.onsetDateTime || r.onsetPeriod);

      } else if (t === 'AllergyIntolerance') {
        out.title = ccText(r.code) || 'Allergy';
        const cs = ccText(r.clinicalStatus);
        if (cs) out.lines.push('Clinical status: ' + cs);
        const vs = ccText(r.verificationStatus);
        if (vs) out.lines.push('Verification: ' + vs);
        if (r.criticality) out.lines.push('Criticality: ' + capFirst(r.criticality));
        if (r.category) out.lines.push('Category: ' + [].concat(r.category).map(capFirst).join(', '));
        (r.reaction || []).forEach(function(x) {
          const man = ccText(x.manifestation);
          if (man) out.lines.push('Reaction: ' + man + (x.severity ? ' (' + x.severity + ')' : ''));
        });
        out.lines = out.lines.concat(noteLines(r.note));
        out.when = fmtWhen(r.recordedDate || r.onsetDateTime);

      } else if (t === 'Observation') {
        out.title = ccText(r.code) || 'Observation';
        const v = obsValue(r);
        if (v) out.lines.push(v);
        (r.component || []).forEach(function(c) {
          const cn = ccText(c.code),
            cv = obsValue(c);
          if (cn || cv) out.lines.push([cn, cv].filter(Boolean).join(': '));
        });
        const interp = ccText(r.interpretation);
        if (interp) out.lines.push('Interpretation: ' + interp);
        (r.referenceRange || []).forEach(function(rr) {
          const txt = rr.text || [qtyText(rr.low), qtyText(rr.high)].filter(Boolean).join(' – ');
          if (txt) out.lines.push('Reference range: ' + txt);
        });
        const members = refLabels(r.hasMember, idx);
        if (members) out.lines.push('Includes: ' + members);
        out.lines = out.lines.concat(noteLines(r.note));
        out.when = fmtWhen(r.effectiveDateTime || r.effectivePeriod || r.issued);

      } else if (t === 'DiagnosticReport') {
        out.title = ccText(r.code) || 'Diagnostic report';
        if (r.status && ['final', 'completed'].indexOf(r.status) === -1) out.lines.push('Status: ' + capFirst(r.status));
        [].concat(r.result || []).forEach(function(ref) {
          const o = deref(idx, ref);
          if (o && o.resourceType === 'Observation') {
            out.lines.push([ccText(o.code), obsValue(o)].filter(Boolean).join(': '));
          } else {
            const l = refLabel(ref, idx);
            if (l) out.lines.push(l);
          }
        });
        if (r.conclusion) out.lines.push('Conclusion: ' + r.conclusion);
        const cc = ccText(r.conclusionCode);
        if (cc) out.lines.push('Coded conclusion: ' + cc);
        out.when = fmtWhen(r.effectiveDateTime || r.effectivePeriod || r.issued);

      } else if (t === 'ServiceRequest') {
        out.title = ccText(r.code) || 'Advised investigation';
        const reason = ccText(r.reasonCode) || refLabels(r.reasonReference, idx);
        if (reason) out.lines.push('Reason: ' + reason);
        if (r.priority) out.lines.push('Priority: ' + capFirst(r.priority));
        if (r.status && ['active', 'completed'].indexOf(r.status) === -1) out.lines.push('Status: ' + capFirst(r.status));
        const occ = fmtWhen(r.occurrenceDateTime || r.occurrencePeriod);
        if (occ) out.lines.push('Scheduled: ' + occ);
        out.lines = out.lines.concat(noteLines(r.note));
        out.when = fmtWhen(r.authoredOn);

      } else if (t === 'Procedure') {
        out.title = ccText(r.code) || 'Procedure';
        if (r.status && r.status !== 'completed') out.lines.push('Status: ' + capFirst(r.status));
        const site = ccText(r.bodySite);
        if (site) out.lines.push('Site: ' + site);
        const outc = ccText(r.outcome);
        if (outc) out.lines.push('Outcome: ' + outc);
        out.lines = out.lines.concat(noteLines(r.note));
        out.when = fmtWhen(r.performedDateTime || r.performedPeriod);

      } else if (t === 'Immunization') {
        out.title = ccText(r.vaccineCode) || 'Immunization';
        if (r.doseQuantity) out.lines.push('Dose: ' + qtyText(r.doseQuantity));
        const route = ccText(r.route);
        if (route) out.lines.push('Route: ' + route);
        const isite = ccText(r.site);
        if (isite) out.lines.push('Site: ' + isite);
        if (r.lotNumber) out.lines.push('Lot: ' + r.lotNumber);
        if (r.expirationDate) out.lines.push('Expiry: ' + fmtWhen(r.expirationDate));
        if (r.status && r.status !== 'completed') out.lines.push('Status: ' + capFirst(r.status));
        out.lines = out.lines.concat(noteLines(r.note));
        out.when = fmtWhen(r.occurrenceDateTime || r.recorded);

      } else if (t === 'FamilyMemberHistory') {
        const rel = ccText(r.relationship);
        out.title = rel || humanName(r.name) || 'Family history';
        if (rel && typeof r.name === 'string' && r.name) out.lines.push(r.name);
        (r.condition || []).forEach(function(c) {
          const nm = ccText(c.code);
          const on = c.onsetAge ? qtyText(c.onsetAge) : fmtWhen(c.onsetString || c.onsetPeriod);
          if (nm) out.lines.push(nm + (on ? ' — ' + on : '') + (c.contributedToDeath ? ' (contributed to death)' : ''));
        });
        out.lines = out.lines.concat(noteLines(r.note));
        out.when = fmtWhen(r.date);

      } else if (t === 'CarePlan') {
        out.title = r.title || 'Care plan';
        if (r.description) out.lines.push(r.description);
        (r.activity || []).forEach(function(a) {
          const d = a && a.detail;
          const nm = d ? (ccText(d.code) || d.description || '') : '';
          if (nm) out.lines.push(nm);
        });
        out.lines = out.lines.concat(noteLines(r.note));
        out.when = fmtWhen(r.created || r.period);

      } else if (t === 'Goal') {
        out.title = ccText(r.description) || 'Goal';
        if (r.lifecycleStatus) out.lines.push('Status: ' + capFirst(r.lifecycleStatus));
        out.lines = out.lines.concat(noteLines(r.note));
        out.when = fmtWhen(r.startDate);

      } else if (t === 'Appointment') {
        out.title = r.description || ccText(r.serviceType) || 'Follow-up appointment';
        if (r.status) out.lines.push('Status: ' + capFirst(r.status));
        if (r.comment) out.lines.push(r.comment);
        out.when = fmtWhen(r.start);

      } else if (t === 'Invoice') {
        const num = (r.identifier && r.identifier[0] && r.identifier[0].value) || '';
        const money = function(m) {
          return m ? qtyText({
            value: m.value,
            unit: m.currency
          }) : '';
        };
        out.title = 'Invoice' + (num ? ' ' + num : '');
        (r.lineItem || []).forEach(function(li) {
          const nm = ccText(li.chargeItemCodeableConcept) || refLabel(li.chargeItemReference, idx);
          const amt = (li.priceComponent || []).map(function(p) {
            return money(p.amount);
          }).filter(Boolean).join(' + ');
          if (nm || amt) out.lines.push([nm, amt].filter(Boolean).join(' — '));
        });
        if (r.totalNet) out.lines.push('Net: ' + money(r.totalNet));
        if (r.totalGross) out.lines.push('Total: ' + money(r.totalGross));
        if (r.status && r.status !== 'issued') out.lines.push('Status: ' + capFirst(r.status));
        out.when = fmtWhen(r.date);

      } else {
        // Not-yet-modelled type: show whatever identifies it rather than dropping
        // clinical content on the floor.
        out.title = ccText(r.code) || r.title || r.description || humanType(t);
        if (r.status) out.lines.push('Status: ' + capFirst(r.status));
        out.lines = out.lines.concat(noteLines(r.note));
        out.when = fmtWhen(r.effectiveDateTime || r.recordedDate || r.date || r.issued || r.authoredOn);
      }

      out.lines = out.lines.filter(Boolean);
      if (!out.title && !out.lines.length) return null;
      out.title = out.title || humanType(t);
      return out;
    }

    // Resources another row already pulled in — a medication's reason, a report's
    // results, an observation's members — are shown inline there, so they must not
    // be listed again as unsectioned leftovers.
    const SUPPORTING_FIELDS = ['reasonReference', 'result', 'hasMember', 'medicationReference',
      'derivedFrom', 'partOf', 'basedOn', 'supportingInfo'
    ];

    function markSupporting(r, idx, used) {
      SUPPORTING_FIELDS.forEach(function(f) {
        [].concat(r[f] || []).forEach(function(ref) {
          const t = deref(idx, ref);
          if (t && used.indexOf(t) === -1) used.push(t);
        });
      });
      (r.lineItem || []).forEach(function(li) {
        const t = deref(idx, li && li.chargeItemReference);
        if (t && used.indexOf(t) === -1) used.push(t);
      });
    }

    function sectionIcon(title) {
      const t = String(title || '').toLowerCase();
      if (/medicat|prescri|drug|pharma/.test(t)) return 'capsule';
      if (/allerg/.test(t)) return 'alert';
      if (/family/.test(t)) return 'users';
      if (/medical history|past|surgic|procedur/.test(t)) return 'link';
      if (/investigat|lab|diagnos|test|specimen|imaging|radio|immun|vaccin/.test(t)) return 'flask';
      if (/document|attach|scan/.test(t)) return 'clip';
      if (/vital|observ|physical/.test(t)) return 'heart';
      if (/advice|plan|instruct|follow|referr/.test(t)) return 'clipboard';
      if (/appoint|visit|encounter/.test(t)) return 'calendar';
      if (/invoice|bill|charge|payment/.test(t)) return 'money';
      return 'pulse';
    }

    // ── Card assembly ─────────────────────────────────────────────────────────
    function metaRow(iconKey, cls, label, value, sub) {
      const $r = $('<div class="rec-meta-row"></div>');
      $r.append('<span class="rec-ic ' + cls + '">' + svgIcon(iconKey, 16) + '</span>');
      const $t = $('<div style="min-width:0;"></div>');
      $t.append($('<div class="rec-meta-lbl"></div>').text(label));
      $t.append($('<div class="rec-meta-val"></div>').text(value || '—'));
      if (sub) $t.append($('<div class="rec-meta-sub"></div>').text(sub));
      return $r.append($t);
    }

    function itemRow(d) {
      const $i = $('<div class="rec-item"></div>');
      // A resource the sender referenced but did not include is not clinical
      // content — mark it so it cannot be mistaken for recorded data.
      if (d.missing) $i.addClass('rec-item-missing');
      $i.append($('<div class="rec-item-title"></div>').text(d.title));
      d.lines.forEach(function(l) {
        $i.append($('<div class="rec-item-line"></div>').text(l));
      });
      if (d.when) $i.append($('<div class="rec-item-when"></div>').text('Recorded At: ' + d.when));
      return $i;
    }

    function docRow(i) {
      const a = ATTACH[i];
      return $('<div class="rec-docrow"></div>')
        .append($('<span class="rec-doc-lbl"></span>').text(a.label))
        .append($('<span class="rec-dl"></span>').attr('data-att', i)
          .html(svgIcon('download', 14) + '<span>Download</span>'));
    }

    // One collapsible section. `items` are described resources, `docs` attachment
    // indexes, `narrative` the section's own text (a fallback when nothing else
    // could be resolved).
    function sectionBlock(title, items, docs, narrative, open) {
      const count = items.length + docs.length;
      const $s = $('<div class="rec-sec"></div>');
      const $h = $('<div class="rec-sec-head"></div>')
        .append('<span class="rec-sec-icon">' + svgIcon(sectionIcon(title), 16) + '</span>')
        .append($('<span class="rec-sec-title"></span>').text(title));
      if (count) $h.append($('<span class="rec-sec-count"></span>').text(String(count)));
      $h.append('<span class="rec-chev">' + svgIcon('chevron', 18) + '</span>');
      const $b = $('<div class="rec-sec-body"></div>');
      items.forEach(function(d) {
        $b.append(itemRow(d));
      });
      docs.forEach(function(i) {
        $b.append(docRow(i));
      });
      if (!count) {
        if (narrative) $b.append($('<div class="rec-narr"></div>').text(narrative));
        else $b.append('<div class="rec-sec-empty">No details recorded.</div>');
      }
      $s.append($h).append($b);
      if (!open) {
        $s.addClass('closed');
        $b.hide();
      }
      return $s;
    }
    $(document).on('click', '.rec-sec-head', function() {
      const $s = $(this).closest('.rec-sec');
      $s.toggleClass('closed');
      $s.children('.rec-sec-body').slideToggle(120);
    });

    function renderCard(e, no) {
      const b = e.bundle || {};
      const idx = indexBundle(b);
      const $card = $('<div class="rec-card"></div>');

      // Header actors: the Composition names the author (practitioner), the
      // custodian (facility) and the subject; fall back to whatever the bundle has.
      let comp = null,
        patient = null,
        org = null,
        prac = null;
      idx.all.forEach(function(r) {
        if (r.resourceType === 'Composition' && !comp) comp = r;
        if (r.resourceType === 'Patient' && !patient) patient = r;
        if (r.resourceType === 'Organization' && !org) org = r;
        if (r.resourceType === 'Practitioner' && !prac) prac = r;
      });
      if (comp) {
        const a = deref(idx, (comp.author || [])[0]);
        if (a && a.resourceType === 'Practitioner') prac = a;
        else if (a && a.resourceType === 'PractitionerRole') prac = deref(idx, a.practitioner) || prac;
        const cu = deref(idx, comp.custodian);
        if (cu && cu.resourceType === 'Organization') org = cu;
        const su = deref(idx, comp.subject);
        if (su && su.resourceType === 'Patient') patient = su;
      }

      const title = (comp && (comp.title || ccText(comp.type))) || e.hiType ||
        (idx.all.length ? 'Health record' : '(empty record)');
      const compEvent = comp && comp.event && comp.event[0] && comp.event[0].period;
      const when = fmtWhen((comp && comp.date) || compEvent || '');

      // Source HIP = the facility's ABDM provider id (facility.ndhm.gov.in) — that
      // is what identifies the sending HIP, not the internal resource id.
      let hipId = '';
      if (org) {
        (org.identifier || []).forEach(function(i) {
          const sys = String(i.system || '');
          const code = (i.type && i.type.coding && i.type.coding[0] && i.type.coding[0].code) || '';
          if (!hipId && (/facility|ndhm|abdm/i.test(sys) || code === 'PRN')) hipId = i.value || '';
        });
        if (!hipId && org.identifier && org.identifier[0]) hipId = org.identifier[0].value || '';
      }

      const $head = $('<div class="rec-head"></div>');
      const $hm = $('<div class="rec-head-main"></div>');
      $hm.append($('<div class="rec-head-title"></div>').append(svgIcon('doc', 17)).append($('<span></span>').text(title)));
      $hm.append($('<div class="rec-head-sub"></div>').text('Record #' + no));
      if (e.careContextReference) $hm.append($('<div class="rec-head-sub"></div>').text(e.careContextReference));
      if (hipId) $hm.append($('<div class="rec-head-sub"></div>').text('Source HIP: ' + hipId));
      $head.append($hm);
      if (when) $head.append($('<span class="rec-date-pill"></span>').text(when));
      $card.append($head);

      // Integrity flags from the gateway — a record that failed to decrypt, or
      // whose checksum did not match, must not look like clean clinical data.
      if (e.decryptStatus && e.decryptStatus !== 'OK') {
        $card.append($('<div class="rec-warn"></div>').text('⚠ This record could not be decrypted — content may be incomplete.'));
      } else if (e.checksumValid === false) {
        $card.append($('<div class="rec-warn"></div>').text('⚠ Checksum mismatch — this record may have been altered in transit.'));
      }

      const GENDERS = {
        male: 'Male',
        female: 'Female',
        other: 'Other',
        unknown: 'Unknown'
      };
      const $meta = $('<div class="rec-meta"></div>');
      const pSub = patient ? [GENDERS[String(patient.gender || '').toLowerCase()] || capFirst(patient.gender), ageFrom(patient.birthDate)]
        .filter(Boolean).join(' · ') : '';
      $meta.append(metaRow('user', 'ic-pat', 'Patient', patient ? humanName(patient.name) : '', pSub));
      const dSub = prac ? ccText((prac.qualification || [])[0] && prac.qualification[0].code) : '';
      $meta.append(metaRow('stetho', 'ic-doc', 'Practitioner', prac ? humanName(prac.name) : '', dSub));
      $meta.append(metaRow('hospital', 'ic-fac', 'Facility', org ? (org.name || '') : ''));
      $card.append($meta);

      // ── Sections: one per Composition.section, in the sender's own order ────
      const used = [];
      const blocks = [];
      const sections = [];
      (function flatten(list) {
        (list || []).forEach(function(sec) {
          if (!sec) return;
          sections.push(sec);
          if (sec.section) flatten(sec.section);
        });
      })(comp && comp.section);

      sections.forEach(function(sec) {
        const items = [],
          docs = [];
        [].concat(sec.entry || []).forEach(function(ref) {
          const r = deref(idx, ref);
          if (!r) {
            // The Composition points at a resource the sender did not actually put
            // in the bundle. That happens: a HIP lists a DocumentReference entry in
            // its section but omits the resource, so there is nothing to open.
            // Rendering just the section heading looked like OUR viewer had failed,
            // so say plainly that the sender left it out.
            const missingType = (ref && ref.type) ? humanType(String(ref.type)) : 'item';
            items.push({
              title: (ref && ref.display) ? String(ref.display) : missingType,
              lines: ['Not included in this record — the sending facility referenced this ' +
                      missingType.toLowerCase() + ' but did not send it.'],
              when: '',
              missing: true
            });
            return;
          }
          if (used.indexOf(r) === -1) used.push(r);
          markSupporting(r, idx, used);
          // Section title is the only sensible name for a bare Binary — it has
          // no type/code of its own to label the download with.
          collectAttachments(r, docs, sec.title || ccText(sec.code));
          if (r.resourceType === 'DocumentReference' || r.resourceType === 'Media') return;
          const d = describeResource(r, idx);
          if (d) items.push(d);
        });
        blocks.push({
          title: sec.title || ccText(sec.code) || 'Details',
          items: items,
          docs: docs,
          narrative: divText(sec.text && sec.text.div)
        });
      });

      const leftover = idx.all.filter(function(r) {
        if (used.indexOf(r) !== -1) return false;
        // Binary is in SKIP_TYPES so describeResource never renders its base64 as
        // text — but it must still reach collectAttachments, otherwise a document
        // no section happens to reference is dropped entirely.
        if (r.resourceType === 'Binary') return !!(r.data || r.url);
        return SKIP_TYPES.indexOf(r.resourceType) === -1;
      });
      if (!blocks.length) {
        // No Composition sections at all — group the resources by type so the data
        // is still readable.
        const byType = {};
        leftover.forEach(function(r) {
          (byType[r.resourceType] = byType[r.resourceType] || []).push(r);
        });
        Object.keys(byType).forEach(function(tp) {
          const items = [],
            docs = [];
          byType[tp].forEach(function(r) {
            collectAttachments(r, docs);
            if (r.resourceType === 'DocumentReference' || r.resourceType === 'Media') return;
            const d = describeResource(r, idx);
            if (d) items.push(d);
          });
          blocks.push({
            title: humanType(tp),
            items: items,
            docs: docs,
            narrative: ''
          });
        });
      } else {
        // Resources no section referenced still belong to the record.
        const extraDocs = [],
          extraItems = [];
        leftover.forEach(function(r) {
          collectAttachments(r, extraDocs);
          if (r.resourceType === 'DocumentReference' || r.resourceType === 'Media') return;
          const d = describeResource(r, idx);
          if (d) extraItems.push(d);
        });
        if (extraItems.length) blocks.push({
          title: 'Other Details',
          items: extraItems,
          docs: [],
          narrative: ''
        });
        if (extraDocs.length) blocks.push({
          title: 'Supporting Documents',
          items: [],
          docs: extraDocs,
          narrative: ''
        });
      }

      if (!blocks.length) {
        blocks.push({
          title: 'Details',
          items: [],
          docs: [],
          narrative: divText(comp && comp.text && comp.text.div)
        });
      }

      blocks.forEach(function(bl, i) {
        $card.append(sectionBlock(bl.title, bl.items, bl.docs, bl.narrative, i < 2));
      });

      // Footer: what the bundle actually contained.
      const census = {};
      idx.all.forEach(function(r) {
        census[r.resourceType] = (census[r.resourceType] || 0) + 1;
      });
      $card.append($('<div class="rec-foot"></div>')
        .append($('<div class="rec-counts"></div>').text(Object.keys(census).map(function(k) {
          return humanType(k) + ' ' + census[k];
        }).join(' · '))));
      return $card;
    }

    // When was this bundle generated? The HIP rebuilds a bundle on every push,
    // stamping a fresh Composition.date, so this is what tells two copies of the
    // same care context apart.
    function bundleGeneratedAt(e) {
      const b = (e && e.bundle) || {};
      let t = b.timestamp || '';
      (b.entry || []).forEach(function(en) {
        const r = en && en.resource;
        if (r && r.resourceType === 'Composition' && r.date) t = r.date;
      });
      const ms = Date.parse(t);
      return isNaN(ms) ? 0 : ms;
    }

    // One consent can be fulfilled by several HI transfers — the HIP re-pushes,
    // or the fetch runs twice — and each push re-sends the SAME care contexts as
    // a freshly built bundle (new resource uuids, new Composition.date). Those
    // arrive as separate entries and were all rendered, so the same prescription
    // appeared two or three times over.
    //
    // careContextReference is the stable identity of a record, so key on it (plus
    // hiType, in case one care context is shared for more than one HI type) and
    // keep the most recently generated copy — that is the freshest data. Entries
    // with no careContextReference have no stable key and are always kept.
    function dedupeEntries(list) {
      const slots = [];
      const posByKey = {};
      let dropped = 0;

      list.forEach(function(e) {
        const cc = String((e && e.careContextReference) || '').trim();
        if (!cc) {
          slots.push(e);
          return;
        }
        const key = cc + '|' + String((e && e.hiType) || '');
        if (!(key in posByKey)) {
          posByKey[key] = slots.length;
          slots.push(e);
          return;
        }
        dropped++;
        const i = posByKey[key];
        if (bundleGeneratedAt(e) >= bundleGeneratedAt(slots[i])) slots[i] = e;
      });

      return { list: slots, dropped: dropped };
    }

    function renderBundles(entries) {
      // Records arrived, so any parked "0 care-contexts" warning was wrong.
      pendingCoverageWarning = null;
      ATTACH.length = 0; // rebuilt on every render; rows index into it
      const res = dedupeEntries(entries || []);
      const list = res.list;
      const $r = $('#results').empty();
      if (!list.length) return;
      const $total = $('<div class="rec-total"></div>')
        .text(list.length + (list.length === 1 ? ' record' : ' records'));
      // Say so rather than silently showing a smaller number than was received.
      if (res.dropped) {
        $total.append($('<span class="rec-total-note"></span>')
          .text(' · ' + res.dropped + ' duplicate ' +
            (res.dropped === 1 ? 'copy' : 'copies') + ' hidden'));
      }
      $r.append($total);
      list.forEach(function(e, i) {
        $r.append(renderCard(e, i + 1));
      });
    }

    // Flatten a FHIR narrative <div> to plain text, preserving its line breaks.
    function divText(div) {
      if (!div) return '';
      return $('<div>').html(String(div).replace(/<br\s*\/?>/gi, '\n').replace(/<\/(p|div|tr|li|h[1-6]|td|th)>/gi, '\n')).text().replace(/ /g, ' ').replace(/[ \t]+\n/g, '\n').trim();
    }

  </script>
<script src="js/field_validation.js?v=<?= @filemtime(__DIR__ . '/js/field_validation.js') ?: 1 ?>"></script>
</body>

</html>