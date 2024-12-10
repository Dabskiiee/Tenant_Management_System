-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 09, 2024 at 05:33 AM
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
Database: `Tenante_Main`
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
(72, 49, 0, 'Has successfully signed in.', '2024-11-29 18:10:06'),
(73, 50, 0, 'Has successfully signed in.', '2024-12-07 14:54:51'),
(74, 50, 0, 'Has successfully signed in.', '2024-12-07 15:01:03'),
(75, 51, 1, 'Has successfully signed in.', '2024-12-07 15:13:10'),
(76, 51, 0, 'Has successfully signed in.', '2024-12-07 15:14:33'),
(77, 31, 0, 'Has successfully signed in.', '2024-12-07 15:14:40'),
(78, 31, 0, 'Has successfully signed in.', '2024-12-07 15:16:32'),
(79, 51, 0, 'Has successfully signed in.', '2024-12-07 15:17:01'),
(80, 51, 0, 'Has successfully signed in.', '2024-12-07 15:23:40'),
(81, 51, 0, 'Has successfully signed in.', '2024-12-07 15:23:51'),
(82, 51, 0, 'Has successfully signed in.', '2024-12-07 15:39:00'),
(83, 51, 0, 'Has successfully signed in.', '2024-12-07 15:39:38'),
(84, 51, 0, 'Has successfully signed in.', '2024-12-07 15:42:00'),
(85, 51, 0, 'Has successfully signed in.', '2024-12-07 15:42:07'),
(86, 51, 0, 'Has successfully signed in.', '2024-12-07 15:42:17'),
(87, 51, 0, 'Has successfully signed in.', '2024-12-07 15:42:37');

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
(0, 'Adrian', 'drpepper3k@gmail.com', 'user', 'Adruia', 'Lolad', '0033-12-03', 'widowed', 'Female', 'uploads/profile_pictures/profile_6754667007c3b8.35262126.png', '202cb962ac59075b964b07152d234b70', NULL, NULL, 'active', NULL, '2024-11-23 06:09:43'),
(0, 'alan john', 'ajjohn152129@gmail.com', 'admin', '0', '0', '0000-00-00', '', 'Other', 'default_profile.png', '827ccb0eea8a706c4c34a16891f84e7b', NULL, NULL, 'active', NULL, '2024-11-24 06:46:26'),
(0, 'Miko', 'alucifer757@gmail.com', 'user', '123', 'adasd123', '0023-12-31', 'divorced', 'Female', 'uploads/profile_pictures/profile_6754635193f7c9.56919858.png', 'c13367945d5d4c91047b3b50234aa7ab', NULL, NULL, 'active', NULL, '2024-12-07 14:48:31'),
(0, 'Lelouch vi Britannia', 'idkfamhelpme@gmail.com', 'user', 'alsdkoka', 'Lelo123', '0144-04-04', 'single', 'Female', 'uploads/profile_pictures/profile_67546641d61165.02221014.png', '202cb962ac59075b964b07152d234b70', NULL, NULL, 'active', NULL, '2024-12-07 15:13:00');

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
(0, 2, 'drpepper3k@gmail.com', 0, 1912.15, 415.12, 5000, 1500, '2024-12-01'),
(0, 3, 'adiesayu928@gmail.com', 6500, 0.00, 0.00, 5000, 1500, '2025-01-03'),
(0, 4, 'alucifer757@gmail.com', 6500, 0.00, 0.00, 5000, 1500, '2025-02-07'),
(0, 5, 'idkfamhelpme@gmail.com', 6500, 0.00, 0.00, 5000, 1500, '2025-02-07');

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
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `user_bills`
--
ALTER TABLE `user_bills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

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
