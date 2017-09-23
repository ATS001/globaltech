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
-- Structure de la table `produits`
--

CREATE TABLE IF NOT EXISTS `produits` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `ref` varchar(100) DEFAULT NULL COMMENT 'Reference produit',
  `designation` varchar(100) DEFAULT NULL COMMENT 'DÃ©signation',
  `stock_min` int(11) DEFAULT NULL COMMENT 'Stock minimal',
  `idcategorie` int(11) DEFAULT NULL COMMENT 'CatÃ©gorie',
  `iduv` int(11) DEFAULT NULL COMMENT 'UnitÃ© de vente',
  `idtype` int(11) DEFAULT NULL COMMENT 'Type produit',
  `etat` int(11) DEFAULT '0',
  `creusr` int(11) DEFAULT NULL,
  `credat` datetime DEFAULT CURRENT_TIMESTAMP,
  `updusr` int(11) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_produit_categorie` (`idcategorie`),
  KEY `fk_produit_uv` (`iduv`),
  KEY `fk_produit_type` (`idtype`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

--
-- Contenu de la table `produits`
--

INSERT INTO `produits` (`id`, `ref`, `designation`, `stock_min`, `idcategorie`, `iduv`, `idtype`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(17, 'LNB_ku-band_high_pll', 'LNB PLL bande Ku', 5, 8, 6, 4, 1, 1, '2017-09-09 11:21:12', 1, '2017-09-14 11:44:15'),
(18, 'Modem_idirect_x1', 'Modem iDirect Evolution X1', 5, 8, 7, 4, 0, 1, '2017-09-09 11:22:21', NULL, NULL),
(19, 'a_skyware_1.2m Ku-Band_122', 'Antenne VSAT 1.2m bande Ku Skyware Global', 3, 8, 8, 4, 1, 1, '2017-09-09 11:28:54', 1, '2017-09-14 11:11:13'),
(20, 'PRD-1/2017', 'STYLO', 100, 3, 2, 2, 0, 1, '2017-09-23 01:09:31', 1, '2017-09-23 01:09:38');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
