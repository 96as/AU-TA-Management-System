<?php
$host = 'localhost';
$dbname = 'ta_management';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM activecourses");
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);  
    $total_courses = $row['COUNT(*)'];

    echo json_encode([
        'success' => true,
        'total_courses' => $total_courses
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>