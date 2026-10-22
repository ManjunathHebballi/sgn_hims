<?php
require_once(__DIR__ . '/../model/session_config.php');
include('../model/medicineClass.php');
$newMedicine = new medicine;
// echo '<pre>';
// print_r($_REQUEST);
if($_REQUEST['submit']=='purchase')
{
$newMedicine->setmanufacture($_REQUEST['manufacture']);
$newMedicine->setdate($_REQUEST['pur_date']);
$newMedicine->setpurdate($_REQUEST['pur_date']);
$newMedicine->setinvoice($_REQUEST['invoice']);
$newMedicine->setdetails($_REQUEST['details']);
$newMedicine->setpayment($_REQUEST['payment']);
$newMedicine->setdiscunt($_REQUEST['discunt']);
$newMedicine->setgtotal($_REQUEST['gtotal']);
$newMedicine->setpamount($_REQUEST['pamount']);
$newMedicine->setmedicine($_REQUEST['medicine']);
$newMedicine->setbatch($_REQUEST['batch']);
$newMedicine->setexdate($_REQUEST['exdate']);
$newMedicine->setboxqunt($_REQUEST['boxqunt']);
$newMedicine->setqunt($_REQUEST['qunt']);
$newMedicine->setfreequnt($_REQUEST['freequnt']);
$newMedicine->setdisc($_REQUEST['discprice']);
$newMedicine->setmprice($_REQUEST['mprice']);
$newMedicine->setmrp($_REQUEST['mrp']);
$newMedicine->settax($_REQUEST['tax']);
$result=$newMedicine->insertPurchase();
if($result> 0)
{
    echo "<script>alert('Medicine Purchase is done')</script>";
}
echo "<script>window.location = '../view_purchase.php'</script>";
}
if($_REQUEST['submit']=='bulk_purchase')
{
   
            $strFilePath = $_FILES['csv']['tmp_name'];
            $handle = fopen($strFilePath,"r");
            $row=1;
            $i=1;
            $discount=$gtotal=$tax=0;
            $newMedicine->setmanufacture($_REQUEST['manufacture']);
            $newMedicine->setdate($_REQUEST['pur_date']);
            $newMedicine->setpurdate($_REQUEST['pur_date']);
            $newMedicine->setinvoice($_REQUEST['invoice']);
            $newMedicine->setdetails($_REQUEST['details']);
            $newMedicine->setpayment($_REQUEST['payment']);
            $newMedicine->setpamount($_REQUEST['pamount']);
            while (($csv_data = fgetcsv($handle, 1000, ",")) !== FALSE)
            {   

            if($row==1)
            {
                $row++;
                continue;
                
            }
            else
            {   
                // print_r($csv_data);exit;
            $id=$newMedicine->getMedicineId($csv_data[0]);
            // echo $id;
                if($id > 0)
                {
                    $newMedicine->setmedicine($id);
                    $newMedicine->setbatch($csv_data[1]);
                    $newMedicine->setexdate(date("Y-m-d",strtotime($csv_data[2])));
                    $newMedicine->setboxqunt($csv_data[4]);
                    $newMedicine->setqunt($csv_data[4]*$csv_data[3]);
                    $newMedicine->setfreequnt($csv_data[5]*$csv_data[3]);
                    $disc=($csv_data[8]*$csv_data[4]* $csv_data[6])/100;
                    $newMedicine->setdisc($disc);
                    $newMedicine->setmprice($csv_data[6]);
                    $newMedicine->setmrp($csv_data[7]);
                    $newMedicine->settax($csv_data[9]);
                    $newMedicine->insertPurchaseBulk($i);
                    $tax=((($csv_data[4]* $csv_data[6])-$disc)*$csv_data[9])/100;
                    $gtotal +=($csv_data[4]* $csv_data[6])+$tax-$disc;
                }
            }


            $i++;
            }
            
            if($gtotal>0)
            {
                $newMedicine->setgtotal($gtotal);
                $newMedicine->insertManufactureLedger();
            }
  
    echo "<script>window.location = '../view_purchase.php'</script>";
}
if($_REQUEST['submit']=='edit_purchase')
{
    $newMedicine->setmanufacture($_REQUEST['manufacture']);
    $newMedicine->setdate($_REQUEST['pur_date']);
    $newMedicine->setpurdate($_REQUEST['pur_date']);
    $newMedicine->setinvoice($_REQUEST['invoice']);
    $newMedicine->setdetails($_REQUEST['details']);
    $newMedicine->setpayment($_REQUEST['payment']);
    $newMedicine->setdiscunt($_REQUEST['discunt']);
    $newMedicine->setgtotal($_REQUEST['gtotal']);
    $newMedicine->setpamount($_REQUEST['pamount']);
    $newMedicine->setmedicine($_REQUEST['medicine']);
    $newMedicine->setbatch($_REQUEST['batch']);
    $newMedicine->setexdate($_REQUEST['exdate']);
    $newMedicine->setboxqunt($_REQUEST['boxqunt']);
    $newMedicine->setqunt($_REQUEST['qunt']);
    $newMedicine->setfreequnt($_REQUEST['freequnt']);
    $newMedicine->setdisc($_REQUEST['discprice']);
    $newMedicine->setmprice($_REQUEST['mprice']);
    $newMedicine->setmrp($_REQUEST['mrp']);
    $newMedicine->settax($_REQUEST['tax']);
    $result=$newMedicine->updatePurchase($_REQUEST['edit'],$_REQUEST['purchase_id']);
if($result> 0)
{
    echo "<script>alert('Medicine Purchase is done')</script>";
}
echo "<script>window.location = '../view_purchase.php'</script>";

}
?>