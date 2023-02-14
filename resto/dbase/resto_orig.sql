-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 09, 2023 at 04:08 PM
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
-- Database: `resto`
--

-- --------------------------------------------------------

--
-- Table structure for table `card_profile`
--

CREATE TABLE `card_profile` (
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

CREATE TABLE `card_profile_history` (
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

CREATE TABLE `credit_line_allocation` (
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

CREATE TABLE `credit_line_allocation_history` (
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

CREATE TABLE `credit_line_transaction` (
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

CREATE TABLE `customer_address` (
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

CREATE TABLE `customer_profile` (
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

CREATE TABLE `customer_profile_history` (
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

CREATE TABLE `customer_shipping_address` (
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

CREATE TABLE `delivery_status` (
  `delivery_status_id` int(11) NOT NULL,
  `order_status_id` int(11) NOT NULL,
  `order_status` varchar(50) NOT NULL,
  `sms` varchar(255) NOT NULL,
  `pos_sales_id` int(11) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `isdeleted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `inv_delivery_details`
--

CREATE TABLE `inv_delivery_details` (
  `delivery_id` int(11) NOT NULL,
  `delivery_invoice_number` varchar(255) NOT NULL,
  `amount` double NOT NULL,
  `payment_terms` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `delivery_date` datetime NOT NULL,
  `date_added` varchar(255) NOT NULL,
  `added_by` int(11) NOT NULL,
  `isdeleted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `inv_lup_location`
--

CREATE TABLE `inv_lup_location` (
  `location_id` int(5) NOT NULL,
  `location_code` varchar(10) DEFAULT NULL,
  `location_description` varchar(50) DEFAULT NULL,
  `visible` int(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `inv_lup_transaction_type`
--

CREATE TABLE `inv_lup_transaction_type` (
  `transaction_type_id` int(5) NOT NULL,
  `transaction_type_code` varchar(5) NOT NULL,
  `transaction_type_description` varchar(20) NOT NULL,
  `visible` int(1) NOT NULL,
  `issales` int(11) NOT NULL,
  `isreturn` int(11) NOT NULL,
  `inventory` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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

CREATE TABLE `inv_lup_unit` (
  `unit_id` int(11) NOT NULL,
  `unit_code` varchar(10) DEFAULT NULL,
  `unit_description` varchar(50) DEFAULT NULL,
  `visible` int(1) DEFAULT '1',
  `isdeleted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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

CREATE TABLE `inv_transaction` (
  `transaction_id` int(10) NOT NULL,
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
  `isdeleted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `inv_transaction`
--

INSERT INTO `inv_transaction` (`transaction_id`, `transaction_no`, `branch_id`, `delivery_id`, `transaction_date`, `expiration_date`, `transaction_type_id`, `location_id`, `item_id`, `unit_id`, `unit_cost`, `markup`, `quantity`, `reference_no`, `remarks`, `reference_date1`, `created_by`, `created_by_datetime`, `edited_by`, `edited_by_datetime`, `created_modified`, `reference_id1`, `reference_id2`, `purpose`, `isdeleted`) VALUES
(1, '0000000001', 0, 0, '2023-01-22 14:57:12', '', 12, 1, 1055, 2, 0.00, 0, -10.00, NULL, 'TRANSACTION FROM SALES', NULL, 1, '2023-01-22 14:57:12', NULL, NULL, '2023-01-22 06:57:12', 2, NULL, NULL, 0),
(2, '0000000002', 0, 0, '2023-01-22 15:10:11', '', 12, 1, 1, 2, 0.00, 0, -100.00, NULL, 'TRANSACTION FROM SALES', NULL, 1, '2023-01-22 15:10:11', NULL, NULL, '2023-01-22 07:10:11', 3, NULL, NULL, 0),
(3, '0000000003', 0, 0, '2023-01-22 15:11:46', '', 12, 1, 1055, 2, 0.00, 0, -10.00, NULL, 'TRANSACTION FROM SALES', NULL, 1, '2023-01-22 15:11:46', NULL, NULL, '2023-01-22 07:11:46', 4, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `inv_transaction_history`
--

CREATE TABLE `inv_transaction_history` (
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

CREATE TABLE `ledger_rebate` (
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
(1, '', '1000000000', '2023-01-22', 1, 0, 4, '0000000004', 0.00, 100.00, 1.33, 150.00, 2.00, '4', 1, '2023-01-22 06:57:12'),
(2, '', '2000000000', '2023-01-22', 1, 0, 5, '0000000005', 10000.00, 10000.00, 133.33, 150.00, 2.00, '5', 0, '2023-01-22 07:10:11'),
(3, '', '3000000000', '2023-01-22', 1, 0, 6, '0000000006', 1000.00, 1000.00, 13.33, 150.00, 2.00, '6', 0, '2023-01-22 07:11:46');

-- --------------------------------------------------------

--
-- Table structure for table `ledger_receivable`
--

CREATE TABLE `ledger_receivable` (
  `receivable_id` int(10) NOT NULL,
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
  `created_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ledger_sales_income`
--

CREATE TABLE `ledger_sales_income` (
  `sales_income_id` int(10) NOT NULL,
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
  `created_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ledger_sales_income`
--

INSERT INTO `ledger_sales_income` (`sales_income_id`, `result`, `sales_income_no`, `branch_id`, `customer_id`, `sales_income_date`, `pos_sales_id`, `settlement_type_id`, `amount`, `sales_reference_no`, `payment_reference_no`, `transaction_type_id`, `verified`, `verified_datetime`, `verified_by_fullname`, `proof_of_payment`, `isdeleted`, `datetime_deleted`, `deleted_by_fullname`, `remarks_sales`, `remarks_payment`, `created_by_fullname`, `created_modified`) VALUES
(1, '', '1000000000', 1, 0, '2023-01-22', 4, 1, 0.00, '', '0', 1, 0, '0000-00-00 00:00:00', '', '', 1, '2023-01-22 14:57:49', 'System Administrator', '1', '1', 'System Administrator', '2023-01-22 06:56:57'),
(2, '', '2000000000', 1, 0, '2023-01-22', 5, 1, 10000.00, '', '0', 1, 0, '0000-00-00 00:00:00', '', '', 0, NULL, NULL, '2', '2', 'System Administrator', '2023-01-22 07:09:20'),
(3, '', '3000000000', 1, 0, '2023-01-22', 6, 1, 1000.00, '', '0', 1, 0, '0000-00-00 00:00:00', '', '', 0, NULL, NULL, '3', '3', 'System Administrator', '2023-01-22 07:11:44');

-- --------------------------------------------------------

--
-- Table structure for table `lup_barangay`
--

CREATE TABLE `lup_barangay` (
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

CREATE TABLE `lup_branch` (
  `branch_id` int(11) NOT NULL,
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
  `created_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `lup_branch`
--

INSERT INTO `lup_branch` (`branch_id`, `branch_code`, `branch_description`, `branch_photo`, `branch_contact_person1`, `branch_contact_person2`, `contact_person_photo1`, `contact_person_photo2`, `branch_contact_number1`, `branch_contact_number2`, `address`, `date_open`, `date_close`, `isdeleted`, `isactive`, `remarks`, `created_modified`) VALUES
(1, '001', 'YOLKY TOASTY', 'DA6rt70FtL_logo.png', 'NA', 'NA', 'WXrZgi1pg0_mason.png', 'j5Zx0DKyKz_TARP_1.jpg', 'NA', 'NA', 'NA', '2022-08-31', '0000-00-00', 0, 1, 'from_setup', '2023-02-05 06:31:11'),
(0, '002', 'BEREANS', 'nQjB0gwNtI_logo2.png', 'NA', 'NA', '', '', 'NA', 'NA', 'NA', '2023-02-05', '0000-00-00', 0, 1, 'from_setup', '2023-02-05 06:31:45');

-- --------------------------------------------------------

--
-- Table structure for table `lup_card_type`
--

CREATE TABLE `lup_card_type` (
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

CREATE TABLE `lup_city_town` (
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

CREATE TABLE `lup_courier` (
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

CREATE TABLE `lup_credit_line_limit` (
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

CREATE TABLE `lup_credit_line_transaction_type` (
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

CREATE TABLE `lup_customer_type` (
  `customer_type_id` int(5) NOT NULL,
  `customer_type_code` varchar(10) DEFAULT NULL,
  `customer_type_name` varchar(100) DEFAULT NULL,
  `customer_type_group` int(11) NOT NULL,
  `created_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `isdeleted` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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

CREATE TABLE `lup_customer_type_group` (
  `customer_type_group_id` int(11) NOT NULL,
  `customer_type_group_name` varchar(50) NOT NULL,
  `pricing` int(11) NOT NULL,
  `created_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `isdeleted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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

CREATE TABLE `lup_invoice_number` (
  `invoice_number_id` int(11) NOT NULL,
  `invoice_number` varchar(50) NOT NULL,
  `pos_sales_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `isdeleted` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `create_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `lup_invoice_number`
--

INSERT INTO `lup_invoice_number` (`invoice_number_id`, `invoice_number`, `pos_sales_id`, `branch_id`, `isdeleted`, `user_id`, `create_modified`) VALUES
(1, '0000000001', 1, 1, 0, 1, '2022-08-27 09:38:18'),
(2, '0000000002', 2, 1, 0, 1, '2022-08-27 09:38:18'),
(3, '0000000003', 3, 1, 0, 1, '2022-08-27 09:38:18'),
(4, '0000000004', 4, 1, 0, 1, '2022-08-27 09:38:18'),
(5, '0000000005', 5, 1, 0, 1, '2022-08-27 09:38:18'),
(6, '0000000006', 6, 1, 0, 1, '2022-08-27 09:38:18'),
(7, '0000000007', 7, 1, 0, 1, '2022-08-27 09:38:18'),
(8, '0000000008', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(9, '0000000009', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(10, '0000000010', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(11, '0000000011', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(12, '0000000012', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(13, '0000000013', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(14, '0000000014', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(15, '0000000015', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(16, '0000000016', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(17, '0000000017', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(18, '0000000018', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(19, '0000000019', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(20, '0000000020', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(21, '0000000021', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(22, '0000000022', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(23, '0000000023', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(24, '0000000024', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(25, '0000000025', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(26, '0000000026', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(27, '0000000027', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(28, '0000000028', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(29, '0000000029', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(30, '0000000030', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(31, '0000000031', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(32, '0000000032', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(33, '0000000033', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(34, '0000000034', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(35, '0000000035', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(36, '0000000036', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(37, '0000000037', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(38, '0000000038', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(39, '0000000039', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(40, '0000000040', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(41, '0000000041', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(42, '0000000042', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(43, '0000000043', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(44, '0000000044', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(45, '0000000045', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(46, '0000000046', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(47, '0000000047', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(48, '0000000048', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(49, '0000000049', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(50, '0000000050', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(51, '0000000051', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(52, '0000000052', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(53, '0000000053', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(54, '0000000054', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(55, '0000000055', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(56, '0000000056', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(57, '0000000057', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(58, '0000000058', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(59, '0000000059', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(60, '0000000060', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(61, '0000000061', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(62, '0000000062', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(63, '0000000063', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(64, '0000000064', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(65, '0000000065', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(66, '0000000066', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(67, '0000000067', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(68, '0000000068', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(69, '0000000069', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(70, '0000000070', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(71, '0000000071', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(72, '0000000072', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(73, '0000000073', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(74, '0000000074', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(75, '0000000075', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(76, '0000000076', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(77, '0000000077', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(78, '0000000078', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(79, '0000000079', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(80, '0000000080', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(81, '0000000081', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(82, '0000000082', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(83, '0000000083', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(84, '0000000084', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(85, '0000000085', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(86, '0000000086', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(87, '0000000087', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(88, '0000000088', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(89, '0000000089', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(90, '0000000090', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(91, '0000000091', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(92, '0000000092', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(93, '0000000093', 0, 1, 0, 1, '2022-08-27 09:38:18'),
(94, '0000000094', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(95, '0000000095', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(96, '0000000096', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(97, '0000000097', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(98, '0000000098', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(99, '0000000099', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(100, '0000000100', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(101, '0000000101', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(102, '0000000102', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(103, '0000000103', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(104, '0000000104', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(105, '0000000105', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(106, '0000000106', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(107, '0000000107', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(108, '0000000108', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(109, '0000000109', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(110, '0000000110', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(111, '0000000111', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(112, '0000000112', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(113, '0000000113', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(114, '0000000114', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(115, '0000000115', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(116, '0000000116', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(117, '0000000117', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(118, '0000000118', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(119, '0000000119', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(120, '0000000120', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(121, '0000000121', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(122, '0000000122', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(123, '0000000123', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(124, '0000000124', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(125, '0000000125', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(126, '0000000126', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(127, '0000000127', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(128, '0000000128', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(129, '0000000129', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(130, '0000000130', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(131, '0000000131', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(132, '0000000132', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(133, '0000000133', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(134, '0000000134', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(135, '0000000135', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(136, '0000000136', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(137, '0000000137', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(138, '0000000138', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(139, '0000000139', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(140, '0000000140', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(141, '0000000141', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(142, '0000000142', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(143, '0000000143', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(144, '0000000144', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(145, '0000000145', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(146, '0000000146', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(147, '0000000147', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(148, '0000000148', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(149, '0000000149', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(150, '0000000150', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(151, '0000000151', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(152, '0000000152', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(153, '0000000153', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(154, '0000000154', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(155, '0000000155', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(156, '0000000156', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(157, '0000000157', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(158, '0000000158', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(159, '0000000159', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(160, '0000000160', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(161, '0000000161', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(162, '0000000162', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(163, '0000000163', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(164, '0000000164', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(165, '0000000165', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(166, '0000000166', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(167, '0000000167', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(168, '0000000168', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(169, '0000000169', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(170, '0000000170', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(171, '0000000171', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(172, '0000000172', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(173, '0000000173', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(174, '0000000174', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(175, '0000000175', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(176, '0000000176', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(177, '0000000177', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(178, '0000000178', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(179, '0000000179', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(180, '0000000180', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(181, '0000000181', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(182, '0000000182', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(183, '0000000183', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(184, '0000000184', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(185, '0000000185', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(186, '0000000186', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(187, '0000000187', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(188, '0000000188', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(189, '0000000189', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(190, '0000000190', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(191, '0000000191', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(192, '0000000192', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(193, '0000000193', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(194, '0000000194', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(195, '0000000195', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(196, '0000000196', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(197, '0000000197', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(198, '0000000198', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(199, '0000000199', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(200, '0000000200', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(201, '0000000201', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(202, '0000000202', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(203, '0000000203', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(204, '0000000204', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(205, '0000000205', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(206, '0000000206', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(207, '0000000207', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(208, '0000000208', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(209, '0000000209', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(210, '0000000210', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(211, '0000000211', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(212, '0000000212', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(213, '0000000213', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(214, '0000000214', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(215, '0000000215', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(216, '0000000216', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(217, '0000000217', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(218, '0000000218', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(219, '0000000219', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(220, '0000000220', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(221, '0000000221', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(222, '0000000222', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(223, '0000000223', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(224, '0000000224', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(225, '0000000225', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(226, '0000000226', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(227, '0000000227', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(228, '0000000228', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(229, '0000000229', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(230, '0000000230', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(231, '0000000231', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(232, '0000000232', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(233, '0000000233', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(234, '0000000234', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(235, '0000000235', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(236, '0000000236', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(237, '0000000237', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(238, '0000000238', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(239, '0000000239', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(240, '0000000240', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(241, '0000000241', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(242, '0000000242', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(243, '0000000243', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(244, '0000000244', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(245, '0000000245', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(246, '0000000246', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(247, '0000000247', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(248, '0000000248', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(249, '0000000249', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(250, '0000000250', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(251, '0000000251', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(252, '0000000252', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(253, '0000000253', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(254, '0000000254', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(255, '0000000255', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(256, '0000000256', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(257, '0000000257', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(258, '0000000258', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(259, '0000000259', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(260, '0000000260', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(261, '0000000261', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(262, '0000000262', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(263, '0000000263', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(264, '0000000264', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(265, '0000000265', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(266, '0000000266', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(267, '0000000267', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(268, '0000000268', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(269, '0000000269', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(270, '0000000270', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(271, '0000000271', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(272, '0000000272', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(273, '0000000273', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(274, '0000000274', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(275, '0000000275', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(276, '0000000276', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(277, '0000000277', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(278, '0000000278', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(279, '0000000279', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(280, '0000000280', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(281, '0000000281', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(282, '0000000282', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(283, '0000000283', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(284, '0000000284', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(285, '0000000285', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(286, '0000000286', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(287, '0000000287', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(288, '0000000288', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(289, '0000000289', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(290, '0000000290', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(291, '0000000291', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(292, '0000000292', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(293, '0000000293', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(294, '0000000294', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(295, '0000000295', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(296, '0000000296', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(297, '0000000297', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(298, '0000000298', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(299, '0000000299', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(300, '0000000300', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(301, '0000000301', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(302, '0000000302', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(303, '0000000303', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(304, '0000000304', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(305, '0000000305', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(306, '0000000306', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(307, '0000000307', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(308, '0000000308', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(309, '0000000309', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(310, '0000000310', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(311, '0000000311', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(312, '0000000312', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(313, '0000000313', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(314, '0000000314', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(315, '0000000315', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(316, '0000000316', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(317, '0000000317', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(318, '0000000318', 0, 1, 0, 1, '2022-08-27 09:38:19'),
(319, '0000000319', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(320, '0000000320', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(321, '0000000321', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(322, '0000000322', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(323, '0000000323', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(324, '0000000324', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(325, '0000000325', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(326, '0000000326', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(327, '0000000327', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(328, '0000000328', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(329, '0000000329', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(330, '0000000330', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(331, '0000000331', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(332, '0000000332', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(333, '0000000333', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(334, '0000000334', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(335, '0000000335', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(336, '0000000336', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(337, '0000000337', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(338, '0000000338', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(339, '0000000339', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(340, '0000000340', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(341, '0000000341', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(342, '0000000342', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(343, '0000000343', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(344, '0000000344', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(345, '0000000345', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(346, '0000000346', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(347, '0000000347', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(348, '0000000348', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(349, '0000000349', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(350, '0000000350', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(351, '0000000351', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(352, '0000000352', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(353, '0000000353', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(354, '0000000354', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(355, '0000000355', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(356, '0000000356', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(357, '0000000357', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(358, '0000000358', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(359, '0000000359', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(360, '0000000360', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(361, '0000000361', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(362, '0000000362', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(363, '0000000363', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(364, '0000000364', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(365, '0000000365', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(366, '0000000366', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(367, '0000000367', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(368, '0000000368', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(369, '0000000369', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(370, '0000000370', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(371, '0000000371', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(372, '0000000372', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(373, '0000000373', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(374, '0000000374', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(375, '0000000375', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(376, '0000000376', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(377, '0000000377', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(378, '0000000378', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(379, '0000000379', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(380, '0000000380', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(381, '0000000381', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(382, '0000000382', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(383, '0000000383', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(384, '0000000384', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(385, '0000000385', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(386, '0000000386', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(387, '0000000387', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(388, '0000000388', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(389, '0000000389', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(390, '0000000390', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(391, '0000000391', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(392, '0000000392', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(393, '0000000393', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(394, '0000000394', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(395, '0000000395', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(396, '0000000396', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(397, '0000000397', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(398, '0000000398', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(399, '0000000399', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(400, '0000000400', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(401, '0000000401', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(402, '0000000402', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(403, '0000000403', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(404, '0000000404', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(405, '0000000405', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(406, '0000000406', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(407, '0000000407', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(408, '0000000408', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(409, '0000000409', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(410, '0000000410', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(411, '0000000411', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(412, '0000000412', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(413, '0000000413', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(414, '0000000414', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(415, '0000000415', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(416, '0000000416', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(417, '0000000417', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(418, '0000000418', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(419, '0000000419', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(420, '0000000420', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(421, '0000000421', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(422, '0000000422', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(423, '0000000423', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(424, '0000000424', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(425, '0000000425', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(426, '0000000426', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(427, '0000000427', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(428, '0000000428', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(429, '0000000429', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(430, '0000000430', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(431, '0000000431', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(432, '0000000432', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(433, '0000000433', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(434, '0000000434', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(435, '0000000435', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(436, '0000000436', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(437, '0000000437', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(438, '0000000438', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(439, '0000000439', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(440, '0000000440', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(441, '0000000441', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(442, '0000000442', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(443, '0000000443', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(444, '0000000444', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(445, '0000000445', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(446, '0000000446', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(447, '0000000447', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(448, '0000000448', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(449, '0000000449', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(450, '0000000450', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(451, '0000000451', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(452, '0000000452', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(453, '0000000453', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(454, '0000000454', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(455, '0000000455', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(456, '0000000456', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(457, '0000000457', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(458, '0000000458', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(459, '0000000459', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(460, '0000000460', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(461, '0000000461', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(462, '0000000462', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(463, '0000000463', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(464, '0000000464', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(465, '0000000465', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(466, '0000000466', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(467, '0000000467', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(468, '0000000468', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(469, '0000000469', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(470, '0000000470', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(471, '0000000471', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(472, '0000000472', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(473, '0000000473', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(474, '0000000474', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(475, '0000000475', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(476, '0000000476', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(477, '0000000477', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(478, '0000000478', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(479, '0000000479', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(480, '0000000480', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(481, '0000000481', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(482, '0000000482', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(483, '0000000483', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(484, '0000000484', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(485, '0000000485', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(486, '0000000486', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(487, '0000000487', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(488, '0000000488', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(489, '0000000489', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(490, '0000000490', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(491, '0000000491', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(492, '0000000492', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(493, '0000000493', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(494, '0000000494', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(495, '0000000495', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(496, '0000000496', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(497, '0000000497', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(498, '0000000498', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(499, '0000000499', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(500, '0000000500', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(501, '0000000501', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(502, '0000000502', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(503, '0000000503', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(504, '0000000504', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(505, '0000000505', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(506, '0000000506', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(507, '0000000507', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(508, '0000000508', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(509, '0000000509', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(510, '0000000510', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(511, '0000000511', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(512, '0000000512', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(513, '0000000513', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(514, '0000000514', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(515, '0000000515', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(516, '0000000516', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(517, '0000000517', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(518, '0000000518', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(519, '0000000519', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(520, '0000000520', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(521, '0000000521', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(522, '0000000522', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(523, '0000000523', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(524, '0000000524', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(525, '0000000525', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(526, '0000000526', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(527, '0000000527', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(528, '0000000528', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(529, '0000000529', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(530, '0000000530', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(531, '0000000531', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(532, '0000000532', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(533, '0000000533', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(534, '0000000534', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(535, '0000000535', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(536, '0000000536', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(537, '0000000537', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(538, '0000000538', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(539, '0000000539', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(540, '0000000540', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(541, '0000000541', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(542, '0000000542', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(543, '0000000543', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(544, '0000000544', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(545, '0000000545', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(546, '0000000546', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(547, '0000000547', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(548, '0000000548', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(549, '0000000549', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(550, '0000000550', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(551, '0000000551', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(552, '0000000552', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(553, '0000000553', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(554, '0000000554', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(555, '0000000555', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(556, '0000000556', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(557, '0000000557', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(558, '0000000558', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(559, '0000000559', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(560, '0000000560', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(561, '0000000561', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(562, '0000000562', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(563, '0000000563', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(564, '0000000564', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(565, '0000000565', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(566, '0000000566', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(567, '0000000567', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(568, '0000000568', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(569, '0000000569', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(570, '0000000570', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(571, '0000000571', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(572, '0000000572', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(573, '0000000573', 0, 1, 0, 1, '2022-08-27 09:38:20'),
(574, '0000000574', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(575, '0000000575', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(576, '0000000576', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(577, '0000000577', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(578, '0000000578', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(579, '0000000579', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(580, '0000000580', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(581, '0000000581', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(582, '0000000582', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(583, '0000000583', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(584, '0000000584', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(585, '0000000585', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(586, '0000000586', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(587, '0000000587', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(588, '0000000588', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(589, '0000000589', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(590, '0000000590', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(591, '0000000591', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(592, '0000000592', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(593, '0000000593', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(594, '0000000594', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(595, '0000000595', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(596, '0000000596', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(597, '0000000597', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(598, '0000000598', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(599, '0000000599', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(600, '0000000600', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(601, '0000000601', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(602, '0000000602', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(603, '0000000603', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(604, '0000000604', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(605, '0000000605', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(606, '0000000606', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(607, '0000000607', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(608, '0000000608', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(609, '0000000609', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(610, '0000000610', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(611, '0000000611', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(612, '0000000612', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(613, '0000000613', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(614, '0000000614', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(615, '0000000615', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(616, '0000000616', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(617, '0000000617', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(618, '0000000618', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(619, '0000000619', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(620, '0000000620', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(621, '0000000621', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(622, '0000000622', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(623, '0000000623', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(624, '0000000624', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(625, '0000000625', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(626, '0000000626', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(627, '0000000627', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(628, '0000000628', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(629, '0000000629', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(630, '0000000630', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(631, '0000000631', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(632, '0000000632', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(633, '0000000633', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(634, '0000000634', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(635, '0000000635', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(636, '0000000636', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(637, '0000000637', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(638, '0000000638', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(639, '0000000639', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(640, '0000000640', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(641, '0000000641', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(642, '0000000642', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(643, '0000000643', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(644, '0000000644', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(645, '0000000645', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(646, '0000000646', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(647, '0000000647', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(648, '0000000648', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(649, '0000000649', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(650, '0000000650', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(651, '0000000651', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(652, '0000000652', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(653, '0000000653', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(654, '0000000654', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(655, '0000000655', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(656, '0000000656', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(657, '0000000657', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(658, '0000000658', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(659, '0000000659', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(660, '0000000660', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(661, '0000000661', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(662, '0000000662', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(663, '0000000663', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(664, '0000000664', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(665, '0000000665', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(666, '0000000666', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(667, '0000000667', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(668, '0000000668', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(669, '0000000669', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(670, '0000000670', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(671, '0000000671', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(672, '0000000672', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(673, '0000000673', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(674, '0000000674', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(675, '0000000675', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(676, '0000000676', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(677, '0000000677', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(678, '0000000678', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(679, '0000000679', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(680, '0000000680', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(681, '0000000681', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(682, '0000000682', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(683, '0000000683', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(684, '0000000684', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(685, '0000000685', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(686, '0000000686', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(687, '0000000687', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(688, '0000000688', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(689, '0000000689', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(690, '0000000690', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(691, '0000000691', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(692, '0000000692', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(693, '0000000693', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(694, '0000000694', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(695, '0000000695', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(696, '0000000696', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(697, '0000000697', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(698, '0000000698', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(699, '0000000699', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(700, '0000000700', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(701, '0000000701', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(702, '0000000702', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(703, '0000000703', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(704, '0000000704', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(705, '0000000705', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(706, '0000000706', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(707, '0000000707', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(708, '0000000708', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(709, '0000000709', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(710, '0000000710', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(711, '0000000711', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(712, '0000000712', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(713, '0000000713', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(714, '0000000714', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(715, '0000000715', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(716, '0000000716', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(717, '0000000717', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(718, '0000000718', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(719, '0000000719', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(720, '0000000720', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(721, '0000000721', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(722, '0000000722', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(723, '0000000723', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(724, '0000000724', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(725, '0000000725', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(726, '0000000726', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(727, '0000000727', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(728, '0000000728', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(729, '0000000729', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(730, '0000000730', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(731, '0000000731', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(732, '0000000732', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(733, '0000000733', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(734, '0000000734', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(735, '0000000735', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(736, '0000000736', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(737, '0000000737', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(738, '0000000738', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(739, '0000000739', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(740, '0000000740', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(741, '0000000741', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(742, '0000000742', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(743, '0000000743', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(744, '0000000744', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(745, '0000000745', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(746, '0000000746', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(747, '0000000747', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(748, '0000000748', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(749, '0000000749', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(750, '0000000750', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(751, '0000000751', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(752, '0000000752', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(753, '0000000753', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(754, '0000000754', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(755, '0000000755', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(756, '0000000756', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(757, '0000000757', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(758, '0000000758', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(759, '0000000759', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(760, '0000000760', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(761, '0000000761', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(762, '0000000762', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(763, '0000000763', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(764, '0000000764', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(765, '0000000765', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(766, '0000000766', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(767, '0000000767', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(768, '0000000768', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(769, '0000000769', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(770, '0000000770', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(771, '0000000771', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(772, '0000000772', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(773, '0000000773', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(774, '0000000774', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(775, '0000000775', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(776, '0000000776', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(777, '0000000777', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(778, '0000000778', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(779, '0000000779', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(780, '0000000780', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(781, '0000000781', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(782, '0000000782', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(783, '0000000783', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(784, '0000000784', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(785, '0000000785', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(786, '0000000786', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(787, '0000000787', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(788, '0000000788', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(789, '0000000789', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(790, '0000000790', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(791, '0000000791', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(792, '0000000792', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(793, '0000000793', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(794, '0000000794', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(795, '0000000795', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(796, '0000000796', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(797, '0000000797', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(798, '0000000798', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(799, '0000000799', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(800, '0000000800', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(801, '0000000801', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(802, '0000000802', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(803, '0000000803', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(804, '0000000804', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(805, '0000000805', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(806, '0000000806', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(807, '0000000807', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(808, '0000000808', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(809, '0000000809', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(810, '0000000810', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(811, '0000000811', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(812, '0000000812', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(813, '0000000813', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(814, '0000000814', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(815, '0000000815', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(816, '0000000816', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(817, '0000000817', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(818, '0000000818', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(819, '0000000819', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(820, '0000000820', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(821, '0000000821', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(822, '0000000822', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(823, '0000000823', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(824, '0000000824', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(825, '0000000825', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(826, '0000000826', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(827, '0000000827', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(828, '0000000828', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(829, '0000000829', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(830, '0000000830', 0, 1, 0, 1, '2022-08-27 09:38:21'),
(831, '0000000831', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(832, '0000000832', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(833, '0000000833', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(834, '0000000834', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(835, '0000000835', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(836, '0000000836', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(837, '0000000837', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(838, '0000000838', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(839, '0000000839', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(840, '0000000840', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(841, '0000000841', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(842, '0000000842', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(843, '0000000843', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(844, '0000000844', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(845, '0000000845', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(846, '0000000846', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(847, '0000000847', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(848, '0000000848', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(849, '0000000849', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(850, '0000000850', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(851, '0000000851', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(852, '0000000852', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(853, '0000000853', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(854, '0000000854', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(855, '0000000855', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(856, '0000000856', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(857, '0000000857', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(858, '0000000858', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(859, '0000000859', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(860, '0000000860', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(861, '0000000861', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(862, '0000000862', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(863, '0000000863', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(864, '0000000864', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(865, '0000000865', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(866, '0000000866', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(867, '0000000867', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(868, '0000000868', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(869, '0000000869', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(870, '0000000870', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(871, '0000000871', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(872, '0000000872', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(873, '0000000873', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(874, '0000000874', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(875, '0000000875', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(876, '0000000876', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(877, '0000000877', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(878, '0000000878', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(879, '0000000879', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(880, '0000000880', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(881, '0000000881', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(882, '0000000882', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(883, '0000000883', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(884, '0000000884', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(885, '0000000885', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(886, '0000000886', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(887, '0000000887', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(888, '0000000888', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(889, '0000000889', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(890, '0000000890', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(891, '0000000891', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(892, '0000000892', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(893, '0000000893', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(894, '0000000894', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(895, '0000000895', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(896, '0000000896', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(897, '0000000897', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(898, '0000000898', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(899, '0000000899', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(900, '0000000900', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(901, '0000000901', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(902, '0000000902', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(903, '0000000903', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(904, '0000000904', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(905, '0000000905', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(906, '0000000906', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(907, '0000000907', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(908, '0000000908', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(909, '0000000909', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(910, '0000000910', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(911, '0000000911', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(912, '0000000912', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(913, '0000000913', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(914, '0000000914', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(915, '0000000915', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(916, '0000000916', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(917, '0000000917', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(918, '0000000918', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(919, '0000000919', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(920, '0000000920', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(921, '0000000921', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(922, '0000000922', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(923, '0000000923', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(924, '0000000924', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(925, '0000000925', 0, 1, 0, 1, '2022-08-27 09:38:22');
INSERT INTO `lup_invoice_number` (`invoice_number_id`, `invoice_number`, `pos_sales_id`, `branch_id`, `isdeleted`, `user_id`, `create_modified`) VALUES
(926, '0000000926', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(927, '0000000927', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(928, '0000000928', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(929, '0000000929', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(930, '0000000930', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(931, '0000000931', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(932, '0000000932', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(933, '0000000933', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(934, '0000000934', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(935, '0000000935', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(936, '0000000936', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(937, '0000000937', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(938, '0000000938', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(939, '0000000939', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(940, '0000000940', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(941, '0000000941', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(942, '0000000942', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(943, '0000000943', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(944, '0000000944', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(945, '0000000945', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(946, '0000000946', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(947, '0000000947', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(948, '0000000948', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(949, '0000000949', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(950, '0000000950', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(951, '0000000951', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(952, '0000000952', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(953, '0000000953', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(954, '0000000954', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(955, '0000000955', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(956, '0000000956', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(957, '0000000957', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(958, '0000000958', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(959, '0000000959', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(960, '0000000960', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(961, '0000000961', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(962, '0000000962', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(963, '0000000963', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(964, '0000000964', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(965, '0000000965', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(966, '0000000966', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(967, '0000000967', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(968, '0000000968', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(969, '0000000969', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(970, '0000000970', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(971, '0000000971', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(972, '0000000972', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(973, '0000000973', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(974, '0000000974', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(975, '0000000975', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(976, '0000000976', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(977, '0000000977', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(978, '0000000978', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(979, '0000000979', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(980, '0000000980', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(981, '0000000981', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(982, '0000000982', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(983, '0000000983', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(984, '0000000984', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(985, '0000000985', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(986, '0000000986', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(987, '0000000987', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(988, '0000000988', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(989, '0000000989', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(990, '0000000990', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(991, '0000000991', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(992, '0000000992', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(993, '0000000993', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(994, '0000000994', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(995, '0000000995', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(996, '0000000996', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(997, '0000000997', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(998, '0000000998', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(999, '0000000999', 0, 1, 0, 1, '2022-08-27 09:38:22'),
(1000, '0000001000', 0, 1, 0, 1, '2022-08-27 09:38:22');

-- --------------------------------------------------------

--
-- Table structure for table `lup_province`
--

CREATE TABLE `lup_province` (
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

CREATE TABLE `lup_region` (
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

CREATE TABLE `lup_registration_status` (
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

CREATE TABLE `lup_sales_team` (
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

CREATE TABLE `lup_settlement_type` (
  `settlement_type_id` int(5) NOT NULL,
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
  `created_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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

CREATE TABLE `lup_settlement_type_group` (
  `settlement_type_group_id` int(5) NOT NULL,
  `settlement_type_group_code` varchar(10) DEFAULT NULL,
  `settlement_type_group_description` varchar(50) DEFAULT NULL,
  `isdeleted` int(1) DEFAULT NULL,
  `created_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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

CREATE TABLE `lup_supplier` (
  `supplier_id` int(5) NOT NULL,
  `supplier_code` varchar(5) DEFAULT NULL,
  `supplier_description` varchar(50) DEFAULT NULL,
  `visible` int(1) DEFAULT NULL,
  `isdeleted` int(1) DEFAULT NULL,
  `created_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `lup_variations`
--

CREATE TABLE `lup_variations` (
  `variation_id` int(10) NOT NULL,
  `ref_no` varchar(10) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `item_id` int(5) DEFAULT NULL,
  `unit_price` double(12,2) DEFAULT NULL,
  `created_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `isdeleted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `lup_variations`
--

INSERT INTO `lup_variations` (`variation_id`, `ref_no`, `description`, `item_id`, `unit_price`, `created_modified`, `isdeleted`) VALUES
(1, 'LCURJ', 'small', 67, 100.00, '2023-02-09 14:07:36', 1),
(2, 'E336V', 'medium', 67, 120.00, '2023-02-09 14:16:57', 1),
(3, '0OURY', 'SMALLER', 67, 100.00, '2023-02-09 14:36:50', 1),
(4, 'UBRP8', '1', 67, 1.00, '2023-02-09 14:43:19', 1),
(5, 'VO1WR', '1', 43, 1.00, '2023-02-09 14:45:07', 1),
(6, 'DAAUZ', '1', 43, 1.00, '2023-02-09 14:50:32', 1),
(7, 'B32ZY', '11', 43, 1.00, '2023-02-09 14:50:38', 1);

-- --------------------------------------------------------

--
-- Table structure for table `order_expense`
--

CREATE TABLE `order_expense` (
  `expense_id` int(11) NOT NULL,
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
  `isdeleted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pos_lup_category`
--

CREATE TABLE `pos_lup_category` (
  `category_id` int(5) NOT NULL,
  `category_code` varchar(5) DEFAULT NULL,
  `category_description` varchar(50) DEFAULT NULL,
  `category_photo` varchar(100) DEFAULT NULL,
  `classification_id` int(5) DEFAULT NULL,
  `visible` int(1) DEFAULT NULL,
  `isdeleted` int(1) DEFAULT NULL,
  `created_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `pos_lup_classification`
--

CREATE TABLE `pos_lup_classification` (
  `classification_id` int(5) NOT NULL,
  `classification_code` varchar(5) DEFAULT NULL,
  `classification_description` varchar(50) DEFAULT NULL,
  `department_id` int(5) DEFAULT NULL,
  `visible` int(1) DEFAULT NULL,
  `branch_id` int(11) NOT NULL,
  `isdeleted` int(1) DEFAULT NULL,
  `created_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

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

CREATE TABLE `pos_lup_department` (
  `department_id` int(5) NOT NULL,
  `department_code` varchar(5) DEFAULT NULL,
  `department_description` varchar(50) DEFAULT NULL,
  `visible` int(1) DEFAULT NULL,
  `isdeleted` int(1) DEFAULT NULL,
  `created_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `pos_lup_department`
--

INSERT INTO `pos_lup_department` (`department_id`, `department_code`, `department_description`, `visible`, `isdeleted`, `created_modified`) VALUES
(1, '-', '-', 1, 0, '2022-08-25 08:09:14');

-- --------------------------------------------------------

--
-- Table structure for table `pos_lup_item`
--

CREATE TABLE `pos_lup_item` (
  `item_id` int(10) UNSIGNED NOT NULL,
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
  `open_price` int(1) UNSIGNED DEFAULT NULL,
  `category_id` int(3) UNSIGNED DEFAULT NULL,
  `classification_id` int(3) UNSIGNED DEFAULT NULL,
  `department_id` int(3) UNSIGNED DEFAULT NULL,
  `branch_id` int(11) NOT NULL,
  `remarks` varchar(250) DEFAULT NULL,
  `visible` int(1) UNSIGNED ZEROFILL DEFAULT '1',
  `isdeleted` int(1) UNSIGNED DEFAULT '0',
  `inventory_item` int(1) UNSIGNED DEFAULT '1',
  `created_by_fullname` varchar(50) DEFAULT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `edited_by` varchar(50) DEFAULT NULL,
  `edited_datetime` datetime DEFAULT NULL,
  `created_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `pos_lup_item`
--

INSERT INTO `pos_lup_item` (`item_id`, `item_code`, `item_description`, `item_short_description`, `unit_id`, `quantity`, `item_barcode`, `item_photo`, `item_price1`, `item_price2`, `item_price3`, `item_cost1`, `item_cost2`, `open_price`, `category_id`, `classification_id`, `department_id`, `branch_id`, `remarks`, `visible`, `isdeleted`, `inventory_item`, `created_by_fullname`, `created_datetime`, `edited_by`, `edited_datetime`, `created_modified`) VALUES
(1, '1', 'Ham and Egg', '', 1, 1, '', '', 169.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 1, 0, 1, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, '2', 'Bacon and Egg', '', 1, 1, '', '', 169.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 1, 0, 1, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, '3', 'Avocado and Egg', '', 1, 1, '', '', 169.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 1, 0, 1, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, '4', 'Spam and Egg', '', 1, 1, '', '', 179.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 1, 0, 1, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, '5', 'Beef Bulgogi and Egg', '', 1, 1, '', '', 179.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 1, 0, 1, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, '6', 'Grilled Chicken and Egg', '', 1, 1, '', '', 179.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 1, 0, 1, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, '7', 'Garlic Butter Shrimp and Egg', '', 1, 1, '', '', 219.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 1, 0, 1, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(8, '8', 'Strawberry Yogurt', '', 1, 1, '', '', 170.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 2, 0, 1, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(9, '9', 'Blueberry Yogurt', '', 1, 1, '', '', 170.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 2, 0, 1, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10, '10', 'Mixed Berries', '', 1, 1, '', '', 180.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 2, 0, 1, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(11, '11', 'Turkey Ham and Egg', '', 1, 1, '', '', 229.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 3, 0, 1, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(12, '12', 'Beef Pastrami and Egg', '', 1, 1, '', '', 229.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 3, 0, 1, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(13, '13', 'Spam Fried Rice', '', 1, 1, '', '', 169.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 4, 0, 1, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(14, '14', 'Ham Fried Rice', '', 1, 1, '', '', 169.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 4, 0, 1, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(15, '15', 'Bacon Fried Rice', '', 1, 1, '', '', 169.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 4, 0, 1, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(16, '16', 'Sausage Fried Rice', '', 1, 1, '', '', 169.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 4, 0, 1, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(17, '17', 'Bulgogi Fried Rice', '', 1, 1, '', '', 179.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 4, 0, 1, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(18, '18', 'Chicken Fried Rice', '', 1, 1, '', '', 179.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 4, 0, 1, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(19, '19', 'Honey Soy', '', 1, 1, '', '', 189.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 5, 0, 1, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(20, '20', 'Gochujang', '', 1, 1, '', '', 189.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 5, 0, 1, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(21, '21', 'Egg', '', 1, 1, '', '', 20.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 6, 0, 1, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(22, '22', 'Fried Rice', '', 1, 1, '', '', 55.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 6, 0, 1, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(23, '23', 'Bacon  ', '', 1, 1, '', '', 60.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 6, 0, 1, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(24, '24', 'Ham  ', '', 1, 1, '', '', 60.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 6, 0, 1, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(25, '25', 'Sausage   ', '', 1, 1, '', '', 60.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 6, 0, 1, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(26, '26', 'Spam  ', '', 1, 1, '', '', 70.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 6, 0, 1, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(27, '27', 'Bulgogi  ', '', 1, 1, '', '', 70.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 6, 0, 1, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(28, '28', 'Chicken  ', '', 1, 1, '', '', 70.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 6, 0, 1, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(29, '29', 'Cheese', '', 1, 1, '', '', 20.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 6, 0, 1, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(30, '30', 'Egg', '', 1, 1, '', '', 20.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 6, 0, 1, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(31, '31', 'Coleslaw', '', 1, 1, '', '', 20.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 6, 0, 1, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(32, '32', 'Sauce', '', 1, 1, '', '', 20.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 6, 0, 1, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(33, '33', 'Spam   ', '', 1, 1, '', '', 70.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 6, 0, 1, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(34, '34', 'Bacon   ', '', 1, 1, '', '', 60.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 6, 0, 1, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(35, '35', 'Ham', '', 1, 1, '', '', 60.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 6, 0, 1, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(36, '36', 'Carbonara', '', 1, 1, '', '', 220.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 7, 0, 2, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(37, '37', 'Chicken Alfredo', '', 1, 1, '', '', 249.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 7, 0, 2, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(38, '38', 'Pesto', '', 1, 1, '', '', 249.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 7, 0, 2, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(39, '39', 'Bolognese', '', 1, 1, '', '', 249.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 7, 0, 2, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(40, '40', 'Creamy Negra Scampi', '', 1, 1, '', '', 269.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 7, 0, 2, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(41, '41', 'Arrabiata ', '', 1, 1, '', '', 269.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 7, 0, 2, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(42, '42', 'Espresso', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 8, 0, 2, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(43, '43', 'Americano', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 8, 0, 2, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(44, '44', 'Latte', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 8, 0, 2, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(45, '45', 'Cappucino', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 8, 0, 2, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(46, '46', 'Mocha', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 8, 0, 2, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(47, '47', 'Caramel Macchiato ', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 8, 0, 2, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(48, '48', 'White Chocolate Mocha', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 8, 0, 2, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(49, '49', 'Salted Caramel Latte', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 8, 0, 2, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(50, '50', 'Butterscotch Latte', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 8, 0, 2, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(51, '51', 'Chocolate', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 9, 0, 2, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(52, '52', 'Matcha', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 9, 0, 2, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(53, '53', 'Chamomile', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 10, 0, 2, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(54, '54', 'Red Berry', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 10, 0, 2, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(55, '55', 'Jasmine', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 10, 0, 2, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(56, '56', 'Earl grey', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 10, 0, 2, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(57, '57', 'Honey Citron', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 10, 0, 2, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(58, '58', 'Butterfly Tea', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 10, 0, 2, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(59, '59', 'Bottled Water', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 11, 0, 2, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(60, '60', 'Coke Regular', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 11, 0, 2, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(61, '61', 'Coke Zero', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 11, 0, 2, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(62, '62', 'Sprite', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 11, 0, 2, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(63, '63', 'Royal', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 11, 0, 2, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(64, '64', 'Plain Fries', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 12, 0, 2, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(65, '65', 'Messy Fries', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 12, 0, 2, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(66, '66', 'Chicken Wings', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 12, 0, 2, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(67, '67', '3 Dip Nachos ', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 12, 0, 2, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(68, '68', 'Veggie Balls', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 12, 0, 2, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(69, '69', 'Margherita', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 13, 0, 2, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(70, '70', 'Kids Pizza', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 13, 0, 2, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(71, '71', 'Pepperoni', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 13, 0, 2, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(72, '72', 'Pesto', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 13, 0, 2, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(73, '73', 'Quatro Formaggi', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 13, 0, 2, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(74, '74', 'Creamy Spinach', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 13, 0, 2, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(75, '75', 'Proscuitto ', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 13, 0, 2, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(76, '76', 'Arrabiata', '', 1, 1, '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 13, 0, 2, '', 1, 0, 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `pos_lup_menu_group`
--

CREATE TABLE `pos_lup_menu_group` (
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

CREATE TABLE `pos_lup_order_type` (
  `order_type_id` int(3) UNSIGNED NOT NULL,
  `order_type_code` varchar(10) DEFAULT NULL,
  `order_type_description` varchar(50) DEFAULT NULL,
  `isdeleted` int(1) DEFAULT NULL,
  `created_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pos_order_type_status`
--

CREATE TABLE `pos_order_type_status` (
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

CREATE TABLE `pos_sales` (
  `pos_sales_id` int(10) UNSIGNED NOT NULL,
  `sales_datetime` varchar(15) DEFAULT NULL,
  `sales_invoice_number` varchar(15) DEFAULT NULL,
  `branch_id` int(5) DEFAULT NULL,
  `customer_id` int(10) UNSIGNED DEFAULT NULL,
  `customer_fullname` varchar(150) DEFAULT NULL,
  `total_quantity` double(10,2) DEFAULT NULL,
  `total_sales` double(10,2) DEFAULT NULL,
  `total_service_charge` double(10,2) DEFAULT NULL,
  `total_tax` double(10,2) DEFAULT NULL,
  `total_discount` double(10,2) DEFAULT NULL,
  `card_id` int(3) DEFAULT NULL,
  `order_type_id` int(3) UNSIGNED DEFAULT NULL,
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
  `created_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pos_sales`
--

INSERT INTO `pos_sales` (`pos_sales_id`, `sales_datetime`, `sales_invoice_number`, `branch_id`, `customer_id`, `customer_fullname`, `total_quantity`, `total_sales`, `total_service_charge`, `total_tax`, `total_discount`, `card_id`, `order_type_id`, `created_by_fullname`, `order_by_fullname`, `settled_by_fullname`, `voided_by_fullname`, `voided_datetime`, `remarks`, `order_status_id`, `order_status`, `result`, `isdeleted`, `dayend_date`, `created_modified`) VALUES
(1, NULL, '', 1, 0, 'cus', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, NULL, '', 0, '', 'WZDa5sphJx', 0, NULL, '2023-01-21 07:26:37'),
(2, NULL, '', 1, 0, 'cus', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, NULL, '', 0, '', 'GmppYSFjcK', 0, NULL, '2023-01-21 07:28:39'),
(3, NULL, '', 0, 0, 'cus', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, '', 0, '', 'DXwi4sFKo0', 0, NULL, '2023-01-21 07:34:39'),
(4, '2023-01-22 14:5', '0000000004', 1, 0, 'cus', 10.00, 0.00, NULL, NULL, NULL, 0, 1, '1', NULL, NULL, '1', '2023-01-22 14:57:49', '', 0, '', 'mgWZfDGdUQ', 1, NULL, '2023-01-22 06:57:49'),
(5, '2023-01-22 15:1', '0000000005', 1, 0, 'cus', 100.00, 10000.00, NULL, NULL, NULL, 0, 1, '1', NULL, NULL, NULL, NULL, '', 0, '', 'b0VscSqboI', 0, NULL, '2023-01-22 07:10:11'),
(6, '2023-01-22 15:1', '0000000006', 1, 0, 'cus', 10.00, 1000.00, NULL, NULL, NULL, 0, 1, '1', NULL, NULL, NULL, NULL, '', 0, '', 'n8JAeDoaUT', 0, NULL, '2023-01-22 07:11:46'),
(7, NULL, '', 1, 0, 'cus', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, NULL, '', 0, '', 'P3vb7wpXHk', 0, NULL, '2023-01-22 07:11:46'),
(0, NULL, '', 1, 0, 'cus', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, NULL, '', 0, '', 'KXtdnhDsQV', 0, NULL, '2023-02-05 06:04:05'),
(0, NULL, '', 1, 0, 'cus', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, NULL, '', 0, '', 'T1jSlBXQxR', 0, NULL, '2023-02-05 06:04:07'),
(0, NULL, '', 1, 0, 'cus', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, NULL, '', 0, '', 'PFrpI9ChiI', 0, NULL, '2023-02-05 06:04:08'),
(0, NULL, '', 1, 0, 'cus', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, NULL, '', 0, '', '9ASLbGWFs1', 0, NULL, '2023-02-05 06:04:09'),
(0, NULL, '', 1, 0, 'cus', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, NULL, '', 0, '', 'ZldhBIkXWy', 0, NULL, '2023-02-05 06:04:09'),
(0, NULL, '', 1, 0, 'cus', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, NULL, '', 0, '', '2atA3pjleR', 0, NULL, '2023-02-05 06:04:09'),
(0, NULL, '', 1, 0, 'cus', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, NULL, '', 0, '', '5ofrDZDBJE', 0, NULL, '2023-02-05 06:04:10'),
(0, NULL, '', 1, 0, 'cus', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, NULL, '', 0, '', 'yVOaNdNzWW', 0, NULL, '2023-02-05 06:04:11'),
(0, NULL, '', 1, 0, 'cus', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, NULL, '', 0, '', 'HwriLqWxo6', 0, NULL, '2023-02-05 06:04:12'),
(0, NULL, '', 1, 0, 'cus', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, NULL, '', 0, '', 'cA1mWqeJND', 0, NULL, '2023-02-05 06:04:13'),
(0, NULL, '', 1, 0, 'cus', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, NULL, '', 0, '', '6xvjjJ5g81', 0, NULL, '2023-02-05 06:04:21'),
(0, NULL, '', 1, 0, 'cus', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, NULL, '', 0, '', 'v9p3AFCAYY', 0, NULL, '2023-02-05 06:11:40'),
(0, NULL, '', 1, 0, 'cus', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, NULL, '', 0, '', 'HSkrziiKYH', 0, NULL, '2023-02-05 06:16:32'),
(0, NULL, '', 1, 0, 'cus', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, NULL, '', 0, '', 'FccAa1Zueg', 0, NULL, '2023-02-05 06:16:33'),
(0, NULL, '', 1, 0, 'cus', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, NULL, '', 0, '', 'vF8po5jyQA', 0, NULL, '2023-02-05 07:17:52');

-- --------------------------------------------------------

--
-- Table structure for table `pos_sales_detail`
--

CREATE TABLE `pos_sales_detail` (
  `pos_sales_detail_id` int(10) UNSIGNED NOT NULL,
  `pos_sales_id` int(10) UNSIGNED DEFAULT NULL,
  `sales_invoice_number` varchar(15) DEFAULT NULL,
  `branch_id` int(5) DEFAULT NULL,
  `item_id` int(10) UNSIGNED DEFAULT NULL,
  `discount` double NOT NULL,
  `item_discount` double NOT NULL,
  `total_item_discount` double NOT NULL,
  `ispercent` int(11) NOT NULL,
  `unit_id` int(11) NOT NULL,
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
  `order_time` time DEFAULT NULL,
  `order_sequence` int(3) UNSIGNED DEFAULT NULL,
  `order_by` int(3) UNSIGNED DEFAULT NULL,
  `created_by` int(3) UNSIGNED DEFAULT NULL,
  `voided_by` int(3) UNSIGNED DEFAULT NULL,
  `voided_datetime` datetime DEFAULT NULL,
  `remarks` varchar(150) DEFAULT NULL,
  `isdeleted` int(1) DEFAULT NULL,
  `dayend_date` date DEFAULT NULL,
  `created_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pos_sales_detail`
--

INSERT INTO `pos_sales_detail` (`pos_sales_detail_id`, `pos_sales_id`, `sales_invoice_number`, `branch_id`, `item_id`, `discount`, `item_discount`, `total_item_discount`, `ispercent`, `unit_id`, `item_code`, `item_description`, `item_short_description`, `category_description`, `department_description`, `classification_description`, `item_cost`, `item_price`, `tax`, `service_charge`, `quantity`, `sub_total`, `grand_total`, `done`, `order_time`, `order_sequence`, `order_by`, `created_by`, `voided_by`, `voided_datetime`, `remarks`, `isdeleted`, `dayend_date`, `created_modified`) VALUES
(1, 4, '0000000004', 1, 1, 0, 0, 0, 0, 2, '000001', 'Bear Brand Adult 180g', 'Bear Brand Adult 180', '-', '', 'MILK NESTLE', 0.00, 0.00, NULL, NULL, 1.00, NULL, 0.00, 0, '14:55:58', NULL, NULL, 1, NULL, NULL, 'POS_SALES_DETAIL', 1, NULL, '2023-01-22 06:57:12'),
(2, 4, '0000000004', 1, 1055, 0, 0, 0, 0, 2, '001055', 'Acyclovir 400mg', 'Acyclovir 400mg', 'GENERIC', '', 'TABLET CAPSULES', 0.00, 0.00, NULL, NULL, 10.00, NULL, 0.00, 0, '14:56:12', NULL, NULL, 1, NULL, NULL, 'POS_SALES_DETAIL', 1, NULL, '2023-01-22 06:57:49'),
(3, 5, '0000000005', 1, 1, 0, 0, 0, 0, 2, '000001', 'Bear Brand Adult 180g', 'Bear Brand Adult 180', '-', '', 'MILK NESTLE', 0.00, 100.00, NULL, NULL, 100.00, NULL, 10000.00, 0, '15:08:59', NULL, NULL, 1, NULL, NULL, 'POS_SALES_DETAIL', 0, NULL, '2023-01-22 07:10:11'),
(4, 6, '0000000006', 1, 1055, 0, 0, 0, 0, 2, '001055', 'Acyclovir 400mg', 'Acyclovir 400mg', 'GENERIC', '', 'TABLET CAPSULES', 0.00, 100.00, NULL, NULL, 10.00, NULL, 1000.00, 0, '15:11:34', NULL, NULL, 1, NULL, NULL, 'POS_SALES_DETAIL', 0, NULL, '2023-01-22 07:11:46'),
(5, 7, '', 1, 1, 0, 0, 0, 0, 2, '000001', 'Bear Brand Adult 180g', 'Bear Brand Adult 180', '-', '', 'MILK NESTLE', 0.00, 100.00, NULL, NULL, 10.00, NULL, 1000.00, 0, '15:13:19', NULL, NULL, 1, NULL, NULL, 'POS_SALES_DETAIL', 1, NULL, '2023-01-22 07:14:35'),
(6, 7, '', 1, 1, 0, 0, 0, 0, 2, '000001', 'Bear Brand Adult 180g', 'Bear Brand Adult 180', '-', '', 'MILK NESTLE', 0.00, 100.00, NULL, NULL, 10.00, NULL, 1000.00, 0, '15:14:24', NULL, NULL, 1, NULL, NULL, 'POS_SALES_DETAIL', 1, NULL, '2023-01-22 07:14:35');

-- --------------------------------------------------------

--
-- Table structure for table `pos_sales_settlement`
--

CREATE TABLE `pos_sales_settlement` (
  `pos_sales_settlement_id` int(10) UNSIGNED NOT NULL,
  `result` varchar(50) NOT NULL,
  `transaction_date` date DEFAULT NULL,
  `pos_sales_id` int(10) UNSIGNED DEFAULT NULL,
  `sales_invoice_number` varchar(20) DEFAULT NULL,
  `settlement_type_id` int(5) UNSIGNED DEFAULT NULL,
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
  `created_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pos_sales_settlement`
--

INSERT INTO `pos_sales_settlement` (`pos_sales_settlement_id`, `result`, `transaction_date`, `pos_sales_id`, `sales_invoice_number`, `settlement_type_id`, `settlement_type_code`, `settlement_type_description`, `settlement_amount`, `change_amount`, `reference_no1`, `reference_no2`, `with_reference_description1`, `with_reference_description2`, `proof_of_payment1`, `proof_of_payment2`, `remarks`, `isdeleted`, `created_modified`) VALUES
(1, '', '2023-01-22', 4, '0000000004', 1, '101', 'CASH', 100.00, 100.00, '', '', '', '', '', '', '', 1, '2023-01-22 06:57:49'),
(2, '', '2023-01-22', 5, '0000000005', 1, '101', 'CASH', 10000.00, 0.00, '', '', '', '', '', '', '', 0, '2023-01-22 07:10:11'),
(3, '', '2023-01-22', 6, '0000000006', 1, '101', 'CASH', 1000.00, 0.00, '', '', '', '', '', '', '', 0, '2023-01-22 07:11:46');

-- --------------------------------------------------------

--
-- Table structure for table `referral_profile`
--

CREATE TABLE `referral_profile` (
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

CREATE TABLE `registration` (
  `registration_id` int(10) NOT NULL,
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
  `created_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `registration_history`
--

CREATE TABLE `registration_history` (
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

CREATE TABLE `settings_credit_line` (
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

CREATE TABLE `settings_customer_type` (
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

CREATE TABLE `settings_pos_menu_group` (
  `settings_pos_menu_group_id` int(10) UNSIGNED NOT NULL,
  `menu_group_id` int(10) UNSIGNED DEFAULT NULL,
  `item_id` int(10) UNSIGNED DEFAULT NULL,
  `isdeleted` int(1) UNSIGNED DEFAULT NULL
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

CREATE TABLE `settings_pos_order_type` (
  `settings_pos_order_type_id` int(10) UNSIGNED NOT NULL,
  `order_type_id` int(5) UNSIGNED DEFAULT NULL,
  `table_id` int(5) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `settings_pos_receipt_footer`
--

CREATE TABLE `settings_pos_receipt_footer` (
  `pos_receipt_footer_id` int(3) UNSIGNED NOT NULL,
  `branch_id` int(5) UNSIGNED NOT NULL DEFAULT '0',
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

CREATE TABLE `settings_pos_receipt_header` (
  `pos_receipt_header_id` int(3) UNSIGNED NOT NULL,
  `branch_id` int(5) UNSIGNED NOT NULL DEFAULT '0',
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

CREATE TABLE `settings_pos_system` (
  `settings_pos_system_id` int(3) UNSIGNED NOT NULL,
  `enable_service_charge_per_outlet` int(1) UNSIGNED DEFAULT NULL,
  `menu_per_outlet` int(1) UNSIGNED DEFAULT NULL,
  `open_table_with_out_card` int(1) UNSIGNED DEFAULT '1',
  `reset_menu_after_ordering` int(1) UNSIGNED DEFAULT '1',
  `show_menu_main_group` int(1) UNSIGNED DEFAULT '0',
  `disable_add_product` int(1) UNSIGNED DEFAULT '0',
  `default_payment_id` int(1) UNSIGNED DEFAULT '0',
  `open_item_id` int(5) UNSIGNED DEFAULT '0',
  `default_order_type_id` int(1) UNSIGNED DEFAULT '0',
  `prompt_before_saving_receipt` int(1) UNSIGNED DEFAULT '1',
  `prompt_before_printing_receipt` int(1) UNSIGNED DEFAULT '0',
  `open_cashdrawer_before_printing_receipt` int(1) UNSIGNED DEFAULT '0',
  `inventory_transaction_type_id` int(1) UNSIGNED DEFAULT '0',
  `inventory_post_after_dayend` int(1) UNSIGNED DEFAULT '0',
  `post_credit_after_dayend` int(1) UNSIGNED DEFAULT '0',
  `credit_payment_with_points` int(1) UNSIGNED DEFAULT '0',
  `report_sales_percent_discount` double(4,3) UNSIGNED DEFAULT '0.000',
  `show_vat_detail` int(1) UNSIGNED DEFAULT '0'
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

CREATE TABLE `settings_rebate` (
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

CREATE TABLE `settings_receipt_print` (
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

CREATE TABLE `settings_settlement_mapping` (
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

CREATE TABLE `se_module` (
  `module_id` int(11) NOT NULL,
  `module_name` varchar(100) NOT NULL,
  `icon` varchar(50) NOT NULL,
  `module_dir` varchar(100) NOT NULL,
  `module_notif` int(11) NOT NULL,
  `active` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `se_module`
--

INSERT INTO `se_module` (`module_id`, `module_name`, `icon`, `module_dir`, `module_notif`, `active`) VALUES
(1, 'CUSTOMERS', '<i class=\"fa fa-users\"></i>', '', 0, 0),
(2, 'SYSTEM PANEL', '<i class=\"fa fa-desktop\"></i>', '', 0, 1),
(3, 'POS & COLLECTION', '<i class=\"fa fa-shopping-cart\"></i>', '', 0, 1),
(4, 'ADMIN & FINANCE', '<i class=\"fa fa-suitcase\"></i>', '', 0, 1),
(5, 'INVENTORY & DELIVERY', '<i class=\"fa fa-list-alt\"></i>', '', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `se_sub_module`
--

CREATE TABLE `se_sub_module` (
  `sub_module_id` int(11) NOT NULL,
  `sub_module_name` varchar(100) NOT NULL,
  `sub_module_dir` varchar(100) NOT NULL,
  `module_id` int(10) DEFAULT NULL,
  `location` varchar(50) NOT NULL,
  `keyword` varchar(50) NOT NULL,
  `active` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

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
(32, 'PREPARATIONS', '', 3, 'pos', 'prepui', 1);

-- --------------------------------------------------------

--
-- Table structure for table `se_user`
--

CREATE TABLE `se_user` (
  `user_id` int(11) NOT NULL,
  `agent_number` varchar(50) NOT NULL,
  `user_username` varchar(100) DEFAULT NULL,
  `user_password` varchar(100) DEFAULT NULL,
  `user_access_id` int(100) DEFAULT NULL,
  `fullname` varchar(50) NOT NULL,
  `sales_team_id` int(100) DEFAULT '0',
  `user_direct` int(100) DEFAULT NULL,
  `branch_id` int(11) NOT NULL,
  `user_status` int(100) DEFAULT NULL,
  `user_reset` int(11) DEFAULT NULL,
  `date_added` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `isadmin` int(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `se_user`
--

INSERT INTO `se_user` (`user_id`, `agent_number`, `user_username`, `user_password`, `user_access_id`, `fullname`, `sales_team_id`, `user_direct`, `branch_id`, `user_status`, `user_reset`, `date_added`, `isadmin`) VALUES
(1, '162903', 'admin', '89ce3b1d6c3ff3d7f064686b2f5b7592', 4, 'System Administrator', 1, 3, 1, 2, 0, '2020-06-20 03:10:01', 1),
(2, '0002', 'juan', 'e50c4880bc5abd1c53c86658d7341622', NULL, 'juan', 0, NULL, 1, NULL, 0, '2022-10-23 10:27:32', 0),
(3, '001', 'user', '68f32b5f0943904f5eac13096f25d756', NULL, 'a', 0, NULL, 1, NULL, 0, '2022-12-22 04:50:51', 0);

-- --------------------------------------------------------

--
-- Table structure for table `se_user_access`
--

CREATE TABLE `se_user_access` (
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

CREATE TABLE `se_user_access_module` (
  `user_access_module_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `module_id` varchar(50) DEFAULT NULL,
  `sub_module_id` varchar(50) NOT NULL,
  `access_level` int(11) NOT NULL,
  `date_added` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `isdeleted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `se_user_access_module`
--

INSERT INTO `se_user_access_module` (`user_access_module_id`, `user_id`, `module_id`, `sub_module_id`, `access_level`, `date_added`, `isdeleted`) VALUES
(1, 1, 'all', 'all', 1, '2020-06-30 18:38:40', 1),
(3, 1, 'all', 'all', 2, '2020-06-30 18:38:58', 0),
(4, 2, '3', '15', 2, '2022-10-23 10:26:26', 0);

-- --------------------------------------------------------

--
-- Table structure for table `sms_log`
--

CREATE TABLE `sms_log` (
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

CREATE TABLE `sms_log_blast` (
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

CREATE TABLE `sms_log_history` (
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

CREATE TABLE `sms_lup_message_template` (
  `message_template_id` int(11) NOT NULL,
  `message_template_code` varchar(20) DEFAULT NULL,
  `message_template_description` varchar(255) DEFAULT NULL,
  `isdeleted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sms_lup_request_type`
--

CREATE TABLE `sms_lup_request_type` (
  `request_type_id` int(11) NOT NULL,
  `request_type_code` varchar(10) DEFAULT NULL,
  `request_type_descrption` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sms_request`
--

CREATE TABLE `sms_request` (
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

CREATE TABLE `sms_request_history` (
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

CREATE TABLE `view_collection_detail_report` (
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

CREATE TABLE `view_collection_detail_source_report` (
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

CREATE TABLE `view_collection_monitoring` (
  `customer_type_id` int(11) DEFAULT NULL,
  `customer_type_name` varchar(100) DEFAULT NULL,
  `pos_sales_id` int(11) UNSIGNED DEFAULT NULL,
  `sales_datetime` varchar(15) DEFAULT NULL,
  `sales_date` varchar(10) DEFAULT NULL,
  `sales_invoice_number` varchar(15) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `branch_code` varchar(255) DEFAULT NULL,
  `branch_description` varchar(255) DEFAULT NULL,
  `customer_id` int(11) UNSIGNED DEFAULT NULL,
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

CREATE TABLE `view_collection_summary_report` (
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

CREATE TABLE `view_consolidated_collection_detail_report` (
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

CREATE TABLE `view_consolidated_collection_summary_report` (
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

CREATE TABLE `view_consolidated_expense_summary_report` (
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

CREATE TABLE `view_consolidated_payment_detail_report` (
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

CREATE TABLE `view_consolidated_payment_summary_report` (
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

CREATE TABLE `view_consolidated_rebate_summary_report` (
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

CREATE TABLE `view_consolidated_receivable_summary_report` (
  `branch_id` int(11) DEFAULT NULL,
  `branch_description` varchar(255) DEFAULT NULL,
  `as_of` date DEFAULT NULL,
  `branch_total_uncollected` double(19,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `view_consolidated_sales_detail_report`
--

CREATE TABLE `view_consolidated_sales_detail_report` (
  `sales_date` varchar(10) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `branch_code` varchar(255) DEFAULT NULL,
  `branch_description` varchar(255) DEFAULT NULL,
  `item_id` int(10) UNSIGNED DEFAULT NULL,
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

CREATE TABLE `view_consolidated_sales_summary_by_type_report` (
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

CREATE TABLE `view_consolidated_sales_summary_report` (
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

CREATE TABLE `view_credit_line_detail_report` (
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

CREATE TABLE `view_credit_line_summary_report` (
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

CREATE TABLE `view_customer_list` (
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

CREATE TABLE `view_expense_detail_report` (
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

CREATE TABLE `view_expense_summary_report` (
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

CREATE TABLE `view_inv_stock_summary` (
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

CREATE TABLE `view_inv_stock_transaction_detail` (
  `user_username` varchar(100) DEFAULT NULL,
  `remarks` varchar(150) DEFAULT NULL,
  `transaction_no` varchar(255) DEFAULT NULL,
  `category_description` varchar(50) DEFAULT NULL,
  `transaction_type_id` int(5) DEFAULT NULL,
  `item_description` varchar(250) DEFAULT NULL,
  `quantity` double(12,2) DEFAULT NULL,
  `transaction_type_description` varchar(20) DEFAULT NULL,
  `unit_id` int(5) DEFAULT NULL,
  `item_id` int(10) UNSIGNED DEFAULT NULL,
  `inventory_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `view_inv_stock_transaction_summary`
--

CREATE TABLE `view_inv_stock_transaction_summary` (
  `category_description` varchar(50) DEFAULT NULL,
  `transaction_type_id` int(5) DEFAULT NULL,
  `item_description` varchar(250) DEFAULT NULL,
  `Total` double(19,2) DEFAULT NULL,
  `transaction_type_description` varchar(20) DEFAULT NULL,
  `unit_id` int(5) DEFAULT NULL,
  `item_id` int(10) UNSIGNED DEFAULT NULL,
  `inventory_from` datetime DEFAULT NULL,
  `inventory_to` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `view_non_cash_collected_detail_report`
--

CREATE TABLE `view_non_cash_collected_detail_report` (
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

CREATE TABLE `view_non_cash_collected_summary_report` (
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

CREATE TABLE `view_payment_detail_report` (
  `collection_for` varchar(8) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `branch_description` varchar(255) DEFAULT NULL,
  `customer_no` varchar(15) DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `middlename` varchar(50) DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `pos_sales_id` int(10) UNSIGNED DEFAULT NULL,
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

CREATE TABLE `view_payment_monitor` (
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

CREATE TABLE `view_payment_summary_report` (
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

CREATE TABLE `view_posting_credit_line` (
  `credit_line_transaction_id` binary(0) DEFAULT NULL,
  `branch_id` int(5) DEFAULT NULL,
  `result` char(0) DEFAULT NULL,
  `transaction_no` binary(0) DEFAULT NULL,
  `transaction_date` date DEFAULT NULL,
  `customer_id` int(10) UNSIGNED DEFAULT NULL,
  `credit_line_transaction_type_id` int(1) DEFAULT NULL,
  `transaction_apply_to` varchar(20) DEFAULT NULL,
  `transaction_amount` double(19,2) DEFAULT NULL,
  `source_id` int(10) UNSIGNED DEFAULT NULL,
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

CREATE TABLE `view_posting_ledger_rebate` (
  `rebate_id` binary(0) DEFAULT NULL,
  `result` char(0) DEFAULT NULL,
  `rebate_no` binary(0) DEFAULT NULL,
  `rebate_date` varchar(10) DEFAULT NULL,
  `branch_id` int(5) DEFAULT NULL,
  `customer_id` int(10) UNSIGNED DEFAULT NULL,
  `pos_sales_id` int(10) UNSIGNED DEFAULT NULL,
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

CREATE TABLE `view_posting_ledger_receivable` (
  `receivable_id` binary(0) DEFAULT NULL,
  `result` char(0) DEFAULT NULL,
  `receivable_no` binary(0) DEFAULT NULL,
  `branch_id` int(5) DEFAULT NULL,
  `customer_id` int(10) UNSIGNED DEFAULT NULL,
  `transaction_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `settlement_type_id` int(5) UNSIGNED DEFAULT NULL,
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

CREATE TABLE `view_posting_ledger_sales_income` (
  `sales_income_id` binary(0) DEFAULT NULL,
  `result` char(0) DEFAULT NULL,
  `sales_income_no` binary(0) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `customer_id` int(11) UNSIGNED DEFAULT NULL,
  `sales_date` varchar(10) DEFAULT NULL,
  `pos_sales_id` int(11) UNSIGNED DEFAULT NULL,
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

CREATE TABLE `view_rebate_detail_report` (
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

CREATE TABLE `view_rebate_summary_report` (
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

CREATE TABLE `view_receivable_detail_report` (
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

CREATE TABLE `view_receivable_summary_report` (
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

CREATE TABLE `view_registration_list` (
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

CREATE TABLE `view_sales_detail_report` (
  `customer_type_id` int(5) DEFAULT NULL,
  `customer_type_name` varchar(100) DEFAULT NULL,
  `pos_sales_id` int(10) UNSIGNED DEFAULT NULL,
  `sales_datetime` varchar(15) DEFAULT NULL,
  `sales_date` varchar(10) DEFAULT NULL,
  `sales_invoice_number` varchar(15) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `branch_code` varchar(255) DEFAULT NULL,
  `branch_description` varchar(255) DEFAULT NULL,
  `customer_id` int(10) UNSIGNED DEFAULT NULL,
  `customer_fullname` varchar(150) DEFAULT NULL,
  `fullname` varchar(50) DEFAULT NULL,
  `item_id` int(10) UNSIGNED DEFAULT NULL,
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

CREATE TABLE `view_sales_income_detail_source_report` (
  `customer_id` int(10) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `branch_description` varchar(255) DEFAULT NULL,
  `customer_no` varchar(15) DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `middlename` varchar(50) DEFAULT NULL,
  `sales_income_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `pos_sales_id` int(10) UNSIGNED DEFAULT NULL,
  `sales_invoice_number` varchar(15) DEFAULT NULL,
  `payment_for` varchar(20) DEFAULT NULL,
  `sales_amount` double(12,2) DEFAULT NULL,
  `created_by_fullname` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `view_sales_summary_by_type_report`
--

CREATE TABLE `view_sales_summary_by_type_report` (
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

CREATE TABLE `view_sales_summary_report` (
  `customer_type_id` int(5) DEFAULT NULL,
  `customer_type_name` varchar(100) DEFAULT NULL,
  `pos_sales_id` int(10) UNSIGNED DEFAULT NULL,
  `sales_datetime` varchar(15) DEFAULT NULL,
  `sales_date` varchar(10) DEFAULT NULL,
  `sales_invoice_number` varchar(15) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `branch_code` varchar(255) DEFAULT NULL,
  `branch_description` varchar(255) DEFAULT NULL,
  `customer_id` int(10) UNSIGNED DEFAULT NULL,
  `customer_fullname` varchar(150) DEFAULT NULL,
  `created_by_fullname` varchar(50) DEFAULT NULL,
  `total_quantity` double(10,2) DEFAULT NULL,
  `total_sales` double(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `view_sms_logs`
--

CREATE TABLE `view_sms_logs` (
  `recipient` varchar(152) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `sent_status` varchar(50) DEFAULT NULL,
  `datetime_sent` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `lup_variations`
--
ALTER TABLE `lup_variations`
  ADD PRIMARY KEY (`variation_id`);

--
-- Indexes for table `pos_lup_classification`
--
ALTER TABLE `pos_lup_classification`
  ADD PRIMARY KEY (`classification_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `lup_variations`
--
ALTER TABLE `lup_variations`
  MODIFY `variation_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `pos_lup_classification`
--
ALTER TABLE `pos_lup_classification`
  MODIFY `classification_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
