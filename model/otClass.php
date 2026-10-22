<?php
require_once(__DIR__ . '/session_config.php');
include('dbconnect.php');


class addOT{ 

    function setsutra($sutra)
    {
        $this->sutra=$sutra;
    }
    function setused($used)
    {
        $this->used=$used;
    }
    function setbalance($balance)
    {
        $this->balance=$balance;
    }
    function setpads($pads)
    {
        $this->pads=$pads;
    }
    function setswabs($swabs)
    {
        $this->swabs=$swabs;
    }
    function setnote($note)
    {
        $this->note=$note;
    }
    function setip_no($ip_no)
    {
        $this->ip_no=$ip_no;
    }

  
    function setdoctor($doctor)
    {
        $this->doctor=$doctor;
    }

    function setottype($ottype)
    {
        $this->ottype=$ottype;
    }

    function setotdate($otdate)
    {
        $this->otdate=$otdate;
    }

    function setotprocedure($otprocedure)
    {
        $this->otprocedure=$otprocedure;
    }

    function setotcomments($otcomments)
    {
        $this->otcomments=$otcomments;
    }

    function insertStock()
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="INSERT INTO `stock_register`( `sutra_prepared`, `sutra_used`, `sutra_balance`) VALUES ('$this->sutra','$this->used','$this->balance')";
        // echo $sql;
        $res=mysqli_query($con,$sql);
             $date=date("Y-m-d H:i:s");
           $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();
$con->close();
        return $res;
    }
    function insertPads()
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="INSERT INTO `pad_register`( `pad_prepared`, `pad_used`, `pad_balance`) VALUES ('$this->pads','$this->used','$this->balance')";
        // echo $sql;
        $res=mysqli_query($con,$sql);
        
             $date=date("Y-m-d H:i:s");
           $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();
$con->close();

        return $res;
    }
    function insertSwabs()
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="INSERT INTO `swab_register`( `swab_prepared`, `swab_used`, `swab_balance`) VALUES ('$this->swabs','$this->used','$this->balance')";
        // echo $sql;
        $res=mysqli_query($con,$sql);
            
             $date=date("Y-m-d H:i:s");
           $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();
$con->close();

       
        return $res;
    }
    function insertFumigation()
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="INSERT INTO `fumigation_register`(`note`) VALUES ('$this->note')";
        // echo $sql;
        $res=mysqli_query($con,$sql);
            
             $date=date("Y-m-d H:i:s");
           $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();
$con->close();

       
        return $res;
    }
    function insertClave()
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="INSERT INTO `clave_register`(`note`) VALUES ('$this->note')";
        // echo $sql;
        $res=mysqli_query($con,$sql);
            
             $date=date("Y-m-d H:i:s");
           $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();
$con->close();

       
        return $res;
    }
    function getOT_type()
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT * FROM `ot_type` WHERE `status`='active'";
        $res=mysqli_query($con,$sql);
        return $res;
    }
    function getdoctor()
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT * FROM `doctor` WHERE `role`='doctor' and `status`='active'";
        $res=mysqli_query($con,$sql);
        return $res;
    }
    function getOT_details($id)
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT * FROM `ot_procedures` WHERE `ot_id`='$id' and `status`='active'";
        // echo $sql;exit;
        $res=mysqli_query($con,$sql);
        return $res;
    }
    function insertOTReg()
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="INSERT INTO `operation_theater`( `patient_id`, `date`, `doctor`, `ot_type`, `comment`, `ot_procedure`) VALUES ('$this->ip_no','$this->otdate','$this->doctor','$this->ottype','$this->otcomments','$this->otprocedure')";
        // echo $sql;exit;
        $res=mysqli_query($con,$sql);
            
             $date=date("Y-m-d H:i:s");
           $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();
$con->close();

       
        return $res;
    }
       function getOTReg($id)
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT ot.id,ot.patient_id,ot.date,ot.comment,ot.doctor,o.ot_name,op.ot_procedure FROM ot_type o,`operation_theater` ot left join ot_procedures op on  op.id=ot.ot_procedure WHERE ot.`patient_id`='$id'  and o.id=ot.ot_type";
        // echo $sql;exit;
        $res=mysqli_query($con,$sql);
        return $res;
    }
}
?>