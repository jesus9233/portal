<?php
	require_once __DIR__ . "/../../connection.php";
	$tableExits = $pdo->getResult("SHOW tables like 'activity_log'");
	if (count($tableExits) == 0) {
	    //Means table is not created, create table first.
	    $createTableQuery = "CREATE TABLE activity_log (
	        id int NOT NULL AUTO_INCREMENT,
	        username VARCHAR(30) NOT NULL,
	        action text,
	        reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	        
	        PRIMARY KEY (id)
	    )";

	    $res = $db->query($createTableQuery);
	    if($res === TRUE) {
	        echo "Created activity_log table";
	    }
	    else {
	        echo "Error creating activity_log table";
	    }
	}
	else {
	    echo "Table already created Nothing to do";
	}
?>