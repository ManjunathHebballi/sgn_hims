<?php
require_once(__DIR__ . '/../../model/session_config.php');
include('../model/dbconnect.php');
// error_reporting(E_ALL);
date_default_timezone_set("Asia/Kolkata");
class addBilling
{
    private $billid,
        $billno,
        $doctor,
        $doc,
        $service,
        $final_bill,
        $primary_doc_amt,
        $primary_doc_percent,
        $primary_hospital_percent,
        $secondary_doc_amt,
        $second_doc_percent,
        $second_hospital_percent,
        $status,
        $patient_id,
        $payment_mode,
        $patient_type,
        $quantity,
        $service_id,
        $service_type,
        $price,
        $disc_per,
        $disc_amt,
        $total,
        $discount,
        $discount_reason,
        $gross,
        $srv_disc,
        $tot_amt,
        $grand,
        $paid_amt,
        $outstand,
        $ipd,
        $advance_amount,
        $adv_id,
        $adj,
        $note,
        $advance,
        $admdate,
        $fname,
        $age,
        $sex,
        $religion,
        $mobile,
        $pname,
        $marital,
        $department,
        $diagnosis,
        $address,
        $ptype,
        $pan,
        $aadhar,
        $policy,
        $mlc,
        $city,
        $consultancy,
        $payment_date,
        $desc,
        $ref_center, $patinetid, $refno, $admidate, $disdate, $complaint, $investigations, $course, $exam, $advice, $deptid, $dis_condtion, $files, $ward, $date;
    function setbillid($billid)
    {
        $this->billid = $billid;
    }
    function setbillno($billno)
    {
        $this->billno = $billno;
    }
    function setdoctor($doctor)
    {
        $this->doctor = $doctor;
    }
    function setdoc($doc)
    {
        $this->doc = $doc;
    }
    function setservice($service)
    {
        $this->service = $service;
    }
    function setfinal_bill($final_bill)
    {
        $this->final_bill = $final_bill;
    }
    function setprimary_doc_amt($primary_doc_amt)
    {
        $this->primary_doc_amt = $primary_doc_amt;
    }
    function setprimary_doc_percent($primary_doc_percent)
    {
        $this->primary_doc_percent = $primary_doc_percent;
    }
    function setprimary_hospital_percent($primary_hospital_percent)
    {
        $this->primary_hospital_percent = $primary_hospital_percent;
    }
    function setsecondary_doc_amt($secondary_doc_amt)
    {
        $this->secondary_doc_amt = $secondary_doc_amt;
    }
    function setsecond_doc_percent($second_doc_percent)
    {
        $this->second_doc_percent = $second_doc_percent;
    }
    function setsecond_hospital_percent($second_hospital_percent)
    {
        $this->second_hospital_percent = $second_hospital_percent;
    }
    function setstatus($status)
    {
        $this->status = $status;
    }
    function setpatient_id($patient_id)
    {
        $this->patient_id = $patient_id;
    }
    function setpayment_mode($payment_mode)
    {
        $this->payment_mode = $payment_mode;
    }
    function setpatient_type($patient_type)
    {
        $this->patient_type = $patient_type;
    }
    function setquantity($quantity)
    {
        $this->quantity = $quantity;
    }
    function setservice_id($service_id)
    {
        $this->service_id = $service_id;
    }
    function setservice_type($service_type)
    {
        $this->service_type = $service_type;
    }

    function setprice($price)
    {
        $this->price = $price;
    }
    function setdisc_per($disc_per)
    {
        $this->disc_per = $disc_per;
    }
    function setdisc_amt($disc_amt)
    {
        $this->disc_amt = $disc_amt;
    }
    function settotal($total)
    {
        $this->total = $total;
    }
    function setdiscount($discount)
    {
        $this->discount = $discount;
    }
    function setdiscount_reason($discount_reason)
    {
        $this->discount_reason = $discount_reason;
    }
    function setgross($gross)
    {
        $this->gross = $gross;
    }
    function setsrv_disc($srv_disc)
    {
        $this->srv_disc = $srv_disc;
    }
    function settot_amt($tot_amt)
    {
        $this->tot_amt = $tot_amt;
    }
    function setgrand($grand)
    {
        $this->grand = $grand;
    }
    function setpaid_amt($paid_amt)
    {
        $this->paid_amt = $paid_amt;
    }
    function setoutstand($outstand)
    {
        $this->outstand = $outstand;
    }

    function setipd($ipd)
    {
        $this->ipd = $ipd;
    }

    function setadvance_amount($advance_amount)
    {
        $this->advance_amount = $advance_amount;
    }

    function setadv_id($adv_id)
    {
        $this->adv_id = $adv_id;
    }
    function setadj($adj)
    {
        $this->adj = $adj;
    }
    function setnote($note)
    {
        $this->note = $note;
    }
    function setadvance($advance)
    {
        $this->advance = $advance;
    }
    function setadmdate($admdate)
    {
        $this->admdate = $admdate;
    }
    function setfname($fname)
    {
        $this->fname = $fname;
    }
    function setage($age)
    {
        $this->age = $age;
    }
    function setsex($sex)
    {
        $this->sex = $sex;
    }
    function setreligion($religion)
    {
        $this->religion = $religion;
    }
    function setmobile($mobile)
    {
        $this->mobile = $mobile;
    }
    function setpname($pname)
    {
        $this->pname = $pname;
    }
    function setmarital($marital)
    {
        $this->marital = $marital;
    }
    function setdepartment($department)
    {
        $this->department = $department;
    }
    function setdiagnosis($diagnosis)
    {
        $this->diagnosis = $diagnosis;
    }
    function setaddress($address)
    {
        $this->address = $address;
    }

    function setptype($ptype)
    {
        $this->ptype = $ptype;
    }
    function setpan($pan)
    {
        $this->pan = $pan;
    }
    function setaadhar($aadhar)
    {
        $this->aadhar = $aadhar;
    }
    function setpolicy($policy)
    {
        $this->policy = $policy;
    }
    function setmlc($mlc)
    {
        $this->mlc = $mlc;
    }
    function setcity($city)
    {
        $this->city = $city;
    }
    function setconsultancy($consultancy)
    {
        $this->consultancy = $consultancy;
    }
    function setpayment_date($date)
    {
        $this->payment_date = $date;
    }
    function setref_center($ref_center)
    {
        $this->ref_center = $ref_center;
    }

    function setpatinetid($patinetid)
    {
        $this->patinetid = $patinetid;
    }
    function setrefno($refno)
    {
        $this->refno = $refno;
    }

    function setadmidate($admidate)
    {
        $this->admidate = $admidate;
    }
    function setdisdate($disdate)
    {
        $this->disdate = $disdate;
    }

    function setcomplaint($complaint)
    {
        $this->complaint = $complaint;
    }
    function setinvestigations($investigations)
    {
        $this->investigations = $investigations;
    }
    function setcourse($course)
    {
        $this->course = $course;
    }
    function setexam($exam)
    {
        $this->exam = $exam;
    }
    function setadvice($advice)
    {
        $this->advice = $advice;
    }

    function setdeptid($deptid)
    {
        $this->deptid = $deptid;
    }
    function setdis_condtion($dis_condtion)
    {
        $this->dis_condtion = $dis_condtion;
    }
    function setfile($files)
    {
        $this->files = $files;
    }
    function setward($ward)
    {
        $this->ward = $ward;
    }
    function setdate($date)
    {
        $this->date = $date;
    }
    function setdesc($desc)
    {
        $this->desc = $desc;
    }
    function getCity()
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "SELECT * FROM `cities` WHERE state_id=12";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    function getDoctor()
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $day = date("l");
        if ($_SESSION['role'] == 'Admin') {
            $admin = '';
        } else {
            $admin = " and st.`day`='$day'";
        }

