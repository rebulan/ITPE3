-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 08, 2023 at 04:36 PM
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
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `announcement_id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `date_added` datetime NOT NULL,
  `added_by` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `isdeleted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`announcement_id`, `title`, `description`, `status`, `date_added`, `added_by`, `level`, `location_id`, `isdeleted`) VALUES
(1, '1', '1', 1, '2023-02-02 08:37:10', 1, 0, 1, 0),
(2, '3', '1', 1, '0000-00-00 00:00:00', 0, 0, 1, 0),
(3, '1', '2323', 1, '0000-00-00 00:00:00', 1, 0, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `forecast_agri`
--

CREATE TABLE `forecast_agri` (
  `forecast_agri_id` int(11) NOT NULL,
  `region_id` int(11) NOT NULL,
  `forecast_agri_date` datetime NOT NULL,
  `wheather_id` int(11) NOT NULL,
  `wind` double NOT NULL,
  `humidity_from` double NOT NULL,
  `humidity_to` double NOT NULL,
  `templow` double NOT NULL,
  `temp_low_id` int(11) NOT NULL,
  `temp_high` double NOT NULL,
  `temp_high_id` int(11) NOT NULL,
  `rainfall_from` double NOT NULL,
  `rainfall_to` int(11) NOT NULL,
  `date_added` datetime NOT NULL,
  `added_by` int(11) NOT NULL,
  `isdeleted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `forecast_daily`
--

CREATE TABLE `forecast_daily` (
  `daily_id` int(11) NOT NULL,
  `forecast_date` datetime NOT NULL,
  `tif_file` varchar(1024) NOT NULL,
  `added_by` int(11) NOT NULL,
  `date_added` datetime NOT NULL,
  `isdeleted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `forecast_daily`
--

INSERT INTO `forecast_daily` (`daily_id`, `forecast_date`, `tif_file`, `added_by`, `date_added`, `isdeleted`) VALUES
(1, '2023-02-08 00:00:00', '0xabcd', 1, '2023-02-08 00:00:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `forecast_daily_details`
--

CREATE TABLE `forecast_daily_details` (
  `daily_details_id` int(11) NOT NULL,
  `daily_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `daily_forecast_rainfall` double NOT NULL,
  `daily_forecast_rainfall_id` int(11) NOT NULL,
  `daily_forecast_rainfall_percentage` double NOT NULL,
  `daily_forecast_rain_percent_id` int(11) NOT NULL,
  `daily_forecast_low_temp` double NOT NULL,
  `daily_forecast_lowtemp_hex` varchar(255) NOT NULL,
  `daily_forecast_high_temp` double NOT NULL,
  `daily_forecast_hightemp_hex` varchar(255) NOT NULL,
  `date_added` varchar(255) NOT NULL,
  `added_by` int(11) NOT NULL,
  `isdeleted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `forecast_daily_details`
--

INSERT INTO `forecast_daily_details` (`daily_details_id`, `daily_id`, `location_id`, `daily_forecast_rainfall`, `daily_forecast_rainfall_id`, `daily_forecast_rainfall_percentage`, `daily_forecast_rain_percent_id`, `daily_forecast_low_temp`, `daily_forecast_lowtemp_hex`, `daily_forecast_high_temp`, `daily_forecast_hightemp_hex`, `date_added`, `added_by`, `isdeleted`) VALUES
(1, 1, 1, 20, 1, 20, 1, 30, '1', 35, '1', '06-02-2023', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `lup_humidity_legends`
--

CREATE TABLE `lup_humidity_legends` (
  `humidity_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `range_from` double NOT NULL,
  `range_to` double NOT NULL,
  `date_added` date NOT NULL,
  `added_by` int(11) NOT NULL,
  `isdeleted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `lup_locations`
--

CREATE TABLE `lup_locations` (
  `location_id` int(11) NOT NULL,
  `location_description` varchar(255) NOT NULL,
  `coordinates` varchar(255) NOT NULL,
  `region_id` int(11) NOT NULL,
  `added_by` int(11) NOT NULL,
  `date_added` datetime NOT NULL,
  `isdeleted` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `lup_locations`
--

INSERT INTO `lup_locations` (`location_id`, `location_description`, `coordinates`, `region_id`, `added_by`, `date_added`, `isdeleted`) VALUES
(1, 'CABANATUAN CITY, NUEVA ECIJA', '0,0', 0, 1, '2023-02-01 06:34:01', 0);

-- --------------------------------------------------------

--
-- Table structure for table `lup_rainfall_legends`
--

CREATE TABLE `lup_rainfall_legends` (
  `rainfall_legend_id` int(11) NOT NULL,
  `rainfall_from` double NOT NULL,
  `rainfall_to` double NOT NULL,
  `color` varchar(255) NOT NULL,
  `date_added` varchar(255) NOT NULL,
  `added_by` int(11) NOT NULL,
  `isdeleted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `lup_rainfall_legends`
--

INSERT INTO `lup_rainfall_legends` (`rainfall_legend_id`, `rainfall_from`, `rainfall_to`, `color`, `date_added`, `added_by`, `isdeleted`) VALUES
(1, 0, 50, '#006633', '', 0, 0),
(2, 51, 100, '#ff0011\r\n', '', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `lup_rainpercentage_legends`
--

CREATE TABLE `lup_rainpercentage_legends` (
  `rain_percentage_legend_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `rain_percent_from` double NOT NULL,
  `rain_percent_to` double NOT NULL,
  `color` varchar(255) NOT NULL,
  `date_added` varchar(255) NOT NULL,
  `added_by` int(11) NOT NULL,
  `isdeleted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `lup_rainpercentage_legends`
--

INSERT INTO `lup_rainpercentage_legends` (`rain_percentage_legend_id`, `description`, `rain_percent_from`, `rain_percent_to`, `color`, `date_added`, `added_by`, `isdeleted`) VALUES
(1, 'NORMAL', 0, 40, '#00FF22', '\r\n', 0, 0),
(2, 'BELOW NORMAL', 41, 80, '#342563', '', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `lup_region`
--

CREATE TABLE `lup_region` (
  `region_id` int(11) NOT NULL,
  `region_description` varchar(255) NOT NULL,
  `date_added` varchar(255) NOT NULL,
  `added_by` int(11) NOT NULL,
  `isdeleted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `lup_soil_wetness`
--

CREATE TABLE `lup_soil_wetness` (
  `soil_wetness_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `date_added` varchar(255) NOT NULL,
  `added_by` int(11) NOT NULL,
  `isdeleted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `lup_status`
--

CREATE TABLE `lup_status` (
  `status_id` int(11) NOT NULL,
  `status` varchar(255) NOT NULL,
  `isdeleted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `lup_status`
--

INSERT INTO `lup_status` (`status_id`, `status`, `isdeleted`) VALUES
(1, 'PUBLISHED', 0),
(2, 'UNPUBLISHED', 0);

-- --------------------------------------------------------

--
-- Table structure for table `lup_temperature_legends`
--

CREATE TABLE `lup_temperature_legends` (
  `temp_legend_id` int(11) NOT NULL,
  `temp_from` double NOT NULL,
  `temp_to` double NOT NULL,
  `color` varchar(255) NOT NULL,
  `date_added` varchar(255) NOT NULL,
  `added_by` int(11) NOT NULL,
  `isdeleted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `lup_temperature_legends`
--

INSERT INTO `lup_temperature_legends` (`temp_legend_id`, `temp_from`, `temp_to`, `color`, `date_added`, `added_by`, `isdeleted`) VALUES
(1, 0, 50, '#006600', '', 0, 0),
(2, 51, 80, '#000', '', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `lup_temperature_locations`
--

CREATE TABLE `lup_temperature_locations` (
  `temp_location_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `date_added` date NOT NULL,
  `added_by` int(11) NOT NULL,
  `isdeleted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `lup_weather_system`
--

CREATE TABLE `lup_weather_system` (
  `weather_system_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `date_added` varchar(255) NOT NULL,
  `added_by` int(11) NOT NULL,
  `isdeleted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `lup_wind_legends`
--

CREATE TABLE `lup_wind_legends` (
  `wind_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `wind_from` double NOT NULL,
  `wind_to` double NOT NULL,
  `color` varchar(255) NOT NULL,
  `date_added` datetime NOT NULL,
  `added_by` int(11) NOT NULL,
  `isdeleted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
(1, 'SYSTEM PANEL', '<i class=\"fa fa-desktop\"></i>', '', 0, 1),
(2, 'FORECAST', '<i class=\"fa fa-chart-simple\"></i>', '', 0, 1),
(3, 'AGRI WEATHER', '<i class=\"fa-solid fa-tractor\"></i>', '', 0, 1),
(4, 'WEATHER MONITORING', '<i class=\"fa fa-bolt\"></i>', '', 0, 1);

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
(1, 'USER PROFILES', '', 1, 'main', 'userui', 1),
(2, 'ADVISORIES', '', 1, 'main', 'announcementui', 1),
(3, 'LOCATIONS', '', 1, 'main', 'locationui', 1),
(4, 'TEMPERATURE LEGENDS', '', 1, 'main', 'templegendui', 1),
(5, 'RAINFALL LEGENDS', '', 1, 'main', 'rainfalllegendui', 1),
(6, 'RAINFALL % LEGENDS', '', 1, 'main', 'rainfallperlegendui', 1),
(7, 'WEATHER CONDITIONS', '', 1, 'main', 'weatherui', 1);

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
(1, '162903', 'admin', '89ce3b1d6c3ff3d7f064686b2f5b7592', 4, 'System Administrator', 1, 3, 1, 2, 0, '2023-01-30 21:40:22', 1);

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
(1, 1, 'all', 'all', 1, '2023-01-30 22:17:46', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`announcement_id`);

--
-- Indexes for table `forecast_agri`
--
ALTER TABLE `forecast_agri`
  ADD PRIMARY KEY (`forecast_agri_id`);

--
-- Indexes for table `forecast_daily`
--
ALTER TABLE `forecast_daily`
  ADD PRIMARY KEY (`daily_id`);

--
-- Indexes for table `forecast_daily_details`
--
ALTER TABLE `forecast_daily_details`
  ADD PRIMARY KEY (`daily_details_id`);

--
-- Indexes for table `lup_humidity_legends`
--
ALTER TABLE `lup_humidity_legends`
  ADD PRIMARY KEY (`humidity_id`);

--
-- Indexes for table `lup_locations`
--
ALTER TABLE `lup_locations`
  ADD PRIMARY KEY (`location_id`);

--
-- Indexes for table `lup_rainfall_legends`
--
ALTER TABLE `lup_rainfall_legends`
  ADD PRIMARY KEY (`rainfall_legend_id`);

--
-- Indexes for table `lup_rainpercentage_legends`
--
ALTER TABLE `lup_rainpercentage_legends`
  ADD PRIMARY KEY (`rain_percentage_legend_id`);

--
-- Indexes for table `lup_region`
--
ALTER TABLE `lup_region`
  ADD PRIMARY KEY (`region_id`);

--
-- Indexes for table `lup_soil_wetness`
--
ALTER TABLE `lup_soil_wetness`
  ADD PRIMARY KEY (`soil_wetness_id`);

--
-- Indexes for table `lup_status`
--
ALTER TABLE `lup_status`
  ADD PRIMARY KEY (`status_id`);

--
-- Indexes for table `lup_temperature_legends`
--
ALTER TABLE `lup_temperature_legends`
  ADD PRIMARY KEY (`temp_legend_id`);

--
-- Indexes for table `lup_temperature_locations`
--
ALTER TABLE `lup_temperature_locations`
  ADD PRIMARY KEY (`temp_location_id`);

--
-- Indexes for table `lup_weather_system`
--
ALTER TABLE `lup_weather_system`
  ADD PRIMARY KEY (`weather_system_id`);

--
-- Indexes for table `lup_wind_legends`
--
ALTER TABLE `lup_wind_legends`
  ADD PRIMARY KEY (`wind_id`);

--
-- Indexes for table `se_module`
--
ALTER TABLE `se_module`
  ADD PRIMARY KEY (`module_id`);

--
-- Indexes for table `se_sub_module`
--
ALTER TABLE `se_sub_module`
  ADD PRIMARY KEY (`sub_module_id`);

--
-- Indexes for table `se_user`
--
ALTER TABLE `se_user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `se_user_access_module`
--
ALTER TABLE `se_user_access_module`
  ADD PRIMARY KEY (`user_access_module_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `announcement_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `forecast_agri`
--
ALTER TABLE `forecast_agri`
  MODIFY `forecast_agri_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `forecast_daily`
--
ALTER TABLE `forecast_daily`
  MODIFY `daily_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `forecast_daily_details`
--
ALTER TABLE `forecast_daily_details`
  MODIFY `daily_details_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `lup_humidity_legends`
--
ALTER TABLE `lup_humidity_legends`
  MODIFY `humidity_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lup_locations`
--
ALTER TABLE `lup_locations`
  MODIFY `location_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `lup_rainfall_legends`
--
ALTER TABLE `lup_rainfall_legends`
  MODIFY `rainfall_legend_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `lup_rainpercentage_legends`
--
ALTER TABLE `lup_rainpercentage_legends`
  MODIFY `rain_percentage_legend_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `lup_region`
--
ALTER TABLE `lup_region`
  MODIFY `region_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lup_soil_wetness`
--
ALTER TABLE `lup_soil_wetness`
  MODIFY `soil_wetness_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lup_status`
--
ALTER TABLE `lup_status`
  MODIFY `status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `lup_temperature_legends`
--
ALTER TABLE `lup_temperature_legends`
  MODIFY `temp_legend_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `lup_temperature_locations`
--
ALTER TABLE `lup_temperature_locations`
  MODIFY `temp_location_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lup_weather_system`
--
ALTER TABLE `lup_weather_system`
  MODIFY `weather_system_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lup_wind_legends`
--
ALTER TABLE `lup_wind_legends`
  MODIFY `wind_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `se_module`
--
ALTER TABLE `se_module`
  MODIFY `module_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `se_sub_module`
--
ALTER TABLE `se_sub_module`
  MODIFY `sub_module_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `se_user`
--
ALTER TABLE `se_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `se_user_access_module`
--
ALTER TABLE `se_user_access_module`
  MODIFY `user_access_module_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
