-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 27, 2015 at 06:24 AM
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
  `ip` varchar(16) NOT NULL,
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

INSERT INTO `login` (`id`, `user`, `pass`, `email`, `role`, `regdate`, `lastvisit`, `ip`, `session`, `expired`, `disabled`, `passchg`, `passdate`, `login_attempts`, `permissions`) VALUES
(12854, 'admin', '$2y$11$putu03YGlI8t6eahu68ZOuKU1vHt./wSn7OGmlJ4VmlpVfKYQGxuG', 'q', 'admin', '2015-02-02 23:37:12', '2015-03-23 22:57:50', '192.168.1.130', 'oarg5e1e7vbrh7dtonq75rpil1', 0, 0, 0, '2015-04-03 23:37:12', 0, ''),
(41873, 'dennis', '$2y$11$eR9EV3NbYR.qnG.dRWt0eOTXgawEOQnUfnsLrz8b9T3if0jaWNSde', 'q', 'user', '2015-02-02 23:37:44', '2015-03-23 22:57:14', '192.168.1.130', 'oarg5e1e7vbrh7dtonq75rpil1', 0, 0, 0, '2015-04-04 00:10:30', 0, ''),
(62074, 'test', '$2y$11$UP3uSC1vgjnVBBm3z1JkDOAOZOv3hzKssWIrnhIMC0kztt6bm7syW', 'q', 'user', '2015-02-02 23:37:30', '2015-02-20 19:53:30', '70.45.157.186', '9b9501e4d4697beb368faee69902c783', 0, 0, 0, '2015-04-03 23:37:30', 0, '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `login`
--
ALTER TABLE `login`
 ADD PRIMARY KEY (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
