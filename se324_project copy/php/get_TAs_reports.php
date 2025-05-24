<?php
header('Content-Type: application/json');

$host = 'localhost';
$dbname = 'ta_management';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $query = "SELECT ta_name FROM ta_course ORDER BY ta_name";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $ta_names = array_column($result, 'ta_name');
    
    echo json_encode(['ta_names' => $ta_names]);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?> 