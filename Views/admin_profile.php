<?php
session_start();
include("../config/db.php");

//check if admin is logged in
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !='admin'){
    header("Location: ../pages/login.html");
    exit();
}

$user_id = $_SESSION['user_id'];
//get existing admin data 
$stmt = $conn->prepare("SELECT * FROM administrator WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="header">
        <h1>Preference Based Hostel Management System</h1>
        <p>Complete your Admin Profile</p>
    </div>
    <div class="nav">
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="admin_profile.php">My Profile</a>
        <a href="allocate_room.php">Allocate Rooms</a>
        <div class="dropdown">
            <a href="#" class="dropbtn">Reports </a>
            <div class="dropdown-content">
                <a href="view_students.php">Student Reports</a>
                <a href="view_rooms.php">Room Reports</a>
                <a href="view_allocations.php">Allocation Reports</a>
                <a href="payment_reports.php">Payment Reports</a>
            </div>
        </div>
        
        <a href="../controller/logout.php">Logout</a>
    </div>
    <div class="form-container">
        <h2 style="text-align: center; margin-bottom: 20px;">Administrator Profile</h2>
        <form action="../controller/admin_profile_controller.php" method="POST">
            <input type="hidden" name="save_admin_profile" value="1">

            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="full_name" value="<?php echo htmlspecialchars($admin['full_name'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label>Department</label>
                <input type="text" name="department" value="<?php echo htmlspecialchars($admin['department'] ?? ''); ?>" placeholder="Hostel Management" required>
            </div>
            
            <div class="form-group">
                <label>Phone Number</label>
                <input type="tel" name="phone" value="<?php echo htmlspecialchars($admin['phone'] ?? ''); ?>" placeholder="optional">
            </div>

            <button type="submit" class="btn" style="width:100%">Save Profile</button>
        </form>
    </div>
    <div class="footer">
        <p>&copy; 2026 Preference Based Hostel Management System.All Rights reserved</p>
    </div>
    
</body>
</html>