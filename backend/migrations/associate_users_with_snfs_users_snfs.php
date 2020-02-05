<?php
require_once __DIR__ . "/../../connection.php";

echo "Compare Snf and User Hospitals and Where Name do not match OR Missing then Insert record .... <br>";
$users_hospitals = $pdo->getResult("SELECT * FROM users_hospitals");
$data = array();
foreach($users_hospitals as $hospital)
{
    $hospitalName = $hospital['hospital_name'];
    $checkSnfsExitOrNot = $pdo->getResult("SELECT * FROM snfs WHERE name='$hospitalName'");
    if(count($checkSnfsExitOrNot) == 0) {
        $insertSnfsTable = $pdo->insert("INSERT into snfs (name) VALUES('$hospitalName')");
        array_push($data ,$insertSnfsTable);
    } if(count($checkSnfsExitOrNot) == 1) {
        echo "Already Inserted";
    }
}
if(count($data) > 0){
    echo "Missing Records Inserted";
}

echo "Get Snf Id From Users Table With User Id .... <br>";
$userData = $pdo->getResult("SELECT * FROM users");
if(count($userData) > 0)
{
    $userQuery = "SELECT * from users";
    $userRecord = $pdo->getResult($userQuery);
    foreach ($userRecord as $key => $value) {
        if($value['snf_id'] != null) {
            $checkSnfAndUser = "SELECT * FROM users_snfs WHERE user_id = " .$value['id']. " AND snf_id = ".$value['snf_id']."";
            $data = $pdo->getResult($checkSnfAndUser);
            if(count($data) == 0)
            {
                $insertSnfQuery = "INSERT into users_snfs(user_id,snf_id) VALUES(".$value['id'].", ".$value['snf_id'].")";
                $result = $pdo->insert($insertSnfQuery);
                echo "Inserted";
            }
            else {
                echo "Data Already inserted";
            }
        }
    }
} else {
    echo "Error Is Created In Users Table .... <br>";
}