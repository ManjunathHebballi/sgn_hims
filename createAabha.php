<?php
// Force the project session path before $_SESSION is touched. session_config.php
// starts the session itself; calling session_start() ahead of it would bind this
// page to PHP's default save path and leave $_SESSION empty (leftbar.php would
// then bounce a logged-in user back to index.php).
require_once __DIR__ . '/model/session_config.php';
require_once __DIR__ . '/config/abdm_config.php'; // FACILITY_NAME for the consent text
date_default_timezone_set(env('TZ', 'Asia/Kolkata'));

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Create Abha Card</title>
  <!-- Favicon icon -->
  <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">

  <link href="./css/style.css" rel="stylesheet">

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    /* ===== BASE ===== */
    .content-body {
      background: #eef2fb;
      font-family: 'Inter', system-ui, sans-serif;
    }

    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }

    input[type=number] {
      -moz-appearance: textfield;
    }

    /* ===== HERO BANNER ===== */
    .abha-hero {
      background: linear-gradient(135deg, #1a56db 0%, #0a7a4d 100%);
      padding: 16px 36px;
      position: relative;
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 20px;
    }

    .abha-hero::before {
      content: '';
      position: absolute;
      top: -80px;
      right: -80px;
      width: 260px;
      height: 260px;
      background: rgba(255, 255, 255, 0.05);
      border-radius: 50%;
      pointer-events: none;
    }

    .abha-hero::after {
      content: '';
      position: absolute;
      bottom: -70px;
      right: 140px;
      width: 160px;
      height: 160px;
      background: rgba(255, 255, 255, 0.04);
      border-radius: 50%;
      pointer-events: none;
    }

    .abha-hero-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: rgba(255, 255, 255, 0.15);
      border: 1px solid rgba(255, 255, 255, 0.25);
      color: white;
      padding: 4px 14px;
      border-radius: 20px;
      font-size: 11px;
      font-weight: 700;
      letter-spacing: 1px;
      margin-bottom: 10px;
    }

    .abha-hero h2 {
      color: white;
      font-size: 26px;
      font-weight: 800;
      margin: 0 0 5px;
    }

    .abha-hero p {
      color: rgba(255, 255, 255, 0.7);
      margin: 0;
      font-size: 14px;
    }

    .btn-create-abha {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      background: white;
      color: #1a56db;
      border: none;
      padding: 10px 22px;
      border-radius: 10px;
      font-size: 14px;
      font-weight: 700;
      cursor: pointer;
      box-shadow: 0 4px 14px rgba(0, 0, 0, 0.18);
      flex-shrink: 0;
      position: relative;
      z-index: 1;
      transition: all .2s;
      font-family: inherit;
      white-space: nowrap;
    }

    .btn-create-abha:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.22);
    }

    /* ===== WORKSPACE ===== */
    .abha-workspace {
      display: grid;
      /* Search view: a single, centered column (the card is hidden). */
      grid-template-columns: minmax(0, 560px);
      justify-content: center;
      gap: 28px;
      padding: 28px 36px;
      align-items: start;
    }

    /* When the profile card is shown (search panel hidden), use the full width. */
    .abha-workspace.full-card {
      grid-template-columns: 1fr;
    }

    .btn-back-search {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: #eef2f7;
      color: #33475b;
      border: 1px solid #d9dee6;
      border-radius: 8px;
      padding: 8px 16px;
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      margin-bottom: 14px;
    }

    .btn-back-search:hover {
      background: #e2e8f0;
    }

    @media(max-width:900px) {
      .abha-workspace {
        grid-template-columns: 1fr;
        padding: 18px;
      }

      .abha-hero {
        padding: 16px 18px;
        flex-wrap: wrap;
      }
    }

    /* ===== SEARCH PANEL ===== */
    .abha-search-panel {
      background: white;
      border-radius: 20px;
      box-shadow: 0 4px 24px rgba(0, 0, 0, 0.07);
      overflow: hidden;
    }

    .panel-head {
      padding: 20px 22px 0;
    }

    .panel-title {
      font-size: 14px;
      font-weight: 700;
      color: #1a1a2e;
      margin-bottom: 14px;
    }

    /* Tabs */
    .search-tabs {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      margin: 0 -22px;
      border-bottom: 2px solid #f0f0f0;
    }

    .stab {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 3px;
      padding: 11px 6px;
      border: none;
      background: none;
      cursor: pointer;
      font-size: 10px;
      font-weight: 600;
      color: #999;
      position: relative;
      transition: color .2s;
      font-family: inherit;
      letter-spacing: .3px;
    }

    .stab i {
      font-size: 19px;
    }

    .stab::after {
      content: '';
      position: absolute;
      bottom: -2px;
      left: 0;
      right: 0;
      height: 2px;
      background: #1a56db;
      transform: scaleX(0);
      transition: transform .2s;
    }

    .stab.active {
      color: #1a56db;
    }

    .stab.active::after {
      transform: scaleX(1);
    }

    /* Input area */
    .panel-body {
      padding: 20px 22px;
    }

    .sinput-wrap {
      position: relative;
      margin-bottom: 14px;
    }

    .sinput-ico {
      position: absolute;
      left: 13px;
      top: 50%;
      transform: translateY(-50%);
      color: #aaa;
      font-size: 18px;
    }

    .sinput {
      width: 100%;
      padding: 13px 13px 13px 40px;
      border: 2px solid #e8e8e8;
      border-radius: 12px;
      font-size: 15px;
      font-family: inherit;
      outline: none;
      transition: border-color .2s;
      height: auto;
    }

    .sinput:focus {
      border-color: #1a56db;
      box-shadow: 0 0 0 3px rgba(26, 86, 219, 0.08);
    }

    .sinput::placeholder {
      color: #ccc;
    }

    /* Mode toggle */
    .mode-wrap {
      margin-bottom: 14px;
    }

    .mode-lbl {
      font-size: 11px;
      font-weight: 700;
      color: #888;
      letter-spacing: .5px;
      text-transform: uppercase;
      margin-bottom: 8px;
      display: block;
    }

    .mode-opts {
      display: flex;
      gap: 8px;
    }

    .mode-opt {
      flex: 1;
      position: relative;
    }

    .mode-opt input {
      position: absolute;
      opacity: 0;
      width: 0;
      height: 0;
    }

    .mode-opt label {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 5px;
      padding: 9px 10px;
      border: 2px solid #e8e8e8;
      border-radius: 10px;
      cursor: pointer;
      font-size: 12px;
      font-weight: 600;
      color: #777;
      transition: all .15s;
    }

    .mode-opt input:checked+label {
      border-color: #1a56db;
      background: #f0f4ff;
      color: #1a56db;
    }

    .btn-search {
      width: 100%;
      padding: 13px;
      border: none;
      border-radius: 12px;
      background: linear-gradient(135deg, #1a56db, #1e3a8a);
      color: white;
      font-size: 15px;
      font-weight: 700;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 7px;
      font-family: inherit;
      box-shadow: 0 4px 14px rgba(26, 86, 219, 0.28);
      transition: all .2s;
    }

    .btn-search:hover {
      transform: translateY(-1px);
      box-shadow: 0 6px 20px rgba(26, 86, 219, 0.38);
    }

    /* OTP section */
    #abha_verify1 {
      display: none;
      border-top: 1px solid #f0f0f0;
    }

    .otp-section {
      padding: 20px 22px;
      background: #fafbff;
    }

    .otp-prompt {
      display: flex;
      align-items: center;
      gap: 8px;
      background: #e8f0fe;
      border-radius: 10px;
      padding: 10px 14px;
      font-size: 13px;
      color: #3d5a99;
      font-weight: 500;
      margin-bottom: 16px;
    }

    .otp-prompt i {
      font-size: 16px;
      color: #1a56db;
    }

    .otp-grid {
      display: grid;
      grid-template-columns: repeat(6, 1fr);
      gap: 8px;
      margin-bottom: 14px;
    }

    .otp-digit {
      height: 52px;
      border: 2px solid #dde4ff;
      border-radius: 10px;
      font-size: 22px;
      font-weight: 700;
      text-align: center;
      background: white;
      outline: none;
      color: #1a1a2e;
      transition: all .15s;
      width: 100%;
      font-family: inherit;
      height: auto;
      padding: 10px 0;
    }

    .otp-digit:focus {
      border-color: #1a56db;
      box-shadow: 0 0 0 3px rgba(26, 86, 219, 0.1);
    }

    .btn-verify {
      width: 100%;
      padding: 13px;
      border: none;
      border-radius: 12px;
      background: linear-gradient(135deg, #0a7a4d, #065f38);
      color: white;
      font-size: 15px;
      font-weight: 700;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 7px;
      font-family: inherit;
      box-shadow: 0 4px 12px rgba(10, 122, 77, 0.28);
      transition: all .2s;
    }

    .btn-verify:hover {
      transform: translateY(-1px);
      box-shadow: 0 6px 18px rgba(10, 122, 77, 0.38);
    }

    /* ===== ABHA ID CARD — Official NHA Style ===== */
    #aabha_card_section {
      display: none;
    }

    @keyframes fadeUp {
      from {
        opacity: 0;
        transform: translateY(18px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .abha-id-card {
      background: #fff;
      border-radius: 14px;
      overflow: hidden;
      box-shadow: 0 8px 40px rgba(0, 0, 0, 0.16);
      border: 1px solid #dde3ef;
      animation: fadeUp .4s ease;
      max-width: 640px;
    }

    /* Blue NHA header band */
    .card-topband {
      background: #003087;
      padding: 13px 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 10px;
    }

    .nha-logo-block {
      display: flex;
      align-items: center;
      gap: 10px;
      flex-shrink: 0;
    }

    .nha-emblem {
      width: 46px;
      height: 46px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.15);
      border: 2px solid rgba(255, 255, 255, 0.35);
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .nha-emblem i {
      font-size: 22px;
      color: #fff;
    }

    .nha-text-block {}

    .nha-text-eng {
      color: #fff;
      font-size: 11px;
      font-weight: 700;
      line-height: 1.3;
    }

    .nha-text-hin {
      color: rgba(255, 255, 255, .75);
      font-size: 10px;
      line-height: 1.3;
    }

    .card-centre-text {
      flex: 1;
      text-align: center;
    }

    .cct-eng {
      color: #fff;
      font-size: 15px;
      font-weight: 800;
      line-height: 1.3;
      display: block;
    }

    .cct-hin {
      color: rgba(255, 255, 255, .8);
      font-size: 13px;
      display: block;
    }

    .abdm-logo-block {
      width: 46px;
      height: 46px;
      border-radius: 50%;
      background: white;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    }

    .abdm-logo-block i {
      font-size: 22px;
      color: #003087;
    }

    /* Card body: photo + info + QR */
    .card-body-row {
      display: flex;
      align-items: flex-start;
      gap: 18px;
      padding: 18px 20px 16px;
      border-bottom: 1px solid #edf0f7;
    }

    .card-photo-box {
      flex-shrink: 0;
    }

    .card-photo-box img {
      width: 100px;
      height: 120px;
      object-fit: cover;
      border: 1.5px solid #c8d0e0;
      display: block;
      background: #f4f6fb;
    }

    .card-info-block {
      flex: 1;
      min-width: 0;
    }

    .ci-label {
      font-size: 10px;
      color: #888;
      font-weight: 600;
      letter-spacing: .4px;
    }

    .ci-name-eng {
      font-size: 20px;
      font-weight: 800;
      color: #1a1a2e;
      line-height: 1.2;
      margin: 1px 0 0;
    }

    .ci-name-hin {
      font-size: 12px;
      color: #666;
      margin-bottom: 10px;
    }

    .ci-field-label {
      font-size: 10px;
      color: #888;
      font-weight: 600;
      letter-spacing: .4px;
      margin-top: 8px;
      display: block;
    }

    .ci-abha-number {
      font-size: 22px;
      font-weight: 900;
      color: #003087;
      letter-spacing: .5px;
      margin: 1px 0 8px;
      display: block;
    }

    .ci-abha-address {
      font-size: 15px;
      font-weight: 700;
      color: #003087;
      margin: 1px 0 0;
      display: block;
      /* ABHA addresses are case-insensitive — always render lowercase. */
      text-transform: lowercase;
    }

    .card-qr-block {
      flex-shrink: 0;
      display: flex;
      align-items: center;
    }

    #qr-code-wrap {
      width: 90px;
      height: 90px;
    }

    #qr-code-wrap canvas,
    #qr-code-wrap img {
      width: 90px !important;
      height: 90px !important;
    }

    /* Bottom info strip */
    .card-strip {
      display: flex;
      padding: 14px 20px;
      gap: 0;
      background: #f7f9ff;
      border-bottom: 1px solid #edf0f7;
    }

    .strip-col {
      flex: 1;
      border-right: 1px solid #dde3ef;
      padding: 0 16px;
    }

    .strip-col:first-child {
      padding-left: 0;
    }

    .strip-col:last-child {
      border-right: none;
    }

    .strip-lbl {
      font-size: 10px;
      color: #888;
      font-weight: 600;
      letter-spacing: .4px;
      display: block;
      margin-bottom: 3px;
    }

    .strip-val {
      font-size: 14px;
      font-weight: 700;
      color: #1a1a2e;
      display: block;
    }

    .strip-hin {
      font-size: 10px;
      color: #888;
    }

    /* Download footer */
    .card-foot {
      padding: 14px 20px;
      background: #fff;
    }

    #download_abha {
      width: 100%;
      padding: 12px;
      background: #003087;
      border: none;
      color: white;
      border-radius: 10px;
      font-size: 14px;
      font-weight: 700;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      transition: all .2s;
      font-family: inherit;
      box-shadow: 0 4px 14px rgba(0, 48, 135, 0.28);
    }

    #download_abha:hover {
      background: #002070;
      transform: translateY(-1px);
    }

    /* ===== MOBILE COMPARE ===== */
    #mobileCompareCard {
      display: none;
      margin-top: 16px;
    }

    .mc-card {
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 4px 18px rgba(245, 158, 11, 0.14);
    }

    .mc-head {
      background: linear-gradient(90deg, #f59e0b, #d97706);
      padding: 12px 20px;
      display: flex;
      align-items: center;
      gap: 8px;
      font-weight: 700;
      color: white;
      font-size: 13px;
    }

    .mc-body {
      background: white;
      padding: 16px 20px;
    }

    .mc-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 7px 0;
      border-bottom: 1px solid #f8f8f8;
      font-size: 13px;
    }

    .mc-row:last-of-type {
      border-bottom: none;
    }

    .mc-lbl {
      color: #999;
      font-weight: 600;
      font-size: 11px;
      text-transform: uppercase;
      letter-spacing: .5px;
    }

    .mc-val {
      font-weight: 700;
      color: #1a1a2e;
    }

    #mobile_compare_msg {
      font-size: 12px;
      font-weight: 600;
      margin-top: 8px;
    }

    #updateProfileMobileBtn {
      margin-top: 10px;
      padding: 8px 18px;
      border-radius: 10px;
      background: #1a56db;
      color: white;
      border: none;
      font-weight: 700;
      cursor: pointer;
      font-size: 13px;
      font-family: inherit;
    }

    /* ===== LOADER ===== */
    #loader-wrapper {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(255, 255, 255, 0.88);
      z-index: 9999;
      display: none;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      gap: 14px;
    }

    .loader-ring {
      width: 52px;
      height: 52px;
      border: 4px solid #e0e7ff;
      border-top-color: #1a56db;
      border-radius: 50%;
      animation: spin .8s linear infinite;
    }

    .loader-txt {
      color: #1a56db;
      font-size: 13px;
      font-weight: 700;
    }

    @keyframes spin {
      to {
        transform: rotate(360deg);
      }
    }

    /* ===== MODALS ===== */
    .modal-content {
      border-radius: 20px;
      border: none;
      box-shadow: 0 24px 80px rgba(0, 0, 0, 0.22);
      overflow: hidden;
    }

    .modal-body {
      padding: 22px;
    }

    /* Gradient header used across all three modals */
    .abha-modal-header {
      background: linear-gradient(135deg, #003087, #1a56db);
      padding: 20px 22px;
      display: flex;
      align-items: center;
      gap: 14px;
    }

    .abha-modal-icon {
      width: 44px;
      height: 44px;
      border-radius: 12px;
      background: rgba(255, 255, 255, 0.18);
      border: 1.5px solid rgba(255, 255, 255, 0.3);
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .abha-modal-icon i {
      font-size: 21px;
      color: #fff;
    }

    .abha-modal-title {
      color: #fff;
      font-size: 16px;
      font-weight: 800;
      margin: 0 0 2px;
      line-height: 1.2;
    }

    .abha-modal-subtitle {
      color: rgba(255, 255, 255, 0.72);
      font-size: 11.5px;
      margin: 0;
    }

    .abha-modal-close {
      margin-left: auto;
      background: rgba(255, 255, 255, 0.15);
      border: none;
      color: white;
      width: 30px;
      height: 30px;
      border-radius: 8px;
      font-size: 18px;
      line-height: 1;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .abha-modal-close:hover {
      background: rgba(255, 255, 255, 0.28);
    }

    /* OTP inputs inside modals */
    .modal-otp-box {
      display: flex;
      justify-content: space-between;
      gap: 6px;
      margin: 10px 0;
    }

    .modal-otp-input {
      flex: 1;
      min-width: 0;
      height: 44px;
      border: 2px solid #dde4ff;
      border-radius: 10px;
      font-size: 17px;
      font-weight: 700;
      text-align: center;
      background: #fff;
      outline: none;
      color: #1a1a2e;
      transition: all .15s;
      -moz-appearance: textfield;
      padding: 0;
    }

    .modal-otp-input::-webkit-inner-spin-button,
    .modal-otp-input::-webkit-outer-spin-button {
      -webkit-appearance: none;
    }

    .modal-otp-input:focus {
      border-color: #1a56db;
      box-shadow: 0 0 0 3px rgba(26, 86, 219, 0.08);
    }

    /* Legacy otp-input/otp-box aliases (unchanged for backward compat) */
    .otp-input {
      width: 45px;
      padding: 10px 0;
      border: 2px solid #dde4ff;
      border-radius: 10px;
      font-size: 22px;
      font-weight: 700;
      text-align: center;
      background: white;
      outline: none;
      color: #1a1a2e;
      transition: all .15s;
      height: auto;
      -moz-appearance: textfield;
    }

    .otp-input::-webkit-inner-spin-button,
    .otp-input::-webkit-outer-spin-button {
      -webkit-appearance: none;
    }

    .otp-input:focus {
      border-color: #1a56db;
      box-shadow: 0 0 0 3px rgba(26, 86, 219, 0.1);
    }

    .otp-box {
      display: flex;
      justify-content: space-between;
      gap: 8px;
    }

    /* Info banner inside modals */
    .modal-otp-banner {
      background: #e8f0fe;
      border-radius: 10px;
      padding: 10px 14px;
      display: flex;
      align-items: flex-start;
      gap: 8px;
      font-size: 12px;
      color: #3d5a99;
      margin-bottom: 14px;
      line-height: 1.5;
    }

    .modal-otp-banner i {
      color: #1a56db;
      font-size: 15px;
      flex-shrink: 0;
      margin-top: 1px;
    }

    /* Modal action buttons */
    .btn-modal-primary {
      width: 100%;
      padding: 13px;
      border: none;
      border-radius: 12px;
      background: linear-gradient(135deg, #1a56db, #1e3a8a);
      color: white;
      font-size: 14px;
      font-weight: 700;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      font-family: inherit;
      box-shadow: 0 4px 14px rgba(26, 86, 219, 0.28);
      transition: all .2s;
    }

    .btn-modal-primary:hover {
      transform: translateY(-1px);
      box-shadow: 0 6px 20px rgba(26, 86, 219, 0.36);
    }

    .btn-modal-primary:disabled {
      opacity: .5;
      cursor: not-allowed;
      transform: none;
    }

    .btn-modal-success {
      width: 100%;
      padding: 13px;
      border: none;
      border-radius: 12px;
      background: linear-gradient(135deg, #0a7a4d, #065f38);
      color: white;
      font-size: 14px;
      font-weight: 700;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      font-family: inherit;
      box-shadow: 0 4px 12px rgba(10, 122, 77, 0.25);
      transition: all .2s;
    }

    .btn-modal-success:hover {
      transform: translateY(-1px);
      box-shadow: 0 6px 18px rgba(10, 122, 77, 0.35);
    }

    .btn-modal-outline {
      padding: 11px 22px;
      border: 2px solid #e0e0e0;
      border-radius: 12px;
      background: transparent;
      color: #666;
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      font-family: inherit;
      transition: all .15s;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }

    .btn-modal-outline:hover {
      border-color: #aaa;
      color: #333;
    }

    /* Modal footer */
    .abha-modal-footer {
      padding: 14px 22px;
      border-top: 1px solid #f0f0f0;
      display: flex;
      gap: 10px;
      justify-content: flex-end;
    }

    /* Text inputs inside modals */
    .modal-field-label {
      font-size: 11px;
      font-weight: 700;
      color: #888;
      text-transform: uppercase;
      letter-spacing: .5px;
      margin-bottom: 6px;
      display: block;
    }

    .modal-field-input {
      width: 100%;
      padding: 12px 16px;
      border: 2px solid #e8e8e8;
      border-radius: 12px;
      font-size: 15px;
      font-family: inherit;
      outline: none;
      transition: border-color .2s;
      color: #1a1a2e;
      -moz-appearance: textfield;
    }

    .modal-field-input:focus {
      border-color: #1a56db;
      box-shadow: 0 0 0 3px rgba(26, 86, 219, 0.08);
    }

    .modal-field-input::-webkit-inner-spin-button,
    .modal-field-input::-webkit-outer-spin-button {
      -webkit-appearance: none;
    }

    .modal-divider {
      border: none;
      border-top: 1px solid #f0f0f0;
      margin: 16px 0;
    }

    /* Step indicator in enterotp */
    .modal-steps {
      display: flex;
      align-items: flex-start;
      margin-bottom: 20px;
      position: relative;
    }

    .modal-steps::before {
      content: '';
      position: absolute;
      top: 12px;
      left: 12px;
      right: 12px;
      height: 2px;
      background: #e8e8e8;
      z-index: 0;
    }

    .mstep {
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 4px;
      position: relative;
      z-index: 1;
    }

    .mstep-dot {
      width: 24px;
      height: 24px;
      border-radius: 50%;
      background: #e8e8e8;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 11px;
      font-weight: 700;
      color: #aaa;
    }

    .mstep-lbl {
      font-size: 10px;
      color: #aaa;
      font-weight: 600;
      letter-spacing: .3px;
    }

    .mstep.active .mstep-dot {
      background: #1a56db;
      color: #fff;
    }

    .mstep.active .mstep-lbl {
      color: #1a56db;
    }

    .mstep.done .mstep-dot {
      background: #0a7a4d;
      color: #fff;
    }

    .mstep.done .mstep-lbl {
      color: #0a7a4d;
    }

    /* ABHA selection table */
    .abha-select-table {
      width: 100%;
      border-collapse: collapse;
    }

    .abha-select-table thead th {
      padding: 10px 16px;
      background: #f0f4ff;
      color: #1a56db;
      font-size: 10.5px;
      font-weight: 700;
      letter-spacing: .5px;
      text-transform: uppercase;
      border-bottom: 2px solid #d8e2ff;
      white-space: nowrap;
    }

    .abha-select-table tbody tr {
      border-bottom: 1px solid #f4f6fc;
      cursor: pointer;
      transition: background .15s;
    }

    .abha-select-table tbody tr:hover {
      background: #f0f6ff;
    }

    .abha-select-table td {
      padding: 10px 16px;
      vertical-align: middle;
      font-size: 13px;
      color: #333;
    }

    .abha-select-table input[type=radio] {
      accent-color: #1a56db;
      width: 16px;
      height: 16px;
    }

    /* Consent box */
    .consent-box {
      max-height: 340px;
      overflow-y: auto;
      padding: 12px;
      margin-top: 12px;
      border: 1px solid #e8e8e8;
      border-radius: 10px;
      background: #f8f9fa;
      font-size: 12px;
      line-height: 1.6;
      color: #555;
    }

    .consent-lang-row {
      display: flex;
      align-items: center;
      gap: 8px;
      margin-top: 12px;
    }

    .consent-lang-select {
      border: 1px solid #d0d9ee;
      border-radius: 8px;
      padding: 4px 8px;
      font-size: 12px;
      color: #1a3a6b;
      background: #f7faff;
      outline: none;
    }

    /* Resend OTP */
    .resend-row {
      font-size: 12px;
      color: #7a8794;
      margin-top: 8px;
    }

    .resend-link {
      color: #1565c0;
      font-weight: 600;
      cursor: pointer;
      text-decoration: underline;
    }

    .resend-link.disabled {
      color: #b0bac4;
      cursor: not-allowed;
      text-decoration: none;
    }

    .resend-timer {
      color: #9aa7b4;
      margin-left: 4px;
    }

    .consent-check {
      display: flex;
      align-items: flex-start;
      gap: 8px;
      margin-top: 10px;
      font-size: 13px;
    }

    /* TC-008 ABHA address picker */
    .aaddr-hint {
      font-size: 13px;
      color: #5b6b7b;
      margin-bottom: 14px;
    }

    .aaddr-hint code {
      background: #eef2f7;
      padding: 1px 5px;
      border-radius: 4px;
    }

    .aaddr-block {
      margin-bottom: 16px;
    }

    .aaddr-label {
      display: block;
      font-weight: 700;
      font-size: 13px;
      color: #33475b;
      margin-bottom: 8px;
    }

    .aaddr-suggest-list {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      max-height: 168px;
      overflow-y: auto;
    }

    .aaddr-chip {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      cursor: pointer;
      border: 1px solid #d5dde5;
      border-radius: 20px;
      padding: 6px 12px;
      font-size: 13px;
      background: #fff;
      transition: all .15s;
    }

    .aaddr-chip:hover {
      border-color: #2b6cb0;
    }

    .aaddr-chip.selected {
      background: #2b6cb0;
      color: #fff;
      border-color: #2b6cb0;
    }

    .aaddr-chip input {
      display: none;
    }

    .aaddr-input {
      width: 100%;
      padding: 10px 12px;
      border: 1px solid #d5dde5;
      border-radius: 8px;
      font-size: 14px;
      outline: none;
    }

    .aaddr-input:focus {
      border-color: #2b6cb0;
    }

    .aaddr-rules {
      list-style: none;
      padding: 0;
      margin: 10px 0 0;
      font-size: 12px;
    }

    .aaddr-rules li {
      padding: 2px 0 2px 20px;
      position: relative;
      color: #8a97a4;
    }

    .aaddr-rules li::before {
      content: "○";
      position: absolute;
      left: 2px;
    }

    .aaddr-rules li.ok {
      color: #0a7a4d;
    }

    .aaddr-rules li.ok::before {
      content: "✓";
    }

    .aaddr-rules li.bad {
      color: #d9534f;
    }

    .aaddr-rules li.bad::before {
      content: "✕";
    }

    .aaddr-msg {
      font-size: 13px;
      margin-top: 10px;
      min-height: 18px;
    }

    .aaddr-msg.err {
      color: #d9534f;
    }

    .aaddr-msg.ok {
      color: #0a7a4d;
    }

    .btn-modal-secondary {
      background: #eef2f7;
      color: #33475b;
      border: none;
      border-radius: 8px;
      padding: 9px 16px;
      font-weight: 600;
      cursor: pointer;
    }

    /* TC-011/017/023/025 link & register */
    .btn-abha-action {
      background: #fff;
      color: #2b6cb0;
      border: 1px solid #2b6cb0;
      border-radius: 8px;
      padding: 8px 14px;
      font-weight: 600;
      font-size: 13px;
      cursor: pointer;
      transition: all .15s;
    }

    .btn-abha-action:hover {
      background: #2b6cb0;
      color: #fff;
    }

    .btn-abha-action:disabled {
      opacity: .5;
      cursor: not-allowed;
    }

    .abha-link-badge {
      margin: 0 20px 12px;
      padding: 9px 12px;
      border-radius: 8px;
      font-size: 13px;
      font-weight: 600;
    }

    .abha-link-badge.linked {
      background: #e7f6ee;
      color: #0a7a4d;
      border: 1px solid #b7e4c7;
    }

    .abha-link-badge.unlinked {
      background: #fff6e6;
      color: #9a6b00;
      border: 1px solid #ffe0a3;
    }

    .link-results {
      max-height: 300px;
      overflow-y: auto;
    }

    .link-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 10px;
      border: 1px solid #e4e9ef;
      border-radius: 8px;
      padding: 10px 12px;
      margin-bottom: 8px;
    }

    .link-row .lp-info {
      font-size: 13px;
      color: #33475b;
    }

    .link-row .lp-info b {
      color: #1a2533;
    }

    .link-row .lp-sub {
      color: #7a8896;
      font-size: 12px;
    }

    .link-row .lp-linked {
      font-size: 11px;
      color: #0a7a4d;
      font-weight: 700;
    }

    .btn-link-pick {
      background: #2b6cb0;
      color: #fff;
      border: none;
      border-radius: 6px;
      padding: 6px 12px;
      font-size: 12px;
      font-weight: 600;
      cursor: pointer;
      white-space: nowrap;
    }

    .btn-link-pick:disabled {
      background: #b7c3d0;
      cursor: not-allowed;
    }

    /* Register-from-ABHA comparison */
    .btn-cmp-pick {
      background: #2b6cb0;
      color: #fff;
      border: none;
      border-radius: 6px;
      padding: 6px 12px;
      font-size: 12px;
      font-weight: 600;
      cursor: pointer;
      white-space: nowrap;
    }

    .btn-cmp-pick.active,
    .btn-cmp-pick:hover {
      background: #1c4e80;
    }

    table.cmp-table th,
    table.cmp-table td {
      vertical-align: middle;
      font-size: 13px;
    }

    table.cmp-table td.cmp-diff {
      background: #fff3cd;
      font-weight: 600;
    }

    /* Biometric (RD service) device status under "Authenticate With" */
    .bio-status {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 10px;
      margin-top: 8px;
      padding: 8px 12px;
      border-radius: 8px;
      font-size: 12px;
      font-weight: 600;
    }

    .bio-status.ready {
      background: #e7f6ee;
      color: #0a7a4d;
      border: 1px solid #b7e4c7;
    }

    .bio-status.missing {
      background: #fff6e6;
      color: #9a6b00;
      border: 1px solid #ffe0a3;
    }

    .bio-status.checking {
      background: #eef2f7;
      color: #33475b;
      border: 1px solid #d9dee6;
    }

    /* Toast notifications (replace blocking alerts) */
    #toastContainer {
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 99999;
      display: flex;
      flex-direction: column;
      gap: 10px;
      max-width: 380px;
    }
    .app-toast {
      color: #fff;
      padding: 12px 16px;
      border-radius: 10px;
      font-size: 13px;
      font-weight: 600;
      line-height: 1.45;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.22);
      opacity: 0;
      transform: translateX(24px);
      transition: opacity .3s ease, transform .3s ease;
      cursor: pointer;
    }
    .app-toast.show { opacity: 1; transform: translateX(0); }
    .app-toast.error   { background: #c62828; }
    .app-toast.success { background: #0a7a4d; }
    .app-toast.info    { background: #1565c0; }
    /* Optional action button (replaces a blocking window.confirm). */
    .app-toast-action {
      margin-top: 10px;
      background: rgba(255, 255, 255, 0.18);
      border: 1px solid rgba(255, 255, 255, 0.5);
      color: #fff;
      border-radius: 8px;
      padding: 6px 14px;
      font-size: 12px;
      font-weight: 700;
      cursor: pointer;
      font-family: inherit;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }
    .app-toast-action:hover { background: rgba(255, 255, 255, 0.32); }
  </style>
</head>

<body>

  <!--*******************
        Preloader start
    ********************-->
  <div id="preloader">
    <div class="sk-three-bounce">
      <div class="sk-child sk-bounce1"></div>
      <div class="sk-child sk-bounce2"></div>
      <div class="sk-child sk-bounce3"></div>
    </div>
  </div>

  <div id="loader-wrapper">
    <div class="loader-ring"></div>
    <div class="loader-txt">Processing…</div>
  </div>
  <!--*******************
        Preloader end
    ********************-->


  <!--**********************************
        Main wrapper start
    ***********************************-->
  <div id="main-wrapper">

    <?php include('leftbar.php') ?>

    <!--**********************************
            Content body start
        ***********************************-->
    <div class="content-body">
      <!-- row -->




      <div class="container-fluid p-0">
        <div class="row no-gutters">
          <div class="col-md-12">
            <div class="modal fade" id="selectAbhaModal" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                  <div class="abha-modal-header">
                    <div class="abha-modal-icon"><i class="ri-id-card-line"></i></div>
                    <div>
                      <p class="abha-modal-title">Select ABHA Profile</p>
                      <p class="abha-modal-subtitle">Multiple profiles found — choose one to continue</p>
                    </div>
                    <button class="abha-modal-close" data-dismiss="modal">&times;</button>
                  </div>
                  <div class="modal-body" style="padding:0;">
                    <div class="table-responsive">
                      <table class="abha-select-table">
                        <thead>
                          <tr>
                            <th>Select</th>
                            <th>ABHA Number</th>
                            <th>Name</th>
                            <th>Gender</th>
                          </tr>
                        </thead>
                        <tbody id="abhaTableBody"></tbody>
                      </table>
                    </div>
                  </div>
                  <div class="abha-modal-footer">
                    <button class="btn-modal-outline" data-dismiss="modal"><i class="ri-close-line"></i> Cancel</button>
                    <button class="btn-modal-primary" id="confirmAbhaSelection" style="width:auto;padding:11px 24px;">
                      <i class="ri-check-line"></i> Continue with Selected
                    </button>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal fade" id="profileOtpModal" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
                <div class="modal-content">
                  <div class="abha-modal-header">
                    <div class="abha-modal-icon"><i class="ri-shield-check-line"></i></div>
                    <div>
                      <p class="abha-modal-title">Verify ABHA Profile</p>
                      <p class="abha-modal-subtitle">Enter the OTP sent to your registered mobile</p>
                    </div>
                    <button class="abha-modal-close" data-dismiss="modal">&times;</button>
                  </div>
                  <div class="modal-body" style="padding:22px;">
                    <div class="modal-otp-banner">
                      <i class="ri-information-line"></i>
                      <span id="profileOtpMsg">An OTP has been sent for selected ABHA profile verification.</span>
                    </div>
                    <div class="modal-otp-box">
                      <input type="number" id="profile_otp_1" class="modal-otp-input" maxlength="1" oninput="moveToNext(this,'profile_otp_2')">
                      <input type="number" id="profile_otp_2" class="modal-otp-input" maxlength="1" oninput="moveToNext(this,'profile_otp_3')">
                      <input type="number" id="profile_otp_3" class="modal-otp-input" maxlength="1" oninput="moveToNext(this,'profile_otp_4')">
                      <input type="number" id="profile_otp_4" class="modal-otp-input" maxlength="1" oninput="moveToNext(this,'profile_otp_5')">
                      <input type="number" id="profile_otp_5" class="modal-otp-input" maxlength="1" oninput="moveToNext(this,'profile_otp_6')">
                      <input type="number" id="profile_otp_6" class="modal-otp-input" maxlength="1" oninput="moveToNext(this,'null')">
                    </div>
                    <button class="btn-modal-success" id="verifyProfileOtpBtn">
                      <i class="ri-check-double-line"></i> Verify OTP
                    </button>
                  </div>
                </div>
              </div>
            </div>
            <!-- Update Mobile Number (OTP-verified) -->
            <div class="modal fade" id="updateMobileModal" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered" style="max-width:440px;">
                <div class="modal-content">
                  <div class="abha-modal-header">
                    <div class="abha-modal-icon"><i class="ri-smartphone-line"></i></div>
                    <div>
                      <p class="abha-modal-title">Update Mobile Number</p>
                      <p class="abha-modal-subtitle" id="umSubtitle">Verify the new mobile with an OTP</p>
                    </div>
                    <button class="abha-modal-close" data-dismiss="modal">&times;</button>
                  </div>
                  <div class="modal-body" style="padding:22px;">
                    <label class="modal-field-label">New Mobile Number</label>
                    <input type="text" id="um_mobile" class="modal-field-input" placeholder="Enter 10-digit mobile" maxlength="10"
                      oninput="enforceMobileField(this, '#um_mobile_hint')" style="margin-bottom:6px;">
                    <div id="um_mobile_hint" style="color:#e53e3e;font-size:12px;margin-bottom:8px;display:none;"></div>
                    <button id="umSendOtpBtn" class="btn-modal-primary">
                      <i class="ri-send-plane-line"></i> Send OTP
                    </button>

                    <div id="umOtpSection" style="display:none; margin-top:16px;">
                      <hr class="modal-divider">
                      <label class="modal-field-label">Enter OTP</label>
                      <div class="modal-otp-box">
                        <input type="number" id="um_otp_1" class="modal-otp-input" maxlength="1" oninput="moveToNext(this,'um_otp_2')">
                        <input type="number" id="um_otp_2" class="modal-otp-input" maxlength="1" oninput="moveToNext(this,'um_otp_3')">
                        <input type="number" id="um_otp_3" class="modal-otp-input" maxlength="1" oninput="moveToNext(this,'um_otp_4')">
                        <input type="number" id="um_otp_4" class="modal-otp-input" maxlength="1" oninput="moveToNext(this,'um_otp_5')">
                        <input type="number" id="um_otp_5" class="modal-otp-input" maxlength="1" oninput="moveToNext(this,'um_otp_6')">
                        <input type="number" id="um_otp_6" class="modal-otp-input" maxlength="1" oninput="moveToNext(this,'null')">
                      </div>
                      <button id="umVerifyBtn" class="btn-modal-success" style="margin-top:14px;">
                        <i class="ri-check-line"></i> Verify &amp; Update
                      </button>
                    </div>
                    <input type="hidden" id="umTxnId" value="">
                    <div id="umMsg" style="font-size:12px; margin-top:10px;"></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal fade" id="enterotp" tabindex="-1" aria-labelledby="registerNew" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width:680px;">
                <div class="modal-content">
                  <div class="abha-modal-header">
                    <div class="abha-modal-icon"><i class="ri-user-add-line"></i></div>
                    <div>
                      <p class="abha-modal-title">Create ABHA</p>
                      <p class="abha-modal-subtitle">Ayushman Bharat Health Account Registration</p>
                    </div>
                    <button class="abha-modal-close" data-dismiss="modal">&times;</button>
                  </div>
                  <div class="modal-body" style="padding:22px;">

                    <!-- Step indicator -->
                    <div class="modal-steps">
                      <div class="mstep active" id="mstep1">
                        <div class="mstep-dot">1</div>
                        <div class="mstep-lbl">Aadhaar</div>
                      </div>
                      <div class="mstep" id="mstep2">
                        <div class="mstep-dot">2</div>
                        <div class="mstep-lbl">Aadhaar OTP</div>
                      </div>
                      <div class="mstep" id="mstep3">
                        <div class="mstep-dot">3</div>
                        <div class="mstep-lbl">Mobile</div>
                      </div>
                      <div class="mstep" id="mstep4">
                        <div class="mstep-dot">4</div>
                        <div class="mstep-lbl">Mobile OTP</div>
                      </div>
                    </div>

                    <!-- Step 1: Aadhaar + consent -->
                    <div id="abha_step1">
                      <!-- Aadhaar input (masked; eye toggle to reveal) -->
                      <label class="modal-field-label">Aadhaar Number</label>
                      <div style="position:relative;">
                        <input class="modal-field-input" type="password" id="aadhaar" placeholder="Enter 12-digit Aadhaar" maxlength="12"
                          inputmode="numeric" autocomplete="off"
                          oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,12)" style="padding-right:44px;">
                        <button type="button" id="toggleAadhaar" tabindex="-1" title="Show / hide"
                          style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#888;font-size:18px;padding:4px;line-height:1;">
                          <i class="ri-eye-off-line"></i>
                        </button>
                      </div>
                      <div id="aadhaar_hint" style="color:#e53e3e;font-size:12px;margin-top:5px;display:none;"></div>

                      <!-- Mobile input — collected up-front, used at ABHA creation -->
                      <label class="modal-field-label" style="margin-top:10px;">Mobile Number</label>
                      <input class="modal-field-input" type="text" id="reg_mobile" placeholder="Enter 10-digit mobile" maxlength="10"
                        oninput="enforceMobileField(this, '#reg_mobile_hint')">
                      <div id="reg_mobile_hint" style="color:#e53e3e;font-size:12px;margin-top:5px;display:none;"></div>

                      <!-- Consent -->
                      <div class="consent-lang-row">
                        <label class="modal-field-label" style="margin:0;">Consent Language</label>
                        <select id="consent_lang" class="consent-lang-select">
                          <option value="en">English</option>
                          <option value="hi">हिन्दी (Hindi)</option>
                          <option value="kn">ಕನ್ನಡ (Kannada)</option>
                        </select>
                      </div>
                      <div class="consent-box" id="consent_text">
                        <div style="font-weight:700;color:#1a1a2e;margin-bottom:8px;" id="consent_declare_head">I hereby declare that:</div>

                        <label class="consent-check" style="font-weight:700;border-bottom:1px solid #e2e6ee;padding-bottom:8px;margin-bottom:6px;">
                          <input type="checkbox" id="consentSelectAll">
                          <span>Select All</span>
                        </label>

                        <label class="consent-check">
                          <input type="checkbox" class="consentChk" data-required="1">
                          <span>I am voluntarily sharing my Aadhaar Number / Virtual ID issued by the Unique Identification Authority of India ("UIDAI"), and my demographic information for the purpose of creating an Ayushman Bharat Health Account number ("ABHA number") and Ayushman Bharat Health Account address ("ABHA Address"). I authorize NHA to use my Aadhaar number / Virtual ID for performing Aadhaar based authentication with UIDAI as per the provisions of the Aadhaar Act, 2016. I understand that UIDAI will share my e-KYC details or response of "Yes" with NHA upon successful authentication.</span>
                        </label>

                        <label class="consent-check">
                          <input type="checkbox" class="consentChk">
                          <span>I intend to create Ayushman Bharat Health Account Number ("ABHA number") and Ayushman Bharat Health Account address ("ABHA Address") using document other than Aadhaar. <em style="color:#6b7280;font-style:normal;">(Optional)</em></span>
                        </label>

                        <label class="consent-check">
                          <input type="checkbox" class="consentChk" data-required="1">
                          <span>I consent to usage of my ABHA address and ABHA number for linking of my legacy (past) government health records and those which will be generated during this encounter.</span>
                        </label>

                        <label class="consent-check">
                          <input type="checkbox" class="consentChk" data-required="1">
                          <span>I authorize the sharing of all my health records with healthcare provider(s) for the purpose of providing healthcare services to me during this encounter.</span>
                        </label>

                        <label class="consent-check">
                          <input type="checkbox" class="consentChk" data-required="1">
                          <span>I consent to the anonymization and subsequent use of my government health records for public health purposes.</span>
                        </label>

                        <label class="consent-check">
                          <input type="checkbox" class="consentChk" data-required="1">
                          <span>I, <b><?php echo htmlspecialchars(defined('FACILITY_NAME') ? FACILITY_NAME : 'Healthcare Provider'); ?></b>, confirm that I have duly informed and explained the beneficiary of the contents of consent for aforementioned purposes.</span>
                        </label>

                        <label class="consent-check">
                          <input type="checkbox" class="consentChk" data-required="1">
                          <span>I, <input type="text" id="consent_beneficiary_name" placeholder="Enter name"
                              maxlength="60" autocomplete="off" title="Letters only"
                              oninput="this.value=this.value.replace(/[^A-Za-z .'\-]/g,'')"
                              style="border:1px solid #cbd3e0;border-radius:6px;outline:none;font-size:12px;padding:4px 8px;min-width:150px;background:#fff;">, have been explained about the consent as stated above and hereby provide my consent for the aforementioned purposes.</span>
                        </label>
                      </div>

                      <!-- Hidden: older code paths reference #abha_consent for the
                         "consent accepted" state; kept in sync from the checkboxes. -->
                      <input type="checkbox" id="abha_consent" style="display:none;">

                      <!-- Authentication method: Aadhaar OTP, or biometric through a UIDAI
                           RD-service device (Mantra MFS100 fingerprint / MIS100V2 iris)
                           connected to this PC. -->
                      <label class="modal-field-label" style="margin-top:14px;">Authenticate With</label>
                      <div class="mode-opts">
                        <div class="mode-opt">
                          <input type="radio" name="abha_auth_mode" id="auth_mode_otp" value="otp" checked>
                          <label for="auth_mode_otp">Aadhaar OTP</label>
                        </div>
                        <div class="mode-opt">
                          <input type="radio" name="abha_auth_mode" id="auth_mode_finger" value="finger">
                          <label for="auth_mode_finger">Fingerprint</label>
                        </div>
                        <div class="mode-opt">
                          <input type="radio" name="abha_auth_mode" id="auth_mode_iris" value="iris">
                          <label for="auth_mode_iris">Iris</label>
                        </div>
                      </div>
                      <div id="bioDeviceStatus" class="bio-status" style="display:none;">
                        <span id="bioDeviceStatusText"></span>
                        <a id="bioDeviceRecheck" class="resend-link">Re-check</a>
                      </div>

                      <button class="btn-modal-primary" id="create_abha" style="margin-top:14px;" disabled>
                        <i class="ri-send-plane-line"></i> Send Aadhaar OTP
                      </button>
                    </div><!-- end #abha_step1 -->

                    <!-- Step 2: Aadhaar OTP -->
                    <div id="abha_verify" style="display:none; margin-top:4px;">
                      <hr class="modal-divider">
                      <div class="modal-otp-banner">
                        <i class="ri-message-2-line"></i>
                        <span id="aadhaarOtpMsg">OTP sent to your Aadhaar-linked mobile number</span>
                      </div>
                      <label class="modal-field-label">Aadhaar OTP</label>
                      <div class="modal-otp-box">
                        <input type="number" name="otp1" id="otp1" class="modal-otp-input" maxlength="1" oninput="moveToNext(this,'otp2')">
                        <input type="number" name="otp2" id="otp2" class="modal-otp-input" maxlength="1" oninput="moveToNext(this,'otp3')">
                        <input type="number" name="otp3" id="otp3" class="modal-otp-input" maxlength="1" oninput="moveToNext(this,'otp4')">
                        <input type="number" name="otp4" id="otp4" class="modal-otp-input" maxlength="1" oninput="moveToNext(this,'otp5')">
                        <input type="number" name="otp5" id="otp5" class="modal-otp-input" maxlength="1" oninput="moveToNext(this,'otp6')">
                        <input type="number" name="otp6" id="otp6" class="modal-otp-input" maxlength="1" oninput="moveToNext(this,'null')">
                      </div>
                      <div class="resend-row">Didn't receive it? <a id="resend_aadhaar_otp" class="resend-link disabled">Resend OTP</a><span id="resend_aadhaar_timer" class="resend-timer"></span></div>
                      <input type="hidden" id="txnId" value="">
                      <input type="hidden" id="mobileTxnId" value="">
                      <div style="display:flex; gap:10px; margin-top:4px;">
                        <button type="button" class="btn-modal-outline abha-back-btn" data-back-to="1">
                          <i class="ri-arrow-left-line"></i> Back
                        </button>
                        <button id="verify_aadhaar_otp" class="btn-modal-success" style="flex:1;">
                          <i class="ri-check-line"></i> Verify &amp; Create ABHA
                        </button>
                      </div>
                    </div><!-- end #abha_verify (step 2) -->

                    <!-- Step 3 / 4: Mobile + Mobile OTP -->
                    <div id="mobile_update_block" style="display:none; margin-top:4px;">
                      <hr class="modal-divider">
                      <div class="modal-otp-banner">
                        <i class="ri-information-line"></i>
                        <span id="mobile_diff_note">Your ABHA registered mobile differs from the mobile you entered. Verify the new mobile to update it.</span>
                      </div>
                      <!-- Mobile number was entered on Screen 1; kept here as hidden. -->
                      <input type="hidden" id="mobile" value="">
                      <button id="send_mobile_otp" class="btn-modal-primary">
                        <i class="ri-send-plane-line"></i> Send OTP to <span id="mobile_update_target"></span>
                      </button>
                      <div style="margin-top:14px;">
                        <label class="modal-field-label">Mobile OTP</label>
                        <div class="modal-otp-box">
                          <input type="number" id="mobile_otp1" class="modal-otp-input" maxlength="1" oninput="moveToNext(this,'mobile_otp2')">
                          <input type="number" id="mobile_otp2" class="modal-otp-input" maxlength="1" oninput="moveToNext(this,'mobile_otp3')">
                          <input type="number" id="mobile_otp3" class="modal-otp-input" maxlength="1" oninput="moveToNext(this,'mobile_otp4')">
                          <input type="number" id="mobile_otp4" class="modal-otp-input" maxlength="1" oninput="moveToNext(this,'mobile_otp5')">
                          <input type="number" id="mobile_otp5" class="modal-otp-input" maxlength="1" oninput="moveToNext(this,'mobile_otp6')">
                          <input type="number" id="mobile_otp6" class="modal-otp-input" maxlength="1" oninput="moveToNext(this,'null')">
                        </div>
                        <div class="resend-row">Didn't receive it? <a id="resend_mobile_otp" class="resend-link disabled">Resend OTP</a><span id="resend_mobile_timer" class="resend-timer"></span></div>
                      </div>
                      <div style="display:flex; gap:10px; margin-top:10px;">
                        <button type="button" class="btn-modal-outline abha-back-btn" data-back-to="2">
                          <i class="ri-arrow-left-line"></i> Back
                        </button>
                        <button id="verify_mobile_otp" class="btn-modal-success" style="flex:1;">
                          <i class="ri-check-line"></i> Verify &amp; Update Mobile
                        </button>
                      </div>
                    </div><!-- end #mobile_update_block -->

                  </div>
                </div>
              </div>
            </div>

            <!-- TC-008: Choose / customise ABHA Address -->
            <div class="modal fade" id="abhaAddressModal" tabindex="-1" role="dialog" aria-labelledby="abhaAddressTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
              <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="abhaAddressTitle"><i class="ri-at-line"></i> Choose your ABHA Address</h5>
                  </div>
                  <div class="modal-body">
                    <p class="aaddr-hint">Pick a suggested ABHA address, or create your own. This becomes your preferred ABHA address (like an email — <code>username@abdm</code>).</p>

                    <div class="aaddr-block">
                      <label class="aaddr-label">Suggested addresses</label>
                      <div id="abhaSuggestList" class="aaddr-suggest-list"><em>Loading suggestions…</em></div>
                    </div>

                    <div class="aaddr-block">
                      <label class="aaddr-label" for="customAbhaAddress">Or create a custom ABHA address</label>
                      <div class="aaddr-input-group" style="display:flex;align-items:stretch;border:1px solid #d5dde5;border-radius:8px;overflow:hidden;">
                        <input type="text" id="customAbhaAddress" class="aaddr-input" style="border:none;border-radius:0;flex:1;min-width:0;" maxlength="18" placeholder="e.g. john.doe123" autocomplete="off" spellcheck="false">
                        <span id="abhaAddrDomain" style="display:flex;align-items:center;padding:0 12px;background:#eef2f7;color:#33475b;font-weight:600;font-size:14px;white-space:nowrap;">@<?php echo htmlspecialchars(defined('ABDM_CM_ID') ? ABDM_CM_ID : 'sbx'); ?></span>
                      </div>
                      <ul id="abhaAddrRules" class="aaddr-rules">
                        <li data-rule="len">8–18 characters</li>
                        <li data-rule="charset">Only letters and numbers, with at most one “.” and one “_”</li>
                        <li data-rule="edges">Does not start or end with “.” or “_”</li>
                      </ul>
                    </div>

                    <div id="abhaAddrMsg" class="aaddr-msg"></div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn-modal-secondary" id="keepDefaultAddr">Keep default</button>
                    <button type="button" class="btn-modal-primary" id="setAbhaAddrBtn" disabled><i class="ri-check-line"></i> Use this address</button>
                  </div>
                </div>
              </div>
            </div>

            <!-- TC-011/023: Link ABHA to an existing HIMS patient -->
            <div class="modal fade" id="linkPatientModal" tabindex="-1" role="dialog" aria-labelledby="linkPatientTitle" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="linkPatientTitle"><i class="ri-links-line"></i> Link ABHA to a Patient</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  </div>
                  <div class="modal-body">
                    <p class="aaddr-hint">Search an existing HIMS patient by name, mobile, OPD No or UHID, then link this ABHA to them.</p>
                    <div style="display:flex; gap:8px; margin-bottom:12px;">
                      <input type="text" id="linkPatientSearch" class="aaddr-input" placeholder="Search patient…" autocomplete="off">
                      <button type="button" class="btn-modal-primary" id="linkPatientSearchBtn" style="white-space:nowrap;"><i class="ri-search-line"></i> Search</button>
                    </div>
                    <div id="linkPatientResults" class="link-results"></div>
                    <div id="linkPatientMsg" class="aaddr-msg"></div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Register from ABHA: compare the ABDM profile against an existing HIMS
                 patient, then choose "Go with ABDM" (register from ABHA) or
                 "Go with HIMS" (open the existing patient). New patients register directly. -->
            <div class="modal fade" id="compareModal" tabindex="-1" role="dialog" aria-labelledby="compareTitle" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="compareTitle"><i class="ri-git-compare-line"></i> Register from ABHA</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  </div>
                  <div class="modal-body">
                    <p class="aaddr-hint">This ABHA is already registered in HIMS. Compare the ABHA profile against the existing record, then add a follow-up using either source of details. (Search to pick a different patient if needed.)</p>
                    <div style="display:flex; gap:8px; margin-bottom:12px;">
                      <input type="text" id="compareSearch" class="aaddr-input" placeholder="Search patient by name, mobile, OPD No or UHID…" autocomplete="off">
                      <button type="button" class="btn-modal-primary" id="compareSearchBtn" style="white-space:nowrap;"><i class="ri-search-line"></i> Search</button>
                    </div>
                    <div id="compareResults" class="link-results"></div>

                    <div id="compareView" style="display:none; margin-top:14px;">
                      <div class="table-responsive">
                        <table class="table table-bordered table-sm cmp-table" style="margin-bottom:10px;">
                          <thead>
                            <tr>
                              <th style="width:22%;">Field</th>
                              <th style="width:39%;"><i class="ri-shield-check-line"></i> ABDM (ABHA)</th>
                              <th style="width:39%;"><i class="ri-hospital-line"></i> HIMS record</th>
                            </tr>
                          </thead>
                          <tbody id="compareBody"></tbody>
                        </table>
                      </div>
                      <div style="display:flex; gap:10px; justify-content:flex-end; flex-wrap:wrap;">
                        <button type="button" class="btn-modal-secondary" id="goWithHimsBtn"><i class="ri-hospital-line"></i> Go with HIMS</button>
                        <button type="button" class="btn-modal-primary" id="goWithAbdmBtn"><i class="ri-shield-check-line"></i> Go with ABDM</button>
                      </div>
                    </div>
                    <div id="compareMsg" class="aaddr-msg"></div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn-modal-secondary" data-dismiss="modal">Cancel</button>
                  </div>
                </div>
              </div>
            </div>

            <!-- HERO BANNER -->
            <div class="abha-hero">
              <div>
                <div class="abha-hero-badge"><i class="ri-shield-check-fill"></i> ABDM Verified</div>
                <h2 style="margin:6px 0 3px;">ABHA Health ID</h2>
                <p>Search, verify or create your Ayushman Bharat Health Account</p>
              </div>
              <button class="btn-create-abha" data-toggle="modal" data-target="#enterotp">
                <i class="ri-add-circle-line"></i> Create New ABHA
              </button>
            </div>

            <!-- WORKSPACE -->
            <div class="abha-workspace">

              <!-- LEFT: Search Panel -->
              <div class="abha-search-panel" id="abhaSearchPanel">
                <div class="panel-head">
                  <div class="panel-title">Search Patient ABHA</div>
                  <!-- Hidden select keeps existing JS working -->
                  <select id="searchType" style="display:none;">
                    <option value="aadhaar">Aadhaar</option>
                    <option value="mobile">Mobile</option>
                    <option value="abhaNumber">ABHA Number</option>
                    <option value="abhaAddress">ABHA Address</option>
                  </select>
                  <!-- Visual tabs -->
                  <div class="search-tabs">
                    <button class="stab active" data-val="aadhaar" data-placeholder="Enter 12-digit Aadhaar" data-icon="ri-fingerprint-line" data-max="12">
                      <i class="ri-fingerprint-line"></i> Aadhaar
                    </button>
                    <button class="stab" data-val="mobile" data-placeholder="Enter 10-digit Mobile" data-icon="ri-smartphone-line" data-max="10">
                      <i class="ri-smartphone-line"></i> Mobile
                    </button>
                    <button class="stab" data-val="abhaNumber" data-placeholder="Enter 14-digit ABHA Number" data-icon="ri-info-card-line" data-max="14">
                      <i class="ri-info-card-line"></i> ABHA No.
                    </button>
                    <button class="stab" data-val="abhaAddress" data-placeholder="Enter ABHA Address" data-icon="ri-at-line" data-max="50">
                      <i class="ri-at-line"></i> ABHA Address
                    </button>
                  </div>
                </div>

                <div class="panel-body">
                  <!-- Dynamic input -->
                  <div class="sinput-wrap">
                    <i class="ri-fingerprint-line sinput-ico" id="searchIcon"></i>
                    <input type="password" id="searchInput" class="sinput" placeholder="Enter 12-digit Aadhaar" maxlength="12" style="padding-right:44px;">
                    <button type="button" id="toggleSearchInput" tabindex="-1" title="Show / hide"
                      style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#aaa;font-size:18px;padding:4px;line-height:1;">
                      <i class="ri-eye-off-line"></i>
                    </button>
                    <!-- Fixed @sbx/@abdm suffix for the ABHA Address tab (username only is typed). -->
                    <span id="searchDomain" style="display:none;position:absolute;right:12px;top:50%;transform:translateY(-50%);color:#33475b;font-weight:700;font-size:14px;white-space:nowrap;pointer-events:none;">@<?php echo htmlspecialchars(defined('ABDM_CM_ID') ? ABDM_CM_ID : 'sbx'); ?></span>
                  </div>
                  <div id="searchInputHint" style="color:#e53e3e;font-size:12px;margin:-6px 0 8px;display:none;"></div>

                  <!-- ABHA Number mode (mobile/aadhaar) -->
                  <div class="mode-wrap" id="abhaNumberModeWrap" style="display:none;">
                    <span class="mode-lbl">Verification Method</span>
                    <div class="mode-opts">
                      <div class="mode-opt">
                        <input type="radio" name="abhaMode" id="modeRadioMobile" value="mobile" checked>
                        <label for="modeRadioMobile"><i class="ri-smartphone-line"></i> Mobile OTP</label>
                      </div>
                      <div class="mode-opt">
                        <input type="radio" name="abhaMode" id="modeRadioAadhaar" value="aadhaar">
                        <label for="modeRadioAadhaar"><i class="ri-fingerprint-line"></i> Aadhaar OTP</label>
                      </div>
                    </div>
                    <!-- Hidden select for backward-compat JS -->
                    <select id="abhaNumberMode" style="display:none;">
                      <option value="mobile">Mobile OTP</option>
                      <option value="aadhaar">Aadhaar OTP</option>
                    </select>
                  </div>

                  <button class="btn-search" id="searchAadhar">
                    <i class="ri-search-line"></i> Search Patient
                  </button>
                </div>

                <!-- OTP Section -->
                <div id="abha_verify1">
                  <div class="otp-section">
                    <div class="otp-prompt">
                      <i class="ri-message-3-line"></i>
                      <span id="searchOtpPromptTxt">OTP sent to your registered mobile number</span>
                    </div>
                    <div class="otp-grid">
                      <input type="number" id="otp_1" maxlength="1" class="otp-digit" oninput="moveToNext(this,'otp_2')">
                      <input type="number" id="otp_2" maxlength="1" class="otp-digit" oninput="moveToNext(this,'otp_3')">
                      <input type="number" id="otp_3" maxlength="1" class="otp-digit" oninput="moveToNext(this,'otp_4')">
                      <input type="number" id="otp_4" maxlength="1" class="otp-digit" oninput="moveToNext(this,'otp_5')">
                      <input type="number" id="otp_5" maxlength="1" class="otp-digit" oninput="moveToNext(this,'otp_6')">
                      <input type="number" id="otp_6" maxlength="1" class="otp-digit" oninput="moveToNext(this,'null')">
                    </div>
                    <input type="hidden" id="txnId1" value="">
                    <button class="btn-verify" id="verify_otp1">
                      <i class="ri-shield-check-line"></i> Verify OTP
                    </button>
                    <div class="resend-row">Didn't receive it? <a id="resend_search_otp" class="resend-link disabled">Resend OTP</a><span id="resend_search_timer" class="resend-timer"></span></div>
                  </div>
                </div>
              </div>

              <!-- RIGHT: ABHA ID Card — Official NHA format -->
              <div id="aabha_card_section">
                <button type="button" id="backToSearchBtn" class="btn-back-search">
                  <i class="ri-arrow-left-line"></i> Back to Search
                </button>
                <div class="abha-id-card">

                  <!-- Blue NHA header band -->
                  <div class="card-topband">
                    <div class="nha-logo-block">
                      <div class="nha-emblem"><i class="ri-government-line"></i></div>
                      <div class="nha-text-block">
                        <div class="nha-text-eng">national<br>health<br>authority</div>
                      </div>
                    </div>
                    <div class="card-centre-text">
                      <span class="cct-eng">Ayushman Bharat Health Account (ABHA)</span>
                      <span class="cct-hin">आयुष्मान भारत स्वास्थ्य खाता (आभा)</span>
                    </div>
                    <div class="abdm-logo-block">
                      <i class="ri-heart-pulse-line"></i>
                    </div>
                  </div>

                  <!-- Photo + Name + ABHA Number + Address + QR -->
                  <div class="card-body-row">
                    <div class="card-photo-box">
                      <img id="image" src="assets/no-photo.png" alt="Patient Photo">
                    </div>
                    <div class="card-info-block">
                      <span class="ci-label" id="lbl_name">Name</span>
                      <div class="ci-name-eng" id="fname">---</div>
                      <div class="ci-name-hin" id="fname_hin"></div>

                      <span class="ci-field-label" id="lbl_abhaNumber">Abha Number</span>
                      <span class="ci-abha-number" id="abha_number">---</span>

                      <span class="ci-field-label" id="lbl_abhaAddress">Abha Address</span>
                      <span class="ci-abha-address" id="abha_address">---</span>
                    </div>
                    <div class="card-qr-block">
                      <div id="qr-code-wrap"></div>
                    </div>
                  </div>

                  <!-- Gender / DOB / Mobile strip -->
                  <div class="card-strip">
                    <div class="strip-col">
                      <span class="strip-lbl" id="lbl_gender">Gender</span>
                      <span class="strip-val" id="sex">---</span>
                    </div>
                    <div class="strip-col">
                      <span class="strip-lbl" id="lbl_dob">Date Of Birth</span>
                      <span class="strip-val" id="dob">---</span>
                    </div>
                    <div class="strip-col">
                      <span class="strip-lbl" id="lbl_mobile">Mobile</span>
                      <span class="strip-val" id="user_mobile">---</span>
                    </div>
                  </div>

                  <!-- Address row -->
                  <div style="padding:10px 20px 12px; border-bottom:1px solid #edf0f7; font-size:13px; color:#444;">
                    <span style="font-size:10px;color:#888;font-weight:600;letter-spacing:.4px;">Address</span><br>
                    <span id="address" style="font-weight:600;color:#1a1a2e;">---</span>
                    <span id="aadhar" style="display:none;"></span>
                  </div>

                  <!-- State / District / Pin Code strip -->
                  <div class="card-strip">
                    <div class="strip-col">
                      <span class="strip-lbl" id="lbl_state">State</span>
                      <span class="strip-val" id="state_name">---</span>
                    </div>
                    <div class="strip-col">
                      <span class="strip-lbl" id="lbl_district">District</span>
                      <span class="strip-val" id="district_name">---</span>
                    </div>
                    <div class="strip-col">
                      <span class="strip-lbl" id="lbl_pincode">Pin Code</span>
                      <span class="strip-val" id="pin_code">---</span>
                    </div>
                  </div>

                  <!-- Link status badge (TC-025) -->
                  <div id="abhaLinkBadge" class="abha-link-badge" style="display:none;"></div>

                  <!-- Download + Link / Register (TC-011/017/023) -->
                  <div class="card-foot" style="flex-wrap:wrap; gap:8px;">
                    <button id="download_abha">
                      <i class="ri-download-2-line"></i> Download ABHA Card
                    </button>
                    <button id="updateMobileBtn" type="button" class="btn-abha-action" style="display:none;">
                      <i class="ri-smartphone-line"></i> Update Mobile Number
                    </button>
                    <button id="linkPatientBtn" type="button" class="btn-abha-action">
                      <i class="ri-links-line"></i> Link to Patient
                    </button>
                    <button id="registerPatientBtn" type="button" class="btn-abha-action">
                      <i class="ri-user-add-line"></i> Register in HIMS
                    </button>
                  </div>

                </div><!-- end .abha-id-card -->

                <!-- Mobile comparison -->
                <div id="mobileCompareCard">
                  <div class="mc-card">
                    <div class="mc-head">
                      <i class="ri-phone-find-line"></i> Mobile Number Verification
                    </div>
                    <div class="mc-body">
                      <div class="mc-row">
                        <span class="mc-lbl">Searched / Entered Mobile</span>
                        <span class="mc-val" id="entered_mobile">-</span>
                      </div>
                      <div class="mc-row">
                        <span class="mc-lbl">ABHA Profile Mobile</span>
                        <span class="mc-val" id="abha_mobile_profile">-</span>
                      </div>
                      <div id="mobile_compare_msg" style="padding:4px 0;"></div>

                      <!-- OTP-verified mobile update (same flow as ABHA creation) -->
                      <div id="mobileUpdateBlock" style="display:none; border-top:1px solid #f0f0f0; padding-top:12px; margin-top:6px;">
                        <div style="font-size:10px;font-weight:700;color:#888;text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px;">
                          Update Communication Mobile
                        </div>
                        <div style="display:flex;gap:8px;align-items:center;">
                          <input type="number" id="update_mobile_input" placeholder="Enter 10-digit mobile"
                            style="flex:1;padding:9px 12px;border:2px solid #e8e8e8;border-radius:10px;font-size:14px;outline:none;font-family:inherit;-moz-appearance:textfield;"
                            oninput="this.value=this.value.slice(0,10)">
                          <button id="cardSendMobileOtpBtn"
                            style="padding:9px 16px;background:#1a56db;color:#fff;border:none;border-radius:10px;font-weight:700;font-size:13px;cursor:pointer;white-space:nowrap;font-family:inherit;">
                            <i class="ri-send-plane-line"></i> Send OTP
                          </button>
                        </div>
                        <div id="cardMobileOtpRow" style="display:none;gap:8px;align-items:center;margin-top:10px;">
                          <input type="number" id="card_mobile_otp" placeholder="Enter 6-digit OTP"
                            style="flex:1;padding:9px 12px;border:2px solid #e8e8e8;border-radius:10px;font-size:14px;outline:none;font-family:inherit;-moz-appearance:textfield;"
                            oninput="this.value=this.value.slice(0,6)">
                          <button id="cardVerifyMobileOtpBtn"
                            style="padding:9px 16px;background:#0a7a4d;color:#fff;border:none;border-radius:10px;font-weight:700;font-size:13px;cursor:pointer;white-space:nowrap;font-family:inherit;">
                            <i class="ri-check-line"></i> Verify &amp; Update
                          </button>
                        </div>
                        <input type="hidden" id="cardMobileTxnId" value="">
                        <div id="cardMobileMsg" style="font-size:12px;margin-top:6px;"></div>
                      </div>
                    </div>
                  </div>
                </div>

              </div><!-- end right panel -->

            </div><!-- end workspace -->

          </div>
        </div>



      </div>


    </div>



    <!--**********************************
            Content body end
        ***********************************-->


    <!--**********************************
            Footer start
        ***********************************-->
    <?php include('footer.php') ?>
    <!--**********************************
            Footer end
        ***********************************-->

    <!--**********************************
           Support ticket button start
        ***********************************-->

    <!--**********************************
           Support ticket button end
        ***********************************-->

  </div>

  </div>
  <!--**********************************
        Main wrapper end
    ***********************************-->

  <!--**********************************
        Scripts
    ***********************************-->
  <!-- Required vendors -->
  <script src="./vendor/global/global.min.js"></script>
  <script src="./js/quixnav-init.js"></script>
  <script src="./js/custom.min.js"></script>
  <script>
    document.getElementById("searchType").addEventListener("change", function() {
      const input = document.getElementById("searchInput");
      const modeWrap = document.getElementById("abhaNumberModeWrap");

      if (this.value === "aadhaar") {
        input.placeholder = "Enter Aadhaar Number";
        input.maxLength = 12;
        input.type = "number";
        modeWrap.style.display = "none";
      } else if (this.value === "mobile") {
        input.placeholder = "Enter Mobile Number";
        input.maxLength = 10;
        input.type = "number";
        modeWrap.style.display = "none";
      } else if (this.value === "abhaNumber") {
        input.placeholder = "Enter ABHA Number";
        input.removeAttribute('maxlength'); // change if your ABHA length is different
        input.type = "text";
        modeWrap.style.display = "block";
      } else if (this.value === "abhaAddress") {
        input.placeholder = "Enter ABHA Address (name@abdm)";
        input.removeAttribute('maxlength');
        input.type = "text";
        modeWrap.style.display = "none";
      }
    });


    function moveToNext(current, nextId) {
      // allow only digits
      current.value = current.value.replace(/[^0-9]/g, "");

      // force only 1 character
      if (current.value.length > 1) {
        current.value = current.value.slice(0, 1);
      }

      // move to next input
      if (current.value.length === 1 && nextId) {
        document.getElementById(nextId).focus();
      }
    }






    // Non-blocking notification (replaces alert). Auto-detects tone from the text
    // when `type` is omitted; auto-dismisses after a few seconds.
    // `action` (optional) = {label, onClick}: renders a button inside the toast and
    // keeps it on screen longer, so a choice that used to need a blocking
    // window.confirm can be offered without stealing focus from the page.
    function toast(message, type, action) {
      message = (message == null ? '' : String(message));
      if (!type) type = /(✓|success|updated|verified|granted|linked|sent successfully)/i.test(message) ? 'success' : 'error';
      var $c = $('#toastContainer');
      if (!$c.length) $c = $('<div id="toastContainer"></div>').appendTo('body');
      var $t = $('<div class="app-toast ' + type + '"></div>');
      $('<div></div>').text(message).appendTo($t);
      var hide = function() {
        $t.removeClass('show');
        setTimeout(function() { $t.remove(); }, 300);
      };
      var life = 4500;
      if (action && action.label) {
        life = 12000; // long enough to read the message and decide
        $('<button type="button" class="app-toast-action"></button>')
          .text(action.label)
          .on('click', function(e) {
            e.stopPropagation(); // the toast's own click-to-dismiss must not swallow it
            hide();
            if (typeof action.onClick === 'function') action.onClick();
          })
          .appendTo($t);
      }
      $c.append($t);
      window.requestAnimationFrame(function() { $t.addClass('show'); });
      $t.on('click', hide);
      setTimeout(hide, life);
    }
    window.toast = toast;

    function showLoader() {
      $('#loader-wrapper').css('display', 'flex').hide().fadeIn(200);
    }

    function hideLoader() {
      $('#loader-wrapper').fadeOut(300);
    }

    // Clear the ABHA card + link badge whenever the search TYPE or the entered
    // patient details change, so a stale profile from a previous search (and its
    // Link / Register actions, which act on the shown card) can't linger.
    function resetAbhaCard() {
      window.ABHA_CARD_SOURCE = 'enrollment'; // default; abha-address flow sets 'phr'
      window.ABHA_KYC_STATUS = ''; // cleared per patient; set when a profile loads
      window.MOBILE_LOGIN = null; // drop any in-progress mobile-login selection
      $('#aabha_card_section').hide();
      $('#abhaSearchPanel').show(); // restore the search section when the card resets
      $('.abha-workspace').removeClass('full-card');
      $('#abhaLinkBadge').removeClass('linked unlinked').hide();
      $('#mobileUpdateBlock').hide();
      $('#updateMobileBtn').hide(); // shown only in the mobile-verification flow
      window.__linkedOpdNo = '';
      ['fname', 'abha_number', 'abha_address', 'sex', 'dob', 'user_mobile', 'address', 'state_name', 'district_name', 'pin_code'].forEach(function(id) {
        var el = document.getElementById(id);
        if (el) el.textContent = '---';
      });
      var fh = document.getElementById('fname_hin');
      if (fh) fh.textContent = '';
      var img = document.getElementById('image');
      if (img) img.src = 'assets/no-photo.png';
      $('#qr-code-wrap').empty();
    }
    window.resetAbhaCard = resetAbhaCard;

    // ── Tab UI wiring ──────────────────────────────────────────────
    // Environment ABHA-address domain (@sbx sandbox / @abdm prod), from ABDM_CM_ID.
    var ABHA_SEARCH_DOMAIN = <?php echo json_encode(defined('ABDM_CM_ID') ? ABDM_CM_ID : 'sbx'); ?>;

    $('.stab').on('click', function() {
      $('.stab').removeClass('active');
      $(this).addClass('active');
      const val = $(this).data('val');
      const ph = $(this).data('placeholder');
      const ico = $(this).data('icon');
      const max = $(this).data('max');
      $('#searchType').val(val);
      $('#searchInput').attr('placeholder', ph).attr('maxlength', max).val('');
      $('#searchInputHint').hide().text('');
      $('#searchIcon').attr('class', ico + ' sinput-ico');
      // Mask the value only for the Aadhaar tab (with an eye toggle to reveal).
      var mask = (val === 'aadhaar');
      $('#searchInput').attr('type', mask ? 'password' : 'text');
      $('#toggleSearchInput').toggle(mask).find('i').attr('class', 'ri-eye-off-line');
      // ABHA Address tab: type only the username; the @sbx/@abdm domain is fixed
      // at the input's end and auto-appended on search.
      var isAddr = (val === 'abhaAddress');
      $('#searchDomain').toggle(isAddr);
      if (isAddr) {
        $('#searchInput').attr('placeholder', 'Enter ABHA username')
          .css('padding-right', (($('#searchDomain').outerWidth() || 40) + 18) + 'px');
      } else {
        $('#searchInput').css('padding-right', '44px');
      }
      // Both ABHA Number and ABHA Address logins let the user choose Mobile OTP
      // or Aadhaar OTP as the verification method.
      $('#abhaNumberModeWrap').toggle(val === 'abhaNumber' || val === 'abhaAddress');
      $('#abha_verify1').hide();
      // Search TYPE changed → drop any previously fetched card.
      resetAbhaCard();
      // clear OTP digits
      for (let i = 1; i <= 6; i++) {
        const el = document.getElementById('otp_' + i);
        if (el) el.value = '';
      }
    });

    // Restrict input by search type: Aadhaar / Mobile / ABHA Number accept digits
    // only (capped at their maxlength); ABHA Address allows its address characters.
    // Clean a mobile field to digits, force a 6-9 start, and warn (via the hint
    // element) when the user tries to start with 0-5. Shared by all mobile inputs.
    function enforceMobileField(el, hintSel) {
      var raw = (el.value || '').replace(/\D/g, '');
      var startedLow = /^[0-5]/.test(raw);
      el.value = raw.replace(/^[0-5]+/, '').slice(0, 10);
      if (hintSel) {
        var $h = $(hintSel);
        if (startedLow) $h.text('Mobile number must start with 6, 7, 8, or 9.').show();
        else $h.hide().text('');
      }
    }
    window.enforceMobileField = enforceMobileField;

    $('#searchInput').on('input', function() {
      var type = $('#searchType').val();
      var max = parseInt($(this).attr('maxlength'), 10) || 50;
      var $hint = $('#searchInputHint');
      if (type === 'mobile') {
        enforceMobileField(this, '#searchInputHint');
      } else if (type === 'aadhaar' || type === 'abhaNumber') {
        $hint.hide().text('');
        var digits = (this.value || '').replace(/\D/g, '').slice(0, max);
        if (digits !== this.value) this.value = digits;
      } else if (type === 'abhaAddress') {
        $hint.hide().text('');
        // Username only — the @sbx/@abdm domain is a fixed suffix. Enforce live:
        // lowercase, allowed charset (letters/numbers/./_), hard 18-char cap.
        var av = (this.value || '').toLowerCase().replace(/[^a-z0-9._]/g, '').slice(0, 18);
        if (av !== this.value) this.value = av;
      } else {
        $hint.hide().text('');
      }
      resetAbhaCard(); // drop any previously fetched card for a different value
    });

    // Sync radio → hidden select for abhaNumberMode
    $('input[name="abhaMode"]').on('change', function() {
      $('#abhaNumberMode').val($(this).val());
    });
    // ────────────────────────────────────────────────────────────────

    // ABDM prefixes UIDAI failures with UIDAI's own code:
    //   "UIDAI Error code : 998 : Aadhaar number is incorrect. Resident shall…"
    // The operator can only act on the sentence, so drop the machine prefix.
    // Idempotent — safe to apply to an already-stripped message.
    function stripUidaiCode(msg) {
      var m = String(msg == null ? '' : msg).match(/UIDAI\s*Error\s*code\s*:?\s*\d+\s*:\s*(.+)$/i);
      return m ? m[1].trim() : String(msg == null ? '' : msg);
    }

    // Pull the deepest human message out of a gateway/ABDM error object (the ABDM
    // detail can be nested as error.error.message).
    function deepErr(obj) {
      return stripUidaiCode(deepErrRaw(obj));
    }

    function deepErrRaw(obj) {
      if (!obj) return '';
      var e = obj.error;
      if (e) {
        if (typeof e === 'string') return e;
        if (e.error && (e.error.message || e.error.code)) return e.error.message || e.error.code;
        if (e.message) return e.message;
        if (e.code) return e.code;
        // Field-level validation errors, e.g. { loginId: "LoginId is invalid" }.
        // Without this the caller falls back to the generic top-level message and
        // the real reason ("the Aadhaar is malformed") never reaches the user.
        var fields = Object.keys(e).filter(function(k) {
          return k !== 'timestamp' && typeof e[k] === 'string' && e[k] !== '';
        });
        if (fields.length) {
          return fields.map(function(k) {
            return e[k];
          }).join('; ');
        }
      }
      if (obj.details && obj.details[0]) return obj.details[0].message || obj.details[0];
      return obj.message || '';
    }
    // Map a raw ABDM/UIDAI OTP error to a friendly message. UIDAI OTP failures come
    // through as e.g. "UIDAI Error code : 400 : OTP validation failed" / ABDM-1204.
    // ── Aadhaar validation ────────────────────────────────────────────────────
    // Twelve digits is not enough to be a real Aadhaar: UIDAI numbers never begin
    // with 0 or 1 and carry a Verhoeff check digit. Catching a mistyped number
    // here saves a round trip that comes back as the opaque
    // "ABDM-1204 / UIDAI Error code : 998 : Aadhaar number is incorrect".
    var VERHOEFF_D = [
      [0, 1, 2, 3, 4, 5, 6, 7, 8, 9], [1, 2, 3, 4, 0, 6, 7, 8, 9, 5],
      [2, 3, 4, 0, 1, 7, 8, 9, 5, 6], [3, 4, 0, 1, 2, 8, 9, 5, 6, 7],
      [4, 0, 1, 2, 3, 9, 5, 6, 7, 8], [5, 9, 8, 7, 6, 0, 4, 3, 2, 1],
      [6, 5, 9, 8, 7, 1, 0, 4, 3, 2], [7, 6, 5, 9, 8, 2, 1, 0, 4, 3],
      [8, 7, 6, 5, 9, 3, 2, 1, 0, 4], [9, 8, 7, 6, 5, 4, 3, 2, 1, 0]
    ];
    var VERHOEFF_P = [
      [0, 1, 2, 3, 4, 5, 6, 7, 8, 9], [1, 5, 7, 6, 2, 8, 3, 0, 9, 4],
      [5, 8, 0, 3, 7, 9, 6, 1, 4, 2], [8, 9, 1, 6, 0, 4, 3, 5, 2, 7],
      [9, 4, 5, 3, 1, 2, 6, 8, 7, 0], [4, 2, 8, 6, 5, 7, 3, 9, 0, 1],
      [2, 7, 9, 3, 8, 0, 6, 4, 1, 5], [7, 0, 4, 6, 9, 1, 3, 2, 5, 8]
    ];

    function aadhaarChecksumOk(num) {
      var c = 0,
        d = String(num).split('').reverse();
      for (var i = 0; i < d.length; i++) {
        c = VERHOEFF_D[c][VERHOEFF_P[i % 8][parseInt(d[i], 10)]];
      }
      return c === 0;
    }

    // '' when the number is structurally valid, otherwise the reason to show.
    function aadhaarError(num) {
      num = String(num || '');
      if (!/^\d{12}$/.test(num)) return 'Aadhaar Number must be exactly 12 digits.';
      if (/^[01]/.test(num)) return 'Aadhaar Number cannot start with 0 or 1.';
      if (!aadhaarChecksumOk(num)) return 'This Aadhaar Number is not valid — please re-check the 12 digits.';
      return '';
    }

    function showAadhaarHint(msg) {
      if (msg) {
        $('#aadhaar_hint').text(msg).show();
      } else {
        $('#aadhaar_hint').hide().text('');
      }
    }

    // ── ABDM error text ───────────────────────────────────────────────────────
    // ABDM nests the real reason and prefixes UIDAI failures with their code:
    //   {message:'Failed to send OTP…', error:{error:{code:'ABDM-1204',
    //    message:'UIDAI Error code : 998 : Aadhaar number is incorrect…'}}}
    // The top-level message is only our gateway's wrapper and tells the operator
    // nothing, so dig out the innermost message and the code that came with it.
    function abdmErrorText(res) {
      var node = res && res.error,
        msg = '',
        code = '';
      for (var i = 0; i < 5 && node; i++) {
        if (typeof node === 'string') {
          msg = node;
          break;
        }
        if (node.code && !code) code = node.code;
        if (node.message) {
          msg = String(node.message);
          break;
        }
        node = node.error || node.details || null;
      }
      if (!msg) {
        // Field-map form, e.g. {loginId:'LoginId is invalid'}
        var flat = (res && res.error && typeof res.error === 'object') ? res.error : null;
        if (flat) {
          for (var k in flat) {
            if (typeof flat[k] === 'string' && flat[k]) {
              msg = flat[k];
              break;
            }
          }
        }
        if (!msg) msg = (res && res.message) || '';
      }
      var uidai = msg.match(/UIDAI\s*Error\s*code\s*:?\s*(\d+)\s*:\s*(.+)$/i);
      if (uidai) {
        code = 'UIDAI-' + uidai[1];
        msg = uidai[2].trim();
      }
      return {
        code: code,
        message: msg
      };
    }

    // ── ABDM session token ────────────────────────────────────────────────────
    // The gateway answers with a flat {accessToken,…} on success and with
    // {success:false, message, error} when ABDM refused or was unreachable. A
    // missing token is usually transient — a gateway restart, an ABDM 5xx, a
    // dropped connection — so retry before giving up, rather than dead-ending the
    // operator with "Access token missing" mid-flow.
    var ABDM_TOKEN_RETRIES = 2, // extra attempts after the first
      ABDM_TOKEN_BACKOFF = 800; // ms, doubled each retry

    function fetchAbdmToken(onToken, onFail, attempt) {
      attempt = attempt || 0;

      function giveUpOrRetry(payload) {
        if (attempt < ABDM_TOKEN_RETRIES) {
          setTimeout(function() {
            fetchAbdmToken(onToken, onFail, attempt + 1);
          }, ABDM_TOKEN_BACKOFF * Math.pow(2, attempt));
          return;
        }
        var info = abdmErrorText(payload || {});
        var msg = info.message ?
          ('Could not obtain an ABDM session token — ' + info.message +
            (info.code ? ' (' + info.code + ')' : '')) :
          ('Could not obtain an ABDM session token after ' + (ABDM_TOKEN_RETRIES + 1) +
            ' attempts. Please try again in a moment.');
        hideLoader();
        if (onFail) {
          onFail(msg);
        } else {
          toast(msg);
        }
      }

      $.post('abha_integration.php', {
          page: 'get_token'
        })
        .done(function(res) {
          var d = null;
          try {
            d = (typeof res === 'string') ? JSON.parse(res) : res;
          } catch (e) {
            d = null; // an HTML error page, not JSON — treat as a failed attempt
          }
          var token = d && (d.accessToken || (d.data && d.data.accessToken));
          if (token) {
            window.ABHA_ACCESS_TOKEN = token;
            onToken(token);
            return;
          }
          giveUpOrRetry(d);
        })
        .fail(function(xhr) {
          giveUpOrRetry(xhr && xhr.responseJSON);
        });
    }

    // Use the token already minted for this flow, or transparently mint a new one
    // instead of asking the operator to restart.
    function withAbdmToken(onToken, onFail) {
      if (window.ABHA_ACCESS_TOKEN) {
        onToken(window.ABHA_ACCESS_TOKEN);
        return;
      }
      fetchAbdmToken(onToken, onFail);
    }

    // True when ABDM/UIDAI rejected the Aadhaar itself (so the hint belongs on
    // the field, not just in a toast).
    function isAadhaarRejection(info) {
      var s = ((info.message || '') + ' ' + (info.code || '')).toLowerCase();
      return /invalid\s*loginid|loginid is invalid|invalid aadhaar|aadhaar number is incorrect|uidai-998|uidai-997/.test(s);
    }

    // ── verify-OTP failures ───────────────────────────────────────────────────
    // A failed verify is not always an OTP problem. ABDM-1114 ("No ABHA user
    // registered with this Aadhaar number.") means the OTP was accepted and there
    // simply is no ABHA — the wrapper "Failed to verify ABHA" prefixed with
    // "OTP Error:" sent the operator hunting for a typo that isn't there.
    function isNoAbhaFound(info) {
      var s = ((info.message || '') + ' ' + (info.code || '')).toLowerCase();
      return /no abha|not registered|does not exist|no .*record|not found|does not match|1114|1115/.test(s);
    }

    function showVerifyOtpFailure(res, searchType) {
      var info = abdmErrorText(res || {});
      var m = info.message || '';
      if (isNoAbhaFound(info)) {
        // Offer the way forward: create one. (Aadhaar is the only identifier we can
        // enrol with, so the other types just report it.)
        if (searchType === 'aadhaar') {
          offerCreateAbha(m || 'No ABHA user registered with this Aadhaar Number');
        } else if (searchType === 'mobile') {
          toast(m || 'No ABHA user registered with this mobile number.');
        } else {
          toast(m || 'No ABHA record found for the value you entered.');
        }
        return;
      }
      toast(friendlyOtpErr(m, 'OTP verification failed. Please try again.'));
    }

    // ── "OTP sent to …" prompt ────────────────────────────────────────────────
    // ABDM answers with "OTP sent to mobile number ending with ******0470" for BOTH
    // verification methods, so the operator cannot tell which number was used. When
    // the Aadhaar method was chosen the OTP goes to the AADHAAR-registered mobile —
    // say so, while keeping ABDM's masked digits.
    function otpSentMessage(raw, verifyMode) {
      var msg = String(raw == null ? '' : raw).trim();
      var viaAadhaar = (verifyMode === 'aadhaar');
      if (!msg) {
        return viaAadhaar ?
          'OTP sent to Aadhaar registered mobile number' :
          'OTP sent to your registered mobile number';
      }
      // Nothing to clarify for the mobile method, or if ABDM already said "Aadhaar".
      if (!viaAadhaar || /aadhaar/i.test(msg)) return msg;
      return msg.replace(/\bmobile number\b/i, 'Aadhaar registered mobile number');
    }

    function friendlyOtpErr(raw, fallback) {
      var s = String(raw || '').toLowerCase();
      if (/otp|1204|expired|invalid|validation failed/.test(s)) {
        return 'The OTP you entered has expired or is incorrect.';
      }
      return raw || fallback || '';
    }
    // Show the right validation message when send-OTP fails (esp. "no ABHA for this
    // identifier"). ABDM signals no-match with code ABDM-1115 / "does not match".
    function showSearchOtpFailure(em, searchType) {
      em = stripUidaiCode(em); // "UIDAI Error code : 998 : X" → "X"
      // UIDAI rejected the Aadhaar itself. Its own sentence ("Aadhaar number is
      // incorrect. Resident shall use correct Aadhaar.") is clearer than anything
      // we would substitute, so show exactly that — against the field and nothing
      // prefixed to it.
      if (/aadhaar number is incorrect|resident shall use correct aadhaar/i.test(em)) {
        $('#searchInputHint').text(em).show();
        $('#searchInput').focus();
        toast(em);
        return;
      }
      var notFound = /1115|does not match|no .*record|not found|no abha|not registered|no account|does not exist/i.test(em || '');
      // ABDM rejects a malformed identifier before it ever looks it up, answering
      // with error.loginId = "LoginId is invalid". That is a bad Aadhaar / mobile /
      // ABHA number — NOT "no ABHA registered" — so say so against the field.
      var invalidId = /login\s*id is invalid|invalid login\s*id|invalid (aadhaar|aadhar|mobile|abha)/i.test(em || '');
      if (invalidId) {
        var msg = {
          aadhaar: 'Invalid Aadhaar Number — please check the 12 digits and try again.',
          mobile: 'Invalid mobile number — please check the 10 digits and try again.',
          abhaNumber: 'Invalid ABHA Number — please check the 14 digits and try again.',
          abhaAddress: 'Invalid ABHA Address — please check it and try again.'
        } [searchType] || 'The value you entered is invalid — please check it and try again.';
        $('#searchInputHint').text(msg).show();
        $('#searchInput').focus();
        toast(msg);
        return;
      }
      if (notFound && searchType === 'aadhaar') {
        offerCreateAbha('No ABHA user registered with this Aadhaar Number');
      } else if (notFound && searchType === 'mobile') {
        toast('No ABHA user registered with this mobile number. Please use an ABHA-linked mobile number.');
      } else if (notFound && (searchType === 'abhaNumber' || searchType === 'abhaAddress')) {
        toast('No ABHA record found for the entered ' + (searchType === 'abhaNumber' ? 'ABHA number' : 'ABHA address') + '.');
      } else {
        toast('Unable to send OTP: ' + (em || 'Please try again.'));
      }
    }

    // ── Mobile login: search ABHA(s) by mobile → user picks one → OTP by index. ──
    function startMobileLogin(mobile, accessToken) {
      $.ajax({
        url: 'abha_integration.php',
        method: 'POST',
        contentType: 'application/json',
        dataType: 'json',
        data: JSON.stringify({
          page: 'search_abha_by_mobile',
          mobile: mobile,
          accessToken: accessToken
        }),
        success: function(resp) {
          hideLoader();
          var arr = Array.isArray(resp) ? resp : ((resp && resp.data) || []);
          var first = arr && arr[0];
          var searchTxnId = first && first.txnId;
          var list = (first && first.ABHA) || [];
          if (!searchTxnId || !list.length) {
            showSearchOtpFailure(deepErr(resp) || 'no record', 'mobile');
            return;
          }
          window.MOBILE_LOGIN = {
            searchTxnId: searchTxnId,
            mobile: mobile
          };
          var esc = function(s) {
            return $('<div>').text(s == null ? '' : s).html();
          };
          var html = list.map(function(a) {
            var g = a.gender === 'M' ? 'Male' : a.gender === 'F' ? 'Female' : (a.gender || '-');
            return '<tr>' +
              '<td class="text-center"><input type="radio" name="selectedAbha" value="' + esc(a.index) + '"></td>' +
              '<td>' + esc(a.ABHANumber) + '</td>' +
              '<td>' + esc(a.name) + '</td>' +
              '<td>' + esc(g) + '</td>' +
              '</tr>';
          }).join('');
          $('#abhaTableBody').html(html);
          $('#selectAbhaModal').modal('show');
        },
        error: function(xhr) {
          hideLoader();
          var parsed = {};
          try {
            parsed = JSON.parse(xhr.responseText || '{}');
          } catch (e) {}
          showSearchOtpFailure(deepErr(parsed) || abdmErrMsg(xhr, ''), 'mobile');
        }
      });
    }

    // Selected an ABHA from the mobile search → request its login OTP (by index).
    function requestMobileLoginOtp(index) {
      showLoader();
      $.ajax({
        url: 'abha_integration.php',
        method: 'POST',
        contentType: 'application/json',
        dataType: 'json',
        data: JSON.stringify({
          page: 'login_otp_by_index',
          txnId: window.MOBILE_LOGIN.searchTxnId,
          index: index,
          accessToken: window.ABHA_ACCESS_TOKEN
        }),
        success: function(resp) {
          hideLoader();
          if (resp && resp.success && resp.data && resp.data.txnId) {
            window.MOBILE_LOGIN.otpTxnId = resp.data.txnId;
            window.MOBILE_LOGIN.index = index; // remember selection for resends
            $('#selectAbhaModal').modal('hide');
            $('.modal-backdrop').remove();
            $('#txnId1').val(resp.data.txnId);
            $('#searchOtpPromptTxt').text(resp.data.message || 'OTP sent to your registered mobile number');
            $('#abha_verify1').css('display', 'block');
            // Count resends so the 2-attempt limit applies to this flow too.
            onOtpSent('resend_search_otp', 'resend_search_timer', window.__searchResend === true);
            window.__searchResend = false;
          } else {
            toast('Unable to send OTP: ' + (deepErr(resp) || 'Please try again.'));
          }
        },
        error: function(xhr) {
          hideLoader();
          var parsed = {};
          try {
            parsed = JSON.parse(xhr.responseText || '{}');
          } catch (e) {}
          toast('OTP error: ' + (deepErr(parsed) || abdmErrMsg(xhr, 'Please try again.')));
        }
      });
    }

    // Verify the mobile-login OTP → /profile/login/verify → fetch profile → card.
    function verifyMobileLoginOtp(txnId, otp) {
      $.ajax({
        url: 'abha_integration.php',
        method: 'POST',
        contentType: 'application/json',
        dataType: 'json',
        data: JSON.stringify({
          page: 'verify_otp',
          otp: otp,
          txnId: txnId,
          searchType: 'mobile',
          accessToken: window.ABHA_ACCESS_TOKEN
        }),
        success: function(response) {
          if (!response || !response.success) {
            hideLoader();
            toast('OTP verification failed: ' + (deepErr(response) || 'Incorrect OTP. Please try again.'));
            return;
          }
          var d = response.data || {};
          // ABDM returns success:true even when the OTP auth failed — catch it.
          if (String(d.authResult).toLowerCase() === 'failed') {
            hideLoader();
            toast(friendlyOtpErr(d.message, 'The OTP you entered has expired or is incorrect.'));
            return;
          }
          var xToken = (d.tokens && d.tokens.token) || d.token || '';
          window.MOBILE_LOGIN = null;
          $('#abha_verify1').hide();
          $('.modal-backdrop').remove();
          if (xToken) {
            fetchAbhaProfile(xToken); // fetch + populate + show card (handles loader)
          } else if (d.ABHAProfile || d.profile) {
            window.ABHA_CARD_SOURCE = 'enrollment';
            populateAbhaCard(d.ABHAProfile || d.profile);
            $('#aabha_card_section').show(); $('#abhaSearchPanel').hide(); $('.abha-workspace').addClass('full-card');
            if (window.refreshAbhaLinkState) refreshAbhaLinkState();
            hideLoader();
          } else {
            hideLoader();
            toast('Verified, but no profile/token was returned.');
          }
        },
        error: function(xhr) {
          hideLoader();
          var p = {};
          try {
            p = JSON.parse(xhr.responseText || '{}');
          } catch (e) {}
          toast('OTP error: ' + (deepErr(p) || abdmErrMsg(xhr, 'Incorrect OTP. Please try again.')));
        }
      });
    }

    $('#searchAadhar').on('click', function(e) {
      e.preventDefault();

      let aadhaar = $('#searchInput').val().trim();
      const searchType = $('#searchType').val().trim();
      const verifyMode = $('#abhaNumberMode').val();
      window.SEARCH_MOBILE = searchType === 'mobile' ? aadhaar : '';

      if (searchType == 'aadhaar' && aadhaarError(aadhaar)) {
        var searchAadhaarProblem = aadhaarError(aadhaar);
        $('#searchInputHint').text(searchAadhaarProblem).show();
        $('#searchInput').focus();
        toast(searchAadhaarProblem);
        return;
      } else if (!/^[6-9]\d{9}$/.test(aadhaar) && searchType == 'mobile') {
        toast("Enter a valid 10-digit mobile number (starting with 6-9)");
        return;
      } else if (!/^\d{14}$/.test(aadhaar) && searchType == 'abhaNumber') {
        toast("Enter valid 14-digit ABHA Number");
        return;
      } else if (searchType == 'abhaAddress') {
        // The field holds the username only; the @sbx/@abdm domain is fixed.
        // (Tolerate a pasted full "user@domain" by keeping just the username.)
        var uname = aadhaar.split('@')[0];
        if (uname.length < 8 || uname.length > 18 ||
            !/^[a-z0-9](?:[a-z0-9._]*[a-z0-9])?$/i.test(uname)) {
          toast("ABHA Address must be 8–18 characters and cannot start/end with . or _");
          return;
        }
        // Auto-append this environment's domain for the actual lookup.
        aadhaar = uname + '@' + ABHA_SEARCH_DOMAIN;
      }
      showLoader(); // start loader at beginning

      // STEP 1: Get Token
      // Retries on a missing/failed token before surfacing anything to the operator.
      fetchAbdmToken(function(accessToken) {
        // STEP 2: Get Public Key
        $.ajax({
          url: 'abha_integration.php',
          method: 'POST',
          contentType: 'application/json',
          dataType: 'json',
          data: JSON.stringify({
            page: 'get_public_key',
            accessToken: accessToken
          }),
          success: function(response) {
            // Mobile uses the proper search→select→OTP-by-index→verify login flow.
            if (searchType === 'mobile') {
              startMobileLogin(aadhaar, accessToken);
              return;
            }
            // STEP 3: Send OTP (aadhaar / abhaNumber / abhaAddress)
            $.ajax({
              url: 'abha_integration.php',
              method: 'POST',
              contentType: 'application/json',
              dataType: 'json',
              data: JSON.stringify({
                page: 'send_otp',
                aadhaar: aadhaar,
                searchType: searchType,
                verifyMode: verifyMode,
                accessToken: accessToken
              }),
              success: function(response) {
                // ABDM errors arrive as HTTP 200 with success:false — handle here,
                // otherwise reading response.data.txnId throws and the loader hangs.
                if (!response || !response.success || !(response.data && response.data.txnId)) {
                  hideLoader();
                  showSearchOtpFailure(deepErr(response), searchType);
                  return;
                }
                $('#abha_verify1').css('display', 'block');
                // Keep ABDM's masked digits, but name the mobile it actually used.
                $('#searchOtpPromptTxt').text(
                  otpSentMessage(response.data && response.data.message, verifyMode)
                );
                $('#txnId1').val(response.data.txnId);
                onOtpSent('resend_search_otp', 'resend_search_timer', window.__searchResend === true);
                window.__searchResend = false;
                hideLoader(); // stop loader only after send_otp finishes
              },
              error: function(xhr) {
                console.error(xhr.responseText);
                hideLoader();
                var parsed = {};
                try {
                  parsed = JSON.parse(xhr.responseText || '{}');
                } catch (e) {}
                showSearchOtpFailure(deepErr(parsed) || abdmErrMsg(xhr, ''), searchType);
              }
            });
          },
          error: function(xhr) {
            console.error(xhr.responseText);
            hideLoader();
          }
        });

      }).fail(function(xhr) {
        toast("Token Error: " + xhr.responseText);
        hideLoader();
      });

    });
    // Step indicator for #enterotp modal (4 steps)
    function setStep(n) {
      window.__abhaStep = n;
      window.__abhaMaxStep = Math.max(window.__abhaMaxStep || 1, Math.min(n, 4));
      for (let i = 1; i <= 4; i++) {
        const el = document.getElementById('mstep' + i);
        if (!el) continue;
        const dot = el.querySelector('.mstep-dot');
        el.classList.remove('active', 'done');
        if (i < n) {
          el.classList.add('done');
          if (dot) dot.textContent = '✓';
        } else {
          if (i === n) el.classList.add('active');
          if (dot) dot.textContent = i;
        }
      }
    }

    // Show exactly one step panel at a time (a true wizard page, not one scroll).
    // Step 3 and 4 share the mobile panel. Data in hidden panels is preserved.
    function abhaRenderStep(n) {
      $('#abha_step1').toggle(n === 1);
      $('#abha_verify').toggle(n === 2);
      $('#mobile_update_block').toggle(n === 3 || n === 4);
      setStep(n);
    }
    // Forward progression (called after an OTP/API step succeeds): unlock + show.
    function abhaShowStep(n) {
      window.__abhaMaxStep = Math.max(window.__abhaMaxStep || 1, Math.min(n, 4));
      abhaRenderStep(n);
    }
    // Back button / step-dot navigation — can't jump ahead of the reached step.
    function abhaGoToStep(n) {
      abhaRenderStep(Math.max(1, Math.min(n, window.__abhaMaxStep || 1)));
    }
    // A wrong Aadhaar OTP only surfaces at create time → send the user back to the
    // Aadhaar-OTP page to re-enter it (fields are editable, not locked).
    function abhaBackToAadhaarOtp() {
      for (let i = 1; i <= 6; i++) {
        $('#otp' + i).val('');
      }
      abhaGoToStep(2);
      $('#otp1').focus();
    }
    $(document).on('click', '.abha-back-btn', function() {
      abhaGoToStep(parseInt($(this).data('back-to'), 10) || 1);
    });
    // Step indicator dots double as navigation to any reached step.
    $('#mstep1,#mstep2,#mstep3,#mstep4').css('cursor', 'pointer').on('click', function() {
      abhaGoToStep(parseInt(this.id.replace('mstep', ''), 10) || 1);
    });

    $('#enterotp').on('show.bs.modal', function() {
      window.__abhaMaxStep = 1;
      abhaRenderStep(1);
      abhaApplyAuthMode();
      if (window.refreshCreateAbhaBtn) refreshCreateAbhaBtn();
    });

    // ── Biometric Aadhaar authentication (UIDAI RD service) ──────────────────
    // Fingerprint (Mantra MFS100) and iris (Mantra MIS100V2) scanners are reached
    // through the vendor RD Service running on this PC at 127.0.0.1:11100-11120.
    // The RD service returns a PID block already encrypted for UIDAI — the page
    // only forwards it and never sees raw biometrics. Do not log the PID XML.
    const RD_HOST = 'http://127.0.0.1';
    const RD_PORT_FROM = 11100;
    const RD_PORT_TO = 11120;
    // UIDAI environment the PID is generated for: PP (pre-production) for the ABDM
    // sandbox, P for production. The RD service itself must be configured for the
    // same environment (ask the device vendor to switch it), else UIDAI rejects it.
    const RD_ENV = <?php echo json_encode(getenv('RD_ENV') ?: (ABDM_CM_ID === 'sbx' ? 'PP' : 'P')); ?>;
    // ABHA enrolment by biometric is an Aadhaar eKYC, and UIDAI only accepts the PID
    // when its wadh matches the eKYC parameters ABDM sends; otherwise it answers
    // K-547 "Invalid value for wadh". wadh = base64(sha256(ver+ra+rc+lr+de+pfr)).
    // Verified on production 2026-10-24: ABDM's eKYC needs the wadh for "2.5FYYNN"
    // (ver 2.5, ra=F, rc=Y, lr=Y, de=N, pfr=N) = RZ+k4w9y…. Refused: "2.5FYNNN" and
    // "2.1FYNNN" (K-547), wadh="" and no wadh (552). RD_WADH in .env overrides it.
    const RD_WADH = <?php echo json_encode((string) (getenv('RD_WADH') ?: 'RZ+k4w9ySTzOibQdDHPzCFqrKScZ74b3EibKYy1WyGw=')); ?>;
    const RD_CAPTURE_TIMEOUT_MS = 15000;
    const RD_MIN_QSCORE = 40; // below this, offer a re-scan before using the capture
    // Set ABHA_BIO_ENROLL=true in .env once ABDM enables biometric enrolment for
    // this client ID; until then a capture only verifies the scanner works.
    const BIO_ENROLL_ENABLED = <?php echo json_encode(strtolower((string) getenv('ABHA_BIO_ENROLL')) === 'true'); ?>;

    window.RD_DEVICES = null; // {finger: {...}, iris: {...}} once discovered
    window.ABHA_BIO = null; // last successful capture {mode, pid, qScore, device}

    function abhaAuthMode() {
      return $('input[name="abha_auth_mode"]:checked').val() || 'otp';
    }

    function rdModeLabel(mode) {
      return mode === 'iris' ? 'Iris' : 'Fingerprint';
    }

    // RD services answer custom HTTP verbs (RDSERVICE / DEVICEINFO / CAPTURE).
    function rdCall(method, url, body, timeout) {
      return $.ajax({
        type: method,
        url: url,
        data: body || null,
        contentType: 'text/xml; charset=utf-8',
        processData: false,
        dataType: 'text',
        crossDomain: true,
        timeout: timeout
      });
    }

    function rdParseXml(text) {
      try {
        var doc = new DOMParser().parseFromString(text || '', 'text/xml');
        return doc.getElementsByTagName('parsererror').length ? null : doc;
      } catch (e) {
        return null;
      }
    }

    // Probe one port: RDSERVICE tells us status + interface paths, DEVICEINFO the
    // model (mi) which decides fingerprint vs iris. Resolves null when nothing
    // usable is listening there.
    function rdProbePort(port) {
      var base = RD_HOST + ':' + port;
      var d = $.Deferred();
      rdCall('RDSERVICE', base + '/', null, 2000).done(function(text) {
        var doc = rdParseXml(text);
        var svc = doc && doc.getElementsByTagName('RDService')[0];
        if (!svc) return d.resolve(null);
        var paths = {};
        $(doc).find('Interface').each(function() {
          paths[(this.getAttribute('id') || '').toUpperCase()] = this.getAttribute('path') || '';
        });
        var dev = {
          port: port,
          base: base,
          status: (svc.getAttribute('status') || '').toUpperCase(),
          info: svc.getAttribute('info') || '',
          capturePath: paths.CAPTURE || '/rd/capture',
          infoPath: paths.DEVICEINFO || '/rd/info',
          model: ''
        };
        rdCall('DEVICEINFO', base + dev.infoPath, null, 3000).done(function(t) {
          var di = rdParseXml(t);
          var node = di && di.getElementsByTagName('DeviceInfo')[0];
          if (node) dev.model = node.getAttribute('mi') || '';
          // L1 RD services state their modality outright (e.g. "Finger" / "Iris").
          $(di).find('Param[name="modality_type"]').each(function() {
            dev.modality = this.getAttribute('value') || '';
          });
        }).always(function() {
          dev.mode = /IRIS/i.test(dev.modality || '') ? 'iris' :
            dev.modality ? 'finger' :
            (/MIS|IRIS/i.test(dev.model + ' ' + dev.info) ? 'iris' : 'finger');
          d.resolve(dev);
        });
      }).fail(function() {
        d.resolve(null);
      });
      return d.promise();
    }

    // Scan the RD port range in parallel; keep the first device found per mode,
    // preferring one whose status is READY.
    function rdDiscover() {
      var probes = [];
      for (var p = RD_PORT_FROM; p <= RD_PORT_TO; p++) probes.push(rdProbePort(p));
      return $.when.apply($, probes).then(function() {
        var found = {};
        Array.prototype.slice.call(arguments).forEach(function(dev) {
          if (!dev) return;
          var cur = found[dev.mode];
          if (!cur || (cur.status !== 'READY' && dev.status === 'READY')) found[dev.mode] = dev;
        });
        window.RD_DEVICES = found;
        return found;
      });
    }

    function bioDeviceFor(mode) {
      var dev = window.RD_DEVICES && window.RD_DEVICES[mode];
      return dev && dev.status === 'READY' ? dev : null;
    }

    function renderBioStatus() {
      var mode = abhaAuthMode();
      var $s = $('#bioDeviceStatus');
      if (mode === 'otp') {
        $s.hide();
        return;
      }
      $s.removeClass('ready missing checking').show();
      if (window.RD_DEVICES === null) {
        $s.addClass('checking');
        $('#bioDeviceStatusText').text('Looking for ' + rdModeLabel(mode).toLowerCase() + ' scanner…');
        return;
      }
      var any = window.RD_DEVICES[mode];
      var dev = bioDeviceFor(mode);
      if (dev) {
        $s.addClass('ready');
        $('#bioDeviceStatusText').text('✓ ' + (dev.model || rdModeLabel(mode) + ' scanner') + ' ready (port ' + dev.port + ')');
      } else if (any) {
        $s.addClass('missing');
        $('#bioDeviceStatusText').text((any.model || rdModeLabel(mode) + ' scanner') + ' found but not ready (' + (any.status || 'UNKNOWN') + ') — check the cable / RD Service.');
      } else {
        $s.addClass('missing');
        $('#bioDeviceStatusText').text(rdModeLabel(mode) + ' scanner not detected. Connect it and make sure its RD Service is running.');
      }
    }

    function abhaRefreshDevices() {
      window.RD_DEVICES = null;
      renderBioStatus();
      refreshCreateAbhaBtn();
      return rdDiscover().always(function() {
        renderBioStatus();
        refreshCreateAbhaBtn();
      });
    }

    // Button label + device status follow the selected method; discovery runs the
    // first time a biometric method is picked (and on "Re-check").
    function abhaApplyAuthMode() {
      var mode = abhaAuthMode();
      var label = mode === 'otp' ? 'Send Aadhaar OTP' : 'Capture ' + rdModeLabel(mode);
      $('#create_abha').html('<i class="ri-' + (mode === 'otp' ? 'send-plane' : 'fingerprint') + '-line"></i> ' + label);
      if (mode !== 'otp' && window.RD_DEVICES === null && !window.__rdDiscovering) {
        window.__rdDiscovering = true;
        abhaRefreshDevices().always(function() {
          window.__rdDiscovering = false;
        });
      }
      renderBioStatus();
      if (window.refreshCreateAbhaBtn) refreshCreateAbhaBtn();
    }
    $('input[name="abha_auth_mode"]').on('change', abhaApplyAuthMode);
    $('#bioDeviceRecheck').on('click', function() {
      abhaRefreshDevices();
    });

    // omitEnv: leave env out so the RD service uses the environment it is
    // configured for (used when the device rejects RD_ENV with -1524).
    function rdPidOptions(mode, omitEnv) {
      var finger = mode === 'finger';
      // No XML declaration: inside a .php file it would read as a PHP short open tag.
      return '<PidOptions ver="1.0">' +
        '<Opts fCount="' + (finger ? 1 : 0) + '" fType="' + (finger ? 2 : '') + '"' +
        ' iCount="' + (finger ? 0 : 1) + '" iType="' + (finger ? '' : 0) + '"' +
        ' pCount="0" pType="" format="0" pidVer="2.0"' +
        ' timeout="' + (RD_CAPTURE_TIMEOUT_MS - 2000) + '" posh="UNKNOWN"' +
        (omitEnv ? '' : ' env="' + RD_ENV + '"') +
        // wadh="" makes the RD service emit an empty wadh, which UIDAI refuses
        // (552 "Invalid wadh element"); with no value the attribute is left out.
        (RD_WADH ? ' wadh="' + RD_WADH + '"' : '') + ' />' +
        '<CustOpts></CustOpts>' +
        '</PidOptions>';
    }

    // Capture one PID block from the scanner for the selected mode.
    function abhaBiometricCapture(mode, aadhaar, mobile) {
      var dev = bioDeviceFor(mode);
      if (!dev) {
        renderBioStatus();
        toast(rdModeLabel(mode) + ' scanner is not ready. Connect it and click Re-check.');
        return;
      }
      var $txt = $('#loader-wrapper .loader-txt');
      var oldTxt = $txt.text();
      $txt.text(mode === 'iris' ? 'Ask the patient to look into the iris scanner…' : 'Place the finger on the scanner…');
      showLoader();
      var omitEnv = false;
      var capture = function() {
        return rdCall('CAPTURE', dev.base + dev.capturePath, rdPidOptions(mode, omitEnv), RD_CAPTURE_TIMEOUT_MS);
      };
      // -1524 "Invalid value for Env": the device is locked to another UIDAI
      // environment than RD_ENV (e.g. a production-only RD service while the
      // ABDM sandbox asks for PP). Retry once and let the device use its own.
      var attempt = capture().then(function(text) {
        if (/errCode="-1524"/.test(text || '')) {
          console.warn('RD service rejected env="' + RD_ENV + '" — retrying with the device\'s own environment.');
          omitEnv = true;
          return capture();
        }
        return text;
      });
      attempt.done(function(text) {
        var doc = rdParseXml(text);
        var resp = doc && doc.getElementsByTagName('Resp')[0];
        if (!resp) {
          toast('Scanner returned an unreadable response. Please try again.');
          return;
        }
        var errCode = resp.getAttribute('errCode');
        if (errCode !== '0') {
          toast('Capture failed: ' + (resp.getAttribute('errInfo') || 'error') + ' (' + errCode + ')');
          return;
        }
        var qScore = parseInt(resp.getAttribute('qScore'), 10) || 0;
        var bio = {
          mode: mode,
          pid: text,
          qScore: qScore,
          device: dev.model,
          aadhaar: aadhaar,
          mobile: mobile
        };
        if (qScore && qScore < RD_MIN_QSCORE) {
          toast('Low capture quality (' + qScore + '). Clean the scanner and re-scan for a better result.', 'error', {
            label: 'Use anyway',
            onClick: function() {
              abhaSubmitBiometric(bio);
            }
          });
          return;
        }
        abhaSubmitBiometric(bio);
      }).fail(function(xhr, status) {
        // A rejection forwarded through .then() only carries the jqXHR.
        status = status || (xhr && xhr.statusText);
        toast(status === 'timeout' ?
          'Scanner timed out — no ' + (mode === 'iris' ? 'iris' : 'finger') + ' detected. Please try again.' :
          'Could not reach the scanner RD Service. Click Re-check and try again.');
      }).always(function() {
        hideLoader();
        $txt.text(oldTxt);
      });
    }

    // Hand the captured PID to ABDM biometric enrolment (enrol/byAadhaar, "bio").
    // A PID block is single-use (UIDAI rejects replays), so every attempt — good
    // or bad — needs a fresh capture. On success the flow continues exactly as
    // after a verified Aadhaar OTP (card, mobile check, ABHA address picker).
    function abhaSubmitBiometric(bio) {
      window.ABHA_BIO = bio;
      $('#bioDeviceStatus').removeClass('missing checking').addClass('ready').show();
      $('#bioDeviceStatusText').text('✓ ' + rdModeLabel(bio.mode) + ' captured' + (bio.qScore ? ' — quality ' + bio.qScore : '') + '.');
      if (!BIO_ENROLL_ENABLED) {
        window.ABHA_BIO = null;
        toast(rdModeLabel(bio.mode) + ' captured successfully. Biometric ABHA creation is not enabled yet — use Aadhaar OTP for now.', 'info');
        return;
      }

      showLoader();
      withAbdmToken(function(accessToken) {
        $.ajax({
          url: 'abha_integration.php',
          method: 'POST',
          contentType: 'application/json',
          dataType: 'json',
          data: JSON.stringify({
            page: 'enroll_bio',
            aadhaar: bio.aadhaar,
            mobile: bio.mobile,
            bioType: bio.mode,
            pid: bio.pid,
            accessToken: accessToken
          }),
          success: function(response) {
            window.ABHA_BIO = null;
            if (response && response.success && response.data && response.data.ABHAProfile) {
              // Enrolment txnId drives the mobile-verify + ABHA-address steps.
              $('#txnId').val(response.data.txnId || '');
              $('#mobileTxnId').val('');
              window.MOBILE_OTP_VERIFIED = false;
              handleAbhaCreated(response.data);
            } else {
              var info = abdmErrorText(response || {});
              toast((info.message ?
                  rdModeLabel(bio.mode) + ' authentication failed: ' + info.message + (info.code ? ' (' + info.code + ')' : '') :
                  rdModeLabel(bio.mode) + ' authentication failed.') +
                ' Please capture again or use Aadhaar OTP.');
              hideLoader();
            }
          },
          error: function(xhr) {
            window.ABHA_BIO = null;
            console.error(xhr.status, xhr.statusText); // never log the request (PID)
            toast(rdModeLabel(bio.mode) + ' authentication error: ' + abdmErrMsg(xhr, 'Please capture again or use Aadhaar OTP.'));
            hideLoader();
          }
        });
      });
    }

    $('#create_abha').on('click', function(e) {
      e.preventDefault();

      const aadhaar = $('#aadhaar').val().trim();
      const regMobile = ($('#reg_mobile').val() || '').trim();

      var reqTotal = $('.consentChk[data-required="1"]').length;
      var reqDone = $('.consentChk[data-required="1"]:checked').length;
      if (reqTotal === 0 || reqDone < reqTotal) {
        toast("Please accept the mandatory consent items to continue.");
        return;
      }
      if (!$('#consent_beneficiary_name').val().trim()) {
        toast("Please enter the beneficiary's name in the consent.");
        $('#consent_beneficiary_name').focus();
        return;
      }

      var aadhaarProblem = aadhaarError(aadhaar);
      if (aadhaarProblem) {
        showAadhaarHint(aadhaarProblem);
        toast(aadhaarProblem);
        $('#aadhaar').focus();
        return;
      }
      showAadhaarHint('');
      if (!/^[6-9]\d{9}$/.test(regMobile)) {
        toast("Enter a valid 10-digit mobile number");
        $('#reg_mobile').focus();
        return;
      }
      // Carry the entered mobile forward — used at ABHA creation and, if the ABHA's
      // registered mobile differs, for the OTP-verified mobile update.
      window.ABHA_ENTERED_MOBILE = regMobile;
      $('#mobile').val(regMobile);

      // TC-002: log the consent acceptance once (not on Resend re-trigger).
      if (!window.__aadhaarResend && window.storeConsent) {
        storeConsent('create');
      }

      // Biometric method: capture from the RD-service scanner instead of sending an OTP.
      // (Resend OTP re-triggers this button and must always stay on the OTP path.)
      if (abhaAuthMode() !== 'otp' && !window.__aadhaarResend) {
        abhaBiometricCapture(abhaAuthMode(), aadhaar, regMobile);
        return;
      }

      showLoader(); // start loader at beginning

      // STEP 1: Get Token
      // Retries on a missing/failed token before surfacing anything to the operator.
      fetchAbdmToken(function(accessToken) {
        // STEP 2: Get Public Key
        $.ajax({
          url: 'abha_integration.php',
          method: 'POST',
          contentType: 'application/json',
          dataType: 'json',
          data: JSON.stringify({
            page: 'get_public_key',
            accessToken: accessToken
          }),
          success: function(response) {
            // STEP 3: Send OTP
            $.ajax({
              url: 'abha_integration.php',
              method: 'POST',
              contentType: 'application/json',
              dataType: 'json',
              data: JSON.stringify({
                page: 'enroll_otp',
                aadhaar: aadhaar,
                accessToken: accessToken
              }),
              success: function(response) {

                // ABDM returns HTTP 200 even when the Aadhaar is invalid, so jQuery
                // treats it as "success". Guard on the payload before advancing —
                // otherwise the UI wrongly proceeds to the OTP screen.
                var otpSent = response && response.success !== false && response.data && response.data.txnId;
                if (!otpSent) {
                  hideLoader();
                  // Show ABDM/UIDAI's own reason — the wrapper "Failed to send OTP
                  // for ABHA enrollment" says nothing the operator can act on.
                  var info = abdmErrorText(response);
                  if (isAadhaarRejection(info)) {
                    var m = info.message || 'This Aadhaar Number was rejected by UIDAI.';
                    showAadhaarHint(m);
                    $('#aadhaar').focus();
                    toast(m);
                  } else {
                    toast(info.message ?
                      (info.message + (info.code ? ' (' + info.code + ')' : '')) :
                      'Unable to send OTP. Please try again.');
                  }
                  return;
                }

                abhaShowStep(2); // hide step 1, show the Aadhaar-OTP page
                // Show ABDM's confirmation (includes the masked mobile number).
                // Enrolment always authenticates against Aadhaar, so the OTP goes to
                // the Aadhaar-registered mobile — label it as such.
                $('#aadhaarOtpMsg').text(otpSentMessage(response.data && response.data.message, 'aadhaar'));
                $('#txnId').val(response.data.txnId);
                $('#mobileTxnId').val('');
                window.MOBILE_OTP_VERIFIED = false;
                $('#send_mobile_otp').prop('disabled', false);
                $('#verify_mobile_otp').prop('disabled', false).html('<i class="ri-check-line"></i> Verify &amp; Update Mobile');
                for (let i = 1; i <= 6; i++) {
                  $('#otp' + i).prop('readonly', false).val('');
                  $('#mobile_otp' + i).prop('readonly', false).val('');
                }
                onOtpSent('resend_aadhaar_otp', 'resend_aadhaar_timer', window.__aadhaarResend === true);
                window.__aadhaarResend = false;
                hideLoader(); // stop loader only after send_otp finishes
              },
              error: function(xhr) {
                console.error(xhr.responseText);
                hideLoader();
                // Same payload shape arrives on a non-200 — surface its reason too.
                var info = abdmErrorText(xhr.responseJSON || {});
                if (!info.message && /Invalid\s*LoginId|invalid\s*aadhaar/i.test(xhr.responseText || '')) {
                  info = {
                    code: '',
                    message: 'Invalid Aadhaar Number'
                  };
                }
                if (isAadhaarRejection(info)) {
                  showAadhaarHint(info.message);
                  $('#aadhaar').focus();
                  toast(info.message);
                } else {
                  toast(info.message ?
                    (info.message + (info.code ? ' (' + info.code + ')' : '')) :
                    'Unable to send OTP. Please try again.');
                }
              }
            });
          },
          error: function(xhr) {
            console.error(xhr.responseText);
            hideLoader();
          }
        });

      }).fail(function(xhr) {
        toast("Token Error: " + xhr.responseText);
        hideLoader();
      });

    });

    // ── Multilingual consent (A2b) ──────────────────────────────────────────
    // NOTE: replace hi/kn with ABDM's OFFICIAL translated consent text for cert.
    const CONSENT_TEXT = {
      en: {
        text: "I, hereby declare that I am voluntarily sharing my Aadhaar Number and demographic information issued by UIDAI, with National Health Authority (NHA) for the sole purpose of creation of ABHA number. I understand that my ABHA number can be used and shared for purposes as may be notified by ABDM from time to time including provision of healthcare services. Further, I am aware that my personal identifiable information (Name, Address, Age, Date of Birth, Gender and Photograph) may be made available to the entities working in the National Digital Health Ecosystem (NDHE) which inter alia includes stakeholders and entities such as healthcare professionals (e.g. doctors), facilities (e.g. hospitals, laboratories) and data fiduciaries (e.g. health programmes), which are registered with or linked to the Ayushman Bharat Digital Mission (ABDM), and various processes there under. I authorize NHA to use my Aadhaar number for performing Aadhaar based authentication with UIDAI as per the provisions of the Aadhaar Act, 2016.",
        agree: "I agree to the above consent and authorize ABHA creation."
      },
      hi: {
        text: "मैं, एतद्द्वारा घोषणा करता/करती हूँ कि मैं स्वेच्छा से UIDAI द्वारा जारी अपना आधार नंबर और जनसांख्यिकीय जानकारी, केवल ABHA नंबर बनाने के उद्देश्य से राष्ट्रीय स्वास्थ्य प्राधिकरण (NHA) के साथ साझा कर रहा/रही हूँ। मैं समझता/समझती हूँ कि मेरा ABHA नंबर समय-समय पर ABDM द्वारा अधिसूचित उद्देश्यों के लिए, जिसमें स्वास्थ्य सेवाओं का प्रावधान शामिल है, उपयोग और साझा किया जा सकता है। इसके अतिरिक्त, मुझे ज्ञात है कि मेरी व्यक्तिगत पहचान योग्य जानकारी (नाम, पता, आयु, जन्म तिथि, लिंग और फोटोग्राफ) राष्ट्रीय डिजिटल स्वास्थ्य पारिस्थितिकी तंत्र (NDHE) में कार्यरत उन संस्थाओं को उपलब्ध कराई जा सकती है जिनमें चिकित्सक, अस्पताल, प्रयोगशालाएँ और स्वास्थ्य कार्यक्रम शामिल हैं, जो आयुष्मान भारत डिजिटल मिशन (ABDM) के साथ पंजीकृत या संबद्ध हैं। मैं आधार अधिनियम, 2016 के प्रावधानों के अनुसार UIDAI के साथ आधार आधारित प्रमाणीकरण करने हेतु NHA को अपने आधार नंबर का उपयोग करने के लिए अधिकृत करता/करती हूँ।",
        agree: "मैं उपरोक्त सहमति से सहमत हूँ और ABHA निर्माण को अधिकृत करता/करती हूँ।"
      },
      kn: {
        text: "ನಾನು ಈ ಮೂಲಕ, UIDAI ನೀಡಿರುವ ನನ್ನ ಆಧಾರ್ ಸಂಖ್ಯೆ ಮತ್ತು ಜನಸಂಖ್ಯಾ ಮಾಹಿತಿಯನ್ನು ಕೇವಲ ABHA ಸಂಖ್ಯೆ ರಚಿಸುವ ಉದ್ದೇಶಕ್ಕಾಗಿ ರಾಷ್ಟ್ರೀಯ ಆರೋಗ್ಯ ಪ್ರಾಧಿಕಾರ (NHA) ದೊಂದಿಗೆ ಸ್ವಯಂಪ್ರೇರಿತವಾಗಿ ಹಂಚಿಕೊಳ್ಳುತ್ತಿದ್ದೇನೆ ಎಂದು ಘೋಷಿಸುತ್ತೇನೆ. ನನ್ನ ABHA ಸಂಖ್ಯೆಯನ್ನು ಆರೋಗ್ಯ ಸೇವೆಗಳನ್ನು ಒದಗಿಸುವುದು ಸೇರಿದಂತೆ ABDM ಕಾಲಕಾಲಕ್ಕೆ ಸೂಚಿಸುವ ಉದ್ದೇಶಗಳಿಗಾಗಿ ಬಳಸಬಹುದು ಮತ್ತು ಹಂಚಿಕೊಳ್ಳಬಹುದು ಎಂದು ನಾನು ಅರ್ಥಮಾಡಿಕೊಂಡಿದ್ದೇನೆ. ಇದಲ್ಲದೆ, ನನ್ನ ವೈಯಕ್ತಿಕ ಗುರುತಿನ ಮಾಹಿತಿಯನ್ನು (ಹೆಸರು, ವಿಳಾಸ, ವಯಸ್ಸು, ಜನ್ಮ ದಿನಾಂಕ, ಲಿಂಗ ಮತ್ತು ಫೋಟೋ) ಆಯುಷ್ಮಾನ್ ಭಾರತ್ ಡಿಜಿಟಲ್ ಮಿಷನ್ (ABDM) ನೊಂದಿಗೆ ನೋಂದಾಯಿಸಲ್ಪಟ್ಟ ಅಥವಾ ಸಂಪರ್ಕಿತವಾದ ವೈದ್ಯರು, ಆಸ್ಪತ್ರೆಗಳು, ಪ್ರಯೋಗಾಲಯಗಳು ಮತ್ತು ಆರೋಗ್ಯ ಕಾರ್ಯಕ್ರಮಗಳಂತಹ NDHE ಘಟಕಗಳಿಗೆ ಲಭ್ಯಗೊಳಿಸಬಹುದು ಎಂದು ನನಗೆ ತಿಳಿದಿದೆ. ಆಧಾರ್ ಕಾಯ್ದೆ, 2016 ರ ನಿಬಂಧನೆಗಳ ಪ್ರಕಾರ UIDAI ದೊಂದಿಗೆ ಆಧಾರ್ ಆಧಾರಿತ ದೃಢೀಕರಣಕ್ಕಾಗಿ ನನ್ನ ಆಧಾರ್ ಸಂಖ್ಯೆಯನ್ನು ಬಳಸಲು ನಾನು NHA ಗೆ ಅಧಿಕಾರ ನೀಡುತ್ತೇನೆ.",
        agree: "ನಾನು ಮೇಲಿನ ಸಮ್ಮತಿಯನ್ನು ಒಪ್ಪುತ್ತೇನೆ ಮತ್ತು ABHA ರಚನೆಗೆ ಅಧಿಕಾರ ನೀಡುತ್ತೇನೆ."
      }
    };
    // Hospital/facility name is sourced dynamically from config (HOSPITAL_NAME in
    // .env → FACILITY_NAME) and reused wherever the provider name is shown.
    window.FACILITY_NAME = <?php echo json_encode(defined('FACILITY_NAME') ? FACILITY_NAME : 'Healthcare Provider'); ?>;

    // The consent is now a structured checklist, so only translate the heading.
    const DECLARE_HEAD = {
      en: 'I hereby declare that:',
      hi: 'मैं एतद्द्वारा घोषणा करता/करती हूँ कि:',
      kn: 'ನಾನು ಈ ಮೂಲಕ ಘೋಷಿಸುತ್ತೇನೆ:'
    };
    $('#consent_lang').on('change', function() {
      $('#consent_declare_head').text(DECLARE_HEAD[this.value] || DECLARE_HEAD.en);
    });

    // Consent: "Select All" toggles every consent box; keep it (and the hidden
    // #abha_consent compat flag) in sync as individual boxes change.
    function syncConsentState() {
      var $all = $('.consentChk');
      $('#consentSelectAll').prop('checked', $all.length > 0 && $all.filter(':checked').length === $all.length);
      var reqTotal = $('.consentChk[data-required="1"]').length;
      var reqDone = $('.consentChk[data-required="1"]:checked').length;
      $('#abha_consent').prop('checked', reqTotal > 0 && reqDone === reqTotal);
      refreshCreateAbhaBtn();
    }
    // Gate "Send Aadhaar OTP": needs valid Aadhaar + mobile, all mandatory consents,
    // and the beneficiary name.
    function refreshCreateAbhaBtn() {
      var aadhaarVal = ($('#aadhaar').val() || '').trim();
      var aadhaarOk = aadhaarError(aadhaarVal) === '';
      // Only complain once all 12 digits are in — not while they are still typing.
      showAadhaarHint(aadhaarVal.length === 12 ? aadhaarError(aadhaarVal) : '');
      var mobileOk = /^[6-9]\d{9}$/.test(($('#reg_mobile').val() || '').trim());
      var reqTotal = $('.consentChk[data-required="1"]').length;
      var reqDone = $('.consentChk[data-required="1"]:checked').length;
      var consentOk = reqTotal > 0 && reqDone === reqTotal;
      var nameOk = ($('#consent_beneficiary_name').val() || '').trim().length > 0;
      // Biometric methods also need their scanner detected and READY.
      var deviceOk = abhaAuthMode() === 'otp' || !!bioDeviceFor(abhaAuthMode());
      $('#create_abha').prop('disabled', !(aadhaarOk && mobileOk && consentOk && nameOk && deviceOk));
    }
    window.refreshCreateAbhaBtn = refreshCreateAbhaBtn;
    $('#aadhaar, #reg_mobile, #consent_beneficiary_name').on('input', refreshCreateAbhaBtn);

    // Show / hide the masked Aadhaar number.
    $(document).on('click', '#toggleAadhaar', function() {
      var $i = $('#aadhaar');
      var reveal = $i.attr('type') === 'password';
      $i.attr('type', reveal ? 'text' : 'password');
      $(this).find('i').attr('class', reveal ? 'ri-eye-line' : 'ri-eye-off-line');
    });
    // Show / hide the masked Aadhaar in the patient-search field.
    $(document).on('click', '#toggleSearchInput', function() {
      var $i = $('#searchInput');
      var reveal = $i.attr('type') === 'password';
      $i.attr('type', reveal ? 'text' : 'password');
      $(this).find('i').attr('class', reveal ? 'ri-eye-line' : 'ri-eye-off-line');
    });
    $('#consentSelectAll').on('change', function() {
      $('.consentChk').prop('checked', this.checked);
      syncConsentState();
      if (this.checked && !$('#consent_beneficiary_name').val().trim()) {
        $('#consent_beneficiary_name').focus();
      }
    });
    $(document).on('change', '.consentChk', syncConsentState);
    refreshCreateAbhaBtn(); // set initial disabled state

    // ── Resend OTP — 60s cooldown, max 2 resends (VRFY_ABHA_305 / 405) ────────
    // Hitting the cap is a rate limit, not a dead end: after RESEND_LOCKOUT the
    // counter resets and resending is possible again, so the operator waits rather
    // than restarting the whole enrolment.
    const RESEND_MAX = 2,
      RESEND_WAIT = 60,
      RESEND_LOCKOUT = 30 * 60; // seconds

    function lockoutText(s) {
      const m = Math.ceil(s / 60);
      return s >= 60 ?
        (' — maximum resend attempts reached, try after ' + m + ' min') :
        (' — maximum resend attempts reached, try after ' + s + 's');
    }

    // Cap reached: hold the link for RESEND_LOCKOUT, counting down, then clear the
    // counter so a fresh pair of resends is available.
    function startResendLockout(linkId, timerId) {
      const $link = $('#' + linkId),
        $timer = $('#' + timerId);
      $link.addClass('disabled');
      clearInterval($link.data('t'));
      let s = RESEND_LOCKOUT;
      $timer.text(lockoutText(s));
      const t = setInterval(function() {
        s--;
        if (s <= 0) {
          clearInterval(t);
          $link.data('c', 0); // counter reset — resends allowed again
          $timer.text(' — you can resend now');
          $link.removeClass('disabled');
          return;
        }
        $timer.text(lockoutText(s));
      }, 1000);
      $link.data('t', t);
    }

    function armResend(linkId, timerId, remaining) {
      const $link = $('#' + linkId),
        $timer = $('#' + timerId);
      $link.addClass('disabled');
      clearInterval($link.data('t'));
      let s = RESEND_WAIT;
      $timer.text(' (' + s + 's)');
      const t = setInterval(function() {
        s--;
        if (s <= 0) {
          clearInterval(t);
          if (remaining > 0) {
            // Resend available again — tell the user how many attempts are left.
            $timer.text(' — ' + remaining + ' attempt' + (remaining === 1 ? '' : 's') + ' remaining');
            $link.removeClass('disabled');
          } else {
            startResendLockout(linkId, timerId);
          }
        } else {
          $timer.text(' (' + s + 's)');
        }
      }, 1000);
      $link.data('t', t);
    }
    // Call on every OTP send. isResend=false => fresh send (reset the counter);
    // isResend=true => a resend (increment). Only RESEND_MAX resends are allowed.
    function onOtpSent(linkId, timerId, isResend) {
      const $link = $('#' + linkId);
      const c = isResend ? ((parseInt($link.data('c'), 10) || 0) + 1) : 0;
      $link.data('c', c);
      armResend(linkId, timerId, RESEND_MAX - c);
    }
    // Resend re-triggers the original send (which regenerates the txnId).
    $('#resend_aadhaar_otp').on('click', function() {
      if ($(this).hasClass('disabled')) return;
      window.__aadhaarResend = true;
      $('#create_abha').trigger('click');
    });
    $('#resend_mobile_otp').on('click', function() {
      if ($(this).hasClass('disabled')) return;
      window.__mobileResend = true;
      $('#send_mobile_otp').trigger('click');
    });
    // Fetch-flow (search by mobile / aadhaar) resend — re-runs the search send-OTP.
    $('#resend_search_otp').on('click', function() {
      if ($(this).hasClass('disabled')) return;
      window.__searchResend = true;
      // Mobile-login: re-request the OTP for the already-selected ABHA (don't
      // restart the whole search), so the resend counter keeps incrementing.
      if (window.MOBILE_LOGIN && window.MOBILE_LOGIN.index) {
        requestMobileLoginOtp(window.MOBILE_LOGIN.index);
      } else {
        $('#searchAadhar').trigger('click');
      }
    });

    /* ── TC-008: Suggested ABHA Address + validation ─────────────────────────── */
    // Validation rules: 8–18 chars, letters+numbers, at most one '.' or '_',
    // must contain a letter, cannot start/end with a separator.
    function checkAbhaAddrRules(v) {
      const dots   = (v.match(/\./g) || []).length;
      const unders = (v.match(/_/g) || []).length;
      return {
        // Rules 1 & 2: 8–18 characters (excludes the @domain suffix).
        len: v.length >= 8 && v.length <= 18,
        // Rules 3 & 5: only letters/numbers, with at most one "." and one "_".
        charset: v.length > 0 && /^[a-zA-Z0-9._]+$/.test(v) && dots <= 1 && unders <= 1,
        // Rule 4: cannot start or end with "." or "_".
        edges: v.length > 0 && /^[a-zA-Z0-9]/.test(v) && /[a-zA-Z0-9]$/.test(v)
      };
    }

    function abhaAddrValid(v) {
      const r = checkAbhaAddrRules(v);
      return r.len && r.charset && r.edges;
    }

    function renderAbhaAddrRules(v) {
      const r = checkAbhaAddrRules(v);
      $('#abhaAddrRules li').each(function() {
        const k = $(this).data('rule');
        $(this).removeClass('ok bad');
        if (v.length === 0) return; // neutral until typing starts
        $(this).addClass(r[k] ? 'ok' : 'bad');
      });
    }
    // Which address is currently chosen (custom takes priority when valid).
    function selectedAbhaAddress() {
      const custom = $('#customAbhaAddress').val().trim().toLowerCase();
      if (custom.length > 0) return abhaAddrValid(custom) ? custom : null;
      const chip = $('#abhaSuggestList .aaddr-chip.selected input').val();
      return chip ? chip.toLowerCase() : null;
    }

    function refreshSetBtn() {
      $('#setAbhaAddrBtn').prop('disabled', !selectedAbhaAddress());
    }

    function openAbhaAddressPicker() {
      $('#abhaAddrMsg').removeClass('err ok').text('');
      $('#customAbhaAddress').val('');
      renderAbhaAddrRules('');
      $('#setAbhaAddrBtn').prop('disabled', true).html('<i class="ri-check-line"></i> Use this address');
      $('#abhaSuggestList').html('<em>Loading suggestions…</em>');
      $('#abhaAddressModal').modal('show');
      loadAbhaSuggestions();
    }
    window.openAbhaAddressPicker = openAbhaAddressPicker;

    function loadAbhaSuggestions() {
      $.ajax({
        url: 'abha_integration.php',
        method: 'POST',
        contentType: 'application/json',
        dataType: 'json',
        data: JSON.stringify({
          page: 'abha_suggestion',
          txnId: window.ABHA_TXN_ID,
          accessToken: window.ABHA_ACCESS_TOKEN
        }),
        success: function(resp) {
          const list = (resp && resp.data && resp.data.abhaAddressList) ||
            (resp && resp.abhaAddressList) || [];
          if (!list.length) {
            $('#abhaSuggestList').html('<em>No suggestions available. Please create a custom address below.</em>');
            return;
          }
          const html = list.map(function(a) {
            const safe = $('<div>').text(lcAddr(a)).html();
            return '<label class="aaddr-chip"><input type="radio" name="abhaSuggest" value="' + safe + '">' + safe + '</label>';
          }).join('');
          $('#abhaSuggestList').html(html);
        },
        error: function(xhr) {
          console.error(xhr.responseText);
          $('#abhaSuggestList').html('<em>Could not load suggestions. Please create a custom address below.</em>');
        }
      });
    }

    // Chip selection (clears custom input so priority is unambiguous)
    $(document).on('change', 'input[name="abhaSuggest"]', function() {
      $('#abhaSuggestList .aaddr-chip').removeClass('selected');
      $(this).closest('.aaddr-chip').addClass('selected');
      $('#customAbhaAddress').val('');
      renderAbhaAddrRules('');
      $('#abhaAddrMsg').removeClass('err ok').text('');
      refreshSetBtn();
    });

    // Custom input typing (deselects chips, live-validates). ABHA addresses are
    // case-insensitive, so keep the field lowercase as the user types.
    $('#customAbhaAddress').on('input', function() {
      // Enforce the rules as the user types/pastes: lowercase, allowed charset
      // only (letters, numbers, "." and "_"), and a hard 18-character cap
      // (excludes the @domain suffix). This guarantees >18 can never be entered.
      var cleaned = $(this).val().toLowerCase().replace(/[^a-z0-9._]/g, '').slice(0, 18);
      if ($(this).val() !== cleaned) $(this).val(cleaned);
      const v = $(this).val().trim();
      if (v.length > 0) {
        $('#abhaSuggestList .aaddr-chip').removeClass('selected');
        $('#abhaSuggestList input[name="abhaSuggest"]').prop('checked', false);
      }
      renderAbhaAddrRules(v);
      if (v.length > 0 && !abhaAddrValid(v)) {
        $('#abhaAddrMsg').removeClass('ok').addClass('err').text('This ABHA address does not meet the rules above.');
      } else {
        $('#abhaAddrMsg').removeClass('err ok').text('');
      }
      refreshSetBtn();
    });

    // "Keep default" = stay on the address ABDM already assigned. Make that explicit
    // on the card instead of leaving it blank: if no preferred address came back,
    // show the derived default (<abha number digits>@<cm id>).
    $('#keepDefaultAddr').on('click', function() {
      var shown = cardVal('abha_address');
      if (!shown) {
        var fallback = lcAddr(window.ABHA_DEFAULT_ADDRESS) ||
          defaultAbhaAddress(cardVal('abha_number'));
        if (fallback) $('#abha_address').html(fallback);
      }
      $('#abhaAddressModal').modal('hide');
    });

    $('#setAbhaAddrBtn').on('click', function() {
      const chosen = selectedAbhaAddress();
      if (!chosen) {
        $('#abhaAddrMsg').removeClass('ok').addClass('err').text('Please pick a suggestion or enter a valid custom address.');
        return;
      }
      const $btn = $(this).prop('disabled', true).html('<i class="ri-loader-4-line"></i> Setting…');
      $.ajax({
        url: 'abha_integration.php',
        method: 'POST',
        contentType: 'application/json',
        dataType: 'json',
        data: JSON.stringify({
          page: 'set_abha_address',
          txnId: window.ABHA_TXN_ID,
          abhaAddress: chosen,
          xToken: window.ABHA_X_TOKEN,
          accessToken: window.ABHA_ACCESS_TOKEN
        }),
        success: function(resp) {
          const ok = resp && (resp.success || resp.code === 'SUCCESS' || resp.preferredAbhaAddress ||
            (resp.data && resp.data.preferredAbhaAddress));
          if (ok) {
            const finalAddr = lcAddr((resp.data && resp.data.preferredAbhaAddress) ||
              resp.preferredAbhaAddress || chosen);
            $('#abha_address').html(finalAddr);
            $('#abhaAddrMsg').removeClass('err').addClass('ok').text('✓ ABHA address set successfully.');
            setTimeout(function() {
              $('#abhaAddressModal').modal('hide');
            }, 900);
          } else {
            $('#abhaAddrMsg').removeClass('ok').addClass('err')
              .text(abdmErrMsg({
                responseText: JSON.stringify(resp)
              }, 'Could not set this ABHA address. It may already be taken — try another.'));
            $btn.prop('disabled', false).html('<i class="ri-check-line"></i> Use this address');
          }
        },
        error: function(xhr) {
          console.error(xhr.responseText);
          $('#abhaAddrMsg').removeClass('ok').addClass('err')
            .text(abdmErrMsg(xhr, 'Could not set this ABHA address. It may already be taken — try another.'));
          $btn.prop('disabled', false).html('<i class="ri-check-line"></i> Use this address');
        }
      });
    });

    /* ── TC-011/017/023/025: Link to Patient / Register in HIMS ──────────────── */
    // Read the currently-displayed ABHA card so link/register works across the
    // create, verify and fetch flows (they all populate the same card fields).
    function cardVal(id) {
      var v = ($('#' + id).text() || '').trim();
      return (v === '---' || v === '-') ? '' : v;
    }
    // TC-002: persist the consent acceptance (compliance audit). Fire-and-forget.
    function storeConsent(purpose) {
      try {
        $.ajax({
          url: 'abha_link_patient.php',
          method: 'POST',
          contentType: 'application/json',
          data: JSON.stringify({
            page: 'store_consent',
            purpose: purpose,
            abha_number: cardVal('abha_number'),
            consent_version: '1.4',
            language: $('#consent_lang').val() || 'en',
            consent_text: ($('#consent_text').text() || '').trim()
          })
        });
      } catch (e) {
        /* non-blocking */
      }
    }
    window.storeConsent = storeConsent;

    // ABHA addresses are case-insensitive — always store & display them lowercase.
    function lcAddr(s) { return (s == null ? '' : String(s)).trim().toLowerCase(); }

    // Every ABHA gets a default address of "<abha number digits>@<cm id>" —
    // e.g. 91-1234-5678-9012 → 91123456789012@sbx. ABDM does not always return it
    // in the create/profile payload (preferredAbhaAddress can be null until the
    // beneficiary picks one), which left the card's ABHA Address blank. Derive it
    // so the card, and everything that reads the card, always has the real address.
    function defaultAbhaAddress(abhaNumber) {
      var digits = String(abhaNumber == null ? '' : abhaNumber).replace(/\D/g, '');
      return digits ? (digits + '@' + ABHA_SEARCH_DOMAIN) : '';
    }

    // The address to show for a profile: whatever ABDM returned, else the default.
    function abhaAddressOf(acc) {
      acc = acc || {};
      var explicit = lcAddr(acc.preferredAddress || acc.preferredAbhaAddress || acc.abhaAddress);
      return explicit || defaultAbhaAddress(acc.ABHANumber || acc.abhaNumber);
    }

    // Show a mobile number with everything but the last 4 digits masked
    // ("8861480470" → "******0470"), the same way ABDM masks account mobiles.
    // Idempotent: a value ABDM already masked is passed through untouched.
    function maskMobile(s) {
      s = String(s == null ? '' : s).trim();
      if (s === '' || s.indexOf('*') !== -1) return s;
      var d = s.replace(/\D/g, '');
      if (d.length <= 4) return s;
      return new Array(d.length - 4 + 1).join('*') + d.slice(-4);
    }
    window.maskMobile = maskMobile;

    // The card deliberately shows a bilingual gender ("Male / ಗಂಡು", from ABDM's
    // localizedDetails). Everything downstream wants the canonical English value:
    // the registration form's <select> only has Male/Female/Other options, so the
    // bilingual string matched nothing and left Gender blank; the ABDM-vs-HIMS
    // comparison likewise flagged it as a difference against a plain "Male".
    function englishGender(v) {
      var s = String(v == null ? '' : v).split('/')[0].trim();
      var map = {
        m: 'Male',
        male: 'Male',
        f: 'Female',
        female: 'Female',
        o: 'Other',
        other: 'Other'
      };
      return map[s.toLowerCase()] || s;
    }

    function readCardProfile() {
      var pincode = cardVal('pin_code');
      var address = cardVal('address');
      // The card shows the PIN inside the address line ("… - 560041"), but the
      // registration form has its own pincode field — strip the appended copy so
      // it is not stored twice in central_opd.
      if (pincode && address) {
        address = address.replace(new RegExp('\\s*-\\s*' + pincode.replace(/[^0-9]/g, '') + '\\s*$'), '').trim();
      }
      return {
        abha: cardVal('abha_number'),
        addr: lcAddr(cardVal('abha_address')),
        name: cardVal('fname'),
        dob: cardVal('dob'),
        sex: englishGender(cardVal('sex')),
        mobile: cardVal('user_mobile'),
        address: address,
        state: cardVal('state_name'),
        district: cardVal('district_name'),
        pincode: pincode
      };
    }

    function normalizeDobForInput(dob) {
      if (!dob) return '';
      if (/^\d{4}-\d{2}-\d{2}$/.test(dob)) return dob; // already ISO
      var m = dob.match(/^(\d{1,2})[-\/](\d{1,2})[-\/](\d{4})$/); // DD-MM-YYYY
      if (m) return m[3] + '-' + ('0' + m[2]).slice(-2) + '-' + ('0' + m[1]).slice(-2);
      return ''; // year-only / unknown
    }

    // Reflect the linked/unlinked state in the badge + Register/Link buttons.
    // `patient` is {source, ref, name} when a HIMS record exists, else null.
    // `unlinkedNote` customises the badge text shown when no record exists.
    function applyAbhaLinkState(patient, unlinkedNote) {
      var $badge = $('#abhaLinkBadge');
      var esc = function(s) {
        return $('<i>').text(s == null ? '' : s).html();
      };
      if (patient) {
        var src = patient.source;
        var ref = (src === 'ipd' ? 'IPD ' : 'OPD ') + patient.ref;
        $badge.removeClass('unlinked').addClass('linked')
          .html('<i class="ri-checkbox-circle-line"></i> Already linked to ' + ref + ' — ' +
            esc(patient.name)).show();
        $('#linkPatientBtn').prop('disabled', true);
        if (src === 'opd') {
          // Already registered → send "Register in HIMS" to the patient's OP
          // Follow-Up page (add a new visit) instead of disabling it.
          window.__linkedOpdNo = patient.ref;
          $('#registerPatientBtn').prop('disabled', false).removeAttr('title')
            .data('mode', 'followup')
            .html('<i class="ri-calendar-check-line"></i> Add Follow-up');
        } else {
          // IPD-linked: OP Follow-Up doesn't apply → keep disabled.
          window.__linkedOpdNo = '';
          $('#registerPatientBtn').prop('disabled', true).data('mode', 'register')
            .attr('title', 'This ABHA is already registered in HIMS')
            .html('<i class="ri-user-add-line"></i> Register in HIMS');
        }
      } else {
        // Not registered → the Register button is the call-to-action (no popup).
        $badge.removeClass('linked').addClass('unlinked')
          .html('<i class="ri-information-line"></i> ' +
            esc(unlinkedNote || 'Not yet linked to a HIMS patient.')).show();
        window.__linkedOpdNo = '';
        $('#linkPatientBtn').prop('disabled', false);
        $('#registerPatientBtn').prop('disabled', false).removeAttr('title')
          .data('mode', 'register')
          .html('<i class="ri-user-add-line"></i> Register in HIMS');
      }
    }

    // Check whether this ABHA is already mapped to a HIMS patient (TC-025) and
    // reflect it in the badge + button states.
    function refreshAbhaLinkState() {
      var p = readCardProfile();
      if (!p.abha) {
        $('#abhaLinkBadge').hide();
        return;
      }
      $.ajax({
        url: 'abha_link_patient.php',
        method: 'POST',
        contentType: 'application/json',
        dataType: 'json',
        data: JSON.stringify({
          page: 'check_duplicate',
          abha_number: p.abha
        }),
        success: function(r) {
          applyAbhaLinkState((r && r.exists && r.patient) ? r.patient : null);
        },
        error: function() {
          $('#abhaLinkBadge').hide();
        }
      });
    }
    window.refreshAbhaLinkState = refreshAbhaLinkState;

    // Follow-up URL for an ALREADY-registered patient, carrying what the ABHA card
    // just verified. opfollowup.php only applies these when abha_src=1 is present,
    // and locks exactly the fields it receives — so everything sent here becomes
    // read-only on the follow-up form.
    //
    // Mobile / Address / State / District / PIN Code all come from the verified
    // profile: the ABHA record is the source of truth on this screen. Anything the
    // card does not have is simply omitted and stays editable at the counter (the
    // demographic fields are mandatory there and are blank on patients registered
    // before central_opd gained state/district/pincode).
    function abhaFollowupUrl(opdNo) {
      var p = readCardProfile();
      var params = new URLSearchParams({
        opd: opdNo,
        abha_src: '1'
      });
      if (p.mobile) params.set('mobile', p.mobile);
      if (p.address) params.set('address', p.address);
      // The verified ABHA number/address from the card — one ABHA number can own
      // several addresses, and the stored HIMS value may be a different one.
      if (p.abha) params.set('abha_number', p.abha);
      if (p.addr) params.set('abha_address', p.addr);
      if (p.state) params.set('state', p.state);
      if (p.district) params.set('district', p.district);
      if (p.pincode) params.set('pincode', p.pincode);
      return 'opfollowup.php?' + params.toString();
    }

    // Build the OPD-registration URL prefilled from the current ABHA card profile.
    function abhaRegistrationUrl() {
      var p = readCardProfile();
      var params = new URLSearchParams({
        abha_prefill: '1'
      });
      if (p.abha) params.set('abha_number', p.abha);
      if (p.addr) params.set('abha_address', p.addr);
      if (p.name) params.set('name', p.name);
      if (p.mobile) params.set('mobile', p.mobile);
      if (p.address) params.set('address', p.address);
      if (p.sex) params.set('gender', p.sex);
      if (p.state) params.set('state', p.state);
      if (p.district) params.set('district', p.district);
      if (p.pincode) params.set('pincode', p.pincode);
      var dob = normalizeDobForInput(p.dob);
      if (dob) params.set('dob', dob);
      return 'opregistration.php?' + params.toString();
    }

    // Open prefilled registration in a new tab; fall back to same-tab navigation
    // if a popup blocker stops window.open (e.g. when called from an async flow).
    function openAbhaRegistration(sameTabFallback) {
      var url = abhaRegistrationUrl();
      var w = window.open(url, '_blank');
      if (!w && sameTabFallback) window.location.href = url;
    }

    // After an ABHA-address verification: check whether that EXACT address is on a
    // central_opd row and reflect it in the badge + buttons. When it isn't found,
    // the "Register in HIMS" button becomes the call-to-action to register as new
    // (no popup) — clicking it opens the prefilled OPD registration.
    function ensureAbhaAddressRegistered(acc) {
      var addr = (acc && (acc.abhaAddress || acc.preferredAbhaAddress)) || '';
      if (!addr) return;
      $.ajax({
        url: 'abha_link_patient.php',
        method: 'POST',
        contentType: 'application/json',
        dataType: 'json',
        data: JSON.stringify({
          page: 'check_abha_address',
          abha_address: addr
        }),
        success: function(r) {
          if (!r || !r.success) return;
          applyAbhaLinkState(
            (r.exists && r.patient) ? r.patient : null,
            'This ABHA address is not registered in HIMS — click "Register in HIMS" to add as new.'
          );
        }
      });
    }

    // An ABHA whose KYC is still PENDING can't be downloaded or registered until
    // the beneficiary completes KYC in their PHR app.
    var KYC_PENDING_MSG = 'Please complete your KYC through the PHR app to download the ABHA Card.';
    function kycPending() {
      return String(window.ABHA_KYC_STATUS || '').toUpperCase() === 'PENDING';
    }

    // Register / Follow-up button.
    //   • EXISTING customer (ABHA already linked to an OPD patient → "Add Follow-up"):
    //     open the ABDM-vs-HIMS comparison auto-loaded with that patient, then let the
    //     operator choose "Go with ABDM" (register from ABHA) or "Go with HIMS"
    //     (continue with the existing record → add follow-up).
    //   • NEW customer (not in HIMS → "Register in HIMS"): register straight from the
    //     ABHA profile — there is no existing record to compare against.
    // "Back to Search" → reload the page for a clean, centered search view.
    $(document).on('click', '#backToSearchBtn', function() {
      window.location.reload();
    });

    $('#registerPatientBtn').on('click', function() {
      if ($(this).prop('disabled')) return;
      if ($(this).data('mode') === 'followup' && window.__linkedOpdNo) {
        // Existing (already-linked) patient. The ABDM-vs-HIMS comparison screen is
        // only for the MOBILE-verification flow; every other flow (Aadhaar / ABHA
        // Number / ABHA Address / freshly created) goes straight to the follow-up.
        if ($('#searchType').val() === 'mobile') {
          openCompareModal(window.__linkedOpdNo);
        } else {
          // Carry the ABHA profile's contact + location details; the follow-up form
          // shows them read-only and saves them onto the patient record.
          window.open(abhaFollowupUrl(window.__linkedOpdNo), '_blank');
        }
        return;
      }
      if (kycPending()) { toast(KYC_PENDING_MSG); return; }
      openAbhaRegistration(false);
    });

    // ── ABDM-vs-HIMS comparison (existing customers) ──────────────────────────
    // Auto-loads the linked HIMS patient (or lets the operator search another),
    // compares core identity, then:
    //   Go with ABDM  → register from the ABHA profile (prefilled OPD registration)
    //   Go with HIMS  → open the patient's OP Follow-Up page (keep HIMS data)
    var __cmpPatient = null;
    var __cmpCache = {};

    // autoOpd (optional): OPD No of the already-linked patient to load & compare at once.
    function openCompareModal(autoOpd) {
      __cmpPatient = null;
      __cmpCache = {};
      $('#compareSearch').val('');
      $('#compareResults').empty();
      $('#compareView').hide();
      $('#compareBody').empty();
      $('#compareMsg').removeClass('err ok').text('');
      $('#compareModal').modal('show');
      if (autoOpd) autoLoadComparePatient(autoOpd);
    }

    // Fetch a single patient's full record by OPD No and render the comparison.
    function autoLoadComparePatient(opd) {
      opd = String(opd);
      $('#compareResults').html('<em>Loading linked patient…</em>');
      $.ajax({
        url: 'abha_link_patient.php',
        method: 'POST',
        contentType: 'application/json',
        dataType: 'json',
        data: JSON.stringify({ page: 'search_patients', q: opd }),
        success: function(r) {
          var pt = (r && r.success && (r.patients || []).filter(function(x) {
            return String(x.opd_no) === opd;
          })[0]) || null;
          if (!pt) {
            $('#compareResults').html('<em>Linked patient ' + opd + ' not found — search below.</em>');
            return;
          }
          __cmpCache[String(pt.opd_no)] = pt;
          $('#compareResults').empty();
          renderCompare(pt);
        },
        error: function() {
          $('#compareResults').html('<em>Could not load linked patient — search below.</em>');
        }
      });
    }

    // Build the side-by-side comparison table for one HIMS patient vs the ABHA card.
    function renderCompare(pt) {
      __cmpPatient = pt;
      var p = readCardProfile();
      var esc = function(s) {
        s = (s == null) ? '' : String(s).trim();
        return $('<i>').text(s === '' ? '—' : s).html();
      };
      var fields = [
        { label: 'Name',    abdm: p.name,        hims: pt.name },
        { label: 'DOB',     abdm: cmpDmy(p.dob), hims: cmpDmy(pt.dob) },
        { label: 'Gender',  abdm: p.sex,         hims: pt.gender },
        { label: 'Mobile',  abdm: p.mobile,      hims: pt.mobile },
        { label: 'Address', abdm: p.address,     hims: pt.address }
      ];
      var body = fields.map(function(f) {
        var a = (f.abdm == null ? '' : String(f.abdm)).trim();
        var h = (f.hims == null ? '' : String(f.hims)).trim();
        var diff = a && h && a.toLowerCase() !== h.toLowerCase();
        var cls = diff ? ' class="cmp-diff"' : '';
        return '<tr><th>' + f.label + '</th><td' + cls + '>' + esc(a) + '</td><td' + cls + '>' + esc(h) + '</td></tr>';
      }).join('');
      $('#compareBody').html(body);
      $('#compareView').show();
      $('#compareMsg').removeClass('err').addClass('ok')
        .text('Existing HIMS patient: OPD ' + pt.opd_no + ' — ' + pt.name);
    }

    // Present a DOB as dd/mm/yyyy regardless of the stored format (ISO or DD-MM-YYYY).
    function cmpDmy(v) {
      var iso = normalizeDobForInput(v);
      if (iso) { var m = iso.split('-'); return m[2] + '/' + m[1] + '/' + m[0]; }
      return v || '';
    }

    function doCompareSearch() {
      var q = $('#compareSearch').val().trim();
      if (q.length < 2) {
        $('#compareMsg').removeClass('ok').addClass('err').text('Enter at least 2 characters.');
        return;
      }
      $('#compareMsg').removeClass('err ok').text('');
      $('#compareResults').html('<em>Searching…</em>');
      $.ajax({
        url: 'abha_link_patient.php',
        method: 'POST',
        contentType: 'application/json',
        dataType: 'json',
        data: JSON.stringify({ page: 'search_patients', q: q }),
        success: function(r) {
          if (!r || !r.success) {
            $('#compareResults').html('<em>' + ((r && r.error) || 'Search failed') + '</em>');
            return;
          }
          if (!r.patients.length) {
            $('#compareResults').html('<em>No patients found — use “Register as New”.</em>');
            return;
          }
          var esc = function(s) { return $('<i>').text(s == null ? '' : s).html(); };
          __cmpCache = {};
          var html = r.patients.map(function(pt) {
            __cmpCache[String(pt.opd_no)] = pt;
            return '<div class="link-row"><div class="lp-info"><b>' + esc(pt.name) + '</b> (' + esc(pt.gender) + ', ' + esc(pt.age) + ')' +
              '<div class="lp-sub">OPD ' + esc(pt.opd_no) + ' · UHID ' + esc(pt.uhid) + ' · ' + esc(pt.mobile) + '</div></div>' +
              '<button type="button" class="btn-cmp-pick" data-opd="' + esc(pt.opd_no) + '">Compare</button></div>';
          }).join('');
          $('#compareResults').html(html);
        },
        error: function() { $('#compareResults').html('<em>Search failed.</em>'); }
      });
    }
    $('#compareSearchBtn').on('click', doCompareSearch);
    $('#compareSearch').on('keydown', function(e) {
      if (e.key === 'Enter') { e.preventDefault(); doCompareSearch(); }
    });

    $(document).on('click', '.btn-cmp-pick', function() {
      var opd = String($(this).data('opd'));
      var pt = __cmpCache[opd];
      if (!pt) return;
      $('.btn-cmp-pick').removeClass('active');
      $(this).addClass('active');
      renderCompare(pt);
    });

    // The patient already exists → both choices open the follow-up page (never a new
    // registration). They differ only in whose contact details get carried in:
    //   Go with ABDM → follow-up with Mobile/Address refreshed from the ABHA profile
    //   Go with HIMS → follow-up with the existing HIMS Mobile/Address kept
    $('#goWithAbdmBtn').on('click', function() {
      if (!__cmpPatient) { toast('Select a patient to compare first.'); return; }
      $('#compareModal').modal('hide');
      window.open(abhaFollowupUrl(__cmpPatient.opd_no), '_blank');
    });
    $('#goWithHimsBtn').on('click', function() {
      if (!__cmpPatient) { toast('Select a patient to compare first.'); return; }
      $('#compareModal').modal('hide');
      window.open('opfollowup.php?opd=' + encodeURIComponent(__cmpPatient.opd_no), '_blank');
    });

    // Link to Patient → open search modal.
    $('#linkPatientBtn').on('click', function() {
      if ($(this).prop('disabled')) return;
      $('#linkPatientResults').empty();
      $('#linkPatientMsg').removeClass('err ok').text('');
      $('#linkPatientSearch').val('');
      $('#linkPatientModal').modal('show');
    });

    function doLinkSearch() {
      var q = $('#linkPatientSearch').val().trim();
      if (q.length < 2) {
        $('#linkPatientMsg').removeClass('ok').addClass('err').text('Enter at least 2 characters.');
        return;
      }
      $('#linkPatientMsg').removeClass('err ok').text('');
      $('#linkPatientResults').html('<em>Searching…</em>');
      $.ajax({
        url: 'abha_link_patient.php',
        method: 'POST',
        contentType: 'application/json',
        dataType: 'json',
        data: JSON.stringify({
          page: 'search_patients',
          q: q
        }),
        success: function(r) {
          if (!r || !r.success) {
            $('#linkPatientResults').html('<em>' + ((r && r.error) || 'Search failed') + '</em>');
            return;
          }
          if (!r.patients.length) {
            $('#linkPatientResults').html('<em>No patients found.</em>');
            return;
          }
          var esc = function(s) {
            return $('<i>').text(s == null ? '' : s).html();
          };
          var html = r.patients.map(function(pt) {
            var hasAbha = (pt.abha_number || '').replace(/\D/g, '').length > 0;
            var linked = hasAbha ? '<div class="lp-linked">Already has an ABHA linked</div>' : '';
            return '<div class="link-row"><div class="lp-info"><b>' + esc(pt.name) + '</b> (' + esc(pt.gender) + ', ' + esc(pt.age) + ')' +
              '<div class="lp-sub">OPD ' + esc(pt.opd_no) + ' · UHID ' + esc(pt.uhid) + ' · ' + esc(pt.mobile) + '</div>' + linked + '</div>' +
              '<button type="button" class="btn-link-pick" data-opd="' + esc(pt.opd_no) + '">Link</button></div>';
          }).join('');
          $('#linkPatientResults').html(html);
        },
        error: function() {
          $('#linkPatientResults').html('<em>Search failed.</em>');
        }
      });
    }
    $('#linkPatientSearchBtn').on('click', doLinkSearch);
    $('#linkPatientSearch').on('keydown', function(e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        doLinkSearch();
      }
    });

    $(document).on('click', '.btn-link-pick', function() {
      var $b = $(this).prop('disabled', true).text('Linking…');
      var opd = String($(this).data('opd'));
      var p = readCardProfile();
      $.ajax({
        url: 'abha_link_patient.php',
        method: 'POST',
        contentType: 'application/json',
        dataType: 'json',
        data: JSON.stringify({
          page: 'link_patient',
          opd_no: opd,
          abha_number: p.abha,
          abha_address: p.addr
        }),
        success: function(r) {
          if (r && r.success) {
            $('#linkPatientMsg').removeClass('err').addClass('ok').text('✓ ' + r.message);
            setTimeout(function() {
              $('#linkPatientModal').modal('hide');
              refreshAbhaLinkState();
            }, 900);
          } else {
            $('#linkPatientMsg').removeClass('ok').addClass('err').text((r && r.error) || 'Link failed.');
            $b.prop('disabled', false).text('Link');
          }
        },
        error: function(xhr) {
          $('#linkPatientMsg').removeClass('ok').addClass('err').text(abdmErrMsg(xhr, 'Link failed.'));
          $b.prop('disabled', false).text('Link');
        }
      });
    });

    // Pull a human-readable message out of an ABDM/gateway error response so a
    // wrong OTP (which comes back as an HTTP error) shows a real message.
    function abdmErrMsg(xhr, fallback) {
      try {
        const d = JSON.parse((xhr && xhr.responseText) || '{}');
        const m = deepErr(d); // digs into the (possibly double-nested) ABDM error
        if (m) return (typeof m === 'object') ? JSON.stringify(m) : m;
      } catch (e) {
        if (xhr && xhr.responseText) return String(xhr.responseText).slice(0, 250);
      }
      return fallback || 'Request failed';
    }

    // No-ABHA-found → show the message and offer to start ABHA creation
    // (TC-016 mobile, TC-022 Aadhaar).
    function offerCreateAbha(message) {
      toast(message, 'info', {
        label: 'Create ABHA',
        onClick: function() {
          $('#enterotp').modal('show');
        }
      });
    }

    function hideEnterOtpModal() {
      const $modal = $('#enterotp');
      $modal.modal('hide'); // trigger Bootstrap hide
      $modal.removeClass('show'); // remove manual "show" class
      $modal.css('display', 'none'); // hide inline style
      $('body').removeClass('modal-open'); // remove scroll lock
      $('.modal-backdrop').remove(); // remove overlay if stuck
    }
    // Screen 2: the Aadhaar OTP is validated by creating/fetching the ABHA
    // (create-by-aadhaar). On success we branch on the registered-vs-entered mobile.
    $('#verify_aadhaar_otp').on('click', function(e) {
      e.preventDefault();

      let otp = "";
      for (let i = 1; i <= 6; i++) {
        otp += document.getElementById("otp" + i).value;
      }
      if (!/^\d{6}$/.test(otp)) {
        toast('Enter valid 6-digit Aadhaar OTP');
        return;
      }

      const txnId = $('#txnId').val();
      const mobile = (window.ABHA_ENTERED_MOBILE || $('#mobile').val() || '').trim();

      showLoader();
      $.ajax({
        url: 'abha_integration.php',
        method: 'POST',
        contentType: 'application/json',
        dataType: 'json',
        data: JSON.stringify({
          page: 'create_abha',
          otp: otp,
          mobile: mobile,
          txnId: txnId,
          accessToken: window.ABHA_ACCESS_TOKEN
        }),
        success: function(response) {
          if (response && response.success) {
            handleAbhaCreated(response.data); // populate card + branch on mobile
          } else {
            toast(friendlyOtpErr(deepErr(response), 'ABHA creation failed. Please re-check the Aadhaar OTP and try again.'));
            abhaBackToAadhaarOtp(); // wrong Aadhaar OTP → back to this step
            hideLoader();
          }
        },
        error: function(xhr) {
          console.error(xhr.responseText);
          toast(friendlyOtpErr(abdmErrMsg(xhr, ''), 'Incorrect OTP. Please re-check the Aadhaar OTP and try again.'));
          abhaBackToAadhaarOtp();
          hideLoader();
        }
      });
    });

    $('#send_mobile_otp').on('click', function(e) {
      e.preventDefault();
      const mobile = ($('#mobile').val() || '').trim();

      if (!/^[6-9]\d{9}$/.test(mobile)) {
        toast('Enter valid 10-digit mobile number');
        return;
      }

      showLoader();
      // A token can be missing here because the session one expired mid-flow —
      // mint a fresh one rather than making the operator start over.
      withAbdmToken(function(accessToken) {
      $.ajax({
        url: 'abha_integration.php',
        method: 'POST',
        contentType: 'application/json',
        dataType: 'json',
        // Enrollment mobile-verify: sends OTP to the NEW mobile (not the registered
        // one), using the enrollment txnId from ABHA creation.
        data: JSON.stringify({
          page: 'mobile_update_send_otp',
          txnId: window.ABHA_TXN_ID,
          mobile: mobile,
          accessToken: accessToken
        }),
        success: function(response) {
          if (response && response.success && response.data && response.data.txnId) {
            $('#mobileTxnId').val(response.data.txnId);
            setStep(4);
            onOtpSent('resend_mobile_otp', 'resend_mobile_timer', window.__mobileResend === true);
            window.__mobileResend = false;
            toast(response.data.message || ('OTP sent to ' + maskMobile(mobile)));
          } else {
            toast('Unable to send mobile OTP: ' + abdmErrMsg({
              responseText: JSON.stringify(response)
            }, 'Please try again.'));
          }
          hideLoader();
        },
        error: function(xhr) {
          console.error(xhr.responseText);
          toast('Send OTP error: ' + abdmErrMsg(xhr, 'Please try again.'));
          hideLoader();
        }
      });
      });
    });

    $('#verify_mobile_otp').on('click', function(e) {
      e.preventDefault();
      const accessToken = window.ABHA_ACCESS_TOKEN;
      const txnId = $('#mobileTxnId').val().trim();
      let mobileOtp = '';
      for (let i = 1; i <= 6; i++) {
        mobileOtp += document.getElementById('mobile_otp' + i).value;
      }

      if (!txnId) {
        toast('Please send mobile OTP first');
        return;
      }
      if (!/^\d{6}$/.test(mobileOtp)) {
        toast('Enter valid 6-digit mobile OTP');
        return;
      }

      showLoader();
      $.ajax({
        url: 'abha_integration.php',
        method: 'POST',
        contentType: 'application/json',
        dataType: 'json',
        // Verify the OTP sent to the new mobile — this links/updates it on the ABHA.
        data: JSON.stringify({
          page: 'mobile_update_verify_otp',
          txnId: txnId,
          otp: mobileOtp,
          accessToken: accessToken
        }),
        success: function(response) {
          var _d = response && response.data;
          if (_d && String(_d.authResult).toLowerCase() === 'failed') {
            toast(friendlyOtpErr(_d.message, 'The OTP you entered has expired or is incorrect.'));
            hideLoader();
            return;
          }
          if (response && response.success) {
            window.MOBILE_OTP_VERIFIED = true;
            $('#user_mobile').html(($('#mobile').val() || '').trim());
            finishAbhaCreated(); // mobile updated → show card + address picker
          } else {
            toast('Mobile OTP verification failed: ' +
              abdmErrMsg({
                responseText: JSON.stringify(response)
              }, 'Incorrect OTP. Please try again.'));
            hideLoader();
          }
        },
        error: function(xhr) {
          console.error(xhr.responseText);
          toast('Mobile OTP error: ' + abdmErrMsg(xhr, 'Incorrect OTP. Please try again.'));
          hideLoader();
        }
      });
    });

    // Fill the ABHA card from a created/fetched profile. `mobile` is the value to
    // show as communication mobile (entered mobile once updated, else profile's).
    // Extract Address / State / District / Pin Code from an ABHA profile robustly.
    // ABDM returns these flat (address, stateName, districtName, pinCode) OR nested
    // under an `address` object OR under alternate keys — cover them all.
    function addrParts(acc) {
      acc = acc || {};
      var a = acc.address, line;
      var pin = String(acc.pinCode || acc.pincode || acc.pin ||
        (a && typeof a === 'object' && (a.pinCode || a.pincode)) || '').trim();
      if (a && typeof a === 'object') {
        // Pin is appended below (as "… - 560001"), so it is left out of this join.
        line = [a.line, a.house, a.street, a.landmark, a.locality,
                a.village || a.villageName, a.subDistrict || a.subDistrictName,
                a.district || a.districtName, a.state || a.stateName
               ].filter(Boolean).join(', ');
      } else {
        line = a || acc.addressLine || acc.kycAddress || '';
      }
      line = String(line).trim().replace(/[,\s]+$/, '');
      // ABDM returns the PIN as its own field, so the card's address line read as an
      // incomplete postal address. Append it in the usual Indian format — unless the
      // line already carries it (some sources embed it in the KYC address string).
      if (pin) {
        if (!line) {
          line = pin;
        } else if (line.indexOf(pin) === -1) {
          line = line + ' - ' + pin;
        }
      }
      return {
        address: line || '-',
        state: acc.stateName || acc.state || (a && (a.stateName || a.state)) || '-',
        district: acc.districtName || acc.district || acc.subDistrictName ||
                  (a && (a.districtName || a.district)) || '-',
        pincode: pin || '-'
      };
    }

    function populateCreatedCard(acc, mobile) {
      if (acc && acc.kycStatus) window.ABHA_KYC_STATUS = String(acc.kycStatus).toUpperCase();
      $('#updateMobileBtn').hide(); // not the mobile-verification flow
      $("#abha_number").html(acc.ABHANumber || acc.abhaNumber || '-');
      $("#abha_address").html(abhaAddressOf(acc) || '-');
      generateQR(acc.ABHANumber || acc.abhaNumber || '');
      $("#aadhar").html($('#aadhaar').val());
      let fname = [acc.firstName, acc.middleName, acc.lastName].filter(Boolean).join(" ");
      $("#fname").html(fname || acc.name || '-');
      $("#dob").html(formatDobDMY(acc));
      $("#image").attr("src",
        acc.photo ? "data:image/png;base64," + acc.photo :
        (acc.profilePhoto ? "data:image/png;base64," + acc.profilePhoto : "assets/no-photo.png"));
      $("#user_mobile").html(mobile || acc.mobile || '-');
      var _ap = addrParts(acc);
      $("#address").html(_ap.address);
      $("#state_name").html(_ap.state);
      $("#district_name").html(_ap.district);
      $("#pin_code").html(_ap.pincode);
      const genderMap = {
        M: 'Male',
        F: 'Female'
      };
      $("#sex").html(genderMap[acc.gender] || acc.gender || 'Other');
    }

    // Called after create-by-aadhaar succeeds (Aadhaar OTP is thereby validated).
    // Populates the card, then — per the "only when it differs" rule — either goes
    // to the mobile-verify/update page (Screen 3) or finishes.
    function handleAbhaCreated(data) {
      const acc = data && data.ABHAProfile;
      if (!acc) {
        toast('No ABHA account found in response');
        hideLoader();
        return;
      }
      window.ABHA_X_TOKEN = data.xToken;
      window.ABHA_TXN_ID = $('#txnId').val();
      // ABDM may not return a preferred address yet — fall back to the default it
      // assigns every ABHA (<number digits>@<cm id>).
      window.ABHA_DEFAULT_ADDRESS = abhaAddressOf(acc);

      // Whether the patient already had an ABHA (isNew=false) or one was created.
      const isNew = (data.isNew !== undefined) ? data.isNew :
        (data.new !== undefined) ? data.new : null;
      window.ABHA_IS_NEW = isNew;

      const entered = (window.ABHA_ENTERED_MOBILE || '').trim();
      const profileMobile = (acc.mobile || '').toString().trim();

      populateCreatedCard(acc, entered || profileMobile);

      // Is the entered communication mobile the Aadhaar-linked one? ABDM returns the
      // account mobile masked (e.g. ******0903) or null, so an exact compare is
      // unreliable — match by the last 4 digits. If it does NOT match (different
      // number, or no account mobile returned), the communication mobile is NOT
      // Aadhaar-linked and must be verified via its own OTP before the profile shows.
      const last4 = function(s) { return String(s || '').replace(/\D/g, '').slice(-4); };
      const mobileLinked = entered.length >= 4 && profileMobile.length >= 4 &&
        last4(entered) === last4(profileMobile);

      if (entered && !mobileLinked) {
        // Communication mobile not Aadhaar-linked → verify it via OTP, then update.
        $('#mobile').val(entered); // hidden field keeps the full number for the API
        $('#mobile_update_target').text(maskMobile(entered));
        $('#mobile_diff_note').text(
          'This ABHA' + (isNew === false ? ' already exists and its' : '') +
          ' registered mobile (' + (maskMobile(profileMobile) || 'not set') + ') differs from the entered mobile (' +
          maskMobile(entered) + '). Send an OTP to verify and update it.');
        for (let i = 1; i <= 6; i++) {
          $('#mobile_otp' + i).prop('readonly', false).val('');
        }
        $('#send_mobile_otp').prop('disabled', false);
        $('#verify_mobile_otp').prop('disabled', false).html('<i class="ri-check-line"></i> Verify &amp; Update Mobile');
        abhaShowStep(3);
        hideLoader();
      } else {
        finishAbhaCreated();
      }
    }

    // Wrap up: reveal the ABHA card, refresh link state, offer the address picker.
    function finishAbhaCreated() {
      hideEnterOtpModal();
      $('#aabha_card_section').show(); $('#abhaSearchPanel').hide(); $('.abha-workspace').addClass('full-card');
      if (window.refreshAbhaLinkState) refreshAbhaLinkState();
      openAbhaAddressPicker();
      hideLoader();
    }
    $('#verify_otp1').on('click', function(e) {
      e.preventDefault();
      showLoader(); // start loader at beginning
      const txnId = $('#txnId1').val();
      const searchType = $('#searchType').val().trim();
      const verifyMode = $('#abhaNumberMode').val();
      let otp = "";
      for (let i = 1; i <= 6; i++) {
        otp += document.getElementById("otp_" + i).value;
      }

      // Mobile-login flow: verify the selected ABHA's login OTP, then show the card.
      if (window.MOBILE_LOGIN) {
        verifyMobileLoginOtp(window.MOBILE_LOGIN.otpTxnId || txnId, otp);
        return;
      }

      // The OTP is only valid for a few minutes but the session token can lapse
      // first — re-mint it rather than discarding a still-valid OTP.
      withAbdmToken(function(accessToken) {
      $.ajax({
        url: 'abha_integration.php',
        method: 'POST',
        contentType: 'application/json', // <-- very important
        dataType: 'json',
        data: JSON.stringify({
          page: 'verify_otp',
          otp: otp,
          txnId: txnId,
          searchType,
          verifyMode,
          accessToken: accessToken // variable containing token string
        }),
        success: function(response) {
          // ABDM can return HTTP success:true while the OTP auth itself failed
          // (data.authResult = "failed"). Treat that as an OTP error, not success.
          var _d = response && response.data;
          if (_d && String(_d.authResult).toLowerCase() === 'failed') {
            hideLoader();
            toast(friendlyOtpErr(_d.message, 'The OTP you entered has expired or is incorrect.'));
            return;
          }
          if (response.success) {
            if (searchType === "mobile") {

              const abhaList = response.data.accounts; // expected: array of ABHA numbers

              if (!abhaList || abhaList.length === 0) {
                hideLoader();
                offerCreateAbha('ABHA Number not found.\nWe did not find any ABHA linked to this mobile number.');
                return;
              }
              let html = '';

              // Populate m
              // hideEnterOtpModal();
              abhaList.forEach(function(acc, index) {
                const photoSrc = acc.profilePhoto ? `data:image/jpeg;base64,${acc.profilePhoto}` : "assets/no-photo.png"; // fallback image
                html += `
                    <tr>
            <td class="text-center">
                <input type="radio" name="selectedAbha" value="${acc.ABHANumber}">
            </td>

            <td class="text-center">
                <img src="${photoSrc}" class="img-thumbnail" width="60" height="60">
            </td>

            <td>${acc.ABHANumber}</td>
            <td>${acc.name}</td>
            <td>${lcAddr(acc.preferredAbhaAddress) || '-'}</td>
            <td>${acc.dob || '-'}</td>
            <td>${acc.gender === 'M' ? 'Male' : acc.gender === 'F' ? 'Female' : 'Other'}</td>
        </tr>
                `;
              });


              $("#selectAbhaModal").modal("show");
              $("#abhaTableBody").html(html);
              window.currentTxnId = response.data.txnId;
              window.jwtToken = response.data.token;
              hideLoader();
              $('#abha_verify1').css('display', 'none');
              return; // STOP → user must choose ABHA before next step
            }

            if (searchType === "abhaAddress") {
              const xToken = (response.data.tokens && response.data.tokens.token) ||
                response.data.token;
              window.ABHA_X_TOKEN = xToken;

              const acc = response.data.profile; // set if same-session abha-profile fetch succeeded

              if (acc) {
                // Full profile returned in same session — populate card directly.
                // Mark the card source so Download uses the PHR card endpoint.
                window.ABHA_CARD_SOURCE = 'phr';
                // KYC status (PENDING blocks Download / Register — see kycPending()).
                window.ABHA_KYC_STATUS = String((acc && acc.kycStatus) ||
                  (response.data.users && response.data.users[0] && response.data.users[0].kycStatus) || '').toUpperCase();
                storeVerifiedAbhaAddress(acc); // accumulate this number's address(es)
                populateAbhaCard(acc);
                $('#abha_verify1').hide();
                $('.modal-backdrop').remove();
                hideEnterOtpModal();
                $('#aabha_card_section').show(); $('#abhaSearchPanel').hide(); $('.abha-workspace').addClass('full-card');
                hideLoader();
                // Badge + Register button reflect whether this ABHA ADDRESS exists in
                // central_opd (not the number) — drives register-as-new when absent.
                ensureAbhaAddressRegistered(acc);
                return;
              }

              // Same-session profile fetch failed (PHR token type rejected by ABDM).
              // Trigger ABHA Number OTP for full profile using abhaNumber from users[0].
              const users = response.data.users;
              const user = users && users[0];
              if (!user) {
                toast('No ABHA profile found for this address');
                hideLoader();
                return;
              }

              window.ABHA_ADDRESS_PROFILE_MODE = true;

              // Prepare second OTP modal
              $('#profileOtpMsg').text(
                'An OTP has been sent to your registered mobile to load your complete ABHA profile.'
              );
              for (let i = 1; i <= 6; i++) {
                const el = document.getElementById('profile_otp_' + i);
                if (el) {
                  el.value = '';
                  el.readOnly = false;
                }
              }

              // Silently send ABHA Number OTP using abhaNumber from users[0]
              $.ajax({
                url: 'abha_integration.php',
                method: 'POST',
                contentType: 'application/json',
                dataType: 'json',
                data: JSON.stringify({
                  page: 'send_otp',
                  aadhaar: user.abhaNumber,
                  searchType: 'abhaNumber',
                  accessToken: window.ABHA_ACCESS_TOKEN || ''
                }),
                success: function(otpRes) {
                  hideLoader();
                  if (!otpRes.success) {
                    window.ABHA_ADDRESS_PROFILE_MODE = false;
                    showAbhaAddressLimitedProfile(user, xToken);
                    return;
                  }
                  window.addressProfileTxnId = otpRes.data && otpRes.data.txnId;
                  $('#abha_verify1').hide();
                  $('.modal-backdrop').remove();
                  hideEnterOtpModal();
                  $('#profileOtpModal').modal('show');
                },
                error: function() {
                  hideLoader();
                  window.ABHA_ADDRESS_PROFILE_MODE = false;
                  showAbhaAddressLimitedProfile(user, xToken);
                }
              });
              return;
            }
            if (searchType === "abhaNumber") {
              fetchAbhaProfile(response.data.token);
              return;
            }


            fetchAbhaProfile(response.data.token);
          } else {
            hideLoader();
            showVerifyOtpFailure(response, searchType);
          }
        },
        error: function(xhr) {
          console.error(xhr.responseText);
          hideLoader();
          // The same {error:{error:{code,message}}} payload arrives on a non-200 —
          // read it rather than blaming the network.
          var body = xhr.responseJSON;
          if (!body) {
            try {
              body = JSON.parse(xhr.responseText || '{}');
            } catch (e) {
              body = null;
            }
          }
          if (body) {
            showVerifyOtpFailure(body, searchType);
          } else {
            toast('Network error during OTP verification. Please try again.');
          }
        }
      });
      });
    });
    $('#download_abha').on('click', function(e) {
      e.preventDefault();
      if (kycPending()) { toast(KYC_PENDING_MSG); return; }

      const accessToken = window.ABHA_ACCESS_TOKEN;
      const xToken = window.ABHA_X_TOKEN;

      // Create a form dynamically
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = 'abha_integration.php';
      form.style.display = 'none'; // hide it

      // ABHA-address (PHR) cards use a different ABDM endpoint than enrollment cards.
      const downloadPage = window.ABHA_CARD_SOURCE === 'phr' ?
        'download_abha_phr' :
        'download_abha';

      // Add hidden inputs
      const inputs = {
        page: downloadPage,
        accessToken: accessToken,
        xToken: xToken
      };

      for (const name in inputs) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        input.value = inputs[name];
        form.appendChild(input);
      }

      document.body.appendChild(form);
      form.submit();
      document.body.removeChild(form);
    });


    $(document).on("click", "#confirmAbhaSelection", function() {
      const accessToken = window.ABHA_ACCESS_TOKEN;
      const selectedAbha = $("input[name='selectedAbha']:checked").val();

      if (!selectedAbha) {
        toast("Please select an ABHA account.");
        return;
      }

      // Mobile-login flow: the selected value is the ABHA index → request its OTP.
      if (window.MOBILE_LOGIN) {
        requestMobileLoginOtp(selectedAbha);
        return;
      }

      showLoader();

      $.ajax({
        url: 'abha_integration.php',
        method: 'POST',
        contentType: 'application/json',
        dataType: 'json',
        data: JSON.stringify({
          page: 'send_profile_otp',
          txnId: window.currentTxnId,
          abhaNumber: selectedAbha,
          jwtToken: window.jwtToken,
          accessToken: accessToken
        }),
        success: function(response) {
          if (!response.success) {
            toast("Unable to verify ABHA selection");
            hideLoader();
            return;
          }
          window.selectedAbhaNumber = selectedAbha;
          $("#selectAbhaModal").modal("hide");
          $('.modal-backdrop').remove();

          // verify/user returns token directly — no OTP needed
          if (response.data && response.data.token) {
            window.ABHA_X_TOKEN = response.data.token;
            fetchAbhaProfile(response.data.token);
            return;
          }

          // Fallback: ABDM returned a txnId → show OTP modal for second factor
          if (response.data && response.data.txnId) {
            window.profileTxnId = response.data.txnId;
          }
          $("#profileOtpModal").modal("show");
          hideLoader();
        }
      });
    });

    $(document).on("click", "#verifyProfileOtpBtn", function() {
      let profileOtp = "";
      for (let i = 1; i <= 6; i++) {
        profileOtp += document.getElementById("profile_otp_" + i).value;
      }
      if (profileOtp.length !== 6) {
        toast("Please enter 6-digit OTP");
        return;
      }
      showLoader();

      // ABHA Address flow: second OTP verifies ABHA Number for full profile token
      if (window.ABHA_ADDRESS_PROFILE_MODE) {
        $.ajax({
          url: 'abha_integration.php',
          method: 'POST',
          contentType: 'application/json',
          dataType: 'json',
          data: JSON.stringify({
            page: 'verify_otp',
            txnId: window.addressProfileTxnId,
            otp: profileOtp,
            searchType: 'abhaNumber',
            accessToken: window.ABHA_ACCESS_TOKEN || ''
          }),
          success: function(response) {
            window.ABHA_ADDRESS_PROFILE_MODE = false;
            var _d = response && response.data;
            if (_d && String(_d.authResult).toLowerCase() === 'failed') {
              toast(friendlyOtpErr(_d.message, 'The OTP you entered has expired or is incorrect.'));
              hideLoader();
              return;
            }
            if (!response.success || !response.data || !response.data.token) {
              toast("OTP verification failed. Please try again.");
              hideLoader();
              return;
            }
            $("#profileOtpModal").modal("hide");
            $('.modal-backdrop').remove();
            fetchAbhaProfile(response.data.token);
          },
          error: function(xhr) {
            console.error(xhr.responseText);
            window.ABHA_ADDRESS_PROFILE_MODE = false;
            toast('OTP error: ' + abdmErrMsg(xhr, 'Incorrect OTP. Please try again.'));
            hideLoader();
          }
        });
        return;
      }

      // Default flow: verify second OTP for "select ABHA" modal
      $.ajax({
        url: 'abha_integration.php',
        method: 'POST',
        contentType: 'application/json',
        dataType: 'json',
        data: JSON.stringify({
          page: 'verify_profile_otp',
          txnId: window.profileTxnId || window.currentTxnId,
          otp: profileOtp,
          accessToken: window.ABHA_ACCESS_TOKEN
        }),
        success: function(response) {
          if (!response.success) {
            toast("Unable to verify profile OTP");
            hideLoader();
            return;
          }
          $("#profileOtpModal").modal("hide");
          $('.modal-backdrop').remove();
          fetchAbhaProfile(response.data.token);
        },
        error: function(xhr) {
          console.error(xhr.responseText);
          hideLoader();
        }
      });
    });

    // Normalise an ABHA DOB (day/month/year parts, or dob/dateOfBirth in either
    // yyyy-mm-dd or d-m-yyyy form) to dd/mm/yyyy for the card.
    function formatDobDMY(acc) {
      if (!acc) return '-';
      var d = acc.dayOfBirth,
        m = acc.monthOfBirth,
        y = acc.yearOfBirth;
      if (y && m && d) {
        return ('0' + d).slice(-2) + '/' + ('0' + m).slice(-2) + '/' + y;
      }
      var raw = (acc.dob || acc.dateOfBirth || '').toString().trim();
      if (!raw) return '-';
      var iso = raw.match(/^(\d{4})[-\/](\d{1,2})[-\/](\d{1,2})$/); // yyyy-mm-dd
      if (iso) return ('0' + iso[3]).slice(-2) + '/' + ('0' + iso[2]).slice(-2) + '/' + iso[1];
      var dmy = raw.match(/^(\d{1,2})[-\/](\d{1,2})[-\/](\d{4})$/); // d-m-yyyy
      if (dmy) return ('0' + dmy[1]).slice(-2) + '/' + ('0' + dmy[2]).slice(-2) + '/' + dmy[3];
      return raw; // year-only or unknown → show as-is
    }

    function generateQR(text) {
      const wrap = document.getElementById('qr-code-wrap');
      if (!wrap) return;
      wrap.innerHTML = '';
      if (text && typeof QRCode !== 'undefined') {
        new QRCode(wrap, {
          text: text,
          width: 90,
          height: 90,
          colorDark: '#003087',
          colorLight: '#ffffff',
          correctLevel: QRCode.CorrectLevel.M
        });
      }
    }

    // Persist every ABHA address we learn for a number. One ABHA number can own
    // several addresses (name@sbx, name@abdm, …), so we INSERT (accumulate) rather
    // than overwrite the single central_opd.abha_address column.
    function storeVerifiedAbhaAddress(acc) {
      if (!acc) return;
      const abhaNumber = acc.abhaNumber || acc.ABHANumber || '';
      const preferred = lcAddr(acc.abhaAddress || acc.preferredAbhaAddress);
      // Some ABDM responses also carry a list of every address for this number.
      let addresses = acc.phrAddress || acc.phrAddresses || acc.abhaAddresses || [];
      if (!Array.isArray(addresses)) addresses = [addresses];
      if (preferred) addresses = addresses.concat(preferred);
      addresses = addresses.map(lcAddr).filter(Boolean);
      if (!abhaNumber || addresses.length === 0) return;
      const fullName = acc.fullName || [acc.firstName, acc.middleName, acc.lastName].filter(Boolean).join(' ');
      $.ajax({
        url: 'abha_link_patient.php',
        method: 'POST',
        contentType: 'application/json',
        dataType: 'json',
        data: JSON.stringify({
          page: 'store_abha_address',
          abha_number: abhaNumber,
          abha_addresses: addresses,
          preferred_abha_address: preferred,
          full_name: fullName
        })
      });
    }

    // The PHR/login JWT embeds the beneficiary mobile — use it when the profile
    // body itself doesn't carry a mobile field (abha-address verify response).
    function mobileFromToken(token) {
      try {
        var parts = (token || '').split('.');
        var pl = JSON.parse(atob(parts[1].replace(/-/g, '+').replace(/_/g, '/')));
        return pl.mobile || '';
      } catch (e) { return ''; }
    }

    function populateAbhaCard(acc) {
      window.ABHA_X_TOKEN = window.ABHA_X_TOKEN || '';
      if (acc && acc.kycStatus) window.ABHA_KYC_STATUS = String(acc.kycStatus).toUpperCase();
      // "Update Mobile Number" is only relevant in the mobile-verification flow.
      $('#updateMobileBtn').toggle($('#searchType').val() === 'mobile');

      // Dynamic labels from API localizedDetails
      const labels = acc.localizedDetails && acc.localizedDetails.localizedLabels;
      const localName = acc.localizedDetails && acc.localizedDetails.name;
      const localGender = acc.localizedDetails && acc.localizedDetails.gender;
      if (labels) {
        $('#lbl_name').text(labels.name ? 'Name / ' + labels.name : 'Name');
        $('#lbl_abhaNumber').text(labels.abhaNumber ? 'Abha Number / ' + labels.abhaNumber : 'Abha Number');
        $('#lbl_abhaAddress').text(labels.abhaAddress ? 'Abha Address / ' + labels.abhaAddress : 'Abha Address');
        $('#lbl_gender').text(labels.gender ? 'Gender / ' + labels.gender : 'Gender');
        $('#lbl_dob').text(labels.dob ? 'Date Of Birth / ' + labels.dob : 'Date Of Birth');
        $('#lbl_mobile').text(labels.mobile ? 'Mobile / ' + labels.mobile : 'Mobile');
      }

      $("#image").attr("src", acc.profilePhoto ?
        ((/^\/9j\//.test(acc.profilePhoto) ? "data:image/jpeg;base64," : "data:image/png;base64,") + acc.profilePhoto) :
        "assets/no-photo.png");
      // Field names differ by source: enrollment/account uses ABHANumber /
      // preferredAbhaAddress, the PHR abha-profile uses abhaNumber / abhaAddress.
      const abhaNo = acc.ABHANumber || acc.abhaNumber || '';
      const abhaAddr = abhaAddressOf(acc); // falls back to <number>@<cm id>
      generateQR(abhaNo || abhaAddr || '');
      $("#abha_number").html(abhaNo || '-');
      $("#abha_address").html(abhaAddr || '-');
      // The PHR abha-profile returns fullName (no first/middle/last) — fall back to it.
      const fullName = [acc.firstName, acc.middleName, acc.lastName].filter(Boolean).join(" ") || acc.fullName || acc.name || '';
      $("#fname").html(fullName || '-');
      if (localName) $("#fname_hin").text(localName);
      $("#dob").html(formatDobDMY(acc));
      // Mobile may live only in the login token, not the profile body.
      const cardMobile = (acc.mobile || mobileFromToken(window.ABHA_X_TOKEN) || '').toString().trim();
      $("#user_mobile").html(cardMobile || '-');
      // Address / State / District / Pin Code (field names vary by source — robust).
      var _ap = addrParts(acc);
      $("#address").html(_ap.address);
      $("#state_name").html(_ap.state);
      $("#district_name").html(_ap.district);
      $("#pin_code").html(_ap.pincode);
      const genderEng = acc.gender === 'M' ? 'Male' : acc.gender === 'F' ? 'Female' : acc.gender || '-';
      $("#sex").html(genderEng + (localGender ? ' / ' + localGender : ''));

      const enteredMobile = (window.SEARCH_MOBILE || '').trim();
      const profileMobile = cardMobile;
      if (enteredMobile.length === 10) {
        $('#mobileCompareCard').show();
        $('#entered_mobile').text(enteredMobile);
        $('#abha_mobile_profile').text(profileMobile || 'Not linked');
        if (profileMobile !== enteredMobile) {
          $('#mobile_compare_msg').html('<span style="color:#d97706;font-weight:700;">⚠ Mobile numbers do not match.</span>');
          $('#mobileUpdateBlock').show();
          $('#update_mobile_input').val(enteredMobile);
        } else {
          $('#mobile_compare_msg').html('<span style="color:#0a7a4d;font-weight:700;">✓ Mobile number matched.</span>');
          $('#mobileUpdateBlock').hide();
        }
      } else {
        $('#mobileCompareCard').hide();
      }
    }

    function showAbhaAddressLimitedProfile(user, xToken) {
      let tokenMobile = '';
      try {
        const parts = xToken.split('.');
        const pl = JSON.parse(atob(parts[1].replace(/-/g, '+').replace(/_/g, '/')));
        tokenMobile = pl.mobile || '';
      } catch (e) {}

      $("#abha_number").html(user.abhaNumber || '-');
      $("#abha_address").html(lcAddr(user.abhaAddress) || '-');
      $("#fname").html(user.fullName || '-');
      $("#dob").html('-');
      $("#image").attr("src", "assets/no-photo.png");
      $("#user_mobile").html(tokenMobile || '-');
      $("#address").html('-');
      $("#state_name").html('-');
      $("#district_name").html('-');
      $("#pin_code").html('-');
      $("#sex").html('-');
      $('#mobileCompareCard').hide();

      $('#abha_verify1').hide();
      $('.modal-backdrop').remove();
      hideEnterOtpModal();
      $('#aabha_card_section').show(); $('#abhaSearchPanel').hide(); $('.abha-workspace').addClass('full-card');
      if (window.refreshAbhaLinkState) refreshAbhaLinkState();
    }

    function fetchAbhaProfile(xToken) {
      // Profile fetched via the ABHA-number token → enrollment card endpoint.
      window.ABHA_CARD_SOURCE = 'enrollment';
      const accessToken = window.ABHA_ACCESS_TOKEN;
      $.ajax({
        url: 'abha_integration.php',
        method: 'POST',
        contentType: 'application/json',
        dataType: 'json',
        data: JSON.stringify({
          page: 'abha_profile',
          xToken: xToken,
          accessToken: accessToken
        }),

        success: function(response) {
          if (!response.success) {
            toast("Unable to fetch ABHA profile");
            hideLoader();
            return;
          }
          const acc = response.data;
          if (!acc) {
            toast("No ABHA profile found");
            hideLoader();
            return;
          }
          window.ABHA_X_TOKEN = xToken;
          populateAbhaCard(acc);
          $('.modal-backdrop').remove();
          hideEnterOtpModal();
          $('#aabha_card_section').show(); $('#abhaSearchPanel').hide(); $('.abha-workspace').addClass('full-card');
          if (window.refreshAbhaLinkState) refreshAbhaLinkState();
          hideLoader();
        },

        error: function(xhr) {
          console.error("Profile Error:", xhr.responseText);
          hideLoader();
        }
      });
    }

    // Card footer: reveal the OTP-verified mobile-update panel (same flow as create).
    // Card footer: open the Update Mobile modal (mobile input + OTP verification).
    // Two OTP-verified paths: enrollment (create flow, has ABHA_TXN_ID) or profile
    // (mobile-login/other cards, uses the login X-Token via /profile/account).
    function umMsg(html) {
      $('#umMsg').html(html || '');
    }
    $(document).on('click', '#updateMobileBtn', function() {
      window.__umProfileFlow = !window.ABHA_TXN_ID; // no enrollment session → profile flow
      $('#umSubtitle').text('Verify the new mobile with an OTP');
      $('#um_mobile').val('').prop('readonly', false);
      $('#um_mobile_hint').hide().text('');
      $('#umSendOtpBtn').prop('disabled', false).html('<i class="ri-send-plane-line"></i> Send OTP');
      $('#umOtpSection').hide();
      for (var i = 1; i <= 6; i++) $('#um_otp_' + i).val('');
      $('#umTxnId').val('');
      umMsg('');
      $('#updateMobileModal').modal('show');
    });

    // Step 1 — send an OTP to the new mobile.
    $(document).on('click', '#umSendOtpBtn', function() {
      var mobile = ($('#um_mobile').val() || '').trim();
      if (!/^[6-9]\d{9}$/.test(mobile)) {
        umMsg('<span style="color:#e53e3e;">Enter a valid 10-digit mobile number.</span>');
        $('#um_mobile').focus();
        return;
      }

      var payload = window.__umProfileFlow ? {
        page: 'profile_mobile_send_otp',
        mobile: mobile,
        xToken: window.ABHA_X_TOKEN,
        accessToken: window.ABHA_ACCESS_TOKEN
      } : {
        page: 'mobile_update_send_otp',
        txnId: window.ABHA_TXN_ID,
        mobile: mobile,
        accessToken: window.ABHA_ACCESS_TOKEN
      };

      showLoader();
      $.ajax({
        url: 'abha_integration.php',
        method: 'POST',
        contentType: 'application/json',
        dataType: 'json',
        data: JSON.stringify(payload),
        success: function(resp) {
          hideLoader();
          if (resp && resp.success && resp.data && resp.data.txnId) {
            $('#umTxnId').val(resp.data.txnId);
            $('#umOtpSection').show();
            $('#um_mobile').prop('readonly', true);
            $('#umSendOtpBtn').prop('disabled', true);
            umMsg('<span style="color:#1565c0;">' + (resp.data.message || ('OTP sent to ' + maskMobile(mobile) + '.')) + '</span>');
            $('#um_otp_1').focus();
          } else {
            umMsg('<span style="color:#e53e3e;">Unable to send OTP: ' + (deepErr(resp) || 'Please try again.') + '</span>');
          }
        },
        error: function(xhr) {
          hideLoader();
          var p = {};
          try {
            p = JSON.parse(xhr.responseText || '{}');
          } catch (e) {}
          umMsg('<span style="color:#e53e3e;">' + (deepErr(p) || abdmErrMsg(xhr, 'Send OTP failed.')) + '</span>');
        }
      });
    });

    // Step 2 — verify the OTP → updates the mobile on the ABHA profile.
    $(document).on('click', '#umVerifyBtn', function() {
      var otp = '';
      for (var i = 1; i <= 6; i++) otp += ($('#um_otp_' + i).val() || '');
      var txnId = ($('#umTxnId').val() || '').trim();
      var mobile = ($('#um_mobile').val() || '').trim();
      if (!txnId) {
        umMsg('<span style="color:#e53e3e;">Please send the OTP first.</span>');
        return;
      }
      if (!/^\d{6}$/.test(otp)) {
        umMsg('<span style="color:#e53e3e;">Enter the 6-digit OTP.</span>');
        return;
      }

      var payload = window.__umProfileFlow ? {
        page: 'profile_mobile_verify_otp',
        txnId: txnId,
        otp: otp,
        xToken: window.ABHA_X_TOKEN,
        accessToken: window.ABHA_ACCESS_TOKEN
      } : {
        page: 'mobile_update_verify_otp',
        txnId: txnId,
        otp: otp,
        accessToken: window.ABHA_ACCESS_TOKEN
      };

      showLoader();
      $.ajax({
        url: 'abha_integration.php',
        method: 'POST',
        contentType: 'application/json',
        dataType: 'json',
        data: JSON.stringify(payload),
        success: function(resp) {
          hideLoader();
          if (resp && resp.success) {
            $('#user_mobile').html(mobile);
            $('#abha_mobile_profile').text(mobile);
            umMsg('<span style="color:#0a7a4d;font-weight:700;">✓ Mobile updated successfully.</span>');
            setTimeout(function() {
              $('#updateMobileModal').modal('hide');
            }, 1200);
          } else {
            umMsg('<span style="color:#e53e3e;">OTP verification failed: ' + (deepErr(resp) || 'Please try again.') + '</span>');
          }
        },
        error: function(xhr) {
          hideLoader();
          var p = {};
          try {
            p = JSON.parse(xhr.responseText || '{}');
          } catch (e) {}
          umMsg('<span style="color:#e53e3e;">' + (deepErr(p) || abdmErrMsg(xhr, 'Verify failed.')) + '</span>');
        }
      });
    });
  </script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
</body>

</html>