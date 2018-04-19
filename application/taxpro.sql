-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 19, 2018 at 04:53 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `taxpro`
--

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE IF NOT EXISTS `cities` (
  `city_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `state_id` bigint(20) unsigned DEFAULT NULL COMMENT '1 to 1 with states',
  `status` tinyint(4) unsigned DEFAULT '1' COMMENT '1-Active, 2-Inactive',
  `create_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`city_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`city_id`, `name`, `state_id`, `status`, `create_date`) VALUES
(1, 'Jaipur', 1, 1, '2018-02-19 19:03:16'),
(2, 'Sikar', 1, 1, '2018-02-19 19:03:23'),
(3, 'Ahmedabad', 2, 1, '2018-02-19 19:03:32'),
(4, 'Gandhinagar', 2, 1, '2018-02-19 19:03:44'),
(5, 'a', 3, 1, '2018-02-19 19:03:44'),
(6, 'aa', 3, 1, '2018-02-19 19:03:44'),
(7, 'b', 4, 1, '2018-02-19 19:03:44'),
(8, 'bb', 4, 1, '2018-02-19 19:03:44'),
(9, 'c', 5, 1, '2018-02-19 19:03:44'),
(10, 'cc', 5, 1, '2018-02-19 19:03:44');

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE IF NOT EXISTS `clients` (
  `client_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `gender` tinyint(4) DEFAULT '11' COMMENT '11-Male, 12-Female',
  `street1` varchar(100) DEFAULT NULL,
  `street2` varchar(100) DEFAULT NULL,
  `country_id` bigint(20) unsigned DEFAULT NULL,
  `state_id` bigint(20) unsigned DEFAULT NULL,
  `city_id` bigint(20) unsigned DEFAULT NULL,
  `zip` varchar(20) DEFAULT NULL,
  `status` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT '1-Active, 2-Inactive',
  `create_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_by` bigint(20) unsigned DEFAULT NULL COMMENT 'user_id from users',
  PRIMARY KEY (`client_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`client_id`, `first_name`, `last_name`, `email`, `contact`, `dob`, `gender`, `street1`, `street2`, `country_id`, `state_id`, `city_id`, `zip`, `status`, `create_date`, `create_by`) VALUES
(1, 'client', 'one', 'client@one.com', '9999999999', '2018-03-01', 12, 's', '', 1, 2, 3, '999999', 1, '2018-03-05 22:21:48', 1),
(2, 'client', 'two', 'client@two.com', '3333333333', '2018-03-01', 11, 'fd', '', 2, 3, 5, '332024', 2, '2018-03-05 22:22:51', 2),
(3, 'client', 'three', 'client@three.com', '8888888888', '2018-03-01', 12, '', '', 2, 3, 5, '123456', 2, '2018-03-05 22:22:51', 2),
(5, 'sdf', '', 'sdf@sda.dfs', '', '2018-03-07', 12, '', '', 2, 3, 5, '435', 1, '2018-03-12 16:23:32', 1),
(8, 'pqrs', '', 'pqrs@gmail.com', '', '2018-03-02', 12, 'abc', '', 2, 4, 8, '222222', 2, '2018-03-12 16:37:46', 1),
(9, 'new', '', 'new@gmail.com', '', '2018-03-01', 12, '', '', 2, 4, 8, '342', 2, '2018-03-12 19:02:34', 1),
(10, 'awxyz', '', 'awxyz@gmail.com', '', '2018-03-01', 11, 'd', '', 2, 5, 10, '123456789', 1, '2018-03-13 15:59:59', 1),
(11, 'Ravi', 'Prajapati', 'ehs.ravip@gmail.com', '', '2018-03-01', 12, 'ss', '', 1, 1, 1, '4325', 2, '2018-03-13 16:02:09', 1),
(12, 'Rakesh', '', 'drjtarpura@gmail.com', '', '2018-03-02', 11, 'dd', '', 1, 1, 1, '3434', 2, '2018-03-15 00:52:31', 1);

-- --------------------------------------------------------

--
-- Table structure for table `clients_attachments`
--

CREATE TABLE IF NOT EXISTS `clients_attachments` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `client_id` bigint(20) unsigned NOT NULL COMMENT 'm to m with users',
  `name` varchar(100) NOT NULL,
  `file_name` varchar(100) DEFAULT NULL,
  `size` int(10) unsigned DEFAULT NULL COMMENT 'in KB',
  `type` varchar(50) DEFAULT NULL,
  `extension` varchar(20) DEFAULT NULL,
  `status` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT '1-Active, 2-Inactive',
  `create_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `clients_attachments`
--

INSERT INTO `clients_attachments` (`id`, `client_id`, `name`, `file_name`, `size`, `type`, `extension`, `status`, `create_date`) VALUES
(1, 4, 'NI-017.jpg', '48f949bacc0dba6953084166b13b1f9d.jpg', 82, 'image/jpeg', 'jpg', 1, '2018-03-06 22:08:52'),
(2, 4, 'NI-017_1(MODEL).jpg', '1345dab9de8550ff57fb027abb99630d.jpg', 54, 'image/jpeg', 'jpg', 1, '2018-03-06 22:08:52'),
(3, 4, 'NI-100.jpg', '4f324e4a82d6464cf69f391ed9d984b2.jpg', 85, 'image/jpeg', 'jpg', 1, '2018-03-06 22:08:52'),
(4, 4, 'NI-100_1.jpg', '7946f8463c30c29bc46d39526627a72d.jpg', 43, 'image/jpeg', 'jpg', 1, '2018-03-06 22:08:52'),
(5, 4, 'JISOM-SU16-A24.pdf', '0ea01fd299037295914ecf39ed347801.pdf', 574, 'application/pdf', 'pdf', 1, '2018-03-06 22:09:28');

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE IF NOT EXISTS `countries` (
  `country_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `status` tinyint(4) unsigned DEFAULT '1' COMMENT '1-Active, 2-Inactive',
  `create_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`country_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`country_id`, `name`, `status`, `create_date`) VALUES
(1, 'India', 1, '2018-02-19 18:57:16'),
(2, 'US', 1, '2018-02-19 18:57:16');

-- --------------------------------------------------------

--
-- Table structure for table `cp_attachments`
--

CREATE TABLE IF NOT EXISTS `cp_attachments` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `orders_details_id` bigint(20) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `file_name` varchar(100) DEFAULT NULL,
  `size` int(10) unsigned DEFAULT NULL COMMENT 'in KB',
  `type` varchar(50) DEFAULT NULL,
  `extension` varchar(20) DEFAULT NULL,
  `status` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT '1-Active, 2-Inactive',
  `create_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `cp_attachments`
--

INSERT INTO `cp_attachments` (`id`, `orders_details_id`, `name`, `file_name`, `size`, `type`, `extension`, `status`, `create_date`) VALUES
(4, 16, 'logo_mini.png', 'b616ee2ce54d1123e4b3be9dfe4e6180.png', 2, 'image/png', 'png', 1, '2018-03-14 23:20:59'),
(5, 20, 'NI-029_1.jpg', '17d458aa97f5324e6aff4c655f967bb7.jpg', 56, 'image/jpeg', 'jpg', 1, '2018-03-15 00:52:31'),
(6, 20, 'NI-031_1.jpg', 'ba0fb10e1f59a6b1867a2d81aa1b2476.jpg', 52, 'image/jpeg', 'jpg', 1, '2018-03-15 00:52:31'),
(7, 20, 'NI-032_1.jpg', '222c1f40bd41824f7d48ec7bc675840f.jpg', 53, 'image/jpeg', 'jpg', 1, '2018-03-15 00:52:31'),
(8, 20, 'NI-041_1(Fine-Wool).jpg', 'd2860984e41249d5517b5bdd7aa7a60f.jpg', 43, 'image/jpeg', 'jpg', 1, '2018-03-15 00:52:31');

-- --------------------------------------------------------

--
-- Table structure for table `message_masters`
--

CREATE TABLE IF NOT EXISTS `message_masters` (
  `message_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `message_code` bigint(20) unsigned DEFAULT NULL,
  `subject` varchar(500) DEFAULT NULL,
  `message_body` text,
  PRIMARY KEY (`message_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `message_masters`
--

INSERT INTO `message_masters` (`message_id`, `message_code`, `subject`, `message_body`) VALUES
(1, 21, 'Taxprotection', 'Hello {{salutation}}. {{first_name}} {{last_name}}<h4>Welcome to <strong>Taxproprotection</strong></h4>'),
(2, 22, 'Taxprotection', 'Hello {{salutation}}. {{first_name}} {{last_name}}<h4>Welcome to <strong>Taxproprotection</strong></h4>');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `order_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint(20) unsigned NOT NULL COMMENT '1 to m clients',
  `order_date` date NOT NULL,
  `description` text,
  `status` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT '1-Active, 2-Inactive',
  `processing_status` tinyint(3) unsigned NOT NULL DEFAULT '3' COMMENT '3-Pending, 4-Processing, 5-Done',
  `create_by` bigint(20) unsigned DEFAULT NULL COMMENT 'salesman''s identification who create this order',
  `assigned_to` bigint(20) unsigned DEFAULT NULL,
  `assign_date` date DEFAULT NULL,
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `client_id`, `order_date`, `description`, `status`, `processing_status`, `create_by`, `assigned_to`, `assign_date`) VALUES
(1, 1, '2018-03-01', NULL, 1, 4, 2, 2, '2018-03-01'),
(2, 2, '2018-03-02', NULL, 1, 4, 2, 3, '2018-03-03'),
(3, 3, '2018-03-03', NULL, 1, 5, 3, 1, '2018-03-13'),
(5, 8, '2018-03-12', NULL, 1, 4, 2, 2, '2018-03-12'),
(6, 2, '2018-03-01', NULL, 1, 4, 3, 4, '2018-03-05'),
(7, 9, '2018-03-12', NULL, 1, 4, 2, 2, '2018-03-13'),
(8, 10, '2018-03-13', NULL, 1, 3, NULL, NULL, NULL),
(9, 11, '2018-03-13', NULL, 1, 4, NULL, 1, '2018-03-14'),
(10, 12, '2018-03-14', NULL, 2, 5, NULL, 1, '2018-03-14');

-- --------------------------------------------------------

--
-- Table structure for table `orders_details`
--

CREATE TABLE IF NOT EXISTS `orders_details` (
  `orders_details_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL COMMENT 'm to m with orders',
  `product_id` bigint(20) unsigned NOT NULL COMMENT '1 to m with products',
  `quantity` int(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`orders_details_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

--
-- Dumping data for table `orders_details`
--

INSERT INTO `orders_details` (`orders_details_id`, `order_id`, `product_id`, `quantity`) VALUES
(1, 1, 1, 22),
(2, 2, 1, 111),
(3, 3, 1, 313),
(6, 3, 5, 500),
(7, 5, 2, 12),
(8, 5, 3, 123),
(9, 6, 1, 5),
(12, 7, 4, 444),
(13, 7, 5, 555),
(14, 8, 2, 222),
(15, 8, 4, 444),
(16, 9, 1, 111),
(17, 9, 2, 222),
(18, 7, 1, 100),
(19, 7, 2, 100),
(20, 10, 2, 34),
(21, 7, 3, 3);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE IF NOT EXISTS `products` (
  `product_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_identifier` varchar(20) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` text,
  `price` int(11) DEFAULT NULL,
  `status` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT '1-Active, 2-Inactive',
  `create_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_identifier`, `name`, `description`, `price`, `status`, `create_date`) VALUES
(1, 'p001', 'one', 'one 1', 100, 2, '2018-03-05 22:46:17'),
(2, 'p002', 'two', 'two', 200, 1, '2018-03-05 22:46:36'),
(3, 'p003', 'three', 'three', 300, 1, '2018-03-05 22:46:53'),
(4, 'p004', 'four', 'four', 400, 2, '2018-03-05 22:47:05'),
(5, 'p005', 'five', NULL, 500, 1, '2018-03-05 22:47:18');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(100) DEFAULT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `website_title` varchar(200) DEFAULT NULL,
  `template_skin` varchar(50) DEFAULT NULL,
  `logo_mini` varchar(200) DEFAULT NULL,
  `logo_lg` varchar(200) DEFAULT NULL,
  `smtp_email` varchar(100) DEFAULT NULL,
  `smtp_pass` varchar(100) DEFAULT NULL,
  `email_alias` varchar(50) DEFAULT NULL,
  `autologout` tinyint(1) NOT NULL DEFAULT '13' COMMENT '13 - Off, 14 - On',
  `autologout_mins` tinyint(3) unsigned DEFAULT '10' COMMENT 'autologout time in minutes',
  `allowed_file_extensions` varchar(500) DEFAULT NULL,
  `file_upload_size_bytes` int(10) unsigned DEFAULT NULL,
  `notification_display_duration_ms` varchar(20) NOT NULL DEFAULT '2000' COMMENT '1s = 1000ms',
  `profile_pic_extensions` varchar(500) DEFAULT 'jpg|jpeg|png',
  `profile_pic_size_bytes` int(10) unsigned DEFAULT NULL COMMENT '1mb = 1,000,000 bytes',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `email`, `contact`, `website_title`, `template_skin`, `logo_mini`, `logo_lg`, `smtp_email`, `smtp_pass`, `email_alias`, `autologout`, `autologout_mins`, `allowed_file_extensions`, `file_upload_size_bytes`, `notification_display_duration_ms`, `profile_pic_extensions`, `profile_pic_size_bytes`) VALUES
(1, 'rakeshjangir.ehs@gmail.com', '9166650505', 'Tax Pro', 'skin-yellow', 'logo_mini.png', 'logo.png', 'rakeshjangir.ehs@gmail.com', 'abc@123_4', 'TaxPro', 14, 2, 'png|jpeg|jpg|gif|xls|xlsx|doc|docx|pdf', 2000000, '1000', 'jpg|jpeg|png', 1000000);

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE IF NOT EXISTS `states` (
  `state_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `country_id` bigint(20) unsigned DEFAULT NULL COMMENT '1 to 1 with country',
  `status` tinyint(3) unsigned DEFAULT '1' COMMENT '1-Active, 2-Inactive',
  `create_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`state_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`state_id`, `name`, `country_id`, `status`, `create_date`) VALUES
(1, 'Rajasthan', 1, 1, '2018-02-19 18:58:54'),
(2, 'Gujarat', 1, 1, '2018-02-19 18:59:01'),
(3, 'A', 2, 1, '2018-02-19 18:59:01'),
(4, 'B', 2, 1, '2018-02-19 18:59:01'),
(5, 'C', 2, 1, '2018-02-19 18:59:01');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `gender` tinyint(4) DEFAULT '11' COMMENT '11-Male, 12-Female',
  `username` varchar(200) DEFAULT NULL,
  `password` varchar(200) DEFAULT NULL,
  `role` tinyint(3) unsigned NOT NULL DEFAULT '9' COMMENT '9-User, 10-Admin',
  `street1` varchar(100) DEFAULT NULL,
  `street2` varchar(100) DEFAULT NULL,
  `country_id` bigint(20) unsigned DEFAULT NULL,
  `state_id` bigint(20) unsigned DEFAULT NULL,
  `city_id` bigint(20) unsigned DEFAULT NULL,
  `zip` varchar(20) DEFAULT NULL,
  `photo` varchar(100) DEFAULT NULL,
  `status` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT '1-Active, 2-Inactive',
  `create_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `reset_hash` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `email`, `contact`, `dob`, `gender`, `username`, `password`, `role`, `street1`, `street2`, `country_id`, `state_id`, `city_id`, `zip`, `photo`, `status`, `create_date`, `reset_hash`) VALUES
(1, 'rakesh', 'Jangir', 'rakeshjangir.ehs@gmail.com', '9166650505', '2018-02-02', 11, 'rakeshj', 'e24710c6c012fd80bcd9acb25c3ebf80', 10, 'a', '', 1, 1, 2, '332024', 'f1bc29ee8c672de681d9f92ebbb2d6cd.jpg', 1, '2018-02-26 22:42:54', '8218f0c8726b601c87270af62458320d'),
(2, 'Harish', 'Sharma', 'test@one.com', '1111111111', '2018-03-01', 11, 'testone', 'e24710c6c012fd80bcd9acb25c3ebf80', 9, 'aa', '', 1, 2, 4, '111111', '505584f08c0e29b99cde1bec6e35e045.jpg', 1, '2018-03-05 22:16:16', NULL),
(3, 'Ravi', 'Kumar', 'test@two.com', '2222222222', '2018-03-02', 12, 'testtwo', 'dcca2dd6adce1e312d01ad347e0b2c35', 9, 'dd', '', 2, 4, 8, '222222', NULL, 1, '2018-03-05 22:16:53', NULL),
(4, 'dev', 'Sharma', 'rjtarpura@gmail.com', '', '2018-03-01', 11, 'rakeshja', 'c443e39c7e18ef453080f9ee8f7de83f', 9, 'ss', '', 1, 2, 3, '343', NULL, 1, '2018-03-05 22:39:06', NULL),
(5, 'wxyz', '', 'wxyz@gmail.com', '', '2018-03-01', 11, 'wxyz', 'd536867dc911e9740515b669d633eaf8', 9, 'dd', '', 1, 2, 4, '12356', NULL, 2, '2018-03-13 15:58:33', NULL),
(6, 'Rakesh', '', 'srjtarpura@gmail.com', '', '2018-03-08', 11, 'rakesh', 'af9eb91ffbe66f1726291f7e4d424aac', 9, 'abc', '', 1, 1, 1, '123123', NULL, 1, '2018-03-15 00:51:27', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users_attachments`
--

CREATE TABLE IF NOT EXISTS `users_attachments` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL COMMENT 'm to m with users',
  `name` varchar(100) NOT NULL,
  `file_name` varchar(100) DEFAULT NULL,
  `size` int(10) unsigned DEFAULT NULL COMMENT 'in KB',
  `type` varchar(50) DEFAULT NULL,
  `extension` varchar(20) DEFAULT NULL,
  `status` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT '1-Active, 2-Inactive',
  `create_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `users_attachments`
--

INSERT INTO `users_attachments` (`id`, `user_id`, `name`, `file_name`, `size`, `type`, `extension`, `status`, `create_date`) VALUES
(1, 4, 'NI-031_1.jpg', '178c2c214645db4e94b585de7016c522.jpg', 52, 'image/jpeg', 'jpg', 1, '2018-03-15 14:14:29'),
(2, 4, 'NI-032_1.jpg', '2ba7b9af2fab1594d35c1a7e465b736d.jpg', 53, 'image/jpeg', 'jpg', 1, '2018-03-15 14:14:29'),
(3, 4, 'NI-100.jpg', '1b1d21fe40936bb3e7fccbf8439eefad.jpg', 92, 'image/jpeg', 'jpg', 1, '2018-03-15 16:13:56'),
(4, 6, 'NI-032_1.jpg', '03aa7761211f51a88286fe38505c074c.jpg', 53, 'image/jpeg', 'jpg', 1, '2018-03-15 16:35:58'),
(5, 6, 'NI-029.jpg', '5a6bc8a710ffd353c6351c58e86bea83.jpg', 106, 'image/jpeg', 'jpg', 1, '2018-03-15 16:36:04');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
