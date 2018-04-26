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
-- Structure de la table `tickets`
--

CREATE TABLE IF NOT EXISTS `tickets` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `id_client` int(11) DEFAULT NULL COMMENT 'Client',
  `projet` varchar(200) DEFAULT NULL COMMENT 'Projet',
  `message` text COMMENT 'Message',
  `date_previs` date DEFAULT NULL COMMENT 'Date prévisionnelle',
  `date_realis` date DEFAULT NULL COMMENT 'Date de réalisation',
  `type_produit` int(11) DEFAULT NULL COMMENT 'Type produit',
  `categorie_produit` int(11) DEFAULT NULL COMMENT 'Catégorie produit',
  `id_produit` int(11) DEFAULT NULL COMMENT 'Produit',
  `id_technicien` int(11) DEFAULT NULL COMMENT 'Technicien',
  `date_affectation` date DEFAULT NULL COMMENT 'Date affectation',
  `etat` int(11) DEFAULT '0',
  `creusr` int(11) DEFAULT NULL,
  `credat` datetime DEFAULT CURRENT_TIMESTAMP,
  `updusr` int(11) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_client_ticket` (`id_client`),
  KEY `fk_cproduit_ticket` (`categorie_produit`),
  KEY `fk_tproduit_ticket` (`type_produit`),
  KEY `fk_user_ticket` (`id_technicien`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- Contenu de la table `tickets`
--

INSERT INTO `tickets` (`id`, `id_client`, `projet`, `message`, `date_previs`, `date_realis`, `type_produit`, `categorie_produit`, `id_produit`, `id_technicien`, `date_affectation`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(13, 27, 'DDD', '<p>XXXX<br></p>', '2018-04-19', NULL, 1, 8, NULL, NULL, NULL, 0, 1, '2018-04-12 22:44:06', 1, '2018-04-13 11:40:52'),
(14, 24, 'Farcha', '<p>Connexion lente<br></p><p><br><b>GOOD LUCK</b><br></p>', '2018-04-20', NULL, 3, 0, 25, NULL, NULL, 0, 1, '2018-04-13 11:50:15', 1, '2018-04-06 19:21:24'),
(15, 1, 'Tandrara', '<p>    Test de ce ticket<br></p>', '2018-04-09', NULL, 3, 0, 22, 2, '2018-04-09', 1, 1, '2018-04-09 11:21:04', 1, '2018-04-09 11:22:56');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
