-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 15, 2023 at 01:25 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `crimes-api`
--
DROP DATABASE IF EXISTS `crimes-api`;
CREATE DATABASE `crimes-api`;
Use `crimes-api`;
-- --------------------------------------------------------

--
-- Table structure for table `cases`
--

CREATE TABLE `cases` (
  `case_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `date_reported` datetime NOT NULL DEFAULT current_timestamp(),
  `misdemeanor` tinyint(1) NOT NULL,
  `crime_sceneID` int(11) NOT NULL,
  `investigator_id` int(11) NOT NULL,
  `court_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cases_offenses`
--

CREATE TABLE `cases_offenses` (
  `case_id` int(11) NOT NULL,
  `offense_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cases_victims`
--

CREATE TABLE `cases_victims` (
  `case_id` int(11) NOT NULL,
  `victim_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `courts`
--

CREATE TABLE `courts` (
  `court_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `time` date NOT NULL,
  `address_id` int(11) NOT NULL,
  `judge_id` int(11) NOT NULL,
  `verdict_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `court_addresses`
--

CREATE TABLE `court_addresses` (
  `address_id` int(11) NOT NULL,
  `city` varchar(100) NOT NULL,
  `street` varchar(100) NOT NULL,
  `building_#` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `crime_scenes`
--

CREATE TABLE `crime_scenes` (
  `crime_sceneID` int(11) NOT NULL,
  `province` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `street` varchar(100) NOT NULL,
  `building_number` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `defendants`
--

CREATE TABLE `defendants` (
  `defendant_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `age` int(4) NOT NULL,
  `specialization` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `investigators`
--

CREATE TABLE `investigators` (
  `investigator_id` int(11) NOT NULL,
  `badge_number` varchar(80) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `rank` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `judges`
--

CREATE TABLE `judges` (
  `judge_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `age` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `offenders`
--

CREATE TABLE `offenders` (
  `offender_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `age` int(4) NOT NULL,
  `marital_status` enum('single','married') NOT NULL,
  `arrest_date` date NOT NULL,
  `arrest_timestamp` time NOT NULL,
  `defendant_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `offender_details`
--

CREATE TABLE `offender_details` (
  `offender_id` int(11) NOT NULL,
  `case_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `offenses`
--

CREATE TABLE `offenses` (
  `offense_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `classification` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prosecutors`
--

CREATE TABLE `prosecutors` (
  `prosecutor_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `age` int(7) DEFAULT NULL,
  `specialization` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `verdicts`
--

CREATE TABLE `verdicts` (
  `verdict_id` int(11) NOT NULL,
  `name` int(100) NOT NULL,
  `description` text NOT NULL,
  `fine` int(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `victims`
--

CREATE TABLE `victims` (
  `victim_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `age` int(7) NOT NULL,
  `marital_status` enum('single','married','divorced','') NOT NULL,
  `prosecutor_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cases`
--
ALTER TABLE `cases`
  ADD PRIMARY KEY (`case_id`),
  ADD KEY `cases_crimescenes_index` (`crime_sceneID`),
  ADD KEY `cases_investigators_index` (`investigator_id`),
  ADD KEY `cases_court_index` (`court_id`);

--
-- Indexes for table `cases_offenses`
--
ALTER TABLE `cases_offenses`
  ADD KEY `cases_offenses` (`case_id`,`offense_id`),
  ADD KEY `offense_id` (`offense_id`);

--
-- Indexes for table `cases_victims`
--
ALTER TABLE `cases_victims`
  ADD KEY `cases_victims_composite` (`case_id`,`victim_id`),
  ADD KEY `victim_id` (`victim_id`);

--
-- Indexes for table `courts`
--
ALTER TABLE `courts`
  ADD PRIMARY KEY (`court_id`),
  ADD KEY `courts_judge_id_fk` (`judge_id`),
  ADD KEY `courts_address_if_fk` (`address_id`),
  ADD KEY `courts_verdict_id_fk` (`verdict_id`);

--
-- Indexes for table `court_addresses`
--
ALTER TABLE `court_addresses`
  ADD PRIMARY KEY (`address_id`);

--
-- Indexes for table `crime_scenes`
--
ALTER TABLE `crime_scenes`
  ADD PRIMARY KEY (`crime_sceneID`);

--
-- Indexes for table `defendants`
--
ALTER TABLE `defendants`
  ADD PRIMARY KEY (`defendant_id`);

--
-- Indexes for table `investigators`
--
ALTER TABLE `investigators`
  ADD PRIMARY KEY (`investigator_id`);

--
-- Indexes for table `judges`
--
ALTER TABLE `judges`
  ADD PRIMARY KEY (`judge_id`);

--
-- Indexes for table `offenders`
--
ALTER TABLE `offenders`
  ADD PRIMARY KEY (`offender_id`),
  ADD KEY `offenders_defendant_id_fk` (`defendant_id`);

--
-- Indexes for table `offender_details`
--
ALTER TABLE `offender_details`
  ADD PRIMARY KEY (`offender_id`),
  ADD KEY `offender_detail_case_id_fk` (`case_id`);

--
-- Indexes for table `offenses`
--
ALTER TABLE `offenses`
  ADD PRIMARY KEY (`offense_id`);

--
-- Indexes for table `prosecutors`
--
ALTER TABLE `prosecutors`
  ADD PRIMARY KEY (`prosecutor_id`);

--
-- Indexes for table `verdicts`
--
ALTER TABLE `verdicts`
  ADD PRIMARY KEY (`verdict_id`);

--
-- Indexes for table `victims`
--
ALTER TABLE `victims`
  ADD PRIMARY KEY (`victim_id`),
  ADD KEY `victims_prosecutors_index` (`prosecutor_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cases`
--
ALTER TABLE `cases`
  MODIFY `case_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `courts`
--
ALTER TABLE `courts`
  MODIFY `court_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `court_addresses`
--
ALTER TABLE `court_addresses`
  MODIFY `address_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `crime_scenes`
--
ALTER TABLE `crime_scenes`
  MODIFY `crime_sceneID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `defendants`
--
ALTER TABLE `defendants`
  MODIFY `defendant_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `investigators`
--
ALTER TABLE `investigators`
  MODIFY `investigator_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `judges`
--
ALTER TABLE `judges`
  MODIFY `judge_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `offenders`
--
ALTER TABLE `offenders`
  MODIFY `offender_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `offenses`
--
ALTER TABLE `offenses`
  MODIFY `offense_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `prosecutors`
--
ALTER TABLE `prosecutors`
  MODIFY `prosecutor_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `verdicts`
--
ALTER TABLE `verdicts`
  MODIFY `verdict_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `victims`
--
ALTER TABLE `victims`
  MODIFY `victim_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cases`
--
ALTER TABLE `cases`
  ADD CONSTRAINT `cases_crime_scenes_fk` FOREIGN KEY (`crime_sceneID`) REFERENCES `crime_scenes` (`crime_sceneID`) ON DELETE CASCADE,
  ADD CONSTRAINT `cases_investigator_id_fk` FOREIGN KEY (`investigator_id`) REFERENCES `investigators` (`investigator_id`) ON DELETE CASCADE;

--
-- Constraints for table `cases_offenses`
--
ALTER TABLE `cases_offenses`
  ADD CONSTRAINT `case_id_index` FOREIGN KEY (`case_id`) REFERENCES `cases` (`case_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `offense_id` FOREIGN KEY (`offense_id`) REFERENCES `offenses` (`offense_id`) ON DELETE CASCADE;

--
-- Constraints for table `cases_victims`
--
ALTER TABLE `cases_victims`
  ADD CONSTRAINT `case_id` FOREIGN KEY (`case_id`) REFERENCES `cases` (`case_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `victim_id` FOREIGN KEY (`victim_id`) REFERENCES `victims` (`victim_id`) ON DELETE CASCADE;

--
-- Constraints for table `courts`
--
ALTER TABLE `courts`
  ADD CONSTRAINT `courts_address_if_fk` FOREIGN KEY (`address_id`) REFERENCES `court_addresses` (`address_id`),
  ADD CONSTRAINT `courts_case_id_fk` FOREIGN KEY (`court_id`) REFERENCES `cases` (`court_id`),
  ADD CONSTRAINT `courts_judge_id_fk` FOREIGN KEY (`judge_id`) REFERENCES `judges` (`judge_id`),
  ADD CONSTRAINT `courts_verdict_id_fk` FOREIGN KEY (`verdict_id`) REFERENCES `verdicts` (`verdict_id`);

--
-- Constraints for table `offenders`
--
ALTER TABLE `offenders`
  ADD CONSTRAINT `offenders_defendant_id_fk` FOREIGN KEY (`defendant_id`) REFERENCES `defendants` (`defendant_id`);

--
-- Constraints for table `offender_details`
--
ALTER TABLE `offender_details`
  ADD CONSTRAINT `offender_detail_case_id_fk` FOREIGN KEY (`case_id`) REFERENCES `cases` (`case_id`),
  ADD CONSTRAINT `offender_details_offender_id_fk` FOREIGN KEY (`offender_id`) REFERENCES `offenders` (`offender_id`);

--
-- Constraints for table `victims`
--
ALTER TABLE `victims`
  ADD CONSTRAINT `victims_prosecutors_index` FOREIGN KEY (`prosecutor_id`) REFERENCES `prosecutors` (`prosecutor_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
