<?php
require_once 'session.php';
require_once 'config.php';
requireLogin();

$user = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home - Job Seekers</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="styles.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
  <!-- Header -->
  <header>
    <h1>J*b Seekers</h1>
    <nav>
      <a href="home.php" title="Home"><i class="fas fa-house"></i></a>
      <a href="profile.php" title="Profile"><i class="fas fa-user"></i></a>
      <a href="logout.php" title="Logout"><i class="fas fa-sign-out-alt"></i></a>
    </nav>
  </header>

  <div class="layout">
    <aside>
      <h3>Job title or Keyword</h3>
      <input type="text" id="searchKeyword" placeholder="Search..." onkeyup="filterJobs()">
      
      <h3>Location</h3>
      <input type="text" id="searchLocation" placeholder="Enter location..." onkeyup="filterJobs()">
      
      <h3>Type</h3>
      <select id="typeFilter" onchange="filterJobs()">
        <option value="">All</option>
        <option value="Full-Time">Full-Time</option>
        <option value="Part-Time">Part-Time</option>
      </select>
      
      <div style="margin-top:20px;">
        <p>Create a professional resume<br>with the help of AI</p>
        <a href="https://chatgpt.com/" target="_blank">
          <button>Create Resume</button>
        </a>
      </div>

      <!-- Admin Only: Add Job Button -->
      <?php if (isAdmin()): ?>
      <div style="margin-top:20px;">
        <button onclick="openAddJobModal()">+ Add New Job</button>
      </div>
      <?php endif; ?>
    </aside>

    <main>
      <h2 style="color:#00ff33">Jobs</h2>
      <div class="jobs-list" id="jobsList">
        <!-- Jobs will be loaded here dynamically -->
      </div>
      <div class="expand">▼ Expand ▼</div>
      <div class="chart">
        <h3>Unemployment Rate in Indonesia (2015–2024)</h3>
        <canvas id="unemploymentChart"></canvas>
      </div>
    </main>

    <div class="right-side">
      <div class="card-box">
        <h3>Job Tips</h3>
        <p>🔹 Update your resume regularly.<br>🔹 Highlight skills relevant to the job.<br>🔹 Practice common interview questions.</p>
      </div>
      <div class="card-box">
        <h3>Company Spotlight</h3>
        <p><strong>GreenTech Inc.</strong><br>Hiring 20+ roles in renewable energy sector.<br>Location: Remote + On-site (Jakarta).</p>
      </div>
      <div class="card-box">
        <h3>Quick Stats</h3>
        <ul>
          <li>Jobs Available: <b id="jobCount">0</b></li>
          <li>Companies: <b>300+</b></li>
          <li>Active Users: <b>5400</b></li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Add/Edit Job Modal -->
  <div id="jobModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeJobModal()">&times;</span>
      <h2 id="modalTitle">Add New Job</h2>
      <form id="jobForm" onsubmit="handleJobSubmit(event)">
        <input type="hidden" id="jobId">
        
        <label>Job Title</label>
        <input type="text" id="jobTitle" required>
        
        <label>Company</label>
        <input type="text" id="jobCompany" required>
        
        <label>Type</label>
        <select id="jobType" required>
          <option value="Full-Time">Full-Time</option>
          <option value="Part-Time">Part-Time</option>
        </select>
        
        <label>Location</label>
        <input type="text" id="jobLocation" required>
        
        <label>Description</label>
        <textarea id="jobDescription" rows="8" required></textarea>
        
        <div style="display:flex; gap:10px; margin-top:20px;">
          <button type="submit" class="btn-primary">Save Job</button>
          <button type="button" onclick="closeJobModal()" class="btn-secondary">Cancel</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    const isAdmin = <?php echo isAdmin() ? 'true' : 'false'; ?>;
    const userId = <?php echo $user['id']; ?>;
  </script>
  <script src="home-php.js"></script>
</body>
</html>
