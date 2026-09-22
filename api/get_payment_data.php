<?php
header('Content-Type: application/json');
session_start();
include("../config/db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student'){
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Get student's allocation and fee details
$sql = "SELECT a.allocation_id, a.period, r.room_number, r.room_type, r.fee_per_semester,
               COALESCE(SUM(p.amount), 0) as paid,
               (r.fee_per_semester - COALESCE(SUM(p.amount), 0)) as balance
        FROM allocation a
        JOIN room r ON a.room_id = r.room_id
        LEFT JOIN payments p ON a.allocation_id = p.allocation_id
        WHERE a.student_id = (SELECT student_id FROM student WHERE user_id = '$user_id')
        AND a.status = 'active'
        GROUP BY a.allocation_id";

$result = $conn->query($sql);

if($result && $result->num_rows > 0){
    $data = $result->fetch_assoc();
    echo json_encode(['success' => true, 'data' => $data]);
} else {
    echo json_encode(['success' => false, 'message' => 'No allocation found']);
}
?>