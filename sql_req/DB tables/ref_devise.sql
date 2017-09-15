-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Ven 15 Septembre 2017 à 21:46
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `globaltech`
--

-- --------------------------------------------------------

--
-- Structure de la table `ref_devise`
--

CREATE TABLE IF NOT EXISTS `ref_devise` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `devise` varchar(30) DEFAULT NULL,
  `abreviation` varchar(10) DEFAULT NULL,
  `etat` int(11) DEFAULT '0',
  `creusr` varchar(50) DEFAULT NULL,
  `credat` datetime DEFAULT CURRENT_TIMESTAMP,
  `updusr` varchar(50) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `ref_devise`
--

INSERT INTO `ref_devise` (`id`, `devise`, `abreviation`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(1, 'Franc CFA', 'FCFA', 1, '1', '2017-09-13 22:08:50', NULL, NULL),
(2, 'Dirham Maroc', 'DH', 1, '1', '2017-09-13 22:10:10', NULL, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
