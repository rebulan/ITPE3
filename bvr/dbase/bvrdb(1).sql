-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 06, 2023 at 11:01 AM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `bvrdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `client_assignment`
--

CREATE TABLE IF NOT EXISTS `client_assignment` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `iscurrent` int(11) NOT NULL,
  `date_added` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

--
-- Dumping data for table `client_assignment`
--

INSERT INTO `client_assignment` (`ID`, `client_id`, `user_id`, `iscurrent`, `date_added`) VALUES
(1, 1, 1, 0, ''),
(2, 2, 3, 0, ''),
(3, 2, 3, 0, ''),
(4, 3, 1, 0, ''),
(5, 3, 3, 0, ''),
(6, 3, 4, 0, ''),
(7, 3, 3, 0, ''),
(8, 3, 3, 0, ''),
(9, 3, 5, 0, ''),
(10, 4, 5, 0, ''),
(11, 5, 4, 0, ''),
(12, 5, 4, 0, ''),
(13, 5, 4, 0, ''),
(14, 5, 4, 0, ''),
(15, 1, 4, 0, ''),
(16, 5, 4, 0, ''),
(17, 5, 4, 0, ''),
(18, 5, 1, 0, ''),
(19, 1, 4, 0, ''),
(20, 1, 4, 0, ''),
(21, 1, 4, 0, ''),
(22, 1, 4, 0, ''),
(23, 1, 1, 0, ''),
(24, 2, 1, 0, ''),
(25, 1, 1, 0, ''),
(26, 1, 3, 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `client_engagements`
--

CREATE TABLE IF NOT EXISTS `client_engagements` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `engagement_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `date_added` varchar(255) NOT NULL,
  `last_update` varchar(255) NOT NULL,
  `update_by` int(11) NOT NULL,
  `added_by` int(11) NOT NULL,
  `isdeleted` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `client_engagements`
--

