-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 15, 2023 at 01:15 AM
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
-- Database: `payong`
--

-- --------------------------------------------------------

--
-- Table structure for table `agri_prognosis`
--

CREATE TABLE `agri_prognosis` (
  `prognosis_id` int(11) NOT NULL,
  `region_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` varchar(2048) NOT NULL,
  `rainf_min` double NOT NULL,
  `rainf_max` double NOT NULL,
  `raind_min` double NOT NULL,
  `raind_max` double NOT NULL,
  `temp_min` double NOT NULL,
  `temp_max` double NOT NULL,
  `soil_condition` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `agri_info_id` int(11) NOT NULL,
  `date_added` varchar(255) NOT NULL,
  `added_by` int(11) NOT NULL,
  `isdeleted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `agri_prognosis`
--

INSERT INTO `agri_prognosis` (`prognosis_id`, `region_id`, `title`, `content`, `rainf_min`, `rainf_max`, `raind_min`, `raind_max`, `temp_min`, `temp_max`, `soil_condition`, `status`, `agri_info_id`, `date_added`, `added_by`, `isdeleted`) VALUES
(1, 1, 'Title', 'Content', 0, 5, 0, 3, 25, 35, 'DRY', 1, 1, '', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `agri_prognosis_location`
--

CREATE TABLE `agri_prognosis_location` (
  `prog_location_id` int(11) NOT NULL,
  `province_id` int(11) NOT NULL,
  `prognosis_id` int(11) NOT NULL,
  `date_added` varchar(255) NOT NULL,
  `added_by` int(11) NOT NULL,
  `isdeleted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `agri_prognosis_location`
--

INSERT INTO `agri_prognosis_location` (`prog_location_id`, `province_id`, `prognosis_id`, `date_added`, `added_by`, `isdeleted`) VALUES
(1, 1, 1, '', 0, 0),
(2, 2, 1, '', 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `agri_prognosis`
--
ALTER TABLE `agri_prognosis`
  ADD PRIMARY KEY (`prognosis_id`);

--
-- Indexes for table `agri_prognosis_location`
--
ALTER TABLE `agri_prognosis_location`
  ADD PRIMARY KEY (`prog_location_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `agri_prognosis`
--
ALTER TABLE `agri_prognosis`
  MODIFY `prognosis_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `agri_prognosis_location`
--
ALTER TABLE `agri_prognosis_location`
  MODIFY `prog_location_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
