-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 07, 2024 at 02:53 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ckt`
--

-- --------------------------------------------------------

--
-- Table structure for table `checklist`
--

CREATE TABLE `checklist` (
  `id` int(11) NOT NULL,
  `tanggal` datetime DEFAULT NULL,
  `toilet_id` int(11) NOT NULL,
  `users_id` int(11) NOT NULL,
  `kloset` tinyint(1) DEFAULT NULL,
  `wastafel` tinyint(1) DEFAULT NULL,
  `lantai` tinyint(1) DEFAULT NULL,
  `dinding` tinyint(1) DEFAULT NULL,
  `kaca` int(1) DEFAULT NULL,
  `bau` int(1) DEFAULT NULL,
  `sabun` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `checklist`
--

INSERT INTO `checklist` (`id`, `tanggal`, `toilet_id`, `users_id`, `kloset`, `wastafel`, `lantai`, `dinding`, `kaca`, `bau`, `sabun`) VALUES
(1, '2024-01-03 22:40:01', 1, 1, 1, 0, 1, 0, 0, 0, 0),
(2, '2024-01-04 00:26:13', 1, 1, 1, 1, 1, 1, 1, 1, 0),
(3, '2024-01-04 00:27:09', 1, 1, 1, 1, 1, 1, 1, 1, 0),
(4, '2024-01-04 00:50:31', 1, 1, 1, 2, 1, 1, 1, 2, 0),
(5, '2024-01-04 00:56:48', 1, 1, 3, 2, 3, 3, 3, 2, 0),
(6, '2024-01-04 00:59:17', 1, 1, 3, 2, 3, 3, 3, 2, 0),
(7, '2024-01-04 01:00:18', 1, 1, 1, 1, 1, 1, 1, 1, 0),
(8, '2024-01-04 01:01:18', 1, 1, 1, 1, 1, 1, 1, 1, 0),
(9, '2024-01-04 01:01:57', 1, 1, 1, 1, 1, 1, 1, 1, 2),
(10, '2024-01-04 01:02:07', 1, 1, 1, 1, 1, 1, 1, 1, 1),
(11, '2024-01-04 01:17:12', 1, 1, 3, 1, 2, 3, 2, 2, 1),
(12, '2024-01-04 01:33:39', 1, 1, 3, 3, 3, 3, 3, 2, 2),
(13, '2024-01-04 01:57:11', 1, 1, 1, 2, 3, 2, 1, 1, 1),
(14, '2024-01-05 01:18:39', 3, 1, 2, 3, 3, 2, 3, 1, 1),
(15, '2024-01-05 01:34:39', 3, 1, 2, 3, 3, 2, 3, 1, 1),
(17, '2024-01-05 21:35:34', 3, 1, 1, 1, 1, 1, 1, 1, 1),
(18, '2024-01-05 21:35:51', 1, 1, 3, 3, 3, 3, 3, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `toilet`
--

CREATE TABLE `toilet` (
  `id` int(11) NOT NULL,
  `lokasi` varchar(45) DEFAULT NULL,
  `keterangan` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `toilet`
--

INSERT INTO `toilet` (`id`, `lokasi`, `keterangan`) VALUES
(1, 'Toilet 1', 'kotor'),
(3, 'Security', 'Bau'),
(4, 'Mushola', 'Sabun tidak ada');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `name` varchar(200) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `role` tinyint(1) DEFAULT NULL COMMENT '1:Admin\r\n2:User'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `name`, `email`, `status`, `role`) VALUES
(1, 'nigga', '$2y$10$X8amu9o269fL31t7oqO6Ru2Hvi00ea87UxlSXuuoIeuhN8Ll47e22', 'nigga', 'nigga@gmail.com', 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `checklist`
--
ALTER TABLE `checklist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_checklist_toilet` (`toilet_id`),
  ADD KEY `fk_checklist_users` (`users_id`);

--
-- Indexes for table `toilet`
--
ALTER TABLE `toilet`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`),
  ADD UNIQUE KEY `username_UNIQUE` (`username`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `checklist`
--
ALTER TABLE `checklist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `toilet`
--
ALTER TABLE `toilet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `checklist`
--
ALTER TABLE `checklist`
  ADD CONSTRAINT `fk_checklist_toilet` FOREIGN KEY (`toilet_id`) REFERENCES `toilet` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_checklist_users` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
