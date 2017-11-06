-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Lun 23 Octobre 2017 à 23:44
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
-- Structure de la table `d_factures`
--

CREATE TABLE IF NOT EXISTS `d_factures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order` int(5) DEFAULT NULL,
  `id_facture` int(11) DEFAULT NULL,
  `id_produit` int(11) DEFAULT NULL,
  `ref_produit` varchar(20) DEFAULT NULL,
  `designation` varchar(40) DEFAULT NULL,
  `qte` int(11) DEFAULT NULL,
  `qte_designation` varchar(50) DEFAULT NULL COMMENT 'la valeur de la qte Année ou mois',
  `prix_unitaire` double DEFAULT NULL,
  `type_remise` varchar(1) DEFAULT NULL,
  `remise_valeur` double DEFAULT NULL,
  `tva` double DEFAULT NULL,
  `prix_ht` double DEFAULT NULL,
  `prix_ttc` double DEFAULT NULL,
  `total_ht` double DEFAULT NULL,
  `total_ttc` double DEFAULT NULL,
  `total_tva` double DEFAULT NULL,
  `creusr` varchar(50) DEFAULT NULL,
  `credat` datetime DEFAULT NULL,
  `updusr` varchar(50) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_id_produit` (`id_produit`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `d_factures`
--

INSERT INTO `d_factures` (`id`, `order`, `id_facture`, `id_produit`, `ref_produit`, `designation`, `qte`, `qte_designation`, `prix_unitaire`, `type_remise`, `remise_valeur`, `tva`, `prix_ht`, `prix_ttc`, `total_ht`, `total_ttc`, `total_tva`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(2, 1, 102, 25, 'GT-PRD-4/2017', 'Connexion  Vsat 1 Mo', 1, 'MOIS', 1000000, 'P', 5, 0, NULL, NULL, 950000, 1121000, 171000, 'admin', '2017-10-23 00:00:00', NULL, NULL),
(3, 1, 103, 23, 'GT-PRD-2/2017', 'Modem iDirect Evolution X3', 0, ' ', 1120000, 'P', 0, 0, NULL, NULL, 1120000, 1321600, 201600, 'admin', '2017-10-23 00:00:00', NULL, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
