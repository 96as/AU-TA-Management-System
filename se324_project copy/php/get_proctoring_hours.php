<?php
$host = 'localhost';
$dbname = 'ta_management';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT SUM(proctor_hours) FROM ta_course");
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $total_proctoring_hours = $row['SUM(proctor_hours)'];

    echo json_encode([
        'success' => true,
        'total_proctoring_hours' => $total_proctoring_hours
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}      
?>
