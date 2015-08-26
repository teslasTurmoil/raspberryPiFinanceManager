/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  ferrick
 * Created: Aug 26, 2015
 */

USE finances;
DROP TABLE IF EXISTS `budget_items`;
CREATE TABLE IF NOT EXISTS `budget_items` (
  `budget_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `amount` decimal(9,2) NOT NULL,
  `account` varchar(100) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`budget_id`)
  );
INSERT INTO budget_items (category, name, account, amount) VALUES
	('Income', 'Salary', 'Checking', '0.00'),
	('Income', 'Bonus', 'Checking', '0.00'),
	('Income', 'Interest/Dividend', 'Checking', '0.00'),
	('Income', 'Other Income', 'Checking', '0.00'),
	('Housing', 'Rent/Mortgage', 'Checking', '0.00'),
	('Housing', 'Maintenance', 'Checking', '0.00'),
	('Housing', 'Improvements', 'Checking', '0.00'),
	('Housing', 'Furnishings', 'Checking', '0.00'),
	('Housing', 'Lawn/Garden', 'Checking', '0.00'),
	('Medical', 'Dental', 'Checking', '0.00'),
	('Medical', 'Doctor', 'Checking', '0.00'),
	('Medical', 'Prescriptions', 'Checking', '0.00'),
	('Medical', 'Health Insurance', 'Checking', '0.00'),
	('Car/Auto', 'Fuel', 'Checking', '0.00'),
	('Car/Auto', 'Repairs', 'Checking', '0.00'),
	('Car/Auto', 'Auto Insurance', 'Checking', '0.00'),
	('Car/Auto', 'Maintenance', 'Checking', '0.00'),
	('Car/Auto', 'Registration/Taxes', 'Checking', '0.00'),
	('Car/Auto', 'Payment', 'Checking', '0.00'),
	('Personal', 'Ashley Madison Web Fees', 'Checking', '0.00'),
	('Personal', 'Life Insurance', 'Checking', '0.00'),
	('Personal', 'Education/School', 'Checking', '0.00'),
	('Personal', 'Fitness/Gym', 'Checking', '0.00'),
	('Personal', 'Appearance/Grooming', 'Checking', '0.00'),
	('Personal', 'Clothing', 'Checking', '0.00'),
	('Personal', 'Miscellaneous/Other', 'Checking', '0.00'),
	('Recreation', 'Vacation', 'Checking', '0.00'),
	('Recreation', 'Entertainment', 'Checking', '0.00'),
	('Recreation', 'Hobbies', 'Checking', '0.00'),
	('Utilities', 'Electricity', 'Checking', '0.00'),
	('Utilities', 'Water/Sewer', 'Checking', '0.00'),
	('Utilities', 'Natural Gas/Propane', 'Checking', '0.00'),
	('Utilities', 'Phone', 'Checking', '0.00'),
	('Utilities', 'Cable/Satellite', 'Checking', '0.00'),
	('Utilities', 'Internet', 'Checking', '0.00'),
	('Utilities', 'Trash/Recycling', 'Checking', '0.00'),
	('Charity', 'Church', 'Checking', '0.00'),
	('Charity', 'Goodwill', 'Checking', '0.00'),
	('Charity', 'Miscellaneous/Other', 'Checking', '0.00'),
	('Miscellaneous/Other', 'Miscellaneous', 'Checking', '0.00'),
	('Miscellaneous/Other', 'Other', 'Checking', '0.00');
	
