<?php
$host = 'localhost';
$dbname = 'ta_management';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT ta_name, COUNT(course_code) AS total_courses, SUM(correcting_hours) AS marking_hours, SUM(proctor_hours) AS proctoring_hours, SUM(lab_hours) AS lab_hours FROM ta_course GROUP BY ta_name");
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['data' => $result]);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>

