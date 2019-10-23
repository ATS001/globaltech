-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 24, 2019 at 12:52 AM
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
-- Table structure for table `visites`
--

CREATE TABLE IF NOT EXISTS `visites` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `commerciale` int(11) DEFAULT NULL COMMENT 'Commerciale',
  `raison_sociale` varchar(200) DEFAULT NULL COMMENT 'Raison sociale',
  `nature_visite` varchar(200) DEFAULT NULL COMMENT 'Client / Prospect',
  `objet_visite` varchar(200) DEFAULT NULL COMMENT 'Objet Visite',
  `date_visite` date DEFAULT NULL COMMENT 'Date Visite',
  `interlocuteur` varchar(200) DEFAULT NULL COMMENT 'Interlocuteur',
  `fonction_interloc` varchar(200) DEFAULT NULL COMMENT 'Fonction Interlocuteur',
  `coordonees_interloc` text COMMENT 'Coordonnées Interlocuteur',
  `commentaire` text COMMENT 'Commentaire',
  `etat` int(11) DEFAULT '0' COMMENT 'Etat',
  `creusr` int(11) DEFAULT NULL,
  `credat` datetime DEFAULT CURRENT_TIMESTAMP,
  `updusr` int(11) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
