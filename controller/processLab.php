<?php
require_once(__DIR__ . '/../model/session_config.php');

date_default_timezone_set("Asia/Kolkata");
// print_r($_REQUEST);exit;
include('../model/labClass.php');
$lab= new lab;

if($_REQUEST['submit']=='add_package')
{
    // print_r($_REQUEST);exit;
    $package_name = $_REQUEST['packege_name'];
    $package_price = $_REQUEST['packege_price'];
    $package_description = $_REQUEST['package_description'];
    $testid = $_REQUEST['test_ids'];
    
    $result = $lab->addPackage($package_name, $package_price, $package_description, $testid);
    
    if($result) {
        echo "<script>alert('Package added successfully!');</script>";
        echo "<script>window.location='../packageList.php';</script>";
    } else {
        echo "<script>alert('Failed to add package. Please try again.');</script>";
        echo "<script>window.location='../createPackage.php';</script>";
    }
}
if($_REQUEST['submit']=='create_reference_center')
{
    
    $lab->setname($_REQUEST['name']);
    $lab->setaddress($_REQUEST['address']);
    $lab->setcontact_name($_REQUEST['contact_name']);
    $lab->setcontact_number($_REQUEST['contact_number']);
    $lab->setemail($_REQUEST['email']);
    $lab->setbill_type($_REQUEST['bill_type']);

    
// print_r($_REQUEST);exit;
    $result = $lab->createReferenceCenter();

    if($result) {
        echo "<script>alert('Reference center created successfully!');</script>";
    } else {
        echo "<script>alert('Failed to create reference center. Please try again.');</script>";
        
    }
    echo "<script>window.location='../referenceCenter.php';</script>";
}
if($_REQUEST['submit']=='edit_reference_center')
{
    // print_r($_REQUEST);exit;
    $lab->setname($_REQUEST['name']);
    $lab->setaddress($_REQUEST['address']);
    $lab->setcontact_name($_REQUEST['contact_name']);
    $lab->setcontact_number($_REQUEST['contact_number']);
    $lab->setemail($_REQUEST['email']);
    $lab->setbill_type($_REQUEST['bill_type']);
    
    $result = $lab->editReferenceCenter($_REQUEST['id']);

    if($result) {
        echo "<script>alert('Reference center updated successfully!');</script>";
    } else {
        echo "<script>alert('Failed to update reference center. Please try again.');</script>";
    }
    echo "<script>window.location='../referenceCenter.php';</script>";
}
if($_REQUEST['submit']=='create_outsource_lab')
{
    $lab->setname($_REQUEST['outsource_lab_name']);
    $lab->setaddress($_REQUEST['outsource_lab_address']);
    $lab->setcontact_name($_REQUEST['outsource_lab_contact_person']);
    $lab->setcontact_number($_REQUEST['outsource_lab_contact_no']);
    $lab->setcity($_REQUEST['outsource_lab_city']);
    $lab->setnabl($_REQUEST['nabl']);
    

    
    $result = $lab->createOutsourceLab();

    if($result) {
        echo "<script>alert('Outsource lab created successfully!');</script>";
    } else {
        echo "<script>alert('Failed to create outsource lab. Please try again.');</script>";
        
    }
    echo "<script>window.location='../outSourceLab.php';</script>";
}
if($_REQUEST['submit']=='edit_outsource_lab')
{
    // print_r($_REQUEST);exit;
    $lab->setname($_REQUEST['outsource_lab_name']);
    $lab->setaddress($_REQUEST['outsource_lab_address']);
    $lab->setcontact_name($_REQUEST['outsource_lab_contact_person']);
    $lab->setcontact_number($_REQUEST['outsource_lab_contact_no']);
    $lab->setcity($_REQUEST['outsource_lab_city']);
    $lab->setnabl($_REQUEST['nabl']);
    
    $result = $lab->editOutsourceLab($_REQUEST['id']);

    if($result) {
        echo "<script>alert('Outsource lab updated successfully!');</script>";
    } else {
        echo "<script>alert('Failed to update outsource lab. Please try again.');</script>";
    }
    echo "<script>window.location='../outSourceLab.php';</script>";
}
if($_REQUEST['submit']=='save_outsource_lab')
{
    // print_r($_REQUEST);exit;
    $lab->setid($_REQUEST['outsource_lab_id']);
    $lab->setsample_id($_REQUEST['sample_id']);
    $lab->setlab_price($_REQUEST['lab_price']);
    $lab->updateSampleCollection();

}

