<?php
require_once(__DIR__ . '/session_config.php');
include('dbconnect.php');


class report{
   function getOTDetails($op_from,$op_to)
   {
    
        $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT c.*,d.dept_name,concat(u.name,' ',u.last_name) as doctor_name,u.unit,ci.ipd_no FROM  department d , `central_opd` c left join doctor u on c.doctor=u.id left join central_ipd ci on ci.opd_no=c.opd_no WHERE d.id=c.dept_id and c.`date` between '$op_from' and '$op_to' order by date,id";
          // echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
    
   }
   function getOPDetails($op_from,$op_to,$gen,$dept)
   {
    
        $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $cond='';
          if($dept>0)
          {
              $cond .=" and d.id='$dept'";
          }
          if($_SESSION['role']=='Generated')
          {
              $cond .=" and c.is_generated=1 ";
          }
          $sql="SELECT c.*,d.dept_name,concat(u.name,' ',u.last_name) as doctor_name,u.unit,ci.ipd_no FROM  department d , `central_opd` c left join doctor u on c.doctor=u.id left join central_ipd ci on ci.opd_no=c.opd_no WHERE d.id=c.dept_id and c.`date` between '$op_from' and '$op_to' and c.is_generated like '$gen%' $cond order by date,id";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
    
   }
    function getIPDetails($ip_from,$ip_to)
   {
    
        $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          
          $sql="SELECT c.*,d.dept_name,di.discharge_date,concat(u.name,' ',u.last_name) as doctor_name,u.unit,di.advice_discharge,co.uhid,bed_number FROM `department` d,central_opd co,bed b,`central_ipd` c LEFT JOIN `discharge` di ON di.patient_id=c.ipd_no LEFT JOIN `doctor` u on concat(u.name,' ',u.last_name)=c.doctor WHERE co.opd_no=c.opd_no and c.bed_id=b.id and d.id=c.dept_id and date(c.`admission_date`) between '$ip_from' and '$ip_to 23:59:59' order by c.admission_date,id";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
    
   }
   function getIPGenDetails($ip_from,$ip_to,$gen,$dept,$gender)
   {
    
        $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $cond='';
          if($dept>0)
          {
              $cond=" and d.id='$dept' and c.ward!='ICU'";
          }
          else if($dept=="ICU"){
              $cond=" and c.ward='ICU'";
          }
          if($_SESSION['role']=='Generated')
          {
              $cond .=" and c.is_generated=1 ";
          }
          $sql="SELECT c.*, co.id as op_case_id, d.dept_name,di.discharge_date,concat(c.doctor) as doctor_name,u.unit,di.advice_discharge,co.uhid,b.bed_number,b.ward,di.created_by as dis_by FROM  central_opd co,`department` d,`central_ipd` c LEFT JOIN `discharge` di ON di.patient_id=c.ipd_no LEFT JOIN `doctor` u on concat(u.name,' ',u.last_name)=c.doctor and c.dept_id=u.department  left join bed b on b.id=c.bed_id  WHERE co.opd_no=c.opd_no and d.id=c.dept_id and date(c.`admission_date`) between '$ip_from' and '$ip_to 23:59:59' and c.gender like '$gender%' and c.is_generated like '$gen%' $cond group by c.ipd_no order by c.ipd_no";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
    
   }   
   function getDisGenDetails($from,$to,$gen,$dept)
   {
    
        $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $cond='';
          if($dept>0)
          {
              $cond=" and d.id='$dept'";
          }
          if($_SESSION['role']=='Generated')
          {
              $cond .=" and c.is_generated=1 ";
          }
          $sql="SELECT c.*,d.dept_name,di.discharge_date,concat(u.name,' ',u.last_name) as doctor_name,u.unit,di.advice_discharge,di.created_by FROM `department` d, `discharge` di,`central_ipd` c   LEFT JOIN `doctor` u on concat(u.name,' ',u.last_name)=c.doctor WHERE d.id=c.dept_id and date(di.`discharge_date`) between '$from' and '$to' and di.patient_id=c.ipd_no and c.is_generated like '$gen%' $cond order by c.admission_date,id";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
    
   }   
   
    function getIPDetailsForSlip($ipno)
   {
    
        $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
           $sql="SELECT c.*,d.dept_name,d.unit as dunit,di.discharge_date,concat(u.name,' ',u.last_name) as doctor_name,u.unit,di.advice_discharge,b.bed_number,c.patient_type,uhid,u.sepeciality,u.mobile as doctor_mobile,co.marital_status,co.parent_name,co.city FROM `department` d,`bed` b,central_opd co,`central_ipd` c LEFT JOIN `discharge` di ON di.patient_id=c.ipd_no LEFT JOIN `doctor` u on concat(u.name,' ',u.last_name)=c.doctor  WHERE d.id=c.dept_id and co.opd_no=c.opd_no and c.id='$ipno' and b.id=c.bed_id order by c.admission_date";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
    
   }
    function getIPDetailsDept($ip_from,$ip_to,$id)
   {
    
        $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT c.*,d.dept_name,di.discharge_date,concat(u.name,' ',u.last_name) as doctor_name,u.unit,di.advice_discharge,co.uhid FROM central_opd co,`department` d,`central_ipd` c LEFT JOIN `discharge` di ON di.patient_id=c.ipd_no LEFT JOIN `doctor` u on concat(u.name,' ',u.last_name)=c.doctor WHERE co.opd_no=c.opd_no and d.id=c.dept_id and date(c.`admission_date`) between '$ip_from' and '$ip_to 23:59:59' and d.id='$id' order by c.admission_date";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
    
   }
   function getDeptDetails($op_from,$op_to,$dept,$type)
   {
    
        $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $cond='';
          if($type=='new')
          {
            $cond="and c.old_patient=0";
          }
         else if($type=='old')
          {
            $cond="and c.old_patient=1";
          }
          $sql="SELECT c.*,d.dept_name,GROUP_CONCAT(m.name) as medic,concat(do.name,' ',do.last_name) as doc FROM department d,`central_opd` c LEFT JOIN `op_medicine` om ON c.id=om.patient_id LEFT JOIN `medicine` m ON m.id=om.medicine left join doctor do on do.id=c.doctor  WHERE d.id=c.dept_id and c.`date` between '$op_from' and '$op_to' and d.id=$dept $cond group by c.date,c.id";
          
          // echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
    
   }
   
   
   function getDept_OP($dept_from,$dept_to)
   {
            $newlogin = new userlogin;                  
            $con=$newlogin->dbconnect();
            $sql="SELECT sum(c.old_patient) as old_patient,count(c.id) as num,d.dept_name,d.id FROM `central_opd` c, department d WHERE d.id=c.dept_id and c.`date` between '$dept_from' and '$dept_to' GROUP by dept_id order by dept_id";
        //   echo $sql;
            $res=mysqli_query($con,$sql);
            return $res;
   }
   function getDept_OP1($dept_from,$dept_to,$gen,$dept)
   {
            $newlogin = new userlogin;                  
            $con=$newlogin->dbconnect();
            $cond='';
             if($dept>0)
          {
              $cond .=" and d.id='$dept'";
          }
           if($_SESSION['role']=='Generated')
          {
              $cond .=" and c.is_generated=1 ";
          }
            $sql="SELECT sum(c.old_patient) as old_patient,count(c.id) as num,d.dept_name,d.id FROM `central_opd` c, department d WHERE d.id=c.dept_id and c.`date` between '$dept_from' and '$dept_to' and is_generated like '$gen%' $cond GROUP by dept_id order by dept_id";
        //   echo $sql;
            $res=mysqli_query($con,$sql);
            return $res;
   }
   function getsubDept_OP($dept_from,$dept_to)
   {
            $newlogin = new userlogin;                  
            $con=$newlogin->dbconnect();
            $sql="SELECT sum(c.old_patient) as old_patient,count(c.id) as num,d.sub_department FROM `central_opd` c, sub_department d WHERE d.id=c.sub_deptid and c.`date` between '$dept_from' and '$dept_to' GROUP by sub_deptid";
        //   echo $sql;
            $res=mysqli_query($con,$sql);
            return $res;
   }
    function getDept_IP($dept_from,$dept_to)
   {
            $newlogin = new userlogin;                  
            $con=$newlogin->dbconnect();
            $sql="SELECT count(c.id) as num,d.dept_name,d.id FROM `central_ipd` c, department d WHERE d.id=c.dept_id and date(c.`admission_date`) between '$dept_from' and '$dept_to' GROUP by dept_id";
        //   echo $sql;
            $res=mysqli_query($con,$sql);
            return $res;
   }
    function getDept_IP1($dept_from,$dept_to,$gen,$dept)
   {
            $newlogin = new userlogin;                  
            $con=$newlogin->dbconnect();
          if($dept>0)
          {
              $cond=" and d.id='$dept' and c.ward!='ICU'";
          }
          else if($dept=="ICU"){
              $cond=" and c.ward='ICU'";
          }
           if($_SESSION['role']=='Generated')
          {
              $cond .=" and c.is_generated=1 ";
          }
            $sql="SELECT count(c.id) as num,d.dept_name,d.id FROM `central_ipd` c, department d WHERE d.id=c.dept_id and date(c.`admission_date`) between '$dept_from' and '$dept_to' and is_generated like '$gen%' $cond GROUP by dept_id";
        //   echo $sql;
            $res=mysqli_query($con,$sql);
            return $res;
   }
   function getGenderDept_IP($dept_from,$dept_to)
   {
            $newlogin = new userlogin;                  
            $con=$newlogin->dbconnect();
            $sql="SELECT sum(case WHEN gender='male' then 1 else 0 end) as male, sum(case WHEN gender='female' then 1 else 0 end) as female ,d.dept_name,d.id FROM `central_ipd` c, department d WHERE d.id=c.dept_id and date(c.`admission_date`) between '$dept_from' and '$dept_to' GROUP by dept_id";
        //   echo $sql;
            $res=mysqli_query($con,$sql);
            return $res;
   }
 function getDeptBed($dept_from,$dept_to)
 {
            $newlogin = new userlogin;                  
            $con=$newlogin->dbconnect();
            if($dept_from==$dept_to)
            {
            $sql="SELECT d.`dept_name`, (SELECT count(dis.id) FROM `discharge` dis,central_ipd c where DATE(dis.discharge_date) > '$dept_from' and c.ipd_no=dis.patient_id and DATE(dis.admission_date) <= '$dept_from' and dis.dept_id=d.id)+ (select count(i.id) from central_ipd i where i.discharge=0 and DATE(i.admission_date)<='$dept_from' and i.dept_id=d.id) as bed,(select count(ci.bed_id) FROM `central_ipd` ci where d.id=ci.dept_id and date(ci.`admission_date`) between '$dept_from' and '$dept_to 23:59:59') as occupied, (SELECT count(dis.id) FROM `discharge` dis,central_ipd c where DATE(dis.discharge_date) between '$dept_from' and '$dept_to' and c.ipd_no=dis.patient_id and dis.dept_id=d.id) as discharge from department d where status='active' and dept_type='both' and id!=17 group by dept_name ";
                    //   echo $sql;
            $res=mysqli_query($con,$sql);
            }
            else{
                $dept=array();
              $data=$res=array();
                $dates = $this->getBetweenDates($dept_from, $dept_to);
                // print_r($dates);exit;
                for($i=0;$i<count($dates);$i++)
                {
                      
                    $query="SELECT d.id,(SELECT count(dis.id) FROM `discharge` dis,central_ipd c where DATE(dis.discharge_date) > '$dates[$i]' and DATE(dis.admission_date) <= '$dates[$i]' and c.ipd_no=dis.patient_id and dis.dept_id=d.id )+ (select count(i.id) from central_ipd i where i.discharge=0 and DATE(i.admission_date)<='$dates[$i]' and i.dept_id=d.id ) as occ from department d where status='active' and dept_type='both' and id!=17 group by d.id order by FIELD(d.id, '3','4','7','6','5','2','11','8','1','9','10')";
                    // echo $query;exit;
            $res2=mysqli_query($con,$query);
                    while($row1=mysqli_fetch_assoc($res2))
                    {
                        // echo "$row1[occ]<br>";
                        $dept[$row1['id']] +=$row1['occ'];
                    }
                }
                // print_r($dept);
                // exit;
                $sql="SELECT d.id,d.`dept_name`,(case when d.id=3 then $dept[3] when d.id=4 then $dept[4] when d.id=5 then $dept[5] when d.id=6 then $dept[6] when d.id=7 then $dept[7] when d.id=2 then $dept[2] else 0 end) as bed, (select count(ci.bed_id) FROM `central_ipd` ci where d.id=ci.dept_id and date(ci.`admission_date`) between '$dept_from' and '$dept_to 23:59:59') as occupied, (SELECT count(dis.id) FROM `discharge` dis,central_ipd c where DATE(dis.discharge_date) between '$dept_from' and '$dept_to' and dis.dept_id=d.id and c.ipd_no=dis.patient_id ) as discharge from department d where status='active' and dept_type='both' and id!=17  group by dept_name ";
                // echo $sql;exit;
                $res=mysqli_query($con,$sql);
                
            }
            return $res;
   }
   function geticuDeptBed($dept_from,$dept_to)
   {
            $newlogin = new userlogin;                  
            $con=$newlogin->dbconnect();
            if($dept_from==$dept_to)
            {
            $sql="SELECT ward,SUM(bed_count) AS bed,SUM(occupied_count) AS occupied,SUM(discharge_count) AS discharge FROM (SELECT (CASE WHEN d.department = 17 THEN 'Emergency' ELSE d.ward END) AS ward,(SELECT COUNT(dis.id) FROM discharge dis, central_ipd c WHERE DATE(dis.discharge_date) > '$dept_from' AND c.ipd_no = dis.patient_id AND DATE(dis.admission_date) <= '$dept_from' AND c.ward = d.ward ) + (SELECT COUNT(i.id) FROM central_ipd i WHERE i.discharge = 0 AND DATE(i.admission_date) <= '$dept_from' AND i.ward = d.ward ) AS bed_count,(SELECT COUNT(ci.bed_id) FROM central_ipd ci WHERE date(ci.admission_date) BETWEEN '$dept_from' AND '$dept_to 23:59:59' AND ci.ward = d.ward ) AS occupied_count,(SELECT COUNT(dis.id) FROM discharge dis, central_ipd c WHERE DATE(dis.discharge_date) between '$dept_from' and '$dept_to' AND c.ipd_no = dis.patient_id AND c.ward = d.ward ) AS discharge_count FROM bed d  GROUP BY ward ORDER BY ward ) AS subquery GROUP BY ward ORDER BY ward;";
                    //   echo $sql;
            $res=mysqli_query($con,$sql);
            }
            else{
                $dept=array();
              $data=$res=array();
                $dates = $this->getBetweenDates($dept_from, $dept_to);
                // print_r($dates);exit;
                for($i=0;$i<count($dates);$i++)
                {
                      
                    $query="SELECT (case when d.department=17 then 'Emergency' when d.ward like 'OBG%' then 'obg' else d.ward end) as ward,(SELECT count(dis.id) FROM `discharge` dis,central_ipd c where DATE(dis.discharge_date) > '$dates[$i]' and DATE(dis.admission_date) <= '$dates[$i]' and c.ipd_no=dis.patient_id and c.ward=d.ward )+ (select count(i.id) from central_ipd i where i.discharge=0 and DATE(i.admission_date)<='$dates[$i]' and i.ward=d.ward ) as occ from bed d  group by ward ";
                    // echo $query;
            $res2=mysqli_query($con,$query);
                    while($row1=mysqli_fetch_assoc($res2))
                    {
                        // echo "$row1[occ]<br>";
                        $dept[$row1['ward']] +=$row1['occ'];
                    }
                }
                // print_r($dept);
                // exit;
                $sql="SELECT  ward,  SUM(bed1) AS bed,  SUM(occupied1) AS occupied,  SUM(discharge1) AS discharge FROM (SELECT (case when d.department=17 then 'Emergency' else d.ward end) as ward,(case when d.ward='NICU(6th Floor)' then ".$dept['NICU(6th Floor)']." when d.ward='PICU (6th FLOOR)' then ".$dept['PICU (6th FLOOR)']." when d.ward='SICU (5th FLOOR)' then ".$dept['SICU (5th FLOOR)']." when d.ward='OBGY ICU (6th  Floor)' then ".$dept['obg']." when d.ward='ICU (2nd FLOOR)' then ".$dept['ICU (2nd FLOOR)']." when d.ward='IICU (2nd FLOOR)' then ".$dept['IICU (2nd FLOOR)']." when d.department='17' then ".$dept['Emergency']." else 0 end) as bed1, (select count(ci.bed_id) FROM `central_ipd` ci where d.ward=ci.ward and date(ci.`admission_date`) between '$dept_from' and '$dept_to 23:59:59' ) as occupied1, (SELECT count(dis.id) FROM `discharge` dis,central_ipd c where DATE(dis.discharge_date) between '$dept_from' and '$dept_to' and c.ward=d.ward and c.ipd_no=dis.patient_id ) as discharge1 from bed d   group by ward) AS subquery GROUP BY ward ORDER by ward;";
                // echo $sql;
                $res=mysqli_query($con,$sql);
                
            }
            return $res;
   }
   function getBetweenDates($startDate, $endDate)
    {
        $rangArray = [];
            
        $startDate = strtotime($startDate);
        $endDate = strtotime($endDate);
             
        for ($currentDate = $startDate; $currentDate <= $endDate; 
                                        $currentDate += (86400)) {
                                                
            $date = date('Y-m-d', $currentDate);
            $rangArray[] = $date;
        }
  
        return $rangArray;
    }
   function getFumigation($from,$to)
   {
            $newlogin = new userlogin;                  
            $con=$newlogin->dbconnect();
            $sql="SELECT * FROM `fumigation_register` where date between '$from' and '$to' ";
        //   echo $sql;
            $res=mysqli_query($con,$sql);
            return $res;
   }
   function getSwab($from,$to)
   {
            $newlogin = new userlogin;                  
            $con=$newlogin->dbconnect();
            $sql="SELECT * FROM `swab_register` where date between '$from' and '$to' ";
        //   echo $sql;
            $res=mysqli_query($con,$sql);
            return $res;
   }
    function getPad($from,$to)
   {
            $newlogin = new userlogin;                  
            $con=$newlogin->dbconnect();
            $sql="SELECT * FROM `pad_register` where date between '$from' and '$to' ";
        //   echo $sql;
            $res=mysqli_query($con,$sql);
            return $res;
   }
    function getClave($from,$to)
   {
            $newlogin = new userlogin;                  
            $con=$newlogin->dbconnect();
            $sql="SELECT * FROM `clave_register` where date between '$from' and '$to' ";
        //   echo $sql;
            $res=mysqli_query($con,$sql);
            return $res;
   }
   function getStock($from,$to)
   {
            $newlogin = new userlogin;                  
            $con=$newlogin->dbconnect();
            $sql="SELECT * FROM `stock_register` where date between '$from' and '$to' ";
        //   echo $sql;
            $res=mysqli_query($con,$sql);
            return $res;
   }
   function getAnasthesia($from,$to)
   {
            $newlogin = new userlogin;                  
            $con=$newlogin->dbconnect();
            $sql="SELECT a.*,p.name,p.age,p.diagnosis FROM `anaesthesia` a,central_ipd p where p.ipd_no=a.ip_num and a.date between '$from' and '$to' order by p.admission_date";
        //   echo $sql;
            $res=mysqli_query($con,$sql);
            return $res;
   }
   function labReport($from,$to)
   {
            $newlogin = new userlogin;                  
            $con=$newlogin->dbconnect();
            $sql="SELECT lt.testname_header, COUNT(DISTINCT CASE WHEN lr.patient_type = 'OP' THEN lr.visit_no END) AS OP, COUNT(DISTINCT CASE WHEN lr.patient_type = 'IP' THEN lr.visit_no END) AS IP FROM lab_report lr JOIN lab_test lt ON lr.testid = lt.testid WHERE lr.date BETWEEN '$from' AND '$to' GROUP BY lt.testname_header ORDER BY lt.testname_header";
            // echo $sql;
            $res=mysqli_query($con,$sql);
            return $res;
   }
   
