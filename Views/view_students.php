<?php

session_start();
include("../config/db.php");
//check Login
if(!isset($_SESSION['user_id'])){
    header("Location: login.html");
    exit();
}

//Check Role
if($_SESSION['role']!='admin'){
    echo "Access Denied!";
    exit();
}

//fetch students
$sql="SELECT s.*, p.sleep_schedule, p.cleanliness_level, p.study_habits, p.noise_tolerance, p.social_level
      FROM student s
      LEFT JOIN preference p ON s.student_id = p.student_id";
$result=$conn->query($sql);
?>

<!DOCTYPE html>
<html lang="len">
    <head>
        <meta charset="UTF-8">
        <mete name="viewport" content="width=device-width, initial-scale=1.0">
        <title>View Students</title>
        <link rel="stylesheet" href="../css/style.css">
        <style>
        @media print { .nav, .no-print, .footer { display: none; } }
        </style>
    </head>
<body>
    <div class="header">
        <h1>Preference Based Hostel Management System</h1>
        <p>View all students</p>
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
        
        <div class="table-container">
            <h2 style="text-align: center;">All students</h2>
            <table>
                <thead>
                    <tr>
                        <th>Student id</th>
                        <th>Full Name</th>
                        <th>Reg No</th>
                        <th>Gender</th>
                        <th>Course</th>
                        <th>Year of Study</th>
                        <th>Phone </th>
                        <th>Sleep</th>
                        <th>Cleanliness</th>
                        <th>Study Habits</th>
                        <th>Noise</th>
                        <th>Social level</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['student_id']; ?></td>
                            <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['reg_no']); ?></td>
                            <td><?php echo $row['gender']; ?></td>
                            <td><?php echo htmlspecialchars($row['course']); ?></td>
                            <td><?php echo $row['year_of_study']; ?></td>
                            <td><?php echo $row['phone']; ?></td>
                            <td><?php echo $row['sleep_schedule'] ?: '-' ?></td>
                            <td><?php echo $row['cleanliness_level'] ?: '-' ?></td>
                            <td><?php echo $row['study_habits'] ?: '-' ?></td>
                            <td><?php echo $row['noise_tolerance'] ?: '-' ?></td>
                            <td><?php echo $row['social_level'] ?: '-' ?></td>
                        </tr>
                        <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <div class="report-actions no-print">
            <a href="#" onclick="window.print(); return false;">Print Report</a>
            &nbsp;|&nbsp;
            <a href="admin_dashboard.php">Back to Dashboard</a>
        </div>

    <div class="footer">
        <p>&copy; 2026 Preference Based Hostel Management System.All Rights reserved</p>
    </div>

</body>
</html>
