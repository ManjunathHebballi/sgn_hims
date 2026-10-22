<?php
require_once(__DIR__ . '/session_config.php');
include('dbconnect.php');


class medicine{
    
     function setName($name)
    {
        $this->name=$name;
    }
    
    function setaddress($address)
    {
        $this->address=$address;
    }
    function setStatus($status)
    {
        $this->status=$status;
    }
    function setstrength($strength)
    {
        $this->strength=$strength;
    }
    function setgeniric($geniric)
    {
        $this->geniric=$geniric;
    }
    function setunit($unit)
    {
        $this->unit=$unit;
    }
    function setshelf($shelf)
    {
        $this->shelf=$shelf;
    }
    function setdetails($details)
    {
        $this->details=$details;
    }
    function setcategory($category)
    {
        $this->category=$category;
    }
    function settype($type)
    {
        $this->type=$type;
    }
    function setmanufacture($manufacture)
    {
        $this->manufacture=$manufacture;
    }
    function setprice($price)
    {
        $this->price=$price;
    }
    function setmprice($mprice)
    {
        $this->mprice=$mprice;
    }
    function setgst($gst)
    {
        $this->gst=$gst;
    }
    function sethsn($hsn)
    {
        $this->hsn=$hsn;
    }
    function setQty($qty)
    {
        $this->qty=$qty;
    }
    
    function setmrp($mrp)
    {
        $this->mrp=$mrp;
    }
    
    function settax($tax)
    {
        $this->tax=$tax;
    }
    function setdiscunt($discunt)
    {
        $this->discunt=$discunt;
    }
    function setdisc($disc)
    {
        $this->disc=$disc;
    }
    function setgtotal($gtotal)
    {
        $this->gtotal=sprintf("%.2f",$gtotal);
    }
    function setpamount($pamount)
    {
        $this->pamount=$pamount;
    }
    function setmedicine($medicine)
    {
        $this->medicine=$medicine;
    }
    function setbatch($batch)
    {
        $this->batch=$batch;
    }
    function setexdate($exdate)
    {
        $this->exdate=$exdate;
    }
    function setboxqunt($boxqunt)
    {
        $this->boxqunt=$boxqunt;
    }
    function setqunt($qunt)
    {
        $this->qunt=$qunt;
    }
    function setfreequnt($freequnt)
    {
        $this->freequnt=$freequnt;
    }
    function setpayment($payment)
    {
        $this->payment=$payment;
    }
    function setdate($date)
    {
        $this->date=$date;
    }
    function setpurdate($purdate)
    {
        $this->purdate=$purdate;
    }
    function setinvoice($invoice)
    {
        $this->invoice=$invoice;
    }
    
    function setpname($pname) 
      {
          $this->pname = $pname;
      }
      function setpnum($pnum) 
      {
          $this->pnum = $pnum;
      }
      
      function setdoctor($doctor) 
      {
          $this->doctor = $doctor;
      }
      
       function setquantity($quantity) 
      {
          $this->quantity = $quantity;
      }
    function settotal($total) 
      {
          $this->total = $total;
      }
    function setindiscount($indiscount) 
      {
          $this->indiscount = $indiscount;
      }
      function setgrand($grand) 
      {
          $this->grand = $grand;
    }
    
    
     function setpatient_id($patient_id) 
      {
          $this->patient_id = $patient_id;
    }
     function setpur_id($pur_id) 
      {
          $this->pur_id = $pur_id;
    }
     function setret($ret) 
      {
          $this->ret = $ret;
    }
     function setdeduction($deduction) 
      {
          $this->deduction = $deduction;
    }
     function settotal_deduction($total_deduction) 
      {
          $this->total_deduction = $total_deduction;
    }
     function setnet_return($net_return) 
      {
          $this->net_return = $net_return;
    }
     function setptype($ptype) 
      {
          $this->ptype = $ptype;
    }
    function setinvoice_no($invoice_no) 
      {
          $this->invoice_no = $invoice_no;
    }
    function setpayment_mode($payment_mode) 
      {
          $this->payment_mode = $payment_mode;
    }
    
    function setsub_store($sub_store) 
      {
          $this->sub_store = $sub_store;
    }
    function setpriority($prio)
    {
        $this->priority=$prio;
    }
    function setindent_id($indent_id)
    {
        $this->indent_id=$indent_id;
    }
    
     function insertCategory()
    {
        $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="INSERT INTO `medicine_category` (`cat_name`,`status`) VALUES ('$this->name','$this->status')";
          $res=mysqli_query($con,$sql);
          return $res;
    }
      function getCategories()
    {
        $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT * FROM `medicine_category` ";
          $res=mysqli_query($con,$sql);
          return $res;
    }
      function getCategory($id)
    {
        $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT * FROM `medicine_category` WHERE id=$id";
          $res=mysqli_query($con,$sql);
          return $res;
    }
      function updateCategory($id)
    {
        $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="UPDATE `medicine_category` SET `cat_name`='$this->name',`status`='$this->status' WHERE id=$id";
          $res=mysqli_query($con,$sql);
          return $res;
    }
    
