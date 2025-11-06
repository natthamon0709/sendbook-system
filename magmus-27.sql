-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 09, 2023 at 08:32 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `magmus-27`
--

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

CREATE TABLE `history` (
  `id` int(11) NOT NULL COMMENT 'ไอดีประวัติ',
  `officer_id` int(11) NOT NULL COMMENT 'ไอดีเจ้าหน้าที',
  `prisoner_id` int(11) NOT NULL COMMENT 'ไอดีเล่มทะเบียนนักโทษ',
  `type` int(11) NOT NULL COMMENT 'ประเภท: 1=ยืม, 2=คืน, 3=นำไปที่อื่นเช่นศาล, 4=หาย, 5=ค้นพบ',
  `location` varchar(255) NOT NULL COMMENT 'สถานที่',
  `note` varchar(255) NOT NULL COMMENT 'หมายเหตุ',
  `is_active` varchar(255) NOT NULL DEFAULT 'true' COMMENT 'สถานะประวัติ',
  `date` date NOT NULL COMMENT 'วันที่',
  `created_date` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'วันที่สร้างประวัติ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `officer`
--

CREATE TABLE `officer` (
  `id` int(11) NOT NULL COMMENT 'ไอดีเจ้าหน้าที่',
  `firstname` varchar(255) NOT NULL COMMENT 'ชื่อจริง',
  `lastname` varchar(255) NOT NULL COMMENT 'นามสกุล',
  `is_deleted` varchar(255) NOT NULL DEFAULT 'false' COMMENT 'บัญชีผู้ใช้งานนี้โดนลบไปแล้วหรือไม่',
  `created_date` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'วันที่สร้างเจ้าหน้าที่'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prisoner`
--

CREATE TABLE `prisoner` (
  `id` int(11) NOT NULL COMMENT 'รหัสเล่มทะเบียนนักโทษ',
  `firstname` varchar(255) NOT NULL COMMENT 'ชื่อจริง',
  `lastname` varchar(255) NOT NULL COMMENT 'นามสกุล',
  `code` varchar(10) NOT NULL COMMENT 'รหัสผู้ต้องขัง',
  `id_card` varchar(13) NOT NULL DEFAULT '' COMMENT 'รหัสบัตรประชาชน',
  `status` int(11) NOT NULL DEFAULT 1 COMMENT 'สถานะเล่มทะเบียน: 1=ยังอยู่, 2=ถูกยืม, 3=ถูกนำไปข้างนอก, 4=หาย',
  `is_deleted` varchar(255) NOT NULL DEFAULT 'false' COMMENT 'ผู้ใช้คนนี้ถูกลบไปแล้วหรือไม่',
  `created_date` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'วันที่สร้างเล่มทะเบียน'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `history`
--
ALTER TABLE `history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `officer`
--
ALTER TABLE `officer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prisoner`
--
ALTER TABLE `prisoner`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `history`
--
ALTER TABLE `history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ไอดีประวัติ';

--
-- AUTO_INCREMENT for table `officer`
--
ALTER TABLE `officer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ไอดีเจ้าหน้าที่';

--
-- AUTO_INCREMENT for table `prisoner`
--
ALTER TABLE `prisoner`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสเล่มทะเบียนนักโทษ';
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
