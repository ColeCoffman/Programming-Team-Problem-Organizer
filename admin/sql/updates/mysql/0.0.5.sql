-- --------------------------------------------------------

--
-- File name must be incremented for Joomla to acknowledge the updated file
--

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
/*SET AUTOCOMMIT = 0;
START TRANSACTION;*/
SET time_zone = "+00:00";

-- --------------------------------------------------------

--
-- Table structure for table `Category`
--

CREATE TABLE IF NOT EXISTS `Category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `History`
--

CREATE TABLE IF NOT EXISTS `History` (
  `id` int(11) NOT NULL,
  `problem_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Problem`
--

CREATE TABLE IF NOT EXISTS `Problem` (
  `id` int(11) NOT NULL,
  `set_id` int(11) DEFAULT NULL,
  `source_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `difficulty` int(11) DEFAULT NULL,
  `category` smallint(6) DEFAULT NULL,
  `pdf_path` varchar(255) DEFAULT NULL,
  `zip_url` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ProblemSet`
--

CREATE TABLE IF NOT EXISTS `ProblemSet` (
  `set_id` int(11) DEFAULT NULL,
  `problem_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Sets`
--

CREATE TABLE IF NOT EXISTS `Sets` (
  `id` int(11) NOT NULL,
  `team_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Source`
--

CREATE TABLE IF NOT EXISTS `Source` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `region` varchar(255) DEFAULT NULL,
  `super_region` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Team`
--

CREATE TABLE IF NOT EXISTS `Team` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `UserHistory`
--

CREATE TABLE IF NOT EXISTS `UserHistory` (
  `history_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE IF NOT EXISTS `Users` (
  `id` int(11) NOT NULL,
  `team_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `permissions` int(11) DEFAULT NULL,
  `user` varchar(255) DEFAULT NULL,
  `pass_hash` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
