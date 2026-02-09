<?php
session_start();
require_once 'config.php';

// If already logged in, redirect to home
if (isset($_SESSION['user_id'])) {
    header("Location: home.php");
    exit();
}

$error = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    
    // Query user from database
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['is_admin'] = $user['is_admin'];
            
            header("Location: home.php");
            exit();
        } else {
            $error = 'Invalid email or password!';
        }
    } else {
        $error = 'Invalid email or password!';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Job Seekers</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body class="login-body">
  <div class="login-container">
    <div class="login-box">
      <h1>J*b Seekers</h1>
      <h2>Welcome Back</h2>
      
      <?php if ($error): ?>
        <div style="background:#ff4444; padding:10px; border-radius:5px; margin-bottom:15px;">
          <?php echo $error; ?>
        </div>
      <?php endif; ?>
      
      <form method="POST" action="">
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" placeholder="Enter your email" required>
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="Enter your password" required>
        </div>
        <button type="submit" class="btn-primary">Login</button>
        <p class="text-center">Don't have an account? <a href="register.php">Sign up</a></p>
      </form>
      
      <div style="margin-top:20px; padding:15px; background:#333; border-radius:5px; font-size:12px;">
        <strong>Demo Accounts:</strong><br>
        Admin: admin@jobseekers.com / admin123<br>
        User: user@jobseekers.com / user123
      </div>
    </div>
  </div>
</body>
</html>