        $sql = "SELECT u.* FROM `doctor` u WHERE role='Doctor' and status='active' group by u.id";
        $res = mysqli_query($con, $sql);
        return $res;
    }

    function selectDept()
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "SELECT * FROM `department` WHERE `status`='active'";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    function selectDepartment()
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "SELECT * FROM `department`";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    function selectDoctor()
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "SELECT * FROM `doctor` WHERE `status`='active'";
        //   echo $sql;
        $res = mysqli_query($con, $sql);
        return $res;
    }

    function getPItems()
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "select * from `service_list` where status='active' and service_group_id in (select id FROM `service_group` WHERE bill_group like '%PATHOLOGY TEST%')";
        // echo $sql;exit;
        $result = mysqli_query($con, $sql);
        mysqli_close($con);
        return $result;
    }
    function getRItems()
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "select * from `service_list` where status='active' and service_group_id in (select id FROM `service_group` WHERE bill_group like '%INVESTIGATION CHARGES%')";
        // echo $sql;exit;
        $result = mysqli_query($con, $sql);
        mysqli_close($con);
        return $result;
    }
    function getBItems()
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "select * from `service_list` where status='active' and service_group_id in (select id FROM `service_group` WHERE bill_group like '%BLOOD CHARGES%')";
        // echo $sql;exit;
        $result = mysqli_query($con, $sql);
        mysqli_close($con);
        return $result;
    }
    function getDItems()
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "select * from `service_list` where status='active' and service_group_id in (select id FROM `service_group` WHERE bill_group like '%OTHER CHARGES%')";
        // echo $sql;exit;
        $result = mysqli_query($con, $sql);
        mysqli_close($con);
        return $result;
    }
    function getOItems()
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "select * from `service_list` where status='active' and service_group_id in (select id FROM `service_group` WHERE bill_group not in ('BLOOD CHARGES','PATHOLOGY TEST','INVESTIGATION CHARGES','ROOM CHARGES'))";
        // echo $sql;exit;
        $result = mysqli_query($con, $sql);
        mysqli_close($con);
        return $result;
    }
    function getIItems()
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "select * from `service_list` where status='active' and service_group_id in (select id FROM `service_group` WHERE bill_group in ('ROOM CHARGES','OTHER CHARGES','ADMISSION CHARGES','OPRATION CHARGES','PHYSIOTHERAPY CHARGES','PATHOLOGY TEST'))";
        // echo $sql;exit;
        $result = mysqli_query($con, $sql);
        mysqli_close($con);
        return $result;
    }
    function getESItems()
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "select * from `service_list` where status='active' ";
        // echo $sql;exit;
        $result = mysqli_query($con, $sql);
        mysqli_close($con);
        return $result;
    }
    function getBillListOP($from, $to, $s)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        if (strlen($s) > 0) {
            $data = "c.`opd_no` like '%$s%'";
        } else {
            $data = "date(ib.`date`) between '$from' and '$to'";
        }
        $sql = "select ib.payment_mode,ib.status,ib.invoice_no as ibinv,d.dept_name,GROUP_CONCAT(ib.`item` SEPARATOR '<br/><br/>') as items, GROUP_CONCAT(ib.`price` SEPARATOR '<br/><br/>') as price,GROUP_CONCAT(ib.`total` SEPARATOR '<br/><br/>') as total,sum(ib.total) as gt,c.* from `ip_bill` ib, central_opd c,department d where d.id=c.dept_id and ib.ipd_no=c.id and $data  group by c.id,ibinv";
        // echo $sql;
        // exit;
        $result = mysqli_query($con, $sql);
        mysqli_close($con);
        return $result;
    }

    function insertDocRevenue()
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "INSERT INTO `doctor_revenue` (`doctor_id`, `service_id`, `amt_share2doctor`, `perc_share2doctor`, `perc_sahre2hospital`, `amt_share2doctor_second`, `perc_share2doctor_second`, `perc_sahre2hospital_second`, `status`, `created_by`) VALUES ('$this->doctor', '$this->service', '$this->primary_doc_amt', '$this->primary_doc_percent', '$this->primary_hospital_percent', '$this->secondary_doc_amt', '$this->second_doc_percent', '$this->second_hospital_percent', '$this->status', '$_SESSION[name]')";
        //  echo $sql;exit;
        $res = mysqli_query($con, $sql);
        $date = date("Y-m-d H:i:s");
        $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
        $sql1->close();
        $con->close();
        return $res;
    }

    function insertBill()
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();



        $service = $this->service;
        $patient_type = $this->patient_type;

        $discount = $this->discount;
        $gross = $this->gross;
        $srv_disc = $this->srv_disc;
        $tot_amt = $this->tot_amt;
        $grand = $this->grand;
        $paid_amt = $this->paid_amt ? $this->paid_amt : 0;
        $outstand = $this->outstand;
        $service_type = $this->service_type;
        $ref_center = $this->ref_center;
        $financialYear = $this->getFinancialYear();
        //   echo 'here';exit;
        if ($service_type == 'OP') {
            $query = "SELECT * FROM data_table WHERE id=5";
            $bill = mysqli_fetch_assoc(mysqli_query($con, $query));
            $bill_no = $bill['table_id'];

            $bill_no = $bill_no + 1;
            $fi_bill_no = 'OP/' . $financialYear . '/' . sprintf("%04d", $bill_no);
        } elseif ($service_type == 'IP') {
            $query = "SELECT * FROM data_table WHERE id=6";
            $bill = mysqli_fetch_assoc(mysqli_query($con, $query));
            $bill_no = $bill['table_id'];

            $bill_no = $bill_no + 1;
            $fi_bill_no = 'IP/' . $financialYear . '/' . sprintf("%04d", $bill_no);
        } elseif ($service_type == 'Pathology') {
            $query = "SELECT * FROM data_table WHERE id=7";
            $bill = mysqli_fetch_assoc(mysqli_query($con, $query));
            $bill_no = $bill['table_id'];

            $bill_no = $bill_no + 1;
            $fi_bill_no = 'LAB/' . $financialYear . '/' . sprintf("%04d", $bill_no);
        } elseif ($service_type == 'Radiology') {
            $query = "SELECT * FROM data_table WHERE id=8";
            $bill = mysqli_fetch_assoc(mysqli_query($con, $query));
            $bill_no = $bill['table_id'];

            $bill_no = $bill_no + 1;
            $fi_bill_no = 'RD/' . $financialYear . '/' . sprintf("%04d", $bill_no);
        } elseif ($service_type == 'Blood Bank') {
            $query = "SELECT * FROM data_table WHERE id=9";
            $bill = mysqli_fetch_assoc(mysqli_query($con, $query));
            $bill_no = $bill['table_id'];

            $bill_no = $bill_no + 1;
            $fi_bill_no = 'BL/' . $financialYear . '/' . sprintf("%04d", $bill_no);
        } elseif ($service_type == 'Dialysis') {
            $query = "SELECT * FROM data_table WHERE id=10";
            $bill = mysqli_fetch_assoc(mysqli_query($con, $query));
            $bill_no = $bill['table_id'];

            $bill_no = $bill_no + 1;
            $fi_bill_no = 'DL/' . $financialYear . '/' . sprintf("%04d", $bill_no);
        }
        $sql = "INSERT INTO `billing` ( `patient_id`, `amount`, `service_level_discount`, `discount_amount`, `paid_amount`, `outstand_amount`, `bill_no`, `doctor`, `dept_id`,  `service_type`, `patient_type`, `status`, `collected_by`, `collected_at`,`referal_center_id`,`discount_reason`) VALUES 
                           ( '$this->patient_id', '$tot_amt', '$srv_disc', '$discount', '$paid_amt', '$outstand', '$fi_bill_no', '$this->doc', '$this->department', '$service_type', '$patient_type', 'PAID', '$_SESSION[name]', NOW(),'$ref_center','$this->discount_reason')";
        //   echo $sql;exit;
        $res = mysqli_query($con, $sql);
        $id = mysqli_insert_id($con);
        $date = date("Y-m-d H:i:s");
        $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
        $sql1->close();


        if ($service_type == 'OP') {
            $query1 = "UPDATE data_table set table_id='$bill_no' WHERE id=5";
        } elseif ($service_type == 'IP') {
            $query1 = "UPDATE data_table set table_id='$bill_no' WHERE id=6";
        } elseif ($service_type == 'Pathology') {
            $query1 = "UPDATE data_table set table_id='$bill_no' WHERE id=7";
        } elseif ($service_type == 'Radiology') {
            $query1 = "UPDATE data_table set table_id='$bill_no' WHERE id=8";
        } elseif ($service_type == 'Blood Bank') {
            $query1 = "UPDATE data_table set table_id='$bill_no' WHERE id=9";
        } elseif ($service_type == 'Dialysis') {
            $query1 = "UPDATE data_table set table_id='$bill_no' WHERE id=10";
        }

        mysqli_query($con, $query1);
        $con->close();
        return $fi_bill_no . '|' . $id;
    }
    function insertBillingDetails($id, $fi_bill_no)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $quantity = $this->quantity;
        $service_id = $this->service_id;
        $price = $this->price;
        $disc_amt = $this->disc_amt;
        $doctor = $this->doctor;
        $patient_type = $this->patient_type;
        $service_type = $this->service_type;
        $desc = $this->desc;
        $sql = "INSERT INTO `billing_details` (`bill_id`, `bill_no`, `service_id`, `doctor_id`, `unit`, `price`, `discount`, `status`,`description`, `created_at`) VALUES 
                    ( '$id', '$fi_bill_no', '$service_id', '$doctor', '$quantity', '$price', '$disc_amt', 'PAID','$desc', NOW())";
        //  echo $sql;exit;
        $res = mysqli_query($con, $sql);


        $date = date("Y-m-d H:i:s");
        if ($service_type == 'Pathology') {
            $date = date('Ymd'); // 20250625
            $prefix = 'LAB-' . $date . '-';
            $sql = "SELECT visit_no,bill_no FROM lab_report WHERE visit_no LIKE '$prefix%' ORDER BY visit_no DESC LIMIT 1";
            $result = mysqli_query($con, $sql);

            if ($row = mysqli_fetch_assoc($result)) {
                if ($row['bill_no'] != $fi_bill_no) {
                    $last_no = (int)substr($row['visit_no'], -4); // Extract last 4 digits
                    $new_no = str_pad($last_no + 1, 4, '0', STR_PAD_LEFT);
                    $visit_no = $prefix . $new_no;
                } else {
                    $visit_no = $row['visit_no'];
                }
            } else {
                $new_no = '0001';
                $visit_no = $prefix . $new_no;
            }


            $sql = "SELECT group_concat(ld.test_group_id) as service_id FROM lab_package_tests ld where ld.package_id='$service_id'";
            $res1 = mysqli_query($con, $sql);
            $row1 = mysqli_fetch_assoc($res1);
            if ($row1 && !empty($row1['service_id'])) {
                $service_id = $row1['service_id'];
            }
            $test_ids = explode(',', $service_id);
            foreach ($test_ids as $tid) {
                $tid = trim($tid);
                if ($tid === '') continue;

                $param_sql = "SELECT id,is_print FROM lab_test_parameter WHERE test_id='$tid' AND `status`='active'";
                $param_res = mysqli_query($con, $param_sql);

                if (mysqli_num_rows($param_res) > 0) {
                    while ($param_row = mysqli_fetch_assoc($param_res)) {
                        $param_id = $param_row['id'];
                        $query = "INSERT INTO lab_report (parameter_id,date,patient_id,patient_type,bill_no,visit_no,testid,print_flag) 
                                          VALUES ('$param_id', now(), '$this->patient_id', '$patient_type', '$fi_bill_no', '$visit_no', '$tid','$param_row[is_print]')";
                        mysqli_query($con, $query);
                    }
                } else {
                    // No parameter for this test_id, insert with parameter_id=0
                    $query = "INSERT INTO lab_report (parameter_id,date,patient_id,patient_type,bill_no,visit_no,testid,print_flag) 
                                      VALUES (0, now(), '$this->patient_id', '$patient_type', '$fi_bill_no', '$visit_no', '$tid','$param_row[is_print]')";
                    mysqli_query($con, $query);
                }
                // echo $query;exitl
            }
        }
        $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
        $sql1->close();
        $con->close();
        return $res;
    }
    function updateBill()
    {
        $billid = $this->billid;
        $billno = $this->billno;

        $patient_type = $this->patient_type;

        $discount = $this->discount;
        $gross = $this->gross;
        $srv_disc = $this->srv_disc;
        $tot_amt = $this->tot_amt;
        $grand = $this->grand;
        $paid_amt = 0;
        $outstand = $this->outstand;
        $service_type = $this->service_type;


        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();

        if ($gross == 0) {
            $sql = "UPDATE `billing` set status='CANCELLED',`amount`='$tot_amt', `service_level_discount`='$srv_disc', `discount_amount`='$discount',  `outstand_amount`='$outstand', `doctor`='$this->doc', `service_type`='$service_type', `patient_type`='$patient_type',`dept_id`='$this->department', `updated_by`='$_SESSION[name]', `updated_at`=NOW() where id='$billid' and bill_no='$billno'";
        } else {
            $sql = "UPDATE `billing` set `amount`='$tot_amt', `service_level_discount`='$srv_disc', `discount_amount`='$discount',  `outstand_amount`='$outstand', `doctor`='$this->doc', `service_type`='$service_type', `patient_type`='$patient_type',`dept_id`='$this->department', `updated_by`='$_SESSION[name]', `updated_at`=NOW() where id='$billid' and bill_no='$billno'";
            // echo $sql;exit;
        }
        $res = mysqli_query($con, $sql);
        $date = date("Y-m-d H:i:s");
        $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
        $sql1->close();
        $con->close();



        return 1;
    }
    function updateBilldetails($id)
    {
        $doctor = $this->doctor;
        $service = $this->service;
        $quantity = $this->quantity;
        $service_id = $this->service_id;
        $price = $this->price;
        $disc_per = $this->disc_per;
        $disc_amt = $this->disc_amt;
        $total = $this->total;
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql1 = "UPDATE `billing_details` set  `service_id`='$service_id', `doctor_id`='$doctor', `unit`='$quantity', `price`='$price', `discount`='$disc_amt',  `updated_at`=NOW(), `updated_by`='$_SESSION[name]' where id='$id'";
        // echo $sql1;exit;
        mysqli_query($con, $sql1);
        $date = date("Y-m-d H:i:s");
        $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
        $sql1->close();
        $con->close();
        return 1;
    }
    function getPatient($id, $bill_no)
    {

        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        if (substr($id, 0, 2) == 'IP') {
            $sql = "select c.*,d.dept_name,b.bill_no,bd.created_at as bill_date,b.payment_mode from `central_ipd` c,department d,billing_details bd,billing b where   b.id=bd.bill_id and b.patient_id=c.ipd_no  and d.id=c.dept_id and c.ipd_no='$id'  and b.bill_no='$bill_no'";
        } else {
            $sql = "select c.*,d.dept_name,b.bill_no,bd.created_at as bill_date,b.payment_mode, b.doctor as doc from `central_opd` c,department d,billing_details bd,billing b,doctor doc where doc.id=c.doctor and  b.id=bd.bill_id and b.patient_id=c.id  and d.id=c.dept_id and c.id='$id'  and b.bill_no='$bill_no'";
        }
        // echo $sql;exit;
        $result = mysqli_query($con, $sql);
        mysqli_close($con);
        return $result;
    }
    function getIPPatient($id, $bill_no)
    {

        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "select c.*,d.dept_name,b.bill_no,bd.created_at as bill_date,b.payment_mode, b.doctor as doc,di.discharge_date,bed.bed_number,b.returned_amount from bed bed,`central_ipd` c,department d,billing_details bd,billing b left join discharge di on di.patient_id=b.patient_id where bed.id=c.bed_id and b.id=bd.bill_id and b.patient_id=c.ipd_no and d.id=c.dept_id and c.ipd_no='$id'  and b.bill_no='$bill_no'";
        // echo $sql;
        $result = mysqli_query($con, $sql);
        mysqli_close($con);
        return $result;
    }
    function getBill($id, $bill_no)
    {

        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "select b.paid_amount,b.returned_amount,b.discount_amount,b.collected_by,bd.*,sl.name as service,aa.adjusted_amount,concat(d.name, ' ',d.last_name) as doctor_name,b.service_type from service_list sl, billing b join billing_details bd on 1=1 left join adjust_advance aa on aa.patient_id=b.patient_id LEFT JOIN doctor d ON d.id=bd.doctor_id where sl.id=bd.service_id and b.id=bd.bill_id and  b.patient_id='$id' and bd.status='PAID' and b.bill_no='$bill_no'";
        // echo $sql;exit;
        $result = mysqli_query($con, $sql);
        mysqli_close($con);
        return $result;
    }
    function getBillListRadiology($from, $to, $s, $type)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $data = $data1 = '';
        if (strlen($s) > 0) {
            $data .= " (c.`opd_no` like '%$s%' || b.bill_no like '%$s%') ";
            $data1 .= " (c.`ipd_no` like '%$s%' || b.bill_no like '%$s%') ";
        } else {
            $data .= " date(b.`date`) between '$from' and '$to'";
            $data1 .= " date(b.`date`) between '$from' and '$to'";
        }

        // if($_SESSION['role']!='Admin')
        // {
        //     $data.=" and b.collected_by like '%$_SESSION[name]%'"; 
        // }
        $sql = "(select b.returned_amount,sum(bd.price * bd.unit) as gt, sum(bd.discount) as discount, b.outstand_amount as outstand,b.discount_amount as b_discount,b.status,b.bill_no,d.dept_name,GROUP_CONCAT(s.name SEPARATOR '<br/><br/>') as service, GROUP_CONCAT(bd.`price` SEPARATOR '<br/><br/>') as price,GROUP_CONCAT(bd.`unit` SEPARATOR '<br/><br/>') as unit,b.patient_id,b.patient_type,c.opd_no,c.name,c.age,c.gender,b.date,b.collected_by,b.id as bill_id,b.paid_amount from `billing` b, billing_details bd, central_opd c,department d,service_list s where d.id=c.dept_id and b.patient_id=c.id and b.id=bd.bill_id and bd.service_id=s.id and b.service_type='$type' and $data group by c.id,b.id)
            UNION ALL (select b.returned_amount,sum(bd.price * bd.unit) as gt, sum(bd.discount) as discount, b.outstand_amount as outstand,b.discount_amount as b_discount,b.status,b.bill_no,d.dept_name,GROUP_CONCAT(s.name SEPARATOR '<br/><br/>') as service, GROUP_CONCAT(bd.`price` SEPARATOR '<br/><br/>') as price,GROUP_CONCAT(bd.`unit` SEPARATOR '<br/><br/>') as unit,b.patient_id,b.patient_type,c.ipd_no as opd_no,c.name,c.age,c.gender,b.date,b.collected_by,b.id as bill_id,b.paid_amount from `billing` b, billing_details bd, central_ipd c,department d,service_list s where d.id=c.dept_id and b.patient_id=c.ipd_no and b.id=bd.bill_id and bd.service_id=s.id and b.service_type='$type' and $data1 group by c.id,b.id)";
        // echo $sql;
        // exit;
        $result = mysqli_query($con, $sql);
        mysqli_close($con);
        return $result;
    }
    function getBillListIP($from, $to, $s, $type)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $data = '';
        if (strlen($s) > 0) {
            $data .= " (c.`ipd_no` like '%$s%' || b.bill_no like '%$s%')";
        } else {
            $data .= " date(b.`date`) between '$from' and '$to'";
        }
        // if($_SESSION['role']!='Admin')
        // {
        //     $data.=" b.collected_by='$_SESSION[name]'"; 
        // }
        $sql = "select b.date,b.returned_amount,sum(bd.price * bd.unit) as gt, sum(bd.discount) as discount, b.outstand_amount as outstand,b.discount_amount as b_discount,b.status,b.bill_no,d.dept_name,GROUP_CONCAT(s.name SEPARATOR '<br/><br/>') as service, GROUP_CONCAT(bd.`price` SEPARATOR '<br/><br/>') as price,GROUP_CONCAT(bd.`unit` SEPARATOR '<br/><br/>') as unit,c.*,b.id,b.collected_by,ifnull((adjusted_amount),0) as advance,b.paid_amount from  billing_details bd, central_ipd c,department d,service_list s,`billing` b left join adjust_advance a on a.patient_id=b.patient_id and b.bill_no=a.invoice_no where d.id=c.dept_id and b.patient_id=c.ipd_no and b.id=bd.bill_id  and bd.service_id=s.id and b.service_type='$type' and $data  group by c.id,b.bill_no";
        // echo $sql;
        // exit;
        $result = mysqli_query($con, $sql);
        mysqli_close($con);
        return $result;
    }

    function returnAmount($bill_no, $amount, $type)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $date = date("Y-m-d H:i:s");
        $sql = "update billing set returned_amount=returned_amount+$amount,outstand_amount=0,returned_at=NOW(),returned_by='$_SESSION[name]' where bill_no='$bill_no'";
        $res = mysqli_query($con, $sql);

        $query1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $query1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $query1->execute();
        $query1->close();

        if ($type != 'IP') {
            $type = 'OP';
        }
        $sql1 = "INSERT INTO bill_return (`bill_no`, `returned_amount`, `returned_by`, `returned_date`, `patient_type`) values('$bill_no','$amount','$_SESSION[name]',NOW(),'$type')";
        mysqli_query($con, $sql1);

        $query = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $query->bind_param("sss", $sql1, $_SESSION['name'], $date);
        $query->execute();
        $query->close();
        $con->close();

        return $res;
    }
    function returnAdvAmount($bill_no, $amount)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $date = date("Y-m-d H:i:s");
        $sql = "update ip_advance_amount set returned_amount=returned_amount+$amount,returned_at=NOW(),returned_by='$_SESSION[name]' where id='$bill_no'";
        //   echo $sql;exit;
        $res = mysqli_query($con, $sql);

        $query1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $query1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $query1->execute();
        $query1->close();

        $sql1 = "INSERT INTO bill_return (`bill_no`, `returned_amount`, `returned_by`, `returned_date`, `patient_type`) values('$bill_no','$amount','$_SESSION[name]',NOW(),'IP')";
        mysqli_query($con, $sql1);

        $query = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $query->bind_param("sss", $sql1, $_SESSION['name'], $date);
        $query->execute();
        $query->close();
        $con->close();

        return $res;
    }
    function insertAdvance()
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "INSERT INTO `ip_advance_amount` (`ip_no`, `amount`, `date`, `status`, `created_by`,`payment_type`,`payment_note`) VALUES ('$this->ipd','$this->advance_amount', NOW(), 'PAID', '$_SESSION[name]','$this->payment_mode','$this->note');";
        $res = mysqli_query($con, $sql);
        $adv_id = mysqli_insert_id($con);
        $date = date("Y-m-d H:i:s");
        $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
        $sql1->close();
        $con->close();



        return $adv_id;
    }
    function updateAdvance($id)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "UPDATE `ip_advance_amount` set `amount`='$this->advance_amount',`payment_type`='$this->payment_mode',`payment_note`='$this->note' where id=$id";
        //   echo $sql;exit;

        $res = mysqli_query($con, $sql);
        $date = date("Y-m-d H:i:s");
        $adv_id = $id;
        $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
        $sql1->close();
        $con->close();



        return $adv_id;
    }

    function getAdvanceListIP($from, $to, $s)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        if (strlen($s) > 0) {
            $data = "ia.ip_no like '%$s%'";
        } else {
            $data = "date(ia.`date`) between '$from' and '$to'";
        }
        $sql = "SELECT c.*,d.`dept_name`,ia.date as adv_date,ia.id as adv_id,ia.amount as adv_amt,ia.status,returned_amount,ia.payment_type,ia.payment_note FROM `central_ipd` c,`department` d,`ip_advance_amount` ia  WHERE  ia.ip_no=c.ipd_no and c.`dept_id`=d.`id` and $data  order by date(ia.date),ia.id";
        // echo $sql;
        $res = mysqli_query($con, $sql);
        return $res;
    }
    function getIPD2($id)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "SELECT c.*,d.`dept_name`,ia.date as adv_date FROM `central_ipd` c,`department` d,`ip_advance_amount` ia  WHERE  ia.ip_no=c.ipd_no and c.`dept_id`=d.`id` and c.`ipd_no`='$id'";
        // echo $sql;
        // exit;
        $res = mysqli_query($con, $sql);
        mysqli_close($con);
        return $res;
    }
    function getAdvanceAmt($id, $adv_id)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "SELECT * FROM `ip_advance_amount` where ip_no='$id' and id=$adv_id";
        // echo $sql;
        //   exit;
        $res = mysqli_query($con, $sql);
        return $res;
    }

    function insertAdjustAmount($ipd, $inv)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "INSERT INTO `adjust_advance` (`patient_id`, `invoice_no`, `advance_id`, `advance_amount`, `adjusted_amount`, `note`, `date`, `adjusted_by`) VALUES ('$ipd', '$inv', '$this->adv_id', '$this->advance', '$this->adj', '$this->note', NOW(), '$_SESSION[name]')";
        // echo $sql;exit;
        $result = mysqli_query($con, $sql);
        $date = date("Y-m-d H:i:s");
        $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
        $sql1->close();
        $con->close();
        return $result;
    }
    function getAdavAdj($bill)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "SELECT ifnull(sum(adjusted_amount),0) as adjust from adjust_advance where invoice_no='$bill'";
        // echo $sql;
        $result = mysqli_query($con, $sql);
        mysqli_close($con);
        return $result;
    }
    function getPatientBill($id, $bill)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "SELECT c.opd_no,c.name,c.gender,c.age,c.mobile,b.* FROM `central_opd` c,billing b where c.id=$id and c.id=b.patient_id and b.bill_no='$bill'";
        //   echo $sql;
        //   exit;
        $res = mysqli_query($con, $sql);
        return $res;
    }
    function getIPPatientBill($id, $bill)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "SELECT c.opd_no,c.ipd_no,c.name,c.gender,c.age,c.mobile,b.* FROM `central_ipd` c,billing b where c.ipd_no='$id' and c.ipd_no=b.patient_id and b.bill_no='$bill'";
        //   echo $sql;
        //   exit;
        $res = mysqli_query($con, $sql);
        return $res;
    }
    function getPatientBillDetails($id, $bill)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();

        $sql = "SELECT * FROM billing_details  where bill_id=$id and bill_no='$bill' and status='PAID'";

        $res = mysqli_query($con, $sql);
        return $res;
    }


    function getOPBillCancel($from, $to, $s)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $data = '';
        if (strlen($s) > 0) {
            $data .= " c.`opd_no` like '%$s%'";
        } else {
            $data .= " date(b.`date`) between '$from' and '$to'";
        }
        if ($_SESSION['role'] != 'Admin') {
            $data .= " b.collected_by='$_SESSION[name]'";
        }
        $sql = "select b.returned_amount,sum(bd.price * bd.unit) as gt, sum(bd.discount) as discount, sum(b.discount_amount) as b_discount,b.status,b.bill_no,d.dept_name,GROUP_CONCAT(s.name SEPARATOR '<br/><br/>') as service, GROUP_CONCAT(bd.`price` SEPARATOR '<br/><br/>') as price,GROUP_CONCAT(bd.`unit` SEPARATOR '<br/><br/>') as unit,c.*,b.date from `billing` b, billing_details bd, central_opd c,department d,service_list s where d.id=c.dept_id and b.patient_id=c.id and b.id=bd.bill_id and bd.service_id=s.id and b.status='CANCELLED' and b.patient_type='OP' and $data  group by c.id,b.bill_no";
        // echo $sql;
        // exit;
        $result = mysqli_query($con, $sql);
        mysqli_close($con);
        return $result;
    }


    function getIPBillCancel($from, $to, $s)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $data = '';
        if (strlen($s) > 0) {
            $data .= " c.`opd_no` like '%$s%'";
        } else {
            $data .= " date(b.`date`) between '$from' and '$to'";
        }
        if ($_SESSION['role'] != 'Admin') {
            $data .= " b.collected_by='$_SESSION[name]'";
        }
        $sql = "select b.returned_amount,sum(bd.price * bd.unit) as gt, sum(bd.discount) as discount, sum(b.discount_amount) as b_discount,b.status,b.bill_no,d.dept_name,GROUP_CONCAT(s.name SEPARATOR '<br/><br/>') as service, GROUP_CONCAT(bd.`price` SEPARATOR '<br/><br/>') as price,GROUP_CONCAT(bd.`unit` SEPARATOR '<br/><br/>') as unit,c.*,b.date,ci.ipd_no from `billing` b, billing_details bd, central_opd c,department d,service_list s,central_ipd ci where ci.opd_no=c.opd_no and d.id=c.dept_id and b.patient_id=ci.ipd_no and b.id=bd.bill_id and bd.service_id=s.id and b.status='CANCELLED' and b.patient_type='IP' and $data  group by c.id,b.bill_no";
        // echo $sql;
        // exit;
        $result = mysqli_query($con, $sql);
        mysqli_close($con);
        return $result;
    }

    function getOPBillReport($from, $to, $s)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $data = '';
        if (strlen($s) > 0) {
            $data .= " c.`opd_no` like '%$s%'";
        } else {
            $data .= " date(b.`date`) between '$from' and '$to'";
        }
        if ($_SESSION['role'] != 'Admin') {
            $data .= " b.collected_by='$_SESSION[name]'";
        }
        $sql = "select b.returned_amount,sum(bd.price * bd.unit) as gt, sum(bd.discount) as discount, sum(b.discount_amount) as b_discount,b.status,b.bill_no,d.dept_name,GROUP_CONCAT(s.name SEPARATOR '<br/><br/>') as service, GROUP_CONCAT(bd.`price` SEPARATOR '<br/><br/>') as price,GROUP_CONCAT(bd.`unit` SEPARATOR '<br/><br/>') as unit,c.*,b.date from `billing` b, billing_details bd, central_opd c,department d,service_list s where d.id=c.dept_id and b.patient_id=c.id and b.id=bd.bill_id and bd.service_id=s.id and b.status='PAID' and b.patient_type='OP' and $data  group by c.id,b.bill_no";
        // echo $sql;
        // exit;
        $result = mysqli_query($con, $sql);
        mysqli_close($con);
        return $result;
    }


    function getIPBillReport($from, $to, $s)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $data = '';
        if (strlen($s) > 0) {
            $data .= " c.`opd_no` like '%$s%'";
        } else {
            $data .= " date(b.`date`) between '$from' and '$to'";
        }
        if ($_SESSION['role'] != 'Admin') {
            $data .= " b.collected_by='$_SESSION[name]'";
        }
        $sql = "select b.returned_amount,sum(bd.price * bd.unit) as gt, sum(bd.discount) as discount, sum(b.discount_amount) as b_discount,b.status,b.bill_no,d.dept_name,GROUP_CONCAT(s.name SEPARATOR '<br/><br/>') as service, GROUP_CONCAT(bd.`price` SEPARATOR '<br/><br/>') as price,GROUP_CONCAT(bd.`unit` SEPARATOR '<br/><br/>') as unit,c.*,b.date,ci.ipd_no from `billing` b, billing_details bd, central_opd c,department d,service_list s,central_ipd ci where ci.opd_no=c.opd_no and d.id=c.dept_id and b.patient_id=ci.ipd_no and b.id=bd.bill_id and bd.service_id=s.id and b.status='PAID' and b.patient_type='IP' and $data  group by c.id,b.bill_no";
        // echo $sql;
        // exit;
        $result = mysqli_query($con, $sql);
        mysqli_close($con);
        return $result;
    }


    function getBillListUserwise($from, $to, $search, $pay, $service)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "SELECT 
    collected_by,
    payment_mode,
    SUM(op_bill) AS op_bill,
    SUM(ip_bill) AS ip_bill,
    SUM(patho_bill) AS patho_bill,
    SUM(radio_bill) AS radio_bill,
    SUM(blood_bill) AS blood_bill,
    SUM(dialysis_bill) AS dialysis_bill,
    SUM(advance) AS advance,
    SUM(op_return) AS op_return,
    SUM(ip_return) AS ip_return
