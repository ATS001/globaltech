-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Ven 15 Septembre 2017 à 21:46
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
-- Structure de la table `ref_pays`
--

CREATE TABLE IF NOT EXISTS `ref_pays` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identifiant ligne',
  `pays` varchar(50) CHARACTER SET latin1 DEFAULT NULL COMMENT 'libelle pays',
  `nationalite` varchar(50) CHARACTER SET latin1 DEFAULT NULL COMMENT 'nationalité',
  `alpha` varchar(2) CHARACTER SET latin1 DEFAULT NULL COMMENT 'code du pays',
  `etat` int(2) NOT NULL DEFAULT '0' COMMENT 'l''etat de la ligne',
  `creusr` varchar(60) NOT NULL COMMENT 'Crée par',
  `credat` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date création',
  `updusr` varchar(60) DEFAULT NULL COMMENT 'Derniere modif par',
  `upddat` datetime DEFAULT NULL COMMENT 'Date derniere modif',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=247 ;

--
-- Contenu de la table `ref_pays`
--

INSERT INTO `ref_pays` (`id`, `pays`, `nationalite`, `alpha`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(1, 'Afghanistan', '0', 'AF', 1, '', '0000-00-00 00:00:00', '1', '2017-09-15 18:16:41'),
(2, 'Albanie', 'Albanaise', 'AL', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(3, 'Antarctique', '0', 'AQ', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(4, 'Algérie', 'Algérienne', 'DZ', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(5, 'Samoa Américaines', '0', 'AS', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(6, 'Andorre', '0', 'AD', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(7, 'Angola', 'angolaise', 'AO', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(8, 'Antigua-et-Barbuda', '0', 'AG', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(9, 'Azerbaïdjan', 'Azerbaïdjanaise', 'AZ', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(10, 'Argentine', '0', 'AR', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(11, 'Australie', 'Australienne', 'AU', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(12, 'Autriche', 'Autrichienne', 'AT', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(13, 'Bahamas', '0', 'BS', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(14, 'Bahreïn', '0', 'BH', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(15, 'Bangladesh', 'Bangladesh', 'BD', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(16, 'Arménie', 'Arménienne', 'AM', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(17, 'Barbade', '0', 'BB', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(18, 'Belgique', 'Belge', 'BE', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(19, 'Bermudes', '0', 'BM', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(20, 'Bhoutan', '0', 'BT', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(21, 'Bolivie', '0', 'BO', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(22, 'Bosnie-Herzégovine', 'Bosniaque', 'BA', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(23, 'Botswana', '0', 'BW', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(24, 'Île Bouvet', '0', 'BV', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(25, 'Brésil', 'Brésilienne', 'BR', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(26, 'Belize', '0', 'BZ', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(27, 'Territoire Britannique de l''Océan Indien', '0', 'IO', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(28, 'Îles Salomon', '0', 'SB', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(29, 'Îles Vierges Britanniques', '0', 'VG', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(30, 'Brunéi Darussalam', '0', 'BN', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(31, 'Bulgarie', 'Bulgare', 'BG', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(32, 'Myanmar', '0', 'MM', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(33, 'Burundi', 'Burundaise', 'BI', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(34, 'Bélarus', '0', 'BY', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(35, 'Cambodge', '0', 'KH', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(36, 'Cameroun', 'Camerounaise', 'CM', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(37, 'Canada', 'Canadienne', 'CA', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(38, 'Cap-vert', '0', 'CV', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(39, 'Îles Caïmanes', '0', 'KY', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(40, 'République Centrafricaine', 'Centre africaine', 'CF', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(41, 'Sri Lanka', '0', 'LK', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(42, 'Tanzanie', 'Tanzanienne', 'TA', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(43, 'Chili', '0', 'CL', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(44, 'Chine', 'Chinoise', 'CN', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(45, 'Taïwan', 'Taiwanaise', 'TW', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(46, 'Île Christmas', '0', 'CX', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(47, 'Îles Cocos (Keeling)', '0', 'CC', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(48, 'Colombie', 'Colombienne', 'CO', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(49, 'Comores', '0', 'KM', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(50, 'Mayotte', '0', 'YT', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(51, 'République du Congo', '0', 'CG', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(52, 'République Démocratique du Congo', 'Congolaise', 'CD', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(53, 'Îles Cook', '0', 'CK', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(54, 'Costa Rica', '0', 'CR', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(55, 'Croatie', '0', 'HR', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(56, 'Cuba', '0', 'CU', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(57, 'Chypre', '0', 'CY', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(58, 'République Tchèque', '0', 'CZ', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(59, 'Bénin', 'Beninoise', 'BJ', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(60, 'Danemark', 'Danoise', 'DK', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(61, 'Dominique', '0', 'DM', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(62, 'République Dominicaine', 'Dominicaine', 'DO', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(63, 'Équateur', '0', 'EC', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(64, 'El Salvador', '0', 'SV', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(65, 'Guinée Équatoriale', 'Equato-guineenne', 'GQ', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(66, 'Éthiopie', 'Ethiopienne', 'ET', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(67, 'Érythrée', '0', 'ER', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(68, 'Estonie', '0', 'EE', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(69, 'Îles Féroé', '0', 'FO', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(70, 'Îles (malvinas) Falkland', '0', 'FK', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(71, 'Géorgie du Sud et les Îles Sandwich du Sud', '0', 'GS', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(72, 'Fidji', '0', 'FJ', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(73, 'Finlande', 'Finlandaise', 'FI', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(74, 'Îles Åland', '0', 'AX', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(75, 'France', 'Française', 'FR', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(76, 'Guyane Française', '0', 'GF', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(77, 'Polynésie Française', '0', 'PF', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(78, 'Terres Australes Françaises', '0', 'TF', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(79, 'Djibouti', '0', 'DJ', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(80, 'Gabon', 'Gabonaise', 'GA', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(81, 'Géorgie', 'Géorgienne', 'GE', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(82, 'Gambie', '0', 'GM', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(83, 'Territoire Palestinien Occupé', '0', 'PS', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(84, 'Allemagne', 'Allemande', 'DE', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(85, 'Ghana', 'Ghanéenne', 'GH', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(86, 'Gibraltar', '0', 'GI', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(87, 'Kiribati', '0', 'KI', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(88, 'Grèce', 'Hellenique', 'GR', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(89, 'Groenland', '0', 'GL', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(90, 'Grenade', '0', 'GD', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(91, 'Guadeloupe', '0', 'GP', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(92, 'Guam', '0', 'GU', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(93, 'Guatemala', '0', 'GT', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(94, 'Guinée', 'Guinéenne', 'GN', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(95, 'Guyana', '0', 'GY', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(96, 'Haïti', '0', 'HT', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(97, 'Îles Heard et Mcdonald', '0', 'HM', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(98, 'Saint-Siège (état de la Cité du Vatican)', '0', 'VA', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(99, 'Honduras', '0', 'HN', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(100, 'Hong-Kong', '0', 'HK', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(101, 'Hongrie', 'Hongroise', 'HU', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(102, 'Islande', '0', 'IS', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(103, 'Inde', 'Indienne', 'IN', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(104, 'Indonésie', 'Indonesienne', 'ID', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(105, 'République Islamique d''Iran', '0', 'IR', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(106, 'Iraq', 'irakienne', 'IQ', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(107, 'Irlande', 'Irlandaise', 'IE', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(108, 'Israël', 'Israelienne', 'IL', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(109, 'Italie', 'Italienne', 'IT', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(110, 'Cote d''Ivoire', 'Ivoirienne', 'CI', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(111, 'Jamaïque', '0', 'JM', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(112, 'Japon', '0', 'JP', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(113, 'Kazakhstan', '0', 'KZ', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(114, 'Jordanie', 'Jordanienne', 'JO', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(115, 'Kenya', 'Kenyanne', 'KE', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(116, 'République Populaire Démocratique de Corée', '0', 'KP', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(117, 'République de Corée', 'Coréenne', 'KR', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(118, 'Koweït', '0', 'KW', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(119, 'Kirghizistan', '0', 'KG', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(120, 'République Démocratique Populaire Lao', '0', 'LA', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(121, 'Liban', 'Libanaise', 'LB', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(122, 'Lesotho', '0', 'LS', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(123, 'Lettonie', 'Lettone', 'LV', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(124, 'Libéria', '0', 'LR', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(125, 'Jamahiriya Arabe Libyenne', 'Libyenne', 'LY', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(126, 'Liechtenstein', '0', 'LI', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(127, 'Lituanie', 'Lituanienne', 'LT', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(128, 'Luxembourg', '0', 'LU', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(129, 'Macao', '0', 'MO', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(130, 'Madagascar', 'Malgache', 'MG', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(131, 'Malawi', '0', 'MW', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(132, 'Malaisie', 'Malaisienne', 'MY', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(133, 'Maldives', '0', 'MV', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(134, 'Mali', 'Malienne', 'ML', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(135, 'Malte', '0', 'MT', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(136, 'Martinique', '0', 'MQ', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(137, 'Mauritanie', 'Mauritanienne', 'MR', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(138, 'Maurice', 'Mauricienne', 'MU', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(139, 'Mexique', 'Mexicaine', 'MX', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(140, 'Monaco', '0', 'MC', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(141, 'Mongolie', '0', 'MN', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(142, 'République de Moldova', 'Moldave', 'MD', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(143, 'Montserrat', '0', 'MS', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(144, 'Maroc', 'Marocaine', 'MA', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(145, 'Mozambique', 'Mozambicaine', 'MZ', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(146, 'Oman', '0', 'OM', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(147, 'Namibie', '0', 'NA', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(148, 'Nauru', '0', 'NR', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(149, 'Népal', 'Népalaise', 'NP', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(150, 'Pays-Bas', 'Hollandaise', 'NL', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(151, 'Antilles Néerlandaises', '0', 'AN', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(152, 'Aruba', '0', 'AW', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(153, 'Nouvelle-Calédonie', '0', 'NC', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(154, 'Vanuatu', '0', 'VU', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(155, 'Nouvelle-Zélande', 'New zelandaise', 'NZ', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(156, 'Nicaragua', '0', 'NI', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(157, 'Niger', 'Nigerienne', 'NE', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(158, 'Nigéria', 'Nigériane', 'NG', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(159, 'Niué', '0', 'NU', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(160, 'Île Norfolk', '0', 'NF', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(161, 'Norvège', 'Norvégienne', 'NO', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(162, 'Îles Mariannes du Nord', '0', 'MP', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(163, 'Îles Mineures Éloignées des États-Unis', '0', 'UM', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(164, 'États Fédérés de Micronésie', '0', 'FM', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(165, 'Îles Marshall', '0', 'MH', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(166, 'Palaos', '0', 'PW', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(167, 'Pakistan', 'Pakistanaise', 'PK', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(168, 'Panama', '0', 'PA', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(169, 'Papouasie-Nouvelle-Guinée', '0', 'PG', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(170, 'Paraguay', '0', 'PY', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(171, 'Pérou', 'Péruvienne', 'PE', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(172, 'Philippines', 'Philippine', 'PH', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(173, 'Pitcairn', '0', 'PN', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(174, 'Pologne', 'Polonaise', 'PL', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(175, 'Portugal', 'Portugaise', 'PT', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(176, 'Guinée-Bissau', '0', 'GW', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(177, 'Timor-Leste', '0', 'TL', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(178, 'Porto Rico', '0', 'PR', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(179, 'Qatar', '0', 'QA', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(180, 'Réunion', '0', 'RE', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(181, 'Roumanie', 'Roumaine', 'RO', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(182, 'Fédération de Russie', 'Russe', 'RU', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(183, 'Rwanda', '0', 'RW', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(184, 'Sainte-Hélène', '0', 'SH', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(185, 'Saint-Kitts-et-Nevis', '0', 'KN', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(186, 'Anguilla', '0', 'AI', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(187, 'Sainte-Lucie', '0', 'LC', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(188, 'Saint-Pierre-et-Miquelon', '0', 'PM', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(189, 'Saint-Vincent-et-les Grenadines', '0', 'VC', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(190, 'Saint-Marin', '0', 'SM', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(191, 'Sao Tomé-et-Principe', '0', 'ST', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(192, 'Arabie Saoudite', 'Saoudienne', 'SA', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(193, 'Sénégal', 'Sénégalaise', 'SN', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(194, 'Seychelles', '0', 'SC', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(195, 'Sierra Leone', '0', 'SL', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(196, 'Singapour', '0', 'SG', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(197, 'Slovaquie', 'Slovaque', 'SK', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(198, 'Viet Nam', '0', 'VN', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(199, 'Slovénie', '0', 'SI', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(200, 'Somalie', '0', 'SO', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(201, 'Afrique du Sud', 'Sud africaine', 'ZA', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(202, 'Zimbabwe', '0', 'ZW', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(203, 'Espagne', 'Espagnole', 'ES', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(205, 'Soudan', 'Soudanaise', 'SD', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(206, 'Suriname', '0', 'SR', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(207, 'Svalbard etÎle Jan Mayen', '0', 'SJ', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(208, 'Swaziland', '0', 'SZ', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(209, 'Suède', 'Suédoise', 'SE', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(210, 'Suisse', '0', 'CH', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(211, 'République Arabe Syrienne', 'Syrienne', 'SY', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(212, 'Tadjikistan', '0', 'TJ', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(213, 'Thaïlande', '0', 'TH', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(214, 'Togo', 'Togolaise', 'TG', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(215, 'Tokelau', '0', 'TK', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(216, 'Tonga', '0', 'TO', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(217, 'Trinité-et-Tobago', 'Trinidad', 'TT', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(218, 'Émirats Arabes Unis', '0', 'AE', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(219, 'Tunisie', 'Tunisienne', 'TN', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(220, 'Turquie', 'Turque', 'TR', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(221, 'Turkménistan', '0', 'TM', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(222, 'Îles Turks et Caïques', '0', 'TC', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(223, 'Tuvalu', '0', 'TV', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(224, 'Ouganda', 'Ougandaise', 'UG', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(225, 'Ukraine', 'Ukrainienne', 'UA', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(226, 'L''ex-République Yougoslave de Macédoine', 'Macedonienne', 'MK', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(227, 'égypte', 'Egyptienne', 'EG', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(228, 'Royaume-Uni', 'Britannique', 'GB', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(229, 'Île de Man', '0', 'IM', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(230, 'République-Unie de Tanzanie', '0', 'TZ', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(231, 'États-Unis', 'Americaine', 'US', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(232, 'Îles Vierges des États-Unis', '0', 'VI', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(233, 'Burkina Faso', 'Burkinabe', 'BF', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(234, 'Uruguay', '0', 'UY', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(235, 'Ouzbékistan', '0', 'UZ', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(236, 'Venezuela', 'Vénézuélienne', 'VE', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(237, 'Wallis et Futuna', '0', 'WF', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(238, 'Samoa', '0', 'WS', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(239, 'Yémen', 'Yemenite', 'YE', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(240, 'Serbie-et-Monténégro', 'Serbe', 'CS', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(241, 'Zambie', '0', 'ZM', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(242, 'Tchad', 'Tchadienne', 'TD', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(243, 'Erythree', 'Erythreenne', 'ER', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(244, 'Soudan du Sud', 'Sud-Soudanaise', 'SS', 1, '', '0000-00-00 00:00:00', '1', '2017-07-09 18:05:47');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
