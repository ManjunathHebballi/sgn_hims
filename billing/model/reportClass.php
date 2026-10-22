<?php 
require_once(__DIR__ . '/../../model/session_config.php');
include('dbconnect.php');


class report{
  private $from,$to,$type;
    function setfrom($from) {
        $this->from = $from;
    } 
    function setto($to) {
        $this->to = $to;
    } 
    function settype($type) {
        $this->type = $type;
    }
 function getCollectionBill($from,$to,$type)
       {
           $newlogin = new userlogin;                  
            $con=$newlogin->dbconnect();
            if($type=='OP')
            {
                $sql="(SELECT c.opd_no as ipd_no,c.date,c.name,concat(d.name,' ',d.last_name) as doctor,c.invoice_no,payment_mode,consultancy as total,'OP' as type,'Registration' as bill_type FROM `central_opd` c,`user` d WHERE d.id=c.doctor and DATE(date) between '$from' and '$to') UNION ALL (SELECT c.opd_no as ipd_no,i.date,c.name,i.doctor,i.invoice_no,i.payment_mode,sum(total),i.type,bill_type FROM `central_opd` c,`ip_bill` i WHERE i.type='OP' and i.ipd_no=c.id and i.status='PAID' and DATE(i.date) between '$from' and '$to' group by invoice_no,bill_type)order by FIELD(bill_type,'Advance','Ambulance','Audiometry','ECG','IPD Charges','Lab','Leech','OPD','Physio Therapy','Procedure','Registration','Scanning','Surgical Charges','Surgical Charges OT ','Swarna Prashana','X-RAY','Return'),date";
            }
            else{
            $sql="(SELECT c.ipd_no,a.date,c.name,doctor,a.id as invoice_no,payment_mode, sum(amount) as total,'IP' as type,'Advance' as bill_type FROM `central_ipd` c,`ip_advance_amount` a WHERE a.ip_no=c.ipd_no and DATE(date) between '$from' and '$to' and a.status='PAID' group by c.ipd_no) UNION ALL
    (SELECT c.ipd_no,i.date,c.name,i.doctor,i.invoice_no,i.payment_mode,sum(total),i.type,bill_type FROM `central_ipd` c,`ip_bill` i WHERE i.ipd_no=c.ipd_no and DATE(i.date) between '$from' and '$to'and i.status='PAID' and i.type='IP'  group by invoice_no,bill_type,c.ipd_no)  UNION ALL
    (SELECT c.ipd_no,r.returned_date as date,c.name,doctor,r.id as invoice_no,payment_mode, returned_amount as total,'IP' as type,'Return' as bill_type FROM `central_ipd` c,`ip_return_amount` r WHERE r.ipd_no=c.ipd_no and DATE(r.returned_date) between '$from' and '$to' and r.status='PAID' and r.type='IP' group by c.ipd_no)  order by FIELD(bill_type,'Advance','Ambulance','Audiometry','ECG','IPD Charges','Lab','Leech','OPD','Physio Therapy','Procedure','Registration','Scanning','Surgical Charges','Surgical Charges OT ','Swarna Prashana','X-RAY','Return'),date";
            
            }
            //   echo $sql;
        //   exit;
            $res=mysqli_query($con,$sql);
            return $res;
       }

       function getAllIPAdvance()
       {
           $newlogin = new userlogin;                  
            $con=$newlogin->dbconnect();
            $data=array();
            $sql="select ip_no,sum(amount) as amount from ip_advance_amount where status='PAID' group by ip_no";
            //   echo $sql;
            $res=mysqli_query($con,$sql);
            while($row=mysqli_fetch_assoc($res))
            {
                $data[$row['ip_no']]=$row['amount'];
            }
            
            return $data;
       }


        function getIPDCollection($from,$to)
        {
            $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          
          $sql="SELECT payment_mode,SUM(total_amount_paid) AS total_amount_paid FROM (SELECT payment_mode, SUM(total) AS total_amount_paid FROM ip_bill WHERE type = 'IP' AND DATE(date) BETWEEN '$from' AND '$to' and status='PAID' GROUP BY payment_mode UNION ALL SELECT payment_mode,sum(amount) AS total_amount_paid from `ip_advance_amount` where DATE(date) BETWEEN '$from' AND '$to' and status='PAID' GROUP BY payment_mode UNION ALL SELECT 'Refund',sum(returned_amount) AS total_amount_paid from ip_return_amount where DATE(returned_date) BETWEEN '$from' AND '$to' and status='PAID' GROUP BY payment_mode) AS combined_data GROUP BY payment_mode";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
        }

        function getIPDCollectionDetails($from,$to)
        {
            $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
            $sql="SELECT date, name, opd_no,ipd_no,  SUM(CASE WHEN payment_mode = 'Cash' THEN total ELSE 0 END) AS cash_consultation_fee, SUM(CASE WHEN payment_mode = 'Card' THEN total ELSE 0 END) AS card_consultation_fee, SUM(CASE WHEN payment_mode = 'UPI' THEN total ELSE 0 END) AS online_consultation_fee, SUM(CASE WHEN payment_mode = 'Refund' THEN total ELSE 0 END) AS refund_consultation_fee FROM (SELECT i.date, c.name, c.opd_no,c.ipd_no,  total, i.payment_mode FROM ip_bill i,central_ipd c WHERE type = 'IP' AND DATE(i.date) BETWEEN '$from' AND '$to' and c.ipd_no=i.ipd_no and status='PAID' group by c.ipd_no UNION ALL SELECT i.date, c.name, c.opd_no,c.ipd_no,sum(amount) AS total_amount_paid,payment_mode from `ip_advance_amount` i,central_ipd c where DATE(date) BETWEEN '$from' AND '$to' and status='PAID' and c.ipd_no=i.ip_no group by c.ipd_no UNION ALL SELECT returned_date, name, opd_no,i.ipd_no,sum(returned_amount) AS total_amount_paid,'Refund' from ip_return_amount i,central_ipd c where DATE(returned_date) BETWEEN '$from' AND '$to' and c.ipd_no=i.ipd_no and status='PAID') AS combined_data where ipd_no!='' GROUP BY ipd_no ORDER BY `combined_data`.`ipd_no` ASC";
            // echo $sql;
            $res=mysqli_query($con,$sql);
          return $res;
            
        }
                function getDept_collection($from,$to,$type)
       {
           $newlogin = new userlogin;                  
            $con=$newlogin->dbconnect();
            if($type=='OP')
            {
                $sql="(SELECT count(c.invoice_no) as inv_count,payment_mode,sum(consultancy) as total,'Registration' as bill_type FROM `central_opd` c WHERE  DATE(date) between '$from' and '$to' group by bill_type) UNION ALL  (SELECT count(DISTINCT(i.invoice_no)),i.payment_mode,sum(total) as total,bill_type FROM `ip_bill` i WHERE DATE(i.date) between '$from' and '$to' and type='OP' and i.status='PAID' group by bill_type) UNION ALL (select count(invoice_no),payment_mode,sum(total),bill_type from ( (SELECT count(r.id) as invoice_no,r.payment_mode, sum(returned_amount) as total,'Return' as bill_type FROM `central_opd` c,`ip_return_amount` r WHERE r.ipd_no=c.id and DATE(r.returned_date) between '$from' and '$to' and r.status='PAID' and r.type='OP')) combine group by bill_type) order by FIELD(bill_type,'Advance','Ambulance','Audiometry','ECG','IPD Charges','Lab','Leech','OPD','Physio Therapy','Procedure','Registration','Scanning','Surgical Charges','Surgical Charges OT ','Swarna Prashana','X-RAY','Return')";
            }else{
              $sql="(SELECT count(a.id) as invoice_no,payment_mode, sum(amount) as total,'Advance' as bill_type FROM `central_ipd` c,`ip_advance_amount` a WHERE a.ip_no=c.ipd_no and DATE(date) between '$from' and '$to'and a.status='PAID' group by bill_type) UNION ALL  (SELECT count(DISTINCT(i.invoice_no)),i.payment_mode,(CASE WHEN (SELECT sum(`amount`) from ip_advance_amount where ipd_no=ip_no)-sum(total) > 0 and type='IP' THEN 0 else sum(total) end) as total,bill_type FROM `ip_bill` i WHERE DATE(i.date) between '$from' and '$to' and type='IP' and i.status='PAID' group by bill_type)UNION ALL (select count(invoice_no),payment_mode,sum(total),bill_type from ((SELECT count(r.id) as invoice_no,r.payment_mode, sum(returned_amount) as total,'Return' as bill_type FROM `central_ipd` c,`ip_return_amount` r WHERE r.ipd_no=c.ipd_no and DATE(r.returned_date) between '$from' and '$to' and r.status='PAID' and r.type='IP')) combine group by bill_type) order by FIELD(bill_type,'Advance','Ambulance','Audiometry','ECG','IPD Charges','Lab','Leech','OPD','Physio Therapy','Procedure','Registration','Scanning','Surgical Charges','Surgical Charges OT ','Swarna Prashana','X-RAY','Return')";  
            }
            
        //   echo $sql;
            $res=mysqli_query($con,$sql);
            return $res;
       }

