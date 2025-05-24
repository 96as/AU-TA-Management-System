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
  <title>Course Managment</title>

  <!-- Material Design Icons -->
  <link href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../css/MainPages.css">
  <link rel="stylesheet" href="../../css/ManageCourses.css">
<!--  start of footer reference added by abdullah --> <link rel="stylesheet" href="../../css/footerstyle.css">  <!--  end of footer reference added by abdullah -->


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

      <div id="main-content" class="main-content">
        <div class="page-header">
          <h2>Course Management</h2>
        </div>
        <ul class="breadcrumbs">
          <li><a href="ManagerMain.php">Home ></a></li>
          <li>Manage Courses</li>
        </ul>
        <hr class="divider divider-bottom-margin">

        <div class="page-actions">
          <div class="search-bar">
            <i class="mdi mdi-magnify"></i>
            <input type="text" placeholder="Search courses..." onkeyup="searchCourses(this.value)">
          </div>
          <button id="add-courses-btn" class="add-courses-btn" onclick="openAddCourseModal('')">
            <i class="fas fa-plus"></i>
            Add Courses
          </button>




          <button class="add-courses-btn" onclick="window.location.href='ManageTa.php'">
            <i class="mdi mdi-view-list"></i>
            Manage TAs
          </button>
        </div>

        <div class="summary-panel">
          <div class="summary-item">
            <div id="courses-count" class="summary-value">0</div>
            <div class="summary-label">Courses</div>
          </div>

          <div class="summary-item">
            <div id="instructors-count" class="summary-value">0</div>
            <div class="summary-label">Instructors</div>
          </div>

          <div class="summary-item">
            <div id="tas-count" class="summary-value">0</div>
            <div class="summary-label">TAs</div>
          </div>

          <div class="summary-item">
            <div id="total-ta-hours" class="summary-value">0</div>
            <div class="summary-label">Total TA Hours</div>
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
              <option>Science</option>
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
        <!-- 1st Year -->
        <div class="year-container">
          <div class="year-header" onclick="toggleYearSection('first-year')">
            <h3>1st Year Courses</h3>
            <i class="mdi mdi-chevron-down chevron-icon"></i>
          </div>

          <div id="first-year" class="year-content">
            <div class="course-item">
              <p class="text-sub">No Courses Added Yet</p>
            </div>
          </div>
        </div>

        <!-- 2nd Year -->
        <div class="year-container">
          <div class="year-header" onclick="toggleYearSection('second-year')">
            <h3>2nd Year Courses</h3>
            <i class="mdi mdi-chevron-down chevron-icon"></i>
          </div>

          <div id="second-year" class="year-content">
            <div class="course-item">
              <p class="text-sub">No Courses Added Yet</p>
            </div>
          </div>
        </div>

        <!-- 3rd Year -->
        <div class="year-container">
          <div class="year-header" onclick="toggleYearSection('third-year')">
            <h3>3rd Year Courses</h3>
            <i class="mdi mdi-chevron-down chevron-icon"></i>
          </div>

          <div id="third-year" class="year-content">
            <div class="course-item">
              <p class="text-sub">No Courses Added Yet</p>
            </div>
          </div>
        </div>

        <!-- 4th Year -->
        <div class="year-container">
          <div class="year-header" onclick="toggleYearSection('fourth-year')">
            <h3>4th Year Courses</h3>
            <i class="mdi mdi-chevron-down chevron-icon"></i>
          </div>

          <div id="fourth-year" class="year-content">
            <div class="course-item">
              <p class="text-sub">No Courses Added Yet</p>
            </div>
          </div>
        </div>

      </div>
    </main>
  </div>
</div>



<!-- Add Course form Modal -->
<div id="add-course-modal" class="course-modal">
  <div class="modal-content">
    <div class="modal-header">
      <div class="modal-title">Add Course</div>
    </div>
    <div class="modal-body">
      <form id="addCourseForm">

        <div class="form-row">
          <div class="form-group">
            <label for="courseCode" class="required">Course Code</label>
            <select id="courseCode" class="form-control" required>
            </select>
          </div>

          <div class="form-group">
            <label for="numStudents">Number of Students</label>
            <input type="number" id="numStudents" class="form-control" placeholder="e.g. 50" min="1">
          </div>
        </div>

        <div class="form-group">
          <label for="selectInstructor">Instructor</label>
          <select id="selectInstructor" class="form-control" required>
          </select>
        </div>

        <div class="form-group">
          <label for="selectTA">Teaching Assistant (Optional)</label>
          <select id="selectTA" class="form-control">
          </select>
        </div>

        <div class="form-group" id="taHoursGroup">
          <label for="TaHours">Max Hours/Week</label>
          <input type="number" id="TaHours" class="form-control" value="" min="1" max="15">
        </div>

        <div class="form-group">
          <label for="numSections">Number of Sections</label>
          <input type="number" id="numSections" class="form-control" value="1" min="1" max="10">
        </div>


      </form>
    </div>
    <div class="modal-footer">
      <button class="cancel-btn" onclick="closeAddCourseModal()">Cancel</button>
      <button id="submit-course-btn" class="add-course-btn" onclick="submitAddCourseForm()">Add Course</button>
    </div>
  </div>
</div>

<!-- Add TA to Course Modal -->
<div id="add-ta-modal" class="course-modal">
  <div class="modal-content">
    <div class="modal-header">
      <div class="modal-title">Add TA to Course</div>
    </div>
    <div class="modal-body">
      <form id="addTaForm">
        <div class="form-group">
          <label for="selectNewTA">Teaching Assistant</label>
          <select id="selectNewTA" class="form-control" required>
          </select>
        </div>

        <div class="form-group">
          <label for="newTaHours">Max Hours/Week</label>
          <input type="number" id="newTaHours" class="form-control" value="" min="1" max="15" required>
        </div>
      </form>
    </div>
    <div class="modal-footer">
      <button class="cancel-btn" onclick="closeAddTaModal()">Cancel</button>
      <button id="submit-ta-btn" class="add-course-btn" onclick="submitAddTaForm()">Add TA</button>
    </div>
  </div>
</div>

<!-- Remove TA from Course Modal -->
<div id="remove-ta-modal" class="course-modal">
  <div class="modal-content">
    <div class="modal-header">
      <div class="modal-title">Remove TA from Course</div>
    </div>
    <div class="modal-body">
      <form id="removeTaForm">
        <div class="form-group">
          <label for="selectRemoveTA">Select TA to Remove</label>
          <select id="selectRemoveTA" class="form-control" required>
          </select>
        </div>
      </form>
    </div>
    <div class="modal-footer">
      <button class="cancel-btn" onclick="closeRemoveTaModal()">Cancel</button>
      <button id="submit-remove-ta-btn" class="delete-course-btn" onclick="submitRemoveTaForm()">Remove TA</button>
    </div>
  </div>
</div>

<script src="../../js/ManageCourses.js"></script>
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