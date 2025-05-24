<?php
// HTML-Team/TaMain.php

session_start();

// 1) Redirect if not logged in
if (empty($_SESSION['ta_id']) || empty($_SESSION['ta_name'])) {
    header('Location: Login.php');
    exit;
}

// 2) Bring in your DB connection
require __DIR__ . '/../database.php';

// Check if connection is successful
if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 3) Fetch assigned courses count
$assignedCount = 0;
$sql = "SELECT COUNT(*) FROM TA_Course WHERE ta_name = ?";
if ($stmt = $connection->prepare($sql)) {
    $stmt->bind_param("s", $_SESSION['ta_name']);
    $stmt->execute();
    $stmt->bind_result($assignedCount);
    $stmt->fetch();
    $stmt->close();
} else {
    die("Prepare failed: " . $connection->error);
}

$totalHours = 0;
$sql = "SELECT COALESCE(SUM(total_assigned_hours),0) FROM ta_Course WHERE ta_name = ?";
if ($stmt = $connection->prepare($sql)) {
    $stmt->bind_param("s", $_SESSION['ta_name']);
    $stmt->execute();
    $stmt->bind_result($totalHours);
    $stmt->fetch();
    $stmt->close();
} else {
    die("Prepare failed: " . $connection->error);
}

$markingHours = 0;
$sql = "SELECT COALESCE(SUM(correcting_hours),0) FROM ta_Course WHERE ta_name = ?";
if ($stmt = $connection->prepare($sql)) {
    $stmt->bind_param("s", $_SESSION['ta_name']);
    $stmt->execute();
    $stmt->bind_result($markingHours);
    $stmt->fetch();
    $stmt->close();
} else {
    die("Prepare failed: " . $connection->error);
}

$labHours = 0;
$sql = "SELECT COALESCE(SUM(lab_hours),0) FROM ta_Course WHERE ta_name = ?";
if ($stmt = $connection->prepare($sql)) {
    $stmt->bind_param("s", $_SESSION['ta_name']);
    $stmt->execute();
    $stmt->bind_result($labHours);
    $stmt->fetch();
    $stmt->close();
} else {
    die("Prepare failed: " . $connection->error);
}

$procHours = 0;
$sql = "SELECT COALESCE(SUM(proctor_hours),0) FROM ta_Course WHERE ta_name = ?";
if ($stmt = $connection->prepare($sql)) {
    $stmt->bind_param("s", $_SESSION['ta_name']);
    $stmt->execute();
    $stmt->bind_result($procHours);
    $stmt->fetch();
    $stmt->close();
} else {
    die("Prepare failed: " . $connection->error);
}

// Debug: Check if tables exist
$tables = ['ta_course', 'activecourses', 'courses', 'tas', 'instructors'];
foreach ($tables as $table) {
    $result = $connection->query("SHOW TABLES LIKE '$table'");
    if ($result->num_rows == 0) {
        die("Table '$table' does not exist");
    }
}

// Debug: Check TA data
$result = $connection->query("SELECT * FROM tas WHERE ta_id = " . intval($_SESSION['ta_id']));
if ($result->num_rows == 0) {
    die("TA with ID " . $_SESSION['ta_id'] . " not found in tas table");
}
$taData = $result->fetch_assoc();

// Debug: Check ta_course data
$result = $connection->query("SELECT * FROM ta_course WHERE ta_name = '" . $connection->real_escape_string($_SESSION['ta_name']) . "'");
if ($result->num_rows == 0) {
    die("No courses found for TA " . $_SESSION['ta_name'] . " in ta_course table");
}

