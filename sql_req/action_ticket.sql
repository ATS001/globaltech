-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Sam 21 Avril 2018 à 13:27
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
-- Structure de la table `action_ticket`
--

CREATE TABLE IF NOT EXISTS `action_ticket` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `id_ticket` int(11) DEFAULT NULL COMMENT 'Ticket',
  `message` text COMMENT 'Description',
  `date_action` date DEFAULT NULL COMMENT 'Date',
  `photo` int(11) DEFAULT NULL COMMENT 'Photo',
  `pj` int(11) DEFAULT NULL COMMENT 'Pièce jointe',
  `etat` int(11) DEFAULT '0',
  `creusr` int(11) DEFAULT NULL,
  `credat` datetime DEFAULT CURRENT_TIMESTAMP,
  `updusr` int(11) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

--
-- Contenu de la table `action_ticket`
--

INSERT INTO `action_ticket` (`id`, `id_ticket`, `message`, `date_action`, `photo`, `pj`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(18, 15, '<p>iiiid<br></p>', '2018-04-21', NULL, NULL, 0, 1, '2018-04-21 11:48:28', NULL, NULL),
(20, 15, '<p>Test pour aller vers detail directement<br></p>', '2018-04-21', NULL, NULL, 0, 1, '2018-04-21 12:56:23', NULL, NULL),
(22, 15, '<p>Test Action pour <br></p>', '2018-04-21', NULL, NULL, 0, 1, '2018-04-21 13:13:24', NULL, NULL),
(25, 15, '<p>Test PJ<br></p>', '2018-04-21', 564, 563, 0, 1, '2018-04-21 13:20:37', NULL, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
