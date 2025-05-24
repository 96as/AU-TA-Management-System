<?php
session_start();
if (empty($_SESSION['instructor_id']) || empty($_SESSION['instructor_name'])) {
    header('Location: Login.php');
    exit;
}
require __DIR__ . '/../database.php';

// Fetch all active courses for this instructor
$courses = [];
$stmt = $connection->prepare("
    SELECT ac.course_code, c.course_name, ac.semester, ac.num_students, ac.num_sections, ac.is_active
    FROM activecourses ac
    JOIN courses c ON ac.course_code = c.course_code
    WHERE ac.instructor = ?
");
$stmt->bind_param("i", $_SESSION['instructor_id']);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    // Fetch TAs for this course
    $tas = [];
    $ta_stmt = $connection->prepare("
        SELECT tc.ta_name, tc.total_assigned_hours, tc.correcting_hours, tc.proctor_hours, tc.lab_hours
        FROM ta_course tc
        WHERE tc.course_code = ?
    ");
    $ta_stmt->bind_param("s", $row['course_code']);
    $ta_stmt->execute();
    $ta_result = $ta_stmt->get_result();
    while ($ta_row = $ta_result->fetch_assoc()) {
        $tas[] = $ta_row;
    }
    $ta_stmt->close();
    $row['tas'] = $tas;
    $courses[] = $row;
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TA Management System - Instructor Courses</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="../../css/InstructorCourses.css">

    <!--  start of footer reference added by abdullah --> <link rel="stylesheet" href="../../css/footerstyle.css">  <!--  end of footer reference added by abdullah -->


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
        <img src="../../images/logo.png" alt="University Logo" class="logo">
        <span class="only-full">TA Management</span>
      </div>
      <div class="divider divider-bottom-margin"></div>
      <a href="InstructorMain.php" class="nav-item active">
        <div class="nav-prepend">
          <i class="fas fa-home"></i>
        </div>
        <span class="only-full">Dashboard</span>
      </a>

      <a href="InstructorCourses.php" class="nav-item">
        <div class="nav-prepend">
          <i class="fas fa-book"></i>
        </div>
        <span class="only-full">Courses</span>
      </a>

      <a href="InstructorAssignTa.php" class="nav-item">
        <div class="nav-prepend">
          <i class="fas fa-users"></i>
        </div>
        <span class="only-full">TA Management</span>
      </a>

      <a href="../../html/Instructor_Reports_page.php" class="nav-item">
      <div class="nav-prepend">
          <i class="fas fa-chart-bar"></i>
        </div>
        <span class="only-full">Reports</span>
      </a>

      <div class="divider"></div>

      <a href="../../html/profile.html" class="nav-item">
        <div class="nav-prepend">
          <i class="mdi mdi-account-circle"></i>
        </div>
        <span class="only-full">Profile</span>
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
          <h1>Instructor Courses</h1>
          <ul class="breadcrumbs">
            <li><a href="InstructorMain.php">Home</a></li>
            <li>></li>
            <li>My Courses</li>
          </ul>
        </div>
        <!-- Summary Panel -->
        <div class="summary-panel">
          <div class="summary-item">
            <div class="summary-value"><?= count($courses) ?></div>
            <div class="summary-label">Total Courses</div>
          </div>
          <div class="summary-item">
            <div class="summary-value"><?php
              $totalTAs = 0;
              foreach ($courses as $course) $totalTAs += count($course['tas']);
              echo $totalTAs;
            ?></div>
            <div class="summary-label">Assigned TAs</div>
          </div>
          <div class="summary-item">
            <div class="summary-value"><?php
              $totalTAHours = 0;
              foreach (
                $courses as $course) {
                foreach ($course['tas'] as $ta) $totalTAHours += $ta['total_assigned_hours'];
              }
              echo $totalTAHours;
            ?></div>
            <div class="summary-label">Total TA Hours</div>
          </div>
          <div class="summary-item">
            <div class="summary-value"><?php
              $totalDist = 0;
              foreach ($courses as $course) {
                foreach ($course['tas'] as $ta) $totalDist += $ta['correcting_hours'] + $ta['proctor_hours'] + $ta['lab_hours'];
              }
              echo $totalDist;
            ?></div>
            <div class="summary-label">Total Hours of Distributed Tasks</div>
          </div>
        </div>
        <div style="display: flex; flex-direction: row; justify-content: space-between; margin-bottom: 30px;"> <h2>My Courses</h2> <div class="btn-details" style="width: 132px;" onclick="window.location.href='InstructorAssignTa.php'">Manage TAs</div> </div>
        <div class="text-sub" id="noCoursesMessage" style="text-align: center; margin-top: 110px; <?= empty($courses) ? '' : 'display: none;' ?>;">
          No courses assigned yet.
        </div>
        <div class="cards" style="display: flex; flex-wrap: wrap;">
          <?php foreach ($courses as $course): ?>
            <div class="card" >
              <div class="card-title">
                <div class="d-col">
                  <div class="text-lg"><?= htmlspecialchars($course['course_name']) ?></div>
                  <div class="text-sub"><span><?= htmlspecialchars($course['course_code']) ?></span> . <span><?= htmlspecialchars($course['semester']) ?></span></div>
                </div>
                <span class="semester-badge"><?= $course['is_active'] ? 'Current' : 'Inactive' ?></span>
              </div>
              <div class="tags">
                <div class="tag"><span><?= htmlspecialchars($course['num_students']) ?></span> Students</div>
                <div class="tag"><span><?= count($course['tas']) ?></span> TAs</div>
                <div class="tag"><span><?= htmlspecialchars($course['num_sections']) ?></span> Sections</div>
              </div>
              <div class="task-list">
                <?php if (empty($course['tas'])): ?>
                  <div class="task-item" style="display: flex; flex-direction: row;">
                    <div class="task-name">No TAs Assigned</div>
                  </div>
                <?php else: ?>
                  <?php foreach ($course['tas'] as $ta): ?>
                    <div class="task-item" style="display: flex; flex-direction: row;">
                      <div class="task-name">
                        <?= htmlspecialchars($ta['ta_name']) ?>
                        <span style="font-weight:normal; color:#666; font-size:13px;">
                          (Total Assigned: <?= htmlspecialchars($ta['total_assigned_hours']) ?> hours)
                        </span>
                      </div>
                      <div class="task-hours" style="display: flex; flex-direction: column; text-align: right;">
                        <div style="text-align: left;"> <span><i class="fas fa-clipboard-check task-icon"></i>Marking: </span> <span><?= htmlspecialchars($ta['correcting_hours']) ?> </span> <span>hours</span> </div>
                        <div style="text-align: left;"> <span><i class="fas fa-eye task-icon"></i>Proctoring: </span> <span><?= htmlspecialchars($ta['proctor_hours']) ?> </span> <span>hours</span> </div>
                        <div> <span><i class="fas fa-chalkboard-teacher task-icon"></i>Lab supervision: </span> <span><?= htmlspecialchars($ta['lab_hours']) ?> </span> <span>hours</span> </div>
                      </div> 
                    </div>
                  <?php endforeach; ?>
                <?php endif; ?>
              </div>  
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="../../js/InstructorCourses.js"></script>


<!-- Start of footer added by abdullah -->
<footer>
  <div id="footerinformation">   
    <div id="footertext">
      <h3 class="needmargin">Information Technology Services (ITS)</h3>
      Alfaisal University Campus<br>
      P.O. Box 50927, Riyadh,<br>
      11533, Kingdom of Saudi Arabia<br>
      Tel: +966 11 215 7888<br>
      <h4 class="needmargin">Email us</h4><br>
      All for Admin, technical and technician related <br> matters please contact
      itsupport@alfaisal.edu <br>  or call on 2157888    
    </div>

    <div id="footerusefullinks">
      <h3>Useful Links</h3>
      <ul class="footer-nav-list">
        <li><a href="policies" class="text-decoration-none">IT Policies</a></li>
        <li><a href="https://admissions.alfaisal.edu" class="text-decoration-none">Undergraduate Admissions</a></li>
        <li><a href="https://gradschool.alfaisal.edu" class="text-decoration-none">Graduate School</a></li>
        <li><a href="https://cob.alfaisal.edu" class="text-decoration-none">College of Business</a></li>
        <li><a href="https://coe.alfaisal.edu" class="text-decoration-none">College of Engineering</a></li>
        <li><a href="https://com.alfaisal.edu" class="text-decoration-none">College of Medicine</a></li>
        <li><a href="https://cop.alfaisal.edu" class="text-decoration-none">College of Pharmacy</a></li>
        <li><a href="https://cos.alfaisal.edu" class="text-decoration-none">College of Science</a></li>
        <li><a href="https://col.alfaisal.edu" class="text-decoration-none">College of Law & Int'l Relations</a></li>
        <li><a href="https://asc.alfaisal.edu" class="text-decoration-none">Academic Success Center</a></li>     
      </ul>
    </div>
  </div> 

  <div id="footercopyrights"> 
    <p>Copyright &copy; 2025 Alfaisal University, All Rights Reserved.</p>
  </div> 
</footer>
<!-- End of footer added by abdullah -->


</body>
</html>