FROM (
    SELECT 
        bp.collected_by,
        bp.payment_mode,
        SUM(CASE WHEN b.service_type = 'OP' THEN bp.amount ELSE 0 END) AS op_bill,
        SUM(CASE WHEN b.service_type = 'IP' THEN bp.amount ELSE 0 END) AS ip_bill,
        SUM(CASE WHEN b.service_type = 'Pathology' THEN bp.amount ELSE 0 END) AS patho_bill,
        SUM(CASE WHEN b.service_type = 'Radiology' THEN bp.amount ELSE 0 END) AS radio_bill,
        SUM(CASE WHEN b.service_type = 'Blood Bank' THEN bp.amount ELSE 0 END) AS blood_bill,
        SUM(CASE WHEN b.service_type = 'Dialysis' THEN bp.amount ELSE 0 END) AS dialysis_bill,
        0 AS advance,
        0 AS op_return,
        0 AS ip_return
    FROM billing b
    INNER JOIN billing_payment bp ON b.id = bp.bill_id
    WHERE DATE(bp.date) BETWEEN '$from' AND '$to' and bp.collected_by like '$search%' and bp.payment_mode like '$pay%' and b.service_type like '$service%'
    GROUP BY bp.collected_by, bp.payment_mode";
        if ($pay == 'Credit' || $pay == "") {
            $sql .= "  UNION ALL

   SELECT 
        b.collected_by,
            'credit' AS payment_mode,       
    SUM(CASE WHEN b.service_type = 'OP' THEN GREATEST(b.amount - IFNULL(bp.total_payment, 0)  - b.discount_amount - b.returned_amount - IFNULL(a.adjusted_amount, 0),0) ELSE 0 END) AS op_bill,
    SUM(CASE WHEN b.service_type = 'IP' THEN GREATEST(b.amount - IFNULL(bp.total_payment, 0)  - b.discount_amount - b.returned_amount - IFNULL(a.adjusted_amount, 0),0) ELSE 0 END) AS ip_bill,
    SUM(CASE WHEN b.service_type = 'Pathology' THEN GREATEST(b.amount - IFNULL(bp.total_payment, 0)  - b.discount_amount - b.returned_amount - IFNULL(a.adjusted_amount, 0),0) ELSE 0 END) AS patho_bill,
    SUM(CASE WHEN b.service_type = 'Radiology' THEN GREATEST(b.amount - IFNULL(bp.total_payment, 0)  - b.discount_amount - b.returned_amount - IFNULL(a.adjusted_amount, 0),0) ELSE 0 END) AS radio_bill,
    SUM(CASE WHEN b.service_type = 'Blood Bank' THEN GREATEST(b.amount - IFNULL(bp.total_payment, 0)  - b.discount_amount - b.returned_amount - IFNULL(a.adjusted_amount, 0),0) ELSE 0 END) AS blood_bill,
    SUM(CASE WHEN b.service_type = 'Dialysis' THEN GREATEST(b.amount - IFNULL(bp.total_payment, 0)  - b.discount_amount - b.returned_amount - IFNULL(a.adjusted_amount, 0),0) ELSE 0 END) AS dialysis_bill,
    0 AS advance,
    0 AS op_return,
    0 AS ip_return
FROM billing b
LEFT JOIN (
    SELECT 
        bill_id, 
        DATE(date) AS date,
        SUM(amount) AS total_payment
    FROM billing_payment
    GROUP BY bill_id
) bp ON bp.bill_id = b.id AND DATE(bp.date) = DATE(b.date)
LEFT JOIN adjust_advance a ON a.invoice_no=b.bill_no
    WHERE DATE(b.date) BETWEEN '$from' AND '$to'
       and b.collected_by like '$search%'
       and b.service_type like '$service%'
       AND (case when b.status='cancelled' then paid_amount>0 ELSE 1=1 end)
    GROUP BY b.collected_by
    HAVING op_bill+ip_bill+patho_bill+radio_bill+blood_bill+dialysis_bill!=0";
        }
        if ($service == 'IP' || $service == '') {
            // Advances taken. status is filtered to PAID for the same reason
            // every other report does it (reportClass::26/42/100, and the
            // ledger at line 2236): ajaxClass::549 sets status='CANCELLED' on a
            // cancelled advance, and without this filter a cancelled advance
            // still counted as money collected.
            $sql .= " UNION ALL

    SELECT 
        created_by AS collected_by,
        payment_type AS payment_mode,
        0, 0, 0, 0, 0, 0,
        SUM(amount) AS advance,
        0, 0
    FROM ip_advance_amount
    WHERE DATE(date) BETWEEN '$from' AND '$to' and created_by like '$search%'
    and payment_type like '$pay%'
    and status = 'PAID'
    GROUP BY created_by, payment_type";

            // Advances returned. returnAdvAmount() writes the refund to
            // ip_advance_amount.returned_amount AND to bill_return — but the
            // bill_return row carries the ip_advance_amount.id in its bill_no
            // column, and the refund branch below inner-joins bill_return to
            // billing on bill_no. An advance id matches no billing.bill_no, so
            // those rows were silently dropped and the advance above was
            // counted in full. Collection was overstated by every advance
            // refund. Read straight from the advance row instead.
            //
            // CAVEAT: returned_amount is a running total and returned_at only
            // holds the LAST refund, so an advance refunded in parts across
            // different days lands entirely on the last date and last user.
            // Correcting that needs a per-refund advance log, not a column.
            if ($pay == 'Cash' || $pay == "") {
                $sql .= " UNION ALL

    SELECT
        returned_by AS collected_by,
        'cash' AS payment_mode,
        0, 0, 0, 0, 0, 0,
        0,
        0 AS op_return,
        SUM(returned_amount) AS ip_return
    FROM ip_advance_amount
    WHERE IFNULL(returned_amount,0) > 0
      and DATE(returned_at) BETWEEN '$from' AND '$to'
      and returned_by like '$search%'
    GROUP BY returned_by";
            }
        }
        if ($pay == 'Cash' || $pay == "") {
            $sql .= " UNION ALL

    SELECT 
        br.returned_by AS collected_by,
        'cash' AS payment_mode,
        0, 0, 0, 0, 0, 0,
        0,
        SUM(CASE WHEN br.patient_type = 'OP' THEN br.returned_amount ELSE 0 END) AS op_return,
        SUM(CASE WHEN br.patient_type = 'IP' THEN br.returned_amount ELSE 0 END) AS ip_return
    FROM bill_return br,
    billing b
    WHERE DATE(br.returned_date) BETWEEN '$from' AND '$to' and br.returned_by like '$search%' and br.bill_no=b.bill_no and b.service_type like '$service%'
    GROUP BY br.returned_by";
        }
        $sql .= ") AS summary
GROUP BY collected_by, payment_mode
ORDER BY collected_by";
        //   echo $sql;
        $result = mysqli_query($con, $sql);
        mysqli_close($con);
        return $result;
    }

    function billing_summary($from, $to)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "SELECT service_type,sum(amount)as amount,sum(discount_amount) as discount FROM `billing` where date(date) BETWEEN '$from' and '$to' and status!='cancelled' group by service_type";
        //SELECT service_type,sum(amount),sum(discount_amount),sum(bill_amount) FROM (SELECT (case when service_group_id=8 THEN 'CT SCAN' ELSE (case when service_group_id=53 THEN 'USG' ELSE (CASE WHEN service_group_id=56 THEN 'X-RAY' ELSE service_type END) END) END) AS service_type,amount,discount_amount,sum(unit*price-discount) as bill_amount FROM `billing` b INNER JOIN billing_details bd ON b.id=bd.bill_id JOIN service_list s ON s.id=bd.service_id where date(b.date) BETWEEN '2025-11-15' and '2025-11-15' and b.status!='cancelled' group by b.id) summ GROUP BY service_type;

        $result = mysqli_query($con, $sql);
        mysqli_close($con);
        return $result;
    }
    function setFinalBill($id)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "update central_ipd set final_bill=1 where ipd_no='$id'";
        $res = mysqli_query($con, $sql);
        $date = date("Y-m-d H:i:s");
        $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
        $sql1->close();
        $con->close();
        return $res;
    }
    function getUser()
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "SELECT concat(name,' ',last_name) as user_name FROM `user` WHERE role in ('Receptionist','Lab','billing','Lab Admin','IT') order by name";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    function insertOP($page)
    {
        $y = date("y");
        $newlogin = new userlogin;
        $date = $this->admdate;
        $con = $newlogin->dbconnect();
        $query = "SELECT * FROM data_table WHERE id=1 || id=4 order by id";
        $iddata = mysqli_query($con, $query);

        while ($central_opd = mysqli_fetch_assoc($iddata)) {
            if ($central_opd['id'] == 1) {
                $opd_no = $central_opd['table_id'] + 1;
            } elseif ($central_opd['id'] == 4) {
                $uhid = $central_opd['table_id'] + 1;
            }
        }
        $y = date("Ymd", strtotime($date));
        if ($y != substr($opd_no, 0, 8)) {
            $data = "SELECT SUBSTR(MAX(opd_no),3,12) as opd,SUBSTR(MAX(uhid),5,12) as uhid FROM central_opd where opd_no like '%$y%'";
            $op_no = mysqli_fetch_assoc(mysqli_query($con, $data));
            if (isset($op_no['opd'])) {
                $opd_no = $op_no['opd'] + 1;
            } else {
                $opd_no = $y . '0001';
            }
        }
        if ($y != substr($uhid, 0, 8)) {
            $data = "SELECT SUBSTR(MAX(uhid),5,12) as uhid FROM central_opd where opd_no like '%$y%'";
            $op_no = mysqli_fetch_assoc(mysqli_query($con, $data));
            if (isset($op_no['uhid'])) {
                $uhid = $op_no['uhid'] + 1;
            } else {
                $uhid = $y . '0001';
            }
        }

        $gender = strtoupper(trim((string)$this->sex));
        if ($gender === 'M') {
            $gender = 'MALE';
        } elseif ($gender === 'F') {
            $gender = 'FEMALE';
        }

        // Step 1: Search if customer already exists in RetailXpress by phone number
        $customerId = null;
        $phoneClean = preg_replace('/\D+/', '', (string)$this->mobile);

        $searchCurl = curl_init();
        curl_setopt_array($searchCurl, array(
            CURLOPT_URL => 'https://api.retailxpress.ai/v1/external/customer?page=1&limit=20&sort_by=createdAt&sort_order=desc&search=' . urlencode($phoneClean),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'x-api-key: 8417daa38eb33753f60ecbabe28d359bea4b4589d8c791f563b00b3de7c53543'
            ),
        ));
        $searchResponse = curl_exec($searchCurl);
        curl_close($searchCurl);
        $searchResult = json_decode($searchResponse, true);

        // Check if a matching customer was found (phone AND full_name must match)
        if (isset($searchResult['data']['items']) && count($searchResult['data']['items']) > 0) {
            $candidate = $searchResult['data']['items'][0];
            if (isset($candidate['full_name']) && strcasecmp(trim($candidate['full_name']), trim($this->fname)) === 0) {
                $customerId = $candidate['id'];
            }
        } elseif (isset($searchResult['data']) && is_array($searchResult['data']) && count($searchResult['data']) > 0 && isset($searchResult['data'][0]['id'])) {
            $candidate = $searchResult['data'][0];
            if (isset($candidate['full_name']) && strcasecmp(trim($candidate['full_name']), trim($this->fname)) === 0) {
                $customerId = $candidate['id'];
            }
        }

        // Step 2: Only create customer if not found
        if (empty($customerId)) {
            $customerPayload = array(
                "full_name" => (string)$this->fname,
                "phone_number" => $phoneClean,
                "address" => (string)$this->address,
                "gender" => $gender,
                "unique_code" => "BMSH" . $uhid
            );

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.retailxpress.ai/v1/external/customer',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($customerPayload),
                CURLOPT_HTTPHEADER => array(
                    'x-api-key: 8417daa38eb33753f60ecbabe28d359bea4b4589d8c791f563b00b3de7c53543',
                    'Content-Type: application/json'
                ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            $result = json_decode($response, true);
            $customerId = $result['data']['id'];
        }


        $sql = "INSERT INTO `central_opd`(`uhid`,`opd_no`,`date`, `name`, `age`, `gender`, `address`, `diagnosis`, `dept_id`,  `religion`,  `parent_name`,  `marital_status`,`mobile`,`doctor`,`created_by`,`pan`,`aadhar`,`policy`,`patient_type`,`city`,`mlc`,`rx_id`) 
      VALUES ('BMSH$uhid','OP$opd_no','$this->admdate','$this->fname','$this->age','$gender','$this->address','$this->diagnosis','$this->department','$this->religion','$this->pname','$this->marital','$this->mobile','$this->doctor','$_SESSION[name]','$this->pan','$this->aadhar','$this->policy','$this->ptype','$this->city','$this->mlc','$customerId')";
        //   echo $sql;exit;
        $res = mysqli_query($con, $sql);

        if ($con->connect_error) {
            die("Connection failed: " . $con->connect_error);
        }
        $id = mysqli_insert_id($con);
        if ($id > 0) {


            $query3 = "UPDATE data_table set table_id=$opd_no WHERE id=1";
            mysqli_query($con, $query3);
            $query4 = "UPDATE data_table set table_id=$uhid WHERE id=4";
            mysqli_query($con, $query4);

            $date = date("Y-m-d H:i:s");
            $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
            $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
            $sql1->execute();
            $sql1->close();
            $con->close();
        }


        if ($page == 'pathology') {

            echo "<script> window.location='../pathology_billing.php?opd=OP$opd_no';</script>";
        } elseif ($page == 'radiology') {
            echo "<script> window.location='../radiology_billing.php?opd=OP$opd_no';</script>";
        } elseif ($page == 'blood') {
            echo "<script> window.location='../blood_billing.php?opd=OP$opd_no';</script>";
        } elseif ($page == 'dialysis') {
            echo "<script> window.location='../dialysis_billing.php?opd=OP$opd_no';</script>";
        }
    }
    function getBillListUserwiseDetailed($from, $to, $search, $pay, $service)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "
            -- OP Billing with billing_payment
