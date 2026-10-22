<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/dbconnect.php');
//   print_r($_REQUEST);exit;


  $newlogin = new userlogin;                  
  $con=$newlogin->dbconnect();
  $week=date("l");

// $list=array();

$new = array('150','150','150','150','150','150','150');
$old = array('50','50','50','50','50','50','50');
exit;
$month = 5;
$year = 2025;

// for($d=1; $d<=15; $d++)
for($d=23; $d<=31; $d++)
{
    $time=mktime(12, 0, 0, $month, $d, $year);          
    if (date('m', $time)==$month)       
        $list[]=date('Y-m-d', $time);
}


for($count=0;$count< count($list); $count++)
{

 $date=$list[$count];
  $oldpatient=$old[$count];
$data=$new[$count];
//  echo "<br> $date old=$oldpatient new=$data <br>";
//  exit;
$pd=round(($data * 8) / 100); //Pediatrics
$ps=ceil(($data * 4) / 100);    //Psychiatry
$sk=ceil(($data * 5) / 100);    //Skin
$tr=round(($data * 0) / 100);   // TBCD 3
$or=round(($data * 15) / 100);  //ortho
$op=ceil(($data * 7) / 100);    //ophto
$og=floor(($data * 11) / 100);  //obg
$dn=floor(($data * 3) / 100);   //dental
$sg=ceil(($data * 18) / 100);   //general surgery
$en=ceil(($data * 5) / 100);    //ent
$me=ceil(($data * 22) / 100);   //genral Medicine
$ph=ceil(($data * 0) / 100); //Physiotherapy 
$em=floor(($data * 2) / 100); //Emergency 

if($oldpatient < $sg)
{
    $big=$sg;
}
else{
    $big=$oldpatient;
}
$sn=0;$j=1;
for($i=1;$i<=$big;$i++)
{
   if($i<=$pd){
        $sql="SELECT id,name,age,gender,caste,occupation FROM patient_data p WHERE age < 14 ORDER BY RAND() LIMIT 1";
        // echo $sql;exit;
        $res=mysqli_query($con,$sql);
       
        while($row=mysqli_fetch_assoc($res))
       {
           
            $sql1="SELECT d.address,d.diagnosis,d.dept,st.doctor_id,st.dept_id,st.day,d.age FROM temp d LEFT JOIN staff_timetable st on st.dept_id=d.dept and st.day='$week' WHERE d.sex='$row[gender]' and d.dept =2 ORDER BY RAND() limit 1";
          
        $res1=mysqli_query($con,$sql1);
            $row1=mysqli_fetch_assoc($res1);
           $query="SELECT * FROM data_table WHERE id=1 || id=4 order by id";
           $iddata=mysqli_query($con,$query);
           
           while($central_opd=mysqli_fetch_assoc($iddata)){
               if($central_opd['id']==1)
               {
                      $opd_no=$central_opd['table_id']+1;
               }
               elseif($central_opd['id']==4)
               {
                   $uhid=$central_opd['table_id']+1;
               }
        
           }
          $y=date("Ymd",strtotime($date));
           if($y!=substr($opd_no,0,8) || $y!=substr($uhid,0,8))
        {
             $data="SELECT SUBSTR(MAX(opd_no),3,12) as opd,SUBSTR(MAX(uhid),4,12) as uhid FROM central_opd where opd_no like '__$y%'";
             $op_no=mysqli_fetch_assoc(mysqli_query($con,$data));
             if(isset($op_no['opd'])){                         
                 $opd_no=$op_no['opd']+1;                         
                 $uhid=$op_no['uhid']+1;                     
                 
             }                     
             else{                        
                 $uhid=$opd_no=$y.'0001';                     
                 
             }
            
        }
        // echo $opd_no;exit;
           $insert="INSERT INTO `central_opd`(`uhid`,`opd_no`,`date`, `name`, `age`, `gender`,`occupation`, `caste`, `address`, `diagnosis`, `dept_id`,`doctor`) VALUES ('AHC$uhid','OP$opd_no','$date','$row[name]','$row1[age]','$row[gender]','$row[occupation]','$row[caste]','$row1[address]','$row1[diagnosis]','$row1[dept]','$row1[doctor_id]')";
        $res2=mysqli_query($con,$insert);
        
        
        if(mysqli_insert_id($con)>0)
        {
        $query3="UPDATE data_table set table_id=$opd_no WHERE id=1";
        mysqli_query($con,$query3);
        $query4="UPDATE data_table set table_id=$uhid WHERE id=4";
        mysqli_query($con,$query4);
        }
        $sn++;
       }
    }
    if($i<=$og){
       
        $sql="SELECT id,name,age,gender,caste,occupation FROM patient_data p WHERE age > 18 and gender='female' ORDER BY RAND() LIMIT 1";
        $res=mysqli_query($con,$sql);
       
        while($row=mysqli_fetch_assoc($res))
       {
           
            $sql1="SELECT d.address,d.diagnosis,d.dept,st.doctor_id,st.dept_id,st.day,d.age FROM temp d LEFT JOIN staff_timetable st on st.dept_id=d.dept and st.day='$week' WHERE d.sex='$row[gender]' and d.dept =12 ORDER BY RAND() limit 1";
          
        $res1=mysqli_query($con,$sql1);
            $row1=mysqli_fetch_assoc($res1);
           $query="SELECT * FROM data_table WHERE id=1 || id=4 order by id";
           $iddata=mysqli_query($con,$query);
           
           while($central_opd=mysqli_fetch_assoc($iddata)){
               if($central_opd['id']==1)
               {
                      $opd_no=$central_opd['table_id']+1;
               }
               elseif($central_opd['id']==4)
               {
                   $uhid=$central_opd['table_id']+1;
               }
        
           }
         $y=date("Ymd",strtotime($date));

           if($y!=substr($opd_no,0,8) || $y!=substr($uhid,0,8))
        {
            $data="SELECT SUBSTR(MAX(opd_no),3,12) as opd,SUBSTR(MAX(uhid),4,12) as uhid FROM central_opd where opd_no like '__$y%'";
                    $op_no=mysqli_fetch_assoc(mysqli_query($con,$data));
                    if(isset($op_no['opd']))
                    {
                        $opd_no=$op_no['opd']+1;
                        $uhid=$op_no['uhid']+1;
                    }
                    else{
                                             
                                 $uhid=$opd_no=$y.'0001';                     
                                 
                             
                    }
            
        }
     
           $insert="INSERT INTO `central_opd`(`uhid`,`opd_no`,`date`, `name`, `age`, `gender`,`occupation`, `caste`, `address`, `diagnosis`, `dept_id`,`doctor`) VALUES ('AHC$uhid','OP$opd_no','$date','$row[name]','$row1[age]','$row[gender]','$row[occupation]','$row[caste]','$row1[address]','$row1[diagnosis]','$row1[dept]','$row1[doctor_id]')";
        $res2=mysqli_query($con,$insert);
        
        
        
        if(mysqli_insert_id($con)>0)
        {
        $query3="UPDATE data_table set table_id=$opd_no WHERE id=1";
        mysqli_query($con,$query3);
        $query4="UPDATE data_table set table_id=$uhid WHERE id=4";
        mysqli_query($con,$query4);
        }
        $sn++;
       }
    }
    if($i<=$sg){
       
        $sql="SELECT id,name,age,gender,caste,occupation FROM patient_data p  WHERE age > 11 ORDER BY RAND() LIMIT 1";
        $res=mysqli_query($con,$sql);
       
        while($row=mysqli_fetch_assoc($res))
       {
           
            $sql1="SELECT d.address,d.diagnosis,d.dept,st.doctor_id,st.dept_id,st.day,d.age FROM temp d LEFT JOIN staff_timetable st on st.dept_id=d.dept and st.day='$week' WHERE d.sex='$row[gender]' and d.dept =6 ORDER BY RAND() limit 1";
          
        $res1=mysqli_query($con,$sql1);
            $row1=mysqli_fetch_assoc($res1);
            $query="SELECT * FROM data_table WHERE id=1 || id=4 order by id";
           $iddata=mysqli_query($con,$query);
           
           while($central_opd=mysqli_fetch_assoc($iddata)){
               if($central_opd['id']==1)
               {
                      $opd_no=$central_opd['table_id']+1;
               }
               elseif($central_opd['id']==4)
               {
                   $uhid=$central_opd['table_id']+1;
               }
        
           }
         $y=date("Ymd",strtotime($date));
           if($y!=substr($opd_no,0,8) || $y!=substr($uhid,0,8))
        {
            $data="SELECT SUBSTR(MAX(opd_no),3,12) as opd,SUBSTR(MAX(uhid),4,12) as uhid FROM central_opd where opd_no like '__$y%'";
                    $op_no=mysqli_fetch_assoc(mysqli_query($con,$data));
                    if(isset($op_no['opd']))
                    {
                        $opd_no=$op_no['opd']+1;
                        $uhid=$op_no['uhid']+1;
                    }
                    else{
                                              
                             $uhid=$opd_no=$y.'0001';                    
                             
                    }
            
        }
        // echo $opd_no;exit;
           $insert="INSERT INTO `central_opd`(`uhid`,`opd_no`,`date`, `name`, `age`, `gender`,`occupation`, `caste`, `address`, `diagnosis`, `dept_id`,`doctor`) VALUES ('AHC$uhid','OP$opd_no','$date','$row[name]','$row1[age]','$row[gender]','$row[occupation]','$row[caste]','$row1[address]','$row1[diagnosis]','$row1[dept]','$row1[doctor_id]')";
        $res2=mysqli_query($con,$insert);
        
        
        
        if(mysqli_insert_id($con)>0)
        {
        $query3="UPDATE data_table set table_id=$opd_no WHERE id=1";
        mysqli_query($con,$query3);
        $query4="UPDATE data_table set table_id=$uhid WHERE id=4";
        mysqli_query($con,$query4);
        }
        $sn++;
       }
    }
    if($i<=$dn){
        
        $sql="SELECT id,name,age,gender,caste,occupation FROM patient_data p  WHERE age between 11 and 50 ORDER BY RAND() LIMIT 1";
        $res=mysqli_query($con,$sql);
       
        while($row=mysqli_fetch_assoc($res))
      {
           
            $sql1="SELECT d.address,d.diagnosis,d.dept,st.doctor_id,st.dept_id,st.day FROM daignosis_data d LEFT JOIN staff_timetable st on st.dept_id=d.dept and st.day='$week' WHERE d.dept =8 ORDER BY RAND() limit 1";
          
        $res1=mysqli_query($con,$sql1);
            $row1=mysqli_fetch_assoc($res1);
          $query="SELECT * FROM data_table WHERE id=1 || id=4 order by id";
           $iddata=mysqli_query($con,$query);
           
           while($central_opd=mysqli_fetch_assoc($iddata)){
               if($central_opd['id']==1)
               {
                      $opd_no=$central_opd['table_id']+1;
               }
               elseif($central_opd['id']==4)
               {
                   $uhid=$central_opd['table_id']+1;
               }
        
           }
          $y=date("Ymd",strtotime($date));
           if($y!=substr($opd_no,0,8) || $y!=substr($uhid,0,8))
        {
            $data="SELECT SUBSTR(MAX(opd_no),3,12) as opd,SUBSTR(MAX(uhid),4,12) as uhid FROM central_opd where opd_no like '__$y%'";
                    $op_no=mysqli_fetch_assoc(mysqli_query($con,$data));
                    if(isset($op_no['opd']))
                    {
                        $opd_no=$op_no['opd']+1;
                        $uhid=$op_no['uhid']+1;
                    }
                    else{
                                             
                             $uhid=$opd_no=$y.'0001';                     
                             
                         
                    }
            
        }
        // echo $opd_no;exit;
           $insert="INSERT INTO `central_opd`(`uhid`,`opd_no`,`date`, `name`, `age`, `gender`,`occupation`, `caste`, `address`,  `dept_id`,`doctor`) VALUES ('AHC$uhid','OP$opd_no','$date','$row[name]','$row[age]','$row[gender]','$row[occupation]','$row[caste]','$row1[address]','$row1[dept]','$row1[doctor_id]')";
        $res2=mysqli_query($con,$insert);
        
        
        
        if(mysqli_insert_id($con)>0)
        {
        $query3="UPDATE data_table set table_id=$opd_no WHERE id=1";
        mysqli_query($con,$query3);
        $query4="UPDATE data_table set table_id=$uhid WHERE id=4";
        mysqli_query($con,$query4);
        }
        $sn++;
      }
    }
    if($i<=$ph){
        
        $sql="SELECT id,name,age,gender,caste,occupation FROM patient_data p  WHERE age between 18 and 50 ORDER BY RAND() LIMIT 1";
        $res=mysqli_query($con,$sql);
       
        while($row=mysqli_fetch_assoc($res))
      {
           
            $sql1="SELECT d.address,d.diagnosis,d.dept,st.doctor_id,st.dept_id,st.day FROM daignosis_data  d LEFT JOIN staff_timetable st on st.dept_id=d.dept and st.day='$week' WHERE  d.dept =20 ORDER BY RAND() limit 1";
          
        $res1=mysqli_query($con,$sql1);
            $row1=mysqli_fetch_assoc($res1);
          $query="SELECT * FROM data_table WHERE id=1 || id=4 order by id";
           $iddata=mysqli_query($con,$query);
           
           while($central_opd=mysqli_fetch_assoc($iddata)){
               if($central_opd['id']==1)
               {
                      $opd_no=$central_opd['table_id']+1;
               }
               elseif($central_opd['id']==4)
               {
                   $uhid=$central_opd['table_id']+1;
               }
        
           }
          $y=date("Ymd",strtotime($date));
           if($y!=substr($opd_no,0,8) || $y!=substr($uhid,0,8))
        {
            $data="SELECT SUBSTR(MAX(opd_no),3,12) as opd,SUBSTR(MAX(uhid),4,12) as uhid FROM central_opd where opd_no like '__$y%'";
                    $op_no=mysqli_fetch_assoc(mysqli_query($con,$data));
                    if(isset($op_no['opd']))
                    {
                        $opd_no=$op_no['opd']+1;
                        $uhid=$op_no['uhid']+1;
                    }
                    else{
                                               
                             $uhid=$opd_no=$y.'0001';                     
                             
                         
                    }
            
        }
        // echo $opd_no;exit;
           $insert="INSERT INTO `central_opd`(`uhid`,`opd_no`,`date`, `name`, `age`, `gender`,`occupation`, `caste`, `address`,  `dept_id`,`doctor`) VALUES ('AHC$uhid','OP$opd_no','$date','$row[name]','$row[age]','$row[gender]','$row[occupation]','$row[caste]','$row1[address]','$row1[dept]','$row1[doctor_id]')";
        $res2=mysqli_query($con,$insert);
        
        
        
        if(mysqli_insert_id($con)>0)
        {
        $query3="UPDATE data_table set table_id=$opd_no WHERE id=1";
        mysqli_query($con,$query3);
        $query4="UPDATE data_table set table_id=$uhid WHERE id=4";
        mysqli_query($con,$query4);
        }
        $sn++;
      }
    }
    if($i<=$sk){
        $sql="SELECT id,name,age,gender,caste,occupation FROM patient_data p  WHERE age > 11 ORDER BY RAND() LIMIT 1";
        $res=mysqli_query($con,$sql);
       
        while($row=mysqli_fetch_assoc($res))
       {
           
            $sql1="SELECT d.address,d.diagnosis,d.dept,st.doctor_id,st.dept_id,st.day,d.age FROM temp d LEFT JOIN staff_timetable st on st.dept_id=d.dept and st.day='$week' WHERE d.sex='$row[gender]' and d.dept =4 ORDER BY RAND() limit 1";
          
        $res1=mysqli_query($con,$sql1);
            $row1=mysqli_fetch_assoc($res1);
             $query="SELECT * FROM data_table WHERE id=1 || id=4 order by id";
           $iddata=mysqli_query($con,$query);
           
           while($central_opd=mysqli_fetch_assoc($iddata)){
               if($central_opd['id']==1)
               {
                      $opd_no=$central_opd['table_id']+1;
               }
               elseif($central_opd['id']==4)
               {
                   $uhid=$central_opd['table_id']+1;
               }
        
           }
         $y=date("Ymd",strtotime($date));
           if($y!=substr($opd_no,0,8) || $y!=substr($uhid,0,8))
        {
            $data="SELECT SUBSTR(MAX(opd_no),3,12) as opd,SUBSTR(MAX(uhid),4,12) as uhid FROM central_opd where opd_no like '__$y%'";
                    $op_no=mysqli_fetch_assoc(mysqli_query($con,$data));
                    if(isset($op_no['opd']))
                    {
                        $opd_no=$op_no['opd']+1;
                        $uhid=$op_no['uhid']+1;
                    }
                    else{
                                            
                             $uhid=$opd_no=$y.'0001';                     
                             
                         
                    }
            
        }
        // echo $opd_no;exit;
           $insert="INSERT INTO `central_opd`(`uhid`,`opd_no`,`date`, `name`, `age`, `gender`,`occupation`, `caste`, `address`, `diagnosis`, `dept_id`,`doctor`) VALUES ('AHC$uhid','OP$opd_no','$date','$row[name]','$row1[age]','$row[gender]','$row[occupation]','$row[caste]','$row1[address]','$row1[diagnosis]','$row1[dept]','$row1[doctor_id]')";
        $res2=mysqli_query($con,$insert);
        
        
        
        if(mysqli_insert_id($con)>0)
        {
        $query3="UPDATE data_table set table_id=$opd_no WHERE id=1";
        mysqli_query($con,$query3);
        $query4="UPDATE data_table set table_id=$uhid WHERE id=4";
        mysqli_query($con,$query4);
        }
        $sn++;
       }
    }
     if($j <=$oldpatient){
        $limit=rand(1,8);
        if($limit > ($oldpatient-$j))
        {
            $limit= $oldpatient-$j;
        }
        for($k=0;$k<$limit;$k++)
        {
            $query="SELECT * FROM data_table WHERE id=1";
            $central_opd=mysqli_fetch_assoc(mysqli_query($con,$query));
            $opd_no=$central_opd['table_id']+1;
        $insert="INSERT INTO `central_opd`(`uhid`,`opd_no`, `name`, `age`, `gender`, `mobile`, `address`, `diagnosis`, `dept_id`, `sub_deptid`, `annual_income`,  `religion`, `caste`, `category`, `parent_name`, `education`, `marital_status`, `occupation`, `economic_state`, `bpl_no`,`date`,`old_patient`,`doctor`) 
        SELECT `uhid`,'OP$opd_no', `name`, `age`, `gender`, `mobile`, `address`, `diagnosis`, `dept_id`, `sub_deptid`, `annual_income`,`religion`, `caste`, `category`, `parent_name`, `education`, `marital_status`, `occupation`, `economic_state`, `bpl_no`,'$date','1',`doctor` FROM `central_opd`  
        WHERE dept_id not in (20,17) and old_patient=0 and `date` <= DATE_SUB('$date', INTERVAL 5 DAY) and `date` > '$date' - INTERVAL 8 WEEK ORDER BY rand() LIMIT 1";   
    //  echo $insert;
     mysqli_query($con,$insert);
         if(mysqli_insert_id($con)>0)
            {
             $query4="UPDATE data_table set table_id=$opd_no WHERE id=1";
                mysqli_query($con,$query4);
            }
        }
     $sn=$sn+$j;
     $j=$j+$limit;
     
    }
    // exit;
    if($i<=$me){
        $sql="SELECT id,name,age,gender,caste,occupation FROM patient_data p  WHERE age > 11 ORDER BY RAND() LIMIT 1";
        $res=mysqli_query($con,$sql);
       
        while($row=mysqli_fetch_assoc($res))
       {
           
            $sql1="SELECT d.address,d.diagnosis,d.dept,st.doctor_id,st.dept_id,st.day,d.age FROM temp d LEFT JOIN staff_timetable st on st.dept_id=d.dept and st.day='$week' WHERE d.sex='$row[gender]' and d.dept =1 ORDER BY RAND() limit 1";
          
        $res1=mysqli_query($con,$sql1);
            $row1=mysqli_fetch_assoc($res1);
            $query="SELECT * FROM data_table WHERE id=1 || id=4 order by id";
           $iddata=mysqli_query($con,$query);
           
           while($central_opd=mysqli_fetch_assoc($iddata)){
               if($central_opd['id']==1)
               {
                      $opd_no=$central_opd['table_id']+1;
               }
               elseif($central_opd['id']==4)
               {
                   $uhid=$central_opd['table_id']+1;
               }
        
           }
           
         $y=date("Ymd",strtotime($date));
         
           if($y!=substr($opd_no,0,8) || $y!=substr($uhid,0,8))
        {
            // echo "$opd_no|$y<br>";
            $data="SELECT SUBSTR(MAX(opd_no),3,12) as opd,SUBSTR(MAX(uhid),4,12) as uhid FROM central_opd where opd_no like '__$y%'";
                    $op_no=mysqli_fetch_assoc(mysqli_query($con,$data));
                    if(isset($op_no['opd']))
                    {
                        $opd_no=$op_no['opd']+1;
                        $uhid=$op_no['uhid']+1;
                    }
                    else{
                                                 
                             $uhid=$opd_no=$y.'0001';                     
                             
                         
                    }
            
        }
        // echo $opd_no;exit;
           $insert="INSERT INTO `central_opd`(`uhid`,`opd_no`,`date`, `name`, `age`, `gender`,`occupation`, `caste`, `address`, `diagnosis`, `dept_id`,`doctor`) VALUES ('AHC$uhid','OP$opd_no','$date','$row[name]','$row1[age]','$row[gender]','$row[occupation]','$row[caste]','$row1[address]','$row1[diagnosis]','$row1[dept]','$row1[doctor_id]')";
        $res2=mysqli_query($con,$insert);
        
        
        
        if(mysqli_insert_id($con)>0)
        {
        $query3="UPDATE data_table set table_id=$opd_no WHERE id=1";
        mysqli_query($con,$query3);
        $query4="UPDATE data_table set table_id=$uhid WHERE id=4";
        mysqli_query($con,$query4);
        }
        $sn++;
       }
    }
    if($i<=$or){
        $sql="SELECT id,name,age,gender,caste,occupation FROM patient_data p  WHERE age > 11 ORDER BY RAND() LIMIT 1";
        $res=mysqli_query($con,$sql);
       
        while($row=mysqli_fetch_assoc($res))
       {
           
            $sql1="SELECT d.address,d.diagnosis,d.dept,st.doctor_id,st.dept_id,st.day,d.age FROM temp d LEFT JOIN staff_timetable st on st.dept_id=d.dept and st.day='$week' WHERE d.sex='$row[gender]' and d.dept =7 ORDER BY RAND() limit 1";
          
        $res1=mysqli_query($con,$sql1);
            $row1=mysqli_fetch_assoc($res1);
            $query="SELECT * FROM data_table WHERE id=1 || id=4 order by id";
           $iddata=mysqli_query($con,$query);
           
           while($central_opd=mysqli_fetch_assoc($iddata)){
               if($central_opd['id']==1)
               {
                      $opd_no=$central_opd['table_id']+1;
               }
               elseif($central_opd['id']==4)
               {
                   $uhid=$central_opd['table_id']+1;
               }
        
           }
          $y=date("Ymd",strtotime($date));
           if($y!=substr($opd_no,0,8) || $y!=substr($uhid,0,8))
        {
            $data="SELECT SUBSTR(MAX(opd_no),3,12) as opd,SUBSTR(MAX(uhid),4,12) as uhid FROM central_opd where opd_no like '__$y%'";
                    $op_no=mysqli_fetch_assoc(mysqli_query($con,$data));
                    if(isset($op_no['opd']))
                    {
                        $opd_no=$op_no['opd']+1;
                        $uhid=$op_no['uhid']+1;
                    }
                    else{
                                              
                             $uhid=$opd_no=$y.'0001';                     
                             
                         
                    }
            
        }
        // echo $opd_no;exit;
           $insert="INSERT INTO `central_opd`(`uhid`,`opd_no`,`date`, `name`, `age`, `gender`,`occupation`, `caste`, `address`, `diagnosis`, `dept_id`,`doctor`) VALUES ('AHC$uhid','OP$opd_no','$date','$row[name]','$row1[age]','$row[gender]','$row[occupation]','$row[caste]','$row1[address]','$row1[diagnosis]','$row1[dept]','$row1[doctor_id]')";
        $res2=mysqli_query($con,$insert);
        
        
        
        if(mysqli_insert_id($con)>0)
        {
        $query3="UPDATE data_table set table_id=$opd_no WHERE id=1";
        mysqli_query($con,$query3);
        $query4="UPDATE data_table set table_id=$uhid WHERE id=4";
        mysqli_query($con,$query4);
        }
        $sn++;
       }
    }
    if($i<=$op){
        $sql="SELECT id,name,age,gender,caste,occupation FROM patient_data p   ORDER BY RAND() LIMIT 1";
        $res=mysqli_query($con,$sql);
       
        while($row=mysqli_fetch_assoc($res))
       {
           
            $sql1="SELECT d.address,d.diagnosis,d.dept,st.doctor_id,st.dept_id,st.day,d.age FROM temp d LEFT JOIN staff_timetable st on st.dept_id=d.dept and st.day='$week' WHERE d.sex='$row[gender]' and d.dept =11 ORDER BY RAND() limit 1";
          
        $res1=mysqli_query($con,$sql1);
            $row1=mysqli_fetch_assoc($res1);
             $query="SELECT * FROM data_table WHERE id=1 || id=4 order by id";
           $iddata=mysqli_query($con,$query);
           
           while($central_opd=mysqli_fetch_assoc($iddata)){
               if($central_opd['id']==1)
               {
                      $opd_no=$central_opd['table_id']+1;
               }
               elseif($central_opd['id']==4)
               {
                   $uhid=$central_opd['table_id']+1;
               }
        
           }
          $y=date("Ymd",strtotime($date));
           if($y!=substr($opd_no,0,8) || $y!=substr($uhid,0,8))
        {
            $data="SELECT SUBSTR(MAX(opd_no),3,12) as opd,SUBSTR(MAX(uhid),4,12) as uhid FROM central_opd where opd_no like '__$y%'";
                    $op_no=mysqli_fetch_assoc(mysqli_query($con,$data));
                    if(isset($op_no['opd']))
                    {
                        $opd_no=$op_no['opd']+1;
                        $uhid=$op_no['uhid']+1;
                    }
                    else{
                                             
                             $uhid=$opd_no=$y.'0001';                     
                             
                         
                    }
            
        }
        // echo $opd_no;exit;
           $insert="INSERT INTO `central_opd`(`uhid`,`opd_no`,`date`, `name`, `age`, `gender`,`occupation`, `caste`, `address`, `diagnosis`, `dept_id`,`doctor`) VALUES ('AHC$uhid','OP$opd_no','$date','$row[name]','$row1[age]','$row[gender]','$row[occupation]','$row[caste]','$row1[address]','$row1[diagnosis]','$row1[dept]','$row1[doctor_id]')";
        $res2=mysqli_query($con,$insert);
        
        
        
        if(mysqli_insert_id($con)>0)
        {
        $query3="UPDATE data_table set table_id=$opd_no WHERE id=1";
        mysqli_query($con,$query3);
        $query4="UPDATE data_table set table_id=$uhid WHERE id=4";
        mysqli_query($con,$query4);
        }
        $sn++;
       }
    }
    if($i<=$en){
        $sql="SELECT id,name,age,gender,caste,occupation FROM patient_data p  WHERE age > 11 ORDER BY RAND() LIMIT 1";
        $res=mysqli_query($con,$sql);
       
        while($row=mysqli_fetch_assoc($res))
       {
           
            $sql1="SELECT d.address,d.diagnosis,d.dept,st.doctor_id,st.dept_id,st.day,d.age FROM temp d LEFT JOIN staff_timetable st on st.dept_id=d.dept and st.day='$week' WHERE d.sex='$row[gender]' and d.dept =18 ORDER BY RAND() limit 1";
          
        $res1=mysqli_query($con,$sql1);
            $row1=mysqli_fetch_assoc($res1);
            $query="SELECT * FROM data_table WHERE id=1 || id=4 order by id";
           $iddata=mysqli_query($con,$query);
           
           while($central_opd=mysqli_fetch_assoc($iddata)){
               if($central_opd['id']==1)
               {
                      $opd_no=$central_opd['table_id']+1;
               }
               elseif($central_opd['id']==4)
               {
                   $uhid=$central_opd['table_id']+1;
               }
        
           }
          $y=date("Ymd",strtotime($date));
           if($y!=substr($opd_no,0,8) || $y!=substr($uhid,0,8))
        {
            $data="SELECT SUBSTR(MAX(opd_no),3,12) as opd,SUBSTR(MAX(uhid),4,12) as uhid FROM central_opd where opd_no like '__$y%'";
                    $op_no=mysqli_fetch_assoc(mysqli_query($con,$data));
                    if(isset($op_no['opd']))
                    {
                        $opd_no=$op_no['opd']+1;
                        $uhid=$op_no['uhid']+1;
                    }
                    else{
                                               
                             $uhid=$opd_no=$y.'0001';                     
                             
                         
                    }
            
        }
        // echo $opd_no;exit;
           $insert="INSERT INTO `central_opd`(`uhid`,`opd_no`,`date`, `name`, `age`, `gender`,`occupation`, `caste`, `address`, `diagnosis`, `dept_id`,`doctor`) VALUES ('AHC$uhid','OP$opd_no','$date','$row[name]','$row1[age]','$row[gender]','$row[occupation]','$row[caste]','$row1[address]','$row1[diagnosis]','$row1[dept]','$row1[doctor_id]')";
        $res2=mysqli_query($con,$insert);
        
        
        
        if(mysqli_insert_id($con)>0)
        {
        $query3="UPDATE data_table set table_id=$opd_no WHERE id=1";
        mysqli_query($con,$query3);
        $query4="UPDATE data_table set table_id=$uhid WHERE id=4";
        mysqli_query($con,$query4);
        }
        $sn++;
       }
    }
    if($i<=$ps){
        $sql="SELECT id,name,age,gender,caste,occupation FROM patient_data p  WHERE age > 11 ORDER BY RAND() LIMIT 1";
        $res=mysqli_query($con,$sql);
       
        while($row=mysqli_fetch_assoc($res))
       {
           
            $sql1="SELECT d.address,d.diagnosis,d.dept,st.doctor_id,st.dept_id,st.day,d.age FROM temp d LEFT JOIN staff_timetable st on st.dept_id=d.dept and st.day='$week' WHERE d.sex='$row[gender]' and d.dept =3 ORDER BY RAND() limit 1";
          
        $res1=mysqli_query($con,$sql1);
            $row1=mysqli_fetch_assoc($res1);
             $query="SELECT * FROM data_table WHERE id=1 || id=4 order by id";
           $iddata=mysqli_query($con,$query);
           
           while($central_opd=mysqli_fetch_assoc($iddata)){
               if($central_opd['id']==1)
               {
                      $opd_no=$central_opd['table_id']+1;
               }
               elseif($central_opd['id']==4)
               {
                   $uhid=$central_opd['table_id']+1;
               }
        
           }
          $y=date("Ymd",strtotime($date));
           if($y!=substr($opd_no,0,8) || $y!=substr($uhid,0,8))
        {
            $data="SELECT SUBSTR(MAX(opd_no),3,12) as opd,SUBSTR(MAX(uhid),4,12) as uhid FROM central_opd where opd_no like '__$y%'";
                    $op_no=mysqli_fetch_assoc(mysqli_query($con,$data));
                    if(isset($op_no['opd']))
                    {
                        $opd_no=$op_no['opd']+1;
                        $uhid=$op_no['uhid']+1;
                    }
                    else{
                                                
                             $uhid=$opd_no=$y.'0001';                     
                             
                         
                    }
            
        }
        // echo $opd_no;exit;
           $insert="INSERT INTO `central_opd`(`uhid`,`opd_no`,`date`, `name`, `age`, `gender`,`occupation`, `caste`, `address`, `diagnosis`, `dept_id`,`doctor`) VALUES ('AHC$uhid','OP$opd_no','$date','$row[name]','$row1[age]','$row[gender]','$row[occupation]','$row[caste]','$row1[address]','$row1[diagnosis]','$row1[dept]','$row1[doctor_id]')";
        $res2=mysqli_query($con,$insert);
        
        
        
        if(mysqli_insert_id($con)>0)
        {
        $query3="UPDATE data_table set table_id=$opd_no WHERE id=1";
        mysqli_query($con,$query3);
        $query4="UPDATE data_table set table_id=$uhid WHERE id=4";
        mysqli_query($con,$query4);
        }
        $sn++;
       }
    }
    if($i<=$tr){
        $sql="SELECT id,name,age,gender,caste,occupation FROM patient_data p  WHERE age > 11 ORDER BY RAND() LIMIT 1";
        $res=mysqli_query($con,$sql);
       
        while($row=mysqli_fetch_assoc($res))
       {
           
            $sql1="SELECT d.address,d.diagnosis,d.dept,st.doctor_id,st.dept_id,st.day,d.age FROM temp d LEFT JOIN staff_timetable st on st.dept_id=d.dept and st.day='$week' WHERE d.sex='$row[gender]' and d.dept =5 ORDER BY RAND() limit 1";
          
        $res1=mysqli_query($con,$sql1);
            $row1=mysqli_fetch_assoc($res1);
             $query="SELECT * FROM data_table WHERE id=1 || id=4 order by id";
           $iddata=mysqli_query($con,$query);
           
           while($central_opd=mysqli_fetch_assoc($iddata)){
               if($central_opd['id']==1)
               {
                      $opd_no=$central_opd['table_id']+1;
               }
               elseif($central_opd['id']==4)
               {
                   $uhid=$central_opd['table_id']+1;
               }
        
           }
         $y=date("Ymd",strtotime($date));
           if($y!=substr($opd_no,0,8) || $y!=substr($uhid,0,8))
        {
            $data="SELECT SUBSTR(MAX(opd_no),3,12) as opd,SUBSTR(MAX(uhid),4,12) as uhid FROM central_opd where opd_no like '__$y%'";
                    $op_no=mysqli_fetch_assoc(mysqli_query($con,$data));
                    if(isset($op_no['opd']))
                    {
                        $opd_no=$op_no['opd']+1;
                        $uhid=$op_no['uhid']+1;
                    }
                    else{
                                             
                             $uhid=$opd_no=$y.'0001';                     
                             
                         
                    }
            
        }
        // echo $opd_no;exit;
           $insert="INSERT INTO `central_opd`(`uhid`,`opd_no`,`date`, `name`, `age`, `gender`,`occupation`, `caste`, `address`, `diagnosis`, `dept_id`,`doctor`) VALUES ('AHC$uhid','OP$opd_no','$date','$row[name]','$row1[age]','$row[gender]','$row[occupation]','$row[caste]','$row1[address]','$row1[diagnosis]','$row1[dept]','$row1[doctor_id]')";
        $res2=mysqli_query($con,$insert);
        
        
        
        if(mysqli_insert_id($con)>0)
        {
        $query3="UPDATE data_table set table_id=$opd_no WHERE id=1";
        mysqli_query($con,$query3);
        $query4="UPDATE data_table set table_id=$uhid WHERE id=4";
        mysqli_query($con,$query4);
        }
        $sn++;
       }
    }
     if($i<=$em){
        $sql="SELECT id,name,age,gender,caste,occupation FROM patient_data p  WHERE age > 11 ORDER BY RAND() LIMIT 1";
        $res=mysqli_query($con,$sql);
       
        while($row=mysqli_fetch_assoc($res))
       {
           
            $sql1="SELECT d.address,d.diagnosis,d.dept,st.doctor_id,st.dept_id,st.day,d.age FROM temp d LEFT JOIN staff_timetable st on st.dept_id=d.dept and st.day='$week' WHERE d.sex='$row[gender]' and d.dept =17 ORDER BY RAND() limit 1";
          
        $res1=mysqli_query($con,$sql1);
            $row1=mysqli_fetch_assoc($res1);
           $query="SELECT * FROM data_table WHERE id=1 || id=4 order by id";
           $iddata=mysqli_query($con,$query);
           
           while($central_opd=mysqli_fetch_assoc($iddata)){
               if($central_opd['id']==1)
               {
                      $opd_no=$central_opd['table_id']+1;
               }
               elseif($central_opd['id']==4)
               {
                   $uhid=$central_opd['table_id']+1;
               }
        
           }
          $y=date("Ymd",strtotime($date));
           if($y!=substr($opd_no,0,8) || $y!=substr($uhid,0,8))
        {
            $data="SELECT SUBSTR(MAX(opd_no),3,12) as opd,SUBSTR(MAX(uhid),4,12) as uhid FROM central_opd where opd_no like '__$y%'";
                    $op_no=mysqli_fetch_assoc(mysqli_query($con,$data));
                    if(isset($op_no['opd']))
                    {
                        $opd_no=$op_no['opd']+1;
                        $uhid=$op_no['uhid']+1;
                    }
                    else{
                                              
                             $uhid=$opd_no=$y.'0001';                     
                             
                         
                    }
            
        }
        // echo $opd_no;exit;
           $insert="INSERT INTO `central_opd`(`uhid`,`opd_no`,`date`, `name`, `age`, `gender`,`occupation`, `caste`, `address`, `diagnosis`, `dept_id`,`doctor`) VALUES ('AHC$uhid','OP$opd_no','$date','$row[name]','$row1[age]','$row[gender]','$row[occupation]','$row[caste]','$row1[address]','$row1[diagnosis]','$row1[dept]','$row1[doctor_id]')";
        $res2=mysqli_query($con,$insert);
        
        
        
        if(mysqli_insert_id($con)>0)
        {
        $query3="UPDATE data_table set table_id=$opd_no WHERE id=1";
        mysqli_query($con,$query3);
        $query4="UPDATE data_table set table_id=$uhid WHERE id=4";
        mysqli_query($con,$query4);
        }
        $sn++;
       }
    }
   

}  


echo $sn."<br>"; 
// exit;
}
exit;
?>
