<?php
require_once 'session.php';
require_once 'config.php';
requireLogin();

$user = getCurrentUser();

// Get job ID from URL
$jobId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($jobId == 0) {
    header("Location: home.php");
    exit();
}

// Fetch job from database
$sql = "SELECT * FROM jobs WHERE id = $jobId";
$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
    header("Location: home.php");
    exit();
}

$job = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($job['title']); ?> - Job Seekers</title>
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

  <div class="container">
    <button onclick="window.location.href='home.php'" class="btn-back">⬅ Back</button>
    
    <div class="job-detail">
      <h2 style="color:#00ff33"><?php echo htmlspecialchars($job['title']); ?></h2>
      <p><strong>Company:</strong> <?php echo htmlspecialchars($job['company']); ?></p>
      <p><strong>Type:</strong> <?php echo htmlspecialchars($job['type']); ?></p>
      <p><strong>Location:</strong> <?php echo htmlspecialchars($job['location']); ?></p>
      
      <h3>Description</h3>
      <div><?php echo $job['description']; ?></div>
      
      <div class="job-actions">
        <button class="btn-primary" onclick="applyForJob(<?php echo $job['id']; ?>)">Apply Now</button>
        
        <!-- Admin Only: Edit and Delete buttons -->
        <?php if (isAdmin()): ?>
        <div style="margin-top:15px;">
          <button class="btn-edit" onclick="window.location.href='home.php?edit=<?php echo $job['id']; ?>'">Edit Job</button>
          <button class="btn-delete" onclick="deleteJob(<?php echo $job['id']; ?>)">Delete Job</button>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <script>
    const isAdmin = <?php echo isAdmin() ? 'true' : 'false'; ?>;
    const userId = <?php echo $user['id']; ?>;
    const userName = '<?php echo addslashes($user['name']); ?>';
    const userEmail = '<?php echo addslashes($user['email']); ?>';
    
    function applyForJob(jobId) {
      if (confirm('Submit application for this job?')) {
        fetch('apply_job.php', {
          method: 'POST',
          headers: {'Content-Type': 'application/x-www-form-urlencoded'},
          body: 'job_id=' + jobId
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert('Application submitted successfully!\n\nWe will contact you at ' + userEmail);
          } else {
            alert(data.message || 'Application failed!');
          }
        });
      }
    }
    
    function deleteJob(jobId) {
      if (!confirm('Are you sure you want to delete this job?')) return;
      
      fetch('job_operations.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'action=delete&id=' + jobId
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert('Job deleted successfully!');
          window.location.href = 'home.php';
        } else {
          alert(data.message || 'Delete failed!');
        }
      });
    }
  </script>
</body>
</html>
