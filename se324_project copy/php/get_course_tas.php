<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection parameters
$host = 'localhost';
$dbname = 'ta_management';
$username = 'root';
$password = '';

try {
    // Get course code from query parameter
    $courseCode = $_GET['course_code'] ?? '';
    if (empty($courseCode)) {
        throw new Exception('Course code is required');
    }

    // Create database connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch all TAs assigned to this course
    $stmt = $pdo->prepare("
        SELECT ta_name, total_assigned_hours 
        FROM ta_course 
        WHERE course_code = ?
    ");
    $stmt->execute([$courseCode]);
    $tas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'data' => $tas
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?> 