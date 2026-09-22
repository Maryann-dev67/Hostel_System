<?php
header('Content-Type: application/json');
session_start();
include("../config/db.php");

//check if user is logged in
if(!isset($_SESSION['user_id'])){
    echo json_encode(['success' =>false, 'message' =>'Not Logged in']);
    exit();
}
$user_id =$_SESSION['user_id'];

//get all notifications for this user from the newest
$sql = "SELECT * FROM notification WHERE user_id = '$user_id'
ORDER BY date_sent DESC";

$result = $conn->query($sql);

$notifications = [];
while($row = $result->fetch_assoc()){
    $notifications[]= $row;
}

echo json_encode(['success' =>true, 'notifications'=> $notifications]);
?>