if($_REQUEST['submit']=='deleteTest')
{
    $lab->deleteTest($_REQUEST['id']);
    echo "<script>alert('Test deleted successfully!');</script>";
    echo "<script>window.location='../addTest.php?id={$_REQUEST['testid']}';</script>";
}
if($_REQUEST['submit']=='testHeading')
{
    // print_r($_REQUEST);exit;
    $lab->settest_id($_REQUEST['test_id']);
    $lab->setname($_REQUEST['name']);
    $lab->setgender($_REQUEST['gender']);
    $lab->setposition($_REQUEST['position']);
    $lab->setDefault($_REQUEST['default_load']);

     $result = $lab->testHeading();
    if($result) {
        echo "<script>alert('Test heading added successfully!');</script>";
    } else {
        echo "<script>alert('Failed to add test heading. Please try again.');</script>";
    }
    echo "<script>window.location='../addTest1.php?id={$_REQUEST['test_id']}';</script>";
}
if($_REQUEST['submit']=='testParameter')
{
    $lab->settest_id($_REQUEST['test_id']);
    $lab->setname($_REQUEST['name']);
    $lab->setunit($_REQUEST['unit']);
    $lab->setmethod($_REQUEST['method']);
    $lab->setgender($_REQUEST['gender']);
    $lab->setposition($_REQUEST['position']);
    $lab->setDefault($_REQUEST['default_load']);

    $result = $lab->testParameter();
    if($result) {
        echo "<script>alert('Test parameter added successfully!');</script>";
    } else {
        echo "<script>alert('Failed to add test parameter. Please try again.');</script>";
    }
    echo "<script>window.location='../addTest1.php?id={$_REQUEST['test_id']}&name={$_REQUEST['test_parameter_name']}';</script>";
}
if($_REQUEST['submit']=='testFreeText')
{
    // echo "<pre>";
    // print_r($_REQUEST);
    // echo "</pre>";
    // exit;
    $lab->settest_id($_REQUEST['test_id']);
    $lab->setbody($_REQUEST['body']);
    $lab->setgender($_REQUEST['gender']);
    $lab->setposition($_REQUEST['position']);
    $lab->setDefault($_REQUEST['default_load']);
    $lab->setReadonly($_REQUEST['free_text_readonly']);

    $result = $lab->testFreeText();
    if($result) {
        echo "<script>alert('Test free text added successfully!');</script>";
    } else {
        echo "<script>alert('Failed to add test free text. Please try again.');</script>";
    }
    echo "<script>window.location='../addTest1.php?id={$_REQUEST['test_id']}&name={$_REQUEST['test_free_text_name']}';</script>";
}

if($_REQUEST['submit']=='editTestFreeText')
{
    echo "<pre>";
    print_r($_REQUEST);
    echo "</pre>";
    exit;
    $lab->settest_id($_REQUEST['test_id']);
    $lab->setbody($_REQUEST['body']);
    $lab->setgender($_REQUEST['gender']);
    $lab->setposition($_REQUEST['position']);
    $lab->setDefault($_REQUEST['default_load']);
    $lab->setReadonly($_REQUEST['free_text_readonly']);

    $result = $lab->updateTestFreeText($_REQUEST['id']);
    if($result) {
        echo "<script>alert('Test free text updated successfully!');</script>";
    } else {
        echo "<script>alert('Failed to update test free text. Please try again.');</script>";
    }
    echo "<script>window.location='../addTest1.php?id={$_REQUEST['test_id']}&name={$_REQUEST['test_free_text_name']}';</script>";
}

