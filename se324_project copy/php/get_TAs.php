<?php
require 'database.php';
header('Content-Type: application/json');

$result = $connection->query("
  SELECT ta_id AS id, name, email, year
  FROM tas
");

$tas = [];
while ($row = $result->fetch_assoc()) {
    $tas[] = $row;
}

echo json_encode($tas);
$connection->close();
