<?php 
require_once(__DIR__ . '/../model/session_config.php');
include('model/ajaxClass.php');

$newajax = new ajax;

if($_REQUEST['page'] == 'ipreg')
{
    $newajax->setopno($_REQUEST['op_no']);
    $flag=0;
    $detail='';
    $res=$newajax->getOPDetails();
    $row=mysqli_fetch_assoc($res);
    // print_r($row);
    foreach($row as $key=>$val)
    {
        if($flag==1)
        {
            $detail .='|';
        }
        $detail .=$val;
            $flag=1;
    }
//    echo $detail;
        echo $detail;
}
if($_REQUEST['page'] == 'opipreg')
{
    $newajax->setopno($_REQUEST['op_no']);
    $flag=0;
    $detail='';
    $res=$newajax->getOPIPDetails();
    $row=mysqli_fetch_assoc($res);
    // print_r($row);
    foreach($row as $key=>$val)
    {
        if($flag==1)
        {
            $detail .='|';
        }
        $detail .=$val;
            $flag=1;
    }
//    echo $detail;
        echo $detail;
}
if($_REQUEST['page'] == 'birthreg')
{
    
    $flag=0;
    $detail='';
    $res=$newajax->getIPDetails4Birth($_REQUEST['ip_no']);
    $row=mysqli_fetch_assoc($res);
    // print_r($row);
    foreach($row as $key=>$val)
    {
        if($flag==1)
        {
            $detail .='|';
        }
        $detail .=$val;
            $flag=1;
    }
//    echo $detail;
        echo $detail;
}

if($_REQUEST['page'] == 'editD')
{
    
    $flag=0;
    $detail='';
    $res=$newajax->getIPDetails4EditD($_REQUEST['id']);
    $row=mysqli_fetch_assoc($res);
    // print_r($row);
    foreach($row as $key=>$val)
    {
        if($flag==1)
        {
            $detail .='|';
        }
        $detail .=$val;
            $flag=1;
    }
//    echo $detail;
        echo $detail;
}
if($_REQUEST['page'] == 'editB')
{
    
    $flag=0;
    $detail='';
    $res=$newajax->getIPDetails4EditB($_REQUEST['id']);
    $row=mysqli_fetch_assoc($res);
    // print_r($row);
    foreach($row as $key=>$val)
    {
        if($flag==1)
        {
            $detail .='|';
        }
        $detail .=$val;
            $flag=1;
    }
//    echo $detail;
        echo $detail;
}
if($_REQUEST['page'] == 'otreg')
{
    
    $flag=0;
    $detail='';
    $res=$newajax->getIPDetails($_REQUEST['ip_no']);
    $row=mysqli_fetch_assoc($res);
    // print_r($row);
    foreach($row as $key=>$val)
    {
        if($flag==1)
        {
            $detail .='|';
        }
        $detail .=$val;
            $flag=1;
    }
//    echo $detail;
        echo $detail;
}

if($_REQUEST['page'] == 'transfer_bed')
{
    
    $flag=0;
    $detail='';
    $res=$newajax->getIPDetails4TransferBed($_REQUEST['ip_no']);
    $row=mysqli_fetch_assoc($res);
    // print_r($row);
    foreach($row as $key=>$val)
    {
        if($flag==1)
        {
            $detail .='|';
        }
        $detail .=$val;
            $flag=1;
    }
//    echo $detail;
        echo $detail;
}
if($_REQUEST['page']=='opd_modal')
{
    ?>
        <div class="table-responsive" id="revist_modal">
                                    <table id="datatable-buttons" class="table table-bordered table-striped display text-center">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>Sl NO</th>
                                              
                                                <th>Visit Data</th>
                                                <th>Department</th>
                                             
                                                <th>Comments</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody class="text-center text-black">
                                            <?php 
                                            $sn=1;
                                            $name='';
                                            $newajax->setopno($_REQUEST['op_no']);
                                            $res=$newajax->getOPRevisit();
                                            while($row=mysqli_fetch_assoc($res))
                                            {
                                                ?>
                                            <tr>
                                            <td><?= $sn?></td>
                                              
                                              <td><?php echo date("d.m.Y",strtotime($row['date']));    ?></td>
                                              <td><?= $row['dept_name']?></td>
                                           
                                              <td><?= $row['diagnosis']?></td>
                                            </tr> 
                                           
                                            <?php $sn++; 
                                        $name=$row['name'];
                                        $dept_id=$row['dept_id'];
                                        }
                                            
                                            ?>
                                            </tbody>
                                            </table>   
                                            </div>  
<?php
echo "<script> document.getElementById('span').innerHTML='( Patient Id: $name | $_REQUEST[op_no] )';
$('#dept option[value=$dept_id]').attr('selected', 'selected');
</script>"  ; 

}
if($_REQUEST['page']=='sub_proce')
{
    ?>
     
                                            <option value="">Select Sub-Procedure</option>
    <?php
    $res=$newajax->setsubproce($_REQUEST['proce']);
    while($row=mysqli_fetch_assoc($res))
    {
?>

                                          
    <option value="<?= $row['id']?>"><?= $row['subprocedure_name']?></option>
                                         
<?php
    }
   
}
if($_REQUEST['page']=='proce_medicine')
{
    ?>
     
                                            <option value="">Select Medicine</option>
    <?php
    $res=$newajax->getProceMedic($_REQUEST['proce']);
    while($row=mysqli_fetch_assoc($res))
    {
?>

                                          
    <option value="<?= $row['id']?>"><?= $row['medicine']?></option>
                                         
<?php
    }
   
}