INSERT INTO `client_engagements` (`ID`, `engagement_id`, `client_id`, `date_added`, `last_update`, `update_by`, `added_by`, `isdeleted`) VALUES
(1, 2, 1, '', '', 0, 1, 1),
(2, 6, 2, '', '', 0, 1, 1),
(3, 2, 1, '', '', 0, 1, 0),
(4, 6, 1, '', '', 0, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `client_info`
--

CREATE TABLE IF NOT EXISTS `client_info` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `business_name` varchar(255) NOT NULL,
  `TIN` varchar(255) NOT NULL,
  `bir_office` int(11) NOT NULL,
  `address` varchar(512) NOT NULL,
  `tax_type` int(11) NOT NULL,
  `business_nature` int(11) NOT NULL,
  `contact_person` varchar(255) NOT NULL,
  `contact1` varchar(255) NOT NULL,
  `contact2` varchar(255) NOT NULL,
  `email_address` varchar(255) NOT NULL,
  `cassign` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  `date_added` varchar(255) NOT NULL,
  `added_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `date_updated` varchar(255) NOT NULL,
  `isdeleted` int(11) NOT NULL,
  `date_deleted` varchar(255) NOT NULL,
  `deleted_by` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `client_info`
--

INSERT INTO `client_info` (`ID`, `business_name`, `TIN`, `bir_office`, `address`, `tax_type`, `business_nature`, `contact_person`, `contact1`, `contact2`, `email_address`, `cassign`, `team_id`, `date_added`, `added_by`, `updated_by`, `date_updated`, `isdeleted`, `date_deleted`, `deleted_by`) VALUES
(1, 'RACS', '12345', 1, 'OBRERO CABANATUAN CITY', 1, 1, 'A', '09915224368', 'AAA', 'bulan_ruel@yahoo.com', 3, 2, '2023-08-18 05:32:24', 1, 0, '', 0, '', 0),
(2, 'CLIENT TEST1', '111', 1, 'M', 1, 1, 'M', 'M', 'M', 'm', 1, 1, '2023-08-18 05:34:53', 1, 0, '', 0, '', 0),
(3, 'CLIENT TEST2', '', 0, 'L', 2, 1, 'L', 'L', 'L', 'l', 5, 1, '2023-08-18 05:37:04', 1, 0, '', 0, '', 0),
(4, 'CLIENT TEST3', '', 0, 'B', 1, 1, 'B', 'B', 'B', 'b', 5, 1, '2023-08-18 05:40:39', 1, 0, '', 0, '', 0),
(5, 'CLIENT TEST4', '', 0, 'A', 1, 1, 'A', 'A', 'A', 'a', 1, 1, '2023-08-18 15:27:10', 1, 0, '', 0, '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `int_sales_purchase`
--

CREATE TABLE IF NOT EXISTS `int_sales_purchase` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `tin` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `gross_vat` double NOT NULL,
  `net_vat` double NOT NULL,
  `sales_type` int(11) NOT NULL,
  `purchase_type` int(11) NOT NULL,
  `percent` double NOT NULL,
  `date_added` varchar(255) NOT NULL,
  `last_update` varchar(255) NOT NULL,
  `update_by` int(11) NOT NULL,
  `added_by` int(11) NOT NULL,
  `transaction_date` varchar(255) NOT NULL,
  `isdeleted` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `int_sales_purchase`
--

INSERT INTO `int_sales_purchase` (`ID`, `client_id`, `customer_name`, `tin`, `address`, `gross_vat`, `net_vat`, `sales_type`, `purchase_type`, `percent`, `date_added`, `last_update`, `update_by`, `added_by`, `transaction_date`, `isdeleted`) VALUES
(1, 0, '1', '1', '1', 1, 1, 0, 0, 0, '', '', 0, 1, '1', 0),
(2, 2, '1', '1', '1', 1, 1, 0, 0, 0, '', '', 0, 1, '2022-08-05', 0);

-- --------------------------------------------------------

--
-- Table structure for table `lup_bir_office`
--

CREATE TABLE IF NOT EXISTS `lup_bir_office` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `office_name` varchar(255) NOT NULL,
  `date_added` varchar(255) NOT NULL,
  `added_by` int(11) NOT NULL,
  `isdeleted` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `lup_bir_office`
--

INSERT INTO `lup_bir_office` (`ID`, `office_name`, `date_added`, `added_by`, `isdeleted`) VALUES
(1, 'OFFICE11', '', 0, 0),
(2, 'Office2', '', 0, 0),
(4, 'Office3', '', 1, 1),
(5, 'Office4', '', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `lup_branch`
--

CREATE TABLE IF NOT EXISTS `lup_branch` (
  `branch_id` int(11) NOT NULL AUTO_INCREMENT,
  `branch_code` varchar(10) DEFAULT NULL,
  `branch_description` varchar(10) DEFAULT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `lup_business_nature`
--

CREATE TABLE IF NOT EXISTS `lup_business_nature` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `business_nature` varchar(255) NOT NULL,
  `date_added` varchar(255) NOT NULL,
  `added_by` int(11) NOT NULL,
  `isdeleted` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `lup_business_nature`
--

INSERT INTO `lup_business_nature` (`ID`, `business_nature`, `date_added`, `added_by`, `isdeleted`) VALUES
(1, 'SOLE PROPRIETOR', '', 0, 0),
(2, 'CORPORATION', '', 0, 0),
(3, 'PARTNERSHIP', '', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `lup_engagements`
--

CREATE TABLE IF NOT EXISTS `lup_engagements` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `engagement` varchar(255) NOT NULL,
  `percent` double NOT NULL,
  `date_added` varchar(255) NOT NULL,
  `last_update` varchar(255) NOT NULL,
  `update_by` int(11) NOT NULL,
  `added_by` int(11) NOT NULL,
  `isdeleted` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `lup_engagements`
--

INSERT INTO `lup_engagements` (`ID`, `engagement`, `percent`, `date_added`, `last_update`, `update_by`, `added_by`, `isdeleted`) VALUES
(1, '', 0, '', '', 0, 1, 1),
(2, 'TEST112', 0, '', '2023-08-29 10:45:16', 1, 1, 0),
(3, 'TEST2', 0, '', '', 0, 1, 1),
(4, 'TEST223', 0, '', '', 0, 1, 1),
(5, 'TEST223', 0, '', '2023-08-29 10:47:01', 1, 1, 1),
(6, 'TEST223', 0, '', '2023-08-29 10:49:14', 1, 1, 0),
(7, 'TEST4', 0, '', '', 0, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `lup_purchase_type`
--

CREATE TABLE IF NOT EXISTS `lup_purchase_type` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `purchase_type` varchar(255) NOT NULL,
  `percent` double NOT NULL,
  `date_added` varchar(255) NOT NULL,
  `last_update` varchar(255) NOT NULL,
  `update_by` int(11) NOT NULL,
  `added_by` int(11) NOT NULL,
  `isdeleted` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `lup_purchase_type`
--

INSERT INTO `lup_purchase_type` (`ID`, `purchase_type`, `percent`, `date_added`, `last_update`, `update_by`, `added_by`, `isdeleted`) VALUES
(1, '', 0, '', '', 0, 1, 1),
(2, 'TEST1', 12.2, '', '2023-08-29 10:40:18', 1, 1, 0),
(3, 'TEST2', 0, '', '', 0, 1, 1),
(4, '1', 12.5, '', '2023-08-29 10:40:26', 1, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `lup_sales_type`
--

CREATE TABLE IF NOT EXISTS `lup_sales_type` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `sales_type` varchar(255) NOT NULL,
  `percent` double NOT NULL,
  `date_added` varchar(255) NOT NULL,
  `last_update` varchar(255) NOT NULL,
  `update_by` int(11) NOT NULL,
  `added_by` int(11) NOT NULL,
  `isdeleted` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `lup_sales_type`
--

INSERT INTO `lup_sales_type` (`ID`, `sales_type`, `percent`, `date_added`, `last_update`, `update_by`, `added_by`, `isdeleted`) VALUES
(1, 'TEST1', 12, '', '2023-08-29 10:49:52', 1, 1, 0),
(2, 'test2', 0, '', '', 0, 1, 1),
(3, 'TEST2', 0, '', '2023-08-25 02:50:19', 1, 1, 1),
(4, 'TEST3', 0, '', '2023-08-25 02:52:30', 1, 1, 1),
(5, 'TEST22', 0, '', '2023-08-25 03:20:10', 1, 1, 1),
(6, 'TEST', 12, '', '2023-08-29 10:49:59', 1, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `lup_team`
--

CREATE TABLE IF NOT EXISTS `lup_team` (
  `team_id` int(5) NOT NULL AUTO_INCREMENT,
  `team_code` varchar(10) DEFAULT NULL,
  `team_name` varchar(150) DEFAULT NULL,
  `team_leader` int(11) NOT NULL,
  `created_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `added_by` int(11) NOT NULL,
  `last_update` int(11) NOT NULL,
  `date_updated` varchar(255) NOT NULL,
  `no_team` int(11) NOT NULL,
  `isdeleted` int(11) NOT NULL,
  PRIMARY KEY (`team_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `lup_team`
--

INSERT INTO `lup_team` (`team_id`, `team_code`, `team_name`, `team_leader`, `created_modified`, `added_by`, `last_update`, `date_updated`, `no_team`, `isdeleted`) VALUES
(1, '0', 'NO TEAM', 0, '2023-08-15 06:20:06', 0, 0, '', 1, 0),
(2, NULL, 'A', 1, '2023-08-17 12:28:18', 1, 1, '2023-08-17 09:14:22', 0, 0),
(3, NULL, 'B', 3, '2023-08-17 12:30:25', 1, 1, '2023-08-17 09:14:51', 0, 0),
(4, NULL, 'test3', 1, '2023-08-17 12:30:50', 1, 0, '', 0, 1),
(5, NULL, 'c', 1, '2023-08-17 13:17:26', 1, 0, '', 0, 1);

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
(1, 'CLIENT INFO', '<i class="fa fa-users"></i>', '', 0, 1),
(2, 'SYSTEM PANEL', '<i class="fa fa-desktop"></i>', '', 0, 1),
(3, 'INTERNAL RECORDS', '<i class="fa fa-book"></i>', '', 0, 1),
(4, 'JOB ORDERS', '<i class="fa fa-suitcase"></i>', '', 0, 1),
(5, 'BILLING AND COLLECTION', '<i class="fa fa-list-alt"></i>', '', 0, 1);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT AUTO_INCREMENT=41 ;

--
-- Dumping data for table `se_sub_module`
--

INSERT INTO `se_sub_module` (`sub_module_id`, `sub_module_name`, `sub_module_dir`, `module_id`, `location`, `keyword`, `active`) VALUES
(1, 'CLIENT PROFILE', '', 1, 'main', 'cprofileui', 1),
(2, 'BIR OFFICES', '', 2, 'main', 'birui', 1),
(3, 'CLIENT LIST', '', 1, 'main', 'clientlistui', 1),
(4, 'USER PROFILE', '', 2, 'main', 'userui', 1),
(5, 'TEAM INFO', '', 2, 'main', 'teamui', 1),
(6, 'PURCHASE TYPE', '', 2, 'main', 'purchasetypeui', 1),
(7, 'ENGAGEMENT INFO', '', 2, 'main', 'enui', 1),
(8, 'CARD PROFILE', '', 2, 'main', 'cprofileui', 0),
(9, 'CREDIT LIMIT', '', 2, 'main', 'climitui', 0),
(10, 'REGISTRATION STATUS', '', 2, 'main', 'regstatusui', 0),
(11, 'SETTLEMENT TYPE GROUP', '', 2, 'main', 'setgroupui', 0),
(12, 'SETTLEMENT TYPE', '', 2, 'main', 'settleui', 0),
(13, 'POS LOOK UP', '', 2, 'main', 'possetui', 0),
(14, 'POS SETTINGS', '', 2, 'main', 'possettingsui', 0),
(15, 'CLIENT APPLICATION', '', 1, 'main', 'applicationui', 1),
(16, 'COLLECTION', '', 3, 'pos', 'colui', 0),
(17, 'SALES MONITORING', '', 4, 'finance', 'salesui', 0),
(18, 'PAYMENT MONITORING', '', 4, 'finance', 'paymentui', 0),
(19, 'REBATES', '', 4, 'finance', 'rebateui', 0),
(20, 'EXPENSES', '', 4, 'finance', 'expenseui', 0),
(21, 'REPORTS', '', 4, 'finance', 'freportui', 0),
(22, 'CLIENT ASSIGNMENT', '', 3, 'main', 'clientass', 1),
(23, 'STOCK MANAGEMENT', '', 5, 'inventory', 'stockmui', 0),
(24, 'STOCK MONITORING', '', 5, 'inventory', 'stockmoui', 0),
(25, 'DELIVERY MONITORING', '', 5, 'inventory', 'dmonitorui', 0),
(27, 'COLLECTION MONITORING', '', 4, 'pos', 'collectionui', 0),
(28, 'SALES RECORDS', '', 3, 'main', 'vatui', 1),
(29, 'DELIVERY DETAILS', '', 5, 'inventory', 'dstockmui', 0),
(31, 'CURRENT STOCK INQUIRY', '', 5, 'inventory', 'cstockmui', 0),
(32, 'PURCHASE RECORDS', '', 3, 'main', 'sptui', 1),
(33, 'PAYMENTS', '', 3, 'pos', 'orderui', 0),
(34, 'TABLES', '', 2, 'main', 'tableui', 0),
(35, 'TAKE ORDERS', '', 3, 'pos', 'takeorderui', 0),
(36, 'ORDER STATUS', '', 3, 'pos', 'orderstatusui', 0),
(37, 'COLLECTION TO REMIT', '', 3, 'finance', 'remitui', 0),
(38, 'REMITTANCES', '', 4, 'finance', 'remittanceui', 0),
(39, 'SALES TYPE INFO', '', 2, 'main', 'salestypeui', 1),
(40, 'CLIENT ENGAGEMENT', '', 1, 'main', 'cenui', 1);

-- --------------------------------------------------------

--
-- Table structure for table `se_user`
--

CREATE TABLE IF NOT EXISTS `se_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `agent_number` varchar(50) NOT NULL,
  `user_username` varchar(100) DEFAULT NULL,
  `user_password` varchar(100) DEFAULT NULL,
  `fullname` varchar(50) NOT NULL,
  `team_id` int(100) DEFAULT '0',
  `branch_id` int(11) NOT NULL,
  `user_status` int(100) DEFAULT NULL,
  `user_reset` int(11) DEFAULT NULL,
  `date_added` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `isadmin` int(1) DEFAULT '0',
  `isdeleted` int(11) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT AUTO_INCREMENT=6 ;

--
-- Dumping data for table `se_user`
--

INSERT INTO `se_user` (`user_id`, `agent_number`, `user_username`, `user_password`, `fullname`, `team_id`, `branch_id`, `user_status`, `user_reset`, `date_added`, `isadmin`, `isdeleted`) VALUES
(1, '162903', 'admin', '89ce3b1d6c3ff3d7f064686b2f5b7592', 'System Administrator', 1, 0, 2, 0, '2023-08-17 11:59:19', 1, 0),
(3, 'test', 'test', '68358d5d9cbbf39fe571ba41f26524b6', 'test', 2, 0, NULL, 1, '2023-08-17 13:20:01', 0, 0),
(4, 'test2', 'test2', '49d09ed0832c23c82b4ae2749920d9a7', 'test2', 1, 0, NULL, 1, '2023-08-15 06:25:47', 0, 0),
(5, 'test3', 'test3', '71a1083be5c288da7e57b8c2bd7cbc96', 'test3', 1, 0, NULL, 1, '2023-08-15 06:25:49', 0, 0);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT AUTO_INCREMENT=31 ;

--
-- Dumping data for table `se_user_access_module`
--

INSERT INTO `se_user_access_module` (`user_access_module_id`, `user_id`, `module_id`, `sub_module_id`, `access_level`, `date_added`, `isdeleted`) VALUES
(1, 1, 'all', 'all', 1, '2020-06-30 18:38:40', 1),
(3, 1, 'all', 'all', 1, '2023-02-13 00:05:19', 0),
(4, 5, '3', '35', 1, '2023-04-16 04:50:55', 0),
(5, 7, '3', '35', 1, '2023-04-16 03:18:01', 0),
(6, 9, '3', '35', 1, '2023-04-16 03:33:58', 0),
(7, 23, '3', '15', 2, '2023-04-25 08:38:30', 0),
(8, 23, '3', '32', 2, '2023-04-25 08:38:36', 0),
(9, 23, '3', '33', 2, '2023-04-25 08:38:39', 0),
(10, 23, '3', '35', 2, '2023-04-25 08:38:43', 0),
(11, 23, '3', '36', 2, '2023-04-25 08:38:46', 0),
(12, 24, '3', '35', 2, '2023-04-25 08:39:37', 0),
(13, 26, '3', '35', 2, '2023-04-25 08:41:22', 0),
(14, 24, '3', '33', 2, '2023-04-30 09:58:11', 0),
(15, 27, 'all', 'all', 1, '2023-04-30 09:59:00', 0),
(16, 24, '3', '15', 2, '2023-05-15 11:43:41', 0),
(17, 24, '3', '36', 2, '2023-05-15 11:43:47', 0),
(18, 44, 'all', 'all', 1, '2023-05-15 12:59:56', 0),
(19, 45, '3', '15', 2, '2023-05-15 13:00:06', 0),
(20, 45, '3', '35', 2, '2023-05-15 13:00:15', 0),
(21, 45, '3', '33', 2, '2023-05-15 13:00:19', 0),
(22, 45, '3', '36', 2, '2023-05-15 13:00:23', 0),
(23, 26, '3', '36', 2, '2023-05-16 07:40:48', 0),
(24, 26, '3', '15', 2, '2023-05-16 07:41:01', 0),
(25, 26, '3', '33', 2, '2023-05-16 07:41:06', 0),
(26, 46, '3', '15', 2, '2023-05-17 09:35:39', 0),
(27, 46, '3', '33', 2, '2023-05-17 09:35:45', 0),
(28, 46, '3', '35', 2, '2023-05-17 09:35:50', 0),
(29, 46, '3', '36', 2, '2023-05-17 09:35:53', 0),
(30, 3, '1', '1', 1, '2023-08-15 05:31:32', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
