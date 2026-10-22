<?php
require_once(__DIR__ . '/session_config.php');

include("dbconnect.php");


class bloodDonor {
    
    // ==================== BLOOD_DONOR_MASTER PROPERTIES ====================
    private $donor_id;
    private $donor_unique_id;
    private $first_name;
    private $last_name;
    private $date_of_birth;
    private $age;
    private $gender;
    private $mobile_number;
    private $alternate_mobile;
    private $email;
    private $address;
    private $city;
    private $state;
    private $pincode;
    private $id_proof_type;
    private $id_proof_number;
    private $blood_group;
    private $rhesus_factor;
    private $donor_type;
    private $current_status;
    private $last_donation_date;
    private $cooling_period_days;
    private $can_donate;
    private $notes;
    
    // ==================== MEDICAL_HISTORY PROPERTIES ====================
    private $total_donations;
    private $recent_illness_flag;
    private $illness_description;
    private $recent_surgery_flag;
    private $surgery_description;
    private $surgery_date;
    private $has_chronic_disease;
    private $chronic_disease_list;
    private $on_medication;
    private $medication_list;
    private $recent_vaccination_flag;
    private $vaccination_type;
    private $vaccination_date;
    private $high_risk_behavior_flag;
    private $risk_behavior_description;
    private $has_cancer_history;
    private $has_blood_disorder;
    private $has_hiv_risk;
    private $hepatitis_risk;
    private $is_pregnant;
    private $has_allergies;
    private $allergy_details;
    private $risk_assessment_status;
    private $risk_flags_count;
    
    // ==================== EXAMINATION PROPERTIES ====================
    private $examination_id;
    private $examination_date;
    private $weight_kg;
    private $height_cm;
    private $blood_pressure_systolic;
    private $blood_pressure_diastolic;
    private $pulse_rate;
    private $temperature_celsius;
    private $hemoglobin_gm_dl;
    private $hemoglobin_status;
    private $platelet_count;
    private $rbc_count;
    private $wbc_count;
    private $hiv_result;
    private $hepatitis_b_result;
    private $hepatitis_c_result;
    private $syphilis_result;
    private $malaria_result;
    private $general_observations;
    private $medical_staff_comments;
    private $veins_condition;
    private $examined_by;
    private $examined_by_designation;
    private $overall_physical_eligible;
    private $approval_status;
    
    // ==================== DEFERRAL PROPERTIES ====================
    private $deferral_id;
    private $deferral_type;
    private $deferral_reason;
    private $deferral_category;
    private $deferral_start_date;
    private $deferral_end_date;
    private $deferral_period_days;
    private $medical_justification;
    private $justification_by;
    private $is_admin_locked;
    private $admin_lock_reason;
    
    // ==================== SETTERS ====================
    
    // Donor Registration Setters
    public function setDonorId($donor_id) { $this->donor_id = $donor_id; }
    public function setDonorUniqueId($donor_unique_id) { $this->donor_unique_id = $donor_unique_id; }
    public function setFirstName($first_name) { $this->first_name = $first_name; }
    public function setLastName($last_name) { $this->last_name = $last_name; }
    public function setDateOfBirth($date_of_birth) { $this->date_of_birth = $date_of_birth; }
    public function setAge($age) { $this->age = $age; }
    public function setGender($gender) { $this->gender = $gender; }
    public function setMobileNumber($mobile_number) { $this->mobile_number = $mobile_number; }
    public function setAlternateMobile($alternate_mobile) { $this->alternate_mobile = $alternate_mobile; }
    public function setEmail($email) { $this->email = $email; }
    public function setAddress($address) { $this->address = $address; }
    public function setCity($city) { $this->city = $city; }
    public function setState($state) { $this->state = $state; }
    public function setPincode($pincode) { $this->pincode = $pincode; }
    public function setIdProofType($id_proof_type) { $this->id_proof_type = $id_proof_type; }
    public function setIdProofNumber($id_proof_number) { $this->id_proof_number = $id_proof_number; }
    public function setBloodGroup($blood_group) { $this->blood_group = $blood_group; }
    public function setRhesus($rhesus_factor) { $this->rhesus_factor = $rhesus_factor; }
    public function setDonorType($donor_type) { $this->donor_type = $donor_type; }
    public function setCurrentStatus($current_status) { $this->current_status = $current_status; }
    public function setLastDonationDate($last_donation_date) { $this->last_donation_date = $last_donation_date; }
    public function setCoolingPeriodDays($cooling_period_days) { $this->cooling_period_days = $cooling_period_days; }
    public function setCanDonate($can_donate) { $this->can_donate = $can_donate; }
    public function setNotes($notes) { $this->notes = $notes; }
    
    // Medical History Setters
    public function setTotalDonations($total_donations) { $this->total_donations = $total_donations; }
    public function setRecentIllnessFlag($recent_illness_flag) { $this->recent_illness_flag = $recent_illness_flag; }
    public function setIllnessDescription($illness_description) { $this->illness_description = $illness_description; }
    public function setRecentSurgeryFlag($recent_surgery_flag) { $this->recent_surgery_flag = $recent_surgery_flag; }
    public function setSurgeryDescription($surgery_description) { $this->surgery_description = $surgery_description; }
    public function setSurgeryDate($surgery_date) { $this->surgery_date = $surgery_date; }
    public function setHasChronicDisease($has_chronic_disease) { $this->has_chronic_disease = $has_chronic_disease; }
    public function setChronicDiseaseList($chronic_disease_list) { $this->chronic_disease_list = $chronic_disease_list; }
    public function setOnMedication($on_medication) { $this->on_medication = $on_medication; }
    public function setMedicationList($medication_list) { $this->medication_list = $medication_list; }
    public function setRecentVaccinationFlag($recent_vaccination_flag) { $this->recent_vaccination_flag = $recent_vaccination_flag; }
    public function setVaccinationType($vaccination_type) { $this->vaccination_type = $vaccination_type; }
    public function setVaccinationDate($vaccination_date) { $this->vaccination_date = $vaccination_date; }
    public function setHighRiskBehaviorFlag($high_risk_behavior_flag) { $this->high_risk_behavior_flag = $high_risk_behavior_flag; }
    public function setRiskBehaviorDescription($risk_behavior_description) { $this->risk_behavior_description = $risk_behavior_description; }
    public function setHasCancerHistory($has_cancer_history) { $this->has_cancer_history = $has_cancer_history; }
    public function setHasBloodDisorder($has_blood_disorder) { $this->has_blood_disorder = $has_blood_disorder; }
    public function setHasHIVRisk($has_hiv_risk) { $this->has_hiv_risk = $has_hiv_risk; }
    public function setHepatitisRisk($hepatitis_risk) { $this->hepatitis_risk = $hepatitis_risk; }
    public function setIsPregnant($is_pregnant) { $this->is_pregnant = $is_pregnant; }
    public function setHasAllergies($has_allergies) { $this->has_allergies = $has_allergies; }
    public function setAllergyDetails($allergy_details) { $this->allergy_details = $allergy_details; }
    public function setRiskAssessmentStatus($risk_assessment_status) { $this->risk_assessment_status = $risk_assessment_status; }
    
