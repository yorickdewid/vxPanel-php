-- phpMyAdmin SQL Dump
-- version 4.2.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 28, 2015 at 01:30 PM
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
  `country` varchar(50) NOT NULL,
  `user_id` smallint(6) NOT NULL,
`profile_detail_id` mediumint(9) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `x_profiles_detail`
--

INSERT INTO `x_profiles_detail` (`firstname`, `lastname`, `street`, `number`, `city`, `country`, `user_id`, `profile_detail_id`) VALUES
('ariekaass', 'kaas', 'kaasstraat', 33, 'blauwkaas', 'GB', 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `x_profiles_detail`
--
ALTER TABLE `x_profiles_detail`
 ADD PRIMARY KEY (`profile_detail_id`), ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `x_profiles_detail`
--
ALTER TABLE `x_profiles_detail`
MODIFY `profile_detail_id` mediumint(9) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
