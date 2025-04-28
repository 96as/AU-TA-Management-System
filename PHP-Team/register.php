<?php
require 'database.php'; // Include the database connection file

// // Get the form data
$id = $_POST['id'];
$name = $_POST['name'];
$username = $_POST['username'];
$password = $_POST['password'];


// Hash the password using password_hash
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$sql_insert = "INSERT INTO managers (manager_id, name, email, pass_hash) VALUES (' " . $_POST['id'] . " ', '" . $_POST['name'] . "', '" . $_POST['username'] . "', '" . $hashed_password . " ')";
// Execute the query
$managers = $conn->query($sql_insert);

if ($managers) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql_insert . "<br>" . $conn->error;
}
// Close the database connection
$conn->close();


?>
