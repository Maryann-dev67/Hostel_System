<?php
header('Content-Type: application/json');
include("../config/db.php");

// Check if room_id is provided
if(!isset($_GET['room_id']) || empty($_GET['room_id'])){
    echo json_encode(['error' => 'Room ID is required']);
    exit();
}

$room_id = $_GET['room_id'];

//get the student mate details and preferences
$stmt = $conn->prepare("
    SELECT s.gender, p.sleep_schedule, p.cleanliness_level, p.study_habits, 
           p.noise_tolerance, p.social_level
    FROM allocation a
    JOIN student s ON a.student_id = s.student_id
    JOIN preference p ON a.student_id = p.student_id
    WHERE a.room_id = ? AND a.status = 'active'
");

$stmt->bind_param("i", $room_id);
$stmt->execute();
$result = $stmt->get_result();

$roommates = [];
while($row = $result->fetch_assoc()){
    $roommates[] = $row;
}

$stmt->close();

echo json_encode($roommates);
?>