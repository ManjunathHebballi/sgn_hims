<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");
include("model/reportClass.php");
$newReport = new report;

$rows = array();
$selectedIds = array();

if (isset($_REQUEST['id'])) {
    $rawIds = is_array($_REQUEST['id']) ? $_REQUEST['id'] : explode(',', (string)$_REQUEST['id']);
    foreach ($rawIds as $rawId) {
        $rawId = trim((string)$rawId);
        if ($rawId !== '' && ctype_digit($rawId)) {
            $selectedIds[] = (int)$rawId;
        }
    }
    $selectedIds = array_values(array_unique($selectedIds));
}

foreach ($selectedIds as $ipId) {
    $dept = $newReport->getIPDetailsForSlip($ipId);
    if ($dept && mysqli_num_rows($dept) > 0) {
        $rows[] = mysqli_fetch_assoc($dept);
    }
}

function formatStickerText($value, $fallback = '-') {
    $value = trim((string)$value);
    if ($value === '') {
        return $fallback;
    }

    return strtoupper($value);
}

function esc($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function isMlcCase($value) {
    return (string)$value === '1';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#319197">

    <title>Print Address Graph</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">

    <link href="./css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" ></script>
 <script>window.onload = function () {
    window.print();

} 
</script> 

    

    <style>
        @page {
            size: A4;
            margin: 1.5cm 1cm 1.5cm 1cm;
        }

        body {
            margin: 0;
            background-color: #fff;
            color: #101010;
            font-family: Arial, Helvetica, sans-serif;
            text-transform: uppercase;
        }

        .print-toolbar {
            text-align: right;
            padding: 8px 10px 0;
        }

        .sheet {
            width: 100%;
            box-sizing: border-box;
            padding: 2mm 0 15mm 0;
            page-break-after: always;
        }

        .sheet:last-child {
            page-break-after: auto;
        }

        .mrd-no {
            margin: 0 0 2.6mm;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.2px;
        }

        .label-grid {
            display: grid;
            grid-template-columns: repeat(3, 8cm);
            justify-content: space-between;
            gap: 3mm;
        }

        .label-card {
            position: relative;
            border: 1px solid #777;
            border-radius: 8px;
            padding: 3mm;
            width: 8cm;
            height: 4.2cm;
            box-sizing: border-box;
            overflow: hidden;
        }

        .mlc-badge {
            position: absolute;
            top: 7mm;
            right: 2mm;
            padding: 1px 6px;
            border: 1px solid #c62828;
            border-radius: 10px;
            color: #c62828;
            background: #fff2f2;
            font-size: 9px;
            font-weight: 700;
            line-height: 1.2;
        }

        .label-line {
            display: flex;
            align-items: flex-end;
            gap: 3px;
            margin-bottom: 1.4mm;
            font-size: 11px;
            line-height: 1.12;
        }

        .label-line:last-child {
            margin-bottom: 0;
        }

        .dual-line {
            justify-content: space-between;
            gap: 8px;
            font-size: 11px;
            font-weight: 700;
        }

        .label-key {
            font-weight: 700;
            white-space: nowrap;
        }

        .label-value {
            font-weight: 600;
            min-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .line-fill {
            flex: 1;
            border-bottom: 1px solid #5b5b5b;
            margin-bottom: 2px;
        }

        .patient-name {
            font-weight: 700;
        }

        .center-wrap {
            max-width: 100%;
        }

        @media screen and (max-width: 991px) {
            .label-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .label-card {
                width: 100%;
                height: auto;
            }
        }

        @media screen and (max-width: 640px) {
            .label-grid {
                grid-template-columns: 1fr;
            }

            .label-card {
                width: 100%;
                height: auto;
            }
        }

        @media print {
            body {
                margin: 0;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .container-fluid {
                padding-left: 0 !important;
                padding-right: 0 !important;
            }

            .d-print-none,
            .print-toolbar {
                display: none !important;
            }

            .center-wrap {
                width: 100% !important;
                max-width: none !important;
            }

            .sheet {
                width: 100%;
                min-height: 210mm;
                margin: 0;
                padding: 0;
            }

            .mrd-no {
                margin: 0 0 1.2mm;
                font-size: 12px;
            }

            .label-grid {
                grid-template-columns: repeat(3, 8cm) !important;
                justify-content: space-between;
                gap: 4mm;
            }

            .label-card {
                width: 8cm;
                height: 4.2cm;
                border-radius: 6px;
                padding: 1.9mm;
            }

            .label-line {
                margin-bottom: 0.7mm;
                font-size: 12px;
                line-height: 1.06;
            }

            .dual-line {
                font-size: 12px;
                gap: 4px;
            }

            .line-fill {
                margin-bottom: 1px;
            }
        }
    </style>
     

</head>

<body>


  


    <!--**********************************
        Main wrapper start
    ***********************************-->
   
   
    



              
<div class="print-toolbar d-print-none">
    <a href="#" onclick="window.print();return false;" data-toggle="tooltip" title="Print" class="btn btn-danger btn-bold mt-2 mt-lg-0"><i class="icofont-print"></i> Print</a>
</div>

<div class="container-fluid center-wrap">
    <?php if (!empty($rows)) { ?>
        <?php foreach ($rows as $index => $res) {
            $ipId = formatStickerText(isset($res['ipd_no']) ? $res['ipd_no'] : '');
            $uid = formatStickerText(isset($res['uhid']) ? $res['uhid'] : '');
            $room = formatStickerText(isset($res['bed_number']) ? $res['bed_number']. ' '.$res['ward'] : '');
            $patientName = formatStickerText(isset($res['name']) ? $res['name'] : '');
            $relativeName = formatStickerText(isset($res['parent_name']) ? $res['parent_name'] : '');
            $gender = formatStickerText(isset($res['gender']) ? substr($res['gender'], 0, 1) : '');
            $age = (isset($res['age']) && trim((string)$res['age']) !== '') ? formatStickerText($res['age'] . 'Y') : '-';
            $doa = '-';
            if (!empty($res['admission_date']) && strtotime($res['admission_date']) !== false) {
                $doa = date("d/m/Y", strtotime($res['admission_date']));
            }
            $city = formatStickerText(isset($res['city']) ? $res['city'] : '');
            $consultant = formatStickerText(isset($res['doctor']) ? $res['doctor'] : (isset($res['doctor_name']) ? $res['doctor_name'] : ''));
            $contact = formatStickerText(isset($res['mobile']) ? $res['mobile'] : '0', '0');
            $mrdNo = formatStickerText(isset($res['opd_no']) ? $res['opd_no'] : $ipId, $ipId);
            $isMlc = isMlcCase(isset($res['mlc']) ? $res['mlc'] : '0');
            if (stripos($mrdNo, 'IP') !== 0) {
                $mrdNo = 'IP' . $mrdNo;
            }
        ?>
            <section class="sheet">
              <div class="label-grid">
                    <?php for ($i = 1; $i <= 12; $i++) { ?>
                        <article class="label-card">
                           
                            <div class="label-line dual-line">
                                <span><span class="label-key">IP ID :</span> <span class="label-value"><?= esc($ipId) ?></span></span>
                                <span><span class="label-key">UID :</span> <span class="label-value"><?= esc($uid) ?></span></span>
                            </div>
<div class="label-line">
                                
                                <span class="line-fill"></span>
                            </div>
                             <?php if ($isMlc) { ?>
                                <div class="mlc-badge">⚠ MLC</div>
                            <?php } ?>
                            <div class="label-line ">
                                <span class="label-key">Room :</span>
                                <span class="label-value"><?= esc($room) ?></span>
                                
                            </div>
    <div class="label-line">
                                
                                <span class="line-fill"></span>
                            </div>
                            <div class="label-line dual-line">
                              <span>  <span class="label-key">Patient :</span>
                                <span class="label-value patient-name"><?= esc($patientName) ?></span></span>
                               
                            </div>

                            <div class="label-line">
                                
                                <span class="line-fill"></span>
                            </div>

                            <div class="label-line dual-line">
                                <span><span class="label-key">DOA :</span>
                                <span class="label-value"><?= esc($doa) ?></span></span>
                                 <span><span class="label-key">Gender/Age :</span>
                                <span class="label-value"> <?= esc($gender) ?> / <?= esc($age) ?></span></span>
                            </div>
<div class="label-line">
                                
                                <span class="line-fill"></span>
                            </div>
                            <div class="label-line dual-line">
                                <span><span class="label-key">City :</span>
                                <span class="label-value"><?= esc($city) ?></span></span>
                               <span><span class="label-key">Contact :</span>
                                <span class="label-value"><?= esc($contact) ?></span></span>
                            </div>
<div class="label-line">
                                
                                <span class="line-fill"></span>
                            </div>
                            <div class="label-line">
                                <span class="label-key">Consultant :</span>
                                <span class="label-value"><?= esc($consultant) ?></span>
                                
                            </div>

                            
                        </article>
                    <?php } ?>
                </div>
            </section>
        <?php } ?>
    <?php } else { ?>
        <p class="mrd-no">No patient record found for bulk address graph printing.</p>
    <?php } ?>
</div>


    <!--**********************************
        Scripts
    ***********************************-->
    <!--  vendors -->

    <script src="./js/custom.min.js"></script>



</body>

</html>