-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Lun 02 Octobre 2017 à 23:28
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
CREATE DEFINER=`root`@`localhost` PROCEDURE `generate_fact`(in tva INT)
BEGIN
DECLARE finished INT DEFAULT FALSE;
declare reference varchar(20);
DECLare totalht double;
DECLare totaltva double;
DECLare totalttc double;
Declare client varchar(100);
Declare contrat int;
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
 FROM Clients c, devis d,d_devis dd, contrats ctr,ref_type_echeance ech 
 WHERE d.id_client=c.id and ctr.iddevis=d.id AND  ctr.idtype_echeance=ech.id
 and ctr.idtype_echeance=ech.id and d.id=dd.id_devis
 and  (SELECT date(NOW())) between ctr.date_effet and ctr.date_fin
 and ctr.etat=1
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
 fetch cur1 into totalht, totaltva, totalttc, client, contrat;
 
 IF finished THEN
      LEAVE read_loop;
 END IF;
 
SELECT CONCAT('GT-FCT-',(SELECT IFNULL(( MAX(SUBSTR(ref, 8, LENGTH(SUBSTR(ref,8))-5))),0)+1  AS reference 
FROM factures WHERE SUBSTR(ref,LENGTH(ref)-3,4)= (SELECT  YEAR(SYSDATE()))),'/',(SELECT  YEAR(SYSDATE()))) INTO reference ;
INSERT INTO factures (ref, base_fact, total_ht, total_tva, total_ttc, total_paye, reste,  CLIENT, idcontrat, date_facture, creusr, credat) 
values(reference,'C',totalht,totaltva,totalttc,0,totalttc,CLIENT,contrat,(SELECT NOW() FROM DUAL),1,(SELECT NOW() FROM DUAL));
END LOOP;
CLOSE cur1;
 
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `notify_contrat`()
BEGIN
update contrats c set c.`etat`= 2 where (SELECT date(NOW()) FROM DUAL)=c.`date_notif` and c.etat=1 ;
 
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `notify_contrat_frn`()
BEGIN
    
update contrats_frn c set c.`etat`= 2 where (SELECT date(NOW()) FROM DUAL)=c.`date_notif` and c.etat=1 ;
    END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `archive`
--

CREATE TABLE IF NOT EXISTS `archive` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID SYS',
  `doc` varchar(120) CHARACTER SET latin1 DEFAULT NULL COMMENT 'lien document',
  `titr` varchar(300) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Titre document',
  `modul` varchar(35) CHARACTER SET latin1 NOT NULL COMMENT 'le module de document',
  `table` varchar(35) CHARACTER SET latin1 DEFAULT NULL COMMENT 'table de modul',
  `idm` int(11) NOT NULL COMMENT 'ID pour Module',
  `service` int(11) NOT NULL COMMENT 'service',
  `type` varchar(10) CHARACTER SET latin1 DEFAULT NULL COMMENT 'type de document',
  `creusr` int(11) NOT NULL COMMENT 'Add by',
  `credat` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'Dat insert',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Table Archives' AUTO_INCREMENT=488 ;

--
-- Contenu de la table `archive`
--

INSERT INTO `archive` (`id`, `doc`, `titr`, `modul`, `table`, `idm`, `service`, `type`, `creusr`, `credat`) VALUES
(1, './upload/users/1/photo_1.jpg', 'Photo de profile de Admin', '0', NULL, 1, 1, 'image', 1, '2017-03-27 12:22:39'),
(2, './upload/users/1/signature_1.png', 'signature  de Admin', '0', NULL, 1, 1, 'image', 1, '2017-03-27 12:22:39'),
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
(87, './upload/users/7/form_7.pdf', 'Formulaire  de Adamou  Adamou', 'users', 'users_sys', 7, 1, 'Document', 1, '2017-04-07 11:34:03'),
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
(377, './upload/users/17/signature_17.png', 'signature  de tester  tester', 'users', 'users_sys', 17, 1, 'Document', 1, '2017-06-13 10:02:41'),
(378, './upload/users/17/form_17.pdf', 'Formulaire  de tester  tester', 'users', 'users_sys', 17, 1, 'Document', 1, '2017-06-13 10:02:42'),
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
(409, './upload/installateurs/1/pj_photo_1.jpg', 'Photo du clientDENOMI9', 'installateurs', 'clients', 1, 1, 'Document', 1, '2017-08-26 14:47:01'),
(411, './upload/installateurs/1/pj_photo_1.png', 'Photo du clientDENOMI9', 'installateurs', 'clients', 1, 1, 'Document', 1, '2017-08-26 17:01:57'),
(413, './upload/installateurs/15/pj_photo_15.png', 'Photo du clientfefeg', 'installateurs', 'clients', 15, 1, 'Document', 1, '2017-08-26 17:05:06'),
(414, './upload/installateurs/1/pj_1.pdf', 'Justifications du clientsfefeg', 'installateurs', 'clients', 1, 1, 'Document', 1, '2017-08-26 17:11:57'),
(417, './upload/users/19/signature_19.png', 'signature  de test  test', 'users', 'users_sys', 19, 1, 'Document', 1, '2017-09-13 15:53:48'),
(418, './upload/users/19/photo_19.png', 'Photo de profile de test  test', 'users', 'users_sys', 19, 1, 'Document', 1, '2017-09-13 15:57:03'),
(422, './upload/users/18/photo_18.png', 'Photo de profile de test1  test1', 'users', 'users_sys', 18, 1, 'Document', 1, '2017-09-13 16:28:38'),
(425, './upload/clients/1/pj_1.pdf', 'Justifications du clientsDE111', 'clients', 'clients', 1, 1, 'Document', 1, '2017-09-13 19:30:56'),
(426, './upload/clients/1/pj_photo_1.png', 'Photo du clientDE111', 'clients', 'clients', 1, 1, 'Document', 1, '2017-09-13 19:30:56'),
(428, './upload/clients/2/pj_2.pdf', 'Justifications du clientsDE111', 'clients', 'clients', 2, 1, 'Document', 1, '2017-09-13 19:58:37'),
(429, './upload/clients/2/pj_photo_2.png', 'Photo du clientDE111', 'clients', 'clients', 2, 1, 'Document', 1, '2017-09-13 19:58:37'),
(430, './upload/clients/8/pj_8.pdf', 'Justifications du clientsDENOMI9', 'clients', 'clients', 8, 1, 'Document', 1, '2017-09-13 20:02:48'),
(431, './upload/clients/9/pj_photo_9.png', 'Photo du clientkmzdkzf', 'clients', 'clients', 9, 1, 'Document', 1, '2017-09-13 20:03:43'),
(433, './upload/clients/16/pj_photo_16.png', 'Photo du clientden', 'clients', 'clients', 16, 1, 'Document', 1, '2017-09-13 21:12:23'),
(434, './upload/clients/17/pj_photo_17.png', 'Photo du clientde', 'clients', 'clients', 17, 1, 'Document', 1, '2017-09-13 22:16:07'),
(435, './upload/contrats/7/pj_7.pdf', 'Justifications du contratCTR-1/2017', 'contrats', 'contrats', 7, 1, 'Document', 1, '2017-09-17 01:05:17'),
(436, './upload/contrats/7/pj_photo_7.png', 'PhotoCTR-1/2017', 'contrats', 'contrats', 7, 1, 'Document', 1, '2017-09-17 01:05:17'),
(437, './upload/contrats/9/pj_9.pdf', 'Justifications du contratCTR-3/2017', 'contrats', 'contrats', 9, 1, 'Document', 1, '2017-09-17 01:11:30'),
(438, './upload/contrats/9/pj_photo_9.png', 'PhotoCTR-3/2017', 'contrats', 'contrats', 9, 1, 'Document', 1, '2017-09-17 01:11:30'),
(439, './upload/contrats/10/pj_10.pdf', 'Justifications du contratCTR-4/2017', 'contrats', 'contrats', 10, 1, 'Document', 1, '2017-09-17 01:19:06'),
(440, './upload/contrats/10/pj_photo_10.png', 'PhotoCTR-4/2017', 'contrats', 'contrats', 10, 1, 'Document', 1, '2017-09-17 01:19:06'),
(441, './upload/contrats_fournisseurs/13/pj_13.pdf', 'Copie Contrat fournisseurCTR-FRN2/2017', 'contrats_fournisseurs', 'contrats_frn', 13, 1, 'Document', 1, '2017-09-21 12:26:03'),
(443, './upload/contrats_fournisseurs/15/pj_15.pdf', 'Copie Contrat fournisseurCTR-FRN-4/2017', 'contrats_fournisseurs', 'contrats_frn', 15, 1, 'Document', 1, '2017-09-21 22:43:17'),
(444, './upload/contrats_fournisseurs/16/pj_16.pdf', 'Copie Contrat fournisseurCTR-FRN-5/2017', 'contrats_fournisseurs', 'contrats_frn', 16, 1, 'Document', 1, '2017-09-21 22:46:10'),
(446, './upload/contrats_fournisseurs/14/pj_14.pdf', 'Copie Contrat fournisseur.', 'contrats_fournisseurs', 'contrats_frn', 14, 1, 'Document', 1, '2017-09-22 02:33:08'),
(447, './upload/contrats_fournisseurs/17/pj_17.pdf', 'Copie Contrat fournisseur.CTR-FRN-4/2017', 'contrats_fournisseurs', 'contrats_frn', 17, 1, 'Document', 1, '2017-09-22 20:55:06'),
(449, './upload/contrats09_2017/contrats_14.pdf', 'contrats 14', 'contrats', 'contrats', 14, 1, 'Document', 1, '2017-09-23 00:30:18'),
(450, './upload/contrats09_2017/contrats_12.pdf', 'contrats 12', 'contrats', 'contrats', 12, 1, 'Document', 1, '2017-09-23 02:12:37'),
(451, './upload/contrats_fournisseurs/12/pj_12.pdf', 'Copie Contrat fournisseur.', 'contrats_fournisseurs', 'contrats_frn', 12, 1, 'Document', 1, '2017-09-23 02:39:50'),
(452, './upload/contrats/16/pj_16.pdf', 'Justifications du contratCTR-5/2017', 'contrats', 'contrats', 16, 1, 'Document', 1, '2017-09-26 01:13:19'),
(453, './upload/contrats/16/pj_photo_16.png', 'PhotoCTR-5/2017', 'contrats', 'contrats', 16, 1, 'Document', 1, '2017-09-26 01:13:19'),
(454, './upload/contrats/17/pj_17.pdf', 'Justifications du contratCTR-6/2017', 'contrats', 'contrats', 17, 1, 'Document', 1, '2017-09-27 22:32:12'),
(455, './upload/contrats/17/pj_photo_17.png', 'PhotoCTR-6/2017', 'contrats', 'contrats', 17, 1, 'Document', 1, '2017-09-27 22:32:12'),
(457, './upload/contrats_fournisseurs/18/pj_18.pdf', 'Copie Contrat fournisseur.', 'contrats_fournisseurs', 'contrats_frn', 18, 1, 'Document', 1, '2017-10-01 16:50:56'),
(458, './upload/contrats_fournisseurs/20/pj_20.pdf', 'Copie Contrat fournisseur.CTR-FRN-2/2017', 'contrats_fournisseurs', 'contrats_frn', 20, 1, 'Document', 1, '2017-10-01 17:52:26'),
(459, './upload/contrats_fournisseurs/21/pj_21.pdf', 'Copie Contrat fournisseur.CTR-FRN-2/2017', 'contrats_fournisseurs', 'contrats_frn', 21, 1, 'Document', 1, '2017-10-01 17:59:04'),
(460, './upload/contrats_fournisseurs/22/pj_22.pdf', 'Copie Contrat fournisseur.CTR-FRN-2/2017', 'contrats_fournisseurs', 'contrats_frn', 22, 1, 'Document', 1, '2017-10-01 18:00:57'),
(461, './upload/contrats_fournisseurs/23/pj_23.pdf', 'Copie Contrat fournisseur.CTR-FRN-2/2017', 'contrats_fournisseurs', 'contrats_frn', 23, 1, 'Document', 1, '2017-10-01 18:03:49'),
(462, './upload/contrats_fournisseurs/25/pj_25.pdf', 'Copie Contrat fournisseur.CTR-FRN-3/2017', 'contrats_fournisseurs', 'contrats_frn', 25, 1, 'Document', 1, '2017-10-01 18:19:12'),
(463, './upload/contrats_fournisseurs/26/pj_26.pdf', 'Copie Contrat fournisseur.CTR-FRN-3/2017', 'contrats_fournisseurs', 'contrats_frn', 26, 1, 'Document', 1, '2017-10-01 18:22:16'),
(464, './upload/contrats_fournisseurs/27/pj_27.pdf', 'Copie Contrat fournisseur.CTR-FRN-3/2017', 'contrats_fournisseurs', 'contrats_frn', 27, 1, 'Document', 1, '2017-10-01 18:32:07'),
(465, './upload/contrats_fournisseurs/28/pj_28.pdf', 'Copie Contrat fournisseur.CTR-FRN-2/2017', 'contrats_fournisseurs', 'contrats_frn', 28, 1, 'Document', 1, '2017-10-01 18:40:09'),
(466, './upload/contrats_fournisseurs/29/pj_29.pdf', 'Copie Contrat fournisseur.CTR-FRN-2/2017', 'contrats_fournisseurs', 'contrats_frn', 29, 1, 'Document', 1, '2017-10-01 18:41:29'),
(467, './upload/contrats_fournisseurs/30/pj_30.pdf', 'Copie Contrat fournisseur.CTR-FRN-2/2017', 'contrats_fournisseurs', 'contrats_frn', 30, 1, 'Document', 1, '2017-10-01 18:43:35'),
(468, './upload/contrats_fournisseurs/31/pj_31.pdf', 'Copie Contrat fournisseur.CTR-FRN-2/2017', 'contrats_fournisseurs', 'contrats_frn', 31, 1, 'Document', 1, '2017-10-01 18:47:21'),
(469, './upload/contrats_fournisseurs/32/pj_32.pdf', 'Copie Contrat fournisseur.CTR-FRN-2/2017', 'contrats_fournisseurs', 'contrats_frn', 32, 1, 'Document', 1, '2017-10-01 18:49:28'),
(470, './upload/contrats_fournisseurs/33/pj_33.pdf', 'Copie Contrat fournisseur.CTR-FRN-2/2017', 'contrats_fournisseurs', 'contrats_frn', 33, 1, 'Document', 1, '2017-10-01 19:08:16'),
(471, './upload/contrats_fournisseurs/34/pj_34.pdf', 'Copie Contrat fournisseur.CTR-FRN-2/2017', 'contrats_fournisseurs', 'contrats_frn', 34, 1, 'Document', 1, '2017-10-01 19:09:51'),
(472, './upload/contrats_fournisseurs/35/pj_35.pdf', 'Copie Contrat fournisseur.CTR-FRN-2/2017', 'contrats_fournisseurs', 'contrats_frn', 35, 1, 'Document', 1, '2017-10-01 19:12:52'),
(473, './upload/contrats_fournisseurs/36/pj_36.pdf', 'Copie Contrat fournisseur.CTR-FRN-2/2017', 'contrats_fournisseurs', 'contrats_frn', 36, 1, 'Document', 1, '2017-10-01 19:18:34'),
(474, './upload/contrats_fournisseurs/37/pj_37.pdf', 'Copie Contrat fournisseur.CTR-FRN-2/2017', 'contrats_fournisseurs', 'contrats_frn', 37, 1, 'Document', 1, '2017-10-01 20:23:32'),
(476, './upload/Devis10_2017/Devis_11.pdf', 'Devis 11', 'devis', 'devis', 11, 1, 'Document', 1, '2017-10-01 21:58:47'),
(477, './upload/contrats_fournisseurs/38/pj_38.pdf', 'Copie Contrat fournisseur.CTR-FRN-3/2017', 'contrats_fournisseurs', 'contrats_frn', 38, 1, 'Document', 1, '2017-10-02 01:42:16'),
(481, './upload/contrats/23/pj_23.pdf', 'Justifications du contratCTR-8/2017', 'contrats', 'contrats', 23, 1, 'Document', 1, '2017-10-02 04:29:37'),
(485, './upload/contrats10_2017/contrats_22.pdf', 'contrats 22', 'contrats', 'contrats', 22, 1, 'Document', 1, '2017-10-02 16:55:09'),
(486, './upload/contrats10_2017/contrats_15.pdf', 'contrats 15', 'contrats', 'contrats', 15, 1, 'Document', 1, '2017-10-02 22:16:41'),
(487, './upload/contrats_fournisseurs/39/pj_39.pdf', 'Copie Contrat fournisseur.GT-CTR-FRN-1/2017', 'contrats_fournisseurs', 'contrats_frn', 39, 1, 'Document', 1, '2017-10-02 22:20:25');

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
(1, 'Grossiste', 1, 1, '2017-08-23 14:26:24', 1, '2017-09-15 01:18:33');

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

