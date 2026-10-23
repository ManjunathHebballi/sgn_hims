<?php
require_once(__DIR__ . '/../../model/session_config.php');
include('../model/dbconnect.php');

date_default_timezone_set("Asia/Kolkata"); 
class ajax{
    private $opno,
    $from,
    $to,
    $doctor;


    function setopno($opno) {
        $this->opno = $opno;
      } 
    function setfrom($from)
    {
        $this->from=$from;
    }
    function setto($to)
    {
        $this->to=$to;
    }
    function setdoctor($doctor)
    {
        $this->doctor=$doctor;
    }

      function getOPDetails()
      {
          $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT c.`id`, c.`opd_no`, c.`date`, c.`name`, c.`age`, c.`gender`, c.`mobile`, c.`address`, c.`diagnosis`, c.`dept_id`, c.`sub_deptid`, c.`annual_income`, c.`old_patient`,c.`doctor`, c.`token`, c.`uhid`,concat(d.name,' ',d.last_name) as doctor_name FROM `central_opd` c,doctor d WHERE `opd_no`='$this->opno' and c.doctor=d.id order by id desc limit 1";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
      }
      function getOPIPDetails()
      {
          $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="(SELECT c.`id`, c.`opd_no`, c.`date`, c.`name`, c.`age`, c.`gender`, c.`mobile`, c.`address`, c.`diagnosis`, c.`dept_id`, c.`sub_deptid`, c.`annual_income`, c.`old_patient`,c.`doctor`, c.`token`, c.`uhid`,concat(d.name,' ',d.last_name) as doctor_name FROM `central_opd` c,doctor d WHERE `opd_no`='$this->opno' and c.doctor=d.id order by id desc limit 1) UNION ALL
          (SELECT c.`ipd_no` as id, c.`ipd_no`, c.`admission_date`, c.`name`, c.`age`, c.`gender`, c.`mobile`, c.`address`, c.`diagnosis`, c.`dept_id`, co.`sub_deptid`, co.`annual_income`, co.`old_patient`,c.`doctor`, co.`token`, co.`uhid`,c.doctor as doctor_name FROM `central_ipd` c,central_opd co WHERE c.`ipd_no`='$this->opno' and co.opd_no=c.opd_no order by id desc limit 1)";
       // echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
      }
      function getIPDetails($id)
      {
          $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT c.`id`, c.`opd_no`, c.`ipd_no`, c.`ipcard_no`, c.`admission_date`, c.`name`, c.`age`, c.`gender`, c.`mobile`, c.`category`, c.`address`, c.`dept_id`, c.`sub_deptid`, c.`religion`, c.`caste`, c.`parent_name`, c.`occupation`, c.`annual_income`, c.`doctor`, c.`mlc`, c.`bed_id`, c.`ward`, c.`diagnosis`, c.`discharge`, c.`cmo`, c.`policy`, c.`patient_type`, c.`remark`, c.`refer_by`, c.`created_by`, c.`is_generated`, c.`final_bill`,sum(ia.amount-COALESCE(a.adjusted_amount, 0)) as amount,ia.id as adv_id,IFNULL((select id from billing where patient_id=c.ipd_no limit 1), 0) as bill FROM `central_ipd` c left join ip_advance_amount ia on c.ipd_no=ia.ip_no and ia.status = 'PAID' LEFT JOIN adjust_advance a ON a.patient_id=c.opd_no and a.advance_id-ia.id WHERE c.`ipd_no`='$id'";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
      }
      
