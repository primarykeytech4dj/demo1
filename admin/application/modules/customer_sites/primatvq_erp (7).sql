-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 16, 2017 at 02:43 PM
-- Server version: 10.1.16-MariaDB
-- PHP Version: 7.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `primatvq_erp`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `id` int(11) NOT NULL,
  `address_1` text NOT NULL,
  `address_2` text NOT NULL,
  `landmark` varchar(255) NOT NULL,
  `site_name` varchar(255) NOT NULL,
  `billing_address` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `area_id` int(11) NOT NULL,
  `type` enum('employees','customers','suppliers','') NOT NULL,
  `city_id` int(11) NOT NULL,
  `state_id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  `pincode` int(11) NOT NULL,
  `is_default` tinyint(4) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`id`, `address_1`, `address_2`, `landmark`, `site_name`, `billing_address`, `user_id`, `area_id`, `type`, `city_id`, `state_id`, `country_id`, `pincode`, `is_default`, `is_active`, `created`, `modified`) VALUES
(1, 'New Address ', 'New Address', '', '', '', 22, 1, 'employees', 1, 1, 1, 411010, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'test', 'test', '', '', '', 20, 1, 'employees', 1, 1, 1, 400101, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'test addrss', 'test adres', '', '', '', 23, 2, 'employees', 1, 1, 1, 400101, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'test addrss', 'test adres', '', '', '', 23, 2, 'employees', 1, 1, 1, 400101, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 'hgj', 'bgjhb', '', '', '', 0, 1, 'employees', 1, 1, 1, 400101, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 'hgj', 'bgjhb', '', '', '', 0, 1, 'employees', 1, 1, 1, 400101, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 'hgj', 'bgjhb', '', '', '', 0, 1, 'employees', 1, 1, 1, 400101, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(8, 'hgj', 'bgjhb', '', '', '', 0, 1, 'employees', 1, 1, 1, 400101, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(9, 'test rudra', 'test rudra', '', '', '', 22, 1, 'employees', 1, 1, 1, 400101, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10, 'test address 1', 'test address 2', '', '', '', 20, 1, 'employees', 1, 1, 1, 400101, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(11, 'test address 1', 'test address 2', '', '', '', 20, 1, 'employees', 1, 1, 1, 400101, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(12, 'tset address 1', 'test address 2', '', '', '', 22, 2, 'employees', 1, 1, 1, 400101, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(13, 'tset address 1', 'test address 2', '', '', '', 22, 2, 'employees', 1, 1, 1, 400101, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(14, 'tset address 1', 'test address 2', '', '', '', 22, 2, 'employees', 1, 1, 1, 400101, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(15, 'test 3', 'test 4', '', '', '', 22, 1, 'employees', 1, 1, 1, 400101, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(16, 'test address 1', 'test address 2', '', '', '', 20, 2, 'employees', 1, 1, 1, 400101, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(17, 'test', 'test', '', '', '', 47, 1, 'employees', 1, 1, 1, 400101, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(18, 'test', 'test 2', '', '', '', 30, 5, 'employees', 1, 1, 1, 400101, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(19, 'test', 'test 2', '', '', '', 48, 1, 'employees', 1, 1, 1, 400101, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(20, 'Test address', 'test address 2', '', '', '', 1, 1, 'employees', 1, 1, 1, 400101, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(21, 'Shop No F-3, Vini Heights CHS LTD', 'Near BPCL, Laxmiben Chedda Marg', '', '', '', 50, 10, 'employees', 1, 1, 1, 401203, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(22, '205, D wing, Yashwant Gaurav Apple 1 CHS LTD', 'Yashwant Nagar', '', '', '', 51, 10, 'employees', 1, 1, 1, 401203, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(23, 'new Updated Address', 'New Updated Address 2', '', '', '', 23, 1, 'employees', 1, 1, 1, 400101, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(24, 'tset adrers', 'teshu addrets', '', '', '', 23, 1, 'employees', 1, 1, 1, 400101, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(25, 'b-209, Shushila Bhavan', 'Nr Parvati Cinema HAll.', '', '', '', 52, 12, 'employees', 1, 1, 1, 401202, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(26, 'Delhi', 'Delhi 2', '', '', '', 5, 1, 'customers', 1, 1, 1, 400101, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `areas`
--

CREATE TABLE `areas` (
  `id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `area_name` varchar(255) NOT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `areas`
--

