<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'database.php'; // Include the database connection file

// Get the form data
$id = $_POST['id'];
$name = $_POST['name'];
$username = $_POST['username'];
$password = $_POST['password'];

// Hash the password using password_hash
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$sql_insert = "INSERT INTO managers (manager_id, name, email, pass_hash) VALUES ('" . $id . "', '" . $name . "', '" . $username . "', '" . $hashed_password . "')";

// Execute the query
$result = $connection->query($sql_insert);

if ($result) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql_insert . "<br>" . $connection->error;
}

// Close the database connection
$connection->close();
?>