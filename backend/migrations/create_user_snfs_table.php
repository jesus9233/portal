<?php
require_once __DIR__ . "/../../connection.php";
$tableExits = $pdo->getResult("SHOW tables like 'users_snfs'");
if (count($tableExits) == 0) {
    //Means table is not created, create table first.
    $createTableQuery = "CREATE TABLE users_snfs (
        id int NOT NULL AUTO_INCREMENT,
        snf_id int,
        user_id int,
        PRIMARY KEY (id)
    )";


    $res = $db->query($createTableQuery);
    if($res === TRUE) {
        echo "Created users snfs table";
    }
    else {
        echo "Error creating users snfs table";
    }
}
else {
    echo "Table already created Nothing to do";
}