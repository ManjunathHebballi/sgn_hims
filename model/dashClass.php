<?php
require_once(__DIR__ . '/session_config.php');
include('dbconnect.php');


class dashboard{

    function getTotalopd(){
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT count(id) as num FROM `central_opd`  WHERE `date` like '".date('Y-')."%'";
        $res=mysqli_query($con,$sql);
            $opd=mysqli_fetch_assoc($res);
        return $opd['num'];
    }
    function getTotalipd(){
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT count(id) as num FROM `central_ipd`  WHERE `admission_date` like '".date('Y-')."%'";
         $res=mysqli_query($con,$sql);
            $opd=mysqli_fetch_assoc($res);
        return $opd['num'];
    }
    function getTotalpatient(){
        $newlogin = new userlogin;                   
        $con=$newlogin->dbconnect();
        $sql="SELECT count(id) as num  FROM `central_ipd` WHERE `admission_date` like '".date('Y-m-d')."%'";
        // echo $sql;
        $res=mysqli_query($con,$sql);
        $ipd=mysqli_fetch_assoc($res);
      
        $sql="SELECT count(id) as num FROM `central_opd` WHERE `date`='".date('Y-m-d')."'";
        $res=mysqli_query($con,$sql);
        $opd=mysqli_fetch_assoc($res);
        $result=$opd['num'] .'|'.$ipd['num'];
        return $result;
    }
    function getBedList()
    {
        $newlogin = new userlogin;                   
        $con=$newlogin->dbconnect();
        $sql="(SELECT d.id,COUNT(CASE WHEN ward like 'male%' and b.status='active' THEN 1 END) AS male, COUNT(CASE WHEN ward like 'female%' and b.status='active' THEN 1 END) AS female, COUNT(CASE WHEN b.status = 'Occupied' THEN 1 END) AS occupied, d.dept_name, d.bed, d.unit FROM department d LEFT JOIN bed b ON d.id = b.department and b.ward !='ICU' WHERE d.dept_type != 'OPD' AND d.status = 'active' and d.bed>0 GROUP BY d.dept_name, d.bed, d.unit ORDER BY id) UNION 
        (SELECT '' as id,0 AS male, 0 AS female, COUNT(CASE WHEN b.status = 'Occupied' THEN 1 END) AS occupied,'ICU' as dept_name, '20' as bed, '0' as unit FROM  bed b WHERE b.ward like '%ICU%'  )";
        $res=mysqli_query($con,$sql);
        return $res;

    }
    
