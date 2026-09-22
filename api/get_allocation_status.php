<?php
header('Content-Type: application/json');
session_start();
include("../config/db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !='student'){
    echo json_encode(['needs_confirmation' => false]);
    exit();
}

$user_id = $_SESSION['user_id'];
//Get student_id
$student= $conn->query("SELECT student_id FROM student WHERE user_id = '$user_id'");
if(!$student || $student->num_rows == 0){
    echo json_encode(['needs_confirmation'=> false]);
    exit();
}

$student_row = $student->fetch_assoc();
$student_id = $student_row['student_id'];
//Check if student has active allocation that need confirmation
$allocation = $conn->query("SELECT student_confirmed FROM allocation WHERE student_id = '$student_id' AND status = 'active'");

if($allocation && $allocation->num_rows > 0){
    $row = $allocation->fetch_assoc();
    $needs = ($row['student_confirmed'] == 'pending');
    echo json_encode(['needs_confirmation' => $needs]);
}else{
    echo json_encode(['needs_confirmation' => false]);
}
?>