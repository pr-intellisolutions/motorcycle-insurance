-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 11, 2015 at 02:51 AM
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
-- Table structure for table `providers`
--

CREATE TABLE IF NOT EXISTS `providers` (
`id` int(11) unsigned NOT NULL,
  `userid` int(11) unsigned NOT NULL,
  `companyName` varchar(64) NOT NULL,
  `companyPhone` varchar(18) NOT NULL,
  `companyEmail` varchar(128) NOT NULL,
  `area` enum('north','south','east','west','central') NOT NULL,
  `address1` varchar(64) NOT NULL,
  `address2` varchar(64) NOT NULL,
  `city` varchar(64) NOT NULL,
  `zip` varchar(18) NOT NULL,
  `country` varchar(64) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `providers`
--

INSERT INTO `providers` (`id`, `userid`, `companyName`, `companyPhone`, `companyEmail`, `area`, `address1`, `address2`, `city`, `zip`, `country`) VALUES
(1, 43054, '', '', '', 'north', '', '', '', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `providers`
--
ALTER TABLE `providers`
 ADD PRIMARY KEY (`id`), ADD KEY `providers_ibfk_1` (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `providers`
--
ALTER TABLE `providers`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `providers`
--
ALTER TABLE `providers`
ADD CONSTRAINT `providers_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `login` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
