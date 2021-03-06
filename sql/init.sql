-- phpMyAdmin SQL Dump
-- version 4.4.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 01, 2015 at 07:33 
-- Server version: 5.6.24
-- PHP Version: 5.6.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `summittosea`
--
CREATE DATABASE IF NOT EXISTS `summittosea` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `summittosea`;

-- --------------------------------------------------------

--
-- Table structure for table `activity`
--

CREATE TABLE IF NOT EXISTS `activity` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `competition`
--

CREATE TABLE IF NOT EXISTS `competition` (
  `id` int(11) NOT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `achievements_by_places` tinyint(1) NOT NULL DEFAULT '0',
  `activity_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `participation`
--

CREATE TABLE IF NOT EXISTS `participation` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `competition_id` int(11) NOT NULL,
  `places` int(11) DEFAULT NULL,
  `summits` int(11) DEFAULT NULL,
  `meters_above_sea_level` int(11) DEFAULT NULL,
  `distance` int(11) DEFAULT NULL,
  `points` int(11) DEFAULT NULL,
  `minutes` int(11) DEFAULT NULL,
  `start_time` int(11) DEFAULT NULL,
  `finish_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `place`
--

CREATE TABLE IF NOT EXISTS `place` (
  `id` int(11) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `meters_above_sea_level` int(11) DEFAULT NULL,
  `distance` int(11) DEFAULT NULL,
  `points` int(11) DEFAULT NULL,
  `latitude` float DEFAULT NULL,
  `longtitude` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `places_activities`
--

CREATE TABLE IF NOT EXISTS `places_activities` (
  `id` int(11) NOT NULL,
  `place_id` int(11) NOT NULL,
  `activity_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `places_competitions`
--

CREATE TABLE IF NOT EXISTS `places_competitions` (
  `id` int(11) NOT NULL,
  `place_id` int(11) NOT NULL,
  `competition_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `prize`
--

CREATE TABLE IF NOT EXISTS `prize` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `points` int(11) DEFAULT NULL,
  `summits` int(11) DEFAULT NULL,
  `meters_above_sea_level` int(11) DEFAULT NULL,
  `distance` int(11) DEFAULT NULL,
  `competition_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `gender` enum('','MALE','FEMALE','OTHER') DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `points` int(11) DEFAULT '0',
  `summits` int(11) DEFAULT '0',
  `meters_above_sea_level` int(11) DEFAULT '0',
  `rank` int(11) DEFAULT '0',
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
  `client_notes` text
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `firstname`, `lastname`, `gender`, `email`, `phone`, `points`, `summits`, `meters_above_sea_level`, `rank`, `auth_key`, `auth_key_expires`, `password_hash`, `password_reset_token`, `status`, `role`, `created_at`, `updated_at`, `last_login`, `login_count`, `image`, `image_hash`, `facebook_id`, `device_token`, `platform`, `client_notes`) VALUES
(1, 'admin', NULL, NULL, NULL, 'admin@summittosea.no', NULL, 0, 0, 0, 0, NULL, NULL, '$2y$13$G8InQdDcR.MHL/tt5vTzVOWL44QJrbwsPD7nimtIhtBcFEU5S1izO', NULL, 10, 20, 1432705276, 1432705276, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `visit`
--

CREATE TABLE IF NOT EXISTS `visit` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `place_id` int(11) NOT NULL,
  `competition_id` int(11) DEFAULT NULL,
  `visit_time` int(11) NOT NULL,
  `acivity_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `watching`
--

CREATE TABLE IF NOT EXISTS `watching` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `watched_user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `win`
--

CREATE TABLE IF NOT EXISTS `win` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `prize_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity`
--
ALTER TABLE `activity`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `competition`
--
ALTER TABLE `competition`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `participation`
--
ALTER TABLE `participation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `place`
--
ALTER TABLE `place`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `places_activities`
--
ALTER TABLE `places_activities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `places_competitions`
--
ALTER TABLE `places_competitions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prize`
--
ALTER TABLE `prize`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- Indexes for table `visit`
--
ALTER TABLE `visit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `watching`
--
ALTER TABLE `watching`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `win`
--
ALTER TABLE `win`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity`
--
ALTER TABLE `activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `competition`
--
ALTER TABLE `competition`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `participation`
--
ALTER TABLE `participation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `place`
--
ALTER TABLE `place`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `places_activities`
--
ALTER TABLE `places_activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `places_competitions`
--
ALTER TABLE `places_competitions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `prize`
--
ALTER TABLE `prize`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `visit`
--
ALTER TABLE `visit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `watching`
--
ALTER TABLE `watching`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `win`
--
ALTER TABLE `win`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


ALTER TABLE `user` CHANGE `username` `username` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;