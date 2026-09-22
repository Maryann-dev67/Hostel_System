<?php 
session_start();
include("../config/db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../pages/login.html");
    exit();
}

// Check if form was submitted
if(isset($_POST['save_admin_profile'])){
    $user_id = $_SESSION['user_id'];
    $full_name = trim($_POST['full_name']);
    $department = trim($_POST['department']);
    $phone = trim($_POST['phone']);
    
    // Validate input
    if(empty($full_name) || empty($department)) {
        header("Location: ../views/admin_profile.php?error=Please fill in all required fields");
        exit();
    }

    if(!preg_match('/^(0|254|\\+254)?[7][0-9]{8}$/', $phone)){
        echo "Error: Please enter a valid phone number (e.g., 0712345678 or +254712345678)";
        exit();
    }
    
    // Check if admin profile already exist
    $check_stmt = $conn->prepare("SELECT admin_id FROM administrator WHERE user_id = ?");
    $check_stmt->bind_param("i", $user_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if($check_result->num_rows > 0){
        // update the existing profile
        $update_stmt = $conn->prepare("UPDATE administrator SET full_name = ?, department = ?, phone = ? WHERE user_id = ?");
        $update_stmt->bind_param("sssi", $full_name, $department, $phone, $user_id);
        
        if($update_stmt->execute()){
            header("Location: ../views/admin_dashboard.php?success=profile_updated");
            exit();
        } else {
            echo "Error updating: " . $update_stmt->error;
        }
        $update_stmt->close();
    } else {
        // INSERT new profile
        $insert_stmt = $conn->prepare("INSERT INTO administrator (user_id, full_name, department, phone) VALUES (?, ?, ?, ?)");
        $insert_stmt->bind_param("isss", $user_id, $full_name, $department, $phone);
        
        if($insert_stmt->execute()){
            header("Location: ../views/admin_dashboard.php?success=profile_saved");
            exit();
        } else {
            echo "Error inserting: " . $insert_stmt->error;
        }
        $insert_stmt->close();
    }
    $check_stmt->close();
} else {
    // If accessed directly
    header("Location: ../pages/admin_profile.php");
    exit();
}
?>