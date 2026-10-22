<?php
require_once(__DIR__ . '/../model/session_config.php');
include('../model/treatmentClass.php');

$newTreat = new addTreat;
// print_r($_REQUEST);exit;

if ($_REQUEST['submit'] == 'history') {
    $newTreat->settext1($_REQUEST['text1']);
    $newTreat->settext2($_REQUEST['text2']);
    $newTreat->settext3($_REQUEST['text3']);
    $newTreat->settext4($_REQUEST['text4']);
    $newTreat->setvedana($_REQUEST['vedana']);
    $newTreat->setpoorva($_REQUEST['poorva']);
    $newTreat->setchikitsa($_REQUEST['chikitsa']);
    $newTreat->setkautumbika($_REQUEST['kautumbika']);
    $newTreat->setmarital($_REQUEST['marital']);
    $newTreat->setvayaktika($_REQUEST['vayaktika']);

    if (isset($_REQUEST['tobacco'])) {
        $newTreat->settobacco(1);
    } else {
        $newTreat->settobacco(0);
    }
    if (isset($_REQUEST['smok'])) {
        $newTreat->setsmok(1);
    } else {
        $newTreat->setsmok(0);
    }
    if (isset($_REQUEST['alco'])) {
        $newTreat->setalco(1);
    } else {
        $newTreat->setalco(0);
    }
    if (isset($_REQUEST['krodha'])) {
        $newTreat->setkrodha(1);
    } else {
        $newTreat->setkrodha(0);
    }
    if (isset($_REQUEST['shoka'])) {
        $newTreat->setshoka(1);
    } else {
        $newTreat->setshoka(0);
    }
    if (isset($_REQUEST['chittogvega'])) {
        $newTreat->setchittogvega(1);
    } else {
        $newTreat->setchittogvega(0);
    }
    if (isset($_REQUEST['atichintana'])) {
        $newTreat->setatichintana(1);
    } else {
        $newTreat->setatichintana(0);
    }
    if (isset($_REQUEST['vegadharana'])) {
        $newTreat->setvegadharana(1);
    } else {
        $newTreat->setvegadharana(0);
    }
    if (isset($_REQUEST['shift'])) {
        $newTreat->setshift(1);
    } else {
        $newTreat->setshift(0);
    }
    if (isset($_REQUEST['udheerna'])) {
        $newTreat->setudheerna(1);
    } else {
        $newTreat->setudheerna(0);
    }
    if (isset($_REQUEST['frequent'])) {
        $newTreat->setfrequent(1);
    } else {
        $newTreat->setfrequent(0);
    }

    $newTreat->setnidra($_REQUEST['nidra']);
    $newTreat->setagani($_REQUEST['agani']);
    $newTreat->setkoshtas($_REQUEST['koshtas']);

    if (isset($_REQUEST['labour'])) {
        $newTreat->setlabour(1);
    } else {
        $newTreat->setlabour(0);
    }
    if (isset($_REQUEST['field_work'])) {
        $newTreat->setfield_work(1);
    } else {
        $newTreat->setfield_work(0);
    }
    if (isset($_REQUEST['sedantary'])) {
        $newTreat->setsedantary(1);
    } else {
        $newTreat->setsedantary(0);
    }
    if (isset($_REQUEST['intellectual'])) {
        $newTreat->setintellectual(1);
    } else {
        $newTreat->setintellectual(0);
    }
    if (isset($_REQUEST['job_Stress'])) {
        $newTreat->setjob_Stress(1);
    } else {
        $newTreat->setjob_Stress(0);
    }

    $newTreat->setmenarche($_REQUEST['menarche']);

    if (isset($_REQUEST['regular'])) {
        $newTreat->setregular(1);
    } else {
        $newTreat->setregular(0);
    }
    if (isset($_REQUEST['irregular'])) {
        $newTreat->setirregular(1);
    } else {
        $newTreat->setirregular(0);
    }
    if (isset($_REQUEST['painful'])) {
        $newTreat->setpainful(1);
    } else {
        $newTreat->setpainful(0);
    }
    if (isset($_REQUEST['scanty'])) {
        $newTreat->setscanty(1);
    } else {
        $newTreat->setscanty(0);
    }
    if (isset($_REQUEST['clots'])) {
        $newTreat->setclots(1);
    } else {
        $newTreat->setclots(0);
    }

    $newTreat->setday($_REQUEST['day']);

    if (isset($_REQUEST['gravida'])) {
        $newTreat->setgravida(1);
    } else {
        $newTreat->setgravida(0);
    }
    if (isset($_REQUEST['parity'])) {
        $newTreat->setparity(1);
    } else {
        $newTreat->setparity(0);
    }
    if (isset($_REQUEST['abortion'])) {
        $newTreat->setabortion(1);
    } else {
        $newTreat->setabortion(0);
    }


    if (isset($_REQUEST['ftnd'])) {
        $newTreat->setftnd(1);
    } else {
        $newTreat->setftnd(0);
    }
    if (isset($_REQUEST['lscs'])) {
        $newTreat->setlscs(1);
    } else {
        $newTreat->setlscs(0);
    }
    if (isset($_REQUEST['fdhd'])) {
        $newTreat->setfdhd(1);
    } else {
        $newTreat->setfdhd(0);
    }
    if ($_REQUEST['edit'] == 1) {
        $newTreat->updateHistory($_REQUEST['pid']);
    } else {
        $newTreat->insertHistory($_REQUEST['pid']);
    }
    header("Location:../iphistrory.php?id=$_REQUEST[pid]#v-pills-examination-tab");
}