CREATE TABLE IF NOT EXISTS `clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `code` varchar(10) NOT NULL COMMENT 'Code client',
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
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_code` (`code`),
  KEY `fk_client_ville` (`id_ville`),
  KEY `fk_client_pays` (`id_pays`),
  KEY `fk_client_type` (`id_categorie`),
  KEY `fk_client_devise` (`id_devise`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- Contenu de la table `clients`
--

INSERT INTO `clients` (`id`, `code`, `denomination`, `id_categorie`, `r_social`, `r_commerce`, `nif`, `nom`, `prenom`, `civilite`, `adresse`, `id_pays`, `id_ville`, `tel`, `fax`, `bp`, `email`, `rib`, `id_devise`, `tva`, `pj`, `pj_photo`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(1, 'CP122', 'DE111', 1, NULL, NULL, NULL, NULL, NULL, 'Femme', 'adr12', 242, 43, '064333333333333333333333', '033333333333333333333333', NULL, 'em@em', NULL, 2, 'N', 425, 426, 1, 1, '2017-08-26 12:59:00', 1, '2017-08-26 19:34:14'),
(8, 'CP444', 'DENOMI9', 1, NULL, NULL, NULL, NULL, NULL, 'Femme', 'adr1', 242, 2, '0444444444444444444444444', NULL, NULL, 'em@em', NULL, 2, 'O', 430, 408, 0, 1, '2017-08-26 15:29:40', 1, '2017-09-13 21:02:48'),
(9, 'pjjjj', 'kmzdkzf', 1, NULL, NULL, NULL, NULL, NULL, 'Femme', 'dzdlz', 242, 43, '0222222222222222222222222', NULL, NULL, 'em@em', NULL, 2, 'N', NULL, 431, 0, 1, '2017-08-26 15:53:32', 1, '2017-09-13 21:03:43'),
(15, 'PRFF', 'fefeg', 1, 'lmlgrl,rleg', NULL, NULL, NULL, NULL, 'Femme', 'grdgdr', 242, 43, '04555555555555555', NULL, NULL, 'em@em', NULL, 2, 'O', 412, 413, 0, 1, '2017-08-26 18:05:06', 1, '2017-08-26 21:01:55'),
(16, 'waaw', 'den', 1, 'rhgg', '10999', '9888888888888', 'hanounou', 'fati', 'Femme', 'adr', 242, 34, '033333333333333333333333333333', NULL, NULL, 'em@em', NULL, 1, 'N', 432, 433, 1, 1, '2017-09-13 17:30:29', 1, '2017-09-20 17:02:25'),
(17, 'CLT-1/2017', 'client 2', 1, 'erhk', ',ldekgmlz', '0444444444', NULL, NULL, 'Femme', 'ezglekrg', 242, 36, '03333333333333333333333333333', '02222222222222222222', 'mkfze', 'em@em', NULL, 2, NULL, NULL, NULL, 0, 1, '2017-09-23 00:35:52', 1, '2017-09-23 03:42:45');

-- --------------------------------------------------------

--
-- Structure de la table `complement_facture`
--

CREATE TABLE IF NOT EXISTS `complement_facture` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `designation` varchar(100) DEFAULT NULL,
  `idfacture` int(11) DEFAULT NULL,
  `montant` double DEFAULT NULL,
  `type` char(1) DEFAULT NULL COMMENT 'P/R Pénalité/Réduction',
  `date_complement` date DEFAULT NULL,
  `etat` int(11) DEFAULT '0',
  `creusr` int(11) DEFAULT NULL,
  `credat` datetime DEFAULT CURRENT_TIMESTAMP,
  `updusr` int(11) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_facture_complement` (`idfacture`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `contrats`
--

CREATE TABLE IF NOT EXISTS `contrats` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
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
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_devis_contrat` (`iddevis`),
  KEY `fk_type_echeance` (`idtype_echeance`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=30 ;

--
-- Contenu de la table `contrats`
--

INSERT INTO `contrats` (`id`, `tkn_frm`, `ref`, `iddevis`, `date_effet`, `date_fin`, `commentaire`, `date_contrat`, `idtype_echeance`, `periode_fact`, `date_notif`, `pj`, `pj_photo`, `contrats_pdf`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(15, '13486b070fa62a4458ee104ec54198c6', 'CTR-4/2017', 21, '2017-09-24', '2017-10-28', '<p>okk  ooooo<br></p>', '2017-10-02', 4, 'D', '2017-10-18', NULL, NULL, 486, 1, 1, '2017-09-23 01:33:40', 1, '2017-10-02 23:16:40'),
(16, '257b3ae275ebda0beb5db36c01004269', 'CTR-5/2017', 24, '2017-09-26', '2017-10-31', '<p>ookkkkkkkkk<br></p>', '2017-09-26', 2, 'F', NULL, 452, 453, NULL, 1, 1, '2017-09-26 02:13:19', 1, '2017-09-26 02:16:58'),
(17, '95ae7c1c9b0b2710d1497bbb390552b7', 'CTR-6/2017', 25, '2017-10-01', '2018-01-31', '<p>ookk<br></p>', '2017-09-27', 1, 'D', NULL, 454, 455, NULL, 1, 1, '2017-09-27 23:32:12', 1, '2017-09-27 23:33:34'),
(22, '73b0d35a7a15c91b7504bb1c016c86eb', 'CTR-7/2017', 11, '2017-10-02', '2017-12-29', '<p>okk<br></p>', '2017-10-02', 2, 'D', '2017-10-02', NULL, NULL, 485, 2, 1, '2017-10-02 02:37:13', 1, '2017-10-02 17:55:08'),
(28, 'b7b6ab986aeebd74a0625f143e085e49', 'CTR-8/2017', 12, '2017-12-01', '2018-03-30', '<p>ok<br></p>', '2017-10-02', 4, 'D', '2018-02-28', NULL, NULL, NULL, 0, 1, '2017-10-02 19:26:35', NULL, NULL),
(29, '5996d4723b3525a00ab3202946b9dbe8', 'GT-CTR-1/2017', 23, '2017-10-02', '2018-01-12', NULL, '2017-10-02', 4, 'D', '2017-10-19', NULL, NULL, NULL, 0, 1, '2017-10-02 23:16:08', NULL, NULL);

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
  `date_notif` date DEFAULT NULL,
  `pj` int(11) DEFAULT NULL,
  `pj_photo` int(11) DEFAULT NULL,
  `etat` int(11) DEFAULT '0',
  `creusr` int(11) DEFAULT NULL,
  `credat` datetime DEFAULT CURRENT_TIMESTAMP,
  `updusr` int(11) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_fournisseur` (`id_fournisseur`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=40 ;

--
-- Contenu de la table `contrats_frn`
--

INSERT INTO `contrats_frn` (`id`, `reference`, `id_fournisseur`, `date_effet`, `date_fin`, `commentaire`, `date_notif`, `pj`, `pj_photo`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(18, 'CTR-FRN-1/2017', 29, '2017-09-01', '2017-10-31', '<p>okk<br></p>', '2017-10-01', 457, NULL, 3, 1, '2017-10-01 16:26:34', 1, '2017-10-01 21:23:32'),
(37, 'CTR-FRN-2/2017', 29, '2017-11-01', '2017-12-31', '<p>ok<br></p>', '2017-12-10', 474, NULL, 0, 1, '2017-10-01 21:23:32', NULL, NULL),
(38, 'CTR-FRN-3/2017', 29, '2017-10-02', '2018-01-01', '<p>okk<br></p>', '2017-10-18', 477, NULL, 2, 1, '2017-10-02 02:42:16', 1, '2017-10-02 02:58:23'),
(39, 'GT-CTR-FRN-1/2017', 29, '2017-10-02', '2017-10-28', '<p>ok<br></p>', '2017-10-18', 487, NULL, 0, 1, '2017-10-02 23:20:25', NULL, NULL);

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
  `totalttc` double DEFAULT '0' COMMENT 'total ttc des articles',
  `totaltva` double DEFAULT '0' COMMENT 'total tva des articles',
  `claus_comercial` text COMMENT 'clauses commercial devis',
  `devis_pdf` int(11) DEFAULT NULL,
  `etat` int(11) DEFAULT '0' COMMENT 'Etat devis defaut 0',
  `creusr` int(11) DEFAULT NULL,
  `credat` datetime DEFAULT NULL,
  `updusr` int(11) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_id_client` (`id_client`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

--
-- Contenu de la table `devis`
--

INSERT INTO `devis` (`id`, `tkn_frm`, `reference`, `id_client`, `tva`, `id_commercial`, `date_devis`, `type_remise`, `valeur_remise`, `totalht`, `totalttc`, `totaltva`, `claus_comercial`, `devis_pdf`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(11, NULL, 'DEV_0_2017', 1, NULL, 1, '2017-09-13', 'P', NULL, 1455454, 1746544.8, 291090.8, 'Paiement 100% àla commande pour', 476, 1, 1, '2017-09-13 01:50:08', NULL, NULL),
(12, NULL, 'DEV_12_2017', 8, NULL, 1, '2017-09-13', 'P', NULL, 7277270, 8732724, 1455454, 'Paiement 100% à la commande pour', NULL, 1, 1, '2017-09-13 01:55:43', NULL, NULL),
(21, 'ef4c85458e75208c1f1028d733ef3450', 'DEV_21_2017', 8, 'O', 1, '2017-09-05', 'P', NULL, 1455466, 1746559, 291093.2, 'Paiement 100% à la commande pour', NULL, 1, 1, '2017-09-13 13:12:27', 1, '2017-09-13 16:35:45'),
(23, '93a0f6bc29f45532f90f93e38c386447', 'DEV_23_2017', 1, 'O', 1, '2017-09-14', 'P', NULL, 1455454, 1455454, 0, 'Paiement 100% à la commande pour', NULL, 1, 1, '2017-09-14 00:10:33', 1, '2017-09-14 00:11:59'),
(24, 'ea302005ed0aecdd4d0c2eaf2122829e', 'DEV_24_2017', 1, 'N', 1, '2017-09-16', 'P', NULL, 10, 10, 0, 'Paiement 100% à la commande pour', NULL, 0, 1, '2017-09-16 14:26:13', NULL, NULL),
(25, '82618ea283a75a6bba5b8611a3f94557', 'DEV_25_2017', 16, 'N', 1, '2017-09-23', 'P', NULL, 20, 20, 0, 'Paiement 100% à la commande pour', NULL, 0, 1, '2017-09-16 17:37:17', 1, '2017-09-23 01:24:14');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=148 ;

--
-- Contenu de la table `d_devis`
--

INSERT INTO `d_devis` (`id`, `order`, `id_devis`, `tkn_frm`, `id_produit`, `ref_produit`, `designation`, `qte`, `prix_unitaire`, `type_remise`, `remise_valeur`, `tva`, `prix_ht`, `prix_ttc`, `total_ht`, `total_ttc`, `total_tva`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(91, 1, 12, '31750c0a121e6dcb693a4d1ca494e2c6', 4, 'Ref2', 'Article 2 produit VAST', 5, 1455454, 'P', 0, 0, NULL, NULL, 7277270, 8732724, 1455454, '1', NULL, NULL, NULL),
(92, 1, 13, '5e4d828bb3803694724801c841ecfd02', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(93, 1, 17, '410fca9ef57b94091cfd14b4b0d86b85', 4, 'Ref2', 'Article 2 produit VAST', 2, 1455454, 'P', 0, 0, NULL, NULL, 2910908, 3493089.6, 582181.6, '1', NULL, NULL, NULL),
(94, 2, 17, '410fca9ef57b94091cfd14b4b0d86b85', 3, 'Ref1', 'X1', 1, 12.3, 'P', 0, 0, NULL, NULL, 12, 14.76, 2.4, '1', NULL, NULL, NULL),
(95, 1, 18, '4e36902a40a10adcf735f00193d6d74c', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(96, 1, 19, '246d6dc9bcf6441c3e241293f434104f', 4, 'Ref2', 'Article 2 produit VAST', 1, 100, 'P', 0, 0, NULL, NULL, 100, 120, 20, '1', NULL, NULL, NULL),
(129, 2, 21, 'ef4c85458e75208c1f1028d733ef3450', 3, 'Ref1', 'X1', 3, 12.3, 'P', 0, 0, NULL, NULL, 12, 14.76, 2.4, '1', NULL, NULL, NULL),
(136, 2, 22, '6b2ab442af25dac3771500a7faf20a25', 3, 'Ref1', 'X1', 1, 12.3, 'P', 0, 0, NULL, NULL, 12, 14.76, 2.4, '1', NULL, '1', '2017-09-13 23:01:28'),
(137, 3, 22, '6b2ab442af25dac3771500a7faf20a25', 4, 'Ref2', 'Article 2 produit VAST', 10, 1455454, 'P', 0, 0, NULL, NULL, 14554540, 17465448, 2910908, '1', NULL, '1', '2017-09-13 22:30:54'),
(145, 1, 23, '93a0f6bc29f45532f90f93e38c386447', 4, 'Ref2', 'Article 2 produit VAST', 1, 1455454, 'P', 0, 0, NULL, NULL, 1455454, 1746544.8, 291090.8, '1', NULL, NULL, NULL),
(146, 1, 24, 'ea302005ed0aecdd4d0c2eaf2122829e', 19, 'a_skyware_1.2m Ku-Ba', 'Antenne VSAT 1.2m bande Ku Skyware Globa', 1, 10, 'P', 0, 0, NULL, NULL, 10, 12, 2, '1', NULL, NULL, NULL),
(147, 1, 25, '82618ea283a75a6bba5b8611a3f94557', 19, 'a_skyware_1.2m Ku-Ba', 'Antenne VSAT 1.2m bande Ku Skyware Globa', 5, 20, 'P', 0, 0, NULL, NULL, 20, 24, 4, '1', NULL, NULL, NULL);

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
-- Structure de la table `echeances_contrat`
--

CREATE TABLE IF NOT EXISTS `echeances_contrat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`,`date_echeance`,`montant`),
  KEY `fk_contrat_echeance` (`idcontrat`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=37 ;

--
-- Contenu de la table `echeances_contrat`
--

INSERT INTO `echeances_contrat` (`id`, `tkn_frm`, `order`, `date_echeance`, `montant`, `commentaire`, `idcontrat`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(1, '280b9532f38b681e492a946901bb6a75', 0, '0000-00-00', 0, 'okk', NULL, 0, 1, '2017-09-17 02:39:08', NULL, NULL),
(2, 'd71733b466aba09cbf75609bbf04c242', 1, '0000-00-00', 0, 'ok', NULL, 0, 1, '2017-09-17 02:41:35', NULL, NULL),
(3, 'bd15c792eabb0ddb94b2957f4791edac', 1, '0000-00-00', 0, 'okk', NULL, 0, 1, '2017-09-17 02:49:47', NULL, NULL),
(4, '55a31a199a934299f81b730c55e4ef7b', 1, '0000-00-00', 0, 'okkk', NULL, 0, 1, '2017-09-17 13:45:32', NULL, NULL),
(5, '431a6d0960af44c721ac47906964e125', 1, '0000-00-00', 0, 'test', NULL, 0, 1, '2017-09-17 13:50:10', NULL, NULL),
(6, '431a6d0960af44c721ac47906964e125', 2, '0000-00-00', 0, 'vvv', NULL, 0, 1, '2017-09-17 13:50:55', NULL, NULL),
(7, 'd727b9f049758a8ab0c732ebdd3d379a', 1, '0000-00-00', 0, 'hdhd', NULL, 0, 1, '2017-09-17 13:52:07', NULL, NULL),
(8, 'd727b9f049758a8ab0c732ebdd3d379a', 2, '0000-00-00', 0, 'cccc', NULL, 0, 1, '2017-09-17 13:52:33', NULL, NULL),
(9, 'd727b9f049758a8ab0c732ebdd3d379a', 3, '0000-00-00', 0, 'ddddd', NULL, 0, 1, '2017-09-17 13:54:21', NULL, NULL),
(10, 'd727b9f049758a8ab0c732ebdd3d379a', 4, '0000-00-00', 0, 'kkk', NULL, 0, 1, '2017-09-17 13:57:00', NULL, NULL),
(11, '179584f02af18c2cfa9ba061ef666c48', 1, '0000-00-00', 0, 'ddd', NULL, 0, 1, '2017-09-17 13:59:36', NULL, NULL),
(12, '179584f02af18c2cfa9ba061ef666c48', 2, '0000-00-00', 0, 'ddd', NULL, 0, 1, '2017-09-17 14:00:08', NULL, NULL),
(13, 'a7aadf89c66c3431c0bf86f9d89c3275', 1, '0000-00-00', 0, 'ccc', NULL, 0, 1, '2017-09-17 14:02:19', NULL, NULL),
(16, '13486b070fa62a4458ee104ec54198c6', 1, '2017-10-01', 1000000, 'waaak', 15, 0, 1, '2017-09-23 01:33:36', 1, '2017-10-02 23:11:06'),
(17, '7a11fcb558cde5ab934960aabdf15fa7', 1, '2017-09-23', 0, 'waawaaaw', NULL, 0, 1, '2017-09-23 02:06:40', 1, '2017-09-23 02:06:48'),
(18, '13486b070fa62a4458ee104ec54198c6', 2, '2017-10-17', 746559, 'okk', 15, 0, 1, '2017-09-30 17:11:58', 1, '2017-10-02 23:10:54'),
(19, '22ba166b45210254b0e9ac86e717c291', 1, '2018-02-06', 1005000, 'okk', NULL, 0, 1, '2017-10-02 05:32:44', NULL, NULL),
(20, '992a6545ed6ec78772de332482c473c4', 1, '2018-01-03', 25000, 'ok', NULL, 0, 1, '2017-10-02 05:44:45', NULL, NULL),
(21, 'eeca6f2c133d556003f82b3322d98994', 1, '2017-10-02', 16000, 'okk', NULL, 0, 1, '2017-10-02 12:08:32', NULL, NULL),
(22, 'eeca6f2c133d556003f82b3322d98994', 2, '2017-10-02', 10000, 'ok', NULL, 0, 1, '2017-10-02 12:37:32', NULL, NULL),
(23, 'ebeceb99f4b2a4de16402c92e7e65e50', 1, '2017-10-02', 110000, 'ppp', NULL, 0, 1, '2017-10-02 17:15:39', NULL, NULL),
(24, '432399ada7787e6bf03f7affe91369bc', 1, '2017-10-02', 1330000, 'ppp', NULL, 0, 1, '2017-10-02 17:19:34', NULL, NULL),
(25, '1a9923629229eee357f639a8b5f4dadc', 1, '2017-10-02', 1099100, 'en fin', NULL, 0, 1, '2017-10-02 17:51:32', 1, '2017-10-02 17:51:40'),
(26, '25132dbec947fb31edeb70ff8a675365', 1, '2017-10-02', 10000, 'ok', NULL, 0, 1, '2017-10-02 18:25:41', NULL, NULL),
(28, 'a262ebb33a6df52e76bf683651f844c9', 1, '2017-10-02', 1200000, 'ok', NULL, 0, 1, '2017-10-02 19:06:11', NULL, NULL),
(30, 'b7b6ab986aeebd74a0625f143e085e49', 1, '2017-12-06', 2732724, 'ok', NULL, 0, 1, '2017-10-02 19:24:03', NULL, NULL),
(31, 'b7b6ab986aeebd74a0625f143e085e49', 2, '2017-12-21', 6000000, 'okk', NULL, 0, 1, '2017-10-02 19:24:32', NULL, NULL),
(34, '4e78415e121a3b49f7cfcdb75a851916', 1, '2018-02-13', 10000, NULL, NULL, 0, 1, '2017-10-02 23:02:28', NULL, NULL),
(35, '871075f2510fa2e637427588dfed0761', 1, '2017-10-12', 10000, NULL, NULL, 0, 1, '2017-10-02 23:09:14', NULL, NULL),
(36, '5996d4723b3525a00ab3202946b9dbe8', 1, '2018-01-04', 1455454, NULL, NULL, 0, 1, '2017-10-02 23:13:47', 1, '2017-10-02 23:15:51');

-- --------------------------------------------------------

--
-- Structure de la table `encaissements`
--

CREATE TABLE IF NOT EXISTS `encaissements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  PRIMARY KEY (`id`),
  KEY `fk_facture` (`idfacture`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `espionnage_update`
--

INSERT INTO `espionnage_update` (`id`, `updt_id`, `table`, `id_item`, `column`, `val_old`, `val_new`, `user`, `updtdat`) VALUES
(1, 'cfb6d23f1256fade1d1ea90b5b81adf2', 'users_sys', 19, 'pass', '7480d19b8e17e2f55de992feda6a74a6', 'd41d8cd98f00b204e9800998ecf8427e', 'admin', '2017-09-13 15:57:03'),
(2, '4d364b15758dba08fe919dfce0cb9cfb', 'users_sys', 18, 'pass', '5a05679021426829ab75ac9fa6655947', 'd41d8cd98f00b204e9800998ecf8427e', 'admin', '2017-09-13 16:17:30');

-- --------------------------------------------------------

--
-- Structure de la table `factures`
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
  `idcontrat` int(11) DEFAULT NULL COMMENT 'Contrat',
  `iddevis` int(11) DEFAULT NULL COMMENT 'Devis',
  `idbl` int(11) DEFAULT NULL COMMENT 'Bon de livraison',
  `date_facture` date DEFAULT NULL,
  `etat` int(11) DEFAULT '0',
  `creusr` int(11) DEFAULT NULL,
  `credat` datetime DEFAULT CURRENT_TIMESTAMP,
  `updusr` int(11) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_contrat` (`idcontrat`),
  KEY `fk_devis` (`iddevis`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=36 ;

--
-- Contenu de la table `factures`
--

INSERT INTO `factures` (`id`, `ref`, `base_fact`, `total_ht`, `total_tva`, `total_ttc`, `total_paye`, `reste`, `client`, `tva`, `idcontrat`, `iddevis`, `idbl`, `date_facture`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(3, 'reef', 'C', 10, 0, 10, 0, 10, '1', NULL, 16, NULL, NULL, '2017-09-01', 0, 1, '2017-09-29 20:07:16', NULL, NULL),
(33, 'GT-FCT-1/2017', 'C', 820000, 180000, 1000000, 0, 1000000, 'DENOMI9', NULL, 15, NULL, NULL, '2017-10-01', 0, 1, '2017-10-01 19:37:53', NULL, NULL),
(34, 'GT-FCT-2/2017', 'C', 10, 0, 10, 0, 10, 'DE111', NULL, 16, NULL, NULL, '2017-10-01', 0, 1, '2017-10-01 19:37:53', NULL, NULL),
(35, 'GT-FCT-3/2017', 'C', 48, 0, 48, 0, 48, 'den', NULL, 17, NULL, NULL, '2017-10-01', 0, 1, '2017-10-01 19:37:53', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `forgot`
--

CREATE TABLE IF NOT EXISTS `forgot` (
  `token` varchar(32) CHARACTER SET latin1 NOT NULL,
  `user` int(2) NOT NULL,
  `etat` int(11) NOT NULL,
  `dat` datetime NOT NULL,
  `expir` datetime NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id demande Forgot',
  `ip` varchar(16) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Ip Demande',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table recovery MDP user' AUTO_INCREMENT=1 ;

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
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_code` (`code`),
  UNIQUE KEY `unique_denomination` (`denomination`),
  KEY `fk_client_pays` (`id_pays`),
  KEY `fk_client_devise` (`id_devise`),
  KEY `fk_ville` (`id_ville`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=31 ;

--
-- Contenu de la table `fournisseurs`
--

INSERT INTO `fournisseurs` (`id`, `code`, `denomination`, `r_social`, `r_commerce`, `nif`, `nom`, `prenom`, `civilite`, `adresse`, `id_pays`, `id_ville`, `tel`, `fax`, `bp`, `email`, `rib`, `id_devise`, `pj`, `pj_photo`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(29, 'FRN-1/2017', 'jkj', ',l,', NULL, NULL, NULL, NULL, 'Femme', 'adr', 242, NULL, '04444444444444444444444', NULL, NULL, 'em@em', NULL, NULL, NULL, NULL, 1, 1, '2017-10-01 15:43:08', 1, '2017-10-01 16:33:08'),
(30, 'FRN-2/2017', 'jkje', ',l,', NULL, NULL, NULL, NULL, 'Femme', 'adr', 242, NULL, '04444444444444444444444', NULL, NULL, 'em@em', NULL, NULL, NULL, NULL, 1, 1, '2017-10-01 15:43:08', 1, '2017-10-01 16:33:08');

-- --------------------------------------------------------

--
-- Structure de la table `modul`
--

CREATE TABLE IF NOT EXISTS `modul` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id modul',
  `modul` varchar(25) CHARACTER SET latin1 NOT NULL COMMENT 'nom modul',
  `description` varchar(100) CHARACTER SET latin1 NOT NULL COMMENT 'Description',
  `rep_modul` varchar(100) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Répertoir module',
  `tables` varchar(100) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Tables utlisées par le module',
  `app_modul` varchar(25) CHARACTER SET latin1 NOT NULL COMMENT 'Application de base',
  `modul_setting` varchar(25) CHARACTER SET latin1 DEFAULT 'NA' COMMENT 'Si is_setting Choix modul',
  `is_setting` tinyint(1) DEFAULT '0' COMMENT 'Modul de parametre',
  `etat` int(11) NOT NULL DEFAULT '0' COMMENT 'Etat de module',
  `services` varchar(40) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Services de Module',
  PRIMARY KEY (`id`),
  UNIQUE KEY `modul` (`modul`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Table Systeme Modules' AUTO_INCREMENT=111 ;

--
-- Contenu de la table `modul`
--

INSERT INTO `modul` (`id`, `modul`, `description`, `rep_modul`, `tables`, `app_modul`, `modul_setting`, `is_setting`, `etat`, `services`) VALUES
(1, 'Systeme', 'Applications utilises par le Systeme', 'tdb', NULL, 'tdb', NULL, 0, 10, '[-1-]'),
(2, 'users', 'Utilisateurs', 'users', 'users_sys', 'user', NULL, 0, 0, '[-1-]'),
(3, 'modul_mgr', 'Modules', 'modul_mgr', 'modul,task,task_action', 'modul', NULL, 0, 0, '[-1-]'),
(4, 'services', 'Services', 'users/settings/services', 'services', 'services', 'users', 1, 0, '[-1-]'),
(28, 'villes', 'Gestion Villes', 'Systeme/settings/villes', 'ref_villes', 'villes', 'Systeme', 1, 0, '[-1-]'),
(77, 'categorie_client', 'Gestion Catégorie Client', 'clients/settings/categorie_client', 'categorie_client', 'categorie_client', 'clients', 1, 0, '[-1-]'),
(87, 'clients', 'Gestion Clients', 'clients', 'clients', 'clients', NULL, 0, 0, '[-1-]'),
(93, 'vente', 'Gestion Vente', 'vente/main', 'devis', 'vente', NULL, 0, 0, '[-1-2-]'),
(95, 'departements', 'Gestion Départements', 'Systeme/settings/departements', 'ref_departement', 'departements', 'Systeme', 1, 0, '[-1-]'),
(97, 'produits', 'Gestion des produits', 'produits', 'produits, ref_unites_vente, ref_categories_produits, ref_types_produits', 'produits', NULL, 0, 0, '[-1-]'),
(98, 'categories_produits', 'Gestion des catégories de produits', 'produits/settings/categories_produits', 'ref_categories_produits', 'categories_produits', 'produits', 1, 0, '[-1-]'),
(99, 'types_produits', 'Gestion des types de produits', 'produits/settings/types_produits', 'ref_types_produits', 'types_produits', 'produits', 1, 0, '[-1-]'),
(100, 'unites_vente', 'Gestion des unités de vente', 'produits/settings/unites_vente', 'ref_unites_vente', 'unites_vente', 'produits', 1, 0, '[-1-]'),
(101, 'regions', 'Gestion des régions', 'Systeme/settings/regions', 'ref_region', 'regions', 'Systeme', 1, 0, '[-1-]'),
(102, 'pays', 'Gestion Pays', 'Systeme/settings/pays', 'ref_pays', 'pays', 'Systeme', 1, 0, '[-1-]'),
(103, 'type_echeance', 'Gestion Type Echeance', 'contrats/settings/type_echeance', 'ref_type_echeance', 'type_echeance', 'contrats', 1, 0, '[-1-]'),
(104, 'contrats', 'Abonnements', 'vente/submodul/contrats', 'contrats', 'contrats', 'vente', 2, 0, '[-1-]'),
(105, 'devis', 'Gestion Devis', 'vente/submodul/devis', 'devis', 'devis', 'vente', 2, 0, '[-1-2-]'),
(106, 'contrats_fournisseurs', 'Contrats Fournisseur', 'contrats_fournisseurs/main', 'contrats_frn', 'contrats_fournisseurs', NULL, 0, 0, '[-1-]'),
(107, 'fournisseurs', 'Gestion Fournisseurs', 'fournisseurs/main', 'fournisseurs', 'fournisseurs', NULL, 0, 0, '[-1-]'),
(109, 'proforma', 'Gestion Proforma', 'vente/submodul/proforma', 'proforma', 'proforma', 'vente', 2, 0, '[-1-2-3-5-4-]'),
(110, 'factures', 'Gestion des factures', 'factures/main', 'factures', 'factures', NULL, 0, 0, '[-1-]');

-- --------------------------------------------------------

--
-- Structure de la table `produits`
--

CREATE TABLE IF NOT EXISTS `produits` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id',
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
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_produit_categorie` (`idcategorie`),
  KEY `fk_produit_uv` (`iduv`),
  KEY `fk_produit_type` (`idtype`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

--
-- Contenu de la table `produits`
--

INSERT INTO `produits` (`id`, `ref`, `designation`, `stock_min`, `idcategorie`, `iduv`, `idtype`, `prix_vente`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(17, 'LNB_ku-band_high_pll', 'LNB PLL bande Ku', 5, 7, 2, 2, NULL, 1, 1, '2017-09-09 11:21:12', 1, '2017-09-14 11:44:15'),
(19, 'a_skyware_1.2m Ku-Band_122', 'Antenne VSAT 1.2m bande Ku Skyware Global', 3, 6, 2, 2, NULL, 0, 1, '2017-09-09 11:28:54', 1, '2017-09-23 03:39:35'),
(20, 'PRD-1/2017', 'STYLO', 100, 3, 2, 2, NULL, 0, 1, '2017-09-23 01:09:31', 1, '2017-09-23 03:44:09');

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
-- Structure de la table `ref_departement`
--

CREATE TABLE IF NOT EXISTS `ref_departement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `departement` varchar(20) CHARACTER SET latin1 DEFAULT NULL,
  `id_region` int(11) NOT NULL,
  `etat` tinyint(1) DEFAULT '0',
  `creusr` varchar(50) CHARACTER SET latin1 NOT NULL,
  `credat` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updusr` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `region_depart` (`id_region`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `ref_departement`
--

INSERT INTO `ref_departement` (`id`, `departement`, `id_region`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(1, 'N''djamena Centre', 24, 1, 'admin', '2017-07-09 17:38:55', 'admin', '2017-09-15 17:57:39');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=245 ;

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

-- --------------------------------------------------------

--
-- Structure de la table `ref_region`
--

CREATE TABLE IF NOT EXISTS `ref_region` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identifiant de ligne',
  `id_pays` int(11) DEFAULT '242' COMMENT 'le pays de la region par default Tchad',
  `region` varchar(30) CHARACTER SET latin1 NOT NULL COMMENT 'libelle region',
  `etat` int(2) NOT NULL DEFAULT '0' COMMENT 'l''etat de la ligne',
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
(24, 242, 'Ville de N''Djamena', 1, '', '2017-03-19 21:47:48', NULL, NULL),
(25, 242, 'Wadi Fira', 1, '', '2017-03-19 21:47:48', '1', '2017-04-02 13:48:49'),
(30, 242, 'Lacc', 1, '1', '2017-04-02 13:50:53', '1', '2017-07-09 18:41:09');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `ref_types_produits`
--

INSERT INTO `ref_types_produits` (`id`, `type_produit`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(1, 'Produit', 1, NULL, '2017-10-01 16:41:22', 1, '2017-10-01 18:41:53'),
(2, 'Prestation', 1, 1, '2017-08-26 19:21:11', 1, '2017-10-01 18:42:02'),
(3, 'Abonnement', 1, NULL, '2017-10-01 16:41:33', 1, '2017-10-01 18:41:58');

-- --------------------------------------------------------

--
-- Structure de la table `ref_type_echeance`
--

CREATE TABLE IF NOT EXISTS `ref_type_echeance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_echeance` varchar(50) DEFAULT NULL COMMENT 'Mensuelle,Trimestrielle,Annuelle',
  `etat` int(11) DEFAULT '0',
  `creusr` varchar(50) DEFAULT NULL,
  `credat` datetime DEFAULT CURRENT_TIMESTAMP,
  `updusr` varchar(50) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Contenu de la table `ref_type_echeance`
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
  `id_departement` int(11) DEFAULT NULL COMMENT 'id du departement',
  `ville` varchar(20) CHARACTER SET latin1 NOT NULL COMMENT 'libelle',
  `latitude` varchar(15) NOT NULL COMMENT 'coordonnées geographique',
  `longitude` varchar(15) NOT NULL COMMENT 'coordonnées geographique',
  `etat` int(11) NOT NULL DEFAULT '0' COMMENT 'etat de ligne',
  `creusr` varchar(60) NOT NULL COMMENT 'Crée par',
  `credat` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date de création',
  `updusr` varchar(60) DEFAULT NULL COMMENT 'Derniere modif',
  `upddat` datetime DEFAULT NULL COMMENT 'Date deniere modif',
  PRIMARY KEY (`id`),
  KEY `ville_dept` (`id_departement`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=44 ;

--
-- Contenu de la table `ref_ville`
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
(43, 1, 'N''djamena', '', '', 1, '', '2017-03-19 23:45:09', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `rules_action`
--

CREATE TABLE IF NOT EXISTS `rules_action` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'rule id',
  `appid` int(11) NOT NULL COMMENT 'id task',
  `idf` varchar(32) CHARACTER SET latin1 DEFAULT NULL COMMENT 'IDF Rul Mgt',
  `service` int(11) DEFAULT NULL COMMENT 'Service ID',
  `userid` int(11) NOT NULL COMMENT 'id user',
  `action_id` int(11) NOT NULL COMMENT 'id action de task',
  `descrip` varchar(75) CHARACTER SET latin1 NOT NULL COMMENT 'description de rule',
  `type` int(11) DEFAULT '0' COMMENT 'action=0 task=1',
  `creusr` varchar(20) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Create by',
  `credat` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'Date Create',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_rule_idf_usrid` (`idf`,`userid`),
  KEY `rules_action_task_appid` (`appid`),
  KEY `rules_action_user_sys` (`userid`),
  KEY `rule_action_task_action` (`action_id`),
  KEY `rule_action_service_id` (`service`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Table store rules for each user for each App and action' AUTO_INCREMENT=23887 ;

--
-- Contenu de la table `rules_action`
--

INSERT INTO `rules_action` (`id`, `appid`, `idf`, `service`, `userid`, `action_id`, `descrip`, `type`, `creusr`, `credat`) VALUES
(23645, 455, 'e69f84a801ee1525f20f34e684688a9b', 1, 1, 652, 'Gestion des catégories de produits', 0, '1', '2017-10-02 22:26:33'),
(23646, 455, '90f6eba3e0ed223e73d250278cb445d5', 1, 1, 653, 'Modifier catégorie', 0, '1', '2017-10-02 22:26:33'),
(23647, 455, 'c62968a45ae9cfa8b127ac1b5573988a', 1, 1, 654, 'Valider catégorie', 0, '1', '2017-10-02 22:26:33'),
(23648, 455, '6f43a6bcbd293f958aff51953559104e', 1, 1, 655, 'Désactiver catégorie', 0, '1', '2017-10-02 22:26:33'),
(23649, 456, 'd26f5940e88a494c0eb65047aab9a17b', 1, 1, 656, 'Ajouter une catégorie', 0, '1', '2017-10-02 22:26:33'),
(23650, 457, '27957c6d0f6869d4d90287cd50b6dde9', 1, 1, 657, 'Modifier une catégorie', 0, '1', '2017-10-02 22:26:33'),
(23651, 458, '41b48dd567e4f79e35261a47b7bad751', 1, 1, 658, 'Valider une catégorie', 0, '1', '2017-10-02 22:26:33'),
(23652, 459, '90dc20c4d1ad7be7fac8ec34d5ac26b3', 1, 1, 659, 'Supprimer une catégorie', 0, '1', '2017-10-02 22:26:33'),
(23653, 333, '6edc543080c65eca3993445c295ff94b', 1, 1, 497, 'Gestion Catégorie Client', 0, '1', '2017-10-02 22:26:33'),
(23654, 333, '142a68a109abd0462ea44fcadffe56de', 1, 1, 506, 'Editer Catégorie Client', 0, '1', '2017-10-02 22:26:33'),
(23655, 333, '70df89fa2654d8b10d7fc7e75e178b7e', 1, 1, 507, 'Activer Catégorie Client', 0, '1', '2017-10-02 22:26:33'),
(23656, 333, '109e82d6db5721f63cd827e9fd224216', 1, 1, 508, 'Désactiver Catégorie Client', 0, '1', '2017-10-02 22:26:33'),
(23657, 334, 'a5c1bd0dfd87824ff0f57c6b1e1d2c3f', 1, 1, 498, 'Ajouter Catégorie Client', 1, '1', '2017-10-02 22:26:33'),
(23658, 335, '8d901f74dfd6ee3a8f44ebd0b83fbfae', 1, 1, 499, 'Editer Catégorie Client', 1, '1', '2017-10-02 22:26:33'),
(23659, 336, 'e87327563ce6b659780d6b2c9bf8ac77', 1, 1, 500, 'Supprimer Catégorie Client', 1, '1', '2017-10-02 22:26:33'),
(23660, 337, 'c955da8d244aac06ee7595d08de7d009', 1, 1, 501, 'Valider Catégorie Client', 1, '1', '2017-10-02 22:26:33'),
(23661, 394, 'f12fb1c50aedc49c3fa3dfa2bd297bd3', 1, 1, 553, 'Gestion Clients', 0, '1', '2017-10-02 22:26:33'),
(23662, 394, 'dd3d5980299911ea854af4fa6f2e7309', 1, 1, 554, 'Editer Client', 0, '1', '2017-10-02 22:26:33'),
(23663, 394, '3c5c04a20d49ad010557a64c8cdac1ce', 1, 1, 555, 'Valider Client', 0, '1', '2017-10-02 22:26:33'),
(23664, 394, '18ace52052f2551099ecaabf049ffaec', 1, 1, 556, 'Désactiver Client', 0, '1', '2017-10-02 22:26:33'),
(23665, 394, '493f9e55fc0340763e07514c1900685a', 1, 1, 557, 'Détails Client', 0, '1', '2017-10-02 22:26:33'),
(23666, 394, '03b4f949b088e41fc9a1f3f23b7906a8', 1, 1, 558, 'Détails  Client', 0, '1', '2017-10-02 22:26:33'),
(23667, 395, '2b9d8bb8f752d1c35fb681c33e38b42b', 1, 1, 559, 'Ajouter Client', 1, '1', '2017-10-02 22:26:33'),
(23668, 396, '54aa9121e05f5e698d354022a8eab71d', 1, 1, 560, 'Editer Client', 1, '1', '2017-10-02 22:26:33'),
(23669, 397, '4eaf650e8c2221d590fac5a6a6952231', 1, 1, 561, 'Supprimer Client', 1, '1', '2017-10-02 22:26:33'),
(23670, 398, '534cd4b17fb8a371d3a20565ab8fd96e', 1, 1, 562, 'Valider Client', 1, '1', '2017-10-02 22:26:33'),
(23671, 399, '95bb6aa696ef630a335aa84e1e425e2c', 1, 1, 563, 'Détails Client', 0, '1', '2017-10-02 22:26:33'),
(23672, 485, '899d40c8f22d4f7a6f048366f1829787', 1, 1, 700, 'Gestion des contrats', 0, '1', '2017-10-02 22:26:33'),
(23673, 485, 'a20f4c5b9c9ebaa238757d6f9f9cb6fb', 1, 1, 701, 'Modifier contrat', 0, '1', '2017-10-02 22:26:33'),
(23674, 485, 'fbb243d2c2fa4200c40993e527b3a339', 1, 1, 702, 'Détail contrat', 0, '1', '2017-10-02 22:26:33'),
(23675, 485, 'e970c1414507e5b83ae39e7ddedbf15e', 1, 1, 703, 'Valider contrat', 0, '1', '2017-10-02 22:26:33'),
(23676, 485, '6908357258099272b60018c0f6fb1078', 1, 1, 704, 'Désactiver contrat', 0, '1', '2017-10-02 22:26:33'),
(23677, 485, '11cabf03a954a5476cc78cf221f04d78', 1, 1, 750, 'Détails Contrat', 0, '1', '2017-10-02 22:26:33'),
(23678, 485, '74710492392c157c6fe6d7e79ddc95fa', 1, 1, 784, 'Renouveler Contrat', 0, '1', '2017-10-02 22:26:33'),
(23679, 485, 'a717e1a94a251fd4316f34aba679c0c1', 1, 1, 788, 'Détails   Contrat ', 0, '1', '2017-10-02 22:26:33'),
(23680, 485, 'cd25d6f0f7f68e3dc35714df632e58df', 1, 1, 789, ' Détails   Contrat', 0, '1', '2017-10-02 22:26:33'),
(23681, 486, '87f4c3ed4713c3bc9e3fef60a6649055', 1, 1, 705, 'Ajouter contrat', 0, '1', '2017-10-02 22:26:33'),
(23682, 487, '9e49a431d9637544cefa2869fd7278b9', 1, 1, 706, 'Modifier contrat', 0, '1', '2017-10-02 22:26:33'),
(23683, 488, '1e9395a182a44787e493bc038cd80bbf', 1, 1, 707, 'Supprimer contrat', 0, '1', '2017-10-02 22:26:33'),
(23684, 489, '460d92834715b149c4db28e1643bd932', 1, 1, 708, 'Valider contrat', 0, '1', '2017-10-02 22:26:33'),
(23685, 490, 'bbcf2879c2f8f60cfa55fa97c6e79268', 1, 1, 709, 'Détail contrat', 0, '1', '2017-10-02 22:26:33'),
(23686, 491, 'fe058ccb890b25a54866be7f24a40363', 1, 1, 710, 'Ajouter échéance ', 0, '1', '2017-10-02 22:26:33'),
(23687, 492, '36a248f56a6a80977e5c90a5c59f39d3', 1, 1, 711, 'Modifier échéance contrat', 0, '1', '2017-10-02 22:26:33'),
(23688, 528, 'f0567980556249721f24f2fc88ebfed5', 1, 1, 783, 'Renouveler Contrat', 0, '1', '2017-10-02 22:26:33'),
(23689, 501, 'ec45512f34613446e7a2e367d4b4cfbd', 1, 1, 724, 'Gestion Contrats Fournisseurs', 0, '1', '2017-10-02 22:26:33'),
(23690, 501, 'e3c0d7e92dad7f8794b2415c334ec3ff', 1, 1, 744, 'Editer Contrat', 0, '1', '2017-10-02 22:26:33'),
(23691, 501, '9dfff1c8dcb804837200f38e95381420', 1, 1, 745, 'Valider Contrat', 0, '1', '2017-10-02 22:26:33'),
(23692, 501, '9fe39b496077065105a57ccd9ed05863', 1, 1, 746, 'Désactiver Contrat', 0, '1', '2017-10-02 22:26:33'),
(23693, 501, 'faee342ff51dbe9f835529ae5b9b2a0b', 1, 1, 779, 'Détails  Contrat ', 0, '1', '2017-10-02 22:26:33'),
(23694, 501, '83406b6b206ed08878f2b2e854932ae5', 1, 1, 780, 'Détails   Contrat  ', 0, '1', '2017-10-02 22:26:33'),
(23695, 501, '8447888bef30fb983477cc1357ff7e6f', 1, 1, 781, 'Détails    Contrat ', 0, '1', '2017-10-02 22:26:33'),
(23696, 501, 'b5455ddf628f5bf0dcb61016556da698', 1, 1, 787, ' Renouveler   Contrat ', 0, '1', '2017-10-02 22:26:33'),
(23697, 509, 'ded24eb817021c5a666a677b1565bc5e', 1, 1, 739, 'Ajouter Contrat', 0, '1', '2017-10-02 22:26:33'),
(23698, 510, 'ed6b8695494bf4ed86d5fb18690b3a59', 1, 1, 740, 'Editer Contrat', 0, '1', '2017-10-02 22:26:33'),
(23699, 511, 'b8a40913b5955209994aaa26d0e8c3d4', 1, 1, 741, 'Supprimer Contrat', 0, '1', '2017-10-02 22:26:33'),
(23700, 512, '5efb874e7d73ccd722df806e8275770f', 1, 1, 742, 'Valider Contrat', 0, '1', '2017-10-02 22:26:33'),
(23701, 527, '64a5f976687a8c5f7cd3d672cc5d9c8c', 1, 1, 778, 'Détails Contrat', 0, '1', '2017-10-02 22:26:33'),
(23702, 529, '2cc55c65e79534161108288adb00472b', 1, 1, 786, 'Renouveler  Contrat', 0, '1', '2017-10-02 22:26:33'),
(23703, 432, 'f320732af279d6f2f8ae9c98cd0216de', 1, 1, 613, 'Gestion Départements', 0, '1', '2017-10-02 22:26:33'),
(23704, 432, '96516cd0c72d814d5dcb1d86eacd29ab', 1, 1, 617, 'Editer Département', 0, '1', '2017-10-02 22:26:33'),
(23705, 432, 'ef27a63534fa9fc3bd4b5086a92db546', 1, 1, 619, 'Valider Département', 0, '1', '2017-10-02 22:26:33'),
(23706, 432, '9aed965af4c4b89a5a23c41bf685d403', 1, 1, 620, 'Désactiver Département', 0, '1', '2017-10-02 22:26:33'),
(23707, 433, '722b3ba1c7fe735e87aa7415e5502a4c', 1, 1, 614, 'Ajouter Département', 0, '1', '2017-10-02 22:26:33'),
(23708, 434, 'daeb31006124e562d284aff67360ee19', 1, 1, 615, 'Editer Département', 0, '1', '2017-10-02 22:26:33'),
(23709, 435, 'a775da608fe55c53211d4f1c6e493251', 1, 1, 616, 'Supprimer Département', 0, '1', '2017-10-02 22:26:33'),
(23710, 436, 'bbb96ec910c5000a2006db2f6e8af10a', 1, 1, 618, 'Valider Département', 0, '1', '2017-10-02 22:26:33'),
(23711, 493, '0e79510db7f03b9b6266fc7b4a612153', 1, 1, 712, 'Gestion Devis', 0, '1', '2017-10-02 22:26:33'),
(23712, 493, 'c15b00a1e37657336df8b6aa0eea2db5', 1, 1, 713, 'Modifier Devis', 0, '1', '2017-10-02 22:26:33'),
(23713, 493, 'd34b07afd92adad84e1c4c2ebd92ba95', 1, 1, 714, 'Voir détails', 0, '1', '2017-10-02 22:26:33'),
(23714, 493, '5a05eba5be17eba1f35ef8927bfa16d2', 1, 1, 715, 'Valider Devis', 0, '1', '2017-10-02 22:26:33'),
(23715, 493, '28e267a2a0647d4cb37b18abb1e7d051', 1, 1, 716, 'Voir détails', 0, '1', '2017-10-02 22:26:33'),
(23716, 494, 'd9eeb330625c1b87e0df00986a47be01', 1, 1, 717, 'Ajouter Devis', 0, '1', '2017-10-02 22:26:33'),
(23717, 495, 'da93cdb05137e15aed9c4c18bddd746a', 1, 1, 718, 'Ajouter détail devis', 0, '1', '2017-10-02 22:26:33'),
(23718, 496, 'f9f3c299f9bd0fec014f6bd3f0e06adb', 1, 1, 719, 'Modifier Devis', 0, '1', '2017-10-02 22:26:33'),
(23719, 497, 'e14cce6f1faf7784adb327581c516b90', 1, 1, 720, 'Supprimer Devis', 0, '1', '2017-10-02 22:26:33'),
(23720, 498, '38f10871792c133ebcc6040e9a11cde8', 1, 1, 721, 'Modifier détail Devis', 0, '1', '2017-10-02 22:26:33'),
(23721, 499, '8def42e75fd4aee61c378d9fb303850d', 1, 1, 722, 'Afficher détail devis', 0, '1', '2017-10-02 22:26:33'),
(23722, 500, '7666e87783b0f5a7eec1eea7593f7dfe', 1, 1, 723, 'Valider Devis', 0, '1', '2017-10-02 22:26:33'),
(23723, 530, 'd76c286028993aff54af01da5dc4b233', 1, 1, 790, 'Gestion des factures', 0, '1', '2017-10-02 22:26:33'),
(23724, 530, '98a697ec628778765b25e02ba2929d38', 1, 1, 791, 'Liste complément', 0, '1', '2017-10-02 22:26:33'),
(23725, 530, '3b6d1456980ea86c0f44c16c464ca790', 1, 1, 792, 'Liste encaissements', 0, '1', '2017-10-02 22:26:33'),
(23726, 530, '9a51fb5298e39a28af3ad6272fc51177', 1, 1, 793, 'Valider facture', 0, '1', '2017-10-02 22:26:33'),
(23727, 530, '851f1d4c13f6025f69f5b9315321d350', 1, 1, 794, 'Désactiver facture', 0, '1', '2017-10-02 22:26:33'),
(23728, 530, '5c79105956d28b5cac52f85784039919', 1, 1, 795, 'Détail facture', 0, '1', '2017-10-02 22:26:33'),
(23729, 531, '55c3c5d2d93143b315513b7401043c8b', 1, 1, 796, 'complements', 0, '1', '2017-10-02 22:26:33'),
(23730, 531, 'dfc4772cc03cf0b92a47f54fc6a2326e', 1, 1, 797, 'Modifier complément', 0, '1', '2017-10-02 22:26:33'),
(23731, 532, '03a18bdd5201e433a3c523a2b34d059a', 1, 1, 798, 'Ajouter complément', 0, '1', '2017-10-02 22:26:33'),
(23732, 533, '88d9bc979cd1102eb8196e7f5e6042ca', 1, 1, 799, 'Encaissement', 0, '1', '2017-10-02 22:26:33'),
(23733, 533, 'c690cc68f5257c0c225b8b8e6126ea56', 1, 1, 800, 'Modifier encaissement', 0, '1', '2017-10-02 22:26:33'),
(23734, 533, '1dc06f602e8630f273d44aa2751b2127', 1, 1, 801, 'Détails encaissement', 0, '1', '2017-10-02 22:26:33'),
(23735, 534, 'e4866b292dbc3c9c5d9cc37273a5b498', 1, 1, 802, 'Ajouter encaissement', 0, '1', '2017-10-02 22:26:33'),
(23736, 535, '8665be10959f39df4f149962eb70041f', 1, 1, 803, 'Modifier complément', 0, '1', '2017-10-02 22:26:33'),
(23737, 536, '585d411904bf7d9e83d21b2810ff1d6c', 1, 1, 804, 'Modifier encaissement', 0, '1', '2017-10-02 22:26:33'),
(23738, 537, '8c8b058a4d030cdc8b49c9008abb2e92', 1, 1, 805, 'Supprimer complément', 0, '1', '2017-10-02 22:26:33'),
(23739, 538, '6bf7d5180940f03567a5d711e8563ba4', 1, 1, 806, 'Supprimer encaissement', 0, '1', '2017-10-02 22:26:33'),
(23740, 539, '256abad0ec8e3bc8ed1c0653ff177255', 1, 1, 807, 'Valider facture', 0, '1', '2017-10-02 22:26:33'),
(23741, 540, 'b5dc5719c1f96df7334f371dcf51a5b6', 1, 1, 808, 'Détail encaissement', 0, '1', '2017-10-02 22:26:33'),
(23742, 541, '16fbf6fdcbb72f863bcf7e4ef28d8e75', 1, 1, 809, 'Détails facture', 0, '1', '2017-10-02 22:26:33'),
(23743, 502, '6beb279abea6434e3b73229aebadc081', 1, 1, 725, 'Gestion Fournisseurs', 0, '1', '2017-10-02 22:26:33'),
(23744, 502, 'ff95747f3a590b6539803f2a9a394cd5', 1, 1, 730, 'Editer Fournisseur', 0, '1', '2017-10-02 22:26:33'),
(23745, 502, 'fea982f5074995d4ccd6211a71ab2680', 1, 1, 731, 'Valider Fournisseur', 0, '1', '2017-10-02 22:26:33'),
(23746, 502, '1d0411a0dec15fc28f054f1a79d95618', 1, 1, 732, 'Désactiver Fournisseur', 0, '1', '2017-10-02 22:26:33'),
(23747, 502, 'a52affdd109b9362ce47ff18aad53e2a', 1, 1, 737, 'Détails Fournisseur', 0, '1', '2017-10-02 22:26:33'),
(23748, 502, 'c6fe5f222dd563204188e8bf0d69bd9e', 1, 1, 738, 'Détails  Fournisseur', 0, '1', '2017-10-02 22:26:33'),
(23749, 503, 'd644015625a9603adb2fcc36167aeb73', 1, 1, 726, 'Ajouter Fournisseur', 0, '1', '2017-10-02 22:26:33'),
(23750, 504, '58c6694abfd3228d927a5d5a06d40b94', 1, 1, 727, 'Editer Fournisseur', 0, '1', '2017-10-02 22:26:33'),
(23751, 505, 'd072f81cd779e4b0152953241d713ca3', 1, 1, 728, 'Supprimer Fournisseur', 0, '1', '2017-10-02 22:26:33'),
(23752, 506, '657351ce5aa227513e3b50dea77db918', 1, 1, 729, 'Valider Fournisseur', 0, '1', '2017-10-02 22:26:33'),
(23753, 508, '83b693fe35a1be29edafe4f6170641aa', 1, 1, 736, 'Détails Fournisseur', 0, '1', '2017-10-02 22:26:33'),
(23754, 21, 'b8e62907d367fb44d644a5189cd07f42', 1, 1, 9, 'Modules', 1, '1', '2017-10-02 22:26:33'),
(23755, 21, '05ce9e55686161d99e0714bb86243e5b', 1, 1, 11, 'Editer Module', 0, '1', '2017-10-02 22:26:33'),
(23756, 21, '819cf9c18a44cb80771a066768d585f2', 1, 1, 12, 'Exporter Module', 0, '1', '2017-10-02 22:26:33'),
(23757, 21, 'd2fc3ee15cee5208a8b9c70f1e53c196', 1, 1, 13, 'Liste task modul', 0, '1', '2017-10-02 22:26:33'),
(23758, 21, 'ad75e6b877f20e3d6fc1789da4dcb3e6', 1, 1, 75, 'Editer Module', 0, '1', '2017-10-02 22:26:33'),
(23759, 21, '064a9b0eff1006fd4f25cb4eaf894ca1', 1, 1, 77, 'Liste task modul Setting', 0, '1', '2017-10-02 22:26:33'),
(23760, 21, 'ac4eb0c94da00a48ad5d995f5e9e9366', 1, 1, 232, 'MAJ Module', 0, '1', '2017-10-02 22:26:33'),
(23761, 22, '44bd5341b0ab41ced21db8b3e92cf5aa', 1, 1, 10, 'Ajouter un Modul', 1, '1', '2017-10-02 22:26:33'),
(23762, 24, '8653b156f1a4160a12e5a94b211e59a2', 1, 1, 16, 'Liste Action Task', 0, '1', '2017-10-02 22:26:33'),
(23763, 24, '86aced763bc02e1957a5c740fb37b4f7', 1, 1, 22, 'Supprimer Application', 0, '1', '2017-10-02 22:26:33'),
(23764, 24, 'f07352e32fe86da1483c6ab071b7e7a9', 1, 1, 99, 'Ajout Affichage WF', 0, '1', '2017-10-02 22:26:33'),
(23765, 25, '1c452aff8f1551b3574e15b74147ea56', 1, 1, 14, 'Ajouter Task Modul', 1, '1', '2017-10-02 22:26:34'),
(23766, 26, 'f085fe4610576987db963501297e4d91', 1, 1, 15, 'Editer Task Modul', 1, '1', '2017-10-02 22:26:34'),
(23767, 26, '38702c272a6f4d334c2f4c3684c8b163', 1, 1, 18, 'Ajouter action modul', 1, '1', '2017-10-02 22:26:34'),
(23768, 27, 'cbae1ebe850f6dd8841426c6fedf1785', 1, 1, 20, 'Liste Action Task', 1, '1', '2017-10-02 22:26:34'),
(23769, 27, 'e30471396f9b86ccdcc94943d80b679a', 1, 1, 147, 'Editer Task Action', 0, '1', '2017-10-02 22:26:34'),
(23770, 28, '502460cd9327b46ee7af0a258ebf8c80', 1, 1, 19, 'Ajouter Action Task', 1, '1', '2017-10-02 22:26:34'),
(23771, 29, '13c107211904d4a2e65dd65c60ec7272', 1, 1, 21, 'Supprimer Application', 1, '1', '2017-10-02 22:26:34'),
(23772, 33, '8c8acf9cf3790b16b1fae26823f45eab', 1, 1, 24, 'Importer des modules', 1, '1', '2017-10-02 22:26:34'),
(23773, 55, '2f4518dab90b706e2f4acd737a0425d8', 1, 1, 70, 'Ajouter Module paramétrage', 1, '1', '2017-10-02 22:26:34'),
(23774, 62, '8e0c0212d8337956ac2f4d6eb180d74b', 1, 1, 74, 'Editer Module paramètrage', 1, '1', '2017-10-02 22:26:34'),
(23775, 79, 'fc54953b47b7fcb11cc14c0c2e2125f0', 1, 1, 98, 'Ajouter Autorisation Etat', 1, '1', '2017-10-02 22:26:34'),
(23776, 108, '966ec2dd83e6006c2d0ff1d1a5f12e33', 1, 1, 146, 'Editer Task Action', 1, '1', '2017-10-02 22:26:34'),
(23777, 167, '3473119f6683893a3f1372dbf7d811e1', 1, 1, 231, 'MAJ Module', 1, '1', '2017-10-02 22:26:34'),
(23778, 475, '605450f3d7c84701b986fa31e1e9fa43', 1, 1, 684, 'Gestion Pays', 0, '1', '2017-10-02 22:26:34'),
(23779, 475, '29ba6cc689eca63dbafb109ec58bc4d6', 1, 1, 689, 'Editer Pays', 0, '1', '2017-10-02 22:26:34'),
(23780, 475, '763fe13212b4324590518773cd9a36fa', 1, 1, 690, 'Valider Pays', 0, '1', '2017-10-02 22:26:34'),
(23781, 475, '3c8427c7313d35219b17572efd380b17', 1, 1, 691, 'Désactiver Pays', 0, '1', '2017-10-02 22:26:34'),
(23782, 476, '3cd55a55307615d72aae84c6b5cf99bc', 1, 1, 685, 'Ajouter Pays', 0, '1', '2017-10-02 22:26:34'),
(23783, 477, 'cfe617d7bc6a9c7d8b86c468f21396f2', 1, 1, 686, 'Editer Pays', 0, '1', '2017-10-02 22:26:34'),
(23784, 478, 'b768486aeb655c48cc411c11fa60e150', 1, 1, 687, 'Supprimer Pays', 0, '1', '2017-10-02 22:26:34'),
(23785, 479, '15e4e24f320daa9d563ae62acff9e586', 1, 1, 688, 'Valider Pays', 0, '1', '2017-10-02 22:26:34'),
(23786, 443, '192715027870a4a612fd44d562e2752f', 1, 1, 631, 'Gestion des produits', 0, '1', '2017-10-02 22:26:34'),
(23787, 443, 'ed13b17897a396c0633d7989f2bc644f', 1, 1, 632, 'Modifier produit', 0, '1', '2017-10-02 22:26:34'),
(23788, 443, '96df3c4057988c54a7d468e5664dba10', 1, 1, 633, 'Détail produit', 0, '1', '2017-10-02 22:26:34'),
(23789, 443, 'eb5b51394e164f00ce8c998310e3a8ba', 1, 1, 634, 'Valider produit', 0, '1', '2017-10-02 22:26:34'),
(23790, 443, '6b087b20929483bb07f8862b39e41f07', 1, 1, 635, 'Désactiver produit', 0, '1', '2017-10-02 22:26:34'),
(23791, 443, '3fe9362cc0a931940b8d5dd40338c9c8', 1, 1, 636, 'Achat produit', 0, '1', '2017-10-02 22:26:34'),
(23792, 444, '93e893c307a6fa63e392f78751ec70ce', 1, 1, 637, 'Ajouter produit', 0, '1', '2017-10-02 22:26:34'),
(23793, 445, 'bcf3beada4a98e8145af2d4fbb744f01', 1, 1, 638, 'Modifier produit', 0, '1', '2017-10-02 22:26:34'),
(23794, 446, '796427ec57f7c13d6b737055ae686b34', 1, 1, 639, 'Detail produit', 0, '1', '2017-10-02 22:26:34'),
(23795, 447, '1fb8cd1a179be07586fa7db05013dd37', 1, 1, 640, 'Valider produit', 0, '1', '2017-10-02 22:26:34'),
(23796, 448, '7779e98d2111faedf458f7aeb548294e', 1, 1, 641, 'Supprimer produit', 0, '1', '2017-10-02 22:26:34'),
(23797, 449, '8da585a04e918c256bd26f0c03f1390d', 1, 1, 642, 'Achat produit', 0, '1', '2017-10-02 22:26:34'),
(23798, 449, 'f8c9a7413089566d1db20dcc5ca17e03', 1, 1, 643, 'Modifier achat', 0, '1', '2017-10-02 22:26:34'),
(23799, 449, '682b4328ee832101a44dac86b22d5757', 1, 1, 644, 'Détail achat', 0, '1', '2017-10-02 22:26:34'),
(23800, 449, 'd1ebf1c5482ddf06721b11ec64afb744', 1, 1, 645, 'Valider achat', 0, '1', '2017-10-02 22:26:34'),
(23801, 449, '368a1e91fc63e263eb01d85a34ecd89b', 1, 1, 646, 'Désactiver achat', 0, '1', '2017-10-02 22:26:34'),
(23802, 450, '659be5cd86a12eba7e59c52d60198a36', 1, 1, 647, 'Ajoute achat', 0, '1', '2017-10-02 22:26:34'),
(23803, 451, '8415336a17e8ca26f3eca5741863f3b2', 1, 1, 648, 'Modifier achat', 0, '1', '2017-10-02 22:26:34'),
(23804, 452, '2c3b4875b72f7da6a87b5c0d7e85f51d', 1, 1, 649, 'Supprimer achat', 0, '1', '2017-10-02 22:26:34'),
(23805, 453, 'd4180eb7a4ff86c598f441ffd4543f36', 1, 1, 650, 'Détail achat', 0, '1', '2017-10-02 22:26:34'),
(23806, 454, '4a4c9b096bad58a96d5ea6f93d66e81c', 1, 1, 651, 'Valider achat', 0, '1', '2017-10-02 22:26:34'),
(23807, 519, '1eb847d87adcad78d5e951e6110061e5', 1, 1, 758, 'Gestion Proforma', 0, '1', '2017-10-02 22:26:34'),
(23808, 519, '44ef6849d8d5d17d8e0535187e923d32', 1, 1, 759, 'Editer proforma', 0, '1', '2017-10-02 22:26:34'),
(23809, 519, 'b7ce06be726011362a271678547a803c', 1, 1, 760, 'Valider Proforma', 0, '1', '2017-10-02 22:26:34'),
(23810, 519, 'abd8c50f1d2ef4beeeddb68a72973587', 1, 1, 761, 'Détail Proforma', 0, '1', '2017-10-02 22:26:34'),
(23811, 519, 'e20d83df90355eca2a65f56a2556601f', 1, 1, 762, 'Détail Proforma', 0, '1', '2017-10-02 22:26:34'),
(23812, 520, 'd5a6338765b9eab63104b59f01c06114', 1, 1, 763, 'Ajouter pro-forma', 0, '1', '2017-10-02 22:26:34'),
(23813, 521, '95831bde77bc886d6ab4dd5e734de743', 1, 1, 764, 'Editer proforma', 0, '1', '2017-10-02 22:26:34'),
(23814, 522, 'cbb4e1efa1c05b42d25a3a6bcab038a2', 1, 1, 765, 'Ajouter détail proforma', 0, '1', '2017-10-02 22:26:34'),
(23815, 523, 'e9f745054778257a255452c6609461a0', 1, 1, 766, 'valider Proforma', 0, '1', '2017-10-02 22:26:34'),
(23816, 524, 'defef148c404c7e6ac79e4783e0a7ab7', 1, 1, 767, 'Détail Pro-forma', 0, '1', '2017-10-02 22:26:34'),
(23817, 525, '53008d64edf241c937a06f03eff139aa', 1, 1, 768, 'Editer détail proforma', 0, '1', '2017-10-02 22:26:34'),
(23818, 470, 'd57b16b3aad4ce59f909609246c4fd36', 1, 1, 676, 'Gestion des régions', 0, '1', '2017-10-02 22:26:34'),
(23819, 470, 'd2e007184668dd70b9bae44d46d28ded', 1, 1, 677, 'Modifier région', 0, '1', '2017-10-02 22:26:34'),
(23820, 470, 'e74403c99ac8325b78735c531a20442f', 1, 1, 678, 'Valider région', 0, '1', '2017-10-02 22:26:34'),
(23821, 470, '7397a0fab078728bd5c53be61022d5ce', 1, 1, 679, 'Désactiver région', 0, '1', '2017-10-02 22:26:34'),
(23822, 471, '0237bd41cf70c3529681b4ccb041f1fd', 1, 1, 680, 'Ajouter région', 0, '1', '2017-10-02 22:26:34'),
(23823, 472, '6d290f454da473cb8a557829a410c3f1', 1, 1, 681, 'Modifier région', 0, '1', '2017-10-02 22:26:34'),
(23824, 473, '008cd9ea5767c739675fef4e1261cfe8', 1, 1, 682, 'Valider région', 0, '1', '2017-10-02 22:26:34'),
(23825, 474, 'fc477e6a4c90cd427ae81e555c11d6a9', 1, 1, 683, 'Supprimer région', 0, '1', '2017-10-02 22:26:34'),
(23826, 34, '83b9fa44466da4bcd7f8304185bfeac8', 1, 1, 28, 'Services', 1, '1', '2017-10-02 22:26:34'),
(23827, 34, '99aea4598ccc18d4c12ae091c8967d13', 1, 1, 33, 'Valider Service', 0, '1', '2017-10-02 22:26:34'),
(23828, 34, 'bb66cf787052616ea3dd02b0b5199b26', 1, 1, 34, 'Supprimer Service', 0, '1', '2017-10-02 22:26:34'),
(23829, 34, '47c552dce8b761ae2e2a44387a93432b', 1, 1, 144, 'Modifier Service Validé', 0, '1', '2017-10-02 22:26:34'),
(23830, 35, '55043bc4207521e3010e91d6267f5302', 1, 1, 29, 'Ajouter Service', 1, '1', '2017-10-02 22:26:34'),
(23831, 36, '2fea3d893f6b6e81467ddd2a744e4a76', 1, 1, 30, 'Modifier Service', 1, '1', '2017-10-02 22:26:34'),
(23832, 37, '1a0d5897d31b4d5e29022671c1112f59', 1, 1, 31, 'Valider Service', 1, '1', '2017-10-02 22:26:34'),
(23833, 38, '42083d4e159baf7c2ace2bb977e2b0a0', 1, 1, 32, 'Supprimer Service', 1, '1', '2017-10-02 22:26:34'),
(23834, 460, 'b6b6bfbd070b5b3dd84acedae7b854e9', 1, 1, 660, 'Gestion des types de produits', 0, '1', '2017-10-02 22:26:34'),
(23835, 460, '3c5400b775264499825a039d66aa9c90', 1, 1, 661, 'Modifier type', 0, '1', '2017-10-02 22:26:34'),
(23836, 460, 'dcf55bc300d690af4c81e4d2335e60e5', 1, 1, 662, 'Valider type', 0, '1', '2017-10-02 22:26:34'),
(23837, 460, '230b9554d37da1c71986af94962cb340', 1, 1, 663, 'Désactiver type', 0, '1', '2017-10-02 22:26:34'),
(23838, 461, 'e0d163499b4ba11d6d7a648bc6fc6de6', 1, 1, 664, 'Ajouter un type', 0, '1', '2017-10-02 22:26:34'),
(23839, 462, 'ac5a6d087b3c8db7501fa5137a47773e', 1, 1, 665, 'Modifier type', 0, '1', '2017-10-02 22:26:34'),
(23840, 463, '2e8242a93a62a264ad7cfc953967f575', 1, 1, 666, 'Valider type', 0, '1', '2017-10-02 22:26:34'),
(23841, 464, 'e3725ba15ca483b9278f68553eca5918', 1, 1, 667, 'Supprimer type', 0, '1', '2017-10-02 22:26:34'),
(23842, 480, '312fd18860781a7b1b7e33587fa423d4', 1, 1, 692, 'Gestion Type Echeance', 0, '1', '2017-10-02 22:26:34'),
(23843, 480, '46ad76148075d6b458f43e84ddf00791', 1, 1, 697, 'Editer Type Echéance', 0, '1', '2017-10-02 22:26:34'),
(23844, 480, 'add2bf057924e606653fbf5bbd65ca09', 1, 1, 698, 'Valider Type Echéance', 0, '1', '2017-10-02 22:26:34'),
(23845, 480, '463d9e1e8367736b958f0dd84b4e36d5', 1, 1, 699, 'Désactiver Type Echéance', 0, '1', '2017-10-02 22:26:34'),
(23846, 481, '76170b14c7b6f1f7058d079fe24f739b', 1, 1, 693, 'Ajouter Type Echéance', 0, '1', '2017-10-02 22:26:34'),
(23847, 482, 'decc5ed58c4d91e6967c9c67e0975cf0', 1, 1, 694, 'Editer Type Echéance', 0, '1', '2017-10-02 22:26:34'),
(23848, 483, '89db6f23dd8e96a69c6a97f556c44e14', 1, 1, 695, 'Supprimer Type Echéance', 0, '1', '2017-10-02 22:26:34'),
(23849, 484, '7527021168823e0118d44297c7684d44', 1, 1, 696, 'Valider Type Echéance', 0, '1', '2017-10-02 22:26:34'),
(23850, 465, '55ecbb545a49c70c0b728bb0c7951077', 1, 1, 668, 'Gestion des unités de vente', 0, '1', '2017-10-02 22:26:34'),
(23851, 465, '67acd70eb04242b7091d9dcbb08295d7', 1, 1, 669, 'Modifier unité ', 0, '1', '2017-10-02 22:26:34'),
(23852, 465, '7363022ed5ad047bfe86d3de4b75b1f4', 1, 1, 670, 'Valider unité', 0, '1', '2017-10-02 22:26:34'),
(23853, 465, 'ec77eb95736c27bfc269cbffc8e113f1', 1, 1, 671, 'Désactiver unité', 0, '1', '2017-10-02 22:26:34'),
(23854, 466, '3a5e8dfe211121eda706f8b6d548d111', 1, 1, 672, 'ajouter une unité', 0, '1', '2017-10-02 22:26:34'),
(23855, 467, '9b7a578981de699286376903e96bc3c7', 1, 1, 673, 'Modifier une unité', 0, '1', '2017-10-02 22:26:34'),
(23856, 468, '62355588366c13ddbadc7a7ca1d226ad', 1, 1, 674, 'Valider une unité', 0, '1', '2017-10-02 22:26:34'),
(23857, 469, 'e5f53a3aaa324415d781156396938101', 1, 1, 675, 'Supprimer une unité', 0, '1', '2017-10-02 22:26:34'),
(23858, 14, '56de23d30d6c54297c8d9772cd3c7f57', 1, 1, 1, 'Utilisateurs', 1, '1', '2017-10-02 22:26:34'),
(23859, 14, 'e656756fb7b39a4e6ddcabca75ff2970', 1, 1, 3, 'Editer Utilisateur', 0, '1', '2017-10-02 22:26:34'),
(23860, 14, 'c073a277957ca1b9f318ac3902555708', 1, 1, 6, 'Permissions', 0, '1', '2017-10-02 22:26:34'),
(23861, 14, 'c51499ddf7007787c4434661c658bbd1', 1, 1, 8, 'Désactiver compte', 0, '1', '2017-10-02 22:26:34'),
(23862, 14, '10096b6f54456bcfc85081523ee64cf6', 1, 1, 23, 'Supprimer utilisateur', 0, '1', '2017-10-02 22:26:34'),
(23863, 14, 'a0999cbed820aff775adf27276ee54a4', 1, 1, 25, 'Editer Utilisateur', 0, '1', '2017-10-02 22:26:34'),
(23864, 14, '9aa6877656339ddff2478b20449a924b', 1, 1, 27, 'Activer compte', 0, '1', '2017-10-02 22:26:34'),
(23865, 14, 'f4c79bb797b92dfa826b51a44e3171af', 1, 1, 112, 'Utilisateurs', 1, '1', '2017-10-02 22:26:34'),
(23866, 14, 'd7f7afd70a297e5c239f6cf271138390', 1, 1, 143, 'Utilisateur Archivé', 1, '1', '2017-10-02 22:26:34'),
(23867, 15, 'df91a8e6f8ee2cde64495fc0cc7d6c6f', 1, 1, 2, 'Ajouter Utilisateurs', 1, '1', '2017-10-02 22:26:34'),
(23868, 16, '2bb46b52eab9eecbdbba35605da07234', 1, 1, 4, 'Editer Utilisateurs', 1, '1', '2017-10-02 22:26:34'),
(23869, 17, '3f59a1326df27378304e142ab3bec090', 1, 1, 5, 'Permission', 1, '1', '2017-10-02 22:26:34'),
(23870, 18, 'b919571c88d036f8889742a81a4f41fd', 1, 1, 7, 'Supprimer utilisateur', 1, '1', '2017-10-02 22:26:34'),
(23871, 19, '38f89764a26e39ce029cd3132c12b2a5', 1, 1, 45, 'Compte utilisateur', 1, '1', '2017-10-02 22:26:34'),
(23872, 20, 'f988a608f35a0bc551cb038b1706d207', 1, 1, 26, 'Activer utilisateur', 1, '1', '2017-10-02 22:26:34'),
(23873, 107, 'b7b3a09fdd73a5b0a3e5ed8a2828f548', 1, 1, 145, 'Désactiver l''utilisateur', 1, '1', '2017-10-02 22:26:34'),
(23874, 161, '0d374b7e2fe21a2e2641c092a3c7f2e9', 1, 1, 222, 'Changer le mot de passe', 1, '1', '2017-10-02 22:26:34'),
(23875, 162, '6f642ee30722158f0318653b9113b887', 1, 1, 224, 'History', 1, '1', '2017-10-02 22:26:34'),
(23876, 163, 'cc907fac13631903d129c137d671d718', 1, 1, 225, 'Activities', 1, '1', '2017-10-02 22:26:34'),
(23877, 430, '3d4eaa53061f51b0c4435bd8e4b89c17', 1, 1, 611, 'Gestion Vente', 0, '1', '2017-10-02 22:26:34'),
(23878, 89, '2c3b01c696ff401a2ac9ffedb7a06e4a', 1, 1, 114, 'Gestion Villes', 1, '1', '2017-10-02 22:26:34'),
(23879, 89, 'b9649163b368f863a0e8036f11cd81ae', 1, 1, 119, 'Editer Ville', 0, '1', '2017-10-02 22:26:34'),
(23880, 89, '89dec6dabcb210cdb9dd28bbef90d43e', 1, 1, 121, 'Editer Ville', 0, '1', '2017-10-02 22:26:34'),
(23881, 89, '4a2edbdcbda34c9d3d1e6abe73643b37', 1, 1, 602, 'Valider Ville', 0, '1', '2017-10-02 22:26:34'),
(23882, 89, '0c8ad6595a4516be83ba5a9cdb7ea9a1', 1, 1, 603, 'Désactiver Ville', 0, '1', '2017-10-02 22:26:34'),
(23883, 90, 'e152b9052d3dcfcac593489dbdc0f61c', 1, 1, 115, 'Ajouter ville', 1, '1', '2017-10-02 22:26:34'),
(23884, 91, '3107e0cd0e0df14c4e94aa088e4457d7', 1, 1, 116, 'Editer Ville', 1, '1', '2017-10-02 22:26:34'),
(23885, 92, 'da79d9214ed5819d7f4f1e3070629a3d', 1, 1, 117, 'Supprimer Ville', 1, '1', '2017-10-02 22:26:34'),
(23886, 423, 'fe03a68d17c62ff2c27329573a1b3550', 1, 1, 601, 'Valider Ville', 0, '1', '2017-10-02 22:26:34');

-- --------------------------------------------------------

--
-- Structure de la table `rules_action_temp`
--

CREATE TABLE IF NOT EXISTS `rules_action_temp` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'rule id',
  `idf` varchar(32) CHARACTER SET latin1 DEFAULT NULL COMMENT 'IDF Rul Mgt',
  `service` int(11) DEFAULT NULL COMMENT 'Service ID',
  `userid` int(11) NOT NULL COMMENT 'id user',
  `descrip` varchar(75) CHARACTER SET latin1 NOT NULL COMMENT 'description de rule',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_rule` (`idf`,`userid`),
  KEY `rules_action_user_sys` (`userid`),
  KEY `rule_action_service_id` (`service`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Table store rules for each user for each App and action' AUTO_INCREMENT=8 ;

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
(2, 'Agent de saisie', 1, 1, NULL, '2016-12-05 22:48:26', NULL, NULL),
(3, 'Direction Générale', 1, 1, NULL, '2016-12-05 22:48:26', NULL, NULL),
(4, 'Informatique', 1, 1, NULL, '2016-12-05 22:48:26', NULL, NULL),
(5, 'Finance', 0, 1, NULL, '2016-12-05 22:48:26', NULL, NULL),
(17, 'Direction Radiocommunication', 0, 1, 1, '2016-12-07 15:48:34', NULL, NULL),
(18, 'Test service modeif', 1, 1, 1, '2017-05-06 16:34:05', 1, '2017-05-06 16:35:33');

-- --------------------------------------------------------

--
-- Structure de la table `session`
--

CREATE TABLE IF NOT EXISTS `session` (
  `id_sys` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID SYS',
  `id` varchar(32) CHARACTER SET latin1 NOT NULL COMMENT 'id session MD5',
  `user` varchar(20) CHARACTER SET latin1 NOT NULL COMMENT 'Nom utilisateur',
  `dat` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Time open session',
  `expir` datetime DEFAULT NULL COMMENT 'Date expiration',
  `ip` varchar(15) CHARACTER SET latin1 NOT NULL COMMENT 'IP Client',
  `browser` varchar(100) CHARACTER SET latin1 NOT NULL COMMENT 'Browser Utilisé',
  `userid` int(11) NOT NULL COMMENT 'ID utilisateur',
  PRIMARY KEY (`id_sys`),
  UNIQUE KEY `id` (`id`),
  KEY `session_user_id` (`userid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=90 ;

--
-- Contenu de la table `session`
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
(10, '6d03b54865c6e982dcf25b000c2a5a32', 'admin', '2017-08-25 23:28:48', '2017-08-26 09:53:47', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:54.0) Gecko/20100101 Firefox/54.0', 1),
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
(89, '075251aa6f5582a39cf5d68e1eb430aa', 'admin', '2017-10-02 21:18:57', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0', 1);

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
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`ste_rc`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `ste_info`
--

INSERT INTO `ste_info` (`id`, `ste_name`, `ste_bp`, `ste_adresse`, `ste_ville`, `ste_pays`, `ste_tel`, `ste_fax`, `ste_email`, `ste_if`, `ste_rc`, `ste_website`, `updusr`, `creusr`, `credat`, `upddat`) VALUES
(1, 'Global-Tech', '00', 'Avenue Charles de Gaules, N’Djamena', 'N''Djamena', 'Tchad', '(+235) 66 32 45 13 / (+235) 99 32 45 13', NULL, 'contact@globaltech.td', '9016442Y', 'TC-ABC-B026/014', 'www.globaltech.td', '1', NULL, NULL, '2017-09-30 23:52:46');

-- --------------------------------------------------------

--
-- Structure de la table `stock`
--

CREATE TABLE IF NOT EXISTS `stock` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_achat_produit` (`idproduit`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=51 ;

--
-- Contenu de la table `stock`
--

INSERT INTO `stock` (`id`, `idproduit`, `qte`, `prix_achat`, `prix_vente`, `date_achat`, `date_validite`, `mouvement`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(38, 17, 100, 1000, 200, '2017-09-09', '2017-09-09', 'E', 0, 1, '2017-09-09 11:31:32', NULL, NULL),
(39, 17, 50, 2000, 1000, '2017-09-10', '2017-09-27', 'E', 0, 1, '2017-09-10 17:17:55', NULL, NULL),
(40, 17, 30, 13000, 100000, '2017-09-20', '2017-09-30', 'E', 0, 1, '2017-09-10 20:54:55', NULL, NULL),
(41, 17, 20, 3000, 450000, '2017-09-12', '2017-09-28', 'E', 0, 1, '2017-09-10 20:55:17', NULL, NULL),
(42, 17, 500, 5000, 90800, '2017-09-11', '2017-09-28', 'E', 0, 1, '2017-09-10 20:56:15', NULL, NULL),
(43, 19, 1000, 230, 500, '2017-09-19', '2017-09-28', 'E', 0, 1, '2017-09-10 21:02:39', NULL, NULL),
(44, 19, 300, 400, 670, '2017-09-14', '2017-09-24', 'E', 0, 1, '2017-09-10 21:04:43', NULL, NULL),
(45, 17, -500, NULL, 9000, NULL, NULL, 'S', 0, 1, '2017-09-10 21:36:07', NULL, NULL),
(46, 19, -300, NULL, 5600, NULL, NULL, 'S', 0, 1, '2017-09-10 21:36:11', NULL, NULL),
(47, 17, 5, NULL, NULL, NULL, NULL, 'R', 0, 1, '2017-09-10 21:36:21', NULL, NULL),
(48, 19, 20, NULL, NULL, NULL, NULL, 'R', 0, 1, '2017-09-10 21:36:28', NULL, NULL);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Contenu de la table `sys_log`
--

INSERT INTO `sys_log` (`id`, `message`, `type_log`, `table_use`, `idm`, `user_exec`, `datlog`) VALUES
(1, 'Création utlisateur', 'Insert', 'users_sys', 19, 'admin', '2017-09-13 15:53:48'),
(2, 'Modification utlisateur', 'Update', 'users_sys', 19, 'admin', '2017-09-13 15:57:03'),
(3, 'Modification utlisateur', 'Update', 'users_sys', 18, 'admin', '2017-09-13 16:17:30'),
(4, 'Modification utlisateur', 'Update', 'users_sys', 18, 'admin', '2017-09-13 16:18:15'),
(5, 'Modification utlisateur', 'Update', 'users_sys', 18, 'admin', '2017-09-13 16:28:38');

-- --------------------------------------------------------

--
-- Structure de la table `sys_notifier`
--

CREATE TABLE IF NOT EXISTS `sys_notifier` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id line',
  `app` varchar(25) NOT NULL COMMENT 'app task',
  `table` varchar(25) DEFAULT NULL COMMENT 'table of app',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE` (`app`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Table des notification app' AUTO_INCREMENT=16 ;

--
-- Contenu de la table `sys_notifier`
--

INSERT INTO `sys_notifier` (`id`, `app`, `table`) VALUES
(8, 'user', 'user_sys'),
(9, 'clients', 'clients'),
(10, 'devis', 'devis'),
(11, 'proforma', 'proforma'),
(13, 'contrats', 'contrats'),
(14, 'fournisseurs', 'fournisseurs'),
(15, 'contrats_fournisseurs', 'contrats_frn');

-- --------------------------------------------------------

--
-- Structure de la table `task`
--

CREATE TABLE IF NOT EXISTS `task` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID Sys',
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
  `services` varchar(20) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Les Services',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE` (`app`),
  KEY `task_ibfk_1` (`modul`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Table Task of modules' AUTO_INCREMENT=542 ;

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
(89, 'villes', 28, 'villes', 'Systeme/settings/villes', 1, 'Gestion Villes', 'building', 1, 0, 0, 'list', '[-1-]'),
(90, 'addville', 28, 'addville', 'Systeme/settings/villes', 1, 'Ajouter ville', NULL, 1, 0, 0, 'form', '[-1-]'),
(91, 'editville', 28, 'editville', 'Systeme/settings/villes', 1, 'Editer Ville', NULL, 1, 0, 0, 'form', '[-1-]'),
(92, 'deleteville', 28, 'deleteville', 'Systeme/settings/villes', 1, 'Supprimer Ville', NULL, 1, 0, 0, 'exec', '[-1-]'),
(107, 'archiv_user', 2, 'archiv_user', 'users', 1, 'Désactiver l''utilisateur', 'ban', 1, 0, 0, 'exec', '[-1-]'),
(108, 'edittaskaction', 3, 'edittaskaction', 'modul_mgr', 1, 'Editer Task Action', 'pen', 1, 0, 0, 'form', '[-1-]'),
(161, 'changepass', 2, 'changepass', 'users', 1, 'Changer le mot de passe', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(162, 'history', 2, 'history', 'users', 1, 'History', 'users', 1, 0, 0, 'list', '[-1-]'),
(163, 'activities', 2, 'activities', 'users', 1, 'Activities', 'users', 1, 0, 0, 'list', '[-1-]'),
(167, 'update_module', 3, 'update_module', 'modul_mgr', 1, 'MAJ Module', 'pencil-square-o', 1, 0, 0, 'exec', '[-1-]'),
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
(423, 'validville', 28, 'validville', 'Systeme/settings/villes', 1, 'Valider Ville', 'lock', 1, 0, 0, 'exec', '[-1-]'),
(430, 'vente', 93, 'vente', 'vente/main', 1, 'Gestion Vente', 'money', 1, 0, 0, 'list', '[-1-2-]'),
(432, 'departements', 95, 'departements', 'Systeme/settings/departements', 1, 'Gestion Départements', 'bullhorn', 1, 0, 0, 'list', '[-1-]'),
(433, 'adddepartement', 95, 'adddepartement', 'Systeme/settings/departements', 1, 'Ajouter Département', 'bullhorn', 1, 0, 0, 'form', '[-1-]'),
(434, 'editdepartement', 95, 'editdepartement', 'Systeme/settings/departements', 1, 'Editer Département', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(435, 'deletedepartement', 95, 'deletedepartement', 'Systeme/settings/departements', 1, 'Supprimer Département', 'trash', 1, 0, 0, 'exec', '[-1-]'),
(436, 'validdepartement', 95, 'validdepartement', 'Systeme/settings/departements', 1, 'Valider Département', 'lock', 1, 0, 0, 'exec', '[-1-]'),
(443, 'produits', 97, 'produits', 'produits', 1, 'Gestion des produits', 'cogs', 1, 0, 0, 'list', '[-1-]'),
(444, 'addproduit', 97, 'addproduit', 'produits', 1, 'Ajouter produit', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(445, 'editproduit', 97, 'editproduit', 'produits', 1, 'Modifier produit', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(446, 'detailproduit', 97, 'detailproduit', 'produits', 1, 'Detail produit', 'cogs', 1, 0, 0, 'profil', '[-1-]'),
(447, 'validproduit', 97, 'validproduit', 'produits', 1, 'Valider produit', 'cogs', 1, 0, 0, 'exec', '[-1-]'),
(448, 'deleteproduit', 97, 'deleteproduit', 'produits', 1, 'Supprimer produit', 'cogs', 1, 0, 0, 'exec', '[-1-]'),
(449, 'buyproducts', 97, 'buyproducts', 'produits', 1, 'Achat produit', 'cogs', 1, 0, 0, 'list', '[-1-]'),
(450, 'addbuyproduct', 97, 'addbuyproduct', 'produits', 1, 'Ajouter achat', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(451, 'editbuyproduct', 97, 'editbuyproduct', 'produits', 1, 'Modifier achat', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(452, 'deletebuyproduct', 97, 'deletebuyproduct', 'produits', 1, 'Supprimer achat', 'cogs', 1, 0, 0, 'exec', '[-1-]'),
(453, 'detailbuyproduct', 97, 'detailbuyproduct', 'produits', 1, 'Détail achat', 'cogs', 1, 0, 0, 'profil', '[-1-]'),
(454, 'validbuyproduct', 97, 'validbuyproduct', 'produits', 1, 'Valider achat', 'cogs', 1, 0, 0, 'exec', '[-1-]'),
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
(470, 'regions', 101, 'regions', 'Systeme/settings/regions', 1, 'Gestion des régions', 'cogs', 1, 0, 0, 'list', '[-1-]'),
(471, 'addregion', 101, 'addregion', 'Systeme/settings/regions', 1, 'Ajouter région', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(472, 'editregion', 101, 'editregion', 'Systeme/settings/regions', 1, 'Modifier région', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(473, 'validregion', 101, 'validregion', 'Systeme/settings/regions', 1, 'Valider région', 'cogs', 1, 0, 0, 'exec', '[-1-]'),
(474, 'deleteregion', 101, 'deleteregion', 'Systeme/settings/regions', 1, 'Supprimer région', 'cogs', 1, 0, 0, 'exec', '[-1-]'),
(475, 'pays', 102, 'pays', 'Systeme/settings/pays', 1, 'Gestion Pays', 'flag', 1, 0, 0, 'list', '[-1-]'),
(476, 'addpays', 102, 'addpays', 'Systeme/settings/pays', 1, 'Ajouter Pays', 'flag', 1, 0, 0, 'form', '[-1-]'),
(477, 'editpays', 102, 'editpays', 'Systeme/settings/pays', 1, 'Editer Pays', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(478, 'deletepays', 102, 'deletepays', 'Systeme/settings/pays', 1, 'Supprimer Pays', 'trash', 1, 0, 0, 'exec', '[-1-]'),
(479, 'validpays', 102, 'validpays', 'Systeme/settings/pays', 1, 'Valider Pays', 'lock', 1, 0, 0, 'exec', '[-1-]'),
(480, 'type_echeance', 103, 'type_echeance', 'contrats/settings/type_echeance', 1, 'Gestion Type Echeance', 'info-circle', 1, 0, 0, 'list', '[-1-]'),
(481, 'addtype_echeance', 103, 'addtype_echeance', 'contrats/settings/type_echeance', 1, 'Ajouter Type Echéance', 'info-circle', 1, 0, 0, 'form', '[-1-]'),
(482, 'edittype_echeance', 103, 'edittype_echeance', 'contrats/settings/type_echeance', 1, 'Editer Type Echéance', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(483, 'deletetype_echeance', 103, 'deletetype_echeance', 'contrats/settings/type_echeance', 1, 'Supprimer Type Echéance', 'trash', 1, 0, 0, 'exec', '[-1-]'),
(484, 'validtype_echeance', 103, 'validtype_echeance', 'contrats/settings/type_echeance', 1, 'Valider Type Echéance', 'lock', 1, 0, 0, 'exec', '[-1-]'),
(485, 'contrats', 104, 'contrats', 'vente/submodul/contrats', 1, 'Abonnements', 'cloud', 1, 0, 0, 'list', '[-1-]'),
(486, 'addcontrat', 104, 'addcontrat', 'vente/submodul/contrats', 1, 'Ajouter contrat', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(487, 'editcontrat', 104, 'editcontrat', 'vente/submodul/contrats', 1, 'Modifier contrat', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(488, 'deletecontrat', 104, 'deletecontrat', 'vente/submodul/contrats', 1, 'Supprimer contrat', 'cogs', 1, 0, 0, 'exec', '[-1-]'),
(489, 'validcontrat', 104, 'validcontrat', 'vente/submodul/contrats', 1, 'Valider contrat', 'cogs', 1, 0, 0, 'exec', '[-1-]'),
(490, 'detailcontrat', 104, 'detailcontrat', 'vente/submodul/contrats', 1, 'Détail contrat', 'cogs', 1, 0, 0, 'profil', '[-1-]'),
(491, 'addecheance_contrat', 104, 'addecheance_contrat', 'vente/submodul/contrats', 1, 'Ajouter échéance contrat', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(492, 'editecheance_contrat', 104, 'editecheance_contrat', 'vente/submodul/contrats', 1, 'Modifier échéance contrat', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(493, 'devis', 105, 'devis', 'vente/submodul/devis', 1, 'Gestion Devis', 'paper-plane-o', 1, 0, 0, 'list', '[-1-2-]'),
(494, 'adddevis', 105, 'adddevis', 'vente/submodul/devis', 1, 'Ajouter Devis', 'plus', 1, 0, 0, 'form', '[-1-2-]'),
(495, 'add_detaildevis', 105, 'add_detaildevis', 'vente/submodul/devis', 1, 'Ajouter détail devis', 'plus', 1, 0, 0, 'form', '[-1-2-]'),
(496, 'editdevis', 105, 'editdevis', 'vente/submodul/devis', 1, 'Modifier Devis', 'pen', 1, 0, 0, 'form', '[-1-2-]'),
(497, 'deletedevis', 105, 'deletedevis', 'vente/submodul/devis', 1, 'Supprimer Devis', 'trash red', 1, 0, 0, 'exec', '[-1-2-]'),
(498, 'edit_detaildevis', 105, 'edit_detaildevis', 'vente/submodul/devis', 1, 'Modifier détail Devis', 'pen', 1, 0, 0, 'form', '[-1-2-]'),
(499, 'viewdevis', 105, 'viewdevis', 'vente/submodul/devis', 1, 'Afficher détail devis', 'eye', 1, 0, 0, 'profil', '[-1-2-]'),
(500, 'validdevis', 105, 'validdevis', 'vente/submodul/devis', 1, 'Valider Devis', NULL, 1, 0, 0, 'exec', '[-1-2-]'),
(501, 'contrats_fournisseurs', 106, 'contrats_fournisseurs', 'contrats_fournisseurs/main', 1, 'Gestion Contrats Fournisseurs', 'book', 1, 0, 0, 'list', '[-1-]'),
(502, 'fournisseurs', 107, 'fournisseurs', 'fournisseurs/main', 1, 'Gestion Fournisseurs', 'users', 1, 0, 0, 'list', '[-1-]'),
(503, 'addfournisseur', 107, 'addfournisseur', 'fournisseurs/main', 1, 'Ajouter Fournisseur', 'user', 1, 0, 0, 'form', '[-1-]'),
(504, 'editfournisseur', 107, 'editfournisseur', 'fournisseurs/main', 1, 'Editer Fournisseur', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(505, 'deletefournisseur', 107, 'deletefournisseur', 'fournisseurs/main', 1, 'Supprimer Fournisseur', 'trash', 1, 0, 0, 'exec', '[-1-]'),
(506, 'validfournisseur', 107, 'validfournisseur', 'fournisseurs/main', 1, 'Valider Fournisseur', 'lock', 1, 0, 0, 'exec', '[-1-]'),
(508, 'detailsfournisseur', 107, 'detailsfournisseur', 'fournisseurs/main', 1, 'Détails Fournisseur', 'eye', 1, 0, 0, 'profil', '[-1-]'),
(509, 'addcontrat_frn', 106, 'addcontrat_frn', 'contrats_fournisseurs/main', 1, 'Ajouter Contrat', 'book', 1, 0, 0, 'form', '[-1-]'),
(510, 'editcontrat_frn', 106, 'editcontrat_frn', 'contrats_fournisseurs/main', 1, 'Editer Contrat', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(511, 'deletecontrat_frn', 106, 'deletecontrat_frn', 'contrats_fournisseurs/main', 1, 'Supprimer Contrat', 'trash', 1, 0, 0, 'exec', '[-1-]'),
(512, 'validcontrat_frn', 106, 'validcontrat_frn', 'contrats_fournisseurs/main', 1, 'Valider Contrat', 'lock', 1, 0, 0, 'exec', '[-1-]'),
(519, 'proforma', 109, 'proforma', 'vente/submodul/proforma', 1, 'Gestion Proforma', 'book', 1, 0, 0, 'list', '[-1-2-3-5-4-]'),
(520, 'addproforma', 109, 'addproforma', 'vente/submodul/proforma', 1, 'Ajouter pro-forma', 'plus', 1, 0, 0, 'form', '[-1-2-3-5-4-]'),
(521, 'editproforma', 109, 'editproforma', 'vente/submodul/proforma', 1, 'Editer proforma', 'pen', 1, 0, 0, 'form', '[-1-2-3-5-4-]'),
(522, 'add_detailproforma', 109, 'add_detailproforma', 'vente/submodul/proforma', 1, 'Ajouter détail proforma', 'plus', 1, 0, 0, 'form', '[-1-2-3-5-4-]'),
(523, 'validproforma', 109, 'validproforma', 'vente/submodul/proforma', 1, 'valider Proforma', 'check', 1, 0, 0, 'exec', '[-1-2-3-5-4-]'),
(524, 'viewproforma', 109, 'viewproforma', 'vente/submodul/proforma', 1, 'Détail Pro-forma', 'eye', 1, 0, 0, 'profil', '[-1-2-3-5-4-]'),
(525, 'edit_detailproforma', 109, 'edit_detailproforma', 'vente/submodul/proforma', 1, 'Editer détail proforma', 'pen', 1, 0, 0, 'form', '[-1-2-3-5-4-]'),
(527, 'detailscontrat_frn', 106, 'detailscontrat_frn', 'contrats_fournisseurs/main', 1, 'Détails Contrat', 'eye', 1, 0, 0, 'profil', '[-1-]'),
(528, 'renouvelercontrat', 104, 'renouvelercontrat', 'vente/submodul/contrats', 1, 'Renouveler Contrat', 'exchange', 1, 0, 0, 'form', '[-1-]'),
(529, 'renouveler_contrat', 106, 'renouveler_contrat', 'contrats_fournisseurs/main', 1, 'Renouveler  Contrat', 'exchange', 1, 0, 0, 'form', '[-1-]'),
(530, 'factures', 110, 'factures', 'factures/main', 1, 'Gestion des factures', 'cogs', 1, 0, 0, 'list', '[-1-]'),
(531, 'complements', 110, 'complements', 'factures/main', 1, 'complements', 'cogs', 1, 0, 0, 'list', '[-1-]'),
(532, 'addcomplement', 110, 'addcomplement', 'factures/main', 1, 'Ajouter complément', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(533, 'encaissements', 110, 'encaissements', 'factures/main', 1, 'Encaissement', 'cogs', 1, 0, 0, 'list', '[-1-]'),
(534, 'addencaissement', 110, 'addencaissement', 'factures/main', 1, 'Ajouter encaissement', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(535, 'editcomplement', 110, 'editcomplement', 'factures/main', 1, 'Modifier complément', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(536, 'editencaissement', 110, 'editencaissement', 'factures/main', 1, 'Modifier encaissement', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(537, 'deletecomplement', 110, 'deletecomplement', 'factures/main', 1, 'Supprimer complément', 'cogs', 1, 0, 0, 'exec', '[-1-]'),
(538, 'deleteencaissement', 110, 'deleteencaissement', 'factures/main', 1, 'Supprimer encaissement', 'cogs', 1, 0, 0, 'exec', '[-1-]'),
(539, 'validfacture', 110, 'validfacture', 'factures/main', 1, 'Valider facture', 'cogs', 1, 0, 0, 'exec', '[-1-]'),
(540, 'detailsencaissement', 110, 'detailsencaissement', 'factures/main', 1, 'Détail encaissement', 'eye', 1, 0, 0, 'profil', '[-1-]'),
(541, 'detailsfacture', 110, 'detailsfacture', 'factures/main', 1, 'Détails facture', 'eye', 1, 0, 0, 'profil', '[-1-]');

-- --------------------------------------------------------

--
-- Structure de la table `task_action`
--

CREATE TABLE IF NOT EXISTS `task_action` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID App Action (AI)',
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
  `upddat` datetime DEFAULT NULL COMMENT 'update by',
  PRIMARY KEY (`id`),
  KEY `task_action_task` (`appid`),
  KEY `task_action_descrip` (`descrip`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Table of Task_Action and Permission of Task' AUTO_INCREMENT=810 ;

--
-- Contenu de la table `task_action`
--

INSERT INTO `task_action` (`id`, `appid`, `idf`, `descrip`, `mode_exec`, `app`, `class`, `code`, `type`, `service`, `etat_line`, `notif`, `etat_desc`, `message_class`, `message_etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(1, 14, '56de23d30d6c54297c8d9772cd3c7f57', 'Utilisateurs', NULL, 'user', NULL, '', 1, '-1-2-', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 15, 'df91a8e6f8ee2cde64495fc0cc7d6c6f', 'Ajouter Utilisateurs', NULL, 'adduser', NULL, '', 1, '-1-2-', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 14, 'e656756fb7b39a4e6ddcabca75ff2970', 'Editer Utilisateur', 'this_url', 'user', NULL, '<li><a href="#" class="this_url" redi="user" data="%id%" rel="edituser" >\r\n     <i class="ace-icon fa fa-pencil bigger-100"></i> Editer compte\r\n   </a></li>', 0, '-1-2-', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 16, '2bb46b52eab9eecbdbba35605da07234', 'Editer Utilisateurs', NULL, 'edituser', NULL, '', 1, '-1-2-', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 17, '3f59a1326df27378304e142ab3bec090', 'Permission', NULL, 'rules', NULL, '', 1, '-1-2-', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 14, 'c073a277957ca1b9f318ac3902555708', 'Permissions', 'this_url', 'user', NULL, '<li><a href="#" class="this_url" redi="user" data="%id%" rel="rules"  >\n     <i class="ace-icon fa fa-key bigger-100"></i> Permission compte\n    </a></li>', 0, '-1-2-', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, 18, 'b919571c88d036f8889742a81a4f41fd', 'Supprimer utilisateur', NULL, 'delete_user', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(8, 14, 'c51499ddf7007787c4434661c658bbd1', 'Désactiver compte', 'this_exec', 'user', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="activeuser" ><i class="ace-icon fa fa-lock bigger-100"></i> Désactiver utilisateur</a></li>', 0, '-1-2-', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
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
(23, 14, '10096b6f54456bcfc85081523ee64cf6', 'Supprimer utilisateur', 'this_exec', 'user', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="delete_user" ><i class="ace-icon fa fa-trash red bigger-100"></i> Supprimer utilisateur</a></li>', 0, '[-1-]', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(24, 33, '8c8acf9cf3790b16b1fae26823f45eab', 'Importer des modules', NULL, 'importmodul', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(25, 14, 'a0999cbed820aff775adf27276ee54a4', 'Editer Utilisateur', 'this_url', 'user', NULL, '<li><a href="#" class="this_url" data="%id%" rel="edituser" ><i class="ace-icon fa fa-users bigger-100"></i> Editer compte</a></li>', 0, '[-1-]', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(26, 20, 'f988a608f35a0bc551cb038b1706d207', 'Activer utilisateur', NULL, 'activeuser', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(27, 14, '9aa6877656339ddff2478b20449a924b', 'Activer compte', 'this_exec', 'user', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="activeuser" ><i class="ace-icon fa fa-unlock bigger-100"></i> Activer utilisateur</a></li>', 0, '[-1-]', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(28, 34, '83b9fa44466da4bcd7f8304185bfeac8', 'Services', NULL, 'services', NULL, '', 1, 'null', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
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
(112, 14, 'f4c79bb797b92dfa826b51a44e3171af', 'Utilisateurs', NULL, 'user', NULL, '', 1, '-1-2-', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(114, 89, '2c3b01c696ff401a2ac9ffedb7a06e4a', 'Gestion Villes', NULL, 'villes', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(115, 90, 'e152b9052d3dcfcac593489dbdc0f61c', 'Ajouter ville', NULL, 'addville', NULL, '', 1, '[-1-2-]', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(116, 91, '3107e0cd0e0df14c4e94aa088e4457d7', 'Editer Ville', NULL, 'editville', NULL, '', 1, '[-1-2-]', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(117, 92, 'da79d9214ed5819d7f4f1e3070629a3d', 'Supprimer Ville', NULL, 'deleteville', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(119, 89, 'b9649163b368f863a0e8036f11cd81ae', 'Editer Ville', 'this_url', 'villes', NULL, '<li><a href="#" class="this_url" data="%id%" rel="editville"  ><i class="ace-icon fa fa-pencil bigger-100"></i> Editer Ville</a></li>', 0, '[-1-]', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(121, 89, '89dec6dabcb210cdb9dd28bbef90d43e', 'Editer Ville', 'this_url', 'villes', NULL, '<li><a href="#" class="this_url" data="%id%" rel="editville"  ><i class="ace-icon fa fa-pencil bigger-100"></i> Editer Ville</a></li>', 0, '[-1-]', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(143, 14, 'd7f7afd70a297e5c239f6cf271138390', 'Utilisateur Archivé', NULL, 'user', NULL, '', 1, '[-1-]', 2, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(144, 34, '47c552dce8b761ae2e2a44387a93432b', 'Modifier Service Validé', 'this_url', 'services', NULL, '<li><a href="#" class="this_url" data="%id%" rel="editservice"  ><i class="ace-icon fa fa-pencil bigger-100"></i> Modifier Service Validé</a></li>', 0, '[-1-]', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(145, 107, 'b7b3a09fdd73a5b0a3e5ed8a2828f548', 'Désactiver l''utilisateur', NULL, 'archiv_user', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(146, 108, '966ec2dd83e6006c2d0ff1d1a5f12e33', 'Editer Task Action', NULL, 'edittaskaction', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(147, 27, 'e30471396f9b86ccdcc94943d80b679a', 'Editer Task Action', 'this_url', 'taskaction', NULL, '<li><a href="#" class="this_url" data="%id%" rel="edittaskaction"  ><i class="ace-icon fa fa-pencil bigger-100"></i> Editer Task Action</a></li>', 0, '[-1-]', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(222, 161, '0d374b7e2fe21a2e2641c092a3c7f2e9', 'Changer le mot de passe', NULL, 'changepass', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(224, 162, '6f642ee30722158f0318653b9113b887', 'History', NULL, 'history', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(225, 163, 'cc907fac13631903d129c137d671d718', 'Activities', NULL, 'activities', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(231, 167, '3473119f6683893a3f1372dbf7d811e1', 'MAJ Module', NULL, 'update_module', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(232, 21, 'ac4eb0c94da00a48ad5d995f5e9e9366', 'MAJ Module', 'this_exec', 'update_module', 'pencil-square-o', '<li><a href="#" class="this_exec" data="%id%" rel="update_module"  ><i class="ace-icon fa fa-pencil-square-o bigger-100"></i> MAJ Module</a></li>', 0, '[-1-]', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(497, 333, '6edc543080c65eca3993445c295ff94b', 'Gestion Catégorie Client', NULL, 'categorie_client', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(498, 334, 'a5c1bd0dfd87824ff0f57c6b1e1d2c3f', 'Ajouter Catégorie Client', NULL, 'addcategorie_client', NULL, '', 1, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(499, 335, '8d901f74dfd6ee3a8f44ebd0b83fbfae', 'Editer Catégorie Client', NULL, 'editcategorie_client', NULL, '', 1, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(500, 336, 'e87327563ce6b659780d6b2c9bf8ac77', 'Supprimer Catégorie Client', NULL, 'deletecategorie_client', NULL, '', 1, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(501, 337, 'c955da8d244aac06ee7595d08de7d009', 'Valider Catégorie Client', NULL, 'validcategorie_client', NULL, '', 1, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(506, 333, '142a68a109abd0462ea44fcadffe56de', 'Editer Catégorie Client', 'this_url', 'editcategorie_client', 'cogs', '<li><a href="#" class="this_url" data="%id%" rel="editcategorie_client"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Editer Catégorie Client</a></li>', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(507, 333, '70df89fa2654d8b10d7fc7e75e178b7e', 'Activer Catégorie Client', 'this_exec', 'validcategorie_client', 'lock', '<li><a href="#" class="this_exec" data="%id%" rel="validcategorie_client"  ><i class="ace-icon fa fa-lock bigger-100"></i> Activer Catégorie Client</a></li>', 0, '[-1-]', 0, 1, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(508, 333, '109e82d6db5721f63cd827e9fd224216', 'Désactiver Catégorie Client', 'this_exec', 'validcategorie_client', 'unlock', '<li><a href="#" class="this_exec" data="%id%" rel="validcategorie_client"  ><i class="ace-icon fa fa-unlock bigger-100"></i> Désactiver Catégorie Client</a></li>', 0, '[-1-]', 1, 0, 'Catégorie Validée', 'success', '<span class="label label-sm label-success">Catégorie Validée</span>', NULL, NULL, NULL, NULL),
(553, 394, 'f12fb1c50aedc49c3fa3dfa2bd297bd3', 'Gestion Clients', NULL, 'clients', 'users', ' ', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(554, 394, 'dd3d5980299911ea854af4fa6f2e7309', 'Editer Client', 'this_url', 'editclient', 'cogs', '<li><a href="#" class="this_url" data="%id%" rel="editclient"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Editer Client</a></li>', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(555, 394, '3c5c04a20d49ad010557a64c8cdac1ce', 'Valider Client', 'this_exec', 'validclient', 'lock', '<li><a href="#" class="this_exec" data="%id%" rel="validclient"  ><i class="ace-icon fa fa-lock bigger-100"></i> Valider Client</a></li>', 0, '[-1-]', 0, 1, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(556, 394, '18ace52052f2551099ecaabf049ffaec', 'Désactiver Client', 'this_exec', 'validclient', 'unlock', '<li><a href="#" class="this_exec" data="%id%" rel="validclient"  ><i class="ace-icon fa fa-unlock bigger-100"></i> Désactiver Client</a></li>', 0, '[-1-]', 1, 0, 'Client Validé', 'success', '<span class="label label-sm label-success">Client Validé</span>', NULL, NULL, NULL, NULL),
(557, 394, '493f9e55fc0340763e07514c1900685a', 'Détails Client', 'this_url', 'detailsclient', 'eye', '<li><a href="#" class="this_url" data="%id%" rel="detailsclient"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails Client</a></li>', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(558, 394, '03b4f949b088e41fc9a1f3f23b7906a8', 'Détails  Client', 'this_url', 'detailsclient', 'eye', '<li><a href="#" class="this_url" data="%id%" rel="detailsclient"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails  Client</a></li>', 0, '[-1-]', 1, 0, 'Client Validé', 'success', '<span class="label label-sm label-success">Client Validé</span>', NULL, NULL, NULL, NULL),
(559, 395, '2b9d8bb8f752d1c35fb681c33e38b42b', 'Ajouter Client', NULL, 'addclient', 'user', ' ', 1, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(560, 396, '54aa9121e05f5e698d354022a8eab71d', 'Editer Client', NULL, 'editclient', 'cogs', ' ', 1, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(561, 397, '4eaf650e8c2221d590fac5a6a6952231', 'Supprimer Client', NULL, 'deleteclient', 'trash', ' ', 1, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(562, 398, '534cd4b17fb8a371d3a20565ab8fd96e', 'Valider Client', NULL, 'validclient', 'lock', ' ', 1, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(563, 399, '95bb6aa696ef630a335aa84e1e425e2c', 'Détails Client', NULL, 'detailsclient', 'eye', ' ', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(601, 423, 'fe03a68d17c62ff2c27329573a1b3550', 'Valider Ville', NULL, 'validville', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(602, 89, '4a2edbdcbda34c9d3d1e6abe73643b37', 'Valider Ville', 'this_exec', 'validville', 'lock', '<li><a href="#" class="this_exec" data="%id%" rel="validville"  ><i class="ace-icon fa fa-lock bigger-100"></i> Valider Ville</a></li>', 0, '[-1-]', 0, 1, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(603, 89, '0c8ad6595a4516be83ba5a9cdb7ea9a1', 'Désactiver Ville', 'this_exec', 'validville', 'unlock', '<li><a href="#" class="this_exec" data="%id%" rel="validville"  ><i class="ace-icon fa fa-unlock bigger-100"></i> Désactiver Ville</a></li>', 0, '[-1-]', 1, 0, 'Ville Validée', 'success', '<span class="label label-sm label-success">Ville Validée</span>', NULL, NULL, NULL, NULL),
(611, 430, '3d4eaa53061f51b0c4435bd8e4b89c17', 'Gestion Vente', NULL, 'vente', NULL, '', 0, '[-1-2-]', 0, 0, 'Actif', 'success', '<span class="label label-sm label-success">Actif</span>', NULL, NULL, NULL, NULL),
(613, 432, 'f320732af279d6f2f8ae9c98cd0216de', 'Gestion Départements', NULL, 'departements', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(614, 433, '722b3ba1c7fe735e87aa7415e5502a4c', 'Ajouter Département', NULL, 'adddepartement', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(615, 434, 'daeb31006124e562d284aff67360ee19', 'Editer Département', NULL, 'editdepartement', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(616, 435, 'a775da608fe55c53211d4f1c6e493251', 'Supprimer Département', NULL, 'deletedepartement', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(617, 432, '96516cd0c72d814d5dcb1d86eacd29ab', 'Editer Département', 'this_url', 'editdepartement', 'cogs', '<li><a href="#" class="this_url" data="%id%" rel="editdepartement"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Editer Département</a></li>', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(618, 436, 'bbb96ec910c5000a2006db2f6e8af10a', 'Valider Département', NULL, 'validdepartement', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(619, 432, 'ef27a63534fa9fc3bd4b5086a92db546', 'Valider Département', 'this_exec', 'validdepartement', 'lock', '<li><a href="#" class="this_exec" data="%id%" rel="validdepartement"  ><i class="ace-icon fa fa-lock bigger-100"></i> Valider Département</a></li>', 0, '[-1-]', 0, 1, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(620, 432, '9aed965af4c4b89a5a23c41bf685d403', 'Désactiver Département', 'this_exec', 'validdepartement', 'unlock', '<li><a href="#" class="this_exec" data="%id%" rel="validdepartement"  ><i class="ace-icon fa fa-unlock bigger-100"></i> Désactiver Département</a></li>', 0, '[-1-]', 1, 0, 'Département Validé', 'success', '<span class="label label-sm label-success">Département Validé</span>', NULL, NULL, NULL, NULL),
(631, 443, '192715027870a4a612fd44d562e2752f', 'Gestion des produits', NULL, 'produits', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(632, 443, 'ed13b17897a396c0633d7989f2bc644f', 'Modifier produit', 'this_url', 'editproduit', NULL, '<li><a href="#" class="this_url" data="%id%" rel="editproduit"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Modifier produit</a></li>', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(633, 443, '96df3c4057988c54a7d468e5664dba10', 'Détail produit', 'this_url', 'detailproduit', NULL, '<li><a href="#" class="this_url" data="%id%" rel="detailproduit"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Détail produit</a></li>', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(634, 443, 'eb5b51394e164f00ce8c998310e3a8ba', 'Valider produit', 'this_exec', 'validproduit', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validproduit"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Valider produit</a></li>', 0, '[-1-]', 0, 1, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(635, 443, '6b087b20929483bb07f8862b39e41f07', 'Désactiver produit', 'this_exec', 'validproduit', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validproduit"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Désactiver produit</a></li>', 0, '[-1-]', 1, 0, 'Produit validé', 'success', '<span class="label label-sm label-success">Produit validé</span>', NULL, NULL, NULL, NULL),
(636, 443, '3fe9362cc0a931940b8d5dd40338c9c8', 'Achat produit', 'this_url', 'buyproducts', NULL, '<li><a href="#" class="this_url" data="%id%" rel="buyproducts"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Achat produit</a></li>', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(637, 444, '93e893c307a6fa63e392f78751ec70ce', 'Ajouter produit', NULL, 'addproduit', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(638, 445, 'bcf3beada4a98e8145af2d4fbb744f01', 'Modifier produit', NULL, 'editproduit', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(639, 446, '796427ec57f7c13d6b737055ae686b34', 'Detail produit', NULL, 'detailproduit', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(640, 447, '1fb8cd1a179be07586fa7db05013dd37', 'Valider produit', NULL, 'validproduit', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(641, 448, '7779e98d2111faedf458f7aeb548294e', 'Supprimer produit', NULL, 'deleteproduit', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(642, 449, '8da585a04e918c256bd26f0c03f1390d', 'Achat produit', NULL, 'buyproducts', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(643, 449, 'f8c9a7413089566d1db20dcc5ca17e03', 'Modifier achat', 'this_url', 'editbuyproduct', NULL, '<li><a href="#" class="this_url" data="%id%" rel="editbuyproduct"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Modifier achat</a></li>', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(644, 449, '682b4328ee832101a44dac86b22d5757', 'Détail achat', 'this_url', 'detailbuyproduct', NULL, '<li><a href="#" class="this_url" data="%id%" rel="detailbuyproduct"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Détail achat</a></li>', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(645, 449, 'd1ebf1c5482ddf06721b11ec64afb744', 'Valider achat', 'this_exec', 'validbuyproduct', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validbuyproduct"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Valider achat</a></li>', 0, '[-1-]', 0, 1, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(646, 449, '368a1e91fc63e263eb01d85a34ecd89b', 'Désactiver achat', 'this_exec', 'validbuyproduct', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validbuyproduct"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Désactiver achat</a></li>', 0, '[-1-]', 1, 0, 'Achat validé', 'success', '<span class="label label-sm label-success">Achat validé</span>', NULL, NULL, NULL, NULL),
(647, 450, '659be5cd86a12eba7e59c52d60198a36', 'Ajoute achat', NULL, 'addbuyproduct', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(648, 451, '8415336a17e8ca26f3eca5741863f3b2', 'Modifier achat', NULL, 'editbuproduct', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(649, 452, '2c3b4875b72f7da6a87b5c0d7e85f51d', 'Supprimer achat', NULL, 'deletebuyproduct', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(650, 453, 'd4180eb7a4ff86c598f441ffd4543f36', 'Détail achat', NULL, 'detailbuyproduct', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(651, 454, '4a4c9b096bad58a96d5ea6f93d66e81c', 'Valider achat', NULL, 'validbuyproduct', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(652, 455, 'e69f84a801ee1525f20f34e684688a9b', 'Gestion des catégories de produits', NULL, 'categories_produits', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(653, 455, '90f6eba3e0ed223e73d250278cb445d5', 'Modifier catégorie', 'this_url', 'editecategorie_produit', NULL, '<li><a href="#" class="this_url" data="%id%" rel="editecategorie_produit"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Modifier catégorie</a></li>', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(654, 455, 'c62968a45ae9cfa8b127ac1b5573988a', 'Valider catégorie', 'this_exec', 'validcategorie_produit', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validcategorie_produit"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Valider catégorie</a></li>', 0, '[-1-]', 0, 1, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(655, 455, '6f43a6bcbd293f958aff51953559104e', 'Désactiver catégorie', 'this_exec', 'validcategorie_produit', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validcategorie_produit"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Désactiver catégorie</a></li>', 0, '[-1-]', 1, 0, 'Catégorie Validée', 'success', '<span class="label label-sm label-success">Catégorie Validée</span>', NULL, NULL, NULL, NULL),
(656, 456, 'd26f5940e88a494c0eb65047aab9a17b', 'Ajouter une catégorie', NULL, 'addcategorie_produit', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(657, 457, '27957c6d0f6869d4d90287cd50b6dde9', 'Modifier une catégorie', NULL, 'editecategorie_produit', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(658, 458, '41b48dd567e4f79e35261a47b7bad751', 'Valider une catégorie', NULL, 'validcategorie_produit', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(659, 459, '90dc20c4d1ad7be7fac8ec34d5ac26b3', 'Supprimer une catégorie', NULL, 'deletecategorie_produit', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(660, 460, 'b6b6bfbd070b5b3dd84acedae7b854e9', 'Gestion des types de produits', NULL, 'types_produits', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(661, 460, '3c5400b775264499825a039d66aa9c90', 'Modifier type', 'this_url', 'edittype_produit', NULL, '<li><a href="#" class="this_url" data="%id%" rel="edittype_produit"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Modifier type</a></li>', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(662, 460, 'dcf55bc300d690af4c81e4d2335e60e5', 'Valider type', 'this_exec', 'validtype_produit', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validtype_produit"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Valider type</a></li>', 0, '[-1-]', 0, 1, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(663, 460, '230b9554d37da1c71986af94962cb340', 'Désactiver type', 'this_exec', 'validtype_produit', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validtype_produit"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Désactiver type</a></li>', 0, '[-1-]', 1, 0, 'Type validé', 'success', '<span class="label label-sm label-success">Type validé</span>', NULL, NULL, NULL, NULL),
(664, 461, 'e0d163499b4ba11d6d7a648bc6fc6de6', 'Ajouter un type', NULL, 'addtype_produit', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(665, 462, 'ac5a6d087b3c8db7501fa5137a47773e', 'Modifier type', NULL, 'edittype_produit', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(666, 463, '2e8242a93a62a264ad7cfc953967f575', 'Valider type', NULL, 'validtype_produit', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(667, 464, 'e3725ba15ca483b9278f68553eca5918', 'Supprimer type', NULL, 'deletetype_produit', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(668, 465, '55ecbb545a49c70c0b728bb0c7951077', 'Gestion des unités de vente', NULL, 'unites_vente', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(669, 465, '67acd70eb04242b7091d9dcbb08295d7', 'Modifier unité ', 'this_url', 'editunite_vente', NULL, '<li><a href="#" class="this_url" data="%id%" rel="editunite_vente"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Modifier unité </a></li>', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(670, 465, '7363022ed5ad047bfe86d3de4b75b1f4', 'Valider unité', 'this_exec', 'validunite_vente', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validunite_vente"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Valider unité</a></li>', 0, '[-1-]', 0, 1, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(671, 465, 'ec77eb95736c27bfc269cbffc8e113f1', 'Désactiver unité', 'this_exec', 'validunite_vente', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validunite_vente"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Désactiver unité</a></li>', 0, '[-1-]', 1, 0, 'Unité de vente validé', 'success', '<span class="label label-sm label-success">Unité de vente validé</span>', NULL, NULL, NULL, NULL),
(672, 466, '3a5e8dfe211121eda706f8b6d548d111', 'ajouter une unité', NULL, 'addunite_vente', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(673, 467, '9b7a578981de699286376903e96bc3c7', 'Modifier une unité', NULL, 'editunite_vente', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(674, 468, '62355588366c13ddbadc7a7ca1d226ad', 'Valider une unité', NULL, 'validunite_vente', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(675, 469, 'e5f53a3aaa324415d781156396938101', 'Supprimer une unité', NULL, 'deleteunite_vente', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(676, 470, 'd57b16b3aad4ce59f909609246c4fd36', 'Gestion des régions', NULL, 'regions', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(677, 470, 'd2e007184668dd70b9bae44d46d28ded', 'Modifier région', 'this_url', 'editregion', NULL, '<li><a href="#" class="this_url" data="%id%" rel="editregion"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Modifier région</a></li>', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(678, 470, 'e74403c99ac8325b78735c531a20442f', 'Valider région', 'this_exec', 'validregion', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validregion"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Valider région</a></li>', 0, '[-1-]', 0, 1, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(679, 470, '7397a0fab078728bd5c53be61022d5ce', 'Désactiver région', 'this_exec', 'validregion', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validregion"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Désactiver région</a></li>', 0, '[-1-]', 1, 0, 'Région Validée', 'success', '<span class="label label-sm label-success">Région Validée</span>', NULL, NULL, NULL, NULL),
(680, 471, '0237bd41cf70c3529681b4ccb041f1fd', 'Ajouter région', NULL, 'addregion', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(681, 472, '6d290f454da473cb8a557829a410c3f1', 'Modifier région', NULL, 'editregion', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(682, 473, '008cd9ea5767c739675fef4e1261cfe8', 'Valider région', NULL, 'validregion', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(683, 474, 'fc477e6a4c90cd427ae81e555c11d6a9', 'Supprimer région', NULL, 'deleteregion', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(684, 475, '605450f3d7c84701b986fa31e1e9fa43', 'Gestion Pays', NULL, 'pays', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(685, 476, '3cd55a55307615d72aae84c6b5cf99bc', 'Ajouter Pays', NULL, 'addpays', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(686, 477, 'cfe617d7bc6a9c7d8b86c468f21396f2', 'Editer Pays', NULL, 'editpays', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(687, 478, 'b768486aeb655c48cc411c11fa60e150', 'Supprimer Pays', NULL, 'deletepays', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(688, 479, '15e4e24f320daa9d563ae62acff9e586', 'Valider Pays', NULL, 'validpays', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(689, 475, '29ba6cc689eca63dbafb109ec58bc4d6', 'Editer Pays', 'this_url', 'editpays', 'cogs', '<li><a href="#" class="this_url" data="%id%" rel="editpays"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Editer Pays</a></li>', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(690, 475, '763fe13212b4324590518773cd9a36fa', 'Valider Pays', 'this_exec', 'validpays', 'lock', '<li><a href="#" class="this_exec" data="%id%" rel="validpays"  ><i class="ace-icon fa fa-lock bigger-100"></i> Valider Pays</a></li>', 0, '[-1-]', 0, 1, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(691, 475, '3c8427c7313d35219b17572efd380b17', 'Désactiver Pays', 'this_exec', 'validpays', 'unlock', '<li><a href="#" class="this_exec" data="%id%" rel="validpays"  ><i class="ace-icon fa fa-unlock bigger-100"></i> Désactiver Pays</a></li>', 0, '[-1-]', 1, 0, 'Pays Validé', 'success', '<span class="label label-sm label-success">Pays Validé</span>', NULL, NULL, NULL, NULL),
(692, 480, '312fd18860781a7b1b7e33587fa423d4', 'Gestion Type Echeance', NULL, 'type_echeance', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(693, 481, '76170b14c7b6f1f7058d079fe24f739b', 'Ajouter Type Echéance', NULL, 'addtype_echeance', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(694, 482, 'decc5ed58c4d91e6967c9c67e0975cf0', 'Editer Type Echéance', NULL, 'edittype_echeance', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(695, 483, '89db6f23dd8e96a69c6a97f556c44e14', 'Supprimer Type Echéance', NULL, 'deletetype_echeance', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(696, 484, '7527021168823e0118d44297c7684d44', 'Valider Type Echéance', NULL, 'validtype_echeance', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(697, 480, '46ad76148075d6b458f43e84ddf00791', 'Editer Type Echéance', 'this_url', 'edittype_echeance', 'cogs', '<li><a href="#" class="this_url" data="%id%" rel="edittype_echeance"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Editer Type Echéance</a></li>', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(698, 480, 'add2bf057924e606653fbf5bbd65ca09', 'Valider Type Echéance', 'this_exec', 'validtype_echeance', 'lock', '<li><a href="#" class="this_exec" data="%id%" rel="validtype_echeance"  ><i class="ace-icon fa fa-lock bigger-100"></i> Valider Type Echéance</a></li>', 0, '[-1-]', 0, 1, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(699, 480, '463d9e1e8367736b958f0dd84b4e36d5', 'Désactiver Type Echéance', 'this_exec', 'validtype_echeance', 'unlock', '<li><a href="#" class="this_exec" data="%id%" rel="validtype_echeance"  ><i class="ace-icon fa fa-unlock bigger-100"></i> Désactiver Type Echéance</a></li>', 0, '[-1-]', 1, 0, 'Type Echéance Validé', 'success', '<span class="label label-sm label-success">Type Echéance Validé</span>', NULL, NULL, NULL, NULL),
(700, 485, '899d40c8f22d4f7a6f048366f1829787', 'Gestion des contrats', NULL, 'contrats', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(701, 485, 'a20f4c5b9c9ebaa238757d6f9f9cb6fb', 'Modifier contrat', 'this_url', 'editcontrat', NULL, '<li><a href="#" class="this_url" data="%id%" rel="editcontrat"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Modifier contrat</a></li>', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(702, 485, 'fbb243d2c2fa4200c40993e527b3a339', 'Détail contrat', 'this_url', 'detailcontrat', NULL, '<li><a href="#" class="this_url" data="%id%" rel="detailcontrat"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Détail contrat</a></li>', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(703, 485, 'e970c1414507e5b83ae39e7ddedbf15e', 'Valider contrat', 'this_exec', 'validcontrat', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validcontrat"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Valider contrat</a></li>', 0, '[-1-]', 0, 1, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(704, 485, '6908357258099272b60018c0f6fb1078', 'Désactiver contrat', 'this_exec', 'validcontrat', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validcontrat"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Désactiver contrat</a></li>', 0, '[-1-]', 1, 0, 'Contrat validé', 'success', '<span class="label label-sm label-success">Contrat validé</span>', NULL, NULL, NULL, NULL),
(705, 486, '87f4c3ed4713c3bc9e3fef60a6649055', 'Ajouter contrat', NULL, 'addcontrat', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(706, 487, '9e49a431d9637544cefa2869fd7278b9', 'Modifier contrat', NULL, 'editcontrat', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(707, 488, '1e9395a182a44787e493bc038cd80bbf', 'Supprimer contrat', NULL, 'deletecontrat', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(708, 489, '460d92834715b149c4db28e1643bd932', 'Valider contrat', NULL, 'validcontrat', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(709, 490, 'bbcf2879c2f8f60cfa55fa97c6e79268', 'Détail contrat', NULL, 'detailcontrat', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(710, 491, 'fe058ccb890b25a54866be7f24a40363', 'Ajouter échéance ', NULL, 'addecheance_contrat', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(711, 492, '36a248f56a6a80977e5c90a5c59f39d3', 'Modifier échéance contrat', NULL, 'editecheance_contrat', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(712, 493, '0e79510db7f03b9b6266fc7b4a612153', 'Gestion Devis', NULL, 'devis', NULL, '', 0, '[-1-2-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(713, 493, 'c15b00a1e37657336df8b6aa0eea2db5', 'Modifier Devis', 'this_url', 'editdevis', NULL, '<li><a href="#" class="this_url" data="%id%" rel="editdevis"  ><i class="ace-icon fa fa-pencil-square-o blue bigger-100"></i> Modifier Devis</a></li>', 0, '[-1-2-]', 0, 1, 'Attente validation', 'warning', '<span class="label label-sm label-warning">Attente validation</span>', NULL, NULL, NULL, NULL),
(714, 493, 'd34b07afd92adad84e1c4c2ebd92ba95', 'Voir détails', 'this_url', 'viewdevis', NULL, '<li><a href="#" class="this_url" data="%id%" rel="viewdevis"  ><i class="ace-icon fa fa-eye bigger-100"></i> Voir détails</a></li>', 0, '[-1-2-17-4-]', 0, 1, 'Attente validation', 'warning', '<span class="label label-sm label-warning">Attente validation</span>', NULL, NULL, NULL, NULL),
(715, 493, '5a05eba5be17eba1f35ef8927bfa16d2', 'Valider Devis', 'this_exec', 'validdevis', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validdevis"  ><i class="ace-icon fa fa-check-square-o green bigger-100"></i> Valider Devis</a></li>', 0, '[-1-2-3-]', 0, 1, 'Attente validation', 'warning', '<span class="label label-sm label-warning">Attente validation</span>', NULL, NULL, NULL, NULL),
(716, 493, '28e267a2a0647d4cb37b18abb1e7d051', 'Voir détails', 'this_url', 'viewdevis', NULL, '<li><a href="#" class="this_url" data="%id%" rel="viewdevis"  ><i class="ace-icon fa fa-eye bigger-100"></i> Voir détails</a></li>', 0, '[-1-2-3-5-]', 1, 0, 'Devis validé', 'success', '<span class="label label-sm label-success">Devis validé</span>', NULL, NULL, NULL, NULL),
(717, 494, 'd9eeb330625c1b87e0df00986a47be01', 'Ajouter Devis', NULL, 'adddevis', NULL, '', 0, '[-1-2-]', 0, 0, 'Brouillon', 'success', '<span class="label label-sm label-success">Brouillon</span>', NULL, NULL, NULL, NULL),
(718, 495, 'da93cdb05137e15aed9c4c18bddd746a', 'Ajouter détail devis', NULL, 'add_detaildevis', NULL, '', 0, '[-1-2-]', 0, 0, 'Attente validation', 'success', '<span class="label label-sm label-success">Attente validation</span>', NULL, NULL, NULL, NULL),
(719, 496, 'f9f3c299f9bd0fec014f6bd3f0e06adb', 'Modifier Devis', NULL, 'editdevis', NULL, '', 0, '[-1-2-]', 0, 0, 'Attente validation', 'warning', '<span class="label label-sm label-warning">Attente validation</span>', NULL, NULL, NULL, NULL),
(720, 497, 'e14cce6f1faf7784adb327581c516b90', 'Supprimer Devis', NULL, 'deletedevis', NULL, '', 0, '[-1-3-]', 0, 0, 'Attente validation', 'warning', '<span class="label label-sm label-warning">Attente validation</span>', NULL, NULL, NULL, NULL),
(721, 498, '38f10871792c133ebcc6040e9a11cde8', 'Modifier détail Devis', NULL, 'edit_detaildevis', NULL, '', 0, '[-1-2-3-]', 0, 0, 'Attente validation', 'warning', '<span class="label label-sm label-warning">Attente validation</span>', NULL, NULL, NULL, NULL);
INSERT INTO `task_action` (`id`, `appid`, `idf`, `descrip`, `mode_exec`, `app`, `class`, `code`, `type`, `service`, `etat_line`, `notif`, `etat_desc`, `message_class`, `message_etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(722, 499, '8def42e75fd4aee61c378d9fb303850d', 'Afficher détail devis', NULL, 'viewdevis', NULL, '', 0, '[-1-2-3-4-18-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(723, 500, '7666e87783b0f5a7eec1eea7593f7dfe', 'Valider Devis', NULL, 'validdevis', NULL, '', 0, '[-1-2-3-5-4-]', 0, 0, 'Attente validation', 'success', '<span class="label label-sm label-success">Attente validation</span>', NULL, NULL, NULL, NULL),
(724, 501, 'ec45512f34613446e7a2e367d4b4cfbd', 'Gestion Contrats Fournisseurs', NULL, 'contrats_fournisseurs', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(725, 502, '6beb279abea6434e3b73229aebadc081', 'Gestion Fournisseurs', NULL, 'fournisseurs', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(726, 503, 'd644015625a9603adb2fcc36167aeb73', 'Ajouter Fournisseur', NULL, 'addfournisseur', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(727, 504, '58c6694abfd3228d927a5d5a06d40b94', 'Editer Fournisseur', NULL, 'editfournisseur', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(728, 505, 'd072f81cd779e4b0152953241d713ca3', 'Supprimer Fournisseur', NULL, 'deletefournisseur', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(729, 506, '657351ce5aa227513e3b50dea77db918', 'Valider Fournisseur', NULL, 'validfournisseur', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(730, 502, 'ff95747f3a590b6539803f2a9a394cd5', 'Editer Fournisseur', 'this_url', 'editfournisseur', 'cogs', '<li><a href="#" class="this_url" data="%id%" rel="editfournisseur"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Editer Fournisseur</a></li>', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(731, 502, 'fea982f5074995d4ccd6211a71ab2680', 'Valider Fournisseur', 'this_exec', 'validfournisseur', 'lock', '<li><a href="#" class="this_exec" data="%id%" rel="validfournisseur"  ><i class="ace-icon fa fa-lock bigger-100"></i> Valider Fournisseur</a></li>', 0, '[-1-]', 0, 1, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(732, 502, '1d0411a0dec15fc28f054f1a79d95618', 'Désactiver Fournisseur', 'this_exec', 'validfournisseur', 'unlock', '<li><a href="#" class="this_exec" data="%id%" rel="validfournisseur"  ><i class="ace-icon fa fa-unlock bigger-100"></i> Désactiver Fournisseur</a></li>', 0, '[-1-]', 1, 0, 'Fournisseur Validé', 'success', '<span class="label label-sm label-success">Fournisseur Validé</span>', NULL, NULL, NULL, NULL),
(736, 508, '83b693fe35a1be29edafe4f6170641aa', 'Détails Fournisseur', NULL, 'detailsfournisseur', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(737, 502, 'a52affdd109b9362ce47ff18aad53e2a', 'Détails Fournisseur', 'this_url', 'detailsfournisseur', 'eye', '<li><a href="#" class="this_url" data="%id%" rel="detailsfournisseur"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails Fournisseur</a></li>', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(738, 502, 'c6fe5f222dd563204188e8bf0d69bd9e', 'Détails  Fournisseur', 'this_url', 'detailsfournisseur', 'eye', '<li><a href="#" class="this_url" data="%id%" rel="detailsfournisseur"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails  Fournisseur</a></li>', 0, '[-1-]', 1, 0, 'Fournisseur Validé', 'success', '<span class="label label-sm label-success">Fournisseur Validé</span>', NULL, NULL, NULL, NULL),
(739, 509, 'ded24eb817021c5a666a677b1565bc5e', 'Ajouter Contrat', NULL, 'addcontrat_frn', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(740, 510, 'ed6b8695494bf4ed86d5fb18690b3a59', 'Editer Contrat', NULL, 'editcontrat_frn', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(741, 511, 'b8a40913b5955209994aaa26d0e8c3d4', 'Supprimer Contrat', NULL, 'deletecontrat_frn', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(742, 512, '5efb874e7d73ccd722df806e8275770f', 'Valider Contrat', NULL, 'validcontrat_frn', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(744, 501, 'e3c0d7e92dad7f8794b2415c334ec3ff', 'Editer Contrat', 'this_url', 'editcontrat_frn', 'cogs', '<li><a href="#" class="this_url" data="%id%" rel="editcontrat_frn"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Editer Contrat</a></li>', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(745, 501, '9dfff1c8dcb804837200f38e95381420', 'Valider Contrat', 'this_exec', 'validcontrat_frn', 'lock', '<li><a href="#" class="this_exec" data="%id%" rel="validcontrat_frn"  ><i class="ace-icon fa fa-lock bigger-100"></i> Valider Contrat</a></li>', 0, '[-1-]', 0, 1, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(746, 501, '9fe39b496077065105a57ccd9ed05863', 'Désactiver Contrat', 'this_exec', 'validcontrat_frn', 'unlock', '<li><a href="#" class="this_exec" data="%id%" rel="validcontrat_frn"  ><i class="ace-icon fa fa-unlock bigger-100"></i> Désactiver Contrat</a></li>', 0, '[-1-]', 1, 0, 'Contrat Validé', 'success', '<span class="label label-sm label-success">Contrat Validé</span>', NULL, NULL, NULL, NULL),
(750, 485, '11cabf03a954a5476cc78cf221f04d78', 'Détails Contrat', 'this_url', 'detailcontrat', 'eye', '<li><a href="#" class="this_url" data="%id%" rel="detailcontrat"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails Contrat</a></li>', 0, '[-1-]', 1, 0, 'Contrat Validé', 'success', '<span class="label label-sm label-success">Contrat Validé</span>', NULL, NULL, NULL, NULL),
(758, 519, '1eb847d87adcad78d5e951e6110061e5', 'Gestion Proforma', NULL, 'proforma', NULL, '', 0, '[-1-2-3-5-4-]', 0, 0, 'Attente validation', 'success', '<span class="label label-sm label-warning">Attente validation</span>', NULL, NULL, NULL, NULL),
(759, 519, '44ef6849d8d5d17d8e0535187e923d32', 'Editer proforma', 'this_url', 'editproforma', NULL, '<li><a href="#" class="this_url" data="%id%" rel="editproforma"  ><i class="ace-icon fa fa-pen blue bigger-100"></i> Editer proforma</a></li>', 0, '[-1-2-3-5-]', 0, 1, 'Attente validation', 'warning', '<span class="label label-sm label-warning">Attente validation</span>', NULL, NULL, NULL, NULL),
(760, 519, 'b7ce06be726011362a271678547a803c', 'Valider Proforma', 'this_exec', 'validproforma', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validproforma"  ><i class="ace-icon fa fa-check bigger-100"></i> Valider Proforma</a></li>', 0, '[-1-3-5-]', 0, 1, 'Attente validation', 'warning', '<span class="label label-sm label-warning">Attente validation</span>', NULL, NULL, NULL, NULL),
(761, 519, 'abd8c50f1d2ef4beeeddb68a72973587', 'Détail Proforma', 'this_url', 'viewproforma', NULL, '<li><a href="#" class="this_url" data="%id%" rel="viewproforma"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détail Proforma</a></li>', 0, '[-1-2-3-5-]', 0, 1, 'Attente validation', 'warning', '<span class="label label-sm label-warning">Attente validation</span>', NULL, NULL, NULL, NULL),
(762, 519, 'e20d83df90355eca2a65f56a2556601f', 'Détail Proforma', 'this_url', 'viewproforma', NULL, '<li><a href="#" class="this_url" data="%id%" rel="viewproforma"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détail Proforma</a></li>', 0, '[-1-2-3-5-]', 1, 0, 'Validée', 'success', '<span class="label label-sm label-success">Validée</span>', NULL, NULL, NULL, NULL),
(763, 520, 'd5a6338765b9eab63104b59f01c06114', 'Ajouter pro-forma', NULL, 'addproforma', NULL, '', 0, '[-1-2-3-5-]', 0, 0, 'Brouillon', 'warning', '<span class="label label-sm label-warning">Brouillon</span>', NULL, NULL, NULL, NULL),
(764, 521, '95831bde77bc886d6ab4dd5e734de743', 'Editer proforma', NULL, 'editproforma', NULL, '', 0, '[-1-2-3-5-]', 0, 0, 'Brouillon', 'warning', '<span class="label label-sm label-warning">Brouillon</span>', NULL, NULL, NULL, NULL),
(765, 522, 'cbb4e1efa1c05b42d25a3a6bcab038a2', 'Ajouter détail proforma', NULL, 'adddetailproforma', NULL, '', 0, '[-1-2-3-5-]', 0, 0, 'Attente validation', 'warning', '<span class="label label-sm label-warning">Attente validation</span>', NULL, NULL, NULL, NULL),
(766, 523, 'e9f745054778257a255452c6609461a0', 'valider Proforma', NULL, 'validproforma', NULL, '', 0, '[-1-2-3-5-]', 0, 0, 'Attente validation', 'warning', '<span class="label label-sm label-warning">Attente validation</span>', NULL, NULL, NULL, NULL),
(767, 524, 'defef148c404c7e6ac79e4783e0a7ab7', 'Détail Pro-forma', NULL, 'viewproforma', NULL, '', 0, '[-1-2-3-5-]', 0, 0, 'Attente validation', 'warning', 'Attente validation', NULL, NULL, NULL, NULL),
(768, 525, '53008d64edf241c937a06f03eff139aa', 'Editer détail proforma', NULL, 'edit_detailproforma', NULL, '', 0, '[-1-2-3-5-]', 0, 0, 'Attente validation', 'warning', '<span class="label label-sm label-warning">Attente validation</span>', NULL, NULL, NULL, NULL),
(778, 527, '64a5f976687a8c5f7cd3d672cc5d9c8c', 'Détails Contrat', NULL, 'detailscontrat_frn', '527', '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(779, 501, 'faee342ff51dbe9f835529ae5b9b2a0b', 'Détails  Contrat ', 'this_url', 'detailscontrat_frn', 'eye', '<li><a href="#" class="this_url" data="%id%" rel="detailscontrat_frn"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails  Contrat </a></li>', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(780, 501, '83406b6b206ed08878f2b2e854932ae5', 'Détails   Contrat  ', 'this_url', 'detailscontrat_frn', 'eye', '<li><a href="#" class="this_url" data="%id%" rel="detailscontrat_frn"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails   Contrat  </a></li>', 0, '[-1-]', 1, 0, 'Client Validé', 'success', '<span class="label label-sm label-success">Client Validé</span>', NULL, NULL, NULL, NULL),
(781, 501, '8447888bef30fb983477cc1357ff7e6f', 'Détails    Contrat ', 'this_url', 'detailscontrat_frn', 'eye', '<li><a href="#" class="this_url" data="%id%" rel="detailscontrat_frn"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails    Contrat </a></li>', 0, '[-1-]', 3, 0, 'Contrat Expiré', 'inverse', '<span class="label label-sm label-inverse">Contrat Expiré</span>', NULL, NULL, NULL, NULL),
(782, 501, 'cd25d6f0f7f68e3dc35714df632e58df', ' Détails   Contrat', 'this_url', 'detailscontrat_frn', 'eye', '<li><a href="#" class="this_url" data="%id%" rel="detailscontrat_frn"  ><i class="ace-icon fa fa-eye bigger-100"></i>  Détails   Contrat</a></li>', 0, '[-1-]', 2, 0, 'Attente Renouvelement', 'danger', '<span class="label label-sm label-danger">Attente Renouvelement</span>', NULL, NULL, NULL, NULL),
(783, 528, 'f0567980556249721f24f2fc88ebfed5', 'Renouveler Contrat', NULL, 'renouvelercontrat', '528', '', 0, '[-1-]', 0, 0, 'Attente Renouvelement', 'danger', '<span class="label label-sm label-danger">Attente Renouvelement</span>', NULL, NULL, NULL, NULL),
(784, 485, '74710492392c157c6fe6d7e79ddc95fa', 'Renouveler Contrat', 'this_url', 'renouvelercontrat', 'exchange', '<li><a href="#" class="this_url" data="%id%" rel="renouvelercontrat"  ><i class="ace-icon fa fa-exchange bigger-100"></i> Renouveler Contrat</a></li>', 0, '[-1-]', 2, 1, 'Attente Renouvelement', 'danger', '<span class="label label-sm label-danger">Attente Renouvelement</span>', NULL, NULL, NULL, NULL),
(786, 529, '2cc55c65e79534161108288adb00472b', 'Renouveler  Contrat', NULL, 'renouveler_contrat', '529', '', 0, '[-1-]', 0, 0, 'Attente Renouvelement', 'danger', '<span class="label label-sm label-danger">Attente Renouvelement</span>', NULL, NULL, NULL, NULL),
(787, 501, 'b5455ddf628f5bf0dcb61016556da698', ' Renouveler   Contrat ', 'this_url', 'renouveler_contrat', 'exchange', '<li><a href="#" class="this_url" data="%id%" rel="renouveler_contrat"  ><i class="ace-icon fa fa-exchange bigger-100"></i>  Renouveler   Contrat </a></li>', 0, '[-1-]', 2, 1, 'Attente Renouvelement', 'danger', '<span class="label label-sm label-danger">Attente Renouvelement</span>', NULL, NULL, NULL, NULL),
(788, 485, 'a717e1a94a251fd4316f34aba679c0c1', 'Détails   Contrat ', 'this_url', 'detailcontrat', 'eye', '<li><a href="#" class="this_url" data="%id%" rel="detailcontrat"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails   Contrat </a></li>', 0, '[-1-]', 3, 0, 'Contrat Expiré', 'inverse', '<span class="label label-sm label-inverse">Contrat Expiré</span>', NULL, NULL, NULL, NULL),
(789, 485, 'cd25d6f0f7f68e3dc35714df632e58df', ' Détails   Contrat', 'this_url', 'detailcontrat', 'eye', '<li><a href="#" class="this_url" data="%id%" rel="detailcontrat"  ><i class="ace-icon fa fa-eye bigger-100"></i>  Détails   Contrat</a></li>', 0, '[-1-]', 2, 0, 'Attente Renouvelement', 'danger', '<span class="label label-sm label-danger">Attente Renouvelement</span>', NULL, NULL, NULL, NULL),
(790, 530, 'd76c286028993aff54af01da5dc4b233', 'Gestion des factures', NULL, 'factures', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(791, 530, '98a697ec628778765b25e02ba2929d38', 'Liste complément', 'this_url', 'complements', NULL, '<li><a href="#" class="this_url" data="%id%" rel="complements"  ><i class="ace-icon fa fa-circle bigger-100"></i> Liste complément</a></li>', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(792, 530, '3b6d1456980ea86c0f44c16c464ca790', 'Liste encaissements', 'this_url', 'encaissements', NULL, '<li><a href="#" class="this_url" data="%id%" rel="encaissements"  ><i class="ace-icon fa fa-euro bigger-100"></i> Liste encaissements</a></li>', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(793, 530, '9a51fb5298e39a28af3ad6272fc51177', 'Valider facture', 'this_exec', 'validfacture', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validfacture"  ><i class="ace-icon fa fa-check  bigger-100"></i> Valider facture</a></li>', 0, '[-1-]', 0, 1, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(794, 530, '851f1d4c13f6025f69f5b9315321d350', 'Désactiver facture', 'this_exec', 'validfacture', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validfacture"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Désactiver facture</a></li>', 0, '[-1-]', 1, 0, 'Facture validée', 'success', '<span class="label label-sm label-success">Facture validée</span>', NULL, NULL, NULL, NULL),
(795, 530, '5c79105956d28b5cac52f85784039919', 'Détail facture', 'this_url', 'detailsfacture', NULL, '<li><a href="#" class="this_url" data="%id%" rel="detailsfacture"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détail facture</a></li>', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(796, 531, '55c3c5d2d93143b315513b7401043c8b', 'complements', NULL, 'complements', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(797, 531, 'dfc4772cc03cf0b92a47f54fc6a2326e', 'Modifier complément', 'this_url', 'editcomplement', NULL, '<li><a href="#" class="this_url" data="%id%" rel="editcomplement"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Modifier complément</a></li>', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(798, 532, '03a18bdd5201e433a3c523a2b34d059a', 'Ajouter complément', NULL, 'addcomplement', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(799, 533, '88d9bc979cd1102eb8196e7f5e6042ca', 'Encaissement', NULL, 'encaissements', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(800, 533, 'c690cc68f5257c0c225b8b8e6126ea56', 'Modifier encaissement', 'this_url', 'editencaissement', NULL, '<li><a href="#" class="this_url" data="%id%" rel="editencaissement"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Modifier encaissement</a></li>', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(801, 533, '1dc06f602e8630f273d44aa2751b2127', 'Détails encaissement', 'this_url', 'detailsencaissement', NULL, '<li><a href="#" class="this_url" data="%id%" rel="detailsencaissement"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails encaissement</a></li>', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(802, 534, 'e4866b292dbc3c9c5d9cc37273a5b498', 'Ajouter encaissement', NULL, 'addencaissement', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(803, 535, '8665be10959f39df4f149962eb70041f', 'Modifier complément', NULL, 'editcomplement', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(804, 536, '585d411904bf7d9e83d21b2810ff1d6c', 'Modifier encaissement', NULL, 'editencaissement', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(805, 537, '8c8b058a4d030cdc8b49c9008abb2e92', 'Supprimer complément', NULL, 'deletecomplement', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', 'Attente de validation', NULL, NULL, NULL, NULL),
(806, 538, '6bf7d5180940f03567a5d711e8563ba4', 'Supprimer encaissement', NULL, 'deleteencaissement', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(807, 539, '256abad0ec8e3bc8ed1c0653ff177255', 'Valider facture', NULL, 'validfacture', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(808, 540, 'b5dc5719c1f96df7334f371dcf51a5b6', 'Détail encaissement', NULL, 'detailsencaissement', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(809, 541, '16fbf6fdcbb72f863bcf7e4ef28d8e75', 'Détails facture', NULL, 'detailsfacture', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `token`
--

CREATE TABLE IF NOT EXISTS `token` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(32) CHARACTER SET latin1 NOT NULL,
  `dattime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `users_sys`
--

CREATE TABLE IF NOT EXISTS `users_sys` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
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
  `upddat` datetime DEFAULT NULL COMMENT 'Updated date',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `user_sys_service` (`service`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Table users Systeme' AUTO_INCREMENT=19 ;

--
-- Contenu de la table `users_sys`
--

INSERT INTO `users_sys` (`id`, `nom`, `fnom`, `lnom`, `pass`, `mail`, `service`, `tel`, `etat`, `defapp`, `agence`, `ctc`, `lastactive`, `photo`, `signature`, `form`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(1, 'admin', 'Administrateur', 'Systeme', '5a05679021426829ab75ac9fa6655947', 'rachid@atelsolution.com', 1, '6544545454', 1, 0, 1, 0, '2017-10-02 22:27:20', 1, 2, 9, NULL, '2017-01-13 13:52:42', '1', '2017-06-06 19:22:54'),
(2, 'rachid', 'Rachid', 'Kada', '5a05679021426829ab75ac9fa6655947', 'rachid@bdctchad.com', 2, '0612668698', 1, 3, NULL, 0, '2017-01-19 22:29:53', 4, 5, 6, NULL, '2017-01-19 21:59:10', NULL, '2017-01-19 21:59:10'),
(17, 'tester', 'tester', 'tester', '5a05679021426829ab75ac9fa6655947', 'test@test', 2, '00000000', 1, 3, NULL, 0, '2017-06-14 23:49:21', 376, 377, 378, '1', '2017-06-13 10:02:41', NULL, NULL),
(18, 'test1', 'test1', 'test1', 'd41d8cd98f00b204e9800998ecf8427e', 'test@tests', 2, '000000000', 0, 3, NULL, 0, NULL, 422, 380, 381, '1', '2017-06-13 10:08:34', '1', '2017-09-13 16:28:38');

--
-- Contraintes pour les tables exportées
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
  ADD CONSTRAINT `fk_devis` FOREIGN KEY (`iddevis`) REFERENCES `devis` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_contrat` FOREIGN KEY (`idcontrat`) REFERENCES `contrats` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `fournisseurs`
--
ALTER TABLE `fournisseurs`
  ADD CONSTRAINT `fk_ville` FOREIGN KEY (`id_ville`) REFERENCES `ref_ville` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_devise` FOREIGN KEY (`id_devise`) REFERENCES `ref_devise` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pays` FOREIGN KEY (`id_pays`) REFERENCES `ref_pays` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
