<?php
session_start();
include("../config/db.php");

// Check if logged in
if(!isset($_SESSION['user_id'])){
    echo "Error: User not logged in";
    exit();
}

$user_id = $_SESSION['user_id'];

// GET STUDENT ID 
$student_stmt = $conn->prepare("SELECT student_id FROM student WHERE user_id = ?");
$student_stmt->bind_param("i", $user_id);
$student_stmt->execute();
$student_result = $student_stmt->get_result();
$row = $student_result->fetch_assoc();
$student_stmt->close();

if(!$row) {
    echo "Error: Please complete your profile first";
    exit();
}

$student_id = $row['student_id'];

// GET FORM DATA
$sleep = $_POST['sleep_schedule'];
$clean = $_POST['cleanliness_level'];
$study = $_POST['study_habits'];
$noise = $_POST['noise_tolerance'];
$social = $_POST['social_level'];
$period = $_POST['preferred_period'];
$preferred_room_type = strtolower(trim($_POST['preferred_room_type']));

// Validate
if($preferred_room_type != 'single' && $preferred_room_type != 'double'){
    $preferred_room_type = 'double';
}
if($period != 'Semester 1' && $period != 'Semester 2'){
    $period = 'Semester 1';
}

// CHECK IF PREFERENCES EXIST 
$check_stmt = $conn->prepare("SELECT preference_id FROM preference WHERE student_id = ?");
$check_stmt->bind_param("i", $student_id);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if($check_result->num_rows > 0){
    // UPDATE EXISTING PREFERENCES 
    $update_stmt = $conn->prepare("UPDATE preference SET 
        sleep_schedule = ?,
        cleanliness_level = ?,
        study_habits = ?,
        noise_tolerance = ?,
        social_level = ?,
        preferred_room_type = ?,
        preferred_period = ?,
        submission_date = NOW()
        WHERE student_id = ?
    ");
    $update_stmt->bind_param("sssssssi", 
        $sleep, $clean, $study, $noise, $social, 
        $preferred_room_type, $period, $student_id
    );

    if($update_stmt->execute()){
        echo "Preferences updated successfully!";
    } else {
        echo "Error updating: " . $update_stmt->error;
    }
    $update_stmt->close();

} else {
    // INSERT NEW PREFERENCES
    $insert_stmt = $conn->prepare("INSERT INTO preference 
        (student_id, sleep_schedule, cleanliness_level, study_habits, noise_tolerance, social_level, preferred_room_type, preferred_period)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $insert_stmt->bind_param("isssssss", 
        $student_id, $sleep, $clean, $study, $noise, $social, 
        $preferred_room_type, $period
    );

    if($insert_stmt->execute()){
        echo "Preferences saved successfully!";
    } else {
        echo "Error saving: " . $insert_stmt->error;
    }
    $insert_stmt->close();
}

$check_stmt->close();
?>