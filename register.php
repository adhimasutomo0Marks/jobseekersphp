<?php
session_start();
require_once 'config.php';

// If already logged in, redirect to home
if (isset($_SESSION['user_id'])) {
    header("Location: home.php");
    exit();
}

$error = '';
$success = '';

// Handle registration form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    
    // Validate password match
    if ($password !== $confirmPassword) {
        $error = 'Passwords do not match!';
    } else {
        // Check if user already exists
        $checkSql = "SELECT * FROM users WHERE email = '$email'";
        $checkResult = mysqli_query($conn, $checkSql);
        
        if (mysqli_num_rows($checkResult) > 0) {
            $error = 'User with this email already exists!';
        } else {
            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert new user
            $sql = "INSERT INTO users (name, email, password, is_admin) VALUES ('$name', '$email', '$hashedPassword', 0)";
            
            if (mysqli_query($conn, $sql)) {
                $userId = mysqli_insert_id($conn);
                
                // Create empty profile for user
                $profileSql = "INSERT INTO user_profiles (user_id) VALUES ($userId)";
                mysqli_query($conn, $profileSql);
                
                $success = 'Registration successful! Redirecting to login...';
                header("refresh:2;url=login.php");
            } else {
                $error = 'Registration failed! Please try again.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - Job Seekers</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body class="login-body">
  <div class="login-container">
    <div class="login-box">
      <h1>J*b Seekers</h1>
      <h2>Create Account</h2>
      
      <?php if ($error): ?>
        <div style="background:#ff4444; padding:10px; border-radius:5px; margin-bottom:15px;">
          <?php echo $error; ?>
        </div>
      <?php endif; ?>
      
      <?php if ($success): ?>
        <div style="background:#00ff33; color:#000; padding:10px; border-radius:5px; margin-bottom:15px;">
          <?php echo $success; ?>
        </div>
      <?php endif; ?>
      
      <form method="POST" action="">
        <div class="form-group">
          <label for="name">Full Name</label>
          <input type="text" id="name" name="name" placeholder="Enter your name" required>
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" placeholder="Enter your email" required>
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="Create a password" required>
        </div>
        <div class="form-group">
          <label for="confirmPassword">Confirm Password</label>
          <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Re-enter password" required>
        </div>
        <button type="submit" class="btn-primary">Sign Up</button>
        <p class="text-center">Already have an account? <a href="login.php">Login</a></p>
      </form>
    </div>
  </div>
</body>
</html>
