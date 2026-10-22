<?php
// session_config.php forces the project session save path and starts the session.
// Calling session_start() ahead of it would bind this page to PHP's default save
// path, leaving $_SESSION empty and bouncing a logged-in user back to index.php.
require_once(__DIR__ . '/model/session_config.php');

include('model/optreatmentClass1.php');
require_once __DIR__ . '/lookup_options.php';

$newTreat = new addOTTreat;

// The patient id is always a numeric central_opd.id. Pin it to an int once, here,
// and use $pid everywhere below — so it can never carry SQL or script into the
// queries, the onclick handlers or the hidden pid field, whatever the URL says.
$pid = isset($_REQUEST['id']) ? (int) $_REQUEST['id'] : 0;

$patient = $newTreat->getPatient($pid);
$res = mysqli_fetch_assoc($patient);

$exam_res = $newTreat->getOPExamination($pid);
$exam_row = mysqli_fetch_assoc($exam_res);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>OP Treatment</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">

    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">

    <link href="assets/libs/datatables.net-select-bs4/css//select.bootstrap4.min.css" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet" />
    <!-- Summernote css -->
    <link href="assets/libs/summernote/summernote-bs4.min.css" rel="stylesheet" type="text/css" />

    <?php $lab = $newTreat->getLab();
    $head = '';
    $data = array();
    $sn = 1;
    while ($row = mysqli_fetch_assoc($lab)) {
        if ($head != $row['testname']) {
            $parent_text = $row['testname'];
            // echo $parent_text.'<br>';
            $data[] = [
                "id" => $sn,
                "text" => $parent_text,
                "children" => []
            ];
            $sn++; // Increment the counter
        }

        $data[count($data) - 1]["children"][] = [
            "id" => $row['testid'],
            "text" => $row['test_name']
        ];

        $head = $row['testname'];
    }
    // print_r($data);
    $jsonData = json_encode($data);

    // exit;
    ?>
    <script>
        var countries = <?= $jsonData ?>
    </script>

    <?php
    $radio_lab = $newTreat->getRadiology(); // Fetch radiology data
    $head = '';
    $radio_data = array();
    $radio_sn = 1;

    // For radiology tests
    while ($row = mysqli_fetch_assoc($radio_lab)) {
        if ($head != $row['testname']) {
            $parent_text = $row['testname'];
            $radio_data[] = [
                "id" => $radio_sn,
                "text" => $parent_text,
                "children" => []
            ];
            $radio_sn++;
        }
        $radio_data[count($radio_data) - 1]["children"][] = [
            "id" => $row['testid'],
            "text" => $row['test_name'] ? $row['test_name'] : $parent_text
        ];
        $head = $row['testname'];
    }

    $jsonRadioData = json_encode($radio_data);
    ?>
    <script>
        var radioTests = <?= $jsonRadioData ?>;
    </script>


