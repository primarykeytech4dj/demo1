-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 30, 2018 at 12:23 PM
-- Server version: 10.1.28-MariaDB
-- PHP Version: 5.6.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `primatvq_abhitakk`
--

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `role_name` varchar(255) NOT NULL,
  `role_code` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role_name`, `role_code`, `is_active`, `created`, `modified`) VALUES
(1, 'Customer', 'Cl', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'Superadmin', 'SA', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'Admin', 'Admin', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'Accountant', 'Accounts', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 'Employee', 'EMP', 1, '2018-03-27 00:00:00', '2018-03-27 00:00:00'),
(6, ' vdnkjvndkj', 'nvf', 1, '2018-03-28 19:38:24', '2018-03-28 19:38:24'),
(7, ' vdnkjvndkj', 'nvf', 1, '2018-03-28 19:39:18', '2018-03-28 19:39:18'),
(8, ' vdnkjvndkj', 'nvf', 1, '2018-03-28 21:18:14', '2018-03-28 21:18:14'),
(9, 'supplier', 'sup', 1, '2018-03-28 21:25:13', '2018-03-28 21:25:13'),
(10, 'supplier', 'sup', 1, '2018-03-29 07:53:44', '2018-03-29 07:53:44'),
(11, 'supplier', 'sup', 1, '2018-03-29 07:55:07', '2018-03-29 07:55:07'),
(12, 'supplier', 'sup', 1, '2018-03-29 07:55:48', '2018-03-29 07:55:48'),
(13, 'supplier', 'sup', 1, '2018-03-29 07:56:25', '2018-03-29 07:56:25'),
(14, 'supplier', 'sup', 1, '2018-03-29 07:56:53', '2018-03-29 07:56:53'),
(15, 'supplier', 'sup', 1, '2018-03-29 08:20:29', '2018-03-29 08:20:29'),
(16, 'supplier', 'sup', 1, '2018-03-29 08:21:53', '2018-03-29 08:21:53'),
(17, 'supplier', 'sup', 1, '2018-03-29 08:30:59', '2018-03-29 08:30:59'),
(18, 'supplier', 'sup', 1, '2018-03-29 08:34:10', '2018-03-29 08:34:10'),
(19, 'supplier', 'sup', 1, '2018-03-29 08:35:11', '2018-03-29 08:35:11'),
(20, 'supplier', 'sup', 1, '2018-03-29 08:35:38', '2018-03-29 08:35:38'),
(21, 'supplier', 'sup', 1, '2018-03-29 08:35:48', '2018-03-29 08:35:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
