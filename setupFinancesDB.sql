-- --------------------------------------------------------
-- Host:                         192.168.0.60
-- Server version:               5.5.47-0+deb7u1 - (Debian)
-- Server OS:                    debian-linux-gnu
-- HeidiSQL Version:             9.3.0.4984
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping database structure for financesNew
CREATE DATABASE IF NOT EXISTS `financesNew` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `financesNew`;


-- Dumping structure for table financesNew.accounts
CREATE TABLE IF NOT EXISTS `accounts` (
  `account_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `balance` decimal(10,2) NOT NULL,
  `APR` decimal(10,4) DEFAULT NULL,
  PRIMARY KEY (`account_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='This can be used to track account balances';

-- Data exporting was unselected.


-- Dumping structure for table financesNew.budgetTimeframe
CREATE TABLE IF NOT EXISTS `budgetTimeframe` (
  `tfID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `StartDate` date NOT NULL,
  `EndDate` date NOT NULL,
  `Description` varchar(50) NOT NULL,
  PRIMARY KEY (`tfID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='used to set when the budgets will be tracked. Allows for easily seeing the history. ';

-- Data exporting was unselected.


-- Dumping structure for table financesNew.budget_items
CREATE TABLE IF NOT EXISTS `budget_items` (
  `budget_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `amount` decimal(9,2) NOT NULL,
  `account` varchar(100) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `assocAccountID` int(10) unsigned NOT NULL,
  PRIMARY KEY (`budget_id`),
  KEY `FK_budget_items_accounts` (`assocAccountID`),
  CONSTRAINT `FK_budget_items_accounts` FOREIGN KEY (`assocAccountID`) REFERENCES `accounts` (`account_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='These are items that will have individual purchases taken from them. The total is for the month.  Examples include Groceries, Electric, Misc. ';

-- Data exporting was unselected.


-- Dumping structure for table financesNew.funds
CREATE TABLE IF NOT EXISTS `funds` (
  `fund_ID` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `balance` decimal(10,2) NOT NULL,
  `assocAccountID` int(10) unsigned NOT NULL,
  PRIMARY KEY (`fund_ID`),
  KEY `FK_funds_accounts` (`assocAccountID`),
  CONSTRAINT `FK_funds_accounts` FOREIGN KEY (`assocAccountID`) REFERENCES `accounts` (`account_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='this table is used to track different funds that are used to save for taxes, holiday spending, etc. ';

-- Data exporting was unselected.


-- Dumping structure for table financesNew.income
CREATE TABLE IF NOT EXISTS `income` (
  `income_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `amount` decimal(9,2) NOT NULL,
  `account` varchar(100) DEFAULT NULL,
  `recID` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`income_ID`),
  KEY `FK_income_receipts` (`recID`),
  CONSTRAINT `FK_income_receipts` FOREIGN KEY (`recID`) REFERENCES `receipts` (`recID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='This is used to track various types of income(deposits).';

-- Data exporting was unselected.


-- Dumping structure for table financesNew.purchases
CREATE TABLE IF NOT EXISTS `purchases` (
  `purchaseID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `amount` decimal(9,2) NOT NULL,
  `account` varchar(100) NOT NULL,
  `recID` int(10) unsigned NOT NULL,
  PRIMARY KEY (`purchaseID`),
  KEY `FK_purchases_receipts` (`recID`),
  CONSTRAINT `FK_purchases_receipts` FOREIGN KEY (`recID`) REFERENCES `receipts` (`recID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='This is to configure the receipts to allow splitting them into multiple budgets. Example, walmart receipt has groceries and misc purchases. Can also just be tying a receipt to a budget. ';

-- Data exporting was unselected.


-- Dumping structure for table financesNew.receipts
CREATE TABLE IF NOT EXISTS `receipts` (
  `recID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `location` varchar(50) NOT NULL,
  `memo` varchar(100) DEFAULT NULL,
  `amount` decimal(9,2) unsigned NOT NULL,
  `date` date NOT NULL,
  `personName` varchar(50) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `fitID` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`recID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='used to match line items in a bank statement\r\nfor types: 0 = Withdrawal, 1 = deposit';

-- Data exporting was unselected.


-- Dumping structure for table financesNew.repeatingEntries
CREATE TABLE IF NOT EXISTS `repeatingEntries` (
  `entry_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `amount` decimal(9,2) NOT NULL,
  `entryType` varchar(100) NOT NULL,
  `timeOfMonth` varchar(100) NOT NULL,
  PRIMARY KEY (`entry_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='These are entries that occur monthly. Can be used with a tool to quickly add them to the income, purchases, and monthly bills. ';

-- Data exporting was unselected.


-- Dumping structure for table financesNew.transfers
CREATE TABLE IF NOT EXISTS `transfers` (
  `transfer_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `from` varchar(100) NOT NULL,
  `to` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  PRIMARY KEY (`transfer_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='used to track transfers between accounts and/or funds. ';

-- Data exporting was unselected.
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
