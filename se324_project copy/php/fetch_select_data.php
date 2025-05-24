<?php
// Database connection parameters
$host = 'localhost';
$dbname = 'ta_management';
$username = 'root';
$password = '';

try {
    // Create database connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch data based on the requested type
    $type = isset($_GET['type']) ? $_GET['type'] : '';
    $data = array();

    switch($type) {
        case 'courses':
            $stmt = $pdo->query("SELECT course_code, course_name FROM courses ORDER BY course_code");
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $data[] = array(
                    'value' => $row['course_code'],
                    'text' => $row['course_code'] . ' - ' . $row['course_name']
                );
            }
            break;

        case 'instructors':
            $stmt = $pdo->query("SELECT instructor_id, name FROM instructors ORDER BY name");
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $data[] = array(
                    'value' => $row['instructor_id'],
                    'text' => $row['name']
                );
            }
            break;

        case 'tas':
            $stmt = $pdo->query("SELECT ta_id, name, year FROM tas ORDER BY name");
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $data[] = array(
                    'value' => $row['ta_id'],
                    'text' => $row['name']  
                );
            }
            break;
    }

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'data' => $data]);

} catch(PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?> 