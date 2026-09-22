<?php
header('Content-Type: application/json');
session_start();
include("../config/db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student'){
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "UPDATE allocation a 
        JOIN student s ON a.student_id = s.student_id 
        SET a.student_confirmed = 'confirmed' 
        WHERE s.user_id = '$user_id' AND a.status = 'active'";

if($conn->query($sql)){
    if($conn->affected_rows > 0){
        echo json_encode(['success' => true, 'message' => 'Occupancy Confirmed!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No active allocation found for this student.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
}
?>