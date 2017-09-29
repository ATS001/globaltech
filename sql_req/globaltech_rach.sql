-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Ven 29 Septembre 2017 à 16:38
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `globaltech_rach`
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
-- Structure de la table `archive`
--

CREATE TABLE IF NOT EXISTS `archive` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID SYS',
  `doc` varchar(120) DEFAULT NULL COMMENT 'lien document',
  `titr` varchar(300) DEFAULT NULL COMMENT 'Titre document',
  `modul` varchar(35) NOT NULL COMMENT 'le module de document',
  `table` varchar(35) DEFAULT NULL COMMENT 'table de modul',
  `idm` int(11) NOT NULL COMMENT 'ID pour Module',
  `service` int(11) NOT NULL COMMENT 'service',
  `type` varchar(10) DEFAULT NULL COMMENT 'type de document',
  `creusr` int(11) NOT NULL COMMENT 'Add by',
  `credat` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'Dat insert',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Table Archives' AUTO_INCREMENT=427 ;

--
-- Contenu de la table `archive`
--

INSERT INTO `archive` (`id`, `doc`, `titr`, `modul`, `table`, `idm`, `service`, `type`, `creusr`, `credat`) VALUES
(1, './upload/users/1/photo_1.jpg', 'Photo de profile de Admin', '0', NULL, 1, 1, 'image', 1, '2017-03-27 12:22:39'),
(9, './upload/users/1/form_1.pdf', 'Formulaire  de Adamou  Adamou', 'users', 'users_sys', 7, 1, 'Document', 1, '2017-04-07 11:34:03'),
(63, './upload/useres/4/photo_4.jpg', 'Photo de profile de Iraimadji  Iraimadji', 'users_sys', 'photo', 4, 1, 'Document', 1, '2017-04-03 15:36:02'),
(64, './upload/permissionnaires/1/pj_1.pdf', 'Formulaire d''enregistrement Permissionnaire Afrijeux', 'Permissionnaire', 'permissionnaires', 1, 2, 'Document', 4, '2017-04-05 14:17:41'),
(65, './upload/permissionnaires/2/pj_2.pdf', 'Formulaire d''enregistrement Permissionnaire Programme d''appui à l''ordonnateur National', 'Permissionnaire', 'permissionnaires', 2, 2, 'Document', 4, '2017-04-05 14:25:41'),
(66, './upload/permissionnaires/3/pj_3.pdf', 'Formulaire d''enregistrement Permissionnaire CABINET HENRY ERNST et YOUNG', 'Permissionnaire', 'permissionnaires', 3, 2, 'Document', 4, '2017-04-05 14:38:48'),
(67, './upload/permissionnaires/4/pj_4.pdf', 'Formulaire d''enregistrement Permissionnaire Société National de Ciment', 'Permissionnaire', 'permissionnaires', 4, 2, 'Document', 4, '2017-04-05 14:45:48'),
(68, './upload/permissionnaires/5/pj_5.pdf', 'Formulaire d''enregistrement Permissionnaire Hydrocarbone', 'Permissionnaire', 'permissionnaires', 5, 2, 'Document', 4, '2017-04-05 14:57:27'),
(69, './upload/permissionnaires/6/pj_6.pdf', 'Formulaire d''enregistrement Permissionnaire SOGEA SATOM', 'Permissionnaire', 'permissionnaires', 6, 2, 'Document', 4, '2017-04-05 15:06:19'),
(70, './upload/permissionnaires/7/pj_7.pdf', 'Formulaire d''enregistrement Permissionnaire GEODIS PROJECTS CHAD SA', 'Permissionnaire', 'permissionnaires', 7, 2, 'Document', 4, '2017-04-05 15:17:28'),
(71, './upload/permissionnaires/8/pj_8.pdf', 'Formulaire d''enregistrement Permissionnaire ORGANISATION HUMANITAIRE', 'Permissionnaire', 'permissionnaires', 8, 2, 'Document', 4, '2017-04-05 15:32:53'),
(72, './upload/permissionnaires/9/pj_9.pdf', 'Formulaire d''enregistrement Permissionnaire societe generale des banque', 'Permissionnaire', 'permissionnaires', 9, 2, 'Document', 4, '2017-04-05 15:42:08'),
(73, './upload/permissionnaires/10/pj_10.pdf', 'Formulaire d''enregistrement Permissionnaire commercial bank tchad', 'Permissionnaire', 'permissionnaires', 10, 2, 'Document', 4, '2017-04-05 15:51:47'),
(74, './upload/permissionnaires/11/pj_11.pdf', 'Formulaire d''enregistrement Permissionnaire saonet', 'Permissionnaire', 'permissionnaires', 11, 2, 'Document', 4, '2017-04-05 15:58:48'),
(75, './upload/permissionnaires/12/pj_12.pdf', 'Formulaire d''enregistrement Permissionnaire BIVAK TCHAD', 'Permissionnaire', 'permissionnaires', 12, 2, 'Document', 4, '2017-04-05 16:06:03'),
(76, './upload/permissionnaires/13/pj_13.pdf', 'Formulaire d''enregistrement Permissionnaire Concern WorldWide', 'Permissionnaire', 'permissionnaires', 13, 2, 'Document', 4, '2017-04-05 16:12:22'),
(77, './upload/permissionnaires/14/pj_14.pdf', 'Formulaire d''enregistrement Permissionnaire Transval Tchad', 'Permissionnaire', 'permissionnaires', 14, 2, 'Document', 4, '2017-04-05 16:18:13'),
(78, './upload/permissionnaires/15/pj_15.pdf', 'Formulaire d''enregistrement Permissionnaire Mairie Centrale', 'Permissionnaire', 'permissionnaires', 15, 2, 'Document', 4, '2017-04-05 16:22:02'),
(79, './upload/permissionnaires/16/pj_16.pdf', 'Formulaire d''enregistrement Permissionnaire Star security', 'Permissionnaire', 'permissionnaires', 16, 2, 'Document', 4, '2017-04-05 16:30:59'),
(80, './upload/permissionnaires/17/pj_17.pdf', 'Formulaire d''enregistrement Permissionnaire Residence Hotel', 'Permissionnaire', 'permissionnaires', 17, 2, 'Document', 4, '2017-04-05 16:34:32'),
(81, './upload/useres/5/photo_5.jpg', 'Photo de profile de Djourbe   Djourbe ', 'users_sys', 'photo', 5, 1, 'Document', 1, '2017-04-06 11:59:22'),
(82, './upload/useres/6/photo_6.jpg', 'Photo de profile de Ibrahim  Ibrahim', 'users_sys', 'photo', 6, 1, 'Document', 1, '2017-04-06 12:01:09'),
(83, './upload/permissionnaires/18/pj_18.pdf', 'Formulaire d''enregistrement Permissionnaire INSEED', 'Permissionnaire', 'permissionnaires', 18, 2, 'Document', 6, '2017-04-06 14:55:17'),
(84, './upload/permissionnaires/19/pj_19.pdf', 'Formulaire d''enregistrement Permissionnaire MSF SUISSE', 'Permissionnaire', 'permissionnaires', 19, 2, 'Document', 6, '2017-04-06 15:19:14'),
(85, './upload/users/7/photo_7.jpg', 'Photo de profile de Adamou  Adamou', 'users', 'users_sys', 7, 1, 'Document', 1, '2017-04-07 11:34:03'),
(86, './upload/users/7/signature_7.png', 'signature  de Adamou  Adamou', 'users', 'users_sys', 7, 1, 'Document', 1, '2017-04-07 11:34:03'),
(88, './upload/station_vsat/1/pj_1.pdf', 'Formulaire d''enregistrement Station VSAT NDJAMENA SIEGE', 'vsat', 'vsat_stations', 1, 2, 'Document', 4, '2017-04-07 12:57:03'),
(89, './upload/station_vsat/2/pj_2.pdf', 'Formulaire d''enregistrement Station VSAT MOUNDOU', 'vsat', 'vsat_stations', 2, 2, 'Document', 5, '2017-04-10 11:46:10'),
(90, './upload/station_vsat/3/pj_3.pdf', 'Formulaire d''enregistrement Station VSAT N''djamena', 'vsat', 'vsat_stations', 3, 2, 'Document', 5, '2017-04-10 12:00:37'),
(94, './upload/station_vsat/4/pj_4.pdf', 'Formulaire d''enregistrement Station VSAT SGT MOUNDOU', 'vsat', 'vsat_stations', 4, 2, 'Document', 5, '2017-04-10 12:20:30'),
(95, './upload/station_vsat/5/pj_5.pdf', 'Formulaire d''enregistrement Station VSAT SGT KOUMRA', 'vsat', 'vsat_stations', 5, 2, 'Document', 5, '2017-04-10 12:28:30'),
(96, './upload/station_vsat/6/pj_6.pdf', 'Formulaire d''enregistrement Station VSAT BIVAC N''DJAMENA', 'vsat', 'vsat_stations', 6, 2, 'Document', 5, '2017-04-10 12:54:15'),
(97, './upload/station_vsat/6/4fe58cae36d2dd3633781d5cbbe7fe92.jpg', 'Vsat BIVAC -vsat_6', 'vsat', 'vsat_stations', 6, 2, 'Image', 5, '2017-04-10 12:54:15'),
(98, './upload/station_vsat/6/98f09f31ea4adf81fc4e185c0a0eee14.jpg', 'MODEM BIVAC -vsat_6', 'vsat', 'vsat_stations', 6, 2, 'Image', 5, '2017-04-10 12:54:15'),
(99, './upload/station_vsat/7/pj_7.pdf', 'Formulaire d''enregistrement Station VSAT SGT-SARH', 'vsat', 'vsat_stations', 7, 2, 'Document', 6, '2017-04-10 15:07:44'),
(100, './upload/station_vsat/8/pj_8.pdf', 'Formulaire d''enregistrement Station VSAT GEODIS N''DJAMENA', 'vsat', 'vsat_stations', 8, 2, 'Document', 5, '2017-04-10 15:29:07'),
(101, './upload/station_vsat/8/fb96830ed56a66d74c3825e3fc0080af.jpg', 'Vsat GEODIS -vsat_8', 'vsat', 'vsat_stations', 8, 2, 'Image', 5, '2017-04-10 15:29:07'),
(102, './upload/station_vsat/8/d9484ad148032aac985de9923aafeaa1.jpg', 'Modem GEODIS -vsat_8', 'vsat', 'vsat_stations', 8, 2, 'Image', 5, '2017-04-10 15:29:07'),
(103, './upload/station_vsat/9/pj_9.pdf', 'Formulaire d''enregistrement Station VSAT CBT SIEGE N''DJAMENA', 'vsat', 'vsat_stations', 9, 2, 'Document', 5, '2017-04-10 15:48:04'),
(107, './upload/station_vsat/10/pj_10.pdf', 'Formulaire d''enregistrement Station VSAT SGT-PALA', 'vsat', 'vsat_stations', 10, 2, 'Document', 6, '2017-04-10 15:50:52'),
(108, './upload/permissionnaires/20/pj_20.pdf', 'Formulaire d''enregistrement Permissionnaire COMPAGNIE SUCRIERE DU TCHAD', 'Permissionnaire', 'permissionnaires', 20, 2, 'Document', 5, '2017-04-10 16:28:41'),
(109, './upload/station_vsat/11/pj_11.pdf', 'Formulaire d''enregistrement Station VSAT SaoNet ndjamena1', 'vsat', 'vsat_stations', 11, 2, 'Document', 6, '2017-04-10 16:50:49'),
(110, './upload/station_vsat/12/pj_12.pdf', 'Formulaire d''enregistrement Station VSAT SGT Kelo', 'vsat', 'vsat_stations', 12, 2, 'Document', 7, '2017-04-11 11:18:52'),
(111, './upload/station_vsat/13/pj_13.pdf', 'Formulaire d''enregistrement Station VSAT CST Farcha', 'vsat', 'vsat_stations', 13, 2, 'Document', 7, '2017-04-11 11:49:50'),
(112, './upload/station_vsat/13/06211a5174a443e3d31776ead3ca1329.jpg', 'Antenne cst -vsat_13', 'vsat', 'vsat_stations', 13, 2, 'Image', 7, '2017-04-11 11:49:50'),
(113, './upload/station_vsat/13/ea45b904027966d13a6a264b6de35559.jpg', 'photo Modem CSt -vsat_13', 'vsat', 'vsat_stations', 13, 2, 'Image', 7, '2017-04-11 11:49:50'),
(114, './upload/station_vsat/14/pj_14.pdf', 'Formulaire d''enregistrement Station VSAT Siege ISSEED N''djamena', 'vsat', 'vsat_stations', 14, 2, 'Document', 5, '2017-04-11 13:15:01'),
(115, './upload/station_vsat/14/20009beda194e8d61ca50a3fadcceb2d.jpg', 'Antenne ISSEED -vsat_14', 'vsat', 'vsat_stations', 14, 2, 'Image', 5, '2017-04-11 13:15:01'),
(116, './upload/station_vsat/15/pj_15.pdf', 'Formulaire d''enregistrement Station VSAT SGT ABECHE', 'vsat', 'vsat_stations', 15, 2, 'Document', 5, '2017-04-11 13:30:54'),
(117, './upload/station_vsat/16/pj_16.pdf', 'Formulaire d''enregistrement Station VSAT SGT Mongo', 'vsat', 'vsat_stations', 16, 2, 'Document', 7, '2017-04-11 13:52:29'),
(118, './upload/station_vsat/17/pj_17.pdf', 'Formulaire d''enregistrement Station VSAT SGT Bongor', 'vsat', 'vsat_stations', 17, 2, 'Document', 7, '2017-04-11 14:12:54'),
(119, './upload/station_vsat/18/pj_18.pdf', 'Formulaire d''enregistrement Station VSAT MSF SUISSE N''djamena', 'vsat', 'vsat_stations', 18, 2, 'Document', 6, '2017-04-11 15:35:04'),
(120, './upload/station_vsat/19/pj_19.pdf', 'Formulaire d''enregistrement Station VSAT AFRIJEUX N''djamena', 'vsat', 'vsat_stations', 19, 2, 'Document', 5, '2017-04-12 10:32:06'),
(121, './upload/station_vsat/19/68a6f4821738b29fe87b3ab0ac0544ad.jpg', 'Modem Afrijeux -vsat_19', 'vsat', 'vsat_stations', 19, 2, 'Image', 5, '2017-04-12 10:32:06'),
(122, './upload/station_vsat/19/f4d6d59f2e0813cad02f5ab9e699f1dd.jpg', 'Antenne Afrijeux -vsat_19', 'vsat', 'vsat_stations', 19, 2, 'Image', 5, '2017-04-12 10:32:06'),
(123, './upload/station_vsat/20/pj_20.pdf', 'Formulaire d''enregistrement Station VSAT MSF BAGASSOLA', 'vsat', 'vsat_stations', 20, 2, 'Document', 5, '2017-04-12 10:59:05'),
(124, './upload/station_vsat/21/pj_21.pdf', 'Formulaire d''enregistrement Station VSAT MSF BOL', 'vsat', 'vsat_stations', 21, 2, 'Document', 5, '2017-04-12 11:17:04'),
(125, './upload/station_vsat/22/pj_22.pdf', 'Formulaire d''enregistrement Station VSAT CST BANDA', 'vsat', 'vsat_stations', 22, 2, 'Document', 5, '2017-04-12 11:40:30'),
(126, './upload/permissionnaires/21/pj_21.pdf', 'Formulaire d''enregistrement Permissionnaire GAB Security', 'Permissionnaire', 'permissionnaires', 21, 2, 'Document', 4, '2017-04-12 12:30:08'),
(127, './upload/permissionnaires/22/pj_22.pdf', 'Formulaire d''enregistrement Permissionnaire OPERATION MEDICALE HUMANITAIRE', 'Permissionnaire', 'permissionnaires', 22, 2, 'Document', 7, '2017-04-13 10:50:56'),
(128, './upload/permissionnaires/23/pj_23.pdf', 'Formulaire d''enregistrement Permissionnaire MIRACLE TELECOM TCHAD', 'Permissionnaire', 'permissionnaires', 23, 2, 'Document', 7, '2017-04-13 10:59:53'),
(129, './upload/permissionnaires/24/pj_24.pdf', 'Formulaire d''enregistrement Permissionnaire HUAWEI TECHNOLOGIES', 'Permissionnaire', 'permissionnaires', 24, 2, 'Document', 7, '2017-04-13 13:51:54'),
(130, './upload/permissionnaires/25/pj_25.pdf', 'Formulaire d''enregistrement Permissionnaire BANQUE AGRICOLE ET COMMERCIALE', 'Permissionnaire', 'permissionnaires', 25, 2, 'Document', 5, '2017-04-13 15:43:10'),
(131, './upload/station_vsat/23/pj_23.pdf', 'Formulaire d''enregistrement Station VSAT SIEGE BAC N''DJAMENA', 'vsat', 'vsat_stations', 23, 2, 'Document', 5, '2017-04-13 16:07:39'),
(132, './upload/station_vsat/23/fe052a05713a2f715b8022fee53aecec.jpg', 'LNB BAC -vsat_23', 'vsat', 'vsat_stations', 23, 2, 'Image', 5, '2017-04-13 16:07:39'),
(133, './upload/station_vsat/23/b53ac50b7d3dc7354bf1a5adaf870614.jpg', 'Modem BAC -vsat_23', 'vsat', 'vsat_stations', 23, 2, 'Image', 5, '2017-04-13 16:07:39'),
(134, './upload/station_vsat/23/2a1d27c9519cc426efe12c6250c186c8.jpg', 'Atenne BAC -vsat_23', 'vsat', 'vsat_stations', 23, 2, 'Image', 5, '2017-04-13 16:07:39'),
(135, './upload/station_vsat/24/pj_24.pdf', 'Formulaire d''enregistrement Station VSAT MSF SIEGE N''DJAMENA', 'vsat', 'vsat_stations', 24, 2, 'Document', 5, '2017-04-13 16:26:22'),
(136, './upload/station_vsat/25/pj_25.pdf', 'Formulaire d''enregistrement Station VSAT MSF HOLL AMTIMAN1', 'vsat', 'vsat_stations', 25, 2, 'Document', 6, '2017-04-14 10:45:09'),
(137, './upload/station_vsat/26/pj_26.pdf', 'Formulaire d''enregistrement Station VSAT MSF HOLLANDE AMTIMANE 2', 'vsat', 'vsat_stations', 26, 2, 'Document', 6, '2017-04-14 11:02:09'),
(138, './upload/permissionnaires/26/pj_26.pdf', 'Formulaire d''enregistrement Permissionnaire Banque Sahélo Saharienne pour l''Investissement et le Commerce', 'Permissionnaire', 'permissionnaires', 26, 2, 'Document', 5, '2017-04-18 14:29:31'),
(139, './upload/station_vsat/27/pj_27.pdf', 'Formulaire d''enregistrement Station VSAT BSIC N''DJAMENA', 'vsat', 'vsat_stations', 27, 2, 'Document', 6, '2017-04-19 12:50:58'),
(140, './upload/station_vsat/28/pj_28.pdf', 'Formulaire d''enregistrement Station VSAT Vsat Hôtel residence', 'vsat', 'vsat_stations', 28, 2, 'Document', 6, '2017-04-19 12:57:50'),
(141, './upload/station_vsat/29/pj_29.pdf', 'Formulaire d''enregistrement Station VSAT Vsat BSIC Moundou', 'vsat', 'vsat_stations', 29, 2, 'Document', 6, '2017-04-19 13:05:33'),
(142, './upload/station_vsat/30/pj_30.pdf', 'Formulaire d''enregistrement Station VSAT Vsat BSIC BOL', 'vsat', 'vsat_stations', 30, 2, 'Document', 6, '2017-04-19 13:12:54'),
(143, './upload/station_vsat/31/pj_31.pdf', 'Formulaire d''enregistrement Station VSAT ISLAMIC RELIEF N''DJAMENA', 'vsat', 'vsat_stations', 31, 2, 'Document', 5, '2017-04-20 11:29:42'),
(144, './upload/station_vsat/32/pj_32.pdf', 'Formulaire d''enregistrement Station VSAT SONACIM PALA', 'vsat', 'vsat_stations', 32, 2, 'Document', 5, '2017-04-20 11:46:08'),
(145, './upload/station_vsat/33/pj_33.pdf', 'Formulaire d''enregistrement Station VSAT PAON/FED N''DJAMENA', 'vsat', 'vsat_stations', 33, 2, 'Document', 5, '2017-04-20 12:05:56'),
(146, './upload/station_vsat/33/5a44da1890ef16bd61fa088c5ad8683e.jpg', 'ATENNE FED -vsat_33', 'vsat', 'vsat_stations', 33, 2, 'Image', 5, '2017-04-20 12:05:56'),
(147, './upload/station_vsat/33/f283d93431fa02ecc09d970a0f74592f.jpg', 'MODEM FED -vsat_33', 'vsat', 'vsat_stations', 33, 2, 'Image', 5, '2017-04-20 12:05:56'),
(148, './upload/station_vsat/33/8f97b90e4678ecb19769e9c7d273103e.jpg', 'LNB ET RADIO FED -vsat_33', 'vsat', 'vsat_stations', 33, 2, 'Image', 5, '2017-04-20 12:05:56'),
(149, './upload/station_vsat/34/pj_34.pdf', 'Formulaire d''enregistrement Station VSAT DGT DOBA', 'vsat', 'vsat_stations', 34, 2, 'Document', 5, '2017-04-20 12:14:59'),
(150, './upload/station_vsat/35/pj_35.pdf', 'Formulaire d''enregistrement Station VSAT SGT MOUSSORO', 'vsat', 'vsat_stations', 35, 2, 'Document', 5, '2017-04-20 12:23:07'),
(151, './upload/station_vsat/36/pj_36.pdf', 'Formulaire d''enregistrement Station VSAT HUAWEI N''DJAMENA', 'vsat', 'vsat_stations', 36, 2, 'Document', 5, '2017-04-20 12:54:41'),
(152, './upload/permissionnaires/27/pj_27.pdf', 'Formulaire d''enregistrement Permissionnaire Computer GOLFE TCHAD', 'Permissionnaire', 'permissionnaires', 27, 2, 'Document', 7, '2017-04-24 14:34:37'),
(153, './upload/permissionnaires/28/pj_28.pdf', 'Formulaire d''enregistrement Permissionnaire CICR Orgonisation internationale humanitaire', 'Permissionnaire', 'permissionnaires', 28, 2, 'Document', 7, '2017-04-24 15:12:10'),
(154, './upload/station_vsat/37/pj_37.pdf', 'Formulaire d''enregistrement Station VSAT Delegation N''djamena A1', 'vsat', 'vsat_stations', 37, 2, 'Document', 7, '2017-04-24 15:52:32'),
(158, './upload/station_vsat/38/pj_38.pdf', 'Formulaire d''enregistrement Station VSAT Delegation N''djamena A2', 'vsat', 'vsat_stations', 38, 2, 'Document', 7, '2017-04-24 16:14:56'),
(162, './upload/station_vsat/39/pj_39.pdf', 'Formulaire d''enregistrement Station VSAT N''djamena DRS', 'vsat', 'vsat_stations', 39, 2, 'Document', 7, '2017-04-24 16:31:11'),
(163, './upload/station_vsat/40/pj_40.pdf', 'Formulaire d''enregistrement Station VSAT SGT N''djamena NIMERY', 'vsat', 'vsat_stations', 40, 2, 'Document', 5, '2017-04-25 11:04:32'),
(164, './upload/station_vsat/41/pj_41.pdf', 'Formulaire d''enregistrement Station VSAT HYDROCARBON N''DJAMENA', 'vsat', 'vsat_stations', 41, 2, 'Document', 5, '2017-04-25 11:29:09'),
(165, './upload/station_vsat/41/965604780935fdae725e379d428086fe.jpg', 'Atenne Hydrocarbone N''djamena -vsat_41', 'vsat', 'vsat_stations', 41, 2, 'Image', 5, '2017-04-25 11:29:09'),
(166, './upload/station_vsat/41/f16f69a7cfc2bd728118ccc8fd4ddfe6.jpg', 'LNB Hydrocarbone -vsat_41', 'vsat', 'vsat_stations', 41, 2, 'Image', 5, '2017-04-25 11:29:09'),
(167, './upload/station_vsat/41/5629bfd02ab54b982df2d571bd424bed.jpg', 'Modem Hydrocarbone N''djam -vsat_41', 'vsat', 'vsat_stations', 41, 2, 'Image', 5, '2017-04-25 11:29:09'),
(168, './upload/station_vsat/42/pj_42.pdf', 'Formulaire d''enregistrement Station VSAT HYDROCARBONE BELANGA', 'vsat', 'vsat_stations', 42, 2, 'Document', 5, '2017-04-25 11:49:34'),
(171, './upload/permissionnaires/29/pj_29.pdf', 'Formulaire d''enregistrement Permissionnaire BOLLORE TRANSPORT ET LOGISTIQUE', 'Permissionnaire', 'permissionnaires', 29, 2, 'Document', 7, '2017-04-26 15:22:06'),
(172, './upload/station_vsat/43/pj_43.pdf', 'Formulaire d''enregistrement Station VSAT BOLLORE N''DJAMENA', 'vsat', 'vsat_stations', 43, 2, 'Document', 6, '2017-04-26 15:58:00'),
(173, './upload/permissionnaires/30/pj_30.pdf', 'Formulaire d''enregistrement Permissionnaire PETROCHAD (MANGARA) LIMITED', 'Permissionnaire', 'permissionnaires', 30, 2, 'Document', 6, '2017-04-27 13:46:33'),
(174, './upload/permissionnaires/31/pj_31.pdf', 'Formulaire d''enregistrement Permissionnaire CFE TCHAD SA', 'Permissionnaire', 'permissionnaires', 31, 2, 'Document', 5, '2017-04-27 14:44:05'),
(175, './upload/permissionnaires/32/pj_32.pdf', 'Formulaire d''enregistrement Permissionnaire AIR FRANCE', 'Permissionnaire', 'permissionnaires', 32, 2, 'Document', 7, '2017-05-02 15:28:03'),
(176, './upload/permissionnaires/33/pj_33.pdf', 'Formulaire d''enregistrement Permissionnaire ECOBANK TCHAD', 'Permissionnaire', 'permissionnaires', 33, 2, 'Document', 5, '2017-05-03 14:33:34'),
(177, './upload/station_vsat/44/pj_44.pdf', 'Formulaire d''enregistrement Station VSAT CFE N''djamena', 'vsat', 'vsat_stations', 44, 2, 'Document', 5, '2017-05-03 14:58:48'),
(178, './upload/station_vsat/45/pj_45.pdf', 'Formulaire d''enregistrement Station VSAT Air France Aéropport', 'vsat', 'vsat_stations', 45, 2, 'Document', 6, '2017-05-03 15:29:36'),
(179, './upload/users/8/photo_8.jpg', 'Photo de profile de Bichara  Bichara', 'users', 'users_sys', 8, 1, 'Document', 1, '2017-05-03 15:35:03'),
(180, './upload/users/8/signature_8.png', 'signature  de Bichara  Bichara', 'users', 'users_sys', 8, 1, 'Document', 1, '2017-05-03 15:35:03'),
(181, './upload/users/8/form_8.pdf', 'Formulaire  de Bichara  Bichara', 'users', 'users_sys', 8, 1, 'Document', 1, '2017-05-03 15:35:03'),
(182, './upload/station_vsat/46/pj_46.pdf', 'Formulaire d''enregistrement Station VSAT SIEGE ECOBANK 2', 'vsat', 'vsat_stations', 46, 2, 'Document', 5, '2017-05-03 15:41:51'),
(183, './upload/station_vsat/46/62997daa0243f5f3813db5ece55a1e22.jpg', 'LNB ECOBANK -vsat_46', 'vsat', 'vsat_stations', 46, 2, 'Image', 5, '2017-05-03 15:41:51'),
(184, './upload/station_vsat/46/c81e68131dd47329414b95306db010cb.jpg', 'ATENNE ECOBANK 2 -vsat_46', 'vsat', 'vsat_stations', 46, 2, 'Image', 5, '2017-05-03 15:41:51'),
(185, './upload/station_vsat/46/b0a5111e0225c58467b8e3144a76b5eb.jpg', 'MODEM ECOBANK 2 -vsat_46', 'vsat', 'vsat_stations', 46, 2, 'Image', 5, '2017-05-03 15:41:51'),
(186, './upload/station_vsat/46/7242f8e398f6750c8db6181c3067fdb1.jpg', 'RADIO ECOBANK 2 -vsat_46', 'vsat', 'vsat_stations', 46, 2, 'Image', 5, '2017-05-03 15:41:51'),
(187, './upload/permissionnaires/34/pj_34.pdf', 'Formulaire d''enregistrement Permissionnaire AAA', 'Permissionnaire', 'permissionnaires', 34, 17, 'Document', 8, '2017-05-03 15:42:10'),
(188, './upload/station_vsat/47/pj_47.pdf', 'Formulaire d''enregistrement Station VSAT Ecobank Ndjamena siege1', 'vsat', 'vsat_stations', 47, 2, 'Document', 6, '2017-05-03 15:56:29'),
(189, './upload/station_vsat/47/716f92f3fdd87236ffac1f740e4f9590.jpg', 'RADIO1 ECOBANK -vsat_47', 'vsat', 'vsat_stations', 47, 2, 'Image', 6, '2017-05-03 15:56:29'),
(190, './upload/station_vsat/47/73707570f98ee156117106ee92ee4d06.jpg', 'MODEM1 ECOBANK -vsat_47', 'vsat', 'vsat_stations', 47, 2, 'Image', 6, '2017-05-03 15:56:29'),
(191, './upload/station_vsat/47/f10a11e733be8c886a14d6f2f946d5cf.jpg', 'VSAT1 ECOBANK -vsat_47', 'vsat', 'vsat_stations', 47, 2, 'Image', 6, '2017-05-03 15:56:29'),
(192, './upload/station_vsat/48/pj_48.pdf', 'Formulaire d''enregistrement Station VSAT ECOBANK SARH', 'vsat', 'vsat_stations', 48, 2, 'Document', 6, '2017-05-04 10:09:30'),
(193, './upload/station_vsat/49/pj_49.pdf', 'Formulaire d''enregistrement Station VSAT ECOBANK PALA', 'vsat', 'vsat_stations', 49, 2, 'Document', 6, '2017-05-04 10:26:48'),
(194, './upload/station_vsat/50/pj_50.pdf', 'Formulaire d''enregistrement Station VSAT ECOBANK ABECHE', 'vsat', 'vsat_stations', 50, 2, 'Document', 6, '2017-05-04 10:44:37'),
(195, './upload/station_vsat/9/77a07c843fdd051fc88f0965fa0b51d5.jpg', 'Vsat CBT -vsat_9', 'vsat', 'vsat_stations', 9, 2, 'Image', 6, '2017-05-05 09:35:39'),
(196, './upload/station_vsat/9/e2a1bd13210c10872beb494149cdf255.jpg', 'LNB CBT -vsat_9', 'vsat', 'vsat_stations', 9, 2, 'Image', 6, '2017-05-05 09:35:39'),
(197, './upload/station_vsat/9/8ebe4bc73f5c4b4783d5fd7b95b88ad9.jpg', 'Modem CBT -vsat_9', 'vsat', 'vsat_stations', 9, 2, 'Image', 6, '2017-05-05 09:35:39'),
(198, './upload/station_vsat/3/a29628d99c72c1f6216aa382a75c4bd5.jpg', 'Vsat Sonacim -vsat_3', 'vsat', 'vsat_stations', 3, 2, 'Image', 4, '2017-05-05 11:50:32'),
(199, './upload/station_vsat/3/88ba18414c3a2a4a34f92e9caa534b08.jpg', 'Modem Sonacim -vsat_3', 'vsat', 'vsat_stations', 3, 2, 'Image', 4, '2017-05-05 11:50:32'),
(200, './upload/station_vsat/3/81a321a0798dee9b6476c6fdb9f7782d.jpg', 'Radio Sonacim -vsat_3', 'vsat', 'vsat_stations', 3, 2, 'Image', 4, '2017-05-05 11:50:32'),
(201, './upload/station_vsat/27/be5d33ce14c903037b1a2b50dddf3828.jpg', 'Antenne BSIC -vsat_27', 'vsat', 'vsat_stations', 27, 2, 'Image', 4, '2017-05-05 11:52:42'),
(202, './upload/station_vsat/37/754dd9365542e9533baac9f84ad4c094.jpg', 'Photo radio CICR -vsat_37', 'vsat', 'vsat_stations', 37, 2, 'Image', 4, '2017-05-05 11:59:05'),
(203, './upload/station_vsat/37/65356a7981e41746b64671abb00b8ccd.jpg', 'modem CICR -vsat_37', 'vsat', 'vsat_stations', 37, 2, 'Image', 4, '2017-05-05 11:59:05'),
(204, './upload/station_vsat/37/9ef57869938f68fe1d391b2b2a482315.jpg', 'photo lnb CICER -vsat_37', 'vsat', 'vsat_stations', 37, 2, 'Image', 4, '2017-05-05 11:59:05'),
(208, './upload/permissionnaires/35/pj_35.pdf', 'Formulaire d''enregistrement Permissionnaire SOTEC', 'Permissionnaire', 'permissionnaires', 35, 2, 'Document', 5, '2017-05-08 10:03:42'),
(209, './upload/station_vsat/51/pj_51.pdf', 'Formulaire d''enregistrement Station VSAT SOTEC N''djamena', 'vsat', 'vsat_stations', 51, 2, 'Document', 7, '2017-05-08 10:31:27'),
(212, './upload/station_vsat/51/18545a195624b64062a0c2d9425f63e2.jpg', 'Antenne SOTEC N''djamena -vsat_51', 'vsat', 'vsat_stations', 51, 2, 'Image', 5, '2017-05-08 10:37:18'),
(213, './upload/station_vsat/51/5415433fc8857f6def981e1beeedd7a6.jpg', 'Modem SOTECH N''djamena -vsat_51', 'vsat', 'vsat_stations', 51, 2, 'Image', 5, '2017-05-08 10:37:18'),
(214, './upload/station_vsat/52/pj_52.pdf', 'Formulaire d''enregistrement Station VSAT SOTEC DANDI', 'vsat', 'vsat_stations', 52, 2, 'Document', 5, '2017-05-08 10:47:55'),
(215, './upload/anonymes/52/a780e53a62fbcd76186aa5736ae0d6c3.jpg', 'BLR1 -anonymes_52', 'anonymes', 'prm_anonyme', 52, 2, 'Image', 6, '2017-05-08 15:43:39'),
(216, './upload/anonymes/52/52493092fb07cbeffa0ce4c1f7c623b9.jpg', 'BLR2 -anonymes_52', 'anonymes', 'prm_anonyme', 52, 2, 'Image', 6, '2017-05-08 15:43:39'),
(217, './upload/anonymes/60/90e7cf9cf99415294cf40ecaa03ed453.jpg', 'VSAT ANONYME -anonymes_60', 'anonymes', 'prm_anonyme', 60, 2, 'Image', 5, '2017-05-09 14:52:52'),
(218, './upload/anonymes/62/7ca9ea4fb98e7b42f25fd4792f23c648.jpg', 'BLR ANONYM -anonymes_62', 'anonymes', 'prm_anonyme', 62, 2, 'Image', 5, '2017-05-09 15:02:45'),
(219, './upload/permissionnaires/36/pj_36.pdf', 'Formulaire d''enregistrement Permissionnaire HIAS', 'Permissionnaire', 'permissionnaires', 36, 2, 'Document', 7, '2017-05-10 11:35:25'),
(220, './upload/station_vsat/53/pj_53.pdf', 'Formulaire d''enregistrement Station VSAT vsat HIAS ', 'vsat', 'vsat_stations', 53, 2, 'Document', 6, '2017-05-10 13:38:21'),
(224, './upload/permissionnaires/37/pj_37.pdf', 'Formulaire d''enregistrement Permissionnaire world vision ', 'Permissionnaire', 'permissionnaires', 37, 2, 'Document', 7, '2017-05-11 10:22:52'),
(225, './upload/station_vsat/54/pj_54.pdf', 'Formulaire d''enregistrement Station VSAT LAI', 'vsat', 'vsat_stations', 54, 2, 'Document', 7, '2017-05-11 10:50:04'),
(226, './upload/station_vsat/55/pj_55.pdf', 'Formulaire d''enregistrement Station VSAT BAGASSOULA', 'vsat', 'vsat_stations', 55, 2, 'Document', 7, '2017-05-11 11:23:18'),
(230, './upload/station_vsat/56/pj_56.pdf', 'Formulaire d''enregistrement Station VSAT Koumra', 'vsat', 'vsat_stations', 56, 2, 'Document', 7, '2017-05-11 11:35:16'),
(231, './upload/station_vsat/57/pj_57.pdf', 'Formulaire d''enregistrement Station VSAT DOBA', 'vsat', 'vsat_stations', 57, 2, 'Document', 7, '2017-05-11 11:46:35'),
(232, './upload/station_vsat/58/pj_58.pdf', 'Formulaire d''enregistrement Station VSAT Moundou', 'vsat', 'vsat_stations', 58, 2, 'Document', 7, '2017-05-11 11:57:02'),
(233, './upload/station_vsat/59/pj_59.pdf', 'Formulaire d''enregistrement Station VSAT GUELENDENG', 'vsat', 'vsat_stations', 59, 2, 'Document', 7, '2017-05-11 12:03:43'),
(234, './upload/station_vsat/60/pj_60.pdf', 'Formulaire d''enregistrement Station VSAT N''DJAMENA', 'vsat', 'vsat_stations', 60, 2, 'Document', 7, '2017-05-11 12:10:59'),
(235, './upload/station_vsat/61/pj_61.pdf', 'Formulaire d''enregistrement Station VSAT ABECHE', 'vsat', 'vsat_stations', 61, 2, 'Document', 7, '2017-05-11 12:21:59'),
(236, './upload/station_vsat/62/pj_62.pdf', 'Formulaire d''enregistrement Station VSAT SGT N''djamena passif', 'vsat', 'vsat_stations', 62, 2, 'Document', 7, '2017-05-11 12:52:30'),
(237, './upload/anonymes/18/b714f2c77c719233467d81d4fa36a723.jpg', 'VSAT derière inconnue -anonymes_18', 'anonymes', 'prm_anonyme', 18, 1, 'Image', 1, '2017-05-11 15:14:47'),
(238, './upload/permissionnaires/38/pj_38.pdf', 'Formulaire d''enregistrement Permissionnaire CROIX ROUGE DU TCHAD', 'Permissionnaire', 'permissionnaires', 38, 2, 'Document', 5, '2017-05-12 11:40:32'),
(239, './upload/anonymes/108/a87c32ea41aca2a00afa549b8264e004.jpg', 'PHOTO BLR -anonymes_108', 'anonymes', 'prm_anonyme', 108, 2, 'Image', 7, '2017-05-16 13:31:24'),
(240, './upload/anonymes/109/8e46f5f69cd58fda51465e1c39b38bfe.jpg', 'OPTIQUE VICTOIRE -anonymes_109', 'anonymes', 'prm_anonyme', 109, 2, 'Image', 7, '2017-05-16 13:32:56'),
(241, './upload/anonymes/110/b7d4a6fabcf995da39f02f5ca0dfd3a7.jpg', 'INCONNUE DERNIERE AG BLR -anonymes_110', 'anonymes', 'prm_anonyme', 110, 2, 'Image', 7, '2017-05-16 13:35:05'),
(242, './upload/anonymes/111/f061d0364fbba11ce3267ae0ae4f3062.jpg', 'AMBASSADE ARABIE SAOUDITE -anonymes_111', 'anonymes', 'prm_anonyme', 111, 2, 'Image', 7, '2017-05-16 13:36:49'),
(243, './upload/anonymes/112/1406b1e8980538f48ea1922740ea46e1.jpg', 'MAIBA AIRWAYS -anonymes_112', 'anonymes', 'prm_anonyme', 112, 2, 'Image', 7, '2017-05-16 13:38:17'),
(244, './upload/anonymes/113/4a2b5a8a9d1cc8a011c25246c2582fa9.jpg', 'AMBASSADE ARABIE SAOUDITE BLR -anonymes_113', 'anonymes', 'prm_anonyme', 113, 2, 'Image', 7, '2017-05-16 13:41:27'),
(245, './upload/anonymes/114/ff468e0eda7911256660bad328d61c3f.jpg', 'CANAL + BLR -anonymes_114', 'anonymes', 'prm_anonyme', 114, 2, 'Image', 7, '2017-05-16 13:43:00'),
(246, './upload/anonymes/115/3530c6cc3d72a2988bd4ee4c2e32b060.jpg', ' MEIG BLR -anonymes_115', 'anonymes', 'prm_anonyme', 115, 2, 'Image', 7, '2017-05-16 13:44:45'),
(247, './upload/anonymes/106/e389b624f4d9339549f60267f4794eb8.jpg', 'DIR.GEN.METEOROLOGIE -anonymes_106', 'anonymes', 'prm_anonyme', 106, 2, 'Image', 7, '2017-05-16 13:47:33'),
(248, './upload/anonymes/105/184f14900e983794f02481c62c67fd93.jpg', 'CNAR BLR -anonymes_105', 'anonymes', 'prm_anonyme', 105, 2, 'Image', 7, '2017-05-16 13:49:10'),
(249, './upload/anonymes/104/b42b7d2fd82290330fb889d8efd7748e.jpg', 'VSAT CNAR -anonymes_104', 'anonymes', 'prm_anonyme', 104, 2, 'Image', 7, '2017-05-16 13:51:17'),
(250, './upload/anonymes/103/df41d3ed6841e9ceacf9e976a109cb91.jpg', 'VSAT CANAL + -anonymes_103', 'anonymes', 'prm_anonyme', 103, 2, 'Image', 7, '2017-05-16 13:52:59'),
(251, './upload/anonymes/102/ee7f1d862f4696003a39ba1d0a3cb888.jpg', 'AGENCE DE VOYAGE ET TOURISME -anonymes_102', 'anonymes', 'prm_anonyme', 102, 2, 'Image', 7, '2017-05-16 13:54:54'),
(252, './upload/anonymes/101/007d44f8c49f5e9f960307dee0bfd026.jpg', 'BLR SATGURU -anonymes_101', 'anonymes', 'prm_anonyme', 101, 2, 'Image', 7, '2017-05-16 13:56:38'),
(253, './upload/anonymes/100/0c24973dd8dcfec50123a281b6b34807.jpg', 'BLR AG -anonymes_100', 'anonymes', 'prm_anonyme', 100, 2, 'Image', 7, '2017-05-16 13:58:16'),
(254, './upload/anonymes/99/ed132b7cbe20d826d9f57a91ea30c5e0.jpg', 'BLR MEIG -anonymes_99', 'anonymes', 'prm_anonyme', 99, 2, 'Image', 7, '2017-05-16 13:59:27'),
(255, './upload/anonymes/98/fd0b27147ebbbb3df07d755744f84b94.jpg', ' VSAT MEIG -anonymes_98', 'anonymes', 'prm_anonyme', 98, 2, 'Image', 7, '2017-05-16 14:01:22'),
(257, './upload/anonymes/118/4ec26607020a2f1fa6a9c386d0b4e985.jpg', 'BLR PNUD -anonymes_118', 'anonymes', 'prm_anonyme', 118, 2, 'Image', 5, '2017-05-17 15:35:10'),
(260, './upload/anonymes/121/8d96e39120fe3073371af93088cab37f.jpg', 'BLR CHINOI FARCHA -anonymes_121', 'anonymes', 'prm_anonyme', 121, 2, 'Image', 5, '2017-05-17 15:46:45'),
(261, './upload/anonymes/119/0e32802bcb2311f3da00578f5c85f144.jpg', 'INCONNUE -anonymes_119', 'anonymes', 'prm_anonyme', 119, 2, 'Image', 5, '2017-05-17 15:47:28'),
(262, './upload/anonymes/120/9ef8858c78fbd305d8e8b3e14b738341.jpg', 'INCONNUE PARTICULIER -anonymes_120', 'anonymes', 'prm_anonyme', 120, 2, 'Image', 5, '2017-05-17 15:48:06'),
(263, './upload/anonymes/117/70a030816091fa82b7914519c428a040.jpg', 'BLR INCONNUE -anonymes_117', 'anonymes', 'prm_anonyme', 117, 2, 'Image', 5, '2017-05-17 15:48:50'),
(265, './upload/anonymes/123/f31bf1da8868144ef482b9890f431bca.jpg', 'VHF Derriere ESSO -anonymes_123', 'anonymes', 'prm_anonyme', 123, 2, 'Image', 5, '2017-05-18 14:45:37'),
(266, './upload/anonymes/124/492082077ebb23790c64b95377d75998.jpg', 'BLR INCONNUE -anonymes_124', 'anonymes', 'prm_anonyme', 124, 2, 'Image', 5, '2017-05-18 14:51:44'),
(267, './upload/anonymes/125/d2ea1e4bf88d69f7a7862da2da7d0656.jpg', 'BLR INCONNUE -anonymes_125', 'anonymes', 'prm_anonyme', 125, 2, 'Image', 5, '2017-05-18 14:54:10'),
(268, './upload/anonymes/126/a8247904403717df1727cfae73d08721.jpg', 'INCONNUE BLR -anonymes_126', 'anonymes', 'prm_anonyme', 126, 2, 'Image', 5, '2017-05-18 14:55:55'),
(269, './upload/anonymes/127/7b3d42bd03ff047189fc77ad749583c8.jpg', 'BLR INCONNUE -anonymes_127', 'anonymes', 'prm_anonyme', 127, 2, 'Image', 5, '2017-05-18 14:57:38'),
(270, './upload/anonymes/128/136a2877c9a2b1101737c43de67e2680.jpg', 'INCONNUE BLR -anonymes_128', 'anonymes', 'prm_anonyme', 128, 2, 'Image', 5, '2017-05-18 15:00:21'),
(271, './upload/anonymes/129/7b66c2326005da921432eef3510effca.jpg', 'BLR APLFT -anonymes_129', 'anonymes', 'prm_anonyme', 129, 2, 'Image', 5, '2017-05-19 11:58:56'),
(272, './upload/anonymes/130/8273651e70ac855efec6e2df448871b9.jpg', 'BLR INCONNUE -anonymes_130', 'anonymes', 'prm_anonyme', 130, 2, 'Image', 5, '2017-05-19 12:01:24'),
(273, './upload/anonymes/131/22f8c69ed7b1dc7173bfa35fc8fe24d5.jpg', 'Vsat E.U -anonymes_131', 'anonymes', 'prm_anonyme', 131, 2, 'Image', 5, '2017-05-19 12:04:10'),
(274, './upload/anonymes/132/dcb91f12e25173f98cf5f0f4a2ffacbf.jpg', 'BLR INCONNUE -anonymes_132', 'anonymes', 'prm_anonyme', 132, 2, 'Image', 5, '2017-05-19 12:07:02'),
(275, './upload/anonymes/133/96aa36538d7e69113234fd5d1b012c1e.jpg', 'BLR VITELLA -anonymes_133', 'anonymes', 'prm_anonyme', 133, 2, 'Image', 5, '2017-05-19 12:11:51'),
(276, './upload/anonymes/134/ffea78d006900a696bebf29b5f4798a8.jpg', 'BLR ISTD -anonymes_134', 'anonymes', 'prm_anonyme', 134, 2, 'Image', 5, '2017-05-19 12:14:01'),
(277, './upload/anonymes/135/7bd8459288967741abffc36b67dd4fb8.jpg', 'BLR MG GROUP -anonymes_135', 'anonymes', 'prm_anonyme', 135, 2, 'Image', 5, '2017-05-19 12:17:16'),
(278, './upload/anonymes/137/8e39ed1c8c503d7ca045206f382932b1.jpg', 'BLR CYBER -anonymes_137', 'anonymes', 'prm_anonyme', 137, 2, 'Image', 5, '2017-05-22 09:41:00'),
(279, './upload/anonymes/138/185322008e503d8a2770be075267d507.jpg', 'BLR PAM -anonymes_138', 'anonymes', 'prm_anonyme', 138, 2, 'Image', 5, '2017-05-22 09:49:06'),
(280, './upload/anonymes/139/91232de8ba796f54581281ee95cdb8af.jpg', 'BLR SHERABELLE -anonymes_139', 'anonymes', 'prm_anonyme', 139, 2, 'Image', 5, '2017-05-22 09:52:34'),
(281, './upload/anonymes/140/ad11c2379d53cf9551afc31f71e67c08.jpg', 'BLR  -anonymes_140', 'anonymes', 'prm_anonyme', 140, 2, 'Image', 5, '2017-05-22 10:48:12'),
(282, './upload/anonymes/141/5e8a3bc66e059650b719df7231360539.jpg', 'BLR PARTICULIER -anonymes_141', 'anonymes', 'prm_anonyme', 141, 2, 'Image', 5, '2017-05-22 10:53:54'),
(283, './upload/station_vsat/63/pj_63.pdf', 'Formulaire d''enregistrement Station VSAT PETROCHAD MANGARA', 'vsat', 'vsat_stations', 63, 2, 'Document', 5, '2017-05-22 11:49:21'),
(284, './upload/station_vsat/64/pj_64.pdf', 'Formulaire d''enregistrement Station VSAT     PETROCHAD Ndjamena', 'vsat', 'vsat_stations', 64, 2, 'Document', 7, '2017-05-22 12:01:18'),
(285, './upload/station_vsat/64/50ba598b8c03d46ab44ae3fcc7b88233.jpg', 'modem PETROCHAD -vsat_64', 'vsat', 'vsat_stations', 64, 2, 'Image', 7, '2017-05-22 12:01:18'),
(286, './upload/station_vsat/64/1f89eec02ba6550fae1fdafa871b1652.jpg', 'RADIO PETROCHAD -vsat_64', 'vsat', 'vsat_stations', 64, 2, 'Image', 7, '2017-05-22 12:01:18'),
(287, './upload/station_vsat/64/723810e08ced90afcdc85d481138c320.jpg', 'VSAT PETROCHAD -vsat_64', 'vsat', 'vsat_stations', 64, 2, 'Image', 7, '2017-05-22 12:01:18'),
(288, './upload/station_vsat/65/pj_65.pdf', 'Formulaire d''enregistrement Station VSAT Vsat Badila PETROCHAD', 'vsat', 'vsat_stations', 65, 2, 'Document', 6, '2017-05-22 12:23:54'),
(289, './upload/anonymes/142/5d328e6b215bc05d074837f91764d072.jpg', 'Vsat DSPA -anonymes_142', 'anonymes', 'prm_anonyme', 142, 2, 'Image', 5, '2017-05-22 16:10:40'),
(290, './upload/anonymes/143/3e02069e593f46087ff5a067a22dd42d.jpg', 'Vsat  C.A -anonymes_143', 'anonymes', 'prm_anonyme', 143, 2, 'Image', 5, '2017-05-22 16:13:46'),
(291, './upload/anonymes/144/ae537b1fdc03144f30ecdd8a16d16352.jpg', 'BLR DSPA -anonymes_144', 'anonymes', 'prm_anonyme', 144, 2, 'Image', 5, '2017-05-23 10:37:20'),
(292, './upload/anonymes/145/278fe1252cea7a6b8336b4a47aa1ea03.jpg', 'BLR INCONNUE -anonymes_145', 'anonymes', 'prm_anonyme', 145, 2, 'Image', 5, '2017-05-23 10:48:23'),
(293, './upload/anonymes/146/32fb6f70916e08315158973ad9dd0e64.jpg', 'BLR  -anonymes_146', 'anonymes', 'prm_anonyme', 146, 2, 'Image', 5, '2017-05-23 11:06:14'),
(294, './upload/anonymes/147/a9e09a851b469f8ee793a13b5dfee98b.jpg', 'BLR -anonymes_147', 'anonymes', 'prm_anonyme', 147, 2, 'Image', 5, '2017-05-23 11:16:14'),
(295, './upload/anonymes/148/895d3df4885ba9e82f59a2cc85147a35.jpg', 'BLR -anonymes_148', 'anonymes', 'prm_anonyme', 148, 2, 'Image', 5, '2017-05-23 11:38:07'),
(296, './upload/permissionnaires/39/pj_39.pdf', 'Formulaire d''enregistrement Permissionnaire ACTION CONTRE LA FAIM', 'Permissionnaire', 'permissionnaires', 39, 2, 'Document', 7, '2017-05-25 14:06:06'),
(297, './upload/permissionnaires/40/pj_40.pdf', 'Formulaire d''enregistrement Permissionnaire CAtholique Relief Services -USCC', 'Permissionnaire', 'permissionnaires', 40, 2, 'Document', 5, '2017-05-25 15:30:09'),
(298, './upload/station_vsat/66/pj_66.pdf', 'Formulaire d''enregistrement Station VSAT ACF MAO BASE', 'vsat', 'vsat_stations', 66, 2, 'Document', 5, '2017-05-26 10:27:57'),
(299, './upload/station_vsat/67/pj_67.pdf', 'Formulaire d''enregistrement Station VSAT ACF MOUSSORO', 'vsat', 'vsat_stations', 67, 2, 'Document', 5, '2017-05-26 10:38:00'),
(300, './upload/station_vsat/68/pj_68.pdf', 'Formulaire d''enregistrement Station VSAT ACF BAGASOLA BASE', 'vsat', 'vsat_stations', 68, 2, 'Document', 5, '2017-05-26 10:45:18'),
(301, './upload/anonymes/149/372296bce57773da3ab1606fc0160fdb.jpg', 'BLR INCONNU -anonymes_149', 'anonymes', 'prm_anonyme', 149, 2, 'Image', 7, '2017-05-29 17:00:50'),
(302, './upload/anonymes/150/d8070d8813fc01346637da1821f0641d.jpg', 'BLR a cote MSF SUISSE -anonymes_150', 'anonymes', 'prm_anonyme', 150, 2, 'Image', 7, '2017-05-29 17:03:40'),
(303, './upload/anonymes/151/d4651f43877a05a37477ac6967f765d3.jpg', 'blr albidey investissement -anonymes_151', 'anonymes', 'prm_anonyme', 151, 2, 'Image', 7, '2017-05-29 17:07:51'),
(304, './upload/anonymes/152/42cfbd80126deb855dc25c5105c93eea.jpg', 'vsat peschaud -anonymes_152', 'anonymes', 'prm_anonyme', 152, 2, 'Image', 7, '2017-05-29 17:10:25'),
(305, './upload/anonymes/153/8f43aecf0c60d17e81e8fb9aa0e4577f.jpg', 'blr -anonymes_153', 'anonymes', 'prm_anonyme', 153, 2, 'Image', 7, '2017-05-29 17:13:34'),
(306, './upload/anonymes/154/04f974ca8cd3238cafbc4ec7cd63c21d.jpg', 'pharmacie eguni -anonymes_154', 'anonymes', 'prm_anonyme', 154, 2, 'Image', 7, '2017-05-29 17:16:37'),
(307, './upload/anonymes/155/0e9274ee9b6b39d73a8062068cdd113f.jpg', 'VSAT  -anonymes_155', 'anonymes', 'prm_anonyme', 155, 2, 'Image', 7, '2017-05-29 17:20:46'),
(308, './upload/anonymes/156/d938a1a1efe4b08f52998198f0acf419.jpg', 'BLR Gd marché -anonymes_156', 'anonymes', 'prm_anonyme', 156, 2, 'Image', 7, '2017-05-29 17:25:49'),
(309, './upload/anonymes/157/b18ec5a954e8c79f752c424e4f492a29.jpg', 'photo -anonymes_157', 'anonymes', 'prm_anonyme', 157, 2, 'Image', 7, '2017-05-30 09:36:22'),
(310, './upload/anonymes/158/b1a306b6b42bfd70cdb4561ec29a696c.jpg', 'moursal -anonymes_158', 'anonymes', 'prm_anonyme', 158, 2, 'Image', 7, '2017-05-30 09:46:42'),
(311, './upload/anonymes/159/110d3a96ee7b4f3b62c99d8063e92eeb.jpg', 'station moursal -anonymes_159', 'anonymes', 'prm_anonyme', 159, 2, 'Image', 7, '2017-05-30 10:03:05'),
(312, './upload/anonymes/160/af385e986f42e1a144fb0f29b25a89db.jpg', 'station -anonymes_160', 'anonymes', 'prm_anonyme', 160, 2, 'Image', 7, '2017-05-30 10:08:15'),
(313, './upload/anonymes/161/cfeec7671de1e2101de08b6e2fd71099.jpg', 'Blr inconnu a moursal -anonymes_161', 'anonymes', 'prm_anonyme', 161, 2, 'Image', 7, '2017-05-30 10:17:50'),
(314, './upload/anonymes/162/ef04df9e1425dbd9499038fc9e912187.jpg', 'phot Blr a cote de programme de lutte contre SIDA -anonymes_162', 'anonymes', 'prm_anonyme', 162, 2, 'Image', 7, '2017-05-30 10:21:30'),
(315, './upload/anonymes/163/4d18a8054ff1cbfdd69ba99372233511.jpg', 'photo Blr de programme de lutte contre SIDA -anonymes_163', 'anonymes', 'prm_anonyme', 163, 2, 'Image', 7, '2017-05-30 10:23:49'),
(316, './upload/anonymes/164/5c05178eca3b624a2e4d7d03e56775a7.jpg', ' photo winer technologie cyber  -anonymes_164', 'anonymes', 'prm_anonyme', 164, 2, 'Image', 7, '2017-05-30 10:26:18'),
(317, './upload/anonymes/165/7112d635e6ff9e37855d856946395089.jpg', 'SNE DEMBE -anonymes_165', 'anonymes', 'prm_anonyme', 165, 2, 'Image', 7, '2017-05-30 10:29:55'),
(318, './upload/anonymes/166/c26373e8441d07bc3147f56f5a21f004.jpg', ' photo Blr QUIFEROU -anonymes_166', 'anonymes', 'prm_anonyme', 166, 2, 'Image', 7, '2017-05-30 10:33:10'),
(319, './upload/anonymes/167/78ff1a9ca3a357256ec3171e3e7024cf.jpg', 'photo Blr de l''hotel a cote de sotel gredia -anonymes_167', 'anonymes', 'prm_anonyme', 167, 2, 'Image', 7, '2017-05-30 10:37:42'),
(320, './upload/anonymes/168/8285488c5285f6b7b2e2415246a5f360.jpg', 'Photo blr projet SEDIGUE -anonymes_168', 'anonymes', 'prm_anonyme', 168, 2, 'Image', 7, '2017-05-31 10:37:15'),
(321, './upload/anonymes/169/392cb5800103e24675a63123013634bd.jpg', 'photo cyber high-tech -anonymes_169', 'anonymes', 'prm_anonyme', 169, 2, 'Image', 7, '2017-05-31 10:39:52'),
(322, './upload/anonymes/170/a2c3423ccaeb7f18e10cf624bd1ca7c5.jpg', 'Photo Agence des Musulmans d''Afrique Bureau du tchad  -anonymes_170', 'anonymes', 'prm_anonyme', 170, 2, 'Image', 7, '2017-05-31 10:44:15'),
(323, './upload/anonymes/171/6c37852ce68e743a8e4d2ececc019505.jpg', 'photo Blr inconnu 10eme arrondissement -anonymes_171', 'anonymes', 'prm_anonyme', 171, 2, 'Image', 7, '2017-05-31 10:48:37'),
(324, './upload/anonymes/172/25096d90f4c468d747129f3098b7e038.jpg', 'Vsat EXPRESSE UNION -anonymes_172', 'anonymes', 'prm_anonyme', 172, 2, 'Image', 5, '2017-05-31 15:45:45'),
(325, './upload/anonymes/173/b243cd83c240ade023632cbea3710f47.jpg', 'Vsat BON SAMARITAIN -anonymes_173', 'anonymes', 'prm_anonyme', 173, 2, 'Image', 5, '2017-05-31 15:48:20'),
(326, './upload/anonymes/175/61b64d9da1b272a08e97c2698a6a8e64.jpg', 'BLR BEING -anonymes_175', 'anonymes', 'prm_anonyme', 175, 2, 'Image', 5, '2017-05-31 16:14:04'),
(327, './upload/anonymes/176/6e2294ed6a35fd13d65b714a2e0cb20a.jpg', 'BLR INCONNU -anonymes_176', 'anonymes', 'prm_anonyme', 176, 2, 'Image', 5, '2017-06-01 13:38:52'),
(328, './upload/anonymes/177/5b9229e41f41e8a11023d64995cdd6d5.jpg', 'INCONNU BLR -anonymes_177', 'anonymes', 'prm_anonyme', 177, 2, 'Image', 5, '2017-06-01 13:40:44'),
(329, './upload/anonymes/178/8f8e4585f6f659db3541fd2aa4a3cf89.jpg', 'BLR INCONNU -anonymes_178', 'anonymes', 'prm_anonyme', 178, 2, 'Image', 5, '2017-06-01 13:43:19'),
(330, './upload/anonymes/179/1896dc046de4f62aef8cc69265f293d4.jpg', 'BLR INCOONU -anonymes_179', 'anonymes', 'prm_anonyme', 179, 2, 'Image', 5, '2017-06-01 13:45:39'),
(331, './upload/anonymes/183/2458d98f77d50a71f83845bb73749625.jpg', 'BLR INCONNU -anonymes_183', 'anonymes', 'prm_anonyme', 183, 2, 'Image', 5, '2017-06-05 09:37:05'),
(332, './upload/anonymes/184/3cbb053e5a71fbb7a110cd218ce7b835.jpg', 'BLR INCONNU -anonymes_184', 'anonymes', 'prm_anonyme', 184, 2, 'Image', 5, '2017-06-05 09:39:33'),
(333, './upload/anonymes/185/931f4fa8154974c7c1fa613215406e11.jpg', 'BLR INCONNU -anonymes_185', 'anonymes', 'prm_anonyme', 185, 2, 'Image', 5, '2017-06-05 09:42:07'),
(334, './upload/anonymes/186/0362efa96e31f440b6ddd03663e63530.jpg', 'BLR INCONNU -anonymes_186', 'anonymes', 'prm_anonyme', 186, 2, 'Image', 5, '2017-06-05 09:44:28'),
(335, './upload/anonymes/187/45dc68c7fffe55916cb5f532e67cdf6e.jpg', 'BLR INCONNU -anonymes_187', 'anonymes', 'prm_anonyme', 187, 2, 'Image', 5, '2017-06-05 09:46:39'),
(336, './upload/anonymes/188/d996ab9279707dd2c7ed247424060a9c.jpg', 'BLR barkan -anonymes_188', 'anonymes', 'prm_anonyme', 188, 2, 'Image', 6, '2017-06-06 09:46:07'),
(337, './upload/anonymes/189/ff0b0aa8caef15808906f6ef63cef67f.jpg', 'Vsat Min.Infrastructure -anonymes_189', 'anonymes', 'prm_anonyme', 189, 2, 'Image', 6, '2017-06-06 09:48:44'),
(338, './upload/anonymes/190/45660801cda47773afb492a13ebb6da7.jpg', 'BLR Min.Infra -anonymes_190', 'anonymes', 'prm_anonyme', 190, 2, 'Image', 6, '2017-06-06 09:50:16'),
(339, './upload/anonymes/191/a427e1e477432737b2c28a085851163a.jpg', 'BLR Fonction publique -anonymes_191', 'anonymes', 'prm_anonyme', 191, 2, 'Image', 6, '2017-06-06 09:52:22'),
(340, './upload/anonymes/192/73c2f43954b4746b49b7d1edf397da73.jpg', 'BLR PNSA -anonymes_192', 'anonymes', 'prm_anonyme', 192, 2, 'Image', 6, '2017-06-06 09:53:49'),
(341, './upload/anonymes/193/f485c91642a3cf782720864e03a7c18f.jpg', 'vsat FER -anonymes_193', 'anonymes', 'prm_anonyme', 193, 2, 'Image', 6, '2017-06-06 09:55:32'),
(342, './upload/anonymes/194/e7d7744e717c932aba3648a4aa30044a.jpg', 'BLR Fiscalité -anonymes_194', 'anonymes', 'prm_anonyme', 194, 2, 'Image', 6, '2017-06-06 09:57:31'),
(343, './upload/anonymes/195/337dc71314f0ebfaed690108041901a0.jpg', 'Vsat Univ. de Farcha -anonymes_195', 'anonymes', 'prm_anonyme', 195, 2, 'Image', 6, '2017-06-06 09:59:45'),
(344, './upload/anonymes/196/783be370e221d02f0980efff98909a0c.jpg', 'Vsat Inconnu Univ. Farcha -anonymes_196', 'anonymes', 'prm_anonyme', 196, 2, 'Image', 6, '2017-06-06 10:08:07'),
(345, './upload/anonymes/197/82f145eec95e947380d9eaaacf540710.jpg', 'BLR derrière Univ Farcha -anonymes_197', 'anonymes', 'prm_anonyme', 197, 2, 'Image', 6, '2017-06-06 10:09:22'),
(346, './upload/anonymes/198/23b186b75b6300aab34196020591d115.jpg', 'BLR direction veterinaire -anonymes_198', 'anonymes', 'prm_anonyme', 198, 2, 'Image', 6, '2017-06-06 10:16:02'),
(347, './upload/anonymes/199/2ab51ab7e167dea0f908414e90fe85e0.jpg', 'BLR oil Libya -anonymes_199', 'anonymes', 'prm_anonyme', 199, 2, 'Image', 6, '2017-06-06 10:18:52'),
(348, './upload/anonymes/200/a743159f3491f3641383676d146bba59.jpg', 'Vsat Oil Libya -anonymes_200', 'anonymes', 'prm_anonyme', 200, 2, 'Image', 6, '2017-06-06 10:26:06'),
(349, './upload/anonymes/201/f9a196e34cdd43a185bc02493be5d3c5.jpg', 'BLR derrière Afrique Média -anonymes_201', 'anonymes', 'prm_anonyme', 201, 2, 'Image', 6, '2017-06-06 10:48:23'),
(350, './upload/anonymes/202/e8eb1f0912f6f88bf1dfb716628916fa.jpg', 'BLR inconnu avec avec garde millitaire -anonymes_202', 'anonymes', 'prm_anonyme', 202, 2, 'Image', 6, '2017-06-06 10:51:00'),
(351, './upload/anonymes/205/5fa0b544a0c51bf507f4f52a79703c78.jpg', 'BLR -anonymes_205', 'anonymes', 'prm_anonyme', 205, 2, 'Image', 6, '2017-06-06 10:56:38'),
(353, './upload/anonymes/207/529e4f247f09abf805aefa2ed51cd9ab.jpg', 'VSAT ATP -anonymes_207', 'anonymes', 'prm_anonyme', 207, 2, 'Image', 6, '2017-06-06 10:58:53'),
(354, './upload/anonymes/208/cf76dd8ff485b21307ce69b7e64dc286.jpg', 'Vsat -anonymes_208', 'anonymes', 'prm_anonyme', 208, 2, 'Image', 6, '2017-06-06 11:00:07'),
(355, './upload/permissionnaires/41/pj_41.pdf', 'Formulaire d''enregistrement Permissionnaire TOTAL MARKETING TCHAD', 'Permissionnaire', 'permissionnaires', 41, 2, 'Document', 6, '2017-06-06 11:33:40'),
(356, './upload/station_vsat/69/pj_69.pdf', 'Formulaire d''enregistrement Station VSAT Total Marketing Tchad', 'vsat', 'vsat_stations', 69, 2, 'Document', 7, '2017-06-06 12:03:37'),
(357, './upload/station_vsat/69/675249a2aada7a29484c14a9656da42f.jpg', 'Photo vsat -vsat_69', 'vsat', 'vsat_stations', 69, 2, 'Image', 7, '2017-06-06 12:03:37'),
(358, './upload/station_vsat/69/6e226c25a8838c98a310fa81656645cb.jpg', 'Photo Modem -vsat_69', 'vsat', 'vsat_stations', 69, 2, 'Image', 7, '2017-06-06 12:03:37'),
(359, './upload/anonymes/209/1ca4599bc2ae511c687fd482e4a00311.jpg', 'PHOTO -anonymes_209', 'anonymes', 'prm_anonyme', 209, 2, 'Image', 7, '2017-06-06 13:32:19'),
(361, './upload/anonymes/210/feb0506a274f481f99a351cccd7cf115.jpg', 'blr a cote de rond point farcha -anonymes_210', 'anonymes', 'prm_anonyme', 210, 2, 'Image', 7, '2017-06-06 13:41:06'),
(362, './upload/anonymes/206/5f21531ae3e89d5faaa5206eda3863cd.jpg', 'BLR -anonymes_206', 'anonymes', 'prm_anonyme', 206, 2, 'Image', 7, '2017-06-06 13:41:51'),
(363, './upload/anonymes/211/92c792c7824a6d64d144bd95f76561d4.jpg', 'chambre froide -anonymes_211', 'anonymes', 'prm_anonyme', 211, 2, 'Image', 7, '2017-06-06 13:53:24'),
(364, './upload/anonymes/212/292f95759c4dcabb91138df001ae977b.jpg', 'Photo CHANDOM Chinois -anonymes_212', 'anonymes', 'prm_anonyme', 212, 2, 'Image', 7, '2017-06-06 13:55:23'),
(365, './upload/anonymes/213/e3ec794feaba621a68f4b0939eeb2a00.jpg', 'Photo Nouveau Vsat SOTEC -anonymes_213', 'anonymes', 'prm_anonyme', 213, 2, 'Image', 7, '2017-06-06 14:03:31'),
(366, './upload/anonymes/214/6eb2eaf69e71f595b5196f383f4ce3d4.jpg', 'Photo HANSON -anonymes_214', 'anonymes', 'prm_anonyme', 214, 2, 'Image', 7, '2017-06-06 14:06:07'),
(367, './upload/anonymes/215/93073212623e2a8f9fc5fbf6033e1bc6.jpg', 'Photo -anonymes_215', 'anonymes', 'prm_anonyme', 215, 2, 'Image', 7, '2017-06-06 14:17:15'),
(368, './upload/anonymes/216/e4b25ef6787e0966b30768c7fb5be753.jpg', 'blr inconnu a cote de l''usine -anonymes_216', 'anonymes', 'prm_anonyme', 216, 2, 'Image', 7, '2017-06-06 14:20:22'),
(369, './upload/anonymes/217/97a14d58d98cc537d0941bb84e98d31a.jpg', 'Photo blr inconnu a l''est de l''usine -anonymes_217', 'anonymes', 'prm_anonyme', 217, 2, 'Image', 7, '2017-06-06 14:22:41'),
(370, './upload/anonymes/218/90ee36aeebfd26d7f2f72cd0359c2dc8.jpg', 'Photo blr inconnu a l''est de l''usine -anonymes_218', 'anonymes', 'prm_anonyme', 218, 2, 'Image', 7, '2017-06-06 14:24:57'),
(374, './upload/anonymes/219/b8c232ae4fe89c668a3df851c8b7c06c.jpg', 'blr inconnu a l''est de l''usine -anonymes_219', 'anonymes', 'prm_anonyme', 219, 2, 'Image', 7, '2017-06-07 16:13:28'),
(375, './upload/anonymes/220/9c5d922ded01ded93b9c767b28f09a5b.jpg', 'vsat HBC -anonymes_220', 'anonymes', 'prm_anonyme', 220, 2, 'Image', 7, '2017-06-07 16:13:58'),
(376, './upload/users/17/photo_17.jpg', 'Photo de profile de tester  tester', 'users', 'users_sys', 17, 1, 'Document', 1, '2017-06-13 10:02:41'),
(378, './upload/users/17/form_17.pdf', 'Formulaire  de tester  tester', 'users', 'users_sys', 17, 1, 'Document', 1, '2017-06-13 10:02:42'),
(379, './upload/users/18/photo_18.jpg', 'Photo de profile de test1  test1', 'users', 'users_sys', 18, 1, 'Document', 1, '2017-06-13 10:08:34'),
(380, './upload/users/18/signature_18.png', 'signature  de test1  test1', 'users', 'users_sys', 18, 1, 'Document', 1, '2017-06-13 10:08:34'),
(381, './upload/users/18/form_18.pdf', 'Formulaire  de test1  test1', 'users', 'users_sys', 18, 1, 'Document', 1, '2017-06-13 10:08:34'),
(382, './upload/blr_stations/1/pj_1.pdf', 'Formulaire d''enregistrement Station BLR SIte Facrcha', 'blr_stations', 'blr_stations', 1, 1, 'Document', 1, '2017-06-14 23:26:09'),
(383, './upload/blr_stations/2/pj_2.pdf', 'Formulaire d''enregistrement Station BLR cccc', 'blr_stations', 'blr_stations', 2, 1, 'Document', 1, '2017-06-15 09:22:29'),
(384, './upload/blr_stations/3/pj_3.pdf', 'Formulaire d''enregistrement Station BLR get', 'blr_stations', 'blr_stations', 3, 1, 'Document', 1, '2017-06-15 10:04:58'),
(385, './upload/blr_clients/1/pj_1.pdf', 'Formulaire d''enregistrement Client BLR Site Rachid', 'blr_clients', 'blr_clients', 1, 1, 'Document', 1, '2017-06-15 10:26:02');
INSERT INTO `archive` (`id`, `doc`, `titr`, `modul`, `table`, `idm`, `service`, `type`, `creusr`, `credat`) VALUES
(390, './upload/blr_clients/2/dc0c14ad848cd19f8d7bdcd166023d8d.jpg', 'Image de site -blr_clients_2', 'blr_clients', 'blr_clients', 2, 1, 'Image', 1, '2017-06-15 10:31:29'),
(391, './upload/blr_clients/3/pj_3.pdf', 'Formulaire d''enregistrement Client BLR SIte test marker', 'blr_clients', 'blr_clients', 3, 1, 'Document', 1, '2017-06-15 10:53:13'),
(392, './upload/blr_clients/3/ea2eb735091d687915f02b6d6c63bb4c.jpg', 'image de test -blr_clients_3', 'blr_clients', 'blr_clients', 3, 1, 'Image', 1, '2017-06-15 10:53:13'),
(393, './upload/blr_clients/4/pj_4.pdf', 'Formulaire d''enregistrement Client BLR xxxx', 'blr_clients', 'blr_clients', 4, 1, 'Document', 1, '2017-06-15 10:56:34'),
(394, './upload/blr_clients/8/pj_8.pdf', 'Formulaire d''enregistrement Client BLR SIte sans frequence', 'blr_clients', 'blr_clients', 8, 1, 'Document', 1, '2017-06-15 11:02:27'),
(395, './upload/blr_stations/4/pj_4.pdf', 'Formulaire d''enregistrement Station BLR Site Gazlle', 'blr_stations', 'blr_stations', 4, 1, 'Document', 1, '2017-06-15 11:10:50'),
(399, './upload/blr_clients/9/pj_9.pdf', 'Formulaire d''enregistrement Client BLR NOm de Client', 'blr_clients', 'blr_clients', 9, 1, 'Document', 1, '2017-06-15 11:22:33'),
(400, './upload/blr_clients/9/7d6ab543136d0608f739f6b2e9fcc6c3.jpg', 'Test image -blr_clients_9', 'blr_clients', 'blr_clients', 9, 1, 'Image', 1, '2017-06-15 11:22:33'),
(401, './upload/blr_stations/4/0bb39e4d5315e54e970b11842d8c3288.jpg', 'Station de base -blr_stations_4', 'blr_stations', 'blr_stations', 4, 1, 'Image', 1, '2017-06-15 11:35:11'),
(402, './upload/blr_clients/11/pj_11.pdf', 'Formulaire d''enregistrement Client BLR NpO', 'blr_clients', 'blr_clients', 11, 1, 'Document', 1, '2017-06-15 11:47:22'),
(403, './upload/uhf_vhf_stations/1/pj_1.pdf', 'Formulaire d''enregistrement Station BLR Maamoura', 'uhf_vhf_stations', 'uhf_vhf_stations', 1, 1, 'Document', 1, '2017-06-25 14:43:03'),
(404, './upload/uhf_vhf_clients/4/f4aef4b68806a671e8ca5a84d4a9ab67.png', 'Mobile -uhf_vhf_clients_4', 'uhf_vhf_clients', 'uhf_vhf_clients', 4, 1, 'Image', 1, '2017-06-29 09:07:39'),
(405, './upload/station_vsat/70/pj_70.pdf', 'Formulaire d''enregistrement Station VSAT test karim', 'vsat', 'vsat_stations', 70, 1, 'Document', 1, '2017-08-06 13:38:35'),
(406, './upload/station_vsat/70/ab63031f4012a1fca21a8db4c4392eb8.jpg', 'nn -vsat_70', 'vsat', 'vsat_stations', 70, 1, 'Image', 1, '2017-08-06 13:38:35'),
(407, './upload/users/19/photo_19.jpg', 'Photo de profile de Testeur  Testeur', 'users', 'users_sys', 19, 1, 'Document', 1, '2017-08-28 01:44:32'),
(409, './upload/users/19/form_19.pdf', 'Formulaire  de Testeur  Testeur', 'users', 'users_sys', 19, 1, 'Document', 1, '2017-08-28 01:44:32'),
(411, './upload/users/1/signature_1.png', 'signature  de Testeur  Testeur', 'users', 'users_sys', 1, 1, 'Document', 1, '2017-08-29 22:01:10'),
(414, 'Devis09_2017/Devis_22.pdf', 'Devis 22', 'devis', 'devis', 22, 1, 'Document', 1, '2017-09-18 21:02:11'),
(415, 'Devis09_2017/Devis_24.pdf', 'Devis 24', 'devis', 'devis', 24, 1, 'Document', 1, '2017-09-18 22:34:05'),
(418, './upload/Devis09_2017/Devis_13.pdf', 'Devis 13', 'devis', 'devis', 13, 1, 'Document', 1, '2017-09-19 10:11:46'),
(420, './upload/Devis09_2017/Devis_23.pdf', 'Devis 23', 'devis', 'devis', 23, 1, 'Document', 1, '2017-09-21 11:58:37'),
(421, './upload/Devis09_2017/Devis_21.pdf', 'Devis 21', 'devis', 'devis', 21, 1, 'Document', 1, '2017-09-21 20:55:53'),
(422, './upload/Devis09_2017/Devis_22.pdf', 'Devis 22', 'devis', 'devis', 22, 1, 'Document', 1, '2017-09-21 23:34:14'),
(423, './upload/Devis09_2017/Devis_25.pdf', 'Devis 25', 'devis', 'devis', 25, 1, 'Document', 1, '2017-09-22 12:14:08'),
(424, './upload/Devis09_2017/Devis_26.pdf', 'Devis 26', 'devis', 'devis', 26, 1, 'Document', 1, '2017-09-23 12:29:53'),
(425, './upload/Devis09_2017/Devis_24.pdf', 'Devis 24', 'devis', 'devis', 24, 1, 'Document', 1, '2017-09-24 11:40:16'),
(426, './upload/Devis09_2017/Devis_27.pdf', 'Devis 27', 'devis', 'devis', 27, 1, 'Document', 1, '2017-09-28 13:43:24');

-- --------------------------------------------------------

--
-- Structure de la table `categorie_client`
--

CREATE TABLE IF NOT EXISTS `categorie_client` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `categorie_client` varchar(100) DEFAULT NULL COMMENT 'Type client',
  `etat` int(11) DEFAULT '0',
  `creusr` int(11) DEFAULT NULL,
  `credat` datetime DEFAULT CURRENT_TIMESTAMP,
  `updusr` int(11) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `categorie_client`
--

INSERT INTO `categorie_client` (`id`, `categorie_client`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(1, 'Grossiste', 1, 1, '2017-08-23 14:26:24', 1, '2017-08-26 21:29:29');

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

CREATE TABLE IF NOT EXISTS `clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `code` varchar(10) NOT NULL COMMENT 'Code client',
  `denomination` varchar(200) NOT NULL COMMENT 'Denomination du client',
  `tva` varchar(1) DEFAULT 'O' COMMENT 'TVA',
  `id_devise` int(5) DEFAULT '1' COMMENT 'ID Devise',
  `id_categorie` int(11) NOT NULL COMMENT 'Type client',
  `r_social` varchar(200) DEFAULT NULL COMMENT 'Raison social',
  `r_commerce` varchar(100) DEFAULT NULL COMMENT 'Registre de commerce',
  `nif` varchar(20) DEFAULT NULL COMMENT 'Id fiscale',
  `nom` varchar(100) DEFAULT NULL COMMENT 'Nom',
  `prenom` varchar(100) DEFAULT NULL COMMENT 'Prénom',
  `civilite` varchar(10) DEFAULT NULL COMMENT 'Sexe',
  `adresse` varchar(200) NOT NULL COMMENT 'Adresse',
  `id_pays` int(11) NOT NULL COMMENT 'Pays',
  `id_ville` int(11) NOT NULL COMMENT 'Ville',
  `tel` varchar(80) NOT NULL COMMENT 'Telephone',
  `fax` varchar(80) DEFAULT NULL COMMENT 'Fax',
  `bp` varchar(80) DEFAULT NULL COMMENT 'Boite postale',
  `email` varchar(100) NOT NULL COMMENT 'E-mail',
  `rib` varchar(30) DEFAULT NULL COMMENT 'compte bancaire du client',
  `devise` varchar(20) DEFAULT NULL COMMENT 'Devise de facturation du client',
  `pj` int(11) DEFAULT NULL,
  `pj_photo` int(11) DEFAULT NULL COMMENT 'photo du client',
  `etat` int(11) DEFAULT '0',
  `creusr` int(11) DEFAULT NULL,
  `credat` datetime DEFAULT CURRENT_TIMESTAMP,
  `updusr` int(11) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_code` (`code`),
  KEY `fk_client_ville` (`id_ville`),
  KEY `fk_client_pays` (`id_pays`),
  KEY `fk_client_type` (`id_categorie`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- Contenu de la table `clients`
--

INSERT INTO `clients` (`id`, `code`, `denomination`, `tva`, `id_devise`, `id_categorie`, `r_social`, `r_commerce`, `nif`, `nom`, `prenom`, `civilite`, `adresse`, `id_pays`, `id_ville`, `tel`, `fax`, `bp`, `email`, `rib`, `devise`, `pj`, `pj_photo`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(2, 'CP122', 'Data Connect Tchad SARL', 'O', 1, 1, NULL, NULL, 'NIF0004545', NULL, NULL, '1', 'adr12', 242, 43, '66666666', '033333333333333333333333', NULL, 'rachid@dctchad.com', NULL, NULL, 415, 416, 1, 1, '2017-08-26 12:59:00', 1, '2017-08-26 19:34:14'),
(3, 'CP123', 'DE111', 'N', 1, 1, NULL, NULL, NULL, NULL, NULL, '2', 'adress', 242, 43, '0444444444444444444444444444', NULL, NULL, 'em@em', NULL, NULL, NULL, NULL, 0, 1, '2017-08-26 13:51:29', 1, '2017-09-03 13:57:15'),
(8, 'CP444', 'DENOMI9', 'N', 1, 1, NULL, NULL, NULL, NULL, NULL, '1', 'adr1', 242, 2, '0444444444444444444444444', NULL, NULL, 'em@em', NULL, NULL, 407, 408, 0, 1, '2017-08-26 15:29:40', 1, '2017-08-26 18:01:57'),
(9, 'pjjjj', 'kmzdkzf', 'O', 1, 1, NULL, NULL, NULL, NULL, NULL, '1', 'dzdlz', 242, 43, '0222222222222222222222222', NULL, NULL, 'em@em', NULL, NULL, NULL, NULL, 0, 1, '2017-08-26 15:53:32', NULL, NULL),
(15, 'PRFF', 'fefeg', 'O', 1, 1, 'lmlgrl,rleg', NULL, NULL, NULL, NULL, '1', 'grdgdr', 242, 43, '04555555555555555', NULL, NULL, 'em@em', NULL, NULL, 412, 413, 1, 1, '2017-08-26 18:05:06', 1, '2017-09-03 13:57:07');

-- --------------------------------------------------------

--
-- Structure de la table `contrats`
--

CREATE TABLE IF NOT EXISTS `contrats` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `ref` varchar(100) NOT NULL COMMENT 'Reference',
  `iddevis` int(11) DEFAULT NULL,
  `date_effet` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  `commentaire` text,
  `date_contrat` date DEFAULT NULL,
  `idtype_echeance` int(11) DEFAULT NULL,
  `pj` int(11) DEFAULT NULL,
  `pj_photo` int(11) DEFAULT NULL,
  `etat` int(11) DEFAULT '0',
  `creusr` int(11) DEFAULT NULL,
  `credat` datetime DEFAULT CURRENT_TIMESTAMP,
  `updusr` int(11) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_devis_contrat` (`iddevis`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `contrats`
--

INSERT INTO `contrats` (`id`, `ref`, `iddevis`, `date_effet`, `date_fin`, `commentaire`, `date_contrat`, `idtype_echeance`, `pj`, `pj_photo`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(1, 'C1', 23, '2017-09-30', '2017-10-29', 'RAS', '2017-09-15', NULL, NULL, NULL, 0, 1, '2017-09-15 17:36:36', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `contrats_frn`
--

CREATE TABLE IF NOT EXISTS `contrats_frn` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `reference` varchar(100) NOT NULL COMMENT 'Reference',
  `id_fournisseur` int(11) DEFAULT NULL,
  `date_effet` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  `commentaire` text,
  `pj` int(11) DEFAULT NULL,
  `pj_photo` int(11) DEFAULT NULL,
  `etat` int(11) DEFAULT '0',
  `creusr` int(11) DEFAULT NULL,
  `credat` datetime DEFAULT CURRENT_TIMESTAMP,
  `updusr` int(11) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- Contenu de la table `contrats_frn`
--

INSERT INTO `contrats_frn` (`id`, `reference`, `id_fournisseur`, `date_effet`, `date_fin`, `commentaire`, `pj`, `pj_photo`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(12, 'CTR-FRN-1/2017', 2, '2017-09-22', '2017-09-23', '<p>okk<br></p>', NULL, NULL, 0, 1, '2017-09-18 20:03:39', 1, '2017-09-22 03:40:20'),
(13, 'CTR-FRN-2/2017', 2, '1970-01-01', '1970-01-23', '<p>okk<br></p>', 441, NULL, 0, 1, '2017-09-21 13:26:03', 1, '2017-09-22 03:40:31'),
(14, 'CTR-FRN-3/2017', 2, '1970-01-01', '1970-07-30', '<p>okjoefzjofzelfklzejfolzekjfloezrjkfop </p><p>^ùlerùsdvmlùemrfglvmerf<br></p>', 446, NULL, 0, 1, '2017-09-21 16:41:03', 1, '2017-09-22 20:37:17'),
(17, 'CTR-FRN-4/2017', 15, '2017-09-22', '2017-09-29', '<p>ok<br></p>', 447, NULL, 0, 1, '2017-09-22 21:55:06', NULL, NULL);

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
  `devis_pdf` int(11) DEFAULT NULL COMMENT 'Generated pdf (archive table)',
  `creusr` int(11) DEFAULT NULL,
  `credat` datetime DEFAULT NULL,
  `updusr` int(11) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_id_client` (`id_client`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29 ;

--
-- Contenu de la table `devis`
--

INSERT INTO `devis` (`id`, `tkn_frm`, `reference`, `id_client`, `tva`, `id_commercial`, `date_devis`, `type_remise`, `valeur_remise`, `totalht`, `totalttc`, `totaltva`, `claus_comercial`, `etat`, `devis_pdf`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(13, '5e4d828bb3803694724801c841ecfd02', 'DEV_13_2017', 3, NULL, 1, '2017-09-13', 'P', NULL, 1455454, 1746544.8, 291090.8, 'Paiement 100% à la commande pour', 0, 418, 1, '2017-09-13 01:56:53', NULL, NULL),
(21, 'ef4c85458e75208c1f1028d733ef3450', 'DEV_21_2017', 8, NULL, 1, '2017-09-05', 'P', NULL, 1455466, 1746559.2, 291093.2, 'Paiement 100% à la commande pour', 0, 421, 1, '2017-09-13 13:12:27', 1, '2017-09-13 16:35:45'),
(22, '6b2ab442af25dac3771500a7faf20a25', 'DEV_22_2017', 3, NULL, 1, '2017-09-13', 'P', NULL, 14554612, 17465534.4, 2910922.4, 'Paiement 100% à la commande pour tester la modif<br>', 1, 422, 1, '2017-09-13 13:26:53', 1, '2017-09-13 22:32:17'),
(23, '93a0f6bc29f45532f90f93e38c386447', 'DEV_23_2017', 2, 'N', 1, '2017-09-14', 'P', NULL, 1455454, 1455454, 0, 'Paiement 100% à la commande pour', 0, 420, 1, '2017-09-14 00:10:33', 1, '2017-09-14 00:11:59'),
(24, 'd1f71e861e9f2ba434b61038ca004d49', 'DEV_24_2017', 2, 'O', 1, '2017-09-18', 'M', 0, 8000000, 9600000, 1600000, '<ul><li>Paiement 100% à la commande</li><li>Livraison 30 jours après paiement</li></ul><p>Merci de nous avoir consulter.<br></p>', 1, 425, 1, '2017-09-18 22:33:06', NULL, NULL),
(25, '46b6e217ee3f8483a8aa81038f59af84', 'DEV_25_2017', 8, 'O', 1, '2017-09-21', 'P', NULL, 12, 14.4, 2.4, 'Paiement 100% à la commande pour', 1, 423, 1, '2017-09-21 23:56:44', NULL, NULL),
(26, '4b44f2cfca61a8567a8e02e2a49f1c94', 'DEV_26_2017', 3, 'O', 1, '2017-09-23', 'P', NULL, 1000490, 1200588, 200098, '<p>Paiement 100% à la commande pour</p><p><span style="background-color: rgb(255, 0, 0);">test Gare Casa port</span><br></p>', 1, 424, 1, '2017-09-23 12:26:18', NULL, NULL),
(27, 'f6ba0b3d5152d2375f648ab3a3a8c49d', 'DEV_27_2017', 8, 'N', 1, '2017-09-28', 'P', NULL, 11000100, 11000100, 0, '<p>Paiement 100% à la commande pour</p><p><br></p>', 1, 426, 1, '2017-09-28 13:42:10', NULL, NULL),
(28, 'e1143c7793e313283e438533b6167278', 'DEV_28_2017', 8, 'N', 1, '2017-09-29', 'P', NULL, 500095, 500095, 0, 'Paiement 100% à la commande pour', 0, NULL, 1, '2017-09-29 01:35:30', NULL, NULL);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=208 ;

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
(138, 1, 22, 'd41d8cd98f00b204e9800998ecf8427e', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(139, 1, 22, 'f6d9e309945b1c475a7405d208e9d94a', 3, 'Ref1', 'X1', 3, 12.3, 'P', 0, 0, NULL, NULL, 36, 44.28, 7.2, '1', NULL, '1', '2017-09-13 22:44:47'),
(140, 2, 22, 'f6d9e309945b1c475a7405d208e9d94a', 4, 'Ref2', 'Article 2 produit VAST', 14, 1455454, 'P', 0, 0, NULL, NULL, 20376356, 24451627.2, 4075271.2, '1', NULL, '1', '2017-09-13 22:52:58'),
(141, 1, 22, 'd6c6e6fcb30ae88f8503b0c18fd8ee72', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(142, 1, 22, 'cdfbb968a094366a25dd4e2f4f4cf4ab', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(143, 1, 22, 'fa4fc037789db0a180080040e9d00862', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(144, 1, 22, '25785014a6a41641f7e7454be6119477', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(146, 1, NULL, '97b85d9c363717a6489c4457422399ec', 3, 'Ref1', 'X1', 1, 12.3, 'P', 0, 0, NULL, NULL, 12, 14.76, 2.4, '1', NULL, NULL, NULL),
(147, 1, NULL, '2f12865e0bb46c504c9f564803ee204a', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(148, 1, NULL, '10132974a1a9a98d51ba680e37ed6dbf', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(149, 1, NULL, '722bcda858f85909e5ad0229d9486194', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(150, 1, 22, '25785014a6a41641f7e7454be6119477', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(151, 1, 22, 'fa4fc037789db0a180080040e9d00862', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(152, 1, 22, 'fa4fc037789db0a180080040e9d00862', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(153, 1, 22, 'fa4fc037789db0a180080040e9d00862', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(154, 1, 22, 'fa4fc037789db0a180080040e9d00862', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(155, 1, 22, 'fa4fc037789db0a180080040e9d00862', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(156, 1, 22, 'fa4fc037789db0a180080040e9d00862', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(157, 1, 22, 'fa4fc037789db0a180080040e9d00862', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(158, 1, 22, 'fa4fc037789db0a180080040e9d00862', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(159, 1, 22, 'fa4fc037789db0a180080040e9d00862', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(160, 1, 22, 'fa4fc037789db0a180080040e9d00862', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(161, 1, 22, 'fa4fc037789db0a180080040e9d00862', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(162, 1, 22, 'fa4fc037789db0a180080040e9d00862', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(163, 1, 22, 'fa4fc037789db0a180080040e9d00862', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(164, 1, 22, 'fa4fc037789db0a180080040e9d00862', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(165, 1, 22, 'fa4fc037789db0a180080040e9d00862', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(166, 1, 22, 'cdfbb968a094366a25dd4e2f4f4cf4ab', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(167, 1, 22, 'd6c6e6fcb30ae88f8503b0c18fd8ee72', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(168, 1, 22, 'd6c6e6fcb30ae88f8503b0c18fd8ee72', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(169, 1, 22, 'd6c6e6fcb30ae88f8503b0c18fd8ee72', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(170, 1, 22, 'd6c6e6fcb30ae88f8503b0c18fd8ee72', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(171, 1, 22, 'd6c6e6fcb30ae88f8503b0c18fd8ee72', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(172, 1, 22, 'd6c6e6fcb30ae88f8503b0c18fd8ee72', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(173, 1, 22, 'd6c6e6fcb30ae88f8503b0c18fd8ee72', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(174, 1, 22, 'd6c6e6fcb30ae88f8503b0c18fd8ee72', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(175, 1, 22, 'd6c6e6fcb30ae88f8503b0c18fd8ee72', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(176, 1, 22, 'd6c6e6fcb30ae88f8503b0c18fd8ee72', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(177, 1, 22, 'd6c6e6fcb30ae88f8503b0c18fd8ee72', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(178, 1, 22, 'd6c6e6fcb30ae88f8503b0c18fd8ee72', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(179, 1, 24, 'd1f71e861e9f2ba434b61038ca004d49', 4, 'Ref2', 'Article 2 produit VAST', 10, 1000000, 'M', 20, 0, NULL, NULL, 8000000, 9600000, 1600000, '1', NULL, NULL, NULL),
(180, 2, NULL, 'd41d8cd98f00b204e9800998ecf8427e', 3, 'Ref1', 'X1', 1, 12.3, 'P', 0, 0, NULL, NULL, 12, 14.76, 2.4, '1', NULL, NULL, NULL),
(181, 1, NULL, '5f6bcefb829fc5ddeba59c591d688cef', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(182, 1, NULL, 'H-dE0Xz_8Y-_vW5m10f659I_9dM5a95b', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(183, 1, NULL, 'df766f2ad815d15873e169c917d6644e', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(184, 1, 25, '46b6e217ee3f8483a8aa81038f59af84', 3, 'Ref1', 'X1', 1, 12.3, 'P', 0, 0, NULL, NULL, 12, 14.76, 2.4, '1', NULL, NULL, NULL),
(185, 1, NULL, '8622792d1a1a3d98bb1364ef9887ac0a', 4, 'Ref2', 'Article 2 produit VAST', 1, 1000000, 'P', 0, 0, NULL, NULL, 1000000, 1200000, 0, '1', NULL, NULL, NULL),
(186, 1, NULL, '69308dc46b8eb21c9b19799744552772', 4, 'Ref2', 'Article 2 produit VAST', 1, 1000000, 'P', 0, 0, NULL, NULL, 1000000, 1200000, 0, '1', NULL, NULL, NULL),
(187, 1, NULL, '37770156fa3256b5c0c9840aefa4d5f3', 4, 'Ref2', 'Article 2 produit VAST', 1, 1000000, 'P', 0, 0, NULL, NULL, 1000000, 1200000, 0, '1', NULL, NULL, NULL),
(188, 1, NULL, '85e24e5b37071861170ec4904b486a4e', 4, 'Ref2', 'Article 2 produit VAST', 1, 1000000, 'P', 0, 0, NULL, NULL, 1000000, 1200000, 0, '1', NULL, NULL, NULL),
(189, 1, 26, '4b44f2cfca61a8567a8e02e2a49f1c94', 4, 'Ref2', 'Article 2 produit VAST', 1, 1000000, 'M', 0.001, 0, NULL, NULL, 999990, 1199988, 0, '1', NULL, NULL, NULL),
(190, 2, 26, '4b44f2cfca61a8567a8e02e2a49f1c94', 3, 'Ref1', 'X1', 5, 100, 'P', 0, 0, NULL, NULL, 500, 600, 90, '1', NULL, '1', '2017-09-23 12:23:06'),
(191, 1, NULL, 'cdc24e8d7af74fde78201d43e4c20ef9', 4, 'Ref2', 'Article 2 produit VAST', 1, 1000000, 'P', 0, 0, NULL, NULL, 1000000, 1200000, 0, '1', NULL, NULL, NULL),
(192, 1, 27, 'f6ba0b3d5152d2375f648ab3a3a8c49d', 4, 'Ref2', 'Article 2 produit VAST', 10, 1000000, 'P', -10, 0, NULL, NULL, 11000000, 13200000, 0, '1', NULL, NULL, NULL),
(193, 2, 27, 'f6ba0b3d5152d2375f648ab3a3a8c49d', 3, 'Ref1', 'X1', 1, 100, 'P', 0, 0, NULL, NULL, 100, 120, 0, '1', NULL, NULL, NULL),
(194, 1, NULL, '001270b5a1a546818760a1c56be9bac6', 4, 'Ref2', 'Article 2 produit VAST', 1, 1000000, 'P', 0, 0, NULL, NULL, 1000000, 1180000, 0, '1', NULL, NULL, NULL),
(195, 2, NULL, '001270b5a1a546818760a1c56be9bac6', 3, 'Ref1', 'X1', 1, 100, 'P', 0, NULL, NULL, NULL, 100, 118, 0, '1', NULL, NULL, NULL),
(196, 1, 28, 'e1143c7793e313283e438533b6167278', 4, 'Ref2', 'Article 2 produit VAST', 1, 1000000, 'P', 50, NULL, NULL, NULL, 500000, 590000, 0, '1', NULL, '1', '2017-09-29 01:30:45'),
(197, 2, 28, 'e1143c7793e313283e438533b6167278', 3, 'Ref1', 'X1', 1, 100, 'P', 5, 0, NULL, NULL, 95, 104.5, 9.5, '1', NULL, '1', '2017-09-29 01:35:19'),
(198, 1, NULL, '91d04b78fa2ab2b2bab134726a3d8a67', 4, 'Ref2', 'Article 2 produit VAST', 1, 1000000, 'P', 0, 0, NULL, NULL, 1000000, 1000000, 0, '1', NULL, NULL, NULL),
(199, 1, NULL, '5b4cd244a84b6494ee0fc9f034931bde', 4, 'Ref2', 'Article 2 produit VAST', 1, 1000000, 'P', 0, 0, NULL, NULL, 1000000, 1180000, 180000, '1', NULL, NULL, NULL),
(200, 1, NULL, '6438a3d784cdee70a369243ae8bbf580', 4, 'Ref2', 'Article 2 produit VAST', 1, 1000000, 'P', 0, 0, NULL, NULL, 1000000, 1000000, 0, '1', NULL, NULL, NULL),
(201, 1, NULL, 'b7bcf93b849f56950f29b99d28aada70', 4, 'Ref2', 'Article 2 produit VAST', 1, 1000000, 'P', 0, 0, NULL, NULL, 1000000, 1180000, 180000, '1', NULL, NULL, NULL),
(202, 1, NULL, '90f464ba63cb224079156ecc23f11b54', 4, 'Ref2', 'Article 2 produit VAST', 1, 1000000, 'P', 0, 0, NULL, NULL, 1000000, 1000000, 0, '1', NULL, NULL, NULL),
(203, 1, NULL, 'f208b7955929d8295d32689417ae2931', 4, 'Ref2', 'Article 2 produit VAST', 1, 1000000, 'P', 0, 0, NULL, NULL, 1000000, 1180000, 180000, '1', NULL, NULL, NULL),
(204, 1, NULL, 'd68b1d94b62642522107e7e3086899ef', 4, 'Ref2', 'Article 2 produit VAST', 1, 1000000, 'P', 0, 0, NULL, NULL, 1000000, 1180000, 180000, '1', NULL, NULL, NULL),
(205, 1, NULL, '756dbf5292b2c65fff566558542df70a', 4, 'Ref2', 'Article 2 produit VAST', 1, 1000000, 'P', 0, 0, NULL, NULL, 1000000, 1000000, 0, '1', NULL, NULL, NULL),
(206, 1, NULL, 'da79f8c673aae7cac682def018a751f7', 4, 'Ref2', 'Article 2 produit VAST', 1, 1000000, 'P', 0, 0, NULL, NULL, 1000000, 1180000, 180000, '1', NULL, NULL, NULL),
(207, 1, NULL, '6104fad60657c00810277dabb6b48e59', 4, 'Ref2', 'Article 2 produit VAST', 1, 1000000, 'P', 0, 0, NULL, NULL, 1000000, 1000000, 0, '1', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `d_proforma`
--

CREATE TABLE IF NOT EXISTS `d_proforma` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order` int(5) DEFAULT NULL,
  `id_proforma` int(11) DEFAULT NULL,
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=194 ;

--
-- Contenu de la table `d_proforma`
--

INSERT INTO `d_proforma` (`id`, `order`, `id_proforma`, `tkn_frm`, `id_produit`, `ref_produit`, `designation`, `qte`, `prix_unitaire`, `type_remise`, `remise_valeur`, `tva`, `prix_ht`, `prix_ttc`, `total_ht`, `total_ttc`, `total_tva`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
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
(138, 1, 22, 'd41d8cd98f00b204e9800998ecf8427e', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(139, 1, 22, 'f6d9e309945b1c475a7405d208e9d94a', 3, 'Ref1', 'X1', 3, 12.3, 'P', 0, 0, NULL, NULL, 36, 44.28, 7.2, '1', NULL, '1', '2017-09-13 22:44:47'),
(140, 2, 22, 'f6d9e309945b1c475a7405d208e9d94a', 4, 'Ref2', 'Article 2 produit VAST', 14, 1455454, 'P', 0, 0, NULL, NULL, 20376356, 24451627.2, 4075271.2, '1', NULL, '1', '2017-09-13 22:52:58'),
(141, 1, 22, 'd6c6e6fcb30ae88f8503b0c18fd8ee72', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(142, 1, 22, 'cdfbb968a094366a25dd4e2f4f4cf4ab', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(143, 1, 22, 'fa4fc037789db0a180080040e9d00862', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(144, 1, 22, '25785014a6a41641f7e7454be6119477', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(145, 1, 23, '93a0f6bc29f45532f90f93e38c386447', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(146, 1, NULL, '97b85d9c363717a6489c4457422399ec', 3, 'Ref1', 'X1', 1, 12.3, 'P', 0, 0, NULL, NULL, 12, 14.76, 2.4, '1', NULL, NULL, NULL),
(147, 1, NULL, '2f12865e0bb46c504c9f564803ee204a', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(148, 1, NULL, '10132974a1a9a98d51ba680e37ed6dbf', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(149, 1, NULL, '722bcda858f85909e5ad0229d9486194', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(150, 1, 22, '25785014a6a41641f7e7454be6119477', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(151, 1, 22, 'fa4fc037789db0a180080040e9d00862', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(152, 1, 22, 'fa4fc037789db0a180080040e9d00862', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(153, 1, 22, 'fa4fc037789db0a180080040e9d00862', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(154, 1, 22, 'fa4fc037789db0a180080040e9d00862', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(155, 1, 22, 'fa4fc037789db0a180080040e9d00862', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(156, 1, 22, 'fa4fc037789db0a180080040e9d00862', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(157, 1, 22, 'fa4fc037789db0a180080040e9d00862', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(158, 1, 22, 'fa4fc037789db0a180080040e9d00862', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(159, 1, 22, 'fa4fc037789db0a180080040e9d00862', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(160, 1, 22, 'fa4fc037789db0a180080040e9d00862', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(161, 1, 22, 'fa4fc037789db0a180080040e9d00862', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(162, 1, 22, 'fa4fc037789db0a180080040e9d00862', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(163, 1, 22, 'fa4fc037789db0a180080040e9d00862', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(164, 1, 22, 'fa4fc037789db0a180080040e9d00862', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(165, 1, 22, 'fa4fc037789db0a180080040e9d00862', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(166, 1, 22, 'cdfbb968a094366a25dd4e2f4f4cf4ab', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(167, 1, 22, 'd6c6e6fcb30ae88f8503b0c18fd8ee72', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(168, 1, 22, 'd6c6e6fcb30ae88f8503b0c18fd8ee72', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(169, 1, 22, 'd6c6e6fcb30ae88f8503b0c18fd8ee72', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(170, 1, 22, 'd6c6e6fcb30ae88f8503b0c18fd8ee72', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(171, 1, 22, 'd6c6e6fcb30ae88f8503b0c18fd8ee72', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(172, 1, 22, 'd6c6e6fcb30ae88f8503b0c18fd8ee72', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(173, 1, 22, 'd6c6e6fcb30ae88f8503b0c18fd8ee72', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(174, 1, 22, 'd6c6e6fcb30ae88f8503b0c18fd8ee72', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(175, 1, 22, 'd6c6e6fcb30ae88f8503b0c18fd8ee72', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(176, 1, 22, 'd6c6e6fcb30ae88f8503b0c18fd8ee72', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(177, 1, 22, 'd6c6e6fcb30ae88f8503b0c18fd8ee72', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(178, 1, 22, 'd6c6e6fcb30ae88f8503b0c18fd8ee72', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(179, 1, 24, 'd1f71e861e9f2ba434b61038ca004d49', 4, 'Ref2', 'Article 2 produit VAST', 10, 1000000, 'M', 20, 0, NULL, NULL, 8000000, 9600000, 1600000, '1', NULL, NULL, NULL),
(180, 2, NULL, 'd41d8cd98f00b204e9800998ecf8427e', 3, 'Ref1', 'X1', 1, 12.3, 'P', 0, 0, NULL, NULL, 12, 14.76, 2.4, '1', NULL, NULL, NULL),
(181, 1, NULL, '5f6bcefb829fc5ddeba59c591d688cef', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(182, 1, NULL, 'H-dE0Xz_8Y-_vW5m10f659I_9dM5a95b', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(183, 1, NULL, 'df766f2ad815d15873e169c917d6644e', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(184, 1, 25, '46b6e217ee3f8483a8aa81038f59af84', 3, 'Ref1', 'X1', 1, 12.3, 'P', 0, 0, NULL, NULL, 12, 14.76, 2.4, '1', NULL, NULL, NULL),
(185, 1, NULL, '8622792d1a1a3d98bb1364ef9887ac0a', 4, 'Ref2', 'Article 2 produit VAST', 1, 1000000, 'P', 0, 0, NULL, NULL, 1000000, 1200000, 0, '1', NULL, NULL, NULL),
(186, 1, NULL, '69308dc46b8eb21c9b19799744552772', 4, 'Ref2', 'Article 2 produit VAST', 1, 1000000, 'P', 0, 0, NULL, NULL, 1000000, 1200000, 0, '1', NULL, NULL, NULL),
(187, 1, NULL, '37770156fa3256b5c0c9840aefa4d5f3', 4, 'Ref2', 'Article 2 produit VAST', 1, 1000000, 'P', 0, 0, NULL, NULL, 1000000, 1200000, 0, '1', NULL, NULL, NULL),
(188, 1, NULL, '85e24e5b37071861170ec4904b486a4e', 4, 'Ref2', 'Article 2 produit VAST', 1, 1000000, 'P', 0, 0, NULL, NULL, 1000000, 1200000, 0, '1', NULL, NULL, NULL),
(189, 1, 26, '4b44f2cfca61a8567a8e02e2a49f1c94', 4, 'Ref2', 'Article 2 produit VAST', 1, 1000000, 'M', 0.001, 0, NULL, NULL, 999990, 1199988, 0, '1', NULL, NULL, NULL),
(190, 2, 26, '4b44f2cfca61a8567a8e02e2a49f1c94', 3, 'Ref1', 'X1', 5, 100, 'P', 0, 0, NULL, NULL, 500, 600, 90, '1', NULL, '1', '2017-09-23 12:23:06'),
(191, 1, NULL, 'cdc24e8d7af74fde78201d43e4c20ef9', 4, 'Ref2', 'Article 2 produit VAST', 1, 1000000, 'P', 0, 0, NULL, NULL, 1000000, 1200000, 0, '1', NULL, NULL, NULL),
(192, 1, NULL, '45c0a3839cb0597ab38154db9be7dd6d', 4, 'Ref2', 'Article 2 produit VAST', 1, 1000000, 'P', 0, 0, NULL, NULL, 1000000, 1000000, 0, '1', NULL, NULL, NULL),
(193, 1, NULL, '3ba900be139ac1e920eeca1176f061e9', 4, 'Ref2', 'Article 2 produit VAST', 2, 1000000, 'P', 0, 0, NULL, NULL, 2000000, 2000000, 0, '1', NULL, '1', '2017-09-29 16:08:46');

-- --------------------------------------------------------

--
-- Structure de la table `espionnage_update`
--

CREATE TABLE IF NOT EXISTS `espionnage_update` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `updt_id` varchar(32) NOT NULL COMMENT 'ID of Update operation',
  `table` varchar(25) NOT NULL COMMENT 'Table espionné',
  `id_item` int(11) NOT NULL COMMENT 'ID de ligne modifié',
  `column` varchar(25) NOT NULL COMMENT 'Column modifié',
  `val_old` varchar(200) DEFAULT NULL COMMENT 'Valeur avant',
  `val_new` varchar(200) DEFAULT NULL COMMENT 'Valeur Aprés',
  `user` varchar(25) NOT NULL COMMENT 'Utilisateur modif',
  `updtdat` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date modification',
  PRIMARY KEY (`id`),
  UNIQUE KEY `updt_id` (`id`,`updt_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Contenu de la table `espionnage_update`
--

INSERT INTO `espionnage_update` (`id`, `updt_id`, `table`, `id_item`, `column`, `val_old`, `val_new`, `user`, `updtdat`) VALUES
(1, '658c7ba5100d6283f8d23a3c5ba2f84d', 'users_sys', 1, 'pass', '5a05679021426829ab75ac9fa6655947', 'd41d8cd98f00b204e9800998ecf8427e', 'admin', '2017-08-28 19:34:49'),
(2, 'e36bbda60811d04e120e0c5ce05e764a', 'users_sys', 19, 'mail', 'testetr@dctchad.com', 'testetr@dctchad.cc', 'admin', '2017-09-03 10:55:41'),
(3, '5d579c411f92421868cbf007e92d4d67', 'users_sys', 1, 'pass', '5a05679021426829ab75ac9fa6655947', 'd41d8cd98f00b204e9800998ecf8427e', 'admin', '2017-09-03 10:58:19'),
(4, '5d579c411f92421868cbf007e92d4d67', 'users_sys', 1, 'service', '2', '3', 'admin', '2017-09-03 10:58:19'),
(5, '5d579c411f92421868cbf007e92d4d67', 'users_sys', 1, 'etat', '1', '0', 'admin', '2017-09-03 10:58:19'),
(6, '1268a6054fef5ff77b8488ed66958aa0', 'users_sys', 2, 'service', '1', '2', 'admin', '2017-09-03 12:21:50'),
(7, 'a20133a01c90f7eaf9d82f55050087f3', 'users_sys', 20, 'pass', '5a05679021426829ab75ac9fa6655947', 'd41d8cd98f00b204e9800998ecf8427e', 'admin', '2017-09-03 13:36:03'),
(8, 'a20133a01c90f7eaf9d82f55050087f3', 'users_sys', 20, 'service', '3', '2', 'admin', '2017-09-03 13:36:03');

-- --------------------------------------------------------

--
-- Structure de la table `forgot`
--

CREATE TABLE IF NOT EXISTS `forgot` (
  `token` varchar(32) NOT NULL,
  `user` int(2) NOT NULL,
  `etat` int(11) NOT NULL,
  `dat` datetime NOT NULL,
  `expir` datetime NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id demande Forgot',
  `ip` varchar(16) DEFAULT NULL COMMENT 'Ip Demande',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Table recovery MDP user' AUTO_INCREMENT=2 ;

--
-- Contenu de la table `forgot`
--

INSERT INTO `forgot` (`token`, `user`, `etat`, `dat`, `expir`, `id`, `ip`) VALUES
('37e9f8258bdf19290f6278f1fbd7c736', 1, 0, '2017-09-24 15:36:49', '2017-09-26 15:36:49', 1, '::1');

-- --------------------------------------------------------

--
-- Structure de la table `fournisseurs`
--

CREATE TABLE IF NOT EXISTS `fournisseurs` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `code` varchar(10) NOT NULL COMMENT 'Code fournisseur',
  `denomination` varchar(200) NOT NULL COMMENT 'Denomination du client',
  `r_social` varchar(200) DEFAULT NULL COMMENT 'Raison social',
  `r_commerce` varchar(100) DEFAULT NULL COMMENT 'Registre de commerce',
  `nif` varchar(20) DEFAULT NULL COMMENT 'Id fiscale',
  `nom` varchar(100) DEFAULT NULL COMMENT 'Nom',
  `prenom` varchar(100) DEFAULT NULL COMMENT 'Prénom',
  `civilite` varchar(10) DEFAULT NULL COMMENT 'Sexe',
  `adresse` varchar(200) NOT NULL COMMENT 'Adresse',
  `id_pays` int(11) NOT NULL COMMENT 'Pays',
  `id_ville` int(11) NOT NULL COMMENT 'Ville',
  `tel` varchar(80) NOT NULL COMMENT 'Telephone',
  `fax` varchar(80) DEFAULT NULL COMMENT 'Fax',
  `bp` varchar(80) DEFAULT NULL COMMENT 'Boite postale',
  `email` varchar(100) NOT NULL COMMENT 'E-mail',
  `rib` varchar(30) DEFAULT NULL COMMENT 'compte bancaire du fournisseur',
  `id_devise` int(11) DEFAULT NULL COMMENT 'Devise de facturation du client',
  `pj` int(11) DEFAULT NULL,
  `pj_photo` int(11) DEFAULT NULL COMMENT 'photo du client',
  `etat` int(11) DEFAULT '0',
  `creusr` int(11) DEFAULT NULL,
  `credat` datetime DEFAULT CURRENT_TIMESTAMP,
  `updusr` int(11) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_code` (`code`),
  KEY `fk_client_ville` (`id_ville`),
  KEY `fk_client_pays` (`id_pays`),
  KEY `fk_client_devise` (`id_devise`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

--
-- Contenu de la table `fournisseurs`
--

INSERT INTO `fournisseurs` (`id`, `code`, `denomination`, `r_social`, `r_commerce`, `nif`, `nom`, `prenom`, `civilite`, `adresse`, `id_pays`, `id_ville`, `tel`, `fax`, `bp`, `email`, `rib`, `id_devise`, `pj`, `pj_photo`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(1, 'CP122', 'frn1', NULL, NULL, NULL, NULL, NULL, 'Femme', 'adr12', 242, 43, '064333333333333333333333', '033333333333333333333333', NULL, 'em@em', NULL, 1, 425, 426, 0, 1, '2017-08-26 12:59:00', 1, '2017-09-20 18:09:35'),
(2, 'CP123', 'frn2', NULL, NULL, NULL, NULL, NULL, 'Homme', 'adress', 242, 43, '0444444444444444444444444444', NULL, NULL, 'em@em', NULL, 2, 428, 429, 1, 1, '2017-08-26 13:51:29', 1, '2017-09-20 17:27:57'),
(8, 'CP444', 'DENOMI9', 'RS', NULL, NULL, NULL, NULL, 'Femme', 'adr1', 242, 2, '0444444444444444444444444', NULL, NULL, 'em@em', NULL, 1, 430, 408, 0, 1, '2017-08-26 15:29:40', 1, '2017-09-20 17:45:36'),
(15, 'PRFF', 'fefeg', 'lmlgrl,rleg', NULL, NULL, NULL, NULL, 'Femme', 'grdgdr', 242, 0, '04555555555555555', NULL, NULL, 'em@em', NULL, 1, 412, 413, 1, 1, '2017-08-26 18:05:06', 1, '2017-09-20 17:46:09'),
(17, 'NJKL', 'den', 'rf', NULL, NULL, NULL, NULL, 'Femme', 'paris', 75, 0, '044444444444444', NULL, 'bpppp', 'em@em', NULL, 1, NULL, NULL, 0, 1, '2017-09-20 18:12:54', 1, '2017-09-20 21:22:57'),
(19, 'FRN-1/2017', 'frns test', NULL, NULL, NULL, NULL, NULL, 'Femme', 'dlajoljda', 242, 0, '089999933333333', NULL, NULL, 'em@em', NULL, 1, NULL, NULL, 0, 1, '2017-09-22 22:54:24', 1, '2017-09-22 22:55:25');

-- --------------------------------------------------------

--
-- Structure de la table `modul`
--

CREATE TABLE IF NOT EXISTS `modul` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id modul',
  `modul` varchar(25) NOT NULL COMMENT 'nom modul',
  `description` varchar(100) NOT NULL COMMENT 'Description',
  `rep_modul` varchar(100) DEFAULT NULL COMMENT 'Répertoir module',
  `tables` varchar(100) DEFAULT NULL COMMENT 'Tables utlisées par le module',
  `app_modul` varchar(25) NOT NULL COMMENT 'Application de base',
  `modul_setting` varchar(25) DEFAULT 'NA' COMMENT 'Si is_setting Choix modul',
  `is_setting` tinyint(1) DEFAULT '0' COMMENT 'Modul de parametre',
  `etat` int(11) NOT NULL DEFAULT '0' COMMENT 'Etat de module',
  `services` varchar(40) DEFAULT NULL COMMENT 'Services de Module',
  PRIMARY KEY (`id`),
  UNIQUE KEY `modul` (`modul`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Table Systeme Modules' AUTO_INCREMENT=44 ;

--
-- Contenu de la table `modul`
--

INSERT INTO `modul` (`id`, `modul`, `description`, `rep_modul`, `tables`, `app_modul`, `modul_setting`, `is_setting`, `etat`, `services`) VALUES
(1, 'Systeme', 'Applications utilises par le Systeme', 'tdb', NULL, 'tdb', NULL, 0, 10, '[-1-2-]'),
(2, 'users', 'Utilisateurs', 'users', 'users_sys', 'user', NULL, 0, 0, '[-1-2-3-]'),
(3, 'modul_mgr', 'Modules', 'modul_mgr', 'modul,task,task_action', 'modul', NULL, 0, 0, '[-1-2-]'),
(4, 'services', 'Services', 'users/settings/services', 'services', 'services', 'users', 1, 0, '[-1-2-]'),
(7, 'produits', 'Gestion des produits', 'produits', 'produits, ref_unites_vente, ref_categories_produits, ref_types_produits', 'produits', NULL, 0, 0, '[-1-2-]'),
(13, 'categorie_client', 'Gestion Catégorie Client', 'clients/settings/categorie_client', 'categorie_client', 'categorie_client', 'clients', 1, 0, '[-1-2-]'),
(14, 'clients', 'Gestion Clients', 'clients', 'clients', 'clients', NULL, 0, 0, '[-1-2-]'),
(20, 'vente', 'Gestion Vente', 'vente/main', 'devis', 'vente', NULL, 0, 0, '[-1-2-]'),
(21, 'devis', 'Gestion Devis', 'vente/submodul/devis', 'devis', 'devis', 'vente', 2, 0, '[-1-2-]'),
(24, 'pays', 'Gestion Pays', 'Systeme/settings/pays', 'ref_pays', 'pays', 'Systeme', 1, 0, '[-1-]'),
(27, 'info_ste', 'Information société', 'Systeme/settings/info_ste', 'ste_info', 'info_ste', 'Systeme', 1, 0, '[-1-3-]'),
(28, 'contrats_fournisseurs', 'Contrats Fournisseur', 'contrats_fournisseurs/main', 'contrats_frn', 'contrats_fournisseurs', NULL, 0, 0, '[-1-]'),
(29, 'fournisseurs', 'Gestion Fournisseurs', 'contrats_fournisseurs/submodul/fournisseurs', 'fournisseurs', 'fournisseurs', 'contrats_fournisseurs', 2, 0, '[-1-]'),
(30, 'villes', 'Gestion Villes', 'Systeme/settings/villes', 'ref_villes', 'villes', 'Systeme', 1, 0, '[-1-]'),
(31, 'categories_produits', 'Gestion des catégories de produits', 'produits/settings/categories_produits', 'ref_categories_produits', 'categories_produits', 'produits', 1, 0, '[-1-]'),
(32, 'types_produits', 'Gestion des types de produits', 'produits/settings/types_produits', 'ref_types_produits', 'types_produits', 'produits', 1, 0, '[-1-]'),
(33, 'unites_vente', 'Gestion des unités de vente', 'produits/settings/unites_vente', 'ref_unites_vente', 'unites_vente', 'produits', 1, 0, '[-1-]'),
(34, 'contrats', 'Abonnements', 'vente/submodul/contrats', 'contrats', 'contrats', 'vente', 2, 0, '[-1-]'),
(35, 'regions', 'Gestion des régions', 'Systeme/settings/regions', 'ref_region', 'regions', 'Systeme', 1, 0, '[-1-]'),
(36, 'departements', 'Gestion Départements', 'Systeme/settings/departements', 'ref_departement', 'departements', 'Systeme', 1, 0, '[-1-]'),
(37, 'type_echeance', 'Gestion Type Echeance', 'contrats/settings/type_echeance', 'ref_type_echeance', 'type_echeance', 'contrats', 1, 0, '[-1-]'),
(43, 'proforma', 'Gestion Proforma', 'vente/submodul/proforma', 'proforma', 'proforma', 'vente', 2, 0, '[-1-2-3-5-4-]');

-- --------------------------------------------------------

--
-- Structure de la table `produits`
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
-- Contenu de la table `produits`
--

INSERT INTO `produits` (`id`, `ref`, `designation`, `pu`, `stock_min`, `idcategorie`, `iduv`, `idtype`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(3, 'Ref1', 'X1', 100, 3, 3, 2, 2, 0, 1, '2017-08-26 20:01:19', NULL, NULL),
(4, 'Ref2', 'Article 2 produit VAST', 1000000, 3, 3, 2, 2, 0, 1, '2017-08-26 20:01:19', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `proforma`
--

CREATE TABLE IF NOT EXISTS `proforma` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tkn_frm` varchar(32) DEFAULT NULL COMMENT 'Token Form insert',
  `reference` varchar(20) DEFAULT NULL,
  `id_client` int(11) DEFAULT NULL,
  `tva` varchar(1) DEFAULT 'O' COMMENT 'Soumis à la TVA',
  `id_commercial` int(11) DEFAULT NULL COMMENT 'commercial chargé du suivi',
  `date_proforma` date DEFAULT NULL,
  `type_remise` varchar(1) DEFAULT 'P' COMMENT 'type de remise',
  `valeur_remise` double DEFAULT '0' COMMENT 'Valeur de la remise',
  `totalht` double DEFAULT '0' COMMENT 'total ht des articles',
  `totalttc` double unsigned DEFAULT '0' COMMENT 'total ttc des articles',
  `totaltva` double DEFAULT '0' COMMENT 'total tva des articles',
  `claus_comercial` text COMMENT 'clauses commercial devis',
  `etat` int(11) DEFAULT '0' COMMENT 'Etat devis defaut 0',
  `proforma_pdf` int(11) DEFAULT NULL COMMENT 'Generated pdf (archive table)',
  `creusr` int(11) DEFAULT NULL,
  `credat` datetime DEFAULT NULL,
  `updusr` int(11) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_id_client` (`id_client`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=27 ;

--
-- Contenu de la table `proforma`
--

INSERT INTO `proforma` (`id`, `tkn_frm`, `reference`, `id_client`, `tva`, `id_commercial`, `date_proforma`, `type_remise`, `valeur_remise`, `totalht`, `totalttc`, `totaltva`, `claus_comercial`, `etat`, `proforma_pdf`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(13, '5e4d828bb3803694724801c841ecfd02', 'DEV_13_2017', 3, NULL, 1, '2017-09-13', 'P', NULL, 1455454, 1746544.8, 291090.8, 'Paiement 100% à la commande pour', 0, 418, 1, '2017-09-13 01:56:53', NULL, NULL),
(21, 'ef4c85458e75208c1f1028d733ef3450', 'DEV_21_2017', 8, NULL, 1, '2017-09-05', 'P', NULL, 1455466, 1746559.2, 291093.2, 'Paiement 100% à la commande pour', 0, 421, 1, '2017-09-13 13:12:27', 1, '2017-09-13 16:35:45'),
(22, '6b2ab442af25dac3771500a7faf20a25', 'DEV_22_2017', 3, NULL, 1, '2017-09-13', 'P', NULL, 14554612, 17465534.4, 2910922.4, 'Paiement 100% à la commande pour tester la modif<br>', 1, 422, 1, '2017-09-13 13:26:53', 1, '2017-09-13 22:32:17'),
(23, '93a0f6bc29f45532f90f93e38c386447', 'DEV_23_2017', 2, 'N', 1, '2017-09-14', 'P', NULL, 1455454, 1455454, 0, 'Paiement 100% à la commande pour', 0, 420, 1, '2017-09-14 00:10:33', 1, '2017-09-14 00:11:59'),
(24, 'd1f71e861e9f2ba434b61038ca004d49', 'DEV_24_2017', 2, 'O', 1, '2017-09-18', 'M', 0, 8000000, 9600000, 1600000, '<ul><li>Paiement 100% à la commande</li><li>Livraison 30 jours après paiement</li></ul><p>Merci de nous avoir consulter.<br></p>', 1, 425, 1, '2017-09-18 22:33:06', NULL, NULL),
(25, '46b6e217ee3f8483a8aa81038f59af84', 'DEV_25_2017', 8, 'O', 1, '2017-09-21', 'P', NULL, 12, 14.4, 2.4, 'Paiement 100% à la commande pour', 1, 423, 1, '2017-09-21 23:56:44', NULL, NULL),
(26, '4b44f2cfca61a8567a8e02e2a49f1c94', 'DEV_26_2017', 3, 'O', 1, '2017-09-23', 'P', NULL, 1000490, 1200588, 200098, '<p>Paiement 100% à la commande pour</p><p><span style="background-color: rgb(255, 0, 0);">test Gare Casa port</span><br></p>', 1, 424, 1, '2017-09-23 12:26:18', NULL, NULL);

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

-- --------------------------------------------------------

--
-- Structure de la table `ref_categ_prm`
--

CREATE TABLE IF NOT EXISTS `ref_categ_prm` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID table',
  `categorie` varchar(75) NOT NULL COMMENT 'Catergorie',
  `abriv` varchar(25) DEFAULT NULL COMMENT 'Abreviation',
  PRIMARY KEY (`id`,`categorie`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Contenu de la table `ref_categ_prm`
--

INSERT INTO `ref_categ_prm` (`id`, `categorie`, `abriv`) VALUES
(1, 'Gouvernement', 'Gouv'),
(2, 'ONG', 'ONG'),
(3, 'Mission diplomate', 'Diplomate'),
(4, 'Secteur privé', 'Privé'),
(5, 'Etablissement Etatique', 'Ets Etat');

-- --------------------------------------------------------

--
-- Structure de la table `ref_devise`
--

CREATE TABLE IF NOT EXISTS `ref_devise` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `devise` varchar(30) DEFAULT NULL,
  `abreviation` varchar(10) DEFAULT NULL,
  `etat` int(11) DEFAULT '0',
  `creusr` varchar(50) DEFAULT NULL,
  `credat` datetime DEFAULT CURRENT_TIMESTAMP,
  `updusr` varchar(50) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `ref_devise`
--

INSERT INTO `ref_devise` (`id`, `devise`, `abreviation`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(1, 'Franc CFA', 'FCFA', 1, '1', '2017-09-13 22:08:50', NULL, NULL),
(2, 'Dirham Maroc', 'DH', 1, '1', '2017-09-13 22:10:10', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `ref_pays`
--

CREATE TABLE IF NOT EXISTS `ref_pays` (
  `id` int(11) NOT NULL,
  `pays` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  `nation` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  `alpha` varchar(2) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `ref_pays`
--

INSERT INTO `ref_pays` (`id`, `pays`, `nation`, `alpha`) VALUES
(1, 'Afghanistan', '0', 'AF'),
(2, 'Albanie', 'Albanaise', 'AL'),
(3, 'Antarctique', '0', 'AQ'),
(4, 'Algérie', 'Algérienne', 'DZ'),
(5, 'Samoa Américaines', '0', 'AS'),
(6, 'Andorre', '0', 'AD'),
(7, 'Angola', 'angolaise', 'AO'),
(8, 'Antigua-et-Barbuda', '0', 'AG'),
(9, 'Azerbaïdjan', 'Azerbaïdjanaise', 'AZ'),
(10, 'Argentine', '0', 'AR'),
(11, 'Australie', 'Australienne', 'AU'),
(12, 'Autriche', 'Autrichienne', 'AT'),
(13, 'Bahamas', '0', 'BS'),
(14, 'Bahreïn', '0', 'BH'),
(15, 'Bangladesh', 'Bangladesh', 'BD'),
(16, 'Arménie', 'Arménienne', 'AM'),
(17, 'Barbade', '0', 'BB'),
(18, 'Belgique', 'Belge', 'BE'),
(19, 'Bermudes', '0', 'BM'),
(20, 'Bhoutan', '0', 'BT'),
(21, 'Bolivie', '0', 'BO'),
(22, 'Bosnie-Herzégovine', 'Bosniaque', 'BA'),
(23, 'Botswana', '0', 'BW'),
(24, 'Île Bouvet', '0', 'BV'),
(25, 'Brésil', 'Brésilienne', 'BR'),
(26, 'Belize', '0', 'BZ'),
(27, 'Territoire Britannique de l''Océan Indien', '0', 'IO'),
(28, 'Îles Salomon', '0', 'SB'),
(29, 'Îles Vierges Britanniques', '0', 'VG'),
(30, 'Brunéi Darussalam', '0', 'BN'),
(31, 'Bulgarie', 'Bulgare', 'BG'),
(32, 'Myanmar', '0', 'MM'),
(33, 'Burundi', 'Burundaise', 'BI'),
(34, 'Bélarus', '0', 'BY'),
(35, 'Cambodge', '0', 'KH'),
(36, 'Cameroun', 'Camerounaise', 'CM'),
(37, 'Canada', 'Canadienne', 'CA'),
(38, 'Cap-vert', '0', 'CV'),
(39, 'Îles Caïmanes', '0', 'KY'),
(40, 'République Centrafricaine', 'Centre africaine', 'CF'),
(41, 'Sri Lanka', '0', 'LK'),
(42, 'Tanzanie', 'Tanzanienne', 'TA'),
(43, 'Chili', '0', 'CL'),
(44, 'Chine', 'Chinoise', 'CN'),
(45, 'Taïwan', 'Taiwanaise', 'TW'),
(46, 'Île Christmas', '0', 'CX'),
(47, 'Îles Cocos (Keeling)', '0', 'CC'),
(48, 'Colombie', 'Colombienne', 'CO'),
(49, 'Comores', '0', 'KM'),
(50, 'Mayotte', '0', 'YT'),
(51, 'République du Congo', '0', 'CG'),
(52, 'République Démocratique du Congo', 'Congolaise', 'CD'),
(53, 'Îles Cook', '0', 'CK'),
(54, 'Costa Rica', '0', 'CR'),
(55, 'Croatie', '0', 'HR'),
(56, 'Cuba', '0', 'CU'),
(57, 'Chypre', '0', 'CY'),
(58, 'République Tchèque', '0', 'CZ'),
(59, 'Bénin', 'Beninoise', 'BJ'),
(60, 'Danemark', 'Danoise', 'DK'),
(61, 'Dominique', '0', 'DM'),
(62, 'République Dominicaine', 'Dominicaine', 'DO'),
(63, 'Équateur', '0', 'EC'),
(64, 'El Salvador', '0', 'SV'),
(65, 'Guinée Équatoriale', 'Equato-guineenne', 'GQ'),
(66, 'Éthiopie', 'Ethiopienne', 'ET'),
(67, 'Érythrée', '0', 'ER'),
(68, 'Estonie', '0', 'EE'),
(69, 'Îles Féroé', '0', 'FO'),
(70, 'Îles (malvinas) Falkland', '0', 'FK'),
(71, 'Géorgie du Sud et les Îles Sandwich du Sud', '0', 'GS'),
(72, 'Fidji', '0', 'FJ'),
(73, 'Finlande', 'Finlandaise', 'FI'),
(74, 'Îles Åland', '0', 'AX'),
(75, 'France', 'Française', 'FR'),
(76, 'Guyane Française', '0', 'GF'),
(77, 'Polynésie Française', '0', 'PF'),
(78, 'Terres Australes Françaises', '0', 'TF'),
(79, 'Djibouti', '0', 'DJ'),
(80, 'Gabon', 'Gabonaise', 'GA'),
(81, 'Géorgie', 'Géorgienne', 'GE'),
(82, 'Gambie', '0', 'GM'),
(83, 'Territoire Palestinien Occupé', '0', 'PS'),
(84, 'Allemagne', 'Allemande', 'DE'),
(85, 'Ghana', 'Ghanéenne', 'GH'),
(86, 'Gibraltar', '0', 'GI'),
(87, 'Kiribati', '0', 'KI'),
(88, 'Grèce', 'Hellenique', 'GR'),
(89, 'Groenland', '0', 'GL'),
(90, 'Grenade', '0', 'GD'),
(91, 'Guadeloupe', '0', 'GP'),
(92, 'Guam', '0', 'GU'),
(93, 'Guatemala', '0', 'GT'),
(94, 'Guinée', 'Guinéenne', 'GN'),
(95, 'Guyana', '0', 'GY'),
(96, 'Haïti', '0', 'HT'),
(97, 'Îles Heard et Mcdonald', '0', 'HM'),
(98, 'Saint-Siège (état de la Cité du Vatican)', '0', 'VA'),
(99, 'Honduras', '0', 'HN'),
(100, 'Hong-Kong', '0', 'HK'),
(101, 'Hongrie', 'Hongroise', 'HU'),
(102, 'Islande', '0', 'IS'),
(103, 'Inde', 'Indienne', 'IN'),
(104, 'Indonésie', 'Indonesienne', 'ID'),
(105, 'République Islamique d''Iran', '0', 'IR'),
(106, 'Iraq', 'irakienne', 'IQ'),
(107, 'Irlande', 'Irlandaise', 'IE'),
(108, 'Israël', 'Israelienne', 'IL'),
(109, 'Italie', 'Italienne', 'IT'),
(110, 'Cote d''Ivoire', 'Ivoirienne', 'CI'),
(111, 'Jamaïque', '0', 'JM'),
(112, 'Japon', '0', 'JP'),
(113, 'Kazakhstan', '0', 'KZ'),
(114, 'Jordanie', 'Jordanienne', 'JO'),
(115, 'Kenya', 'Kenyanne', 'KE'),
(116, 'République Populaire Démocratique de Corée', '0', 'KP'),
(117, 'République de Corée', 'Coréenne', 'KR'),
(118, 'Koweït', '0', 'KW'),
(119, 'Kirghizistan', '0', 'KG'),
(120, 'République Démocratique Populaire Lao', '0', 'LA'),
(121, 'Liban', 'Libanaise', 'LB'),
(122, 'Lesotho', '0', 'LS'),
(123, 'Lettonie', 'Lettone', 'LV'),
(124, 'Libéria', '0', 'LR'),
(125, 'Jamahiriya Arabe Libyenne', 'Libyenne', 'LY'),
(126, 'Liechtenstein', '0', 'LI'),
(127, 'Lituanie', 'Lituanienne', 'LT'),
(128, 'Luxembourg', '0', 'LU'),
(129, 'Macao', '0', 'MO'),
(130, 'Madagascar', 'Malgache', 'MG'),
(131, 'Malawi', '0', 'MW'),
(132, 'Malaisie', 'Malaisienne', 'MY'),
(133, 'Maldives', '0', 'MV'),
(134, 'Mali', 'Malienne', 'ML'),
(135, 'Malte', '0', 'MT'),
(136, 'Martinique', '0', 'MQ'),
(137, 'Mauritanie', 'Mauritanienne', 'MR'),
(138, 'Maurice', 'Mauricienne', 'MU'),
(139, 'Mexique', 'Mexicaine', 'MX'),
(140, 'Monaco', '0', 'MC'),
(141, 'Mongolie', '0', 'MN'),
(142, 'République de Moldova', 'Moldave', 'MD'),
(143, 'Montserrat', '0', 'MS'),
(144, 'Maroc', 'Marocaine', 'MA'),
(145, 'Mozambique', 'Mozambicaine', 'MZ'),
(146, 'Oman', '0', 'OM'),
(147, 'Namibie', '0', 'NA'),
(148, 'Nauru', '0', 'NR'),
(149, 'Népal', 'Népalaise', 'NP'),
(150, 'Pays-Bas', 'Hollandaise', 'NL'),
(151, 'Antilles Néerlandaises', '0', 'AN'),
(152, 'Aruba', '0', 'AW'),
(153, 'Nouvelle-Calédonie', '0', 'NC'),
(154, 'Vanuatu', '0', 'VU'),
(155, 'Nouvelle-Zélande', 'New zelandaise', 'NZ'),
(156, 'Nicaragua', '0', 'NI'),
(157, 'Niger', 'Nigerienne', 'NE'),
(158, 'Nigéria', 'Nigériane', 'NG'),
(159, 'Niué', '0', 'NU'),
(160, 'Île Norfolk', '0', 'NF'),
(161, 'Norvège', 'Norvégienne', 'NO'),
(162, 'Îles Mariannes du Nord', '0', 'MP'),
(163, 'Îles Mineures Éloignées des États-Unis', '0', 'UM'),
(164, 'États Fédérés de Micronésie', '0', 'FM'),
(165, 'Îles Marshall', '0', 'MH'),
(166, 'Palaos', '0', 'PW'),
(167, 'Pakistan', 'Pakistanaise', 'PK'),
(168, 'Panama', '0', 'PA'),
(169, 'Papouasie-Nouvelle-Guinée', '0', 'PG'),
(170, 'Paraguay', '0', 'PY'),
(171, 'Pérou', 'Péruvienne', 'PE'),
(172, 'Philippines', 'Philippine', 'PH'),
(173, 'Pitcairn', '0', 'PN'),
(174, 'Pologne', 'Polonaise', 'PL'),
(175, 'Portugal', 'Portugaise', 'PT'),
(176, 'Guinée-Bissau', '0', 'GW'),
(177, 'Timor-Leste', '0', 'TL'),
(178, 'Porto Rico', '0', 'PR'),
(179, 'Qatar', '0', 'QA'),
(180, 'Réunion', '0', 'RE'),
(181, 'Roumanie', 'Roumaine', 'RO'),
(182, 'Fédération de Russie', 'Russe', 'RU'),
(183, 'Rwanda', '0', 'RW'),
(184, 'Sainte-Hélène', '0', 'SH'),
(185, 'Saint-Kitts-et-Nevis', '0', 'KN'),
(186, 'Anguilla', '0', 'AI'),
(187, 'Sainte-Lucie', '0', 'LC'),
(188, 'Saint-Pierre-et-Miquelon', '0', 'PM'),
(189, 'Saint-Vincent-et-les Grenadines', '0', 'VC'),
(190, 'Saint-Marin', '0', 'SM'),
(191, 'Sao Tomé-et-Principe', '0', 'ST'),
(192, 'Arabie Saoudite', 'Saoudienne', 'SA'),
(193, 'Sénégal', 'Sénégalaise', 'SN'),
(194, 'Seychelles', '0', 'SC'),
(195, 'Sierra Leone', '0', 'SL'),
(196, 'Singapour', '0', 'SG'),
(197, 'Slovaquie', 'Slovaque', 'SK'),
(198, 'Viet Nam', '0', 'VN'),
(199, 'Slovénie', '0', 'SI'),
(200, 'Somalie', '0', 'SO'),
(201, 'Afrique du Sud', 'Sud africaine', 'ZA'),
(202, 'Zimbabwe', '0', 'ZW'),
(203, 'Espagne', 'Espagnole', 'ES'),
(205, 'Soudan', 'Soudanaise', 'SD'),
(206, 'Suriname', '0', 'SR'),
(207, 'Svalbard etÎle Jan Mayen', '0', 'SJ'),
(208, 'Swaziland', '0', 'SZ'),
(209, 'Suède', 'Suédoise', 'SE'),
(210, 'Suisse', '0', 'CH'),
(211, 'République Arabe Syrienne', 'Syrienne', 'SY'),
(212, 'Tadjikistan', '0', 'TJ'),
(213, 'Thaïlande', '0', 'TH'),
(214, 'Togo', 'Togolaise', 'TG'),
(215, 'Tokelau', '0', 'TK'),
(216, 'Tonga', '0', 'TO'),
(217, 'Trinité-et-Tobago', 'Trinidad', 'TT'),
(218, 'Émirats Arabes Unis', '0', 'AE'),
(219, 'Tunisie', 'Tunisienne', 'TN'),
(220, 'Turquie', 'Turque', 'TR'),
(221, 'Turkménistan', '0', 'TM'),
(222, 'Îles Turks et Caïques', '0', 'TC'),
(223, 'Tuvalu', '0', 'TV'),
(224, 'Ouganda', 'Ougandaise', 'UG'),
(225, 'Ukraine', 'Ukrainienne', 'UA'),
(226, 'L''ex-République Yougoslave de Macédoine', 'Macedonienne', 'MK'),
(227, 'égypte', 'Egyptienne', 'EG'),
(228, 'Royaume-Uni', 'Britannique', 'GB'),
(229, 'Île de Man', '0', 'IM'),
(230, 'République-Unie de Tanzanie', '0', 'TZ'),
(231, 'Etats-Unis Amerique', 'Americaine', 'US'),
(232, 'Îles Vierges des États-Unis', '0', 'VI'),
(233, 'Burkina Faso', 'Burkinabe', 'BF'),
(234, 'Uruguay', '0', 'UY'),
(235, 'Ouzbékistan', '0', 'UZ'),
(236, 'Venezuela', 'Vénézuélienne', 'VE'),
(237, 'Wallis et Futuna', '0', 'WF'),
(238, 'Samoa', '0', 'WS'),
(239, 'Yémen', 'Yemenite', 'YE'),
(240, 'Serbie-et-Monténégro', 'Serbe', 'CS'),
(241, 'Zambie', '0', 'ZM'),
(242, 'Tchad', 'Tchadienne', 'TD'),
(243, 'Erythree', 'Erythreenne', 'ER'),
(244, 'Soudan du Sud', 'Sud-Soudanaise', 'SS');

-- --------------------------------------------------------

--
-- Structure de la table `ref_region`
--

CREATE TABLE IF NOT EXISTS `ref_region` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identifiant de ligne',
  `id_pays` int(11) DEFAULT '242' COMMENT 'le pays de la region par default Tchad',
  `region` varchar(30) CHARACTER SET latin1 NOT NULL COMMENT 'libelle region',
  `etat` int(2) NOT NULL DEFAULT '1' COMMENT 'l''etat de la ligne',
  `creusr` varchar(60) NOT NULL COMMENT 'Crée par',
  `credat` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date de création',
  `updusr` varchar(60) DEFAULT NULL COMMENT 'Derniere modif par',
  `upddat` datetime DEFAULT NULL COMMENT 'Date derniere modif',
  PRIMARY KEY (`id`),
  KEY `fk_region_pays` (`id_pays`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=31 ;

--
-- Contenu de la table `ref_region`
--

INSERT INTO `ref_region` (`id`, `id_pays`, `region`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(1, 242, 'Barh el Ghazel', 0, '', '2017-03-19 21:47:48', 'admin', '2017-03-28 21:55:35'),
(2, 242, 'Batha', 1, '', '2017-03-19 21:47:48', NULL, NULL),
(5, 242, 'Borkou', 1, '', '2017-03-19 21:47:48', NULL, NULL),
(6, 242, 'Chari-Baguirmi', 1, '', '2017-03-19 21:47:48', 'admin', '2017-03-28 21:56:56'),
(7, 242, 'Ennedi Est', 1, '', '2017-03-19 21:47:48', NULL, NULL),
(8, 242, 'Ennedi Ouest', 1, '', '2017-03-19 21:47:48', NULL, NULL),
(9, 242, 'Guéra', 1, '', '2017-03-19 21:47:48', 'admin', '2017-03-28 21:56:04'),
(10, 242, 'Hadjer-Lamis', 1, '', '2017-03-19 21:47:48', NULL, NULL),
(11, 242, 'Kanem', 1, '', '2017-03-19 21:47:48', NULL, NULL),
(12, 242, 'Lac', 1, '', '2017-03-19 21:47:48', NULL, NULL),
(13, 242, 'Logone Occidental', 1, '', '2017-03-19 21:47:48', NULL, NULL),
(14, 242, 'Logone Oriental', 1, '', '2017-03-19 21:47:48', NULL, NULL),
(15, 242, 'Mandoul', 0, '', '2017-03-19 21:47:48', NULL, NULL),
(16, 242, 'Mayo-Kebbi Est', 0, '', '2017-03-19 21:47:48', NULL, NULL),
(17, 242, 'Mayo-Kebbi Ouest', 1, '', '2017-03-19 21:47:48', NULL, NULL),
(18, 242, 'Moyen-Chari', 1, '', '2017-03-19 21:47:48', NULL, NULL),
(19, 242, 'Ouaddaï', 1, '', '2017-03-19 21:47:48', NULL, NULL),
(20, 242, 'Salamat', 1, '', '2017-03-19 21:47:48', NULL, NULL),
(21, 242, 'Sila', 1, '', '2017-03-19 21:47:48', NULL, NULL),
(22, 242, 'Tandjilé', 1, '', '2017-03-19 21:47:48', NULL, NULL),
(23, 242, 'Tibesti', 1, '', '2017-03-19 21:47:48', NULL, NULL),
(24, 242, 'Ville de N''Djamena', 1, '', '2017-03-19 21:47:48', NULL, NULL),
(25, 242, 'Wadi Fira', 1, '', '2017-03-19 21:47:48', '1', '2017-04-02 13:48:49'),
(30, 242, 'Lacc', 1, '1', '2017-04-02 13:50:53', '1', '2017-04-02 13:52:01');

-- --------------------------------------------------------

--
-- Structure de la table `ref_secteur_activite`
--

CREATE TABLE IF NOT EXISTS `ref_secteur_activite` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID Table',
  `secteur` varchar(36) NOT NULL COMMENT 'Secteur',
  PRIMARY KEY (`id`,`secteur`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=49 ;

--
-- Contenu de la table `ref_secteur_activite`
--

INSERT INTO `ref_secteur_activite` (`id`, `secteur`) VALUES
(1, 'Aéronautique / Spatial'),
(2, 'Agence pub / Marketing Direct'),
(3, 'Agriculture / Environnement'),
(4, 'Agroalimentaire'),
(5, 'Ameublement / Décoration'),
(6, 'Assurance / Courtage'),
(7, 'Audiovisuel'),
(8, 'Automobile / Motos / Cycles'),
(9, 'Autres Industries'),
(10, 'Autres services'),
(11, 'Banque / Finance'),
(12, 'BTP / Génie Civil'),
(13, 'Call Center / Web Center'),
(14, 'Chimie / Parachimie / Peintures'),
(15, 'Communication / Evénementiel'),
(16, 'Comptabilité / Audit'),
(17, 'Conseil / Etudes'),
(18, 'Cosmétique / Parfumerie / Luxe'),
(19, 'Distribution'),
(20, 'Edition / Imprimerie'),
(21, 'Education/Formation'),
(22, 'Electro-mécanique / Mécanique'),
(23, 'Electronique'),
(24, 'Energie'),
(25, 'Extraction / Mines'),
(26, 'Ferroviaire'),
(27, 'Hôtellerie / Restauration'),
(28, 'Immobilier / Promoteur /Agence'),
(29, 'Import / Export / Négoce'),
(30, 'Informatique'),
(31, 'Internet / Multimédia'),
(32, 'Juridique / Cabinet d’avocats'),
(33, 'Matériel Médical /Parapharmacie'),
(34, 'Métallurgie / Sidérurgie'),
(35, 'Nettoyage / Sécurité / Gardiennage'),
(36, 'Offshoring/Nearshoring'),
(37, 'Papier / Carton'),
(38, 'Pétrole/Gaz'),
(39, 'Pharmacie / Santé'),
(40, 'Plasturgie'),
(41, 'Presse'),
(42, 'Recrutement / Intérim'),
(43, 'Service public /Administration'),
(44, 'Tabac'),
(45, 'Telecom'),
(46, 'Textile / Cuir'),
(47, 'Tourisme / Voyage / Loisirs '),
(48, 'Transport / Messagerie /Logistique');

-- --------------------------------------------------------

--
-- Structure de la table `ref_types_produits`
--

CREATE TABLE IF NOT EXISTS `ref_types_produits` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `type_produit` varchar(100) NOT NULL COMMENT 'Type service/produit',
  `etat` int(11) DEFAULT '0',
  `creusr` int(11) DEFAULT NULL,
  `credat` datetime DEFAULT CURRENT_TIMESTAMP,
  `updusr` int(11) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `ref_types_produits`
--

INSERT INTO `ref_types_produits` (`id`, `type_produit`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(2, 'MMMM', 0, 1, '2017-08-26 19:21:11', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `ref_unites_vente`
--

CREATE TABLE IF NOT EXISTS `ref_unites_vente` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `unite_vente` varchar(50) NOT NULL COMMENT 'UnitÃ© de vente des produits(Kg,m...)',
  `etat` int(11) DEFAULT '0',
  `creusr` int(11) DEFAULT NULL,
  `credat` datetime DEFAULT CURRENT_TIMESTAMP,
  `updusr` int(11) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `ref_unites_vente`
--

INSERT INTO `ref_unites_vente` (`id`, `unite_vente`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(2, 'FTFT', 0, 1, '2017-08-26 18:24:08', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `ref_ville`
--

CREATE TABLE IF NOT EXISTS `ref_ville` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identifiant ligne',
  `id_region` int(11) DEFAULT '0' COMMENT 'id de region',
  `ville` varchar(20) CHARACTER SET latin1 NOT NULL COMMENT 'libelle',
  `latitude` varchar(15) NOT NULL COMMENT 'coordonnées geographique',
  `longitude` varchar(15) NOT NULL COMMENT 'coordonnées geographique',
  `etat` int(11) NOT NULL DEFAULT '1' COMMENT 'etat de ligne',
  `creusr` varchar(60) NOT NULL COMMENT 'Crée par',
  `credat` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date de création',
  `updusr` varchar(60) DEFAULT NULL COMMENT 'Derniere modif',
  `upddat` datetime DEFAULT NULL COMMENT 'Date deniere modif',
  PRIMARY KEY (`id`),
  KEY `fk_ville_region` (`id_region`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=49 ;

--
-- Contenu de la table `ref_ville`
--

INSERT INTO `ref_ville` (`id`, `id_region`, `ville`, `latitude`, `longitude`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(1, 2, 'Batha', '12.33545', '15.22654', 1, '', '2017-03-19 23:45:09', 'admin', '2017-04-11 22:37:08'),
(2, 1, 'Ati', '', '', 1, '', '2017-03-19 23:45:09', NULL, NULL),
(3, 1, 'Chari-Baguirmi', '', '', 1, '', '2017-03-19 23:45:09', NULL, NULL),
(4, 1, 'Massenya', '', '', 1, '', '2017-03-19 23:45:09', NULL, NULL),
(5, 1, 'Hadjer-Lamis', '', '', 1, '', '2017-03-19 23:45:09', NULL, NULL),
(6, 1, 'Massakory', '', '', 1, '', '2017-03-19 23:45:09', NULL, NULL),
(7, 1, 'Wadi-Fira', '', '', 1, '', '2017-03-19 23:45:09', NULL, NULL),
(8, 1, 'Beltine', '', '', 1, '', '2017-03-19 23:45:09', NULL, NULL),
(9, 1, 'Bahr El Gazal', '', '', 1, '', '2017-03-19 23:45:09', NULL, NULL),
(10, 1, 'Moussoro', '', '', 1, '', '2017-03-19 23:45:09', NULL, NULL),
(11, 1, 'Borko', '', '', 1, '', '2017-03-19 23:45:09', NULL, NULL),
(12, 1, 'Faya-Largeau', '', '', 1, '', '2017-03-19 23:45:09', NULL, NULL),
(13, 1, 'Ennedi', '', '', 1, '', '2017-03-19 23:45:09', NULL, NULL),
(14, 1, 'Fada', '', '', 1, '', '2017-03-19 23:45:09', NULL, NULL),
(15, 1, 'Guéra', '', '', 1, '', '2017-03-19 23:45:09', NULL, NULL),
(16, 1, 'Mongo', '', '', 1, '', '2017-03-19 23:45:09', NULL, NULL),
(17, 1, 'Kanem', '', '', 1, '', '2017-03-19 23:45:09', NULL, NULL),
(18, 1, 'Mao', '', '', 1, '', '2017-03-19 23:45:09', NULL, NULL),
(19, 1, 'Lac Tchad', '', '', 1, '', '2017-03-19 23:45:09', NULL, NULL),
(20, 1, 'Bol', '', '', 1, '', '2017-03-19 23:45:09', NULL, NULL),
(21, 1, 'Logone Occidental', '', '', 1, '', '2017-03-19 23:45:09', NULL, NULL),
(22, 1, 'Moundou', '', '', 1, '', '2017-03-19 23:45:09', NULL, NULL),
(23, 1, 'Logone Oriental', '', '', 1, '', '2017-03-19 23:45:09', NULL, NULL),
(24, 1, 'Doba', '', '', 1, '', '2017-03-19 23:45:09', NULL, NULL),
(25, 1, 'Mandoul', '', '', 1, '', '2017-03-19 23:45:09', NULL, NULL),
(26, 1, 'Koumra', '', '', 1, '', '2017-03-19 23:45:09', NULL, NULL),
(27, 1, 'Mayo-Kebbi Est', '', '', 1, '', '2017-03-19 23:45:09', NULL, NULL),
(28, 1, 'Bongor', '', '', 1, '', '2017-03-19 23:45:09', NULL, NULL),
(29, 1, 'Mayo-Kebbi Ouest', '', '', 1, '', '2017-03-19 23:45:09', NULL, NULL),
(30, 1, 'Pala', '', '', 1, '', '2017-03-19 23:45:09', NULL, NULL),
(31, 1, 'Moyen-Chari', '', '', 1, '', '2017-03-19 23:45:09', NULL, NULL),
(32, 1, 'Sarh', '', '', 1, '', '2017-03-19 23:45:09', NULL, NULL),
(33, 1, 'Ouaddaï', '', '', 1, '', '2017-03-19 23:45:09', NULL, NULL),
(34, 1, 'Abéché', '', '', 1, '', '2017-03-19 23:45:09', NULL, NULL),
(35, 1, 'Salamat', '', '', 1, '', '2017-03-19 23:45:09', NULL, NULL),
(36, 1, 'Am-Timan', '', '', 1, '', '2017-03-19 23:45:09', NULL, NULL),
(37, 1, 'Sila', '', '', 1, '', '2017-03-19 23:45:09', NULL, NULL),
(38, 1, 'Goz Beïda', '', '', 1, '', '2017-03-19 23:45:09', NULL, NULL),
(39, 1, 'Tandjilé', '', '', 1, '', '2017-03-19 23:45:09', NULL, NULL),
(40, 1, 'Laï', '', '', 1, '', '2017-03-19 23:45:09', NULL, NULL),
(41, 1, 'Tibesti', '', '', 1, '', '2017-03-19 23:45:09', NULL, NULL),
(42, 1, 'Bardaï', '', '', 1, '', '2017-03-19 23:45:09', NULL, NULL),
(43, 1, 'N''djamena', '', '', 1, '', '2017-03-19 23:45:09', NULL, NULL),
(48, 2, 'Test ville', '12.33545', '15.22654', 1, 'admin', '2017-04-11 22:06:36', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `rules_action`
--

CREATE TABLE IF NOT EXISTS `rules_action` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'rule id',
  `appid` int(11) NOT NULL COMMENT 'id task',
  `idf` varchar(32) DEFAULT NULL COMMENT 'IDF Rul Mgt',
  `service` int(11) DEFAULT NULL COMMENT 'Service ID',
  `userid` int(11) NOT NULL COMMENT 'id user',
  `action_id` int(11) NOT NULL COMMENT 'id action de task',
  `descrip` varchar(75) NOT NULL COMMENT 'description de rule',
  `type` int(11) DEFAULT '0' COMMENT 'action=0 task=1',
  `creusr` varchar(20) DEFAULT NULL COMMENT 'Create by',
  `credat` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'Date Create',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_rule_idf_usrid` (`idf`,`userid`),
  KEY `rules_action_task_appid` (`appid`),
  KEY `rules_action_user_sys` (`userid`),
  KEY `rule_action_task_action` (`action_id`),
  KEY `rule_action_service_id` (`service`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Table store rules for each user for each App and action' AUTO_INCREMENT=18381 ;

--
-- Contenu de la table `rules_action`
--

INSERT INTO `rules_action` (`id`, `appid`, `idf`, `service`, `userid`, `action_id`, `descrip`, `type`, `creusr`, `credat`) VALUES
(11752, 14, '56de23d30d6c54297c8d9772cd3c7f57', 3, 2, 1, 'Utilisateurs', 1, '1', '2017-09-03 13:29:49'),
(11753, 14, 'e656756fb7b39a4e6ddcabca75ff2970', 3, 2, 3, 'Editer Utilisateur', 0, '1', '2017-09-03 13:29:49'),
(11754, 14, 'c073a277957ca1b9f318ac3902555708', 3, 2, 6, 'Permissions', 0, '1', '2017-09-03 13:29:49'),
(11755, 14, 'c51499ddf7007787c4434661c658bbd1', 3, 2, 8, 'Désactiver compte', 0, '1', '2017-09-03 13:29:49'),
(11756, 14, '10096b6f54456bcfc85081523ee64cf6', 3, 2, 23, 'Supprimer utilisateur', 0, '1', '2017-09-03 13:29:49'),
(11757, 14, 'a0999cbed820aff775adf27276ee54a4', 3, 2, 25, 'Editer Utilisateur', 0, '1', '2017-09-03 13:29:49'),
(11758, 14, '9aa6877656339ddff2478b20449a924b', 3, 2, 27, 'Activer compte', 0, '1', '2017-09-03 13:29:49'),
(11759, 14, 'f4c79bb797b92dfa826b51a44e3171af', 3, 2, 112, 'Utilisateurs', 0, '1', '2017-09-03 13:29:49'),
(11760, 14, 'd7f7afd70a297e5c239f6cf271138390', 3, 2, 143, 'Utilisateur Archivé', 0, '1', '2017-09-03 13:29:49'),
(11761, 15, 'df91a8e6f8ee2cde64495fc0cc7d6c6f', 3, 2, 2, 'Ajouter Utilisateurs', 1, '1', '2017-09-03 13:29:49'),
(11762, 16, '2bb46b52eab9eecbdbba35605da07234', 3, 2, 4, 'Editer Utilisateurs', 1, '1', '2017-09-03 13:29:49'),
(11763, 17, '3f59a1326df27378304e142ab3bec090', 3, 2, 5, 'Permission', 1, '1', '2017-09-03 13:29:49'),
(11764, 18, 'b919571c88d036f8889742a81a4f41fd', 3, 2, 7, 'Supprimer utilisateur', 1, '1', '2017-09-03 13:29:49'),
(11765, 19, '38f89764a26e39ce029cd3132c12b2a5', 3, 2, 45, 'Compte utilisateur', 1, '1', '2017-09-03 13:29:49'),
(11766, 20, 'f988a608f35a0bc551cb038b1706d207', 3, 2, 26, 'Activer utilisateur', 1, '1', '2017-09-03 13:29:49'),
(11767, 107, 'b7b3a09fdd73a5b0a3e5ed8a2828f548', 3, 2, 145, 'Désactiver l''utilisateur', 1, '1', '2017-09-03 13:29:49'),
(11768, 161, '0d374b7e2fe21a2e2641c092a3c7f2e9', 3, 2, 222, 'Changer le mot de passe', 1, '1', '2017-09-03 13:29:49'),
(11769, 162, '6f642ee30722158f0318653b9113b887', 3, 2, 224, 'History', 1, '1', '2017-09-03 13:29:49'),
(11770, 163, 'cc907fac13631903d129c137d671d718', 3, 2, 225, 'Activities', 1, '1', '2017-09-03 13:29:49'),
(11892, 21, '05ce9e55686161d99e0714bb86243e5b', 2, 20, 11, 'Editer Module', 0, '1', '2017-09-03 13:41:53'),
(11893, 21, '819cf9c18a44cb80771a066768d585f2', 2, 20, 12, 'Exporter Module', 0, '1', '2017-09-03 13:41:53'),
(11894, 21, 'd2fc3ee15cee5208a8b9c70f1e53c196', 2, 20, 13, 'Liste task modul', 0, '1', '2017-09-03 13:41:53'),
(11895, 21, '064a9b0eff1006fd4f25cb4eaf894ca1', 2, 20, 77, 'Liste task modul Setting', 0, '1', '2017-09-03 13:41:53'),
(11896, 22, '44bd5341b0ab41ced21db8b3e92cf5aa', 2, 20, 10, 'Ajouter un Modul', 1, '1', '2017-09-03 13:41:53'),
(11897, 24, '8653b156f1a4160a12e5a94b211e59a2', 2, 20, 16, 'Liste Action Task', 0, '1', '2017-09-03 13:41:53'),
(11898, 25, '1c452aff8f1551b3574e15b74147ea56', 2, 20, 14, 'Ajouter Task Modul', 1, '1', '2017-09-03 13:41:53'),
(11899, 26, 'f085fe4610576987db963501297e4d91', 2, 20, 15, 'Editer Task Modul', 1, '1', '2017-09-03 13:41:53'),
(11900, 26, '38702c272a6f4d334c2f4c3684c8b163', 2, 20, 18, 'Ajouter action modul', 1, '1', '2017-09-03 13:41:53'),
(11901, 34, '99aea4598ccc18d4c12ae091c8967d13', 2, 20, 33, 'Valider Service', 0, '1', '2017-09-03 13:41:53'),
(11902, 34, 'bb66cf787052616ea3dd02b0b5199b26', 2, 20, 34, 'Supprimer Service', 0, '1', '2017-09-03 13:41:53'),
(11903, 35, '55043bc4207521e3010e91d6267f5302', 2, 20, 29, 'Ajouter Service', 1, '1', '2017-09-03 13:41:53'),
(11904, 36, '2fea3d893f6b6e81467ddd2a744e4a76', 2, 20, 30, 'Modifier Service', 1, '1', '2017-09-03 13:41:53'),
(11905, 37, '1a0d5897d31b4d5e29022671c1112f59', 2, 20, 31, 'Valider Service', 1, '1', '2017-09-03 13:41:53'),
(11906, 38, '42083d4e159baf7c2ace2bb977e2b0a0', 2, 20, 32, 'Supprimer Service', 1, '1', '2017-09-03 13:41:53'),
(11907, 14, '56de23d30d6c54297c8d9772cd3c7f57', 2, 20, 1, 'Utilisateurs', 1, '1', '2017-09-03 13:41:53'),
(11908, 14, 'e656756fb7b39a4e6ddcabca75ff2970', 2, 20, 3, 'Editer Utilisateur', 0, '1', '2017-09-03 13:41:53'),
(11909, 14, 'c073a277957ca1b9f318ac3902555708', 2, 20, 6, 'Permissions', 0, '1', '2017-09-03 13:41:53'),
(11910, 14, 'c51499ddf7007787c4434661c658bbd1', 2, 20, 8, 'Désactiver compte', 0, '1', '2017-09-03 13:41:53'),
(11911, 14, '10096b6f54456bcfc85081523ee64cf6', 2, 20, 23, 'Supprimer utilisateur', 0, '1', '2017-09-03 13:41:53'),
(11912, 14, 'a0999cbed820aff775adf27276ee54a4', 2, 20, 25, 'Editer Utilisateur', 0, '1', '2017-09-03 13:41:53'),
(11913, 14, '9aa6877656339ddff2478b20449a924b', 2, 20, 27, 'Activer compte', 0, '1', '2017-09-03 13:41:53'),
(11914, 14, 'f4c79bb797b92dfa826b51a44e3171af', 2, 20, 112, 'Utilisateurs', 0, '1', '2017-09-03 13:41:53'),
(11915, 14, 'd7f7afd70a297e5c239f6cf271138390', 2, 20, 143, 'Utilisateur Archivé', 0, '1', '2017-09-03 13:41:53'),
(11916, 15, 'df91a8e6f8ee2cde64495fc0cc7d6c6f', 2, 20, 2, 'Ajouter Utilisateurs', 1, '1', '2017-09-03 13:41:53'),
(11917, 16, '2bb46b52eab9eecbdbba35605da07234', 2, 20, 4, 'Editer Utilisateurs', 1, '1', '2017-09-03 13:41:53'),
(11918, 17, '3f59a1326df27378304e142ab3bec090', 2, 20, 5, 'Permission', 1, '1', '2017-09-03 13:41:53'),
(14165, 437, 'e152b9052d3dcfcac593489dbdc0f61c', 2, 20, 670, 'Ajouter ville', 1, '1', '2017-09-23 11:51:11'),
(14167, 438, '3107e0cd0e0df14c4e94aa088e4457d7', 2, 20, 671, 'Editer Ville', 1, '1', '2017-09-23 11:51:11'),
(18173, 441, 'e69f84a801ee1525f20f34e684688a9b', 1, 1, 674, 'Gestion des catégories de produits', 0, '1', '2017-09-29 16:08:17'),
(18174, 441, '90f6eba3e0ed223e73d250278cb445d5', 1, 1, 675, 'Modifier catégorie', 0, '1', '2017-09-29 16:08:17'),
(18175, 441, 'c62968a45ae9cfa8b127ac1b5573988a', 1, 1, 676, 'Valider catégorie', 0, '1', '2017-09-29 16:08:17'),
(18176, 441, '6f43a6bcbd293f958aff51953559104e', 1, 1, 677, 'Désactiver catégorie', 0, '1', '2017-09-29 16:08:17'),
(18177, 442, 'd26f5940e88a494c0eb65047aab9a17b', 1, 1, 678, 'Ajouter une catégorie', 0, '1', '2017-09-29 16:08:17'),
(18178, 443, '27957c6d0f6869d4d90287cd50b6dde9', 1, 1, 679, 'Modifier une catégorie', 0, '1', '2017-09-29 16:08:17'),
(18179, 444, '41b48dd567e4f79e35261a47b7bad751', 1, 1, 680, 'Valider une catégorie', 0, '1', '2017-09-29 16:08:17'),
(18180, 445, '90dc20c4d1ad7be7fac8ec34d5ac26b3', 1, 1, 681, 'Supprimer une catégorie', 0, '1', '2017-09-29 16:08:17'),
(18181, 362, '6edc543080c65eca3993445c295ff94b', 1, 1, 552, 'Gestion Catégorie Client', 0, '1', '2017-09-29 16:08:17'),
(18182, 362, '142a68a109abd0462ea44fcadffe56de', 1, 1, 553, 'Editer Catégorie Client', 0, '1', '2017-09-29 16:08:17'),
(18183, 362, '70df89fa2654d8b10d7fc7e75e178b7e', 1, 1, 554, 'Activer Catégorie Client', 0, '1', '2017-09-29 16:08:17'),
(18184, 362, '109e82d6db5721f63cd827e9fd224216', 1, 1, 555, 'Désactiver Catégorie Client', 0, '1', '2017-09-29 16:08:17'),
(18185, 363, 'a5c1bd0dfd87824ff0f57c6b1e1d2c3f', 1, 1, 556, 'Ajouter Catégorie Client', 1, '1', '2017-09-29 16:08:17'),
(18186, 364, '8d901f74dfd6ee3a8f44ebd0b83fbfae', 1, 1, 557, 'Editer Catégorie Client', 1, '1', '2017-09-29 16:08:17'),
(18187, 365, 'e87327563ce6b659780d6b2c9bf8ac77', 1, 1, 558, 'Supprimer Catégorie Client', 1, '1', '2017-09-29 16:08:17'),
(18188, 366, 'c955da8d244aac06ee7595d08de7d009', 1, 1, 559, 'Valider Catégorie Client', 1, '1', '2017-09-29 16:08:17'),
(18189, 367, 'f12fb1c50aedc49c3fa3dfa2bd297bd3', 1, 1, 560, 'Gestion Clients', 0, '1', '2017-09-29 16:08:17'),
(18190, 367, 'dd3d5980299911ea854af4fa6f2e7309', 1, 1, 561, 'Editer Client', 0, '1', '2017-09-29 16:08:17'),
(18191, 367, '3c5c04a20d49ad010557a64c8cdac1ce', 1, 1, 562, 'Valider Client', 0, '1', '2017-09-29 16:08:17'),
(18192, 367, '18ace52052f2551099ecaabf049ffaec', 1, 1, 563, 'Désactiver Client', 0, '1', '2017-09-29 16:08:17'),
(18193, 367, '493f9e55fc0340763e07514c1900685a', 1, 1, 564, 'Détails Client', 0, '1', '2017-09-29 16:08:17'),
(18194, 367, '03b4f949b088e41fc9a1f3f23b7906a8', 1, 1, 565, 'Détails  Client', 0, '1', '2017-09-29 16:08:17'),
(18195, 368, '2b9d8bb8f752d1c35fb681c33e38b42b', 1, 1, 566, 'Ajouter Client', 1, '1', '2017-09-29 16:08:17'),
(18196, 369, '54aa9121e05f5e698d354022a8eab71d', 1, 1, 567, 'Editer Client', 1, '1', '2017-09-29 16:08:17'),
(18197, 370, '4eaf650e8c2221d590fac5a6a6952231', 1, 1, 568, 'Supprimer Client', 1, '1', '2017-09-29 16:08:17'),
(18198, 371, '534cd4b17fb8a371d3a20565ab8fd96e', 1, 1, 569, 'Valider Client', 1, '1', '2017-09-29 16:08:17'),
(18199, 372, '95bb6aa696ef630a335aa84e1e425e2c', 1, 1, 570, 'Détails Client', 0, '1', '2017-09-29 16:08:17'),
(18200, 456, '899d40c8f22d4f7a6f048366f1829787', 1, 1, 698, 'Gestion des contrats', 0, '1', '2017-09-29 16:08:17'),
(18201, 456, 'a20f4c5b9c9ebaa238757d6f9f9cb6fb', 1, 1, 699, 'Modifier contrat', 0, '1', '2017-09-29 16:08:17'),
(18202, 456, 'fbb243d2c2fa4200c40993e527b3a339', 1, 1, 700, 'Détail contrat', 0, '1', '2017-09-29 16:08:18'),
(18203, 456, 'e970c1414507e5b83ae39e7ddedbf15e', 1, 1, 701, 'Valider contrat', 0, '1', '2017-09-29 16:08:18'),
(18204, 456, '6908357258099272b60018c0f6fb1078', 1, 1, 702, 'Désactiver contrat', 0, '1', '2017-09-29 16:08:18'),
(18205, 456, '11cabf03a954a5476cc78cf221f04d78', 1, 1, 703, 'Détails Contrat', 0, '1', '2017-09-29 16:08:18'),
(18206, 457, '87f4c3ed4713c3bc9e3fef60a6649055', 1, 1, 704, 'Ajouter contrat', 0, '1', '2017-09-29 16:08:18'),
(18207, 458, '9e49a431d9637544cefa2869fd7278b9', 1, 1, 705, 'Modifier contrat', 0, '1', '2017-09-29 16:08:18'),
(18208, 459, '1e9395a182a44787e493bc038cd80bbf', 1, 1, 706, 'Supprimer contrat', 0, '1', '2017-09-29 16:08:18'),
(18209, 460, '460d92834715b149c4db28e1643bd932', 1, 1, 707, 'Valider contrat', 0, '1', '2017-09-29 16:08:18'),
(18210, 461, 'bbcf2879c2f8f60cfa55fa97c6e79268', 1, 1, 708, 'Détail contrat', 0, '1', '2017-09-29 16:08:18'),
(18211, 462, 'fe058ccb890b25a54866be7f24a40363', 1, 1, 709, 'Ajouter échéance ', 0, '1', '2017-09-29 16:08:18'),
(18212, 463, '36a248f56a6a80977e5c90a5c59f39d3', 1, 1, 710, 'Modifier échéance contrat', 0, '1', '2017-09-29 16:08:18'),
(18213, 424, 'ec45512f34613446e7a2e367d4b4cfbd', 1, 1, 643, 'Gestion Contrats Fournisseurs', 0, '1', '2017-09-29 16:08:18'),
(18214, 424, 'e3c0d7e92dad7f8794b2415c334ec3ff', 1, 1, 644, 'Editer Contrat', 0, '1', '2017-09-29 16:08:18'),
(18215, 424, '9dfff1c8dcb804837200f38e95381420', 1, 1, 645, 'Valider Contrat', 0, '1', '2017-09-29 16:08:18'),
(18216, 424, '9fe39b496077065105a57ccd9ed05863', 1, 1, 646, 'Désactiver Contrat', 0, '1', '2017-09-29 16:08:18'),
(18217, 424, '0092ad9ef69b6420a611df6859a43cda', 1, 1, 647, 'Détails Contrat', 0, '1', '2017-09-29 16:08:18'),
(18218, 424, '6ca83d9c6c0b229446da30b60b74031a', 1, 1, 648, 'Détails  Contrat', 0, '1', '2017-09-29 16:08:18'),
(18219, 425, 'ded24eb817021c5a666a677b1565bc5e', 1, 1, 649, 'Ajouter Contrat', 0, '1', '2017-09-29 16:08:18'),
(18220, 426, 'ed6b8695494bf4ed86d5fb18690b3a59', 1, 1, 650, 'Editer Contrat', 0, '1', '2017-09-29 16:08:18'),
(18221, 427, 'b8a40913b5955209994aaa26d0e8c3d4', 1, 1, 651, 'Supprimer Contrat', 0, '1', '2017-09-29 16:08:18'),
(18222, 428, '5efb874e7d73ccd722df806e8275770f', 1, 1, 652, 'Valider Contrat', 0, '1', '2017-09-29 16:08:18'),
(18223, 469, 'f320732af279d6f2f8ae9c98cd0216de', 1, 1, 719, 'Gestion Départements', 0, '1', '2017-09-29 16:08:18'),
(18224, 469, '96516cd0c72d814d5dcb1d86eacd29ab', 1, 1, 720, 'Editer Département', 0, '1', '2017-09-29 16:08:18'),
(18225, 469, 'ef27a63534fa9fc3bd4b5086a92db546', 1, 1, 721, 'Valider Département', 0, '1', '2017-09-29 16:08:18'),
(18226, 469, '9aed965af4c4b89a5a23c41bf685d403', 1, 1, 722, 'Désactiver Département', 0, '1', '2017-09-29 16:08:18'),
(18227, 470, '722b3ba1c7fe735e87aa7415e5502a4c', 1, 1, 723, 'Ajouter Département', 0, '1', '2017-09-29 16:08:18'),
(18228, 471, 'daeb31006124e562d284aff67360ee19', 1, 1, 724, 'Editer Département', 0, '1', '2017-09-29 16:08:18'),
(18229, 472, 'a775da608fe55c53211d4f1c6e493251', 1, 1, 725, 'Supprimer Département', 0, '1', '2017-09-29 16:08:18'),
(18230, 473, 'bbb96ec910c5000a2006db2f6e8af10a', 1, 1, 726, 'Valider Département', 0, '1', '2017-09-29 16:08:18'),
(18231, 385, '0e79510db7f03b9b6266fc7b4a612153', 1, 1, 586, 'Gestion Devis', 0, '1', '2017-09-29 16:08:18'),
(18232, 385, 'c15b00a1e37657336df8b6aa0eea2db5', 1, 1, 590, 'Modifier Devis', 0, '1', '2017-09-29 16:08:18'),
(18233, 385, 'd34b07afd92adad84e1c4c2ebd92ba95', 1, 1, 594, 'Voir détails', 0, '1', '2017-09-29 16:08:18'),
(18234, 385, '5a05eba5be17eba1f35ef8927bfa16d2', 1, 1, 596, 'Valider Devis', 0, '1', '2017-09-29 16:08:18'),
(18235, 385, '28e267a2a0647d4cb37b18abb1e7d051', 1, 1, 597, 'Voir détails', 0, '1', '2017-09-29 16:08:18'),
(18236, 388, 'd9eeb330625c1b87e0df00986a47be01', 1, 1, 587, 'Ajouter Devis', 0, '1', '2017-09-29 16:08:18'),
(18237, 389, 'da93cdb05137e15aed9c4c18bddd746a', 1, 1, 588, 'Ajouter détail devis', 0, '1', '2017-09-29 16:08:18'),
(18238, 390, 'f9f3c299f9bd0fec014f6bd3f0e06adb', 1, 1, 589, 'Modifier Devis', 0, '1', '2017-09-29 16:08:18'),
(18239, 391, 'e14cce6f1faf7784adb327581c516b90', 1, 1, 591, 'Supprimer Devis', 0, '1', '2017-09-29 16:08:18'),
(18240, 392, '38f10871792c133ebcc6040e9a11cde8', 1, 1, 592, 'Modifier détail Devis', 0, '1', '2017-09-29 16:08:18'),
(18241, 393, '8def42e75fd4aee61c378d9fb303850d', 1, 1, 593, 'Afficher détail devis', 0, '1', '2017-09-29 16:08:18'),
(18242, 394, '7666e87783b0f5a7eec1eea7593f7dfe', 1, 1, 595, 'Valider Devis', 0, '1', '2017-09-29 16:08:18'),
(18243, 430, '6beb279abea6434e3b73229aebadc081', 1, 1, 654, 'Gestion Fournisseurs', 0, '1', '2017-09-29 16:08:18'),
(18244, 430, 'ff95747f3a590b6539803f2a9a394cd5', 1, 1, 655, 'Editer Fournisseur', 0, '1', '2017-09-29 16:08:18'),
(18245, 430, 'fea982f5074995d4ccd6211a71ab2680', 1, 1, 656, 'Valider Fournisseur', 0, '1', '2017-09-29 16:08:18'),
(18246, 430, '1d0411a0dec15fc28f054f1a79d95618', 1, 1, 657, 'Désactiver Fournisseur', 0, '1', '2017-09-29 16:08:18'),
(18247, 430, 'a52affdd109b9362ce47ff18aad53e2a', 1, 1, 658, 'Détails Fournisseur', 0, '1', '2017-09-29 16:08:18'),
(18248, 430, 'c6fe5f222dd563204188e8bf0d69bd9e', 1, 1, 659, 'Détails  Fournisseur', 0, '1', '2017-09-29 16:08:18'),
(18249, 431, 'd644015625a9603adb2fcc36167aeb73', 1, 1, 660, 'Ajouter Fournisseur', 0, '1', '2017-09-29 16:08:18'),
(18250, 432, '58c6694abfd3228d927a5d5a06d40b94', 1, 1, 661, 'Editer Fournisseur', 0, '1', '2017-09-29 16:08:18'),
(18251, 433, 'd072f81cd779e4b0152953241d713ca3', 1, 1, 662, 'Supprimer Fournisseur', 0, '1', '2017-09-29 16:08:18'),
(18252, 434, '657351ce5aa227513e3b50dea77db918', 1, 1, 663, 'Valider Fournisseur', 0, '1', '2017-09-29 16:08:18'),
(18253, 435, '83b693fe35a1be29edafe4f6170641aa', 1, 1, 664, 'Détails Fournisseur', 0, '1', '2017-09-29 16:08:18'),
(18254, 423, '72db1c2280dc3eb6405908c1c5b6c815', 1, 1, 642, 'Information société', 0, '1', '2017-09-29 16:08:18'),
(18255, 21, 'b8e62907d367fb44d644a5189cd07f42', 1, 1, 9, 'Modules', 1, '1', '2017-09-29 16:08:18'),
(18256, 21, '05ce9e55686161d99e0714bb86243e5b', 1, 1, 11, 'Editer Module', 0, '1', '2017-09-29 16:08:18'),
(18257, 21, '819cf9c18a44cb80771a066768d585f2', 1, 1, 12, 'Exporter Module', 0, '1', '2017-09-29 16:08:18'),
(18258, 21, 'd2fc3ee15cee5208a8b9c70f1e53c196', 1, 1, 13, 'Liste task modul', 0, '1', '2017-09-29 16:08:18'),
(18259, 21, 'ad75e6b877f20e3d6fc1789da4dcb3e6', 1, 1, 75, 'Editer Module', 0, '1', '2017-09-29 16:08:19'),
(18260, 21, '064a9b0eff1006fd4f25cb4eaf894ca1', 1, 1, 77, 'Liste task modul Setting', 0, '1', '2017-09-29 16:08:19'),
(18261, 21, 'ac4eb0c94da00a48ad5d995f5e9e9366', 1, 1, 232, 'MAJ Module', 0, '1', '2017-09-29 16:08:19'),
(18262, 22, '44bd5341b0ab41ced21db8b3e92cf5aa', 1, 1, 10, 'Ajouter un Modul', 1, '1', '2017-09-29 16:08:19'),
(18263, 24, '8653b156f1a4160a12e5a94b211e59a2', 1, 1, 16, 'Liste Action Task', 0, '1', '2017-09-29 16:08:19'),
(18264, 24, '86aced763bc02e1957a5c740fb37b4f7', 1, 1, 22, 'Supprimer Application', 0, '1', '2017-09-29 16:08:19'),
(18265, 24, 'f07352e32fe86da1483c6ab071b7e7a9', 1, 1, 99, 'Ajout Affichage WF', 0, '1', '2017-09-29 16:08:19'),
(18266, 25, '1c452aff8f1551b3574e15b74147ea56', 1, 1, 14, 'Ajouter Task Modul', 1, '1', '2017-09-29 16:08:19'),
(18267, 26, 'f085fe4610576987db963501297e4d91', 1, 1, 15, 'Editer Task Modul', 1, '1', '2017-09-29 16:08:19'),
(18268, 26, '38702c272a6f4d334c2f4c3684c8b163', 1, 1, 18, 'Ajouter action modul', 1, '1', '2017-09-29 16:08:19'),
(18269, 27, 'cbae1ebe850f6dd8841426c6fedf1785', 1, 1, 20, 'Liste Action Task', 1, '1', '2017-09-29 16:08:19'),
(18270, 27, 'e30471396f9b86ccdcc94943d80b679a', 1, 1, 147, 'Editer Task Action', 0, '1', '2017-09-29 16:08:19'),
(18271, 27, 'e8996f69e9f6afe1f8f7b9d2487cb662', 1, 1, 581, 'Dupliquer Task Action', 0, '1', '2017-09-29 16:08:19'),
(18272, 28, '502460cd9327b46ee7af0a258ebf8c80', 1, 1, 19, 'Ajouter Action Task', 1, '1', '2017-09-29 16:08:19'),
(18273, 29, '13c107211904d4a2e65dd65c60ec7272', 1, 1, 21, 'Supprimer Application', 1, '1', '2017-09-29 16:08:19'),
(18274, 33, '8c8acf9cf3790b16b1fae26823f45eab', 1, 1, 24, 'Importer des modules', 1, '1', '2017-09-29 16:08:19'),
(18275, 55, '2f4518dab90b706e2f4acd737a0425d8', 1, 1, 70, 'Ajouter Module paramétrage', 1, '1', '2017-09-29 16:08:19'),
(18276, 62, '8e0c0212d8337956ac2f4d6eb180d74b', 1, 1, 74, 'Editer Module paramètrage', 1, '1', '2017-09-29 16:08:19'),
(18277, 79, 'fc54953b47b7fcb11cc14c0c2e2125f0', 1, 1, 98, 'Ajouter Autorisation Etat', 1, '1', '2017-09-29 16:08:19'),
(18278, 108, '966ec2dd83e6006c2d0ff1d1a5f12e33', 1, 1, 146, 'Editer Task Action', 1, '1', '2017-09-29 16:08:19'),
(18279, 167, '3473119f6683893a3f1372dbf7d811e1', 1, 1, 231, 'MAJ Module', 1, '1', '2017-09-29 16:08:19'),
(18280, 378, 'ca32f2764cf93defb97895f5e44b0e54', 1, 1, 580, 'Dupliquer Task Action', 0, '1', '2017-09-29 16:08:19'),
(18281, 408, '605450f3d7c84701b986fa31e1e9fa43', 1, 1, 618, 'Gestion Pays', 0, '1', '2017-09-29 16:08:19'),
(18282, 408, '29ba6cc689eca63dbafb109ec58bc4d6', 1, 1, 619, 'Editer Pays', 0, '1', '2017-09-29 16:08:19'),
(18283, 408, '763fe13212b4324590518773cd9a36fa', 1, 1, 620, 'Valider Pays', 0, '1', '2017-09-29 16:08:19'),
(18284, 408, '3c8427c7313d35219b17572efd380b17', 1, 1, 621, 'Désactiver Pays', 0, '1', '2017-09-29 16:08:19'),
(18285, 409, '3cd55a55307615d72aae84c6b5cf99bc', 1, 1, 622, 'Ajouter Pays', 0, '1', '2017-09-29 16:08:19'),
(18286, 410, 'cfe617d7bc6a9c7d8b86c468f21396f2', 1, 1, 623, 'Editer Pays', 0, '1', '2017-09-29 16:08:19'),
(18287, 411, 'b768486aeb655c48cc411c11fa60e150', 1, 1, 624, 'Supprimer Pays', 0, '1', '2017-09-29 16:08:19'),
(18288, 412, '15e4e24f320daa9d563ae62acff9e586', 1, 1, 625, 'Valider Pays', 0, '1', '2017-09-29 16:08:19'),
(18289, 330, '192715027870a4a612fd44d562e2752f', 1, 1, 499, 'Gestion des produits', 0, '1', '2017-09-29 16:08:19'),
(18290, 330, 'ed13b17897a396c0633d7989f2bc644f', 1, 1, 500, 'Modifier produit', 0, '1', '2017-09-29 16:08:19'),
(18291, 330, '96df3c4057988c54a7d468e5664dba10', 1, 1, 501, 'Détail produit', 0, '1', '2017-09-29 16:08:19'),
(18292, 330, 'eb5b51394e164f00ce8c998310e3a8ba', 1, 1, 502, 'Valider produit', 0, '1', '2017-09-29 16:08:19'),
(18293, 330, '6b087b20929483bb07f8862b39e41f07', 1, 1, 503, 'Désactiver produit', 0, '1', '2017-09-29 16:08:19'),
(18294, 330, 'e82e6cf7b8c6364fe92ee713b885c9f8', 1, 1, 582, 'Archiver produit', 0, '1', '2017-09-29 16:08:19'),
(18295, 331, '93e893c307a6fa63e392f78751ec70ce', 1, 1, 504, 'Ajouter produit', 0, '1', '2017-09-29 16:08:19'),
(18296, 332, 'bcf3beada4a98e8145af2d4fbb744f01', 1, 1, 505, 'Modifier produit', 0, '1', '2017-09-29 16:08:19'),
(18297, 333, '796427ec57f7c13d6b737055ae686b34', 1, 1, 506, 'Detail produit', 0, '1', '2017-09-29 16:08:19'),
(18298, 334, '1fb8cd1a179be07586fa7db05013dd37', 1, 1, 507, 'Valider produit', 0, '1', '2017-09-29 16:08:19'),
(18299, 335, '7779e98d2111faedf458f7aeb548294e', 1, 1, 508, 'Supprimer produit', 0, '1', '2017-09-29 16:08:19'),
(18300, 505, '1eb847d87adcad78d5e951e6110061e5', 1, 1, 773, 'Gestion Proforma', 0, '1', '2017-09-29 16:08:19'),
(18301, 505, '44ef6849d8d5d17d8e0535187e923d32', 1, 1, 778, 'Editer proforma', 0, '1', '2017-09-29 16:08:19'),
(18302, 505, 'b7ce06be726011362a271678547a803c', 1, 1, 779, 'Valider Proforma', 0, '1', '2017-09-29 16:08:19'),
(18303, 505, 'abd8c50f1d2ef4beeeddb68a72973587', 1, 1, 781, 'Détail Proforma', 0, '1', '2017-09-29 16:08:19'),
(18304, 505, 'e20d83df90355eca2a65f56a2556601f', 1, 1, 782, 'Détail Proforma', 0, '1', '2017-09-29 16:08:19'),
(18305, 506, 'd5a6338765b9eab63104b59f01c06114', 1, 1, 774, 'Ajouter pro-forma', 0, '1', '2017-09-29 16:08:19'),
(18306, 507, '95831bde77bc886d6ab4dd5e734de743', 1, 1, 775, 'Editer proforma', 0, '1', '2017-09-29 16:08:19'),
(18307, 508, 'cbb4e1efa1c05b42d25a3a6bcab038a2', 1, 1, 776, 'Ajouter détail proforma', 0, '1', '2017-09-29 16:08:19'),
(18308, 509, 'e9f745054778257a255452c6609461a0', 1, 1, 777, 'valider Proforma', 0, '1', '2017-09-29 16:08:19'),
(18309, 510, 'defef148c404c7e6ac79e4783e0a7ab7', 1, 1, 780, 'Détail Pro-forma', 0, '1', '2017-09-29 16:08:19'),
(18310, 511, '53008d64edf241c937a06f03eff139aa', 1, 1, 783, 'Editer détail proforma', 0, '1', '2017-09-29 16:08:19'),
(18311, 464, 'd57b16b3aad4ce59f909609246c4fd36', 1, 1, 711, 'Gestion des régions', 0, '1', '2017-09-29 16:08:19'),
(18312, 464, 'd2e007184668dd70b9bae44d46d28ded', 1, 1, 712, 'Modifier région', 0, '1', '2017-09-29 16:08:19'),
(18313, 464, 'e74403c99ac8325b78735c531a20442f', 1, 1, 713, 'Valider région', 0, '1', '2017-09-29 16:08:19'),
(18314, 464, '7397a0fab078728bd5c53be61022d5ce', 1, 1, 714, 'Désactiver région', 0, '1', '2017-09-29 16:08:19'),
(18315, 465, '0237bd41cf70c3529681b4ccb041f1fd', 1, 1, 715, 'Ajouter région', 0, '1', '2017-09-29 16:08:19'),
(18316, 466, '6d290f454da473cb8a557829a410c3f1', 1, 1, 716, 'Modifier région', 0, '1', '2017-09-29 16:08:19'),
(18317, 467, '008cd9ea5767c739675fef4e1261cfe8', 1, 1, 717, 'Valider région', 0, '1', '2017-09-29 16:08:19'),
(18318, 468, 'fc477e6a4c90cd427ae81e555c11d6a9', 1, 1, 718, 'Supprimer région', 0, '1', '2017-09-29 16:08:19'),
(18319, 34, '83b9fa44466da4bcd7f8304185bfeac8', 1, 1, 28, 'Services', 0, '1', '2017-09-29 16:08:19'),
(18320, 34, '99aea4598ccc18d4c12ae091c8967d13', 1, 1, 33, 'Valider Service', 0, '1', '2017-09-29 16:08:19'),
(18321, 34, 'bb66cf787052616ea3dd02b0b5199b26', 1, 1, 34, 'Supprimer Service', 0, '1', '2017-09-29 16:08:19'),
(18322, 34, '47c552dce8b761ae2e2a44387a93432b', 1, 1, 144, 'Modifier Service Validé', 0, '1', '2017-09-29 16:08:19'),
(18323, 35, '55043bc4207521e3010e91d6267f5302', 1, 1, 29, 'Ajouter Service', 1, '1', '2017-09-29 16:08:19'),
(18324, 36, '2fea3d893f6b6e81467ddd2a744e4a76', 1, 1, 30, 'Modifier Service', 1, '1', '2017-09-29 16:08:19'),
(18325, 37, '1a0d5897d31b4d5e29022671c1112f59', 1, 1, 31, 'Valider Service', 1, '1', '2017-09-29 16:08:19'),
(18326, 38, '42083d4e159baf7c2ace2bb977e2b0a0', 1, 1, 32, 'Supprimer Service', 1, '1', '2017-09-29 16:08:19'),
(18327, 446, 'b6b6bfbd070b5b3dd84acedae7b854e9', 1, 1, 682, 'Gestion des types de produits', 0, '1', '2017-09-29 16:08:19'),
(18328, 446, '3c5400b775264499825a039d66aa9c90', 1, 1, 683, 'Modifier type', 0, '1', '2017-09-29 16:08:19'),
(18329, 446, 'dcf55bc300d690af4c81e4d2335e60e5', 1, 1, 684, 'Valider type', 0, '1', '2017-09-29 16:08:19'),
(18330, 446, '230b9554d37da1c71986af94962cb340', 1, 1, 685, 'Désactiver type', 0, '1', '2017-09-29 16:08:19'),
(18331, 447, 'e0d163499b4ba11d6d7a648bc6fc6de6', 1, 1, 686, 'Ajouter un type', 0, '1', '2017-09-29 16:08:19'),
(18332, 448, 'ac5a6d087b3c8db7501fa5137a47773e', 1, 1, 687, 'Modifier type', 0, '1', '2017-09-29 16:08:19'),
(18333, 449, '2e8242a93a62a264ad7cfc953967f575', 1, 1, 688, 'Valider type', 0, '1', '2017-09-29 16:08:19'),
(18334, 450, 'e3725ba15ca483b9278f68553eca5918', 1, 1, 689, 'Supprimer type', 0, '1', '2017-09-29 16:08:19'),
(18335, 474, '312fd18860781a7b1b7e33587fa423d4', 1, 1, 727, 'Gestion Type Echeance', 0, '1', '2017-09-29 16:08:19'),
(18336, 474, '46ad76148075d6b458f43e84ddf00791', 1, 1, 728, 'Editer Type Echéance', 0, '1', '2017-09-29 16:08:19'),
(18337, 474, 'add2bf057924e606653fbf5bbd65ca09', 1, 1, 729, 'Valider Type Echéance', 0, '1', '2017-09-29 16:08:19'),
(18338, 474, '463d9e1e8367736b958f0dd84b4e36d5', 1, 1, 730, 'Désactiver Type Echéance', 0, '1', '2017-09-29 16:08:19'),
(18339, 475, '76170b14c7b6f1f7058d079fe24f739b', 1, 1, 731, 'Ajouter Type Echéance', 0, '1', '2017-09-29 16:08:19'),
(18340, 476, 'decc5ed58c4d91e6967c9c67e0975cf0', 1, 1, 732, 'Editer Type Echéance', 0, '1', '2017-09-29 16:08:19'),
(18341, 477, '89db6f23dd8e96a69c6a97f556c44e14', 1, 1, 733, 'Supprimer Type Echéance', 0, '1', '2017-09-29 16:08:19'),
(18342, 478, '7527021168823e0118d44297c7684d44', 1, 1, 734, 'Valider Type Echéance', 0, '1', '2017-09-29 16:08:19'),
(18343, 451, '55ecbb545a49c70c0b728bb0c7951077', 1, 1, 690, 'Gestion des unités de vente', 0, '1', '2017-09-29 16:08:19'),
(18344, 451, '67acd70eb04242b7091d9dcbb08295d7', 1, 1, 691, 'Modifier unité ', 0, '1', '2017-09-29 16:08:19'),
(18345, 451, '7363022ed5ad047bfe86d3de4b75b1f4', 1, 1, 692, 'Valider unité', 0, '1', '2017-09-29 16:08:19'),
(18346, 451, 'ec77eb95736c27bfc269cbffc8e113f1', 1, 1, 693, 'Désactiver unité', 0, '1', '2017-09-29 16:08:19'),
(18347, 452, '3a5e8dfe211121eda706f8b6d548d111', 1, 1, 694, 'ajouter une unité', 0, '1', '2017-09-29 16:08:19'),
(18348, 453, '9b7a578981de699286376903e96bc3c7', 1, 1, 695, 'Modifier une unité', 0, '1', '2017-09-29 16:08:19'),
(18349, 454, '62355588366c13ddbadc7a7ca1d226ad', 1, 1, 696, 'Valider une unité', 0, '1', '2017-09-29 16:08:19'),
(18350, 455, 'e5f53a3aaa324415d781156396938101', 1, 1, 697, 'Supprimer une unité', 0, '1', '2017-09-29 16:08:19'),
(18351, 14, '56de23d30d6c54297c8d9772cd3c7f57', 1, 1, 1, 'Utilisateurs', 1, '1', '2017-09-29 16:08:19'),
(18352, 14, 'e656756fb7b39a4e6ddcabca75ff2970', 1, 1, 3, 'Editer Utilisateur', 0, '1', '2017-09-29 16:08:20'),
(18353, 14, 'c073a277957ca1b9f318ac3902555708', 1, 1, 6, 'Permissions', 0, '1', '2017-09-29 16:08:20'),
(18354, 14, 'c51499ddf7007787c4434661c658bbd1', 1, 1, 8, 'Désactiver compte', 0, '1', '2017-09-29 16:08:20'),
(18355, 14, '10096b6f54456bcfc85081523ee64cf6', 1, 1, 23, 'Supprimer utilisateur', 0, '1', '2017-09-29 16:08:20'),
(18356, 14, 'a0999cbed820aff775adf27276ee54a4', 1, 1, 25, 'Editer Utilisateur', 0, '1', '2017-09-29 16:08:20'),
(18357, 14, '9aa6877656339ddff2478b20449a924b', 1, 1, 27, 'Activer compte', 0, '1', '2017-09-29 16:08:20'),
(18358, 14, 'f4c79bb797b92dfa826b51a44e3171af', 1, 1, 112, 'Utilisateurs', 0, '1', '2017-09-29 16:08:20'),
(18359, 14, 'd7f7afd70a297e5c239f6cf271138390', 1, 1, 143, 'Utilisateur Archivé', 0, '1', '2017-09-29 16:08:20'),
(18360, 14, '17c98287fb82388423e04d24404cf662', 1, 1, 579, 'Permissions', 0, '1', '2017-09-29 16:08:20'),
(18361, 15, 'df91a8e6f8ee2cde64495fc0cc7d6c6f', 1, 1, 2, 'Ajouter Utilisateurs', 1, '1', '2017-09-29 16:08:20'),
(18362, 16, '2bb46b52eab9eecbdbba35605da07234', 1, 1, 4, 'Editer Utilisateurs', 1, '1', '2017-09-29 16:08:20'),
(18363, 17, '3f59a1326df27378304e142ab3bec090', 1, 1, 5, 'Permission', 1, '1', '2017-09-29 16:08:20'),
(18364, 18, 'b919571c88d036f8889742a81a4f41fd', 1, 1, 7, 'Supprimer utilisateur', 1, '1', '2017-09-29 16:08:20'),
(18365, 19, '38f89764a26e39ce029cd3132c12b2a5', 1, 1, 45, 'Compte utilisateur', 1, '1', '2017-09-29 16:08:20'),
(18366, 20, 'f988a608f35a0bc551cb038b1706d207', 1, 1, 26, 'Activer utilisateur', 1, '1', '2017-09-29 16:08:20'),
(18367, 107, 'b7b3a09fdd73a5b0a3e5ed8a2828f548', 1, 1, 145, 'Désactiver l''utilisateur', 1, '1', '2017-09-29 16:08:20'),
(18368, 161, '0d374b7e2fe21a2e2641c092a3c7f2e9', 1, 1, 222, 'Changer le mot de passe', 1, '1', '2017-09-29 16:08:20'),
(18369, 162, '6f642ee30722158f0318653b9113b887', 1, 1, 224, 'History', 1, '1', '2017-09-29 16:08:20'),
(18370, 163, 'cc907fac13631903d129c137d671d718', 1, 1, 225, 'Activities', 1, '1', '2017-09-29 16:08:20'),
(18371, 384, '3d4eaa53061f51b0c4435bd8e4b89c17', 1, 1, 585, 'Gestion Vente', 0, '1', '2017-09-29 16:08:20'),
(18372, 436, '2c3b01c696ff401a2ac9ffedb7a06e4a', 1, 1, 665, 'Gestion Villes', 1, '1', '2017-09-29 16:08:20'),
(18373, 436, 'b9649163b368f863a0e8036f11cd81ae', 1, 1, 666, 'Editer Ville', 0, '1', '2017-09-29 16:08:20'),
(18374, 436, '89dec6dabcb210cdb9dd28bbef90d43e', 1, 1, 667, 'Editer Ville', 0, '1', '2017-09-29 16:08:20'),
(18375, 436, '4a2edbdcbda34c9d3d1e6abe73643b37', 1, 1, 668, 'Valider Ville', 0, '1', '2017-09-29 16:08:20'),
(18376, 436, '0c8ad6595a4516be83ba5a9cdb7ea9a1', 1, 1, 669, 'Désactiver Ville', 0, '1', '2017-09-29 16:08:20'),
(18377, 437, 'e152b9052d3dcfcac593489dbdc0f61c', 1, 1, 670, 'Ajouter ville', 1, '1', '2017-09-29 16:08:20'),
(18378, 438, '3107e0cd0e0df14c4e94aa088e4457d7', 1, 1, 671, 'Editer Ville', 1, '1', '2017-09-29 16:08:20'),
(18379, 439, 'da79d9214ed5819d7f4f1e3070629a3d', 1, 1, 672, 'Supprimer Ville', 1, '1', '2017-09-29 16:08:20'),
(18380, 440, 'fe03a68d17c62ff2c27329573a1b3550', 1, 1, 673, 'Valider Ville', 0, '1', '2017-09-29 16:08:20');

-- --------------------------------------------------------

--
-- Structure de la table `rules_action_temp`
--

CREATE TABLE IF NOT EXISTS `rules_action_temp` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'rule id',
  `idf` varchar(32) DEFAULT NULL COMMENT 'IDF Rul Mgt',
  `service` int(11) DEFAULT NULL COMMENT 'Service ID',
  `userid` int(11) NOT NULL COMMENT 'id user',
  `descrip` varchar(75) NOT NULL COMMENT 'description de rule',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_rule` (`idf`,`userid`),
  KEY `rules_action_user_sys` (`userid`),
  KEY `rule_action_service_id` (`service`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Table store rules for each user for each App and action' AUTO_INCREMENT=2 ;

--
-- Contenu de la table `rules_action_temp`
--

INSERT INTO `rules_action_temp` (`id`, `idf`, `service`, `userid`, `descrip`) VALUES
(1, '1eb847d87adcad78d5e951e6110061e5', 1, 1, 'Gestion Proforma');

-- --------------------------------------------------------

--
-- Structure de la table `services`
--

CREATE TABLE IF NOT EXISTS `services` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID Groupe',
  `service` varchar(150) CHARACTER SET latin1 NOT NULL COMMENT 'Nom du Groupe',
  `sign` int(11) DEFAULT NULL COMMENT 'Exige une Signature',
  `etat` int(11) DEFAULT '0' COMMENT 'Etat line',
  `creusr` int(11) DEFAULT NULL COMMENT 'Créér par',
  `credat` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'Date de création',
  `updusr` int(11) DEFAULT NULL COMMENT 'Modifiée par',
  `upddat` datetime DEFAULT NULL COMMENT 'Date de modification',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- Contenu de la table `services`
--

INSERT INTO `services` (`id`, `service`, `sign`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(1, 'Administrateur', 1, 1, NULL, '2016-12-05 22:48:26', NULL, NULL),
(2, 'Agent de saisie', 0, 1, NULL, '2016-12-05 22:48:26', NULL, NULL),
(3, 'Direction Générale', 0, 1, NULL, '2016-12-05 22:48:26', NULL, NULL),
(4, 'Informatique', 1, 1, NULL, '2016-12-05 22:48:26', NULL, NULL),
(5, 'Finance', 0, 1, NULL, '2016-12-05 22:48:26', NULL, NULL),
(17, 'Direction Radiocommunication', 0, 1, 1, '2016-12-07 15:48:34', 1, '2017-09-03 16:30:08'),
(18, 'Test service modeif', 1, 1, 1, '2017-05-06 16:34:05', 1, '2017-05-06 16:35:33');

-- --------------------------------------------------------

--
-- Structure de la table `session`
--

CREATE TABLE IF NOT EXISTS `session` (
  `id_sys` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID SYS',
  `id` varchar(32) NOT NULL COMMENT 'id session MD5',
  `user` varchar(20) NOT NULL COMMENT 'Nom utilisateur',
  `dat` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Time open session',
  `expir` datetime DEFAULT NULL COMMENT 'Date expiration',
  `ip` varchar(15) NOT NULL COMMENT 'IP Client',
  `browser` varchar(100) NOT NULL COMMENT 'Browser Utilisé',
  `userid` int(11) NOT NULL COMMENT 'ID utilisateur',
  PRIMARY KEY (`id_sys`),
  UNIQUE KEY `id` (`id`),
  KEY `session_user_id` (`userid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=202 ;

--
-- Contenu de la table `session`
--

INSERT INTO `session` (`id_sys`, `id`, `user`, `dat`, `expir`, `ip`, `browser`, `userid`) VALUES
(1, '8588b3bb820b259aa9a852e00554d4d3', 'admin', '2017-08-23 00:06:14', '2017-08-24 10:44:20', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:54.0) Gecko/20100101 Firefox/54.0', 1),
(2, 'b421eda451b63a73f4753ef5b0813da1', 'admin', '2017-08-24 10:44:20', '2017-08-24 15:38:08', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:54.0) Gecko/20100101 Firefox/54.0', 1),
(3, 'f7f0619cadb4eb18fc2938c2bffcb271', 'admin', '2017-08-24 15:39:55', '2017-08-25 01:44:56', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:54.0) Gecko/20100101 Firefox/54.0', 1),
(4, '4f88a44011d745df0fd18950a0ddc581', 'admin', '2017-08-25 01:45:03', '2017-08-26 23:21:14', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:54.0) Gecko/20100101 Firefox/54.0', 1),
(5, '18dc46b502cfaca426d762e4883a436f', 'admin', '2017-08-26 23:21:14', '2017-08-28 01:30:42', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:54.0) Gecko/20100101 Firefox/54.0', 1),
(6, 'e17d1e969a85ad3567f707178780deca', 'admin', '2017-08-28 01:30:42', '2017-08-28 19:01:43', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(7, 'c7fdd112ba4eeb04debee98ab4a1e551', 'admin', '2017-08-28 19:01:43', '2017-08-28 21:56:24', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(8, 'ad8becb536821ab2fc902d48a95c151b', 'admin', '2017-08-28 21:56:31', '2017-08-29 11:26:38', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(9, '255d7d71e1c26be296405645bd29fecc', 'admin', '2017-08-29 11:26:38', '2017-08-29 13:20:57', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(10, 'a485aa7282dbeb9a6c43a3e5529d0aec', 'admin', '2017-08-29 13:21:01', '2017-08-29 21:21:05', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(11, '4e57aef002f5752577efd697944f4e31', 'admin', '2017-08-29 21:21:14', '2017-08-31 13:18:49', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(12, 'dcc2eee8a8b42bbf52b60a7f9a2e4943', 'admin', '2017-08-31 13:18:49', '2017-09-03 10:38:52', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(13, '979b75ba5335a2715323d0dcd58adc18', 'admin', '2017-09-03 10:38:52', '2017-09-03 15:46:22', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(14, '39661236e558e8acc6e4b460fef6a646', 'admin', '2017-09-03 15:46:22', '2017-09-03 20:20:38', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(15, '54c343dee4295cd4a501dd6329865555', 'admin', '2017-09-03 20:20:42', '2017-09-04 10:54:57', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(16, '03daca12167e339a1b112fc8197bdab6', 'admin', '2017-09-04 10:55:05', '2017-09-04 13:35:59', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(17, '4ffed2a53094e8e18b35e3ce4f4eb80a', 'admin', '2017-09-04 13:36:03', '2017-09-04 15:34:30', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(18, 'e6074d748578af99c4c1cd0c0062ec40', 'admin', '2017-09-04 15:34:37', '2017-09-05 01:32:20', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(19, 'f17d66abbd88cebca7ef90a52114e418', 'admin', '2017-09-05 01:32:26', '2017-09-05 12:03:46', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(20, 'cf83a9ee89f714235ec0dc861d9c07cd', 'admin', '2017-09-05 12:03:46', '2017-09-05 13:58:07', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(21, '6dac3aa292a23545458ff5d84776f961', 'admin', '2017-09-05 13:58:07', '2017-09-06 22:29:58', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(22, '4d2791ecf3220a94dcfcfcda2c6b86b2', 'admin', '2017-09-06 22:29:58', '2017-09-07 10:45:03', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(23, '5e36c75d23b6daeb577ffa2893317def', 'admin', '2017-09-07 10:45:10', '2017-09-07 12:38:16', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(24, '2b9954810b5c3a674cd103d31f34272a', 'admin', '2017-09-07 12:38:19', '2017-09-07 19:58:19', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(25, 'c821e340829b4ba6a8eb25c6aba1b39f', 'admin', '2017-09-07 19:58:27', '2017-09-07 21:56:26', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(26, '6b2bfd903656b2017ff11b5075233965', 'admin', '2017-09-07 21:56:35', '2017-09-08 09:49:12', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(27, '5cf75afa33e461b6934f8563c1a8706c', 'admin', '2017-09-08 09:49:17', '2017-09-08 12:18:08', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(28, 'ec373c1fc9451bfd42197b0dac90e88a', 'admin', '2017-09-08 12:18:12', '2017-09-08 20:39:05', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(29, 'cc260496818eca3c4effa39d27071624', 'admin', '2017-09-08 20:40:51', '2017-09-08 22:54:12', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(30, '8392ce0e452977167cf93173c79f727f', 'admin', '2017-09-08 22:54:33', '2017-09-08 22:58:20', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(31, '74fdacc146f970e037c45b44dd650bfa', 'admin', '2017-09-08 22:58:33', '2017-09-09 08:53:55', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(32, 'b3543e44d4f7eac52cd93756571208c5', 'admin', '2017-09-09 08:54:03', '2017-09-09 12:26:45', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(33, '3172ab458c22781838188636334b10b9', 'admin', '2017-09-09 12:26:53', '2017-09-10 09:27:38', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(34, '38bb2a1282041d5a9dde5b54fd1f974a', 'admin', '2017-09-10 09:27:47', '2017-09-10 15:12:51', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(35, '40685a0878e64dada36af66135786d1b', 'admin', '2017-09-10 15:12:59', '2017-09-10 18:57:05', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(36, '2a9f4c3546c27b5b8185289c522aa8b2', 'admin', '2017-09-10 18:57:14', '2017-09-10 22:30:06', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(37, '08af89ec962da1a96ec9f4abf71ff4fe', 'admin', '2017-09-10 22:30:10', '2017-09-11 08:48:26', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(38, 'c39470c85d56d8891cf71c01b830648a', 'admin', '2017-09-11 08:48:36', '2017-09-11 17:00:43', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(39, '4687c4426d9c7c0a1fc56e46db55eb9f', 'admin', '2017-09-11 17:02:04', '2017-09-12 12:54:50', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(40, '08e31e1710a26753930153976bd5da82', 'admin', '2017-09-12 12:54:55', '2017-09-12 14:23:27', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(41, '9049740a821f9df5421d9d7a0b6fd653', 'admin', '2017-09-12 14:23:35', '2017-09-12 22:13:21', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(42, '02f8f1ae45fc327ecf42429ed47e979c', 'admin', '2017-09-12 22:13:25', '2017-09-13 08:29:21', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(43, '6ba8ebf0a20ce259c569f2444653e601', 'admin', '2017-09-13 08:29:26', '2017-09-13 21:08:15', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(44, '5f52f27bcdb04e28e25ecedaf23fc131', 'admin', '2017-09-13 21:08:25', '2017-09-14 11:52:11', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(45, 'ce444ece482b9ceced6b2f5954cd2e69', 'admin', '2017-09-14 11:52:18', '2017-09-14 19:01:27', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(46, 'b5b35f8be7a70d40fa5e3615f3093b7c', 'admin', '2017-09-14 19:01:35', '2017-09-14 21:44:35', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(47, '4bd6e977f20dde2fc0e7821da011c29a', 'admin', '2017-09-14 21:44:47', '2017-09-15 12:35:47', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(48, '51544d83021265b3cb555a0eae6c52f2', 'admin', '2017-09-15 12:36:18', '2017-09-15 20:44:14', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(49, '81abbee2851f6b984eb4a389d943ccd0', 'admin', '2017-09-15 20:44:14', '2017-09-15 23:11:40', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(50, '80fc616bef3c5ddbc4ca498e89dddf65', 'admin', '2017-09-15 23:11:48', '2017-09-16 21:13:38', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(51, '905baab0506e3d609b6194a7a7b2cd86', 'admin', '2017-09-17 00:37:10', '2017-09-18 08:47:24', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(52, 'd6620f59c7c5c52f284737f41cd7a99d', 'admin', '2017-09-18 08:47:27', '2017-09-18 13:55:11', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(53, '6a4168778e8e37f6fbb36ec191287df2', 'admin', '2017-09-18 13:55:22', '2017-09-18 20:06:13', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(54, '30d288e7b04e1f5c72f38ea2928078ff', 'admin', '2017-09-18 20:06:28', '2017-09-18 23:36:13', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(55, '7cb9881f9e5b74e919bc71ac2f29d0f6', 'admin', '2017-09-19 00:25:36', '2017-09-19 10:03:49', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(56, 'd1a7ab0c4174f3c5a99e38b542992881', 'admin', '2017-09-19 10:03:57', '2017-09-19 12:26:36', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(57, '553a4eb0eb040ab2247532164c1c88bf', 'admin', '2017-09-19 12:27:00', '2017-09-19 13:52:42', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(58, 'a7bdc355774f52a4d2357d1d3a5d0501', 'admin', '2017-09-19 13:52:42', '2017-09-20 10:02:24', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(59, 'b1ec432c5dec5d4cc60449d5be2fd0a8', 'admin', '2017-09-20 10:02:29', '2017-09-20 19:02:34', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(60, '860e9aa717c99893f71c0d944b7d1257', 'admin', '2017-09-20 19:02:34', '2017-09-20 20:28:44', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(61, '2777cfe379124011428aba7f96742a4a', 'admin', '2017-09-20 20:28:51', '2017-09-20 23:58:51', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(62, '1e1221dc835c955a550a207f8d2d5a42', 'admin', '2017-09-20 23:58:53', '2017-09-21 09:08:11', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(63, 'fe755c35e0bfee480f89b9a5743ec5e6', 'admin', '2017-09-21 09:08:13', '2017-09-21 11:33:59', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(64, '8c8608b613277c10eb9003a1407461e6', 'admin', '2017-09-21 11:34:07', '2017-09-21 22:04:38', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(65, 'd2d35452935729c8d762f7e5b9f9a239', 'admin', '2017-09-21 22:04:47', '2017-09-21 23:43:46', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(66, '1344fac7061c58d195cde9eabee014c8', 'admin', '2017-09-21 23:43:52', '2017-09-21 23:55:59', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(67, '29dad7d9d851cc5cf591ba8085413c20', 'admin', '2017-09-21 23:56:05', '2017-09-22 09:43:39', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(68, '09a6ce14d843ffbcb3971787e31c9247', 'admin', '2017-09-22 11:21:20', '2017-09-22 11:25:54', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(69, '9e3bcb3537508c4f45acc58f340a7d9b', 'admin', '2017-09-22 11:26:04', '2017-09-22 11:28:05', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(70, '023d0dbd93bc3239208302464005f705', 'admin', '2017-09-22 11:29:29', '2017-09-22 11:31:33', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(71, '3866a1559c97330d3ac058322c619278', 'admin', '2017-09-22 12:13:19', '2017-09-22 13:15:10', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(72, '7fbfce4f9e77e99defdaac482f7c4c7a', 'admin', '2017-09-22 18:48:03', '2017-09-22 22:28:39', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(73, '8d30e3d460c2412d8627240cd5899e97', 'admin', '2017-09-22 22:28:41', '2017-09-23 08:50:50', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(74, '4dbbb0621e031b28d41ad3f92f4dbe49', 'admin', '2017-09-23 08:55:56', '2017-09-23 11:40:54', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(75, '0ceac70f1e5d2434b9f6c34f6022ace7', 'admin', '2017-09-23 11:42:39', '2017-09-23 12:00:02', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(76, '3848b177409677fb7ac88487e4458af7', 'admin', '2017-09-23 12:01:18', '2017-09-24 11:39:40', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(77, '2008ef12f256e3e47bdf3062eafe4114', 'admin', '2017-09-24 11:39:40', '2017-09-24 13:18:48', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(78, '3370315451bb7f7cb188b656604e3a81', 'admin', '2017-09-24 13:44:26', '2017-09-24 13:53:06', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(79, 'd014e9bd5eb7b4918db5bed1105304fc', 'admin', '2017-09-24 13:54:51', '2017-09-24 13:55:05', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(80, 'a426e79f11e8d1bab053ffbf99bcc22e', 'admin', '2017-09-24 13:55:50', '2017-09-24 13:56:02', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(81, 'a91234840fa3bf94e269afc82d32f903', 'admin', '2017-09-24 13:58:28', '2017-09-24 14:09:44', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(82, '2c17a8081a1c8f98a7ac46c8e49e7809', 'admin', '2017-09-24 14:09:49', '2017-09-24 14:10:03', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(83, 'd7a717cfe1bf5b4a1cd26fa5157dfb1c', 'admin', '2017-09-24 14:10:13', '2017-09-24 14:10:37', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(84, '158459f9b81629cb7ce19f845da23e2a', 'admin', '2017-09-24 14:11:38', '2017-09-24 14:11:52', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(85, 'eb4b8c8ee3a9058bb32a80e61dcd93da', 'admin', '2017-09-24 14:12:01', '2017-09-24 14:12:16', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(86, '66370f2eb147339c7d99cc6f18117fcb', 'admin', '2017-09-24 14:19:58', '2017-09-24 14:20:12', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(87, '379ba7f5a6efd5223801c46dae7bcbd2', 'admin', '2017-09-24 14:22:00', '2017-09-24 14:22:15', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(88, 'b10103edc3bcbf2579a81ad508ba5f70', 'admin', '2017-09-24 14:22:23', '2017-09-24 14:22:37', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(89, '5a8866f9b789bd7f927a64210b94b133', 'admin', '2017-09-24 14:22:46', '2017-09-24 14:23:01', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(90, '6e533b44af9ee5386fd8d4606d73477f', 'admin', '2017-09-24 14:24:52', '2017-09-24 14:25:06', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(91, '5cca22fc4b9d42141b59e94ae0c3f29a', 'admin', '2017-09-24 14:25:11', '2017-09-24 14:25:24', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(92, 'f0f32d99defd358a795a360a6314a2dc', 'admin', '2017-09-24 14:30:14', '2017-09-24 14:30:27', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(93, '03382d6ec19db069be1d2a0caef1d601', 'admin', '2017-09-24 14:30:32', '2017-09-24 14:30:55', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(94, '20bacb751cf65ae1c9968f26f38c77e9', 'admin', '2017-09-24 14:42:13', '2017-09-24 14:42:21', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(95, '6d3a4948daf2e29a03964b3bcc75dfa6', 'admin', '2017-09-24 14:42:32', '2017-09-24 14:42:45', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(96, '354ba37ef2e12ba1e5064f9646b9efb0', 'admin', '2017-09-24 14:44:20', '2017-09-24 14:44:32', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(97, 'bd5e6b868e876317467092ed6d3779a4', 'admin', '2017-09-24 14:45:28', '2017-09-24 14:45:41', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(98, 'f6c325c1055bf95dffb97aa35351f138', 'admin', '2017-09-24 14:46:34', '2017-09-24 14:46:47', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(99, '01c02d7a5c4c8dfb3f8193943bf69cd9', 'admin', '2017-09-24 14:47:31', '2017-09-24 14:47:44', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(100, 'c0d6624921d88a9d62d14228cddd7487', 'admin', '2017-09-24 15:18:51', '2017-09-24 15:20:08', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(101, '352ea18509ac4076efc1c883dc917cb4', 'admin', '2017-09-24 15:20:08', '2017-09-24 15:20:23', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(102, '679765040895ebf55c8bc55bca7a58e4', 'admin', '2017-09-24 15:21:06', '2017-09-24 15:23:16', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(103, '74f9dca632f141d084617c8e483d6ab8', 'admin', '2017-09-24 15:23:20', '2017-09-24 15:23:53', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(104, '4d16e8b5b14a87d562d742198102dbfb', 'admin', '2017-09-24 15:28:37', '2017-09-24 15:31:33', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(105, '1f35b2b6c7fc469c5c3018819159b16e', 'admin', '2017-09-24 15:31:36', '2017-09-24 15:32:09', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(106, '9d6659a6ed741f5091c3173992fa752c', 'admin', '2017-09-24 15:34:53', '2017-09-24 15:35:26', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(107, 'cc5deab5b6bb635aede3f489de3e83de', 'admin', '2017-09-24 15:35:37', '2017-09-24 15:36:10', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(108, 'c0f20f98226ff73e7edf3faba509e58e', 'admin', '2017-09-24 15:36:58', '2017-09-24 15:37:33', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(109, '8268defedee72cd61d0e589bc89de64e', 'admin', '2017-09-24 15:37:57', '2017-09-24 15:38:31', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(110, '370d1f38724cf649570b001b95c64452', 'admin', '2017-09-24 15:41:04', '2017-09-24 15:41:38', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(111, '84676b7dd6961bde36342ce2019dfd57', 'admin', '2017-09-24 15:44:36', '2017-09-24 15:45:11', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(112, '8cd4009499878be28bf57224f2d95827', 'admin', '2017-09-24 15:46:16', '2017-09-24 15:46:49', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(113, '0f3da6744f579392dd91be805c5598a1', 'admin', '2017-09-24 17:17:31', '2017-09-24 17:18:05', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(114, 'd6739548d1586eff24acab952a78ffcf', 'admin', '2017-09-24 17:21:44', '2017-09-24 17:22:18', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(115, 'f0c8d3bb783b2c705428355d9a1f99a3', 'admin', '2017-09-24 17:22:33', '2017-09-24 17:23:07', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(116, '20d4c4e2bf5f5f121c1e5e974dba0e8d', 'admin', '2017-09-24 17:24:58', '2017-09-24 17:25:05', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(117, '5750461c6cc232f9fe36471785f826da', 'admin', '2017-09-24 17:25:09', '2017-09-24 17:25:42', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(118, 'edeef72f64049a54faa95c9ce541da6e', 'admin', '2017-09-24 17:25:58', '2017-09-24 17:26:31', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(119, '0c66b75b6a83582bd0a934e0e079f1cd', 'admin', '2017-09-24 17:27:50', '2017-09-24 17:28:23', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(120, '7f11deb735cc0cf207237a21ce0da8b4', 'admin', '2017-09-24 17:36:40', '2017-09-24 17:37:14', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(121, '4bc8ea814a7037acd4d7599b57ea39eb', 'admin', '2017-09-24 17:40:14', '2017-09-24 17:40:48', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(122, 'cac45ac4e419fcb9219da7457d252d47', 'admin', '2017-09-24 17:42:25', '2017-09-24 17:43:00', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(123, 'a1da5f8b589711914f0e3f33f55f0014', 'admin', '2017-09-24 17:46:33', '2017-09-24 17:49:09', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(124, '450ab76dddde432aabf89dcb82f234c9', 'admin', '2017-09-24 17:49:12', '2017-09-24 17:50:06', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(125, 'bf6940ac029ccde8ffdff2666a096f1a', 'admin', '2017-09-24 17:51:22', '2017-09-24 17:51:55', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(126, 'ec09d0cf516b760944410fc7eb5b9f44', 'admin', '2017-09-24 17:52:47', '2017-09-24 17:52:56', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(127, 'a029d9dcc88606302f4dfac2ab55ed70', 'admin', '2017-09-24 17:54:15', '2017-09-24 17:54:25', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(128, 'a2857c5f9e830d32cd4a5153563c0a9e', 'admin', '2017-09-24 18:38:19', '2017-09-24 18:40:39', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(129, '147b3374ab095b06e31e1728a5276b90', 'admin', '2017-09-24 18:40:43', '2017-09-24 18:41:27', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(130, '783a4bb69654e86a0fde2f8b7eba6b4a', 'admin', '2017-09-24 18:41:27', '2017-09-24 18:42:01', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(131, '49548c20c2ee50ca6d577e3ddc0de436', 'admin', '2017-09-24 18:42:34', '2017-09-24 18:42:42', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(132, '09712794ed02c0169801283af2a9a69a', 'admin', '2017-09-24 18:44:51', '2017-09-24 18:44:59', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(133, 'c2492c76dc5c7969ee8fde31d67bd10c', 'admin', '2017-09-24 18:45:02', '2017-09-24 18:45:35', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(134, '94b4b6208efd3b8a25ab74e30df1484c', 'admin', '2017-09-24 18:48:52', '2017-09-24 18:49:24', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(135, 'ca1afbb44c1512a8a574b197f989bc72', 'admin', '2017-09-24 18:49:28', '2017-09-24 18:50:01', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(136, '6060fc38dcac39b81ecc3f39db9ad730', 'admin', '2017-09-24 18:57:08', '2017-09-24 18:57:16', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(137, 'f11ab1b252cea5e61a6857b2b4ad3ea2', 'admin', '2017-09-24 18:57:19', '2017-09-24 18:57:52', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(138, '101f124fd5a485c7f25b6d3ad3d6e9b4', 'admin', '2017-09-24 18:58:16', '2017-09-24 18:58:49', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(139, '184161d1b280e3d8c0cd2c13f7555204', 'admin', '2017-09-24 19:00:39', '2017-09-24 19:01:12', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(140, 'f1994f1ec7493ce367d3e7b2f8a72531', 'admin', '2017-09-24 19:07:00', '2017-09-24 19:07:16', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(141, '638aca22db8d136f9570423b4cf7b1f6', 'admin', '2017-09-24 19:09:06', '2017-09-24 19:09:40', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(142, 'fc8bb0319018c75330cd52bb652233e2', 'admin', '2017-09-24 19:11:18', '2017-09-24 19:11:52', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(143, 'd64bcb3ad55d21bdeeb7dab8271774e4', 'admin', '2017-09-24 19:20:38', '2017-09-24 19:21:17', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(144, 'eb443d804660d4d7ba88a789d3c1c0fc', 'admin', '2017-09-24 19:22:33', '2017-09-24 19:22:40', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(145, 'cb641542d68ea56d8a153a48bec60d19', 'admin', '2017-09-24 19:29:45', '2017-09-24 19:30:16', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(146, '43002a36af829f6f33f006ffaaddc9a6', 'admin', '2017-09-24 19:31:42', '2017-09-24 23:45:10', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(147, 'd1f6061cc9e3c1759e40673e68d82d6f', 'admin', '2017-09-24 23:46:59', '2017-09-24 23:47:33', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(148, 'a784e337c588f1f1e8a07c43c4dba367', 'admin', '2017-09-24 23:48:49', '2017-09-24 23:49:23', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(149, 'f67d8c58fcf02a092ac1607b1b4c36da', 'admin', '2017-09-24 23:51:41', '2017-09-24 23:52:14', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(150, '4b7e92e5e8481bc9a0cdd66afb748647', 'admin', '2017-09-24 23:53:22', '2017-09-24 23:53:56', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(151, '5035ca5553bc09f5f336bed6a1bbbfa8', 'admin', '2017-09-24 23:54:38', '2017-09-24 23:55:12', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(152, 'a5660800e94f14ef572620adc5362a92', 'admin', '2017-09-24 23:56:00', '2017-09-24 23:56:34', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(153, '29038b64cefb3c8f07ac5f8ce04611df', 'admin', '2017-09-24 23:57:07', '2017-09-24 23:57:40', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(154, '62fd0cfe3a3af98f0b32eea0bdc11af2', 'admin', '2017-09-24 23:59:05', '2017-09-24 23:59:14', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(155, 'a80d2664ba0ed843ceba2e3786a5048a', 'admin', '2017-09-25 00:02:18', '2017-09-25 00:02:50', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(156, '7410efe333f44e4d3b262cadd31db394', 'admin', '2017-09-25 00:03:51', '2017-09-25 00:04:08', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(157, '09690827f4fb8b19be33c921c6a26e80', 'admin', '2017-09-25 00:13:31', '2017-09-25 00:13:44', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(158, '239d0d4d14c238fa9a65210a8498d9d3', 'admin', '2017-09-25 00:17:29', '2017-09-25 00:17:42', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(159, 'eb6b1aba1aed584a1eea1adc1292f110', 'admin', '2017-09-25 00:19:16', '2017-09-25 00:19:29', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(160, 'b71264dd2d8afe24d0fd93c85a8d4fbe', 'admin', '2017-09-25 00:22:56', '2017-09-25 00:23:09', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(161, '8d89c5f4c43e2697ba7dce9dffb3d3f3', 'admin', '2017-09-25 00:25:45', '2017-09-25 00:25:58', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(162, '5aeffad6f296d0dbbf4f1fc5758aa9fa', 'admin', '2017-09-25 00:28:05', '2017-09-25 00:28:19', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(163, 'dc63969eea407c7bb5eab4486816347e', 'admin', '2017-09-25 00:31:09', '2017-09-25 00:31:22', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(164, '4d198c3e60d4c7cd0c977efdc34affad', 'admin', '2017-09-25 00:32:26', '2017-09-25 00:32:40', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(165, 'd9c805120442b3f14378fa45c98ca510', 'admin', '2017-09-25 00:34:57', '2017-09-25 00:35:11', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(166, 'f9a44e63513d1ae3be2286b242cb0aec', 'admin', '2017-09-25 00:36:35', '2017-09-25 00:36:50', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(167, '39e762e375e36892bac4f8bf6aed8049', 'admin', '2017-09-25 00:41:52', '2017-09-25 00:42:07', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(168, '323ad7bc1712d487c3da99ffe1b4f493', 'admin', '2017-09-25 00:45:01', '2017-09-25 00:45:15', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(169, 'd13735ae04131ac47ff84039f299cd85', 'admin', '2017-09-25 00:46:10', '2017-09-25 00:46:24', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(170, 'af297dacdd9f4c476dbfc3ad5478f91b', 'admin', '2017-09-25 00:47:01', '2017-09-25 00:47:15', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(171, 'fe9b53b5ceb6fcbf4d8900d2483a0631', 'admin', '2017-09-25 01:03:08', '2017-09-25 01:03:23', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(172, '6242d7502d1c3c2eb9e53cf0372a99e8', 'admin', '2017-09-25 01:05:49', '2017-09-25 01:06:02', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(173, 'dfba083ff6a8c76b79a44dfddd4d380e', 'admin', '2017-09-25 01:06:07', '2017-09-25 01:06:20', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(174, '51e549ea60f9063fc38d1241af74d481', 'admin', '2017-09-25 01:11:08', '2017-09-25 08:05:24', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(175, 'c4369c13c418ee5ef00db80b36c63670', 'admin', '2017-09-25 08:07:11', '2017-09-25 08:34:22', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(176, '1681e47027a59102b63d3889ad5c741f', 'admin', '2017-09-25 08:34:31', '2017-09-25 08:36:45', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(177, '0214f12875aa7ade56aca0eaa74383f7', 'admin', '2017-09-25 08:36:56', '2017-09-25 08:39:20', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(178, '8e764a7176941c70b14666b64b72d41d', 'admin', '2017-09-26 08:29:58', '2017-09-26 10:40:08', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(179, 'd65801177527661f0d6ae0230828d60b', 'admin', '2017-09-26 10:40:08', '2017-09-26 11:11:10', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(180, 'cde0a724a7477d4528b77433cd2a8218', 'admin', '2017-09-26 11:23:26', '2017-09-26 11:47:19', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(181, 'fbe7f209fd26d4a1a5048b1040e25826', 'admin', '2017-09-26 11:47:25', '2017-09-26 11:49:29', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(182, 'f5927ec5f581ccd53ae05d579f8a5f96', 'admin', '2017-09-27 00:00:50', '2017-09-27 00:05:49', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(183, '43e811dbf6abb3369ac5bd3a49a4b8a3', 'admin', '2017-09-27 00:15:09', '2017-09-27 01:06:35', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(184, '1a9900344d181471d5badddbcf4d6a7a', 'admin', '2017-09-27 01:08:55', '2017-09-27 06:28:36', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(185, '4c599281295cc559efa6030241b7b0b2', 'admin', '2017-09-27 23:16:17', '2017-09-28 00:57:45', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(186, 'ac7fa6e5d1b77594e7f8fbec4d23dfd7', 'admin', '2017-09-28 00:58:28', '2017-09-28 01:30:07', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(187, '1a7eb0964bbd48504f863e38c60e0886', 'admin', '2017-09-28 01:32:43', '2017-09-28 08:35:46', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(188, 'ba796916263a56c9a7945e089596ea1c', 'admin', '2017-09-28 10:15:33', '2017-09-28 10:58:22', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(189, 'dd0cbc9cdccc164315e9e8abba7c98af', 'admin', '2017-09-28 13:06:13', '2017-09-28 16:18:28', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(190, '47177394870231c5a1f60c66d4a24edf', 'admin', '2017-09-29 00:44:16', '2017-09-29 00:44:49', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(191, 'b0f716c6eee44ab80c8f4ec2a07f9bb3', 'admin', '2017-09-29 00:48:18', '2017-09-29 00:48:53', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(192, '19f4e4ea6fe4b03cf35262db9deba46d', 'admin', '2017-09-29 00:49:45', '2017-09-29 00:50:43', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(193, 'ccec744006d21682101cce70807d5738', 'admin', '2017-09-29 00:50:52', '2017-09-29 00:51:26', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(194, 'afdf44753b42732485da82dde73bb3c5', 'admin', '2017-09-29 00:54:01', '2017-09-29 10:09:53', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(195, 'c60d1a157cd490d8e4e9bd2a567d19d9', 'admin', '2017-09-29 10:26:18', '2017-09-29 11:44:04', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(196, '6e284c2d10802f38f7b9c6260a49ed63', 'admin', '2017-09-29 11:44:04', '2017-09-29 12:54:11', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 1),
(197, 'dbccd08ea2066f433c20001c8968cae9', 'admin', '2017-09-29 15:37:16', '2017-09-29 15:37:49', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 1),
(198, '14b304e3740dc5d6aac8b36a0ba0014f', 'admin', '2017-09-29 15:38:28', '2017-09-29 15:39:42', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 1),
(199, '7422837f6b5c968f7af717e2538ceaa5', 'admin', '2017-09-29 15:39:48', '2017-09-29 15:40:22', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 1),
(200, '9510dbc3ff55ad83967c277099b34195', 'admin', '2017-09-29 15:40:44', '2017-09-29 15:41:21', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 1),
(201, '9093a4d45df034af3b5778b509ca152b', 'admin', '2017-09-29 15:42:25', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 1);

-- --------------------------------------------------------

--
-- Structure de la table `ste_bank`
--

CREATE TABLE IF NOT EXISTS `ste_bank` (
  `id` int(11) DEFAULT NULL,
  `bank` varchar(30) DEFAULT NULL,
  `num_compte` varchar(30) DEFAULT NULL,
  `devise` int(3) DEFAULT NULL,
  `solde` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `ste_info`
--

CREATE TABLE IF NOT EXISTS `ste_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ste_name` varchar(45) DEFAULT NULL,
  `ste_bp` varchar(5) DEFAULT NULL,
  `ste_adresse` varchar(90) DEFAULT NULL,
  `ste_tel` varchar(15) DEFAULT NULL,
  `ste_fax` varchar(15) DEFAULT NULL,
  `ste_email` varchar(45) DEFAULT NULL,
  `ste_if` varchar(15) DEFAULT NULL,
  `ste_rc` varchar(15) DEFAULT NULL,
  `ste_website` varchar(45) DEFAULT NULL,
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `ste_info`
--

INSERT INTO `ste_info` (`id`, `ste_name`, `ste_bp`, `ste_adresse`, `ste_tel`, `ste_fax`, `ste_email`, `ste_if`, `ste_rc`, `ste_website`) VALUES
(1, 'nom', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `sys_log`
--

CREATE TABLE IF NOT EXISTS `sys_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID Systeme',
  `message` varchar(400) DEFAULT NULL COMMENT 'Message',
  `type_log` varchar(10) DEFAULT NULL COMMENT 'Type of log (insert_update_delete_login_show)',
  `table_use` varchar(30) DEFAULT NULL COMMENT 'Table of ligne',
  `idm` int(11) DEFAULT NULL COMMENT 'ID of line',
  `user_exec` varchar(25) DEFAULT NULL COMMENT 'User Execute',
  `datlog` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'Date loggining',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Contenu de la table `sys_log`
--

INSERT INTO `sys_log` (`id`, `message`, `type_log`, `table_use`, `idm`, `user_exec`, `datlog`) VALUES
(1, 'Création utlisateur', 'Insert', 'users_sys', 19, 'admin', '2017-08-28 01:44:32'),
(2, 'Modification utlisateur', 'Update', 'users_sys', 1, 'admin', '2017-08-28 19:34:49'),
(3, 'Modification utlisateur', 'Update', 'users_sys', 1, 'admin', '2017-08-29 22:01:10'),
(4, 'Modification utlisateur', 'Update', 'users_sys', 1, 'admin', '2017-09-03 10:55:41'),
(5, 'Modification utlisateur', 'Update', 'users_sys', 1, 'admin', '2017-09-03 10:58:19'),
(6, 'Modification utlisateur', 'Update', 'users_sys', 2, 'admin', '2017-09-03 11:33:12'),
(7, 'Modification utlisateur', 'Update', 'users_sys', 2, 'admin', '2017-09-03 11:38:46'),
(8, 'Modification utlisateur', 'Update', 'users_sys', 2, 'admin', '2017-09-03 12:21:50'),
(9, 'Création utlisateur', 'Insert', 'users_sys', 20, 'admin', '2017-09-03 13:34:19'),
(10, 'Modification utlisateur', 'Update', 'users_sys', 20, 'admin', '2017-09-03 13:36:03');

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
(5, 'clients', 'clients');

-- --------------------------------------------------------

--
-- Structure de la table `task`
--

CREATE TABLE IF NOT EXISTS `task` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID Sys',
  `app` varchar(25) CHARACTER SET utf8 NOT NULL COMMENT 'App Name',
  `modul` int(40) DEFAULT NULL COMMENT 'Module id ',
  `file` varchar(30) NOT NULL COMMENT 'File of App',
  `rep` varchar(60) DEFAULT NULL COMMENT 'Folder of App',
  `session` int(11) NOT NULL COMMENT 'Need session =1 else 0',
  `dscrip` varchar(50) DEFAULT NULL COMMENT 'Description',
  `sbclass` varchar(25) DEFAULT NULL COMMENT 'Class icon',
  `ajax` int(11) DEFAULT '0' COMMENT 'is Ajax App',
  `app_sys` int(11) NOT NULL DEFAULT '0' COMMENT 'Application Système',
  `etat` int(11) DEFAULT '0' COMMENT 'Etat de ligne',
  `type_view` varchar(10) DEFAULT NULL COMMENT 'Type affichage Application',
  `services` varchar(20) DEFAULT NULL COMMENT 'Les Services',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE` (`app`),
  KEY `task_ibfk_1` (`modul`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Table Task of modules' AUTO_INCREMENT=512 ;

--
-- Contenu de la table `task`
--

INSERT INTO `task` (`id`, `app`, `modul`, `file`, `rep`, `session`, `dscrip`, `sbclass`, `ajax`, `app_sys`, `etat`, `type_view`, `services`) VALUES
(1, 'login', 1, 'login', 'login', 0, 'Connexion', NULL, 0, 1, 0, '', '[-1-]'),
(2, 'forgot', 1, 'forgot', 'login', 0, 'Mot de passe oublie', NULL, 0, 1, 0, '', '[-1-]'),
(3, 'tdb', 1, 'tdb', 'tdb', 1, 'Tableau de bord', NULL, 0, 1, 0, '', '[-1-]'),
(4, 'logout', 1, 'logout', 'login', 1, 'Deconnexion', NULL, 0, 1, 0, '', '[-1-]'),
(5, 'check', 1, 'check', 'ajax', 1, 'Check All', NULL, 1, 1, 0, '', '[-1-]'),
(6, 'shopdf', 1, 'shopdf', 'ajax', 1, 'PDF Viewer', NULL, 1, 1, 0, '', '[-1-]'),
(7, 'tooltip', 1, 'tooltip', 'ajax', 1, 'annotation', NULL, 1, 1, 0, '', '[-1-]'),
(8, 'dbd', 1, 'dbd', 'tdb', 1, 'Tableau de bord', NULL, 0, 1, 0, '', '[-1-]'),
(9, 'upload', 1, 'upload', 'ajax', 1, 'Uploder', NULL, 1, 1, 0, '', '[-1-]'),
(10, 'add_pic', 1, 'add_pic', 'ajax', 1, 'Add Pic Gallery', NULL, 1, 1, 0, '', '[-1-]'),
(11, 'loadenselect', 1, 'loadenselect', 'ajax', 0, 'remplir select', NULL, 1, 1, 0, '', '[-1-]'),
(12, 'errorjs', 1, 'errorjs', 'ajax', 0, 'Erreur JS', NULL, 0, 1, 0, '', '[-1-]'),
(13, 'recovery', 1, 'recovery', 'login', 0, 'Récuperation Motde passe', NULL, 0, 1, 0, '', '[-1-]'),
(14, 'user', 2, 'user', 'users', 1, 'Utilisateurs', 'users', 1, 0, 0, 'list', '[-1-]'),
(15, 'adduser', 2, 'adduser', 'users', 1, 'Ajouter Utilisateur', NULL, 1, 0, 0, 'form', '[-1-]'),
(16, 'edituser', 2, 'edituser', 'users', 1, 'Editer compte utilisateur', NULL, 1, 0, 0, 'form', '[-1-]'),
(17, 'rules', 2, 'rules', 'users', 1, 'Permission Utilisateur', 'users', 1, 0, 0, 'form', '[-1-]'),
(18, 'delete_user', 2, 'delete_user', 'users', 1, 'Supprimer utilisateur', 'trash', 1, 0, 0, 'exec', 'null'),
(19, 'compte', 2, 'compte', 'users', 1, 'Details profile utilisateur', NULL, 1, 1, 0, 'profil', '[-1-]'),
(20, 'activeuser', 2, 'activeuser', 'users', 1, 'Activer utilisateur', 'unlock', 1, 0, 0, 'exec', '[-1-]'),
(21, 'modul', 3, 'modul', 'modul_mgr', 1, 'Modules', 'cubes', 1, 0, 0, 'list', '[-1-]'),
(22, 'addmodul', 3, 'addmodul', 'modul_mgr', 1, 'Ajouter Module', '', 1, 1, 0, 'form', '[-1-]'),
(23, 'editmodul', 3, 'editmodul', 'modul_mgr', 1, 'Editer Module', '', 1, 1, 0, 'form', '[-1-]'),
(24, 'task', 3, 'task', 'modul_mgr', 1, 'Liste Application Associes', '', 1, 1, 0, 'list', '[-1-]'),
(25, 'addtask', 3, 'addtask', 'modul_mgr', 1, 'Ajouter Module Task', '', 1, 1, 0, 'form', '[-1-]'),
(26, 'edittask', 3, 'edittask', 'modul_mgr', 1, 'Editer Module Task', '', 1, 1, 0, 'form', '[-1-]'),
(27, 'taskaction', 3, 'taskaction', 'modul_mgr', 1, 'Liste Task Action', '0', 1, 0, 0, 'list', '[-1-]'),
(28, 'addtaskaction', 3, 'addtaskaction', 'modul_mgr', 1, 'Ajouter Action Task', '0', 1, 0, 0, 'form', '[-1-3-]'),
(29, 'deletetask', 3, 'deletetask', 'modul_mgr', 1, 'Supprimer Application', NULL, 1, 0, 0, 'exec', '[-1-]'),
(33, 'importmodul', 3, 'importmodul', 'modul_mgr', 1, 'Importer des modules', NULL, 1, 0, 0, 'exec', '[-1-]'),
(34, 'services', 4, 'services', 'users/settings/services', 1, 'Services', 'briefcase', 1, 0, 0, 'list', '[-1-]'),
(35, 'addservice', 4, 'addservice', 'users/settings/services', 1, 'Ajouter Service', NULL, 1, 0, 0, 'form', '[-1-2-]'),
(36, 'editservice', 4, 'editservice', 'users/settings/services', 1, 'Modifier Service', NULL, 1, 0, 0, 'form', '[-1-2-]'),
(37, 'validservice', 4, 'validservice', 'users/settings/services', 1, 'Valider Service', NULL, 1, 0, 0, 'exec', '[-1-2-]'),
(38, 'deletservice', 4, 'deletservice', 'users/settings/services', 1, 'Supprimer Service', NULL, 1, 0, 0, 'exec', '[-1-2-]'),
(43, 'autocomplet', 1, 'autocomplet', 'ajax', 1, 'Auto complete Input', NULL, 1, 1, 0, '', '[-1-]'),
(53, 'addtest', 1, 'addtest', 'tdb', 1, 'Test galerry', NULL, 1, 1, 0, 'form', '[-1-2-17-]'),
(55, 'addmodulsetting', 3, 'addmodulsetting', 'modul_mgr', 1, 'Ajouter Module paramétrage', NULL, 1, 0, 0, 'form', '[-1-]'),
(62, 'editmodulsetting', 3, 'editmodulsetting', 'modul_mgr', 1, 'Editer Module paramètrage', 'na', 1, 0, 0, 'form', '[-1-]'),
(66, 'setting', 1, 'setting', 'tdb', 1, 'Paramètrages', NULL, 1, 1, 0, '', '[-1-]'),
(79, 'addetatrule', 3, 'addetatrule', 'modul_mgr', 1, 'Ajouter Autorisation Etat', NULL, 1, 0, 0, 'form', '[-1-]'),
(107, 'archiv_user', 2, 'archiv_user', 'users', 1, 'Désactiver l''utilisateur', 'ban', 1, 0, 0, 'exec', '[-1-]'),
(108, 'edittaskaction', 3, 'edittaskaction', 'modul_mgr', 1, 'Editer Task Action', 'pen', 1, 0, 0, 'form', '[-1-]'),
(161, 'changepass', 2, 'changepass', 'users', 1, 'Changer le mot de passe', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(162, 'history', 2, 'history', 'users', 1, 'History', 'users', 1, 0, 0, 'list', '[-1-]'),
(163, 'activities', 2, 'activities', 'users', 1, 'Activities', 'users', 1, 0, 0, 'list', '[-1-]'),
(167, 'update_module', 3, 'update_module', 'modul_mgr', 1, 'MAJ Module', 'pencil-square-o', 1, 0, 0, 'exec', '[-1-]'),
(319, 'notif', 1, 'notifier', 'ajax', 1, 'Notifier', NULL, 1, 1, 0, '', '[-1-]'),
(330, 'produits', 7, 'produits', 'produits', 1, 'Gestion des produits', 'cogs', 1, 0, 0, 'list', '[-1-]'),
(331, 'addproduit', 7, 'addproduit', 'produits', 1, 'Ajouter produit', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(332, 'editproduit', 7, 'editproduit', 'produits', 1, 'Modifier produit', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(333, 'detailproduit', 7, 'detailproduit', 'produits', 1, 'Detail produit', 'cogs', 1, 0, 0, 'profil', '[-1-]'),
(334, 'validproduit', 7, 'validproduit', 'produits', 1, 'Valider produit', 'cogs', 1, 0, 0, 'exec', '[-1-]'),
(335, 'deleteproduit', 7, 'deleteproduit', 'produits', 1, 'Supprimer produit', 'cogs', 1, 0, 0, 'exec', '[-1-]'),
(362, 'categorie_client', 13, 'categorie_client', 'clients/settings/categorie_client', 1, 'Gestion Catégorie Client', 'certificate', 1, 0, 0, 'list', '[-1-]'),
(363, 'addcategorie_client', 13, 'addcategorie_client', 'clients/settings/categorie_client', 1, 'Ajouter Catégorie Client', 'certificate', 1, 0, 0, 'form', '[-1-]'),
(364, 'editcategorie_client', 13, 'editcategorie_client', 'clients/settings/categorie_client', 1, 'Editer Catégorie Client', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(365, 'deletecategorie_client', 13, 'deletecategorie_client', 'clients/settings/categorie_client', 1, 'Supprimer Catégorie Client', 'trash', 1, 0, 0, 'exec', '[-1-]'),
(366, 'validcategorie_client', 13, 'validcategorie_client', 'clients/settings/categorie_client', 1, 'Valider Catégorie Client', 'cloud', 1, 0, 0, 'exec', '[-1-]'),
(367, 'clients', 14, 'clients', 'clients', 1, 'Gestion Clients', 'users', 1, 0, 0, 'list', '[-1-]'),
(368, 'addclient', 14, 'addclient', 'clients', 1, 'Ajouter Client', 'users', 1, 0, 0, 'form', '[-1-]'),
(369, 'editclient', 14, 'editclient', 'clients', 1, 'Editer Client', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(370, 'deleteclient', 14, 'deleteclient', 'clients', 1, 'Supprimer Client', 'trash', 1, 0, 0, 'exec', '[-1-]'),
(371, 'validclient', 14, 'validclient', 'clients', 1, 'Valider Client', 'lock', 1, 0, 0, 'exec', '[-1-]'),
(372, 'detailsclient', 14, 'detailsclient', 'clients', 1, 'Détails Client', 'eye', 1, 0, 0, 'profil', '[-1-]'),
(378, 'dupliqtaskaction', 3, 'dupliqtaskaction', 'modul_mgr', 1, 'Dupliquer Task Action', 'copy', 1, 0, 0, 'form', '[-1-2-]'),
(384, 'vente', 20, 'vente', 'vente/main', 1, 'Gestion Vente', 'money', 1, 0, 0, 'list', '[-1-2-]'),
(385, 'devis', 21, 'devis', 'vente/submodul/devis', 1, 'Gestion Devis', 'paper-plane-o', 1, 0, 0, 'list', '[-1-2-]'),
(388, 'adddevis', 21, 'adddevis', 'vente/submodul/devis', 1, 'Ajouter Devis', 'plus', 1, 0, 0, 'form', '[-1-2-]'),
(389, 'add_detaildevis', 21, 'add_detaildevis', 'vente/submodul/devis', 1, 'Ajouter détail devis', 'plus', 1, 0, 0, 'form', '[-1-2-]'),
(390, 'editdevis', 21, 'editdevis', 'vente/submodul/devis', 1, 'Modifier Devis', 'pen', 1, 0, 0, 'form', '[-1-2-]'),
(391, 'deletedevis', 21, 'deletedevis', 'vente/submodul/devis', 1, 'Supprimer Devis', 'trash red', 1, 0, 0, 'exec', '[-1-2-]'),
(392, 'edit_detaildevis', 21, 'edit_detaildevis', 'vente/submodul/devis', 1, 'Modifier détail Devis', 'pen', 1, 0, 0, 'form', '[-1-2-]'),
(393, 'viewdevis', 21, 'viewdevis', 'vente/submodul/devis', 1, 'Afficher détail devis', 'eye', 1, 0, 0, 'profil', '[-1-2-]'),
(394, 'validdevis', 21, 'validdevis', 'vente/submodul/devis', 1, 'Valider Devis', NULL, 1, 0, 0, 'exec', '[-1-2-]'),
(408, 'pays', 24, 'pays', 'Systeme/settings/pays', 1, 'Gestion Pays', 'flag', 1, 0, 0, 'list', '[-1-]'),
(409, 'addpays', 24, 'addpays', 'Systeme/settings/pays', 1, 'Ajouter Pays', 'flag', 1, 0, 0, 'form', '[-1-]'),
(410, 'editpays', 24, 'editpays', 'Systeme/settings/pays', 1, 'Editer Pays', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(411, 'deletepays', 24, 'deletepays', 'Systeme/settings/pays', 1, 'Supprimer Pays', 'trash', 1, 0, 0, 'exec', '[-1-]'),
(412, 'validpays', 24, 'validpays', 'Systeme/settings/pays', 1, 'Valider Pays', 'lock', 1, 0, 0, 'exec', '[-1-]'),
(423, 'info_ste', 27, 'info_ste', 'Systeme/settings/info_ste', 1, 'Information société', 'credit-card', 1, 0, 0, 'list', '[-1-3-]'),
(424, 'contrats_fournisseurs', 28, 'contrats_fournisseurs', 'contrats_fournisseurs/main', 1, 'Gestion Contrats Fournisseurs', 'book', 1, 0, 0, 'list', '[-1-]'),
(425, 'addcontrat_frn', 28, 'addcontrat_frn', 'contrats_fournisseurs/main', 1, 'Ajouter Contrat', 'book', 1, 0, 0, 'form', '[-1-]'),
(426, 'editcontrat_frn', 28, 'editcontrat_frn', 'contrats_fournisseurs/main', 1, 'Editer Contrat', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(427, 'deletecontrat_frn', 28, 'deletecontrat_frn', 'contrats_fournisseurs/main', 1, 'Supprimer Contrat', 'trash', 1, 0, 0, 'exec', '[-1-]'),
(428, 'validcontrat_frn', 28, 'validcontrat_frn', 'contrats_fournisseurs/main', 1, 'Valider Contrat', 'lock', 1, 0, 0, 'exec', '[-1-]'),
(429, 'detailscontrat_frn', 28, 'detailscontrat_frn', 'contrats_fournisseurs/main', 1, 'Détails Contrat', 'eye', 1, 0, 0, 'profil', '[-1-]'),
(430, 'fournisseurs', 29, 'fournisseurs', 'contrats_fournisseurs/submodul/fournisseurs', 1, 'Gestion Fournisseurs', 'users', 1, 0, 0, 'list', '[-1-]'),
(431, 'addfournisseur', 29, 'addfournisseur', 'contrats_fournisseurs/submodul/fournisseurs', 1, 'Ajouter Fournisseur', 'user', 1, 0, 0, 'form', '[-1-]'),
(432, 'editfournisseur', 29, 'editfournisseur', 'contrats_fournisseurs/submodul/fournisseurs', 1, 'Editer Fournisseur', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(433, 'deletefournisseur', 29, 'deletefournisseur', 'contrats_fournisseurs/submodul/fournisseurs', 1, 'Supprimer Fournisseur', 'trash', 1, 0, 0, 'exec', '[-1-]'),
(434, 'validfournisseur', 29, 'validfournisseur', 'contrats_fournisseurs/submodul/fournisseurs', 1, 'Valider Fournisseur', 'lock', 1, 0, 0, 'exec', '[-1-]'),
(435, 'detailsfournisseur', 29, 'detailsfournisseur', 'contrats_fournisseurs/submodul/fournisseurs', 1, 'Détails Fournisseur', 'eye', 1, 0, 0, 'profil', '[-1-]'),
(436, 'villes', 30, 'villes', 'Systeme/settings/villes', 1, 'Gestion Villes', 'building', 1, 0, 0, 'list', '[-1-]'),
(437, 'addville', 30, 'addville', 'Systeme/settings/villes', 1, 'Ajouter ville', NULL, 1, 0, 0, 'form', '[-1-]'),
(438, 'editville', 30, 'editville', 'Systeme/settings/villes', 1, 'Editer Ville', NULL, 1, 0, 0, 'form', '[-1-]'),
(439, 'deleteville', 30, 'deleteville', 'Systeme/settings/villes', 1, 'Supprimer Ville', NULL, 1, 0, 0, 'exec', '[-1-]'),
(440, 'validville', 30, 'validville', 'Systeme/settings/villes', 1, 'Valider Ville', 'lock', 1, 0, 0, 'exec', '[-1-]'),
(441, 'categories_produits', 31, 'categories_produits', 'produits/settings/categories_produits', 1, 'Gestion des catégories de produits', 'cogs', 1, 0, 0, 'list', '[-1-]'),
(442, 'addcategorie_produit', 31, 'addcategorie_produit', 'produits/settings/categories_produits', 1, 'Ajouter une catégorie', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(443, 'editecategorie_produit', 31, 'editecategorie_produit', 'produits/settings/categories_produits', 1, 'Modifier une catégorie', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(444, 'validcategorie_produit', 31, 'validcategorie_produit', 'produits/settings/categories_produits', 1, 'Valider une catégorie', 'cogs', 1, 0, 0, 'exec', '[-1-]'),
(445, 'deletecategorie_produit', 31, 'deletecategorie_produit', 'produits/settings/categories_produits', 1, 'Supprimer une catégorie', 'cogs', 1, 0, 0, 'exec', '[-1-]'),
(446, 'types_produits', 32, 'types_produits', 'produits/settings/types_produits', 1, 'Gestion des types de produits', 'cogs', 1, 0, 0, 'list', '[-1-]'),
(447, 'addtype_produit', 32, 'addtype_produit', 'produits/settings/types_produits', 1, 'Ajouter un type', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(448, 'edittype_produit', 32, 'edittype_produit', 'produits/settings/types_produits', 1, 'Modifier type', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(449, 'validtype_produit', 32, 'validtype_produit', 'produits/settings/types_produits', 1, 'Valider type', 'cogs', 1, 0, 0, 'exec', '[-1-]'),
(450, 'deletetype_produit', 32, 'deletetype_produit', 'produits/settings/types_produits', 1, 'Supprimer type', 'cogs', 1, 0, 0, 'exec', '[-1-]'),
(451, 'unites_vente', 33, 'unites_vente', 'produits/settings/unites_vente', 1, 'Gestion des unités de vente', 'cogs', 1, 0, 0, 'list', '[-1-]'),
(452, 'addunite_vente', 33, 'addunite_vente', 'produits/settings/unites_vente', 1, 'ajouter une unité', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(453, 'editunite_vente', 33, 'editunite_vente', 'produits/settings/unites_vente', 1, 'Modifier une unité', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(454, 'validunite_vente', 33, 'validunite_vente', 'produits/settings/unites_vente', 1, 'Valider une unité', 'cogs', 1, 0, 0, 'exec', '[-1-]'),
(455, 'deleteunite_vente', 33, 'deleteunite_vente', 'produits/settings/unites_vente', 1, 'Supprimer une unité', 'cogs', 1, 0, 0, 'exec', '[-1-]'),
(456, 'contrats', 34, 'contrats', 'vente/submodul/contrats', 1, 'Gestion des contrats', 'cloud', 1, 0, 0, 'list', '[-1-]'),
(457, 'addcontrat', 34, 'addcontrat', 'vente/submodul/contrats', 1, 'Ajouter contrat', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(458, 'editcontrat', 34, 'editcontrat', 'vente/submodul/contrats', 1, 'Modifier contrat', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(459, 'deletecontrat', 34, 'deletecontrat', 'vente/submodul/contrats', 1, 'Supprimer contrat', 'cogs', 1, 0, 0, 'exec', '[-1-]'),
(460, 'validcontrat', 34, 'validcontrat', 'vente/submodul/contrats', 1, 'Valider contrat', 'cogs', 1, 0, 0, 'exec', '[-1-]'),
(461, 'detailcontrat', 34, 'detailcontrat', 'vente/submodul/contrats', 1, 'Détail contrat', 'cogs', 1, 0, 0, 'profil', '[-1-]'),
(462, 'addecheance_contrat', 34, 'addecheance_contrat', 'vente/submodul/contrats', 1, 'Ajouter échéance contrat', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(463, 'editecheance_contrat', 34, 'editecheance_contrat', 'vente/submodul/contrats', 1, 'Modifier échéance contrat', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(464, 'regions', 35, 'regions', 'Systeme/settings/regions', 1, 'Gestion des régions', 'cogs', 1, 0, 0, 'list', '[-1-]'),
(465, 'addregion', 35, 'addregion', 'Systeme/settings/regions', 1, 'Ajouter région', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(466, 'editregion', 35, 'editregion', 'Systeme/settings/regions', 1, 'Modifier région', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(467, 'validregion', 35, 'validregion', 'Systeme/settings/regions', 1, 'Valider région', 'cogs', 1, 0, 0, 'exec', '[-1-]'),
(468, 'deleteregion', 35, 'deleteregion', 'Systeme/settings/regions', 1, 'Supprimer région', 'cogs', 1, 0, 0, 'exec', '[-1-]'),
(469, 'departements', 36, 'departements', 'Systeme/settings/departements', 1, 'Gestion Départements', 'bullhorn', 1, 0, 0, 'list', '[-1-]'),
(470, 'adddepartement', 36, 'adddepartement', 'Systeme/settings/departements', 1, 'Ajouter Département', 'bullhorn', 1, 0, 0, 'form', '[-1-]'),
(471, 'editdepartement', 36, 'editdepartement', 'Systeme/settings/departements', 1, 'Editer Département', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(472, 'deletedepartement', 36, 'deletedepartement', 'Systeme/settings/departements', 1, 'Supprimer Département', 'trash', 1, 0, 0, 'exec', '[-1-]'),
(473, 'validdepartement', 36, 'validdepartement', 'Systeme/settings/departements', 1, 'Valider Département', 'lock', 1, 0, 0, 'exec', '[-1-]'),
(474, 'type_echeance', 37, 'type_echeance', 'contrats/settings/type_echeance', 1, 'Gestion Type Echeance', 'info-circle', 1, 0, 0, 'list', '[-1-]'),
(475, 'addtype_echeance', 37, 'addtype_echeance', 'contrats/settings/type_echeance', 1, 'Ajouter Type Echéance', 'info-circle', 1, 0, 0, 'form', '[-1-]'),
(476, 'edittype_echeance', 37, 'edittype_echeance', 'contrats/settings/type_echeance', 1, 'Editer Type Echéance', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(477, 'deletetype_echeance', 37, 'deletetype_echeance', 'contrats/settings/type_echeance', 1, 'Supprimer Type Echéance', 'trash', 1, 0, 0, 'exec', '[-1-]'),
(478, 'validtype_echeance', 37, 'validtype_echeance', 'contrats/settings/type_echeance', 1, 'Valider Type Echéance', 'lock', 1, 0, 0, 'exec', '[-1-]'),
(505, 'proforma', 43, 'proforma', 'vente/submodul/proforma', 1, 'Gestion Proforma', 'book', 1, 0, 0, 'list', '[-1-2-3-5-4-]'),
(506, 'addproforma', 43, 'addproforma', 'vente/submodul/proforma', 1, 'Ajouter pro-forma', 'plus', 1, 0, 0, 'form', '[-1-2-3-5-4-]'),
(507, 'editproforma', 43, 'editproforma', 'vente/submodul/proforma', 1, 'Editer proforma', 'pen', 1, 0, 0, 'form', '[-1-2-3-5-4-]'),
(508, 'add_detailproforma', 43, 'add_detailproforma', 'vente/submodul/proforma', 1, 'Ajouter détail proforma', 'plus', 1, 0, 0, 'form', '[-1-2-3-5-4-]'),
(509, 'validproforma', 43, 'validproforma', 'vente/submodul/proforma', 1, 'valider Proforma', 'check', 1, 0, 0, 'exec', '[-1-2-3-5-4-]'),
(510, 'viewproforma', 43, 'viewproforma', 'vente/submodul/proforma', 1, 'Détail Pro-forma', 'eye', 1, 0, 0, 'profil', '[-1-2-3-5-4-]'),
(511, 'edit_detailproforma', 43, 'edit_detailproforma', 'vente/submodul/proforma', 1, 'Editer détail proforma', 'pen', 1, 0, 0, 'form', '[-1-2-3-5-4-]');

-- --------------------------------------------------------

--
-- Structure de la table `task_action`
--

CREATE TABLE IF NOT EXISTS `task_action` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID App Action (AI)',
  `appid` int(11) NOT NULL COMMENT 'ID Application',
  `idf` varchar(32) DEFAULT NULL COMMENT 'Idf for Mgt rules',
  `descrip` varchar(75) NOT NULL COMMENT 'Description',
  `mode_exec` varchar(10) DEFAULT NULL COMMENT 'Mode execution',
  `app` varchar(25) DEFAULT NULL COMMENT 'Appli to call',
  `class` varchar(30) DEFAULT NULL COMMENT 'Class menu',
  `code` varchar(250) NOT NULL COMMENT 'code html',
  `type` int(11) NOT NULL DEFAULT '0' COMMENT 'action = 0 task=1',
  `service` varchar(30) DEFAULT NULL COMMENT 'User Service ID',
  `etat_line` int(11) NOT NULL COMMENT 'Etat de la ligne quand si action liste',
  `notif` int(1) DEFAULT '0' COMMENT 'Notification ligne',
  `etat_desc` varchar(200) DEFAULT NULL COMMENT 'Descrp ETAT line',
  `message_class` varchar(15) DEFAULT NULL COMMENT 'Message class',
  `message_etat` varchar(250) DEFAULT 'nA' COMMENT 'Message Etat',
  `creusr` varchar(25) DEFAULT NULL COMMENT 'Add by',
  `credat` datetime DEFAULT NULL COMMENT 'add date',
  `updusr` varchar(25) DEFAULT NULL COMMENT 'update by',
  `upddat` datetime DEFAULT NULL COMMENT 'update by',
  PRIMARY KEY (`id`),
  KEY `task_action_task` (`appid`),
  KEY `task_action_descrip` (`descrip`),
  KEY `task_action_app` (`app`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Table of Task_Action and Permission of Task' AUTO_INCREMENT=784 ;

--
-- Contenu de la table `task_action`
--

INSERT INTO `task_action` (`id`, `appid`, `idf`, `descrip`, `mode_exec`, `app`, `class`, `code`, `type`, `service`, `etat_line`, `notif`, `etat_desc`, `message_class`, `message_etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(1, 14, '56de23d30d6c54297c8d9772cd3c7f57', 'Utilisateurs', NULL, 'user', NULL, '', 1, '-1-2-3-', 1, 0, 'Actif', 'success', '<span class="label label-sm label-success">Actif</span>', NULL, NULL, NULL, NULL),
(2, 15, 'df91a8e6f8ee2cde64495fc0cc7d6c6f', 'Ajouter Utilisateurs', NULL, 'adduser', NULL, '', 1, '-1-2-', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 14, 'e656756fb7b39a4e6ddcabca75ff2970', 'Editer Utilisateur', 'this_url', 'user', NULL, '<li><a href="#" class="this_url" redi="user" data="%id%" rel="edituser" >\r\n     <i class="ace-icon fa fa-pencil bigger-100"></i> Editer compte\r\n   </a></li>', 0, '-1-2-3-', 1, 0, 'Actif', 'success', '<span class="label label-sm label-success">Actif</span>', NULL, NULL, NULL, NULL),
(4, 16, '2bb46b52eab9eecbdbba35605da07234', 'Editer Utilisateurs', NULL, 'edituser', NULL, '', 1, '-1-2-', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 17, '3f59a1326df27378304e142ab3bec090', 'Permission', NULL, 'rules', NULL, '', 1, '-1-2-', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 14, 'c073a277957ca1b9f318ac3902555708', 'Permissions', 'this_url', 'user', NULL, '<li><a href="#" class="this_url" redi="user" data="%id%" rel="rules"  >\n     <i class="ace-icon fa fa-key bigger-100"></i> Permission compte\n    </a></li>', 0, '-1-2-3-', 1, 0, 'Actif', 'success', '<span class="label label-sm label-success">Actif</span>', NULL, NULL, NULL, NULL),
(7, 18, 'b919571c88d036f8889742a81a4f41fd', 'Supprimer utilisateur', NULL, 'delete_user', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(8, 14, 'c51499ddf7007787c4434661c658bbd1', 'Désactiver compte', 'this_exec', 'user', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="activeuser" ><i class="ace-icon fa fa-lock bigger-100"></i> Désactiver utilisateur</a></li>', 0, '-1-3-', 1, 0, 'Actif', 'success', '<span class="label label-sm label-success">Actif</span>', NULL, NULL, NULL, NULL),
(9, 21, 'b8e62907d367fb44d644a5189cd07f42', 'Modules', NULL, 'modul', NULL, '', 1, 'null', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(10, 22, '44bd5341b0ab41ced21db8b3e92cf5aa', 'Ajouter un Modul', NULL, 'addmodul', NULL, '', 1, '-1-2-', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(11, 21, '05ce9e55686161d99e0714bb86243e5b', 'Editer Module', 'this_url', 'modul', NULL, '<li><a href="#" class="this_url" data="%id%" rel="editmodul" >\r\n      <i class="ace-icon fa fa-pencil bigger-100"></i> Editer Module\r\n    </a></li>', 0, '-1-2-', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(12, 21, '819cf9c18a44cb80771a066768d585f2', 'Exporter Module', NULL, 'modul', NULL, '<li><a href="#" class="export_mod" data="%id%&export=1&mod=%id%" rel="modul" item="%id%" >\r\n      <i class="ace-icon fa fa-download bigger-100"></i> Exporter Module\r\n    </a></li>', 0, '-1-2-', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(13, 21, 'd2fc3ee15cee5208a8b9c70f1e53c196', 'Liste task modul', 'this_url', 'modul', NULL, '<li><a href="#" class="this_url" data="%id%" rel="task" >\r\n     <i class="ace-icon fa fa-external-link bigger-100"></i>Application associes\r\n    </a></li>', 0, '-1-2-', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(14, 25, '1c452aff8f1551b3574e15b74147ea56', 'Ajouter Task Modul', NULL, 'addtask', NULL, '', 1, '-1-2-', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(15, 26, 'f085fe4610576987db963501297e4d91', 'Editer Task Modul', NULL, 'edittask', NULL, '', 1, '-1-2-', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(16, 24, '8653b156f1a4160a12e5a94b211e59a2', 'Liste Action Task', 'this_url', 'task', NULL, '<li><a href="#" class="this_url" data="%id%" rel="taskaction"  >\r\n     <i class="ace-icon fa fa-external-link bigger-100"></i>Application associes\r\n    </a></li>', 0, '-1-2-', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(18, 26, '38702c272a6f4d334c2f4c3684c8b163', 'Ajouter action modul', NULL, 'edittask', NULL, '', 1, '-1-2-', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(19, 28, '502460cd9327b46ee7af0a258ebf8c80', 'Ajouter Action Task', NULL, 'addtaskaction', NULL, '', 1, '[-1-3-]', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(20, 27, 'cbae1ebe850f6dd8841426c6fedf1785', 'Liste Action Task', NULL, 'taskaction', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(21, 29, '13c107211904d4a2e65dd65c60ec7272', 'Supprimer Application', NULL, 'deletetask', NULL, '', 1, 'null', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(22, 24, '86aced763bc02e1957a5c740fb37b4f7', 'Supprimer Application', 'this_exec', 'task', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="task"  ><i class="ace-icon fa fa-draft bigger-100"></i> Supprimer Application</a></li>', 0, '[-1-]', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(23, 14, '10096b6f54456bcfc85081523ee64cf6', 'Supprimer utilisateur', 'this_exec', 'user', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="delete_user" ><i class="ace-icon fa fa-trash red bigger-100"></i> Supprimer utilisateur</a></li>', 0, '-1-2-3-', 1, 0, 'Actif', 'success', '<span class="label label-sm label-success">Actif</span>', NULL, NULL, NULL, NULL),
(24, 33, '8c8acf9cf3790b16b1fae26823f45eab', 'Importer des modules', NULL, 'importmodul', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(25, 14, 'a0999cbed820aff775adf27276ee54a4', 'Editer Utilisateur', 'this_url', 'user', NULL, '<li><a href="#" class="this_url" data="%id%" rel="edituser" ><i class="ace-icon fa fa-users bigger-100"></i> Editer compte</a></li>', 0, '-1-2-3-', 0, 0, 'Attente Validation', 'danger', '<span class="label label-sm label-danger">Attente Validation</span>', NULL, NULL, NULL, NULL),
(26, 20, 'f988a608f35a0bc551cb038b1706d207', 'Activer utilisateur', NULL, 'activeuser', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(27, 14, '9aa6877656339ddff2478b20449a924b', 'Activer compte', 'this_exec', 'user', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="activeuser" ><i class="ace-icon fa fa-unlock bigger-100"></i> Activer utilisateur</a></li>', 0, '-1-2-3-', 0, 1, 'Attente Validation', 'danger', '<span class="label label-sm label-danger">Attente Validation</span>', NULL, NULL, NULL, NULL),
(28, 34, '83b9fa44466da4bcd7f8304185bfeac8', 'Services', NULL, 'services', NULL, '', 0, '[-1-2-4-]', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(29, 35, '55043bc4207521e3010e91d6267f5302', 'Ajouter Service', NULL, 'addservice', NULL, '', 1, '[-1-2-4-]', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(30, 36, '2fea3d893f6b6e81467ddd2a744e4a76', 'Modifier Service', NULL, 'editservice', NULL, '', 1, '[-1-2-4-]', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(31, 37, '1a0d5897d31b4d5e29022671c1112f59', 'Valider Service', NULL, 'validservice', NULL, '', 1, '[-1-2-4-]', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(32, 38, '42083d4e159baf7c2ace2bb977e2b0a0', 'Supprimer Service', NULL, 'deletservice', NULL, '', 1, '[-1-2-4-]', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(33, 34, '99aea4598ccc18d4c12ae091c8967d13', 'Valider Service', 'this_exec', 'services', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validservice" ><i class="ace-icon fa fa-unlock bigger-100"></i> Valider Service</a></li>', 0, '[-1-2-4-]', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(34, 34, 'bb66cf787052616ea3dd02b0b5199b26', 'Supprimer Service', 'this_exec', 'services', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="deletservice" ><i class="ace-icon fa fa-trash bigger-100"></i> Supprimer Service</a></li>', 0, '[-1-2-4-]', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(45, 19, '38f89764a26e39ce029cd3132c12b2a5', 'Compte utilisateur', NULL, 'compte', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(70, 55, '2f4518dab90b706e2f4acd737a0425d8', 'Ajouter Module paramétrage', NULL, 'addmodulsetting', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(74, 62, '8e0c0212d8337956ac2f4d6eb180d74b', 'Editer Module paramètrage', NULL, 'editmodulsetting', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(75, 21, 'ad75e6b877f20e3d6fc1789da4dcb3e6', 'Editer Module', 'this_url', 'modul', NULL, '<li><a href="#" class="this_url" data="%id%" rel="editmodulsetting"  ><i class="ace-icon fa fa-pencil bigger-100"></i> Editer Module</a></li>', 0, '[-1-]', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(77, 21, '064a9b0eff1006fd4f25cb4eaf894ca1', 'Liste task modul Setting', 'this_url', 'modul', NULL, '<li><a href="#" class="this_url" data="%id%" rel="task" >\r\n     <i class="ace-icon fa fa-external-link bigger-100"></i>Application associes\r\n    </a></li>', 0, '-1-2-', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(98, 79, 'fc54953b47b7fcb11cc14c0c2e2125f0', 'Ajouter Autorisation Etat', NULL, 'addetatrule', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(99, 24, 'f07352e32fe86da1483c6ab071b7e7a9', 'Ajout Affichage WF', 'this_url', 'task', NULL, '<li><a href="#" class="this_url" data="%id%" rel="addetatrule"  ><i class="ace-icon fa fa-eye bigger-100"></i> Ajout Affichage WF</a></li>', 0, '[-1-]', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(112, 14, 'f4c79bb797b92dfa826b51a44e3171af', 'Utilisateurs', NULL, 'user', NULL, '', 0, '-1-2-3-', 0, 1, 'Attente Validation', 'danger', '<span class="label label-sm label-danger">Attente Validation</span>', NULL, NULL, NULL, NULL),
(143, 14, 'd7f7afd70a297e5c239f6cf271138390', 'Utilisateur Archivé', NULL, 'user', NULL, 'dddd', 0, '-1-2-3-', 2, 0, 'Archivé', 'inverse', '<span class="label label-sm label-inverse">Archivé</span>', NULL, NULL, NULL, NULL),
(144, 34, '47c552dce8b761ae2e2a44387a93432b', 'Modifier Service Validé', 'this_url', 'services', NULL, '<li><a href="#" class="this_url" data="%id%" rel="editservice"  ><i class="ace-icon fa fa-pencil bigger-100"></i> Modifier Service Validé</a></li>', 0, '[-1-]', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(145, 107, 'b7b3a09fdd73a5b0a3e5ed8a2828f548', 'Désactiver l''utilisateur', NULL, 'archiv_user', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(146, 108, '966ec2dd83e6006c2d0ff1d1a5f12e33', 'Editer Task Action', NULL, 'edittaskaction', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(147, 27, 'e30471396f9b86ccdcc94943d80b679a', 'Editer Task Action', 'this_url', 'taskaction', NULL, '<li><a href="#" class="this_url" data="%id%" rel="edittaskaction"  ><i class="ace-icon fa fa-pencil bigger-100"></i> Editer Task Action</a></li>', 0, '[-1-]', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(222, 161, '0d374b7e2fe21a2e2641c092a3c7f2e9', 'Changer le mot de passe', NULL, 'changepass', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(224, 162, '6f642ee30722158f0318653b9113b887', 'History', NULL, 'history', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(225, 163, 'cc907fac13631903d129c137d671d718', 'Activities', NULL, 'activities', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(231, 167, '3473119f6683893a3f1372dbf7d811e1', 'MAJ Module', NULL, 'update_module', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(232, 21, 'ac4eb0c94da00a48ad5d995f5e9e9366', 'MAJ Module', 'this_exec', 'modul', 'pencil-square-o', '<li><a href="#" class="this_exec" data="%id%" rel="update_module"  ><i class="ace-icon fa fa-pencil-square-o bigger-100"></i> MAJ Module</a></li>', 0, '[-1-]', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(499, 330, '192715027870a4a612fd44d562e2752f', 'Gestion des produits', NULL, 'produits', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', NULL, 'nA', NULL, NULL, NULL, NULL),
(500, 330, 'ed13b17897a396c0633d7989f2bc644f', 'Modifier produit', NULL, 'produits', NULL, '<li><a href="#" class="this_url" data="%id%" rel="editproduit"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Modifier produit</a></li>', 0, '[-1-]', 0, 0, 'Attente de validation', NULL, 'nA', NULL, NULL, NULL, NULL),
(501, 330, '96df3c4057988c54a7d468e5664dba10', 'Détail produit', NULL, 'produits', NULL, '<li><a href="#" class="this_url" data="%id%" rel="detailproduit"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Détail produit</a></li>', 0, '[-1-]', 0, 0, 'Attente de validation', NULL, 'nA', NULL, NULL, NULL, NULL),
(502, 330, 'eb5b51394e164f00ce8c998310e3a8ba', 'Valider produit', NULL, 'produits', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validproduit"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Valider produit</a></li>', 0, '[-1-]', 0, 1, 'Attente de validation', NULL, 'nA', NULL, NULL, NULL, NULL),
(503, 330, '6b087b20929483bb07f8862b39e41f07', 'Désactiver produit', NULL, 'produits', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validproduit"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Désactiver produit</a></li>', 0, '[-1-]', 1, 0, 'Valide', NULL, 'nA', NULL, NULL, NULL, NULL),
(504, 331, '93e893c307a6fa63e392f78751ec70ce', 'Ajouter produit', NULL, 'addproduit', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', NULL, 'nA', NULL, NULL, NULL, NULL),
(505, 332, 'bcf3beada4a98e8145af2d4fbb744f01', 'Modifier produit', NULL, 'editproduit', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', NULL, 'nA', NULL, NULL, NULL, NULL),
(506, 333, '796427ec57f7c13d6b737055ae686b34', 'Detail produit', NULL, 'detailproduit', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', NULL, 'nA', NULL, NULL, NULL, NULL),
(507, 334, '1fb8cd1a179be07586fa7db05013dd37', 'Valider produit', NULL, 'validproduit', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', NULL, 'nA', NULL, NULL, NULL, NULL),
(508, 335, '7779e98d2111faedf458f7aeb548294e', 'Supprimer produit', NULL, 'deleteproduit', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', NULL, 'nA', NULL, NULL, NULL, NULL),
(552, 362, '6edc543080c65eca3993445c295ff94b', 'Gestion Catégorie Client', NULL, NULL, NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', NULL, 'nA', NULL, NULL, NULL, NULL),
(553, 362, '142a68a109abd0462ea44fcadffe56de', 'Editer Catégorie Client', NULL, NULL, NULL, '<li><a href="#" class="this_url" data="%id%" rel="editcategorie_client"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Editer Catégorie Client</a></li>', 0, '[-1-]', 0, 0, 'Attente Validation', NULL, 'nA', NULL, NULL, NULL, NULL),
(554, 362, '70df89fa2654d8b10d7fc7e75e178b7e', 'Activer Catégorie Client', NULL, NULL, NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validcategorie_client"  ><i class="ace-icon fa fa-lock bigger-100"></i> Activer Catégorie Client</a></li>', 0, '[-1-]', 0, 1, 'Attente Validation', NULL, 'nA', NULL, NULL, NULL, NULL),
(555, 362, '109e82d6db5721f63cd827e9fd224216', 'Désactiver Catégorie Client', NULL, NULL, NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validcategorie_client"  ><i class="ace-icon fa fa-unlock bigger-100"></i> Désactiver Catégorie Client</a></li>', 0, '[-1-]', 1, 0, 'Catégorie Validée', NULL, 'nA', NULL, NULL, NULL, NULL),
(556, 363, 'a5c1bd0dfd87824ff0f57c6b1e1d2c3f', 'Ajouter Catégorie Client', NULL, NULL, NULL, '', 1, '[-1-]', 0, 0, 'Attente Validation', NULL, 'nA', NULL, NULL, NULL, NULL),
(557, 364, '8d901f74dfd6ee3a8f44ebd0b83fbfae', 'Editer Catégorie Client', NULL, NULL, NULL, '', 1, '[-1-]', 0, 0, 'Attente Validation', NULL, 'nA', NULL, NULL, NULL, NULL),
(558, 365, 'e87327563ce6b659780d6b2c9bf8ac77', 'Supprimer Catégorie Client', NULL, NULL, NULL, '', 1, '[-1-]', 0, 0, 'Attente Validation', NULL, 'nA', NULL, NULL, NULL, NULL),
(559, 366, 'c955da8d244aac06ee7595d08de7d009', 'Valider Catégorie Client', NULL, NULL, NULL, '', 1, '[-1-]', 0, 0, 'Attente Validation', NULL, 'nA', NULL, NULL, NULL, NULL),
(560, 367, 'f12fb1c50aedc49c3fa3dfa2bd297bd3', 'Gestion Clients', NULL, NULL, NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', NULL, 'nA', NULL, NULL, NULL, NULL),
(561, 367, 'dd3d5980299911ea854af4fa6f2e7309', 'Editer Client', NULL, NULL, NULL, '<li><a href="#" class="this_url" data="%id%" rel="editclient"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Editer Client</a></li>', 0, '[-1-]', 0, 0, 'Attente Validation', NULL, 'nA', NULL, NULL, NULL, NULL),
(562, 367, '3c5c04a20d49ad010557a64c8cdac1ce', 'Valider Client', NULL, NULL, NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validclient"  ><i class="ace-icon fa fa-lock bigger-100"></i> Valider Client</a></li>', 0, '[-1-]', 0, 1, 'Attente Validation', NULL, 'nA', NULL, NULL, NULL, NULL),
(563, 367, '18ace52052f2551099ecaabf049ffaec', 'Désactiver Client', NULL, NULL, NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validclient"  ><i class="ace-icon fa fa-unlock bigger-100"></i> Désactiver Client</a></li>', 0, '[-1-]', 1, 0, 'Client Validé', NULL, 'nA', NULL, NULL, NULL, NULL),
(564, 367, '493f9e55fc0340763e07514c1900685a', 'Détails Client', NULL, NULL, NULL, '<li><a href="#" class="this_url" data="%id%" rel="detailsclient"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails Client</a></li>', 0, '[-1-]', 0, 0, 'Attente Validation', NULL, 'nA', NULL, NULL, NULL, NULL),
(565, 367, '03b4f949b088e41fc9a1f3f23b7906a8', 'Détails  Client', NULL, NULL, NULL, '<li><a href="#" class="this_url" data="%id%" rel="detailsclient"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails  Client</a></li>', 0, '[-1-]', 1, 0, 'Client Validé', NULL, 'nA', NULL, NULL, NULL, NULL),
(566, 368, '2b9d8bb8f752d1c35fb681c33e38b42b', 'Ajouter Client', NULL, NULL, NULL, '', 1, '[-1-]', 0, 0, 'Attente Validation', NULL, 'nA', NULL, NULL, NULL, NULL),
(567, 369, '54aa9121e05f5e698d354022a8eab71d', 'Editer Client', NULL, NULL, NULL, '', 1, '[-1-]', 0, 0, 'Attente Validation', NULL, 'nA', NULL, NULL, NULL, NULL),
(568, 370, '4eaf650e8c2221d590fac5a6a6952231', 'Supprimer Client', NULL, NULL, NULL, '', 1, '[-1-]', 0, 0, 'Attente Validation', NULL, 'nA', NULL, NULL, NULL, NULL),
(569, 371, '534cd4b17fb8a371d3a20565ab8fd96e', 'Valider Client', NULL, NULL, NULL, '', 1, '[-1-]', 0, 0, 'Attente Validation', NULL, 'nA', NULL, NULL, NULL, NULL),
(570, 372, '95bb6aa696ef630a335aa84e1e425e2c', 'Détails Client', NULL, NULL, NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', NULL, 'nA', NULL, NULL, NULL, NULL),
(579, 14, '17c98287fb82388423e04d24404cf662', 'Permissions', 'this_url', 'rules', 'lock', '<li><a href="#" class="this_url" data="%id%" rel="rules"  ><i class="ace-icon fa fa-lock bigger-100"></i> Permissions</a></li>', 0, '[-1-]', 0, 1, 'Attente activation', 'danger', '<span class="label label-sm label-danger">Attente activation</span>', NULL, NULL, NULL, NULL),
(580, 378, 'ca32f2764cf93defb97895f5e44b0e54', 'Dupliquer Task Action', NULL, 'dupliqtaskaction', NULL, '', 0, '[-1-]', 0, 0, 'Rien', 'success', '<span class="label label-sm label-success">Rien</span>', NULL, NULL, NULL, NULL),
(581, 27, 'e8996f69e9f6afe1f8f7b9d2487cb662', 'Dupliquer Task Action', 'this_url', 'dupliqtaskaction', 'copy', '<li><a href="#" class="this_url" data="%id%" rel="dupliqtaskaction"  ><i class="ace-icon fa fa-copy bigger-100"></i> Dupliquer Task Action</a></li>', 0, '[-1-]', 0, 0, 'Active', 'success', '<span class="label label-sm label-success">Active</span>', NULL, NULL, NULL, NULL),
(582, 330, 'e82e6cf7b8c6364fe92ee713b885c9f8', 'Archiver produit', 'this_url', 'produits', 'zip', '<li><a href="#" class="this_url" data="%id%" rel="produits"  ><i class="ace-icon fa fa-zip bigger-100"></i> Archiver produit</a></li>', 0, '[-1-]', 0, 1, 'Attente de validation', 'success', '<span class="label label-sm label-success">Attente de validation</span>', NULL, NULL, NULL, NULL),
(585, 384, '3d4eaa53061f51b0c4435bd8e4b89c17', 'Gestion Vente', NULL, 'vente', NULL, '', 0, '[-1-2-]', 0, 0, 'Actif', 'success', '<span class="label label-sm label-success">Actif</span>', NULL, NULL, NULL, NULL),
(586, 385, '0e79510db7f03b9b6266fc7b4a612153', 'Gestion Devis', NULL, 'devis', NULL, '', 0, '[-1-2-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(587, 388, 'd9eeb330625c1b87e0df00986a47be01', 'Ajouter Devis', NULL, 'adddevis', NULL, '', 0, '[-1-2-]', 0, 0, 'Brouillon', 'success', '<span class="label label-sm label-success">Brouillon</span>', NULL, NULL, NULL, NULL),
(588, 389, 'da93cdb05137e15aed9c4c18bddd746a', 'Ajouter détail devis', NULL, 'add_detaildevis', NULL, '', 0, '[-1-2-]', 0, 0, 'Attente validation', 'success', '<span class="label label-sm label-success">Attente validation</span>', NULL, NULL, NULL, NULL),
(589, 390, 'f9f3c299f9bd0fec014f6bd3f0e06adb', 'Modifier Devis', NULL, 'editdevis', NULL, '', 0, '[-1-2-]', 0, 0, 'Attente validation', 'warning', '<span class="label label-sm label-warning">Attente validation</span>', NULL, NULL, NULL, NULL),
(590, 385, 'c15b00a1e37657336df8b6aa0eea2db5', 'Modifier Devis', 'this_url', 'editdevis', 'pencil-square-o blue', '<li><a href="#" class="this_url" data="%id%" rel="editdevis"  ><i class="ace-icon fa fa-pencil-square-o blue bigger-100"></i> Modifier Devis</a></li>', 0, '[-1-2-]', 0, 1, 'Attente validation', 'warning', '<span class="label label-sm label-warning">Attente validation</span>', NULL, NULL, NULL, NULL),
(591, 391, 'e14cce6f1faf7784adb327581c516b90', 'Supprimer Devis', NULL, 'deletedevis', NULL, '', 0, '[-1-3-]', 0, 0, 'Attente validation', 'warning', '<span class="label label-sm label-warning">Attente validation</span>', NULL, NULL, NULL, NULL),
(592, 392, '38f10871792c133ebcc6040e9a11cde8', 'Modifier détail Devis', NULL, 'edit_detaildevis', NULL, '', 0, '[-1-2-3-]', 0, 0, 'Attente validation', 'warning', '<span class="label label-sm label-warning">Attente validation</span>', NULL, NULL, NULL, NULL),
(593, 393, '8def42e75fd4aee61c378d9fb303850d', 'Afficher détail devis', NULL, 'viewdevis', NULL, '', 0, '[-1-2-3-4-18-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(594, 385, 'd34b07afd92adad84e1c4c2ebd92ba95', 'Voir détails', 'this_url', 'viewdevis', 'eye', '<li><a href="#" class="this_url" data="%id%" rel="viewdevis"  ><i class="ace-icon fa fa-eye bigger-100"></i> Voir détails</a></li>', 0, '[-1-2-17-4-]', 0, 1, 'Attente validation', 'warning', '<span class="label label-sm label-warning">Attente validation</span>', NULL, NULL, NULL, NULL),
(595, 394, '7666e87783b0f5a7eec1eea7593f7dfe', 'Valider Devis', NULL, 'validdevis', NULL, '', 0, '[-1-2-3-5-4-]', 0, 0, 'Attente validation', 'success', '<span class="label label-sm label-success">Attente validation</span>', NULL, NULL, NULL, NULL),
(596, 385, '5a05eba5be17eba1f35ef8927bfa16d2', 'Valider Devis', 'this_exec', 'validdevis', 'check-square-o green', '<li><a href="#" class="this_exec" data="%id%" rel="validdevis"  ><i class="ace-icon fa fa-check-square-o green bigger-100"></i> Valider Devis</a></li>', 0, '[-1-2-3-]', 0, 1, 'Attente validation', 'warning', '<span class="label label-sm label-warning">Attente validation</span>', NULL, NULL, NULL, NULL),
(597, 385, '28e267a2a0647d4cb37b18abb1e7d051', 'Voir détails', 'this_url', 'viewdevis', 'eye', '<li><a href="#" class="this_url" data="%id%" rel="viewdevis"  ><i class="ace-icon fa fa-eye bigger-100"></i> Voir détails</a></li>', 0, '[-1-2-3-5-]', 1, 0, 'Devis validé', 'success', '<span class="label label-sm label-success">Devis validé</span>', NULL, NULL, NULL, NULL),
(618, 408, '605450f3d7c84701b986fa31e1e9fa43', 'Gestion Pays', NULL, 'pays', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(619, 408, '29ba6cc689eca63dbafb109ec58bc4d6', 'Editer Pays', 'this_url', 'editpays', NULL, '<li><a href="#" class="this_url" data="%id%" rel="editpays"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Editer Pays</a></li>', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(620, 408, '763fe13212b4324590518773cd9a36fa', 'Valider Pays', 'this_exec', 'validpays', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validpays"  ><i class="ace-icon fa fa-lock bigger-100"></i> Valider Pays</a></li>', 0, '[-1-]', 0, 1, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(621, 408, '3c8427c7313d35219b17572efd380b17', 'Désactiver Pays', 'this_exec', 'validpays', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validpays"  ><i class="ace-icon fa fa-unlock bigger-100"></i> Désactiver Pays</a></li>', 0, '[-1-]', 1, 0, 'Pays Validé', 'success', '<span class="label label-sm label-success">Pays Validé</span>', NULL, NULL, NULL, NULL),
(622, 409, '3cd55a55307615d72aae84c6b5cf99bc', 'Ajouter Pays', NULL, 'addpays', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(623, 410, 'cfe617d7bc6a9c7d8b86c468f21396f2', 'Editer Pays', NULL, 'editpays', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(624, 411, 'b768486aeb655c48cc411c11fa60e150', 'Supprimer Pays', NULL, 'deletepays', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(625, 412, '15e4e24f320daa9d563ae62acff9e586', 'Valider Pays', NULL, 'validpays', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(642, 423, '72db1c2280dc3eb6405908c1c5b6c815', 'Information société', NULL, 'info_ste', NULL, '', 0, '[-1-3-]', 0, 0, 'Confirmé', 'success', '<span class="label label-sm label-success">Confirmé</span>', NULL, NULL, NULL, NULL),
(643, 424, 'ec45512f34613446e7a2e367d4b4cfbd', 'Gestion Contrats Fournisseurs', NULL, 'contrats_fournisseurs', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(644, 424, 'e3c0d7e92dad7f8794b2415c334ec3ff', 'Editer Contrat', 'this_url', 'editcontrat_frn', NULL, '<li><a href="#" class="this_url" data="%id%" rel="editcontrat_frn"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Editer Contrat</a></li>', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(645, 424, '9dfff1c8dcb804837200f38e95381420', 'Valider Contrat', 'this_exec', 'validcontrat_frn', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validcontrat_frn"  ><i class="ace-icon fa fa-lock bigger-100"></i> Valider Contrat</a></li>', 0, '[-1-]', 0, 1, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(646, 424, '9fe39b496077065105a57ccd9ed05863', 'Désactiver Contrat', 'this_exec', 'validcontrat_frn', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validcontrat_frn"  ><i class="ace-icon fa fa-unlock bigger-100"></i> Désactiver Contrat</a></li>', 0, '[-1-]', 1, 0, 'Contrat Validé', 'success', '<span class="label label-sm label-success">Contrat Validé</span>', NULL, NULL, NULL, NULL),
(647, 424, '0092ad9ef69b6420a611df6859a43cda', 'Détails Contrat', 'this_url', 'detailscontrat_frn', NULL, '<li><a href="#" class="this_url" data="%id%" rel="detailscontrat_frn"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails Contrat</a></li>', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(648, 424, '6ca83d9c6c0b229446da30b60b74031a', 'Détails  Contrat', 'this_url', 'detailscontrat_frn', NULL, '<li><a href="#" class="this_url" data="%id%" rel="detailscontrat_frn"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails  Contrat</a></li>', 0, '[-1-]', 1, 0, 'Contrat Validé', 'success', '<span class="label label-sm label-success">Contrat Validé</span>', NULL, NULL, NULL, NULL),
(649, 425, 'ded24eb817021c5a666a677b1565bc5e', 'Ajouter Contrat', NULL, 'addcontrat_frn', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(650, 426, 'ed6b8695494bf4ed86d5fb18690b3a59', 'Editer Contrat', NULL, 'editcontrat_frn', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(651, 427, 'b8a40913b5955209994aaa26d0e8c3d4', 'Supprimer Contrat', NULL, 'deletecontrat_frn', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(652, 428, '5efb874e7d73ccd722df806e8275770f', 'Valider Contrat', NULL, 'validcontrat_frn', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(653, 429, '11cabf03a954a5476cc78cf221f04d78', 'Détails Contrat', NULL, 'detailscontrat_frn', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(654, 430, '6beb279abea6434e3b73229aebadc081', 'Gestion Fournisseurs', NULL, 'fournisseurs', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(655, 430, 'ff95747f3a590b6539803f2a9a394cd5', 'Editer Fournisseur', 'this_url', 'editfournisseur', NULL, '<li><a href="#" class="this_url" data="%id%" rel="editfournisseur"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Editer Fournisseur</a></li>', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(656, 430, 'fea982f5074995d4ccd6211a71ab2680', 'Valider Fournisseur', 'this_exec', 'validfournisseur', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validfournisseur"  ><i class="ace-icon fa fa-lock bigger-100"></i> Valider Fournisseur</a></li>', 0, '[-1-]', 0, 1, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(657, 430, '1d0411a0dec15fc28f054f1a79d95618', 'Désactiver Fournisseur', 'this_exec', 'validfournisseur', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validfournisseur"  ><i class="ace-icon fa fa-unlock bigger-100"></i> Désactiver Fournisseur</a></li>', 0, '[-1-]', 1, 0, 'Fournisseur Validé', 'success', '<span class="label label-sm label-success">Fournisseur Validé</span>', NULL, NULL, NULL, NULL),
(658, 430, 'a52affdd109b9362ce47ff18aad53e2a', 'Détails Fournisseur', 'this_url', 'detailsfournisseur', NULL, '<li><a href="#" class="this_url" data="%id%" rel="detailsfournisseur"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails Fournisseur</a></li>', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(659, 430, 'c6fe5f222dd563204188e8bf0d69bd9e', 'Détails  Fournisseur', 'this_url', 'detailsfournisseur', NULL, '<li><a href="#" class="this_url" data="%id%" rel="detailsfournisseur"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails  Fournisseur</a></li>', 0, '[-1-]', 1, 0, 'Fournisseur Validé', 'success', '<span class="label label-sm label-success">Fournisseur Validé</span>', NULL, NULL, NULL, NULL),
(660, 431, 'd644015625a9603adb2fcc36167aeb73', 'Ajouter Fournisseur', NULL, 'addfournisseur', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(661, 432, '58c6694abfd3228d927a5d5a06d40b94', 'Editer Fournisseur', NULL, 'editfournisseur', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(662, 433, 'd072f81cd779e4b0152953241d713ca3', 'Supprimer Fournisseur', NULL, 'deletefournisseur', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(663, 434, '657351ce5aa227513e3b50dea77db918', 'Valider Fournisseur', NULL, 'validfournisseur', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(664, 435, '83b693fe35a1be29edafe4f6170641aa', 'Détails Fournisseur', NULL, 'detailsfournisseur', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(665, 436, '2c3b01c696ff401a2ac9ffedb7a06e4a', 'Gestion Villes', NULL, 'villes', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(666, 436, 'b9649163b368f863a0e8036f11cd81ae', 'Editer Ville', 'this_url', 'villes', NULL, '<li><a href="#" class="this_url" data="%id%" rel="editville"  ><i class="ace-icon fa fa-pencil bigger-100"></i> Editer Ville</a></li>', 0, '[-1-]', 0, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(667, 436, '89dec6dabcb210cdb9dd28bbef90d43e', 'Editer Ville', 'this_url', 'villes', NULL, '<li><a href="#" class="this_url" data="%id%" rel="editville"  ><i class="ace-icon fa fa-pencil bigger-100"></i> Editer Ville</a></li>', 0, '[-1-]', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(668, 436, '4a2edbdcbda34c9d3d1e6abe73643b37', 'Valider Ville', 'this_exec', 'validville', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validville"  ><i class="ace-icon fa fa-lock bigger-100"></i> Valider Ville</a></li>', 0, '[-1-]', 0, 1, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(669, 436, '0c8ad6595a4516be83ba5a9cdb7ea9a1', 'Désactiver Ville', 'this_exec', 'validville', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validville"  ><i class="ace-icon fa fa-unlock bigger-100"></i> Désactiver Ville</a></li>', 0, '[-1-]', 1, 0, 'Ville Validée', 'success', '<span class="label label-sm label-success">Ville Validée</span>', NULL, NULL, NULL, NULL),
(670, 437, 'e152b9052d3dcfcac593489dbdc0f61c', 'Ajouter ville', NULL, 'addville', NULL, '', 1, '[-1-2-]', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(671, 438, '3107e0cd0e0df14c4e94aa088e4457d7', 'Editer Ville', NULL, 'editville', NULL, '', 1, '[-1-2-]', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(672, 439, 'da79d9214ed5819d7f4f1e3070629a3d', 'Supprimer Ville', NULL, 'deleteville', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(673, 440, 'fe03a68d17c62ff2c27329573a1b3550', 'Valider Ville', NULL, 'validville', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(674, 441, 'e69f84a801ee1525f20f34e684688a9b', 'Gestion des catégories de produits', NULL, 'categories_produits', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(675, 441, '90f6eba3e0ed223e73d250278cb445d5', 'Modifier catégorie', 'this_url', 'editecategorie_produit', NULL, '<li><a href="#" class="this_url" data="%id%" rel="editecategorie_produit"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Modifier catégorie</a></li>', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(676, 441, 'c62968a45ae9cfa8b127ac1b5573988a', 'Valider catégorie', 'this_exec', 'validcategorie_produit', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validcategorie_produit"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Valider catégorie</a></li>', 0, '[-1-]', 0, 1, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(677, 441, '6f43a6bcbd293f958aff51953559104e', 'Désactiver catégorie', 'this_exec', 'validcategorie_produit', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validcategorie_produit"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Désactiver catégorie</a></li>', 0, '[-1-]', 1, 0, 'Catégorie Validée', 'success', '<span class="label label-sm label-success">Catégorie Validée</span>', NULL, NULL, NULL, NULL),
(678, 442, 'd26f5940e88a494c0eb65047aab9a17b', 'Ajouter une catégorie', NULL, 'addcategorie_produit', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(679, 443, '27957c6d0f6869d4d90287cd50b6dde9', 'Modifier une catégorie', NULL, 'editecategorie_produit', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(680, 444, '41b48dd567e4f79e35261a47b7bad751', 'Valider une catégorie', NULL, 'validcategorie_produit', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(681, 445, '90dc20c4d1ad7be7fac8ec34d5ac26b3', 'Supprimer une catégorie', NULL, 'deletecategorie_produit', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(682, 446, 'b6b6bfbd070b5b3dd84acedae7b854e9', 'Gestion des types de produits', NULL, 'types_produits', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(683, 446, '3c5400b775264499825a039d66aa9c90', 'Modifier type', 'this_url', 'edittype_produit', NULL, '<li><a href="#" class="this_url" data="%id%" rel="edittype_produit"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Modifier type</a></li>', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(684, 446, 'dcf55bc300d690af4c81e4d2335e60e5', 'Valider type', 'this_exec', 'validtype_produit', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validtype_produit"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Valider type</a></li>', 0, '[-1-]', 0, 1, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(685, 446, '230b9554d37da1c71986af94962cb340', 'Désactiver type', 'this_exec', 'validtype_produit', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validtype_produit"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Désactiver type</a></li>', 0, '[-1-]', 1, 0, 'Type validé', 'success', '<span class="label label-sm label-success">Type validé</span>', NULL, NULL, NULL, NULL),
(686, 447, 'e0d163499b4ba11d6d7a648bc6fc6de6', 'Ajouter un type', NULL, 'addtype_produit', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(687, 448, 'ac5a6d087b3c8db7501fa5137a47773e', 'Modifier type', NULL, 'edittype_produit', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(688, 449, '2e8242a93a62a264ad7cfc953967f575', 'Valider type', NULL, 'validtype_produit', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(689, 450, 'e3725ba15ca483b9278f68553eca5918', 'Supprimer type', NULL, 'deletetype_produit', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(690, 451, '55ecbb545a49c70c0b728bb0c7951077', 'Gestion des unités de vente', NULL, 'unites_vente', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(691, 451, '67acd70eb04242b7091d9dcbb08295d7', 'Modifier unité ', 'this_url', 'editunite_vente', NULL, '<li><a href="#" class="this_url" data="%id%" rel="editunite_vente"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Modifier unité </a></li>', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(692, 451, '7363022ed5ad047bfe86d3de4b75b1f4', 'Valider unité', 'this_exec', 'validunite_vente', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validunite_vente"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Valider unité</a></li>', 0, '[-1-]', 0, 1, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(693, 451, 'ec77eb95736c27bfc269cbffc8e113f1', 'Désactiver unité', 'this_exec', 'validunite_vente', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validunite_vente"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Désactiver unité</a></li>', 0, '[-1-]', 1, 0, 'Unité de vente validé', 'success', '<span class="label label-sm label-success">Unité de vente validé</span>', NULL, NULL, NULL, NULL),
(694, 452, '3a5e8dfe211121eda706f8b6d548d111', 'ajouter une unité', NULL, 'addunite_vente', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(695, 453, '9b7a578981de699286376903e96bc3c7', 'Modifier une unité', NULL, 'editunite_vente', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(696, 454, '62355588366c13ddbadc7a7ca1d226ad', 'Valider une unité', NULL, 'validunite_vente', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(697, 455, 'e5f53a3aaa324415d781156396938101', 'Supprimer une unité', NULL, 'deleteunite_vente', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(698, 456, '899d40c8f22d4f7a6f048366f1829787', 'Gestion des contrats', NULL, 'contrats', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(699, 456, 'a20f4c5b9c9ebaa238757d6f9f9cb6fb', 'Modifier contrat', 'this_url', 'editcontrat', NULL, '<li><a href="#" class="this_url" data="%id%" rel="editcontrat"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Modifier contrat</a></li>', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(700, 456, 'fbb243d2c2fa4200c40993e527b3a339', 'Détail contrat', 'this_url', 'detailcontrat', NULL, '<li><a href="#" class="this_url" data="%id%" rel="detailcontrat"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Détail contrat</a></li>', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(701, 456, 'e970c1414507e5b83ae39e7ddedbf15e', 'Valider contrat', 'this_exec', 'validcontrat', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validcontrat"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Valider contrat</a></li>', 0, '[-1-]', 0, 1, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(702, 456, '6908357258099272b60018c0f6fb1078', 'Désactiver contrat', 'this_exec', 'validcontrat', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validcontrat"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Désactiver contrat</a></li>', 0, '[-1-]', 1, 0, 'Contrat validé', 'success', '<span class="label label-sm label-success">Contrat validé</span>', NULL, NULL, NULL, NULL),
(703, 456, '11cabf03a954a5476cc78cf221f04d78', 'Détails Contrat', 'this_url', 'detailcontrat', NULL, '<li><a href="#" class="this_url" data="%id%" rel="detailcontrat"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails Contrat</a></li>', 0, '[-1-]', 1, 0, 'Contrat Validé', 'success', '<span class="label label-sm label-success">Contrat Validé</span>', NULL, NULL, NULL, NULL),
(704, 457, '87f4c3ed4713c3bc9e3fef60a6649055', 'Ajouter contrat', NULL, 'addcontrat', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(705, 458, '9e49a431d9637544cefa2869fd7278b9', 'Modifier contrat', NULL, 'editcontrat', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(706, 459, '1e9395a182a44787e493bc038cd80bbf', 'Supprimer contrat', NULL, 'deletecontrat', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(707, 460, '460d92834715b149c4db28e1643bd932', 'Valider contrat', NULL, 'validcontrat', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(708, 461, 'bbcf2879c2f8f60cfa55fa97c6e79268', 'Détail contrat', NULL, 'detailcontrat', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(709, 462, 'fe058ccb890b25a54866be7f24a40363', 'Ajouter échéance ', NULL, 'addecheance_contrat', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(710, 463, '36a248f56a6a80977e5c90a5c59f39d3', 'Modifier échéance contrat', NULL, 'editecheance_contrat', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(711, 464, 'd57b16b3aad4ce59f909609246c4fd36', 'Gestion des régions', NULL, 'regions', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(712, 464, 'd2e007184668dd70b9bae44d46d28ded', 'Modifier région', 'this_url', 'editregion', NULL, '<li><a href="#" class="this_url" data="%id%" rel="editregion"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Modifier région</a></li>', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(713, 464, 'e74403c99ac8325b78735c531a20442f', 'Valider région', 'this_exec', 'validregion', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validregion"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Valider région</a></li>', 0, '[-1-]', 0, 1, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(714, 464, '7397a0fab078728bd5c53be61022d5ce', 'Désactiver région', 'this_exec', 'validregion', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validregion"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Désactiver région</a></li>', 0, '[-1-]', 1, 0, 'Région Validée', 'success', '<span class="label label-sm label-success">Région Validée</span>', NULL, NULL, NULL, NULL),
(715, 465, '0237bd41cf70c3529681b4ccb041f1fd', 'Ajouter région', NULL, 'addregion', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(716, 466, '6d290f454da473cb8a557829a410c3f1', 'Modifier région', NULL, 'editregion', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(717, 467, '008cd9ea5767c739675fef4e1261cfe8', 'Valider région', NULL, 'validregion', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(718, 468, 'fc477e6a4c90cd427ae81e555c11d6a9', 'Supprimer région', NULL, 'deleteregion', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(719, 469, 'f320732af279d6f2f8ae9c98cd0216de', 'Gestion Départements', NULL, 'departements', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(720, 469, '96516cd0c72d814d5dcb1d86eacd29ab', 'Editer Département', 'this_url', 'editdepartement', NULL, '<li><a href="#" class="this_url" data="%id%" rel="editdepartement"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Editer Département</a></li>', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL);
INSERT INTO `task_action` (`id`, `appid`, `idf`, `descrip`, `mode_exec`, `app`, `class`, `code`, `type`, `service`, `etat_line`, `notif`, `etat_desc`, `message_class`, `message_etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(721, 469, 'ef27a63534fa9fc3bd4b5086a92db546', 'Valider Département', 'this_exec', 'validdepartement', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validdepartement"  ><i class="ace-icon fa fa-lock bigger-100"></i> Valider Département</a></li>', 0, '[-1-]', 0, 1, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(722, 469, '9aed965af4c4b89a5a23c41bf685d403', 'Désactiver Département', 'this_exec', 'validdepartement', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validdepartement"  ><i class="ace-icon fa fa-unlock bigger-100"></i> Désactiver Département</a></li>', 0, '[-1-]', 1, 0, 'Département Validé', 'success', '<span class="label label-sm label-success">Département Validé</span>', NULL, NULL, NULL, NULL),
(723, 470, '722b3ba1c7fe735e87aa7415e5502a4c', 'Ajouter Département', NULL, 'adddepartement', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(724, 471, 'daeb31006124e562d284aff67360ee19', 'Editer Département', NULL, 'editdepartement', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(725, 472, 'a775da608fe55c53211d4f1c6e493251', 'Supprimer Département', NULL, 'deletedepartement', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(726, 473, 'bbb96ec910c5000a2006db2f6e8af10a', 'Valider Département', NULL, 'validdepartement', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(727, 474, '312fd18860781a7b1b7e33587fa423d4', 'Gestion Type Echeance', NULL, 'type_echeance', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(728, 474, '46ad76148075d6b458f43e84ddf00791', 'Editer Type Echéance', 'this_url', 'edittype_echeance', NULL, '<li><a href="#" class="this_url" data="%id%" rel="edittype_echeance"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Editer Type Echéance</a></li>', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(729, 474, 'add2bf057924e606653fbf5bbd65ca09', 'Valider Type Echéance', 'this_exec', 'validtype_echeance', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validtype_echeance"  ><i class="ace-icon fa fa-lock bigger-100"></i> Valider Type Echéance</a></li>', 0, '[-1-]', 0, 1, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(730, 474, '463d9e1e8367736b958f0dd84b4e36d5', 'Désactiver Type Echéance', 'this_exec', 'validtype_echeance', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validtype_echeance"  ><i class="ace-icon fa fa-unlock bigger-100"></i> Désactiver Type Echéance</a></li>', 0, '[-1-]', 1, 0, 'Type Echéance Validé', 'success', '<span class="label label-sm label-success">Type Echéance Validé</span>', NULL, NULL, NULL, NULL),
(731, 475, '76170b14c7b6f1f7058d079fe24f739b', 'Ajouter Type Echéance', NULL, 'addtype_echeance', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(732, 476, 'decc5ed58c4d91e6967c9c67e0975cf0', 'Editer Type Echéance', NULL, 'edittype_echeance', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(733, 477, '89db6f23dd8e96a69c6a97f556c44e14', 'Supprimer Type Echéance', NULL, 'deletetype_echeance', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(734, 478, '7527021168823e0118d44297c7684d44', 'Valider Type Echéance', NULL, 'validtype_echeance', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(773, 505, '1eb847d87adcad78d5e951e6110061e5', 'Gestion Proforma', NULL, 'proforma', NULL, '', 0, '[-1-2-3-5-4-]', 0, 0, 'Attente validation', 'success', '<span class="label label-sm label-warning">Attente validation</span>', NULL, NULL, NULL, NULL),
(774, 506, 'd5a6338765b9eab63104b59f01c06114', 'Ajouter pro-forma', NULL, 'addproforma', NULL, '', 0, '[-1-2-3-5-]', 0, 0, 'Brouillon', 'warning', '<span class="label label-sm label-warning">Brouillon</span>', NULL, NULL, NULL, NULL),
(775, 507, '95831bde77bc886d6ab4dd5e734de743', 'Editer proforma', NULL, 'editproforma', NULL, '', 0, '[-1-2-3-5-]', 0, 0, 'Brouillon', 'warning', '<span class="label label-sm label-warning">Brouillon</span>', NULL, NULL, NULL, NULL),
(776, 508, 'cbb4e1efa1c05b42d25a3a6bcab038a2', 'Ajouter détail proforma', NULL, 'adddetailproforma', NULL, '', 0, '[-1-2-3-5-]', 0, 0, 'Attente validation', 'warning', '<span class="label label-sm label-warning">Attente validation</span>', NULL, NULL, NULL, NULL),
(777, 509, 'e9f745054778257a255452c6609461a0', 'valider Proforma', NULL, 'validproforma', NULL, '', 0, '[-1-2-3-5-]', 0, 0, 'Attente validation', 'warning', '<span class="label label-sm label-warning">Attente validation</span>', NULL, NULL, NULL, NULL),
(778, 505, '44ef6849d8d5d17d8e0535187e923d32', 'Editer proforma', 'this_url', 'editproforma', 'pen blue', '<li><a href="#" class="this_url" data="%id%" rel="editproforma"  ><i class="ace-icon fa fa-pen blue bigger-100"></i> Editer proforma</a></li>', 0, '[-1-2-3-5-]', 0, 1, 'Attente validation', 'warning', '<span class="label label-sm label-warning">Attente validation</span>', NULL, NULL, NULL, NULL),
(779, 505, 'b7ce06be726011362a271678547a803c', 'Valider Proforma', 'this_exec', 'validproforma', 'check', '<li><a href="#" class="this_exec" data="%id%" rel="validproforma"  ><i class="ace-icon fa fa-check bigger-100"></i> Valider Proforma</a></li>', 0, '[-1-3-5-]', 0, 1, 'Attente validation', 'warning', '<span class="label label-sm label-warning">Attente validation</span>', NULL, NULL, NULL, NULL),
(780, 510, 'defef148c404c7e6ac79e4783e0a7ab7', 'Détail Pro-forma', NULL, 'viewproforma', '510', '', 0, '[-1-2-3-5-]', 0, 0, 'Attente validation', 'warning', 'Attente validation', NULL, NULL, NULL, NULL),
(781, 505, 'abd8c50f1d2ef4beeeddb68a72973587', 'Détail Proforma', 'this_url', 'viewproforma', 'eye', '<li><a href="#" class="this_url" data="%id%" rel="viewproforma"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détail Proforma</a></li>', 0, '[-1-2-3-5-]', 0, 1, 'Attente validation', 'warning', '<span class="label label-sm label-warning">Attente validation</span>', NULL, NULL, NULL, NULL),
(782, 505, 'e20d83df90355eca2a65f56a2556601f', 'Détail Proforma', 'this_url', 'viewproforma', 'eye', '<li><a href="#" class="this_url" data="%id%" rel="viewproforma"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détail Proforma</a></li>', 0, '[-1-2-3-5-]', 1, 0, 'Validée', 'success', '<span class="label label-sm label-success">Validée</span>', NULL, NULL, NULL, NULL),
(783, 511, '53008d64edf241c937a06f03eff139aa', 'Editer détail proforma', NULL, 'edit_detailproforma', '511', '', 0, '[-1-2-3-5-]', 0, 0, 'Attente validation', 'warning', '<span class="label label-sm label-warning">Attente validation</span>', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `token`
--

CREATE TABLE IF NOT EXISTS `token` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(32) NOT NULL,
  `dattime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `users_sys`
--

CREATE TABLE IF NOT EXISTS `users_sys` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `nom` text NOT NULL COMMENT 'pseudo',
  `fnom` varchar(20) DEFAULT NULL COMMENT 'Nom',
  `lnom` varchar(20) DEFAULT NULL COMMENT 'prénom',
  `pass` text NOT NULL COMMENT 'mot de pass (crypté)',
  `mail` text NOT NULL COMMENT 'Adresse Email',
  `service` int(11) NOT NULL COMMENT 'Service utilisateur',
  `tel` varchar(15) DEFAULT NULL COMMENT 'Téléphone',
  `etat` int(11) NOT NULL COMMENT 'statut utilisateur',
  `defapp` int(11) DEFAULT NULL COMMENT 'application par défault',
  `agence` int(11) DEFAULT NULL COMMENT 'Agence d''affectation',
  `ctc` int(11) NOT NULL DEFAULT '0' COMMENT 'Compteur tentative de connexion',
  `lastactive` datetime DEFAULT NULL COMMENT 'derniere activité',
  `photo` int(6) DEFAULT NULL COMMENT 'Photo utilisateur',
  `signature` int(6) DEFAULT NULL COMMENT 'signature',
  `form` int(6) DEFAULT NULL COMMENT 'Path for form Sing up',
  `creusr` varchar(20) DEFAULT NULL COMMENT 'Inserted BY',
  `credat` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'Inserted date',
  `updusr` varchar(20) DEFAULT NULL COMMENT 'Update BY',
  `upddat` datetime DEFAULT NULL COMMENT 'Updated date',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `user_sys_service` (`service`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Table users Systeme' AUTO_INCREMENT=21 ;

--
-- Contenu de la table `users_sys`
--

INSERT INTO `users_sys` (`id`, `nom`, `fnom`, `lnom`, `pass`, `mail`, `service`, `tel`, `etat`, `defapp`, `agence`, `ctc`, `lastactive`, `photo`, `signature`, `form`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(1, 'admin', 'Administrateur', 'Systeme', '5a05679021426829ab75ac9fa6655947', 'rachid@atelsolution.com', 1, '6544545454', 1, 0, 1, 0, '2017-09-29 16:36:57', 1, 411, 9, NULL, '2017-01-13 13:52:42', '1', '2017-06-06 19:22:54'),
(2, 'rachid', 'Rachid', 'Kada', 'd41d8cd98f00b204e9800998ecf8427e', 'rachid@bdctchad.com', 3, '0612668698', 1, 3, NULL, 0, '2017-01-19 22:29:53', 4, 5, 6, NULL, '2017-01-19 21:59:10', '1', '2017-09-03 12:21:49'),
(17, 'tester', 'tester', 'tester', '5a05679021426829ab75ac9fa6655947', 'test@test', 2, '00000000', 1, 3, NULL, 0, '2017-06-14 23:49:21', 376, 377, 378, '1', '2017-06-13 10:02:41', NULL, NULL),
(18, 'test1', 'test1', 'test1', '5a05679021426829ab75ac9fa6655947', 'test@tests', 2, '000000000', 1, 3, NULL, 0, NULL, 379, 380, 381, '1', '2017-06-13 10:08:34', NULL, NULL),
(19, 'tester1', 'Testeur', 'File', 'd41d8cd98f00b204e9800998ecf8427e', 'testetr@dctchad.cc', 2, '000000000', 1, 3, NULL, 0, NULL, 407, 408, 409, '1', '2017-08-28 01:44:32', '1', '2017-09-03 10:55:41'),
(20, 'anouar', 'ANouar', 'Ouajdi', 'd41d8cd98f00b204e9800998ecf8427e', 'anouar@gmail.fg', 2, '000000000', 0, 3, NULL, 0, NULL, NULL, NULL, NULL, '1', '2017-09-03 13:34:18', '1', '2017-09-03 13:36:03');

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `clients`
--
ALTER TABLE `clients`
  ADD CONSTRAINT `fk_client_categorie` FOREIGN KEY (`id_categorie`) REFERENCES `categorie_client` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_client_pays` FOREIGN KEY (`id_pays`) REFERENCES `ref_pays` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_client_ville` FOREIGN KEY (`id_ville`) REFERENCES `ref_ville` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `contrats`
--
ALTER TABLE `contrats`
  ADD CONSTRAINT `fk_devis_contrat` FOREIGN KEY (`iddevis`) REFERENCES `devis` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `devis`
--
ALTER TABLE `devis`
  ADD CONSTRAINT `fk_id_client` FOREIGN KEY (`id_client`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `produits`
--
ALTER TABLE `produits`
  ADD CONSTRAINT `fk_produit_categorie` FOREIGN KEY (`idcategorie`) REFERENCES `ref_categories_produits` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_produit_type` FOREIGN KEY (`idtype`) REFERENCES `ref_types_produits` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_produit_uv` FOREIGN KEY (`iduv`) REFERENCES `ref_unites_vente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `rules_action`
--
ALTER TABLE `rules_action`
  ADD CONSTRAINT `rules_action_user_sys` FOREIGN KEY (`userid`) REFERENCES `users_sys` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rule_action_service_id` FOREIGN KEY (`service`) REFERENCES `services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rule_action_task_action` FOREIGN KEY (`action_id`) REFERENCES `task_action` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rule_action_task_id` FOREIGN KEY (`appid`) REFERENCES `task` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `session`
--
ALTER TABLE `session`
  ADD CONSTRAINT `session_user_id` FOREIGN KEY (`userid`) REFERENCES `users_sys` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `sys_notifier`
--
ALTER TABLE `sys_notifier`
  ADD CONSTRAINT `fk_app_task` FOREIGN KEY (`app`) REFERENCES `task` (`app`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `task`
--
ALTER TABLE `task`
  ADD CONSTRAINT `task_ibfk_1` FOREIGN KEY (`modul`) REFERENCES `modul` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `task_action`
--
ALTER TABLE `task_action`
  ADD CONSTRAINT `task_action_task` FOREIGN KEY (`appid`) REFERENCES `task` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `users_sys`
--
ALTER TABLE `users_sys`
  ADD CONSTRAINT `user_service_id` FOREIGN KEY (`service`) REFERENCES `services` (`id`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
