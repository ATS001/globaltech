-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  sam. 28 déc. 2019 à 13:30
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
-- Structure de la table `sys_setting`
--



--
-- Déchargement des données de la table `sys_setting`
--

INSERT INTO `sys_setting` (`id`, `key`, `value`, `comment`, `modul`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(33, 'etat_objectif_mensuel', '{"objectif_wait":"0", "objectif_encour":"1", "objectif_stop":"2", "objectif_atteint":"3", "objectif_non":"4",  "objectif_force":"5", "objectif_suspendu":"6", "objectif_paye":"7"}', 'Etat Objectif mensuel', 178, 1, '1', '2019-12-16 19:50:19', '1', '2019-12-27 16:01:21');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
