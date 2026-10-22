<?php
include_once '../model/dbconnect.php';
// Display normalisation for the care-context payload. Required directly rather
// than via abdm_config.php — this endpoint does not otherwise need the ABDM
// credentials, and pulling them in would load .env on every discover call.
require_once __DIR__ . '/../config/abdm_display.php';

header('Content-Type: application/json');

function respondJson(int $statusCode, array $payload): void
{
	http_response_code($statusCode);
	echo json_encode($payload, JSON_UNESCAPED_SLASHES);
	exit;
}

function getInputValue(array $input, array $patient, string $key): string
{
	if (isset($input[$key]) && !is_array($input[$key])) {
		return trim((string) $input[$key]);
	}

	if (isset($patient[$key]) && !is_array($patient[$key])) {
		return trim((string) $patient[$key]);
	}

	if (isset($_REQUEST[$key]) && !is_array($_REQUEST[$key])) {
		return trim((string) $_REQUEST[$key]);
	}

	return '';
}

function normalizeDigits(string $value): string
{
	return preg_replace('/\D+/', '', $value) ?? '';
}

function normalizeAlphaNumeric(string $value): string
{
	$normalized = preg_replace('/[^A-Za-z0-9]/', '', strtoupper($value));
	return $normalized ?? '';
}

function extractVerifiedIdentifier(array $verifiedIdentifiers, array $acceptedTypes): string
{
	foreach ($verifiedIdentifiers as $identifier) {
		if (!is_array($identifier)) {
			continue;
		}

		$type = strtolower(trim((string) ($identifier['type'] ?? '')));
		if (in_array($type, $acceptedTypes, true)) {
			return trim((string) ($identifier['value'] ?? ''));
		}
	}

	return '';
}

function getTableColumns(mysqli $con, string $table): array
{
	$columns = [];
	$result = $con->query("SHOW COLUMNS FROM `$table`");

	if ($result instanceof mysqli_result) {
		while ($row = $result->fetch_assoc()) {
			$columns[$row['Field']] = true;
		}
		$result->free();
	}

	return $columns;
}

function buildSelectColumns(string $table, array $columns): string
{
	$dateColumn = $table === 'central_opd' ? 'date' : 'admission_date';
	$ipdSelect = $table === 'central_ipd' && isset($columns['ipd_no']) ? 'ipd_no' : 'NULL';
	$opdSelect = isset($columns['opd_no']) ? 'opd_no' : 'NULL';
	$abhaNumberSelect = isset($columns['abha_number']) ? 'abha_number' : "''";
	$abhaAddressSelect = isset($columns['abha_address']) ? 'abha_address' : "''";
	$doctorSelect = isset($columns['doctor']) ? 'doctor' : "''";
	$deptSelect = isset($columns['dept_id']) ? 'dept_id' : 'NULL';

	return "
		SELECT
			'" . ($table === 'central_opd' ? 'OPD' : 'IPD') . "' AS record_type,
			id,
			$opdSelect AS opd_no,
			$ipdSelect AS ipd_no,
			$dateColumn AS visit_date,
			name,
			age,
			gender,
			mobile,
			address,
			diagnosis,
			$deptSelect AS dept_id,
			$doctorSelect AS doctor,
			$abhaNumberSelect AS abha_number,
			$abhaAddressSelect AS abha_address
		FROM `$table`
	";
}

function fetchPatientRecords(mysqli $con, string $table, array $columns, array $lookup): array
{
	$conditions = [];
	$params = [];
	$types = '';

	if (!empty($lookup['mobile']) && isset($columns['mobile'])) {
		$conditions[] = "REPLACE(REPLACE(REPLACE(mobile, ' ', ''), '-', ''), '+', '') = ?";
		$params[] = $lookup['mobile'];
		$types .= 's';
	}

	if (!empty($lookup['abha_number']) && isset($columns['abha_number'])) {
		$conditions[] = "REPLACE(abha_number, '-', '') = ?";
		$params[] = $lookup['abha_number'];
		$types .= 's';
	}

	if (!empty($lookup['abha_address']) && isset($columns['abha_address'])) {
		$conditions[] = 'LOWER(abha_address) = LOWER(?)';
		$params[] = $lookup['abha_address'];
		$types .= 's';
	}

	if ($table === 'central_opd' && !empty($lookup['opd_no']) && isset($columns['opd_no'])) {
		$conditions[] = 'opd_no = ?';
		$params[] = $lookup['opd_no'];
		$types .= 's';
	}

	if ($table === 'central_ipd') {
		if (!empty($lookup['ipd_no']) && isset($columns['ipd_no'])) {
			$conditions[] = 'ipd_no = ?';
			$params[] = $lookup['ipd_no'];
			$types .= 's';
		}

		if (!empty($lookup['opd_no']) && isset($columns['opd_no'])) {
			$conditions[] = 'opd_no = ?';
			$params[] = $lookup['opd_no'];
			$types .= 's';
		}
	}

	if ($conditions === []) {
		return [];
	}

	$sql = buildSelectColumns($table, $columns) . ' WHERE ' . implode(' OR ', $conditions) . ' ORDER BY visit_date DESC, id DESC';
	$stmt = $con->prepare($sql);

	if (!$stmt) {
		return [];
	}

	$stmt->bind_param($types, ...$params);
	$stmt->execute();
	$result = $stmt->get_result();
	$records = [];

	if ($result instanceof mysqli_result) {
		while ($row = $result->fetch_assoc()) {
			$records[] = $row;
		}
		$result->free();
	}

	$stmt->close();

	return $records;
}

