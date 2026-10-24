<?php
require_once(__DIR__ . '/model/session_config.php');

date_default_timezone_set('Asia/Kolkata');
include('model/dischargeClass.php');
include('model/optreatmentClass1.php');
require_once __DIR__ . '/upload_guard.php';

$message = '';
$messageType = '';
$selectedIpdNo = isset($_REQUEST['ipd_no']) ? trim($_REQUEST['ipd_no']) : '';
$dischargeModel = new discharge();
$dischargeModel->ensurePatientDocumentsTable();

if (isset($_POST['submit']) && $_POST['submit'] === 'upload_document') {
    $selectedIpdNo = isset($_POST['ipd_no']) ? trim($_POST['ipd_no']) : '';
    $documentName = isset($_POST['document_name']) ? trim($_POST['document_name']) : '';

    if ($selectedIpdNo === '' || $documentName === '') {
        $message = 'Please select patient and enter document name.';
        $messageType = 'danger';
    } elseif (!isset($_FILES['document_file']) || $_FILES['document_file']['error'] !== UPLOAD_ERR_OK) {
        // Surface the SPECIFIC upload error so a silent failure (size limit,
        // partial upload, missing temp dir) is diagnosable instead of generic.
        $uploadErr = isset($_FILES['document_file']) ? (int) $_FILES['document_file']['error'] : UPLOAD_ERR_NO_FILE;
        $errMap = [
            UPLOAD_ERR_INI_SIZE   => 'File is larger than the server upload limit (upload_max_filesize).',
            UPLOAD_ERR_FORM_SIZE  => 'File is larger than the form allows.',
            UPLOAD_ERR_PARTIAL    => 'The file was only partially uploaded. Please try again.',
            UPLOAD_ERR_NO_FILE    => 'Please choose a file to upload.',
            UPLOAD_ERR_NO_TMP_DIR => 'Server is missing a temporary folder (upload_tmp_dir).',
            UPLOAD_ERR_CANT_WRITE => 'Server failed to write the uploaded file to disk.',
            UPLOAD_ERR_EXTENSION  => 'A PHP extension blocked the upload.',
        ];
        $message = ($errMap[$uploadErr] ?? 'File upload failed.') . ' (code ' . $uploadErr . ')';
        $messageType = 'danger';
    } else {
        $patientRow = $dischargeModel->getIpdPatientByNo($selectedIpdNo);

        if (!$patientRow) {
            $message = 'Selected patient was not found.';
            $messageType = 'danger';
        } else {
            $patientName = $patientRow['name'];

            // ABDM PHR viewers only render PDF and images; other formats are
            // delivered but can't be opened, so restrict uploads to these.
            $allowedExtensions = array('pdf', 'jpg', 'jpeg', 'png');
            $maxSizeBytes = 10 * 1024 * 1024;

            $originalName = $_FILES['document_file']['name'];
            $fileSize = $_FILES['document_file']['size'];
            $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

            if (!in_array($extension, $allowedExtensions, true)) {
                $message = 'File type not allowed. Please upload a PDF or image (jpg, jpeg, png) file.';
                $messageType = 'danger';
            } elseif ($fileSize > $maxSizeBytes) {
                $message = 'File size must be less than or equal to 10 MB.';
                $messageType = 'danger';
            } else {
                $uploadDir = __DIR__ . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'patient_documents';

                // uploadGuardStore checks MIME, magic bytes and embedded scripts,
                // and drops the .htaccess that stops the directory executing anything.
                $upErr = null;
                $newFileName = uploadGuardStore($_FILES['document_file'], 'document', $uploadDir, $upErr);

                if ($newFileName === null) {
                    $message = $upErr;
                    $messageType = 'danger';
                } else {
                    $relativePath = 'uploads/patient_documents/' . $newFileName;
                    $uploadedBy = isset($_SESSION['name']) ? $_SESSION['name'] : 'System';

                    $documentId = $dischargeModel->insertPatientDocument($selectedIpdNo, $patientName, $documentName, $relativePath, $uploadedBy);

                    if ($documentId !== false) {
                        // Push the document to the patient's ABHA as a
                        // HealthDocumentRecord care context. Skips silently when
                        // the patient has no ABHA or auto-link is switched off.
                        if (!empty($patientRow['opd_id'])) {
                            $abhaModel = new addOTTreat();
                            $abhaModel->linkAbhaSingleContext('document', $documentId, (int) $patientRow['opd_id'], $selectedIpdNo);
                        }
                        $message = 'Document uploaded successfully.';
                        $messageType = 'success';
                    } else {
                        $message = 'File uploaded, but record save failed.';
                        $messageType = 'warning';
                    }
                }
            }
        }
    }
}

