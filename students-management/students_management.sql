-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 14, 2026 at 05:55 PM
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
-- Database: `students_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `scores`
--

CREATE TABLE `scores` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `attendance_score` decimal(4,1) NOT NULL DEFAULT 0.0,
  `midterm_score` decimal(4,1) NOT NULL DEFAULT 0.0,
  `final_score` decimal(4,1) NOT NULL DEFAULT 0.0,
  `scores` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `scores`
--

INSERT INTO `scores` (`id`, `student_id`, `subject_id`, `attendance_score`, `midterm_score`, `final_score`, `scores`) VALUES
(8, 1, 2, 0.0, 0.0, 8.0, 4.80),
(9, 4, 2, 0.0, 0.0, 7.0, 4.20),
(10, 10, 2, 5.0, 4.0, 3.0, 3.50),
(11, 2, 2, 0.0, 0.0, 8.5, 5.10),
(12, 19, 2, 9.0, 9.0, 9.0, 9.00),
(14, 1, 3, 8.0, 5.0, 7.0, 6.50),
(15, 4, 3, 9.0, 9.0, 9.0, 9.00),
(16, 10, 3, 6.0, 3.0, 7.0, 5.70),
(19, 27, 2, 0.0, 0.0, 0.0, 0.00),
(20, 28, 2, 0.0, 0.0, 0.0, 0.00),
(31, 66, 3, 0.0, 0.0, 0.0, 0.00),
(32, 67, 3, 0.0, 0.0, 0.0, 0.00),
(33, 68, 3, 0.0, 0.0, 0.0, 0.00),
(34, 69, 3, 0.0, 0.0, 0.0, 0.00),
(35, 70, 3, 0.0, 0.0, 0.0, 0.00),
(37, 66, 2, 9.0, 8.5, 9.2, 8.97),
(38, 67, 2, 6.0, 6.5, 7.0, 6.75),
(39, 68, 2, 5.5, 5.0, 6.0, 5.65),
(40, 69, 2, 8.0, 7.5, 8.5, 8.15),
(41, 70, 2, 4.5, 5.0, 5.5, 5.25),
(42, 71, 2, 9.0, 8.0, 8.0, 8.10),
(43, 72, 2, 3.5, 4.0, 5.0, 4.55),
(44, 73, 2, 9.5, 9.0, 9.8, 9.53),
(45, 74, 2, 6.8, 6.0, 7.2, 6.80),
(46, 75, 2, 5.0, 5.5, 6.0, 5.75),
(47, 76, 2, 7.2, 7.8, 8.0, 7.86),
(48, 77, 2, 8.5, 8.0, 9.0, 8.65),
(49, 78, 2, 6.0, 6.2, 6.8, 6.54),
(50, 79, 2, 7.8, 7.5, 8.2, 7.95),
(51, 80, 2, 4.0, 4.5, 5.5, 5.05),
(52, 81, 2, 9.2, 8.8, 9.5, 9.26),
(53, 82, 2, 6.5, 6.0, 6.5, 6.35),
(54, 83, 2, 5.8, 5.5, 6.0, 5.83),
(55, 84, 2, 7.0, 7.2, 7.8, 7.54),
(56, 85, 2, 8.8, 8.5, 9.0, 8.83),
(57, 86, 2, 3.0, 4.0, 4.5, 4.20),
(58, 87, 2, 6.2, 6.5, 7.0, 6.77),
(59, 88, 2, 7.5, 7.8, 8.5, 8.19),
(60, 89, 2, 5.0, 5.5, 6.2, 5.87),
(61, 90, 2, 9.0, 9.2, 9.5, 9.36),
(62, 91, 2, 6.5, 6.8, 7.2, 7.01),
(63, 92, 2, 4.5, 5.0, 5.8, 5.43),
(64, 93, 2, 8.0, 8.2, 8.8, 8.54),
(65, 94, 2, 7.0, 6.5, 7.5, 7.15),
(66, 95, 2, 5.5, 5.0, 6.5, 5.95),
(67, 96, 2, 9.5, 9.0, 9.7, 9.47),
(68, 97, 2, 6.0, 6.2, 6.8, 6.54),
(69, 98, 2, 7.8, 7.5, 8.0, 7.83),
(70, 99, 2, 4.0, 4.5, 5.0, 4.75),
(71, 27, 3, 9.0, 8.0, 8.5, 8.40),
(72, 28, 3, 0.0, 0.0, 0.0, 0.00),
(73, 30, 3, 0.0, 0.0, 0.0, 0.00),
(74, 31, 3, 0.0, 0.0, 0.0, 0.00),
(75, 32, 3, 0.0, 0.0, 0.0, 0.00),
(76, 33, 3, 0.0, 0.0, 0.0, 0.00),
(77, 34, 3, 0.0, 0.0, 0.0, 0.00),
(78, 35, 3, 0.0, 0.0, 0.0, 0.00),
(79, 41, 2, 0.0, 0.0, 0.0, 0.00),
(80, 42, 2, 0.0, 0.0, 0.0, 0.00),
(81, 43, 2, 10.0, 9.0, 9.0, 9.10),
(82, 44, 2, 10.0, 9.0, 9.0, 9.10),
(83, 45, 2, 0.0, 0.0, 0.0, 0.00),
(84, 46, 2, 0.0, 0.0, 0.0, 0.00),
(85, 47, 2, 0.0, 0.0, 0.0, 0.00),
(86, 48, 2, 0.0, 0.0, 0.0, 0.00),
(87, 1, 6, 9.5, 9.0, 9.8, 9.53),
(88, 4, 6, 9.0, 8.8, 9.2, 9.06),
(89, 27, 6, 8.7, 8.5, 8.9, 8.76),
(90, 28, 6, 8.2, 8.0, 8.4, 8.26),
(91, 30, 6, 7.8, 7.5, 8.0, 7.83),
(92, 31, 6, 7.4, 7.2, 7.6, 7.46),
(93, 32, 6, 7.0, 6.8, 7.3, 7.12),
(94, 33, 6, 6.8, 6.5, 7.0, 6.83),
(95, 34, 6, 6.5, 6.2, 6.8, 6.59),
(96, 35, 6, 6.2, 6.0, 6.5, 6.32),
(97, 36, 6, 5.8, 5.5, 6.0, 5.83),
(98, 37, 6, 5.5, 5.2, 5.8, 5.59),
(99, 38, 6, 5.2, 5.0, 5.5, 5.32),
(100, 39, 6, 4.8, 4.5, 5.0, 4.83),
(101, 40, 6, 4.5, 4.2, 4.8, 4.59),
(102, 100, 6, 3.8, 4.0, 4.2, 4.10),
(103, 102, 6, 2.5, 3.0, 3.5, 3.25),
(104, 10, 6, 9.2, 9.0, 9.5, 9.32);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `mssv` varchar(20) NOT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `gender` enum('Nam','Nữ','Khác') DEFAULT 'Khác',
  `class` varchar(50) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `email` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `mssv`, `fullname`, `gender`, `class`, `phone`, `address`, `birthday`, `email`) VALUES
(1, '20230001', 'Nguyễn Văn An', 'Nam', 'D16CNTT', '0911111111', 'Hà Nội\r\n', '2005-01-15', 'an20230001@gmail.com'),
(2, '20240002', 'Trần Thị Bích', 'Khác', 'D17CNTT', '0922222222', 'Hải Phòng', '2006-03-20', 'bich20240002@gmail.com'),
(4, '20230003', 'Nguyễn Văn Đông', 'Nam', 'D16CNTT', '0918180090', 'Hoa Lư- Ninh Bình', '2005-03-26', 'nvdong@gmail.com'),
(10, '20240001', 'Liễu Như Yên', 'Khác', 'D17CNTT', '0915555555', 'TP-HCM', '2006-12-02', 'yen@gmail.com'),
(19, '20240003', 'Nguyễn Nhật hạ', 'Khác', 'D17CNTT', '0918444444', 'Ninh Hải - Hoa Lư - Ninh Bình', '2006-06-03', 'nguyenha@gmail.com'),
(27, '20230004', 'Nguyễn Văn A4', 'Khác', 'D16CNTT', '0910000004', 'D16CNTT', '2005-04-04', 'a4@gmail.com'),
(28, '20230005', 'Nguyễn Văn A5', 'Nữ', 'D16CNTT', '0910000005', 'D16CNTT', '2005-05-05', 'a5@gmail.com'),
(30, '20230007', 'Nguyễn Văn A7', 'Khác', 'D16CNTT', '0910000007', 'D16CNTT', '2005-07-07', 'a7@gmail.com'),
(31, '20230008', 'Nguyễn Văn A8', 'Khác', 'D16CNTT', '0910000008', 'D16CNTT', '2005-08-08', 'a8@gmail.com'),
(32, '20230009', 'Nguyễn Văn A9', 'Khác', 'D16CNTT', '0910000009', 'D16CNTT', '2005-09-09', 'a9@gmail.com'),
(33, '20230010', 'Nguyễn Văn A10', 'Khác', 'D16CNTT', '0910000010', 'D16CNTT', '2005-10-10', 'a10@gmail.com'),
(34, '20230011', 'Nguyễn Văn A11', 'Nam', 'D16CNTT', '0910000011', 'D16CNTT', '2005-11-11', 'a11@gmail.com'),
(35, '20230012', 'Nguyễn Văn A12', 'Khác', 'D16CNTT', '0910000012', 'D16CNTT', '2005-12-12', 'a12@gmail.com'),
(36, '20230013', 'Nguyễn Văn A13', 'Khác', 'D16CNTT', '0910000013', 'D16CNTT', '2006-01-13', 'a13@gmail.com'),
(37, '20230014', 'Nguyễn Văn A14', 'Khác', 'D16CNTT', '0910000014', 'D16CNTT', '2006-02-14', 'a14@gmail.com'),
(38, '20230015', 'Nguyễn Văn A15', 'Khác', 'D16CNTT', '0910000015', 'D16CNTT', '2006-03-15', 'a15@gmail.com'),
(39, '20230016', 'Nguyễn Văn A16', 'Khác', 'D16CNTT', '0910000016', 'D16CNTT', '2006-04-16', 'a16@gmail.com'),
(40, '20230017', 'Nguyễn Văn A17', 'Khác', 'D16CNTT', '0910000017', 'D16CNTT', '2006-05-17', 'a17@gmail.com'),
(41, '20240006', 'Trần Văn B6', 'Khác', 'D17CNTT', '0920000006', 'D17CNTT', '2005-06-06', 'b6@gmail.com'),
(42, '20240007', 'Trần Văn B7', 'Khác', 'D17CNTT', '0920000007', 'D17CNTT', '2005-07-07', 'b7@gmail.com'),
(43, '20240008', 'Trần Văn B8', 'Khác', 'D17CNTT', '0920000008', 'D17CNTT', '2005-08-08', 'b8@gmail.com'),
(44, '20240009', 'Trần Văn B9', 'Khác', 'D17CNTT', '0920000009', 'D17CNTT', '2005-09-09', 'b9@gmail.com'),
(45, '20240010', 'Trần Văn B10', 'Khác', 'D17CNTT', '0920000010', 'D17CNTT', '2005-10-10', 'b10@gmail.com'),
(46, '20240011', 'Trần Văn B11', 'Khác', 'D17CNTT', '0920000011', 'D17CNTT', '2005-11-11', 'b11@gmail.com'),
(47, '20240012', 'Trần Văn B12', 'Khác', 'D17CNTT', '0920000012', 'D17CNTT', '2005-12-12', 'b12@gmail.com'),
(48, '20240013', 'Trần Văn B13', 'Khác', 'D17CNTT', '0920000013', 'D17CNTT', '2006-01-13', 'b13@gmail.com'),
(49, '20240014', 'Trần Văn B14', 'Khác', 'D17CNTT', '0920000014', 'D17CNTT', '2006-02-14', 'b14@gmail.com'),
(50, '20240015', 'Trần Văn B15', 'Khác', 'D17CNTT', '0920000015', 'D17CNTT', '2006-03-15', 'b15@gmail.com'),
(51, '20240016', 'Trần Văn B16', 'Khác', 'D17CNTT', '0920000016', 'D17CNTT', '2006-04-16', 'b16@gmail.com'),
(52, '20240017', 'Trần Văn B17', 'Khác', 'D17CNTT', '0920000017', 'D17CNTT', '2006-05-17', 'b17@gmail.com'),
(53, '20240018', 'Trần Văn B18', 'Khác', 'D17CNTT', '0920000018', 'D17CNTT', '2006-06-18', 'b18@gmail.com'),
(54, '20240019', 'Trần Văn B19', 'Khác', 'D17CNTT', '0920000019', 'D17CNTT', '2006-07-19', 'b19@gmail.com'),
(55, '20240020', 'Trần Văn B20', 'Khác', 'D17CNTT', '0920000020', 'D17CNTT', '2006-08-20', 'b20@gmail.com'),
(56, '20240021', 'Trần Văn B21', 'Khác', 'D17CNTT', '0920000021', 'D17CNTT', '2006-09-21', 'b21@gmail.com'),
(57, '20240022', 'Trần Văn B22', 'Khác', 'D17CNTT', '0920000022', 'D17CNTT', '2006-10-22', 'b22@gmail.com'),
(58, '20240023', 'Trần Văn B23', 'Khác', 'D17CNTT', '0920000023', 'D17CNTT', '2006-11-23', 'b23@gmail.com'),
(59, '20240024', 'Trần Văn B24', 'Khác', 'D17CNTT', '0920000024', 'D17CNTT', '2006-12-24', 'b24@gmail.com'),
(60, '20240025', 'Trần Văn B25', 'Khác', 'D17CNTT', '0920000025', 'D17CNTT', '2005-01-25', 'b25@gmail.com'),
(61, '20240026', 'Trần Văn B26', 'Khác', 'D17CNTT', '0920000026', 'D17CNTT', '2005-02-26', 'b26@gmail.com'),
(62, '20240027', 'Trần Văn B27', 'Khác', 'D17CNTT', '0920000027', 'D17CNTT', '2005-03-27', 'b27@gmail.com'),
(63, '20240028', 'Trần Văn B28', 'Khác', 'D17CNTT', '0920000028', 'D17CNTT', '2005-04-28', 'b28@gmail.com'),
(64, '20240029', 'Trần Văn B29', 'Khác', 'D17CNTT', '0920000029', 'D17CNTT', '2005-05-29', 'b29@gmail.com'),
(65, '20240030', 'Trần Văn B30', 'Khác', 'D17CNTT', '0920000030', 'D17CNTT', '2005-06-30', 'b30@gmail.com'),
(66, '20250001', 'Lê Văn C1', 'Khác', 'D18CNTT', '0930000001', 'D18CNTT', '2005-01-01', 'c1@gmail.com'),
(67, '20250002', 'Lê Văn C2', 'Khác', 'D18CNTT', '0930000002', 'D18CNTT', '2005-02-02', 'c2@gmail.com'),
(68, '20250003', 'Lê Văn C3', 'Khác', 'D18CNTT', '0930000003', 'D18CNTT', '2005-03-03', 'c3@gmail.com'),
(69, '20250004', 'Lê Văn C4', 'Khác', 'D18CNTT', '0930000004', 'D18CNTT', '2005-04-04', 'c4@gmail.com'),
(70, '20250005', 'Lê Văn C5', 'Khác', 'D18CNTT', '0930000005', 'D18CNTT', '2005-05-05', 'c5@gmail.com'),
(71, '20250006', 'Lê Văn C6', 'Khác', 'D18CNTT', '0930000006', 'D18CNTT', '2005-06-06', 'c6@gmail.com'),
(72, '20250007', 'Lê Văn C7', 'Khác', 'D18CNTT', '0930000007', 'D18CNTT', '2005-07-07', 'c7@gmail.com'),
(73, '20250008', 'Lê Văn C8', 'Khác', 'D18CNTT', '0930000008', 'D18CNTT', '2005-08-08', 'c8@gmail.com'),
(74, '20250009', 'Lê Văn C9', 'Khác', 'D18CNTT', '0930000009', 'D18CNTT', '2005-09-09', 'c9@gmail.com'),
(75, '20250010', 'Lê Văn C10', 'Khác', 'D18CNTT', '0930000010', 'D18CNTT', '2005-10-10', 'c10@gmail.com'),
(76, '20250011', 'Lê Văn C11', 'Khác', 'D18CNTT', '0930000011', 'D18CNTT', '2005-11-11', 'c11@gmail.com'),
(77, '20250012', 'Lê Văn C12', 'Khác', 'D18CNTT', '0930000012', 'D18CNTT', '2005-12-12', 'c12@gmail.com'),
(78, '20250013', 'Lê Văn C13', 'Khác', 'D18CNTT', '0930000013', 'D18CNTT', '2006-01-13', 'c13@gmail.com'),
(79, '20250014', 'Lê Văn C14', 'Khác', 'D18CNTT', '0930000014', 'D18CNTT', '2006-02-14', 'c14@gmail.com'),
(80, '20250015', 'Lê Văn C15', 'Khác', 'D18CNTT', '0930000015', 'D18CNTT', '2006-03-15', 'c15@gmail.com'),
(81, '20250016', 'Lê Văn C16', 'Khác', 'D18CNTT', '0930000016', 'D18CNTT', '2006-04-16', 'c16@gmail.com'),
(82, '20250017', 'Lê Văn C17', 'Khác', 'D18CNTT', '0930000017', 'D18CNTT', '2006-05-17', 'c17@gmail.com'),
(83, '20250018', 'Lê Văn C18', 'Khác', 'D18CNTT', '0930000018', 'D18CNTT', '2006-06-18', 'c18@gmail.com'),
(84, '20250019', 'Lê Văn C19', 'Khác', 'D18CNTT', '0930000019', 'D18CNTT', '2006-07-19', 'c19@gmail.com'),
(85, '20250020', 'Lê Văn C20', 'Khác', 'D18CNTT', '0930000020', 'D18CNTT', '2006-08-20', 'c20@gmail.com'),
(86, '20250021', 'Lê Văn C21', 'Khác', 'D18CNTT', '0930000021', 'D18CNTT', '2006-09-21', 'c21@gmail.com'),
(87, '20250022', 'Lê Văn C22', 'Khác', 'D18CNTT', '0930000022', 'D18CNTT', '2006-10-22', 'c22@gmail.com'),
(88, '20250023', 'Lê Văn C23', 'Khác', 'D18CNTT', '0930000023', 'D18CNTT', '2006-11-23', 'c23@gmail.com'),
(89, '20250024', 'Lê Văn C24', 'Khác', 'D18CNTT', '0930000024', 'D18CNTT', '2006-12-24', 'c24@gmail.com'),
(90, '20250025', 'Lê Văn C25', 'Khác', 'D18CNTT', '0930000025', 'D18CNTT', '2005-01-25', 'c25@gmail.com'),
(91, '20250026', 'Lê Văn C26', 'Khác', 'D18CNTT', '0930000026', 'D18CNTT', '2005-02-26', 'c26@gmail.com'),
(92, '20250027', 'Lê Văn C27', 'Khác', 'D18CNTT', '0930000027', 'D18CNTT', '2005-03-27', 'c27@gmail.com'),
(93, '20250028', 'Lê Văn C28', 'Khác', 'D18CNTT', '0930000028', 'D18CNTT', '2005-04-28', 'c28@gmail.com'),
(94, '20250029', 'Lê Văn C29', 'Khác', 'D18CNTT', '0930000029', 'D18CNTT', '2005-05-29', 'c29@gmail.com'),
(95, '20250030', 'Lê Văn C30', 'Khác', 'D18CNTT', '0930000030', 'D18CNTT', '2005-06-30', 'c30@gmail.com'),
(96, '20250031', 'Lê Văn C31', 'Khác', 'D18CNTT', '0930000031', 'D18CNTT', '2005-07-01', 'c31@gmail.com'),
(97, '20250032', 'Lê Văn C32', 'Khác', 'D18CNTT', '0930000032', 'D18CNTT', '2005-08-02', 'c32@gmail.com'),
(98, '20250033', 'Lê Văn C33', 'Khác', 'D18CNTT', '0930000033', 'D18CNTT', '2005-09-03', 'c33@gmail.com'),
(99, '20250034', 'Lê Văn C34', 'Khác', 'D18CNTT', '0930000034', 'D18CNTT', '2005-10-04', 'c34@gmail.com'),
(100, '20230022', 'Nguyễn Văn F', 'Khác', 'D16CNTT', '0918888888', 'Cà mau', '2007-07-26', 'nguyenvanf@gmail.com'),
(101, '20240036', 'Nguyễn Văn G', 'Khác', 'D17CNTT', '0708477777', 'Hà Giang', '2006-04-14', 'nguyenvang@gmail.com'),
(102, '20230030', 'Nguyễn Văn G', 'Nam', 'D16CNTT', '0708478337', 'Làng Bụi Đá', '2005-07-08', 'Gnguyen@gmail.com'),
(104, '20240043', 'Nguyễn Tấn Phát', 'Nam', 'D17CNTT', '1900190000', 'Ninh Bình', '2006-07-12', 'Tanphat@gmail.com'),
(105, '20230002', 'Nguyen Van K', 'Nam', 'D16CNTT', '0911111111', 'Ha Noi', '2005-04-15', 'K20230001@gmail.com'),
(109, '20230025', 'Đỗ Mai Linh', 'Nam', 'D16CNTT', '0911118888', 'Hà Nội', '2005-04-24', 'Linh@gmail.com'),
(110, '20230026', 'Ô Mai Chuối', 'Nam', 'D16CNTT', '0708004567', 'Làng Trắng Phổi', '2005-04-08', 'chuoi@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `id` int(11) NOT NULL,
  `subject_code` varchar(20) DEFAULT NULL,
  `subject_name` varchar(100) NOT NULL,
  `credits` int(11) NOT NULL DEFAULT 3,
  `description` text DEFAULT NULL,
  `attendance_weight` int(11) NOT NULL DEFAULT 10,
  `midterm_weight` int(11) NOT NULL DEFAULT 30,
  `final_weight` int(11) NOT NULL DEFAULT 60
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`id`, `subject_code`, `subject_name`, `credits`, `description`, `attendance_weight`, `midterm_weight`, `final_weight`) VALUES
(2, 'IT2202', 'Cơ sở dữ liệu', 3, 'Môn học cơ sở dữ liệu và truy vấn SQL.', 10, 30, 60),
(3, 'IT4420', 'Lập Trình Web', 2, 'Môn học lập trình web cơ bản.', 10, 30, 60),
(5, 'IT3001', 'Hệ quản trị CSDL', 4, 'db', 10, 30, 60),
(6, 'IT1101', 'Lập trình C', 3, 'Lập trình C', 10, 30, 60);

-- --------------------------------------------------------

--
-- Table structure for table `teacher`
--

CREATE TABLE `teacher` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `teacher`
--

INSERT INTO `teacher` (`id`, `name`, `email`, `password`, `phone`) VALUES
(1, 'UnKnow', 'admin@hluv.edu.com.vn', 'Abcxyz@123', '0901111111'),
(2, 'Tran Thi Lan', 'gv1@hluv.edu.com.vn', '12345678', '0902222222'),
(3, 'Phạm Bảo Khoa', 'khoartk135@hluv.edu.com.vn', 'Khoa422005@@', '0912740611');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `scores`
--
ALTER TABLE `scores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_scores_student` (`student_id`),
  ADD KEY `fk_scores_subject` (`subject_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mssv` (`mssv`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subject_code` (`subject_code`);

--
-- Indexes for table `teacher`
--
ALTER TABLE `teacher`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `scores`
--
ALTER TABLE `scores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT for table `subject`
--
ALTER TABLE `subject`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `teacher`
--
ALTER TABLE `teacher`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `scores`
--
ALTER TABLE `scores`
  ADD CONSTRAINT `fk_scores_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_scores_subject` FOREIGN KEY (`subject_id`) REFERENCES `subject` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
