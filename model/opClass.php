<?php
require_once(__DIR__ . '/session_config.php');
include('dbconnect.php');

date_default_timezone_set("Asia/Kolkata");
class addOP
{

  private $admdate, $card, $fname, $age, $dob, $abha_number, $abha_address, $state, $district, $pincode, $sex, $address, $diagnosis, $department, $religion, $caste, $pname, $edu, $marital, $occupation, $economic, $cardn, $annual, $category, $mobile, $doctor, $opd_no, $subdepartment, $ptype, $pcomment, $pan, $aadhar, $policy, $mlc, $city, $consultancy;
  function setadmdate($admdate)
  {
    $this->admdate = $admdate;
  }
  function setcard($card)
  {
    $this->card = $card;
  }
  function setfname($fname)
  {
    $this->fname = $fname;
  }
  function setage($age)
  {
    $this->age = $age;
  }
  // Optional date of birth (YYYY-MM-DD). Blank is stored as NULL — patients
  // registered before central_opd gained this column carry an age only.
  function setdob($dob)
  {
    $this->dob = trim((string) $dob);
  }
  // Verified ABHA identifiers, set only when registration was opened from a
  // fetched ABHA profile (opregistration.php?abha_prefill=1).
  function setabhanumber($abhaNumber)
  {
    $this->abha_number = trim((string) $abhaNumber);
  }
  function setabhaaddress($abhaAddress)
  {
    $this->abha_address = strtolower(trim((string) $abhaAddress));
  }
  // Postal parts of the address, as a verified ABHA profile supplies them. Kept
  // alongside the free-text `address` line, which also ends with them.
  function setState($state)
  {
    $this->state = trim((string) $state);
  }
  function setDistrict($district)
  {
    $this->district = trim((string) $district);
  }
  function setPincode($pincode)
  {
    // Digits only — ABDM sends a 6-digit PIN and the column is varchar(10).
    $this->pincode = substr(preg_replace('/\D/', '', (string) $pincode), 0, 10);
  }
  // SQL literal for the dob column: a quoted date, or a bare NULL when the form
  // left it empty. Injected unquoted into the queries below, so an empty DOB does
  // not become '0000-00-00'.
  private function dobSqlLiteral()
  {
    $dob = trim((string) $this->dob);
    if ($dob === '' || $dob === '0000-00-00') {
      return 'NULL';
    }
    return "'" . $dob . "'";
  }
  function setsex($sex)
  {
    $this->sex = $sex;
  }
  function setreligion($religion)
  {
    $this->religion = $religion;
  }
  function setcaste($caste)
  {
    $this->caste = $caste;
  }
  function setedu($edu)
  {
    $this->edu = $edu;
  }
  function setmobile($mobile)
  {
    $this->mobile = $mobile;
  }
  function setpname($pname)
  {
    $this->pname = $pname;
  }
  function setmarital($marital)
  {
    $this->marital = $marital;
  }
  function setoccupation($occupation)
  {
    $this->occupation = $occupation;
  }
  function seteconomic($economic)
  {
    $this->economic = $economic;
  }
  function setcardn($cardn)
  {
    $this->cardn = $cardn;
  }
  function setdepartment($department)
  {
    $this->department = $department;
  }
  function setsubdepartment($subdepartment)
  {
    $this->subdepartment = $subdepartment;
  }
  function setdiagnosis($diagnosis)
  {
    $this->diagnosis = $diagnosis;
  }

  function setaddress($address)
  {
    $this->address = $address;
  }
  function setcategory($category)
  {
    $this->category = $category;
  }
  function setannual($annual)
  {
    $this->annual = $annual;
  }

  function setopd_no($opd_no)
  {
    $this->opd_no = $opd_no;
  }

  function setdoctor($doctor)
  {
    $this->doctor = $doctor;
  }
  function setptype($ptype)
  {
    $this->ptype = $ptype;
  }
  function setpcomment($pcomment)
  {
    $this->pcomment = $pcomment;
  }
  function setpan($pan)
  {
    $this->pan = $pan;
  }
  function setaadhar($aadhar)
  {
    $this->aadhar = $aadhar;
  }
  function setpolicy($policy)
  {
    $this->policy = $policy;
  }
  function setmlc($mlc)
  {
    $this->mlc = $mlc;
  }
  function setcity($city)
  {
    $this->city = $city;
  }
  function setconsultancy($consultancy)
  {
    $this->consultancy = $consultancy;
  }

  private function logCurlTransaction($context, $url, $payload, $response)
  {
    $newlogin = new userlogin;
    $con = $newlogin->dbconnect();

    $payloadText = is_string($payload) ? $payload : json_encode($payload);
    if ($payloadText === false) {
      $payloadText = '';
    }

    $responseText = is_string($response) ? $response : json_encode($response);
    if ($responseText === false) {
      $responseText = '';
    }

    $createdAt = date('Y-m-d H:i:s');
    $stmt = $con->prepare("INSERT INTO `api_log`(`context`, `url`, `payload`, `response`, `created_at`) VALUES (?, ?, ?, ?, ?)");
    if ($stmt) {
      $stmt->bind_param("sssss", $context, $url, $payloadText, $responseText, $createdAt);
      $stmt->execute();
      $stmt->close();
    }

    $con->close();
  }

