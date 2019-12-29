-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  sam. 28 déc. 2019 à 13:27
-- Version du serveur :  5.7.26
-- Version de PHP :  5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `globaltech`
--

-- --------------------------------------------------------

--
-- Structure de la table `objectif_mensuels`
--

DROP TABLE IF EXISTS `objectif_mensuels`;
CREATE TABLE IF NOT EXISTS `objectif_mensuels` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `description` varchar(300) DEFAULT NULL COMMENT 'Désignation',
  `objectif` int(11) DEFAULT '0' COMMENT 'Objectif',
  `realise` int(11) DEFAULT '0' COMMENT 'Réalisation',
  `seuil` float DEFAULT '100' COMMENT 'Seuil',
  `montant_benif` int(11) DEFAULT NULL COMMENT 'Montant benifice',
  `id_commercial` int(11) DEFAULT NULL COMMENT 'ID Commercial',
  `date_s` date DEFAULT NULL COMMENT 'Date début',
  `date_e` date DEFAULT NULL COMMENT 'Date fin',
  `annee` int(4) DEFAULT NULL COMMENT 'Année',
  `mois` int(2) DEFAULT NULL COMMENT 'Mois',
  `etat` int(11) DEFAULT '0',
  `creusr` int(11) DEFAULT NULL,
  `credat` datetime DEFAULT CURRENT_TIMESTAMP,
  `updusr` int(11) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `objectif_mensuels`
--