       function getDoctor_collection($from,$to,$type)
       {
           $newlogin = new userlogin;                  
            $con=$newlogin->dbconnect();
            if($type=='OP')
            {
                $sql="(select sum(inv_count) as inv_count,sum(total) as total,doctor,'' as bill_type from ((SELECT count(c.invoice_no) as inv_count,sum(consultancy) as total,concat(d.name,' ',d.last_name) as doctor FROM `central_opd` c,`user` d WHERE d.id=c.doctor and DATE(date) between '$from' and '$to' group by doctor) UNION ALL (SELECT count(distinct(i.invoice_no)) as inv_count,sum(total) as total,i.doctor FROM `central_opd` c,`ip_bill` i WHERE i.type='OP' and i.ipd_no=c.id and i.status='PAID' and DATE(i.date) between '$from' and '$to' group by doctor))combuni group by doctor ) UNION ALL (select sum(invoice_no) as inv_count,sum(total) as total,doctor,bill_type from ((SELECT count(r.id) as invoice_no, sum(returned_amount) as total,concat(u.name,' ',u.last_name)as doctor,'Return' as bill_type FROM `central_opd` c,`ip_return_amount` r,`user` u WHERE u.id=c.doctor and r.ipd_no=c.id and DATE(r.returned_date) between '$from' and '$to' and r.status='PAID' and type='OP'))  rtrn group by doctor) order by doctor,bill_type";
            }
            else{
            $sql="(select sum(inv_count) as inv_count,sum(total) as total,doctor,'' as bill_type from ((SELECT count(a.id) as invoice_no, sum(amount) as total,c.doctor FROM `central_ipd` c,`ip_advance_amount` a WHERE a.ip_no=c.ipd_no and DATE(date) between '$from' and '$to' group by doctor and a.status='PAID')  UNION ALL
    (SELECT count(distinct(i.invoice_no)),(CASE WHEN (SELECT sum(`amount`) from ip_advance_amount where c.ipd_no=ip_no)-sum(total) > 0 THEN 0 else sum(total) end) as total,i.doctor FROM `central_ipd` c,`ip_bill` i WHERE i.ipd_no=c.ipd_no and DATE(i.date) between '$from' and '$to'and i.status='PAID' and i.type='IP' group by doctor)) combuni group by doctor ) UNION ALL (select sum(invoice_no) as inv_count,sum(total) as total,doctor,bill_type from ((SELECT count(r.id) as invoice_no, sum(returned_amount) as total,c.doctor,'Return' as bill_type FROM `central_ipd` c,`ip_return_amount` r WHERE r.ipd_no=c.ipd_no and DATE(r.returned_date) between '$from' and '$to' and r.status='PAID' and r.type='IP') )  rtrn group by doctor) order by doctor,bill_type";
        //   echo $sql;
            }
            $res=mysqli_query($con,$sql);
            return $res;
       }
       

       function getMode_collection($from,$to,$type)
       {
           $newlogin = new userlogin;                  
            $con=$newlogin->dbconnect();
            if($type=='OP')
            {
                $sql="(SELECT count(c.invoice_no) as inv_count,payment_mode,sum(consultancy) as total,'Registration' as bill_type FROM `central_opd` c WHERE  DATE(date) between '$from' and '$to' group by payment_mode) UNION ALL (SELECT count(DISTINCT(i.invoice_no)),i.payment_mode, sum(total) as total,bill_type FROM `ip_bill` i WHERE DATE(i.date) between '$from' and '$to' and type='OP' and i.status='PAID' group by payment_mode)";
            }
            else{
                 $sql="(SELECT count(a.id) as inv_count,payment_mode, sum(amount) as total,'Advance' as bill_type FROM `central_ipd` c,`ip_advance_amount` a WHERE a.ip_no=c.ipd_no and DATE(date) between '$from' and '$to'and a.status='PAID' group by payment_mode) UNION ALL  (SELECT count(DISTINCT(i.invoice_no)),i.payment_mode,(CASE WHEN (SELECT sum(`amount`) from ip_advance_amount where ipd_no=ip_no)-sum(total) > 0 and type='IP' THEN 0 else sum(total) end) as total,bill_type FROM `ip_bill` i WHERE DATE(i.date) between '$from' and '$to' and type='IP' and i.status='PAID' group by payment_mode)";
            }
           
        //   echo $sql;
            $res=mysqli_query($con,$sql);
            return $res;
       }


               function getOPDCollection($from,$to)
        {
            $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          
          $sql="SELECT payment_mode, SUM(total_consultation_fee) + SUM(total_amount_paid) AS total_amount_paid FROM ( SELECT payment_mode, SUM(consultancy) AS total_consultation_fee, 0 AS total_amount_paid FROM central_opd WHERE DATE(date) BETWEEN '$from' AND '$to' GROUP BY payment_mode UNION ALL SELECT payment_mode, 0 AS total_consultation_fee, SUM(total) AS total_amount_paid FROM ip_bill WHERE type = 'OP' AND DATE(date) BETWEEN '$from' AND '$to' GROUP BY payment_mode ) AS combined_data GROUP BY payment_mode";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
        }
        function getOPDCollectionDetails($from,$to)
        {
            $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
            $sql="SELECT date, name, opd_no, id, SUM(CASE WHEN payment_mode = 'Cash' THEN consultancy ELSE 0 END) AS cash_consultation_fee, SUM(CASE WHEN payment_mode = 'Card' THEN consultancy ELSE 0 END) AS card_consultation_fee, SUM(CASE WHEN payment_mode = 'UPI' THEN consultancy ELSE 0 END) AS online_consultation_fee FROM ( SELECT date, name, opd_no, id, consultancy, payment_mode FROM central_opd WHERE DATE(date) BETWEEN '$from' AND '$to' UNION ALL SELECT i.date, c.name, c.opd_no, c.id, total, i.payment_mode FROM ip_bill i,central_opd c WHERE type = 'OP' AND DATE(i.date) BETWEEN '$from' AND '$to' and c.id=i.ipd_no ) AS combined_data GROUP BY id ORDER BY `combined_data`.`id` ASC";
            // echo $sql;
            $res=mysqli_query($con,$sql);
          return $res;
            
        }
        function getOPDetails($from,$to,$type,$gn)
        {
            $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          if(strlen($gn)>0)
          {
              $dt="and b.is_generated=$gn";
          }
          else{
              $dt="";
          }
          $sql="(SELECT rc.name as ref_center,c.name,c.age,c.gender,c.address,c.uhid,c.opd_no,b.date,b.bill_no,sum(b.amount-discount_amount) as amount,sum(b.service_level_discount) as service_level_discount,sum(b.paid_amount) as paid_amount,sum(b.returned_amount) as returned_amount,b.collected_by FROM central_opd c,`billing` b left join reference_centers rc on rc.id=b.referal_center_id where c.id=b.patient_id and date(b.date) between '$from' and '$to' and b.status='PAID' and service_type='$type' $dt group by b.id)
          UNION ALL (SELECT rc.name as ref_center,i.name,i.age,c.gender,i.address,c.uhid,i.ipd_no,b.date,b.bill_no,sum(b.amount-discount_amount) as amount,sum(b.service_level_discount) as service_level_discount,sum(b.paid_amount) as paid_amount,sum(b.returned_amount) as returned_amount,b.collected_by FROM central_opd c,central_ipd i,`billing` b left join reference_centers rc on rc.id=b.referal_center_id where i.ipd_no=b.patient_id and c.opd_no=i.opd_no and date(b.date) between '$from' and '$to' and b.status='PAID' and service_type='$type' $dt group by b.id)";
        //  echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
        }
      function getOPDetails1($op_from,$op_to,$gen,$dept)
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
        
         function getOPDetailsRef($from,$to,$ref)
        {
            $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT c.name,c.age,c.gender,c.address,c.uhid,c.opd_no,b.date,b.bill_no,sum(b.amount-discount_amount) as amount,sum(b.service_level_discount) as service_level_discount,sum(b.paid_amount) as paid_amount,sum(b.returned_amount) as returned_amount,b.collected_by FROM `billing` b,central_opd c,reference_centers rc where rc.id=b.referal_center_id and rc.id=$ref and c.id=b.patient_id and date(b.date) between '$from' and '$to' and b.status='PAID' group by b.id";
        //  echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
        }
        
        
        
         function getIPDetails($from,$to,$type,$gn)
        {
            $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
           if(strlen($gn)>0)
          {
              $dt="and b.is_generated=$gn";
          }
          else{
              $dt="";
          }
          $sql="SELECT c.ipd_no,c.name,c.age,c.gender,c.address,c.opd_no,b.date,b.bill_no,sum(b.amount) as amount,sum(b.discount_amount) as discount_amount,sum(b.service_level_discount) as service_level_discount,sum(b.paid_amount) as paid_amount,sum(b.outstand_amount) as outstand_amount,sum(b.returned_amount) as returned_amount,b.collected_by,ifnull(sum(adjusted_amount),0) as advance FROM central_ipd c,`billing` b left join adjust_advance a on a.patient_id=b.patient_id and b.bill_no=a.invoice_no where  c.ipd_no=b.patient_id and date(b.date) between '$from' and '$to' and b.status='PAID' and service_type='$type' $dt group by bill_no";
        //  echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
        }
        
        
        function getDept_OP($from,$to,$type)
        {
          $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT d.dept_name,sum(b.amount)as amount,sum(discount_amount) as discount,sum(b.service_level_discount) as service_level_discount,sum(b.paid_amount) as paid_amount,sum(b.returned_amount) as returned_amount,count(b.id) as bill FROM `billing` b,department d where b.dept_id=d.id and date(b.date) between '$from' and '$to' and b.status='PAID' and service_type='$type' group by d.id";
          
          $res=mysqli_query($con,$sql);
          return $res;
        }
        
        
         function getDept_IP($from,$to,$type,$dept)
        {
          $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT d.dept_name,sum(b.amount)as amount,sum(discount_amount) as discount,sum(b.service_level_discount) as service_level_discount,sum(b.paid_amount) as paid_amount,sum(b.returned_amount) as returned_amount,count(b.id) as bill,ifnull(sum(adjusted_amount),0) as advance FROM department d,`billing` b left join adjust_advance a on a.patient_id=b.patient_id and b.bill_no=a.invoice_no where b.dept_id=d.id and date(b.date) between '$from' and '$to' and b.status='PAID' and (d.id='$dept' || '$dept'='') and service_type='$type' group by d.id";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
        }
        
        
        function getDoctorBill($from,$to,$type)
        {
            $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT b.doctor,sum(b.amount)as amount,sum(discount_amount) as discount,sum(b.service_level_discount) as service_level_discount,sum(b.paid_amount) as paid_amount,sum(b.returned_amount) as returned_amount,count(b.id) as bill FROM `billing` b where  date(b.date) between '$from' and '$to' and b.status='PAID' and service_type='$type' group by doctor";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
        }
        
        
         function getDoctorBillIP($from,$to,$type)
        {
            $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT b.doctor,sum(b.amount)as amount,sum(discount_amount) as discount,sum(b.service_level_discount) as service_level_discount,sum(b.paid_amount) as paid_amount,sum(b.returned_amount) as returned_amount,count(b.id) as bill,ifnull(sum(adjusted_amount),0) as advance FROM `billing` b left join adjust_advance a on a.patient_id=b.patient_id and b.bill_no=a.invoice_no where  date(b.date) between '$from' and '$to' and b.status='PAID' and service_type='$type' group by doctor";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
        } 
        
        
        function getServiceReport($from,$to,$type)
        {
           $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT s.name,sum(bd.unit) as unit,sum(bd.price*bd.unit) as price,sum(bd.discount) as service_level_discount,count(bd.id) as bill,sum(b.discount_amount) as discount_amount,sum(b.returned_amount) as returned_amount FROM `billing` b,billing_details bd,service_list s where bd.bill_id=b.id and s.id=bd.service_id and date(b.date) between '$from' and '$to' and bd.status='PAID' and b.status='PAID' and b.service_type='$type' group by bd.service_id";
        //  echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
        }
        
        
        
         function getServiceReportIP($from,$to,$type)
        {
           $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT s.name,sum(bd.unit) as unit,sum(bd.price*bd.unit) as price,sum(bd.discount) as service_level_discount,count(bd.id) as bill FROM `billing` b,billing_details bd,service_list s where bd.bill_id=b.id and s.id=bd.service_id and date(b.date) between '$from' and '$to' and bd.status='PAID' and b.status='PAID' and b.service_type='$type' group by bd.service_id";
        //  echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
        }
        
        
        function getCategoryBill($from,$to,$type)
        {
            $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT b.'' as charge_type,sum(b.amount)as amount,sum(discount_amount) as discount,sum(b.service_level_discount) as service_level_discount,sum(b.paid_amount) as paid_amount,count(b.id) as bill FROM `billing` b where  date(b.date) between '$from' and '$to' and b.status='PAID' and service_type='$type' group by charge_type";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
        }
        
        
        
         function getCategoryBillIP($from,$to,$type)
        {
            $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT b.''  as charge_type,sum(b.amount)as amount,sum(discount_amount) as discount,sum(b.service_level_discount) as service_level_discount,sum(b.paid_amount) as paid_amount,count(b.id) as bill,ifnull(sum(adjusted_amount),0) as advance FROM `billing` b left join adjust_advance a on a.patient_id=b.patient_id and b.bill_no=a.invoice_no where  date(b.date) between '$from' and '$to' and b.status='PAID' and service_type='$type' group by charge_type";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
        }
        
        function getDeptWiseReportOP($from,$to,$type)
        {
          $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT d.dept_name,sum(b.amount)as amount,sum(discount_amount) as discount,sum(b.service_level_discount) as service_level_discount,sum(b.paid_amount) as paid_amount,sum(b.returned_amount) as returned_amount,count(distinct(b.patient_id)) as bill,d.id as dept_id FROM `billing` b,department d where b.dept_id=d.id and date(b.date) between '$from' and '$to' and b.status='PAID' and service_type='$type' group by d.id";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
        }
        
        function getDetailedDeptWiseReportOP($from,$to,$type,$dept_id)
        {
          $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT count(service_id) as service_count,bd.service_id,sl.name,sum(bd.unit*bd.price) as amount,sum(bd.discount) as bd_discount,sum(discount_amount) as discount,sum(b.service_level_discount) as service_level_discount,sum(b.paid_amount) as paid_amount,count(b.id) as bill FROM `billing` b,department d,billing_details bd,service_list sl where bd.bill_id=b.id and bd.service_id=sl.id and b.dept_id=d.id and  b.status='PAID' and bd.status='PAID' and date(b.date) between '$from' and '$to' and b.dept_id='$dept_id' and b.service_type='$type' group by bd.service_id";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
        }
        
         function getDoctorWiseBill($from,$to,$type)
        {
            $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT  group_concat(b.id) as bill_id,b.doctor,sum(b.amount)as amount,sum(discount_amount) as discount,sum(b.service_level_discount) as service_level_discount,sum(b.paid_amount) as paid_amount,sum(b.returned_amount) as returned_amount,count(b.id) as bill FROM `billing` b where  date(b.date) between '$from' and '$to' and b.status='PAID' and service_type='$type' group by doctor";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
        }
        
        
          function getDetailedDoctorWiseBill($bill_id)
        {
            $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT  b.*,c.name FROM billing b,central_opd c where b.status='PAID' and c.id=patient_id and b.id in ($bill_id)";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
        }
        
        
          function getDeptWiseReportIP($from,$to,$type,$dept)
        {
          $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT group_concat(b.id) as bill_id,d.dept_name,sum(b.amount)as amount,sum(discount_amount) as discount,sum(b.service_level_discount) as service_level_discount,sum(b.paid_amount) as paid_amount,sum(b.returned_amount) as returned_amount,count(DISTINCT(b.patient_id)) as bill ,ifnull(sum(adjusted_amount),0) as advance FROM department d,`billing` b left join adjust_advance a on a.patient_id=b.patient_id and b.bill_no=a.invoice_no where  b.dept_id=d.id and (d.id='$dept' || '$dept'='' ) and date(b.date) between '$from' and '$to' and b.status='PAID' and service_type='$type' group by d.id";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
        }
        
        function getDetailedDeptWiseReportIP($bill_id)
        {
          $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT count(DISTINCT(b.patient_id)) as service_count,bd.service_id,sl.name,sum(bd.price) as amount,sum(bd.discount) as bd_discount,sum(discount_amount) as discount,sum(b.service_level_discount) as service_level_discount,sum(b.paid_amount) as paid_amount,count(b.id) as bill FROM `billing` b,department d,billing_details bd,service_list sl where bd.bill_id=b.id and bd.service_id=sl.id and b.dept_id=d.id and  b.status='PAID' and bd.bill_id in ($bill_id) group by bd.service_id";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
        }
        
         function getDoctorWiseBillIP($from,$to,$type)
        {
            $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT  group_concat(b.id) as bill_id,b.doctor,sum(b.amount)as amount,sum(discount_amount) as discount,sum(b.service_level_discount) as service_level_discount,sum(b.paid_amount) as paid_amount,sum(b.returned_amount) as returned_amount,count(b.id) as bill FROM `billing` b where  date(b.date) between '$from' and '$to' and b.status='PAID' and service_type='$type' group by doctor";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
        }
        
        
          function getDetailedDoctorWiseBillIP($bill_id)
        {
            $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT  b.*,c.name FROM billing b,central_ipd c where b.status='PAID' and c.ipd_no=patient_id and b.id in ($bill_id)";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
        }
        function getDoctorShare($from,$to,$doc,$serv)
        {
            $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT concat(d.name,' ',d.last_name) as doctor,s.name,count(patient_id) as 'count',sum(b.unit*b.price) as amount,sum(discount) as discount,sum(CASE WHEN dr.amt_share2doctor>0 then dr.amt_share2doctor else ((b.unit*b.price)*perc_share2doctor)/100 end) as share_amt FROM service_list s,billing bi,doctor d,`billing_details` b LEFT JOIN doctor_revenue dr on  b.service_id=dr.service_id and dr.doctor_id=b.doctor_id where bi.id=b.bill_id and b.doctor_id>0 and s.id=b.service_id and d.id=b.doctor_id and date(bi.date) BETWEEN '$from' and '$to' and bi.is_generated=0   and bi.status='paid' and (d.id='$doc' || '$doc'='') and (s.id='$serv' || '$serv'='') group by s.name,d.id order by doctor";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
        }
        function getDoctorShareDetails($from,$to,$doc,$serv)
        {
            $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="(SELECT concat(d.name,' ',d.last_name) as doctor,s.name,b.unit*b.price as amount,discount as discount,(CASE WHEN dr.amt_share2doctor>0 then dr.amt_share2doctor else ((b.unit*b.price)*perc_share2doctor)/100 end) as share_amt,c.name as patient,c.age,c.gender,c.opd_no as opd,bi.bill_no,bi.date,bi.collected_by,bi.payment_mode FROM service_list s,billing bi,doctor d,central_opd c,`billing_details` b LEFT JOIN doctor_revenue dr on dr.doctor_id=b.doctor_id and b.service_id=dr.service_id where c.id=bi.patient_id and bi.id=b.bill_id and b.doctor_id>0 and s.id=b.service_id and d.id=b.doctor_id and  date(bi.date) BETWEEN '$from' and '$to'  and bi.status='paid' and (d.id='$doc' || '$doc'='') and (s.id='$serv' || '$serv'='') and bi.is_generated=0  and bi.patient_type='OP' group by bill_no,s.name,d.id)
          union all (SELECT concat(d.name,' ',d.last_name) as doctor,s.name,b.unit*b.price as amount,discount as discount,(CASE WHEN dr.amt_share2doctor>0 then dr.amt_share2doctor else ((b.unit*b.price)*perc_share2doctor)/100 end) as share_amt,c.name as patient,c.age,c.gender,c.ipd_no as opd,bi.bill_no,bi.date,bi.collected_by,bi.payment_mode FROM service_list s,billing bi,doctor d,central_ipd c,`billing_details` b LEFT JOIN doctor_revenue dr ON dr.doctor_id=b.doctor_id and b.service_id=dr.service_id where c.ipd_no=bi.patient_id and bi.id=b.bill_id and b.doctor_id>0 and s.id=b.service_id and d.id=b.doctor_id and date(bi.date) BETWEEN '$from' and '$to' and bi.status='paid' and (d.id='$doc' || '$doc'='') and (s.id='$serv' || '$serv'='')  and bi.is_generated=0 and bi.patient_type='IP' group by bill_no,s.name,d.id)order by doctor,date";
        
        //   echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
        }
        function getDoctorCollectionDetails($from,$to,$doc,$serv,$bill_type)
        {
            $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="(SELECT concat(d.name,' ',d.last_name) as doctor,s.name,b.unit*b.price as amount,discount as discount,c.name as patient,c.age,c.gender,c.opd_no as opd,bi.bill_no,bi.date,bi.collected_by,bi.payment_mode FROM `billing_details` b,service_list s,billing bi,doctor d,central_opd c where c.id=bi.patient_id and bi.id=b.bill_id and s.id=b.service_id  and date(bi.date) BETWEEN '$from' and '$to' and bi.status='paid' and (d.id='$doc' || '$doc'='') and (b.service_id='$serv' || '$serv'='') and (service_type='$bill_type' || '$bill_type'='') and bi.doctor=concat(d.name,' ',d.last_name) and bi.is_generated=0 and  bi.patient_type='OP')
          union all (SELECT concat(d.name,' ',d.last_name) as doctor,s.name,b.unit*b.price as amount,discount as discount,c.name as patient,c.age,c.gender,c.ipd_no as opd,bi.bill_no,bi.date,bi.collected_by,bi.payment_mode FROM `billing_details` b,service_list s,billing bi,doctor d,central_ipd c where c.ipd_no=bi.patient_id and bi.id=b.bill_id and s.id=b.service_id  and date(bi.date) BETWEEN '$from' and '$to' and bi.status='paid' and (d.id='$doc' || '$doc'='') and (b.service_id='$serv' || '$serv'='') and (service_type='$bill_type' || '$bill_type'='') and bi.doctor=concat(d.name,' ',d.last_name) and bi.is_generated=0 and bi.patient_type='IP') order by doctor,opd";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
        }
        function doctor()
        {
          $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT * from doctor where status='active'";
          $res=mysqli_query($con,$sql);
          return $res;
        }
        function getServiceList()
        {
            $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="select * from `service_list` where status='active'";
          $res=mysqli_query($con,$sql);
          return $res;
        }
        function department()
        {
            $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT * from department where status='active'";
          $res=mysqli_query($con,$sql);
          return $res;
          
        }
        function allDepartment()
        {
            $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT * from department ";
          $res=mysqli_query($con,$sql);
          return $res;
          
        }
        function getPatientDeptWiseReportIP($id)
        {
            $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT c.name,c.age,c.address,c.gender,c.ipd_no,b.date,b.payment_mode,b.amount,b.service_level_discount,b.discount_amount,b.paid_amount,b.returned_amount,b.bill_no,collected_by ,ifnull(sum(adjusted_amount),0) as advance FROM central_ipd c,`billing` b left join adjust_advance a on a.patient_id=b.patient_id and b.bill_no=a.invoice_no WHERE c.ipd_no=b.patient_id and b.id in ($id) group by b.id";
          $res=mysqli_query($con,$sql);
          return $res;
        }
        function getServiceDetails($from,$to,$serv,$dept=0)
        {
            $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $deptFilter = ($dept > 0) ? " and dept.id='" . intval($dept) . "'" : '';
          $sql="(SELECT s.name,bd.unit*bd.price as amount,discount as discount,c.name as patient,c.age,c.gender,c.opd_no as opd,b.bill_no,b.date,b.collected_by,b.payment_mode,dept.dept_name FROM `billing_details` bd,billing b,service_list s,central_opd c,department dept WHERE c.id=b.patient_id and s.id=bd.service_id and b.id=bd.bill_id and dept.id=b.dept_id and date(b.date) between '$from' and '$to' and bd.status='PAID' and  (s.id='$serv' || '$serv'='') and b.patient_type='OP'$deptFilter) UNION ALL (SELECT s.name,bd.unit*bd.price as amount,discount as discount,c.name as patient,c.age,c.gender,c.ipd_no as opd,b.bill_no,b.date,b.collected_by,b.payment_mode,dept.dept_name FROM `billing_details` bd,billing b,service_list s,central_ipd c,department dept WHERE c.ipd_no=b.patient_id and s.id=bd.service_id and b.id=bd.bill_id and dept.id=b.dept_id and bd.status='PAID' and date(b.date) between '$from' and '$to' and (s.id='$serv' || '$serv'='') and b.patient_type='IP'$deptFilter)";
          
            $res=mysqli_query($con,$sql);
          return $res;
        }
        function getDeptCollectionDetails($from,$to,$dept,$type)
        {
             $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $data='';
          if($dept>0)
          {
              $data .= " and dept.id='$dept'";
          }
          if(strlen($type)>0)
          {
              $data .= " and b.service_type='$type'";
          }
          $sql="(SELECT c.name,c.opd_no as opd,b.bill_no,b.date,b.doctor,b.collected_by,b.payment_mode,c.gender,b.paid_amount,dept.dept_name 
          FROM `billing` b,central_opd c,department dept,billing_details bd WHERE bd.bill_id=b.id and dept.id=b.dept_id and c.id=b.patient_id and date(b.date) BETWEEN '$from' and '$to' $data and bd.service_id=18 and b.service_type='OP' and b.status='PAID')  order by dept_name;";
           
           $res=mysqli_query($con,$sql);
          return $res;
        }
        function getDepartmentCollection($from,$to,$dept,$type)
        {
               $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $data='';
          if($dept>0)
          {
              $data .= " and dept.id='$dept'";
          }
          if(strlen($type)>0)
          {
              $data .= " and b.service_type='$type'";
          }
          $sql="SELECT dept.dept_name,sg.name,COUNT(distinct(b.patient_id)) as 'count',sum(bd.unit*bd.price-bd.discount) as paid,sum(b.discount_amount) as discount,dept.id as dept_id FROM `billing` b,department dept,billing_details bd,service_list s, service_group sg WHERE sg.id=s.service_group_id and s.id=bd.service_id and b.id=bd.bill_id  and dept.id=b.dept_id and date(date) BETWEEN '$from' and '$to' $data and b.service_type in ('OP','IP') and bd.status='PAID' group by dept_name,sg.id order by dept_name";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
        }
        function getDiscount($from,$to,$dept)
        {
                  $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $data='';
          if($dept>0)
          {
              $data .= " and b.dept_id='$dept'";
          }
          $sql="SELECT sum(discount_amount) as discount_amount,sum(adjusted_amount) as adjusted_amount FROM `billing` b left JOIN adjust_advance a on a.patient_id=b.patient_id and a.invoice_no=b.bill_no where date(b.date) BETWEEN '$from' and '$to' $data";
            $res=mysqli_query($con,$sql);
          return $res;       
        }
        function getDepartmentAdjustedAdvance($from,$to,$dept)
        {
            $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT sum(adjusted_amount) as adjusted_amount,sum(discount_amount) as discount_amount FROM `billing` b left join adjust_advance a on a.patient_id=b.patient_id and b.bill_no=a.invoice_no WHERE date(b.date) BETWEEN '$from' and '$to' and b.dept_id='$dept' and b.service_type in ('IP') and b.status='PAID'";
        
        $res=mysqli_query($con,$sql);
          return $res;
            
        }
        
        function getRefCenter()
        {
          $newlogin = new userlogin();
          $con = $newlogin->dbconnect();
          $sql = "SELECT * FROM reference_centers";
          $res = mysqli_query($con, $sql);
          return $res;
        }
        
        function getRefCenterBill($from,$to,$id)
        {
          $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          if($id!=0)
          {
              $rc=" and rc.id=$id ";
          }
          else
          {
              $rc=" and rc.id like '%' ";
          }
          $sql="SELECT rc.id as ref_id,rc.name as ref_center,b.date,sum(b.amount-discount_amount) as amount,sum(b.service_level_discount) as service_level_discount,sum(b.paid_amount) as paid_amount,sum(b.returned_amount) as returned_amount,b.collected_by FROM `billing` b,central_opd c,reference_centers rc where rc.id=b.referal_center_id and c.id=b.patient_id and date(b.date) between '$from' and '$to' and b.status='PAID'  $rc group by rc.id";
        //  echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
        }
         function getBillListRadiology($from,$to,$type,$doc)
        {
            $newlogin = new userlogin;
            $con=$newlogin->dbconnect();
            $data='';
           
                $data .=" date(b.`date`) between '$from' and '$to' and s.service_group_id='$type' and b.doctor like '$doc%'";
            
            // if($_SESSION['role']!='Admin')
            // {
            //     $data.=" and b.collected_by like '%$_SESSION[name]%'"; 
            // }
            $sql="(select b.returned_amount,sum(bd.price * bd.unit) as gt, sum(bd.discount) as discount,sum(bd.price) as price_tot,sum(bd.unit) as unit_tot, b.outstand_amount as outstand,b.discount_amount as b_discount,b.status,b.bill_no,d.dept_name,GROUP_CONCAT(s.name SEPARATOR '<br/><br/>') as service, GROUP_CONCAT(bd.`price` SEPARATOR '<br/><br/>') as price,GROUP_CONCAT(bd.`unit` SEPARATOR '<br/><br/>') as unit,b.patient_id,b.patient_type,c.opd_no,c.name,c.age,c.gender,b.date,b.collected_by,b.id as bill_id,b.paid_amount,b.doctor from `billing` b, billing_details bd, central_opd c,department d,service_list s where d.id=c.dept_id and b.patient_id=c.id and b.id=bd.bill_id and bd.service_id=s.id and b.service_type='radiology' and $data group by c.id,b.id)
            UNION ALL (select b.returned_amount,sum(bd.price * bd.unit) as gt, sum(bd.discount) as discount,sum(bd.price) as price_tot,sum(bd.unit) as unit_tot, b.outstand_amount as outstand,b.discount_amount as b_discount,b.status,b.bill_no,d.dept_name,GROUP_CONCAT(s.name SEPARATOR '<br/><br/>') as service, GROUP_CONCAT(bd.`price` SEPARATOR '<br/><br/>') as price,GROUP_CONCAT(bd.`unit` SEPARATOR '<br/><br/>') as unit,b.patient_id,b.patient_type,c.ipd_no as opd_no,c.name,c.age,c.gender,b.date,b.collected_by,b.id as bill_id,b.paid_amount,b.doctor from `billing` b, billing_details bd, central_ipd c,department d,service_list s where d.id=c.dept_id and b.patient_id=c.ipd_no and b.id=bd.bill_id and bd.service_id=s.id and b.service_type='radiology' and $data group by c.id,b.id)";
            // echo $sql;
            // exit;
            $result=mysqli_query($con,$sql);
            mysqli_close($con);
            return $result;
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
          $sql="SELECT c.*,d.dept_name,di.discharge_date,concat(c.doctor) as doctor_name,u.unit,di.advice_discharge,co.uhid,b.bed_number,b.ward,di.created_by as dis_by FROM  central_opd co,`department` d,`central_ipd` c LEFT JOIN `discharge` di ON di.patient_id=c.ipd_no LEFT JOIN `doctor` u on concat(u.name,' ',u.last_name)=c.doctor and c.dept_id=u.department  left join bed b on b.id=c.bed_id  WHERE co.opd_no=c.opd_no and d.id=c.dept_id and date(c.`admission_date`) between '$ip_from' and '$ip_to 23:59:59' and c.gender like '$gender%' and c.is_generated like '$gen%' $cond group by c.ipd_no order by c.ipd_no";
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
       
          $sql="(SELECT c.id,c.admission_date,c.ipd_no,c.name,c.gender,c.age,c.address,d.dept_name,dis.discharge_date,b.bed_number,c.is_generated,b.ward,c.patient_type from department d,bed b,central_ipd c left JOIN discharge dis on c.ipd_no=dis.patient_id WHERE b.id=c.bed_id and b.ward not like '%ICU%' and d.id=c.dept_id and c.discharge=0 and DATE(c.admission_date)<='$date'and c.is_generated like '$gen%' $cond) 
          UNION (SELECT c.id,c.admission_date,c.ipd_no,c.name,c.gender,c.age,c.address,d.dept_name,dis.discharge_date,b.bed_number,c.is_generated,b.ward,c.patient_type from department d,bed b,central_ipd c, discharge dis WHERE b.id=c.bed_id and d.id=c.dept_id and b.ward not like '%ICU%' and date(c.admission_date) <= '$date' AND c.ipd_no=dis.patient_id and DATE(dis.discharge_date) > '$date' and c.is_generated like '$gen%' $cond)
          UNION (SELECT c.id,c.admission_date,c.ipd_no,c.name,c.gender,c.age,c.address,d.dept_name,dis.discharge_date,b.bed_number,c.is_generated,b.ward,c.patient_type from department d,bed b,central_ipd c left JOIN discharge dis on c.ipd_no=dis.patient_id WHERE b.id=c.bed_id and b.ward like '%ICU%' and d.id=c.dept_id and c.discharge=0 and DATE(c.admission_date)<='$date'and c.is_generated like '$gen%' $cond) 
          UNION (SELECT c.id,c.admission_date,c.ipd_no,c.name,c.gender,c.age,c.address,d.dept_name,dis.discharge_date,b.bed_number,c.is_generated,b.ward,c.patient_type from department d,bed b,central_ipd c, discharge dis WHERE b.id=c.bed_id and d.id=c.dept_id and b.ward like '%ICU%' and date(c.admission_date) <= '$date' AND c.ipd_no=dis.patient_id and DATE(dis.discharge_date) > '$date' and c.is_generated like '$gen%' $cond)order by admission_date,id ";
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
     function getBillCancellationReport($from, $to, $service_type = '', $search = '')
   {
       $newlogin = new userlogin;
       $con = $newlogin->dbconnect();
       
       $where_op = "DATE(b.date) BETWEEN '$from' AND '$to' AND b.status='CANCELLED'";
       $where_ip = "DATE(b.date) BETWEEN '$from' AND '$to' AND b.status='CANCELLED'";
       
       if(!empty($service_type)) {
           $where_op .= " AND b.service_type='$service_type'";
           $where_ip .= " AND b.service_type='$service_type'";
       }
       
       if(!empty($search)) {
           $where_op .= " AND (c.opd_no LIKE '%$search%' OR c.name LIKE '%$search%' OR b.bill_no LIKE '%$search%')";
           $where_ip .= " AND (i.ipd_no LIKE '%$search%' OR i.name LIKE '%$search%' OR b.bill_no LIKE '%$search%')";
       }
       
       if($_SESSION['role'] != 'Admin' && $_SESSION['role'] != 'Accountant') {
           $where_op .= " AND b.collected_by='$_SESSION[name]'";
           $where_ip .= " AND b.collected_by='$_SESSION[name]'";
       }
       
       $sql = "(SELECT 
           b.id,
           b.bill_no,
           b.date,
           b.service_type,
           b.patient_type,
           c.opd_no,
           '' as ipd_no,
           c.name,
           c.age,
           c.gender,
           c.mobile,
           b.amount as bill_amount,
           b.paid_amount,
           b.discount_amount,
           b.returned_amount,
           b.cancellation_note,
           b.collected_by,
           b.updated_by,
            b.returned_by,
           b.returned_at,
           b.updated_at as cancelled_at,
           GROUP_CONCAT(DISTINCT s.name SEPARATOR ', ') as services,
           SUM(bd.price * bd.unit) as gross_total,
           SUM(bd.discount) as service_discount
       FROM billing b
       INNER JOIN central_opd c ON b.patient_id = c.id
       LEFT JOIN billing_details bd ON b.id = bd.bill_id
       LEFT JOIN service_list s ON bd.service_id = s.id
       WHERE $where_op
       GROUP BY b.id, b.bill_no)
       UNION ALL
       (SELECT 
           b.id,
           b.bill_no,
           b.date,
           b.service_type,
           b.patient_type,
           i.opd_no,
           i.ipd_no,
           i.name,
           i.age,
           i.gender,
           i.mobile,
           b.amount as bill_amount,
           b.paid_amount,
           b.discount_amount,
           b.returned_amount,
           b.cancellation_note,
           b.collected_by,
           b.updated_by,
            b.returned_by,
           b.returned_at,
           b.updated_at as cancelled_at,
           GROUP_CONCAT(DISTINCT s.name SEPARATOR ', ') as services,
           SUM(bd.price * bd.unit) as gross_total,
           SUM(bd.discount) as service_discount
       FROM billing b
       INNER JOIN central_ipd i ON b.patient_id = i.ipd_no
       LEFT JOIN billing_details bd ON b.id = bd.bill_id
       LEFT JOIN service_list s ON bd.service_id = s.id
       WHERE $where_ip
       GROUP BY b.id, b.bill_no)
       ORDER BY date DESC, bill_no DESC";
    //   echo $sql;
    //   exit;
       $res = mysqli_query($con, $sql);
       return $res;
   }
   
   function getBillCancellationSummary($from, $to, $service_type = '')
   {
       $newlogin = new userlogin;
       $con = $newlogin->dbconnect();
       
       $where = "DATE(date) BETWEEN '$from' AND '$to' AND status='CANCELLED'";
       
       if(!empty($service_type)) {
           $where .= " AND service_type='$service_type'";
       }
       
       if($_SESSION['role'] != 'Admin' && $_SESSION['role'] != 'Accountant') {
           $where .= " AND collected_by='$_SESSION[name]'";
       }
       
       $sql = "SELECT 
           service_type,
           COUNT(DISTINCT bill_no) as total_bills,
           SUM(amount) as total_amount,
           SUM(discount_amount) as total_discount,
           SUM(paid_amount) as total_paid,
           SUM(returned_amount) as total_returned
       FROM billing
       WHERE $where
       GROUP BY service_type
       ORDER BY service_type";
       
       $res = mysqli_query($con, $sql);
       return $res;
   }
