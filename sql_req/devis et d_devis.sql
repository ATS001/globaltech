-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Jeu 14 Septembre 2017 à 00:20
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

DELIMITER $$
--
-- Procédures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_count`()
BEGIN
        SELECT COUNT(*) as cont FROM aemploi;
    END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `devis`
--

CREATE TABLE IF NOT EXISTS `devis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tkn_frm` varchar(32) DEFAULT NULL COMMENT 'Token Form insert',
  `reference` varchar(20) DEFAULT NULL,
  `id_client` int(11) DEFAULT NULL,
  `tva` varchar(1) DEFAULT 'O' COMMENT 'Soumis à la TVA',
  `id_commercial` int(11) DEFAULT NULL COMMENT 'commercial chargé du suivi',
  `date_devis` date DEFAULT NULL,
  `type_remise` varchar(1) DEFAULT 'P' COMMENT 'type de remise',
  `valeur_remise` double DEFAULT '0' COMMENT 'Valeur de la remise',
  `totalht` double DEFAULT '0' COMMENT 'total ht des articles',
  `totalttc` double unsigned DEFAULT '0' COMMENT 'total ttc des articles',
  `totaltva` double DEFAULT '0' COMMENT 'total tva des articles',
  `claus_comercial` text COMMENT 'clauses commercial devis',
  `etat` int(11) DEFAULT '0' COMMENT 'Etat devis defaut 0',
  `creusr` int(11) DEFAULT NULL,
  `credat` datetime DEFAULT NULL,
  `updusr` int(11) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_id_client` (`id_client`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=24 ;

--
-- Contenu de la table `devis`
--

INSERT INTO `devis` (`id`, `tkn_frm`, `reference`, `id_client`, `tva`, `id_commercial`, `date_devis`, `type_remise`, `valeur_remise`, `totalht`, `totalttc`, `totaltva`, `claus_comercial`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(11, NULL, 'DEV_0_2017', 1, NULL, 1, '2017-09-13', 'P', NULL, 1455454, 1746544.8, 291090.8, 'Paiement 100% àla commande pour', 0, 1, '2017-09-13 01:50:08', NULL, NULL),
(12, NULL, 'DEV_12_2017', 8, NULL, 1, '2017-09-13', 'P', NULL, 7277270, 8732724, 1455454, 'Paiement 100% à la commande pour', 0, 1, '2017-09-13 01:55:43', NULL, NULL),
(13, '5e4d828bb3803694724801c841ecfd02', 'DEV_13_2017', 2, NULL, 1, '2017-09-13', 'P', NULL, 1455454, 1746544.8, 291090.8, 'Paiement 100% à la commande pour', 0, 1, '2017-09-13 01:56:53', NULL, NULL),
(21, 'ef4c85458e75208c1f1028d733ef3450', 'DEV_21_2017', 8, NULL, 1, '2017-09-05', 'P', NULL, 1455466, 1746559.2, 291093.2, 'Paiement 100% à la commande pour', 0, 1, '2017-09-13 13:12:27', 1, '2017-09-13 16:35:45'),
(22, '6b2ab442af25dac3771500a7faf20a25', 'DEV_22_2017', 2, NULL, 1, '2017-09-13', 'P', NULL, 14554612, 17465534.4, 2910922.4, 'Paiement 100% à la commande pour tester la modif<br>', 0, 1, '2017-09-13 13:26:53', 1, '2017-09-13 22:32:17'),
(23, '93a0f6bc29f45532f90f93e38c386447', 'DEV_23_2017', 1, 'N', 1, '2017-09-14', 'P', NULL, 1455454, 1455454, 0, 'Paiement 100% à la commande pour', 0, 1, '2017-09-14 00:10:33', 1, '2017-09-14 00:11:59');

-- --------------------------------------------------------

--
-- Structure de la table `d_devis`
--

CREATE TABLE IF NOT EXISTS `d_devis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order` int(5) DEFAULT NULL,
  `id_devis` int(11) DEFAULT NULL,
  `tkn_frm` varchar(32) NOT NULL,
  `id_produit` int(11) DEFAULT NULL,
  `ref_produit` varchar(20) DEFAULT NULL,
  `designation` varchar(40) DEFAULT NULL,
  `qte` int(11) DEFAULT NULL,
  `prix_unitaire` double DEFAULT NULL,
  `type_remise` varchar(1) DEFAULT NULL,
  `remise_valeur` double DEFAULT NULL,
  `tva` double DEFAULT NULL,
  `prix_ht` double DEFAULT NULL,
  `prix_ttc` double DEFAULT NULL,
  `total_ht` double DEFAULT NULL,
  `total_ttc` double DEFAULT NULL,
  `total_tva` double DEFAULT NULL,
  `creusr` varchar(50) DEFAULT NULL,
  `credat` datetime DEFAULT NULL,
  `updusr` varchar(50) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_devis` (`tkn_frm`),
  KEY `fk_id_produit` (`id_produit`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=146 ;

--
-- Contenu de la table `d_devis`
--

INSERT INTO `d_devis` (`id`, `order`, `id_devis`, `tkn_frm`, `id_produit`, `ref_produit`, `designation`, `qte`, `prix_unitaire`, `type_remise`, `remise_valeur`, `tva`, `prix_ht`, `prix_ttc`, `total_ht`, `total_ttc`, `total_tva`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(90, 1, NULL, 'f93598d564d156e4b839127862639853', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(91, 1, 12, '31750c0a121e6dcb693a4d1ca494e2c6', 4, 'Ref2', 'Article 2 produit VAST', 5, 1455454, 'P', 0, 0, NULL, NULL, 7277270, 8732724, 1455454, '1', NULL, NULL, NULL),
(92, 1, 13, '5e4d828bb3803694724801c841ecfd02', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(93, 1, 17, '410fca9ef57b94091cfd14b4b0d86b85', 4, 'Ref2', 'Article 2 produit VAST', 2, 1455454, 'P', 0, 0, NULL, NULL, 2910908, 3493089.6, 582181.6, '1', NULL, NULL, NULL),
(94, 2, 17, '410fca9ef57b94091cfd14b4b0d86b85', 3, 'Ref1', 'X1', 1, 12.3, 'P', 0, 0, NULL, NULL, 12, 14.76, 2.4, '1', NULL, NULL, NULL),
(95, 1, 18, '4e36902a40a10adcf735f00193d6d74c', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(96, 1, 19, '246d6dc9bcf6441c3e241293f434104f', 4, 'Ref2', 'Article 2 produit VAST', 1, 100, 'P', 0, 0, NULL, NULL, 100, 120, 20, '1', NULL, NULL, NULL),
(97, 1, NULL, '8381b6a6caaef6d41890bb15b17395ce', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(98, 1, NULL, 'f84dc7a524bd56f297b7f18bf5cdabe9', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(99, 1, NULL, '65111863547891d181b469314b035d1a', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(100, 1, NULL, 'b3a4ec11ceb0b70c0843569fd93c6728', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(101, 1, NULL, '03ffb5263d81d4b18f71c162b267a0e9', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(102, 1, NULL, '40d5d42538fb1ebaabca04f2064de888', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(103, 1, NULL, '7774affb65a7bbd39c6d89c5a46ce6f3', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(104, 1, NULL, '6e5d642de64732c9a6088800b1cbf1bf', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(105, 1, NULL, '0ca3ac386e924d77741fc31cf5af1f1b', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(106, 1, NULL, '2ea0c1c513c5ecb22f3de5a792b2e1f5', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(107, 1, NULL, '30b68c2cae5686750a08ff03ec66d201', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(108, 1, NULL, '3214283a1cbbb992d11503515038f69f', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(110, 1, NULL, '4f330c90501b40195caebe358fb346d7', 3, 'Ref1', 'X1', 1, 12.3, 'P', 0, 0, NULL, NULL, 12, 14.76, 2.4, '1', NULL, NULL, NULL),
(112, 1, NULL, '7fb42b1018cf0aaddf3000a3e21f0110', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(121, 1, NULL, '58579cbb8488714b6b9090d1f55b77d4', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(125, 2, NULL, '623245a3cfab7c5057c9620a4703354c', 3, 'Ref1', 'X1', 1, 12.3, 'P', 0, 0, NULL, NULL, 12, 14.76, 2.4, '1', NULL, NULL, NULL),
(126, 1, NULL, '40e21886f8088155718d0b2fa7ca92f7', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(128, 1, 21, 'ef4c85458e75208c1f1028d733ef3450', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(129, 2, 21, 'ef4c85458e75208c1f1028d733ef3450', 3, 'Ref1', 'X1', 1, 12.3, 'P', 0, 0, NULL, NULL, 12, 14.76, 2.4, '1', NULL, NULL, NULL),
(135, 1, NULL, 'e9ed0fdda7b7a3f297fcd58b06152476', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(136, 2, 22, '6b2ab442af25dac3771500a7faf20a25', 3, 'Ref1', 'X1', 1, 12.3, 'P', 0, 0, NULL, NULL, 12, 14.76, 2.4, '1', NULL, '1', '2017-09-13 23:01:28'),
(137, 3, 22, '6b2ab442af25dac3771500a7faf20a25', 4, 'Ref2', 'Article 2 produit VAST', 10, 1455454, 'P', 0, 0, NULL, NULL, 14554540, 17465448, 2910908, '1', NULL, '1', '2017-09-13 22:30:54'),
(138, 1, NULL, 'd41d8cd98f00b204e9800998ecf8427e', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(139, 1, NULL, 'f6d9e309945b1c475a7405d208e9d94a', 3, 'Ref1', 'X1', 3, 12.3, 'P', 0, 0, NULL, NULL, 36, 44.28, 7.2, '1', NULL, '1', '2017-09-13 22:44:47'),
(140, 2, NULL, 'f6d9e309945b1c475a7405d208e9d94a', 4, 'Ref2', 'Article 2 produit VAST', 14, 1455454, 'P', 0, 0, NULL, NULL, 20376356, 24451627.2, 4075271.2, '1', NULL, '1', '2017-09-13 22:52:58'),
(141, 1, NULL, 'd6c6e6fcb30ae88f8503b0c18fd8ee72', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(142, 1, NULL, 'cdfbb968a094366a25dd4e2f4f4cf4ab', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(143, 1, NULL, 'fa4fc037789db0a180080040e9d00862', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(144, 1, NULL, '25785014a6a41641f7e7454be6119477', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(145, 1, 23, '93a0f6bc29f45532f90f93e38c386447', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL);

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `devis`
--
ALTER TABLE `devis`
  ADD CONSTRAINT `fk_id_client` FOREIGN KEY (`id_client`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