      function getIPDetails4TransferBed($id)
      {
          $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT c.`id`, c.`opd_no`, c.`ipd_no`, c.`ipcard_no`, c.`admission_date`, c.`name`, c.`age`, c.`gender`, c.`mobile`, c.`category`, c.`address`, c.`dept_id`, c.`sub_deptid`, c.`religion`, c.`caste`, c.`parent_name`, c.`occupation`, c.`annual_income`, c.`doctor`, c.`mlc`, c.`bed_id`, c.`ward`, c.`diagnosis`, c.`discharge`, c.`cmo`, c.`policy`, c.`patient_type`, c.`remark`, c.`refer_by`, c.`created_by`, c.`is_generated`, c.`final_bill`,d.dept_name,b.bed_number FROM `central_ipd` c,department d,bed b WHERE c.`ipd_no`='$id' and c.dept_id=d.id and b.id=c.bed_id";
            // echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
      }
      
      
      
      function getIPDetails4EditD($id)
      {
          $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT * FROM `death_record` WHERE `id`='$id'";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
      }
         function getIPDetails4EditB($id)
      {
          $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT * FROM `birth_record` WHERE `id`='$id'";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
      }
      
      function getIPDetails4Birth($id)
      {
          $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT ip.`id`, ip.`opd_no`, ip.`ipd_no`, ip.`ipcard_no`, ip.`admission_date`, ip.`name`, ip.`age`, ip.`gender`, ip.`mobile`, ip.`category`, ip.`address`, ip.`dept_id`, ip.`sub_deptid`, ip.`religion`, ip.`caste`, ip.`parent_name`, ip.`occupation`, ip.`annual_income`, ip.`doctor`, ip.`mlc`, ip.`bed_id`, ip.`ward`, ip.`diagnosis`, ip.`discharge`, ip.`cmo`, ip.`policy`, ip.`patient_type`, ip.`remark`, ip.`refer_by`, ip.`created_by`, ip.`is_generated`, ip.`final_bill`,concat(u.name ,' ',u.last_name) as doc FROM `central_ipd` ip, `doctor` u WHERE `ipd_no`='$id' and ip.doctor=u.id";
          echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
      }
      function getOPRevisit()
      {
        $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT c.`id`, c.`opd_no`, c.`date`, c.`name`, c.`age`, c.`gender`, c.`mobile`, c.`address`, c.`diagnosis`, c.`dept_id`, c.`sub_deptid`, c.`annual_income`, c.`old_patient`, c.`religion`, c.`caste`, c.`category`, c.`parent_name`, c.`education`, c.`marital_status`, c.`occupation`, c.`doctor`, c.`economic_state`, c.`bpl_no`, c.`note`, c.`token`, c.`uhid`, c.`remark`, c.`created_by`, c.`is_generated`, c.`is_ipd`, c.`policy`, c.`aadhar`, c.`pan`, c.`patient_type`, c.`created_at`, c.`mlc`, c.`city`,d.`dept_name` FROM `central_opd` c,`department` d WHERE `opd_no`='$this->opno' and d.id=c.dept_id";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
      }
      function setsubproce($val)
      {
        $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT * from `sub_procedure` WHERE `procedure_id`=$val";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
      }
      function getProceMedic($val)
      {
          $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT * from `procedure_medicine` WHERE `procedure`=$val";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
      }
      function deleteLab($val)
      {
          $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="DELETE from `lab_report` WHERE `id`=$val";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
                      
$date=date("Y-m-d H:i:s");
$sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
$sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
$sql1->execute();
$sql1->close();
$con->close();

    
          return $res;
      }
      function deleteBed($val)
      {
          $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="update  `bed` set status='inactive' WHERE `id`=$val";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
                      
$date=date("Y-m-d H:i:s");
$sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
$sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
$sql1->execute();
$sql1->close();
$con->close();

    
          return $res;
      }
      function deleteDept($val)
      {
          $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="update  `department` set status='inactive' WHERE `id`=$val";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
                      
$date=date("Y-m-d H:i:s");
$sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
$sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
$sql1->execute();
$sql1->close();
$con->close();

    
          return $res;
      }
       function deleteMedic($val)
      {
          $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="DELETE from `op_medicine` WHERE `id`=$val";
          echo $sql;
          $res=mysqli_query($con,$sql);
                      
$date=date("Y-m-d H:i:s");
$sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
$sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
$sql1->execute();
$sql1->close();
$con->close();

    
          return $res;
      }
      
