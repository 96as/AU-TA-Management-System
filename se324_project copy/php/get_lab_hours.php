<?php
$host = 'localhost';
$dbname = 'ta_management';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT SUM(lab_hours) FROM ta_course");
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $total_lab_hours = $row['SUM(lab_hours)'];

    echo json_encode([
        'success' => true,
        'total_lab_hours' => $total_lab_hours
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}      
?>
