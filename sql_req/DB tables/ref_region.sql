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
-- Structure de la table `ref_region`
--

CREATE TABLE IF NOT EXISTS `ref_region` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identifiant de ligne',
  `id_pays` int(11) DEFAULT '242' COMMENT 'le pays de la region par default Tchad',
  `region` varchar(30) CHARACTER SET latin1 NOT NULL COMMENT 'libelle region',
  `etat` int(2) NOT NULL DEFAULT '0' COMMENT 'l''etat de la ligne',
  `creusr` varchar(60) NOT NULL COMMENT 'Crée par',
  `credat` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date de création',
  `updusr` varchar(60) DEFAULT NULL COMMENT 'Derniere modif par',
  `upddat` datetime DEFAULT NULL COMMENT 'Date derniere modif',
  PRIMARY KEY (`id`),
  KEY `fk_region_pays` (`id_pays`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=31 ;

--
-- Contenu de la table `ref_region`
--

INSERT INTO `ref_region` (`id`, `id_pays`, `region`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(1, 242, 'Barh el Ghazel', 1, '', '2017-03-19 21:47:48', '1', '2017-09-15 17:57:53'),
(2, 242, 'Batha', 1, '', '2017-03-19 21:47:48', NULL, NULL),
(5, 242, 'Borkou', 1, '', '2017-03-19 21:47:48', NULL, NULL),
(6, 242, 'Chari-Baguirmi', 1, '', '2017-03-19 21:47:48', 'admin', '2017-03-28 21:56:56'),
(7, 242, 'Ennedi Est', 1, '', '2017-03-19 21:47:48', NULL, NULL),
(8, 242, 'Ennedi Ouest', 1, '', '2017-03-19 21:47:48', NULL, NULL),
(9, 242, 'Guéra', 1, '', '2017-03-19 21:47:48', 'admin', '2017-03-28 21:56:04'),
(10, 242, 'Hadjer-Lamis', 1, '', '2017-03-19 21:47:48', NULL, NULL),
(11, 242, 'Kanem', 1, '', '2017-03-19 21:47:48', NULL, NULL),
(12, 242, 'Lac', 1, '', '2017-03-19 21:47:48', NULL, NULL),
(13, 242, 'Logone Occidental', 1, '', '2017-03-19 21:47:48', NULL, NULL),
(14, 242, 'Logone Oriental', 1, '', '2017-03-19 21:47:48', NULL, NULL),
(15, 242, 'Mandoul', 1, '', '2017-03-19 21:47:48', NULL, NULL),
(16, 242, 'Mayo-Kebbi Est', 1, '', '2017-03-19 21:47:48', NULL, NULL),
(17, 242, 'Mayo-Kebbi Ouest', 1, '', '2017-03-19 21:47:48', NULL, NULL),
(18, 242, 'Moyen-Chari', 1, '', '2017-03-19 21:47:48', NULL, NULL),
(19, 242, 'Ouaddaï', 1, '', '2017-03-19 21:47:48', NULL, NULL),
(20, 242, 'Salamat', 1, '', '2017-03-19 21:47:48', NULL, NULL),
(21, 242, 'Sila', 1, '', '2017-03-19 21:47:48', NULL, NULL),
(22, 242, 'Tandjilé', 1, '', '2017-03-19 21:47:48', NULL, NULL),
(23, 242, 'Tibesti', 1, '', '2017-03-19 21:47:48', NULL, NULL),
(24, 242, 'Ville de N''Djamena', 1, '', '2017-03-19 21:47:48', NULL, NULL),
(25, 242, 'Wadi Fira', 1, '', '2017-03-19 21:47:48', '1', '2017-04-02 13:48:49'),
(30, 242, 'Lacc', 1, '1', '2017-04-02 13:50:53', '1', '2017-07-09 18:41:09');

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `ref_region`
--
ALTER TABLE `ref_region`
  ADD CONSTRAINT `pays_region` FOREIGN KEY (`id_pays`) REFERENCES `ref_pays` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