      function getState($id)
    {
        $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT * FROM `states` WHERE country_id=$id ";
          $res=mysqli_query($con,$sql);
          return $res;
    }
    function getCity($id)
    {
        $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT * FROM `cities` WHERE state_id=$id ";
          $res=mysqli_query($con,$sql);
          return $res;
    }
    function getDoctor($id)
    {
       $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $day=date("l");
           if($_SESSION['role']=='Admin')
          {
              $admin='';
          }
          else{
              $admin="and st.`day`='$day'";
          }
          $sql="SELECT u.* FROM `doctor` u,`staff_timetable` st WHERE role='Doctor' and u.`id`=st.`doctor_id` $admin and st.dept_id=$id group by u.id";
          $res=mysqli_query($con,$sql);
          return $res;
    }
    function updateTimeTable($id)
    {
        $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="UPDATE `staff_timetable` SET doctor_id='$this->doctor',start_time='$this->from',end_time='$this->to' WHERE id=$id ";
          $res=mysqli_query($con,$sql);
                      
$date=date("Y-m-d H:i:s");
$sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
$sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
$sql1->execute();
$sql1->close();
$con->close();

    
          return $res;
    }
    
    function getIcd($id)
    {
         $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT concat(`code`,' - ',`details`) as icd FROM `icd_codes` WHERE code='$id' ";
        //   echo $sql;
          $res=mysqli_fetch_assoc(mysqli_query($con,$sql));
        //   print_r($res);
          return $res['icd'];
    }
    
    function getMedicine($id)
    {
        
         $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT m.*,u.unit_name FROM `medicine` m, medicine_units u where m.med_unit=u.id and m.`med_manuf`=$id and m.status='active'";
          $res=mysqli_query($con,$sql);
          return $res;
    }
    
     function getStock($id)
    {
        
         $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT m.manuf_price,m.med_price,sum(current_qty) as qty FROM `medicine` m left join `medicine_purchase` p  on p.medicine_id=m.id where m.id=$id ";
        //  echo $sql;
          $res=mysqli_fetch_assoc(mysqli_query($con,$sql));
          return $res['qty'].'|'.$res['manuf_price'].'|'.$res['med_price'];
    }
     function delRadioCat($id)
      {
          $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="DELETE from `radiology_category` WHERE `id`=$id";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
                      
$date=date("Y-m-d H:i:s");
$sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
$sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
$sql1->execute();
$sql1->close();
$con->close();

    
          return $res;
      }
      function delRadioUnit($id)
      {
          $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="DELETE from `radiology_unit` WHERE `id`=$id";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
                      
$date=date("Y-m-d H:i:s");
$sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
$sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
$sql1->execute();
$sql1->close();
$con->close();

    
          return $res;
      }
      function delRadioPara($id)
      {
          $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="DELETE from `radiology_parameter` WHERE `id`=$id";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
                      
$date=date("Y-m-d H:i:s");
$sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
$sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
$sql1->execute();
$sql1->close();
$con->close();

    
          return $res;
      }
      function delPathoCat($id)
      {
          $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="DELETE from `pathology_category` WHERE `id`=$id";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
                      
$date=date("Y-m-d H:i:s");
$sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
$sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
$sql1->execute();
$sql1->close();
$con->close();

    
          return $res;
      }
      function delPathoUnit($id)
      {
          $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="DELETE from `pathology_unit` WHERE `id`=$id";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
                      
$date=date("Y-m-d H:i:s");
$sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
$sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
$sql1->execute();
$sql1->close();
$con->close();

    
          return $res;
      }
      function delPathoPara($id)
      {
          $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="DELETE from `pathology_parameter` WHERE `id`=$id";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
                      
$date=date("Y-m-d H:i:s");
$sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
$sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
$sql1->execute();
$sql1->close();
$con->close();

    
          return $res;
      }
      
