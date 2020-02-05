<?php

require_once 'connection.php';
session_start();
if (isset($_POST['cid'])) {
    $category = $pdo->getResult("SELECT * FROM category WHERE cat_id = '" . $_POST['cid'] . "'");
    echo json_encode($category);
}

if (isset($_POST['iid'])) {
    $str = $_POST['iid'];
    $icd = $pdo->getResult("SELECT * FROM icd WHERE icd_code = '$str'");
    echo json_encode($icd);
}

if (isset($_REQUEST['action'])) {
    if ($_REQUEST['action'] == 'getFirstValue') {
        $id = $_GET['id'];
        $snf = $pdo->getResult("SELECT * FROM snfs WHERE id='$id'");
        echo json_encode(['success' => true, 'data' => $snf]);
    }
}

if (isset($_REQUEST['action'])) {
    if ($_REQUEST['action'] == 'getQuestions') {
        $result = $pdo->getResult("SELECT * FROM questions");
        echo json_encode($result);
    }
    if ($_REQUEST['action'] == 'getPatients') {
        $snfID = $_POST['snfsid'];
        if ($snfID == 0) {
            $arr = [];
            $count = $pdo->getCount("SELECT count(*) as count FROM patients");
            $arr['draw'] = $_POST['draw'];
            $arr['recordsTotal'] = $count;
            $arr['recordsFiltered'] = $count;
            $query = "SELECT * FROM patients";
            $arr['data'] = $pdo->getResult($query);
            echo json_encode($arr);
        } else {
            $arr = [];
            $count = $pdo->getCount("SELECT count(*) as count FROM patients WHERE snf_id=$snfID");
            $arr['draw'] = $_POST['draw'];
            $arr['recordsTotal'] = $count;
            $arr['recordsFiltered'] = $count;
            $query = "SELECT * FROM patients WHERE snf_id=$snfID ORDER BY ID DESC LIMIT " . $_POST['start'] . " , " . $_POST['length'] . " ";
            //echo $query; die;
            $arr['data'] = $pdo->getResult($query);
            echo json_encode($arr);
        }
    }

    if ($_REQUEST['action'] == 'getActivity') {
        $selectuser = $_REQUEST['selectuser'];
        $arr = [];
        $count = $pdo->getCount("SELECT count(*) AS count FROM activity_log WHERE username='$selectuser'");
        $arr['draw'] = $_POST['draw'];
        $arr['recordsTotal'] = $count;
        $arr['recordsFiltered'] = $count;
        $query = "SELECT * FROM activity_log WHERE username='$selectuser' LIMIT " . $_POST['start'] . " , " . $_POST['length'] . " ";
        $arr['data'] = $pdo->getResult($query);
        echo json_encode($arr);       
    }

    if ($_REQUEST['action'] == 'getUser') {

        $arr = [];
        $search_str = $_POST['search']['value'];

        $count = $pdo->getCount("SELECT COUNT(*) as count FROM users");
        $arr['draw'] = $_POST['draw'];
        $arr['recordsTotal'] = $count;
        $arr['recordsFiltered'] = $count;
        $query = "SELECT CONCAT(firstname, ' ', lastname) AS name, email FROM users WHERE email LIKE '%$search_str%' OR firstname LIKE '%$search_str%' OR lastname LIKE '%$search_str%' LIMIT " . $_POST['start'] . " , " . $_POST['length'] . " ";
        $arr['data'] = $pdo->getResult($query);
        echo json_encode($arr);
    }

    if($_REQUEST['action'] == 'getUsersAndSnfs') {
        $snfsQuery = "SELECT * from snfs";
        //Get All SNFS
        $snfs = $pdo->getResult($snfsQuery);
        $usersQuery = "SELECT * FROM users";
        $users = $pdo->getResult($usersQuery);
        $selectHtml = '<select class="form-control" id="userName" name="userName">';
        $selectHtml .= '<option value=0>Select User</option>';
        foreach ($users as $key => $value) {
            $selectHtml .= '<option value="'. $value['id'] . '">'. $value['firstname'] . ' ' . $value['lastname'] .'</option>';
        }
        $selectHtml .= '</select>';
        $snfsHtml = '';
        foreach ($snfs as $key => $value) {
            $snfsHtml  .= '<div class="custom-control custom-checkbox float-left"><input type="checkbox" data-id = "' . $value['id'] . '"class="form-check-input" id="q' . $value['id'] . '" name="snfids" increment="1">  <label class="form-check-label" for="q' . $value['name'] . '">' . $value['name'] . '</label></div><br>';
        }
        $data['usersHtml'] = $selectHtml;
        $data['snfsHtml'] = $snfsHtml;
        echo json_encode($data);
        die;
    }

    if ($_REQUEST['action'] == 'getAnswers') {
		$medicalrecord	= urldecode($_POST['medicalrecord']);
        $patientname  = urldecode($_POST['patientname']);

        // Add activity log
        $username = $_SESSION['username'];
        $action = $username." viewed that name is '".$patientname."' and medical record is ".$medicalrecord;
        $query = "INSERT INTO activity_log (username, action) VALUES(?,?)";
        $activity_logs = $pdo->insert($query, [$username, $action]);
        $result = $pdo->getResult("SELECT p.*, q.title FROM patient_answers p INNER JOIN questions q ON (p.question_id = q.id) where medicalrecord = ?", [$medicalrecord]);
        $data['answers'] = $result;
        $result = $pdo->getResult("SELECT p.*, i.icd_desc, i.icd_tertiary_ranking FROM patient_icd_codes p INNER JOIN icd i ON (p.icd_code = i.icd_code) where medicalrecord = ? order by p.id asc", [$medicalrecord]); //icd_tertiary_ranking 
        $data['icd_codes'] = $result;
        echo json_encode($data);
    }

	if ($_REQUEST['action'] == 'changehospital') {
        $hospitalname	= $_GET['hospitalname'];
        $data = $pdo->getResult("SELECT * FROM snfs WHERE id=$hospitalname");
        $_SESSION['hospital'] = $data[0]['name'];
        echo json_encode($data);
    }

    if ($_REQUEST['action'] == 'activitylogs') {
        $selectuser = $_REQUEST['selectuser'];
        $arr = [];
        $count = $pdo->getCount("SELECT count(*) AS count FROM activity_log WHERE username='$selectuser'");
        $arr['draw'] = $_POST['draw'];
        $arr['recordsTotal'] = $count;
        $arr['recordsFiltered'] = $count;
        $query = "SELECT * FROM activity_log WHERE username='$selectuser'";
        $arr['data'] = $pdo->getResult($query);
        echo json_encode($arr);
    }

	if ($_REQUEST['action'] == 'savesnfs') {
        $snfsname	= $_POST['snfsname'];
        try{
            $existChk = $pdo->getResult('SELECT 1 FROM activity_log LIMIT 1');
        } catch(Exception $e) {
            $pdo->run('CREATE TABLE `activity_log` ( `id` INT NOT NULL AUTO_INCREMENT, `username` VARCHAR(30) NOT NULL, `action` text, `reg_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, PRIMARY KEY (`id`) )');
        }
        $username = $_SESSION['username'];
        $action = $username." Added SNF '".$snfsname."'";
        $query = "INSERT INTO activity_log (username, action) VALUES(?,?)";
        $activity_logs = $pdo->insert($query, [$username, $action]);
        $query = "INSERT INTO snfs (name) VALUES ('$snfsname') ";
        $result = mysqli_query($db, $query);
        echo json_encode($result);
    }

    if ($_REQUEST['action'] == 'insertDataUserSnf') {
        $userId	= $_POST['user_id'];
        $snfsID  = $_POST['snfList'];
        $user_name = $pdo->getResult("SELECT firstname, lastname FROM users where id = $userId");
        $firstname = $user_name[0]['firstname'];
        $lastname = $user_name[0]['lastname'];
        $hospital_name = $pdo->getResult("SELECT name FROM snfs WHERE id=$snfsID");
        try{
            $existChk = $pdo->getResult('SELECT 1 FROM activity_log LIMIT 1');
        } catch(Exception $e) {
            $pdo->run('CREATE TABLE `activity_log` ( `id` INT NOT NULL AUTO_INCREMENT, `username` VARCHAR(30) NOT NULL, `action` text, `reg_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, PRIMARY KEY (`id`) )');
        }
        $username = $_SESSION['username'];
        $action = $username." Assigned User is '".$firstname." ".$lastname."'"." to SNF is '".$hospital_name."'";
        $query = "INSERT INTO activity_log (username, action) VALUES(?,?)";
        $activity_logs = $pdo->insert($query, [$username, $action]);
        $checkQuery = $pdo->getResult("SELECT user_id FROM users_snfs WHERE user_id=$userId");
        $deleteArray = array();
        foreach ($checkQuery as $deletePreviousRecordAginstUserId){
            $deleteQuery = "DELETE FROM users_snfs WHERE user_id=$userId";
            $result = mysqli_query($db, $deleteQuery);
        }
        $snfID	= $_POST['snfList'];
        $commseperated = explode(",",$snfID);
        $count = count($commseperated);
        $array = array();
        foreach ($commseperated as $value) {
            $query = "INSERT INTO users_snfs (snf_id,user_id) VALUES ('$value','$userId') ";
            $result = mysqli_query($db, $query);
            array_push($array,$result);
        }
        echo json_encode([
            'success' => true,
            'message' => $result
        ]);
    }

    if ($_REQUEST['action'] == 'getPatientDetail') {
                $medicalrecord  = urldecode($_POST['medicalRecord']);
        $patientname  = urldecode($_POST['patientname']);
        // Add activity log
        $username = $_SESSION['username'];
        $action = $username." edit that name is '".$patientname."' and medical record is ".$medicalrecord;
        $query = "INSERT INTO activity_log (username, action) VALUES(?,?)";
        $activity_logs = $pdo->insert($query, [$username, $action]);
    }
}

function loadCodes() {
    $icd = $pdo->getResult("SELECT * FROM icd");
    echo json_encode($icd);
    return $icd;
}

?>