function getIPBalanceReport($from, $to, $search = '')
   {
       $newlogin = new userlogin;
       $con = $newlogin->dbconnect();
       
       $where = "DATE(b.date) BETWEEN '$from' AND '$to'";
       
       if(!empty($search)) {
           $where .= " AND (i.ipd_no LIKE '%$search%' OR i.name LIKE '%$search%')";
       }
       
       $sql = "SELECT 
           i.ipd_no,
           i.name,
           i.age,
           i.gender,
           i.admission_date,
           i.opd_no,
           date(b.date) as date,
           group_concat(bill_no separator ',<br>') as bill_no,
           SUM(CASE WHEN b.service_type='IP' THEN b.amount ELSE 0 END) as ip_bills,
           SUM(CASE WHEN b.service_type='IP' THEN b.paid_amount ELSE 0 END) as ip_receipt,
           SUM(CASE WHEN b.service_type='IP' THEN aa.adjusted_amount ELSE 0 END) as ip_adjusted,
           SUM(CASE WHEN b.service_type='Pathology' THEN b.amount ELSE 0 END) as lab_bills,
           SUM(CASE WHEN b.service_type='Pathology' THEN b.paid_amount ELSE 0 END) as lab_receipt,
           
           SUM(CASE WHEN b.service_type='Radiology' THEN b.amount ELSE 0 END) as radio_bills,
           SUM(CASE WHEN b.service_type='Radiology' THEN b.paid_amount ELSE 0 END) as radio_receipt,
           
           SUM(CASE WHEN b.service_type='dialysis' THEN b.amount ELSE 0 END) as dialysis_bills,
           SUM(CASE WHEN b.service_type='dialysis' THEN b.paid_amount ELSE 0 END) as dialysis_receipt,
           
           SUM(CASE WHEN b.service_type='Blood Bank' THEN b.amount ELSE 0 END) as blood_bills,
           SUM(CASE WHEN b.service_type='Blood Bank' THEN b.paid_amount ELSE 0 END) as blood_receipt,
      
           
           SUM(b.amount) as total_bills,
           SUM(b.paid_amount+b.discount_amount+b.service_level_discount + IFNULL(aa.adjusted_amount, 0)) as total_receipt,
           SUM(b.outstand_amount) as balance,
           
           i.patient_type as scheme
       FROM central_ipd i
       JOIN billing b ON i.ipd_no = b.patient_id AND b.status='PAID'
       LEFT JOIN adjust_advance aa ON aa.invoice_no = b.bill_no AND aa.patient_id = i.ipd_no
       WHERE $where
       GROUP BY i.ipd_no
       HAVING total_bills > total_receipt

       ORDER BY b.date, i.ipd_no";
    //   echo $sql;
       $res = mysqli_query($con, $sql);
       return $res;
   }

