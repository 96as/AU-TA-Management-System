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

    // Get course code from request
    $courseCode = $_GET['code'] ?? '';
    
    if (empty($courseCode)) {
        throw new Exception('Course code is required');
    }

    // Prepare and execute query
    $stmt = $pdo->prepare("SELECT course_code, course_name, course_year, course_type, terms_offered FROM courses WHERE course_code = ?");
    $stmt->execute([$courseCode]);
    $course = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($course) {
        echo json_encode([
            'success' => true,
            'data' => $course
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Course not found'
        ]);
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?> 