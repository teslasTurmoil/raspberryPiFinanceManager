-- --------------------------------------------------------
-- Host:                         192.168.0.60
-- Server version:               5.5.44-0+deb7u1 - (Debian)
-- Server OS:                    debian-linux-gnu
-- HeidiSQL Version:             9.1.0.4867
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping database structure for finances
CREATE DATABASE IF NOT EXISTS `finances` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `finances`;


-- Dumping structure for table finances.accounts
CREATE TABLE IF NOT EXISTS `accounts` (
  `account_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `balance` decimal(10,2) NOT NULL,
  `APR` decimal(10,4) DEFAULT NULL,
  PRIMARY KEY (`account_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='This can be used to track account balances';

-- Data exporting was unselected.


-- Dumping structure for table finances.budget_items
CREATE TABLE IF NOT EXISTS `budget_items` (
  `budget_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `amount` decimal(9,2) NOT NULL,
  `account` varchar(100) NOT NULL,
  PRIMARY KEY (`budget_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='These are items that will have individual purchases taken from them. The total is for the month. ';

-- Data exporting was unselected.


-- Dumping structure for table finances.funds
CREATE TABLE IF NOT EXISTS `funds` (
  `fund_ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `balance` decimal(10,2) NOT NULL,
  `assocAccountID` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`fund_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='this table is used to track different funds that are used to save for taxes, holiday spending, etc. ';

-- Data exporting was unselected.


-- Dumping structure for table finances.income
CREATE TABLE IF NOT EXISTS `income` (
  `income_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `personName` varchar(100) NOT NULL,
  `amount` decimal(9,2) NOT NULL,
  `incomeDate` date NOT NULL,
  `account` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`income_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='This is used to track various types of income(deposits).';

-- Data exporting was unselected.


-- Dumping structure for table finances.monthlyBills
CREATE TABLE IF NOT EXISTS `monthlyBills` (
  `bill_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `amount` decimal(9,2) NOT NULL,
  `billDate` date NOT NULL,
  `account` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`bill_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table finances.purchases
CREATE TABLE IF NOT EXISTS `purchases` (
  `purchaseID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `location` varchar(100) NOT NULL,
  `personName` varchar(100) NOT NULL,
  `amount` decimal(9,2) NOT NULL,
  `purchaseDate` date NOT NULL,
  `account` varchar(100) NOT NULL,
  `budget` varchar(100) NOT NULL,
  PRIMARY KEY (`purchaseID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table finances.repeatingEntries
CREATE TABLE IF NOT EXISTS `repeatingEntries` (
  `entry_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `amount` decimal(9,2) NOT NULL,
  `entryType` varchar(100) NOT NULL,
  `timeOfMonth` varchar(100) NOT NULL,
  PRIMARY KEY (`entry_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='These are entries that occur monthly. Can be used with a tool to quickly add them to the income, purchases, and monthly bills tables. ';

-- Data exporting was unselected.


-- Dumping structure for table finances.transfers
CREATE TABLE IF NOT EXISTS `transfers` (
  `transfer_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `from` varchar(100) NOT NULL,
  `to` varchar(50) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`transfer_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='used to track transfers';

-- Data exporting was unselected.
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
