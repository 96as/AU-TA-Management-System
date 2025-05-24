<?php
require 'database.php';
header('Content-Type: application/json');

try {
    // Test the connection
    if (!$connection) {
        throw new Exception("Database connection failed");
    }

    // Query the tas table
    $result = $connection->query("SELECT * FROM tas");
    
    if (!$result) {
        throw new Exception("Query failed: " . $connection->error);
    }

    $tas = [];
    while ($row = $result->fetch_assoc()) {
        $tas[] = $row;
    }

    echo json_encode([
        'status' => 'success',
        'count' => count($tas),
        'data' => $tas
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}

$connection->close();
?> 