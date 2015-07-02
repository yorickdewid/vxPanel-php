-- phpMyAdmin SQL Dump
-- version 4.2.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 30, 2015 at 08:50 PM
-- Server version: 5.6.24-72.2
-- PHP Version: 5.6.4-4ubuntu6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `zpanel_core`
--

-- --------------------------------------------------------

--
-- Table structure for table `x_profiles_detail`
--

CREATE TABLE IF NOT EXISTS `x_profiles_detail` (
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `street` varchar(100) NOT NULL,
  `number` int(6) NOT NULL,
  `city` varchar(50) NOT NULL,
  `postcode` varchar(10) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `country` varchar(50) NOT NULL,
  `company_name` varchar(100) DEFAULT NULL,
  `company_kvk` varchar(20) DEFAULT NULL,
  `company_type` varchar(30) DEFAULT NULL,
  `faxnumber` varchar(32) DEFAULT NULL,
  `user_id` int(6) unsigned NOT NULL,
`profile_detail_id` mediumint(9) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `x_profiles_detail`
--

INSERT INTO `x_profiles_detail` (`firstname`, `lastname`, `street`, `number`, `city`, `postcode`, `phone`, `email`, `country`, `company_name`, `company_kvk`, `company_type`, `faxnumber`, `user_id`, `profile_detail_id`) VALUES
('ariekaass', 'kaas', 'kaasstraat', 33, 'blauwkaas', '', '', '', 'GB', NULL, NULL, NULL, NULL, 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `x_profiles_detail`
--
ALTER TABLE `x_profiles_detail`
 ADD PRIMARY KEY (`profile_detail_id`), ADD UNIQUE KEY `company_kvk` (`company_kvk`), ADD KEY `user_id` (`user_id`), ADD KEY `user_id_2` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `x_profiles_detail`
--
ALTER TABLE `x_profiles_detail`
MODIFY `profile_detail_id` mediumint(9) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `x_profiles_detail`
--
ALTER TABLE `x_profiles_detail`
ADD CONSTRAINT `x_profiles_detail_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `x_accounts` (`ac_id_pk`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
