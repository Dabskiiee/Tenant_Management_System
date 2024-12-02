-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 30, 2024 at 10:49 AM
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
-- Database: `itelec5`
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
(1, 'maglaquimiko55@gmail.com', 'gdlp anev qwfm bxqb', '2024-09-22 08:44:32', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(14) NOT NULL,
  `user_id` int(14) NOT NULL,
  `guests` int(11) DEFAULT NULL,
  `activity` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `user_id`, `guests`, `activity`, `created_at`) VALUES
(69, 49, 0, 'Has successfully signed in.', '2024-11-29 12:55:47'),
(70, 49, 0, 'Has successfully signed in.', '2024-11-29 13:32:25'),
(71, 49, 0, 'Has successfully signed in.', '2024-11-29 16:33:21'),
(72, 49, 0, 'Has successfully signed in.', '2024-11-29 18:10:06');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `profile_pic` varchar(400) DEFAULT NULL,
  `fullname` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `usertype` varchar(50) NOT NULL DEFAULT 'user',
  `rent_bill` int(50) NOT NULL,
  `water_bill` decimal(50,0) NOT NULL,
  `elec_bill` decimal(50,0) NOT NULL,
  `wifi_bill` int(50) NOT NULL,
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

INSERT INTO `user` (`id`, `profile_pic`, `fullname`, `email`, `usertype`, `rent_bill`, `water_bill`, `elec_bill`, `wifi_bill`, `password`, `reset_token`, `token_expiry`, `status`, `tokencode`, `created_at`) VALUES
(31, NULL, 'Adrian Miko C. Maglaqui', 'drpepper3k@gmail.com', 'user', 10000, 415, 1912, 1700, '202cb962ac59075b964b07152d234b70', NULL, NULL, 'active', NULL, '2024-11-23 06:09:43'),
(33, NULL, 'alan john', 'ajjohn152129@gmail.com', 'admin', 0, 0, 0, 0, '827ccb0eea8a706c4c34a16891f84e7b', NULL, NULL, 'active', NULL, '2024-11-24 06:46:26'),
(49, NULL, 'adie', 'adiesayu928@gmail.com', 'user', 0, 0, 0, 0, '827ccb0eea8a706c4c34a16891f84e7b', NULL, NULL, 'active', NULL, '2024-11-27 15:39:32');

-- --------------------------------------------------------

--
-- Table structure for table `user_bills`
--

CREATE TABLE `user_bills` (
  `id` int(11) NOT NULL,
  `user_details` int(11) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `balance` int(11) NOT NULL,
  `electricity` decimal(10,2) NOT NULL,
  `water` decimal(10,2) NOT NULL,
  `rent` int(11) NOT NULL,
  `wifi` int(11) NOT NULL,
  `due_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_bills`
--

INSERT INTO `user_bills` (`id`, `user_details`, `email`, `balance`, `electricity`, `water`, `rent`, `wifi`, `due_date`) VALUES
(1, 31, 'drpepper3k@gmail.com', 0, 1912.15, 415.12, 5000, 1500, '2024-12-01'),
(10, 49, 'adiesayu928@gmail.com', 6500, 0.00, 0.00, 5000, 1500, '2025-01-03');

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `user_bills`
--
ALTER TABLE `user_bills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
  ADD CONSTRAINT `user_bills_ibfk_1` FOREIGN KEY (`user_details`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
