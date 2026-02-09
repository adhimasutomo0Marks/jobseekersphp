<?php
require_once 'session.php';
require_once 'config.php';
requireLogin();

$user = getCurrentUser();
$userId = $user['id'];

// Fetch user profile from database
$sql = "SELECT u.*, p.about, p.skills, p.linkedin, p.github, p.photo 
        FROM users u 
        LEFT JOIN user_profiles p ON u.id = p.user_id 
        WHERE u.id = $userId";
$result = mysqli_query($conn, $sql);
$profile = mysqli_fetch_assoc($result);

$success = '';
$error = '';

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $about = mysqli_real_escape_string($conn, $_POST['about']);
    $skills = mysqli_real_escape_string($conn, $_POST['skills']);
    $linkedin = mysqli_real_escape_string($conn, $_POST['linkedin']);
    $github = mysqli_real_escape_string($conn, $_POST['github']);
    $photo = mysqli_real_escape_string($conn, $_POST['photo']);
    
    // Update user name
    $updateUserSql = "UPDATE users SET name = '$name' WHERE id = $userId";
    mysqli_query($conn, $updateUserSql);
    
    // Update or insert profile
    $checkProfileSql = "SELECT * FROM user_profiles WHERE user_id = $userId";
    $checkResult = mysqli_query($conn, $checkProfileSql);
    
    if (mysqli_num_rows($checkResult) > 0) {
        // Update existing profile
        $updateProfileSql = "UPDATE user_profiles SET 
                            about = '$about',
                            skills = '$skills',
                            linkedin = '$linkedin',
                            github = '$github',
                            photo = '$photo'
                            WHERE user_id = $userId";
        mysqli_query($conn, $updateProfileSql);
    } else {
        // Insert new profile
        $insertProfileSql = "INSERT INTO user_profiles (user_id, about, skills, linkedin, github, photo) 
                            VALUES ($userId, '$about', '$skills', '$linkedin', '$github', '$photo')";
        mysqli_query($conn, $insertProfileSql);
    }
    
    // Update session name
    $_SESSION['user_name'] = $name;
    
    $success = 'Profile updated successfully!';
    
    // Refresh profile data
    $result = mysqli_query($conn, $sql);
    $profile = mysqli_fetch_assoc($result);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile - Job Seekers</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <header>
    <h1>J*b Seekers</h1>
    <nav>
      <a href="home.php" title="Home"><i class="fas fa-house"></i></a>
      <a href="profile.php" title="Profile"><i class="fas fa-user"></i></a>
      <a href="logout.php" title="Logout"><i class="fas fa-sign-out-alt"></i></a>
    </nav>
  </header>

  <div class="profile-box">
    <h2 style="color:#00ff33">MY PROFILE</h2>

    <?php if ($success): ?>
      <div style="background:#00ff33; color:#000; padding:10px; border-radius:5px; margin-bottom:15px;">
        <?php echo $success; ?>
      </div>
    <?php endif; ?>

    <?php if ($error): ?>
      <div style="background:#ff4444; padding:10px; border-radius:5px; margin-bottom:15px;">
        <?php echo $error; ?>
      </div>
    <?php endif; ?>

    <div class="profile-photo">
      <img id="previewPhoto" src="<?php echo !empty($profile['photo']) ? htmlspecialchars($profile['photo']) : "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='120' height='120'%3E%3Crect fill='%23444' width='120' height='120'/%3E%3Ctext x='50%25' y='50%25' font-size='48' fill='%2300ff33' text-anchor='middle' dy='.3em'%3E👤%3C/text%3E%3C/svg%3E"; ?>" alt="Profile Photo">
      <input type="file" accept="image/*" onchange="previewProfilePhoto(event)">
    </div>

    <form method="POST" action="">
      <input type="hidden" name="photo" id="photoData" value="<?php echo htmlspecialchars($profile['photo'] ?? ''); ?>">
      
      <label>Name</label>
      <input type="text" name="name" value="<?php echo htmlspecialchars($profile['name']); ?>" required>

      <label>Email</label>
      <input type="email" value="<?php echo htmlspecialchars($profile['email']); ?>" readonly>

      <label>About Me</label>
      <textarea name="about" rows="4" placeholder="Write something..."><?php echo htmlspecialchars($profile['about'] ?? ''); ?></textarea>

      <label>Skills</label>
      <input type="text" name="skills" value="<?php echo htmlspecialchars($profile['skills'] ?? ''); ?>" placeholder="e.g. JavaScript, UI/UX, SEO">

      <label>LinkedIn</label>
      <input type="url" name="linkedin" value="<?php echo htmlspecialchars($profile['linkedin'] ?? ''); ?>" placeholder="https://linkedin.com/in/username">

      <label>GitHub</label>
      <input type="url" name="github" value="<?php echo htmlspecialchars($profile['github'] ?? ''); ?>" placeholder="https://github.com/username">

      <button type="submit" class="btn-primary">Save Profile</button>
    </form>
  </div>

  <script>
    function previewProfilePhoto(event) {
      const file = event.target.files[0];
      if (!file) return;
      
      const reader = new FileReader();
      reader.onload = function(e) {
        document.getElementById('previewPhoto').src = e.target.result;
        document.getElementById('photoData').value = e.target.result;
      };
      reader.readAsDataURL(file);
    }
  </script>
</body>
</html>
