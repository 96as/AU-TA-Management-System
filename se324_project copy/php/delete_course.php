<?php
header('Content-Type: application/json');

// Database connection parameters
$host = 'localhost';
$dbname = 'ta_management';
$username = 'root';
$password = '';

try {
     // Create database connection
     $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 
    // Get JSON data from request body
    $jsonData = file_get_contents('php://input');
    $data = json_decode($jsonData, true);
    
    if (!$data || !isset($data['courseCode'])) {
        throw new Exception('Invalid request data');
    }

    $courseCode = $data['courseCode'];

    // Delete from activecourses table
    $stmt = $pdo->prepare("DELETE FROM activecourses WHERE course_code = ?");
    $stmt->execute([$courseCode]);

    // Delete from ta_course table
    $stmt = $pdo->prepare("DELETE FROM ta_course WHERE course_code = ?");
    $stmt->execute([$courseCode]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Course deleted successfully'
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

?> 