-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 02, 2018 at 12:25 AM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `globaltech`
--

-- --------------------------------------------------------

--
-- Table structure for table `sys_setting`
--

CREATE TABLE IF NOT EXISTS `sys_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identifiant de ligne',
  `key` varchar(30) DEFAULT '242' COMMENT 'clé paramètre',
  `value` varchar(200) CHARACTER SET latin1 NOT NULL COMMENT 'Valeur Paramètre',
  `comment` varchar(250) DEFAULT NULL COMMENT 'Description',
  `modul` int(11) NOT NULL COMMENT 'Module qui utilise paramètre',
  `etat` int(2) NOT NULL DEFAULT '1' COMMENT 'l''etat de la ligne',
  `creusr` varchar(60) NOT NULL COMMENT 'Crée par',
  `credat` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date de création',
  `updusr` varchar(60) DEFAULT NULL COMMENT 'Derniere modif par',
  `upddat` datetime DEFAULT NULL COMMENT 'Date derniere modif',
  PRIMARY KEY (`id`),
  KEY `fk_region_pays` (`key`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `sys_setting`
--

INSERT INTO `sys_setting` (`id`, `key`, `value`, `comment`, `modul`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(19, 'archive_tickets', '{"ticket_cloture":"3"}', 'Archive ticket', 135, 1, '1', '2018-08-01 00:11:45', NULL, NULL),
(20, 'archive_ticket_frs', '{"ticket_cloture":"3"}', 'Archive ticket frs', 143, 1, '1', '2018-08-01 00:11:45', NULL, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