function misreport1($from,$to)
   {
       $newlogin = new userlogin;
       $con = $newlogin->dbconnect();
       $from = mysqli_real_escape_string($con, $from);
       $to = mysqli_real_escape_string($con, $to);

       $data = array();

       $sql = "SELECT d.dept_name, COUNT(DISTINCT c.id) AS data
               FROM department d
               LEFT JOIN central_opd c ON c.dept_id = d.id AND DATE(c.date) BETWEEN '$from' AND '$to' 
               AND c.is_generated=0  AND doctor not in (76)
              --  INNER JOIN billing b ON b.patient_id = c.id AND b.service_type='OP' AND b.status='PAID'
               WHERE d.status='active'
               GROUP BY d.id, d.dept_name
               ORDER BY d.dept_name";
       $res = mysqli_query($con, $sql);
       while($row = mysqli_fetch_assoc($res)) {
           $data[$row['dept_name']]['OP'] = (int)$row['data'];
       }

       $sql1 = "SELECT d.dept_name, IFNULL(SUM(ip_data.occ_days), 0) AS bed
                FROM department d
                LEFT JOIN (
                    SELECT dept_id, SUM(occ_days) AS occ_days
                    FROM (
                        SELECT dis.dept_id,
                               SUM(DATEDIFF(
                                   LEAST(DATE(dis.discharge_date), DATE_ADD('$to', INTERVAL 1 DAY)),
                                   GREATEST(DATE(dis.admission_date), '$from')
                               )) AS occ_days
                        FROM discharge dis
                        INNER JOIN central_ipd c ON c.ipd_no = dis.patient_id and is_generated=0
                        WHERE DATE(dis.discharge_date) > '$from'
                          AND DATE(dis.admission_date) <= '$to'
                        GROUP BY dis.dept_id

                        UNION ALL

                        SELECT i.dept_id,
                               SUM(DATEDIFF(
                                   DATE_ADD('$to', INTERVAL 1 DAY),
                                   GREATEST(DATE(i.admission_date), '$from')
                               )) AS occ_days
                        FROM central_ipd i
                        WHERE i.discharge = 0
                          AND DATE(i.admission_date) <= '$to'
                          and is_generated=0
                        GROUP BY i.dept_id
                    ) occ_union
                    GROUP BY dept_id
                ) ip_data ON ip_data.dept_id = d.id
                WHERE d.status='active' AND d.dept_type='both'
                GROUP BY d.id, d.dept_name
                ORDER BY FIELD(d.id, '3','4','7','6','5','2','11','8','1','9','10')";
       $res2 = mysqli_query($con, $sql1);
       while($row1 = mysqli_fetch_assoc($res2)) {
           $data[$row1['dept_name']]['IP'] = (int)$row1['bed'];
       }

       $sql2 = "SELECT dept_name, SUM(data) AS ot
                FROM (
                    SELECT d.dept_name, COUNT(o.id) AS data
                    FROM operation_theater o
                    INNER JOIN central_opd c ON c.id = o.patient_id
                    INNER JOIN department d ON d.id = c.dept_id
                    WHERE DATE(o.date) BETWEEN '$from' AND '$to'
                    and c.is_generated=0
                    GROUP BY d.id, d.dept_name

                    UNION ALL

                    SELECT d.dept_name, COUNT(o.id) AS data
                    FROM operation_theater o
                    INNER JOIN central_ipd c ON c.ipd_no = o.patient_id
                    INNER JOIN department d ON d.id = c.dept_id
                    WHERE DATE(o.date) BETWEEN '$from' AND '$to'
                    and c.is_generated=0
                    GROUP BY d.id, d.dept_name
                ) data1
                GROUP BY dept_name";
       $res3 = mysqli_query($con, $sql2);
       while($row2 = mysqli_fetch_assoc($res3)) {
           $data[$row2['dept_name']]['OT'] = (int)$row2['ot'];
       }

       return $data;
   }
   function misreport2($from,$to)
   {
       $newlogin = new userlogin;
       $con = $newlogin->dbconnect();
       $from = mysqli_real_escape_string($con, $from);
       $to = mysqli_real_escape_string($con, $to);

       $sql = "SELECT service_type, SUM(amount) AS amount
FROM (
    SELECT 
        b.id,
        CASE 
            WHEN b.service_type = 'OP' AND bd.service_id != 18 THEN 'SLIP' 
            ELSE b.service_type 
        END AS service_type,
        b.paid_amount AS amount
    FROM billing b
    INNER JOIN billing_details bd ON b.id = bd.bill_id
    WHERE DATE(b.date) BETWEEN '$from' AND '$to'
      AND b.status = 'PAID' 
    GROUP BY b.id
) x
GROUP BY service_type
ORDER BY FIELD(service_type,'OP','IP','SLIP','Pathology','Radiology','Dialysis','Blood Bank')";

       $res = mysqli_query($con, $sql);
       return $res;
   }
    function misreport3($from,$to)
   {
       $newlogin = new userlogin;
       $con = $newlogin->dbconnect();
       $from = mysqli_real_escape_string($con, $from);
       $to = mysqli_real_escape_string($con, $to);

       $sql = "SELECT COUNT(c.id) AS data, d.dept_name, CONCAT(doc.name,' ',doc.last_name) AS doctor
               FROM central_opd c
               INNER JOIN department d ON d.id = c.dept_id
               INNER JOIN doctor doc ON doc.id = c.doctor and doc.id not in (76)
               WHERE DATE(c.date) BETWEEN '$from' AND '$to' and c.is_generated=0
               GROUP BY d.id, d.dept_name, doc.id, doc.name, doc.last_name
               ORDER BY dept_name,doctor";
       $res = mysqli_query($con, $sql);
       return $res;
   }
   function misreport4($from,$to)
   {
       $newlogin = new userlogin;
       $con = $newlogin->dbconnect();
       $from = mysqli_real_escape_string($con, $from);
       $to = mysqli_real_escape_string($con, $to);

       $data = array();
       $sql = "SELECT b.ward, IFNULL(SUM(room_data.occ_days), 0) AS bed
               FROM bed b
               LEFT JOIN (
                   SELECT bed_id, SUM(occ_days) AS occ_days
                   FROM (
                       SELECT c.bed_id,
                              SUM(DATEDIFF(
                                  LEAST(DATE(dis.discharge_date), DATE_ADD('$to', INTERVAL 1 DAY)),
                                  GREATEST(DATE(dis.admission_date), '$from')
                              )) AS occ_days
                       FROM discharge dis
                       INNER JOIN central_ipd c ON c.ipd_no = dis.patient_id and c.is_generated=0
                       WHERE DATE(dis.discharge_date) > '$from'
                         AND DATE(dis.admission_date) <= '$to' 
                       GROUP BY c.bed_id

                       UNION ALL

                       SELECT i.bed_id,
                              SUM(DATEDIFF(
                                  DATE_ADD('$to', INTERVAL 1 DAY),
                                  GREATEST(DATE(i.admission_date), '$from')
                              )) AS occ_days
                       FROM central_ipd i
                       WHERE i.discharge = 0
                         AND DATE(i.admission_date) <= '$to' and i.is_generated=0
                       GROUP BY i.bed_id
                   ) room_union
                   GROUP BY bed_id
               ) room_data ON room_data.bed_id = b.id
               GROUP BY b.ward
               ORDER BY b.ward";
       $res = mysqli_query($con, $sql);
       while($row = mysqli_fetch_assoc($res)) {
           $data[$row['ward']] = (int)$row['bed'];
       }
       return $data;
   }
}