(SELECT 
    b.id,
    o.opd_no AS opd,
    o.name,
    o.gender,
    b.bill_no,
    bp.date,
    IFNULL(SUM(CASE WHEN bp.payment_mode = 'cash' THEN bp.amount ELSE 0 END), 0) AS cash,
    IFNULL(SUM(CASE WHEN bp.payment_mode = 'upi' THEN bp.amount ELSE 0 END), 0) AS upi,
    IFNULL(SUM(CASE WHEN bp.payment_mode = 'cheque' THEN bp.amount ELSE 0 END), 0) AS cheque,
    IFNULL(SUM(CASE WHEN bp.payment_mode = 'card' THEN bp.amount ELSE 0 END), 0) AS card,
    IFNULL(SUM(CASE WHEN bp.payment_mode = 'bank transfer' THEN bp.amount ELSE 0 END), 0) AS bank,
    0 AS credit,
    bp.collected_by,
    group_concat(bp.payment_note) as payment_note
FROM billing b
JOIN central_opd o ON o.id = b.patient_id
LEFT JOIN billing_payment bp ON bp.bill_no = b.bill_no
WHERE 
    DATE(bp.date) BETWEEN '$from' AND '$to'
    AND b.patient_type = 'OP'
    AND bp.collected_by LIKE '$search%'
    AND bp.payment_mode like '$pay%'
    and b.service_type like '$service%'
GROUP BY b.id)

