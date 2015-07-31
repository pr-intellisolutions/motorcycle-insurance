-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 31, 2015 at 02:00 PM
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
-- Table structure for table `assist`
--

CREATE TABLE IF NOT EXISTS `assist` (
`id` int(11) unsigned NOT NULL,
  `customer_id` int(11) unsigned NOT NULL,
  `provider_id` int(11) unsigned NOT NULL,
  `assist_desc` varchar(128) NOT NULL,
  `assist_area` varchar(64) NOT NULL,
  `assist_city` varchar(64) NOT NULL,
  `assist_date` date NOT NULL,
  `assist_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
(1, 'Puerto Rico Motorcycle Road Assistance Services', '', 'localhost', '/motorcycle-insurance/', 4, 24, 'alphanumeric with spacers', 4, 32, 'normal', 60, 4);

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE IF NOT EXISTS `login` (
  `id` int(11) unsigned NOT NULL DEFAULT '0',
  `user` varchar(32) NOT NULL,
  `pass` varchar(256) NOT NULL,
  `email` varchar(128) NOT NULL,
  `role` enum('admin','provider','user') DEFAULT 'user',
  `regdate` datetime NOT NULL,
  `lastvisit` datetime NOT NULL,
  `lastip` varchar(32) NOT NULL,
  `lastbrowser` varchar(128) NOT NULL,
  `ip` varchar(32) NOT NULL,
  `browser` varchar(128) NOT NULL,
  `session` varchar(256) NOT NULL,
  `expired` tinyint(1) NOT NULL DEFAULT '0',
  `disabled` tinyint(1) NOT NULL DEFAULT '0',
  `active` tinyint(4) NOT NULL,
  `passchg` tinyint(1) NOT NULL DEFAULT '0',
  `passdate` datetime NOT NULL,
  `login_attempts` int(11) NOT NULL DEFAULT '0',
  `permissions` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`id`, `user`, `pass`, `email`, `role`, `regdate`, `lastvisit`, `lastip`, `lastbrowser`, `ip`, `browser`, `session`, `expired`, `disabled`, `active`, `passchg`, `passdate`, `login_attempts`, `permissions`) VALUES
(12854, 'admin', '$2y$11$putu03YGlI8t6eahu68ZOuKU1vHt./wSn7OGmlJ4VmlpVfKYQGxuG', 'q', 'admin', '2015-02-02 23:37:12', '2015-07-30 22:09:40', '::1', 'Mozilla/5.0 (Windows NT 6.3; WOW64; Trident/7.0; rv:11.0) like Gecko', '::1', 'Mozilla/5.0 (Windows NT 6.3; WOW64; Trident/7.0; Touch; rv:11.0) like Gecko', 'ujnlikml4me5ugbfd42tj6d3r5', 0, 0, 1, 0, '2015-10-15 23:37:12', 0, 'all'),
(37749, 'dborrero', '$2y$11$8XD0LqIHCZ6Hh0LsQuNPbupbQQelaC91iS2FTqy9mnc6vK85F/AJa', 'g', 'admin', '2015-07-06 20:00:07', '2015-07-10 23:46:54', '::1', 'Mozilla/5.0 (Windows NT 6.3; WOW64; rv:38.0) Gecko/20100101 Firefox/38.0', '::1', 'Mozilla/5.0 (Windows NT 6.3; WOW64; Trident/7.0; Touch; rv:11.0) like Gecko', 'qsur6rbqkvqfd86tdsc6nmaoh5', 0, 0, 1, 0, '2015-09-04 20:00:07', 0, ''),
(39460, 'test', '$2y$11$1oHfDr/pPsLnupcmSBjCjui7g9WL537ssYcufpek1JeuXJJFv5q5q', 'test', 'user', '2015-07-28 21:47:59', '0000-00-00 00:00:00', '', '', '::1', 'Mozilla/5.0 (Windows NT 6.3; WOW64; Trident/7.0; rv:11.0) like Gecko', 'qgjacv0ldngaqq3olnk0fcnh26', 0, 0, 1, 0, '2015-09-26 21:47:59', 0, 'none'),
(41873, 'dennis', '$2y$11$eR9EV3NbYR.qnG.dRWt0eOTXgawEOQnUfnsLrz8b9T3if0jaWNSde', 'q', 'user', '2015-02-02 23:37:44', '2015-06-28 20:57:33', '::1', 'Mozilla/5.0 (Windows NT 6.3; WOW64; Trident/7.0; Touch; rv:11.0) like Gecko', '::1', 'Mozilla/5.0 (Windows NT 6.3; WOW64; Trident/7.0; Touch; rv:11.0) like Gecko', 'e4f9hdv38l4abe5altmqj0vdg5', 0, 0, 1, 0, '2015-07-23 00:10:30', 4, ''),
(43054, 'provider', '$2y$11$7Bta0ZCDr/7fy1B.fftbC.8HM9hIclpVVjFYDtykT.PNIwmPfvl9q', 'provider', 'provider', '2015-07-10 20:49:43', '0000-00-00 00:00:00', '', '', '::1', 'Mozilla/5.0 (Windows NT 6.3; WOW64; Trident/7.0; Touch; rv:11.0) like Gecko', 'qsur6rbqkvqfd86tdsc6nmaoh5', 0, 0, 1, 0, '2015-09-08 20:49:43', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `plans`
--

CREATE TABLE IF NOT EXISTS `plans` (
`id` int(11) unsigned NOT NULL,
  `name` varchar(64) NOT NULL,
  `title` varchar(64) NOT NULL,
  `description` varchar(128) NOT NULL,
  `num_occurrences` int(11) NOT NULL,
  `num_miles` int(11) NOT NULL,
  `num_vehicles` int(11) NOT NULL,
  `plan_price` float NOT NULL,
  `mile_price` float NOT NULL,
  `extend_price` float NOT NULL,
  `term` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `date_entered` datetime NOT NULL,
  `last_modify` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `plans`
--

INSERT INTO `plans` (`id`, `name`, `title`, `description`, `num_occurrences`, `num_miles`, `num_vehicles`, `plan_price`, `mile_price`, `extend_price`, `term`, `active`, `date_entered`, `last_modify`) VALUES
(14, 'basico', 'Membres&iacute;a B&aacute;sica', 'Incluye 4 ocurrencias en adici&oacute;n 50 millas de remolque', 4, 50, 2, 60, 3, 30, 12, 1, '2015-07-28 21:37:01', '0000-00-00 00:00:00'),
(15, 'extendido', 'Membres&iacute;a Extendida', 'Incluye 4 ocurrencias en adici&oacute;n 100 millas de remolque', 4, 100, 2, 90, 3, 30, 12, 1, '2015-07-28 21:45:00', '2015-07-28 21:45:33'),
(16, 'test', '', '', 0, 0, 0, 0, 0, 0, 0, 1, '2015-07-30 22:39:34', '0000-00-00 00:00:00');

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
  `phone` varchar(18) NOT NULL,
  `address1` varchar(64) NOT NULL,
  `address2` varchar(64) NOT NULL,
  `city` varchar(64) NOT NULL,
  `state` varchar(64) NOT NULL,
  `zip` varchar(18) NOT NULL,
  `country` varchar(64) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1012 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `profile`
--

INSERT INTO `profile` (`id`, `userid`, `name`, `middle`, `last`, `maiden`, `phone`, `address1`, `address2`, `city`, `state`, `zip`, `country`) VALUES
(1010, 37749, 'Dennis', 'J.', 'Borrero', 'Torres', '(224) 321-7628', '6109 Calle San Claudio', '', 'Ponce', 'PR', '00730', 'Puerto Rico'),
(1011, 39460, 'test', 'test', 'test', '', '', '', '', '', '', '', '');

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
  `area` enum('central','este','norte','noreste','noroeste','oeste','sur','sureste','suroeste') NOT NULL,
  `companyAddress1` varchar(64) NOT NULL,
  `companyAddress2` varchar(64) NOT NULL,
  `city` varchar(64) NOT NULL,
  `zip` varchar(18) NOT NULL,
  `country` varchar(64) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `providers`
--

INSERT INTO `providers` (`id`, `userid`, `companyName`, `companyPhone`, `companyEmail`, `area`, `companyAddress1`, `companyAddress2`, `city`, `zip`, `country`) VALUES
(1, 43054, 'La compania', '888-8888', 'lacompania@email.com', 'sur', '123', 'probando', 'san juan', '000911', 'PR');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE IF NOT EXISTS `services` (
`id` int(11) unsigned NOT NULL,
  `userid` int(11) unsigned NOT NULL,
  `plan_id` int(11) unsigned NOT NULL,
  `occurrence_counter` int(11) NOT NULL,
  `miles_counter` int(11) NOT NULL,
  `vehicles_counter` int(11) NOT NULL,
  `renewal` tinyint(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `userid`, `plan_id`, `occurrence_counter`, `miles_counter`, `vehicles_counter`, `renewal`) VALUES
(1, 39460, 14, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE IF NOT EXISTS `vehicles` (
`id` int(11) unsigned NOT NULL,
  `userid` int(11) unsigned NOT NULL,
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
-- Indexes for table `assist`
--
ALTER TABLE `assist`
 ADD PRIMARY KEY (`id`), ADD KEY `customer_id` (`customer_id`,`provider_id`), ADD KEY `provider_id` (`provider_id`);

--
-- Indexes for table `config`
--
ALTER TABLE `config`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `user` (`user`);

--
-- Indexes for table `plans`
--
ALTER TABLE `plans`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `profile`
--
ALTER TABLE `profile`
 ADD PRIMARY KEY (`id`), ADD KEY `profile_ibfk_1` (`userid`);

--
-- Indexes for table `providers`
--
ALTER TABLE `providers`
 ADD PRIMARY KEY (`id`), ADD KEY `providers_ibfk_1` (`userid`), ADD KEY `userid` (`userid`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
 ADD PRIMARY KEY (`id`), ADD KEY `userid` (`userid`), ADD KEY `plan_id` (`plan_id`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
 ADD PRIMARY KEY (`id`), ADD KEY `vehicles_ibfk_1` (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assist`
--
ALTER TABLE `assist`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `config`
--
ALTER TABLE `config`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `plans`
--
ALTER TABLE `plans`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `profile`
--
ALTER TABLE `profile`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1012;
--
-- AUTO_INCREMENT for table `providers`
--
ALTER TABLE `providers`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `assist`
--
ALTER TABLE `assist`
ADD CONSTRAINT `assist_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `login` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `assist_ibfk_2` FOREIGN KEY (`provider_id`) REFERENCES `providers` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `profile`
--
ALTER TABLE `profile`
ADD CONSTRAINT `profile_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `login` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `providers`
--
ALTER TABLE `providers`
ADD CONSTRAINT `providers_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `login` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `services`
--
ALTER TABLE `services`
ADD CONSTRAINT `services_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `login` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `services_ibfk_2` FOREIGN KEY (`plan_id`) REFERENCES `plans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `vehicles`
--
ALTER TABLE `vehicles`
ADD CONSTRAINT `vehicles_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `login` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
