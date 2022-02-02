-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 02, 2022 at 09:48 AM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `itpe2db`
--

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE IF NOT EXISTS `menu` (
  `module_id` int(11) NOT NULL AUTO_INCREMENT,
  `module_name` varchar(100) NOT NULL,
  `readonly` int(11) NOT NULL,
  `icon` varchar(50) NOT NULL,
  `keyword` varchar(50) NOT NULL,
  `location` varchar(255) NOT NULL,
  `active` int(10) NOT NULL,
  PRIMARY KEY (`module_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT AUTO_INCREMENT=4 ;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`module_id`, `module_name`, `readonly`, `icon`, `keyword`, `location`, `active`) VALUES
(1, 'USER MANAGER', 1, '<i class="fa fa-user"></i>', 'userui', 'main.php', 1),
(2, 'MENU MANAGEMENT', 1, '<i class="fa fa-bars"></i>', 'smenuui', 'main.php', 1);

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
  `user_reset` int(11) DEFAULT NULL,
  `date_added` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `isadmin` int(1) DEFAULT '0',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT AUTO_INCREMENT=3 ;

--
-- Dumping data for table `se_user`
--

INSERT INTO `se_user` (`user_id`, `agent_number`, `user_username`, `user_password`, `fullname`, `user_reset`, `date_added`, `isadmin`) VALUES
(1, 'admin', 'admin', '0c7540eb7e65b553ec1ba6b20de79608', 'admin', 0, '2022-02-02 08:39:29', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
