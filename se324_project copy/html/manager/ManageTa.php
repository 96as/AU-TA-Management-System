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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Manage TAs</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../../css/ManageTAs.css">
    <link rel="stylesheet" href="../../css/MainPages.css">
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

    <div class="main-content" style="width: 100%;">
      <div class="page-header">
        <h2>TA Management</h2>
      </div>
      <ul class="breadcrumbs">
        <li><a href="ManagerMain.php">Home ></a></li>
        <li>Manage TAs</li>
      </ul>
      <hr class="divider divider-bottom-margin">

      <div class="content">
        <div class="summary" id="summary-cards"></div>
        <div class="section">
          <div class="section-header">
            <h2>Manage TAs</h2>
            <div class="btn-group">
              <button class="btn outline" id="remove-ta">Remove TA</button>
              <button class="btn primary" id="add-ta">Add New TA</button>
            </div>
          </div>
          <div class="table-responsive">
            <table>
              <thead>
                <tr>
                  <th><input type="checkbox" id="select-all"></th>
                  <th>Name</th>
                  <th>ID</th>
                  <th>University Email</th>
                  <th>Year</th>
                </tr>
              </thead>
              <tbody id="ta-table-body"></tbody>
            </table>
          </div>
          <div class="footer" id="table-footer"></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Add/Edit Modal -->
  <div class="modal" id="ta-modal">
    <div class="modal-content">
      <button class="close-modal" id="close-modal-btn" style="position:absolute;top:12px;right:16px;background:none;border:none;font-size:1.5rem;cursor:pointer;z-index:10;" aria-label="Close">&times;</button>
      <h3 id="modal-title">Add New TA</h3>
      <div class="form-group">
        <label for="ta-name">Name</label>
        <input type="text" id="ta-name" required />
      </div>
      <div class="form-group">
        <label for="ta-id">ID</label>
        <input type="text" id="ta-id" required />
      </div>
      <div class="form-group">
        <label for="ta-email">University Email</label>
        <input type="email" id="ta-email" required />
      </div>
      <div class="form-group">
        <label for="ta-year">Year</label>
        <select id="ta-year" required>
          <option value="">Select Year</option>
          <option value="Freshman">Freshman</option>
          <option value="Sophomore">Sophomore</option>
          <option value="Junior">Junior</option>
          <option value="Senior">Senior</option>
        </select>
      </div>
      <div class="modal-actions">
        <button class="btn primary" id="save-btn">Save</button>
      </div>
    </div>
  </div>

  <!-- Only keep this JS file reference -->
   <script src="../../js/drawer.js" defer></script>
    <script src="../../js/mangerTA.js" defer></script>


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
