USE hostel_system;

-- TABLE 1: user
CREATE TABLE main_user (
user_id INT PRIMARY KEY AUTO_INCREMENT,
email VARCHAR(100) NOT NULL UNIQUE,
password_hash VARCHAR(255)NOT NULL,
role ENUM('student','admin')NOT NULL,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
last_login TIMESTAMP NULL
);

-- TABLE 2: Student
CREATE TABLE student(
student_id INT PRIMARY KEY AUTO_INCREMENT,
user_id INT NOT NULL,
reg_no VARCHAR(20) NOT NULL UNIQUE,
gender ENUM('M','F','OTHER')NOT NULL,
course VARCHAR(100)NOT NULL,
year_of_study INT NOT NULL,
phone VARCHAR(15),
emergency_contact VARCHAR(100),
FOREIGN KEY (user_id) REFERENCES main_user(user_id) ON DELETE CASCADE);

-- Table 3;administrator 
CREATE TABLE administrator (
    admin_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL UNIQUE,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(15),
    access_level ENUM('super', 'standard') DEFAULT 'standard',
    FOREIGN KEY (user_id) REFERENCES main_user(user_id) ON DELETE CASCADE
);

-- TABLE 4; Rooms
CREATE TABLE room (
    room_id INT PRIMARY KEY AUTO_INCREMENT,
    room_number VARCHAR(10) NOT NULL UNIQUE,
    floor INT NOT NULL,
    capacity INT NOT NULL,
    room_type ENUM('single', 'double', 'triple', 'dorm') NOT NULL,
    occupancy_status ENUM('vacant', 'partial', 'full') DEFAULT 'vacant',
    availability BOOLEAN DEFAULT TRUE,
    facilities TEXT
);

-- TABLE 5: Preference
CREATE TABLE preference (
    preference_id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL UNIQUE, -- One student has one preference
    sleep_schedule ENUM('early', 'night', 'flexible') NOT NULL,
    cleanliness_level INT NOT NULL CHECK (cleanliness_level BETWEEN 1 AND 5),
    study_habits ENUM('quiet', 'moderate', 'social') NOT NULL,
    noise_tolerance INT NOT NULL CHECK (noise_tolerance BETWEEN 1 AND 5),
    social_level INT NOT NULL CHECK (social_level BETWEEN 1 AND 5),
    submission_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES student(student_id) ON DELETE CASCADE
);

-- TABLE 6: Allocation
CREATE TABLE allocation (
    allocation_id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    room_id INT NOT NULL,
    allocation_date DATE NOT NULL,
    status ENUM('active', 'changed', 'cancelled') DEFAULT 'active',
    FOREIGN KEY (student_id) REFERENCES student(student_id) ON DELETE CASCADE,
    FOREIGN KEY (room_id) REFERENCES room(room_id) ON DELETE CASCADE,
    -- Ensure one student has only one active allocation per academic year
    CONSTRAINT unique_active_allocation UNIQUE (student_id,status)
);


-- TABLE 7: Notification
CREATE TABLE notification (
    notification_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    message_type ENUM('allocation', 'reminder', 'alert', 'info') NOT NULL,
    message_content TEXT NOT NULL,
    date_sent TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('unread', 'read') DEFAULT 'unread',
    FOREIGN KEY (user_id) REFERENCES main_user(user_id) ON DELETE CASCADE
);



