-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 30, 2019 at 10:21 AM
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
-- Table structure for table `order_transaction`
--

CREATE TABLE `order_transaction` (
  `order_transaction_id` int(10) NOT NULL,
  `result` varchar(50) NOT NULL,
  `order_transaction_no` varchar(10) DEFAULT NULL,
  `order_transaction_date` varchar(50) NOT NULL,
  `customer_id` int(10) DEFAULT NULL,
  `customer_shipping_address_id` int(10) DEFAULT NULL,
  `ship_to` varchar(150) DEFAULT NULL,
  `total_amount` double(12,2) DEFAULT NULL,
  `remarks_order` varchar(250) DEFAULT NULL,
  `remarks_shipping` varchar(250) DEFAULT NULL,
  `sales_agent_id` int(5) DEFAULT NULL,
  `team_id` int(11) NOT NULL,
  `remittance_center_id` int(5) DEFAULT '0',
  `bank_id` int(5) DEFAULT '0',
  `courier_id` int(5) DEFAULT '0',
  `sender` varchar(50) NOT NULL,
  `payment_method_id` int(11) NOT NULL,
  `reference_payment` varchar(50) NOT NULL,
  `reference_no_courier` varchar(50) NOT NULL,
  `reference_payment_image` varchar(250) DEFAULT NULL,
  `reference_image_courier` varchar(250) DEFAULT NULL,
  `status_remittance` varchar(50) DEFAULT NULL,
  `status_collection` varchar(50) DEFAULT '0',
  `status_shipping` int(11) DEFAULT '0',
  `status_order_transaction` varchar(50) DEFAULT NULL,
  `created_by_order_transaction` varchar(150) DEFAULT NULL,
  `datetime_created` datetime DEFAULT NULL,
  `updated_by_remittance` varchar(150) DEFAULT NULL,
  `updated_by_bank` varchar(150) DEFAULT NULL,
  `updated_by_courier` varchar(150) DEFAULT NULL,
  `datetime_remittance_updated` datetime DEFAULT NULL,
  `datetime_collection_updated` datetime DEFAULT NULL,
  `datetime_shipping_updated` datetime DEFAULT NULL,
  `created_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `order_transaction`
--
ALTER TABLE `order_transaction`
  ADD PRIMARY KEY (`order_transaction_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `customer_shipping_address_id` (`customer_shipping_address_id`),
  ADD KEY `sales_agent_id` (`sales_agent_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `order_transaction`
--
ALTER TABLE `order_transaction`
  MODIFY `order_transaction_id` int(10) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
