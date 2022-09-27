-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 05, 2022 at 03:01 PM
-- Server version: 10.3.35-MariaDB-log-cll-lve
-- PHP Version: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
-- SET AUTOCOMMIT = 0;
-- START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ptpogxcd_PTPO`
--

-- --------------------------------------------------------

--
-- Table structure for table `com_catalogsystem_category`
--

CREATE TABLE `com_catalogsystem_category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `com_catalogsystem_category`
--

INSERT INTO `com_catalogsystem_category` (`id`, `name`) VALUES
(1, 'Graph Theory'),
(2, 'Arrays'),
(3, 'Linked Lists'),
(4, 'Recursion');

-- --------------------------------------------------------

--
-- Table structure for table `com_catalogsystem_history`
--

CREATE TABLE `com_catalogsystem_history` (
  `id` int(11) NOT NULL,
  `problem_id` int(11) DEFAULT NULL,
  `team_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `com_catalogsystem_history`
--

INSERT INTO `com_catalogsystem_history` (`id`, `problem_id`, `team_id`, `date`) VALUES
(1, 1, 1, '2022-08-27'),
(2, 2, 2, '2022-08-28'),
(3, 2, 1, '2022-08-30'),
(4, 3, 1, '2022-09-02'),
(5, 4, 1, '2021-12-13');

-- --------------------------------------------------------

--
-- Table structure for table `com_catalogsystem_problem`
--

CREATE TABLE `com_catalogsystem_problem` (
  `id` int(11) NOT NULL,
  `source_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `difficulty` int(11) DEFAULT NULL,
  `pdf_link` varchar(255) DEFAULT NULL,
  `zip_link` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `com_catalogsystem_problem`
--

INSERT INTO `com_catalogsystem_problem` (`id`, `source_id`, `category_id`, `name`, `difficulty`, `pdf_link`, `zip_link`) VALUES
(1, 1, 1, 'Funny Pandas', 3, 'c:/pdf', 'c:/zip'),
(2, 3, 3, 'Musical Chairs', 5, 'c:/pdf2', 'c:/zip2'),
(3, 2, 2, 'Windmill Nodes', 4, 'c:/pdf3', 'c:/zip3'),
(4, 3, 4, 'Rubix Juggle', 8, 'c:/pdf3', 'c:/zip3'),
(5, 2, 3, 'Spaceship Tunes', 1, 'c:/pdf3', 'c:/zip3');

-- --------------------------------------------------------

--
-- Table structure for table `com_catalogsystem_problemset`
--

CREATE TABLE `com_catalogsystem_problemset` (
  `id` int(11) NOT NULL,
  `set_id` int(11) DEFAULT NULL,
  `problem_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `com_catalogsystem_problemset`
--

INSERT INTO `com_catalogsystem_problemset` (`id`, `set_id`, `problem_id`) VALUES
(1, 3, 1),
(2, 2, 2),
(3, 2, 3),
(4, 3, 4),
(5, 1, 4),
(6, 3, 5);

-- --------------------------------------------------------

--
-- Table structure for table `com_catalogsystem_set`
--

CREATE TABLE `com_catalogsystem_set` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `zip_link` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `com_catalogsystem_set`
--

INSERT INTO `com_catalogsystem_set` (`id`, `name`, `zip_link`) VALUES
(1, 'Long Problems', 'c:/zipset1'),
(2, 'Emma\'s Practice', 'c:/zipset2'),
(3, 'Charlie\'s Practice', 'c:/zipset3');

-- --------------------------------------------------------

--
-- Table structure for table `com_catalogsystem_source`
--

CREATE TABLE `com_catalogsystem_source` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `com_catalogsystem_source`
--

INSERT INTO `com_catalogsystem_source` (`id`, `name`) VALUES
(1, 'New York 2022'),
(2, 'Orlando 2021'),
(3, 'Washington DC 2022');

-- --------------------------------------------------------

--
-- Table structure for table `com_catalogsystem_team`
--

CREATE TABLE `com_catalogsystem_team` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------
--
-- Indexes for dumped tables
--

--
-- Indexes for table `com_catalogsystem_category`
--
ALTER TABLE `com_catalogsystem_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `com_catalogsystem_history`
--
ALTER TABLE `com_catalogsystem_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `problem_id` (`problem_id`);

--
-- Indexes for table `com_catalogsystem_problem`
--
ALTER TABLE `com_catalogsystem_problem`
  ADD PRIMARY KEY (`id`),
  ADD KEY `source_id` (`source_id`) USING BTREE;

--
-- Indexes for table `com_catalogsystem_problemset`
--
ALTER TABLE `com_catalogsystem_problemset`
  ADD PRIMARY KEY (`id`),
  ADD KEY `set_id` (`set_id`),
  ADD KEY `problem_id` (`problem_id`);

--
-- Indexes for table `com_catalogsystem_set`
--
ALTER TABLE `com_catalogsystem_set`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `com_catalogsystem_source`
--
ALTER TABLE `com_catalogsystem_source`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `com_catalogsystem_team`
--
ALTER TABLE `com_catalogsystem_team`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `com_catalogsystem_category`
--
ALTER TABLE `com_catalogsystem_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `com_catalogsystem_history`
--
ALTER TABLE `com_catalogsystem_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `com_catalogsystem_problem`
--
ALTER TABLE `com_catalogsystem_problem`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `com_catalogsystem_problemset`
--
ALTER TABLE `com_catalogsystem_problemset`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `com_catalogsystem_set`
--
ALTER TABLE `com_catalogsystem_set`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `com_catalogsystem_source`
--
ALTER TABLE `com_catalogsystem_source`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `com_catalogsystem_team`
--
ALTER TABLE `com_catalogsystem_team`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

-- --------------------------------------------------------
--
-- Triggers for Database
--

/* Error: "This command is not supported in the prepared statement protocol yet"
CREATE TRIGGER `cleanUpHistory`
    AFTER DELETE ON `com_catalogsystem_problem`
    FOR EACH ROW DELETE FROM com_catalogsystem_history
    WHERE problem_id = OLD.id;

CREATE TRIGGER `cleanUpProblemSet`
    AFTER DELETE ON `com_catalogsystem_problem`
    FOR EACH ROW DELETE FROM com_catalogsystem_problemset
    WHERE problem_id = OLD.id;
*/

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