      function getRange($id)
      {
          $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
           $sql="SELECT rp.*,ru.unit as para_unit FROM radiology_parameter rp, radiology_unit ru where rp.unit=ru.id and rp.id=$id";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
      }
      
      function getBed($id,$ward)
      {
          $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
        //   if($_SESSION['role']=='Admin')
        //   {
        //       $admin="department in ($id,6)";
        //   }
        //   else{
              $admin="department=$id";
        //   }
           $sql="SELECT * FROM bed WHERE (department='$id' or department=0) and ward='$ward' and status='active'";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
      }
      
        function getWard($id)
      {
          $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
        //   if($_SESSION['role']=='Admin')
        //   {
        //       $admin="department in ($id,6)";
        //   }
        //   else{
              $admin="department=$id || department=0";
        //   }
           $sql="SELECT distinct(ward) as ward FROM bed WHERE $admin and status='active'";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
      }
      function deleteOT($id)
      {
          $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
           $sql="DELETE FROM operation_theater WHERE id='$id'";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
          return mysqli_affected_rows($con);
      }
      
      function getItems($id)
        {
            $newlogin = new userlogin;
            $con=$newlogin->dbconnect();
            $sql="select * from `service_list` where id=$id and status='active'";
            // echo $sql;exit;
            $result=mysqli_query($con,$sql);
            mysqli_close($con);
            return $result;
        }
        
