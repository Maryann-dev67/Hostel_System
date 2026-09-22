<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    echo "Unauthorized";
    exit();
}

if(isset($_POST['room_number'])){
    $room_number = trim($_POST['room_number']);
    $capacity = intval($_POST['capacity']);
    $room_type = trim($_POST['room_type']);
    
    if($room_type == 'Single' || $room_type == 'single'){
        $fee_per_semester = 15000;
    } elseif($room_type == 'Double' || $room_type == 'double'){
        $fee_per_semester = 10000;
    } else {
        $fee_per_semester = 10000;
    }

    $check = $conn->query("SELECT room_id FROM room WHERE room_number = '$room_number'");
    if($check->num_rows > 0){
        echo "Room number already exists!";
        exit();
    }
    
    $stmt = $conn->prepare("INSERT INTO room (room_number, capacity, room_type, fee_per_semester) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siss", $room_number, $capacity, $room_type, $fee_per_semester);
    
    if($stmt->execute()){
        echo "success";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Invalid request";
}
?>