if($_REQUEST['submit']=='antibiotic')
{
     $lab->settest_id($_REQUEST['test_id']);
    $lab->setname($_REQUEST['name']);
    $lab->setgender($_REQUEST['gender']);
    $lab->setposition($_REQUEST['position']);
    $lab->setDefault($_REQUEST['default_load']);
    $result = $lab->addAntibiotic();
    if($result) {
        echo "<script>alert('Antibiotic added successfully!');</script>";
    } else {
        echo "<script>alert('Failed to add antibiotic. Please try again.');</script>";
    }
    echo "<script>window.location='../addTest1.php?id={$_REQUEST['test_id']}&name={$_REQUEST['test_free_text_name']}';</script>";
}

// Edit Test Parameter
if (isset($_POST['submit']) && ($_POST['submit'] == 'editTestParameter' || $_POST['submit'] == 'editTestHeading' || $_POST['submit'] == 'editTestAntibiotic' || $_POST['submit'] == 'editTestTableFormat')) {

    $id = $_POST['id'];
    $name = $_POST['name'];
    $unit = $_POST['unit'] ?? '';
    $method = $_POST['method'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $position = $_POST['position'];
    $default = $_POST['default_load'] ?? 0;
    $readonly = $_POST['readonly'] ?? 0;
    
    // Update the parameter with additional fields
    $result = $lab->updateTestItem($id, $name, $unit, $method, $gender, $position,$default,$readonly);
    if ($result) {
        echo "<script>window.location='../addTest1.php?id=" . urlencode($_POST['test_id']) . "&name=" . urlencode($_POST['test_parameter_name']) . "&msg=updated';</script>";
        exit;
    } else {
        echo "<script>window.location='../addTest1.php?id=" . urlencode($_POST['test_id']) . "&name=" . urlencode($_POST['test_parameter_name']) . "&msg=error';</script>";
        exit;
    }
}

if (isset($_POST['submit']) && $_POST['submit'] == 'update_positions') {
    $order = json_decode($_POST['order'], true);
    foreach ($order as $item) {
        $lab->updatePosition($item['id'], $item['position']);
    }
    echo json_encode(['success' => true]);
    exit;
}

if($_REQUEST['submit']=='test_range')
{
    $lab->settest_id($_REQUEST['test_id']);
    $lab->setnormal_value($_REQUEST['normal_value']);
    $lab->setgender($_REQUEST['gender']);
    $lab->setabnormal_condition($_REQUEST['abnormal_condition']);
    $lab->setcritical_lower_limit($_REQUEST['critical_lower_limit']);
    $lab->setcritical_upper_limit($_REQUEST['critical_upper_limit']);
    $lab->setreference_range_id($_REQUEST['reference_range_id']);
    $lab->setparameter_id($_REQUEST['parameter_id']);
    $lab->setlower_limit($_REQUEST['lower_limit']);
    $lab->setupper_limit($_REQUEST['upper_limit']);
    $result = $lab->addTestRange();
    if($result) {
        echo "<script>alert('Test range added successfully!');</script>";
    } else {
        echo "<script>alert('Failed to add test range. Please try again.');</script>";
    }
    echo "<script>window.location='../addTest1.php?id={$_REQUEST['test_id']}&name={$_REQUEST['test_free_text_name']}';</script>";
}

if(isset($_POST['submit']) && $_POST['submit']==='update_test_range')
{
    $range_id = intval($_POST['id']);
  
    $lab->setnormal_value($_REQUEST['normal_value']);
    $lab->setgender($_REQUEST['gender']);
    $lab->setabnormal_condition($_REQUEST['abnormal_condition']);
    $lab->setcritical_lower_limit($_REQUEST['critical_lower_limit']);
    $lab->setcritical_upper_limit($_REQUEST['critical_upper_limit']);
    $lab->setreference_range_id($_REQUEST['reference_range_id']);
        $lab->setlower_limit($_REQUEST['lower_limit']);
        $lab->setupper_limit($_REQUEST['upper_limit']);
  
    $res = $lab->updateTestRange($range_id);
    $test_id = urlencode($_POST['test_id'] ?? '');
    $test_name = urlencode($_POST['test_name'] ?? '');
    if($res){
        echo "<script>window.location='../addTest1.php?id={$test_id}&name={$test_name}&msg=range_updated';</script>";
    } else {
        echo "<script>window.location='../addTest1.php?id={$test_id}&name={$test_name}&msg=range_update_failed';</script>";
    }
    exit;
}


if($_REQUEST['submit']=='add_test_result')
{
    $lab->setOption($_REQUEST['option']);
    $lab->setposition($_REQUEST['position']);
    $lab->settest_id($_REQUEST['test_parameter_id']);
    if (isset($_REQUEST['result_bold']))
    {
        $lab->setresult_bold($_REQUEST['result_bold']);
    }
    $result=$lab->addTestResult();
    if($result) {
        echo "<script>alert('Test Result added successfully!');</script>";
    } else {
        echo "<script>alert('Failed to add test result. Please try again.');</script>";
    }
    echo "<script>window.location='../addTest1.php?id={$_REQUEST['test_id']}&name={$_REQUEST['test_free_text_name']}';</script>";

}
if($_REQUEST['submit']=='add_test_group_row')
{
    // echo "<pre>";
    // print_r($_REQUEST);
    $lab->settest_id($_REQUEST['parameter_id']);
    $lab->setheading1($_REQUEST['heading1']);
    $lab->setheading2($_REQUEST['heading2']);
    $lab->setheading3($_REQUEST['heading3']);
    $result=$lab->addTestAntibiotic();
    if($result) {
        echo "<script>alert('Test antibiotic added successfully!');</script>";
    } else {
        echo "<script>alert('Failed to add antibiotic result. Please try again.');</script>";
    }
    echo "<script>window.location='../addTest1.php?id={$_REQUEST['test_id']}&name={$_REQUEST['test_name']}';</script>";
}
if (isset($_POST['submit']) && $_POST['submit'] === 'add_antibiotic_list') {

// print_r($_REQUEST);exit;
    $parameter_id = $_POST['parameter_id'];
    $antibiotic_ids = $_POST['antibiotic_ids'] ?? [];

    // Fetch all antibiotics (id => name) for lookup
   
    foreach ($antibiotic_ids as $antibiotic_name) {
    //   echo $antibiotic_name;
            // Insert into lab_test_antibiotic (parameter_id, heading1)
            $lab->insertTestAntibiotic($parameter_id, $antibiotic_name);
        
    }
    // Redirect or return success
    echo "<script>window.location='../addTest1.php?id=".$_POST['test_id']."&name=" . urlencode($_POST['test_name']) . "';</script>";
    exit;
}
if($_REQUEST['submit']=='tableFormat')
{
  
    $lab->settest_id($_REQUEST['test_id']);
    $lab->setname($_REQUEST['name']);
    $lab->setcolumns($_REQUEST['columns']);
    $lab->setposition($_REQUEST['position']);
    $lab->setgender($_REQUEST['gender']);
    $result=$lab->addTableFormat();

    if($result) {
        echo "<script>alert('Table format added successfully!');</script>";
    } else {
        echo "<script>alert('Failed to add table format. Please try again.');</script>";
    }
    echo "<script>window.location='../addTest1.php?id={$_REQUEST['test_id']}&name={$_REQUEST['test_free_text_name']}';</script>";
}
if($_REQUEST['submit']=='add_table_data_row')
{
    // print_r($_REQUEST);exit;
    $lab->setparameter_id($_REQUEST['parameter_id']);
    $lab->setdata_name($_REQUEST['data_name']);
    $lab->setposition($_REQUEST['position']);
    $result=$lab->addTableDataRow();
    if($result) {
        echo "<script>alert('Table data row added successfully!');</script>";
    } else {
        echo "<script>alert('Failed to add table data row. Please try again.');</script>";
    }
    echo "<script>window.location='../addTest1.php?id={$_REQUEST['test_id']}&name={$_REQUEST['test_free_text_name']}';</script>";
}
if (isset($_POST['submit']) && $_POST['submit'] === 'save_result') {
//     echo "<pre>";
// print_r($_REQUEST);exit;
    // Loop through each test (outer key is test_id, value is array of parameters)
    foreach ($_POST['report'] as $test_id => $parameters) {
        // $parameters is an array: [parameter_id => data]
        foreach ($parameters as $parameter_id => $data) {
            // print_r($data);
            $position = $data['position'] ?? 0;
            $type = $data['type'] ?? '';

            // Determine print flag for this parameter
            $print_flag = 0;
            // For parameter and free_text, check print_test
            if ( isset($_POST['print_test'][$test_id][$parameter_id])) {
                $print_flag = 1;
            }
            // For table_format, check print_table
            if ($type == 'table_format' && isset($_POST['print_table'][$test_id][$parameter_id])) {
                $print_flag = 1;
            }
            // For antibiotic, check print_test (since print_antibiotic is not present in the sample)
            if ($type == 'antibiotic' && isset($_POST['print_test'][$test_id][$parameter_id])) {
                $print_flag = 1;
            }

            // Handle each type
             if ($type == 'table_format') {
                $table_data = $data['table_data'] ?? [];
                $value = json_encode($table_data);
                $lab->saveLabResult( $type, $value, $test_id, $print_flag, $_REQUEST['test_id'], $_REQUEST['bill_no'],$_REQUEST['testid']);
            } elseif ($type == 'antibiotic') {
                $antibiotic_data = $data['antibiotic_data'] ?? [];
                // Optionally, handle print flags for each antibiotic row if needed
                // For now, just save the whole antibiotic_data array as JSON
                $value = json_encode($antibiotic_data);
                // echo $value;
                $lab->saveLabResult( $type, $value, $test_id, $print_flag, $_REQUEST['test_id'], $_REQUEST['bill_no'],$_REQUEST['testid']);
            }
            else{
                $value = $data['value'] ?? '';
                $lab->saveLabResult( $type, $value, $test_id, $print_flag, $_REQUEST['test_id'], $_REQUEST['bill_no'],$_REQUEST['testid']);
            }
        }
        
        // Auto-insert user's signature into lab_report_dispatch after saving results
        $user_id = $_SESSION['loginid'];
        $lab->autoInsertUserSignature($_REQUEST['testid'], $_REQUEST['test_id'], $_REQUEST['bill_no'], $user_id);
    }
// exit;
    // Redirect or show success message
     echo "<script>alert('Result saved successfully!');</script>";
    echo "<script>window.location='../lab_report.php?id={$_REQUEST['test_id']}&bill_no={$_REQUEST['bill_no']}';</script>";
    exit;
}
if(isset($_POST['submit']) && $_POST['submit'] === 'eSignature')
{
    // Example: Save the eSignature for a lab report
    $lab->setuser_id($_REQUEST['user_id']);
    $lab->setuser_name($_REQUEST['user_name']);
    $lab->setdesignation($_REQUEST['designation']);
    $lab->setqualification($_REQUEST['qualification']);
    $lab->setposition($_REQUEST['position']);
     if(isset($_FILES['signature']))
    {
        $file=rand(1000,100000)."-".$_FILES["signature"]["name"];
    $spath = "uploads/$file";  
       $target_path = "../uploads/";  
    $target_path = $target_path.basename( $file);   
          
    if(move_uploaded_file($_FILES['signature']['tmp_name'], $target_path)) {  
        // echo "File uploaded successfully!";
        // call  function where to store in db and insert $target_path  
    } 
           
    
        if(strlen($_FILES["signature"]['name'])>0)
        {   
            $lab->setsignature_image($spath);
        }
    }

        // 3. Save the signature (you may need to implement this in your $lab object)
        $result = $lab->saveESignature();

        if ($result) {
            echo "<script>alert('eSignature saved successfully!');</script>";
        } else {
            echo "<script>alert('Failed to save eSignature. Please try again.');</script>";
        }
    

    // 4. Redirect back to the report or relevant page
    echo "<script>window.location='../eSignatures.php';</script>";
    exit;
}

if(isset($_POST['submit']) && $_POST['submit'] === 'editESignature')
{

    $lab->setuser_name($_REQUEST['user_name']);
    $lab->setdesignation($_REQUEST['designation']);
    $lab->setqualification($_REQUEST['qualification']);
    $lab->setposition($_REQUEST['position']);
    
    if(isset($_FILES['signature']) && $_FILES['signature']['size'] > 0)
    {
        $file=rand(1000,100000)."-".$_FILES["signature"]["name"];
        $spath = "uploads/$file";  
        $target_path = "../uploads/";  
        $target_path = $target_path.basename( $file);   
              
        if(move_uploaded_file($_FILES['signature']['tmp_name'], $target_path)) {  
            $lab->setsignature_image($spath);
        } 
    }

    $result = $lab->updateESignature($_REQUEST['id']);

    if ($result) {
        echo "<script>alert('eSignature updated successfully!');</script>";
    } else {
        echo "<script>alert('Failed to update eSignature. Please try again.');</script>";
    }

    echo "<script>window.location='../eSignatures.php';</script>";
    exit;
}

if(isset($_POST['submit']) && $_POST['submit'] === 'deleteESignature')
{
    $result = $lab->deleteESignature($_REQUEST['id']);

    if ($result) {
        echo "<script>alert('eSignature deleted successfully!');</script>";
    } else {
        echo "<script>alert('Failed to delete eSignature. Please try again.');</script>";
    }

    echo "<script>window.location='../eSignatures.php';</script>";
    exit;
}
if (isset($_POST['submit']) && $_POST['submit'] === 'save_report_dispatch') {
    header('Content-Type: application/json');

    // Expecting fields from lab_report modal
    $test_id = $_POST['test_id'] ?? '';
    $patient_id = $_POST['patient_id'] ?? '';
    $bill_no = $_POST['bill_no'] ?? '';
    $dispatch = $_POST['report_dispatch'] ?? [];
    $sample_received_date = $dispatch['sample_received_date'] ?? '';
    $reporting_time = $dispatch['reporting_time'] ?? '';
    $print_signature_on_every_page = isset($dispatch['print_signature_on_every_page']) ? 1 : 0;
    $left_signature = $dispatch['left_signature'] ?? '';
    $right_signature = $dispatch['right_signature'] ?? '';
    $left_e_signature = isset($dispatch['left_e_signature']) ? 1 : 0;
    $right_e_signature = isset($dispatch['right_e_signature']) ? 1 : 0;
   
    if (empty($test_id) || empty($patient_id) || empty($bill_no)) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit;
    }

    // Set values via setters and save
    $lab->settest_id($test_id);
    $lab->setpatient_id($patient_id);
    $lab->setbill_no($bill_no);
    $lab->setsample_received_date($sample_received_date);
    $lab->setreporting_time($reporting_time);
    $lab->setprint_signature_on_every_page($print_signature_on_every_page);
    $lab->setleft_signature($left_signature);
    $lab->setright_signature($right_signature);
    $lab->setleft_e_signature($left_e_signature);
    $lab->setright_e_signature($right_e_signature);
    // echo 'here--------------------------------'.$_REQUEST['dispatch_id'];
    if($_POST['dispatch_id']>0)
    {
        $ok = $lab->updateReportDispatch($_POST['dispatch_id']);
    }else{
         $ok = $lab->saveReportDispatch();
    }
   

    echo json_encode(['success' => (bool)$ok]);
    exit;
}

if($_REQUEST['submit']=='update_result_option')
{
    $data=$lab->updateResultOption($_REQUEST['resid'], $_REQUEST['option'], $_REQUEST['highlight_result']);
    echo "<script>window.location='../addTest1.php?id={$_REQUEST['id']}&name={$_REQUEST['name']}';</script>";
}
if($_REQUEST['submit']=='lab_department')
{
    if(isset($_REQUEST['editid']))
    {
         $lab->updateLabDepartment($_REQUEST['dept_name'],$_REQUEST['status'],$_REQUEST['editid']);
    }
    else{
        $lab->insertLabDepartment($_REQUEST['dept_name']);
    }
    
    echo "<script>window.location='../lab_department.php';</script>";
}