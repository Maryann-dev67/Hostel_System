-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 22, 2026 at 09:06 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hostel_systemnew`
--

-- --------------------------------------------------------

--
-- Table structure for table `administrator`
--

CREATE TABLE `administrator` (
  `admin_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `department` varchar(100) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `access_level` enum('super','standard') DEFAULT 'standard'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `administrator`
--

INSERT INTO `administrator` (`admin_id`, `user_id`, `full_name`, `department`, `phone`, `access_level`) VALUES
(1, 2, 'Hope Wangui', 'Hostel Management', '0113487325', 'standard');

-- --------------------------------------------------------

--
-- Table structure for table `allocation`
--

CREATE TABLE `allocation` (
  `allocation_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `allocation_date` date NOT NULL,
  `status` enum('active','changed','cancelled') DEFAULT 'active',
  `period` enum('Semester 1','Semester 2','Semester 3','Full Year') DEFAULT 'Full Year',
  `student_confirmed` enum('pending','confirmed') DEFAULT 'pending',
  `academic_year` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `allocation`
--

INSERT INTO `allocation` (`allocation_id`, `student_id`, `room_id`, `allocation_date`, `status`, `period`, `student_confirmed`, `academic_year`) VALUES
(1, 1, 3, '2026-06-25', 'changed', 'Semester 1', 'confirmed', '2026-2027'),
(2, 2, 4, '2026-07-02', 'active', 'Semester 2', 'pending', '2026-2027'),
(3, 3, 4, '2026-07-03', 'changed', 'Full Year', 'pending', '2026-2027'),
(4, 1, 2, '2026-07-04', 'active', 'Full Year', 'pending', '2026-2027'),
(6, 3, 4, '2026-07-12', 'active', 'Full Year', 'confirmed', '2026-2027'),
(7, 5, 3, '2026-07-12', 'active', 'Semester 3', 'pending', '2026-2027'),
(8, 4, 5, '2026-07-12', 'active', 'Semester 2', 'pending', '2026-2027'),
(10, 7, 7, '2026-07-16', 'active', 'Semester 2', 'confirmed', '2026-2027'),
(11, 11, 9, '2026-07-18', 'active', 'Semester 2', 'pending', '2026-2027'),
(12, 6, 11, '2026-07-18', 'active', 'Semester 2', 'pending', '2026-2027'),
(13, 8, 13, '2026-07-18', 'active', 'Semester 2', 'pending', '2026-2027'),
(16, 9, 15, '2026-09-02', 'active', 'Semester 1', 'confirmed', '2026-2027'),
(18, 19, 5, '2026-09-16', 'active', 'Semester 1', 'pending', '2026-2027');

-- --------------------------------------------------------

--
-- Table structure for table `main_user`
--

CREATE TABLE `main_user` (
  `user_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('student','admin') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `main_user`
--

INSERT INTO `main_user` (`user_id`, `email`, `password_hash`, `role`, `created_at`, `last_login`) VALUES
(1, 'faithmaji@gmail.com', '$2y$10$alE/aBXgXe07fT.NScUOVuYfnOxzqrTAOX2lHnar3kAP0C00.oTU6', 'student', '2026-06-25 14:39:50', '2026-07-16 09:02:20'),
(2, 'hopewangui@gmail.com', '$2y$10$Qu19d8/gwVV/kknGQLDXUeWmb56AWjX3FP/qfM0tifQ.brmexmup.', 'admin', '2026-06-25 14:42:54', '2026-09-16 19:50:51'),
(3, 'abi7@gmail.com', '$2y$10$w9Fg1jb18qvTj2cQ5cykqeP0RvY7Lym6Rrlm9F6isjNQPilgrCA4C', 'student', '2026-06-25 14:58:21', '2026-06-25 14:58:37'),
(4, 'janedoe@gmail.com', '$2y$10$C9oxAmb3.msGiNyqSkapquyu5pcpxVTT8i3DQm5L4oOCn9LaQs6My', 'student', '2026-07-02 10:41:09', '2026-07-18 05:54:51'),
(5, 'gracewanjiru2@gmail.com', '$2y$10$CIhIjZtvjFYQ.X0DZYw70.6gPnkMV/MfE2MMjl4aNnVdfsI.IDe7q', 'student', '2026-07-03 04:48:00', '2026-07-12 09:52:52'),
(6, 'jennifer@gmail.com', '$2y$10$LgD3KkgZs3bx0QBZBRLbJO3D84fU1EcNJgAu7jmYET4PvOE5.HJJS', 'student', '2026-07-03 14:50:18', '2026-07-16 04:37:58'),
(7, 'juliusmugo@gmail.com', '$2y$10$pFAkkFGTYSqxPINZ6AxFAeGXz19L8R1rHWNGoqJSeyBueB.4oN3va', 'student', '2026-07-12 10:08:08', '2026-07-12 10:08:47'),
(8, 'paustine4@gmail.com', '$2y$10$fYOUGN.6jhcGUAfO5t9H0.clYuSggM8/WN46cW4VjF8wPatRmBoqy', 'student', '2026-07-12 10:11:22', '2026-08-09 08:12:45'),
(9, 'joyce@gmail.com', '$2y$10$Z5/p5jACKkUgXTx7ZGYhEun0LuuTczRH6mRRkFr4/GWPXiBbHN7si', 'student', '2026-07-16 01:32:09', '2026-07-16 01:32:30'),
(10, 'george@gmail.com', '$2y$10$ilrn5pxnfeZv2UWHlxNTk.c2UtCm3PNt.L4XprhcorlkpAmran3LK', 'student', '2026-07-17 08:21:26', '2026-07-18 14:53:20'),
(11, 'enwel@gmail.com', '$2y$10$F.dQoTHHsqCb2Qr2aSNFWucWxK0CE.mnITXrWpRD/LcO05pbCfY1S', 'student', '2026-07-17 09:25:07', '2026-09-15 11:54:36'),
(12, 'andrew@gmail.com', '$2y$10$Vx8cY8ElsYCEcjxNx2X3puyrksuauUNjUDnrt4GB241H3D8CA81KO', 'student', '2026-07-17 10:02:13', '2026-07-17 10:02:43'),
(13, 'phillip3@gmail.com', '$2y$10$aPxQK6XBPGZ3dxdaYAXEyejno7O6lbx9vXHxg.vKuwm7xXT1nVrbm', 'student', '2026-07-17 18:33:37', '2026-07-20 18:09:25'),
(14, 'ethanketip@gmail.com', '$2y$10$jp5XOJ7oieorne2BuX2sNeMJXPC8hyS8xOM6PtSXDfLZDwoIZ4q.W', 'student', '2026-07-18 06:19:47', '2026-07-18 06:20:14'),
(15, 'raymond@gmail.com', '$2y$10$Cd/AeePOt6nBaplVTuJrsuRP.WyOvXkhGRkj20dPKpct2QWcBPlOS', 'student', '2026-07-18 14:59:44', '2026-09-02 17:31:00'),
(16, 'norahmwanza@gmail.com', '$2y$10$a05pE6RxGP4LRbatkERU9uyj7CFYidkcYXtQZABkm49qTjnhv42La', 'student', '2026-07-20 06:00:27', '2026-09-12 14:20:00'),
(17, 'joshua23@gmail.com', '$2y$10$V0vfcsrYmLlQl/QvVOj3qeMoe9gOzapdumzA9EaqyjRf9ZNLNd9iK', 'student', '2026-07-20 06:17:04', '2026-09-12 14:21:04'),
(18, 'annwairimu4@gmail.com', '$2y$10$v2JPgiDg6.9dfmGvOvFhpeHHvKhx1812Mx8wHN7nlvFhA34UVh3Re', 'student', '2026-07-20 08:43:13', '2026-09-12 14:22:18'),
(19, 'josephine@gmail.com', '$2y$10$P2DLhJofLOHD.ArVVKL/Y.A3oWj6S5ISyOrOVaF29xAM52prdFLIS', 'student', '2026-07-20 08:57:59', '2026-09-02 17:30:18'),
(20, 'phyllismbuthia@gmail.com', '$2y$10$oq3TW3rL0XH8x.427tvl8eBWbwivRR89Fb7/l4ONvA6DgvdNzqFDi', 'student', '2026-07-20 17:31:44', '2026-07-20 17:46:54'),
(21, 'ruthwanjiru@gmail.com', '$2y$10$lhnsa.EucmUBob/nhOxBsO2im3x4z2.grzWUD95kc7T.IrYz2twLO', 'student', '2026-09-16 06:13:02', '2026-09-16 06:13:20'),
(22, 'babra@gmail.com', '$2y$10$5Sj8sAUKTVhlzxAu9bknt.lBjgGx4er4mwUIpXpKSJDdGLy6haYE6', 'student', '2026-09-16 19:48:05', '2026-09-16 19:48:26');

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message_type` enum('allocation','allocation_removed') NOT NULL,
  `message_content` text NOT NULL,
  `date_sent` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('unread','read') DEFAULT 'unread'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `notification`
--

INSERT INTO `notification` (`notification_id`, `user_id`, `message_type`, `message_content`, `date_sent`, `status`) VALUES
(1, 1, 'allocation', 'You have been allocated to roomA003forSemester 1 of the academic year2026-2027.', '2026-06-25 15:30:39', 'read'),
(2, 3, 'allocation', 'You have been allocated to roomA004forSemester 2 of the academic year2026-2027.', '2026-07-02 08:44:59', 'unread'),
(3, 4, 'allocation', 'You have been allocated to roomA004forFull Year of the academic year2026-2027.', '2026-07-03 01:50:53', 'read'),
(4, 1, 'allocation', 'Your room has been changed from Room A003 to Room A002 for the academic year 2026-2027.', '2026-07-04 04:31:23', 'read'),
(5, 4, 'allocation', 'Your room has been changed from Room A004 to Room A003 for the academic year 2026-2027.', '2026-07-04 04:35:19', 'read'),
(6, 4, 'allocation', 'You have been allocated to roomA004forFull Year of the academic year2026-2027.', '2026-07-12 05:07:28', 'read'),
(7, 6, 'allocation', 'You have been allocated to room A003 for Semester 3 of the academic year 2026-2027.', '2026-07-12 09:45:29', 'read'),
(8, 5, 'allocation', 'You have been allocated to room A005 for Semester 2 of the academic year 2026-2027.', '2026-07-12 09:54:25', 'unread'),
(9, 7, 'allocation', 'You have been allocated to room A007 for Semester 2 of the academic year 2026-2027.', '2026-07-12 10:18:36', 'unread'),
(10, 8, 'allocation', 'You have been allocated to room A007 for Semester 2 of the academic year 2026-2027.', '2026-07-16 09:14:18', 'read'),
(11, 14, 'allocation', 'You have been allocated to room A202 for Semester 2 of the academic year 2026-2027.', '2026-07-18 12:17:01', 'unread'),
(12, 7, 'allocation', 'You have been allocated to room A204 for Semester 2 of the academic year 2026-2027.', '2026-07-18 12:26:48', 'unread'),
(13, 10, 'allocation', 'You have been allocated to room A206 for Semester 2 of the academic year 2026-2027.', '2026-07-18 15:04:23', 'unread'),
(14, 15, 'allocation', 'You have been allocated to room A206 for Semester 2 of the academic year 2026-2027.', '2026-07-18 15:04:39', 'read'),
(15, 15, 'allocation', 'You have been allocated to room A206 for Semester 2 of the academic year 2026-2027.', '2026-07-20 05:46:55', 'read'),
(16, 15, 'allocation_removed', 'Your room allocation (Room A206) has been removed. Please vacate the room. To be re-allocated, update your preferences and submit them again.', '2026-07-20 05:47:10', 'read'),
(17, 11, 'allocation', 'You have been allocated to room A208 for Semester 1 of the academic year 2026-2027.', '2026-09-02 17:26:19', 'read'),
(18, 13, 'allocation', 'You have been allocated to room A210 for Semester 1 of the academic year 2026-2027.', '2026-09-11 05:00:01', 'unread'),
(19, 13, 'allocation_removed', 'Your room allocation (Room A210) has been removed. Please vacate the room. To be re-allocated, update your preferences and submit them again.', '2026-09-11 05:00:18', 'unread'),
(20, 22, 'allocation', 'You have been allocated to room A005 for Semester 1 of the academic year 2026-2027.', '2026-09-16 19:52:14', 'unread');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `allocation_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_date` date NOT NULL,
  `payment_method` enum('mpesa','bank') DEFAULT 'mpesa',
  `status` enum('paid','pending','partial') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `preference`
--

CREATE TABLE `preference` (
  `preference_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `sleep_schedule` enum('early','night','flexible') NOT NULL,
  `cleanliness_level` int(11) NOT NULL CHECK (`cleanliness_level` between 1 and 5),
  `study_habits` int(11) DEFAULT NULL,
  `noise_tolerance` int(11) NOT NULL CHECK (`noise_tolerance` between 1 and 5),
  `social_level` int(11) NOT NULL CHECK (`social_level` between 1 and 5),
  `submission_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `preferred_room_type` enum('single','double') DEFAULT 'double',
  `preferred_period` enum('Semester 1','Semester 2') NOT NULL DEFAULT 'Semester 1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `preference`
--

INSERT INTO `preference` (`preference_id`, `student_id`, `sleep_schedule`, `cleanliness_level`, `study_habits`, `noise_tolerance`, `social_level`, `submission_date`, `preferred_room_type`, `preferred_period`) VALUES
(1, 1, 'night', 5, 2, 2, 4, '2026-06-25 14:54:59', 'double', 'Semester 1'),
(2, 2, 'early', 3, 3, 1, 3, '2026-06-25 14:59:55', 'double', 'Semester 2'),
(3, 3, 'early', 1, 2, 2, 3, '2026-07-18 06:02:02', 'double', 'Semester 1'),
(4, 5, 'flexible', 3, 2, 3, 3, '2026-07-03 15:00:35', 'double', 'Semester 1'),
(5, 4, 'early', 5, 3, 2, 2, '2026-07-12 09:53:40', 'double', 'Semester 2'),
(6, 6, 'early', 2, 2, 2, 2, '2026-07-12 10:10:23', 'double', 'Semester 2'),
(7, 7, 'early', 3, 3, 2, 1, '2026-08-01 06:25:55', 'double', 'Semester 1'),
(8, 8, 'early', 2, 3, 1, 1, '2026-07-17 08:23:29', 'double', 'Semester 2'),
(9, 9, 'early', 1, 3, 1, 5, '2026-07-17 09:27:14', 'double', 'Semester 1'),
(10, 11, 'early', 3, 2, 4, 5, '2026-07-18 06:23:25', 'double', 'Semester 2'),
(11, 12, 'early', 2, 3, 1, 1, '2026-07-18 15:03:25', 'double', 'Semester 2'),
(12, 16, 'early', 1, 2, 2, 1, '2026-07-20 17:28:37', 'single', 'Semester 1'),
(13, 10, 'early', 1, 3, 1, 5, '2026-07-20 18:10:33', 'double', 'Semester 1'),
(14, 13, 'early', 4, 1, 2, 3, '2026-09-12 14:20:37', 'double', 'Semester 1'),
(15, 14, 'early', 4, 2, 2, 2, '2026-09-12 14:21:53', 'double', 'Semester 1'),
(16, 15, 'night', 4, 3, 4, 2, '2026-09-12 14:22:52', 'single', 'Semester 1'),
(17, 19, 'early', 3, 2, 2, 3, '2026-09-16 19:50:29', 'double', 'Semester 1');

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `room_id` int(11) NOT NULL,
  `room_number` varchar(10) NOT NULL,
  `capacity` int(11) NOT NULL,
  `room_type` enum('single','double') NOT NULL,
  `fee_per_semester` decimal(10,2) DEFAULT 10000.00
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`room_id`, `room_number`, `capacity`, `room_type`, `fee_per_semester`) VALUES
(2, 'A002', 1, 'single', 15000.00),
(3, 'A003', 2, 'double', 10000.00),
(4, 'A004', 2, 'double', 10000.00),
(5, 'A005', 2, 'double', 10000.00),
(6, 'A006', 1, 'single', 15000.00),
(7, 'A007', 2, 'double', 10000.00),
(8, 'A201', 1, 'single', 15000.00),
(9, 'A202', 2, 'double', 10000.00),
(10, 'A203', 1, 'single', 15000.00),
(11, 'A204', 2, 'double', 10000.00),
(12, 'A205', 1, 'single', 15000.00),
(13, 'A206', 2, 'double', 10000.00),
(14, 'A207', 1, 'single', 15000.00),
(15, 'A208', 2, 'double', 10000.00),
(16, 'A209', 1, 'single', 15000.00),
(17, 'A210', 2, 'double', 10000.00),
(19, 'A211', 2, 'double', 10000.00),
(20, 'B090', 2, 'double', 10000.00);

--
-- Triggers `room`
--
DELIMITER $$
CREATE TRIGGER `set_room_fee` BEFORE INSERT ON `room` FOR EACH ROW BEGIN
    IF NEW.room_type = 'Single' THEN
        SET NEW.fee_per_semester = 15000;
    ELSEIF NEW.room_type = 'Double' THEN
        SET NEW.fee_per_semester = 10000;
    ELSE
        SET NEW.fee_per_semester = 10000;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `student_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `reg_no` varchar(20) NOT NULL,
  `gender` enum('M','F','OTHER') NOT NULL,
  `course` varchar(100) NOT NULL,
  `year_of_study` int(11) NOT NULL,
  `phone` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`student_id`, `user_id`, `full_name`, `reg_no`, `gender`, `course`, `year_of_study`, `phone`) VALUES
