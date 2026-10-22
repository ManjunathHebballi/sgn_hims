<?php 
require_once(__DIR__ . '/../../model/session_config.php');
include('../model/dbconnect.php');


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
        $sql="SELECT COUNT(CASE WHEN ward like 'male%' and b.status='active' THEN 1 END) AS male, COUNT(CASE WHEN ward like 'female%' and b.status='active' THEN 1 END) AS female, COUNT(CASE WHEN b.status = 'Occupied' THEN 1 END) AS occupied, d.dept_name, d.bed, d.unit FROM department d LEFT JOIN bed b ON d.id = b.department and b.id <=330 WHERE d.dept_type != 'OPD' AND d.status = 'active' GROUP BY d.dept_name, d.bed, d.unit ORDER BY d.id";
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
    function selectOPreg_seacrh($from, $to, $search, $doctor, $limit, $offset)
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

    $sql = "
        SELECT 
            opd.*,
            d.dept_name,
            CONCAT(doc.name,' ',doc.last_name) AS doctor,
            (
                SELECT COUNT(id) 
                FROM billing 
                WHERE patient_id = opd.id 
                AND service_type = 'op'
            ) AS bill
        FROM central_opd opd
        INNER JOIN department d ON opd.dept_id = d.id
        INNER JOIN doctor doc ON doc.id = opd.doctor
        WHERE $data
        AND CONCAT(doc.name,' ',doc.last_name) LIKE '$doctor%'
        ORDER BY opd.date DESC
        
    ";
//LIMIT $limit OFFSET $offset
    return mysqli_query($con, $sql);
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
    
        $sql = "
            SELECT COUNT(opd.id) AS total
            FROM central_opd opd
            INNER JOIN doctor doc ON doc.id = opd.doctor
            WHERE $data
            AND CONCAT(doc.name,' ',doc.last_name) LIKE '$doctor%'
        ";
    
        $res = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($res);
        return $row['total'];
    }

    function selectIPreg_seacrh($from,$to,$search)
    {
      $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      if(strlen($search)>1)
      {
          $data="(address like '%$search%' || opd_no like '$search%' || opd.name like '%$search%' || opd.mobile like '$search%' || ipd_no like '$search%')";
      }
      elseif($from==date('Y-m-d')){
          $data="opd.discharge=0";
      }
      else{
          $data="opd.admission_date between '$from' and '$to'";
      }
      $sql="SELECT opd.*,d.dept_name FROM department d,`central_ipd` opd  WHERE $data and opd.dept_id=d.id  group by opd.id ";
    //   echo $sql;
      $res=mysqli_query($con,$sql);
      return $res;
    }
    function getDoctor()
    {
        $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      $sql="SELECT id,concat(name,' ',last_name) as name FROM doctor where status='active'";
      $res=mysqli_query($con,$sql);
      return $res;
    }
     function getDayWiseCollection($from, $to, $service = '', $payment_mode = '') {
        $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
       $service_filter = '';
       if($service != '') {
           $service_filter = " AND b.service_type = '$service'";
       }
       
       $payment_filter = '';
       if($payment_mode != '') {
           $payment_filter = " AND bp.payment_mode = '$payment_mode'";
       }
       
       $sql = "SELECT 
                   DATE(bp.date) as bill_date,
                   b.service_type,
                   SUM(CASE WHEN bp.payment_mode = 'Cash' THEN bp.amount ELSE 0 END) as cash,
                   SUM(CASE WHEN bp.payment_mode = 'UPI' THEN bp.amount ELSE 0 END) as upi,
                   SUM(CASE WHEN bp.payment_mode = 'Card' THEN bp.amount ELSE 0 END) as card,
                   SUM(CASE WHEN bp.payment_mode = 'Cheque' THEN bp.amount ELSE 0 END) as cheque,
                   SUM(CASE WHEN bp.payment_mode = 'Bank Transfer' THEN bp.amount ELSE 0 END) as bank,
                   COUNT(DISTINCT b.id) as bills_count
               FROM billing b
               JOIN billing_payment bp ON b.id = bp.bill_id
               WHERE DATE(bp.date) >= '$from' 
               AND DATE(bp.date) <= '$to'
               AND b.status != 'Cancelled'
               $service_filter
               $payment_filter
               GROUP BY DATE(bp.date), b.service_type
               ORDER BY DATE(bp.date) DESC, b.service_type";
       
       return mysqli_query($con, $sql);
   }
   function getOutstandingBillsCount() {
        $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
       
       $sql = "SELECT COUNT(*) as count,sum(outstand_amount) as amount
               FROM billing 
               WHERE status != 'Cancelled' 
               AND outstand_amount > 0";
       
       $res = mysqli_query($con, $sql);
       $row = mysqli_fetch_assoc($res);
       return $row;
   }
}

?>