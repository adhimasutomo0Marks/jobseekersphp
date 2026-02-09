<?php
require_once 'session.php';
require_once 'config.php';
requireLogin();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $jobId = intval($_POST['job_id']);
    $userId = $_SESSION['user_id'];
    
    // Check if already applied
    $checkSql = "SELECT * FROM applications WHERE job_id = $jobId AND user_id = $userId";
    $checkResult = mysqli_query($conn, $checkSql);
    
    if (mysqli_num_rows($checkResult) > 0) {
        echo json_encode(['success' => false, 'message' => 'You have already applied for this job']);
        exit();
    }
    
    // Insert application
    $sql = "INSERT INTO applications (job_id, user_id, status) VALUES ($jobId, $userId, 'pending')";
    
    if (mysqli_query($conn, $sql)) {
        echo json_encode(['success' => true, 'message' => 'Application submitted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to submit application']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
