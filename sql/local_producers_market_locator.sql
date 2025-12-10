-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 05, 2025 at 12:32 PM
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
-- Database: `local_producers_market_locator`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role_id` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `username`, `email`, `password`, `full_name`, `created_at`, `role_id`) VALUES
(0, 'admin', 'admin@localmarket.com', '$2y$10$CVx1aIfNRT./A41mWJJGX.omHoZEU1dEYBlfJm3G3s4KCWHvd0s8O', 'System Administrator', '2025-11-10 16:20:02', 1),
(17, 'adminsample', 'admin@example.com', '$2y$10$k8tCssAjWsG6yP8ohzFKmOO1ELT7cJGrBYmzk20GOZW3etdddNrOK', 'Admin Sample', '2025-12-05 10:23:52', 2);

-- --------------------------------------------------------

--
-- Table structure for table `admin_help_requests`
--

CREATE TABLE `admin_help_requests` (
  `request_id` int(11) NOT NULL,
  `producer_id` int(11) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message_text` text DEFAULT NULL,
  `request_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Open','In Progress','Resolved') DEFAULT 'Open',
  `admin_response` text DEFAULT NULL,
  `date_responded` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_help_requests`
--

INSERT INTO `admin_help_requests` (`request_id`, `producer_id`, `subject`, `message_text`, `request_date`, `status`, `admin_response`, `date_responded`) VALUES
(3, 7, 'Product', 'Help po i can\'t add any products', '2025-11-28 13:27:52', 'Resolved', 'I already fixed the problem po ma\'am.', '2025-12-04 16:02:06'),
(5, 7, 'Schedule', 'PANO PO PAG ADD SCHEDULE?', '2025-11-28 13:48:57', 'Resolved', 'REsolve', '2025-12-04 16:16:23'),
(8, 7, 'FARM PROFILE', 'HINDI PO MAKAADD NG LOCATION', '2025-11-28 15:56:17', 'In Progress', 'wait po', '2025-12-04 13:48:14'),
(9, 8, 'FARM PROFILE', 'DI PO MAKA UPDATE NANG PROFILE', '2025-11-28 16:06:11', 'Resolved', 'Na resolved ko na po yung issue maam', '2025-11-28 16:30:24'),
(10, 8, 'Schedule', 'DI PO MAKA UPDATE SCHEDULE', '2025-11-28 16:27:05', 'Resolved', 'RESOLVED', '2025-12-04 13:48:30'),
(11, 8, 'Product', 'Di po makaadd ng product', '2025-11-28 16:30:56', 'Resolved', 'I already solved the problem po maam', '2025-12-04 12:09:57'),
(14, 8, 'Product', 'KAY NAMAN DIRE NAKAKA ADD', '2025-12-01 06:46:35', 'Resolved', 'okay na po maam', '2025-12-04 12:20:29'),
(15, 7, 'profile', 'can\'t add po address', '2025-12-04 16:03:13', 'In Progress', 'Okay wait po process ko lang', '2025-12-05 10:25:47'),
(16, 8, 'Product', 'add product', '2025-12-05 11:31:42', 'Open', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `audience` varchar(100) NOT NULL,
  `status` varchar(20) DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `consumer_reviews`
--

CREATE TABLE `consumer_reviews` (
  `review_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `farm_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `comment_text` text DEFAULT NULL,
  `review_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `consumer_reviews`
--

INSERT INTO `consumer_reviews` (`review_id`, `user_id`, `farm_id`, `rating`, `comment_text`, `review_date`) VALUES
(168, 38, 12, 5, '123123', '2025-11-26 16:12:37'),
(169, 32, 12, 2, 'wowow', '2025-11-28 15:44:49'),
(170, 32, 7, 5, 'wow', '2025-11-28 17:06:03'),
(171, 32, 7, 5, 'TANGINAMO DARWIN LIGTAS', '2025-12-01 06:43:08'),
(172, 32, 7, 5, 'galing', '2025-12-04 16:25:01');

-- --------------------------------------------------------

--
-- Table structure for table `farms`
--

CREATE TABLE `farms` (
  `farm_id` int(11) NOT NULL,
  `producer_id` int(11) NOT NULL,
  `farm_name` varchar(100) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `approval_status` enum('Pending','Approved','Rejected') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `farms`
--

INSERT INTO `farms` (`farm_id`, `producer_id`, `farm_name`, `address`, `city`, `phone_number`, `description`, `approval_status`) VALUES
(7, 8, 'JACKYLYN\'S OBRERO FARM', 'P-3 BRGY OBRERO', 'CALBAYOG CITY', '09213742341', 'WE HAVE BUNDLES OF ANY KINDS OF MEAT', 'Approved'),
(12, 7, 'MARYBETH ACASO FARM', 'P4 BRGY. MATOBATO', 'CALBAYOG CITY', '09360544637', 'PAKADI NA KAM SA AKON FARM MGA LANGGA', 'Approved'),
(13, 14, 'MATULUNGIN FARM', 'california', 'Calbayog City', '09213742341', 'OTEN', 'Approved'),
(14, 15, 'DAVE\'S FARM', 'BRGY. INUURAGYAW', 'CALBAYOG CITY', '09685723112', 'PAKADI NA KAM LIBRE MILKTEA', 'Approved');

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `favorite_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `farm_id` int(11) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `favorites`
--

INSERT INTO `favorites` (`favorite_id`, `user_id`, `farm_id`, `date_added`) VALUES
(32, 37, 7, '2025-11-26 14:47:03'),
(39, 32, 14, '2025-12-05 11:22:11'),
(40, 32, 12, '2025-12-05 11:22:12');

-- --------------------------------------------------------

--
-- Table structure for table `market_schedules`
--

CREATE TABLE `market_schedules` (
  `schedule_id` int(11) NOT NULL,
  `farm_id` int(11) NOT NULL,
  `day_of_week` varchar(20) DEFAULT NULL,
  `start_time` varchar(15) DEFAULT NULL,
  `end_time` varchar(15) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `last_updated_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `market_schedules`
--

INSERT INTO `market_schedules` (`schedule_id`, `farm_id`, `day_of_week`, `start_time`, `end_time`, `location`, `last_updated_date`) VALUES
(11, 12, 'Sunday', '05:00:00', '09:00:00', 'nijaga st. near st. paul cathedral', '2025-11-28 15:39:09'),
(12, 12, 'Wednesday', '05:00:00', '09:00:00', 'Calbayog Public Market', '2025-11-28 15:43:19'),
(13, 12, 'Monday', '06:00:00', '09:00:00', 'P3 BRGY. OBRERO', '2025-11-28 15:43:59'),
(14, 7, 'Thursday', '08:00:00', '09:00:00', 'nijaga st. near st. paul cathedral', '2025-12-05 11:30:32'),
(18, 7, 'Monday', '05:00:00', '12:00:00', 'Calbayog Public Market', '2025-12-05 11:31:07');

-- --------------------------------------------------------

--
-- Table structure for table `producers`
--

CREATE TABLE `producers` (
  `producer_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_verified` tinyint(1) DEFAULT 0,
  `role_id` int(11) DEFAULT 2,
  `account_status` varchar(10) GENERATED ALWAYS AS (case `is_verified` when 0 then 'pending' when 1 then 'verified' when 2 then 'canceled' else 'unknown' end) STORED,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `producers`
--

INSERT INTO `producers` (`producer_id`, `username`, `email`, `phone_number`, `password`, `reg_date`, `is_verified`, `role_id`, `status`) VALUES
(7, 'testsample', 'testsample@gmail.com', '09058054940', '$2y$10$BZlhJWU2UQoB22b2aLLDh.fsIJIHWh7HFGqyAkn/RGCbNINxV430u', '2025-11-12 15:35:32', 1, 2, 'Active'),
(8, 'Jackylynenero', 'jackylyn@gmail.com', '09058054940', '$2y$10$CYQ2k8qUkuUOkt.gNVPMqeYGKViCdqePJJMX6vYvQcoJ2Vf69Mj7C', '2025-11-12 16:07:52', 1, 2, 'Active'),
(9, 'johnalmer', 'johnalmer@gmail.com', '09213742341', '$2y$10$JbkElthBujxmAGIlBXEo2uOMSaFb/ZOHS3GSzO99f/FsURrrpsana', '2025-11-12 16:23:57', 1, 2, 'Active'),
(10, 'almeralontaga', 'almeralontaga@gmail.com', '09050809533', '$2y$10$aoiy/eGU1fXbfD4lwLlT6.N/rbS7jxgmZEFrU/SawAWbuhNplQHFi', '2025-11-24 03:49:36', 1, 2, 'Active'),
(13, 'jackylyn', 'jackymagan@gmail.com', '09123456789', '$2y$10$jfayQ10klXUi59SvKLQx.OjNZUcO8i4s/bu9HcDDTtJ5Tnl3RQpm2', '2025-11-26 16:09:22', 2, 2, 'Active'),
(14, 'almerr', 'almer@gmail.com', '09123456789', '$2y$10$sitACLo/f/IxlUjjDrNHJ.dPV/vWZVy.79qoWF5sTuiHynpG6hdei', '2025-11-30 10:45:47', 1, 2, 'Active'),
(15, 'davevillegas', 'davevillegas@gmail.com', '09685723112', '$2y$10$yniU7zQnLTvybDHQwH4j0e69pJVeT5mVaojlIO7v6o4aCI6m3Y8x.', '2025-12-04 09:19:39', 1, 2, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `farm_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `unit_price` decimal(10,2) DEFAULT NULL,
  `stock_quantity` int(11) DEFAULT NULL,
  `unit_of_measure` varchar(20) DEFAULT NULL,
  `last_updated_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `farm_id`, `product_name`, `category`, `unit_price`, `stock_quantity`, `unit_of_measure`, `last_updated_date`) VALUES
(9, 7, 'Grapes', 'Fruits', 300.00, 213, 'kg', '2025-12-05 11:18:08'),
(10, 7, 'mango', 'Fruits', 135.00, 123, 'kg', '2025-11-25 14:45:35'),
(15, 12, 'grapes', 'Fruits', 123.00, 12, 'kg', '2025-11-28 15:37:58'),
(16, 12, 'balanghoy', 'Others', 40.00, 50, 'kg', '2025-11-28 15:38:43'),
(17, 7, 'baboy', 'Meat', 350.00, 25, 'kg', '2025-11-28 16:24:02'),
(20, 7, 'Bugas', 'Grains', 60.00, 125, 'kg', '2025-12-05 11:30:17');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`) VALUES
(1, 'Admin'),
(3, 'Consumer'),
(2, 'Producer');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `role_id` int(11) DEFAULT 3,
  `account_status` enum('Active','Inactive') NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `phone_number`, `password`, `reg_date`, `role_id`, `account_status`) VALUES
(32, 'gnomesmooch', 'marionacaso74@gmail.com', '09959572543', '$2y$10$Tk9huJNjO/RD8FfgOubx2ObLPnA8d138LxB8eGiUSbGH4LN1A4o8O', '2025-11-19 12:38:54', 3, 'Active'),
(37, 'testsample1', 'testsample2@gmail.com', '09959572543', '$2y$10$sF9wUarQeiJHD.fHoOp6mOzugJ.7f97T08roxMe08R1f5dHSo.y0u', '2025-11-20 14:26:42', 3, 'Active'),
(38, 'marionberi', 'acasomarion5@gmail.com', '09058054940', '$2y$10$16o2/I1DuSrDUGKJqs3WyuWTgu1f5u5JeAEVcbDMbG0L1e8Vlm5xi', '2025-11-26 16:09:49', 3, 'Active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `admin_help_requests`
--
ALTER TABLE `admin_help_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `producer_id` (`producer_id`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `consumer_reviews`
--
ALTER TABLE `consumer_reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `farm_id` (`farm_id`);

--
-- Indexes for table `farms`
--
ALTER TABLE `farms`
  ADD PRIMARY KEY (`farm_id`),
  ADD KEY `producer_id` (`producer_id`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`favorite_id`),
  ADD UNIQUE KEY `unique_favorite` (`user_id`,`farm_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `farm_id` (`farm_id`);

--
-- Indexes for table `market_schedules`
--
ALTER TABLE `market_schedules`
  ADD PRIMARY KEY (`schedule_id`),
  ADD KEY `farm_id` (`farm_id`);

--
-- Indexes for table `producers`
--
ALTER TABLE `producers`
  ADD PRIMARY KEY (`producer_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `farm_id` (`farm_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`),
  ADD UNIQUE KEY `role_name` (`role_name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `admin_help_requests`
--
ALTER TABLE `admin_help_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `consumer_reviews`
--
ALTER TABLE `consumer_reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=173;

--
-- AUTO_INCREMENT for table `farms`
--
ALTER TABLE `farms`
  MODIFY `farm_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `favorite_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `market_schedules`
--
ALTER TABLE `market_schedules`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `producers`
--
ALTER TABLE `producers`
  MODIFY `producer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admins`
--
ALTER TABLE `admins`
  ADD CONSTRAINT `admins_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`) ON DELETE SET NULL;

--
-- Constraints for table `admin_help_requests`
--
ALTER TABLE `admin_help_requests`
  ADD CONSTRAINT `admin_help_requests_ibfk_1` FOREIGN KEY (`producer_id`) REFERENCES `producers` (`producer_id`) ON DELETE CASCADE;

--
-- Constraints for table `consumer_reviews`
--
ALTER TABLE `consumer_reviews`
  ADD CONSTRAINT `consumer_reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `consumer_reviews_ibfk_2` FOREIGN KEY (`farm_id`) REFERENCES `farms` (`farm_id`) ON DELETE CASCADE;

--
-- Constraints for table `farms`
--
ALTER TABLE `farms`
  ADD CONSTRAINT `farms_ibfk_1` FOREIGN KEY (`producer_id`) REFERENCES `producers` (`producer_id`) ON DELETE CASCADE;

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`farm_id`) REFERENCES `farms` (`farm_id`) ON DELETE CASCADE;

--
-- Constraints for table `market_schedules`
--
ALTER TABLE `market_schedules`
  ADD CONSTRAINT `market_schedules_ibfk_1` FOREIGN KEY (`farm_id`) REFERENCES `farms` (`farm_id`) ON DELETE CASCADE;

--
-- Constraints for table `producers`
--
ALTER TABLE `producers`
  ADD CONSTRAINT `producers_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`) ON DELETE SET NULL;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`farm_id`) REFERENCES `farms` (`farm_id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