if($_REQUEST['page']=='lab_del')
{
    // echo "here";
    $newajax->deleteLab($_REQUEST['id']);
    echo "success";
    
}
if($_REQUEST['page']=='bed_del')
{
    // echo "here";
    $newajax->deleteBed($_REQUEST['id']);
    echo "sucess";
    
}
if($_REQUEST['page']=='dept_del')
{
    // echo "here";
    $newajax->deleteDept($_REQUEST['id']);
    echo "sucess";
    
}
if($_REQUEST['page']=='medic_del')
{
    // echo "here";
    $newajax->deleteMedic($_REQUEST['id']);
    
}
if($_REQUEST['page']=='state')
{
        $state=$newajax->getState($_REQUEST['country']);
        echo "<option value=''>Select State</option>";
       while($row=mysqli_fetch_assoc($state)){?>
       
       <option value="<?= $row['id']?>"><?= $row['name']?></option>
       <?php } 
}
if($_REQUEST['page']=='city')
{
        $state=$newajax->getCity($_REQUEST['state']);
        echo "<option value=''>Select City</option>";
       while($row=mysqli_fetch_assoc($state)){?>
       
       <option value="<?= $row['id']?>"><?= $row['name']?></option>
       <?php } 
}
if($_REQUEST['page']=='doctor')
{
        $state=$newajax->getDoctor($_REQUEST['dept']);
        echo "<option value=''>Select Doctor</option>";
       while($row=mysqli_fetch_assoc($state)){?>
       
       <option value="<?= $row['id']?>"><?= $row['name'].' '.$row['last_name']?></option>
       <?php } 
}
if($_REQUEST['page']=='doctor1')
{
        $state=$newajax->getDoctor($_REQUEST['dept']);
        echo "<option value=''>Select Doctor</option>";
       while($row=mysqli_fetch_assoc($state)){?>
       
       <option value="<?= $row['name'].' '.$row['last_name']?>"><?= $row['name'].' '.$row['last_name']?></option>
       <?php } 
}
if($_REQUEST['page']=='TimeTable')
{
    $newajax->setfrom($_REQUEST['from']);
    $newajax->setto($_REQUEST['to']);
    $newajax->setdoctor($_REQUEST['doctor']);
    $data=$newajax->updateTimeTable($_REQUEST['id']);
}
if($_REQUEST['page']=='icd_code')
{
    $data=$newajax->getIcd($_REQUEST['icd']);
    echo $data;
}
if($_REQUEST['page']=='medic')
{
    $data=$newajax->getMedicine($_REQUEST['manufac']);
     echo "<option value=''>Select Medicine</option>";
     while($row=mysqli_fetch_assoc($data))
    { ?>
        <option value="<?= $row['id']?>"><?= $row['name'].' '.$row['med_strenght'].' '.$row['unit_name']?></option>
    <?php }
}
if($_REQUEST['page']=='stock')
{
    $data=$newajax->getStock($_REQUEST['medic']);
    echo $data;
}
if($_REQUEST['page']=='delRCat')
{
    $data=$newajax->delRadioCat($_REQUEST['id']);
}
if($_REQUEST['page']=='delRUnit')
{
    $data=$newajax->delRadioUnit($_REQUEST['id']);
}
if($_REQUEST['page']=='delRPara')
{
    $data=$newajax->delRadioPara($_REQUEST['id']);
}
if($_REQUEST['page']=='delPCat')
{
    $data=$newajax->delPathoCat($_REQUEST['id']);
}
if($_REQUEST['page']=='delPUnit')
{
    $data=$newajax->delPathoUnit($_REQUEST['id']);
}
if($_REQUEST['page']=='delPPara')
{
    $data=$newajax->delPathoPara($_REQUEST['id']);
}
if($_REQUEST['page']=='range')
{
    
     $flag=0;
    $detail='';
    $res=$newajax->getRange($_REQUEST['id']);
    $row=mysqli_fetch_assoc($res);
    // print_r($row);
    foreach($row as $key=>$val)
    {
        if($flag==1)
        {
            $detail .='|';
        }
        $detail .=$val;
            $flag=1;
    }
//    echo $detail;
        echo $detail;
}

