-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 11, 2018 at 10:47 AM
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
-- Table structure for table `company_testimonials`
--

CREATE TABLE `company_testimonials` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `company` varchar(255) NOT NULL,
  `designation` varchar(255) NOT NULL,
  `comment` longtext NOT NULL,
  `image` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `company_testimonials`
--

INSERT INTO `company_testimonials` (`id`, `name`, `company`, `designation`, `comment`, `image`, `is_active`, `created`, `modified`) VALUES
(1, 'deepak', 'pkt', 'developer', 'test', 'product1.jpg', 1, '0000-00-00 00:00:00', '2018-01-11 10:02:20'),
(3, 'test', 'test', 'test', 'tesst', 'Desert.jpg', 1, '2018-01-07 08:54:47', '2018-01-11 10:02:20'),
(24, 'exampleeeeeeeeeeeeeee', 'example', 'test', 'tesst', 'Desert.jpg', 1, '2018-01-11 09:37:20', '2018-01-11 10:02:20'),
(25, '', 'sdfst', 'grt', 'grey6e', 'Koala.jpg', 1, '2018-01-11 09:52:44', '2018-01-11 10:02:20'),
(26, '', '', 'fdgdrtd', 'grtey', 'Hydrangeas.jpg', 1, '2018-01-11 09:52:44', '2018-01-11 10:02:20'),
(27, 'fgdrt', 'rtw5t', '', '', '', 1, '2018-01-11 09:52:44', '2018-01-11 10:02:20'),
(28, 'vghf', ' ghhjdrt', '', '', '', 1, '2018-01-11 10:02:20', '2018-01-11 10:02:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `company_testimonials`
--
ALTER TABLE `company_testimonials`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `company_testimonials`
--
ALTER TABLE `company_testimonials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
