<?php
header('Content-Type: application/json');

$host = 'localhost';
$dbname = 'ta_management';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT 
                                    ta_course.course_code,
                                    courses.course_name,
                                    SUM(ta_course.correcting_hours) AS marking_hours,
                                    SUM(ta_course.proctor_hours) AS proctoring_hours,
                                    SUM(ta_course.lab_hours) AS lab_hours
                                FROM ta_course
                                JOIN courses ON ta_course.course_code = courses.course_code
                                GROUP BY ta_course.course_code, courses.course_name");
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode(['data' => $result]);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
