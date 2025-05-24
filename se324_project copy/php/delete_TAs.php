<?php
require 'database.php';
header('Content-Type: text/plain');

// For debugging, log the incoming POST
file_put_contents("delete_log.txt", print_r($_POST, true), FILE_APPEND);

$ids = $_POST['ids'] ?? '';
if (trim($ids) === '') {
    http_response_code(400);
    echo "No IDs provided";
    exit;
}

// sanitize & parse
$arr = array_filter(array_map('intval', explode(',', $ids)));
if (empty($arr)) {
    http_response_code(400);
    echo "No valid IDs";
    exit;
}
$list = implode(',', $arr);

try {
// First check if any of these TAs have course assignments
$delete_ta_course_sql = "
    DELETE FROM ta_course 
    WHERE ta_name IN (
        SELECT name FROM tas WHERE ta_id IN ($list)
    )
";

// Then, delete from tas
$delete_tas_sql = "
    DELETE FROM tas 
    WHERE ta_id IN ($list)
";
$connection->query($delete_ta_course_sql);
$remove_course_assignments = $connection->affected_rows;

$connection->query($delete_tas_sql);
$remove_tas = $connection->affected_rows;

$connection->commit();
echo "Success";

} catch (Exception $e) {
    $connection->rollback();
    http_response_code(500);
    echo "Delete failed: " . $e->getMessage();
}

$connection->close();