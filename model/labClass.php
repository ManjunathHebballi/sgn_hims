  <?php 
  require_once(__DIR__ . '/session_config.php');
  include('dbconnect.php');
  error_reporting(E_ALL);

  date_default_timezone_set('asia/kolkata');
  class lab{
  private $name,$address,$contact_name,$contact_number,$email,$bill_type,$city,$nabl,$id,$sample_id,$lab_price,$test_id,$gender,$position,$unit,$method,$body,$normal_value,$abnormal_condition,$critical_lower_limit,$critical_upper_limit,$reference_range_id,$parameter_id,$lower_limit,$upper_limit,$option,$result_bold,$heading1,$heading2,$heading3,$columns,$data_name, $user_name, $designation, $qualification, $signature_image, $default, $patient_id, $bill_no, $sample_received_date, $reporting_time, $print_signature_on_every_page, $left_signature, $right_signature, $left_e_signature, $right_e_signature, $readonly, $user_id, $visit_no;


  function setname($name)
    {
      $this->name=$name;
    }
    function setaddress($address)
    {
      $this->address=$address;
    }
    function setcontact_name($contact_name)   
    {
      $this->contact_name=$contact_name;
    }
    function setcontact_number($contact_number)
    {
      $this->contact_number=$contact_number;
    }
    function setemail($email)
    {
      $this->email=$email;
    }
    function setbill_type($bill_type)   
    {
      $this->bill_type=$bill_type;
    } 
  function setcity($city)
    {
      $this->city=$city;
    }
    function setnabl($nabl)
    {
      $this->nabl=$nabl;
    }
    function setid($id)
    {
      $this->id=$id;
    }
    function setsample_id($sample_id)
    {
      $this->sample_id=$sample_id;
    }
    function setlab_price($lab_price)
    {
      $this->lab_price=$lab_price;
    }
    function settest_id($test_id)
    {
      $this->test_id=$test_id;
    }
    function setgender($gender)
    {
      $this->gender=$gender;
    }
    function setposition($position)
    {
      $this->position=$position;
    }
    function setunit($unit)
    {
      $this->unit=$unit;
    }
    function setmethod($method)
    {
      $this->method=$method;
    }
    function setbody($body)
    {
      $this->body=$body;
    }
    function setnormal_value($normal_value)
    {
      $this->normal_value = $normal_value;
    }
    function setabnormal_condition($abnormal_condition)
    {
      $this->abnormal_condition = $abnormal_condition;
    }
    function setcritical_lower_limit($critical_lower_limit)
    {
      $this->critical_lower_limit = $critical_lower_limit;
    }
    function setcritical_upper_limit($critical_upper_limit)
    {
      $this->critical_upper_limit = $critical_upper_limit;
    }
    function setreference_range_id($reference_range_id)
    {
      $this->reference_range_id = $reference_range_id;
    }
    function setparameter_id($parameter_id)
    {
      $this->parameter_id = $parameter_id;
    }
    function setlower_limit($lower_limit)
    {
      $this->lower_limit = $lower_limit;
    }
    function setupper_limit($upper_limit)
    {
      $this->upper_limit = $upper_limit;
    }
    function setOption($option)
    {
      $this->option = $option;
    }
    function setresult_bold($result_bold)
    {
      $this->result_bold = $result_bold;
    }
    function setheading1($heading1)
    {
      $this->heading1 = $heading1;
    }
    function setheading2($heading2)
    {
      $this->heading2 = $heading2;
    }
    function setheading3($heading3)
    {
      $this->heading3 = $heading3;
    }
    function setcolumns($columns)
    {
      $this->columns = $columns;
    }
    function setdata_name($data_name)
    {
      $this->data_name = $data_name;
    }
    function setuser_name($user_name)
    {
      $this->user_name = $user_name;
    }
    function setdesignation($designation)
    {
      $this->designation = $designation;
    }
    function setqualification($qualification)
    {
      $this->qualification = $qualification;
    }
    function setsignature_image($signature_image)
    {
      $this->signature_image = $signature_image;
    }
    function setDefault($default)
    {
      $this->default = $default;
    }
    
    function setpatient_id($patient_id)
    {
      $this->patient_id = $patient_id;
    }
    function setbill_no($bill_no)
    {
      $this->bill_no = $bill_no;
    }
    function setsample_received_date($sample_received_date)
    {
      $this->sample_received_date =  date("Y-m-d H:i:s",strtotime($sample_received_date));
    }
    function setreporting_time($reporting_time)
    {
      $this->reporting_time = date("Y-m-d H:i:s",strtotime($reporting_time));
    }
    function setprint_signature_on_every_page($print_signature_on_every_page)
    {
      $this->print_signature_on_every_page = $print_signature_on_every_page;
    }
    function setleft_signature($left_signature)
    {
      $this->left_signature = $left_signature;
    }
    function setright_signature($right_signature)
    {
      $this->right_signature = $right_signature;
    }
    function setleft_e_signature($left_e_signature)
    {
      $this->left_e_signature = $left_e_signature;
    }
    function setright_e_signature($right_e_signature)
    {
      $this->right_e_signature = $right_e_signature;
    }
      function setReadonly($readonly)
      {
          $this->readonly = $readonly;
      }
      function setuser_id($user_id)
      {
          $this->user_id = $user_id;
      }
    
  function labPatientResult1($from,$to,$search)
  {
    $newlogin = new userlogin;                  
    $con=$newlogin->dbconnect();
  
    if(strlen($search)>0)
    {
      $cond=" and (co.name like '%$search%' || co.mobile like '%$search%' || co.address like '%$search%')";   
    }
    else{
        $cond=" and lr.date between '$from' and '$to'";
    }
    $sql="SELECT co.`date`,co.`name`,co.`gender`,co.`address`,co.`id`,co.mobile,lr.visit_no, lr.bill_no as lab_count,t.testname,t.testid,is_finalized,finalized_by FROM `central_opd` co,`lab_report` lr,labtest_group lt,sample_status s,lab_test t WHERE s.bill_no=lr.bill_no and t.testid=lt.labid and s.patient_id=co.`id` and s.test_id=lt.labid and lt.testid=lr.parameter_id and lr.`patient_id`=co.`id` and is_sample_acknowledge=1  $cond group by co.`id`,t.testid";
  //   echo $sql;
    $res=mysqli_query($con,$sql);
    return $res;
  }
  function labPatientResult($from,$to,$search,$collection)
  {
    $newlogin = new userlogin;                  
    $con=$newlogin->dbconnect();
   $from_query=$data="";
    if(strlen($search)>0)
    {
      $cond_opd=" (co.name like '%$search%' || co.mobile like '%$search%' || co.address like '%$search%')";
      $cond_ipd=" (ci.name like '%$search%' || ci.mobile like '%$search%' || ci.address like '%$search%')";   
    }
    else{
        $cond_opd=" lr.date between '$from' and '$to'";
        $cond_ipd=" lr.date between '$from' and '$to'";
    }
   if(isset($_SESSION['lab_dept'])&&$_SESSION['lab_dept'] != '')
    {
        // Handle lab_dept as JSON array for multiple department access
        $lab_dept = implode("','",json_decode($_SESSION['lab_dept'], true));
        $cond_opd .= " AND  t.testname_header in ('$lab_dept')";
        $cond_ipd .= " AND  t.testname_header in ('$lab_dept')";
    }
    if($collection==='1')
    {
      $data=",MAX(ss.sample_collected_by) AS sample_status";
      $from_query=" LEFT JOIN sample_status ss ON ss.bill_no = lr.bill_no 
        AND ss.patient_id = lr.patient_id
        AND ss.test_id = t.testid";
       $cond_opd.=" and ss.is_sample_collected =1";
        $cond_ipd.=" and ss.is_sample_collected=1";
    }
    else if($collection==='0')
    {
       $cond_opd .=" and lr.bill_no NOT IN (SELECT bill_no FROM sample_status WHERE patient_id = lr.patient_id AND test_id = t.testid AND is_sample_collected=1)";
    
       $cond_ipd .=" and lr.bill_no NOT IN (SELECT bill_no FROM sample_status WHERE patient_id = lr.patient_id AND test_id = t.testid AND is_sample_collected=1)";
    }
    else{
      $data=",MAX(ss.sample_collected_by) AS sample_status";
      $from_query=" LEFT JOIN sample_status ss ON ss.bill_no = lr.bill_no 
        AND ss.patient_id = lr.patient_id
        AND ss.test_id = t.testid";
    }
    $sql = "(
    SELECT 
        lr.`date`,
        co.`name`,
        co.`gender`,
        co.`age`,
        co.`id`,
        co.mobile,
        lr.visit_no,
        lr.bill_no,
        '' AS ipd_no,
        GROUP_CONCAT(DISTINCT t.testname ORDER BY t.testname SEPARATOR '<br>') AS testname,
        GROUP_CONCAT(DISTINCT t.testid ORDER BY t.testid) AS testid,
        COUNT(lr.id) AS testcount,
        t.samples
        $data
    FROM central_opd co
    JOIN lab_report lr ON lr.patient_id = co.id
    JOIN lab_test t ON t.testid = lr.testid
   $from_query
    WHERE $cond_opd
    GROUP BY lr.bill_no, co.id, lr.visit_no, t.samples
)
UNION ALL
(
    SELECT 
        lr.`date`,
        ci.`name`,
        ci.`gender`,
        ci.`age`,
        ci.`id`,
        ci.mobile,
        lr.visit_no,
        lr.bill_no,
        ci.ipd_no,
        GROUP_CONCAT(DISTINCT t.testname ORDER BY t.testname SEPARATOR '<br>') AS testname,
        GROUP_CONCAT(DISTINCT t.testid ORDER BY t.testid) AS testid,
        COUNT(lr.id) AS testcount,
        t.samples
        $data
    FROM central_ipd ci
    JOIN lab_report lr ON lr.patient_id = ci.ipd_no
    JOIN lab_test t ON t.testid = lr.testid
    $from_query
    WHERE $cond_ipd
    GROUP BY lr.bill_no, ci.id, lr.visit_no, t.samples
)
ORDER BY visit_no DESC";
    //   echo $sql;
    $res=mysqli_query($con,$sql);
    return $res;
  }
  function labPatientResult2($from,$to,$search,$acknowledge)
  {
    $newlogin = new userlogin;                  
    $con=$newlogin->dbconnect();
  
    if(strlen($search)>0)
    {
      $cond=" (co.name like '%$search%' || co.mobile like '%$search%' || co.address like '%$search%')";   
    }
    else{
        $cond=" lr.date between '$from' and '$to'";
    }
    if($acknowledge !== '') {
        $cond .= " AND s.is_sample_acknowledge = '$acknowledge'";
    }
        if(isset($_SESSION['lab_dept'])&&$_SESSION['lab_dept'] != '')
    {
        // Handle lab_dept as JSON array for multiple department access
        $lab_dept = implode("','",json_decode($_SESSION['lab_dept'], true));
        $cond .= " AND  t.testname_header in ('$lab_dept')";
    }
   
    $sql = "
      (
    SELECT 
        lr.`date`,
        co.`name`,
        co.`gender`,
        co.`age`,
        co.`id`,
        co.address,
        co.mobile,
        lr.visit_no,
        lr.bill_no,
        '' AS ipd_no,
        group_concat(distinct(t.testname)) as testname,
        s.test_id,
        COUNT(lr.id) AS testcount,
        t.samples,
        s.id AS sample_id,
        s.is_sample_acknowledge,
        s.sample_acknowledged_by,
        s.is_finalized,
        s.finalized_by,
        lr.bill_no AS lab_count,
        s.is_outsourced,
        test_result,
        t.testid
    FROM central_opd co
    JOIN lab_report lr ON lr.patient_id = co.id
    JOIN lab_test t ON t.testid = lr.testid
    JOIN sample_status s ON s.bill_no = lr.bill_no 
                        AND s.patient_id = co.id
                        AND s.test_id = t.testid
    where $cond and s.is_sample_dispatch=1
    GROUP BY s.id

    UNION ALL

    SELECT 
        lr.`date`,
        ci.`name`,
        ci.`gender`,
        ci.`age`,
        ci.`id`,
        ci.address,
        ci.mobile,
        lr.visit_no,
        lr.bill_no,
        ci.ipd_no,
        group_concat(distinct(t.testname)) as testname,
        s.test_id,
        COUNT(lr.id) AS testcount,
        t.samples,
        s.id AS sample_id,
        s.is_sample_acknowledge,
        s.sample_acknowledged_by,
        s.is_finalized,
        s.finalized_by,
        lr.bill_no AS lab_count,
        s.is_outsourced,
        test_result,
        t.testid
    FROM central_ipd ci
    JOIN lab_report lr ON lr.patient_id = ci.ipd_no
    JOIN lab_test t ON t.testid = lr.testid
    JOIN sample_status s ON s.bill_no = lr.bill_no 
                        AND s.patient_id = ci.ipd_no
                        AND s.test_id = t.testid
    where $cond and s.is_sample_dispatch=1
    GROUP BY s.id
  )
  ORDER BY visit_no DESC;

    ";
      //  echo $sql;
    $res=mysqli_query($con,$sql);
    return $res;
  }
