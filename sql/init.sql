-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 28, 2015 at 08:12 PM
-- Server version: 5.1.63-community
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `summit`
--

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `gender` enum('','MALE','FEMALE','OTHER') DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `auth_key` varchar(255) DEFAULT NULL,
  `auth_key_expires` int(11) DEFAULT NULL,
  `password_hash` varchar(255) DEFAULT NULL,
  `password_reset_token` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '10',
  `role` int(11) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `last_login` int(11) DEFAULT NULL,
  `login_count` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `image_hash` varchar(32) DEFAULT NULL,
  `facebook_id` bigint(20) DEFAULT NULL,
  `device_token` varchar(64) DEFAULT NULL,
  `platform` enum('apns','gcm') DEFAULT NULL,
  `client_notes` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `phone` (`phone`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `firstname`, `lastname`, `gender`, `email`, `phone`, `auth_key`, `auth_key_expires`, `password_hash`, `password_reset_token`, `status`, `role`, `created_at`, `updated_at`, `last_login`, `login_count`, `image`, `image_hash`, `facebook_id`, `device_token`, `platform`, `client_notes`) VALUES
(1, 'admin', NULL, NULL, NULL, 'admin@summittosea.no', NULL, NULL, NULL, '$2y$13$G8InQdDcR.MHL/tt5vTzVOWL44QJrbwsPD7nimtIhtBcFEU5S1izO', NULL, 10, 20, 1432705276, 1432705276, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