if ($_REQUEST['submit'] == 'examination') {
    // print_r($_REQUEST);exit;

    $newTreat->setpulse($_REQUEST['pulse']);
    $newTreat->setrespiration($_REQUEST['p_respiration']);
    $newTreat->settemp($_REQUEST['p_temp']);
    $newTreat->setbp($_REQUEST['bp']);
    $newTreat->setwh($_REQUEST['wh']);
    $newTreat->setbuilt($_REQUEST['built']);
    $newTreat->setscalp($_REQUEST['scalp']);
    $newTreat->seteyes($_REQUEST['eyes']);
    $newTreat->setnose($_REQUEST['p_nose']);
    $newTreat->setoral_cavity($_REQUEST['oral_cavity']);
    $newTreat->settounge($_REQUEST['tounge']);
    $newTreat->setneck($_REQUEST['neck']);
    $newTreat->setnails($_REQUEST['nails']);
    $newTreat->setedema($_REQUEST['edema']);
    $newTreat->setlymph($_REQUEST['lymph']);
    $newTreat->setanemia($_REQUEST['p_anemia']);
    $newTreat->setcyanosis($_REQUEST['p_cyanosis']);
    $newTreat->setjaundice($_REQUEST['jaundice']);
    $newTreat->setpigmentation($_REQUEST['pigmentation']);
    $newTreat->setothers($_REQUEST['others']);





    $newTreat->setrr($_REQUEST['rr']);
    $newTreat->setchest_shape($_REQUEST['chest_shape']);
    $newTreat->setr_nose($_REQUEST['r_nose']);
    $newTreat->setr_throat($_REQUEST['r_throat']);
    $newTreat->setr_cyanosis($_REQUEST['r_cyanosis']);
    $newTreat->setothers_a($_REQUEST['others_a']);
    $newTreat->setresp_moment($_REQUEST['resp_moment']);
    $newTreat->setmediastinum($_REQUEST['mediastinum']);
    $newTreat->settenderness($_REQUEST['tenderness']);
    $newTreat->setothers_b($_REQUEST['others_b']);





    $newTreat->setresonant($_REQUEST['resonant']);
    $newTreat->setcardiac_dullness($_REQUEST['cardiac_dullness']);
    $newTreat->setliver_dullness($_REQUEST['liver_dullness']);
    $newTreat->setother_dullness($_REQUEST['other_dullness']);
    $newTreat->sethyper($_REQUEST['hyper']);
    $newTreat->seta_breath_sounds($_REQUEST['a_breath_sounds']);
    $newTreat->setbroncho($_REQUEST['broncho']);
    $newTreat->setbreath_sounds($_REQUEST['breath_sounds']);
    $newTreat->setadded_sounds($_REQUEST['added_sounds']);
    $newTreat->setpr($_REQUEST['pr']);
    $newTreat->setrythom($_REQUEST['rythom']);
    $newTreat->setvolume($_REQUEST['volume']);
    $newTreat->setcharacter($_REQUEST['character']);
    $newTreat->setbp2($_REQUEST['bp2']);
    $newTreat->setccf($_REQUEST['ccf']);
    $newTreat->settender($_REQUEST['tender']);
    $newTreat->setpedal($_REQUEST['pedal']);
    $newTreat->setother_clubing($_REQUEST['other_clubing']);
    $newTreat->setc_cyanosis($_REQUEST['c_cyanosis']);
    $newTreat->setc_anemia($_REQUEST['c_anemia']);
    $newTreat->setchest_wall($_REQUEST['chest_wall']);
    $newTreat->setapex_beat($_REQUEST['apex_beat']);
    $newTreat->setpulsation($_REQUEST['pulsation']);
    $newTreat->setbeat_loaction($_REQUEST['beat_loaction']);
    $newTreat->setcharecter($_REQUEST['charecter']);
    $newTreat->settender_areas($_REQUEST['tender_areas']);
    $newTreat->setother_location($_REQUEST['other_location']);
    $newTreat->setcharecter2($_REQUEST['charecter2']);
    $newTreat->settender_areas2($_REQUEST['tender_areas2']);
    $newTreat->setthrills($_REQUEST['thrills']);
    $newTreat->setdiastolic($_REQUEST['diastolic']);
    $newTreat->setdull_loaction($_REQUEST['dull_loaction']);
    $newTreat->setdull_charecter($_REQUEST['dull_charecter']);
    $newTreat->setsound1($_REQUEST['sound1']);
    $newTreat->setsound2($_REQUEST['sound2']);
    $newTreat->setsplitting($_REQUEST['splitting']);
    $newTreat->seta_added_sounds($_REQUEST['a_added_sounds']);
    $newTreat->setsite($_REQUEST['site']);
    $newTreat->setheart_timing($_REQUEST['heart_timing']);
    $newTreat->setheart_char($_REQUEST['heart_char']);
    $newTreat->setpitch($_REQUEST['pitch']);
    $newTreat->setgrade($_REQUEST['grade']);
    $newTreat->setpresent($_REQUEST['present']);
    $newTreat->setabsent($_REQUEST['absent']);
    $newTreat->setother_heart($_REQUEST['other_heart']);






    $newTreat->setlips($_REQUEST['lips']);
    $newTreat->setteeth($_REQUEST['teeth']);
    $newTreat->setg_toungue($_REQUEST['g_toungue']);
    $newTreat->settonsils($_REQUEST['tonsils']);
    $newTreat->setshape($_REQUEST['shape']);
    $newTreat->setg_respiration($_REQUEST['g_respiration']);
    $newTreat->setumbilicus($_REQUEST['umbilicus']);
    $newTreat->sethernial($_REQUEST['hernial']);
    $newTreat->setvessels($_REQUEST['vessels']);
    $newTreat->setstriae($_REQUEST['striae']);
    $newTreat->setp_tenderness($_REQUEST['p_tenderness']);
    $newTreat->setrigidity($_REQUEST['rigidity']);


    $newTreat->setliver_size($_REQUEST['liver_size']);
    $newTreat->setliver_tender($_REQUEST['liver_tender']);
    $newTreat->setliver_margin($_REQUEST['liver_margin']);
    $newTreat->setliver_cons($_REQUEST['liver_cons']);
    $newTreat->setspleen_size($_REQUEST['spleen_size']);
    $newTreat->setspleen_tender($_REQUEST['spleen_tender']);
    $newTreat->setspleen_margin($_REQUEST['spleen_margin']);
    $newTreat->setspleen_cons($_REQUEST['spleen_cons']);
    $newTreat->setswelling($_REQUEST['swelling']);
    $newTreat->setrebound($_REQUEST['rebound']);
    $newTreat->setprecussion($_REQUEST['precussion']);
    $newTreat->setdullness_grade1($_REQUEST['dullness_grade1']);
    $newTreat->setdullness_grade2($_REQUEST['dullness_grade2']);
    $newTreat->setdullness_grade3($_REQUEST['dullness_grade3']);
    $newTreat->setdullness_grade($_REQUEST['dullness_grade']);
    $newTreat->setperistalitic($_REQUEST['peristalitic']);
    $newTreat->setrubs($_REQUEST['rubs']);
    $newTreat->setrectal($_REQUEST['rectal']);


    $newTreat->setmental_status($_REQUEST['mental_status']);
    $newTreat->setmemory($_REQUEST['memory']);
    $newTreat->setorientation($_REQUEST['orientation']);
    $newTreat->setspeech($_REQUEST['speech']);
    $newTreat->setbehavior($_REQUEST['behavior']);
    $newTreat->setgait($_REQUEST['gait']);
    $newTreat->setnerve_exam($_REQUEST['nerve_exam']);
    $newTreat->setnutrition($_REQUEST['nutrition']);
    $newTreat->setmuscle_score($_REQUEST['muscle_score']);
    $newTreat->setmuscle_tone($_REQUEST['muscle_tone']);
    $newTreat->setcoordinations($_REQUEST['coordinations']);
    $newTreat->setinvoluntary($_REQUEST['involuntary']);


    $newTreat->settouch($_REQUEST['touch']);
    $newTreat->setpain($_REQUEST['pain']);
    $newTreat->setsup_temp($_REQUEST['sup_temp']);
    $newTreat->setcrude_touch($_REQUEST['crude_touch']);
    $newTreat->setvaibration($_REQUEST['vaibration']);
    $newTreat->setjoint_sense($_REQUEST['joint_sense']);
    $newTreat->settactile_l($_REQUEST['tactile_l']);
    $newTreat->settactile_d($_REQUEST['tactile_d']);
    $newTreat->settactile_e($_REQUEST['tactile_e']);
    $newTreat->settactile_o($_REQUEST['tactile_o']);




    if (isset($_REQUEST['signs1'])) {
        $newTreat->setsigns1(1);
    } else {
        $newTreat->setsigns1(0);
    }
    if (isset($_REQUEST['signs2'])) {
        $newTreat->setsigns2(1);
    } else {
        $newTreat->setsigns2(0);
    }
    if (isset($_REQUEST['signs3'])) {
        $newTreat->setsigns3(1);
    } else {
        $newTreat->setsigns3(0);
    }
    if (isset($_REQUEST['jerks1'])) {
        $newTreat->setjerks1(1);
    } else {
        $newTreat->setjerks1(0);
    }
    if (isset($_REQUEST['jerks2'])) {
        $newTreat->setjerks2(1);
    } else {
        $newTreat->setjerks2(0);
    }
    if (isset($_REQUEST['jerks3'])) {
        $newTreat->setjerks3(1);
    } else {
        $newTreat->setjerks3(0);
    }
    if (isset($_REQUEST['jerks4'])) {
        $newTreat->setjerks4(1);
    } else {
        $newTreat->setjerks4(0);
    }



    $newTreat->setabdominal($_REQUEST['abdominal']);



    if (isset($_REQUEST['plantar'])) {
        $newTreat->setplantar(1);
    } else {
        $newTreat->setplantar(0);
    }
    if (isset($_REQUEST['jaw_jerk'])) {
        $newTreat->setjaw_jerk(1);
    } else {
        $newTreat->setjaw_jerk(0);
    }
    if (isset($_REQUEST['knee_reflex'])) {
        $newTreat->setknee_reflex(1);
    } else {
        $newTreat->setknee_reflex(0);
    }
    if (isset($_REQUEST['ankle_reflex'])) {
        $newTreat->setankle_reflex(1);
    } else {
        $newTreat->setankle_reflex(0);
    }

    $newTreat->setany_other($_REQUEST['any_other']);



















    if (isset($_REQUEST['upalepita'])) {
        $newTreat->setupalepita(1);
    } else {
        $newTreat->setupalepita(0);
    }
    if (isset($_REQUEST['ragayukta'])) {
        $newTreat->setragayukta(1);
    } else {
        $newTreat->setragayukta(0);
    }
    if (isset($_REQUEST['jivaothers'])) {
        $newTreat->setjivaothers(1);
    } else {
        $newTreat->setjivaothers(0);
    }
    if (isset($_REQUEST['paka'])) {
        $newTreat->setpaka(1);
    } else {
        $newTreat->setpaka(0);
    }
    if (isset($_REQUEST['cc'])) {
        $newTreat->setcc(1);
    } else {
        $newTreat->setcc(0);
    }

    $newTreat->setshabda($_REQUEST['shabda']);
    $newTreat->setsparsha($_REQUEST['sparsha']);

    if (isset($_REQUEST['ruksha'])) {
        $newTreat->setruksha(1);
    } else {
        $newTreat->setruksha(0);
    }
    if (isset($_REQUEST['snigdha'])) {
        $newTreat->setsnigdha(1);
    } else {
        $newTreat->setsnigdha(0);
    }
    if (isset($_REQUEST['tanu'])) {
        $newTreat->settanu(1);
    } else {
        $newTreat->settanu(0);
    }
    if (isset($_REQUEST['sthula'])) {
        $newTreat->setsthula(1);
    } else {
        $newTreat->setsthula(0);
    }
    if (isset($_REQUEST['ushna'])) {
        $newTreat->setushna(1);
    } else {
        $newTreat->setushna(0);
    }
    if (isset($_REQUEST['pale'])) {
        $newTreat->setpale(1);
    } else {
        $newTreat->setpale(0);
    }
    if (isset($_REQUEST['conjested'])) {
        $newTreat->setconjested(1);
    } else {
        $newTreat->setconjested(0);
    }
    if (isset($_REQUEST['kalushita'])) {
        $newTreat->setkalushita(1);
    } else {
        $newTreat->setkalushita(0);
    }
    if (isset($_REQUEST['akshimalasrava'])) {
        $newTreat->setakshimalasrava(1);
    } else {
        $newTreat->setakshimalasrava(0);
    }
    if (isset($_REQUEST['jalasrava'])) {
        $newTreat->setjalasrava(1);
    } else {
        $newTreat->setjalasrava(0);
    }
    if (isset($_REQUEST['raga'])) {
        $newTreat->setraga(1);
    } else {
        $newTreat->setraga(0);
    }


    if ($_REQUEST['edit'] == 1) {
        $newTreat->updateExamination($_REQUEST['pid']);
    } else {
        $newTreat->insertExamination($_REQUEST['pid']);
    }
    header("Location:../ipexam.php?id=$_REQUEST[pid]#v-pills-investigations-tab");
}

