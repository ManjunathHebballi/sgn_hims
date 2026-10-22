<?php
require_once(__DIR__ . '/session_config.php');
include('dbconnect.php');


class addRadio{ 

    function settest_name($test_name)
    {
        $this->test_name=$test_name;
    }
    function setshort_name($short_name)
    {
        $this->short_name=$short_name;
    }
    function settest_type($test_type)
    {
        $this->test_type=$test_type;
    }
    function setradiology_category_id($radiology_category_id)
    {
        $this->radiology_category_id=$radiology_category_id;
    }
    function setsub_category($sub_category)
    {
        $this->sub_category=$sub_category;
    }
    function setmethod($method)
    {
        $this->method=$method;
    }
    function setreport_days($report_days)
    {
        $this->report_days=$report_days;
    }
    function setcharge_category_id($charge_category_id)
    {
        $this->charge_category_id=$charge_category_id;
    }
    function setcharge_name($charge_name)
    {
        $this->charge_name=$charge_name;
    }
    function settax($tax)
    {
        $this->tax=$tax;
    }
    function setstandard_charge($standard_charge)
    {
        $this->standard_charge=$standard_charge;
    }
    function setamount($amount)
    {
        $this->amount=$amount;
    }
    function setparameter($parameter)
    {
        $this->parameter=$parameter;
    }
    function setrange($range)
    {
        $this->range=$range;
    }
    function setunit($unit)
    {
        $this->unit=$unit;
    }
    
    function setpayment_mode($payment_mode)
    {
        $this->payment_mode=$payment_mode;
    }
    function setnote($note)
    {
        $this->note=$note;
    }
    function settest($test)
    {
        $this->test=$test;
    }function setdays($days)
    {
        $this->days=$days;
    }
    function setdate($date)
    {
        $this->date=$date;
    }
    function setgrand_total($grand_total)
    {
        $this->grand_total=$grand_total;
    }
    function settotal_tax($total_tax)
    {
        $this->total_tax=$total_tax;
    }
    function setdiscount($discount)
    {
        $this->discount=$discount;
    }
    function setnet_amount($net_amount)
    {
        $this->net_amount=$net_amount;
    }
    function setpaid_amount($paid_amount)
    {
        $this->paid_amount=$paid_amount;
    }
    function setdue_amount($due_amount)
    {
        $this->due_amount=$due_amount;
    }
   
    
    function insertRadioTest()
    {
         $newlogin = new userlogin;                  
         $con=$newlogin->dbconnect();
         $sql="INSERT INTO `radiology_test` (`test_name`, `short_name`, `test_type`, `category_name`, `sub_category`, `method`, `report_days`, `charge_category`, `charge_name`, `tax`, `standard_charge`, `amount`,`parameter_id`,`range`,`unit`) VALUES ( '$this->test_name', '$this->short_name', '$this->test_type', '$this->radiology_category_id', '$this->sub_category', '$this->method', '$this->report_days', '$this->charge_category_id', '$this->charge_name', '$this->tax', '$this->standard_charge', '$this->amount','$this->parameter','$this->range','$this->unit')";
        //  echo $sql;
        //  exit;
         $res=mysqli_query($con,$sql);
             
             $date=date("Y-m-d H:i:s");
           $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
$sql1->close();
$con->close();

       
         return $res;
    }
      function getRadioCat()
    {
         $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT * FROM radiology_category";
        $res=mysqli_query($con,$sql);
        return $res;
    }
    
     function getRadioPara()
    {
         $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT * FROM radiology_parameter";
        $res=mysqli_query($con,$sql);
        return $res;
    }
    
    function getRadioTest()
    {
         $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="SELECT rt.*,rc.category FROM radiology_test rt,radiology_category rc where rt.category_name=rc.id";
        $res=mysqli_query($con,$sql);
        return $res;
    }
    
    function getOpPatients($op_from,$op_to)
    {
      $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      $sql="select * from central_opd where `date` between '$op_from' and '$op_to' order by `date`";
    //   echo $sql;exit;
      $res=mysqli_query($con,$sql);
      return $res;
    }
    
    function getRadioTest4Bill()
    {
        $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="select * from radiology_test";
        //   echo $sql;exit;
          $res=mysqli_query($con,$sql);
          return $res;
    }
    
    function insertRadioBill($id)
    {   
        $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
        $sql="INSERT INTO `radiology_billing` (`patiend_id`, `payment_mode`, `note`, `test_id`, `report_days`, `report_date`, `tax`, `amount`, `grand_total`, `total_tax_amt`, `discount`, `net_amount`, `paid_amount`, `due_amount`) VALUES ('$id', '$this->payment_mode', '$this->note', '$this->test', '$this->days', '$this->date', '$this->tax', '$this->amount', '$this->grand_total', '$this->total_tax', '$this->discount', '$this->net_amount', '$this->paid_amount', '$this->due_amount')";
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
}