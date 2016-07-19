-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 18, 2015 at 01:25 AM
-- Server version: 5.5.44-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `myceo`
--

-- --------------------------------------------------------

--
-- Table structure for table `mailbox`
--

CREATE TABLE IF NOT EXISTS `mailbox` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `SenderID` int(11) DEFAULT NULL,
  `ReceiverID` int(11) DEFAULT NULL,
  `Subject` varchar(255) DEFAULT NULL,
  `Message` text,
  `SentDate` datetime DEFAULT NULL,
  `CreatedDate` datetime DEFAULT NULL,
  `entry_by` int(11) DEFAULT NULL,
  `IsView` enum('0','1') DEFAULT '0',
  `Status` enum('draft','sent','receive','trash','inbox') DEFAULT 'inbox',
  `Category` int(6) DEFAULT NULL,
  `Labels` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

--
-- Dumping data for table `mailbox`
--

INSERT INTO `mailbox` (`Id`, `SenderID`, `ReceiverID`, `Subject`, `Message`, `SentDate`, `CreatedDate`, `entry_by`, `IsView`, `Status`, `Category`, `Labels`) VALUES
(9, 1, 38, 'test sending email', 'Hellow everybody here ?<br><p><br></p>', '2015-10-26 02:16:28', '2015-10-26 02:16:28', NULL, '1', 'inbox', NULL, NULL),
(10, 1, 31, 'test mail app', '<p>this is a test for mail app</p>', '2015-10-28 05:19:09', '2015-10-28 05:19:09', NULL, '1', 'sent', NULL, NULL),
(11, 1, 31, 'test mail app', '<p>this is a test for mail app</p>', '2015-10-28 05:19:09', '2015-10-28 05:19:09', NULL, '1', 'inbox', NULL, NULL),
(12, 1, 38, 'yyyy', '<p>Hello there ,</p><p><br></p><p>We are testing for mailbox module , let us know if this work <br></p><p><br></p><p><br></p><p>Admini<br></p>', '2015-10-30 02:49:47', '2015-10-30 02:49:47', NULL, '0', 'inbox', NULL, NULL),
(13, 31, 38, 'Hi', '<p>Hisd cvdvc</p>', '2015-10-29 06:49:15', '2015-10-29 06:49:15', NULL, '1', 'inbox', 0, NULL),
(14, 1, 7, 'Trial', '<p>Hello this is for notification and mailbox trial by Vishal</p>', '2015-10-29 07:38:49', '2015-10-29 07:38:49', NULL, '1', 'inbox', 0, NULL),
(15, 1, 31, 'Trial', '<p>Hello this is for notification and mailbox trial by Vishal</p>', '2015-10-29 07:38:49', '2015-10-29 07:38:49', NULL, '1', 'inbox', 0, NULL),
(16, 1, 38, 'Trial', '<p>Hello this is for notification and mailbox trial by Vishal</p>', '2015-10-29 07:38:49', '2015-10-29 07:38:49', NULL, '1', 'inbox', 0, NULL),
(19, 1, 31, 'Hi there', 'Hi there , this is test email from new mailbox<br><br>Let us know if this work for you <br><p><br></p>', '2015-10-30 02:51:10', '2015-10-30 02:51:10', NULL, '1', 'sent', NULL, NULL),
(20, 1, 31, 'Hi there', 'Hi there , this is test email from new mailbox<br><br>Let us know if this work for you <br><p><br></p>', '2015-10-30 02:51:10', '2015-10-30 02:51:10', NULL, '1', 'inbox', NULL, NULL),
(21, 31, 31, 'Hi there', 'Hi there , this is test email from new mailbox<br><br>Let us know if this work for you <br><p><br><br>Yes this worked!!</p>', '2015-10-30 05:15:54', '2015-10-30 05:15:54', NULL, '0', 'sent', NULL, NULL),
(22, 31, 31, 'Hi there', 'Hi there , this is test email from new mailbox<br><br>Let us know if this work for you <br><p><br><br>Yes this worked!!</p>', '2015-10-30 05:15:54', '2015-10-30 05:15:54', NULL, '1', 'inbox', NULL, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
