<?php
session_start();

// 1) Redirect if not logged in as instructor
if (empty($_SESSION['instructor_id']) || empty($_SESSION['instructor_name'])) {
    header('Location: Login.php');
    exit;
}

// 2) Bring in your DB connection
require __DIR__ . '/../database.php';

// 3) Fetch instructor's courses count
$totalCourses = 0;
$sql = "SELECT COUNT(*) FROM activecourses WHERE instructor = ?";
if ($stmt = $connection->prepare($sql)) {
    $stmt->bind_param("i", $_SESSION['instructor_id']);
    $stmt->execute();
    $stmt->bind_result($totalCourses);
    $stmt->fetch();
    $stmt->close();
} else {
    die("Prepare failed: " . $connection->error);
}

// 4) Fetch assigned TAs count
$assignedTAs = 0;
$sql = "SELECT COUNT(DISTINCT ta_name) FROM ta_course tc 
        JOIN activecourses ac ON tc.course_code = ac.course_code 
        WHERE ac.instructor = ?";
if ($stmt = $connection->prepare($sql)) {
    $stmt->bind_param("i", $_SESSION['instructor_id']);
    $stmt->execute();
    $stmt->bind_result($assignedTAs);
    $stmt->fetch();
    $stmt->close();
} else {
    die("Prepare failed: " . $connection->error);
}

// 5) Fetch total TA hours
$totalHours = 0;
$sql = "SELECT COALESCE(SUM(tc.total_assigned_hours), 0) 
        FROM ta_course tc 
        JOIN activecourses ac ON tc.course_code = ac.course_code 
        WHERE ac.instructor = ?";
if ($stmt = $connection->prepare($sql)) {
    $stmt->bind_param("i", $_SESSION['instructor_id']);
    $stmt->execute();
    $stmt->bind_result($totalHours);
    $stmt->fetch();
    $stmt->close();
} else {
    die("Prepare failed: " . $connection->error);
}

// 6) Fetch total distributed task hours
$distributedHours = 0;
$sql = "SELECT COALESCE(SUM(tc.correcting_hours + tc.lab_hours + tc.proctor_hours), 0) 
        FROM ta_course tc 
        JOIN activecourses ac ON tc.course_code = ac.course_code 
        WHERE ac.instructor = ?";
if ($stmt = $connection->prepare($sql)) {
    $stmt->bind_param("i", $_SESSION['instructor_id']);
    $stmt->execute();
    $stmt->bind_result($distributedHours);
    $stmt->fetch();
    $stmt->close();
} else {
    die("Prepare failed: " . $connection->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TA Management System - Instructor Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../css/InstructorMain.css">

    <!--  start of footer reference added by abdullah --> <link rel="stylesheet" href="../../css/footerstyle.css">  <!--  end of footer reference added by abdullah -->


</head>
<body>
<div class="app-wrap">
  <div class="drawer drawer-rail" id="sidebar">
    <div class="drawer-content">
      <div style="display: flex; justify-content: center; margin-bottom: 20px;">
        <img src="../../images/logo.png" alt="logo" class="logo">
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

  <div class="main">
  <div class="main-content">
      <div class="page-header">
        <h1>Instructor Dashboard</h1>
        <ul class="breadcrumbs">
          <li><a href="InstructorMain.php">Home ></a></li>
          <li>Dashboard</li>
        </ul>
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['instructor_name']); ?></h2>
      </div>

      <div class="stats-section">
        <div class="stat-card">
          <div class="stat-icon"><i class="fas fa-book"></i></div>
          <div class="stat-value"><?= htmlspecialchars($totalCourses) ?></div>
          <div class="stat-label">Total Courses</div>
        </div>

        <div class="stat-card">
          <div class="stat-icon"><i class="fas fa-users"></i></div>
          <div class="stat-value"><?= htmlspecialchars($assignedTAs) ?></div>
          <div class="stat-label">Assigned TAs</div>
        </div>

        <div class="stat-card">
          <div class="stat-icon"><i class="fas fa-clock"></i></div>
          <div class="stat-value"><?= htmlspecialchars($totalHours) ?></div>
          <div class="stat-label">Total TA Hours</div>
        </div>

        <div class="stat-card">
          <div class="stat-icon"><i class="fas fa-clock"></i></div>
          <div class="stat-value"><?= htmlspecialchars($distributedHours) ?></div>
          <div class="stat-label">Total Hours of Distributed Tasks</div>
        </div>
      </div>

      <div class="section-title">Quick Access</div>

      <div class="cards">
        <div class="card">
          <div class="card-title">
            <div class="d-col">
              <span class="text-lg">My Courses</span>
              <span class="text-sub">View Courses and TAs</span>
            </div>
          </div>
          <div class="tags">
            <span class="tag"><i class="fas fa-book"></i> View Assigned Courses</span>
            <span class="tag"><i class="fas fa-tasks"></i> View TAs Tasks</span>
          </div>

          <div class="card-footer">
            <a href="InstructorCourses.php" class="btn-details">View Courses</a>
          </div>
        </div>

        <div class="card">
          <div class="card-title">
            <div class="d-col">
              <span class="text-lg">Manage TA tasks</span>
              <span class="text-sub">Assign tasks to TAs</span>
            </div>
          </div>
          <div class="tags">
            <span class="tag"><i class="fas fa-users"></i> View Assigned TAs</span>
            <span class="tag"><i class="fas fa-tasks"></i> Distribute Tasks</span>
          </div>
          <div class="card-footer">
            <a href="InstructorAssignTa.php" class="btn-details">Manage TAs</a>
          </div>
        </div>

        <div class="card">
          <div class="card-title">
            <div class="d-col">
              <span class="text-lg">Reports</span>
              <span class="text-sub">View System Reports and Analytics</span>
            </div>
          </div>
          <div class="tags">
            <span class="tag"><i class="fas fa-chart-bar"></i> TA Workload</span>
            <span class="tag"><i class="fas fa-tasks"></i> Task Distribution</span>
          </div>
          <div class="card-footer">
            <a href="../../html/Instructor_Reports_page.php" class="btn-details">View Reports</a>
          </div>
        </div>
      </div>

        </ul>

      </div>
    </div>
  </div>
</div>


<script src="../../js/drawer.js"></script>


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
