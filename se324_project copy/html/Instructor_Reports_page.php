<?php
session_start();
if (empty($_SESSION['instructor_id']) || empty($_SESSION['instructor_name'])) {
    header('Location: ../php/extra_phps/Login.php');
    exit;
}
require __DIR__ . '/../php/database.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TA Management System - Reports</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
<!--  start of footer reference added by abdullah --> <link rel="stylesheet" href="../css/footerstyle.css">  <!--  end of footer reference added by abdullah -->

  <script src="../js/Reports.js"></script>
  <link rel="stylesheet" href="../css/Reports.css">
  <link rel="stylesheet" href="../css/MainPages.css">
</head>
<body>

<div class="app-wrap">
  <div class="drawer drawer-rail" id="sidebar">
    <button class="drawer-toggle-btn" id="sidebarToggle">
      <i class="fas fa-chevron-right" id="toggleIcon"></i>
    </button>
    <div class="drawer-content">
      <div style="display: flex; justify-content: center; margin-bottom: 20px;">
        <img src="../images/logo.png" alt=":P fix meeeee" class="logo">
        <span class="only-full university-name">Alfaisal University</span>
      </div>
      <div class="divider divider-bottom-margin"></div>
      
      <a href="../php/extra_phps/InstructorMain.php" class="nav-item">
        <div class="nav-prepend">
          <i class="fas fa-home"></i>
        </div>
        <span class="only-full">Dashboard</span>
      </a>

      <a href="../php/extra_phps/InstructorCourses.php" class="nav-item">
        <div class="nav-prepend">
          <i class="fas fa-book"></i>
        </div>
        <span class="only-full">Courses</span>
      </a>

      <a href="../php/extra_phps/InstructorAssignTa.php" class="nav-item">
        <div class="nav-prepend">
          <i class="fas fa-users"></i>
        </div>
        <span class="only-full">TA Management</span>
      </a>

      <a href="Instructor_Reports_page.php" class="nav-item">
        <div class="nav-prepend">
          <i class="fas fa-chart-bar"></i>
        </div>
        <span class="only-full">Reports</span>
      </a>

      <div class="divider"></div>

      <a href="profile.html" class="nav-item">
        <div class="nav-prepend">
          <i class="mdi mdi-account-circle"></i>
        </div>
        <span class="only-full">Profile</span>
      </a>
      <a href="../php/extra_phps/Login.php?logout" class="nav-item logout">
        <div class="nav-prepend">
          <i class="fas fa-sign-out-alt"></i>
        </div>
        <span class="only-full">Logout</span>
      </a>
    </div>
  </div>

  <div class="app-inner">
    <div class="main">
      <div class="main-content" id="mainContent">
        <div class="page-header">
          <h1>Reports</h1>
          <ul class="breadcrumbs">
            <li><a href="../php/extra_phps/InstructorMain.php">Home ></a></li>
            <li>View Reports</li>
          </ul>
        </div>

        <div class="page-actions">
          <div class="search-bar">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search reports...">
          </div>
          <button class="export-btn">
            <i class="fas fa-download"></i>
            Export Data
          </button>
        </div>

        <div class="summary-panel">
          <div class="summary-item">
            <div class="summary-value" id="totalCourses"></div>
            <div class="summary-label">Total Courses</div>
          </div>
          <div class="summary-item">
            <div class="summary-value" id="totalInstructors"></div>
            <div class="summary-label">Instructors</div>
          </div>
          <div class="summary-item">
            <div class="summary-value" id="totalTAs"></div>
            <div class="summary-label">Total TAs</div>
          </div>
          <div class="summary-item">
            <div class="summary-value" id="totalHours"></div>
            <div class="summary-label">Total Hours</div>
          </div>
          
        </div>

        <div class="tile-container">
          <div class="tile">
            <div class="tile-title">Marking Hours</div>
            <div class="tile-value" id="markingHours"></div>
          </div>
          <div class="tile">
            <div class="tile-title">Proctoring Hours</div>
            <div class="tile-value" id="proctoringHours"></div>
            
          </div>
          <div class="tile">
            <div class="tile-title">Lab Supervision</div>
            <div class="tile-value" id="labHours"></div>
            
          </div>
        </div>

        <div class="report-controls">
          <div class="filters">
            <div class="filter-group">
              <label for="semesterFilter">Semester:</label>
              <select id="semesterFilter">
                <option value="all" selected>All Semesters</option>
                <option value="spring2025">Spring 2025</option>
                <option value="fall2024">Fall 2024</option>
                <option value="spring2024">Spring 2024</option>
              </select>
            </div>
            <div class="filter-group">
              <label for="departmentFilter">Department:</label>
              <select id="departmentFilter">
                <option value="all" selected>All Departments</option>
                <option value="coe">Computer Engineering</option>
                <option value="cs">Computer Science</option>
                <option value="ee">Electrical Engineering</option>
              </select>
            </div>
          </div>
        </div>

        <div class="card task-distribution-card">
          <div class="card-header">
            <div class="card-title">Task Distribution by Course</div>
          </div>
          <div class="chart-container">
            <canvas id="taskDistributionChart"></canvas>
          </div>
        </div>

        <div class="card workload-analysis-card">
          <div class="card-header">
            <div class="card-title">TA Workload Analysis</div>
          </div>
          <div class="chart-container">
            <canvas id="workloadChart"></canvas>
          </div>
        </div>

        <div class="card">
          <div class="card-header">
            <div class="card-title">Detailed Task Hours by Course</div>
          </div>
          <div class="table-responsive">
            <table >
              <thead>
              <tr>
                <th>Course Code</th>
                <th>Course Name</th>
                <th>Marking</th>
                <th>Proctoring</th>
                <th>Lab Supervision</th>
                <th>Total Hours</th>
                <th>Distribution</th>
              </tr>
              </thead>
              <tbody id="courseHourSplitTable">
              
              </tbody>
            </table>
          </div>
        </div>

        <div class="card">
          <div class="card-header">
            <div class="card-title">TA Workload by Individual</div>
          </div>
          <div class="table-responsive">
            <table>
              <thead>
              <tr>
                <th>TA Name</th>
                <th>Courses</th>
                <th>Marking</th>
                <th>Proctoring</th>
                <th>Lab Supervision</th>
                <th>Total Hours</th>
                <th>Utilization</th>
              </tr>
              </thead>
              <tbody id="TAHourSplitTable">
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


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
 <script src="../js/drawer.js"></script>
</html>
