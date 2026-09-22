<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../pages/login.html");
    exit();
}

// Get all unallocated students with their preferences
$students = $conn->query("
    SELECT s.student_id, s.full_name, s.reg_no, s.gender, 
           p.sleep_schedule, p.cleanliness_level, p.study_habits, 
           p.noise_tolerance, p.social_level, p.preferred_period, p.preferred_room_type
    FROM student s
    JOIN preference p ON s.student_id = p.student_id
    WHERE s.student_id NOT IN (SELECT student_id FROM allocation WHERE status = 'active')
");

// Get all rooms with current occupants
$rooms = $conn->query("
    SELECT r.room_id, r.room_number, r.capacity, r.room_type,
           COUNT(a.student_id) AS current_students
    FROM room r
    LEFT JOIN allocation a ON r.room_id = a.room_id AND a.status = 'active'
    GROUP BY r.room_id
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Allocate Rooms</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .compatible-room {
            background-color: #d4edda;
            border: 2px solid #28a745;
            padding: 15px;
            margin: 10px;
            border-radius: 8px;
            cursor: pointer;
            display: inline-block;
            width: 280px;
        }
        .compatible-room:hover {
            transform: scale(1.02);
        }
        .room-list {
            display: flex;
            flex-wrap: wrap;
            margin-top: 20px;
        }
        .student-info {
            background: #e3f2fd;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Hostel Management System</h1>
        <p>Smart Room Allocation - Only Showing Compatible Rooms</p>
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
        <div class="form-container">
            <h2 style="text-align: center; margin-bottom:10px;">Allocate Room</h2>
            <div class="form-group">
                <div style="background: #fff3cd; padding: 10px 15px; border-radius: 5px; margin-bottom: 20px; text-align: center; border: 1px solid #ffeeba;">
                    <strong>Note:</strong> Please only allocate students for the current semester.
                    <span style="color: #856404; font-size: 13px; display: block; margin-top: 3px;">
                        (Check the student's preferred semester in the dropdown below)
                    </span>
                </div>
                <label>Select Student:</label>
                <select id="student_select" style="width:100%; padding:10px;">
                    <option value="">-- Select Student --</option>
                    <?php while($s = $students->fetch_assoc()): ?>
                        <option value="<?php echo $s['student_id']; ?>"
                                data-sleep="<?php echo $s['sleep_schedule']; ?>"
                                data-clean="<?php echo $s['cleanliness_level']; ?>"
                                data-study="<?php echo $s['study_habits']; ?>"
                                data-noise="<?php echo $s['noise_tolerance']; ?>"
                                data-social="<?php echo $s['social_level']; ?>"
                                data-period="<?php echo $s['preferred_period']; ?>"
                                data-preferred-room="<?php echo $s['preferred_room_type']; ?>"
                                data-student-name="<?php echo $s['full_name']; ?>"
                                data-gender="<?php echo $s['gender']; ?>">
                                
                            <?php echo $s['full_name']; ?> (<?php echo $s['reg_no']; ?>)
                            - <?php echo $s['preferred_period']; ?> 
                            - Wants: <?php echo ucfirst($s['preferred_room_type'] ?? 'Any'); ?> room
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>

        <div id="student_info" style="display: none;" class="student-info"></div>
        <div id="results" style="margin-top: 20px;"></div>

        <form id="allocation_form" action="../controller/allocation_controller.php" method="POST" style="display:none;">
            <input type="hidden" name="student_id" id="student_id">
            <input type="hidden" name="room_id" id="room_id">
            <input type="hidden" name="academic_year" value="<?php echo date('Y') . '-' . (date('Y')+1); ?>">
        </form>
    </div>

    <script>
        // Store all rooms data
        const allRooms = <?php 
            $room_data = [];
            while($r = $rooms->fetch_assoc()){
                $room_data[] = $r;
            }
            echo json_encode($room_data);
        ?>;

        // Function to check if two students are compatible
        function isCompatible(s1, s2){
            if(s1.gender !== s2.gender) return false;
            if(s1.sleep !== s2.sleep) return false;
            if(Math.abs(s1.study - s2.study) > 2) return false;
            if(Math.abs(s1.clean - s2.clean) > 2) return false;
            if(Math.abs(s1.noise - s2.noise) > 2) return false;
            if(Math.abs(s1.social - s2.social) > 2) return false;
            return true;
        }

        // Function to get roommates for a specific room
        async function getRoommates(roomId){
            const response = await fetch(`../api/get_roommates.php?room_id=${roomId}`);
            return await response.json();
        }

        // Main function when student is selected
        document.getElementById('student_select').addEventListener('change', async function(){
            const studentId = this.value;
            const selectedOption = this.options[this.selectedIndex];
            
            if(!studentId){
                document.getElementById('results').innerHTML = '';
                document.getElementById('student_info').style.display = 'none';
                return;
            }

            // Get selected student's preferences
            const selectedStudent = {
                gender: selectedOption.dataset.gender,
                sleep: selectedOption.dataset.sleep,
                clean: parseInt(selectedOption.dataset.clean),
                study: parseInt(selectedOption.dataset.study),
                noise: parseInt(selectedOption.dataset.noise),
                social: parseInt(selectedOption.dataset.social),
                preferredRoom: selectedOption.dataset.preferredRoom
            };

            // Show student info
            const studentName = selectedOption.dataset.studentName;
            const preferredRoom = selectedOption.dataset.preferredRoom;
            const period = selectedOption.dataset.period;
            document.getElementById('student_info').innerHTML = `
                <strong>Selected Student:</strong> ${studentName}<br>
                <strong>Preferred Semester:</strong> ${period}<br>
                <strong>Preferred Room Type:</strong> ${preferredRoom ? ucfirst(preferredRoom) : 'Any'} room
            `;
            document.getElementById('student_info').style.display = 'block';

            let html = '<h3>Compatible Rooms</h3><div class="room-list">';
            let hasCompatible = false;

            function ucfirst(str) {
                if(!str) return '';
                return str.charAt(0).toUpperCase() + str.slice(1);
            }

            // Check each room
            for(let room of allRooms){
                const availableSpaces = room.capacity - room.current_students;
                if(availableSpaces <= 0) continue;

                // Check if room type matches student's preference
                if(selectedStudent.preferredRoom && selectedStudent.preferredRoom !== 'any'){
                    if(room.room_type !== selectedStudent.preferredRoom){
                        continue; 
                    }
                }

                const roommates = await getRoommates(room.room_id);
                
                // If room is empty  always compatible
                if(roommates.length === 0){
                    hasCompatible = true;
                    html += `
                        <div class="compatible-room" onclick="allocateRoom(${studentId}, ${room.room_id})">
                            <strong> Room ${room.room_number}</strong><br>
                            Type: ${ucfirst(room.room_type)}<br>
                            Status: Empty room<br>
                            <span style="color:green;"> Perfect Match - ${ucfirst(room.room_type)} room available</span>
                        </div>`;
                    continue;
                }

                // Check compatibility with ALL existing roommates
                let compatible = true;
                for(let roommate of roommates){
                    const mateStudent = {
                        gender: roommate.gender,
                        sleep: roommate.sleep_schedule,
                        clean: roommate.cleanliness_level,
                        study: roommate.study_habits,
                        noise: roommate.noise_tolerance,
                        social: roommate.social_level
                    };
                    if(!isCompatible(selectedStudent, mateStudent)){
                        compatible = false;
                        break;
                    }
                }

                if(compatible){
                    hasCompatible = true;
                    html += `
                        <div class="compatible-room" onclick="allocateRoom(${studentId}, ${room.room_id})">
                            <strong> Room ${room.room_number}</strong><br>
                            Type: ${ucfirst(room.room_type)}<br>
                            Occupied: ${room.current_students}/${room.capacity}<br>
                            <span style="color:green;">Compatible with existing roommates</span>
                        </div>`;
                }
            }

            if(!hasCompatible){
                html += `<p style="color:orange; width:100%;">No compatible rooms available for this student. Try a different student.</p>`;
            }

            html += `</div>`;
            
            document.getElementById('results').innerHTML = html;
        });

        function allocateRoom(studentId, roomId){
            if(confirm('Allocate this student to the selected room?')){
                const select = document.getElementById('student_select');
                const selectedOption = select.options[select.selectedIndex];
                const period = selectedOption.dataset.period;
                
                document.getElementById('student_id').value = studentId;
                document.getElementById('room_id').value = roomId;
                
                let periodField = document.getElementById('period');
                if(!periodField){
                    periodField = document.createElement('input');
                    periodField.type = 'hidden';
                    periodField.name = 'period';
                    periodField.id = 'period';
                    document.getElementById('allocation_form').appendChild(periodField);
                }
                periodField.value = period;
                
                document.getElementById('allocation_form').submit();
            }
        }
    </script>
</body>
</html>