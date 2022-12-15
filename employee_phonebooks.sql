-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 15, 2022 at 04:52 AM
-- Server version: 8.0.31
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `employee_phonebooks`
--

-- --------------------------------------------------------

--
-- Table structure for table `all_employees`
--

CREATE TABLE `all_employees` (
  `id` int NOT NULL,
  `eid` varchar(250) NOT NULL,
  `email` varchar(150) NOT NULL,
  `name` varchar(100) NOT NULL,
  `designation` varchar(50) NOT NULL,
  `department` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `all_employees`
--

INSERT INTO `all_employees` (`id`, `eid`, `email`, `name`, `designation`, `department`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '1569', 's.m.iftakhairul@gmail.com', 'S M Iftakhairul', 'Software Specialist', 'Engineering', '2022-12-15 04:33:28', '2022-12-15 04:33:28', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `all_phone_book`
--

CREATE TABLE `all_phone_book` (
  `id` int NOT NULL,
  `prefix` varchar(4) NOT NULL COMMENT '+40=ro\r\n+39=de',
  `number` varchar(15) NOT NULL COMMENT 'left trimmed all the leading zeroes',
  `name` varchar(80) NOT NULL COMMENT 'associate or client name',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'last update timestamp',
  `deleted` tinyint NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `all_phone_book`
--

INSERT INTO `all_phone_book` (`id`, `prefix`, `number`, `name`, `updated_at`, `deleted`) VALUES
(1, '+880', '1630132436', 'Bangladesh (+880)', '2022-12-15 04:33:28', 0);

-- --------------------------------------------------------

--
-- Table structure for table `all_phone_book_links`
--

CREATE TABLE `all_phone_book_links` (
  `link_id` int NOT NULL COMMENT 'internal index',
  `phone_book_id` int NOT NULL COMMENT 'all_phone_book.id',
  `table_id` int NOT NULL COMMENT 'form_employees.employee_id',
  `table_name` varchar(100) NOT NULL COMMENT 'eg: form_employees'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `all_phone_book_links`
--

INSERT INTO `all_phone_book_links` (`link_id`, `phone_book_id`, `table_id`, `table_name`) VALUES
(1, 1, 1, 'all_employees');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `all_employees`
--
ALTER TABLE `all_employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `eid` (`eid`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `all_phone_book`
--
ALTER TABLE `all_phone_book`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_phone` (`prefix`,`number`);

--
-- Indexes for table `all_phone_book_links`
--
ALTER TABLE `all_phone_book_links`
  ADD PRIMARY KEY (`link_id`),
  ADD UNIQUE KEY `single_refference` (`phone_book_id`,`table_id`,`table_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `all_employees`
--
ALTER TABLE `all_employees`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `all_phone_book`
--
ALTER TABLE `all_phone_book`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `all_phone_book_links`
--
ALTER TABLE `all_phone_book_links`
  MODIFY `link_id` int NOT NULL AUTO_INCREMENT COMMENT 'internal index', AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