    function getTimeTable($day)
    {
        $newlogin = new userlogin;                   
        $con=$newlogin->dbconnect();
        $sql="SELECT st.day,GROUP_CONCAT(CONCAT(u.name,' ',u.last_name)) as doctor FROM `staff_timetable` st,`department` d, `doctor` u WHERE u.`id`=st.`doctor_id` AND st.`dept_id`=d.`id` and d.dept_name='$day'
GROUP BY st.day ORDER BY FIELD(`st`.`day`,'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday' ) ASC";
// echo $sql;
        $res=mysqli_query($con,$sql);
        return $res;
    }
      function getLastMonthBedList()
    {
        $month= date("Y-m");
        $newlogin = new userlogin;                   
        $con=$newlogin->dbconnect();
        $sql="SELECT d.`dept_name`,d.bed,count(DISTINCT(ipd_no)) as occupied,count(DISTINCT(dis.patient_id)) as discharge from department d Left JOIN `central_ipd` ci on d.id=ci.dept_id and ci.bed_id > 0 and ci.`admission_date` like '$month%' Left JOIN discharge dis on d.id = dis.dept_id and dis.discharge_date like '$month%' WHERE d.dept_type !='OPD' and d.status='active' group by dept_name ORDER BY d.id";
        // echo $sql;
        $res=mysqli_query($con,$sql);
        return $res;

    }
    function chartOpdPatient()
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        // $sql="SELECT month(appointmentdate) as month ,patientid, COUNT(patientid) as count FROM appointment GROUP BY patientid ";
        $sql="select count(distinct(id)) as count,month(date) as month from central_opd where YEAR(date) = YEAR(CURDATE()) group by month(date)";
        $res=mysqli_query($con,$sql);
        mysqli_close($con);
        return $res;
    }
        function chartIpdPatient()
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        // $sql="SELECT month(appointmentdate) as month ,patientid, COUNT(patientid) as count FROM appointment GROUP BY patientid ";
        $sql="select count(distinct(id)) as count,month(admission_date) as month from central_ipd where YEAR(admission_date) = YEAR(CURDATE()) group by month(admission_date)";
        $res=mysqli_query($con,$sql);
        mysqli_close($con);
        return $res;
    }
    function getLabpatient($searchParams = [], $limit = 15, $offset = 0)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        
        // Build WHERE clause based on search parameters
        $whereConditions = [];
        if(count($searchParams)==0)
        {
              $whereConditions[] = "lr.date='" . date('Y-m-d') . "'";
        }
      
        
        // Patient name search (contains)
        if (!empty($searchParams['patient_name_cont'])) {
            $patientName = mysqli_real_escape_string($con, $searchParams['patient_name_cont']);
            $whereConditions[] = "(op.name LIKE '%$patientName%' OR ip.name LIKE '%$patientName%')";
        }
        if (!empty($searchParams['lab_no_eq'])) {
            $labNo = mysqli_real_escape_string($con, $searchParams['lab_no_eq']);
            $whereConditions[] = "lr.visit_no like '%$labNo%'";
        }
        if (!empty($searchParams['bill_bill_no_eq'])) {
            $billNo = mysqli_real_escape_string($con, $searchParams['bill_bill_no_eq']);
            $whereConditions[] = "lr.bill_no like '%$billNo%'";
        }
        if (!empty($searchParams['patient_mobile_eq'])) {
            $mobile = mysqli_real_escape_string($con, $searchParams['patient_mobile_eq']);
            $whereConditions[] = "(op.mobile like '%$mobile%' OR ip.mobile like '%$mobile%')";
        }
        if (!empty($searchParams['status_eq'])) {
            $status = mysqli_real_escape_string($con, $searchParams['status_eq']);
            $whereConditions[] = "s.status = '$status'";
        }
        if (!empty($searchParams['test_name_eq'])) {
            $testId = mysqli_real_escape_string($con, $searchParams['test_name_eq']);
            $whereConditions[] = "t.testid = '$testId'";
        }
        if (!empty($searchParams['ip_no_eq'])) {
            $ipNo = mysqli_real_escape_string($con, $searchParams['ip_no_eq']);
            $whereConditions[] = "ip.ipd_no like '%$ipNo%'";
        }
        if (!empty($searchParams['op_no_eq'])) {
            $opNo = mysqli_real_escape_string($con, $searchParams['op_no_eq']);
            $whereConditions[] = "(op.opd_no like '%$opNo%' OR ip.opd_no like '%$opNo%')";
        }
        if (!empty($searchParams['lab_category_eq'])) {
            $category = mysqli_real_escape_string($con, $searchParams['lab_category_eq']);
            $whereConditions[] = "t.testname_header = '$category'";
        }
        if (!empty($searchParams['doctor_id_eq'])) {
            $doctorId = mysqli_real_escape_string($con, $searchParams['doctor_id_eq']);
            $whereConditions[] = "b.doctor = '$doctorId'";
        }
        if (!empty($searchParams['referral_center_eq'])) {
            $refCenter = mysqli_real_escape_string($con, $searchParams['referral_center_eq']);
            if ($refCenter == '0') {
                $whereConditions[] = "(b.referal_center_id IS NULL OR b.referal_center_id = 0)";
            } else {
                $whereConditions[] = "b.referal_center_id = '$refCenter'";
            }
        }
        if (!empty($searchParams['uhid_number_eq'])) {
            $uhid = mysqli_real_escape_string($con, $searchParams['uhid_number_eq']);
             $whereConditions[] = "(op.uhid like '%$uhid%' OR (ip.opd_no=op.opd_no and op.uhid like '%$uhid%'))";
        }
        if (!empty($searchParams['outsource_lab_eq'])) {
            $outLab = mysqli_real_escape_string($con, $searchParams['outsource_lab_eq']);
            $whereConditions[] = "s.outsource_lab_id = '$outLab'";
        }
        if (!empty($searchParams['patient_type_eq'])) {
            $patientType = mysqli_real_escape_string($con, $searchParams['patient_type_eq']);
            $whereConditions[] = "lr.patient_type = '$patientType'";
        }
        
        $whereClause = implode(' AND ', $whereConditions);
        
        // Sanitize pagination inputs
        $limit = intval($limit);
        if ($limit <= 0) { $limit = 15; }
        $offset = intval($offset);
        if ($offset < 0) { $offset = 0; }

        // Optimized pagination using direct patient joins (no UNION) and minimal joins
        $sql = "
        SELECT 
          b.`date`,
          COALESCE(op.name, ip.name) AS name,
          COALESCE(op.gender, ip.gender) AS gender,
          COALESCE(op.age, ip.age) AS age,
          COALESCE(op.id, ip.ipd_no) AS id,
          COALESCE(op.mobile, ip.mobile) AS mobile,
          COALESCE(op.is_generated, ip.is_generated) AS is_generated,
          lr.visit_no,
          lr.bill_no,
          ip.ipd_no,
          op.opd_no,
          b.id as billid,
          lr.patient_type,
          COUNT(lr.id) as testcount,
          s.test_id as testid,
          s.id as sample_id,
          is_sample_acknowledge,
          sample_acknowledged_by,
          is_finalized,
          finalized_by,
          is_result_entered,
          result_entered_by,
          lr.bill_no as lab_count,
          `is_outsourced`,
          test_result,
          b.doctor,
          b.amount,
          b.paid_amount,
          b.outstand_amount,
          r.name as referal_center_name,
          b.date as bill_date,
            JSON_OBJECTAGG(
    t.testname,
    JSON_OBJECT(
      'has_result', IF(COALESCE(s.is_finalized, 0) = 1, TRUE, FALSE),
      'has_outsource', IF(COALESCE(s.is_outsourced, 0) = 1, TRUE, FALSE)
    )
  ) AS tests_map_by_name
        FROM (
          SELECT DISTINCT lr.visit_no
          FROM lab_report lr
          LEFT JOIN central_opd op ON lr.patient_type = 'OP' AND op.id = lr.patient_id
          LEFT JOIN central_ipd ip ON lr.patient_type = 'IP' AND ip.ipd_no = lr.patient_id
          LEFT JOIN billing b ON b.bill_no = lr.bill_no
          LEFT JOIN lab_test t ON t.testid = lr.testid
          WHERE $whereClause
          ORDER BY lr.visit_no DESC
          LIMIT $limit OFFSET $offset
        ) v
        JOIN lab_report lr ON lr.visit_no = v.visit_no
        LEFT JOIN central_opd op ON lr.patient_type = 'OP' AND op.id = lr.patient_id
        LEFT JOIN central_ipd ip ON lr.patient_type = 'IP' AND ip.ipd_no = lr.patient_id
        JOIN billing b ON b.bill_no = lr.bill_no
        LEFT JOIN reference_centers r ON b.referal_center_id = r.id
        LEFT JOIN sample_status s ON s.bill_no = lr.bill_no AND (
          (lr.patient_type = 'OP' AND s.patient_id = op.id) OR
          (lr.patient_type = 'IP' AND s.patient_id = ip.ipd_no)
        )
        LEFT JOIN lab_test t ON t.testid = lr.testid
        
        GROUP BY lr.visit_no
        ORDER BY lr.visit_no DESC
        ";
        // echo $sql;
        $res = mysqli_query($con, $sql);
        return $res;
    }
    function getLabpatientCount($searchParams = [])
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();

        $whereConditions = [];
        if(count($searchParams)==0)
        {
              $whereConditions[] = "lr.date='" . date('Y-m-d') . "'";
        }

        if (!empty($searchParams['patient_name_cont'])) {
            $patientName = mysqli_real_escape_string($con, $searchParams['patient_name_cont']);
            $whereConditions[] = "(op.name LIKE '%$patientName%' OR ip.name LIKE '%$patientName%')";
        }
        if (!empty($searchParams['lab_no_eq'])) {
            $labNo = mysqli_real_escape_string($con, $searchParams['lab_no_eq']);
            $whereConditions[] = "lr.visit_no like '%$labNo%'";
        }
        if (!empty($searchParams['bill_bill_no_eq'])) {
            $billNo = mysqli_real_escape_string($con, $searchParams['bill_bill_no_eq']);
            $whereConditions[] = "lr.bill_no like '%$billNo%'";
        }
        if (!empty($searchParams['patient_mobile_eq'])) {
            $mobile = mysqli_real_escape_string($con, $searchParams['patient_mobile_eq']);
            $whereConditions[] = "(op.mobile like '%$mobile%' OR ip.mobile like '%$mobile%')";
        }
        if (!empty($searchParams['status_eq'])) {
            $status = mysqli_real_escape_string($con, $searchParams['status_eq']);
            $whereConditions[] = "s.status = '$status'";
        }
        if (!empty($searchParams['test_name_eq'])) {
            $testId = mysqli_real_escape_string($con, $searchParams['test_name_eq']);
            $whereConditions[] = "t.testid = '$testId'";
        }
        if (!empty($searchParams['ip_no_eq'])) {
            $ipNo = mysqli_real_escape_string($con, $searchParams['ip_no_eq']);
            $whereConditions[] = "ip.ipd_no like '%$ipNo%'";
        }
        if (!empty($searchParams['op_no_eq'])) {
            $opNo = mysqli_real_escape_string($con, $searchParams['op_no_eq']);
            $whereConditions[] = "(op.opd_no like '%$opNo%' OR ip.opd_no like '%$opNo%')";
        }
        if (!empty($searchParams['lab_category_eq'])) {
            $category = mysqli_real_escape_string($con, $searchParams['lab_category_eq']);
            $whereConditions[] = "t.testname_header = '$category'";
        }
        if (!empty($searchParams['doctor_id_eq'])) {
            $doctorId = mysqli_real_escape_string($con, $searchParams['doctor_id_eq']);
            $whereConditions[] = "b.doctor = '$doctorId'";
        }
        if (!empty($searchParams['referral_center_eq'])) {
            $refCenter = mysqli_real_escape_string($con, $searchParams['referral_center_eq']);
            if ($refCenter == '0') {
                $whereConditions[] = "(b.referal_center_id IS NULL OR b.referal_center_id = 0)";
            } else {
                $whereConditions[] = "b.referal_center_id = '$refCenter'";
            }
        }
        if (!empty($searchParams['uhid_number_eq'])) {
            $uhid = mysqli_real_escape_string($con, $searchParams['uhid_number_eq']);
             $whereConditions[] = "(op.uhid like '%$uhid%' OR (ip.opd_no=op.opd_no and op.uhid like '%$uhid%'))";
        }
        if (!empty($searchParams['outsource_lab_eq'])) {
            $outLab = mysqli_real_escape_string($con, $searchParams['outsource_lab_eq']);
            $whereConditions[] = "s.outsource_lab_id = '$outLab'";
        }
        if (!empty($searchParams['patient_type_eq'])) {
            $patientType = mysqli_real_escape_string($con, $searchParams['patient_type_eq']);
            $whereConditions[] = "lr.patient_type = '$patientType'";
        }

        $whereClause = implode(' AND ', $whereConditions);

        $sql = "
        SELECT COUNT(*) as total FROM (
          SELECT DISTINCT lr.visit_no
          FROM lab_report lr
          LEFT JOIN central_opd op ON lr.patient_type = 'OP' AND op.id = lr.patient_id
          LEFT JOIN central_ipd ip ON lr.patient_type = 'IP' AND ip.ipd_no = lr.patient_id
          LEFT JOIN billing b ON b.bill_no = lr.bill_no
          LEFT JOIN lab_test t ON t.testid = lr.testid
          WHERE $whereClause
        ) x
        ";
        $res = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($res);
        return intval($row['total'] ?? 0);
    }