INSERT INTO `objectif_mensuels` (`id`, `description`, `objectif`, `realise`, `seuil`, `montant_benif`, `id_commercial`, `date_s`, `date_e`, `annee`, `mois`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(1, 'Test Objectif mensuel', 2000000, 100000, 100, NULL, 13, '2019-12-11', '2019-12-26', 2019, NULL, 1, 1, '2019-12-11 23:02:51', 1, '2019-12-13 10:48:09'),
(2, 'Mon premier test mensuel', 100000, NULL, 100, NULL, 10, '2019-12-01', '2019-12-31', 2019, NULL, 1, 1, '2019-12-13 18:38:41', 1, '2019-12-16 18:05:21'),
(3, 'Objectif : décembre - 2019', 2900000, NULL, 100, NULL, 12, '2019-12-01', '2019-12-31', 2019, NULL, 1, 1, '2019-12-13 19:38:02', 1, '2019-12-16 18:04:03'),
(4, 'Objectif juillet - 2019', 2900000, NULL, 100, NULL, 12, '2019-07-01', '2019-07-31', 2019, NULL, 1, 1, '2019-12-13 19:41:18', 1, '2019-12-16 18:03:38'),
(5, 'Objectif : avril - 2019', 123, NULL, 100, NULL, 12, '2019-04-01', '2019-04-30', 2019, NULL, 1, 1, '2019-12-13 19:42:04', 1, '2019-12-16 18:03:27'),
(6, 'Objectif : décembre - 2019', 20000, NULL, 1.1, NULL, 1, '2019-12-01', '2019-12-31', 2019, NULL, 6, 1, '2019-12-14 10:17:52', 1, '2019-12-27 16:25:48'),
(7, 'Objectif : février - 2019', 299302, NULL, 1.6, NULL, 13, '2019-02-01', '2019-02-28', 2019, NULL, 1, 1, '2019-12-14 10:18:36', 1, '2019-12-14 09:29:40'),
(8, 'Objectif : janvier - 2019', 1000000, 0, 85, NULL, 15, '2019-01-01', '2019-01-31', 2019, NULL, 6, 1, '2019-12-16 21:28:00', 1, '2019-12-27 16:21:19'),
(9, 'Objectif : février - 2019', 1000000, 0, 85, NULL, 15, '2019-02-01', '2019-02-28', 2019, NULL, 0, 1, '2019-12-16 21:28:00', NULL, NULL),
(10, 'Objectif : mars - 2019', 1000000, 0, 85, NULL, 15, '2019-03-01', '2019-03-31', 2019, NULL, 0, 1, '2019-12-16 21:28:00', NULL, NULL),
(11, 'Objectif : avril - 2019', 1000000, 0, 85, NULL, 15, '2019-04-01', '2019-04-30', 2019, NULL, 0, 1, '2019-12-16 21:28:00', NULL, NULL),
(12, 'Objectif : mai - 2019', 1000000, 0, 85, NULL, 15, '2019-05-01', '2019-05-31', 2019, NULL, 0, 1, '2019-12-16 21:28:00', NULL, NULL),
(13, 'Objectif : juin - 2019', 1000000, 0, 85, NULL, 15, '2019-06-01', '2019-06-30', 2019, NULL, 0, 1, '2019-12-16 21:28:00', NULL, NULL),
(14, 'Objectif : juillet - 2019', 1000000, 0, 85, NULL, 15, '2019-07-01', '2019-07-31', 2019, NULL, 0, 1, '2019-12-16 21:28:00', NULL, NULL),
(15, 'Objectif : août - 2019', 1000000, 0, 85, NULL, 15, '2019-08-01', '2019-08-31', 2019, NULL, 0, 1, '2019-12-16 21:28:00', NULL, NULL),
(16, 'Objectif : septembre - 2019', 1000000, 0, 85, NULL, 15, '2019-09-01', '2019-09-30', 2019, NULL, 0, 1, '2019-12-16 21:28:00', NULL, NULL),
(17, 'Objectif : octobre - 2019', 1000000, 0, 85, NULL, 15, '2019-10-01', '2019-10-31', 2019, NULL, 0, 1, '2019-12-16 21:28:00', NULL, NULL),
(18, 'Objectif : novembre - 2019', 1000000, 0, 85, NULL, 15, '2019-11-01', '2019-11-30', 2019, NULL, 0, 1, '2019-12-16 21:28:00', NULL, NULL),
(19, 'Objectif : décembre - 2019', 1000000, 484515, 85, 100000, 15, '2019-12-01', '2019-12-31', 2019, 12, 5, 1, '2019-12-16 21:28:00', 1, '2019-12-28 12:07:12'),
(20, 'Objectif : janvier - 2019', 120000, 0, 67, NULL, 7, '2019-01-01', '2019-01-31', 2019, NULL, 0, 1, '2019-12-16 21:30:18', NULL, NULL),
(21, 'Objectif : février - 2019', 120000, 0, 67, NULL, 7, '2019-02-01', '2019-02-28', 2019, NULL, 0, 1, '2019-12-16 21:30:18', NULL, NULL),
(22, 'Objectif : mars - 2019', 120000, 0, 67, NULL, 7, '2019-03-01', '2019-03-31', 2019, NULL, 0, 1, '2019-12-16 21:30:19', NULL, NULL),
(23, 'Objectif : avril - 2019', 120000, 0, 67, NULL, 7, '2019-04-01', '2019-04-30', 2019, NULL, 0, 1, '2019-12-16 21:30:19', NULL, NULL),
(24, 'Objectif : mai - 2019', 120000, 0, 67, NULL, 7, '2019-05-01', '2019-05-31', 2019, NULL, 0, 1, '2019-12-16 21:30:19', NULL, NULL),
(25, 'Objectif : juin - 2019', 120000, 0, 67, NULL, 7, '2019-06-01', '2019-06-30', 2019, NULL, 0, 1, '2019-12-16 21:30:19', NULL, NULL),
(26, 'Objectif : juillet - 2019', 120000, 0, 67, NULL, 7, '2019-07-01', '2019-07-31', 2019, NULL, 0, 1, '2019-12-16 21:30:19', NULL, NULL),
(27, 'Objectif : août - 2019', 120000, 0, 67, NULL, 7, '2019-08-01', '2019-08-31', 2019, NULL, 0, 1, '2019-12-16 21:30:19', NULL, NULL),
(28, 'Objectif : septembre - 2019', 120000, 0, 67, NULL, 7, '2019-09-01', '2019-09-30', 2019, NULL, 0, 1, '2019-12-16 21:30:19', NULL, NULL),
(29, 'Objectif : octobre - 2019', 120000, 0, 67, NULL, 7, '2019-10-01', '2019-10-31', 2019, NULL, 0, 1, '2019-12-16 21:30:19', NULL, NULL),
(30, 'Objectif : novembre - 2019', 120000, 88000, 67, 2678809, 7, '2019-11-01', '2019-11-30', 2019, 11, 3, 1, '2019-12-16 21:30:19', 1, '2019-12-16 21:17:31'),
(31, 'Objectif : décembre - 2019', 120000, 0, 67, NULL, 7, '2019-12-01', '2019-12-31', 2019, 12, 2, 1, '2019-12-16 21:30:19', NULL, NULL),
(32, 'Objectif : janvier - 2019', 1000000, 0, 86, NULL, 12, '2019-01-01', '2019-01-31', 2019, 1, 0, 1, '2019-12-22 12:35:36', NULL, NULL),
(33, 'Objectif : février - 2019', 1000000, 0, 86, NULL, 12, '2019-02-01', '2019-02-28', 2019, 2, 0, 1, '2019-12-22 12:35:36', NULL, NULL),
(34, 'Objectif : mars - 2019', 1000000, 0, 86, NULL, 12, '2019-03-01', '2019-03-31', 2019, 3, 0, 1, '2019-12-22 12:35:36', NULL, NULL),
(35, 'Objectif : décembre - 2019', 2000000, 484515, 85, NULL, 17, '2019-12-01', '2019-12-31', 2019, 12, 2, 1, '2019-12-22 18:24:05', 1, '2019-12-22 17:43:15');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
