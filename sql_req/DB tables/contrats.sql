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
-- Structure de la table `contrats`
--

CREATE TABLE IF NOT EXISTS `contrats` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `tkn_frm` varchar(32) DEFAULT NULL,
  `ref` varchar(100) NOT NULL COMMENT 'Reference',
  `iddevis` int(11) DEFAULT NULL,
  `date_effet` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  `commentaire` text,
  `date_contrat` date DEFAULT NULL,
  `idtype_echeance` int(11) DEFAULT NULL,
  `pj` int(11) DEFAULT NULL,
  `pj_photo` int(11) DEFAULT NULL,
  `contrats_pdf` int(11) DEFAULT NULL,
  `etat` int(11) DEFAULT '0',
  `creusr` int(11) DEFAULT NULL,
  `credat` datetime DEFAULT CURRENT_TIMESTAMP,
  `updusr` int(11) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_devis_contrat` (`iddevis`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- Contenu de la table `contrats`
--

INSERT INTO `contrats` (`id`, `tkn_frm`, `ref`, `iddevis`, `date_effet`, `date_fin`, `commentaire`, `date_contrat`, `idtype_echeance`, `pj`, `pj_photo`, `contrats_pdf`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(12, 'c144d6e7fd09b05f1c96b5671054507a', 'CTR-1/2017', 13, '2017-09-22', '2017-09-23', '<p>okk<br></p>', '2017-09-18', 4, NULL, NULL, 450, 1, 1, '2017-09-18 20:03:39', 1, '2017-09-18 20:04:04'),
(13, 'cfe9bcccd33ba972448fbe5a7728314c', 'CTR-2/2017', 13, '2017-09-22', '2017-09-22', '<p>ok<br></p>', '2017-09-23', 2, NULL, NULL, NULL, 0, 1, '2017-09-22 21:54:15', 1, '2017-09-23 03:19:22'),
(14, 'ea64f87a579739b60cf41d628eda4260', 'CTR-3/2017', 22, '2017-09-23', '2017-09-29', '<p>okkk<br></p>', '2017-09-23', 1, NULL, NULL, 449, 0, 1, '2017-09-23 01:29:54', NULL, NULL),
(15, '13486b070fa62a4458ee104ec54198c6', 'CTR-4/2017', 21, '2017-09-24', '2017-09-28', '<p>okk<br></p>', '2017-09-23', 4, NULL, NULL, NULL, 0, 1, '2017-09-23 01:33:40', 1, '2017-09-23 02:23:34');

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `contrats`
--
ALTER TABLE `contrats`
  ADD CONSTRAINT `fk_devis_contrat` FOREIGN KEY (`iddevis`) REFERENCES `devis` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