    // Examination Setters
    public function setExaminationId($examination_id) { $this->examination_id = $examination_id; }
    public function setExaminationDate($examination_date) { $this->examination_date = $examination_date; }
    public function setWeight($weight_kg) { $this->weight_kg = $weight_kg; }
    public function setHeight($height_cm) { $this->height_cm = $height_cm; }
    public function setBloodPressure($systolic, $diastolic) {
        $this->blood_pressure_systolic = $systolic;
        $this->blood_pressure_diastolic = $diastolic;
    }
    public function setPulseRate($pulse_rate) { $this->pulse_rate = $pulse_rate; }
    public function setTemperature($temperature_celsius) { $this->temperature_celsius = $temperature_celsius; }
    public function setHemoglobin($hemoglobin_gm_dl) { $this->hemoglobin_gm_dl = $hemoglobin_gm_dl; }
    public function setPlateletCount($platelet_count) { $this->platelet_count = $platelet_count; }
    public function setRBCCount($rbc_count) { $this->rbc_count = $rbc_count; }
    public function setWBCCount($wbc_count) { $this->wbc_count = $wbc_count; }
    public function setHIVResult($hiv_result) { $this->hiv_result = $hiv_result; }
    public function setHepatitis_BResult($hepatitis_b_result) { $this->hepatitis_b_result = $hepatitis_b_result; }
    public function setHepatitis_CResult($hepatitis_c_result) { $this->hepatitis_c_result = $hepatitis_c_result; }
    public function setSyphilisResult($syphilis_result) { $this->syphilis_result = $syphilis_result; }
    public function setMalariaResult($malaria_result) { $this->malaria_result = $malaria_result; }
    public function setGeneralObservations($general_observations) { $this->general_observations = $general_observations; }
    public function setMedicalStaffComments($medical_staff_comments) { $this->medical_staff_comments = $medical_staff_comments; }
    public function setVeinsCondition($veins_condition) { $this->veins_condition = $veins_condition; }
    public function setExaminedBy($examined_by) { $this->examined_by = $examined_by; }
    public function setExaminedByDesignation($examined_by_designation) { $this->examined_by_designation = $examined_by_designation; }
    public function setApprovalStatus($approval_status) { $this->approval_status = $approval_status; }
    
    // Deferral Setters
    public function setDeferralId($deferral_id) { $this->deferral_id = $deferral_id; }
    public function setDeferralType($deferral_type) { $this->deferral_type = $deferral_type; }
    public function setDeferralReason($deferral_reason) { $this->deferral_reason = $deferral_reason; }
    public function setDeferralCategory($deferral_category) { $this->deferral_category = $deferral_category; }
    public function setDeferralStartDate($deferral_start_date) { $this->deferral_start_date = $deferral_start_date; }
    public function setDeferralEndDate($deferral_end_date) { $this->deferral_end_date = $deferral_end_date; }
    public function setDeferralPeriodDays($deferral_period_days) { $this->deferral_period_days = $deferral_period_days; }
    public function setMedicalJustification($medical_justification) { $this->medical_justification = $medical_justification; }
    public function setJustificationBy($justification_by) { $this->justification_by = $justification_by; }
    public function setIsAdminLocked($is_admin_locked) { $this->is_admin_locked = $is_admin_locked; }
    public function setAdminLockReason($admin_lock_reason) { $this->admin_lock_reason = $admin_lock_reason; }
    
    // ==================== GETTERS ====================
    
    public function getDonorId() { return $this->donor_id; }
    public function getDonorUniqueId() { return $this->donor_unique_id; }
    public function getFirstName() { return $this->first_name; }
    public function getLastName() { return $this->last_name; }
    public function getAge() { return $this->age; }
    public function getGender() { return $this->gender; }
    public function getMobileNumber() { return $this->mobile_number; }
    public function getBloodGroup() { return $this->blood_group; }
    public function getCurrentStatus() { return $this->current_status; }
    public function getCanDonate() { return $this->can_donate; }
    public function getHemoglobin() { return $this->hemoglobin_gm_dl; }
    public function getWeight() { return $this->weight_kg; }
    public function getRiskAssessmentStatus() { return $this->risk_assessment_status; }
    
    // ==================== BUSINESS METHODS ====================
    
    // Database Connection (Reuse from parent pattern)
    private function dbconnect() {
        $con = new userlogin();
        return $con->dbconnect();
    }
    
