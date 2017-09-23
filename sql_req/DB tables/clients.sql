-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Sam 23 Septembre 2017 à 03:31
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
  `id_devise` int(11) DEFAULT NULL COMMENT 'Devise de facturation du client',
  `tva` varchar(20) DEFAULT NULL COMMENT 'tva O ou N',
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
  KEY `fk_client_type` (`id_categorie`),
  KEY `fk_client_devise` (`id_devise`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- Contenu de la table `clients`
--

INSERT INTO `clients` (`id`, `code`, `denomination`, `id_categorie`, `r_social`, `r_commerce`, `nif`, `nom`, `prenom`, `civilite`, `adresse`, `id_pays`, `id_ville`, `tel`, `fax`, `bp`, `email`, `rib`, `id_devise`, `tva`, `pj`, `pj_photo`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(1, 'CP122', 'DE111', 1, NULL, NULL, NULL, NULL, NULL, 'Femme', 'adr12', 242, 43, '064333333333333333333333', '033333333333333333333333', NULL, 'em@em', NULL, 2, 'N', 425, 426, 1, 1, '2017-08-26 12:59:00', 1, '2017-08-26 19:34:14'),
(2, 'CP', 'DE111', 1, NULL, NULL, NULL, NULL, NULL, 'Homme', 'adress', 242, 43, '0444444444444444444444444444', NULL, NULL, 'em@em', NULL, 2, NULL, 428, 429, 0, 1, '2017-08-26 13:51:29', 1, '2017-09-20 17:50:57'),
(8, 'CP444', 'DENOMI9', 1, NULL, NULL, NULL, NULL, NULL, 'Femme', 'adr1', 242, 2, '0444444444444444444444444', NULL, NULL, 'em@em', NULL, 2, 'O', 430, 408, 0, 1, '2017-08-26 15:29:40', 1, '2017-09-13 21:02:48'),
(9, 'pjjjj', 'kmzdkzf', 1, NULL, NULL, NULL, NULL, NULL, 'Femme', 'dzdlz', 242, 43, '0222222222222222222222222', NULL, NULL, 'em@em', NULL, 2, 'N', NULL, 431, 0, 1, '2017-08-26 15:53:32', 1, '2017-09-13 21:03:43'),
(15, 'PRFF', 'fefeg', 1, 'lmlgrl,rleg', NULL, NULL, NULL, NULL, 'Femme', 'grdgdr', 242, 43, '04555555555555555', NULL, NULL, 'em@em', NULL, 2, 'O', 412, 413, 0, 1, '2017-08-26 18:05:06', 1, '2017-08-26 21:01:55'),
(16, 'waaw', 'den', 1, 'rhgg', '10999', '9888888888888', 'hanounou', 'fati', 'Femme', 'adr', 242, 34, '033333333333333333333333333333', NULL, NULL, 'em@em', NULL, 1, 'N', 432, 433, 1, 1, '2017-09-13 17:30:29', 1, '2017-09-20 17:02:25'),
(17, 'CLT-1/2017', 'client 2', 1, 'erhk', ',ldekgmlz', '0444444444', NULL, NULL, 'Femme', 'ezglekrg', 242, 36, '03333333333333333333333333333', '02222222222222222222', 'mkfze', 'em@em', NULL, 2, NULL, NULL, NULL, 0, 1, '2017-09-23 00:35:52', 1, '2017-09-23 00:38:07');

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `clients`
--
ALTER TABLE `clients`
  ADD CONSTRAINT `fk_client_categorie` FOREIGN KEY (`id_categorie`) REFERENCES `categorie_client` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_client_devise` FOREIGN KEY (`id_devise`) REFERENCES `ref_devise` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_client_pays` FOREIGN KEY (`id_pays`) REFERENCES `ref_pays` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_client_ville` FOREIGN KEY (`id_ville`) REFERENCES `ref_ville` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
