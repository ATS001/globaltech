-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Sam 23 Septembre 2017 à 03:34
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
-- Structure de la table `ref_type_echeance`
--

CREATE TABLE IF NOT EXISTS `ref_type_echeance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_echeance` varchar(50) DEFAULT NULL COMMENT 'Mensuelle,Trimestrielle,Annuelle',
  `etat` int(11) DEFAULT '0',
  `creusr` varchar(50) DEFAULT NULL,
  `credat` datetime DEFAULT CURRENT_TIMESTAMP,
  `updusr` varchar(50) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `ref_type_echeance`
--

INSERT INTO `ref_type_echeance` (`id`, `type_echeance`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(1, 'Annuelle', 1, '1', '2017-09-15 20:05:43', '1', '2017-09-15 20:17:12'),
(2, 'Mensuelle', 1, '1', '2017-09-15 20:17:02', '1', '2017-09-15 20:17:16'),
(3, 'Trimestrielle', 1, '1', '2017-09-15 20:17:08', '1', '2017-09-15 20:17:14'),
(4, 'Autres', 1, '1', '2017-09-16 12:58:33', NULL, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