   function labReportParameter($from,$to)
   {
            $newlogin = new userlogin;                  
            $con=$newlogin->dbconnect();
            $sql="SELECT lt.testname as name,lt.testid, SUM(CASE WHEN lr.patient_type = 'OP' THEN 1 ELSE 0 END) AS OP, SUM(CASE WHEN lr.patient_type = 'IP' THEN 1 ELSE 0 END) AS IP FROM lab_report lr JOIN lab_test lt ON lr.testid = lt.testid WHERE lr.date BETWEEN '$from' AND '$to' GROUP BY lt.testid ORDER BY lt.testname";
            // echo $sql;
            $res=mysqli_query($con,$sql);
            return $res;
   }
   
   function iplabReport($from,$to,$test)
   {
            $newlogin = new userlogin;                  
            $con=$newlogin->dbconnect();
            $sql="SELECT c.*, group_concat(lp.`name` SEPARATOR '<br>') as test, group_concat(lr.`test_result` SEPARATOR '<br>') as test_result,lr.`date` as ldate,lt.testname,d.dept_name FROM `lab_report` lr,`lab_test_parameter` lp,lab_test lt,central_ipd c,department d WHERE d.id=c.dept_id and c.ipd_no=lr.patient_id and lt.`testid` =lp.`test_id` and lp.id=lr.parameter_id and lt.testname_header='$test' and lr.date between '$from' and '$to' and lr.patient_type='IP' group by patient_id,lr.date,lr.visit_no order by lr.date,position";
        //   echo $sql;
            $res=mysqli_query($con,$sql);
            return $res;
   }
   
