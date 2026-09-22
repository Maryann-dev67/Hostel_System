<?php
session_start();
include("../config/db.php");

// Check admin login
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit();
}

// Get admin name
$admin_name = "Administrator";
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT full_name FROM administrator WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if($result && $result->num_rows > 0){
    $row = $result->fetch_assoc();
    $admin_name = $row['full_name'];
}
$stmt->close();
// Total Students
$totalQuery = $conn->query("SELECT COUNT(*) as total FROM student");
$totalStudents = $totalQuery->fetch_assoc()['total'];

// Total Rooms
$roomQuery = $conn->query("SELECT COUNT(*) as total FROM room");
$totalRooms = $roomQuery->fetch_assoc()['total'];

// Allocated Students
$allocatedQuery = $conn->query("SELECT COUNT(DISTINCT student_id) as allocated FROM allocation WHERE status = 'active'");
$allocated = $allocatedQuery->fetch_assoc()['allocated'];

// Unallocated Students
$unallocated = $totalStudents - $allocated;

// Total Allocations
$totalAllocQuery = $conn->query("SELECT COUNT(*) as total FROM allocation WHERE status = 'active'");
$totalAllocations = $totalAllocQuery->fetch_assoc()['total'];

// Occupied Rooms
$occupiedQuery = $conn->query("
    SELECT COUNT(DISTINCT room_id) as occupied 
    FROM allocation 
    WHERE status = 'active'
");
$occupiedRooms = $occupiedQuery->fetch_assoc()['occupied'];

$availableRooms = $totalRooms - $occupiedRooms;

$allocatedPercent = ($totalStudents > 0) ? round(($allocated / $totalStudents) * 100) : 0;
$unallocatedPercent = ($totalStudents > 0) ? round(($unallocated / $totalStudents) * 100) : 0;
$occupiedPercent = ($totalRooms > 0) ? round(($occupiedRooms / $totalRooms) * 100) : 0;
$availablePercent = ($totalRooms > 0) ? round(($availableRooms / $totalRooms) * 100) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .welcome-box {
            background: linear-gradient(135deg, #2c3e50, #3498db);
            color: white;
            padding: 25px 35px;
            border-radius: 12px;
            margin: 20px 30px;
            display: flex;
            align-items: center;
            gap: 20px;
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
        }
        .welcome-text h2 {
            margin: 0 0 5px 0;
            font-size: 24px;
        }

        .welcome-text p {
            margin: 0;
            opacity: 0.85;
            font-size: 15px;
        }

        .stats-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            margin: 20px 30px;
        }

        .stat-card {
            background:linear-gradient(135deg, #2c3e50, #3498db);
            color: white;
            border-radius: 12px;
            padding: 20px 25px;
            text-align: center;
            flex: 1 1 180px;
            max-width: 220px;
            min-width: 160px;
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
            border-top: 4px solid #3498db;
            transition: transform 0.3s;
            border:none;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(52, 152, 219, 0.4);
        }

        .stat-card .number {
            font-size: 32px;
            font-weight: bold;
            color: white;
        }

        .stat-card .label {
            font-size: 13px;
            color: rgba(255,255,255,0.85);
            margin-top: 3px;
        }

        .stat-card .percent {
            font-size: 12px;
            color: rgba(255,255,255,0.7);
            margin-top: 2px;
        }

        .charts-container {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            justify-content: center;
            margin: 20px 30px 40px 30px;
        }

        .chart-box {
            background: #fff;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            max-width: 450px;
            width: 100%;
            min-width: 300px;
            flex: 1;
        }

        .chart-box h3 {
            text-align: center;
            margin-bottom: 20px;
            color: #2c3e50;
            font-size: 17px;
        }

        .chart-box canvas {
            max-height: 280px;
            max-width: 100%;
        }

        @media (max-width: 768px) {
            .welcome-box {
                flex-direction: column;
                text-align: center;
                padding: 20px;
                margin: 15px;
            }
            .welcome-text h2 {
                font-size: 20px;
            }
            .stats-container {
                margin: 15px;
            }
            .stat-card {
                flex: 1 1 100%;
                max-width: 100%;
            }
            .charts-container {
                margin: 15px;
            }
            .chart-box {
                min-width: 100%;
            }
         }
    </style>
</head>
<body>

    <div class="header">
        <h1>Preference Based Hostel Management System</h1>
        <p>Administrator Dashboard</p>
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
    <div class="welcome-box">
        <div class="welcome-text">
            <h2>Welcome, <?php echo $admin_name; ?>!</h2>
            <p>Here's an overview of the hostel system.</p>
        </div>
    </div>
    <div class="stats-container">
        <div class="stat-card">
            <div class="number"><?php echo $totalStudents; ?></div>
            <div class="label">Total Students</div>
        </div>

        <div class="stat-card">
            <div class="number"><?php echo $allocated; ?></div>
            <div class="label">Allocated Students</div>
            <div class="percent"><?php echo $allocatedPercent; ?>% of total</div>
        </div>

        <div class="stat-card">
            <div class="number"><?php echo $unallocated; ?></div>
            <div class="label">Unallocated Students</div>
            <div class="percent"><?php echo $unallocatedPercent; ?>% of total</div>
        </div>

        <div class="stat-card">
            <div class="number"><?php echo $totalRooms; ?></div>
            <div class="label">Total Rooms</div>
        </div>

        <div class="stat-card">
            <div class="number"><?php echo $occupiedRooms; ?></div>
            <div class="label">Occupied Rooms</div>
            <div class="percent"><?php echo $occupiedPercent; ?>% of total</div>
        </div>

        <div class="stat-card">
            <div class="number"><?php echo $availableRooms; ?></div>
            <div class="label"> Available Rooms</div>
            <div class="percent"><?php echo $availablePercent; ?>% of total</div>
        </div>

        <div class="stat-card">
            <div class="number"><?php echo $totalAllocations; ?></div>
            <div class="label">Total Allocations</div>
        </div>
    </div>
    <div class="charts-container">
        <div class="chart-box">
            <h3>Student Allocation Status</h3>
            <canvas id="studentChart"></canvas>
        </div>

        <div class="chart-box">
            <h3>Room Occupancy Status</h3>
            <canvas id="roomChart"></canvas>
        </div>
    </div>

    <div class="footer">
        <p>&copy; 2026 Preference Based Hostel Management System. All Rights Reserved</p>
    </div>

    <script>
        const ctx1 = document.getElementById('studentChart').getContext('2d');
        new Chart(ctx1, {
            type: 'doughnut',
            data: {
                labels: ['Allocated', 'Unallocated'],
                datasets: [{
                    data: [
                        <?php echo $allocated; ?>,
                        <?php echo $unallocated; ?>
                    ],
                    backgroundColor: ['#2ecc71', '#e74c3c'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            font: { size: 14 }
                        }
                    }
                },
                cutout: '70%'
            }
        });
        const ctx2 = document.getElementById('roomChart').getContext('2d');
        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: ['Occupied Rooms', 'Available Rooms'],
                datasets: [{
                    label: 'Number of Rooms',
                    data: [
                        <?php echo $occupiedRooms; ?>,
                        <?php echo $availableRooms; ?>
                    ],
                    backgroundColor: ['#3498db', '#f39c12'],
                    borderColor: ['#2980b9', '#e67e22'],
                    borderWidth: 2,
                    borderRadius: 6,
                    barPercentage: 0.6
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            font: { size: 13 }
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: { size: 13 }
                        }
                    }
                }
            }
        });
    </script>

</body>
</html>