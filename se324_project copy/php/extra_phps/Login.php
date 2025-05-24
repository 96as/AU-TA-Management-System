<?php
session_start();

// Handle logout
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header('Location: Login.php');
    exit;
}

// 1) Pull in your DB connection
require __DIR__ . '/../database.php';

// Check if database connection is successful
if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Check if instructors table exists
$result = $connection->query("SHOW TABLES LIKE 'instructors'");
if ($result->num_rows == 0) {
    die("The instructors table does not exist. Please check your database setup.");
}

$error = '';

// 2) Only run this block on form POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['username'] ?? '');
    $pass  = $_POST['password'] ?? '';

    // Debug: Log the attempt
    error_log("Login attempt for email: " . $email);

    // 3) First try to login as TA
    $stmt = $connection->prepare("
        SELECT ta_id, name, pass_hash
          FROM tas
         WHERE email = ?
    ");
    if (!$stmt) {
        die("Prepare failed: " . $connection->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    // Debug: Log TA check result
    error_log("TA check result: " . ($row ? "Found" : "Not found"));

    // 4) If TA login successful
    if ($row && password_verify($pass, $row['pass_hash'])) {
        error_log("TA login successful");
        $_SESSION['ta_id'] = $row['ta_id'];
        $_SESSION['ta_name'] = $row['name'];
        $_SESSION['ta_email'] = $email;
        header('Location: TaMain.php');
        exit;
    }

    // 5) If not TA, try instructor login
    $stmt = $connection->prepare("
        SELECT instructor_id, name, pass_hash
          FROM instructors
         WHERE email = ?
    ");
    if (!$stmt) {
        die("Prepare failed: " . $connection->error . "\nSQL: SELECT instructor_id, name, pass_hash FROM instructors WHERE email = ?");
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    // Debug: Log instructor check result
    error_log("Instructor check result: " . ($row ? "Found" : "Not found"));

    // 6) If instructor login successful
    if ($row && password_verify($pass, $row['pass_hash'])) {
        error_log("Instructor login successful");
        $_SESSION['instructor_id'] = $row['instructor_id'];
        $_SESSION['instructor_name'] = $row['name'];
        $_SESSION['instructor_email'] = $email;
        header('Location: InstructorMain.php');
        exit;
    }

    // 7) If not instructor, try manager login
    $stmt = $connection->prepare("
        SELECT manager_id, name, pass_hash
          FROM managers
         WHERE email = ?
    ");
    if (!$stmt) {
        die("Prepare failed: " . $connection->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    // Debug: Log manager check result
    error_log("Manager check result: " . ($row ? "Found" : "Not found"));

    // 8) If manager login successful
    if ($row && password_verify($pass, $row['pass_hash'])) {
        error_log("Manager login successful");
        $_SESSION['manager_id'] = $row['manager_id'];
        $_SESSION['manager_name'] = $row['name'];
        $_SESSION['manager_email'] = $email;
        header('Location: ../../html/manager/ManagerMain.php');
        exit;
    }

    // Debug: Log password verification result
    if ($row) {
        error_log("Password verification failed");
    }

    $error = "Invalid email or password.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>TA Management Login</title>

  <!-- Client-side validation -->
  <script src="../../js/login.js" defer></script>
  <link rel="stylesheet" href="../../css/login.css">

  <style>
    
    
    #signinBox { 
      width: 300px; margin: 100px auto; padding: 20px; background: #fff; border-radius:8px;
      box-shadow:0 4px 8px rgba(0,0,0,.2); animation:fadeInDown 1s both; margin-top: 10%;
    }
    @keyframes fadeInDown {
      from { opacity:0; transform:translateY(-20px); } 
      to   { opacity:1; transform:translateY(0); }
    }
    .logo-container{width: 100px;
    margin-left: 15px;
    height: auto;}
    .input-wrapper { display:flex; align-items:center; border:1px solid #ccc; border-radius:5px; padding:5px 10px; margin-bottom:15px; }
    .input-icon { font-size:16px; padding-right:10px; border-right:1px solid #ccc; margin-right:10px; }
    .input-wrapper input { flex:1; border:none; outline:none; padding:10px 5px; }
    #btn-signin { width:50%; background:#0056b3; color:#fff; border:none; padding:10px; border-radius:5px; cursor:pointer; margin-left: 70px; }
    #btn-signin:hover { background:#10315A; }
    .error { color:red; text-align:center; margin-bottom:15px; }
  </style>
</head>
<body>

  <div id="signinBox">
    <div class="logo-container">
      <img src="../../images/logo.png" id="logo" class="img-fluid mx-auto d-block"/>
    </div>
    <h3 style="text-align:center; margin-bottom:20px;">TA Management System</h3>

    <!-- 5) Show server-side error if login failed -->
    <?php if ($error): ?>
      <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <!-- 6) Post back to this same file -->
    <form method="POST" action="Login.php">
      <div class="input-wrapper">
        <span class="input-icon">&#128100;</span>
        <input id="username" name="username" type="email" placeholder="you@alfaisal.edu" required>
      </div>
      <div class="input-wrapper">
        <span class="input-icon">&#128274;</span>
        <input id="password" name="password" type="password" placeholder="Password" required>
      </div>
      <button type="submit" id="btn-signin">Sign In</button>
    </form>
  </div>

</body>
</html>