<style>
    /* ── OP Treatment — step-wise wizard (visual only) ─────────────────────── */
    .content-body .card { border: none; border-radius: 16px; box-shadow: 0 8px 28px rgba(30,41,59,.08); }
    .content-body .card-body { padding: 22px 26px; }

    .op-progress { height: 6px; background: #eef2f9; border-radius: 4px; overflow: hidden; margin: 4px 0 16px; }
    .op-progress > div { height: 100%; width: 0; background: linear-gradient(90deg, #4f46e5, #0ea5e9); transition: width .3s ease; }

    .op-stepper { display: flex; flex-wrap: wrap; gap: 8px; margin: 0 0 18px; }
    .op-pill { display: inline-flex; align-items: center; gap: 8px; border: 1px solid #e2e8f0; background: #fff;
        border-radius: 30px; padding: 6px 15px 6px 6px; cursor: pointer; font-size: 12.5px; font-weight: 600;
        color: #64748b; transition: all .15s; font-family: inherit; }
    .op-pill:hover { border-color: #6366f1; color: #4f46e5; }
    .op-pill-num { width: 24px; height: 24px; border-radius: 50%; background: #eef2f9; color: #64748b;
        display: inline-flex; align-items: center; justify-content: center; font-weight: 800; font-size: 12px; flex: 0 0 auto; }
    .op-pill.active { border-color: #4f46e5; background: #eef2ff; color: #4f46e5; box-shadow: 0 3px 10px rgba(79,70,229,.16); }
    .op-pill.active .op-pill-num { background: #4f46e5; color: #fff; }
    .op-pill.done { color: #0f766e; border-color: #a7f3d0; background: #ecfdf5; }
    .op-pill.done .op-pill-num { background: #10b981; color: #fff; }
    .op-pill.done .op-pill-num span { display: none; }
    .op-pill.done .op-pill-num::after { content: "\2713"; }

    .op-step-head { display: flex; align-items: center; gap: 10px; margin: 6px 0 16px; font-size: 17px; font-weight: 700; color: #1e293b; }
    .op-step-head .op-step-idx { background: linear-gradient(135deg, #4f46e5, #0ea5e9); color: #fff; border-radius: 8px; padding: 3px 11px; font-size: 12px; font-weight: 800; letter-spacing: .3px; }

    /* step visibility — !important so it always wins over Bootstrap/widget rules */
    .op-step-hidden { display: none !important; }
    /* stray section dividers (kept between cols) are noise in the wizard */
    .content-body .card-body .row > hr { display: none; }
    /* sub-section headings within a step (Vital Signs / Body Measurements, etc.) */
    .op-subhead { font-size: 12.5px; font-weight: 800; text-transform: uppercase; letter-spacing: .5px;
        color: #4f46e5; margin: 20px 0 12px; padding-bottom: 6px; border-bottom: 2px solid #eef2ff; }
    .op-subhead:first-child { margin-top: 4px; }
    .op-vital-group { display: block; }

    /* sticky navigation / actions bar (built at form level) */
    .op-navbar { position: sticky; bottom: 0; display: flex; align-items: center; gap: 10px;
        background: rgba(255,255,255,.96); backdrop-filter: blur(6px); border-top: 1px solid #e6eaf2;
        padding: 12px 22px; margin-top: 18px; z-index: 30; box-shadow: 0 -6px 20px rgba(30,41,59,.06);
        border-bottom-left-radius: 16px; border-bottom-right-radius: 16px; }
    .op-navbar .op-spacer { flex: 1 1 auto; }
    .op-navbar .btn { border-radius: 10px; font-weight: 700; padding: 9px 20px; float: none !important; margin: 0 !important; }
    .op-prev { background: #eef2f9; color: #334155; border: none; }
    .op-next { background: linear-gradient(135deg, #4f46e5, #6366f1); color: #fff; border: none; }
    .op-next:hover, .op-prev:hover { filter: brightness(.97); }

    @media (max-width: 767px) { .op-pill-txt { display: none; } .content-body .card-body { padding: 16px; } }
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
    <!--*******************
        Preloader end
    ********************-->


    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">

        <?php include('leftbar.php') ?>

        <!--**********************************
            Ccontent body start
        ***********************************-->
        <div class="content-body">
            <!-- row -->

            <div class="container-fluid">
                <div class="row mb-3">
                    <div class="col-md-12">
                        <h4 class="text-uppercase">DEPARTMENT OP VISIT DETAILS</h4>
                    </div>
                </div>
                <form action="controller/processOPTreat1.php" method="post">
                    <div class="card">
                        <div class="card-body">
                            <h5><?= $res['opd_no'] . ' : ' . $res['name'] . ' ( ' . $res['age'] . ' y, ' . $res['gender'] . ' )' ?> </h5>
                            <hr />

                            <input type="hidden" name="exam_edit" value="<?= $exam_row['id'] ?>">
                            <div class="row">

                                <div class="col-md-6">
                                    <label>Comorbidities</label>
                                    <select name="como[]" multiple id="como" class="form-control selectpicker" placeholder="Select Comorbidities" data-live-search="true">

                                        <?php lookupOptions($newTreat->getComorbidities(), 'comorbidities', $exam_row['comorbidities'] ?? ''); ?>


                                    </select>

                                </div>
                                <div class="col-md-6">
                                    <label>Hypersensitivity</label>
                                    <select name="hyper[]" multiple id="hyper" class="form-control selectpicker" placeholder="Select Hypersensitivity" data-live-search="true">

                                        <?php lookupOptions($newTreat->getHypersensitivity(), 'hypersensitivity', $exam_row['hypersensitivity'] ?? ''); ?>


                                    </select>

                                </div>
                                <div class="col-md-6 mt-3">
                                    <div class="form-group">
                                        <label>Chief Complain</label>
                                        <select name="complaint[]" multiple id="complaint" class="form-control selectpicker" data-live-search="true">
                                            <?php lookupOptions($newTreat->getComplaints(), 'complaints', $exam_row['complaints'] ?? ''); ?>


                                        </select>
                                    </div>

                                </div>
                                <div class="col-md-6 mt-3">
                                    <label>Associated With</label>
                                    <select name="associated[]" multiple id="associated" class="form-control selectpicker" placeholder="Select Associated With" data-live-search="true">

                                        <?php lookupOptions($newTreat->getAssociated_with(), 'associated_with', $exam_row['associated_with'] ?? ''); ?>


                                    </select>

                                </div>
                                <div class="col-md-12 mt-4">
                                    <label> History</label>

                                    <pre><textarea name="history" id="textarea1"  placeholder="Type Here...."><?= $exam_row['history'] ?></textarea></pre>
                                </div>

                                <?php
                                // Family History is captured per relative rather than as free text: the
                                // patient's PHR renders it from the structured FHIR fields (relationship,
                                // sex, condition, age, contributed-to-death) and prints "NA" for anything
                                // the record does not carry. A sentence in a textarea could never fill
                                // those, so the form collects them directly.
                                //
                                // Stored as JSON in the existing op_examination.family_history TEXT column —
                                // no schema change. Rows saved before this (plain sentences) still load:
                                // json_decode fails, and the value is shown in the free-text box below.
                                $fhRows   = [];
                                $fhLegacy = '';
                                $fhRaw    = trim((string) ($exam_row['family_history'] ?? ''));
                                if ($fhRaw !== '') {
                                    $decoded = json_decode($fhRaw, true);
                                    if (is_array($decoded)) {
                                        $fhRows = $decoded;
                                    } else {
                                        $fhLegacy = $fhRaw;
                                    }
                                }
                                $fhRelations = ['Father', 'Mother', 'Brother', 'Sister', 'Son', 'Daughter',
                                                'Grandfather', 'Grandmother', 'Uncle', 'Aunt', 'Cousin',
                                                'Husband', 'Wife', 'Other'];
                                ?>
                                <div class="col-md-12 mt-2">
                                    <label> Family History</label>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered mb-1" id="fhTable">
                                            <thead>
                                                <tr>
                                                    <th style="width:18%">Relation</th>
                                                    <th style="width:34%">Condition</th>
                                                    <th style="width:12%">Age</th>
                                                    <th style="width:13%">Deceased</th>
                                                    <th style="width:18%">Contributed to death</th>
                                                    <th style="width:5%"></th>
                                                </tr>
                                            </thead>
                                            <tbody id="fhBody">
                                            <?php foreach ($fhRows as $fh): ?>
                                                <tr>
                                                    <td>
                                                        <select name="fh_relation[]" class="form-control form-control-sm">
                                                            <option value="">-- select --</option>
                                                            <?php foreach ($fhRelations as $rel): ?>
                                                                <option value="<?= htmlspecialchars($rel, ENT_QUOTES, 'UTF-8') ?>"<?= (($fh['relation'] ?? '') === $rel ? ' selected' : '') ?>><?= htmlspecialchars($rel, ENT_QUOTES, 'UTF-8') ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </td>
                                                    <td><input type="text" name="fh_condition[]" class="form-control form-control-sm" maxlength="150" placeholder="e.g. Hypertension" value="<?= htmlspecialchars((string)($fh['condition'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"></td>
                                                    <!-- Age is mandatory (the PHR prints "age: NA" without it) but is NOT
                                                         marked `required`: this form is a wizard and step 1 is display:none
                                                         when the user saves from a later step, where Chrome refuses to submit
                                                         a form containing an invalid control it cannot focus. Enforced in the
                                                         submit handler instead, which can show a real message. -->
                                                    <td><input type="number" name="fh_age[]" class="form-control form-control-sm" min="0" max="120" placeholder="yrs" value="<?= htmlspecialchars((string)($fh['age'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"></td>
                                                    <td>
                                                        <select name="fh_deceased[]" class="form-control form-control-sm">
                                                            <option value="No"<?= (($fh['deceased'] ?? 'No') === 'No' ? ' selected' : '') ?>>No</option>
                                                            <option value="Yes"<?= (($fh['deceased'] ?? '') === 'Yes' ? ' selected' : '') ?>>Yes</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="fh_contributed[]" class="form-control form-control-sm">
                                                            <option value="No"<?= (($fh['contributed'] ?? 'No') === 'No' ? ' selected' : '') ?>>No</option>
                                                            <option value="Yes"<?= (($fh['contributed'] ?? '') === 'Yes' ? ' selected' : '') ?>>Yes</option>
                                                        </select>
                                                    </td>
                                                    <td class="text-center"><button type="button" class="btn btn-sm btn-link text-danger fh-del" title="Remove">&times;</button></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="fhAdd">+ Add relative</button>
                                    <small class="form-text text-muted">Each relative is shared separately in the patient's ABDM health record. Leave empty if there is no relevant family history.</small>

                                    <?php if ($fhLegacy !== ''): ?>
                                        <!-- Pre-existing free-text entry. Shown so nothing is lost; it is
                                             carried through untouched until someone re-enters it as rows. -->
                                        <div class="mt-2">
                                            <label class="text-muted" style="font-size:12px">Previously recorded (free text)</label>
                                            <textarea name="family_history_legacy" class="form-control" rows="2"><?= htmlspecialchars($fhLegacy, ENT_QUOTES, 'UTF-8') ?></textarea>
                                        </div>
                                    <?php else: ?>
                                        <input type="hidden" name="family_history_legacy" value="">
                                    <?php endif; ?>
                                </div>


                                <div class="col-md-12">
                                    <h5>Vitals</h5>
                                    <hr>
                                    <div class="op-vital-group">
                                    <h6 class="op-subhead">Vital Signs</h6>
                                    <div class="row">
                                        <div class="col-sm-2">
                                            <label>BP</label>
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" placeholder="BP" name="bp" id='bp' value="<?= $exam_row['bp'] ?>">
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="basic-addon2">BP_S / BP_D</span>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-sm-2">

                                            <label>RBS</label>
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" placeholder="RBS" name="rbs" id='rbs' value="<?= $exam_row['rbs'] ?>">
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="basic-addon2">mg/dl</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">

                                            <label>PR</label>
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" placeholder="PR" name="pr" id='pr' value="<?= $exam_row['pr'] ?>">
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="basic-addon2">bpm</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">

                                            <label>TP</label>
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" placeholder="TP" name="tp" id='tp' value="<?= $exam_row['tp'] ?>">
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="basic-addon2"><sup>o</sup>F</span>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-sm-2">

                                            <label>RR</label>
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" placeholder="RR" name="rr" id='rr' value="<?= $exam_row['rr'] ?>">
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="basic-addon2">Cpm</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-2">

                                            <label>SpO2</label>
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" placeholder="SpO2" name="spo" id='spo' value="<?= $exam_row['spo2'] ?>">
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="basic-addon2">%</span>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <h6 class="op-subhead">Body Measurements</h6>
                                    <div class="row">
                                        <div class="col-sm-2">

                                            <label>Ht</label>
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" placeholder="Ht" name="ht" id='ht' value="<?= $exam_row['ht'] ?>">
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="basic-addon2">Cms</span>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-sm-2">


                                            <label>Waist</label>
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" placeholder="Waist" name="waist" id='waist' value="<?= $exam_row['waist'] ?>">
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="basic-addon2">Cms</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">

                                            <label>Weight</label>
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" placeholder="Weight" name="weight" id='weight' value="<?= $exam_row['weight'] ?>">
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="basic-addon2">Kgs</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-2">

                                            <label>HC</label>
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" placeholder="HC" name="hc" id='hc' value="<?= $exam_row['hc'] ?>">
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="basic-addon2">Cms</span>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-sm-2">

                                            <label>BMI</label>
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" placeholder="BMI" name="bmi" id='bmi' value="<?= $exam_row['bmi'] ?>">

                                            </div>

                                        </div>

                                    </div>
                                    </div><!-- /op-vital-group -->

                                    <div class="col-md-12 mt-4">
                                        <h5> Examination</h5>
                                        <hr />

                                        <pre><textarea name="examination" id="textarea2"  placeholder="Type Here...."><?= $exam_row['examination'] ?></textarea></pre>
                                    </div>
                                    <hr />
                                    <?php
                                    $wOpt = function ($field, $options) use ($exam_row) {
                                        $cur = (string)($exam_row[$field] ?? '');
                                        $h = '<option value="">--</option>';
                                        foreach ($options as $o) {
                                            $sel = ($cur === (string)$o) ? ' selected' : '';
                                            $h .= '<option' . $sel . '>' . htmlspecialchars((string)$o) . '</option>';
                                        }
                                        return $h;
                                    };
                                    $wVal = function ($field) use ($exam_row) { return htmlspecialchars((string)($exam_row[$field] ?? '')); };
                                    ?>
                                    <div class="col-md-12 mt-3">
                                        <h5>Wellness / Lifestyle Assessment</h5><hr />

                                        <h6 class="op-subhead">Lifestyle</h6>
                                        <div class="row">
                                            <div class="col-md-3 mb-2"><label>Smoking</label><select name="smoking" class="form-control"><?= $wOpt('smoking', ['Never','Former','Current']) ?></select></div>
                                            <div class="col-md-3 mb-2"><label>Alcohol</label><select name="alcohol" class="form-control"><?= $wOpt('alcohol', ['None','Occasional','Regular']) ?></select></div>
                                            <div class="col-md-3 mb-2"><label>Diet</label><select name="diet" class="form-control"><?= $wOpt('diet', ['Vegetarian','Non-Vegetarian','Vegan','Mixed']) ?></select></div>
                                        </div>

                                        <h6 class="op-subhead">Physical Activity</h6>
                                        <div class="row">
                                            <div class="col-md-3 mb-2"><label>Steps</label><input type="number" name="steps" class="form-control" placeholder="e.g. 6000" value="<?= $wVal('steps') ?>"></div>
                                            <div class="col-md-3 mb-2"><label>Calories Burned</label><input type="number" name="calories_burned" class="form-control" placeholder="kcal" value="<?= $wVal('calories_burned') ?>"></div>
                                            <div class="col-md-3 mb-2"><label>Sleep Duration (hrs)</label><input type="number" step="0.5" name="sleep_duration" class="form-control" placeholder="e.g. 7" value="<?= $wVal('sleep_duration') ?>"></div>
                                        </div>

                                        <h6 class="op-subhead">General Assessment</h6>
                                        <div class="row">
                                            <div class="col-md-3 mb-2"><label>Fluid Intake (ml)</label><input type="number" name="fluid_intake" class="form-control" placeholder="e.g. 2000" value="<?= $wVal('fluid_intake') ?>"></div>
                                            <div class="col-md-3 mb-2"><label>Mental Status</label><select name="mental_status" class="form-control"><?= $wOpt('mental_status', ['Normal','Anxious','Depressed','Stressed','Other']) ?></select></div>
                                            <div class="col-md-3 mb-2"><label>Overall Wellness</label><select name="overall_wellness" class="form-control"><?= $wOpt('overall_wellness', ['Good','Average','Poor']) ?></select></div>
                                        </div>

                                        <?php
                                        // Women's Health applies to female patients only. For anyone
                                        // else the block is hidden but the stored values ride along as
                                        // hidden inputs — the controller reads these fields
                                        // unconditionally, so dropping them from the form entirely
                                        // would blank existing data on the next save.
                                        $genderRaw   = strtolower(trim((string) ($res['gender'] ?? '')));
                                        $isFemalePat = in_array($genderRaw, ['f', 'female', 'woman'], true);
                                        ?>
                                        <?php if (!$isFemalePat): ?>
                                            <input type="hidden" name="lmp" value="<?= $wVal('lmp') ?>">
                                            <input type="hidden" name="menopause" value="<?= $wVal('menopause') ?>">
                                            <input type="hidden" name="menarche" value="<?= $wVal('menarche') ?>">
                                        <?php else: ?>
                                        <h6 class="op-subhead">Women's Health</h6>
                                        <div class="row">
                                            <div class="col-md-3 mb-2"><label>LMP</label><input type="date" name="lmp" class="form-control" value="<?= $wVal('lmp') ?>"></div>
                                            <div class="col-md-3 mb-2"><label>Menopause</label><select name="menopause" class="form-control"><?= $wOpt('menopause', ['No','Yes']) ?></select></div>
                                            <div class="col-md-3 mb-2"><label>Menarche (age)</label><input type="number" name="menarche" class="form-control" placeholder="age in years" value="<?= $wVal('menarche') ?>"></div>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <hr />
                                    <div class="col-md-12 mt-3">
                                        <label>Diagnosis</label>
                                        <select name="diagnosis[]" multiple id="diagnosis" class="form-control selectpicker" placeholder="Select Diagnosis" data-live-search="true">

                                            <?php lookupOptions($newTreat->getIcdCodes(), 'details', $exam_row['diagnosis'] ?? ''); ?>


                                        </select>

                                    </div>

                                    <div class="row px-3 mt-3">



                                    </div>

                                    <div class="row p-3">

                                        <div class="col-md-12">
                                            <h5>Add Prescription</h5>
                                            <hr />
                                            <div>
                                                <table class="table table-bordered " id="loadMedic">
                                                    <thead class="thead-dark  text-center ">
                                                        <tr>
                                                            <!--<th class="text-center" width="50px">Sl.No.</th>-->
                                                            <!--<th>Date</th>-->
                                                            <th>Medicine Name</th>

                                                            <th>Dosage</th>
                                                            <th>Timing</th>
                                                            <th>Frequency</th>
                                                            <th>Duration</th>

                                                            <th>Quantity</th>
                                                            <th>Instructions</th>


                                                            <th>Action</th>

                                                        </tr>
                                                    </thead>


                                                    <tbody class="text-black text-center">
                                                        <?php
                                                        $data = $newTreat->getPatienMedicDoc($pid);
                                                        if (mysqli_num_rows($data) > 0) {
                                                            while ($row = mysqli_fetch_assoc($data)) { ?>
                                                                <tr>



                                                                    <td>


                                                                        <?= $row['name'] ?></td>
                                                                    <td><?php echo $row['dosage'] ?></td>
                                                                    <td><?php echo $row['when'] ?></td>
                                                                    <td> <?php echo $row['frequency'] ?></td>
                                                                    <td><?php echo $row['days'] ?></td>

                                                                    <td><?= $row['qty'] ?></td>
                                                                    <td><?= $row['instruction'] ?></td>
                                                                    <td class="text-center"><a href="#" title="Delete Test" class="badge badge-pill badge-danger"><i class="ri-close-line "></i></a></td>


                                                                </tr>
                                                            <?php }
                                                        } else if ($res['old_patient'] == 1) {
                                                            $sn = 1;
                                                            $data = $newTreat->patientoldDetials($res['opd_no'], $res['date']);
                                                            while ($row = mysqli_fetch_assoc($data)) {
                                                            ?>
                                                                <tr id="add<?= $sn ?>">


                                                                    <td>
                                                                        <input type="text" class="form-control" list="medicines101" name="medicine[]" id="medicine101" onkeyup="addMedic(this.value,101)" value="<?= $row['name'] ?>" autocomplete="off" readonly placeholder="Enter Medicine Name">




                                                                    </td>
                                                                    <td>


                                                                        <input type="text" class="form-control" list="dosage" name="dose[]" id="dose101" onchange="calQty(101)" value="<?= $row['dosage'] ?>" readonly placeholder="Enter Dosage" />

                                                                    </td>
                                                                    <td> <input type="text" class="form-control" readonly name="food[]" id="food101" value="<?= $row['when'] ?>" />

                                                                    </td>
                                                                    <td><input type="text" class="form-control" readonly name="frequency[]" id="frequency101" value="<?= $row['frequency'] ?>" />

                                                                    </td>

                                                                    <td>

                                                                        <input type="text" class="form-control" list="day1" name="days[]" id="days101" onkeyup="showResult(this.value,1)" onchange="calQty(1)" autocomplete="off" placeholder="Enter Medicine Name" value="<?= $row['days'] ?>" readonly>


                                                                    </td>
                                                                    <td> <input class="form-control" name="qty[]" id="qty101" value="<?= $row['qty'] ?>" readonly></td>
                                                                    <td><a href="#" onclick="dltold(<?= $sn; ?>)" class="text-danger">Delete</a></td>

                                                                    <input type='hidden' name='medicine_id[]' id='medicine_id101' value='<?= $row['medicine'] ?>'>

                                                                    <!-- <td>
                                                                             
                                                                                     <a href="" class="badge badge-pill badge-danger p-3 "><i class=" bi bi-trash"></i></a>
                                                                                 </td> -->
                                                                </tr>

                                                        <?php $sn++;
                                                            }
                                                        }
                                                        ?>
                                                        <tr id="addr1">


                                                            <td>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control" list="medicines1" name="medicine[]" id="medicine1" onkeyup="addMedic(this.value,1)" autocomplete="off" placeholder="Enter Medicine Name">
                                                                    <div class="input-group-append">
                                                                        <button class="btn btn-outline-secondary" type="button" title="Search medicine" onclick="openMedicineSearch(1)">Search</button>
                                                                    </div>
                                                                </div>

                                                                <datalist id="medicines1">
                                                                    <option value="">Select Medicine</option>
                                                                    <option value="">Select Medicine</option>
                                                                    <?php $medi = $newTreat->getdoctMedic();
                                                                    while ($medic = mysqli_fetch_assoc($medi)) { ?>
                                                                        <option value="<?= $medic['name'] ?>"></option>
                                                                    <?php } ?>
                                                                </datalist>


                                                            </td>
                                                            <td>


                                                                <input type="text" class="form-control" list="dosage" name="dose[]" id="dose1" onchange="calQty(1)" placeholder="Enter Dosage" />
                                                                <datalist id="dosage">
                                                                    <option value="">Select</option>
                                                                    <option value="1-0-0">1-0-0</option>
                                                                    <option value="0-0-1">0-0-1</option>
                                                                    <option value="1-0-1">1-0-1</option>
                                                                    <option value="1-1-1">1-1-1</option>
                                                                    <option value="1-1-0">1-1-0</option>
                                                                    <option value="0-1-0">0-1-0</option>
                                                                    <option value="0-1-1">0-1-1</option>
                                                                    <option value="0-0-0-1">0-0-0-1</option>
                                                                </datalist>
                                                            </td>
                                                            <td> <select class="form-control" name="food[]" id="food1">
                                                                    <option value="">Select</option>
                                                                    <option value="Before Food">Before Food</option>
                                                                    <option value="After Food">After Food</option>
                                                                    <option value="Before Breakfast">Before Breakfast</option>
                                                                    <option value="After Breakfast">After Breakfast</option>
                                                                    <option value="Before Lunch">Before Lunch</option>
                                                                    <option value="After Lunch">After Lunch</option>
                                                                    <option value="Before Dinner">Before Dinner</option>
                                                                    <option value="After Dinner">After Dinner</option>
                                                                    <option value="Empty Stomach">Empty Stomach</option>
                                                                    <option value="Bed Time">Bed Time</option>
                                                                    <option value="SOS">SOS</option>

                                                                </select></td>
                                                            <td>
                                                                <select class="form-control" name="frequency[]" id="frequency1">
                                                                    <option value="">Select</option>
                                                                    <option value="Daily">Daily</option>
                                                                    <option value="Alternate Day">Alternative Days</option>
                                                                    <option value="Weekly">Weekly</option>
                                                                    <option value="Fort Night">Fort Night</option>
                                                                    <option value="Monthly">Monthly</option>


                                                                </select>
                                                            </td>

                                                            <td>

                                                                <input type="text" class="form-control" list="day1" name="days[]" id="days1" onkeyup="showResult(this.value,1)" onchange="calQty(1)" autocomplete="off" placeholder="Enter Medicine Name">
                                                                <datalist id="day1">


                                                                    <option><span class="span_day1"></span> days</option>
                                                                    <option><span class="span_day1"></span> weeks</option>
                                                                    <option><span class="span_day1"></span> months</option>
                                                                    <option><span class="span_day1"></span> years</option>
                                                                </datalist>

                                                            </td>
                                                            <td> <input class="form-control" name="qty[]" id="qty1"></td>
                                                            <td> <input class="form-control" name="inst[]" id="inst1" placeholder='Enter Instructions'></td>
                                                            <td><button type="button" class="btn btn-danger" onclick="dlt(1)">Delete</button></td>

                                                            <input type='hidden' name='medicine_id[]' id='medicine_id1' value=''>

                                                            <!-- <td>
                                                                             
                                                                                    <a href="" class="badge badge-pill badge-danger p-3 "><i class=" bi bi-trash"></i></a>
                                                                                </td> -->
                                                        </tr>
                                                        <tr id="addr2"></tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                    </div>


                                    <!--end of Medication-->

                                    <hr>
                                    <div class="row ">


                                        <div class="col-md-6 mb-2">
                                            <h5> LAB Investigations</h5>


                                            <hr />

                                            <div class="">
                                                <div class="form-group">
                                                    <label for="">Select Tests</label>
                                                    <select id="lab" name="lab_test[]" class="form-control selectpicker" multiple style="width: 100%"></select>

                                                </div>

                                            </div>


                                            <table class="table table-bordered" id="loadLab">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th>Sl.No.</th>
                                                        <th>Selected Tests</th>
                                                        <!--<th>Result</th>-->
                                                        <th class="text-center">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $sn = 1;
                                                    $lab = $newTreat->getPatientLab($pid);
                                                    while ($row = mysqli_fetch_assoc($lab)) { ?>
                                                        <tr>
                                                            <td><?= $sn ?></td>
                                                            <td>
                                                                <p><?= $row['test_name'] ?></p>
                                                            </td>

                                                            <td class="text-center"><a href="#" onclick="deleteLab(<?= htmlspecialchars(json_encode($row['op_lab_id'], JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP), ENT_QUOTES, 'UTF-8') ?>,<?= htmlspecialchars(json_encode($pid, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP), ENT_QUOTES, 'UTF-8') ?>)" data-toggle="tooltip" title="Delete Test" class="badge badge-pill badge-danger"><i class="ri-close-line "></i></a></td>

                                                        </tr>
                                                    <?php $sn++;
                                                    } ?>
                                                </tbody>
                                            </table>
                                            <textarea class="form-control mt-3" cols='1' rows='2' placeholder="Enter Laboratory Observation...." name='lab_obs'><?= $exam_row['lab_observation'] ?></textarea>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <h5>Radiology</h5>


                                            <hr />

                                            <div class="">
                                                <div class="form-group">
                                                    <label for="">Select Tests</label>
                                                    <select id="radio_lab" name="radio_lab_test[]" class="form-control selectpicker" multiple style="width: 100%"></select>

                                                </div>

                                            </div>


                                            <table class="table table-bordered" id="loadLab">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th>Sl.No.</th>
                                                        <th>Selected Tests</th>
                                                        <!--<th>Result</th>-->
                                                        <th class="text-center">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $sn = 1;
                                                    $lab = $newTreat->getPatientLabRadio($pid);
                                                    while ($row = mysqli_fetch_assoc($lab)) { ?>
                                                        <tr>
                                                            <td><?= $sn ?></td>
                                                            <td>
                                                                <p><?= $row['test_name'] ?></p>
                                                            </td>
                                                            <td class="text-center"><a href="#" onclick="deleteLab(<?= htmlspecialchars(json_encode($row['op_lab_id'], JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP), ENT_QUOTES, 'UTF-8') ?>,<?= htmlspecialchars(json_encode($pid, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP), ENT_QUOTES, 'UTF-8') ?>)" data-toggle="tooltip" title="Delete Test" class="badge badge-pill badge-danger"><i class="ri-close-line "></i></a></td>

                                                        </tr>
                                                    <?php $sn++;
                                                    } ?>
                                                </tbody>
                                            </table>
                                            <textarea class="form-control mt-3" cols='1' rows='2' placeholder="Enter Radiology Observation...." name='radio_obs'><?= $exam_row['radiology_observation'] ?></textarea>

                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">

                                        <div class="col-md-12 mb-2">
                                            <h5>Immunization Record</h5>
                                            <hr />
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Select Immunization</label>

                                                <!-- UI-only: the per-vaccine rows below submit immunization[] so it stays
                 aligned with manufacturer[]/lot_number[]/dose_number[] -->
                                                <select id="immunization"
                                                    class="form-control selectpicker"
                                                    multiple
                                                    data-live-search="true"
                                                    style="width: 100%">

                                                    <optgroup label="At Birth">
                                                        <option value="BCG">BCG</option>
                                                        <option value="OPV-0">OPV-0 (Oral Polio)</option>
                                                        <option value="Hepatitis B-0">Hepatitis B-0</option>
                                                    </optgroup>

                                                    <optgroup label="6 Weeks">
                                                        <option value="DPT-1">DPT-1 / Pentavalent</option>
                                                        <option value="IPV-1">IPV-1</option>
                                                        <option value="Hib-1">Hib-1</option>
                                                        <option value="Hepatitis B-1">Hepatitis B-1</option>
                                                        <option value="Rotavirus-1">Rotavirus-1</option>
                                                        <option value="PCV-1">PCV-1</option>
                                                    </optgroup>

                                                    <optgroup label="10 Weeks">
                                                        <option value="DPT-2">DPT-2</option>
                                                        <option value="IPV-2">IPV-2</option>
                                                        <option value="Hib-2">Hib-2</option>
                                                        <option value="Rotavirus-2">Rotavirus-2</option>
                                                        <option value="PCV-2">PCV-2</option>
                                                    </optgroup>

                                                    <optgroup label="14 Weeks">
                                                        <option value="DPT-3">DPT-3</option>
                                                        <option value="IPV-3">IPV-3</option>
                                                        <option value="Hib-3">Hib-3</option>
                                                        <option value="Hepatitis B-2">Hepatitis B-2</option>
                                                        <option value="Rotavirus-3">Rotavirus-3</option>
                                                    </optgroup>

                                                    <optgroup label="6 Months">
                                                        <option value="OPV-booster">OPV Booster</option>
                                                        <option value="Hepatitis B-3">Hepatitis B-3</option>
                                                    </optgroup>

                                                    <optgroup label="9 Months">
                                                        <option value="Measles/MR-1">Measles / MR-1</option>
                                                        <option value="JE-1">JE-1</option>
                                                    </optgroup>

                                                    <optgroup label="12 Months">
                                                        <option value="PCV-booster">PCV Booster</option>
                                                        <option value="MMR-1">MMR-1</option>
                                                    </optgroup>

                                                    <optgroup label="15-18 Months">
                                                        <option value="DPT-booster-1">DPT Booster-1</option>
                                                        <option value="Hib-booster">Hib Booster</option>
                                                        <option value="IPV-booster">IPV Booster</option>
                                                        <option value="MMR-2">MMR-2</option>
                                                        <option value="Varicella-1">Varicella-1</option>
                                                        <option value="Hepatitis A-1">Hepatitis A-1</option>
                                                    </optgroup>

                                                    <optgroup label="2 Years">
                                                        <option value="Typhoid Conjugate Vaccine">Typhoid Conjugate Vaccine</option>
                                                    </optgroup>

                                                    <optgroup label="4–6 Years">
                                                        <option value="DPT-booster-2">DPT Booster-2</option>
                                                        <option value="OPV-booster-2">OPV Booster</option>
                                                        <option value="Varicella-2">Varicella-2</option>
                                                        <option value="MMR-3">MMR-3</option>
                                                    </optgroup>

                                                    <optgroup label="9–14 Years">
                                                        <option value="HPV-1">HPV Dose-1</option>
                                                        <option value="HPV-2">HPV Dose-2</option>
                                                    </optgroup>

                                                    <optgroup label="Adults">
                                                        <option value="Influenza">Influenza (Yearly)</option>
                                                        <option value="Hepatitis B Adult">Hepatitis B (Adult)</option>
                                                        <option value="Typhoid Booster">Typhoid Booster</option>
                                                        <option value="Tdap">Tdap</option>
                                                        <option value="COVID-19">COVID-19</option>
                                                    </optgroup>

                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Next Vaccination Date</label>
                                                <input type="date" name="vacc_date" class="form-control" min="<?= date('Y-m-d') ?>">
                                            </div>
                                        </div>

                                        <!-- Per-vaccine details (manufacturer / lot no / dose no) -->
                                        <div class="col-md-12" id="immuDetailsWrap" style="display:none;">
                                            <table class="table table-bordered table-sm" id="immuDetails">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th style="width:5%">Sl.No.</th>
                                                        <th style="width:25%">Vaccine</th>
                                                        <th style="width:25%">Manufacturer</th>
                                                        <th style="width:25%">Lot / Batch No.</th>
                                                        <th style="width:20%">Dose No.</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="immuDetailsBody"></tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-12">
                                            <table class="table table-bordered" id="loadLab">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th>Sl.No.</th>
                                                        <th>Immuniztion</th>
                                                        <th>Manufacturer</th>
                                                        <th>Lot / Batch No.</th>
                                                        <th>Dose No.</th>
                                                        <th>Next Date</th>
                                                        <!--<th class="text-center">Action</th>-->
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $sn = 1;
                                                    $lab = $newTreat->getPatientImmunization($pid);
                                                    while ($row = mysqli_fetch_assoc($lab)) { ?>
                                                        <tr>
                                                            <td><?= $sn ?></td>
                                                            <td>
                                                                <p><?= $row['immunization_name'] ?></p>
                                                            </td>
                                                            <td><?= htmlspecialchars($row['manufacturer'] ?? '') ?></td>
                                                            <td><?= htmlspecialchars($row['lot_number'] ?? '') ?></td>
                                                            <td><?= htmlspecialchars($row['dose_number'] ?? '') ?></td>
                                                            <td>
                                                                <?php // Legacy rows hold 0000-00-00 for "no next dose" — show nothing. ?>
                                                                <p><?= (empty($row['next_date']) || strpos((string) $row['next_date'], '0000-00-00') === 0) ? '—' : htmlspecialchars($row['next_date']) ?></p>
                                                            </td>

                                                        </tr>
                                                    <?php $sn++;
                                                    } ?>
                                                </tbody>
                                            </table>

                                        </div>
                                    </div>

                                    <hr>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>Medical Advice</label>
                                            <textarea class="form-control mt-3" cols='1' rows='3' placeholder="Enter Medical Advices...." name='medical_advice'><?= $exam_row['medical_advice'] ?></textarea>
                                        </div>
                                        <div class="col-md-12 mt-3">
                                            <label>Advice</label>
                                            <select name="advice[]" multiple id="advice" class="form-control selectpicker" placeholder="Select Advice" data-live-search="true">

                                                <?php lookupOptions($newTreat->getAdvice(), 'advice', $exam_row['advice'] ?? ''); ?>


                                            </select>

                                        </div>

                                        <hr>
                                        <div class="col-md-12 mt-4">

                                            <div class="row d-flex align-items-center ">
                                                <div class="col-md-4 ">
                                                    <lable>Next Visit </lable>

                                                    <div class="input-group mb-3">
                                                        <input type="number" min="0" class="form-control mt-2" name="day" placeholder="Days" aria-label="Recipient's username" aria-describedby="basic-addon2">
                                                        <div class="input-group-append">

                                                            <div class="btn-group btn-group-toggle mt-2 text-center" data-toggle="buttons">
                                                                <label class="btn btn-outline-secondary active">
                                                                    <input type="radio" checked name="indays" id="day" value="days"> Days
                                                                </label>
                                                                <label class="btn btn-outline-secondary">
                                                                    <input type="radio" name="indays" id="week" value="weeks"> Weeks
                                                                </label>
                                                                <label class="btn btn-outline-secondary">
                                                                    <input type="radio" name="indays" id="month" value="months"> Months
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>



                                                </div>
                                                <div class="col-md-1 mt-3 text-center">
                                                    <h5>OR</h5>
                                                </div>
                                                <div class="col-md-3">

                                                    <div class="form-group">
                                                        <lable>Select Date</lable>
                                                        <input class="form-control" value="<?php if ($exam_row['next_visit_date'] > date('Y-m-d')) echo $exam_row['next_visit_date']; ?>" type="date" minvalue="<?php echo date('d-m-Y') ?>" name="nextdate">
                                                    </div>

                                                </div>
                                                <div class="col-md-3">

                                                    <div class="form-group">
                                                        <lable>Refer To.</lable>
                                                        <select class="form-control selectpicker" name="refer_to" id="refer_to">
                                                            <option value="">Select Doctor</option>
                                                            <?php
                                                            $docto = $newTreat->getDoctor();

                                                            while ($row = mysqli_fetch_assoc($docto)) {
                                                            ?>
                                                                <option <?php if ($exam_row['referred_to'] == $row['id']) {
                                                                            echo "selected";
                                                                        } ?> value="<?= $row['id'] ?>"><?= $row['name'] . ' ' . $row['last_name'] ?></option>
                                                            <?php } ?>

                                                        </select>
                                                    </div>

                                                </div>


                                            </div>
                                            <div class="row">
                                                <input type="hidden" name="pid" value="<?= htmlspecialchars($pid, ENT_QUOTES, 'UTF-8') ?>">
                                                <!--<button type="submit" class="btn btn-success mr-3 mt-4" name="submit" value="invest">Add</button>-->
                                                <div class="col-md-12">

                                                    <button class="btn btn-success float-right ml-2 mr-2" name="sumbit" type="submit" value="print">Save & Print</button>
                                                    <button class="btn btn-warning float-right ml-2 mr-2" name="sumbit" type="submit" value="save">Save</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>



                                </div>
                                <!--End Container Fluied-->
                            </div>
                        </div>
                    </div>
                </form>
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

            <!--<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>-->
            <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script>

            <!-- Summernote js -->
            <script src="assets/libs/summernote/summernote-bs4.min.js"></script>
            <!--tinymce js-->
            <script src="assets/libs/tinymce/tinymce.min.js"></script>

            <!-- init js -->
            <script src="assets/js/pages/form-editor.init.js"></script>

            <script>
                $(".selectpicker").select2({
                    tags: true
                });

                // Build per-vaccine rows (manufacturer / lot / dose) from the immunization select.
                (function() {
                    // These are clinical identifiers, not prose. Each field accepts only
                    // the characters its value can legitimately contain, and must hold at
                    // least one letter or digit — so a mis-keyed "!@#$%^" is caught here
                    // instead of being stored as a vaccination record. The same rules are
                    // enforced again in optreatmentClass1::insertImmunization().
                    var IMMU_RULES = {
                        'manufacturer[]': {
                            label: 'Manufacturer',
                            re: /^[A-Za-z0-9 .,&()\/-]+$/,
                            max: 150,
                            hint: 'letters, digits and . , & ( ) / -'
                        },
                        'lot_number[]': {
                            label: 'Lot / Batch No.',
                            re: /^[A-Za-z0-9 _\/-]+$/,
                            max: 100,
                            hint: 'letters, digits and _ / -'
                        },
                        'dose_number[]': {
                            label: 'Dose No.',
                            re: /^[A-Za-z0-9 \/-]+$/,
                            max: 50,
                            hint: 'e.g. 1, 2nd or "single booster"'
                        }
                    };

                    // '' when acceptable, otherwise why not. Blank is allowed — these
                    // details are often not to hand; only junk is refused.
                    function immuFieldError(name, value) {
                        var rule = IMMU_RULES[name];
                        var v = String(value == null ? '' : value).trim();
                        if (!rule || v === '') return '';
                        if (v.length > rule.max) return rule.label + ' is too long (max ' + rule.max + ' characters).';
                        if (!/[A-Za-z0-9]/.test(v)) return rule.label + ' must contain at least one letter or digit.';
                        if (!rule.re.test(v)) return rule.label + ' has invalid characters — use ' + rule.hint + '.';
                        return '';
                    }
                    window.immuFieldError = immuFieldError;

                    function markImmuField($el, msg) {
                        $el.css('border-color', msg ? '#e53e3e' : '').attr('title', msg || '');
                    }
                    $(document).on('input blur', '#immuDetailsBody input[type="text"]', function() {
                        markImmuField($(this), immuFieldError(this.name, this.value));
                    });

                    // Nothing reaches the controller until every row is clean.
                    $(document).on('submit', 'form[action*="processOPTreat"]', function(e) {
                        var problems = [];
                        $('#immuDetailsBody input[type="text"]').each(function() {
                            var msg = immuFieldError(this.name, this.value);
                            markImmuField($(this), msg);
                            if (msg && problems.indexOf(msg) === -1) problems.push(msg);
                        });
                        // A NEXT vaccination date in the past is not a next date. Blank is
                        // fine — it is stored as "no next dose" rather than 0000-00-00.
                        var $vd = $('input[name="vacc_date"]');
                        var vd = ($vd.val() || '').trim();
                        if (vd && $('#immunization').val() && $('#immunization').val().length) {
                            var today = new Date();
                            today.setHours(0, 0, 0, 0);
                            var picked = new Date(vd + 'T00:00:00');
                            if (isNaN(picked.getTime())) {
                                problems.push('Next vaccination date is not a valid date.');
                            } else if (picked < today) {
                                problems.push('Next vaccination date cannot be in the past.');
                            }
                            markImmuField($vd, problems.length ? 'Check this date' : '');
                        }
                        if (problems.length) {
                            e.preventDefault();
                            alert('Please correct the immunization details:\n\n• ' + problems.join('\n• '));
                            $('#immuDetailsBody input[style*="rgb(229"]').first().focus();
                            return false;
                        }
                    });

                    function rebuildImmuRows() {
                        var sel = $('#immunization').val() || [];
                        var prev = {};
                        $('#immuDetailsBody tr').each(function() {
                            var v = $(this).attr('data-vac');
                            prev[v] = {
                                m: $(this).find('input[name="manufacturer[]"]').val(),
                                l: $(this).find('input[name="lot_number[]"]').val(),
                                d: $(this).find('input[name="dose_number[]"]').val()
                            };
                        });
                        var $body = $('#immuDetailsBody').empty();
                        if (!sel.length) {
                            $('#immuDetailsWrap').hide();
                            return;
                        }
                        $('#immuDetailsWrap').show();
                        sel.forEach(function(vac, i) {
                            var esc = $('<div>').text(vac).html();
                            var p = prev[vac] || {};
                            $body.append(
                                '<tr data-vac="' + esc + '">' +
                                '<td>' + (i + 1) + '</td>' +
                                '<td>' + esc + '<input type="hidden" name="immunization[]" value="' + esc + '"></td>' +
                                '<td><input type="text" class="form-control form-control-sm" name="manufacturer[]" value="' + (p.m || '') + '" placeholder="Manufacturer" maxlength="150" pattern="(?=.*[A-Za-z])[A-Za-z0-9 .,&amp;\(\)\/&#39;\-]+" title="Letters are required. Numbers may be used along with letters (e.g. 3M Health Care). A number-only name is not allowed."></td>' +
                                '<td><input type="text" class="form-control form-control-sm" name="lot_number[]" value="' + (p.l || '') + '" placeholder="Lot / Batch No." maxlength="100" pattern="[A-Za-z0-9 _\\/\\-]+" title="Letters, digits and _ / -"></td>' +
                                '<td><input type="text" class="form-control form-control-sm" name="dose_number[]" value="' + (p.d || '') + '" placeholder="e.g. 1" maxlength="50" pattern="[A-Za-z0-9 \\/\\-]+" title="e.g. 1, 2nd or single booster"></td>' +
                                '</tr>'
                            );
                        });
                    }
                    $('#immunization').on('change', rebuildImmuRows);

                    // ── Family History rows ─────────────────────────────────
                    // Relations kept in sync with $fhRelations in the PHP above.
                    var FH_RELATIONS = <?= json_encode($fhRelations, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;

                    function fhRowHtml() {
                        var opts = '<option value="">-- select --</option>';
                        FH_RELATIONS.forEach(function(r) {
                            opts += '<option value="' + r + '">' + r + '</option>';
                        });
                        var yesNo = '<option value="No">No</option><option value="Yes">Yes</option>';
                        return '<tr>' +
                            '<td><select name="fh_relation[]" class="form-control form-control-sm">' + opts + '</select></td>' +
                            '<td><input type="text" name="fh_condition[]" class="form-control form-control-sm" maxlength="150" placeholder="e.g. Hypertension"></td>' +
                            '<td><input type="number" name="fh_age[]" class="form-control form-control-sm" min="0" max="120" placeholder="yrs"></td>' +
                            '<td><select name="fh_deceased[]" class="form-control form-control-sm">' + yesNo + '</select></td>' +
                            '<td><select name="fh_contributed[]" class="form-control form-control-sm">' + yesNo + '</select></td>' +
                            '<td class="text-center"><button type="button" class="btn btn-sm btn-link text-danger fh-del" title="Remove">&times;</button></td>' +
                            '</tr>';
                    }

                    $('#fhAdd').on('click', function() {
                        $('#fhBody').append(fhRowHtml());
                    });
                    $(document).on('click', '.fh-del', function() {
                        $(this).closest('tr').remove();
                    });

                    // A half-filled row reaches the patient's PHR as a card of "NA"s,
                    // which is exactly what this form exists to prevent. Validated here
                    // rather than with `required` attributes because step 1 is
                    // display:none when saving from a later step, and Chrome refuses to
                    // submit a form holding an invalid control it cannot focus.
                    $('form').on('submit', function(e) {
                        var problems = [];
                        $('#fhBody tr').each(function(i) {
                            var rel  = $.trim($(this).find('select[name="fh_relation[]"]').val() || '');
                            var cond = $.trim($(this).find('input[name="fh_condition[]"]').val() || '');
                            var age  = $.trim($(this).find('input[name="fh_age[]"]').val() || '');
                            if (rel === '' && cond === '' && age === '') { return; }   // untouched row
                            if (rel === '')  { problems.push('Row ' + (i + 1) + ': choose a Relation.'); }
                            if (cond === '') { problems.push('Row ' + (i + 1) + ': enter a Condition.'); }
                            if (age === '')  { problems.push('Row ' + (i + 1) + ': enter an Age (the patient app shows "NA" without it).'); }
                        });
                        if (problems.length) {
                            e.preventDefault();
                            alert('Family History:\n\n• ' + problems.join('\n• '));
                        }
                    });
                })();

                tinymce.init({

                    selector: "#textarea1",
                    toolbar_mode: 'sliding',
                    height: 300

                });
                tinymce.init({

                    selector: "#textarea2",
                    toolbar_mode: 'sliding',
                    height: 300

                });
                tinymce.init({

                    selector: "#textarea3",
                    toolbar_mode: 'sliding',
                    height: 300

                });
            </script>

            <script>
                function addMedic(val, i) {
                    const options = $('#medicines' + i + ' option');
                    const filter = val.toLowerCase();

                    options.each(function() {
                        const optionValue = $(this).val().toLowerCase();
                        const startsWithFilter = optionValue.startsWith(filter);

                        $(this).toggle(startsWithFilter);
                    });
                    i++;
                    $('#addr' + i).html("<td><div class='input-group'><input class='form-control' list='medicines" + i + "' name='medicine[]' id='medicine" + i + "' onchange='addMedic(this.value," + i + ")'><div class='input-group-append'><button class='btn btn-outline-secondary' type='button' onclick='openMedicineSearch(" + i + ")'>Search</button></div></div><datalist id='medicines" + i + "'><option value='' >Select Medicine</option><?php $medi = $newTreat->getdoctMedic();
                                                                                                                                                                                                                                                                    while ($medic = mysqli_fetch_assoc($medi)) { ?><option value='<?= $medic['name'] ?>'></option><?php } ?></datalist></td> <td><input type='text'  list='dosage" + i + "' class='form-control' name='dose[]' id='dose" + i + "' onchange='calQty(" + i + ")'><datalist id='dosage" + i + "'><option value=''>Select</option><option value='1-0-0'>1-0-0</option><option value='0-0-1'>0-0-1</option><option value='1-0-1'>1-0-1</option><option value='1-1-1'>1-1-1</option><option value='1-1-0'>1-1-0</option><option value='0-1-0'>0-1-0</option><option value='0-1-1'>0-1-1</option><option value='0-0-0-1'>0-0-0-1</option></datalist></td> <td><select class='form-control' name='food[]' id='food" + i + "'><option value=''>Select</option><option value='Before Food'>Before Food</option><option value='After Food'>After Food</option><option value='Before Breakfast'>Before Breakfast</option><option value='After Breakfast'>After Breakfast</option><option value='Before Lunch'>Before Lunch</option><option value='After Lunch'>After Lunch</option><option value='Before Dinner'>Before Dinner</option><option value='After Dinner'>After Dinner</option><option value='Empty Stomach'>Empty Stomach</option><option value='Bed Time'>Bed Time</option><option value='SOS'>SOS</option></select></td> <td><select class='form-control' name='frequency[]' id='frequency" + i + "'><option value=''>Select</option><option value='Daily'>Daily</option><option value='Alternative Days'>Alternative Days</option><option value='Weekly'>Weekly</option><option value='Fort Night'>Fort Night</option><option value='Monthly'>Monthly</option></select></td> <td><input type='text' class='form-control' list='day" + i + "' name='days[]' id='days" + i + "' onkeyup='showResult(this.value," + i + ");calQty(" + i + ")' autocomplete='off' placeholder='Enter Medicine Name'><datalist id='day" + i + "'><option><span class='span_day" + i + "'></span> days</option><option><span class='span_day" + i + "'></span> weeks</option><option><span class='span_day" + i + "'></span> months</option><option><span class='span_day" + i + "'></span> Years</option></datalist></td> <td><input class='form-control' name='qty[]' id='qty" + i + "'></td> <td><input class='form-control' name='inst[]' id='inst" + i + "' placeholder='Enter Instructions'></td><td><button class='btn btn-danger' type='button' onclick='dlt(" + i + ")'>Delete</button><input type='hidden' name='medicine_id[]' id='medicine_id" + i + "' value=''></td>");
                    $('#loadMedic').append('<tr id="addr' + (i + 1) + '"></tr>');
                    i--;
                    $.ajax({
                        type: 'post',
                        url: 'ajax.php', //Here you will fetch records 
                        data: 'medicine=' + val + '&page=doc_medicine',
                        success: function(data) {
                            s = data.split('|');
                            document.getElementById("dose" + i).value = s[1];
                            document.getElementById("food" + i).value = s[2];
                            document.getElementById("frequency" + i).value = s[3];
                            document.getElementById("days" + i).value = s[4];
                            document.getElementById("qty" + i).value = s[0];
                            document.getElementById("medicine_id" + i).value = s[5];
                        }
                    });
                }

                function dlt(id) {
                    if (id > 1) {
                        $("#addr" + (id)).html('');
                    }

                }

                function dltold(id) {

                    $("#add" + (id)).html('');


                }

                function getIcdCode(id, type) {
                    $.ajax({
                        type: 'post',
                        url: 'ajax.php', //Here you will fetch records 
                        data: 'icd=' + id + '&page=icd_code',
                        success: function(data) {
                            document.getElementById(type).value = data;
                        }
                    });
                }

                function deleteLab(val, id) {
                    $.ajax({
                        type: 'post',
                        url: 'ajax.php', //Here you will fetch records 
                        data: 'id=' + val + '&page=op_lab_del', //Pass $id
                        success: function(data) {
                            $('#loadLab').load(" #loadLab"); //Show fetched data from database
                        }
                    });
                }

                function deleteMedicine(val) {
                    $.ajax({
                        type: 'post',
                        url: 'ajax.php', //Here you will fetch records 
                        data: 'id=' + val + '&page=medic_del', //Pass $id
                        success: function(data) {
                            $('#loadMedic').load(" #loadMedic"); //Show fetched data from database
                        }
                    });
                }

                function showResult(val, i) {
                    var numericPart = val.replace(/\D/g, "");
                    var elements = document.getElementsByClassName("span_day" + i);
                    for (var i = 0; i < elements.length; i++) {
                        elements[i].innerHTML = numericPart;
                    }
                }
            </script>
            <script>
                function calQty(val) {
                    // var dose=document.getElementById('dose'+val).value);


                    var doseString = document.getElementById('dose' + val).value;
                    var doseArray = doseString.split('-');
                    var sum = 0;
                    for (var i = 0; i < doseArray.length; i++) {
                        sum += parseInt(doseArray[i]);
                    }


                    var stringValue = document.getElementById('days' + val).value;

                    var numericValue = parseInt(stringValue.match(/\d+/)[0]);
                    //  alert(numericValue)
                    //  var textValue = stringValue.split(' ')[1];

                    if (stringValue.toLowerCase().includes('days')) {
                        var days = 1;
                    } else if (stringValue.toLowerCase().includes('weeks')) {
                        var days = 7;
                    } else if (stringValue.toLowerCase().includes('months')) {
                        var days = 30;
                    } else {
                        var days = 365;
                    }
                    //  alert(sum*numericValue*days)
                    document.getElementById('qty' + val).value = sum * numericValue * days;
                }
            </script>
            <script>
                $('#lab').select2({
                    placeholder: "Select test",
                    allowClear: true,
                    width: '100%',
                    data: countries
                });

                $('#lab').on('select2:open', function(e) {

                    $('#select2-lab-results').on('click', function(event) {

                        event.stopPropagation();
                        var data = $(event.target).html();
                        var selectedOptionGroup = data.toString().trim();

                        var groupchildren = [];

                        for (var i = 0; i < countries.length; i++) {


                            if (selectedOptionGroup.toString() === countries[i].text.toString()) {

                                for (var j = 0; j < countries[i].children.length; j++) {

                                    groupchildren.push(countries[i].children[j].id);

                                }

                            }


                        }


                        var options = [];

                        options = $('#lab').val();

                        if (options === null || options === '') {

                            options = [];

                        }

                        for (var i = 0; i < groupchildren.length; i++) {

                            var count = 0;

                            for (var j = 0; j < options.length; j++) {

                                if (options[j].toString() === groupchildren[i].toString()) {

                                    count++;
                                    break;

                                }

                            }

                            if (count === 0) {
                                options.push(groupchildren[i].toString());
                            }
                        }

                        $('#lab').val(options);
                        $('#lab').trigger('change'); // Notify any JS components that the value changed
                        $('#lab').select2('close');

                    });
                });
            </script>


            <script>
                $('#radio_lab').select2({
                    placeholder: "Select radiology test",
                    allowClear: true,
                    width: '100%',
                    data: radioTests
                });

                $('#radio_lab').on('select2:open', function(e) {
                    $('#select2-radio_lab-results').on('click', function(event) {
                        event.stopPropagation();
                        var data = $(event.target).html();
                        var selectedOptionGroup = data.toString().trim();

                        var groupchildren = [];

                        for (var i = 0; i < radioTests.length; i++) {
                            if (selectedOptionGroup === radioTests[i].text.toString()) {
                                for (var j = 0; j < radioTests[i].children.length; j++) {
                                    groupchildren.push(radioTests[i].children[j].id);
                                }
                            }
                        }

                        var options = $('#radio_lab').val();
                        if (options === null || options === '') {
                            options = [];
                        }

                        for (var i = 0; i < groupchildren.length; i++) {
                            if (!options.includes(groupchildren[i].toString())) {
                                options.push(groupchildren[i].toString());
                            }
                        }

                        $('#radio_lab').val(options);
                        $('#radio_lab').trigger('change');
                        $('#radio_lab').select2('close');
                    });
                });
            </script>
<script>
    // ── OP Treatment step-wise wizard ─────────────────────────────────────────
    // Groups the existing form sections into steps and shows one at a time.
    // Pure client-side: every field stays in the DOM, so the form submits exactly
    // as before. If a section can't be located it simply stays visible.
    (function () {
        function init() {
            // Match processOPTreat.php AND processOPTreat1.php — an install still
            // posting to the un-suffixed controller would otherwise miss the wizard
            // entirely and render every step on one page.
            var form = document.querySelector('form[action*="processOPTreat"]');
            if (!form || form.__opWizard) return;
            form.__opWizard = true;
            var cardBody = form.querySelector('.card-body') || form;

            var steps = [
                { t: 'History & Complaints' }, { t: 'Vitals' }, { t: 'Wellness / Lifestyle' },
                { t: 'Examination & Diagnosis' }, { t: 'Prescription' }, { t: 'Investigations' },
                { t: 'Immunization' }, { t: 'Advice & Review' }
            ];
            var maxStep = steps.length;

            function norm(s) { return (s || '').replace(/\s+/g, ' ').trim().toLowerCase(); }
            function has(el, sel) { try { return !!el.querySelector(sel); } catch (e) { return false; } }
            function h5txt(el) { var h = el.querySelector('h5'); return h ? norm(h.textContent) : ''; }

            // Classify a section element to its step number purely by the fields /
            // heading it contains (robust against the exact wrapper nesting).
            function classify(el) {
                if (has(el, '[name="como[]"]') || has(el, '[name="hyper[]"]') || has(el, '[name="complaint[]"]') ||
                    has(el, '[name="associated[]"]') || has(el, '[name="history"]') || has(el, '[name="fh_relation[]"]') || has(el, '#fhTable')) return 1;
                var h = h5txt(el);
                if (h.indexOf('vitals') === 0 || has(el, '[name="bp"]') || has(el, '[name="weight"]') || has(el, '[name="bmi"]')) return 2;
                if (h.indexOf('wellness') === 0 || has(el, '[name="smoking"]') || has(el, '[name="diet"]') || has(el, '[name="steps"]') || has(el, '[name="lmp"]')) return 3;
                if (h.indexOf('examination') === 0 || has(el, '[name="examination"]') || has(el, '[name="diagnosis[]"]')) return 4;
                if (h.indexOf('prescription') >= 0 || has(el, '#loadMedic') || has(el, '[name="medicine[]"]')) return 5;
                if (h.indexOf('lab') === 0 || h.indexOf('radiology') === 0 || has(el, '[name="lab_test[]"]') || has(el, '[name="radio_lab_test[]"]')) return 6;
                if (h.indexOf('immunization') === 0 || has(el, '#immunization') || has(el, '[name="immunization[]"]')) return 7;
                if (has(el, '[name="medical_advice"]') || has(el, '[name="nextdate"]') || has(el, '[name="refer_to"]')) return 8;
                return 0;
            }

            // The sections live as children of the row that holds Comorbidities, but
            // several are grouped inside an inner wrapper. Walk into any element that
            // holds MORE THAN ONE section heading, otherwise treat it as a leaf.
            var comaEl = form.querySelector('[name="como[]"]');
            var container = comaEl ? comaEl.closest('.row') : null;
            if (!container) return;
            var candidates = [];
            (function collect(parent) {
                Array.prototype.forEach.call(parent.children, function (ch) {
                    if (ch.nodeType !== 1) return;
                    if (ch.querySelectorAll('h5').length > 1) collect(ch);
                    else candidates.push(ch);
                });
            })(container);
            candidates.forEach(function (el) { var s = classify(el); if (s > 0) el.setAttribute('data-op-step', s); });

            // A section's own <h5> heading / <hr> dividers sit OUTSIDE its tagged
            // block (they are bare children of the shared wrapper), so without this
            // they would leak onto every step. Attach each leading heading/divider
            // to the step it introduces by walking back over untagged h5/hr siblings.
            Array.prototype.forEach.call(form.querySelectorAll('[data-op-step]'), function (el) {
                var s = el.getAttribute('data-op-step');
                var p = el.previousElementSibling;
                while (p && !p.hasAttribute('data-op-step') && (p.tagName === 'H5' || p.tagName === 'HR')) {
                    p.setAttribute('data-op-step', s);
                    p = p.previousElementSibling;
                }
            });

            var stepped = Array.prototype.slice.call(form.querySelectorAll('[data-op-step]'));
            if (!stepped.length) return; // nothing matched — leave the form untouched

            // Progress + stepper + step title, inserted right after the patient banner
            var banner = cardBody.querySelector('h5');
            var head = document.createElement('div');
            var pills = steps.map(function (d, i) {
                return '<button type="button" class="op-pill" data-goto="' + (i + 1) + '">' +
                       '<span class="op-pill-num"><span>' + (i + 1) + '</span></span>' +
                       '<span class="op-pill-txt">' + d.t + '</span></button>';
            }).join('');
            head.innerHTML = '<div class="op-progress"><div></div></div><div class="op-stepper">' + pills + '</div>' +
                             '<div class="op-step-head"><span class="op-step-idx">Step 1</span><span class="op-step-title"></span></div>';
            var anchor = banner;
            if (banner && banner.nextElementSibling && banner.nextElementSibling.tagName === 'HR') anchor = banner.nextElementSibling;
            if (anchor) anchor.insertAdjacentElement('afterend', head); else cardBody.insertBefore(head, cardBody.firstChild);
            var progress = head.querySelector('.op-progress > div');
            var stepTitle = head.querySelector('.op-step-title');
            var stepIdx = head.querySelector('.op-step-idx');
            var pillEls = head.querySelectorAll('.op-pill');

            // Sticky nav bar at FORM level (never inside a step) + relocate Save buttons
            var navbar = document.createElement('div');
            navbar.className = 'op-navbar';
            var prevBtn = document.createElement('button');
            prevBtn.type = 'button'; prevBtn.className = 'btn op-prev'; prevBtn.innerHTML = '← Previous';
            var nextBtn = document.createElement('button');
            nextBtn.type = 'button'; nextBtn.className = 'btn op-next'; nextBtn.innerHTML = 'Next →';
            var spacer = document.createElement('div'); spacer.className = 'op-spacer';
            navbar.appendChild(prevBtn);
            navbar.appendChild(spacer);
            var saveBtns = Array.prototype.slice.call(form.querySelectorAll('[name="sumbit"]'));
            saveBtns.forEach(function (b) { b.classList.remove('float-right'); navbar.appendChild(b); });
            navbar.appendChild(nextBtn);
            form.appendChild(navbar);

            var current = 1;
            function show(n) {
                current = Math.max(1, Math.min(maxStep, n));
                stepped.forEach(function (el) {
                    var match = parseInt(el.getAttribute('data-op-step'), 10) === current;
                    el.classList.toggle('op-step-hidden', !match);
                    el.style.removeProperty('display'); // clear any stale inline display
                });
                pillEls.forEach(function (p) {
                    var s = parseInt(p.getAttribute('data-goto'), 10);
                    p.classList.toggle('active', s === current);
                    p.classList.toggle('done', s < current);
                });
                progress.style.width = (maxStep > 1 ? Math.round((current - 1) / (maxStep - 1) * 100) : 100) + '%';
                stepIdx.textContent = 'Step ' + current;
                stepTitle.textContent = steps[current - 1].t;
                prevBtn.style.display = current > 1 ? '' : 'none';
                nextBtn.style.display = current < maxStep ? '' : 'none';
                try { cardBody.scrollIntoView({ behavior: 'smooth', block: 'start' }); } catch (e) {}
            }
            prevBtn.addEventListener('click', function () { show(current - 1); });
            nextBtn.addEventListener('click', function () { show(current + 1); });
            // explicit per-pill handlers (in addition to delegation) for reliability
            Array.prototype.forEach.call(pillEls, function (p) {
                p.addEventListener('click', function () { show(parseInt(p.getAttribute('data-goto'), 10)); });
            });
            show(1);
        }
        // Run after full load (so select2 / tinymce initialise at full size first).
        if (document.readyState === 'complete') setTimeout(init, 200);
        else window.addEventListener('load', function () { setTimeout(init, 200); });
    })();
</script>
<script>
// Restrict clinical/vitals inputs to numeric values only (accept only numbers where needed).
(function () {
    function attach(ids, sanitize, mode) {
        ids.forEach(function (id) {
            var el = document.getElementById(id);
            if (!el) return;
            el.setAttribute('inputmode', mode || 'decimal');
            var handler = function () {
                var before = this.value;
                var after = sanitize(before);
                if (after !== before) {
                    var caret = this.selectionStart - (before.length - after.length);
                    this.value = after;
                    try { this.setSelectionRange(caret, caret); } catch (e) {}
                }
            };
            el.addEventListener('input', handler);
            el.addEventListener('blur', handler);
        });
    }
    // Integers only — Pulse Rate, Respiratory Rate, SpO2
    attach(['pr', 'rr', 'spo'], function (v) { return v.replace(/[^0-9]/g, ''); }, 'numeric');
    // Decimals allowed — RBS, Temperature, Height, Waist, Weight, Head Circumference, BMI
    attach(['rbs', 'tp', 'ht', 'waist', 'weight', 'hc', 'bmi'], function (v) {
        v = v.replace(/[^0-9.]/g, '');
        var i = v.indexOf('.');
        if (i !== -1) { v = v.slice(0, i + 1) + v.slice(i + 1).replace(/\./g, ''); }
        return v;
    });
    // Blood Pressure — digits and a single "/" (e.g. 120/80)
    attach(['bp'], function (v) {
        v = v.replace(/[^0-9/]/g, '');
        var i = v.indexOf('/');
        if (i !== -1) { v = v.slice(0, i + 1) + v.slice(i + 1).replace(/\//g, ''); }
        return v;
    });
    // Prescription Qty (integer). Rows are added dynamically, so use event
    // delegation to cover qty1, qty2 … and the pre-filled qty101 fields.
    document.addEventListener('input', function (e) {
        var t = e.target;
        if (!t || t.name !== 'qty[]') return;
        var before = t.value;
        var after = before.replace(/[^0-9]/g, '');
        if (after !== before) {
            var caret = t.selectionStart - (before.length - after.length);
            t.value = after;
            try { t.setSelectionRange(caret, caret); } catch (err) {}
        }
    });
})();
</script>
<!-- Medicine search picker (user-friendly medicine lookup) -->
<div class="modal fade" id="medicineSearchModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Search Medicine</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <input type="text" id="medSearchInput" class="form-control mb-2" placeholder="Type to search medicine..." autocomplete="off">
                <div id="medSearchResults" class="list-group" style="max-height:340px;overflow-y:auto"></div>
                <div id="medSearchEmpty" class="text-muted small mt-2" style="display:none">No medicines found.</div>
            </div>
        </div>
    </div>
</div>
<?php
$__mlist = $newTreat->getdoctMedic();
$__mnames = [];
while ($__m = mysqli_fetch_assoc($__mlist)) {
    if (!empty($__m['name'])) { $__mnames[] = $__m['name']; }
}
$__mnames = array_values(array_unique($__mnames));
?>
<script>
// Medicine search picker — fills the target prescription row's medicine field
// then reuses the existing addMedic() flow (auto-fills dose/qty, adds next row).
var MEDICINE_LIST = <?= json_encode($__mnames, JSON_UNESCAPED_UNICODE) ?>;
var _medSearchRow = 1;
function medEsc(s) {
    return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;').replace(/'/g, '&#39;');
}
function renderMedResults(filter) {
    filter = (filter || '').toLowerCase();
    var out = '', count = 0;
    for (var k = 0; k < MEDICINE_LIST.length; k++) {
        var nm = MEDICINE_LIST[k];
        if (!nm) continue;
        if (filter === '' || String(nm).toLowerCase().indexOf(filter) !== -1) {
            out += "<button type='button' class='list-group-item list-group-item-action med-result' data-name=\"" + medEsc(nm) + "\">" + medEsc(nm) + "</button>";
            if (++count >= 300) break; // cap for performance
        }
    }
    document.getElementById('medSearchResults').innerHTML = out;
    document.getElementById('medSearchEmpty').style.display = count ? 'none' : 'block';
}
function openMedicineSearch(i) {
    _medSearchRow = i;
    var inp = document.getElementById('medSearchInput');
    inp.value = '';
    renderMedResults('');
    $('#medicineSearchModal').modal('show');
    setTimeout(function () { inp.focus(); }, 350);
}
$(document).on('input', '#medSearchInput', function () { renderMedResults(this.value); });
$(document).on('click', '#medSearchResults .med-result', function () {
    var name = $(this).attr('data-name');
    var i = _medSearchRow;
    var field = document.getElementById('medicine' + i);
    if (field) { field.value = name; addMedic(name, i); }
    $('#medicineSearchModal').modal('hide');
});
</script>
</body>

</html>