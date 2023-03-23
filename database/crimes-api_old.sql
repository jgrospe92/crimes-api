-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 23, 2023 at 03:26 PM
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

--
-- Dumping data for table `cases`
--

INSERT INTO `cases` (`case_id`, `description`, `date_reported`, `misdemeanor`, `crime_sceneID`, `investigator_id`, `court_id`) VALUES
(1, 'On April 6th, 2004, Jeremy Elbertson committed arson at the National Park of Las Vegas. Later found within Toronto living as a streamer on the streaming platform \'Twitch\', he has been arrested and will be trialed in court.', '2020-01-30 13:04:18', 0, 2, 1, 1),
(2, 'On September 21st, 2012, Rick Moranis was arrested for raping 25 year old Mario Mario in New York, where the victim was walking home with a sandwich he had just bought from a bodega.', '2014-09-23 10:39:16', 0, 3, 5, 5),
(3, 'On April 1st, 2022, Andrew Tate murdered his best friend LeRock Johnson in an April Fools prank gone wrong.', '2023-03-17 00:26:51', 0, 1, 2, 3),
(4, 'On November 9th, 2009, Cirno Fairy murdered Okuyasu Nijimura and Josuke Higashikata by freezing them to death in an industrial freezer.', '2009-11-10 10:56:24', 0, 5, 4, 3),
(5, 'On February 14th, 2023, Doug Bowser was found dead in his house with a parasol in the back of his head. His murderer: Peach Toadstool.', '2023-02-14 22:08:49', 0, 4, 3, 2);

-- --------------------------------------------------------

--
-- Table structure for table `cases_offenses`
--