function labPatientResult4($from,$to,$search,$finalized)
  {
    $newlogin = new userlogin;                  
    $con=$newlogin->dbconnect();
  
    if(strlen($search)>0)
    {
      $cond=" (co.name like '%$search%' || co.mobile like '%$search%' || co.address like '%$search%')";   
    }
    else{
        $cond=" lr.date between '$from' and '$to'";
    }
    if($finalized !== '') {
        $cond .= " AND s.is_finalized = '$finalized'";
    }
        if(isset($_SESSION['lab_dept'])&&$_SESSION['lab_dept'] != '')
    {
        // Handle lab_dept as JSON array for multiple department access
        $lab_dept = implode("','",json_decode($_SESSION['lab_dept'], true));
        $cond .= " AND  t.testname_header in ('$lab_dept')";
    }
   
    // A sample may only be finalized once a result has been entered. Rows that
    // were finalized before this was tracked are still shown, so the finalized
    // history stays visible.
    $finalize_gate = "(s.is_result_entered = 1 OR s.is_finalized = 1)";
    $sql = "
      (
    SELECT 
        lr.`date`,
        co.`name`,
        co.`gender`,
        co.`age`,
        co.`id`,
        co.address,
        co.mobile,
        lr.visit_no,
        lr.bill_no,
        '' AS ipd_no,
        group_concat(distinct(t.testname)) as testname,
        s.test_id,
        COUNT(lr.id) AS testcount,
        t.samples,
        s.id AS sample_id,
        s.is_sample_acknowledge,
        s.sample_acknowledged_by,
        s.is_finalized,
        s.finalized_by,
         s.is_result_entered,
      s.result_entered_by,
        lr.bill_no AS lab_count,
        s.is_outsourced,
        test_result,
        t.testid
    FROM central_opd co
    JOIN lab_report lr ON lr.patient_id = co.id
    JOIN lab_test t ON t.testid = lr.testid
    JOIN sample_status s ON s.bill_no = lr.bill_no 
                        AND s.patient_id = co.id
                        AND s.test_id = t.testid
    where $cond and s.is_sample_dispatch=1 and $finalize_gate
    GROUP BY s.id

    UNION ALL

    SELECT 
        lr.`date`,
        ci.`name`,
        ci.`gender`,
        ci.`age`,
        ci.`id`,
        ci.address,
        ci.mobile,
        lr.visit_no,
        lr.bill_no,
        ci.ipd_no,
        group_concat(distinct(t.testname)) as testname,
        s.test_id,
        COUNT(lr.id) AS testcount,
        t.samples,
        s.id AS sample_id,
        s.is_sample_acknowledge,
        s.sample_acknowledged_by,
        s.is_finalized,
        s.finalized_by,
         s.is_result_entered,
      s.result_entered_by,
        lr.bill_no AS lab_count,
        s.is_outsourced,
        test_result,
        t.testid
    FROM central_ipd ci
    JOIN lab_report lr ON lr.patient_id = ci.ipd_no
    JOIN lab_test t ON t.testid = lr.testid
    JOIN sample_status s ON s.bill_no = lr.bill_no 
                        AND s.patient_id = ci.ipd_no
                        AND s.test_id = t.testid
    where $cond and s.is_sample_dispatch=1 and $finalize_gate
    GROUP BY s.id
  )
  ORDER BY visit_no DESC;

    ";
      //  echo $sql;
    $res=mysqli_query($con,$sql);
    return $res;
  }

function labPatientResult3($from,$to,$search,$result_entered)

