<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
include('model/dbconnect.php');
 $newlogin = new userlogin;                  
  $con=$newlogin->dbconnect();
$lab=array('583','592','558','420','0','563','558','563','591','563','452','0','572','586','556','584','562','426','0');
$month = 3;
$year = 2023;

for($d=1; $d<=31; $d++)
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
$sn=$bio=round(($data * 37) / 100); //Biochemistry
$hem=ceil(($data * 48) / 100);    //Hematology
$micro=ceil(($data * 0.3) / 100);    //Microbiology
$pat=round(($data * 7) / 100);   // Clinical Pathology
$his=round(($data * 0.3) / 100);  //Histopathology
$cyt=ceil(($data * 0.6) / 100);    //Cytopathology
$sir=floor(($data * 7) / 100);  //Serology


    for($i=1;$i<=$sn;$i++)
    {
        if($i<=$bio)
        {
            $limit=rand(1,8);
            if($limit > $bio)
            {
                $limit=$bio;
            }
            $sql="insert INTO lab_report (lab_result,date,patient_id,patient_type) SELECT l.testid,c.date,c.id,'OP' FROM `labtest_group` l left join central_opd c on FIND_IN_SET(dept_id, dept) > 0 || diagnosis=concat('%',dept,'%') WHERE date ='$date' and l.labid=4 ORDER by rand() LIMIT $limit";
        
            mysqli_query($con,$sql);
            $bio -=$limit;
        }
        if($i<=$hem)
        {
            $limit=rand(1,8);
            if($limit > $hem)
            {
                $limit=$hem;
            }
            $sql="insert INTO lab_report (lab_result,date,patient_id,patient_type) SELECT l.testid,c.date,c.id,'OP' FROM `labtest_group` l left join central_opd c on FIND_IN_SET(dept_id, dept) > 0 || diagnosis=concat('%',dept,'%') WHERE date ='$date' and l.labid=1 ORDER by rand() LIMIT $limit";
        
            mysqli_query($con,$sql);
            $hem -=$limit;
        }
        if($i<=$micro)
        {
            $limit=rand(1,8);
            if($limit > $micro)
            {
                $limit=$micro;
            }
            $sql="insert INTO lab_report (lab_result,date,patient_id,patient_type) SELECT l.testid,c.date,c.id,'OP' FROM `labtest_group` l left join central_opd c on FIND_IN_SET(dept_id, dept) > 0 || diagnosis=concat('%',dept,'%') || dept='' WHERE date ='$date' and l.labid=7 ORDER by rand() LIMIT $limit";
        
            mysqli_query($con,$sql);
            $micro -=$limit;
        }
        if($i<=$pat)
        {
            $limit=rand(1,8);
            if($limit > $pat)
            {
                $limit=$pat;
            }
            $sql="insert INTO lab_report (lab_result,date,patient_id,patient_type) SELECT l.testid,c.date,c.id,'OP' FROM `labtest_group` l left join central_opd c on FIND_IN_SET(dept_id, dept) > 0 || diagnosis=concat('%',dept,'%') WHERE date ='$date' and l.labid=3 ORDER by rand() LIMIT $limit";
        
            mysqli_query($con,$sql);
            $pat -=$limit;
        }
        if($i<=$his)
        {
            $limit=rand(1,8);
            if($limit > $his)
            {
                $limit=$his;
            }
            $sql="insert INTO lab_report (lab_result,date,patient_id,patient_type) SELECT l.testid,c.date,c.id,'OP' FROM `labtest_group` l left join central_opd c on FIND_IN_SET(dept_id, dept) > 0 || diagnosis=concat('%',dept,'%') || dept='' WHERE date ='$date' and l.labid=6 ORDER by rand() LIMIT $limit";
        
            mysqli_query($con,$sql);
            $his -=$limit;
        }
        if($i<=$cyt)
        {
            $limit=rand(1,8);
            if($limit > $cyt)
            {
                $limit=$cyt;
            }
            $sql="insert INTO lab_report (lab_result,date,patient_id,patient_type) SELECT l.testid,c.date,c.id,'OP' FROM `labtest_group` l left join central_opd c on FIND_IN_SET(dept_id, dept) > 0 || diagnosis=concat('%',dept,'%') WHERE date ='$date' and l.labid=5 ORDER by rand() LIMIT $limit";
        
            mysqli_query($con,$sql);
            $cyt -=$limit;
        }
        if($i<=$sir)
        {
            $limit=rand(1,8);
            if($limit > $sir)
            {
                $limit=$sir;
            }
            $sql="insert INTO lab_report (lab_result,date,patient_id,patient_type) SELECT l.testid,c.date,c.id,'OP' FROM `labtest_group` l left join central_opd c on FIND_IN_SET(dept_id, dept) > 0 || diagnosis=concat('%',dept,'%') || dept='' WHERE date ='$date' and l.labid=2 ORDER by rand() LIMIT $limit";
        
            mysqli_query($con,$sql);
            $sir -=$limit;
        }
        
    }



#########################################################################################################################################################################################################################################