        function cancel_bill($bill,$note)
        {
            $newlogin = new userlogin;
            $con=$newlogin->dbconnect();
            $date=date("Y-m-d H:i:s");
            $sql="update billing set status='CANCELLED',cancellation_note='$note',updated_by='$_SESSION[name]',updated_at=NOW(),outstand_amount = paid_amount where bill_no='$bill'";
            $result=mysqli_query($con,$sql);
            
            $query = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
            $query->bind_param("sss", $sql, $_SESSION['name'], $date);
            $query->execute();
            $query->close();
            
            $sql1="update billing_details set status='CANCELLED' where bill_no='$bill'";
            $result=mysqli_query($con,$sql1);
                        
            $query1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
            $query1->bind_param("sss", $sql1, $_SESSION['name'], $date);
            $query1->execute();
            $query1->close();
            
            $sql2="update billing_payment set status='CANCELLED' where bill_no='$bill'";
            $result=mysqli_query($con,$sql2);
                        
            $query2 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
            $query2->bind_param("sss", $sql2, $_SESSION['name'], $date);
            $query2->execute();
            $query2->close();

$con->close();
            return $result;
        }
        function billcancel($id)
        {
            $newlogin = new userlogin;
            $con=$newlogin->dbconnect();
            
            $sql2="select * from billing_details where id=$id";
            $res=mysqli_query($con,$sql2);
            $row=mysqli_fetch_assoc($res);
            $query="update billing set amount=amount-(($row[price]*$row[unit])-$row[discount]),outstand_amount=outstand_amount-(($row[price]*$row[unit])-$row[discount]),service_level_discount=service_level_discount-$row[discount] where id=$row[bill_id]";
            mysqli_query($con,$query);
           $date=date("Y-m-d H:i:s");
           
           $sql="UPDATE billing_details SET status='CANCELLED' where id=$id";
            $result=mysqli_query($con,$sql);
$sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
$sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
$sql1->execute();
$sql1->close();
$con->close();
            return $result;
        }
        function billESICancel($id)
        {
            $newlogin = new userlogin;
            $con=$newlogin->dbconnect();
            $sql2="select * from esi_billing_details where id=$id";
            $res=mysqli_query($con,$sql2);
            $row=mysqli_fetch_assoc($res);
            $query="update esi_billing set amount=amount-(($row[price]*$row[unit])-$row[discount]) where id=$row[bill_id]";
            mysqli_query($con,$query);
            $sql="delete from esi_billing_details where id=$id";
            $result=mysqli_query($con,$sql);
           $date=date("Y-m-d H:i:s");
$sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
$sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
$sql1->execute();
$sql1->close();
$con->close();
            return $result;
        }
        function billClaimCancel($id)
        {
            $newlogin = new userlogin;
            $con=$newlogin->dbconnect();
            $sql2="select * from claim_billing_details where id=$id";
            $res=mysqli_query($con,$sql2);
            $row=mysqli_fetch_assoc($res);
            $query="update claim_billing set amount=amount-(($row[price]*$row[unit])-$row[discount]) where id=$row[bill_id]";
            mysqli_query($con,$query);
            $sql="delete from claim_billing_details where id=$id";
            $result=mysqli_query($con,$sql);
           $date=date("Y-m-d H:i:s");
$sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
$sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
$sql1->execute();
$sql1->close();
$con->close();
            return $result;
        }
        
        
        function cancel_advance($bill,$reason)
        {
            $newlogin = new userlogin;
            $con=$newlogin->dbconnect();
            $sql="update ip_advance_amount set status='CANCELLED',cancellation_note='$reason',cancelled_by='$_SESSION[name]',cancelled_at=now() where id='$bill'";
            $result=mysqli_query($con,$sql);
        $date=date("Y-m-d H:i:s");
$sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
$sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
$sql1->execute();
$sql1->close();
$con->close();
            return $result;
        }
        
        
        function final_bill($id)
        {
            $newlogin = new userlogin;
            $con=$newlogin->dbconnect();
            $sqll="update central_ipd set final_bill=1 where ipd_no='$id'"; 
            // echo $sqll;exit;
            $result=mysqli_query($con,$sqll);
           $date=date("Y-m-d H:i:s");
$sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
$sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
$sql1->execute();
$sql1->close();
$con->close();
            return $result;
        }
        function getPatient($val)
        {
            $search=trim($val);
            $newlogin = new userlogin;
            $con=$newlogin->dbconnect();

            if($search=='')
            {
                $result=mysqli_query($con,"SELECT `id`,`opd_no`,`uhid`,`name`,`mobile`,`age`,`address` FROM `central_opd` WHERE 1=0");
                mysqli_close($con);
                return $result;
            }

            $search=mysqli_real_escape_string($con,$search);
            $patientKey = "CASE WHEN `uhid` IS NOT NULL AND `uhid` != '' THEN CONCAT('U-', `uhid`) WHEN `mobile` IS NOT NULL AND `mobile` != '' THEN CONCAT('M-', `mobile`) ELSE CONCAT('O-', `opd_no`) END";

            if(preg_match('/^[0-9]+$/',$search) || stripos($search,'SGN')===0)
            {
                $where="`opd_no` like '$search%' OR `uhid` like '$search%' OR `mobile` like '$search%'";
            }
            else
            {
                $where="`name` like '$search%' OR `mobile` like '$search%' OR `uhid` like '$search%' OR `opd_no` like '$search%'";
            }

            $sqll="SELECT c.`id`, c.`opd_no`, c.`uhid`, c.`name`, c.`mobile`, c.`age`, c.`address`
            FROM `central_opd` c
            INNER JOIN (
                SELECT MAX(`id`) AS latest_id
                FROM `central_opd`
                WHERE $where
                GROUP BY $patientKey
                ORDER BY MAX(`id`) DESC
                LIMIT 20
            ) latest ON latest.latest_id = c.`id`
            ORDER BY c.`id` DESC";
            // echo $sqll;exit;
            $result=mysqli_query($con,$sqll);

            if($result && mysqli_num_rows($result)==0 && strlen($search)>=4)
            {
                $fallbackSql="SELECT c.`id`, c.`opd_no`, c.`uhid`, c.`name`, c.`mobile`, c.`age`, c.`address`
                FROM `central_opd` c
                INNER JOIN (
                    SELECT MAX(`id`) AS latest_id
                    FROM `central_opd`
                    WHERE `address` like '%$search%'
                    GROUP BY $patientKey
                    ORDER BY MAX(`id`) DESC
                    LIMIT 10
                ) latest ON latest.latest_id = c.`id`
                ORDER BY c.`id` DESC";
                $result=mysqli_query($con,$fallbackSql);
            }

            mysqli_close($con);
            return $result;
        }
        
        
         function changepassword($old,$new)
        {
          $newlogin = new userlogin;
          $con=$newlogin->dbconnect();
          $sql="UPDATE user set password=md5('$new') WHERE id='$_SESSION[loginid]' and password=md5('$old')";
        //   echo $sql;exit;
          $res=mysqli_query($con,$sql);
          if(mysqli_affected_rows($con) == 0)
          {
              $data="Incorrect current password";
          }
          else{
              $date=date("Y-m-d H:i:s");
$sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
$sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
$sql1->execute();
$sql1->close();
$con->close();
              $data="Password updated successfully";
          }
          echo $data;
        }
   function getDept($val)
    {
        $newlogin = new userlogin;
            $con=$newlogin->dbconnect();
            $sql="select d.id from department d,doctor doc where doc.billing_dept=d.id and concat(doc.name,' ',doc.last_name)='$val' and doc.status='active'";
            // echo $sql;
            $result=mysqli_query($con,$sql);
            mysqli_close($con);
            return $result;
    }
    function getIItems()
        {
            $newlogin = new userlogin;
            $con=$newlogin->dbconnect();
            $sql="select * from `service_list` where status='active'";
            // echo $sql;exit;
            $result=mysqli_query($con,$sql);
            mysqli_close($con);
            return $result;
        }
         function selectDoctor()
    {
      $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      $sql="SELECT * FROM `doctor` WHERE `status`='active'";
    //   echo $sql;
      $res=mysqli_query($con,$sql);
      return $res;
    }
    function getPatientBill($val)
    {
        $newlogin = new userlogin;
        $con=$newlogin->dbconnect();
        $sql="SELECT bd.*,s.esi_code,s.esi_amount FROM `billing` b,billing_details bd,service_list s WHERE s.id=bd.service_id and bd.bill_id=b.id and bd.status='PAID' and b.status='PAID' and b.patient_id='$val' and service_type in ('IP','Radiology') 
        ORDER BY CASE service_type
    WHEN 'ip' THEN 1
    WHEN 'Radiology' THEN 2
    else 3 end,bd.id";
        // echo $sql
        $res=mysqli_query($con,$sql);
      return $res;
    }
    function getPItems()
        {
            $newlogin = new userlogin;
            $con=$newlogin->dbconnect();
            $sql="select * from `service_list` where status='active' and service_group_id in (select id FROM `service_group` WHERE bill_group like '%PATHOLOGY TEST%')";
            // echo $sql;exit;
            $result=mysqli_query($con,$sql);
            mysqli_close($con);
            return $result;
        }

    function getRoundAssessmentsForBilling($patient_id)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $patient_id = mysqli_real_escape_string($con, $patient_id);
        $sql = "SELECT ra.id, ra.qty, ra.doctor_id,ra.price,ra.description, ra.round_datetime,
                       sl.id as service_id, sl.name as service_name, sl.ipd_charge, sl.is_doctor,
                       CONCAT(d.name,' ',d.last_name) as doctor_name
                FROM ip_round_assessment ra
                LEFT JOIN service_list sl ON sl.id = ra.service_id
                LEFT JOIN doctor d ON d.id = ra.doctor_id AND ra.doctor_id > 0
                WHERE ra.patient_id = '$patient_id' AND ra.status = 'active'
                ORDER BY ra.round_datetime ASC";
        $result = mysqli_query($con, $sql);
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        mysqli_close($con);
        return json_encode($data);
    }

}