-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Ven 13 Octobre 2017 à 01:41
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
-- Structure de la table `factures`
--

CREATE TABLE IF NOT EXISTS `factures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ref` varchar(50) DEFAULT NULL,
  `base_fact` char(1) DEFAULT NULL COMMENT 'C/D/B Contrat/Devis/BL',
  `total_ht` double DEFAULT NULL,
  `total_tva` double DEFAULT NULL,
  `total_ttc_initial` double DEFAULT NULL,
  `total_ttc` double DEFAULT NULL,
  `total_paye` double DEFAULT NULL COMMENT 'Le total payé',
  `reste` double DEFAULT NULL COMMENT 'reste à payer',
  `client` varchar(100) DEFAULT NULL,
  `projet` varchar(200) DEFAULT NULL COMMENT 'designation projet',
  `ref_bc` varchar(200) DEFAULT NULL COMMENT 'ref bon commande client',
  `tva` double DEFAULT NULL,
  `idcontrat` int(11) DEFAULT NULL COMMENT 'Contrat',
  `iddevis` int(11) DEFAULT NULL COMMENT 'Devis',
  `idbl` int(11) DEFAULT NULL COMMENT 'Bon de livraison',
  `date_facture` date DEFAULT NULL,
  `du` date DEFAULT NULL COMMENT 'debut periode facture',
  `au` date DEFAULT NULL COMMENT 'fin periode facture',
  `etat` int(11) DEFAULT '0',
  `creusr` int(11) DEFAULT NULL,
  `credat` datetime DEFAULT CURRENT_TIMESTAMP,
  `updusr` int(11) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_contrat` (`idcontrat`),
  KEY `fk_devis` (`iddevis`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=69 ;

--
-- Contenu de la table `factures`
--

INSERT INTO `factures` (`id`, `ref`, `base_fact`, `total_ht`, `total_tva`, `total_ttc_initial`, `total_ttc`, `total_paye`, `reste`, `client`, `projet`, `ref_bc`, `tva`, `idcontrat`, `iddevis`, `idbl`, `date_facture`, `du`, `au`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(43, 'GT-FCT-1/2017', 'C', 48, 0, 48, 48, 0, 48, 'den', 'projet installation VSAT', 'BC-19999999BHJJJ', NULL, 17, NULL, NULL, '2016-10-12', '2016-10-12', '2017-10-12', 0, 1, '2017-10-12 20:10:31', NULL, NULL),
(44, 'GT-FCT-2/2017', 'C', 145545.4, 29109.079999999998, 174654.48, 174654.48, 0, 174654.48, 'DE111', NULL, NULL, NULL, 22, NULL, NULL, '2017-10-12', '2017-10-12', '2017-11-12', 0, 1, '2017-10-12 20:10:31', NULL, NULL),
(45, 'GT-FCT-3/2017', 'C', 1148000, 252000, 1400000, 1400000, 0, 1400000, 'DE111', NULL, NULL, NULL, 31, NULL, NULL, '2017-10-12', '2017-09-08', '2017-10-12', 0, 1, '2017-10-12 20:10:31', NULL, NULL),
(46, 'GT-FCT-4/2017', 'C', 48, 0, 48, 48, 0, 48, 'den', 'projet installation VSAT', 'BC-19999999BHJJJ', NULL, 17, NULL, NULL, '2017-10-12', '2016-10-13', '2017-10-12', 0, 1, '2017-10-12 20:13:40', NULL, NULL),
(68, 'GT-FCT-5/2017', 'D', 1455454, 0, 1455454, 1455454, 0, 1455454, 'DE111', NULL, NULL, NULL, NULL, 23, NULL, '2017-10-12', NULL, NULL, 0, 1, '2017-10-12 20:52:43', NULL, NULL);

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `factures`
--
ALTER TABLE `factures`
  ADD CONSTRAINT `fk_contrat` FOREIGN KEY (`idcontrat`) REFERENCES `contrats` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_devis` FOREIGN KEY (`iddevis`) REFERENCES `devis` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