UNION ALL

-- IP Billing with billing_payment
(SELECT 
    b.id,
    o.ipd_no AS opd,
    o.name,
    o.gender,
    b.bill_no,
    bp.date,
    IFNULL(SUM(CASE WHEN bp.payment_mode = 'cash' THEN bp.amount ELSE 0 END), 0) AS cash,
    IFNULL(SUM(CASE WHEN bp.payment_mode = 'upi' THEN bp.amount ELSE 0 END), 0) AS upi,
    IFNULL(SUM(CASE WHEN bp.payment_mode = 'cheque' THEN bp.amount ELSE 0 END), 0) AS cheque,
     IFNULL(SUM(CASE WHEN bp.payment_mode = 'card' THEN bp.amount ELSE 0 END), 0) AS card,
     IFNULL(SUM(CASE WHEN bp.payment_mode = 'bank transfer' THEN bp.amount ELSE 0 END), 0) AS bank,
    0 AS credit,
    bp.collected_by,
    group_concat(bp.payment_note) as payment_note
FROM billing b
JOIN central_ipd o ON o.ipd_no = b.patient_id
LEFT JOIN billing_payment bp ON bp.bill_no = b.bill_no
WHERE 
    DATE(bp.date) BETWEEN '$from' AND '$to'
    AND b.patient_type = 'IP'
    AND bp.collected_by LIKE '$search%'
    AND bp.payment_mode like '$pay%'
    and b.service_type like '$service%'
GROUP BY b.id)";
        if ($service == 'IP' || $service == '') {
            $sql .= "
UNION ALL

-- Advance Payment from ip_advance_amount
(SELECT 
    a.id,
    o.ipd_no AS opd,
    o.name,
    o.gender,
    a.id AS bill_no,
    a.date,
    IFNULL(CASE WHEN a.payment_type = 'cash' THEN a.amount ELSE 0 END, 0) AS cash,
    IFNULL(CASE WHEN a.payment_type = 'upi' THEN a.amount ELSE 0 END, 0) AS upi,
    IFNULL(CASE WHEN a.payment_type = 'cheque' THEN a.amount ELSE 0 END, 0) AS cheque,
     IFNULL(CASE WHEN a.payment_type = 'card' THEN a.amount ELSE 0 END, 0) AS card,
     IFNULL(CASE WHEN a.payment_type = 'bank transfer' THEN a.amount ELSE 0 END, 0) AS bank,
    0 AS credit,
    a.created_by AS collected_by,
    a.payment_note
FROM ip_advance_amount a
JOIN central_ipd o ON o.ipd_no = a.ip_no
WHERE 
    DATE(a.date) BETWEEN '$from' AND '$to'
    AND a.status = 'PAID'
    AND a.payment_type like '$pay%'
    AND a.created_by LIKE '$search%'
)";
        }
        if ($pay == 'Cash' || $pay == '') {
            $sql .= "
UNION ALL

-- Return Amounts
(SELECT 
    b.id,
    CASE WHEN b.patient_type = 'OP' THEN o.opd_no ELSE i.ipd_no END AS opd,
    CASE WHEN b.patient_type = 'OP' THEN o.name ELSE i.name END AS name,
    CASE WHEN b.patient_type = 'OP' THEN o.gender ELSE i.gender END AS gender,
    r.bill_no,
    r.returned_date AS date,
    0 - IFNULL(r.returned_amount, 0) AS cash,
    0 AS upi,
    0 AS cheque,
    0 AS card,
    0 AS credit,
    0 AS bank,
    r.returned_by AS collected_by,
    ''
FROM bill_return r
JOIN billing b ON r.bill_no = b.bill_no
LEFT JOIN central_opd o ON b.patient_id = o.id AND b.patient_type = 'OP'
LEFT JOIN central_ipd i ON b.patient_id = i.ipd_no AND b.patient_type = 'IP'
WHERE 
    DATE(r.returned_date) BETWEEN '$from' AND '$to'
    and b.service_type like '$service%'
    AND r.returned_by LIKE '$search%')";
        }
        if ($pay == 'Credit' || $pay == '') {
            $sql .= "
UNION ALL
(
  SELECT 
    b.id,
    CASE WHEN b.patient_type = 'OP' THEN o.opd_no ELSE i.ipd_no END AS opd,
    CASE WHEN b.patient_type = 'OP' THEN o.name ELSE i.name END AS name,
    CASE WHEN b.patient_type = 'OP' THEN o.gender ELSE i.gender END AS gender,
    b.bill_no,
    b.date,
    0 AS cash,
    0 AS upi,
    0 AS cheque,
    0 AS card,
    0 AS bank,
    (b.amount-IFNULL(bp.total_payment,0)-b.service_level_discount-b.discount_amount-b.returned_amount-IFNULL(a.adjusted_amount,0)) AS credit,
    b.collected_by,
    ''
  FROM billing b
  LEFT JOIN central_opd o ON b.patient_type = 'OP' AND b.patient_id = o.id
  LEFT JOIN central_ipd i ON b.patient_type = 'IP' AND b.patient_id = i.ipd_no
  LEFT JOIN (
    SELECT 
        bill_id, 
        date,
        SUM(amount) AS total_payment
    FROM billing_payment
    GROUP BY bill_id
)  bp ON bp.bill_id = b.id and date(bp.date)=date(b.date)
LEFT JOIN adjust_advance a ON a.invoice_no=b.bill_no
  WHERE DATE(b.date) BETWEEN '$from' AND '$to'
    AND b.collected_by LIKE '$search%'
    and b.service_type like '$service%'
    AND (case when b.status='cancelled' then paid_amount>0 ELSE 1=1 end)
    group by b.id
    having credit>0
)";
        }
        $sql .= " ORDER BY collected_by, id,date;
