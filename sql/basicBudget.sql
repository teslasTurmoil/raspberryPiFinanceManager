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
INSERT INTO budget_items (name, account, amount) VALUES
	('Income', 'Checking', '0.00'),
	('Housing',  'Checking', '0.00'),
	('Medical',  'Checking', '0.00'),
	('Car/Auto', 'Checking', '0.00'),
	('Personal',  'Checking', '0.00'),
	('Recreation',  'Checking', '0.00'),
	('Utilities',  'Checking', '0.00'),
	('Charity',  'Checking', '0.00'),
	('Miscellaneous/Other', 'Checking', '0.00');

