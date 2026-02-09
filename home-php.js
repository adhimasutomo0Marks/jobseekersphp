// Home page functionality for PHP version

let allJobs = [];

// Load all jobs on page load
function loadJobs() {
  fetch('job_operations.php?action=get_all')
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        allJobs = data.jobs;
        displayJobs(allJobs);
        updateJobCount(allJobs.length);
      }
    })
    .catch(error => console.error('Error loading jobs:', error));
}

// Display jobs
function displayJobs(jobs) {
  const jobsList = document.getElementById('jobsList');
  if (!jobsList) return;
  
  jobsList.innerHTML = '';
  
  jobs.forEach(job => {
    const jobCard = createJobCard(job);
    jobsList.appendChild(jobCard);
  });
}

// Create job card element
function createJobCard(job) {
  const card = document.createElement('div');
  card.className = 'job-card';
  
  card.innerHTML = `
    <h4>${escapeHtml(job.title)}</h4>
    <p>${escapeHtml(job.company)} – ${escapeHtml(job.type)} – ${escapeHtml(job.location)}</p>
    ${isAdmin ? `
      <div class="job-card-actions">
        <button class="btn-edit" onclick="editJobFromCard(${job.id}); event.stopPropagation();">Edit</button>
        <button class="btn-delete" onclick="deleteJobFromCard(${job.id}); event.stopPropagation();">Delete</button>
      </div>
    ` : ''}
  `;
  
  // Navigate to job detail on click
  card.addEventListener('click', () => {
    window.location.href = `job-detail.php?id=${job.id}`;
  });
  
  return card;
}

// Filter jobs based on search criteria
function filterJobs() {
  const keyword = document.getElementById('searchKeyword')?.value.toLowerCase() || '';
  const location = document.getElementById('searchLocation')?.value.toLowerCase() || '';
  const type = document.getElementById('typeFilter')?.value || '';
  
  const filteredJobs = allJobs.filter(job => {
    const matchesKeyword = !keyword || 
      job.title.toLowerCase().includes(keyword) || 
      job.company.toLowerCase().includes(keyword);
    
    const matchesLocation = !location || 
      job.location.toLowerCase().includes(location);
    
    const matchesType = !type || job.type === type;
    
    return matchesKeyword && matchesLocation && matchesType;
  });
  
  displayJobs(filteredJobs);
  updateJobCount(filteredJobs.length);
}

// Update job count
function updateJobCount(count) {
  const jobCount = document.getElementById('jobCount');
  if (jobCount) {
    jobCount.textContent = count;
  }
}

// Open add job modal
function openAddJobModal() {
  const modal = document.getElementById('jobModal');
  const modalTitle = document.getElementById('modalTitle');
  const form = document.getElementById('jobForm');
  
  modalTitle.textContent = 'Add New Job';
  form.reset();
  document.getElementById('jobId').value = '';
  
  modal.style.display = 'block';
}

// Edit job from card
function editJobFromCard(jobId) {
  const job = allJobs.find(j => j.id == jobId);
  if (!job) return;
  
  const modal = document.getElementById('jobModal');
  const modalTitle = document.getElementById('modalTitle');
  
  modalTitle.textContent = 'Edit Job';
  
  document.getElementById('jobId').value = job.id;
  document.getElementById('jobTitle').value = job.title;
  document.getElementById('jobCompany').value = job.company;
  document.getElementById('jobType').value = job.type;
  document.getElementById('jobLocation').value = job.location;
  document.getElementById('jobDescription').value = job.description.replace(/<br>/g, '\n').replace(/<[^>]*>/g, '');
  
  modal.style.display = 'block';
}

// Delete job from card
function deleteJobFromCard(jobId) {
  if (!confirm('Are you sure you want to delete this job?')) return;
  
  fetch('job_operations.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: `action=delete&id=${jobId}`
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      alert('Job deleted successfully!');
      loadJobs();
    } else {
      alert(data.message || 'Failed to delete job');
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert('An error occurred');
  });
}

// Close job modal
function closeJobModal() {
  const modal = document.getElementById('jobModal');
  modal.style.display = 'none';
}

// Handle job form submission
function handleJobSubmit(event) {
  event.preventDefault();
  
  const jobId = document.getElementById('jobId').value;
  const action = jobId ? 'update' : 'create';
  
  const formData = new URLSearchParams();
  formData.append('action', action);
  if (jobId) formData.append('id', jobId);
  formData.append('title', document.getElementById('jobTitle').value);
  formData.append('company', document.getElementById('jobCompany').value);
  formData.append('type', document.getElementById('jobType').value);
  formData.append('location', document.getElementById('jobLocation').value);
  formData.append('description', document.getElementById('jobDescription').value.replace(/\n/g, '<br>'));
  
  fetch('job_operations.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: formData.toString()
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      alert(jobId ? 'Job updated successfully!' : 'Job created successfully!');
      closeJobModal();
      loadJobs();
    } else {
      alert(data.message || 'Operation failed');
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert('An error occurred');
  });
}

// Initialize chart
function initChart() {
  const ctx = document.getElementById('unemploymentChart');
  if (!ctx) return;
  
  new Chart(ctx.getContext('2d'), {
    type: 'bar',
    data: {
      labels: [2015, 2016, 2017, 2018, 2019, 2020, 2021, 2022, 2023, 2024],
      datasets: [{
        label: 'Unemployment Rate (%)',
        data: [6, 5.8, 5.5, 5.3, 5, 6.5, 6, 5.8, 5.5, 5.2],
        backgroundColor: '#00ff33'
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
}

// Helper function to escape HTML
function escapeHtml(text) {
  const map = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#039;'
  };
  return text.replace(/[&<>"']/g, m => map[m]);
}

// Close modal when clicking outside
window.onclick = function(event) {
  const modal = document.getElementById('jobModal');
  if (event.target == modal) {
    closeJobModal();
  }
}

// Run on page load
document.addEventListener('DOMContentLoaded', function() {
  loadJobs();
  initChart();
  
  // Check if editing from URL parameter
  const urlParams = new URLSearchParams(window.location.search);
  const editId = urlParams.get('edit');
  if (editId && isAdmin) {
    // Wait for jobs to load first
    setTimeout(() => editJobFromCard(editId), 500);
  }
});