    function insertUnit()
    {
        $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="INSERT INTO `medicine_units` (`unit_name`,`status`) VALUES ('$this->name','$this->status')";
          $res=mysqli_query($con,$sql);
          return $res;
    }
      function getUnits()
    {
        $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT * FROM `medicine_units` ";
          $res=mysqli_query($con,$sql);
          return $res;
    }
      function getUnit($id)
    {
        $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT * FROM `medicine_units` WHERE id=$id";
          $res=mysqli_query($con,$sql);
          return $res;
    }
      function updateUnit($id)
    {
        $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="UPDATE `medicine_units` SET `unit_name`='$this->name',`status`='$this->status' WHERE id=$id";
          $res=mysqli_query($con,$sql);
          return $res;
    }
    
    
     function insertType()
    {
        $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="INSERT INTO `medicine_types` (`type_name`,`status`) VALUES ('$this->name','$this->status')";
          $res=mysqli_query($con,$sql);
          return $res;
    }
      function getTypes()
    {
        $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT * FROM `medicine_types` ";
          $res=mysqli_query($con,$sql);
          return $res;
    }
      function getType($id)
    {
        $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT * FROM `medicine_types` WHERE id=$id";
          $res=mysqli_query($con,$sql);
          return $res;
    }
      function updateType($id)
    {
        $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="UPDATE `medicine_types` SET `type_name`='$this->name',`status`='$this->status' WHERE id=$id";
          $res=mysqli_query($con,$sql);
          return $res;
    }
    function getManufacture()
    {
          $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT * FROM `pharma_manufacturer` WHERE status='Active' ";
          $res=mysqli_query($con,$sql);
          return $res;
    }
      function getTypes1()
    {
        $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT * FROM `medicine_types` WHERE status='Active'";
          $res=mysqli_query($con,$sql);
          return $res;
    }
      function getUnits1()
    {
        $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT * FROM `medicine_units` WHERE status='Active'";
          $res=mysqli_query($con,$sql);
          return $res;
    }
       function getCategories1()
    {
        $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT * FROM `medicine_category` WHERE status='Active' ";
          $res=mysqli_query($con,$sql);
          return $res;
    }
    
    function insertMedicine()
    {
          $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="INSERT INTO `medicine`(`name`, `med_strenght`, `generic_name`, `shelf`, `med_details`, `med_price`, `med_manuf`, `manuf_price`, `med_category`, `med_type`, `med_unit`,`gst`,`hsn`) 
          VALUES ('$this->name','$this->strength','$this->geniric','$this->shelf','$this->details','$this->price','$this->manufacture','$this->mprice','$this->category','$this->type','$this->unit','$this->gst','$this->hsn')";
          $res=mysqli_query($con,$sql);
          return $res;
    }
    function getMedicine()
    {
          $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="select me.name,me.shelf,me.med_price,me.manuf_price,me.med_strenght,me.generic_name,me.id from medicine me order by me.name";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
    }
    function getMedicine1()
    {
          $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="select m.*,me.name,me.shelf,me.med_price,me.manuf_price,me.med_strenght,me.generic_name,me.id from medicine me,medicine_purchase m where m.medicine_id=me.id group by me.id order by me.name";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
    }
    function getMedicinebyid($id)
    {
          $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="select group_concat(m.batch_id)as batch,me.name,me.shelf,me.med_price,me.manuf_price,me.med_strenght,me.generic_name,me.id from medicine me,medicine_purchase m where m.medicine_id=me.id and me.id=$id group by me.id order by me.name";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
    }
    function getMedicineList()
    {
        $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="select * from medicine  order by name";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
    }
    function getMedicineDetial($id)
    {
          $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT m.* FROM `medicine` m  WHERE m.id=$id";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
    }
    function updateMedicine($id)
    {
          $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="UPDATE `medicine` set `name`='$this->name', `med_strenght`='$this->strength', `generic_name`='$this->geniric', `shelf`='$this->shelf', `med_details`='$this->details', `med_price`='$this->price', `med_manuf`='$this->manufacture', `manuf_price`='$this->mprice', `med_category`='$this->category', `med_type`='$this->type', `med_unit`='$this->unit',status='$this->status',`gst`='$this->gst',`hsn`='$this->hsn' WHERE id=$id";
          $res=mysqli_query($con,$sql);
          return $res;
    }
     function getOPDMedicine($from,$to)
    {
          $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT op.date,op.name,op.opd_no,d.dept_name,CONCAT(u.name,' ',u.last_name) as doctor,op.id FROM central_opd op,department d,user u WHERE  d.id=op.dept_id and op.doctor=u.id and date(op.date) between '$from' and '$to' group by op.id";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
    }
    function getOPDMedicine1($from,$to,$search)
    {
          $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          if(strlen($search) > 0)
          {
              $cond=" and op.name like '%$search%' || op.opd_no like '$search%' || op.mobile like '$search%'";
          }
          else{
              $cond=" and date(op.date) between '$from' and '$to'";
          }
          $sql="SELECT op.date,op.name,op.opd_no,d.dept_name,CONCAT(u.name,' ',u.last_name) as doctor,op.id FROM central_opd op,department d,user u WHERE  d.id=op.dept_id and op.doctor=u.id $cond group by op.id";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
    }
    function getPatientname($id)
    {
        $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT op.date, op.name,op.opd_no,d.dept_name,CONCAT(u.name,' ',u.last_name) as doctor,op.id FROM central_opd op, department d,user u WHERE  d.id=op.dept_id and op.doctor=u.id and op.id=$id";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
    }
    function getPharmaBill($id,$inv)
    {
        $newlogin = new userlogin;                  
      $con=$newlogin->dbconnect();
      $sql="SELECT o.*,m.name FROM `op_medicine` o,medicine m WHERE m.id=o.medicine and patient_id='$id' and invoice_no='$inv'";
    //   echo $sql;
      $res=mysqli_query($con,$sql);
      return $res;
    }
    
