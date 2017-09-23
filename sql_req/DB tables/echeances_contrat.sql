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
-- Structure de la table `echeances_contrat`
--

CREATE TABLE IF NOT EXISTS `echeances_contrat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tkn_frm` varchar(32) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `date_echeance` date DEFAULT NULL,
  `commentaire` varchar(2000) DEFAULT NULL,
  `idcontrat` int(11) DEFAULT NULL,
  `etat` int(11) DEFAULT '0',
  `creusr` int(11) DEFAULT NULL,
  `credat` datetime DEFAULT CURRENT_TIMESTAMP,
  `updusr` int(11) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_contrat_echeance` (`idcontrat`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- Contenu de la table `echeances_contrat`
--

INSERT INTO `echeances_contrat` (`id`, `tkn_frm`, `order`, `date_echeance`, `commentaire`, `idcontrat`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(1, '280b9532f38b681e492a946901bb6a75', 0, '0000-00-00', 'okk', NULL, 0, 1, '2017-09-17 02:39:08', NULL, NULL),
(2, 'd71733b466aba09cbf75609bbf04c242', 1, '0000-00-00', 'ok', NULL, 0, 1, '2017-09-17 02:41:35', NULL, NULL),
(3, 'bd15c792eabb0ddb94b2957f4791edac', 1, '0000-00-00', 'okk', NULL, 0, 1, '2017-09-17 02:49:47', NULL, NULL),
(4, '55a31a199a934299f81b730c55e4ef7b', 1, '0000-00-00', 'okkk', NULL, 0, 1, '2017-09-17 13:45:32', NULL, NULL),
(5, '431a6d0960af44c721ac47906964e125', 1, '0000-00-00', 'test', NULL, 0, 1, '2017-09-17 13:50:10', NULL, NULL),
(6, '431a6d0960af44c721ac47906964e125', 2, '0000-00-00', 'vvv', NULL, 0, 1, '2017-09-17 13:50:55', NULL, NULL),
(7, 'd727b9f049758a8ab0c732ebdd3d379a', 1, '0000-00-00', 'hdhd', NULL, 0, 1, '2017-09-17 13:52:07', NULL, NULL),
(8, 'd727b9f049758a8ab0c732ebdd3d379a', 2, '0000-00-00', 'cccc', NULL, 0, 1, '2017-09-17 13:52:33', NULL, NULL),
(9, 'd727b9f049758a8ab0c732ebdd3d379a', 3, '0000-00-00', 'ddddd', NULL, 0, 1, '2017-09-17 13:54:21', NULL, NULL),
(10, 'd727b9f049758a8ab0c732ebdd3d379a', 4, '0000-00-00', 'kkk', NULL, 0, 1, '2017-09-17 13:57:00', NULL, NULL),
(11, '179584f02af18c2cfa9ba061ef666c48', 1, '0000-00-00', 'ddd', NULL, 0, 1, '2017-09-17 13:59:36', NULL, NULL),
(12, '179584f02af18c2cfa9ba061ef666c48', 2, '0000-00-00', 'ddd', NULL, 0, 1, '2017-09-17 14:00:08', NULL, NULL),
(13, 'a7aadf89c66c3431c0bf86f9d89c3275', 1, '0000-00-00', 'ccc', NULL, 0, 1, '2017-09-17 14:02:19', NULL, NULL),
(15, 'c144d6e7fd09b05f1c96b5671054507a', 1, '2017-09-27', 'okk', 12, 0, 1, '2017-09-18 20:03:28', NULL, NULL),
(16, '13486b070fa62a4458ee104ec54198c6', 1, '2017-09-23', 'okkkkkj', 15, 0, 1, '2017-09-23 01:33:36', NULL, NULL),
(17, '7a11fcb558cde5ab934960aabdf15fa7', 1, '2017-09-23', 'waawaaaw', NULL, 0, 1, '2017-09-23 02:06:40', 1, '2017-09-23 02:06:48');

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `echeances_contrat`
--
ALTER TABLE `echeances_contrat`
  ADD CONSTRAINT `fk_contrat_echeance` FOREIGN KEY (`idcontrat`) REFERENCES `contrats` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
