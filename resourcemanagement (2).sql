-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 09, 2025 at 04:32 AM
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
-- Database: `resourcemanagement`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(10) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `date_started_working` date NOT NULL,
  `date_of_retirement` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `first_name`, `last_name`, `username`, `password`, `date_started_working`, `date_of_retirement`) VALUES
(1, 'Mohammad', 'Rostom', 'mradmin', 'windows7', '0000-00-00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `ID` int(11) NOT NULL,
  `managerID` int(11) NOT NULL,
  `firstName` varchar(50) DEFAULT NULL,
  `lastName` varchar(50) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `userName` varchar(50) DEFAULT NULL,
  `employeePasswordHash` varchar(255) DEFAULT NULL,
  `dateOfEntry` date DEFAULT curdate(),
  `dateOfRetirement` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`ID`, `managerID`, `firstName`, `lastName`, `age`, `userName`, `employeePasswordHash`, `dateOfEntry`, `dateOfRetirement`) VALUES
(3000, 2, 'malak', 'safadi', 20, 'malakSafadi', '23648435', '2025-02-06', NULL),
(3001, 2, 'Mahdi', 'ghrayeb', 25, 'mahdi', '7539885', '2025-02-06', NULL),
(3002, 2, 'ghada', 'solbe', 22, 'gahadaSOLbee', '74834925', '2025-02-06', NULL),
(3003, 20, 'Fatima', 'Rostom', 36, 'fatima', '46387464', '2025-02-06', NULL),
(3004, 20, 'mariam', 'baraket', 24, 'marBaraket', '6574823', '2025-02-06', NULL),
(3005, 20, 'sarah', 'baraket', 34, 'sara', '28394759', '2025-02-06', NULL),
(3006, 20, 'Hamdoun', 'ramadan', 44, 'hamdoun', '4873472', '2025-02-06', NULL),
(3007, 2, 'karim', 'restom', 23, 'karim', '29478342', '2025-02-06', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `manager`
--

CREATE TABLE `manager` (
  `ID` int(11) NOT NULL,
  `firstName` varchar(50) DEFAULT NULL,
  `lastName` varchar(50) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `userName` varchar(50) DEFAULT NULL,
  `managerPasswordHash` varchar(255) DEFAULT NULL,
  `dateOfEntry` date DEFAULT curdate(),
  `dateOfRetirement` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `manager`
--

INSERT INTO `manager` (`ID`, `firstName`, `lastName`, `age`, `userName`, `managerPasswordHash`, `dateOfEntry`, `dateOfRetirement`) VALUES
(2, 'aysha', 'arab', 37, 'Aysha~', '2332485', '2025-02-06', NULL),
(20, 'hassan', 'malek', 34, 'hassanMalek', '4934735', '2025-02-06', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE `project` (
  `ID` int(10) NOT NULL,
  `projectName` varchar(100) DEFAULT NULL,
  `deadline` date DEFAULT NULL,
  `dateCreated` date DEFAULT curdate(),
  `projectDone` varchar(3) DEFAULT 'NO' COMMENT 'values ''yes'' or ''no''',
  `managerID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Project';

--
-- Dumping data for table `project`
--

INSERT INTO `project` (`ID`, `projectName`, `deadline`, `dateCreated`, `projectDone`, `managerID`) VALUES
(6000, 'project Alpha', '2025-03-01', '2025-02-07', 'NO', 2),
(6001, 'project Beta', '2025-03-02', '2025-02-07', 'NO', 2),
(6002, 'project Delta', '2025-03-03', '2025-02-07', 'NO', 2),
(6003, 'project Nexus', '2025-03-04', '2025-02-07', 'NO', 20),
(6004, 'project Horizon', '2025-03-05', '2025-02-07', 'NO', 20),
(6005, 'project Titan', '2025-03-06', '2025-02-07', 'NO', 20);

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `task_id` int(11) NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('Pending','In Progress','Completed','Blocked') DEFAULT 'Pending',
  `priority` enum('Low','Medium','High','Critical') DEFAULT 'Medium',
  `start_date` datetime DEFAULT current_timestamp(),
  `completed_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`task_id`, `project_id`, `assigned_to`, `title`, `description`, `status`, `priority`, `start_date`, `completed_at`) VALUES
(5001, 6002, 3004, 'Design Database Schema', 'Create ER diagrams and define database tables', 'Pending', 'Medium', '2025-02-07 00:42:23', NULL),
(5002, 6005, 3006, 'Create API Endpoints', 'Develop RESTful APIs for user and data management', 'Pending', 'Medium', '2025-02-07 00:42:23', NULL),
(5003, 6001, 3003, 'Set Up CI/CD Pipeline', 'Configure automated build and deployment processes', 'Pending', 'Medium', '2025-02-07 00:42:23', NULL),
(5004, 6004, 3007, 'Perform Security Audit', NULL, 'Pending', 'Medium', '2025-02-07 00:42:23', NULL),
(5005, 6003, 3000, 'Optimize Database Queries', 'Analyze slow queries and optimize performance', 'Pending', 'Medium', '2025-02-07 00:42:23', NULL),
(5007, 6001, 3005, 'Implement Payment Integration', 'Integrate Stripe and PayPal for transactions', 'Pending', 'Medium', '2025-02-07 00:42:23', NULL),
(5008, 6004, 3004, 'Write Unit Tests', NULL, 'Pending', 'Medium', '2025-02-07 00:42:23', NULL),
(5009, 6002, 3006, 'Deploy to Production', 'Ensure successful deployment on cloud infrastructure', 'Pending', 'Medium', '2025-02-07 00:42:23', NULL),
(5010, 6003, 3001, 'Set Up Load Balancer', 'Distribute traffic efficiently among servers', 'Pending', 'Medium', '2025-02-07 00:42:23', NULL),
(5011, 6005, 3007, 'Develop Mobile App Backend', NULL, 'Pending', 'Medium', '2025-02-07 00:42:23', NULL),
(5012, 6000, 3002, 'Create Marketing Plan', 'Outline the strategy for digital and offline marketing', 'Pending', 'Medium', '2025-02-07 00:42:23', NULL),
(5013, 6001, 3003, 'Implement Search Functionality', 'Add full-text search capability to the platform', 'Pending', 'Medium', '2025-02-07 00:42:23', NULL),
(5014, 6002, 3005, 'Configure Server Monitoring', NULL, 'Pending', 'Medium', '2025-02-07 00:42:23', NULL),
(5015, 6004, 3000, 'Write API Documentation', 'Document endpoints and response formats for developers', 'Pending', 'Medium', '2025-02-07 00:42:23', NULL),
(5016, 6003, 3006, 'Set Up Logging System', 'Implement logging for debugging and analytics', 'Pending', 'Medium', '2025-02-07 00:42:23', NULL),
(5017, 6001, 3004, 'Develop Admin Dashboard', 'Create an interface for administrators to manage users', 'Pending', 'Medium', '2025-02-07 00:42:23', NULL),
(5018, 6002, 3007, 'Optimize Frontend Performance', NULL, 'Pending', 'Medium', '2025-02-07 00:42:23', NULL),
(5019, 6005, 3002, 'Conduct Usability Testing', 'Gather user feedback and improve UI/UX', 'Pending', 'Medium', '2025-02-07 00:42:23', NULL),
(5020, 6000, 3001, 'Change data types', 'change data types to linked lists', 'Pending', 'High', '2025-02-08 09:41:58', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `managerID` (`managerID`);

--
-- Indexes for table `manager`
--
ALTER TABLE `manager`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_managerID` (`managerID`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`task_id`),
  ADD KEY `assigned_to` (`assigned_to`),
  ADD KEY `tasks_ibfk_1` (`project_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3008;

--
-- AUTO_INCREMENT for table `manager`
--
ALTER TABLE `manager`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2000;

--
-- AUTO_INCREMENT for table `project`
--
ALTER TABLE `project`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6006;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5025;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `employee`
--
ALTER TABLE `employee`
  ADD CONSTRAINT `employee_ibfk_1` FOREIGN KEY (`managerID`) REFERENCES `manager` (`ID`);

--
-- Constraints for table `project`
--
ALTER TABLE `project`
  ADD CONSTRAINT `fk_managerID` FOREIGN KEY (`managerID`) REFERENCES `manager` (`ID`);

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `project` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `tasks_ibfk_2` FOREIGN KEY (`assigned_to`) REFERENCES `employee` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