   function iplabReportParameter($from,$to,$test)
   {
            $newlogin = new userlogin;                  
            $con=$newlogin->dbconnect();
            $sql="SELECT c.*, lp.`name`, `test_result`,lr.`date` as ldate,lt.testname,d.dept_name FROM `lab_report` lr,`lab_test_parameter` lp,lab_test lt,central_ipd c,department d WHERE d.id=c.dept_id and c.ipd_no=lr.patient_id and lt.`testid` =lp.`test_id` and lp.id=lr.parameter_id and lt.testid='$test' and lr.date between '$from' and '$to' and lr.patient_type='IP' order by lr.date,position";
        //   echo $sql;
            $res=mysqli_query($con,$sql);
            return $res;
   }
   
   
    function oplabReport($from,$to,$test)
   {
            $newlogin = new userlogin;                  
            $con=$newlogin->dbconnect();
            $sql="SELECT c.*, group_concat(lp.`name` SEPARATOR '<br>') as test, group_concat(lr.`test_result` SEPARATOR '<br>') as test_result,lr.`date` as ldate,lt.testname,d.dept_name,concat(u.name,' ',u.last_name) as doc FROM `lab_report` lr,`lab_test_parameter` lp,lab_test lt,central_opd c,department d,doctor u WHERE u.id=c.doctor and d.id=c.dept_id and c.id=lr.patient_id and lt.`testid` =lp.`test_id` and lp.id=lr.parameter_id and lt.testname_header='$test' and lr.date between '$from' and '$to' and lr.patient_type='OP' group by patient_id,lr.date order by lr.date,position";
            // $sql="SELECT ltg.test_name,lr.date,lr.test_result,op.opd_no,op.name,op.gender,d.dept_name,concat(u.name,' ',u.last_name) as doctor,lr.lab_tech FROM `lab_report` lr,`labtest_group` ltg,`central_opd` op,lab_test lt,department d,doctor u where lr.`lab_result`=ltg.`testid`and lt.testid=ltg.labid and op.id=lr.patient_id and lr.date between '$from' and '$to' and lt.testid=$id  and u.id=op.doctor and d.id=op.dept_id";
        //   echo $sql;
            $res=mysqli_query($con,$sql);
            return $res;
   }
   
   
    function totalLabReport($from, $to, $test)
    {
    $newlogin = new userlogin;                  
    $con = $newlogin->dbconnect();

    $sql = "
        SELECT 
            lr.visit_no as visit_no,
            c.diagnosis,
            c.name,
            c.age,
            c.gender,
            c.opd_no,
            c.ipd_no,
            GROUP_CONCAT(lp.name SEPARATOR '<br>') AS test,
            GROUP_CONCAT(lr.test_result SEPARATOR '<br>') AS test_result,
            lr.date AS ldate,
            lt.testname,
            d.dept_name,
            doctor AS doc,
            lr.patient_type
        FROM lab_report lr
        JOIN lab_test_parameter lp ON lp.id = lr.parameter_id
        JOIN lab_test lt ON lt.testid = lp.test_id
        JOIN central_ipd c ON c.ipd_no = lr.patient_id
        JOIN department d ON d.id = c.dept_id
        WHERE 
            lt.testname_header = '$test'
            AND lr.date BETWEEN '$from' AND '$to'
            AND lr.patient_type = 'IP'
        GROUP BY lr.patient_id, lr.date, lr.visit_no

        UNION ALL

        SELECT 
            lr.visit_no as visit_no,
            c.diagnosis,
            c.name,
            c.age,
            c.gender,
            c.opd_no,
            '' AS ipd_no,
            GROUP_CONCAT(lp.name SEPARATOR '<br>') AS test,
            GROUP_CONCAT(lr.test_result SEPARATOR '<br>') AS test_result,
            lr.date AS ldate,
            lt.testname,
            d.dept_name,
            CONCAT(u.name,' ',u.last_name) AS doc,
            lr.patient_type
        FROM lab_report lr
        JOIN lab_test_parameter lp ON lp.id = lr.parameter_id
        JOIN lab_test lt ON lt.testid = lp.test_id
        JOIN central_opd c ON c.id = lr.patient_id
        JOIN department d ON d.id = c.dept_id
        JOIN doctor u ON u.id = c.doctor
        WHERE 
            lt.testname_header = '$test'
            AND lr.date BETWEEN '$from' AND '$to'
            AND lr.patient_type = 'OP'
        GROUP BY lr.patient_id, lr.date

        ORDER BY ldate, visit_no
    ";
    $res = mysqli_query($con, $sql);
    return $res;
}

   
    function oplabReportParameter($from,$to,$test)
   {
            $newlogin = new userlogin;                  
            $con=$newlogin->dbconnect();
            $sql="SELECT c.*, lp.`name`, lr.`test_result`,lr.`date` as ldate,lt.testname,d.dept_name,concat(u.name,' ',u.last_name) as doc FROM `lab_report` lr,`lab_test_parameter` lp,lab_test lt,central_opd c,department d,doctor u WHERE u.id=c.doctor and d.id=c.dept_id and c.id=lr.patient_id and lt.`testid` =lp.`test_id` and lp.id=lr.parameter_id and lt.testid='$test' and lr.date between '$from' and '$to' and lr.patient_type='OP'  order by lr.date,position";
            // $sql="SELECT ltg.test_name,lr.date,lr.test_result,op.opd_no,op.name,op.gender,d.dept_name,concat(u.name,' ',u.last_name) as doctor,lr.lab_tech FROM `lab_report` lr,`labtest_group` ltg,`central_opd` op,lab_test lt,department d,doctor u where lr.`lab_result`=ltg.`testid`and lt.testid=ltg.labid and op.id=lr.patient_id and lr.date between '$from' and '$to' and lt.testid=$id  and u.id=op.doctor and d.id=op.dept_id";
        //   echo $sql;
            $res=mysqli_query($con,$sql);
            return $res;
   }
    function otProceReport($from,$to)
   {
            $newlogin = new userlogin;                  
            $con=$newlogin->dbconnect();
            $sql="SELECT op.`ot_procedure`,count(op.id) as val,op.id FROM `ot_procedures` op,operation_theater ot where op.id=ot.ot_procedure group by op.id";
        //   echo $sql;
            $res=mysqli_query($con,$sql);
            return $res;
   }
       function otProcedureReport($from,$to,$id)
   {
            $newlogin = new userlogin;                  
            $con=$newlogin->dbconnect();
            $sql="SELECT ip.*,op.ot_procedure,ty.ot_name,d.name as doctor,ot.date,ot.comment FROM `central_ipd` ip,doctor d,ot_type ty,operation_theater ot LEFT JOIN ot_procedures op on op.id=ot.ot_procedure where d.id=ot.doctor and ty.id=ot.ot_type and ot.ot_procedure=$id and ip.ipd_no=ot.patient_id and ot.`date` between '$from 12:00:00' and '$to 23:59:00' ";
        //   echo $sql;exit;
            $res=mysqli_query($con,$sql);
            return $res;
   }
    function otTypeReport($from,$to)
   {
            $newlogin = new userlogin;                  
            $con=$newlogin->dbconnect();
            $sql="SELECT op.`ot_name`,count(ot.id) as val,op.id FROM `ot_type` op left join operation_theater ot on op.id=ot.ot_type and date(date) between '$from' and '$to' group by op.id order by op.id,date";
        //   echo $sql;
            $res=mysqli_query($con,$sql);
            return $res;
   }
        function otTypeReports($from,$to,$id)
   {
            $newlogin = new userlogin;                  
            $con=$newlogin->dbconnect();
            $sql="(SELECT ip.opd_no,ipd_no,name,gender,age,diagnosis,op.ot_procedure,ty.ot_name,ot.date,ot.comment,ot.doctor FROM `central_ipd` ip,ot_type ty,operation_theater ot LEFT JOIN ot_procedures op on op.id=ot.ot_procedure where ty.id=ot.ot_type and ot.ot_type=$id and ip.ipd_no=ot.patient_id and DATE(ot.`date`) between '$from' and '$to')
            UNION (SELECT ip.opd_no,'' as ipd_no,name,gender,age,diagnosis,op.ot_procedure,ty.ot_name,ot.date,ot.comment,ot.doctor FROM `central_opd` ip,ot_type ty,operation_theater ot LEFT JOIN ot_procedures op on op.id=ot.ot_procedure where ty.id=ot.ot_type and ot.ot_type=$id and ip.id=ot.patient_id and DATE(ot.`date`) between '$from' and '$to')order by date ";
        //   echo $sql;exit;
            $res=mysqli_query($con,$sql);
            return $res;
   }
         function otDeptReports($from,$to,$id,$ot_type)
   {
            $newlogin = new userlogin;  
            if(strlen($id) > 0){
            $dept= "and ip.dept_id=".$id;
            }
            else{
                $dept='';
            }
            if(strlen($ot_type) > 0){
            $ot_type= "and ty.id=".$ot_type;
            }
            else{
                $ot_type='';
            }
            $con=$newlogin->dbconnect();
            $sql="(SELECT ip.opd_no,ipd_no,name,gender,age,diagnosis,op.ot_procedure,ty.ot_name,ot.date,ot.comment,ot.doctor,d.dept_name,ot.created_by FROM `central_ipd` ip,ot_type ty,department d,operation_theater ot LEFT JOIN ot_procedures op on op.id=ot.ot_procedure where ty.id=ot.ot_type $dept  and d.id=ip.dept_id and ip.ipd_no=ot.patient_id and date(ot.`date`) between '$from' and '$to' $ot_type)
            union (SELECT ip.opd_no,'' as ipd_no,name,gender,age,diagnosis,op.ot_procedure,ty.ot_name,ot.date,ot.comment,ot.doctor,d.dept_name,ot.created_by FROM `central_opd` ip,ot_type ty,department d,operation_theater ot LEFT JOIN ot_procedures op on op.id=ot.ot_procedure where ty.id=ot.ot_type $dept  and d.id=ip.dept_id and ip.id=ot.patient_id and date(ot.`date`) between '$from' and '$to' $ot_type)";
        //   echo $sql;
            $res=mysqli_query($con,$sql);
            return $res;
   }
   function getDept()
   {
       $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT * FROM `department` WHERE `status`='active' ";
    //   echo $sql;
        $res=mysqli_query($con,$sql);
        return $res;
   }
   function getProcedureReport()
   {
       $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT count(*) as val,p.procedure_name FROM `procedure` p ,`op_treatment` ot WHERE p.id=ot.procedure group by p.id";
    //   echo $sql;
        $res=mysqli_query($con,$sql);
        return $res;
   }
   function getIPProcedureReport()
   {
       $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT count(*) as val,p.procedure_name FROM `procedure` p ,`therapies` ot WHERE p.id=ot.procedure group by p.id";
    //   echo $sql;
        $res=mysqli_query($con,$sql);
        return $res;
   }
   function getProcedureWise()
   {
       $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT p.procedure_name,pm.medicine,ot.date,op.name,op.opd_no,op.age,op.gender,op.diagnosis,op.address,op.symptoms FROM `procedure` p ,`central_opd` op,`op_treatment` ot left join procedure_medicine pm on ot.medicine=pm.id WHERE p.id=ot.procedure and ot.patient_id=op.id";
    //   echo $sql;
        $res=mysqli_query($con,$sql);
        return $res;
   }
   function getIPProcedureWise()
   {
       $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT p.procedure_name,pm.medicine,ot.date,op.name,op.opd_no,op.ipd_no,op.age,op.gender,op.diagnosis,op.address FROM `procedure` p ,`central_ipd` op,`therapies` ot left join procedure_medicine pm on ot.medicine=pm.id WHERE p.id=ot.procedure and ot.patient_id=op.ipd_no";
    //   echo $sql;
        $res=mysqli_query($con,$sql);
        return $res;
   }
    function getGender_IP($from,$to)
   {
       $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT sum(case when gender='female' then 1 else 0 end) as female,sum(case when gender='male' then 1 else 0 end) as male,dept_name FROM `central_ipd` ip,department d where d.id=ip.dept_id and date(ip.admission_date) between '$from' and '$to 23:59:59' group by dept_id";
      
        $res=mysqli_query($con,$sql);
        return $res;
   }
   function getGender_IPDept($from,$to,$id)
   {
       $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT sum(case when gender='female' then 1 else 0 end) as female,sum(case when gender='male' then 1 else 0 end) as male,dept_name FROM `central_ipd` ip,department d where d.id=ip.dept_id and date(ip.admission_date) between '$from' and '$to 23:59:59' and d.id='$id' group by dept_id";
      
        $res=mysqli_query($con,$sql);
        return $res;
   }
   function getAdmission($date)
   {
            $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT c.*,d.dept_name,di.discharge_date,b.bed_number FROM `department` d,`bed` b,`central_ipd` c LEFT JOIN `discharge` di ON di.patient_id=c.ipd_no WHERE d.id=c.dept_id and b.id=c.bed_id and DATE(c.`admission_date`) = '$date' order by c.admission_date";
          // echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
   }
    function getDischarge($date)
   {
            $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT c.*,d.dept_name,di.discharge_date,b.bed_number FROM `department` d,`bed` b,`central_ipd` c , `discharge` di WHERE d.id=c.dept_id and b.id=c.bed_id and DATE(di.`discharge_date`) = '$date' and di.patient_id=c.ipd_no  order by c.admission_date";
          // echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
   }
   function getBedOccupancy($date)
   {
            $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="(SELECT c.id,c.admission_date,c.ipd_no,c.name,c.gender,c.age,c.address,d.dept_name,dis.discharge_date,b.bed_number from department d,bed b,central_ipd c left JOIN discharge dis on c.ipd_no=dis.patient_id WHERE b.id=c.bed_id  and d.id=c.dept_id and c.discharge=0 and DATE(c.admission_date)<='$date') 
          UNION (SELECT c.id,c.admission_date,c.ipd_no,c.name,c.gender,c.age,c.address,d.dept_name,dis.discharge_date,b.bed_number from department d,bed b,central_ipd c, discharge dis WHERE b.id=c.bed_id and d.id=c.dept_id and date(c.admission_date) <= '$date' AND c.ipd_no=dis.patient_id  and DATE(dis.discharge_date) > '$date') order by admission_date,id ";
          // echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
   }
   function getBedOccupancyGen($date,$gen,$dept)
   {
            $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
           $cond='';
          if($dept>0)
          {
              $cond=" and d.id='$dept' and b.ward!='ICU'";
          }
          else if($dept=="ICU"){
              $cond=" and b.ward='ICU'";
          }
       
          $sql="(SELECT c.id,c.admission_date,c.ipd_no,c.name,c.gender,c.age,c.address,d.dept_name,dis.discharge_date,b.bed_number,c.is_generated,b.ward,c.patient_type from department d,bed b,central_ipd c left JOIN discharge dis on c.ipd_no=dis.patient_id WHERE b.id=c.bed_id and b.ward not like '%ICU%' and d.id=c.dept_id and c.discharge=0 and DATE(c.admission_date)<='$date'and c.is_generated like '$gen%' $cond) UNION (SELECT c.id,c.admission_date,c.ipd_no,c.name,c.gender,c.age,c.address,d.dept_name,dis.discharge_date,b.bed_number,c.is_generated,b.ward,c.patient_type  from department d,bed b,central_ipd c, discharge dis WHERE b.id=c.bed_id and d.id=c.dept_id and b.ward not like '%ICU%' and date(c.admission_date) <= '$date' AND c.ipd_no=dis.patient_id and DATE(dis.discharge_date) > '$date' and c.is_generated like '$gen%' $cond)
          UNION (SELECT c.id,c.admission_date,c.ipd_no,c.name,c.gender,c.age,c.address,d.dept_name,dis.discharge_date,b.bed_number,c.is_generated,b.ward,c.patient_type from department d,bed b,central_ipd c left JOIN discharge dis on c.ipd_no=dis.patient_id WHERE b.id=c.bed_id and b.ward like '%ICU%' and d.id=c.dept_id and c.discharge=0 and DATE(c.admission_date)<='$date'and c.is_generated like '$gen%' $cond) UNION (SELECT c.id,c.admission_date,c.ipd_no,c.name,c.gender,c.age,c.address,d.dept_name,dis.discharge_date,b.bed_number,c.is_generated,b.ward,c.patient_type  from department d,bed b,central_ipd c, discharge dis WHERE b.id=c.bed_id and d.id=c.dept_id and b.ward like '%ICU%' and date(c.admission_date) <= '$date' AND c.ipd_no=dis.patient_id and DATE(dis.discharge_date) > '$date' and c.is_generated like '$gen%' $cond)order by admission_date,id ";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
   }
  
   function getBedOcc($date)
   {
            $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
           
          $sql="select dept_name,sum(occupied) as occupied,unit,ward,bed_count from ((SELECT d.dept_name,sum(CASE WHEN b.status = 'occupied' THEN 1 END) as occupied, (case when d.unit=3 then 'UNIT 1 & UNIT 2 & UNIT 3' when d.unit=2 then 'UNIT 1 &UNIT 2' else 'UNIT 1' end)unit, c.ward,(SELECT count(id) from bed where ward=c.ward and department=c.dept_id) as bed_count FROM central_ipd c,doctor u,bed b,department d where c.dept_id=d.id and CONCAT(u.name, ' ', u.last_name) = c.doctor and b.id = c.bed_id and c.discharge=0  and DATE(c.admission_date)<='$date' GROUP BY  dept_name,c.ward) UNION (SELECT d.dept_name,sum(CASE WHEN b.id > 0 THEN 1 END) as occupied, (case when d.unit=3 then 'UNIT 1 & UNIT 2 & UNIT 3' when d.unit=2 then 'UNIT 1 &UNIT 2' else 'UNIT 1' end)unit, c.ward,(SELECT count(id) from bed where ward=c.ward and department=c.dept_id) as bed_count FROM central_ipd c,doctor u,bed b,department d, discharge dis where c.dept_id=d.id and CONCAT(u.name, ' ', u.last_name) = c.doctor AND c.ipd_no=dis.patient_id  and b.id = c.bed_id  and DATE(c.admission_date)<='$date' and  DATE(dis.discharge_date) > '$date' GROUP BY  dept_name,c.ward)) as subquery group by dept_name,ward ORDER by dept_name,ward";
          $res=mysqli_query($con,$sql);
          return $res;
   }
   