if($_REQUEST['page']=='bed')
{
    $data=$newajax->getBed($_REQUEST['dept'],$_REQUEST['ward']);
     echo "<option value=''>Select Bed</option>";
     while($row=mysqli_fetch_assoc($data))
    { ?>
        <option value="<?= $row['id']?>"><?= $row['bed_number']?></option>
    <?php }
}
if($_REQUEST['page']=='ward')
{
    $data=$newajax->getWard($_REQUEST['dept']);
     echo "<option value=''>Select Ward</option>";
     while($row=mysqli_fetch_assoc($data))
    { ?>
        <option value="<?= $row['ward']?>"><?= $row['ward']?></option>
    <?php }
}
if($_REQUEST['page']=='deleteOT')
{
    $data=$newajax->deleteOT($_REQUEST['id']);
    echo $data;
}

if($_REQUEST['page']=='getService')
{
   
     $flag=0;
    $detail='';
    $res=$newajax->getItems($_REQUEST['id']);
    $row=mysqli_fetch_assoc($res);
    // print_r($row);
    foreach($row as $key=>$val)
    {
        if($flag==1)
        {
            $detail .='|';
        }
        $detail .=$val;
            $flag=1;
    }
//    echo $detail;
        echo $detail;
    
}


if($_REQUEST['page']=='bill_cancel')
{
    $data=$newajax->cancel_bill($_REQUEST['bill_no'],$_REQUEST['reason']);
    echo $data;
}



if($_REQUEST['page']=='final_bill')
{
    $data=$newajax->final_bill($_REQUEST['ip_no']);
    echo $data;
}


if($_REQUEST['page']=='advance_cancel')
{
    $data=$newajax->cancel_advance($_REQUEST['adv_no'],$_REQUEST['reason']);
    echo $data;
}
if($_REQUEST['page']=='cancelBill')
{
    $data=$newajax->billcancel($_REQUEST['id']);
    echo $data;
}
if($_REQUEST['page']=='cancelClaimBill')
{
    $data=$newajax->billClaimCancel($_REQUEST['id']);
    echo $data;
}
if($_REQUEST['page']=='cancelESIBill')
{
    $data=$newajax->billESICancel($_REQUEST['id']);
    echo $data;
}
if($_REQUEST['page']=='getPatient')
{
    $data=$newajax->getPatient($_REQUEST['search']);
    while($row=mysqli_fetch_assoc($data))
    {
        echo "<option value='$row[opd_no]-$row[name]-$row[mobile]'>";
    }
    
}

if($_REQUEST['page']=='getRoundAssessments')
{
    echo $newajax->getRoundAssessmentsForBilling($_REQUEST['patient_id']);
}

