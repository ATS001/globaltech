-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Sam 26 Août 2017 à 21:30
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
-- Structure de la table `clients`
--

CREATE TABLE IF NOT EXISTS `clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `code` varchar(10) NOT NULL COMMENT 'Code client',
  `denomination` varchar(200) NOT NULL COMMENT 'Denomination du client',
  `id_categorie` int(11) NOT NULL COMMENT 'Type client',
  `r_social` varchar(200) DEFAULT NULL COMMENT 'Raison social',
  `r_commerce` varchar(100) DEFAULT NULL COMMENT 'Registre de commerce',
  `nif` varchar(20) DEFAULT NULL COMMENT 'Id fiscale',
  `nom` varchar(100) DEFAULT NULL COMMENT 'Nom',
  `prenom` varchar(100) DEFAULT NULL COMMENT 'Prénom',
  `civilite` varchar(10) DEFAULT NULL COMMENT 'Sexe',
  `adresse` varchar(200) NOT NULL COMMENT 'Adresse',
  `id_pays` int(11) NOT NULL COMMENT 'Pays',
  `id_ville` int(11) NOT NULL COMMENT 'Ville',
  `tel` varchar(80) NOT NULL COMMENT 'Telephone',
  `fax` varchar(80) DEFAULT NULL COMMENT 'Fax',
  `bp` varchar(80) DEFAULT NULL COMMENT 'Boite postale',
  `email` varchar(100) NOT NULL COMMENT 'E-mail',
  `rib` varchar(30) DEFAULT NULL COMMENT 'compte bancaire du client',
  `devise` varchar(20) DEFAULT NULL COMMENT 'Devise de facturation du client',
  `pj` int(11) DEFAULT NULL,
  `pj_photo` int(11) DEFAULT NULL COMMENT 'photo du client',
  `etat` int(11) DEFAULT '0',
  `creusr` int(11) DEFAULT NULL,
  `credat` datetime DEFAULT CURRENT_TIMESTAMP,
  `updusr` int(11) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_code` (`code`),
  KEY `fk_client_ville` (`id_ville`),
  KEY `fk_client_pays` (`id_pays`),
  KEY `fk_client_type` (`id_categorie`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- Contenu de la table `clients`
--

INSERT INTO `clients` (`id`, `code`, `denomination`, `id_categorie`, `r_social`, `r_commerce`, `nif`, `nom`, `prenom`, `civilite`, `adresse`, `id_pays`, `id_ville`, `tel`, `fax`, `bp`, `email`, `rib`, `devise`, `pj`, `pj_photo`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(1, 'CP122', 'DE111', 1, NULL, NULL, NULL, NULL, NULL, '1', 'adr12', 242, 43, '064333333333333333333333', '033333333333333333333333', NULL, 'em@em', NULL, NULL, 415, 416, 1, 1, '2017-08-26 12:59:00', 1, '2017-08-26 19:34:14'),
(2, 'CP123', 'DE111', 1, NULL, NULL, NULL, NULL, NULL, '2', 'adress', 242, 43, '0444444444444444444444444444', NULL, NULL, 'em@em', NULL, NULL, NULL, NULL, 0, 1, '2017-08-26 13:51:29', 1, '2017-08-26 21:12:25'),
(8, 'CP444', 'DENOMI9', 1, NULL, NULL, NULL, NULL, NULL, '1', 'adr1', 242, 2, '0444444444444444444444444', NULL, NULL, 'em@em', NULL, NULL, 407, 408, 0, 1, '2017-08-26 15:29:40', 1, '2017-08-26 18:01:57'),
(9, 'pjjjj', 'kmzdkzf', 1, NULL, NULL, NULL, NULL, NULL, '1', 'dzdlz', 242, 43, '0222222222222222222222222', NULL, NULL, 'em@em', NULL, NULL, NULL, NULL, 0, 1, '2017-08-26 15:53:32', NULL, NULL),
(15, 'PRFF', 'fefeg', 1, 'lmlgrl,rleg', NULL, NULL, NULL, NULL, '1', 'grdgdr', 242, 43, '04555555555555555', NULL, NULL, 'em@em', NULL, NULL, 412, 413, 0, 1, '2017-08-26 18:05:06', 1, '2017-08-26 21:01:55');

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `clients`
--
ALTER TABLE `clients`
  ADD CONSTRAINT `fk_client_categorie` FOREIGN KEY (`id_categorie`) REFERENCES `categorie_client` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_client_pays` FOREIGN KEY (`id_pays`) REFERENCES `ref_pays` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_client_ville` FOREIGN KEY (`id_ville`) REFERENCES `ref_ville` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