$patients = $dischargeModel->getRecentIpdPatients(500);
$documents = $dischargeModel->getPatientDocuments($selectedIpdNo, 100);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Patient Documents</title>
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link href="./css/style.css" rel="stylesheet">
</head>
<body>
    <div id="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>

    <div id="main-wrapper">
        <?php include('leftbar.php'); ?>

        <div class="content-body text-uppercase">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h4 class="mb-0">Upload Patient Document</h4>
                                    <a href="ippatient.php" class="btn btn-sm btn-secondary">Back to IP Patients</a>
                                </div>

                                <?php if ($message !== '') { ?>
                                <div class="alert alert-<?php echo $messageType; ?>"><?php echo htmlspecialchars($message); ?></div>
                                <?php } ?>

                                <form method="post" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Select Patient (IPD No)</label>
                                                <select name="ipd_no" class="form-control" required>
                                                    <option value="">Select Patient</option>
                                                    <?php foreach ($patients as $patient) { ?>
                                                        <option value="<?php echo htmlspecialchars($patient['ipd_no']); ?>" <?php echo ($selectedIpdNo === $patient['ipd_no']) ? 'selected' : ''; ?>>
                                                            <?php echo htmlspecialchars($patient['ipd_no'] . ' - ' . $patient['name'] . ' - ' . $patient['mobile']); ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Document Name</label>
                                                <input type="text" name="document_name" class="form-control" placeholder="Ex: MRI Report, ID Proof" required maxlength="255">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Upload File</label>
                                                <input type="file" name="document_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                                                <small class="text-muted">Allowed: pdf, jpg, jpeg, png (max 10 MB). Shared to the patient's ABHA as a Health Document.</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 text-right">
                                            <button type="submit" name="submit" value="upload_document" class="btn btn-primary">Upload Document</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="mb-0">Uploaded Documents</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped text-center">
                                        <thead>
                                            <tr>
                                                <th>Sl No</th>
                                                <th>IPD No</th>
                                                <th>Patient Name</th>
                                                <th>Document Name</th>
                                                <th>File</th>
                                                <th>ABHA</th>
                                                <th>Uploaded By</th>
                                                <th>Uploaded On</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (count($documents) === 0) { ?>
                                                <tr>
                                                    <td colspan="8">No documents found.</td>
                                                </tr>
                                            <?php } else { ?>
                                                <?php $sn = 1; foreach ($documents as $doc) { ?>
                                                    <tr>
                                                        <td><?php echo $sn; ?></td>
                                                        <td><?php echo htmlspecialchars($doc['patient_no']); ?></td>
                                                        <td><?php echo htmlspecialchars($doc['patient_name']); ?></td>
                                                        <td><?php echo htmlspecialchars($doc['document_name']); ?></td>
                                                        <td>
                                                            <a href="<?php echo htmlspecialchars($doc['file_path']); ?>" target="_blank" class="badge badge-info">View / Download</a>
                                                        </td>
                                                        <td>
                                                            <?php if (!empty($doc['abha_linked'])) { ?>
                                                                <span class="badge badge-success">Linked</span>
                                                            <?php } else { ?>
                                                                <span class="badge badge-light">Not linked</span>
                                                            <?php } ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($doc['uploaded_by']); ?></td>
                                                        <td><?php echo date('d-m-Y H:i', strtotime($doc['uploaded_at'])); ?></td>
                                                    </tr>
                                                <?php $sn++; } ?>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include('footer.php'); ?>
    </div>

    <script src="./vendor/global/global.min.js"></script>
    <script src="./js/quixnav-init.js"></script>
    <script src="./js/custom.min.js"></script>
</body>
</html>
