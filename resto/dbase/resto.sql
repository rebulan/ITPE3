-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 14, 2023 at 10:01 AM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `resto`
--

-- --------------------------------------------------------

--
-- Table structure for table `card_profile`
--

CREATE TABLE IF NOT EXISTS `card_profile` (
  `card_profile_id` int(10) NOT NULL,
  `card_profile_no` varchar(10) DEFAULT NULL,
  `result` varchar(50) NOT NULL,
  `card_number` varchar(50) DEFAULT NULL,
  `card_name` varchar(150) DEFAULT NULL,
  `card_type_id` int(1) DEFAULT NULL,
  `datetime_created` datetime DEFAULT NULL,
  `created_by_fullname` varchar(150) DEFAULT NULL,
  `valid_from` date DEFAULT NULL,
  `valid_to` date DEFAULT NULL,
  `with_validity` int(1) DEFAULT NULL,
  `remarks` varchar(150) DEFAULT NULL,
  `occupied` int(11) NOT NULL,
  `isdeleted` int(1) DEFAULT NULL,
  `created_modified` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `card_profile_history`
--

CREATE TABLE IF NOT EXISTS `card_profile_history` (
  `card_profile_history_id` int(10) NOT NULL,
  `card_profile_id` int(10) DEFAULT NULL,
  `card_profile_no` varchar(10) DEFAULT NULL,
  `card_number` varchar(50) DEFAULT NULL,
  `card_name` varchar(150) DEFAULT NULL,
  `card_type_id` int(1) DEFAULT NULL,
  `datetime_created` datetime DEFAULT NULL,
  `created_by_fullname` varchar(150) DEFAULT NULL,
  `valid_from` date DEFAULT NULL,
  `valid_to` date DEFAULT NULL,
  `with_validity` int(1) DEFAULT NULL,
  `remarks` varchar(150) DEFAULT NULL,
  `isdeleted` int(1) DEFAULT NULL,
  `created_modified` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `credit_line_allocation`
--

CREATE TABLE IF NOT EXISTS `credit_line_allocation` (
  `credit_line_allocation_id` int(10) NOT NULL,
  `result` varchar(50) NOT NULL,
  `credit_line_allocation_no` varchar(10) DEFAULT NULL,
  `allocation_date` date DEFAULT NULL,
  `registration_id` int(10) DEFAULT NULL,
  `customer_id` int(10) DEFAULT NULL,
  `credit_line_limit_id` int(3) DEFAULT NULL,
  `credit_line_limit_amount` double(12,2) DEFAULT NULL,
  `valid_from` date DEFAULT NULL,
  `valid_to` date DEFAULT NULL,
  `remarks` varchar(50) DEFAULT NULL,
  `user_id` int(5) DEFAULT NULL,
  `isdeleted` int(3) NOT NULL DEFAULT '0',
  `created_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `credit_line_allocation_history`
--

CREATE TABLE IF NOT EXISTS `credit_line_allocation_history` (
  `credit_line_allocation_history_id` int(10) NOT NULL,
  `credit_line_allocation_id` int(10) NOT NULL DEFAULT '0',
  `credit_line_allocation_no` varchar(10) DEFAULT NULL,
  `allocation_date` date DEFAULT NULL,
  `registration_id` int(10) DEFAULT NULL,
  `customer_id` int(10) DEFAULT NULL,
  `credit_line_limit_id` int(3) DEFAULT NULL,
  `credit_line_limit_amount` double(12,2) DEFAULT NULL,
  `valid_from` date DEFAULT NULL,
  `valid_to` date DEFAULT NULL,
  `remarks` varchar(50) DEFAULT NULL,
  `user_id` int(5) DEFAULT NULL,
  `isdeleted` int(3) NOT NULL DEFAULT '0',
  `created_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Column 14` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `credit_line_transaction`
--

CREATE TABLE IF NOT EXISTS `credit_line_transaction` (
  `credit_line_transaction_id` int(10) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `result` varchar(50) NOT NULL,
  `transaction_no` varchar(15) DEFAULT NULL,
  `transaction_date` date DEFAULT NULL,
  `customer_id` int(10) DEFAULT NULL,
  `credit_line_transaction_type_id` int(3) DEFAULT NULL,
  `transaction_apply_to` varchar(20) DEFAULT NULL COMMENT 'credit_allocation_no + timestamp',
  `transaction_amount` double(12,2) DEFAULT NULL,
  `source_id` int(10) DEFAULT NULL,
  `source_no` varchar(15) DEFAULT NULL,
  `source_remarks` varchar(150) DEFAULT NULL,
  `transaction_remarks` varchar(150) DEFAULT NULL,
  `created_by_fullname` varchar(50) DEFAULT NULL,
  `isdeleted` int(11) NOT NULL,
  `created_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customer_address`
--

CREATE TABLE IF NOT EXISTS `customer_address` (
  `customer_address_id` int(10) NOT NULL,
  `customer_id` int(10) DEFAULT NULL,
  `street_name` varchar(250) DEFAULT NULL,
  `barangay_id` int(5) DEFAULT NULL,
  `city_town_id` int(5) DEFAULT NULL,
  `province_id` int(5) DEFAULT NULL,
  `region_id` int(5) DEFAULT NULL,
  `remarks` varchar(250) DEFAULT NULL,
  `created_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `isdeleted` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customer_profile`
--

CREATE TABLE IF NOT EXISTS `customer_profile` (
  `customer_id` int(10) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `customer_no` varchar(15) DEFAULT NULL,
  `customer_type_id` int(3) NOT NULL,
  `referral` int(11) NOT NULL,
  `reference_no` varchar(50) NOT NULL,
  `result` varchar(50) NOT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `middlename` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `business_name` varchar(100) DEFAULT NULL,
  `contact_person` varchar(150) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `birthdate` varchar(50) DEFAULT NULL,
  `home_address` varchar(512) NOT NULL,
  `contact_no1` varchar(20) DEFAULT NULL,
  `contact_no2` varchar(20) DEFAULT NULL,
  `contact_no3` varchar(20) DEFAULT NULL,
  `email_address` varchar(50) NOT NULL,
  `social_media1` varchar(50) DEFAULT NULL,
  `social_media2` varchar(50) DEFAULT NULL,
  `social_media3` varchar(50) DEFAULT NULL,
  `is_non_member` int(11) NOT NULL,
  `created_by_fullname` varchar(150) DEFAULT NULL,
  `datetime_created` datetime DEFAULT NULL,
  `edited_by_fullname` varchar(150) DEFAULT NULL,
  `datetime_edited` datetime DEFAULT NULL,
  `isdeleted` int(1) DEFAULT '0',
  `created_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customer_profile_history`
--

CREATE TABLE IF NOT EXISTS `customer_profile_history` (
  `customer_history_id` int(10) NOT NULL,
  `customer_id` int(10) DEFAULT NULL,
  `customer_no` varchar(15) DEFAULT NULL,
  `customer_type_id` int(3) DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `middlename` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `business_name` varchar(100) DEFAULT NULL,
  `contact_person` varchar(150) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `contact_no1` varchar(20) DEFAULT NULL,
  `contact_no2` varchar(20) DEFAULT NULL,
  `contact_no3` varchar(20) DEFAULT NULL,
  `social_media1` varchar(50) DEFAULT NULL,
  `social_media2` varchar(50) DEFAULT NULL,
  `social_media3` varchar(50) DEFAULT NULL,
  `created_by_fullname` varchar(150) DEFAULT NULL,
  `datetime_created` datetime DEFAULT NULL,
  `edited_by_fullname` varchar(150) DEFAULT NULL,
  `datetime_edited` datetime DEFAULT NULL,
  `isdeleted` int(1) DEFAULT '0',
  `created_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `customer_shipping_address`
--

CREATE TABLE IF NOT EXISTS `customer_shipping_address` (
  `customer_shipping_address_id` int(10) NOT NULL,
  `customer_id` int(10) DEFAULT NULL,
  `street_name` varchar(250) DEFAULT NULL,
  `barangay_id` int(5) DEFAULT NULL,
  `city_town_id` int(5) DEFAULT NULL,
  `province_id` int(5) DEFAULT NULL,
  `region_id` int(5) DEFAULT NULL,
  `remarks` varchar(250) DEFAULT NULL,
  `created_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `isdeleted` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `delivery_status`
--

CREATE TABLE IF NOT EXISTS `delivery_status` (
  `delivery_status_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_status_id` int(11) NOT NULL,
  `order_status` varchar(50) NOT NULL,
  `sms` varchar(255) NOT NULL,
  `pos_sales_id` int(11) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `isdeleted` int(11) NOT NULL,
  PRIMARY KEY (`delivery_status_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `inv_delivery_details`
--

CREATE TABLE IF NOT EXISTS `inv_delivery_details` (
  `delivery_id` int(11) NOT NULL AUTO_INCREMENT,
  `delivery_invoice_number` varchar(255) NOT NULL,
  `amount` double NOT NULL,
  `payment_terms` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `delivery_date` datetime NOT NULL,
  `date_added` varchar(255) NOT NULL,
  `added_by` int(11) NOT NULL,
  `isdeleted` int(11) NOT NULL,
  PRIMARY KEY (`delivery_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `inv_lup_location`
--

CREATE TABLE IF NOT EXISTS `inv_lup_location` (
  `location_id` int(5) NOT NULL,
  `location_code` varchar(10) DEFAULT NULL,
  `location_description` varchar(50) DEFAULT NULL,
  `visible` int(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `inv_lup_transaction_type`
--

CREATE TABLE IF NOT EXISTS `inv_lup_transaction_type` (
  `transaction_type_id` int(5) NOT NULL AUTO_INCREMENT,
  `transaction_type_code` varchar(5) NOT NULL,
  `transaction_type_description` varchar(20) NOT NULL,
  `visible` int(1) NOT NULL,
  `issales` int(11) NOT NULL,
  `isreturn` int(11) NOT NULL,
  `inventory` varchar(5) NOT NULL,
  PRIMARY KEY (`transaction_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `inv_lup_transaction_type`
--

INSERT INTO `inv_lup_transaction_type` (`transaction_type_id`, `transaction_type_code`, `transaction_type_description`, `visible`, `issales`, `isreturn`, `inventory`) VALUES
(1, 'OI', 'BEGINNING INVENTORY', 1, 0, 0, 'IN'),
(2, 'REC', 'RECEIVING', 1, 0, 0, 'IN'),
(3, 'ADJ-I', 'ADJUSTMENT (IN)', 1, 0, 0, 'IN'),
(4, 'SC', 'SERVICE CONNECTION', 0, 0, 0, 'OUT'),
(5, 'PO', 'PURCHASE ORDER', 0, 0, 0, 'OUT'),
(7, 'ADJ-O', 'ADJUSTMENT (OUT)', 1, 0, 0, 'OUT'),
(10, 'ADJ-O', 'ADJUSTMENT (OUT)', 0, 0, 0, 'OUT'),
(11, 'DEV', 'DELIVERY', 1, 0, 0, 'IN'),
(12, 'SALE', 'SALES', 1, 1, 0, 'OUT'),
(13, 'RET', 'RETURN', 1, 0, 1, 'OUT');

-- --------------------------------------------------------

--
-- Table structure for table `inv_lup_unit`
--

CREATE TABLE IF NOT EXISTS `inv_lup_unit` (
  `unit_id` int(11) NOT NULL AUTO_INCREMENT,
  `unit_code` varchar(10) DEFAULT NULL,
  `unit_description` varchar(50) DEFAULT NULL,
  `visible` int(1) DEFAULT '1',
  `isdeleted` int(11) NOT NULL,
  PRIMARY KEY (`unit_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `inv_lup_unit`
--

INSERT INTO `inv_lup_unit` (`unit_id`, `unit_code`, `unit_description`, `visible`, `isdeleted`) VALUES
(1, '-', '-', 1, 0),
(2, '0001', 'PCS', 1, 0),
(3, '0002', 'BOX', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `inv_transaction`
--

CREATE TABLE IF NOT EXISTS `inv_transaction` (
  `transaction_id` int(10) NOT NULL AUTO_INCREMENT,
  `transaction_no` varchar(255) DEFAULT NULL,
  `branch_id` int(11) NOT NULL,
  `delivery_id` int(11) NOT NULL,
  `transaction_date` datetime DEFAULT NULL,
  `expiration_date` varchar(255) NOT NULL,
  `transaction_type_id` int(5) DEFAULT NULL,
  `location_id` int(5) DEFAULT NULL,
  `item_id` int(10) DEFAULT NULL,
  `unit_id` int(5) DEFAULT NULL,
  `unit_cost` double(12,2) DEFAULT NULL,
  `markup` double NOT NULL,
  `quantity` double(12,2) DEFAULT NULL,
  `reference_no` varchar(20) DEFAULT NULL,
  `remarks` varchar(150) DEFAULT NULL,
  `reference_date1` date DEFAULT NULL,
  `created_by` int(5) DEFAULT NULL,
  `created_by_datetime` datetime DEFAULT NULL,
  `edited_by` int(5) DEFAULT NULL,
  `edited_by_datetime` datetime DEFAULT NULL,
  `created_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `reference_id1` int(10) DEFAULT NULL,
  `reference_id2` int(10) DEFAULT NULL,
  `purpose` varchar(250) DEFAULT NULL,
  `isdeleted` int(11) NOT NULL,
  PRIMARY KEY (`transaction_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

--
-- Dumping data for table `inv_transaction`
--

INSERT INTO `inv_transaction` (`transaction_id`, `transaction_no`, `branch_id`, `delivery_id`, `transaction_date`, `expiration_date`, `transaction_type_id`, `location_id`, `item_id`, `unit_id`, `unit_cost`, `markup`, `quantity`, `reference_no`, `remarks`, `reference_date1`, `created_by`, `created_by_datetime`, `edited_by`, `edited_by_datetime`, `created_modified`, `reference_id1`, `reference_id2`, `purpose`, `isdeleted`) VALUES
(1, '0000000001', 0, 0, '2023-02-12 15:54:44', '', 12, 1, 1, 1, 0.00, 0, -1.00, NULL, 'TRANSACTION FROM SALES', NULL, 1, '2023-02-12 15:54:44', NULL, NULL, '2023-02-12 07:54:44', 1, NULL, NULL, 0),
(2, '0000000002', 0, 0, '2023-02-12 15:54:44', '', 12, 1, 77, 0, 0.00, 0, -1.00, NULL, 'TRANSACTION FROM SALES', NULL, 1, '2023-02-12 15:54:44', NULL, NULL, '2023-02-12 07:54:44', 2, NULL, NULL, 0),
(3, '0000000003', 0, 0, '2023-02-12 15:56:49', '', 12, 1, 1, 1, 0.00, 0, -2.00, NULL, 'TRANSACTION FROM SALES', NULL, 1, '2023-02-12 15:56:49', NULL, NULL, '2023-02-12 07:56:49', 5, NULL, NULL, 0),
(4, '0000000004', 0, 0, '2023-02-12 15:56:49', '', 12, 1, 78, 0, 0.00, 0, -2.00, NULL, 'TRANSACTION FROM SALES', NULL, 1, '2023-02-12 15:56:49', NULL, NULL, '2023-02-12 07:56:49', 6, NULL, NULL, 0),
(5, '0000000005', 0, 0, '2023-02-12 16:11:16', '', 12, 1, 1, 1, 0.00, 0, -2.00, NULL, 'TRANSACTION FROM SALES', NULL, 1, '2023-02-12 16:11:16', NULL, NULL, '2023-02-12 08:11:16', 7, NULL, NULL, 0),
(6, '0000000006', 0, 0, '2023-02-12 16:11:16', '', 12, 1, 77, 0, 0.00, 0, -2.00, NULL, 'TRANSACTION FROM SALES', NULL, 1, '2023-02-12 16:11:16', NULL, NULL, '2023-02-12 08:11:16', 8, NULL, NULL, 0),
(7, '0000000007', 0, 0, '2023-02-12 17:12:34', '', 12, 1, 1, 1, 0.00, 0, -1.00, NULL, 'TRANSACTION FROM SALES', NULL, 1, '2023-02-12 17:12:34', NULL, NULL, '2023-02-12 09:12:34', 9, NULL, NULL, 0),
(8, '0000000008', 0, 0, '2023-02-12 17:12:34', '', 12, 1, 77, 0, 0.00, 0, -1.00, NULL, 'TRANSACTION FROM SALES', NULL, 1, '2023-02-12 17:12:34', NULL, NULL, '2023-02-12 09:12:34', 10, NULL, NULL, 0),
(9, '0000000009', 0, 0, '2023-02-13 05:37:42', '', 12, 1, 1, 1, 0.00, 0, -10.00, NULL, 'TRANSACTION FROM SALES', NULL, 1, '2023-02-13 05:37:42', NULL, NULL, '2023-02-12 21:37:42', 1, NULL, NULL, 0),
(10, '0000000010', 0, 0, '2023-02-13 05:37:42', '', 12, 1, 77, 0, 0.00, 0, -5.00, NULL, 'TRANSACTION FROM SALES', NULL, 1, '2023-02-13 05:37:42', NULL, NULL, '2023-02-12 21:37:42', 2, NULL, NULL, 0),
(11, '0000000011', 0, 0, '2023-02-13 05:39:52', '', 12, 1, 1, 1, 0.00, 0, -5.00, NULL, 'TRANSACTION FROM SALES', NULL, 1, '2023-02-13 05:39:52', NULL, NULL, '2023-02-12 21:39:52', 3, NULL, NULL, 0),
(12, '0000000012', 0, 0, '2023-02-13 05:39:52', '', 12, 1, 77, 0, 0.00, 0, -5.00, NULL, 'TRANSACTION FROM SALES', NULL, 1, '2023-02-13 05:39:52', NULL, NULL, '2023-02-12 21:39:52', 4, NULL, NULL, 0),
(13, '0000000013', 0, 0, '2023-02-13 05:48:53', '', 12, 1, 1, 1, 0.00, 0, -1.00, NULL, 'TRANSACTION FROM SALES', NULL, 1, '2023-02-13 05:48:53', NULL, NULL, '2023-02-12 21:48:53', 5, NULL, NULL, 0),
(14, '0000000014', 0, 0, '2023-02-13 05:48:53', '', 12, 1, 77, 0, 0.00, 0, -1.00, NULL, 'TRANSACTION FROM SALES', NULL, 1, '2023-02-13 05:48:53', NULL, NULL, '2023-02-12 21:48:53', 6, NULL, NULL, 0),
(15, '0000000015', 0, 0, '2023-02-13 05:48:53', '', 12, 1, 78, 0, 0.00, 0, -1.00, NULL, 'TRANSACTION FROM SALES', NULL, 1, '2023-02-13 05:48:53', NULL, NULL, '2023-02-12 21:48:53', 7, NULL, NULL, 0),
(16, '0000000016', 0, 0, '2023-02-13 05:50:57', '', 12, 1, 1, 1, 0.00, 0, -5.00, NULL, 'TRANSACTION FROM SALES', NULL, 1, '2023-02-13 05:50:57', NULL, NULL, '2023-02-12 21:50:57', 8, NULL, NULL, 0),
(17, '0000000017', 0, 0, '2023-02-13 05:50:57', '', 12, 1, 77, 0, 0.00, 0, -5.00, NULL, 'TRANSACTION FROM SALES', NULL, 1, '2023-02-13 05:50:57', NULL, NULL, '2023-02-12 21:50:57', 9, NULL, NULL, 0),
(18, '0000000018', 0, 0, '2023-02-13 06:14:35', '', 12, 1, 1, 1, 0.00, 0, -5.00, NULL, 'TRANSACTION FROM SALES', NULL, 1, '2023-02-13 06:14:35', NULL, NULL, '2023-02-12 22:14:35', 12, NULL, NULL, 0),
(19, '0000000019', 0, 0, '2023-02-13 06:14:35', '', 12, 1, 77, 0, 0.00, 0, -5.00, NULL, 'TRANSACTION FROM SALES', NULL, 1, '2023-02-13 06:14:35', NULL, NULL, '2023-02-12 22:14:35', 13, NULL, NULL, 0),
(20, '0000000020', 0, 0, '2023-02-13 06:15:11', '', 12, 1, 1, 1, 0.00, 0, -1.00, NULL, 'TRANSACTION FROM SALES', NULL, 1, '2023-02-13 06:15:11', NULL, NULL, '2023-02-12 22:15:11', 10, NULL, NULL, 0),
(21, '0000000021', 0, 0, '2023-02-13 06:15:11', '', 12, 1, 77, 0, 0.00, 0, -1.00, NULL, 'TRANSACTION FROM SALES', NULL, 1, '2023-02-13 06:15:11', NULL, NULL, '2023-02-12 22:15:11', 11, NULL, NULL, 0),
(22, '0000000022', 0, 0, '2023-02-13 11:14:40', '', 12, 1, 1, 1, 0.00, 0, -2.00, NULL, 'TRANSACTION FROM SALES', NULL, 1, '2023-02-13 11:14:40', NULL, NULL, '2023-02-13 03:14:40', 1, NULL, NULL, 0),
(23, '0000000023', 0, 0, '2023-02-13 11:14:40', '', 12, 1, 77, 0, 0.00, 0, -2.00, NULL, 'TRANSACTION FROM SALES', NULL, 1, '2023-02-13 11:14:40', NULL, NULL, '2023-02-13 03:14:40', 2, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `inv_transaction_history`
--

CREATE TABLE IF NOT EXISTS `inv_transaction_history` (
  `transaction_history_id` int(10) NOT NULL,
  `transaction_id` int(10) DEFAULT NULL,
  `transaction_no` varchar(10) DEFAULT NULL,
  `transaction_date` date DEFAULT NULL,
  `transaction_type_id` int(5) DEFAULT NULL,
  `location_id` int(5) DEFAULT NULL,
  `item_id` int(10) DEFAULT NULL,
  `unit_id` int(5) DEFAULT NULL,
  `unit_cost` double(12,2) DEFAULT NULL,
  `quantity` double(12,2) DEFAULT NULL,
  `reference_no` varchar(20) DEFAULT NULL,
  `remarks` varchar(150) DEFAULT NULL,
  `reference_date1` date DEFAULT NULL,
  `created_by` int(5) DEFAULT NULL,
  `created_by_datetime` datetime DEFAULT NULL,
  `edited_by` int(5) DEFAULT NULL,
  `edited_by_datetime` datetime DEFAULT NULL,
  `created_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `reference_id1` int(10) DEFAULT NULL,
  `reference_id2` int(10) DEFAULT NULL,
  `purpose` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `ledger_rebate`
--

CREATE TABLE IF NOT EXISTS `ledger_rebate` (
  `rebate_id` int(10) NOT NULL,
  `result` varchar(50) NOT NULL,
  `rebate_no` varchar(15) DEFAULT NULL,
  `rebate_date` date DEFAULT NULL,
  `branch_id` int(5) DEFAULT NULL,
  `customer_id` int(10) DEFAULT NULL,
  `pos_sales_id` int(10) DEFAULT NULL,
  `sales_invoice_number` varchar(15) DEFAULT NULL,
  `total_sales` double(12,2) DEFAULT NULL,
  `sales_with_rebate` double(12,2) DEFAULT NULL,
  `rebate` double(12,2) DEFAULT NULL,
  `rate_sales` double(12,2) DEFAULT NULL,
  `rate_points` double(12,2) DEFAULT NULL,
  `remarks_sales` varchar(50) NOT NULL,
  `isdeleted` int(1) DEFAULT '0',
  `created_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ledger_rebate`
--

INSERT INTO `ledger_rebate` (`rebate_id`, `result`, `rebate_no`, `rebate_date`, `branch_id`, `customer_id`, `pos_sales_id`, `sales_invoice_number`, `total_sales`, `sales_with_rebate`, `rebate`, `rate_sales`, `rate_points`, `remarks_sales`, `isdeleted`, `created_modified`) VALUES
(0, '', '0000000000', '2023-02-13', 1, 0, 1, '0000000001', 170.00, 170.00, 2.27, 150.00, 2.00, '1', 0, '2023-02-12 07:54:44'),
(0, '', '0000000000', '2023-02-13', 1, 0, 2, '0000000002', 340.00, 340.00, 4.53, 150.00, 2.00, '2', 1, '2023-02-12 07:56:49'),
(0, '', '0000000000', '2023-02-13', 1, 0, 3, '0000000003', 340.00, 340.00, 4.53, 150.00, 2.00, '3', 0, '2023-02-12 08:11:16'),
(0, '', '0000000000', '2023-02-13', 1, 0, 4, '0000000004', 170.00, 170.00, 2.27, 150.00, 2.00, '4', 0, '2023-02-12 09:12:34'),
(0, '', '0000000000', '2023-02-13', 1, 0, 1, '0000000001', 1800.00, 1970.00, 26.27, 150.00, 2.00, '1', 0, '2023-02-12 21:37:42'),
(0, '', '0000000000', '2023-02-13', 1, 0, 2, '0000000002', 950.00, 950.00, 12.67, 150.00, 2.00, '2', 0, '2023-02-12 21:39:52'),
(0, '', '0000000000', '2023-02-13', 1, 0, 3, '0000000003', 190.00, 190.00, 2.53, 150.00, 2.00, '3', 0, '2023-02-12 21:48:53'),
(0, '', '0000000000', '2023-02-13', 1, 0, 4, '0000000004', 850.00, 850.00, 11.33, 150.00, 2.00, '4', 0, '2023-02-12 21:50:57'),
(0, '', '0000000000', '2023-02-13', 1, 0, 6, '0000000005', 850.00, 1000.00, 13.33, 150.00, 2.00, '6', 0, '2023-02-12 22:14:35'),
(0, '', '0000000000', '2023-02-13', 1, 0, 5, '0000000007', 170.00, 170.00, 2.27, 150.00, 2.00, '5', 0, '2023-02-12 22:15:11'),
(0, '', '0000000000', '2023-02-13', 1, 0, 2, '0000000001', 340.00, 340.00, 4.53, 150.00, 2.00, '2', 0, '2023-02-13 03:14:40');

-- --------------------------------------------------------

--
-- Table structure for table `ledger_receivable`
--

CREATE TABLE IF NOT EXISTS `ledger_receivable` (
  `receivable_id` int(10) NOT NULL AUTO_INCREMENT,
  `result` varchar(50) NOT NULL,
  `receivable_no` varchar(15) DEFAULT NULL,
  `branch_id` int(5) DEFAULT NULL,
  `customer_id` int(10) DEFAULT NULL,
  `transaction_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `settlement_type_id` int(5) DEFAULT NULL,
  `transaction_amount` double(12,2) DEFAULT NULL,
  `transaction_apply_to` varchar(20) DEFAULT NULL,
  `transaction_source_number` varchar(20) DEFAULT NULL,
  `transaction_source` varchar(20) DEFAULT NULL,
  `proof_of_payment` varchar(100) DEFAULT NULL,
  `remarks_sales` varchar(150) DEFAULT NULL,
  `remarks_payment` varchar(150) DEFAULT NULL,
  `sales_income_id` int(11) NOT NULL,
  `isdeleted` int(1) DEFAULT '0',
  `created_by_fullname` varchar(100) DEFAULT NULL,
  `created_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`receivable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ledger_sales_income`
--

CREATE TABLE IF NOT EXISTS `ledger_sales_income` (
  `sales_income_id` int(10) NOT NULL AUTO_INCREMENT,
  `result` varchar(50) NOT NULL,
  `sales_income_no` varchar(15) DEFAULT NULL,
  `branch_id` int(5) DEFAULT NULL,
  `customer_id` int(10) DEFAULT NULL,
  `sales_income_date` date DEFAULT NULL,
  `pos_sales_id` int(10) DEFAULT NULL,
  `settlement_type_id` int(5) DEFAULT NULL,
  `amount` double(12,2) DEFAULT NULL,
  `sales_reference_no` varchar(20) DEFAULT NULL,
  `payment_reference_no` varchar(20) DEFAULT NULL,
  `transaction_type_id` int(5) DEFAULT NULL,
  `verified` int(1) DEFAULT NULL,
  `verified_datetime` datetime DEFAULT NULL,
  `verified_by_fullname` varchar(100) DEFAULT NULL,
  `proof_of_payment` varchar(100) DEFAULT NULL,
  `isdeleted` int(1) DEFAULT NULL,
  `datetime_deleted` datetime DEFAULT NULL,
  `deleted_by_fullname` varchar(100) DEFAULT NULL,
  `remarks_sales` varchar(250) DEFAULT NULL,
  `remarks_payment` varchar(250) DEFAULT NULL,
  `created_by_fullname` varchar(100) DEFAULT NULL,
  `created_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`sales_income_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `ledger_sales_income`
--

INSERT INTO `ledger_sales_income` (`sales_income_id`, `result`, `sales_income_no`, `branch_id`, `customer_id`, `sales_income_date`, `pos_sales_id`, `settlement_type_id`, `amount`, `sales_reference_no`, `payment_reference_no`, `transaction_type_id`, `verified`, `verified_datetime`, `verified_by_fullname`, `proof_of_payment`, `isdeleted`, `datetime_deleted`, `deleted_by_fullname`, `remarks_sales`, `remarks_payment`, `created_by_fullname`, `created_modified`) VALUES
(1, '', '1000000000', 1, 0, '2023-02-13', 2, 1, 340.00, '', '0', 1, 0, '0000-00-00 00:00:00', '', '', 0, NULL, NULL, '1', '1', 'System Administrator', '2023-02-13 03:14:37');

-- --------------------------------------------------------

--
-- Table structure for table `lup_barangay`
--

CREATE TABLE IF NOT EXISTS `lup_barangay` (
  `barangay_id` int(5) NOT NULL,
  `barangay_code` varchar(10) DEFAULT NULL,
  `city_town_id` varchar(10) DEFAULT NULL,
  `barangay_name` varchar(50) DEFAULT NULL,
  `created_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `isdeleted` int(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `lup_branch`
--

CREATE TABLE IF NOT EXISTS `lup_branch` (
  `branch_id` int(11) NOT NULL AUTO_INCREMENT,
  `branch_code` varchar(255) DEFAULT NULL,
  `branch_description` varchar(255) DEFAULT NULL,
  `branch_photo` varchar(100) DEFAULT NULL,
  `branch_contact_person1` varchar(150) DEFAULT NULL,
  `branch_contact_person2` varchar(150) DEFAULT NULL,
  `contact_person_photo1` varchar(100) DEFAULT NULL,
  `contact_person_photo2` varchar(100) DEFAULT NULL,
  `branch_contact_number1` varchar(20) DEFAULT NULL,
  `branch_contact_number2` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `date_open` date DEFAULT NULL,
  `date_close` date DEFAULT NULL,
  `isdeleted` int(1) DEFAULT '0',
  `isactive` int(1) DEFAULT '1',
  `remarks` varchar(250) DEFAULT NULL,
  `created_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`branch_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `lup_branch`
--

INSERT INTO `lup_branch` (`branch_id`, `branch_code`, `branch_description`, `branch_photo`, `branch_contact_person1`, `branch_contact_person2`, `contact_person_photo1`, `contact_person_photo2`, `branch_contact_number1`, `branch_contact_number2`, `address`, `date_open`, `date_close`, `isdeleted`, `isactive`, `remarks`, `created_modified`) VALUES
(1, '0001', 'YOLKY TOASTY', 'ApdcwQ985w_logo.png', 'NA', 'NA', '', '', 'NA', 'NA', 'NA', '2023-02-06', '0000-00-00', 0, 1, 'from_setup', '2023-02-12 07:45:45'),
(2, '0002', 'BEREANS', 'K8Xl4KGhPd_logo2.png', 'NA', 'NA', '', '', 'NA', 'NA', 'NA', '2023-02-06', '0000-00-00', 0, 1, 'from_setup', '2023-02-12 07:45:59');

-- --------------------------------------------------------

--
-- Table structure for table `lup_card_type`
--

CREATE TABLE IF NOT EXISTS `lup_card_type` (
  `card_type_id` int(3) NOT NULL,
  `card_type_code` varchar(10) DEFAULT NULL,
  `card_type_description` varchar(50) DEFAULT NULL,
  `isdeleted` int(1) DEFAULT NULL,
  `created_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `lup_city_town`
--

CREATE TABLE IF NOT EXISTS `lup_city_town` (
  `city_town_id` int(5) NOT NULL,
  `city_town_code` varchar(10) DEFAULT NULL,
  `province_id` varchar(10) DEFAULT NULL,
  `city_town_name` varchar(100) DEFAULT NULL,
  `created_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `isdeleted` int(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `lup_courier`
--

CREATE TABLE IF NOT EXISTS `lup_courier` (
  `courier_id` int(5) NOT NULL,
  `courier_code` varchar(10) DEFAULT NULL,
  `courier_name` varchar(50) DEFAULT NULL,
  `is_international` int(11) NOT NULL,
  `created_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `isdeleted` int(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `lup_credit_line_limit`
--

CREATE TABLE IF NOT EXISTS `lup_credit_line_limit` (
  `credit_line_limit_id` int(5) NOT NULL,
  `credit_line_limit_code` varchar(10) DEFAULT NULL,
  `credit_line_limit_description` varchar(50) DEFAULT NULL,
  `credit_line_limit_amount` double(12,2) DEFAULT NULL,
  `isdeleted` int(1) DEFAULT NULL,
  `created_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `lup_credit_line_transaction_type`
--

CREATE TABLE IF NOT EXISTS `lup_credit_line_transaction_type` (
  `credit_line_transaction_type_id` int(3) NOT NULL,
  `credit_line_transaction_type_code` varchar(10) DEFAULT NULL,
  `credit_line_transaction_type_description` varchar(50) DEFAULT NULL,
  `isdeleted` int(3) DEFAULT NULL,
  `created_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `lup_customer_type`
--

CREATE TABLE IF NOT EXISTS `lup_customer_type` (
  `customer_type_id` int(5) NOT NULL AUTO_INCREMENT,
  `customer_type_code` varchar(10) DEFAULT NULL,
  `customer_type_name` varchar(100) DEFAULT NULL,
  `customer_type_group` int(11) NOT NULL,
  `created_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `isdeleted` int(1) NOT NULL,
  PRIMARY KEY (`customer_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `lup_customer_type`
--

INSERT INTO `lup_customer_type` (`customer_type_id`, `customer_type_code`, `customer_type_name`, `customer_type_group`, `created_modified`, `isdeleted`) VALUES
(1, 'MEM', 'MEMBER', 1, '2020-04-30 13:27:12', 0),
(2, 'RET', 'RETAILER', 2, '2020-04-30 13:27:36', 0),
(3, 'DEP', 'DEPOT', 3, '2020-04-30 13:27:56', 1);

-- --------------------------------------------------------

--
-- Table structure for table `lup_customer_type_group`
--

CREATE TABLE IF NOT EXISTS `lup_customer_type_group` (
  `customer_type_group_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_type_group_name` varchar(50) NOT NULL,
  `pricing` int(11) NOT NULL,
  `created_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `isdeleted` int(11) NOT NULL,
  PRIMARY KEY (`customer_type_group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `lup_customer_type_group`
--

INSERT INTO `lup_customer_type_group` (`customer_type_group_id`, `customer_type_group_name`, `pricing`, `created_modified`, `isdeleted`) VALUES
(1, 'MEMBER', 1, '2020-01-08 16:00:00', 0),
(2, 'RETAILER', 2, '2020-01-09 06:18:22', 0),
(3, 'DEPOT', 3, '2020-04-29 03:43:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `lup_invoice_number`
--

CREATE TABLE IF NOT EXISTS `lup_invoice_number` (
  `invoice_number_id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(50) NOT NULL,
  `pos_sales_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `isdeleted` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `create_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`invoice_number_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=101 ;

--
-- Dumping data for table `lup_invoice_number`
--

INSERT INTO `lup_invoice_number` (`invoice_number_id`, `invoice_number`, `pos_sales_id`, `branch_id`, `isdeleted`, `user_id`, `create_modified`) VALUES
(1, '0000000001', 2, 1, 0, 1, '2023-02-13 02:56:53'),
(2, '0000000002', 3, 1, 0, 1, '2023-02-13 02:56:53'),
(3, '0000000003', 3, 1, 0, 1, '2023-02-13 02:56:53'),
(4, '0000000004', 3, 1, 0, 1, '2023-02-13 02:56:53'),
(5, '0000000005', 3, 1, 0, 1, '2023-02-13 02:56:53'),
(6, '0000000006', 2, 1, 0, 1, '2023-02-13 02:56:53'),
(7, '0000000007', 3, 1, 0, 1, '2023-02-13 02:56:53'),
(8, '0000000008', 3, 1, 0, 1, '2023-02-13 02:56:53'),
(9, '0000000009', 3, 1, 0, 1, '2023-02-13 02:56:53'),
(10, '0000000010', 3, 1, 0, 1, '2023-02-13 02:56:53'),
(11, '0000000011', 3, 1, 0, 1, '2023-02-13 02:56:53'),
(12, '0000000012', 6, 1, 0, 1, '2023-02-13 02:56:53'),
(13, '0000000013', 5, 1, 0, 1, '2023-02-13 02:56:53'),
(14, '0000000014', 6, 1, 0, 1, '2023-02-13 02:56:53'),
(15, '0000000015', 5, 1, 0, 1, '2023-02-13 02:56:53'),
(16, '0000000016', 5, 1, 0, 1, '2023-02-13 02:56:53'),
(17, '0000000017', 6, 1, 0, 1, '2023-02-13 02:56:53'),
(18, '0000000018', 5, 1, 0, 1, '2023-02-13 02:56:53'),
(19, '0000000019', 5, 1, 0, 1, '2023-02-13 02:56:53'),
(20, '0000000020', 5, 1, 0, 1, '2023-02-13 02:56:53'),
(21, '0000000021', 6, 1, 0, 1, '2023-02-13 02:56:53'),
(22, '0000000022', 6, 1, 0, 1, '2023-02-13 02:56:53'),
(23, '0000000023', 6, 1, 0, 1, '2023-02-13 02:56:53'),
(24, '0000000024', 8, 1, 0, 1, '2023-02-13 02:56:53'),
(25, '0000000025', 9, 1, 0, 1, '2023-02-13 02:56:53'),
(26, '0000000026', 10, 1, 0, 1, '2023-02-13 02:56:53'),
(27, '0000000027', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(28, '0000000028', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(29, '0000000029', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(30, '0000000030', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(31, '0000000031', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(32, '0000000032', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(33, '0000000033', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(34, '0000000034', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(35, '0000000035', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(36, '0000000036', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(37, '0000000037', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(38, '0000000038', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(39, '0000000039', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(40, '0000000040', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(41, '0000000041', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(42, '0000000042', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(43, '0000000043', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(44, '0000000044', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(45, '0000000045', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(46, '0000000046', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(47, '0000000047', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(48, '0000000048', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(49, '0000000049', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(50, '0000000050', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(51, '0000000051', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(52, '0000000052', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(53, '0000000053', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(54, '0000000054', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(55, '0000000055', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(56, '0000000056', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(57, '0000000057', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(58, '0000000058', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(59, '0000000059', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(60, '0000000060', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(61, '0000000061', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(62, '0000000062', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(63, '0000000063', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(64, '0000000064', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(65, '0000000065', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(66, '0000000066', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(67, '0000000067', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(68, '0000000068', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(69, '0000000069', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(70, '0000000070', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(71, '0000000071', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(72, '0000000072', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(73, '0000000073', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(74, '0000000074', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(75, '0000000075', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(76, '0000000076', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(77, '0000000077', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(78, '0000000078', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(79, '0000000079', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(80, '0000000080', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(81, '0000000081', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(82, '0000000082', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(83, '0000000083', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(84, '0000000084', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(85, '0000000085', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(86, '0000000086', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(87, '0000000087', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(88, '0000000088', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(89, '0000000089', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(90, '0000000090', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(91, '0000000091', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(92, '0000000092', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(93, '0000000093', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(94, '0000000094', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(95, '0000000095', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(96, '0000000096', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(97, '0000000097', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(98, '0000000098', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(99, '0000000099', 0, 1, 0, 1, '2023-02-13 02:56:53'),
(100, '0000000100', 0, 1, 0, 1, '2023-02-13 02:56:53');

-- --------------------------------------------------------

--
-- Table structure for table `lup_province`
--

CREATE TABLE IF NOT EXISTS `lup_province` (
  `province_id` int(5) NOT NULL,
  `province_code` varchar(10) DEFAULT NULL,
  `region_id` varchar(10) DEFAULT NULL,
  `province_name` varchar(100) DEFAULT NULL,
  `created_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `isdeleted` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `lup_region`
--

CREATE TABLE IF NOT EXISTS `lup_region` (
  `region_id` int(5) NOT NULL,
  `region_code` varchar(10) DEFAULT NULL,
  `region_name` varchar(100) DEFAULT NULL,
  `created_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `isdeleted` int(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `lup_registration_status`
--

CREATE TABLE IF NOT EXISTS `lup_registration_status` (
  `registration_status_id` int(11) NOT NULL,
  `registration_status_code` varchar(10) DEFAULT NULL,
  `registration_status_description` varchar(20) DEFAULT NULL,
  `isdeleted` varchar(20) DEFAULT NULL,
  `created_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `lup_registration_status`
--

INSERT INTO `lup_registration_status` (`registration_status_id`, `registration_status_code`, `registration_status_description`, `isdeleted`, `created_modified`) VALUES
(1, '100', 'PENDING', '0', '2020-05-01 01:03:36'),
(2, '102', 'APPROVED', '0', '2020-05-01 01:03:58'),
(3, '103', 'REJECTED', '0', '2020-05-02 09:46:56');

-- --------------------------------------------------------

--
-- Table structure for table `lup_sales_team`
--

CREATE TABLE IF NOT EXISTS `lup_sales_team` (
  `sales_team_id` int(5) NOT NULL,
  `sales_team_code` varchar(10) DEFAULT NULL,
  `sales_team_name` varchar(150) DEFAULT NULL,
  `team_leader` int(11) NOT NULL,
  `created_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `no_team` int(11) NOT NULL,
  `isdeleted` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `lup_settlement_type`
--

CREATE TABLE IF NOT EXISTS `lup_settlement_type` (
  `settlement_type_id` int(5) NOT NULL AUTO_INCREMENT,
  `settlement_code` varchar(10) DEFAULT NULL,
  `settlement_description` varchar(100) DEFAULT NULL,
  `settlement_type_group_id` int(3) DEFAULT NULL,
  `with_reference_no1` int(1) DEFAULT NULL,
  `with_reference_no2` int(1) DEFAULT NULL,
  `with_reference_description1` int(1) DEFAULT NULL,
  `with_reference_description2` int(1) DEFAULT NULL,
  `with_proof_of_payment1` int(1) DEFAULT NULL,
  `with_proof_of_payment2` int(1) DEFAULT NULL,
  `charge_to_account1` int(1) DEFAULT NULL,
  `charge_to_account2` int(1) DEFAULT NULL,
  `visible` int(1) DEFAULT NULL,
  `isdeleted` int(1) DEFAULT NULL,
  `isdefault` int(11) NOT NULL,
  `created_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`settlement_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=29 ;

--
-- Dumping data for table `lup_settlement_type`
--

INSERT INTO `lup_settlement_type` (`settlement_type_id`, `settlement_code`, `settlement_description`, `settlement_type_group_id`, `with_reference_no1`, `with_reference_no2`, `with_reference_description1`, `with_reference_description2`, `with_proof_of_payment1`, `with_proof_of_payment2`, `charge_to_account1`, `charge_to_account2`, `visible`, `isdeleted`, `isdefault`, `created_modified`) VALUES
(1, '101', 'CASH', 1, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 1, '2020-05-13 05:48:59'),
(2, '102', 'CREDIT LINE', 2, 0, 0, 0, 0, 0, 0, 1, 0, 1, 0, 0, '2020-05-13 05:48:59'),
(3, '103', 'BDO CREDIT CARD', 2, 1, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, '2020-05-13 05:48:59'),
(4, '104', 'BPI', 3, 1, 0, 1, 0, 1, 0, 0, 0, 0, 0, 0, '2020-05-13 05:48:59'),
(5, '105', 'PNB', 3, 1, 0, 1, 0, 1, 0, 0, 0, 0, 0, 0, '2020-05-13 05:48:59'),
(6, '106', 'EASTWEST', 3, 1, 0, 1, 0, 1, 0, 0, 0, 0, 0, 0, '2020-05-13 05:48:59'),
(7, '107', 'METROBANK', 3, 1, 0, 1, 0, 1, 0, 0, 0, 0, 0, 0, '2020-05-13 05:48:59'),
(8, '108', 'LANDBANK', 3, 1, 0, 1, 0, 1, 0, 0, 0, 0, 0, 0, '2020-05-13 05:48:59'),
(9, '109', 'RCBC', 3, 1, 0, 1, 0, 1, 0, 0, 0, 0, 0, 0, '2020-05-13 05:48:59'),
(10, '110', 'SECURITY', 3, 1, 0, 1, 0, 1, 0, 0, 0, 0, 0, 0, '2020-05-13 05:48:59'),
(11, '111', 'CHINA', 3, 1, 0, 1, 0, 1, 0, 0, 0, 0, 0, 0, '2020-05-13 05:48:59'),
(12, '112', 'CEBUANA', 4, 1, 0, 1, 0, 1, 0, 0, 0, 0, 0, 0, '2020-05-13 05:48:59'),
(13, '113', 'MLHUILLIER', 4, 1, 0, 1, 0, 1, 0, 0, 0, 0, 0, 0, '2020-05-13 05:48:59'),
(14, '114', 'PALAWAN', 4, 1, 0, 1, 0, 1, 0, 0, 0, 0, 0, 0, '2020-05-13 05:48:59'),
(15, '115', 'LBC', 4, 1, 0, 1, 0, 1, 0, 0, 0, 0, 0, 0, '2020-05-13 05:48:59'),
(16, '116', 'WESTERN', 4, 1, 0, 1, 0, 1, 0, 0, 0, 0, 0, 0, '2020-05-13 05:48:59'),
(17, '117', 'SMART PADALA', 4, 1, 0, 1, 0, 1, 0, 0, 0, 0, 0, 0, '2020-05-13 05:48:59'),
(18, '118', 'TRUE MONEY', 4, 1, 0, 1, 0, 1, 0, 0, 0, 0, 0, 0, '2020-05-13 05:48:59'),
(19, '119', 'VILLARICA', 4, 1, 0, 1, 0, 1, 0, 0, 0, 0, 0, 0, '2020-05-13 05:48:59'),
(20, '120', 'RD CASH', 4, 1, 0, 1, 0, 1, 0, 0, 0, 0, 0, 0, '2020-05-13 05:48:59'),
(21, '121', 'GCASH', 4, 1, 0, 1, 0, 1, 0, 0, 0, 0, 0, 0, '2020-05-13 05:48:59'),
(22, '122', 'PAYPAL', 4, 1, 0, 1, 0, 1, 0, 0, 0, 0, 0, 0, '2020-05-13 05:48:59'),
(23, '123', 'PAYMAYA', 4, 1, 0, 1, 0, 1, 0, 0, 0, 0, 0, 0, '2020-05-13 05:48:59'),
(24, '124', 'MEETUP', 4, 1, 0, 1, 0, 1, 0, 0, 0, 0, 0, 0, '2020-05-13 05:48:59'),
(25, '125', 'CARE OF', 4, 1, 0, 1, 0, 1, 0, 0, 0, 0, 0, 0, '2020-05-13 05:48:59'),
(26, '126', 'CLAVERIA TOURS', 4, 1, 0, 1, 0, 1, 0, 0, 0, 0, 0, 0, '2020-05-13 05:48:59'),
(27, '127', 'GMW', 4, 1, 0, 1, 0, 1, 0, 0, 0, 0, 0, 0, '2020-05-13 05:48:59'),
(28, 'SAMPLE', 'SAMPLE', 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 1, 0, '2020-06-08 22:19:14');

-- --------------------------------------------------------

--
-- Table structure for table `lup_settlement_type_group`
--

CREATE TABLE IF NOT EXISTS `lup_settlement_type_group` (
  `settlement_type_group_id` int(5) NOT NULL AUTO_INCREMENT,
  `settlement_type_group_code` varchar(10) DEFAULT NULL,
  `settlement_type_group_description` varchar(50) DEFAULT NULL,
  `isdeleted` int(1) DEFAULT NULL,
  `created_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`settlement_type_group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `lup_settlement_type_group`
--

INSERT INTO `lup_settlement_type_group` (`settlement_type_group_id`, `settlement_type_group_code`, `settlement_type_group_description`, `isdeleted`, `created_modified`) VALUES
(1, '0001', 'CASH', 0, '2022-09-03 08:10:12'),
(2, '0002', 'CREDIT CARD', 0, '2022-09-03 08:10:19'),
(3, '0003', 'CASH CARD', 0, '2022-09-03 08:10:29');

-- --------------------------------------------------------

--
-- Table structure for table `lup_supplier`
--

CREATE TABLE IF NOT EXISTS `lup_supplier` (
  `supplier_id` int(5) NOT NULL AUTO_INCREMENT,
  `supplier_code` varchar(5) DEFAULT NULL,
  `supplier_description` varchar(50) DEFAULT NULL,
  `visible` int(1) DEFAULT NULL,
  `isdeleted` int(1) DEFAULT NULL,
  `created_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`supplier_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `lup_variations`
--

CREATE TABLE IF NOT EXISTS `lup_variations` (
  `variation_id` int(10) NOT NULL AUTO_INCREMENT,
  `ref_no` varchar(10) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `item_id` int(5) DEFAULT NULL,
  `unit_price` double(12,2) DEFAULT NULL,
  `created_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `isdeleted` int(11) NOT NULL,
  PRIMARY KEY (`variation_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=102 ;

--
-- Dumping data for table `lup_variations`
--

INSERT INTO `lup_variations` (`variation_id`, `ref_no`, `description`, `item_id`, `unit_price`, `created_modified`, `isdeleted`) VALUES
(1, '', 'Ham and Egg', 1, 169.00, '0000-00-00 00:00:00', 0),
(2, '', 'Bacon and Egg', 2, 169.00, '0000-00-00 00:00:00', 0),
(3, '', 'Avocado and Egg', 3, 169.00, '0000-00-00 00:00:00', 0),
(4, '', 'Spam and Egg', 4, 179.00, '0000-00-00 00:00:00', 0),
(5, '', 'Beef Bulgogi and Egg', 5, 179.00, '0000-00-00 00:00:00', 0),
(6, '', 'Grilled Chicken and Egg', 6, 179.00, '0000-00-00 00:00:00', 0),
(7, '', 'Garlic Butter Shrimp and Egg', 7, 219.00, '0000-00-00 00:00:00', 0),
(8, '', '16OZ', 8, 170.00, '0000-00-00 00:00:00', 0),
(9, '', '16OZ', 9, 170.00, '0000-00-00 00:00:00', 0),
(10, '', '16OZ', 10, 180.00, '0000-00-00 00:00:00', 0),
(11, '', 'Turkey Ham and Egg', 11, 229.00, '0000-00-00 00:00:00', 0),
(12, '', 'Beef Pastrami and Egg', 12, 229.00, '0000-00-00 00:00:00', 0),
(13, '', 'Spam Fried Rice', 13, 169.00, '0000-00-00 00:00:00', 0),
(14, '', 'Ham Fried Rice', 14, 169.00, '0000-00-00 00:00:00', 0),
(15, '', 'Bacon Fried Rice', 15, 169.00, '0000-00-00 00:00:00', 0),
(16, '', 'Sausage Fried Rice', 16, 169.00, '0000-00-00 00:00:00', 0),
(17, '', 'Bulgogi Fried Rice', 17, 179.00, '0000-00-00 00:00:00', 0),
(18, '', 'Chicken Fried Rice', 18, 179.00, '0000-00-00 00:00:00', 0),
(19, '', 'Honey Soy', 19, 189.00, '0000-00-00 00:00:00', 0),
(20, '', 'Gochujang', 20, 189.00, '0000-00-00 00:00:00', 0),
(21, '', 'Egg', 21, 20.00, '0000-00-00 00:00:00', 0),
(22, '', 'Fried Rice', 22, 55.00, '0000-00-00 00:00:00', 0),
(23, '', 'Bacon  ', 23, 60.00, '0000-00-00 00:00:00', 0),
(24, '', 'Ham  ', 24, 60.00, '0000-00-00 00:00:00', 0),
(25, '', 'Sausage   ', 25, 60.00, '0000-00-00 00:00:00', 0),
(26, '', 'Spam  ', 26, 70.00, '0000-00-00 00:00:00', 0),
(27, '', 'Bulgogi  ', 27, 70.00, '0000-00-00 00:00:00', 0),
(28, '', 'Chicken  ', 28, 70.00, '0000-00-00 00:00:00', 0),
(29, '', 'Cheese', 29, 20.00, '0000-00-00 00:00:00', 0),
(30, '', 'Egg', 30, 20.00, '0000-00-00 00:00:00', 0),
(31, '', 'Coleslaw', 31, 20.00, '0000-00-00 00:00:00', 0),
(32, '', 'Sauce', 32, 20.00, '0000-00-00 00:00:00', 0),
(33, '', 'Spam   ', 33, 70.00, '0000-00-00 00:00:00', 0),
(34, '', 'Bacon   ', 34, 60.00, '0000-00-00 00:00:00', 0),
(35, '', 'Ham', 35, 60.00, '0000-00-00 00:00:00', 0),
(36, '', 'Fettuccine', 36, 220.00, '0000-00-00 00:00:00', 0),
(37, '', 'Fettuccine', 37, 249.00, '0000-00-00 00:00:00', 0),
(38, '', 'Fettuccine', 38, 249.00, '0000-00-00 00:00:00', 0),
(39, '', 'Fettuccine', 39, 249.00, '0000-00-00 00:00:00', 0),
(40, '', 'Fettuccine', 40, 269.00, '0000-00-00 00:00:00', 0),
(41, '', 'Fettuccine', 41, 269.00, '0000-00-00 00:00:00', 0),
(42, '', 'Fussili', 36, 220.00, '0000-00-00 00:00:00', 0),
(43, '', 'Fussili', 37, 249.00, '0000-00-00 00:00:00', 0),
(44, '', 'Fussili', 38, 249.00, '0000-00-00 00:00:00', 0),
(45, '', 'Fussili', 39, 249.00, '0000-00-00 00:00:00', 0),
(46, '', 'Fussili', 40, 269.00, '0000-00-00 00:00:00', 0),
(47, '', 'Fussili', 41, 269.00, '0000-00-00 00:00:00', 0),
(48, '', 'Penne', 36, 220.00, '0000-00-00 00:00:00', 0),
(49, '', 'Penne', 37, 249.00, '0000-00-00 00:00:00', 0),
(50, '', 'Penne', 38, 249.00, '0000-00-00 00:00:00', 0),
(51, '', 'Penne', 39, 249.00, '0000-00-00 00:00:00', 0),
(52, '', 'Penne', 40, 269.00, '0000-00-00 00:00:00', 0),
(53, '', 'Penne', 41, 269.00, '0000-00-00 00:00:00', 0),
(54, '', 'HOT', 42, 70.00, '0000-00-00 00:00:00', 0),
(55, '', 'HOT', 43, 90.00, '0000-00-00 00:00:00', 0),
(56, '', 'HOT', 44, 100.00, '0000-00-00 00:00:00', 0),
(57, '', 'HOT', 45, 100.00, '0000-00-00 00:00:00', 0),
(58, '', 'HOT', 46, 110.00, '0000-00-00 00:00:00', 0),
(59, '', 'HOT', 47, 120.00, '0000-00-00 00:00:00', 0),
(60, '', 'HOT', 48, 120.00, '0000-00-00 00:00:00', 0),
(61, '', 'HOT', 49, 120.00, '0000-00-00 00:00:00', 0),
(62, '', 'HOT', 50, 120.00, '0000-00-00 00:00:00', 0),
(63, '', 'ICED', 42, 0.00, '0000-00-00 00:00:00', 0),
(64, '', 'ICED', 43, 100.00, '0000-00-00 00:00:00', 0),
(65, '', 'ICED', 44, 110.00, '0000-00-00 00:00:00', 0),
(66, '', 'ICED', 45, 0.00, '0000-00-00 00:00:00', 0),
(67, '', 'ICED', 46, 120.00, '0000-00-00 00:00:00', 0),
(68, '', 'ICED', 47, 130.00, '0000-00-00 00:00:00', 0),
(69, '', 'ICED', 48, 130.00, '0000-00-00 00:00:00', 0),
(70, '', 'ICED', 49, 130.00, '0000-00-00 00:00:00', 0),
(71, '', 'ICED', 50, 130.00, '0000-00-00 00:00:00', 0),
(72, '', 'HOT', 51, 160.00, '0000-00-00 00:00:00', 0),
(73, '', 'HOT', 52, 160.00, '0000-00-00 00:00:00', 0),
(74, '', 'ICED', 51, 160.00, '0000-00-00 00:00:00', 0),
(75, '', 'ICED', 52, 160.00, '0000-00-00 00:00:00', 0),
(76, '', 'Chamomile', 53, 90.00, '0000-00-00 00:00:00', 0),
(77, '', 'Red Berry', 54, 90.00, '0000-00-00 00:00:00', 0),
(78, '', 'Jasmine', 55, 90.00, '0000-00-00 00:00:00', 0),
(79, '', 'Earl grey', 56, 90.00, '0000-00-00 00:00:00', 0),
(80, '', 'Honey Citron', 57, 90.00, '0000-00-00 00:00:00', 0),
(81, '', 'Butterfly Tea', 58, 90.00, '0000-00-00 00:00:00', 0),
(82, '', 'Bottled Water', 59, 40.00, '0000-00-00 00:00:00', 0),
(83, '', 'Coke Regular', 60, 50.00, '0000-00-00 00:00:00', 0),
(84, '', 'Coke Zero', 61, 50.00, '0000-00-00 00:00:00', 0),
(85, '', 'Sprite', 62, 50.00, '0000-00-00 00:00:00', 0),
(86, '', 'Royal', 63, 50.00, '0000-00-00 00:00:00', 0),
(87, '', 'Plain Fries', 64, 150.00, '0000-00-00 00:00:00', 0),
(88, '', 'Messy Fries', 65, 220.00, '0000-00-00 00:00:00', 0),
(89, '', 'Chicken Wings', 66, 199.00, '0000-00-00 00:00:00', 0),
(90, '', '3 Dip Nachos ', 67, 220.00, '0000-00-00 00:00:00', 0),
(91, '', 'Veggie Balls', 68, 220.00, '0000-00-00 00:00:00', 0),
(92, '', 'Margherita', 69, 420.00, '0000-00-00 00:00:00', 0),
(93, '', 'Bunny', 70, 420.00, '0000-00-00 00:00:00', 0),
(94, '', 'Fish', 70, 420.00, '0000-00-00 00:00:00', 0),
(95, '', 'Mickey Mouse', 70, 420.00, '0000-00-00 00:00:00', 0),
(96, '', 'Pepperoni', 71, 460.00, '0000-00-00 00:00:00', 0),
(97, '', 'Pesto', 72, 460.00, '0000-00-00 00:00:00', 0),
(98, '', 'Quatro Formaggi', 73, 470.00, '0000-00-00 00:00:00', 0),
(99, '', 'Creamy Spinach', 74, 500.00, '0000-00-00 00:00:00', 0),
(100, '', 'Proscuitto ', 75, 520.00, '0000-00-00 00:00:00', 0),
(101, '', 'Arrabiata', 76, 520.00, '0000-00-00 00:00:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `order_expense`
--

CREATE TABLE IF NOT EXISTS `order_expense` (
  `expense_id` int(11) NOT NULL AUTO_INCREMENT,
  `branch_id` int(11) NOT NULL,
  `result` varchar(50) NOT NULL,
  `expense_description` varchar(50) NOT NULL,
  `is_other_deposit` int(11) NOT NULL,
  `customer_type_group_id` int(11) NOT NULL,
  `expense_date` varchar(50) NOT NULL,
  `expense_amount` float NOT NULL,
  `proof` varchar(50) NOT NULL,
  `added_by` int(11) NOT NULL,
  `modified_by` int(11) NOT NULL,
  `deleted_by` int(11) NOT NULL,
  `isdeleted` int(11) NOT NULL,
  PRIMARY KEY (`expense_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pos_lup_category`
--

CREATE TABLE IF NOT EXISTS `pos_lup_category` (
  `category_id` int(5) NOT NULL,
  `category_code` varchar(5) DEFAULT NULL,
  `category_description` varchar(50) DEFAULT NULL,
  `category_photo` varchar(100) DEFAULT NULL,
  `classification_id` int(5) DEFAULT NULL,
  `visible` int(1) DEFAULT NULL,
  `isdeleted` int(1) DEFAULT NULL,
  `created_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `pos_lup_classification`
--

CREATE TABLE IF NOT EXISTS `pos_lup_classification` (
  `classification_id` int(5) NOT NULL AUTO_INCREMENT,
  `classification_code` varchar(5) DEFAULT NULL,
  `classification_description` varchar(50) DEFAULT NULL,
  `department_id` int(5) DEFAULT NULL,
  `visible` int(1) DEFAULT NULL,
  `branch_id` int(11) NOT NULL,
  `isdeleted` int(1) DEFAULT NULL,
  `created_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`classification_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT AUTO_INCREMENT=14 ;

--
-- Dumping data for table `pos_lup_classification`
--

INSERT INTO `pos_lup_classification` (`classification_id`, `classification_code`, `classification_description`, `department_id`, `visible`, `branch_id`, `isdeleted`, `created_modified`) VALUES
(1, '0001', 'EGG DROP SANDWICH', 0, 1, 1, 0, '2023-02-05 06:09:12'),
(2, '0002', 'KOREAN YOGURT', 0, 1, 1, 0, '2023-02-05 06:09:31'),
(3, '0003', 'GOURMET SANDWICH', 0, 1, 1, 0, '2023-02-05 06:09:43'),
(4, '0004', 'FRIED RICE MEAL', 0, 1, 1, 0, '2023-02-05 06:09:59'),
(5, '0005', 'KOREAN FRIED CHICKEN SANWICH', 0, 1, 1, 0, '2023-02-05 06:11:11'),
(6, '0006', 'ADDITIONAL', 0, 1, 1, 0, '2023-02-05 06:11:34'),
(7, '0007', 'PASTA', 0, 1, 2, 0, '2023-02-05 06:55:25'),
(8, '0008', 'COFFEE BASED', 0, 1, 2, 0, '2023-02-05 06:58:35'),
(9, '0009', 'NON-COFFEE BASED', 0, 1, 2, 0, '2023-02-05 06:58:48'),
(10, '0010', 'TEA', 0, 1, 2, 0, '2023-02-05 06:59:18'),
(11, '0011', 'OTHER DRINKS', 0, 1, 2, 0, '2023-02-05 06:59:28'),
(12, '0012', 'APPETIZER', 0, 1, 2, 0, '2023-02-05 06:59:48'),
(13, '0013', 'PIZZA', 0, 1, 2, 0, '2023-02-05 06:59:53');

-- --------------------------------------------------------

--
-- Table structure for table `pos_lup_department`
--

CREATE TABLE IF NOT EXISTS `pos_lup_department` (
  `department_id` int(5) NOT NULL AUTO_INCREMENT,
  `department_code` varchar(5) DEFAULT NULL,
  `department_description` varchar(50) DEFAULT NULL,
  `visible` int(1) DEFAULT NULL,
  `isdeleted` int(1) DEFAULT NULL,
  `created_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`department_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT AUTO_INCREMENT=2 ;

--
-- Dumping data for table `pos_lup_department`
--

INSERT INTO `pos_lup_department` (`department_id`, `department_code`, `department_description`, `visible`, `isdeleted`, `created_modified`) VALUES
(1, '-', '-', 1, 0, '2022-08-25 08:09:14');

-- --------------------------------------------------------

--
-- Table structure for table `pos_lup_item`
--

CREATE TABLE IF NOT EXISTS `pos_lup_item` (
  `item_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `item_code` varchar(10) DEFAULT NULL,
  `item_description` varchar(250) DEFAULT NULL,
  `item_short_description` varchar(20) DEFAULT NULL,
  `unit_id` int(11) NOT NULL,
  `quantity` double NOT NULL,
  `item_barcode` varchar(50) DEFAULT NULL,
  `item_photo` varchar(100) DEFAULT NULL,
  `item_price1` double(10,2) DEFAULT NULL,
  `item_price2` double(10,2) DEFAULT NULL,
  `item_price3` double(10,2) NOT NULL,
  `item_cost1` double(10,2) DEFAULT NULL,
  `item_cost2` double(10,2) DEFAULT NULL,
  `open_price` int(1) unsigned DEFAULT NULL,
  `category_id` int(3) unsigned DEFAULT NULL,
  `classification_id` int(3) unsigned DEFAULT NULL,
  `department_id` int(3) unsigned DEFAULT NULL,
  `branch_id` int(11) NOT NULL,
  `addon_id` int(11) NOT NULL,
  `remarks` varchar(250) DEFAULT NULL,
  `visible` int(1) unsigned zerofill DEFAULT '1',
  `isdeleted` int(1) unsigned DEFAULT '0',
  `inventory_item` int(1) unsigned DEFAULT '1',
  `created_by_fullname` varchar(50) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `edited_by` varchar(50) DEFAULT NULL,
  `edited_datetime` datetime DEFAULT NULL,
  `created_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT AUTO_INCREMENT=79 ;

--
-- Dumping data for table `pos_lup_item`
--

INSERT INTO `pos_lup_item` (`item_id`, `item_code`, `item_description`, `item_short_description`, `unit_id`, `quantity`, `item_barcode`, `item_photo`, `item_price1`, `item_price2`, `item_price3`, `item_cost1`, `item_cost2`, `open_price`, `category_id`, `classification_id`, `department_id`, `branch_id`, `addon_id`, `remarks`, `visible`, `isdeleted`, `inventory_item`, `created_by_fullname`, `created_datetime`, `edited_by`, `edited_datetime`, `created_modified`) VALUES
(1, '1', 'Ham and Egg', '', 1, 1, '', '', 169.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 1, 0, 1, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, '2', 'Bacon and Egg', '', 1, 1, '', '', 169.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 1, 0, 1, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, '3', 'Avocado and Egg', '', 1, 1, '', '', 169.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 1, 0, 1, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, '4', 'Spam and Egg', '', 1, 1, '', '', 179.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 1, 0, 1, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, '5', 'Beef Bulgogi and Egg', '', 1, 1, '', '', 179.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 1, 0, 1, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, '6', 'Grilled Chicken and Egg', '', 1, 1, '', '', 179.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 1, 0, 1, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, '7', 'Garlic Butter Shrimp and Egg', '', 1, 1, '', '', 219.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 1, 0, 1, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(8, '8', 'Strawberry Yogurt', '', 1, 1, '', '', 170.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 2, 0, 1, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(9, '9', 'Blueberry Yogurt', '', 1, 1, '', '', 170.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 2, 0, 1, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10, '10', 'Mixed Berries', '', 1, 1, '', '', 180.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 2, 0, 1, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(11, '11', 'Turkey Ham and Egg', '', 1, 1, '', '', 229.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 3, 0, 1, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(12, '12', 'Beef Pastrami and Egg', '', 1, 1, '', '', 229.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 3, 0, 1, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(13, '13', 'Spam Fried Rice', '', 1, 1, '', '', 169.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 4, 0, 1, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(14, '14', 'Ham Fried Rice', '', 1, 1, '', '', 169.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 4, 0, 1, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(15, '15', 'Bacon Fried Rice', '', 1, 1, '', '', 169.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 4, 0, 1, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(16, '16', 'Sausage Fried Rice', '', 1, 1, '', '', 169.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 4, 0, 1, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(17, '17', 'Bulgogi Fried Rice', '', 1, 1, '', '', 179.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 4, 0, 1, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(18, '18', 'Chicken Fried Rice', '', 1, 1, '', '', 179.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 4, 0, 1, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(19, '19', 'Honey Soy', '', 1, 1, '', '', 189.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 5, 0, 1, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(20, '20', 'Gochujang', '', 1, 1, '', '', 189.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 5, 0, 1, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(21, '21', 'Egg', '', 1, 1, '', '', 20.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 6, 0, 1, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(22, '22', 'Fried Rice', '', 1, 1, '', '', 55.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 6, 0, 1, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(23, '23', 'Bacon  ', '', 1, 1, '', '', 60.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 6, 0, 1, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(24, '24', 'Ham  ', '', 1, 1, '', '', 60.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 6, 0, 1, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(25, '25', 'Sausage   ', '', 1, 1, '', '', 60.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 6, 0, 1, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(26, '26', 'Spam  ', '', 1, 1, '', '', 70.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 6, 0, 1, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(27, '27', 'Bulgogi  ', '', 1, 1, '', '', 70.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 6, 0, 1, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(28, '28', 'Chicken  ', '', 1, 1, '', '', 70.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 6, 0, 1, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(29, '29', 'Cheese', '', 1, 1, '', '', 20.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 6, 0, 1, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(30, '30', 'Egg', '', 1, 1, '', '', 20.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 6, 0, 1, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(31, '31', 'Coleslaw', '', 1, 1, '', '', 20.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 6, 0, 1, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(32, '32', 'Sauce', '', 1, 1, '', '', 20.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 6, 0, 1, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(33, '33', 'Spam   ', '', 1, 1, '', '', 70.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 6, 0, 1, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(34, '34', 'Bacon   ', '', 1, 1, '', '', 60.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 6, 0, 1, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(35, '35', 'Ham', '', 1, 1, '', '', 60.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 6, 0, 1, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(36, '36', 'Carbonara', '', 1, 1, '', '', 220.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 7, 0, 2, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(37, '37', 'Chicken Alfredo', '', 1, 1, '', '', 249.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 7, 0, 2, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(38, '38', 'Pesto', '', 1, 1, '', '', 249.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 7, 0, 2, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(39, '39', 'Bolognese', '', 1, 1, '', '', 249.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 7, 0, 2, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(40, '40', 'Creamy Negra Scampi', '', 1, 1, '', '', 269.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 7, 0, 2, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(41, '41', 'Arrabiata ', '', 1, 1, '', '', 269.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 7, 0, 2, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(42, '42', 'Espresso', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 8, 0, 2, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(43, '43', 'Americano', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 8, 0, 2, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(44, '44', 'Latte', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 8, 0, 2, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(45, '45', 'Cappucino', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 8, 0, 2, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(46, '46', 'Mocha', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 8, 0, 2, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(47, '47', 'Caramel Macchiato ', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 8, 0, 2, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(48, '48', 'White Chocolate Mocha', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 8, 0, 2, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(49, '49', 'Salted Caramel Latte', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 8, 0, 2, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(50, '50', 'Butterscotch Latte', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 8, 0, 2, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(51, '51', 'Chocolate', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 9, 0, 2, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(52, '52', 'Matcha', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 9, 0, 2, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(53, '53', 'Chamomile', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 10, 0, 2, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(54, '54', 'Red Berry', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 10, 0, 2, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(55, '55', 'Jasmine', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 10, 0, 2, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(56, '56', 'Earl grey', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 10, 0, 2, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(57, '57', 'Honey Citron', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 10, 0, 2, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(58, '58', 'Butterfly Tea', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 10, 0, 2, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(59, '59', 'Bottled Water', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 11, 0, 2, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(60, '60', 'Coke Regular', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 11, 0, 2, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(61, '61', 'Coke Zero', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 11, 0, 2, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(62, '62', 'Sprite', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 11, 0, 2, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(63, '63', 'Royal', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 11, 0, 2, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(64, '64', 'Plain Fries', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 12, 0, 2, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(65, '65', 'Messy Fries', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 12, 0, 2, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(66, '66', 'Chicken Wings', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 12, 0, 2, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(67, '67', '3 Dip Nachos ', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 12, 0, 2, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(68, '68', 'Veggie Balls', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 12, 0, 2, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(69, '69', 'Margherita', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 13, 0, 2, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(70, '70', 'Kids Pizza', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 13, 0, 2, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(71, '71', 'Pepperoni', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 13, 0, 2, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(72, '72', 'Pesto', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 13, 0, 2, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(73, '73', 'Quatro Formaggi', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 13, 0, 2, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(74, '74', 'Creamy Spinach', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 13, 0, 2, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(75, '75', 'Proscuitto ', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 13, 0, 2, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(76, '76', 'Arrabiata', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 13, 0, 2, 0, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `pos_lup_menu_group`
--

CREATE TABLE IF NOT EXISTS `pos_lup_menu_group` (
  `menu_group_id` int(5) NOT NULL,
  `menu_group_code` varchar(10) DEFAULT NULL,
  `menu_group_description` varchar(50) DEFAULT NULL,
  `menu_group_photo` varchar(50) DEFAULT NULL,
  `visible` int(1) DEFAULT NULL,
  `isdeleted` int(1) DEFAULT NULL,
  `created_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pos_lup_order_type`
--

CREATE TABLE IF NOT EXISTS `pos_lup_order_type` (
  `order_type_id` int(3) unsigned NOT NULL,
  `order_type_code` varchar(10) DEFAULT NULL,
  `order_type_description` varchar(50) DEFAULT NULL,
  `isdeleted` int(1) DEFAULT NULL,
  `created_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pos_order_type_status`
--

CREATE TABLE IF NOT EXISTS `pos_order_type_status` (
  `pos_order_type_status_id` int(11) NOT NULL,
  `status_description` varchar(50) NOT NULL,
  `order_type_id` int(11) NOT NULL,
  `sms_template_id` int(11) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `added_by` varchar(50) NOT NULL,
  `isdeleted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pos_sales`
--

CREATE TABLE IF NOT EXISTS `pos_sales` (
  `pos_sales_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sales_datetime` varchar(15) DEFAULT NULL,
  `sales_invoice_number` varchar(15) DEFAULT NULL,
  `branch_id` int(5) DEFAULT NULL,
  `customer_id` int(10) unsigned DEFAULT NULL,
  `customer_fullname` varchar(150) DEFAULT NULL,
  `total_quantity` double(10,2) DEFAULT NULL,
  `total_sales` double(10,2) DEFAULT NULL,
  `total_service_charge` double(10,2) DEFAULT NULL,
  `total_tax` double(10,2) DEFAULT NULL,
  `total_discount` double(10,2) DEFAULT NULL,
  `card_id` int(3) DEFAULT NULL,
  `order_type_id` int(3) unsigned DEFAULT NULL,
  `order_count` int(11) NOT NULL,
  `created_by_fullname` varchar(50) DEFAULT NULL,
  `order_by_fullname` varchar(50) DEFAULT NULL,
  `settled_by_fullname` varchar(50) DEFAULT NULL,
  `voided_by_fullname` varchar(50) DEFAULT NULL,
  `voided_datetime` datetime DEFAULT NULL,
  `remarks` varchar(250) DEFAULT NULL,
  `order_status_id` int(11) NOT NULL,
  `order_status` varchar(50) NOT NULL,
  `result` varchar(50) NOT NULL,
  `isdeleted` int(1) DEFAULT NULL,
  `dayend_date` date DEFAULT NULL,
  `created_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pos_sales_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `pos_sales`
--

INSERT INTO `pos_sales` (`pos_sales_id`, `sales_datetime`, `sales_invoice_number`, `branch_id`, `customer_id`, `customer_fullname`, `total_quantity`, `total_sales`, `total_service_charge`, `total_tax`, `total_discount`, `card_id`, `order_type_id`, `order_count`, `created_by_fullname`, `order_by_fullname`, `settled_by_fullname`, `voided_by_fullname`, `voided_datetime`, `remarks`, `order_status_id`, `order_status`, `result`, `isdeleted`, `dayend_date`, `created_modified`) VALUES
(1, NULL, '', 1, 0, 'cus', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '1', NULL, NULL, NULL, NULL, '', 0, '', 'igZbWRqKCk', 0, NULL, '2023-02-13 02:55:35'),
(2, '2023-02-13 11:1', '0000000001', 1, 0, 'cus', 4.00, 340.00, NULL, NULL, NULL, 0, 1, 0, '1', NULL, NULL, NULL, NULL, '', 0, '', 'M7IcB5CqKS', 0, NULL, '2023-02-13 03:14:40'),
(3, NULL, '', 1, 0, 'cus', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '1', NULL, NULL, NULL, NULL, '', 0, '', '6k1zXVXb86', 0, NULL, '2023-02-13 02:58:13'),
(4, NULL, '', 1, 0, 'cus', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '1', NULL, NULL, NULL, NULL, '', 0, '', 'oXTh2RZBSJ', 0, NULL, '2023-02-13 02:58:48'),
(5, NULL, '', 1, 0, 'cus', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '1', NULL, NULL, NULL, NULL, '', 0, '', 'HwqtaNy7Nu', 0, NULL, '2023-02-13 03:14:45'),
(6, NULL, '', 1, 0, 'cus', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '5', NULL, NULL, NULL, NULL, '', 0, '', 'UafIp5NpoT', 0, NULL, '2023-02-13 03:48:45'),
(7, NULL, '', 1, 0, 'cus', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '7', NULL, NULL, NULL, NULL, '', 0, '', '1sAHuSetZZ', 0, NULL, '2023-02-13 03:50:37'),
(8, NULL, '', 1, 0, 'cus', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '1', NULL, NULL, NULL, NULL, '', 0, '', 'MVoAcRXrVM', 0, NULL, '2023-02-13 07:33:54'),
(9, NULL, '', 1, 0, 'cus', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '1', NULL, NULL, NULL, NULL, '', 0, '', 'oTQKg6LyYP', 0, NULL, '2023-02-13 12:12:02'),
(10, NULL, '', 1, 0, 'cus', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '1', NULL, NULL, NULL, NULL, '', 0, '', '7mnRJfhnfu', 0, NULL, '2023-02-14 07:09:59');

-- --------------------------------------------------------

--
-- Table structure for table `pos_sales_detail`
--

CREATE TABLE IF NOT EXISTS `pos_sales_detail` (
  `pos_sales_detail_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pos_sales_id` int(10) unsigned DEFAULT NULL,
  `sales_invoice_number` varchar(15) DEFAULT NULL,
  `branch_id` int(5) DEFAULT NULL,
  `item_id` int(10) unsigned DEFAULT NULL,
  `discount` double NOT NULL,
  `item_discount` double NOT NULL,
  `total_item_discount` double NOT NULL,
  `ispercent` int(11) NOT NULL,
  `unit_id` int(11) NOT NULL,
  `addon_id` int(11) NOT NULL,
  `item_code` varchar(20) DEFAULT NULL,
  `item_description` varchar(250) DEFAULT NULL,
  `item_short_description` varchar(20) DEFAULT NULL,
  `category_description` varchar(50) DEFAULT NULL,
  `department_description` varchar(50) DEFAULT NULL,
  `classification_description` varchar(50) DEFAULT NULL,
  `item_cost` double(10,2) DEFAULT NULL,
  `item_price` double(10,2) DEFAULT NULL,
  `tax` double(10,2) DEFAULT NULL,
  `service_charge` double(10,2) DEFAULT NULL,
  `quantity` double(7,2) DEFAULT NULL,
  `sub_total` double(10,2) DEFAULT NULL,
  `grand_total` double(10,2) DEFAULT NULL,
  `done` int(11) NOT NULL,
  `done_date` varchar(255) NOT NULL,
  `order_time` time DEFAULT NULL,
  `order_sequence` int(3) unsigned DEFAULT NULL,
  `order_by` int(3) unsigned DEFAULT NULL,
  `created_by` int(3) unsigned DEFAULT NULL,
  `voided_by` int(3) unsigned DEFAULT NULL,
  `voided_datetime` datetime DEFAULT NULL,
  `remarks` varchar(150) DEFAULT NULL,
  `pos_key` varchar(255) NOT NULL,
  `isdeleted` int(1) DEFAULT NULL,
  `dayend_date` date DEFAULT NULL,
  `created_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pos_sales_detail_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `pos_sales_detail`
--

INSERT INTO `pos_sales_detail` (`pos_sales_detail_id`, `pos_sales_id`, `sales_invoice_number`, `branch_id`, `item_id`, `discount`, `item_discount`, `total_item_discount`, `ispercent`, `unit_id`, `addon_id`, `item_code`, `item_description`, `item_short_description`, `category_description`, `department_description`, `classification_description`, `item_cost`, `item_price`, `tax`, `service_charge`, `quantity`, `sub_total`, `grand_total`, `done`, `done_date`, `order_time`, `order_sequence`, `order_by`, `created_by`, `voided_by`, `voided_datetime`, `remarks`, `pos_key`, `isdeleted`, `dayend_date`, `created_modified`) VALUES
(1, 2, '0000000001', 1, 1, 0, 0, 0, 0, 1, 0, '1', 'Ham and Egg', '', '', '', 'EGG DROP SANDWICH', 0.00, 150.00, NULL, NULL, 2.00, NULL, 300.00, 0, '', '10:57:22', NULL, NULL, 1, NULL, NULL, 'POS_SALES_DETAIL', '', 0, NULL, '2023-02-13 03:14:40'),
(2, 2, '0000000001', 1, 77, 0, 0, 0, 0, 0, 1, '', 'KETSUP', '', '', '', '', 0.00, 20.00, NULL, NULL, 2.00, NULL, 40.00, 0, '', '10:57:22', NULL, NULL, 1, NULL, NULL, 'POS_SALES_DETAIL', '', 0, NULL, '2023-02-13 03:14:40'),
(3, 3, '', 1, 1, 0, 0, 0, 0, 1, 0, '1', 'Ham and Egg', '', '', '', 'EGG DROP SANDWICH', 0.00, 150.00, NULL, NULL, 1.00, NULL, 150.00, 0, '', '10:58:38', NULL, NULL, 1, NULL, NULL, 'POS_SALES_DETAIL', '', 1, NULL, '2023-02-13 03:16:08'),
(4, 3, '', 1, 77, 0, 0, 0, 0, 0, 3, '', 'KETSUP', '', '', '', '', 0.00, 20.00, NULL, NULL, 1.00, NULL, 20.00, 0, '', '10:58:38', NULL, NULL, 1, NULL, NULL, 'POS_SALES_DETAIL', '', 1, NULL, '2023-02-13 03:16:08'),
(5, 6, '', 1, 1, 0, 0, 0, 0, 1, 0, '1', 'Ham and Egg', '', '', '', 'EGG DROP SANDWICH', 0.00, 170.00, NULL, NULL, 1.00, NULL, 170.00, 0, '', '11:49:08', NULL, NULL, 1, NULL, NULL, 'POS_SALES_DETAIL', '', 0, NULL, '2023-02-13 03:49:08'),
(6, 6, '', 1, 77, 0, 0, 0, 0, 0, 5, '', 'KETSUP', '', '', '', '', 0.00, 20.00, NULL, NULL, 1.00, NULL, 20.00, 0, '', '11:49:08', NULL, NULL, 1, NULL, NULL, 'POS_SALES_DETAIL', '', 0, NULL, '2023-02-13 03:49:08'),
(7, 5, '', 1, 1, 0, 0, 0, 0, 1, 0, '1', 'Ham and Egg', '', '', '', 'EGG DROP SANDWICH', 0.00, 170.00, NULL, NULL, 1.00, NULL, 170.00, 0, '', '12:02:57', NULL, NULL, 1, NULL, NULL, 'POS_SALES_DETAIL', '', 0, NULL, '2023-02-13 04:02:57'),
(8, 5, '', 1, 77, 0, 0, 0, 0, 0, 7, '', 'KETSUP', '', '', '', '', 0.00, 20.00, NULL, NULL, 1.00, NULL, 20.00, 0, '', '12:02:57', NULL, NULL, 1, NULL, NULL, 'POS_SALES_DETAIL', '', 0, NULL, '2023-02-13 04:02:57');

-- --------------------------------------------------------

--
-- Table structure for table `pos_sales_settlement`
--

CREATE TABLE IF NOT EXISTS `pos_sales_settlement` (
  `pos_sales_settlement_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `result` varchar(50) NOT NULL,
  `transaction_date` date DEFAULT NULL,
  `pos_sales_id` int(10) unsigned DEFAULT NULL,
  `sales_invoice_number` varchar(20) DEFAULT NULL,
  `settlement_type_id` int(5) unsigned DEFAULT NULL,
  `settlement_type_code` varchar(10) DEFAULT NULL,
  `settlement_type_description` varchar(50) DEFAULT NULL,
  `settlement_amount` double(12,2) DEFAULT NULL,
  `change_amount` double(12,2) DEFAULT NULL,
  `reference_no1` varchar(20) DEFAULT NULL,
  `reference_no2` varchar(20) DEFAULT NULL,
  `with_reference_description1` varchar(100) DEFAULT NULL,
  `with_reference_description2` varchar(100) DEFAULT NULL,
  `proof_of_payment1` varchar(100) DEFAULT NULL,
  `proof_of_payment2` varchar(100) DEFAULT NULL,
  `remarks` varchar(150) DEFAULT NULL,
  `isdeleted` int(1) DEFAULT NULL,
  `created_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pos_sales_settlement_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `pos_sales_settlement`
--

INSERT INTO `pos_sales_settlement` (`pos_sales_settlement_id`, `result`, `transaction_date`, `pos_sales_id`, `sales_invoice_number`, `settlement_type_id`, `settlement_type_code`, `settlement_type_description`, `settlement_amount`, `change_amount`, `reference_no1`, `reference_no2`, `with_reference_description1`, `with_reference_description2`, `proof_of_payment1`, `proof_of_payment2`, `remarks`, `isdeleted`, `created_modified`) VALUES
(1, '', '2023-02-13', 2, '0000000001', 1, '101', 'CASH', 340.00, 0.00, '', '', '', '', '', '', '', 0, '2023-02-13 03:14:40');

-- --------------------------------------------------------

--
-- Table structure for table `referral_profile`
--

CREATE TABLE IF NOT EXISTS `referral_profile` (
  `referral_id` int(10) NOT NULL,
  `referral_no` varchar(15) DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `middlename` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `business_name` varchar(100) DEFAULT NULL,
  `contact_no1` varchar(20) DEFAULT NULL,
  `contact_no2` varchar(20) DEFAULT NULL,
  `social_media1` varchar(50) DEFAULT NULL,
  `social_media2` varchar(50) DEFAULT NULL,
  `created_by_fullname` varchar(50) DEFAULT NULL,
  `datetime_created` datetime DEFAULT NULL,
  `edited_by_fullname` varchar(50) DEFAULT NULL,
  `datetime_edited` datetime DEFAULT NULL,
  `isdeleted` int(1) DEFAULT NULL,
  `created_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `registration`
--

CREATE TABLE IF NOT EXISTS `registration` (
  `registration_id` int(10) NOT NULL AUTO_INCREMENT,
  `branch_id` int(11) NOT NULL,
  `registration_no` varchar(15) DEFAULT NULL,
  `result` varchar(50) NOT NULL,
  `registration_date` date DEFAULT NULL,
  `customer_id` int(10) DEFAULT NULL,
  `referral_id` int(10) DEFAULT NULL,
  `card_profile_id` int(10) DEFAULT NULL,
  `registration_status_id` int(1) DEFAULT NULL,
  `valid_from` date DEFAULT NULL,
  `valid_to` date DEFAULT NULL,
  `with_validity` int(1) DEFAULT NULL,
  `photo_of_applicant` varchar(100) DEFAULT NULL,
  `photo_of_identification` varchar(100) DEFAULT NULL,
  `posted_by_fullname` varchar(50) DEFAULT NULL,
  `datetime_posted` datetime DEFAULT NULL,
  `edited_by_fullname` varchar(50) DEFAULT NULL,
  `datetime_edited` datetime DEFAULT NULL,
  `isdeleted` int(1) DEFAULT NULL,
  `created_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`registration_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `registration_history`
--

CREATE TABLE IF NOT EXISTS `registration_history` (
  `registration_id` int(10) NOT NULL,
  `registration_no` varchar(15) DEFAULT NULL,
  `registration_date` date DEFAULT NULL,
  `customer_id` int(10) DEFAULT NULL,
  `referral_id` int(10) DEFAULT NULL,
  `card_profile_id` int(10) DEFAULT NULL,
  `registration_status_id` int(1) DEFAULT NULL,
  `valid_from` date DEFAULT NULL,
  `valid_to` date DEFAULT NULL,
  `with_validity` int(1) DEFAULT NULL,
  `photo_of_applicant` varchar(100) DEFAULT NULL,
  `photo_of_identification` varchar(100) DEFAULT NULL,
  `posted_by_fullname` varchar(50) DEFAULT NULL,
  `datetime_posted` datetime DEFAULT NULL,
  `edited_by_fullname` varchar(50) DEFAULT NULL,
  `datetime_edited` datetime DEFAULT NULL,
  `isdeleted` int(1) DEFAULT NULL,
  `created_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `settings_credit_line`
--

CREATE TABLE IF NOT EXISTS `settings_credit_line` (
  `settings_credit_line_id` int(3) NOT NULL,
  `credit_line_days_to_due` int(3) DEFAULT NULL,
  `credit_line_with_penalty` int(1) DEFAULT NULL,
  `credit_line_penalty_to_apply` double(3,2) DEFAULT NULL,
  `credit_line_payment_type_id` int(3) DEFAULT NULL,
  `created_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `isdeleted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `settings_customer_type`
--

CREATE TABLE IF NOT EXISTS `settings_customer_type` (
  `settings_customer_type_id` int(3) NOT NULL,
  `customer_type_id` int(3) DEFAULT NULL,
  `with_credit_line` int(1) DEFAULT NULL,
  `isdeleted` int(1) DEFAULT NULL,
  `created_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `settings_customer_type`
--

INSERT INTO `settings_customer_type` (`settings_customer_type_id`, `customer_type_id`, `with_credit_line`, `isdeleted`, `created_modified`) VALUES
(1, 1, 1, 0, '2020-05-01 03:00:13'),
(2, 2, 0, 0, '2020-05-01 03:00:24'),
(3, 3, 0, 0, '2020-05-01 03:00:32');

-- --------------------------------------------------------

--
-- Table structure for table `settings_pos_menu_group`
--

CREATE TABLE IF NOT EXISTS `settings_pos_menu_group` (
  `settings_pos_menu_group_id` int(10) unsigned NOT NULL,
  `menu_group_id` int(10) unsigned DEFAULT NULL,
  `item_id` int(10) unsigned DEFAULT NULL,
  `isdeleted` int(1) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `settings_pos_menu_group`
--

INSERT INTO `settings_pos_menu_group` (`settings_pos_menu_group_id`, `menu_group_id`, `item_id`, `isdeleted`) VALUES
(1, 1, 51, 0),
(2, 1, 58, 0),
(3, 2, 52, 0),
(4, 2, 53, 0),
(5, 3, 54, 0),
(6, 4, 55, 0),
(7, 4, 59, 0),
(8, 5, 56, 0),
(9, 6, 57, 0);

-- --------------------------------------------------------

--
-- Table structure for table `settings_pos_order_type`
--

CREATE TABLE IF NOT EXISTS `settings_pos_order_type` (
  `settings_pos_order_type_id` int(10) unsigned NOT NULL,
  `order_type_id` int(5) unsigned DEFAULT NULL,
  `table_id` int(5) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `settings_pos_receipt_footer`
--

CREATE TABLE IF NOT EXISTS `settings_pos_receipt_footer` (
  `pos_receipt_footer_id` int(3) unsigned NOT NULL,
  `branch_id` int(5) unsigned NOT NULL DEFAULT '0',
  `line1` varchar(40) DEFAULT NULL,
  `line2` varchar(40) DEFAULT NULL,
  `line3` varchar(40) DEFAULT NULL,
  `line4` varchar(40) DEFAULT NULL,
  `line5` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `settings_pos_receipt_footer`
--

INSERT INTO `settings_pos_receipt_footer` (`pos_receipt_footer_id`, `branch_id`, `line1`, `line2`, `line3`, `line4`, `line5`) VALUES
(1, 1, 'THIS IS NOT OFFICIAL RECEIPT', '-', '-', '-', 'THANK YOU');

-- --------------------------------------------------------

--
-- Table structure for table `settings_pos_receipt_header`
--

CREATE TABLE IF NOT EXISTS `settings_pos_receipt_header` (
  `pos_receipt_header_id` int(3) unsigned NOT NULL,
  `branch_id` int(5) unsigned NOT NULL DEFAULT '0',
  `line1` varchar(40) DEFAULT NULL,
  `line2` varchar(40) DEFAULT NULL,
  `line3` varchar(40) DEFAULT NULL,
  `line4` varchar(40) DEFAULT NULL,
  `line5` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `settings_pos_receipt_header`
--

INSERT INTO `settings_pos_receipt_header` (`pos_receipt_header_id`, `branch_id`, `line1`, `line2`, `line3`, `line4`, `line5`) VALUES
(2, 1, 'AZCALIVA CORPORATION', 'Pasuquin, Ilocos Norte', '-', '-', '-');

-- --------------------------------------------------------

--
-- Table structure for table `settings_pos_system`
--

CREATE TABLE IF NOT EXISTS `settings_pos_system` (
  `settings_pos_system_id` int(3) unsigned NOT NULL,
  `enable_service_charge_per_outlet` int(1) unsigned DEFAULT NULL,
  `menu_per_outlet` int(1) unsigned DEFAULT NULL,
  `open_table_with_out_card` int(1) unsigned DEFAULT '1',
  `reset_menu_after_ordering` int(1) unsigned DEFAULT '1',
  `show_menu_main_group` int(1) unsigned DEFAULT '0',
  `disable_add_product` int(1) unsigned DEFAULT '0',
  `default_payment_id` int(1) unsigned DEFAULT '0',
  `open_item_id` int(5) unsigned DEFAULT '0',
  `default_order_type_id` int(1) unsigned DEFAULT '0',
  `prompt_before_saving_receipt` int(1) unsigned DEFAULT '1',
  `prompt_before_printing_receipt` int(1) unsigned DEFAULT '0',
  `open_cashdrawer_before_printing_receipt` int(1) unsigned DEFAULT '0',
  `inventory_transaction_type_id` int(1) unsigned DEFAULT '0',
  `inventory_post_after_dayend` int(1) unsigned DEFAULT '0',
  `post_credit_after_dayend` int(1) unsigned DEFAULT '0',
  `credit_payment_with_points` int(1) unsigned DEFAULT '0',
  `report_sales_percent_discount` double(4,3) unsigned DEFAULT '0.000',
  `show_vat_detail` int(1) unsigned DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `settings_pos_system`
--

INSERT INTO `settings_pos_system` (`settings_pos_system_id`, `enable_service_charge_per_outlet`, `menu_per_outlet`, `open_table_with_out_card`, `reset_menu_after_ordering`, `show_menu_main_group`, `disable_add_product`, `default_payment_id`, `open_item_id`, `default_order_type_id`, `prompt_before_saving_receipt`, `prompt_before_printing_receipt`, `open_cashdrawer_before_printing_receipt`, `inventory_transaction_type_id`, `inventory_post_after_dayend`, `post_credit_after_dayend`, `credit_payment_with_points`, `report_sales_percent_discount`, `show_vat_detail`) VALUES
(1, 0, 1, 1, 1, 1, 1, 1, 2715, 2, 1, 1, 1, 4, 1, 0, 0, 0.120, 1);

-- --------------------------------------------------------

--
-- Table structure for table `settings_rebate`
--

CREATE TABLE IF NOT EXISTS `settings_rebate` (
  `settings_rebate_id` int(11) NOT NULL,
  `rebate_amount` double(12,2) DEFAULT '0.00',
  `rebate_point` double(12,2) DEFAULT '0.00',
  `referral_point` double(12,2) DEFAULT '0.00',
  `rebate_settlement_type_id` int(5) DEFAULT '0',
  `isdeleted` int(11) NOT NULL,
  `created_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `settings_rebate`
--

INSERT INTO `settings_rebate` (`settings_rebate_id`, `rebate_amount`, `rebate_point`, `referral_point`, `rebate_settlement_type_id`, `isdeleted`, `created_modified`) VALUES
(1, 150.00, 2.00, 2.00, 1, 1, '2020-06-26 19:14:09');

-- --------------------------------------------------------

--
-- Table structure for table `settings_receipt_print`
--

CREATE TABLE IF NOT EXISTS `settings_receipt_print` (
  `ID` int(11) NOT NULL,
  `enable` int(11) NOT NULL,
  `date_added` varchar(255) NOT NULL,
  `iscurrent` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `settings_receipt_print`
--

INSERT INTO `settings_receipt_print` (`ID`, `enable`, `date_added`, `iscurrent`) VALUES
(1, 0, '2022-11-08 07:51:10', 0),
(2, 1, '2022-11-08 07:58:18', 0),
(3, 0, '2022-11-08 07:58:41', 0),
(4, 1, '2022-11-08 07:58:43', 0),
(5, 0, '2022-11-08 07:58:51', 0),
(6, 1, '2022-11-08 08:01:40', 0),
(7, 0, '2022-12-04 10:38:32', 1);

-- --------------------------------------------------------

--
-- Table structure for table `settings_settlement_mapping`
--

CREATE TABLE IF NOT EXISTS `settings_settlement_mapping` (
  `settings_settlement_mapping_id` int(5) NOT NULL,
  `settlement_type_id` int(5) DEFAULT NULL,
  `direct_deposit` int(1) DEFAULT '0',
  `with_verification` int(1) DEFAULT '0',
  `with_rebate` int(1) DEFAULT '0',
  `charge_to_customer` int(1) DEFAULT '0',
  `charge_to_employee` int(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `settings_settlement_mapping`
--

INSERT INTO `settings_settlement_mapping` (`settings_settlement_mapping_id`, `settlement_type_id`, `direct_deposit`, `with_verification`, `with_rebate`, `charge_to_customer`, `charge_to_employee`) VALUES
(1, 1, 0, 0, 1, 0, 0),
(2, 2, 0, 0, 1, 1, 0),
(3, 3, 0, 0, 0, 1, 0),
(4, 4, 0, 0, 0, 0, 0),
(5, 5, 0, 0, 0, 0, 0),
(6, 6, 0, 0, 0, 0, 0),
(7, 7, 0, 0, 0, 0, 0),
(8, 8, 0, 0, 0, 0, 0),
(9, 9, 0, 0, 0, 0, 0),
(10, 10, 0, 0, 0, 0, 0),
(11, 11, 0, 0, 0, 0, 0),
(12, 12, 0, 0, 0, 0, 0),
(13, 13, 0, 0, 0, 0, 0),
(14, 14, 0, 0, 0, 0, 0),
(15, 15, 0, 0, 0, 0, 0),
(16, 16, 0, 0, 0, 0, 0),
(17, 17, 0, 0, 0, 0, 0),
(18, 18, 0, 0, 0, 0, 0),
(19, 19, 0, 0, 0, 0, 0),
(20, 20, 0, 0, 0, 0, 0),
(21, 21, 0, 0, 0, 0, 0),
(22, 22, 0, 0, 0, 0, 0),
(23, 23, 0, 0, 0, 0, 0),
(24, 24, 0, 0, 0, 0, 0),
(25, 25, 0, 0, 0, 0, 0),
(26, 26, 0, 0, 0, 0, 0),
(27, 27, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `se_module`
--

CREATE TABLE IF NOT EXISTS `se_module` (
  `module_id` int(11) NOT NULL AUTO_INCREMENT,
  `module_name` varchar(100) NOT NULL,
  `icon` varchar(50) NOT NULL,
  `module_dir` varchar(100) NOT NULL,
  `module_notif` int(11) NOT NULL,
  `active` int(10) NOT NULL,
  PRIMARY KEY (`module_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT AUTO_INCREMENT=6 ;

--
-- Dumping data for table `se_module`
--

INSERT INTO `se_module` (`module_id`, `module_name`, `icon`, `module_dir`, `module_notif`, `active`) VALUES
(1, 'CUSTOMERS', '<i class="fa fa-users"></i>', '', 0, 0),
(2, 'SYSTEM PANEL', '<i class="fa fa-desktop"></i>', '', 0, 1),
(3, 'POS & COLLECTION', '<i class="fa fa-shopping-cart"></i>', '', 0, 1),
(4, 'ADMIN & FINANCE', '<i class="fa fa-suitcase"></i>', '', 0, 1),
(5, 'INVENTORY & DELIVERY', '<i class="fa fa-list-alt"></i>', '', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `se_sub_module`
--

CREATE TABLE IF NOT EXISTS `se_sub_module` (
  `sub_module_id` int(11) NOT NULL AUTO_INCREMENT,
  `sub_module_name` varchar(100) NOT NULL,
  `sub_module_dir` varchar(100) NOT NULL,
  `module_id` int(10) DEFAULT NULL,
  `location` varchar(50) NOT NULL,
  `keyword` varchar(50) NOT NULL,
  `active` int(10) NOT NULL,
  PRIMARY KEY (`sub_module_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT AUTO_INCREMENT=36 ;

--
-- Dumping data for table `se_sub_module`
--

INSERT INTO `se_sub_module` (`sub_module_id`, `sub_module_name`, `sub_module_dir`, `module_id`, `location`, `keyword`, `active`) VALUES
(1, 'CUSTOMER PROFILES', '', 1, 'customer', 'singlecm', 1),
(2, 'REGISTRATION', '', 1, 'customer', 'regui', 1),
(3, 'REPORTS', '', 1, 'customer', 'creportui', 1),
(4, 'USER PROFILE', '', 2, 'main', 'userui', 1),
(5, 'BRANCHES', '', 2, 'main', 'branchui', 1),
(6, 'CARD TYPE', '', 2, 'main', 'cardtypeui', 0),
(7, 'CUSTOMER TYPE', '', 2, 'main', 'customertypeui', 0),
(8, 'CARD PROFILE', '', 2, 'main', 'cprofileui', 0),
(9, 'CREDIT LIMIT', '', 2, 'main', 'climitui', 0),
(10, 'REGISTRATION STATUS', '', 2, 'main', 'regstatusui', 0),
(11, 'SETTLEMENT TYPE GROUP', '', 2, 'main', 'setgroupui', 1),
(12, 'SETTLEMENT TYPE', '', 2, 'main', 'settleui', 1),
(13, 'POS LOOK UP', '', 2, 'main', 'possetui', 1),
(14, 'POS SETTINGS', '', 2, 'main', 'possettingsui', 1),
(15, 'POS', '', 3, 'pos', 'posui', 1),
(16, 'COLLECTION', '', 3, 'pos', 'colui', 0),
(17, 'SALES MONITORING', '', 4, 'finance', 'salesui', 1),
(18, 'PAYMENT MONITORING', '', 4, 'finance', 'paymentui', 0),
(19, 'REBATES', '', 4, 'finance', 'rebateui', 0),
(20, 'EXPENSES', '', 4, 'finance', 'expenseui', 1),
(21, 'REPORTS', '', 4, 'finance', 'freportui', 1),
(22, 'REPORTS', '', 3, 'pos', 'sreportui', 1),
(23, 'STOCK MANAGEMENT', '', 5, 'inventory', 'stockmui', 1),
(24, 'STOCK MONITORING', '', 5, 'inventory', 'stockmoui', 1),
(25, 'DELIVERY MONITORING', '', 5, 'inventory', 'dmonitorui', 0),
(27, 'COLLECTION MONITORING', '', 4, 'pos', 'collectionui', 1),
(28, 'POS[CREDIT LINE]', '', 3, 'pos', 'cposui', 0),
(29, 'DELIVERY DETAILS', '', 5, 'inventory', 'dstockmui', 1),
(31, 'CURRENT STOCK INQUIRY', '', 5, 'inventory', 'cstockmui', 1),
(32, 'PREPARATIONS', '', 3, 'pos', 'prepui', 1),
(33, 'ORDERS', '', 3, 'pos', 'orderui', 1),
(34, 'TABLES', '', 2, 'main', 'tableui', 1),
(35, 'TAKE ORDERS', '', 3, 'pos', 'torderui', 1);

-- --------------------------------------------------------

--
-- Table structure for table `se_user`
--

CREATE TABLE IF NOT EXISTS `se_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `agent_number` varchar(50) NOT NULL,
  `user_username` varchar(100) DEFAULT NULL,
  `user_password` varchar(100) DEFAULT NULL,
  `user_access_id` int(100) DEFAULT NULL,
  `fullname` varchar(50) NOT NULL,
  `sales_team_id` int(100) DEFAULT '0',
  `user_direct` int(100) DEFAULT NULL,
  `istable` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `user_status` int(100) DEFAULT NULL,
  `user_reset` int(11) DEFAULT NULL,
  `date_added` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `isadmin` int(1) DEFAULT '0',
  `isdeleted` int(11) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT AUTO_INCREMENT=11 ;

--
-- Dumping data for table `se_user`
--

INSERT INTO `se_user` (`user_id`, `agent_number`, `user_username`, `user_password`, `user_access_id`, `fullname`, `sales_team_id`, `user_direct`, `istable`, `branch_id`, `user_status`, `user_reset`, `date_added`, `isadmin`, `isdeleted`) VALUES
(1, '162903', 'admin', '89ce3b1d6c3ff3d7f064686b2f5b7592', 4, 'System Administrator', 1, 3, 0, 1, 2, 0, '2020-06-20 03:10:01', 1, 0),
(5, '', NULL, NULL, NULL, 'TABLE1', 0, NULL, 1, 1, NULL, NULL, '2023-02-12 23:55:22', 0, 0),
(6, '', NULL, NULL, NULL, 'table2', 0, NULL, 1, 1, NULL, NULL, '2023-02-13 00:01:06', 0, 1),
(7, '', NULL, NULL, NULL, 'table2', 0, NULL, 1, 1, NULL, NULL, '2023-02-13 00:02:17', 0, 0),
(8, '', NULL, NULL, NULL, 'TABLE3', 0, NULL, 1, 1, NULL, NULL, '2023-02-13 00:12:04', 0, 0),
(9, '', NULL, NULL, NULL, 'TABLE4', 0, NULL, 1, 1, NULL, NULL, '2023-02-13 00:03:40', 0, 0),
(10, '200', '200', 'dfda83fd22c179e62d9e138f6060a895', NULL, 'bereans1', 0, NULL, 0, 2, NULL, 1, '2023-02-13 00:10:54', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `se_user_access`
--

CREATE TABLE IF NOT EXISTS `se_user_access` (
  `user_access_id` int(11) NOT NULL,
  `user_access_name` varchar(100) DEFAULT NULL,
  `date_added` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `se_user_access`
--

INSERT INTO `se_user_access` (`user_access_id`, `user_access_name`, `date_added`) VALUES
(1, 'Sales and Marketing ', '2019-10-17 06:52:18'),
(2, 'Admin and Finance', '2019-10-17 06:52:39'),
(3, 'Delivery and Inventory', '2019-10-17 06:52:48'),
(4, 'System Administrator', '2019-10-17 06:52:48');

-- --------------------------------------------------------

--
-- Table structure for table `se_user_access_module`
--

CREATE TABLE IF NOT EXISTS `se_user_access_module` (
  `user_access_module_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `module_id` varchar(50) DEFAULT NULL,
  `sub_module_id` varchar(50) NOT NULL,
  `access_level` int(11) NOT NULL,
  `date_added` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `isdeleted` int(11) NOT NULL,
  PRIMARY KEY (`user_access_module_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT AUTO_INCREMENT=4 ;

--
-- Dumping data for table `se_user_access_module`
--

INSERT INTO `se_user_access_module` (`user_access_module_id`, `user_id`, `module_id`, `sub_module_id`, `access_level`, `date_added`, `isdeleted`) VALUES
(1, 1, 'all', 'all', 1, '2020-06-30 18:38:40', 1),
(3, 1, 'all', 'all', 1, '2023-02-13 00:05:19', 0);

-- --------------------------------------------------------

--
-- Table structure for table `sms_log`
--

CREATE TABLE IF NOT EXISTS `sms_log` (
  `sms_log_id` int(15) NOT NULL,
  `order_transaction_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `datetime_sent` datetime NOT NULL,
  `recepient` varchar(50) NOT NULL,
  `message` varchar(255) NOT NULL,
  `flash_sms` varchar(1) NOT NULL,
  `sent_by` varchar(50) NOT NULL,
  `sent_status` varchar(50) NOT NULL,
  `sent_count` int(3) DEFAULT '0',
  `priority_no` int(3) DEFAULT '0',
  `created_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sms_log_blast`
--

CREATE TABLE IF NOT EXISTS `sms_log_blast` (
  `order_transaction_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `datetime_sent` datetime NOT NULL,
  `recepient` varchar(50) NOT NULL,
  `message` varchar(255) NOT NULL,
  `flash_sms` varchar(1) NOT NULL,
  `sent_by` varchar(50) NOT NULL,
  `sent_status` varchar(50) NOT NULL,
  `sent_count` int(3) DEFAULT '0',
  `priority_no` int(3) DEFAULT '0',
  `created_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sms_log_history`
--

CREATE TABLE IF NOT EXISTS `sms_log_history` (
  `sms_log_history_id` int(15) NOT NULL,
  `sms_log_id` int(15) NOT NULL DEFAULT '0',
  `order_transaction_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `datetime_sent` datetime NOT NULL,
  `recepient` varchar(50) NOT NULL,
  `message` varchar(255) NOT NULL,
  `flash_sms` varchar(1) NOT NULL,
  `sent_by` varchar(50) NOT NULL,
  `sent_status` varchar(50) NOT NULL,
  `sent_count` int(3) DEFAULT '0',
  `priority_no` int(3) DEFAULT '0',
  `created_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `sms_lup_message_template`
--

CREATE TABLE IF NOT EXISTS `sms_lup_message_template` (
  `message_template_id` int(11) NOT NULL,
  `message_template_code` varchar(20) DEFAULT NULL,
  `message_template_description` varchar(255) DEFAULT NULL,
  `isdeleted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sms_lup_request_type`
--

CREATE TABLE IF NOT EXISTS `sms_lup_request_type` (
  `request_type_id` int(11) NOT NULL,
  `request_type_code` varchar(10) DEFAULT NULL,
  `request_type_descrption` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sms_request`
--

CREATE TABLE IF NOT EXISTS `sms_request` (
  `sms_request_id` int(15) NOT NULL,
  `request_type_id` int(3) DEFAULT NULL,
  `datetime_requested` datetime DEFAULT NULL,
  `order_transaction_id` int(10) DEFAULT NULL,
  `customer_id` int(10) DEFAULT NULL,
  `processed` int(3) DEFAULT NULL,
  `created_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sms_request_history`
--

CREATE TABLE IF NOT EXISTS `sms_request_history` (
  `sms_request_history_id` int(15) NOT NULL,
  `sms_request_id` int(15) NOT NULL DEFAULT '0',
  `request_type_id` int(3) DEFAULT NULL,
  `datetime_requested` datetime DEFAULT NULL,
  `order_transaction_id` int(10) DEFAULT NULL,
  `customer_id` int(10) DEFAULT NULL,
  `process_id` int(3) DEFAULT NULL,
  `created_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `view_collection_detail_report`
--

CREATE TABLE IF NOT EXISTS `view_collection_detail_report` (
  `collection_for` varchar(8) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `branch_description` varchar(255) DEFAULT NULL,
  `customer_no` varchar(15) DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `middlename` varchar(50) DEFAULT NULL,
  `transaction_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `receivable_no` varchar(15) DEFAULT NULL,
  `payment_for` varchar(20) DEFAULT NULL,
  `collection_amount` double(19,2) DEFAULT NULL,
  `settlement_type_id` int(5) DEFAULT NULL,
  `settlement_description` varchar(100) DEFAULT NULL,
  `settlement_type_group_id` int(5) DEFAULT NULL,
  `settlement_type_group_description` varchar(50) DEFAULT NULL,
  `created_by_fullname` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `view_collection_detail_source_report`
--

CREATE TABLE IF NOT EXISTS `view_collection_detail_source_report` (
  `customer_id` int(10) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `branch_description` varchar(255) DEFAULT NULL,
  `customer_no` varchar(15) DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `middlename` varchar(50) DEFAULT NULL,
  `transaction_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `receivable_no` varchar(15) DEFAULT NULL,
  `payment_for` varchar(20) DEFAULT NULL,
  `receivable_amount` double(12,2) DEFAULT NULL,
  `settlement_type_id` int(5) DEFAULT NULL,
  `settlement_description` varchar(100) DEFAULT NULL,
  `settlement_type_group_id` int(5) DEFAULT NULL,
  `settlement_type_group_description` varchar(50) DEFAULT NULL,
  `created_by_fullname` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `view_collection_monitoring`
--

CREATE TABLE IF NOT EXISTS `view_collection_monitoring` (
  `customer_type_id` int(11) DEFAULT NULL,
  `customer_type_name` varchar(100) DEFAULT NULL,
  `pos_sales_id` int(11) unsigned DEFAULT NULL,
  `sales_datetime` varchar(15) DEFAULT NULL,
  `sales_date` varchar(10) DEFAULT NULL,
  `sales_invoice_number` varchar(15) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `branch_code` varchar(255) DEFAULT NULL,
  `branch_description` varchar(255) DEFAULT NULL,
  `customer_id` int(11) unsigned DEFAULT NULL,
  `customer_fullname` varchar(150) DEFAULT NULL,
  `created_by_fullname` varchar(50) DEFAULT NULL,
  `total_quantity` double(10,2) DEFAULT NULL,
  `total_sales` double(10,2) DEFAULT NULL,
  `total_payment` double(19,2) DEFAULT NULL,
  `total_balance` double(19,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `view_collection_summary_report`
--

CREATE TABLE IF NOT EXISTS `view_collection_summary_report` (
  `collection_for` varchar(8) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `branch_description` varchar(255) DEFAULT NULL,
  `transaction_date` date DEFAULT NULL,
  `collection_amount` double(19,2) DEFAULT NULL,
  `settlement_type_id` int(5) DEFAULT NULL,
  `settlement_description` varchar(100) DEFAULT NULL,
  `settlement_type_group_id` int(5) DEFAULT NULL,
  `settlement_type_group_description` varchar(50) DEFAULT NULL,
  `created_by_fullname` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `view_consolidated_collection_detail_report`
--

CREATE TABLE IF NOT EXISTS `view_consolidated_collection_detail_report` (
  `collection_for` varchar(8) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `branch_description` varchar(255) DEFAULT NULL,
  `transaction_date` date DEFAULT NULL,
  `collection_amount` double(19,2) DEFAULT NULL,
  `settlement_type_id` int(5) DEFAULT NULL,
  `settlement_description` varchar(100) DEFAULT NULL,
  `settlement_type_group_id` int(5) DEFAULT NULL,
  `settlement_type_group_description` varchar(50) DEFAULT NULL,
  `created_by_fullname` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `view_consolidated_collection_summary_report`
--

CREATE TABLE IF NOT EXISTS `view_consolidated_collection_summary_report` (
  `branch_id` int(11) DEFAULT NULL,
  `branch_description` varchar(255) DEFAULT NULL,
  `transaction_date` date DEFAULT NULL,
  `collection_amount` double(19,2) DEFAULT NULL,
  `created_by_fullname` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `view_consolidated_expense_summary_report`
--

CREATE TABLE IF NOT EXISTS `view_consolidated_expense_summary_report` (
  `branch_id` int(11) DEFAULT NULL,
  `branch_description` varchar(255) DEFAULT NULL,
  `expense_date` varchar(50) DEFAULT NULL,
  `expense_description` varchar(50) DEFAULT NULL,
  `total_expense_amount` double DEFAULT NULL,
  `added_by` int(11) DEFAULT NULL,
  `fullname` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `view_consolidated_payment_detail_report`
--

CREATE TABLE IF NOT EXISTS `view_consolidated_payment_detail_report` (
  `collection_for` varchar(8) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `branch_description` varchar(255) DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `payment_amount` double(19,2) DEFAULT NULL,
  `settlement_type_id` int(5) DEFAULT NULL,
  `settlement_description` varchar(100) DEFAULT NULL,
  `settlement_type_group_id` int(5) DEFAULT NULL,
  `settlement_type_group_description` varchar(50) DEFAULT NULL,
  `created_by_fullname` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `view_consolidated_payment_summary_report`
--

CREATE TABLE IF NOT EXISTS `view_consolidated_payment_summary_report` (
  `branch_id` int(11) DEFAULT NULL,
  `branch_description` varchar(255) DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `payment_amount` double(19,2) DEFAULT NULL,
  `created_by_fullname` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `view_consolidated_rebate_summary_report`
--

CREATE TABLE IF NOT EXISTS `view_consolidated_rebate_summary_report` (
  `customer_id` int(10) DEFAULT NULL,
  `customer_no` varchar(15) DEFAULT NULL,
  `as_of` date DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `middlename` varchar(50) DEFAULT NULL,
  `transaction_no` varchar(15) DEFAULT NULL,
  `total_purchases` double(19,2) DEFAULT NULL,
  `total_rebate` double(19,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `view_consolidated_receivable_summary_report`
--

CREATE TABLE IF NOT EXISTS `view_consolidated_receivable_summary_report` (
  `branch_id` int(11) DEFAULT NULL,
  `branch_description` varchar(255) DEFAULT NULL,
  `as_of` date DEFAULT NULL,
  `branch_total_uncollected` double(19,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `view_consolidated_sales_detail_report`
--

CREATE TABLE IF NOT EXISTS `view_consolidated_sales_detail_report` (
  `sales_date` varchar(10) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `branch_code` varchar(255) DEFAULT NULL,
  `branch_description` varchar(255) DEFAULT NULL,
  `item_id` int(10) unsigned DEFAULT NULL,
  `item_description` varchar(250) DEFAULT NULL,
  `item_price` double(10,2) DEFAULT NULL,
  `total_quantity` double(19,2) DEFAULT NULL,
  `grand_total` double(19,2) DEFAULT NULL,
  `category_description` varchar(50) DEFAULT NULL,
  `department_description` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `view_consolidated_sales_summary_by_type_report`
--

CREATE TABLE IF NOT EXISTS `view_consolidated_sales_summary_by_type_report` (
  `customer_type_id` int(5) DEFAULT NULL,
  `customer_type_name` varchar(100) DEFAULT NULL,
  `sales_date` varchar(10) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `branch_code` varchar(255) DEFAULT NULL,
  `branch_description` varchar(255) DEFAULT NULL,
  `total_quantity` double(19,2) DEFAULT NULL,
  `total_sales` double(19,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `view_consolidated_sales_summary_report`
--

CREATE TABLE IF NOT EXISTS `view_consolidated_sales_summary_report` (
  `sales_date` varchar(10) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `branch_code` varchar(255) DEFAULT NULL,
  `branch_description` varchar(255) DEFAULT NULL,
  `customer_count` bigint(21) DEFAULT NULL,
  `transaction_count` bigint(21) DEFAULT NULL,
  `branch_total_quantity` double(19,2) DEFAULT NULL,
  `branch_total_sales` double(19,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `view_credit_line_detail_report`
--

CREATE TABLE IF NOT EXISTS `view_credit_line_detail_report` (
  `branch_id` int(11) DEFAULT NULL,
  `branch_description` varchar(255) DEFAULT NULL,
  `customer_id` int(10) DEFAULT NULL,
  `customer_no` varchar(15) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `middlename` varchar(50) DEFAULT NULL,
  `city_town_name` varchar(100) DEFAULT NULL,
  `barangay_name` varchar(50) DEFAULT NULL,
  `street_name` varchar(250) DEFAULT NULL,
  `credit_limit_reference_no` varchar(20) DEFAULT NULL,
  `as_of` date DEFAULT NULL,
  `total_allocated` double(19,2) DEFAULT NULL,
  `total_spent` double(19,2) DEFAULT NULL,
  `available_credit` double(19,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `view_credit_line_summary_report`
--

CREATE TABLE IF NOT EXISTS `view_credit_line_summary_report` (
  `branch_id` int(11) DEFAULT NULL,
  `branch_description` varchar(255) DEFAULT NULL,
  `total_customer` bigint(21) DEFAULT NULL,
  `total_allocated` double(19,2) DEFAULT NULL,
  `total_spent` double(19,2) DEFAULT NULL,
  `available_credit` double(19,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `view_customer_list`
--

CREATE TABLE IF NOT EXISTS `view_customer_list` (
  `customer_type_id` int(5) DEFAULT NULL,
  `customer_type_name` varchar(100) DEFAULT NULL,
  `customer_type_group_id` int(11) DEFAULT NULL,
  `customer_type_group_name` varchar(50) DEFAULT NULL,
  `customer_id` int(10) DEFAULT NULL,
  `customer_no` varchar(15) DEFAULT NULL,
  `reference_no` varchar(50) DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `middlename` varchar(50) DEFAULT NULL,
  `business_name` varchar(100) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `contact_person` varchar(150) DEFAULT NULL,
  `birthdate` varchar(50) DEFAULT NULL,
  `contact_no1` varchar(20) DEFAULT NULL,
  `contact_no2` varchar(20) DEFAULT NULL,
  `social_media1` varchar(50) DEFAULT NULL,
  `social_media2` varchar(50) DEFAULT NULL,
  `email_address` varchar(50) DEFAULT NULL,
  `isdeleted` int(1) DEFAULT NULL,
  `street_name` varchar(250) DEFAULT NULL,
  `region_name` varchar(100) DEFAULT NULL,
  `province_name` varchar(100) DEFAULT NULL,
  `city_town_name` varchar(100) DEFAULT NULL,
  `barangay_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `view_expense_detail_report`
--

CREATE TABLE IF NOT EXISTS `view_expense_detail_report` (
  `branch_id` int(11) DEFAULT NULL,
  `branch_description` varchar(255) DEFAULT NULL,
  `expense_date` varchar(50) DEFAULT NULL,
  `expense_description` varchar(50) DEFAULT NULL,
  `expense_amount` float DEFAULT NULL,
  `proof` varchar(50) DEFAULT NULL,
  `added_by` int(11) DEFAULT NULL,
  `fullname` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `view_expense_summary_report`
--

CREATE TABLE IF NOT EXISTS `view_expense_summary_report` (
  `branch_id` int(11) DEFAULT NULL,
  `branch_description` varchar(255) DEFAULT NULL,
  `expense_date` varchar(50) DEFAULT NULL,
  `expense_description` varchar(50) DEFAULT NULL,
  `total_expense_amount` double DEFAULT NULL,
  `proof` varchar(50) DEFAULT NULL,
  `added_by` int(11) DEFAULT NULL,
  `fullname` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `view_inv_stock_summary`
--

CREATE TABLE IF NOT EXISTS `view_inv_stock_summary` (
  `item_description` varchar(250) DEFAULT NULL,
  `Total` double(19,2) DEFAULT NULL,
  `unit_description` varchar(50) DEFAULT NULL,
  `category_description` varchar(50) DEFAULT NULL,
  `inventory_from` datetime DEFAULT NULL,
  `inventory_to` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `view_inv_stock_transaction_detail`
--

CREATE TABLE IF NOT EXISTS `view_inv_stock_transaction_detail` (
  `user_username` varchar(100) DEFAULT NULL,
  `remarks` varchar(150) DEFAULT NULL,
  `transaction_no` varchar(255) DEFAULT NULL,
  `category_description` varchar(50) DEFAULT NULL,
  `transaction_type_id` int(5) DEFAULT NULL,
  `item_description` varchar(250) DEFAULT NULL,
  `quantity` double(12,2) DEFAULT NULL,
  `transaction_type_description` varchar(20) DEFAULT NULL,
  `unit_id` int(5) DEFAULT NULL,
  `item_id` int(10) unsigned DEFAULT NULL,
  `inventory_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `view_inv_stock_transaction_summary`
--

CREATE TABLE IF NOT EXISTS `view_inv_stock_transaction_summary` (
  `category_description` varchar(50) DEFAULT NULL,
  `transaction_type_id` int(5) DEFAULT NULL,
  `item_description` varchar(250) DEFAULT NULL,
  `Total` double(19,2) DEFAULT NULL,
  `transaction_type_description` varchar(20) DEFAULT NULL,
  `unit_id` int(5) DEFAULT NULL,
  `item_id` int(10) unsigned DEFAULT NULL,
  `inventory_from` datetime DEFAULT NULL,
  `inventory_to` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `view_non_cash_collected_detail_report`
--

CREATE TABLE IF NOT EXISTS `view_non_cash_collected_detail_report` (
  `collection_for` varchar(8) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `branch_description` varchar(255) DEFAULT NULL,
  `payment_for` varchar(20) DEFAULT NULL,
  `customer_no` varchar(15) DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `collection_date` date DEFAULT NULL,
  `total_cash_amount` double(19,2) DEFAULT NULL,
  `settlement_description` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `view_non_cash_collected_summary_report`
--

CREATE TABLE IF NOT EXISTS `view_non_cash_collected_summary_report` (
  `collection_for` varchar(8) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `branch_description` varchar(255) DEFAULT NULL,
  `collection_date` date DEFAULT NULL,
  `total_cash_amount` double(19,2) DEFAULT NULL,
  `settlement_description` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `view_payment_detail_report`
--

CREATE TABLE IF NOT EXISTS `view_payment_detail_report` (
  `collection_for` varchar(8) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `branch_description` varchar(255) DEFAULT NULL,
  `customer_no` varchar(15) DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `middlename` varchar(50) DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `pos_sales_id` int(10) unsigned DEFAULT NULL,
  `sales_income_id` int(10) DEFAULT NULL,
  `sales_income_no` varchar(15) DEFAULT NULL,
  `payment_for` varchar(15) DEFAULT NULL,
  `payment_amount` double(19,2) DEFAULT NULL,
  `settlement_type_id` int(5) DEFAULT NULL,
  `settlement_description` varchar(100) DEFAULT NULL,
  `settlement_type_group_id` int(5) DEFAULT NULL,
  `settlement_type_group_description` varchar(50) DEFAULT NULL,
  `created_by_fullname` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `view_payment_monitor`
--

CREATE TABLE IF NOT EXISTS `view_payment_monitor` (
  `receivable_no` varchar(15) DEFAULT NULL,
  `collection_for` varchar(8) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `branch_description` varchar(255) DEFAULT NULL,
  `payment_for` varchar(20) DEFAULT NULL,
  `customer_no` varchar(15) DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `collection_date` date DEFAULT NULL,
  `total_cash_amount` double(19,2) DEFAULT NULL,
  `settlement_description` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `view_payment_summary_report`
--

CREATE TABLE IF NOT EXISTS `view_payment_summary_report` (
  `collection_for` varchar(8) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `branch_description` varchar(255) DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `payment_amount` double(19,2) DEFAULT NULL,
  `settlement_type_id` int(5) DEFAULT NULL,
  `settlement_description` varchar(100) DEFAULT NULL,
  `settlement_type_group_id` int(5) DEFAULT NULL,
  `settlement_type_group_description` varchar(50) DEFAULT NULL,
  `created_by_fullname` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `view_posting_credit_line`
--

CREATE TABLE IF NOT EXISTS `view_posting_credit_line` (
  `credit_line_transaction_id` binary(0) DEFAULT NULL,
  `branch_id` int(5) DEFAULT NULL,
  `result` char(0) DEFAULT NULL,
  `transaction_no` binary(0) DEFAULT NULL,
  `transaction_date` date DEFAULT NULL,
  `customer_id` int(10) unsigned DEFAULT NULL,
  `credit_line_transaction_type_id` int(1) DEFAULT NULL,
  `transaction_apply_to` varchar(20) DEFAULT NULL,
  `transaction_amount` double(19,2) DEFAULT NULL,
  `source_id` int(10) unsigned DEFAULT NULL,
  `source_no` varchar(15) DEFAULT NULL,
  `source_remarks` varchar(250) DEFAULT NULL,
  `transaction_remarks` char(0) DEFAULT NULL,
  `customer_fullname` varchar(150) DEFAULT NULL,
  `isdeleted` int(1) DEFAULT NULL,
  `created_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `view_posting_ledger_rebate`
--

CREATE TABLE IF NOT EXISTS `view_posting_ledger_rebate` (
  `rebate_id` binary(0) DEFAULT NULL,
  `result` char(0) DEFAULT NULL,
  `rebate_no` binary(0) DEFAULT NULL,
  `rebate_date` varchar(10) DEFAULT NULL,
  `branch_id` int(5) DEFAULT NULL,
  `customer_id` int(10) unsigned DEFAULT NULL,
  `pos_sales_id` int(10) unsigned DEFAULT NULL,
  `sales_invoice_number` varchar(15) DEFAULT NULL,
  `total_sales` double(19,2) DEFAULT NULL,
  `sales_with_rebate` double(19,2) DEFAULT NULL,
  `rebate` double(23,6) DEFAULT NULL,
  `rate_sales` double(12,2) DEFAULT NULL,
  `rate_points` double(12,2) DEFAULT NULL,
  `0` int(1) DEFAULT NULL,
  `created_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `view_posting_ledger_receivable`
--

CREATE TABLE IF NOT EXISTS `view_posting_ledger_receivable` (
  `receivable_id` binary(0) DEFAULT NULL,
  `result` char(0) DEFAULT NULL,
  `receivable_no` binary(0) DEFAULT NULL,
  `branch_id` int(5) DEFAULT NULL,
  `customer_id` int(10) unsigned DEFAULT NULL,
  `transaction_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `settlement_type_id` int(5) unsigned DEFAULT NULL,
  `transaction_amount` double(12,2) DEFAULT NULL,
  `transaction_apply_to` varchar(20) DEFAULT NULL,
  `transaction_source_number` varchar(20) DEFAULT NULL,
  `transaction_source` varchar(3) DEFAULT NULL,
  `proof_of_payment` char(0) DEFAULT NULL,
  `remarks_sales` varchar(250) DEFAULT NULL,
  `remarks_payment` varchar(150) DEFAULT NULL,
  `isdeleted` int(1) DEFAULT NULL,
  `created_by_fullname` varchar(50) DEFAULT NULL,
  `created_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `view_posting_ledger_sales_income`
--

CREATE TABLE IF NOT EXISTS `view_posting_ledger_sales_income` (
  `sales_income_id` binary(0) DEFAULT NULL,
  `result` char(0) DEFAULT NULL,
  `sales_income_no` binary(0) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `customer_id` int(11) unsigned DEFAULT NULL,
  `sales_date` varchar(10) DEFAULT NULL,
  `pos_sales_id` int(11) unsigned DEFAULT NULL,
  `settlement_type_id` bigint(11) DEFAULT NULL,
  `total_sales` double(13,2) DEFAULT NULL,
  `sales_invoice_number` varchar(20) DEFAULT NULL,
  `payment_reference_no` char(0) DEFAULT NULL,
  `transaction_type_id` bigint(20) DEFAULT NULL,
  `verified` bigint(20) DEFAULT NULL,
  `verified_datetime` binary(0) DEFAULT NULL,
  `verified_by_fullname` binary(0) DEFAULT NULL,
  `proof_of_payment` char(0) DEFAULT NULL,
  `isdeleted` bigint(20) DEFAULT NULL,
  `datetime_deleted` binary(0) DEFAULT NULL,
  `deleted_by_fullname` binary(0) DEFAULT NULL,
  `sales_remarks` varchar(250) DEFAULT NULL,
  `remarks_payment` varchar(150) DEFAULT NULL,
  `created_by_fullname` varchar(50) DEFAULT NULL,
  `created_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `view_rebate_detail_report`
--

CREATE TABLE IF NOT EXISTS `view_rebate_detail_report` (
  `branch_id` int(11) DEFAULT NULL,
  `branch_description` varchar(255) DEFAULT NULL,
  `transaction_date` date DEFAULT NULL,
  `customer_id` int(10) DEFAULT NULL,
  `customer_no` varchar(15) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `middlename` varchar(50) DEFAULT NULL,
  `transaction_no` varchar(15) DEFAULT NULL,
  `total_purchase` double(12,2) DEFAULT NULL,
  `rebate` double(12,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `view_rebate_summary_report`
--

CREATE TABLE IF NOT EXISTS `view_rebate_summary_report` (
  `branch_id` int(11) DEFAULT NULL,
  `branch_description` varchar(255) DEFAULT NULL,
  `as_of` date DEFAULT NULL,
  `customer_id` int(10) DEFAULT NULL,
  `customer_no` varchar(15) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `middlename` varchar(50) DEFAULT NULL,
  `transaction_no` varchar(15) DEFAULT NULL,
  `total_purchases` double(19,2) DEFAULT NULL,
  `total_rebate` double(19,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `view_receivable_detail_report`
--

CREATE TABLE IF NOT EXISTS `view_receivable_detail_report` (
  `branch_id` int(11) DEFAULT NULL,
  `branch_description` varchar(255) DEFAULT NULL,
  `customer_id` int(10) DEFAULT NULL,
  `customer_no` varchar(15) DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `middlename` varchar(50) DEFAULT NULL,
  `transaction_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `unpaid_bill` varchar(20) DEFAULT NULL,
  `balance` double(19,2) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `social_media1` varchar(50) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `region_name` varchar(100) DEFAULT NULL,
  `province_name` varchar(100) DEFAULT NULL,
  `city_town_name` varchar(100) DEFAULT NULL,
  `barangay_name` varchar(50) DEFAULT NULL,
  `street_name` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `view_receivable_summary_report`
--

CREATE TABLE IF NOT EXISTS `view_receivable_summary_report` (
  `branch_id` int(11) DEFAULT NULL,
  `branch_description` varchar(255) DEFAULT NULL,
  `as_of` date DEFAULT NULL,
  `customer_id` int(10) DEFAULT NULL,
  `customer_no` varchar(15) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `middlename` varchar(50) DEFAULT NULL,
  `total_unpaid_bill` double(19,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `view_registration_list`
--

CREATE TABLE IF NOT EXISTS `view_registration_list` (
  `card_profile_no` varchar(10) DEFAULT NULL,
  `card_number` varchar(50) DEFAULT NULL,
  `card_name` varchar(150) DEFAULT NULL,
  `with_validity` int(1) DEFAULT NULL,
  `valid_from` date DEFAULT NULL,
  `valid_to` date DEFAULT NULL,
  `registration_status_description` varchar(20) DEFAULT NULL,
  `registration_id` int(10) DEFAULT NULL,
  `registration_no` varchar(15) DEFAULT NULL,
  `registration_date` date DEFAULT NULL,
  `photo_of_applicant` varchar(100) DEFAULT NULL,
  `photo_of_identification` varchar(100) DEFAULT NULL,
  `referred_by` varchar(101) DEFAULT NULL,
  `referral_contact_no1` varchar(20) DEFAULT NULL,
  `referral_contact_no2` varchar(20) DEFAULT NULL,
  `customer_type_id` int(5) DEFAULT NULL,
  `customer_type_name` varchar(100) DEFAULT NULL,
  `customer_type_group_id` int(11) DEFAULT NULL,
  `customer_type_group_name` varchar(50) DEFAULT NULL,
  `customer_id` int(10) DEFAULT NULL,
  `customer_no` varchar(15) DEFAULT NULL,
  `reference_no` varchar(50) DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `middlename` varchar(50) DEFAULT NULL,
  `business_name` varchar(100) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `contact_person` varchar(150) DEFAULT NULL,
  `birthdate` varchar(50) DEFAULT NULL,
  `contact_no1` varchar(20) DEFAULT NULL,
  `contact_no2` varchar(20) DEFAULT NULL,
  `social_media1` varchar(50) DEFAULT NULL,
  `social_media2` varchar(50) DEFAULT NULL,
  `email_address` varchar(50) DEFAULT NULL,
  `isdeleted` int(1) DEFAULT NULL,
  `street_name` varchar(250) DEFAULT NULL,
  `region_name` varchar(100) DEFAULT NULL,
  `province_name` varchar(100) DEFAULT NULL,
  `city_town_name` varchar(100) DEFAULT NULL,
  `barangay_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `view_sales_detail_report`
--

CREATE TABLE IF NOT EXISTS `view_sales_detail_report` (
  `customer_type_id` int(5) DEFAULT NULL,
  `customer_type_name` varchar(100) DEFAULT NULL,
  `pos_sales_id` int(10) unsigned DEFAULT NULL,
  `sales_datetime` varchar(15) DEFAULT NULL,
  `sales_date` varchar(10) DEFAULT NULL,
  `sales_invoice_number` varchar(15) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `branch_code` varchar(255) DEFAULT NULL,
  `branch_description` varchar(255) DEFAULT NULL,
  `customer_id` int(10) unsigned DEFAULT NULL,
  `customer_fullname` varchar(150) DEFAULT NULL,
  `fullname` varchar(50) DEFAULT NULL,
  `item_id` int(10) unsigned DEFAULT NULL,
  `item_description` varchar(250) DEFAULT NULL,
  `item_price` double(10,2) DEFAULT NULL,
  `quantity` double(7,2) DEFAULT NULL,
  `grand_total` double(10,2) DEFAULT NULL,
  `category_description` varchar(50) DEFAULT NULL,
  `department_description` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `view_sales_income_detail_source_report`
--

CREATE TABLE IF NOT EXISTS `view_sales_income_detail_source_report` (
  `customer_id` int(10) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `branch_description` varchar(255) DEFAULT NULL,
  `customer_no` varchar(15) DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `middlename` varchar(50) DEFAULT NULL,
  `sales_income_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `pos_sales_id` int(10) unsigned DEFAULT NULL,
  `sales_invoice_number` varchar(15) DEFAULT NULL,
  `payment_for` varchar(20) DEFAULT NULL,
  `sales_amount` double(12,2) DEFAULT NULL,
  `created_by_fullname` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `view_sales_summary_by_type_report`
--

CREATE TABLE IF NOT EXISTS `view_sales_summary_by_type_report` (
  `customer_type_id` int(5) DEFAULT NULL,
  `customer_type_name` varchar(100) DEFAULT NULL,
  `sales_date` varchar(10) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `branch_code` varchar(255) DEFAULT NULL,
  `branch_description` varchar(255) DEFAULT NULL,
  `total_quantity` double(19,2) DEFAULT NULL,
  `total_sales` double(19,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `view_sales_summary_report`
--

CREATE TABLE IF NOT EXISTS `view_sales_summary_report` (
  `customer_type_id` int(5) DEFAULT NULL,
  `customer_type_name` varchar(100) DEFAULT NULL,
  `pos_sales_id` int(10) unsigned DEFAULT NULL,
  `sales_datetime` varchar(15) DEFAULT NULL,
  `sales_date` varchar(10) DEFAULT NULL,
  `sales_invoice_number` varchar(15) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `branch_code` varchar(255) DEFAULT NULL,
  `branch_description` varchar(255) DEFAULT NULL,
  `customer_id` int(10) unsigned DEFAULT NULL,
  `customer_fullname` varchar(150) DEFAULT NULL,
  `created_by_fullname` varchar(50) DEFAULT NULL,
  `total_quantity` double(10,2) DEFAULT NULL,
  `total_sales` double(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `view_sms_logs`
--

CREATE TABLE IF NOT EXISTS `view_sms_logs` (
  `recipient` varchar(152) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `sent_status` varchar(50) DEFAULT NULL,
  `datetime_sent` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
