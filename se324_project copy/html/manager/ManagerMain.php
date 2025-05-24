<?php

session_start();

// 1) Redirect if not logged in
if (empty($_SESSION['manager_id']) || empty($_SESSION['manager_name'])) {
    header('Location: ../../php/extra_phps/Login.php');
    exit;
}

// 2) Bring in your DB connection
require __DIR__ . '/../../php/database.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manager Dashboard - TA Management System</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css" rel="stylesheet">
  
  <link rel="stylesheet" href="../../css/footerstyle.css">

  <link rel="stylesheet" href="../../css/MainPages.css">

</head>
<body>
<div class="app-wrap">
  <div class="drawer drawer-rail" id="sidebar">
    <div class="drawer-content">
      <div style="display: flex; justify-content: center; margin-bottom: 20px;">
        <img src="../../images/logo.png" alt=":P fix meeeee" class="logo">
        <span class="only-full university-name">Alfaisal University</span>
      </div>

      <div class="divider divider-bottom-margin"></div>

      <a href="ManagerMain.php" class="nav-item">
        <div class="nav-prepend">
          <i class="fas fa-home"></i>
        </div>
        <span class="only-full">Dashboard</span>
      </a>

      <a href="ManageCourses.php" class="nav-item">
        <div class="nav-prepend">
          <i class="fas fa-book"></i>
        </div>
        <span class="only-full">Courses</span>
      </a>

      <a href="ManageTa.php" class="nav-item">
        <div class="nav-prepend">
          <i class="fas fa-users"></i>
        </div>
        <span class="only-full">TA Management</span>
      </a>

      <a href="../Reports_page.php" class="nav-item">
        <div class="nav-prepend">
          <i class="fas fa-chart-bar"></i>
        </div>
        <span class="only-full">Reports</span>
      </a>

      <div class="divider"></div>

      <a href="../profile.html" class="nav-item">
        <div class="nav-prepend">
          <i class="mdi mdi-account-circle"></i>
        </div>
        <span class="only-full">Profile</span>
      </a>
      <a href="../../php/extra_phps/Login.php?logout" class="nav-item logout">
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
        <h1>Manager Dashboard</h1>
        <ul class="breadcrumbs">
          <li><a href="ManagerMain.php">Home ></a></li>
          <li>Dashboard</li>
        </ul>
      </div>

      <div class="stats-section">
        <div class="stat-card">
          <div class="stat-icon"><i class="fas fa-book"></i></div>
          <div class="stat-value" id="dashboard-courses-count">0</div>
          <div class="stat-label">Total Courses</div>
        </div>
        
        <div class="stat-card">
          <div class="stat-icon"><i class="fas fa-user-tie"></i></div>
          <div class="stat-value" id="dashboard-instructors-count">0</div>
          <div class="stat-label">Instructors</div>
        </div>

        <div class="stat-card">
          <div class="stat-icon"><i class="fas fa-users"></i></div>
          <div class="stat-value" id="dashboard-tas-count">0</div>
          <div class="stat-label">Total TAs</div>
        </div>

        <div class="stat-card">
          <div class="stat-icon"><i class="fas fa-clock"></i></div>
          <div class="stat-value" id="dashboard-total-ta-hours">0</div>
          <div class="stat-label">Total TA Hours</div>
        </div>
      </div>

      <div class="section-title">Quick Access</div>

      <div class="cards">
        <div class="card">
          <div class="card-title">
            <div class="d-col">
              <span class="text-lg">Courses Management</span>
              <span class="text-sub">Create and manage courses</span>
            </div>
          </div>
          <div class="tags">
            <span class="tag"><i class="fas fa-plus"></i> Create Course</span>
            <span class="tag"><i class="fas fa-link"></i> Assign TA</span>
          </div>
          <div class="card-footer">
            <a href="ManageCourses.php" class="btn-details">Go to Courses</a>
          </div>
        </div>

        <div class="card">
          <div class="card-title">
            <div class="d-col">
              <span class="text-lg">TA Management</span>
              <span class="text-sub">Add and assign teaching assistants</span>
            </div>
          </div>
          <div class="tags">
            <span class="tag"><i class="fas fa-plus"></i> Add TA</span>
          </div>
          <div class="card-footer">
            <a href="ManageTa.php" class="btn-details">Manage TAs</a>
          </div>
        </div>

        <div class="card">
          <div class="card-title">
            <div class="d-col">
              <span class="text-lg">Reports</span>
              <span class="text-sub">View system Reports and Analytics</span>
            </div>
          </div>
          <div class="tags">
            <span class="tag"><i class="fas fa-chart-bar"></i> TA Workload</span>
            <span class="tag"><i class="fas fa-tasks"></i> Task Distribution</span>
          </div>
          <div class="card-footer">
            <a href="../Reports_page.php" class="btn-details">View Reports</a>
          </div>
        </div>
      </div>

      </div>
    </div>
  </div>
</div>

<script src="../../js/drawer.js"></script>
<script>
function fetchDashboardStats() {
    fetch('../../php/get_summary_stats.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('dashboard-courses-count').textContent = data.courses_count;
                document.getElementById('dashboard-instructors-count').textContent = data.instructors_count;
                document.getElementById('dashboard-tas-count').textContent = data.tas_count;
                document.getElementById('dashboard-total-ta-hours').textContent = data.total_ta_hours;
            }
        })
        .catch(err => console.error('Error fetching dashboard stats:', err));
}
document.addEventListener('DOMContentLoaded', fetchDashboardStats);
</script>


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