<?php

//Server, name, password from localhost(User Account Overview)
$db_server = "localhost";
$db_user = "root";
$db_password = "";
$db_name = "ta_management";

$connection = null; //Connection Variable

//Establishing the connection to the database
try {
    $connection = mysqli_connect($db_server, $db_user, $db_password, $db_name);
    if (!$connection) {
        throw new Exception("Connection failed");
}
} catch (Exception $e) {
    error_log("Database connection error: " . $e->getMessage());
    http_response_code(500);
    die("Database connection failed");
}
?>
