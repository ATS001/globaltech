-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Sam 07 Octobre 2017 à 22:06
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
-- Structure de la table `sys_setting`
--

CREATE TABLE IF NOT EXISTS `sys_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identifiant de ligne',
  `key` varchar(30) DEFAULT '242' COMMENT 'clé paramètre',
  `value` varchar(200) CHARACTER SET latin1 NOT NULL COMMENT 'Valeur Paramètre',
  `comment` varchar(250) DEFAULT NULL COMMENT 'Description',
  `modul` int(11) NOT NULL COMMENT 'Module qui utilise paramètre',
  `etat` int(2) NOT NULL DEFAULT '1' COMMENT 'l''etat de la ligne',
  `creusr` varchar(60) NOT NULL COMMENT 'Crée par',
  `credat` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date de création',
  `updusr` varchar(60) DEFAULT NULL COMMENT 'Derniere modif par',
  `upddat` datetime DEFAULT NULL COMMENT 'Date derniere modif',
  PRIMARY KEY (`id`),
  KEY `fk_region_pays` (`key`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `sys_setting`
--

INSERT INTO `sys_setting` (`id`, `key`, `value`, `comment`, `modul`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(2, 'test', 'array(''1''=>''val1'', ''2''=>''val2'')', 'description test', 106, 1, '1', '2017-10-04 15:00:41', '1', '2017-10-04 15:10:40'),
(3, 'par2', '{"1":"val1", "2":"val2"}', 'Test array', 104, 1, '1', '2017-10-04 15:33:40', '1', '2017-10-04 15:51:42'),
(4, 'etat_valid_devis', '2', 'L''etat où le devis est validé pour exploitation dans le contrat', 105, 1, '1', '2017-10-06 15:01:26', NULL, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
