-- phpMyAdmin SQL Dump
-- version 2.11.9.4
-- http://www.phpmyadmin.net
--
-- Host: oniddb
-- Generation Time: Dec 02, 2016 at 08:36 PM
-- Server version: 5.5.52
-- PHP Version: 5.2.6-1+lenny16

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `kurakuls-db`
--

-- --------------------------------------------------------

--
-- Table structure for table `activitylog`
--

DROP TABLE IF EXISTS `activitylog`;
CREATE TABLE IF NOT EXISTS `activitylog` (
  `activity_id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `username` varchar(100) NOT NULL,
  `action` varchar(100) NOT NULL,
  `additionalinfo` varchar(500) NOT NULL DEFAULT 'none',
  `ip` varchar(15) NOT NULL,
  PRIMARY KEY (`activity_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=178 ;

-- --------------------------------------------------------

--
-- Table structure for table `attempts`
--

DROP TABLE IF EXISTS `attempts`;
CREATE TABLE IF NOT EXISTS `attempts` (
  `ip` varchar(15) NOT NULL,
  `count` int(11) NOT NULL,
  `expiredate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `award`
--

DROP TABLE IF EXISTS `award`;
CREATE TABLE IF NOT EXISTS `award` (
  `award_ID` int(2) NOT NULL AUTO_INCREMENT,
  `award_class` varchar(100) NOT NULL,
  PRIMARY KEY (`award_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `award_record`
--

DROP TABLE IF EXISTS `award_record`;
CREATE TABLE IF NOT EXISTS `award_record` (
  `award_record_ID` int(3) NOT NULL AUTO_INCREMENT,
  `recipient_lname` varchar(100) NOT NULL,
  `recipient_fname` varchar(100) NOT NULL,
  `award_create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usr_ID` int(3) NOT NULL,
  `awd_ID` int(2) NOT NULL,
  `reg_ID` int(2) NOT NULL,
  `recipient_email` varchar(100) NOT NULL,
  `show_award` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`award_record_ID`),
  KEY `awd_ID` (`awd_ID`),
  KEY `usr_ID` (`usr_ID`),
  KEY `reg_ID` (`reg_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

-- --------------------------------------------------------

--
-- Table structure for table `region`
--

DROP TABLE IF EXISTS `region`;
CREATE TABLE IF NOT EXISTS `region` (
  `region_ID` int(2) NOT NULL AUTO_INCREMENT,
  `city` varchar(100) DEFAULT NULL,
  `region_name` varchar(100) NOT NULL,
  PRIMARY KEY (`region_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

DROP TABLE IF EXISTS `reports`;
CREATE TABLE IF NOT EXISTS `reports` (
  `report_ID` int(2) NOT NULL AUTO_INCREMENT,
  `report_type` varchar(255) NOT NULL,
  PRIMARY KEY (`report_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `session_id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(3) NOT NULL,
  `username` varchar(100) NOT NULL,
  `hash` varchar(32) NOT NULL,
  `expiredate` datetime NOT NULL,
  `ip` varchar(15) NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=130 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(128) NOT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `firstname` varchar(100) DEFAULT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `isactive` tinyint(1) NOT NULL DEFAULT '0',
  `activekey` varchar(15) NOT NULL DEFAULT '0',
  `resetkey` varchar(15) NOT NULL DEFAULT '0',
  `acc_ID` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`,`email`,`username`),
  KEY `acc_ID` (`acc_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_access`
--

DROP TABLE IF EXISTS `user_access`;
CREATE TABLE IF NOT EXISTS `user_access` (
  `access_ID` tinyint(1) NOT NULL AUTO_INCREMENT,
  `access_level` varchar(100) NOT NULL,
  PRIMARY KEY (`access_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `award_record`
--
ALTER TABLE `award_record`
  ADD CONSTRAINT `award_record_ibfk_1` FOREIGN KEY (`awd_ID`) REFERENCES `award` (`award_ID`),
  ADD CONSTRAINT `award_record_ibfk_2` FOREIGN KEY (`usr_ID`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `award_record_ibfk_3` FOREIGN KEY (`reg_ID`) REFERENCES `region` (`region_ID`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`acc_ID`) REFERENCES `user_access` (`access_ID`);