    /**
     * STEP 1: Register New Donor
     * Captures donor identity and contact details
     * Generates unique Donor ID
     * Identifies first-time or repeat donor
     */
    public function registerDonor() {
        try {
           $newlogin = new userlogin;                  
  $db =$newlogin->dbconnect();
            
            // Validate minimum age (18 years)
            $birth_date = new DateTime($this->date_of_birth);
            $today = new DateTime();
            $age = $today->diff($birth_date)->y;
            
            if ($age < 18) {
                return array('status' => 'error', 'message' => 'Donor must be at least 18 years old');
            }
            
            // Check for duplicate mobile number (one active donor profile per person)
            $check_query = "SELECT donor_id FROM blood_donor_master WHERE mobile_number = ? AND is_active = TRUE LIMIT 1";
            $check_stmt = $db->prepare($check_query);
            $check_stmt->bind_param("s", $this->mobile_number);
            $check_stmt->execute();
            
            if ($check_stmt->get_result()->num_rows > 0) {
                return array('status' => 'error', 'message' => 'Active donor profile already exists for this mobile number');
            }
            
            // Generate Unique Donor ID (Format: BD-YYYY-0001)
            $year = date('Y');
            $count_query = "SELECT COUNT(*) as count FROM blood_donor_master WHERE created_date LIKE ? AND is_active = TRUE";
            $like_pattern = $year . '%';
            $count_stmt = $db->prepare($count_query);
            $count_stmt->bind_param("s", $like_pattern);
            $count_stmt->execute();
            $count_result = $count_stmt->get_result();
            $count_row = $count_result->fetch_assoc();
            $next_num = $count_row['count'] + 1;
            
            $this->donor_unique_id = 'BD-' . $year . '-' . str_pad($next_num, 4, '0', STR_PAD_LEFT);
            
            // Determine donor type (first-time or repeat)
            $this->donor_type = 'First_Time';
            
            // Insert into blood_donor_master
            $insert_query = "INSERT INTO blood_donor_master 
                (donor_unique_id, first_name, last_name, date_of_birth, gender, mobile_number, 
                 alternate_mobile, email, address, city, state, pincode, 
                 id_proof_type, id_proof_number, donor_type, current_status, created_by)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $db->prepare($insert_query);
            $status = 'Registered';
            $created_by = isset($_SESSION['name']) ? $_SESSION['name'] : 'System';
            
            $stmt->bind_param("sssssssssssssssss",
                $this->donor_unique_id, $this->first_name, $this->last_name, $this->date_of_birth,
                $this->gender, $this->mobile_number, $this->alternate_mobile, $this->email,
                $this->address, $this->city, $this->state, $this->pincode,
                $this->id_proof_type, $this->id_proof_number, $this->donor_type, $status, $created_by
            );
            
            if ($stmt->execute()) {
                $this->donor_id = $db->insert_id;
                
                // Log to audit trail
                $this->logAuditTrail('Registration', null, $this->donor_unique_id, $created_by);
                
                return array(
                    'status' => 'success',
                    'message' => 'Donor registered successfully',
                    'donor_id' => $this->donor_id,
                    'donor_unique_id' => $this->donor_unique_id
                );
            } else {
                return array('status' => 'error', 'message' => 'Failed to register donor: ' . $stmt->error);
            }
            
        } catch (Exception $e) {
            return array('status' => 'error', 'message' => 'Exception: ' . $e->getMessage());
        }
    }
    