";
        // echo $sql;
        $res = mysqli_query($con, $sql);
        return $res;
    }
    function getBillListDetailed($from, $to, $search)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "
            -- OP Billing with billing_payment
(SELECT 
    b.id,
    o.opd_no AS opd,
    o.name,
    o.gender,
    b.bill_no,
    bp.date,
    IFNULL(SUM(CASE WHEN bp.payment_mode = 'cash' THEN bp.amount ELSE 0 END), 0) AS cash,
    IFNULL(SUM(CASE WHEN bp.payment_mode = 'upi' THEN bp.amount ELSE 0 END), 0) AS upi,
    IFNULL(SUM(CASE WHEN bp.payment_mode = 'cheque' THEN bp.amount ELSE 0 END), 0) AS cheque,
    IFNULL(SUM(CASE WHEN bp.payment_mode = 'card' THEN bp.amount ELSE 0 END), 0) AS card,
    0 AS credit,
    bp.collected_by,
    bp.payment_note
FROM billing b
JOIN central_opd o ON o.id = b.patient_id
LEFT JOIN billing_payment bp ON bp.bill_no = b.bill_no
WHERE 
    DATE(bp.date) BETWEEN '$from' AND '$to'
    AND b.patient_type = 'OP'
    AND bp.collected_by LIKE '$search%'
GROUP BY b.id)

UNION ALL

-- IP Billing with billing_payment
(SELECT 
    b.id,
    o.ipd_no AS opd,
    o.name,
    o.gender,
    b.bill_no,
    bp.date,
    IFNULL(SUM(CASE WHEN bp.payment_mode = 'cash' THEN bp.amount ELSE 0 END), 0) AS cash,
    IFNULL(SUM(CASE WHEN bp.payment_mode = 'upi' THEN bp.amount ELSE 0 END), 0) AS upi,
    IFNULL(SUM(CASE WHEN bp.payment_mode = 'cheque' THEN bp.amount ELSE 0 END), 0) AS cheque,
     IFNULL(SUM(CASE WHEN bp.payment_mode = 'card' THEN bp.amount ELSE 0 END), 0) AS card,
    0 AS credit,
    bp.collected_by,
    bp.payment_note
FROM billing b
JOIN central_ipd o ON o.ipd_no = b.patient_id
LEFT JOIN billing_payment bp ON bp.bill_no = b.bill_no
WHERE 
    DATE(bp.date) BETWEEN '$from' AND '$to'
    AND b.patient_type = 'IP'
    AND bp.collected_by LIKE '$search%'
GROUP BY b.id)

UNION ALL

-- Advance Payment from ip_advance_amount
(SELECT 
    a.id,
    o.ipd_no AS opd,
    o.name,
    o.gender,
    a.id AS bill_no,
    a.date,
    IFNULL(CASE WHEN a.payment_type = 'cash' THEN a.amount ELSE 0 END, 0) AS cash,
    IFNULL(CASE WHEN a.payment_type = 'upi' THEN a.amount ELSE 0 END, 0) AS upi,
    IFNULL(CASE WHEN a.payment_type = 'cheque' THEN a.amount ELSE 0 END, 0) AS cheque,
     IFNULL(CASE WHEN a.payment_type = 'card' THEN a.amount ELSE 0 END, 0) AS card,
    0 AS credit,
    a.created_by AS collected_by,
    ''
FROM ip_advance_amount a
JOIN central_ipd o ON o.ipd_no = a.ip_no
WHERE 
    DATE(a.date) BETWEEN '$from' AND '$to'
    AND a.status = 'PAID'
    AND a.created_by LIKE '$search%')

UNION ALL

-- Return Amounts
(SELECT 
    b.id,
    CASE WHEN b.patient_type = 'OP' THEN o.opd_no ELSE i.ipd_no END AS opd,
    CASE WHEN b.patient_type = 'OP' THEN o.name ELSE i.name END AS name,
    CASE WHEN b.patient_type = 'OP' THEN o.gender ELSE i.gender END AS gender,
    r.bill_no,
    r.returned_date AS date,
    0 - IFNULL(r.returned_amount, 0) AS cash,
    0 AS upi,
    0 AS cheque,
    0 AS card,
    0 AS credit,
    r.returned_by AS collected_by,
    ''
FROM bill_return r
JOIN billing b ON r.bill_no = b.bill_no
LEFT JOIN central_opd o ON b.patient_id = o.id AND b.patient_type = 'OP'
LEFT JOIN central_ipd i ON b.patient_id = i.ipd_no AND b.patient_type = 'IP'
WHERE 
    DATE(r.returned_date) BETWEEN '$from' AND '$to'
    AND r.returned_by LIKE '$search%')
