<?php
require_once 'session.php';
require_once 'config.php';
requireLogin();

header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

// READ - Get all jobs
if ($action == 'get_all') {
    $sql = "SELECT * FROM jobs ORDER BY date_posted DESC";
    $result = mysqli_query($conn, $sql);
    
    $jobs = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $jobs[] = $row;
    }
    
    echo json_encode(['success' => true, 'jobs' => $jobs]);
    exit();
}

// READ - Get single job
if ($action == 'get_one') {
    $id = intval($_POST['id'] ?? $_GET['id'] ?? 0);
    
    $sql = "SELECT * FROM jobs WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    
    if ($row = mysqli_fetch_assoc($result)) {
        echo json_encode(['success' => true, 'job' => $row]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Job not found']);
    }
    exit();
}

// CREATE - Add new job (Admin only)
if ($action == 'create') {
    requireAdmin();
    
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $company = mysqli_real_escape_string($conn, $_POST['company']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $userId = $_SESSION['user_id'];
    
    $sql = "INSERT INTO jobs (title, company, type, location, description, created_by) 
            VALUES ('$title', '$company', '$type', '$location', '$description', $userId)";
    
    if (mysqli_query($conn, $sql)) {
        $jobId = mysqli_insert_id($conn);
        echo json_encode(['success' => true, 'message' => 'Job created successfully', 'id' => $jobId]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to create job']);
    }
    exit();
}

// UPDATE - Edit job (Admin only)
if ($action == 'update') {
    requireAdmin();
    
    $id = intval($_POST['id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $company = mysqli_real_escape_string($conn, $_POST['company']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    
    $sql = "UPDATE jobs SET 
            title = '$title',
            company = '$company',
            type = '$type',
            location = '$location',
            description = '$description'
            WHERE id = $id";
    
    if (mysqli_query($conn, $sql)) {
        echo json_encode(['success' => true, 'message' => 'Job updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update job']);
    }
    exit();
}

// DELETE - Remove job (Admin only)
if ($action == 'delete') {
    requireAdmin();
    
    $id = intval($_POST['id']);
    
    $sql = "DELETE FROM jobs WHERE id = $id";
    
    if (mysqli_query($conn, $sql)) {
        echo json_encode(['success' => true, 'message' => 'Job deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete job']);
    }
    exit();
}

echo json_encode(['success' => false, 'message' => 'Invalid action']);
?>
