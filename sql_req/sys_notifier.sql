-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Jeu 21 Septembre 2017 à 23:04
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
-- Structure de la table `sys_notifier`
--

CREATE TABLE IF NOT EXISTS `sys_notifier` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id line',
  `app` varchar(25) DEFAULT NULL COMMENT 'app task',
  `table` varchar(25) DEFAULT NULL COMMENT 'table of app',
  PRIMARY KEY (`id`),
  KEY `fk_app_task` (`app`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Table des notification app' AUTO_INCREMENT=6 ;

--
-- Contenu de la table `sys_notifier`
--

INSERT INTO `sys_notifier` (`id`, `app`, `table`) VALUES
(1, 'user', 'users_sys'),
(2, 'devis', 'devis'),
(4, 'contrats', 'contrats'),
(5, 'clients', 'clients');

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `sys_notifier`
--
ALTER TABLE `sys_notifier`
  ADD CONSTRAINT `fk_app_task` FOREIGN KEY (`app`) REFERENCES `task` (`app`) ON DELETE CASCADE ON UPDATE CASCADE;

 UPDATE task set task.file = 'notifier' where app = 'notif';	
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
