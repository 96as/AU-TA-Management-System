<?php

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost';
$dbname = 'ta_management';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1. Active courses count
    $stmt = $pdo->query("SELECT COUNT(*) FROM activecourses WHERE is_active = 1");
    $courses_count = (int)$stmt->fetchColumn();

    // 2. Unique instructors in activecourses
    $stmt = $pdo->query("SELECT COUNT(DISTINCT instructor) FROM activecourses WHERE is_active = 1");
    $instructors_count = (int)$stmt->fetchColumn();

    // 3. Total TAs
    $stmt = $pdo->query("SELECT COUNT(*) FROM tas");
    $tas_count = (int)$stmt->fetchColumn();

    // 4. Total TA hours
    $stmt = $pdo->query("SELECT SUM(total_assigned_hours) FROM ta_course");
    $total_ta_hours = (int)$stmt->fetchColumn();

    echo json_encode([
        'success' => true,
        'courses_count' => $courses_count,
        'instructors_count' => $instructors_count,
        'tas_count' => $tas_count,
        'total_ta_hours' => $total_ta_hours
    ]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} 