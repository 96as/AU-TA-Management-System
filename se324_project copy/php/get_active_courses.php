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
    // Create database connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch active courses with instructor names, course details, and TA information
    $stmt = $pdo->query("
        SELECT 
            ac.*,
            i.name as instructor_name,
            c.course_name,
            c.course_year,
            c.course_type,
            c.terms_offered,
            tc.ta_name
        FROM activecourses ac 
        LEFT JOIN instructors i ON ac.instructor = i.instructor_id 
        LEFT JOIN courses c ON ac.course_code = c.course_code
        LEFT JOIN ta_course tc ON ac.course_code = tc.course_code
        WHERE ac.is_active = 1
        ORDER BY ac.course_code
    ");
    
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Log the number of courses found
    error_log('Number of active courses found: ' . count($courses));

    echo json_encode([
        'success' => true,
        'data' => $courses
    ]);

} catch (Exception $e) {
    error_log('Error in get_active_courses.php: ' . $e->getMessage());
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?> 