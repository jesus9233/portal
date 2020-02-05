<?php
require_once __DIR__ . "/../../connection.php";
$tableExits = $pdo->getResult("SHOW tables like 'roles'");
if (count($tableExits) == 0) {
    //Means table is not created, create table first.
    $createTableQuery = "CREATE TABLE roles (
        id int NOT NULL AUTO_INCREMENT,
        role_name varchar(255) NOT NULL,
        PRIMARY KEY (id)
    )";
    $res = $db->query($createTableQuery);
    if($res === TRUE) {
        echo "Role Table created";
        //Add role_id collumn in users table
        $alterUserTable = "ALTER TABLE users ADD role_id int";
        $res = $db->query($alterUserTable);
        if ($res === TRUE) {
            echo "Added role_id in user table, creating Relationship...";
            $alterUserTable = "ALTER TABLE users ADD CONSTRAINT fk_user_role_id FOREIGN KEY(role_id) REFERENCES roles(id)";
            try {
                $res = $db->query($alterUserTable);
                if ($res === TRUE) {
                    echo "Added Foreign key constraint to user.";
                } else {
                    echo "Error Adding Foreign Key constraint to user table.";
                }
            } catch (Exception $e) {
                echo "Exception: " . $e;
            }
        }
    }
    //Insert data to roles table
    $insertRoleTableQuery = "INSERT INTO roles(role_name) VALUES('admin')";
    $db->query($insertRoleTableQuery);
    $admin_role_id = $db->insert_id;
    $insertRoleTableQuery = "INSERT INTO roles(role_name) VALUES('patient')";
    $db->query($insertRoleTableQuery);
    $patient_role_id = $db->insert_id;
    //Update user table and Assign admin role to adrianvfx@gmail.com
    //Rest users will have role of patient
    $updateAdminUserRole = "UPDATE users SET role_id = $admin_role_id WHERE email = 'adrianvfx@gmail.com'";
    $db->query($updateAdminUserRole);
    echo "Assigned admin role for email adrianvfx@gmail.com";
    // $updatePatientUserRole = "UPDATE users SET role_id = $patient_role_id WHERE email <> 'adrianvfx@gmail.com'";
    // $db->query($updatePatientUserRole);
    // echo "Assigned patient role for all emails except adrianvfx@gmail.com";
}
else {
    echo "Nothing to do";
}