function getDoctors() {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "SELECT id, CONCAT(name, ' ', last_name) as doctor_name FROM doctor WHERE status = 'active' ORDER BY name";
        $res = mysqli_query($con, $sql);
        return $res;
    }

    function getReferenceCenters() {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "SELECT id, name FROM reference_centers WHERE status = 'active' ORDER BY name";
        $res = mysqli_query($con, $sql);
        return $res;
    }

    function getLabCategories() {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "SELECT DISTINCT(testname_header) as lab_category_name FROM lab_test WHERE testname_header IS NOT NULL AND testname_header != '' ORDER BY testname_header";
        $res = mysqli_query($con, $sql);
        return $res;
    }

    function getOutsourceLabs() {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "SELECT id, name FROM outsource_labs  ORDER BY name";
        $res = mysqli_query($con, $sql);
        return $res;
    }

    function getTestNames() {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "SELECT testid, testname FROM lab_test WHERE status = 'active' ORDER BY testname";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    function getStockValue() {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        
        $sql = "SELECT SUM(si.current_stock * si.purchase_rate) as total_value 
                FROM store_items si 
                WHERE si.status = 'active'";
        
        return mysqli_query($con, $sql);
    }
    
    function getLowStockItems() {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        
        $sql = "SELECT si.id, si.item_code, si.item_name, si.current_stock, si.min_stock_level, si.reorder_level 
                FROM store_items si 
                WHERE si.status = 'active' AND si.current_stock <= si.min_stock_level 
                ORDER BY si.current_stock ASC 
                LIMIT 20";
        
        return mysqli_query($con, $sql);
    }
    
    function getExpiringItems($days = 30) {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        
        $sql = "SELECT DISTINCT si.item_name, si.item_code, sbm.batch_no, sbm.expiry_date, sbm.available_qty 
                FROM store_batch_master sbm
                JOIN store_items si ON sbm.item_id = si.id
                WHERE sbm.expiry_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL $days DAY)
                AND sbm.available_qty > 0
                ORDER BY sbm.expiry_date ASC
                LIMIT 20";
        
        return mysqli_query($con, $sql);
    }
    
    function getTodayGRNCount($today) {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        
        $sql = "SELECT COUNT(*) as count FROM store_grn WHERE DATE(grn_date) = '$today'";
        
        return mysqli_query($con, $sql);
    }
    
    function getTodayIssueCount($today) {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        
        $sql = "SELECT COUNT(*) as count FROM store_issue WHERE DATE(issue_date) = '$today'";
        
        return mysqli_query($con, $sql);
    }
    
    function getRecentGRNs($limit = 5) {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        
        $sql = "SELECT sg.*, ss.name as supplier_name 
                FROM store_grn sg 
                LEFT JOIN store_supplier ss ON sg.supplier_id = ss.id 
                ORDER BY sg.grn_date DESC, sg.id DESC 
                LIMIT $limit";
        
        return mysqli_query($con, $sql);
    }
    
    function getRecentIssues($limit = 5) {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        
        $sql = "SELECT * FROM store_issue 
                ORDER BY issue_date DESC, id DESC 
                LIMIT $limit";
        
        return mysqli_query($con, $sql);
    }
     function getActiveItems() {
        $newlogin = new userlogin;                  
        $con = $newlogin->dbconnect();
        $sql = "SELECT si.*, sc.category_name, su.unit_name 
                FROM `store_items` si 
                LEFT JOIN `store_categories` sc ON si.category_id = sc.id 
                LEFT JOIN `store_units` su ON si.unit_id = su.id 
                WHERE si.status=1 
                ORDER BY si.item_name";
        $res = mysqli_query($con, $sql);
        return $res;
    }
}

?>