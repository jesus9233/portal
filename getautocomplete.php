<?php
require 'connection.php';
session_start();

if (isset($_GET['st'])) {
    $str = $_GET['st'];

    $sql = "SELECT icd_code FROM icd WHERE icd_code LIKE '%{$str}%' ORDER BY icd_code";

    $result = mysqli_query($db, $sql);

    $array = array();

    while ($row = mysqli_fetch_assoc($result)) {
        // $array[] = $row['icd_code'] . ',' . $row['icd_desc'];
         $array[] = $row['icd_code'];
    }
    echo json_encode($array);
}

if (isset($_GET['snfs'])) {
    $str = $_GET['snfs'];

    $sql=" SELECT * FROM snfs WHERE name like '%".$str."%'";
    $result = mysqli_query($db, $sql);

    $array = array();

    while ($row = mysqli_fetch_assoc($result)) {
        // $array[] = $row['icd_code'] . ',' . $row['icd_desc'];
         $array[] = $row['name'];
    }
    echo json_encode($array);
}

if (isset($_GET['getSnfAgainstUserID'])) {
    $userId = $_GET['user_id'];
    $getUserSnf = $pdo->getResult("SELECT * FROM users_snfs WHERE user_id='$userId'");
    $arraySnf = array();
    foreach($getUserSnf as $getsnf)
    {
        $snf_id = $getsnf['snf_id'];
        $getsnfs = $pdo->getResult("SELECT * FROM snfs WHERE id=$snf_id");
        array_push($arraySnf,$getsnfs);
    }
    echo json_encode([
        'getUserSnf' => $arraySnf
    ]);
}

if (isset($_GET['snfId'])) {
    $str = $_GET['itemID'];
    $userId = $_GET['user_id'];
    if($userId)
    {
        $sql=$pdo->getResult("SELECT id , name FROM snfs WHERE name='$str'");
        $snfId = $sql;
        $snf_id = $sql[0]['id'];
        $checkSql = $pdo->getResult("SELECT snf_id,user_id FROM users_snfs WHERE snf_id='$snf_id' AND user_id='$userId'");
        echo json_encode([
            'snfId' => $snfId,
            'checkSnfAssign' => $checkSql
        ]);
    } else {
        $userselected = 'select user first';
        echo json_encode([
            'success' => false,
            'message' =>$userselected
        ]);
    }
}

if (isset($_GET['std'])) {
    $strs = $_GET['std'];

    $sql = "SELECT icd_code, icd_desc FROM icd WHERE icd_desc LIKE '%{$strs}%' ORDER BY icd_code";

    $result = mysqli_query($db, $sql);

    $array = array();

    while ($row = mysqli_fetch_assoc($result)) {
      $array[] = $row['icd_code'] . ':' . $row['icd_desc'];
    }
    echo json_encode($array);
}

if (isset($_POST['itemID'])) {
    $var1 = $_POST['itemID'];
    $str = $_POST['itemID'];
    $icd = $pdo->getResult("SELECT icd_code, cat_id FROM icd WHERE icd_code = '$str'");
    echo json_encode($icd);
}
if (isset($_POST['itemID2'])) {
    $var1 = $_POST['itemID2'];
    $str = $_POST['itemID2'];
    $icd = $pdo->getResult("SELECT * FROM icd WHERE icd_code ='$str'");
    echo json_encode($icd);
}

if (isset($_POST['addCode'])) {
    $code = $_POST['addCode'];
    $username = $_SESSION['username'];
    $action = $username." added Code '".$code."'";
    $query = "INSERT INTO activity_log (username, action) VALUES(?,?)";
    $add_code = $pdo->insert($query, [$username, $action]);
    echo json_encode($add_code);
}

if (isset($_POST['removeCode'])) {
    $code = $_POST['removeCode'];
    $username = $_SESSION['username'];
    $action = $username." removed Code '".$code."'";
    $query = "INSERT INTO activity_log (username, action) VALUES(?,?)";
    $add_code = $pdo->insert($query, [$username, $action]);
    echo json_encode($add_code);
}