{
  $newlogin = new userlogin;                  
  $con=$newlogin->dbconnect();
  if(strlen($search)>0)
  {
    $cond_op=" and (co.name like '%$search%' || co.mobile like '%$search%' || co.address like '%$search%' || co.opd_no like '%$search%' || lr.visit_no like '%$search%' || lr.bill_no like '%$search%')";   
    $cond_ip=" and (ci.name like '%$search%' || ci.mobile like '%$search%' || ci.address like '%$search%' || ci.opd_no like '%$search%' || ci.ipd_no like '%$search%' || lr.visit_no like '%$search%' || lr.bill_no like '%$search%')";   
  }
  else{
      $cond_op=$cond_ip=" and lr.date between '$from' and '$to'";
  }
  if($result_entered !== '') {
      $cond_op .= " AND s.is_result_entered = '$result_entered'";
      $cond_ip .= " AND s.is_result_entered = '$result_entered'";
  }
    if(isset($_SESSION['lab_dept'])&&$_SESSION['lab_dept'] != '')
    {
      $lab_dept = implode("','",json_decode($_SESSION['lab_dept'], true));
        $cond_op .= " AND  t.testname_header in ('$lab_dept')";
        $cond_ip .= " AND  t.testname_header in ('$lab_dept')";

    }
       
 $sql = "
  (
    SELECT 
        lr.`date`,
        co.`name`,
        co.`gender`,
        co.`age`,
        co.`id`,
        co.mobile,
        lr.visit_no,
        lr.bill_no,
        '' AS ipd_no,
        group_concat(distinct(t.testname)) AS testname,
        s.test_id AS testid,
        COUNT(lr.id) AS testcount,
        t.samples,
        s.id AS sample_id,
        s.is_sample_acknowledge,
        s.sample_acknowledged_by,
        s.is_finalized,
        s.finalized_by,
        s.is_result_entered,
        s.result_entered_by,
        lr.bill_no AS lab_count,
        s.is_outsourced,
        test_result
    FROM central_opd co
    JOIN lab_report lr ON lr.patient_id = co.id
    JOIN lab_test t ON t.testid = lr.testid
    JOIN sample_status s 
          ON s.bill_no = lr.bill_no 
        AND s.patient_id = co.id 
        AND s.test_id = t.testid
    WHERE s.is_sample_acknowledge = 1
    $cond_op
    GROUP BY lr.visit_no
    UNION ALL
    SELECT 
        lr.`date`,
        ci.`name`,
        ci.`gender`,
        ci.`age`,
        ci.`id`,
        ci.mobile,
        lr.visit_no,
        lr.bill_no,
        ci.ipd_no,
        group_concat(distinct(t.testname)) AS testname,
        s.test_id AS testid,
        COUNT(lr.id) AS testcount,
        t.samples,
        s.id AS sample_id,
        s.is_sample_acknowledge,
        s.sample_acknowledged_by,
        s.is_finalized,
        s.finalized_by,
        s.is_result_entered,
        s.result_entered_by,
        lr.bill_no AS lab_count,
        s.is_outsourced,
        test_result
    FROM central_ipd ci
    JOIN lab_report lr ON lr.patient_id = ci.ipd_no
    JOIN lab_test t ON t.testid = lr.testid
    JOIN sample_status s 
          ON s.bill_no = lr.bill_no 
        AND s.patient_id = ci.ipd_no 
        AND s.test_id = t.testid
    WHERE s.is_sample_acknowledge = 1
    $cond_ip
    GROUP BY lr.visit_no
  )

  ORDER BY visit_no DESC;



  ";

    //  echo $sql;

  $res=mysqli_query($con,$sql);

  return $res;

}
  // Get samples that are collected but not yet dispatched
  function getCollectedSamples($from,$to,$search,$dispatch)
  {
    $newlogin = new userlogin;                  
    $con=$newlogin->dbconnect();
  
    if(strlen($search)>0)
    {
      $cond=" (co.name like '%$search%' || co.mobile like '%$search%' || co.address like '%$search%' || lr.visit_no like '%$search%' || lr.bill_no like '%$search%')";   
    }
    else{
        $cond=" lr.date between '$from' and '$to'";
    }
    if($dispatch !== '') {
        $cond .= " AND s.is_sample_dispatch = '$dispatch'";
    }
    if(isset($_SESSION['lab_dept'])&&$_SESSION['lab_dept'] != '')
    {
        // Handle lab_dept as JSON array for multiple department access
        $lab_dept = implode("','",json_decode($_SESSION['lab_dept'], true));
        $cond .= " AND  t.testname_header in ('$lab_dept')";
    }
    $sql = "
      (
    SELECT 
        lr.`date`,
        co.`name`,
        co.`gender`,
        co.`age`,
        co.`id`,
        co.address,
        co.mobile,
        lr.visit_no,
        lr.bill_no,
        '' AS ipd_no,
        group_concat(distinct(t.testname)) as testname,
        s.test_id,
        COUNT(lr.id) AS testcount,
        t.samples,
        s.id AS sample_id,
        s.is_sample_collected,
        s.sample_collected_by,
        s.is_sample_dispatch,
        s.is_sample_acknowledge,
        s.is_outsourced,
        lr.bill_no AS lab_count,
        t.testid
    FROM central_opd co
    JOIN lab_report lr ON lr.patient_id = co.id
    JOIN lab_test t ON t.testid = lr.testid
    JOIN sample_status s ON s.bill_no = lr.bill_no 
                        AND s.patient_id = co.id
                        AND s.test_id = t.testid
    where $cond and s.is_sample_collected=1 
    GROUP BY s.id

    UNION ALL

    SELECT 
        lr.`date`,
        ci.`name`,
        ci.`gender`,
        ci.`age`,
        ci.`id`,
        ci.address,
        ci.mobile,
        lr.visit_no,
        lr.bill_no,
        ci.ipd_no,
        group_concat(distinct(t.testname)) as testname,
        s.test_id,
        COUNT(lr.id) AS testcount,
        t.samples,
        s.id AS sample_id,
        s.is_sample_collected,
        s.sample_collected_by,
        s.is_sample_dispatch,
        s.is_sample_acknowledge,
        s.is_outsourced,
        lr.bill_no AS lab_count,
        t.testid
    FROM central_ipd ci
    JOIN lab_report lr ON lr.patient_id = ci.ipd_no
    JOIN lab_test t ON t.testid = lr.testid
    JOIN sample_status s ON s.bill_no = lr.bill_no 
                        AND s.patient_id = ci.ipd_no
                        AND s.test_id = t.testid
    where $cond and s.is_sample_collected=1 
    GROUP BY s.id
  )
  ORDER BY visit_no DESC;

    ";
      //  echo $sql;
    $res=mysqli_query($con,$sql);
    return $res;
  }

  // Update sample dispatch status
  function updateSampleDispatch($sample_id)
  {
      $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      // Guarded on the previous stage rather than trusting the worklist: the id
      // arrives straight off $_REQUEST, and a stale page or a hand-made request
      // could otherwise dispatch a sample that was never collected.
      $sample_id = (int) $sample_id;
      $actor = mysqli_real_escape_string($con, (string) $_SESSION['name']);
      $sql="UPDATE `sample_status` SET `is_sample_dispatch`='1',`sample_dispatch_by`='$actor',`sample_dispatch_at`=now()
            WHERE id=$sample_id AND is_sample_collected=1 AND is_sample_dispatch=0";
      $res=mysqli_query($con,$sql);
      return $res && mysqli_affected_rows($con) > 0;
  }

  function getLabDetails()
  {
      $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      $sql="SELECT testname_header,testname,l.testid FROM `lab_test` l WHERE  l.status='Active' ";
      $res=mysqli_query($con,$sql);
    return $res;
  }
  function insertSampleCollect($bill_no,$patient_id,$test_id,$visit_no)
  {
      $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      // print_r($test_id);
      // sample_status has only a plain KEY on (bill_no, patient_id, test_id), so
      // nothing at the database level stops a second row for the same physical
      // sample. A double submit or a back-button re-post used to create one, and
      // the duplicate then showed up as a second row on every downstream
      // worklist. Skip anything already collected instead.
      $res = false;
      $inserted = 0;
      $actor    = mysqli_real_escape_string($con, (string) $_SESSION['name']);
      $billEsc  = mysqli_real_escape_string($con, (string) $bill_no);
      $patEsc   = mysqli_real_escape_string($con, (string) $patient_id);

      if (!is_array($test_id)) {
          $test_id = ($test_id === '' || $test_id === null) ? array() : array($test_id);
      }

      foreach ($test_id as $test_ids) {
        $test_ids = trim((string) $test_ids);
        if ($test_ids === '') { continue; }
        $testEsc = mysqli_real_escape_string($con, $test_ids);

        $dup = mysqli_query($con, "SELECT id FROM `sample_status`
                                    WHERE bill_no='$billEsc' AND patient_id='$patEsc'
                                      AND test_id='$testEsc' AND is_sample_collected=1
                                    LIMIT 1");
        if ($dup && mysqli_num_rows($dup) > 0) { continue; }

        $barcode_no = mysqli_real_escape_string($con, $visit_no . '/' . $test_ids);
        $sql="INSERT IGNORE INTO `sample_status`( `barcode_no`,`bill_no`, `patient_id`, `test_id`, `is_sample_collected`, `sample_collected_by`, `sample_collected_at`) VALUES('$barcode_no','$billEsc','$patEsc','$testEsc','1','$actor',now())";
        // echo $sql;
        $res = mysqli_query($con,$sql);
        // INSERT IGNORE reports success even when it skipped a duplicate, so
        // count what actually landed.
        if ($res && mysqli_affected_rows($con) > 0) { $inserted++; }
      }
      // exit;
    return $inserted > 0;
  }
  function updateSampleCollect($id)
  {
      $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      // A sample can only be acknowledged by the lab once it has been dispatched.
      $id = (int) $id;
      $actor = mysqli_real_escape_string($con, (string) $_SESSION['name']);
      $sql="UPDATE `sample_status` SET `is_sample_acknowledge`='1',`sample_acknowledged_by`='$actor',`sample_acknowledged_at`=now()
            WHERE id=$id AND is_sample_dispatch=1 AND is_sample_acknowledge=0";
      $res=mysqli_query($con,$sql);
      return $res && mysqli_affected_rows($con) > 0;
  }
   function updateResultFinalization($id)
  {
      $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      // The clinically important gate: a report may only be finalized once a
      // result has actually been entered. A finalized report is what gets printed
      // for the patient and published to ABDM, so an empty one must not pass.
      // Returns false when nothing matched, so the caller can tell it was refused.
      $id = (int) $id;
      $actor = mysqli_real_escape_string($con, (string) $_SESSION['name']);
      $sql="UPDATE `sample_status` SET `is_finalized`='1',`finalized_by`='$actor',`finalized_at`=now()
            WHERE id=$id AND is_result_entered=1 AND is_finalized=0";
      // echo $sql;exit;
      $res=mysqli_query($con,$sql);
      if (!$res || mysqli_affected_rows($con) === 0) {
          return false;
      }
      $sql_get = "SELECT test_id, patient_id, bill_no FROM `sample_status` WHERE id=$id LIMIT 1";
      $res_get = mysqli_query($con, $sql_get);
    //   echo $sql_get;exit;
      if($row = mysqli_fetch_assoc($res_get)) {
          $test_id = $row['test_id'];
          $patient_id = $row['patient_id'];
          $bill_no = $row['bill_no'];
          
          $user_id = isset($_SESSION['loginid']) ? $_SESSION['loginid'] : 0;
          if($user_id > 0) {
              $this->autoInsertUserSignature($test_id, $patient_id, $bill_no, $user_id);
          }
      }


    return $res;
  }
  function getLabGroup()
  {
      $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      $sql="SELECT * FROM `lab_test` WHERE status='active'";
      // echo $sql;exit;
      $res=mysqli_query($con,$sql);
    return $res;
  }
  function addPackage($package_name, $package_price, $package_description, $testid)
  {
      $newlogin = new userlogin;                  
      $con = $newlogin->dbconnect();
  echo $package_name;
      // If $testid is a string, convert to array
      if (is_string($testid)) {
          $testid = explode(',', $testid);
      }
      $testid = array_map('intval', $testid); // Ensure test IDs are integers
      $sql1="INSERT INTO service_list(`name`, `opd_charge`, `ipd_charge`, `service_group_id`) VALUES ('$package_name', '$package_price', '$package_price', '27')";
      // echo $sql1;exit;
      mysqli_query($con, $sql1);
      $id= mysqli_insert_id($con);
      $sql = "INSERT INTO `lab_package`(`id`,`package_name`, `package_price`, `package_description`, created_by) VALUES ('$id','$package_name', '$package_price', '$package_description', '$_SESSION[name]')";
      // echo $sql;exit;
      if (mysqli_query($con, $sql)) {
          $package_id = mysqli_insert_id($con);
          foreach ($testid as $test_ids) {
              $sql = "INSERT INTO `lab_package_tests`(`package_id`, `test_group_id`) VALUES ('$package_id', '$test_ids')";
              mysqli_query($con, $sql);
          }
          return true;
      } else {
          return false;
      }
  }
  function getLabPackage()
  {
    $newlogin = new userlogin();
    $con = $newlogin->dbconnect();
    $sql = "SELECT 
              lp.*, 
              GROUP_CONCAT(lpt.test_group_id) as test_group_ids,
              GROUP_CONCAT(lt.testname) as test_names
            FROM lab_package lp
            LEFT JOIN lab_package_tests lpt ON lp.id = lpt.package_id
            LEFT JOIN lab_test lt ON lpt.test_group_id = lt.testid
            GROUP BY lp.id";
    $res = mysqli_query($con, $sql);
    return $res;
  }
  function getLabPackageById($package_id)
  {
    $newlogin = new userlogin();
    $con = $newlogin->dbconnect();
    $package_id = intval($package_id);
    $sql = "SELECT 
          lp.*, 
          GROUP_CONCAT(lpt.test_group_id) as test_group_ids,
          GROUP_CONCAT(lt.testname) as test_names,
          GROUP_CONCAT(lt.price) as test_prices
        FROM lab_package lp
        LEFT JOIN lab_package_tests lpt ON lp.id = lpt.package_id
        LEFT JOIN lab_test lt ON lpt.test_group_id = lt.testid
        WHERE lp.id = $package_id
        GROUP BY lp.id";
    $res = mysqli_query($con, $sql);
    return mysqli_fetch_assoc($res);
  }



  function updatePackage($package_id, $package_name, $package_price, $package_description, $testid)
  {
    $newlogin = new userlogin();
    $con = $newlogin->dbconnect();
    $package_id = intval($package_id);
    $package_name = mysqli_real_escape_string($con, $package_name);
    $package_price = mysqli_real_escape_string($con, $package_price);
    $package_description = mysqli_real_escape_string($con, $package_description);

    // Update package details
    $sql = "UPDATE lab_package SET package_name='$package_name', package_price='$package_price', package_description='$package_description'WHERE id=$package_id";
    $result = mysqli_query($con, $sql);

    // Remove old test ids
    mysqli_query($con, "DELETE FROM lab_package_tests WHERE package_id = $package_id");

    // If $testid is a string, convert to array
    if (is_string($testid)) {
      $testid = explode(',', $testid);
    }
    $testid = array_map('intval', $testid);

    // Insert new test ids
    foreach ($testid as $test_ids) {
      $sql = "INSERT INTO lab_package_tests(package_id, test_group_id) VALUES ('$package_id', '$test_ids')";
      mysqli_query($con, $sql);
    }
    return $result;
  }
  function createReferenceCenter()
  {
      $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      $name = mysqli_real_escape_string($con, $this->name);
      $address = mysqli_real_escape_string($con, $this->address);
      $contact_name = mysqli_real_escape_string($con, $this->contact_name);
      $contact_number = mysqli_real_escape_string($con, $this->contact_number);
      $email = mysqli_real_escape_string($con, $this->email);
      $bill_type = mysqli_real_escape_string($con, $this->bill_type);

      $sql="INSERT INTO `reference_centers`(`name`, `address`, `contact_name`, `contact_number`, `email`, `bill_type`) VALUES ('$name','$address','$contact_name','$contact_number','$email','$bill_type')";
      // echo $sql;exit;
      $res=mysqli_query($con,$sql);
    return $res;
  }
  function getReferenceCenter()
  {
      $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      $sql="SELECT * FROM `reference_centers`";
      // echo $sql;
      $res=mysqli_query($con,$sql);
    return $res;
  }
  function editReferenceCenter($id)
  {
      $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      $name = mysqli_real_escape_string($con, $this->name);
      $address = mysqli_real_escape_string($con, $this->address);
      $contact_name = mysqli_real_escape_string($con, $this->contact_name);
      $contact_number = mysqli_real_escape_string($con, $this->contact_number);
      $email = mysqli_real_escape_string($con, $this->email);
      $bill_type = mysqli_real_escape_string($con, $this->bill_type);

      $sql="UPDATE `reference_centers` SET `name`='$name',`address`='$address',`contact_name`='$contact_name',`contact_number`='$contact_number',`email`='$email',`bill_type`='$bill_type' WHERE id=$id";
      // echo $sql;exit;
      $res=mysqli_query($con,$sql);
    return $res;
  }
  function createOutsourceLab()
  {
    $newlogin = new userlogin;                  
    $con=$newlogin->dbconnect();
    $name = mysqli_real_escape_string($con, $this->name);
    $address = mysqli_real_escape_string($con, $this->address);
    $contact_name = mysqli_real_escape_string($con, $this->contact_name);
    $contact_number = mysqli_real_escape_string($con, $this->contact_number);
    $city = mysqli_real_escape_string($con, $this->city);
    $nabl = mysqli_real_escape_string($con, $this->nabl);

    $sql="INSERT INTO `outsource_labs`(`name`, `address`, `contact_name`, `contact_number`, `city`, `nabl`) VALUES ('$name','$address','$contact_name','$contact_number','$city','$nabl')";
  //   echo $sql;exit;
    $res=mysqli_query($con,$sql);
    return $res;
  }
  function getOutsourceLab()
  {
    $newlogin = new userlogin;                  
    $con=$newlogin->dbconnect();
    $sql="SELECT * FROM `outsource_labs`";
    // echo $sql;
    $res=mysqli_query($con,$sql);
    return $res;
  }
  function editOutsourceLab($id)
  {
    $newlogin = new userlogin;                  
    $con=$newlogin->dbconnect();
    $name = mysqli_real_escape_string($con, $this->name);
    $address = mysqli_real_escape_string($con, $this->address);
    $contact_name = mysqli_real_escape_string($con, $this->contact_name);
    $contact_number = mysqli_real_escape_string($con, $this->contact_number);
    $city = mysqli_real_escape_string($con, $this->city);
    $nabl = mysqli_real_escape_string($con, $this->nabl);

    $sql="UPDATE `outsource_labs` SET `name`='$name',`address`='$address',`contact_name`='$contact_name',`contact_number`='$contact_number',`city`='$city',`nabl`='$nabl' WHERE id=$id";
    // echo $sql;exit;
    $res=mysqli_query($con,$sql);
    return $res;   
  }
  function updateSampleCollection()
  {
      $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      $id = intval($this->id);
      $price= mysqli_real_escape_string($con, $this->lab_price);
      $sample_id = intval($this->sample_id);
      $sql="UPDATE `sample_status` SET `outsource_lab_id`='$id',`outsourced_price`='$price',`is_outsourced`='1',`outsourced_by`='$_SESSION[name]',`outsourced_at`=now() WHERE id=$sample_id";
      // echo $sql;exit;
      $res=mysqli_query($con,$sql);
    return $res;
  }
  function deleteTest($test_id)
  {
      $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      $test_id = intval($test_id);
      $sql = "update labtest_group set status='Inactive' WHERE testid = $test_id";
      // echo $sql;exit;
      $res = mysqli_query($con, $sql);
      if ($res) {
          return true;
      } else {
          return false;
      }
  }
  function testHeading()
  {
      $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      $test_id = intval($this->test_id);
      $name = mysqli_real_escape_string($con, $this->name);
      $gender = mysqli_real_escape_string($con, $this->gender);
      $position = intval($this->position);
      $default = intval($this->default);

      $sql="INSERT INTO `lab_test_parameter`(`test_id`, `name`, `gender`, `position`,`type`,`is_print`) VALUES ('$test_id','$name','$gender','$position','heading','$default')";
      //  echo $sql;exit;
      $res=mysqli_query($con,$sql);
      return $res;
  }
  function testParameter()
  {
      $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      $test_id = intval($this->test_id);  
      $name = mysqli_real_escape_string($con, $this->name);
      $unit = mysqli_real_escape_string($con, $this->unit);
      $method = mysqli_real_escape_string($con, $this->method);
      $gender = mysqli_real_escape_string($con, $this->gender);
      $position = intval($this->position);
      $default = intval($this->default);

      $sql="INSERT INTO `lab_test_parameter`(`test_id`, `name`, `unit`, `method`, `gender`, `position`,`type`,`is_print`) VALUES ('$test_id','$name','$unit','$method','$gender','$position','parameter','$default')";
      //  echo $sql;exit;
      $res=mysqli_query($con,$sql);
      return $res;
  }
  function testFreeText()
  {
      $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      $test_id = intval($this->test_id);
      $body = mysqli_real_escape_string($con, $this->body);
      $gender = mysqli_real_escape_string($con, $this->gender);
      $position = intval($this->position);
      $default = intval($this->default);
      $readonly = intval($this->readonly);

      $sql="INSERT INTO `lab_test_parameter`(`test_id`, `name`, `gender`, `position`,`type`,`is_print`,`is_readonly`) VALUES ('$test_id','$body','$gender','$position','free_text','$default','$readonly')";
      // echo $sql;exit;
      $res=mysqli_query($con,$sql);
      return $res;
  }
  function updateTestFreeText($id)

  {

      $newlogin = new userlogin;                  

      $con=$newlogin->dbconnect();

      $test_id = intval($this->test_id);

      $body = mysqli_real_escape_string($con, $this->body);

      $gender = mysqli_real_escape_string($con, $this->gender);

      $position = intval($this->position);

      $default = intval($this->default);

      $readonly = intval($this->readonly);



      $sql="UPDATE `lab_test_parameter` SET `test_id`='$test_id', `name`='$body', `gender`='$gender', `position`='$position',`is_print`='$default',`is_readonly`='$readonly' WHERE id=$id";

      // echo $sql;exit;

      $res=mysqli_query($con,$sql);

      return $res;

  }
  function addAntibiotic()
  {
      $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      $test_id = intval($this->test_id);
      $name = mysqli_real_escape_string($con, $this->name);
      $gender = mysqli_real_escape_string($con, $this->gender);
      $position = intval($this->position);
      $default = intval($this->default);
      $sql="INSERT INTO `lab_test_parameter`(`test_id`, `name`, `gender`, `position`,`type`,`is_print`) VALUES ('$test_id','$name','$gender','$position','antibiotic','$default')";
      //  echo $sql;exit;
      $res=mysqli_query($con,$sql);
      return $res;
  }
  function getLabTestParameterById($id)
  {
      $newlogin = new userlogin;
      $con = $newlogin->dbconnect();
      $id = intval($id);
      $sql = "SELECT * FROM `lab_test_parameter` WHERE `test_id` = '$id' and status='active' order by position";
      $result = mysqli_query($con, $sql);
      $parameters = array();
      if ($result) {
          while ($row = mysqli_fetch_assoc($result)) {
              $parameters[] = $row;
          }
      }
      return $parameters;
  }
  function updateTestItem($id, $name, $unit, $method, $gender, $position, $default, $readonly)
  {
      $newlogin = new userlogin;
      $con = $newlogin->dbconnect();
      $id = intval($id);
      $name = mysqli_real_escape_string($con, $name);
      $gender = mysqli_real_escape_string($con, $gender);
      $position = intval($position);

      $sql = "UPDATE `lab_test_parameter` 
              SET `name` = '$name', `gender` = '$gender', `position` = '$position', `unit` = '$unit', `method` = '$method', `is_print`='$default', `is_readonly`='$readonly'
              WHERE `id` = '$id'";
  // echo $sql;exit;
      $res = mysqli_query($con, $sql);
      return $res;
  }
  function updatePosition($id, $position)
  {
      $newlogin = new userlogin;
      $con = $newlogin->dbconnect();
      $id = intval($id);
      $position = intval($position);

      $sql = "UPDATE `lab_test_parameter` SET `position` = '$position' WHERE `id` = '$id'";
      echo $sql;
      $res = mysqli_query($con, $sql);
      return $res;
  }
  function addTestRange()
  {
      $newlogin = new userlogin;
      $con = $newlogin->dbconnect();

      // Get and sanitize all required fields from the object properties
      $test_id = mysqli_real_escape_string($con, $this->test_id);
      $normal_value = mysqli_real_escape_string($con, $this->normal_value);
      $gender = mysqli_real_escape_string($con, $this->gender);
      $abnormal_condition = mysqli_real_escape_string($con, $this->abnormal_condition);
      $critical_lower_limit = mysqli_real_escape_string($con, $this->critical_lower_limit);
      $critical_upper_limit = mysqli_real_escape_string($con, $this->critical_upper_limit);
      $reference_range_id = intval($this->reference_range_id);
      $parameter_id = intval($this->parameter_id);
      $lower_limit = mysqli_real_escape_string($con, $this->lower_limit);
      $upper_limit = mysqli_real_escape_string($con, $this->upper_limit);

      $sql = "INSERT INTO `lab_test_range` 
          (`test_id`, `normal_value`, `gender`, `abnormal_condition`, `critical_lower_limit`, `critical_upper_limit`, `reference_range_id`, `parameter_id`, `lower_limit`, `upper_limit`)
          VALUES 
          ('$test_id', '$normal_value', '$gender', '$abnormal_condition', '$critical_lower_limit', '$critical_upper_limit', '$reference_range_id', '$parameter_id', '$lower_limit', '$upper_limit')";
    
      $res = mysqli_query($con, $sql);
      return $res;
  }
  function addTestResult()
  {
      $newlogin = new userlogin;
      $con = $newlogin->dbconnect();

      // Get and sanitize all required fields from the object properties
      $option = mysqli_real_escape_string($con, $this->option);
      $position = intval($this->position);
      $test_id = mysqli_real_escape_string($con, $this->test_id);
      $result_bold = isset($this->result_bold) ? intval($this->result_bold) : 0;

      $sql = "INSERT INTO `lab_test_result` (`test_parameter_id`, `options`, `position`, `result_bold`)
              VALUES ('$test_id', '$option', '$position', '$result_bold')";

      $res = mysqli_query($con, $sql);
      return $res;
  }
  function addTestAntibiotic()
  {
      $newlogin = new userlogin;
      $con = $newlogin->dbconnect();
      $test_id = mysqli_real_escape_string($con, $this->test_id);
      $heading1 = mysqli_real_escape_string($con, $this->heading1);
      $heading2 = mysqli_real_escape_string($con, $this->heading2);
      $heading3 = mysqli_real_escape_string($con, $this->heading3);
      $sql = "INSERT INTO `lab_test_antibiotic` (`parameter_id`, `heading1`, `heading2`, `heading3`)
              VALUES ('$test_id', '$heading1', '$heading2', '$heading3')";
      $res = mysqli_query($con, $sql);
      return $res;  
  }

  function getTestAntibiotic($test_id)
  {
      $newlogin = new userlogin;
      $con = $newlogin->dbconnect();
      $sql = "SELECT * FROM `lab_test_antibiotic` WHERE `parameter_id` = '$test_id'";
      // echo $sql;
      $res = mysqli_query($con, $sql);
      return $res;
  }
  function getAllAntibiotics()
  {
      $newlogin = new userlogin;
      $con = $newlogin->dbconnect();
      $sql = "SELECT * FROM `antibiotics`";
      $res = mysqli_query($con, $sql);
      return $res;
  }
  function insertTestAntibiotic($parameter_id, $antibiotic_name) {
      $newlogin = new userlogin;
      $con = $newlogin->dbconnect();
      $parameter_id = intval($parameter_id);
      $antibiotic_name = mysqli_real_escape_string($con, $antibiotic_name);
      $sql = "INSERT INTO lab_test_antibiotic (parameter_id, heading1) VALUES ('$parameter_id', '$antibiotic_name')";
      // echo $sql;exit;
      return mysqli_query($con, $sql);
  }
      // --- LAB TEST GROUP CRUD METHODS ---
      /**
       * Get lab tests with optional department and name filters
       */
      function getLabTests($filter_department = '', $filter_name = '') {
          $newlogin = new userlogin;
          $con = $newlogin->dbconnect();
          $where = " WHERE `status`='active'";
          if ($filter_department) {
              $where .= " AND testname_header='" . mysqli_real_escape_string($con, $filter_department) . "'";
          }
          if ($filter_name) {
              $where .= " AND testname LIKE '%" . mysqli_real_escape_string($con, $filter_name) . "%'";
          }
          $sql = "SELECT * FROM `lab_test` $where order by testname_header";
          return mysqli_query($con, $sql);
      }
      /**
       * Insert a new lab test and service_list entry
       */
      function insertLabTest($testname, $testprice, $type, $sample, $tat_time = '', $nabl = 0) {
          $newlogin = new userlogin;
          $con = $newlogin->dbconnect();
          $testname = mysqli_real_escape_string($con, $testname);
          $testprice = mysqli_real_escape_string($con, $testprice);
          $type = mysqli_real_escape_string($con, $type);
          $sample = mysqli_real_escape_string($con, $sample);
          $tat_time = mysqli_real_escape_string($con, $tat_time);
          $sql1 = "INSERT INTO service_list (name,opd_charge,ipd_charge,type,service_group_id,status) VALUES('$testname','$testprice','$testprice','Internal','27','Active')";
          mysqli_query($con, $sql1);
          $id = mysqli_insert_id($con);
          $query = "INSERT INTO `lab_test`( `testid`, `testname`, `price`, `testname_header`,`samples`, `tat_time`,`is_nabl`, `status`) VALUES ('$id','$testname','$testprice','$type','$sample', '$tat_time', '$nabl', 'active')";
          mysqli_query($con, $query);
          return mysqli_insert_id($con);
      }
      /**
       * Update an existing lab test and service_list entry
       */
      function updateLabTest($editid, $testname, $testprice, $type, $sample, $tat_time = '', $nabl = 0) {
          $newlogin = new userlogin;
          $con = $newlogin->dbconnect();
          $editid = intval($editid);
          $testname = mysqli_real_escape_string($con, $testname);
          $testprice = mysqli_real_escape_string($con, $testprice);
          $type = mysqli_real_escape_string($con, $type);
          $sample = mysqli_real_escape_string($con, $sample);
          $tat_time = mysqli_real_escape_string($con, $tat_time);
          $sql1 = "UPDATE service_list SET opd_charge='$testprice',ipd_charge='$testprice' WHERE id='$editid'";
          mysqli_query($con, $sql1);
          $query = "UPDATE `lab_test` SET `testname`='$testname', `price`='$testprice', `testname_header`='$type',`samples`='$sample', `tat_time`='$tat_time', `is_nabl`='$nabl' WHERE testid='$editid'";
          return mysqli_query($con, $query);
      }
      /**
       * Delete a lab test and its service_list entry
       */
      function deleteLabTest($testid) {
          $newlogin = new userlogin;
          $con = $newlogin->dbconnect();
          $testid = intval($testid);
          // Delete from lab_test
          mysqli_query($con, "update lab_test set status='inactive' WHERE testid='$testid'");
          // Delete from service_list
          return mysqli_query($con, "DELETE FROM service_list WHERE id='$testid'");
      }
      /**
       * Get a single lab test by id
       */
      function getLabTestById($testid) {
          $newlogin = new userlogin;
          $con = $newlogin->dbconnect();
          $testid = intval($testid);
          $sql = "SELECT * FROM `lab_test` WHERE `testid`='$testid'";
          $res = mysqli_query($con, $sql);
          return mysqli_fetch_assoc($res);
      }
      function getLabTest($patient_id,$bill_no)
      {
          $newlogin = new userlogin;
          $con = $newlogin->dbconnect();
          $sql = "SELECT l.testname,l.samples,l.testid,s.is_sample_collected,s.is_outsourced,s.is_finalized,s.is_sample_acknowledge,s.id as sample_id,s.finalized_by,l.testname_header,s.is_sample_dispatch,s.is_result_entered FROM lab_test l,lab_report lr LEFT JOIN sample_status s ON s.test_id=lr.testid and s.patient_id=lr.patient_id and s.bill_no=lr.bill_no  where l.status='active' and l.testid=lr.testid and lr.patient_id='$patient_id' and lr.bill_no='$bill_no' group by l.testid order by l.testname_header";
      //   echo $sql;
          $res = mysqli_query($con, $sql);
          return $res;
      }
      function getLabTest1($patient_id,$bill_no)
      {
          $newlogin = new userlogin;
          $con = $newlogin->dbconnect();
          $sql = "SELECT group_concat(distinct(l.testname)) as testname,group_concat(distinct(l.testid)) as testid,s.is_sample_collected,s.is_outsourced,s.is_finalized,s.is_sample_acknowledge,s.sample_collected_at,ld.`left_signature`, ld.`right_signature`, ld.`left_e_signature`, ld.`right_e_signature`,ld.sample_received_date,ld.reporting_time,ld.id as dispatch_id 
          FROM sample_status s,lab_test l,lab_report lr 
          LEFT JOIN lab_report_dispatch ld ON FIND_IN_SET(lr.testid, ld.test_id) > 0 and ld.bill_no=lr.bill_no and ld.patient_id=lr.patient_id  
          where l.status='active' and l.testid=lr.testid and lr.patient_id='$patient_id' and lr.bill_no='$bill_no' and s.test_id=lr.testid and s.patient_id=lr.patient_id and s.bill_no=lr.bill_no and s.is_finalized=1 group by l.testname_header,date(ld.reporting_time)";
      //   echo $sql;
          $res = mysqli_query($con, $sql);
          return $res;
      }
      function getPatientLabVisits($patient_id)
      {
          $newlogin = new userlogin;
          $con = $newlogin->dbconnect();
          $sql = "SELECT c.name, c.age, c.gender, c.opd_no, lr.visit_no, b.doctor, r.name as ref_center, 'central_opd' as source,c.is_generated
              FROM central_opd c
              JOIN lab_report lr ON c.id = lr.patient_id
              JOIN billing b ON b.bill_no = lr.bill_no
              LEFT JOIN reference_centers r ON b.referal_center_id = r.id
              WHERE c.id = '$patient_id'
              UNION ALL
              SELECT c.name, c.age, c.gender, c.ipd_no, lr.visit_no, b.doctor, r.name as ref_center, 'central_ipd' as source,c.is_generated
              FROM central_ipd c
              JOIN lab_report lr ON c.ipd_no = lr.patient_id
              JOIN billing b ON b.bill_no = lr.bill_no
              LEFT JOIN reference_centers r ON b.referal_center_id = r.id
              WHERE c.ipd_no = '$patient_id'";
      //   echo $sql;
          $res = mysqli_query($con, $sql);
          return $res;
      }
      
      function checkTestResults($testid, $patient_id, $bill_no)
      {
          $newlogin = new userlogin;
          $con = $newlogin->dbconnect();
          $sql = "SELECT is_result_entered FROM sample_status WHERE test_id='$testid' AND patient_id='$patient_id' AND bill_no='$bill_no' LIMIT 1";
          $res = mysqli_query($con, $sql);
          if($res && mysqli_num_rows($res) > 0) {
              $row = mysqli_fetch_assoc($res);
              return ($row['is_result_entered'] == 1) ? true : false;
          }
          return false;
      }
      
      function autoInsertUserSignature($testid, $patient_id, $bill_no, $user_id)
      {
          $newlogin = new userlogin;
          $con = $newlogin->dbconnect();
          
          // Get user's signature info from lab_signature table by matching id with user_id
          $sql_user = "SELECT id, position FROM lab_signature WHERE user_id='$user_id' AND status='active' LIMIT 1";
          $res_user = mysqli_query($con, $sql_user);
        //   echo $sql_user;exit;
          if(mysqli_num_rows($res_user) > 0) {
              $user_sign = mysqli_fetch_assoc($res_user);
              $signature_id = $user_sign['id'];
              $position = strtolower($user_sign['position']); // 'left' or 'right'
              
              // Get sample received date from sample_status
              $sql_sample = "SELECT sample_collected_at FROM sample_status WHERE test_id='$testid' AND patient_id='$patient_id' AND bill_no='$bill_no' LIMIT 1";
              $res_sample = mysqli_query($con, $sql_sample);
              $sample_data = mysqli_fetch_assoc($res_sample);
              $sample_received_date = $sample_data['sample_collected_at'] ?? date('Y-m-d H:i:s');
              
              // Check if dispatch record exists
              $sql_check = "SELECT id FROM lab_report_dispatch WHERE test_id='$testid' AND patient_id='$patient_id' AND bill_no='$bill_no' LIMIT 1";
              $res_check = mysqli_query($con, $sql_check);
            //   echo $sql_check;exit;
              if(mysqli_num_rows($res_check) > 0) {
                  // Update existing record
                  $dispatch = mysqli_fetch_assoc($res_check);
                  $dispatch_id = $dispatch['id'];
                  
                  if($position == 'left') {
                      $sql_update = "UPDATE lab_report_dispatch SET left_e_signature=1,left_signature='$signature_id', sample_received_date='$sample_received_date', reporting_time=NOW() WHERE id='$dispatch_id'";
                  } else {
                      $sql_update = "UPDATE lab_report_dispatch SET right_e_signature=1,right_signature='$signature_id', sample_received_date='$sample_received_date', reporting_time=NOW() WHERE id='$dispatch_id'";
                  }
                  mysqli_query($con, $sql_update);
              } else {
                  // Insert new record
                  if($position == 'left') {
                      $sql_insert = "INSERT INTO lab_report_dispatch (test_id, patient_id, bill_no, left_e_signature,left_signature, sample_received_date, reporting_time) VALUES ('$testid', '$patient_id', '$bill_no', '1','$signature_id', '$sample_received_date', NOW())";
                  } else {
                      $sql_insert = "INSERT INTO lab_report_dispatch (test_id, patient_id, bill_no, right_e_signature,right_signature, sample_received_date, reporting_time) VALUES ('$testid', '$patient_id', '$bill_no', '1','$signature_id', '$sample_received_date', NOW())";
                  }
                  mysqli_query($con, $sql_insert);
              }
              return true;
          }
          else{
              // No active signature found for user, do nothing or log as needed
              return false;
          }
      }
      function getLabTestDetails($patient_id, $bill_no, $testid, $gender)
      {
          $newlogin = new userlogin;
          $con = $newlogin->dbconnect();

          // Fetch all test parameters for the given testid, including all types (parameter, free_text, heading, etc.)
          $sql = "SELECT 
                      ltp.id as parameter_id,
                      ltp.name as testname,
                      ltp.type,
                      ltp.unit,
                      ltp.method,
                      ltp.position,
                      ltp.gender,
                      ltp.status,
                      ltp.is_print
                  FROM lab_test_parameter ltp
                  WHERE ltp.test_id = '$testid' 
                    AND ltp.status = 'Active'
                    AND (ltp.gender = '$gender' OR ltp.gender = 'both')
                  ORDER BY ltp.position ASC";
          $param_res = mysqli_query($con, $sql);

          $result = [];
          while ($param = mysqli_fetch_assoc($param_res)) {
              $row = $param;

              // Fetch lab_report row for this parameter, patient, bill
              $lab_report_sql = "SELECT id, test_result, print_flag FROM lab_report 
                                 WHERE patient_id = '$patient_id' 
                                   AND bill_no = '$bill_no' 
                                   AND testid = '$testid' 
                                   AND parameter_id = '{$param['parameter_id']}'";
              $lab_report_res = mysqli_query($con, $lab_report_sql);
              $lab_report = mysqli_fetch_assoc($lab_report_res);
              $row['lab_report_id'] = $lab_report ? $lab_report['id'] : null;
              $row['test_result'] = $lab_report ? $lab_report['test_result'] : '';
              $row['print_flag'] = $lab_report ? $lab_report['print_flag'] : $param['is_print'];

              // Fetch normal value, lower/upper limit for 'parameter' type
              if ($param['type'] == 'parameter') {
                  $range_sql = "SELECT normal_value, lower_limit, upper_limit 
                                FROM lab_test_range 
                                WHERE parameter_id = '{$param['parameter_id']}' 
                                  AND status = 'active' 
                                  AND (gender = '$gender' OR gender = 'both')
                                LIMIT 1";
                  $range_res = mysqli_query($con, $range_sql);
                  $range = mysqli_fetch_assoc($range_res);
                  $row['normal_value'] = $range ? $range['normal_value'] : '';
                  $row['lower_limit'] = $range ? $range['lower_limit'] : '';
                  $row['upper_limit'] = $range ? $range['upper_limit'] : '';
              } else {
                  $row['normal_value'] = '';
                  $row['lower_limit'] = '';
                  $row['upper_limit'] = '';
              }
              
              if($param['type']=='free_text') {
                  $row['test_result'] = $row['test_result'] ? $row['test_result'] : $row['testname'];
              }

              $result[] = $row;
          }
// print_r($result);
          return $result;
      }
      
      function getPatientLabVisitsDate($patient_id)
      {
          $newlogin = new userlogin;
          $con = $newlogin->dbconnect();
          $str = substr($patient_id, 0, 2); 
          if($str=='IP')
          {
              $sql = "SELECT b.date,s.bill_no FROM lab_report s,billing b where s.patient_id='$patient_id' and s.bill_no=b.bill_no  group by s.bill_no";
              // echo $sql;
              $res = mysqli_query($con, $sql);
          }
          else
          {
              $sql1="select uhid from central_opd where id='$patient_id'";
              $res1=mysqli_query($con, $sql1);
              $row1=mysqli_fetch_assoc($res1);
              
              $sql2="select id from central_opd where uhid='$row1[uhid]' order by date";
              $res2=mysqli_query($con, $sql2);
            
              
              $ids = [];
              while ($row2 = mysqli_fetch_assoc($res2)) 
              {
                  $ids[] = $row2['id'];
              }
              
              $id = implode(",", $ids);
              
              
              $sql = "SELECT b.date,s.bill_no FROM lab_report s,billing b where s.patient_id in ($id) and s.bill_no=b.bill_no  group by s.bill_no";
              // echo $sql;
              $res = mysqli_query($con, $sql);
          }
          return $res;
      }
      function addTableFormat()
      {
          $newlogin = new userlogin;
          $con = $newlogin->dbconnect();
          $test_id = intval($this->test_id);
          $name = mysqli_real_escape_string($con, $this->name);
          $gender = mysqli_real_escape_string($con, $this->gender);
          $position = intval($this->position);
          $sql = "INSERT INTO `lab_test_parameter` (`test_id`, `name`, `gender`, `position`,`type`) VALUES ('$test_id','$name','$gender','$position','table_format')";
          $res = mysqli_query($con, $sql);
          $id=mysqli_insert_id($con);
          $columns=explode(',',$this->columns);
          foreach($columns as $column)
          {
              $sql1="INSERT INTO `lab_test_table_format` (`parameter_id`, `column_name`) VALUES ('$id', '$column')";
              mysqli_query($con, $sql1);
          }
          return $res;
      }
      function getTableFormat($id)
      {
          $newlogin = new userlogin;
          $con = $newlogin->dbconnect();
          $sql = "SELECT * FROM `lab_test_table_format` WHERE `parameter_id` = '$id'";
          $res = mysqli_query($con, $sql);
          return $res;
      }
      function addTableDataRow()
      {
          $newlogin = new userlogin;
          $con = $newlogin->dbconnect();
          $data_name = mysqli_real_escape_string($con, $this->data_name);
          $position = intval($this->position);
          $parameter_id = intval($this->parameter_id);
          $sql = "INSERT INTO `lab_test_table_data_row` (`parameter_id`, `data_name`, `position`) VALUES ('$parameter_id', '$data_name', '$position')";
          $res = mysqli_query($con, $sql);
          return $res;
      }
      function getTableDataRow($id)
      {
          $newlogin = new userlogin;
          $con = $newlogin->dbconnect();
          $sql = "SELECT * FROM `lab_test_table_data_row` WHERE `parameter_id` = '$id' and status='active'";
          $res = mysqli_query($con, $sql);
          return $res;
      }
  function saveLabResult( $type, $value, $test_id, $print_flag, $patient_id = null, $bill_no = null,$testid) {
      $newlogin = new userlogin;
      $con = $newlogin->dbconnect();
      $test_id = intval($test_id);
      if(strlen($value)>0)
      {
          $print_flag=1;
      }
      else{
      $print_flag = intval($print_flag);
      }
      // $type = mysqli_real_escape_string($con, $type);
  //  echo $value.' - ';
          // $value = mysqli_real_escape_string($con, $value);
        
    $sql = "UPDATE lab_report SET parameter_type=?, test_result=?, print_flag=? WHERE id=?";
      $stmt = mysqli_prepare($con, $sql);
      if (!$stmt) {
          return false;
      }
      // $this->id should be set before calling this function
      $id = intval($this->id);
      mysqli_stmt_bind_param($stmt, "ssii", $type, $value, $print_flag, $test_id);
      $result = mysqli_stmt_execute($stmt);
      mysqli_stmt_close($stmt);
      
      // Update sample_status table when is_result_entered=0
      if($patient_id && $bill_no) {
          $sql_check = "SELECT is_result_entered FROM sample_status WHERE test_id='$testid' AND patient_id='$patient_id' AND bill_no='$bill_no' LIMIT 1";
          $res_check = mysqli_query($con, $sql_check);
          // echo $sql_check;exit;
          if($res_check && mysqli_num_rows($res_check) > 0) {
              $row_check = mysqli_fetch_assoc($res_check);
              if($row_check['is_result_entered'] == 0) {
                  $result_entered_by = mysqli_real_escape_string($con, $_SESSION['name']);
                  $sql_update_sample = "UPDATE sample_status SET is_result_entered=1, result_entered_by='$result_entered_by', result_entered_at=NOW() WHERE test_id='$testid' AND patient_id='$patient_id' AND bill_no='$bill_no'";
                  mysqli_query($con, $sql_update_sample);
              }
          }
      }
      // exit;
      return $result;
  }
  function getLabUsers()
  {
      $newlogin = new userlogin;
      $con = $newlogin->dbconnect();
      $sql = "SELECT id, CONCAT(name, ' ', last_name) as full_name, name, last_name, designation, qualification FROM `user` WHERE role IN ('Lab', 'Lab Admin') AND status='active' ORDER BY name";
      $res = mysqli_query($con, $sql);
      return $res;
  }
  function saveESignature()
  {
      $newlogin = new userlogin;
      $con = $newlogin->dbconnect();
      $user_id = intval($this->user_id);
      $user_name = mysqli_real_escape_string($con, $this->user_name);
      $designation = mysqli_real_escape_string($con, $this->designation);
      $qualification = mysqli_real_escape_string($con, $this->qualification);
      $position = mysqli_real_escape_string($con,$this->position);
      $signature_image = mysqli_real_escape_string($con, $this->signature_image);
      $sql = "INSERT INTO `lab_signature` (`user_id`, `user_name`, `designation`, `qualification`, `position`, `signature_image`) VALUES ('$user_id', '$user_name', '$designation', '$qualification', '$position', '$signature_image')";
      $res = mysqli_query($con, $sql);
      return $res;
  }
  function getESignature()
  {
      $newlogin = new userlogin;
      $con = $newlogin->dbconnect();
      $sql = "SELECT * FROM `lab_signature` where `status`='active'";
      $res = mysqli_query($con, $sql);
      return $res;
  }
  function updateESignature($id)
  {
    $newlogin = new userlogin;
      $con = $newlogin->dbconnect();
      $user_name = mysqli_real_escape_string($con, $this->user_name);
      $designation = mysqli_real_escape_string($con, $this->designation);
      $qualification = mysqli_real_escape_string($con, $this->qualification);
      $position = $this->position;
      $update_fields = "`user_name`='$user_name', `designation`='$designation', `qualification`='$qualification', `position`='$position'";
      if (!empty($this->signature_image)) {
          $signature_image = mysqli_real_escape_string($con, $this->signature_image);
          $update_fields .= ", `signature_image`='$signature_image'";
      }
      $sql = "UPDATE `lab_signature` SET $update_fields WHERE id='$id'";
      $res = mysqli_query($con, $sql);
      return $res;
  }
  function deleteESignature($id)
  {
      $newlogin = new userlogin;
      $con = $newlogin->dbconnect();
      $sql = "UPDATE `lab_signature` SET `status`='inactive' WHERE id='$id'";
      $res = mysqli_query($con, $sql);
      return $res;
  }
  function updateTestRange($id)
  {
      $newlogin = new userlogin;
      $con = $newlogin->dbconnect();
      
      $normal_value = mysqli_real_escape_string($con, $this->normal_value);
      $gender = mysqli_real_escape_string($con, $this->gender);
      $abnormal_condition = mysqli_real_escape_string($con, $this->abnormal_condition);
      $critical_lower_limit = mysqli_real_escape_string($con, $this->critical_lower_limit);
      $critical_upper_limit = mysqli_real_escape_string($con, $this->critical_upper_limit);
      $reference_range_id = intval($this->reference_range_id);
      $lower_limit = mysqli_real_escape_string($con, $this->lower_limit);
      $upper_limit = mysqli_real_escape_string($con, $this->upper_limit);
      
      $sql = "UPDATE lab_test_range SET `normal_value`='$normal_value', `gender`='$gender', `abnormal_condition`='$abnormal_condition', `critical_lower_limit`='$critical_lower_limit', `critical_upper_limit`='$critical_upper_limit', `reference_range_id`='$reference_range_id', `lower_limit`='$lower_limit', `upper_limit`='$upper_limit' WHERE id=$id";
  // echo $sql;exit;
      return mysqli_query($con, $sql);
  }
  function getEsignatures($val)
  {
      $newlogin = new userlogin;
      $con = $newlogin->dbconnect();
      $sql = "select * from `lab_signature` WHERE position='$val'";
      $res = mysqli_query($con, $sql);
      return $res;
  }

    // Save report dispatch details using previously set values
    function saveReportDispatch()
    {
      $newlogin = new userlogin;
      $con = $newlogin->dbconnect();

      $sql = "INSERT INTO lab_report_dispatch (
        test_id,
        patient_id,
        bill_no,
        sample_received_date,
        reporting_time,
        print_signature_on_every_page,
        left_signature,
        right_signature,
        left_e_signature,
        right_e_signature,
        created_at
      ) VALUES (
        '$this->test_id',
        '$this->patient_id',
        '$this->bill_no',
        '$this->sample_received_date',
        '$this->reporting_time',
        '$this->print_signature_on_every_page',
        '$this->left_signature',
        '$this->right_signature',
        '$this->left_e_signature',
        '$this->right_e_signature',
        now()
      )";
  // echo $sql;
      $ok = mysqli_query($con,$sql);
      return $ok;
    }
    function updateReportDispatch($id)
    {
      $newlogin = new userlogin;
      $con = $newlogin->dbconnect();

      $sql = "UPDATE lab_report_dispatch SET
        test_id='$this->test_id',
        patient_id='$this->patient_id',
        bill_no='$this->bill_no',
        sample_received_date='$this->sample_received_date',
        reporting_time='$this->reporting_time',
        print_signature_on_every_page='$this->print_signature_on_every_page',
        left_signature='$this->left_signature',
        right_signature='$this->right_signature',
        left_e_signature='$this->left_e_signature',
        right_e_signature='$this->right_e_signature' WHERE id='$id'";
  // echo $sql;
      $ok = mysqli_query($con,$sql);
      return $ok;
    }
    function updateResultOption($id, $option, $highlight_result)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();

        $id = intval($id);
        $option = mysqli_real_escape_string($con, $option);
        $highlight_result = intval($highlight_result);

        // Update the test result
        $sql = "UPDATE `lab_test_result` SET `options` = '$option', `result_bold` = $highlight_result WHERE `id` = $id";
      //   echo $sql;exit;
        mysqli_query($con, $sql);

        $date = date("Y-m-d H:i:s");
        $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
        $sql1->close();

        if (mysqli_affected_rows($con) > 0) {
            return "Result option updated successfully!";
        } else {
            return "No changes made or update failed.";
        }
    }
    function getLabDepartment()
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
      $sql="SELECT * FROM lab_department WHERE status='active'";
      $ok = mysqli_query($con,$sql);
      return $ok;
    }
    function getEditLabDepartment($id)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
      $sql="SELECT * FROM lab_department WHERE id='$id'";
      $ok = mysqli_query($con,$sql);
      return $ok;
    }
    function insertLabDepartment($name)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
      $sql="INSERT INTO lab_department (dept_name,status) values ('$name','active')";
      $ok = mysqli_query($con,$sql);
      return $ok;
    }
    function updateLabDepartment($name,$status,$id)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
      $sql="UPDATE lab_department SET dept_name='$name',status='$status' WHERE id='$id'";
      $ok = mysqli_query($con,$sql);
      return $ok;
    }
    function getLabReference()
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql="SELECT * FROM `lab_reference_range` WHERE status='active'";
        $ok = mysqli_query($con,$sql);
        return $ok;
        
    }
  }
  ?>