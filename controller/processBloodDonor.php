<?php
// Blood Donor Module Controller
// Handles form submissions for donor registration, medical history, examination, eligibility, and deferral
// Pattern: Forms POST to this controller with action parameter

require_once(__DIR__ . '/../model/session_config.php');
// print_r($_REQUEST);exit;

error_reporting(E_ALL);
ini_set('display_errors', 1);

include('../model/bloodDonorClass.php');

date_default_timezone_set("Asia/Kolkata");

// ==================== FORM SUBMISSION HANDLER ====================

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_REQUEST['action'])) {
    
    $action = $_REQUEST['action'];
    $response = array();
    
    // ==================== DONOR REGISTRATION ====================
    if ($action == 'registerDonor') {
        
        $donor = new bloodDonor();
        
        // Set properties from form
        $donor->setFirstName($_REQUEST['first_name']);
        $donor->setLastName($_REQUEST['last_name']);
        $donor->setDateOfBirth($_REQUEST['date_of_birth']);
        $donor->setGender($_REQUEST['gender']);
        $donor->setMobileNumber($_REQUEST['mobile_number']);
        $donor->setAlternateMobile($_REQUEST['alternate_mobile'] ?? '');
        $donor->setEmail($_REQUEST['email'] ?? '');
        $donor->setAddress($_REQUEST['address']);
        $donor->setCity($_REQUEST['city'] ?? '');
        $donor->setState($_REQUEST['state'] ?? '');
        $donor->setPincode($_REQUEST['pincode'] ?? '');
        $donor->setIdProofType($_REQUEST['id_proof_type'] ?? 'None');
        $donor->setIdProofNumber($_REQUEST['id_proof_number'] ?? '');
        $donor->setNotes($_REQUEST['notes'] ?? '');
        
        // Register and redirect
        $result = $donor->registerDonor();
        
        if ($result['status'] == 'success') {
            // Redirect to medical history form with donor ID
            echo "<script>window.location.href = '../blood_donor_medical_history.php?donor_id=" . $result['donor_id'] . "&donor_unique_id=" . $result['donor_unique_id'] . "';</script>";
            exit;
        } else {
            // Redirect back with error
            echo "<script>window.location.href = '../add_blood_donor.php?error=" . urlencode($result['message']) . "';</script>";
            exit;
        }
    }
    
    // ==================== MEDICAL HISTORY SUBMISSION ====================
    elseif ($action == 'submitMedicalHistory') {
        
        $donor = new bloodDonor();
        $donor->setDonorId($_REQUEST['donor_id']);
        
        // Set medical history properties
        $donor->setTotalDonations((int)($_REQUEST['total_donations'] ?? 0));
        $donor->setRecentIllnessFlag(isset($_REQUEST['recent_illness_flag']) ? 1 : 0);
        $donor->setIllnessDescription($_REQUEST['illness_description'] ?? '');
        $donor->setRecentSurgeryFlag(isset($_REQUEST['recent_surgery_flag']) ? 1 : 0);
        $donor->setSurgeryDescription($_REQUEST['surgery_description'] ?? '');
        $donor->setSurgeryDate($_REQUEST['surgery_date'] ?? null);
        $donor->setHasChronicDisease(isset($_REQUEST['has_chronic_disease']) ? 1 : 0);
        $donor->setChronicDiseaseList($_REQUEST['chronic_disease_list'] ?? '');
        $donor->setOnMedication(isset($_REQUEST['on_medication']) ? 1 : 0);
        $donor->setMedicationList($_REQUEST['medication_list'] ?? '');
        $donor->setRecentVaccinationFlag(isset($_REQUEST['recent_vaccination_flag']) ? 1 : 0);
        $donor->setVaccinationType($_REQUEST['vaccination_type'] ?? '');
        $donor->setVaccinationDate($_REQUEST['vaccination_date'] ?? null);
        $donor->setHighRiskBehaviorFlag(isset($_REQUEST['high_risk_behavior_flag']) ? 1 : 0);
        $donor->setRiskBehaviorDescription($_REQUEST['risk_behavior_description'] ?? '');
        $donor->setHasCancerHistory(isset($_REQUEST['has_cancer_history']) ? 1 : 0);
        $donor->setHasBloodDisorder(isset($_REQUEST['has_blood_disorder']) ? 1 : 0);
        $donor->setHasHIVRisk(isset($_REQUEST['has_hiv_risk']) ? 1 : 0);
        $donor->setHepatitisRisk(isset($_REQUEST['hepatitis_risk']) ? 1 : 0);
        $donor->setIsPregnant(isset($_REQUEST['is_pregnant']) ? 1 : 0);
        $donor->setHasAllergies(isset($_REQUEST['has_allergies']) ? 1 : 0);
        $donor->setAllergyDetails($_REQUEST['allergy_details'] ?? '');
        
        // Submit and redirect
        $result = $donor->submitMedicalHistory();
        
        if ($result['status'] == 'success') {
            echo "<script>window.location.href = '../blood_donor_examination.php?donor_id=" . $_REQUEST['donor_id'] . "';</script>";
            exit;
        } else {
            echo "<script>window.location.href = '../blood_donor_medical_history.php?donor_id=" . $_REQUEST['donor_id'] . "&error=" . urlencode($result['message']) . "';</script>";
            exit;
        }
    }
    
    // ==================== PHYSICAL EXAMINATION SUBMISSION ====================
    elseif ($action == 'submitExamination') {
        
        $donor = new bloodDonor();
        $donor->setDonorId($_REQUEST['donor_id']);
        
        // Set examination properties
        $donor->setExaminationDate($_REQUEST['examination_date'] ?? date('Y-m-d'));
        $donor->setWeight((float)($_REQUEST['weight_kg']));
        $donor->setHeight((int)($_REQUEST['height_cm'] ?? 0));
        $donor->setBloodPressure((int)($_REQUEST['blood_pressure_systolic']), (int)($_REQUEST['blood_pressure_diastolic']));
        $donor->setPulseRate((int)($_REQUEST['pulse_rate']));
        $donor->setTemperature((float)($_REQUEST['temperature_celsius'] ?? 37.0));
        $donor->setHemoglobin((float)($_REQUEST['hemoglobin_gm_dl']));
        $donor->setPlateletCount($_REQUEST['platelet_count'] ?? null);
        $donor->setRBCCount($_REQUEST['rbc_count'] ?? null);
        $donor->setWBCCount($_REQUEST['wbc_count'] ?? null);
        $donor->setHIVResult($_REQUEST['hiv_result'] ?? 'Not Tested');
        $donor->setHepatitis_BResult($_REQUEST['hepatitis_b_result'] ?? 'Not Tested');
        $donor->setHepatitis_CResult($_REQUEST['hepatitis_c_result'] ?? 'Not Tested');
        $donor->setSyphilisResult($_REQUEST['syphilis_result'] ?? 'Not Tested');
        $donor->setMalariaResult($_REQUEST['malaria_result'] ?? 'Not Tested');
        $donor->setGeneralObservations($_REQUEST['general_observations'] ?? '');
        $donor->setMedicalStaffComments($_REQUEST['medical_staff_comments'] ?? '');
        $donor->setVeinsCondition($_REQUEST['veins_condition'] ?? 'Good');
        $donor->setExaminedBy($_SESSION['name'] ?? 'System');
        $donor->setExaminedByDesignation($_SESSION['designation'] ?? 'Medical Staff');
        
        // Submit examination
        $result = $donor->submitPhysicalExamination();
        
        if ($result['status'] == 'success') {
            echo "<script>window.location.href = '../blood_donor_eligibility.php?donor_id=" . $_REQUEST['donor_id'] . "';</script>";
            exit;
        } else {
            echo "<script>window.location.href = '../blood_donor_examination.php?donor_id=" . $_REQUEST['donor_id'] . "&error=" . urlencode($result['message']) . "';</script>";
            exit;
        }
    }
    
    // ==================== ELIGIBILITY EVALUATION ====================
    elseif ($action == 'evaluateEligibility') {
        
        $donor = new bloodDonor();
        $donor->setDonorId($_REQUEST['donor_id']);
        
        // Evaluate eligibility
        $result = $donor->evaluateEligibility();
        
        if ($result['status'] == 'success') {
            if ($result['eligible']) {
                // Eligible - redirect to donor profile
                echo "<script>window.location.href = '../blood_donor_profile.php?donor_id=" . $_REQUEST['donor_id'] . "&success=Eligible';</script>";
                exit;
            } else {
                // Deferred - redirect to deferral form
                $deferral_type = $result['deferral_type'];
                $deferral_category = $result['deferral_category'];
                $deferral_reasons = implode(", ", $result['deferral_reasons']);
                
                echo "<script>window.location.href = '../blood_donor_deferral.php?donor_id=" . $_REQUEST['donor_id'] 
                    . "&deferral_type=" . $deferral_type
                    . "&deferral_category=" . $deferral_category
                    . "&deferral_reasons=" . urlencode($deferral_reasons) . "';</script>";
                exit;
            }
        } else {
            echo "<script>window.location.href = '../blood_donor_eligibility.php?donor_id=" . $_REQUEST['donor_id'] . "&error=" . urlencode($result['message']) . "';</script>";
            exit;
        }
    }
    
    // ==================== CREATE DEFERRAL RECORD ====================
    elseif ($action == 'createDeferral') {
        
        $donor = new bloodDonor();
        $donor->setDonorId($_REQUEST['donor_id']);
        $donor->setDeferralType($_REQUEST['deferral_type']);
        $donor->setDeferralReason($_REQUEST['deferral_reason']);
        $donor->setDeferralCategory($_REQUEST['deferral_category']);
        $donor->setDeferralStartDate($_REQUEST['deferral_start_date'] ?? date('Y-m-d'));
        $donor->setDeferralPeriodDays((int)($_REQUEST['deferral_period_days'] ?? 0));
        $donor->setMedicalJustification($_REQUEST['medical_justification']);
        
        // Create deferral
        $result = $donor->createDeferral();
        
        if ($result['status'] == 'success') {
            echo "<script>window.location.href = '../blood_donor_profile.php?donor_id=" . $_REQUEST['donor_id'] . "&success=Deferred';</script>";
            exit;
        } else {
            echo "<script>window.location.href = '../blood_donor_deferral.php?donor_id=" . $_REQUEST['donor_id'] . "&error=" . urlencode($result['message']) . "';</script>";
            exit;
        }
    }
    
    // ==================== SEARCH DONOR (AJAX) ====================
    elseif ($action == 'searchDonor' && isset($_REQUEST['term'])) {
        
        header('Content-Type: application/json');
        
        $donor = new bloodDonor();
        $result = $donor->searchDonor($_REQUEST['term']);
        
        $donors = array();
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $donors[] = array(
                    'id' => $row['donor_id'],
                    'label' => $row['donor_unique_id'] . ' - ' . $row['first_name'] . ' ' . $row['last_name'],
                    'value' => $row['donor_unique_id']
                );
            }
        }
        
        echo json_encode($donors);
        exit;
    }
    
    // ==================== UNKNOWN ACTION ====================
    else {
        echo "<script>window.location.href = '../blood_donor_list.php?error=Invalid action';</script>";
        exit;
    }
}

// ==================== INVALID REQUEST ====================
else {
    echo "<script>window.location.href = '../blood_donor_list.php?error=Invalid request';</script>";
    exit;
}
?>