          function getleafs()
    {
        $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT * FROM `medicine_leaf` ";
          $res=mysqli_query($con,$sql);
          return $res;
    }
      function getleaf($id)
    {
        $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT * FROM `medicine_leaf` WHERE id=$id";
          $res=mysqli_query($con,$sql);
          return $res;
    }
      function updateLeaf($id)
    {
        $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="UPDATE `medicine_leaf` SET `leaf_type`='$this->name',`number`='$this->qty',`status`='$this->status' WHERE id=$id";
          $res=mysqli_query($con,$sql);
          return $res;
    }
     function insertLeaf()
    {
        $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="INSERT INTO `medicine_leaf` (`leaf_type`,`number`,`status`) VALUES ('$this->name','$this->qty','$this->status')";
          $res=mysqli_query($con,$sql);
          return $res;
    }
    
    function insertPurchase()
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        
        $sql3="SELECT * FROM manufacturer_ledger where invoice='$this->invoice' and credit>0 limit 1";
        $res3=mysqli_query($con,$sql3);
        $row3=mysqli_fetch_assoc($res3);
        if(mysqli_num_rows($res3)==0)
        {
         $query="SELECT table_id FROM `data_table` where id=3";
         
        $purchase=mysqli_fetch_row(mysqli_query($con,$query));
        $id=$purchase['0']+1;
        $sql1="INSERT INTO `manufacturer_ledger`( `purchase_id`,`manuf_id`, `invoice`, `date`, `desc`, `debit`, `credit`, `mode`) VALUES ('$id','$this->manufacture','$this->invoice','$this->purdate','$this->details','0','$this->gtotal','$this->payment')";
        // echo $sql1;
        mysqli_query($con,$sql1);
        $sql2="INSERT INTO `manufacturer_ledger`( `purchase_id`,`manuf_id`, `invoice`, `date`, `desc`, `debit`, `credit`, `mode`) VALUES ('$id','$this->manufacture','$this->invoice','$this->purdate','$this->details','$this->pamount','0','$this->payment')";
        mysqli_query($con,$sql2);
        }
        else{
            $id=$row3['purchase_id'];
            $credit=$row3['credit']+$this->gtotal;
            $sql4="UPDATE manufacturer_ledger set credit='$credit' where id='$row3[id]'";
            mysqli_query($con,$sql4);
        }
        // echo $sql2;
        // exit;
        $medic=$this->medicine;
        $batch=$this->batch;
        $exp=$this->exdate;
        $box=$this->boxqunt;
        $qty=$this->qunt;
        $free=$this->freequnt;
        $price=$this->mprice;
        $mrp=$this->mrp;
        $tax=$this->tax;
        $disc=$this->discunt;
        $discount=$this->disc;
       
