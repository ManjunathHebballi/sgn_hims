<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/dbconnect.php');
//   print_r($_REQUEST);exit;


  $newlogin = new userlogin;                  
  $con=$newlogin->dbconnect();
  $week=date("l");


  $oldpatient=83;
$data=134;

 
$kc=round(($data * 23) / 100);
$sh=ceil(($data * 20) / 100);
$sk=ceil(($data * 11) / 100);
$ps=round(($data * 11) / 100);
$kb=ceil(($data * 13) / 100);
$pk=ceil(($data * 12) / 100);
$fe=round(($data * 3) / 100);
$at=round(($data * 1) / 100);
$sw=ceil(($data * 6) / 100);
// echo "<br>kc ".$kc;
// echo "<br>sh ".$sh;
// echo "<br>sk ".$sk;
// echo "<br>ps ".$ps;
// echo "<br>kb ".$kb;
echo "<br>pk ".$pk;
echo "<br>fe ".$fe;
echo "<br>at ".$at;
echo "<br>sw ".$sw."<br>";

if($oldpatient < $kc)
{
    $big=$kc;
}
else{
    $big=$oldpatient;
}
$sn=0;$j=1;
for($i=1;$i<=$big;$i++)
{
   if($i<=$kb){
        $sql="SELECT id,name,age,gender,caste,occupation FROM patient_data p WHERE age < 12 ORDER BY RAND() LIMIT 1";
        // echo $sql;exit;
        $res=mysqli_query($con,$sql);
       
        while($row=mysqli_fetch_assoc($res))
       {
           
            $sql1="SELECT d.address,d.diagnosis,d.dept,st.doctor_id,st.dept_id,st.day FROM daignosis_data d LEFT JOIN staff_timetable st on st.dept_id=d.dept and st.day='$week' WHERE d.dept =2 ORDER BY RAND() limit 1";
          
        $res1=mysqli_query($con,$sql1);
            $row1=mysqli_fetch_assoc($res1);
           $query="SELECT * FROM data_table WHERE id=1";
           $central_opd=mysqli_fetch_assoc(mysqli_query($con,$query));
           $opd_no=$central_opd['table_id']+1;
          $y=date("y",strtotime($_REQUEST['date']));
           if($y!=substr($opd_no,0,2))
        {
            $opd_no=$y.'000001';
        }
        // echo $opd_no;exit;
           $insert="INSERT INTO `central_opd`(`opd_no`,`date`, `name`, `age`, `gender`,`occupation`, `caste`, `address`, `diagnosis`, `dept_id`,`doctor`) VALUES ('$opd_no','$_REQUEST[date]','$row[name]','$row[age]','$row[gender]','$row[occupation]','$row[caste]','$row1[address]','$row1[diagnosis]','$row1[dept]','$row1[doctor_id]')";
        $res2=mysqli_query($con,$insert);
        
        echo $insert."<br>";
        
        $query="UPDATE data_table set table_id=$opd_no WHERE id=1";
        mysqli_query($con,$query);
        $sn++;
       }
    }
    if($i<=$ps){
       
        $sql="SELECT id,name,age,gender,caste,occupation FROM patient_data p WHERE age > 11 and gender='female' ORDER BY RAND() LIMIT 1";
        $res=mysqli_query($con,$sql);
       
        while($row=mysqli_fetch_assoc($res))
       {
           
            $sql1="SELECT d.address,d.diagnosis,d.dept,st.doctor_id,st.dept_id,st.day FROM daignosis_data d LEFT JOIN staff_timetable st on st.dept_id=d.dept and st.day='$week' WHERE d.dept =5 ORDER BY RAND() limit 1";
          
        $res1=mysqli_query($con,$sql1);
            $row1=mysqli_fetch_assoc($res1);
           $query="SELECT * FROM data_table WHERE id=1";
           $central_opd=mysqli_fetch_assoc(mysqli_query($con,$query));
           $opd_no=$central_opd['table_id']+1;
          $y=date("y",strtotime($_REQUEST['date']));
           if($y!=substr($opd_no,0,2))
        {
            $opd_no=$y.'000001';
        }
        // echo $opd_no;exit;
           $insert="INSERT INTO `central_opd`(`opd_no`,`date`, `name`, `age`, `gender`,`occupation`, `caste`, `address`, `diagnosis`, `dept_id`,`doctor`) VALUES ('$opd_no','$_REQUEST[date]','$row[name]','$row[age]','$row[gender]','$row[occupation]','$row[caste]','$row1[address]','$row1[diagnosis]','$row1[dept]','$row1[doctor_id]')";
        $res2=mysqli_query($con,$insert);
        
        //  echo $insert."<br>";
        
        $query="UPDATE data_table set table_id=$opd_no WHERE id=1";
        mysqli_query($con,$query);
        $sn++;
       }
    }
    if($i<=$sk){
       
        $sql="SELECT id,name,age,gender,caste,occupation FROM patient_data p  WHERE age > 11 ORDER BY RAND() LIMIT 1";
        $res=mysqli_query($con,$sql);
       
        while($row=mysqli_fetch_assoc($res))
       {
           
            $sql1="SELECT d.address,d.diagnosis,d.dept,st.doctor_id,st.dept_id,st.day FROM daignosis_data d LEFT JOIN staff_timetable st on st.dept_id=d.dept and st.day='$week' WHERE d.dept =6 ORDER BY RAND() limit 1";
          
        $res1=mysqli_query($con,$sql1);
            $row1=mysqli_fetch_assoc($res1);
           $query="SELECT * FROM data_table WHERE id=1";
           $central_opd=mysqli_fetch_assoc(mysqli_query($con,$query));
           $opd_no=$central_opd['table_id']+1;
          $y=date("y",strtotime($_REQUEST['date']));
           if($y!=substr($opd_no,0,2))
        {
            $opd_no=$y.'000001';
        }
        // echo $opd_no;exit;
           $insert="INSERT INTO `central_opd`(`opd_no`,`date`, `name`, `age`, `gender`,`occupation`, `caste`, `address`, `diagnosis`, `dept_id`,`doctor`,`sub_deptid`) VALUES ('$opd_no','$_REQUEST[date]','$row[name]','$row[age]','$row[gender]','$row[occupation]','$row[caste]','$row1[address]','$row1[diagnosis]','$row1[dept]','$row1[doctor_id]',ROUND((2 - 1 * RAND()), 0))";
        $res2=mysqli_query($con,$insert);
        
        
        
        $query="UPDATE data_table set table_id=$opd_no WHERE id=1";
        mysqli_query($con,$query);
        $sn++;
       }
    }
    if($i<=$sh){
        
        $sql="SELECT id,name,age,gender,caste,occupation FROM patient_data p  WHERE age > 11 ORDER BY RAND() LIMIT 1";
        $res=mysqli_query($con,$sql);
       
        while($row=mysqli_fetch_assoc($res))
       {
           
            $sql1="SELECT d.address,d.diagnosis,d.dept,st.doctor_id,st.dept_id,st.day FROM daignosis_data d LEFT JOIN staff_timetable st on st.dept_id=d.dept and st.day='$week' WHERE d.dept =7 ORDER BY RAND() limit 1";
          
        $res1=mysqli_query($con,$sql1);
            $row1=mysqli_fetch_assoc($res1);
           $query="SELECT * FROM data_table WHERE id=1";
           $central_opd=mysqli_fetch_assoc(mysqli_query($con,$query));
           $opd_no=$central_opd['table_id']+1;
          $y=date("y",strtotime($_REQUEST['date']));
           if($y!=substr($opd_no,0,2))
        {
            $opd_no=$y.'000001';
        }
        // echo $opd_no;exit;
           $insert="INSERT INTO `central_opd`(`opd_no`,`date`, `name`, `age`, `gender`,`occupation`, `caste`, `address`, `diagnosis`, `dept_id`,`doctor`) VALUES ('$opd_no','$_REQUEST[date]','$row[name]','$row[age]','$row[gender]','$row[occupation]','$row[caste]','$row1[address]','$row1[diagnosis]','$row1[dept]','$row1[doctor_id]')";
        $res2=mysqli_query($con,$insert);
        
        
        
        $query="UPDATE data_table set table_id=$opd_no WHERE id=1";
        mysqli_query($con,$query);
        $sn++;
       }
    }
    if($i<=$pk){
        $sql="SELECT id,name,age,gender,caste,occupation FROM patient_data p  WHERE age > 11 ORDER BY RAND() LIMIT 1";
        $res=mysqli_query($con,$sql);
       
        while($row=mysqli_fetch_assoc($res))
       {
           
            $sql1="SELECT d.address,d.diagnosis,d.dept,st.doctor_id,st.dept_id,st.day FROM daignosis_data d LEFT JOIN staff_timetable st on st.dept_id=d.dept and st.day='$week' WHERE d.dept =4 ORDER BY RAND() limit 1";
          
        $res1=mysqli_query($con,$sql1);
            $row1=mysqli_fetch_assoc($res1);
           $query="SELECT * FROM data_table WHERE id=1";
           $central_opd=mysqli_fetch_assoc(mysqli_query($con,$query));
           $opd_no=$central_opd['table_id']+1;
          $y=date("y",strtotime($_REQUEST['date']));
           if($y!=substr($opd_no,0,2))
        {
            $opd_no=$y.'000001';
        }
        // echo $opd_no;exit;
           $insert="INSERT INTO `central_opd`(`opd_no`,`date`, `name`, `age`, `gender`,`occupation`, `caste`, `address`, `diagnosis`, `dept_id`,`doctor`) VALUES ('$opd_no','$_REQUEST[date]','$row[name]','$row[age]','$row[gender]','$row[occupation]','$row[caste]','$row1[address]','$row1[diagnosis]','$row1[dept]','$row1[doctor_id]')";
        $res2=mysqli_query($con,$insert);
        
        
        
        $query="UPDATE data_table set table_id=$opd_no WHERE id=1";
        mysqli_query($con,$query);
        $sn++;
       }
    }
    if($i<=$kc){
        $sql="SELECT id,name,age,gender,caste,occupation FROM patient_data p  WHERE age > 11 ORDER BY RAND() LIMIT 1";
        $res=mysqli_query($con,$sql);
       
        while($row=mysqli_fetch_assoc($res))
       {
           
            $sql1="SELECT d.address,d.diagnosis,d.dept,st.doctor_id,st.dept_id,st.day FROM daignosis_data d LEFT JOIN staff_timetable st on st.dept_id=d.dept and st.day='$week' WHERE d.dept =3 ORDER BY RAND() limit 1";
          
        $res1=mysqli_query($con,$sql1);
            $row1=mysqli_fetch_assoc($res1);
           $query="SELECT * FROM data_table WHERE id=1";
           $central_opd=mysqli_fetch_assoc(mysqli_query($con,$query));
           $opd_no=$central_opd['table_id']+1;
          $y=date("y",strtotime($_REQUEST['date']));
           if($y!=substr($opd_no,0,2))
        {
            $opd_no=$y.'000001';
        }
        // echo $opd_no;exit;
           $insert="INSERT INTO `central_opd`(`opd_no`,`date`, `name`, `age`, `gender`,`occupation`, `caste`, `address`, `diagnosis`, `dept_id`,`doctor`) VALUES ('$opd_no','$_REQUEST[date]','$row[name]','$row[age]','$row[gender]','$row[occupation]','$row[caste]','$row1[address]','$row1[diagnosis]','$row1[dept]','$row1[doctor_id]')";
        $res2=mysqli_query($con,$insert);
        
        
        
        $query="UPDATE data_table set table_id=$opd_no WHERE id=1";
        mysqli_query($con,$query);
        $sn++;
       }
    }
    if($i<=$sw){
        $sql="SELECT id,name,age,gender,caste,occupation FROM patient_data p  WHERE age > 11 ORDER BY RAND() LIMIT 1";
        $res=mysqli_query($con,$sql);
       
        while($row=mysqli_fetch_assoc($res))
       {
           
            $sql1="SELECT d.address,d.diagnosis,d.dept,st.doctor_id,st.dept_id,st.day FROM daignosis_data d LEFT JOIN staff_timetable st on st.dept_id=d.dept and st.day='$week' WHERE d.dept =8 ORDER BY RAND() limit 1";
          
        $res1=mysqli_query($con,$sql1);
            $row1=mysqli_fetch_assoc($res1);
           $query="SELECT * FROM data_table WHERE id=1";
           $central_opd=mysqli_fetch_assoc(mysqli_query($con,$query));
           $opd_no=$central_opd['table_id']+1;
          $y=date("y",strtotime($_REQUEST['date']));
           if($y!=substr($opd_no,0,2))
        {
            $opd_no=$y.'000001';
        }
        // echo $opd_no;exit;
           $insert="INSERT INTO `central_opd`(`opd_no`,`date`, `name`, `age`, `gender`,`occupation`, `caste`, `address`, `diagnosis`, `dept_id`,`doctor`) VALUES ('$opd_no','$_REQUEST[date]','$row[name]','$row[age]','$row[gender]','$row[occupation]','$row[caste]','$row1[address]','$row1[diagnosis]','$row1[dept]','$row1[doctor_id]')";
        $res2=mysqli_query($con,$insert);
        
        
        
        $query="UPDATE data_table set table_id=$opd_no WHERE id=1";
        mysqli_query($con,$query);
        $sn++;
       }
    }
    if($i<=$fe){
        $sql="SELECT id,name,age,gender,caste,occupation FROM patient_data p   ORDER BY RAND() LIMIT 1";
        $res=mysqli_query($con,$sql);
       
        while($row=mysqli_fetch_assoc($res))
       {
           
            $sql1="SELECT d.address,d.diagnosis,d.dept,st.doctor_id,st.dept_id,st.day FROM daignosis_data d LEFT JOIN staff_timetable st on st.dept_id=d.dept and st.day='$week' WHERE d.dept =10 ORDER BY RAND() limit 1";
          
        $res1=mysqli_query($con,$sql1);
            $row1=mysqli_fetch_assoc($res1);
           $query="SELECT * FROM data_table WHERE id=1";
           $central_opd=mysqli_fetch_assoc(mysqli_query($con,$query));
           $opd_no=$central_opd['table_id']+1;
          $y=date("y",strtotime($_REQUEST['date']));
           if($y!=substr($opd_no,0,2))
        {
            $opd_no=$y.'000001';
        }
        // echo $opd_no;exit;
           $insert="INSERT INTO `central_opd`(`opd_no`,`date`, `name`, `age`, `gender`,`occupation`, `caste`, `address`, `diagnosis`, `dept_id`,`doctor`) VALUES ('$opd_no','$_REQUEST[date]','$row[name]','$row[age]','$row[gender]','$row[occupation]','$row[caste]','$row1[address]','$row1[diagnosis]','$row1[dept]','$row1[doctor_id]')";
        $res2=mysqli_query($con,$insert);
        
        
        
        $query="UPDATE data_table set table_id=$opd_no WHERE id=1";
        mysqli_query($con,$query);
        $sn++;
       }
    }
    if($i<=$at){
        $sql="SELECT id,name,age,gender,caste,occupation FROM patient_data p  WHERE age > 11 ORDER BY RAND() LIMIT 1";
        $res=mysqli_query($con,$sql);
       
        while($row=mysqli_fetch_assoc($res))
       {
           
            $sql1="SELECT d.address,d.diagnosis,d.dept,st.doctor_id,st.dept_id,st.day FROM daignosis_data d LEFT JOIN staff_timetable st on st.dept_id=d.dept and st.day='$week' WHERE d.dept =1 ORDER BY RAND() limit 1";
          
        $res1=mysqli_query($con,$sql1);
            $row1=mysqli_fetch_assoc($res1);
           $query="SELECT * FROM data_table WHERE id=1";
           $central_opd=mysqli_fetch_assoc(mysqli_query($con,$query));
           $opd_no=$central_opd['table_id']+1;
          $y=date("y",strtotime($_REQUEST['date']));
           if($y!=substr($opd_no,0,2))
        {
            $opd_no=$y.'000001';
        }
        // echo $opd_no;exit;
           $insert="INSERT INTO `central_opd`(`opd_no`,`date`, `name`, `age`, `gender`,`occupation`, `caste`, `address`, `diagnosis`, `dept_id`,`doctor`) VALUES ('$opd_no','$_REQUEST[date]','$row[name]','$row[age]','$row[gender]','$row[occupation]','$row[caste]','$row1[address]','$row1[diagnosis]','$row1[dept]','$row1[doctor_id]')";
        $res2=mysqli_query($con,$insert);
        
        
        
        $query="UPDATE data_table set table_id=$opd_no WHERE id=1";
        mysqli_query($con,$query);
        $sn++;
       }
    }
      if($j <=$oldpatient){
        $limit=rand(1,8);
        if($limit > ($oldpatient-$j))
        {
            $limit= $oldpatient-$j;
        }
        $query="INSERT INTO `central_opd`(`opd_no`, `name`, `age`, `gender`, `mobile`, `address`, `diagnosis`, `dept_id`, `sub_deptid`, `annual_income`,  `religion`, `caste`, `category`, `parent_name`, `education`, `marital_status`, `occupation`, `economic_state`, `bpl_no`,`date`,`old_patient`) SELECT `opd_no`, `name`, `age`, `gender`, `mobile`, `address`, `diagnosis`, `dept_id`, `sub_deptid`, `annual_income`,`religion`, `caste`, `category`, `parent_name`, `education`, `marital_status`, `occupation`, `economic_state`, `bpl_no`,'$_REQUEST[date]',1 FROM `central_opd`  WHERE dept_id !=1  and `date` < '$_REQUEST[date]' - INTERVAL 1 WEEK ORDER BY rand() LIMIT $limit";   
    //  echo $query;exit;
     mysqli_query($con,$query);
     $sn=$sn+$j;
     $j=$j+$limit;
     
    }

}  


echo $sn; 
exit;
?>
