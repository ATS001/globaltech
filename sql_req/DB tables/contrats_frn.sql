-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Ven 22 Septembre 2017 à 03:41
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
-- Structure de la table `contrats_frn`
--

CREATE TABLE IF NOT EXISTS `contrats_frn` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `reference` varchar(100) NOT NULL COMMENT 'Reference',
  `id_fournisseur` int(11) DEFAULT NULL,
  `date_effet` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  `commentaire` text,
  `pj` int(11) DEFAULT NULL,
  `pj_photo` int(11) DEFAULT NULL,
  `etat` int(11) DEFAULT '0',
  `creusr` int(11) DEFAULT NULL,
  `credat` datetime DEFAULT CURRENT_TIMESTAMP,
  `updusr` int(11) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- Contenu de la table `contrats_frn`
--

INSERT INTO `contrats_frn` (`id`, `reference`, `id_fournisseur`, `date_effet`, `date_fin`, `commentaire`, `pj`, `pj_photo`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(12, 'CTR-FRN-1/2017', 2, '2017-09-22', '2017-09-23', '<p>okk<br></p>', 445, NULL, 0, 1, '2017-09-18 20:03:39', 1, '2017-09-22 03:40:20'),
(13, 'CTR-FRN-2/2017', 2, '1970-01-01', '1970-01-23', '<p>okk<br></p>', 441, NULL, 0, 1, '2017-09-21 13:26:03', 1, '2017-09-22 03:40:31'),
(14, 'CTR-FRN-3/2017', 2, '1970-01-01', '1970-07-30', '<p>ok<br></p>', 446, NULL, 0, 1, '2017-09-21 16:41:03', 1, '2017-09-22 03:33:08');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