// Fetch all courses for the TA
$courses = [];
$stmt = $connection->prepare("
  SELECT 
    c.course_name,
    tc.course_code,
    c.terms_offered,
    ac.num_students,
    tc.total_assigned_hours,
    tc.correcting_hours,
    tc.proctor_hours,
    tc.lab_hours,
    i.name as instructor_name,
    ac.instructor as instructor_id
  FROM ta_course AS tc
  JOIN courses AS c ON tc.course_code = c.course_code
  LEFT JOIN activecourses AS ac ON tc.course_code = ac.course_code
  LEFT JOIN instructors AS i ON ac.instructor = i.instructor_id
  WHERE tc.ta_name = ?
");

if (!$stmt) {
    die("Prepare failed: " . $connection->error);
}

$stmt->bind_param("s", $_SESSION['ta_name']);
$stmt->execute();
$result = $stmt->get_result();

// Debug: Print the query results
error_log("Query results for TA: " . $_SESSION['ta_name']);
while ($row = $result->fetch_assoc()) {
    error_log(print_r($row, true));
    $courses[] = $row;
}
$stmt->close();

// Everything for Course Card 1
$courseName = '';

$stmt = $connection->prepare("
  SELECT c.course_name
  FROM ta_course AS tc
  JOIN courses AS c ON tc.course_code = c.course_code
  WHERE tc.ta_name = ?
  LIMIT 1
");

if (!$stmt) {
    die("Prepare failed: " . $connection->error);
}

$stmt->bind_param("s", $_SESSION['ta_name']);
$stmt->execute();
$stmt->bind_result($courseName);
$stmt->fetch();
$stmt->close();

$courseCode = '';
$sql = "SELECT course_code FROM ta_course WHERE ta_name = ?";
if ($stmt = $connection->prepare($sql)) {
    $stmt->bind_param("s", $_SESSION['ta_name']);
    $stmt->execute();
    $stmt->bind_result($courseCode);
    $stmt->fetch();
    $stmt->close();
} else {
    die("Prepare failed: " . $connection->error);
}

$courseSem = '';

$stmt = $connection->prepare("
  SELECT c.terms_offered
  FROM courses AS c
  JOIN ta_course AS tc ON tc.course_code = c.course_code
  WHERE tc.ta_name = ?
  LIMIT 1
");

if (!$stmt) {
    die("Prepare failed: " . $connection->error);
}

$stmt->bind_param("s", $_SESSION['ta_name']);
$stmt->execute();
$stmt->bind_result($courseSem);
$stmt->fetch();
$stmt->close();

$numStud = 0;

$stmt = $connection->prepare("
  SELECT ac.num_students
  FROM activecourses AS ac
  JOIN ta_course AS tc ON tc.course_code = ac.course_code
  WHERE tc.ta_name = ?
  LIMIT 1
");

if (!$stmt) {
    die("Prepare failed: " . $connection->error);
}

$stmt->bind_param("s", $_SESSION['ta_name']);
$stmt->execute();
$stmt->bind_result($numStud);
$stmt->fetch();
$stmt->close();

$numHours = 0;
$stmt = $connection->prepare("
  SELECT total_assigned_hours
  FROM ta_course WHERE ta_name = ?
  LIMIT 1
");

$stmt->bind_param("s", $_SESSION['ta_name']);
$stmt->execute();
$stmt->bind_result($numHours);
$stmt->fetch();
$stmt->close();

$markingHoursCard = 0;
$stmt = $connection->prepare("
  SELECT correcting_hours
  FROM ta_course WHERE ta_name = ?
  LIMIT 1
");

$stmt->bind_param("s", $_SESSION['ta_name']);
$stmt->execute();
$stmt->bind_result($markingHoursCard);
$stmt->fetch();
$stmt->close();

$procHoursCard = 0;
$stmt = $connection->prepare("
  SELECT proctor_hours
  FROM ta_course WHERE ta_name = ?
  LIMIT 1
");

$stmt->bind_param("s", $_SESSION['ta_name']);
$stmt->execute();
$stmt->bind_result($procHoursCard);
$stmt->fetch();
$stmt->close();

$LabHoursCard = 0;
$stmt = $connection->prepare("
  SELECT lab_hours
  FROM ta_course WHERE ta_name = ?
  LIMIT 1
");

$stmt->bind_param("s", $_SESSION['ta_name']);
$stmt->execute();
$stmt->bind_result($LabHoursCard);
$stmt->fetch();
$stmt->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TA Management System - TA Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="../../css/TaMain.css">
</head>
<body>
<div class="app-wrap">
  <!-- Sidebar / Drawer -->
  <div class="drawer drawer-rail" id="drawer">
    <button class="drawer-toggle-btn" id="drawerToggle">
      <i class="fas fa-chevron-right"></i>
    </button>
    <div class="drawer-content">
      <div class="logo-container">
        <img src="/api/placeholder/120/30" alt="University Logo" class="logo">
        <span class="only-full">TA Management</span>
      </div>
      <div class="divider divider-bottom-margin"></div>
      <a href="#" class="nav-item">
        <div class="nav-prepend">
          <i class="fas fa-home"></i>
        </div>
        <span class="only-full">Dashboard</span>
      </a>
      <a href="#" class="nav-item active">
        <div class="nav-prepend">
          <i class="fas fa-book"></i>
        </div>
        <span class="only-full">My Courses</span>
      </a>
      <a href="#" class="nav-item">
        <div class="nav-prepend">
          <i class="fas fa-calendar"></i>
        </div>
        <span class="only-full">Schedule</span>
      </a>
      <a href="#" class="nav-item">
        <div class="nav-prepend">
          <i class="fas fa-clock"></i>
        </div>
        <span class="only-full">Hours Log</span>
      </a>
      <div class="divider"></div>
      <a href="#" class="nav-item">
        <div class="nav-prepend">
          <i class="fas fa-cog"></i>
        </div>
        <span class="only-full">Settings</span>
      </a>
      <a href="Login.php?logout" class="nav-item logout">
        <div class="nav-prepend">
          <i class="fas fa-sign-out-alt"></i>
        </div>
        <span class="only-full">Logout</span>
      </a>
    </div>
  </div>

  <!-- Main Content -->
  <div class="app-inner">
    <div class="main">
      <div class="main-content" id="mainContent">
        <div class="page-header">
          <h1>TA Dashboard</h1>
          <ul class="breadcrumbs">
            <li><a href="#">Home</a></li>
            <li>></li>
            <li>My Courses</li>
          </ul>
        </div>

        <!-- Summary Panel -->
        <div class="summary-panel">
            <div class="summary-item">
              <div class="summary-value"><?= htmlspecialchars($assignedCount) ?></div>
              <div class="summary-label">Assigned Courses</div>
            </div>
            <div class="summary-item">
              <div class="summary-value"><?= htmlspecialchars($totalHours) ?></div>
              <div class="summary-label">Total Hours</div>
            </div>
            <div class="summary-item">
              <div class="summary-value"><?= htmlspecialchars($markingHours) ?></div>
              <div class="summary-label">Marking Hours</div>
            </div>
            <div class="summary-item">
              <div class="summary-value"><?= htmlspecialchars($labHours) ?></div>
              <div class="summary-label">Lab Supervision</div>
            </div>
            <div class="summary-item">
              <div class="summary-value"><?= htmlspecialchars($procHours) ?></div>
              <div class="summary-label">Proctoring</div>
            </div>
          </div>


        <h2>My Courses</h2>

        <div class="cards">
          <?php if (empty($courses)): ?>
            <div class="text-sub" id="noCoursesMessage" style="text-align: center; margin-top: 110px;">
              No courses assigned yet.
            </div>
          <?php else: ?>
            <?php foreach ($courses as $course): ?>
              <div class="card">
                <div class="card-title">
                  <div class="d-col">
                    <div class="text-lg"><?= htmlspecialchars($course['course_name']) ?></div>
                    <div class="text-sub">
                      <?= htmlspecialchars($course['course_code']) ?> | 
                      Instructor: <?= htmlspecialchars($course['instructor_name'] ?? 'Not Assigned') ?>
                    </div>
                  </div>
                  <span class="semester-badge">Current</span>
                </div>
                <div class="tags">
                  <div class="tag">
                    <span><?= htmlspecialchars($course['num_students'] ?? 0) ?></span> Students
                  </div>
                  <div class="tag-purple">
                    <i class="fas fa-clock icon-purple"></i>
                    <span><?= htmlspecialchars($course['total_assigned_hours']) ?></span>&nbsp;Hours
                  </div>
                </div>

                <div class="task-list">
                  <div class="task-item">
                    <div class="task-name">
                      <i class="fas fa-clipboard-check task-icon"></i>
                      Marking
                    </div>
                    <div class="task-hours">
                      <span><?= htmlspecialchars($course['correcting_hours']) ?></span> hours
                    </div>
                  </div>
                  <div class="task-item">
                    <div class="task-name">
                      <i class="fas fa-eye task-icon"></i>
                      Proctoring
                    </div>
                    <div class="task-hours">
                      <span><?= htmlspecialchars($course['proctor_hours']) ?></span> hours
                    </div>
                  </div>
                  <div class="task-item">
                    <div class="task-name">
                      <i class="fas fa-chalkboard-teacher task-icon"></i>
                      Lab Supervision
                    </div>
                    <div class="task-hours">
                      <span><?= htmlspecialchars($course['lab_hours']) ?></span> hours
                    </div>
                  </div>
                </div>

                

                
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

  <!-- Your dashboard JS -->
  <script src="../../js/TaMain.js" defer></script>
  <script src="../../js/drawer.js" defer></script>
</body>
</html>
