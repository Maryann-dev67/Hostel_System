<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../pages/login.html");
    exit();
}

// Get all students with their payment status
$students = $conn->query("
    SELECT s.full_name, s.reg_no, r.room_number, a.period,
           COALESCE(SUM(p.amount), 0) as paid,
           r.fee_per_semester as total_fee,
           CASE 
               WHEN COALESCE(SUM(p.amount), 0) >= r.fee_per_semester THEN 'Paid'
               WHEN COALESCE(SUM(p.amount), 0) > 0 THEN 'Partial'
               ELSE 'Not Paid'
           END as payment_status
    FROM allocation a
    JOIN student s ON a.student_id = s.student_id
    JOIN room r ON a.room_id = r.room_id
    LEFT JOIN payments p ON a.allocation_id = p.allocation_id
    WHERE a.status = 'active'
    GROUP BY a.allocation_id
    ORDER BY s.full_name
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment Status</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .paid { color: green; font-weight: bold; }
        .partial { color: orange; font-weight: bold; }
        .not-paid { color: red; font-weight: bold; }
         @media print { .nav, .btn, .no-print { display: none !important; } }
    </style>
</head>
<body>
    <div class="header">
        <h1>Student Payment Status</h1>
        <p>View which students have paid their hostel fees</p>
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
    <div class="container">
        <div class="table-container">
            <h2 style="text-align: center; margin-bottom: 30px">Payment Status Reports</h2>
            <table style="width:100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #2c3e50; color: white;">
                        <th>Student Name</th>
                        <th>Reg No</th>
                        <th>Room</th>
                        <th>Period</th>
                        <th>Amount Paid</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($students && $students->num_rows > 0): ?>
                        <?php while($row = $students->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['reg_no']); ?></td>
                                <td><?php echo $row['room_number']; ?></td>
                                <td><?php echo $row['period']; ?></td>
                                <td>KES <?php echo number_format($row['paid'], 2); ?></td>
                                <td>
                                    <?php 
                                    if($row['payment_status'] == 'Paid'){
                                        echo '<span class="paid"> Paid</span>';
                                    } elseif($row['payment_status'] == 'Partial'){
                                        echo '<span class="partial">Partial</span>';
                                    } else {
                                        echo '<span class="not-paid">Not Paid</span>';
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center;">No students allocated yet</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div style="text-align: center; margin-top: 20px;">
            <a href="#" onclick="window.print(); return false;" style="color: #3498db; text-decoration: none; margin: 0 10px;">Print</a>
            <a href="admin_dashboard.php" class="btn" style="">Back to Dashboard</a>
    </div>
</body>
</html>