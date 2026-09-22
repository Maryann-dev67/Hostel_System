<?php
session_start();
include("../config/db.php");

//check admin
if(!isset($_SESSION['user_id']) || $_SESSION['role' ]!= 'admin'){
    header("Location: ../views/login.php");
    exit();
}
//handle add room
if(isset($_POST['add_room'])){
    $room_number = $_POST['room_number'];
    $capacity = $_POST['capacity'];
    $room_type = $_POST['room_type'];

    //check if room already exists
    $check = $conn->query("SELECT room_id FROM room WHERE room_number = '$room_number'");
    if($check->num_rows > 0){
        $error = "Room number already exists!";
    }else{
        $conn->query("INSERT INTO room (room_number, capacity, room_type)
        VALUES('$room_number', '$capacity', '$room_type')");
        $success = "Room added Successfully!";
        header ("Location: view_rooms.php");
        exit();
    }
}
// Fetch rooms and count active students in each room
$sql = "SELECT r.*, COUNT(a.allocation_id) AS occupied_count
        FROM room r
        LEFT JOIN allocation a 
        ON r.room_id = a.room_id AND a.status = 'active'
        GROUP BY r.room_id
        ORDER BY r.room_number";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Rooms</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="header">
        <h1>Preference Based Hostel Management System</h2>
        <p>View, Add and Delete rooms</p>
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
            <h2 style="text-align: center;margin-bottom: 0;">Manage Rooms</h2>
            <?php if(isset($success)): ?>
                <div class="success-msg"><?php echo $success; ?></div>
            <?php endif; ?>
            <?php if(isset($error)): ?>
                <div class="error-msg"> <?php echo $error; ?></div>
            <?php endif; ?>
            <div class="add-room-form">
                <div>
                    <label>Room Number:</label><br>
                    <input type="text" id="room_number" placeholder="">
                </div>
                <div>
                    <label>Capacity:</label>
                    <select id="capacity" required>
                        <option value="1">1 (Single)</option>
                        <option value="2">2 (Double)</option>
                    </select>
                </div>
                <div>
                    <label>Room Type:</label><br>
                    <select id="room_type" required>
                        <option value="single">Single</option>
                        <option value="double">Double</option>
                    </select>
                </div>
                <div>
                    <button onclick="addRoom()" class="btn"> Add Room</button>
                </div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Room ID</th>
                        <th>Room Number</th>
                        <th>Capacity</th>
                        <th>Room type</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['room_id']; ?></td>
                            <td><?php echo htmlspecialchars($row['room_number']); ?></td>
                            <td><?php echo $row['capacity']; ?></td>
                            <td><?php echo ucfirst($row['room_type']); ?></td>
                            <td>
                                <?php if ($row['occupied_count'] == 0) {
                                    echo "Available " . $row['occupied_count'] . "/" . $row['capacity'];
                                    } elseif ($row['occupied_count'] < $row['capacity']) {
                                        echo "Partially Occupied " . $row['occupied_count'] . "/" . $row['capacity'];
                                        } else {
                                            echo "Occupied " . $row['occupied_count'] . "/" . $row['capacity'];
                                            }
                                ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <br>
        <a href="admin_dashboard.php" class="btn">Back to Dashboard</a>
    </div>
    <div class="footer">
        <p>&copy; 2026 Preference Based Hostel Management System.All Rights Reserved </p>
    </div> 
    <script>
        function addRoom(){
            const room_number = document.getElementById('room_number').value;
            const capacity= document.getElementById('capacity').value;
            const room_type = document.getElementById('room_type').value;

            if(!room_number){
                alert('Please enter room number');
                return;
            }
            fetch('add_room.php',{
                method:'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'room_number=' +encodeURIComponent(room_number)+
                '&capacity=' +encodeURIComponent(capacity)+
                '&room_type=' +encodeURIComponent(room_type)
            })
            .then(response =>response.text())
            .then(data =>{
                if(data === 'success'){
                    location.reload();
                }else{
                    alert(data);
                }
            });
        }
    </script>
    
</body>
</html>


