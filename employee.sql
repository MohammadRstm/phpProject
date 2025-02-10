-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 10, 2025 at 06:06 PM
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
  `dateOfRetirement` date DEFAULT NULL,
  `profilePicture` varchar(255) DEFAULT '643353.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`ID`, `managerID`, `firstName`, `lastName`, `age`, `userName`, `employeePasswordHash`, `dateOfEntry`, `dateOfRetirement`, `profilePicture`) VALUES
(3000, 2, 'malak', 'safadi', 20, 'malakSafadi', '23648435', '2025-02-06', NULL, '643353.png'),
(3001, 2, 'Mahdi', 'ghrayeb', 25, 'mahdi', '7539885', '2025-02-06', NULL, '643353.png'),
(3002, 2, 'ghada', 'solbe', 22, 'gahadaSOLbee', '74834925', '2025-02-06', NULL, '643353.png'),
(3003, 2, 'Fatima', 'Rostom', 36, 'fatima', '46387464', '2025-02-06', NULL, '643353.png'),
(3005, 2, 'sarah', 'baraket', 34, 'sara', '28394759', '2025-02-06', NULL, '643353.png'),
(3006, 2, 'Hamdoun', 'ramadan', 44, 'hamdoun', '4873472', '2025-02-06', NULL, '643353.png'),
(3007, 2, 'karim', 'restom', 23, 'karim', '29478342', '2025-02-06', NULL, '643353.png'),
(3012, 2, 'mohammad', 'restom', 20, 'mohammadrstm', '1234567', '2025-02-10', NULL, '643353.png'),
(3013, 2000, 'nour', 'restom', 18, 'nourelrosto', 'Country!1', '2025-02-10', NULL, '643353.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `managerID` (`managerID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3014;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `employee`
--
ALTER TABLE `employee`
  ADD CONSTRAINT `employee_ibfk_1` FOREIGN KEY (`managerID`) REFERENCES `manager` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
