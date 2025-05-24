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
        SELECT tc.ta_name, tc.total_assigned_hours, tc.correcting_hours, tc.proctor_hours, tc.lab_hours,
               t.year as ta_type
        FROM ta_course tc
        JOIN tas t ON tc.ta_name = t.name
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

// --- SUMMARY PANEL CALCULATIONS ---
// 1. Total courses assigned to this instructor
$totalCourses = count($courses);

// 2. Total number of TAs assigned to a course with this instructor
$totalTAs = 0;
$uniqueTAs = [];
foreach ($courses as $course) {
    foreach ($course['tas'] as $ta) {
        if (!isset($uniqueTAs[$ta['ta_name']])) {
            $uniqueTAs[$ta['ta_name']] = true;
            $totalTAs++;
        }
    }
}

// 3. Sum of total_assigned_hours for TAs assigned to a course with this instructor
$totalTAHours = 0;
foreach ($courses as $course) {
    foreach ($course['tas'] as $ta) {
        $totalTAHours += (int)$ta['total_assigned_hours'];
    }
}

// 4. Total sum of proctor_hours, correcting_hours & lab_hours for TAs assigned to a course with this instructor
$totalDistributedTasks = 0;
foreach ($courses as $course) {
    foreach ($course['tas'] as $ta) {
        $totalDistributedTasks += (int)$ta['proctor_hours'] + (int)$ta['correcting_hours'] + (int)$ta['lab_hours'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Distribute Tasks for TAs</title>

  <!-- Material Design Icons -->
  <link href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../css/InstructorAssignTa.css">

    <!--  start of footer reference added by abdullah --> <link rel="stylesheet" href="../../css/footerstyle.css">  <!--  end of footer reference added by abdullah -->

  <!-- <script src="../../js/instructorAssignTa.js" defer></script> -->



</head>
<body>
<div class="app-wrap">
  <div class="app-inner">
    <main class="main">
      <div class="drawer drawer-rail" id="sidebar">
        <div class="drawer-content">
          <div style="display: flex; justify-content: center; margin-bottom: 20px;">
            <img src="../../images/logo.png" alt=":P fix meeeee" class="logo">
            <span class="only-full university-name">Alfaisal University</span>
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

        <button class="drawer-toggle-btn" id="sidebarToggle">
          <i class="fas fa-chevron-right" id="toggleIcon"></i>
        </button>
      </div>


      <div id="main-content" class="main-content">
        <div class="page-header">
          <h2>Manage TA tasks</h2>
        </div>
        <ul class="breadcrumbs">
          <li><a href="InstructorMain.php">Home ></a></li>
          <li>Assign tasks to TAs</li>
        </ul>
        <hr class="divider divider-bottom-margin">

        <div class="page-actions">
          <div class="search-bar">
            <i class="mdi mdi-magnify"></i>
            <input type="text" placeholder="Search courses...">
          </div>
          <a href="InstructorCourses.php">
            <button class="view-all-btn">
              <i class="mdi mdi-view-list"></i>
              View All Courses
            </button>
          </a>
        </div>

        <div class="summary-panel">
          
          <div class="summary-item" id="courses-summary">
            <div class="summary-value"><?= htmlspecialchars($totalCourses) ?></div>
            <div class="summary-label">Courses</div>
          </div>

          <div class="summary-item" id="total-tas-summary">
            <div class="summary-value"><?= htmlspecialchars($totalTAs) ?></div>
            <div class="summary-label">Total TAs</div>
          </div>
          
          <div class="summary-item" id="total-ta-hours-summary">
            <div class="summary-value"><?= htmlspecialchars($totalTAHours) ?></div>
            <div class="summary-label">Total TA Hours</div>
          </div>
          
          <div class="summary-item" id="total-distributed-tasks-summary">
            <div class="summary-value"><?= htmlspecialchars($totalDistributedTasks) ?></div>
            <div class="summary-label">Total Hours Of Distributed Tasks</div>
          </div>
        </div>

        <div class="filters">
          <div class="filter-group">
            <label>Term:</label>
            <select>
              <option>Spring 2025</option>
              <option>Fall 2024</option>
              <option>Summer 2024</option>
            </select>
          </div>
          <div class="filter-group">
            <label>Department:</label>
            <select>
              <option>All Departments</option>
              <option>Computer Science</option>
              <option>Software Engineering</option>
              <option>Mathematics</option>
            </select>
          </div>
          <div class="filter-group">
            <label>Year:</label>
            <select>
              <option>All Years</option>
              <option>1st Year</option>
              <option>2nd Year</option>
              <option>3rd Year</option>
              <option>4th Year</option>
            </select>
          </div>
        </div>

        <!-- Display courses and TAs -->
        <div id="assignment-list">
          <?php foreach ($courses as $course): ?>
            <div class="course-container">
              <h3><?= htmlspecialchars($course['course_name']) ?> (<?= htmlspecialchars($course['course_code']) ?>)</h3>
              <div class="ta-list">
                <?php foreach ($course['tas'] as $ta): ?>
                  <div class="ta-item">
                    <div class="ta-info">
                      <div class="ta-details">
                        <div class="ta-name"><?= htmlspecialchars($ta['ta_name']) ?></div>
                        <div class="ta-type"><?php echo $ta['ta_type'] ?></div>
                        <div class="ta-tasks-summary">
                          Labs: <?= (int)$ta['lab_hours'] ?>h | Proctoring: <?= (int)$ta['proctor_hours'] ?>h | Correcting: <?= (int)$ta['correcting_hours'] ?>h
                        </div>
                      </div>
                    </div>
                    <div class="hours-input">
                      <button class="distribute-tasks-btn"
                        data-ta-name="<?= htmlspecialchars($ta['ta_name']) ?>"
                        data-course-code="<?= htmlspecialchars($course['course_code']) ?>"
                        data-total-hours="<?= htmlspecialchars($ta['total_assigned_hours']) ?>"
                        data-proctor-hours="<?= htmlspecialchars($ta['proctor_hours']) ?>"
                        data-correcting-hours="<?= htmlspecialchars($ta['correcting_hours']) ?>"
                        data-lab-hours="<?= htmlspecialchars($ta['lab_hours']) ?>"
                      >Distribute Tasks</button>
                      <span class="ta-hours"><?= htmlspecialchars($ta['total_assigned_hours']) ?> hrs/week</span>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </main>
  </div>
</div>

<!-- Task Distribution Modal -->
<div id="task-modal" class="ta-modal">
  <div class="modal-content">
    <div class="modal-header">
      <div class="modal-title" id="modal-title">Distribute Tasks</div>
      <button class="close-modal" onclick="closeTaskModal()">×</button>
    </div>
    <div class="modal-body" id="modal-body">
      <!-- Task distribution form will be inserted here by JS -->
    </div>
    <div class="modal-footer">
      <button class="cancel-btn" onclick="closeTaskModal()">Cancel</button>
      <button class="save-btn" onclick="saveTaskDistribution()">Save</button>
    </div>
  </div>
</div>

<script>
const taCourseData = <?php echo json_encode($courses); ?>;
</script>
<script src="../../js/instructorAssignTa.js"></script>
<script src="../../js/drawer.js" defer></script>


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