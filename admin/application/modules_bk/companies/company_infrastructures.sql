-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 27, 2017 at 09:17 AM
-- Server version: 10.1.16-MariaDB
-- PHP Version: 7.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `primatvq_finance`
--

-- --------------------------------------------------------

--
-- Table structure for table `company_infrastructures`
--

CREATE TABLE `company_infrastructures` (
  `id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `comment` longtext NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `company_infrastructures`
--

INSERT INTO `company_infrastructures` (`id`, `company_id`, `comment`, `is_active`, `created`, `modified`) VALUES
(1, 1, '<!DOCTYPE html><html><head></head><body><p>abccffdsrfrnj,hj</p></body></html>', 1, '2017-11-26 00:00:00', '2017-11-26 17:33:18'),
(2, 1, '<!DOCTYPE html><html><head></head><body><p>bbjythjytfhjjuuu</p></body></html>', 1, '2017-11-26 00:00:00', '2017-11-26 17:33:18'),
(3, 0, '', 1, '2017-11-26 15:48:30', '2017-11-26 15:48:30'),
(4, 0, '', 1, '2017-11-26 15:48:49', '2017-11-26 15:48:49'),
(5, 1, '<!DOCTYPE html><html><head></head><body><p>sddff</p></body></html>', 1, '2017-11-26 16:01:46', '2017-11-26 17:33:18'),
(6, 1, '<!DOCTYPE html><html><head></head><body><p>fgrth</p></body></html>', 1, '2017-11-26 16:16:08', '2017-11-26 17:33:18'),
(7, 1, '<!DOCTYPE html><html><head></head><body><p>vnhgf</p></body></html>', 1, '2017-11-26 16:17:22', '2017-11-26 17:33:18'),
(8, 1, '<!DOCTYPE html><html><head></head><body></body></html>', 1, '2017-11-26 16:22:16', '2017-11-26 17:33:18'),
(9, 1, '<!DOCTYPE html><html><head></head><body></body></html>', 1, '2017-11-26 16:53:19', '2017-11-26 17:33:18'),
(10, 1, '<!DOCTYPE html><html><head></head><body></body></html>', 1, '2017-11-26 16:53:19', '2017-11-26 17:33:18'),
(11, 1, '<!DOCTYPE html><html><head></head><body></body></html>', 1, '2017-11-26 16:53:19', '2017-11-26 17:33:18'),
(12, 1, '<!DOCTYPE html><html><head></head><body></body></html>', 1, '2017-11-26 16:53:19', '2017-11-26 17:33:18'),
(13, 1, '<!DOCTYPE html><html><head></head><body></body></html>', 1, '2017-11-26 16:55:19', '2017-11-26 17:33:18'),
(14, 1, '<!DOCTYPE html><html><head></head><body></body></html>', 1, '2017-11-26 16:55:19', '2017-11-26 17:33:18'),
(15, 1, '<!DOCTYPE html><html><head></head><body></body></html>', 1, '2017-11-26 16:55:19', '2017-11-26 17:33:18'),
(16, 1, '<!DOCTYPE html><html><head></head><body></body></html>', 0, '2017-11-26 16:55:19', '2017-11-26 17:33:18'),
(17, 1, '<!DOCTYPE html><html><head></head><body></body></html>', 0, '2017-11-26 16:55:19', '2017-11-26 17:33:18'),
(18, 1, '<!DOCTYPE html><html><head></head><body></body></html>', 0, '2017-11-26 16:55:19', '2017-11-26 17:33:18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `company_infrastructures`
--
ALTER TABLE `company_infrastructures`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `company_infrastructures`
--
ALTER TABLE `company_infrastructures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