        for($i=0;$i<count($medic);$i++)
        {
            $freeqty=(($qty[$i]/$box[$i])*$free[$i]);
            $totqty=$qty[$i]+$freeqty;
            $exp_date=date("Y-m-01",strtotime($exp[$i]));
        $sql="INSERT INTO `medicine_purchase`( `purchase_id`,`manuf_id`, `date`, `invoice_no`, `medicine_id`, `batch_id`, `exp_date`, `box_qty`, `total_qty`, `current_qty`, `manf_price`, `box_mrp`, `tax`, `discount`,`free_qty`) VALUES ('$id','$this->manufacture','$this->date','$this->invoice','$medic[$i]','$batch[$i]','$exp_date','$box[$i]','$totqty','$totqty','$price[$i]','$mrp[$i]','$tax[$i]','$discount[$i]','$freeqty')";
        $res=mysqli_query($con,$sql);
        $disc=0;
        }
        $response=mysqli_insert_id($con);
        if(mysqli_num_rows($res3)==0)
        {
        $update="UPDATE `data_table` set table_id=$id where id=3";
        mysqli_query($con,$update);
        }
        return $response;
    }
    
    function insertManufactureLedger()
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $query="SELECT table_id FROM `data_table` where id=3";
        $purchase=mysqli_fetch_row(mysqli_query($con,$query));
        $id=$purchase['0']+1;
        $sql1="INSERT INTO `manufacturer_ledger`( `purchase_id`,`manuf_id`, `invoice`, `date`, `desc`, `debit`, `credit`, `mode`) VALUES ('$id','$this->manufacture','$this->invoice','$this->purdate','$this->details','0','$this->gtotal','$this->payment')";
        // echo $sql1;
        mysqli_query($con,$sql1);
        $sql2="INSERT INTO `manufacturer_ledger`( `purchase_id`,`manuf_id`, `invoice`, `date`, `desc`, `debit`, `credit`, `mode`) VALUES ('$id','$this->manufacture','$this->invoice','$this->purdate','$this->details','$this->pamount','0','$this->payment')";
        $res=mysqli_query($con,$sql2);
        $update="UPDATE `data_table` set table_id=$id where id=3";
        mysqli_query($con,$update);
        return $res;
    }
    
    function insertPurchaseBulk($i)
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
         $query="SELECT table_id FROM `data_table` where id=3";
        $purchase=mysqli_fetch_row(mysqli_query($con,$query));
        $id=$purchase['0']+1;
            $freeqty=$this->freequnt;
            $totqty=$this->qunt+$freeqty;
        $sql="INSERT INTO `medicine_purchase`( `purchase_id`,`manuf_id`, `date`, `invoice_no`, `medicine_id`, `batch_id`, `exp_date`, `box_qty`, `total_qty`, `current_qty`, `manf_price`, `box_mrp`, `tax`, `discount`,`free_qty`) VALUES ('$id','$this->manufacture','$this->date','$this->invoice','$this->medicine','$this->batch','$this->exdate','$this->boxqunt','$totqty','$totqty','$this->mprice','$this->mrp','$this->tax','$this->disc','$freeqty')";
        $res=mysqli_query($con,$sql);

        $response=mysqli_insert_id($con);
        
       
        return $response;
    }
    
    
    function getManufactureLedger($id,$from,$to)
    {
        $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT m.*,(select sum(debit)-sum(credit) from  `manufacturer_ledger`  where manuf_id=m.manuf_id and date <='$from' and id <= m.id) as balance FROM `manufacturer_ledger` m WHERE m.manuf_id=$id and m.date between '$from' and '$to'";
        //   echo $sql;
          $res=mysqli_query($con,$sql);
          return $res;
    }
    
    function getManufactureBalance($id,$from)
    {
        $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="select sum(debit)-sum(credit) as balance from  `manufacturer_ledger`  where manuf_id=$id and date <'$from'";
          $res=mysqli_fetch_assoc(mysqli_query($con,$sql));
          return $res['balance']=='' ? '0.00' : $res['balance'] ;
    }
    
    function getPurchaseDetails()
    {
        $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT l.credit,l.purchase_id,l.manuf_id,l.invoice,l.date,m.manuf_name from `manufacturer_ledger` l,`pharma_manufacturer` m where m.id=l.manuf_id and l.credit > 0  group by invoice";
          $res=mysqli_query($con,$sql);
          return $res;
    }
    function getBillingDetails($id)
    {
         $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT m.manuf_name,`manuf_mobile`, `manuf_email`, `manuf_address`,l.date,l.invoice from `manufacturer_ledger` l,`pharma_manufacturer` m where m.id=l.manuf_id and l.purchase_id = $id limit 1";
          $res=mysqli_query($con,$sql);
          return $res;
    }
     
         function insertWalkinBill($bill)
      {
        $newlogin = new userlogin;
          $con=$newlogin->dbconnect();
          
         $location= strtoupper(substr($_SESSION['pharma_location'], 0, 2));
          $year=date('Ym');
          
          $sql2="select current_qty,exp_date,tax from medicine_purchase where medicine_id='$this->medicine' and batch_id='$this->batch'";
          $result2=mysqli_query($con,$sql2);
          $row=mysqli_fetch_assoc($result2);
          $qty=$row['current_qty'] - $this->quantity;
          
          
          $sql1="update medicine_purchase set current_qty=$qty where medicine_id='$this->medicine' and batch_id='$this->batch'";
     
            $res1=mysqli_query($con,$sql1);
            $sql="INSERT INTO `walkin_pharma_bill` (`patientname`,`mob_no`,`date`,`medecine`,`batch`,`quantity`,`price`,`total_amt`,`discount_amt`,`grand_amt`,`type`,`bill_no`,`exp_date`,`tax`,`address`,`doctor`,`payment_mode`,`collected_by`)
            VALUES ('$this->pname','$this->pnum','$this->date','$this->medicine','$this->batch','$this->quantity','$this->price',' $this->total','$this->indiscount','$this->grand','Walk In','PM$location$year/$bill','$row[exp_date]','$row[tax]','$this->address','$this->doctor','$this->payment_mode','$_SESSION[name]')";

// echo $sql;exit;
         
          $result=mysqli_query($con,$sql);
          return $result;
      }
         function getInvoiceNo()
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
         $sql3="select * from data_table where id=12";
        $res3=mysqli_fetch_assoc(mysqli_query($con,$sql3));
        $inv=$res3['table_id']+1;
        return $inv;
    }
    function updateInvoiceNo($inv)
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
         $sql="update data_table set table_id=$inv where id=12";
         mysqli_query($con,$sql);
         return $inv;
    }
       function getBillNo()
     {
          $newlogin = new userlogin;
        $con=$newlogin->dbconnect();
        $date=date("Y-m-d H:i:s");
            $sql="SELECT bill_no FROM `walkin_pharma_bill` order by bill_no desc limit 1";
        $result=mysqli_query($con,$sql);
        if(mysqli_num_rows($result)==0)
        {
            $res=1;
        }
        else{
            $row=mysqli_fetch_assoc($result);
            $res=$row['bill_no'] + 1;
        }
        return $res;
     }
      function getWalkinPharmaBill()
      {
        $newlogin = new userlogin;
          $con=$newlogin->dbconnect();
        
        //   $sql= " SELECT p.`bill_no`,p.`patientname`,p.`doctor`,p.`medecine` , GROUP_CONCAT(m.`exp_date` SEPARATOR '<br/><br/>') as expdate , GROUP_CONCAT(p.`quantity` SEPARATOR '<br/><br/>') as quantity , GROUP_CONCAT(p.`price` SEPARATOR '<br/><br/>') as price , GROUP_CONCAT(p.`total_amt` SEPARATOR '<br/><br/>') as total_amt , GROUP_CONCAT(me.`name` SEPARATOR '<br/><br/>') as medicinename,p.`date` from `walkin_pharma_bill` p, `medicine_purchase` m,`medicine` me where p.`medecine`=m.`medicine_id` and me.id=m.medicine_id group by p.bill_no order by p.`id` desc";
            $sql="SELECT p.`bill_no`, p.`patientname`, p.`doctor`, p.`medecine`,
    (SELECT GROUP_CONCAT(m.`exp_date` SEPARATOR '<br/><br/>') 
     FROM `medicine_purchase` m 
     WHERE m.`medicine_id` = p.`medecine`) AS expdate,
    (SELECT GROUP_CONCAT(p2.`quantity` SEPARATOR '<br/><br/>') 
     FROM `walkin_pharma_bill` p2 
     WHERE p2.`bill_no` = p.`bill_no`) AS quantity,
    (SELECT GROUP_CONCAT(p3.`price` SEPARATOR '<br/><br/>') 
     FROM `walkin_pharma_bill` p3 
     WHERE p3.`bill_no` = p.`bill_no`) AS price,
    (SELECT GROUP_CONCAT(p4.`total_amt` SEPARATOR '<br/><br/>') 
     FROM `walkin_pharma_bill` p4 
     WHERE p4.`bill_no` = p.`bill_no`) AS total_amt,
    GROUP_CONCAT(me.`name` SEPARATOR '<br/><br/>') AS medicinename,
    p.`date`
FROM `walkin_pharma_bill` p
JOIN `medicine` me ON p.`medecine` = me.id
GROUP BY p.`bill_no`
ORDER BY p.`id` DESC";
            // echo $sql;
          $result=mysqli_query($con,$sql);
          return $result;
      }
       function getDetails($id)
      {
        $newlogin = new userlogin;
          $con=$newlogin->dbconnect();
           $sql=" SELECT * from `walkin_pharma_bill` where  `bill_no`=$id limit 1";
           // echo "$sql";
          $result=mysqli_query($con,$sql);
          return $result;
      }
      function getWalkinPharmaBillP($id,$inv)
      {
        $newlogin = new userlogin;
          $con=$newlogin->dbconnect();
        //   $sql=" SELECT p.*, me.name as mname,me.* from `walkin_pharma_bill` p, `medicine_purchase` m,`medicine` me where p.`medecine`=m.`medicine_id`and m.medicine_id=me.id and p.`bill_no`=$id group by m.id";
            $sql="SELECT DISTINCT p.*, me.name as mname, me.* FROM `walkin_pharma_bill` p JOIN `medicine_purchase` m ON p.`medecine` = m.`medicine_id` JOIN `medicine` me ON m.`medicine_id` = me.id WHERE p.`bill_no` = 'AHCP-$inv'";
            // echo "$sql";
          $result=mysqli_query($con,$sql);
          return $result;
      }
      function getwalkinpatient($id,$inv)
      {
         $newlogin = new userlogin;
          $con=$newlogin->dbconnect();
            $sql="SELECT  * FROM `walkin_pharma_bill` p WHERE p.`bill_no` = 'AHCP-$inv'  limit 1";
            // echo "$sql";
          $result=mysqli_query($con,$sql);
          return $result; 
      }
      function getPurchaseDetils1($id)
      {
          $newlogin = new userlogin;
          $con=$newlogin->dbconnect();
          $sql="SELECT date,invoice_no,batch_id,exp_date,box_qty,total_qty,free_qty,manf_price,box_mrp,tax,m.name,mp.discount,manuf_id,mp.id,mp.medicine_id,(select sum(total_qty) from medicine_purchase where medicine_id=m.id)as stock FROM `medicine_purchase` mp,medicine m where mp.purchase_id='$id' and medicine_id=m.id";
        //  echo $sql;
          $result=mysqli_query($con,$sql);
          return $result;
      }
      function getPurchaseDetils($id)
      {
          $newlogin = new userlogin;
          $con=$newlogin->dbconnect();
          $sql="SELECT * FROM `manufacturer_ledger`  where purchase_id='$id' ";
         
          $result=mysqli_query($con,$sql);
          return $result;
      }
      function getMedicineId($name)
      {
        $newlogin = new userlogin;
        $con=$newlogin->dbconnect();
        $name=preg_replace("/[\'\"]+/", "", $name);
        $sql="SELECT id FROM medicine where name like '$name%' order by id desc limit 1";
        // echo $sql;exit;
        $res=mysqli_query($con,$sql);
        $result=mysqli_fetch_assoc($res);
        if(mysqli_num_rows($res)>0)
        {
            $id=$result['id'];
        }
        else{
            $sql1="INSERT INTO medicine(`name`) VALUES ('$name')";
            // echo $sql1;
            mysqli_query($con,$sql1);
            $id=mysqli_insert_id($con);
        }
        // exit;
          return $id;
      }
      
       function getPatientreturn($id)
      {
         $newlogin = new userlogin;
          $con=$newlogin->dbconnect();
            $sql="(SELECT  o.patient_id,c.name,o.date,'OP' as type FROM `op_medicine` o,central_opd c WHERE o.`patient_id` = c.id and o.invoice_no='$id'  limit 1) UNION ALL 
                  (SELECT  o.patient_id, c.name,o.date,'IP' as type FROM `ip_medicine` o,central_ipd c WHERE o.`patient_id` = c.ipd_no and o.invoice_no='$id'  limit 1) UNION ALL
                  (SELECT  id as patient_id, patientname as name,date,'Walk In' as type FROM `walkin_pharma_bill`  WHERE bill_no='$id'  limit 1)";
                //   echo $sql;
          $result=mysqli_query($con,$sql);
          return $result; 
      }
      
       function getReturnMedicine($id)
      {
         $newlogin = new userlogin;
          $con=$newlogin->dbconnect();
            $sql="(select o.id,m.name,o.qty,o.total,o.price from medicine m,op_medicine o where m.id=o.medicine and o.invoice_no='$id') UNION ALL
                  (select o.id,m.name,o.qty,o.total,o.price from medicine m,ip_medicine o where m.id=o.medicine and o.invoice_no='$id') UNION ALL
                  (select o.id,m.name,o.quantity as qty,o.total_amt as total,o.price from medicine m,walkin_pharma_bill o where m.id=o.medecine and o.bill_no='$id')";
                //   echo $sql;
          $result=mysqli_query($con,$sql);
          return $result; 
      }
      
      function insertCustReturn()
      {
        $newlogin = new userlogin;
         $con=$newlogin->dbconnect();
         if($this->ret!='0')
         {
          $sql="INSERT INTO `return_customer` (`patient_id`, `purchase_id`, `returned_qty`, `deduction_percent`, `deduction_amount`, `returned_amount`, `type`, `return_date`, `returned_by`,`invoice_no`) VALUES  ('$this->patient_id', '$this->pur_id', '$this->ret', '$this->deduction', '$this->total_deduction', '$this->net_return', '$this->ptype', NOW(), '$_SESSION[name]','$this->invoice_no')";
    //   echo $sql;
    
          $sql1="update op_medicine set qty=qty-$this->ret,returned_qty=$this->ret where id=$this->pur_id ";
          mysqli_query($con,$sql1);
          $sql2="update medicine_purchase set current_qty=current_qty+$this->ret where medicine_id=(select medicine from op_medicine where id=$this->pur_id) and batch_id=( select batch from op_medicine where id=$this->pur_id)";
           mysqli_query($con,$sql2);
        //   echo $sql2;
        //   exit;
        $result=mysqli_query($con,$sql);
         }
         return $result; 
          
      }
      
      function getInvoiceList($from,$to)
      {
           $newlogin = new userlogin;
          $con=$newlogin->dbconnect();
            $sql="((select c.name,c.mobile,'OP' as ptype,o.invoice_no,o.date,o.grand_total as total from op_medicine o,central_opd c where o.date between '$from' and '$to' and o.patient_id=c.id group by o.invoice_no) UNION ALL 
            (select c.name,c.mobile,'IP' as ptype,o.invoice_no,o.date,sum(o.grand_total) as total from ip_medicine o,central_ipd c where o.date between '$from' and '$to' and o.patient_id=c.ipd_no group by o.invoice_no) UNION ALL 
            (select patientname as name,mob_no as mobile,'Walk In' as ptype,bill_no as invoice_no,date,sum(total_amt) as total from walkin_pharma_bill where date between '$from' and '$to' group by bill_no)) order by date,invoice_no";
            // echo $sql;
          $result=mysqli_query($con,$sql);
          return $result; 
      }
      
        function getDoctor()
        {
          $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $day=date("l");
          $sql="SELECT u.* FROM `user` u,`staff_timetable` st WHERE role='Doctor' and u.`id`=st.`doctor_id` and st.`day`='$day'";
          $res=mysqli_query($con,$sql);
          return $res;
        }
    function updatePurchase($edit,$id)
    {
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        
        $sql3="UPDATE manufacturer_ledger set `manuf_id`='$this->manufacture',`invoice`='$this->invoice',`date`='$this->purdate',`desc`='$this->details',`credit`='$this->gtotal',`mode`='$this->payment' where purchase_id='$id' and credit>0 limit 1";
        $res3=mysqli_query($con,$sql3);
        // echo $sql2;
        // exit;
        $medic=$this->medicine;
        $batch=$this->batch;
        $exp=$this->exdate;
        $box=$this->boxqunt;
        $qty=$this->qunt;
        $free=$this->freequnt;
        $price=$this->mprice;
        $mrp=$this->mrp;
        $tax=$this->tax;
        $disc=$this->discunt;
        $discount=$this->disc;
       
        for($i=0;$i<count($medic);$i++)
        {
            $leaf=($qty[$i]/$box[$i]);
            if(is_nan($leaf))
            {
                $leaf=1;
            }
            $exp_date=date("Y-m-01",strtotime($exp[$i]));
            $freeqty=($leaf*$free[$i]);
            $totqty=$qty[$i]+$freeqty;
        $sql="UPDATE `medicine_purchase` set  `manuf_id`='$this->manufacture', `date`='$this->date', `invoice_no`='$this->invoice', `medicine_id`='$medic[$i]', `batch_id`='$batch[$i]', `exp_date`='$exp_date', `box_qty`='$box[$i]', `total_qty`='$totqty', `current_qty`='$totqty', `manf_price`='$price[$i]', `box_mrp`='$mrp[$i]', `tax`='$tax[$i]', `discount`='$discount[$i]',`free_qty`='$freeqty' where purchase_id='$id' and id='$edit[$i]'";
        // echo $sql.'<br>';
        $res=mysqli_query($con,$sql);
        $disc=0;
        }
        $response=mysqli_insert_id($con);
        return $response;
    }
    
    function getMedicine4Purchase()
    {   
         $newlogin = new userlogin;                  
          $con=$newlogin->dbconnect();
          $sql="SELECT m.*,u.unit_name FROM `medicine` m left join medicine_units u on m.med_unit=u.id where m.status='active' order by rand() limit 1650";
          $res=mysqli_query($con,$sql);
          return $res;
    }
       function insertOPMedic($id)
    {
        $date=date("Y-m-d");
        $newlogin = new userlogin;                  
        $con=$newlogin->dbconnect();
        $sql="INSERT INTO `op_medicine`(`patient_id`, `date`, `medicine`, `qty`,`price`,`total`,`discount`,`grand_total`,`invoice_no`,`payment_mode`,`batch`,`tax`,`exp_date`) VALUES 
        ('$id','$date','$this->medicine','$this->quantity','$this->price','$this->total','$this->indiscount','$this->grand','$this->invoice','$this->payment','$this->batch','$this->tax','$this->exdate')";
        // echo $sql;
        // exit();
        
        
         $sql2="select current_qty from medicine_purchase where medicine_id='$this->medicine' and batch_id='$this->batch'";
          $result2=mysqli_query($con,$sql2);
          $row=mysqli_fetch_assoc($result2);
          $qty=$row['current_qty'] - $this->quantity;
          
          
          $sql1="update medicine_purchase set current_qty=$qty where medicine_id='$this->medicine' and batch_id='$this->batch'";
        //      echo $sql1;
        // exit;
            $res1=mysqli_query($con,$sql1);
       
        
        $res=mysqli_query($con,$sql);
        return $res;
    }
    function updateDiscOP($id)
    {
        $newlogin = new userlogin;
        $con=$newlogin->dbconnect();
        $sql="UPDATE `op_medicine` SET `discount`='$this->indiscount' WHERE `patient_id`='$id' and discount=0 limit 1";
        $result=mysqli_query($con,$sql);
        return $result;
    }
    function getSubstore()
    {
        $newlogin = new userlogin;
        $con=$newlogin->dbconnect();
        $sql="SELECT * FROM sub_store WHERE status='active'";
        $result=mysqli_query($con,$sql);
        return $result;
    }
    function addIndent()
    {
        $newlogin = new userlogin;
        $con=$newlogin->dbconnect();
        $medicine=$this->medicine;
        $req_qty=$this->qty;
        
        $financialYear = $this->getFinancialYear();
        
        $sql3="select * from data_table where id=13";
        $res3=mysqli_fetch_assoc(mysqli_query($con,$sql3));
        $ind= $res3['table_id']+1;
        mysqli_query($con,"update data_table set table_id=$ind where id=13");
        $inv='INDE/'.$financialYear.'/'.sprintf("%04d",$ind);
        foreach($medicine as $key => $med)
        {
            if($med>0)
            {
                $sql="INSERT INTO indent (store_id,medicine_id,req_qty,indent_no,indent_date,priority,status,indent_created_by) values('$this->sub_store','$med','$req_qty[$key]','$inv','$this->date','$this->priority','pending','$_SESSION[name]')";
                $result=mysqli_query($con,$sql);
            }
        }
        
        return $result;
    }
    function getIndent()
    {
        $newlogin = new userlogin;
        $con=$newlogin->dbconnect();
        $sql="SELECT * FROM indent WHERE (('$_SESSION[role]'!='Pharmacist' and status ='pending') ||  status !='received')  and (store_id='$_SESSION[storeid]' || '$_SESSION[role]'!='Pharmacist') group by indent_no ";
        // echo $sql;
        $result=mysqli_query($con,$sql);
        return $result;
    }
    function getIndentIssued()
    {
        $newlogin = new userlogin;
        $con=$newlogin->dbconnect();
        $sql="SELECT * FROM indent WHERE status !='pending' and (store_id='$_SESSION[storeid]' || '$_SESSION[role]'!='Pharmacist') group by indent_no ";
        // echo $sql;
        $result=mysqli_query($con,$sql);
        return $result;
    }
    
    function getIndentByNo($inde_no)
    {
        $newlogin = new userlogin;
        $con=$newlogin->dbconnect();
        $sql="SELECT i.*,m.name FROM indent i,medicine m WHERE indent_no='$inde_no' and m.id=i.medicine_id ";
        // echo $sql;
        $result=mysqli_query($con,$sql);
        return $result;
    }
    
    function issueIndent()
    {
        $newlogin = new userlogin;
        $con=$newlogin->dbconnect();
        $indent=$this->indent_id;
        $tax=$this->tax;
        $batch=$this->batch;
        $expdate=$this->exdate;
        $price=$this->price;
        $qty=$this->qty;
        foreach($indent as $key => $id)
        {
            $exdate=date("Y-m-t",strtotime($expdate[$key]));
            $sql="UPDATE indent SET batch='$batch[$key]',exp_date='$exdate',tax='$tax[$key]',price='$price[$key]',issue_qty='$qty[$key]',status='ISSUED',issue_date=now(),issued_by='$_SESSION[name]'   WHERE id=$id";
            // echo $sql;exit;
            $result=mysqli_query($con,$sql);
        }
        return $result;
    }
    function getFinancialYear() {
    //   echo 'here';exit;
    $month = date('n'); // Numeric representation of month (1-12)
    $year = date('Y');

    if ($month >= 4) {
        // April to December
        $startYear = $year;
        $endYear = $year + 1;
    } else {
        // January to March
        $startYear = $year - 1;
        $endYear = $year;
    }

    return "$startYear-" . substr($endYear, -2);
    }
}
?>    