//IP Lab
// $sn=$bio=round(($data * 36) / 100); //Biochemistry
// $hem=ceil(($data * 53) / 100);    //Hematology
// $micro=ceil(($data * 2) / 100);    //Microbiology
// $pat=round(($data * 4) / 100);   // Clinical Pathology
// $his=round(($data * 0.7) / 100);  //Histopathology
// $cyt=ceil(($data * 0.3) / 100);    //Cytopathology
// $sir=floor(($data * 4) / 100);  //Serology


//     for($i=1;$i<=$sn;$i++)
//     {
//         if($i<=$bio)
//         {
//             $limit=rand(1,8);
//             if($limit > $bio)
//             {
//                 $limit=$bio;
//             }
//             $sql="insert INTO lab_report (lab_result,date,patient_id,patient_type) SELECT l.testid,c.admission_date,c.id,'IP' FROM `labtest_group` l left join central_ipd c on FIND_IN_SET(dept_id, dept) > 0 || diagnosis=concat('%',dept,'%') WHERE admission_date ='$date' and l.labid=4 ORDER by rand() LIMIT $limit";
        
//             mysqli_query($con,$sql);
//             $bio -=$limit;
//         }
//         if($i<=$hem)
//         {
//             $limit=rand(1,8);
//             if($limit > $hem)
//             {
//                 $limit=$hem;
//             }
//             $sql="insert INTO lab_report (lab_result,date,patient_id,patient_type) SELECT l.testid,c.admission_date,c.id,'IP' FROM `labtest_group` l left join central_ipd c on FIND_IN_SET(dept_id, dept) > 0 || diagnosis=concat('%',dept,'%') WHERE admission_date ='$date' and l.labid=1 ORDER by rand() LIMIT $limit";
        
//             mysqli_query($con,$sql);
//             $hem -=$limit;
//         }
//         if($i<=$micro)
//         {
//             $limit=rand(1,8);
//             if($limit > $micro)
//             {
//                 $limit=$micro;
//             }
//             $sql="insert INTO lab_report (lab_result,date,patient_id,patient_type) SELECT l.testid,c.admission_date,c.id,'IP' FROM `labtest_group` l left join central_ipd c on FIND_IN_SET(dept_id, dept) > 0 || diagnosis=concat('%',dept,'%') || dept='' WHERE admission_date ='$date' and l.labid=7 ORDER by rand() LIMIT $limit";
        
//             mysqli_query($con,$sql);
//             $micro -=$limit;
//         }
//         if($i<=$pat)
//         {
//             $limit=rand(1,8);
//             if($limit > $pat)
//             {
//                 $limit=$pat;
//             }
//             $sql="insert INTO lab_report (lab_result,date,patient_id,patient_type) SELECT l.testid,c.admission_date,c.id,'IP' FROM `labtest_group` l left join central_ipd c on FIND_IN_SET(dept_id, dept) > 0 || diagnosis=concat('%',dept,'%') WHERE admission_date ='$date' and l.labid=3 ORDER by rand() LIMIT $limit";
        
//             mysqli_query($con,$sql);
//             $pat -=$limit;
//         }
//         if($i<=$his)
//         {
//             $limit=rand(1,8);
//             if($limit > $his)
//             {
//                 $limit=$his;
//             }
//             $sql="insert INTO lab_report (lab_result,date,patient_id,patient_type) SELECT l.testid,c.admission_date,c.id,'IP' FROM `labtest_group` l left join central_ipd c on FIND_IN_SET(dept_id, dept) > 0 || diagnosis=concat('%',dept,'%') || dept='' WHERE admission_date ='$date' and l.labid=6 ORDER by rand() LIMIT $limit";
        
//             mysqli_query($con,$sql);
//             $his -=$limit;
//         }
//         if($i<=$cyt)
//         {
//             $limit=rand(1,8);
//             if($limit > $cyt)
//             {
//                 $limit=$cyt;
//             }
//             $sql="insert INTO lab_report (lab_result,date,patient_id,patient_type) SELECT l.testid,c.admission_date,c.id,'IP' FROM `labtest_group` l left join central_ipd c on FIND_IN_SET(dept_id, dept) > 0 || diagnosis=concat('%',dept,'%') WHERE admission_date ='$date' and l.labid=5 ORDER by rand() LIMIT $limit";
        
//             mysqli_query($con,$sql);
//             $cyt -=$limit;
//         }
//         if($i<=$sir)
//         {
//             $limit=rand(1,8);
//             if($limit > $sir)
//             {
//                 $limit=$sir;
//             }
//             $sql="insert INTO lab_report (lab_result,date,patient_id,patient_type) SELECT l.testid,c.admission_date,c.id,'IP' FROM `labtest_group` l left join central_ipd c on FIND_IN_SET(dept_id, dept) > 0 || diagnosis=concat('%',dept,'%') || dept='' WHERE admission_date ='$date' and l.labid=2 ORDER by rand() LIMIT $limit";
        
//             mysqli_query($con,$sql);
//             $sir -=$limit;
//         }
        
//     }
}
?>