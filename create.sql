-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server versie:                5.6.13 - MySQL Community Server (GPL)
-- Server OS:                    Win32
-- HeidiSQL Versie:              8.3.0.4694
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Databasestructuur van test wordt geschreven
CREATE DATABASE IF NOT EXISTS `credits` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `credits`;


-- Structuur van  tabel test.credit_transaction wordt geschreven
CREATE TABLE IF NOT EXISTS `credit_transaction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wallet_id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `amount` double NOT NULL DEFAULT '5',
  `status_id` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `wallet` (`wallet_id`),
  KEY `FK_credit_transaction_status` (`status_id`),
  CONSTRAINT `FK_credit_transaction_status` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `wallet` FOREIGN KEY (`wallet_id`) REFERENCES `wallet` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporteren was gedeselecteerd


-- Structuur van  tabel test.status wordt geschreven
CREATE TABLE IF NOT EXISTS `status` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `status` char(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporteren was gedeselecteerd


-- Structuur van  tabel test.wallet wordt geschreven
CREATE TABLE IF NOT EXISTS `wallet` (
  `id` int(11) NOT NULL,
  `total` double NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporteren was gedeselecteerd
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;