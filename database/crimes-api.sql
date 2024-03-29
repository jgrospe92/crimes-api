-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 07, 2023 at 08:10 PM
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
CREATE DATABASE IF NOT EXISTS `crimes-api` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `crimes-api`;

-- --------------------------------------------------------

--
-- Table structure for table `cases`
--

DROP TABLE IF EXISTS `cases`;
CREATE TABLE `cases` (
  `case_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `date_reported` datetime NOT NULL DEFAULT current_timestamp(),
  `misdemeanor` tinyint(1) NOT NULL,
  `crime_sceneID` int(11) NOT NULL,
  `investigator_id` int(11) NOT NULL,
  `court_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cases`
--

INSERT INTO `cases` (`case_id`, `description`, `date_reported`, `misdemeanor`, `crime_sceneID`, `investigator_id`, `court_id`) VALUES
(1, 'On April 6th, 2004, Jeremy Elbertson committed arson at the National Park of Las Vegas. Later found within Toronto living as a streamer on the streaming platform \'Twitch\', he has been arrested and will be trialed in court.', '2020-01-30 13:04:18', 0, 2, 2, 1),
(2, 'On September 21st, 2012, Rick Moranis was arrested for raping 25 year old Mario Mario in New York, where the victim was walking home with a sandwich he had just bought from a bodega.', '2014-09-23 10:39:16', 0, 3, 5, 5),
(3, 'On April 1st, 2022, Andrew Tate murdered his best friend LeRock Johnson in an April Fools prank gone wrong.', '2023-03-17 00:26:51', 0, 1, 2, 3),
(4, 'On November 9th, 2009, Cirno Fairy murdered Okuyasu Nijimura and Josuke Higashikata by freezing them to death in an industrial freezer.', '2009-11-10 10:56:24', 0, 5, 4, 3),
(5, 'On February 14th, 2023, Doug Bowser was found dead in his house with a parasol in the back of his head. His murderer: Peach Toadstool.', '2023-02-14 22:08:49', 0, 4, 3, 2),
(6, 'Hit and run accident', '2023-04-04 10:00:00', 1, 1, 2, 1),
(7, 'On April 6th, 2004, Jeremy Elbertson committed arson at the National Park of Las Vegas. Later found within Toronto living as a streamer on the streaming platform \'Twitch\', he has been arrested and will be trialed in court.', '2020-01-30 13:04:18', 0, 2, 5, 1),
(8, 'On arpil 06, there was a freezing rain....', '2023-04-06 08:04:18', 0, 2, 5, 1),
(9, 'On arpil 06, there was a freezing rain....', '2023-04-06 08:04:18', 0, 2, 5, 1),
(10, 'On arpil 06, there was a freezing rain....', '2023-04-06 08:04:18', 0, 2, 5, 1),
(11, 'On arpil 06, there was a freezing rain....', '2023-04-06 08:04:18', 0, 2, 5, 1),
(12, 'Updating case 12 for the seoncd time', '2023-04-06 08:04:18', 1, 2, 3, 2),
(13, 'Test post cases....', '2023-04-06 08:04:18', 0, 2, 5, 3),
(14, 'Test post cases....', '2023-04-06 08:04:18', 0, 2, 5, 3),
(15, 'Freezing rain in montreal!! ....', '2023-04-06 08:04:18', 1, 2, 5, 2);

-- --------------------------------------------------------

--
-- Table structure for table `cases_offenses`
--

DROP TABLE IF EXISTS `cases_offenses`;
CREATE TABLE `cases_offenses` (
  `case_id` int(11) NOT NULL,
  `offense_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cases_offenses`
--

INSERT INTO `cases_offenses` (`case_id`, `offense_id`) VALUES
(1, 1),
(1, 6),
(2, 3),
(2, 5),
(2, 5),
(3, 5),
(4, 5),
(5, 5),
(11, 6),
(12, 5),
(13, 2),
(14, 5),
(15, 2),
(15, 6);

-- --------------------------------------------------------

--
-- Table structure for table `cases_victims`
--

DROP TABLE IF EXISTS `cases_victims`;
CREATE TABLE `cases_victims` (
  `case_id` int(11) NOT NULL,
  `victim_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cases_victims`
--

INSERT INTO `cases_victims` (`case_id`, `victim_id`) VALUES
(1, 1),
(1, 5),
(2, 4),
(3, 3),
(4, 1),
(5, 5),
(13, 4),
(14, 4),
(15, 1);

-- --------------------------------------------------------

--
-- Table structure for table `courts`
--

DROP TABLE IF EXISTS `courts`;
CREATE TABLE `courts` (
  `court_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `time` time DEFAULT NULL,
  `address_id` int(11) NOT NULL,
  `judge_id` int(11) NOT NULL,
  `verdict_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courts`
--

INSERT INTO `courts` (`court_id`, `name`, `date`, `time`, `address_id`, `judge_id`, `verdict_id`) VALUES
(1, 'Toronto Courthouse', '2023-04-15', '06:11:14', 1, 1, 1),
(2, 'Court of Appeal', '2023-04-17', '11:15:02', 2, 2, 2),
(3, 'Supreme Court of Canada', '2023-04-18', '17:08:13', 3, 3, 3),
(4, 'Provincial Court of Alberta', '2023-04-19', '07:06:00', 4, 4, 4),
(5, 'Courts Administration Service', '2023-04-20', '15:22:00', 5, 5, 5);

-- --------------------------------------------------------

--
-- Table structure for table `court_addresses`
--

DROP TABLE IF EXISTS `court_addresses`;
CREATE TABLE `court_addresses` (
  `address_id` int(11) NOT NULL,
  `city` varchar(100) NOT NULL,
  `street` varchar(100) NOT NULL,
  `postal_code` varchar(15) NOT NULL,
  `building_num` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `court_addresses`
--

INSERT INTO `court_addresses` (`address_id`, `city`, `street`, `postal_code`, `building_num`) VALUES
(1, 'Toronto', ' University Ave', 'M5G 1T3', '361'),
(2, 'Vancouver', 'Smithe St', 'V6Z 2E1', '800'),
(3, 'Ottawa', 'Wellington St', 'K1A 0J1', '301 '),
(4, 'Calgary', '5 St SW', 'T2P 5P7', '601'),
(5, 'Montreal', 'Mcgill St.', 'H2Y 3Z7', '30');

-- --------------------------------------------------------

--
-- Table structure for table `crime_scenes`
--

DROP TABLE IF EXISTS `crime_scenes`;
CREATE TABLE `crime_scenes` (
  `crime_sceneID` int(11) NOT NULL,
  `province` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `street` varchar(100) NOT NULL,
  `building_number` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `crime_scenes`
--

INSERT INTO `crime_scenes` (`crime_sceneID`, `province`, `city`, `street`, `building_number`) VALUES
(1, 'Quebec', 'Trois-Riviere', 'Idontknow Street', '0923'),
(2, 'Ontario', 'Kingston', 'Green Dolphin Street', '0012'),
(3, 'New York', 'New York', 'Time Square', 'Outside'),
(4, 'New Mexico', 'Albuquerque', 'Negra Arroyo Lane', '308'),
(5, 'Washington', 'Washington D.C', 'Pennsylvania Avenue NW', '1600');

-- --------------------------------------------------------

--
-- Table structure for table `defendants`
--

DROP TABLE IF EXISTS `defendants`;
CREATE TABLE `defendants` (
  `defendant_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `age` int(4) NOT NULL,
  `specialization` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `defendants`
--

INSERT INTO `defendants` (`defendant_id`, `first_name`, `last_name`, `age`, `specialization`) VALUES
(1, 'John', 'Silva', 31, 'Criminal Law'),
(2, 'Maria', 'Doe', 27, 'Family Law'),
(3, 'Michael', 'Smith', 45, 'Corporate Law'),
(4, 'Emily', 'Browning', 38, 'Immigration Law'),
(5, 'David', 'Bowsmitchdt', 47, 'Environmental Law');

-- --------------------------------------------------------

--
-- Table structure for table `investigators`
--

DROP TABLE IF EXISTS `investigators`;
CREATE TABLE `investigators` (
  `investigator_id` int(11) NOT NULL,
  `badge_number` varchar(80) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `rank` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `investigators`
--

INSERT INTO `investigators` (`investigator_id`, `badge_number`, `first_name`, `last_name`, `rank`) VALUES
(1, '1585', 'Hank', 'Shrader', 'Certified Legal Investigator'),
(2, '1802', 'Jessie', 'James', 'Certified Forensic Investigator'),
(3, '6521', 'Gustavo', 'Fring', 'Certified Fraud Examiner'),
(4, '0421', 'Cole', 'Phelps', 'Board Certified Investigator'),
(5, '9091', 'Chad Jeff', 'Bezos', 'Certified Forensic Investigator'),
(9, '1535', 'Hank', 'Shrader', 'Board Certified Investigator'),
(10, '15335', 'Hank', 'Shrader', 'Certified Legal Investigator'),
(11, '3453', 'Hank', 'Shrader', 'Certified Legal Investigator'),
(12, '1255', 'Hank', 'Shrader', 'Chief of Police'),
(13, '13255', 'Hank', 'Shrader', 'Chief of Police'),
(14, '2125', 'Dwight Update', 'Schrute', 'Certified Fraud Examiner'),
(21, '2611', 'Dwight', 'Schrute', 'Certified Legal Investigator');

-- --------------------------------------------------------

--
-- Table structure for table `judges`
--

DROP TABLE IF EXISTS `judges`;
CREATE TABLE `judges` (
  `judge_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `age` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `judges`
--

INSERT INTO `judges` (`judge_id`, `first_name`, `last_name`, `age`) VALUES
(1, 'Jayce', 'Smith', 45),
(2, 'Leonard', 'Fisher', 50),
(3, 'David', 'Mitch', 55),
(4, 'Karen', 'Matheson', 48),
(5, 'Judy', 'Brown', 60);

-- --------------------------------------------------------

--
-- Table structure for table `offenders`
--

DROP TABLE IF EXISTS `offenders`;
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

--
-- Dumping data for table `offenders`
--

INSERT INTO `offenders` (`offender_id`, `first_name`, `last_name`, `age`, `marital_status`, `arrest_date`, `arrest_timestamp`, `defendant_id`) VALUES
(1, 'John', 'Bastista', 30, 'married', '2022-02-14', '21:04:56', 3),
(2, 'Mike', 'Gustos', 41, 'single', '0000-00-00', '16:56:57', 3),
(3, 'Jeremy', 'Elbertson', 36, 'single', '2020-01-30', '12:07:57', 3),
(4, 'Andrew', 'Tate', 41, 'married', '2022-04-01', '16:56:57', 1),
(5, 'Peach', 'Toadstool', 24, 'married', '2023-02-14', '23:56:57', 4),
(6, 'Rick', 'Moranis', 54, 'married', '2014-09-24', '15:02:01', 5),
(7, 'Cirno', 'Fairy', 60, 'single', '2009-11-09', '21:04:56', 3);

-- --------------------------------------------------------

--
-- Table structure for table `offender_details`
--

DROP TABLE IF EXISTS `offender_details`;
CREATE TABLE `offender_details` (
  `offender_id` int(11) NOT NULL,
  `case_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `offender_details`
--

INSERT INTO `offender_details` (`offender_id`, `case_id`) VALUES
(3, 1),
(4, 3),
(5, 5),
(6, 2),
(7, 4),
(3, 14),
(7, 15),
(4, 1),
(2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `offenses`
--

DROP TABLE IF EXISTS `offenses`;
CREATE TABLE `offenses` (
  `offense_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `classification` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `offenses`
--

INSERT INTO `offenses` (`offense_id`, `name`, `description`, `classification`) VALUES
(1, 'Arson', 'Felonies are serious crimes that are usually punishable by more than one year in prison. Examples of felonies include murder, rape, burglary, and drug trafficking.', 'Felony'),
(2, 'Domestic Violence', 'Misdemeanors are less serious crimes that are usually punishable by less than one year in jail. Examples of misdemeanors include petty theft, disorderly conduct, and minor drug offenses.', 'Misdemeanor'),
(3, 'Inseider trading', 'White-collar crimes are nonviolent offenses that are typically committed in a professional or business setting. Examples of white-collar crimes include fraud, embezzlement, and insider trading.', 'White-collar crime'),
(4, 'Homecide', 'Violent crimes involve the use of force or threat of force against another person. Examples of violent crimes include assault, battery, and homicide.', 'Violent crime'),
(5, 'Roberry', 'Property crimes involve the taking or destruction of another person\'s property. Examples of property crimes include theft, robbery, and arson.', 'Property crime'),
(6, 'Phishing', 'Cybercrimes are crimes that are committed using a computer or the internet. Examples of cybercrimes include hacking, identity theft, and online fraud', 'Cyber-crime'),
(7, 'Drug trafficking', 'Drug crimes involve the possession, sale, or distribution of illegal drugs. Examples of drug crimes include drug possession, drug trafficking, and drug manufacturing.', 'Drug crime'),
(8, 'Cryto Scam', 'A crypto scam is a fraudulent scheme that deceives people into investing in fake or illegitimate cryptocurrency projects with promises of high returns. Scammers often disappear with investors\' money, leaving them with no way to recover their funds, updated', 'Cyber-crime');

-- --------------------------------------------------------

--
-- Table structure for table `prosecutors`
--

DROP TABLE IF EXISTS `prosecutors`;
CREATE TABLE `prosecutors` (
  `prosecutor_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `age` int(7) DEFAULT NULL,
  `specialization` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prosecutors`
--

INSERT INTO `prosecutors` (`prosecutor_id`, `first_name`, `last_name`, `age`, `specialization`) VALUES
(1, 'LeBronze', 'Age', 44, 'Criminal Law'),
(2, 'LeBomb', 'Bay', 61, 'Admiralty Law'),
(3, 'Taughtthe', 'Laws', 35, 'First Amendment Law'),
(4, 'Minster', 'Beech', 29, 'Business Law'),
(5, 'James Morgan', 'McGill', 48, 'Criminal Law');

-- --------------------------------------------------------

--
-- Table structure for table `verdicts`
--

DROP TABLE IF EXISTS `verdicts`;
CREATE TABLE `verdicts` (
  `verdict_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `sentence` int(11) DEFAULT NULL,
  `fine` int(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `verdicts`
--

INSERT INTO `verdicts` (`verdict_id`, `name`, `description`, `sentence`, `fine`) VALUES
(1, 'Guilty', 'The defendant has been found guilty of the crime.', 25, 1000),
(2, 'Not Guilty', 'The defendant has been found not guilty of the crime.', 0, 0),
(3, 'Partially Guilty', 'The defendant has been found partially guilty of the crime.', 5, 500),
(4, 'Innocent', 'The defendant has been proven to be innocent of the crime.', 0, 0),
(5, 'Mistrial', 'The trial has ended without a verdict due to a mistrial.', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `victims`
--

DROP TABLE IF EXISTS `victims`;
CREATE TABLE `victims` (
  `victim_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `age` int(7) NOT NULL,
  `marital_status` enum('single','married','divorced','') NOT NULL,
  `prosecutor_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `victims`
--

INSERT INTO `victims` (`victim_id`, `first_name`, `last_name`, `age`, `marital_status`, `prosecutor_id`) VALUES
(1, 'Josuke', 'Higashikata', 16, 'single', 1),
(2, 'Okuyasu', 'Nijimura', 16, 'single', NULL),
(3, 'LeRock', 'Johnson', 57, 'married', 4),
(4, 'Mario', 'Mario', 25, 'single', 5),
(5, 'Doug', 'Bowser Koopa', 41, 'divorced', 3),
(6, 'No', 'One', 0, 'single', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ws_log`
--

DROP TABLE IF EXISTS `ws_log`;
CREATE TABLE `ws_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `email` varchar(150) NOT NULL,
  `user_action` varchar(255) NOT NULL,
  `logged_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `user_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ws_users`
--

DROP TABLE IF EXISTS `ws_users`;
CREATE TABLE `ws_users` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ws_users`
--

INSERT INTO `ws_users` (`user_id`, `first_name`, `last_name`, `email`, `password`, `created_at`, `role`) VALUES
(15, 'Test', 'Me', 'jgrospe@gmail.com', '$2y$15$bBnvKQpN40E49EIMwkK7yuymU/LKQ3lLr1TluojnHY3YgJrsU02da', '2023-05-07 06:05:54', 'admin');

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
  ADD PRIMARY KEY (`investigator_id`),
  ADD UNIQUE KEY `badge_number` (`badge_number`);

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
  ADD KEY `offender_detail_case_id_fk` (`case_id`),
  ADD KEY `offender_index` (`offender_id`) USING BTREE;

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
-- Indexes for table `ws_log`
--
ALTER TABLE `ws_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ws_log_user_id_PK` (`user_id`);

--
-- Indexes for table `ws_users`
--
ALTER TABLE `ws_users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cases`
--
ALTER TABLE `cases`
  MODIFY `case_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `courts`
--
ALTER TABLE `courts`
  MODIFY `court_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `court_addresses`
--
ALTER TABLE `court_addresses`
  MODIFY `address_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `crime_scenes`
--
ALTER TABLE `crime_scenes`
  MODIFY `crime_sceneID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `defendants`
--
ALTER TABLE `defendants`
  MODIFY `defendant_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `investigators`
--
ALTER TABLE `investigators`
  MODIFY `investigator_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `judges`
--
ALTER TABLE `judges`
  MODIFY `judge_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `offenders`
--
ALTER TABLE `offenders`
  MODIFY `offender_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `offenses`
--
ALTER TABLE `offenses`
  MODIFY `offense_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `prosecutors`
--
ALTER TABLE `prosecutors`
  MODIFY `prosecutor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `verdicts`
--
ALTER TABLE `verdicts`
  MODIFY `verdict_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `victims`
--
ALTER TABLE `victims`
  MODIFY `victim_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `ws_log`
--
ALTER TABLE `ws_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ws_users`
--
ALTER TABLE `ws_users`
  MODIFY `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cases`
--
ALTER TABLE `cases`
  ADD CONSTRAINT `cases_court_id_fk` FOREIGN KEY (`court_id`) REFERENCES `courts` (`court_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cases_crime_scenes_fk` FOREIGN KEY (`crime_sceneID`) REFERENCES `crime_scenes` (`crime_sceneID`) ON DELETE CASCADE,
  ADD CONSTRAINT `cases_investigator_id_fk` FOREIGN KEY (`investigator_id`) REFERENCES `investigators` (`investigator_id`) ON DELETE CASCADE;

--
-- Constraints for table `cases_offenses`
--
ALTER TABLE `cases_offenses`
  ADD CONSTRAINT `case_id_index` FOREIGN KEY (`case_id`) REFERENCES `cases` (`case_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `offense_id` FOREIGN KEY (`offense_id`) REFERENCES `offenses` (`offense_id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
  ADD CONSTRAINT `address_id_courts` FOREIGN KEY (`address_id`) REFERENCES `court_addresses` (`address_id`),
  ADD CONSTRAINT `judge_id_courts` FOREIGN KEY (`judge_id`) REFERENCES `judges` (`judge_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `verdict_id_courts` FOREIGN KEY (`verdict_id`) REFERENCES `verdicts` (`verdict_id`) ON DELETE CASCADE;

--
-- Constraints for table `offenders`
--
ALTER TABLE `offenders`
  ADD CONSTRAINT `offenders_defendant_id_fk` FOREIGN KEY (`defendant_id`) REFERENCES `defendants` (`defendant_id`) ON DELETE CASCADE;

--
-- Constraints for table `offender_details`
--
ALTER TABLE `offender_details`
  ADD CONSTRAINT `case_id_details` FOREIGN KEY (`case_id`) REFERENCES `cases` (`case_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `offender_id_details` FOREIGN KEY (`offender_id`) REFERENCES `offenders` (`offender_id`) ON DELETE CASCADE;

--
-- Constraints for table `victims`
--
ALTER TABLE `victims`
  ADD CONSTRAINT `prosecuter_id_victims` FOREIGN KEY (`prosecutor_id`) REFERENCES `prosecutors` (`prosecutor_id`) ON DELETE CASCADE;

--
-- Constraints for table `ws_log`
--
ALTER TABLE `ws_log`
  ADD CONSTRAINT `ws_log_user_id_PK` FOREIGN KEY (`user_id`) REFERENCES `ws_users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
