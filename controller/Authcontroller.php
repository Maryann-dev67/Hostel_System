<?php
session_start();
include("../config/db.php");

if(isset($_POST['login'])){

    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    if(empty($email) || empty($password)){
        header("Location: ../pages/login.html?error=empty_fields");
        exit();
    }
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        header("Location: ../pages/login.html?error=invalid_email");
        exit();
    }
    if(strlen($password) < 8){
        header("Location: ../pages/login.html?error=short_password");
        exit();
    }

    // Check if email exists
    $stmt = $conn->prepare("SELECT * FROM main_user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0){
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password_hash'])){
            $update_stmt = $conn->prepare("UPDATE main_user SET last_login = NOW() WHERE user_id = ?");
            $update_stmt->bind_param("i", $user['user_id']);
            $update_stmt->execute();
            $update_stmt->close();

            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role'] = $user['role'];

            if($user['role'] == 'student'){
                header("Location: ../pages/student_dashboard.html");
                exit();
            } else if($user['role'] == 'admin'){
                header("Location: ../views/admin_dashboard.php");
                exit();
            }
        } else {
            header("Location: ../pages/login.html?error=wrong_password");
            exit();
        }
    } else {
        header("Location: ../pages/login.html?error=user_not_found");
        exit();
    }
    $stmt->close();
}
// REGISTER FUNCTION
if(isset($_POST['register'])){

    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'] ?? 'student';
    // Check if fields are empty
    if(empty($email) || empty($password) || empty($confirm_password)){
        header("Location: ../pages/register.html?error=empty_fields");
        exit();
    }

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        header("Location: ../pages/register.html?error=invalid_email");
        exit();
    }
    if(strlen($password) < 8){
        header("Location: ../pages/register.html?error=short_password");
        exit();
    }
    if(!preg_match('/[A-Z]/', $password)){
        header("Location: ../pages/register.html?error=no_uppercase");
        exit();
    }
    if(!preg_match('/[!@#$%^&*()\-_=+{};:,<.>]/', $password)){
        header("Location: ../pages/register.html?error=no_special");
        exit();
    }

    if($password !== $confirm_password){
        header("Location: ../pages/register.html?error=password_mismatch");
        exit();
    }
    if($role == 'admin'){
        header("Location: ../pages/register.html?error=admin_not_allowed");
        exit();
    }
    // Check if email already exists
    $check_stmt = $conn->prepare("SELECT user_id FROM main_user WHERE email = ?");
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if($check_result->num_rows > 0){
        header("Location: ../pages/register.html?error=email_exists");
        exit();
    }
    $check_stmt->close();

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user
    $insert_stmt = $conn->prepare("INSERT INTO main_user (email, password_hash, role) VALUES (?, ?, ?)");
    $insert_stmt->bind_param("sss", $email, $hashed_password, $role);

    if($insert_stmt->execute()){
        header("Location: ../pages/login.html?success=registered");
        exit();
    } else {
        header("Location: ../pages/register.html?error=registration_failed");
        exit();
    }
    $insert_stmt->close();
}
?>