<?php
require __DIR__ . '/../database.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

// Get and validate input
$course_code = $_POST['course_code'] ?? '';
$ta_name = $_POST['ta_name'] ?? '';
$proctor_hours = (int)($_POST['proctor_hours'] ?? 0);
$correcting_hours = (int)($_POST['correcting_hours'] ?? 0);
$lab_hours = (int)($_POST['lab_hours'] ?? 0);
$total_assigned_hours = (int)($_POST['total_assigned_hours'] ?? 0);

// Validate required fields
if (!$course_code || !$ta_name) {
    echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    exit;
}

// Validate hours are non-negative
if ($proctor_hours < 0 || $correcting_hours < 0 || $lab_hours < 0) {
    echo json_encode(['success' => false, 'error' => 'Hours cannot be negative']);
    exit;
}

// Calculate total task hours
$total_task_hours = $proctor_hours + $correcting_hours + $lab_hours;

// Validate total task hours don't exceed assigned hours
if ($total_task_hours > $total_assigned_hours) {
    echo json_encode([
        'success' => false, 
        'error' => "Total task hours ($total_task_hours) cannot exceed assigned hours ($total_assigned_hours)"
    ]);
    exit;
}

try {
    // Update the ta_course table
    $stmt = $connection->prepare("
        UPDATE ta_course 
        SET proctor_hours = ?, 
            correcting_hours = ?, 
            lab_hours = ?
        WHERE course_code = ? AND ta_name = ?
    ");

    $stmt->bind_param("iiiss", 
        $proctor_hours, 
        $correcting_hours, 
        $lab_hours,
        $course_code, 
        $ta_name
    );

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Tasks updated successfully',
            'data' => [
                'proctor_hours' => $proctor_hours,
                'correcting_hours' => $correcting_hours,
                'lab_hours' => $lab_hours,
                'total_assigned_hours' => $total_assigned_hours
            ]
        ]);
    } else {
        throw new Exception($stmt->error);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Database error: ' . $e->getMessage()
    ]);
}

$stmt->close();
$connection->close(); 