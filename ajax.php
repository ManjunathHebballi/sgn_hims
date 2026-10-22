<?php 
require_once(__DIR__ . '/model/session_config.php');
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
if($_REQUEST['page'] == 'otreg1')
{
    
    $flag=0;
    $detail='';
    $res=$newajax->getOPDetails1($_REQUEST['op_no']);
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
// Removing a test ORDERED on a visit (op_lab), as opposed to deleting a lab
// result row (lab_del above). Used by optreatment.php / optreatment1.php.
if($_REQUEST['page']=='op_lab_del')
{
    $newajax->deleteOpLab($_REQUEST['id']);
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
if($_REQUEST['page']=='dept_doctor')
{
        $state=$newajax->getDeptDoctor($_REQUEST['dept_id']);
        echo "<option value=''>Select Doctor</option>";
       while($row=mysqli_fetch_assoc($state)){?>
       
       <option value="<?= $row['id']?>"><?= $row['name'].' '.$row['last_name']?></option>
       <?php } 
}
if($_REQUEST['page']=='doctor_dept')
{
        $state=$newajax->getDepartment($_REQUEST['id']);
        $row=mysqli_fetch_assoc($state);
        echo $row['department'];
      
}
if($_REQUEST['page']=='doctor1')
{
        $state=$newajax->getDeptDoctor($_REQUEST['dept']);
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
if($_REQUEST['page']=='doc_medicine')
{
    
    $data=$newajax->getdoctMedic($_REQUEST['medicine']);
    echo $data['qty'].'|'.$data['dosage'].'|'.$data['timing'].'|'.$data['frequency'].'|'.$data['duration'].'|'.$data['id'];
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
        <option value="<?= $row['id']?>"><?= $row['bed_number']." (".$row['ward'].")"?></option>
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
if($_REQUEST['page']=='doctor_cons')
{
    $data=$newajax->getConsultation($_REQUEST['id']);
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

if($_REQUEST['page'] == 'serach')
{
    $newajax->setopno($_REQUEST['op_no']);
    $flag=0;
    $details='';
    $res=$newajax->getNameOPDetailsNew();
    while($row=mysqli_fetch_assoc($res))
    {
        
        $details.='<tr><td><button class="btn btn-primary btn-sm" onclick="patientDetail1(\''.$row['opd_no'].'\')" data-dismiss="modal">Select</button></td>';
        $details.='<td>'.$row['opd_no'].'</td>';
        $details.='<td>'.$row['name'].'</td>';
        $details.='<td>'.$row['mobile'].'</td>';
        $details.='<td>'.$row['gender'].'</td>';
        $details.='<td>'.$row['age'].'</td>';
        $details.='<td>'.$row['address'].'</td></tr>';
    }
//    echo $detail;
        echo $details;
}


if($_REQUEST['page'] == 'ipregnew')
{
    $newajax->setopno($_REQUEST['op_no']);
    $flag=0;
    $detail='';
    $res=$newajax->getOPDetailsNew();
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
if($_REQUEST['page']=='getPatient')
{
    $data=$newajax->getPatient($_REQUEST['search']);
    // print_r($data);
    while($row=mysqli_fetch_assoc($data))
    {
        $display = htmlspecialchars($row['opd_no'].' - '.$row['uhid'].' - '.$row['name'].' - '.$row['mobile'], ENT_QUOTES);
        $opdNo = htmlspecialchars($row['opd_no'], ENT_QUOTES);
        $name = htmlspecialchars($row['name'], ENT_QUOTES);
        $age = htmlspecialchars($row['age'], ENT_QUOTES);
        $address = htmlspecialchars($row['address'], ENT_QUOTES);

        echo "<option value='{$display}' data-opd='{$opdNo}' data-name='{$name}' data-age='{$age}' data-address='{$address}'></option>";
    }
    
}
if($_REQUEST['page']=='getexpiry')
{
    $data=$newajax->getExpiry($_REQUEST['medic'],$_REQUEST['batch']);
    // print_r($data);
    $date='';
    if($data['exp_date']>'0000-00-00')
    {
        $date=date("Y-m",strtotime($data['exp_date']));
    }
    $price=sprintf('%.2f',$data['box_mrp']/(($data['total_qty']-$data['free_qty'])/$data['box_qty']));
    echo $date.'|'.$price.'|'.$data['tax'].'|'.$data['current_qty'];
}
if($_REQUEST['page']=='getmedic')
{
     $data=$newajax->getMedicineBatch($_REQUEST['id']);
    
     while($row=mysqli_fetch_assoc($data))
    { ?>
        <option value="<?= $row['batch_id']?>"><?= $row['batch_id']?></option>
    <?php }
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
if($_REQUEST['page']=='deletePackage')
{
    $data=$newajax->deletePackage($_REQUEST['id']);
    echo $data;
}
if($_REQUEST['page']=='delete_reference_center')
{
    $data=$newajax->deleteReferenceCenter($_REQUEST['id']);
    echo $data;
}
if($_REQUEST['page']=='delete_outsource_lab')
{
    $data=$newajax->deleteOutsourceLab($_REQUEST['id']);
    echo $data;
}
if($_REQUEST['page']=='delete_test_item')
{
    $data=$newajax->deleteTestParameter($_REQUEST['id']);
    echo $data;
}
if($_REQUEST['page']=='get_test_ranges')
{
    $data=$newajax->getTestRange($_REQUEST['id']);
    if (!empty($data)) {
        
        foreach ($data as $row) {
            // echo $row['reference_range_id'];
            $reference_range = isset($row['reference_range_id']) && $row['reference_range_id']==0 ? 'Any' : htmlspecialchars($row['reference_name']);
            $gender = isset($row['gender']) ? htmlspecialchars($row['gender']) : '';
            $limit = '';
            if (isset($row['lower_limit']) && isset($row['upper_limit'])) {
                $limit = htmlspecialchars($row['lower_limit']) . ' - ' . htmlspecialchars($row['upper_limit']).' '.$row['unit'];
            }
            $critical_limit = '';
            if (isset($row['critical_lower_limit']) && isset($row['critical_upper_limit'])) {
                $critical_limit = htmlspecialchars($row['critical_lower_limit']) . ' - ' . htmlspecialchars($row['critical_upper_limit']);
            }
            $normal_value = isset($row['normal_value']) ? htmlspecialchars($row['normal_value']) : '';
            $special_condition = isset($row['abnormal_condition']) ? htmlspecialchars($row['abnormal_condition']) : '';
            echo "<tr>
                    <td>{$reference_range}</td>
                    <td>{$gender}</td>
                    <td>{$limit}</td>
                    <td>{$critical_limit}</td>
                    <td>{$normal_value}</td>
                    <td>{$special_condition}</td>
                    <td>
                        <button class='btn btn-primary btn-sm edit-test-range' id='edit-test-range'
                        data-id='" . htmlspecialchars($row['id']) . "' 
                        data-reference_range='" . htmlspecialchars($row['reference_range_id']) . "' 
                        data-gender='" . $gender . "' 
                        data-lower_limit='" . htmlspecialchars($row['lower_limit']) . "' 
                        data-upper_limit='" . htmlspecialchars($row['upper_limit']) . "' 
                        data-unit='" . htmlspecialchars($row['unit']) . "' 
                        data-critical_upper_limit='" . htmlspecialchars($row['critical_upper_limit']) . "'
                        data-critical_lower_limit='" . htmlspecialchars($row['critical_lower_limit']) . "'
                        data-normal_value='" . $normal_value . "'
                        data-special_condition='" . $special_condition . "'>Edit</button>
                        <button class='btn btn-danger btn-sm delete-test-result1' data-id='" . htmlspecialchars($row['id']) . "'>Delete</button>
                    </td>
                    </tr>";
        }
    } else {
        echo "<tr><td colspan=7>No data found.</td></tr>";
    }
}
if($_REQUEST['page']=='get_test_results')
{
    $data = $newajax->getTestResult($_REQUEST['id']);
    if (!empty($data)) {
        foreach ($data as $row) {
            $option = isset($row['options']) ? htmlspecialchars($row['options']) : '';
           
            $result_bold = (isset($row['result_bold']) && $row['result_bold']) ? 'Yes' : 'No';
            echo "<tr>
                    <td>{$option}</td>
                    <td>{$result_bold}</td>
                    <td>
                     <button class='btn btn-primary btn-sm edit-result-option'
                     data-id='" . htmlspecialchars($row['id']) . "'
                     data-option=\"{$option}\"
                     data-res_bold='" . $result_bold . "'
                     >Edit</button> 
                        <button class='btn btn-danger btn-sm delete-test-result' data-id='" . htmlspecialchars($row['id']) . "'>Delete</button>
                    </td>
                    </tr>";
        }
    } else {
        echo "<tr><td colspan=3>No data found.</td></tr>";
    }
}
if($_REQUEST['page']=='delete_test_result')
{
    $data=$newajax->deleteTestResult($_REQUEST['id']);
    echo $data;
}
if($_REQUEST['page']=='lab_report')
{
    function sanitize_summernote_html($html) {
        $allowed_tags = [
            'table', 'thead', 'tbody', 'tr', 'td', 'th',
            'b', 'strong', 'i', 'u', 'ul', 'ol', 'li', 'p', 'br', 'span'
        ];
        $allowed_attrs = ['colspan', 'rowspan'];
        // Remove all tags not in allowed list
       $html = preg_replace('/<\?xml[^>]*\?>/i', '', $html);
    $html = preg_replace('/<!--\?xml[^>]*\?-->/i', '', $html);

    $doc = new DOMDocument();
    libxml_use_internal_errors(true);
    $doc->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $xpath = new DOMXPath($doc);

        // Remove disallowed tags
        foreach ($doc->getElementsByTagName('*') as $el) {
            if (!in_array($el->nodeName, $allowed_tags)) {
                $el->parentNode->removeChild($el);
            }
        }

        // Remove disallowed attributes
        foreach ($doc->getElementsByTagName('*') as $el) {
            $attrs_to_remove = [];
            foreach ($el->attributes as $attr) {
                if (!in_array($attr->nodeName, $allowed_attrs)) {
                    $attrs_to_remove[] = $attr->nodeName;
                }
            }
            foreach ($attrs_to_remove as $attr) {
                $el->removeAttribute($attr);
            }
        }

        $clean_html = $doc->saveHTML();
        // Remove the XML encoding declaration if present
        $clean_html = preg_replace('/^<\?xml.*?\?>/', '', $clean_html);
        return $clean_html;
    }

    $data = $newajax->getLabTest($_REQUEST['patient_id'], $_REQUEST['bill_no'], $_REQUEST['testid'], $_REQUEST['gender']);
    foreach($data as $row)
    {
        if($row['lab_report_id']>0)
        {
        $type = $row['type'];
        $checked=$row['print_flag'] ? 'checked' : '';
        if ($type == 'heading') {
            echo "<tr><td colspan='3'><b>".htmlspecialchars($row['testname'])."</b></td>";
             echo "<td>";
             echo "<input type='hidden' name='report[".htmlspecialchars($row['lab_report_id'])."][".htmlspecialchars($row['parameter_id'])."][type]' value='heading'>";
            echo "<input type='hidden' name='report[".htmlspecialchars($row['lab_report_id'])."][".htmlspecialchars($row['parameter_id'])."][position]' value='".htmlspecialchars($row['position'] ?? 0)."'>";
            echo "<input type='checkbox' style='position: static; margin: 0;' name='print_test[".htmlspecialchars($row['lab_report_id'])."][".htmlspecialchars($row['parameter_id'])."]' value='1' $checked>";
            echo "<label style='margin-left: 5px;'>Print</label>";
            echo "</td></tr>";
        } elseif ($type == 'parameter') {
            echo "<tr>";
            echo "<td>".htmlspecialchars($row['testname'])."</td>";
            echo "<td>";
            if (!empty($row['options']) && is_array($row['options'])) {
                $select_id = 'report_'.htmlspecialchars($row['parameter_id']).'_value';
                $select_name = "report[".htmlspecialchars($row['lab_report_id'])."][".htmlspecialchars($row['parameter_id'])."][value]";
                echo "<div class='d-flex align-items-center'>";
                echo "<select class='form-control' id='".$select_id."' name='".$select_name."'>";
                echo "<option value=''></option>";
                foreach ($row['options'] as $option) {
                    $selected = ($row['test_result'] == $option) ? "selected" : "";
                    echo "<option value='".htmlspecialchars($option)."' $selected>".htmlspecialchars($option)."</option>";
                }
                echo "</select>";
                echo "<button type='button' class='btn btn-link btn-sm ml-2 btn-add-more' style='font-size:0.7rem' data-target='".$select_id."'>Add more</button>";
                echo "</div>";
            } else {
                echo "<input type='text' class='form-control' name='report[".htmlspecialchars($row['lab_report_id'])."][".htmlspecialchars($row['parameter_id'])."][value]' value='".htmlspecialchars($row['test_result'])."'>";
            }
            echo "<input type='hidden' name='report[".htmlspecialchars($row['lab_report_id'])."][".htmlspecialchars($row['parameter_id'])."][type]' value='parameter'>";
            echo "<input type='hidden' name='report[".htmlspecialchars($row['lab_report_id'])."][".htmlspecialchars($row['parameter_id'])."][position]' value='".htmlspecialchars($row['position'] ?? 0)."'>";
            echo "</td>";
            echo "<td>";
            if ($row['normal_value']) {
                echo htmlspecialchars($row['normal_value']).' '.htmlspecialchars($row['unit']);
            } elseif ($row['lower_limit'] !== '' && $row['upper_limit'] !== '') {
                echo htmlspecialchars($row['lower_limit']) . ' - ' . htmlspecialchars($row['upper_limit']) . ' ' . htmlspecialchars($row['unit']);
            } else {
                echo '';
            }
            echo "</td>";
            echo "<td>";
            echo "<input type='checkbox' style='position: static; margin: 0;' name='print_test[".htmlspecialchars($row['lab_report_id'])."][".htmlspecialchars($row['parameter_id'])."]' value='1' $checked>";
            echo "<label style='margin-left: 5px;'>Print</label>";
            echo "</td>";
            echo "</tr>";
        } elseif ($type == 'free_text') {
            $clean_result = sanitize_summernote_html($row['test_result']);
            $row['is_readonly'] ? 'readonly' : '';
            echo "<tr>";
            echo "<td colspan='3'>";
            echo "<textarea class='form-control summernote' id='summernote_id_".htmlspecialchars($row['parameter_id'])."' name='report[".htmlspecialchars($row['lab_report_id'])."][".htmlspecialchars($row['parameter_id'])."][value]' rows='3' placeholder='Enter Result' ".$row['is_readonly'].">".htmlspecialchars($clean_result)."</textarea>";
            echo "<input type='hidden' name='report[".htmlspecialchars($row['lab_report_id'])."][".htmlspecialchars($row['parameter_id'])."][type]' value='free_text'>";
            echo "<input type='hidden' name='report[".htmlspecialchars($row['lab_report_id'])."][".htmlspecialchars($row['parameter_id'])."][position]' value='".htmlspecialchars($row['position'] ?? 0)."'>";
            echo "</td>";
            echo "<td>";
            echo "<input type='checkbox' style='position: static; margin: 0;' name='print_test[".htmlspecialchars($row['lab_report_id'])."][".htmlspecialchars($row['parameter_id'])."]' value='1' $checked>";
            echo "<label style='margin-left: 5px;'>Print</label>";
            echo "</td>";
            echo "</tr>";
        } 
        elseif ($type == 'table_format') {
            // Fetch columns
            $columns = [];
            $col_res = $newajax->getTableFormat($row['parameter_id']);
            while($col = mysqli_fetch_assoc($col_res)) {
                $columns[] = $col['column_name'];
            }
            
            echo "<tr>";
            echo "<td colspan='3'>";
            echo "<table class='table table-bordered table-sm'>";
            echo "<tr>";
            echo "<td colspan='".(count($columns)+1)."'>".htmlspecialchars($row['testname'])."</td>";
            echo "</tr>";
            echo "<tr>";
            foreach ($columns as $col_index => $col) {
                
                echo "<th>".htmlspecialchars($col);
                // Hidden input for header row
                echo "<input type='hidden' name='report[".htmlspecialchars($row['lab_report_id'])."][".htmlspecialchars($row['parameter_id'])."][table_data][$col_index][row_type]' id='report_".htmlspecialchars($row['parameter_id'])."table_data_{$col_index}row_type' value='TableHeaderRow' autocomplete='off'>";
                echo "<input type='hidden' name='report[".htmlspecialchars($row['lab_report_id'])."][".htmlspecialchars($row['parameter_id'])."][table_data][$col_index][name]' id='report_".htmlspecialchars($row['parameter_id'])."table_data_{$col_index}name' value='".htmlspecialchars($col)."' autocomplete='off'> ";
                echo "</th>";
            }
            echo "<th>Print</th>";
            echo "</tr>";
            
            $row_index = count($columns); // Start after header rows
            foreach ($row['table_data_rows'] as $table_data_row) {
                echo "<tr>";
                // Hidden inputs for data row
                echo "<input type='hidden' name='report[".htmlspecialchars($row['lab_report_id'])."][".htmlspecialchars($row['parameter_id'])."][table_data][$row_index][row_type]' id='report_".htmlspecialchars($row['parameter_id'])."table_data_{$row_index}row_type' value='TableDataRow' autocomplete='off'>";
                echo "<input type='hidden' name='report[".htmlspecialchars($row['lab_report_id'])."][".htmlspecialchars($row['parameter_id'])."][table_data][$row_index][name]' id='report_".htmlspecialchars($row['parameter_id'])."table_data_{$row_index}name' value='".htmlspecialchars($table_data_row['data_name'])."' autocomplete='off'>";
                // First column: data_name
                echo "<td> ".htmlspecialchars($table_data_row['data_name'])." </td>";
                // For each column (skip first), output a select for result
                foreach ($columns as $col_index2 => $col) {
                    if ($col_index2 == 0) continue; // Skip first column
                    $select_name = "report[".htmlspecialchars($row['lab_report_id'])."][".htmlspecialchars($row['parameter_id'])."][table_data][".($row_index)."][result][".$col_index2."]";
                    $select_id = "report_".htmlspecialchars($row['parameter_id'])."table_data_".($row_index)."_result_$col_index2";
                    // Set selected value for table_format result (similar to antibiotics)
                    $selected_value = '';
                    if (!empty($row['test_result'])) {
                        // test_result is JSON for table_format
                        $decoded_result = [];
                        if (!empty($row['test_result'])) {
                            $decoded_result = json_decode($row['test_result'], true);
                        }
                        if (is_array($decoded_result)) {
                            // Find the matching data_name and column for this row/column
                           
                            foreach ($decoded_result as $tr) {
                                $print_test = isset($tr['print']) && $tr['print'] == 1 ? 'checked' : '';
                                if (
                                    isset($tr['name'], $tr['result']) &&
                                    $tr['name'] === $table_data_row['data_name']
                                ) {
                                    
                                    $selected_value = $tr['result'][$col_index2];
                                    break;
                                }
                             
                            //    echo  $tr['name'].' '.$tr['result'].' '.$tr['name'].' '.$table_data_row['data_name'].' '.$tr['column'].' '.$col.'<br>';
                            }
                        }
                    }
                    echo "<td> ";
                    if (!empty($row['options']) && is_array($row['options'])) {
                    echo "<select name='$select_name' id='$select_id' class='form-control input-small'><option value=''></option>";
                    
                        foreach ($row['options'] as $option) {
                            $selected = ($option == $selected_value) ? "selected" : "";
                            echo '<option value="'.htmlspecialchars($option).'" '.$selected.'>'.htmlspecialchars($option).'</option>';
                        }
                  
                    echo "</select>";
                    }
                    else{
                        echo "<input type='text' name='$select_name' id='$select_id' value='$selected_value' class='form-control'>";
                    
                    }
                    echo "</td>";
                }
                echo "<td>";
                echo "<input type='checkbox' style='position: static; margin: 0;' name='report[".htmlspecialchars($row['lab_report_id'])."][".htmlspecialchars($row['parameter_id'])."][table_data][".($row_index)."][print]' value='1' $print_test>";
                echo "</td>";
                echo "</tr>";
                $row_index++;
            }
            echo "</table>";
            echo "<input type='hidden' name='report[".htmlspecialchars($row['lab_report_id'])."][".htmlspecialchars($row['parameter_id'])."][type]' value='table_format'>";
            echo "<input type='hidden' name='report[".htmlspecialchars($row['lab_report_id'])."][".htmlspecialchars($row['parameter_id'])."][position]' value='".htmlspecialchars($row['position'] ?? 0)."'>";
            echo "</td>";
           
            echo "<td>";
            echo "<input type='checkbox' style='position: static; margin: 0;' name='print_test[".htmlspecialchars($row['lab_report_id'])."][".htmlspecialchars($row['parameter_id'])."]' value='1' $checked>";
            echo "<label style='margin-left: 5px;'>Print</label>";
            echo "</td>";
            echo "</tr>";
        }
        elseif ($type == 'antibiotic') {
            // Fetch antibiotic data
            $antibiotic_data = $row['antibiotic_data'] ?? [];
            
            echo "<tr>";
            echo "<td colspan='3'>";
            echo "<table class='table table-bordered table-sm'>";
            echo "<tr>";
            echo "<td colspan='4'>".htmlspecialchars($row['testname'])."</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<th>Antibiotic</th>";
            echo "<th>INHIBITION ZONE</th>";
            echo "<th>STANDARD ZONE</th>";
            echo "<th>Print</th>";
            echo "</tr>";
            
            $row_index = 0;
            foreach ($antibiotic_data as $antibiotic) {
                // print_r($antibiotic);
                echo "<tr>";
                // Hidden inputs for antibiotic row
                echo "<input type='hidden' name='report[".htmlspecialchars($row['lab_report_id'])."][".htmlspecialchars($row['parameter_id'])."][antibiotic_data][$row_index][row_type]' id='report_".htmlspecialchars($row['parameter_id'])."antibiotic_data_{$row_index}row_type' value='AntibioticRow' autocomplete='off'>";
                echo "<input type='hidden' name='report[".htmlspecialchars($row['lab_report_id'])."][".htmlspecialchars($row['parameter_id'])."][antibiotic_data][$row_index][name]' id='report_".htmlspecialchars($row['parameter_id'])."antibiotic_data_{$row_index}name' value='".htmlspecialchars($antibiotic['heading1'])."' autocomplete='off'>";
                
                // Antibiotic name column
                echo "<td>".htmlspecialchars($antibiotic['heading1'])."</td>";
                
                // INHIBITION ZONE input field
                $inhibition_name = "report[".htmlspecialchars($row['lab_report_id'])."][".htmlspecialchars($row['parameter_id'])."][antibiotic_data][$row_index][inhibition_zone]";
                $inhibition_id = "report_".htmlspecialchars($row['parameter_id'])."_antibiotic_data_{$row_index}_inhibition_zone";
                // Get inhibition_zone value from json if available
                
                // If $row['test_result'] is a JSON string, decode it and use its values for the antibiotic rows
                if (!empty($row['test_result']) && is_string($row['test_result'])) {
                    $decoded_antibiotic_data = json_decode($row['test_result'], true);
                    if (is_array($decoded_antibiotic_data)) {
                        $inhibition_value = htmlspecialchars($decoded_antibiotic_data[$row_index]['inhibition_zone']);
                        $standard_value = htmlspecialchars($decoded_antibiotic_data[$row_index]['standard_zone']);
                        
                    } else {
                        $inhibition_value = isset($antibiotic['inhibition_zone']) ? htmlspecialchars($antibiotic['inhibition_zone']) : '';
                        $standard_value = isset($antibiotic['standard_zone']) ? htmlspecialchars($antibiotic['standard_zone']) : '';
                    }
                    $print_test = isset($decoded_antibiotic_data[$row_index]['print']) ? 'checked' : '';
                } else {
                    $inhibition_value = isset($antibiotic['inhibition_zone']) ? htmlspecialchars($antibiotic['inhibition_zone']) : '';
                    $standard_value = isset($antibiotic['standard_zone']) ? htmlspecialchars($antibiotic['standard_zone']) : '';
                    $print_test = isset($decoded_antibiotic_data[$row_index]['print']) ? 'checked' : '';
                }
                echo "<td><input type='text' class='form-control' name='$inhibition_name' id='$inhibition_id' value='$inhibition_value' autocomplete='off'></td>";
                
                
               
                echo "<td><input type='text' class='form-control' name='$standard_name' id='$standard_id' value='$standard_value' autocomplete='off'></td>";
                echo "<td><input type='checkbox' style='position: static; margin: 0;' name='report[".htmlspecialchars($row['lab_report_id'])."][".htmlspecialchars($row['parameter_id'])."][antibiotic_data][$row_index][print]' value='1' $print_test>$antibiotic[print]</td>";
                echo "</tr>";
                $row_index++;
            }
            echo "</table>";
            echo "<input type='hidden' name='report[".htmlspecialchars($row['lab_report_id'])."][".htmlspecialchars($row['parameter_id'])."][type]' value='antibiotic'>";
            echo "<input type='hidden' name='report[".htmlspecialchars($row['lab_report_id'])."][".htmlspecialchars($row['parameter_id'])."][position]' value='".htmlspecialchars($row['position'] ?? 0)."'>";
            echo "</td>";
           
            echo "<td>";
            echo "<input type='checkbox' style='position: static; margin: 0;' name='print_test[".htmlspecialchars($row['lab_report_id'])."][".htmlspecialchars($row['parameter_id'])."]' value='1' $checked>";
            echo "<label style='margin-left: 5px;'>Print</label> ";
            echo "</td>";
            echo "</tr>";
        }
       
    }
     
    }
    echo "<input type='hidden' name='testid' value='$_REQUEST[testid]'>";
}
if($_REQUEST['page']=='delete_test_result1')
{
    $data=$newajax->deleteTestResult1($_REQUEST['id']);
    echo $data;
}
if($_REQUEST['page']=='delete_table_data_row')
{
    $data=$newajax->deleteTableDataRow($_REQUEST['id']);
    echo $data;
}
if($_REQUEST['page']=='get_test_item_data')
{
    $id = $_REQUEST['id'];
    $type = $_REQUEST['type'];
    
  
    $data = [];
    
   
       
            $result = $newajax->getTestParameterById($id);
            if($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $data = [
                    'name' => $row['name'] ?? '',
                    'unit' => $row['unit'] ?? '',
                    'method' => $row['method'] ?? '',
                    'gender' => $row['gender'] ?? 'both',
                    'position' => $row['position'] ?? 1,
                    'defaults' => $row['is_print'] ?? 0,
                    'readonly' => $row['is_readonly'] ?? 0
                ];
            }
           else{
            $data = ['error' => 'Unknown type'];
           }
           
    
    
    echo json_encode($data);
}
if($_REQUEST['page']=='get_e_signature_data')
{
    // echo $_REQUEST['id'];
    $result=$newajax->getESignatureById($_REQUEST['id']);
      $data = mysqli_fetch_assoc($result);
    echo json_encode($data);
}
if($_REQUEST['page']=='lab_report_view')
{
    $data = $newajax->getLabTest($_REQUEST['patient_id'], $_REQUEST['bill_no'], $_REQUEST['testid'], $_REQUEST['gender']);
    
    echo "<table class='table table-bordered table-hover' width='100%'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>Lab Test</th>";
    echo "<th>Result</th>";
    echo "<th>Normal Value</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    
    foreach($data as $row)
    {
        $type = $row['type'];
        $print_flag = $row['print_flag'] ?? false;
        
        // Only show items that have print flag enabled
        if ($print_flag) {
            if ($type == 'heading') {
                echo "<tr><td colspan='3'><b>".htmlspecialchars($row['testname'])."</b></td></tr>";
            } elseif ($type == 'parameter') {
                echo "<tr>";
                echo "<td>".htmlspecialchars($row['testname'])."</td>";
                echo "<td>";
                if (!empty($row['test_result'])) {
                    echo htmlspecialchars($row['test_result']);
                } else {
                    echo "<em>Not entered</em>";
                }
                echo "</td>";
                echo "<td>";
                if ($row['normal_value']) {
                    echo htmlspecialchars($row['normal_value']).' '.htmlspecialchars($row['unit']);
                } elseif ($row['lower_limit'] !== '' && $row['upper_limit'] !== '') {
                    echo htmlspecialchars($row['lower_limit']) . ' - ' . htmlspecialchars($row['upper_limit']) . ' ' . htmlspecialchars($row['unit']);
                } else {
                    echo '';
                }
                echo "</td>";
                echo "</tr>";
            } elseif ($type == 'free_text') {
                echo "<tr>";
                echo "<td colspan='3'>";
                if (!empty($row['test_result'])) {
                    echo "<div class='border p-3 bg-light'>".($row['test_result'])."</div>";
                } else {
                    echo "<em>Not entered</em>";
                }
                echo "</td>";
                echo "</tr>";
            } elseif ($type == 'table_format') {
                echo "<tr>";
                echo "<td colspan='3'>";
                if (!empty($row['test_result'])) {
                    $table_data = json_decode($row['test_result'], true);
                    if (is_array($table_data)) {
                        echo "<table class='table table-bordered table-sm'>";
                        // Show only rows that have print enabled
                        foreach ($table_data as $table_row) {
                            $row_has_print = false;
                            // Check if any cell in this row has print enabled
                            foreach ($table_row as $cell_key => $cell) {
                                if (isset($cell['print']) && $cell['print'] == 1) {
                                    $row_has_print = true;
                                    break;
                                }
                            }
                            
                            if ($row_has_print) {
                                echo "<tr>";
                                foreach ($table_row as $cell) {
                                    if (is_array($cell)) {
                                        echo "<td>".htmlspecialchars($cell['result'] ?? $cell['name'] ?? '')."</td>";
                                    } else {
                                        echo "<td>".htmlspecialchars($cell)."</td>";
                                    }
                                }
                                echo "</tr>";
                            }
                        }
                        echo "</table>";
                    } else {
                        echo htmlspecialchars($row['test_result']);
                    }
                } else {
                    echo "<em>Not entered</em>";
                }
                echo "</td>";
                echo "</tr>";
            } elseif ($type == 'antibiotic') {
                echo "<tr>";
                echo "<td colspan='3'>";
                if (!empty($row['test_result'])) {
                    $antibiotic_data = json_decode($row['test_result'], true);
                    if (is_array($antibiotic_data)) {
                        echo "<table class='table table-bordered table-sm'>";
                        echo "<thead><tr><th>Antibiotic</th><th>Inhibition Zone</th><th>Standard Zone</th></tr></thead>";
                        echo "<tbody>";
                        foreach ($antibiotic_data as $antibiotic) {
                            // Only show antibiotics that have print enabled
                            if (isset($antibiotic['print']) && $antibiotic['print'] == 1) {
                                echo "<tr>";
                                echo "<td>".htmlspecialchars($antibiotic['heading1'] ?? '')."</td>";
                                echo "<td>".htmlspecialchars($antibiotic['inhibition_zone'] ?? '')."</td>";
                                echo "<td>".htmlspecialchars($antibiotic['standard_zone'] ?? '')."</td>";
                                echo "</tr>";
                            }
                        }
                        echo "</tbody></table>";
                    } else {
                        echo htmlspecialchars($row['test_result']);
                    }
                } else {
                    echo "<em>Not entered</em>";
                }
                echo "</td>";
                echo "</tr>";
            }
        }
    }
    
    echo "</tbody>";
    echo "</table>";
}
if($_REQUEST['page']=='counter_set')
{
    $_SESSION['counter']=$_REQUEST['counter'];
}
?>