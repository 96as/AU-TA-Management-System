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
    if (empty($data['ta_name']) || empty($data['course_code'])) {
        throw new Exception('TA name and course code are required');
    }

    // Use provided total_assigned_hours or default to 0
    $total_assigned_hours = isset($data['total_assigned_hours']) ? intval($data['total_assigned_hours']) : 0;
    error_log('Received total_assigned_hours: ' . $total_assigned_hours); // Debug log

    // Check if adding this assignment would exceed 15 hours for this TA
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->prepare("SELECT SUM(total_assigned_hours) as total_hours FROM ta_course WHERE ta_name = ?");
    $stmt->execute([$data['ta_name']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $current_total = $row && $row['total_hours'] ? intval($row['total_hours']) : 0;
    if (($current_total + $total_assigned_hours) > 15) {
        throw new Exception('This TA cannot be assigned more than 15 total hours across all courses.');
    }

    // Check if TA exists
    $stmt = $pdo->prepare("SELECT name FROM tas WHERE name = ?");
    $stmt->execute([$data['ta_name']]);
    if (!$stmt->fetch()) {
        throw new Exception('TA not found');
    }

    // Check if course exists in activecourses
    $stmt = $pdo->prepare("SELECT course_code FROM activecourses WHERE course_code = ?");
    $stmt->execute([$data['course_code']]);
    if (!$stmt->fetch()) {
        throw new Exception('Active course not found');
    }

    // Check if assignment already exists
    $stmt = $pdo->prepare("SELECT ta_name FROM ta_course WHERE ta_name = ? AND course_code = ?");
    $stmt->execute([$data['ta_name'], $data['course_code']]);
    if ($stmt->fetch()) {
        throw new Exception('TA is already assigned to this course');
    }

    // Prepare and execute insert query
    $stmt = $pdo->prepare("
        INSERT INTO ta_course (
            ta_name,
            course_code,
            total_assigned_hours,
            proctor_hours,
            correcting_hours,
            lab_hours
        ) VALUES (?, ?, ?, 0, 0, 0)
    ");

    $stmt->execute([
        $data['ta_name'],
        $data['course_code'],
        $total_assigned_hours
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'TA assignment added successfully'
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?> 