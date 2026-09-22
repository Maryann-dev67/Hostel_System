<?php 
session_start();
include("../config/db.php");

//check admin access
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !='admin'){
    header("Location: login.php");
    exit();
}

//fetch allocations with student and room details
$sql="SELECT allocation.*, student.full_name,room.room_number
FROM allocation
JOIN student ON allocation.student_id = student.student_id
JOIN room ON allocation.room_id = room.room_id
WHERE allocation.status = 'active'
ORDER BY allocation.allocation_date DESC";

$result= $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>View Allocations</title>
        <link rel="stylesheet" href="../css/style.css">
    </head>
    <body>
        <div class="header">
            <h1>Preference Based Hostel Management System</h1>
            <p>View All Allocations</p>
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
            <?php if(isset($_GET['success']) && $_GET['success'] == 'removed'): ?>
            <div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin: 10px 0;">
                Student has been removed from their room. They can now be re-allocated.
            </div>
        <?php endif; ?>
        
        <?php if(isset($_GET['error']) && $_GET['error'] == 'failed'): ?>
            <div style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin: 10px 0;">
                Failed to remove student. Please try again.
            </div>
        <?php endif; ?>
            
            <div class="table-container">
                <h2 style="text-align: center;">Room Allocations</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Room Number</th>
                            <th>Allocation date</th>
                            <th>Status</th>
                            <th>Occupancy</th>
                            <th>Period</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['room_number']); ?></td>
                                    <td><?php echo ucfirst($row['allocation_date']); ?></td>
                                    <td><?php echo $row['status']; ?></td>
                                    <td>
                                        <?php
                                        if($row['student_confirmed'] == 'confirmed'){
                                            echo 'Confirmed';
                                        }elseif($row['student_confirmed'] == 'pending'){
                                            echo 'pending';
                                        }else{
                                            echo '-';
                                        }
                                        ?>
                                    </td>
                                    <td><?php echo $row['period']; ?></td>
                                    <td>
                                    <form action="../controller/remove_allocation_controller.php" method="POST" onsubmit="return confirm('Remove <?php echo htmlspecialchars($row['full_name']); ?> from their room?');">
                                        <input type="hidden" name="allocation_id" value="<?php echo $row['allocation_id']; ?>">
                                        <button type="submit" style="background: #dc3545; color: white; border: none; padding: 5px 12px; border-radius: 4px; cursor: pointer;" name="remove_allocation">Remove</button>
                                    </form>
                                </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <br>
            <a href="admin_dashboard.php" class="btn">Back to Dashboard</a>
        </div>
        <div class="footer">
            <p>&copy;2026 Preference Based Hostel Management System.All Rights Reserved </p>
        </div>
    </body>
</html>