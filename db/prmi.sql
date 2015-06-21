-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 21, 2015 at 05:07 PM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `prmi`
--

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE IF NOT EXISTS `config` (
`id` int(11) NOT NULL,
  `site_name` varchar(64) NOT NULL,
  `site_desc` varchar(128) NOT NULL,
  `site_host` varchar(64) NOT NULL,
  `site_module` varchar(64) NOT NULL,
  `user_minlen` int(11) NOT NULL,
  `user_maxlen` int(11) NOT NULL,
  `user_complexity` enum('alphanumeric','alphanumeric with spacers','','') NOT NULL,
  `pass_minlen` int(11) NOT NULL,
  `pass_maxlen` int(11) NOT NULL,
  `pass_complexity` enum('simple','normal','strong','') NOT NULL,
  `pass_expiration` int(11) NOT NULL,
  `max_login_attempts` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`id`, `site_name`, `site_desc`, `site_host`, `site_module`, `user_minlen`, `user_maxlen`, `user_complexity`, `pass_minlen`, `pass_maxlen`, `pass_complexity`, `pass_expiration`, `max_login_attempts`) VALUES
(1, 'Puerto Rico Motorcycle Road Assistance Services', '', 'localhost', '/motorcycle-insurance/', 4, 24, 'alphanumeric with spacers', 4, 32, 'simple', 60, 4);

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE IF NOT EXISTS `login` (
  `id` int(11) unsigned NOT NULL DEFAULT '0',
  `user` varchar(32) NOT NULL,
  `pass` varchar(256) NOT NULL,
  `email` varchar(128) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `regdate` datetime NOT NULL,
  `lastvisit` datetime NOT NULL,
  `lastip` varchar(32) NOT NULL,
  `lastbrowser` varchar(128) NOT NULL,
  `ip` varchar(32) NOT NULL,
  `browser` varchar(128) NOT NULL,
  `session` varchar(256) NOT NULL,
  `expired` tinyint(1) NOT NULL DEFAULT '0',
  `disabled` tinyint(1) NOT NULL DEFAULT '0',
  `passchg` tinyint(1) NOT NULL DEFAULT '0',
  `passdate` datetime NOT NULL,
  `login_attempts` int(11) NOT NULL DEFAULT '0',
  `permissions` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`id`, `user`, `pass`, `email`, `role`, `regdate`, `lastvisit`, `lastip`, `lastbrowser`, `ip`, `browser`, `session`, `expired`, `disabled`, `passchg`, `passdate`, `login_attempts`, `permissions`) VALUES
(12854, 'admin', '$2y$11$putu03YGlI8t6eahu68ZOuKU1vHt./wSn7OGmlJ4VmlpVfKYQGxuG', 'q', 'admin', '2015-02-02 23:37:12', '2015-06-21 10:57:19', '::1', 'Mozilla/5.0 (Windows NT 6.3; WOW64; Trident/7.0; Touch; rv:11.0) like Gecko', '::1', 'Mozilla/5.0 (Windows NT 6.3; WOW64; Trident/7.0; Touch; rv:11.0) like Gecko', 'arkjp20sec0c3mflqodqvki8m7', 0, 0, 0, '2015-07-14 23:37:12', 0, 'all'),
(41873, 'dennis', '$2y$11$eR9EV3NbYR.qnG.dRWt0eOTXgawEOQnUfnsLrz8b9T3if0jaWNSde', 'q', 'user', '2015-02-02 23:37:44', '2015-04-27 14:02:05', '', '', '::1', '', '353n5g9e63dbvj68bhjmmtaro3', 0, 0, 0, '2015-07-23 00:10:30', 0, ''),
(62074, 'test', '$2y$11$UP3uSC1vgjnVBBm3z1JkDOAOZOv3hzKssWIrnhIMC0kztt6bm7syW', 'q', 'user', '2015-02-02 23:37:30', '2015-02-20 19:53:30', '', '', '70.45.157.186', '', '9b9501e4d4697beb368faee69902c783', 0, 0, 0, '2015-04-03 23:37:30', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `plan`
--

CREATE TABLE IF NOT EXISTS `plan` (
  `id` int(10) unsigned NOT NULL,
  `desc` varchar(128) NOT NULL,
  `price` float NOT NULL,
  `term` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `profile`
--

CREATE TABLE IF NOT EXISTS `profile` (
`id` int(11) unsigned NOT NULL,
  `userid` int(11) unsigned NOT NULL,
  `name` varchar(32) NOT NULL,
  `middle` varchar(32) NOT NULL,
  `last` varchar(32) NOT NULL,
  `maiden` varchar(32) NOT NULL,
  `email` varchar(64) NOT NULL,
  `phone` varchar(18) NOT NULL,
  `address1` varchar(64) NOT NULL,
  `address2` varchar(64) NOT NULL,
  `city` varchar(64) NOT NULL,
  `state` varchar(64) NOT NULL,
  `zip` varchar(18) NOT NULL,
  `country` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `vehicle`
--

CREATE TABLE IF NOT EXISTS `vehicle` (
  `id` int(10) unsigned NOT NULL,
  `userid` int(11) NOT NULL,
  `type` varchar(64) NOT NULL,
  `model` varchar(64) NOT NULL,
  `brand` varchar(64) NOT NULL,
  `year` int(11) NOT NULL,
  `plate` int(11) NOT NULL,
  `serial` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `config`
--
ALTER TABLE `config`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plan`
--
ALTER TABLE `plan`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `profile`
--
ALTER TABLE `profile`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vehicle`
--
ALTER TABLE `vehicle`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `config`
--
ALTER TABLE `config`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `profile`
--
ALTER TABLE `profile`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