CREATE TABLE `cases_offenses` (
  `case_id` int(11) NOT NULL,
  `offense_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cases_offenses`
--

INSERT INTO `cases_offenses` (`case_id`, `offense_id`) VALUES
(1, 1),
(2, 3),
(3, 5),
(4, 5),
(5, 5);

-- --------------------------------------------------------

--
-- Table structure for table `cases_victims`
--

CREATE TABLE `cases_victims` (
  `case_id` int(11) NOT NULL,
  `victim_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cases_victims`
--

INSERT INTO `cases_victims` (`case_id`, `victim_id`) VALUES
(1, 6),
(2, 4),
(3, 3),
(4, 1),
(5, 5);

-- --------------------------------------------------------

--
-- Table structure for table `courts`
--

CREATE TABLE `courts` (
  `court_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `address_id` int(11) NOT NULL,
  `judge_id` int(11) NOT NULL,
  `verdict_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courts`
--

INSERT INTO `courts` (`court_id`, `name`, `date`, `time`, `address_id`, `judge_id`, `verdict_id`) VALUES
(1, 'Toronto Courthouse', '2023-04-15', '06:00:00', 1, 1, 1),
(2, 'Court of Appeal', '2023-04-17', '11:00:00', 2, 2, 2),
(3, 'Supreme Court of Canada', '2023-04-18', '12:00:00', 3, 3, 3),
(4, 'Provincial Court of Alberta', '2023-04-19', '13:00:00', 4, 4, 4),
(5, 'Courts Administration Service', '2023-04-20', '14:00:00', 5, 5, 5);

-- --------------------------------------------------------

--
-- Table structure for table `court_addresses`
--

CREATE TABLE `court_addresses` (
  `address_id` int(11) NOT NULL,
  `city` varchar(100) NOT NULL,
  `street` varchar(100) NOT NULL,
  `postal_code` varchar(15) NOT NULL,
  `building_#` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courts_addresses`
--

INSERT INTO `court_addresses` (`address_id`, `city`, `street`, `postal_code`, `building_#`) VALUES
(1, 'Toronto', ' University Ave', 'M5G 1T3','361'),
(2, 'Vancouver', 'Smithe St', 'V6Z 2E1','800'),
(3, 'Ottawa', 'Wellington St', 'K1A 0J1','301 '),
(4, 'Calgary', '5 St SW', 'T2P 5P7','601'),
(5, 'Montreal', 'Mcgill St.', 'H2Y 3Z7','30');

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
(1, '1585', 'Hank', 'Shrader', 'Chief of Police'),
(2, '1802', 'Jessie', 'James', 'Police Captain'),
(3, '6521', 'Gustavo', 'Fring', 'Police Detective'),
(4, '0421', 'Cole', 'Phelps', 'Police Detective'),
(5, '9091', 'Chad Jeff', 'Bezos', 'Police Lieutenant');

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
(3, 'Jeremy', 'Elbertson', 36, 'single', '2020-01-30', '12:07:57', 3),
(4, 'Andrew', 'Tate', 41, 'married', '2022-04-01', '16:56:57', 1),
(5, 'Peach', 'Toadstool', 24, 'married', '2023-02-14', '23:56:57', 4),
(6, 'Rick', 'Moranis', 54, 'married', '2014-09-24', '15:02:01', 5),
(7, 'Cirno', 'Fairy', 60, 'single', '2009-11-09', '21:04:56', 3);

-- --------------------------------------------------------

--
-- Table structure for table `offender_details`
--

CREATE TABLE `offender_details` (
  `offender_id` int(11) NOT NULL,
  `case_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `offender_details`
--

INSERT INTO `offender_details` (`offender_id`, `case_id`) VALUES
(3, 1),
(6, 2),
(4, 3),
(7, 4),
(5, 5);

-- --------------------------------------------------------

--
-- Table structure for table `offenses`
--

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
(1, 'Arson', 'The willful and malicious burning of property or nature.', 'Felony'),
(2, 'Domestic Violence', 'Violent or abusive acts towards ones spouse, offspring or close relatives.', 'Misdemeanor'),
(3, 'Rape', 'Sexual acts towards a non-consenting victim.', 'Misdemeanor'),
(4, 'Fraud', 'Intentional deception to gain something of value, usually money.', 'Misdemeanor'),
(5, 'Homicide', 'The act of killing another person, whether intentionally or accidentally.', 'Felony');

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

CREATE TABLE `verdicts` (
  `verdict_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `sentence` varchar(150) DEFAULT NULL,
  `fine` int(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `verdicts`
--

INSERT INTO `verdicts` (`verdict_id`, `name`, `description`, `sentence`, `fine`) VALUES
(1, 'Guilty', 'The defendant has been found guilty of the crime.', '25 years in prison', 1000),
(2, 'Not Guilty', 'The defendant has been found not guilty of the crime.', 'None', 0),
(3, 'Partially Guilty', 'The defendant has been found partially guilty of the crime.', '5 years in prison', 500),
(4, 'Innocent', 'The defendant has been proven to be innocent of the crime.', 'None', 0),
(5, 'Mistrial', 'The trial has ended without a verdict due to a mistrial.', 'None', 0);

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
-- Dumping data for table `victims`
--

INSERT INTO `victims` (`victim_id`, `first_name`, `last_name`, `age`, `marital_status`, `prosecutor_id`) VALUES
(1, 'Josuke', 'Higashikata', 16, 'single', 1),
(2, 'Okuyasu', 'Nijimura', 16, 'single', NULL),
(3, 'LeRock', 'Johnson', 57, 'married', 4),
(4, 'Mario', 'Mario', 25, 'single', 5),
(5, 'Doug', 'Bowser Koopa', 41, 'divorced', 3),
(6, 'No', 'One', 0, 'single', NULL);

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
  MODIFY `case_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
  MODIFY `investigator_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
  MODIFY `offense_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
-- Constraints for table `offenders`
--
ALTER TABLE `offenders`
  ADD CONSTRAINT `offenders_defendant_id_fk` FOREIGN KEY (`defendant_id`) REFERENCES `defendants` (`defendant_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
