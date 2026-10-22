<?php 
require_once(__DIR__ . '/model/session_config.php');
include('model/treatmentClass.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Clinical Summary</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f9f9f9; }
        h2 { color: #2c3e50; margin-top: 30px; }
        .section { background: #fff; border-radius: 8px; box-shadow: 0 2px 8px #eee; padding: 24px; margin-bottom: 24px; }
        .complaints-list { list-style: decimal inside; margin-left: 0; padding-left: 0; }
        .label { font-weight: bold; color: #34495e; }
        .input-line { border: none; border-bottom: 1px solid #bbb; background: transparent; width: 200px; font-size: 1em; }
        .medication-table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        .medication-table th, .medication-table td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        .medication-table th { background: #f0f0f0; }
    </style>
</head>
<body>
    <div class="section">
        <h2>CHIEF COMPLAINTS CLINICAL SUMMARY</h2>
        <ol class="complaints-list">
            <li><input class="input-line" type="text" placeholder="Complaint 1"></li>
            <li><input class="input-line" type="text" placeholder="Complaint 2"></li>
            <li><input class="input-line" type="text" placeholder="Complaint 3"></li>
            <li><input class="input-line" type="text" placeholder="Complaint 4"></li>
            <li><input class="input-line" type="text" placeholder="Complaint 5"></li>
        </ol>
        <div style="margin-top:16px;">
            <span class="label">Since</span> <input class="input-line" type="text" placeholder="Duration"> (months/years)
        </div>
    </div>
    <div class="section">
        <h2>PAST HISTORY</h2>
        <textarea rows="2" style="width:100%;"></textarea>
    </div>
    <div class="section">
        <h2>MEDICAL HISTORY</h2>
        <textarea rows="2" style="width:100%;"></textarea>
    </div>
    <div class="section">
        <h2>FAMILY HISTORY</h2>
        <textarea rows="2" style="width:100%;"></textarea>
    </div>
    <div class="section">
        <h2>PERSONAL HISTORY</h2>
        <textarea rows="2" style="width:100%;"></textarea>
    </div>
    <div class="section">
        <h2>PREMORBID PERSONALITY</h2>
        <textarea rows="2" style="width:100%;"></textarea>
    </div>
    <div class="section">
        <h2>MENTAL STATE EXAMINATION AND CAPACITY</h2>
        <textarea rows="2" style="width:100%;"></textarea>
    </div>
    <div class="section">
        <h2>ASSESSMENT GENERAL PHYSICAL EXAMINATION</h2>
        <textarea rows="2" style="width:100%;"></textarea>
    </div>
    <div class="section">
        <h2>CLINICAL INVESTIGATIONS</h2>
        <textarea rows="2" style="width:100%;"></textarea>
    </div>
    <div class="section">
        <h2>COURSE OF TREATMENT IN HOSPITAL</h2>
        <textarea rows="2" style="width:100%;"></textarea>
    </div>
    <div class="section">
        <h2>ADVICE ON DISCHARGE PLANNED PSYCHO-SOCIAL INTERVENTION</h2>
        <textarea rows="2" style="width:100%;"></textarea>
    </div>
    <div class="section">
        <h2>MEDICATION</h2>
        <table class="medication-table">
            <tr>
                <th>NAME</th>
                <th>DOSE</th>
                <th>Frequency</th>
                <th>Duration</th>
            </tr>
            <tr>
                <td><input class="input-line" type="text" placeholder="Medication Name"></td>
                <td><input class="input-line" type="text" placeholder="Dose"></td>
                <td><input class="input-line" type="text" placeholder="Frequency"></td>
                <td><input class="input-line" type="text" placeholder="Duration"></td>
            </tr>
            <tr>
                <td><input class="input-line" type="text" placeholder="Medication Name"></td>
                <td><input class="input-line" type="text" placeholder="Dose"></td>
                <td><input class="input-line" type="text" placeholder="Frequency"></td>
                <td><input class="input-line" type="text" placeholder="Duration"></td>
            </tr>
        </table>
    </div>
</body>
</html>

 