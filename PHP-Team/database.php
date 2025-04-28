<?php

//Server, name, password from localhost(User Account Overview)
$db_server = "localhost";
$db_user = "root";
$db_password = "";
$db_name = "ta_management";


$conn = ""; //Connection Variable

//Establishing the connection to the database
try{
$conn = mysqli_connect($db_server, $db_user, $db_password, $db_name);
}
catch(mysqli_sql_exception){
    echo "Could not connect!";
}

//Testing Connection
if($conn) {
    
    echo "You are Connected!";
} else {
    echo "Connection Failed!";
}
?>
