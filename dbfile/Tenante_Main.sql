-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 19, 2024 at 06:32 AM
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
-- Database: `tenante_main`
--

-- --------------------------------------------------------

--
-- Table structure for table `email_config`
--

CREATE TABLE `email_config` (
  `id` int(145) NOT NULL,
  `email` varchar(145) DEFAULT NULL,
  `password` varchar(145) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `email_config`
--

INSERT INTO `email_config` (`id`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 'tenantemanagement@gmail.com', 'bqpn gijq kenu sxbl', '2024-09-21 16:44:32', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(14) NOT NULL,
  `user_id` int(14) NOT NULL,
  `name` varchar(50) NOT NULL,
  `guests` int(11) DEFAULT NULL,
  `activity` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `user_id`, `name`, `guests`, `activity`, `created_at`) VALUES
(51, 1, 'adie', 0, 'Has successfully signed in.', '2024-12-15 15:13:36'),
(52, 1, 'adie', 0, 'Has successfully signed in.', '2024-12-15 15:16:22'),
(53, 1, 'adie', 0, 'Has successfully signed in.', '2024-12-19 05:15:14');

-- --------------------------------------------------------

--
-- Table structure for table `rent_distribution`
--

CREATE TABLE `rent_distribution` (
  `id` int(11) NOT NULL,
  `room_no` int(11) NOT NULL,
  `elec` int(11) NOT NULL,
  `water` int(11) NOT NULL,
  `rent` int(11) NOT NULL,
  `wifi` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rent_distribution`
--

INSERT INTO `rent_distribution` (`id`, `room_no`, `elec`, `water`, `rent`, `wifi`) VALUES
(1, 1, 650, 750, 5000, 1500),
(2, 2, 2000, 1200, 5000, 1500),
(3, 3, 1200, 500, 5000, 1500);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `fullname` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `usertype` varchar(50) NOT NULL DEFAULT 'user',
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `birthday` date DEFAULT NULL,
  `civil_status` enum('single','married','divorced','widowed') NOT NULL DEFAULT 'single',
  `gender` enum('Male','Female','Other','') NOT NULL DEFAULT 'Other',
  `profile_image` varchar(255) DEFAULT 'uploads/profile_pictures/default_profile.jpg',
  `password` varchar(500) DEFAULT NULL,
  `reset_token` varchar(400) DEFAULT NULL,
  `token_expiry` datetime DEFAULT NULL,
  `status` enum('not_active','active') NOT NULL DEFAULT 'active',
  `tokencode` varchar(400) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `fullname`, `email`, `usertype`, `firstname`, `lastname`, `birthday`, `civil_status`, `gender`, `profile_image`, `password`, `reset_token`, `token_expiry`, `status`, `tokencode`, `created_at`) VALUES
(1, 'adie', 'adiesayu928@gmail.com', 'user', 'jonatan', 'mekeni', '2000-12-08', 'single', 'Male', 'uploads/profile_pictures/profile_6759bc56931770.20207478.png', '827ccb0eea8a706c4c34a16891f84e7b', 'b35a791797f429667ecd96a26130b83d83c0d1d993c39f33c59e981dd0e8a093', '2024-12-10 15:28:32', 'active', NULL, '2024-12-09 11:02:05'),
(2, 'Adrian', 'drpepper3k@gmail.com', 'user', 'Adruia', 'Lolad', '0033-12-03', 'widowed', 'Female', 'uploads/profile_pictures/profile_6754667007c3b8.35262126.png', '202cb962ac59075b964b07152d234b70', NULL, NULL, 'active', NULL, '2024-11-22 22:09:43'),
(4, 'Miko', 'alucifer757@gmail.com', 'user', '123', 'adasd123', '0023-12-31', 'divorced', 'Female', 'uploads/profile_pictures/profile_6754635193f7c9.56919858.png', 'c13367945d5d4c91047b3b50234aa7ab', NULL, NULL, 'active', NULL, '2024-12-07 06:48:31'),
(5, 'Lelouch vi Britannia', 'idkfamhelpme@gmail.com', 'user', 'alsdkoka', 'Lelo123', '0144-04-04', 'single', 'Female', 'uploads/profile_pictures/profile_67546641d61165.02221014.png', '202cb962ac59075b964b07152d234b70', NULL, NULL, 'active', NULL, '2024-12-07 07:13:00'),
(7, 'admin', 'tenantemanagement@gmail.com', 'admin', '', '', '0000-00-00', 'single', 'Other', 'uploads/profile_pictures/default_profile.jpg', '827ccb0eea8a706c4c34a16891f84e7b', NULL, NULL, 'active', NULL, '2024-11-23 22:46:26'),
(9, 'pog1lndlord', 'luis.dabu1226@gmail.com', 'landlord', '', '', NULL, 'single', 'Other', 'uploads/profile_pictures/default_profile.jpg', '202cb962ac59075b964b07152d234b70', NULL, NULL, 'active', NULL, '2024-12-10 01:13:39');

-- --------------------------------------------------------

--
-- Table structure for table `user_bills`
--

CREATE TABLE `user_bills` (
  `id` int(11) NOT NULL,
  `room_no` int(2) NOT NULL,
  `name` varchar(50) NOT NULL,
  `user_details` int(11) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `balance` int(11) NOT NULL,
  `electricity` decimal(10,2) NOT NULL,
  `water` decimal(10,2) NOT NULL,
  `rent` int(11) NOT NULL,
  `wifi` int(11) NOT NULL,
  `due_date` date NOT NULL,
  `unpaid_amt` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_bills`
--

INSERT INTO `user_bills` (`id`, `room_no`, `name`, `user_details`, `email`, `balance`, `electricity`, `water`, `rent`, `wifi`, `due_date`, `unpaid_amt`) VALUES
(1, 2, 'adie', 1, 'adiesayu928@gmail.com', 5500, 1000.00, 600.00, 5000, 1500, '2025-01-03', 0.00),
(2, 1, 'Adrian', 2, 'drpepper3k@gmail.com', 0, 0.00, 325.00, 5000, 1500, '2025-01-03', 0.00),
(4, 2, 'Miko', 4, 'alucifer757@gmail.com', 6500, 1000.00, 600.00, 5000, 1500, '2025-01-03', 0.00),
(5, 3, 'Lelouch vi Britannia', 5, 'idkfamhelpme@gmail.com', 6500, 3000.00, 1600.00, 5000, 1500, '2025-01-03', 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `user_comments`
--

CREATE TABLE `user_comments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `address` varchar(25) NOT NULL,
  `type` varchar(20) NOT NULL,
  `comment` text NOT NULL,
  `commented_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_notification`
--

CREATE TABLE `user_notification` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `sent_by` varchar(255) NOT NULL,
  `notif` text NOT NULL,
  `time_sent` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `rent_distribution`
--
ALTER TABLE `rent_distribution`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_bills`
--
ALTER TABLE `user_bills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_details` (`user_details`);

--
-- Indexes for table `user_comments`
--
ALTER TABLE `user_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_notification`
--
ALTER TABLE `user_notification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `rent_distribution`
--
ALTER TABLE `rent_distribution`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user_bills`
--
ALTER TABLE `user_bills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user_comments`
--
ALTER TABLE `user_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `user_notification`
--
ALTER TABLE `user_notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_bills`
--
ALTER TABLE `user_bills`
  ADD CONSTRAINT `user_bills_ibfk_1` FOREIGN KEY (`user_details`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