   function getBedOccDetails($date)
   {
            $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
           
          $sql="(SELECT d.dept_name,concat(c.ward,' - ',u.unit) as ward,c.opd_no,c.ipd_no,c.name,c.age,c.gender,c.ward as cward,u.unit,d.id FROM `central_ipd` c,doctor u,department d where d.id=c.dept_id and concat(u.name,' ',u.last_name)=c.doctor and discharge=0 and  DATE(c.admission_date)<='$date') UNION (SELECT d.dept_name,concat(c.ward,' - ',u.unit) as ward,c.opd_no,c.ipd_no,c.name,c.age,c.gender,c.ward as cward,u.unit,d.id FROM `central_ipd` c,doctor u,department d,discharge dis where d.id=c.dept_id and concat(u.name,' ',u.last_name)=c.doctor and DATE(c.admission_date)<='$date' AND c.ipd_no=dis.patient_id and  DATE(dis.discharge_date) > '$date')  order by cward,unit,id";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
   }
   function getBedOccupancy1($date)
   {
            $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="select sum(male) as male ,sum(female) as female, dept_name from ((SELECT sum(case when c.gender='Male' then 1 else 0 end) as male,SUM(case when c.gender='Female' then 1 else 0 end) as female,d.dept_name from department d,central_ipd c WHERE d.id=c.dept_id and c.discharge=0 and DATE(c.admission_date)<='$date' group by dept_name) UNION (SELECT sum(case when c.gender='Male' then 1 else 0 end) as male,sum(case when c.gender='Female' then 1 else 0 end) as female,d.dept_name from department d,central_ipd c, discharge dis WHERE d.id=c.dept_id and date(c.admission_date) <= '$date' AND c.ipd_no=dis.patient_id and DATE(dis.discharge_date) > '$date' group by dept_name)) combine group by dept_name;";
          // echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
   }
     function getIPDetailsReport($id)
   {
       $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT c.*,d.dept_name,di.discharge_date,b.bed_number,b.ward FROM `department` d,`bed` b,`central_ipd` c LEFT JOIN `discharge` di ON di.patient_id=c.ipd_no WHERE d.id=c.dept_id and b.id=c.bed_id and c.`ipd_no` = '$id'";
    //   echo $sql;exit;
        $res=mysqli_query($con,$sql);
        return $res;
   }
   function getIPHistoryReport($id)
   {
       $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT * FROM `patient_history` WHERE patient_id='$id'";
    //   echo $sql;
        $res=mysqli_query($con,$sql);
        return $res;
   }
   function getIPInvestigationReport($id)
   {
       $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT p.*,r.roga_name FROM `patient_examintaion` p LEFT JOIN roga r ON r.id=p.roga_type WHERE patient_id='$id'";
    //   echo $sql;
        $res=mysqli_query($con,$sql);
        return $res;
   }
    function getIPlabReport($id)
   {
            $newlogin = new userlogin;                  
            $con=$newlogin->dbconnect();
            $sql="SELECT ltg.test_name,lr.date,lr.test_result FROM `lab_report` lr,`labtest_group` ltg where lr.`lab_result`=ltg.`testid` lr.patient_id='$id'  ";
        //   echo $sql;
            $res=mysqli_query($con,$sql);
            return $res;
   }
      function getIPTreatReport($id)
   {
            $newlogin = new userlogin;                  
            $con=$newlogin->dbconnect();
            $sql="SELECT * FROM `patient_investigation` WHERE patient_id='$id'";
        //   echo $sql;
            $res=mysqli_query($con,$sql);
            return $res;
   }
   function getIPTherapies($id)
   {
       $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT ot.*,p.procedure_name,sp.subprocedure_name,m.medicine as proce_medic from `procedure` p,`procedure_medicine` m,`therapies` ot left join sub_procedure sp on ot.sub_procedure=sp.id WHERE `patient_id`='$id' and p.id=ot.procedure and m.id=ot.medicine";
    //   echo $sql;
        $res=mysqli_query($con,$sql);
        return $res;
   }
      function getPatienMedic($id)
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT *,m.`name` from `ip_medicine` im,`medicine` m where m.id=im.`medicine` and `patient_id`='$id'";
        $res=mysqli_query($con,$sql);
        return $res;
    }  
     function getDaily($id)
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT * FROM `daily_entry` WHERE `patient_id`='$id'";   
        // echo $sql;
        $res=mysqli_query($con,$sql);
        return $res;
    }
    function getOTReg($id)
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT ot.patient_id,ot.date,ot.comment,d.name,d.last_name,o.ot_name,op.ot_procedure FROM doctor d,ot_type o,`operation_theater` ot left join ot_procedures op on  op.id=ot.ot_procedure WHERE ot.`patient_id`='$id' and d.id=ot.doctor and o.id=ot.ot_type";
        // echo $sql;exit;
        $res=mysqli_query($con,$sql);
        return $res;
    }
    
    function getOPD($id)
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT * FROM `central_opd` WHERE `id`='$id'";   
        // echo $sql;
        $res=mysqli_query($con,$sql);
        return $res;
    }
    
      function getOPDetailsForSlip($id)
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT c.*,dt.`name` as fname,dt.`last_name`,d.`dept_name` FROM `department`d,`central_opd`c left join `doctor`dt on c.doctor=dt.id WHERE c.`id`=$id and c.dept_id=d.id ";   
        // echo $sql;
        $res=mysqli_query($con,$sql);
        return $res;
    }



    function getIPPatient($id)
    {
     
         $newlogin = new userlogin;                  
           $con=$newlogin->dbconnect();
           $sql="SELECT c.*,d.dept_name,di.discharge_date FROM `department` d,`central_ipd` c LEFT JOIN `discharge` di ON di.patient_id=c.ipd_no WHERE d.id=c.dept_id  and c.ipd_no='$id'";
           // echo $sql;
           $res=mysqli_query($con,$sql);
           return $res;
     
    }

    function getIPAdvance($id)
    {
     
         $newlogin = new userlogin;                  
           $con=$newlogin->dbconnect();
           $sql=" SELECT ip_no,date,amount  FROM `ip_advance_amount`    where ip_no='$id'    ";
           // echo $sql;
           $res=mysqli_query($con,$sql);
           return $res;
     
    }
    // SELECT ip_no,date,amount   FROM `ip_advance_amount`   where ip_no='IP2200014'
     function getam($from,$to,$type)
    {
     if($type=='adult')
     {
         $cond= "and age between 18 and 60"; 
     }
     elseif($type=='minor')
     {
         $cond="and age <18";
     }
     elseif($type=='senior')
     {
         $cond="and age >60";
     }
         $newlogin = new userlogin;                  
           $con=$newlogin->dbconnect();
           $sql="SELECT gender,(CASE WHEN old_patient=0 then 'New' ELSE 'Re-visit' end)as type,COUNT(id) as total FROM `central_opd` WHERE date between '$from' and '$to' $cond GROUP by gender,old_patient order by gender";
            // echo "$sql<br>";
           $res=mysqli_query($con,$sql);
           return $res;
     
    }
    
    function getsr($from,$to)
    {
        $newlogin = new userlogin;                  
           $con=$newlogin->dbconnect();
           $sql="SELECT d.dept_name,u.unit,sum(CASE WHEN old_patient=0 then 1 ELSE 0 end)as new,sum(CASE WHEN old_patient=1 then 1 ELSE 0 end)as old,COUNT(c.id) as total FROM `central_opd` c,`doctor` u,department d WHERE d.id=c.dept_id and c.doctor=u.id and c.date between '$from' and '$to' GROUP by c.dept_id,u.unit ORDER BY d.id,unit";
            // echo "$sql<br>";
           $res=mysqli_query($con,$sql);
           return $res;
    }
    function overalldetails($from)
    {
        $newlogin=new userlogin;
        $con=$newlogin->dbconnect();
        $sql="SELECT (SELECT count(id) from central_opd where date = '$from') as opd,(SELECT count(id) from central_opd where date = '$from' and dept_id=17) as casuality,(SELECT count(id) from central_ipd where DATE(admission_date) ='$from' ) as admission,(SELECT count(id) from discharge where DATE(discharge_date) = '$from') as discharge,((SELECT count(c.id) from central_ipd c left JOIN discharge dis on c.ipd_no=dis.patient_id WHERE c.discharge=0 and date(c.admission_date)<='$from'  ) + (SELECT count(c.id) from central_ipd c, discharge dis WHERE date(c.admission_date) <= '$from' AND c.ipd_no=dis.patient_id and DATE(dis.discharge_date) > '$from' )) as occupied,(SELECT count(id) from bed) as bed";
        
        $res=mysqli_query($con,$sql);
           return $res;
    }
    function radioReport($from,$to)
   {
            $newlogin = new userlogin;                  
            $con=$newlogin->dbconnect();
            $sql="SELECT lt.testid,(SUM(case when patient_type='OP' then 1 else 0 END)) as OP ,(sum(case when patient_type='IP' then 1 else 0 END )) as IP,lt.testname FROM `lab_report` lr,`labtest_group` ltg,lab_test lt where lr.`lab_result`=ltg.`testid` and lt.testid=ltg.labid and lr.date between '$from' and '$to' and labid in (8,9,10,11) GROUP by lt.testid  ";
        //   echo $sql;
            $res=mysqli_query($con,$sql);
            return $res;
   }
   function getDoctor()
   {
       $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT * from doctor where role='Doctor' and status='active'";
        $res=mysqli_query($con,$sql);
        return $res;
   }
    function getDoctorDetails($from,$to,$doctor,$type)
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        
        if($type=='OPD')
        {
            $sql="SELECT opd_no,uhid,date,c.name,c.age,c.gender,c.address,dept_name,concat(u.name,' ',u.last_name) AS doctor from central_opd c,department d,doctor u where d.id=c.dept_id and u.id=c.doctor and c.doctor='$doctor' and date between '$from' and '$to'";
        }
        else{
            $sql="SELECT opd_no,ipd_no as uhid,admission_date as date,c.name,c.age,c.gender,c.address,dept_name,c.doctor from central_ipd c,department d,doctor u where d.id=c.dept_id and c.doctor=concat(u.name,' ',u.last_name) and u.id='$doctor' and DATE(admission_date) between '$from' and '$to'";
        }
        // echo $sql;
        $res=mysqli_query($con,$sql);
        return $res;
        
    }
     function getIPDepartment()
   {
       $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
            $sql="SELECT d.dept_name,bed,id FROM `department` d WHERE d.`status`='active' and d.dept_type='BOTH' ORDER BY d.id";
    //   echo $sql;
        $res=mysqli_query($con,$sql);
        return $res;
   }
   function getDailyIPRegistrySummary($from, $to)
   {
       $newlogin = new userlogin;
       $con = $newlogin->dbconnect();
       $from = mysqli_real_escape_string($con, $from);
       $to = mysqli_real_escape_string($con, $to);
       $fromDateTime = $from . ' 00:00:00';
       $toDateTime = $to . ' 23:59:59';

       $sql = "SELECT report_date, dept_id, SUM(admission_count) AS admission_count, SUM(discharge_count) AS discharge_count
               FROM (
                   SELECT DATE(admission_date) AS report_date, dept_id, COUNT(*) AS admission_count, 0 AS discharge_count
                   FROM central_ipd
                   WHERE admission_date BETWEEN '$fromDateTime' AND '$toDateTime' and ward not like '%ICU%'
                   GROUP BY DATE(admission_date), dept_id

                   UNION ALL

                   SELECT DATE(discharge_date) AS report_date, dept_id, 0 AS admission_count, COUNT(*) AS discharge_count
                   FROM discharge
                   WHERE discharge_date BETWEEN '$fromDateTime' AND '$toDateTime' and ward not like '%ICU%'
                   GROUP BY DATE(discharge_date), dept_id

                   UNION ALL

                     SELECT DATE(admission_date) AS report_date, dept_id, COUNT(*) AS admission_count, 0 AS discharge_count
                   FROM central_ipd
                   WHERE admission_date BETWEEN '$fromDateTime' AND '$toDateTime' and ward like '%ICU%'
                   GROUP BY DATE(admission_date), dept_id

                   UNION ALL

                   SELECT DATE(discharge_date) AS report_date, dept_id, 0 AS admission_count, COUNT(*) AS discharge_count
                   FROM discharge
                   WHERE discharge_date BETWEEN '$fromDateTime' AND '$toDateTime' and ward like '%ICU%'
                   GROUP BY DATE(discharge_date), dept_id
               ) AS daily_summary
               GROUP BY report_date, dept_id
               ORDER BY report_date, dept_id";

       $res = mysqli_query($con, $sql);
       return $res;
   }
   function getDailyIPRegistrySummarySeparatedIcu($from, $to)
   {
       $newlogin = new userlogin;
       $con = $newlogin->dbconnect();
       $from = mysqli_real_escape_string($con, $from);
       $to = mysqli_real_escape_string($con, $to);
       $fromDateTime = $from . ' 00:00:00';
       $toDateTime = $to . ' 23:59:59';

       $sql = "SELECT report_date, dept_id, is_icu, SUM(admission_count) AS admission_count, SUM(discharge_count) AS discharge_count
               FROM (
                   SELECT DATE(admission_date) AS report_date,
                          CASE WHEN ward LIKE '%ICU%' THEN 0 ELSE dept_id END AS dept_id,
                          CASE WHEN ward LIKE '%ICU%' THEN 1 ELSE 0 END AS is_icu,
                          COUNT(*) AS admission_count,
                          0 AS discharge_count
                   FROM central_ipd
                   WHERE admission_date BETWEEN '$fromDateTime' AND '$toDateTime'
                   GROUP BY DATE(admission_date), CASE WHEN ward LIKE '%ICU%' THEN 0 ELSE dept_id END, CASE WHEN ward LIKE '%ICU%' THEN 1 ELSE 0 END

                   UNION ALL

                   SELECT DATE(dis.discharge_date) AS report_date,
                          CASE WHEN ip.ward LIKE '%ICU%' THEN 0 ELSE dis.dept_id END AS dept_id,
                          CASE WHEN ip.ward LIKE '%ICU%' THEN 1 ELSE 0 END AS is_icu,
                          0 AS admission_count,
                          COUNT(*) AS discharge_count
                   FROM discharge dis
                   INNER JOIN central_ipd ip ON ip.ipd_no = dis.patient_id
                   WHERE dis.discharge_date BETWEEN '$fromDateTime' AND '$toDateTime'
                   GROUP BY DATE(dis.discharge_date), CASE WHEN ip.ward LIKE '%ICU%' THEN 0 ELSE dis.dept_id END, CASE WHEN ip.ward LIKE '%ICU%' THEN 1 ELSE 0 END
               ) AS daily_summary
               GROUP BY report_date, dept_id, is_icu
               ORDER BY report_date, is_icu, dept_id";

       $res = mysqli_query($con, $sql);
       return $res;
   }
   function getDeptIPReport($i,$year)
    {
        $y=$year;
        $dept_from=date("Y-m-01",mktime(0,0,0,$i+1,0,$y));
        $dept_to=date("Y-m-t",mktime(0,0,0,$i+1,0,$y));
 
         $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $dept=array();
              $data=$res=array();
             
                $dates = $this->getBetweenDates($dept_from, $dept_to);
                // print_r($dates);exit;
                $sn=0;
                for($i=0;$i<count($dates);$i++)
                {
                      
                    $query="(SELECT d.id,(SELECT count(dis.id) FROM `discharge` dis,central_ipd i where i.ipd_no=dis.patient_id and i.ward not like '%ICU%' and date(dis.discharge_date) >= '$dates[$i]' and date(dis.admission_date) < '$dates[$i]' and dis.dept_id=d.id)+ (select count(i.id) from central_ipd i where i.discharge=0 and date(i.admission_date)<'$dates[$i]' and i.ward not like '%ICU%' and i.dept_id=d.id) as occ from department d WHERE d.dept_type='both'  and d.`status`='active' group by d.id)
                    UNION  (SELECT '0' as id,(SELECT count(dis.id) FROM `discharge` dis,central_ipd i where i.ipd_no=dis.patient_id and i.ward like '%ICU%' and date(dis.discharge_date) >= '$dates[$i]' and date(dis.admission_date) < '$dates[$i]' and dis.dept_id=d.id)+ (select count(i.id) from central_ipd i where i.discharge=0 and date(i.admission_date)<'$dates[$i]' and i.dept_id=d.id and i.ward like '%ICU%') as occ from department d WHERE d.dept_type='both' and d.`status`='active' )order by id";
                    // echo $query;exit;
            $res2=mysqli_query($con,$query);
                    while($row1=mysqli_fetch_assoc($res2))
                    {
                        // echo "$row1[occ]<br>";
                        $dept[$row1['id']] +=$row1['occ'];
                        $n=1;
                    }
                    $sn +=$n;
                }
          $sql="(SELECT d.id,d.`dept_name`,(case when d.id=3 then $dept[3] when d.id=4 then $dept[4] when d.id=6 then $dept[6] when d.id=7 then $dept[7] when d.id=2 then $dept[2] when d.id=1 then $dept[1] when d.id=11 then $dept[11] when d.id=12 then $dept[12]  when d.id=18 then $dept[18] else 0 end) as bed, (select count(ci.bed_id) FROM `central_ipd` ci where d.id=ci.dept_id and date(ci.`admission_date`) between '$dept_from' and '$dept_to' and ci.ward not like '%ICU%') as occupied, (SELECT count(dis.id) FROM `discharge` dis,central_ipd i where i.ipd_no=dis.patient_id and i.ward not like '%ICU%' and date(dis.discharge_date) between '$dept_from' and '$dept_to' and dis.dept_id=d.id) as discharge,'$sn' as daycount from department d WHERE d.dept_type='both' and d.status='active' group by dept_name)
          UNION (SELECT '0' as id,'ICU' as dept_name,(case when $dept[0]>0 then $dept[0] else 0 end) as bed, (select count(ci.bed_id) FROM `central_ipd` ci where d.id=ci.dept_id and date(ci.`admission_date`) between '$dept_from' and '$dept_to' and ci.ward  like '%ICU%') as occupied, (SELECT count(dis.id) FROM `discharge` dis,central_ipd i where i.ipd_no=dis.patient_id and i.ward like '%ICU%' and date(dis.discharge_date) between '$dept_from' and '$dept_to' and dis.dept_id=d.id) as discharge,'$sn' as daycount from department d group by 1 ) order by CAST(id AS UNSIGNED)";
                // echo $sql;
            //   exit;
          $res=mysqli_query($con,$sql);
          return $res;
     
    }
    function getDeptIPMonthlyReport($i,$year)
    {
        $y=$year;
        $dept_from=date("Y-m-01",mktime(0,0,0,$i+1,0,$y));
        $dept_to=date("Y-m-t",mktime(0,0,0,$i+1,0,$y));
 
         $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $dept=array();
              $data=$res=array();
             
                $dates = $this->getBetweenDates($dept_from, $dept_to);
                // print_r($dates);exit;
                $sn=0;
         
          $sql="(SELECT d.id,d.`dept_name`, (select count(ci.bed_id) FROM `central_ipd` ci where d.id=ci.dept_id and date(ci.`admission_date`) between '$dept_from' and '$dept_to' and ci.ward not like '%ICU%') as occupied, (SELECT count(dis.id) FROM `discharge` dis,central_ipd i where i.ipd_no=dis.patient_id and i.ward not like '%ICU%' and date(dis.discharge_date) between '$dept_from' and '$dept_to' and dis.dept_id=d.id) as discharge,'$sn' as daycount from department d WHERE d.dept_type='both' and d.status='active' group by dept_name)
          UNION (SELECT '0' as id,'ICU' as dept_name, (select count(ci.bed_id) FROM `central_ipd` ci where d.id=ci.dept_id and date(ci.`admission_date`) between '$dept_from' and '$dept_to' and ci.ward  like '%ICU%') as occupied, (SELECT count(dis.id) FROM `discharge` dis,central_ipd i where i.ipd_no=dis.patient_id and i.ward like '%ICU%' and date(dis.discharge_date) between '$dept_from' and '$dept_to' and dis.dept_id=d.id) as discharge,'$sn' as daycount from department d group by 1 ) order by CAST(id AS UNSIGNED)";
                // echo $sql;
            //   exit;
          $res=mysqli_query($con,$sql);
          return $res;
     
    }
        function getDepartment()
   {
       $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT d.id, d.dept_name FROM `department` d  WHERE d.`status`='active' and d.id !=9 order by d.id";
    //   echo $sql;
        $res=mysqli_query($con,$sql);
        return $res;
   }

    function getDeptDailyGroupedReport($date)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "SELECT dept_id,
                       SUM(CASE WHEN old_patient = 0 THEN 1 ELSE 0 END) AS new_count,
                       SUM(CASE WHEN old_patient = 1 THEN 1 ELSE 0 END) AS old_count,
                       COUNT(*) AS total_count
                FROM central_opd
                WHERE date = '$date'
                GROUP BY dept_id";
        $res = mysqli_query($con, $sql);
        return $res;
    }
      function getDialyIPReport($date)
   {
            $newlogin = new userlogin;                  
            $con=$newlogin->dbconnect();
           
            $sql="SELECT d.`dept_name`, (SELECT count(dis.id) FROM `discharge` dis where date(dis.discharge_date) >= '$date' and date(dis.admission_date) < '$date' and dis.dept_id=d.id)+ (select count(i.id) from central_ipd i where i.discharge=0 and date(i.admission_date)<'$date' and i.dept_id=d.id) as bed,(select count(ci.bed_id) FROM `central_ipd` ci where d.id=ci.dept_id and date(ci.`admission_date`) between '$date' and '$date') as occupied, (SELECT count(dis.id) FROM `discharge` dis where date(dis.discharge_date) between '$date' and '$date' and dis.dept_id=d.id) as discharge from department d WHERE d.status='active' and d.dept_type='both' group by dept_name order by d.id";
                    //   echo $sql;
            $res=mysqli_query($con,$sql);
            return $res;
   }
   function getDeptReport($i,$year)
    {
        $y=$year;
        $to=date("Y-m-",mktime(0,0,0,$i+1,0,$y));
 
         $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT sum(case WHEN gender='male' and old_patient=1 and dept_id=3 then 1 else 0 end ) as ps_male_new,sum(case WHEN gender='female' and old_patient=1 and dept_id=3 then 1 else 0 end ) as ps_female_new,sum(case WHEN gender='male' and old_patient=0 and dept_id=3 then 1 else 0 end ) as ps_male_old,sum(case WHEN gender='female' and old_patient=0 and dept_id=3 then 1 else 0 end ) as ps_female_old,
          sum(case WHEN gender='male' and old_patient=1 and dept_id='4' then 1 else 0 end ) as sk_male_new,sum(case WHEN gender='female' and old_patient=1 and dept_id=4 then 1 else 0 end ) as sk_female_new,sum(case WHEN gender='male' and old_patient=0 and dept_id=4 then 1 else 0 end ) as sk_male_old,sum(case WHEN gender='female' and old_patient=0 and dept_id=4 then 1 else 0 end ) as sk_female_old,
          sum(case WHEN gender='male' and old_patient=1 and dept_id='7' then 1 else 0 end ) as or_male_new,sum(case WHEN gender='female' and old_patient=1 and dept_id='7' then 1 else 0 end ) as or_female_new,sum(case WHEN gender='male' and old_patient=0 and dept_id='7' then 1 else 0 end ) as or_male_old,sum(case WHEN gender='female' and old_patient=0 and dept_id='7' then 1 else 0 end ) as or_female_old,
          sum(case WHEN gender='male' and old_patient=1 and dept_id='6' then 1 else 0 end ) as gs_male_new,sum(case WHEN gender='female' and old_patient=1 and dept_id='6' then 1 else 0 end ) as gs_female_new,sum(case WHEN gender='male' and old_patient=0 and dept_id='6' then 1 else 0 end ) as gs_male_old,sum(case WHEN gender='female' and old_patient=0 and dept_id='6' then 1 else 0 end ) as gs_female_old,
          sum(case WHEN gender='male' and old_patient=1 and dept_id='18'  then 1 else 0 end ) as en_male_new,sum(case WHEN gender='female' and old_patient=1 and dept_id='18'  then 1 else 0 end ) as en_female_new,sum(case WHEN gender='male' and old_patient=0 and dept_id='18' then 1 else 0 end ) as en_male_old,sum(case WHEN gender='female' and old_patient=0 and dept_id='18' then 1 else 0 end ) as en_female_old,
          sum(case WHEN gender='male' and old_patient=1 and dept_id='20' then 1 else 0 end ) as ph_male_new,sum(case WHEN gender='female' and old_patient=1 and dept_id='20' then 1 else 0 end ) as ph_female_new,sum(case WHEN gender='male' and old_patient=0 and dept_id='20' then 1 else 0 end ) as ph_male_old,sum(case WHEN gender='female' and old_patient=0 and dept_id='20' then 1 else 0 end ) as ph_female_old,
          sum(case WHEN gender='male' and old_patient=1 and dept_id='2' then 1 else 0 end ) as pd_male_new,sum(case WHEN gender='female' and old_patient=1 and dept_id='2' then 1 else 0 end ) as pd_female_new,sum(case WHEN gender='male' and old_patient=0 and dept_id='2' then 1 else 0 end ) as pd_male_old,sum(case WHEN gender='female' and old_patient=0 and dept_id='2' then 1 else 0 end ) as pd_female_old,
          sum(case WHEN gender='male' and old_patient=1 and dept_id='11' then 1 else 0 end ) as op_male_new,sum(case WHEN gender='female' and old_patient=1 and dept_id='11' then 1 else 0 end ) as op_female_new,sum(case WHEN gender='male' and old_patient=0 and dept_id='11' then 1 else 0 end ) as op_male_old,sum(case WHEN gender='female' and old_patient=0 and dept_id='11' then 1 else 0 end ) as op_female_old,
          sum(case WHEN gender='male' and old_patient=1 and dept_id='1' then 1 else 0 end ) as gm_male_new,sum(case WHEN gender='female' and old_patient=1 and dept_id='1' then 1 else 0 end ) as gm_female_new,sum(case WHEN gender='male' and old_patient=0 and dept_id='1' then 1 else 0 end ) as gm_male_old,sum(case WHEN gender='female' and old_patient=0 and dept_id='1' then 1 else 0 end ) as gm_female_old,
          sum(case WHEN gender='male' and old_patient=1 and dept_id='12' then 1 else 0 end ) as obg_male_new,sum(case WHEN gender='female' and old_patient=1 and dept_id='12' then 1 else 0 end ) as obg_female_new,sum(case WHEN gender='male' and old_patient=0 and dept_id='12' then 1 else 0 end ) as obg_male_old,sum(case WHEN gender='female' and old_patient=0 and dept_id='12' then 1 else 0 end ) as obg_female_old,
          sum(case WHEN gender='male' and old_patient=1 and dept_id='8' then 1 else 0 end ) as dn_male_new,sum(case WHEN gender='female' and old_patient=1 and dept_id='8' then 1 else 0 end ) as dn_female_new,sum(case WHEN gender='male' and old_patient=0 and dept_id='8' then 1 else 0 end ) as dn_male_old,sum(case WHEN gender='female' and old_patient=0 and dept_id='8' then 1 else 0 end ) as dn_female_old,
          sum(case WHEN gender='male' and old_patient=1 and dept_id='17' then 1 else 0 end ) as em_male_new,sum(case WHEN gender='female' and old_patient=1 and dept_id='17' then 1 else 0 end ) as em_female_new,sum(case WHEN gender='male' and old_patient=0 and dept_id='17' then 1 else 0 end ) as em_male_old,sum(case WHEN gender='female' and old_patient=0 and dept_id='17' then 1 else 0 end ) as em_female_old
          
          FROM central_opd where date like '$to%'" ;
        //   echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
     
    }
     function getDeptDailyReport($date)
    {

    $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT sum(case WHEN gender='male' and old_patient=1 and dept_id=3 then 1 else 0 end ) as ps_male_new,sum(case WHEN gender='female' and old_patient=1 and dept_id=3 then 1 else 0 end ) as ps_female_new,sum(case WHEN gender='male' and old_patient=0 and dept_id=3 then 1 else 0 end ) as ps_male_old,sum(case WHEN gender='female' and old_patient=0 and dept_id=3 then 1 else 0 end ) as ps_female_old,
          sum(case WHEN gender='male' and old_patient=1 and dept_id='4' then 1 else 0 end ) as sk_male_new,sum(case WHEN gender='female' and old_patient=1 and dept_id=4 then 1 else 0 end ) as sk_female_new,sum(case WHEN gender='male' and old_patient=0 and dept_id=4 then 1 else 0 end ) as sk_male_old,sum(case WHEN gender='female' and old_patient=0 and dept_id=4 then 1 else 0 end ) as sk_female_old,
          sum(case WHEN gender='male' and old_patient=1 and dept_id='7' then 1 else 0 end ) as or_male_new,sum(case WHEN gender='female' and old_patient=1 and dept_id='7' then 1 else 0 end ) as or_female_new,sum(case WHEN gender='male' and old_patient=0 and dept_id='7' then 1 else 0 end ) as or_male_old,sum(case WHEN gender='female' and old_patient=0 and dept_id='7' then 1 else 0 end ) as or_female_old,
          sum(case WHEN gender='male' and old_patient=1 and dept_id='6' then 1 else 0 end ) as gs_male_new,sum(case WHEN gender='female' and old_patient=1 and dept_id='6' then 1 else 0 end ) as gs_female_new,sum(case WHEN gender='male' and old_patient=0 and dept_id='6' then 1 else 0 end ) as gs_male_old,sum(case WHEN gender='female' and old_patient=0 and dept_id='6' then 1 else 0 end ) as gs_female_old,
          sum(case WHEN gender='male' and old_patient=1 and dept_id='18'  then 1 else 0 end ) as en_male_new,sum(case WHEN gender='female' and old_patient=1 and dept_id='18'  then 1 else 0 end ) as en_female_new,sum(case WHEN gender='male' and old_patient=0 and dept_id='18' then 1 else 0 end ) as en_male_old,sum(case WHEN gender='female' and old_patient=0 and dept_id='18' then 1 else 0 end ) as en_female_old,
          sum(case WHEN gender='male' and old_patient=1 and dept_id='5' then 1 else 0 end ) as tb_male_new,sum(case WHEN gender='female' and old_patient=1 and dept_id='5' then 1 else 0 end ) as tb_female_new,sum(case WHEN gender='male' and old_patient=0 and dept_id='5' then 1 else 0 end ) as tb_male_old,sum(case WHEN gender='female' and old_patient=0 and dept_id='5' then 1 else 0 end ) as tb_female_old,
          sum(case WHEN gender='male' and old_patient=1 and dept_id='2' then 1 else 0 end ) as pd_male_new,sum(case WHEN gender='female' and old_patient=1 and dept_id='2' then 1 else 0 end ) as pd_female_new,sum(case WHEN gender='male' and old_patient=0 and dept_id='2' then 1 else 0 end ) as pd_male_old,sum(case WHEN gender='female' and old_patient=0 and dept_id='2' then 1 else 0 end ) as pd_female_old,
          sum(case WHEN gender='male' and old_patient=1 and dept_id='11' then 1 else 0 end ) as op_male_new,sum(case WHEN gender='female' and old_patient=1 and dept_id='11' then 1 else 0 end ) as op_female_new,sum(case WHEN gender='male' and old_patient=0 and dept_id='11' then 1 else 0 end ) as op_male_old,sum(case WHEN gender='female' and old_patient=0 and dept_id='11' then 1 else 0 end ) as op_female_old,
          sum(case WHEN gender='male' and old_patient=1 and dept_id='1' then 1 else 0 end ) as gm_male_new,sum(case WHEN gender='female' and old_patient=1 and dept_id='1' then 1 else 0 end ) as gm_female_new,sum(case WHEN gender='male' and old_patient=0 and dept_id='1' then 1 else 0 end ) as gm_male_old,sum(case WHEN gender='female' and old_patient=0 and dept_id='1' then 1 else 0 end ) as gm_female_old,
          sum(case WHEN gender='male' and old_patient=1 and dept_id='12' then 1 else 0 end ) as obg_male_new,sum(case WHEN gender='female' and old_patient=1 and dept_id='12' then 1 else 0 end ) as obg_female_new,sum(case WHEN gender='male' and old_patient=0 and dept_id='12' then 1 else 0 end ) as obg_male_old,sum(case WHEN gender='female' and old_patient=0 and dept_id='12' then 1 else 0 end ) as obg_female_old,
          sum(case WHEN gender='male' and old_patient=1 and dept_id='8' then 1 else 0 end ) as dn_male_new,sum(case WHEN gender='female' and old_patient=1 and dept_id='8' then 1 else 0 end ) as dn_female_new,sum(case WHEN gender='male' and old_patient=0 and dept_id='8' then 1 else 0 end ) as dn_male_old,sum(case WHEN gender='female' and old_patient=0 and dept_id='8' then 1 else 0 end ) as dn_female_old,
          sum(case WHEN gender='male' and old_patient=1 and dept_id='20' then 1 else 0 end ) as ph_male_new,sum(case WHEN gender='female' and old_patient=1 and dept_id='20' then 1 else 0 end ) as ph_female_new,sum(case WHEN gender='male' and old_patient=0 and dept_id='20' then 1 else 0 end ) as ph_male_old,sum(case WHEN gender='female' and old_patient=0 and dept_id='20' then 1 else 0 end ) as ph_female_old,
          sum(case WHEN gender='male' and old_patient=1 and dept_id='17' then 1 else 0 end ) as em_male_new,sum(case WHEN gender='female' and old_patient=1 and dept_id='17' then 1 else 0 end ) as em_female_new,sum(case WHEN gender='male' and old_patient=0 and dept_id='17' then 1 else 0 end ) as em_male_old,sum(case WHEN gender='female' and old_patient=0 and dept_id='17' then 1 else 0 end ) as em_female_old
          
          FROM central_opd where date= '$date'" ;
        //   echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
     
    }
         function getDeptReportDetails($i,$y,$dept,$type)
    {
       
         $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
           $cond='';
          if($type=='new')
          {
            $cond="and op.old_patient=0";
          }
         else if($type=='old')
          {
            $cond="and op.old_patient=1";
          }
          $sql="select op.*,d.dept_name from central_opd op,department d where year(op.date)='$y' and month(op.date)='$i' and op.dept_id=$dept and op.dept_id=d.id $cond" ;
        //   echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
     
    }
    function getIPDetailsICU($from,$to,$bed,$type)
    {
        $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
         
         if($type=='admission')
          {
               if($bed=='17')
              {
                  $sql="SELECT c.*,d.dept_name,di.discharge_date,concat(u.name,' ',u.last_name) as doctor_name,u.unit,di.advice_discharge FROM `department` d,`central_ipd` c LEFT JOIN `discharge` di ON di.patient_id=c.ipd_no LEFT JOIN `doctor` u on concat(u.name,' ',u.last_name)=c.doctor WHERE d.id=c.dept_id and date(c.`admission_date`) between '$from' and '$to' and d.id='$bed'";
              }
              else{
                $sql="SELECT c.*,d.dept_name,di.discharge_date,concat(u.name,' ',u.last_name) as doctor_name,u.unit,di.advice_discharge FROM `department` d,`bed` b,`central_ipd` c LEFT JOIN `discharge` di ON di.patient_id=c.ipd_no LEFT JOIN `doctor` u on concat(u.name,' ',u.last_name)=c.doctor WHERE d.id=c.dept_id and c.bed_id=b.id and date(c.`admission_date`) between '$from' and '$to' and b.ward='$bed'";    
              }
              
          }
          elseif($type=='discharge')
          {
               if($bed=='17')
              {
                  $sql="SELECT c.*,d.dept_name,di.discharge_date,concat(u.name,' ',u.last_name) as doctor_name,u.unit,di.advice_discharge FROM `department` d, `discharge` di ,`central_ipd` c LEFT JOIN `doctor` u on concat(u.name,' ',u.last_name)=c.doctor WHERE d.id=c.dept_id and  di.patient_id=c.ipd_no and date(di.`discharge_date`) between '$from' and '$to' and d.id='$bed'";
              }
              else{
                $sql="SELECT c.*,d.dept_name,di.discharge_date,concat(u.name,' ',u.last_name) as doctor_name,u.unit,di.advice_discharge FROM `department` d,`bed` b, `discharge` di,`central_ipd` c LEFT JOIN `doctor` u on concat(u.name,' ',u.last_name)=c.doctor WHERE d.id=c.dept_id and c.bed_id=b.id and  di.patient_id=c.ipd_no and date(di.`discharge_date`) between '$from' and '$to' and b.ward='$bed'";    
              }
          }
          elseif($type='all')
          {
              if($bed=='17')
              {
                   $sql="(SELECT c.id,c.admission_date,c.ipd_no,c.name,c.gender,c.age,c.address,d.dept_name,dis.discharge_date,b.bed_number,d.unit,c.diagnosis,c.opd_no from department d,bed b,central_ipd c left JOIN discharge dis on c.ipd_no=dis.patient_id WHERE b.id=c.bed_id and d.id='17'  and d.id=c.dept_id and c.discharge=0  and DATE(c.admission_date)<='$from') UNION (SELECT c.id,c.admission_date,c.ipd_no,c.name,c.gender,c.age,c.address,d.dept_name,dis.discharge_date,b.bed_number,d.unit,c.diagnosis,c.opd_no from department d,bed b,central_ipd c, discharge dis WHERE b.id=c.bed_id and d.id=c.dept_id and date(c.admission_date) <= '$from' and d.id='17' AND c.ipd_no=dis.patient_id and DATE(dis.discharge_date) > '$from') order by id ";
              }
              else{
                  $sql="(SELECT c.id,c.admission_date,c.ipd_no,c.name,c.gender,c.age,c.address,d.dept_name,dis.discharge_date,b.bed_number,d.unit,c.diagnosis,c.opd_no from department d,bed b,central_ipd c left JOIN discharge dis on c.ipd_no=dis.patient_id WHERE b.id=c.bed_id and d.id!='17'  and d.id=c.dept_id and c.discharge=0 and b.ward='$bed' and DATE(c.admission_date)<='$from') UNION (SELECT c.id,c.admission_date,c.ipd_no,c.name,c.gender,c.age,c.address,d.dept_name,dis.discharge_date,b.bed_number,d.unit,c.diagnosis,c.opd_no from department d,bed b,central_ipd c, discharge dis WHERE b.id=c.bed_id and d.id=c.dept_id and date(c.admission_date) <= '$from' and b.ward='$bed' and d.id!='17' AND c.ipd_no=dis.patient_id and DATE(dis.discharge_date) > '$from') order by id ";
              }
          }
          
    //   echo $sql;
           $res=mysqli_query($con,$sql);
          return $res;
          
    }
    function getdischargeType($from,$to)
    {
        $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      $sql="Select count(discharge_condition) as total,discharge_condition from discharge where date(discharge_date) between '$from' and '$to' group by discharge_condition";
      $res=mysqli_query($con,$sql);
          return $res;
    }
    function nonteachingbed($from,$to)
    {
        $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
        $sql="(SELECT c.id,c.admission_date,c.ipd_no,c.name,c.gender,c.age,c.address,d.dept_name,dis.discharge_date,b.bed_number,d.unit,c.diagnosis,c.opd_no from department d,bed b,central_ipd c left JOIN discharge dis on c.ipd_no=dis.patient_id WHERE b.id=c.bed_id   and d.id=c.dept_id and c.discharge=0  and DATE(c.admission_date)<='$from') UNION (SELECT c.id,c.admission_date,c.ipd_no,c.name,c.gender,c.age,c.address,d.dept_name,dis.discharge_date,b.bed_number,d.unit,c.diagnosis,c.opd_no from department d,bed b,central_ipd c, discharge dis WHERE b.id=c.bed_id and d.id=c.dept_id and date(c.admission_date) <= '$from' and c.ipd_no=dis.patient_id  and DATE(dis.discharge_date) > '$from') order by id ";
        $res=mysqli_query($con,$sql);
          return $res;
    }
    
    function getMonthlyLab($year)
    {
          $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
        $sql="SELECT 
    lt.testname,
    COUNT(DISTINCT CASE WHEN MONTH(lr.date) = 1 THEN lr.patient_id END) AS jan_distinct,
    COUNT(CASE WHEN MONTH(lr.date) = 1 THEN lr.lab_result END) AS jan_lab_results,

    COUNT(DISTINCT CASE WHEN MONTH(lr.date) = 2 THEN lr.patient_id END) AS feb_distinct,
    COUNT(CASE WHEN MONTH(lr.date) = 2 THEN lr.lab_result END) AS feb_lab_results,

    COUNT(DISTINCT CASE WHEN MONTH(lr.date) = 3 THEN lr.patient_id END) AS mar_distinct,
    COUNT(CASE WHEN MONTH(lr.date) = 3 THEN lr.lab_result END) AS mar_lab_results,

    COUNT(DISTINCT CASE WHEN MONTH(lr.date) = 4 THEN lr.patient_id END) AS apr_distinct,
    COUNT(CASE WHEN MONTH(lr.date) = 4 THEN lr.lab_result END) AS apr_lab_results,

    COUNT(DISTINCT CASE WHEN MONTH(lr.date) = 5 THEN lr.patient_id END) AS may_distinct,
    COUNT(CASE WHEN MONTH(lr.date) = 5 THEN lr.lab_result END) AS may_lab_results,

    COUNT(DISTINCT CASE WHEN MONTH(lr.date) = 6 THEN lr.patient_id END) AS june_distinct,
    COUNT(CASE WHEN MONTH(lr.date) = 6 THEN lr.lab_result END) AS june_lab_results,

    COUNT(DISTINCT CASE WHEN MONTH(lr.date) = 7 THEN lr.patient_id END) AS july_distinct,
    COUNT(CASE WHEN MONTH(lr.date) = 7 THEN lr.lab_result END) AS july_lab_results,

    COUNT(DISTINCT CASE WHEN MONTH(lr.date) = 8 THEN lr.patient_id END) AS aug_distinct,
    COUNT(CASE WHEN MONTH(lr.date) = 8 THEN lr.lab_result END) AS aug_lab_results,

    COUNT(DISTINCT CASE WHEN MONTH(lr.date) = 9 THEN lr.patient_id END) AS sep_distinct,
    COUNT(CASE WHEN MONTH(lr.date) = 9 THEN lr.lab_result END) AS sep_lab_results,

    COUNT(DISTINCT CASE WHEN MONTH(lr.date) = 10 THEN lr.patient_id END) AS oct_distinct,
    COUNT(CASE WHEN MONTH(lr.date) = 10 THEN lr.lab_result END) AS oct_lab_results,

    COUNT(DISTINCT CASE WHEN MONTH(lr.date) = 11 THEN lr.patient_id END) AS nov_distinct,
    COUNT(CASE WHEN MONTH(lr.date) = 11 THEN lr.lab_result END) AS nov_lab_results,
 
    COUNT(DISTINCT CASE WHEN MONTH(lr.date) = 12 THEN lr.patient_id END) AS dec_distinct,
    COUNT(CASE WHEN MONTH(lr.date) = 12 THEN lr.lab_result END) AS dec_lab_results


    FROM 

    lab_report lr
INNER JOIN 
    labtest_group lg ON lg.testid = lr.lab_result
INNER JOIN 
    lab_test lt ON lg.labid = lt.testid
WHERE 
    YEAR(lr.date) = '$year'
GROUP BY 
    lt.testname";
    // echo $sql;
    // exit;
        $res=mysqli_query($con,$sql);
          return $res;
    }
    
 function radioGenReport($from,$to,$gen,$type)
   {
            $newlogin = new userlogin;                  
            $con=$newlogin->dbconnect();
            if($gen=='1')
            {
                $cond=" and is_generated=1";
            }
            elseif($gen=='0'){
                $cond=" and is_generated=0";
            }
            else{
                $cond='';
            }
            if($type=='OP')
            {
                $sql="SELECT lt.testid,c.opd_no,'' as ipd_no,c.name,c.age,c.gender,c.address,lt.testname,lr.date,c.is_generated,lr.id FROM `lab_report` lr,`labtest_group` ltg,lab_test lt,central_opd c where c.id=lr.patient_id and lr.patient_type='op' and lr.`lab_result`=ltg.`testid` and lt.testid=ltg.labid and lr.date between '$from' and '$to' $cond and labid in (8,9,10,11)   order by lr.id";
            }
            elseif($type=='IP'){
                $sql="SELECT lt.testid,c.opd_no,c.ipd_no,c.name,c.age,c.gender,c.address,lt.testname,lr.date,c.is_generated,lr.id FROM `lab_report` lr,`labtest_group` ltg,lab_test lt,central_ipd c where c.ipd_no=lr.patient_id and lr.patient_type='ip' and lr.`lab_result`=ltg.`testid` and lt.testid=ltg.labid and lr.date between '$from' and '$to' $cond and labid in (8,9,10,11)   order by lr.id";
            }
            else{
                $sql="(SELECT lt.testid,c.opd_no,'' as ipd_no,c.name,c.age,c.gender,c.address,lt.testname,lr.date,c.is_generated,lr.id FROM `lab_report` lr,`labtest_group` ltg,lab_test lt,central_opd c where c.id=lr.patient_id and lr.patient_type='op' and lr.`lab_result`=ltg.`testid` and lt.testid=ltg.labid and lr.date between '$from' and '$to' $cond and labid in (8,9,10,11)) UNION (SELECT lt.testid,c.opd_no,c.ipd_no,c.name,c.age,c.gender,c.address,lt.testname,lr.date,c.is_generated,lr.id FROM `lab_report` lr,`labtest_group` ltg,lab_test lt,central_ipd c where c.ipd_no=lr.patient_id and lr.patient_type='ip' and lr.`lab_result`=ltg.`testid` and lt.testid=ltg.labid and lr.date between '$from' and '$to' $cond and labid in (8,9,10,11)) order by id";
            }
        //   echo $sql;
            $res=mysqli_query($con,$sql);
            return $res;
   }
   function misreport1($from,$to)
   {
       $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT dept_name, count(c.id) as data from central_opd c,department d where date(date) between '$from' and '$to' and dept_id=d.id group by d.id";
        $res=mysqli_query($con,$sql);
        $data=array();
        while($row=mysqli_fetch_assoc($res))
        {
            $data[$row['dept_name']]['OP']=$row['data'];
        }
        
        if($from==$to)
            {
            $sql1="SELECT d.`dept_name`, (SELECT count(dis.id) FROM `discharge` dis,central_ipd c where DATE(dis.discharge_date) > '$from' and c.ipd_no=dis.patient_id and DATE(dis.admission_date) <= '$from' and dis.dept_id=d.id )+ (select count(i.id) from central_ipd i where i.discharge=0 and DATE(i.admission_date)<='$from' and i.dept_id=d.id) as bed from department d where status='active' and dept_type='both' group by dept_name ";
                    //   echo $sql1;
            $res2=mysqli_query($con,$sql1);
                while($row1=mysqli_fetch_assoc($res2))
                {
                    $data[$row1['dept_name']]['IP']=$row1['bed'];
                }
            }
            else{
               
                $dates = $this->getBetweenDates($from, $to);
                // print_r($dates);exit;
                for($i=0;$i<count($dates);$i++)
                {
                      
                    $query="SELECT d.dept_name,(SELECT count(dis.id) FROM `discharge` dis,central_ipd c where DATE(dis.discharge_date) > '$dates[$i]' and DATE(dis.admission_date) <= '$dates[$i]' and c.ipd_no=dis.patient_id and dis.dept_id=d.id )+ (select count(i.id) from central_ipd i where i.discharge=0 and DATE(i.admission_date)<='$dates[$i]' and i.dept_id=d.id ) as occ from department d where status='active' and dept_type='both' group by d.id order by FIELD(d.id, '3','4','7','6','5','2','11','8','1','9','10')";
                    // echo $query;
                     $res2=mysqli_query($con,$query);
                    while($row1=mysqli_fetch_assoc($res2))
                    {
                        // echo "$row1[occ]<br>";
                        $data[$row1['dept_name']]['IP'] +=$row1['occ'];
                    }
                }
            }
            $sql2="select dept_name,sum(data)as ot from ((select dept_name,count(o.id) as data from central_opd c,operation_theater o,department d where d.id=c.dept_id and o.patient_id=c.id and o.date between '$from' and '$to' group by d.id) UNION (select dept_name,count(o.id) as data from central_ipd c,operation_theater o,department d where d.id=c.dept_id and o.patient_id=c.ipd_no and o.date between '$from' and '$to' group by d.id)) as data1 group by dept_name";
            //   echo $sql2;
              $res2=mysqli_query($con,$sql2);
              while($row2=mysqli_fetch_assoc($res2))
              {
                  $data[$row2['dept_name']]['OT']=$row2['ot'];
              }
             
              
        return $data;
   }
   function misreport2($from,$to)
   {
       $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
       
        $sql="SELECT service_type,sum(paid_amount) as amount from billing b where date(b.date) between '$from' and '$to' group by service_type ORDER BY FIELD(service_type,'OP','IP','Pathology','Radiology','Dialysis','Blood Bank')";
      
        $res=mysqli_query($con,$sql);
        return $res;
   }
    function misreport3($from,$to)
   {
       $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT count(c.id) as data,dept_name,concat(doc.name,' ',doc.last_name) as doctor from central_opd c,department d,doctor doc where doc.id=c.doctor and d.id=c.dept_id and date(c.date) between '$from' and '$to' group by d.id,doc.id order by doctor";
    //   echo $sql;
        $res=mysqli_query($con,$sql);
        return $res;
   }
   function misreport4($from,$to)
   {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $data=array();
        if($from==$to)
            {
            $sql1="SELECT b.`ward`, (SELECT count(dis.id) FROM `discharge` dis,central_ipd c where DATE(dis.discharge_date) > '$from' and c.ipd_no=dis.patient_id and DATE(dis.admission_date) <= '$from' and c.bed_id=b.id )+ (select count(i.id) from central_ipd i where i.discharge=0 and DATE(i.admission_date)<='$from' and i.bed_id=b.id) as bed from bed b group by b.ward ";
                    //   echo $sql;
            $res2=mysqli_query($con,$sql1);
                while($row1=mysqli_fetch_assoc($res2))
                {
                    $data[$row1['ward']] +=$row1['bed'];
                }
            }
            else{
               
                $dates = $this->getBetweenDates($from, $to);
                // print_r($dates);exit;
                for($i=0;$i<count($dates);$i++)
                {
                      
                    $query="SELECT b.ward,(SELECT count(dis.id) FROM `discharge` dis,central_ipd c where DATE(dis.discharge_date) > '$dates[$i]' and DATE(dis.admission_date) <= '$dates[$i]' and c.ipd_no=dis.patient_id and c.bed_id=b.id )+ (select count(i.id) from central_ipd i where i.discharge=0 and DATE(i.admission_date)<='$dates[$i]' and i.bed_id=b.id ) as occ from bed b group by b.ward ";
                    // echo $query;
            $res2=mysqli_query($con,$query);
                    while($row1=mysqli_fetch_assoc($res2))
                    {
                        // echo "$row1[occ]<br>";
                        $data[$row1['ward']] +=$row1['occ'];
                    }
                }
            }
        return $data;
   }
   function selectOP_seacrh($search)
   {
       $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT opd.*,d.dept_name,u.name as doctor FROM doctor u,department d,`central_opd` opd WHERE  opd.dept_id=d.id and u.id=opd.doctor and (opd.uhid like '%$search%' || opd.name like '%$search%' || opd.mobile like '%$search%' || opd.opd_no like '$search%' || opd.address like '%$search%') group by opd.id ";
        $res=mysqli_query($con,$sql);
        return $res;
   }
   function getOPDPatient($data)
   {
      $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT opd.*,d.dept_name,u.name as doctor FROM doctor u,department d,`central_opd` opd WHERE  opd.dept_id=d.id and u.id=opd.doctor and opd.opd_no like '$data%' group by opd.id";
        // echo $sql;
        $res=mysqli_query($con,$sql);
        return $res; 
   }
    function getIPDPatient($data)
   {
      $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT ipd.*,d.dept_name,dis.discharge_date,dis.created_by as discharged_by FROM department d,`central_ipd` ipd left join discharge dis on dis.patient_id=ipd.ipd_no WHERE  ipd.dept_id=d.id  and ipd.opd_no like '$data%' group by ipd.id";
        // echo $sql;
        $res=mysqli_query($con,$sql);
        return $res; 
   }
   function getBillPatient($data)
   {
       $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="(SELECT b.date,b.bill_no,b.doctor,b.paid_amount,b.payment_mode,b.collected_by,b.patient_id,b.service_type,b.status,d.dept_name,b.id,b.outstand_amount FROM `billing` b,department d WHERE d.id=b.dept_id and patient_id in ($data) and b.status='PAID')
        UNION ALL (select date,concat('ADV',id),'',amount,payment_type,created_by,ip_no,'Advance',status,'',id,'' from ip_advance_amount where ip_no in ($data) and status='PAID') order by date";
        // echo $sql;
        $res=mysqli_query($con,$sql);
        return $res; 
   }
   function getottype()
   {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();  
        $sql="SELECT * FROM ot_type where status='active'";
        $res=mysqli_query($con,$sql);
        return $res; 
   }
   
     function getPurchaseDetails($from,$to,$distributer)
        {
            $newlogin = new userlogin;                  
              $con=$newlogin->dbconnect();
              $sql="SELECT l.credit,l.purchase_id,l.manuf_id,l.invoice,l.date,m.manuf_name from `manufacturer_ledger` l,`pharma_manufacturer` m where m.id=l.manuf_id and l.credit > 0 and l.date between '$from' and '$to' $distributer group by invoice";
            //   echo $sql;
              $res=mysqli_query($con,$sql);
              return $res;
        }
        
        
        function getManufacture()
        {
              $newlogin = new userlogin;                  
              $con=$newlogin->dbconnect();
              $sql="SELECT * FROM `pharma_manufacturer` WHERE status='Active'";
              $res=mysqli_query($con,$sql);
              return $res;
        }
        
        
        function getSalesDetails($from,$to,$ptype,$doctor)
        {
              $newlogin = new userlogin;                  
              $con=$newlogin->dbconnect();
              
              if($doctor=='All')
              {
                  $doc='';
              }
              
              if($doctor!='All')
              {
                  $doc= " and u.id=$doctor";
              }
              
              if($ptype=='All')
              {
              
              $sql="((select c.name,c.mobile,'OP' as ptype,o.invoice_no,o.date,o.grand_total as total,concat(u.name,'',u.last_name) as doc from op_medicine o,central_opd c,user u where u.id=c.doctor and o.date between '$from' and '$to' and o.patient_id=c.id $doc group by o.invoice_no) UNION ALL (select c.name,c.mobile,'IP' as ptype,o.invoice_no,o.date,sum(o.grand_total) as total,c.doctor as doc from ip_medicine o,central_ipd c,user u where concat(u.name,' ',u.last_name)=c.doctor and  o.date between '$from' and '$to' and o.patient_id=c.ipd_no $doc group by o.invoice_no) UNION ALL (select patientname as name,mob_no as mobile,'Walk In' as ptype,bill_no as invoice_no,date,sum(total_amt) as total,doctor as doc from walkin_pharma_bill,user u where concat(u.name,' ',u.last_name)=doctor and date between '$from' and '$to' $doc group by bill_no)) order by date,invoice_no";
              }
              if($ptype=='OP')
              {
                 $sql="select c.name,c.mobile,'OP' as ptype,o.invoice_no,o.date,o.grand_total as total,concat(u.name,'',u.last_name) as doc from op_medicine o,central_opd c,user u where u.id=c.doctor and o.date between '$from' and '$to' and o.patient_id=c.id $doc group by o.invoice_no"; 
                 
              }
              if($ptype=='IP')
              {
                  $sql="select c.name,c.mobile,'IP' as ptype,o.invoice_no,o.date,sum(o.grand_total) as total,c.doctor as doc from ip_medicine o,central_ipd c,user u where concat(u.name,' ',u.last_name)=c.doctor and  o.date between '$from' and '$to' and o.patient_id=c.ipd_no $doc group by o.invoice_no";
              }
              if($ptype=='Walk In')
              {
                 $sql="select patientname as name,mob_no as mobile,'Walk In' as ptype,bill_no as invoice_no,date,sum(total_amt) as total,doctor as doc from walkin_pharma_bill,user u where concat(u.name,' ',u.last_name)=doctor and date between '$from' and '$to' $doc group by bill_no"; 
              }
            //   echo $sql;
              $res=mysqli_query($con,$sql);
              return $res;
        }
        function getTotalBed($date)
        {
            $newlogin = new userlogin;                  
              $con=$newlogin->dbconnect();
              $sql="SELECT bed_count FROM `bed_occupancy` WHERE '$date' between from_date and to_date";
              $res=mysqli_query($con,$sql);
              $row=mysqli_fetch_assoc($res);
              return $row['bed_count'];
        }

        function getDisSummary($ipd_no)
        {
            $newlogin = new userlogin;
            $con = $newlogin->dbconnect();
            $safe_ipd = mysqli_real_escape_string($con, $ipd_no);
            $sql = "SELECT * FROM `discharge` WHERE `patient_id` = '$safe_ipd' ORDER BY id DESC LIMIT 1";
            $res = mysqli_query($con, $sql);
            return $res;
        }


        // ====================================================================
        // ABHA / ABDM registration coverage reports (abha_reports.php)
        // --------------------------------------------------------------------
        // How many OPD / IPD patients registered in a period carry an ABHA.
        //
        // central_opd.abha_number is written at registration time (opClass::addOP
        // and the ABHA prefill on opregistration.php), so OPD figures read it
        // directly.
        //
        // central_ipd.abha_number, however, is only filled in by the ABDM
        // discovery sync (api/patient_history.php) or by abha_integration.php --
        // ipregistration.php never captures one. An admission normally inherits
        // the ABHA captured on the OPD registration it came through, so every
        // IPD figure below falls back to the newest non-empty
        // central_opd.abha_number for the same opd_no. That fallback is a
        // correlated LIMIT 1 sub-select on purpose: a JOIN would double-count an
        // admission whose opd_no repeats in central_opd (revisit rows).
        //
        // The aggregates run over a derived table so the fallback is evaluated
        // once per row instead of once per aggregate, and so nothing outside the
        // GROUP BY key is selected (safe under ONLY_FULL_GROUP_BY).
        //
        // The IPD window is written as a half-open range rather than
        // DATE(admission_date) BETWEEN ..., because wrapping the column in a
        // function stops idx_admission_date being usable as a range and turns
        // every one of these into a full index scan. Both indexes this report
        // relies on live in sql/abha_report_indexes.sql -- run it before use.
        // ====================================================================

        /** SQL expression yielding the effective ABHA number of a central_opd row. */
        private function abhaOPExpr($alias = 'c')
        {
            return "NULLIF(TRIM(COALESCE(`$alias`.`abha_number`,'')),'')";
        }

        /**
         * SQL expression: 1 when any care context of the visit has been linked
         * to ABDM -- the visit row itself (consultation / discharge), or any of
         * its bills (billing) or lab samples (sample_status). Bills and samples
         * key a visit by patient_id = central_opd.id for OP and = ipd_no for IP,
         * the same mapping api/patient_history.php uses to build care contexts.
         * Compared as strings so an 'IP…' number is never cast to 0.
         */
        private function abhaLinkedExpr($rowFlag, $visitKey)
        {
            return "CASE WHEN COALESCE($rowFlag,0) = 1
                          OR EXISTS (SELECT 1 FROM `billing` lb
                                      WHERE lb.`patient_id` = CAST($visitKey AS CHAR)
                                        AND lb.`abha_linked` = 1)
                          OR EXISTS (SELECT 1 FROM `sample_status` ls
                                      WHERE ls.`patient_id` = CAST($visitKey AS CHAR)
                                        AND ls.`abha_linked` = 1)
                         THEN 1 ELSE 0 END";
        }

        /** SQL expression yielding the effective ABHA number of a central_ipd row. */
        private function abhaIPExpr($alias = 'i')
        {
            return "COALESCE(
                        NULLIF(TRIM(COALESCE(`$alias`.`abha_number`,'')),''),
                        (SELECT NULLIF(TRIM(COALESCE(o.`abha_number`,'')),'')
                           FROM `central_opd` o
                          WHERE o.`opd_no` = `$alias`.`opd_no`
                            AND TRIM(COALESCE(o.`abha_number`,'')) <> ''
                          ORDER BY o.`id` DESC LIMIT 1)
                    )";
        }

        /** Normalise a summary row: never NULL, and carry the derived figures. */
        private function abhaFill($row)
        {
            $total  = (int) (isset($row['total'])       ? $row['total']       : 0);
            $with   = (int) (isset($row['with_abha'])   ? $row['with_abha']   : 0);
            $uniq   = (int) (isset($row['unique_abha']) ? $row['unique_abha'] : 0);
            $linked = (int) (isset($row['care_linked']) ? $row['care_linked'] : 0);
            return array(
                'total'        => $total,
                'with_abha'    => $with,
                'without_abha' => $total - $with,
                'unique_abha'  => $uniq,
                'care_linked'  => $linked,
                'percent'      => $total > 0 ? round(($with * 100) / $total, 2) : 0,
            );
        }

        /** Run a summary query built over the derived table above. */
        private function abhaSummary($inner, $from, $to, $dept)
        {
            $newlogin = new userlogin;
            $con = $newlogin->dbconnect();
            $sql = "SELECT COUNT(*)                                            AS total,
                           SUM(CASE WHEN t.abha IS NOT NULL THEN 1 ELSE 0 END) AS with_abha,
                           COUNT(DISTINCT t.abha)                              AS unique_abha,
                           SUM(t.linked)                                       AS care_linked
                      FROM ($inner) t";
            $st = $con->prepare($sql);
            if ($dept > 0) { $d = (int) $dept; $st->bind_param('ssi', $from, $to, $d); }
            else           { $st->bind_param('ss', $from, $to); }
            $st->execute();
            $row = $st->get_result()->fetch_assoc();
            $st->close();
            return $this->abhaFill($row);
        }

        /**
         * Headline OPD numbers for the period: registrations, how many hold an
         * ABHA, how many distinct ABHAs those are, and how many rows have had a
         * care context pushed to ABDM (see abhaLinkedExpr()).
         */
        function abhaOPSummary($from, $to, $dept = 0)
        {
            $abha = $this->abhaOPExpr('c');
            $cond = ($dept > 0) ? " AND c.`dept_id` = ?" : "";
            $inner = "SELECT $abha AS abha,
                             {$this->abhaLinkedExpr('c.`abha_linked`', 'c.`id`')} AS linked
                        FROM `central_opd` c
                       WHERE c.`date` BETWEEN ? AND ? $cond";
            return $this->abhaSummary($inner, $from, $to, $dept);
        }

        /** Headline IPD numbers for the period. See the note on the fallback above. */
        function abhaIPSummary($from, $to, $dept = 0)
        {
            $abha = $this->abhaIPExpr('i');
            $cond = ($dept > 0) ? " AND i.`dept_id` = ?" : "";
            $inner = "SELECT $abha AS abha,
                             {$this->abhaLinkedExpr('i.`abha_linked`', 'i.`ipd_no`')} AS linked
                        FROM `central_ipd` i
                       WHERE i.`admission_date` >= ? AND i.`admission_date` < DATE_ADD(?, INTERVAL 1 DAY) $cond";
            return $this->abhaSummary($inner, $from, $to, $dept);
        }

        /**
         * OPD registration rows for the period.
         * $status: 'with' = ABHA holders only, 'without' = no ABHA, '' = both.
         */
        function abhaOPDetails($from, $to, $dept = 0, $status = '')
        {
            $newlogin = new userlogin;
            $con = $newlogin->dbconnect();
            $abha = $this->abhaOPExpr('c');
            $cond = '';
            if ($dept > 0)             { $cond .= " AND c.`dept_id` = ?"; }
            if ($status === 'with')    { $cond .= " AND $abha IS NOT NULL"; }
            if ($status === 'without') { $cond .= " AND $abha IS NULL"; }
            $sql = "SELECT c.`id`, c.`uhid`, c.`opd_no`, c.`date`, c.`name`, c.`age`, c.`gender`,
                           c.`mobile`, c.`address`, d.`dept_name`, c.`patient_type`, c.`created_by`,
                           $abha                                         AS abha_number,
                           NULLIF(TRIM(COALESCE(c.`abha_address`,'')),'') AS abha_address,
                           {$this->abhaLinkedExpr('c.`abha_linked`', 'c.`id`')} AS abha_linked
                      FROM `central_opd` c
                      LEFT JOIN `department` d ON d.`id` = c.`dept_id`
                     WHERE c.`date` BETWEEN ? AND ? $cond
                     ORDER BY c.`date`, c.`id`";
            $st = $con->prepare($sql);
            if ($dept > 0) { $d = (int) $dept; $st->bind_param('ssi', $from, $to, $d); }
            else           { $st->bind_param('ss', $from, $to); }
            $st->execute();
            return $st->get_result();
        }

        /** IPD admission rows for the period. $status as in abhaOPDetails(). */
        function abhaIPDetails($from, $to, $dept = 0, $status = '')
        {
            $newlogin = new userlogin;
            $con = $newlogin->dbconnect();
            $abha = $this->abhaIPExpr('i');
            $cond = '';
            if ($dept > 0)             { $cond .= " AND i.`dept_id` = ?"; }
            if ($status === 'with')    { $cond .= " AND $abha IS NOT NULL"; }
            if ($status === 'without') { $cond .= " AND $abha IS NULL"; }
            $sql = "SELECT i.`id`, i.`ipd_no`, i.`opd_no`, i.`admission_date`, i.`name`, i.`age`,
                           i.`gender`, i.`mobile`, i.`ward`, d.`dept_name`, i.`patient_type`, i.`created_by`,
                           (SELECT o.`uhid` FROM `central_opd` o
                             WHERE o.`opd_no` = i.`opd_no` ORDER BY o.`id` DESC LIMIT 1) AS uhid,
                           $abha                                         AS abha_number,
                           COALESCE(
                             NULLIF(TRIM(COALESCE(i.`abha_address`,'')),''),
                             (SELECT NULLIF(TRIM(COALESCE(o.`abha_address`,'')),'')
                                FROM `central_opd` o
                               WHERE o.`opd_no` = i.`opd_no`
                                 AND TRIM(COALESCE(o.`abha_address`,'')) <> ''
                               ORDER BY o.`id` DESC LIMIT 1)
                           )                                             AS abha_address,
                           {$this->abhaLinkedExpr('i.`abha_linked`', 'i.`ipd_no`')} AS abha_linked
                      FROM `central_ipd` i
                      LEFT JOIN `department` d ON d.`id` = i.`dept_id`
                     WHERE i.`admission_date` >= ? AND i.`admission_date` < DATE_ADD(?, INTERVAL 1 DAY) $cond
                     ORDER BY i.`admission_date`, i.`id`";
            $st = $con->prepare($sql);
            if ($dept > 0) { $d = (int) $dept; $st->bind_param('ssi', $from, $to, $d); }
            else           { $st->bind_param('ss', $from, $to); }
            $st->execute();
            return $st->get_result();
        }

        /** Department-wise ABHA coverage. $type is 'OP' or 'IP'. */
        function abhaDeptWise($from, $to, $type = 'OP')
        {
            $newlogin = new userlogin;
            $con = $newlogin->dbconnect();
            if ($type === 'IP') {
                $abha = $this->abhaIPExpr('i');
                $inner = "SELECT COALESCE(d.`dept_name`,'Not Set') AS dept_name, $abha AS abha
                            FROM `central_ipd` i
                            LEFT JOIN `department` d ON d.`id` = i.`dept_id`
                           WHERE i.`admission_date` >= ? AND i.`admission_date` < DATE_ADD(?, INTERVAL 1 DAY)";
            } else {
                $abha = $this->abhaOPExpr('c');
                $inner = "SELECT COALESCE(d.`dept_name`,'Not Set') AS dept_name, $abha AS abha
                            FROM `central_opd` c
                            LEFT JOIN `department` d ON d.`id` = c.`dept_id`
                           WHERE c.`date` BETWEEN ? AND ?";
            }
            $sql = "SELECT t.dept_name, COUNT(*) AS total,
                           SUM(CASE WHEN t.abha IS NOT NULL THEN 1 ELSE 0 END) AS with_abha
                      FROM ($inner) t
                     GROUP BY t.dept_name
                     ORDER BY total DESC";
            $st = $con->prepare($sql);
            $st->bind_param('ss', $from, $to);
            $st->execute();
            return $st->get_result();
        }

        /**
         * Day-wise OPD + IPD ABHA coverage merged into one array keyed by date,
         * so the trend table prints a single row per day.
         */
        function abhaDayWise($from, $to)
        {
            $newlogin = new userlogin;
            $con = $newlogin->dbconnect();
            $days = array();

            $abha  = $this->abhaOPExpr('c');
            $inner = "SELECT c.`date` AS d, $abha AS abha
                        FROM `central_opd` c
                       WHERE c.`date` BETWEEN ? AND ?";
            $sql = "SELECT t.d, COUNT(*) AS total,
                           SUM(CASE WHEN t.abha IS NOT NULL THEN 1 ELSE 0 END) AS with_abha
                      FROM ($inner) t GROUP BY t.d ORDER BY t.d";
            $st = $con->prepare($sql);
            $st->bind_param('ss', $from, $to);
            $st->execute();
            $res = $st->get_result();
            while ($r = $res->fetch_assoc()) {
                $days[$r['d']] = array(
                    'date'     => $r['d'],
                    'op_total' => (int) $r['total'], 'op_abha' => (int) $r['with_abha'],
                    'ip_total' => 0,                 'ip_abha' => 0,
                );
            }
            $st->close();

            $abha  = $this->abhaIPExpr('i');
            $inner = "SELECT DATE(i.`admission_date`) AS d, $abha AS abha
                        FROM `central_ipd` i
                       WHERE i.`admission_date` >= ? AND i.`admission_date` < DATE_ADD(?, INTERVAL 1 DAY)";
            $sql = "SELECT t.d, COUNT(*) AS total,
                           SUM(CASE WHEN t.abha IS NOT NULL THEN 1 ELSE 0 END) AS with_abha
                      FROM ($inner) t GROUP BY t.d ORDER BY t.d";
            $st = $con->prepare($sql);
            $st->bind_param('ss', $from, $to);
            $st->execute();
            $res = $st->get_result();
            while ($r = $res->fetch_assoc()) {
                if (!isset($days[$r['d']])) {
                    $days[$r['d']] = array('date' => $r['d'], 'op_total' => 0, 'op_abha' => 0,
                                           'ip_total' => 0, 'ip_abha' => 0);
                }
                $days[$r['d']]['ip_total'] = (int) $r['total'];
                $days[$r['d']]['ip_abha']  = (int) $r['with_abha'];
            }
            $st->close();

            ksort($days);
            return array_values($days);
        }

        /**
         * Bed occupancy over the report window, split by ABHA.
         *
         * Uses the same midnight-census rule as bed_occupancy.php / getBedOcc():
         * an admission occupies a bed on day D when it was admitted on or before
         * D and is either still admitted (discharge = 0) or was discharged after
         * D. The day of discharge therefore does not count as a bed-day.
         *
         * Only stays that can overlap the window are fetched -- still-admitted
         * ones, plus those discharged on or after $from -- and the per-day census
         * is then walked in PHP, which keeps this to two indexed reads however
         * long the range is.
         *
         * Beds come from the `bed` master (status 'inactive' = removed), grouped
         * by ward. A stay whose bed_id no longer resolves is kept under the ward
         * written on the admission itself.
         *
         * Returns array(
         *   'beds'  => total beds, 'days' => days in window,
         *   'bed_days', 'bed_days_abha', 'avg_census', 'occupancy' (%),
         *   'census_to', 'census_to_abha'  (occupied on the last day),
         *   'wards' => [ward => same keys + 'beds'],
         *   'daily' => [ ['date','occupied','with_abha'], ... ]
         * )
         */
        function abhaBedOccupancy($from, $to, $dept = 0)
        {
            $newlogin = new userlogin;
            $con = $newlogin->dbconnect();

            $empty = function () {
                return array('beds' => 0, 'bed_days' => 0, 'bed_days_abha' => 0,
                             'census_to' => 0, 'census_to_abha' => 0);
            };

            // ── Beds per ward ───────────────────────────────────────────────
            $wards = array();
            $sql = "SELECT COALESCE(NULLIF(TRIM(b.`ward`),''),'Not Set') AS ward, COUNT(*) AS beds
                      FROM `bed` b
                     WHERE COALESCE(b.`status`,'') <> 'inactive'"
                 . ($dept > 0 ? " AND b.`department` = ?" : "")
                 . " GROUP BY ward ORDER BY ward";
            $st = $con->prepare($sql);
            if ($dept > 0) { $d = (int) $dept; $st->bind_param('i', $d); }
            $st->execute();
            $res = $st->get_result();
            while ($r = $res->fetch_assoc()) {
                $wards[$r['ward']] = $empty();
                $wards[$r['ward']]['beds'] = (int) $r['beds'];
            }
            $st->close();

            // ── Stays that can overlap [from, to] ───────────────────────────
            // The discharged branch takes the latest discharge row per ipd_no,
            // since a re-saved discharge summary leaves more than one behind.
            $abha = $this->abhaIPExpr('i');
            $cond = ($dept > 0) ? " AND i.`dept_id` = ?" : "";
            $cols = "DATE(i.`admission_date`) AS adm,
                     COALESCE(NULLIF(TRIM(bd.`ward`),''), NULLIF(TRIM(i.`ward`),''), 'Not Set') AS ward,
                     $abha AS abha";
            $sql = "SELECT $cols, NULL AS dis
                      FROM `central_ipd` i
                      LEFT JOIN `bed` bd ON bd.`id` = i.`bed_id`
                     WHERE i.`discharge` = 0
                       AND i.`admission_date` < DATE_ADD(?, INTERVAL 1 DAY) $cond
                    UNION ALL
                    SELECT $cols, x.dis
                      FROM (SELECT dis.`patient_id`, DATE(MAX(dis.`discharge_date`)) AS dis
                              FROM `discharge` dis
                             WHERE dis.`discharge_date` >= ?
                             GROUP BY dis.`patient_id`) x
                      JOIN `central_ipd` i ON i.`ipd_no` = x.`patient_id`
                      LEFT JOIN `bed` bd ON bd.`id` = i.`bed_id`
                     WHERE i.`discharge` <> 0
                       AND i.`admission_date` < DATE_ADD(?, INTERVAL 1 DAY) $cond";
            $st = $con->prepare($sql);
            if ($dept > 0) {
                $d = (int) $dept;
                $st->bind_param('sissi', $to, $d, $from, $to, $d);
            } else {
                $st->bind_param('sss', $to, $from, $to);
            }
            $st->execute();
            $res = $st->get_result();

            $fromTs = strtotime($from);
            $toTs   = strtotime($to);
            $daily  = array();
            for ($t = $fromTs; $t <= $toTs; $t = strtotime('+1 day', $t)) {
                $daily[date('Y-m-d', $t)] = array('date' => date('Y-m-d', $t), 'occupied' => 0, 'with_abha' => 0);
            }
            $days = count($daily);

            while ($r = $res->fetch_assoc()) {
                // Occupied from admission day up to, not including, discharge day.
                $start = max(strtotime($r['adm']), $fromTs);
                $end   = $r['dis'] !== null ? min(strtotime('-1 day', strtotime($r['dis'])), $toTs) : $toTs;
                if ($end < $start) { continue; }

                $w = $r['ward'];
                if (!isset($wards[$w])) { $wards[$w] = $empty(); }
                $hasAbha = $r['abha'] !== null;

                for ($t = $start; $t <= $end; $t = strtotime('+1 day', $t)) {
                    $k = date('Y-m-d', $t);
                    $daily[$k]['occupied']++;
                    $wards[$w]['bed_days']++;
                    if ($hasAbha) { $daily[$k]['with_abha']++; $wards[$w]['bed_days_abha']++; }
                }
                if ($end === $toTs) {
                    $wards[$w]['census_to']++;
                    if ($hasAbha) { $wards[$w]['census_to_abha']++; }
                }
            }
            $st->close();

            $out = $empty();
            foreach ($wards as $w => $row) {
                foreach (array_keys($out) as $k) { $out[$k] += $row[$k]; }
                $wards[$w]['avg_census'] = $days > 0 ? round($row['bed_days'] / $days, 2) : 0;
                $wards[$w]['occupancy']  = ($row['beds'] > 0 && $days > 0)
                    ? round(($row['bed_days'] * 100) / ($row['beds'] * $days), 2) : 0;
            }
            ksort($wards);
            $out['days']       = $days;
            $out['avg_census'] = $days > 0 ? round($out['bed_days'] / $days, 2) : 0;
            $out['occupancy']  = ($out['beds'] > 0 && $days > 0)
                ? round(($out['bed_days'] * 100) / ($out['beds'] * $days), 2) : 0;
            $out['wards']      = $wards;
            $out['daily']      = array_values($daily);
            return $out;
        }
}
?>