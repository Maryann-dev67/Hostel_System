<?php
header('Content-Type: application/json');
session_start();
include("../config/db.php");

if(!isset($_SESSION['user_id'])){
    echo json_encode(['success' => false]);
    exit();
}

$user_id = $_SESSION['user_id'];

// Mark all unread notifications as read
$sql = "UPDATE notification SET status = 'read' WHERE user_id = '$user_id' AND status = 'unread'";
$conn->query($sql);

echo json_encode(['success' => true]);
?>