INSERT INTO `areas` (`id`, `city_id`, `area_name`, `is_active`, `created`, `modified`) VALUES
(1, 1, 'Kandivali East', 1, '2017-03-03 00:00:00', '2017-03-03 00:00:00'),
(2, 1, 'Kandivali(W)', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 1, 'Dahanu(East)', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 1, 'Dahanu(W)', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 1, 'Palghar(E)', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 1, 'Palghar(W)', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 1, 'Virar(E)', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(8, 1, 'Virar(W)', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(9, 1, 'Nalasopara(E)', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10, 1, 'Nalasopara(W)', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(11, 1, 'Vasai(E)', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(12, 1, 'Vasai(W)', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(13, 1, 'Naigaon(E)', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(14, 1, 'Naigaon(W)', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(15, 1, 'Bhayandar(E)', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(16, 1, 'Bhayander(W)', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(17, 1, 'Mira Road(E)', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(18, 1, 'Mira Road(W)', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(19, 1, 'Dahisar(E)', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(20, 1, 'Dahisar(W)', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(21, 1, 'Borivali(E)', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(22, 1, 'Borivali(W)', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(23, 1, 'Malad(E)', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(24, 1, 'Malad(W)', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(25, 1, 'Goregaon(E)', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(26, 1, 'Goregaon(W)', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(27, 1, 'Jogeshwari(E)', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(28, 1, 'Jogeshwari(W)', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(29, 1, 'Andheri(E)', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(30, 1, 'Andheri(W)', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(31, 1, 'Vile Parle(E)', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(32, 1, 'Vile Parle(W)', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(33, 1, 'Santacruz(E)', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(34, 1, 'Santacruz(W)', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(35, 1, 'Khar Road(E)', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(36, 1, 'Khar Road(W)', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(37, 1, 'Bandra(E)', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(38, 1, 'Bandra(W)', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(39, 1, 'Mahim(E)', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(40, 1, 'Mahim(W)', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(41, 1, 'Matunga(E)', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `assets`
--

CREATE TABLE `assets` (
  `id` int(11) NOT NULL,
  `assets` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bank_accounts`
--

CREATE TABLE `bank_accounts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_type` enum('employees','customers','suppliers','') NOT NULL,
  `bank_name` varchar(255) NOT NULL,
  `account_type` enum('Saving Account','Current Account','','') NOT NULL,
  `account_number` varchar(20) NOT NULL,
  `ifsc_code` varchar(20) NOT NULL,
  `branch` varchar(255) NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bank_accounts`
--

INSERT INTO `bank_accounts` (`id`, `user_id`, `user_type`, `bank_name`, `account_type`, `account_number`, `ifsc_code`, `branch`, `is_default`, `is_active`, `created`, `modified`) VALUES
(1, 51, 'employees', 'PNB', 'Saving Account', '1286001500008182', 'PUNB004', 'Kandivali (W), Mathuradas Road', 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 51, 'employees', 'HDFC Bank', 'Saving Account', '12345678952', 'HDFC5', 'Thane', 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 51, 'employees', 'HDFC Bank', 'Saving Account', '154545', 'hgjgjgj', 'khjgkj', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 50, 'employees', 'IDBI', 'Current Account', '1133586333', 'IDBI123', 'Kandivali East', 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 52, 'employees', 'HDFC', 'Saving Account', '12354896523', 'GFHJGJK', 'Thane', 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 5, 'customers', 'HDFC Bank', 'Current Account', '123456789525', 'HDFC56', 'worli', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `blood_group`
--

CREATE TABLE `blood_group` (
  `id` int(11) NOT NULL,
  `blood_group` varchar(5) NOT NULL,
  `desciption` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `blood_group`
--

INSERT INTO `blood_group` (`id`, `blood_group`, `desciption`, `is_active`, `created`, `modified`) VALUES
(1, 'A+ve', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'B+ve', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'AB+', 'AB+', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'O+ve', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` int(11) NOT NULL,
  `city_name` varchar(255) NOT NULL,
  `short_name` varchar(25) NOT NULL,
  `country_id` int(11) NOT NULL,
  `state_id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `population` varchar(255) NOT NULL,
  `population_class` char(10) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `city_name`, `short_name`, `country_id`, `state_id`, `type`, `population`, `population_class`, `is_active`, `created`, `modified`) VALUES
(1, 'Mumbai', '', 1, 1, 'Municipal Corporation / Corporation', '1,35,97,924', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'Delhi', '', 1, 2, 'Municipal Corporation / Corporation', '1,10,07,835', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'Bengaluru', '', 1, 3, 'Municipal Corporation / Corporation', '84,25,970', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'Ahmedabad', '', 1, 4, 'Municipal Corporation / Corporation.', '72,08,200', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 'Hyderabad', '', 1, 5, 'Municipal Corporation / Corporation', '68,09,970', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 'Chennai', '', 1, 6, 'Municipal Corporation / Corporation.', '46,81,087', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 'Kolkata', '', 1, 7, 'Municipal Corporation / Corporation', '44,86,679', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(8, 'Pune', '', 1, 1, 'Urban Agglomeration', '37,60,636', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(9, 'Jaipur', '', 1, 8, 'Municipal Corporation / Corporation', '30,73,350', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10, 'Surat', '', 1, 4, 'Municipal Corporation / Corporation.', '28,76,374', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(11, 'Lucknow', '', 1, 9, 'Municipal Corporation / Corporation', '28,15,601', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(12, 'Kanpur', '', 1, 9, 'Municipal Corporation / Corporation', '27,67,031', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(13, 'Nagpur', '', 1, 1, 'Municipal Corporation / Corporation', '24,05,421', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(14, 'Patna', '', 1, 10, 'Municipal Corporation / Corporation.', '22,31,554', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(15, 'Indore', '', 1, 11, 'Municipal Corporation / Corporation.', '19,60,631', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(16, 'Thane', '', 1, 1, 'Municipal Corporation / Corporation.', '18,18,872', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(17, 'Bhopal', '', 1, 11, 'Municipal Corporation / Corporation.', '17,95,648', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(18, 'Visakhapatnam', '', 1, 12, 'Municipal Committee.', '17,30,320', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(19, 'Vadodara', '', 1, 4, 'Municipal Committee.', '16,66,703', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(20, 'Firozabad', '', 1, 9, 'Census town.', '16,45,675', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(21, 'Ludhiana', '', 1, 13, 'Municipal Corporation / Corporation.', '16,13,878', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(22, 'Rajkot', '', 1, 4, 'Urban Agglomeration', '16,06,745', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(23, 'Agra', '', 1, 9, 'Municipal Corporation / Corporation.', '15,74,542', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(24, 'Siliguri', '', 1, 7, 'Municipal Corporation / Corporation.', '15,72,000', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(25, 'Nashik', '', 1, 1, 'Municipal Corporation / Corporation', '14,86,973', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(26, 'Faridabad', '', 1, 14, 'Municipal Corporation / Corporation.', '14,04,653', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(27, 'Patiala', '', 1, 13, 'Municipal Corporation / Corporation.', '13,54,686', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(28, 'Meerut', '', 1, 9, 'Municipal Corporation / Corporation', '13,09,023', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(29, 'Kalyan-Dombivali', '', 1, 1, 'Municipal Corporation / Corporation.', '12,46,381', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(30, 'Vasai-Virar', '', 1, 1, 'Municipal Corporation / Corporation.', '12,21,233', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(31, 'Varanasi', '', 1, 9, 'Municipal Corporation / Corporation.', '12,01,815', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(32, 'Srinagar', '', 1, 15, 'Urban Agglomeration', '11,92,792', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(33, 'Dhanbad', '', 1, 16, 'Municipal Corporation / Corporation', '11,61,561', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(34, 'Jodhpur', '', 1, 8, 'Municipal Corporation / Corporation', '11,37,000', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(35, 'Amritsar', '', 1, 13, 'Municipal Corporation / Corporation.', '11,32,761', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(36, 'Raipur', '', 1, 17, 'Municipal Committee', '11,22,555', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(37, 'Allahabad', '', 1, 9, 'Municipality.', '11,17,094', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(38, 'Coimbatore', '', 1, 6, 'Municipal Corporation / Corporation', '10,61,447', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(39, 'Jabalpur', '', 1, 11, 'Municipal Corporation / Corporation', '10,54,336', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(40, 'Gwalior', '', 1, 11, 'Municipal Corporation / Corporation', '10,53,505', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(41, 'Vijayawada', '', 1, 12, 'Municipal Corporation / Corporation.', '10,48,240', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(42, 'Madurai', '', 1, 6, 'Municipal Corporation / Corporation', '10,16,885', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(43, 'Guwahati', '', 1, 18, 'Municipal Corporation / Corporation', '9,63,429', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(44, 'Chandigarh', '', 1, 19, 'Municipal Corporation / Corporation.', '9,60,787', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(45, 'Hubli-Dharwad', '', 1, 3, 'Municipal Corporation / Corporation', '9,43,857', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(46, 'Amroha', '', 1, 9, 'Nagar Panchayat.', '8,97,135', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(47, 'Moradabad', '', 1, 9, 'Municipal Corporation / Corporation', '8,89,810', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(48, 'Gurgaon', '', 1, 14, 'Municipal Corporation / Corporation.', '8,76,824', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(49, 'Aligarh', '', 1, 9, 'Municipalityunicipal Corporation / Corporation.', '8,72,575', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(50, 'Solapur', '', 1, 1, 'Municipal Corporation / Corporation.', '8,72,478', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(51, 'Ranchi', '', 1, 16, 'Urban Agglomeration', '8,63,495', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(52, 'Jalandhar', '', 1, 13, 'Municipal Corporation / Corporation', '8,62,196', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(53, 'Tiruchirappalli', '', 1, 6, 'Municipal Corporation / Corporation.', '8,46,915', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(54, 'Bhubaneswar', '', 1, 20, 'Municipal Corporation / Corporation', '8,37,737', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(55, 'Salem', '', 1, 6, 'Municipal Corporation / Corporation', '8,31,038', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(56, 'Warangal', '', 1, 5, 'Municipal Corporation / Corporation.', '8,19,429', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(57, 'Mira-Bhayandar', '', 1, 1, 'Municipal Corporation / Corporation', '8,14,655', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(58, 'Thiruvananthapuram', '', 1, 21, 'Municipal Corporation / Corporation.', '7,52,490', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(59, 'Bhiwandi', '', 1, 1, 'Municipal Corporation / Corporation.', '7,11,329', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(60, 'Saharanpur', '', 1, 9, 'Municipal Corporation / Corporation', '7,03,345', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(61, 'Guntur', '', 1, 12, 'Municipal Corporation / Corporation', '6,51,382', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(62, 'Amravati', '', 1, 1, 'Municipal Corporation / Corporation', '6,46,801', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(63, 'Bikaner', '', 1, 8, 'Municipal Corporation / Corporation.', '6,44,406', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(64, 'Noida', '', 1, 9, 'Census town', '6,42,381', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(65, 'Jamshedpur', '', 1, 16, 'Notified area committee / Notified Area Council ', '6,29,659', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(66, 'Bhilai Nagar', '', 1, 17, 'Municipal Corporation / Corporation', '6,25,697', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(67, 'Cuttack', '', 1, 20, 'Municipal Corporation / Corporation', '6,06,007', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(68, 'Kochi', '', 1, 21, 'Municipal Corporation / Corporation', '6,01,574', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(69, 'Udaipur', '', 1, 8, 'Municipal Corporation / Corporation', '5,98,483', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(70, 'Bhavnagar', '', 1, 4, 'Municipal Corporation / Corporation', '5,93,768', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(71, 'Dehradun', '', 1, 22, 'Municipal Corporation / Corporation', '5,78,420', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(72, 'Asansol', '', 1, 7, 'Municipal Corporation / Corporation.', '5,64,491', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(73, 'Nanded-Waghala', '', 1, 1, 'Municipal Corporation / Corporation.', '5,50,564', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(74, 'Ajmer', '', 1, 8, 'Municipal Corporation / Corporation.', '5,42,580', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(75, 'Jamnagar', '', 1, 4, 'Municipal Corporation / Corporation', '5,29,308', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(76, 'Ujjain', '', 1, 11, 'Municipal Corporation / Corporation.', '5,15,215', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(77, 'Sangli', '', 1, 1, 'Urban Agglomeration', '5,13,862', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(78, 'Loni', '', 1, 9, 'Nagar Panchayat', '5,12,296', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(79, 'Jhansi', '', 1, 9, 'Municipal Corporation / Corporation.', '5,07,293', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(80, 'Pondicherry', '', 1, 23, 'Urban Agglomeration', '5,05,959', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(81, 'Nellore', '', 1, 12, 'Municipal Corporation / Corporation', '5,05,258', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(82, 'Jammu', '', 1, 15, 'Municipal Committee', '5,03,690', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(83, 'Belagavi', '', 1, 3, 'Municipal Corporation / Corporation.', '4,88,292', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(84, 'Raurkela', '', 1, 20, 'Municipal Corporation / Corporation', '4,84,874', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(85, 'Mangaluru', '', 1, 3, 'Municipal Corporation / Corporation', '4,84,785', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(86, 'Tirunelveli', '', 1, 6, 'Municipal Corporation / Corporation.', '4,74,838', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(87, 'Malegaon', '', 1, 1, 'Municipal Corporation / Corporation', '4,71,006', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(88, 'Gaya', '', 1, 10, 'Municipal Corporation / Corporation.', '4,63,454', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(89, 'Tiruppur', '', 1, 6, 'Municipal Corporation / Corporation', '4,44,543', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(90, 'Davanagere', '', 1, 3, 'Municipal Corporation / Corporation.', '4,35,128', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(91, 'Kozhikode', '', 1, 21, 'Municipal Corporation / Corporation', '4,32,097', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(92, 'Akola', '', 1, 1, 'Municipal Corporation / Corporation.', '4,27,146', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(93, 'Kurnool', '', 1, 12, 'Municipal Corporation / Corporation', '4,24,920', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(94, 'Bokaro Steel City', '', 1, 16, 'Census town', '4,13,934', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(95, 'Rajahmundry', '', 1, 12, 'Urban Agglomeration', '4,13,616', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(96, 'Ballari', '', 1, 3, 'Municipal Corporation / Corporation.', '4,09,644', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(97, 'Agartala', '', 1, 24, 'Municipal Corporation / Corporation.', '3,99,688', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(98, 'Bhagalpur', '', 1, 10, 'Municipal Corporation / Corporation.', '3,98,138', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(99, 'Latur', '', 1, 1, 'Municipal Council', '3,82,754', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(100, 'Dhule', '', 1, 1, 'Municipal Corporation / Corporation', '3,76,093', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(101, 'Korba', '', 1, 17, 'Municipal Corporation / Corporation.', '3,63,210', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(102, 'Bhilwara', '', 1, 8, 'Municipality', '3,60,009', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(103, 'Brahmapur', '', 1, 20, 'Municipal Corporation / Corporation', '3,55,823', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(104, 'Mysore', '', 1, 25, 'Municipal Corporation / Corporation.', '3,51,838', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(105, 'Muzaffarpur', '', 1, 10, 'Municipal Corporation / Corporation.', '3,51,838', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(106, 'Ahmednagar', '', 1, 1, 'Municipal Corporation / Corporation.', '3,50,905', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(107, 'Kollam', '', 1, 21, 'Municipal Corporation / Corporation', '3,49,033', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(108, 'Raghunathganj', '', 1, 7, 'Municipality', '3,46,854', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(109, 'Bilaspur', '', 1, 17, 'Municipal Corporation / Corporation', '3,30,106', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(110, 'Shahjahanpur', '', 1, 9, 'Urban Agglomeration', '3,21,885', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(111, 'Thrissur', '', 1, 21, 'Municipal Corporation / Corporation.', '3,15,596', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(112, 'Alwar', '', 1, 8, 'Municipal Council', '3,15,310', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(113, 'Kakinada', '', 1, 12, 'Municipal Corporation / Corporation', '3,12,255', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(114, 'Nizamabad', '', 1, 5, 'Municipal Corporation / Corporation', '3,10,467', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(115, 'Sagar', '', 1, 11, 'Urban Agglomeration', '3,08,922', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(116, 'Tumkur', '', 1, 3, 'City Municipal Council', '3,05,821', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(117, 'Hisar', '', 1, 14, 'Municipal Council', '3,01,249', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(118, 'Rohtak', '', 1, 14, 'Municipal Corporation / Corporation.', '2,94,577', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(119, 'Panipat', '', 1, 14, 'Municipal Council', '2,94,150', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(120, 'Darbhanga', '', 1, 10, 'Municipal Corporation / Corporation.', '2,94,116', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(121, 'Kharagpur', '', 1, 7, 'Municipality/Industrial Township', '2,93,719', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(122, 'Aizawl', '', 1, 26, 'Notified Town', '2,91,822', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(123, 'Ichalkaranji', '', 1, 1, 'Municipal Council', '2,87,570', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(124, 'Tirupati', '', 1, 12, 'Municipal Corporation / Corporation.', '2,87,035', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(125, 'Karnal', '', 1, 14, 'Municipal Council', '2,86,974', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(126, 'Bathinda', '', 1, 13, 'Municipal Corporation / Corporation', '2,85,813', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(127, 'Rampur', '', 1, 9, 'Municipal board', '2,81,494', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(128, 'Shivamogga', '', 1, 3, 'City Municipal Council', '2,74,352', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(129, 'Ratlam', '', 1, 11, 'Urban Agglomeration', '2,73,892', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(130, 'Modinagar', '', 1, 9, 'Municipality', '2,72,918', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(131, 'Durg', '', 1, 17, 'Municipal Corporation / Corporation', '2,68,679', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(132, 'Shillong', '', 1, 27, 'Urban Agglomeration', '2,67,662', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(133, 'Imphal', '', 1, 28, 'Municipal Council', '2,64,986', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(134, 'Hapur', '', 1, 9, 'Nagar Palika Parishad', '2,62,801', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(135, 'Ranipet', '', 1, 6, 'Urban Agglomeration', '2,62,346', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(136, 'Anantapur', '', 1, 12, 'Municipal Corporation / Corporation.', '2,62,340', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(137, 'Arrah', '', 1, 10, 'Municipal Corporation / Corporation.', '2,61,099', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(138, 'Karimnagar', '', 1, 5, 'Municipal Corporation / Corporation', '2,60,899', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(139, 'Parbhani', '', 1, 1, 'Municipal Council', '2,59,329', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(140, 'Etawah', '', 1, 9, 'Nagar Palika Parishad', '2,56,790', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(141, 'Bharatpur', '', 1, 8, 'Municipal Corporation / Corporation.', '2,52,109', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(142, 'Begusarai', '', 1, 10, 'Municipal Corporation / Corporation.', '2,51,136', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 'New Delhi', '', 1, 2, 'Municipal Council', '2,49,998', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(144, 'Chhapra', '', 1, 10, 'Municipal Corporation / Corporation', '2,49,555', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(145, 'Kadapa', '', 1, 12, 'Municipal Corporation / Corporation', '2,41,823', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(146, 'Ramagundam', '', 1, 5, 'Municipal Corporation / Corporation', '2,37,686', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(147, 'Pali', '', 1, 8, 'Municipal Council', '2,29,956', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(148, 'Satna', '', 1, 11, 'Urban Agglomeration', '2,29,307', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(149, 'Vizianagaram', '', 1, 12, 'Municipality', '2,28,025', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(150, 'Katihar', '', 1, 10, 'Municipal Corporation / Corporation', '2,25,982', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(151, 'Hardwar', '', 1, 22, 'Nagar Palika Parishad', '2,25,235', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(152, 'Sonipat', '', 1, 14, 'Urban Agglomeration', '2,25,074', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(153, 'Nagercoil', '', 1, 6, 'Municipality', '2,24,329', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(154, 'Thanjavur', '', 1, 6, 'Municipality', '2,22,619', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(155, 'Murwara (Katni)', '', 1, 11, 'Municipal Corporation / Corporation', '2,21,875', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(156, 'Naihati', '', 1, 7, 'Municipality', '2,21,762', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(157, 'Sambhal', '', 1, 9, 'Nagar Palika Parishad', '2,21,334', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(158, 'Nadiad', '', 1, 4, 'Municipality', '2,18,150', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(159, 'Yamunanagar', '', 1, 14, 'Municipal Council', '2,16,628', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(160, 'English Bazar', '', 1, 7, 'Municipality', '2,16,083', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(161, 'Eluru', '', 1, 12, 'Municipal Corporation / Corporation.', '2,14,414', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(162, 'Munger', '', 1, 10, 'Municipal Corporation / Corporation', '2,13,101', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(163, 'Panchkula', '', 1, 14, 'Municipal Council', '2,10,175', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(164, 'Raayachuru', '', 1, 3, 'City Municipal Council', '2,07,421', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(165, 'Panvel', '', 1, 1, 'Municipal Council', '2,04,336', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(166, 'Deoghar', '', 1, 16, 'Municipal Corporation / Corporation', '2,03,116', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(167, 'Ongole', '', 1, 12, 'Municipality', '2,02,826', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(168, 'Nandyal', '', 1, 12, 'Municipality', '2,00,746', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(169, 'Morena', '', 1, 11, 'Municipality', '2,00,506', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(170, 'Bhiwani', '', 1, 14, 'Municipal Council', '1,97,662', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(171, 'Porbandar', '', 1, 4, 'Urban Agglomeration', '1,97,382', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(172, 'Palakkad', '', 1, 21, 'Urban Agglomeration', '1,97,369', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(173, 'Anand', '', 1, 4, 'Municipality', '1,97,351', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(174, 'Purnia', '', 1, 10, 'Municipal Corporation / Corporation.', '1,97,211', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(175, 'Baharampur', '', 1, 7, 'Municipality', '1,95,363', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(176, 'Barmer', '', 1, 8, 'Nagar Parishad', '1,89,715', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(177, 'Morvi', '', 1, 4, 'Municipality', '1,88,278', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(178, 'Orai', '', 1, 9, 'Nagar Palika Parishad.', '1,87,185', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(179, 'Bahraich', '', 1, 9, 'Nagar Palika Parishad', '1,86,241', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(180, 'Sikar', '', 1, 8, 'Urban Agglomeration', '1,85,925', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(181, 'Vellore', '', 1, 6, 'Municipal Corporation / Corporation.', '1,85,895', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(182, 'Singrauli', '', 1, 11, 'Municipal Corporation / Corporation.', '1,85,190', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(183, 'Khammam', '', 1, 5, 'Municipal Corporation / Corporation', '1,84,252', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(184, 'Mahesana', '', 1, 4, 'Municipality', '1,84,133', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(185, 'Silchar', '', 1, 18, 'Urban Agglomeration', '1,84,105', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(186, 'Sambalpur', '', 1, 20, 'Municipal Corporation / Corporation.', '1,83,383', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(187, 'Rewa', '', 1, 11, 'Municipal Corporation / Corporation.', '1,83,274', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(188, 'Unnao', '', 1, 9, 'Nagar Palika Parishad.', '1,77,658', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(189, 'Hugli-Chinsurah', '', 1, 7, 'Municipality', '1,77,209', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(190, 'Raiganj', '', 1, 7, 'Urban Agglomeration', '1,75,047', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(191, 'Phusro', '', 1, 16, 'Urban Agglomeration', '1,74,402', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(192, 'Adityapur', '', 1, 16, 'Nagar Panchayat.', '1,74,355', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(193, 'Alappuzha', '', 1, 21, 'Municipality', '1,74,164', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(194, 'Bahadurgarh', '', 1, 14, 'Municipal Council', '1,70,426', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(195, 'Machilipatnam', '', 1, 12, 'Municipality', '1,70,008', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(196, 'Rae Bareli', '', 1, 9, 'Municipal board', '1,69,333', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(197, 'Jalpaiguri', '', 1, 7, 'Municipal Corporation / Corporation', '1,69,013', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(198, 'Bharuch', '', 1, 4, 'Municipality', '1,68,729', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(199, 'Pathankot', '', 1, 13, 'Urban Agglomeration', '1,68,485', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(200, 'Hoshiarpur', '', 1, 13, 'Municipal Council', '1,68,443', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(201, 'Baramula', '', 1, 15, 'Municipal Council', '1,67,986', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(202, 'Adoni', '', 1, 12, 'Municipality', '1,66,344', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(203, 'Jind', '', 1, 14, 'Municipal Council', '1,66,225', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(204, 'Tonk', '', 1, 8, 'Municipal Council', '1,65,363', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(205, 'Tenali', '', 1, 12, 'Municipality', '1,64,649', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(206, 'Kancheepuram', '', 1, 6, 'Municipality', '1,64,265', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(207, 'Vapi', '', 1, 4, 'Municipality', '1,63,630', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(208, 'Sirsa', '', 1, 14, 'Municipal Council', '1,60,735', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(209, 'Navsari', '', 1, 4, 'Municipality', '1,60,100', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(210, 'Mahbubnagar', '', 1, 5, 'Municipality', '1,57,902', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(211, 'Puri', '', 1, 20, 'Municipality', '1,57,837', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(212, 'Robertson Pet', '', 1, 3, 'Urban Agglomeration', '1,57,084', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(213, 'Erode', '', 1, 6, 'Municipal Corporation / Corporation.', '1,56,953', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(214, 'Batala', '', 1, 13, 'Municipal Council', '1,56,400', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(215, 'Haldwani-cum-Kathgodam', '', 1, 22, 'Nagar Palika Parishad', '1,56,060', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(216, 'Vidisha', '', 1, 11, 'Municipality', '1,55,959', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(217, 'Saharsa', '', 1, 10, 'Nagar Panchayat', '1,55,175', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(218, 'Thanesar', '', 1, 14, 'Municipal Council', '1,54,962', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(219, 'Chittoor', '', 1, 12, 'Municipal Corporation / Corporation.', '1,53,766', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(220, 'Veraval', '', 1, 4, 'Municipality', '1,53,696', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(221, 'Lakhimpur', '', 1, 9, 'Nagar Palika Parishad', '1,52,010', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(222, 'Sitapur', '', 1, 9, 'Municipal board', '1,51,908', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(223, 'Hindupur', '', 1, 12, 'Municipality', '1,51,835', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(224, 'Santipur', '', 1, 7, 'Municipality', '1,51,774', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(225, 'Balurghat', '', 1, 7, 'Municipality', '1,51,183', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(226, 'Ganjbasoda', '', 1, 11, 'Nagar Panchayat.', '1,50,454', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(227, 'Moga', '', 1, 13, 'Municipal Council', '1,50,432', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(228, 'Proddatur', '', 1, 12, 'Municipality', '1,50,309', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(229, 'Srinagar', '', 1, 22, '', '1,50,000', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(230, 'Medinipur', '', 1, 7, 'Municipality', '1,49,769', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(231, 'Habra', '', 1, 7, 'Municipality', '1,49,675', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(232, 'Sasaram', '', 1, 10, 'Nagar Panchayat', '1,47,396', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(233, 'Hajipur', '', 1, 10, 'Nagar Panchayat', '1,47,126', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(234, 'Bhuj', '', 1, 4, 'Municipality', '1,47,123', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(235, 'Shivpuri', '', 1, 11, 'Municipality', '1,46,892', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(236, 'Ranaghat', '', 1, 7, 'Urban Agglomeration', '1,45,285', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(237, 'Shimla', '', 1, 29, 'Urban Agglomeration', '1,44,975', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(238, 'Tiruvannamalai', '', 1, 6, 'Municipality', '1,44,683', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(239, 'Kaithal', '', 1, 14, 'Municipal Council', '1,44,633', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(240, 'Rajnandgaon', '', 1, 17, 'Municipal Corporation / Corporation.', '1,43,770', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(241, 'Godhra', '', 1, 4, 'Municipality', '1,43,126', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(242, 'Hazaribag', '', 1, 16, 'Nagar Panchayat', '1,42,494', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(243, 'Bhimavaram', '', 1, 12, 'Municipality', '1,42,280', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(244, 'Mandsaur', '', 1, 11, 'Municipality', '1,41,468', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(245, 'Dibrugarh', '', 1, 18, 'Municipal board', '1,38,661', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(246, 'Kolar', '', 1, 3, 'City Municipal Council', '1,38,553', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(247, 'Bankura', '', 1, 7, 'Municipality', '1,38,036', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(248, 'Mandya', '', 1, 3, 'City Municipal Council', '1,37,735', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(249, 'Dehri-on-Sone', '', 1, 10, 'Nagar Panchayat', '1,37,068', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(250, 'Madanapalle', '', 1, 12, 'Municipality', '1,35,669', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(251, 'Malerkotla', '', 1, 13, 'Municipal Council', '1,35,330', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(252, 'Lalitpur', '', 1, 9, 'Nagar Palika Parishad', '1,33,041', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(253, 'Bettiah', '', 1, 10, 'Nagar Panchayat', '1,32,896', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(254, 'Pollachi', '', 1, 6, 'Urban Agglomeration', '1,28,458', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(255, 'Khanna', '', 1, 13, 'Municipal Council', '1,28,130', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(256, 'Neemuch', '', 1, 11, 'Municipality', '1,28,108', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(257, 'Palwal', '', 1, 14, 'Municipal Council', '1,27,931', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(258, 'Palanpur', '', 1, 4, 'Municipality', '1,27,125', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(259, 'Guntakal', '', 1, 12, 'Municipality', '1,26,479', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(260, 'Nabadwip', '', 1, 7, 'Municipality', '1,25,528', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(261, 'Udupi', '', 1, 3, 'City Municipal Council.', '1,25,350', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(262, 'Jagdalpur', '', 1, 17, 'Municipal Corporation / Corporation', '1,25,345', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(263, 'Motihari', '', 1, 10, 'Nagar Panchayat', '1,25,183', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(264, 'Pilibhit', '', 1, 9, 'Municipal board', '1,24,245', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(265, 'Dimapur', '', 1, 30, 'Municipal Committee', '1,23,777', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(266, 'Mohali', '', 1, 13, 'Municipal Council', '1,23,484', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(267, 'Sadulpur', '', 1, 8, 'Municipality', '1,22,326', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(268, 'Rajapalayam', '', 1, 6, 'Municipality', '1,22,307', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(269, 'Dharmavaram', '', 1, 12, 'Municipality', '1,21,992', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(270, 'Kashipur', '', 1, 22, 'Nagar Palika Parishad.', '1,21,610', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(271, 'Sivakasi', '', 1, 6, 'Urban Agglomeration', '1,21,358', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(272, 'Darjiling', '', 1, 7, 'Municipality', '1,20,414', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(273, 'Chikkamagaluru', '', 1, 3, 'City Municipal Council', '1,18,496', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(274, 'Gudivada', '', 1, 12, 'Municipality', '1,18,289', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(275, 'Baleshwar Town', '', 1, 20, 'Municipality', '1,18,202', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(276, 'Mancherial', '', 1, 5, 'Urban Agglomeration', '1,18,195', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(277, 'Srikakulam', '', 1, 12, 'Urban Agglomeration', '1,17,320', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(278, 'Adilabad', '', 1, 5, 'Municipality', '1,17,167', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(279, 'Yavatmal', '', 1, 1, 'Municipal Council', '1,16,714', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(280, 'Barnala', '', 1, 13, 'Municipal Council', '1,16,454', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(281, 'Nagaon', '', 1, 18, 'Municipal board', '1,16,355', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(282, 'Narasaraopet', '', 1, 12, 'Municipality', '1,16,329', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(283, 'Raigarh', '', 1, 17, 'Urban Agglomeration', '1,15,908', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(284, 'Roorkee', '', 1, 22, 'Urban Agglomeration', '1,15,278', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(285, 'Valsad', '', 1, 4, 'Municipality', '1,14,636', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(286, 'Ambikapur', '', 1, 17, 'Municipal Corporation / Corporation.', '1,14,575', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(287, 'Giridih', '', 1, 16, 'Town Panchayat', '1,14,447', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(288, 'Chandausi', '', 1, 9, 'Nagar Palika Parishad', '1,14,254', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(289, 'Purulia', '', 1, 7, 'Municipality', '1,13,806', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(290, 'Patan', '', 1, 4, 'Urban Agglomeration', '1,13,749', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(291, 'Bagaha', '', 1, 10, 'Nagar Panchayat', '1,13,012', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(292, 'Hardoi ', '', 1, 9, 'Municipality', '1,12,486', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(293, 'Achalpur', '', 1, 1, 'Municipal Council', '1,12,293', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(294, 'Osmanabad', '', 1, 1, 'Municipal Council', '1,12,085', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(295, 'Deesa', '', 1, 4, '', '1,11,149', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(296, 'Nandurbar', '', 1, 1, 'Municipal Council', '1,11,067', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(297, 'Azamgarh', '', 1, 9, 'P.', '1,10,980', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(298, 'Ramgarh', '', 1, 16, 'Urban Agglomeration', '1,10,496', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(299, 'Firozpur', '', 1, 13, 'Municipal Council', '1,10,091', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(300, 'Baripada Town', '', 1, 20, 'Municipality', '1,10,058', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(301, 'Karwar', '', 1, 3, 'City Municipal Council', '1,10,000', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(302, 'Siwan', '', 1, 10, 'Municipality', '1,09,919', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(303, 'Rajampet', '', 1, 12, 'Municipality', '1,09,575', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(304, 'Pudukkottai', '', 1, 6, 'Municipality', '1,09,217', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(305, 'Anantnag', '', 1, 15, 'Municipal Council', '1,08,505', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(306, 'Tadpatri', '', 1, 12, 'Municipality', '1,08,249', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(307, 'Satara', '', 1, 1, 'Municipal Council', '1,08,048', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(308, 'Bhadrak', '', 1, 20, 'Municipality', '1,07,369', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(309, 'Kishanganj', '', 1, 10, 'Nagar Panchayat', '1,07,076', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(310, 'Suryapet', '', 1, 5, 'Urban Agglomeration', '1,06,524', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(311, 'Wardha', '', 1, 1, 'Municipal Council', '1,06,444', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(312, 'Ranebennuru', '', 1, 3, 'City Municipal Council', '1,06,365', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(313, 'Amreli', '', 1, 4, 'Municipality', '1,05,980', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(314, 'Neyveli (TS)', '', 1, 6, 'Census town', '1,05,687', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(315, 'Jamalpur', '', 1, 10, 'Nagar Panchayat', '1,05,221', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(316, 'Marmagao', '', 1, 31, 'Urban Agglomeration', '1,04,758', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(317, 'Udgir', '', 1, 1, 'Municipal Council', '1,04,063', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(318, 'Tadepalligudem', '', 1, 12, 'Municipality', '1,03,577', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(319, 'Nagapattinam', '', 1, 6, 'Municipality', '1,02,838', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(320, 'Buxar', '', 1, 10, 'Nagar Panchayat', '1,02,591', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(321, 'Aurangabad', '', 1, 1, 'Municipal Corporation / Corporation.', '1,02,520', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(322, 'Jehanabad', '', 1, 10, 'Nagar Panchayat', '1,02,456', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(323, 'Phagwara', '', 1, 13, 'Urban Agglomeration', '1,02,253', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(324, 'Khair', '', 1, 9, 'Nagar Palika Parishad', '1,02,106', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(325, 'Sawai Madhopur', '', 1, 8, 'Urban Agglomeration', '1,01,997', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(326, 'Kapurthala', '', 1, 13, 'Municipal Council', '1,01,654', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(327, 'Chilakaluripet', '', 1, 12, 'Municipality', '1,01,550', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(328, 'Aurangabad', '', 1, 10, 'Nagar Panchayat.', '1,01,520', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(329, 'Malappuram', '', 1, 21, 'Municipality', '1,01,330', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(330, 'Rewari', '', 1, 14, 'Municipal Council', '1,00,684', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(331, 'Nagaur', '', 1, 8, 'Municipality', '1,00,618', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(332, 'Sultanpur', '', 1, 9, 'Municipal board', '1,00,065', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(333, 'Nagda', '', 1, 11, 'Municipality', '1,00,036', 'Class I', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(334, 'Port Blair', '', 1, 32, 'Municipal Council', '99,984', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(335, 'Lakhisarai', '', 1, 10, 'Nagar Panchayat', '99,979', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(336, 'Panaji', '', 1, 31, 'Urban Agglomeration', '99,677', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(337, 'Tinsukia', '', 1, 18, 'Municipal board.', '99,448', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(338, 'Itarsi', '', 1, 11, 'Municipality', '99,329', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(339, 'Kohima', '', 1, 30, 'Municipal Committee', '99,039', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(340, 'Balangir', '', 1, 20, 'Municipality', '98,238', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(341, 'Nawada', '', 1, 10, 'Nagar Parishad', '98,029', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(342, 'Jharsuguda', '', 1, 20, 'Municipality', '97,730', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(343, 'Jagtial', '', 1, 5, 'Municipality', '96,460', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(344, 'Viluppuram', '', 1, 6, 'Municipality', '96,253', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(345, 'Amalner', '', 1, 1, 'Municipal Council', '95,994', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(346, 'Zirakpur', '', 1, 13, 'Municipal Council', '95,553', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(347, 'Tanda', '', 1, 9, 'Municipal board', '95,516', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(348, 'Tiruchengode', '', 1, 6, 'Municipality', '95,335', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(349, 'Nagina', '', 1, 9, 'Nagar Palika Parishad', '95,246', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(350, 'Yemmiganur', '', 1, 12, 'Municipality', '95,149', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(351, 'Vaniyambadi', '', 1, 6, 'Municipality', '95,061', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(352, 'Sarni', '', 1, 11, 'Municipality', '95,012', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(353, 'Theni Allinagaram', '', 1, 6, 'Municipality', '94,453', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(354, 'Margao', '', 1, 31, 'Urban Agglomeration', '94,383', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(355, 'Akot', '', 1, 1, 'Municipal Council', '92,637', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(356, 'Sehore', '', 1, 11, 'Urban Agglomeration', '92,518', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(357, 'Mhow Cantonment', '', 1, 11, 'Urban Agglomeration', '92,364', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(358, 'Kot Kapura', '', 1, 13, 'Municipal Council', '91,979', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(359, 'Makrana', '', 1, 8, 'Urban Agglomeration', '91,853', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(360, 'Pandharpur', '', 1, 1, 'Municipal Council', '91,379', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(361, 'Miryalaguda', '', 1, 5, 'Urban Agglomeration', '91,359', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(362, 'Shamli', '', 1, 9, 'Municipal board', '90,055', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(363, 'Seoni', '', 1, 11, 'Municipality', '89,801', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(364, 'Ranibennur', '', 1, 3, 'City Municipal Council', '89,618', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(365, 'Kadiri', '', 1, 12, 'Municipality', '89,429', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(366, 'Shrirampur', '', 1, 1, 'Urban Agglomeration', '88,761', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(367, 'Rudrapur', '', 1, 22, 'Municipal board', '88,676', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(368, 'Parli', '', 1, 1, 'Municipal Council', '88,537', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(369, 'Najibabad', '', 1, 9, 'Municipal board', '88,535', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(370, 'Nirmal', '', 1, 5, 'Municipality', '88,433', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(371, 'Udhagamandalam', '', 1, 6, 'Municipality', '88,430', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(372, 'Shikohabad', '', 1, 9, 'Municipal board', '88,161', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(373, 'Jhumri Tilaiya', '', 1, 16, 'Nagar Panchayat', '87,867', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(374, 'Aruppukkottai', '', 1, 6, 'Municipality', '87,722', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(375, 'Ponnani', '', 1, 21, 'Municipality', '87,495', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(376, 'Jamui', '', 1, 10, 'Nagar Panchayat', '87,357', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(377, 'Sitamarhi', '', 1, 10, 'Urban Agglomeration', '87,279', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(378, 'Chirala', '', 1, 12, 'Municipality', '87,200', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(379, 'Anjar', '', 1, 4, 'Municipality', '87,183', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(380, 'Karaikal', '', 1, 23, 'Municipality', '86,838', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(381, 'Hansi', '', 1, 14, 'Municipal Council', '86,770', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(382, 'Anakapalle', '', 1, 12, 'Municipality', '86,519', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(383, 'Mahasamund', '', 1, 17, 'Municipality', '85650', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(384, 'Faridkot', '', 1, 13, 'Municipal Council', '85,435', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(385, 'Saunda', '', 1, 16, 'Census town', '85,075', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(386, 'Dhoraji', '', 1, 4, 'Municipality', '84,545', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(387, 'Paramakudi', '', 1, 6, 'Municipality', '84,321', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(388, 'Balaghat', '', 1, 11, 'Municipality', '84,261', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(389, 'Sujangarh', '', 1, 8, 'Municipality', '83,846', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(390, 'Khambhat', '', 1, 4, 'Municipality', '83,715', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(391, 'Muktsar', '', 1, 13, 'Municipal Council', '83,655', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(392, 'Rajpura', '', 1, 13, 'Municipal Council', '82,956', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(393, 'Kavali', '', 1, 12, 'Municipality', '82,336', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(394, 'Dhamtari', '', 1, 17, 'Municipality', '82,111', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(395, 'Ashok Nagar', '', 1, 11, 'Municipality', '81,828', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(396, 'Sardarshahar', '', 1, 8, 'Municipality', '81,394', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(397, 'Mahuva', '', 1, 4, 'Urban Agglomeration', '80,726', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(398, 'Bargarh', '', 1, 20, 'Municipality', '80,625', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(399, 'Kamareddy', '', 1, 5, 'Municipality', '80,315', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(400, 'Sahibganj', '', 1, 16, 'Municipality', '80,154', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(401, 'Kothagudem', '', 1, 5, 'Municipality', '79,819', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(402, 'Ramanagaram', '', 1, 3, 'City Municipal Council', '79,394', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(403, 'Gokak', '', 1, 3, 'City Municipal Council', '79,121', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(404, 'Tikamgarh', '', 1, 11, 'Municipality', '79,106', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(405, 'Araria', '', 1, 10, 'Nagar Panchayat.', '79,021', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `cities` (`id`, `city_name`, `short_name`, `country_id`, `state_id`, `type`, `population`, `population_class`, `is_active`, `created`, `modified`) VALUES
(406, 'Rishikesh', '', 1, 22, 'Urban Agglomeration', '78,805', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(407, 'Shahdol', '', 1, 11, 'Municipality', '78,624', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(408, 'Medininagar (Daltonganj)', '', 1, 16, 'Nagar Parishad', '78,396', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(409, 'Arakkonam', '', 1, 6, 'Municipality', '78,395', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(410, 'Washim', '', 1, 1, 'Municipal Council', '78,387', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(411, 'Sangrur', '', 1, 13, 'Municipal Council', '77,989', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(412, 'Bodhan', '', 1, 5, 'Municipality', '77,573', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(413, 'Fazilka', '', 1, 13, 'Municipal Council', '76,492', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(414, 'Palacole', '', 1, 12, 'Urban Agglomeration', '76,308', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(415, 'Keshod', '', 1, 4, 'Municipality', '76,193', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(416, 'Sullurpeta', '', 1, 12, 'Municipality', '75,925', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(417, 'Wadhwan', '', 1, 4, 'Municipality', '75,755', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(418, 'Gurdaspur', '', 1, 13, 'Municipal Council', '75,549', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(419, 'Vatakara', '', 1, 21, 'Municipality', '75,295', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(420, 'Tura', '', 1, 27, 'Municipality', '74,858', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(421, 'Narnaul', '', 1, 14, 'Municipal Council', '74,581', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(422, 'Kharar', '', 1, 13, 'Municipal Council', '74,460', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(423, 'Yadgir', '', 1, 3, 'City Municipal Council', '74,294', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(424, 'Ambejogai', '', 1, 1, 'Municipal Council', '73,975', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(425, 'Ankleshwar', '', 1, 4, 'Municipality', '73,928', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(426, 'Savarkundla', '', 1, 4, 'Municipality', '73,774', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(427, 'Paradip', '', 1, 20, 'Notified area committee / Notified Area Council', '73,625', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(428, 'Virudhachalam', '', 1, 6, 'Municipality', '73,585', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(429, 'Kanhangad', '', 1, 21, 'Municipality', '73,342', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(430, 'Kadi', '', 1, 4, 'Municipality', '73,228', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(431, 'Srivilliputhur', '', 1, 6, 'Municipality', '73,183', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(432, 'Gobindgarh', '', 1, 13, 'Municipal Council', '73,130', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(433, 'Tindivanam', '', 1, 6, 'Municipality', '72,796', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(434, 'Mansa', '', 1, 13, 'Municipal Council', '72,627', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(435, 'Taliparamba', '', 1, 21, 'Municipality', '72,465', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(436, 'Manmad', '', 1, 1, 'Municipal Council', '72,401', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(437, 'Tanuku', '', 1, 12, 'Municipality', '72,348', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(438, 'Rayachoti', '', 1, 12, 'Census town', '72,297', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(439, 'Virudhunagar', '', 1, 6, 'Municipality', '72,296', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(440, 'Koyilandy', '', 1, 21, 'Municipality', '71,873', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(441, 'Jorhat', '', 1, 18, 'Municipal board', '71,782', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(442, 'Karur', '', 1, 6, 'Municipality', '70,980', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(443, 'Valparai', '', 1, 6, 'Municipality', '70,859', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(444, 'Srikalahasti', '', 1, 12, 'Municipality', '70,854', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(445, 'Neyyattinkara', '', 1, 21, 'Municipality', '70,850', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(446, 'Bapatla', '', 1, 12, 'Municipality', '70,777', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(447, 'Fatehabad', '', 1, 14, 'Municipal Council', '70,777', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(448, 'Malout', '', 1, 13, 'Municipal Council', '70,765', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(449, 'Sankarankovil', '', 1, 6, 'Municipality', '70,574', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(450, 'Tenkasi', '', 1, 6, 'Municipality', '70,545', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(451, 'Ratnagiri', '', 1, 1, 'Municipal Council', '70,383', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(452, 'Rabkavi Banhatti', '', 1, 3, 'City Municipal Council', '70,248', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(453, 'Sikandrabad', '', 1, 9, 'Municipal board', '69,867', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(454, 'Chaibasa', '', 1, 16, 'Nagar Palika Parishad', '69,565', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(455, 'Chirmiri', '', 1, 17, 'Municipal Corporation / Corporation.', '69,307', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(456, 'Palwancha', '', 1, 5, 'Municipality', '69,088', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(457, 'Bhawanipatna', '', 1, 20, 'Municipality', '69,045', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(458, 'Kayamkulam', '', 1, 21, 'Municipality', '68,634', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(459, 'Pithampur', '', 1, 11, 'Nagar Panchayat', '68,080', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(460, 'Nabha', '', 1, 13, 'Municipal Council', '67,972', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(461, 'Shahabad, Hardoi', '', 1, 9, 'Municipal board', '67,751', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(462, 'Dhenkanal', '', 1, 20, 'Municipality', '67,414', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(463, 'Uran Islampur', '', 1, 1, 'Municipal Council', '67,391', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(464, 'Gopalganj', '', 1, 10, 'Nagar Panchayat', '67,339', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(465, 'Bongaigaon City', '', 1, 18, 'Municipal board', '67,322', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(466, 'Palani', '', 1, 6, 'Municipality', '67,231', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(467, 'Pusad', '', 1, 1, 'Municipal Council', '67,116', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(468, 'Sopore', '', 1, 15, 'Urban Agglomeration', '66,963', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(469, 'Pilkhuwa', '', 1, 9, 'Municipal board', '66,907', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(470, 'Tarn Taran', '', 1, 13, 'Municipal Council', '66,847', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(471, 'Renukoot', '', 1, 9, 'Urban Agglomeration', '66,597', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(472, 'Mandamarri', '', 1, 5, 'Municipality', '66,596', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(473, 'Shahabad', '', 1, 3, 'Urban Agglomeration', '66,550', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(474, 'Barbil', '', 1, 20, 'Municipality', '66,540', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(475, 'Koratla', '', 1, 5, 'Municipality', '66,504', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(476, 'Madhubani', '', 1, 10, 'Municipality', '66,340', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(477, 'Arambagh', '', 1, 7, 'Municipality', '66,175', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(478, 'Gohana', '', 1, 14, 'Municipal Committee', '65,708', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(479, 'Ladnu', '', 1, 8, 'Municipality', '65,575', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(480, 'Pattukkottai', '', 1, 6, 'Municipality', '65,533', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(481, 'Sirsi', '', 1, 3, 'Urban Agglomeration', '65,335', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(482, 'Sircilla', '', 1, 5, 'Municipality', '65,314', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(483, 'Tamluk', '', 1, 7, 'Municipality', '65,306', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(484, 'Jagraon', '', 1, 13, 'Municipal Council', '65,240', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(485, 'AlipurdUrban Agglomerationr', '', 1, 7, 'Municipality', '65,232', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(486, 'Alirajpur', '', 1, 11, 'Municipality', '65,232', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(487, 'Tandur', '', 1, 5, 'Municipality', '65,115', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(488, 'Naidupet', '', 1, 12, 'Municipality', '65,000', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(489, 'Tirupathur', '', 1, 6, 'Municipality', '64,125', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(490, 'Tohana', '', 1, 14, 'Municipal Committee', '63,871', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(491, 'Ratangarh', '', 1, 8, 'Municipality', '63,486', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(492, 'Dhubri', '', 1, 18, 'Municipal board', '63,388', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(493, 'Masaurhi', '', 1, 10, 'Nagar Parishad', '63,248', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(494, 'Visnagar', '', 1, 4, 'Municipality', '63,073', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(495, 'Vrindavan', '', 1, 9, 'Municipal board', '63,005', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(496, 'Nokha', '', 1, 8, 'Municipality', '62,699', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(497, 'Nagari', '', 1, 12, 'Municipality', '62,253', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(498, 'Narwana', '', 1, 14, 'Municipal Council', '62,090', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(499, 'Ramanathapuram', '', 1, 6, 'Municipality', '62,050', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(500, 'Ujhani', '', 1, 9, 'Nagar Palika Parishad.', '62,039', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(501, 'Samastipur', '', 1, 10, 'Urban Agglomeration', '61,998', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(502, 'Laharpur', '', 1, 9, 'Nagar Palika Parishad', '61,990', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(503, 'Sangamner', '', 1, 1, 'Municipal Council', '61,958', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(504, 'Nimbahera', '', 1, 8, 'Municipality', '61,949', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(505, 'Siddipet', '', 1, 5, 'Municipality', '61,809', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(506, 'Suri', '', 1, 7, 'Municipality', '61,806', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(507, 'Diphu', '', 1, 18, 'Town Committee / Town Area Committee', '61,797', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(508, 'Jhargram', '', 1, 7, 'Municipality', '61,712', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(509, 'Shirpur-Warwade', '', 1, 1, 'Municipal Council', '61,694', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(510, 'Tilhar', '', 1, 9, 'Nagar Palika Parishad.', '61,444', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(511, 'Sindhnur', '', 1, 3, 'Town Municipal Council', '61,262', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(512, 'Udumalaipettai', '', 1, 6, 'Municipality', '61,133', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(513, 'Malkapur', '', 1, 1, 'Municipal Council', '61,012', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(514, 'Wanaparthy', '', 1, 5, 'Municipality', '60,949', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(515, 'Gudur', '', 1, 12, 'Municipality', '60,625', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(516, 'Kendujhar', '', 1, 20, 'Municipality', '60,590', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(517, 'Mandla', '', 1, 11, 'Urban Agglomeration', '60,542', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(518, 'Mandi', '', 1, 29, 'Municipal Council', '60,387', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(519, 'Nedumangad', '', 1, 21, 'Municipality', '60,161', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(520, 'North Lakhimpur', '', 1, 18, 'Municipal board', '59,814', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(521, 'Vinukonda', '', 1, 12, 'Municipality', '59,725', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(522, 'Tiptur', '', 1, 3, 'City Municipal Council', '59,543', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(523, 'Gobichettipalayam', '', 1, 6, 'Municipality', '59,523', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(524, 'Sunabeda', '', 1, 20, 'Notified area committee / Notified Area Council', '58,884', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(525, 'Wani', '', 1, 1, 'Municipal Council', '58,840', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(526, 'Upleta', '', 1, 4, 'Municipality', '58,775', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(527, 'Narasapuram', '', 1, 12, 'Municipality', '58,770', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(528, 'Nuzvid', '', 1, 12, 'Municipality', '58,590', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(529, 'Tezpur', '', 1, 18, 'Municipal board.', '58,559', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(530, 'Una', '', 1, 4, 'Municipality', '58,528', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(531, 'Markapur', '', 1, 12, 'Municipality', '58,462', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(532, 'Sheopur', '', 1, 11, 'Urban Agglomeration', '58,342', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(533, 'Thiruvarur', '', 1, 6, 'Municipality', '58,301', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(534, 'Sidhpur', '', 1, 4, 'Urban Agglomeration', '58,194', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(535, 'Sahaswan', '', 1, 9, 'Municipal board', '58,184', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(536, 'Suratgarh', '', 1, 8, 'Municipality', '58,119', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(537, 'Shajapur', '', 1, 11, 'Urban Agglomeration', '57,818', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(538, 'Rayagada', '', 1, 20, 'Municipality', '57,759', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(539, 'Lonavla', '', 1, 1, 'Municipal Council', '57,698', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(540, 'Ponnur', '', 1, 12, 'Municipality', '57,640', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(541, 'Kagaznagar', '', 1, 5, 'Municipality', '57,583', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(542, 'Gadwal', '', 1, 5, 'Municipality', '57,569', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(543, 'Bhatapara', '', 1, 17, 'Municipality', '57,537', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(544, 'Kandukur', '', 1, 12, 'Municipality', '57,246', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(545, 'Sangareddy', '', 1, 5, 'Municipality', '57,113', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(546, 'Unjha', '', 1, 4, 'Municipality', '57,108', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(547, 'Lunglei', '', 1, 26, 'Notified Town', '57,011', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(548, 'Karimganj', '', 1, 18, 'Municipal board', '56,854', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(549, 'Kannur', '', 1, 21, 'Municipality', '56,823', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(550, 'Bobbili', '', 1, 12, 'Municipality', '56,819', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(551, 'Mokameh', '', 1, 10, 'Municipality', '56,615', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(552, 'Talegaon Dabhade', '', 1, 1, 'Municipal Council', '56,435', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(553, 'Anjangaon', '', 1, 1, 'Municipal Council', '56,380', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(554, 'Mangrol', '', 1, 4, 'Urban Agglomeration', '56,320', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(555, 'Sunam', '', 1, 13, 'Urban Agglomeration', '56,251', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(556, 'Gangarampur', '', 1, 7, 'Municipality', '56,175', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(557, 'Thiruvallur', '', 1, 6, 'Municipality', '56,074', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(558, 'Tirur', '', 1, 21, 'Municipality', '56,058', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(559, 'Rath', '', 1, 9, 'Municipal board', '55,950', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(560, 'Jatani', '', 1, 20, 'Municipality', '55,925', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(561, 'Viramgam', '', 1, 4, 'Municipality', '55,821', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(562, 'Rajsamand', '', 1, 8, 'Municipality', '55,687', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(563, 'Yanam', '', 1, 23, 'Municipality', '55,626', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(564, 'Kottayam', '', 1, 21, 'Municipality', '55,374', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(565, 'Panruti', '', 1, 6, 'Municipality', '55,346', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(566, 'Dhuri', '', 1, 13, 'Municipal Council', '55,225', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(567, 'Namakkal', '', 1, 6, 'Municipality', '55,145', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(568, 'Kasaragod', '', 1, 21, 'Municipality', '54,172', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(569, 'Modasa', '', 1, 4, 'Municipality', '54,135', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(570, 'Rayadurg', '', 1, 12, 'Municipality', '54,125', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(571, 'Supaul', '', 1, 10, 'Municipality', '54,085', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(572, 'Kunnamkulam', '', 1, 21, 'Municipality', '54,071', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(573, 'Umred', '', 1, 1, 'Municipal Council', '53,971', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(574, 'Bellampalle', '', 1, 5, 'Municipality', '53,958', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(575, 'Sibsagar', '', 1, 18, 'Municipal board', '53,854', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(576, 'Mandi Dabwali', '', 1, 14, 'Municipal Committee', '53,811', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(577, 'Ottappalam', '', 1, 21, 'Municipality', '53,792', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(578, 'Dumraon', '', 1, 10, 'Notified area committee / Notified Area Council', '53,618', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(579, 'Samalkot', '', 1, 12, 'Municipality', '53,602', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(580, 'Jaggaiahpet', '', 1, 12, 'Municipality', '53,530', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(581, 'Goalpara', '', 1, 18, 'Municipal board', '53,430', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(582, 'Tuni', '', 1, 12, 'Municipality', '53,425', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(583, 'Lachhmangarh', '', 1, 8, 'Municipality', '53,392', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(584, 'Bhongir', '', 1, 5, 'Municipality', '53,339', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(585, 'Amalapuram', '', 1, 12, 'Municipality', '53,231', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(586, 'Firozpur Cantt.', '', 1, 13, 'Cantonment Board / Cantonment', '53,199', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(587, 'Vikarabad', '', 1, 5, 'Municipality', '53,143', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(588, 'Thiruvalla', '', 1, 21, 'Municipality', '52,883', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(589, 'Sherkot', '', 1, 9, 'Municipal board', '52,880', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(590, 'Palghar', '', 1, 1, 'Municipal Council', '52,677', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(591, 'Shegaon', '', 1, 1, 'Municipal Council', '52,423', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(592, 'Jangaon', '', 1, 5, 'Municipality', '52,394', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(593, 'Bheemunipatnam', '', 1, 12, 'Municipality', '52,110', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(594, 'Panna', '', 1, 11, 'Urban Agglomeration', '52,057', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(595, 'Thodupuzha', '', 1, 21, 'Municipality', '52,045', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(596, 'KathUrban Agglomeration', '', 1, 15, 'Municipal Council', '51,991', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(597, 'Palitana', '', 1, 4, 'Municipality', '51,944', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(598, 'Arwal', '', 1, 10, 'Nagar Panchayat.', '51,849', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(599, 'Venkatagiri', '', 1, 12, 'Municipality', '51,708', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(600, 'Kalpi', '', 1, 9, 'Nagar Palika Parishad', '51,670', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(601, 'Rajgarh (Churu)', '', 1, 8, 'Urban Agglomeration', '51,640', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(602, 'Sattenapalle', '', 1, 12, 'Municipality', '51,404', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(603, 'Arsikere', '', 1, 3, 'Town Municipal Council', '51,336', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(604, 'Ozar', '', 1, 1, 'Census town', '51,297', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(605, 'Thirumangalam', '', 1, 6, 'Municipality', '51,194', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(606, 'Petlad', '', 1, 4, 'Municipality', '51,147', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(607, 'Nasirabad', '', 1, 8, 'Cantonment Board / Cantonment', '50,804', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(608, 'Phaltan', '', 1, 1, 'Municipal Council', '50,800', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(609, 'Rampurhat', '', 1, 7, 'Municipality', '50,613', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(610, 'Nanjangud', '', 1, 3, 'Town Municipal Council', '50,598', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(611, 'Forbesganj', '', 1, 10, 'Municipality', '50,475', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(612, 'Tundla', '', 1, 9, 'Nagar Palika Parishad', '50,423', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(613, 'BhabUrban Agglomeration', '', 1, 10, 'Nagar Panchayat', '50,179', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(614, 'Sagara', '', 1, 3, 'Town Municipal Council', '50,131', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(615, 'Pithapuram', '', 1, 12, 'Municipality', '50,103', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(616, 'Sira', '', 1, 3, 'Town Municipal Council', '50,088', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(617, 'Bhadrachalam', '', 1, 5, 'Census town', '50,087', 'Class II', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(618, 'Charkhi Dadri', '', 1, 14, 'Nagar Palika Parishad', '49,985', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(619, 'Chatra', '', 1, 16, 'Municipality', '49,985', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(620, 'Palasa Kasibugga', '', 1, 12, 'Nagar Panchayat', '49,899', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(621, 'Nohar', '', 1, 8, 'Municipality', '49,835', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(622, 'Yevla', '', 1, 1, 'Municipal Council', '49,826', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(623, 'Sirhind Fatehgarh Sahib', '', 1, 13, 'Municipal Council', '49,825', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(624, 'Bhainsa', '', 1, 5, 'Municipality', '49,764', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(625, 'Parvathipuram', '', 1, 12, 'Municipality', '49,714', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(626, 'Shahade', '', 1, 1, 'Municipal Council', '49,696', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(627, 'Chalakudy', '', 1, 21, 'Municipality', '49,525', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(628, 'Narkatiaganj', '', 1, 10, 'Nagar Parishad', '49,507', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(629, 'Kapadvanj', '', 1, 4, 'Municipality', '49,308', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(630, 'Macherla', '', 1, 12, 'Municipality', '49,221', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(631, 'Raghogarh-Vijaypur', '', 1, 11, 'Municipality', '49,173', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(632, 'Rupnagar', '', 1, 13, 'Municipal Council', '49,159', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(633, 'Naugachhia', '', 1, 10, 'Nagar Panchayat', '49,069', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(634, 'Sendhwa', '', 1, 11, 'Municipality', '48,941', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(635, 'Byasanagar', '', 1, 20, 'Municipality', '48,911', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(636, 'Sandila', '', 1, 9, 'Municipal board', '48,899', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(637, 'Gooty', '', 1, 12, 'Census town', '48,658', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(638, 'Salur', '', 1, 12, 'Municipality', '48,354', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(639, 'Nanpara', '', 1, 9, 'Nagar Palika Parishad', '48,337', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(640, 'Sardhana', '', 1, 9, 'Municipal board', '48,314', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(641, 'Vita', '', 1, 1, 'Municipal Council', '48,289', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(642, 'Gumia', '', 1, 16, 'Census town', '48,141', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(643, 'Puttur', '', 1, 3, 'Town Municipal Council', '48,070', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(644, 'Jalandhar Cantt.', '', 1, 13, 'Cantonment Board / Cantonment', '47,845', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(645, 'Nehtaur', '', 1, 9, 'Nagar Palika Parishad', '47,834', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(646, 'Changanassery', '', 1, 21, 'Municipality', '47,685', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(647, 'Mandapeta', '', 1, 12, 'Municipality', '47,638', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(648, 'Dumka', '', 1, 16, 'Notified area committee / Notified Area Council', '47,584', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(649, 'Seohara', '', 1, 9, 'Urban Agglomeration', '47,575', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(650, 'Umarkhed', '', 1, 1, 'Municipal Council', '47,458', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(651, 'Madhupur', '', 1, 16, 'Municipality', '47,326', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(652, 'Vikramasingapuram', '', 1, 6, 'Municipality', '47,241', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(653, 'Punalur', '', 1, 21, 'Municipality', '47,235', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(654, 'Kendrapara', '', 1, 20, 'Municipality', '47,006', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(655, 'Sihor', '', 1, 4, 'Municipality', '46,960', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(656, 'Nellikuppam', '', 1, 6, 'Municipality', '46,678', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(657, 'Samana', '', 1, 13, 'Municipal Council', '46,592', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(658, 'Warora', '', 1, 1, 'Municipal Council', '46,532', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(659, 'Nilambur', '', 1, 21, 'Census town', '46,366', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(660, 'Rasipuram', '', 1, 6, 'Municipality', '46,330', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(661, 'Ramnagar', '', 1, 22, 'Municipal board', '46,205', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(662, 'Jammalamadugu', '', 1, 12, 'Nagar Panchayat', '46,069', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(663, 'Nawanshahr', '', 1, 13, 'Municipal Council', '46,024', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(664, 'Thoubal', '', 1, 28, 'Municipal Council', '45,947', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(665, 'Athni', '', 1, 3, 'Town Municipal Council', '45,858', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(666, 'Cherthala', '', 1, 21, 'Municipality', '45,827', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(667, 'Sidhi', '', 1, 11, 'Municipality', '45,700', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(668, 'Farooqnagar', '', 1, 5, 'Census town.', '45,675', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(669, 'Peddapuram', '', 1, 12, 'Municipality', '45,520', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(670, 'Chirkunda', '', 1, 16, 'Nagar Panchayat', '45,508', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(671, 'Pachora', '', 1, 1, 'Municipal Council', '45,333', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(672, 'Madhepura', '', 1, 10, 'Municipality', '45,031', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(673, 'Pithoragarh', '', 1, 22, 'Municipal board', '44,964', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(674, 'Tumsar', '', 1, 1, 'Municipal Council', '44,869', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(675, 'Phalodi', '', 1, 8, 'Urban Agglomeration', '44,868', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(676, 'Tiruttani', '', 1, 6, 'Municipality', '44,781', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(677, 'Rampura Phul', '', 1, 13, 'Urban Agglomeration', '44,665', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(678, 'Perinthalmanna', '', 1, 21, 'Municipality', '44,612', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(679, 'Padrauna', '', 1, 9, 'Municipal board', '44,383', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(680, 'Pipariya', '', 1, 11, 'Urban Agglomeration', '44,378', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(681, 'Dalli-Rajhara', '', 1, 17, 'Municipality', '44,363', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(682, 'Punganur', '', 1, 12, 'Nagar Panchayat', '44,314', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(683, 'Mattannur', '', 1, 21, 'Municipality', '44,313', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(684, 'Mathura', '', 1, 9, 'Municipality', '44,313', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(685, 'Thakurdwara', '', 1, 9, 'Nagar Palika Parishad.', '44,255', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(686, 'Nandivaram-Guduvancheri', '', 1, 6, 'Town Panchayat', '44,098', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(687, 'Mulbagal', '', 1, 3, 'Town Municipal Council', '44,033', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(688, 'Manjlegaon', '', 1, 1, 'Municipal Council', '44,029', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(689, 'Wankaner', '', 1, 4, 'Municipality', '43,881', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(690, 'Sillod', '', 1, 1, 'Municipal Council', '43,867', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(691, 'Nidadavole', '', 1, 12, 'Municipality', '43,809', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(692, 'Surapura', '', 1, 3, 'Town Municipal Council', '43,622', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(693, 'Rajagangapur', '', 1, 20, 'Municipality', '43,594', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(694, 'Sheikhpura', '', 1, 10, 'Municipality', '43,113', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(695, 'Parlakhemundi', '', 1, 20, 'Municipality', '43,097', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(696, 'Kalimpong', '', 1, 7, 'Municipality', '42,998', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(697, 'Siruguppa', '', 1, 3, 'Town Panchayat', '42,919', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(698, 'Arvi', '', 1, 1, 'Municipal Council', '42,822', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(699, 'Limbdi', '', 1, 4, 'Municipality', '42,769', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(700, 'Barpeta', '', 1, 18, 'Municipal board', '42,649', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(701, 'Manglaur', '', 1, 22, 'Municipal board', '42,584', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(702, 'Repalle', '', 1, 12, 'Municipality', '42,539', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(703, 'Mudhol', '', 1, 3, 'Town Municipal Council', '42,461', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(704, 'Shujalpur', '', 1, 11, 'Municipality', '42,461', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(705, 'Mandvi', '', 1, 4, 'Municipality', '42,355', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(706, 'Thangadh', '', 1, 4, 'Municipality', '42,351', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(707, 'Sironj', '', 1, 11, 'Municipality', '42,179', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(708, 'Nandura', '', 1, 1, 'Municipal Council', '42,167', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(709, 'Shoranur', '', 1, 21, 'Municipality', '42,029', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(710, 'Nathdwara', '', 1, 8, 'Municipality', '42,016', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(711, 'Periyakulam', '', 1, 6, 'Municipality', '42,012', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(712, 'Sultanganj', '', 1, 10, 'Notified area', '41,958', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(713, 'Medak', '', 1, 5, 'Nagar Panchayat', '41,945', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(714, 'Narayanpet', '', 1, 5, 'Nagar Panchayat', '41,752', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(715, 'Raxaul Bazar', '', 1, 10, 'Municipality', '41,610', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(716, 'Rajauri', '', 1, 15, 'Notified area committee / Notified Area Council', '41,552', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(717, 'Pernampattu', '', 1, 6, 'Town Panchayat', '41,499', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(718, 'Nainital', '', 1, 22, 'Nagar Palika Parishad', '41,377', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(719, 'Ramachandrapuram', '', 1, 12, 'Municipality', '41,370', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(720, 'Vaijapur', '', 1, 1, 'Municipal Council', '41,296', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(721, 'Nangal', '', 1, 13, 'Municipal Council', '41,172', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(722, 'Sidlaghatta', '', 1, 3, 'Town Municipal Council', '41,098', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(723, 'Punch', '', 1, 15, 'Municipal board', '40,987', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(724, 'Pandhurna', '', 1, 11, 'Municipality', '40,931', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(725, 'Wadgaon Road', '', 1, 1, 'Census town.', '40,884', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(726, 'Talcher', '', 1, 20, 'Municipality', '40,841', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(727, 'Varkala', '', 1, 21, 'Municipality', '40,728', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(728, 'Pilani', '', 1, 8, 'Urban Agglomeration', '40,590', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(729, 'Nowgong', '', 1, 11, 'Municipality', '40,580', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(730, 'Naila Janjgir', '', 1, 17, 'Municipality', '40,561', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(731, 'Mapusa', '', 1, 31, 'Municipal Council', '40,487', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(732, 'Vellakoil', '', 1, 6, 'Municipality', '40,359', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(733, 'Merta City', '', 1, 8, 'Municipality', '40,252', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(734, 'Sivaganga', '', 1, 6, 'Urban Agglomeration', '40,220', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(735, 'Mandideep', '', 1, 11, 'Municipality', '39,859', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(736, 'Sailu', '', 1, 1, 'Municipal Council', '39,851', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(737, 'Vyara', '', 1, 4, 'Municipality', '39,789', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(738, 'Kovvur', '', 1, 12, 'Municipality', '39,667', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(739, 'Vadalur', '', 1, 6, 'Town Panchayat', '39,514', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(740, 'Nawabganj', '', 1, 9, 'Nagar Palika Parishad', '39,241', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(741, 'Padra', '', 1, 4, 'Urban Agglomeration', '39,205', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(742, 'Sainthia', '', 1, 7, 'Municipality', '39,145', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(743, 'Siana', '', 1, 9, 'Municipal board', '38,999', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(744, 'Shahpur', '', 1, 3, 'Town Municipal Council', '38,907', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(745, 'Sojat', '', 1, 8, 'Municipality', '38,883', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(746, 'Noorpur', '', 1, 9, 'Nagar Palika Parishad', '38,806', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(747, 'Paravoor', '', 1, 21, 'Municipality', '38,652', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(748, 'Murtijapur', '', 1, 1, 'Municipal Council', '38,554', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(749, 'Ramnagar', '', 1, 10, 'Notified area', '38,554', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(750, 'Sundargarh', '', 1, 20, 'Municipality', '38,421', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(751, 'Taki', '', 1, 7, 'Municipality', '38,263', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(752, 'Saundatti-Yellamma', '', 1, 3, 'Town Municipal Council', '38,155', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(753, 'Pathanamthitta', '', 1, 21, 'Municipality', '38,009', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(754, 'Wadi', '', 1, 3, 'Town Municipal Council', '37,988', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(755, 'Rameshwaram', '', 1, 6, 'Town Panchayat', '37,968', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(756, 'Tasgaon', '', 1, 1, 'Municipal Council', '37,945', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(757, 'Sikandra Rao', '', 1, 9, 'Municipal board', '37,938', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(758, 'Sihora', '', 1, 11, 'Municipality', '37,870', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(759, 'Tiruvethipuram', '', 1, 6, 'Municipality', '37,802', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(760, 'Tiruvuru', '', 1, 12, 'Municipality', '37,802', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(761, 'Mehkar', '', 1, 1, 'Municipal Council', '37,715', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(762, 'Peringathur', '', 1, 21, 'Census town', '37,699', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(763, 'Perambalur', '', 1, 6, 'Town Panchayat', '37,631', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(764, 'Manvi', '', 1, 3, 'Town Municipal Council', '37,613', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(765, 'Zunheboto', '', 1, 30, 'Town Committee / Town Area Committee', '37,447', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(766, 'Mahnar Bazar', '', 1, 10, 'Municipality', '37,370', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(767, 'Attingal', '', 1, 21, 'Municipality', '37,346', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(768, 'Shahbad', '', 1, 14, 'Municipal Committee', '37,289', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(769, 'Puranpur', '', 1, 9, 'Municipal board', '37,233', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(770, 'Nelamangala', '', 1, 3, 'Town Municipal Council', '37,232', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(771, 'Nakodar', '', 1, 13, 'Municipal Council', '36,973', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(772, 'Lunawada', '', 1, 4, 'Municipality', '36,954', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(773, 'Murshidabad', '', 1, 7, 'Municipality', '36,947', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(774, 'Mahe', '', 1, 23, 'Municipality', '36,828', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(775, 'Lanka', '', 1, 18, 'Municipal board', '36,805', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(776, 'Rudauli', '', 1, 9, 'Municipal board', '36,776', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(777, 'Tuensang', '', 1, 30, 'Town Committee / Town Area Committee', '36,774', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(778, 'Lakshmeshwar', '', 1, 3, 'Town Municipal Council', '36,754', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(779, 'Zira', '', 1, 13, 'Municipal Council', '36,732', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(780, 'Yawal', '', 1, 1, 'Municipal Council', '36,706', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(781, 'Thana Bhawan', '', 1, 9, 'Nagar Panchayat.', '36,669', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(782, 'Ramdurg', '', 1, 3, 'Urban Agglomeration', '36,649', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(783, 'Pulgaon', '', 1, 1, 'Municipal Council', '36,522', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(784, 'Sadasivpet', '', 1, 5, 'Municipality', '36,334', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(785, 'Nargund', '', 1, 3, 'Town Municipal Council', '36,291', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(786, 'Neem-Ka-Thana', '', 1, 8, 'Municipality', '36,231', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(787, 'Memari', '', 1, 7, 'Municipality', '36,207', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(788, 'Nilanga', '', 1, 1, 'Municipal Council', '36,172', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(789, 'Naharlagun', '', 1, 33, 'Notified Town', '36,158', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(790, 'Pakaur', '', 1, 16, 'Municipality', '36,029', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(791, 'Wai', '', 1, 1, 'Municipal Council', '36,025', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(792, 'Tarikere', '', 1, 3, 'Town Municipal Council', '35,942', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(793, 'Malavalli', '', 1, 3, 'Town Municipal Council', '35,851', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(794, 'Raisen', '', 1, 11, 'Municipality', '35,702', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(795, 'Lahar', '', 1, 11, 'Nagar Panchayat', '35,674', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(796, 'Uravakonda', '', 1, 12, 'Census town', '35,565', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(797, 'Savanur', '', 1, 3, 'Town Municipal Council', '35,563', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(798, 'Sirohi', '', 1, 8, 'Municipality', '35,544', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(799, 'Udhampur', '', 1, 15, 'Urban Agglomeration', '35,507', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(800, 'Umarga', '', 1, 1, 'Municipal Council', '35,477', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(801, 'Pratapgarh', '', 1, 8, 'Municipality', '35,422', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(802, 'Lingsugur', '', 1, 3, 'Town Municipal Council', '35,411', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(803, 'Usilampatti', '', 1, 6, 'Municipality', '35,219', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(804, 'Palia Kalan', '', 1, 9, 'Municipal board', '35,029', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(805, 'Wokha', '', 1, 30, 'Town Committee / Town Area Committee.', '35,004', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(806, 'Rajpipla', '', 1, 4, 'Municipality', '34,923', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(807, 'Vijayapura', '', 1, 3, 'Town Municipal Council', '34,866', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(808, 'Rawatbhata', '', 1, 8, 'Municipality', '34,690', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(809, 'Sangaria', '', 1, 8, 'Municipality', '34,537', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(810, 'Paithan', '', 1, 1, 'Municipal Council', '34,518', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(811, 'Rahuri', '', 1, 1, 'Municipal Council', '34,476', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(812, 'Patti', '', 1, 13, 'Municipal Council', '34,444', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(813, 'Zaidpur', '', 1, 9, 'Nagar Panchayat', '34,443', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(814, 'Lalsot', '', 1, 8, 'Municipality', '34,363', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(815, 'Maihar', '', 1, 11, 'Municipality', '34,342', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(816, 'Vedaranyam', '', 1, 6, 'Municipality', '34,266', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(817, 'Nawapur', '', 1, 1, 'Municipal Council', '34,207', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(818, 'Solan', '', 1, 29, 'Municipal Council', '34,206', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(819, 'Vapi', '', 1, 4, 'Industrial Notified Area', '34,162', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(820, 'Sanawad', '', 1, 11, 'Municipality', '34,114', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(821, 'Warisaliganj', '', 1, 10, 'Nagar Panchayat.', '34,056', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(822, 'Revelganj', '', 1, 10, 'Municipality', '34,042', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(823, 'Sabalgarh', '', 1, 11, 'Urban Agglomeration', '34,039', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(824, 'Tuljapur', '', 1, 1, 'Municipal Council', '34,011', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(825, 'Simdega', '', 1, 16, 'Notified area', '33,981', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(826, 'Musabani', '', 1, 16, 'Census town', '33,980', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(827, 'Kodungallur', '', 1, 21, 'Municipality', '33,935', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(828, 'Phulabani', '', 1, 20, 'Notified area committee / Notified Area Council', '33,890', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(829, 'Umreth', '', 1, 4, 'Municipality', '33,762', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(830, 'Narsipatnam', '', 1, 12, 'Census town', '33,757', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(831, 'Nautanwa', '', 1, 9, 'Nagar Palika Parishad', '33,753', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(832, 'Rajgir', '', 1, 10, 'Notified area', '33,738', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(833, 'Yellandu', '', 1, 5, 'Municipality', '33,732', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(834, 'Sathyamangalam', '', 1, 6, 'Municipality', '33,722', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(835, 'Pilibanga', '', 1, 8, 'Municipality', '33,608', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(836, 'Morshi', '', 1, 1, 'Municipal Council', '33,607', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(837, 'Pehowa', '', 1, 14, 'Municipal Committee', '33,564', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(838, 'Sonepur', '', 1, 10, 'Notified area', '33,490', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `cities` (`id`, `city_name`, `short_name`, `country_id`, `state_id`, `type`, `population`, `population_class`, `is_active`, `created`, `modified`) VALUES
(839, 'Pappinisseri', '', 1, 21, 'Census town', '33,273', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(840, 'Zamania', '', 1, 9, 'Nagar Panchayat', '33,243', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(841, 'Mihijam', '', 1, 16, 'Notified area', '33,236', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(842, 'Purna', '', 1, 1, 'Municipal Council', '33,225', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(843, 'Puliyankudi', '', 1, 6, 'Municipality', '33,187', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(844, 'Shikarpur, Bulandshahr', '', 1, 9, 'Municipal board', '33,187', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(845, 'Umaria', '', 1, 11, 'Municipality', '33,114', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(846, 'Porsa', '', 1, 11, 'Municipality', '33,103', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(847, 'Naugawan Sadat', '', 1, 9, 'Nagar Panchayat', '32,954', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(848, 'Fatehpur Sikri', '', 1, 9, 'Municipal board.', '32,905', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(849, 'Manuguru', '', 1, 5, 'Census town', '32,893', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(850, 'Udaipur', '', 1, 24, 'Municipal Council', '32,758', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(851, 'Pipar City', '', 1, 8, 'Municipality', '32,735', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(852, 'Pattamundai', '', 1, 20, 'Notified area committee / Notified Area Council', '32,730', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(853, 'Nanjikottai', '', 1, 6, 'Census town', '32,689', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(854, 'Taranagar', '', 1, 8, 'Municipality', '32,640', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(855, 'Yerraguntla', '', 1, 12, 'Census town', '32,574', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(856, 'Satana', '', 1, 1, 'Municipal Council', '32,561', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(857, 'Sherghati', '', 1, 10, 'Notified area', '32,526', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(858, 'Sankeshwara', '', 1, 3, 'Town Municipal Council', '32,511', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(859, 'Madikeri', '', 1, 3, 'Town Municipal Council', '32,496', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(860, 'Thuraiyur', '', 1, 6, 'Municipality', '32,439', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(861, 'Sanand', '', 1, 4, 'Municipality', '32,417', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(862, 'Rajula', '', 1, 4, 'Municipality', '32,395', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(863, 'Kyathampalle', '', 1, 5, 'Census town', '32,385', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(864, 'Shahabad, Rampur', '', 1, 9, 'Nagar Panchayat', '32,370', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(865, 'Tilda Newra', '', 1, 17, 'Municipality', '32,331', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(866, 'Narsinghgarh', '', 1, 11, 'Municipality', '32,329', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(867, 'Chittur-Thathamangalam', '', 1, 21, 'Municipality', '32,298', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(868, 'Malaj Khand', '', 1, 11, 'Municipality', '32,296', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(869, 'Sarangpur', '', 1, 11, 'Municipality', '32,294', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(870, 'Robertsganj', '', 1, 9, 'Municipal board', '32,243', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(871, 'Sirkali', '', 1, 6, 'Municipality', '32,228', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(872, 'Radhanpur', '', 1, 4, 'Municipality', '32,191', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(873, 'Tiruchendur', '', 1, 6, 'Town Panchayat', '32,171', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(874, 'Utraula', '', 1, 9, 'Nagar Palika Parishad.', '32,145', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(875, 'Patratu', '', 1, 16, 'Census town', '32,134', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(876, 'Vijainagar, Ajmer', '', 1, 8, 'Municipality', '32,124', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(877, 'Periyasemur', '', 1, 6, 'Town Panchayat', '32,024', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(878, 'Pathri', '', 1, 1, 'Municipal Council', '32,001', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(879, 'Sadabad', '', 1, 9, 'Nagar Panchayat', '31,742', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(880, 'Talikota', '', 1, 3, 'Town Municipal Council', '31,693', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(881, 'Sinnar', '', 1, 1, 'Municipal Council', '31,630', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(882, 'Mungeli', '', 1, 17, 'Urban Agglomeration', '31,613', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(883, 'Sedam', '', 1, 3, 'Town Municipal Council', '31,539', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(884, 'Shikaripur', '', 1, 3, 'Town Municipal Council', '31,516', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(885, 'Sumerpur', '', 1, 8, 'Municipality', '31,482', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(886, 'Sattur', '', 1, 6, 'Municipality', '31,443', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(887, 'Sugauli', '', 1, 10, 'Notified area', '31,432', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(888, 'Lumding', '', 1, 18, 'Municipal board', '31,347', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(889, 'Vandavasi', '', 1, 6, 'Municipality', '31,320', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(890, 'Titlagarh', '', 1, 20, 'Notified area committee / Notified Area Council ', '31,258', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(891, 'Uchgaon', '', 1, 1, 'Census town', '31,238', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(892, 'Mokokchung', '', 1, 30, 'Town Committee / Town Area Committee', '31,214', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(893, 'Paschim Punropara', '', 1, 7, 'Census town', '31,198', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(894, 'Sagwara', '', 1, 8, 'Municipality', '31,127', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(895, 'Ramganj Mandi', '', 1, 8, 'Municipality', '30,973', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(896, 'Tarakeswar', '', 1, 7, 'Municipality', '30,947', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(897, 'Mahalingapura', '', 1, 3, 'Town Municipal Council', '30,858', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(898, 'Dharmanagar', '', 1, 24, 'Municipal Council', '30,785', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(899, 'Mahemdabad', '', 1, 4, 'Municipality', '30,768', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(900, 'Manendragarh', '', 1, 17, 'Municipality', '30,758', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(901, 'Uran', '', 1, 1, 'Municipal Council', '30,439', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(902, 'Tharamangalam', '', 1, 6, 'Town Panchayat', '30,222', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(903, 'Tirukkoyilur', '', 1, 6, 'Town Panchayat', '30,212', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(904, 'Pen', '', 1, 1, 'Municipal Council', '30,201', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(905, 'Makhdumpur', '', 1, 10, 'Notified area', '30,109', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(906, 'Maner', '', 1, 10, 'Notified area', '30,082', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(907, 'Oddanchatram', '', 1, 6, 'Town Panchayat', '30,064', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(908, 'Palladam', '', 1, 6, 'Town Panchayat', '30,016', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(909, 'Mundi', '', 1, 11, 'Nagar Panchayat', '30,000', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(910, 'Nabarangapur', '', 1, 20, 'Municipality', '29,960', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(911, 'Mudalagi', '', 1, 3, 'Town Municipal Council', '29,893', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(912, 'Samalkha', '', 1, 14, 'Municipal Committee', '29,866', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(913, 'Nepanagar', '', 1, 11, 'Municipality', '29,682', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(914, 'Karjat', '', 1, 1, 'Municipal Council', '29,663', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(915, 'Ranavav', '', 1, 4, 'Urban Agglomeration', '29,645', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(916, 'Pedana', '', 1, 12, 'Nagar Panchayat', '29,613', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(917, 'Pinjore', '', 1, 14, 'Urban Agglomeration', '29,609', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(918, 'Lakheri', '', 1, 8, 'Municipality', '29,572', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(919, 'Pasan', '', 1, 11, 'Municipality', '29,565', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(920, 'Puttur', '', 1, 12, 'Census town', '29,436', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(921, 'Vadakkuvalliyur', '', 1, 6, 'Town Panchayat', '29,417', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(922, 'Tirukalukundram', '', 1, 6, 'Town Panchayat', '29,391', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(923, 'Mahidpur', '', 1, 11, 'Urban Agglomeration', '29,379', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(924, 'Mussoorie', '', 1, 22, 'Urban Agglomeration', '29,329', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(925, 'Muvattupuzha', '', 1, 21, 'Municipality', '29,246', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(926, 'Rasra', '', 1, 9, 'Municipal board', '29,238', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(927, 'Udaipurwati', '', 1, 8, 'Municipality', '29,236', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(928, 'Manwath', '', 1, 1, 'Municipal Council', '29,218', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(929, 'Adoor', '', 1, 21, 'Municipality', '29,171', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(930, 'Uthamapalayam', '', 1, 6, 'Town Panchayat', '29,050', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(931, 'Partur', '', 1, 1, 'Municipal Council', '29,012', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(932, 'Nahan', '', 1, 29, 'Municipal Council', '28,899', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(933, 'Ladwa', '', 1, 14, 'Municipal Committee', '28,887', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(934, 'Mankachar', '', 1, 18, 'Census town', '28,780', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(935, 'Nongstoin', '', 1, 27, 'Town Committee / Town Area Committee', '28,742', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(936, 'Losal', '', 1, 8, 'Municipality', '28,504', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(937, 'Sri Madhopur', '', 1, 8, 'Municipality', '28,492', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(938, 'Ramngarh', '', 1, 8, 'Municipality', '28,458', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(939, 'Mavelikkara', '', 1, 21, 'Municipality', '28,439', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(940, 'Rawatsar', '', 1, 8, 'Municipality', '28,387', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(941, 'Rajakhera', '', 1, 8, 'Municipality', '28,349', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(942, 'Lar', '', 1, 9, 'Nagar Panchayat', '28,307', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(943, 'Lal Gopalganj Nindaura', '', 1, 9, 'Nagar Panchayat', '28,288', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(944, 'Muddebihal', '', 1, 3, 'Town Municipal Council', '28,219', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(945, 'Sirsaganj', '', 1, 9, 'Urban Agglomeration', '28,212', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(946, 'Shahpura', '', 1, 8, 'Municipality', '28,174', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(947, 'Surandai', '', 1, 6, 'Town Panchayat', '28,146', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(948, 'Sangole', '', 1, 1, 'Municipal Council', '28,116', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(949, 'Pavagada', '', 1, 3, 'Town Panchayat', '28,068', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(950, 'Tharad', '', 1, 4, 'Municipality', '27,954', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(951, 'Mansa', '', 1, 4, 'Municipality', '27,922', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(952, 'Umbergaon', '', 1, 4, 'Municipality', '27,859', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(953, 'Mavoor', '', 1, 21, 'Census town', '27,845', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(954, 'Nalbari', '', 1, 18, 'Municipal board', '27,839', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(955, 'Talaja', '', 1, 4, 'Municipality', '27,822', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(956, 'Malur', '', 1, 3, 'Town Municipal Council', '27,815', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(957, 'Mangrulpir', '', 1, 1, 'Municipal Council', '27,815', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(958, 'Soro', '', 1, 20, 'Notified area committee / Notified Area Council', '27,794', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(959, 'Shahpura', '', 1, 8, 'Municipality', '27,792', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(960, 'Vadnagar', '', 1, 4, 'Municipality', '27,790', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(961, 'Raisinghnagar', '', 1, 8, 'Municipality', '27,736', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(962, 'Sindhagi', '', 1, 3, 'Town Municipal Council', '27,732', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(963, 'Sanduru', '', 1, 3, 'Town Panchayat', '27,614', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(964, 'Sohna', '', 1, 14, 'Municipal Committee', '27,570', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(965, 'Manavadar', '', 1, 4, 'Municipality', '27,563', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(966, 'Pihani', '', 1, 9, 'Municipal board', '27,545', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(967, 'Safidon', '', 1, 14, 'Municipal Committee', '27,541', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(968, 'Risod', '', 1, 1, 'Municipal Council', '27,516', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(969, 'Rosera', '', 1, 10, 'Municipality', '27,492', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(970, 'Sankari', '', 1, 6, 'Town Panchayat', '27,454', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(971, 'Malpura', '', 1, 8, 'Urban Agglomeration', '27,360', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(972, 'Sonamukhi', '', 1, 7, 'Municipality', '27,354', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(973, 'Shamsabad, Agra', '', 1, 9, 'Municipal board', '27,338', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(974, 'Nokha', '', 1, 10, 'Nagar Panchayat', '27,302', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(975, 'PandUrban Agglomeration', '', 1, 7, 'Census town', '27,161', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(976, 'Mainaguri', '', 1, 7, 'Census town', '27,106', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(977, 'Afzalpur', '', 1, 3, 'Town Panchayat', '27,088', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(978, 'Shirur', '', 1, 1, 'Municipal Council', '26,999', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(979, 'Salaya', '', 1, 4, 'Municipality', '26,875', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(980, 'Shenkottai', '', 1, 6, 'Municipality', '26,838', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(981, 'Pratapgarh', '', 1, 24, 'Census town', '26,837', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(982, 'Vadipatti', '', 1, 6, 'Town Panchayat', '26,830', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(983, 'Nagarkurnool', '', 1, 5, 'Census town', '26,801', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(984, 'Savner', '', 1, 1, 'Municipal Council', '26,712', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(985, 'Sasvad', '', 1, 1, 'Municipal Council', '26,689', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(986, 'Rudrapur', '', 1, 9, 'Nagar Panchayat', '26,683', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(987, 'Soron', '', 1, 9, 'Municipal board', '26,678', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(988, 'Sholingur', '', 1, 6, 'Town Panchayat', '26,652', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(989, 'Pandharkaoda', '', 1, 1, 'Municipal Council', '26,572', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(990, 'Perumbavoor', '', 1, 21, 'Municipality', '26,547', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(991, 'Maddur', '', 1, 3, 'Town Municipal Council', '26,521', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(992, 'Nadbai', '', 1, 8, 'Municipality', '26,411', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(993, 'Talode', '', 1, 1, 'Municipal Council', '26,363', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(994, 'Shrigonda', '', 1, 1, 'Municipal Council', '26,324', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(995, 'Madhugiri', '', 1, 3, 'Town Municipal Council', '26,304', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(996, 'Tekkalakote', '', 1, 3, 'Town Panchayat', '26,224', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(997, 'Seoni-Malwa', '', 1, 11, 'Municipality', '26,202', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(998, 'Shirdi', '', 1, 1, 'Municipal Council', '26,184', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(999, 'SUrban Agglomerationr', '', 1, 9, 'Municipal board', '26,149', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1000, 'Terdal', '', 1, 3, 'Town Municipal Council', '26,088', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1001, 'Raver', '', 1, 1, 'Municipal Council', '25,993', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1002, 'Tirupathur', '', 1, 6, 'Town Panchayat', '25,980', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1003, 'Taraori', '', 1, 14, 'Municipal Committee.', '25,944', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1004, 'Mukhed', '', 1, 1, 'Municipal Council', '25,933', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1005, 'Manachanallur', '', 1, 6, 'Town Panchayat', '25,931', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1006, 'Rehli', '', 1, 11, 'Municipality', '25,890', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1007, 'Sanchore', '', 1, 8, 'Municipality', '25,884', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1008, 'Rajura', '', 1, 1, 'Municipal Council', '25,843', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1009, 'Piro', '', 1, 10, 'Notified area', '25,811', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1010, 'Mudabidri', '', 1, 3, 'Town Municipal Council', '25,713', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1011, 'Vadgaon Kasba', '', 1, 1, 'Municipal Council', '25,651', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1012, 'Nagar', '', 1, 8, 'Municipality', '25,572', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1013, 'Vijapur', '', 1, 4, 'Municipality', '25,558', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1014, 'Viswanatham', '', 1, 6, 'Census town', '25,555', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1015, 'Polur', '', 1, 6, 'Town Panchayat', '25,505', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1016, 'Panagudi', '', 1, 6, 'Town Panchayat', '25,501', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1017, 'Manawar', '', 1, 11, 'Municipality', '25,467', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1018, 'Tehri', '', 1, 22, 'Nagar Palika Parishad.', '25,423', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1019, 'Samdhan', '', 1, 9, 'Nagar Panchayat', '25,327', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1020, 'Pardi', '', 1, 4, 'Municipality', '25,275', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1021, 'Rahatgarh', '', 1, 11, 'Nagar Panchayat', '25,215', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1022, 'Panagar', '', 1, 11, 'Municipality', '25,199', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1023, 'Uthiramerur', '', 1, 6, 'Town Panchayat', '25,194', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1024, 'Tirora', '', 1, 1, 'Municipal Council', '25,181', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1025, 'Rangia', '', 1, 18, 'Municipal board', '25,151', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1026, 'Sahjanwa', '', 1, 9, 'Nagar Panchayat', '25,107', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1027, 'Wara Seoni', '', 1, 11, 'Municipality', '25,103', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1028, 'Magadi', '', 1, 3, 'Town Municipal Council', '25,031', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1029, 'Rajgarh (Alwar)', '', 1, 8, 'Municipality', '25,009', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1030, 'Rafiganj', '', 1, 10, 'Notified area', '24,992', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1031, 'Tarana', '', 1, 11, 'Nagar Panchayat', '24,908', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1032, 'Rampur Maniharan', '', 1, 9, 'Nagar Panchayat', '24,844', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1033, 'Sheoganj', '', 1, 8, 'Municipality', '24,789', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1034, 'Raikot', '', 1, 13, 'Municipal Council', '24,769', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1035, 'Pauri', '', 1, 22, 'Municipal board', '24,743', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1036, 'Sumerpur', '', 1, 9, 'Nagar Panchayat', '24,661', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1037, 'Navalgund', '', 1, 3, 'Town Municipal Council', '24,613', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1038, 'Shahganj', '', 1, 9, 'Municipal board', '24,602', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1039, 'Marhaura', '', 1, 10, 'Notified area', '24,548', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1040, 'Tulsipur', '', 1, 9, 'Nagar Panchayat', '24,488', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1041, 'Sadri', '', 1, 8, 'Municipality', '24,413', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1042, 'Thiruthuraipoondi', '', 1, 6, 'Municipality', '24,404', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1043, 'Shiggaon', '', 1, 3, 'Town Panchayat', '24,327', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1044, 'Pallapatti', '', 1, 6, 'Census town', '24,326', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1045, 'Mahendragarh', '', 1, 14, 'Municipal Committee', '24,323', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1046, 'Sausar', '', 1, 11, 'Nagar Panchayat', '24,312', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1047, 'Ponneri', '', 1, 6, 'Town Panchayat', '24,309', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1048, 'Mahad', '', 1, 1, 'Municipal Council', '24,276', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1049, 'Lohardaga', '', 1, 16, 'Municipal Council', '24,125', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1050, 'Tirwaganj', '', 1, 9, 'Nagar Panchayat', '24,082', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1051, 'Margherita', '', 1, 18, 'Census town', '24,049', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1052, 'Sundarnagar', '', 1, 29, 'Municipal Council', '23,986', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1053, 'Rajgarh', '', 1, 11, 'Nagar Panchayat', '23,937', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1054, 'Mangaldoi', '', 1, 18, 'Municipal board', '23,920', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1055, 'Renigunta', '', 1, 12, 'Census town', '23,862', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1056, 'Longowal', '', 1, 13, 'Municipal Council', '23,851', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1057, 'Ratia', '', 1, 14, 'Municipal Committee', '23,826', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1058, 'Lalgudi', '', 1, 6, 'Town Panchayat', '23,740', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1059, 'Shrirangapattana', '', 1, 3, 'Town Municipal Council', '23,729', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1060, 'Niwari', '', 1, 11, 'Nagar Panchayat', '23,724', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1061, 'Natham', '', 1, 6, 'Town Panchayat', '23,660', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1062, 'Unnamalaikadai', '', 1, 6, 'Town Panchayat', '23,656', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1063, 'PurqUrban Agglomerationzi', '', 1, 9, 'Urban Agglomeration', '23,599', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1064, 'Shamsabad, Farrukhabad', '', 1, 9, 'Nagar Panchayat', '23,596', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1065, 'Mirganj', '', 1, 10, 'Notified area', '23,576', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1066, 'Todaraisingh', '', 1, 8, 'Municipality', '23,559', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1067, 'Warhapur', '', 1, 9, 'Nagar Panchayat.', '23,456', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1068, 'Rajam', '', 1, 12, 'Census town', '23,424', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1069, 'Urmar Tanda', '', 1, 13, 'Municipal Council', '23,419', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1070, 'Lonar', '', 1, 1, 'Municipal Council', '23,416', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1071, 'Powayan', '', 1, 9, 'Nagar Panchayat', '23,406', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1072, 'P.N.Patti', '', 1, 6, 'Town Panchayat', '23,331', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1073, 'Palampur', '', 1, 29, 'Town Panchayat', '23,331', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1074, 'Srisailam Project (Right Flank Colony) Township', '', 1, 12, 'Census town', '23,273', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1075, 'Sindagi', '', 1, 3, 'Town Municipal Council', '23,234', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1076, 'Sandi', '', 1, 9, 'Municipal board', '23,234', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1077, 'Vaikom', '', 1, 21, 'Municipality', '23,234', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1078, 'Malda', '', 1, 7, 'Municipality', '23,218', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1079, 'Tharangambadi', '', 1, 6, 'Town Panchayat', '23,191', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1080, 'Sakaleshapura', '', 1, 3, 'Town Municipal Council', '23,176', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1081, 'Lalganj', '', 1, 10, 'Notified area committee / Notified Area Council', '23,124', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1082, 'Malkangiri', '', 1, 20, 'Notified area committee / Notified Area Council', '23,114', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1083, 'Rapar', '', 1, 4, 'Municipality', '23,057', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1084, 'Mauganj', '', 1, 11, 'Nagar Panchayat', '23,024', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1085, 'Todabhim', '', 1, 8, 'Municipality', '22,977', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1086, 'Srinivaspur', '', 1, 3, 'Town Panchayat', '22,959', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1087, 'Murliganj', '', 1, 10, 'Notified area', '22,936', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1088, 'Reengus', '', 1, 8, 'Municipality', '22,932', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1089, 'Sawantwadi', '', 1, 1, 'Municipal Council', '22,901', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1090, 'Tittakudi', '', 1, 6, 'Town Panchayat', '22,894', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1091, 'Lilong', '', 1, 28, 'Nagar Panchayat', '22,888', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1092, 'Rajaldesar', '', 1, 8, 'Municipality', '22,836', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1093, 'Pathardi', '', 1, 1, 'Municipal Council', '22,827', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1094, 'Achhnera', '', 1, 9, 'Nagar Panchayat.', '22,781', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1095, 'Pacode', '', 1, 6, 'Town Panchayat', '22,781', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1096, 'Naraura', '', 1, 9, 'Nagar Panchayat', '22,775', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1097, 'Nakur', '', 1, 9, 'Nagar Palika Parishad', '22,712', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1098, 'Palai', '', 1, 21, 'Municipality', '22,640', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1099, 'Morinda, India', '', 1, 13, 'Municipal Council', '22,635', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1100, 'Manasa', '', 1, 11, 'Nagar Panchayat', '22,623', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1101, 'Nainpur', '', 1, 11, 'Municipality', '22,607', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1102, 'Sahaspur', '', 1, 9, 'Nagar Panchayat', '22,606', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1103, 'Pauni', '', 1, 1, 'Municipal Council', '22,587', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1104, 'Prithvipur', '', 1, 11, 'Nagar Panchayat', '22,535', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1105, 'Ramtek', '', 1, 1, 'Municipal Council', '22,516', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1106, 'Silapathar', '', 1, 18, 'Town Committee / Town Area Committee', '22,516', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1107, 'Songadh', '', 1, 4, 'Municipality', '22,431', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1108, 'Safipur', '', 1, 9, 'Nagar Panchayat', '22,378', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1109, 'Sohagpur', '', 1, 11, 'Nagar Panchayat', '22,339', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1110, 'Mul', '', 1, 1, 'Municipal Council', '22,330', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1111, 'Sadulshahar', '', 1, 8, 'Municipality', '22,326', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1112, 'Phillaur', '', 1, 13, 'Municipal Council', '22,302', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1113, 'Sambhar', '', 1, 8, 'Municipality', '22,293', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1114, 'Prantij', '', 1, 8, 'Municipality', '22,282', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1115, 'Nagla', '', 1, 22, 'Census town', '22,258', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1116, 'Pattran', '', 1, 13, 'Nagar Panchayat', '22,175', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1117, 'Mount Abu', '', 1, 8, 'Municipality', '22,152', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1118, 'Reoti', '', 1, 9, 'Nagar Panchayat', '22,082', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1119, 'Tenu dam-cum-Kathhara', '', 1, 16, 'Census town.', '22,080', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1120, 'Panchla', '', 1, 7, 'Census town', '22,051', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1121, 'Sitarganj', '', 1, 22, 'Municipal board', '22,027', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1122, 'Pasighat', '', 1, 33, 'Census town', '21,965', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1123, 'Motipur', '', 1, 10, 'Notified area', '21,957', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1124, 'O'' Valley', '', 1, 6, 'Town Panchayat', '21,943', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1125, 'Raghunathpur', '', 1, 7, 'Municipality', '21,932', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1126, 'Suriyampalayam', '', 1, 6, 'Town Panchayat', '21,923', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1127, 'Qadian', '', 1, 13, 'Municipal Council', '21,899', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1128, 'Rairangpur', '', 1, 20, 'Notified area committee / Notified Area Council', '21,896', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1129, 'Silvassa', '', 1, 34, 'Census town', '21,893', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1130, 'Nowrozabad (Khodargama)', '', 1, 11, 'Nagar Panchayat', '21,883', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1131, 'Mangrol', '', 1, 8, 'Municipality', '21,842', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1132, 'Soyagaon', '', 1, 1, 'Census town', '21,819', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1133, 'Sujanpur', '', 1, 13, 'Municipal Council', '21,815', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1134, 'Manihari', '', 1, 10, 'Notified area', '21,803', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1135, 'Sikanderpur', '', 1, 9, 'Nagar Panchayat', '21,783', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1136, 'Mangalvedhe', '', 1, 1, 'Municipal Council', '21,706', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1137, 'Phulera', '', 1, 8, 'Municipality', '21,643', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1138, 'Ron', '', 1, 3, 'Town Panchayat', '21,643', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1139, 'Sholavandan', '', 1, 6, 'Town Panchayat', '21,638', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1140, 'Saidpur', '', 1, 9, 'Nagar Panchayat', '21,568', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1141, 'Shamgarh', '', 1, 11, 'Nagar Panchayat', '21,507', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1142, 'Thammampatti', '', 1, 6, 'Town Panchayat', '21,503', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1143, 'Maharajpur', '', 1, 11, 'Nagar Panchayat', '21,490', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1144, 'Multai', '', 1, 11, 'Nagar Panchayat', '21,423', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1145, 'Mukerian', '', 1, 13, 'Municipal Council', '21,384', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1146, 'Sirsi', '', 1, 9, 'Nagar Panchayat', '21,373', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1147, 'Purwa', '', 1, 9, 'Nagar Panchayat', '21,271', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1148, 'Sheohar', '', 1, 10, 'Notified area', '21,262', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1149, 'Namagiripettai', '', 1, 6, 'Town Panchayat', '21,250', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1150, 'Parasi', '', 1, 9, 'Census town', '21,206', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1151, 'Lathi', '', 1, 4, 'Municipality', '21,173', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1152, 'Lalganj', '', 1, 9, 'Nagar Panchayat', '21,142', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1153, 'Narkhed', '', 1, 1, 'Municipal Council', '21,127', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1154, 'Mathabhanga', '', 1, 7, 'Municipality', '21,107', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1155, 'Shendurjana', '', 1, 1, 'Municipal Council', '21,083', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1156, 'Peravurani', '', 1, 6, 'Town Panchayat', '21,045', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1157, 'Mariani', '', 1, 18, 'Town Committee / Town Area Committee', '20,997', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1158, 'Phulpur', '', 1, 9, 'Nagar Panchayat', '20,986', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1159, 'Rania', '', 1, 14, 'Municipal Committee', '20,961', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1160, 'Pali', '', 1, 11, 'Nagar Panchayat', '20,942', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1161, 'Pachore', '', 1, 11, 'Nagar Panchayat', '20,939', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1162, 'Parangipettai', '', 1, 6, 'Town Panchayat', '20,912', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1163, 'Pudupattinam', '', 1, 6, 'Census town', '20,901', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1164, 'Panniyannur', '', 1, 21, 'Census town', '20,863', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1165, 'Maharajganj', '', 1, 10, 'Notified area', '20,860', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1166, 'Rau', '', 1, 11, 'Nagar Panchayat', '20,855', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1167, 'Monoharpur', '', 1, 7, 'Census town', '20,846', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1168, 'Mandawa', '', 1, 8, 'Municipality', '20,830', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1169, 'Marigaon', '', 1, 18, 'Town Committee / Town Area Committee', '20,811', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1170, 'Pallikonda', '', 1, 6, 'Town Panchayat', '20,771', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1171, 'Pindwara', '', 1, 8, 'Municipality', '20,765', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1172, 'Shishgarh', '', 1, 9, 'Nagar Panchayat', '20,684', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1173, 'Patur', '', 1, 1, 'Municipal Council', '20,538', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1174, 'Mayang Imphal', '', 1, 28, 'Nagar Panchayat', '20,532', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1175, 'Mhowgaon', '', 1, 11, 'Nagar Panchayat', '20,523', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1176, 'Guruvayoor', '', 1, 21, 'Municipality', '20,510', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1177, 'Mhaswad', '', 1, 1, 'Municipal Council', '20,500', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1178, 'Sahawar', '', 1, 9, 'Nagar Panchayat', '20,470', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1179, 'Sivagiri', '', 1, 6, 'Town Panchayat', '20,380', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1180, 'Mundargi', '', 1, 3, 'Town Panchayat', '20,363', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1181, 'Punjaipugalur', '', 1, 6, 'Town Panchayat', '20,309', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1182, 'Kailasahar', '', 1, 24, 'Nagar Panchayat', '20,279', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1183, 'Samthar', '', 1, 9, 'Municipal board', '20,217', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1184, 'Sakti', '', 1, 17, 'Nagar Panchayat', '20,213', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1185, 'Sadalagi', '', 1, 3, 'Town Panchayat', '20,202', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1186, 'Silao', '', 1, 10, 'Notified area', '20,177', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1187, 'Mandalgarh', '', 1, 8, 'Municipality', '20,169', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1188, 'Loha', '', 1, 1, 'Municipal Council', '20,148', 'Class III', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1189, 'Pukhrayan', '', 1, 9, 'Municipal board', '20,107', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1190, 'Padmanabhapuram', '', 1, 6, 'Municipality', '20,075', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1191, 'Belonia', '', 1, 24, 'Municipal Council', '19,996', 'Class IV', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1192, 'Saiha', '', 1, 26, 'Notified Town', '19,731', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1193, 'Srirampore', '', 1, 7, '', '19500', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1194, 'Talwara', '', 1, 13, 'Census town', '19,485', 'Class IV', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1195, 'Puthuppally', '', 1, 21, 'Municipality', '18,850', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1196, 'Khowai', '', 1, 24, 'Municipal Council', '18,526', 'Class IV', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1197, 'Vijaypur', '', 1, 11, 'Nagar Panchayat.', '16,964', 'Class IV', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1198, 'Takhatgarh', '', 1, 8, 'Municipality', '16,729', 'Class IV', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1199, 'Thirupuvanam', '', 1, 6, 'Town Panchayat', '14,989', 'Class IV', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1200, 'Adra', '', 1, 7, 'Census town.', '14,956', 'Class IV', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1201, 'Piriyapatna', '', 1, 3, 'Census town', '14,924', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1202, 'Obra', '', 1, 9, 'Nagar Panchayat', '14,786', 'Class IV', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1203, 'Adalaj', '', 1, 4, 'Census town.', '11,957', 'Class IV', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1204, 'Nandgaon', '', 1, 1, 'Nagar Panchayat', '11,517', 'Class IV', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1205, 'Barh', '', 1, 10, 'Census town', '10,803', 'Class IV', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1206, 'Chhapra', '', 1, 4, 'Census town', '10,147', 'Class IV', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1207, 'Panamattom', '', 1, 21, 'Notified area', '10,032', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1208, 'Niwai', '', 1, 9, 'Nagar Panchayat', '9,205', 'Class V', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1209, 'Bageshwar', '', 1, 22, 'Nagar Palika Parishad', '9,079', 'Class V', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1210, 'Tarbha', '', 1, 20, 'Notified area committee / Notified Area Council.', '8,334', 'Class V', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1211, 'Adyar', '', 1, 3, 'Census town.', '7,034', 'Class V', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1212, 'Narsinghgarh', '', 1, 11, 'Census town', '6,735', 'Class V', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1213, 'Warud', '', 1, 1, 'Municipal Council', '6,386', 'Class V', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1214, 'Asarganj', '', 1, 10, 'Census town.', '6,327', 'Class V', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1215, 'Sarsod', '', 1, 14, 'Gram Panchayat', '4,630', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `conveyances`
--

CREATE TABLE `conveyances` (
  `id` int(11) NOT NULL,
  `conveyance_name` varchar(255) NOT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `conveyance_pay_groups`
--

CREATE TABLE `conveyance_pay_groups` (
  `id` int(11) NOT NULL,
  `pay_group_id` int(11) NOT NULL,
  `conveyance_id` int(11) NOT NULL,
  `default_cost` float(10,2) NOT NULL,
  `type` enum('VALUE','PERCENT','','') NOT NULL DEFAULT 'PERCENT',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `short_name` varchar(255) NOT NULL,
  `currency` varchar(255) NOT NULL,
  `currency_code` char(10) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `name`, `short_name`, `currency`, `currency_code`, `is_active`, `created`, `modified`) VALUES
(1, 'India', 'IN', 'Indian Rupee', 'INR', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'New Zealand', 'NZ', 'New Zealand Dollars', 'NZD', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'Cook Islands', 'CK', 'New Zealand Dollars', 'NZD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'Niue', 'NU', 'New Zealand Dollars', 'NZD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 'Pitcairn', 'PN', 'New Zealand Dollars', 'NZD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 'Tokelau', 'TK', 'New Zealand Dollars', 'NZD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 'Australian', 'AU', 'Australian Dollars', 'AUD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(8, 'Christmas Island', 'CX', 'Australian Dollars', 'AUD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(9, 'Cocos (Keeling) Islands', 'CC', 'Australian Dollars', 'AUD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10, 'Heard and Mc Donald Islands', 'HM', 'Australian Dollars', 'AUD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(11, 'Kiribati', 'KI', 'Australian Dollars', 'AUD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(12, 'Nauru', 'NR', 'Australian Dollars', 'AUD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(13, 'Norfolk Island', 'NF', 'Australian Dollars', 'AUD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(14, 'Tuvalu', 'TV', 'Australian Dollars', 'AUD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(15, 'American Samoa', 'AS', 'Euros', 'EUR', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(16, 'Andorra', 'AD', 'Euros', 'EUR', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(17, 'Austria', 'AT', 'Euros', 'EUR', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(18, 'Belgium', 'BE', 'Euros', 'EUR', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(19, 'Finland', 'FI', 'Euros', 'EUR', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(20, 'France', 'FR', 'Euros', 'EUR', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(21, 'French Guiana', 'GF', 'Euros', 'EUR', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(22, 'French Southern Territories', 'TF', 'Euros', 'EUR', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(23, 'Germany', 'DE', 'Euros', 'EUR', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(24, 'Greece', 'GR', 'Euros', 'EUR', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(25, 'Guadeloupe', 'GP', 'Euros', 'EUR', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(26, 'Ireland', 'IE', 'Euros', 'EUR', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(27, 'Italy', 'IT', 'Euros', 'EUR', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(28, 'Luxembourg', 'LU', 'Euros', 'EUR', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(29, 'Martinique', 'MQ', 'Euros', 'EUR', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(30, 'Mayotte', 'YT', 'Euros', 'EUR', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(31, 'Monaco', 'MC', 'Euros', 'EUR', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(32, 'Netherlands', 'NL', 'Euros', 'EUR', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(33, 'Portugal', 'PT', 'Euros', 'EUR', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(34, 'Reunion', 'RE', 'Euros', 'EUR', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(35, 'Samoa', 'WS', 'Euros', 'EUR', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(36, 'San Marino', 'SM', 'Euros', 'EUR', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(37, 'Slovenia', 'SI', 'Euros', 'EUR', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(38, 'Spain', 'ES', 'Euros', 'EUR', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(39, 'Vatican City State (Holy See)', 'VA', 'Euros', 'EUR', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(40, 'South Georgia and the South Sandwich Islands', 'GS', 'Sterling', 'GBP', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(41, 'United Kingdom', 'GB', 'Sterling', 'GBP', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(42, 'Jersey', 'JE', 'Sterling', 'GBP', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(43, 'British Indian Ocean Territory', 'IO', 'USD', 'USD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(44, 'Guam', 'GU', 'USD', 'USD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(45, 'Marshall Islands', 'MH', 'USD', 'USD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(46, 'Micronesia Federated States of', 'FM', 'USD', 'USD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(47, 'Northern Mariana Islands', 'MP', 'USD', 'USD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(48, 'Palau', 'PW', 'USD', 'USD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(49, 'Puerto Rico', 'PR', 'USD', 'USD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(50, 'Turks and Caicos Islands', 'TC', 'USD', 'USD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(51, 'United States', 'US', 'USD', 'USD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(52, 'United States Minor Outlying Islands', 'UM', 'USD', 'USD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(53, 'Virgin Islands (British)', 'VG', 'USD', 'USD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(54, 'Virgin Islands (US)', 'VI', 'USD', 'USD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(55, 'Hong Kong', 'HK', 'HKD', 'HKD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(56, 'Canada', 'CA', 'Canadian Dollar', 'CAD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(57, 'Japan', 'JP', 'Japanese Yen', 'JPY', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(58, 'Afghanistan', 'AF', 'Afghani', 'AFN', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(59, 'Albania', 'AL', 'Lek', 'ALL', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(60, 'Algeria', 'DZ', 'Algerian Dinar', 'DZD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(61, 'Anguilla', 'AI', 'East Caribbean Dollar', 'XCD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(62, 'Antigua and Barbuda', 'AG', 'East Caribbean Dollar', 'XCD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(63, 'Dominica', 'DM', 'East Caribbean Dollar', 'XCD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(64, 'Grenada', 'GD', 'East Caribbean Dollar', 'XCD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(65, 'Montserrat', 'MS', 'East Caribbean Dollar', 'XCD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(66, 'Saint Kitts', 'KN', 'East Caribbean Dollar', 'XCD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(67, 'Saint Lucia', 'LC', 'East Caribbean Dollar', 'XCD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(68, 'Saint Vincent Grenadines', 'VC', 'East Caribbean Dollar', 'XCD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(69, 'Argentina', 'AR', 'Peso', 'ARS', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(70, 'Armenia', 'AM', 'Dram', 'AMD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(71, 'Aruba', 'AW', 'Netherlands Antilles Guilder', 'ANG', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(72, 'Netherlands Antilles', 'AN', 'Netherlands Antilles Guilder', 'ANG', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(73, 'Azerbaijan', 'AZ', 'Manat', 'AZN', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(74, 'Bahamas', 'BS', 'Bahamian Dollar', 'BSD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(75, 'Bahrain', 'BH', 'Bahraini Dinar', 'BHD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(76, 'Bangladesh', 'BD', 'Taka', 'BDT', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(77, 'Barbados', 'BB', 'Barbadian Dollar', 'BBD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(78, 'Belarus', 'BY', 'Belarus Ruble', 'BYR', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(79, 'Belize', 'BZ', 'Belizean Dollar', 'BZD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(80, 'Benin', 'BJ', 'CFA Franc BCEAO', 'XOF', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(81, 'Burkina Faso', 'BF', 'CFA Franc BCEAO', 'XOF', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(82, 'Guinea-Bissau', 'GW', 'CFA Franc BCEAO', 'XOF', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(83, 'Ivory Coast', 'CI', 'CFA Franc BCEAO', 'XOF', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(84, 'Mali', 'ML', 'CFA Franc BCEAO', 'XOF', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(85, 'Niger', 'NE', 'CFA Franc BCEAO', 'XOF', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(86, 'Senegal', 'SN', 'CFA Franc BCEAO', 'XOF', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(87, 'Togo', 'TG', 'CFA Franc BCEAO', 'XOF', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(88, 'Bermuda', 'BM', 'Bermudian Dollar', 'BMD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(89, 'Bhutan', 'BT', 'Indian Rupee', 'INR', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(90, 'Bolivia', 'BO', 'Boliviano', 'BOB', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(91, 'Botswana', 'BW', 'Pula', 'BWP', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(92, 'Bouvet Island', 'BV', 'Norwegian Krone', 'NOK', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(93, 'Norway', 'NO', 'Norwegian Krone', 'NOK', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(94, 'Svalbard and Jan Mayen Islands', 'SJ', 'Norwegian Krone', 'NOK', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(95, 'Brazil', 'BR', 'Brazil', 'BRL', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(96, 'Brunei Darussalam', 'BN', 'Bruneian Dollar', 'BND', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(97, 'Bulgaria', 'BG', 'Lev', 'BGN', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(98, 'Burundi', 'BI', 'Burundi Franc', 'BIF', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(99, 'Cambodia', 'KH', 'Riel', 'KHR', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(100, 'Cameroon', 'CM', 'CFA Franc BEAC', 'XAF', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(101, 'Central African Republic', 'CF', 'CFA Franc BEAC', 'XAF', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(102, 'Chad', 'TD', 'CFA Franc BEAC', 'XAF', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(103, 'Congo Republic of the Democratic', 'CG', 'CFA Franc BEAC', 'XAF', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(104, 'Equatorial Guinea', 'GQ', 'CFA Franc BEAC', 'XAF', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(105, 'Gabon', 'GA', 'CFA Franc BEAC', 'XAF', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(106, 'Cape Verde', 'CV', 'Escudo', 'CVE', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(107, 'Cayman Islands', 'KY', 'Caymanian Dollar', 'KYD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(108, 'Chile', 'CL', 'Chilean Peso', 'CLP', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(109, 'China', 'CN', 'Yuan Renminbi', 'CNY', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(110, 'Colombia', 'CO', 'Peso', 'COP', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(111, 'Comoros', 'KM', 'Comoran Franc', 'KMF', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(112, 'Congo-Brazzaville', 'CD', 'Congolese Frank', 'CDF', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(113, 'Costa Rica', 'CR', 'Costa Rican Colon', 'CRC', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(114, 'Croatia (Hrvatska)', 'HR', 'Croatian Dinar', 'HRK', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(115, 'Cuba', 'CU', 'Cuban Peso', 'CUP', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(116, 'Cyprus', 'CY', 'Cypriot Pound', 'CYP', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(117, 'Czech Republic', 'CZ', 'Koruna', 'CZK', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(118, 'Denmark', 'DK', 'Danish Krone', 'DKK', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(119, 'Faroe Islands', 'FO', 'Danish Krone', 'DKK', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(120, 'Greenland', 'GL', 'Danish Krone', 'DKK', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(121, 'Djibouti', 'DJ', 'Djiboutian Franc', 'DJF', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(122, 'Dominican Republic', 'DO', 'Dominican Peso', 'DOP', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(123, 'East Timor', 'TP', 'Indonesian Rupiah', 'IDR', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(124, 'Indonesia', 'ID', 'Indonesian Rupiah', 'IDR', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(125, 'Ecuador', 'EC', 'Sucre', 'ECS', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(126, 'Egypt', 'EG', 'Egyptian Pound', 'EGP', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(127, 'El Salvador', 'SV', 'Salvadoran Colon', 'SVC', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(128, 'Eritrea', 'ER', 'Ethiopian Birr', 'ETB', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(129, 'Ethiopia', 'ET', 'Ethiopian Birr', 'ETB', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(130, 'Estonia', 'EE', 'Estonian Kroon', 'EEK', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(131, 'Falkland Islands (Malvinas)', 'FK', 'Falkland Pound', 'FKP', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(132, 'Fiji', 'FJ', 'Fijian Dollar', 'FJD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(133, 'French Polynesia', 'PF', 'CFP Franc', 'XPF', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(134, 'New Caledonia', 'NC', 'CFP Franc', 'XPF', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(135, 'Wallis and Futuna Islands', 'WF', 'CFP Franc', 'XPF', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(136, 'Gambia', 'GM', 'Dalasi', 'GMD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(137, 'Georgia', 'GE', 'Lari', 'GEL', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(138, 'Gibraltar', 'GI', 'Gibraltar Pound', 'GIP', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(139, 'Guatemala', 'GT', 'Quetzal', 'GTQ', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(140, 'Guinea', 'GN', 'Guinean Franc', 'GNF', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(141, 'Guyana', 'GY', 'Guyanaese Dollar', 'GYD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(142, 'Haiti', 'HT', 'Gourde', 'HTG', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 'Honduras', 'HN', 'Lempira', 'HNL', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(144, 'Hungary', 'HU', 'Forint', 'HUF', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(145, 'Iceland', 'IS', 'Icelandic Krona', 'ISK', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(146, 'Iran (Islamic Republic of)', 'IR', 'Iranian Rial', 'IRR', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(147, 'Iraq', 'IQ', 'Iraqi Dinar', 'IQD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(148, 'Israel', 'IL', 'Shekel', 'ILS', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(149, 'Jamaica', 'JM', 'Jamaican Dollar', 'JMD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(150, 'Jordan', 'JO', 'Jordanian Dinar', 'JOD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(151, 'Kazakhstan', 'KZ', 'Tenge', 'KZT', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(152, 'Kenya', 'KE', 'Kenyan Shilling', 'KES', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(153, 'Korea North', 'KP', 'Won', 'KPW', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(154, 'Korea South', 'KR', 'Won', 'KRW', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(155, 'Kuwait', 'KW', 'Kuwaiti Dinar', 'KWD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(156, 'Kyrgyzstan', 'KG', 'Som', 'KGS', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(157, 'Lao People?s Democratic Republic', 'LA', 'Kip', 'LAK', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(158, 'Latvia', 'LV', 'Lat', 'LVL', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(159, 'Lebanon', 'LB', 'Lebanese Pound', 'LBP', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(160, 'Lesotho', 'LS', 'Loti', 'LSL', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(161, 'Liberia', 'LR', 'Liberian Dollar', 'LRD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(162, 'Libyan Arab Jamahiriya', 'LY', 'Libyan Dinar', 'LYD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(163, 'Liechtenstein', 'LI', 'Swiss Franc', 'CHF', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(164, 'Switzerland', 'CH', 'Swiss Franc', 'CHF', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(165, 'Lithuania', 'LT', 'Lita', 'LTL', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(166, 'Macau', 'MO', 'Pataca', 'MOP', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(167, 'Macedonia', 'MK', 'Denar', 'MKD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(168, 'Madagascar', 'MG', 'Malagasy Franc', 'MGA', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(169, 'Malawi', 'MW', 'Malawian Kwacha', 'MWK', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(170, 'Malaysia', 'MY', 'Ringgit', 'MYR', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(171, 'Maldives', 'MV', 'Rufiyaa', 'MVR', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(172, 'Malta', 'MT', 'Maltese Lira', 'MTL', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(173, 'Mauritania', 'MR', 'Ouguiya', 'MRO', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(174, 'Mauritius', 'MU', 'Mauritian Rupee', 'MUR', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(175, 'Mexico', 'MX', 'Peso', 'MXN', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(176, 'Moldova Republic of', 'MD', 'Leu', 'MDL', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(177, 'Mongolia', 'MN', 'Tugrik', 'MNT', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(178, 'Morocco', 'MA', 'Dirham', 'MAD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(179, 'Western Sahara', 'EH', 'Dirham', 'MAD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(180, 'Mozambique', 'MZ', 'Metical', 'MZN', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(181, 'Myanmar', 'MM', 'Kyat', 'MMK', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(182, 'Namibia', 'NA', 'Dollar', 'NAD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(183, 'Nepal', 'NP', 'Nepalese Rupee', 'NPR', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(184, 'Nicaragua', 'NI', 'Cordoba Oro', 'NIO', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(185, 'Nigeria', 'NG', 'Naira', 'NGN', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(186, 'Oman', 'OM', 'Sul Rial', 'OMR', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(187, 'Pakistan', 'PK', 'Rupee', 'PKR', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(188, 'Panama', 'PA', 'Balboa', 'PAB', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(189, 'Papua New Guinea', 'PG', 'Kina', 'PGK', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(190, 'Paraguay', 'PY', 'Guarani', 'PYG', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(191, 'Peru', 'PE', 'Nuevo Sol', 'PEN', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(192, 'Philippines', 'PH', 'Peso', 'PHP', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(193, 'Poland', 'PL', 'Zloty', 'PLN', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(194, 'Qatar', 'QA', 'Rial', 'QAR', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(195, 'Romania', 'RO', 'Leu', 'RON', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(196, 'Russian Federation', 'RU', 'Ruble', 'RUB', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(197, 'Rwanda', 'RW', 'Rwanda Franc', 'RWF', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(198, 'Sao Tome and Principe', 'ST', 'Dobra', 'STD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(199, 'Saudi Arabia', 'SA', 'Riyal', 'SAR', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(200, 'Seychelles', 'SC', 'Rupee', 'SCR', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(201, 'Sierra Leone', 'SL', 'Leone', 'SLL', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(202, 'Singapore', 'SG', 'Dollar', 'SGD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(203, 'Slovakia (Slovak Republic)', 'SK', 'Koruna', 'SKK', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(204, 'Solomon Islands', 'SB', 'Solomon Islands Dollar', 'SBD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(205, 'Somalia', 'SO', 'Shilling', 'SOS', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(206, 'South Africa', 'ZA', 'Rand', 'ZAR', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(207, 'Sri Lanka', 'LK', 'Rupee', 'LKR', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(208, 'Sudan', 'SD', 'Dinar', 'SDG', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(209, 'Suriname', 'SR', 'Surinamese Guilder', 'SRD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(210, 'Swaziland', 'SZ', 'Lilangeni', 'SZL', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(211, 'Sweden', 'SE', 'Krona', 'SEK', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(212, 'Syrian Arab Republic', 'SY', 'Syrian Pound', 'SYP', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(213, 'Taiwan', 'TW', 'Dollar', 'TWD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(214, 'Tajikistan', 'TJ', 'Tajikistan Ruble', 'TJS', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(215, 'Tanzania', 'TZ', 'Shilling', 'TZS', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(216, 'Thailand', 'TH', 'Baht', 'THB', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(217, 'Tonga', 'TO', 'Pa?anga', 'TOP', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(218, 'Trinidad and Tobago', 'TT', 'Trinidad and Tobago Dollar', 'TTD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(219, 'Tunisia', 'TN', 'Tunisian Dinar', 'TND', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(220, 'Turkey', 'TR', 'Lira', 'TRY', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(221, 'Turkmenistan', 'TM', 'Manat', 'TMT', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(222, 'Uganda', 'UG', 'Shilling', 'UGX', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(223, 'Ukraine', 'UA', 'Hryvnia', 'UAH', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(224, 'United Arab Emirates', 'AE', 'Dirham', 'AED', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(225, 'Uruguay', 'UY', 'Peso', 'UYU', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(226, 'Uzbekistan', 'UZ', 'Som', 'UZS', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(227, 'Vanuatu', 'VU', 'Vatu', 'VUV', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(228, 'Venezuela', 'VE', 'Bolivar', 'VEF', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(229, 'Vietnam', 'VN', 'Dong', 'VND', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(230, 'Yemen', 'YE', 'Rial', 'YER', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(231, 'Zambia', 'ZM', 'Kwacha', 'ZMK', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(232, 'Zimbabwe', 'ZW', 'Zimbabwe Dollar', 'ZWD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(233, 'Aland Islands', 'AX', 'Euro', 'EUR', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(234, 'Angola', 'AO', 'Angolan kwanza', 'AOA', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(235, 'Antarctica', 'AQ', 'Antarctican dollar', 'AQD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(236, 'Bosnia and Herzegovina', 'BA', 'Bosnia and Herzegovina convertible mark', 'BAM', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(237, 'Congo (Kinshasa)', 'CD', 'Congolese Frank', 'CDF', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(238, 'Ghana', 'GH', 'Ghana cedi', 'GHS', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(239, 'Guernsey', 'GG', 'Guernsey pound', 'GGP', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(240, 'Isle of Man', 'IM', 'Manx pound', 'GBP', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(241, 'Laos', 'LA', 'Lao kip', 'LAK', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(242, 'Macao S.A.R.', 'MO', 'Macanese pataca', 'MOP', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(243, 'Montenegro', 'ME', 'Euro', 'EUR', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(244, 'Palestinian Territory', 'PS', 'Jordanian dinar', 'JOD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(245, 'Saint Barthelemy', 'BL', 'Euro', 'EUR', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(246, 'Saint Helena', 'SH', 'Saint Helena pound', 'GBP', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(247, 'Saint Martin (French part)', 'MF', 'Netherlands Antillean guilder', 'ANG', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(248, 'Saint Pierre and Miquelon', 'PM', 'Euro', 'EUR', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(249, 'Serbia', 'RS', 'Serbian dinar', 'RSD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(250, 'US Armed Forces', 'USAF', 'US Dollar', 'USD', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `primary_email` varchar(255) NOT NULL,
  `secondary_email` varchar(255) NOT NULL,
  `contact_1` varchar(12) NOT NULL,
  `contact_2` varchar(12) NOT NULL,
  `blood_group` varchar(3) NOT NULL,
  `profile_img` varchar(255) NOT NULL DEFAULT 'defaultm.jpg',
  `emp_code` varchar(250) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `first_name`, `middle_name`, `surname`, `company_name`, `primary_email`, `secondary_email`, `contact_1`, `contact_2`, `blood_group`, `profile_img`, `emp_code`, `is_active`, `created`, `modified`) VALUES
(1, 'Deepak', 'P', 'Jha', 'Primary key technologies', 'ass@hgjh.hgjh', '', '8108765848', '', '', '', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'Deepak', 'P', 'Jha', 'Primary key technologies', 'ass@hgjh.hgjh', '', '8108765848', '', '', '', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'Deepak', 'P', 'Jha', 'Primary key technologies', 'ass@hgjh.hgjh', '', '8108765848', '', '', '', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'Primary', 'Key', 'Technologies', 'Primary key technologies', 'hgjkll@hvjhk.bhbk', 'jhkjhlj@hbkj.hk', '8108765848', '', 'B+v', 'DSC01161.JPG', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 'Ashwani', 'M', 'Mishra', 'Bureau Veritas', 'ashwani@gmail.com', '', '8108765848', '', 'A+v', 'DSC01174.JPG', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `customer_sites`
--

CREATE TABLE `customer_sites` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `address_id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `primary_email` varchar(255) NOT NULL,
  `secondary_email` varchar(255) NOT NULL,
  `contact_1` varchar(12) NOT NULL,
  `contact_2` varchar(12) NOT NULL,
  `blood_group` varchar(3) NOT NULL,
  `profile_img` varchar(255) NOT NULL,
  `site_code` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `department_name` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `designations`
--

CREATE TABLE `designations` (
  `id` int(11) NOT NULL,
  `designation` varchar(255) NOT NULL,
  `shortform` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modfied` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `is_active` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`id`, `name`, `is_active`, `created`, `modified`) VALUES
(1, 'Adhaar Card', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'Pan Card', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'Ration Card', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'Driving Licence', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 'Electricity Bill', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `dumps`
--

CREATE TABLE `dumps` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` varchar(255) NOT NULL,
  `unit` int(11) NOT NULL,
  `remark` varchar(255) NOT NULL,
  `amt` float(10,2) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `dump_details`
--

CREATE TABLE `dump_details` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `dump_id` int(11) NOT NULL,
  `purchase_details_id` int(11) NOT NULL,
  `batch_no` int(11) NOT NULL,
  `quantity` varchar(255) NOT NULL,
  `units` varchar(255) NOT NULL,
  `amt` float(10,2) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `dyn_groups`
--

CREATE TABLE `dyn_groups` (
  `id` int(11) NOT NULL,
  `title` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `abbrev` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Navigation groupings. Eg, header, sidebar, footer, etc';

--
-- Dumping data for table `dyn_groups`
--

INSERT INTO `dyn_groups` (`id`, `title`, `abbrev`) VALUES
(1, 'Header', 'header'),
(2, 'Sidebar', 'sidebar'),
(3, 'Footer', 'footer'),
(4, 'Topbar', 'topbar'),
(5, 'Sidebar1', 'sidebar1'),
(6, 'Sidebar2', 'sidebar2'),
(7, 'Frontend', 'frontend'),
(8, 'Employee Module', 'Employee');

-- --------------------------------------------------------

--
-- Table structure for table `dyn_menu`
--

CREATE TABLE `dyn_menu` (
  `id` int(11) NOT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `link_type` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'uri',
  `page_id` int(11) NOT NULL DEFAULT '0',
  `module_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `uri` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `dyn_group_id` int(11) NOT NULL DEFAULT '0',
  `position` int(5) NOT NULL DEFAULT '0',
  `target` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `is_parent` tinyint(1) NOT NULL DEFAULT '0',
  `show_menu` int(1) NOT NULL DEFAULT '1',
  `anchor_attribute` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `li_attribute` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `divider` tinyint(1) NOT NULL DEFAULT '0',
  `is_tab` tinyint(1) NOT NULL DEFAULT '1',
  `child_attribute` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dyn_menu`
--

INSERT INTO `dyn_menu` (`id`, `title`, `link_type`, `page_id`, `module_name`, `url`, `uri`, `dyn_group_id`, `position`, `target`, `parent_id`, `is_parent`, `show_menu`, `anchor_attribute`, `li_attribute`, `icon`, `divider`, `is_tab`, `child_attribute`, `is_active`, `created`, `modified`) VALUES
(1, 'Category 1', 'page', 1, '', 'http://www.category1.com', '', 1, 1, '', 0, 1, 2, 'treeview-menu', '', 'fa fa-dashboard', 0, 0, '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'Category 2', 'page', 2, '', 'http://www.category2.com', '', 1, 1, '', 0, 0, 2, 'treeview-menu', '', '', 0, 0, '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'Category 3', 'page', 3, '', 'http://www.category3.com', '', 1, 1, '', 0, 0, 2, 'treeview-menu', '', '', 0, 0, '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'Category 4', 'page', 4, '', 'http://www.category4.com', '', 1, 1, '', 0, 0, 2, 'treeview-menu', '', '', 0, 0, '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 'Category 1 - 1', 'page', 5, '', 'http://www.category11.com', '', 1, 2, '', 1, 0, 2, 'sidebar-menu', '', 'fa fa-circle-o', 0, 0, '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 'Category 1 - 2', 'page', 6, '', 'http://www.category12.com', '', 1, 2, '', 1, 1, 2, 'sidebar-menu', '', '', 0, 0, '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 'Category 1 - 2 - 1', 'page', 7, '', 'http://www.category121.com', '', 1, 3, '', 6, 0, 2, 'class="sidebar-menutreeview-menu menu-open"', '', '', 0, 0, '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(8, 'Category 1 - 2 - 2', 'page', 8, '', 'http://www.category122.com', '', 1, 3, '', 6, 1, 2, 'sidebar-menu', '', '', 0, 0, '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(9, 'Category 1 - 2 - 2 - 1', 'page', 9, '', 'http://www.category1221.com', '', 1, 4, '', 8, 0, 2, 'sidebar-menu', '', '', 0, 0, '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10, 'Category 1 - 2 - 2 - 2', 'page', 10, '', 'http://www.category1222.com', '', 1, 4, '', 8, 0, 2, 'sidebar-menu', '', '', 0, 0, '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(11, 'Category 3 - 1', 'page', 11, '', 'http://www.category31.com', '', 1, 2, '', 3, 1, 2, 'sidebar-menu', '', '', 0, 0, '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(12, 'Category 3 - 2', 'page', 12, '', 'http://www.category32.com', '', 1, 2, '', 3, 0, 2, 'sidebar-menu', '', '', 0, 0, '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(13, 'Category 3 - 3', 'page', 13, '', 'http://www.category33.com', '', 1, 2, '', 3, 0, 2, 'sidebar-menu', '', '', 0, 0, '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(14, 'Category 3 - 4', 'page', 14, '', 'http://www.category34.com', '', 1, 2, '', 3, 0, 2, 'sidebar-menu', '', '', 0, 0, '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(15, 'Category 3 - 5', 'page', 15, '', 'http://www.category35.com', '', 1, 2, '', 3, 0, 2, 'sidebar-menu', '', '', 0, 0, '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(16, 'Category 3 - 6', 'page', 16, '', 'http://www.category36.com', '', 1, 2, '', 3, 0, 2, 'sidebar-menu', '', '', 0, 0, '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(17, 'colleges', 'page', 17, '', '#', '', 7, 1, NULL, 0, 1, 2, 'treeview-menu', '', '', 0, 0, 'class="stream stream0" ', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(18, 'Arts', 'page', 18, '', '#', '', 7, 2, '', 17, 0, 2, 'class="inner_link str_name"', 'class="stream_li"', '', 0, 0, 'class="tabs clearfix tab0" style="display: none;"', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(19, 'Course', 'page', 19, '', '#', '', 7, 1, NULL, 0, 1, 2, '', '', '', 0, 0, '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(20, 'Cutoff', 'page', 20, '', '#', '', 7, 1, NULL, 0, 1, 2, '', '', '', 0, 0, '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(21, 'Exams n Results', 'page', 21, '', '#', '', 7, 1, NULL, 0, 1, 2, '', '', '', 0, 0, '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(22, 'Event n Festival', 'page', 22, '', 'www.teachuseducation.com', '', 7, 1, NULL, 0, 1, 2, '', '', '', 0, 0, '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(23, 'navigation', 'page', 23, '', 'www.teachuseducation.com', '', 7, 1, NULL, 0, 1, 2, '', '', '', 0, 0, '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(24, 'Commerce', 'page', 24, '', 'www.education.com', '', 7, 2, NULL, 17, 0, 2, 'class="inner_link str_name"', 'class="stream_li"', '', 0, 0, 'class="tabs clearfix tab1" style="display: none;"', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(25, 'Science', 'page', 25, '', 'www.education.com', '', 7, 2, NULL, 17, 0, 2, 'class="inner_link str_name"', 'class="stream_li"', '', 0, 0, 'class="tabs clearfix tab2" style="display: none;"', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(26, 'Management', 'page', 26, '', 'www.education.com', '', 7, 2, NULL, 17, 0, 2, '', '', '', 0, 0, '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(27, 'Junior College', 'page', 27, '', '#', '#', 7, 3, NULL, 18, 1, 2, 'class="inner_link"', 'data-link="menu/get_Tabs" class="tab_filterrr"', '', 0, 1, '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(28, 'Degree College', 'page', 28, '', '#', '#', 7, 3, NULL, 18, 1, 2, 'class="inner_link"', 'data-link="menu/get_Tabs" class="tab_filterrr"', '', 0, 1, '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(29, 'Junior College', 'page', 29, '', '#', '#', 7, 3, NULL, 24, 1, 2, 'class="inner_link"', 'data-link="menu/get_Tabs" class="tab_filterrr"', '', 0, 1, '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(30, 'Degree College', 'page', 30, '', '#', '#', 7, 3, NULL, 24, 1, 2, 'class="inner_link"', 'data-link="menu/get_Tabs" class="tab_filterrr"', '', 0, 1, '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(31, 'Junior College', 'page', 31, '', '#', '#', 7, 3, NULL, 25, 1, 2, 'class="inner_link"', 'data-link="menu/get_Tabs" class="tab_filterrr"', '', 0, 1, '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(32, 'Degree College', 'page', 32, '', '#', '#', 7, 3, NULL, 25, 1, 2, 'class="inner_link"', 'data-link="menu/get_Tabs" class="tab_filterrr"', '', 0, 1, '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `dyn_menu_tags`
--

CREATE TABLE `dyn_menu_tags` (
  `id` int(11) NOT NULL,
  `dyn_menu_id` int(11) NOT NULL,
  `tag` varchar(255) NOT NULL,
  `attribute` varchar(255) NOT NULL,
  `priority` int(11) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modifed` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `dyn_menu_tags`
--

INSERT INTO `dyn_menu_tags` (`id`, `dyn_menu_id`, `tag`, `attribute`, `priority`, `is_active`, `created`, `modifed`) VALUES
(1, 17, 'div', 'class="normalizer" name="normailzer" style="display:none"', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 18, 'ul', 'class="menu_cont clearfix"', 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `email_whitelist`
--

CREATE TABLE `email_whitelist` (
  `id` int(11) NOT NULL,
  `email` varchar(320) NOT NULL,
  `account_type` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `allow_login` tinyint(1) NOT NULL DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `first_name`, `middle_name`, `surname`, `dob`, `contact_1`, `contact_2`, `blood_group`, `primary_email`, `secondary_email`, `profile_img`, `emp_code`, `start_date`, `allow_login`, `is_active`, `created`, `modified`) VALUES
(1, 'Deepak', 'P.', 'jha', '1999-06-08', '1234567890', '1234567890', 'AB+', 'gfhfjhjhk@fggfg.jhk', 'hgjgk@jhg.jhj', 'defaultm.jpg', '', '0000-00-00', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'abc', '', 'abc', '0000-00-00', '1234567890', '1234567890', '0', 'abc@gmail.com', 'abc@gmail.com', 'defaultm.jpg', NULL, '0000-00-00', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'om', '', 'jha', '0000-00-00', '1234567890', '1234567890', '0', 'adf@gfch.hgj', 'hgvjhk@gvh.hgh', 'default4.png', NULL, '0000-00-00', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 'khushboo', '', 'Singh', '0000-00-00', '9167632340', '9167632340', '0', 'khush.singh9319@gmail.com', 'khush.singh9319@gmail.com', 'Desert.jpg', NULL, '0000-00-00', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 'ashwani', '', 'mishra', '0000-00-00', '1234567890', '1234567890', '0', 'ashwani@gmail.com', 'ashwani@gmail.com', 'Tulips1.jpg', NULL, '0000-00-00', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 'khush', '', 'singh', '0000-00-00', '0987654321', '0987654321', '0', 'khush.12@gmail.com', 'khush.12@gmail.com', 'Jellyfish.jpg', NULL, '0000-00-00', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(8, 'deepak', '', 'jha', '0000-00-00', '1234567890', '1234567890', '0', 'jha@gmail.com', 'jha@gmail.com', 'Hydrangeas.jpg', NULL, '0000-00-00', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(9, 'Deepak', '', 'Jha', '0000-00-00', '8108765848', '8108765848', '1', 'deep@gmail.com', 'deep@gmail.com', 'Hydrangeas7.jpg', NULL, '0000-00-00', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10, 'anbc', '', 'anbc', '1970-01-01', '0987654321', '0987654321', '1', 'abcbd@gf.com', 'abcbd@gf.com', 'Penguins14.jpg', NULL, '0000-00-00', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(11, 'Deepak', '', 'Jha', '1970-01-01', '81098765848', '81098765848', '1', 'mme.dpj@gmail.com', 'mme.dpj@gmail.com', 'Penguins18.jpg', NULL, '0000-00-00', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(13, 'Ash', '', 'Mishra', '2017-12-07', '8108765848', '8108765848', '1', 'mmkk@hgjk.jhgj', 'hgkj@hgfj.hgj', 'Penguins24.jpg', NULL, '0000-00-00', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(14, 'Deepak', '', 'Jha', '1970-01-01', '8108765848', '8108765848', '1', 'dep@gmail.com', 'dep@gmail.com', 'Hydrangeas10.jpg', NULL, '0000-00-00', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(15, 'ash', '', 'mishra', '1970-01-01', '8108765848', '8108765848', '1', '8108765848@gmail.com', '8108765848@gmail.com', 'Penguins27.jpg', NULL, '0000-00-00', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(16, 'deepak', 'M', 'jha', '1970-01-01', '1234567890', '1234567890', '1', '1234567890@gmail.com', '1234567890@gmail.com', 'Penguins29.jpg', NULL, '0000-00-00', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(17, 'deepak', 'M', 'jha', '1970-01-01', '1234567890', '1234567890', '1', '12345678902@gmail.com', '12345678902@gmail.com', 'defaultm.jpg', NULL, '0000-00-00', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(18, 'rajveer', 's', 'singh', '1970-01-01', '1234567890', '1234567890', '1', 'rajveer@gmail.com', 'rajveer@gmail.com', 'Penguins43.jpg', NULL, '0000-00-00', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(19, 'Rajveer', 'S', 'Singh', '1970-01-01', '9167632340', '9167632340', '1', '9167632340@gmail.com', '9167632340@gmail.com', 'Hydrangeas13.jpg', NULL, '0000-00-00', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(20, 'khu', 's', 'sing', '2017-06-07', '1234689076', '1234689079', '1', '1234689076@gg.cc', '1234689076@gg.cc', 'Penguins45.jpg', NULL, '0000-00-00', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(21, 'Rajveer', 'Sushil', 'Singh', '1991-11-19', '9167632340', '9167632340', '1', '916763234101@gmail.com', '91676323401@gmail.com', 'Hydrangeas23.jpg', NULL, '0000-00-00', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(22, 'Rudra', 'S', 'Singh', '1970-01-01', '9167632340', '9167632340', '1', '9167632340ss@gmail.com', '9167632340ss@gmail.com', 'Hydrangeas15.jpg', NULL, '0000-00-00', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(23, 'Test', 'test', 'tset', '2016-11-07', '8108765848', '8108765848', '1', '810876@ggg.ghh', '810876@jkj.hk', 'Jellyfish1.jpg', NULL, '0000-00-00', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(24, 'Jyoti', 'R', 'Naik', '1970-01-01', '9004185542', '9004185542', '1', '9004185542@gmail.com', '9004185542@gmail.com', 'Penguins46.jpg', NULL, '0000-00-00', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(25, 'pkt', 'pkt', 'pkt', '2017-02-04', '9167632340', '9167632340', '1', '9167632340abc@gmail.com', '9167632340abc@gmail.com', 'Jellyfish2.jpg', NULL, '0000-00-00', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(26, 'Ashwani', 'M', 'Mishra', '1970-01-01', '9167632340', '9167632340', '1', '9167632340ash@gmail.com', '9167632340ash@gmail.com', 'Chrysanthemum.jpg', NULL, '0000-00-00', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(27, 'Yuvraj', 'S', 'Singh', '2016-07-11', '8108817717', '8108817717', '1', '8108817717@gmail.com', '8108817717@gmail.com', 'Desert5.jpg', NULL, '0000-00-00', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(28, 'abc', 'abc', 'abc', '2017-04-09', '9167632340', '9167632340', '1', '916763234032@yahoo.in', '916763234032@yahoo.in', 'Penguins47.jpg', NULL, '0000-00-00', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(29, 'abc', 'abc', 'abc', '1970-01-01', '1234567890', '1234567890', '1', '123456789098@ymail.com', '123456789098@ymail.com', 'Desert6.jpg', NULL, '0000-00-00', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(30, 'abc', 'abc', 'abc', '2017-01-08', '9870504532', '9870504532', '1', '9870504532@yhoo.in', '9870504532@yhoo.in', 'Desert8.jpg', NULL, '0000-00-00', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(31, 'om', 'prakash', 'yadav', '1970-01-01', '879656453421', '879656453421', '1', '879656453421@abc.in', '879656453421@abc.in', 'Jellyfish3.jpg', NULL, '0000-00-00', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(32, 'om', 'prakash', 'yadav', '1970-01-01', '879656453421', '879656453421', '1', '87965645342@abc.in', '879656453421@abc.in', 'Jellyfish4.jpg', NULL, '0000-00-00', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(33, 'om', 'prakash', 'yadav', '1970-01-01', '879656453421', '879656453421', '1', '8796564532@abc.in', '879656453421@abc.in', 'Jellyfish5.jpg', NULL, '0000-00-00', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(34, 'om', 'prakash', 'yadav', '1970-01-01', '879656453421', '879656453421', '1', '8796564532@abc.inf', '879656453421@abc.in', 'defaultm.jpg', NULL, '0000-00-00', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(35, 'om', 'prakash', 'yadav', '1970-01-01', '879656453421', '879656453421', '1', '8796564532@abc.inh', '879656453421@abc.in', 'defaultm.jpg', NULL, '0000-00-00', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(36, 'Om', 'prakash', 'yadav', '2017-03-08', '1234567890', '1234567890', '1', '1234567890@google.com', '1234567890@google.com', 'Penguins49.jpg', NULL, '0000-00-00', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(37, 'Om', 'prakash', 'yadav', '2017-03-08', '1234567890', '1234567890', '1', '1234567894@google.com', '1234567890@google.com', 'Desert11.jpg', NULL, '0000-00-00', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(38, 'Test', 'T', 'Employee', '1970-01-01', '8108765848', '9920758818', '1', 'mailm@gmail.com', 'primarykeytech@gmail.com', '', NULL, '0000-00-00', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(39, 'Deepak', 'P', 'Jha', '1991-04-06', '8108765848', '9920758818', '1', 'pui@bhgk.jhjgh', 'vmn@hjbkl.jhgj', '', NULL, '0000-00-00', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(40, 'Kishan', 'M', 'Lokhande', '1969-12-01', '9225122112', '9821625731', '1', 'kisanl@rediffmail.com', 'mailme.deepakjha@gmail.com', 'silhouette-165527_1920.jpg', NULL, '0000-00-00', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(41, 'Primary', 'Key', 'Technologies', '1970-01-01', '8108765848', '', '1', 'mailme.deejha@gmail.com', '', '', NULL, '0000-00-00', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(43, 'hgjh', 'kjhk', 'hjkjhk', '1991-07-03', '8108765848', '9920758818', '1', 'mailme.d@gmail.com', '', '', NULL, '0000-00-00', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(44, 'hgjh', 'kjhk', 'hjkjhk', '1991-07-03', '8108765848', '9920758818', '1', 'maime.d@gmail.com', '', '', NULL, '0000-00-00', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(45, 'hgjh', 'kjhk', 'hjkjhk', '1991-07-03', '8108765848', '9920758818', '1', 'mame.d@gmail.com', '', '', NULL, '0000-00-00', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(46, 'hgjh', 'kjhk', 'hjkjhk', '1991-11-18', '8108765848', '9920758818', '1', 'mame.d@gmail.cm', '', 'WhatsApp_Image_2017-07-16_at_7_51_44_PM2.jpeg', 'MSS0000046', '0000-00-00', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(47, 'gtset', 'jhgj', 'nbmnb', '1970-01-01', '8108765848', '9920758818', '3', 'hjbjkkkjk@gghkjl.jhgjk', '', 'WhatsApp_Image_2017-07-16_at_7_54_57_PM.jpeg', 'MSS0000047', '0000-00-00', 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(48, 'jhbjk', 'jhghjk', 'kjghjgkj', '2017-09-09', '8108765848', '', '1', 'hgvjkblkml@hgbjkjl.kjhk', '', '', 'MSS0000048', '0000-00-00', 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(49, 'Primary', 'Key', 'Technologies', '1970-01-01', '8108765848', '', '3', 'gfchgjkbl@gfgvhj.hj', '', 'WhatsApp_Image_2017-07-16_at_7_51_44_PM3.jpeg', 'MSS0000049', '0000-00-00', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(50, 'Primary', 'Key', 'Technologies', '2017-09-09', '8108765848', '9920758818', 'A+ve', 'hgfh@hgjl.hgj', '', 'logo.jpg', 'MSS0000050', '0000-00-00', 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(51, 'Ashwini', 'M.', 'Mishra', '1990-08-27', '8130509687', '', 'A+ve', 'ashwani.mishraa27@gmail.com', '', 'DSC01184.JPG', 'MSS0000051', '2017-09-05', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(52, 'Shubham', 'K', 'Lokhande', '1991-06-04', '7875338100', '', 'A+ve', 'shubham.lokhande.1998@gmail.com', '', 'DSC01160.JPG', 'MSS0000052', '2017-09-12', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `employees_roles`
--

CREATE TABLE `employees_roles` (
  `id` int(11) NOT NULL,
  `employees_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `employees_salaries`
--

CREATE TABLE `employees_salaries` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `employment_start_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `employment_end_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `salary` int(11) NOT NULL,
  `provident_fund` int(11) NOT NULL,
  `esic` int(11) NOT NULL,
  `professional_tax` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employees_salaries`
--

INSERT INTO `employees_salaries` (`id`, `employee_id`, `employment_start_date`, `employment_end_date`, `salary`, `provident_fund`, `esic`, `professional_tax`, `is_active`, `created`, `modified`) VALUES
(1, 52, '2017-02-01 00:00:00', '0000-00-00 00:00:00', 10000, 800, 0, 150, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 52, '2011-02-03 00:00:00', '0000-00-00 00:00:00', 15000, 0, 0, 175, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 51, '2017-06-06 00:00:00', '2017-12-15 00:00:00', 50000, 500, 5, 511, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `floors`
--

CREATE TABLE `floors` (
  `id` int(11) NOT NULL,
  `floor` varchar(255) NOT NULL,
  `shortcode` varchar(10) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gl_trans`
--

CREATE TABLE `gl_trans` (
  `id` int(11) NOT NULL,
  `trans_id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `received_amt` varchar(10) NOT NULL,
  `payable_amt` varchar(10) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ip_blacklist`
--

CREATE TABLE `ip_blacklist` (
  `id` int(11) NOT NULL,
  `ip_address` varchar(15) NOT NULL,
  `failed_attempts` int(11) NOT NULL,
  `lock_time` varchar(15) DEFAULT NULL,
  `last_login_attempt` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `is` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `first_name` varchar(64) NOT NULL,
  `surname` varchar(64) NOT NULL,
  `username` varchar(24) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `account_type` varchar(32) NOT NULL,
  `email` varchar(320) NOT NULL,
  `email_verification_link` varchar(64) DEFAULT NULL,
  `email_ver_time` varchar(15) DEFAULT NULL,
  `email_verified` varchar(3) DEFAULT NULL,
  `accnt_create_time` varchar(15) NOT NULL,
  `passwd_reset_str` varchar(64) DEFAULT NULL,
  `passwd_reset_time` varchar(15) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`id`, `first_name`, `surname`, `username`, `password_hash`, `employee_id`, `account_type`, `email`, `email_verification_link`, `email_ver_time`, `email_verified`, `accnt_create_time`, `passwd_reset_str`, `passwd_reset_time`, `is_active`, `created`, `modified`) VALUES
(19, 'deepak', '---', 'jha', '$2y$10$eIPnoRcdQkT6o5w7m4NcVOhSVnTfNYe4Tbgg2v740MQppROoqfdtG', 0, 'basic', 'primarykeytech@gmail.com', 'c19453ab31fba0e8fee72dc07c474d690145c3f1', '1499587600', NULL, '1499587599', NULL, NULL, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(20, 'khushboo', 'singh', 'khush', '$2y$10$mbUnymVklPQv494IRr04EePflAwNwNPJ1OF.mm/ujiB69R5IL1.q2', 0, 'basic', 'khush@gmail.com', 'b19eb7e665abf502300b8cdd84ebe67cab84f050', '1499608783', NULL, '1499608782', NULL, NULL, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(21, 'deepak', 'jha', 'deepakj', '$2y$10$LOaDkqHwTEz4f/r8uJj/5OUONU/p56/zELotgCk2boHAaNwlyEEQi', 0, 'basic', 'mail@gmail.com', '0f03d328cded759e22da13b3ac203c7a40de8a57', '1499859675', NULL, '1499859674', NULL, NULL, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(22, 'khus', 's', 'khus', '$2y$10$c/jnmKSMQoZU01Fu0VJqLODx9RThExnnfdAoC7ersz5DcfKzkm/H2', 0, 'basic', 'khus@gm.com', '382ed7a437e1f91cb398685be7f56ca178915c7a', '1499860515', NULL, '1499860514', NULL, NULL, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(23, 'khus2', 's', 'khus2', '$2y$10$0aYZN8puSldJG0eUVf19WOuonhObfinJdZ3ttq8dP2vX/BlWlaqmu', 0, 'basic', 'khus2@gm.com', 'd6aaf9d90cf0a0e07a3d5e68f9038222b9b6f4ec', '1499860597', NULL, '1499860596', NULL, NULL, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(24, 'om', 'om', 'om', '$2y$10$ShiHkba8W2yOAz3frB4MAuY2GTDLXbEplk7ieDbIC76Qoo2qrEBfO', 0, 'basic', 'mailme.deepakjha@gmail.com', '2adba07a9139a824ea8d29ca2a6821cc86f279ae', '1499866668', NULL, '1499866667', NULL, NULL, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `menu_list`
--

CREATE TABLE `menu_list` (
  `id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE `modules` (
  `id` int(11) NOT NULL,
  `module_name` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created` date NOT NULL,
  `modified` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `amt_before_tax` float(10,2) NOT NULL,
  `tax_group_id` int(11) NOT NULL,
  `default_doctor_id` int(11) NOT NULL,
  `default_nurse_id` int(11) NOT NULL,
  `amt_after_tax` float(10,2) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `amt_before_tax` float(10,2) NOT NULL,
  `tax_group_id` int(11) NOT NULL,
  `amt_after_tax` float(10,2) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pay_groups`
--

CREATE TABLE `pay_groups` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` int(11) NOT NULL,
  `reference_no` varchar(255) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `amt_before_tax` float(10,2) NOT NULL,
  `tax_group_id` int(11) NOT NULL,
  `total_amt` float(10,2) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_details`
--

CREATE TABLE `purchase_details` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `purchase_id` int(11) NOT NULL,
  `purchase_date` datetime NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `units` varchar(255) NOT NULL,
  `batch_no` datetime NOT NULL,
  `mfg_date` datetime NOT NULL,
  `expiry_date` datetime NOT NULL,
  `amt_before_tax` float(10,2) NOT NULL,
  `tax_group_id` int(11) NOT NULL,
  `total_amt` float(10,2) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
(1, 'Security', 'Security', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'Bodyguard', 'Bodyguard', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'Admin', 'Admin', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'Accountant', 'Accounts', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `room` varchar(255) NOT NULL,
  `charge` float(10,2) NOT NULL,
  `floor_id` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `salary_deductions`
--

CREATE TABLE `salary_deductions` (
  `id` int(11) NOT NULL,
  `deduction_name` varchar(255) NOT NULL,
  `deduction_type` varchar(255) NOT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sales_details`
--

CREATE TABLE `sales_details` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `sale_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `customer_id` int(11) NOT NULL,
  `units` varchar(255) NOT NULL,
  `amt_before_tax` float(10,2) NOT NULL,
  `tax_group_id` int(11) NOT NULL,
  `total_amt` float(10,2) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `service` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  `state_name` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`id`, `country_id`, `state_name`, `created`, `modified`, `is_active`) VALUES
(1, 1, 'Maharashtra', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1),
(2, 1, 'Delhi', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1),
(3, 1, 'Karnataka', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1),
(4, 1, 'Gujarat', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1),
(5, 1, 'Telangana', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1),
(6, 1, 'Tamil Nadu', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1),
(7, 1, 'West Bengal', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1),
(8, 1, 'Rajasthan', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1),
(9, 1, 'Uttar Pradesh', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1),
(10, 1, 'Bihar', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1),
(11, 1, 'Madhya Pradesh', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1),
(12, 1, 'Andhra Pradesh', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1),
(13, 1, 'Punjab', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1),
(14, 1, 'Haryana', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1),
(15, 1, 'Jammu and Kashmir', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1),
(16, 1, 'Jharkhand', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1),
(17, 1, 'Chhattisgarh', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1),
(18, 1, 'Assam', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1),
(19, 1, 'Chandigarh', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1),
(20, 1, 'Odisha', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1),
(21, 1, 'Kerala', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1),
(22, 1, 'Uttarakhand', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1),
(23, 1, 'Puducherry', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1),
(24, 1, 'Tripura', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1),
(25, 1, 'Karnatka', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1),
(26, 1, 'Mizoram', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1),
(27, 1, 'Meghalaya', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1),
(28, 1, 'Manipur', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1),
(29, 1, 'Himachal Pradesh', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1),
(30, 1, 'Nagaland', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1),
(31, 1, 'Goa', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1),
(32, 1, 'Andaman and Nicobar Islands', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1),
(33, 1, 'Arunachal Pradesh', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1),
(34, 1, 'Dadra and Nagar Haveli', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `stock_movements`
--

CREATE TABLE `stock_movements` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `trans_id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `units` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `supporting_module`
--

CREATE TABLE `supporting_module` (
  `id` int(11) NOT NULL,
  `supporting_module_name` varchar(255) NOT NULL,
  `module_id` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tab_contents`
--

CREATE TABLE `tab_contents` (
  `id` int(11) NOT NULL,
  `heading` varchar(255) NOT NULL,
  `tab_content` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `dyn_menu_id` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tab_contents`
--

INSERT INTO `tab_contents` (`id`, `heading`, `tab_content`, `link`, `dyn_menu_id`, `is_active`, `created`, `modified`) VALUES
(1, 'Western Cluster', 'Dahanu to Dahisar', '{"controller":"colleges", "method":"","sub_stream_id":"1","city_cluster_area_id":"1"}', 27, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'Western Cluster', 'Borivali to Malad', '{"controller":"colleges", "method":"","sub_stream_id":"1","city_cluster_area_id":"2"}', 27, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'Western Cluster', 'Goregaon to Vile Parle\r\n', '{"controller":"colleges", "method":"","sub_stream_id":"1","city_cluster_area_id":"3"}', 27, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'Western Cluster', 'Santacruz to Dadar', '{"controller":"colleges", "method":"","sub_stream_id":"1","city_cluster_area_id":"4"}', 27, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 'Western Cluster', 'Elphinstone to Churchgate', '{"controller":"colleges", "method":"","sub_stream_id":"1","city_cluster_area_id":"5"}', 27, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 'Central Cluster', 'Dahanu to Dahisar', '#', 27, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 'Central Cluster', 'Borivali to Malad', '#', 27, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(8, 'Central Cluster', 'Goregaon to Vile Parle', '#', 27, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(9, 'Central Cluster', 'Santacruz to Dadar', '#', 27, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10, 'Central Cluster', 'Elphinstone to Churchgate', '#', 27, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(11, 'Harbour Cluster', 'Dahanu to Dahisar', '#', 27, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(12, 'Harbour Cluster', 'Borivali to Malad', '#', 27, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(13, 'Harbour Cluster', 'Goregaon to Vile Parle', '#', 27, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(14, 'Harbour Cluster', 'Santacruz to Dadar', '#', 27, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(15, 'Harbour Cluster', 'Elphinstone to Churchgate', '#', 27, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(16, 'Western Cluster', 'Dahanu to Dahisar', '{"controller":"colleges", "method":"","sub_stream_id":"1","city_cluster_area_id":"1"}', 28, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(17, 'Western Cluster', 'Borivali to Malad', '{"controller":"colleges", "method":"","sub_stream_id":"1","city_cluster_area_id":"1"}', 28, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(18, 'Western Cluster', 'Goregaon to Vile Parle', '{"controller":"colleges", "method":"","sub_stream_id":"1","city_cluster_area_id":"1"}', 28, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(19, 'Western Cluster', 'Santacruz to Dadar', '{"controller":"colleges", "method":"","sub_stream_id":"1","city_cluster_area_id":"1"}', 28, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(20, 'Western Cluster', 'Elphinstone to Churchgate', '{"controller":"colleges", "method":"","sub_stream_id":"1","city_cluster_area_id":"1"}', 28, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(21, 'Central Cluster', 'Dahanu to Dahisar', '{"controller":"colleges", "method":"","sub_stream_id":"1","city_cluster_area_id":"1"}', 28, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(22, 'Central Cluster', 'Borivali to Malad', '{"controller":"colleges", "method":"","sub_stream_id":"1","city_cluster_area_id":"1"}', 28, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(23, 'Central Cluster', 'Goregoan to Vile Parle', '{"controller":"colleges", "method":"","sub_stream_id":"1","city_cluster_area_id":"1"}', 28, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(24, 'Central Cluster', 'Santacruz to Dadar', '{"controller":"colleges", "method":"","sub_stream_id":"1","city_cluster_area_id":"1"}', 28, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(25, 'Central Cluster', 'Elphinstone to Churchgate', '{"controller":"colleges", "method":"","sub_stream_id":"1","city_cluster_area_id":"1"}', 28, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(26, 'Harbour Cluster', 'Dahanu to Dahisar', '{"controller":"colleges", "method":"","sub_stream_id":"1","city_cluster_area_id":"1"}', 28, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(27, 'Harbour Cluster', 'Borivali to Malad', '{"controller":"colleges", "method":"","sub_stream_id":"1","city_cluster_area_id":"1"}', 28, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(28, 'Harbour Cluster', 'Goregaon to Vile Parle', '{"controller":"colleges", "method":"","sub_stream_id":"1","city_cluster_area_id":"1"}', 28, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(29, 'Harbour Cluster', 'Santacruz to Dadar', '{"controller":"colleges", "method":"","sub_stream_id":"1","city_cluster_area_id":"1"}', 28, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(30, 'Harbour Cluster', 'Elphinstone to Churchgate', '{"controller":"colleges", "method":"","sub_stream_id":"1","city_cluster_area_id":"1"}', 28, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(31, 'Western Cluster', 'Dahanu to Dahisar', '{"controller":"colleges", "method":"","sub_stream_id":"2","city_cluster_area_id":"1"}', 29, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(32, 'Western Cluster', 'Borivali to Malad', '{"controller":"colleges", "method":"","sub_stream_id":"2","city_cluster_area_id":"2"}', 29, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(33, 'Western Cluster', 'Goregaon to Vile Parle\n', '{"controller":"colleges", "method":"","sub_stream_id":"2","city_cluster_area_id":"3"}', 29, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(34, 'Western Cluster', 'Santacruz to Dadar', '{"controller":"colleges", "method":"","sub_stream_id":"2","city_cluster_area_id":"4"}', 29, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(35, 'Western Cluster', 'Elphinstone to Churchgate', '{"controller":"colleges", "method":"","sub_stream_id":"2","city_cluster_area_id":"5"}', 29, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(36, 'Central Cluster', 'Kasara to Kalyan', '{"controller":"colleges", "method":"","sub_stream_id":"2","city_cluster_area_id":"6"}', 29, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(37, 'Central Cluster', 'Borivali to Malad', '{"controller":"colleges", "method":"","sub_stream_id":"2","city_cluster_area_id":"2"}', 29, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(38, 'Central Cluster', 'Goregaon to Vile Parle', '{"controller":"colleges", "method":"","sub_stream_id":"2","city_cluster_area_id":"3"}', 29, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(39, 'Central Cluster', 'Santacruz to Dadar', '{"controller":"colleges", "method":"","sub_stream_id":"2","city_cluster_area_id":"4"}', 29, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(40, 'Central Cluster', 'Elphinstone to Churchgate', '{"controller":"colleges", "method":"","sub_stream_id":"2","city_cluster_area_id":"5"}', 29, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(41, 'Harbour Cluster', 'Dahanu to Dahisar', '{"controller":"colleges", "method":"","sub_stream_id":"2","city_cluster_area_id":"1"}', 29, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(42, 'Harbour Cluster', 'Borivali to Malad', '{"controller":"colleges", "method":"","sub_stream_id":"2","city_cluster_area_id":"2"}', 29, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(43, 'Harbour Cluster', 'Goregaon to Vile Parle', '{"controller":"colleges", "method":"","sub_stream_id":"2","city_cluster_area_id":"3"}', 29, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(44, 'Harbour Cluster', 'Santacruz to Dadar', '{"controller":"colleges", "method":"","sub_stream_id":"2","city_cluster_area_id":"4"}', 29, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(45, 'Harbour Cluster', 'Elphinstone to Churchgate', '{"controller":"colleges", "method":"","sub_stream_id":"2","city_cluster_area_id":"5"}', 29, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(46, 'Western Cluster', 'Dahanu to Dahisar', '{"controller":"colleges", "method":"","sub_stream_id":"2","city_cluster_area_id":"1"}', 30, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(47, 'Western Cluster', 'Borivali to Malad', '{"controller":"colleges", "method":"","sub_stream_id":"2","city_cluster_area_id":"2"}', 30, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(48, 'Western Cluster', 'Goregaon to Vile Parle', '{"controller":"colleges", "method":"","sub_stream_id":"2","city_cluster_area_id":"3"}', 30, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(49, 'Western Cluster', 'Santacruz to Dadar', '{"controller":"colleges", "method":"","sub_stream_id":"2","city_cluster_area_id":"4"}', 30, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(50, 'Western Cluster', 'Elphinstone to Churchgate', '{"controller":"colleges", "method":"","sub_stream_id":"2","city_cluster_area_id":"5"}', 30, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(51, 'Central Cluster', 'Kasara to Kalyan', '{"controller":"colleges", "method":"","sub_stream_id":"2","city_cluster_area_id":"1"}', 30, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(52, 'Central Cluster', 'Borivali to Malad', '{"controller":"colleges", "method":"","sub_stream_id":"2","city_cluster_area_id":"2"}', 30, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(53, 'Central Cluster', 'Goregoan to Vile Parle', '{"controller":"colleges", "method":"","sub_stream_id":"2","city_cluster_area_id":"3"}', 30, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(54, 'Central Cluster', 'Santacruz to Dadar', '{"controller":"colleges", "method":"","sub_stream_id":"2","city_cluster_area_id":"4"}', 30, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(55, 'Central Cluster', 'Elphinstone to Churchgate', '{"controller":"colleges", "method":"","sub_stream_id":"2","city_cluster_area_id":"5"}', 30, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(56, 'Harbour Cluster', 'Dahanu to Dahisar', '{"controller":"colleges", "method":"","sub_stream_id":"2","city_cluster_area_id":"1"}', 30, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(57, 'Harbour Cluster', 'Borivali to Malad', '{"controller":"colleges", "method":"","sub_stream_id":"2","city_cluster_area_id":"2"}', 30, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(58, 'Harbour Cluster', 'Goregaon to Vile Parle', '{"controller":"colleges", "method":"","sub_stream_id":"2","city_cluster_area_id":"3"}', 30, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(59, 'Harbour Cluster', 'Santacruz to Dadar', '{"controller":"colleges", "method":"","sub_stream_id":"2","city_cluster_area_id":"4"}', 30, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(60, 'Harbour Cluster', 'Elphinstone to Churchgate', '{"controller":"colleges", "method":"","sub_stream_id":"2","city_cluster_area_id":"5"}', 30, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(61, 'Western Cluster', 'Dahanu to Dahisar', '{"controller":"colleges", "method":"","sub_stream_id":"3","city_cluster_area_id":"1"}', 31, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(62, 'Western Cluster', 'Borivali to Malad', '{"controller":"colleges", "method":"","sub_stream_id":"3","city_cluster_area_id":"2"}', 31, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(63, 'Western Cluster', 'Goregaon to Vile Parle\n', '{"controller":"colleges", "method":"","sub_stream_id":"3","city_cluster_area_id":"3"}', 31, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(64, 'Western Cluster', 'Santacruz to Dadar', '{"controller":"colleges", "method":"","sub_stream_id":"3","city_cluster_area_id":"4"}', 31, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(65, 'Western Cluster', 'Elphinstone to Churchgate', '{"controller":"colleges", "method":"","sub_stream_id":"3","city_cluster_area_id":"5"}', 31, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(66, 'Central Cluster', 'Kasara to Kalyan', '{"controller":"colleges", "method":"","sub_stream_id":"3","city_cluster_area_id":"1"}', 31, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(67, 'Central Cluster', 'Borivali to Malad', '{"controller":"colleges", "method":"","sub_stream_id":"3","city_cluster_area_id":"2"}', 31, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(68, 'Central Cluster', 'Goregaon to Vile Parle', '{"controller":"colleges", "method":"","sub_stream_id":"3","city_cluster_area_id":"3"}', 31, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(69, 'Central Cluster', 'Santacruz to Dadar', '{"controller":"colleges", "method":"","sub_stream_id":"3","city_cluster_area_id":"4"}', 31, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(70, 'Central Cluster', 'Elphinstone to Churchgate', '{"controller":"colleges", "method":"","sub_stream_id":"3","city_cluster_area_id":"5"}', 31, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(71, 'Harbour Cluster', 'Dahanu to Dahisar', '{"controller":"colleges", "method":"","sub_stream_id":"3","city_cluster_area_id":"1"}', 31, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(72, 'Harbour Cluster', 'Borivali to Malad', '{"controller":"colleges", "method":"","sub_stream_id":"3","city_cluster_area_id":"2"}', 31, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(73, 'Harbour Cluster', 'Goregaon to Vile Parle', '{"controller":"colleges", "method":"","sub_stream_id":"3","city_cluster_area_id":"3"}', 31, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(74, 'Harbour Cluster', 'Santacruz to Dadar', '{"controller":"colleges", "method":"","sub_stream_id":"3","city_cluster_area_id":"4"}', 31, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(75, 'Harbour Cluster', 'Elphinstone to Churchgate', '{"controller":"colleges", "method":"","sub_stream_id":"3","city_cluster_area_id":"5"}', 31, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(76, 'Western Cluster', 'Dahanu to Dahisar', '{"controller":"colleges", "method":"","sub_stream_id":"3","city_cluster_area_id":"1"}', 32, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(77, 'Western Cluster', 'Borivali to Malad', '{"controller":"colleges", "method":"","sub_stream_id":"3","city_cluster_area_id":"2"}', 32, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(78, 'Western Cluster', 'Goregaon to Vile Parle', '{"controller":"colleges", "method":"","sub_stream_id":"3","city_cluster_area_id":"3"}', 32, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(79, 'Western Cluster', 'Santacruz to Dadar', '{"controller":"colleges", "method":"","sub_stream_id":"3","city_cluster_area_id":"4"}', 32, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(80, 'Western Cluster', 'Elphinstone to Churchgate', '{"controller":"colleges", "method":"","sub_stream_id":"3","city_cluster_area_id":"5"}', 32, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(81, 'Central Cluster', 'Kasara to Kalyan', '{"controller":"colleges", "method":"","sub_stream_id":"3","city_cluster_area_id":"1"}', 32, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(82, 'Central Cluster', 'Borivali to Malad', '{"controller":"colleges", "method":"","sub_stream_id":"3","city_cluster_area_id":"2"}', 32, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(83, 'Central Cluster', 'Goregoan to Vile Parle', '{"controller":"colleges", "method":"","sub_stream_id":"3","city_cluster_area_id":"3"}', 32, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(84, 'Central Cluster', 'Santacruz to Dadar', '{"controller":"colleges", "method":"","sub_stream_id":"3","city_cluster_area_id":"4"}', 32, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(85, 'Central Cluster', 'Elphinstone to Churchgate', '{"controller":"colleges", "method":"","sub_stream_id":"3","city_cluster_area_id":"5"}', 32, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(86, 'Harbour Cluster', 'Dahanu to Dahisar', '{"controller":"colleges", "method":"","sub_stream_id":"3","city_cluster_area_id":"1"}', 32, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(87, 'Harbour Cluster', 'Borivali to Malad', '{"controller":"colleges", "method":"","sub_stream_id":"3","city_cluster_area_id":"2"}', 32, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(88, 'Harbour Cluster', 'Goregaon to Vile Parle', '{"controller":"colleges", "method":"","sub_stream_id":"3","city_cluster_area_id":"3"}', 32, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(89, 'Harbour Cluster', 'Santacruz to Dadar', '{"controller":"colleges", "method":"","sub_stream_id":"3","city_cluster_area_id":"4"}', 32, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(90, 'Harbour Cluster', 'Elphinstone to Churchgate', '{"controller":"colleges", "method":"","sub_stream_id":"3","city_cluster_area_id":"5"}', 32, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tax_groups`
--

CREATE TABLE `tax_groups` (
  `id` int(11) NOT NULL,
  `tax_group` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tax_group_items`
--

CREATE TABLE `tax_group_items` (
  `id` int(11) NOT NULL,
  `tax_group_item` varchar(255) NOT NULL,
  `tax_group_id` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `trans`
--

CREATE TABLE `trans` (
  `id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_documents`
--

CREATE TABLE `user_documents` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_type` enum('employees','customers','suppliers','') NOT NULL,
  `document_id` int(11) NOT NULL,
  `file` varchar(255) NOT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_documents`
--

INSERT INTO `user_documents` (`id`, `user_id`, `user_type`, `document_id`, `file`, `is_active`, `created`, `modified`) VALUES
(1, 51, 'employees', 1, 'Sunham_Moderate_unfilled_Testing_Protocol_V1_Jan_1711.pdf', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 51, 'employees', 2, 'Sunham_Rugs_Testing_Protocol_V2_July_1711.pdf', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 51, 'employees', 3, 'Sunham_Towels_Testing_Protocol_V1_Jan_1711.pdf', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 51, 'employees', 4, '001684472425_(1)5.pdf', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 51, 'employees', 5, '001684472425_(1)14.pdf', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 51, 'employees', 5, '001684472425_(1)15.pdf', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 51, 'employees', 5, '001684472425_(1)16.pdf', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(8, 52, 'employees', 1, '001684472425_(2).pdf', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(9, 5, 'customers', 1, 'Sunham_Moderate_unfilled_Testing_Protocol_V1_Jan_1712.pdf', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10, 5, 'customers', 2, 'Sunham_Rugs_Testing_Protocol_V2_July_1712.pdf', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `vendor`
--

CREATE TABLE `vendor` (
  `id` int(11) NOT NULL,
  `vendor_name` varchar(255) NOT NULL,
  `vendor_email` varchar(255) NOT NULL,
  `vendor_contact` varchar(12) NOT NULL,
  `address` varchar(255) NOT NULL,
  `manufacturer` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `areas`
--
ALTER TABLE `areas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `assets`
--
ALTER TABLE `assets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bank_accounts`
--
ALTER TABLE `bank_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blood_group`
--
ALTER TABLE `blood_group`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `conveyances`
--
ALTER TABLE `conveyances`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `conveyance_pay_groups`
--
ALTER TABLE `conveyance_pay_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_sites`
--
ALTER TABLE `customer_sites`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `designations`
--
ALTER TABLE `designations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dumps`
--
ALTER TABLE `dumps`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dump_details`
--
ALTER TABLE `dump_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dyn_groups`
--
ALTER TABLE `dyn_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_whitelist`
--
ALTER TABLE `email_whitelist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `emp_code` (`emp_code`);

--
-- Indexes for table `employees_roles`
--
ALTER TABLE `employees_roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employees_salaries`
--
ALTER TABLE `employees_salaries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `floors`
--
ALTER TABLE `floors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gl_trans`
--
ALTER TABLE `gl_trans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ip_blacklist`
--
ALTER TABLE `ip_blacklist`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ip_address` (`ip_address`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`is`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`,`email`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu_list`
--
ALTER TABLE `menu_list`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pay_groups`
--
ALTER TABLE `pay_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_details`
--
ALTER TABLE `purchase_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `salary_deductions`
--
ALTER TABLE `salary_deductions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales_details`
--
ALTER TABLE `sales_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `supporting_module`
--
ALTER TABLE `supporting_module`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tab_contents`
--
ALTER TABLE `tab_contents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tax_groups`
--
ALTER TABLE `tax_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tax_group_items`
--
ALTER TABLE `tax_group_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trans`
--
ALTER TABLE `trans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_documents`
--
ALTER TABLE `user_documents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendor`
--
ALTER TABLE `vendor`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT for table `areas`
--
ALTER TABLE `areas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;
--
-- AUTO_INCREMENT for table `assets`
--
ALTER TABLE `assets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `bank_accounts`
--
ALTER TABLE `bank_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `blood_group`
--
ALTER TABLE `blood_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1216;
--
-- AUTO_INCREMENT for table `conveyances`
--
ALTER TABLE `conveyances`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `conveyance_pay_groups`
--
ALTER TABLE `conveyance_pay_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=251;
--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `customer_sites`
--
ALTER TABLE `customer_sites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `designations`
--
ALTER TABLE `designations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `dumps`
--
ALTER TABLE `dumps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `dump_details`
--
ALTER TABLE `dump_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `dyn_groups`
--
ALTER TABLE `dyn_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `email_whitelist`
--
ALTER TABLE `email_whitelist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;
--
-- AUTO_INCREMENT for table `employees_roles`
--
ALTER TABLE `employees_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `employees_salaries`
--
ALTER TABLE `employees_salaries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `floors`
--
ALTER TABLE `floors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `gl_trans`
--
ALTER TABLE `gl_trans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ip_blacklist`
--
ALTER TABLE `ip_blacklist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `is` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `menu_list`
--
ALTER TABLE `menu_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `modules`
--
ALTER TABLE `modules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pay_groups`
--
ALTER TABLE `pay_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `purchase_details`
--
ALTER TABLE `purchase_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `salary_deductions`
--
ALTER TABLE `salary_deductions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sales_details`
--
ALTER TABLE `sales_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
--
-- AUTO_INCREMENT for table `stock_movements`
--
ALTER TABLE `stock_movements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `supporting_module`
--
ALTER TABLE `supporting_module`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tab_contents`
--
ALTER TABLE `tab_contents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;
--
-- AUTO_INCREMENT for table `tax_groups`
--
ALTER TABLE `tax_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tax_group_items`
--
ALTER TABLE `tax_group_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `trans`
--
ALTER TABLE `trans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_documents`
--
ALTER TABLE `user_documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `vendor`
--
ALTER TABLE `vendor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
