-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 26, 2017 at 08:16 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `globaltech`
--

-- --------------------------------------------------------

--
-- Table structure for table `produits`
--

CREATE TABLE IF NOT EXISTS `produits` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `ref` varchar(100) DEFAULT NULL COMMENT 'Reference produit',
  `designation` varchar(100) DEFAULT NULL COMMENT 'DÃ©signation',
  `pu` double DEFAULT NULL COMMENT 'Prix unitaire',
  `stock_min` int(11) DEFAULT NULL COMMENT 'Stock minimal',
  `idcategorie` int(11) DEFAULT NULL COMMENT 'CatÃ©gorie',
  `iduv` int(11) DEFAULT NULL COMMENT 'UnitÃ© de vente',
  `idtype` int(11) DEFAULT NULL COMMENT 'Type produit',
  `etat` int(11) DEFAULT '0',
  `creusr` int(11) DEFAULT NULL,
  `credat` datetime DEFAULT CURRENT_TIMESTAMP,
  `updusr` int(11) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_produit_categorie` (`idcategorie`),
  KEY `fk_produit_uv` (`iduv`),
  KEY `fk_produit_type` (`idtype`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `produits`
--

INSERT INTO `produits` (`id`, `ref`, `designation`, `pu`, `stock_min`, `idcategorie`, `iduv`, `idtype`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(3, 'Ref1', 'X1', 12.3, 3, 3, 2, 2, 0, 1, '2017-08-26 20:01:19', NULL, NULL);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `produits`
--
ALTER TABLE `produits`
  ADD CONSTRAINT `fk_produit_uv` FOREIGN KEY (`iduv`) REFERENCES `ref_unites_vente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_produit_categorie` FOREIGN KEY (`idcategorie`) REFERENCES `ref_categories_produits` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_produit_type` FOREIGN KEY (`idtype`) REFERENCES `ref_types_produits` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
