-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 01, 2024 at 07:36 PM
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
-- Database: `slms_example_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `follow_up_remarks`
--

CREATE TABLE `follow_up_remarks` (
  `id` int(11) NOT NULL,
  `applicant_id` int(11) NOT NULL,
  `remarks` varchar(255) NOT NULL,
  `next_follow_up_date` date DEFAULT NULL,
  `follow_up_status` varchar(50) DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `follow_up_remarks`
--
DELIMITER $$
CREATE TRIGGER `update_status_after_remark_insert` AFTER INSERT ON `follow_up_remarks` FOR EACH ROW BEGIN
    UPDATE interested_applicants
    SET status = (SELECT A FROM master WHERE ID = NEW.follow_up_status)
    WHERE id = NEW.applicant_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `interested_applicants`
--

CREATE TABLE `interested_applicants` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` varchar(255) NOT NULL,
  `applying_for_class` varchar(255) NOT NULL,
  `query` text NOT NULL,
  `date_submitted` date DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Unchecked'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `master`
--

CREATE TABLE `master` (
  `ID` int(11) NOT NULL,
  `A` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `master`
--
DELIMITER $$
CREATE TRIGGER `update_status_on_master_rename` AFTER UPDATE ON `master` FOR EACH ROW BEGIN
  IF NEW.A <> OLD.A THEN  -- Check if the master name has changed
    UPDATE interested_applicants
    SET status = NEW.A  -- Set the status to the new master name
    WHERE status = OLD.A;  -- Update based on the old master name in the "status" column
  END IF;
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `follow_up_remarks`
--
ALTER TABLE `follow_up_remarks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `applicant_id` (`applicant_id`);

--
-- Indexes for table `interested_applicants`
--
ALTER TABLE `interested_applicants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `master`
--
ALTER TABLE `master`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `follow_up_remarks`
--
ALTER TABLE `follow_up_remarks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `interested_applicants`
--
ALTER TABLE `interested_applicants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT for table `master`
--
ALTER TABLE `master`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `follow_up_remarks`
--
ALTER TABLE `follow_up_remarks`
  ADD CONSTRAINT `follow_up_remarks_ibfk_1` FOREIGN KEY (`applicant_id`) REFERENCES `interested_applicants` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
