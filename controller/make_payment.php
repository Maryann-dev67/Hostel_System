<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student'){
    echo "Not logged in";
    exit();
}

if(isset($_POST['allocation_id'])){
    $allocation_id = $_POST['allocation_id'];
    $amount = $_POST['amount'];
    $payment_method = $_POST['payment_method'];
    $payment_date = date("Y-m-d");
    
    // Get total paid so far
    $paid_query = $conn->query("SELECT COALESCE(SUM(amount),0) as total FROM payments WHERE allocation_id = '$allocation_id'");
    $paid = $paid_query->fetch_assoc()['total'];
    
    // Get total fee
    $fee_query = $conn->query("
        SELECT r.fee_per_semester 
        FROM allocation a 
        JOIN room r ON a.room_id = r.room_id 
        WHERE a.allocation_id = '$allocation_id'
    ");
    $fee = $fee_query->fetch_assoc()['fee_per_semester'];
    
    $new_total = $paid + $amount;
    $status = ($new_total >= $fee) ? 'paid' : 'partial';
    
    // Insert payment
    $insert = $conn->query("
        INSERT INTO payments (allocation_id, amount, payment_date, payment_method, status)
        VALUES ('$allocation_id', '$amount', '$payment_date', '$payment_method','$status')
    ");
    
    if($insert){
        echo "Payment recorded successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "Invalid request";
}
?>