(1, 1, 'Faith Maji', '1057090', 'F', 'Education', 2, '0113487325'),
(2, 3, 'Abigail Achieng', '1049034', 'F', 'Economics', 2, '0701089780'),
(3, 4, 'Jane Doe', '1023456', 'F', 'Nursing', 4, '0744566334'),
(4, 5, 'Grace Wanjiru', '1070890', 'F', 'Engineering ', 3, '0701089780'),
(5, 6, 'Jennifer wawira', '1045326', 'F', 'Law', 3, '0789955423'),
(6, 7, 'Julius Mugo', '1060980', 'M', 'Engineering ', 2, '0793278632'),
(7, 8, 'Paustine Njeri', '1040890', 'F', 'Education', 3, 'xyz'),
(8, 10, 'George Njenga', '1020350', 'M', 'computer science', 3, '0788336635'),
(9, 11, 'Enwel Njuguna', '1076443', 'M', 'computer science', 3, '0785553664'),
(10, 13, 'Phillip Juma', '1090560', 'M', 'Education', 4, '0114557880'),
(11, 14, 'Ethan Ketip', '1089234', 'M', 'Theology', 3, '07654432235'),
(12, 15, 'Raymond Kuria', '1070654', 'M', 'Law', 4, '0765234560'),
(13, 16, 'Norah Mwanza', '1098453', 'F', 'International Relations', 3, '0712343590'),
(14, 17, 'Joshua Baraka', '1023670', 'M', 'Engineering ', 4, '0115678065'),
(15, 18, 'Ann Wairimu', '1045890', 'F', 'Nursing', 4, '0713345566'),
(16, 19, 'Josephine Achieng', '1056039', 'F', 'Education', 4, '0798023456'),
(17, 20, 'Phyllis Mbuthia', '1050870', 'F', 'Education', 3, '0722225647'),
(18, 21, 'Ruth Wanjiru', '1049086', 'F', 'Nursing', 3, '0789122536'),
(19, 22, 'Babra wanjiku', '1059080', 'F', 'computer science', 3, '0709918266');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `administrator`
--
ALTER TABLE `administrator`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `allocation`
--
ALTER TABLE `allocation`
  ADD PRIMARY KEY (`allocation_id`),
  ADD UNIQUE KEY `unique_active_allocation` (`student_id`,`status`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `main_user`
--
ALTER TABLE `main_user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `allocation_id` (`allocation_id`);

--
-- Indexes for table `preference`
--
ALTER TABLE `preference`
  ADD PRIMARY KEY (`preference_id`),
  ADD UNIQUE KEY `student_id` (`student_id`),
  ADD UNIQUE KEY `student_id_2` (`student_id`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`room_id`),
  ADD UNIQUE KEY `room_number` (`room_number`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `reg_no` (`reg_no`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `administrator`
--
ALTER TABLE `administrator`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `allocation`
--
ALTER TABLE `allocation`
  MODIFY `allocation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `main_user`
--
ALTER TABLE `main_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `preference`
--
ALTER TABLE `preference`
  MODIFY `preference_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `room`
--
ALTER TABLE `room`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `administrator`
--
ALTER TABLE `administrator`
  ADD CONSTRAINT `administrator_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `main_user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `allocation`
--
ALTER TABLE `allocation`
  ADD CONSTRAINT `allocation_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `allocation_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `room` (`room_id`) ON DELETE CASCADE;

--
-- Constraints for table `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `notification_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `main_user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`allocation_id`) REFERENCES `allocation` (`allocation_id`);

--
-- Constraints for table `preference`
--
ALTER TABLE `preference`
  ADD CONSTRAINT `preference_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`) ON DELETE CASCADE;

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `main_user` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
