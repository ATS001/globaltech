-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Sam 23 Septembre 2017 à 03:33
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
-- Structure de la table `ref_departement`
--

CREATE TABLE IF NOT EXISTS `ref_departement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `departement` varchar(20) DEFAULT NULL,
  `id_region` int(11) NOT NULL,
  `etat` tinyint(1) DEFAULT '0',
  `creusr` varchar(50) NOT NULL,
  `credat` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updusr` varchar(50) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `region_depart` (`id_region`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `ref_departement`
--

INSERT INTO `ref_departement` (`id`, `departement`, `id_region`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(1, 'N''djamena Centre', 24, 1, 'admin', '2017-07-09 17:38:55', 'admin', '2017-09-15 17:57:39');

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `ref_departement`
--
ALTER TABLE `ref_departement`
  ADD CONSTRAINT `region_depart` FOREIGN KEY (`id_region`) REFERENCES `ref_region` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
