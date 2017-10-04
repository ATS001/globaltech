-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 05, 2017 at 12:00 AM
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
-- Table structure for table `factures`
--

CREATE TABLE IF NOT EXISTS `factures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ref` varchar(50) DEFAULT NULL,
  `base_fact` char(1) DEFAULT NULL COMMENT 'C/D/B Contrat/Devis/BL',
  `total_ht` double DEFAULT NULL,
  `total_tva` double DEFAULT NULL,
  `total_ttc` double DEFAULT NULL,
  `total_paye` double DEFAULT NULL COMMENT 'Le total payé',
  `reste` double DEFAULT NULL COMMENT 'reste à payer',
  `client` varchar(100) DEFAULT NULL,
  `tva` double DEFAULT NULL,
  `facture_pdf` int(11) DEFAULT NULL,
  `idcontrat` int(11) DEFAULT NULL COMMENT 'Contrat',
  `iddevis` int(11) DEFAULT NULL COMMENT 'Devis',
  `idbl` int(11) DEFAULT NULL COMMENT 'Bon de livraison',
  `date_facture` date DEFAULT NULL,
  `etat` int(11) DEFAULT '0',
  `creusr` int(11) DEFAULT NULL,
  `credat` datetime DEFAULT CURRENT_TIMESTAMP,
  `updusr` int(11) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=32 ;

--
-- Dumping data for table `factures`
--

INSERT INTO `factures` (`id`, `ref`, `base_fact`, `total_ht`, `total_tva`, `total_ttc`, `total_paye`, `reste`, `client`, `tva`, `facture_pdf`, `idcontrat`, `iddevis`, `idbl`, `date_facture`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(1, 'GT-FACT-1', 'C', 1000, 200, 1200, 100, NULL, 'DCT', 20, 473, 15, 11, NULL, '2017-09-25', 1, 1, '2017-09-25 20:49:32', NULL, NULL),
(3, 'reef', 'C', 10, 0, 10, 100, -90, '1', NULL, 474, 16, NULL, NULL, '2017-09-29', 1, 1, '2017-09-29 20:07:16', NULL, NULL),
(4, 'reef', 'C', 10, 0, 10, 0, 10, NULL, NULL, 0, 16, NULL, NULL, '2017-09-29', 0, 1, '2017-09-29 20:08:48', NULL, NULL),
(5, 'reef', 'C', 10, 0, 10, 0, 10, 'DE111', NULL, 0, 16, NULL, NULL, '2017-09-29', 0, 1, '2017-09-29 20:09:27', NULL, NULL),
(6, 'LL', 'C', 10, 0, 10, 0, 10, 'DE111', NULL, 475, 16, NULL, NULL, '2017-09-29', 1, 1, '2017-09-29 20:47:08', NULL, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
