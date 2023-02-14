-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 30, 2019 at 04:04 AM
-- Server version: 10.1.36-MariaDB
-- PHP Version: 5.6.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cubicle`
--

-- --------------------------------------------------------

--
-- Table structure for table `lup_customer_type`
--

CREATE TABLE `lup_customer_type` (
  `customer_type_id` int(5) NOT NULL,
  `customer_type_code` varchar(10) DEFAULT NULL,
  `customer_type_name` varchar(100) DEFAULT NULL,
  `created_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `isdeleted` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `lup_customer_type`
--

INSERT INTO `lup_customer_type` (`customer_type_id`, `customer_type_code`, `customer_type_name`, `created_modified`, `isdeleted`) VALUES
(1, '101', 'DEALER', '2019-10-30 00:32:16', 0),
(2, '102', 'RESELLER', '2019-10-30 00:32:16', 0),
(3, '103', 'DISTRIBUTOR', '2019-10-30 00:32:48', 0),
(4, '104', 'STARTER KIT', '2019-10-30 00:32:48', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `lup_customer_type`
--
ALTER TABLE `lup_customer_type`
  ADD PRIMARY KEY (`customer_type_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `lup_customer_type`
--
ALTER TABLE `lup_customer_type`
  MODIFY `customer_type_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
