<?php
header('Content-Type: application/json');
session_start();
include("../config/db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student'){
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Get allocation_id 
$alloc_query = $conn->query("
    SELECT a.allocation_id 
    FROM allocation a
    WHERE a.student_id = (SELECT student_id FROM student WHERE user_id = '$user_id')
    AND a.status = 'active'
");
//check if allocation exits
if($alloc_query && $alloc_query->num_rows > 0){
    $alloc = $alloc_query->fetch_assoc();
    $allocation_id = $alloc['allocation_id'];
    
    $history = $conn->query("
        SELECT * FROM payments 
        WHERE allocation_id = '$allocation_id' 
        ORDER BY payment_date DESC
    ");
    
    $payments = [];
    while($row = $history->fetch_assoc()){
        $payments[] = $row;
    }
    
    echo json_encode(['success' => true, 'payments' => $payments]);
} else {
    echo json_encode(['success' => true, 'payments' => []]);
}
?>