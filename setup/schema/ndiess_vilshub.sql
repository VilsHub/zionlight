-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 10.123.0.78:3307
-- Generation Time: Dec 28, 2020 at 04:05 PM
-- Server version: 8.0.16
-- PHP Version: 7.0.33-0+deb9u9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ndiess_vilshub`
--

-- --------------------------------------------------------

--
-- Table structure for table `appDevRequests`
--

CREATE TABLE `appDevRequests` (
  `id` int(1) NOT NULL,
  `platform` int(1) DEFAULT NULL,
  `projectName` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `projectDescription` text COLLATE utf8_unicode_ci NOT NULL,
  `duration` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `budget` varchar(7) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(14) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bookDev`
--

CREATE TABLE `bookDev` (
  `id` int(1) NOT NULL,
  `taskType` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `taskDescription` text COLLATE utf8_unicode_ci NOT NULL,
  `location` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `dateNeeded` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(14) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `channels`
--

CREATE TABLE `channels` (
  `id` int(1) NOT NULL,
  `name` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `channels`
--

INSERT INTO `channels` (`id`, `name`) VALUES
(1, 'messenger'),
(2, 'whatsapp'),
(3, 'telegram'),
(4, 'phone');

-- --------------------------------------------------------

--
-- Table structure for table `domains`
--

CREATE TABLE `domains` (
  `id` int(1) NOT NULL,
  `name` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `domains`
--

INSERT INTO `domains` (`id`, `name`) VALUES
(1, 'System administration'),
(2, 'Programming');

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` int(1) NOT NULL,
  `name` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id`, `name`) VALUES
(1, 'basic'),
(2, 'pro'),
(3, 'expert');

-- --------------------------------------------------------

--
-- Table structure for table `platforms`
--

CREATE TABLE `platforms` (
  `id` int(1) NOT NULL,
  `name` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `platforms`
--

INSERT INTO `platforms` (`id`, `name`) VALUES
(1, 'web'),
(2, 'pc'),
(3, 'embedded');

-- --------------------------------------------------------

--
-- Table structure for table `realtimeSolutionSubscribtions`
--

CREATE TABLE `realtimeSolutionSubscribtions` (
  `id` int(5) NOT NULL,
  `first_name` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `domain` int(1) NOT NULL,
  `startDate` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `active` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trainingEnrollement`
--

CREATE TABLE `trainingEnrollement` (
  `id` int(1) NOT NULL,
  `fullName` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(14) COLLATE utf8_unicode_ci NOT NULL,
  `platform` int(1) DEFAULT NULL,
  `package` int(1) DEFAULT NULL,
  `startDate` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `paid` enum('0','1') COLLATE utf8_unicode_ci DEFAULT '0',
  `balance` varchar(7) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `userChannels`
--

CREATE TABLE `userChannels` (
  `id` int(1) NOT NULL,
  `userID` int(5) NOT NULL,
  `channel` int(1) NOT NULL,
  `channelID` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appDevRequests`
--
ALTER TABLE `appDevRequests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `appDevRequestsFK` (`platform`);

--
-- Indexes for table `bookDev`
--
ALTER TABLE `bookDev`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `channels`
--
ALTER TABLE `channels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `domains`
--
ALTER TABLE `domains`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `platforms`
--
ALTER TABLE `platforms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `realtimeSolutionSubscribtions`
--
ALTER TABLE `realtimeSolutionSubscribtions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`,`domain`),
  ADD KEY `realtimeSolutionSubscribtionsFK1` (`domain`);

--
-- Indexes for table `trainingEnrollement`
--
ALTER TABLE `trainingEnrollement`
  ADD PRIMARY KEY (`id`),
  ADD KEY `trainingEnrollementFK1` (`platform`),
  ADD KEY `trainingEnrollementFK2` (`package`);

--
-- Indexes for table `userChannels`
--
ALTER TABLE `userChannels`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `userID` (`userID`,`channel`,`channelID`),
  ADD KEY `userChannelsFK1` (`channel`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appDevRequests`
--
ALTER TABLE `appDevRequests`
  MODIFY `id` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `bookDev`
--
ALTER TABLE `bookDev`
  MODIFY `id` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `channels`
--
ALTER TABLE `channels`
  MODIFY `id` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `domains`
--
ALTER TABLE `domains`
  MODIFY `id` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `platforms`
--
ALTER TABLE `platforms`
  MODIFY `id` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `realtimeSolutionSubscribtions`
--
ALTER TABLE `realtimeSolutionSubscribtions`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `trainingEnrollement`
--
ALTER TABLE `trainingEnrollement`
  MODIFY `id` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `userChannels`
--
ALTER TABLE `userChannels`
  MODIFY `id` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appDevRequests`
--
ALTER TABLE `appDevRequests`
  ADD CONSTRAINT `appDevRequestsFK` FOREIGN KEY (`platform`) REFERENCES `platforms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `realtimeSolutionSubscribtions`
--
ALTER TABLE `realtimeSolutionSubscribtions`
  ADD CONSTRAINT `realtimeSolutionSubscribtionsFK1` FOREIGN KEY (`domain`) REFERENCES `domains` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `trainingEnrollement`
--
ALTER TABLE `trainingEnrollement`
  ADD CONSTRAINT `trainingEnrollementFK1` FOREIGN KEY (`platform`) REFERENCES `platforms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `trainingEnrollementFK2` FOREIGN KEY (`package`) REFERENCES `packages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `userChannels`
--
ALTER TABLE `userChannels`
  ADD CONSTRAINT `userChannelsFK1` FOREIGN KEY (`channel`) REFERENCES `channels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `userChannelsFK2` FOREIGN KEY (`userID`) REFERENCES `realtimeSolutionSubscribtions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
