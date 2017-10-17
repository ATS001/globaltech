-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le :  mar. 17 oct. 2017 à 22:35
-- Version du serveur :  10.1.28-MariaDB
-- Version de PHP :  5.5.38

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

DELIMITER $$
--
-- Procédures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `generate_devis_fact` (IN `id_devis` INT)  BEGIN
DECLARE reference VARCHAR(20);
SELECT CONCAT('GT-FCT-',(SELECT IFNULL(( MAX(SUBSTR(ref, 8, LENGTH(SUBSTR(ref,8))-5))),0)+1  AS reference 
FROM factures WHERE SUBSTR(ref,LENGTH(ref)-3,4)= (SELECT  YEAR(SYSDATE()))),'/',(SELECT  YEAR(SYSDATE()))) INTO reference ;
INSERT INTO factures (ref, base_fact, total_ht, total_tva, total_ttc_initial ,total_ttc, total_paye, reste,
			CLIENT, projet, ref_bc, iddevis, date_facture, creusr, credat) 
SELECT reference,'D', d.totalht, d.totaltva, d.totalttc, d.totalttc, 0, d.totalttc, c.denomination, d.projet, d.ref_bc,
			d.id, (SELECT NOW() FROM DUAL), 1,(SELECT NOW() FROM DUAL)
FROM Clients c, devis d
WHERE d.id_client=c.id  AND d.id=id_devis;
 
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generate_fact` (IN `tva` INT)  BEGIN
DECLARE finished INT DEFAULT FALSE;
declare reference varchar(20);
DECLare totalht double;
DECLare totaltva double;
DECLare totalttc double;
Declare client varchar(100);
Declare contrat int;
Declare projet varchar(200);
declare ref_bc varchar(200);
declare du date;
declare au date;
DECLARE cur1 CURSOR FOR  select 
if(ech.type_echeance='Annuelle',(d.totalht/dd.qte)* 12,
		IF(ech.type_echeance='Mensuelle',(d.totalht/dd.qte)* 1,
		   IF(ech.type_echeance='Trimestrielle',(d.totalht/dd.qte)* 3,
		      IF(ech.type_echeance='Simestrielle',(d.totalht/dd.qte)* 6,
			 IF(ech.type_echeance='Autres' and d.tva='O',(select ec.montant - (ec.montant * tva /100) from echeances_contrat ec where ec.idcontrat=ctr.id and (SELECT date(NOW()) FROM DUAL)=ec.date_echeance)
			   ,(select ec.montant  from echeances_contrat ec where ec.idcontrat=ctr.id and (SELECT date(NOW()) FROM DUAL)=ec.date_echeance)
		           )
		         )
		     )
		  )		
	     )as totalht,
	     
	     if(ech.type_echeance='Annuelle',(d.totaltva/dd.qte)* 12,
		IF(ech.type_echeance='Mensuelle',(d.totaltva/dd.qte)* 1,
		   IF(ech.type_echeance='Trimestrielle',(d.totaltva/dd.qte)* 3,
		      IF(ech.type_echeance='Simestrielle',(d.totaltva/dd.qte)* 6,
			 IF(ech.type_echeance='Autres' and d.tva='O',(select (ec.montant * tva /100) from echeances_contrat ec where ec.idcontrat=ctr.id and (SELECT date(NOW()) FROM DUAL)=ec.date_echeance)
			   ,0
		           )
		         )
		     )
		  )		
	     )as totaltva,
	      if(ech.type_echeance='Annuelle',(d.totalttc/dd.qte)* 12,
		IF(ech.type_echeance='Mensuelle',(d.totalttc/dd.qte)* 1,
		   IF(ech.type_echeance='Trimestrielle',(d.totalttc/dd.qte)* 3,
		      IF(ech.type_echeance='Simestrielle',(d.totalttc/dd.qte)* 6,
			 IF(ech.type_echeance='Autres' , (select ec.montant  from echeances_contrat ec where ec.idcontrat=ctr.id and (SELECT date(NOW()) FROM DUAL)=ec.date_echeance)
			   ,null
			   )
		         )
		     )
		  )		
	     )as totalttc,
	     c.denomination,ctr.id
	     ,d.projet,d.ref_bc
	     ,(IF ((SELECT COUNT(*) FROM factures f WHERE f.idcontrat=ctr.id)>0, 
		(SELECT DATE_ADD(MAX(f.date_facture), INTERVAL 1 DAY) FROM factures f WHERE f.idcontrat=ctr.id),
		 date_effet
		  )
	      )
 AS DU
,(CASE 
	WHEN ech.type_echeance='Annuelle'       THEN 
	(IF ((SELECT COUNT(*) FROM factures f WHERE f.idcontrat=ctr.id)>0, 
	     (SELECT DATE_ADD(MAX(f.date_facture), INTERVAL 1 YEAR) FROM factures f WHERE f.idcontrat=ctr.id),
	      DATE_ADD(date_effet, INTERVAL 1 YEAR)
	     )
	)
	WHEN ech.type_echeance='Mensuelle'      THEN 
	(IF ((SELECT COUNT(*) FROM factures f WHERE f.idcontrat=ctr.id)>0, 
	     (SELECT DATE_ADD(MAX(f.date_facture), INTERVAL 1 MONTH) FROM factures f WHERE f.idcontrat=ctr.id),
	      DATE_ADD(date_effet, INTERVAL 1 MONTH)
	     )
	)	
	WHEN ech.type_echeance='Trimestrielle'  THEN 
	(IF ((SELECT COUNT(*) FROM factures f WHERE f.idcontrat=ctr.id)>0, 
	     (SELECT DATE_ADD(MAX(f.date_facture), INTERVAL 3 MONTH) FROM factures f WHERE f.idcontrat=ctr.id),
	      DATE_ADD(date_effet, INTERVAL 3 MONTH)
	     )
	)
	WHEN ech.type_echeance='Simestrielle'   THEN 
	(IF ((SELECT COUNT(*) FROM factures f WHERE f.idcontrat=ctr.id)>0, 
	     (SELECT DATE_ADD(MAX(f.date_facture), INTERVAL 6 MONTH) FROM factures f WHERE f.idcontrat=ctr.id),
	     DATE_ADD(date_effet, INTERVAL 6 MONTH)
	     )
	)
	WHEN ech.type_echeance='Autres'	       THEN 
	(SELECT date_echeance FROM echeances_contrat ec WHERE ec.idcontrat=ctr.id AND (SELECT DATE(NOW()) FROM DUAL)=date_echeance)
	
END ) AS AU
 FROM clients c, devis d,d_devis dd, contrats ctr,ref_type_echeance ech 
 WHERE d.id_client=c.id and ctr.iddevis=d.id AND  ctr.idtype_echeance=ech.id
 and ctr.idtype_echeance=ech.id and d.id=dd.id_devis
 and  (SELECT date(NOW())) between ctr.date_effet and ctr.date_fin
 and ctr.etat in (1,2)
 AND (SELECT date(NOW()) FROM DUAL)= (case 
	when ech.type_echeance='Annuelle'       then 
	(IF ((select count(*) from factures f where f.idcontrat=ctr.id)>0, 
	     (select DATE_ADD(max(f.date_facture), INTERVAL 1 year) from factures f where f.idcontrat=ctr.id),
	      IF(ctr.periode_fact='F',DATE_ADD(date_effet, INTERVAL 1 year),date_effet)
	     )
	)
	when ech.type_echeance='Mensuelle'      then 
	(IF ((select count(*) from factures f where f.idcontrat=ctr.id)>0, 
	     (select DATE_ADD(max(f.date_facture), INTERVAL 1 MONTH) from factures f where f.idcontrat=ctr.id),
	      IF(ctr.periode_fact='F',DATE_ADD(date_effet, INTERVAL 1 MONTH),date_effet)
	     )
	)	
	when ech.type_echeance='Trimestrielle'  then 
	(IF ((select count(*) from factures f where f.idcontrat=ctr.id)>0, 
	     (select DATE_ADD(max(f.date_facture), INTERVAL 3 MONTH) from factures f where f.idcontrat=ctr.id),
	      IF(ctr.periode_fact='F',DATE_ADD(date_effet, INTERVAL 3 MONTH),date_effet)
	     )
	)
	when ech.type_echeance='Simestrielle'   then 
	(IF ((select count(*) from factures f where f.idcontrat=ctr.id)>0, 
	     (select DATE_ADD(max(f.date_facture), INTERVAL 6 MONTH) from factures f where f.idcontrat=ctr.id),
	     IF(ctr.periode_fact='F',DATE_ADD(date_effet, INTERVAL 6 MONTH),date_effet)
	     )
	)
	when ech.type_echeance='Autres'	       then 
	(IF ((select count(*) from factures f where f.idcontrat=ctr.id and (SELECT date(NOW()) FROM DUAL)=f.date_facture)>0, 
	     null,
	     (select date_echeance from echeances_contrat ec where ec.idcontrat=ctr.id and (SELECT date(NOW()) FROM DUAL)=date_echeance)
	     )
	)
	
END );
DECLARE CONTINUE HANDLER FOR NOT FOUND SET finished = TRUE;
 OPEN cur1;
 read_loop: LOOP
 fetch cur1 into totalht, totaltva, totalttc, client, contrat, projet, ref_bc, du, au;
 
 IF finished THEN
      LEAVE read_loop;
 END IF;
 
SELECT CONCAT('GT-FCT-',(SELECT IFNULL(( MAX(SUBSTR(ref, 8, LENGTH(SUBSTR(ref,8))-5))),0)+1  AS reference 
FROM factures WHERE SUBSTR(ref,LENGTH(ref)-3,4)= (SELECT  YEAR(SYSDATE()))),'/',(SELECT  YEAR(SYSDATE()))) INTO reference ;
INSERT INTO factures (ref, base_fact, total_ht, total_tva, total_ttc_initial ,total_ttc, total_paye, reste,  CLIENT, projet, ref_bc, idcontrat, date_facture, du, au, creusr, credat) 
values(reference,'C',totalht,totaltva,totalttc,totalttc,0,totalttc,CLIENT, projet, ref_bc, contrat,(SELECT NOW() FROM DUAL), du, au, 1,(SELECT NOW() FROM DUAL));
END LOOP;
CLOSE cur1;
 
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `manage_devis` (IN `etat_expir` INT, `etat_archive` INT)  BEGIN
 /*Expire Devis*/    
UPDATE devis d SET etat = etat_expir WHERE DATE(DATE_ADD(d.date_devis, INTERVAL + d.vie DAY)) <= CURDATE();
 /*Archive Devis*/
UPDATE devis d SET etat = etat_archive WHERE DATE(DATE_ADD(d.date_devis, INTERVAL + d.vie + 60 DAY)) <= CURDATE();
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `notify_contrat` ()  BEGIN
update contrats c set c.`etat`= 2 where (SELECT date(NOW()) FROM DUAL)=c.`date_notif` and c.etat=1 ;
update contrats c set c.`etat`= 3 where (SELECT date(NOW()) FROM DUAL)=(c.`date_fin`+1) ;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `notify_contrat_frn` ()  BEGIN
    
update contrats_frn c set c.`etat`= 2 where (SELECT date(NOW()) FROM DUAL)=c.`date_notif` and c.etat=1 ;
update contrats_frn c set c.`etat`= 3 where (SELECT date(NOW()) FROM DUAL)=(c.`date_fin`+1) ;
    END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `archive`
--

CREATE TABLE `archive` (
  `id` int(11) NOT NULL COMMENT 'ID SYS',
  `doc` varchar(120) CHARACTER SET latin1 DEFAULT NULL COMMENT 'lien document',
  `titr` varchar(300) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Titre document',
  `modul` varchar(35) CHARACTER SET latin1 NOT NULL COMMENT 'le module de document',
  `table` varchar(35) CHARACTER SET latin1 DEFAULT NULL COMMENT 'table de modul',
  `idm` int(11) NOT NULL COMMENT 'ID pour Module',
  `service` int(11) NOT NULL COMMENT 'service',
  `type` varchar(10) CHARACTER SET latin1 DEFAULT NULL COMMENT 'type de document',
  `creusr` int(11) NOT NULL COMMENT 'Add by',
  `credat` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'Dat insert'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table Archives';

--
-- Déchargement des données de la table `archive`
--

INSERT INTO `archive` (`id`, `doc`, `titr`, `modul`, `table`, `idm`, `service`, `type`, `creusr`, `credat`) VALUES
(1, './upload/users/1/photo_1.jpg', 'Photo de profile de Admin', '0', NULL, 1, 1, 'image', 1, '2017-03-27 12:22:39'),
(2, './upload/users/1/signature_1.png', 'signature  de Admin', '0', NULL, 1, 1, 'image', 1, '2017-03-27 12:22:39'),
(513, './upload/users/19/signature_19.png', 'signature  de Boukhdada  Boukhdada', 'users', 'users_sys', 19, 1, 'Document', 1, '2017-10-11 00:36:55'),
(514, './upload/users/20/photo_20.jpg', 'Photo de profile de Rachid  Rachid', 'users', 'users_sys', 20, 1, 'Document', 1, '2017-10-11 00:55:09'),
(515, './upload/users/20/signature_20.png', 'signature  de Rachid  Rachid', 'users', 'users_sys', 20, 1, 'Document', 1, '2017-10-11 00:55:09'),
(516, './upload/users/2/signature_2.png', 'signature  de Fatima Zahra  Fatima Zahra', 'users', 'users_sys', 2, 1, 'Document', 1, '2017-10-13 20:22:52'),
(517, './upload/users/22/signature_22.png', 'signature  de Fati   Fati ', 'users', 'users_sys', 22, 3, 'Document', 20, '2017-10-16 21:34:25'),
(518, './upload/users/23/signature_23.png', 'signature  de Ayoub  Ayoub', 'users', 'users_sys', 23, 3, 'Document', 20, '2017-10-16 21:36:27'),
(519, './upload//proforma/mois_10_2017/31/31_GT_PROF-0002_2017.pdf', 'Proforma 31 #GT_PROF-0002/2017', 'proforma', 'proforma', 31, 1, 'Document', 22, '2017-10-16 22:54:24'),
(520, './upload//proforma/mois_10_2017/32/32_GT_PROF-0001_2017.pdf', 'Proforma 32 #GT_PROF-0001/2017', 'proforma', 'proforma', 32, 1, 'Document', 1, '2017-10-16 23:06:23'),
(522, './upload//devis/mois_10_2017/38/38_GT_DEV-0001_2017.pdf', 'Devis 38 #GT_DEV-0001/2017', 'devis', 'devis', 38, 1, 'Document', 23, '2017-10-16 23:38:26'),
(523, './upload//devis/mois_10_2017/39/39_GT_DEV-0002_2017.pdf', 'Devis 39 #GT_DEV-0002/2017', 'devis', 'devis', 39, 1, 'Document', 1, '2017-10-16 23:49:34'),
(524, './upload//devis/mois_10_2017/40/40_GT_DEV-0003_2017.pdf', 'Devis 40 #GT_DEV-0003/2017', 'devis', 'devis', 40, 1, 'Document', 1, '2017-10-16 23:49:44'),
(525, './upload/contrats/32/pj_32.pdf', 'Justifications du contratGT-CTR-1/2017', 'contrats', 'contrats', 32, 1, 'Document', 1, '2017-10-17 00:14:14'),
(526, './upload/contrats10_2017/contrats_32.pdf', 'contrats 32', 'contrats', 'contrats', 32, 1, 'Document', 1, '2017-10-17 01:24:58'),
(527, './upload/contrats10_2017/contrats_33.pdf', 'contrats 33', 'contrats', 'contrats', 33, 1, 'Document', 22, '2017-10-17 01:32:00'),
(528, './upload//devis/mois_10_2017/41/41_GT_DEV-0004_2017.pdf', 'Devis 41 #GT_DEV-0004/2017', 'devis', 'devis', 41, 1, 'Document', 1, '2017-10-17 01:52:18'),
(530, './upload/contrats10_2017/contrats_34.pdf', 'contrats 34', 'contrats', 'contrats', 34, 1, 'Document', 1, '2017-10-17 02:06:56'),
(531, './upload/users/24/signature_24.png', 'signature  de Ali  Ali', 'users', 'users_sys', 24, 3, 'Document', 20, '2017-10-17 12:24:54'),
(533, './upload//devis/mois_10_2017/42/42_GT_DEV-0005_2017.pdf', 'Devis 42 #GT_DEV-0005/2017', 'devis', 'devis', 42, 3, 'Document', 24, '2017-10-17 13:41:16'),
(534, './upload/contrats10_2017/contrats_35.pdf', 'contrats 35', 'contrats', 'contrats', 35, 3, 'Document', 24, '2017-10-17 13:54:39');

-- --------------------------------------------------------

--
-- Structure de la table `categorie_client`
--

CREATE TABLE `categorie_client` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `categorie_client` varchar(100) DEFAULT NULL COMMENT 'Type client',
  `etat` int(11) DEFAULT '0',
  `creusr` int(11) DEFAULT NULL,
  `credat` datetime DEFAULT CURRENT_TIMESTAMP,
  `updusr` int(11) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `categorie_client`
--

INSERT INTO `categorie_client` (`id`, `categorie_client`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(1, 'Grossiste', 1, 1, '2017-08-23 14:26:24', 1, '2017-09-15 01:18:33'),
(2, 'Detaillant ', 1, 24, '2017-10-17 12:41:21', 24, '2017-10-17 12:43:54');

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `code` varchar(15) NOT NULL COMMENT 'Code client',
  `denomination` varchar(200) NOT NULL COMMENT 'Denomination du client',
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
  `id_devise` int(11) DEFAULT NULL COMMENT 'Devise de facturation du client',
  `tva` varchar(20) DEFAULT NULL COMMENT 'tva O ou N',
  `pj` int(11) DEFAULT NULL,
  `pj_photo` int(11) DEFAULT NULL COMMENT 'photo du client',
  `etat` int(11) DEFAULT '0',
  `creusr` int(11) DEFAULT NULL,
  `credat` datetime DEFAULT CURRENT_TIMESTAMP,
  `updusr` int(11) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `clients`
--

INSERT INTO `clients` (`id`, `code`, `denomination`, `id_categorie`, `r_social`, `r_commerce`, `nif`, `nom`, `prenom`, `civilite`, `adresse`, `id_pays`, `id_ville`, `tel`, `fax`, `bp`, `email`, `rib`, `id_devise`, `tva`, `pj`, `pj_photo`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(24, 'GT-CLT-1/2017', '50FED', 1, 'Fifteen Fed', '908765342', '3876627930', 'Ahmed', 'Salim', 'Homme', 'Sabangali', 242, 43, '98874635', '98874634', 'BP 5', 'ahmed.salim@ffa.td', NULL, 1, 'O', NULL, NULL, 1, 19, '2017-10-11 12:36:07', 19, '2017-10-11 13:05:11'),
(25, 'GT-CLT-2/2017', 'IMC', 1, 'International Management Company', '409347363', '2099984563', 'Abakar', 'Ahmed', 'Homme', 'Farcha', 242, 43, '44995588', '44995589', 'BP 6', 'abakar.ahmed@imc.td', NULL, 1, 'O', NULL, NULL, 1, 19, '2017-10-11 12:50:57', 19, '2017-10-11 12:54:24'),
(26, 'GT-CLT-3/2017', 'GSI', 1, 'Global System IT', '5544498756', '3499887465', 'Issein', 'Ibrahim', 'Homme', 'Chagoua', 242, 43, '55990055', '55990054', 'BP100', 'issein@gbs.td', NULL, 1, 'O', NULL, NULL, 1, 19, '2017-10-11 12:53:14', 19, '2017-10-11 12:54:28'),
(27, 'GT-CLT-4/2017', 'DCT', 1, 'Data Connect Tchad ', '235', '235', 'Rachid', 'Kada ', 'Homme', 'Bololo', 242, 43, '68777505', NULL, '180', 'rachid@dctchad.com', NULL, 1, 'O', NULL, NULL, 1, 24, '2017-10-17 12:33:37', 24, '2017-10-17 12:39:46');

-- --------------------------------------------------------

--
-- Structure de la table `complement_facture`
--

CREATE TABLE `complement_facture` (
  `id` int(11) NOT NULL,
  `designation` varchar(100) DEFAULT NULL,
  `idfacture` int(11) DEFAULT NULL,
  `montant` double DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `date_complement` date DEFAULT NULL,
  `etat` int(11) DEFAULT '0',
  `creusr` int(11) DEFAULT NULL,
  `credat` datetime DEFAULT CURRENT_TIMESTAMP,
  `updusr` int(11) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `complement_facture`
--

INSERT INTO `complement_facture` (`id`, `designation`, `idfacture`, `montant`, `type`, `date_complement`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(2, 'Arret du service pour 3 jours', 77, -50000, 'Réduction', '2017-10-17', 0, 1, '2017-10-17 02:16:19', NULL, NULL),
(3, 'Retard Paiement', 77, 10000, 'Pénalité', '2017-10-17', 0, 1, '2017-10-17 02:17:39', NULL, NULL),
(4, 'Geste commerciale ', 80, -100000, 'Réduction', '2017-10-17', 0, 24, '2017-10-17 14:04:00', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `contrats`
--

CREATE TABLE `contrats` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `tkn_frm` varchar(32) DEFAULT NULL,
  `ref` varchar(100) NOT NULL COMMENT 'Reference',
  `iddevis` int(11) DEFAULT NULL,
  `date_effet` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  `commentaire` text,
  `date_contrat` date DEFAULT NULL,
  `idtype_echeance` int(11) DEFAULT NULL,
  `periode_fact` varchar(50) DEFAULT NULL COMMENT 'periode de facturation Debut ou fin du mois',
  `date_notif` date DEFAULT NULL,
  `pj` int(11) DEFAULT NULL,
  `pj_photo` int(11) DEFAULT NULL,
  `contrats_pdf` int(11) DEFAULT NULL,
  `etat` int(11) DEFAULT '0',
  `creusr` int(11) DEFAULT NULL,
  `credat` datetime DEFAULT CURRENT_TIMESTAMP,
  `updusr` int(11) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `contrats`
--

INSERT INTO `contrats` (`id`, `tkn_frm`, `ref`, `iddevis`, `date_effet`, `date_fin`, `commentaire`, `date_contrat`, `idtype_echeance`, `periode_fact`, `date_notif`, `pj`, `pj_photo`, `contrats_pdf`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(32, '4fc1e848e77269ae2d5e4ccf8df29bac', 'GT-CTR-1/2017', 38, '2017-10-17', '2018-10-20', '<p>RAS<br></p>', '2017-10-17', 2, 'D', '2017-10-31', 525, NULL, 526, 1, 1, '2017-10-17 00:14:14', 1, '2017-10-17 01:24:57'),
(33, '911a17b4fd39f3109cddd5a6a337bc38', 'GT-CTR-2/2017', 40, '2016-10-17', '2018-10-17', NULL, '2017-10-17', 1, 'F', '2017-10-18', NULL, NULL, 527, 1, 22, '2017-10-17 01:31:54', 22, '2017-10-17 01:32:00'),
(34, 'f8d8bc3b498339793dd75bb61e75b49b', 'GT-CTR-3/2017', 41, '2017-10-17', '2017-11-04', NULL, '2017-10-17', 4, 'D', '2017-10-19', NULL, NULL, 530, 1, 1, '2017-10-17 01:59:26', 1, '2017-10-17 02:06:56'),
(35, '1aff93505a244129b7f3c8cb1b49f9ab', 'GT-CTR-4/2017', 42, '2017-10-17', '2018-10-17', '<p>Article 1:Objet du contrat&nbsp;</p><p> Le present à pour objet la fourniture d\'une bande passante de 1024/256kbps sur ASTRA 4A pour une durée de 12 mois;</p><p>Article 2: Modalité de paiement </p><p>Le paiement de la bande passante se fait trimestriellement à l\'avance. <br></p>', '2017-10-17', 3, 'D', '2018-09-17', NULL, NULL, 534, 1, 24, '2017-10-17 13:53:23', 24, '2017-10-17 13:54:39');

-- --------------------------------------------------------

--
-- Structure de la table `contrats_frn`
--

CREATE TABLE `contrats_frn` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `reference` varchar(100) NOT NULL COMMENT 'Reference',
  `id_fournisseur` int(11) DEFAULT NULL,
  `date_effet` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  `commentaire` text,
  `date_notif` date DEFAULT NULL,
  `pj` int(11) DEFAULT NULL,
  `pj_photo` int(11) DEFAULT NULL,
  `etat` int(11) DEFAULT '0',
  `creusr` int(11) DEFAULT NULL,
  `credat` datetime DEFAULT CURRENT_TIMESTAMP,
  `updusr` int(11) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `devis`
--

CREATE TABLE `devis` (
  `id` int(11) NOT NULL,
  `tkn_frm` varchar(32) DEFAULT NULL COMMENT 'Token Form insert',
  `reference` varchar(20) DEFAULT NULL,
  `projet` varchar(200) DEFAULT NULL COMMENT 'Desgnation projet',
  `id_client` int(11) DEFAULT NULL,
  `tva` varchar(1) DEFAULT 'O' COMMENT 'Soumis à la TVA',
  `id_commercial` int(11) DEFAULT NULL COMMENT 'commercial chargé du suivi',
  `date_devis` date DEFAULT NULL,
  `type_remise` varchar(1) DEFAULT 'P' COMMENT 'type de remise',
  `valeur_remise` double DEFAULT '0' COMMENT 'Valeur de la remise',
  `totalht` double DEFAULT '0' COMMENT 'total ht des articles',
  `totalttc` double DEFAULT '0' COMMENT 'total ttc des articles',
  `totaltva` double DEFAULT '0' COMMENT 'total tva des articles',
  `vie` int(11) DEFAULT NULL COMMENT 'Duree de vie',
  `claus_comercial` text COMMENT 'clauses commercial devis',
  `ref_bc` varchar(100) DEFAULT NULL COMMENT 'Ref bon commande client',
  `scan` varchar(60) DEFAULT NULL COMMENT 'Scan all doc',
  `devis_pdf` int(11) DEFAULT NULL,
  `etat` int(11) DEFAULT '0' COMMENT 'Etat devis defaut 0',
  `creusr` int(11) DEFAULT NULL,
  `credat` datetime DEFAULT NULL,
  `updusr` int(11) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `devis`
--

INSERT INTO `devis` (`id`, `tkn_frm`, `reference`, `projet`, `id_client`, `tva`, `id_commercial`, `date_devis`, `type_remise`, `valeur_remise`, `totalht`, `totalttc`, `totaltva`, `vie`, `claus_comercial`, `ref_bc`, `scan`, `devis_pdf`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(38, '11e417207dc033ff6ea5626b5422ef50', 'GT_DEV-0001/2017', 'Projet', 24, 'O', 23, '2017-10-16', 'P', NULL, 950000, 1121000, 171000, 30, 'Paiement 100% à la commande', 'SS12456789', NULL, 522, 4, 20, '2017-10-16 22:22:29', 23, '2017-10-16 23:38:07'),
(39, '12bd3fcd1f0066f9fe6303fbd592adf1', 'GT_DEV-0002/2017', 'Test Encore', 24, 'O', 20, '2017-10-16', 'P', NULL, 1000000, 1180000, 180000, 30, 'Paiement 100% à la commande', NULL, NULL, 523, 5, 20, '2017-10-16 22:23:07', NULL, NULL),
(40, '5458a55111c0033b710ad7a43bf797cb', 'GT_DEV-0003/2017', 'Installation VSAT trache 1 N\'Djamena', 26, 'O', 2, '2017-10-16', 'P', NULL, 727000, 857860, 130860, 30, 'Paiement 100% à la commande', 'REF-BC-19990', NULL, 524, 4, 2, '2017-10-16 23:10:34', 2, '2017-10-16 23:23:22'),
(41, 'c5051f481e6f4cabbb51a4c751c1ead1', 'GT_DEV-0004/2017', 'FAYA', 24, 'O', 1, '2017-10-17', 'P', NULL, 1120000, 1321600, 201600, 30, 'Paiement 100% à la commande', 'REFBC', NULL, 528, 4, 1, '2017-10-17 01:52:04', NULL, NULL),
(42, '9ca3399cf28c1b32ff9f2f0baec534f0', 'GT_DEV-0005/2017', NULL, 27, 'N', 24, '2017-10-17', 'P', NULL, 175000, 175000, 0, 30, '<p>Paiement 100% à la commande</p><p>Le délais de livraison se féras dans 07 jours <br></p>', '', NULL, 533, 4, 24, '2017-10-17 13:20:05', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `d_devis`
--

CREATE TABLE `d_devis` (
  `id` int(11) NOT NULL,
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
  `upddat` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `d_devis`
--

INSERT INTO `d_devis` (`id`, `order`, `id_devis`, `tkn_frm`, `id_produit`, `ref_produit`, `designation`, `qte`, `prix_unitaire`, `type_remise`, `remise_valeur`, `tva`, `prix_ht`, `prix_ttc`, `total_ht`, `total_ttc`, `total_tva`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(159, 1, 37, 'e5232dc32fb69c2e6499171e3caaba8d', 25, 'GT-PRD-4/2017', 'Connexion  Vsat 1 Mo', 1, 1000000, 'P', 0, 0, NULL, NULL, 1000000, 1180000, 180000, '1', NULL, NULL, NULL),
(160, 1, NULL, '2023269925606feebda3d5911de38f5e', 25, 'GT-PRD-4/2017', 'Connexion  Vsat 1 Mo', 1, 1000000, 'P', 0, 0, NULL, NULL, 1000000, 1180000, 180000, '1', NULL, NULL, NULL),
(161, 1, 38, '11e417207dc033ff6ea5626b5422ef50', 25, 'GT-PRD-4/2017', 'Connexion  Vsat 1 Mo', 1, 1000000, 'P', 5, 0, NULL, NULL, 950000, 1121000, 171000, '20', NULL, '23', '2017-10-16 23:38:03'),
(162, 1, 39, '12bd3fcd1f0066f9fe6303fbd592adf1', 25, 'GT-PRD-4/2017', 'Connexion  Vsat 1 Mo', 1, 1000000, 'P', 0, 0, NULL, NULL, 1000000, 1180000, 180000, '20', NULL, NULL, NULL),
(163, 1, 40, '5458a55111c0033b710ad7a43bf797cb', 26, 'GT-PRD-5/2017', 'Installation Site', 1, 50000, 'P', -10, 0, NULL, NULL, 55000, 64900, 9900, '2', NULL, NULL, NULL),
(166, 1, NULL, 'adb7195a9e2ac8f2b92ef28399823c17', 22, 'GT-PRD-1/2017', 'LNB PLL bande C', 1, 150000, 'P', 0, 0, NULL, NULL, 150000, 177000, 27000, '2', NULL, NULL, NULL),
(167, 1, 41, 'c5051f481e6f4cabbb51a4c751c1ead1', 23, 'GT-PRD-2/2017', 'Modem iDirect Evolution X3', 1, 1120000, 'P', 0, 0, NULL, NULL, 1120000, 1321600, 201600, '1', NULL, NULL, NULL),
(168, 1, 42, '9ca3399cf28c1b32ff9f2f0baec534f0', 28, 'GT-PRD-6/2017', '1024/256kbps Astra4A', 1, 175000, 'P', 0, 0, NULL, NULL, 175000, 175000, 0, '24', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `d_proforma`
--

CREATE TABLE `d_proforma` (
  `id` int(11) NOT NULL,
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
  `upddat` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `d_proforma`
--

INSERT INTO `d_proforma` (`id`, `order`, `id_proforma`, `tkn_frm`, `id_produit`, `ref_produit`, `designation`, `qte`, `prix_unitaire`, `type_remise`, `remise_valeur`, `tva`, `prix_ht`, `prix_ttc`, `total_ht`, `total_ttc`, `total_tva`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(198, 1, 30, 'ceeb410b47f8253ce9bd70f989c6ec88', 25, 'GT-PRD-4/2017', 'Connexion  Vsat 1 Mo', 1, 1000000, 'P', 5, 0, NULL, NULL, 950000, 1121000, 171000, '23', NULL, NULL, NULL),
(199, 1, 31, 'fe2afb3dc9e5675281199526c97bb4cf', 26, 'GT-PRD-5/2017', 'Installation Site', 1, 50000, 'P', 2, 0, NULL, NULL, 49000, 57820, 8820, '23', NULL, NULL, NULL),
(200, 1, 32, '6d50c057765b9b9966330a3fedef1836', 22, 'GT-PRD-1/2017', 'LNB PLL bande C', 1, 150000, 'P', 10, 0, NULL, NULL, 135000, 135000, 0, '23', NULL, NULL, NULL),
(201, 2, 32, '6d50c057765b9b9966330a3fedef1836', 26, 'GT-PRD-5/2017', 'Installation Site', 1, 50000, 'P', 20, 0, NULL, NULL, 40000, 40000, 0, '2', NULL, NULL, NULL),
(202, 1, 33, 'e974dbf42ed364ae632fb42b50b4c473', 25, 'GT-PRD-4/2017', 'Connexion  Vsat 1 Mo', 1, 1000000, 'P', 0, 0, NULL, NULL, 1000000, 1180000, 180000, '2', NULL, NULL, NULL),
(203, 1, NULL, '9f52fb0a56f9b26b44a371d34cbe864c', 25, 'GT-PRD-4/2017', 'Connexion  Vsat 1 Mo', 1, 1000000, 'P', 0, 0, NULL, NULL, 1000000, 1180000, 180000, '1', NULL, NULL, NULL),
(204, 1, 34, '45861c7a059c1622cbe15382631f1ac6', 24, 'GT-PRD-3/2017', 'Antenne VSAT 1.2m bande Ku Skyware Globa', 2, 700000, 'P', 3, 0, NULL, NULL, 1358000, 1602440, 244440, '24', NULL, NULL, NULL),
(205, 2, 34, '45861c7a059c1622cbe15382631f1ac6', 22, 'GT-PRD-1/2017', 'LNB PLL bande C', 1, 150000, 'P', 0, 0, NULL, NULL, 150000, 177000, 27000, '24', NULL, NULL, NULL),
(206, 3, 34, '45861c7a059c1622cbe15382631f1ac6', 28, 'GT-PRD-6/2017', '1024/256kbps Astra4A', 1, 175000, 'P', 5, 0, NULL, NULL, 166250, 196175, 29925, '24', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `echeances_contrat`
--

CREATE TABLE `echeances_contrat` (
  `id` int(11) NOT NULL,
  `tkn_frm` varchar(32) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `date_echeance` date NOT NULL,
  `montant` double NOT NULL COMMENT 'le montant a payer',
  `commentaire` varchar(2000) DEFAULT NULL,
  `idcontrat` int(11) DEFAULT NULL,
  `etat` int(11) DEFAULT '0',
  `creusr` int(11) DEFAULT NULL,
  `credat` datetime DEFAULT CURRENT_TIMESTAMP,
  `updusr` int(11) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `echeances_contrat`
--

INSERT INTO `echeances_contrat` (`id`, `tkn_frm`, `order`, `date_echeance`, `montant`, `commentaire`, `idcontrat`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(39, 'f8d8bc3b498339793dd75bb61e75b49b', 3, '2017-10-17', 1000000, NULL, 34, 0, 1, '2017-10-17 01:57:47', NULL, NULL),
(40, 'f8d8bc3b498339793dd75bb61e75b49b', 4, '2017-10-26', 321600, NULL, 34, 0, 1, '2017-10-17 01:59:02', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `encaissements`
--

CREATE TABLE `encaissements` (
  `id` int(11) NOT NULL,
  `ref` varchar(20) DEFAULT NULL,
  `designation` varchar(100) DEFAULT NULL,
  `idfacture` int(11) DEFAULT NULL,
  `montant` int(11) DEFAULT NULL,
  `pj` int(11) DEFAULT NULL,
  `date_encaissement` date DEFAULT NULL,
  `etat` int(11) DEFAULT '0',
  `creusr` int(11) DEFAULT NULL,
  `credat` datetime DEFAULT CURRENT_TIMESTAMP,
  `updusr` int(11) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL,
  `mode_payement` double DEFAULT NULL,
  `ref_payement` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `encaissements`
--

INSERT INTO `encaissements` (`id`, `ref`, `designation`, `idfacture`, `montant`, `pj`, `date_encaissement`, `etat`, `creusr`, `credat`, `updusr`, `upddat`, `mode_payement`, `ref_payement`) VALUES
(2, 'GT-ENC-1/2017', 'Paiement récu', 77, 1000000, NULL, '2017-10-17', 0, 1, '2017-10-17 02:20:17', NULL, NULL, 0, '123456789'),
(3, 'GT-ENC-2/2017', 'Paiement ', 77, 81000, NULL, '2017-10-17', 0, 1, '2017-10-17 02:21:37', NULL, NULL, 0, '1245697'),
(4, 'GT-ENC-3/2017', 'Paiement par chèque ', 80, 325000, NULL, '2017-10-17', 0, 24, '2017-10-17 14:07:32', NULL, NULL, 0, '23566'),
(5, 'GT-ENC-4/2017', 'Paiement par chèque', 80, 100000, NULL, '2017-10-17', 0, 24, '2017-10-17 14:08:56', NULL, NULL, 0, '23566');

-- --------------------------------------------------------

--
-- Structure de la table `espionnage_update`
--

CREATE TABLE `espionnage_update` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `updt_id` varchar(32) NOT NULL COMMENT 'ID of Update operation',
  `table` varchar(25) NOT NULL COMMENT 'Table espionné',
  `id_item` int(11) NOT NULL COMMENT 'ID de ligne modifié',
  `column` varchar(25) NOT NULL COMMENT 'Column modifié',
  `val_old` varchar(200) DEFAULT NULL COMMENT 'Valeur avant',
  `val_new` varchar(200) DEFAULT NULL COMMENT 'Valeur Aprés',
  `user` varchar(25) NOT NULL COMMENT 'Utilisateur modif',
  `updtdat` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date modification'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `espionnage_update`
--

INSERT INTO `espionnage_update` (`id`, `updt_id`, `table`, `id_item`, `column`, `val_old`, `val_new`, `user`, `updtdat`) VALUES
(1, 'cfb6d23f1256fade1d1ea90b5b81adf2', 'users_sys', 19, 'pass', '7480d19b8e17e2f55de992feda6a74a6', 'd41d8cd98f00b204e9800998ecf8427e', 'admin', '2017-09-13 15:57:03'),
(2, '4d364b15758dba08fe919dfce0cb9cfb', 'users_sys', 18, 'pass', '5a05679021426829ab75ac9fa6655947', 'd41d8cd98f00b204e9800998ecf8427e', 'admin', '2017-09-13 16:17:30'),
(3, 'f9a57ac082f6e22249c0d3172f1693f0', 'devis', 27, 'date_devis', '09-10-2017', '2017-10-09', 'admin', '2017-10-09 14:57:22'),
(4, 'bc64d92371a0e46d83aa1524de719302', 'd_devis', 150, 'ref_produit', 'a_skyware_1.2m Ku-Ba', 'a_skyware_1.2m Ku-Band_122', 'admin', '2017-10-09 15:05:38'),
(5, 'bc64d92371a0e46d83aa1524de719302', 'd_devis', 150, 'designation', 'Antenne VSAT 1.2m bande Ku Skyware Globa', 'Antenne VSAT 1.2m bande Ku Skyware Global', 'admin', '2017-10-09 15:05:38'),
(6, 'bc64d92371a0e46d83aa1524de719302', 'd_devis', 150, 'qte', '78', '5', 'admin', '2017-10-09 15:05:38'),
(7, 'bc64d92371a0e46d83aa1524de719302', 'd_devis', 150, 'tva', '0', 'O', 'admin', '2017-10-09 15:05:38'),
(8, 'bc64d92371a0e46d83aa1524de719302', 'd_devis', 150, 'total_ht', '39000', '2500', 'admin', '2017-10-09 15:05:38'),
(9, 'bc64d92371a0e46d83aa1524de719302', 'd_devis', 150, 'total_ttc', '46020', '2950', 'admin', '2017-10-09 15:05:38'),
(10, 'bc64d92371a0e46d83aa1524de719302', 'd_devis', 150, 'total_tva', '7020', '450', 'admin', '2017-10-09 15:05:38'),
(11, '090bcf49b60941348eb4ecf7e5b6a727', 'ref_unites_vente', 2, 'unite_vente', 'FTFT', 'Mois', 'admin', '2017-10-10 02:16:48'),
(12, '7a1b7ad6d470b656d23fe3051a0747f2', 'devis', 23, 'tva', 'O', 'N', 'admin', '2017-10-10 10:52:33'),
(13, '7a1b7ad6d470b656d23fe3051a0747f2', 'devis', 23, 'date_devis', '14-09-2017', '2017-10-10', 'admin', '2017-10-10 10:52:33'),
(14, '7a1b7ad6d470b656d23fe3051a0747f2', 'devis', 23, 'totalht', '1455454', '1555454', 'admin', '2017-10-10 10:52:33'),
(15, '7a1b7ad6d470b656d23fe3051a0747f2', 'devis', 23, 'totalttc', '1455454', '1555454', 'admin', '2017-10-10 10:52:33'),
(16, 'e0cdaaf556b9f850a890c5ebc467c5b4', 'devis', 34, 'date_devis', '10-10-2017', '2017-10-10', 'admin', '2017-10-10 12:07:29'),
(17, 'e0cdaaf556b9f850a890c5ebc467c5b4', 'devis', 34, 'totalht', '100000', '400000', 'admin', '2017-10-10 12:07:29'),
(18, 'e0cdaaf556b9f850a890c5ebc467c5b4', 'devis', 34, 'totalttc', '118000', '472000', 'admin', '2017-10-10 12:07:29'),
(19, 'e0cdaaf556b9f850a890c5ebc467c5b4', 'devis', 34, 'totaltva', '18000', '72000', 'admin', '2017-10-10 12:07:29'),
(20, '52c9daf8c3904ef962289e4b25b930d8', 'users_sys', 19, 'nom', 'atoub', 'ayoub', 'admin', '2017-10-11 00:45:38'),
(21, '52c9daf8c3904ef962289e4b25b930d8', 'users_sys', 19, 'pass', '5a05679021426829ab75ac9fa6655947', 'd41d8cd98f00b204e9800998ecf8427e', 'admin', '2017-10-11 00:45:38'),
(22, 'd244f03ab9c75a604413f6bc36bc2cc8', 'users_sys', 19, 'pass', 'd41d8cd98f00b204e9800998ecf8427e', '5a05679021426829ab75ac9fa6655947', 'admin', '2017-10-11 00:47:17'),
(23, '36d2964f33795231882a9224b93531d5', 'produits', 25, 'designation', 'Connexion 1Mo Vsat', 'Connexion  Vsat 1 Mo', 'ayoub', '2017-10-11 10:59:32'),
(24, 'ab4360946bb3700bd7f326492f88f4e0', 'clients', 24, 'denomination', 'FFA', '50FED', 'ayoub', '2017-10-11 12:54:05'),
(25, 'ab4360946bb3700bd7f326492f88f4e0', 'clients', 24, 'r_social', 'Fifo Fed', 'Fifteen Fed', 'ayoub', '2017-10-11 12:54:05'),
(26, 'ab4360946bb3700bd7f326492f88f4e0', 'clients', 24, 'tva', 'OUI', NULL, 'ayoub', '2017-10-11 12:54:05'),
(27, 'c2bde21d86caf60d82e953fd94b5416a', 'clients', 24, 'tva', 'NON', 'O', 'ayoub', '2017-10-11 13:05:02'),
(28, '840cc38eda956446eefe959acc81839c', 'users_sys', 2, 'nom', 'rachid', 'fati', 'admin', '2017-10-13 20:10:32'),
(29, '840cc38eda956446eefe959acc81839c', 'users_sys', 2, 'mail', 'rachid@bdctchad.com', 'fatimazahra@dctchad.com', 'admin', '2017-10-13 20:10:32'),
(30, '840cc38eda956446eefe959acc81839c', 'users_sys', 2, 'pass', '5a05679021426829ab75ac9fa6655947', 'b305f33ba282cf1747e78f3a48346cb1', 'admin', '2017-10-13 20:10:32'),
(31, '840cc38eda956446eefe959acc81839c', 'users_sys', 2, 'fnom', 'Rachid', 'Fatima Zahra', 'admin', '2017-10-13 20:10:32'),
(32, '840cc38eda956446eefe959acc81839c', 'users_sys', 2, 'lnom', 'Kada', 'HANOUNOU', 'admin', '2017-10-13 20:10:32'),
(33, '840cc38eda956446eefe959acc81839c', 'users_sys', 2, 'tel', '0612668698', '0674471151', 'admin', '2017-10-13 20:10:32'),
(34, '393ecb0ab4be09ed2a854995a4319005', 'users_sys', 19, 'pass', '5a05679021426829ab75ac9fa6655947', 'd41d8cd98f00b204e9800998ecf8427e', 'admin', '2017-10-13 20:11:09'),
(35, '393ecb0ab4be09ed2a854995a4319005', 'users_sys', 19, 'service', '3', '5', 'admin', '2017-10-13 20:11:09'),
(36, '1c637ff110f31555e90770fe78a17634', 'users_sys', 19, 'pass', 'd41d8cd98f00b204e9800998ecf8427e', '5a05679021426829ab75ac9fa6655947', 'admin', '2017-10-13 20:13:19'),
(37, '3f1ad6af87b10757263742af37babd16', 'users_sys', 2, 'pass', 'b305f33ba282cf1747e78f3a48346cb1', 'd41d8cd98f00b204e9800998ecf8427e', 'admin', '2017-10-13 20:22:52'),
(38, '66096f72d58f8cf1d572af1ade295667', 'users_sys', 2, 'pass', 'd41d8cd98f00b204e9800998ecf8427e', '5a05679021426829ab75ac9fa6655947', 'admin', '2017-10-13 20:23:42'),
(39, 'c403d3a53f39468388d8ae8138f0ecbc', 'd_proforma', 194, 'qte', '1', '10', 'admin', '2017-10-15 23:14:23'),
(40, 'c403d3a53f39468388d8ae8138f0ecbc', 'd_proforma', 194, 'tva', '0', 'O', 'admin', '2017-10-15 23:14:23'),
(41, 'c403d3a53f39468388d8ae8138f0ecbc', 'd_proforma', 194, 'total_ht', '1000000', '10000000', 'admin', '2017-10-15 23:14:23'),
(42, 'c403d3a53f39468388d8ae8138f0ecbc', 'd_proforma', 194, 'total_ttc', '1180000', '11800000', 'admin', '2017-10-15 23:14:23'),
(43, 'c403d3a53f39468388d8ae8138f0ecbc', 'd_proforma', 194, 'total_tva', '180000', '1800000', 'admin', '2017-10-15 23:14:23'),
(44, '039318a2978fbb2a2953cb33ddc973f1', 'proforma', 1, 'date_proforma', '15-10-2017', '2017-10-15', 'admin', '2017-10-15 23:14:38'),
(45, '7f5905f736ca801c3900b79c56d447af', 'produits', 27, 'designation', 'okk', 'okkjj', 'fati', '2017-10-16 21:57:24'),
(46, '733dd34af3117d11ac2042c827a808be', 'proforma', 1, 'tva', 'O', 'N', 'fati', '2017-10-16 22:45:13'),
(47, '733dd34af3117d11ac2042c827a808be', 'proforma', 1, 'id_commercial', '23', '2', 'fati', '2017-10-16 22:45:13'),
(48, '733dd34af3117d11ac2042c827a808be', 'proforma', 1, 'date_proforma', '16-10-2017', '2017-10-16', 'fati', '2017-10-16 22:45:13'),
(49, '51ad101805323814f6af1efbbff789bd', 'devis', 40, 'tva', 'N', 'O', 'fati', '2017-10-16 23:20:02'),
(50, '51ad101805323814f6af1efbbff789bd', 'devis', 40, 'date_devis', '16-10-2017', '2017-10-16', 'fati', '2017-10-16 23:20:02'),
(51, '51ad101805323814f6af1efbbff789bd', 'devis', 40, 'projet', 'Installation VSAT trache 1 N\'Djamena', '0', 'fati', '2017-10-16 23:20:02'),
(52, '51ad101805323814f6af1efbbff789bd', 'devis', 40, 'totalttc', '727000', '857860', 'fati', '2017-10-16 23:20:02'),
(53, '51ad101805323814f6af1efbbff789bd', 'devis', 40, 'totaltva', '0', '130860', 'fati', '2017-10-16 23:20:02'),
(54, 'a844503013afc92ca00d4c56a4ed6ba6', 'devis', 40, 'date_devis', '16-10-2017', '2017-10-16', 'fati', '2017-10-16 23:23:22'),
(55, 'a844503013afc92ca00d4c56a4ed6ba6', 'devis', 40, 'projet', '0', 'Installation VSAT trache 1 N\'Djamena', 'fati', '2017-10-16 23:23:22'),
(56, '641a70d73789c0154bc92fb0a24e7483', 'd_devis', 161, 'remise_valeur', '0', '5', 'ayoubadmin', '2017-10-16 23:38:03'),
(57, '641a70d73789c0154bc92fb0a24e7483', 'd_devis', 161, 'tva', '0', 'O', 'ayoubadmin', '2017-10-16 23:38:03'),
(58, '641a70d73789c0154bc92fb0a24e7483', 'd_devis', 161, 'total_ht', '1000000', '950000', 'ayoubadmin', '2017-10-16 23:38:03'),
(59, '641a70d73789c0154bc92fb0a24e7483', 'd_devis', 161, 'total_ttc', '1180000', '1121000', 'ayoubadmin', '2017-10-16 23:38:03'),
(60, '641a70d73789c0154bc92fb0a24e7483', 'd_devis', 161, 'total_tva', '180000', '171000', 'ayoubadmin', '2017-10-16 23:38:03'),
(61, '106b5065846828b1894cab205daa6dfa', 'devis', 38, 'id_commercial', '20', '23', 'ayoubadmin', '2017-10-16 23:38:07'),
(62, '106b5065846828b1894cab205daa6dfa', 'devis', 38, 'date_devis', '16-10-2017', '2017-10-16', 'ayoubadmin', '2017-10-16 23:38:07'),
(63, '106b5065846828b1894cab205daa6dfa', 'devis', 38, 'totalht', '1000000', '950000', 'ayoubadmin', '2017-10-16 23:38:07'),
(64, '106b5065846828b1894cab205daa6dfa', 'devis', 38, 'totalttc', '1180000', '1121000', 'ayoubadmin', '2017-10-16 23:38:07'),
(65, '106b5065846828b1894cab205daa6dfa', 'devis', 38, 'totaltva', '180000', '171000', 'ayoubadmin', '2017-10-16 23:38:07'),
(66, '7342a1cdba36f91c5408535d5f4e52d9', 'contrats', 32, 'date_effet', '2017-10-17', '2017-10-20', 'admin', '2017-10-17 01:24:40'),
(67, '7342a1cdba36f91c5408535d5f4e52d9', 'contrats', 32, 'date_fin', '2018-10-17', '2018-10-20', 'admin', '2017-10-17 01:24:40');

-- --------------------------------------------------------

--
-- Structure de la table `factures`
--

CREATE TABLE `factures` (
  `id` int(11) NOT NULL,
  `ref` varchar(50) DEFAULT NULL,
  `base_fact` char(1) DEFAULT NULL COMMENT 'C/D/B Contrat/Devis/BL',
  `total_ht` double DEFAULT NULL,
  `total_tva` double DEFAULT NULL,
  `total_ttc_initial` double DEFAULT NULL,
  `total_ttc` double DEFAULT NULL,
  `total_paye` double DEFAULT NULL COMMENT 'Le total payé',
  `reste` double DEFAULT NULL COMMENT 'reste à payer',
  `client` varchar(100) DEFAULT NULL,
  `projet` varchar(200) DEFAULT NULL COMMENT 'designation projet',
  `ref_bc` varchar(200) DEFAULT NULL COMMENT 'ref bon commande client',
  `tva` double DEFAULT NULL,
  `idcontrat` int(11) DEFAULT NULL COMMENT 'Contrat',
  `iddevis` int(11) DEFAULT NULL COMMENT 'Devis',
  `idbl` int(11) DEFAULT NULL COMMENT 'Bon de livraison',
  `date_facture` date DEFAULT NULL,
  `du` date DEFAULT NULL COMMENT 'debut periode facture',
  `au` date DEFAULT NULL COMMENT 'fin periode facture',
  `etat` int(11) DEFAULT '0',
  `creusr` int(11) DEFAULT NULL,
  `credat` datetime DEFAULT CURRENT_TIMESTAMP,
  `updusr` int(11) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `factures`
--

INSERT INTO `factures` (`id`, `ref`, `base_fact`, `total_ht`, `total_tva`, `total_ttc_initial`, `total_ttc`, `total_paye`, `reste`, `client`, `projet`, `ref_bc`, `tva`, `idcontrat`, `iddevis`, `idbl`, `date_facture`, `du`, `au`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(77, 'GT-FCT-1/2017', 'C', 950000, 171000, 1121000, 1081000, 1081000, 0, '50FED', 'Projet', 'SS12456789', NULL, 32, NULL, NULL, '2017-10-17', '2017-10-17', '2017-11-17', 4, 1, '2017-10-17 02:07:20', NULL, NULL),
(78, 'GT-FCT-2/2017', 'C', 8724000, 1570320, 10294320, 10294320, 0, 10294320, 'GSI', 'Installation VSAT trache 1 N\'Djamena', 'REF-BC-19990', NULL, 33, NULL, NULL, '2017-10-17', '2016-10-17', '2017-10-17', 0, 1, '2017-10-17 02:07:20', NULL, NULL),
(79, 'GT-FCT-3/2017', 'C', 820000, 180000, 1000000, 1000000, 0, 1000000, '50FED', 'FAYA', 'REFBC', NULL, 34, NULL, NULL, '2017-10-17', '2017-10-17', '2017-10-17', 0, 1, '2017-10-17 02:07:20', NULL, NULL),
(80, 'GT-FCT-4/2017', 'C', 525000, 0, 525000, 425000, 425000, 0, 'DCT', NULL, '', NULL, 35, NULL, NULL, '2017-10-17', '2017-10-17', '2018-01-17', 4, 1, '2017-10-17 14:01:18', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `forgot`
--

CREATE TABLE `forgot` (
  `token` varchar(32) CHARACTER SET latin1 NOT NULL,
  `user` int(2) NOT NULL,
  `etat` int(11) NOT NULL,
  `dat` datetime NOT NULL,
  `expir` datetime NOT NULL,
  `id` int(11) NOT NULL COMMENT 'Id demande Forgot',
  `ip` varchar(16) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Ip Demande'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table recovery MDP user';

--
-- Déchargement des données de la table `forgot`
--

INSERT INTO `forgot` (`token`, `user`, `etat`, `dat`, `expir`, `id`, `ip`) VALUES
('c15b7f525c0451bc9fdcc379aca9e3d7', 19, 0, '2017-10-11 00:49:02', '2017-10-13 00:49:02', 1, '197.159.16.2');

-- --------------------------------------------------------

--
-- Structure de la table `fournisseurs`
--

CREATE TABLE `fournisseurs` (
  `id` int(11) NOT NULL COMMENT 'ID',
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
  `id_ville` int(11) DEFAULT NULL COMMENT 'Ville',
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
  `upddat` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `modul`
--

CREATE TABLE `modul` (
  `id` int(11) NOT NULL COMMENT 'id modul',
  `modul` varchar(25) CHARACTER SET latin1 NOT NULL COMMENT 'nom modul',
  `description` varchar(100) CHARACTER SET latin1 NOT NULL COMMENT 'Description',
  `rep_modul` varchar(100) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Répertoir module',
  `tables` varchar(100) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Tables utlisées par le module',
  `app_modul` varchar(25) CHARACTER SET latin1 NOT NULL COMMENT 'Application de base',
  `modul_setting` varchar(25) CHARACTER SET latin1 DEFAULT 'NA' COMMENT 'Si is_setting Choix modul',
  `is_setting` tinyint(1) DEFAULT '0' COMMENT 'Modul de parametre',
  `etat` int(11) NOT NULL DEFAULT '0' COMMENT 'Etat de module',
  `services` varchar(40) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Services de Module'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table Systeme Modules';

--
-- Déchargement des données de la table `modul`
--

INSERT INTO `modul` (`id`, `modul`, `description`, `rep_modul`, `tables`, `app_modul`, `modul_setting`, `is_setting`, `etat`, `services`) VALUES
(1, 'Systeme', 'Applications utilises par le Systeme', 'tdb', NULL, 'tdb', NULL, 0, 10, '[-1-]'),
(3, 'services', 'Services', 'users/settings/services', 'services', 'services', 'users', 1, 0, '[-1-2-3-]'),
(5, 'sys_setting', 'Paramètrage Système', 'Systeme/settings/sys_setting', 'sys', 'sys_setting', 'Systeme', 1, 0, '[-1-]'),
(6, 'pays', 'Gestion Pays', 'Systeme/settings/pays', 'ref_pays', 'pays', 'Systeme', 1, 0, '[-1-2-3-]'),
(7, 'regions', 'Gestion des régions', 'Systeme/settings/regions', 'ref_region', 'regions', 'Systeme', 1, 0, '[-1-2-3-]'),
(8, 'villes', 'Gestion Villes', 'Systeme/settings/villes', 'ref_villes', 'villes', 'Systeme', 1, 0, '[-1-2-3-]'),
(9, 'departements', 'Gestion Départements', 'Systeme/settings/departements', 'ref_departement', 'departements', 'Systeme', 1, 0, '[-1-2-3-]'),
(77, 'categorie_client', 'Gestion Catégorie Client', 'clients/settings/categorie_client', 'categorie_client', 'categorie_client', 'clients', 1, 0, '[-1-2-3-]'),
(87, 'clients', 'Gestion Clients', 'clients', 'clients', 'clients', NULL, 0, 0, '[-1-2-3-]'),
(93, 'vente', 'Gestion Vente', 'vente/main', 'devis', 'vente', NULL, 0, 0, '[-1-2-3-5-]'),
(98, 'categories_produits', 'Gestion des catégories de produits', 'produits/settings/categories_produits', 'ref_categories_produits', 'categories_produits', 'produits', 1, 0, '[-1-2-3-]'),
(99, 'types_produits', 'Gestion des types de produits', 'produits/settings/types_produits', 'ref_types_produits', 'types_produits', 'produits', 1, 0, '[-1-2-3-]'),
(100, 'unites_vente', 'Gestion des unités de vente', 'produits/settings/unites_vente', 'ref_unites_vente', 'unites_vente', 'produits', 1, 0, '[-1-2-3-]'),
(103, 'type_echeance', 'Gestion Type Echeance', 'contrats/settings/type_echeance', 'ref_type_echeance', 'type_echeance', 'contrats', 1, 0, '[-1-2-3-]'),
(107, 'fournisseurs', 'Gestion Fournisseurs', 'fournisseurs/main', 'fournisseurs', 'fournisseurs', NULL, 0, 0, '[-1-2-3-]'),
(111, 'info_ste', 'Information société', 'Systeme/settings/info_ste', 'ste_info', 'info_ste', 'Systeme', 1, 0, '[-1-2-]'),
(117, 'contrats_fournisseurs', 'Contrats Fournisseur', 'contrats_fournisseurs/main', 'contrats_frn', 'contrats_fournisseurs', NULL, 0, 0, '[-1-2-3-]'),
(120, 'modul_mgr', 'Modules', 'modul_mgr', 'modul,task,task_action', 'modul', NULL, 0, 0, '[-1-]'),
(121, 'devis', 'Gestion Devis', 'vente/submodul/devis', 'devis', 'devis', 'vente', 2, 0, '[-1-2-3-5-]'),
(123, 'factures', 'Gestion des factures', 'factures/main', 'factures', 'factures', NULL, 0, 0, '[-1-2-3-5-]'),
(125, 'contrats', 'Abonnements', 'vente/submodul/contrats', 'contrats', 'contrats', 'vente', 2, 0, '[-1-2-3-5-]'),
(126, 'users', 'Utilisateurs', 'users', 'users_sys', 'user', NULL, 0, 0, '[-1-2-3-5-]'),
(127, 'proforma', 'Gestion Proforma', 'vente/submodul/proforma', 'proforma', 'proforma', 'vente', 2, 0, '[-1-2-3-5-4-]'),
(128, 'produits', 'Gestion des produits', 'produits', 'produits, ref_unites_vente, ref_categories_produits, ref_types_produits', 'produits', NULL, 0, 0, '[-1-2-3-]');

-- --------------------------------------------------------

--
-- Structure de la table `produits`
--

CREATE TABLE `produits` (
  `id` int(11) NOT NULL COMMENT 'Id',
  `ref` varchar(100) DEFAULT NULL COMMENT 'Reference produit',
  `designation` varchar(100) DEFAULT NULL COMMENT 'DÃ©signation',
  `stock_min` int(11) DEFAULT NULL COMMENT 'Stock minimal',
  `idcategorie` int(11) DEFAULT NULL COMMENT 'CatÃ©gorie',
  `iduv` int(11) DEFAULT NULL COMMENT 'UnitÃ© de vente',
  `idtype` int(11) DEFAULT NULL COMMENT 'Type produit',
  `prix_vente` double DEFAULT NULL COMMENT 'Prix si type produit= prestation ou abonnement',
  `etat` int(11) DEFAULT '0',
  `creusr` int(11) DEFAULT NULL,
  `credat` datetime DEFAULT CURRENT_TIMESTAMP,
  `updusr` int(11) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `produits`
--

INSERT INTO `produits` (`id`, `ref`, `designation`, `stock_min`, `idcategorie`, `iduv`, `idtype`, `prix_vente`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(22, 'GT-PRD-1/2017', 'LNB PLL bande C', 1, 9, 6, 1, 150000, 1, 19, '2017-10-11 10:47:16', 22, '2017-10-16 23:09:25'),
(23, 'GT-PRD-2/2017', 'Modem iDirect Evolution X3', 1, 8, 6, 1, 1120000, 1, 19, '2017-10-11 10:47:51', 19, '2017-10-11 12:57:36'),
(24, 'GT-PRD-3/2017', 'Antenne VSAT 1.2m bande Ku Skyware Global I', 1, 10, 6, 1, 700000, 1, 19, '2017-10-11 10:48:50', 19, '2017-10-11 12:57:45'),
(25, 'GT-PRD-4/2017', 'Connexion  Vsat 1 Mo', NULL, 11, 2, 3, 1000000, 1, 19, '2017-10-11 10:59:12', 19, '2017-10-11 11:33:21'),
(26, 'GT-PRD-5/2017', 'Installation Site', NULL, 12, 7, 2, 50000, 1, 19, '2017-10-11 11:02:47', 19, '2017-10-11 11:32:59'),
(28, 'GT-PRD-6/2017', '1024/256kbps Astra4A', 0, 13, 2, 3, 175000, 1, 24, '2017-10-17 12:53:55', 24, '2017-10-17 12:55:57');

-- --------------------------------------------------------

--
-- Structure de la table `proforma`
--

CREATE TABLE `proforma` (
  `id` int(11) NOT NULL,
  `tkn_frm` varchar(32) DEFAULT NULL COMMENT 'Token Form insert',
  `reference` varchar(20) DEFAULT NULL,
  `id_client` int(11) DEFAULT NULL,
  `tva` varchar(1) DEFAULT 'O' COMMENT 'Soumis à la TVA',
  `id_commercial` int(11) DEFAULT NULL COMMENT 'commercial chargé du suivi',
  `date_proforma` date DEFAULT NULL,
  `type_remise` varchar(1) DEFAULT 'P' COMMENT 'type de remise',
  `valeur_remise` double DEFAULT '0' COMMENT 'Valeur de la remise',
  `totalht` double DEFAULT '0' COMMENT 'total ht des articles',
  `totalttc` double UNSIGNED DEFAULT '0' COMMENT 'total ttc des articles',
  `totaltva` double DEFAULT '0' COMMENT 'total tva des articles',
  `vie` int(3) NOT NULL COMMENT 'Durée de vie',
  `claus_comercial` text COMMENT 'clauses commercial devis',
  `etat` int(11) DEFAULT '0' COMMENT 'Etat devis defaut 0',
  `proforma_pdf` int(11) DEFAULT NULL COMMENT 'Generated pdf (archive table)',
  `creusr` int(11) DEFAULT NULL,
  `credat` datetime DEFAULT NULL,
  `updusr` int(11) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `proforma`
--

INSERT INTO `proforma` (`id`, `tkn_frm`, `reference`, `id_client`, `tva`, `id_commercial`, `date_proforma`, `type_remise`, `valeur_remise`, `totalht`, `totalttc`, `totaltva`, `vie`, `claus_comercial`, `etat`, `proforma_pdf`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(31, 'fe2afb3dc9e5675281199526c97bb4cf', 'GT_PROF-0002/2017', 26, 'O', 23, '2017-10-16', 'P', 0, 0, 0, 0, 60, '<p>RAS<br></p>', 2, 519, 23, '2017-10-16 22:22:44', NULL, NULL),
(32, '6d50c057765b9b9966330a3fedef1836', 'GT_PROF-0001/2017', 25, 'N', 2, '2017-10-16', 'P', 0, 0, 0, 0, 60, '<p>RAS<br></p>', 2, 520, 23, '2017-10-16 22:26:25', 2, '2017-10-16 22:45:13'),
(34, '45861c7a059c1622cbe15382631f1ac6', 'GT_PROF-0002/2017', 27, 'O', 24, '2017-10-17', 'P', 0, 0, 0, 0, 30, '<p>Le délais de livraison est de 15 Jours <br></p>', 1, NULL, 24, '2017-10-17 14:37:33', NULL, NULL);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `qte_actuel`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `qte_actuel` (
`id_produit` int(11)
,`qte_act` decimal(32,0)
);

-- --------------------------------------------------------

--
-- Structure de la table `ref_categories_produits`
--

CREATE TABLE `ref_categories_produits` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `categorie_produit` varchar(100) NOT NULL COMMENT 'CatÃ©gorie de produits',
  `etat` int(11) DEFAULT '0',
  `creusr` int(11) DEFAULT NULL,
  `credat` datetime DEFAULT CURRENT_TIMESTAMP,
  `updusr` int(11) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `ref_categories_produits`
--

INSERT INTO `ref_categories_produits` (`id`, `categorie_produit`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(8, 'Modems', 1, 19, '2017-10-11 10:25:40', 24, '2017-10-17 12:48:13'),
(9, 'Lnb', 1, 19, '2017-10-11 10:25:54', 19, '2017-10-11 10:45:01'),
(10, 'Antennes', 1, 19, '2017-10-11 10:26:26', 19, '2017-10-11 10:45:18'),
(11, 'Accès à internet', 1, 19, '2017-10-11 10:32:38', 19, '2017-10-11 10:45:22'),
(12, 'Installation', 1, 19, '2017-10-11 11:00:19', 19, '2017-10-11 11:00:27'),
(13, 'Mini VSAT', 1, 24, '2017-10-17 12:48:52', 24, '2017-10-17 12:49:08');

-- --------------------------------------------------------

--
-- Structure de la table `ref_categ_prm`
--

CREATE TABLE `ref_categ_prm` (
  `id` int(11) NOT NULL COMMENT 'ID table',
  `categorie` varchar(75) NOT NULL COMMENT 'Catergorie',
  `abriv` varchar(25) DEFAULT NULL COMMENT 'Abreviation'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `ref_categ_prm`
--

INSERT INTO `ref_categ_prm` (`id`, `categorie`, `abriv`) VALUES
(1, 'Gouvernement', 'Gouv'),
(2, 'ONG', 'ONG'),
(3, 'Mission diplomate', 'Diplomate'),
(4, 'Secteur privé', 'Privé'),
(5, 'Etablissement Etatique', 'Ets Etat');

-- --------------------------------------------------------

--
-- Structure de la table `ref_departement`
--

CREATE TABLE `ref_departement` (
  `id` int(11) NOT NULL,
  `departement` varchar(20) CHARACTER SET latin1 DEFAULT NULL,
  `id_region` int(11) NOT NULL,
  `etat` tinyint(1) DEFAULT '0',
  `creusr` varchar(50) CHARACTER SET latin1 NOT NULL,
  `credat` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updusr` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  `upddat` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `ref_departement`
--

INSERT INTO `ref_departement` (`id`, `departement`, `id_region`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(1, 'N\'djamena Centre', 24, 1, 'admin', '2017-07-09 17:38:55', 'admin', '2017-09-15 17:57:39');

-- --------------------------------------------------------

--
-- Structure de la table `ref_devise`
--

CREATE TABLE `ref_devise` (
  `id` int(11) NOT NULL,
  `devise` varchar(30) DEFAULT NULL,
  `abreviation` varchar(10) DEFAULT NULL,
  `etat` int(11) DEFAULT '0',
  `creusr` varchar(50) DEFAULT NULL,
  `credat` datetime DEFAULT CURRENT_TIMESTAMP,
  `updusr` varchar(50) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `ref_devise`
--

INSERT INTO `ref_devise` (`id`, `devise`, `abreviation`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(1, 'Franc CFA', 'FCFA', 1, '1', '2017-09-13 22:08:50', NULL, NULL),
(2, 'Dirham Maroc', 'DH', 1, '1', '2017-09-13 22:10:10', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `ref_pays`
--

CREATE TABLE `ref_pays` (
  `id` int(11) NOT NULL COMMENT 'identifiant ligne',
  `pays` varchar(50) CHARACTER SET latin1 DEFAULT NULL COMMENT 'libelle pays',
  `nationalite` varchar(50) CHARACTER SET latin1 DEFAULT NULL COMMENT 'nationalité',
  `alpha` varchar(2) CHARACTER SET latin1 DEFAULT NULL COMMENT 'code du pays',
  `etat` int(2) NOT NULL DEFAULT '0' COMMENT 'l''etat de la ligne',
  `creusr` varchar(60) NOT NULL COMMENT 'Crée par',
  `credat` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date création',
  `updusr` varchar(60) DEFAULT NULL COMMENT 'Derniere modif par',
  `upddat` datetime DEFAULT NULL COMMENT 'Date derniere modif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `ref_pays`
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
(27, 'Territoire Britannique de l\'Océan Indien', '0', 'IO', 1, '', '0000-00-00 00:00:00', NULL, NULL),
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
(105, 'République Islamique d\'Iran', '0', 'IR', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(106, 'Iraq', 'irakienne', 'IQ', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(107, 'Irlande', 'Irlandaise', 'IE', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(108, 'Israël', 'Israelienne', 'IL', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(109, 'Italie', 'Italienne', 'IT', 1, '', '0000-00-00 00:00:00', NULL, NULL),
(110, 'Cote d\'Ivoire', 'Ivoirienne', 'CI', 1, '', '0000-00-00 00:00:00', NULL, NULL),
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
(226, 'L\'ex-République Yougoslave de Macédoine', 'Macedonienne', 'MK', 1, '', '0000-00-00 00:00:00', NULL, NULL),
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

-- --------------------------------------------------------

--
-- Structure de la table `ref_region`
--

CREATE TABLE `ref_region` (
  `id` int(11) NOT NULL COMMENT 'identifiant de ligne',
  `id_pays` int(11) DEFAULT '242' COMMENT 'le pays de la region par default Tchad',
  `region` varchar(30) CHARACTER SET latin1 NOT NULL COMMENT 'libelle region',
  `etat` int(2) NOT NULL DEFAULT '0' COMMENT 'l''etat de la ligne',
  `creusr` varchar(60) NOT NULL COMMENT 'Crée par',
  `credat` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date de création',
  `updusr` varchar(60) DEFAULT NULL COMMENT 'Derniere modif par',
  `upddat` datetime DEFAULT NULL COMMENT 'Date derniere modif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `ref_region`
--

INSERT INTO `ref_region` (`id`, `id_pays`, `region`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(1, 242, 'Barh el Ghazel', 1, '', '2017-03-19 21:47:48', '1', '2017-09-15 17:57:53'),
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
(15, 242, 'Mandoul', 1, '', '2017-03-19 21:47:48', NULL, NULL),
(16, 242, 'Mayo-Kebbi Est', 1, '', '2017-03-19 21:47:48', NULL, NULL),
(17, 242, 'Mayo-Kebbi Ouest', 1, '', '2017-03-19 21:47:48', NULL, NULL),
(18, 242, 'Moyen-Chari', 1, '', '2017-03-19 21:47:48', NULL, NULL),
(19, 242, 'Ouaddaï', 1, '', '2017-03-19 21:47:48', NULL, NULL),
(20, 242, 'Salamat', 1, '', '2017-03-19 21:47:48', NULL, NULL),
(21, 242, 'Sila', 1, '', '2017-03-19 21:47:48', NULL, NULL),
(22, 242, 'Tandjilé', 1, '', '2017-03-19 21:47:48', NULL, NULL),
(23, 242, 'Tibesti', 1, '', '2017-03-19 21:47:48', NULL, NULL),
(24, 242, 'Ville de N\'Djamena', 1, '', '2017-03-19 21:47:48', NULL, NULL),
(25, 242, 'Wadi Fira', 1, '', '2017-03-19 21:47:48', '1', '2017-04-02 13:48:49'),
(30, 242, 'Lacc', 1, '1', '2017-04-02 13:50:53', '1', '2017-07-09 18:41:09');

-- --------------------------------------------------------

--
-- Structure de la table `ref_types_produits`
--

CREATE TABLE `ref_types_produits` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `type_produit` varchar(100) NOT NULL COMMENT 'Type service/produit',
  `etat` int(11) DEFAULT '0',
  `creusr` int(11) DEFAULT NULL,
  `credat` datetime DEFAULT CURRENT_TIMESTAMP,
  `updusr` int(11) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `ref_types_produits`
--

INSERT INTO `ref_types_produits` (`id`, `type_produit`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(1, 'Produit', 1, NULL, '2017-10-01 16:41:22', 1, '2017-10-01 18:41:53'),
(2, 'Prestation', 1, 1, '2017-08-26 19:21:11', 1, '2017-10-01 18:42:02'),
(3, 'Abonnement', 1, NULL, '2017-10-01 16:41:33', 1, '2017-10-01 18:41:58');

-- --------------------------------------------------------

--
-- Structure de la table `ref_type_echeance`
--

CREATE TABLE `ref_type_echeance` (
  `id` int(11) NOT NULL,
  `type_echeance` varchar(50) DEFAULT NULL COMMENT 'Mensuelle,Trimestrielle,Annuelle',
  `etat` int(11) DEFAULT '0',
  `creusr` varchar(50) DEFAULT NULL,
  `credat` datetime DEFAULT CURRENT_TIMESTAMP,
  `updusr` varchar(50) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `ref_type_echeance`
--

INSERT INTO `ref_type_echeance` (`id`, `type_echeance`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(1, 'Annuelle', 1, '1', '2017-09-15 20:05:43', '1', '2017-09-15 20:17:12'),
(2, 'Mensuelle', 1, '1', '2017-09-15 20:17:02', '1', '2017-09-15 20:17:16'),
(3, 'Trimestrielle', 1, '1', '2017-09-15 20:17:08', '1', '2017-09-15 20:17:14'),
(4, 'Autres', 1, '1', '2017-09-16 12:58:33', NULL, NULL),
(5, 'Simestrielle', 1, '1', '2017-09-30 01:47:47', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `ref_unites_vente`
--

CREATE TABLE `ref_unites_vente` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `unite_vente` varchar(50) NOT NULL COMMENT 'UnitÃ© de vente des produits(Kg,m...)',
  `etat` int(11) DEFAULT '0',
  `creusr` int(11) DEFAULT NULL,
  `credat` datetime DEFAULT CURRENT_TIMESTAMP,
  `updusr` int(11) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `ref_unites_vente`
--

INSERT INTO `ref_unites_vente` (`id`, `unite_vente`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(2, 'Mois', 1, 1, '2017-08-26 18:24:08', 19, '2017-10-11 10:45:39'),
(3, 'Mégaoctet', 1, 19, '2017-10-11 10:33:51', 19, '2017-10-11 10:45:45'),
(4, 'Gigaoctet', 1, 19, '2017-10-11 10:34:08', 19, '2017-10-11 10:45:49'),
(5, 'Mètre', 1, 19, '2017-10-11 10:37:09', 19, '2017-10-11 10:46:01'),
(6, 'Unité', 1, 19, '2017-10-11 10:44:26', 19, '2017-10-11 10:45:56'),
(7, 'Jour-Homme', 1, 19, '2017-10-11 11:01:28', 19, '2017-10-11 11:02:03');

-- --------------------------------------------------------

--
-- Structure de la table `ref_ville`
--

CREATE TABLE `ref_ville` (
  `id` int(11) NOT NULL COMMENT 'Identifiant ligne',
  `id_departement` int(11) DEFAULT NULL COMMENT 'id du departement',
  `ville` varchar(20) CHARACTER SET latin1 NOT NULL COMMENT 'libelle',
  `latitude` varchar(15) NOT NULL COMMENT 'coordonnées geographique',
  `longitude` varchar(15) NOT NULL COMMENT 'coordonnées geographique',
  `etat` int(11) NOT NULL DEFAULT '0' COMMENT 'etat de ligne',
  `creusr` varchar(60) NOT NULL COMMENT 'Crée par',
  `credat` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date de création',
  `updusr` varchar(60) DEFAULT NULL COMMENT 'Derniere modif',
  `upddat` datetime DEFAULT NULL COMMENT 'Date deniere modif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `ref_ville`
--

INSERT INTO `ref_ville` (`id`, `id_departement`, `ville`, `latitude`, `longitude`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(1, 1, 'Batha', '', '', 1, '', '2017-03-19 23:45:09', 'admin', '2017-09-15 17:57:29'),
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
(43, 1, 'N\'djamena', '', '', 1, '', '2017-03-19 23:45:09', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `rules_action`
--

CREATE TABLE `rules_action` (
  `id` int(11) NOT NULL COMMENT 'rule id',
  `appid` int(11) NOT NULL COMMENT 'id task',
  `idf` varchar(32) CHARACTER SET latin1 DEFAULT NULL COMMENT 'IDF Rul Mgt',
  `service` int(11) DEFAULT NULL COMMENT 'Service ID',
  `userid` int(11) NOT NULL COMMENT 'id user',
  `action_id` int(11) NOT NULL COMMENT 'id action de task',
  `descrip` varchar(75) CHARACTER SET latin1 NOT NULL COMMENT 'description de rule',
  `type` int(11) DEFAULT '0' COMMENT 'action=0 task=1',
  `creusr` varchar(20) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Create by',
  `credat` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'Date Create'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table store rules for each user for each App and action';

--
-- Déchargement des données de la table `rules_action`
--

INSERT INTO `rules_action` (`id`, `appid`, `idf`, `service`, `userid`, `action_id`, `descrip`, `type`, `creusr`, `credat`) VALUES
(28785, 455, 'e69f84a801ee1525f20f34e684688a9b', 3, 20, 652, 'Gestion des catégories de produits', 0, '1', '2017-10-13 20:12:39'),
(28786, 455, '90f6eba3e0ed223e73d250278cb445d5', 3, 20, 653, 'Modifier catégorie', 0, '1', '2017-10-13 20:12:39'),
(28787, 455, 'c62968a45ae9cfa8b127ac1b5573988a', 3, 20, 654, 'Valider catégorie', 0, '1', '2017-10-13 20:12:39'),
(28788, 455, '6f43a6bcbd293f958aff51953559104e', 3, 20, 655, 'Désactiver catégorie', 0, '1', '2017-10-13 20:12:39'),
(28789, 456, 'd26f5940e88a494c0eb65047aab9a17b', 3, 20, 656, 'Ajouter une catégorie', 0, '1', '2017-10-13 20:12:39'),
(28790, 457, '27957c6d0f6869d4d90287cd50b6dde9', 3, 20, 657, 'Modifier une catégorie', 0, '1', '2017-10-13 20:12:39'),
(28791, 458, '41b48dd567e4f79e35261a47b7bad751', 3, 20, 658, 'Valider une catégorie', 0, '1', '2017-10-13 20:12:39'),
(28792, 459, '90dc20c4d1ad7be7fac8ec34d5ac26b3', 3, 20, 659, 'Supprimer une catégorie', 0, '1', '2017-10-13 20:12:39'),
(28793, 333, '6edc543080c65eca3993445c295ff94b', 3, 20, 497, 'Gestion Catégorie Client', 0, '1', '2017-10-13 20:12:39'),
(28794, 333, '142a68a109abd0462ea44fcadffe56de', 3, 20, 506, 'Editer Catégorie Client', 0, '1', '2017-10-13 20:12:39'),
(28795, 333, '70df89fa2654d8b10d7fc7e75e178b7e', 3, 20, 507, 'Activer Catégorie Client', 0, '1', '2017-10-13 20:12:39'),
(28796, 333, '109e82d6db5721f63cd827e9fd224216', 3, 20, 508, 'Désactiver Catégorie Client', 0, '1', '2017-10-13 20:12:39'),
(28797, 334, 'a5c1bd0dfd87824ff0f57c6b1e1d2c3f', 3, 20, 498, 'Ajouter Catégorie Client', 1, '1', '2017-10-13 20:12:39'),
(28798, 335, '8d901f74dfd6ee3a8f44ebd0b83fbfae', 3, 20, 499, 'Editer Catégorie Client', 1, '1', '2017-10-13 20:12:39'),
(28799, 336, 'e87327563ce6b659780d6b2c9bf8ac77', 3, 20, 500, 'Supprimer Catégorie Client', 1, '1', '2017-10-13 20:12:39'),
(28800, 337, 'c955da8d244aac06ee7595d08de7d009', 3, 20, 501, 'Valider Catégorie Client', 1, '1', '2017-10-13 20:12:39'),
(28801, 394, 'f12fb1c50aedc49c3fa3dfa2bd297bd3', 3, 20, 553, 'Gestion Clients', 0, '1', '2017-10-13 20:12:39'),
(28802, 394, 'dd3d5980299911ea854af4fa6f2e7309', 3, 20, 554, 'Editer Client', 0, '1', '2017-10-13 20:12:39'),
(28803, 394, '3c5c04a20d49ad010557a64c8cdac1ce', 3, 20, 555, 'Valider Client', 0, '1', '2017-10-13 20:12:39'),
(28804, 394, '18ace52052f2551099ecaabf049ffaec', 3, 20, 556, 'Désactiver Client', 0, '1', '2017-10-13 20:12:39'),
(28805, 394, '493f9e55fc0340763e07514c1900685a', 3, 20, 557, 'Détails Client', 0, '1', '2017-10-13 20:12:39'),
(28806, 394, '03b4f949b088e41fc9a1f3f23b7906a8', 3, 20, 558, 'Détails  Client', 0, '1', '2017-10-13 20:12:39'),
(28807, 395, '2b9d8bb8f752d1c35fb681c33e38b42b', 3, 20, 559, 'Ajouter Client', 1, '1', '2017-10-13 20:12:39'),
(28808, 396, '54aa9121e05f5e698d354022a8eab71d', 3, 20, 560, 'Editer Client', 1, '1', '2017-10-13 20:12:39'),
(28809, 397, '4eaf650e8c2221d590fac5a6a6952231', 3, 20, 561, 'Supprimer Client', 1, '1', '2017-10-13 20:12:39'),
(28810, 398, '534cd4b17fb8a371d3a20565ab8fd96e', 3, 20, 562, 'Valider Client', 1, '1', '2017-10-13 20:12:39'),
(28811, 399, '95bb6aa696ef630a335aa84e1e425e2c', 3, 20, 563, 'Détails Client', 0, '1', '2017-10-13 20:12:39'),
(28812, 609, 'ec45512f34613446e7a2e367d4b4cfbd', 3, 20, 920, 'Gestion Contrats Fournisseurs', 0, '1', '2017-10-13 20:12:39'),
(28813, 609, 'e3c0d7e92dad7f8794b2415c334ec3ff', 3, 20, 921, 'Editer Contrat', 0, '1', '2017-10-13 20:12:39'),
(28814, 609, '9dfff1c8dcb804837200f38e95381420', 3, 20, 922, 'Valider Contrat', 0, '1', '2017-10-13 20:12:39'),
(28815, 609, '9fe39b496077065105a57ccd9ed05863', 3, 20, 923, 'Désactiver Contrat', 0, '1', '2017-10-13 20:12:39'),
(28816, 609, 'faee342ff51dbe9f835529ae5b9b2a0b', 3, 20, 924, 'Détails  Contrat ', 0, '1', '2017-10-13 20:12:39'),
(28817, 609, '83406b6b206ed08878f2b2e854932ae5', 3, 20, 925, 'Détails   Contrat  ', 0, '1', '2017-10-13 20:12:39'),
(28818, 609, '8447888bef30fb983477cc1357ff7e6f', 3, 20, 926, 'Détails    Contrat ', 0, '1', '2017-10-13 20:12:39'),
(28819, 609, '4cc1845128f6a5ff3ed01100292d8ebb', 3, 20, 927, '  Détails    Contrat', 0, '1', '2017-10-13 20:12:39'),
(28820, 609, 'cd82d84c5f70a633b10aae88c34e9159', 3, 20, 928, '  Renouveler   Contrat ', 0, '1', '2017-10-13 20:12:39'),
(28821, 609, 'e9e994a0f8a204f1323fca7ce30931fe', 3, 20, 929, ' Détails  Contrat ', 0, '1', '2017-10-13 20:12:39'),
(28822, 609, 'b9e0a2a0236899590c72d31b878edfb2', 3, 20, 930, ' Renouveler  Contrat ', 0, '1', '2017-10-13 20:12:39'),
(28823, 610, 'ded24eb817021c5a666a677b1565bc5e', 3, 20, 931, 'Ajouter Contrat', 0, '1', '2017-10-13 20:12:39'),
(28824, 611, 'ed6b8695494bf4ed86d5fb18690b3a59', 3, 20, 932, 'Editer Contrat', 0, '1', '2017-10-13 20:12:39'),
(28825, 612, 'b8a40913b5955209994aaa26d0e8c3d4', 3, 20, 933, 'Supprimer Contrat', 0, '1', '2017-10-13 20:12:39'),
(28826, 613, '5efb874e7d73ccd722df806e8275770f', 3, 20, 934, 'Valider Contrat', 0, '1', '2017-10-13 20:12:39'),
(28827, 614, '64a5f976687a8c5f7cd3d672cc5d9c8c', 3, 20, 935, 'Détails Contrat', 0, '1', '2017-10-13 20:12:39'),
(28828, 615, '2cc55c65e79534161108288adb00472b', 3, 20, 936, 'Renouveler  Contrat', 0, '1', '2017-10-13 20:12:39'),
(28829, 432, 'f320732af279d6f2f8ae9c98cd0216de', 3, 20, 613, 'Gestion Départements', 0, '1', '2017-10-13 20:12:39'),
(28830, 432, '96516cd0c72d814d5dcb1d86eacd29ab', 3, 20, 617, 'Editer Département', 0, '1', '2017-10-13 20:12:39'),
(28831, 432, 'ef27a63534fa9fc3bd4b5086a92db546', 3, 20, 619, 'Valider Département', 0, '1', '2017-10-13 20:12:39'),
(28832, 432, '9aed965af4c4b89a5a23c41bf685d403', 3, 20, 620, 'Désactiver Département', 0, '1', '2017-10-13 20:12:39'),
(28833, 433, '722b3ba1c7fe735e87aa7415e5502a4c', 3, 20, 614, 'Ajouter Département', 0, '1', '2017-10-13 20:12:39'),
(28834, 434, 'daeb31006124e562d284aff67360ee19', 3, 20, 615, 'Editer Département', 0, '1', '2017-10-13 20:12:39'),
(28835, 435, 'a775da608fe55c53211d4f1c6e493251', 3, 20, 616, 'Supprimer Département', 0, '1', '2017-10-13 20:12:39'),
(28836, 436, 'bbb96ec910c5000a2006db2f6e8af10a', 3, 20, 618, 'Valider Département', 0, '1', '2017-10-13 20:12:39'),
(28837, 655, '0e79510db7f03b9b6266fc7b4a612153', 3, 20, 1005, 'Gestion Devis', 1, '1', '2017-10-13 20:12:39'),
(28838, 655, 'c15b00a1e37657336df8b6aa0eea2db5', 3, 20, 1006, 'Modifier Devis', 0, '1', '2017-10-13 20:12:39'),
(28839, 655, '28e267a2a0647d4cb37b18abb1e7d051', 3, 20, 1008, 'Voir détails', 0, '1', '2017-10-13 20:12:39'),
(28840, 655, 'd34b07afd92adad84e1c4c2ebd92ba95', 3, 20, 1009, 'Voir détails', 0, '1', '2017-10-13 20:12:39'),
(28841, 655, '4b11c0bfb3f970a541100f7fc334927e', 3, 20, 1011, 'Voir détails', 0, '1', '2017-10-13 20:12:39'),
(28842, 655, '61a0655c2c13039b5b8262b82ae6cb51', 3, 20, 1012, 'Voir détails', 0, '1', '2017-10-13 20:12:39'),
(28843, 655, 'ed30dfbb21d8d24a0da432252358bdf8', 3, 20, 1013, 'Voir détails', 0, '1', '2017-10-13 20:12:39'),
(28844, 656, 'd9eeb330625c1b87e0df00986a47be01', 3, 20, 1018, 'Ajouter Devis', 0, '1', '2017-10-13 20:12:39'),
(28845, 657, 'da93cdb05137e15aed9c4c18bddd746a', 3, 20, 1019, 'Ajouter détail devis', 0, '1', '2017-10-13 20:12:39'),
(28846, 658, 'f9f3c299f9bd0fec014f6bd3f0e06adb', 3, 20, 1020, 'Modifier Devis', 0, '1', '2017-10-13 20:12:39'),
(28847, 659, 'e14cce6f1faf7784adb327581c516b90', 3, 20, 1021, 'Supprimer Devis', 0, '1', '2017-10-13 20:12:39'),
(28848, 660, '38f10871792c133ebcc6040e9a11cde8', 3, 20, 1022, 'Modifier détail Devis', 0, '1', '2017-10-13 20:12:39'),
(28849, 661, '8def42e75fd4aee61c378d9fb303850d', 3, 20, 1023, 'Afficher détail devis', 0, '1', '2017-10-13 20:12:39'),
(28850, 662, '7666e87783b0f5a7eec1eea7593f7dfe', 3, 20, 1024, 'Valider Devis', 0, '1', '2017-10-13 20:12:39'),
(28851, 663, '7f1c48324d8c369da9aa6ab8af35acd8', 3, 20, 1025, 'Validation Client Devis', 0, '1', '2017-10-13 20:12:39'),
(28852, 664, '6adf896091dde0df89f777f31606953c', 3, 20, 1026, 'Débloquer devis', 0, '1', '2017-10-13 20:12:39'),
(28853, 502, '6beb279abea6434e3b73229aebadc081', 3, 20, 725, 'Gestion Fournisseurs', 0, '1', '2017-10-13 20:12:39'),
(28854, 502, 'ff95747f3a590b6539803f2a9a394cd5', 3, 20, 730, 'Editer Fournisseur', 0, '1', '2017-10-13 20:12:39'),
(28855, 502, 'fea982f5074995d4ccd6211a71ab2680', 3, 20, 731, 'Valider Fournisseur', 0, '1', '2017-10-13 20:12:39'),
(28856, 502, '1d0411a0dec15fc28f054f1a79d95618', 3, 20, 732, 'Désactiver Fournisseur', 0, '1', '2017-10-13 20:12:39'),
(28857, 502, 'a52affdd109b9362ce47ff18aad53e2a', 3, 20, 737, 'Détails Fournisseur', 0, '1', '2017-10-13 20:12:39'),
(28858, 502, 'c6fe5f222dd563204188e8bf0d69bd9e', 3, 20, 738, 'Détails  Fournisseur', 0, '1', '2017-10-13 20:12:39'),
(28859, 503, 'd644015625a9603adb2fcc36167aeb73', 3, 20, 726, 'Ajouter Fournisseur', 0, '1', '2017-10-13 20:12:39'),
(28860, 504, '58c6694abfd3228d927a5d5a06d40b94', 3, 20, 727, 'Editer Fournisseur', 0, '1', '2017-10-13 20:12:39'),
(28861, 505, 'd072f81cd779e4b0152953241d713ca3', 3, 20, 728, 'Supprimer Fournisseur', 0, '1', '2017-10-13 20:12:39'),
(28862, 506, '657351ce5aa227513e3b50dea77db918', 3, 20, 729, 'Valider Fournisseur', 0, '1', '2017-10-13 20:12:39'),
(28863, 508, '83b693fe35a1be29edafe4f6170641aa', 3, 20, 736, 'Détails Fournisseur', 0, '1', '2017-10-13 20:12:39'),
(28864, 475, '605450f3d7c84701b986fa31e1e9fa43', 3, 20, 684, 'Gestion Pays', 0, '1', '2017-10-13 20:12:39'),
(28865, 475, '29ba6cc689eca63dbafb109ec58bc4d6', 3, 20, 689, 'Editer Pays', 0, '1', '2017-10-13 20:12:39'),
(28866, 475, '763fe13212b4324590518773cd9a36fa', 3, 20, 690, 'Valider Pays', 0, '1', '2017-10-13 20:12:39'),
(28867, 475, '3c8427c7313d35219b17572efd380b17', 3, 20, 691, 'Désactiver Pays', 0, '1', '2017-10-13 20:12:39'),
(28868, 476, '3cd55a55307615d72aae84c6b5cf99bc', 3, 20, 685, 'Ajouter Pays', 0, '1', '2017-10-13 20:12:39'),
(28869, 477, 'cfe617d7bc6a9c7d8b86c468f21396f2', 3, 20, 686, 'Editer Pays', 0, '1', '2017-10-13 20:12:39'),
(28870, 478, 'b768486aeb655c48cc411c11fa60e150', 3, 20, 687, 'Supprimer Pays', 0, '1', '2017-10-13 20:12:39'),
(28871, 479, '15e4e24f320daa9d563ae62acff9e586', 3, 20, 688, 'Valider Pays', 0, '1', '2017-10-13 20:12:39'),
(28883, 470, 'd57b16b3aad4ce59f909609246c4fd36', 3, 20, 676, 'Gestion des régions', 0, '1', '2017-10-13 20:12:39'),
(28884, 470, 'd2e007184668dd70b9bae44d46d28ded', 3, 20, 677, 'Modifier région', 0, '1', '2017-10-13 20:12:39'),
(28885, 470, 'e74403c99ac8325b78735c531a20442f', 3, 20, 678, 'Valider région', 0, '1', '2017-10-13 20:12:39'),
(28886, 470, '7397a0fab078728bd5c53be61022d5ce', 3, 20, 679, 'Désactiver région', 0, '1', '2017-10-13 20:12:39'),
(28887, 471, '0237bd41cf70c3529681b4ccb041f1fd', 3, 20, 680, 'Ajouter région', 0, '1', '2017-10-13 20:12:39'),
(28888, 472, '6d290f454da473cb8a557829a410c3f1', 3, 20, 681, 'Modifier région', 0, '1', '2017-10-13 20:12:39'),
(28889, 473, '008cd9ea5767c739675fef4e1261cfe8', 3, 20, 682, 'Valider région', 0, '1', '2017-10-13 20:12:39'),
(28890, 474, 'fc477e6a4c90cd427ae81e555c11d6a9', 3, 20, 683, 'Supprimer région', 0, '1', '2017-10-13 20:12:39'),
(28891, 34, '83b9fa44466da4bcd7f8304185bfeac8', 3, 20, 28, 'Services', 0, '1', '2017-10-13 20:12:39'),
(28892, 34, '3c388c1e842851df49abe9ee73c0a2e7', 3, 20, 33, 'Valider Service', 0, '1', '2017-10-13 20:12:39'),
(28893, 34, 'dcb9555d5ca1d108e9fa95daa9da4b3a', 3, 20, 34, 'Supprimer Service', 0, '1', '2017-10-13 20:12:39'),
(28894, 34, '74950fb3fd858404b6048c1e81bd7c9a', 3, 20, 144, 'Modifier Service', 0, '1', '2017-10-13 20:12:39'),
(28895, 35, '55043bc4207521e3010e91d6267f5302', 3, 20, 29, 'Ajouter Service', 1, '1', '2017-10-13 20:12:39'),
(28896, 36, '2fea3d893f6b6e81467ddd2a744e4a76', 3, 20, 30, 'Modifier Service', 1, '1', '2017-10-13 20:12:39'),
(28897, 37, '1a0d5897d31b4d5e29022671c1112f59', 3, 20, 31, 'Valider Service', 1, '1', '2017-10-13 20:12:39'),
(28898, 38, '42083d4e159baf7c2ace2bb977e2b0a0', 3, 20, 32, 'Supprimer Service', 1, '1', '2017-10-13 20:12:39'),
(28899, 460, 'b6b6bfbd070b5b3dd84acedae7b854e9', 3, 20, 660, 'Gestion des types de produits', 0, '1', '2017-10-13 20:12:39'),
(28900, 460, '3c5400b775264499825a039d66aa9c90', 3, 20, 661, 'Modifier type', 0, '1', '2017-10-13 20:12:39'),
(28901, 460, 'dcf55bc300d690af4c81e4d2335e60e5', 3, 20, 662, 'Valider type', 0, '1', '2017-10-13 20:12:39'),
(28902, 460, '230b9554d37da1c71986af94962cb340', 3, 20, 663, 'Désactiver type', 0, '1', '2017-10-13 20:12:39'),
(28903, 461, 'e0d163499b4ba11d6d7a648bc6fc6de6', 3, 20, 664, 'Ajouter un type', 0, '1', '2017-10-13 20:12:39'),
(28904, 462, 'ac5a6d087b3c8db7501fa5137a47773e', 3, 20, 665, 'Modifier type', 0, '1', '2017-10-13 20:12:39'),
(28905, 463, '2e8242a93a62a264ad7cfc953967f575', 3, 20, 666, 'Valider type', 0, '1', '2017-10-13 20:12:39'),
(28906, 464, 'e3725ba15ca483b9278f68553eca5918', 3, 20, 667, 'Supprimer type', 0, '1', '2017-10-13 20:12:39'),
(28907, 480, '312fd18860781a7b1b7e33587fa423d4', 3, 20, 692, 'Gestion Type Echeance', 0, '1', '2017-10-13 20:12:39'),
(28908, 480, '46ad76148075d6b458f43e84ddf00791', 3, 20, 697, 'Editer Type Echéance', 0, '1', '2017-10-13 20:12:39'),
(28909, 480, 'add2bf057924e606653fbf5bbd65ca09', 3, 20, 698, 'Valider Type Echéance', 0, '1', '2017-10-13 20:12:39'),
(28910, 480, '463d9e1e8367736b958f0dd84b4e36d5', 3, 20, 699, 'Désactiver Type Echéance', 0, '1', '2017-10-13 20:12:39'),
(28911, 481, '76170b14c7b6f1f7058d079fe24f739b', 3, 20, 693, 'Ajouter Type Echéance', 0, '1', '2017-10-13 20:12:39'),
(28912, 482, 'decc5ed58c4d91e6967c9c67e0975cf0', 3, 20, 694, 'Editer Type Echéance', 0, '1', '2017-10-13 20:12:39'),
(28913, 483, '89db6f23dd8e96a69c6a97f556c44e14', 3, 20, 695, 'Supprimer Type Echéance', 0, '1', '2017-10-13 20:12:39'),
(28914, 484, '7527021168823e0118d44297c7684d44', 3, 20, 696, 'Valider Type Echéance', 0, '1', '2017-10-13 20:12:39'),
(28915, 465, '55ecbb545a49c70c0b728bb0c7951077', 3, 20, 668, 'Gestion des unités de vente', 0, '1', '2017-10-13 20:12:39'),
(28916, 465, '67acd70eb04242b7091d9dcbb08295d7', 3, 20, 669, 'Modifier unité ', 0, '1', '2017-10-13 20:12:39'),
(28917, 465, '7363022ed5ad047bfe86d3de4b75b1f4', 3, 20, 670, 'Valider unité', 0, '1', '2017-10-13 20:12:39'),
(28918, 465, 'ec77eb95736c27bfc269cbffc8e113f1', 3, 20, 671, 'Désactiver unité', 0, '1', '2017-10-13 20:12:39'),
(28919, 466, '3a5e8dfe211121eda706f8b6d548d111', 3, 20, 672, 'ajouter une unité', 0, '1', '2017-10-13 20:12:39'),
(28920, 467, '9b7a578981de699286376903e96bc3c7', 3, 20, 673, 'Modifier une unité', 0, '1', '2017-10-13 20:12:39'),
(28921, 468, '62355588366c13ddbadc7a7ca1d226ad', 3, 20, 674, 'Valider une unité', 0, '1', '2017-10-13 20:12:39'),
(28922, 469, 'e5f53a3aaa324415d781156396938101', 3, 20, 675, 'Supprimer une unité', 0, '1', '2017-10-13 20:12:39'),
(28923, 709, '56de23d30d6c54297c8d9772cd3c7f57', 3, 20, 1115, 'Utilisateurs', 1, '1', '2017-10-13 20:12:39'),
(28924, 709, 'e656756fb7b39a4e6ddcabca75ff2970', 3, 20, 1116, 'Editer Utilisateur', 0, '1', '2017-10-13 20:12:39'),
(28925, 709, 'c073a277957ca1b9f318ac3902555708', 3, 20, 1117, 'Permissions', 0, '1', '2017-10-13 20:12:39'),
(28926, 709, 'c51499ddf7007787c4434661c658bbd1', 3, 20, 1118, 'Désactiver compte', 0, '1', '2017-10-13 20:12:39'),
(28927, 709, '10096b6f54456bcfc85081523ee64cf6', 3, 20, 1119, 'Supprimer utilisateur', 0, '1', '2017-10-13 20:12:39'),
(28928, 709, 'a0999cbed820aff775adf27276ee54a4', 3, 20, 1120, 'Editer Utilisateur', 0, '1', '2017-10-13 20:12:39'),
(28929, 709, '9aa6877656339ddff2478b20449a924b', 3, 20, 1121, 'Activer compte', 0, '1', '2017-10-13 20:12:39'),
(28930, 709, 'f4c79bb797b92dfa826b51a44e3171af', 3, 20, 1122, 'Utilisateurs', 0, '1', '2017-10-13 20:12:39'),
(28931, 709, 'd7f7afd70a297e5c239f6cf271138390', 3, 20, 1123, 'Utilisateur Archivé', 0, '1', '2017-10-13 20:12:39'),
(28932, 709, '17c98287fb82388423e04d24404cf662', 3, 20, 1124, 'Permissions', 0, '1', '2017-10-13 20:12:39'),
(28933, 709, '2c1c5556f30585c5b7c93fbbeaa98595', 3, 20, 1125, 'Historique session', 0, '1', '2017-10-13 20:12:39'),
(28934, 709, '7fd4ca84d162b70d3a4f0bad73e0e4b3', 3, 20, 1126, 'Liste Activitées', 0, '1', '2017-10-13 20:12:39'),
(28935, 710, 'df91a8e6f8ee2cde64495fc0cc7d6c6f', 3, 20, 1127, 'Ajouter Utilisateurs', 1, '1', '2017-10-13 20:12:39'),
(28936, 711, '2bb46b52eab9eecbdbba35605da07234', 3, 20, 1128, 'Editer Utilisateurs', 1, '1', '2017-10-13 20:12:39'),
(28937, 712, '3f59a1326df27378304e142ab3bec090', 3, 20, 1129, 'Permission', 1, '1', '2017-10-13 20:12:39'),
(28938, 713, 'b919571c88d036f8889742a81a4f41fd', 3, 20, 1130, 'Supprimer utilisateur', 1, '1', '2017-10-13 20:12:39'),
(28939, 714, '38f89764a26e39ce029cd3132c12b2a5', 3, 20, 1131, 'Compte utilisateur', 1, '1', '2017-10-13 20:12:39'),
(28940, 715, 'f988a608f35a0bc551cb038b1706d207', 3, 20, 1132, 'Activer utilisateur', 1, '1', '2017-10-13 20:12:39'),
(28941, 716, 'b7b3a09fdd73a5b0a3e5ed8a2828f548', 3, 20, 1133, 'Désactiver l\'utilisateur', 1, '1', '2017-10-13 20:12:39'),
(28942, 717, '0d374b7e2fe21a2e2641c092a3c7f2e9', 3, 20, 1134, 'Changer le mot de passe', 1, '1', '2017-10-13 20:12:39'),
(28943, 718, '6f642ee30722158f0318653b9113b887', 3, 20, 1135, 'History', 1, '1', '2017-10-13 20:12:39'),
(28944, 719, 'cc907fac13631903d129c137d671d718', 3, 20, 1136, 'Activities', 1, '1', '2017-10-13 20:12:39'),
(28945, 430, '3d4eaa53061f51b0c4435bd8e4b89c17', 3, 20, 611, 'Gestion Vente', 0, '1', '2017-10-13 20:12:39'),
(28946, 89, '2c3b01c696ff401a2ac9ffedb7a06e4a', 3, 20, 114, 'Gestion Villes', 1, '1', '2017-10-13 20:12:39'),
(28947, 89, 'b9649163b368f863a0e8036f11cd81ae', 3, 20, 119, 'Editer Ville', 0, '1', '2017-10-13 20:12:39'),
(28948, 89, '89dec6dabcb210cdb9dd28bbef90d43e', 3, 20, 121, 'Editer Ville', 0, '1', '2017-10-13 20:12:39'),
(28949, 89, '4a2edbdcbda34c9d3d1e6abe73643b37', 3, 20, 602, 'Valider Ville', 0, '1', '2017-10-13 20:12:39'),
(28950, 89, '0c8ad6595a4516be83ba5a9cdb7ea9a1', 3, 20, 603, 'Désactiver Ville', 0, '1', '2017-10-13 20:12:39'),
(28951, 90, 'e152b9052d3dcfcac593489dbdc0f61c', 3, 20, 115, 'Ajouter ville', 1, '1', '2017-10-13 20:12:39'),
(28952, 91, '3107e0cd0e0df14c4e94aa088e4457d7', 3, 20, 116, 'Editer Ville', 1, '1', '2017-10-13 20:12:39'),
(28953, 92, 'da79d9214ed5819d7f4f1e3070629a3d', 3, 20, 117, 'Supprimer Ville', 1, '1', '2017-10-13 20:12:39'),
(28954, 423, 'fe03a68d17c62ff2c27329573a1b3550', 3, 20, 601, 'Valider Ville', 0, '1', '2017-10-13 20:12:39'),
(29003, 720, '1eb847d87adcad78d5e951e6110061e5', 3, 20, 1137, 'Gestion Proforma', 0, '1', '2017-10-15 23:11:57'),
(29005, 720, '44ef6849d8d5d17d8e0535187e923d32', 3, 20, 1138, 'Editer proforma', 0, '1', '2017-10-15 23:11:57'),
(29007, 720, 'b7ce06be726011362a271678547a803c', 3, 20, 1139, 'Valider Proforma', 0, '1', '2017-10-15 23:11:57'),
(29009, 720, 'abd8c50f1d2ef4beeeddb68a72973587', 3, 20, 1140, 'Détail Proforma', 0, '1', '2017-10-15 23:11:57'),
(29011, 720, 'e20d83df90355eca2a65f56a2556601f', 3, 20, 1142, 'Détail Proforma', 0, '1', '2017-10-15 23:11:57'),
(29013, 721, 'd5a6338765b9eab63104b59f01c06114', 3, 20, 1144, 'Ajouter pro-forma', 0, '1', '2017-10-15 23:11:57'),
(29015, 722, '95831bde77bc886d6ab4dd5e734de743', 3, 20, 1145, 'Editer proforma', 0, '1', '2017-10-15 23:11:57'),
(29017, 723, 'cbb4e1efa1c05b42d25a3a6bcab038a2', 3, 20, 1146, 'Ajouter détail proforma', 0, '1', '2017-10-15 23:11:57'),
(29019, 724, 'e9f745054778257a255452c6609461a0', 3, 20, 1147, 'valider Proforma', 0, '1', '2017-10-15 23:11:57'),
(29021, 725, 'defef148c404c7e6ac79e4783e0a7ab7', 3, 20, 1148, 'Détail Pro-forma', 0, '1', '2017-10-15 23:11:57'),
(29023, 726, '53008d64edf241c937a06f03eff139aa', 3, 20, 1149, 'Editer détail proforma', 0, '1', '2017-10-15 23:11:57'),
(29748, 455, 'e69f84a801ee1525f20f34e684688a9b', 2, 2, 652, 'Gestion des catégories de produits', 0, '2', '2017-10-16 21:30:25'),
(29749, 455, '90f6eba3e0ed223e73d250278cb445d5', 2, 2, 653, 'Modifier catégorie', 0, '2', '2017-10-16 21:30:25'),
(29750, 455, 'c62968a45ae9cfa8b127ac1b5573988a', 2, 2, 654, 'Valider catégorie', 0, '2', '2017-10-16 21:30:25'),
(29751, 455, '6f43a6bcbd293f958aff51953559104e', 2, 2, 655, 'Désactiver catégorie', 0, '2', '2017-10-16 21:30:25'),
(29752, 456, 'd26f5940e88a494c0eb65047aab9a17b', 2, 2, 656, 'Ajouter une catégorie', 0, '2', '2017-10-16 21:30:25'),
(29753, 457, '27957c6d0f6869d4d90287cd50b6dde9', 2, 2, 657, 'Modifier une catégorie', 0, '2', '2017-10-16 21:30:25'),
(29754, 458, '41b48dd567e4f79e35261a47b7bad751', 2, 2, 658, 'Valider une catégorie', 0, '2', '2017-10-16 21:30:25'),
(29755, 459, '90dc20c4d1ad7be7fac8ec34d5ac26b3', 2, 2, 659, 'Supprimer une catégorie', 0, '2', '2017-10-16 21:30:25'),
(29756, 333, '6edc543080c65eca3993445c295ff94b', 2, 2, 497, 'Gestion Catégorie Client', 0, '2', '2017-10-16 21:30:25'),
(29757, 333, '142a68a109abd0462ea44fcadffe56de', 2, 2, 506, 'Editer Catégorie Client', 0, '2', '2017-10-16 21:30:25'),
(29758, 333, '70df89fa2654d8b10d7fc7e75e178b7e', 2, 2, 507, 'Activer Catégorie Client', 0, '2', '2017-10-16 21:30:25'),
(29759, 333, '109e82d6db5721f63cd827e9fd224216', 2, 2, 508, 'Désactiver Catégorie Client', 0, '2', '2017-10-16 21:30:25'),
(29760, 334, 'a5c1bd0dfd87824ff0f57c6b1e1d2c3f', 2, 2, 498, 'Ajouter Catégorie Client', 1, '2', '2017-10-16 21:30:25'),
(29761, 335, '8d901f74dfd6ee3a8f44ebd0b83fbfae', 2, 2, 499, 'Editer Catégorie Client', 1, '2', '2017-10-16 21:30:25'),
(29762, 336, 'e87327563ce6b659780d6b2c9bf8ac77', 2, 2, 500, 'Supprimer Catégorie Client', 1, '2', '2017-10-16 21:30:25'),
(29763, 337, 'c955da8d244aac06ee7595d08de7d009', 2, 2, 501, 'Valider Catégorie Client', 1, '2', '2017-10-16 21:30:25'),
(29764, 394, 'f12fb1c50aedc49c3fa3dfa2bd297bd3', 2, 2, 553, 'Gestion Clients', 0, '2', '2017-10-16 21:30:25'),
(29765, 394, 'dd3d5980299911ea854af4fa6f2e7309', 2, 2, 554, 'Editer Client', 0, '2', '2017-10-16 21:30:25'),
(29766, 394, '3c5c04a20d49ad010557a64c8cdac1ce', 2, 2, 555, 'Valider Client', 0, '2', '2017-10-16 21:30:25'),
(29767, 394, '18ace52052f2551099ecaabf049ffaec', 2, 2, 556, 'Désactiver Client', 0, '2', '2017-10-16 21:30:25'),
(29768, 394, '493f9e55fc0340763e07514c1900685a', 2, 2, 557, 'Détails Client', 0, '2', '2017-10-16 21:30:25'),
(29769, 394, '03b4f949b088e41fc9a1f3f23b7906a8', 2, 2, 558, 'Détails  Client', 0, '2', '2017-10-16 21:30:25'),
(29770, 395, '2b9d8bb8f752d1c35fb681c33e38b42b', 2, 2, 559, 'Ajouter Client', 1, '2', '2017-10-16 21:30:25'),
(29771, 396, '54aa9121e05f5e698d354022a8eab71d', 2, 2, 560, 'Editer Client', 1, '2', '2017-10-16 21:30:25'),
(29772, 397, '4eaf650e8c2221d590fac5a6a6952231', 2, 2, 561, 'Supprimer Client', 1, '2', '2017-10-16 21:30:25'),
(29773, 398, '534cd4b17fb8a371d3a20565ab8fd96e', 2, 2, 562, 'Valider Client', 1, '2', '2017-10-16 21:30:25'),
(29774, 399, '95bb6aa696ef630a335aa84e1e425e2c', 2, 2, 563, 'Détails Client', 0, '2', '2017-10-16 21:30:25'),
(29775, 609, 'ec45512f34613446e7a2e367d4b4cfbd', 2, 2, 920, 'Gestion Contrats Fournisseurs', 0, '2', '2017-10-16 21:30:25'),
(29776, 609, 'e3c0d7e92dad7f8794b2415c334ec3ff', 2, 2, 921, 'Editer Contrat', 0, '2', '2017-10-16 21:30:25'),
(29777, 609, '9dfff1c8dcb804837200f38e95381420', 2, 2, 922, 'Valider Contrat', 0, '2', '2017-10-16 21:30:25'),
(29778, 609, '9fe39b496077065105a57ccd9ed05863', 2, 2, 923, 'Désactiver Contrat', 0, '2', '2017-10-16 21:30:25'),
(29779, 609, 'faee342ff51dbe9f835529ae5b9b2a0b', 2, 2, 924, 'Détails  Contrat ', 0, '2', '2017-10-16 21:30:25'),
(29780, 609, '83406b6b206ed08878f2b2e854932ae5', 2, 2, 925, 'Détails   Contrat  ', 0, '2', '2017-10-16 21:30:25'),
(29781, 609, '8447888bef30fb983477cc1357ff7e6f', 2, 2, 926, 'Détails    Contrat ', 0, '2', '2017-10-16 21:30:25'),
(29782, 609, '4cc1845128f6a5ff3ed01100292d8ebb', 2, 2, 927, '  Détails    Contrat', 0, '2', '2017-10-16 21:30:25'),
(29783, 609, 'cd82d84c5f70a633b10aae88c34e9159', 2, 2, 928, '  Renouveler   Contrat ', 0, '2', '2017-10-16 21:30:25'),
(29784, 609, 'e9e994a0f8a204f1323fca7ce30931fe', 2, 2, 929, ' Détails  Contrat ', 0, '2', '2017-10-16 21:30:25'),
(29785, 609, 'b9e0a2a0236899590c72d31b878edfb2', 2, 2, 930, ' Renouveler  Contrat ', 0, '2', '2017-10-16 21:30:25'),
(29786, 610, 'ded24eb817021c5a666a677b1565bc5e', 2, 2, 931, 'Ajouter Contrat', 0, '2', '2017-10-16 21:30:25'),
(29787, 611, 'ed6b8695494bf4ed86d5fb18690b3a59', 2, 2, 932, 'Editer Contrat', 0, '2', '2017-10-16 21:30:25'),
(29788, 612, 'b8a40913b5955209994aaa26d0e8c3d4', 2, 2, 933, 'Supprimer Contrat', 0, '2', '2017-10-16 21:30:25'),
(29789, 613, '5efb874e7d73ccd722df806e8275770f', 2, 2, 934, 'Valider Contrat', 0, '2', '2017-10-16 21:30:25'),
(29790, 614, '64a5f976687a8c5f7cd3d672cc5d9c8c', 2, 2, 935, 'Détails Contrat', 0, '2', '2017-10-16 21:30:25'),
(29791, 615, '2cc55c65e79534161108288adb00472b', 2, 2, 936, 'Renouveler  Contrat', 0, '2', '2017-10-16 21:30:25'),
(29792, 655, '0e79510db7f03b9b6266fc7b4a612153', 2, 2, 1005, 'Gestion Devis', 1, '2', '2017-10-16 21:30:25'),
(29793, 655, 'c15b00a1e37657336df8b6aa0eea2db5', 2, 2, 1006, 'Modifier Devis', 0, '2', '2017-10-16 21:30:25'),
(29794, 655, '7cfdba6bc6bc94c65b97e77746cf49b5', 2, 2, 1007, 'Envoi au client', 0, '2', '2017-10-16 21:30:25'),
(29795, 655, '28e267a2a0647d4cb37b18abb1e7d051', 2, 2, 1008, 'Voir détails', 0, '2', '2017-10-16 21:30:25'),
(29796, 655, 'd34b07afd92adad84e1c4c2ebd92ba95', 2, 2, 1009, 'Voir détails', 0, '2', '2017-10-16 21:30:25'),
(29797, 655, 'f6f55b9d0ba9d704b2861d57cda32477', 2, 2, 1010, 'Réponse Client', 0, '2', '2017-10-16 21:30:25'),
(29798, 655, '4b11c0bfb3f970a541100f7fc334927e', 2, 2, 1011, 'Voir détails', 0, '2', '2017-10-16 21:30:25'),
(29799, 655, '61a0655c2c13039b5b8262b82ae6cb51', 2, 2, 1012, 'Voir détails', 0, '2', '2017-10-16 21:30:25'),
(29800, 655, 'ed30dfbb21d8d24a0da432252358bdf8', 2, 2, 1013, 'Voir détails', 0, '2', '2017-10-16 21:30:25'),
(29801, 655, 'b1bcc4a4ab154f110abcf54c0c659fb3', 2, 2, 1016, 'Voir détails', 0, '2', '2017-10-16 21:30:25'),
(29802, 655, '91a90a46e3430c491ab8db654b6e87c4', 2, 2, 1017, 'Voir détails', 0, '2', '2017-10-16 21:30:25'),
(29803, 656, 'd9eeb330625c1b87e0df00986a47be01', 2, 2, 1018, 'Ajouter Devis', 0, '2', '2017-10-16 21:30:25'),
(29804, 657, 'da93cdb05137e15aed9c4c18bddd746a', 2, 2, 1019, 'Ajouter détail devis', 0, '2', '2017-10-16 21:30:25'),
(29805, 658, 'f9f3c299f9bd0fec014f6bd3f0e06adb', 2, 2, 1020, 'Modifier Devis', 0, '2', '2017-10-16 21:30:25'),
(29806, 660, '38f10871792c133ebcc6040e9a11cde8', 2, 2, 1022, 'Modifier détail Devis', 0, '2', '2017-10-16 21:30:25'),
(29807, 661, '8def42e75fd4aee61c378d9fb303850d', 2, 2, 1023, 'Afficher détail devis', 0, '2', '2017-10-16 21:30:25'),
(29808, 662, '7666e87783b0f5a7eec1eea7593f7dfe', 2, 2, 1024, 'Valider Devis', 0, '2', '2017-10-16 21:30:25'),
(29809, 663, '7f1c48324d8c369da9aa6ab8af35acd8', 2, 2, 1025, 'Validation Client Devis', 0, '2', '2017-10-16 21:30:25'),
(29832, 720, '1eb847d87adcad78d5e951e6110061e5', 2, 2, 1137, 'Gestion Proforma', 0, '2', '2017-10-16 21:30:25'),
(29833, 720, '44ef6849d8d5d17d8e0535187e923d32', 2, 2, 1138, 'Editer proforma', 0, '2', '2017-10-16 21:30:25'),
(29834, 720, 'abd8c50f1d2ef4beeeddb68a72973587', 2, 2, 1140, 'Détail Proforma', 0, '2', '2017-10-16 21:30:25'),
(29835, 720, '35a88b5c359908b063ac98cafc622987', 2, 2, 1141, 'Détail Proforma', 0, '2', '2017-10-16 21:30:25'),
(29836, 720, 'e20d83df90355eca2a65f56a2556601f', 2, 2, 1142, 'Détail Proforma', 0, '2', '2017-10-16 21:30:25'),
(29837, 720, '252ed64d8956e20fb88c1be41688f74a', 2, 2, 1143, 'Envoi proforma au client', 0, '2', '2017-10-16 21:30:25'),
(29838, 721, 'd5a6338765b9eab63104b59f01c06114', 2, 2, 1144, 'Ajouter pro-forma', 0, '2', '2017-10-16 21:30:25'),
(29839, 722, '95831bde77bc886d6ab4dd5e734de743', 2, 2, 1145, 'Editer proforma', 0, '2', '2017-10-16 21:30:25'),
(29840, 723, 'cbb4e1efa1c05b42d25a3a6bcab038a2', 2, 2, 1146, 'Ajouter détail proforma', 0, '2', '2017-10-16 21:30:25'),
(29841, 724, 'e9f745054778257a255452c6609461a0', 2, 2, 1147, 'valider Proforma', 0, '2', '2017-10-16 21:30:25'),
(29842, 725, 'defef148c404c7e6ac79e4783e0a7ab7', 2, 2, 1148, 'Détail Pro-forma', 0, '2', '2017-10-16 21:30:25'),
(29843, 726, '53008d64edf241c937a06f03eff139aa', 2, 2, 1149, 'Editer détail proforma', 0, '2', '2017-10-16 21:30:25'),
(29844, 727, '30e9d3d1ad17eb7f1fc0d5cbb9b58482', 2, 2, 1150, 'Supprimer proforma', 1, '2', '2017-10-16 21:30:25'),
(29845, 34, '3c388c1e842851df49abe9ee73c0a2e7', 2, 2, 33, 'Valider Service', 0, '2', '2017-10-16 21:30:25'),
(29846, 34, 'dcb9555d5ca1d108e9fa95daa9da4b3a', 2, 2, 34, 'Supprimer Service', 0, '2', '2017-10-16 21:30:25'),
(29847, 35, '55043bc4207521e3010e91d6267f5302', 2, 2, 29, 'Ajouter Service', 1, '2', '2017-10-16 21:30:25'),
(29848, 36, '2fea3d893f6b6e81467ddd2a744e4a76', 2, 2, 30, 'Modifier Service', 1, '2', '2017-10-16 21:30:25'),
(29849, 37, '1a0d5897d31b4d5e29022671c1112f59', 2, 2, 31, 'Valider Service', 1, '2', '2017-10-16 21:30:25'),
(29850, 38, '42083d4e159baf7c2ace2bb977e2b0a0', 2, 2, 32, 'Supprimer Service', 1, '2', '2017-10-16 21:30:25'),
(29851, 709, '56de23d30d6c54297c8d9772cd3c7f57', 2, 2, 1115, 'Utilisateurs', 1, '2', '2017-10-16 21:30:25'),
(29852, 709, 'e656756fb7b39a4e6ddcabca75ff2970', 2, 2, 1116, 'Editer Utilisateur', 0, '2', '2017-10-16 21:30:25'),
(29853, 709, 'c073a277957ca1b9f318ac3902555708', 2, 2, 1117, 'Permissions', 0, '2', '2017-10-16 21:30:25'),
(29854, 709, 'c51499ddf7007787c4434661c658bbd1', 2, 2, 1118, 'Désactiver compte', 0, '2', '2017-10-16 21:30:25'),
(29855, 709, '10096b6f54456bcfc85081523ee64cf6', 2, 2, 1119, 'Supprimer utilisateur', 0, '2', '2017-10-16 21:30:25'),
(29856, 709, 'a0999cbed820aff775adf27276ee54a4', 2, 2, 1120, 'Editer Utilisateur', 0, '2', '2017-10-16 21:30:25'),
(29857, 709, '9aa6877656339ddff2478b20449a924b', 2, 2, 1121, 'Activer compte', 0, '2', '2017-10-16 21:30:25'),
(29858, 709, 'f4c79bb797b92dfa826b51a44e3171af', 2, 2, 1122, 'Utilisateurs', 0, '2', '2017-10-16 21:30:25'),
(29859, 709, 'd7f7afd70a297e5c239f6cf271138390', 2, 2, 1123, 'Utilisateur Archivé', 0, '2', '2017-10-16 21:30:25'),
(29860, 709, '17c98287fb82388423e04d24404cf662', 2, 2, 1124, 'Permissions', 0, '2', '2017-10-16 21:30:25'),
(29861, 709, '2c1c5556f30585c5b7c93fbbeaa98595', 2, 2, 1125, 'Historique session', 0, '2', '2017-10-16 21:30:25'),
(29862, 709, '7fd4ca84d162b70d3a4f0bad73e0e4b3', 2, 2, 1126, 'Liste Activitées', 0, '2', '2017-10-16 21:30:25'),
(29863, 710, 'df91a8e6f8ee2cde64495fc0cc7d6c6f', 2, 2, 1127, 'Ajouter Utilisateurs', 1, '2', '2017-10-16 21:30:25'),
(29864, 711, '2bb46b52eab9eecbdbba35605da07234', 2, 2, 1128, 'Editer Utilisateurs', 1, '2', '2017-10-16 21:30:25'),
(29865, 712, '3f59a1326df27378304e142ab3bec090', 2, 2, 1129, 'Permission', 1, '2', '2017-10-16 21:30:25'),
(29866, 713, 'b919571c88d036f8889742a81a4f41fd', 2, 2, 1130, 'Supprimer utilisateur', 1, '2', '2017-10-16 21:30:25'),
(29867, 714, '38f89764a26e39ce029cd3132c12b2a5', 2, 2, 1131, 'Compte utilisateur', 1, '2', '2017-10-16 21:30:25'),
(29868, 715, 'f988a608f35a0bc551cb038b1706d207', 2, 2, 1132, 'Activer utilisateur', 1, '2', '2017-10-16 21:30:25'),
(29869, 716, 'b7b3a09fdd73a5b0a3e5ed8a2828f548', 2, 2, 1133, 'Désactiver l\'utilisateur', 1, '2', '2017-10-16 21:30:25'),
(29870, 717, '0d374b7e2fe21a2e2641c092a3c7f2e9', 2, 2, 1134, 'Changer le mot de passe', 1, '2', '2017-10-16 21:30:25'),
(29871, 718, '6f642ee30722158f0318653b9113b887', 2, 2, 1135, 'History', 1, '2', '2017-10-16 21:30:25'),
(29872, 719, 'cc907fac13631903d129c137d671d718', 2, 2, 1136, 'Activities', 1, '2', '2017-10-16 21:30:25'),
(29873, 430, '3d4eaa53061f51b0c4435bd8e4b89c17', 2, 2, 611, 'Gestion Vente', 0, '2', '2017-10-16 21:30:25'),
(29874, 655, '0e79510db7f03b9b6266fc7b4a612153', 5, 19, 1005, 'Gestion Devis', 1, '20', '2017-10-16 21:31:17'),
(29875, 655, 'c15b00a1e37657336df8b6aa0eea2db5', 5, 19, 1006, 'Modifier Devis', 0, '20', '2017-10-16 21:31:17'),
(29876, 655, '7cfdba6bc6bc94c65b97e77746cf49b5', 5, 19, 1007, 'Envoi au client', 0, '20', '2017-10-16 21:31:17'),
(29877, 655, '28e267a2a0647d4cb37b18abb1e7d051', 5, 19, 1008, 'Voir détails', 0, '20', '2017-10-16 21:31:17'),
(29878, 655, 'd34b07afd92adad84e1c4c2ebd92ba95', 5, 19, 1009, 'Voir détails', 0, '20', '2017-10-16 21:31:17'),
(29879, 655, 'f6f55b9d0ba9d704b2861d57cda32477', 5, 19, 1010, 'Réponse Client', 0, '20', '2017-10-16 21:31:17'),
(29880, 655, '4b11c0bfb3f970a541100f7fc334927e', 5, 19, 1011, 'Voir détails', 0, '20', '2017-10-16 21:31:17'),
(29881, 655, '61a0655c2c13039b5b8262b82ae6cb51', 5, 19, 1012, 'Voir détails', 0, '20', '2017-10-16 21:31:17'),
(29882, 655, 'ed30dfbb21d8d24a0da432252358bdf8', 5, 19, 1013, 'Voir détails', 0, '20', '2017-10-16 21:31:17'),
(29883, 655, '7bd2e025ffb3893dea4776e152301716', 5, 19, 1014, 'Débloquer devis', 0, '20', '2017-10-16 21:31:17'),
(29884, 655, '6d9ec7d9ebebcdc921376e7bf0c9fdaf', 5, 19, 1015, 'Valider devis', 0, '20', '2017-10-16 21:31:17'),
(29885, 655, 'b1bcc4a4ab154f110abcf54c0c659fb3', 5, 19, 1016, 'Voir détails', 0, '20', '2017-10-16 21:31:17'),
(29886, 655, '91a90a46e3430c491ab8db654b6e87c4', 5, 19, 1017, 'Voir détails', 0, '20', '2017-10-16 21:31:17'),
(29887, 656, 'd9eeb330625c1b87e0df00986a47be01', 5, 19, 1018, 'Ajouter Devis', 0, '20', '2017-10-16 21:31:17'),
(29888, 657, 'da93cdb05137e15aed9c4c18bddd746a', 5, 19, 1019, 'Ajouter détail devis', 0, '20', '2017-10-16 21:31:17'),
(29889, 658, 'f9f3c299f9bd0fec014f6bd3f0e06adb', 5, 19, 1020, 'Modifier Devis', 0, '20', '2017-10-16 21:31:17'),
(29890, 659, 'e14cce6f1faf7784adb327581c516b90', 5, 19, 1021, 'Supprimer Devis', 0, '20', '2017-10-16 21:31:18'),
(29891, 660, '38f10871792c133ebcc6040e9a11cde8', 5, 19, 1022, 'Modifier détail Devis', 0, '20', '2017-10-16 21:31:18'),
(29892, 661, '8def42e75fd4aee61c378d9fb303850d', 5, 19, 1023, 'Afficher détail devis', 0, '20', '2017-10-16 21:31:18'),
(29893, 662, '7666e87783b0f5a7eec1eea7593f7dfe', 5, 19, 1024, 'Valider Devis', 0, '20', '2017-10-16 21:31:18'),
(29894, 663, '7f1c48324d8c369da9aa6ab8af35acd8', 5, 19, 1025, 'Validation Client Devis', 0, '20', '2017-10-16 21:31:18'),
(29895, 664, '6adf896091dde0df89f777f31606953c', 5, 19, 1026, 'Débloquer devis', 0, '20', '2017-10-16 21:31:18'),
(29896, 665, '15cbb79dd4a74266158e6b29a83e683c', 5, 19, 1027, 'Archiver Devis', 1, '20', '2017-10-16 21:31:18'),
(29897, 720, '1eb847d87adcad78d5e951e6110061e5', 5, 19, 1137, 'Gestion Proforma', 0, '20', '2017-10-16 21:31:18'),
(29898, 720, '44ef6849d8d5d17d8e0535187e923d32', 5, 19, 1138, 'Editer proforma', 0, '20', '2017-10-16 21:31:18'),
(29899, 720, 'b7ce06be726011362a271678547a803c', 5, 19, 1139, 'Valider Proforma', 0, '20', '2017-10-16 21:31:18'),
(29900, 720, 'abd8c50f1d2ef4beeeddb68a72973587', 5, 19, 1140, 'Détail Proforma', 0, '20', '2017-10-16 21:31:18'),
(29901, 720, '35a88b5c359908b063ac98cafc622987', 5, 19, 1141, 'Détail Proforma', 0, '20', '2017-10-16 21:31:18'),
(29902, 720, 'e20d83df90355eca2a65f56a2556601f', 5, 19, 1142, 'Détail Proforma', 0, '20', '2017-10-16 21:31:18'),
(29903, 720, '252ed64d8956e20fb88c1be41688f74a', 5, 19, 1143, 'Envoi proforma au client', 0, '20', '2017-10-16 21:31:18'),
(29904, 721, 'd5a6338765b9eab63104b59f01c06114', 5, 19, 1144, 'Ajouter pro-forma', 0, '20', '2017-10-16 21:31:18'),
(29905, 722, '95831bde77bc886d6ab4dd5e734de743', 5, 19, 1145, 'Editer proforma', 0, '20', '2017-10-16 21:31:18'),
(29906, 723, 'cbb4e1efa1c05b42d25a3a6bcab038a2', 5, 19, 1146, 'Ajouter détail proforma', 0, '20', '2017-10-16 21:31:18'),
(29907, 724, 'e9f745054778257a255452c6609461a0', 5, 19, 1147, 'valider Proforma', 0, '20', '2017-10-16 21:31:18'),
(29908, 725, 'defef148c404c7e6ac79e4783e0a7ab7', 5, 19, 1148, 'Détail Pro-forma', 0, '20', '2017-10-16 21:31:18'),
(29909, 726, '53008d64edf241c937a06f03eff139aa', 5, 19, 1149, 'Editer détail proforma', 0, '20', '2017-10-16 21:31:18'),
(29910, 727, '30e9d3d1ad17eb7f1fc0d5cbb9b58482', 5, 19, 1150, 'Supprimer proforma', 1, '20', '2017-10-16 21:31:18'),
(29911, 709, '56de23d30d6c54297c8d9772cd3c7f57', 5, 19, 1115, 'Utilisateurs', 1, '20', '2017-10-16 21:31:18'),
(29912, 709, 'e656756fb7b39a4e6ddcabca75ff2970', 5, 19, 1116, 'Editer Utilisateur', 0, '20', '2017-10-16 21:31:18'),
(29913, 709, 'c073a277957ca1b9f318ac3902555708', 5, 19, 1117, 'Permissions', 0, '20', '2017-10-16 21:31:18'),
(29914, 709, 'c51499ddf7007787c4434661c658bbd1', 5, 19, 1118, 'Désactiver compte', 0, '20', '2017-10-16 21:31:18'),
(29915, 709, '10096b6f54456bcfc85081523ee64cf6', 5, 19, 1119, 'Supprimer utilisateur', 0, '20', '2017-10-16 21:31:18'),
(29916, 709, 'a0999cbed820aff775adf27276ee54a4', 5, 19, 1120, 'Editer Utilisateur', 0, '20', '2017-10-16 21:31:18'),
(29917, 709, '9aa6877656339ddff2478b20449a924b', 5, 19, 1121, 'Activer compte', 0, '20', '2017-10-16 21:31:18'),
(29918, 709, 'f4c79bb797b92dfa826b51a44e3171af', 5, 19, 1122, 'Utilisateurs', 0, '20', '2017-10-16 21:31:18'),
(29919, 709, 'd7f7afd70a297e5c239f6cf271138390', 5, 19, 1123, 'Utilisateur Archivé', 0, '20', '2017-10-16 21:31:18'),
(29920, 709, '17c98287fb82388423e04d24404cf662', 5, 19, 1124, 'Permissions', 0, '20', '2017-10-16 21:31:18'),
(29921, 709, '2c1c5556f30585c5b7c93fbbeaa98595', 5, 19, 1125, 'Historique session', 0, '20', '2017-10-16 21:31:18'),
(29922, 709, '7fd4ca84d162b70d3a4f0bad73e0e4b3', 5, 19, 1126, 'Liste Activitées', 0, '20', '2017-10-16 21:31:18'),
(29923, 710, 'df91a8e6f8ee2cde64495fc0cc7d6c6f', 5, 19, 1127, 'Ajouter Utilisateurs', 1, '20', '2017-10-16 21:31:18'),
(29924, 711, '2bb46b52eab9eecbdbba35605da07234', 5, 19, 1128, 'Editer Utilisateurs', 1, '20', '2017-10-16 21:31:18'),
(29925, 712, '3f59a1326df27378304e142ab3bec090', 5, 19, 1129, 'Permission', 1, '20', '2017-10-16 21:31:18'),
(29926, 713, 'b919571c88d036f8889742a81a4f41fd', 5, 19, 1130, 'Supprimer utilisateur', 1, '20', '2017-10-16 21:31:18'),
(29927, 714, '38f89764a26e39ce029cd3132c12b2a5', 5, 19, 1131, 'Compte utilisateur', 1, '20', '2017-10-16 21:31:18'),
(29928, 715, 'f988a608f35a0bc551cb038b1706d207', 5, 19, 1132, 'Activer utilisateur', 1, '20', '2017-10-16 21:31:18'),
(29929, 716, 'b7b3a09fdd73a5b0a3e5ed8a2828f548', 5, 19, 1133, 'Désactiver l\'utilisateur', 1, '20', '2017-10-16 21:31:18'),
(29930, 717, '0d374b7e2fe21a2e2641c092a3c7f2e9', 5, 19, 1134, 'Changer le mot de passe', 1, '20', '2017-10-16 21:31:18'),
(29931, 718, '6f642ee30722158f0318653b9113b887', 5, 19, 1135, 'History', 1, '20', '2017-10-16 21:31:18'),
(29932, 719, 'cc907fac13631903d129c137d671d718', 5, 19, 1136, 'Activities', 1, '20', '2017-10-16 21:31:18'),
(29933, 430, '3d4eaa53061f51b0c4435bd8e4b89c17', 5, 19, 611, 'Gestion Vente', 0, '20', '2017-10-16 21:31:18'),
(29934, 34, '83b9fa44466da4bcd7f8304185bfeac8', 1, 22, 28, 'Services', 0, NULL, '2017-10-16 21:34:25'),
(29935, 35, '55043bc4207521e3010e91d6267f5302', 1, 22, 29, 'Ajouter Service', 1, NULL, '2017-10-16 21:34:25'),
(29936, 36, '2fea3d893f6b6e81467ddd2a744e4a76', 1, 22, 30, 'Modifier Service', 1, NULL, '2017-10-16 21:34:25'),
(29937, 37, '1a0d5897d31b4d5e29022671c1112f59', 1, 22, 31, 'Valider Service', 1, NULL, '2017-10-16 21:34:25'),
(29938, 38, '42083d4e159baf7c2ace2bb977e2b0a0', 1, 22, 32, 'Supprimer Service', 1, NULL, '2017-10-16 21:34:25'),
(29939, 34, '3c388c1e842851df49abe9ee73c0a2e7', 1, 22, 33, 'Valider Service', 0, NULL, '2017-10-16 21:34:25'),
(29940, 34, 'dcb9555d5ca1d108e9fa95daa9da4b3a', 1, 22, 34, 'Supprimer Service', 0, NULL, '2017-10-16 21:34:25'),
(29941, 89, '2c3b01c696ff401a2ac9ffedb7a06e4a', 1, 22, 114, 'Gestion Villes', 1, NULL, '2017-10-16 21:34:25'),
(29942, 90, 'e152b9052d3dcfcac593489dbdc0f61c', 1, 22, 115, 'Ajouter ville', 1, NULL, '2017-10-16 21:34:25'),
(29943, 91, '3107e0cd0e0df14c4e94aa088e4457d7', 1, 22, 116, 'Editer Ville', 1, NULL, '2017-10-16 21:34:25'),
(29944, 92, 'da79d9214ed5819d7f4f1e3070629a3d', 1, 22, 117, 'Supprimer Ville', 1, NULL, '2017-10-16 21:34:25'),
(29945, 89, 'b9649163b368f863a0e8036f11cd81ae', 1, 22, 119, 'Editer Ville', 0, NULL, '2017-10-16 21:34:25'),
(29946, 89, '89dec6dabcb210cdb9dd28bbef90d43e', 1, 22, 121, 'Editer Ville', 0, NULL, '2017-10-16 21:34:25'),
(29947, 34, '74950fb3fd858404b6048c1e81bd7c9a', 1, 22, 144, 'Modifier Service', 0, NULL, '2017-10-16 21:34:25'),
(29948, 333, '6edc543080c65eca3993445c295ff94b', 1, 22, 497, 'Gestion Catégorie Client', 0, NULL, '2017-10-16 21:34:25'),
(29949, 334, 'a5c1bd0dfd87824ff0f57c6b1e1d2c3f', 1, 22, 498, 'Ajouter Catégorie Client', 1, NULL, '2017-10-16 21:34:25'),
(29950, 335, '8d901f74dfd6ee3a8f44ebd0b83fbfae', 1, 22, 499, 'Editer Catégorie Client', 1, NULL, '2017-10-16 21:34:25'),
(29951, 336, 'e87327563ce6b659780d6b2c9bf8ac77', 1, 22, 500, 'Supprimer Catégorie Client', 1, NULL, '2017-10-16 21:34:25'),
(29952, 337, 'c955da8d244aac06ee7595d08de7d009', 1, 22, 501, 'Valider Catégorie Client', 1, NULL, '2017-10-16 21:34:25'),
(29953, 333, '142a68a109abd0462ea44fcadffe56de', 1, 22, 506, 'Editer Catégorie Client', 0, NULL, '2017-10-16 21:34:25'),
(29954, 333, '70df89fa2654d8b10d7fc7e75e178b7e', 1, 22, 507, 'Activer Catégorie Client', 0, NULL, '2017-10-16 21:34:25'),
(29955, 333, '109e82d6db5721f63cd827e9fd224216', 1, 22, 508, 'Désactiver Catégorie Client', 0, NULL, '2017-10-16 21:34:25'),
(29956, 394, 'f12fb1c50aedc49c3fa3dfa2bd297bd3', 1, 22, 553, 'Gestion Clients', 0, NULL, '2017-10-16 21:34:25'),
(29957, 394, 'dd3d5980299911ea854af4fa6f2e7309', 1, 22, 554, 'Editer Client', 0, NULL, '2017-10-16 21:34:25'),
(29958, 394, '3c5c04a20d49ad010557a64c8cdac1ce', 1, 22, 555, 'Valider Client', 0, NULL, '2017-10-16 21:34:25'),
(29959, 394, '18ace52052f2551099ecaabf049ffaec', 1, 22, 556, 'Désactiver Client', 0, NULL, '2017-10-16 21:34:25'),
(29960, 394, '493f9e55fc0340763e07514c1900685a', 1, 22, 557, 'Détails Client', 0, NULL, '2017-10-16 21:34:25'),
(29961, 394, '03b4f949b088e41fc9a1f3f23b7906a8', 1, 22, 558, 'Détails  Client', 0, NULL, '2017-10-16 21:34:25'),
(29962, 395, '2b9d8bb8f752d1c35fb681c33e38b42b', 1, 22, 559, 'Ajouter Client', 1, NULL, '2017-10-16 21:34:25'),
(29963, 396, '54aa9121e05f5e698d354022a8eab71d', 1, 22, 560, 'Editer Client', 1, NULL, '2017-10-16 21:34:25'),
(29964, 397, '4eaf650e8c2221d590fac5a6a6952231', 1, 22, 561, 'Supprimer Client', 1, NULL, '2017-10-16 21:34:25'),
(29965, 398, '534cd4b17fb8a371d3a20565ab8fd96e', 1, 22, 562, 'Valider Client', 1, NULL, '2017-10-16 21:34:25'),
(29966, 399, '95bb6aa696ef630a335aa84e1e425e2c', 1, 22, 563, 'Détails Client', 0, NULL, '2017-10-16 21:34:25'),
(29967, 423, 'fe03a68d17c62ff2c27329573a1b3550', 1, 22, 601, 'Valider Ville', 0, NULL, '2017-10-16 21:34:25'),
(29968, 89, '4a2edbdcbda34c9d3d1e6abe73643b37', 1, 22, 602, 'Valider Ville', 0, NULL, '2017-10-16 21:34:25'),
(29969, 89, '0c8ad6595a4516be83ba5a9cdb7ea9a1', 1, 22, 603, 'Désactiver Ville', 0, NULL, '2017-10-16 21:34:25'),
(29970, 430, '3d4eaa53061f51b0c4435bd8e4b89c17', 1, 22, 611, 'Gestion Vente', 0, NULL, '2017-10-16 21:34:25'),
(29971, 432, 'f320732af279d6f2f8ae9c98cd0216de', 1, 22, 613, 'Gestion Départements', 0, NULL, '2017-10-16 21:34:25'),
(29972, 433, '722b3ba1c7fe735e87aa7415e5502a4c', 1, 22, 614, 'Ajouter Département', 0, NULL, '2017-10-16 21:34:25'),
(29973, 434, 'daeb31006124e562d284aff67360ee19', 1, 22, 615, 'Editer Département', 0, NULL, '2017-10-16 21:34:25'),
(29974, 435, 'a775da608fe55c53211d4f1c6e493251', 1, 22, 616, 'Supprimer Département', 0, NULL, '2017-10-16 21:34:25'),
(29975, 432, '96516cd0c72d814d5dcb1d86eacd29ab', 1, 22, 617, 'Editer Département', 0, NULL, '2017-10-16 21:34:25'),
(29976, 436, 'bbb96ec910c5000a2006db2f6e8af10a', 1, 22, 618, 'Valider Département', 0, NULL, '2017-10-16 21:34:25'),
(29977, 432, 'ef27a63534fa9fc3bd4b5086a92db546', 1, 22, 619, 'Valider Département', 0, NULL, '2017-10-16 21:34:25'),
(29978, 432, '9aed965af4c4b89a5a23c41bf685d403', 1, 22, 620, 'Désactiver Département', 0, NULL, '2017-10-16 21:34:25'),
(29979, 455, 'e69f84a801ee1525f20f34e684688a9b', 1, 22, 652, 'Gestion des catégories de produits', 0, NULL, '2017-10-16 21:34:25'),
(29980, 455, '90f6eba3e0ed223e73d250278cb445d5', 1, 22, 653, 'Modifier catégorie', 0, NULL, '2017-10-16 21:34:25'),
(29981, 455, 'c62968a45ae9cfa8b127ac1b5573988a', 1, 22, 654, 'Valider catégorie', 0, NULL, '2017-10-16 21:34:25'),
(29982, 455, '6f43a6bcbd293f958aff51953559104e', 1, 22, 655, 'Désactiver catégorie', 0, NULL, '2017-10-16 21:34:25'),
(29983, 456, 'd26f5940e88a494c0eb65047aab9a17b', 1, 22, 656, 'Ajouter une catégorie', 0, NULL, '2017-10-16 21:34:25'),
(29984, 457, '27957c6d0f6869d4d90287cd50b6dde9', 1, 22, 657, 'Modifier une catégorie', 0, NULL, '2017-10-16 21:34:25'),
(29985, 458, '41b48dd567e4f79e35261a47b7bad751', 1, 22, 658, 'Valider une catégorie', 0, NULL, '2017-10-16 21:34:25'),
(29986, 459, '90dc20c4d1ad7be7fac8ec34d5ac26b3', 1, 22, 659, 'Supprimer une catégorie', 0, NULL, '2017-10-16 21:34:25'),
(29987, 460, 'b6b6bfbd070b5b3dd84acedae7b854e9', 1, 22, 660, 'Gestion des types de produits', 0, NULL, '2017-10-16 21:34:25'),
(29988, 460, '3c5400b775264499825a039d66aa9c90', 1, 22, 661, 'Modifier type', 0, NULL, '2017-10-16 21:34:25'),
(29989, 460, 'dcf55bc300d690af4c81e4d2335e60e5', 1, 22, 662, 'Valider type', 0, NULL, '2017-10-16 21:34:25'),
(29990, 460, '230b9554d37da1c71986af94962cb340', 1, 22, 663, 'Désactiver type', 0, NULL, '2017-10-16 21:34:25'),
(29991, 461, 'e0d163499b4ba11d6d7a648bc6fc6de6', 1, 22, 664, 'Ajouter un type', 0, NULL, '2017-10-16 21:34:25'),
(29992, 462, 'ac5a6d087b3c8db7501fa5137a47773e', 1, 22, 665, 'Modifier type', 0, NULL, '2017-10-16 21:34:25'),
(29993, 463, '2e8242a93a62a264ad7cfc953967f575', 1, 22, 666, 'Valider type', 0, NULL, '2017-10-16 21:34:25'),
(29994, 464, 'e3725ba15ca483b9278f68553eca5918', 1, 22, 667, 'Supprimer type', 0, NULL, '2017-10-16 21:34:25'),
(29995, 465, '55ecbb545a49c70c0b728bb0c7951077', 1, 22, 668, 'Gestion des unités de vente', 0, NULL, '2017-10-16 21:34:25'),
(29996, 465, '67acd70eb04242b7091d9dcbb08295d7', 1, 22, 669, 'Modifier unité ', 0, NULL, '2017-10-16 21:34:25'),
(29997, 465, '7363022ed5ad047bfe86d3de4b75b1f4', 1, 22, 670, 'Valider unité', 0, NULL, '2017-10-16 21:34:25'),
(29998, 465, 'ec77eb95736c27bfc269cbffc8e113f1', 1, 22, 671, 'Désactiver unité', 0, NULL, '2017-10-16 21:34:25'),
(29999, 466, '3a5e8dfe211121eda706f8b6d548d111', 1, 22, 672, 'ajouter une unité', 0, NULL, '2017-10-16 21:34:25'),
(30000, 467, '9b7a578981de699286376903e96bc3c7', 1, 22, 673, 'Modifier une unité', 0, NULL, '2017-10-16 21:34:25'),
(30001, 468, '62355588366c13ddbadc7a7ca1d226ad', 1, 22, 674, 'Valider une unité', 0, NULL, '2017-10-16 21:34:25'),
(30002, 469, 'e5f53a3aaa324415d781156396938101', 1, 22, 675, 'Supprimer une unité', 0, NULL, '2017-10-16 21:34:25'),
(30003, 470, 'd57b16b3aad4ce59f909609246c4fd36', 1, 22, 676, 'Gestion des régions', 0, NULL, '2017-10-16 21:34:25'),
(30004, 470, 'd2e007184668dd70b9bae44d46d28ded', 1, 22, 677, 'Modifier région', 0, NULL, '2017-10-16 21:34:25'),
(30005, 470, 'e74403c99ac8325b78735c531a20442f', 1, 22, 678, 'Valider région', 0, NULL, '2017-10-16 21:34:25'),
(30006, 470, '7397a0fab078728bd5c53be61022d5ce', 1, 22, 679, 'Désactiver région', 0, NULL, '2017-10-16 21:34:25'),
(30007, 471, '0237bd41cf70c3529681b4ccb041f1fd', 1, 22, 680, 'Ajouter région', 0, NULL, '2017-10-16 21:34:25'),
(30008, 472, '6d290f454da473cb8a557829a410c3f1', 1, 22, 681, 'Modifier région', 0, NULL, '2017-10-16 21:34:25'),
(30009, 473, '008cd9ea5767c739675fef4e1261cfe8', 1, 22, 682, 'Valider région', 0, NULL, '2017-10-16 21:34:25'),
(30010, 474, 'fc477e6a4c90cd427ae81e555c11d6a9', 1, 22, 683, 'Supprimer région', 0, NULL, '2017-10-16 21:34:25'),
(30011, 475, '605450f3d7c84701b986fa31e1e9fa43', 1, 22, 684, 'Gestion Pays', 0, NULL, '2017-10-16 21:34:25'),
(30012, 476, '3cd55a55307615d72aae84c6b5cf99bc', 1, 22, 685, 'Ajouter Pays', 0, NULL, '2017-10-16 21:34:25'),
(30013, 477, 'cfe617d7bc6a9c7d8b86c468f21396f2', 1, 22, 686, 'Editer Pays', 0, NULL, '2017-10-16 21:34:25'),
(30014, 478, 'b768486aeb655c48cc411c11fa60e150', 1, 22, 687, 'Supprimer Pays', 0, NULL, '2017-10-16 21:34:25'),
(30015, 479, '15e4e24f320daa9d563ae62acff9e586', 1, 22, 688, 'Valider Pays', 0, NULL, '2017-10-16 21:34:25'),
(30016, 475, '29ba6cc689eca63dbafb109ec58bc4d6', 1, 22, 689, 'Editer Pays', 0, NULL, '2017-10-16 21:34:25'),
(30017, 475, '763fe13212b4324590518773cd9a36fa', 1, 22, 690, 'Valider Pays', 0, NULL, '2017-10-16 21:34:25'),
(30018, 475, '3c8427c7313d35219b17572efd380b17', 1, 22, 691, 'Désactiver Pays', 0, NULL, '2017-10-16 21:34:25'),
(30019, 480, '312fd18860781a7b1b7e33587fa423d4', 1, 22, 692, 'Gestion Type Echeance', 0, NULL, '2017-10-16 21:34:25'),
(30020, 481, '76170b14c7b6f1f7058d079fe24f739b', 1, 22, 693, 'Ajouter Type Echéance', 0, NULL, '2017-10-16 21:34:25'),
(30021, 482, 'decc5ed58c4d91e6967c9c67e0975cf0', 1, 22, 694, 'Editer Type Echéance', 0, NULL, '2017-10-16 21:34:25'),
(30022, 483, '89db6f23dd8e96a69c6a97f556c44e14', 1, 22, 695, 'Supprimer Type Echéance', 0, NULL, '2017-10-16 21:34:25'),
(30023, 484, '7527021168823e0118d44297c7684d44', 1, 22, 696, 'Valider Type Echéance', 0, NULL, '2017-10-16 21:34:25'),
(30024, 480, '46ad76148075d6b458f43e84ddf00791', 1, 22, 697, 'Editer Type Echéance', 0, NULL, '2017-10-16 21:34:25'),
(30025, 480, 'add2bf057924e606653fbf5bbd65ca09', 1, 22, 698, 'Valider Type Echéance', 0, NULL, '2017-10-16 21:34:25'),
(30026, 480, '463d9e1e8367736b958f0dd84b4e36d5', 1, 22, 699, 'Désactiver Type Echéance', 0, NULL, '2017-10-16 21:34:25'),
(30027, 502, '6beb279abea6434e3b73229aebadc081', 1, 22, 725, 'Gestion Fournisseurs', 0, NULL, '2017-10-16 21:34:25'),
(30028, 503, 'd644015625a9603adb2fcc36167aeb73', 1, 22, 726, 'Ajouter Fournisseur', 0, NULL, '2017-10-16 21:34:25'),
(30029, 504, '58c6694abfd3228d927a5d5a06d40b94', 1, 22, 727, 'Editer Fournisseur', 0, NULL, '2017-10-16 21:34:25'),
(30030, 505, 'd072f81cd779e4b0152953241d713ca3', 1, 22, 728, 'Supprimer Fournisseur', 0, NULL, '2017-10-16 21:34:25'),
(30031, 506, '657351ce5aa227513e3b50dea77db918', 1, 22, 729, 'Valider Fournisseur', 0, NULL, '2017-10-16 21:34:25'),
(30032, 502, 'ff95747f3a590b6539803f2a9a394cd5', 1, 22, 730, 'Editer Fournisseur', 0, NULL, '2017-10-16 21:34:25'),
(30033, 502, 'fea982f5074995d4ccd6211a71ab2680', 1, 22, 731, 'Valider Fournisseur', 0, NULL, '2017-10-16 21:34:25'),
(30034, 502, '1d0411a0dec15fc28f054f1a79d95618', 1, 22, 732, 'Désactiver Fournisseur', 0, NULL, '2017-10-16 21:34:25'),
(30035, 508, '83b693fe35a1be29edafe4f6170641aa', 1, 22, 736, 'Détails Fournisseur', 0, NULL, '2017-10-16 21:34:25'),
(30036, 502, 'a52affdd109b9362ce47ff18aad53e2a', 1, 22, 737, 'Détails Fournisseur', 0, NULL, '2017-10-16 21:34:25'),
(30037, 502, 'c6fe5f222dd563204188e8bf0d69bd9e', 1, 22, 738, 'Détails  Fournisseur', 0, NULL, '2017-10-16 21:34:25'),
(30038, 542, '72db1c2280dc3eb6405908c1c5b6c815', 1, 22, 810, 'Information société', 0, NULL, '2017-10-16 21:34:25'),
(30039, 543, 'a1c5a2657cc1b2ff6f85c6fe8f1c51ac', 1, 22, 811, 'Paramètrage Système', 0, NULL, '2017-10-16 21:34:25'),
(30040, 543, 'de6285d9c0027ff8bccdf2af385ac337', 1, 22, 812, 'Editer paramètre', 0, NULL, '2017-10-16 21:34:25'),
(30041, 544, '82f83d9d3d30fdef00d4c3ef96f0f899', 1, 22, 813, 'Ajouter Paramètre', 0, NULL, '2017-10-16 21:34:25'),
(30042, 545, 'f0e54f346e9dcfdff65274709ce2c8ca', 1, 22, 814, 'Editer paramètre', 0, NULL, '2017-10-16 21:34:25');
INSERT INTO `rules_action` (`id`, `appid`, `idf`, `service`, `userid`, `action_id`, `descrip`, `type`, `creusr`, `credat`) VALUES
(30043, 546, 'aaccd24eaf085b8f18115c9c7653d401', 1, 22, 815, 'Supprimer Paramètre', 0, NULL, '2017-10-16 21:34:25'),
(30044, 609, 'ec45512f34613446e7a2e367d4b4cfbd', 1, 22, 920, 'Gestion Contrats Fournisseurs', 0, NULL, '2017-10-16 21:34:25'),
(30045, 609, 'e3c0d7e92dad7f8794b2415c334ec3ff', 1, 22, 921, 'Editer Contrat', 0, NULL, '2017-10-16 21:34:25'),
(30046, 609, '9dfff1c8dcb804837200f38e95381420', 1, 22, 922, 'Valider Contrat', 0, NULL, '2017-10-16 21:34:25'),
(30047, 609, '9fe39b496077065105a57ccd9ed05863', 1, 22, 923, 'Désactiver Contrat', 0, NULL, '2017-10-16 21:34:25'),
(30048, 609, 'faee342ff51dbe9f835529ae5b9b2a0b', 1, 22, 924, 'Détails  Contrat ', 0, NULL, '2017-10-16 21:34:25'),
(30049, 609, '83406b6b206ed08878f2b2e854932ae5', 1, 22, 925, 'Détails   Contrat  ', 0, NULL, '2017-10-16 21:34:25'),
(30050, 609, '8447888bef30fb983477cc1357ff7e6f', 1, 22, 926, 'Détails    Contrat ', 0, NULL, '2017-10-16 21:34:25'),
(30051, 609, '4cc1845128f6a5ff3ed01100292d8ebb', 1, 22, 927, '  Détails    Contrat', 0, NULL, '2017-10-16 21:34:25'),
(30052, 609, 'cd82d84c5f70a633b10aae88c34e9159', 1, 22, 928, '  Renouveler   Contrat ', 0, NULL, '2017-10-16 21:34:25'),
(30053, 609, 'e9e994a0f8a204f1323fca7ce30931fe', 1, 22, 929, ' Détails  Contrat ', 0, NULL, '2017-10-16 21:34:25'),
(30054, 609, 'b9e0a2a0236899590c72d31b878edfb2', 1, 22, 930, ' Renouveler  Contrat ', 0, NULL, '2017-10-16 21:34:25'),
(30055, 610, 'ded24eb817021c5a666a677b1565bc5e', 1, 22, 931, 'Ajouter Contrat', 0, NULL, '2017-10-16 21:34:25'),
(30056, 611, 'ed6b8695494bf4ed86d5fb18690b3a59', 1, 22, 932, 'Editer Contrat', 0, NULL, '2017-10-16 21:34:25'),
(30057, 612, 'b8a40913b5955209994aaa26d0e8c3d4', 1, 22, 933, 'Supprimer Contrat', 0, NULL, '2017-10-16 21:34:25'),
(30058, 613, '5efb874e7d73ccd722df806e8275770f', 1, 22, 934, 'Valider Contrat', 0, NULL, '2017-10-16 21:34:25'),
(30059, 614, '64a5f976687a8c5f7cd3d672cc5d9c8c', 1, 22, 935, 'Détails Contrat', 0, NULL, '2017-10-16 21:34:25'),
(30060, 615, '2cc55c65e79534161108288adb00472b', 1, 22, 936, 'Renouveler  Contrat', 0, NULL, '2017-10-16 21:34:25'),
(30061, 637, '05ce9e55686161d99e0714bb86243e5b', 1, 22, 979, 'Editer Module', 0, NULL, '2017-10-16 21:34:25'),
(30062, 637, '819cf9c18a44cb80771a066768d585f2', 1, 22, 980, 'Exporter Module', 0, NULL, '2017-10-16 21:34:25'),
(30063, 637, 'd2fc3ee15cee5208a8b9c70f1e53c196', 1, 22, 981, 'Liste task modul', 0, NULL, '2017-10-16 21:34:25'),
(30064, 637, 'ad75e6b877f20e3d6fc1789da4dcb3e6', 1, 22, 982, 'Editer Module', 0, NULL, '2017-10-16 21:34:25'),
(30065, 637, '064a9b0eff1006fd4f25cb4eaf894ca1', 1, 22, 983, 'Liste task modul Setting', 0, NULL, '2017-10-16 21:34:25'),
(30066, 637, 'ac4eb0c94da00a48ad5d995f5e9e9366', 1, 22, 984, 'MAJ Module', 0, NULL, '2017-10-16 21:34:25'),
(30067, 638, '44bd5341b0ab41ced21db8b3e92cf5aa', 1, 22, 985, 'Ajouter un Modul', 1, NULL, '2017-10-16 21:34:25'),
(30068, 640, '8653b156f1a4160a12e5a94b211e59a2', 1, 22, 986, 'Liste Action Task', 0, NULL, '2017-10-16 21:34:25'),
(30069, 640, '86aced763bc02e1957a5c740fb37b4f7', 1, 22, 987, 'Supprimer Application', 0, NULL, '2017-10-16 21:34:25'),
(30070, 640, 'f07352e32fe86da1483c6ab071b7e7a9', 1, 22, 988, 'Ajout Affichage WF', 0, NULL, '2017-10-16 21:34:25'),
(30071, 641, '1c452aff8f1551b3574e15b74147ea56', 1, 22, 989, 'Ajouter Task Modul', 1, NULL, '2017-10-16 21:34:25'),
(30072, 642, 'f085fe4610576987db963501297e4d91', 1, 22, 990, 'Editer Task Modul', 1, NULL, '2017-10-16 21:34:25'),
(30073, 642, '38702c272a6f4d334c2f4c3684c8b163', 1, 22, 991, 'Ajouter action modul', 1, NULL, '2017-10-16 21:34:25'),
(30074, 643, 'cbae1ebe850f6dd8841426c6fedf1785', 1, 22, 992, 'Liste Action Task', 1, NULL, '2017-10-16 21:34:25'),
(30075, 643, 'e30471396f9b86ccdcc94943d80b679a', 1, 22, 993, 'Editer Task Action', 0, NULL, '2017-10-16 21:34:25'),
(30076, 644, '502460cd9327b46ee7af0a258ebf8c80', 1, 22, 994, 'Ajouter Action Task', 1, NULL, '2017-10-16 21:34:25'),
(30077, 646, '8c8acf9cf3790b16b1fae26823f45eab', 1, 22, 996, 'Importer des modules', 1, NULL, '2017-10-16 21:34:25'),
(30078, 647, '2f4518dab90b706e2f4acd737a0425d8', 1, 22, 997, 'Ajouter Module paramétrage', 1, NULL, '2017-10-16 21:34:25'),
(30079, 648, '8e0c0212d8337956ac2f4d6eb180d74b', 1, 22, 998, 'Editer Module paramètrage', 1, NULL, '2017-10-16 21:34:25'),
(30080, 649, 'fc54953b47b7fcb11cc14c0c2e2125f0', 1, 22, 999, 'Ajouter Autorisation Etat', 1, NULL, '2017-10-16 21:34:25'),
(30081, 650, '966ec2dd83e6006c2d0ff1d1a5f12e33', 1, 22, 1000, 'Editer Task Action', 1, NULL, '2017-10-16 21:34:25'),
(30082, 651, '3473119f6683893a3f1372dbf7d811e1', 1, 22, 1001, 'MAJ Module', 1, NULL, '2017-10-16 21:34:25'),
(30083, 652, '2e2346bd422536c1d996ff25f9e71357', 1, 22, 1002, 'Dupliquer Action Task', 0, NULL, '2017-10-16 21:34:25'),
(30084, 653, '8a3634181ae5bc9223b689a310158962', 1, 22, 1003, 'Supprimer Task action', 0, NULL, '2017-10-16 21:34:25'),
(30085, 654, '8afb3c669307183cd3b7d189fbf204d7', 1, 22, 1004, 'Affichage Work Flow', 0, NULL, '2017-10-16 21:34:25'),
(30086, 655, '0e79510db7f03b9b6266fc7b4a612153', 1, 22, 1005, 'Gestion Devis', 1, NULL, '2017-10-16 21:34:25'),
(30087, 655, 'c15b00a1e37657336df8b6aa0eea2db5', 1, 22, 1006, 'Modifier Devis', 0, NULL, '2017-10-16 21:34:25'),
(30088, 655, '7cfdba6bc6bc94c65b97e77746cf49b5', 1, 22, 1007, 'Envoi au client', 0, NULL, '2017-10-16 21:34:25'),
(30089, 655, '28e267a2a0647d4cb37b18abb1e7d051', 1, 22, 1008, 'Voir détails', 0, NULL, '2017-10-16 21:34:25'),
(30090, 655, 'd34b07afd92adad84e1c4c2ebd92ba95', 1, 22, 1009, 'Voir détails', 0, NULL, '2017-10-16 21:34:25'),
(30091, 655, 'f6f55b9d0ba9d704b2861d57cda32477', 1, 22, 1010, 'Réponse Client', 0, NULL, '2017-10-16 21:34:25'),
(30092, 655, '4b11c0bfb3f970a541100f7fc334927e', 1, 22, 1011, 'Voir détails', 0, NULL, '2017-10-16 21:34:25'),
(30093, 655, '61a0655c2c13039b5b8262b82ae6cb51', 1, 22, 1012, 'Voir détails', 0, NULL, '2017-10-16 21:34:25'),
(30094, 655, 'ed30dfbb21d8d24a0da432252358bdf8', 1, 22, 1013, 'Voir détails', 0, NULL, '2017-10-16 21:34:25'),
(30095, 655, '7bd2e025ffb3893dea4776e152301716', 1, 22, 1014, 'Débloquer devis', 0, NULL, '2017-10-16 21:34:25'),
(30096, 655, '6d9ec7d9ebebcdc921376e7bf0c9fdaf', 1, 22, 1015, 'Valider devis', 0, NULL, '2017-10-16 21:34:25'),
(30097, 655, 'b1bcc4a4ab154f110abcf54c0c659fb3', 1, 22, 1016, 'Voir détails', 0, NULL, '2017-10-16 21:34:25'),
(30098, 655, '91a90a46e3430c491ab8db654b6e87c4', 1, 22, 1017, 'Voir détails', 0, NULL, '2017-10-16 21:34:25'),
(30099, 656, 'd9eeb330625c1b87e0df00986a47be01', 1, 22, 1018, 'Ajouter Devis', 0, NULL, '2017-10-16 21:34:25'),
(30100, 657, 'da93cdb05137e15aed9c4c18bddd746a', 1, 22, 1019, 'Ajouter détail devis', 0, NULL, '2017-10-16 21:34:25'),
(30101, 658, 'f9f3c299f9bd0fec014f6bd3f0e06adb', 1, 22, 1020, 'Modifier Devis', 0, NULL, '2017-10-16 21:34:25'),
(30102, 659, 'e14cce6f1faf7784adb327581c516b90', 1, 22, 1021, 'Supprimer Devis', 0, NULL, '2017-10-16 21:34:25'),
(30103, 660, '38f10871792c133ebcc6040e9a11cde8', 1, 22, 1022, 'Modifier détail Devis', 0, NULL, '2017-10-16 21:34:25'),
(30104, 661, '8def42e75fd4aee61c378d9fb303850d', 1, 22, 1023, 'Afficher détail devis', 0, NULL, '2017-10-16 21:34:25'),
(30105, 662, '7666e87783b0f5a7eec1eea7593f7dfe', 1, 22, 1024, 'Valider Devis', 0, NULL, '2017-10-16 21:34:25'),
(30106, 663, '7f1c48324d8c369da9aa6ab8af35acd8', 1, 22, 1025, 'Validation Client Devis', 0, NULL, '2017-10-16 21:34:25'),
(30107, 664, '6adf896091dde0df89f777f31606953c', 1, 22, 1026, 'Débloquer devis', 0, NULL, '2017-10-16 21:34:25'),
(30108, 665, '15cbb79dd4a74266158e6b29a83e683c', 1, 22, 1027, 'Archiver Devis', 1, NULL, '2017-10-16 21:34:25'),
(30109, 675, '4c924acb9adc87d8389e8f9842a965c5', 1, 22, 1047, 'Gestion des factures', 0, NULL, '2017-10-16 21:34:25'),
(30110, 675, '98a697ec628778765b25e02ba2929d38', 1, 22, 1048, 'Liste complément', 0, NULL, '2017-10-16 21:34:25'),
(30111, 675, 'f8b20f7fec99b45b967a431d64b7f061', 1, 22, 1049, 'Liste encaissements', 0, NULL, '2017-10-16 21:34:25'),
(30112, 675, '9a51fb5298e39a28af3ad6272fc51177', 1, 22, 1050, 'Valider facture', 0, NULL, '2017-10-16 21:34:25'),
(30113, 675, '851f1d4c13f6025f69f5b9315321d350', 1, 22, 1051, 'Désactiver facture', 0, NULL, '2017-10-16 21:34:25'),
(30114, 675, '5c79105956d28b5cac52f85784039919', 1, 22, 1052, 'Détail facture', 0, NULL, '2017-10-16 21:34:25'),
(30115, 675, '7892721423af84a0b54e90250cf27ee3', 1, 22, 1053, 'Détails Facture', 0, NULL, '2017-10-16 21:34:25'),
(30116, 675, 'b5380d403c9947ce060963f28e6d7539', 1, 22, 1054, 'Envoyer au client', 0, NULL, '2017-10-16 21:34:25'),
(30117, 675, '80a4b2643b95c2836e968411811d3c21', 1, 22, 1055, 'Détails facture', 0, NULL, '2017-10-16 21:34:25'),
(30118, 675, '2f679be3c0d7b88529209f86745f9028', 1, 22, 1056, 'Détails facture', 0, NULL, '2017-10-16 21:34:25'),
(30119, 675, '429558e9a1e899c11051ea5c9a4f7942', 1, 22, 1057, 'Détails facture', 0, NULL, '2017-10-16 21:34:25'),
(30120, 675, '3acd11d8d74fb7e1ba8d5721e96f91bd', 1, 22, 1058, 'Liste encaissements', 0, NULL, '2017-10-16 21:34:25'),
(30121, 676, '55c3c5d2d93143b315513b7401043c8b', 1, 22, 1059, 'complements', 0, NULL, '2017-10-16 21:34:25'),
(30122, 676, 'dfc4772cc03cf0b92a47f54fc6a2326e', 1, 22, 1060, 'Modifier complément', 0, NULL, '2017-10-16 21:34:25'),
(30123, 677, '03a18bdd5201e433a3c523a2b34d059a', 1, 22, 1061, 'Ajouter complément', 0, NULL, '2017-10-16 21:34:25'),
(30124, 678, '88d9bc979cd1102eb8196e7f5e6042ca', 1, 22, 1062, 'Encaissement', 0, NULL, '2017-10-16 21:34:25'),
(30125, 678, 'c690cc68f5257c0c225b8b8e6126ea56', 1, 22, 1063, 'Modifier encaissement', 0, NULL, '2017-10-16 21:34:25'),
(30126, 678, '1dc06f602e8630f273d44aa2751b2127', 1, 22, 1064, 'Détails encaissement', 0, NULL, '2017-10-16 21:34:25'),
(30127, 679, 'e4866b292dbc3c9c5d9cc37273a5b498', 1, 22, 1065, 'Ajouter encaissement', 0, NULL, '2017-10-16 21:34:25'),
(30128, 680, '8665be10959f39df4f149962eb70041f', 1, 22, 1066, 'Modifier complément', 0, NULL, '2017-10-16 21:34:25'),
(30129, 681, '585d411904bf7d9e83d21b2810ff1d6c', 1, 22, 1067, 'Modifier encaissement', 0, NULL, '2017-10-16 21:34:25'),
(30130, 682, '8c8b058a4d030cdc8b49c9008abb2e92', 1, 22, 1068, 'Supprimer complément', 0, NULL, '2017-10-16 21:34:25'),
(30131, 683, '6bf7d5180940f03567a5d711e8563ba4', 1, 22, 1069, 'Supprimer encaissement', 0, NULL, '2017-10-16 21:34:25'),
(30132, 684, '256abad0ec8e3bc8ed1c0653ff177255', 1, 22, 1070, 'Valider facture', 0, NULL, '2017-10-16 21:34:25'),
(30133, 685, 'b5dc5719c1f96df7334f371dcf51a5b6', 1, 22, 1071, 'Détail encaissement', 0, NULL, '2017-10-16 21:34:25'),
(30134, 686, '16fbf6fdcbb72f863bcf7e4ef28d8e75', 1, 22, 1072, 'Détails facture', 0, NULL, '2017-10-16 21:34:25'),
(30135, 687, '5efdeb41007109ca99f23f0756217827', 1, 22, 1073, 'Désactiver Facture', 0, NULL, '2017-10-16 21:34:25'),
(30158, 700, '899d40c8f22d4f7a6f048366f1829787', 1, 22, 1096, 'Gestion des contrats', 0, NULL, '2017-10-16 21:34:25'),
(30159, 700, 'a20f4c5b9c9ebaa238757d6f9f9cb6fb', 1, 22, 1097, 'Modifier contrat', 0, NULL, '2017-10-16 21:34:25'),
(30160, 700, 'fbb243d2c2fa4200c40993e527b3a339', 1, 22, 1098, 'Détail contrat', 0, NULL, '2017-10-16 21:34:25'),
(30161, 700, 'e970c1414507e5b83ae39e7ddedbf15e', 1, 22, 1099, 'Valider contrat', 0, NULL, '2017-10-16 21:34:25'),
(30163, 700, '11cabf03a954a5476cc78cf221f04d78', 1, 22, 1101, 'Détails Contrat', 0, NULL, '2017-10-16 21:34:25'),
(30164, 700, '74710492392c157c6fe6d7e79ddc95fa', 1, 22, 1102, 'Renouveler Contrat', 0, NULL, '2017-10-16 21:34:25'),
(30165, 700, 'a717e1a94a251fd4316f34aba679c0c1', 1, 22, 1103, 'Détails   Contrat ', 0, NULL, '2017-10-16 21:34:25'),
(30166, 700, 'cd25d6f0f7f68e3dc35714df632e58df', 1, 22, 1104, ' Détails   Contrat', 0, NULL, '2017-10-16 21:34:25'),
(30167, 700, '9542829fd94f174b96cb33cf94b2758a', 1, 22, 1105, 'Détails   Contrat', 0, NULL, '2017-10-16 21:34:25'),
(30168, 700, '656d41ad5452611636a5d9f966729e39', 1, 22, 1106, 'Renouveler Contrat', 0, NULL, '2017-10-16 21:34:25'),
(30169, 701, '87f4c3ed4713c3bc9e3fef60a6649055', 1, 22, 1107, 'Ajouter contrat', 0, NULL, '2017-10-16 21:34:25'),
(30170, 702, '9e49a431d9637544cefa2869fd7278b9', 1, 22, 1108, 'Modifier contrat', 0, NULL, '2017-10-16 21:34:25'),
(30171, 703, '1e9395a182a44787e493bc038cd80bbf', 1, 22, 1109, 'Supprimer contrat', 0, NULL, '2017-10-16 21:34:25'),
(30172, 704, '460d92834715b149c4db28e1643bd932', 1, 22, 1110, 'Valider contrat', 0, NULL, '2017-10-16 21:34:25'),
(30173, 705, 'bbcf2879c2f8f60cfa55fa97c6e79268', 1, 22, 1111, 'Détail contrat', 0, NULL, '2017-10-16 21:34:25'),
(30174, 706, 'fe058ccb890b25a54866be7f24a40363', 1, 22, 1112, 'Ajouter échéance ', 0, NULL, '2017-10-16 21:34:25'),
(30175, 707, '36a248f56a6a80977e5c90a5c59f39d3', 1, 22, 1113, 'Modifier échéance contrat', 0, NULL, '2017-10-16 21:34:25'),
(30176, 708, 'f0567980556249721f24f2fc88ebfed5', 1, 22, 1114, 'Renouveler Contrat', 0, NULL, '2017-10-16 21:34:25'),
(30177, 709, '56de23d30d6c54297c8d9772cd3c7f57', 1, 22, 1115, 'Utilisateurs', 1, NULL, '2017-10-16 21:34:25'),
(30178, 709, 'e656756fb7b39a4e6ddcabca75ff2970', 1, 22, 1116, 'Editer Utilisateur', 0, NULL, '2017-10-16 21:34:25'),
(30179, 709, 'c073a277957ca1b9f318ac3902555708', 1, 22, 1117, 'Permissions', 0, NULL, '2017-10-16 21:34:25'),
(30180, 709, 'c51499ddf7007787c4434661c658bbd1', 1, 22, 1118, 'Désactiver compte', 0, NULL, '2017-10-16 21:34:25'),
(30181, 709, '10096b6f54456bcfc85081523ee64cf6', 1, 22, 1119, 'Supprimer utilisateur', 0, NULL, '2017-10-16 21:34:25'),
(30182, 709, 'a0999cbed820aff775adf27276ee54a4', 1, 22, 1120, 'Editer Utilisateur', 0, NULL, '2017-10-16 21:34:25'),
(30183, 709, '9aa6877656339ddff2478b20449a924b', 1, 22, 1121, 'Activer compte', 0, NULL, '2017-10-16 21:34:25'),
(30184, 709, 'f4c79bb797b92dfa826b51a44e3171af', 1, 22, 1122, 'Utilisateurs', 0, NULL, '2017-10-16 21:34:25'),
(30185, 709, 'd7f7afd70a297e5c239f6cf271138390', 1, 22, 1123, 'Utilisateur Archivé', 0, NULL, '2017-10-16 21:34:25'),
(30186, 709, '17c98287fb82388423e04d24404cf662', 1, 22, 1124, 'Permissions', 0, NULL, '2017-10-16 21:34:25'),
(30187, 709, '2c1c5556f30585c5b7c93fbbeaa98595', 1, 22, 1125, 'Historique session', 0, NULL, '2017-10-16 21:34:25'),
(30188, 709, '7fd4ca84d162b70d3a4f0bad73e0e4b3', 1, 22, 1126, 'Liste Activitées', 0, NULL, '2017-10-16 21:34:25'),
(30189, 710, 'df91a8e6f8ee2cde64495fc0cc7d6c6f', 1, 22, 1127, 'Ajouter Utilisateurs', 1, NULL, '2017-10-16 21:34:25'),
(30190, 711, '2bb46b52eab9eecbdbba35605da07234', 1, 22, 1128, 'Editer Utilisateurs', 1, NULL, '2017-10-16 21:34:25'),
(30191, 712, '3f59a1326df27378304e142ab3bec090', 1, 22, 1129, 'Permission', 1, NULL, '2017-10-16 21:34:25'),
(30192, 713, 'b919571c88d036f8889742a81a4f41fd', 1, 22, 1130, 'Supprimer utilisateur', 1, NULL, '2017-10-16 21:34:25'),
(30193, 714, '38f89764a26e39ce029cd3132c12b2a5', 1, 22, 1131, 'Compte utilisateur', 1, NULL, '2017-10-16 21:34:25'),
(30194, 715, 'f988a608f35a0bc551cb038b1706d207', 1, 22, 1132, 'Activer utilisateur', 1, NULL, '2017-10-16 21:34:25'),
(30195, 716, 'b7b3a09fdd73a5b0a3e5ed8a2828f548', 1, 22, 1133, 'Désactiver l\'utilisateur', 1, NULL, '2017-10-16 21:34:25'),
(30196, 717, '0d374b7e2fe21a2e2641c092a3c7f2e9', 1, 22, 1134, 'Changer le mot de passe', 1, NULL, '2017-10-16 21:34:25'),
(30197, 718, '6f642ee30722158f0318653b9113b887', 1, 22, 1135, 'History', 1, NULL, '2017-10-16 21:34:25'),
(30198, 719, 'cc907fac13631903d129c137d671d718', 1, 22, 1136, 'Activities', 1, NULL, '2017-10-16 21:34:25'),
(30199, 720, '1eb847d87adcad78d5e951e6110061e5', 1, 22, 1137, 'Gestion Proforma', 0, NULL, '2017-10-16 21:34:25'),
(30200, 720, '44ef6849d8d5d17d8e0535187e923d32', 1, 22, 1138, 'Editer proforma', 0, NULL, '2017-10-16 21:34:25'),
(30201, 720, 'b7ce06be726011362a271678547a803c', 1, 22, 1139, 'Valider Proforma', 0, NULL, '2017-10-16 21:34:25'),
(30202, 720, 'abd8c50f1d2ef4beeeddb68a72973587', 1, 22, 1140, 'Détail Proforma', 0, NULL, '2017-10-16 21:34:25'),
(30203, 720, '35a88b5c359908b063ac98cafc622987', 1, 22, 1141, 'Détail Proforma', 0, NULL, '2017-10-16 21:34:25'),
(30204, 720, 'e20d83df90355eca2a65f56a2556601f', 1, 22, 1142, 'Détail Proforma', 0, NULL, '2017-10-16 21:34:25'),
(30205, 720, '252ed64d8956e20fb88c1be41688f74a', 1, 22, 1143, 'Envoi proforma au client', 0, NULL, '2017-10-16 21:34:25'),
(30206, 721, 'd5a6338765b9eab63104b59f01c06114', 1, 22, 1144, 'Ajouter pro-forma', 0, NULL, '2017-10-16 21:34:25'),
(30207, 722, '95831bde77bc886d6ab4dd5e734de743', 1, 22, 1145, 'Editer proforma', 0, NULL, '2017-10-16 21:34:25'),
(30208, 723, 'cbb4e1efa1c05b42d25a3a6bcab038a2', 1, 22, 1146, 'Ajouter détail proforma', 0, NULL, '2017-10-16 21:34:25'),
(30209, 724, 'e9f745054778257a255452c6609461a0', 1, 22, 1147, 'valider Proforma', 0, NULL, '2017-10-16 21:34:25'),
(30210, 725, 'defef148c404c7e6ac79e4783e0a7ab7', 1, 22, 1148, 'Détail Pro-forma', 0, NULL, '2017-10-16 21:34:25'),
(30211, 726, '53008d64edf241c937a06f03eff139aa', 1, 22, 1149, 'Editer détail proforma', 0, NULL, '2017-10-16 21:34:25'),
(30212, 727, '30e9d3d1ad17eb7f1fc0d5cbb9b58482', 1, 22, 1150, 'Supprimer proforma', 1, NULL, '2017-10-16 21:34:25'),
(30956, 455, 'e69f84a801ee1525f20f34e684688a9b', 1, 23, 652, 'Gestion des catégories de produits', 0, '20', '2017-10-16 21:36:40'),
(30957, 455, '90f6eba3e0ed223e73d250278cb445d5', 1, 23, 653, 'Modifier catégorie', 0, '20', '2017-10-16 21:36:40'),
(30958, 455, 'c62968a45ae9cfa8b127ac1b5573988a', 1, 23, 654, 'Valider catégorie', 0, '20', '2017-10-16 21:36:40'),
(30959, 455, '6f43a6bcbd293f958aff51953559104e', 1, 23, 655, 'Désactiver catégorie', 0, '20', '2017-10-16 21:36:40'),
(30960, 456, 'd26f5940e88a494c0eb65047aab9a17b', 1, 23, 656, 'Ajouter une catégorie', 0, '20', '2017-10-16 21:36:40'),
(30961, 457, '27957c6d0f6869d4d90287cd50b6dde9', 1, 23, 657, 'Modifier une catégorie', 0, '20', '2017-10-16 21:36:40'),
(30962, 458, '41b48dd567e4f79e35261a47b7bad751', 1, 23, 658, 'Valider une catégorie', 0, '20', '2017-10-16 21:36:40'),
(30963, 459, '90dc20c4d1ad7be7fac8ec34d5ac26b3', 1, 23, 659, 'Supprimer une catégorie', 0, '20', '2017-10-16 21:36:40'),
(30964, 333, '6edc543080c65eca3993445c295ff94b', 1, 23, 497, 'Gestion Catégorie Client', 0, '20', '2017-10-16 21:36:40'),
(30965, 333, '142a68a109abd0462ea44fcadffe56de', 1, 23, 506, 'Editer Catégorie Client', 0, '20', '2017-10-16 21:36:40'),
(30966, 333, '70df89fa2654d8b10d7fc7e75e178b7e', 1, 23, 507, 'Activer Catégorie Client', 0, '20', '2017-10-16 21:36:40'),
(30967, 333, '109e82d6db5721f63cd827e9fd224216', 1, 23, 508, 'Désactiver Catégorie Client', 0, '20', '2017-10-16 21:36:40'),
(30968, 334, 'a5c1bd0dfd87824ff0f57c6b1e1d2c3f', 1, 23, 498, 'Ajouter Catégorie Client', 1, '20', '2017-10-16 21:36:40'),
(30969, 335, '8d901f74dfd6ee3a8f44ebd0b83fbfae', 1, 23, 499, 'Editer Catégorie Client', 1, '20', '2017-10-16 21:36:40'),
(30970, 336, 'e87327563ce6b659780d6b2c9bf8ac77', 1, 23, 500, 'Supprimer Catégorie Client', 1, '20', '2017-10-16 21:36:40'),
(30971, 337, 'c955da8d244aac06ee7595d08de7d009', 1, 23, 501, 'Valider Catégorie Client', 1, '20', '2017-10-16 21:36:40'),
(30972, 394, 'f12fb1c50aedc49c3fa3dfa2bd297bd3', 1, 23, 553, 'Gestion Clients', 0, '20', '2017-10-16 21:36:40'),
(30973, 394, 'dd3d5980299911ea854af4fa6f2e7309', 1, 23, 554, 'Editer Client', 0, '20', '2017-10-16 21:36:40'),
(30974, 394, '3c5c04a20d49ad010557a64c8cdac1ce', 1, 23, 555, 'Valider Client', 0, '20', '2017-10-16 21:36:40'),
(30975, 394, '18ace52052f2551099ecaabf049ffaec', 1, 23, 556, 'Désactiver Client', 0, '20', '2017-10-16 21:36:40'),
(30976, 394, '493f9e55fc0340763e07514c1900685a', 1, 23, 557, 'Détails Client', 0, '20', '2017-10-16 21:36:40'),
(30977, 394, '03b4f949b088e41fc9a1f3f23b7906a8', 1, 23, 558, 'Détails  Client', 0, '20', '2017-10-16 21:36:40'),
(30978, 395, '2b9d8bb8f752d1c35fb681c33e38b42b', 1, 23, 559, 'Ajouter Client', 1, '20', '2017-10-16 21:36:40'),
(30979, 396, '54aa9121e05f5e698d354022a8eab71d', 1, 23, 560, 'Editer Client', 1, '20', '2017-10-16 21:36:40'),
(30980, 397, '4eaf650e8c2221d590fac5a6a6952231', 1, 23, 561, 'Supprimer Client', 1, '20', '2017-10-16 21:36:40'),
(30981, 398, '534cd4b17fb8a371d3a20565ab8fd96e', 1, 23, 562, 'Valider Client', 1, '20', '2017-10-16 21:36:40'),
(30982, 399, '95bb6aa696ef630a335aa84e1e425e2c', 1, 23, 563, 'Détails Client', 0, '20', '2017-10-16 21:36:40'),
(30983, 700, '899d40c8f22d4f7a6f048366f1829787', 1, 23, 1096, 'Gestion des contrats', 0, '20', '2017-10-16 21:36:40'),
(30984, 700, 'a20f4c5b9c9ebaa238757d6f9f9cb6fb', 1, 23, 1097, 'Modifier contrat', 0, '20', '2017-10-16 21:36:40'),
(30985, 700, 'fbb243d2c2fa4200c40993e527b3a339', 1, 23, 1098, 'Détail contrat', 0, '20', '2017-10-16 21:36:40'),
(30986, 700, 'e970c1414507e5b83ae39e7ddedbf15e', 1, 23, 1099, 'Valider contrat', 0, '20', '2017-10-16 21:36:40'),
(30988, 700, '11cabf03a954a5476cc78cf221f04d78', 1, 23, 1101, 'Détails Contrat', 0, '20', '2017-10-16 21:36:40'),
(30989, 700, '74710492392c157c6fe6d7e79ddc95fa', 1, 23, 1102, 'Renouveler Contrat', 0, '20', '2017-10-16 21:36:40'),
(30990, 700, 'a717e1a94a251fd4316f34aba679c0c1', 1, 23, 1103, 'Détails   Contrat ', 0, '20', '2017-10-16 21:36:40'),
(30991, 700, 'cd25d6f0f7f68e3dc35714df632e58df', 1, 23, 1104, ' Détails   Contrat', 0, '20', '2017-10-16 21:36:40'),
(30992, 700, '9542829fd94f174b96cb33cf94b2758a', 1, 23, 1105, 'Détails   Contrat', 0, '20', '2017-10-16 21:36:40'),
(30993, 700, '656d41ad5452611636a5d9f966729e39', 1, 23, 1106, 'Renouveler Contrat', 0, '20', '2017-10-16 21:36:40'),
(30994, 701, '87f4c3ed4713c3bc9e3fef60a6649055', 1, 23, 1107, 'Ajouter contrat', 0, '20', '2017-10-16 21:36:40'),
(30995, 702, '9e49a431d9637544cefa2869fd7278b9', 1, 23, 1108, 'Modifier contrat', 0, '20', '2017-10-16 21:36:40'),
(30996, 703, '1e9395a182a44787e493bc038cd80bbf', 1, 23, 1109, 'Supprimer contrat', 0, '20', '2017-10-16 21:36:40'),
(30997, 704, '460d92834715b149c4db28e1643bd932', 1, 23, 1110, 'Valider contrat', 0, '20', '2017-10-16 21:36:40'),
(30998, 705, 'bbcf2879c2f8f60cfa55fa97c6e79268', 1, 23, 1111, 'Détail contrat', 0, '20', '2017-10-16 21:36:40'),
(30999, 706, 'fe058ccb890b25a54866be7f24a40363', 1, 23, 1112, 'Ajouter échéance ', 0, '20', '2017-10-16 21:36:40'),
(31000, 707, '36a248f56a6a80977e5c90a5c59f39d3', 1, 23, 1113, 'Modifier échéance contrat', 0, '20', '2017-10-16 21:36:40'),
(31001, 708, 'f0567980556249721f24f2fc88ebfed5', 1, 23, 1114, 'Renouveler Contrat', 0, '20', '2017-10-16 21:36:40'),
(31002, 609, 'ec45512f34613446e7a2e367d4b4cfbd', 1, 23, 920, 'Gestion Contrats Fournisseurs', 0, '20', '2017-10-16 21:36:40'),
(31003, 609, 'e3c0d7e92dad7f8794b2415c334ec3ff', 1, 23, 921, 'Editer Contrat', 0, '20', '2017-10-16 21:36:40'),
(31004, 609, '9dfff1c8dcb804837200f38e95381420', 1, 23, 922, 'Valider Contrat', 0, '20', '2017-10-16 21:36:40'),
(31005, 609, '9fe39b496077065105a57ccd9ed05863', 1, 23, 923, 'Désactiver Contrat', 0, '20', '2017-10-16 21:36:40'),
(31006, 609, 'faee342ff51dbe9f835529ae5b9b2a0b', 1, 23, 924, 'Détails  Contrat ', 0, '20', '2017-10-16 21:36:40'),
(31007, 609, '83406b6b206ed08878f2b2e854932ae5', 1, 23, 925, 'Détails   Contrat  ', 0, '20', '2017-10-16 21:36:40'),
(31008, 609, '8447888bef30fb983477cc1357ff7e6f', 1, 23, 926, 'Détails    Contrat ', 0, '20', '2017-10-16 21:36:40'),
(31009, 609, '4cc1845128f6a5ff3ed01100292d8ebb', 1, 23, 927, '  Détails    Contrat', 0, '20', '2017-10-16 21:36:40'),
(31010, 609, 'cd82d84c5f70a633b10aae88c34e9159', 1, 23, 928, '  Renouveler   Contrat ', 0, '20', '2017-10-16 21:36:40'),
(31011, 609, 'e9e994a0f8a204f1323fca7ce30931fe', 1, 23, 929, ' Détails  Contrat ', 0, '20', '2017-10-16 21:36:40'),
(31012, 609, 'b9e0a2a0236899590c72d31b878edfb2', 1, 23, 930, ' Renouveler  Contrat ', 0, '20', '2017-10-16 21:36:40'),
(31013, 610, 'ded24eb817021c5a666a677b1565bc5e', 1, 23, 931, 'Ajouter Contrat', 0, '20', '2017-10-16 21:36:40'),
(31014, 611, 'ed6b8695494bf4ed86d5fb18690b3a59', 1, 23, 932, 'Editer Contrat', 0, '20', '2017-10-16 21:36:40'),
(31015, 612, 'b8a40913b5955209994aaa26d0e8c3d4', 1, 23, 933, 'Supprimer Contrat', 0, '20', '2017-10-16 21:36:40'),
(31016, 613, '5efb874e7d73ccd722df806e8275770f', 1, 23, 934, 'Valider Contrat', 0, '20', '2017-10-16 21:36:40'),
(31017, 614, '64a5f976687a8c5f7cd3d672cc5d9c8c', 1, 23, 935, 'Détails Contrat', 0, '20', '2017-10-16 21:36:40'),
(31018, 615, '2cc55c65e79534161108288adb00472b', 1, 23, 936, 'Renouveler  Contrat', 0, '20', '2017-10-16 21:36:40'),
(31019, 432, 'f320732af279d6f2f8ae9c98cd0216de', 1, 23, 613, 'Gestion Départements', 0, '20', '2017-10-16 21:36:40'),
(31020, 432, '96516cd0c72d814d5dcb1d86eacd29ab', 1, 23, 617, 'Editer Département', 0, '20', '2017-10-16 21:36:40'),
(31021, 432, 'ef27a63534fa9fc3bd4b5086a92db546', 1, 23, 619, 'Valider Département', 0, '20', '2017-10-16 21:36:40'),
(31022, 432, '9aed965af4c4b89a5a23c41bf685d403', 1, 23, 620, 'Désactiver Département', 0, '20', '2017-10-16 21:36:40'),
(31023, 433, '722b3ba1c7fe735e87aa7415e5502a4c', 1, 23, 614, 'Ajouter Département', 0, '20', '2017-10-16 21:36:40'),
(31024, 434, 'daeb31006124e562d284aff67360ee19', 1, 23, 615, 'Editer Département', 0, '20', '2017-10-16 21:36:40'),
(31025, 435, 'a775da608fe55c53211d4f1c6e493251', 1, 23, 616, 'Supprimer Département', 0, '20', '2017-10-16 21:36:40'),
(31026, 436, 'bbb96ec910c5000a2006db2f6e8af10a', 1, 23, 618, 'Valider Département', 0, '20', '2017-10-16 21:36:40'),
(31027, 655, '0e79510db7f03b9b6266fc7b4a612153', 1, 23, 1005, 'Gestion Devis', 1, '20', '2017-10-16 21:36:40'),
(31028, 655, 'c15b00a1e37657336df8b6aa0eea2db5', 1, 23, 1006, 'Modifier Devis', 0, '20', '2017-10-16 21:36:40'),
(31029, 655, '7cfdba6bc6bc94c65b97e77746cf49b5', 1, 23, 1007, 'Envoi au client', 0, '20', '2017-10-16 21:36:40'),
(31030, 655, '28e267a2a0647d4cb37b18abb1e7d051', 1, 23, 1008, 'Voir détails', 0, '20', '2017-10-16 21:36:40'),
(31031, 655, 'd34b07afd92adad84e1c4c2ebd92ba95', 1, 23, 1009, 'Voir détails', 0, '20', '2017-10-16 21:36:40'),
(31032, 655, 'f6f55b9d0ba9d704b2861d57cda32477', 1, 23, 1010, 'Réponse Client', 0, '20', '2017-10-16 21:36:40'),
(31033, 655, '4b11c0bfb3f970a541100f7fc334927e', 1, 23, 1011, 'Voir détails', 0, '20', '2017-10-16 21:36:40'),
(31034, 655, '61a0655c2c13039b5b8262b82ae6cb51', 1, 23, 1012, 'Voir détails', 0, '20', '2017-10-16 21:36:40'),
(31035, 655, 'ed30dfbb21d8d24a0da432252358bdf8', 1, 23, 1013, 'Voir détails', 0, '20', '2017-10-16 21:36:40'),
(31036, 655, '7bd2e025ffb3893dea4776e152301716', 1, 23, 1014, 'Débloquer devis', 0, '20', '2017-10-16 21:36:40'),
(31037, 655, '6d9ec7d9ebebcdc921376e7bf0c9fdaf', 1, 23, 1015, 'Valider devis', 0, '20', '2017-10-16 21:36:40'),
(31038, 655, 'b1bcc4a4ab154f110abcf54c0c659fb3', 1, 23, 1016, 'Voir détails', 0, '20', '2017-10-16 21:36:40'),
(31039, 655, '91a90a46e3430c491ab8db654b6e87c4', 1, 23, 1017, 'Voir détails', 0, '20', '2017-10-16 21:36:40'),
(31040, 656, 'd9eeb330625c1b87e0df00986a47be01', 1, 23, 1018, 'Ajouter Devis', 0, '20', '2017-10-16 21:36:40'),
(31041, 657, 'da93cdb05137e15aed9c4c18bddd746a', 1, 23, 1019, 'Ajouter détail devis', 0, '20', '2017-10-16 21:36:40'),
(31042, 658, 'f9f3c299f9bd0fec014f6bd3f0e06adb', 1, 23, 1020, 'Modifier Devis', 0, '20', '2017-10-16 21:36:40'),
(31043, 659, 'e14cce6f1faf7784adb327581c516b90', 1, 23, 1021, 'Supprimer Devis', 0, '20', '2017-10-16 21:36:40'),
(31044, 660, '38f10871792c133ebcc6040e9a11cde8', 1, 23, 1022, 'Modifier détail Devis', 0, '20', '2017-10-16 21:36:40'),
(31045, 661, '8def42e75fd4aee61c378d9fb303850d', 1, 23, 1023, 'Afficher détail devis', 0, '20', '2017-10-16 21:36:40'),
(31046, 662, '7666e87783b0f5a7eec1eea7593f7dfe', 1, 23, 1024, 'Valider Devis', 0, '20', '2017-10-16 21:36:40'),
(31047, 663, '7f1c48324d8c369da9aa6ab8af35acd8', 1, 23, 1025, 'Validation Client Devis', 0, '20', '2017-10-16 21:36:40'),
(31048, 664, '6adf896091dde0df89f777f31606953c', 1, 23, 1026, 'Débloquer devis', 0, '20', '2017-10-16 21:36:40'),
(31049, 665, '15cbb79dd4a74266158e6b29a83e683c', 1, 23, 1027, 'Archiver Devis', 1, '20', '2017-10-16 21:36:40'),
(31050, 675, '4c924acb9adc87d8389e8f9842a965c5', 1, 23, 1047, 'Gestion des factures', 0, '20', '2017-10-16 21:36:40'),
(31051, 675, '98a697ec628778765b25e02ba2929d38', 1, 23, 1048, 'Liste complément', 0, '20', '2017-10-16 21:36:40'),
(31052, 675, 'f8b20f7fec99b45b967a431d64b7f061', 1, 23, 1049, 'Liste encaissements', 0, '20', '2017-10-16 21:36:40'),
(31053, 675, '9a51fb5298e39a28af3ad6272fc51177', 1, 23, 1050, 'Valider facture', 0, '20', '2017-10-16 21:36:40'),
(31054, 675, '851f1d4c13f6025f69f5b9315321d350', 1, 23, 1051, 'Désactiver facture', 0, '20', '2017-10-16 21:36:40'),
(31055, 675, '5c79105956d28b5cac52f85784039919', 1, 23, 1052, 'Détail facture', 0, '20', '2017-10-16 21:36:40'),
(31056, 675, '7892721423af84a0b54e90250cf27ee3', 1, 23, 1053, 'Détails Facture', 0, '20', '2017-10-16 21:36:40'),
(31057, 675, 'b5380d403c9947ce060963f28e6d7539', 1, 23, 1054, 'Envoyer au client', 0, '20', '2017-10-16 21:36:40'),
(31058, 675, '80a4b2643b95c2836e968411811d3c21', 1, 23, 1055, 'Détails facture', 0, '20', '2017-10-16 21:36:40'),
(31059, 675, '2f679be3c0d7b88529209f86745f9028', 1, 23, 1056, 'Détails facture', 0, '20', '2017-10-16 21:36:40'),
(31060, 675, '429558e9a1e899c11051ea5c9a4f7942', 1, 23, 1057, 'Détails facture', 0, '20', '2017-10-16 21:36:40'),
(31061, 675, '3acd11d8d74fb7e1ba8d5721e96f91bd', 1, 23, 1058, 'Liste encaissements', 0, '20', '2017-10-16 21:36:40'),
(31062, 676, '55c3c5d2d93143b315513b7401043c8b', 1, 23, 1059, 'complements', 0, '20', '2017-10-16 21:36:40'),
(31063, 676, 'dfc4772cc03cf0b92a47f54fc6a2326e', 1, 23, 1060, 'Modifier complément', 0, '20', '2017-10-16 21:36:40'),
(31064, 677, '03a18bdd5201e433a3c523a2b34d059a', 1, 23, 1061, 'Ajouter complément', 0, '20', '2017-10-16 21:36:40'),
(31065, 678, '88d9bc979cd1102eb8196e7f5e6042ca', 1, 23, 1062, 'Encaissement', 0, '20', '2017-10-16 21:36:40'),
(31066, 678, 'c690cc68f5257c0c225b8b8e6126ea56', 1, 23, 1063, 'Modifier encaissement', 0, '20', '2017-10-16 21:36:40'),
(31067, 678, '1dc06f602e8630f273d44aa2751b2127', 1, 23, 1064, 'Détails encaissement', 0, '20', '2017-10-16 21:36:40'),
(31068, 679, 'e4866b292dbc3c9c5d9cc37273a5b498', 1, 23, 1065, 'Ajouter encaissement', 0, '20', '2017-10-16 21:36:40'),
(31069, 680, '8665be10959f39df4f149962eb70041f', 1, 23, 1066, 'Modifier complément', 0, '20', '2017-10-16 21:36:40'),
(31070, 681, '585d411904bf7d9e83d21b2810ff1d6c', 1, 23, 1067, 'Modifier encaissement', 0, '20', '2017-10-16 21:36:40'),
(31071, 682, '8c8b058a4d030cdc8b49c9008abb2e92', 1, 23, 1068, 'Supprimer complément', 0, '20', '2017-10-16 21:36:40'),
(31072, 683, '6bf7d5180940f03567a5d711e8563ba4', 1, 23, 1069, 'Supprimer encaissement', 0, '20', '2017-10-16 21:36:40'),
(31073, 684, '256abad0ec8e3bc8ed1c0653ff177255', 1, 23, 1070, 'Valider facture', 0, '20', '2017-10-16 21:36:40'),
(31074, 685, 'b5dc5719c1f96df7334f371dcf51a5b6', 1, 23, 1071, 'Détail encaissement', 0, '20', '2017-10-16 21:36:40'),
(31075, 686, '16fbf6fdcbb72f863bcf7e4ef28d8e75', 1, 23, 1072, 'Détails facture', 0, '20', '2017-10-16 21:36:40'),
(31076, 687, '5efdeb41007109ca99f23f0756217827', 1, 23, 1073, 'Désactiver Facture', 0, '20', '2017-10-16 21:36:40'),
(31077, 502, '6beb279abea6434e3b73229aebadc081', 1, 23, 725, 'Gestion Fournisseurs', 0, '20', '2017-10-16 21:36:40'),
(31078, 502, 'ff95747f3a590b6539803f2a9a394cd5', 1, 23, 730, 'Editer Fournisseur', 0, '20', '2017-10-16 21:36:40'),
(31079, 502, 'fea982f5074995d4ccd6211a71ab2680', 1, 23, 731, 'Valider Fournisseur', 0, '20', '2017-10-16 21:36:40'),
(31080, 502, '1d0411a0dec15fc28f054f1a79d95618', 1, 23, 732, 'Désactiver Fournisseur', 0, '20', '2017-10-16 21:36:40'),
(31081, 502, 'a52affdd109b9362ce47ff18aad53e2a', 1, 23, 737, 'Détails Fournisseur', 0, '20', '2017-10-16 21:36:40'),
(31082, 502, 'c6fe5f222dd563204188e8bf0d69bd9e', 1, 23, 738, 'Détails  Fournisseur', 0, '20', '2017-10-16 21:36:40'),
(31083, 503, 'd644015625a9603adb2fcc36167aeb73', 1, 23, 726, 'Ajouter Fournisseur', 0, '20', '2017-10-16 21:36:40'),
(31084, 504, '58c6694abfd3228d927a5d5a06d40b94', 1, 23, 727, 'Editer Fournisseur', 0, '20', '2017-10-16 21:36:40'),
(31085, 505, 'd072f81cd779e4b0152953241d713ca3', 1, 23, 728, 'Supprimer Fournisseur', 0, '20', '2017-10-16 21:36:40'),
(31086, 506, '657351ce5aa227513e3b50dea77db918', 1, 23, 729, 'Valider Fournisseur', 0, '20', '2017-10-16 21:36:40'),
(31087, 508, '83b693fe35a1be29edafe4f6170641aa', 1, 23, 736, 'Détails Fournisseur', 0, '20', '2017-10-16 21:36:40'),
(31088, 542, '72db1c2280dc3eb6405908c1c5b6c815', 1, 23, 810, 'Information société', 0, '20', '2017-10-16 21:36:40'),
(31089, 637, 'b8e62907d367fb44d644a5189cd07f42', 1, 23, 978, 'Modules', 1, '20', '2017-10-16 21:36:40'),
(31090, 637, '05ce9e55686161d99e0714bb86243e5b', 1, 23, 979, 'Editer Module', 0, '20', '2017-10-16 21:36:40'),
(31091, 637, '819cf9c18a44cb80771a066768d585f2', 1, 23, 980, 'Exporter Module', 0, '20', '2017-10-16 21:36:40'),
(31092, 637, 'd2fc3ee15cee5208a8b9c70f1e53c196', 1, 23, 981, 'Liste task modul', 0, '20', '2017-10-16 21:36:40'),
(31093, 637, 'ad75e6b877f20e3d6fc1789da4dcb3e6', 1, 23, 982, 'Editer Module', 0, '20', '2017-10-16 21:36:40'),
(31094, 637, '064a9b0eff1006fd4f25cb4eaf894ca1', 1, 23, 983, 'Liste task modul Setting', 0, '20', '2017-10-16 21:36:40'),
(31095, 637, 'ac4eb0c94da00a48ad5d995f5e9e9366', 1, 23, 984, 'MAJ Module', 0, '20', '2017-10-16 21:36:40'),
(31096, 638, '44bd5341b0ab41ced21db8b3e92cf5aa', 1, 23, 985, 'Ajouter un Modul', 1, '20', '2017-10-16 21:36:40'),
(31097, 640, '8653b156f1a4160a12e5a94b211e59a2', 1, 23, 986, 'Liste Action Task', 0, '20', '2017-10-16 21:36:40'),
(31098, 640, '86aced763bc02e1957a5c740fb37b4f7', 1, 23, 987, 'Supprimer Application', 0, '20', '2017-10-16 21:36:40'),
(31099, 640, 'f07352e32fe86da1483c6ab071b7e7a9', 1, 23, 988, 'Ajout Affichage WF', 0, '20', '2017-10-16 21:36:40'),
(31100, 641, '1c452aff8f1551b3574e15b74147ea56', 1, 23, 989, 'Ajouter Task Modul', 1, '20', '2017-10-16 21:36:40'),
(31101, 642, 'f085fe4610576987db963501297e4d91', 1, 23, 990, 'Editer Task Modul', 1, '20', '2017-10-16 21:36:40'),
(31102, 642, '38702c272a6f4d334c2f4c3684c8b163', 1, 23, 991, 'Ajouter action modul', 1, '20', '2017-10-16 21:36:40'),
(31103, 643, 'cbae1ebe850f6dd8841426c6fedf1785', 1, 23, 992, 'Liste Action Task', 1, '20', '2017-10-16 21:36:40'),
(31104, 643, 'e30471396f9b86ccdcc94943d80b679a', 1, 23, 993, 'Editer Task Action', 0, '20', '2017-10-16 21:36:40'),
(31105, 644, '502460cd9327b46ee7af0a258ebf8c80', 1, 23, 994, 'Ajouter Action Task', 1, '20', '2017-10-16 21:36:40'),
(31106, 645, '13c107211904d4a2e65dd65c60ec7272', 1, 23, 995, 'Supprimer Application', 1, '20', '2017-10-16 21:36:40'),
(31107, 646, '8c8acf9cf3790b16b1fae26823f45eab', 1, 23, 996, 'Importer des modules', 1, '20', '2017-10-16 21:36:40'),
(31108, 647, '2f4518dab90b706e2f4acd737a0425d8', 1, 23, 997, 'Ajouter Module paramétrage', 1, '20', '2017-10-16 21:36:40'),
(31109, 648, '8e0c0212d8337956ac2f4d6eb180d74b', 1, 23, 998, 'Editer Module paramètrage', 1, '20', '2017-10-16 21:36:40'),
(31110, 649, 'fc54953b47b7fcb11cc14c0c2e2125f0', 1, 23, 999, 'Ajouter Autorisation Etat', 1, '20', '2017-10-16 21:36:40'),
(31111, 650, '966ec2dd83e6006c2d0ff1d1a5f12e33', 1, 23, 1000, 'Editer Task Action', 1, '20', '2017-10-16 21:36:40'),
(31112, 651, '3473119f6683893a3f1372dbf7d811e1', 1, 23, 1001, 'MAJ Module', 1, '20', '2017-10-16 21:36:40'),
(31113, 652, '2e2346bd422536c1d996ff25f9e71357', 1, 23, 1002, 'Dupliquer Action Task', 0, '20', '2017-10-16 21:36:40'),
(31114, 653, '8a3634181ae5bc9223b689a310158962', 1, 23, 1003, 'Supprimer Task action', 0, '20', '2017-10-16 21:36:40'),
(31115, 654, '8afb3c669307183cd3b7d189fbf204d7', 1, 23, 1004, 'Affichage Work Flow', 0, '20', '2017-10-16 21:36:40'),
(31116, 475, '605450f3d7c84701b986fa31e1e9fa43', 1, 23, 684, 'Gestion Pays', 0, '20', '2017-10-16 21:36:40'),
(31117, 475, '29ba6cc689eca63dbafb109ec58bc4d6', 1, 23, 689, 'Editer Pays', 0, '20', '2017-10-16 21:36:40'),
(31118, 475, '763fe13212b4324590518773cd9a36fa', 1, 23, 690, 'Valider Pays', 0, '20', '2017-10-16 21:36:40'),
(31119, 475, '3c8427c7313d35219b17572efd380b17', 1, 23, 691, 'Désactiver Pays', 0, '20', '2017-10-16 21:36:40'),
(31120, 476, '3cd55a55307615d72aae84c6b5cf99bc', 1, 23, 685, 'Ajouter Pays', 0, '20', '2017-10-16 21:36:40'),
(31121, 477, 'cfe617d7bc6a9c7d8b86c468f21396f2', 1, 23, 686, 'Editer Pays', 0, '20', '2017-10-16 21:36:40'),
(31122, 478, 'b768486aeb655c48cc411c11fa60e150', 1, 23, 687, 'Supprimer Pays', 0, '20', '2017-10-16 21:36:40'),
(31123, 479, '15e4e24f320daa9d563ae62acff9e586', 1, 23, 688, 'Valider Pays', 0, '20', '2017-10-16 21:36:40'),
(31146, 720, '1eb847d87adcad78d5e951e6110061e5', 1, 23, 1137, 'Gestion Proforma', 0, '20', '2017-10-16 21:36:40'),
(31147, 720, '44ef6849d8d5d17d8e0535187e923d32', 1, 23, 1138, 'Editer proforma', 0, '20', '2017-10-16 21:36:40'),
(31148, 720, 'b7ce06be726011362a271678547a803c', 1, 23, 1139, 'Valider Proforma', 0, '20', '2017-10-16 21:36:40'),
(31149, 720, 'abd8c50f1d2ef4beeeddb68a72973587', 1, 23, 1140, 'Détail Proforma', 0, '20', '2017-10-16 21:36:40'),
(31150, 720, '35a88b5c359908b063ac98cafc622987', 1, 23, 1141, 'Détail Proforma', 0, '20', '2017-10-16 21:36:40'),
(31151, 720, 'e20d83df90355eca2a65f56a2556601f', 1, 23, 1142, 'Détail Proforma', 0, '20', '2017-10-16 21:36:40'),
(31152, 720, '252ed64d8956e20fb88c1be41688f74a', 1, 23, 1143, 'Envoi proforma au client', 0, '20', '2017-10-16 21:36:40'),
(31153, 721, 'd5a6338765b9eab63104b59f01c06114', 1, 23, 1144, 'Ajouter pro-forma', 0, '20', '2017-10-16 21:36:40'),
(31154, 722, '95831bde77bc886d6ab4dd5e734de743', 1, 23, 1145, 'Editer proforma', 0, '20', '2017-10-16 21:36:40'),
(31155, 723, 'cbb4e1efa1c05b42d25a3a6bcab038a2', 1, 23, 1146, 'Ajouter détail proforma', 0, '20', '2017-10-16 21:36:40'),
(31156, 724, 'e9f745054778257a255452c6609461a0', 1, 23, 1147, 'valider Proforma', 0, '20', '2017-10-16 21:36:40'),
(31157, 725, 'defef148c404c7e6ac79e4783e0a7ab7', 1, 23, 1148, 'Détail Pro-forma', 0, '20', '2017-10-16 21:36:40'),
(31158, 726, '53008d64edf241c937a06f03eff139aa', 1, 23, 1149, 'Editer détail proforma', 0, '20', '2017-10-16 21:36:40'),
(31159, 727, '30e9d3d1ad17eb7f1fc0d5cbb9b58482', 1, 23, 1150, 'Supprimer proforma', 1, '20', '2017-10-16 21:36:40'),
(31160, 470, 'd57b16b3aad4ce59f909609246c4fd36', 1, 23, 676, 'Gestion des régions', 0, '20', '2017-10-16 21:36:40'),
(31161, 470, 'd2e007184668dd70b9bae44d46d28ded', 1, 23, 677, 'Modifier région', 0, '20', '2017-10-16 21:36:40'),
(31162, 470, 'e74403c99ac8325b78735c531a20442f', 1, 23, 678, 'Valider région', 0, '20', '2017-10-16 21:36:40'),
(31163, 470, '7397a0fab078728bd5c53be61022d5ce', 1, 23, 679, 'Désactiver région', 0, '20', '2017-10-16 21:36:40'),
(31164, 471, '0237bd41cf70c3529681b4ccb041f1fd', 1, 23, 680, 'Ajouter région', 0, '20', '2017-10-16 21:36:40'),
(31165, 472, '6d290f454da473cb8a557829a410c3f1', 1, 23, 681, 'Modifier région', 0, '20', '2017-10-16 21:36:40'),
(31166, 473, '008cd9ea5767c739675fef4e1261cfe8', 1, 23, 682, 'Valider région', 0, '20', '2017-10-16 21:36:40'),
(31167, 474, 'fc477e6a4c90cd427ae81e555c11d6a9', 1, 23, 683, 'Supprimer région', 0, '20', '2017-10-16 21:36:40'),
(31168, 34, '83b9fa44466da4bcd7f8304185bfeac8', 1, 23, 28, 'Services', 0, '20', '2017-10-16 21:36:40'),
(31169, 34, '3c388c1e842851df49abe9ee73c0a2e7', 1, 23, 33, 'Valider Service', 0, '20', '2017-10-16 21:36:40'),
(31170, 34, 'dcb9555d5ca1d108e9fa95daa9da4b3a', 1, 23, 34, 'Supprimer Service', 0, '20', '2017-10-16 21:36:40'),
(31171, 34, '74950fb3fd858404b6048c1e81bd7c9a', 1, 23, 144, 'Modifier Service', 0, '20', '2017-10-16 21:36:40'),
(31172, 35, '55043bc4207521e3010e91d6267f5302', 1, 23, 29, 'Ajouter Service', 1, '20', '2017-10-16 21:36:40'),
(31173, 36, '2fea3d893f6b6e81467ddd2a744e4a76', 1, 23, 30, 'Modifier Service', 1, '20', '2017-10-16 21:36:40'),
(31174, 37, '1a0d5897d31b4d5e29022671c1112f59', 1, 23, 31, 'Valider Service', 1, '20', '2017-10-16 21:36:40'),
(31175, 38, '42083d4e159baf7c2ace2bb977e2b0a0', 1, 23, 32, 'Supprimer Service', 1, '20', '2017-10-16 21:36:40'),
(31176, 543, 'a1c5a2657cc1b2ff6f85c6fe8f1c51ac', 1, 23, 811, 'Paramètrage Système', 0, '20', '2017-10-16 21:36:40'),
(31177, 543, 'de6285d9c0027ff8bccdf2af385ac337', 1, 23, 812, 'Editer paramètre', 0, '20', '2017-10-16 21:36:40'),
(31178, 544, '82f83d9d3d30fdef00d4c3ef96f0f899', 1, 23, 813, 'Ajouter Paramètre', 0, '20', '2017-10-16 21:36:40'),
(31179, 545, 'f0e54f346e9dcfdff65274709ce2c8ca', 1, 23, 814, 'Editer paramètre', 0, '20', '2017-10-16 21:36:40'),
(31180, 546, 'aaccd24eaf085b8f18115c9c7653d401', 1, 23, 815, 'Supprimer Paramètre', 0, '20', '2017-10-16 21:36:40'),
(31181, 460, 'b6b6bfbd070b5b3dd84acedae7b854e9', 1, 23, 660, 'Gestion des types de produits', 0, '20', '2017-10-16 21:36:40'),
(31182, 460, '3c5400b775264499825a039d66aa9c90', 1, 23, 661, 'Modifier type', 0, '20', '2017-10-16 21:36:40'),
(31183, 460, 'dcf55bc300d690af4c81e4d2335e60e5', 1, 23, 662, 'Valider type', 0, '20', '2017-10-16 21:36:40'),
(31184, 460, '230b9554d37da1c71986af94962cb340', 1, 23, 663, 'Désactiver type', 0, '20', '2017-10-16 21:36:40'),
(31185, 461, 'e0d163499b4ba11d6d7a648bc6fc6de6', 1, 23, 664, 'Ajouter un type', 0, '20', '2017-10-16 21:36:40'),
(31186, 462, 'ac5a6d087b3c8db7501fa5137a47773e', 1, 23, 665, 'Modifier type', 0, '20', '2017-10-16 21:36:40'),
(31187, 463, '2e8242a93a62a264ad7cfc953967f575', 1, 23, 666, 'Valider type', 0, '20', '2017-10-16 21:36:40'),
(31188, 464, 'e3725ba15ca483b9278f68553eca5918', 1, 23, 667, 'Supprimer type', 0, '20', '2017-10-16 21:36:40'),
(31189, 480, '312fd18860781a7b1b7e33587fa423d4', 1, 23, 692, 'Gestion Type Echeance', 0, '20', '2017-10-16 21:36:40'),
(31190, 480, '46ad76148075d6b458f43e84ddf00791', 1, 23, 697, 'Editer Type Echéance', 0, '20', '2017-10-16 21:36:40'),
(31191, 480, 'add2bf057924e606653fbf5bbd65ca09', 1, 23, 698, 'Valider Type Echéance', 0, '20', '2017-10-16 21:36:40'),
(31192, 480, '463d9e1e8367736b958f0dd84b4e36d5', 1, 23, 699, 'Désactiver Type Echéance', 0, '20', '2017-10-16 21:36:40'),
(31193, 481, '76170b14c7b6f1f7058d079fe24f739b', 1, 23, 693, 'Ajouter Type Echéance', 0, '20', '2017-10-16 21:36:40'),
(31194, 482, 'decc5ed58c4d91e6967c9c67e0975cf0', 1, 23, 694, 'Editer Type Echéance', 0, '20', '2017-10-16 21:36:40'),
(31195, 483, '89db6f23dd8e96a69c6a97f556c44e14', 1, 23, 695, 'Supprimer Type Echéance', 0, '20', '2017-10-16 21:36:40'),
(31196, 484, '7527021168823e0118d44297c7684d44', 1, 23, 696, 'Valider Type Echéance', 0, '20', '2017-10-16 21:36:40'),
(31197, 465, '55ecbb545a49c70c0b728bb0c7951077', 1, 23, 668, 'Gestion des unités de vente', 0, '20', '2017-10-16 21:36:40'),
(31198, 465, '67acd70eb04242b7091d9dcbb08295d7', 1, 23, 669, 'Modifier unité ', 0, '20', '2017-10-16 21:36:40'),
(31199, 465, '7363022ed5ad047bfe86d3de4b75b1f4', 1, 23, 670, 'Valider unité', 0, '20', '2017-10-16 21:36:40'),
(31200, 465, 'ec77eb95736c27bfc269cbffc8e113f1', 1, 23, 671, 'Désactiver unité', 0, '20', '2017-10-16 21:36:40'),
(31201, 466, '3a5e8dfe211121eda706f8b6d548d111', 1, 23, 672, 'ajouter une unité', 0, '20', '2017-10-16 21:36:40'),
(31202, 467, '9b7a578981de699286376903e96bc3c7', 1, 23, 673, 'Modifier une unité', 0, '20', '2017-10-16 21:36:40'),
(31203, 468, '62355588366c13ddbadc7a7ca1d226ad', 1, 23, 674, 'Valider une unité', 0, '20', '2017-10-16 21:36:40'),
(31204, 469, 'e5f53a3aaa324415d781156396938101', 1, 23, 675, 'Supprimer une unité', 0, '20', '2017-10-16 21:36:40'),
(31205, 709, '56de23d30d6c54297c8d9772cd3c7f57', 1, 23, 1115, 'Utilisateurs', 1, '20', '2017-10-16 21:36:40'),
(31206, 709, 'e656756fb7b39a4e6ddcabca75ff2970', 1, 23, 1116, 'Editer Utilisateur', 0, '20', '2017-10-16 21:36:40'),
(31207, 709, 'c073a277957ca1b9f318ac3902555708', 1, 23, 1117, 'Permissions', 0, '20', '2017-10-16 21:36:40'),
(31208, 709, 'c51499ddf7007787c4434661c658bbd1', 1, 23, 1118, 'Désactiver compte', 0, '20', '2017-10-16 21:36:40'),
(31209, 709, '10096b6f54456bcfc85081523ee64cf6', 1, 23, 1119, 'Supprimer utilisateur', 0, '20', '2017-10-16 21:36:40'),
(31210, 709, 'a0999cbed820aff775adf27276ee54a4', 1, 23, 1120, 'Editer Utilisateur', 0, '20', '2017-10-16 21:36:40'),
(31211, 709, '9aa6877656339ddff2478b20449a924b', 1, 23, 1121, 'Activer compte', 0, '20', '2017-10-16 21:36:40'),
(31212, 709, 'f4c79bb797b92dfa826b51a44e3171af', 1, 23, 1122, 'Utilisateurs', 0, '20', '2017-10-16 21:36:40'),
(31213, 709, 'd7f7afd70a297e5c239f6cf271138390', 1, 23, 1123, 'Utilisateur Archivé', 0, '20', '2017-10-16 21:36:40'),
(31214, 709, '17c98287fb82388423e04d24404cf662', 1, 23, 1124, 'Permissions', 0, '20', '2017-10-16 21:36:40'),
(31215, 709, '2c1c5556f30585c5b7c93fbbeaa98595', 1, 23, 1125, 'Historique session', 0, '20', '2017-10-16 21:36:40'),
(31216, 709, '7fd4ca84d162b70d3a4f0bad73e0e4b3', 1, 23, 1126, 'Liste Activitées', 0, '20', '2017-10-16 21:36:40'),
(31217, 710, 'df91a8e6f8ee2cde64495fc0cc7d6c6f', 1, 23, 1127, 'Ajouter Utilisateurs', 1, '20', '2017-10-16 21:36:40'),
(31218, 711, '2bb46b52eab9eecbdbba35605da07234', 1, 23, 1128, 'Editer Utilisateurs', 1, '20', '2017-10-16 21:36:40'),
(31219, 712, '3f59a1326df27378304e142ab3bec090', 1, 23, 1129, 'Permission', 1, '20', '2017-10-16 21:36:40'),
(31220, 713, 'b919571c88d036f8889742a81a4f41fd', 1, 23, 1130, 'Supprimer utilisateur', 1, '20', '2017-10-16 21:36:40'),
(31221, 714, '38f89764a26e39ce029cd3132c12b2a5', 1, 23, 1131, 'Compte utilisateur', 1, '20', '2017-10-16 21:36:40'),
(31222, 715, 'f988a608f35a0bc551cb038b1706d207', 1, 23, 1132, 'Activer utilisateur', 1, '20', '2017-10-16 21:36:40'),
(31223, 716, 'b7b3a09fdd73a5b0a3e5ed8a2828f548', 1, 23, 1133, 'Désactiver l\'utilisateur', 1, '20', '2017-10-16 21:36:40'),
(31224, 717, '0d374b7e2fe21a2e2641c092a3c7f2e9', 1, 23, 1134, 'Changer le mot de passe', 1, '20', '2017-10-16 21:36:40'),
(31225, 718, '6f642ee30722158f0318653b9113b887', 1, 23, 1135, 'History', 1, '20', '2017-10-16 21:36:40'),
(31226, 719, 'cc907fac13631903d129c137d671d718', 1, 23, 1136, 'Activities', 1, '20', '2017-10-16 21:36:40'),
(31227, 430, '3d4eaa53061f51b0c4435bd8e4b89c17', 1, 23, 611, 'Gestion Vente', 0, '20', '2017-10-16 21:36:40'),
(31228, 89, '2c3b01c696ff401a2ac9ffedb7a06e4a', 1, 23, 114, 'Gestion Villes', 1, '20', '2017-10-16 21:36:40'),
(31229, 89, 'b9649163b368f863a0e8036f11cd81ae', 1, 23, 119, 'Editer Ville', 0, '20', '2017-10-16 21:36:40'),
(31230, 89, '89dec6dabcb210cdb9dd28bbef90d43e', 1, 23, 121, 'Editer Ville', 0, '20', '2017-10-16 21:36:40'),
(31231, 89, '4a2edbdcbda34c9d3d1e6abe73643b37', 1, 23, 602, 'Valider Ville', 0, '20', '2017-10-16 21:36:40'),
(31232, 89, '0c8ad6595a4516be83ba5a9cdb7ea9a1', 1, 23, 603, 'Désactiver Ville', 0, '20', '2017-10-16 21:36:40'),
(31233, 90, 'e152b9052d3dcfcac593489dbdc0f61c', 1, 23, 115, 'Ajouter ville', 1, '20', '2017-10-16 21:36:40'),
(31234, 91, '3107e0cd0e0df14c4e94aa088e4457d7', 1, 23, 116, 'Editer Ville', 1, '20', '2017-10-16 21:36:40'),
(31235, 92, 'da79d9214ed5819d7f4f1e3070629a3d', 1, 23, 117, 'Supprimer Ville', 1, '20', '2017-10-16 21:36:40'),
(31236, 423, 'fe03a68d17c62ff2c27329573a1b3550', 1, 23, 601, 'Valider Ville', 0, '20', '2017-10-16 21:36:40'),
(31238, 728, '192715027870a4a612fd44d562e2752f', 2, 2, 1151, 'Gestion des produits', 0, '22', '2017-10-16 21:39:54'),
(31239, 728, '192715027870a4a612fd44d562e2752f', 1, 22, 1151, 'Gestion des produits', 0, '22', '2017-10-16 21:39:54'),
(31240, 728, '192715027870a4a612fd44d562e2752f', 1, 23, 1151, 'Gestion des produits', 0, '22', '2017-10-16 21:39:54'),
(31242, 728, 'ed13b17897a396c0633d7989f2bc644f', 2, 2, 1152, 'Modifier produit', 0, '22', '2017-10-16 21:39:54'),
(31243, 728, 'ed13b17897a396c0633d7989f2bc644f', 1, 22, 1152, 'Modifier produit', 0, '22', '2017-10-16 21:39:54'),
(31244, 728, 'ed13b17897a396c0633d7989f2bc644f', 1, 23, 1152, 'Modifier produit', 0, '22', '2017-10-16 21:39:54'),
(31246, 728, '96df3c4057988c54a7d468e5664dba10', 2, 2, 1153, 'Détail produit', 0, '22', '2017-10-16 21:39:54'),
(31247, 728, '96df3c4057988c54a7d468e5664dba10', 1, 22, 1153, 'Détail produit', 0, '22', '2017-10-16 21:39:54'),
(31248, 728, '96df3c4057988c54a7d468e5664dba10', 1, 23, 1153, 'Détail produit', 0, '22', '2017-10-16 21:39:54'),
(31250, 728, 'eb5b51394e164f00ce8c998310e3a8ba', 2, 2, 1154, 'Valider produit', 0, '22', '2017-10-16 21:39:54'),
(31251, 728, 'eb5b51394e164f00ce8c998310e3a8ba', 1, 22, 1154, 'Valider produit', 0, '22', '2017-10-16 21:39:54'),
(31252, 728, 'eb5b51394e164f00ce8c998310e3a8ba', 1, 23, 1154, 'Valider produit', 0, '22', '2017-10-16 21:39:54'),
(31254, 728, '6b087b20929483bb07f8862b39e41f07', 2, 2, 1155, 'Désactiver produit', 0, '22', '2017-10-16 21:39:54'),
(31255, 728, '6b087b20929483bb07f8862b39e41f07', 1, 22, 1155, 'Désactiver produit', 0, '22', '2017-10-16 21:39:54'),
(31256, 728, '6b087b20929483bb07f8862b39e41f07', 1, 23, 1155, 'Désactiver produit', 0, '22', '2017-10-16 21:39:54'),
(31258, 728, '3fe9362cc0a931940b8d5dd40338c9c8', 2, 2, 1156, 'Achat produit', 0, '22', '2017-10-16 21:39:54'),
(31259, 728, '3fe9362cc0a931940b8d5dd40338c9c8', 1, 22, 1156, 'Achat produit', 0, '22', '2017-10-16 21:39:54'),
(31260, 728, '3fe9362cc0a931940b8d5dd40338c9c8', 1, 23, 1156, 'Achat produit', 0, '22', '2017-10-16 21:39:54'),
(31262, 728, '41b9c4b7028269d4540915d6ec14ee79', 2, 2, 1157, 'Détails Produit', 0, '22', '2017-10-16 21:39:54'),
(31263, 728, '41b9c4b7028269d4540915d6ec14ee79', 1, 22, 1157, 'Détails Produit', 0, '22', '2017-10-16 21:39:54'),
(31264, 728, '41b9c4b7028269d4540915d6ec14ee79', 1, 23, 1157, 'Détails Produit', 0, '22', '2017-10-16 21:39:54'),
(31266, 729, '93e893c307a6fa63e392f78751ec70ce', 2, 2, 1158, 'Ajouter produit', 0, '22', '2017-10-16 21:39:54'),
(31267, 729, '93e893c307a6fa63e392f78751ec70ce', 1, 22, 1158, 'Ajouter produit', 0, '22', '2017-10-16 21:39:54'),
(31268, 729, '93e893c307a6fa63e392f78751ec70ce', 1, 23, 1158, 'Ajouter produit', 0, '22', '2017-10-16 21:39:54'),
(31270, 730, 'bcf3beada4a98e8145af2d4fbb744f01', 2, 2, 1159, 'Modifier produit', 0, '22', '2017-10-16 21:39:54'),
(31271, 730, 'bcf3beada4a98e8145af2d4fbb744f01', 1, 22, 1159, 'Modifier produit', 0, '22', '2017-10-16 21:39:54'),
(31272, 730, 'bcf3beada4a98e8145af2d4fbb744f01', 1, 23, 1159, 'Modifier produit', 0, '22', '2017-10-16 21:39:54'),
(31274, 731, '796427ec57f7c13d6b737055ae686b34', 2, 2, 1160, 'Detail produit', 0, '22', '2017-10-16 21:39:54'),
(31275, 731, '796427ec57f7c13d6b737055ae686b34', 1, 22, 1160, 'Detail produit', 0, '22', '2017-10-16 21:39:54'),
(31276, 731, '796427ec57f7c13d6b737055ae686b34', 1, 23, 1160, 'Detail produit', 0, '22', '2017-10-16 21:39:54'),
(31278, 732, '1fb8cd1a179be07586fa7db05013dd37', 2, 2, 1161, 'Valider produit', 0, '22', '2017-10-16 21:39:54'),
(31279, 732, '1fb8cd1a179be07586fa7db05013dd37', 1, 22, 1161, 'Valider produit', 0, '22', '2017-10-16 21:39:54'),
(31280, 732, '1fb8cd1a179be07586fa7db05013dd37', 1, 23, 1161, 'Valider produit', 0, '22', '2017-10-16 21:39:54'),
(31282, 733, '7779e98d2111faedf458f7aeb548294e', 2, 2, 1162, 'Supprimer produit', 0, '22', '2017-10-16 21:39:54');
INSERT INTO `rules_action` (`id`, `appid`, `idf`, `service`, `userid`, `action_id`, `descrip`, `type`, `creusr`, `credat`) VALUES
(31283, 733, '7779e98d2111faedf458f7aeb548294e', 1, 22, 1162, 'Supprimer produit', 0, '22', '2017-10-16 21:39:54'),
(31284, 733, '7779e98d2111faedf458f7aeb548294e', 1, 23, 1162, 'Supprimer produit', 0, '22', '2017-10-16 21:39:54'),
(31286, 734, '8da585a04e918c256bd26f0c03f1390d', 2, 2, 1163, 'Achat produit', 0, '22', '2017-10-16 21:39:54'),
(31287, 734, '8da585a04e918c256bd26f0c03f1390d', 1, 22, 1163, 'Achat produit', 0, '22', '2017-10-16 21:39:54'),
(31288, 734, '8da585a04e918c256bd26f0c03f1390d', 1, 23, 1163, 'Achat produit', 0, '22', '2017-10-16 21:39:54'),
(31290, 734, 'f8c9a7413089566d1db20dcc5ca17e03', 2, 2, 1164, 'Modifier achat', 0, '22', '2017-10-16 21:39:54'),
(31291, 734, 'f8c9a7413089566d1db20dcc5ca17e03', 1, 22, 1164, 'Modifier achat', 0, '22', '2017-10-16 21:39:54'),
(31292, 734, 'f8c9a7413089566d1db20dcc5ca17e03', 1, 23, 1164, 'Modifier achat', 0, '22', '2017-10-16 21:39:54'),
(31294, 734, '682b4328ee832101a44dac86b22d5757', 2, 2, 1165, 'Détail achat', 0, '22', '2017-10-16 21:39:54'),
(31295, 734, '682b4328ee832101a44dac86b22d5757', 1, 22, 1165, 'Détail achat', 0, '22', '2017-10-16 21:39:54'),
(31296, 734, '682b4328ee832101a44dac86b22d5757', 1, 23, 1165, 'Détail achat', 0, '22', '2017-10-16 21:39:54'),
(31298, 734, 'd1ebf1c5482ddf06721b11ec64afb744', 2, 2, 1166, 'Valider achat', 0, '22', '2017-10-16 21:39:54'),
(31299, 734, 'd1ebf1c5482ddf06721b11ec64afb744', 1, 22, 1166, 'Valider achat', 0, '22', '2017-10-16 21:39:54'),
(31300, 734, 'd1ebf1c5482ddf06721b11ec64afb744', 1, 23, 1166, 'Valider achat', 0, '22', '2017-10-16 21:39:54'),
(31302, 734, '368a1e91fc63e263eb01d85a34ecd89b', 2, 2, 1167, 'Désactiver achat', 0, '22', '2017-10-16 21:39:54'),
(31303, 734, '368a1e91fc63e263eb01d85a34ecd89b', 1, 22, 1167, 'Désactiver achat', 0, '22', '2017-10-16 21:39:54'),
(31304, 734, '368a1e91fc63e263eb01d85a34ecd89b', 1, 23, 1167, 'Désactiver achat', 0, '22', '2017-10-16 21:39:54'),
(31306, 735, '659be5cd86a12eba7e59c52d60198a36', 2, 2, 1168, 'Ajoute achat', 0, '22', '2017-10-16 21:39:54'),
(31307, 735, '659be5cd86a12eba7e59c52d60198a36', 1, 22, 1168, 'Ajoute achat', 0, '22', '2017-10-16 21:39:54'),
(31308, 735, '659be5cd86a12eba7e59c52d60198a36', 1, 23, 1168, 'Ajoute achat', 0, '22', '2017-10-16 21:39:54'),
(31310, 736, '8415336a17e8ca26f3eca5741863f3b2', 2, 2, 1169, 'Modifier achat', 0, '22', '2017-10-16 21:39:54'),
(31311, 736, '8415336a17e8ca26f3eca5741863f3b2', 1, 22, 1169, 'Modifier achat', 0, '22', '2017-10-16 21:39:54'),
(31312, 736, '8415336a17e8ca26f3eca5741863f3b2', 1, 23, 1169, 'Modifier achat', 0, '22', '2017-10-16 21:39:54'),
(31314, 737, '2c3b4875b72f7da6a87b5c0d7e85f51d', 2, 2, 1170, 'Supprimer achat', 0, '22', '2017-10-16 21:39:54'),
(31315, 737, '2c3b4875b72f7da6a87b5c0d7e85f51d', 1, 22, 1170, 'Supprimer achat', 0, '22', '2017-10-16 21:39:54'),
(31316, 737, '2c3b4875b72f7da6a87b5c0d7e85f51d', 1, 23, 1170, 'Supprimer achat', 0, '22', '2017-10-16 21:39:54'),
(31318, 738, 'd4180eb7a4ff86c598f441ffd4543f36', 2, 2, 1171, 'Détail achat', 0, '22', '2017-10-16 21:39:54'),
(31319, 738, 'd4180eb7a4ff86c598f441ffd4543f36', 1, 22, 1171, 'Détail achat', 0, '22', '2017-10-16 21:39:54'),
(31320, 738, 'd4180eb7a4ff86c598f441ffd4543f36', 1, 23, 1171, 'Détail achat', 0, '22', '2017-10-16 21:39:54'),
(31322, 739, '4a4c9b096bad58a96d5ea6f93d66e81c', 2, 2, 1172, 'Valider achat', 0, '22', '2017-10-16 21:39:54'),
(31323, 739, '4a4c9b096bad58a96d5ea6f93d66e81c', 1, 22, 1172, 'Valider achat', 0, '22', '2017-10-16 21:39:54'),
(31324, 739, '4a4c9b096bad58a96d5ea6f93d66e81c', 1, 23, 1172, 'Valider achat', 0, '22', '2017-10-16 21:39:54'),
(31364, 455, 'e69f84a801ee1525f20f34e684688a9b', 1, 1, 652, 'Gestion des catégories de produits', 0, '20', '2017-10-16 23:30:39'),
(31365, 455, '90f6eba3e0ed223e73d250278cb445d5', 1, 1, 653, 'Modifier catégorie', 0, '20', '2017-10-16 23:30:39'),
(31366, 455, 'c62968a45ae9cfa8b127ac1b5573988a', 1, 1, 654, 'Valider catégorie', 0, '20', '2017-10-16 23:30:39'),
(31367, 455, '6f43a6bcbd293f958aff51953559104e', 1, 1, 655, 'Désactiver catégorie', 0, '20', '2017-10-16 23:30:39'),
(31368, 456, 'd26f5940e88a494c0eb65047aab9a17b', 1, 1, 656, 'Ajouter une catégorie', 0, '20', '2017-10-16 23:30:39'),
(31369, 457, '27957c6d0f6869d4d90287cd50b6dde9', 1, 1, 657, 'Modifier une catégorie', 0, '20', '2017-10-16 23:30:39'),
(31370, 458, '41b48dd567e4f79e35261a47b7bad751', 1, 1, 658, 'Valider une catégorie', 0, '20', '2017-10-16 23:30:39'),
(31371, 459, '90dc20c4d1ad7be7fac8ec34d5ac26b3', 1, 1, 659, 'Supprimer une catégorie', 0, '20', '2017-10-16 23:30:39'),
(31372, 333, '6edc543080c65eca3993445c295ff94b', 1, 1, 497, 'Gestion Catégorie Client', 0, '20', '2017-10-16 23:30:39'),
(31373, 333, '142a68a109abd0462ea44fcadffe56de', 1, 1, 506, 'Editer Catégorie Client', 0, '20', '2017-10-16 23:30:39'),
(31374, 333, '70df89fa2654d8b10d7fc7e75e178b7e', 1, 1, 507, 'Activer Catégorie Client', 0, '20', '2017-10-16 23:30:39'),
(31375, 333, '109e82d6db5721f63cd827e9fd224216', 1, 1, 508, 'Désactiver Catégorie Client', 0, '20', '2017-10-16 23:30:39'),
(31376, 334, 'a5c1bd0dfd87824ff0f57c6b1e1d2c3f', 1, 1, 498, 'Ajouter Catégorie Client', 1, '20', '2017-10-16 23:30:39'),
(31377, 335, '8d901f74dfd6ee3a8f44ebd0b83fbfae', 1, 1, 499, 'Editer Catégorie Client', 1, '20', '2017-10-16 23:30:39'),
(31378, 336, 'e87327563ce6b659780d6b2c9bf8ac77', 1, 1, 500, 'Supprimer Catégorie Client', 1, '20', '2017-10-16 23:30:39'),
(31379, 337, 'c955da8d244aac06ee7595d08de7d009', 1, 1, 501, 'Valider Catégorie Client', 1, '20', '2017-10-16 23:30:39'),
(31380, 394, 'f12fb1c50aedc49c3fa3dfa2bd297bd3', 1, 1, 553, 'Gestion Clients', 0, '20', '2017-10-16 23:30:39'),
(31381, 394, 'dd3d5980299911ea854af4fa6f2e7309', 1, 1, 554, 'Editer Client', 0, '20', '2017-10-16 23:30:39'),
(31382, 394, '3c5c04a20d49ad010557a64c8cdac1ce', 1, 1, 555, 'Valider Client', 0, '20', '2017-10-16 23:30:39'),
(31383, 394, '18ace52052f2551099ecaabf049ffaec', 1, 1, 556, 'Désactiver Client', 0, '20', '2017-10-16 23:30:39'),
(31384, 394, '493f9e55fc0340763e07514c1900685a', 1, 1, 557, 'Détails Client', 0, '20', '2017-10-16 23:30:39'),
(31385, 394, '03b4f949b088e41fc9a1f3f23b7906a8', 1, 1, 558, 'Détails  Client', 0, '20', '2017-10-16 23:30:39'),
(31386, 395, '2b9d8bb8f752d1c35fb681c33e38b42b', 1, 1, 559, 'Ajouter Client', 1, '20', '2017-10-16 23:30:39'),
(31387, 396, '54aa9121e05f5e698d354022a8eab71d', 1, 1, 560, 'Editer Client', 1, '20', '2017-10-16 23:30:39'),
(31388, 397, '4eaf650e8c2221d590fac5a6a6952231', 1, 1, 561, 'Supprimer Client', 1, '20', '2017-10-16 23:30:39'),
(31389, 398, '534cd4b17fb8a371d3a20565ab8fd96e', 1, 1, 562, 'Valider Client', 1, '20', '2017-10-16 23:30:39'),
(31390, 399, '95bb6aa696ef630a335aa84e1e425e2c', 1, 1, 563, 'Détails Client', 0, '20', '2017-10-16 23:30:39'),
(31391, 700, '899d40c8f22d4f7a6f048366f1829787', 1, 1, 1096, 'Gestion des contrats', 0, '20', '2017-10-16 23:30:39'),
(31392, 700, 'a20f4c5b9c9ebaa238757d6f9f9cb6fb', 1, 1, 1097, 'Modifier contrat', 0, '20', '2017-10-16 23:30:39'),
(31393, 700, 'fbb243d2c2fa4200c40993e527b3a339', 1, 1, 1098, 'Détail contrat', 0, '20', '2017-10-16 23:30:39'),
(31394, 700, 'e970c1414507e5b83ae39e7ddedbf15e', 1, 1, 1099, 'Valider contrat', 0, '20', '2017-10-16 23:30:39'),
(31396, 700, '11cabf03a954a5476cc78cf221f04d78', 1, 1, 1101, 'Détails Contrat', 0, '20', '2017-10-16 23:30:39'),
(31397, 700, '74710492392c157c6fe6d7e79ddc95fa', 1, 1, 1102, 'Renouveler Contrat', 0, '20', '2017-10-16 23:30:39'),
(31398, 700, 'a717e1a94a251fd4316f34aba679c0c1', 1, 1, 1103, 'Détails   Contrat ', 0, '20', '2017-10-16 23:30:39'),
(31399, 700, 'cd25d6f0f7f68e3dc35714df632e58df', 1, 1, 1104, ' Détails   Contrat', 0, '20', '2017-10-16 23:30:39'),
(31400, 700, '9542829fd94f174b96cb33cf94b2758a', 1, 1, 1105, 'Détails   Contrat', 0, '20', '2017-10-16 23:30:39'),
(31401, 700, '656d41ad5452611636a5d9f966729e39', 1, 1, 1106, 'Renouveler Contrat', 0, '20', '2017-10-16 23:30:39'),
(31402, 701, '87f4c3ed4713c3bc9e3fef60a6649055', 1, 1, 1107, 'Ajouter contrat', 0, '20', '2017-10-16 23:30:39'),
(31403, 702, '9e49a431d9637544cefa2869fd7278b9', 1, 1, 1108, 'Modifier contrat', 0, '20', '2017-10-16 23:30:39'),
(31404, 703, '1e9395a182a44787e493bc038cd80bbf', 1, 1, 1109, 'Supprimer contrat', 0, '20', '2017-10-16 23:30:39'),
(31405, 704, '460d92834715b149c4db28e1643bd932', 1, 1, 1110, 'Valider contrat', 0, '20', '2017-10-16 23:30:39'),
(31406, 705, 'bbcf2879c2f8f60cfa55fa97c6e79268', 1, 1, 1111, 'Détail contrat', 0, '20', '2017-10-16 23:30:39'),
(31407, 706, 'fe058ccb890b25a54866be7f24a40363', 1, 1, 1112, 'Ajouter échéance ', 0, '20', '2017-10-16 23:30:39'),
(31408, 707, '36a248f56a6a80977e5c90a5c59f39d3', 1, 1, 1113, 'Modifier échéance contrat', 0, '20', '2017-10-16 23:30:39'),
(31409, 708, 'f0567980556249721f24f2fc88ebfed5', 1, 1, 1114, 'Renouveler Contrat', 0, '20', '2017-10-16 23:30:39'),
(31410, 609, 'ec45512f34613446e7a2e367d4b4cfbd', 1, 1, 920, 'Gestion Contrats Fournisseurs', 0, '20', '2017-10-16 23:30:39'),
(31411, 609, 'e3c0d7e92dad7f8794b2415c334ec3ff', 1, 1, 921, 'Editer Contrat', 0, '20', '2017-10-16 23:30:39'),
(31412, 609, '9dfff1c8dcb804837200f38e95381420', 1, 1, 922, 'Valider Contrat', 0, '20', '2017-10-16 23:30:39'),
(31413, 609, '9fe39b496077065105a57ccd9ed05863', 1, 1, 923, 'Désactiver Contrat', 0, '20', '2017-10-16 23:30:39'),
(31414, 609, 'faee342ff51dbe9f835529ae5b9b2a0b', 1, 1, 924, 'Détails  Contrat ', 0, '20', '2017-10-16 23:30:39'),
(31415, 609, '83406b6b206ed08878f2b2e854932ae5', 1, 1, 925, 'Détails   Contrat  ', 0, '20', '2017-10-16 23:30:39'),
(31416, 609, '8447888bef30fb983477cc1357ff7e6f', 1, 1, 926, 'Détails    Contrat ', 0, '20', '2017-10-16 23:30:39'),
(31417, 609, '4cc1845128f6a5ff3ed01100292d8ebb', 1, 1, 927, '  Détails    Contrat', 0, '20', '2017-10-16 23:30:39'),
(31418, 609, 'cd82d84c5f70a633b10aae88c34e9159', 1, 1, 928, '  Renouveler   Contrat ', 0, '20', '2017-10-16 23:30:39'),
(31419, 609, 'e9e994a0f8a204f1323fca7ce30931fe', 1, 1, 929, ' Détails  Contrat ', 0, '20', '2017-10-16 23:30:39'),
(31420, 609, 'b9e0a2a0236899590c72d31b878edfb2', 1, 1, 930, ' Renouveler  Contrat ', 0, '20', '2017-10-16 23:30:39'),
(31421, 610, 'ded24eb817021c5a666a677b1565bc5e', 1, 1, 931, 'Ajouter Contrat', 0, '20', '2017-10-16 23:30:39'),
(31422, 611, 'ed6b8695494bf4ed86d5fb18690b3a59', 1, 1, 932, 'Editer Contrat', 0, '20', '2017-10-16 23:30:39'),
(31423, 612, 'b8a40913b5955209994aaa26d0e8c3d4', 1, 1, 933, 'Supprimer Contrat', 0, '20', '2017-10-16 23:30:39'),
(31424, 613, '5efb874e7d73ccd722df806e8275770f', 1, 1, 934, 'Valider Contrat', 0, '20', '2017-10-16 23:30:39'),
(31425, 614, '64a5f976687a8c5f7cd3d672cc5d9c8c', 1, 1, 935, 'Détails Contrat', 0, '20', '2017-10-16 23:30:39'),
(31426, 615, '2cc55c65e79534161108288adb00472b', 1, 1, 936, 'Renouveler  Contrat', 0, '20', '2017-10-16 23:30:39'),
(31427, 432, 'f320732af279d6f2f8ae9c98cd0216de', 1, 1, 613, 'Gestion Départements', 0, '20', '2017-10-16 23:30:39'),
(31428, 432, '96516cd0c72d814d5dcb1d86eacd29ab', 1, 1, 617, 'Editer Département', 0, '20', '2017-10-16 23:30:39'),
(31429, 432, 'ef27a63534fa9fc3bd4b5086a92db546', 1, 1, 619, 'Valider Département', 0, '20', '2017-10-16 23:30:39'),
(31430, 432, '9aed965af4c4b89a5a23c41bf685d403', 1, 1, 620, 'Désactiver Département', 0, '20', '2017-10-16 23:30:39'),
(31431, 433, '722b3ba1c7fe735e87aa7415e5502a4c', 1, 1, 614, 'Ajouter Département', 0, '20', '2017-10-16 23:30:39'),
(31432, 434, 'daeb31006124e562d284aff67360ee19', 1, 1, 615, 'Editer Département', 0, '20', '2017-10-16 23:30:39'),
(31433, 435, 'a775da608fe55c53211d4f1c6e493251', 1, 1, 616, 'Supprimer Département', 0, '20', '2017-10-16 23:30:39'),
(31434, 436, 'bbb96ec910c5000a2006db2f6e8af10a', 1, 1, 618, 'Valider Département', 0, '20', '2017-10-16 23:30:39'),
(31435, 655, '0e79510db7f03b9b6266fc7b4a612153', 1, 1, 1005, 'Gestion Devis', 1, '20', '2017-10-16 23:30:39'),
(31436, 655, 'c15b00a1e37657336df8b6aa0eea2db5', 1, 1, 1006, 'Modifier Devis', 0, '20', '2017-10-16 23:30:39'),
(31437, 655, '7cfdba6bc6bc94c65b97e77746cf49b5', 1, 1, 1007, 'Envoi au client', 0, '20', '2017-10-16 23:30:39'),
(31438, 655, '28e267a2a0647d4cb37b18abb1e7d051', 1, 1, 1008, 'Voir détails', 0, '20', '2017-10-16 23:30:39'),
(31439, 655, 'd34b07afd92adad84e1c4c2ebd92ba95', 1, 1, 1009, 'Voir détails', 0, '20', '2017-10-16 23:30:39'),
(31440, 655, 'f6f55b9d0ba9d704b2861d57cda32477', 1, 1, 1010, 'Réponse Client', 0, '20', '2017-10-16 23:30:39'),
(31441, 655, '4b11c0bfb3f970a541100f7fc334927e', 1, 1, 1011, 'Voir détails', 0, '20', '2017-10-16 23:30:39'),
(31442, 655, '61a0655c2c13039b5b8262b82ae6cb51', 1, 1, 1012, 'Voir détails', 0, '20', '2017-10-16 23:30:39'),
(31443, 655, 'ed30dfbb21d8d24a0da432252358bdf8', 1, 1, 1013, 'Voir détails', 0, '20', '2017-10-16 23:30:39'),
(31444, 655, '7bd2e025ffb3893dea4776e152301716', 1, 1, 1014, 'Débloquer devis', 0, '20', '2017-10-16 23:30:39'),
(31445, 655, '6d9ec7d9ebebcdc921376e7bf0c9fdaf', 1, 1, 1015, 'Valider devis', 0, '20', '2017-10-16 23:30:39'),
(31446, 655, 'b1bcc4a4ab154f110abcf54c0c659fb3', 1, 1, 1016, 'Voir détails', 0, '20', '2017-10-16 23:30:39'),
(31447, 655, '91a90a46e3430c491ab8db654b6e87c4', 1, 1, 1017, 'Voir détails', 0, '20', '2017-10-16 23:30:39'),
(31448, 656, 'd9eeb330625c1b87e0df00986a47be01', 1, 1, 1018, 'Ajouter Devis', 0, '20', '2017-10-16 23:30:39'),
(31449, 657, 'da93cdb05137e15aed9c4c18bddd746a', 1, 1, 1019, 'Ajouter détail devis', 0, '20', '2017-10-16 23:30:39'),
(31450, 658, 'f9f3c299f9bd0fec014f6bd3f0e06adb', 1, 1, 1020, 'Modifier Devis', 0, '20', '2017-10-16 23:30:39'),
(31451, 659, 'e14cce6f1faf7784adb327581c516b90', 1, 1, 1021, 'Supprimer Devis', 0, '20', '2017-10-16 23:30:39'),
(31452, 660, '38f10871792c133ebcc6040e9a11cde8', 1, 1, 1022, 'Modifier détail Devis', 0, '20', '2017-10-16 23:30:39'),
(31453, 661, '8def42e75fd4aee61c378d9fb303850d', 1, 1, 1023, 'Afficher détail devis', 0, '20', '2017-10-16 23:30:39'),
(31454, 662, '7666e87783b0f5a7eec1eea7593f7dfe', 1, 1, 1024, 'Valider Devis', 0, '20', '2017-10-16 23:30:39'),
(31455, 663, '7f1c48324d8c369da9aa6ab8af35acd8', 1, 1, 1025, 'Validation Client Devis', 0, '20', '2017-10-16 23:30:39'),
(31456, 664, '6adf896091dde0df89f777f31606953c', 1, 1, 1026, 'Débloquer devis', 0, '20', '2017-10-16 23:30:39'),
(31457, 665, '15cbb79dd4a74266158e6b29a83e683c', 1, 1, 1027, 'Archiver Devis', 1, '20', '2017-10-16 23:30:39'),
(31458, 675, '4c924acb9adc87d8389e8f9842a965c5', 1, 1, 1047, 'Gestion des factures', 0, '20', '2017-10-16 23:30:39'),
(31459, 675, '98a697ec628778765b25e02ba2929d38', 1, 1, 1048, 'Liste complément', 0, '20', '2017-10-16 23:30:39'),
(31460, 675, 'f8b20f7fec99b45b967a431d64b7f061', 1, 1, 1049, 'Liste encaissements', 0, '20', '2017-10-16 23:30:39'),
(31461, 675, '9a51fb5298e39a28af3ad6272fc51177', 1, 1, 1050, 'Valider facture', 0, '20', '2017-10-16 23:30:39'),
(31462, 675, '851f1d4c13f6025f69f5b9315321d350', 1, 1, 1051, 'Désactiver facture', 0, '20', '2017-10-16 23:30:39'),
(31463, 675, '5c79105956d28b5cac52f85784039919', 1, 1, 1052, 'Détail facture', 0, '20', '2017-10-16 23:30:39'),
(31464, 675, '7892721423af84a0b54e90250cf27ee3', 1, 1, 1053, 'Détails Facture', 0, '20', '2017-10-16 23:30:39'),
(31465, 675, 'b5380d403c9947ce060963f28e6d7539', 1, 1, 1054, 'Envoyer au client', 0, '20', '2017-10-16 23:30:39'),
(31466, 675, '80a4b2643b95c2836e968411811d3c21', 1, 1, 1055, 'Détails facture', 0, '20', '2017-10-16 23:30:39'),
(31467, 675, '2f679be3c0d7b88529209f86745f9028', 1, 1, 1056, 'Détails facture', 0, '20', '2017-10-16 23:30:39'),
(31468, 675, '429558e9a1e899c11051ea5c9a4f7942', 1, 1, 1057, 'Détails facture', 0, '20', '2017-10-16 23:30:39'),
(31469, 675, '3acd11d8d74fb7e1ba8d5721e96f91bd', 1, 1, 1058, 'Liste encaissements', 0, '20', '2017-10-16 23:30:39'),
(31470, 676, '55c3c5d2d93143b315513b7401043c8b', 1, 1, 1059, 'complements', 0, '20', '2017-10-16 23:30:39'),
(31471, 676, 'dfc4772cc03cf0b92a47f54fc6a2326e', 1, 1, 1060, 'Modifier complément', 0, '20', '2017-10-16 23:30:39'),
(31472, 677, '03a18bdd5201e433a3c523a2b34d059a', 1, 1, 1061, 'Ajouter complément', 0, '20', '2017-10-16 23:30:39'),
(31473, 678, '88d9bc979cd1102eb8196e7f5e6042ca', 1, 1, 1062, 'Encaissement', 0, '20', '2017-10-16 23:30:39'),
(31474, 678, 'c690cc68f5257c0c225b8b8e6126ea56', 1, 1, 1063, 'Modifier encaissement', 0, '20', '2017-10-16 23:30:39'),
(31475, 678, '1dc06f602e8630f273d44aa2751b2127', 1, 1, 1064, 'Détails encaissement', 0, '20', '2017-10-16 23:30:39'),
(31476, 679, 'e4866b292dbc3c9c5d9cc37273a5b498', 1, 1, 1065, 'Ajouter encaissement', 0, '20', '2017-10-16 23:30:39'),
(31477, 680, '8665be10959f39df4f149962eb70041f', 1, 1, 1066, 'Modifier complément', 0, '20', '2017-10-16 23:30:39'),
(31478, 681, '585d411904bf7d9e83d21b2810ff1d6c', 1, 1, 1067, 'Modifier encaissement', 0, '20', '2017-10-16 23:30:39'),
(31479, 682, '8c8b058a4d030cdc8b49c9008abb2e92', 1, 1, 1068, 'Supprimer complément', 0, '20', '2017-10-16 23:30:39'),
(31480, 683, '6bf7d5180940f03567a5d711e8563ba4', 1, 1, 1069, 'Supprimer encaissement', 0, '20', '2017-10-16 23:30:39'),
(31481, 684, '256abad0ec8e3bc8ed1c0653ff177255', 1, 1, 1070, 'Valider facture', 0, '20', '2017-10-16 23:30:39'),
(31482, 685, 'b5dc5719c1f96df7334f371dcf51a5b6', 1, 1, 1071, 'Détail encaissement', 0, '20', '2017-10-16 23:30:39'),
(31483, 686, '16fbf6fdcbb72f863bcf7e4ef28d8e75', 1, 1, 1072, 'Détails facture', 0, '20', '2017-10-16 23:30:39'),
(31484, 687, '5efdeb41007109ca99f23f0756217827', 1, 1, 1073, 'Désactiver Facture', 0, '20', '2017-10-16 23:30:39'),
(31485, 502, '6beb279abea6434e3b73229aebadc081', 1, 1, 725, 'Gestion Fournisseurs', 0, '20', '2017-10-16 23:30:39'),
(31486, 502, 'ff95747f3a590b6539803f2a9a394cd5', 1, 1, 730, 'Editer Fournisseur', 0, '20', '2017-10-16 23:30:39'),
(31487, 502, 'fea982f5074995d4ccd6211a71ab2680', 1, 1, 731, 'Valider Fournisseur', 0, '20', '2017-10-16 23:30:39'),
(31488, 502, '1d0411a0dec15fc28f054f1a79d95618', 1, 1, 732, 'Désactiver Fournisseur', 0, '20', '2017-10-16 23:30:39'),
(31489, 502, 'a52affdd109b9362ce47ff18aad53e2a', 1, 1, 737, 'Détails Fournisseur', 0, '20', '2017-10-16 23:30:39'),
(31490, 502, 'c6fe5f222dd563204188e8bf0d69bd9e', 1, 1, 738, 'Détails  Fournisseur', 0, '20', '2017-10-16 23:30:39'),
(31491, 503, 'd644015625a9603adb2fcc36167aeb73', 1, 1, 726, 'Ajouter Fournisseur', 0, '20', '2017-10-16 23:30:39'),
(31492, 504, '58c6694abfd3228d927a5d5a06d40b94', 1, 1, 727, 'Editer Fournisseur', 0, '20', '2017-10-16 23:30:39'),
(31493, 505, 'd072f81cd779e4b0152953241d713ca3', 1, 1, 728, 'Supprimer Fournisseur', 0, '20', '2017-10-16 23:30:39'),
(31494, 506, '657351ce5aa227513e3b50dea77db918', 1, 1, 729, 'Valider Fournisseur', 0, '20', '2017-10-16 23:30:39'),
(31495, 508, '83b693fe35a1be29edafe4f6170641aa', 1, 1, 736, 'Détails Fournisseur', 0, '20', '2017-10-16 23:30:39'),
(31496, 542, '72db1c2280dc3eb6405908c1c5b6c815', 1, 1, 810, 'Information société', 0, '20', '2017-10-16 23:30:39'),
(31497, 637, 'b8e62907d367fb44d644a5189cd07f42', 1, 1, 978, 'Modules', 1, '20', '2017-10-16 23:30:39'),
(31498, 637, '05ce9e55686161d99e0714bb86243e5b', 1, 1, 979, 'Editer Module', 0, '20', '2017-10-16 23:30:39'),
(31499, 637, '819cf9c18a44cb80771a066768d585f2', 1, 1, 980, 'Exporter Module', 0, '20', '2017-10-16 23:30:39'),
(31500, 637, 'd2fc3ee15cee5208a8b9c70f1e53c196', 1, 1, 981, 'Liste task modul', 0, '20', '2017-10-16 23:30:39'),
(31501, 637, 'ad75e6b877f20e3d6fc1789da4dcb3e6', 1, 1, 982, 'Editer Module', 0, '20', '2017-10-16 23:30:39'),
(31502, 637, '064a9b0eff1006fd4f25cb4eaf894ca1', 1, 1, 983, 'Liste task modul Setting', 0, '20', '2017-10-16 23:30:39'),
(31503, 637, 'ac4eb0c94da00a48ad5d995f5e9e9366', 1, 1, 984, 'MAJ Module', 0, '20', '2017-10-16 23:30:39'),
(31504, 638, '44bd5341b0ab41ced21db8b3e92cf5aa', 1, 1, 985, 'Ajouter un Modul', 1, '20', '2017-10-16 23:30:39'),
(31505, 640, '8653b156f1a4160a12e5a94b211e59a2', 1, 1, 986, 'Liste Action Task', 0, '20', '2017-10-16 23:30:39'),
(31506, 640, '86aced763bc02e1957a5c740fb37b4f7', 1, 1, 987, 'Supprimer Application', 0, '20', '2017-10-16 23:30:39'),
(31507, 640, 'f07352e32fe86da1483c6ab071b7e7a9', 1, 1, 988, 'Ajout Affichage WF', 0, '20', '2017-10-16 23:30:39'),
(31508, 641, '1c452aff8f1551b3574e15b74147ea56', 1, 1, 989, 'Ajouter Task Modul', 1, '20', '2017-10-16 23:30:39'),
(31509, 642, 'f085fe4610576987db963501297e4d91', 1, 1, 990, 'Editer Task Modul', 1, '20', '2017-10-16 23:30:39'),
(31510, 642, '38702c272a6f4d334c2f4c3684c8b163', 1, 1, 991, 'Ajouter action modul', 1, '20', '2017-10-16 23:30:39'),
(31511, 643, 'cbae1ebe850f6dd8841426c6fedf1785', 1, 1, 992, 'Liste Action Task', 1, '20', '2017-10-16 23:30:39'),
(31512, 643, 'e30471396f9b86ccdcc94943d80b679a', 1, 1, 993, 'Editer Task Action', 0, '20', '2017-10-16 23:30:39'),
(31513, 644, '502460cd9327b46ee7af0a258ebf8c80', 1, 1, 994, 'Ajouter Action Task', 1, '20', '2017-10-16 23:30:39'),
(31514, 645, '13c107211904d4a2e65dd65c60ec7272', 1, 1, 995, 'Supprimer Application', 1, '20', '2017-10-16 23:30:39'),
(31515, 646, '8c8acf9cf3790b16b1fae26823f45eab', 1, 1, 996, 'Importer des modules', 1, '20', '2017-10-16 23:30:39'),
(31516, 647, '2f4518dab90b706e2f4acd737a0425d8', 1, 1, 997, 'Ajouter Module paramétrage', 1, '20', '2017-10-16 23:30:39'),
(31517, 648, '8e0c0212d8337956ac2f4d6eb180d74b', 1, 1, 998, 'Editer Module paramètrage', 1, '20', '2017-10-16 23:30:39'),
(31518, 649, 'fc54953b47b7fcb11cc14c0c2e2125f0', 1, 1, 999, 'Ajouter Autorisation Etat', 1, '20', '2017-10-16 23:30:39'),
(31519, 650, '966ec2dd83e6006c2d0ff1d1a5f12e33', 1, 1, 1000, 'Editer Task Action', 1, '20', '2017-10-16 23:30:39'),
(31520, 651, '3473119f6683893a3f1372dbf7d811e1', 1, 1, 1001, 'MAJ Module', 1, '20', '2017-10-16 23:30:39'),
(31521, 652, '2e2346bd422536c1d996ff25f9e71357', 1, 1, 1002, 'Dupliquer Action Task', 0, '20', '2017-10-16 23:30:39'),
(31522, 653, '8a3634181ae5bc9223b689a310158962', 1, 1, 1003, 'Supprimer Task action', 0, '20', '2017-10-16 23:30:39'),
(31523, 654, '8afb3c669307183cd3b7d189fbf204d7', 1, 1, 1004, 'Affichage Work Flow', 0, '20', '2017-10-16 23:30:39'),
(31524, 475, '605450f3d7c84701b986fa31e1e9fa43', 1, 1, 684, 'Gestion Pays', 0, '20', '2017-10-16 23:30:39'),
(31525, 475, '29ba6cc689eca63dbafb109ec58bc4d6', 1, 1, 689, 'Editer Pays', 0, '20', '2017-10-16 23:30:39'),
(31526, 475, '763fe13212b4324590518773cd9a36fa', 1, 1, 690, 'Valider Pays', 0, '20', '2017-10-16 23:30:39'),
(31527, 475, '3c8427c7313d35219b17572efd380b17', 1, 1, 691, 'Désactiver Pays', 0, '20', '2017-10-16 23:30:39'),
(31528, 476, '3cd55a55307615d72aae84c6b5cf99bc', 1, 1, 685, 'Ajouter Pays', 0, '20', '2017-10-16 23:30:39'),
(31529, 477, 'cfe617d7bc6a9c7d8b86c468f21396f2', 1, 1, 686, 'Editer Pays', 0, '20', '2017-10-16 23:30:39'),
(31530, 478, 'b768486aeb655c48cc411c11fa60e150', 1, 1, 687, 'Supprimer Pays', 0, '20', '2017-10-16 23:30:39'),
(31531, 479, '15e4e24f320daa9d563ae62acff9e586', 1, 1, 688, 'Valider Pays', 0, '20', '2017-10-16 23:30:39'),
(31532, 728, '192715027870a4a612fd44d562e2752f', 1, 1, 1151, 'Gestion des produits', 0, '20', '2017-10-16 23:30:39'),
(31533, 728, 'cb96e99d5f8e381637d1ac83f1a21f1c', 1, 1, 1152, 'Modifier  produit', 0, '20', '2017-10-16 23:30:39'),
(31534, 728, '64e84ff11fea7f68bcf6a5b744c36081', 1, 1, 1153, 'Détail  produit', 0, '20', '2017-10-16 23:30:39'),
(31535, 728, '0c94d85f4ee23656a01155ad1af5001c', 1, 1, 1154, 'Valider  produit', 0, '20', '2017-10-16 23:30:39'),
(31536, 728, '6b087b20929483bb07f8862b39e41f07', 1, 1, 1155, 'Désactiver produit', 0, '20', '2017-10-16 23:30:39'),
(31537, 728, '6f1d7cc5bd1c941beffa0ae3e1efd559', 1, 1, 1156, 'Achat  produit', 0, '20', '2017-10-16 23:30:39'),
(31538, 728, '41b9c4b7028269d4540915d6ec14ee79', 1, 1, 1157, 'Détails Produit', 0, '20', '2017-10-16 23:30:39'),
(31539, 729, '93e893c307a6fa63e392f78751ec70ce', 1, 1, 1158, 'Ajouter produit', 0, '20', '2017-10-16 23:30:39'),
(31540, 730, 'bcf3beada4a98e8145af2d4fbb744f01', 1, 1, 1159, 'Modifier produit', 0, '20', '2017-10-16 23:30:39'),
(31541, 731, '796427ec57f7c13d6b737055ae686b34', 1, 1, 1160, 'Detail produit', 0, '20', '2017-10-16 23:30:39'),
(31542, 732, '1fb8cd1a179be07586fa7db05013dd37', 1, 1, 1161, 'Valider produit', 0, '20', '2017-10-16 23:30:39'),
(31543, 733, '7779e98d2111faedf458f7aeb548294e', 1, 1, 1162, 'Supprimer produit', 0, '20', '2017-10-16 23:30:39'),
(31544, 734, '8da585a04e918c256bd26f0c03f1390d', 1, 1, 1163, 'Achat produit', 0, '20', '2017-10-16 23:30:39'),
(31545, 734, 'f8c9a7413089566d1db20dcc5ca17e03', 1, 1, 1164, 'Modifier achat', 0, '20', '2017-10-16 23:30:39'),
(31546, 734, '682b4328ee832101a44dac86b22d5757', 1, 1, 1165, 'Détail achat', 0, '20', '2017-10-16 23:30:39'),
(31547, 734, 'd1ebf1c5482ddf06721b11ec64afb744', 1, 1, 1166, 'Valider achat', 0, '20', '2017-10-16 23:30:39'),
(31548, 734, '368a1e91fc63e263eb01d85a34ecd89b', 1, 1, 1167, 'Désactiver achat', 0, '20', '2017-10-16 23:30:39'),
(31549, 735, '659be5cd86a12eba7e59c52d60198a36', 1, 1, 1168, 'Ajoute achat', 0, '20', '2017-10-16 23:30:39'),
(31550, 736, '8415336a17e8ca26f3eca5741863f3b2', 1, 1, 1169, 'Modifier achat', 0, '20', '2017-10-16 23:30:39'),
(31551, 737, '2c3b4875b72f7da6a87b5c0d7e85f51d', 1, 1, 1170, 'Supprimer achat', 0, '20', '2017-10-16 23:30:39'),
(31552, 738, 'd4180eb7a4ff86c598f441ffd4543f36', 1, 1, 1171, 'Détail achat', 0, '20', '2017-10-16 23:30:39'),
(31553, 739, '4a4c9b096bad58a96d5ea6f93d66e81c', 1, 1, 1172, 'Valider achat', 0, '20', '2017-10-16 23:30:39'),
(31554, 720, '1eb847d87adcad78d5e951e6110061e5', 1, 1, 1137, 'Gestion Proforma', 0, '20', '2017-10-16 23:30:39'),
(31555, 720, '44ef6849d8d5d17d8e0535187e923d32', 1, 1, 1138, 'Editer proforma', 0, '20', '2017-10-16 23:30:39'),
(31556, 720, 'b7ce06be726011362a271678547a803c', 1, 1, 1139, 'Valider Proforma', 0, '20', '2017-10-16 23:30:39'),
(31557, 720, 'abd8c50f1d2ef4beeeddb68a72973587', 1, 1, 1140, 'Détail Proforma', 0, '20', '2017-10-16 23:30:39'),
(31558, 720, '35a88b5c359908b063ac98cafc622987', 1, 1, 1141, 'Détail Proforma', 0, '20', '2017-10-16 23:30:39'),
(31559, 720, 'e20d83df90355eca2a65f56a2556601f', 1, 1, 1142, 'Détail Proforma', 0, '20', '2017-10-16 23:30:39'),
(31560, 720, '252ed64d8956e20fb88c1be41688f74a', 1, 1, 1143, 'Envoi proforma au client', 0, '20', '2017-10-16 23:30:39'),
(31561, 721, 'd5a6338765b9eab63104b59f01c06114', 1, 1, 1144, 'Ajouter pro-forma', 0, '20', '2017-10-16 23:30:39'),
(31562, 722, '95831bde77bc886d6ab4dd5e734de743', 1, 1, 1145, 'Editer proforma', 0, '20', '2017-10-16 23:30:39'),
(31563, 723, 'cbb4e1efa1c05b42d25a3a6bcab038a2', 1, 1, 1146, 'Ajouter détail proforma', 0, '20', '2017-10-16 23:30:39'),
(31564, 724, 'e9f745054778257a255452c6609461a0', 1, 1, 1147, 'valider Proforma', 0, '20', '2017-10-16 23:30:39'),
(31565, 725, 'defef148c404c7e6ac79e4783e0a7ab7', 1, 1, 1148, 'Détail Pro-forma', 0, '20', '2017-10-16 23:30:39'),
(31566, 726, '53008d64edf241c937a06f03eff139aa', 1, 1, 1149, 'Editer détail proforma', 0, '20', '2017-10-16 23:30:39'),
(31567, 727, '30e9d3d1ad17eb7f1fc0d5cbb9b58482', 1, 1, 1150, 'Supprimer proforma', 1, '20', '2017-10-16 23:30:39'),
(31568, 470, 'd57b16b3aad4ce59f909609246c4fd36', 1, 1, 676, 'Gestion des régions', 0, '20', '2017-10-16 23:30:39'),
(31569, 470, 'd2e007184668dd70b9bae44d46d28ded', 1, 1, 677, 'Modifier région', 0, '20', '2017-10-16 23:30:39'),
(31570, 470, 'e74403c99ac8325b78735c531a20442f', 1, 1, 678, 'Valider région', 0, '20', '2017-10-16 23:30:39'),
(31571, 470, '7397a0fab078728bd5c53be61022d5ce', 1, 1, 679, 'Désactiver région', 0, '20', '2017-10-16 23:30:39'),
(31572, 471, '0237bd41cf70c3529681b4ccb041f1fd', 1, 1, 680, 'Ajouter région', 0, '20', '2017-10-16 23:30:39'),
(31573, 472, '6d290f454da473cb8a557829a410c3f1', 1, 1, 681, 'Modifier région', 0, '20', '2017-10-16 23:30:39'),
(31574, 473, '008cd9ea5767c739675fef4e1261cfe8', 1, 1, 682, 'Valider région', 0, '20', '2017-10-16 23:30:39'),
(31575, 474, 'fc477e6a4c90cd427ae81e555c11d6a9', 1, 1, 683, 'Supprimer région', 0, '20', '2017-10-16 23:30:39'),
(31576, 34, '83b9fa44466da4bcd7f8304185bfeac8', 1, 1, 28, 'Services', 0, '20', '2017-10-16 23:30:39'),
(31577, 34, '3c388c1e842851df49abe9ee73c0a2e7', 1, 1, 33, 'Valider Service', 0, '20', '2017-10-16 23:30:39'),
(31578, 34, 'dcb9555d5ca1d108e9fa95daa9da4b3a', 1, 1, 34, 'Supprimer Service', 0, '20', '2017-10-16 23:30:39'),
(31579, 34, '74950fb3fd858404b6048c1e81bd7c9a', 1, 1, 144, 'Modifier Service', 0, '20', '2017-10-16 23:30:39'),
(31580, 35, '55043bc4207521e3010e91d6267f5302', 1, 1, 29, 'Ajouter Service', 1, '20', '2017-10-16 23:30:39'),
(31581, 36, '2fea3d893f6b6e81467ddd2a744e4a76', 1, 1, 30, 'Modifier Service', 1, '20', '2017-10-16 23:30:39'),
(31582, 37, '1a0d5897d31b4d5e29022671c1112f59', 1, 1, 31, 'Valider Service', 1, '20', '2017-10-16 23:30:39'),
(31583, 38, '42083d4e159baf7c2ace2bb977e2b0a0', 1, 1, 32, 'Supprimer Service', 1, '20', '2017-10-16 23:30:39'),
(31584, 543, 'a1c5a2657cc1b2ff6f85c6fe8f1c51ac', 1, 1, 811, 'Paramètrage Système', 0, '20', '2017-10-16 23:30:39'),
(31585, 543, 'de6285d9c0027ff8bccdf2af385ac337', 1, 1, 812, 'Editer paramètre', 0, '20', '2017-10-16 23:30:39'),
(31586, 544, '82f83d9d3d30fdef00d4c3ef96f0f899', 1, 1, 813, 'Ajouter Paramètre', 0, '20', '2017-10-16 23:30:39'),
(31587, 545, 'f0e54f346e9dcfdff65274709ce2c8ca', 1, 1, 814, 'Editer paramètre', 0, '20', '2017-10-16 23:30:39'),
(31588, 546, 'aaccd24eaf085b8f18115c9c7653d401', 1, 1, 815, 'Supprimer Paramètre', 0, '20', '2017-10-16 23:30:39'),
(31589, 460, 'b6b6bfbd070b5b3dd84acedae7b854e9', 1, 1, 660, 'Gestion des types de produits', 0, '20', '2017-10-16 23:30:39'),
(31590, 460, '3c5400b775264499825a039d66aa9c90', 1, 1, 661, 'Modifier type', 0, '20', '2017-10-16 23:30:39'),
(31591, 460, 'dcf55bc300d690af4c81e4d2335e60e5', 1, 1, 662, 'Valider type', 0, '20', '2017-10-16 23:30:39'),
(31592, 460, '230b9554d37da1c71986af94962cb340', 1, 1, 663, 'Désactiver type', 0, '20', '2017-10-16 23:30:39'),
(31593, 461, 'e0d163499b4ba11d6d7a648bc6fc6de6', 1, 1, 664, 'Ajouter un type', 0, '20', '2017-10-16 23:30:39'),
(31594, 462, 'ac5a6d087b3c8db7501fa5137a47773e', 1, 1, 665, 'Modifier type', 0, '20', '2017-10-16 23:30:39'),
(31595, 463, '2e8242a93a62a264ad7cfc953967f575', 1, 1, 666, 'Valider type', 0, '20', '2017-10-16 23:30:39'),
(31596, 464, 'e3725ba15ca483b9278f68553eca5918', 1, 1, 667, 'Supprimer type', 0, '20', '2017-10-16 23:30:39'),
(31597, 480, '312fd18860781a7b1b7e33587fa423d4', 1, 1, 692, 'Gestion Type Echeance', 0, '20', '2017-10-16 23:30:39'),
(31598, 480, '46ad76148075d6b458f43e84ddf00791', 1, 1, 697, 'Editer Type Echéance', 0, '20', '2017-10-16 23:30:39'),
(31599, 480, 'add2bf057924e606653fbf5bbd65ca09', 1, 1, 698, 'Valider Type Echéance', 0, '20', '2017-10-16 23:30:39'),
(31600, 480, '463d9e1e8367736b958f0dd84b4e36d5', 1, 1, 699, 'Désactiver Type Echéance', 0, '20', '2017-10-16 23:30:39'),
(31601, 481, '76170b14c7b6f1f7058d079fe24f739b', 1, 1, 693, 'Ajouter Type Echéance', 0, '20', '2017-10-16 23:30:39'),
(31602, 482, 'decc5ed58c4d91e6967c9c67e0975cf0', 1, 1, 694, 'Editer Type Echéance', 0, '20', '2017-10-16 23:30:39'),
(31603, 483, '89db6f23dd8e96a69c6a97f556c44e14', 1, 1, 695, 'Supprimer Type Echéance', 0, '20', '2017-10-16 23:30:39'),
(31604, 484, '7527021168823e0118d44297c7684d44', 1, 1, 696, 'Valider Type Echéance', 0, '20', '2017-10-16 23:30:39'),
(31605, 465, '55ecbb545a49c70c0b728bb0c7951077', 1, 1, 668, 'Gestion des unités de vente', 0, '20', '2017-10-16 23:30:39'),
(31606, 465, '67acd70eb04242b7091d9dcbb08295d7', 1, 1, 669, 'Modifier unité ', 0, '20', '2017-10-16 23:30:39'),
(31607, 465, '7363022ed5ad047bfe86d3de4b75b1f4', 1, 1, 670, 'Valider unité', 0, '20', '2017-10-16 23:30:39'),
(31608, 465, 'ec77eb95736c27bfc269cbffc8e113f1', 1, 1, 671, 'Désactiver unité', 0, '20', '2017-10-16 23:30:39'),
(31609, 466, '3a5e8dfe211121eda706f8b6d548d111', 1, 1, 672, 'ajouter une unité', 0, '20', '2017-10-16 23:30:39'),
(31610, 467, '9b7a578981de699286376903e96bc3c7', 1, 1, 673, 'Modifier une unité', 0, '20', '2017-10-16 23:30:39'),
(31611, 468, '62355588366c13ddbadc7a7ca1d226ad', 1, 1, 674, 'Valider une unité', 0, '20', '2017-10-16 23:30:39'),
(31612, 469, 'e5f53a3aaa324415d781156396938101', 1, 1, 675, 'Supprimer une unité', 0, '20', '2017-10-16 23:30:39'),
(31613, 709, '56de23d30d6c54297c8d9772cd3c7f57', 1, 1, 1115, 'Utilisateurs', 1, '20', '2017-10-16 23:30:39'),
(31614, 709, 'e656756fb7b39a4e6ddcabca75ff2970', 1, 1, 1116, 'Editer Utilisateur', 0, '20', '2017-10-16 23:30:39'),
(31615, 709, 'c073a277957ca1b9f318ac3902555708', 1, 1, 1117, 'Permissions', 0, '20', '2017-10-16 23:30:39'),
(31616, 709, 'c51499ddf7007787c4434661c658bbd1', 1, 1, 1118, 'Désactiver compte', 0, '20', '2017-10-16 23:30:39'),
(31617, 709, '10096b6f54456bcfc85081523ee64cf6', 1, 1, 1119, 'Supprimer utilisateur', 0, '20', '2017-10-16 23:30:39'),
(31618, 709, 'a0999cbed820aff775adf27276ee54a4', 1, 1, 1120, 'Editer Utilisateur', 0, '20', '2017-10-16 23:30:39'),
(31619, 709, '9aa6877656339ddff2478b20449a924b', 1, 1, 1121, 'Activer compte', 0, '20', '2017-10-16 23:30:39'),
(31620, 709, 'f4c79bb797b92dfa826b51a44e3171af', 1, 1, 1122, 'Utilisateurs', 0, '20', '2017-10-16 23:30:39'),
(31621, 709, 'd7f7afd70a297e5c239f6cf271138390', 1, 1, 1123, 'Utilisateur Archivé', 0, '20', '2017-10-16 23:30:39'),
(31622, 709, '17c98287fb82388423e04d24404cf662', 1, 1, 1124, 'Permissions', 0, '20', '2017-10-16 23:30:39'),
(31623, 709, '2c1c5556f30585c5b7c93fbbeaa98595', 1, 1, 1125, 'Historique session', 0, '20', '2017-10-16 23:30:39'),
(31624, 709, '7fd4ca84d162b70d3a4f0bad73e0e4b3', 1, 1, 1126, 'Liste Activitées', 0, '20', '2017-10-16 23:30:39'),
(31625, 710, 'df91a8e6f8ee2cde64495fc0cc7d6c6f', 1, 1, 1127, 'Ajouter Utilisateurs', 1, '20', '2017-10-16 23:30:39'),
(31626, 711, '2bb46b52eab9eecbdbba35605da07234', 1, 1, 1128, 'Editer Utilisateurs', 1, '20', '2017-10-16 23:30:39'),
(31627, 712, '3f59a1326df27378304e142ab3bec090', 1, 1, 1129, 'Permission', 1, '20', '2017-10-16 23:30:39'),
(31628, 713, 'b919571c88d036f8889742a81a4f41fd', 1, 1, 1130, 'Supprimer utilisateur', 1, '20', '2017-10-16 23:30:39'),
(31629, 714, '38f89764a26e39ce029cd3132c12b2a5', 1, 1, 1131, 'Compte utilisateur', 1, '20', '2017-10-16 23:30:39'),
(31630, 715, 'f988a608f35a0bc551cb038b1706d207', 1, 1, 1132, 'Activer utilisateur', 1, '20', '2017-10-16 23:30:39'),
(31631, 716, 'b7b3a09fdd73a5b0a3e5ed8a2828f548', 1, 1, 1133, 'Désactiver l\'utilisateur', 1, '20', '2017-10-16 23:30:39'),
(31632, 717, '0d374b7e2fe21a2e2641c092a3c7f2e9', 1, 1, 1134, 'Changer le mot de passe', 1, '20', '2017-10-16 23:30:39'),
(31633, 718, '6f642ee30722158f0318653b9113b887', 1, 1, 1135, 'History', 1, '20', '2017-10-16 23:30:39'),
(31634, 719, 'cc907fac13631903d129c137d671d718', 1, 1, 1136, 'Activities', 1, '20', '2017-10-16 23:30:39'),
(31635, 430, '3d4eaa53061f51b0c4435bd8e4b89c17', 1, 1, 611, 'Gestion Vente', 0, '20', '2017-10-16 23:30:39'),
(31636, 89, '2c3b01c696ff401a2ac9ffedb7a06e4a', 1, 1, 114, 'Gestion Villes', 1, '20', '2017-10-16 23:30:39'),
(31637, 89, 'b9649163b368f863a0e8036f11cd81ae', 1, 1, 119, 'Editer Ville', 0, '20', '2017-10-16 23:30:39'),
(31638, 89, '89dec6dabcb210cdb9dd28bbef90d43e', 1, 1, 121, 'Editer Ville', 0, '20', '2017-10-16 23:30:39'),
(31639, 89, '4a2edbdcbda34c9d3d1e6abe73643b37', 1, 1, 602, 'Valider Ville', 0, '20', '2017-10-16 23:30:39'),
(31640, 89, '0c8ad6595a4516be83ba5a9cdb7ea9a1', 1, 1, 603, 'Désactiver Ville', 0, '20', '2017-10-16 23:30:39'),
(31641, 90, 'e152b9052d3dcfcac593489dbdc0f61c', 1, 1, 115, 'Ajouter ville', 1, '20', '2017-10-16 23:30:39'),
(31642, 91, '3107e0cd0e0df14c4e94aa088e4457d7', 1, 1, 116, 'Editer Ville', 1, '20', '2017-10-16 23:30:39'),
(31643, 92, 'da79d9214ed5819d7f4f1e3070629a3d', 1, 1, 117, 'Supprimer Ville', 1, '20', '2017-10-16 23:30:39'),
(31644, 423, 'fe03a68d17c62ff2c27329573a1b3550', 1, 1, 601, 'Valider Ville', 0, '20', '2017-10-16 23:30:39'),
(32130, 455, 'e69f84a801ee1525f20f34e684688a9b', 3, 24, 652, 'Gestion des catégories de produits', 0, '1', '2017-10-17 13:58:52'),
(32131, 455, '90f6eba3e0ed223e73d250278cb445d5', 3, 24, 653, 'Modifier catégorie', 0, '1', '2017-10-17 13:58:52'),
(32132, 455, 'c62968a45ae9cfa8b127ac1b5573988a', 3, 24, 654, 'Valider catégorie', 0, '1', '2017-10-17 13:58:52'),
(32133, 455, '6f43a6bcbd293f958aff51953559104e', 3, 24, 655, 'Désactiver catégorie', 0, '1', '2017-10-17 13:58:52'),
(32134, 456, 'd26f5940e88a494c0eb65047aab9a17b', 3, 24, 656, 'Ajouter une catégorie', 0, '1', '2017-10-17 13:58:52'),
(32135, 457, '27957c6d0f6869d4d90287cd50b6dde9', 3, 24, 657, 'Modifier une catégorie', 0, '1', '2017-10-17 13:58:52'),
(32136, 458, '41b48dd567e4f79e35261a47b7bad751', 3, 24, 658, 'Valider une catégorie', 0, '1', '2017-10-17 13:58:52'),
(32137, 459, '90dc20c4d1ad7be7fac8ec34d5ac26b3', 3, 24, 659, 'Supprimer une catégorie', 0, '1', '2017-10-17 13:58:52'),
(32138, 333, '6edc543080c65eca3993445c295ff94b', 3, 24, 497, 'Gestion Catégorie Client', 0, '1', '2017-10-17 13:58:52'),
(32139, 333, '142a68a109abd0462ea44fcadffe56de', 3, 24, 506, 'Editer Catégorie Client', 0, '1', '2017-10-17 13:58:52'),
(32140, 333, '70df89fa2654d8b10d7fc7e75e178b7e', 3, 24, 507, 'Activer Catégorie Client', 0, '1', '2017-10-17 13:58:52'),
(32141, 333, '109e82d6db5721f63cd827e9fd224216', 3, 24, 508, 'Désactiver Catégorie Client', 0, '1', '2017-10-17 13:58:52'),
(32142, 334, 'a5c1bd0dfd87824ff0f57c6b1e1d2c3f', 3, 24, 498, 'Ajouter Catégorie Client', 1, '1', '2017-10-17 13:58:52'),
(32143, 335, '8d901f74dfd6ee3a8f44ebd0b83fbfae', 3, 24, 499, 'Editer Catégorie Client', 1, '1', '2017-10-17 13:58:52'),
(32144, 336, 'e87327563ce6b659780d6b2c9bf8ac77', 3, 24, 500, 'Supprimer Catégorie Client', 1, '1', '2017-10-17 13:58:52'),
(32145, 337, 'c955da8d244aac06ee7595d08de7d009', 3, 24, 501, 'Valider Catégorie Client', 1, '1', '2017-10-17 13:58:52'),
(32146, 394, 'f12fb1c50aedc49c3fa3dfa2bd297bd3', 3, 24, 553, 'Gestion Clients', 0, '1', '2017-10-17 13:58:52'),
(32147, 394, 'dd3d5980299911ea854af4fa6f2e7309', 3, 24, 554, 'Editer Client', 0, '1', '2017-10-17 13:58:52'),
(32148, 394, '3c5c04a20d49ad010557a64c8cdac1ce', 3, 24, 555, 'Valider Client', 0, '1', '2017-10-17 13:58:52'),
(32149, 394, '18ace52052f2551099ecaabf049ffaec', 3, 24, 556, 'Désactiver Client', 0, '1', '2017-10-17 13:58:52'),
(32150, 394, '493f9e55fc0340763e07514c1900685a', 3, 24, 557, 'Détails Client', 0, '1', '2017-10-17 13:58:52'),
(32151, 394, '03b4f949b088e41fc9a1f3f23b7906a8', 3, 24, 558, 'Détails  Client', 0, '1', '2017-10-17 13:58:52'),
(32152, 395, '2b9d8bb8f752d1c35fb681c33e38b42b', 3, 24, 559, 'Ajouter Client', 1, '1', '2017-10-17 13:58:52'),
(32153, 396, '54aa9121e05f5e698d354022a8eab71d', 3, 24, 560, 'Editer Client', 1, '1', '2017-10-17 13:58:52'),
(32154, 397, '4eaf650e8c2221d590fac5a6a6952231', 3, 24, 561, 'Supprimer Client', 1, '1', '2017-10-17 13:58:52'),
(32155, 398, '534cd4b17fb8a371d3a20565ab8fd96e', 3, 24, 562, 'Valider Client', 1, '1', '2017-10-17 13:58:52'),
(32156, 399, '95bb6aa696ef630a335aa84e1e425e2c', 3, 24, 563, 'Détails Client', 0, '1', '2017-10-17 13:58:52'),
(32157, 700, '899d40c8f22d4f7a6f048366f1829787', 3, 24, 1096, 'Gestion des contrats', 0, '1', '2017-10-17 13:58:52'),
(32158, 700, '4aea0d5a7bdb0e2513897507947fc3de', 3, 24, 1097, 'Modifier  contrat', 0, '1', '2017-10-17 13:58:52'),
(32159, 700, '4ccf7c3c72dfa25157ab01762069929e', 3, 24, 1098, 'Détail  contrat', 0, '1', '2017-10-17 13:58:52'),
(32160, 700, '18c5260f189a488c59134c1d53270dae', 3, 24, 1099, 'Valider  contrat', 0, '1', '2017-10-17 13:58:52'),
(32161, 700, '6ca83d9c6c0b229446da30b60b74031a', 3, 24, 1101, 'Détails  Contrat', 0, '1', '2017-10-17 13:58:52'),
(32162, 700, '52eef475bfa2afb7eb065329a93b0b4c', 3, 24, 1102, 'Renouveler  Contrat', 0, '1', '2017-10-17 13:58:52'),
(32163, 700, 'b23939959d533fa68091fca749b691aa', 3, 24, 1103, 'Détails Contrat ', 0, '1', '2017-10-17 13:58:52'),
(32164, 700, 'b6cc6622e5874a5c0a04e2103d8a7dd0', 3, 24, 1104, ' Détails    Contrat', 0, '1', '2017-10-17 13:58:52'),
(32165, 700, 'c58a3038be080d0c6cdf89e0fd0a8c71', 3, 24, 1105, 'Détails  Contrat', 0, '1', '2017-10-17 13:58:52'),
(32166, 700, '656d41ad5452611636a5d9f966729e39', 3, 24, 1106, 'Renouveler Contrat', 0, '1', '2017-10-17 13:58:52'),
(32167, 701, '87f4c3ed4713c3bc9e3fef60a6649055', 3, 24, 1107, 'Ajouter contrat', 0, '1', '2017-10-17 13:58:52'),
(32168, 702, '9e49a431d9637544cefa2869fd7278b9', 3, 24, 1108, 'Modifier contrat', 0, '1', '2017-10-17 13:58:52'),
(32169, 703, '1e9395a182a44787e493bc038cd80bbf', 3, 24, 1109, 'Supprimer contrat', 0, '1', '2017-10-17 13:58:52'),
(32170, 704, '460d92834715b149c4db28e1643bd932', 3, 24, 1110, 'Valider contrat', 0, '1', '2017-10-17 13:58:52'),
(32171, 705, 'bbcf2879c2f8f60cfa55fa97c6e79268', 3, 24, 1111, 'Détail contrat', 0, '1', '2017-10-17 13:58:52'),
(32172, 706, 'fe058ccb890b25a54866be7f24a40363', 3, 24, 1112, 'Ajouter échéance ', 0, '1', '2017-10-17 13:58:52'),
(32173, 707, '36a248f56a6a80977e5c90a5c59f39d3', 3, 24, 1113, 'Modifier échéance contrat', 0, '1', '2017-10-17 13:58:52'),
(32174, 708, 'f0567980556249721f24f2fc88ebfed5', 3, 24, 1114, 'Renouveler Contrat', 0, '1', '2017-10-17 13:58:52'),
(32175, 609, 'ec45512f34613446e7a2e367d4b4cfbd', 3, 24, 920, 'Gestion Contrats Fournisseurs', 0, '1', '2017-10-17 13:58:52'),
(32176, 609, 'e3c0d7e92dad7f8794b2415c334ec3ff', 3, 24, 921, 'Editer Contrat', 0, '1', '2017-10-17 13:58:52'),
(32177, 609, '9dfff1c8dcb804837200f38e95381420', 3, 24, 922, 'Valider Contrat', 0, '1', '2017-10-17 13:58:52'),
(32178, 609, '9fe39b496077065105a57ccd9ed05863', 3, 24, 923, 'Désactiver Contrat', 0, '1', '2017-10-17 13:58:52'),
(32179, 609, 'faee342ff51dbe9f835529ae5b9b2a0b', 3, 24, 924, 'Détails  Contrat ', 0, '1', '2017-10-17 13:58:52'),
(32180, 609, '83406b6b206ed08878f2b2e854932ae5', 3, 24, 925, 'Détails   Contrat  ', 0, '1', '2017-10-17 13:58:52'),
(32181, 609, '8447888bef30fb983477cc1357ff7e6f', 3, 24, 926, 'Détails    Contrat ', 0, '1', '2017-10-17 13:58:52'),
(32182, 609, '4cc1845128f6a5ff3ed01100292d8ebb', 3, 24, 927, '  Détails    Contrat', 0, '1', '2017-10-17 13:58:52'),
(32183, 609, 'cd82d84c5f70a633b10aae88c34e9159', 3, 24, 928, '  Renouveler   Contrat ', 0, '1', '2017-10-17 13:58:52'),
(32184, 609, 'e9e994a0f8a204f1323fca7ce30931fe', 3, 24, 929, ' Détails  Contrat ', 0, '1', '2017-10-17 13:58:52'),
(32185, 609, 'b9e0a2a0236899590c72d31b878edfb2', 3, 24, 930, ' Renouveler  Contrat ', 0, '1', '2017-10-17 13:58:52'),
(32186, 610, 'ded24eb817021c5a666a677b1565bc5e', 3, 24, 931, 'Ajouter Contrat', 0, '1', '2017-10-17 13:58:52'),
(32187, 611, 'ed6b8695494bf4ed86d5fb18690b3a59', 3, 24, 932, 'Editer Contrat', 0, '1', '2017-10-17 13:58:52'),
(32188, 612, 'b8a40913b5955209994aaa26d0e8c3d4', 3, 24, 933, 'Supprimer Contrat', 0, '1', '2017-10-17 13:58:52'),
(32189, 613, '5efb874e7d73ccd722df806e8275770f', 3, 24, 934, 'Valider Contrat', 0, '1', '2017-10-17 13:58:52'),
(32190, 614, '64a5f976687a8c5f7cd3d672cc5d9c8c', 3, 24, 935, 'Détails Contrat', 0, '1', '2017-10-17 13:58:52'),
(32191, 615, '2cc55c65e79534161108288adb00472b', 3, 24, 936, 'Renouveler  Contrat', 0, '1', '2017-10-17 13:58:52'),
(32192, 432, 'f320732af279d6f2f8ae9c98cd0216de', 3, 24, 613, 'Gestion Départements', 0, '1', '2017-10-17 13:58:52'),
(32193, 432, '96516cd0c72d814d5dcb1d86eacd29ab', 3, 24, 617, 'Editer Département', 0, '1', '2017-10-17 13:58:52'),
(32194, 432, 'ef27a63534fa9fc3bd4b5086a92db546', 3, 24, 619, 'Valider Département', 0, '1', '2017-10-17 13:58:52'),
(32195, 432, '9aed965af4c4b89a5a23c41bf685d403', 3, 24, 620, 'Désactiver Département', 0, '1', '2017-10-17 13:58:52'),
(32196, 433, '722b3ba1c7fe735e87aa7415e5502a4c', 3, 24, 614, 'Ajouter Département', 0, '1', '2017-10-17 13:58:52'),
(32197, 434, 'daeb31006124e562d284aff67360ee19', 3, 24, 615, 'Editer Département', 0, '1', '2017-10-17 13:58:52'),
(32198, 435, 'a775da608fe55c53211d4f1c6e493251', 3, 24, 616, 'Supprimer Département', 0, '1', '2017-10-17 13:58:52'),
(32199, 436, 'bbb96ec910c5000a2006db2f6e8af10a', 3, 24, 618, 'Valider Département', 0, '1', '2017-10-17 13:58:52'),
(32200, 655, '0e79510db7f03b9b6266fc7b4a612153', 3, 24, 1005, 'Gestion Devis', 1, '1', '2017-10-17 13:58:52'),
(32201, 655, 'c15b00a1e37657336df8b6aa0eea2db5', 3, 24, 1006, 'Modifier Devis', 0, '1', '2017-10-17 13:58:52'),
(32202, 655, '998fb803f2e64f22418b3b388d6240a4', 3, 24, 1007, 'Envoi Devis au client', 0, '1', '2017-10-17 13:58:52'),
(32203, 655, '28e267a2a0647d4cb37b18abb1e7d051', 3, 24, 1008, 'Voir détails', 0, '1', '2017-10-17 13:58:52'),
(32204, 655, 'd34b07afd92adad84e1c4c2ebd92ba95', 3, 24, 1009, 'Voir détails', 0, '1', '2017-10-17 13:58:52'),
(32205, 655, 'f6f55b9d0ba9d704b2861d57cda32477', 3, 24, 1010, 'Réponse Client', 0, '1', '2017-10-17 13:58:52'),
(32206, 655, '4b11c0bfb3f970a541100f7fc334927e', 3, 24, 1011, 'Voir détails', 0, '1', '2017-10-17 13:58:52'),
(32207, 655, '61a0655c2c13039b5b8262b82ae6cb51', 3, 24, 1012, 'Voir détails', 0, '1', '2017-10-17 13:58:52'),
(32208, 655, 'ed30dfbb21d8d24a0da432252358bdf8', 3, 24, 1013, 'Voir détails', 0, '1', '2017-10-17 13:58:52'),
(32209, 655, '7bd2e025ffb3893dea4776e152301716', 3, 24, 1014, 'Débloquer devis', 0, '1', '2017-10-17 13:58:52'),
(32210, 655, '6d9ec7d9ebebcdc921376e7bf0c9fdaf', 3, 24, 1015, 'Valider devis', 0, '1', '2017-10-17 13:58:52'),
(32211, 655, 'b1bcc4a4ab154f110abcf54c0c659fb3', 3, 24, 1016, 'Voir détails', 0, '1', '2017-10-17 13:58:52'),
(32212, 655, '91a90a46e3430c491ab8db654b6e87c4', 3, 24, 1017, 'Voir détails', 0, '1', '2017-10-17 13:58:52'),
(32213, 656, 'd9eeb330625c1b87e0df00986a47be01', 3, 24, 1018, 'Ajouter Devis', 0, '1', '2017-10-17 13:58:52'),
(32214, 657, 'da93cdb05137e15aed9c4c18bddd746a', 3, 24, 1019, 'Ajouter détail devis', 0, '1', '2017-10-17 13:58:52'),
(32215, 658, 'f9f3c299f9bd0fec014f6bd3f0e06adb', 3, 24, 1020, 'Modifier Devis', 0, '1', '2017-10-17 13:58:52'),
(32216, 659, 'e14cce6f1faf7784adb327581c516b90', 3, 24, 1021, 'Supprimer Devis', 0, '1', '2017-10-17 13:58:52'),
(32217, 660, '38f10871792c133ebcc6040e9a11cde8', 3, 24, 1022, 'Modifier détail Devis', 0, '1', '2017-10-17 13:58:52'),
(32218, 661, '8def42e75fd4aee61c378d9fb303850d', 3, 24, 1023, 'Afficher détail devis', 0, '1', '2017-10-17 13:58:52'),
(32219, 662, '7666e87783b0f5a7eec1eea7593f7dfe', 3, 24, 1024, 'Valider Devis', 0, '1', '2017-10-17 13:58:52'),
(32220, 663, '7f1c48324d8c369da9aa6ab8af35acd8', 3, 24, 1025, 'Validation Client Devis', 0, '1', '2017-10-17 13:58:52'),
(32221, 664, '6adf896091dde0df89f777f31606953c', 3, 24, 1026, 'Débloquer devis', 0, '1', '2017-10-17 13:58:52'),
(32222, 665, '15cbb79dd4a74266158e6b29a83e683c', 3, 24, 1027, 'Archiver Devis', 1, '1', '2017-10-17 13:58:52'),
(32223, 675, '4c924acb9adc87d8389e8f9842a965c5', 3, 24, 1047, 'Gestion des factures', 0, '1', '2017-10-17 13:58:52'),
(32224, 675, '98a697ec628778765b25e02ba2929d38', 3, 24, 1048, 'Liste complément', 0, '1', '2017-10-17 13:58:52'),
(32225, 675, 'f8b20f7fec99b45b967a431d64b7f061', 3, 24, 1049, 'Liste encaissements', 0, '1', '2017-10-17 13:58:52'),
(32226, 675, '9a51fb5298e39a28af3ad6272fc51177', 3, 24, 1050, 'Valider facture', 0, '1', '2017-10-17 13:58:52'),
(32227, 675, '851f1d4c13f6025f69f5b9315321d350', 3, 24, 1051, 'Désactiver facture', 0, '1', '2017-10-17 13:58:52'),
(32228, 675, '5c79105956d28b5cac52f85784039919', 3, 24, 1052, 'Détail facture', 0, '1', '2017-10-17 13:58:52'),
(32229, 675, '7892721423af84a0b54e90250cf27ee3', 3, 24, 1053, 'Détails Facture', 0, '1', '2017-10-17 13:58:52'),
(32230, 675, 'b5380d403c9947ce060963f28e6d7539', 3, 24, 1054, 'Envoyer au client', 0, '1', '2017-10-17 13:58:52'),
(32231, 675, '80a4b2643b95c2836e968411811d3c21', 3, 24, 1055, 'Détails facture', 0, '1', '2017-10-17 13:58:52'),
(32232, 675, '2f679be3c0d7b88529209f86745f9028', 3, 24, 1056, 'Détails facture', 0, '1', '2017-10-17 13:58:52'),
(32233, 675, '429558e9a1e899c11051ea5c9a4f7942', 3, 24, 1057, 'Détails facture', 0, '1', '2017-10-17 13:58:52'),
(32234, 675, '3acd11d8d74fb7e1ba8d5721e96f91bd', 3, 24, 1058, 'Liste encaissements', 0, '1', '2017-10-17 13:58:52'),
(32235, 676, '55c3c5d2d93143b315513b7401043c8b', 3, 24, 1059, 'complements', 0, '1', '2017-10-17 13:58:52'),
(32236, 676, 'dfc4772cc03cf0b92a47f54fc6a2326e', 3, 24, 1060, 'Modifier complément', 0, '1', '2017-10-17 13:58:52'),
(32237, 677, '03a18bdd5201e433a3c523a2b34d059a', 3, 24, 1061, 'Ajouter complément', 0, '1', '2017-10-17 13:58:52'),
(32238, 678, '88d9bc979cd1102eb8196e7f5e6042ca', 3, 24, 1062, 'Encaissement', 0, '1', '2017-10-17 13:58:52'),
(32239, 678, 'c690cc68f5257c0c225b8b8e6126ea56', 3, 24, 1063, 'Modifier encaissement', 0, '1', '2017-10-17 13:58:52'),
(32240, 678, '1dc06f602e8630f273d44aa2751b2127', 3, 24, 1064, 'Détails encaissement', 0, '1', '2017-10-17 13:58:52'),
(32241, 679, 'e4866b292dbc3c9c5d9cc37273a5b498', 3, 24, 1065, 'Ajouter encaissement', 0, '1', '2017-10-17 13:58:52'),
(32242, 680, '8665be10959f39df4f149962eb70041f', 3, 24, 1066, 'Modifier complément', 0, '1', '2017-10-17 13:58:52'),
(32243, 681, '585d411904bf7d9e83d21b2810ff1d6c', 3, 24, 1067, 'Modifier encaissement', 0, '1', '2017-10-17 13:58:52'),
(32244, 682, '8c8b058a4d030cdc8b49c9008abb2e92', 3, 24, 1068, 'Supprimer complément', 0, '1', '2017-10-17 13:58:52'),
(32245, 683, '6bf7d5180940f03567a5d711e8563ba4', 3, 24, 1069, 'Supprimer encaissement', 0, '1', '2017-10-17 13:58:52'),
(32246, 684, '256abad0ec8e3bc8ed1c0653ff177255', 3, 24, 1070, 'Valider facture', 0, '1', '2017-10-17 13:58:52'),
(32247, 685, 'b5dc5719c1f96df7334f371dcf51a5b6', 3, 24, 1071, 'Détail encaissement', 0, '1', '2017-10-17 13:58:52'),
(32248, 686, '16fbf6fdcbb72f863bcf7e4ef28d8e75', 3, 24, 1072, 'Détails facture', 0, '1', '2017-10-17 13:58:52'),
(32249, 687, '5efdeb41007109ca99f23f0756217827', 3, 24, 1073, 'Désactiver Facture', 0, '1', '2017-10-17 13:58:52'),
(32250, 502, '6beb279abea6434e3b73229aebadc081', 3, 24, 725, 'Gestion Fournisseurs', 0, '1', '2017-10-17 13:58:52'),
(32251, 502, 'ff95747f3a590b6539803f2a9a394cd5', 3, 24, 730, 'Editer Fournisseur', 0, '1', '2017-10-17 13:58:52'),
(32252, 502, 'fea982f5074995d4ccd6211a71ab2680', 3, 24, 731, 'Valider Fournisseur', 0, '1', '2017-10-17 13:58:52'),
(32253, 502, '1d0411a0dec15fc28f054f1a79d95618', 3, 24, 732, 'Désactiver Fournisseur', 0, '1', '2017-10-17 13:58:52'),
(32254, 502, 'a52affdd109b9362ce47ff18aad53e2a', 3, 24, 737, 'Détails Fournisseur', 0, '1', '2017-10-17 13:58:52'),
(32255, 502, 'c6fe5f222dd563204188e8bf0d69bd9e', 3, 24, 738, 'Détails  Fournisseur', 0, '1', '2017-10-17 13:58:52'),
(32256, 503, 'd644015625a9603adb2fcc36167aeb73', 3, 24, 726, 'Ajouter Fournisseur', 0, '1', '2017-10-17 13:58:52'),
(32257, 504, '58c6694abfd3228d927a5d5a06d40b94', 3, 24, 727, 'Editer Fournisseur', 0, '1', '2017-10-17 13:58:52'),
(32258, 505, 'd072f81cd779e4b0152953241d713ca3', 3, 24, 728, 'Supprimer Fournisseur', 0, '1', '2017-10-17 13:58:52'),
(32259, 506, '657351ce5aa227513e3b50dea77db918', 3, 24, 729, 'Valider Fournisseur', 0, '1', '2017-10-17 13:58:52'),
(32260, 508, '83b693fe35a1be29edafe4f6170641aa', 3, 24, 736, 'Détails Fournisseur', 0, '1', '2017-10-17 13:58:52');
INSERT INTO `rules_action` (`id`, `appid`, `idf`, `service`, `userid`, `action_id`, `descrip`, `type`, `creusr`, `credat`) VALUES
(32261, 475, '605450f3d7c84701b986fa31e1e9fa43', 3, 24, 684, 'Gestion Pays', 0, '1', '2017-10-17 13:58:52'),
(32262, 475, '29ba6cc689eca63dbafb109ec58bc4d6', 3, 24, 689, 'Editer Pays', 0, '1', '2017-10-17 13:58:52'),
(32263, 475, '763fe13212b4324590518773cd9a36fa', 3, 24, 690, 'Valider Pays', 0, '1', '2017-10-17 13:58:52'),
(32264, 475, '3c8427c7313d35219b17572efd380b17', 3, 24, 691, 'Désactiver Pays', 0, '1', '2017-10-17 13:58:52'),
(32265, 476, '3cd55a55307615d72aae84c6b5cf99bc', 3, 24, 685, 'Ajouter Pays', 0, '1', '2017-10-17 13:58:52'),
(32266, 477, 'cfe617d7bc6a9c7d8b86c468f21396f2', 3, 24, 686, 'Editer Pays', 0, '1', '2017-10-17 13:58:52'),
(32267, 478, 'b768486aeb655c48cc411c11fa60e150', 3, 24, 687, 'Supprimer Pays', 0, '1', '2017-10-17 13:58:52'),
(32268, 479, '15e4e24f320daa9d563ae62acff9e586', 3, 24, 688, 'Valider Pays', 0, '1', '2017-10-17 13:58:52'),
(32269, 728, '192715027870a4a612fd44d562e2752f', 3, 24, 1151, 'Gestion des produits', 0, '1', '2017-10-17 13:58:52'),
(32270, 728, 'cb96e99d5f8e381637d1ac83f1a21f1c', 3, 24, 1152, 'Modifier  produit', 0, '1', '2017-10-17 13:58:52'),
(32271, 728, '64e84ff11fea7f68bcf6a5b744c36081', 3, 24, 1153, 'Détail  produit', 0, '1', '2017-10-17 13:58:52'),
(32272, 728, '0c94d85f4ee23656a01155ad1af5001c', 3, 24, 1154, 'Valider  produit', 0, '1', '2017-10-17 13:58:52'),
(32273, 728, '6b087b20929483bb07f8862b39e41f07', 3, 24, 1155, 'Désactiver produit', 0, '1', '2017-10-17 13:58:52'),
(32274, 728, '6f1d7cc5bd1c941beffa0ae3e1efd559', 3, 24, 1156, 'Achat  produit', 0, '1', '2017-10-17 13:58:52'),
(32275, 728, '41b9c4b7028269d4540915d6ec14ee79', 3, 24, 1157, 'Détails Produit', 0, '1', '2017-10-17 13:58:52'),
(32276, 729, '93e893c307a6fa63e392f78751ec70ce', 3, 24, 1158, 'Ajouter produit', 0, '1', '2017-10-17 13:58:52'),
(32277, 730, 'bcf3beada4a98e8145af2d4fbb744f01', 3, 24, 1159, 'Modifier produit', 0, '1', '2017-10-17 13:58:52'),
(32278, 731, '796427ec57f7c13d6b737055ae686b34', 3, 24, 1160, 'Detail produit', 0, '1', '2017-10-17 13:58:52'),
(32279, 732, '1fb8cd1a179be07586fa7db05013dd37', 3, 24, 1161, 'Valider produit', 0, '1', '2017-10-17 13:58:52'),
(32280, 733, '7779e98d2111faedf458f7aeb548294e', 3, 24, 1162, 'Supprimer produit', 0, '1', '2017-10-17 13:58:52'),
(32281, 734, '8da585a04e918c256bd26f0c03f1390d', 3, 24, 1163, 'Achat produit', 0, '1', '2017-10-17 13:58:52'),
(32282, 734, 'f8c9a7413089566d1db20dcc5ca17e03', 3, 24, 1164, 'Modifier achat', 0, '1', '2017-10-17 13:58:52'),
(32283, 734, '682b4328ee832101a44dac86b22d5757', 3, 24, 1165, 'Détail achat', 0, '1', '2017-10-17 13:58:52'),
(32284, 734, 'd1ebf1c5482ddf06721b11ec64afb744', 3, 24, 1166, 'Valider achat', 0, '1', '2017-10-17 13:58:52'),
(32285, 734, '368a1e91fc63e263eb01d85a34ecd89b', 3, 24, 1167, 'Désactiver achat', 0, '1', '2017-10-17 13:58:52'),
(32286, 735, '659be5cd86a12eba7e59c52d60198a36', 3, 24, 1168, 'Ajoute achat', 0, '1', '2017-10-17 13:58:52'),
(32287, 736, '8415336a17e8ca26f3eca5741863f3b2', 3, 24, 1169, 'Modifier achat', 0, '1', '2017-10-17 13:58:52'),
(32288, 737, '2c3b4875b72f7da6a87b5c0d7e85f51d', 3, 24, 1170, 'Supprimer achat', 0, '1', '2017-10-17 13:58:52'),
(32289, 738, 'd4180eb7a4ff86c598f441ffd4543f36', 3, 24, 1171, 'Détail achat', 0, '1', '2017-10-17 13:58:52'),
(32290, 739, '4a4c9b096bad58a96d5ea6f93d66e81c', 3, 24, 1172, 'Valider achat', 0, '1', '2017-10-17 13:58:52'),
(32291, 720, '1eb847d87adcad78d5e951e6110061e5', 3, 24, 1137, 'Gestion Proforma', 0, '1', '2017-10-17 13:58:52'),
(32292, 720, '44ef6849d8d5d17d8e0535187e923d32', 3, 24, 1138, 'Editer proforma', 0, '1', '2017-10-17 13:58:52'),
(32293, 720, 'b7ce06be726011362a271678547a803c', 3, 24, 1139, 'Valider Proforma', 0, '1', '2017-10-17 13:58:52'),
(32294, 720, 'abd8c50f1d2ef4beeeddb68a72973587', 3, 24, 1140, 'Détail Proforma', 0, '1', '2017-10-17 13:58:52'),
(32295, 720, '35a88b5c359908b063ac98cafc622987', 3, 24, 1141, 'Détail Proforma', 0, '1', '2017-10-17 13:58:52'),
(32296, 720, 'e20d83df90355eca2a65f56a2556601f', 3, 24, 1142, 'Détail Proforma', 0, '1', '2017-10-17 13:58:52'),
(32297, 720, '252ed64d8956e20fb88c1be41688f74a', 3, 24, 1143, 'Envoi proforma au client', 0, '1', '2017-10-17 13:58:52'),
(32298, 721, 'd5a6338765b9eab63104b59f01c06114', 3, 24, 1144, 'Ajouter pro-forma', 0, '1', '2017-10-17 13:58:52'),
(32299, 722, '95831bde77bc886d6ab4dd5e734de743', 3, 24, 1145, 'Editer proforma', 0, '1', '2017-10-17 13:58:52'),
(32300, 723, 'cbb4e1efa1c05b42d25a3a6bcab038a2', 3, 24, 1146, 'Ajouter détail proforma', 0, '1', '2017-10-17 13:58:52'),
(32301, 724, 'e9f745054778257a255452c6609461a0', 3, 24, 1147, 'valider Proforma', 0, '1', '2017-10-17 13:58:52'),
(32302, 725, 'defef148c404c7e6ac79e4783e0a7ab7', 3, 24, 1148, 'Détail Pro-forma', 0, '1', '2017-10-17 13:58:52'),
(32303, 726, '53008d64edf241c937a06f03eff139aa', 3, 24, 1149, 'Editer détail proforma', 0, '1', '2017-10-17 13:58:52'),
(32304, 727, '30e9d3d1ad17eb7f1fc0d5cbb9b58482', 3, 24, 1150, 'Supprimer proforma', 1, '1', '2017-10-17 13:58:52'),
(32305, 470, 'd57b16b3aad4ce59f909609246c4fd36', 3, 24, 676, 'Gestion des régions', 0, '1', '2017-10-17 13:58:52'),
(32306, 470, 'd2e007184668dd70b9bae44d46d28ded', 3, 24, 677, 'Modifier région', 0, '1', '2017-10-17 13:58:52'),
(32307, 470, 'e74403c99ac8325b78735c531a20442f', 3, 24, 678, 'Valider région', 0, '1', '2017-10-17 13:58:52'),
(32308, 470, '7397a0fab078728bd5c53be61022d5ce', 3, 24, 679, 'Désactiver région', 0, '1', '2017-10-17 13:58:52'),
(32309, 471, '0237bd41cf70c3529681b4ccb041f1fd', 3, 24, 680, 'Ajouter région', 0, '1', '2017-10-17 13:58:52'),
(32310, 472, '6d290f454da473cb8a557829a410c3f1', 3, 24, 681, 'Modifier région', 0, '1', '2017-10-17 13:58:52'),
(32311, 473, '008cd9ea5767c739675fef4e1261cfe8', 3, 24, 682, 'Valider région', 0, '1', '2017-10-17 13:58:52'),
(32312, 474, 'fc477e6a4c90cd427ae81e555c11d6a9', 3, 24, 683, 'Supprimer région', 0, '1', '2017-10-17 13:58:52'),
(32313, 34, '83b9fa44466da4bcd7f8304185bfeac8', 3, 24, 28, 'Services', 0, '1', '2017-10-17 13:58:52'),
(32314, 34, '3c388c1e842851df49abe9ee73c0a2e7', 3, 24, 33, 'Valider Service', 0, '1', '2017-10-17 13:58:52'),
(32315, 34, 'dcb9555d5ca1d108e9fa95daa9da4b3a', 3, 24, 34, 'Supprimer Service', 0, '1', '2017-10-17 13:58:52'),
(32316, 34, '74950fb3fd858404b6048c1e81bd7c9a', 3, 24, 144, 'Modifier Service', 0, '1', '2017-10-17 13:58:52'),
(32317, 35, '55043bc4207521e3010e91d6267f5302', 3, 24, 29, 'Ajouter Service', 1, '1', '2017-10-17 13:58:52'),
(32318, 36, '2fea3d893f6b6e81467ddd2a744e4a76', 3, 24, 30, 'Modifier Service', 1, '1', '2017-10-17 13:58:52'),
(32319, 37, '1a0d5897d31b4d5e29022671c1112f59', 3, 24, 31, 'Valider Service', 1, '1', '2017-10-17 13:58:52'),
(32320, 38, '42083d4e159baf7c2ace2bb977e2b0a0', 3, 24, 32, 'Supprimer Service', 1, '1', '2017-10-17 13:58:52'),
(32321, 460, 'b6b6bfbd070b5b3dd84acedae7b854e9', 3, 24, 660, 'Gestion des types de produits', 0, '1', '2017-10-17 13:58:52'),
(32322, 460, '3c5400b775264499825a039d66aa9c90', 3, 24, 661, 'Modifier type', 0, '1', '2017-10-17 13:58:52'),
(32323, 460, 'dcf55bc300d690af4c81e4d2335e60e5', 3, 24, 662, 'Valider type', 0, '1', '2017-10-17 13:58:52'),
(32324, 460, '230b9554d37da1c71986af94962cb340', 3, 24, 663, 'Désactiver type', 0, '1', '2017-10-17 13:58:52'),
(32325, 461, 'e0d163499b4ba11d6d7a648bc6fc6de6', 3, 24, 664, 'Ajouter un type', 0, '1', '2017-10-17 13:58:52'),
(32326, 462, 'ac5a6d087b3c8db7501fa5137a47773e', 3, 24, 665, 'Modifier type', 0, '1', '2017-10-17 13:58:52'),
(32327, 463, '2e8242a93a62a264ad7cfc953967f575', 3, 24, 666, 'Valider type', 0, '1', '2017-10-17 13:58:52'),
(32328, 464, 'e3725ba15ca483b9278f68553eca5918', 3, 24, 667, 'Supprimer type', 0, '1', '2017-10-17 13:58:52'),
(32329, 480, '312fd18860781a7b1b7e33587fa423d4', 3, 24, 692, 'Gestion Type Echeance', 0, '1', '2017-10-17 13:58:52'),
(32330, 480, '46ad76148075d6b458f43e84ddf00791', 3, 24, 697, 'Editer Type Echéance', 0, '1', '2017-10-17 13:58:52'),
(32331, 480, 'add2bf057924e606653fbf5bbd65ca09', 3, 24, 698, 'Valider Type Echéance', 0, '1', '2017-10-17 13:58:52'),
(32332, 480, '463d9e1e8367736b958f0dd84b4e36d5', 3, 24, 699, 'Désactiver Type Echéance', 0, '1', '2017-10-17 13:58:52'),
(32333, 481, '76170b14c7b6f1f7058d079fe24f739b', 3, 24, 693, 'Ajouter Type Echéance', 0, '1', '2017-10-17 13:58:52'),
(32334, 482, 'decc5ed58c4d91e6967c9c67e0975cf0', 3, 24, 694, 'Editer Type Echéance', 0, '1', '2017-10-17 13:58:52'),
(32335, 483, '89db6f23dd8e96a69c6a97f556c44e14', 3, 24, 695, 'Supprimer Type Echéance', 0, '1', '2017-10-17 13:58:52'),
(32336, 484, '7527021168823e0118d44297c7684d44', 3, 24, 696, 'Valider Type Echéance', 0, '1', '2017-10-17 13:58:52'),
(32337, 465, '55ecbb545a49c70c0b728bb0c7951077', 3, 24, 668, 'Gestion des unités de vente', 0, '1', '2017-10-17 13:58:52'),
(32338, 465, '67acd70eb04242b7091d9dcbb08295d7', 3, 24, 669, 'Modifier unité ', 0, '1', '2017-10-17 13:58:52'),
(32339, 465, '7363022ed5ad047bfe86d3de4b75b1f4', 3, 24, 670, 'Valider unité', 0, '1', '2017-10-17 13:58:52'),
(32340, 465, 'ec77eb95736c27bfc269cbffc8e113f1', 3, 24, 671, 'Désactiver unité', 0, '1', '2017-10-17 13:58:52'),
(32341, 466, '3a5e8dfe211121eda706f8b6d548d111', 3, 24, 672, 'ajouter une unité', 0, '1', '2017-10-17 13:58:52'),
(32342, 467, '9b7a578981de699286376903e96bc3c7', 3, 24, 673, 'Modifier une unité', 0, '1', '2017-10-17 13:58:52'),
(32343, 468, '62355588366c13ddbadc7a7ca1d226ad', 3, 24, 674, 'Valider une unité', 0, '1', '2017-10-17 13:58:52'),
(32344, 469, 'e5f53a3aaa324415d781156396938101', 3, 24, 675, 'Supprimer une unité', 0, '1', '2017-10-17 13:58:52'),
(32345, 709, '56de23d30d6c54297c8d9772cd3c7f57', 3, 24, 1115, 'Utilisateurs', 1, '1', '2017-10-17 13:58:52'),
(32346, 709, 'e656756fb7b39a4e6ddcabca75ff2970', 3, 24, 1116, 'Editer Utilisateur', 0, '1', '2017-10-17 13:58:52'),
(32347, 709, 'c073a277957ca1b9f318ac3902555708', 3, 24, 1117, 'Permissions', 0, '1', '2017-10-17 13:58:52'),
(32348, 709, 'c51499ddf7007787c4434661c658bbd1', 3, 24, 1118, 'Désactiver compte', 0, '1', '2017-10-17 13:58:52'),
(32349, 709, '10096b6f54456bcfc85081523ee64cf6', 3, 24, 1119, 'Supprimer utilisateur', 0, '1', '2017-10-17 13:58:52'),
(32350, 709, 'a0999cbed820aff775adf27276ee54a4', 3, 24, 1120, 'Editer Utilisateur', 0, '1', '2017-10-17 13:58:52'),
(32351, 709, '9aa6877656339ddff2478b20449a924b', 3, 24, 1121, 'Activer compte', 0, '1', '2017-10-17 13:58:52'),
(32352, 709, 'f4c79bb797b92dfa826b51a44e3171af', 3, 24, 1122, 'Utilisateurs', 0, '1', '2017-10-17 13:58:52'),
(32353, 709, 'd7f7afd70a297e5c239f6cf271138390', 3, 24, 1123, 'Utilisateur Archivé', 0, '1', '2017-10-17 13:58:52'),
(32354, 709, '17c98287fb82388423e04d24404cf662', 3, 24, 1124, 'Permissions', 0, '1', '2017-10-17 13:58:52'),
(32355, 709, '2c1c5556f30585c5b7c93fbbeaa98595', 3, 24, 1125, 'Historique session', 0, '1', '2017-10-17 13:58:52'),
(32356, 709, '7fd4ca84d162b70d3a4f0bad73e0e4b3', 3, 24, 1126, 'Liste Activitées', 0, '1', '2017-10-17 13:58:52'),
(32357, 710, 'df91a8e6f8ee2cde64495fc0cc7d6c6f', 3, 24, 1127, 'Ajouter Utilisateurs', 1, '1', '2017-10-17 13:58:52'),
(32358, 711, '2bb46b52eab9eecbdbba35605da07234', 3, 24, 1128, 'Editer Utilisateurs', 1, '1', '2017-10-17 13:58:52'),
(32359, 712, '3f59a1326df27378304e142ab3bec090', 3, 24, 1129, 'Permission', 1, '1', '2017-10-17 13:58:52'),
(32360, 713, 'b919571c88d036f8889742a81a4f41fd', 3, 24, 1130, 'Supprimer utilisateur', 1, '1', '2017-10-17 13:58:52'),
(32361, 714, '38f89764a26e39ce029cd3132c12b2a5', 3, 24, 1131, 'Compte utilisateur', 1, '1', '2017-10-17 13:58:52'),
(32362, 715, 'f988a608f35a0bc551cb038b1706d207', 3, 24, 1132, 'Activer utilisateur', 1, '1', '2017-10-17 13:58:52'),
(32363, 716, 'b7b3a09fdd73a5b0a3e5ed8a2828f548', 3, 24, 1133, 'Désactiver l\'utilisateur', 1, '1', '2017-10-17 13:58:52'),
(32364, 717, '0d374b7e2fe21a2e2641c092a3c7f2e9', 3, 24, 1134, 'Changer le mot de passe', 1, '1', '2017-10-17 13:58:52'),
(32365, 718, '6f642ee30722158f0318653b9113b887', 3, 24, 1135, 'History', 1, '1', '2017-10-17 13:58:52'),
(32366, 719, 'cc907fac13631903d129c137d671d718', 3, 24, 1136, 'Activities', 1, '1', '2017-10-17 13:58:52'),
(32367, 430, '3d4eaa53061f51b0c4435bd8e4b89c17', 3, 24, 611, 'Gestion Vente', 0, '1', '2017-10-17 13:58:52'),
(32368, 89, '2c3b01c696ff401a2ac9ffedb7a06e4a', 3, 24, 114, 'Gestion Villes', 1, '1', '2017-10-17 13:58:52'),
(32369, 89, 'b9649163b368f863a0e8036f11cd81ae', 3, 24, 119, 'Editer Ville', 0, '1', '2017-10-17 13:58:52'),
(32370, 89, '89dec6dabcb210cdb9dd28bbef90d43e', 3, 24, 121, 'Editer Ville', 0, '1', '2017-10-17 13:58:52'),
(32371, 89, '4a2edbdcbda34c9d3d1e6abe73643b37', 3, 24, 602, 'Valider Ville', 0, '1', '2017-10-17 13:58:52'),
(32372, 89, '0c8ad6595a4516be83ba5a9cdb7ea9a1', 3, 24, 603, 'Désactiver Ville', 0, '1', '2017-10-17 13:58:52'),
(32373, 90, 'e152b9052d3dcfcac593489dbdc0f61c', 3, 24, 115, 'Ajouter ville', 1, '1', '2017-10-17 13:58:52'),
(32374, 91, '3107e0cd0e0df14c4e94aa088e4457d7', 3, 24, 116, 'Editer Ville', 1, '1', '2017-10-17 13:58:52'),
(32375, 92, 'da79d9214ed5819d7f4f1e3070629a3d', 3, 24, 117, 'Supprimer Ville', 1, '1', '2017-10-17 13:58:52'),
(32376, 423, 'fe03a68d17c62ff2c27329573a1b3550', 3, 24, 601, 'Valider Ville', 0, '1', '2017-10-17 13:58:52');

-- --------------------------------------------------------

--
-- Structure de la table `rules_action_temp`
--

CREATE TABLE `rules_action_temp` (
  `id` int(11) NOT NULL COMMENT 'rule id',
  `idf` varchar(32) CHARACTER SET latin1 DEFAULT NULL COMMENT 'IDF Rul Mgt',
  `service` int(11) DEFAULT NULL COMMENT 'Service ID',
  `userid` int(11) NOT NULL COMMENT 'id user',
  `descrip` varchar(75) CHARACTER SET latin1 NOT NULL COMMENT 'description de rule'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table store rules for each user for each App and action';

-- --------------------------------------------------------

--
-- Structure de la table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL COMMENT 'ID Groupe',
  `service` varchar(150) CHARACTER SET latin1 NOT NULL COMMENT 'Nom du Groupe',
  `sign` int(11) DEFAULT NULL COMMENT 'Exige une Signature',
  `etat` int(11) DEFAULT '0' COMMENT 'Etat line',
  `creusr` int(11) DEFAULT NULL COMMENT 'Créér par',
  `credat` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'Date de création',
  `updusr` int(11) DEFAULT NULL COMMENT 'Modifiée par',
  `upddat` datetime DEFAULT NULL COMMENT 'Date de modification'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `services`
--

INSERT INTO `services` (`id`, `service`, `sign`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(1, 'Administrateur', 0, 1, NULL, '2016-12-05 22:48:26', 1, '2017-10-08 20:40:55'),
(2, 'Commercial', 1, 1, NULL, '2016-12-05 22:48:26', 1, '2017-10-13 20:07:11'),
(3, 'Direction Générale', 1, 1, NULL, '2016-12-05 22:48:26', NULL, NULL),
(4, 'Informatique', 1, 1, NULL, '2016-12-05 22:48:26', NULL, NULL),
(5, 'Finance', 0, 1, NULL, '2016-12-05 22:48:26', NULL, NULL),
(20, 'Service Technique', 0, 1, 1, '2017-10-08 21:18:02', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `session`
--

CREATE TABLE `session` (
  `id_sys` int(11) NOT NULL COMMENT 'ID SYS',
  `id` varchar(32) CHARACTER SET latin1 NOT NULL COMMENT 'id session MD5',
  `user` varchar(20) CHARACTER SET latin1 NOT NULL COMMENT 'Nom utilisateur',
  `dat` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Time open session',
  `expir` datetime DEFAULT NULL COMMENT 'Date expiration',
  `ip` varchar(15) CHARACTER SET latin1 NOT NULL COMMENT 'IP Client',
  `browser` varchar(100) CHARACTER SET latin1 NOT NULL COMMENT 'Browser Utilisé',
  `userid` int(11) NOT NULL COMMENT 'ID utilisateur'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `session`
--

INSERT INTO `session` (`id_sys`, `id`, `user`, `dat`, `expir`, `ip`, `browser`, `userid`) VALUES
(1, '0e95763e4f2bc8b6c7492a3ec93fd571', 'admin', '2017-08-23 00:02:21', '2017-08-23 00:51:39', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:54.0) Gecko/20100101 Firefox/54.0', 1),
(2, '70bc548a819698be570b193cb8c430a8', 'admin', '2017-08-23 11:10:13', '2017-08-23 18:25:44', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:54.0) Gecko/20100101 Firefox/54.0', 1),
(3, 'bd8493fd57aecdc70f77cfe6b040df70', 'admin', '2017-08-23 18:25:46', '2017-08-24 12:15:14', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:54.0) Gecko/20100101 Firefox/54.0', 1),
(4, 'c2e63a9678583386daf3d83f8d6cefb5', 'admin', '2017-08-24 12:15:21', '2017-08-24 14:07:44', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:54.0) Gecko/20100101 Firefox/54.0', 1),
(5, '8e7c04f199bd9cfdf517b8788603b1bd', 'admin', '2017-08-24 14:10:06', '2017-08-24 16:18:07', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:54.0) Gecko/20100101 Firefox/54.0', 1),
(6, '5fa113b5833545f3dc436f295190777c', 'admin', '2017-08-24 16:18:11', '2017-08-24 16:22:09', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:54.0) Gecko/20100101 Firefox/54.0', 1),
(7, 'f9a3721bdf8271eb15a1d7d213700bfe', 'admin', '2017-08-24 16:22:11', '2017-08-24 18:04:44', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:54.0) Gecko/20100101 Firefox/54.0', 1),
(8, 'fd166126ce9f0b9577822eb8442aedca', 'admin', '2017-08-24 18:04:48', '2017-08-25 16:08:37', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:54.0) Gecko/20100101 Firefox/54.0', 1),
(9, '551ac3e8a9e549db4cd8f808c2ffc0e8', 'admin', '2017-08-25 16:09:41', '2017-08-25 23:28:26', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:54.0) Gecko/20100101 Firefox/54.0', 1),
(10, '6d03b54865c6e982dcf25b000c2a5a3h', 'admin', '2017-08-25 23:28:48', '2017-08-26 09:53:47', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:54.0) Gecko/20100101 Firefox/54.0', 1),
(11, 'bb54f061709179ac41bd2db1b59b2832', 'admin', '2017-08-26 09:53:51', '2017-08-26 14:55:11', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:54.0) Gecko/20100101 Firefox/54.0', 1),
(12, '986f39e5a0acde23bb0d7b9d7b583b41', 'admin', '2017-08-26 14:57:04', '2017-08-26 16:28:14', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:54.0) Gecko/20100101 Firefox/54.0', 1),
(13, '6c066689c176bee29fe11631beb251c0', 'admin', '2017-08-26 16:28:26', '2017-08-26 18:23:12', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:54.0) Gecko/20100101 Firefox/54.0', 1),
(14, '4e93cb85295b9cff46c12144733b7a56', 'admin', '2017-08-26 18:27:12', '2017-08-26 23:29:34', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:54.0) Gecko/20100101 Firefox/54.0', 1),
(15, '63e5d02cd68fe495306366657587bc8c', 'admin', '2017-08-26 23:29:43', '2017-08-30 16:45:17', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:54.0) Gecko/20100101 Firefox/54.0', 1),
(16, '82ebf4fed8dcb3a2076e118e7cfec521', 'admin', '2017-08-30 16:45:36', '2017-08-31 00:18:54', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:54.0) Gecko/20100101 Firefox/54.0', 1),
(17, '15c5207aa858e0559f0493417551ee8c', 'admin', '2017-08-31 00:19:01', '2017-08-31 13:56:27', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:54.0) Gecko/20100101 Firefox/54.0', 1),
(18, 'c217f6ae5a4e16250899342d547e8e07', 'admin', '2017-08-31 13:56:31', '2017-08-31 15:53:27', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:54.0) Gecko/20100101 Firefox/54.0', 1),
(19, 'efe2496f807a9cb9d8034eaa419818a6', 'admin', '2017-08-31 15:53:32', '2017-09-02 11:42:59', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:54.0) Gecko/20100101 Firefox/54.0', 1),
(20, 'e2170497134d39fd7a40deb9f0460e1a', 'admin', '2017-09-02 11:43:03', '2017-09-04 11:10:09', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:54.0) Gecko/20100101 Firefox/54.0', 1),
(21, '5bc1b86b4231b63400f2ef5a74763335', 'admin', '2017-09-04 11:10:14', '2017-09-05 12:33:03', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:54.0) Gecko/20100101 Firefox/54.0', 1),
(22, 'd3a902611ae8a81f2f1d59cb74567f06', 'admin', '2017-09-05 12:33:08', '2017-09-05 23:55:06', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:54.0) Gecko/20100101 Firefox/54.0', 1),
(23, 'cec3821ef0655f217ec6d504b4e8b24c', 'admin', '2017-09-05 23:55:09', '2017-09-06 01:06:36', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:54.0) Gecko/20100101 Firefox/54.0', 1),
(24, '9b765351589c402d67533066255f8829', 'admin', '2017-09-06 01:06:42', '2017-09-08 11:16:40', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:54.0) Gecko/20100101 Firefox/54.0', 1),
(25, '64120a40ba8fd5f3c17915d6b37cadae', 'admin', '2017-09-08 11:18:26', '2017-09-08 17:19:23', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:54.0) Gecko/20100101 Firefox/54.0', 1),
(26, '2b82350097b5b334bf9e367ce263398a', 'admin', '2017-09-08 17:19:26', '2017-09-08 19:03:09', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:54.0) Gecko/20100101 Firefox/54.0', 1),
(27, '2a11fbf3b8fffcda92e55b169f194a5e', 'admin', '2017-09-08 19:03:21', '2017-09-08 21:36:51', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:54.0) Gecko/20100101 Firefox/54.0', 1),
(28, 'd3b1d7705acd202335d1f5d9ecef869d', 'admin', '2017-09-08 21:36:54', '2017-09-09 14:11:48', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:54.0) Gecko/20100101 Firefox/54.0', 1),
(29, 'df693a967e595e2159340c159c7382a8', 'admin', '2017-09-09 14:11:52', '2017-09-10 17:28:25', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:54.0) Gecko/20100101 Firefox/54.0', 1),
(30, '95d6dd9c84204324df17bce0d56f26f1', 'admin', '2017-09-10 17:28:29', '2017-09-11 16:54:30', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:54.0) Gecko/20100101 Firefox/54.0', 1),
(31, '06f57704c407fea987ccfd7f6d8ca968', 'admin', '2017-09-11 16:54:34', '2017-09-11 17:12:10', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:54.0) Gecko/20100101 Firefox/54.0', 1),
(32, 'd13bbcfa503f3c9c51c7347f174ba820', 'admin', '2017-09-11 17:12:18', '2017-09-11 17:17:44', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:54.0) Gecko/20100101 Firefox/54.0', 1),
(33, 'baa88fe9d9cab17c009e727993e9c13c', 'admin', '2017-09-11 17:18:58', '2017-09-11 19:12:06', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:54.0) Gecko/20100101 Firefox/54.0', 1),
(34, 'a9535f303d5d030aa7677832e87920a3', 'admin', '2017-09-11 19:12:11', '2017-09-11 22:44:28', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:54.0) Gecko/20100101 Firefox/54.0', 1),
(35, 'caaea8460aaf3953d334a6b5445ba989', 'admin', '2017-09-12 21:38:30', '2017-09-13 09:52:33', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:54.0) Gecko/20100101 Firefox/54.0', 1),
(36, 'c90724666e887cd82870b95e693c27d5', 'admin', '2017-09-13 09:52:40', '2017-09-13 15:47:00', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:54.0) Gecko/20100101 Firefox/54.0', 1),
(37, 'dc4d8445f202aa21f8a7e7763c6c23b6', 'admin', '2017-09-13 15:47:03', '2017-09-13 18:58:55', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:54.0) Gecko/20100101 Firefox/54.0', 1),
(38, 'd914cbe7f70bfde34f5625c2f685b6b4', 'admin', '2017-09-13 18:58:55', '2017-09-13 21:10:49', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(39, '2b354852276d48e4f324bf9b31500c3e', 'admin', '2017-09-13 21:10:52', '2017-09-14 00:56:51', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(40, '87bb510f157c1be80c8f982208e134c4', 'admin', '2017-09-14 00:56:54', '2017-09-14 11:50:07', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(41, 'd8da9a80fcd0ee17bbac9f05e74d40f3', 'admin', '2017-09-14 12:00:47', '2017-09-14 19:21:27', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(42, 'f32dffd1946537bde0883e46c866af8f', 'admin', '2017-09-14 19:22:39', '2017-09-14 21:10:19', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(43, '35c0e1987e403084bea83e0752116339', 'admin', '2017-09-14 21:10:19', '2017-09-15 00:17:49', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(44, '6630e85832c45b66113c9dce5dd13010', 'admin', '2017-09-15 00:17:52', '2017-09-15 11:30:04', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(45, 'ccfaa0dbd19d6934f9a9036e69d6db16', 'admin', '2017-09-15 11:30:08', '2017-09-15 16:20:42', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(46, 'eca69cd0fa7cec2f20cda50fc92816e5', 'admin', '2017-09-15 16:20:55', '2017-09-15 18:59:59', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(47, '6cf92b85c7fe127a8a47f13d15019d4d', 'admin', '2017-09-15 19:03:20', '2017-09-15 20:42:37', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(48, '55530a49e55a43ea919d9684ebe6d1cc', 'admin', '2017-09-15 20:43:33', '2017-09-16 12:39:20', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(49, '512cf8cf89fabeb2ff998adf8c92ebd6', 'admin', '2017-09-16 12:39:44', '2017-09-16 16:30:40', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(50, '1f48845af56fa19700e1063812628e5c', 'admin', '2017-09-16 16:30:43', '2017-09-16 18:45:14', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(51, '5c85b18acf1d009babb9371662d23d2f', 'admin', '2017-09-16 18:45:17', '2017-09-16 20:20:07', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(52, 'cae3384666fa6b2b950eb525fd855511', 'admin', '2017-09-16 20:20:11', '2017-09-16 21:31:42', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(53, '8513882373a13710b0da224264d77339', 'admin', '2017-09-16 23:05:04', '2017-09-17 12:30:21', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(54, 'b4af2eb792a32d01092f493ce39f07ce', 'admin', '2017-09-17 12:30:24', '2017-09-18 12:55:50', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(55, '7752cbc1b7d4c172cc49ddb9b8125ef0', 'admin', '2017-09-18 12:59:25', '2017-09-18 13:19:00', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(56, '4487aac9f0ec4813b69929c166bbf142', 'admin', '2017-09-18 13:19:00', '2017-09-18 15:34:38', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(57, '0b0dae7749ea31b3f89c9302f31091e9', 'admin', '2017-09-18 15:47:33', '2017-09-19 12:50:54', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(58, '6c8f36a098e087478c5fe352728667dc', 'admin', '2017-09-19 12:50:57', '2017-09-20 12:51:02', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(59, '399e2de0b09def770358b97488780a65', 'admin', '2017-09-20 12:51:07', '2017-09-20 15:28:09', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(60, 'ca38e7aafe2522f4403fda94a0248474', 'admin', '2017-09-20 15:28:17', '2017-09-20 15:36:20', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(61, 'aafa01caa8b9182576d2aaaaae256a41', 'admin', '2017-09-20 15:36:20', '2017-09-20 20:20:42', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(62, 'fcb5b2d68eb5d0e45a3d3dc40c3ab68d', 'admin', '2017-09-20 20:20:45', '2017-09-20 23:24:41', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(63, '5d6b527f9da291cf3112b3f6bcdc89e4', 'admin', '2017-09-20 23:24:48', '2017-09-21 12:01:34', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(64, 'e68fdcc1b706a85e23f910b2660248c1', 'admin', '2017-09-21 12:01:38', '2017-09-21 15:05:32', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(65, '2bf8b65710ba400513a97b4bf1f60732', 'admin', '2017-09-21 15:05:32', '2017-09-21 17:17:37', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(66, '0db2c54e07b1078ac3aa7918b2a2a5fa', 'admin', '2017-09-21 17:17:43', '2017-09-21 22:39:49', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(67, '12a5b907325da7f5b7c3f0087437fa32', 'admin', '2017-09-21 22:39:57', '2017-09-22 01:28:01', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(68, '6242e6cb7bb89007b4e939d8f504e67a', 'admin', '2017-09-22 01:28:17', '2017-09-22 10:58:30', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(69, '6bc6c428629bed8c128460b15af2f1b9', 'admin', '2017-09-22 10:58:32', '2017-09-22 12:03:16', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(70, '49bb28e3b197c00cf7d7862eb9dacbf1', 'admin', '2017-09-22 18:25:07', '2017-09-22 22:57:04', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(71, '51f5e16330ce20d3bd56c62bc8e65b74', 'admin', '2017-09-22 23:12:16', '2017-09-23 02:08:47', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(72, '840585c4dc51e5667f51b33b80611b36', 'admin', '2017-09-23 02:08:47', '2017-09-25 23:54:40', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(73, 'defe17f7849a334a3d3028c51856b79b', 'admin', '2017-09-25 23:54:45', '2017-09-26 23:31:09', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(74, '2fb7503bef748566174b51d553b211f6', 'admin', '2017-09-27 00:57:14', '2017-09-27 22:22:52', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(75, '39174b634240b5a71b158d4eebaf794d', 'admin', '2017-09-27 22:23:21', '2017-09-27 23:34:34', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(76, '5a17ebf2821476a0733348a636223c42', 'admin', '2017-09-28 00:09:04', '2017-09-28 21:51:34', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(77, '0e1d4f0bae6d5bd7f1bf0fd66a566679', 'admin', '2017-09-29 12:32:01', '2017-09-29 13:33:02', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(78, '32134cbd715033de5353f799dcf3eb4d', 'admin', '2017-09-29 15:53:29', '2017-09-29 17:45:47', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(79, '6ec1ea425927487b127f4263442d6dd4', 'admin', '2017-09-29 19:08:44', '2017-09-29 20:25:43', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(80, '5fced9c3a65b9cba28c98bfbf5c7b13b', 'admin', '2017-09-30 12:51:26', '2017-09-30 14:28:33', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(81, '48bad7f3314fc0e1b068eb5460b5b7a3', 'admin', '2017-09-30 15:16:44', '2017-09-30 19:52:01', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(82, 'e28f22812a7441d562217aad09715ccd', 'admin', '2017-10-01 01:22:54', '2017-10-01 10:45:07', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(83, 'be194acd810ea09649150584e46dd99b', 'admin', '2017-10-01 11:33:35', '2017-10-01 15:22:38', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(84, '13cba7ec2bb407b05dc9ce94c6cb54ea', 'admin', '2017-10-01 15:22:38', '2017-10-01 20:19:38', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(85, '763fb383338ffa438c659e4b9d272b07', 'admin', '2017-10-01 20:20:09', '2017-10-01 20:26:01', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(86, '87e39e33c02cf6c61e5eeaf76c4cf6ba', 'admin', '2017-10-01 20:26:01', '2017-10-02 10:53:29', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(87, '44215c028be6f89c8c8aeec77ad788b9', 'admin', '2017-10-02 11:06:56', '2017-10-02 12:43:22', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(88, '61ac9da34a32a8e131354a81abb7e2c4', 'admin', '2017-10-02 16:04:44', '2017-10-02 21:07:49', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(89, '075251aa6f5582a39cf5d68e1eb430aa', 'admin', '2017-10-02 21:18:57', '2017-10-08 17:36:51', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0', 1),
(90, '11f91a0f44a688a1933e9fbab7c5e314', 'admin', '2017-10-08 17:36:51', '2017-10-08 17:45:02', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 1),
(91, '46a1e7d30871fa61d1f4cb97c6a3b780', 'admin', '2017-10-08 17:45:02', '2017-10-08 18:28:52', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 1),
(92, 'ba1e1b3ff17d4aacde80071cac9eb888', 'admin', '2017-10-08 18:29:20', '2017-10-08 20:30:49', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 1),
(93, '52ca913087bd8c2258032dfbc7033357', 'admin', '2017-10-08 20:30:51', '2017-10-09 11:28:12', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 1),
(94, 'ffed1f3b2e729560300cb321251e5dc5', 'admin', '2017-10-09 11:28:12', '2017-10-09 18:42:07', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 1),
(95, '1c449d7f987087613857bebe42413e18', 'admin', '2017-10-09 18:42:15', '2017-10-09 19:25:13', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 1),
(96, '1aa08b3298af21e699bb92e497824161', 'admin', '2017-10-09 19:25:24', '2017-10-09 20:47:00', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 1),
(97, '4988aef3dea4822b917c6db8fc4a568e', 'admin', '2017-10-09 20:47:12', '2017-10-09 22:46:46', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 1),
(98, '230cbd72ed5d26903ef3e2a89846e796', 'admin', '2017-10-09 22:46:50', '2017-10-10 10:46:19', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 1),
(99, '409af2e9f4925abd42cd30ff140a811b', 'admin', '2017-10-10 10:46:28', '2017-10-10 14:20:56', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 1),
(100, '4ff7de54f4e089a1c28cec769d331fe0', 'admin', '2017-10-10 23:10:46', '2017-10-10 23:13:24', '197.159.16.2', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 1),
(101, '2d163f16f966b34fd27bc63c713ebecf', 'admin', '2017-10-10 23:23:47', '2017-10-10 23:50:32', '197.159.16.2', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 1),
(102, '37806e1ac8a8f19f5724ff8e1c8d9559', 'admin', '2017-10-10 23:50:32', '2017-10-10 23:54:24', '197.159.16.2', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 1),
(103, 'a093438a55a6b22714dd5045e016145f', 'admin', '2017-10-10 23:59:49', '2017-10-11 00:32:27', '197.159.16.2', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 1),
(104, '40e22a24e9c7a5e5922fd042f8e1e494', 'admin', '2017-10-11 00:30:21', '2017-10-11 00:40:40', '41.250.166.141', 'Mozilla/5.0 (Windows NT 6.2; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100', 1),
(105, '6f3366332f17ce6c8fe252cb7d48d9d3', 'admin', '2017-10-11 00:32:49', '2017-10-11 00:47:21', '197.159.16.2', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 1),
(106, '4602527ee46ac6bda5ccbda93315f733', 'atoub', '2017-10-11 00:45:34', NULL, '41.250.166.141', 'Mozilla/5.0 (Windows NT 6.2; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100', 19),
(107, '52d3d75c002d862e0c7f796dcadd1c43', 'ayoub', '2017-10-11 00:47:34', '2017-10-11 00:48:58', '197.159.16.2', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 19),
(108, '3be5373a719dea0283839724ee1fe487', 'ayoub', '2017-10-11 00:48:58', '2017-10-11 00:49:53', '41.250.166.141', 'Mozilla/5.0 (Windows NT 6.2; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100', 19),
(109, 'dad3092249bb61992807edb7f1af6b76', 'admin', '2017-10-11 00:49:40', '2017-10-11 00:49:47', '41.250.166.141', 'Mozilla/5.0 (Windows NT 6.2; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100', 1),
(110, 'c1b549fd7ad1c62f5d9b18de8da4ea4d', 'ayoub', '2017-10-11 00:49:53', '2017-10-11 00:50:48', '41.250.166.141', 'Mozilla/5.0 (Windows NT 6.2; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100', 19),
(111, '5014bf18f75d5749b701f35d944e46cd', 'ayoub', '2017-10-11 00:50:48', '2017-10-11 10:13:55', '41.250.166.141', 'Mozilla/5.0 (Windows NT 6.2; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100', 19),
(112, 'ecd59428996f9b20d334f8f702c3d89e', 'admin', '2017-10-11 00:53:54', '2017-10-11 00:56:33', '197.159.16.2', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 1),
(113, '0923abb35e55f5f48def8dc5837dadb2', 'kada', '2017-10-11 00:56:49', '2017-10-13 18:12:52', '197.159.16.2', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 20),
(114, 'b1da6fc9f37446549af5d3ebb3653acc', 'admin', '2017-10-11 00:59:14', '2017-10-11 12:46:10', '197.159.16.2', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.10', 1),
(115, '55a25d6f01b2d19233a6af3540c68082', 'ayoub', '2017-10-11 10:13:55', '2017-10-11 12:03:24', '105.157.38.129', 'Mozilla/5.0 (Windows NT 6.2; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100', 19),
(116, '82ee33eb0941f52564f5c3df663db33e', 'ayoub', '2017-10-11 12:04:20', '2017-10-11 12:46:36', '41.141.239.29', 'Mozilla/5.0 (Windows NT 6.2; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100', 19),
(117, '976f3dc1433c9286fac283d6fbfeb820', 'admin', '2017-10-11 12:46:10', '2017-10-11 13:29:11', '37.208.61.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:53.0) Gecko/20100101 Firefox/53.0', 1),
(118, '35a5b73c7ec420c4c04955c4b20de261', 'ayoub', '2017-10-11 12:46:40', '2017-10-11 14:10:34', '160.176.13.5', 'Mozilla/5.0 (Windows NT 6.2; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100', 19),
(119, 'c23fc332ba1abd9a640c628a47a5abc2', 'admin', '2017-10-11 13:29:11', '2017-10-11 18:23:46', '41.251.179.71', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:56.0) Gecko/20100101 Firefox/56.0', 1),
(120, '01007fca2baead399291f3a5679bbaf6', 'ayoub', '2017-10-11 14:10:34', '2017-10-11 14:11:44', '41.141.122.254', 'Mozilla/5.0 (Windows NT 6.2; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100', 19),
(121, 'b1e12a55b382c0a8163dff27ad001b3c', 'ayoub', '2017-10-11 14:11:47', '2017-10-11 20:14:54', '41.141.122.254', 'Mozilla/5.0 (Windows NT 6.2; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100', 19),
(122, 'ddb1945471235595f32323cb24161761', 'admin', '2017-10-11 18:23:46', '2017-10-12 20:33:30', '197.159.16.2', 'Mozilla/5.0 (Linux; Android 7.0; SAMSUNG SM-G928T Build/NRD90M) AppleWebKit/537.36 (KHTML, like Geck', 1),
(123, '5a67c6046a95e953d912355b751bdcb4', 'ayoub', '2017-10-11 20:14:54', '2017-10-13 17:25:14', '41.141.122.254', 'Mozilla/5.0 (Windows NT 6.2; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100', 19),
(124, '7906ca7d8669e44eb9aeadd517ebdb06', 'admin', '2017-10-12 20:33:30', '2017-10-13 15:46:31', '154.73.165.5', 'Mozilla/5.0 (Linux; Android 7.0; SAMSUNG SM-G928T Build/NRD90M) AppleWebKit/537.36 (KHTML, like Geck', 1),
(125, 'b2f2483780bfd0c17b60b4d984a9df01', 'admin', '2017-10-13 15:46:31', '2017-10-13 16:54:58', '197.159.16.2', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 1),
(126, '6258e525a08103289474e137703fd29f', 'admin', '2017-10-13 16:55:08', '2017-10-13 17:39:02', '197.159.16.2', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 1),
(127, 'fb4b98b562a5d89e951059f9e521826a', 'ayoub', '2017-10-13 17:25:14', '2017-10-13 18:13:42', '160.176.106.143', 'Mozilla/5.0 (Windows NT 6.2; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100', 19),
(128, '2c360194cdcb06273fbead4dc75759c5', 'admin', '2017-10-13 17:26:08', '2017-10-13 17:39:13', '160.176.22.36', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:56.0) Gecko/20100101 Firefox/56.0', 1),
(129, '2f83371783b34c563bb8132f9618b82a', 'admin', '2017-10-13 17:39:13', '2017-10-13 18:12:36', '197.159.16.2', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 1),
(130, '41ac501ac8df2dca5605b301795d77cf', 'admin', '2017-10-13 18:11:25', '2017-10-13 19:04:34', '160.176.22.36', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:56.0) Gecko/20100101 Firefox/56.0', 1),
(131, 'f4ce831c3916fd7902e3dc28ad3ffe03', 'kada', '2017-10-13 18:12:52', '2017-10-13 18:44:25', '197.159.16.2', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 20),
(132, '7fc79fc3221cdd2fbc8d93e7bdea1556', 'ayoub', '2017-10-13 18:13:42', '2017-10-13 20:06:42', '160.176.106.143', 'Mozilla/5.0 (Windows NT 6.2; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100', 19),
(133, '7d34222ad97218bec41a215699f50e49', 'admin', '2017-10-13 18:44:36', '2017-10-13 19:05:02', '197.159.16.2', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 1),
(134, 'c8258516145327f4980fc2308c0c9b3f', 'admin', '2017-10-13 19:05:02', '2017-10-13 20:05:39', '160.176.22.36', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:56.0) Gecko/20100101 Firefox/56.0', 1),
(135, '1f9f6e181db8892c33bea7d95af80be8', 'admin', '2017-10-13 20:05:39', '2017-10-13 20:18:00', '41.143.26.214', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:56.0) Gecko/20100101 Firefox/56.0', 1),
(136, 'f1686f5d55494f3ba5185adef2886dc0', 'ayoub', '2017-10-13 20:06:42', '2017-10-13 20:13:27', '160.176.106.143', 'Mozilla/5.0 (Windows NT 6.2; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100', 19),
(137, '70362e4c7cf45c6485f7b1dd9353a1bd', 'kada', '2017-10-13 20:07:40', '2017-10-16 21:10:10', '197.159.16.2', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 20),
(138, '23aef8a8c7282eb61b6751f0c24fec5c', 'ayoub', '2017-10-13 20:13:27', '2017-10-13 20:19:18', '160.176.106.143', 'Mozilla/5.0 (Windows NT 6.2; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100', 19),
(139, '1ca6518a35111f1ef88a7c411e6f0161', 'admin', '2017-10-13 20:18:48', '2017-10-14 00:31:57', '197.159.16.2', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.10', 1),
(140, '902f6aa2808205006598edbd056a5503', 'ayoub', '2017-10-13 20:19:32', '2017-10-13 20:22:14', '160.176.106.143', 'Mozilla/5.0 (Windows NT 6.2; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100', 19),
(141, '616d8523f5b3d3bc6dad10ad539d85fb', 'ayoub', '2017-10-13 20:22:17', '2017-10-13 20:23:01', '160.176.106.143', 'Mozilla/5.0 (Windows NT 6.2; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100', 19),
(142, 'd4d182d25cbae93b5f14a3f0fda89dc5', 'fati', '2017-10-13 20:23:52', '2017-10-16 21:10:05', '41.143.26.214', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:56.0) Gecko/20100101 Firefox/56.0', 2),
(143, 'c2f881c7305f531af119f82609205699', 'ayoub', '2017-10-13 20:23:57', '2017-10-13 20:26:07', '160.176.106.143', 'Mozilla/5.0 (Windows NT 6.2; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100', 19),
(144, 'a9aa6ffaf079bbf089440a5f0e9d883c', 'ayoub', '2017-10-13 20:26:10', '2017-10-13 20:30:33', '160.176.106.143', 'Mozilla/5.0 (Windows NT 6.2; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100', 19),
(145, 'b2c0798ece62f8f0718f5ee4cae98983', 'ayoub', '2017-10-13 20:30:33', '2017-10-16 21:10:19', '160.176.106.143', 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:49.0) Gecko/20100101 Firefox/49.0', 19),
(146, 'e43204dc9cbbf1b7bb1c65bca20567fe', 'admin', '2017-10-14 00:31:57', '2017-10-15 23:11:30', '154.73.165.18', 'Mozilla/5.0 (Linux; Android 7.0; SAMSUNG SM-G928T Build/NRD90M) AppleWebKit/537.36 (KHTML, like Geck', 1),
(147, '790ed026dc6a5711ac3c93b44a3727d8', 'admin', '2017-10-15 23:11:30', '2017-10-16 00:00:32', '197.159.16.2', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 1),
(148, 'd4f8baa45563bd348a9a9c7e16e6fcff', 'admin', '2017-10-16 00:00:32', '2017-10-16 19:38:33', '197.159.16.2', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 1),
(149, 'e77b23c87fe49cecab00f99458772490', 'admin', '2017-10-16 19:38:33', '2017-10-16 21:10:37', '197.159.16.2', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 1),
(150, '8deee8d7893f205b701539713f7ae12c', 'fati', '2017-10-16 21:10:05', '2017-10-16 23:24:33', '160.178.181.223', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:56.0) Gecko/20100101 Firefox/56.0', 2),
(151, 'fafe81e4d4fc6930fd206067840c4fe1', 'kada', '2017-10-16 21:10:10', '2017-10-16 22:12:48', '197.159.16.2', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 20),
(152, '8be73d3d12acd1859c89a18f81d5d1b0', 'ayoub', '2017-10-16 21:10:19', '2017-10-16 21:34:32', '41.251.164.120', 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:49.0) Gecko/20100101 Firefox/49.0', 19),
(153, 'f8fa13a4b8b162296d5003579c9585db', 'admin', '2017-10-16 21:10:37', '2017-10-16 22:12:41', '197.159.16.2', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.10', 1),
(154, 'c730cf3db89dec330363553ec097d7f9', 'fatiadmin', '2017-10-16 21:34:57', '2017-10-16 22:42:34', '160.178.181.223', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:56.0) Gecko/20100101 Firefox/56.0', 22),
(155, '728423c66c46c35b42dfd5d4a127cebb', 'ayoubadmin', '2017-10-16 21:36:48', '2017-10-17 15:25:01', '41.251.164.120', 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:49.0) Gecko/20100101 Firefox/49.0', 23),
(156, '03f3ccdc572691bf8ac0d717f4673931', 'kada', '2017-10-16 22:13:07', '2017-10-16 23:34:08', '197.159.16.2', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 20),
(157, '4be6b6782a99c88d6d51a143cb710e48', 'admin', '2017-10-16 22:30:46', '2017-10-16 23:05:41', '197.159.16.2', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.10', 1),
(158, '56f598063cb5a5c621184e7f39b6e5f8', 'fatiadmin', '2017-10-16 22:42:34', '2017-10-16 23:39:30', '160.178.181.223', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:56.0) Gecko/20100101 Firefox/56.0', 22),
(159, 'ede5d80c97d2824cc034c25ae1dc04ab', 'admin', '2017-10-16 23:05:44', '2017-10-16 23:34:14', '197.159.16.2', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.10', 1),
(160, 'cf31a3cff2402f0f1261db26d2766f32', 'fati', '2017-10-16 23:24:44', '2017-10-16 23:33:41', '197.253.212.69', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:56.0) Gecko/20100101 Firefox/56.0', 2),
(161, 'd6942091e76ecd8a9b675f90f10e55a4', 'fati', '2017-10-16 23:33:50', '2017-10-16 23:39:07', '197.253.227.175', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:56.0) Gecko/20100101 Firefox/56.0', 2),
(162, '677ae9596b47f7c8ab58b869433752ef', 'admin', '2017-10-16 23:34:14', '2017-10-17 01:23:29', '197.159.16.2', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 1),
(163, '9ec56a62ad0f8d2dbbae66a80c450a58', 'fati', '2017-10-16 23:39:16', NULL, '197.253.212.69', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:56.0) Gecko/20100101 Firefox/56.0', 2),
(164, 'fe5de1aff8a066d507b8576a839dd082', 'fatiadmin', '2017-10-16 23:39:39', '2017-10-17 01:23:57', '197.253.212.69', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:56.0) Gecko/20100101 Firefox/56.0', 22),
(165, 'ef71d77e3e1db0a216686ed04d791bba', 'admin', '2017-10-17 01:23:40', '2017-10-17 10:54:40', '197.159.16.2', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 1),
(166, '24f9194c194f8f2410130bad8f6e8346', 'fatiadmin', '2017-10-17 01:23:57', NULL, '160.178.181.223', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:56.0) Gecko/20100101 Firefox/56.0', 22),
(167, 'd879d73594a319c2d6538acd9bd5512e', 'admin', '2017-10-17 10:54:40', '2017-10-17 13:06:18', '197.159.16.2', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.10', 1),
(168, '871e562ba03fd42a64f0ab767d5d129f', 'kada', '2017-10-17 12:22:30', '2017-10-17 12:27:32', '37.208.61.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:53.0) Gecko/20100101 Firefox/53.0', 20),
(169, 'cc6561cda1bc9577da2eb9c02d7b9d8d', 'ali', '2017-10-17 12:27:57', '2017-10-17 15:17:35', '37.208.61.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:53.0) Gecko/20100101 Firefox/53.0', 24),
(170, 'd28a271d1c555c3adb39164d2ae667f2', 'admin', '2017-10-17 13:06:18', NULL, '37.208.61.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 1),
(171, '3e5127c02bdc36d7db1ba2a31e5fae56', 'ali', '2017-10-17 15:17:56', '2017-10-17 15:26:07', '37.208.61.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:53.0) Gecko/20100101 Firefox/53.0', 24),
(172, '7d8241072df21cd7b873ecfaa614a818', 'ayoubadmin', '2017-10-17 15:25:01', '2017-10-17 19:01:33', '105.154.179.34', 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:49.0) Gecko/20100101 Firefox/49.0', 23),
(173, '01e1c51dea6c5e99531e18181e0d48cf', 'ayoubadmin', '2017-10-17 19:01:33', NULL, '105.154.179.34', 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:49.0) Gecko/20100101 Firefox/49.0', 23);

-- --------------------------------------------------------

--
-- Structure de la table `ste_bank`
--

CREATE TABLE `ste_bank` (
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

CREATE TABLE `ste_info` (
  `id` int(11) NOT NULL,
  `ste_name` varchar(45) DEFAULT NULL,
  `ste_bp` varchar(5) DEFAULT NULL,
  `ste_adresse` varchar(90) DEFAULT NULL,
  `ste_ville` varchar(23) DEFAULT 'N''Djamena',
  `ste_pays` varchar(23) DEFAULT 'Tchad',
  `ste_tel` varchar(55) DEFAULT NULL,
  `ste_fax` varchar(15) DEFAULT NULL,
  `ste_email` varchar(45) DEFAULT NULL,
  `ste_if` varchar(15) DEFAULT NULL,
  `ste_rc` varchar(15) NOT NULL,
  `ste_website` varchar(45) DEFAULT NULL,
  `updusr` varchar(25) DEFAULT NULL,
  `creusr` varchar(25) DEFAULT NULL,
  `credat` datetime DEFAULT NULL,
  `upddat` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `ste_info`
--

INSERT INTO `ste_info` (`id`, `ste_name`, `ste_bp`, `ste_adresse`, `ste_ville`, `ste_pays`, `ste_tel`, `ste_fax`, `ste_email`, `ste_if`, `ste_rc`, `ste_website`, `updusr`, `creusr`, `credat`, `upddat`) VALUES
(1, 'Global-Tech', '1656', 'Avenue Charles de Gaulle, ', 'N\'Djamena', 'Tchad', '(+235) 66324513 / 22514044', NULL, 'contact@globaltech.td', '9016442Y', 'TC-ABC-B026/014', 'www.globaltech.td', '1', NULL, NULL, '2017-10-17 13:31:27');

-- --------------------------------------------------------

--
-- Structure de la table `stock`
--

CREATE TABLE `stock` (
  `id` int(11) NOT NULL,
  `idproduit` int(11) DEFAULT NULL,
  `qte` int(11) DEFAULT NULL,
  `prix_achat` double DEFAULT NULL,
  `prix_vente` double DEFAULT NULL,
  `date_achat` date DEFAULT NULL,
  `date_validite` date DEFAULT NULL,
  `mouvement` char(1) DEFAULT NULL COMMENT 'E/S/R',
  `etat` int(11) DEFAULT '0',
  `creusr` int(11) DEFAULT NULL,
  `credat` datetime DEFAULT CURRENT_TIMESTAMP,
  `updusr` int(11) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `stock`
--

INSERT INTO `stock` (`id`, `idproduit`, `qte`, `prix_achat`, `prix_vente`, `date_achat`, `date_validite`, `mouvement`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(49, 22, 5, 150000, 200000, '2017-10-11', '2017-10-11', 'E', 0, 19, '2017-10-11 11:49:04', NULL, NULL),
(50, 22, 5, 100000, 150000, '2017-10-11', '2020-12-31', 'E', 0, 19, '2017-10-11 12:57:05', NULL, NULL),
(51, 23, 100, 850000, 1120000, '2017-10-16', '2017-10-16', 'E', 0, 1, '2017-10-16 22:38:31', NULL, NULL),
(52, 24, 5, 500000, 700000, '2017-10-17', '2017-10-17', 'E', 1, 24, '2017-10-17 14:33:25', 24, '2017-10-17 14:34:00');

-- --------------------------------------------------------

--
-- Structure de la table `sys_log`
--

CREATE TABLE `sys_log` (
  `id` int(11) NOT NULL COMMENT 'ID Systeme',
  `message` varchar(400) DEFAULT NULL COMMENT 'Message',
  `type_log` varchar(10) DEFAULT NULL COMMENT 'Type of log (insert_update_delete_login_show)',
  `table_use` varchar(30) DEFAULT NULL COMMENT 'Table of ligne',
  `idm` int(11) DEFAULT NULL COMMENT 'ID of line',
  `user_exec` varchar(25) DEFAULT NULL COMMENT 'User Execute',
  `sesid` varchar(32) DEFAULT NULL COMMENT 'ID Session',
  `datlog` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'Date loggining'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `sys_log`
--

INSERT INTO `sys_log` (`id`, `message`, `type_log`, `table_use`, `idm`, `user_exec`, `sesid`, `datlog`) VALUES
(1, 'Création utlisateur', 'Insert', 'users_sys', 19, 'admin', NULL, '2017-09-13 15:53:48'),
(2, 'Modification utlisateur', 'Update', 'users_sys', 19, 'admin', NULL, '2017-09-13 15:57:03'),
(3, 'Modification utlisateur', 'Update', 'users_sys', 18, 'admin', NULL, '2017-09-13 16:17:30'),
(4, 'Modification utlisateur', 'Update', 'users_sys', 18, 'admin', NULL, '2017-09-13 16:18:15'),
(5, 'Modification utlisateur', 'Update', 'users_sys', 18, 'admin', NULL, '2017-09-13 16:28:38'),
(6, 'Enregistrement Devis 29', 'Insert', 'devis', 29, 'admin', NULL, '2017-10-09 14:31:27'),
(7, 'Modification Détail Devis ', 'Update', 'd_devis', NULL, 'admin', NULL, '2017-10-09 14:51:13'),
(8, 'Modification Devis 27', 'Update', 'devis', 27, 'admin', NULL, '2017-10-09 14:51:22'),
(9, 'Modification Devis 27', 'Update', 'devis', 27, 'admin', NULL, '2017-10-09 14:51:52'),
(10, 'Modification Devis 27', 'Update', 'devis', 27, 'admin', NULL, '2017-10-09 14:51:59'),
(11, 'Modification Devis 27', 'Update', 'devis', 27, 'admin', NULL, '2017-10-09 14:52:11'),
(12, 'Modification Devis 27', 'Update', 'devis', 27, 'admin', NULL, '2017-10-09 14:55:36'),
(13, 'Modification Devis 27', 'Update', 'devis', 27, 'admin', NULL, '2017-10-09 14:56:47'),
(14, 'Modification Devis 27', 'Update', 'devis', 27, 'admin', NULL, '2017-10-09 14:57:22'),
(15, 'Modification Détail Devis ', 'Update', 'd_devis', NULL, 'admin', NULL, '2017-10-09 14:58:20'),
(16, 'Modification Détail Devis ', 'Update', 'd_devis', NULL, 'admin', NULL, '2017-10-09 14:59:25'),
(17, 'Modification Détail Devis ', 'Update', 'd_devis', NULL, 'admin', NULL, '2017-10-09 15:03:13'),
(18, 'Modification Détail Devis 150', 'Update', 'd_devis', 150, 'admin', NULL, '2017-10-09 15:05:38'),
(19, 'Enregistrement Détail Devis 152', 'Insert', 'd_devis', 152, 'admin', NULL, '2017-10-09 15:18:25'),
(20, 'Enregistrement Devis 30', 'Insert', 'devis', 30, 'admin', NULL, '2017-10-09 15:19:40'),
(21, 'Enregistrement Détail Devis 153', 'Insert', 'd_devis', 153, 'admin', NULL, '2017-10-09 15:21:05'),
(22, 'Enregistrement Devis 31', 'Insert', 'devis', 31, 'admin', NULL, '2017-10-09 15:21:07'),
(23, 'Enregistrement Détail Devis 154', 'Insert', 'd_devis', 154, 'admin', NULL, '2017-10-09 15:23:11'),
(24, 'Enregistrement Devis 32', 'Insert', 'devis', 32, 'admin', NULL, '2017-10-09 15:23:14'),
(25, 'Validation client #Devis:', 'Update', 'devis', 23, 'admin', NULL, '2017-10-09 19:26:53'),
(26, 'Validation client #Devis:', 'Update', 'devis', 21, 'admin', NULL, '2017-10-09 19:42:23'),
(27, 'Validation client #Devis:', 'Update', 'devis', 12, 'admin', NULL, '2017-10-09 19:44:32'),
(28, 'Validation client #Devis:', 'Update', 'devis', 11, 'admin', NULL, '2017-10-09 19:47:07'),
(29, 'Validation client #Devis:', 'Update', 'devis', 11, 'admin', NULL, '2017-10-09 19:47:38'),
(30, 'Validation client #Devis:', 'Update', 'devis', 30, 'admin', NULL, '2017-10-09 19:48:52'),
(31, 'Validation client #Devis:', 'Update', 'devis', 30, 'admin', NULL, '2017-10-09 19:51:07'),
(32, 'Validation client #Devis:', 'Update', 'devis', 30, 'admin', NULL, '2017-10-09 19:52:29'),
(33, 'Refus devis par le client #Devis:', 'Update', 'devis', 24, 'admin', NULL, '2017-10-09 20:08:39'),
(34, 'Demande modification devis #Devis:', 'Update', 'devis', 11, 'admin', NULL, '2017-10-10 01:28:05'),
(35, 'Modification unité de vente', 'Update', 'ref_unites_vente', 2, 'admin', NULL, '2017-10-10 02:16:48'),
(36, 'Validation unité de vente', 'Validate', 'ref_unites_vente', 2, 'admin', NULL, '2017-10-10 02:16:55'),
(37, 'Insertion produit', 'Insert', 'produits', 21, 'admin', NULL, '2017-10-10 02:19:47'),
(38, 'Validation produit', 'Validate', 'produits', 21, 'admin', NULL, '2017-10-10 02:19:57'),
(39, 'Enregistrement Détail Devis 155', 'Insert', 'd_devis', 155, 'admin', NULL, '2017-10-10 02:30:46'),
(40, 'Enregistrement Devis 33', 'Insert', 'devis', 33, 'admin', NULL, '2017-10-10 02:31:34'),
(41, 'Validation client #Devis:', 'Update', 'devis', 33, 'admin', NULL, '2017-10-10 02:34:44'),
(42, 'Insertion contrat abonnement', 'Insert', 'contrats', 30, 'admin', NULL, '2017-10-10 02:40:41'),
(43, 'Validation contrat abonnement', 'Validate', 'contrats', 30, 'admin', NULL, '2017-10-10 02:41:07'),
(44, 'Insertion contrat abonnement', 'Insert', 'contrats', 31, 'admin', NULL, '2017-10-10 02:48:07'),
(45, 'Validation contrat abonnement', 'Validate', 'contrats', 30, 'admin', NULL, '2017-10-10 02:48:08'),
(46, 'Enregistrement Détail Devis 156', 'Insert', 'd_devis', 156, 'admin', NULL, '2017-10-10 10:49:19'),
(47, 'Modification Devis 23', 'Update', 'devis', 23, 'admin', NULL, '2017-10-10 10:52:33'),
(48, 'Demande modification devis #Devis:', 'Update', 'devis', 12, 'admin', NULL, '2017-10-10 11:30:12'),
(49, 'Demande modification devis #Devis:', 'Update', 'devis', 11, 'admin', NULL, '2017-10-10 11:41:43'),
(50, 'Validation client #Devis:', 'Update', 'devis', 24, 'admin', NULL, '2017-10-10 11:43:27'),
(51, 'Enregistrement Détail Devis 157', 'Insert', 'd_devis', 157, 'admin', NULL, '2017-10-10 12:02:06'),
(52, 'Enregistrement Devis 34', 'Insert', 'devis', 34, 'admin', NULL, '2017-10-10 12:02:31'),
(53, 'Enregistrement Détail Devis 158', 'Insert', 'd_devis', 158, 'admin', NULL, '2017-10-10 12:03:14'),
(54, 'Modification Devis 34', 'Update', 'devis', 34, 'admin', NULL, '2017-10-10 12:07:29'),
(55, 'Création utlisateur', 'Insert', 'users_sys', 19, 'admin', NULL, '2017-10-11 00:36:55'),
(56, 'Modification utlisateur', 'Update', 'users_sys', 19, 'admin', NULL, '2017-10-11 00:45:38'),
(57, 'Modification utlisateur', 'Update', 'users_sys', 19, 'admin', NULL, '2017-10-11 00:47:17'),
(58, 'Création utlisateur', 'Insert', 'users_sys', 20, 'admin', NULL, '2017-10-11 00:55:09'),
(59, 'Validation unité de vente', 'Validate', 'ref_unites_vente', 2, 'ayoub', NULL, '2017-10-11 01:31:33'),
(60, 'Insertion catégorie produit', 'Insert', 'ref_categories_produits', 8, 'ayoub', NULL, '2017-10-11 10:25:40'),
(61, 'Insertion catégorie produit', 'Insert', 'ref_categories_produits', 9, 'ayoub', NULL, '2017-10-11 10:25:54'),
(62, 'Insertion catégorie produit', 'Insert', 'ref_categories_produits', 10, 'ayoub', NULL, '2017-10-11 10:26:26'),
(63, 'Insertion catégorie produit', 'Insert', 'ref_categories_produits', 11, 'ayoub', NULL, '2017-10-11 10:32:38'),
(64, 'Insertion unité de vente', 'Insert', 'ref_unites_vente', 3, 'ayoub', NULL, '2017-10-11 10:33:51'),
(65, 'Insertion unité de vente', 'Insert', 'ref_unites_vente', 4, 'ayoub', NULL, '2017-10-11 10:34:08'),
(66, 'Insertion unité de vente', 'Insert', 'ref_unites_vente', 5, 'ayoub', NULL, '2017-10-11 10:37:09'),
(67, 'Insertion unité de vente', 'Insert', 'ref_unites_vente', 6, 'ayoub', NULL, '2017-10-11 10:44:26'),
(68, 'Validation catégorie produit', 'Validate', 'ref_categories_produits', 8, 'ayoub', NULL, '2017-10-11 10:44:56'),
(69, 'Validation catégorie produit', 'Validate', 'ref_categories_produits', 9, 'ayoub', NULL, '2017-10-11 10:45:01'),
(70, 'Validation catégorie produit', 'Validate', 'ref_categories_produits', 10, 'ayoub', NULL, '2017-10-11 10:45:18'),
(71, 'Validation catégorie produit', 'Validate', 'ref_categories_produits', 11, 'ayoub', NULL, '2017-10-11 10:45:22'),
(72, 'Validation unité de vente', 'Validate', 'ref_unites_vente', 2, 'ayoub', NULL, '2017-10-11 10:45:39'),
(73, 'Validation unité de vente', 'Validate', 'ref_unites_vente', 3, 'ayoub', NULL, '2017-10-11 10:45:45'),
(74, 'Validation unité de vente', 'Validate', 'ref_unites_vente', 4, 'ayoub', NULL, '2017-10-11 10:45:49'),
(75, 'Validation unité de vente', 'Validate', 'ref_unites_vente', 6, 'ayoub', NULL, '2017-10-11 10:45:56'),
(76, 'Validation unité de vente', 'Validate', 'ref_unites_vente', 5, 'ayoub', NULL, '2017-10-11 10:46:01'),
(77, 'Insertion produit', 'Insert', 'produits', 22, 'ayoub', NULL, '2017-10-11 10:47:16'),
(78, 'Insertion produit', 'Insert', 'produits', 23, 'ayoub', NULL, '2017-10-11 10:47:51'),
(79, 'Insertion produit', 'Insert', 'produits', 24, 'ayoub', NULL, '2017-10-11 10:48:50'),
(80, 'Insertion produit', 'Insert', 'produits', 25, 'ayoub', NULL, '2017-10-11 10:59:12'),
(81, 'Modification produit', 'Update', 'produits', 25, 'ayoub', NULL, '2017-10-11 10:59:32'),
(82, 'Insertion catégorie produit', 'Insert', 'ref_categories_produits', 12, 'ayoub', NULL, '2017-10-11 11:00:19'),
(83, 'Validation catégorie produit', 'Validate', 'ref_categories_produits', 12, 'ayoub', NULL, '2017-10-11 11:00:27'),
(84, 'Insertion unité de vente', 'Insert', 'ref_unites_vente', 7, 'ayoub', NULL, '2017-10-11 11:01:28'),
(85, 'Validation unité de vente', 'Validate', 'ref_unites_vente', 7, 'ayoub', NULL, '2017-10-11 11:02:03'),
(86, 'Insertion produit', 'Insert', 'produits', 26, 'ayoub', NULL, '2017-10-11 11:02:47'),
(87, 'Création client', 'Insert', 'clients', 18, 'ayoub', NULL, '2017-10-11 11:17:59'),
(88, 'Validation produit', 'Validate', 'produits', 26, 'ayoub', NULL, '2017-10-11 11:32:59'),
(89, 'Validation produit', 'Validate', 'produits', 25, 'ayoub', NULL, '2017-10-11 11:33:21'),
(90, 'Validation client', 'Validate', 'clients', 18, 'ayoub', NULL, '2017-10-11 11:43:13'),
(91, 'Insertion achat produit', 'Insert', 'stock', 49, 'ayoub', NULL, '2017-10-11 11:49:04'),
(92, 'Validation client', 'Validate', 'clients', 22, 'ayoub', NULL, '2017-10-11 12:27:19'),
(93, 'Validation client', 'Validate', 'clients', 23, 'ayoub', NULL, '2017-10-11 12:27:24'),
(94, 'Création client', 'Insert', 'clients', 24, 'ayoub', NULL, '2017-10-11 12:36:07'),
(95, 'Création client', 'Insert', 'clients', 25, 'ayoub', NULL, '2017-10-11 12:50:57'),
(96, 'Création client', 'Insert', 'clients', 26, 'ayoub', NULL, '2017-10-11 12:53:14'),
(97, 'Modification client', 'Update', 'clients', 24, 'ayoub', NULL, '2017-10-11 12:54:05'),
(98, 'Validation client', 'Validate', 'clients', 24, 'ayoub', NULL, '2017-10-11 12:54:19'),
(99, 'Validation client', 'Validate', 'clients', 25, 'ayoub', NULL, '2017-10-11 12:54:24'),
(100, 'Validation client', 'Validate', 'clients', 26, 'ayoub', NULL, '2017-10-11 12:54:28'),
(101, 'Insertion achat produit', 'Insert', 'stock', 50, 'ayoub', NULL, '2017-10-11 12:57:05'),
(102, 'Validation produit', 'Validate', 'produits', 22, 'ayoub', NULL, '2017-10-11 12:57:31'),
(103, 'Validation produit', 'Validate', 'produits', 23, 'ayoub', NULL, '2017-10-11 12:57:36'),
(104, 'Validation produit', 'Validate', 'produits', 24, 'ayoub', NULL, '2017-10-11 12:57:45'),
(105, 'Validation client', 'Validate', 'clients', 24, 'ayoub', NULL, '2017-10-11 13:04:49'),
(106, 'Modification client', 'Update', 'clients', 24, 'ayoub', NULL, '2017-10-11 13:05:02'),
(107, 'Validation client', 'Validate', 'clients', 24, 'ayoub', NULL, '2017-10-11 13:05:11'),
(108, 'Enregistrement Détail Devis 159', 'Insert', 'd_devis', 159, 'admin', '6258e525a08103289474e137703fd29f', '2017-10-13 17:10:28'),
(109, 'Enregistrement Devis 37', 'Insert', 'devis', 37, 'admin', '6258e525a08103289474e137703fd29f', '2017-10-13 17:10:35'),
(110, 'Validation fournisseur', 'Validate', 'fournisseurs', 30, 'admin', '41ac501ac8df2dca5605b301795d77cf', '2017-10-13 18:14:00'),
(111, 'Validation fournisseur', 'Validate', 'fournisseurs', 29, 'admin', '41ac501ac8df2dca5605b301795d77cf', '2017-10-13 18:14:05'),
(112, 'Suppression fournisseur', 'Delete', 'fournisseurs', 30, 'admin', '41ac501ac8df2dca5605b301795d77cf', '2017-10-13 18:14:10'),
(113, 'Suppression fournisseur', 'Delete', 'fournisseurs', 29, 'admin', '41ac501ac8df2dca5605b301795d77cf', '2017-10-13 18:14:16'),
(114, 'Modification utlisateur', 'Update', 'users_sys', 2, 'admin', '1f9f6e181db8892c33bea7d95af80be8', '2017-10-13 20:10:32'),
(115, 'Modification utlisateur', 'Update', 'users_sys', 19, 'admin', '1f9f6e181db8892c33bea7d95af80be8', '2017-10-13 20:11:09'),
(116, 'Modification utlisateur', 'Update', 'users_sys', 19, 'admin', '1f9f6e181db8892c33bea7d95af80be8', '2017-10-13 20:13:19'),
(117, 'Modification utlisateur', 'Update', 'users_sys', 2, 'admin', '1ca6518a35111f1ef88a7c411e6f0161', '2017-10-13 20:22:52'),
(118, 'Modification utlisateur', 'Update', 'users_sys', 2, 'admin', '1ca6518a35111f1ef88a7c411e6f0161', '2017-10-13 20:23:42'),
(119, 'Enregistrement proforma ', 'Insert', 'proforma', NULL, 'admin', '790ed026dc6a5711ac3c93b44a3727d8', '2017-10-15 23:12:36'),
(120, 'Modification Détail proforma 27', 'Update', NULL, NULL, 'admin', '790ed026dc6a5711ac3c93b44a3727d8', '2017-10-15 23:14:23'),
(121, 'Modification proforma 27', 'Update', 'proforma', 27, 'admin', '790ed026dc6a5711ac3c93b44a3727d8', '2017-10-15 23:14:38'),
(122, 'Enregistrement Détail Devis 160', 'Insert', 'd_devis', 160, 'admin', 'd4f8baa45563bd348a9a9c7e16e6fcff', '2017-10-16 00:05:17'),
(123, 'Enregistrement proforma ', 'Insert', 'proforma', NULL, 'admin', 'e77b23c87fe49cecab00f99458772490', '2017-10-16 19:39:23'),
(124, 'Suppression proforma 27', 'Delete', 'proforma', 27, 'admin', 'e77b23c87fe49cecab00f99458772490', '2017-10-16 19:41:31'),
(125, 'Enregistrement proforma ', 'Insert', 'proforma', NULL, 'admin', 'e77b23c87fe49cecab00f99458772490', '2017-10-16 19:42:15'),
(126, 'Validation produit', 'Validate', 'produits', 22, 'fati', '8deee8d7893f205b701539713f7ae12c', '2017-10-16 21:30:59'),
(127, 'Création utlisateur', 'Insert', 'users_sys', 22, 'kada', 'fafe81e4d4fc6930fd206067840c4fe1', '2017-10-16 21:34:25'),
(128, 'Création utlisateur', 'Insert', 'users_sys', 23, 'kada', 'fafe81e4d4fc6930fd206067840c4fe1', '2017-10-16 21:36:27'),
(129, 'Insertion produit', 'Insert', 'produits', 27, 'fati', '8deee8d7893f205b701539713f7ae12c', '2017-10-16 21:57:07'),
(130, 'Modification produit', 'Update', 'produits', 27, 'fati', '8deee8d7893f205b701539713f7ae12c', '2017-10-16 21:57:24'),
(131, 'Validation produit', 'Validate', 'produits', 27, 'fati', '8deee8d7893f205b701539713f7ae12c', '2017-10-16 21:57:30'),
(132, 'Validation produit', 'Validate', 'produits', 27, 'fati', '8deee8d7893f205b701539713f7ae12c', '2017-10-16 21:57:44'),
(133, 'Suppression produit', 'Delete', 'produits', 27, 'fati', '8deee8d7893f205b701539713f7ae12c', '2017-10-16 21:57:50'),
(134, 'Enregistrement proforma 30', 'Insert', 'proforma', NULL, 'ayoubadmin', '728423c66c46c35b42dfd5d4a127cebb', '2017-10-16 22:22:11'),
(135, 'Enregistrement Détail Devis 161', 'Insert', 'd_devis', 161, 'kada', '03f3ccdc572691bf8ac0d717f4673931', '2017-10-16 22:22:24'),
(136, 'Enregistrement Devis 38', 'Insert', 'devis', 38, 'kada', '03f3ccdc572691bf8ac0d717f4673931', '2017-10-16 22:22:29'),
(137, 'Enregistrement proforma 31', 'Insert', 'proforma', NULL, 'ayoubadmin', '728423c66c46c35b42dfd5d4a127cebb', '2017-10-16 22:22:44'),
(138, 'Enregistrement Détail Devis 162', 'Insert', 'd_devis', 162, 'kada', '03f3ccdc572691bf8ac0d717f4673931', '2017-10-16 22:23:01'),
(139, 'Enregistrement Devis 39', 'Insert', 'devis', 39, 'kada', '03f3ccdc572691bf8ac0d717f4673931', '2017-10-16 22:23:07'),
(140, 'Suppression proforma 30', 'Delete', 'proforma', 30, 'ayoubadmin', '728423c66c46c35b42dfd5d4a127cebb', '2017-10-16 22:25:53'),
(141, 'Enregistrement proforma 32', 'Insert', 'proforma', NULL, 'ayoubadmin', '728423c66c46c35b42dfd5d4a127cebb', '2017-10-16 22:26:25'),
(142, 'Insertion achat produit', 'Insert', 'stock', 51, 'admin', '4be6b6782a99c88d6d51a143cb710e48', '2017-10-16 22:38:31'),
(143, 'Validation PROFORMA:31', 'Update', 'proforma', 31, 'ayoubadmin', '728423c66c46c35b42dfd5d4a127cebb', '2017-10-16 22:43:24'),
(144, 'Modification proforma 32', 'Update', 'proforma', 32, 'fati', '8deee8d7893f205b701539713f7ae12c', '2017-10-16 22:45:13'),
(145, 'Expédition proforma 31', 'Update', 'proforma', NULL, 'fatiadmin', '56f598063cb5a5c621184e7f39b6e5f8', '2017-10-16 22:54:24'),
(146, 'Validation PROFORMA:32', 'Update', 'proforma', 32, 'fatiadmin', '56f598063cb5a5c621184e7f39b6e5f8', '2017-10-16 22:58:10'),
(147, 'Enregistrement Détail Devis 163', 'Insert', 'd_devis', 163, 'fati', '8deee8d7893f205b701539713f7ae12c', '2017-10-16 23:06:21'),
(148, 'Expédition proforma 32', 'Update', 'proforma', NULL, 'admin', 'ede5d80c97d2824cc034c25ae1dc04ab', '2017-10-16 23:06:23'),
(149, 'Validation produit', 'Validate', 'produits', 22, 'fatiadmin', '56f598063cb5a5c621184e7f39b6e5f8', '2017-10-16 23:09:25'),
(150, 'Enregistrement Détail Devis 164', 'Insert', 'd_devis', 164, 'fati', '8deee8d7893f205b701539713f7ae12c', '2017-10-16 23:10:11'),
(151, 'Enregistrement Devis 40', 'Insert', 'devis', 40, 'fati', '8deee8d7893f205b701539713f7ae12c', '2017-10-16 23:10:34'),
(152, 'Enregistrement proforma 33', 'Insert', 'proforma', NULL, 'fati', '8deee8d7893f205b701539713f7ae12c', '2017-10-16 23:14:54'),
(153, 'Suppression proforma 33', 'Delete', 'proforma', 33, 'fati', '8deee8d7893f205b701539713f7ae12c', '2017-10-16 23:15:14'),
(154, 'Modification Devis 40', 'Update', 'devis', 40, 'fati', '8deee8d7893f205b701539713f7ae12c', '2017-10-16 23:20:02'),
(155, 'Modification Devis 40', 'Update', 'devis', 40, 'fati', '8deee8d7893f205b701539713f7ae12c', '2017-10-16 23:23:22'),
(156, 'Validation devis 38', 'Update', 'devis', 38, 'ayoubadmin', '728423c66c46c35b42dfd5d4a127cebb', '2017-10-16 23:23:45'),
(157, 'Validation devis 40', 'Update', 'devis', 40, 'ayoubadmin', '728423c66c46c35b42dfd5d4a127cebb', '2017-10-16 23:24:50'),
(158, 'Validation devis 39', 'Update', 'devis', 39, 'ayoubadmin', '728423c66c46c35b42dfd5d4a127cebb', '2017-10-16 23:24:55'),
(159, 'Expédition devis 38', 'Update', 'devis', 38, 'ayoubadmin', '728423c66c46c35b42dfd5d4a127cebb', '2017-10-16 23:36:40'),
(160, 'Demande modification devis #Devis:38', 'Update', 'devis', 38, 'admin', '677ae9596b47f7c8ab58b869433752ef', '2017-10-16 23:37:05'),
(161, 'Débloquer devis 38', 'Update', 'devis', 38, 'ayoubadmin', '728423c66c46c35b42dfd5d4a127cebb', '2017-10-16 23:37:25'),
(162, 'Modification Détail Devis 38', 'Update', NULL, NULL, 'ayoubadmin', '728423c66c46c35b42dfd5d4a127cebb', '2017-10-16 23:38:03'),
(163, 'Modification Devis 38', 'Update', 'devis', 38, 'ayoubadmin', '728423c66c46c35b42dfd5d4a127cebb', '2017-10-16 23:38:07'),
(164, 'Validation devis 38', 'Update', 'devis', 38, 'ayoubadmin', '728423c66c46c35b42dfd5d4a127cebb', '2017-10-16 23:38:18'),
(165, 'Expédition devis 38', 'Update', 'devis', 38, 'ayoubadmin', '728423c66c46c35b42dfd5d4a127cebb', '2017-10-16 23:38:26'),
(166, 'Validation client #Devis:38', 'Update', 'devis', 38, 'admin', '677ae9596b47f7c8ab58b869433752ef', '2017-10-16 23:38:53'),
(167, 'Enregistrement Détail Devis 165', 'Insert', 'd_devis', 165, 'fati', '9ec56a62ad0f8d2dbbae66a80c450a58', '2017-10-16 23:44:46'),
(168, 'Enregistrement Détail Devis 166', 'Insert', 'd_devis', 166, 'fati', '9ec56a62ad0f8d2dbbae66a80c450a58', '2017-10-16 23:46:57'),
(169, 'Expédition devis 39', 'Update', 'devis', 39, 'admin', '677ae9596b47f7c8ab58b869433752ef', '2017-10-16 23:49:34'),
(170, 'Expédition devis 40', 'Update', 'devis', 40, 'admin', '677ae9596b47f7c8ab58b869433752ef', '2017-10-16 23:49:44'),
(171, 'Refus devis par le client #Devis:39', 'Update', 'devis', 39, 'admin', '677ae9596b47f7c8ab58b869433752ef', '2017-10-16 23:49:54'),
(172, 'Insertion contrat abonnement', 'Insert', 'contrats', 32, 'admin', '677ae9596b47f7c8ab58b869433752ef', '2017-10-17 00:14:14'),
(173, 'Modification contrat abonnement', 'Update', 'contrats', 32, 'admin', 'ef71d77e3e1db0a216686ed04d791bba', '2017-10-17 01:24:40'),
(174, 'Validation contrat abonnement', 'Validate', 'contrats', 32, 'admin', 'ef71d77e3e1db0a216686ed04d791bba', '2017-10-17 01:24:58'),
(175, 'Validation client #Devis:40', 'Update', 'devis', 40, 'fatiadmin', '24f9194c194f8f2410130bad8f6e8346', '2017-10-17 01:31:05'),
(176, 'Insertion contrat abonnement', 'Insert', 'contrats', 33, 'fatiadmin', '24f9194c194f8f2410130bad8f6e8346', '2017-10-17 01:31:54'),
(177, 'Validation contrat abonnement', 'Validate', 'contrats', 33, 'fatiadmin', '24f9194c194f8f2410130bad8f6e8346', '2017-10-17 01:32:00'),
(178, 'Enregistrement Détail Devis 167', 'Insert', 'd_devis', 167, 'admin', 'ef71d77e3e1db0a216686ed04d791bba', '2017-10-17 01:51:53'),
(179, 'Enregistrement Devis 41', 'Insert', 'devis', 41, 'admin', 'ef71d77e3e1db0a216686ed04d791bba', '2017-10-17 01:52:04'),
(180, 'Validation devis 41', 'Update', 'devis', 41, 'admin', 'ef71d77e3e1db0a216686ed04d791bba', '2017-10-17 01:52:11'),
(181, 'Expédition devis 41', 'Update', 'devis', 41, 'admin', 'ef71d77e3e1db0a216686ed04d791bba', '2017-10-17 01:52:18'),
(182, 'Validation client #Devis:41', 'Update', 'devis', 41, 'admin', 'ef71d77e3e1db0a216686ed04d791bba', '2017-10-17 01:52:35'),
(183, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 37, 'admin', 'ef71d77e3e1db0a216686ed04d791bba', '2017-10-17 01:54:25'),
(184, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 38, 'admin', 'ef71d77e3e1db0a216686ed04d791bba', '2017-10-17 01:56:30'),
(185, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 39, 'admin', 'ef71d77e3e1db0a216686ed04d791bba', '2017-10-17 01:57:47'),
(186, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 40, 'admin', 'ef71d77e3e1db0a216686ed04d791bba', '2017-10-17 01:59:02'),
(187, 'Suppression échéance contrat abonnement', 'Delete', 'echeances_contrat', NULL, 'admin', 'ef71d77e3e1db0a216686ed04d791bba', '2017-10-17 01:59:11'),
(188, 'Suppression échéance contrat abonnement', 'Delete', 'echeances_contrat', NULL, 'admin', 'ef71d77e3e1db0a216686ed04d791bba', '2017-10-17 01:59:17'),
(189, 'Insertion contrat abonnement', 'Insert', 'contrats', 34, 'admin', 'ef71d77e3e1db0a216686ed04d791bba', '2017-10-17 01:59:26'),
(190, 'Validation contrat abonnement', 'Validate', 'contrats', 34, 'admin', 'ef71d77e3e1db0a216686ed04d791bba', '2017-10-17 01:59:38'),
(191, 'Modification contrat abonnement', 'Update', 'contrats', 34, 'fatiadmin', '24f9194c194f8f2410130bad8f6e8346', '2017-10-17 02:05:06'),
(192, 'Validation contrat abonnement', 'Validate', 'contrats', 34, 'admin', 'ef71d77e3e1db0a216686ed04d791bba', '2017-10-17 02:06:56'),
(193, 'Création utlisateur', 'Insert', 'users_sys', 24, 'kada', '871e562ba03fd42a64f0ab767d5d129f', '2017-10-17 12:24:54'),
(194, 'Création client', 'Insert', 'clients', 27, 'ali', 'cc6561cda1bc9577da2eb9c02d7b9d8d', '2017-10-17 12:33:37'),
(195, 'Validation client', 'Validate', 'clients', 27, 'ali', 'cc6561cda1bc9577da2eb9c02d7b9d8d', '2017-10-17 12:39:46'),
(196, 'Création catégorie client', 'Insert', 'categorie_client', 2, 'ali', 'cc6561cda1bc9577da2eb9c02d7b9d8d', '2017-10-17 12:41:21'),
(197, 'Validation catégorie client', 'Validate', 'categorie_client', 2, 'ali', 'cc6561cda1bc9577da2eb9c02d7b9d8d', '2017-10-17 12:43:54'),
(198, 'Validation catégorie produit', 'Validate', 'ref_categories_produits', 8, 'ali', 'cc6561cda1bc9577da2eb9c02d7b9d8d', '2017-10-17 12:47:51'),
(199, 'Validation catégorie produit', 'Validate', 'ref_categories_produits', 8, 'ali', 'cc6561cda1bc9577da2eb9c02d7b9d8d', '2017-10-17 12:48:13'),
(200, 'Insertion catégorie produit', 'Insert', 'ref_categories_produits', 13, 'ali', 'cc6561cda1bc9577da2eb9c02d7b9d8d', '2017-10-17 12:48:52'),
(201, 'Validation catégorie produit', 'Validate', 'ref_categories_produits', 13, 'ali', 'cc6561cda1bc9577da2eb9c02d7b9d8d', '2017-10-17 12:49:08'),
(202, 'Insertion produit', 'Insert', 'produits', 28, 'ali', 'cc6561cda1bc9577da2eb9c02d7b9d8d', '2017-10-17 12:53:55'),
(203, 'Validation produit', 'Validate', 'produits', 28, 'ali', 'cc6561cda1bc9577da2eb9c02d7b9d8d', '2017-10-17 12:55:57'),
(204, 'Enregistrement Détail Devis 168', 'Insert', 'd_devis', 168, 'ali', 'cc6561cda1bc9577da2eb9c02d7b9d8d', '2017-10-17 13:13:40'),
(205, 'Enregistrement Devis 42', 'Insert', 'devis', 42, 'ali', 'cc6561cda1bc9577da2eb9c02d7b9d8d', '2017-10-17 13:20:05'),
(206, 'Validation devis 42', 'Update', 'devis', 42, 'ali', 'cc6561cda1bc9577da2eb9c02d7b9d8d', '2017-10-17 13:20:40'),
(207, 'Expédition devis 42', 'Update', 'devis', 42, 'ali', 'cc6561cda1bc9577da2eb9c02d7b9d8d', '2017-10-17 13:33:35'),
(208, 'Demande modification devis #Devis:42', 'Update', 'devis', 42, 'ali', 'cc6561cda1bc9577da2eb9c02d7b9d8d', '2017-10-17 13:38:37'),
(209, 'Débloquer devis 42', 'Update', 'devis', 42, 'ali', 'cc6561cda1bc9577da2eb9c02d7b9d8d', '2017-10-17 13:39:19'),
(210, 'Validation devis 42', 'Update', 'devis', 42, 'ali', 'cc6561cda1bc9577da2eb9c02d7b9d8d', '2017-10-17 13:41:05'),
(211, 'Expédition devis 42', 'Update', 'devis', 42, 'ali', 'cc6561cda1bc9577da2eb9c02d7b9d8d', '2017-10-17 13:41:16'),
(212, 'Validation client #Devis:42', 'Update', 'devis', 42, 'ali', 'cc6561cda1bc9577da2eb9c02d7b9d8d', '2017-10-17 13:45:33'),
(213, 'Insertion contrat abonnement', 'Insert', 'contrats', 35, 'ali', 'cc6561cda1bc9577da2eb9c02d7b9d8d', '2017-10-17 13:53:23'),
(214, 'Validation contrat abonnement', 'Validate', 'contrats', 35, 'ali', 'cc6561cda1bc9577da2eb9c02d7b9d8d', '2017-10-17 13:54:39'),
(215, 'Insertion achat produit', 'Insert', 'stock', 52, 'ali', 'cc6561cda1bc9577da2eb9c02d7b9d8d', '2017-10-17 14:33:25'),
(216, 'Validation achat produit', 'Validate', 'stock', 52, 'ali', 'cc6561cda1bc9577da2eb9c02d7b9d8d', '2017-10-17 14:34:00'),
(217, 'Enregistrement proforma 34', 'Insert', 'proforma', NULL, 'ali', 'cc6561cda1bc9577da2eb9c02d7b9d8d', '2017-10-17 14:37:33'),
(218, 'Validation PROFORMA:34', 'Update', 'proforma', 34, 'ali', 'cc6561cda1bc9577da2eb9c02d7b9d8d', '2017-10-17 14:37:48');

-- --------------------------------------------------------

--
-- Structure de la table `sys_notifier`
--

CREATE TABLE `sys_notifier` (
  `id` int(11) NOT NULL COMMENT 'Id line',
  `app` varchar(25) NOT NULL COMMENT 'app task',
  `table` varchar(25) DEFAULT NULL COMMENT 'table of app'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table des notification app';

--
-- Déchargement des données de la table `sys_notifier`
--

INSERT INTO `sys_notifier` (`id`, `app`, `table`) VALUES
(9, 'clients', 'clients'),
(14, 'fournisseurs', 'fournisseurs'),
(16, 'devis', 'devis'),
(17, 'contrats', 'contrats'),
(18, 'contrats_fournisseurs', 'contrats_frn'),
(19, 'factures', 'factures'),
(20, 'produits', 'produits'),
(21, 'proforma', 'proforma');

-- --------------------------------------------------------

--
-- Structure de la table `sys_setting`
--

CREATE TABLE `sys_setting` (
  `id` int(11) NOT NULL COMMENT 'identifiant de ligne',
  `key` varchar(30) DEFAULT '242' COMMENT 'clé paramètre',
  `value` varchar(200) CHARACTER SET latin1 NOT NULL COMMENT 'Valeur Paramètre',
  `comment` varchar(250) DEFAULT NULL COMMENT 'Description',
  `modul` int(11) NOT NULL COMMENT 'Module qui utilise paramètre',
  `etat` int(2) NOT NULL DEFAULT '1' COMMENT 'l''etat de la ligne',
  `creusr` varchar(60) NOT NULL COMMENT 'Crée par',
  `credat` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date de création',
  `updusr` varchar(60) DEFAULT NULL COMMENT 'Derniere modif par',
  `upddat` datetime DEFAULT NULL COMMENT 'Date derniere modif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `sys_setting`
--

INSERT INTO `sys_setting` (`id`, `key`, `value`, `comment`, `modul`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(2, 'test', 'array(\'1\'=>\'val1\', \'2\'=>\'val2\')', 'description test', 106, 1, '1', '2017-10-04 15:00:41', '1', '2017-10-04 15:10:40'),
(3, 'par2', '{\"1\":\"val1\", \"2\":\"val2\"}', 'Test array', 104, 1, '1', '2017-10-04 15:33:40', '1', '2017-10-04 15:51:42'),
(4, 'etat_valid_devis', '2', 'L\'etat où le devis est validé pour exploitation dans le contrat', 105, 1, '1', '2017-10-06 15:01:26', NULL, NULL),
(5, 'abr_ste', 'GT', 'Abréviation  du nom Ste pour les Références documents', 5, 1, '1', '2017-10-09 15:22:50', NULL, NULL),
(6, 'send_mail_devis', 'false', 'Envoi devis par email ', 115, 1, '1', '2017-10-10 11:33:06', '1', '2017-10-10 11:40:47'),
(7, 'etat_valid_devis', '3', 'L\'etat où le devis est validé pour exploitation dans le contrat', 115, 1, '1', '2017-10-10 11:44:31', NULL, NULL),
(8, 'etat_devis', '{\"creat_devis\":\"0\", \"valid_devis\":\"1\",  \"send_devis\":\"2\", \"modif_client\": \"3\", \"valid_client\":\"4\", \"refus_client\":\"5\", \"devis_expir\":\"6\", \"devis_archive\":\"7\"}', 'Les différents etat de WF devis', 115, 1, '1', '2017-10-12 12:33:14', '1', '2017-10-12 14:26:11'),
(9, 'etat_proforma', '{\"creat_proforma\":\"0\", \"valid_proforma\":\"1\",  \"send_proforma\":\"2\", \"proforma_expir\":\"3\", \"proforma_archive\":\"4\"}', 'Les différents etat de WF Proforma', 127, 1, '1', '2017-10-16 00:01:26', NULL, NULL),
(10, 'etat_devis', '{\"0\":\"creat_devis\",\" 1\":\"valid_devis\", \"2\":\"send_devis\", \"3\":\"reponse_client\", \"4\":\"valid_client\", \"5\":\"refus_client\",\" 6\":\"devis_expir\", \"7\":\"devis_archive\"}', 'Les différents etat de WF devis', 121, 1, '1', '2017-10-16 00:01:56', NULL, NULL),
(11, 'send_mail_devis', 'false', 'Envoi devis par email', 121, 1, '1', '2017-10-16 00:02:40', NULL, NULL),
(12, 'tva', '18', 'Valeur TVA', 93, 1, '1', '2017-10-17 01:38:52', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `task`
--

CREATE TABLE `task` (
  `id` int(11) NOT NULL COMMENT 'ID Sys',
  `app` varchar(25) NOT NULL COMMENT 'App Name',
  `modul` int(40) DEFAULT NULL COMMENT 'Module id ',
  `file` varchar(30) CHARACTER SET latin1 NOT NULL COMMENT 'File of App',
  `rep` varchar(60) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Folder of App',
  `session` int(11) NOT NULL COMMENT 'Need session =1 else 0',
  `dscrip` varchar(50) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Description',
  `sbclass` varchar(25) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Class icon',
  `ajax` int(11) DEFAULT '0' COMMENT 'is Ajax App',
  `app_sys` int(11) NOT NULL DEFAULT '0' COMMENT 'Application Système',
  `etat` int(11) DEFAULT '0' COMMENT 'Etat de ligne',
  `type_view` varchar(10) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Type affichage Application',
  `services` varchar(20) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Les Services'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table Task of modules';

--
-- Déchargement des données de la table `task`
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
(34, 'services', 3, 'services', 'users/settings/services', 1, 'Services', 'briefcase', 1, 0, 0, 'list', '[-1-]'),
(35, 'addservices', 3, 'addservices', 'users/settings/services', 1, 'Ajouter Service', NULL, 1, 0, 0, 'form', '[-1-2-]'),
(36, 'editservices', 3, 'editservices', 'users/settings/services', 1, 'Modifier Service', NULL, 1, 0, 0, 'form', '[-1-2-]'),
(37, 'validservices', 3, 'validservices', 'users/settings/services', 1, 'Valider Service', 'check', 1, 0, 0, 'exec', '[-1-]'),
(38, 'deleteservices', 3, 'deleteservices', 'users/settings/services', 1, 'Supprimer Service', NULL, 1, 0, 0, 'exec', '[-1-2-]'),
(43, 'autocomplet', 1, 'autocomplet', 'ajax', 1, 'Auto complete Input', NULL, 1, 1, 0, '', '[-1-]'),
(53, 'addtest', 1, 'addtest', 'tdb', 1, 'Test galerry', NULL, 1, 1, 0, 'form', '[-1-2-17-]'),
(66, 'setting', 1, 'setting', 'tdb', 1, 'Paramètrages', NULL, 1, 1, 0, '', '[-1-]'),
(89, 'villes', 8, 'villes', 'Systeme/settings/villes', 1, 'Gestion Villes', 'building', 1, 0, 0, 'list', '[-1-]'),
(90, 'addville', 8, 'addville', 'Systeme/settings/villes', 1, 'Ajouter ville', NULL, 1, 0, 0, 'form', '[-1-]'),
(91, 'editville', 8, 'editville', 'Systeme/settings/villes', 1, 'Editer Ville', NULL, 1, 0, 0, 'form', '[-1-]'),
(92, 'deleteville', 8, 'deleteville', 'Systeme/settings/villes', 1, 'Supprimer Ville', NULL, 1, 0, 0, 'exec', '[-1-]'),
(319, 'notif', 1, 'notifier', 'ajax', 1, 'Notifier', NULL, 1, 1, 0, '', '[-1-]'),
(333, 'categorie_client', 77, 'categorie_client', 'clients/settings/categorie_client', 1, 'Gestion Catégorie Client', 'certificate', 1, 0, 0, 'list', '[-1-]'),
(334, 'addcategorie_client', 77, 'addcategorie_client', 'clients/settings/categorie_client', 1, 'Ajouter Catégorie Client', 'certificate', 1, 0, 0, 'form', '[-1-]'),
(335, 'editcategorie_client', 77, 'editcategorie_client', 'clients/settings/categorie_client', 1, 'Editer Catégorie Client', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(336, 'deletecategorie_client', 77, 'deletecategorie_client', 'clients/settings/categorie_client', 1, 'Supprimer Catégorie Client', 'trash', 1, 0, 0, 'exec', '[-1-]'),
(337, 'validcategorie_client', 77, 'validcategorie_client', 'clients/settings/categorie_client', 1, 'Valider Catégorie Client', 'cloud', 1, 0, 0, 'exec', '[-1-]'),
(394, 'clients', 87, 'clients', 'clients', 1, 'Gestion Clients', 'users', 1, 0, 0, 'list', '[-1-]'),
(395, 'addclient', 87, 'addclient', 'clients', 1, 'Ajouter Client', 'users', 1, 0, 0, 'form', '[-1-]'),
(396, 'editclient', 87, 'editclient', 'clients', 1, 'Editer Client', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(397, 'deleteclient', 87, 'deleteclient', 'clients', 1, 'Supprimer Client', 'trash', 1, 0, 0, 'exec', '[-1-]'),
(398, 'validclient', 87, 'validclient', 'clients', 1, 'Valider Client', 'lock', 1, 0, 0, 'exec', '[-1-]'),
(399, 'detailsclient', 87, 'detailsclient', 'clients', 1, 'Détails Client', 'eye', 1, 0, 0, 'profil', '[-1-]'),
(423, 'validville', 8, 'validville', 'Systeme/settings/villes', 1, 'Valider Ville', 'lock', 1, 0, 0, 'exec', '[-1-]'),
(430, 'vente', 93, 'vente', 'vente/main', 1, 'Gestion Vente', 'money', 1, 0, 0, 'list', '[-1-2-3-5-]'),
(432, 'departements', 9, 'departements', 'Systeme/settings/departements', 1, 'Gestion Départements', 'bullhorn', 1, 0, 0, 'list', '[-1-]'),
(433, 'adddepartement', 9, 'adddepartement', 'Systeme/settings/departements', 1, 'Ajouter Département', 'bullhorn', 1, 0, 0, 'form', '[-1-]'),
(434, 'editdepartement', 9, 'editdepartement', 'Systeme/settings/departements', 1, 'Editer Département', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(435, 'deletedepartement', 9, 'deletedepartement', 'Systeme/settings/departements', 1, 'Supprimer Département', 'trash', 1, 0, 0, 'exec', '[-1-]'),
(436, 'validdepartement', 9, 'validdepartement', 'Systeme/settings/departements', 1, 'Valider Département', 'lock', 1, 0, 0, 'exec', '[-1-]'),
(455, 'categories_produits', 98, 'categories_produits', 'produits/settings/categories_produits', 1, 'Gestion des catégories de produits', 'cogs', 1, 0, 0, 'list', '[-1-]'),
(456, 'addcategorie_produit', 98, 'addcategorie_produit', 'produits/settings/categories_produits', 1, 'Ajouter une catégorie', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(457, 'editecategorie_produit', 98, 'editecategorie_produit', 'produits/settings/categories_produits', 1, 'Modifier une catégorie', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(458, 'validcategorie_produit', 98, 'validcategorie_produit', 'produits/settings/categories_produits', 1, 'Valider une catégorie', 'cogs', 1, 0, 0, 'exec', '[-1-]'),
(459, 'deletecategorie_produit', 98, 'deletecategorie_produit', 'produits/settings/categories_produits', 1, 'Supprimer une catégorie', 'cogs', 1, 0, 0, 'exec', '[-1-]'),
(460, 'types_produits', 99, 'types_produits', 'produits/settings/types_produits', 1, 'Gestion des types de produits', 'cogs', 1, 0, 0, 'list', '[-1-]'),
(461, 'addtype_produit', 99, 'addtype_produit', 'produits/settings/types_produits', 1, 'Ajouter un type', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(462, 'edittype_produit', 99, 'edittype_produit', 'produits/settings/types_produits', 1, 'Modifier type', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(463, 'validtype_produit', 99, 'validtype_produit', 'produits/settings/types_produits', 1, 'Valider type', 'cogs', 1, 0, 0, 'exec', '[-1-]'),
(464, 'deletetype_produit', 99, 'deletetype_produit', 'produits/settings/types_produits', 1, 'Supprimer type', 'cogs', 1, 0, 0, 'exec', '[-1-]'),
(465, 'unites_vente', 100, 'unites_vente', 'produits/settings/unites_vente', 1, 'Gestion des unités de vente', 'cogs', 1, 0, 0, 'list', '[-1-]'),
(466, 'addunite_vente', 100, 'addunite_vente', 'produits/settings/unites_vente', 1, 'ajouter une unité', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(467, 'editunite_vente', 100, 'editunite_vente', 'produits/settings/unites_vente', 1, 'Modifier une unité', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(468, 'validunite_vente', 100, 'validunite_vente', 'produits/settings/unites_vente', 1, 'Valider une unité', 'cogs', 1, 0, 0, 'exec', '[-1-]'),
(469, 'deleteunite_vente', 100, 'deleteunite_vente', 'produits/settings/unites_vente', 1, 'Supprimer une unité', 'cogs', 1, 0, 0, 'exec', '[-1-]'),
(470, 'regions', 7, 'regions', 'Systeme/settings/regions', 1, 'Gestion des régions', 'cogs', 1, 0, 0, 'list', '[-1-]'),
(471, 'addregion', 7, 'addregion', 'Systeme/settings/regions', 1, 'Ajouter région', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(472, 'editregion', 7, 'editregion', 'Systeme/settings/regions', 1, 'Modifier région', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(473, 'validregion', 7, 'validregion', 'Systeme/settings/regions', 1, 'Valider région', 'cogs', 1, 0, 0, 'exec', '[-1-]'),
(474, 'deleteregion', 7, 'deleteregion', 'Systeme/settings/regions', 1, 'Supprimer région', 'cogs', 1, 0, 0, 'exec', '[-1-]'),
(475, 'pays', 6, 'pays', 'Systeme/settings/pays', 1, 'Gestion Pays', 'flag', 1, 0, 0, 'list', '[-1-]'),
(476, 'addpays', 6, 'addpays', 'Systeme/settings/pays', 1, 'Ajouter Pays', 'flag', 1, 0, 0, 'form', '[-1-]'),
(477, 'editpays', 6, 'editpays', 'Systeme/settings/pays', 1, 'Editer Pays', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(478, 'deletepays', 6, 'deletepays', 'Systeme/settings/pays', 1, 'Supprimer Pays', 'trash', 1, 0, 0, 'exec', '[-1-]'),
(479, 'validpays', 6, 'validpays', 'Systeme/settings/pays', 1, 'Valider Pays', 'lock', 1, 0, 0, 'exec', '[-1-]'),
(480, 'type_echeance', 103, 'type_echeance', 'contrats/settings/type_echeance', 1, 'Gestion Type Echeance', 'info-circle', 1, 0, 0, 'list', '[-1-]'),
(481, 'addtype_echeance', 103, 'addtype_echeance', 'contrats/settings/type_echeance', 1, 'Ajouter Type Echéance', 'info-circle', 1, 0, 0, 'form', '[-1-]'),
(482, 'edittype_echeance', 103, 'edittype_echeance', 'contrats/settings/type_echeance', 1, 'Editer Type Echéance', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(483, 'deletetype_echeance', 103, 'deletetype_echeance', 'contrats/settings/type_echeance', 1, 'Supprimer Type Echéance', 'trash', 1, 0, 0, 'exec', '[-1-]'),
(484, 'validtype_echeance', 103, 'validtype_echeance', 'contrats/settings/type_echeance', 1, 'Valider Type Echéance', 'lock', 1, 0, 0, 'exec', '[-1-]'),
(502, 'fournisseurs', 107, 'fournisseurs', 'fournisseurs/main', 1, 'Gestion Fournisseurs', 'users', 1, 0, 0, 'list', '[-1-]'),
(503, 'addfournisseur', 107, 'addfournisseur', 'fournisseurs/main', 1, 'Ajouter Fournisseur', 'user', 1, 0, 0, 'form', '[-1-]'),
(504, 'editfournisseur', 107, 'editfournisseur', 'fournisseurs/main', 1, 'Editer Fournisseur', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(505, 'deletefournisseur', 107, 'deletefournisseur', 'fournisseurs/main', 1, 'Supprimer Fournisseur', 'trash', 1, 0, 0, 'exec', '[-1-]'),
(506, 'validfournisseur', 107, 'validfournisseur', 'fournisseurs/main', 1, 'Valider Fournisseur', 'lock', 1, 0, 0, 'exec', '[-1-]'),
(508, 'detailsfournisseur', 107, 'detailsfournisseur', 'fournisseurs/main', 1, 'Détails Fournisseur', 'eye', 1, 0, 0, 'profil', '[-1-]'),
(542, 'info_ste', 111, 'info_ste', 'Systeme/settings/info_ste', 1, 'Information société', 'credit-card', 1, 0, 0, 'list', '[-1-3-]'),
(543, 'sys_setting', 5, 'sys_setting', 'Systeme/settings/sys_setting', 1, 'Paramètrage Système', 'setting', 1, 0, 0, 'list', '[-1-]'),
(544, 'add_sys_setting', 5, 'add_sys_setting', 'Systeme/settings/sys_setting', 1, 'Ajouter Paramètre', 'plus', 1, 0, 0, 'form', '[-1-]'),
(545, 'edit_sys_setting', 5, 'edit_sys_setting', 'Systeme/settings/sys_setting', 1, 'Editer paramètre', 'pen', 1, 0, 0, 'form', '[-1-]'),
(546, 'delete_sys_setting', 5, 'delete_sys_setting', 'Systeme/settings/sys_setting', 1, 'Supprimer Paramètre', 'trash', 1, 0, 0, 'exec', '[-1-]'),
(604, 'report', 1, 'report', 'ajax', 1, 'PDF Reporter', NULL, 1, 1, 0, '', '[-1-]'),
(609, 'contrats_fournisseurs', 117, 'contrats_fournisseurs', 'contrats_fournisseurs/main', 1, 'Gestion Contrats Fournisseurs', 'book', 1, 0, 0, 'list', '[-1-]'),
(610, 'addcontrat_frn', 117, 'addcontrat_frn', 'contrats_fournisseurs/main', 1, 'Ajouter Contrat', 'book', 1, 0, 0, 'form', '[-1-]'),
(611, 'editcontrat_frn', 117, 'editcontrat_frn', 'contrats_fournisseurs/main', 1, 'Editer Contrat', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(612, 'deletecontrat_frn', 117, 'deletecontrat_frn', 'contrats_fournisseurs/main', 1, 'Supprimer Contrat', 'trash', 1, 0, 0, 'exec', '[-1-]'),
(613, 'validcontrat_frn', 117, 'validcontrat_frn', 'contrats_fournisseurs/main', 1, 'Valider Contrat', 'lock', 1, 0, 0, 'exec', '[-1-]'),
(614, 'detailscontrat_frn', 117, 'detailscontrat_frn', 'contrats_fournisseurs/main', 1, 'Détails Contrat', 'eye', 1, 0, 0, 'profil', '[-1-]'),
(615, 'renouveler_contrat', 117, 'renouveler_contrat', 'contrats_fournisseurs/main', 1, 'Renouveler  Contrat', 'exchange', 1, 0, 0, 'form', '[-1-]'),
(637, 'modul', 120, 'modul', 'modul_mgr', 1, 'Modules', 'cubes', 1, 0, 0, 'list', '[-1-]'),
(638, 'addmodul', 120, 'addmodul', 'modul_mgr', 1, 'Ajouter Module', NULL, 1, 1, 0, 'form', '[-1-]'),
(639, 'editmodul', 120, 'editmodul', 'modul_mgr', 1, 'Editer Module', NULL, 1, 1, 0, 'form', '[-1-]'),
(640, 'task', 120, 'task', 'modul_mgr', 1, 'Liste Application Associes', NULL, 1, 1, 0, 'list', '[-1-]'),
(641, 'addtask', 120, 'addtask', 'modul_mgr', 1, 'Ajouter Module Task', NULL, 1, 1, 0, 'form', '[-1-]'),
(642, 'edittask', 120, 'edittask', 'modul_mgr', 1, 'Editer Module Task', NULL, 1, 1, 0, 'form', '[-1-]'),
(643, 'taskaction', 120, 'taskaction', 'modul_mgr', 1, 'Liste Task Action', '0', 1, 0, 0, 'list', '[-1-]'),
(644, 'addtaskaction', 120, 'addtaskaction', 'modul_mgr', 1, 'Ajouter Action Task', '0', 1, 0, 0, 'form', '[-1-3-]'),
(645, 'deletetask', 120, 'deletetask', 'modul_mgr', 1, 'Supprimer Application', NULL, 1, 0, 0, 'exec', '[-1-]'),
(646, 'importmodul', 120, 'importmodul', 'modul_mgr', 1, 'Importer des modules', NULL, 1, 0, 0, 'exec', '[-1-]'),
(647, 'addmodulsetting', 120, 'addmodulsetting', 'modul_mgr', 1, 'Ajouter Module paramétrage', NULL, 1, 0, 0, 'form', '[-1-]'),
(648, 'editmodulsetting', 120, 'editmodulsetting', 'modul_mgr', 1, 'Editer Module paramètrage', 'na', 1, 0, 0, 'form', '[-1-]'),
(649, 'addetatrule', 120, 'addetatrule', 'modul_mgr', 1, 'Ajouter Autorisation Etat', NULL, 1, 0, 0, 'form', '[-1-]'),
(650, 'edittaskaction', 120, 'edittaskaction', 'modul_mgr', 1, 'Editer Task Action', 'pen', 1, 0, 0, 'form', '[-1-]'),
(651, 'update_module', 120, 'update_module', 'modul_mgr', 1, 'MAJ Module', 'pencil-square-o', 1, 0, 0, 'exec', '[-1-]'),
(652, 'dupliqtaskaction', 120, 'dupliqtaskaction', 'modul_mgr', 1, 'Dupliquer Action Task', 'check', 1, 0, 0, 'formadd', '[-1-]'),
(653, 'deletetaskaction', 120, 'deletetaskaction', 'modul_mgr', 1, 'Supprimer Task action', 'trash', 1, 0, 0, 'exec', '[-1-]'),
(654, 'workflow', 120, 'workflow', 'modul_mgr', 1, 'Affichage Work Flow', 'exchange', 1, 0, 0, 'profil', '[-1-]'),
(655, 'devis', 121, 'devis', 'vente/submodul/devis', 1, 'Gestion Devis', 'paper-plane-o', 1, 0, 0, 'list', '[-1-2-3-5-]'),
(656, 'adddevis', 121, 'adddevis', 'vente/submodul/devis', 1, 'Ajouter Devis', 'plus', 1, 0, 0, 'form', '[-1-2-]'),
(657, 'add_detaildevis', 121, 'add_detaildevis', 'vente/submodul/devis', 1, 'Ajouter détail devis', 'plus', 1, 0, 0, 'form', '[-1-2-]'),
(658, 'editdevis', 121, 'editdevis', 'vente/submodul/devis', 1, 'Modifier Devis', 'pen', 1, 0, 0, 'form', '[-1-2-]'),
(659, 'deletedevis', 121, 'deletedevis', 'vente/submodul/devis', 1, 'Supprimer Devis', 'trash red', 1, 0, 0, 'exec', '[-1-2-]'),
(660, 'edit_detaildevis', 121, 'edit_detaildevis', 'vente/submodul/devis', 1, 'Modifier détail Devis', 'pen', 1, 0, 0, 'form', '[-1-2-]'),
(661, 'viewdevis', 121, 'viewdevis', 'vente/submodul/devis', 1, 'Afficher détail devis', 'eye', 1, 0, 0, 'profil', '[-1-2-]'),
(662, 'validdevis', 121, 'validdevis', 'vente/submodul/devis', 1, 'Valider Devis', NULL, 1, 0, 0, 'exec', '[-1-2-]'),
(663, 'validdevisclient', 121, 'validdevisclient', 'vente/submodul/devis', 1, 'Validation Client Devis', 'check', 1, 0, 0, 'formpers', '[-1-2-]'),
(664, 'debloqdevis', 121, 'debloqdevis', 'vente/submodul/devis', 1, 'Débloquer devis', 'unlock blue', 1, 0, 0, 'exec', '[-1-2-]'),
(665, 'archivdevis', 121, 'archivdevis', 'vente/submodul/devis', 1, 'Archiver Devis', 'zip', 1, 0, 0, 'exec', '[-1-2-3-]'),
(675, 'factures', 123, 'factures', 'factures/main', 1, 'Gestion des factures', 'file', 1, 0, 0, 'list', '[-1-2-3-5-]'),
(676, 'complements', 123, 'complements', 'factures/main', 1, 'complements', 'cogs', 1, 0, 0, 'list', '[-1-]'),
(677, 'addcomplement', 123, 'addcomplement', 'factures/main', 1, 'Ajouter complément', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(678, 'encaissements', 123, 'encaissements', 'factures/main', 1, 'Encaissement', 'cogs', 1, 0, 0, 'list', '[-1-]'),
(679, 'addencaissement', 123, 'addencaissement', 'factures/main', 1, 'Ajouter encaissement', 'cogs', 1, 0, 0, 'list', '[-1-]'),
(680, 'editcomplement', 123, 'editcomplement', 'factures/main', 1, 'Modifier complément', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(681, 'editencaissement', 123, 'editencaissement', 'factures/main', 1, 'Modifier encaissement', 'cogs', 1, 0, 0, 'list', '[-1-]'),
(682, 'deletecomplement', 123, 'deletecomplement', 'factures/main', 1, 'Supprimer complément', 'cogs', 1, 0, 0, 'exec', '[-1-]'),
(683, 'deleteencaissement', 123, 'deleteencaissement', 'factures/main', 1, 'Supprimer encaissement', 'cogs', 1, 0, 0, 'exec', '[-1-]'),
(684, 'validfacture', 123, 'validfacture', 'factures/main', 1, 'Valider facture', 'cogs', 1, 0, 0, 'exec', '[-1-]'),
(685, 'detailsencaissement', 123, 'detailsencaissement', 'factures/main', 1, 'Détail encaissement', 'eye', 1, 0, 0, 'profil', '[-1-]'),
(686, 'detailsfacture', 123, 'detailsfacture', 'factures/main', 1, 'Détails facture', 'eye', 1, 0, 0, 'profil', '[-1-]'),
(687, 'rejectfacture', 123, 'rejectfacture', 'factures/main', 1, 'Désactiver Facture', 'remove', 1, 0, 0, 'exec', '[-1-]'),
(700, 'contrats', 125, 'contrats', 'vente/submodul/contrats', 1, 'Abonnements', 'cloud', 1, 0, 0, 'list', '[-1-2-3-5-]'),
(701, 'addcontrat', 125, 'addcontrat', 'vente/submodul/contrats', 1, 'Ajouter contrat', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(702, 'editcontrat', 125, 'editcontrat', 'vente/submodul/contrats', 1, 'Modifier contrat', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(703, 'deletecontrat', 125, 'deletecontrat', 'vente/submodul/contrats', 1, 'Supprimer contrat', 'cogs', 1, 0, 0, 'exec', '[-1-]'),
(704, 'validcontrat', 125, 'validcontrat', 'vente/submodul/contrats', 1, 'Valider contrat', 'cogs', 1, 0, 0, 'exec', '[-1-]'),
(705, 'detailcontrat', 125, 'detailcontrat', 'vente/submodul/contrats', 1, 'Détail contrat', 'cogs', 1, 0, 0, 'profil', '[-1-]'),
(706, 'addecheance_contrat', 125, 'addecheance_contrat', 'vente/submodul/contrats', 1, 'Ajouter échéance contrat', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(707, 'editecheance_contrat', 125, 'editecheance_contrat', 'vente/submodul/contrats', 1, 'Modifier échéance contrat', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(708, 'renouvelercontrat', 125, 'renouvelercontrat', 'vente/submodul/contrats', 1, 'Renouveler Contrat', 'exchange', 1, 0, 0, 'form', '[-1-]'),
(709, 'user', 126, 'user', 'users', 1, 'Utilisateurs', 'users', 1, 0, 0, 'list', '[-1-]'),
(710, 'adduser', 126, 'adduser', 'users', 1, 'Ajouter Utilisateur', NULL, 1, 0, 0, 'form', '[-1-]'),
(711, 'edituser', 126, 'edituser', 'users', 1, 'Editer compte utilisateur', NULL, 1, 0, 0, 'form', '[-1-]'),
(712, 'rules', 126, 'rules', 'users', 1, 'Permission Utilisateur', 'users', 1, 0, 0, 'form', '[-1-]'),
(713, 'delete_user', 126, 'delete_user', 'users', 1, 'Supprimer utilisateur', 'trash', 1, 0, 0, 'exec', 'null'),
(714, 'compte', 126, 'compte', 'users', 1, 'Details profile utilisateur', NULL, 1, 1, 0, 'profil', '[-1-]'),
(715, 'activeuser', 126, 'activeuser', 'users', 1, 'Activer utilisateur', 'unlock', 1, 0, 0, 'exec', '[-1-]'),
(716, 'archiv_user', 126, 'archiv_user', 'users', 1, 'Désactiver l\'utilisateur', 'ban', 1, 0, 0, 'exec', '[-1-]'),
(717, 'changepass', 126, 'changepass', 'users', 1, 'Changer le mot de passe', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(718, 'history', 126, 'history', 'users', 1, 'History', 'users', 1, 0, 0, 'list', '[-1-]'),
(719, 'activities', 126, 'activities', 'users', 1, 'Activities', 'users', 1, 0, 0, 'list', '[-1-]'),
(720, 'proforma', 127, 'proforma', 'vente/submodul/proforma', 1, 'Gestion Proforma', 'book', 1, 0, 0, 'list', '[-1-2-3-5-4-]'),
(721, 'addproforma', 127, 'addproforma', 'vente/submodul/proforma', 1, 'Ajouter pro-forma', 'plus', 1, 0, 0, 'form', '[-1-2-3-5-4-]'),
(722, 'editproforma', 127, 'editproforma', 'vente/submodul/proforma', 1, 'Editer proforma', 'pen', 1, 0, 0, 'form', '[-1-2-3-5-4-]'),
(723, 'add_detailproforma', 127, 'add_detailproforma', 'vente/submodul/proforma', 1, 'Ajouter détail proforma', 'plus', 1, 0, 0, 'form', '[-1-2-3-5-4-]'),
(724, 'validproforma', 127, 'validproforma', 'vente/submodul/proforma', 1, 'valider Proforma', 'check', 1, 0, 0, 'exec', '[-1-2-3-5-4-]'),
(725, 'viewproforma', 127, 'viewproforma', 'vente/submodul/proforma', 1, 'Détail Pro-forma', 'eye', 1, 0, 0, 'profil', '[-1-2-3-5-4-]'),
(726, 'edit_detailproforma', 127, 'edit_detailproforma', 'vente/submodul/proforma', 1, 'Editer détail proforma', 'pen', 1, 0, 0, 'form', '[-1-2-3-5-4-]'),
(727, 'deleteproforma', 127, 'deleteproforma', 'vente/submodul/proforma', 1, 'Supprimer proforma', 'trash', 1, 0, 0, 'exec', '[-1-2-3-5-4-]'),
(728, 'produits', 128, 'produits', 'produits', 1, 'Gestion des produits', 'cogs', 1, 0, 0, 'list', '[-1-]'),
(729, 'addproduit', 128, 'addproduit', 'produits', 1, 'Ajouter produit', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(730, 'editproduit', 128, 'editproduit', 'produits', 1, 'Modifier produit', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(731, 'detailproduit', 128, 'detailproduit', 'produits', 1, 'Detail produit', 'cogs', 1, 0, 0, 'profil', '[-1-]'),
(732, 'validproduit', 128, 'validproduit', 'produits', 1, 'Valider produit', 'cogs', 1, 0, 0, 'exec', '[-1-]'),
(733, 'deleteproduit', 128, 'deleteproduit', 'produits', 1, 'Supprimer produit', 'cogs', 1, 0, 0, 'exec', '[-1-]'),
(734, 'buyproducts', 128, 'buyproducts', 'produits', 1, 'Achat produit', 'cogs', 1, 0, 0, 'list', '[-1-]'),
(735, 'addbuyproduct', 128, 'addbuyproduct', 'produits', 1, 'Ajouter achat', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(736, 'editbuyproduct', 128, 'editbuyproduct', 'produits', 1, 'Modifier achat', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(737, 'deletebuyproduct', 128, 'deletebuyproduct', 'produits', 1, 'Supprimer achat', 'cogs', 1, 0, 0, 'exec', '[-1-]'),
(738, 'detailbuyproduct', 128, 'detailbuyproduct', 'produits', 1, 'Détail achat', 'cogs', 1, 0, 0, 'profil', '[-1-]'),
(739, 'validbuyproduct', 128, 'validbuyproduct', 'produits', 1, 'Valider achat', 'cogs', 1, 0, 0, 'exec', '[-1-]');

-- --------------------------------------------------------

--
-- Structure de la table `task_action`
--

CREATE TABLE `task_action` (
  `id` int(11) NOT NULL COMMENT 'ID App Action (AI)',
  `appid` int(11) NOT NULL COMMENT 'ID Application',
  `idf` varchar(32) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Idf for Mgt rules',
  `descrip` varchar(75) CHARACTER SET latin1 NOT NULL COMMENT 'Description',
  `mode_exec` varchar(10) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Mode execution',
  `app` varchar(25) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Appli to call',
  `class` varchar(30) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Class menu',
  `code` text CHARACTER SET latin1 NOT NULL COMMENT 'code html',
  `type` int(11) DEFAULT '0' COMMENT 'action = 0 task=1',
  `service` varchar(30) CHARACTER SET latin1 DEFAULT NULL COMMENT 'User Service ID',
  `etat_line` int(11) NOT NULL COMMENT 'Etat de la ligne quand si action liste',
  `notif` int(1) DEFAULT '0' COMMENT 'Notification ligne',
  `etat_desc` varchar(200) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Descrp ETAT line',
  `message_class` varchar(15) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Message class',
  `message_etat` varchar(250) CHARACTER SET latin1 DEFAULT 'nA' COMMENT 'Message Etat',
  `creusr` varchar(25) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Add by',
  `credat` datetime DEFAULT NULL COMMENT 'add date',
  `updusr` varchar(25) CHARACTER SET latin1 DEFAULT NULL COMMENT 'update by',
  `upddat` datetime DEFAULT NULL COMMENT 'update by'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table of Task_Action and Permission of Task';

--
-- Déchargement des données de la table `task_action`
--

INSERT INTO `task_action` (`id`, `appid`, `idf`, `descrip`, `mode_exec`, `app`, `class`, `code`, `type`, `service`, `etat_line`, `notif`, `etat_desc`, `message_class`, `message_etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(28, 34, '83b9fa44466da4bcd7f8304185bfeac8', 'Services', NULL, 'services', NULL, '', 0, '[-1-]', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(29, 35, '55043bc4207521e3010e91d6267f5302', 'Ajouter Service', NULL, 'addservice', NULL, '', 1, '[-1-2-4-]', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(30, 36, '2fea3d893f6b6e81467ddd2a744e4a76', 'Modifier Service', NULL, 'editservice', NULL, '', 1, '[-1-2-4-]', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(31, 37, '1a0d5897d31b4d5e29022671c1112f59', 'Valider Service', NULL, 'validservice', NULL, '', 1, '[-1-2-4-]', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(32, 38, '42083d4e159baf7c2ace2bb977e2b0a0', 'Supprimer Service', NULL, 'deletservice', NULL, '', 1, '[-1-2-4-]', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(33, 34, '3c388c1e842851df49abe9ee73c0a2e7', 'Valider Service', 'this_exec', 'validservices', 'check', '<li><a href=\"#\" class=\"this_exec\" data=\"%id%\" rel=\"validservices\"  ><i class=\"ace-icon fa fa-check bigger-100\"></i> Valider Service</a></li>', 0, '[-1-2-4-]', 0, 1, 'Attente validation', 'warning', '<span class=\"label label-sm label-warning\">Attente validation</span>', NULL, NULL, NULL, NULL),
(34, 34, 'dcb9555d5ca1d108e9fa95daa9da4b3a', 'Supprimer Service', 'this_exec', 'deleteservices', 'trash red', '<li><a href=\"#\" class=\"this_exec\" data=\"%id%\" rel=\"deleteservices\"  ><i class=\"ace-icon fa fa-trash red bigger-100\"></i> Supprimer Service</a></li>', 0, '[-1-2-4-]', 1, 0, 'Service validé', 'success', '<span class=\"label label-sm label-success\">Service validé</span>', NULL, NULL, NULL, NULL),
(114, 89, '2c3b01c696ff401a2ac9ffedb7a06e4a', 'Gestion Villes', NULL, 'villes', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(115, 90, 'e152b9052d3dcfcac593489dbdc0f61c', 'Ajouter ville', NULL, 'addville', NULL, '', 1, '[-1-2-]', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(116, 91, '3107e0cd0e0df14c4e94aa088e4457d7', 'Editer Ville', NULL, 'editville', NULL, '', 1, '[-1-2-]', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(117, 92, 'da79d9214ed5819d7f4f1e3070629a3d', 'Supprimer Ville', NULL, 'deleteville', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(119, 89, 'b9649163b368f863a0e8036f11cd81ae', 'Editer Ville', 'this_url', 'villes', NULL, '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"editville\"  ><i class=\"ace-icon fa fa-pencil bigger-100\"></i> Editer Ville</a></li>', 0, '[-1-]', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(121, 89, '89dec6dabcb210cdb9dd28bbef90d43e', 'Editer Ville', 'this_url', 'villes', NULL, '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"editville\"  ><i class=\"ace-icon fa fa-pencil bigger-100\"></i> Editer Ville</a></li>', 0, '[-1-]', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(144, 34, '74950fb3fd858404b6048c1e81bd7c9a', 'Modifier Service', 'this_url', 'editservices', 'pencil-square-o blue', '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"editservices\"  ><i class=\"ace-icon fa fa-pencil-square-o blue bigger-100\"></i> Modifier Service</a></li>', 0, '[-1-]', 1, 0, 'Service validé', 'success', '<span class=\"label label-sm label-success\">Service validé</span>', NULL, NULL, NULL, NULL),
(497, 333, '6edc543080c65eca3993445c295ff94b', 'Gestion Catégorie Client', NULL, 'categorie_client', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(498, 334, 'a5c1bd0dfd87824ff0f57c6b1e1d2c3f', 'Ajouter Catégorie Client', NULL, 'addcategorie_client', NULL, '', 1, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(499, 335, '8d901f74dfd6ee3a8f44ebd0b83fbfae', 'Editer Catégorie Client', NULL, 'editcategorie_client', NULL, '', 1, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(500, 336, 'e87327563ce6b659780d6b2c9bf8ac77', 'Supprimer Catégorie Client', NULL, 'deletecategorie_client', NULL, '', 1, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(501, 337, 'c955da8d244aac06ee7595d08de7d009', 'Valider Catégorie Client', NULL, 'validcategorie_client', NULL, '', 1, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(506, 333, '142a68a109abd0462ea44fcadffe56de', 'Editer Catégorie Client', 'this_url', 'editcategorie_client', 'cogs', '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"editcategorie_client\"  ><i class=\"ace-icon fa fa-cogs bigger-100\"></i> Editer Catégorie Client</a></li>', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(507, 333, '70df89fa2654d8b10d7fc7e75e178b7e', 'Activer Catégorie Client', 'this_exec', 'validcategorie_client', 'lock', '<li><a href=\"#\" class=\"this_exec\" data=\"%id%\" rel=\"validcategorie_client\"  ><i class=\"ace-icon fa fa-lock bigger-100\"></i> Activer Catégorie Client</a></li>', 0, '[-1-]', 0, 1, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(508, 333, '109e82d6db5721f63cd827e9fd224216', 'Désactiver Catégorie Client', 'this_exec', 'validcategorie_client', 'unlock', '<li><a href=\"#\" class=\"this_exec\" data=\"%id%\" rel=\"validcategorie_client\"  ><i class=\"ace-icon fa fa-unlock bigger-100\"></i> Désactiver Catégorie Client</a></li>', 0, '[-1-]', 1, 0, 'Catégorie Validée', 'success', '<span class=\"label label-sm label-success\">Catégorie Validée</span>', NULL, NULL, NULL, NULL),
(553, 394, 'f12fb1c50aedc49c3fa3dfa2bd297bd3', 'Gestion Clients', NULL, 'clients', 'users', ' ', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(554, 394, 'dd3d5980299911ea854af4fa6f2e7309', 'Editer Client', 'this_url', 'editclient', 'cogs', '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"editclient\"  ><i class=\"ace-icon fa fa-cogs bigger-100\"></i> Editer Client</a></li>', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(555, 394, '3c5c04a20d49ad010557a64c8cdac1ce', 'Valider Client', 'this_exec', 'validclient', 'lock', '<li><a href=\"#\" class=\"this_exec\" data=\"%id%\" rel=\"validclient\"  ><i class=\"ace-icon fa fa-lock bigger-100\"></i> Valider Client</a></li>', 0, '[-1-]', 0, 1, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(556, 394, '18ace52052f2551099ecaabf049ffaec', 'Désactiver Client', 'this_exec', 'validclient', 'unlock', '<li><a href=\"#\" class=\"this_exec\" data=\"%id%\" rel=\"validclient\"  ><i class=\"ace-icon fa fa-unlock bigger-100\"></i> Désactiver Client</a></li>', 0, '[-1-]', 1, 0, 'Client Validé', 'success', '<span class=\"label label-sm label-success\">Client Validé</span>', NULL, NULL, NULL, NULL),
(557, 394, '493f9e55fc0340763e07514c1900685a', 'Détails Client', 'this_url', 'detailsclient', 'eye', '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"detailsclient\"  ><i class=\"ace-icon fa fa-eye bigger-100\"></i> Détails Client</a></li>', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(558, 394, '03b4f949b088e41fc9a1f3f23b7906a8', 'Détails  Client', 'this_url', 'detailsclient', 'eye', '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"detailsclient\"  ><i class=\"ace-icon fa fa-eye bigger-100\"></i> Détails  Client</a></li>', 0, '[-1-]', 1, 0, 'Client Validé', 'success', '<span class=\"label label-sm label-success\">Client Validé</span>', NULL, NULL, NULL, NULL),
(559, 395, '2b9d8bb8f752d1c35fb681c33e38b42b', 'Ajouter Client', NULL, 'addclient', 'user', ' ', 1, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(560, 396, '54aa9121e05f5e698d354022a8eab71d', 'Editer Client', NULL, 'editclient', 'cogs', ' ', 1, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(561, 397, '4eaf650e8c2221d590fac5a6a6952231', 'Supprimer Client', NULL, 'deleteclient', 'trash', ' ', 1, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(562, 398, '534cd4b17fb8a371d3a20565ab8fd96e', 'Valider Client', NULL, 'validclient', 'lock', ' ', 1, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(563, 399, '95bb6aa696ef630a335aa84e1e425e2c', 'Détails Client', NULL, 'detailsclient', 'eye', ' ', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(601, 423, 'fe03a68d17c62ff2c27329573a1b3550', 'Valider Ville', NULL, 'validville', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(602, 89, '4a2edbdcbda34c9d3d1e6abe73643b37', 'Valider Ville', 'this_exec', 'validville', 'lock', '<li><a href=\"#\" class=\"this_exec\" data=\"%id%\" rel=\"validville\"  ><i class=\"ace-icon fa fa-lock bigger-100\"></i> Valider Ville</a></li>', 0, '[-1-]', 0, 1, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(603, 89, '0c8ad6595a4516be83ba5a9cdb7ea9a1', 'Désactiver Ville', 'this_exec', 'validville', 'unlock', '<li><a href=\"#\" class=\"this_exec\" data=\"%id%\" rel=\"validville\"  ><i class=\"ace-icon fa fa-unlock bigger-100\"></i> Désactiver Ville</a></li>', 0, '[-1-]', 1, 0, 'Ville Validée', 'success', '<span class=\"label label-sm label-success\">Ville Validée</span>', NULL, NULL, NULL, NULL),
(611, 430, '3d4eaa53061f51b0c4435bd8e4b89c17', 'Gestion Vente', NULL, 'vente', NULL, '', 0, '[-1-2-]', 0, 0, 'Actif', 'success', '<span class=\"label label-sm label-success\">Actif</span>', NULL, NULL, NULL, NULL),
(613, 432, 'f320732af279d6f2f8ae9c98cd0216de', 'Gestion Départements', NULL, 'departements', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(614, 433, '722b3ba1c7fe735e87aa7415e5502a4c', 'Ajouter Département', NULL, 'adddepartement', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(615, 434, 'daeb31006124e562d284aff67360ee19', 'Editer Département', NULL, 'editdepartement', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(616, 435, 'a775da608fe55c53211d4f1c6e493251', 'Supprimer Département', NULL, 'deletedepartement', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(617, 432, '96516cd0c72d814d5dcb1d86eacd29ab', 'Editer Département', 'this_url', 'editdepartement', 'cogs', '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"editdepartement\"  ><i class=\"ace-icon fa fa-cogs bigger-100\"></i> Editer Département</a></li>', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(618, 436, 'bbb96ec910c5000a2006db2f6e8af10a', 'Valider Département', NULL, 'validdepartement', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(619, 432, 'ef27a63534fa9fc3bd4b5086a92db546', 'Valider Département', 'this_exec', 'validdepartement', 'lock', '<li><a href=\"#\" class=\"this_exec\" data=\"%id%\" rel=\"validdepartement\"  ><i class=\"ace-icon fa fa-lock bigger-100\"></i> Valider Département</a></li>', 0, '[-1-]', 0, 1, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(620, 432, '9aed965af4c4b89a5a23c41bf685d403', 'Désactiver Département', 'this_exec', 'validdepartement', 'unlock', '<li><a href=\"#\" class=\"this_exec\" data=\"%id%\" rel=\"validdepartement\"  ><i class=\"ace-icon fa fa-unlock bigger-100\"></i> Désactiver Département</a></li>', 0, '[-1-]', 1, 0, 'Département Validé', 'success', '<span class=\"label label-sm label-success\">Département Validé</span>', NULL, NULL, NULL, NULL),
(652, 455, 'e69f84a801ee1525f20f34e684688a9b', 'Gestion des catégories de produits', NULL, 'categories_produits', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(653, 455, '90f6eba3e0ed223e73d250278cb445d5', 'Modifier catégorie', 'this_url', 'editecategorie_produit', NULL, '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"editecategorie_produit\"  ><i class=\"ace-icon fa fa-cogs bigger-100\"></i> Modifier catégorie</a></li>', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(654, 455, 'c62968a45ae9cfa8b127ac1b5573988a', 'Valider catégorie', 'this_exec', 'validcategorie_produit', NULL, '<li><a href=\"#\" class=\"this_exec\" data=\"%id%\" rel=\"validcategorie_produit\"  ><i class=\"ace-icon fa fa-cogs bigger-100\"></i> Valider catégorie</a></li>', 0, '[-1-]', 0, 1, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(655, 455, '6f43a6bcbd293f958aff51953559104e', 'Désactiver catégorie', 'this_exec', 'validcategorie_produit', NULL, '<li><a href=\"#\" class=\"this_exec\" data=\"%id%\" rel=\"validcategorie_produit\"  ><i class=\"ace-icon fa fa-cogs bigger-100\"></i> Désactiver catégorie</a></li>', 0, '[-1-]', 1, 0, 'Catégorie Validée', 'success', '<span class=\"label label-sm label-success\">Catégorie Validée</span>', NULL, NULL, NULL, NULL),
(656, 456, 'd26f5940e88a494c0eb65047aab9a17b', 'Ajouter une catégorie', NULL, 'addcategorie_produit', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(657, 457, '27957c6d0f6869d4d90287cd50b6dde9', 'Modifier une catégorie', NULL, 'editecategorie_produit', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(658, 458, '41b48dd567e4f79e35261a47b7bad751', 'Valider une catégorie', NULL, 'validcategorie_produit', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(659, 459, '90dc20c4d1ad7be7fac8ec34d5ac26b3', 'Supprimer une catégorie', NULL, 'deletecategorie_produit', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(660, 460, 'b6b6bfbd070b5b3dd84acedae7b854e9', 'Gestion des types de produits', NULL, 'types_produits', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(661, 460, '3c5400b775264499825a039d66aa9c90', 'Modifier type', 'this_url', 'edittype_produit', NULL, '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"edittype_produit\"  ><i class=\"ace-icon fa fa-cogs bigger-100\"></i> Modifier type</a></li>', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(662, 460, 'dcf55bc300d690af4c81e4d2335e60e5', 'Valider type', 'this_exec', 'validtype_produit', NULL, '<li><a href=\"#\" class=\"this_exec\" data=\"%id%\" rel=\"validtype_produit\"  ><i class=\"ace-icon fa fa-cogs bigger-100\"></i> Valider type</a></li>', 0, '[-1-]', 0, 1, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(663, 460, '230b9554d37da1c71986af94962cb340', 'Désactiver type', 'this_exec', 'validtype_produit', NULL, '<li><a href=\"#\" class=\"this_exec\" data=\"%id%\" rel=\"validtype_produit\"  ><i class=\"ace-icon fa fa-cogs bigger-100\"></i> Désactiver type</a></li>', 0, '[-1-]', 1, 0, 'Type validé', 'success', '<span class=\"label label-sm label-success\">Type validé</span>', NULL, NULL, NULL, NULL),
(664, 461, 'e0d163499b4ba11d6d7a648bc6fc6de6', 'Ajouter un type', NULL, 'addtype_produit', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(665, 462, 'ac5a6d087b3c8db7501fa5137a47773e', 'Modifier type', NULL, 'edittype_produit', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(666, 463, '2e8242a93a62a264ad7cfc953967f575', 'Valider type', NULL, 'validtype_produit', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(667, 464, 'e3725ba15ca483b9278f68553eca5918', 'Supprimer type', NULL, 'deletetype_produit', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(668, 465, '55ecbb545a49c70c0b728bb0c7951077', 'Gestion des unités de vente', NULL, 'unites_vente', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(669, 465, '67acd70eb04242b7091d9dcbb08295d7', 'Modifier unité ', 'this_url', 'editunite_vente', NULL, '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"editunite_vente\"  ><i class=\"ace-icon fa fa-cogs bigger-100\"></i> Modifier unité </a></li>', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(670, 465, '7363022ed5ad047bfe86d3de4b75b1f4', 'Valider unité', 'this_exec', 'validunite_vente', NULL, '<li><a href=\"#\" class=\"this_exec\" data=\"%id%\" rel=\"validunite_vente\"  ><i class=\"ace-icon fa fa-cogs bigger-100\"></i> Valider unité</a></li>', 0, '[-1-]', 0, 1, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(671, 465, 'ec77eb95736c27bfc269cbffc8e113f1', 'Désactiver unité', 'this_exec', 'validunite_vente', NULL, '<li><a href=\"#\" class=\"this_exec\" data=\"%id%\" rel=\"validunite_vente\"  ><i class=\"ace-icon fa fa-cogs bigger-100\"></i> Désactiver unité</a></li>', 0, '[-1-]', 1, 0, 'Unité de vente validé', 'success', '<span class=\"label label-sm label-success\">Unité de vente validé</span>', NULL, NULL, NULL, NULL),
(672, 466, '3a5e8dfe211121eda706f8b6d548d111', 'ajouter une unité', NULL, 'addunite_vente', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(673, 467, '9b7a578981de699286376903e96bc3c7', 'Modifier une unité', NULL, 'editunite_vente', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(674, 468, '62355588366c13ddbadc7a7ca1d226ad', 'Valider une unité', NULL, 'validunite_vente', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(675, 469, 'e5f53a3aaa324415d781156396938101', 'Supprimer une unité', NULL, 'deleteunite_vente', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(676, 470, 'd57b16b3aad4ce59f909609246c4fd36', 'Gestion des régions', NULL, 'regions', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(677, 470, 'd2e007184668dd70b9bae44d46d28ded', 'Modifier région', 'this_url', 'editregion', NULL, '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"editregion\"  ><i class=\"ace-icon fa fa-cogs bigger-100\"></i> Modifier région</a></li>', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(678, 470, 'e74403c99ac8325b78735c531a20442f', 'Valider région', 'this_exec', 'validregion', NULL, '<li><a href=\"#\" class=\"this_exec\" data=\"%id%\" rel=\"validregion\"  ><i class=\"ace-icon fa fa-cogs bigger-100\"></i> Valider région</a></li>', 0, '[-1-]', 0, 1, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(679, 470, '7397a0fab078728bd5c53be61022d5ce', 'Désactiver région', 'this_exec', 'validregion', NULL, '<li><a href=\"#\" class=\"this_exec\" data=\"%id%\" rel=\"validregion\"  ><i class=\"ace-icon fa fa-cogs bigger-100\"></i> Désactiver région</a></li>', 0, '[-1-]', 1, 0, 'Région Validée', 'success', '<span class=\"label label-sm label-success\">Région Validée</span>', NULL, NULL, NULL, NULL),
(680, 471, '0237bd41cf70c3529681b4ccb041f1fd', 'Ajouter région', NULL, 'addregion', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(681, 472, '6d290f454da473cb8a557829a410c3f1', 'Modifier région', NULL, 'editregion', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(682, 473, '008cd9ea5767c739675fef4e1261cfe8', 'Valider région', NULL, 'validregion', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(683, 474, 'fc477e6a4c90cd427ae81e555c11d6a9', 'Supprimer région', NULL, 'deleteregion', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(684, 475, '605450f3d7c84701b986fa31e1e9fa43', 'Gestion Pays', NULL, 'pays', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(685, 476, '3cd55a55307615d72aae84c6b5cf99bc', 'Ajouter Pays', NULL, 'addpays', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(686, 477, 'cfe617d7bc6a9c7d8b86c468f21396f2', 'Editer Pays', NULL, 'editpays', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(687, 478, 'b768486aeb655c48cc411c11fa60e150', 'Supprimer Pays', NULL, 'deletepays', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(688, 479, '15e4e24f320daa9d563ae62acff9e586', 'Valider Pays', NULL, 'validpays', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(689, 475, '29ba6cc689eca63dbafb109ec58bc4d6', 'Editer Pays', 'this_url', 'editpays', 'cogs', '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"editpays\"  ><i class=\"ace-icon fa fa-cogs bigger-100\"></i> Editer Pays</a></li>', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(690, 475, '763fe13212b4324590518773cd9a36fa', 'Valider Pays', 'this_exec', 'validpays', 'lock', '<li><a href=\"#\" class=\"this_exec\" data=\"%id%\" rel=\"validpays\"  ><i class=\"ace-icon fa fa-lock bigger-100\"></i> Valider Pays</a></li>', 0, '[-1-]', 0, 1, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(691, 475, '3c8427c7313d35219b17572efd380b17', 'Désactiver Pays', 'this_exec', 'validpays', 'unlock', '<li><a href=\"#\" class=\"this_exec\" data=\"%id%\" rel=\"validpays\"  ><i class=\"ace-icon fa fa-unlock bigger-100\"></i> Désactiver Pays</a></li>', 0, '[-1-]', 1, 0, 'Pays Validé', 'success', '<span class=\"label label-sm label-success\">Pays Validé</span>', NULL, NULL, NULL, NULL),
(692, 480, '312fd18860781a7b1b7e33587fa423d4', 'Gestion Type Echeance', NULL, 'type_echeance', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(693, 481, '76170b14c7b6f1f7058d079fe24f739b', 'Ajouter Type Echéance', NULL, 'addtype_echeance', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(694, 482, 'decc5ed58c4d91e6967c9c67e0975cf0', 'Editer Type Echéance', NULL, 'edittype_echeance', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(695, 483, '89db6f23dd8e96a69c6a97f556c44e14', 'Supprimer Type Echéance', NULL, 'deletetype_echeance', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(696, 484, '7527021168823e0118d44297c7684d44', 'Valider Type Echéance', NULL, 'validtype_echeance', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(697, 480, '46ad76148075d6b458f43e84ddf00791', 'Editer Type Echéance', 'this_url', 'edittype_echeance', 'cogs', '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"edittype_echeance\"  ><i class=\"ace-icon fa fa-cogs bigger-100\"></i> Editer Type Echéance</a></li>', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(698, 480, 'add2bf057924e606653fbf5bbd65ca09', 'Valider Type Echéance', 'this_exec', 'validtype_echeance', 'lock', '<li><a href=\"#\" class=\"this_exec\" data=\"%id%\" rel=\"validtype_echeance\"  ><i class=\"ace-icon fa fa-lock bigger-100\"></i> Valider Type Echéance</a></li>', 0, '[-1-]', 0, 1, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(699, 480, '463d9e1e8367736b958f0dd84b4e36d5', 'Désactiver Type Echéance', 'this_exec', 'validtype_echeance', 'unlock', '<li><a href=\"#\" class=\"this_exec\" data=\"%id%\" rel=\"validtype_echeance\"  ><i class=\"ace-icon fa fa-unlock bigger-100\"></i> Désactiver Type Echéance</a></li>', 0, '[-1-]', 1, 0, 'Type Echéance Validé', 'success', '<span class=\"label label-sm label-success\">Type Echéance Validé</span>', NULL, NULL, NULL, NULL),
(725, 502, '6beb279abea6434e3b73229aebadc081', 'Gestion Fournisseurs', NULL, 'fournisseurs', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(726, 503, 'd644015625a9603adb2fcc36167aeb73', 'Ajouter Fournisseur', NULL, 'addfournisseur', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(727, 504, '58c6694abfd3228d927a5d5a06d40b94', 'Editer Fournisseur', NULL, 'editfournisseur', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(728, 505, 'd072f81cd779e4b0152953241d713ca3', 'Supprimer Fournisseur', NULL, 'deletefournisseur', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(729, 506, '657351ce5aa227513e3b50dea77db918', 'Valider Fournisseur', NULL, 'validfournisseur', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(730, 502, 'ff95747f3a590b6539803f2a9a394cd5', 'Editer Fournisseur', 'this_url', 'editfournisseur', 'cogs', '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"editfournisseur\"  ><i class=\"ace-icon fa fa-cogs bigger-100\"></i> Editer Fournisseur</a></li>', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(731, 502, 'fea982f5074995d4ccd6211a71ab2680', 'Valider Fournisseur', 'this_exec', 'validfournisseur', 'lock', '<li><a href=\"#\" class=\"this_exec\" data=\"%id%\" rel=\"validfournisseur\"  ><i class=\"ace-icon fa fa-lock bigger-100\"></i> Valider Fournisseur</a></li>', 0, '[-1-]', 0, 1, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(732, 502, '1d0411a0dec15fc28f054f1a79d95618', 'Désactiver Fournisseur', 'this_exec', 'validfournisseur', 'unlock', '<li><a href=\"#\" class=\"this_exec\" data=\"%id%\" rel=\"validfournisseur\"  ><i class=\"ace-icon fa fa-unlock bigger-100\"></i> Désactiver Fournisseur</a></li>', 0, '[-1-]', 1, 0, 'Fournisseur Validé', 'success', '<span class=\"label label-sm label-success\">Fournisseur Validé</span>', NULL, NULL, NULL, NULL),
(736, 508, '83b693fe35a1be29edafe4f6170641aa', 'Détails Fournisseur', NULL, 'detailsfournisseur', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(737, 502, 'a52affdd109b9362ce47ff18aad53e2a', 'Détails Fournisseur', 'this_url', 'detailsfournisseur', 'eye', '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"detailsfournisseur\"  ><i class=\"ace-icon fa fa-eye bigger-100\"></i> Détails Fournisseur</a></li>', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(738, 502, 'c6fe5f222dd563204188e8bf0d69bd9e', 'Détails  Fournisseur', 'this_url', 'detailsfournisseur', 'eye', '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"detailsfournisseur\"  ><i class=\"ace-icon fa fa-eye bigger-100\"></i> Détails  Fournisseur</a></li>', 0, '[-1-]', 1, 0, 'Fournisseur Validé', 'success', '<span class=\"label label-sm label-success\">Fournisseur Validé</span>', NULL, NULL, NULL, NULL),
(810, 542, '72db1c2280dc3eb6405908c1c5b6c815', 'Information société', NULL, 'info_ste', NULL, '', 0, '[-1-3-]', 0, 0, 'Confirmé', 'success', '<span class=\"label label-sm label-success\">Confirmé</span>', NULL, NULL, NULL, NULL),
(811, 543, 'a1c5a2657cc1b2ff6f85c6fe8f1c51ac', 'Paramètrage Système', NULL, 'sys_setting', NULL, '', 0, '[-1-]', 0, 0, 'Rien', 'success', '<span class=\"label label-sm label-success\">Rien</span>', NULL, NULL, NULL, NULL),
(812, 543, 'de6285d9c0027ff8bccdf2af385ac337', 'Editer paramètre', 'this_url', 'edit_sys_setting', NULL, '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"edit_sys_setting\"  ><i class=\"ace-icon fa fa-pen blue bigger-100\"></i> Editer paramètre</a></li>', 0, '[-1-]', 1, 0, 'Active', 'success', '<span class=\"label label-sm label-success\">Active</span>', NULL, NULL, NULL, NULL),
(813, 544, '82f83d9d3d30fdef00d4c3ef96f0f899', 'Ajouter Paramètre', NULL, 'add_sys_setting', NULL, '', 0, '[-1-]', 1, 0, 'Confirmé', 'success', '<span class=\"label label-sm label-success\">Confirmé</span>', NULL, NULL, NULL, NULL),
(814, 545, 'f0e54f346e9dcfdff65274709ce2c8ca', 'Editer paramètre', NULL, 'edit_sys_setting', NULL, '', 0, '[-1-]', 1, 0, 'Validé', 'success', '<span class=\"label label-sm label-success\">Validé</span>', NULL, NULL, NULL, NULL),
(815, 546, 'aaccd24eaf085b8f18115c9c7653d401', 'Supprimer Paramètre', NULL, 'delete_sys_setting', NULL, '', 0, '[-1-]', 1, 0, 'Active', 'success', '<span class=\"label label-sm label-success\">Active</span>', NULL, NULL, NULL, NULL),
(920, 609, 'ec45512f34613446e7a2e367d4b4cfbd', 'Gestion Contrats Fournisseurs', NULL, 'contrats_fournisseurs', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(921, 609, 'e3c0d7e92dad7f8794b2415c334ec3ff', 'Editer Contrat', 'this_url', 'editcontrat_frn', NULL, '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"editcontrat_frn\"  ><i class=\"ace-icon fa fa-cogs bigger-100\"></i> Editer Contrat</a></li>', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(922, 609, '9dfff1c8dcb804837200f38e95381420', 'Valider Contrat', 'this_exec', 'validcontrat_frn', NULL, '<li><a href=\"#\" class=\"this_exec\" data=\"%id%\" rel=\"validcontrat_frn\"  ><i class=\"ace-icon fa fa-lock bigger-100\"></i> Valider Contrat</a></li>', 0, '[-1-]', 0, 1, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(923, 609, '9fe39b496077065105a57ccd9ed05863', 'Désactiver Contrat', 'this_exec', 'validcontrat_frn', NULL, '<li><a href=\"#\" class=\"this_exec\" data=\"%id%\" rel=\"validcontrat_frn\"  ><i class=\"ace-icon fa fa-unlock bigger-100\"></i> Désactiver Contrat</a></li>', 0, '[-1-]', 1, 0, 'Contrat Validé', 'success', '<span class=\"label label-sm label-success\">Contrat Validé</span>', NULL, NULL, NULL, NULL),
(924, 609, 'faee342ff51dbe9f835529ae5b9b2a0b', 'Détails  Contrat ', 'this_url', 'detailscontrat_frn', NULL, '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"detailscontrat_frn\"  ><i class=\"ace-icon fa fa-eye bigger-100\"></i> Détails  Contrat </a></li>', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(925, 609, '83406b6b206ed08878f2b2e854932ae5', 'Détails   Contrat  ', 'this_url', 'detailscontrat_frn', NULL, '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"detailscontrat_frn\"  ><i class=\"ace-icon fa fa-eye bigger-100\"></i> Détails   Contrat  </a></li>', 0, '[-1-]', 1, 0, 'Client Validé', 'success', '<span class=\"label label-sm label-success\">Client Validé</span>', NULL, NULL, NULL, NULL),
(926, 609, '8447888bef30fb983477cc1357ff7e6f', 'Détails    Contrat ', 'this_url', 'detailscontrat_frn', NULL, '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"detailscontrat_frn\"  ><i class=\"ace-icon fa fa-eye bigger-100\"></i> Détails    Contrat </a></li>', 0, '[-1-]', 3, 0, 'Contrat Expiré', 'info', '<span class=\"label label-sm label-info\">Contrat Expiré</span>', NULL, NULL, NULL, NULL),
(927, 609, '4cc1845128f6a5ff3ed01100292d8ebb', '  Détails    Contrat', 'this_url', 'detailscontrat_frn', NULL, '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"detailscontrat_frn\"  ><i class=\"ace-icon fa fa-eye bigger-100\"></i>   Détails    Contrat</a></li>', 0, '[-1-]', 2, 0, 'Attente Renouvelement', 'danger', '<span class=\"label label-sm label-danger\">Attente Renouvelement</span>', NULL, NULL, NULL, NULL),
(928, 609, 'cd82d84c5f70a633b10aae88c34e9159', '  Renouveler   Contrat ', 'this_url', 'renouveler_contrat', NULL, '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"renouveler_contrat\"  ><i class=\"ace-icon fa fa-exchange bigger-100\"></i>   Renouveler   Contrat </a></li>', 0, '[-1-]', 2, 1, 'Attente Renouvelement', 'danger', '<span class=\"label label-sm label-danger\">Attente Renouvelement</span>', NULL, NULL, NULL, NULL),
(929, 609, 'e9e994a0f8a204f1323fca7ce30931fe', ' Détails  Contrat ', 'this_url', 'detailscontrat_frn', NULL, '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"detailscontrat_frn\"  ><i class=\"ace-icon fa fa-eye bigger-100\"></i>  Détails  Contrat </a></li>', 0, '[-1-]', 4, 0, 'Contrat Expiré', 'inverse', '<span class=\"label label-sm label-inverse\">Contrat Expiré</span>', NULL, NULL, NULL, NULL),
(930, 609, 'b9e0a2a0236899590c72d31b878edfb2', ' Renouveler  Contrat ', 'this_url', 'renouveler_contrat', NULL, '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"renouveler_contrat\"  ><i class=\"ace-icon fa fa-exchange bigger-100\"></i>  Renouveler  Contrat </a></li>', 0, '[-1-]', 3, 0, 'Contrat Expiré', 'info', '<span class=\"label label-sm label-info\">Contrat Expiré</span>', NULL, NULL, NULL, NULL),
(931, 610, 'ded24eb817021c5a666a677b1565bc5e', 'Ajouter Contrat', NULL, 'addcontrat_frn', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(932, 611, 'ed6b8695494bf4ed86d5fb18690b3a59', 'Editer Contrat', NULL, 'editcontrat_frn', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(933, 612, 'b8a40913b5955209994aaa26d0e8c3d4', 'Supprimer Contrat', NULL, 'deletecontrat_frn', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(934, 613, '5efb874e7d73ccd722df806e8275770f', 'Valider Contrat', NULL, 'validcontrat_frn', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(935, 614, '64a5f976687a8c5f7cd3d672cc5d9c8c', 'Détails Contrat', NULL, 'detailscontrat_frn', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(936, 615, '2cc55c65e79534161108288adb00472b', 'Renouveler  Contrat', NULL, 'renouveler_contrat', NULL, '', 0, '[-1-]', 0, 0, 'Attente Renouvelement', 'danger', '<span class=\"label label-sm label-danger\">Attente Renouvelement</span>', NULL, NULL, NULL, NULL),
(978, 637, 'b8e62907d367fb44d644a5189cd07f42', 'Modules', NULL, 'modul', NULL, '', 1, 'null', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(979, 637, '05ce9e55686161d99e0714bb86243e5b', 'Editer Module', 'this_url', 'modul', NULL, '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"editmodul\" >\n      <i class=\"ace-icon fa fa-pencil bigger-100\"></i> Editer Module\n    </a></li>', 0, '-1-2-', 0, 1, NULL, NULL, '', NULL, NULL, NULL, NULL),
(980, 637, '819cf9c18a44cb80771a066768d585f2', 'Exporter Module', NULL, 'modul', NULL, '<li><a href=\"#\" class=\"export_mod\" data=\"%id%&export=1&mod=%id%\" rel=\"modul\" item=\"%id%\" >\n      <i class=\"ace-icon fa fa-download bigger-100\"></i> Exporter Module\n    </a></li>', 0, '-1-2-', 0, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(981, 637, 'd2fc3ee15cee5208a8b9c70f1e53c196', 'Liste task modul', 'this_url', 'modul', NULL, '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"task\" >\n     <i class=\"ace-icon fa fa-external-link bigger-100\"></i>Application associes\n    </a></li>', 0, '-1-2-', 0, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(982, 637, 'ad75e6b877f20e3d6fc1789da4dcb3e6', 'Editer Module', 'this_url', 'modul', NULL, '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"editmodulsetting\"  ><i class=\"ace-icon fa fa-pencil bigger-100\"></i> Editer Module</a></li>', 0, '[-1-]', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(983, 637, '064a9b0eff1006fd4f25cb4eaf894ca1', 'Liste task modul Setting', 'this_url', 'modul', NULL, '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"task\" >\n     <i class=\"ace-icon fa fa-external-link bigger-100\"></i>Application associes\n    </a></li>', 0, '-1-2-', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(984, 637, 'ac4eb0c94da00a48ad5d995f5e9e9366', 'MAJ Module', 'this_exec', 'update_module', NULL, '<li><a href=\"#\" class=\"this_exec\" data=\"%id%\" rel=\"update_module\"  ><i class=\"ace-icon fa fa-pencil-square-o bigger-100\"></i> MAJ Module</a></li>', 0, '[-1-]', 0, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(985, 638, '44bd5341b0ab41ced21db8b3e92cf5aa', 'Ajouter un Modul', NULL, 'addmodul', NULL, '', 1, '-1-2-', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(986, 640, '8653b156f1a4160a12e5a94b211e59a2', 'Liste Action Task', 'this_url', 'task', NULL, '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"taskaction\"  >\n     <i class=\"ace-icon fa fa-external-link bigger-100\"></i>Application associes\n    </a></li>', 0, '-1-2-', 0, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(987, 640, '86aced763bc02e1957a5c740fb37b4f7', 'Supprimer Application', 'this_exec', 'task', NULL, '<li><a href=\"#\" class=\"this_exec\" data=\"%id%\" rel=\"task\"  ><i class=\"ace-icon fa fa-draft bigger-100\"></i> Supprimer Application</a></li>', 0, '[-1-]', 0, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(988, 640, 'f07352e32fe86da1483c6ab071b7e7a9', 'Ajout Affichage WF', 'this_url', 'task', NULL, '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"addetatrule\"  ><i class=\"ace-icon fa fa-eye bigger-100\"></i> Ajout Affichage WF</a></li>', 0, '[-1-]', 0, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(989, 641, '1c452aff8f1551b3574e15b74147ea56', 'Ajouter Task Modul', NULL, 'addtask', NULL, '', 1, '-1-2-', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(990, 642, 'f085fe4610576987db963501297e4d91', 'Editer Task Modul', NULL, 'edittask', NULL, '', 1, '-1-2-', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(991, 642, '38702c272a6f4d334c2f4c3684c8b163', 'Ajouter action modul', NULL, 'edittask', NULL, '', 1, '-1-2-', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(992, 643, 'cbae1ebe850f6dd8841426c6fedf1785', 'Liste Action Task', NULL, 'taskaction', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(993, 643, 'e30471396f9b86ccdcc94943d80b679a', 'Editer Task Action', 'this_url', 'taskaction', NULL, '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"edittaskaction\"  ><i class=\"ace-icon fa fa-pencil bigger-100\"></i> Editer Task Action</a></li>', 0, '[-1-]', 0, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(994, 644, '502460cd9327b46ee7af0a258ebf8c80', 'Ajouter Action Task', NULL, 'addtaskaction', NULL, '', 1, '[-1-3-]', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(995, 645, '13c107211904d4a2e65dd65c60ec7272', 'Supprimer Application', NULL, 'deletetask', NULL, '', 1, 'null', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(996, 646, '8c8acf9cf3790b16b1fae26823f45eab', 'Importer des modules', NULL, 'importmodul', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(997, 647, '2f4518dab90b706e2f4acd737a0425d8', 'Ajouter Module paramétrage', NULL, 'addmodulsetting', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(998, 648, '8e0c0212d8337956ac2f4d6eb180d74b', 'Editer Module paramètrage', NULL, 'editmodulsetting', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(999, 649, 'fc54953b47b7fcb11cc14c0c2e2125f0', 'Ajouter Autorisation Etat', NULL, 'addetatrule', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(1000, 650, '966ec2dd83e6006c2d0ff1d1a5f12e33', 'Editer Task Action', NULL, 'edittaskaction', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(1001, 651, '3473119f6683893a3f1372dbf7d811e1', 'MAJ Module', NULL, 'update_module', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(1002, 652, '2e2346bd422536c1d996ff25f9e71357', 'Dupliquer Action Task', NULL, 'dupliqtaskaction', NULL, '', 0, '[-1-]', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(1003, 653, '8a3634181ae5bc9223b689a310158962', 'Supprimer Task action', NULL, 'deletetaskaction', NULL, '', 0, '[-1-]', 0, 0, 'Not Message', 'success', '<span class=\"label label-sm label-success\">Not Message</span>', NULL, NULL, NULL, NULL),
(1004, 654, '8afb3c669307183cd3b7d189fbf204d7', 'Affichage Work Flow', NULL, 'workflow', NULL, '', 0, '[-1-]', 0, 0, 'Work flow', 'success', '<span class=\"label label-sm label-success\">Work flow</span>', NULL, NULL, NULL, NULL),
(1005, 655, '0e79510db7f03b9b6266fc7b4a612153', 'Gestion Devis', NULL, 'devis', NULL, '', 1, '[-1-2-]', 0, 1, 'Attente validation', 'warning', '<span class=\"label label-sm label-warning\">Attente validation</span>', NULL, NULL, NULL, NULL),
(1006, 655, 'c15b00a1e37657336df8b6aa0eea2db5', 'Modifier Devis', 'this_url', 'editdevis', NULL, '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"editdevis\"  ><i class=\"ace-icon fa fa-pencil-square-o blue bigger-100\"></i> Modifier Devis</a></li>', 0, '[-1-2-]', 0, 1, 'Attente validation', 'warning', '<span class=\"label label-sm label-warning\">Attente validation</span>', NULL, NULL, NULL, NULL),
(1007, 655, '998fb803f2e64f22418b3b388d6240a4', 'Envoi Devis au client', 'this_exec', 'validdevis', 'envelope blue', '<li><a href=\"#\" class=\"this_exec\" data=\"%id%\" rel=\"validdevis\"  ><i class=\"ace-icon fa fa-envelope blue bigger-100\"></i> Envoi Devis au client</a></li>', 0, '[-1-2-3-]', 1, 1, 'Attente Expédition', 'info', '<span class=\"label label-sm label-info\">Attente Expédition</span>', NULL, NULL, NULL, NULL),
(1008, 655, '28e267a2a0647d4cb37b18abb1e7d051', 'Voir détails', 'this_url', 'viewdevis', NULL, '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"viewdevis\"  ><i class=\"ace-icon fa fa-eye bigger-100\"></i> Voir détails</a></li>', 0, '[-1-2-3-5-]', 1, 1, 'Attente Expédition', 'info', '<span class=\"label label-sm label-info\">Attente Expédition</span>', NULL, NULL, NULL, NULL),
(1009, 655, 'd34b07afd92adad84e1c4c2ebd92ba95', 'Voir détails', 'this_url', 'viewdevis', NULL, '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"viewdevis\"  ><i class=\"ace-icon fa fa-eye bigger-100\"></i> Voir détails</a></li>', 0, '[-1-2-3-5-]', 0, 1, 'Attente validation', 'warning', '<span class=\"label label-sm label-warning\">Attente validation</span>', NULL, NULL, NULL, NULL),
(1010, 655, 'f6f55b9d0ba9d704b2861d57cda32477', 'Réponse Client', 'this_url', 'validdevisclient', NULL, '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"validdevisclient\"  ><i class=\"ace-icon fa fa-check green bigger-100\"></i> Réponse Client</a></li>', 0, '[-1-2-3-5-]', 2, 0, 'Attente réponse client', 'info', '<span class=\"label label-sm label-info\">Attente réponse client</span>', NULL, NULL, NULL, NULL),
(1011, 655, '4b11c0bfb3f970a541100f7fc334927e', 'Voir détails', 'this_url', 'viewdevis', NULL, '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"viewdevis\"  ><i class=\"ace-icon fa fa-eye bigger-100\"></i> Voir détails</a></li>', 0, '[-1-2-3-5-]', 3, 1, 'Demande modification', 'info', '<span class=\"label label-sm label-info\">Demande modification</span>', NULL, NULL, NULL, NULL),
(1012, 655, '61a0655c2c13039b5b8262b82ae6cb51', 'Voir détails', 'this_url', 'viewdevis', NULL, '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"viewdevis\"  ><i class=\"ace-icon fa fa-eye bigger-100\"></i> Voir détails</a></li>', 0, '[-1-2-3-5-]', 2, 0, 'Attente réponse client', 'info', '<span class=\"label label-sm label-info\">Attente réponse client</span>', NULL, NULL, NULL, NULL),
(1013, 655, 'ed30dfbb21d8d24a0da432252358bdf8', 'Voir détails', 'this_url', 'viewdevis', NULL, '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"viewdevis\"  ><i class=\"ace-icon fa fa-eye bigger-100\"></i> Voir détails</a></li>', 0, '[-1-2-3-5-]', 4, 0, 'Devis Validé par le client', 'success', '<span class=\"label label-sm label-success\">Devis Validé par le client</span>', NULL, NULL, NULL, NULL),
(1014, 655, '7bd2e025ffb3893dea4776e152301716', 'Débloquer devis', 'this_exec', 'debloqdevis', NULL, '<li><a href=\"#\" class=\"this_exec\" data=\"%id%\" rel=\"debloqdevis\"  ><i class=\"ace-icon fa fa-unlock blue bigger-100\"></i> Débloquer devis</a></li>', 0, '[-1-3-]', 3, 1, 'Demande modification', 'info', '<span class=\"label label-sm label-info\">Demande modification</span>', NULL, NULL, NULL, NULL),
(1015, 655, '6d9ec7d9ebebcdc921376e7bf0c9fdaf', 'Valider devis', 'this_exec', 'validdevis', NULL, '<li><a href=\"#\" class=\"this_exec\" data=\"%id%\" rel=\"validdevis\"  ><i class=\"ace-icon fa fa-check green bigger-100\"></i> Valider devis</a></li>', 0, '[-1-3-]', 0, 1, 'Attente validation', 'warning', '<span class=\"label label-sm label-warning\">Attente validation</span>', NULL, NULL, NULL, NULL);
INSERT INTO `task_action` (`id`, `appid`, `idf`, `descrip`, `mode_exec`, `app`, `class`, `code`, `type`, `service`, `etat_line`, `notif`, `etat_desc`, `message_class`, `message_etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(1016, 655, 'b1bcc4a4ab154f110abcf54c0c659fb3', 'Voir détails', 'this_url', 'viewdevis', NULL, '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"viewdevis\"  ><i class=\"ace-icon fa fa-eye bigger-100\"></i> Voir détails</a></li>', 0, '[-1-2-3-5-]', 5, 0, 'Devis rejeté par le client', 'danger', '<span class=\"label label-sm label-danger\">Devis rejeté par le client</span>', NULL, NULL, NULL, NULL),
(1017, 655, '91a90a46e3430c491ab8db654b6e87c4', 'Voir détails', 'this_url', 'viewdevis', NULL, '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"viewdevis\"  ><i class=\"ace-icon fa fa-eye bigger-100\"></i> Voir détails</a></li>', 0, '[-1-2-3-5-]', 6, 0, 'Devis Expiré', 'inverse', '<span class=\"label label-sm label-inverse\">Devis Expiré</span>', NULL, NULL, NULL, NULL),
(1018, 656, 'd9eeb330625c1b87e0df00986a47be01', 'Ajouter Devis', NULL, 'adddevis', NULL, '', 0, '[-1-2-]', 0, 0, 'Brouillon', 'success', '<span class=\"label label-sm label-success\">Brouillon</span>', NULL, NULL, NULL, NULL),
(1019, 657, 'da93cdb05137e15aed9c4c18bddd746a', 'Ajouter détail devis', NULL, 'add_detaildevis', NULL, '', 0, '[-1-2-]', 0, 0, 'Attente validation', 'success', '<span class=\"label label-sm label-success\">Attente validation</span>', NULL, NULL, NULL, NULL),
(1020, 658, 'f9f3c299f9bd0fec014f6bd3f0e06adb', 'Modifier Devis', NULL, 'editdevis', NULL, '', 0, '[-1-2-]', 0, 0, 'Attente validation', 'warning', '<span class=\"label label-sm label-warning\">Attente validation</span>', NULL, NULL, NULL, NULL),
(1021, 659, 'e14cce6f1faf7784adb327581c516b90', 'Supprimer Devis', NULL, 'deletedevis', NULL, '', 0, '[-1-3-]', 0, 0, 'Attente validation', 'warning', '<span class=\"label label-sm label-warning\">Attente validation</span>', NULL, NULL, NULL, NULL),
(1022, 660, '38f10871792c133ebcc6040e9a11cde8', 'Modifier détail Devis', NULL, 'edit_detaildevis', NULL, '', 0, '[-1-2-3-]', 0, 0, 'Attente validation', 'warning', '<span class=\"label label-sm label-warning\">Attente validation</span>', NULL, NULL, NULL, NULL),
(1023, 661, '8def42e75fd4aee61c378d9fb303850d', 'Afficher détail devis', NULL, 'viewdevis', NULL, '', 0, '[-1-2-3-4-18-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1024, 662, '7666e87783b0f5a7eec1eea7593f7dfe', 'Valider Devis', NULL, 'validdevis', NULL, '', 0, '[-1-2-3-5-4-]', 0, 0, 'Attente validation', 'success', '<span class=\"label label-sm label-success\">Attente validation</span>', NULL, NULL, NULL, NULL),
(1025, 663, '7f1c48324d8c369da9aa6ab8af35acd8', 'Validation Client Devis', NULL, 'validdevisclient', NULL, '', 0, '[-1-2-3-5-]', 0, 0, 'Attente validation client', 'info', '<span class=\"label label-sm label-info\">Attente validation client</span>', NULL, NULL, NULL, NULL),
(1026, 664, '6adf896091dde0df89f777f31606953c', 'Débloquer devis', NULL, 'debloqdevis', NULL, '', 0, '[-1-3-]', 0, 0, 'Débloquer Devis', 'inverse', '<span class=\"label label-sm label-inverse\">Débloquer Devis</span>', NULL, NULL, NULL, NULL),
(1027, 665, '15cbb79dd4a74266158e6b29a83e683c', 'Archiver Devis', NULL, 'archivdevis', NULL, '', 1, '[-1-3-]', 0, 0, 'Devis archivé', 'inverse', '<span class=\"label label-sm label-inverse\">Devis archivé</span>', NULL, NULL, NULL, NULL),
(1047, 675, '4c924acb9adc87d8389e8f9842a965c5', 'Gestion des factures', NULL, 'factures', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(1048, 675, '98a697ec628778765b25e02ba2929d38', 'Liste complément', 'this_url', 'complements', NULL, '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"complements\"  ><i class=\"ace-icon fa fa-circle bigger-100\"></i> Liste complément</a></li>', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1049, 675, 'f8b20f7fec99b45b967a431d64b7f061', 'Liste encaissements', 'this_url', 'encaissements', NULL, '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"encaissements\"  ><i class=\"ace-icon fa fa-euro bigger-100\"></i> Liste encaissements</a></li>', 0, '[-1-]', 2, 0, 'Attente Payement', 'info', '<span class=\"label label-sm label-info\">Attente Payement</span>', NULL, NULL, NULL, NULL),
(1050, 675, '9a51fb5298e39a28af3ad6272fc51177', 'Valider facture', 'this_exec', 'validfacture', NULL, '<li><a href=\"#\" class=\"this_exec\" data=\"%id%\" rel=\"validfacture\"  ><i class=\"ace-icon fa fa-check bigger-100\"></i> Valider facture</a></li>', 0, '[-1-]', 0, 1, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(1051, 675, '851f1d4c13f6025f69f5b9315321d350', 'Désactiver facture', 'this_exec', 'rejectfacture', NULL, '<li><a href=\"#\" class=\"this_exec\" data=\"%id%\" rel=\"rejectfacture\"  ><i class=\"ace-icon fa fa-unlock bigger-100\"></i> Désactiver facture</a></li>', 0, '[-1-]', 1, 0, 'Attente Envoi Client', 'success', '<span class=\"label label-sm label-success\">Attente Envoi Client</span>', NULL, NULL, NULL, NULL),
(1052, 675, '5c79105956d28b5cac52f85784039919', 'Détail facture', 'this_url', 'detailsfacture', NULL, '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"detailsfacture\"  ><i class=\"ace-icon fa fa-eye bigger-100\"></i> Détail facture</a></li>', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class=\"label label-sm label-warning\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(1053, 675, '7892721423af84a0b54e90250cf27ee3', 'Détails Facture', 'this_url', 'detailsfacture', NULL, '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"detailsfacture\"  ><i class=\"ace-icon fa fa-eye bigger-100\"></i> Détails Facture</a></li>', 0, '[-1-]', 1, 0, 'Attente Envoi Client', 'success', '<span class=\"label label-sm label-success\">Attente Envoi Client</span>', NULL, NULL, NULL, NULL),
(1054, 675, 'b5380d403c9947ce060963f28e6d7539', 'Envoyer au client', 'this_exec', 'validfacture', NULL, '<li><a href=\"#\" class=\"this_exec\" data=\"%id%\" rel=\"validfacture\"  ><i class=\"ace-icon fa fa-check bigger-100\"></i> Envoyer au client</a></li>', 0, '[-1-]', 1, 0, 'Attente Payement', 'info', '<span class=\"label label-sm label-info\">Attente Payement</span>', NULL, NULL, NULL, NULL),
(1055, 675, '80a4b2643b95c2836e968411811d3c21', 'Détails facture', 'this_url', 'detailsfacture', NULL, '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"detailsfacture\"  ><i class=\"ace-icon fa fa-eye bigger-100\"></i> Détails facture</a></li>', 0, '[-1-]', 2, 0, 'Attente Payement', 'info', '<span class=\"label label-sm label-info\">Attente Payement</span>', NULL, NULL, NULL, NULL),
(1056, 675, '2f679be3c0d7b88529209f86745f9028', 'Détails facture', 'this_url', 'detailsfacture', NULL, '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"detailsfacture\"  ><i class=\"ace-icon fa fa-eye bigger-100\"></i> Détails facture</a></li>', 0, '[-1-]', 3, 0, 'Payé Partiellement', 'inverse', '<span class=\"label label-sm label-inverse\">Payé Partiellement</span>', NULL, NULL, NULL, NULL),
(1057, 675, '429558e9a1e899c11051ea5c9a4f7942', 'Détails facture', 'this_url', 'detailsfacture', NULL, '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"detailsfacture\"  ><i class=\"ace-icon fa fa-eye bigger-100\"></i> Détails facture</a></li>', 0, '[-1-]', 4, 0, 'Facture Payée', 'danger', '<span class=\"label label-sm label-danger\">Facture Payée</span>', NULL, NULL, NULL, NULL),
(1058, 675, '3acd11d8d74fb7e1ba8d5721e96f91bd', 'Liste encaissements', 'this_url', 'encaissements', NULL, '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"encaissements\"  ><i class=\"ace-icon fa fa-euro bigger-100\"></i> Liste encaissements</a></li>', 0, '[-1-]', 3, 0, 'Payé partiellement', 'inverse', '<span class=\"label label-sm label-inverse\">Payé partiellement</span>', NULL, NULL, NULL, NULL),
(1059, 676, '55c3c5d2d93143b315513b7401043c8b', 'complements', NULL, 'complements', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1060, 676, 'dfc4772cc03cf0b92a47f54fc6a2326e', 'Modifier complément', 'this_url', 'editcomplement', NULL, '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"editcomplement\"  ><i class=\"ace-icon fa fa-cogs bigger-100\"></i> Modifier complément</a></li>', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1061, 677, '03a18bdd5201e433a3c523a2b34d059a', 'Ajouter complément', NULL, 'addcomplement', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1062, 678, '88d9bc979cd1102eb8196e7f5e6042ca', 'Encaissement', NULL, 'encaissements', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1063, 678, 'c690cc68f5257c0c225b8b8e6126ea56', 'Modifier encaissement', 'this_url', 'editencaissement', NULL, '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"editencaissement\"  ><i class=\"ace-icon fa fa-pencil-square-o bigger-100\"></i> Modifier encaissement</a></li>', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1064, 678, '1dc06f602e8630f273d44aa2751b2127', 'Détails encaissement', 'this_url', 'detailsencaissement', NULL, '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"detailsencaissement\"  ><i class=\"ace-icon fa fa-eye bigger-100\"></i> Détails encaissement</a></li>', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1065, 679, 'e4866b292dbc3c9c5d9cc37273a5b498', 'Ajouter encaissement', NULL, 'addencaissement', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1066, 680, '8665be10959f39df4f149962eb70041f', 'Modifier complément', NULL, 'editcomplement', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1067, 681, '585d411904bf7d9e83d21b2810ff1d6c', 'Modifier encaissement', NULL, 'editencaissement', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1068, 682, '8c8b058a4d030cdc8b49c9008abb2e92', 'Supprimer complément', NULL, 'deletecomplement', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', 'Attente de validation', NULL, NULL, NULL, NULL),
(1069, 683, '6bf7d5180940f03567a5d711e8563ba4', 'Supprimer encaissement', NULL, 'deleteencaissement', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1070, 684, '256abad0ec8e3bc8ed1c0653ff177255', 'Valider facture', NULL, 'validfacture', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1071, 685, 'b5dc5719c1f96df7334f371dcf51a5b6', 'Détail encaissement', NULL, 'detailsencaissement', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1072, 686, '16fbf6fdcbb72f863bcf7e4ef28d8e75', 'Détails facture', NULL, 'detailsfacture', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1073, 687, '5efdeb41007109ca99f23f0756217827', 'Désactiver Facture', NULL, 'rejectfacture', NULL, '', 0, '[-1-]', 0, 0, 'Facture Validée', 'success', '<span class=\"label label-sm label-success\">Facture Validée</span>', NULL, NULL, NULL, NULL),
(1096, 700, '899d40c8f22d4f7a6f048366f1829787', 'Gestion des contrats', NULL, 'contrats', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1097, 700, '4aea0d5a7bdb0e2513897507947fc3de', 'Modifier  contrat', 'this_url', 'editcontrat', 'pencil-square', '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"editcontrat\"  ><i class=\"ace-icon fa fa-pencil-square bigger-100\"></i> Modifier  contrat</a></li>', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1098, 700, '4ccf7c3c72dfa25157ab01762069929e', 'Détail  contrat', 'this_url', 'detailcontrat', 'eye', '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"detailcontrat\"  ><i class=\"ace-icon fa fa-eye bigger-100\"></i> Détail  contrat</a></li>', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1099, 700, '18c5260f189a488c59134c1d53270dae', 'Valider  contrat', 'this_exec', 'validcontrat', 'check-square-o', '<li><a href=\"#\" class=\"this_exec\" data=\"%id%\" rel=\"validcontrat\"  ><i class=\"ace-icon fa fa-check-square-o bigger-100\"></i> Valider  contrat</a></li>', 0, '[-1-]', 0, 1, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1101, 700, '6ca83d9c6c0b229446da30b60b74031a', 'Détails  Contrat', 'this_url', 'detailcontrat', 'eye', '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"detailcontrat\"  ><i class=\"ace-icon fa fa-eye bigger-100\"></i> Détails  Contrat</a></li>', 0, '[-1-]', 1, 0, 'Contrat validé', 'success', '<span class=\"label label-sm label-success\">Contrat validé</span>', NULL, NULL, NULL, NULL),
(1102, 700, '52eef475bfa2afb7eb065329a93b0b4c', 'Renouveler  Contrat', 'this_url', 'renouvelercontrat', 'exchange', '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"renouvelercontrat\"  ><i class=\"ace-icon fa fa-exchange bigger-100\"></i> Renouveler  Contrat</a></li>', 0, '[-1-]', 2, 1, 'Attente Renouvelement', 'danger', '<span class=\"label label-sm label-danger\">Attente Renouvelement</span>', NULL, NULL, NULL, NULL),
(1103, 700, 'b23939959d533fa68091fca749b691aa', 'Détails Contrat ', 'this_url', 'detailcontrat', 'file', '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"detailcontrat\"  ><i class=\"ace-icon fa fa-file bigger-100\"></i> Détails Contrat </a></li>', 0, '[-1-]', 3, 0, 'Contrat Expiré', 'info', '<span class=\"label label-sm label-info\">Contrat Expiré</span>', NULL, NULL, NULL, NULL),
(1104, 700, 'b6cc6622e5874a5c0a04e2103d8a7dd0', ' Détails    Contrat', 'this_url', 'detailcontrat', 'exchange', '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"detailcontrat\"  ><i class=\"ace-icon fa fa-exchange bigger-100\"></i>  Détails    Contrat</a></li>', 0, '[-1-]', 2, 0, 'Attente Renouvelement', 'danger', '<span class=\"label label-sm label-danger\">Attente Renouvelement</span>', NULL, NULL, NULL, NULL),
(1105, 700, 'c58a3038be080d0c6cdf89e0fd0a8c71', 'Détails  Contrat', 'this_url', 'detailcontrat', 'file', '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"detailcontrat\"  ><i class=\"ace-icon fa fa-file bigger-100\"></i> Détails  Contrat</a></li>', 0, '[-1-]', 4, 0, 'Contrat Expiré', 'inverse', '<span class=\"label label-sm label-inverse\">Contrat Expiré</span>', NULL, NULL, NULL, NULL),
(1106, 700, '656d41ad5452611636a5d9f966729e39', 'Renouveler Contrat', 'this_url', 'renouvelercontrat', NULL, '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"renouvelercontrat\"  ><i class=\"ace-icon fa fa-exchange bigger-100\"></i> Renouveler Contrat</a></li>', 0, '[-1-]', 3, 0, 'Contrat Expiré', 'info', '<span class=\"label label-sm label-info\">Contrat Expiré</span>', NULL, NULL, NULL, NULL),
(1107, 701, '87f4c3ed4713c3bc9e3fef60a6649055', 'Ajouter contrat', NULL, 'addcontrat', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1108, 702, '9e49a431d9637544cefa2869fd7278b9', 'Modifier contrat', NULL, 'editcontrat', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1109, 703, '1e9395a182a44787e493bc038cd80bbf', 'Supprimer contrat', NULL, 'deletecontrat', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1110, 704, '460d92834715b149c4db28e1643bd932', 'Valider contrat', NULL, 'validcontrat', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1111, 705, 'bbcf2879c2f8f60cfa55fa97c6e79268', 'Détail contrat', NULL, 'detailcontrat', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1112, 706, 'fe058ccb890b25a54866be7f24a40363', 'Ajouter échéance ', NULL, 'addecheance_contrat', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1113, 707, '36a248f56a6a80977e5c90a5c59f39d3', 'Modifier échéance contrat', NULL, 'editecheance_contrat', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1114, 708, 'f0567980556249721f24f2fc88ebfed5', 'Renouveler Contrat', NULL, 'renouvelercontrat', NULL, '', 0, '[-1-]', 0, 0, 'Attente Renouvelement', 'danger', '<span class=\"label label-sm label-danger\">Attente Renouvelement</span>', NULL, NULL, NULL, NULL),
(1115, 709, '56de23d30d6c54297c8d9772cd3c7f57', 'Utilisateurs', NULL, 'user', NULL, '', 1, '-1-2-3-', 1, 0, 'Actif', 'success', '<span class=\"label label-sm label-success\">Actif</span>', NULL, NULL, NULL, NULL),
(1116, 709, 'e656756fb7b39a4e6ddcabca75ff2970', 'Editer Utilisateur', 'this_url', 'user', NULL, '<li><a href=\"#\" class=\"this_url\" redi=\"user\" data=\"%id%\" rel=\"edituser\" >\n     <i class=\"ace-icon fa fa-pencil bigger-100\"></i> Editer compte\n   </a></li>', 0, '-1-2-3-', 1, 0, 'Actif', 'success', '<span class=\"label label-sm label-success\">Actif</span>', NULL, NULL, NULL, NULL),
(1117, 709, 'c073a277957ca1b9f318ac3902555708', 'Permissions', 'this_url', 'user', NULL, '<li><a href=\"#\" class=\"this_url\" redi=\"user\" data=\"%id%\" rel=\"rules\"  >\n     <i class=\"ace-icon fa fa-key bigger-100\"></i> Permission compte\n    </a></li>', 0, '-1-2-3-', 1, 0, 'Actif', 'success', '<span class=\"label label-sm label-success\">Actif</span>', NULL, NULL, NULL, NULL),
(1118, 709, 'c51499ddf7007787c4434661c658bbd1', 'Désactiver compte', 'this_exec', 'user', NULL, '<li><a href=\"#\" class=\"this_exec\" data=\"%id%\" rel=\"activeuser\" ><i class=\"ace-icon fa fa-lock bigger-100\"></i> Désactiver utilisateur</a></li>', 0, '-1-3-', 1, 0, 'Actif', 'success', '<span class=\"label label-sm label-success\">Actif</span>', NULL, NULL, NULL, NULL),
(1119, 709, '10096b6f54456bcfc85081523ee64cf6', 'Supprimer utilisateur', 'this_exec', 'user', NULL, '<li><a href=\"#\" class=\"this_exec\" data=\"%id%\" rel=\"delete_user\" ><i class=\"ace-icon fa fa-trash red bigger-100\"></i> Supprimer utilisateur</a></li>', 0, '-1-2-3-', 1, 0, 'Actif', 'success', '<span class=\"label label-sm label-success\">Actif</span>', NULL, NULL, NULL, NULL),
(1120, 709, 'a0999cbed820aff775adf27276ee54a4', 'Editer Utilisateur', 'this_url', 'user', NULL, '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"edituser\" ><i class=\"ace-icon fa fa-users bigger-100\"></i> Editer compte</a></li>', 0, '-1-2-3-', 0, 0, 'Attente Validation', 'danger', '<span class=\"label label-sm label-danger\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(1121, 709, '9aa6877656339ddff2478b20449a924b', 'Activer compte', 'this_exec', 'user', NULL, '<li><a href=\"#\" class=\"this_exec\" data=\"%id%\" rel=\"activeuser\" ><i class=\"ace-icon fa fa-unlock bigger-100\"></i> Activer utilisateur</a></li>', 0, '-1-2-3-', 0, 1, 'Attente Validation', 'danger', '<span class=\"label label-sm label-danger\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(1122, 709, 'f4c79bb797b92dfa826b51a44e3171af', 'Utilisateurs', NULL, 'user', NULL, '', 0, '-1-2-3-', 0, 1, 'Attente Validation', 'danger', '<span class=\"label label-sm label-danger\">Attente Validation</span>', NULL, NULL, NULL, NULL),
(1123, 709, 'd7f7afd70a297e5c239f6cf271138390', 'Utilisateur Archivé', NULL, 'user', NULL, 'dddd', 0, '-1-2-3-', 2, 0, 'Archivé', 'inverse', '<span class=\"label label-sm label-inverse\">Archivé</span>', NULL, NULL, NULL, NULL),
(1124, 709, '17c98287fb82388423e04d24404cf662', 'Permissions', 'this_url', 'rules', NULL, '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"rules\"  ><i class=\"ace-icon fa fa-lock bigger-100\"></i> Permissions</a></li>', 0, '[-1-]', 0, 1, 'Attente activation', 'danger', '<span class=\"label label-sm label-danger\">Attente activation</span>', NULL, NULL, NULL, NULL),
(1125, 709, '2c1c5556f30585c5b7c93fbbeaa98595', 'Historique session', 'this_url', 'history', NULL, '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"history\"  ><i class=\"ace-icon fa fa-clock-o blue bigger-100\"></i> Historique session</a></li>', 0, '[-1-3-]', 1, 0, 'Compte Active', 'success', '<span class=\"label label-sm label-success\">Compte Active</span>', NULL, NULL, NULL, NULL),
(1126, 709, '7fd4ca84d162b70d3a4f0bad73e0e4b3', 'Liste Activitées', 'this_url', 'activities', NULL, '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"activities\"  ><i class=\"ace-icon fa fa-exchange blue bigger-100\"></i> Liste Activitées</a></li>', 0, '[-1-3-]', 1, 0, 'Compte Actif', 'success', '<span class=\"label label-sm label-success\">Compte Actif</span>', NULL, NULL, NULL, NULL),
(1127, 710, 'df91a8e6f8ee2cde64495fc0cc7d6c6f', 'Ajouter Utilisateurs', NULL, 'adduser', NULL, '', 1, '-1-2-', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(1128, 711, '2bb46b52eab9eecbdbba35605da07234', 'Editer Utilisateurs', NULL, 'edituser', NULL, '', 1, '-1-2-', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(1129, 712, '3f59a1326df27378304e142ab3bec090', 'Permission', NULL, 'rules', NULL, '', 1, '-1-2-', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(1130, 713, 'b919571c88d036f8889742a81a4f41fd', 'Supprimer utilisateur', NULL, 'delete_user', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(1131, 714, '38f89764a26e39ce029cd3132c12b2a5', 'Compte utilisateur', NULL, 'compte', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(1132, 715, 'f988a608f35a0bc551cb038b1706d207', 'Activer utilisateur', NULL, 'activeuser', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(1133, 716, 'b7b3a09fdd73a5b0a3e5ed8a2828f548', 'Désactiver l\'utilisateur', NULL, 'archiv_user', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(1134, 717, '0d374b7e2fe21a2e2641c092a3c7f2e9', 'Changer le mot de passe', NULL, 'changepass', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(1135, 718, '6f642ee30722158f0318653b9113b887', 'History', NULL, 'history', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(1136, 719, 'cc907fac13631903d129c137d671d718', 'Activities', NULL, 'activities', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(1137, 720, '1eb847d87adcad78d5e951e6110061e5', 'Gestion Proforma', NULL, 'proforma', NULL, '', 0, '[-1-2-3-5-4-]', 0, 0, 'Attente validation', 'warning', '<span class=\"label label-sm label-warning\">Attente validation</span>', NULL, NULL, NULL, NULL),
(1138, 720, '44ef6849d8d5d17d8e0535187e923d32', 'Editer proforma', 'this_url', 'editproforma', NULL, '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"editproforma\"  ><i class=\"ace-icon fa fa-pen blue bigger-100\"></i> Editer proforma</a></li>', 0, '[-1-2-3-5-]', 0, 1, 'Attente validation', 'warning', '<span class=\"label label-sm label-warning\">Attente validation</span>', NULL, NULL, NULL, NULL),
(1139, 720, 'b7ce06be726011362a271678547a803c', 'Valider Proforma', 'this_exec', 'validproforma', NULL, '<li><a href=\"#\" class=\"this_exec\" data=\"%id%\" rel=\"validproforma\"  ><i class=\"ace-icon fa fa-check bigger-100\"></i> Valider Proforma</a></li>', 0, '[-1-3-5-]', 0, 1, 'Attente validation', 'warning', '<span class=\"label label-sm label-warning\">Attente validation</span>', NULL, NULL, NULL, NULL),
(1140, 720, 'abd8c50f1d2ef4beeeddb68a72973587', 'Détail Proforma', 'this_url', 'viewproforma', NULL, '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"viewproforma\"  ><i class=\"ace-icon fa fa-eye blue bigger-100\"></i> Détail Proforma</a></li>', 0, '[-1-2-3-5-]', 0, 0, 'Attente validation', 'warning', '<span class=\"label label-sm label-warning\">Attente validation</span>', NULL, NULL, NULL, NULL),
(1141, 720, '35a88b5c359908b063ac98cafc622987', 'Détail Proforma', 'this_url', 'viewproforma', NULL, '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"viewproforma\"  ><i class=\"ace-icon fa fa-eye blue bigger-100\"></i> Détail Proforma</a></li>', 0, '[-1-2-3-5-]', 2, 0, 'Proforma envoyée au client', 'success', '<span class=\"label label-sm label-success\">Proforma envoyée au client</span>', NULL, NULL, NULL, NULL),
(1142, 720, 'e20d83df90355eca2a65f56a2556601f', 'Détail Proforma', 'this_url', 'viewproforma', NULL, '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"viewproforma\"  ><i class=\"ace-icon fa fa-eye blue bigger-100\"></i> Détail Proforma</a></li>', 0, '[-1-2-3-5-]', 1, 0, 'Attente Expédition', 'info', '<span class=\"label label-sm label-info\">Attente Expédition</span>', NULL, NULL, NULL, NULL),
(1143, 720, '252ed64d8956e20fb88c1be41688f74a', 'Envoi proforma au client', 'this_exec', 'validproforma', NULL, '<li><a href=\"#\" class=\"this_exec\" data=\"%id%\" rel=\"validproforma\"  ><i class=\"ace-icon fa fa-envelope bigger-100\"></i> Envoi proforma au client</a></li>', 0, '[-1-2-]', 1, 1, 'Attente Expédition', 'info', '<span class=\"label label-sm label-info\">Attente Expédition</span>', NULL, NULL, NULL, NULL),
(1144, 721, 'd5a6338765b9eab63104b59f01c06114', 'Ajouter pro-forma', NULL, 'addproforma', NULL, '', 0, '[-1-2-3-5-]', 0, 0, 'Brouillon', 'warning', '<span class=\"label label-sm label-warning\">Brouillon</span>', NULL, NULL, NULL, NULL),
(1145, 722, '95831bde77bc886d6ab4dd5e734de743', 'Editer proforma', NULL, 'editproforma', NULL, '', 0, '[-1-2-3-5-]', 0, 0, 'Brouillon', 'warning', '<span class=\"label label-sm label-warning\">Brouillon</span>', NULL, NULL, NULL, NULL),
(1146, 723, 'cbb4e1efa1c05b42d25a3a6bcab038a2', 'Ajouter détail proforma', NULL, 'adddetailproforma', NULL, '', 0, '[-1-2-3-5-]', 0, 0, 'Attente validation', 'warning', '<span class=\"label label-sm label-warning\">Attente validation</span>', NULL, NULL, NULL, NULL),
(1147, 724, 'e9f745054778257a255452c6609461a0', 'valider Proforma', NULL, 'validproforma', NULL, '', 0, '[-1-2-3-5-]', 0, 0, 'Attente validation', 'warning', '<span class=\"label label-sm label-warning\">Attente validation</span>', NULL, NULL, NULL, NULL),
(1148, 725, 'defef148c404c7e6ac79e4783e0a7ab7', 'Détail Pro-forma', NULL, 'viewproforma', NULL, '', 0, '[-1-2-3-5-]', 0, 0, 'Attente validation', 'warning', 'Attente validation', NULL, NULL, NULL, NULL),
(1149, 726, '53008d64edf241c937a06f03eff139aa', 'Editer détail proforma', NULL, 'edit_detailproforma', NULL, '', 0, '[-1-2-3-5-]', 0, 0, 'Attente validation', 'warning', '<span class=\"label label-sm label-warning\">Attente validation</span>', NULL, NULL, NULL, NULL),
(1150, 727, '30e9d3d1ad17eb7f1fc0d5cbb9b58482', 'Supprimer proforma', NULL, 'deleteproforma', NULL, '', 1, '[-1-2-3-5-]', 0, 0, 'Attente validation', 'warning', '<span class=\"label label-sm label-warning\">Attente validation</span>', NULL, NULL, NULL, NULL),
(1151, 728, '192715027870a4a612fd44d562e2752f', 'Gestion des produits', NULL, 'produits', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1152, 728, 'cb96e99d5f8e381637d1ac83f1a21f1c', 'Modifier  produit', 'this_url', 'editproduit', 'pencil-square-o', '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"editproduit\"  ><i class=\"ace-icon fa fa-pencil-square-o bigger-100\"></i> Modifier  produit</a></li>', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1153, 728, '64e84ff11fea7f68bcf6a5b744c36081', 'Détail  produit', 'this_url', 'detailproduit', 'eye', '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"detailproduit\"  ><i class=\"ace-icon fa fa-eye bigger-100\"></i> Détail  produit</a></li>', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1154, 728, '0c94d85f4ee23656a01155ad1af5001c', 'Valider  produit', 'this_exec', 'validproduit', 'check-square-o', '<li><a href=\"#\" class=\"this_exec\" data=\"%id%\" rel=\"validproduit\"  ><i class=\"ace-icon fa fa-check-square-o bigger-100\"></i> Valider  produit</a></li>', 0, '[-1-]', 0, 1, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1155, 728, '6b087b20929483bb07f8862b39e41f07', 'Désactiver produit', 'this_exec', 'validproduit', NULL, '<li><a href=\"#\" class=\"this_exec\" data=\"%id%\" rel=\"validproduit\"  ><i class=\"ace-icon fa fa-cogs bigger-100\"></i> Désactiver produit</a></li>', 0, '[-1-]', 1, 0, 'Produit  Validé', 'success', '<span class=\"label label-sm label-success\">Produit  Validé</span>', NULL, NULL, NULL, NULL),
(1156, 728, '6f1d7cc5bd1c941beffa0ae3e1efd559', 'Achat  produit', 'this_url', 'buyproducts', 'euro', '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"buyproducts\"  ><i class=\"ace-icon fa fa-euro bigger-100\"></i> Achat  produit</a></li>', 0, '[-1-]', 1, 0, 'Produit  Validé', 'success', '<span class=\"label label-sm label-success\">Produit  Validé</span>', NULL, NULL, NULL, NULL),
(1157, 728, '41b9c4b7028269d4540915d6ec14ee79', 'Détails Produit', 'this_url', 'detailproduit', NULL, '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"detailproduit\"  ><i class=\"ace-icon fa fa-eye bigger-100\"></i> Détails Produit</a></li>', 0, '[-1-]', 1, 0, 'Produit  Validé', 'success', '<span class=\"label label-sm label-success\">Produit  Validé</span>', NULL, NULL, NULL, NULL),
(1158, 729, '93e893c307a6fa63e392f78751ec70ce', 'Ajouter produit', NULL, 'addproduit', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1159, 730, 'bcf3beada4a98e8145af2d4fbb744f01', 'Modifier produit', NULL, 'editproduit', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1160, 731, '796427ec57f7c13d6b737055ae686b34', 'Detail produit', NULL, 'detailproduit', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1161, 732, '1fb8cd1a179be07586fa7db05013dd37', 'Valider produit', NULL, 'validproduit', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1162, 733, '7779e98d2111faedf458f7aeb548294e', 'Supprimer produit', NULL, 'deleteproduit', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1163, 734, '8da585a04e918c256bd26f0c03f1390d', 'Achat produit', NULL, 'buyproducts', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1164, 734, 'f8c9a7413089566d1db20dcc5ca17e03', 'Modifier achat', 'this_url', 'editbuyproduct', NULL, '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"editbuyproduct\"  ><i class=\"ace-icon fa fa-cogs bigger-100\"></i> Modifier achat</a></li>', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1165, 734, '682b4328ee832101a44dac86b22d5757', 'Détail achat', 'this_url', 'detailbuyproduct', NULL, '<li><a href=\"#\" class=\"this_url\" data=\"%id%\" rel=\"detailbuyproduct\"  ><i class=\"ace-icon fa fa-cogs bigger-100\"></i> Détail achat</a></li>', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1166, 734, 'd1ebf1c5482ddf06721b11ec64afb744', 'Valider achat', 'this_exec', 'validbuyproduct', NULL, '<li><a href=\"#\" class=\"this_exec\" data=\"%id%\" rel=\"validbuyproduct\"  ><i class=\"ace-icon fa fa-cogs bigger-100\"></i> Valider achat</a></li>', 0, '[-1-]', 0, 1, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1167, 734, '368a1e91fc63e263eb01d85a34ecd89b', 'Désactiver achat', 'this_exec', 'validbuyproduct', NULL, '<li><a href=\"#\" class=\"this_exec\" data=\"%id%\" rel=\"validbuyproduct\"  ><i class=\"ace-icon fa fa-cogs bigger-100\"></i> Désactiver achat</a></li>', 0, '[-1-]', 1, 0, 'Achat validé', 'success', '<span class=\"label label-sm label-success\">Achat validé</span>', NULL, NULL, NULL, NULL),
(1168, 735, '659be5cd86a12eba7e59c52d60198a36', 'Ajoute achat', NULL, 'addbuyproduct', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1169, 736, '8415336a17e8ca26f3eca5741863f3b2', 'Modifier achat', NULL, 'editbuproduct', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1170, 737, '2c3b4875b72f7da6a87b5c0d7e85f51d', 'Supprimer achat', NULL, 'deletebuyproduct', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1171, 738, 'd4180eb7a4ff86c598f441ffd4543f36', 'Détail achat', NULL, 'detailbuyproduct', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1172, 739, '4a4c9b096bad58a96d5ea6f93d66e81c', 'Valider achat', NULL, 'validbuyproduct', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class=\"label label-sm label-warning\">Attente de validation</span>', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `token`
--

CREATE TABLE `token` (
  `id` int(11) NOT NULL,
  `token` varchar(32) CHARACTER SET latin1 NOT NULL,
  `dattime` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `users_sys`
--

CREATE TABLE `users_sys` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `nom` text CHARACTER SET latin1 NOT NULL COMMENT 'pseudo',
  `fnom` varchar(20) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Nom',
  `lnom` varchar(20) CHARACTER SET latin1 DEFAULT NULL COMMENT 'prénom',
  `pass` text CHARACTER SET latin1 NOT NULL COMMENT 'mot de pass (crypté)',
  `mail` text CHARACTER SET latin1 NOT NULL COMMENT 'Adresse Email',
  `service` int(11) NOT NULL COMMENT 'Service utilisateur',
  `tel` varchar(15) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Téléphone',
  `etat` int(11) NOT NULL COMMENT 'statut utilisateur',
  `defapp` int(11) DEFAULT NULL COMMENT 'application par défault',
  `agence` int(11) DEFAULT NULL COMMENT 'Agence d''affectation',
  `ctc` int(11) NOT NULL DEFAULT '0' COMMENT 'Compteur tentative de connexion',
  `lastactive` datetime DEFAULT NULL COMMENT 'derniere activité',
  `photo` int(6) DEFAULT NULL COMMENT 'Photo utilisateur',
  `signature` int(6) DEFAULT NULL COMMENT 'signature',
  `form` int(6) DEFAULT NULL COMMENT 'Path for form Sing up',
  `creusr` varchar(20) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Inserted BY',
  `credat` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'Inserted date',
  `updusr` varchar(20) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Update BY',
  `upddat` datetime DEFAULT NULL COMMENT 'Updated date'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table users Systeme';

--
-- Déchargement des données de la table `users_sys`
--

INSERT INTO `users_sys` (`id`, `nom`, `fnom`, `lnom`, `pass`, `mail`, `service`, `tel`, `etat`, `defapp`, `agence`, `ctc`, `lastactive`, `photo`, `signature`, `form`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(1, 'admin', 'Administrateur', 'Systeme', '5a05679021426829ab75ac9fa6655947', 'rachid@atelsolution.com', 1, '6544545454', 1, 0, 1, 0, '2017-10-17 13:58:55', 1, 2, 9, NULL, '2017-01-13 13:52:42', '1', '2017-06-06 19:22:54'),
(2, 'fati', 'Fatima Zahra', 'HANOUNOU', '5a05679021426829ab75ac9fa6655947', 'fatimazahra@dctchad.com', 2, '0674471151', 1, 3, NULL, 0, '2017-10-17 00:02:37', 4, 516, 6, NULL, '2017-01-19 21:59:10', '1', '2017-10-13 20:23:42'),
(19, 'ayoub', 'Boukhdada', 'Ayoub', '5a05679021426829ab75ac9fa6655947', 'ayoub@dctchad.com', 5, '68126683', 1, 3, NULL, 0, '2017-10-16 21:28:26', NULL, 513, NULL, '1', '2017-10-11 00:36:55', '1', '2017-10-13 20:13:19'),
(20, 'kada', 'Rachid', 'Kada', '5a05679021426829ab75ac9fa6655947', 'rachid@dctchad.com', 3, '666666666', 1, 3, NULL, 0, '2017-10-17 12:27:24', 514, 515, NULL, '1', '2017-10-11 00:55:09', NULL, NULL),
(22, 'fatiadmin', 'Fati ', 'Admin', '5a05679021426829ab75ac9fa6655947', 'fati@dcthad.com', 1, '00000000000', 1, 3, NULL, 0, '2017-10-17 02:23:30', NULL, 517, NULL, '20', '2017-10-16 21:34:25', NULL, NULL),
(23, 'ayoubadmin', 'Ayoub', 'Admin', '5a05679021426829ab75ac9fa6655947', 'ayoub@test.ts', 1, '000000000000', 1, 3, NULL, 0, '2017-10-17 19:04:26', NULL, 518, NULL, '20', '2017-10-16 21:36:27', NULL, NULL),
(24, 'ali', 'Ali', 'Mahamat', '6d73a35afe9aab0219cce06d839e9c08', 'contact@globaltech-sat.com', 3, '0023566324513', 1, 3, NULL, 0, '2017-10-17 15:19:30', NULL, 531, NULL, '20', '2017-10-17 12:24:54', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la vue `qte_actuel`
--
DROP TABLE IF EXISTS `qte_actuel`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `qte_actuel`  AS  select `produits`.`id` AS `id_produit`,if(isnull((select sum(`stock`.`qte`) from `stock` where ((`stock`.`idproduit` = `produits`.`id`) and (`stock`.`mouvement` in ('E','S'))))),0,(select sum(`stock`.`qte`) from `stock` where ((`stock`.`idproduit` = `produits`.`id`) and (`stock`.`mouvement` in ('E','S'))))) AS `qte_act` from `produits` group by `produits`.`id` ;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `archive`
--
ALTER TABLE `archive`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `categorie_client`
--
ALTER TABLE `categorie_client`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_code` (`code`),
  ADD KEY `fk_client_ville` (`id_ville`),
  ADD KEY `fk_client_pays` (`id_pays`),
  ADD KEY `fk_client_type` (`id_categorie`),
  ADD KEY `fk_client_devise` (`id_devise`);

--
-- Index pour la table `complement_facture`
--
ALTER TABLE `complement_facture`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_facture_complement` (`idfacture`);

--
-- Index pour la table `contrats`
--
ALTER TABLE `contrats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_devis_contrat` (`iddevis`),
  ADD KEY `fk_type_echeance` (`idtype_echeance`);

--
-- Index pour la table `contrats_frn`
--
ALTER TABLE `contrats_frn`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_fournisseur` (`id_fournisseur`);

--
-- Index pour la table `devis`
--
ALTER TABLE `devis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_id_client` (`id_client`);

--
-- Index pour la table `d_devis`
--
ALTER TABLE `d_devis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_devis` (`tkn_frm`),
  ADD KEY `fk_id_produit` (`id_produit`);

--
-- Index pour la table `d_proforma`
--
ALTER TABLE `d_proforma`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_devis` (`tkn_frm`),
  ADD KEY `fk_id_produit` (`id_produit`);

--
-- Index pour la table `echeances_contrat`
--
ALTER TABLE `echeances_contrat`
  ADD PRIMARY KEY (`id`,`date_echeance`,`montant`),
  ADD KEY `fk_contrat_echeance` (`idcontrat`);

--
-- Index pour la table `encaissements`
--
ALTER TABLE `encaissements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_facture` (`idfacture`);

--
-- Index pour la table `espionnage_update`
--
ALTER TABLE `espionnage_update`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `updt_id` (`id`,`updt_id`);

--
-- Index pour la table `factures`
--
ALTER TABLE `factures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_contrat` (`idcontrat`),
  ADD KEY `fk_devis` (`iddevis`);

--
-- Index pour la table `forgot`
--
ALTER TABLE `forgot`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `fournisseurs`
--
ALTER TABLE `fournisseurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_code` (`code`),
  ADD UNIQUE KEY `unique_denomination` (`denomination`),
  ADD KEY `fk_client_pays` (`id_pays`),
  ADD KEY `fk_client_devise` (`id_devise`),
  ADD KEY `fk_ville` (`id_ville`);

--
-- Index pour la table `modul`
--
ALTER TABLE `modul`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `modul` (`modul`);

--
-- Index pour la table `produits`
--
ALTER TABLE `produits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_produit_categorie` (`idcategorie`),
  ADD KEY `fk_produit_uv` (`iduv`),
  ADD KEY `fk_produit_type` (`idtype`);

--
-- Index pour la table `proforma`
--
ALTER TABLE `proforma`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_id_client` (`id_client`);

--
-- Index pour la table `ref_categories_produits`
--
ALTER TABLE `ref_categories_produits`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `ref_categ_prm`
--
ALTER TABLE `ref_categ_prm`
  ADD PRIMARY KEY (`id`,`categorie`);

--
-- Index pour la table `ref_departement`
--
ALTER TABLE `ref_departement`
  ADD PRIMARY KEY (`id`),
  ADD KEY `region_depart` (`id_region`);

--
-- Index pour la table `ref_devise`
--
ALTER TABLE `ref_devise`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `ref_pays`
--
ALTER TABLE `ref_pays`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `ref_region`
--
ALTER TABLE `ref_region`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_region_pays` (`id_pays`);

--
-- Index pour la table `ref_types_produits`
--
ALTER TABLE `ref_types_produits`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `ref_type_echeance`
--
ALTER TABLE `ref_type_echeance`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `ref_unites_vente`
--
ALTER TABLE `ref_unites_vente`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `ref_ville`
--
ALTER TABLE `ref_ville`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ville_dept` (`id_departement`);

--
-- Index pour la table `rules_action`
--
ALTER TABLE `rules_action`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_rule_idf_usrid` (`idf`,`userid`),
  ADD KEY `rules_action_task_appid` (`appid`),
  ADD KEY `rules_action_user_sys` (`userid`),
  ADD KEY `rule_action_task_action` (`action_id`),
  ADD KEY `rule_action_service_id` (`service`);

--
-- Index pour la table `rules_action_temp`
--
ALTER TABLE `rules_action_temp`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_rule` (`idf`,`userid`),
  ADD KEY `rules_action_user_sys` (`userid`),
  ADD KEY `rule_action_service_id` (`service`);

--
-- Index pour la table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `session`
--
ALTER TABLE `session`
  ADD PRIMARY KEY (`id_sys`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `session_user_id` (`userid`);

--
-- Index pour la table `ste_info`
--
ALTER TABLE `ste_info`
  ADD PRIMARY KEY (`ste_rc`),
  ADD KEY `id` (`id`);

--
-- Index pour la table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_achat_produit` (`idproduit`);

--
-- Index pour la table `sys_log`
--
ALTER TABLE `sys_log`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `sys_notifier`
--
ALTER TABLE `sys_notifier`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQUE` (`app`);

--
-- Index pour la table `sys_setting`
--
ALTER TABLE `sys_setting`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_region_pays` (`key`);

--
-- Index pour la table `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQUE` (`app`),
  ADD KEY `task_ibfk_1` (`modul`);

--
-- Index pour la table `task_action`
--
ALTER TABLE `task_action`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_action_task` (`appid`),
  ADD KEY `task_action_descrip` (`descrip`);

--
-- Index pour la table `token`
--
ALTER TABLE `token`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users_sys`
--
ALTER TABLE `users_sys`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `user_sys_service` (`service`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `archive`
--
ALTER TABLE `archive`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID SYS', AUTO_INCREMENT=535;

--
-- AUTO_INCREMENT pour la table `categorie_client`
--
ALTER TABLE `categorie_client`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT pour la table `complement_facture`
--
ALTER TABLE `complement_facture`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `contrats`
--
ALTER TABLE `contrats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT pour la table `contrats_frn`
--
ALTER TABLE `contrats_frn`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT pour la table `devis`
--
ALTER TABLE `devis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT pour la table `d_devis`
--
ALTER TABLE `d_devis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=169;

--
-- AUTO_INCREMENT pour la table `d_proforma`
--
ALTER TABLE `d_proforma`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=207;

--
-- AUTO_INCREMENT pour la table `echeances_contrat`
--
ALTER TABLE `echeances_contrat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT pour la table `encaissements`
--
ALTER TABLE `encaissements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `espionnage_update`
--
ALTER TABLE `espionnage_update`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT pour la table `factures`
--
ALTER TABLE `factures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT pour la table `forgot`
--
ALTER TABLE `forgot`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id demande Forgot', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `fournisseurs`
--
ALTER TABLE `fournisseurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT pour la table `modul`
--
ALTER TABLE `modul`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id modul', AUTO_INCREMENT=129;

--
-- AUTO_INCREMENT pour la table `produits`
--
ALTER TABLE `produits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id', AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT pour la table `proforma`
--
ALTER TABLE `proforma`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT pour la table `ref_categories_produits`
--
ALTER TABLE `ref_categories_produits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `ref_categ_prm`
--
ALTER TABLE `ref_categ_prm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID table', AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `ref_departement`
--
ALTER TABLE `ref_departement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `ref_devise`
--
ALTER TABLE `ref_devise`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `ref_pays`
--
ALTER TABLE `ref_pays`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identifiant ligne', AUTO_INCREMENT=245;

--
-- AUTO_INCREMENT pour la table `ref_region`
--
ALTER TABLE `ref_region`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identifiant de ligne', AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT pour la table `ref_types_produits`
--
ALTER TABLE `ref_types_produits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `ref_type_echeance`
--
ALTER TABLE `ref_type_echeance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `ref_unites_vente`
--
ALTER TABLE `ref_unites_vente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `ref_ville`
--
ALTER TABLE `ref_ville`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identifiant ligne', AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT pour la table `rules_action`
--
ALTER TABLE `rules_action`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'rule id', AUTO_INCREMENT=32377;

--
-- AUTO_INCREMENT pour la table `rules_action_temp`
--
ALTER TABLE `rules_action_temp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'rule id', AUTO_INCREMENT=773;

--
-- AUTO_INCREMENT pour la table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID Groupe', AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `session`
--
ALTER TABLE `session`
  MODIFY `id_sys` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID SYS', AUTO_INCREMENT=174;

--
-- AUTO_INCREMENT pour la table `ste_info`
--
ALTER TABLE `ste_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `stock`
--
ALTER TABLE `stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT pour la table `sys_log`
--
ALTER TABLE `sys_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID Systeme', AUTO_INCREMENT=219;

--
-- AUTO_INCREMENT pour la table `sys_notifier`
--
ALTER TABLE `sys_notifier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id line', AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pour la table `sys_setting`
--
ALTER TABLE `sys_setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identifiant de ligne', AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `task`
--
ALTER TABLE `task`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID Sys', AUTO_INCREMENT=740;

--
-- AUTO_INCREMENT pour la table `task_action`
--
ALTER TABLE `task_action`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID App Action (AI)', AUTO_INCREMENT=1173;

--
-- AUTO_INCREMENT pour la table `token`
--
ALTER TABLE `token`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users_sys`
--
ALTER TABLE `users_sys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=25;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `clients`
--
ALTER TABLE `clients`
  ADD CONSTRAINT `fk_client_categorie` FOREIGN KEY (`id_categorie`) REFERENCES `categorie_client` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_client_devise` FOREIGN KEY (`id_devise`) REFERENCES `ref_devise` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_client_pays` FOREIGN KEY (`id_pays`) REFERENCES `ref_pays` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_client_ville` FOREIGN KEY (`id_ville`) REFERENCES `ref_ville` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `complement_facture`
--
ALTER TABLE `complement_facture`
  ADD CONSTRAINT `fk_facture_complement` FOREIGN KEY (`idfacture`) REFERENCES `factures` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `contrats`
--
ALTER TABLE `contrats`
  ADD CONSTRAINT `fk_devis_contrat` FOREIGN KEY (`iddevis`) REFERENCES `devis` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_type_echeance` FOREIGN KEY (`idtype_echeance`) REFERENCES `ref_type_echeance` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `contrats_frn`
--
ALTER TABLE `contrats_frn`
  ADD CONSTRAINT `fk_fournisseur` FOREIGN KEY (`id_fournisseur`) REFERENCES `fournisseurs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `devis`
--
ALTER TABLE `devis`
  ADD CONSTRAINT `fk_id_client` FOREIGN KEY (`id_client`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `echeances_contrat`
--
ALTER TABLE `echeances_contrat`
  ADD CONSTRAINT `fk_contrat_echeance` FOREIGN KEY (`idcontrat`) REFERENCES `contrats` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `encaissements`
--
ALTER TABLE `encaissements`
  ADD CONSTRAINT `fk_facture` FOREIGN KEY (`idfacture`) REFERENCES `factures` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `factures`
--
ALTER TABLE `factures`
  ADD CONSTRAINT `fk_contrat` FOREIGN KEY (`idcontrat`) REFERENCES `contrats` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_devis` FOREIGN KEY (`iddevis`) REFERENCES `devis` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `fournisseurs`
--
ALTER TABLE `fournisseurs`
  ADD CONSTRAINT `fk_devise` FOREIGN KEY (`id_devise`) REFERENCES `ref_devise` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pays` FOREIGN KEY (`id_pays`) REFERENCES `ref_pays` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ville` FOREIGN KEY (`id_ville`) REFERENCES `ref_ville` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `produits`
--
ALTER TABLE `produits`
  ADD CONSTRAINT `fk_produit_categorie` FOREIGN KEY (`idcategorie`) REFERENCES `ref_categories_produits` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_produit_type` FOREIGN KEY (`idtype`) REFERENCES `ref_types_produits` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_produit_uv` FOREIGN KEY (`iduv`) REFERENCES `ref_unites_vente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `ref_departement`
--
ALTER TABLE `ref_departement`
  ADD CONSTRAINT `region_depart` FOREIGN KEY (`id_region`) REFERENCES `ref_region` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `ref_region`
--
ALTER TABLE `ref_region`
  ADD CONSTRAINT `pays_region` FOREIGN KEY (`id_pays`) REFERENCES `ref_pays` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `ref_ville`
--
ALTER TABLE `ref_ville`
  ADD CONSTRAINT `ville_dept` FOREIGN KEY (`id_departement`) REFERENCES `ref_departement` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `rules_action`
--
ALTER TABLE `rules_action`
  ADD CONSTRAINT `rule_action_service_id` FOREIGN KEY (`service`) REFERENCES `services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rule_action_task_action` FOREIGN KEY (`action_id`) REFERENCES `task_action` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rule_action_task_id` FOREIGN KEY (`appid`) REFERENCES `task` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rules_action_user_sys` FOREIGN KEY (`userid`) REFERENCES `users_sys` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `session`
--
ALTER TABLE `session`
  ADD CONSTRAINT `session_user_id` FOREIGN KEY (`userid`) REFERENCES `users_sys` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `stock`
--
ALTER TABLE `stock`
  ADD CONSTRAINT `fk_achat_produit` FOREIGN KEY (`idproduit`) REFERENCES `produits` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
