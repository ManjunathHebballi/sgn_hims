<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/dbconnect.php');
 $newlogin = new userlogin;                  
  $con=$newlogin->dbconnect();
$lab=array('36','31','38','40','39','31','30','32','39','39','35','37','32','37','32','32','31');
$month = 5;
$year = 2023;

for($d=1; $d<=17; $d++)
{
    $time=mktime(12, 0, 0, $month, $d, $year);          
    if (date('m', $time)==$month)       
        $list[]=date('Y-m-d', $time);
}


for($count=0;$count<count($list);$count++)
{
     $date=$list[$count];
     $data=$lab[$count];
// op lab
$sn=$xray=round(($data * 60) / 100); //XRAY
$usg=ceil(($data * 40) / 100);    //USG



    // for($i=1;$i<=$sn;$i++)
    // {
    //     if($i<=$xray)
    //     {
    //         $limit=rand(1,8);
    //         if($limit > $xray)
    //         {
    //             $limit=$xray;
    //         }
    //         $sql="insert INTO lab_report (lab_result,date,patient_id,patient_type) SELECT l.testid,c.date,c.id,'OP' FROM `labtest_group` l left join central_opd c on FIND_IN_SET(dept_id, dept) > 0 || diagnosis=concat('%',dept,'%') WHERE date ='$date' and l.labid=10 ORDER by rand() LIMIT $limit";
        
    //         mysqli_query($con,$sql);
    //         $xray -=$limit;
    //     }
    //     if($i<=$usg)
    //     {
    //         $limit=rand(1,8);
    //         if($limit > $usg)
    //         {
    //             $limit=$usg;
    //         }
    //         $sql="insert INTO lab_report (lab_result,date,patient_id,patient_type) SELECT l.testid,c.date,c.id,'OP' FROM `labtest_group` l left join central_opd c on FIND_IN_SET(dept_id, dept) > 0 || diagnosis=concat('%',dept,'%') WHERE date ='$date' and l.labid=8 ORDER by rand() LIMIT $limit";
        
    //         mysqli_query($con,$sql);
    //         $usg -=$limit;
    //     }
        
        
    // }



#########################################################################################################################################################################################################################################



//IP Lab
 for($i=1;$i<=$sn;$i++)
    {
        if($i<=$xray)
        {
            $limit=rand(1,8);
            if($limit > $xray)
            {
                $limit=$xray;
            }
            $sql="insert INTO lab_report (lab_result,date,patient_id,patient_type) SELECT l.testid,c.admission_date,c.ipd_no,'IP' FROM `labtest_group` l left join central_ipd c on FIND_IN_SET(dept_id, dept) > 0 || diagnosis=concat('%',dept,'%') WHERE admission_date ='$date' and l.labid=10 ORDER by rand() LIMIT $limit";
        
            mysqli_query($con,$sql);
            $xray -=$limit;
        }
        if($i<=$usg)
        {
            $limit=rand(1,8);
            if($limit > $usg)
            {
                $limit=$usg;
            }
            $sql="insert INTO lab_report (lab_result,date,patient_id,patient_type) SELECT l.testid,c.admission_date,c.ipd_no,'IP' FROM `labtest_group` l left join central_ipd c on FIND_IN_SET(dept_id, dept) > 0 || diagnosis=concat('%',dept,'%') WHERE admission_date ='$date' and l.labid=8 ORDER by rand() LIMIT $limit";
        
            mysqli_query($con,$sql);
            $usg -=$limit;
        }
        
        
    }
}
?>