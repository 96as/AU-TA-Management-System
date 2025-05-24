<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'database.php';

// Log the incoming POST data (for debugging)
// file_put_contents("log.txt", print_r($_POST, true), FILE_APPEND);

// Read values safely
$ta_id = $_POST['id'] ?? '';
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$year = $_POST['year'] ?? '';

if ($ta_id && $name && $email && $year) {
    // Make sure year is valid
    $allowed_years = ['Freshman', 'Sophomore', 'Junior', 'Senior'];
    if (!in_array($year, $allowed_years)) {
        echo "Invalid year value. Must be: " . implode(", ", $allowed_years);
        exit;
    }

    $defaultPassword = "123456";
    $pass_hash = password_hash($defaultPassword, PASSWORD_DEFAULT);
    $max_hours = 0;

    $stmt = $connection->prepare("INSERT INTO tas (ta_id, name, email, year, pass_hash, max_hours) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssi", $ta_id, $name, $email, $year, $pass_hash, $max_hours);

    if ($stmt->execute()) {
        echo "Success";
    } else {
        if (str_contains($stmt->error, 'Duplicate')) {
            echo "Error: TA ID or Email already exists.";
        } else {
            echo "SQL Error: " . $stmt->error;
        }
    }

    $stmt->close();
} else {
    echo "Missing data: ";
    echo "ta_id = $ta_id, name = $name, email = $email, year = $year";
}

$connection->close();
?>