  private function getNextCentralOpdSequence($con, $date, $column)
  {
    $dateKey = date("Ymd", strtotime($date));
    $column = $column === 'uhid' ? 'uhid' : 'opd_no';
    $prefixLength = $column === 'uhid' ? 5 : 3;
    $defaultSuffix = $column === 'uhid' ? '0001' : '0001';

    $sql = "SELECT SUBSTR(MAX(`$column`),$prefixLength,12) AS seq FROM `central_opd` WHERE `$column` LIKE '%$dateKey%'";
    $row = mysqli_fetch_assoc(mysqli_query($con, $sql));

    if (isset($row['seq']) && trim((string)$row['seq']) !== '') {
      return (string)(((int)$row['seq']) + 1);
    }

    return $dateKey . $defaultSuffix;
  }

  private function isDuplicateCentralOpdInsert($con)
  {
    return (int)mysqli_errno($con) === 1062;
  }

  function insertOP()
  {
    $y = date("y");
    $newlogin = new userlogin;
    $date = $this->admdate;
    $con = $newlogin->dbconnect();
    $query = "SELECT * FROM data_table WHERE id=1 || id=4 order by id";
    $iddata = mysqli_query($con, $query);

    while ($central_opd = mysqli_fetch_assoc($iddata)) {
      if ($central_opd['id'] == 1) {
        $opd_no = $central_opd['table_id'] + 1;
      } elseif ($central_opd['id'] == 4) {
        $uhid = $central_opd['table_id'] + 1;
      }
    }
    $y = date("Ymd", strtotime($date));
    if ($y !== substr((string)$opd_no, 0, 8)) {
      $data = "SELECT SUBSTR(MAX(opd_no),3,12) as opd FROM central_opd where opd_no like '%$y%'";
      $op_no = mysqli_fetch_assoc(mysqli_query($con, $data));
      if (isset($op_no['opd'])) {
        $opd_no = $op_no['opd'] + 1;
      } else {
        $opd_no = $y . '0001';
      }
    }
    if ($y != substr($uhid, 0, 8)) {
      $data = "SELECT SUBSTR(MAX(uhid),4,12) as uhid FROM central_opd where uhid like '%$y%'";
      $op_no = mysqli_fetch_assoc(mysqli_query($con, $data));
      if (isset($op_no['uhid'])) {
        $uhid = $op_no['uhid'] + 1;
      } else {
        $uhid = $y . '0001';
      }
    }
    $sql1 = "select ifnull(max(token),0) as tok from central_opd where date(date)='$date' and doctor='$this->doctor' and is_generated=0 ";
    $tok = mysqli_fetch_assoc(mysqli_query($con, $sql1));
    $token = $tok['tok'] + 1;


    $gender = strtoupper(trim((string)$this->sex));
    if ($gender === 'M') {
      $gender = 'MALE';
    } elseif ($gender === 'F') {
      $gender = 'FEMALE';
    }

    // Step 1: Search if customer already exists in RetailXpress by phone number
    $customerId = null;
    $phoneClean = preg_replace('/\D+/', '', (string)$this->mobile);

    $searchCurl = curl_init();
    curl_setopt_array($searchCurl, array(
      CURLOPT_URL => 'https://api.retailxpress.ai/v1/external/customer?page=1&limit=20&sort_by=createdAt&sort_order=desc&search=' . urlencode($phoneClean),
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_HTTPHEADER => array(
        'x-api-key: 8417daa38eb33753f60ecbabe28d359bea4b4589d8c791f563b00b3de7c53543'
      ),
    ));
    $searchUrl = 'https://api.retailxpress.ai/v1/external/customer?page=1&limit=20&sort_by=createdAt&sort_order=desc&search=' . urlencode($phoneClean);
    $searchResponse = curl_exec($searchCurl);
    $this->logCurlTransaction('insertOP.customerSearch', $searchUrl, array('phone_number' => $phoneClean), $searchResponse);
    curl_close($searchCurl);
    $searchResult = json_decode($searchResponse, true);

    // Check if a matching customer was found (phone AND full_name must match)
    if (isset($searchResult['data']['items']) && count($searchResult['data']['items']) > 0) {
      $candidate = $searchResult['data']['items'][0];
      if (isset($candidate['full_name']) && strcasecmp(trim($candidate['full_name']), trim($this->fname)) === 0) {
        $customerId = $candidate['id'];
      }
    } elseif (isset($searchResult['data']) && is_array($searchResult['data']) && count($searchResult['data']) > 0 && isset($searchResult['data'][0]['id'])) {
      $candidate = $searchResult['data'][0];
      if (isset($candidate['full_name']) && strcasecmp(trim($candidate['full_name']), trim($this->fname)) === 0) {
        $customerId = $candidate['id'];
      }
    }

    // Step 2: Only create customer if not found
    if (empty($customerId)) {
      $customerPayload = array(
        "full_name" => (string)$this->fname,
        "phone_number" => $phoneClean,
        "address" => (string)$this->address,
        "gender" => $gender,
        "unique_code" => "SGN" . $uhid
      );

      $customerUrl = 'https://api.retailxpress.ai/v1/external/customer';
      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL => $customerUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($customerPayload),
        CURLOPT_HTTPHEADER => array(
          'x-api-key: 8417daa38eb33753f60ecbabe28d359bea4b4589d8c791f563b00b3de7c53543',
          'Content-Type: application/json'
        ),
      ));
      $response = curl_exec($curl);
      $this->logCurlTransaction('insertOP.customerCreate', $customerUrl, $customerPayload, $response);
      curl_close($curl);
      $result = json_decode($response, true);
      $customerId = $result['data']['id'];
    }

    $maxInsertRetries = 5;
    $insertAttempts = 0;
    $res = false;
    $id = 0;
    $sql = '';

    $dobSql = $this->dobSqlLiteral();
    $abhaNumber  = mysqli_real_escape_string($con, (string) $this->abha_number);
    $abhaAddress = mysqli_real_escape_string($con, (string) $this->abha_address);
    $stateVal    = mysqli_real_escape_string($con, (string) $this->state);
    $districtVal = mysqli_real_escape_string($con, (string) $this->district);
    $pincodeVal  = mysqli_real_escape_string($con, (string) $this->pincode);

    while ($insertAttempts < $maxInsertRetries) {
      $sql = "INSERT INTO `central_opd`(`uhid`,`opd_no`,`date`, `name`, `dob`, `age`, `gender`, `address`, `diagnosis`, `dept_id`, `sub_deptid`, `religion`, `caste`, `parent_name`, `education`, `marital_status`, `occupation`, `economic_state`, `bpl_no`,`annual_income`,`category`,`mobile`,`doctor`,`created_by`,`pan`,`aadhar`,`policy`,`patient_type`,`created_at`,`token`,`city`,`mlc`,`rx_id`,`abha_number`,`abha_address`,`state`,`district`,`pincode`)
        VALUES ('SGN$uhid','OP$opd_no','$this->admdate','$this->fname',$dobSql,'$this->age','$this->sex','$this->address','$this->diagnosis','$this->department','$this->subdepartment','$this->religion','$this->caste','$this->pname','$this->edu','$this->marital','$this->occupation','$this->economic','$this->cardn','$this->annual','$this->category','$this->mobile','$this->doctor','$_SESSION[name]','$this->pan','$this->aadhar','$this->policy','$this->ptype',now(),'$token','$this->city','$this->mlc','$customerId','$abhaNumber','$abhaAddress','$stateVal','$districtVal','$pincodeVal')";

      $res = mysqli_query($con, $sql);
      if ($res) {
        $id = mysqli_insert_id($con);
        break;
      }

      if (!$this->isDuplicateCentralOpdInsert($con)) {
        break;
      }

      $opd_no = $this->getNextCentralOpdSequence($con, $date, 'opd_no');
      $uhid = $this->getNextCentralOpdSequence($con, $date, 'uhid');
      $insertAttempts++;
    }
    if ($con->connect_error) {
      die("Connection failed: " . $con->connect_error);
    }

    if ($id > 0) {



      $date = date("Y-m-d H:i:s");

      $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
      $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
      $sql1->execute();
      $sql1->close();

      $query3 = "UPDATE data_table set table_id=$opd_no WHERE id=1";
      mysqli_query($con, $query3);
      $query4 = "UPDATE data_table set table_id=$uhid WHERE id=4";
      mysqli_query($con, $query4);
      $con->close();





      echo "<script>window.open('../addOP_billing.php?opd_no=OP$opd_no&fees=$this->consultancy')</script>";
      // echo "<script>window.open('../opcasesheet.php?id=$id')</script>";
      echo "<script>window.location='../opregistration.php?success=1'</script>";
    } else {
      echo "<script>window.location='../opregistration.php?success=0'</script>";
    }
    //   echo "here";




  }

  function selectDept()
  {
    $newlogin = new userlogin;
    $con = $newlogin->dbconnect();
    $sql = "SELECT * FROM `department` WHERE `status`='active'";
    $res = mysqli_query($con, $sql);
    return $res;
  }
  function selectSubDept()
  {
    $newlogin = new userlogin;
    $con = $newlogin->dbconnect();
    $sql = "SELECT * FROM `sub_department` WHERE `status`='active'";
    $res = mysqli_query($con, $sql);
    return $res;
  }
  function selectOPreg()
  {
    $newlogin = new userlogin;
    $con = $newlogin->dbconnect();
    $sql = "SELECT * FROM `central_opd` WHERE `old_patient`=0 ORDER BY id DESC";
    $res = mysqli_query($con, $sql);
    return $res;
  }
  function revisitOP()
  {

    $date = date("Y-m-d", strtotime($this->admdate));
    $day = date("l", strtotime($this->admdate));
    $newlogin = new userlogin;
    $con = $newlogin->dbconnect();
    $query = "SELECT * FROM data_table WHERE id=1";
    $iddata = mysqli_fetch_assoc(mysqli_query($con, $query));
    $opd = $iddata['table_id'] + 1;
    $y = date("Ymd", strtotime($date));
    if ($y !== substr((string)$opd, 0, 8)) {
      $data = "SELECT SUBSTR(MAX(opd_no),3,13) as uhid FROM central_opd where opd_no like '%$y%'";
      $op_no = mysqli_fetch_assoc(mysqli_query($con, $data));
      if (isset($op_no['opd_no'])) {
        $opd = $op_no['opd_no'] + 1;
      } else {
        $opd = $y . '0001';
      }
    }

    $sql1 = "select ifnull(max(token),0) as tok from central_opd where date(date)='$date' and doctor='$this->doctor' and is_generated=0 ";
    $tok = mysqli_fetch_assoc(mysqli_query($con, $sql1));
    $token = $tok['tok'] + 1;
    $curl = curl_init();

    $patientSql = "SELECT * FROM central_opd WHERE opd_no='$this->opd_no' order by `date` desc LIMIT 1";
    $patientRes = mysqli_query($con, $patientSql);
    $patientRow = mysqli_fetch_assoc($patientRes);
    $customerId = $patientRow['rx_id'];
    //  echo "Customer ID: " . $customerId . "\n"; // Debugging output
    if (strlen($patientRow['rx_id']) == 0 || $patientRow['rx_id'] == null) {
      $gender = strtoupper(trim((string)$patientRow['gender']));
      if ($gender === 'M') {
        $gender = 'MALE';
      } elseif ($gender === 'F') {
        $gender = 'FEMALE';
      }

      $phoneClean = preg_replace('/\D+/', '', (string)$patientRow['mobile']);

      // Step 1: Search if customer already exists in RetailXpress by phone number
      $searchCurl = curl_init();
      curl_setopt_array($searchCurl, array(
        CURLOPT_URL => 'https://api.retailxpress.ai/v1/external/customer?page=1&limit=20&sort_by=createdAt&sort_order=desc&search=' . urlencode($phoneClean),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
          'x-api-key: 8417daa38eb33753f60ecbabe28d359bea4b4589d8c791f563b00b3de7c53543'
        ),
      ));
      $searchUrl = 'https://api.retailxpress.ai/v1/external/customer?page=1&limit=20&sort_by=createdAt&sort_order=desc&search=' . urlencode($phoneClean);
      $searchResponse = curl_exec($searchCurl);
      $this->logCurlTransaction('revisitOP.customerSearch', $searchUrl, array('phone_number' => $phoneClean), $searchResponse);
      curl_close($searchCurl);
      $searchResult = json_decode($searchResponse, true);

      // Check if a matching customer was found (phone AND full_name must match)
      if (isset($searchResult['data']['items']) && count($searchResult['data']['items']) > 0) {
        $candidate = $searchResult['data']['items'][0];
        if (isset($candidate['full_name']) && strcasecmp(trim($candidate['full_name']), trim($patientRow['name'])) === 0) {
          $customerId = $candidate['id'];
        }
      } elseif (isset($searchResult['data']) && is_array($searchResult['data']) && count($searchResult['data']) > 0 && isset($searchResult['data'][0]['id'])) {
        $candidate = $searchResult['data'][0];
        if (isset($candidate['full_name']) && strcasecmp(trim($candidate['full_name']), trim($patientRow['name'])) === 0) {
          $customerId = $candidate['id'];
        }
      }

      // Step 2: Only create customer if not found
      if (empty($customerId)) {
        $customerPayload = array(
          "full_name" => (string)$patientRow['name'],
          "phone_number" => $phoneClean,
          "address" => (string)$patientRow['address'],
          "gender" => $gender,
          "unique_code" => (string)$patientRow['uhid']
        );

        $customerUrl = 'https://api.retailxpress.ai/v1/external/customer';
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => $customerUrl,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => json_encode($customerPayload),
          CURLOPT_HTTPHEADER => array(
            'x-api-key: 8417daa38eb33753f60ecbabe28d359bea4b4589d8c791f563b00b3de7c53543',
            'Content-Type: application/json'
          ),
        ));
        $response = curl_exec($curl);
        $this->logCurlTransaction('revisitOP.customerCreate', $customerUrl, $customerPayload, $response);
        curl_close($curl);
        $result = json_decode($response, true);
        $customerId = $result['data']['id'];
      }
    }


    $week = date("l");
    // Carry the DOB (and ABHA identifiers) forward from the previous visit — a
    // revisit row is a copy of the patient, so dropping them would lose the link.
    $revisitDob = empty($patientRow['dob']) || $patientRow['dob'] === '0000-00-00'
      ? 'NULL' : "'" . $patientRow['dob'] . "'";
    // The follow-up screen lets staff correct Mobile / Address / City and can carry a
    // verified ABHA across from createAabha.php. Use whatever the form supplied; fall
    // back to the previous visit's value when it did not (older callers post none of
    // these, and must keep behaving exactly as before).
    $revisitMobile   = ($this->mobile  !== null && trim((string)$this->mobile)  !== '') ? $this->mobile  : $patientRow['mobile'];
    $revisitAddress  = ($this->address !== null && trim((string)$this->address) !== '') ? $this->address : $patientRow['address'];
    $revisitCity     = ($this->city    !== null && trim((string)$this->city)    !== '') ? $this->city    : $patientRow['city'];
    $revisitAbhaNo   = ($this->abha_number  !== null && trim((string)$this->abha_number)  !== '')
      ? $this->abha_number  : (isset($patientRow['abha_number'])  ? $patientRow['abha_number']  : '');
    $revisitAbhaAddr = ($this->abha_address !== null && trim((string)$this->abha_address) !== '')
      ? $this->abha_address : (isset($patientRow['abha_address']) ? $patientRow['abha_address'] : '');

    $revisitState    = ($this->state    !== null && trim((string)$this->state)    !== '') ? $this->state    : (isset($patientRow['state'])    ? $patientRow['state']    : '');
    $revisitDistrict = ($this->district !== null && trim((string)$this->district) !== '') ? $this->district : (isset($patientRow['district']) ? $patientRow['district'] : '');
    $revisitPincode  = ($this->pincode  !== null && trim((string)$this->pincode)  !== '') ? $this->pincode  : (isset($patientRow['pincode'])  ? $patientRow['pincode']  : '');

    $revisitMobile   = mysqli_real_escape_string($con, (string) $revisitMobile);
    $revisitAddress  = mysqli_real_escape_string($con, (string) $revisitAddress);
    $revisitCity     = mysqli_real_escape_string($con, (string) $revisitCity);
    $revisitAbhaNo   = mysqli_real_escape_string($con, (string) $revisitAbhaNo);
    $revisitAbhaAddr = mysqli_real_escape_string($con, (string) $revisitAbhaAddr);
    $revisitState    = mysqli_real_escape_string($con, (string) $revisitState);
    $revisitDistrict = mysqli_real_escape_string($con, (string) $revisitDistrict);
    $revisitPincode  = mysqli_real_escape_string($con, (string) $revisitPincode);

    $sql = "INSERT INTO `central_opd`(`uhid`,`date`, `name`, `dob`, `age`, `gender`, `address`, `religion`, `caste`, `parent_name`, `education`, `marital_status`, `occupation`, `economic_state`, `bpl_no`,`annual_income`,`category`,`mobile`,`opd_no`,`diagnosis`, `dept_id`,`old_patient`,`doctor`,`created_by`,`token`,`patient_type`,`city`,`rx_id`,`abha_number`,`abha_address`,`state`,`district`,`pincode`)
      values( '$patientRow[uhid]','$date','$patientRow[name]', $revisitDob, '$patientRow[age]', '$patientRow[gender]', '$revisitAddress',  '$patientRow[religion]', '$patientRow[caste]', '$patientRow[parent_name]', '$patientRow[education]', '$patientRow[marital_status]', '$patientRow[occupation]', '$patientRow[economic_state]', '$patientRow[bpl_no]','$patientRow[annual_income]','$patientRow[category]','$revisitMobile','OP$opd','$patientRow[diagnosis]','$this->department',1,'$this->doctor','$_SESSION[name]','$token','$patientRow[patient_type]','$revisitCity','$customerId','$revisitAbhaNo','$revisitAbhaAddr','$revisitState','$revisitDistrict','$revisitPincode')";
    // echo $sql;exit;
    $res = mysqli_query($con, $sql);
    if ($con->connect_error) {
      die("Connection failed: " . $con->connect_error);
    }
    $id = mysqli_insert_id($con);
    if ($id > 0) {


      $date = date("Y-m-d H:i:s");
      $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
      $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
      $sql1->execute();
      $sql1->close();

      $query4 = "UPDATE data_table set table_id=$opd WHERE id=1";
      mysqli_query($con, $query4);
      $con->close();
      echo "<script>window.open('../addOP_billing.php?opd_no=OP$opd&fees=$this->consultancy')</script>";
      // echo "<script>window.open('../opcasesheet.php?id=$id')</script>";
      echo "<script>window.location='../opregistration.php?success=1'</script>";
    } else {
      echo "<script>window.location='../opregistration.php?success=0'</script>";
    }
  }
  function selectOPreg1($from, $to)
  {
    $newlogin = new userlogin;
    $con = $newlogin->dbconnect();
    $sql = "SELECT opd.*,d.dept_name,u.name as doctor,(case when m.id > 0 then 1 else 0 end) as status FROM doctor u,department d,`central_opd` opd left join op_medicine m on m.patient_id=opd.id WHERE opd.date between '$from' and '$to' and opd.dept_id=d.id and u.id=opd.doctor group by opd.id";
    //   echo $sql;exit;
    $res = mysqli_query($con, $sql);
    return $res;
  }
  function selectOPreg_seacrh($from, $to, $search, $doc, $limit, $offset)
  {
    $newlogin = new userlogin;
    $con = $newlogin->dbconnect();
    $data = '';
    if (strlen($search) > 1) {
      $data .= "(address like '%$search%' || opd_no like '$search%' || opd.name like '%$search%' || opd.mobile like '$search%' || uhid like '$search%')";
    } else {
      $data .= "opd.date between '$from' and '$to'";
    }
    if ($_SESSION['role'] == 'Generated') {
      $data .= " and is_generated=1 ";
    }
    if ($doc > 0) {
      $data .= " and opd.doctor='$doc' and opd.is_generated=0 ";
    }
    $sql = "select opd.*,
    d.dept_name,
    u.name AS doctor,
    IF(m.patient_id IS NULL, 0, 1) AS status,
    COUNT(b.id) AS bill
FROM central_opd opd
INNER JOIN department d ON d.id = opd.dept_id
LEFT JOIN doctor u ON u.id = opd.doctor
LEFT JOIN op_medicine m ON m.patient_id = opd.id
LEFT JOIN billing b 
    ON b.patient_id = opd.id 
    AND b.service_type = 'op' WHERE $data and opd.dept_id=d.id  group by opd.id order by opd_no 
       ";
    // LIMIT $limit OFFSET $offset
    //   echo $sql;
    $res = mysqli_query($con, $sql);
    return $res;
  }
  function countOPreg($from, $to, $search, $doctor)
  {
    $newlogin = new userlogin;
    $con = $newlogin->dbconnect();

    if (strlen($search) > 1) {
      $data = "(opd.address LIKE '%$search%' 
                      OR opd.opd_no LIKE '$search%' 
                      OR opd.name LIKE '%$search%' 
                      OR opd.mobile LIKE '$search%' 
                      OR opd.uhid LIKE '$search%')";
    } else {
      $data = "opd.date BETWEEN '$from' AND '$to'";
    }
    if ($doctor > 0) {
      $data .= " and opd.doctor='$doctor' and opd.is_generated=0 ";
    }
    $sql = "
            SELECT COUNT(opd.id) AS total
            FROM central_opd opd
            INNER JOIN doctor doc ON doc.id = opd.doctor
            WHERE $data
            AND opd.doctor LIKE '$doctor%'
        ";

    $res = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($res);
    return $row['total'];
  }
  function selectOPreg2()
  {
    $newlogin = new userlogin;
    $con = $newlogin->dbconnect();
    $sql = "SELECT * FROM `central_opd` WHERE date=now()";
    $res = mysqli_query($con, $sql);
    return $res;
  }
  function getCity()
  {
    $newlogin = new userlogin;
    $con = $newlogin->dbconnect();
    $sql = "SELECT * FROM `cities` WHERE state_id=17";
    $res = mysqli_query($con, $sql);
    return $res;
  }
  function getDoctor()
  {
    $newlogin = new userlogin;
    $con = $newlogin->dbconnect();
    $day = date("l");
    if ($_SESSION['role'] == 'Admin') {
      $admin = '';
    } else {
      $admin = " and st.`day`='$day'";
    }

    $sql = "SELECT u.* FROM `doctor` u WHERE role='Doctor' and status='active' group by u.id order by u.name";
    $res = mysqli_query($con, $sql);
    return $res;
  }
  function labPatient()
  {
    $newlogin = new userlogin;
    $con = $newlogin->dbconnect();

    if ($_SESSION['role'] == 'Radiology') {
      $labid = " and is_radio=1";
    } else {
      $labid = "";
    }
    $sql = "SELECT co.`opd_no`,co.`date`,co.`name`,co.`gender`,co.`address`,co.`id`,lr.visit_no FROM `central_opd` co,`lab_report` lr,labtest_group lt WHERE lt.testid=lr.lab_result and lr.`patient_id`=co.`id` $labid group by co.`id`";
    $res = mysqli_query($con, $sql);
    return $res;
  }
  function labPatientResult($from, $to, $data)
  {
    $newlogin = new userlogin;
    $con = $newlogin->dbconnect();

    if ($_SESSION['role'] == 'Radiology') {
      $labid = " and is_radio=1";
    } else {
      $labid = "";
    }
    if (strlen($data) > 0) {
      $labid .= " and lr.visit_no='$data' ";
    } else {
      $labid .= " and lr.date between '$from' and '$to' ";
    }
    $sql = "SELECT co.`opd_no`,co.`date`,co.`name`,co.`gender`,co.`address`,co.`id`,lr.visit_no,lr.bill_no FROM `central_opd` co,`lab_report` lr,labtest_group lt WHERE lt.testid=lr.lab_result and lr.`patient_id`=co.`id` $labid  group by co.`id`,lr.bill_no";
    //   echo $sql;
    $res = mysqli_query($con, $sql);
    return $res;
  }
  function labPatient1()
  {
    $newlogin = new userlogin;
    $con = $newlogin->dbconnect();
    if ($_SESSION['role'] == 'Radiology') {
      $labid = "8,9,10,11";
    } else {
      $labid = "1,2,3,4,5,6,7";
    }
    $sql = "SELECT co.`opd_no`,co.`admission_date` as 'date',co.`name`,co.`gender`,co.`address`,co.`ipd_no`,visit_no FROM `central_ipd` co,`lab_report` lr ,labtest_group lt WHERE lt.testid=lr.lab_result and lr.`patient_id`=co.`ipd_no` and labid in ($labid) group by co.`ipd_no`";
    //   echo $sql;
    $res = mysqli_query($con, $sql);
    return $res;
  }
  function iplabPatient1($from, $to, $data)
  {
    $newlogin = new userlogin;
    $con = $newlogin->dbconnect();
    if ($_SESSION['role'] == 'Radiology' || $data == 'Radiology') {
      $labid = "8,9,10,11";
    } else {
      $labid = "1,2,3,4,5,6,7";
    }
    $sql = "SELECT co.`opd_no`,co.`admission_date` as 'date',co.`name`,co.`gender`,co.`address`,co.`ipd_no`,visit_no FROM `central_ipd` co,`lab_report` lr ,labtest_group lt WHERE lt.testid=lr.lab_result and lr.`patient_id`=co.`ipd_no` and labid in ($labid) and lr.date between '$from' and '$to' group by co.`ipd_no`";
    //   echo $sql;
    $res = mysqli_query($con, $sql);
    return $res;
  }
  function selectOPregToday()
  {
    $date = date("Y-m-d");
    $newlogin = new userlogin;
    $con = $newlogin->dbconnect();
    $sql = "SELECT * FROM `central_opd` WHERE `old_patient`=1 and date='$date' ORDER BY id";
    //   echo $sql;exit;
    $res = mysqli_query($con, $sql);
    return $res;
  }
  function getPatient($id)
  {
    $date = date("Y-m-d");
    $newlogin = new userlogin;
    $con = $newlogin->dbconnect();
    $sql = "SELECT * FROM `central_opd` WHERE id='$id'";
    //   echo $sql;exit;
    $res = mysqli_query($con, $sql);
    return $res;
  }
  function selectDepartment()
  {
    $newlogin = new userlogin;
    $con = $newlogin->dbconnect();
    $sql = "SELECT * FROM `department`";
    $res = mysqli_query($con, $sql);
    return $res;
  }

  /**
   * Save Mobile / Address / City edited on the follow-up screen back onto every
   * visit row for this patient, so the corrected details are what the next lookup
   * shows. Blank values are skipped — the follow-up form only sends a field when
   * it was editable (nothing on file), so an empty one must not wipe what is there.
   */
  function updateOPContact($opdNo, $mobile, $address, $city, $state = '', $district = '', $pincode = '')
  {
    $newlogin = new userlogin;
    $con = $newlogin->dbconnect();

    $sets = array();
    if (trim((string) $mobile)   !== '') {
      $sets[] = "`mobile`='"   . mysqli_real_escape_string($con, $mobile)   . "'";
    }
    if (trim((string) $address)  !== '') {
      $sets[] = "`address`='"  . mysqli_real_escape_string($con, $address)  . "'";
    }
    if (trim((string) $city)     !== '') {
      $sets[] = "`city`='"     . mysqli_real_escape_string($con, $city)     . "'";
    }
    if (trim((string) $state)    !== '') {
      $sets[] = "`state`='"    . mysqli_real_escape_string($con, $state)    . "'";
    }
    if (trim((string) $district) !== '') {
      $sets[] = "`district`='" . mysqli_real_escape_string($con, $district) . "'";
    }
    $pin = preg_replace('/\D/', '', (string) $pincode);
    if ($pin !== '') {
      $sets[] = "`pincode`='" . mysqli_real_escape_string($con, $pin) . "'";
    }

    if (empty($sets) || trim((string) $opdNo) === '') {
      return false;
    }

    $opdEsc = mysqli_real_escape_string($con, $opdNo);
    $sql = "UPDATE `central_opd` SET " . implode(', ', $sets) . " WHERE `opd_no`='$opdEsc'";
    return mysqli_query($con, $sql);
  }

  /**
   * Cached ABDM care-context link token for a patient, or null when none is held.
   * ABDM refuses a second link-token request while one is still live (ABDM-1092),
   * so the token is reused until it expires rather than requested per save.
   */
  function getLinkCareToken($opdNo)
  {
    $newlogin = new userlogin;
    $con = $newlogin->dbconnect();

    $opdEsc = mysqli_real_escape_string($con, (string) $opdNo);
    $res = mysqli_query($con, "SELECT `linkcare_token`, `linkRequestId` FROM `central_opd` WHERE `opd_no`='$opdEsc' ORDER BY id DESC LIMIT 1");
    $row = $res ? mysqli_fetch_assoc($res) : null;
    if (!$row || trim((string) $row['linkcare_token']) === '') {
      return null;
    }
    return $row;
  }

  /**
   * Store the link token (and the requestId ABDM correlates its callback with)
   * against every visit row for this patient.
   */
  function updateLinkCareToken($opdNo, $linkToken, $linkRequestId)
  {
    $newlogin = new userlogin;
    $con = $newlogin->dbconnect();

    if (trim((string) $opdNo) === '') {
      return false;
    }
    $opdEsc   = mysqli_real_escape_string($con, (string) $opdNo);
    $tokenEsc = mysqli_real_escape_string($con, (string) $linkToken);
    $reqEsc   = mysqli_real_escape_string($con, (string) $linkRequestId);

    $sql = "UPDATE `central_opd` SET `linkcare_token`='$tokenEsc', `linkRequestId`='$reqEsc' WHERE `opd_no`='$opdEsc'";
    return mysqli_query($con, $sql);
  }

  function editOP($id)
  {
    $date = date("Y-m-d");
    $newlogin = new userlogin;
    $con = $newlogin->dbconnect();
    $dobSql = $this->dobSqlLiteral();
    $stateVal    = mysqli_real_escape_string($con, (string) $this->state);
    $districtVal = mysqli_real_escape_string($con, (string) $this->district);
    $pincodeVal  = mysqli_real_escape_string($con, (string) $this->pincode);
    $sql = "UPDATE `central_opd` set `state`='$stateVal', `district`='$districtVal', `pincode`='$pincodeVal', `name`='$this->fname', `dob`=$dobSql, `age`='$this->age', `gender`='$this->sex', `address`='$this->address', `diagnosis`='$this->diagnosis', `dept_id`='$this->department', `religion`='$this->religion', `caste`='$this->caste', `parent_name`='$this->pname', `education`='$this->edu', `marital_status`='$this->marital', `occupation`='$this->occupation', `economic_state`='$this->economic', `bpl_no`='$this->cardn',`annual_income`='$this->annual',`category`='$this->category',`mobile`='$this->mobile',`doctor`='$this->doctor',`pan`='$this->pan',`aadhar`='$this->aadhar',`policy`='$this->policy',`remark`='$this->pcomment' WHERE id='$id'";
    //   echo $sql;exit;
    $res = mysqli_query($con, $sql);

    $date = date("Y-m-d H:i:s");
    $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
    $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
    $sql1->execute();
    $sql1->close();

    $sql = "UPDATE central_ipd set  `name`='$this->fname', `age`='$this->age', `gender`='$this->sex', `address`='$this->address', `diagnosis`='$this->diagnosis' where opd_no=(select opd_no from central_opd where id=$id)";
    $res = mysqli_query($con, $sql);

    $date = date("Y-m-d H:i:s");
    $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
    $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
    $sql1->execute();
    $sql1->close();
    $con->close();
    return $res;
  }
  function deleteOP($id)
  {
    $newlogin = new userlogin;
    $con = $newlogin->dbconnect();
    $query = "select * from central_opd where id='$id'";
    $row = mysqli_fetch_assoc(mysqli_query($con, $query));
    $sql = "DELETE from central_opd where id='$id'";
    $res = mysqli_query($con, $sql);
    $opd = substr($row['opd_no'], 2, 12) - 1;
    $uhid = substr($row['uhid'], 3, 13) - 1;
    $query = "UPDATE data_table set table_id=$opd WHERE id=1";
    mysqli_query($con, $query);
    $query = "UPDATE data_table set table_id=$uhid WHERE id=4";
    mysqli_query($con, $query);
    return $res;
  }
  function selectDoctor()
  {
    $newlogin = new userlogin;
    $con = $newlogin->dbconnect();
    $sql = "SELECT * FROM `doctor` WHERE `status`='active'";
    $res = mysqli_query($con, $sql);
    return $res;
  }
  function getOItems()
  {
    $newlogin = new userlogin;
    $con = $newlogin->dbconnect();
    $sql = "select * from `service_list` where status='active' and service_group_id in (select id FROM `service_group` WHERE bill_group not in ('BLOOD CHARGES','PATHOLOGY TEST','INVESTIGATION CHARGES','ROOM CHARGES'))";
    // echo $sql;exit;
    $result = mysqli_query($con, $sql);
    mysqli_close($con);
    return $result;
  }
  function getItems()
  {
    $newlogin = new userlogin;
    $con = $newlogin->dbconnect();
    $sql = "select * from `bill_items` where status='active'";
    // echo $sql;exit;
    $result = mysqli_query($con, $sql);
    mysqli_close($con);
    return $result;
  }

  function selectOPregSearch($from, $to, $search)
  {
    $newlogin = new userlogin;
    $con = $newlogin->dbconnect();
    $cond = '';
    if (strlen($search) > 0) {
      $cond .= " and (opd.name like '%$search%' || opd.mobile like '%$search%' || opd.opd_no like '$search%' || opd.address like '%$search%')";
    } else {
      $cond .= " and date(opd.date) between '$from' and '$to'";
    }
    if ($_SESSION['role'] == 'Doctor') {
      $cond .= " and u.id='$_SESSION[loginid]'";
    }
    $sql = "SELECT opd.*,d.dept_name,u.name as doctor,(case when m.id > 0 then 1 else 0 end) as status,m.date as mdate FROM doctor u,department d,`central_opd` opd left join op_medicine m on m.patient_id=opd.id WHERE  opd.dept_id=d.id and u.id=opd.doctor $cond group by opd.id ";

    $res = mysqli_query($con, $sql);
    return $res;
  }
  function selectPatientMaster()
  {
    $newlogin = new userlogin;
    $con = $newlogin->dbconnect();
    $sql = "SELECT opd_no,name,age,gender,mobile,address FROM `central_opd` group by name,mobile";
    $res = mysqli_query($con, $sql);
    return $res;
  }
  function getPatientType()
  {
    $newlogin = new userlogin;
    $con = $newlogin->dbconnect();
    $sql = "SELECT * FROM `patient_type`";
    $res = mysqli_query($con, $sql);
    return $res;
  }
}
