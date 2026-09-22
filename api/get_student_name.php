<?php

error_reporting(0);
ini_set('display_errors', 0);

header('Content-Type: application/json');
session_start();
include("../config/db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !='student'){
    echo json_encode(['success' => false, 'message'=> 'Not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT full_name FROM student WHERE user_id= '$user_id'";
$result = $conn->query($sql);

if($result && $result ->num_rows > 0){
    $row = $result->fetch_assoc();
    echo json_encode(['success' => true, 'name' => $row['full_name']]);
}else{
    echo json_encode(['success' => false, 'message'=> 'Student not Found']);
}
?>