if ($_REQUEST['submit'] == 'lab') {
    // print_r($_REQUEST);exit;
    $bill_no = $newTreat->insertIPLab($_REQUEST['lab_test'], $_REQUEST['pid'], $_REQUEST['date']);
    header("Location:../lab_report.php?id=$_REQUEST[pid]&bill_no=$bill_no");
}
if ($_REQUEST['submit'] == 'invest') {
    // print_r($_REQUEST);exit;
    $newTreat->setnindana($_REQUEST['nindana']);
    $newTreat->setpoorvapoorva($_REQUEST['poorvapoorva']);
    $newTreat->setroopa($_REQUEST['roopa']);
    $newTreat->setupashaya($_REQUEST['upashaya']);
    $newTreat->setdosha($_REQUEST['dosha']);
    $newTreat->setdushy($_REQUEST['dushy']);
    $newTreat->setagni($_REQUEST['agni']);
    $newTreat->setama($_REQUEST['ama']);
    $newTreat->setsrotas($_REQUEST['srotas']);
    $newTreat->setsroto($_REQUEST['sroto']);
    $newTreat->setlakshana($_REQUEST['lakshana']);
    $newTreat->setvyadhi($_REQUEST['vyadhi']);
    $newTreat->setsanchara($_REQUEST['sanchara']);
    $newTreat->setvyaktasthana($_REQUEST['vyaktasthana']);
    $newTreat->setvyadhiadhisthana($_REQUEST['vyadhiadhisthana']);
    $newTreat->setrogamarga($_REQUEST['rogamarga']);
    $newTreat->setrogabala($_REQUEST['rogabala']);
    $newTreat->setrogibala($_REQUEST['rogibala']);
    $newTreat->setrogaswabhava($_REQUEST['rogaswabhava']);
    $newTreat->setupadrava($_REQUEST['upadrava']);
    $newTreat->setarista($_REQUEST['arista']);
    $newTreat->setsamprapti($_REQUEST['samprapti']);
    $newTreat->setsapeksha($_REQUEST['sapeksha']);
    $newTreat->setnidana($_REQUEST['nidana']);
    $newTreat->setvyadi($_REQUEST['vyadi']);
    $newTreat->setpid($_REQUEST['pid']);
    if ($_REQUEST['edit'] > 0) {
        $newTreat->updateInvest();
    } else {
        $newTreat->insertInvest();
    }
    header("Location:../treatment.php?id=$_REQUEST[pid]#v-pills-treatment-tab");
}
if ($_REQUEST['submit'] == 'medic') {
    // print_r($_REQUEST);exit;
    $newTreat->setomedicine($_REQUEST['omedicine']);
    $newTreat->setomedicine_name($_REQUEST['omedicine_name']);
    $newTreat->setqty($_REQUEST['qty1']);


    // $newOT->setnight($_REQUEST['night']);
    $newTreat->insertIPMedic($_REQUEST['pid'], $_REQUEST['rx_id'], $_REQUEST['position']);
    header("Location:../treatment.php?id=$_REQUEST[pid]#v-pills-medication-tab");
} else if ($_REQUEST['submit'] == 'medic_batch') {
    // Batch medicine submission handler
    if (isset($_REQUEST['medicines_data']) && !empty($_REQUEST['medicines_data'])) {
        $medicines = json_decode($_REQUEST['medicines_data'], true);
        $patientId = $_REQUEST['pid'];
        $rxId = $_REQUEST['rx_id'];
        $patientType = $_REQUEST['patient_type'] ? trim($_REQUEST['patient_type']) : 'STD'; // Get patient type from form data
        if (is_array($medicines) && count($medicines) > 0) {
            $items = array(); // Build items array for API
            $insert_id = array(); // To store inserted medicine IDs
            foreach ($medicines as $index => $medicine) {
                $productId = isset($medicine['product_id']) ? trim((string)$medicine['product_id']) : '';
                $productName = isset($medicine['product_name']) ? trim((string)$medicine['product_name']) : '';
                $qty = isset($medicine['quantity']) ? $medicine['quantity'] : 0;

                if ($productId === '' && $productName === '') {
                    continue;
                }

                // Set properties for each medicine
                $newTreat->setomedicine($productId);
                $newTreat->setomedicine_name($productName);
                $newTreat->setqty($qty);

                // Insert medicine into database
                $res = $newTreat->insertIPMedic($patientId);

                // Add to items array for API payload
                if ($res && $productId !== '') {
                    $items[] = array(
                        "position" => (int)$index + 1,
                        "product_id" => (string)$productId,
                        "quantity" => (int)$qty
                    );
                }

                if ($res) {
                    $insert_id[] = $res; // Store inserted medicine IDs
                }
            }

            // Make single API call after all medicines are inserted
            if (!empty($items)) {
                if (!isset($rxId) || trim($rxId) === "") {
                    $rxId = $newTreat->ensureIpdRxId($patientId);
                    if (empty($rxId)) {
                        echo "<script>window.location.href='../treatment.php?id=$patientId#v-pills-medication-tab&error=3';</script>";
                        exit;
                    }
                }
                $payload = array(
                    "customer_id" => (string)$rxId,
                    "issue_date" => date("d-m-Y"),
                    "status" => "PENDING",
                    "reference_number" => "$patientId",
                    "type" => "$patientType",
                    "description" => "Patient Type: $patientType\n Indent By : " . $_SESSION['name'], // Include patient type in description
                    "items" => $items
                );
                // print_r($payload);
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://api.retailxpress.ai/v1/external/retailer-delivery-challan',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => json_encode($payload),
                    CURLOPT_HTTPHEADER => array(
                        'x-api-key: 8417daa38eb33753f60ecbabe28d359bea4b4589d8c791f563b00b3de7c53543',
                        'Content-Type: application/json'
                    ),
                ));

                $response = curl_exec($curl);
                curl_close($curl);
                // echo "API Response: " . $response; // Debug API response
                // exit;
                $rxId = json_decode($response, true)['data']['id'] ?? null; // Extract Rx ID from API response
                $dcno = json_decode($response, true)['data']['delivery_challan_number'] ?? null; // Extract DC No from API response
                $newTreat->updateIPMedic(implode(',', $insert_id), $rxId, $dcno); // Update database with Rx ID and DC No for each inserted medicine
            }

            // Redirect after successful batch insertion
            echo "<script>window.location.href='../treatment.php?id=$patientId#v-pills-medication-tab&success=1';</script>";
        } else {
            // Invalid or empty medicines data
            echo "<script>window.location.href='../treatment.php?id=$patientId#v-pills-medication-tab&error=1';</script>";
        }
    } else {
        // No medicines data provided
        echo "<script>window.location.href='../treatment.php?id=" . $_REQUEST['pid'] . "#v-pills-medication-tab&error=2';</script>";
    }
}
if ($_REQUEST['submit'] == 'round_assessment') {
    $patientId = $_REQUEST['pid'];
    $roundData = isset($_REQUEST['round_data']) ? $_REQUEST['round_data'] : '';

    if (!empty($roundData)) {
        $rounds = json_decode($roundData, true);
        if (is_array($rounds) && count($rounds) > 0) {
            $inserted = $newTreat->insertRoundAssessmentBatch($patientId, $rounds);
            if ($inserted > 0) {
                echo "<script>window.location.href='../treatment.php?id=$patientId&round_success=1#v-pills-medication-tab';</script>";
            } else {
                echo "<script>window.location.href='../treatment.php?id=$patientId&round_error=1#v-pills-medication-tab';</script>";
            }
        } else {
            echo "<script>window.location.href='../treatment.php?id=$patientId&round_error=1#v-pills-medication-tab';</script>";
        }
    } else {
        echo "<script>window.location.href='../treatment.php?id=$patientId&round_error=1#v-pills-medication-tab';</script>";
    }
}
if ($_REQUEST['submit'] == 'daily') {

    $newTreat->setpulse($_REQUEST['pulse']);
    $newTreat->setbp($_REQUEST['bp']);
    $newTreat->settemp($_REQUEST['temp']);
    $newTreat->setnadi($_REQUEST['nadi']);
    $newTreat->setrbs($_REQUEST['rbs']);
    $newTreat->seturine_output($_REQUEST['urine_output']);
    $newTreat->setrespiratory_rate($_REQUEST['respiratory_rate']);
    $newTreat->setdiet($_REQUEST['diet']);
    $newTreat->setmedicin($_REQUEST['medicin']);
    $newTreat->setprogress($_REQUEST['progress']);
    $newTreat->setpid($_REQUEST['pid']);
    $newTreat->insertDaily();
    echo "<script>window.location.href='../treatment.php?id=" . $_REQUEST['pid'] . "#v-pills-medication-tab';</script>";
}
if ($_REQUEST['submit'] == 'proced') {
    // print_r($_REQUEST);exit;
    $newTreat->setpdate($_REQUEST['pdate']);
    $newTreat->setprocedure($_REQUEST['procedure']);
    $newTreat->setsubprocedure($_REQUEST['subprocedure']);
    $newTreat->setomedicine($_REQUEST['proce_medicine']);
    $newTreat->setroga($_REQUEST['dose']);
    $newTreat->setqty($_REQUEST['qty']);
    $newTreat->settotal1($_REQUEST['total']);
    $newTreat->setmorning($_REQUEST['morning']);
    $newTreat->setafternoon($_REQUEST['afternoon']);
    $newTreat->setevening($_REQUEST['evening']);
    $newTreat->setnight($_REQUEST['night']);
    $newTreat->setanupana($_REQUEST['observation']);
    $newTreat->insertProceMedic($_REQUEST['pid']);
    header("Location:../treatment.php?id=$_REQUEST[pid]#v-pills-medication-tab");
}

if ($_REQUEST['submit'] == 'ot_reg') {
    // print_r($_REQUEST);exit;
    $newTreat->setip_no($_REQUEST['pid']);
    $newTreat->setdoctor($_REQUEST['doctor']);
    $newTreat->setottype($_REQUEST['ottype']);
    $newTreat->setotdate($_REQUEST['otdate']);
    if ($_REQUEST['major_ot'] > 0) {
        $newTreat->setotprocedure($_REQUEST['major_ot']);
    } else {
        $newTreat->setotprocedure($_REQUEST['minor_ot']);
    }
    $newTreat->setotcomments($_REQUEST['comments']);

    $newTreat->insertOTReg();
    header("Location:../treatment.php?id=$_REQUEST[pid]#v-pills-medication-tab");
}
