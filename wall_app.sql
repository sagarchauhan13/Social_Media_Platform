-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 22, 2020 at 10:01 AM
-- Server version: 5.7.26
-- PHP Version: 7.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wall_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(50) NOT NULL,
  `user_email` varchar(50) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_mobile` bigint(10) NOT NULL,
  `user_photo` varchar(5000) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_on` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `user_name`, `user_email`, `user_password`, `user_mobile`, `user_photo`, `created_on`, `updated_on`) VALUES
(1, 'Sagar', 'sagar@mail.com', '$2y$10$wQsUxp50CB/iYMRcE6a65eg7GQGNQPG/rTopj.6kZza4z/Kxyn4N6', 9945533122, 'om.jpg', '2020-01-21 10:17:43', NULL),
(2, 'Mehul', 'mehul@mail.com', '$2y$10$rDPS87HvrK5xvvoyGJg0DO6QKn4t.Tvg7x2pCGzbTtdXMXI3voS4O', 9945533122, 'nature.jpg', '2020-01-21 10:18:04', NULL),
(3, 'Dhaval', 'dhaval.s@e2logy.com', '$2y$10$wsj1UoeHScDWXAs24JlgQOyfA8P84otFj.FezW1teMVW7an8hBpIO', 9945533122, 'e2logy.png', '2020-01-21 10:18:18', NULL),
(4, 'Hemal', 'hemal@mail.com', '$2y$10$UxB884GanS14u7ONPV3l5u66lvlRDWPl8OrDwbQoJpmvysdjmhYkG', 123, 'nature.jpg', '2020-01-21 10:56:22', NULL),
(5, 'Try User', 'try@mail.com', '$2y$10$hhDeoX6ScDkTBng9QcCwWOYPgzG9P3yGn39UQEjWEbjQ1uUroXA3S', 123, 'om.jpg', '2020-01-21 13:46:21', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_comment`
--

DROP TABLE IF EXISTS `user_comment`;
CREATE TABLE IF NOT EXISTS `user_comment` (
  `user_comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `user_comment` varchar(255) NOT NULL,
  `user_content_id` int(11) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_on` datetime DEFAULT NULL,
  PRIMARY KEY (`user_comment_id`),
  KEY `user_content_id` (`user_content_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_content`
--

DROP TABLE IF EXISTS `user_content`;
CREATE TABLE IF NOT EXISTS `user_content` (
  `user_content_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(50) NOT NULL,
  `user_content` longtext NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_on` datetime DEFAULT NULL,
  PRIMARY KEY (`user_content_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_content_response`
--

DROP TABLE IF EXISTS `user_content_response`;
CREATE TABLE IF NOT EXISTS `user_content_response` (
  `response_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(50) NOT NULL,
  `user_content_id` int(50) NOT NULL,
  `content_response` enum('1','2') DEFAULT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_on` datetime DEFAULT NULL,
  PRIMARY KEY (`response_id`),
  KEY `user_id` (`user_id`),
  KEY `user_content_id` (`user_content_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_friend`
--

DROP TABLE IF EXISTS `user_friend`;
CREATE TABLE IF NOT EXISTS `user_friend` (
  `user_friend_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `requested_user_id` int(11) NOT NULL,
  PRIMARY KEY (`user_friend_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_request`
--

DROP TABLE IF EXISTS `user_request`;
CREATE TABLE IF NOT EXISTS `user_request` (
  `user_request_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(50) NOT NULL,
  `requested_user_id` varchar(50) NOT NULL,
  `status_id` enum('1','2','3') DEFAULT '1',
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_on` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_request_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
