<?php
session_start();
include("../config/db.php");

if(isset($_POST['save_profile'])){
    $user_id = $_SESSION['user_id'];

    $full_name = $_POST['full_name'];
    $reg_no = $_POST['reg_no'];
    $gender = $_POST['gender'];
    $course = $_POST['course'];
    $year = $_POST['year_of_study'];
    $phone = $_POST['phone'];

    if(!preg_match('/^[0-9]{7}$/', $reg_no)){
        echo "Error: Registration number must be exactly 7 digits (e.g., 1234567)";
        exit();
    }

    if(!preg_match('/^(0|254|\\+254)?[7][0-9]{8}$/', $phone)){
        echo "Error: Please enter a valid phone number (e.g., 0712345678 or +254712345678)";
        exit();
    }

    // CHECK IF PROFILE EXISTS
    $check_stmt = $conn->prepare("SELECT student_id FROM student WHERE user_id = ?");
    $check_stmt->bind_param("i", $user_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if($check_result->num_rows > 0){
        // UPDATE EXISTING PROFILE 
        $update_stmt = $conn->prepare("UPDATE student SET full_name = ?, reg_no = ?, gender = ?,course = ?, year_of_study = ?,phone = ?
        WHERE user_id = ?
        ");
        $update_stmt->bind_param("ssssssi", $full_name, $reg_no, $gender, $course, $year, $phone, $user_id
        );

        if($update_stmt->execute()){
            echo "Profile updated successfully!";
        } else {
            echo "Error updating: " . $update_stmt->error;
        }
        $update_stmt->close();

    } else {
        // INSERT NEW PROFILE 
        $insert_stmt = $conn->prepare("INSERT INTO student 
            (user_id, full_name, reg_no, gender, course, year_of_study, phone)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $insert_stmt->bind_param("issssss", $user_id, $full_name, $reg_no, $gender, $course, $year, $phone
        );

        if($insert_stmt->execute()){
            echo "Profile saved successfully!";
        } else {
            echo "Error saving: " . $insert_stmt->error;
        }
        $insert_stmt->close();
    }

    $check_stmt->close();
}
?>