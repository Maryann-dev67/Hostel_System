<?php
session_start();
include("../config/db.php");

$_SESSION['error'] = '';
$_SESSION['success'] = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $student_id = $_POST['student_id'];
    $room_id = $_POST['room_id'];
    $academic_year = $_POST['academic_year'];
    $allocation_date = date("Y-m-d");
    // check if student already has a room?
    $check_stmt = $conn->prepare("SELECT * FROM allocation WHERE student_id = ? AND status = 'active'");
    $check_stmt->bind_param("i", $student_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error'] = "Student already has a room!";
        header("Location: ../pages/allocate_room.php");
        exit();
    }
    $check_stmt->close();

    // check if Room has capacity
    $count_stmt = $conn->prepare("SELECT COUNT(*) AS total FROM allocation WHERE room_id = ? AND status = 'active'");
    $count_stmt->bind_param("i", $room_id);
    $count_stmt->execute();
    $countResult = $count_stmt->get_result();
    $countRow = $countResult->fetch_assoc();
    $current_students = $countRow['total'];
    $count_stmt->close();

    $capacity_stmt = $conn->prepare("SELECT capacity FROM room WHERE room_id = ?");
    $capacity_stmt->bind_param("i", $room_id);
    $capacity_stmt->execute();
    $capacityResult = $capacity_stmt->get_result();
    $capacityRow = $capacityResult->fetch_assoc();
    $capacity = $capacityRow['capacity'];
    $capacity_stmt->close();

    if ($current_students >= $capacity) {
        $_SESSION['error'] = "Room is already full!";
        header("Location: ../views/allocate_room.php");
        exit();
    }
    //Get ALL student preferences
    $pref_stmt = $conn->prepare("SELECT * FROM preference WHERE student_id = ?");
    $pref_stmt->bind_param("i", $student_id);
    $pref_stmt->execute();
    $prefResult = $pref_stmt->get_result();
    $pref = $prefResult->fetch_assoc();
    $pref_stmt->close();

    if (!$pref) {
        $_SESSION['error'] = "Student has not set preferences.";
        header("Location: ../views/allocate_room.php");
        exit();
    }

    // Get the period from preferences
    $period = $pref['preferred_period'];
    //Insert the allocation
    $insert_stmt = $conn->prepare("INSERT INTO allocation (student_id, room_id, allocation_date, status, academic_year, student_confirmed, period) VALUES (?, ?, ?, 'active', ?, 'pending', ?)");
    $insert_stmt->bind_param("iisss", $student_id, $room_id, $allocation_date, $academic_year, $period);

    if ($insert_stmt->execute()) {
        // NOTIFICATION: Send to student
        $user_stmt = $conn->prepare("SELECT user_id FROM student WHERE student_id = ?");
        $user_stmt->bind_param("i", $student_id);
        $user_stmt->execute();
        $userResult = $user_stmt->get_result();
        $user = $userResult->fetch_assoc();
        $user_id = $user['user_id'];
        $user_stmt->close();

        $room_stmt = $conn->prepare("SELECT room_number FROM room WHERE room_id = ?");
        $room_stmt->bind_param("i", $room_id);
        $room_stmt->execute();
        $roomResult = $room_stmt->get_result();
        $room = $roomResult->fetch_assoc();
        $room_stmt->close();

        $message = "You have been allocated to room " . $room['room_number'] . " for " . $period . " of the academic year " . $academic_year . ".";

        $notify_stmt = $conn->prepare("INSERT INTO notification (user_id, message_type, message_content, date_sent, status) VALUES (?, 'allocation', ?, NOW(), 'unread')");
        $notify_stmt->bind_param("is", $user_id, $message);
        $notify_stmt->execute();
        $notify_stmt->close();

        $_SESSION['success'] = "Room allocated successfully!";
        header("Location: ../views/allocate_room.php?success=1");
        exit();
    } else {
        $_SESSION['error'] = "Error: " . $insert_stmt->error;
        header("Location: ../views/allocate_room.php");
        exit();
    }
    $insert_stmt->close();
}
?>