UNION ALL
(
  SELECT 
    b.id,
    CASE WHEN b.patient_type = 'OP' THEN o.opd_no ELSE i.ipd_no END AS opd,
    CASE WHEN b.patient_type = 'OP' THEN o.name ELSE i.name END AS name,
    CASE WHEN b.patient_type = 'OP' THEN o.gender ELSE i.gender END AS gender,
    b.bill_no,
    b.date,
    0 AS cash,
    0 AS upi,
    0 AS cheque,
    0 AS card,
    (b.amount-IFNULL(bp.total_payment,0)-b.service_level_discount-b.discount_amount-b.returned_amount-IFNULL(a.adjusted_amount,0)) AS credit,
    b.collected_by,
    ''
  FROM billing b
  LEFT JOIN central_opd o ON b.patient_type = 'OP' AND b.patient_id = o.id
  LEFT JOIN central_ipd i ON b.patient_type = 'IP' AND b.patient_id = i.ipd_no
  LEFT JOIN (
    SELECT 
        bill_id, 
        date,
        SUM(amount) AS total_payment
    FROM billing_payment
    GROUP BY bill_id
)  bp ON bp.bill_id = b.id and date(bp.date)=date(b.date)
LEFT JOIN adjust_advance a ON a.invoice_no=b.bill_no
  WHERE DATE(b.date) BETWEEN '$from' AND '$to'
    AND b.collected_by LIKE '$search%'
    AND (case when b.status='cancelled' then paid_amount>0 ELSE 1=1 end)
    group by b.id
    having credit>0
)
ORDER BY collected_by, id,date;
";
        // echo $sql;
        $res = mysqli_query($con, $sql);
        return $res;
    }
    function addPayment($bill_id, $bill_no)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $query = "SELECT * FROM data_table WHERE id=11";

        $bill = mysqli_fetch_assoc(mysqli_query($con, $query));
        $bill_rec = $bill['table_id'];
        $financialYear = $this->getFinancialYear();
        $bill_no1 = $bill_rec + 1;
        $fi_bill_rec = 'REC/' . $financialYear . '/' . sprintf("%04d", $bill_no1);
        $qu = "select * from billing where id='$bill_id'";
        $res1 = mysqli_query($con, $qu);
        $row = mysqli_fetch_assoc($res1);
        if ($row['outstand_amount'] > 0) {
            $note = mysqli_real_escape_string($con, $this->note);
            $sql = "INSERT INTO `billing_payment`( `bill_id`, `bill_no`, `bill_receipt`, `date`, `amount`, `payment_mode`, `payment_note`, `payment_status`,collected_by,created_at) 
         VALUES ('$bill_id','$bill_no','$fi_bill_rec','$this->payment_date','$this->paid_amt','$this->payment_mode','$note','PAID','$_SESSION[name]',now())";
            //  echo $sql;exit;
            $res = mysqli_query($con, $sql);
            if ($res) {

                $query1 = "UPDATE data_table set table_id='$bill_no1' WHERE id=11";
                mysqli_query($con, $query1);

                $sql1 = "UPDATE billing SET paid_amount=paid_amount+$this->paid_amt,outstand_amount=outstand_amount-$this->paid_amt WHERE id='$bill_id'";
                mysqli_query($con, $sql1);
            } else {
                echo "<script>alert('Payment not recorded Please try again.')</script>";
            }
            $date = date("Y-m-d H:i:s");
            $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
            $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
            $sql1->execute();
            $sql1->close();
        } else {
            echo "<script>alert('There is no outstanding amount. Please check and resubmit')</script>";
        }
        return $res;
    }
    function addOPPayment($bill_id, $bill_no)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $query = "SELECT * FROM data_table WHERE id=11";

        $bill = mysqli_fetch_assoc(mysqli_query($con, $query));
        $bill_rec = $bill['table_id'];
        $financialYear = $this->getFinancialYear();
        $bill_no1 = $bill_rec + 1;
        $fi_bill_rec = 'REC/' . $financialYear . '/' . sprintf("%04d", $bill_no1);

        $note = mysqli_real_escape_string($con, $this->note);
        $sql = "INSERT INTO `billing_payment`( `bill_id`, `bill_no`, `bill_receipt`, `date`, `amount`, `payment_mode`, `payment_note`, `payment_status`,collected_by,created_at) 
         VALUES ('$bill_id','$bill_no','$fi_bill_rec','$this->payment_date','$this->paid_amt','$this->payment_mode','$note','PAID','$_SESSION[name]',now())";
        //  echo $sql;exit;
        $res = mysqli_query($con, $sql);
        if ($res) {

            $query1 = "UPDATE data_table set table_id='$bill_no1' WHERE id=11";
            mysqli_query($con, $query1);
        } else {
            echo "<script>alert('Payment not recorded Please try again.')</script>";
        }
        $date = date("Y-m-d H:i:s");
        $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
        $sql1->close();

        return $res;
    }
    function getBillPayment($bill_id, $bill_no)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "SELECT * FROM `billing_payment` WHERE `bill_id`='$bill_id' and `bill_no`='$bill_no'";
        //   echo $sql;
        $res = mysqli_query($con, $sql);
        return $res;
    }
    function getPayment($id)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "SELECT * FROM `billing_payment` WHERE `id`='$id'";
        //   echo $sql;
        $res = mysqli_query($con, $sql);
        return $res;
    }
    function getFinancialYear()
    {
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
    function insertClaimBill($bill_no)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();



        $service = $this->service;
        $patient_type = $this->patient_type;

        $discount = $this->discount;
        $gross = $this->gross;
        $srv_disc = $this->srv_disc;
        $tot_amt = $this->tot_amt;
        $grand = $this->grand;
        $paid_amt = 0;
        $outstand = $this->outstand;
        $service_type = $this->service_type;
        $ward = $this->ward;

        $financialYear = $this->getFinancialYear();
        if ($bill_no == '') {
            $query = "SELECT * FROM data_table WHERE id=14";
            $bill = mysqli_fetch_assoc(mysqli_query($con, $query));
            $bill_no = $bill['table_id'];

            $bill_no = $bill_no + 1;
        }
        $fi_bill_no = $bill_no;

        $sql = "INSERT INTO `claim_billing` ( `patient_id`, `amount`, `service_level_discount`, `discount_amount`, `paid_amount`, `outstand_amount`, `bill_no`, `doctor`, `dept_id`,`ward_type`, `service_type`, `patient_type`, `status`, `collected_by`, `collected_at`) VALUES 
                           ( '$this->patient_id', '$tot_amt', '$srv_disc', '$discount', '$paid_amt', '$outstand', '$fi_bill_no', '$this->doc', '$this->department','$ward', '$service_type', '$patient_type', 'PAID', '$_SESSION[name]', NOW())";
        //   echo $sql;exit;
        $res = mysqli_query($con, $sql);
        $id = mysqli_insert_id($con);
        $date = date("Y-m-d H:i:s");
        $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
        $sql1->close();


        //   $query1="UPDATE data_table set table_id='$bill_no' WHERE id=14";


        //   mysqli_query($con,$query1);
        $con->close();
        return $fi_bill_no . '|' . $id;
    }
    function insertClaimBillingDetails($id, $fi_bill_no)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $quantity = $this->quantity;
        $service_id = $this->service_id;
        $price = $this->price;
        $disc_amt = $this->disc_amt;
        $doctor = $this->doctor;
        $sql = "INSERT INTO `claim_billing_details` (`bill_id`, `bill_no`, `service_id`, `doctor_id`, `unit`, `price`, `discount`, `status`, `created_at`) VALUES 
                    ( '$id', '$fi_bill_no', '$service_id', '$doctor', '$quantity', '$price', '$disc_amt', 'PAID', NOW())";
        $res = mysqli_query($con, $sql);
        $date = date("Y-m-d H:i:s");
        $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
        $sql1->close();
        $con->close();
        return $res;
    }
    function updateClaimBill()
    {
        $billid = $this->billid;
        $billno = $this->billno;

        $patient_type = $this->patient_type;

        $discount = $this->discount;
        $gross = $this->gross;
        $srv_disc = $this->srv_disc;
        $tot_amt = $this->tot_amt;
        $grand = $this->grand;
        $paid_amt = 0;
        $outstand = $this->outstand;
        $service_type = $this->service_type;
        $date = date("Y-m-d H:i:s", strtotime($this->date));

        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();

        if ($gross == 0) {
            $sql = "UPDATE `claim_billing` set status='CANCELLED' where id='$billid' and bill_no='$billno'";
        } else {
            $sql = "UPDATE `claim_billing` set `amount`='$tot_amt',`date`='$date', `service_level_discount`='$srv_disc', `discount_amount`='$discount',  `outstand_amount`='$outstand', `doctor`='$this->doc', `service_type`='$service_type', `patient_type`='$patient_type',`dept_id`='$this->department', `updated_by`='$_SESSION[name]', `updated_at`=NOW() where id='$billid' and bill_no='$billno'";
            // echo $sql;exit;
        }
        $res = mysqli_query($con, $sql);
        $date = date("Y-m-d H:i:s");
        $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
        $sql1->close();
        $con->close();



        return 1;
    }
    function updateClaimBilldetails($id)
    {
        $doctor = $this->doctor;
        $service = $this->service;
        $quantity = $this->quantity;
        $service_id = $this->service_id;
        $price = $this->price;
        $disc_per = $this->disc_per;
        $disc_amt = $this->disc_amt;
        $total = $this->total;
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql1 = "UPDATE `claim_billing_details` set  `service_id`='$service_id', `doctor_id`='$doctor', `unit`='$quantity', `price`='$price', `discount`='$disc_amt',  `updated_at`=NOW(), `updated_by`='$_SESSION[name]' where id='$id'";
        // echo $sql1;exit;
        mysqli_query($con, $sql1);
        $date = date("Y-m-d H:i:s");
        $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
        $sql1->close();
        $con->close();
        return 1;
    }
    function getClaimBill($id, $bill_no)
    {

        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "select b.paid_amount,b.returned_amount,b.discount_amount,b.collected_by,bd.*,sl.name as service,concat(d.name, ' ',d.last_name) as doctor_name,b.service_type,b.ward_type from service_list sl, claim_billing b join claim_billing_details bd on 1=1  LEFT JOIN doctor d ON d.id=bd.doctor_id where sl.id=bd.service_id and b.id=bd.bill_id and  b.patient_id='$id' and bd.status='PAID' and b.bill_no='$bill_no'";
        // echo $sql;exit;
        $result = mysqli_query($con, $sql);
        mysqli_close($con);
        return $result;
    }


    function insertESIBill($bill_no)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();



        $service = $this->service;
        $patient_type = $this->patient_type;

        $discount = $this->discount;
        $gross = $this->gross;
        $srv_disc = $this->srv_disc;
        $tot_amt = $this->tot_amt;
        $grand = $this->grand;
        $paid_amt = 0;
        $outstand = $this->outstand;
        $service_type = $this->service_type;
        $ward = $this->ward;
        $financialYear = $this->getFinancialYear();

        //     $query="SELECT * FROM data_table WHERE id=15";
        //  $bill=mysqli_fetch_assoc(mysqli_query($con,$query));
        //  $bill_no=$bill['table_id'];

        //  $bill_no=$bill_no+1;
        $fi_bill_no = $bill_no;

        $sql = "INSERT INTO `esi_billing` ( `patient_id`, `amount`, `service_level_discount`, `discount_amount`, `paid_amount`, `outstand_amount`, `bill_no`, `doctor`, `dept_id`,  `service_type`, `patient_type`, `ward_type`, `status`, `collected_by`, `collected_at`) VALUES 
                           ( '$this->patient_id', '$tot_amt', '$srv_disc', '$discount', '$paid_amt', '$outstand', '$fi_bill_no', '$this->doc', '$this->department', '$service_type', '$patient_type','$ward', 'PAID', '$_SESSION[name]', NOW())";
        //   echo $sql;exit;
        $res = mysqli_query($con, $sql);
        $id = mysqli_insert_id($con);
        $date = date("Y-m-d H:i:s");
        $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
        $sql1->close();


        //   $query1="UPDATE data_table set table_id='$bill_no' WHERE id=15";


        //   mysqli_query($con,$query1);
        $con->close();
        return $fi_bill_no . '|' . $id;
    }
    function insertESIBillingDetails($id, $fi_bill_no)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $quantity = $this->quantity;
        $service_id = $this->service_id;
        $price = $this->price;
        $disc_amt = $this->disc_amt;
        $doctor = $this->doctor;

        $sql = "INSERT INTO `esi_billing_details` (`bill_id`, `bill_no`, `service_id`, `doctor_id`, `unit`, `price`, `discount`, `status`, `created_at`) VALUES 
                    ( '$id', '$fi_bill_no', '$service_id', '$doctor', '$quantity', '$price', '$disc_amt', 'PAID', NOW())";
        $res = mysqli_query($con, $sql);
        $date = date("Y-m-d H:i:s");
        $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
        $sql1->close();
        $con->close();
        return $res;
    }
    function updateESIBill()
    {
        $billid = $this->billid;
        $billno = $this->billno;

        $patient_type = $this->patient_type;

        $discount = $this->discount;
        $gross = $this->gross;
        $srv_disc = $this->srv_disc;
        $tot_amt = $this->tot_amt;
        $grand = $this->grand;
        $paid_amt = 0;
        $outstand = $this->outstand;
        $service_type = $this->service_type;
        $ward = $this->ward;
        $date = date("Y-m-d H:i:s", strtotime($this->date));
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();

        if ($gross == 0) {
            $sql = "UPDATE `esi_billing` set status='CANCELLED' where id='$billid' and bill_no='$billno'";
        } else {
            $sql = "UPDATE `esi_billing` set `amount`='$tot_amt',date='$date', `service_level_discount`='$srv_disc', `discount_amount`='$discount',`ward_type`='$ward', `outstand_amount`='$outstand', `doctor`='$this->doc', `service_type`='$service_type', `patient_type`='$patient_type',`dept_id`='$this->department', `updated_by`='$_SESSION[name]', `updated_at`=NOW() where id='$billid' and bill_no='$billno'";
            // echo $sql;exit;
        }
        $res = mysqli_query($con, $sql);
        $date = date("Y-m-d H:i:s");
        $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
        $sql1->close();
        $con->close();



        return 1;
    }
    function updateESIBilldetails($id)
    {
        $doctor = $this->doctor;
        $service = $this->service;
        $quantity = $this->quantity;
        $service_id = $this->service_id;
        $price = $this->price;
        $disc_per = $this->disc_per;
        $disc_amt = $this->disc_amt;
        $total = $this->total;
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql1 = "UPDATE `esi_billing_details` set  `service_id`='$service_id', `doctor_id`='$doctor', `unit`='$quantity', `price`='$price', `discount`='$disc_amt',  `updated_at`=NOW(), `updated_by`='$_SESSION[name]' where id='$id'";
        // echo $sql1;exit;
        mysqli_query($con, $sql1);
        $date = date("Y-m-d H:i:s");
        $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
        $sql1->close();
        $con->close();
        return 1;
    }
    function getESIBill($id, $bill_no)
    {

        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "select b.paid_amount,b.returned_amount,b.discount_amount,b.collected_by,bd.*,sl.name as service,concat(d.name, ' ',d.last_name) as doctor_name,b.service_type,sl.esi_code from service_list sl, esi_billing b join esi_billing_details bd on 1=1  LEFT JOIN doctor d ON d.id=bd.doctor_id where sl.id=bd.service_id and b.id=bd.bill_id and  b.patient_id='$id' and bd.status='PAID' and b.bill_no='$bill_no'";
        // echo $sql;;
        $result = mysqli_query($con, $sql);
        mysqli_close($con);
        return $result;
    }
    function getCalimPatientBill($id, $bill)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "SELECT c.opd_no,c.ipd_no,c.name,c.gender,c.age,c.mobile,c.admission_date,c.doctor,c.address,dis.discharge_date,be.bed_number,c.discharge,c.patient_type as scheme,d.dept_name,b.* FROM claim_billing b,bed be,department d,`central_ipd` c LEFT JOIN discharge dis on dis.patient_id=c.ipd_no where d.id=c.dept_id and c.bed_id=be.id and c.ipd_no='$id' and c.ipd_no=b.patient_id and b.bill_no='$bill'";
        //   echo $sql;
        //   exit;
        $res = mysqli_query($con, $sql);
        return $res;
    }

    function getBillListClaim($from, $to, $s, $type)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $data = '';
        if (strlen($s) > 0) {
            $data .= " (c.`ipd_no` like '%$s%' || b.bill_no like '%$s%')";
        } else {
            $data .= " date(b.`date`) between '$from' and '$to'";
        }
        // if($_SESSION['role']!='Admin')
        // {
        //     $data.=" b.collected_by='$_SESSION[name]'"; 
        // }
        $sql = "select b.date,b.returned_amount,sum(bd.price * bd.unit) as gt, sum(bd.discount) as discount, b.outstand_amount as outstand,b.discount_amount as b_discount,b.status,b.bill_no,d.dept_name,GROUP_CONCAT(s.name SEPARATOR '<br/><br/>') as service, GROUP_CONCAT(bd.`price` SEPARATOR '<br/><br/>') as price,GROUP_CONCAT(bd.`unit` SEPARATOR '<br/><br/>') as unit,c.*,b.id,b.collected_by,0 as advance,b.paid_amount from  claim_billing_details bd, central_ipd c,department d,service_list s,`claim_billing` b  where d.id=c.dept_id and b.patient_id=c.ipd_no and b.id=bd.bill_id and bd.status!='cancelled' and bd.service_id=s.id and b.service_type='$type' and $data  group by c.id,b.id";
        // echo $sql;
        // exit;
        $result = mysqli_query($con, $sql);
        mysqli_close($con);
        return $result;
    }
    function getBillListESI($from, $to, $s, $type)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $data = '';
        if (strlen($s) > 0) {
            $data .= " (c.`ipd_no` like '%$s%' || b.bill_no like '%$s%')";
        } else {
            $data .= " date(b.`date`) between '$from' and '$to'";
        }
        // if($_SESSION['role']!='Admin')
        // {
        //     $data.=" b.collected_by='$_SESSION[name]'"; 
        // }
        $sql = "select b.date,b.returned_amount,sum(bd.price * bd.unit) as gt, sum(bd.discount) as discount, b.outstand_amount as outstand,b.discount_amount as b_discount,b.status,b.bill_no,d.dept_name,GROUP_CONCAT(s.name SEPARATOR '<br/><br/>') as service, GROUP_CONCAT(bd.`price` SEPARATOR '<br/><br/>') as price,GROUP_CONCAT(bd.`unit` SEPARATOR '<br/><br/>') as unit,c.*,b.id,b.collected_by,0 as advance,b.paid_amount 
            from  esi_billing_details bd, central_ipd c,department d,service_list s,`esi_billing` b  where d.id=c.dept_id and b.patient_id=c.ipd_no and b.id=bd.bill_id and bd.status!='cancelled' and bd.service_id=s.id  and $data  group by c.id,b.id";
        // echo $sql;
        // exit;
        $result = mysqli_query($con, $sql);
        mysqli_close($con);
        return $result;
    }

    function getESIPatientBill($id, $bill)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "SELECT c.opd_no,c.ipd_no,c.name,c.gender,c.age,c.mobile,c.admission_date,c.doctor,c.address,c.discharge,dis.discharge_date,be.bed_number,d.dept_name,b.* 
            FROM esi_billing b,bed be,department d,`central_ipd` c LEFT JOIN discharge dis on dis.patient_id=c.ipd_no
            where d.id=b.dept_id and c.bed_id=be.id and c.ipd_no='$id' and c.ipd_no=b.patient_id and b.bill_no='$bill'";
        //   echo $sql;
        //   exit;
        $res = mysqli_query($con, $sql);
        return $res;
    }
    function getClaimPatientBill($id, $bill)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "SELECT c.opd_no,c.ipd_no,c.name,c.gender,c.age,c.mobile,c.admission_date,c.doctor,c.address,c.discharge,dis.discharge_date,be.bed_number,d.dept_name,b.* 
            FROM claim_billing b,bed be,department d,`central_ipd` c LEFT JOIN discharge dis on dis.patient_id=c.ipd_no
            where d.id=b.dept_id and c.bed_id=be.id and c.ipd_no='$id' and c.ipd_no=b.patient_id and b.bill_no='$bill'";
        //   echo $sql;
        //   exit;
        $res = mysqli_query($con, $sql);
        return $res;
    }
    function getLabPackage()
    {
        $newlogin = new userlogin();
        $con = $newlogin->dbconnect();
        $sql = "SELECT 
            lp.*, 
            GROUP_CONCAT(lpt.test_group_id) as test_group_ids,
            GROUP_CONCAT(lt.testname) as test_names,
            GROUP_CONCAT(lt.price) as test_price
          FROM lab_package lp
          LEFT JOIN lab_package_tests lpt ON lp.id = lpt.package_id
          LEFT JOIN lab_test lt ON lpt.test_group_id = lt.testid
          GROUP BY lp.id";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    function getRefCenter()
    {
        $newlogin = new userlogin();
        $con = $newlogin->dbconnect();
        $sql = "SELECT * FROM reference_centers";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    function insertDischarge()
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql1 = "SELECT * FROM `discharge` WHERE `patient_id`='$this->patinetid'";

        $res1 = mysqli_query($con, $sql1);
        if (mysqli_num_rows($res1) == 0) {
            $sql = "INSERT INTO `discharge`(`patient_id`, `ref_no`, `patient_name`,`dept_id`, `age`, `sex`, `admission_date`, `discharge_date`, `diagnosis`, `complaints`, `investigation`, `course_treatment`, `advice_discharge`,`physical_examination`,`created_by`,`discharge_condition`,`discharge_file`) 
          VALUES ('$this->patinetid','$this->refno','$this->fname','$this->deptid','$this->age','$this->sex','$this->admidate','$this->disdate','$this->diagnosis','$this->complaint','$this->investigations','$this->course','$this->advice','$this->exam','$_SESSION[name]','$this->dis_condtion','$this->files')";
            //  return 1;
            //  echo $sql;exit;
            $res = mysqli_query($con, $sql);

            $date = date("Y-m-d H:i:s");
            $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
            $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
            $sql1->execute();
            $sql1->close();

            $query = "UPDATE `bed` SET `status`='active' WHERE id=(SELECT `bed_id` FROM `central_ipd` WHERE ipd_no='$this->patinetid')";
            mysqli_query($con, $query);

            $query = "UPDATE `central_ipd` SET discharge=1,final_bill=1 WHERE ipd_no='$this->patinetid'";
            mysqli_query($con, $query);
            $con->close();

            return $res;
        } else {
            echo "<script>alert('Patient Already Discharged Please check the IP Number')</script>";
            //   echo mysqli_num_rows($res1);
            //   echo $sql1;
            // exit;
            return 1;
        }
    }
    function selectOP_seacrh($search)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "SELECT c.*,d.dept_name FROM department d,`central_ipd` c WHERE  c.dept_id=d.id and c.ipd_no='$search' ";
        // echo $sql;
        $res = mysqli_query($con, $sql);
        return $res;
    }
    function getBillPatient($data)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "(SELECT b.date,b.bill_no,b.doctor,b.paid_amount,b.payment_mode,b.collected_by,b.patient_id,b.service_type,b.status,d.dept_name,b.id,b.outstand_amount FROM `billing` b,department d WHERE d.id=b.dept_id and patient_id='$data' and b.status='PAID')
        UNION ALL (select date,concat('ADV',id),'',amount,payment_type,created_by,ip_no,'Advance',status,'',id,'' from ip_advance_amount where ip_no='$data' and status='PAID') order by date";
        // echo $sql;
        $res = mysqli_query($con, $sql);
        return $res;
    }
    function updatePayment($amount, $date, $mode, $note, $id, $bill_id, $collect)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();

        $query = "SELECT * FROM billing WHERE id=$bill_id";
        $res1 = mysqli_query($con, $query);
        $row = mysqli_fetch_assoc($res1);

        $sql = "UPDATE billing_payment SET `date`='$date',`amount`='$amount',`payment_mode`='$mode',`payment_note`='$note',collected_by='$collect' WHERE id='$id'";
        $res = mysqli_query($con, $sql);
        $date = date("Y-m-d H:i:s");
        $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
        $sql1->close();

        $query1 = "SELECT sum(amount) as paid_amount from billing_payment where bill_id='$bill_id'";
        $res2 = mysqli_query($con, $query1);
        $row1 = mysqli_fetch_assoc($res2);
        $paid = $row1['paid_amount'];
        $prev_outstand = $row['outstand_amount'];
        $outstand = $prev_outstand + $row['paid_amount'] - $paid;
        $update = "UPDATE billing SET paid_amount='$paid',outstand_amount='$outstand' WHERE id='$bill_id'";
        mysqli_query($con, $update);
        return $res;
    }

    function markRoundAssessmentsBilled($ids, $dc)
    {
        $ids = preg_replace('/[^0-9,]/', '', $ids);
        if (empty($ids)) return;

        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();

        $sql = "UPDATE ip_round_assessment SET status='BILLED' WHERE id IN ($ids)";
        mysqli_query($con, $sql);

        $date = date("Y-m-d H:i:s");
        $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
        $sql1->close();

        $dcNumbers = array();
        if (is_array($dc)) {
            foreach ($dc as $dcNo) {
                preg_match_all('/DC\/[A-Z0-9\/\-]+/i', strtoupper((string)$dcNo), $matches);
                if (!empty($matches[0])) {
                    foreach ($matches[0] as $matchedDc) {
                        $dcNumbers[] = mysqli_real_escape_string($con, trim($matchedDc));
                    }
                }
            }
        } else {
            preg_match_all('/DC\/[A-Z0-9\/\-]+/i', strtoupper((string)$dc), $matches);
            if (!empty($matches[0])) {
                foreach ($matches[0] as $matchedDc) {
                    $dcNumbers[] = mysqli_real_escape_string($con, trim($matchedDc));
                }
            }
        }

        $dcNumbers = array_values(array_unique($dcNumbers));

        if (!empty($dcNumbers)) {
            $dcList = "'" . implode("','", $dcNumbers) . "'";
            $dcSql = "SELECT rx_so_id FROM `ip_medicine` WHERE dc_no IN ($dcList) AND dc_no LIKE 'DC/%' AND dc_no NOT LIKE 'DCR/%' AND IFNULL(rx_so_id,'') != '' GROUP BY dc_no";
            $dcRes = mysqli_query($con, $dcSql);

            if ($dcRes) {
                while ($row = mysqli_fetch_assoc($dcRes)) {
                    $rxSoId = trim($row['rx_so_id']);
                    if ($rxSoId == '') {
                        continue;
                    }

                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => 'https://api.retailxpress.ai/v1/external/retailer-delivery-challan/' . urlencode($rxSoId) . '/mark-as-billed',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'PATCH',
                        CURLOPT_HTTPHEADER => array(
                            'x-api-key: 8417daa38eb33753f60ecbabe28d359bea4b4589d8c791f563b00b3de7c53543',
                            'Content-Type: application/json'
                        ),
                    ));

                    curl_exec($curl);
                    curl_close($curl);
                }
            }
        }

        $con->close();
    }

    function updateAlreadyBilledDc($dcInput, $apiBaseUrl = 'https://api.retailxpress.ai')
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();

        $rawInput = is_array($dcInput) ? implode("\n", $dcInput) : (string)$dcInput;
        preg_match_all('/DC\/[A-Z0-9\/\-]+/i', strtoupper($rawInput), $matches);

        $dcNumbers = array();
        if (!empty($matches[0])) {
            foreach ($matches[0] as $matchedDc) {
                $matchedDc = trim($matchedDc);
                if ($matchedDc != '' && strpos($matchedDc, 'DCR/') !== 0) {
                    $dcNumbers[] = $matchedDc;
                }
            }
        }

        $dcNumbers = array_values(array_unique($dcNumbers));

        if (empty($dcNumbers)) {
            $con->close();
            return array(
                'status' => 'error',
                'message' => 'No valid DC numbers found. Use format like DC/11/2627/715',
                'dc_numbers' => array(),
                'matched' => array(),
                'not_found' => array(),
                'api_results' => array()
            );
        }

        $safeDcNumbers = array();
        foreach ($dcNumbers as $dcNo) {
            $safeDcNumbers[] = "'" . mysqli_real_escape_string($con, $dcNo) . "'";
        }

        $dcList = implode(',', $safeDcNumbers);
        $dcSql = "SELECT dc_no, rx_so_id FROM `ip_medicine` WHERE dc_no IN ($dcList) AND dc_no LIKE 'DC/%' AND dc_no NOT LIKE 'DCR/%' GROUP BY dc_no";
        $dcRes = mysqli_query($con, $dcSql);

        $date = date("Y-m-d H:i:s");
        $logStmt = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $logStmt->bind_param("sss", $dcSql, $_SESSION['name'], $date);
        $logStmt->execute();
        $logStmt->close();

        if (!$dcRes) {
            $error = mysqli_error($con);
            $con->close();
            return array(
                'status' => 'error',
                'message' => $error,
                'dc_numbers' => $dcNumbers,
                'matched' => array(),
                'not_found' => $dcNumbers,
                'api_results' => array()
            );
        }

        $matched = array();
        $apiResults = array();
        $apiBaseUrl = rtrim($apiBaseUrl, '/');

        while ($row = mysqli_fetch_assoc($dcRes)) {
            $dcNo = trim($row['dc_no']);
            $rxSoId = trim($row['rx_so_id']);
            $matched[$dcNo] = $rxSoId;

            if ($rxSoId == '') {
                $apiResults[] = array(
                    'dc_no' => $dcNo,
                    'rx_so_id' => '',
                    'http_code' => 0,
                    'response' => '',
                    'error' => 'rx_so_id is empty'
                );
                continue;
            }

            $url = $apiBaseUrl . '/v1/external/retailer-delivery-challan/' . urlencode($rxSoId) . '/mark-as-billed';
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'PATCH',
                CURLOPT_HTTPHEADER => array(
                    'x-api-key: 8417daa38eb33753f60ecbabe28d359bea4b4589d8c791f563b00b3de7c53543',
                    'Content-Type: application/json'
                ),
            ));

            $response = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $error = curl_error($curl);
            curl_close($curl);

            $apiResults[] = array(
                'dc_no' => $dcNo,
                'rx_so_id' => $rxSoId,
                'http_code' => $httpCode,
                'response' => $response,
                'error' => $error
            );
        }

        $notFound = array_values(array_diff($dcNumbers, array_keys($matched)));
        $con->close();

        return array(
            'status' => 'success',
            'message' => 'Processed ' . count($matched) . ' DC number(s)',
            'dc_numbers' => $dcNumbers,
            'matched' => $matched,
            'not_found' => $notFound,
            'api_results' => $apiResults
        );
    }
}
