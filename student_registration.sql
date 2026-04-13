-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 06, 2026 at 05:25 AM
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
  `email` varchar(150) NOT NULL,
  `dia_chi` varchar(255) NOT NULL,
  `ngay_sinh` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `ho_ten`, `gioi_tinh`, `email`, `dia_chi`, `ngay_sinh`) VALUES
(1, 'Nguyễn Văn A', 'Nam', 'vana@example.com', 'Hà Nội', '2026-03-13'),
(4, 'Lê Quang Ấn', 'Nam', 'an@gmail.com', 'Ninh Bình', '2000-11-02'),
(5, 'Trần Ngọc Anhh', 'Nam', 'ngocanh@gmail.com', 'Ninh Bình', '2026-03-09'),
(6, 'Phạm Thanh Bình', 'Nam', 'binh@gmail.com', 'Ninh Bình', '2026-03-20'),
(7, 'Phạm Bảo Khoa', 'Nam', 'khoa@gmail.com', 'Ninh Bình', '2026-03-20'),
(25, 'Dư Ngọc Trương', 'Nam', 'truong@gmail.com', 'Ninh Bình', '2005-04-23'),
(27, 'Phạm Văn B', 'Nữ', 'b@gmail.com', 'Quảng Ninh', '2002-02-23'),
(28, 'Hà Đăng C', 'Nam', 'son@gmail.com', 'Hà Nam', '2005-03-07'),
(29, 'Ngô Hải D', 'Nam', 'd@gmail.com', 'Đà Nẵng', '2005-04-22'),
(30, 'Phạm Nhật V', 'Nam', 'v@gmail.com', 'Hồ Chí Minh', '2004-06-10'),
(31, 'Hồ Hoàng Demo', 'Nữ', 'demo@gmail.com', 'Hồ Chí Minh', '2001-02-03'),
(33, 'Đinh Huyền Trâm', 'Nữ', 'trang@gmail.com', 'Lạng Sơn', '2006-12-03'),
(36, 'Lil Uzi Vert', 'Nam', 'xotourlif3@gmail.com', 'USA', '1995-07-31');

-- --------------------------------------------------------

--
-- Table structure for table `student_academic`
--

CREATE TABLE `student_academic` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `chuyen_nganh` varchar(255) DEFAULT NULL,
  `gpa` float DEFAULT NULL,
  `tinh_trang` varchar(50) DEFAULT NULL,
  `xep_loai` varchar(50) DEFAULT NULL,
  `khoa_hoc` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `student_academic`
--

INSERT INTO `student_academic` (`id`, `student_id`, `chuyen_nganh`, `gpa`, `tinh_trang`, `xep_loai`, `khoa_hoc`) VALUES
(1, 25, 'CNTT', 4.4, 'Năm 4', 'Xuất sắc', '2023-2027'),
(3, 1, 'Kinh Tế', 4, 'Năm 3', 'Xuất sắc', '2022-2026'),
(4, 4, 'CNTT', 3, 'Năm 3', 'Giỏi', '2020-2024'),
(5, 5, 'CNTT', 3, 'Năm 1', 'Xuất sắc', '2023-2027'),
(6, 6, 'CNTT', 2, 'Năm 2', 'Khá', '2023-2027'),
(7, 7, 'CNTT', 3, 'Khác', 'Giỏi', '2024-2028'),
(8, 27, 'Quản Trị Kinh Doanh', 3, 'Năm 2', 'Giỏi', '2020-2024'),
(9, 28, 'Tâm Lý', 3, 'Đã tốt nghiệp', 'Giỏi', '2019-2023'),
(10, 29, 'Sư Phạm', 4, 'Năm 4', 'Xuất sắc', '2019-2023'),
(11, 30, 'Ngôn Ngữ', 4, 'Năm 3', 'Xuất sắc', '2012-2026'),
(12, 31, 'Demo', 0, 'Khác', 'Yếu', '2000-2004'),
(14, 33, 'Kiến Trúc', 2, 'Năm 1', 'Giỏi', '2021-2025'),
(17, 36, 'Rapper', 4, 'Đã tốt nghiệp', 'Xuất sắc', '2020 - 2024');

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
(7, 'demo', 'demo@gmail.com', '$2y$10$xZHUbf9ncbb6tS1pvVuLbOlgijwhF2.lcpg3pA9/MaeDMKGFrFMm.', '2005-01-01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_academic`
--
ALTER TABLE `student_academic`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `student_academic`
--
ALTER TABLE `student_academic`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `student_academic`
--
ALTER TABLE `student_academic`
  ADD CONSTRAINT `student_academic_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
