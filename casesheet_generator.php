<?php
// Disable output buffering so chunks flush to browser immediately
if (ob_get_level()) ob_end_clean();
ob_implicit_flush(true);

$age      = $_REQUEST['age']            ?? '';
$gender   = $_REQUEST['gender']         ?? '';
$diagnosis= $_REQUEST['diagnosis']      ?? '';
$dih      = $_REQUEST['dih']            ?? '';
$dept     = $_REQUEST['dept']           ?? '';
$patient  = $_REQUEST['patient']        ?? '';
$admission= $_REQUEST['admission_date'] ?? '';

$deptSafe    = htmlspecialchars($dept,    ENT_QUOTES);
$patientSafe = htmlspecialchars($patient, ENT_QUOTES);

// Output the HTML shell immediately — user sees spinner at once
echo <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{$deptSafe} Casesheet: {$patientSafe}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- marked.js renders markdown in the browser as chunks arrive -->
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <style>
        body { background:#f5faff; margin:0; font-family:'Segoe UI',Arial,sans-serif; }
        .container { background:#fff; max-width:860px; margin:30px auto; padding:28px 20px 48px; border-radius:16px; box-shadow:0 5px 22px rgba(44,62,80,.12); }
        h1,h2,h3,h4 { color:#00396d; margin-top:16px; }
        h1 { font-size:2.2rem; margin-bottom:14px; }
        h2 { font-size:1.4rem; border-bottom:1.5px solid #e0eef6; padding-bottom:4px; }
        table { width:100%; border-collapse:collapse; background:#f4fafd; font-size:1rem; }
        th,td { border:1px solid #c2dcf4; padding:6px 10px; }
        th { background:#eaf5fc; }
        ul { margin-top:6px; }
        @media (max-width:700px) { .container { padding:8px; } table { font-size:.95rem; } }
        /* Spinner */
        #loading { text-align:center; padding:60px 20px; }
        .spinner { width:48px; height:48px; border:5px solid #e0eef6; border-top-color:#00396d;
                   border-radius:50%; animation:spin 1s linear infinite; margin:0 auto 16px; }
        @keyframes spin { to { transform:rotate(360deg); } }
        #loading p { color:#00396d; font-size:1.1rem; }
        #content { display:none; }
    </style>
</head>
<body>
<div class="container">
    <div id="loading">
        <div class="spinner"></div>
        <p>Generating {$deptSafe} casesheet for <strong>{$patientSafe}</strong>&hellip;</p>
    </div>
    <div id="content"></div>
</div>
<script>
    var _md = '';
    function _append(chunk) {
        _md += chunk;
        document.getElementById('content').innerHTML = marked.parse(_md);
        if (document.getElementById('loading').style.display !== 'none') {
            document.getElementById('loading').style.display = 'none';
            document.getElementById('content').style.display = 'block';
        }
    }
    function _done() {
        document.getElementById('loading').style.display = 'none';
        document.getElementById('content').style.display = 'block';
    }
    function _error(msg) {
        document.getElementById('loading').innerHTML =
            '<p style="color:red;">Error: ' + msg + '</p>';
    }
</script>
HTML;
flush();

// --- Stream API call ---
$url  = 'https://api.deepseek.com/chat/completions';
$body = json_encode([
    "model"    => "deepseek-chat",
    "messages" => [
        ["role" => "system", "content" => "Indian Medical Assistant"],
        ["role" => "user",   "content" =>
            "Generate a comprehensive $dept casesheet for the following patient:$patient\n" .
            "admission date:$admission\nAge/Sex: $age years / $gender\n" .
            "diagnosis : $diagnosis\nadmitted days : $dih days\n\n" .
            "Include these sections: Presenting Complaints, History of Present Illness, " .
            "Past Medical & Surgical History, Family & Social History, General Examination, " .
            "Vital Signs on Admission, Systemic Examination, Provisional Diagnosis, " .
            "Investigations Ordered with results, Treatment Plan, Progress Notes, " .
            "Nursing Notes, Discharge Summary, Nursing Chart (Vitals & Medication Monitoring)."]
    ],
    "stream" => true   // ← stream enabled
]);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer sk-c693a99641e448d480f592f335d0236c'
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, $body);

// Process each SSE chunk as it arrives
curl_setopt($ch, CURLOPT_WRITEFUNCTION, function($ch, $raw) {
    $lines = explode("\n", $raw);
    foreach ($lines as $line) {
        $line = trim($line);
        if (strncmp($line, 'data: ', 6) !== 0) continue;
        $payload = substr($line, 6);
        if ($payload === '[DONE]') break;
        $obj  = json_decode($payload, true);
        $text = $obj['choices'][0]['delta']['content'] ?? '';
        if ($text === '') continue;
        // Inject a tiny script tag so the browser renders each chunk immediately
        $escaped = json_encode($text);
        echo "<script>_append($escaped);</script>\n";
        flush();
    }
    return strlen($raw);
});

$ok = curl_exec($ch);
if (!$ok || curl_errno($ch)) {
    $err = htmlspecialchars(curl_error($ch), ENT_QUOTES);
    echo "<script>_error('$err');</script>\n";
}
curl_close($ch);

echo "<script>_done();</script>\n";
echo "</body></html>";

