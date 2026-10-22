<?php 
require_once(__DIR__ . '/session_config.php');
include('dbconnect.php');

date_default_timezone_set("Asia/Kolkata"); 
class ajax{
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
          $sql="SELECT c.`id`,c.`opd_no`,c.`date`,c.`name`,c.`age`,c.`gender`,c.`mobile`,c.`address`,c.`diagnosis`,c.`dept_id`,c.`sub_deptid`,c.`annual_income`,c.`old_patient`,c.`religion`,c.`caste`,c.`category`,c.`parent_name`,c.`education`,c.`marital_status`,c.`occupation`,c.`doctor`,c.`economic_state`,c.`bpl_no`,c.`note`,c.`token`,c.`uhid`,c.`remark`,c.`created_by`,c.`is_generated`,c.`is_ipd`,c.`policy`,c.`aadhar`,c.`pan`,c.`patient_type`,c.`created_at`,c.`mlc`,c.`city`,concat(d.name,' ',d.last_name) as doc,rx_id,c.`dob`,c.`abha_number`,c.`abha_address`,c.`state`,c.`district`,c.`pincode` FROM `central_opd` c,doctor d WHERE `opd_no`='$this->opno' and d.id=c.doctor order by id desc limit 1";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
      }
      function getdoctMedic($id)
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT * from `medicine` where name='$id'";
        // echo $sql;exit;
        $res=mysqli_fetch_assoc(mysqli_query($con,$sql));
        return $res;
    }
      function getOPDetails1($id)
      {
          $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT c.`id`,c.`opd_no`,c.`date`,c.`name`,c.`age`,c.`gender`,c.`mobile`,c.`address`,c.`diagnosis`,c.`dept_id`,c.`sub_deptid`,c.`annual_income`,c.`old_patient`,c.`religion`,c.`caste`,c.`category`,c.`parent_name`,c.`education`,c.`marital_status`,c.`occupation`,c.`doctor`,c.`economic_state`,c.`bpl_no`,c.`note`,c.`token`,c.`uhid`,c.`remark`,c.`created_by`,c.`is_generated`,c.`is_ipd`,c.`policy`,c.`aadhar`,c.`pan`,c.`patient_type`,c.`created_at`,c.`mlc`,c.`city`,concat(d.name,' ',d.last_name) as doc FROM `central_opd` c,doctor d WHERE c.id=$id and d.id=c.doctor order by id desc limit 1";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
      }
      function getIPDetails($id)
      {
          $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT * FROM `central_ipd` WHERE `ipd_no`='$id'";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
      }
      
      function getIPDetails4TransferBed($id)
      {
          $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT c.*,d.dept_name,b.bed_number FROM `central_ipd` c,department d,bed b WHERE c.`ipd_no`='$id' and c.dept_id=d.id and b.id=c.bed_id";
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
          $sql="SELECT ip.*,concat(u.name ,' ',u.last_name) as doc FROM `central_ipd` ip, `doctor` u WHERE `ipd_no`='$id' and ip.doctor=u.id";
          // echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
      }
      function getOPRevisit()
      {
        $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT c.`id`,c.`opd_no`,c.`date`,c.`name`,c.`age`,c.`gender`,c.`mobile`,c.`address`,c.`diagnosis`,c.`dept_id`,c.`sub_deptid`,c.`annual_income`,c.`old_patient`,c.`religion`,c.`caste`,c.`category`,c.`parent_name`,c.`education`,c.`marital_status`,c.`occupation`,c.`doctor`,c.`economic_state`,c.`bpl_no`,c.`note`,c.`token`,c.`uhid`,c.`remark`,c.`created_by`,c.`is_generated`,c.`is_ipd`,c.`policy`,c.`aadhar`,c.`pan`,c.`patient_type`,c.`created_at`,c.`mlc`,c.`city`,d.`dept_name` FROM `central_opd` c,`department` d WHERE `opd_no`='$this->opno' and d.id=c.dept_id";
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

          return $res;
      }
      /**
       * Remove an ORDERED test from a visit — an `op_lab` row, not a result.
       *
       * The treatment pages list tests ordered on the visit, so their delete
       * button must remove the order. They were calling deleteLab() above, which
       * deletes from `lab_report` — a different table — using the lab test's id
       * as if it were a report id, so it silently destroyed an unrelated
       * patient's result row. deleteLab() stays as it is because
       * ip_lab_result.php genuinely does delete lab_report rows.
       */
      function deleteOpLab($val)
      {
          $newlogin = new userlogin;
          $con=$newlogin->dbconnect();

          $stmt=$con->prepare("DELETE FROM `op_lab` WHERE `id`=?");
          if(!$stmt)
          {
              return false;
          }
          $id=(int)$val;
          $stmt->bind_param('i',$id);
          $res=$stmt->execute();
          $stmt->close();

          $date=date("Y-m-d H:i:s");
          $audit="DELETE FROM `op_lab` WHERE `id`='$id'";
          $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
          if($sql1)
          {
              $user=isset($_SESSION['name']) ? $_SESSION['name'] : '';
              $sql1->bind_param("sss", $audit, $user, $date);
              $sql1->execute();
              $sql1->close();
          }

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

          return $res;
      }
       function deleteMedic($val)
      {
          $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="DELETE from `op_medicine` WHERE `id`=$val";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
         
          $date=date("Y-m-d H:i:s");
      $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();

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
          $sql="SELECT u.* FROM `doctor` u WHERE role='Doctor' and status='active' group by u.id order by u.name";
          $res=mysqli_query($con,$sql);
          return $res;
    }
    function getDeptDoctor($id)
    {
          $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $day=date("l");
         
          $sql="SELECT u.* FROM `doctor` u WHERE role='Doctor' and status='active' and department='$id' group by u.id order by u.name";
          $res=mysqli_query($con,$sql);
          return $res;
    }
    function getDepartment($id)
    {
       $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $day=date("l");
          
          $sql="SELECT u.* FROM `doctor` u WHERE role='Doctor' and status='active' and u.id='$id' group by u.id order by u.name";
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
          $sql="SELECT m.*,u.unit_name FROM `medicine` m left join medicine_units u on m.med_unit=u.id where m.`med_manuf`=$id and m.status='active'";
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
        
           if($ward=='ICU')
           {
               $wd=" ward like '%ICU%' and ";
           }
           else
           {
               $wd=" department='$id' and ward='$ward' and ";
           }
           $sql="SELECT * FROM bed WHERE $wd status='active'";
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
           $sql="SELECT distinct(ward) as ward FROM bed WHERE $admin ";
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
        
          $res1=mysqli_query($con,$sql);
          $res =mysqli_affected_rows($con);
           
            $date=date("Y-m-d H:i:s");
      $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();

          return $res;
      }
      function getConsultation($id)
      {
          $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
           $sql="SELECT consultation FROM doctor WHERE id='$id'";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
          $data= mysqli_fetch_assoc($res);
         return $data['consultation'];
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
        
         function getNameOPDetailsNew()
      {
          $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT c.`id`,c.`opd_no`,c.`date`,c.`name`,c.`age`,c.`gender`,c.`mobile`,c.`address`,c.`diagnosis`,c.`dept_id`,c.`sub_deptid`,c.`annual_income`,c.`old_patient`,c.`religion`,c.`caste`,c.`category`,c.`parent_name`,c.`education`,c.`marital_status`,c.`occupation`,c.`doctor`,c.`economic_state`,c.`bpl_no`,c.`note`,c.`token`,c.`uhid`,c.`remark`,c.`created_by`,c.`is_generated`,c.`is_ipd`,c.`policy`,c.`aadhar`,c.`pan`,c.`patient_type`,c.`created_at`,c.`mlc`,c.`city` FROM `central_opd` c WHERE (c.uhid like '$this->opno' || c.opd_no like '$this->opno' || c.name like '$this->opno%' || c.mobile like '$this->opno%') group by opd_no ";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
      }
      function getOPDetailsNew()
      {
          $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT c.`id`,c.`opd_no`,c.`date`,c.`name`,c.`age`,c.`gender`,c.`mobile`,c.`address`,c.`diagnosis`,c.`dept_id`,c.`sub_deptid`,c.`annual_income`,c.`old_patient`,c.`religion`,c.`caste`,c.`category`,c.`parent_name`,c.`education`,c.`marital_status`,c.`occupation`,c.`doctor`,c.`economic_state`,c.`bpl_no`,c.`note`,c.`token`,c.`uhid`,c.`remark`,c.`created_by`,c.`is_generated`,c.`is_ipd`,c.`policy`,c.`aadhar`,c.`pan`,c.`patient_type`,c.`created_at`,c.`mlc`,c.`city` ,concat(u.name,' ',u.last_name) as doc 
          FROM `central_opd` c, doctor u WHERE   (c.opd_no like '$this->opno%' || c.uhid like '$this->opno%') and u.id=c.doctor";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
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
 function getMedicineBatch($id)
    {
        $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT * FROM `medicine_purchase` WHERE `medicine_id`='$id' and current_qty > 0";
          echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
    }
    function getExpiry($id,$batch)
    {
        $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT * FROM `medicine_purchase` WHERE `medicine_id`='$id' and batch_id='$batch' and current_qty > 0";
        //   echo $sql;
          $res=mysqli_fetch_assoc(mysqli_query($con,$sql));
          return $res;
    }
    function deletePackage($id)
  {
      $newlogin = new userlogin;                  
      $con = $newlogin->dbconnect();
    
      // Delete related records from lab_package_tests and lab_package
      $sql2 = "DELETE FROM `lab_package_tests` WHERE `package_id` = $id";
      mysqli_query($con, $sql2);

      $sql = "DELETE FROM `lab_package` WHERE `id` = $id";
      mysqli_query($con, $sql);
      $date = date("Y-m-d H:i:s");
      $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
      $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
      $sql1->execute();
      $sql1->close();

      return mysqli_affected_rows($con) > 0;
  }
  function deleteReferenceCenter($id)
  {
      $newlogin = new userlogin;                  
      $con = $newlogin->dbconnect();
    
      // Delete related records from reference_center
      $sql = "DELETE FROM `reference_centers` WHERE `id` = $id";
      mysqli_query($con, $sql);
      $date = date("Y-m-d H:i:s");
      $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
      $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
      $sql1->execute();
      $sql1->close();

      return mysqli_affected_rows($con) > 0;
  }
  function deleteOutsourceLab($id)
  {
      $newlogin = new userlogin;                  
      $con = $newlogin->dbconnect();
    
      // Delete related records from outsource_lab
      $sql = "DELETE FROM `outsource_labs` WHERE `id` = $id";
      mysqli_query($con, $sql);
      $date = date("Y-m-d H:i:s");
      $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
      $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
      $sql1->execute();
      $sql1->close();

      return mysqli_affected_rows($con) > 0;
  } 
  function deleteTestParameter($id)
  {
      $newlogin = new userlogin;                  
      $con = $newlogin->dbconnect();
    
      // Delete related records from outsource_lab
      $sql = "update `lab_test_parameter` SET status='inactive' WHERE `id` = $id";
    //   echo $sql;
      mysqli_query($con, $sql);
      $date = date("Y-m-d H:i:s");
      $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
      $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
      $sql1->execute();
      $sql1->close();

      return mysqli_affected_rows($con) > 0;
  }
    function getTestRange($id)
  {
      $newlogin = new userlogin;
      $con = $newlogin->dbconnect();
      $sql = "SELECT lr.*,ltp.unit,lrr.reference_name FROM `lab_test_parameter` ltp,`lab_test_range` lr LEFT JOIN lab_reference_range lrr ON lrr.id=lr.reference_range_id WHERE ltp.id = '$id' and lr.parameter_id = ltp.id and lr.status='active'";
// echo $sql;
      $res = mysqli_query($con, $sql);
      $ranges = [];
      while ($row = mysqli_fetch_assoc($res)) {
          $ranges[] = $row;
      }
      return $ranges;
  }
  function getTestResult($id)
  {
      $newlogin = new userlogin;
      $con = $newlogin->dbconnect();
      $sql = "SELECT * FROM `lab_test_result` WHERE `test_parameter_id` = '$id'";
      $res = mysqli_query($con, $sql);
      $ranges = [];
      while ($row = mysqli_fetch_assoc($res)) {
          $ranges[] = $row;
      }
      return $ranges;
  }
  function deleteTestResult($id)
  {
      $newlogin = new userlogin;
      $con = $newlogin->dbconnect();

      // Mark the test result as inactive (soft delete)
      $sql = "DELETE from `lab_test_result` WHERE `id` = $id";
      echo $sql;
      mysqli_query($con, $sql);

      $date = date("Y-m-d H:i:s");
      $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
      $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
      $sql1->execute();
      $sql1->close();

      return mysqli_affected_rows($con) > 0;
  }
  function deleteTestResult1($id)
  {
      $newlogin = new userlogin;
      $con = $newlogin->dbconnect();
      $sql = "update `lab_test_range` set status='inactive' WHERE `id` = $id";
      mysqli_query($con, $sql);
      return mysqli_affected_rows($con) > 0;
  }
  function getLabTest($patient_id, $bill_no, $testid, $gender)
  {
      $newlogin = new userlogin;
      $con = $newlogin->dbconnect();

      // Fetch all test parameters for the given testid, including all types (parameter, free_text, table_format, antibiotic)
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
          $lab_report_sql = "SELECT id, test_result,print_flag FROM lab_report 
                             WHERE patient_id = '$patient_id' 
                               AND bill_no = '$bill_no' 
                               AND testid = '$testid' 
                               AND parameter_id = '{$param['parameter_id']}'";
                            //   echo $lab_report_sql;exit;
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
                            // echo $range_sql;
              $range_res = mysqli_query($con, $range_sql);
              $range = mysqli_fetch_assoc($range_res);
              $row['normal_value'] = $range ? $range['normal_value'] : '';
              $row['lower_limit'] = $range ? $range['lower_limit'] : '';
              $row['upper_limit'] = $range ? $range['upper_limit'] : '';
            
          } else {
              $row['normal_value'] = '';
              $row['lower_limit'] = '';
              $row['upper_limit'] = '';
              // For table_format, unit may be set in parameter
          }
          if($param['type']=='free_text')
          {
               $row['test_result'] = $row['test_result'] ? $row['test_result'] : $row['testname'];
          }
          // Fetch options for 'parameter' type (dropdown)
          if ($param['type'] != 'free_text' && $param['type'] !='heading') {
              $options_sql = "SELECT options FROM lab_test_result 
                              WHERE test_parameter_id = '{$param['parameter_id']}'";
              $options_res = mysqli_query($con, $options_sql);
              $options = [];
              while ($opt = mysqli_fetch_assoc($options_res)) {
                  $options[] = $opt['options'];
              }
              $row['options'] =$options;
          } else {
              $row['options'] = '';
          }

          // For table_format, fetch table data rows
          if ($param['type'] == 'table_format') {
              $table_data_sql = "SELECT * FROM lab_test_table_data_row 
                                 WHERE parameter_id = '{$param['parameter_id']}' 
                                   AND status = 'active'
                                 ORDER BY position ASC";
              $table_data_res = mysqli_query($con, $table_data_sql);
              $table_data = [];
              while ($td = mysqli_fetch_assoc($table_data_res)) {
                  $table_data[] = $td;
              }
              $row['table_data_rows'] = $table_data;
          }

          // For antibiotic type, fetch antibiotic data
          if ($param['type'] == 'antibiotic') {
              $antibiotic_sql = "SELECT * FROM lab_test_antibiotic 
                                 WHERE parameter_id = '{$param['parameter_id']}' ";
              $antibiotic_res = mysqli_query($con, $antibiotic_sql);
              $antibiotic_data = [];
              while ($ab = mysqli_fetch_assoc($antibiotic_res)) {
                  $antibiotic_data[] = $ab;
              }
              $row['antibiotic_data'] = $antibiotic_data;
          }

          $result[] = $row;
      }
// print_r($result);
      // Return as an array of associative arrays (for compatibility with existing code)
      return $result;
  }
  function deleteTableDataRow($id)
  {
      $newlogin = new userlogin;
      $con = $newlogin->dbconnect();
      $sql = "update `lab_test_table_data_row` set status='inactive' WHERE `id` = $id";
      mysqli_query($con, $sql);
      return mysqli_affected_rows($con) > 0;
  }
  function getTableFormat($id)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "SELECT * FROM `lab_test_table_format` WHERE `parameter_id` = '$id'";
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
     function getTestParameterById($id) {
    $newlogin = new userlogin;
    $con = $newlogin->dbconnect();
    $id = intval($id);
    $sql = "SELECT * FROM `lab_test_parameter` WHERE `id` = '$id' ";
    return mysqli_query($con, $sql);
  }
    function getESignatureById($id)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $id = intval($id);
        $sql = "SELECT * FROM `lab_signature` WHERE `id`='$id' AND `status`='active'";
        // echo $sql;
        // exit;
        $res = mysqli_query($con, $sql);
        return $res;
    }
}