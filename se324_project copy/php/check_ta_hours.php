<?php
header('Content-Type: application/json');

$host = 'localhost';
$dbname = 'ta_management';
$username = 'root';
$password = '';

try {
    $jsonData = file_get_contents('php://input');
    $data = json_decode($jsonData, true);
    
    if (empty($data['ta_name']) || !isset($data['requested_hours'])) {
        throw new Exception('TA name and requested hours are required');
    }
    $requested_hours = intval($data['requested_hours']);
    
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->prepare("SELECT SUM(total_assigned_hours) as total_hours FROM ta_course WHERE ta_name = ?");
    $stmt->execute([$data['ta_name']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $current_total = $row && $row['total_hours'] ? intval($row['total_hours']) : 0;
    $hours_left = 15 - $current_total;
    
    if ($requested_hours > $hours_left) {
        echo json_encode([
            'success' => false,
            'hours_left' => $hours_left,
            'message' => 'This TA can only be assigned ' . $hours_left . ' more hour(s).'
        ]);
        exit;
    }
    
    echo json_encode([
        'success' => true,
        'hours_left' => $hours_left
    ]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} 