/**
 * M2-002 patient-matching PRIORITY (user-initiated linking discover):
 *   1. ABHA (address or number)   2. Mobile   3. Demographic (name+gender+YOB)
 * Tries each tier in order and returns the FIRST that yields records, so a
 * weaker identifier never overrides a stronger one. Only used for ABDM discover;
 * the manual link UI keeps its single-identifier lookup.
 *
 * @return array{0: array, 1: array, 2: string}  [opdRecords, ipdRecords, tier]
 */
function fetchByPriority(mysqli $con, array $opdColumns, array $ipdColumns, array $lookup, array $patient): array
{
	// Tier 1 — ABHA address or number
	if ($lookup['abha_number'] !== '' || $lookup['abha_address'] !== '') {
		$t1  = ['abha_number' => $lookup['abha_number'], 'abha_address' => $lookup['abha_address'], 'mobile' => '', 'opd_no' => '', 'ipd_no' => ''];
		$opd = fetchPatientRecords($con, 'central_opd', $opdColumns, $t1);
		$ipd = fetchPatientRecords($con, 'central_ipd', $ipdColumns, $t1);
		if ($opd !== [] || $ipd !== []) {
			return [$opd, $ipd, 'abha'];
		}
	}

	// Tier 2 — mobile
	if ($lookup['mobile'] !== '') {
		$t2  = ['abha_number' => '', 'abha_address' => '', 'mobile' => $lookup['mobile'], 'opd_no' => '', 'ipd_no' => ''];
		$opd = fetchPatientRecords($con, 'central_opd', $opdColumns, $t2);
		$ipd = fetchPatientRecords($con, 'central_ipd', $ipdColumns, $t2);
		if ($opd !== [] || $ipd !== []) {
			return [$opd, $ipd, 'mobile'];
		}
	}

	// Tier 3 — demographic (exact name + gender initial + year of birth)
	$demo = fetchByDemographics($con, $opdColumns, $patient);
	if ($demo !== []) {
		return [$demo, [], 'demographic'];
	}

	return [[], [], 'none'];
}

/**
 * Conservative demographic match (last-resort tier): exact name + year-of-birth
 * (+ gender initial when present), OPD only (central_ipd has no dob column).
 */
function fetchByDemographics(mysqli $con, array $opdColumns, array $patient): array
{
	$name = trim((string) ($patient['name'] ?? ''));
	$yob  = (int) ($patient['yearOfBirth'] ?? 0);
	if ($name === '' || $yob < 1900 || $yob > (int) date('Y')) {
		return [];
	}
	if (!isset($opdColumns['name']) || !isset($opdColumns['dob'])) {
		return [];
	}

	$sql    = buildSelectColumns('central_opd', $opdColumns) . ' WHERE LOWER(name) = LOWER(?) AND YEAR(dob) = ?';
	$params = [$name, $yob];
	$types  = 'si';

	$genderInitial = strtoupper(substr(trim((string) ($patient['gender'] ?? '')), 0, 1));
	if ($genderInitial !== '' && isset($opdColumns['gender'])) {
		$sql .= ' AND UPPER(LEFT(gender, 1)) = ?';
		$params[] = $genderInitial;
		$types   .= 's';
	}
	$sql .= ' ORDER BY visit_date DESC, id DESC';

	$stmt = $con->prepare($sql);
	if (!$stmt) {
		return [];
	}
	$stmt->bind_param($types, ...$params);
	$stmt->execute();
	$result  = $stmt->get_result();
	$records = [];
	if ($result instanceof mysqli_result) {
		while ($row = $result->fetch_assoc()) {
			$records[] = $row;
		}
		$result->free();
	}
	$stmt->close();

	return $records;
}

function syncAbhaIdentifiers(mysqli $con, string $table, array $columns, array &$records, array $lookup): void
{
	$canUpdateAbhaNumber = isset($columns['abha_number']) && $lookup['abha_number'] !== '';
	$canUpdateAbhaAddress = isset($columns['abha_address']) && $lookup['abha_address'] !== '';

	if (!$canUpdateAbhaNumber && !$canUpdateAbhaAddress) {
		return;
	}

	foreach ($records as &$record) {
		$recordId = isset($record['id']) ? (int) $record['id'] : 0;
		if ($recordId <= 0) {
			continue;
		}

		$setClauses = [];
		$types = '';
		$params = [];

		if ($canUpdateAbhaNumber) {
			$currentAbhaNumber = trim((string) ($record['abha_number'] ?? ''));
			if ($currentAbhaNumber === '') {
				$record['abha_number'] = $lookup['abha_number'];
				$setClauses[] = 'abha_number = ?';
				$types .= 's';
				$params[] = $lookup['abha_number'];
			}
		}

		if ($canUpdateAbhaAddress) {
			$currentAbhaAddress = trim((string) ($record['abha_address'] ?? ''));
			if ($currentAbhaAddress === '') {
				$record['abha_address'] = $lookup['abha_address'];
				$setClauses[] = 'abha_address = ?';
				$types .= 's';
				$params[] = $lookup['abha_address'];
			}
		}

		if ($setClauses === []) {
			continue;
		}

		$sql = 'UPDATE `' . $table . '` SET ' . implode(', ', $setClauses) . ' WHERE id = ? LIMIT 1';
		$stmt = $con->prepare($sql);

		if (!$stmt) {
			continue;
		}

		$types .= 'i';
		$params[] = $recordId;
		$stmt->bind_param($types, ...$params);
		$stmt->execute();
		$stmt->close();
	}
	unset($record);
}

