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
-- Structure de la table `ref_categories_produits`
--

CREATE TABLE IF NOT EXISTS `ref_categories_produits` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `categorie_produit` varchar(100) NOT NULL COMMENT 'CatÃ©gorie de produits',
  `etat` int(11) DEFAULT '0',
  `creusr` int(11) DEFAULT NULL,
  `credat` datetime DEFAULT CURRENT_TIMESTAMP,
  `updusr` int(11) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Contenu de la table `ref_categories_produits`
--

INSERT INTO `ref_categories_produits` (`id`, `categorie_produit`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(1, 'XY9999', 0, 1, '2017-08-25 16:45:54', 1, '2017-08-26 19:19:29'),
(2, 'YY', 0, 1, '2017-08-25 17:55:15', NULL, NULL),
(3, 'CCC', 0, 1, '2017-08-25 21:18:23', NULL, NULL),
(4, 'KKK', 0, 1, '2017-08-26 12:31:31', NULL, NULL),
(5, 'LLL', 0, 1, '2017-08-26 12:38:25', NULL, NULL),
(6, 'XL', 1, 1, '2017-08-26 12:38:40', 1, '2017-08-26 13:25:25'),
(7, 'PPPP', 0, 1, '2017-08-26 19:18:09', NULL, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
