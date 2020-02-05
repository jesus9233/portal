<?php
require_once __DIR__ . "/../../connection.php";
$alterUserTableQuery = "ALTER TABLE `users` CHANGE `Hospital` `Hospital` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL";
$db->query($alterUserTableQuery);
echo "Updated User Table";