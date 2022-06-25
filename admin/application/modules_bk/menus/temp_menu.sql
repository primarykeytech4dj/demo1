-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 30, 2018 at 02:23 PM
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
-- Table structure for table `temp_menu`
--

CREATE TABLE `temp_menu` (
  `id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `target` enum('_self','_blank','_new','') NOT NULL,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `slug` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `class` varchar(100) NOT NULL,
  `is_active` int(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `temp_menu`
--

INSERT INTO `temp_menu` (`id`, `menu_id`, `parent_id`, `target`, `name`, `slug`, `class`, `is_active`, `created`, `modified`) VALUES
(1, 1, 0, '_self', 'News', '', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 1, 0, '_self', 'Gallery', 'bhjj', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 1, 0, '_self', 'Blog', '', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 1, 1, '_self', 'आपका शहर', 'आपका-शहर', 'cat-red', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 1, 1, '_self', 'अन्य राज्य', 'अन्य-राज्य', 'cat-blue', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 1, 1, '_self', 'देश विदेश', 'देश-विदेश', 'cat-violet', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 1, 1, '_self', 'व्यापार', 'व्यापार', 'cat-cyan', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(8, 1, 1, '_self', 'खेल', 'खेल', 'cat-orange', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(9, 1, 1, '_self', 'मनोरंजन', 'मनोरंजन', 'cat-violet', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(43, 1, 4, '_self', 'सिरसा', 'news/category/सिरसा', '', 1, '2018-03-19 12:15:12', '2018-03-19 12:15:12'),
(11, 1, 4, '_self', 'भिवानी', 'news/category/भिवानी', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(12, 1, 4, '_self', 'रोहतक', 'news/category/रोहतक', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(13, 1, 4, '_self', 'हिसार', 'news/category/हिसार', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(14, 1, 4, '_self', 'रेवाड़ी ', 'news/category/रेवाड़ी', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(15, 1, 5, '_self', 'राजस्थान', 'news/states/राजस्थान', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(16, 1, 5, '_self', 'पंजाब', 'news/states/पंजाब', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(17, 1, 5, '_self', 'हिमाचल प्रदेश', 'news/states/हिमाचल-प्रदेश', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(18, 1, 5, '_self', 'दिल्ली', 'news/states/दिल्ली', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(19, 1, 8, '_self', 'फुटबॉल', 'news/category/फुटबॉल', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(20, 1, 8, '_self', 'क्रिकेट', 'news/category/क्रिकेट', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(21, 1, 8, '_self', 'टेनिस', 'news/category/टेनिस', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(22, 1, 8, '_self', 'बॉक्स', 'news/category/बॉक्स', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(23, 1, 8, '_self', 'हॉर्स', 'news/category/हॉर्स', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(24, 1, 8, '_self', 'रेसिंग', 'news/category/रेसिंग', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(39, 1, 1, '_self', 'टेक्नोलॉजी', 'टेक्नोलॉजी', 'cat-cyan', 1, '2018-03-15 05:44:46', '2018-03-15 05:44:46'),
(40, 1, 1, '_self', 'क्राइम', 'क्राइम', 'cat-red', 1, '2018-03-15 07:25:40', '2018-03-15 07:25:40'),
(41, 1, 1, '_self', 'हैल्थ', 'हैल्थ', 'cat-green', 1, '2018-03-15 07:27:16', '2018-03-15 07:27:16'),
(42, 1, 1, '_self', 'संस्कृति', 'धर्म-संस्कृति', '', 1, '2018-03-15 07:28:26', '2018-03-15 07:28:26'),
(44, 0, 2, '_self', 'vhvjh', ' buyhg', '', 1, '2018-03-30 09:12:04', '2018-03-30 09:12:04'),
(45, 0, 2, '_self', 'nbujhgj', 'nbyujhu', 'cat-blue', 1, '2018-03-30 09:42:45', '2018-03-30 09:42:45'),
(46, 0, 2, '_self', 'nbujhgj', 'nbyujhu', 'cat-blue', 1, '2018-03-30 09:43:24', '2018-03-30 09:43:24'),
(47, 0, 0, '_self', 'crime', 'bhgyu', 'cat-red', 1, '2018-03-30 10:16:27', '2018-03-30 10:16:27'),
(48, 0, 0, '_self', 'crime', 'bhgyu', 'cat-red', 1, '2018-03-30 10:17:44', '2018-03-30 10:17:44'),
(49, 0, 0, '_self', 'crime', 'bhgyu', 'cat-red', 1, '2018-03-30 10:17:53', '2018-03-30 10:17:53'),
(50, 0, 0, '_self', 'crime', 'bhgyu', 'cat-red', 1, '2018-03-30 10:21:21', '2018-03-30 10:21:21'),
(51, 0, 0, '_self', 'crime', 'bhgyu', 'cat-red', 1, '2018-03-30 10:21:36', '2018-03-30 10:21:36'),
(52, 0, 2, '_self', 'jbjbkj', ' njk', 'cat-blue', 1, '2018-03-30 10:23:03', '2018-03-30 10:23:03'),
(53, 0, 0, '_self', 'bjhhj', 'fgdr', '', 1, '2018-03-30 10:31:38', '2018-03-30 10:31:38'),
(54, 0, 0, '_self', 'bjhhj', 'fgdr', '', 1, '2018-03-30 10:32:47', '2018-03-30 10:32:47'),
(55, 0, 0, '_self', 'bjhhj', 'fgdr', '', 1, '2018-03-30 11:07:36', '2018-03-30 11:07:36'),
(56, 0, 0, '_self', 'bjhhj', 'fgdr', '', 1, '2018-03-30 11:07:49', '2018-03-30 11:07:49'),
(57, 0, 0, '_self', 'bjhhj', 'fgdr', '', 1, '2018-03-30 11:08:00', '2018-03-30 11:08:00'),
(58, 0, 0, '_self', 'bjhhj', 'fgdr', '', 1, '2018-03-30 11:19:33', '2018-03-30 11:19:33'),
(59, 0, 0, '_self', 'bjhhj', 'fgdr', '', 1, '2018-03-30 11:19:51', '2018-03-30 11:19:51'),
(60, 2, 0, '_self', ' vdfnbj', 'fgg', 'cat-blue', 1, '2018-03-30 14:11:31', '2018-03-30 14:11:31');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `temp_menu`
--
ALTER TABLE `temp_menu`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `temp_menu`
--
ALTER TABLE `temp_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
