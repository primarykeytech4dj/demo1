-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 10, 2017 at 04:25 PM
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
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `dob` date NOT NULL,
  `contact_1` varchar(15) NOT NULL,
  `contact_2` varchar(15) NOT NULL,
  `blood_group` varchar(10) DEFAULT NULL,
  `primary_email` varchar(255) NOT NULL,
  `secondary_email` varchar(255) NOT NULL,
  `profile_img` varchar(255) NOT NULL DEFAULT 'defaultm.jpg',
  `emp_code` varchar(255) DEFAULT NULL,
  `start_date` date NOT NULL,
  `pan_no` varchar(255) NOT NULL,
  `adhaar_no` varchar(255) NOT NULL,
  `licence_no` varchar(255) NOT NULL,
  `expiry_date` datetime NOT NULL,
  `licence_state` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL DEFAULT '1',
  `allow_login` tinyint(1) NOT NULL DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `first_name`, `middle_name`, `surname`, `dob`, `contact_1`, `contact_2`, `blood_group`, `primary_email`, `secondary_email`, `profile_img`, `emp_code`, `start_date`, `pan_no`, `adhaar_no`, `licence_no`, `expiry_date`, `licence_state`, `role_id`, `allow_login`, `is_active`, `created`, `modified`) VALUES
(1, 'Deepak', 'P.', 'jha', '1999-06-08', '1234567890', '1234567890', 'AB+', 'gfhfjhjhk@fggfg.jhk', 'hgjgk@jhg.jhj', 'logo.jpg', 'Miss00001', '2017-09-28', '', '', '', '0000-00-00 00:00:00', '', 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'abc', '', 'abc', '0000-00-00', '1234567890', '1234567890', '0', 'abc@gmail.com', 'abc@gmail.com', 'defaultm.jpg', 'L0000003', '0000-00-00', '', '', '', '0000-00-00 00:00:00', '', 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'om', '', 'jha', '0000-00-00', '1234567890', '1234567890', '0', 'adf@gfch.hgj', 'hgvjhk@gvh.hgh', 'default4.png', NULL, '0000-00-00', '', '', '', '0000-00-00 00:00:00', '', 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 'khushboo', '', 'Singh', '0000-00-00', '9167632340', '9167632340', '0', 'khush.singh9319@gmail.com', 'khush.singh9319@gmail.com', 'Desert.jpg', NULL, '0000-00-00', '', '', '', '0000-00-00 00:00:00', '', 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 'ashwani', '', 'mishra', '0000-00-00', '1234567890', '1234567890', '0', 'ashwani@gmail.com', 'ashwani@gmail.com', 'Tulips1.jpg', NULL, '0000-00-00', '', '', '', '0000-00-00 00:00:00', '', 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 'khush', '', 'singh', '0000-00-00', '0987654321', '0987654321', '0', 'khush.12@gmail.com', 'khush.12@gmail.com', 'Jellyfish.jpg', NULL, '0000-00-00', '', '', '', '0000-00-00 00:00:00', '', 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(8, 'deepak', '', 'jha', '0000-00-00', '1234567890', '1234567890', '0', 'jha@gmail.com', 'jha@gmail.com', 'Hydrangeas.jpg', NULL, '0000-00-00', '', '', '', '0000-00-00 00:00:00', '', 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(9, 'Deepak', '', 'Jha', '0000-00-00', '8108765848', '8108765848', '1', 'deep@gmail.com', 'deep@gmail.com', 'Hydrangeas7.jpg', NULL, '0000-00-00', '', '', '', '0000-00-00 00:00:00', '', 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10, 'anbc', '', 'anbc', '1970-01-01', '0987654321', '0987654321', '1', 'abcbd@gf.com', 'abcbd@gf.com', 'Penguins14.jpg', NULL, '0000-00-00', '', '', '', '0000-00-00 00:00:00', '', 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(11, 'Deepak', '', 'Jha', '1970-01-01', '81098765848', '81098765848', '1', 'mme.dpj@gmail.com', 'mme.dpj@gmail.com', 'Penguins18.jpg', NULL, '0000-00-00', '', '', '', '0000-00-00 00:00:00', '', 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(13, 'Ash', '', 'Mishra', '2017-12-07', '8108765848', '8108765848', '1', 'mmkk@hgjk.jhgj', 'hgkj@hgfj.hgj', 'Penguins24.jpg', NULL, '0000-00-00', '', '', '', '0000-00-00 00:00:00', '', 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(14, 'Deepak', '', 'Jha', '1970-01-01', '8108765848', '8108765848', '1', 'dep@gmail.com', 'dep@gmail.com', 'Hydrangeas10.jpg', NULL, '0000-00-00', '', '', '', '0000-00-00 00:00:00', '', 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(15, 'ash', '', 'mishra', '1970-01-01', '8108765848', '8108765848', '1', '8108765848@gmail.com', '8108765848@gmail.com', 'Penguins27.jpg', NULL, '0000-00-00', '', '', '', '0000-00-00 00:00:00', '', 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(16, 'deepak', 'M', 'jha', '1970-01-01', '1234567890', '1234567890', '1', '1234567890@gmail.com', '1234567890@gmail.com', 'Penguins29.jpg', NULL, '0000-00-00', '', '', '', '0000-00-00 00:00:00', '', 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(17, 'deepak', 'M', 'jha', '1970-01-01', '1234567890', '1234567890', '1', '12345678902@gmail.com', '12345678902@gmail.com', 'defaultm.jpg', NULL, '0000-00-00', '', '', '', '0000-00-00 00:00:00', '', 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(18, 'rajveer', 's', 'singh', '1970-01-01', '1234567890', '1234567890', '1', 'rajveer@gmail.com', 'rajveer@gmail.com', 'Penguins43.jpg', NULL, '0000-00-00', '', '', '', '0000-00-00 00:00:00', '', 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(19, 'Rajveer', 'S', 'Singh', '1970-01-01', '9167632340', '9167632340', '1', '9167632340@gmail.com', '9167632340@gmail.com', 'Hydrangeas13.jpg', NULL, '0000-00-00', '', '', '', '0000-00-00 00:00:00', '', 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(20, 'khu', 's', 'sing', '2017-06-07', '1234689076', '1234689079', '1', '1234689076@gg.cc', '1234689076@gg.cc', 'Penguins45.jpg', NULL, '0000-00-00', '', '', '', '0000-00-00 00:00:00', '', 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(21, 'Rajveer', 'Sushil', 'Singh', '1991-11-19', '9167632340', '9167632340', '1', '916763234101@gmail.com', '91676323401@gmail.com', 'Hydrangeas23.jpg', NULL, '0000-00-00', '', '', '', '0000-00-00 00:00:00', '', 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(22, 'Rudra', 'S', 'Singh', '1970-01-01', '9167632340', '9167632340', '1', '9167632340ss@gmail.com', '9167632340ss@gmail.com', 'Hydrangeas15.jpg', NULL, '0000-00-00', '', '', '', '0000-00-00 00:00:00', '', 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(23, 'Test', 'test', 'tset', '2016-11-07', '8108765848', '8108765848', '1', '810876@ggg.ghh', '810876@jkj.hk', 'Jellyfish1.jpg', NULL, '0000-00-00', '', '', '', '0000-00-00 00:00:00', '', 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(24, 'Jyoti', 'R', 'Naik', '1970-01-01', '9004185542', '9004185542', '1', '9004185542@gmail.com', '9004185542@gmail.com', 'Penguins46.jpg', NULL, '0000-00-00', '', '', '', '0000-00-00 00:00:00', '', 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(25, 'pkt', 'pkt', 'pkt', '2017-02-04', '9167632340', '9167632340', '1', '9167632340abc@gmail.com', '9167632340abc@gmail.com', 'Jellyfish2.jpg', NULL, '0000-00-00', '', '', '', '0000-00-00 00:00:00', '', 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(26, 'Ashwani', 'M', 'Mishra', '1970-01-01', '9167632340', '9167632340', '1', '9167632340ash@gmail.com', '9167632340ash@gmail.com', 'Chrysanthemum.jpg', NULL, '0000-00-00', '', '', '', '0000-00-00 00:00:00', '', 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(27, 'Yuvraj', 'S', 'Singh', '2016-07-11', '8108817717', '8108817717', '1', '8108817717@gmail.com', '8108817717@gmail.com', 'Desert5.jpg', NULL, '0000-00-00', '', '', '', '0000-00-00 00:00:00', '', 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(28, 'abc', 'abc', 'abc', '2017-04-09', '9167632340', '9167632340', '1', '916763234032@yahoo.in', '916763234032@yahoo.in', 'Penguins47.jpg', NULL, '0000-00-00', '', '', '', '0000-00-00 00:00:00', '', 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(29, 'abc', 'abc', 'abc', '1970-01-01', '1234567890', '1234567890', '1', '123456789098@ymail.com', '123456789098@ymail.com', 'Desert6.jpg', NULL, '0000-00-00', '', '', '', '0000-00-00 00:00:00', '', 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(30, 'abc', 'abc', 'abc', '2017-01-08', '9870504532', '9870504532', '1', '9870504532@yhoo.in', '9870504532@yhoo.in', 'Desert8.jpg', NULL, '0000-00-00', '', '', '', '0000-00-00 00:00:00', '', 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(31, 'om', 'prakash', 'yadav', '1970-01-01', '879656453421', '879656453421', '1', '879656453421@abc.in', '879656453421@abc.in', 'Jellyfish3.jpg', NULL, '0000-00-00', '', '', '', '0000-00-00 00:00:00', '', 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(32, 'om', 'prakash', 'yadav', '1970-01-01', '879656453421', '879656453421', '1', '87965645342@abc.in', '879656453421@abc.in', 'Jellyfish4.jpg', NULL, '0000-00-00', '', '', '', '0000-00-00 00:00:00', '', 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(33, 'om', 'prakash', 'yadav', '1970-01-01', '879656453421', '879656453421', '1', '8796564532@abc.in', '879656453421@abc.in', 'Jellyfish5.jpg', NULL, '0000-00-00', '', '', '', '0000-00-00 00:00:00', '', 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(34, 'om', 'prakash', 'yadav', '1970-01-01', '879656453421', '879656453421', '1', '8796564532@abc.inf', '879656453421@abc.in', 'defaultm.jpg', NULL, '0000-00-00', '', '', '', '0000-00-00 00:00:00', '', 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(35, 'om', 'prakash', 'yadav', '1970-01-01', '879656453421', '879656453421', '1', '8796564532@abc.inh', '879656453421@abc.in', 'defaultm.jpg', NULL, '0000-00-00', '', '', '', '0000-00-00 00:00:00', '', 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(36, 'Om', 'prakash', 'yadav', '2017-03-08', '1234567890', '1234567890', '1', '1234567890@google.com', '1234567890@google.com', 'Penguins49.jpg', NULL, '0000-00-00', '', '', '', '0000-00-00 00:00:00', '', 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(37, 'Om', 'prakash', 'yadav', '2017-03-08', '1234567890', '1234567890', '1', '1234567894@google.com', '1234567890@google.com', 'Desert11.jpg', NULL, '0000-00-00', '', '', '', '0000-00-00 00:00:00', '', 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(38, 'Test', 'T', 'Employee', '1970-01-01', '8108765848', '9920758818', '1', 'mailm@gmail.com', 'primarykeytech@gmail.com', '', NULL, '0000-00-00', '', '', '', '0000-00-00 00:00:00', '', 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(39, 'Deepak', 'P', 'Jha', '1991-04-06', '8108765848', '9920758818', '1', 'pui@bhgk.jhjgh', 'vmn@hjbkl.jhgj', '', NULL, '0000-00-00', '', '', '', '0000-00-00 00:00:00', '', 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(40, 'Kishan', 'M', 'Lokhande', '1969-12-01', '9225122112', '9821625731', '1', 'kisanl@rediffmail.com', 'mailme.deepakjha@gmail.com', 'silhouette-165527_1920.jpg', NULL, '0000-00-00', '', '', '', '0000-00-00 00:00:00', '', 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(41, 'Primary', 'Key', 'Technologies', '1970-01-01', '8108765848', '', '1', 'mailme.deejha@gmail.com', '', '', NULL, '0000-00-00', '', '', '', '0000-00-00 00:00:00', '', 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(43, 'hgjh', 'kjhk', 'hjkjhk', '1991-07-03', '8108765848', '9920758818', '1', 'mailme.d@gmail.com', '', '', NULL, '0000-00-00', '', '', '', '0000-00-00 00:00:00', '', 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(44, 'hgjh', 'kjhk', 'hjkjhk', '1991-07-03', '8108765848', '9920758818', '1', 'maime.d@gmail.com', '', '', NULL, '0000-00-00', '', '', '', '0000-00-00 00:00:00', '', 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(45, 'hgjh', 'kjhk', 'hjkjhk', '1991-07-03', '8108765848', '9920758818', '1', 'mame.d@gmail.com', '', '', NULL, '0000-00-00', '', '', '', '0000-00-00 00:00:00', '', 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(46, 'hgjh', 'kjhk', 'hjkjhk', '1991-11-18', '8108765848', '9920758818', '1', 'mame.d@gmail.cm', '', 'WhatsApp_Image_2017-07-16_at_7_51_44_PM2.jpeg', 'MSS0000046', '0000-00-00', '', '', '', '0000-00-00 00:00:00', '', 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(47, 'gtset', 'jhgj', 'nbmnb', '1970-01-01', '8108765848', '9920758818', '3', 'hjbjkkkjk@gghkjl.jhgjk', '', 'WhatsApp_Image_2017-07-16_at_7_54_57_PM.jpeg', 'MSS0000047', '0000-00-00', '', '', '', '0000-00-00 00:00:00', '', 1, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(48, 'jhbjk', 'jhghjk', 'kjghjgkj', '2017-09-09', '8108765848', '', '1', 'hgvjkblkml@hgbjkjl.kjhk', '', '', 'MSS0000048', '0000-00-00', '', '', '', '0000-00-00 00:00:00', '', 1, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(49, 'Primary', 'Key', 'Technologies', '1970-01-01', '8108765848', '', '3', 'gfchgjkbl@gfgvhj.hj', '', 'WhatsApp_Image_2017-07-16_at_7_51_44_PM3.jpeg', 'MSS0000049', '0000-00-00', '', '', '', '0000-00-00 00:00:00', '', 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(50, 'Primary', 'Key', 'Technologies', '2017-09-09', '8108765848', '9920758818', 'A+ve', 'hgfh@hgjl.hgj', '', 'logo.jpg', 'MSS0000050', '0000-00-00', '', '', '', '0000-00-00 00:00:00', '', 1, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(51, 'Ashwini', 'M.', 'Mishra', '1990-08-27', '8130509687', '', 'A+ve', 'ashwani.mishraa27@gmail.com', '', 'DSC01184.JPG', 'MSS0000051', '2017-09-05', '', '', '', '0000-00-00 00:00:00', '', 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(52, 'Shubham', 'K', 'Lokhande', '1991-06-04', '7875338100', '', 'A+ve', 'shubham.lokhande.1998@gmail.com', '', 'DSC01160.JPG', 'MSS0000052', '2017-09-12', '', '', '', '0000-00-00 00:00:00', '', 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(64, 'Om', 'Prakash', 'Pandey', '2017-09-25', '0987654321', '0987654321', '2', '0987654321@gmail.com', '0987654321@gmail.com', 'Penguins15.jpg', '65', '0000-00-00', '0987654321@gmail.com', '0987654321@gmail.com', '', '0000-00-00 00:00:00', '', 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(66, 'Om', 'Prakash', 'Pandey', '2017-09-25', '0987654321', '0987654321', '3', '09876549871@gmail.com', '0987654321@gmail.com', 'Koala1.jpg', 'MISS0000066', '2017-10-11', '0987654321@gmail.com', '0987654321@gmail.com', '', '0000-00-00 00:00:00', '', 1, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(67, 'Om', 'Prakash', 'Pandey', '2017-12-04', '9876543210', '9876543210', '2', '', '', 'rajasthan4.png', 'MISS0000067', '0000-00-00', '9876543210', '9876543210', '9876543210', '0000-00-00 00:00:00', '1', 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(68, 'Yuvraj', 'S.', 'Singh', '2017-11-10', '918764367588', '918764367588', '2', '', '', 'Chrysanthemum.jpg', 'MISS0000068', '0000-00-00', '918764367588', '918764367588', '918764367588', '0000-00-00 00:00:00', '1', 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(69, 'Rohit', 'D.', 'Singh', '2017-11-28', '9876543210', '9876543210', '2', '', '', 'Penguins17.jpg', 'MISS0000069', '0000-00-00', '9876543210', '9876543210', '9876543210', '0000-00-00 00:00:00', '1', 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(70, 'Rohit', 'D.', 'Singh', '2017-11-28', '9876543210', '9876543210', '2', '', '', 'Penguins18.jpg', 'MISS0000070', '0000-00-00', '9876543210', '9876543210', '9876543210', '0000-00-00 00:00:00', '1', 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(71, 'Rohit', 'D.', 'Singh', '2017-11-28', '9876543210', '9876543210', '2', '', '', 'Penguins19.jpg', 'MISS0000071', '0000-00-00', '9876543210', '9876543210', '9876543210', '0000-00-00 00:00:00', '1', 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(72, 'Rohit', 'D.', 'Singh', '2017-11-28', '9876543210', '9876543210', '2', '', '', 'Penguins20.jpg', 'MISS0000072', '0000-00-00', '9876543210', '9876543210', '9876543210', '0000-00-00 00:00:00', '1', 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(73, 'test', 'test', 'test', '2017-11-10', '988765432210', '988765432210', '1', '', '', 'Lighthouse.jpg', 'MISS0000073', '0000-00-00', '988765432210', '988765432210', '988765432210', '0000-00-00 00:00:00', '1', 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(74, 'test', 'test', 'test', '2017-11-10', '988765432210', '988765432210', '1', '', '', 'Lighthouse1.jpg', 'MISS0000074', '0000-00-00', '988765432210', '988765432210', '988765432210', '0000-00-00 00:00:00', '1', 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(75, 'abc', 'abc', 'abc', '2017-11-10', '988668899100', '98866889989', '', '', '', '', 'PKT/Driver/0000075', '0000-00-00', '988668899', '988668899', '988668899', '0000-00-00 00:00:00', '1', 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `emp_code` (`emp_code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;