<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost';
$dbname = 'ta_management';
$username = 'root';
$password = '';

try {
    $jsonData = file_get_contents('php://input');
    $data = json_decode($jsonData, true);

    if (empty($data['ta_name']) || empty($data['course_code'])) {
        throw new Exception('TA name and course code are required');
    }

    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("DELETE FROM ta_course WHERE ta_name = ? AND course_code = ?");
    $stmt->execute([$data['ta_name'], $data['course_code']]);

    echo json_encode(['success' => true, 'message' => 'TA removed successfully']);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} 