if($_REQUEST['page']=='change_pwd')
{
    $newajax->changepassword($_REQUEST['opwd'],$_REQUEST['npwd']);
}
if($_REQUEST['page']=='getDept')
{
    $res=$newajax->getDept($_REQUEST['id']);
    $row=mysqli_fetch_assoc($res);
    echo $row['id'];
}
if($_REQUEST['page']=='getPatientBill')
{
    $res1=$newajax->getPatientBill($_REQUEST['ip_no']);
    // print_r(mysqli_fetch_assoc($res));
    $sn=1;
    while($row1=mysqli_fetch_assoc($res1))
    {
        if(strlen($row1['esi_code'])>0) {
            $price=$row1['esi_amount'];
        }
        else{
            $price=$row1['price'];
        }
        if(stripos($row1['bill_no'], 'IP') === 0)
            {
                $bill_no=$row1['bill_no'];
            }
        ?>
        <tr id='addr<?= $sn?>'>
                
              <td class="col-md-3">
                  
                <select name="service[]" id="service<?= $sn?>" class="form-control select2doctor" required onchange="showResult(this.value,<?= $sn?>)">
                    <option>Select Service</option>
                    <?php $res=$newajax->getIItems();
                    while($row=mysqli_fetch_assoc($res))
                    { ?>
                    <option value="<?=$row['id']?>" <?php if($row1['service_id']==$row['id']) echo 'selected';?>><?=$row['name']?></option>
                    <?php } ?>
                </select>
               </td>
               <td>
                    <input type="text" class="form-control" name="esi[]" id="esi<?= $sn?>" value="<?= $row1['esi_code']?>" readonly>
                    
                </td>
               
                <td>
                    <input type="number" class="form-control" name="quantity[]" id="quantity<?= $sn?>" value="<?= $row1['unit']?>" onkeyup="checkQty(<?= $sn?>)" required>
                    
                </td>
                    <input type="hidden" class="form-control" name="service_id[]" id="service_id<?= $sn?>" value="<?= $row1['service_id']?>">
                    <input type="hidden" class="form-control" name="doctor_id[]" id="doctor_id<?= $sn?>" value="<?= $row1['doctor_id']?>">
                <td>
                    <input type="number" class="form-control" name="price[]" id="price<?= $sn?>" placeholder="0" value="<?= $price?>" onkeyup="checkQty(<?= $sn?>)" required>
                </td>
                <td>
                    <input type="number" class="form-control" name="disc_per[]" id="disc_per<?= $sn?>" placeholder="0" onkeyup="checkQty(<?= $sn?>)" >
                </td>
                <td>
                    <input type="number" class="form-control" name="disc_amt[]" id="disc_amt<?= $sn?>" placeholder="0" onkeyup="checkQty(<?= $sn?>)" >
                </td>
             
                <td>
                    <input type="text" class="form-control" name="total[]" id="total<?= $sn?>" placeholder="0" value="<?= $price*$row1['unit']?>" readonly>
                <!--<input type="hidden" name="medicineid1" id="medicineid1" value=''>-->

                </td>
                <td class=" text-center">
               <a class="pull-right btn btn-danger"  id='delete_row' name='dlt<?= $sn?>' onclick='dlt(<?= $sn?>)' >  <i class="icofont-ui-delete "  data-toggle="tooltip" data-placement="top" title="Delete"></i></a>
                </td>
            </tr>
        <?php
        $sn++;
    }
    ?>
            <tr  id='addr<?= $sn++?>'></tr>
            <script> $('.select2doctor').select2();</script>
            <?php 
             echo "|$sn|$bill_no";
}
if($_REQUEST['page']=='getPatientClaimBill')
{
    $res1=$newajax->getPatientBill($_REQUEST['ip_no']);
    // print_r(mysqli_fetch_assoc($res));
    $sn=1;
    while($row1=mysqli_fetch_assoc($res1))
    {
     
            $price=$row1['price'];
            if(stripos($row1['bill_no'], 'IP') === 0)
            {
                $bill_no=$row1['bill_no'];
            }
        
        ?>
        <tr id='addr<?= $sn?>'>
                
              <td class="col-md-3">
                  
                <select name="service[]" id="service<?= $sn?>" class="form-control select2doctor" required onchange="showResult(this.value,1)">
                    <option>Select Service</option>
                    <?php $res=$newajax->getIItems();
                    while($row=mysqli_fetch_assoc($res))
                    { ?>
                    <option value="<?=$row['id']?>" <?php if($row1['service_id']==$row['id']) echo 'selected';?>><?=$row['name']?></option>
                    <?php } ?>
                </select>
               </td>
              
                <td>
                    <input type="number" class="form-control" name="quantity[]" id="quantity<?= $sn?>" value="<?= $row1['unit']?>" onkeyup="checkQty(<?= $sn?>)" required>
                    
                </td>
                    <input type="hidden" class="form-control" name="service_id[]" id="service_id<?= $sn?>" value="<?= $row1['service_id']?>">
                    <input type="hidden" class="form-control" name="doctor_id[]" id="doctor_id<?= $sn?>" value="<?= $row1['doctor_id']?>">
                <td>
                    <input type="number" class="form-control" name="price[]" id="price<?= $sn?>" placeholder="0" value="<?= $price?>" onkeyup="checkQty(<?= $sn?>)" required>
                </td>
                <td>
                    <input type="number" class="form-control" name="disc_per[]" id="disc_per<?= $sn?>" placeholder="0" onkeyup="checkQty(<?= $sn?>)" >
                </td>
                <td>
                    <input type="number" class="form-control" name="disc_amt[]" id="disc_amt<?= $sn?>" placeholder="0" onkeyup="checkQty(<?= $sn?>)" >
                </td>
             
                <td>
                    <input type="text" class="form-control" name="total[]" id="total<?= $sn?>" placeholder="0" value="<?= $price*$row1['unit']?>" readonly>
                <!--<input type="hidden" name="medicineid1" id="medicineid1" value=''>-->

                </td>
                <td class=" text-center">
               <a class="pull-right btn btn-danger"  id='delete_row' name='dlt<?= $sn?>' onclick='dlt(<?= $sn?>)' >  <i class="icofont-ui-delete "  data-toggle="tooltip" data-placement="top" title="Delete"></i></a>
                </td>
            </tr>
        <?php
        $sn++;
    }
    ?>
            <tr  id='addr<?= $sn++?>'></tr>
            <script> $('.select2doctor').select2();</script>
            <?php 
            echo "|$sn|$bill_no";
}
    if($_REQUEST['page']=='getPItemsSelect2'){
        $data=$newajax->getPItems();
        echo " <option value=''>Select Service</option>";
        while($row=mysqli_fetch_assoc($data))
        {
            echo "<option value='$row[id]'>$row[name]</option>";
        }
    }
?>