function getUniqueValues(array $records, string $column, bool $castToInt = false): array
{
	$values = [];

	foreach ($records as $record) {
		if (!isset($record[$column]) || $record[$column] === '' || $record[$column] === null) {
			continue;
		}

		$value = $castToInt ? (int) $record[$column] : trim((string) $record[$column]);
		if ($value === '' || $value === 0 && !$castToInt) {
			continue;
		}

		$values[(string) $value] = $value;
	}

	return array_values($values);
}

function buildSqlIntList(array $values): string
{
	$items = array_map('intval', $values);
	$items = array_values(array_filter($items, static function (int $value): bool {
		return $value > 0;
	}));

	return $items === [] ? '' : implode(',', $items);
}

function buildSqlStringList(mysqli $con, array $values): string
{
	$items = [];

	foreach ($values as $value) {
		$stringValue = trim((string) $value);
		if ($stringValue === '') {
			continue;
		}

		$items[] = "'" . $con->real_escape_string($stringValue) . "'";
	}

	$items = array_values(array_unique($items));

	return $items === [] ? '' : implode(',', $items);
}

function fetchRows(mysqli $con, string $sql): array
{
	$result = $con->query($sql);
	if (!$result instanceof mysqli_result) {
		return [];
	}

	$rows = [];
	while ($row = $result->fetch_assoc()) {
		$rows[] = $row;
	}
	$result->free();

	return $rows;
}