    /**
     * STEP 2: Submit Medical History & Risk Assessment
     * Collect donor health and risk information
     * Auto-flag risk responses
     */
    public function submitMedicalHistory() {
        try {
           $newlogin = new userlogin;                  
  $db =$newlogin->dbconnect();
            
            // Count risk flags
            $risk_flags = 0;
            
            if ($this->recent_illness_flag) $risk_flags++;
            if ($this->recent_surgery_flag) $risk_flags++;
            if ($this->has_chronic_disease) $risk_flags++;
            if ($this->on_medication) $risk_flags++;
            if ($this->recent_vaccination_flag) $risk_flags++;
            if ($this->high_risk_behavior_flag) $risk_flags++;
            if ($this->has_cancer_history) $risk_flags++;
            if ($this->has_blood_disorder) $risk_flags++;
            if ($this->has_hiv_risk) $risk_flags++;
            if ($this->hepatitis_risk) $risk_flags++;
            if ($this->is_pregnant) $risk_flags++;
            if ($this->has_allergies) $risk_flags++;
            
            // Auto-assign risk assessment status
            if ($this->has_hiv_risk || $this->hepatitis_risk || $this->has_cancer_history) {
                $this->risk_assessment_status = 'High_Risk';
            } elseif ($risk_flags >= 3) {
                $this->risk_assessment_status = 'Medium_Risk';
            } else {
                $this->risk_assessment_status = 'Low_Risk';
            }
            
            // Insert into blood_donor_medical_history
            $insert_query = "INSERT INTO blood_donor_medical_history
                (donor_id, total_donations, recent_illness_flag, illness_description, 
                 recent_surgery_flag, surgery_description, surgery_date,
                 has_chronic_disease, chronic_disease_list, on_medication, medication_list,
                 recent_vaccination_flag, vaccination_type, vaccination_date,
                 high_risk_behavior_flag, risk_behavior_description,
                 has_cancer_history, has_blood_disorder, has_hiv_risk, hepatitis_risk,
                 is_pregnant, has_allergies, allergy_details,
                 risk_assessment_status, risk_flags_count, questionnaire_completed, created_by)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, TRUE, ?)";
            
            $stmt = $db->prepare($insert_query);
            $created_by = isset($_SESSION['name']) ? $_SESSION['name'] : 'System';
            
            $stmt->bind_param("iiisisssiisisisssiiisissss",
                $this->donor_id, $this->total_donations, $this->recent_illness_flag, $this->illness_description,
                $this->recent_surgery_flag, $this->surgery_description, $this->surgery_date,
                $this->has_chronic_disease, $this->chronic_disease_list, $this->on_medication, $this->medication_list,
                $this->recent_vaccination_flag, $this->vaccination_type, $this->vaccination_date,
                $this->high_risk_behavior_flag, $this->risk_behavior_description,
                $this->has_cancer_history, $this->has_blood_disorder, $this->has_hiv_risk, $this->hepatitis_risk,
                $this->is_pregnant, $this->has_allergies, $this->allergy_details,
                $this->risk_assessment_status, $risk_flags, $created_by
            );
            
            if ($stmt->execute()) {
                // Log to audit trail
                $this->logAuditTrail('Medical_History_Update', null, $this->risk_assessment_status, $created_by);
                
                return array(
                    'status' => 'success',
                    'message' => 'Medical history submitted',
                    'risk_assessment' => $this->risk_assessment_status,
                    'risk_flags_count' => $risk_flags
                );
            } else {
                return array('status' => 'error', 'message' => 'Failed to submit medical history: ' . $stmt->error);
            }
            
        } catch (Exception $e) {
            return array('status' => 'error', 'message' => 'Exception: ' . $e->getMessage());
        }
    }
    
    /**
     * STEP 3: Physical Examination
     * Basic fitness assessment before donation
     * Medical staff only
     */
    public function submitPhysicalExamination() {
        try {
           $newlogin = new userlogin;                  
  $db =$newlogin->dbconnect();
            
            // Validate weight (minimum 50 kg)
            if ($this->weight_kg < 50) {
                return array('status' => 'error', 'message' => 'Minimum weight requirement not met (50 kg required)');
            }
            
            // Determine hemoglobin eligibility based on gender
            $gender = $this->getGenderForExamination();
            $min_hemoglobin = ($gender == 'Female') ? 12.5 : 13.5;
            
            // Evaluate physical parameters
            $weight_eligible = ($this->weight_kg >= 50);
            $bp_eligible = ($this->blood_pressure_systolic >= 90 && $this->blood_pressure_systolic <= 180 &&
                            $this->blood_pressure_diastolic >= 60 && $this->blood_pressure_diastolic <= 100);
            $pulse_eligible = ($this->pulse_rate >= 50 && $this->pulse_rate <= 100);
            $hemoglobin_eligible = ($this->hemoglobin_gm_dl >= $min_hemoglobin);
            $temperature_eligible = ($this->temperature_celsius >= 36.5 && $this->temperature_celsius <= 37.5);
            
            $overall_physical_eligible = ($weight_eligible && $bp_eligible && $pulse_eligible && $hemoglobin_eligible && $temperature_eligible);
            
            // Set hemoglobin status
            if ($this->hemoglobin_gm_dl < $min_hemoglobin) {
                $this->hemoglobin_status = 'Low';
            } elseif ($this->hemoglobin_gm_dl > 17) {
                $this->hemoglobin_status = 'High';
            } else {
                $this->hemoglobin_status = 'Acceptable';
            }
            
            // Insert examination record
            $insert_query = "INSERT INTO blood_donor_examination
                (donor_id, examination_date, weight_kg, height_cm, blood_pressure_systolic, blood_pressure_diastolic,
                 pulse_rate, temperature_celsius, hemoglobin_gm_dl, hemoglobin_status,
                 platelet_count, rbc_count, wbc_count,
                 hiv_result, hepatitis_b_result, hepatitis_c_result, syphilis_result, malaria_result,
                 general_observations, medical_staff_comments, veins_condition,
                 weight_eligible, bp_eligible, pulse_eligible, hemoglobin_eligible, temperature_eligible,
                 overall_physical_eligible, examined_by, examined_by_designation)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $db->prepare($insert_query);
            $exam_date = $this->examination_date ?: date('Y-m-d');
            
            $stmt->bind_param("isiiiiiddsddssssssssiiiiiii",
                $this->donor_id, $exam_date, $this->weight_kg, $this->height_cm,
                $this->blood_pressure_systolic, $this->blood_pressure_diastolic,
                $this->pulse_rate, $this->temperature_celsius, $this->hemoglobin_gm_dl, $this->hemoglobin_status,
                $this->platelet_count, $this->rbc_count, $this->wbc_count,
                $this->hiv_result, $this->hepatitis_b_result, $this->hepatitis_c_result, $this->syphilis_result, $this->malaria_result,
                $this->general_observations, $this->medical_staff_comments, $this->veins_condition,
                $weight_eligible, $bp_eligible, $pulse_eligible, $hemoglobin_eligible, $temperature_eligible,
                $overall_physical_eligible, $this->examined_by, $this->examined_by_designation
            );
            
            if ($stmt->execute()) {
                $this->examination_id = $db->insert_id;
                
                // Log to audit trail
                $created_by = isset($_SESSION['name']) ? $_SESSION['name'] : 'System';
                $this->logAuditTrail('Examination_Entry', null, ($overall_physical_eligible ? 'Pass' : 'Fail'), $created_by);
                
                return array(
                    'status' => 'success',
                    'message' => 'Physical examination submitted',
                    'overall_eligible' => $overall_physical_eligible,
                    'examination_id' => $this->examination_id,
                    'hemoglobin_status' => $this->hemoglobin_status,
                    'weight_eligible' => $weight_eligible,
                    'hemoglobin_eligible' => $hemoglobin_eligible
                );
            } else {
                return array('status' => 'error', 'message' => 'Failed to submit examination: ' . $stmt->error);
            }
            
        } catch (Exception $e) {
            return array('status' => 'error', 'message' => 'Exception: ' . $e->getMessage());
        }
    }
    
    /**
     * STEP 4: Eligibility Evaluation
     * Make decision: Eligible, Temporarily Deferred, Permanently Deferred
     * Evaluate based on medical history + examination results
     */
    public function evaluateEligibility() {
        try {
           $newlogin = new userlogin;                  
  $db =$newlogin->dbconnect();
            
            // Get latest examination
            $exam_query = "SELECT * FROM blood_donor_examination WHERE donor_id = ? ORDER BY examination_date DESC LIMIT 1";
            $exam_stmt = $db->prepare($exam_query);
            $exam_stmt->bind_param("i", $this->donor_id);
            $exam_stmt->execute();
            $exam_result = $exam_stmt->get_result();
            
            if ($exam_result->num_rows == 0) {
                return array('status' => 'error', 'message' => 'No examination record found. Complete physical examination first.');
            }
            
            $exam = $exam_result->fetch_assoc();
            
            // Get medical history
            $hist_query = "SELECT * FROM blood_donor_medical_history WHERE donor_id = ? ORDER BY history_date DESC LIMIT 1";
            $hist_stmt = $db->prepare($hist_query);
            $hist_stmt->bind_param("i", $this->donor_id);
            $hist_stmt->execute();
            $hist_result = $hist_stmt->get_result();
            $history = $hist_result->fetch_assoc();
            
            // Decision Logic
            $eligible = TRUE;
            $deferral_reasons = array();
            $deferral_type = null;
            $deferral_category = null;
            
            // Check Examination Parameters
            if (!$exam['overall_physical_eligible']) {
                if ($exam['hemoglobin_status'] == 'Low') {
                    $eligible = FALSE;
                    $deferral_reasons[] = 'Low Hemoglobin';
                    $deferral_category = 'Low_Hemoglobin';
                    $deferral_type = 'Temporary';
                }
                if (!$exam['bp_eligible']) {
                    $eligible = FALSE;
                    $deferral_reasons[] = 'Blood Pressure out of range';
                    $deferral_category = 'High_Blood_Pressure';
                    $deferral_type = 'Temporary';
                }
                if (!$exam['weight_eligible']) {
                    $eligible = FALSE;
                    $deferral_reasons[] = 'Weight below minimum (50 kg)';
                    $deferral_category = 'Insufficient_Weight';
                    $deferral_type = 'Temporary';
                }
            }
            
            // Check Medical History
            if ($history) {
                if ($history['recent_illness_flag']) {
                    $eligible = FALSE;
                    $deferral_reasons[] = 'Recent illness: ' . $history['illness_description'];
                    if (!$deferral_category) $deferral_category = 'Recent_Illness';
                    $deferral_type = 'Temporary';
                }
                if ($history['recent_surgery_flag']) {
                    $eligible = FALSE;
                    $deferral_reasons[] = 'Recent surgery: ' . $history['surgery_description'];
                    if (!$deferral_category) $deferral_category = 'Recent_Surgery';
                    $deferral_type = 'Temporary';
                }
                if ($history['high_risk_behavior_flag']) {
                    $eligible = FALSE;
                    $deferral_reasons[] = 'High-risk behavior declared';
                    if (!$deferral_category) $deferral_category = 'High_Risk_Behavior';
                    $deferral_type = 'Permanent';
                }
                if ($history['has_hiv_risk'] || $exam['hiv_result'] == 'Positive') {
                    $eligible = FALSE;
                    $deferral_reasons[] = 'HIV risk/positive status';
                    if (!$deferral_category) $deferral_category = 'HIV_Positive';
                    $deferral_type = 'Permanent';
                }
                if ($history['hepatitis_risk'] || $exam['hepatitis_b_result'] == 'Positive' || $exam['hepatitis_c_result'] == 'Positive') {
                    $eligible = FALSE;
                    $deferral_reasons[] = 'Hepatitis risk/positive status';
                    if (!$deferral_category) $deferral_category = 'Hepatitis_Positive';
                    $deferral_type = 'Permanent';
                }
            }
            
            // Get donor info for status update
            $donor_query = "SELECT * FROM blood_donor_master WHERE donor_id = ?";
            $donor_stmt = $db->prepare($donor_query);
            $donor_stmt->bind_param("i", $this->donor_id);
            $donor_stmt->execute();
            $donor_result = $donor_stmt->get_result();
            $donor = $donor_result->fetch_assoc();
            
            // Determine final status
            if ($eligible) {
                $final_status = 'Eligible';
                $can_donate = TRUE;
            } else {
                $final_status = ($deferral_type == 'Temporary') ? 'Deferred_Temporary' : 'Deferred_Permanent';
                $can_donate = FALSE;
            }
            
            // Update donor status
            $update_query = "UPDATE blood_donor_master SET current_status = ?, can_donate = ? WHERE donor_id = ?";
            $update_stmt = $db->prepare($update_query);
            $update_stmt->bind_param("sii", $final_status, $can_donate, $this->donor_id);
            
            if ($update_stmt->execute()) {
                // Log to audit trail
                $created_by = isset($_SESSION['name']) ? $_SESSION['name'] : 'System';
                $this->logAuditTrail('Eligibility_Decision', 'Registered', $final_status, $created_by);
                
                return array(
                    'status' => 'success',
                    'message' => 'Eligibility evaluation completed',
                    'eligible' => $eligible,
                    'final_status' => $final_status,
                    'can_donate' => $can_donate,
                    'deferral_reasons' => $deferral_reasons,
                    'deferral_type' => $deferral_type,
                    'deferral_category' => $deferral_category
                );
            } else {
                return array('status' => 'error', 'message' => 'Failed to update eligibility: ' . $update_stmt->error);
            }
            
        } catch (Exception $e) {
            return array('status' => 'error', 'message' => 'Exception: ' . $e->getMessage());
        }
    }
    
    /**
     * STEP 5: Create Deferral Record
     * Record deferral decision with medical justification
     * Lock for permanent deferrals
     */
    public function createDeferral() {
        try {
           $newlogin = new userlogin;                  
  $db =$newlogin->dbconnect();
            
            // Validate deferral inputs
            if (!$this->deferral_type || !$this->deferral_reason || !$this->medical_justification) {
                return array('status' => 'error', 'message' => 'Missing deferral type, reason, or medical justification');
            }
            
            // Calculate deferral end date for temporary deferrals
            $deferral_end_date = null;
            if ($this->deferral_type == 'Temporary' && $this->deferral_period_days) {
                $start = new DateTime($this->deferral_start_date ?: date('Y-m-d'));
                $start->add(new DateInterval('P' . $this->deferral_period_days . 'D'));
                $deferral_end_date = $start->format('Y-m-d');
            }
            
            // Insert deferral record
            $insert_query = "INSERT INTO blood_donor_deferral
                (donor_id, deferral_type, deferral_reason, deferral_category,
                 deferral_start_date, deferral_end_date, deferral_period_days,
                 medical_justification, justification_by, is_admin_locked, allow_re_evaluation, is_active)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $db->prepare($insert_query);
            $start_date = $this->deferral_start_date ?: date('Y-m-d');
            $admin_locked = ($this->deferral_type == 'Permanent') ? TRUE : FALSE;
            $allow_re_eval = ($this->deferral_type == 'Temporary') ? TRUE : FALSE;
            $justification_by = isset($_SESSION['name']) ? $_SESSION['name'] : 'System';
            
            $stmt->bind_param("isssssississi",
                $this->donor_id, $this->deferral_type, $this->deferral_reason, $this->deferral_category,
                $start_date, $deferral_end_date, $this->deferral_period_days,
                $this->medical_justification, $justification_by, $admin_locked, $allow_re_eval, TRUE
            );
            
            if ($stmt->execute()) {
                $deferral_id = $db->insert_id;
                
                // Log to audit trail (immutable)
                $created_by = isset($_SESSION['name']) ? $_SESSION['name'] : 'System';
                $this->logAuditTrail('Deferral_Created', null, $this->deferral_type, $created_by, null, TRUE);
                
                return array(
                    'status' => 'success',
                    'message' => 'Deferral record created',
                    'deferral_id' => $deferral_id,
                    'deferral_type' => $this->deferral_type,
                    'deferral_end_date' => $deferral_end_date
                );
            } else {
                return array('status' => 'error', 'message' => 'Failed to create deferral: ' . $stmt->error);
            }
            
        } catch (Exception $e) {
            return array('status' => 'error', 'message' => 'Exception: ' . $e->getMessage());
        }
    }
    
    /**
     * Get Donor Details by ID
     */
    public function getDonorDetails() {
        try {
           $newlogin = new userlogin;                  
  $db =$newlogin->dbconnect();
            
            $query = "SELECT * FROM blood_donor_master WHERE donor_id = ?";
            $stmt = $db->prepare($query);
            $stmt->bind_param("i", $this->donor_id);
            $stmt->execute();
            
            return $stmt->get_result();
            
        } catch (Exception $e) {
            return null;
        }
    }
    
    /**
     * Get Medical History by Donor ID
     */
    public function getMedicalHistory() {
        try {
           $newlogin = new userlogin;                  
  $db =$newlogin->dbconnect();
            
            $query = "SELECT * FROM blood_donor_medical_history WHERE donor_id = ? ORDER BY history_date DESC LIMIT 1";
            $stmt = $db->prepare($query);
            $stmt->bind_param("i", $this->donor_id);
            $stmt->execute();
            
            return $stmt->get_result();
            
        } catch (Exception $e) {
            return null;
        }
    }
    
    /**
     * Get Latest Examination by Donor ID
     */
    public function getLatestExamination() {
        try {
           $newlogin = new userlogin;                  
  $db =$newlogin->dbconnect();
            
            $query = "SELECT * FROM blood_donor_examination WHERE donor_id = ? ORDER BY examination_date DESC LIMIT 1";
            $stmt = $db->prepare($query);
            $stmt->bind_param("i", $this->donor_id);
            $stmt->execute();
            
            return $stmt->get_result();
            
        } catch (Exception $e) {
            return null;
        }
    }
    
    /**
     * Get Active Deferral by Donor ID
     */
    public function getActiveDeferral() {
        try {
           $newlogin = new userlogin;                  
  $db =$newlogin->dbconnect();
            
            $query = "SELECT * FROM blood_donor_deferral WHERE donor_id = ? AND is_active = TRUE ORDER BY deferral_start_date DESC LIMIT 1";
            $stmt = $db->prepare($query);
            $stmt->bind_param("i", $this->donor_id);
            $stmt->execute();
            
            return $stmt->get_result();
            
        } catch (Exception $e) {
            return null;
        }
    }
    
    /**
     * Get All Donors (With Filter)
     */
    public function getAllDonors($status = null, $limit = 100, $offset = 0) {
        try {
           $newlogin = new userlogin;                  
  $db =$newlogin->dbconnect();
            
            if ($status) {
                $query = "SELECT * FROM blood_donor_master WHERE current_status = ? AND is_active = TRUE 
                         ORDER BY created_date DESC LIMIT ?, ?";
                $stmt = $db->prepare($query);
                $stmt->bind_param("sii", $status, $offset, $limit);
            } else {
                $query = "SELECT * FROM blood_donor_master WHERE is_active = TRUE 
                         ORDER BY created_date DESC LIMIT ?, ?";
                $stmt = $db->prepare($query);
                $stmt->bind_param("ii", $offset, $limit);
            }
            
            $stmt->execute();
            return $stmt->get_result();
            
        } catch (Exception $e) {
            return null;
        }
    }
    
    /**
     * Search Donor by Mobile, Unique ID, or Name
     */
    public function searchDonor($search_term) {
        try {
           $newlogin = new userlogin;                  
  $db =$newlogin->dbconnect();
            
            $search = '%' . $search_term . '%';
            $query = "SELECT * FROM blood_donor_master 
                     WHERE (mobile_number LIKE ? OR donor_unique_id LIKE ? OR first_name LIKE ? OR last_name LIKE ?)
                     AND is_active = TRUE LIMIT 20";
            
            $stmt = $db->prepare($query);
            $stmt->bind_param("ssss", $search, $search, $search, $search);
            $stmt->execute();
            
            return $stmt->get_result();
            
        } catch (Exception $e) {
            return null;
        }
    }
    
    /**
     * Get Donor's Audit Trail
     */
    public function getAuditTrail() {
        try {
           $newlogin = new userlogin;                  
  $db =$newlogin->dbconnect();
            
            $query = "SELECT * FROM blood_donor_audit_log WHERE donor_id = ? ORDER BY performed_date DESC";
            $stmt = $db->prepare($query);
            $stmt->bind_param("i", $this->donor_id);
            $stmt->execute();
            
            return $stmt->get_result();
            
        } catch (Exception $e) {
            return null;
        }
    }
    
    /**
     * Private: Log to Audit Trail (Immutable for Deferrals)
     */
    private function logAuditTrail($action_type, $old_value, $new_value, $performed_by, $affected_field = null, $is_locked = FALSE) {
        try {
           $newlogin = new userlogin;                  
  $db =$newlogin->dbconnect();
            
            $ip_address = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
            
            $insert_query = "INSERT INTO blood_donor_audit_log
                (donor_id, action_type, old_value, new_value, affected_field, performed_by, ip_address, is_locked, performed_date)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
            
            $stmt = $db->prepare($insert_query);
            $stmt->bind_param("issssssi", 
                $this->donor_id, $action_type, $old_value, $new_value, $affected_field, $performed_by, $ip_address, $is_locked
            );
            
            $stmt->execute();
            
        } catch (Exception $e) {
            // Silently fail - audit trail is not critical for blocking operation
        }
    }
    
    /**
     * Helper: Get Gender from Database
     */
    private function getGenderForExamination() {
        try {
           $newlogin = new userlogin;                  
  $db =$newlogin->dbconnect();
            $query = "SELECT gender FROM blood_donor_master WHERE donor_id = ?";
            $stmt = $db->prepare($query);
            $stmt->bind_param("i", $this->donor_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return $row['gender'];
            }
            return 'Other';
        } catch (Exception $e) {
            return 'Other';
        }
    }
    
    // ==================== DEFERRAL CLEARANCE & RE-EVALUATION METHODS ====================
    
    /**
     * Get all donors pending re-evaluation
     * Uses view: v_donors_pending_re_evaluation
     */
    public function getDonorsPendingReEvaluation() {
       $newlogin = new userlogin;                  
  $db =$newlogin->dbconnect();
        $sql = "SELECT * FROM v_donors_pending_re_evaluation ORDER BY days_since_clearance DESC";
        return mysqli_query($db, $sql);
    }
    
    /**
     * Get cleared deferral details for a donor
     * Returns most recent cleared deferral
     */
    public function getClearedDeferralDetails() {
       $newlogin = new userlogin;                  
  $db =$newlogin->dbconnect();
        $sql = "SELECT * FROM blood_donor_deferral 
                WHERE donor_id = {$this->donor_id}
                AND is_active = 0 
                AND clearance_date IS NOT NULL
                ORDER BY clearance_date DESC LIMIT 1";
        return mysqli_query($db, $sql);
    }
    
    /**
     * Check for recently cleared deferrals (within specified days)
     * Used for eligibility validation
     */
    public function getRecentlyClearedDeferral($days = 90) {
       $newlogin = new userlogin;                  
  $db =$newlogin->dbconnect();
        $sql = "SELECT * FROM blood_donor_deferral 
                WHERE donor_id = {$this->donor_id}
                AND is_active = 0 
                AND clearance_date >= DATE_SUB(CURDATE(), INTERVAL $days DAY)
                AND deferral_type = 'Temporary'
                ORDER BY clearance_date DESC LIMIT 1";
        return mysqli_query($db, $sql);
    }
    
    /**
     * Get re-evaluation queue entry for donor
     */
    public function getReEvaluationQueueEntry() {
       $newlogin = new userlogin;                  
  $db =$newlogin->dbconnect();
        $sql = "SELECT * FROM donor_re_evaluation_queue 
                WHERE donor_id = {$this->donor_id}
                AND status = 'Pending'
                ORDER BY created_date DESC LIMIT 1";
        return mysqli_query($db, $sql);
    }
    
    /**
     * Update re-evaluation queue status
     */
    public function updateReEvaluationStatus($status, $notes = null) {
       $newlogin = new userlogin;                  
  $db =$newlogin->dbconnect();
        $notes_sql = $notes ? ", completion_notes = '" . mysqli_real_escape_string($db, $notes) . "'" : "";
        $completed_sql = ($status == 'Completed') ? ", completed_date = NOW()" : "";
        
        $sql = "UPDATE donor_re_evaluation_queue 
                SET status = '$status',
                    modified_date = NOW()
                    $notes_sql
                    $completed_sql
                WHERE donor_id = {$this->donor_id}
                AND status = 'Pending'";
        
        return mysqli_query($db, $sql);
    }
    
    /**
     * Check if donor requires re-evaluation
     * Returns array with status and reason
     */
    public function checkReEvaluationRequired() {
       $newlogin = new userlogin;                  
  $db =$newlogin->dbconnect();
        
        // Get donor details
        $donor_result = $this->getDonorDetails();
        if (!$donor_result || $donor_result->num_rows == 0) {
            return array('required' => false, 'reason' => 'Donor not found');
        }
        
        $donor = $donor_result->fetch_assoc();
        
        // Check if status is Pending_Re_Evaluation
        if ($donor['current_status'] == 'Pending_Re_Evaluation') {
            return array(
                'required' => true, 
                'reason' => 'Donor status is Pending Re-Evaluation after deferral clearance',
                'action' => 'Complete Medical History → Physical Examination → Medical Approval'
            );
        }
        
        // Check for recently cleared deferrals
        $cleared_result = $this->getRecentlyClearedDeferral(90);
        if ($cleared_result && $cleared_result->num_rows > 0 && $donor['current_status'] != 'Eligible') {
            $cleared = $cleared_result->fetch_assoc();
            return array(
                'required' => true,
                'reason' => 'Deferral cleared on ' . date('d-M-Y', strtotime($cleared['clearance_date'])) . ' - Medical re-evaluation required',
                'action' => 'Complete full medical screening'
            );
        }
        
        return array('required' => false, 'reason' => 'No re-evaluation needed');
    }
    
    /**
     * Get donor eligibility status with detailed checks
     * Returns array with eligible flag and error messages
     */
    public function checkDonorEligibility() {
       $newlogin = new userlogin;                  
  $db =$newlogin->dbconnect();
        $errors = array();
        
        // Get donor details
        $donor_result = $this->getDonorDetails();
        if (!$donor_result || $donor_result->num_rows == 0) {
            return array('eligible' => false, 'errors' => array('Donor not found'));
        }
        
        $donor = $donor_result->fetch_assoc();
        
        // Check 1: Status must be Eligible
        if ($donor['current_status'] != 'Eligible') {
            if ($donor['current_status'] == 'Pending_Re_Evaluation') {
                $errors[] = "Donor requires medical re-evaluation after deferral clearance";
                $errors[] = "Required: Complete Medical History (Stage 2) → Physical Examination (Stage 3) → Medical Officer Approval";
                $errors[] = '<a href="blood_donor_re_evaluation.php?donor_id=' . $this->donor_id . '" style="color: #dc3545; font-weight: bold;">Click here to start re-evaluation process</a>';
            } else {
                $errors[] = "Donor status is not Eligible (Current: " . str_replace('_', ' ', $donor['current_status']) . ")";
            }
        }
        
        // Check 2: Active deferral
        $deferral_result = $this->getActiveDeferral();
        if ($deferral_result && $deferral_result->num_rows > 0) {
            $deferral = $deferral_result->fetch_assoc();
            $errors[] = "Active deferral: " . $deferral['deferral_reason'];
            if ($deferral['deferral_end_date']) {
                $errors[] = "Clearance date: " . date('d-M-Y', strtotime($deferral['deferral_end_date']));
            }
        }
        
        // Check 3: Recently cleared deferrals
        $recent_clearance = $this->getRecentlyClearedDeferral(90);
        if ($recent_clearance && $recent_clearance->num_rows > 0 && $donor['current_status'] != 'Eligible') {
            $cleared = $recent_clearance->fetch_assoc();
            $errors[] = "Deferral cleared on " . date('d-M-Y', strtotime($cleared['clearance_date'])) . " - Medical re-evaluation required";
        }
        
        // Check 4: Cooling period
        if ($donor['next_eligible_donation_date'] && $donor['next_eligible_donation_date'] > date('Y-m-d')) {
            $days_remaining = ceil((strtotime($donor['next_eligible_donation_date']) - time()) / 86400);
            $errors[] = "Cooling period not completed. Next eligible date: " . date('d-M-Y', strtotime($donor['next_eligible_donation_date'])) . " ($days_remaining days remaining)";
        }
        
        $eligible = (count($errors) == 0);
        
        return array('eligible' => $eligible, 'errors' => $errors);
    }
       public static function clearDeferralById($db, $deferral_id) {
        $sql = "UPDATE blood_donor_deferral 
                SET is_active = 0,
                    clearance_date = CURDATE(),
                    clearance_method = 'Automatic_Expiry',
                    modified_date = NOW(),
                    modified_by = 'SYSTEM_AUTO'
                WHERE deferral_id = " . intval($deferral_id);
        
        return mysqli_query($db, $sql);
    }
    
    /**
     * Update donor status to Pending_Re_Evaluation (used by automated script)
     * Static method for batch processing
     */
    public static function updateDonorToPendingReEvaluation($db, $donor_id) {
        $sql = "UPDATE blood_donor_master 
                SET current_status = 'Pending_Re_Evaluation',
                    can_donate = 0,
                    last_modified_date = NOW(),
                    last_modified_by = 'SYSTEM_AUTO',
                    notes = CONCAT(COALESCE(notes, ''), '\n[', NOW(), '] Deferral cleared automatically. Re-evaluation required.')
                WHERE donor_id = " . intval($donor_id) . "
                AND current_status = 'Deferred_Temporary'";
        
        $result = mysqli_query($db, $sql);
        return array(
            'success' => $result,
            'rows_affected' => mysqli_affected_rows($db)
        );
    }
    
    /**
     * Create audit trail entry (used by automated script)
     * Static method for batch processing
     */
    public static function createAuditTrail($db, $donor_id, $action_type, $old_value, $new_value, $performed_by = 'SYSTEM_AUTO') {
        $sql = "INSERT INTO blood_donor_audit_log (
                donor_id, action_type, old_value, new_value, performed_by, performed_date
                ) VALUES (
                " . intval($donor_id) . ", 
                '" . mysqli_real_escape_string($db, $action_type) . "', 
                '" . mysqli_real_escape_string($db, $old_value) . "', 
                '" . mysqli_real_escape_string($db, $new_value) . "', 
                '" . mysqli_real_escape_string($db, $performed_by) . "', 
                NOW()
                )";
        
        return mysqli_query($db, $sql);
    }
    
    /**
     * Add donor to re-evaluation queue (used by automated script)
     * Static method for batch processing
     */
    public static function addToReEvaluationQueue($db, $donor_id, $reason = 'Deferral clearance - Medical re-evaluation required', $priority = 'Normal', $days_until_due = 30) {
        $sql = "INSERT INTO donor_re_evaluation_queue (
                donor_id, reason, priority, due_date, created_date
                ) VALUES (
                " . intval($donor_id) . ", 
                '" . mysqli_real_escape_string($db, $reason) . "', 
                '" . mysqli_real_escape_string($db, $priority) . "', 
                DATE_ADD(CURDATE(), INTERVAL " . intval($days_until_due) . " DAY), 
                NOW()
                )";
        
        return mysqli_query($db, $sql);
    }
    
    /**
     * Get expired deferrals for automated clearance
     * Used by automated script
     */
    public static function getExpiredDeferrals() {
        $newlogin = new userlogin;                  
  $db =$newlogin->dbconnect();
        $sql = "SELECT d.deferral_id, d.donor_id, d.deferral_reason, d.deferral_end_date,
                dm.donor_unique_id, dm.first_name, dm.last_name, dm.mobile_number
                FROM blood_donor_deferral d
                INNER JOIN blood_donor_master dm ON d.donor_id = dm.donor_id
                WHERE d.is_active = 1 
                AND d.deferral_type = 'Temporary'
                AND d.deferral_end_date < CURDATE()";
        
        return mysqli_query($db, $sql);
    }
    
    /**
     * Clear expired deferral (used by automated script)
     */
    public function clearExpiredDeferral($clearance_method = 'Automatic_Expiry') {
       $newlogin = new userlogin;                  
  $db =$newlogin->dbconnect();
        
        mysqli_begin_transaction($db);
        
        try {
            // Deactivate deferral
            $sql_clear = "UPDATE blood_donor_deferral 
                         SET is_active = 0,
                             clearance_date = CURDATE(),
                             clearance_method = '$clearance_method',
                             modified_date = NOW(),
                             modified_by = 'SYSTEM_AUTO'
                         WHERE donor_id = {$this->donor_id}
                         AND is_active = 1
                         AND deferral_type = 'Temporary'
                         AND deferral_end_date < CURDATE()";
            
            if (!mysqli_query($db, $sql_clear)) {
                throw new Exception("Failed to clear deferral");
            }
            
            // Update donor status
            $sql_donor = "UPDATE blood_donor_master 
                         SET current_status = 'Pending_Re_Evaluation',
                             can_donate = 0,
                             last_modified_date = NOW(),
                             last_modified_by = 'SYSTEM_AUTO'
                         WHERE donor_id = {$this->donor_id}
                         AND current_status = 'Deferred_Temporary'";
            
            if (!mysqli_query($db, $sql_donor)) {
                throw new Exception("Failed to update donor status");
            }
            
            mysqli_commit($db);
            return array('status' => 'success', 'message' => 'Deferral cleared successfully');
            
        } catch (Exception $e) {
            mysqli_rollback($db);
            return array('status' => 'error', 'message' => $e->getMessage());
        }
    }
    
    // ==================== BLOOD COLLECTION METHODS ====================
    
    /**
     * Get collection details with unit and donor information
     * Used by blood_collection_success.php
     */
    public function getCollectionWithUnitDetails($collection_id, $unit_id) {
       $newlogin = new userlogin;                  
  $db =$newlogin->dbconnect();
        $sql = "SELECT c.*, u.unit_barcode, u.bag_number, u.unit_status, u.expiry_date, u.blood_group,
                d.donor_unique_id, d.first_name, d.last_name, d.mobile_number
                FROM blood_collections c
                INNER JOIN blood_units u ON c.collection_id = u.collection_id
                INNER JOIN blood_donor_master d ON c.donor_id = d.donor_id
                WHERE c.collection_id = $collection_id AND u.unit_id = $unit_id";
        
        return mysqli_query($db, $sql);
    }
    
    /**
     * Get all collections for a donor
     */
    public function getDonorCollections() {
       $newlogin = new userlogin;                  
  $db =$newlogin->dbconnect();
        $sql = "SELECT c.collection_id, c.collection_number, c.collection_date, c.collection_time,
                       c.blood_bag_type, c.volume_collected_ml, u.unit_barcode, u.unit_status
                FROM blood_collections c
                LEFT JOIN blood_units u ON c.collection_id = u.collection_id
                WHERE c.donor_id = {$this->donor_id}
                ORDER BY c.collection_date DESC, c.collection_time DESC";
        
        return mysqli_query($db, $sql);
    }
    
    /**
     * Get cooling period details for donor
     */
    public function getCoolingPeriodDetails() {
       $newlogin = new userlogin;                  
  $db =$newlogin->dbconnect();
        $sql = "SELECT * FROM donor_cooling_periods 
                WHERE donor_id = {$this->donor_id}
                AND end_date >= CURDATE()
                ORDER BY end_date DESC LIMIT 1";
        
        return mysqli_query($db, $sql);
    }
    
}
?>
