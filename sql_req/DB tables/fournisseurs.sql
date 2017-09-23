-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Sam 23 Septembre 2017 à 03:32
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
-- Structure de la table `fournisseurs`
--

CREATE TABLE IF NOT EXISTS `fournisseurs` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `code` varchar(10) NOT NULL COMMENT 'Code fournisseur',
  `denomination` varchar(200) NOT NULL COMMENT 'Denomination du client',
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
  `rib` varchar(30) DEFAULT NULL COMMENT 'compte bancaire du fournisseur',
  `id_devise` int(11) DEFAULT NULL COMMENT 'Devise de facturation du client',
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
  KEY `fk_client_devise` (`id_devise`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

--
-- Contenu de la table `fournisseurs`
--

INSERT INTO `fournisseurs` (`id`, `code`, `denomination`, `r_social`, `r_commerce`, `nif`, `nom`, `prenom`, `civilite`, `adresse`, `id_pays`, `id_ville`, `tel`, `fax`, `bp`, `email`, `rib`, `id_devise`, `pj`, `pj_photo`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(1, 'CP122', 'frn1', NULL, NULL, NULL, NULL, NULL, 'Femme', 'adr12', 242, 43, '064333333333333333333333', '033333333333333333333333', NULL, 'em@em', NULL, 1, 425, 426, 0, 1, '2017-08-26 12:59:00', 1, '2017-09-20 18:09:35'),
(2, 'CP123', 'frn2', NULL, NULL, NULL, NULL, NULL, 'Homme', 'adress', 242, 43, '0444444444444444444444444444', NULL, NULL, 'em@em', NULL, 2, 428, 429, 1, 1, '2017-08-26 13:51:29', 1, '2017-09-20 17:27:57'),
(8, 'CP444', 'DENOMI9', 'RS', NULL, NULL, NULL, NULL, 'Femme', 'adr1', 242, 2, '0444444444444444444444444', NULL, NULL, 'em@em', NULL, 1, 430, 408, 0, 1, '2017-08-26 15:29:40', 1, '2017-09-20 17:45:36'),
(15, 'PRFF', 'fefeg', 'lmlgrl,rleg', NULL, NULL, NULL, NULL, 'Femme', 'grdgdr', 242, 0, '04555555555555555', NULL, NULL, 'em@em', NULL, 1, 412, 413, 1, 1, '2017-08-26 18:05:06', 1, '2017-09-20 17:46:09'),
(17, 'NJKL', 'den', 'rf', NULL, NULL, NULL, NULL, 'Femme', 'paris', 75, 0, '044444444444444', NULL, 'bpppp', 'em@em', NULL, 1, NULL, NULL, 0, 1, '2017-09-20 18:12:54', 1, '2017-09-20 21:22:57'),
(19, 'FRN-1/2017', 'frns test', NULL, NULL, NULL, NULL, NULL, 'Femme', 'dlajoljda', 242, 0, '089999933333333', NULL, NULL, 'em@em', NULL, 1, NULL, NULL, 0, 1, '2017-09-22 22:54:24', 1, '2017-09-22 22:55:25');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
