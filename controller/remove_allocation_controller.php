<?php
session_start();
include("../config/db.php");

// Check admin login
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../views/login.php");
    exit();
}

// Check if form was submitted
if(isset($_POST['remove_allocation'])){
    $allocation_id = $_POST['allocation_id'];
    
    // Get student_id and room_id before deleting
    $info_stmt = $conn->prepare("SELECT student_id, room_id FROM allocation WHERE allocation_id = ?");
    $info_stmt->bind_param("i", $allocation_id);
    $info_stmt->execute();
    $info_result = $info_stmt->get_result();
    $info = $info_result->fetch_assoc();
    $info_stmt->close();
    
    if(!$info){
        header("Location: ../pages/view_allocations.php?error=not_found");
        exit();
    }
    
    $student_id = $info['student_id'];
    $room_id = $info['room_id'];
    
    // STEP 2: Get user_id for notification
    $user_stmt = $conn->prepare("SELECT user_id FROM student WHERE student_id = ?");
    $user_stmt->bind_param("i", $student_id);
    $user_stmt->execute();
    $user_result = $user_stmt->get_result();
    $user_row = $user_result->fetch_assoc();
    $user_id = $user_row['user_id'];
    $user_stmt->close();
    
    // STEP 3: Get room number
    $room_stmt = $conn->prepare("SELECT room_number FROM room WHERE room_id = ?");
    $room_stmt->bind_param("i", $room_id);
    $room_stmt->execute();
    $room_result = $room_stmt->get_result();
    $room_row = $room_result->fetch_assoc();
    $room_number = $room_row['room_number'];
    $room_stmt->close();
    
    // STEP 4: Delete the allocation
    $delete_stmt = $conn->prepare("DELETE FROM allocation WHERE allocation_id = ?");
    $delete_stmt->bind_param("i", $allocation_id);
    
    if($delete_stmt->execute()){
        
        // STEP 5: Send simple notification
        $message = "Your room allocation (Room " . $room_number . ") has been removed. Please vacate the room. To be re-allocated, update your preferences and submit them again.";
        
        $notify_stmt = $conn->prepare("INSERT INTO notification (user_id, message_type, message_content, date_sent, status) VALUES (?, 'allocation_removed', ?, NOW(), 'unread')");
        $notify_stmt->bind_param("is", $user_id, $message);
        $notify_stmt->execute();
        $notify_stmt->close();
        
        $_SESSION['success'] = "Student removed from room. Notification sent.";
        header("Location: ../views/view_allocations.php?success=removed");
        exit();
        
    } else {
        $_SESSION['error'] = "Failed to remove allocation: " . $delete_stmt->error;
        header("Location: ../views/view_allocations.php?error=failed");
        exit();
    }
    
    $delete_stmt->close();
} else {
    header("Location: ../views/view_allocations.php");
    exit();
}
?>