<?php
require_once(__DIR__ . '/../model/session_config.php');

include('../model/cathLabClass.php');

// Get patient ID from request
$patient_id = isset($_POST['patient_id']) ? $_POST['patient_id'] : 0;

if(!$patient_id) {
    echo json_encode([]);
    exit();
}

try {
    $cathLab = new cathLab;
    
    // Query to get procedures for this patient
    global $db;
    
    $query = "SELECT 
                id,
                procedure_code,
                procedure_type,
                procedure_date,
                patient_id,
                admission_id
            FROM cathlab_procedure
            WHERE patient_id = ?
            AND status IN ('pending', 'completed')
            ORDER BY procedure_date DESC";
    
    $stmt = mysqli_prepare($db, $query);
    
    if(!$stmt) {
        echo json_encode([]);
        exit();
    }
    
    mysqli_stmt_bind_param($stmt, "i", $patient_id);
    mysqli_stmt_execute($stmt);
    
    $result = mysqli_stmt_get_result($stmt);
    $procedures = [];
    
    while($row = mysqli_fetch_assoc($result)) {
        $procedures[] = [
            'id' => $row['id'],
            'procedure_code' => $row['procedure_code'],
            'procedure_type' => $row['procedure_type'],
            'procedure_date' => date('d-m-Y', strtotime($row['procedure_date']))
        ];
    }
    
    echo json_encode($procedures);
    
} catch(Exception $e) {
    echo json_encode([]);
}
?>
