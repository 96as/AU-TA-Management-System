<?php
// Ensure no whitespace or other output before headers
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection parameters
$host = 'localhost';
$dbname = 'ta_management';
$username = 'root';
$password = '';

try {
    // Get JSON data from request body
    $jsonData = file_get_contents('php://input');
    $data = json_decode($jsonData, true);
    
    if (!$data) {
        throw new Exception('Invalid request data: ' . json_last_error_msg());
    }

    // Required fields validation
    if (empty($data['course_code']) || empty($data['instructor'])) {
        throw new Exception('Course code and instructor are required');
    }

    // Create database connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if course already exists in activecourses
    $stmt = $pdo->prepare("SELECT course_code FROM activecourses WHERE course_code = ?");
    $stmt->execute([$data['course_code']]);
    if ($stmt->fetch()) {
        throw new Exception('Course is already active');
    }

    // Prepare and execute insert query
    $stmt = $pdo->prepare("
        INSERT INTO activecourses (
            course_code, 
            instructor, 
            semester, 
            num_students, 
            num_sections, 
            is_active
        ) VALUES (?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $data['course_code'],
        $data['instructor'],
        $data['semester'],
        $data['num_students'],
        $data['num_sections'],
        $data['is_active']
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Active course added successfully'
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?> 