<?php
header('Content-Type: application/json');

$host = 'localhost';
$dbname = 'ta_management';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT total_assigned_hours FROM ta_course ORDER BY ta_name");
    $stmt->execute();
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $total_hours = array_column($row, 'total_assigned_hours');

    echo json_encode([
        'success' => true,
        'data' => $total_hours
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
