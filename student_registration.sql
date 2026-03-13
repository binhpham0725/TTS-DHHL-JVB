-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 13, 2026 at 02:02 AM
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
-- Database: `student_registration`
--

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `ho_ten` varchar(100) NOT NULL,
  `gioi_tinh` enum('Nam','Nữ') NOT NULL,
  `tuoi` int(10) UNSIGNED NOT NULL,
  `email` varchar(150) NOT NULL,
  `dia_chi` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `ho_ten`, `gioi_tinh`, `tuoi`, `email`, `dia_chi`) VALUES
(1, 'Nguyễn Văn A', 'Nam', 20, 'vana@example.com', 'Hà Nội'),
(4, 'Dư Ngọc Trương', 'Nam', 21, 'truong@gmail.com', 'Ninh Bình'),
(5, 'Trần Ngọc Anh', 'Nam', 22, 'ngocanh@gmail.com', 'Ninh Bình'),
(6, 'Phạm Thanh Bình', 'Nam', 21, 'binh@gmail.com', 'Ninh Bình'),
(7, 'Phạm Bảo Khoa', 'Nam', 21, 'khoa@gmail.com', 'Ninh Bình');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `birthday` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `birthday`) VALUES
(1, 'truonk', 'truong@gmail.com', '$2y$10$6Zc3GyazVSSu/LqRn4QK5.MaRibkuMXR57VpbDEGsELT84WewHVGG', '2005-04-23'),
(4, 'hello', 'hello@gmail.com', '$2y$10$TTXd5QKfHahB7RXmOo8LAe61mh3VrD/KjNbroitu67ij5Lni3umCC', '2026-03-13'),
(5, 'hehe', 'hehe@gmail.com', '$2y$10$gL34sbHwJAE9siI2xb7U2eheLxmql84aK2nEur5mi2b7mAIWMf42e', '2026-03-16'),
(6, 'thitcho', 'thitcho@gmail.com', '$2y$10$uXn.g47zx3izVvaMCYV6/O25Xse4YChx1ehRCjKaPcmpEIe2LHw6G', '2026-03-11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