function fetchHiTypeRecords(mysqli $con, array $opdRecords, array $ipdRecords): array
{
	$opdIds = getUniqueValues($opdRecords, 'id', true);
	$ipdNos = getUniqueValues($ipdRecords, 'ipd_no');

	$hiTypeRecords = [
		'OPConsultation' => [],
		'WellnessRecord' => [],
		'Prescription' => [],
		'ImmunizationRecord' => [],
		'DischargeSummary' => [],
		'HealthDocumentRecord' => [],
		'Invoice' => []
	];

	$opdIdList = buildSqlIntList($opdIds);
	$ipdNoList = buildSqlStringList($con, $ipdNos);

	if ($opdIdList !== '') {
		$hiTypeRecords['OPConsultation'] = fetchRows($con, "
			SELECT
				co.id,
				co.id AS patient_id,
				co.opd_no,
				co.date AS visit_date,
				co.diagnosis,
				co.doctor,
				co.dept_id
			FROM central_opd co
			WHERE co.id IN ($opdIdList)
			ORDER BY co.date DESC, co.id DESC
		");

		$hiTypeRecords['WellnessRecord'] = fetchRows($con, "
			SELECT
				oe.id,
				oe.patient_id,
				co.opd_no,
				co.date AS visit_date,
				oe.bp,
				oe.rbs,
				oe.pr,
				oe.weight,
				oe.diagnosis,
				oe.next_visit_date
			FROM op_examination oe
			INNER JOIN central_opd co ON co.id = oe.patient_id
			WHERE oe.patient_id IN ($opdIdList)
			ORDER BY co.date DESC, oe.id DESC
		");

		$hiTypeRecords['Prescription'] = fetchRows($con, "
			SELECT
				om.id,
				om.patient_id,
				co.opd_no,
				om.date,
				m.name AS medicine_name,
				om.dosage,
				om.qty,
				om.frequency,
				om.`when` AS medicine_when,
				om.days,
				om.instruction
			FROM op_medicine om
			LEFT JOIN medicine m ON m.id = om.medicine
			INNER JOIN central_opd co ON co.id = om.patient_id
			WHERE om.patient_id IN ($opdIdList)
			ORDER BY om.date DESC, om.id DESC
		");

		$hiTypeRecords['ImmunizationRecord'] = fetchRows($con, "
			SELECT
				im.id,
				im.patient_id,
				co.opd_no,
				im.immunization_name,
				im.next_date,
				im.created_at
			FROM immunization im
			INNER JOIN central_opd co ON co.id = im.patient_id
			WHERE im.patient_id IN ($opdIdList)
			ORDER BY im.created_at DESC, im.id DESC
		");
	}

	if ($ipdNoList !== '') {
		$hiTypeRecords['DischargeSummary'] = fetchRows($con, "
			SELECT
				d.*,
				ci.name,
				ci.opd_no,
				ci.ipd_no
			FROM discharge d
			LEFT JOIN central_ipd ci ON ci.ipd_no = d.patient_id
			WHERE d.patient_id IN ($ipdNoList)
			ORDER BY d.discharge_date DESC, d.id DESC
		");

		$hiTypeRecords['HealthDocumentRecord'] = fetchRows($con, "
			SELECT
				pd.id,
				pd.patient_no AS patient_id,
				pd.patient_no AS ipd_no,
				pd.patient_name,
				pd.document_name,
				pd.file_path,
				pd.uploaded_by,
				pd.uploaded_at
			FROM patient_documents pd
			WHERE pd.patient_type = 'IP'
				AND pd.patient_no IN ($ipdNoList)
			ORDER BY pd.uploaded_at DESC, pd.id DESC
		");
	}

	$invoiceConditions = [];
	if ($opdIdList !== '') {
		$invoiceConditions[] = "(b.patient_id IN ($opdIdList))";
	}
	if ($ipdNoList !== '') {
		$invoiceConditions[] = "(b.patient_id IN ($ipdNoList))";
	}

	if ($invoiceConditions !== []) {
		$hiTypeRecords['Invoice'] = fetchRows($con, "
			SELECT
				b.id,
				b.bill_no,
				b.patient_id,
				b.patient_type,
				b.service_type,
				b.date,
				b.amount,
				b.paid_amount,
				b.status,
				b.doctor,
				b.collected_by
			FROM billing b
			WHERE " . implode(' OR ', $invoiceConditions) . "
			ORDER BY b.date DESC, b.id DESC
		");
	}

	return $hiTypeRecords;
}

function buildHiTypeSummary(array $hiTypeRecords): array
{
	$supported = array_keys($hiTypeRecords);
	$counts = [];
	$total = 0;

	foreach ($hiTypeRecords as $hiType => $records) {
		$counts[$hiType] = count($records);
		$total += $counts[$hiType];
	}

	return [
		'supported' => $supported,
		'counts' => $counts,
		'total' => $total,
		'records' => $hiTypeRecords
	];
}

function getRequestHeadersNormalized(): array
{
	if (function_exists('getallheaders')) {
		$headers = getallheaders();
	} elseif (function_exists('apache_request_headers')) {
		$headers = apache_request_headers();
	} else {
		$headers = [];
		foreach ($_SERVER as $key => $value) {
			if (strpos($key, 'HTTP_') === 0) {
				$headerName = strtolower(str_replace('_', '-', substr($key, 5)));
				$headers[$headerName] = $value;
			}
		}
		if (isset($_SERVER['CONTENT_TYPE'])) {
			$headers['content-type'] = $_SERVER['CONTENT_TYPE'];
		}
	}

	$normalized = [];
	foreach ($headers as $name => $value) {
		$normalized[strtolower((string) $name)] = $value;
	}

	return $normalized;
}

function getCallbackRequestId(array $headers, array $input): ?string
{
	$candidates = [
		$headers['request-id'] ?? null,
		$headers['x-request-id'] ?? null,
		$input['requestId'] ?? null,
		$input['response']['requestId'] ?? null
	];

	foreach ($candidates as $candidate) {
		if (!is_array($candidate) && trim((string) $candidate) !== '') {
			return trim((string) $candidate);
		}
	}

	return null;
}

function buildReferenceNumber(string $hiType, array $records): string
{
	$firstRecord = $records[0] ?? [];

	switch ($hiType) {
		case 'OPConsultation':
			return 'OPCON-' . date('Ymd') . '/' . ($firstRecord['patient_id'] ?? '0');
		case 'WellnessRecord':
			return 'WELL-' . date('Ymd') . '/' . ($firstRecord['patient_id'] ?? '0');
		case 'Prescription':
			return 'PRES-' . date('Ymd') . '/' . ($firstRecord['patient_id'] ?? '0');
		case 'ImmunizationRecord':
			return 'IMMUNI-' . date('Ymd') . '/' . ($firstRecord['patient_id'] ?? '0');
		case 'DischargeSummary':
			return 'DIS-' . date('Ymd') . '/' . ($firstRecord['patient_id'] ?? '0');
		case 'HealthDocumentRecord':
			return 'HDR-' . date('Ymd') . '/' . ($firstRecord['patient_id'] ?? '0');
		case 'Invoice':
			return 'INV-' . date('Ymd') . '/' . ($firstRecord['patient_id'] ?? '0');
		default:
			return strtoupper($hiType) . '-' . date('Ymd');
	}
}

function buildDisplayTitle(string $hiType, array $records): string
{
	$firstRecord = $records[0] ?? [];
	$patientId = $firstRecord['patient_id'] ?? ($firstRecord['ipd_no'] ?? '');

	switch ($hiType) {
		case 'OPConsultation':
			return 'OP Consultation for Patient ID: ' . $patientId;
		case 'WellnessRecord':
			return 'Wellness Record - Patient ID: ' . $patientId;
		case 'Prescription':
			return 'Prescription for Patient ID: ' . $patientId;
		case 'ImmunizationRecord':
			return 'Immunization for Patient ID: ' . $patientId;
		case 'DischargeSummary':
			return 'Discharge Summary for Patient ID: ' . $patientId;
		case 'HealthDocumentRecord':
			return 'Health Document Record for Patient ID: ' . $patientId;
		case 'Invoice':
			return 'Invoice for Patient ID: ' . $patientId;
		default:
			return $hiType;
	}
}

function buildCareContextDisplay(string $hiType, array $record): string
{
	switch ($hiType) {
		case 'OPConsultation':
			$parts = [];
			if (!empty($record['visit_date'])) {
				$parts[] = date('d.m.Y', strtotime((string) $record['visit_date']));
			}
			if (!empty($record['consultancy'])) {
				$parts[] = 'Consultancy- ' . $record['consultancy'];
			}
			if (!empty($record['diagnosis'])) {
				$parts[] = 'Diagnosis- ' . $record['diagnosis'];
			}
			if (!empty($record['opd_no'])) {
				$parts[] = 'OPD No- ' . $record['opd_no'];
			}
			return implode(', ', $parts);

		case 'WellnessRecord':
			$parts = [];
			if (!empty($record['visit_date'])) {
				$parts[] = date('d.m.Y', strtotime((string) $record['visit_date']));
			}
			if (!empty($record['bp'])) {
				$parts[] = 'BP- ' . $record['bp'];
			}
			if (!empty($record['rbs'])) {
				$parts[] = 'RBS- ' . $record['rbs'];
			}
			if (!empty($record['pr'])) {
				$parts[] = 'PR- ' . $record['pr'];
			}
			if (!empty($record['weight'])) {
				$parts[] = 'Weight- ' . $record['weight'] . ' KGs';
			}
			if (!empty($record['diagnosis'])) {
				$parts[] = 'Diagnosis- ' . $record['diagnosis'];
			}
			return implode(', ', $parts);

		case 'Prescription':
			$parts = [];
			if (!empty($record['date'])) {
				$parts[] = date('d.m.Y', strtotime((string) $record['date']));
			}
			if (!empty($record['medicine_name'])) {
				$parts[] = $record['medicine_name'];
			}
			if (!empty($record['dosage'])) {
				$parts[] = $record['dosage'];
			}
			if (!empty($record['frequency'])) {
				$parts[] = $record['frequency'];
			}
			return implode(', ', $parts);

		case 'ImmunizationRecord':
			$parts = [];
			if (!empty($record['created_at'])) {
				$parts[] = date('d.m.Y', strtotime((string) $record['created_at']));
			}
			if (!empty($record['immunization_name'])) {
				$parts[] = $record['immunization_name'];
			}
			if (!empty($record['next_date'])) {
				$parts[] = 'Next date: ' . $record['next_date'];
			}
			return implode(', ', $parts);

		case 'DischargeSummary':
			$parts = [];
			if (!empty($record['discharge_date'])) {
				$parts[] = 'Discharge Summary ' . date('d.m.Y', strtotime((string) $record['discharge_date']));
			}
			if (!empty($record['diagnosis'])) {
				$parts[] = 'Diagnosis- ' . $record['diagnosis'];
			}
			if (!empty($record['discharge_condition'])) {
				$parts[] = 'Condition- ' . $record['discharge_condition'];
			}
			return implode(', ', $parts);

		case 'HealthDocumentRecord':
			$parts = [];
			if (!empty($record['uploaded_at'])) {
				$parts[] = date('d.m.Y', strtotime((string) $record['uploaded_at']));
			}
			if (!empty($record['document_name'])) {
				$parts[] = $record['document_name'];
			}
			if (!empty($record['uploaded_by'])) {
				$parts[] = 'Uploaded by- ' . $record['uploaded_by'];
			}
			return implode(', ', $parts);

		case 'Invoice':
			$parts = [];
			if (!empty($record['date'])) {
				$parts[] = date('d.m.Y', strtotime((string) $record['date']));
			}
			if (!empty($record['bill_no'])) {
				$parts[] = 'Invoice ' . $record['bill_no'];
			}
			if (!empty($record['service_type'])) {
				$parts[] = $record['service_type'];
			}
			if (isset($record['paid_amount']) && $record['paid_amount'] !== '') {
				$parts[] = 'Amount: ' . number_format((float) $record['paid_amount'], 2, '.', '');
			}
			return implode(', ', $parts);

		default:
			return $hiType;
	}
}

function formatAbdmCount(int $count): string
{
	$count = max(1, min(20, $count));
	return str_pad((string) $count, 2, '0', STR_PAD_LEFT);
}

function buildPatientEntries(array $hiTypeRecords): array
{
	$entries = [];

	foreach ($hiTypeRecords as $hiType => $records) {
		// Invoice is emitted per-bill (group ref INV/{billId}) by buildInvoiceEntries
		// so fhir_records can resolve the exact bill; skip it in the grouped path.
		if ($records === [] || $hiType === 'Invoice') {
			continue;
		}

		$careContexts = [];
		foreach ($records as $record) {
			$careContexts[] = [
				'referenceNumber' => trim((string) ($record['id'] ?? uniqid('', true))),
				'display' => buildCareContextDisplay($hiType, $record)
			];
		}

		$careContextChunks = array_chunk($careContexts, 20);
		$totalChunks = count($careContextChunks);

		foreach ($careContextChunks as $index => $careContextChunk) {
			$referenceNumber = buildReferenceNumber($hiType, $records);
			if ($totalChunks > 1) {
				$referenceNumber .= '-' . ($index + 1);
			}

			$entries[] = [
				'referenceNumber' => $referenceNumber,
				'display' => buildDisplayTitle($hiType, $records),
				'careContexts' => $careContextChunk,
				'hiType' => $hiType,
				'count' => formatAbdmCount(count($careContextChunk))
			];
		}
	}

	return $entries;
}

/**
 * Invoices are emitted one entry PER bill with group ref `INV/{billId}` (the
 * same format as save-time linking) so api/fhir_records.php resolves the exact
 * bill + line items. The legacy grouped `INV-{date}/{patient_id}` format put the
 * patient id where fhir_records expects the bill id → wrong/empty line items.
 */
function buildInvoiceEntries(array $invoiceRecords): array
{
	$entries = [];
	foreach ($invoiceRecords as $rec) {
		$billId = trim((string) ($rec['id'] ?? ''));
		if ($billId === '') {
			continue;
		}
		$entries[] = [
			'referenceNumber' => 'INV/' . $billId,
			'display'         => 'Invoice for Patient ID: ' . ($rec['patient_id'] ?? ''),
			'careContexts'    => [[
				'referenceNumber' => $billId,
				'display'         => buildCareContextDisplay('Invoice', $rec)
			]],
			'hiType' => 'Invoice',
			'count'  => formatAbdmCount(1)
		];
	}
	return $entries;
}

/**
 * Finalized lab tests are exposed as DiagnosticReport care contexts, one per
 * sample_status row (group ref `DIAGRPT/{sampleId}`, inner `DGR-{sampleId}`) so
 * fhir_records.php resolves the exact finalized result. Only is_finalized=1.
 */
function buildDiagnosticEntries(mysqli $con, array $opdRecords, array $ipdRecords): array
{
	$ids = [];
	foreach (getUniqueValues($opdRecords, 'id', true) as $v) { $ids[] = (string) $v; }
	foreach (getUniqueValues($ipdRecords, 'ipd_no') as $v)   { $ids[] = (string) $v; }
	$list = buildSqlStringList($con, $ids);
	if ($list === '') {
		return [];
	}

	$rows = fetchRows($con, "
		SELECT s.id AS sample_id, s.patient_id, s.test_id, s.finalized_at, lt.testname
		FROM sample_status s
		LEFT JOIN lab_test lt ON lt.testid = s.test_id
		WHERE s.is_finalized = 1 AND s.patient_id IN ($list)
		ORDER BY s.id DESC
	");

	$entries = [];
	foreach ($rows as $r) {
		$sid = trim((string) ($r['sample_id'] ?? ''));
		if ($sid === '') {
			continue;
		}
		$parts = [];
		if (!empty($r['finalized_at']) && $r['finalized_at'] !== '0000-00-00 00:00:00') {
			$parts[] = date('d.m.Y', strtotime((string) $r['finalized_at']));
		}
		$testName = !empty($r['testname']) ? $r['testname'] : 'Lab Test';
		$parts[] = $testName;

		// Structured diagnostic result. The DiagnosticReport bundle now carries
		// the printable report as an in-bundle DocumentReference (the PHR renders
		// it), so a separate HDR-DIAG HealthDocumentRecord care context is no
		// longer emitted — one clean care context per finalized sample.
		$entries[] = [
			'referenceNumber' => 'DIAGRPT/' . $sid,
			'display'         => 'Diagnostic Report for Patient ID: ' . ($r['patient_id'] ?? ''),
			'careContexts'    => [[
				'referenceNumber' => 'DGR-' . $sid,
				'display'         => implode(', ', $parts)
			]],
			'hiType' => 'DiagnosticReport',
			'count'  => formatAbdmCount(1)
		];
	}
	return $entries;
}

/**
 * Inpatient clinical care contexts — medications given during the stay
 * (`ip_medicine` → Prescription, group ref `IPPRES/{ipd_no}`, inner `IPMED-{id}`)
 * and daily vitals (`daily_entry` → WellnessRecord, group ref `IPWELL/{ipd_no}`,
 * inner `IPVIT-{id}`). Uses the SAME reference format as the discharge-time
 * auto-link so api/fhir_records.php resolves them via central_ipd/ip tables.
 * Without this, IPD Prescription/Wellness weren't discoverable for manual linking.
 */
function buildIpdClinicalEntries(mysqli $con, array $ipdRecords): array
{
	$ipdNos = getUniqueValues($ipdRecords, 'ipd_no');
	$list   = buildSqlStringList($con, $ipdNos);
	if ($list === '') {
		return [];
	}

	$entries = [];

	// Prescription — one group per ipd_no.
	$medByIpd = [];
	$medRows = fetchRows($con, "
		SELECT im.id, im.patient_id, im.date, im.dosage, m.name AS medicine_name
		FROM ip_medicine im
		LEFT JOIN medicine m ON m.id = im.medicine
		WHERE im.patient_id IN ($list)
		ORDER BY im.id ASC
	");
	foreach ($medRows as $r) {
		$ipd = trim((string) ($r['patient_id'] ?? ''));
		if ($ipd === '') {
			continue;
		}
		$name   = trim((string) ($r['medicine_name'] ?? '')) ?: 'Medication';
		$dosage = trim((string) ($r['dosage'] ?? ''));
		$day    = !empty($r['date']) ? date('d.m.Y', strtotime((string) $r['date'])) : '';
		$display = trim($name . ($dosage !== '' ? ' ' . $dosage : '') . ($day !== '' ? ', ' . $day : ''));
		$medByIpd[$ipd][] = [
			'referenceNumber' => 'IPMED-' . $r['id'],
			'display'         => $display !== '' ? $display : ('Medication ' . $r['id'])
		];
	}
	foreach ($medByIpd as $ipd => $ccs) {
		$entries[] = [
			'referenceNumber' => 'IPPRES/' . $ipd,
			'display'         => 'Inpatient Prescription for Patient ID: ' . $ipd,
			'careContexts'    => $ccs,
			'hiType'          => 'Prescription',
			'count'           => formatAbdmCount(count($ccs))
		];
	}

	// WellnessRecord (vitals) — one group per ipd_no; skip progress-only rows.
	$vitByIpd = [];
	$dailyRows = fetchRows($con, "
		SELECT id, patient_id, pulse, temp, bp, date
		FROM daily_entry
		WHERE patient_id IN ($list)
		ORDER BY id ASC
	");
	foreach ($dailyRows as $r) {
		$ipd = trim((string) ($r['patient_id'] ?? ''));
		if ($ipd === '') {
			continue;
		}
		$parts = [];
		if (trim((string) $r['bp']) !== '')    { $parts[] = 'BP ' . trim((string) $r['bp']); }
		if (trim((string) $r['pulse']) !== '')  { $parts[] = 'Pulse ' . trim((string) $r['pulse']); }
		if (trim((string) $r['temp']) !== '')   { $parts[] = 'Temp ' . trim((string) $r['temp']); }
		if (empty($parts)) {
			continue;
		}
		$day = !empty($r['date']) ? date('d.m.Y', strtotime((string) $r['date'])) : '';
		if ($day !== '') { $parts[] = $day; }
		$vitByIpd[$ipd][] = [
			'referenceNumber' => 'IPVIT-' . $r['id'],
			'display'         => implode(', ', $parts)
		];
	}
	foreach ($vitByIpd as $ipd => $ccs) {
		$entries[] = [
			'referenceNumber' => 'IPWELL/' . $ipd,
			'display'         => 'Inpatient Vitals for Patient ID: ' . $ipd,
			'careContexts'    => $ccs,
			'hiType'          => 'WellnessRecord',
			'count'           => formatAbdmCount(count($ccs))
		];
	}

	return $entries;
}

/**
 * Discharge summaries are ALSO exposed as a HealthDocumentRecord (PDF document)
 * via `HDR-DIS/{ipd_no}` (inner `HDOC-{dischargeId}`) — the document form of the
 * structured DischargeSummary, matching the discharge-time auto-link so
 * api/fhir_records.php renders it through buildDischargeSummaryHtml → PDF.
 */
function buildDischargeDocumentEntries(array $dischargeRecords): array
{
	$entries = [];
	foreach ($dischargeRecords as $r) {
		$did = trim((string) ($r['id'] ?? ''));
		$ipd = trim((string) ($r['ipd_no'] ?? ($r['patient_id'] ?? '')));
		if ($did === '' || $ipd === '') {
			continue;
		}
		$parts = [];
		if (!empty($r['discharge_date'])) {
			$parts[] = 'Discharge Summary ' . date('d.m.Y', strtotime((string) $r['discharge_date']));
		}
		$entries[] = [
			'referenceNumber' => 'HDR-DIS/' . $ipd,
			'display'         => 'Discharge Summary Document for Patient ID: ' . $ipd,
			'careContexts'    => [[
				'referenceNumber' => 'HDOC-' . $did,
				'display'         => implode(', ', $parts) !== '' ? implode(', ', $parts) : 'Discharge Summary'
			]],
			'hiType' => 'HealthDocumentRecord',
			'count'  => formatAbdmCount(1)
		];
	}
	return $entries;
}

/**
 * OPD case sheets are exposed as HealthDocumentRecord care contexts so they are
 * discoverable/consentable alongside OPConsultation & Prescription. The group
 * referenceNumber MUST be `HDR-OPC/{opd_id}` and the inner careContext ref
 * `HDOC-{exam_id}` so api/fhir_records.php routes them to buildOPDCaseSheetHtml.
 */
function buildOpdCaseSheetEntries(mysqli $con, array $opdRecords): array
{
	$opdIds = getUniqueValues($opdRecords, 'id', true);
	$list   = buildSqlIntList($opdIds);
	if ($list === '') {
		return [];
	}

	$rows = fetchRows($con, "
		SELECT oe.id AS exam_id, oe.patient_id AS opd_id, co.opd_no,
		       co.date AS visit_date, oe.diagnosis, oe.complaints
		FROM op_examination oe
		INNER JOIN central_opd co ON co.id = oe.patient_id
		WHERE oe.patient_id IN ($list)
		ORDER BY oe.id DESC
	");

	$byOpd = [];
	foreach ($rows as $r) {
		$byOpd[(string) $r['opd_id']][] = $r;
	}

	$entries = [];
	foreach ($byOpd as $opdId => $exams) {
		$careContexts = [];
		foreach (array_slice($exams, 0, 20) as $ex) {
			$parts = [];
			if (!empty($ex['visit_date'])) {
				$parts[] = date('d.m.Y', strtotime((string) $ex['visit_date']));
			}
			$parts[] = 'OPD Case Sheet';
			if (!empty($ex['diagnosis'])) {
				$parts[] = 'Diagnosis- ' . $ex['diagnosis'];
			}
			if (!empty($ex['opd_no'])) {
				$parts[] = 'OPD No- ' . $ex['opd_no'];
			}
			$careContexts[] = [
				'referenceNumber' => 'HDOC-' . $ex['exam_id'],
				'display'         => implode(', ', $parts)
			];
		}

		if ($careContexts === []) {
			continue;
		}

		$entries[] = [
			'referenceNumber' => 'HDR-OPC/' . $opdId,
			'display'         => 'OPD Case Sheet for Patient ID: ' . $opdId,
			'careContexts'    => $careContexts,
			'hiType'          => 'HealthDocumentRecord',
			'count'           => formatAbdmCount(count($careContexts))
		];
	}

	return $entries;
}

function buildMatchedBy(array $lookup, array $patientEntries): array
{
	if ($patientEntries === []) {
		return [];
	}

	$matchedBy = [];

	if (!empty($lookup['abha_number']) || !empty($lookup['abha_address'])) {
		$matchedBy[] = 'HEALTH_ID';
	}

	if (!empty($lookup['mobile'])) {
		$matchedBy[] = 'MOBILE';
	}

	if (!empty($lookup['opd_no']) || !empty($lookup['ipd_no'])) {
		$matchedBy[] = 'MR';
	}

	return $matchedBy;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'GET') {
	respondJson(405, [
		'success' => false,
		'message' => 'Only GET and POST are supported.'
	]);
}

$rawBody = file_get_contents('php://input');
$input = [];
$requestHeaders = getRequestHeadersNormalized();

if ($rawBody !== false && trim($rawBody) !== '') {
	$input = json_decode($rawBody, true);
	if (!is_array($input)) {
		respondJson(400, [
			'success' => false,
			'message' => 'Invalid JSON body.'
		]);
	}
}

$patient = isset($input['patient']) && is_array($input['patient']) ? $input['patient'] : [];
$verifiedIdentifiers = isset($patient['verifiedIdentifiers']) && is_array($patient['verifiedIdentifiers'])
	? $patient['verifiedIdentifiers']
	: [];

$abhaNumber = getInputValue($input, $patient, 'abha_number');
if ($abhaNumber === '') {
	$abhaNumber = extractVerifiedIdentifier($verifiedIdentifiers, ['abha_number']);
}

$mobile = getInputValue($input, $patient, 'mobile');
if ($mobile === '') {
	$mobile = extractVerifiedIdentifier($verifiedIdentifiers, ['mobile']);
}

$abhaAddress = getInputValue($input, $patient, 'abha_address');
if ($abhaAddress === '') {
	$abhaAddress = extractVerifiedIdentifier($verifiedIdentifiers, ['abhaaddress', 'abha_address']);
}
if ($abhaAddress === '' && isset($patient['id']) && !is_array($patient['id'])) {
	$abhaAddress = trim((string) $patient['id']);
}

$opdNo = getInputValue($input, $patient, 'opd_no');
$ipdNo = getInputValue($input, $patient, 'ipd_no');

$lookup = [
	'abha_number' => normalizeDigits($abhaNumber),
	'mobile' => normalizeDigits($mobile),
	'abha_address' => $abhaAddress,
	'opd_no' => normalizeAlphaNumeric($opdNo),
	'ipd_no' => normalizeAlphaNumeric($ipdNo)
];

if ($lookup['abha_number'] === '' && $lookup['mobile'] === '' && $lookup['abha_address'] === '' && $lookup['opd_no'] === '' && $lookup['ipd_no'] === '') {
	respondJson(422, [
		'success' => false,
		'message' => 'Provide at least one identifier: abha number, abha address, mobile, opd_no or ipd_no.'
	]);
}

try {
	$db = new userlogin();
	$con = $db->dbconnect();
} catch (Exception $exception) {
	respondJson(500, [
		'success' => false,
		'message' => 'Database connection failed.'
	]);
}

$opdColumns = getTableColumns($con, 'central_opd');
$ipdColumns = getTableColumns($con, 'central_ipd');

// ABDM discover (has transactionId / verifiedIdentifiers) → prioritized matching
// (ABHA → mobile → demographic). The manual link UI sends a single identifier
// and keeps the original OR-based lookup.
$isDiscovery = (trim((string) ($input['transactionId'] ?? '')) !== '') || !empty($verifiedIdentifiers);

if ($isDiscovery) {
	[$opdRecords, $ipdRecords, $matchTier] = fetchByPriority($con, $opdColumns, $ipdColumns, $lookup, $patient);
} else {
	$opdRecords = fetchPatientRecords($con, 'central_opd', $opdColumns, $lookup);
	$ipdRecords = fetchPatientRecords($con, 'central_ipd', $ipdColumns, $lookup);
}

syncAbhaIdentifiers($con, 'central_opd', $opdColumns, $opdRecords, $lookup);
syncAbhaIdentifiers($con, 'central_ipd', $ipdColumns, $ipdRecords, $lookup);

$hiTypeRecords = fetchHiTypeRecords($con, $opdRecords, $ipdRecords);
$patientEntries = buildPatientEntries($hiTypeRecords);

// Invoices: one entry per bill with INV/{billId} so fhir_records resolves the
// exact bill + line items (legacy grouped format broke this → null/NA items).
$patientEntries = array_merge($patientEntries, buildInvoiceEntries($hiTypeRecords['Invoice'] ?? []));

// Expose OPD case sheets as HealthDocumentRecord care contexts (routed to the
// case-sheet builder via the HDR-OPC/ reference prefix).
$patientEntries = array_merge($patientEntries, buildOpdCaseSheetEntries($con, $opdRecords));

// Finalized lab tests as DiagnosticReport care contexts (DIAGRPT/{sampleId}).
$patientEntries = array_merge($patientEntries, buildDiagnosticEntries($con, $opdRecords, $ipdRecords));

// IPD clinical care contexts: inpatient meds (IPPRES/) + daily vitals (IPWELL/),
// matching the discharge-time auto-link format so they're linkable manually too.
$patientEntries = array_merge($patientEntries, buildIpdClinicalEntries($con, $ipdRecords));

// Discharge summary DOCUMENT (HDR-DIS/) — the PDF form of each discharge summary.
$patientEntries = array_merge($patientEntries, buildDischargeDocumentEntries($hiTypeRecords['DischargeSummary'] ?? []));

$con->close();

// Single choke point for display normalisation: every builder above feeds into
// $patientEntries, so ABDM can never be handed an empty, over-long or
// odd-charactered label regardless of which one produced it ("Invalid display").
$patientEntries = abdmSanitizeCareContextEntries($patientEntries);

respondJson(200, [
	'transactionId' => $input['transactionId'] ?? null,
	'patient' => $patientEntries,
	'matchedBy' => buildMatchedBy($lookup, $patientEntries),
	'response' => [
		'requestId' => getCallbackRequestId($requestHeaders, $input)
	]
]);

