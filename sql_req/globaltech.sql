-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Mer 02 Mai 2018 à 11:49
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
CREATE DEFINER=`root`@`localhost` PROCEDURE `generate_devis_bl`(IN `id_devis` INT)
BEGIN
DECLARE finished INT DEFAULT FALSE;
DECLARE ref_bl VARCHAR(20);
DECLARE cpt_bl INT;
DECLARE ORDER_d INT;
DECLARE id_produit INT; 
DECLARE ref_produit VARCHAR(200);
DECLARE designation VARCHAR(200); 
DECLARE qte INT; 
DECLARE cur1 CURSOR FOR SELECT 
  dd.`order`, dd.id_produit, dd.ref_produit, dd.designation, dd.qte FROM d_devis dd WHERE dd.id_devis=id_devis; 
  
DECLARE CONTINUE HANDLER FOR NOT FOUND SET finished = TRUE; 
SELECT CONCAT((SELECT IFNULL(( MAX(CAST(SUBSTR(reference, 7, LENGTH(SUBSTR(reference,7))-5) AS SIGNED))),0)+1 AS reference 
FROM bl WHERE SUBSTR(reference,LENGTH(reference)-3,4)= (SELECT  YEAR(SYSDATE()))),'/',(SELECT  YEAR(SYSDATE()))) INTO ref_bl ;
INSERT INTO bl (reference, CLIENT, projet, ref_bc, iddevis, date_bl, creusr, credat) 
SELECT CONCAT('GT-BL-',(IF(ref_bl<=9,CONCAT('000',ref_bl),IF(ref_bl>9 AND ref_bl<=99,CONCAT('00',ref_bl),IF(ref_bl>99 AND ref_bl<=999,CONCAT('0',ref_bl),ref_bl)))))
, c.denomination, d.projet, d.ref_bc,
			d.id, (SELECT NOW() FROM DUAL), 1,(SELECT NOW() FROM DUAL)
FROM clients c, devis d
WHERE d.id_client=c.id  AND d.id=id_devis;
SELECT MAX(id) FROM bl WHERE iddevis=id_devis INTO cpt_bl;
 OPEN cur1;
 read_loop: LOOP
 
 FETCH cur1 INTO ORDER_d, id_produit, ref_produit, designation, qte;
   
 IF finished THEN
      LEAVE read_loop;
 END IF;
  
INSERT INTO d_bl VALUES( (SELECT `AUTO_INCREMENT` FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'globaltech' AND   TABLE_NAME   = 'd_bl'),
  ORDER_d, cpt_bl, id_produit, ref_produit, designation, qte, 0 , qte, 1, 
  (SELECT DATE(NOW()) FROM DUAL), NULL, NULL); 
  
END LOOP;
CLOSE cur1;
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generate_devis_fact`(IN `id_devis` INT)
BEGIN
DECLARE finished INT DEFAULT FALSE;
DECLARE ref VARCHAR(20);
DECLARE cpt_fact INT;
DECLARE ORDER_d INT;
DECLARE id_produit INT; 
DECLARE ref_produit VARCHAR(200);
DECLARE designation VARCHAR(200); 
DECLARE qte INT; 
DECLARE prix_unitaire DOUBLE;  
DECLARE type_remise VARCHAR(10);
DECLARE remise_valeur DOUBLE; 
DECLARE tva DOUBLE;  
DECLARE prix_ht DOUBLE; 
DECLARE prix_ttc DOUBLE; 
DECLARE total_ht DOUBLE; 
DECLARE total_ttc DOUBLE; 
DECLARE total_tva DOUBLE;
DECLARE cur1 CURSOR FOR SELECT 
  dd.`order`, dd.id_produit, dd.ref_produit, dd.designation, dd.qte, dd.prix_unitaire, dd.type_remise,
  dd.remise_valeur, dd.tva, dd.prix_ht, dd.prix_ttc, dd.total_ht, dd.total_ttc, dd.total_tva 
  FROM d_devis dd WHERE dd.id_devis=id_devis; 
DECLARE CONTINUE HANDLER FOR NOT FOUND SET finished = TRUE; 
SELECT CONCAT((SELECT IFNULL(( MAX(CAST(SUBSTR(reference, 8, LENGTH(SUBSTR(reference,8))-5) AS SIGNED))),0)+1 AS reference 
FROM factures WHERE SUBSTR(reference,LENGTH(reference)-3,4)= (SELECT  YEAR(SYSDATE()))),'/',(SELECT  YEAR(SYSDATE()))) INTO ref ;
INSERT INTO factures (reference, base_fact, total_ht, total_tva, total_ttc_initial ,total_ttc, total_paye, reste,
			CLIENT, projet, ref_bc, iddevis, date_facture, creusr, credat) 
SELECT CONCAT('GT-FCT-',(IF(ref<=9,CONCAT('000',ref),IF(ref>9 AND ref<=99,CONCAT('00',ref),IF(ref>99 AND ref<=999,CONCAT('0',ref),ref)))))
,'D', d.totalht, d.totaltva, d.totalttc, d.totalttc, 0, d.totalttc, c.denomination, d.projet, d.ref_bc,
			d.id, (SELECT NOW() FROM DUAL), 1,(SELECT NOW() FROM DUAL)
FROM clients c, devis d
WHERE d.id_client=c.id  AND d.id=id_devis;
SELECT MAX(id) FROM factures f  WHERE f.iddevis=id_devis INTO cpt_fact;
 OPEN cur1;
 read_loop: LOOP
 
 FETCH cur1 INTO ORDER_d, id_produit, ref_produit, designation, qte, prix_unitaire, type_remise,
 remise_valeur, tva, prix_ht, prix_ttc, total_ht, total_ttc, total_tva;
   
 IF finished THEN
      LEAVE read_loop;
 END IF;
 
INSERT INTO d_factures VALUES( (SELECT `AUTO_INCREMENT` FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'globaltech' AND   TABLE_NAME   = 'd_factures'),
  ORDER_d, cpt_fact, id_produit, ref_produit, designation, qte, ' ', prix_unitaire, type_remise,
  remise_valeur, tva, prix_ht, prix_ttc, total_ht, total_ttc, total_tva,1, 
  (SELECT DATE(NOW()) FROM DUAL), NULL, NULL); 
END LOOP;
CLOSE cur1;
 
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generate_fact`(IN `tva` INT)
BEGIN
DECLARE finished INT DEFAULT FALSE;
declare ref varchar(20);
DECLare totalht double;
DECLare totaltva double;
DECLare totalttc double;
Declare client varchar(100);
Declare contrat int;
Declare projet varchar(200);
declare ref_bc varchar(200);
declare du date;
declare au date;
DECLARE order_d int;
DECLARE id_produit int;
DECLARE ref_produit varchar(200);
DECLARE designation varchar(200);
DECLARE qte int; 
DEClare qte_designation varchar(20);
DECLARE prix_unitaire double;
DECLARE type_remise varchar(10);
DECLARE remise_valeur double; 
DECLARE tva  DOUBLE;
DECLARE prix_ht DOUBLE;
DECLARE prix_ttc DOUBLE; 
DECLARE total_ht DOUBLE;
DECLARE total_ttc DOUBLE; 
DECLARE total_tva DOUBLE;
DECLARE cpt_fact int;
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
		(SELECT DATE_ADD(MAX(f.au), INTERVAL 1 DAY) FROM factures f WHERE f.idcontrat=ctr.id),
		 date_effet
		  )
	      )
 AS DU
,(CASE 
	WHEN ech.type_echeance='Annuelle'       THEN 
	(IF ((SELECT COUNT(*) FROM factures f WHERE f.idcontrat=ctr.id)>0, 
	     (SELECT DATE_ADD(MAX(f.au), INTERVAL 1 YEAR) FROM factures f WHERE f.idcontrat=ctr.id),
	      DATE_ADD(date_add(date_effet, INTERVAL -1 DAY) , INTERVAL 1 YEAR)
	     )
	)
	WHEN ech.type_echeance='Mensuelle'      THEN 
	(IF ((SELECT COUNT(*) FROM factures f WHERE f.idcontrat=ctr.id)>0, 
	     (SELECT DATE_ADD(MAX(f.au), INTERVAL 1 MONTH) FROM factures f WHERE f.idcontrat=ctr.id),
	      DATE_ADD(date_add(date_effet, INTERVAL -1 DAY), INTERVAL 1 MONTH)
	     )
	)	
	WHEN ech.type_echeance='Trimestrielle'  THEN 
	(IF ((SELECT COUNT(*) FROM factures f WHERE f.idcontrat=ctr.id)>0, 
	     (SELECT DATE_ADD(MAX(f.au), INTERVAL 3 MONTH) FROM factures f WHERE f.idcontrat=ctr.id),
	      DATE_ADD(date_add(date_effet, INTERVAL -1 DAY), INTERVAL 3 MONTH)
	     )
	)
	WHEN ech.type_echeance='Simestrielle'   THEN 
	(IF ((SELECT COUNT(*) FROM factures f WHERE f.idcontrat=ctr.id)>0, 
	     (SELECT DATE_ADD(MAX(f.au), INTERVAL 6 MONTH) FROM factures f WHERE f.idcontrat=ctr.id),
	     DATE_ADD(date_add(date_effet, INTERVAL -1 DAY), INTERVAL 6 MONTH)
	     )
	)
	WHEN ech.type_echeance='Autres'	       THEN 
	(SELECT date_echeance FROM echeances_contrat ec WHERE ec.idcontrat=ctr.id AND (SELECT DATE(NOW()) FROM DUAL)=date_echeance)
	
END ) AS AU,
dd.order, dd.id_produit, dd.ref_produit, dd.designation, 
IF(ech.type_echeance='Annuelle',1,
		IF(ech.type_echeance='Mensuelle',1,
		   IF(ech.type_echeance='Trimestrielle',3,
		      IF(ech.type_echeance='Simestrielle',6,
			 IF(ech.type_echeance='Autres', ' ', dd.qte )
			   )
		         )
		     )
   ) AS qte,
   IF(ech.type_echeance='Annuelle','ANS',
		IF(ech.type_echeance='Mensuelle','MOIS',
		   IF(ech.type_echeance='Trimestrielle','MOIS',
		      IF(ech.type_echeance='Simestrielle','MOIS',
			 IF(ech.type_echeance='Autres', ' ', ' ' )
			   )
		         )
		     )
   ) AS qte_designation,
    dd.prix_unitaire, dd.type_remise, 
dd.remise_valeur, dd.tva, dd.prix_ht, dd.prix_ttc,
IF(ech.type_echeance='Annuelle',(dd.total_ht/dd.qte)* 12,
		IF(ech.type_echeance='Mensuelle',(dd.total_ht/dd.qte)* 1,
		   IF(ech.type_echeance='Trimestrielle',(dd.total_ht/dd.qte)* 3,
		      IF(ech.type_echeance='Simestrielle',(dd.total_ht/dd.qte)* 6,
			 IF(ech.type_echeance='Autres' AND d.tva='O',(SELECT ec.montant - (ec.montant * tva /100) FROM echeances_contrat ec WHERE ec.idcontrat=ctr.id AND (SELECT DATE(NOW()) FROM DUAL)=ec.date_echeance)
			   ,(SELECT ec.montant  FROM echeances_contrat ec WHERE ec.idcontrat=ctr.id AND (SELECT DATE(NOW()) FROM DUAL)=ec.date_echeance)
		           )
		         )
		     )
		  )		
	     )AS total_ht,
IF(ech.type_echeance='Annuelle',(dd.total_ttc/dd.qte)* 12,
		IF(ech.type_echeance='Mensuelle',(dd.total_ttc/dd.qte)* 1,
		   IF(ech.type_echeance='Trimestrielle',(dd.total_ttc/dd.qte)* 3,
		      IF(ech.type_echeance='Simestrielle',(dd.total_ttc/dd.qte)* 6,
			 IF(ech.type_echeance='Autres' , (SELECT ec.montant  FROM echeances_contrat ec WHERE ec.idcontrat=ctr.id AND (SELECT DATE(NOW()) FROM DUAL)=ec.date_echeance)
			   ,NULL
			    )
		         )
		     )
		  )		
	     )AS total_ttc,
IF(ech.type_echeance='Annuelle',(dd.total_tva/dd.qte)* 12,
		IF(ech.type_echeance='Mensuelle',(dd.total_tva/dd.qte)* 1,
		   IF(ech.type_echeance='Trimestrielle',(dd.total_tva/dd.qte)* 3,
		      IF(ech.type_echeance='Simestrielle',(dd.total_tva/dd.qte)* 6,
			 IF(ech.type_echeance='Autres' AND d.tva='O',(SELECT (ec.montant * tva /100) FROM echeances_contrat ec WHERE ec.idcontrat=ctr.id AND (SELECT DATE(NOW()) FROM DUAL)=ec.date_echeance)
			   ,0
			   )
		         )
		     )
		  )		
	     )AS total_tva
	     
 FROM clients c, devis d,d_devis dd, contrats ctr,ref_type_echeance ech 
 WHERE d.id_client=c.id and ctr.iddevis=d.id AND  ctr.idtype_echeance=ech.id
 and ctr.idtype_echeance=ech.id and d.id=dd.id_devis
 and  (SELECT date(NOW())) between ctr.date_effet and ctr.date_fin
 and ctr.etat in (1,2)
 AND (SELECT date(NOW()) FROM DUAL)= (case 
	when ech.type_echeance='Annuelle'       then 
	(IF ((select count(*) from factures f where f.idcontrat=ctr.id)>0, 
	     (select DATE_ADD(max(f.au), INTERVAL 1 year) from factures f where f.idcontrat=ctr.id),
	      IF(ctr.periode_fact='F',DATE_ADD(date_add(date_effet, INTERVAL -1 DAY), INTERVAL 1 year),date_effet)
	     )
	)
	when ech.type_echeance='Mensuelle'      then 
	(IF ((select count(*) from factures f where f.idcontrat=ctr.id)>0, 
	     (select DATE_ADD(max(f.au), INTERVAL 1 MONTH) from factures f where f.idcontrat=ctr.id),
	      IF(ctr.periode_fact='F',DATE_ADD(date_add(date_effet, INTERVAL -1 DAY), INTERVAL 1 MONTH),date_effet)
	     )
	)	
	when ech.type_echeance='Trimestrielle'  then 
	(IF ((select count(*) from factures f where f.idcontrat=ctr.id)>0, 
	     (select DATE_ADD(max(f.au), INTERVAL 3 MONTH) from factures f where f.idcontrat=ctr.id),
	      IF(ctr.periode_fact='F',DATE_ADD(date_add(date_effet, INTERVAL -1 DAY), INTERVAL 3 MONTH),date_effet)
	     )
	)
	when ech.type_echeance='Simestrielle'   then 
	(IF ((select count(*) from factures f where f.idcontrat=ctr.id)>0, 
	     (select DATE_ADD(max(f.au), INTERVAL 6 MONTH) from factures f where f.idcontrat=ctr.id),
	     IF(ctr.periode_fact='F',DATE_ADD(date_add(date_effet, INTERVAL -1 DAY), INTERVAL 6 MONTH),date_effet)
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
 fetch cur1 into totalht, totaltva, totalttc, client, contrat, projet, ref_bc, du, au, order_d, id_produit, ref_produit, 
 designation, qte, qte_designation, prix_unitaire, type_remise, remise_valeur, tva, prix_ht, prix_ttc, total_ht, total_ttc, total_tva;
 
 IF finished THEN
      LEAVE read_loop;
 END IF;
SELECT CONCAT((SELECT IFNULL(( MAX(CAST(SUBSTR(reference, 8, LENGTH(SUBSTR(reference,8))-5) AS SIGNED))),0)+1 AS reference 
FROM factures WHERE SUBSTR(reference,LENGTH(reference)-3,4)= (SELECT  YEAR(SYSDATE()))),'/',(SELECT  YEAR(SYSDATE()))) INTO ref ;
INSERT INTO factures (reference, base_fact, total_ht, total_tva, total_ttc_initial ,total_ttc, total_paye, reste,  CLIENT, projet, ref_bc, idcontrat, date_facture, du, au, creusr, credat) 
values(CONCAT('GT-FCT-',(IF(ref<=9,CONCAT('000',ref),IF(ref>9 AND ref<=99,CONCAT('00',ref),IF(ref>99 AND ref<=999,CONCAT('0',ref),ref)))))
,'C',totalht,totaltva,totalttc,totalttc,0,totalttc,CLIENT, projet, ref_bc, contrat,(SELECT NOW() FROM DUAL), du, au, 1,(SELECT NOW() FROM DUAL));
SELECT MAX(id) FROM factures f into cpt_fact;
INSERT INTO d_factures values( (SELECT `AUTO_INCREMENT` FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'globaltech' AND   TABLE_NAME   = 'd_factures'),
order_d, cpt_fact,id_produit, ref_produit, designation, qte, qte_designation, prix_unitaire, type_remise,
remise_valeur, tva, prix_ht, prix_ttc, total_ht, total_ttc, total_tva,
'admin', (SELECT DATE(NOW()) FROM DUAL), null, null
			     );
END LOOP;
CLOSE cur1;
 
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generate_facturation`(IN `tva` INT)
BEGIN
DECLARE finished INT DEFAULT FALSE;
DECLARE ref VARCHAR(20);
DECLARE totalht DOUBLE;
DECLARE totaltva DOUBLE;
DECLARE totalttc DOUBLE;
DECLARE CLIENT VARCHAR(100);
DECLARE contrat INT;
DECLARE projet VARCHAR(200);
DECLARE ref_bc VARCHAR(200);
DECLARE du DATE;
DECLARE au DATE;
DECLARE order_d INT;
DECLARE id_produit INT;
DECLARE ref_produit VARCHAR(200);
DECLARE designation VARCHAR(200);
DECLARE qte INT; 
DECLARE qte_designation VARCHAR(20);
DECLARE prix_unitaire DOUBLE;
DECLARE type_remise VARCHAR(10);
DECLARE remise_valeur DOUBLE; 
DECLARE tva  DOUBLE;
DECLARE prix_ht DOUBLE;
DECLARE prix_ttc DOUBLE; 
DECLARE total_ht DOUBLE;
DECLARE total_ttc DOUBLE; 
DECLARE total_tva DOUBLE;
DECLARE cpt_fact INT;
DECLARE echeance_id INT;
DECLARE cur1 CURSOR FOR  SELECT 
IF(ech.type_echeance='Annuelle',(d.totalht/dd.qte)* 12,
		IF(ech.type_echeance='Mensuelle',(d.totalht/dd.qte)* 1,
		   IF(ech.type_echeance='Trimestrielle',(d.totalht/dd.qte)* 3,
		      IF(ech.type_echeance='Simestrielle',(d.totalht/dd.qte)* 6,
			IF(ech.type_echeance='Bimensuelle',(d.totalht/dd.qte)* 2,
			  IF(ech.type_echeance='Autres' AND d.tva='O',(ec.montant - (ec.montant * tva /100) )
			   ,ec.montant
		            )
		           )
		         )
		     )
		  )		
	     )AS totalht,
	     
	     IF(ech.type_echeance='Annuelle',(d.totaltva/dd.qte)* 12,
		IF(ech.type_echeance='Mensuelle',(d.totaltva/dd.qte)* 1,
		   IF(ech.type_echeance='Trimestrielle',(d.totaltva/dd.qte)* 3,
		      IF(ech.type_echeance='Simestrielle',(d.totaltva/dd.qte)* 6,
			IF(ech.type_echeance='Bimensuelle',(d.totaltva/dd.qte)* 2,
			  IF(ech.type_echeance='Autres' AND d.tva='O',(ec.montant * tva /100)
			   ,0
		            )
		          )
		         )
		     )
		  )		
	     )AS totaltva,
	      IF(ech.type_echeance='Annuelle',(d.totalttc/dd.qte)* 12,
		IF(ech.type_echeance='Mensuelle',(d.totalttc/dd.qte)* 1,
		   IF(ech.type_echeance='Trimestrielle',(d.totalttc/dd.qte)* 3,
		      IF(ech.type_echeance='Simestrielle',(d.totalttc/dd.qte)* 6,
		        IF(ech.type_echeance='Bimensuelle',(d.totalttc/dd.qte)* 2,
			  IF(ech.type_echeance='Autres' ,ec.montant 
			     ,0
			    )
		           )
		         )
		     )
		  )		
	     )AS totalttc,
c.denomination,ctr.id,d.projet,d.ref_bc
,ec.`date_debut`AS DU,ec.`date_fin`AS AU,
dd.order, dd.id_produit, dd.ref_produit, dd.designation, 
IF(ech.type_echeance='Annuelle',1,
		IF(ech.type_echeance='Mensuelle',1,
		   IF(ech.type_echeance='Trimestrielle',3,
		      IF(ech.type_echeance='Simestrielle',6,
		       IF(ech.type_echeance='Bimensuelle',2,
			 IF(ech.type_echeance='Autres', ' ', dd.qte )
			  )
			 )
		      )
		   )
   ) AS qte,
   IF(ech.type_echeance='Annuelle','Ans',
		IF(ech.type_echeance='Mensuelle','Mois',
		   IF(ech.type_echeance='Trimestrielle','Mois',
		      IF(ech.type_echeance='Simestrielle','Mois',
		        IF(ech.type_echeance='Bimensuelle','Mois',
			 IF(ech.type_echeance='Autres', ' ', ' ' )
			   )
			 )
		      )
		   )
   ) AS qte_designation,
    dd.prix_unitaire, dd.type_remise, 
dd.remise_valeur, dd.tva, dd.prix_ht, dd.prix_ttc, 
IF(ech.type_echeance='Annuelle',(dd.total_ht/dd.qte)* 12,
		IF(ech.type_echeance='Mensuelle',(dd.total_ht/dd.qte)* 1,
		   IF(ech.type_echeance='Trimestrielle',(dd.total_ht/dd.qte)* 3,
		      IF(ech.type_echeance='Simestrielle',(dd.total_ht/dd.qte)* 6,
		        IF(ech.type_echeance='Bimensuelle',(dd.total_ht/dd.qte)* 2,
			  IF(ech.type_echeance='Autres' AND d.tva='O',(ec.montant - (ec.montant * tva /100) )
			   ,ec.montant
		            )
		           )
		         )
		      )
		    )		
    )AS total_ht,
IF(ech.type_echeance='Annuelle',(dd.total_ttc/dd.qte)* 12,
		IF(ech.type_echeance='Mensuelle',(dd.total_ttc/dd.qte)* 1,
		   IF(ech.type_echeance='Trimestrielle',(dd.total_ttc/dd.qte)* 3,
		      IF(ech.type_echeance='Simestrielle',(dd.total_ttc/dd.qte)* 6,
		        IF(ech.type_echeance='Bimensuelle',(dd.total_ttc/dd.qte)* 2,
			  IF(ech.type_echeance='Autres' ,ec.montant ,0)
		           )
		         )
		     )
		  )		
    )AS total_ttc,
IF(ech.type_echeance='Annuelle',(dd.total_tva/dd.qte)* 12,
		IF(ech.type_echeance='Mensuelle',(dd.total_tva/dd.qte)* 1,
		   IF(ech.type_echeance='Trimestrielle',(dd.total_tva/dd.qte)* 3,
		      IF(ech.type_echeance='Simestrielle',(dd.total_tva/dd.qte)* 6,
		       IF(ech.type_echeance='Bimensuelle',(dd.total_tva/dd.qte)* 2,
			 IF(ech.type_echeance='Autres' AND d.tva='O',(ec.montant * tva /100)
			   ,0
		           )
		          )
		         )
		      )
		   )		
   )AS total_tva,
ec.id AS echeance
	     
 FROM clients c, devis d,d_devis dd, contrats ctr,ref_type_echeance ech ,echeances_contrat ec  
 WHERE d.id_client=c.id AND ctr.iddevis=d.id
 AND ctr.idtype_echeance=ech.id AND d.id=dd.id_devis AND ec.idcontrat=ctr.id 
 AND ec.`id` NOT IN (SELECT id_echeance FROM factures f WHERE f.`id_echeance`=ec.`id`)
 AND ctr.etat IN (1,2)
 AND (SELECT DATE(NOW()) FROM DUAL)= ec.`date_echeance`;
 
DECLARE CONTINUE HANDLER FOR NOT FOUND SET finished = TRUE;
 OPEN cur1;
 read_loop: LOOP
 FETCH cur1 INTO totalht, totaltva, totalttc, CLIENT, contrat, projet, ref_bc, du, au, order_d, id_produit, ref_produit, 
 designation, qte, qte_designation, prix_unitaire, type_remise, remise_valeur, tva, prix_ht, prix_ttc, total_ht, total_ttc, total_tva,echeance_id;
 
 IF finished THEN
      LEAVE read_loop;
 END IF;
SELECT CONCAT((SELECT IFNULL(( MAX(CAST(SUBSTR(reference, 8, LENGTH(SUBSTR(reference,8))-5) AS SIGNED))),0)+1 AS reference 
FROM factures WHERE SUBSTR(reference,LENGTH(reference)-3,4)= (SELECT  YEAR(SYSDATE()))),'/',(SELECT  YEAR(SYSDATE()))) INTO ref ;
INSERT INTO factures (reference, base_fact, total_ht, total_tva, total_ttc_initial ,total_ttc, total_paye, reste,  CLIENT, projet, ref_bc, idcontrat, id_echeance, date_facture, du, au, creusr, credat) 
VALUES(CONCAT('GT-FCT-',(IF(ref<=9,CONCAT('000',ref),IF(ref>9 AND ref<=99,CONCAT('00',ref),IF(ref>99 AND ref<=999,CONCAT('0',ref),ref)))))
,'C',totalht,totaltva,totalttc,totalttc,0,totalttc,CLIENT, projet, ref_bc, contrat, echeance_id,(SELECT NOW() FROM DUAL), du, au, 1,(SELECT NOW() FROM DUAL));
SELECT MAX(id) FROM factures f INTO cpt_fact;
INSERT INTO d_factures VALUES( (SELECT `AUTO_INCREMENT` FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'globaltech' AND   TABLE_NAME   = 'd_factures'),
order_d, cpt_fact,id_produit, ref_produit, designation, qte, qte_designation, prix_unitaire, type_remise,
remise_valeur, tva, prix_ht, prix_ttc, total_ht, total_ttc, total_tva,
1, (SELECT DATE(NOW()) FROM DUAL), NULL, NULL
			     );
UPDATE echeances_contrat e SET e.etat= 1 WHERE e.id=echeance_id;
END LOOP;
CLOSE cur1;
 
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generate_facture_fati`(IN `iddev` INT)
BEGIN
DECLARE finished INT DEFAULT FALSE;
DECLARE ref VARCHAR(20);
DECLARE totalht DOUBLE;
DECLARE totaltva DOUBLE;
DECLARE totalttc DOUBLE;
DECLARE CLIENT VARCHAR(100);
DECLARE contrat INT;
DECLARE projet VARCHAR(200);
DECLARE ref_bc VARCHAR(200);
DECLARE du DATE;
DECLARE au DATE;
DECLARE order_d INT;
DECLARE id_produit INT;
DECLARE ref_produit VARCHAR(200);
DECLARE designation VARCHAR(200);
DECLARE qte INT; 
DECLARE qte_designation VARCHAR(20);
DECLARE prix_unitaire DOUBLE;
DECLARE type_remise VARCHAR(10);
DECLARE remise_valeur DOUBLE; 
DECLARE tva  DOUBLE;
DECLARE prix_ht DOUBLE;
DECLARE prix_ttc DOUBLE; 
DECLARE total_ht DOUBLE;
DECLARE total_ttc DOUBLE; 
DECLARE total_tva DOUBLE;
DECLARE cpt_fact INT;
DECLARE cur1 CURSOR FOR  SELECT 
IF(ech.type_echeance='Annuelle',(d.totalht/dd.qte)* 12,
		IF(ech.type_echeance='Mensuelle',(d.totalht/dd.qte)* 1,
		   IF(ech.type_echeance='Trimestrielle',(d.totalht/dd.qte)* 3,
		      IF(ech.type_echeance='Simestrielle',(d.totalht/dd.qte)* 6,
			 IF(ech.type_echeance='Autres' AND d.tva='O',(SELECT ec.montant - (ec.montant * 20 /100) FROM echeances_contrat ec WHERE ec.idcontrat=ctr.id AND (SELECT DATE(NOW()) FROM DUAL)=ec.date_echeance)
			   ,(SELECT ec.montant  FROM echeances_contrat ec WHERE ec.idcontrat=ctr.id AND (SELECT DATE(NOW()) FROM DUAL)=ec.date_echeance)
		           )
		         )
		     )
		  )		
	     )AS totalht,
	     
	     IF(ech.type_echeance='Annuelle',(d.totaltva/dd.qte)* 12,
		IF(ech.type_echeance='Mensuelle',(d.totaltva/dd.qte)* 1,
		   IF(ech.type_echeance='Trimestrielle',(d.totaltva/dd.qte)* 3,
		      IF(ech.type_echeance='Simestrielle',(d.totaltva/dd.qte)* 6,
			 IF(ech.type_echeance='Autres' AND d.tva='O',(SELECT (ec.montant * 20 /100) FROM echeances_contrat ec WHERE ec.idcontrat=ctr.id AND (SELECT DATE(NOW()) FROM DUAL)=ec.date_echeance)
			   ,0
		           )
		         )
		     )
		  )		
	     )AS totaltva,
	      IF(ech.type_echeance='Annuelle',(d.totalttc/dd.qte)* 12,
		IF(ech.type_echeance='Mensuelle',(d.totalttc/dd.qte)* 1,
		   IF(ech.type_echeance='Trimestrielle',(d.totalttc/dd.qte)* 3,
		      IF(ech.type_echeance='Simestrielle',(d.totalttc/dd.qte)* 6,
			 IF(ech.type_echeance='Autres' , (SELECT ec.montant  FROM echeances_contrat ec WHERE ec.idcontrat=ctr.id AND (SELECT DATE(NOW()) FROM DUAL)=ec.date_echeance)
			   ,NULL
			   )
		         )
		     )
		  )		
	     )AS totalttc,
	     c.denomination,ctr.id
	     ,d.projet,d.ref_bc
	     ,(IF ((SELECT COUNT(*) FROM factures f WHERE f.idcontrat=ctr.id)>0, 
		(SELECT DATE_ADD(MAX(f.au), INTERVAL 1 DAY) FROM factures f WHERE f.idcontrat=ctr.id),
		 date_effet
		  )
	      )
 AS DU
,(CASE 
	WHEN ech.type_echeance='Annuelle'       THEN 
	(IF ((SELECT COUNT(*) FROM factures f WHERE f.idcontrat=ctr.id)>0, 
	     (SELECT DATE_ADD(MAX(f.au), INTERVAL 1 YEAR) FROM factures f WHERE f.idcontrat=ctr.id),
	      DATE_ADD(DATE_ADD(date_effet, INTERVAL -1 DAY) , INTERVAL 1 YEAR)
	     )
	)
	WHEN ech.type_echeance='Mensuelle'      THEN 
	(IF ((SELECT COUNT(*) FROM factures f WHERE f.idcontrat=ctr.id)>0, 
	     (SELECT DATE_ADD(MAX(f.au), INTERVAL 1 MONTH) FROM factures f WHERE f.idcontrat=ctr.id),
	      DATE_ADD(DATE_ADD(date_effet, INTERVAL -1 DAY), INTERVAL 1 MONTH)
	     )
	)	
	WHEN ech.type_echeance='Trimestrielle'  THEN 
	(IF ((SELECT COUNT(*) FROM factures f WHERE f.idcontrat=ctr.id)>0, 
	     (SELECT DATE_ADD(MAX(f.au), INTERVAL 3 MONTH) FROM factures f WHERE f.idcontrat=ctr.id),
	      DATE_ADD(DATE_ADD(date_effet, INTERVAL -1 DAY), INTERVAL 3 MONTH)
	     )
	)
	WHEN ech.type_echeance='Simestrielle'   THEN 
	(IF ((SELECT COUNT(*) FROM factures f WHERE f.idcontrat=ctr.id)>0, 
	     (SELECT DATE_ADD(MAX(f.au), INTERVAL 6 MONTH) FROM factures f WHERE f.idcontrat=ctr.id),
	     DATE_ADD(DATE_ADD(date_effet, INTERVAL -1 DAY), INTERVAL 6 MONTH)
	     )
	)
	WHEN ech.type_echeance='Autres'	       THEN 
	(SELECT date_echeance FROM echeances_contrat ec WHERE ec.idcontrat=ctr.id AND (SELECT DATE(NOW()) FROM DUAL)=date_echeance)
	
END ) AS AU,
dd.order, dd.id_produit, dd.ref_produit, dd.designation, 
IF(ech.type_echeance='Annuelle',1,
		IF(ech.type_echeance='Mensuelle',1,
		   IF(ech.type_echeance='Trimestrielle',3,
		      IF(ech.type_echeance='Simestrielle',6,
			 IF(ech.type_echeance='Autres', ' ', dd.qte )
			   )
		         )
		     )
   ) AS qte,
   IF(ech.type_echeance='Annuelle','ANS',
		IF(ech.type_echeance='Mensuelle','MOIS',
		   IF(ech.type_echeance='Trimestrielle','MOIS',
		      IF(ech.type_echeance='Simestrielle','MOIS',
			 IF(ech.type_echeance='Autres', ' ', ' ' )
			   )
		         )
		     )
   ) AS qte_designation,
    dd.prix_unitaire, dd.type_remise, 
dd.remise_valeur, dd.tva, dd.prix_ht, dd.prix_ttc, dd.total_ht, dd.total_ttc, dd.total_tva
 FROM clients c, devis d,d_devis dd, contrats ctr,ref_type_echeance ech 
 WHERE d.id_client=c.id AND ctr.iddevis=d.id AND  ctr.idtype_echeance=ech.id
 AND ctr.idtype_echeance=ech.id AND d.id=dd.id_devis
 and d.id=iddev;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET finished = TRUE;
 OPEN cur1;
 read_loop: LOOP
 FETCH cur1 INTO totalht, totaltva, totalttc, CLIENT, contrat, projet, ref_bc, du, au, order_d, id_produit, ref_produit, 
 designation, qte, qte_designation, prix_unitaire, type_remise, remise_valeur, tva, prix_ht, prix_ttc, total_ht, total_ttc, total_tva;
 
 IF finished THEN
      LEAVE read_loop;
 END IF;
SELECT CONCAT((SELECT IFNULL(( MAX(CAST(SUBSTR(reference, 8, LENGTH(SUBSTR(reference,8))-5) AS SIGNED))),0)+1 AS reference 
FROM factures WHERE SUBSTR(reference,LENGTH(reference)-3,4)= (SELECT  YEAR(SYSDATE()))),'/',(SELECT  YEAR(SYSDATE()))) INTO ref ;
INSERT INTO factures (reference, base_fact, total_ht, total_tva, total_ttc_initial ,total_ttc, total_paye, reste,  CLIENT, projet, ref_bc, idcontrat, date_facture, du, au, creusr, credat) 
VALUES(CONCAT('GT-FCT-',(IF(ref<=9,CONCAT('000',ref),IF(ref>9 AND ref<=99,CONCAT('00',ref),IF(ref>99 AND ref<=999,CONCAT('0',ref),ref)))))
,'C',totalht,totaltva,totalttc,totalttc,0,totalttc,CLIENT, projet, ref_bc, contrat,(SELECT NOW() FROM DUAL), du, au, 1,(SELECT NOW() FROM DUAL));
SELECT MAX(id) FROM factures f INTO cpt_fact;
INSERT INTO d_factures VALUES( (SELECT `AUTO_INCREMENT` FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'globaltech' AND   TABLE_NAME   = 'd_factures'),
order_d, cpt_fact,id_produit, ref_produit, designation, qte, qte_designation, prix_unitaire, type_remise,
remise_valeur, tva, prix_ht, prix_ttc, total_ht, total_ttc, total_tva,
'admin', (SELECT DATE(NOW()) FROM DUAL), NULL, NULL
			     );
END LOOP;
CLOSE cur1;
 
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `manage_devis`(IN `etat_expir` INT, `etat_archive` INT)
BEGIN
 /*Expire Devis*/    
UPDATE devis d SET etat = etat_expir WHERE DATE(DATE_ADD(d.date_devis, INTERVAL + d.vie DAY)) <= CURDATE();
 /*Archive Devis*/
UPDATE devis d SET etat = etat_archive WHERE DATE(DATE_ADD(d.date_devis, INTERVAL + d.vie + 60 DAY)) <= CURDATE();
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `manuel_generate_facturation`(IN `tva` INT,in echeance int)
BEGIN
DECLARE finished INT DEFAULT FALSE;
DECLARE ref VARCHAR(20);
DECLARE totalht DOUBLE;
DECLARE totaltva DOUBLE;
DECLARE totalttc DOUBLE;
DECLARE CLIENT VARCHAR(100);
DECLARE contrat INT;
DECLARE projet VARCHAR(200);
DECLARE ref_bc VARCHAR(200);
DECLARE du DATE;
DECLARE au DATE;
DECLARE order_d INT;
DECLARE id_produit INT;
DECLARE ref_produit VARCHAR(200);
DECLARE designation VARCHAR(200);
DECLARE qte INT; 
DECLARE qte_designation VARCHAR(20);
DECLARE prix_unitaire DOUBLE;
DECLARE type_remise VARCHAR(10);
DECLARE remise_valeur DOUBLE; 
DECLARE tva  DOUBLE;
DECLARE prix_ht DOUBLE;
DECLARE prix_ttc DOUBLE; 
DECLARE total_ht DOUBLE;
DECLARE total_ttc DOUBLE; 
DECLARE total_tva DOUBLE;
DECLARE cpt_fact INT;
DECLARE echeance_id INT;
DECLARE cur1 CURSOR FOR  SELECT 
IF(ech.type_echeance='Annuelle',(d.totalht/dd.qte)* 12,
		IF(ech.type_echeance='Mensuelle',(d.totalht/dd.qte)* 1,
		   IF(ech.type_echeance='Trimestrielle',(d.totalht/dd.qte)* 3,
		      IF(ech.type_echeance='Simestrielle',(d.totalht/dd.qte)* 6,
			IF(ech.type_echeance='Bimensuelle',(d.totalht/dd.qte)* 2,
			  IF(ech.type_echeance='Autres' AND d.tva='O',(ec.montant - (ec.montant * tva /100) )
			   ,ec.montant
		            )
		           )
		         )
		     )
		  )		
	     )AS totalht,
	     
	     IF(ech.type_echeance='Annuelle',(d.totaltva/dd.qte)* 12,
		IF(ech.type_echeance='Mensuelle',(d.totaltva/dd.qte)* 1,
		   IF(ech.type_echeance='Trimestrielle',(d.totaltva/dd.qte)* 3,
		      IF(ech.type_echeance='Simestrielle',(d.totaltva/dd.qte)* 6,
			IF(ech.type_echeance='Bimensuelle',(d.totaltva/dd.qte)* 2,
			  IF(ech.type_echeance='Autres' AND d.tva='O',(ec.montant * tva /100)
			   ,0
		            )
		          )
		         )
		     )
		  )		
	     )AS totaltva,
	      IF(ech.type_echeance='Annuelle',(d.totalttc/dd.qte)* 12,
		IF(ech.type_echeance='Mensuelle',(d.totalttc/dd.qte)* 1,
		   IF(ech.type_echeance='Trimestrielle',(d.totalttc/dd.qte)* 3,
		      IF(ech.type_echeance='Simestrielle',(d.totalttc/dd.qte)* 6,
		        IF(ech.type_echeance='Bimensuelle',(d.totalttc/dd.qte)* 2,
			  IF(ech.type_echeance='Autres' ,ec.montant 
			     ,0
			    )
		           )
		         )
		     )
		  )		
	     )AS totalttc,
c.denomination,ctr.id,d.projet,d.ref_bc
,ec.`date_debut`AS DU,ec.`date_fin`AS AU,
dd.order, dd.id_produit, dd.ref_produit, dd.designation, 
IF(ech.type_echeance='Annuelle',1,
		IF(ech.type_echeance='Mensuelle',1,
		   IF(ech.type_echeance='Trimestrielle',3,
		      IF(ech.type_echeance='Simestrielle',6,
		       IF(ech.type_echeance='Bimensuelle',2,
			 IF(ech.type_echeance='Autres', ' ', dd.qte )
			  )
			 )
		      )
		   )
   ) AS qte,
   IF(ech.type_echeance='Annuelle','Ans',
		IF(ech.type_echeance='Mensuelle','Mois',
		   IF(ech.type_echeance='Trimestrielle','Mois',
		      IF(ech.type_echeance='Simestrielle','Mois',
		        IF(ech.type_echeance='Bimensuelle','Mois',
			 IF(ech.type_echeance='Autres', ' ', ' ' )
			   )
			 )
		      )
		   )
   ) AS qte_designation,
    dd.prix_unitaire, dd.type_remise, 
dd.remise_valeur, dd.tva, dd.prix_ht, dd.prix_ttc, 
IF(ech.type_echeance='Annuelle',(dd.total_ht/dd.qte)* 12,
		IF(ech.type_echeance='Mensuelle',(dd.total_ht/dd.qte)* 1,
		   IF(ech.type_echeance='Trimestrielle',(dd.total_ht/dd.qte)* 3,
		      IF(ech.type_echeance='Simestrielle',(dd.total_ht/dd.qte)* 6,
		        IF(ech.type_echeance='Bimensuelle',(dd.total_ht/dd.qte)* 2,
			  IF(ech.type_echeance='Autres' AND d.tva='O',(ec.montant - (ec.montant * tva /100) )
			   ,ec.montant
		            )
		           )
		         )
		      )
		    )		
    )AS total_ht,
IF(ech.type_echeance='Annuelle',(dd.total_ttc/dd.qte)* 12,
		IF(ech.type_echeance='Mensuelle',(dd.total_ttc/dd.qte)* 1,
		   IF(ech.type_echeance='Trimestrielle',(dd.total_ttc/dd.qte)* 3,
		      IF(ech.type_echeance='Simestrielle',(dd.total_ttc/dd.qte)* 6,
		        IF(ech.type_echeance='Bimensuelle',(dd.total_ttc/dd.qte)* 2,
			  IF(ech.type_echeance='Autres' ,ec.montant ,0)
		           )
		         )
		     )
		  )		
    )AS total_ttc,
IF(ech.type_echeance='Annuelle',(dd.total_tva/dd.qte)* 12,
		IF(ech.type_echeance='Mensuelle',(dd.total_tva/dd.qte)* 1,
		   IF(ech.type_echeance='Trimestrielle',(dd.total_tva/dd.qte)* 3,
		      IF(ech.type_echeance='Simestrielle',(dd.total_tva/dd.qte)* 6,
		       IF(ech.type_echeance='Bimensuelle',(dd.total_tva/dd.qte)* 2,
			 IF(ech.type_echeance='Autres' AND d.tva='O',(ec.montant * tva /100)
			   ,0
		           )
		          )
		         )
		      )
		   )		
   )AS total_tva,
ec.id AS echeance
	     
 FROM clients c, devis d,d_devis dd, contrats ctr,ref_type_echeance ech ,echeances_contrat ec  
 WHERE d.id_client=c.id AND ctr.iddevis=d.id
 AND ctr.idtype_echeance=ech.id AND d.id=dd.id_devis AND ec.idcontrat=ctr.id 
 AND ec.`id` NOT IN (SELECT id_echeance FROM factures f WHERE f.`id_echeance`=ec.`id`)
 AND ctr.etat IN (1,2)
 and ec.id=echeance;
 
DECLARE CONTINUE HANDLER FOR NOT FOUND SET finished = TRUE;
 OPEN cur1;
 read_loop: LOOP
 FETCH cur1 INTO totalht, totaltva, totalttc, CLIENT, contrat, projet, ref_bc, du, au, order_d, id_produit, ref_produit, 
 designation, qte, qte_designation, prix_unitaire, type_remise, remise_valeur, tva, prix_ht, prix_ttc, total_ht, total_ttc, total_tva,echeance_id;
 
 IF finished THEN
      LEAVE read_loop;
 END IF;
SELECT CONCAT((SELECT IFNULL(( MAX(CAST(SUBSTR(reference, 8, LENGTH(SUBSTR(reference,8))-5) AS SIGNED))),0)+1 AS reference 
FROM factures WHERE SUBSTR(reference,LENGTH(reference)-3,4)= (SELECT  YEAR(SYSDATE()))),'/',(SELECT  YEAR(SYSDATE()))) INTO ref ;
INSERT INTO factures (reference, base_fact, total_ht, total_tva, total_ttc_initial ,total_ttc, total_paye, reste,  CLIENT, projet, ref_bc, idcontrat, id_echeance, date_facture, du, au, creusr, credat) 
VALUES(CONCAT('GT-FCT-',(IF(ref<=9,CONCAT('000',ref),IF(ref>9 AND ref<=99,CONCAT('00',ref),IF(ref>99 AND ref<=999,CONCAT('0',ref),ref)))))
,'C',totalht,totaltva,totalttc,totalttc,0,totalttc,CLIENT, projet, ref_bc, contrat, echeance_id,(SELECT NOW() FROM DUAL), du, au, 1,(SELECT NOW() FROM DUAL));
SELECT MAX(id) FROM factures f INTO cpt_fact;
INSERT INTO d_factures VALUES( (SELECT `AUTO_INCREMENT` FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'globaltech' AND   TABLE_NAME   = 'd_factures'),
order_d, cpt_fact,id_produit, ref_produit, designation, qte, qte_designation, prix_unitaire, type_remise,
remise_valeur, tva, prix_ht, prix_ttc, total_ht, total_ttc, total_tva,
1, (SELECT DATE(NOW()) FROM DUAL), NULL, NULL
			     );
UPDATE echeances_contrat e SET e.etat= 1 WHERE e.id=echeance_id;
END LOOP;
CLOSE cur1;
 
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `notify_contrat`()
BEGIN
update contrats c set c.`etat`= 2 where (SELECT date(NOW()) FROM DUAL)=c.`date_notif` and c.etat=1 ;
update contrats c set c.`etat`= 3 where (SELECT date(NOW()) FROM DUAL)=(c.`date_fin`+1) ;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `notify_contrat_frn`()
BEGIN
    
update contrats_frn c set c.`etat`= 2 where (SELECT date(NOW()) FROM DUAL)=c.`date_notif` and c.etat=1 ;
update contrats_frn c set c.`etat`= 3 where (SELECT date(NOW()) FROM DUAL)=(c.`date_fin`+1) ;
    END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `action_ticket`
--

CREATE TABLE IF NOT EXISTS `action_ticket` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `id_ticket` int(11) DEFAULT NULL COMMENT 'Ticket',
  `message` text COMMENT 'Description',
  `date_action` date DEFAULT NULL COMMENT 'Date',
  `photo` int(11) DEFAULT NULL COMMENT 'Photo',
  `pj` int(11) DEFAULT NULL COMMENT 'Pièce jointe',
  `etat` int(11) DEFAULT '0',
  `creusr` int(11) DEFAULT NULL,
  `credat` datetime DEFAULT CURRENT_TIMESTAMP,
  `updusr` int(11) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=60 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Table Archives' AUTO_INCREMENT=591 ;

--
-- Contenu de la table `archive`
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
(534, './upload/contrats10_2017/contrats_35.pdf', 'contrats 35', 'contrats', 'contrats', 35, 3, 'Document', 24, '2017-10-17 13:54:39'),
(535, './upload/fournisseurs/33/pj_33.pdf', 'Justifications du fournisseurstest', 'fournisseurs', 'fournisseurs', 33, 1, 'Document', 1, '2017-10-18 21:52:44'),
(536, './upload/fournisseurs/33/pj_photo_33.png', 'Photo du fournisseurtest', 'fournisseurs', 'fournisseurs', 33, 1, 'Document', 1, '2017-10-18 21:52:44'),
(538, './upload/clients/33/pj_33.pdf', 'Justifications du clientstest client', 'clients', 'clients', 33, 1, 'Document', 1, '2017-10-19 22:14:54'),
(539, './upload/clients/33/pj_photo_33.png', 'Photo du clienttest client', 'clients', 'clients', 33, 1, 'Document', 1, '2017-10-19 22:14:54'),
(543, './upload/contrats_fournisseurs/40/pj_40.pdf', 'Copie Contrat fournisseur.', 'contrats_fournisseurs', 'contrats_frn', 40, 1, 'Document', 1, '2017-11-16 20:59:45'),
(544, './upload//devis/mois_01_2018/46/46_GT_DEV-0003_2018.pdf', 'Devis 46 #GT_DEV-0003/2018', 'devis', 'devis', 46, 1, 'Document', 1, '2018-01-08 17:13:16'),
(545, './upload/encaissements/7/pj_7.pdf', 'Justifications de l''encaissement', 'encaissements', 'encaissements', 7, 1, 'Document', 1, '2018-01-14 23:12:50'),
(546, './upload/contrats01_2018/contrats_34.pdf', 'contrats 34', 'contrats', 'contrats', 34, 1, 'Document', 1, '2018-01-28 20:46:52'),
(547, './upload/contrats01_2018/contrats_33.pdf', 'contrats 33', 'contrats', 'contrats', 33, 1, 'Document', 1, '2018-01-28 20:50:37'),
(548, './upload/contrats01_2018/contrats_38.pdf', 'contrats 38', 'contrats', 'contrats', 38, 1, 'Document', 1, '2018-01-28 20:56:47'),
(549, './upload//devis/mois_02_2018/54/54_GT_DEV-0001_2018.pdf', 'Devis 54 #GT_DEV-0001/2018', 'devis', 'devis', 54, 1, 'Document', 1, '2018-02-04 17:31:50'),
(550, './upload//devis/mois_02_2018/55/55_GT_DEV-0002_2018.pdf', 'Devis 55 #GT_DEV-0002/2018', 'devis', 'devis', 55, 1, 'Document', 1, '2018-02-04 17:40:07'),
(551, './upload/contrats02_2018/contrats_75.pdf', 'contrats 75', 'contrats', 'contrats', 75, 1, 'Document', 1, '2018-02-04 17:40:57'),
(552, './upload//devis/mois_02_2018/56/56_GT_DEV-0003_2018.pdf', 'Devis 56 #GT_DEV-0003/2018', 'devis', 'devis', 56, 1, 'Document', 1, '2018-02-24 23:44:56'),
(553, './upload/contrats02_2018/contrats_76.pdf', 'contrats 76', 'contrats', 'contrats', 76, 1, 'Document', 1, '2018-02-25 12:04:06'),
(554, './upload//devis/mois_02_2018/57/57_GT_DEV-0004_2018.pdf', 'Devis 57 #GT_DEV-0004/2018', 'devis', 'devis', 57, 1, 'Document', 1, '2018-02-28 10:28:09'),
(555, './upload/contrats02_2018/contrats_77.pdf', 'contrats 77', 'contrats', 'contrats', 77, 1, 'Document', 1, '2018-02-28 10:30:09'),
(556, './upload//proforma/mois_03_2018/39/39_GT_PROF-0004_2018.pdf', 'Proforma 39 #GT_PROF-0004/2018', 'proforma', 'proforma', 39, 1, 'Document', 1, '2018-03-04 00:19:34'),
(557, './upload//devis/mois_03_2018/58/58_GT_DEV-0005_2018.pdf', 'Devis 58 #GT_DEV-0005/2018', 'devis', 'devis', 58, 1, 'Document', 1, '2018-03-04 00:30:39'),
(558, './upload/contrats03_2018/contrats_78.pdf', 'contrats 78', 'contrats', 'contrats', 78, 1, 'Document', 1, '2018-03-04 00:54:55'),
(560, './upload//devis/mois_03_2018/60/60_GT_DEV-0007_2018.pdf', 'Devis 60 #GT_DEV-0007/2018', 'devis', 'devis', 60, 1, 'Document', 1, '2018-03-04 02:10:04'),
(562, './upload//devis/mois_03_2018/59/59_GT_DEV-0006_2018.pdf', 'Devis 59 #GT_DEV-0006/2018', 'devis', 'devis', 59, 1, 'Document', 1, '2018-03-04 03:11:55'),
(563, './upload//devis/mois_03_2018/61/61_GT_DEV-0008_2018.pdf', 'Devis 61 #GT_DEV-0008/2018', 'devis', 'devis', 61, 1, 'Document', 1, '2018-03-04 12:21:19'),
(564, './upload//devis/mois_03_2018/62/62_GT_DEV-0009_2018.pdf', 'Devis 62 #GT_DEV-0009/2018', 'devis', 'devis', 62, 1, 'Document', 1, '2018-03-04 13:23:08'),
(567, './upload/contrats03_2018/contrats_79.pdf', 'contrats 79', 'contrats', 'contrats', 79, 1, 'Document', 1, '2018-03-04 13:35:05'),
(568, './upload//devis/mois_03_2018/63/63_GT_DEV-0010_2018.pdf', 'Devis 63 #GT_DEV-0010/2018', 'devis', 'devis', 63, 1, 'Document', 1, '2018-03-04 13:40:50'),
(569, './upload/contrats03_2018/contrats_82.pdf', 'contrats 82', 'contrats', 'contrats', 82, 1, 'Document', 1, '2018-03-04 14:15:35'),
(570, './upload/contrats03_2018/contrats_83.pdf', 'contrats 83', 'contrats', 'contrats', 83, 1, 'Document', 1, '2018-03-04 14:18:34'),
(571, './upload/contrats_fournisseurs/41/pj_41.pdf', 'Copie Contrat fournisseur.', 'contrats_fournisseurs', 'contrats_frn', 41, 1, 'Document', 1, '2018-03-04 14:24:08'),
(572, './upload/contrats03_2018/contrats_94.pdf', 'contrats 94', 'contrats', 'contrats', 94, 1, 'Document', 1, '2018-03-04 23:41:54'),
(573, './upload/contrats03_2018/contrats_95.pdf', 'contrats 95', 'contrats', 'contrats', 95, 1, 'Document', 1, '2018-03-05 02:51:55'),
(574, './upload/contrats03_2018/contrats_74.pdf', 'contrats 74', 'contrats', 'contrats', 74, 1, 'Document', 1, '2018-03-19 23:20:55'),
(575, './upload//devis/mois_04_2018/61/61_GT_DEV-0008_2018.pdf', 'Devis 61 #GT_DEV-0008/2018', 'devis', 'devis', 61, 1, 'Document', 1, '2018-04-06 20:04:32'),
(577, './upload/contrats04_2018/contrats_97.pdf', 'contrats 97', 'contrats', 'contrats', 97, 1, 'Document', 1, '2018-04-06 20:11:19'),
(578, './upload/contrats04_2018/contrats_77.pdf', 'contrats 77', 'contrats', 'contrats', 77, 1, 'Document', 1, '2018-04-06 21:44:15'),
(579, './upload/contrats04_2018/contrats_98.pdf', 'contrats 98', 'contrats', 'contrats', 98, 1, 'Document', 1, '2018-04-06 21:44:22'),
(580, './upload/tickets/26/photo_26.jpg', 'Photo15', 'tickets', 'action_ticket', 26, 1, 'Document', 1, '2018-04-21 18:48:55'),
(581, './upload/tickets/27/pj_27.pdf', 'PJ15', 'tickets', 'action_ticket', 27, 1, 'Document', 1, '2018-04-21 18:49:43'),
(582, './upload/tickets/27/photo_27.png', 'Photo15', 'tickets', 'action_ticket', 27, 1, 'Document', 1, '2018-04-21 18:49:43'),
(583, './upload/tickets/28/pj_28.pdf', 'PJ14', 'tickets', 'action_ticket', 28, 1, 'Document', 1, '2018-04-23 18:41:06'),
(584, './upload/tickets/28/photo_28.png', 'Photo14', 'tickets', 'action_ticket', 28, 1, 'Document', 1, '2018-04-23 18:41:06'),
(585, './upload//devis/mois_04_2018/64/64_GT_DEV-0011_2018.pdf', 'Devis 64 #GT_DEV-0011/2018', 'devis', 'devis', 64, 1, 'Document', 1, '2018-04-24 18:16:02'),
(586, './upload//devis/mois_04_2018/65/65_GT_DEV-0012_2018.pdf', 'Devis 65 #GT_DEV-0012/2018', 'devis', 'devis', 65, 1, 'Document', 1, '2018-04-29 09:54:40'),
(587, './upload//devis/mois_04_2018/66/66_GT_DEV-0013_2018.pdf', 'Devis 66 #GT_DEV-0013/2018', 'devis', 'devis', 66, 1, 'Document', 1, '2018-04-29 10:38:48'),
(590, './upload/contrats04_2018/contrats_100.pdf', 'contrats 100', 'contrats', 'contrats', 100, 1, 'Document', 1, '2018-04-29 18:33:32');

-- --------------------------------------------------------

--
-- Structure de la table `bl`
--

CREATE TABLE IF NOT EXISTS `bl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reference` varchar(20) DEFAULT NULL,
  `client` varchar(100) DEFAULT NULL,
  `projet` varchar(200) DEFAULT NULL COMMENT 'designation projet',
  `ref_bc` varchar(200) DEFAULT NULL COMMENT 'ref bon commande client',
  `iddevis` int(11) DEFAULT NULL COMMENT 'Devis',
  `date_bl` date DEFAULT NULL,
  `etat` int(11) DEFAULT '0',
  `creusr` int(11) DEFAULT NULL,
  `credat` datetime DEFAULT CURRENT_TIMESTAMP,
  `updusr` int(11) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_devis_bl` (`iddevis`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `bl`
--

INSERT INTO `bl` (`id`, `reference`, `client`, `projet`, `ref_bc`, `iddevis`, `date_bl`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(1, 'GT-BL-0001/2018', 'DCT', 'PROJ', 'Ref', 54, '2018-05-02', 0, 1, '2018-05-02 10:39:19', NULL, NULL),
(2, 'GT-BL-0002/2018', 'Test2018', NULL, 'OKK', 61, '2018-05-02', 0, 1, '2018-05-02 10:40:13', NULL, NULL);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `categorie_client`
--

INSERT INTO `categorie_client` (`id`, `categorie_client`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(1, 'Grossiste', 1, 1, '2017-08-23 14:26:24', 1, '2017-09-15 01:18:33'),
(2, 'Detaillant ', 1, 24, '2017-10-17 12:41:21', 24, '2017-10-17 12:43:54');

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

CREATE TABLE IF NOT EXISTS `clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `reference` varchar(20) NOT NULL COMMENT 'Code client',
  `type_client` varchar(1) DEFAULT NULL COMMENT 'Type client D/T',
  `denomination` varchar(200) NOT NULL COMMENT 'Denomination du client',
  `id_categorie` int(11) NOT NULL COMMENT 'Type client',
  `r_social` varchar(200) DEFAULT NULL COMMENT 'Raison social',
  `r_commerce` varchar(100) DEFAULT NULL COMMENT 'Registre de commerce',
  `nif` varchar(20) DEFAULT NULL COMMENT 'Id fiscale',
  `nom` varchar(100) DEFAULT NULL COMMENT 'Nom',
  `prenom` varchar(100) DEFAULT NULL COMMENT 'Prénom',
  `civilite` varchar(10) DEFAULT NULL COMMENT 'Sexe',
  `adresse` varchar(200) DEFAULT NULL COMMENT 'Adresse',
  `id_pays` int(11) NOT NULL COMMENT 'Pays',
  `id_ville` int(11) DEFAULT NULL COMMENT 'Ville',
  `tel` varchar(80) DEFAULT NULL COMMENT 'Telephone',
  `fax` varchar(80) DEFAULT NULL COMMENT 'Fax',
  `bp` varchar(80) DEFAULT NULL COMMENT 'Boite postale',
  `email` varchar(100) DEFAULT NULL COMMENT 'E-mail',
  `rib` varchar(30) DEFAULT NULL COMMENT 'compte bancaire du client',
  `id_devise` int(11) DEFAULT NULL COMMENT 'Devise de facturation du client',
  `tva` varchar(20) DEFAULT 'O' COMMENT 'tva O ou N',
  `pj` int(11) DEFAULT NULL,
  `pj_photo` int(11) DEFAULT NULL COMMENT 'photo du client',
  `etat` int(11) DEFAULT '0',
  `creusr` int(11) DEFAULT NULL,
  `credat` datetime DEFAULT CURRENT_TIMESTAMP,
  `updusr` int(11) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_code` (`reference`),
  KEY `fk_client_ville` (`id_ville`),
  KEY `fk_client_pays` (`id_pays`),
  KEY `fk_client_type` (`id_categorie`),
  KEY `fk_client_devise` (`id_devise`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=48 ;

--
-- Contenu de la table `clients`
--

INSERT INTO `clients` (`id`, `reference`, `type_client`, `denomination`, `id_categorie`, `r_social`, `r_commerce`, `nif`, `nom`, `prenom`, `civilite`, `adresse`, `id_pays`, `id_ville`, `tel`, `fax`, `bp`, `email`, `rib`, `id_devise`, `tva`, `pj`, `pj_photo`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(24, 'GT-CLT-1/2017', NULL, '50FED', 1, 'Fifteen Fed', '908765342', '3876627930', 'Ahmed', 'Salim', 'Homme', 'Sabangali', 242, 43, '98874635', '98874634', 'BP 5', 'ahmed.salim@ffa.td', NULL, 1, 'O', NULL, NULL, 1, 19, '2017-10-11 12:36:07', 1, '2017-10-19 01:15:35'),
(25, 'GT-CLT-2/2017', NULL, 'IMC', 1, 'International Management Company', '409347363', '2099984563', 'Abakar', 'Ahmed', 'Homme', 'Farcha', 242, 43, '44995588', '44995589', 'BP 6', 'abakar.ahmed@imc.td', NULL, 1, 'O', NULL, NULL, 1, 19, '2017-10-11 12:50:57', 19, '2017-10-11 12:54:24'),
(26, 'GT-CLT-3/2017', NULL, 'GSI', 1, 'Global System IT', '5544498756', '3499887465', 'Issein', 'Ibrahim', 'Homme', 'Chagoua', 242, 43, '55990055', '55990054', 'BP100', 'issein@gbs.td', NULL, 1, 'O', NULL, NULL, 1, 19, '2017-10-11 12:53:14', 19, '2017-10-11 12:54:28'),
(27, 'GT-CLT-4/2017', NULL, 'DCT', 1, 'Data Connect Tchad ', '235', '235', 'Rachid', 'Kada ', 'Homme', 'Bololo', 242, 43, '68777505', NULL, '180', 'rachid@dctchad.com', NULL, 1, 'O', NULL, NULL, 1, 24, '2017-10-17 12:33:37', 24, '2017-10-17 12:39:46'),
(33, 'GT_CLT-0005/2017', NULL, 'test client', 2, 'pll', NULL, NULL, NULL, NULL, 'Femme', 'pll', 75, 44, '05555555555555555555', NULL, NULL, 'em@em', NULL, 1, NULL, 538, 539, 0, 1, '2017-10-19 22:21:43', 1, '2017-10-19 23:14:54'),
(36, 'GT_CLT-0006/2017', NULL, 'ezfezf', 2, 'ezfzef', 'feezf', 'feef', 'ezfze', 'efdfe', 'Femme', 'zefzef', 242, 1, '3222222222222222', '3333333333333333', 'ee', 'em@em', NULL, 1, 'O', NULL, NULL, 0, 1, '2017-12-02 13:12:11', NULL, NULL),
(37, 'GT_CLT-0007/2017', NULL, 'EFSDFSDF', 2, 'EZFEZF', 'EZFEZ', 'FFF', 'FFF', 'FFFEE', 'Femme', 'ZEFZF', 242, NULL, '33333333333333', '344444444444444444', 'EE', 'EM@EE', NULL, 1, 'N', NULL, NULL, 1, 1, '2017-12-02 13:14:35', 1, '2018-01-03 12:50:58'),
(38, 'GT_CLT-0001/2018', NULL, 'testja', 2, 'rh', 'jlkjez', 'okozekf', 'ghh', 'hkhjk', 'Femme', 'lklk', 242, NULL, '0344444444444', '04444444444', 'bp 190', 'em@em.com', NULL, 1, NULL, NULL, NULL, 0, 1, '2018-03-04 00:24:55', 1, '2018-03-04 00:30:58'),
(39, 'GT_CLT-0002/2018', 'D', 'jiijef', 2, 'fezmktm', 'kfmzrk', 'mlkmzlrk', 'fezf', 'zegfrze', 'Femme', 'grzger', 242, NULL, '044444455555', '02222222222222222', 'rmzkgm', 'em@em', NULL, 1, 'O', NULL, NULL, 1, 1, '2018-03-04 00:37:25', 1, '2018-03-04 01:15:33'),
(44, 'GT_CLT-0003/2018', 'D', 'Test2018', 2, NULL, NULL, NULL, 'Test', NULL, 'Femme', 'Ndjamena', 242, 1, '66324513', NULL, NULL, NULL, NULL, 1, 'O', NULL, NULL, 1, 1, '2018-03-04 12:54:30', 1, '2018-03-04 12:57:32'),
(45, 'GT_CLT-0004/2018', NULL, 'zad', 2, NULL, NULL, NULL, NULL, NULL, 'Femme', NULL, 242, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'O', NULL, NULL, 0, 1, '2018-03-04 19:50:01', 1, '2018-03-04 20:10:07'),
(46, 'GT_CLT-0005/2018', NULL, 'pofzekfolerzjf', 1, NULL, NULL, NULL, 'ezkfjze', NULL, 'Femme', NULL, 242, 1, NULL, NULL, NULL, NULL, NULL, 1, 'N', NULL, NULL, 0, 1, '2018-03-04 20:12:28', NULL, NULL),
(47, 'GT_CLT-0006/2018', 'D', 'erjljglerjgf', 2, NULL, NULL, NULL, NULL, NULL, 'Femme', NULL, 242, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'N', NULL, NULL, 1, 1, '2018-03-04 20:15:58', 1, '2018-03-04 20:17:40');

-- --------------------------------------------------------

--
-- Structure de la table `code_cloture`
--

CREATE TABLE IF NOT EXISTS `code_cloture` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code_cloture` varchar(200) DEFAULT NULL,
  `etat` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Structure de la table `commerciaux`
--

CREATE TABLE IF NOT EXISTS `commerciaux` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `nom` varchar(50) DEFAULT NULL COMMENT 'Nom',
  `prenom` varchar(50) DEFAULT NULL COMMENT 'Prénom',
  `is_glbt` varchar(10) DEFAULT NULL COMMENT 'Interne/Externe',
  `cin` varchar(50) DEFAULT NULL COMMENT 'CIN',
  `rib` varchar(50) DEFAULT NULL COMMENT 'RIB',
  `tel` varchar(50) DEFAULT NULL COMMENT 'Tel',
  `sexe` varchar(10) DEFAULT NULL COMMENT 'Homme/Femme',
  `adresse` varchar(100) DEFAULT NULL COMMENT 'Adresse',
  `email` varchar(50) DEFAULT NULL COMMENT 'Email',
  `pj` int(11) DEFAULT NULL COMMENT 'Pièce Jointe',
  `photo` int(11) DEFAULT NULL COMMENT 'Photo',
  `etat` int(11) DEFAULT '0',
  `creusr` int(11) DEFAULT NULL,
  `credat` datetime DEFAULT CURRENT_TIMESTAMP,
  `updusr` int(11) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `commerciaux`
--

INSERT INTO `commerciaux` (`id`, `nom`, `prenom`, `is_glbt`, `cin`, `rib`, `tel`, `sexe`, `adresse`, `email`, `pj`, `photo`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(1, 'HANOUNOU', 'Fatima Zahra', 'Interne', 'BK263910', '033333333333333333', '0674471151', 'Femme', 'Hay El Moustakbale, Casablanca', 'hanounou.fatimazahra@hotmail.fr', NULL, NULL, 1, 1, '2018-01-01 13:26:48', NULL, NULL),
(2, 'FATIMA', 'ZAHRA', 'Non', 'bk362910', '0', '666666', NULL, NULL, NULL, NULL, NULL, 1, 1, '2018-03-04 12:04:04', 1, '2018-03-04 13:05:03');

-- --------------------------------------------------------

--
-- Structure de la table `complement_facture`
--

CREATE TABLE IF NOT EXISTS `complement_facture` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `designation` varchar(100) DEFAULT NULL,
  `idfacture` int(11) DEFAULT NULL,
  `montant` double DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `date_complement` date DEFAULT NULL,
  `etat` int(11) DEFAULT '0',
  `creusr` int(11) DEFAULT NULL,
  `credat` datetime DEFAULT CURRENT_TIMESTAMP,
  `updusr` int(11) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_facture_complement` (`idfacture`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Contenu de la table `complement_facture`
--

INSERT INTO `complement_facture` (`id`, `designation`, `idfacture`, `montant`, `type`, `date_complement`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(5, 'reduction', 130, -1000000, 'Réduction', '2018-03-04', 0, 1, '2018-03-04 11:33:24', NULL, NULL),
(6, 'Geste commerciale', 131, -2000000, 'Réduction', '2018-03-04', 0, 1, '2018-03-04 13:25:34', NULL, NULL),
(7, 'RETARD DE PAIEMENT ', 131, 50000, 'Pénalité', '2018-03-04', 0, 1, '2018-03-04 13:26:54', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `compte_commerciale`
--

CREATE TABLE IF NOT EXISTS `compte_commerciale` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_commerciale` int(11) DEFAULT NULL,
  `objet` varchar(200) DEFAULT NULL COMMENT 'objet',
  `id_facture` int(11) DEFAULT NULL,
  `id_encaissement` int(11) DEFAULT NULL,
  `id_credit` int(11) DEFAULT NULL COMMENT 'ID de la ligne credit',
  `credit` double DEFAULT NULL,
  `debit` double DEFAULT '0',
  `Type` varchar(100) DEFAULT 'A' COMMENT 'A => Base encaissement / B => commission action externe',
  `date_debit` date DEFAULT NULL COMMENT 'Date debit',
  `methode_payement` varchar(100) DEFAULT NULL,
  `pj` int(11) DEFAULT NULL,
  `etat` int(11) DEFAULT '0',
  `creusr` int(11) DEFAULT NULL,
  `credat` datetime DEFAULT CURRENT_TIMESTAMP,
  `updusr` int(11) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_compte_commerciale` (`id_commerciale`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=37 ;

--
-- Contenu de la table `compte_commerciale`
--

INSERT INTO `compte_commerciale` (`id`, `id_commerciale`, `objet`, `id_facture`, `id_encaissement`, `id_credit`, `credit`, `debit`, `Type`, `date_debit`, `methode_payement`, `pj`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(24, 1, '20% de la facture: GT-FCT-0001/2018', 108, NULL, NULL, 200000, 0, 'Automatique', NULL, NULL, NULL, 2, 1, '2018-01-23 23:51:42', 1, '2018-03-01 21:34:49'),
(25, 1, '10% de la facture: GT-FCT-0003/2018', 117, NULL, NULL, 1000, 0, 'Automatique', NULL, NULL, NULL, 1, 1, '2018-03-01 00:02:04', NULL, NULL),
(26, 1, '10% de la facture: GT-FCT-0003/2018', 117, NULL, NULL, 10, 0, 'Automatique', NULL, NULL, NULL, 1, 1, '2018-03-01 00:35:06', NULL, NULL),
(29, 1, 'wa3', NULL, NULL, 24, NULL, 100000, 'A', '2018-03-01', 'Espèce', NULL, 0, 1, '2018-03-01 20:34:49', NULL, NULL),
(30, 1, '10% de la facture: GT-FCT-0008/2018', 126, 14, NULL, 100000, 0, 'Automatique', NULL, NULL, NULL, 2, 1, '2018-03-04 03:05:00', 1, '2018-03-04 03:08:58'),
(31, 1, 'payer se7a', NULL, NULL, 30, NULL, 50000, 'A', '2018-03-04', 'Espèce', NULL, 0, 1, '2018-03-04 02:08:58', NULL, NULL),
(32, 2, '10% de la facture: GT-FCT-0013/2018', 131, 15, NULL, 500000, 0, 'Automatique', NULL, NULL, NULL, 3, 1, '2018-03-04 14:04:17', 1, '2018-03-04 14:07:50'),
(33, 2, 'Paiement', NULL, NULL, 32, NULL, 250000, 'A', '2018-03-04', 'Espèce', NULL, 0, 1, '2018-03-04 13:06:46', NULL, NULL),
(34, 2, 'Paiement', NULL, NULL, 32, NULL, 250000, 'A', '2018-03-04', 'Espèce', NULL, 0, 1, '2018-03-04 13:07:50', NULL, NULL),
(35, 2, '10% de la facture: GT-FCT-0013/2018', 131, 16, NULL, 440750, 0, 'Automatique', NULL, NULL, NULL, 1, 1, '2018-03-04 14:19:09', NULL, NULL),
(36, 2, 'comiisghg DEVIS 6000', NULL, NULL, NULL, 9000, 0, 'Manuelle', NULL, NULL, NULL, 0, 1, '2018-03-04 14:28:00', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `contrats`
--

CREATE TABLE IF NOT EXISTS `contrats` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `tkn_frm` varchar(32) DEFAULT NULL,
  `reference` varchar(20) NOT NULL COMMENT 'Reference',
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=103 ;

--
-- Contenu de la table `contrats`
--

INSERT INTO `contrats` (`id`, `tkn_frm`, `reference`, `iddevis`, `date_effet`, `date_fin`, `commentaire`, `date_contrat`, `idtype_echeance`, `periode_fact`, `date_notif`, `pj`, `pj_photo`, `contrats_pdf`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(74, '939ac00bd98ad97bd7eb4e01cd75c3c3', 'GT_CTR-0001/2018', 42, '2018-01-27', '2019-01-26', NULL, '2018-01-26', 4, 'D', '2018-01-27', NULL, NULL, 574, 3, 1, '2018-01-26 23:35:33', 1, '2018-03-20 00:20:54'),
(75, '4ede9e9b5e57dc083d446a3db208638e', 'GT_CTR-0002/2018', 55, '2018-02-25', '2020-02-24', NULL, '2018-02-24', 1, 'D', '2019-02-14', NULL, NULL, 551, 1, 1, '2018-02-04 18:40:50', 1, '2018-02-04 18:40:56'),
(76, '8133802dc67db95717b3263ba14b024b', 'GT_CTR-0003/2018', 56, '2018-02-25', '2019-02-25', NULL, '2018-02-26', 4, 'D', '2018-03-09', NULL, NULL, 553, 1, 1, '2018-02-25 13:03:55', 1, '2018-02-26 12:02:38'),
(77, 'ecb6fcab0e4a05d15855275826fa9084', 'GT_CTR-0004/2018', 57, '2018-02-28', '2020-02-27', '<p>TESST<br></p>', '2018-02-28', 4, 'D', '2018-05-24', NULL, NULL, 578, 0, 1, '2018-02-28 11:29:38', 1, '2018-04-06 22:44:15'),
(78, '47e1d5fcfb4df23218781236e76035e1', 'GT_CTR-0005/2018', 58, '2018-01-01', '2019-12-31', NULL, '2018-03-04', 1, 'D', '2018-03-14', NULL, NULL, 558, 1, 1, '2018-03-04 01:32:35', 1, '2018-03-04 01:54:54'),
(79, 'db0f07c62c5a7c3b6f139b0e835aaa3c', 'GT_CTR-0006/2018', 62, '2018-01-01', '2018-12-31', '<p>Paiement de bande passante <br></p>', '2018-03-04', 3, 'D', '2018-12-01', NULL, NULL, 567, 1, 1, '2018-03-04 14:24:54', 1, '2018-03-04 14:35:05'),
(97, '4d37317fe4b1d475fd68cab6def3b2ba', 'GT_CTR-0007/2018', 63, '2018-04-06', '2018-08-05', '<p>test<br></p>', '2018-04-06', 6, 'D', '2018-05-02', NULL, NULL, 577, 1, 1, '2018-04-06 21:08:07', 1, '2018-04-06 21:11:18'),
(100, 'd9601d32f390d0e719a14541e8bd2121', 'GT_CTR-0008/2018', 66, '2018-04-29', '2018-05-28', NULL, '2018-04-29', 4, 'D', '2018-05-05', NULL, NULL, 590, 4, 1, '2018-04-29 13:12:39', 1, '2018-04-29 19:33:32'),
(102, '02a95d86b2014b20a04b3882c2dd3819', 'GT_CTR-0009/2018', 66, '2018-04-29', '2018-05-28', NULL, '2018-04-29', 4, 'D', '2018-05-10', NULL, NULL, NULL, 0, 1, '2018-04-29 19:33:31', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `contrats_frn`
--

CREATE TABLE IF NOT EXISTS `contrats_frn` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `reference` varchar(20) NOT NULL COMMENT 'Reference',
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=42 ;

--
-- Contenu de la table `contrats_frn`
--

INSERT INTO `contrats_frn` (`id`, `reference`, `id_fournisseur`, `date_effet`, `date_fin`, `commentaire`, `date_notif`, `pj`, `pj_photo`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(40, 'GT_CTR-FRN-0001/2017', 33, '2017-10-18', '2017-10-31', NULL, '2017-10-23', 543, NULL, 0, 1, '2017-10-18 22:53:39', 1, '2017-11-16 21:59:45'),
(41, 'GT_CTR-FRN-0001/2018', 34, '2018-03-04', '2019-07-31', '<p>test<br></p>', '2019-07-01', 571, NULL, 1, 1, '2018-03-04 15:24:08', 1, '2018-03-04 15:24:28');

-- --------------------------------------------------------

--
-- Structure de la table `devis`
--

CREATE TABLE IF NOT EXISTS `devis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tkn_frm` varchar(32) DEFAULT NULL COMMENT 'Token Form insert',
  `type_devis` varchar(3) DEFAULT 'VNT' COMMENT 'Abonnement ou vente',
  `reference` varchar(20) DEFAULT NULL,
  `projet` varchar(200) DEFAULT NULL COMMENT 'Desgnation projet',
  `id_client` int(11) DEFAULT NULL,
  `tva` varchar(1) DEFAULT 'O' COMMENT 'Soumis à la TVA',
  `id_commercial` int(11) DEFAULT NULL COMMENT 'commercial chargé du suivi',
  `commission` double DEFAULT '0' COMMENT 'commission(%) du commercial',
  `total_commission` double DEFAULT '0' COMMENT 'prix total de la commssion',
  `date_devis` date DEFAULT NULL,
  `type_remise` varchar(1) DEFAULT 'P' COMMENT 'type de remise',
  `total_remise` float DEFAULT NULL,
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
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_id_client` (`id_client`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=67 ;

--
-- Contenu de la table `devis`
--

INSERT INTO `devis` (`id`, `tkn_frm`, `type_devis`, `reference`, `projet`, `id_client`, `tva`, `id_commercial`, `commission`, `total_commission`, `date_devis`, `type_remise`, `total_remise`, `valeur_remise`, `totalht`, `totalttc`, `totaltva`, `vie`, `claus_comercial`, `ref_bc`, `scan`, `devis_pdf`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(42, '9ca3399cf28c1b32ff9f2f0baec534f0', 'ABN', 'GT_DEV-0005/2017', NULL, 27, 'N', 1, 0, NULL, '2017-10-17', 'P', NULL, NULL, 175000, 175000, 0, 30, '<p>Paiement 100% à la commande</p><p>Le délais de livraison se féras dans 07 jours <br></p>', '', NULL, 533, 4, 24, '2017-10-17 13:20:05', NULL, NULL),
(54, '1ab438845a91bf90a7061eaee4680d97', 'VNT', 'GT_DEV-0001/2018', 'PROJ', 27, 'O', 1, 0, 145376, '2018-02-04', 'P', 0, 0, 1232000, 1453760, 221760, 30, 'Paiement 100% à la commande', 'Ref', NULL, 549, 4, 1, '2018-02-04 18:31:36', NULL, NULL),
(55, '5bf645a464932aa03713ae6763dab500', 'ABN', 'GT_DEV-0002/2018', 'PROJ', 27, 'O', 1, 10, 129800, '2018-02-04', 'P', 0, 0, 1100000, 1298000, 198000, 30, 'Paiement 100% à la commande', 'ok', NULL, 550, 4, 1, '2018-02-04 18:39:54', NULL, NULL),
(56, '0b78213fcc9088f97bd89aae6eb657ef', 'ABN', 'GT_DEV-0003/2018', NULL, 27, 'O', 1, 0, 0, '2018-02-15', 'P', 0, 0, 2999970, 3539964.6, 539994.6, 30, 'Paiement 100% à la commande', 'tfo', NULL, 552, 4, 1, '2018-02-15 23:54:23', NULL, NULL),
(57, '7e93d9bfe90f4edbaa5a9778d72e97f1', 'ABN', 'GT_DEV-0004/2018', 'TEST', 26, 'O', 1, 10, 129800, '2018-02-28', 'P', 0, 0, 1100000, 1298000, 198000, 60, 'Paiement 100% à la commande', 'OK', NULL, 554, 4, 1, '2018-02-28 11:27:49', NULL, NULL),
(58, '3653a1f11ef0a05cd75f73223af3be8e', 'ABN', 'GT_DEV-0005/2018', 'PROJ', 27, 'O', 1, 10, 233640, '2018-03-04', 'P', 0, 0, 1980000, 2336400, 356400, 60, 'Paiement 100% à la commande', 'ok', NULL, 557, 4, 1, '2018-03-04 01:20:46', 1, '2018-03-04 00:20:58'),
(59, 'b0fdc600148104d30ff7d89dd7de2f65', 'VNT', 'GT_DEV-0006/2018', 'TEE', 24, 'O', 1, 0, 0, '2018-03-04', 'P', 0, 0, 1820000, 2147600, 327600, 60, 'Paiement 100% à la commande', 'ok', NULL, 562, 4, 1, '2018-03-04 03:00:44', NULL, NULL),
(60, '1d46e3492ac93f010c4b7b2f9c8301e9', 'VNT', 'GT_DEV-0007/2018', 'test', 27, 'O', 1, 0, 0, '2018-03-04', 'P', 0, 0, 150000, 177000, 27000, 60, 'Paiement 100% à la commande', 'okk', NULL, 560, 4, 1, '2018-03-04 03:09:52', NULL, NULL),
(61, '5787f2e70a54529b573c4d3569a69d85', 'VNT', 'GT_DEV-0008/2018', NULL, 44, 'O', 2, 10, 1135750, '2018-03-04', 'P', 0, 0, 9625000, 11357500, 1732500, 30, 'Paiement 100% à la commande', 'OKK', NULL, 575, 4, 1, '2018-03-04 13:08:46', NULL, NULL),
(62, '401ba80040bcc48c30c7726f732433dc', 'ABN', 'GT_DEV-0009/2018', NULL, 44, 'O', 2, 10, 9994600, '2018-03-04', 'P', 0, 0, 84700000, 99946000, 15246000, 30, 'Paiement 100% à la commande', '', NULL, 564, 4, 1, '2018-03-04 14:22:18', NULL, NULL),
(63, 'fd49c619d27af1e877922955cac3de64', 'ABN', 'GT_DEV-0010/2018', NULL, 44, 'O', 2, 10, 778800, '2018-03-04', 'P', 0, 0, 6600000, 7788000, 1188000, 30, 'Paiement 100% à la commande', '', NULL, 568, 4, 1, '2018-03-04 14:40:21', NULL, NULL),
(64, 'a4976bbfe00197cc59ed9bd583850ff7', 'ABN', 'GT_DEV-0011/2018', 'hhhh', 24, 'O', 2, 0, 0, '2018-04-06', 'P', 0, 0, 150000, 177000, 27000, 30, '<p>Paiement 100% à la commande</p><p>Test saisi<br></p>', NULL, NULL, 585, 2, 1, '2018-04-06 21:58:36', NULL, NULL),
(65, '165f6fa1d3d437851c68e3ac0e3c3d65', 'ABN', 'GT_DEV-0012/2018', 'PROJ', 27, 'O', 1, 10, 97350, '2018-04-29', 'P', 0, 0, 825000, 973500, 148500, 90, 'Paiement 100% à la commande', 'OKK', NULL, 586, 4, 1, '2018-04-29 10:54:28', NULL, NULL),
(66, '942333cfdc0aadec3ba0418426e1c3fe', 'ABN', 'GT_DEV-0013/2018', 'Test abonnement', 27, 'N', 1, 0, 0, '2018-04-29', 'P', 0, 0, 1000000, 1000000, 0, 90, 'Paiement 100% à la commande', 'pk', NULL, 587, 4, 1, '2018-04-29 11:38:36', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `d_bl`
--

CREATE TABLE IF NOT EXISTS `d_bl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order` int(5) DEFAULT NULL,
  `id_bl` int(11) DEFAULT NULL,
  `id_produit` int(11) DEFAULT NULL,
  `ref_produit` varchar(20) DEFAULT NULL,
  `designation` varchar(40) DEFAULT NULL,
  `qte_cmd` int(11) DEFAULT NULL COMMENT 'la quantité commandée sur le devis et la facture',
  `qte_livre` int(11) DEFAULT NULL COMMENT 'la quantité livrée',
  `reliquat` int(11) DEFAULT NULL COMMENT 'le reste à livrer',
  `creusr` varchar(50) DEFAULT NULL,
  `credat` datetime DEFAULT NULL,
  `updusr` varchar(50) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_id_produit` (`id_produit`),
  KEY `fk_factures` (`id_bl`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `d_bl`
--

INSERT INTO `d_bl` (`id`, `order`, `id_bl`, `id_produit`, `ref_produit`, `designation`, `qte_cmd`, `qte_livre`, `reliquat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(1, 1, 2, 30, 'GT_PRD-0008', 'iDIRECT EVOLUTION X5', 5, 0, 5, '1', '2018-05-02 00:00:00', NULL, NULL),
(2, 2, 2, 22, 'GT-PRD-1/2017', 'LNB PLL bande C', 10, 0, 10, '1', '2018-05-02 00:00:00', NULL, NULL),
(3, 3, 2, 24, 'GT-PRD-3/2017', 'Antenne VSAT 1.2m bande Ku Skyware Globa', 5, 0, 5, '1', '2018-05-02 00:00:00', NULL, NULL);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=231 ;

--
-- Contenu de la table `d_devis`
--

INSERT INTO `d_devis` (`id`, `order`, `id_devis`, `tkn_frm`, `id_produit`, `ref_produit`, `designation`, `qte`, `prix_unitaire`, `type_remise`, `remise_valeur`, `tva`, `prix_ht`, `prix_ttc`, `total_ht`, `total_ttc`, `total_tva`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(223, 1, 61, '5787f2e70a54529b573c4d3569a69d85', 30, 'GT_PRD-0008', 'iDIRECT EVOLUTION X5', 5, 750000, 'P', 0, 0, 825000, NULL, 4125000, 4867500, 742500, '1', NULL, NULL, NULL),
(224, 2, 61, '5787f2e70a54529b573c4d3569a69d85', 22, 'GT-PRD-1/2017', 'LNB PLL bande C', 10, 150000, 'P', 0, 0, 165000, NULL, 1650000, 1947000, 297000, '1', NULL, NULL, NULL),
(225, 3, 61, '5787f2e70a54529b573c4d3569a69d85', 24, 'GT-PRD-3/2017', 'Antenne VSAT 1.2m bande Ku Skyware Globa', 5, 700000, 'P', 0, 0, 770000, NULL, 3850000, 4543000, 693000, '1', NULL, NULL, NULL),
(226, 1, 62, '401ba80040bcc48c30c7726f732433dc', 25, 'GT-PRD-4/2017', 'Connexion  Vsat 1 Mo', 77, 1000000, 'P', 0, 0, 1100000, NULL, 84700000, 99946000, 15246000, '1', NULL, '1', '2018-03-04 13:22:01'),
(227, 1, 63, 'fd49c619d27af1e877922955cac3de64', 25, 'GT-PRD-4/2017', 'Connexion  Vsat 1 Mo', 6, 1000000, 'P', 0, 0, 1100000, NULL, 6600000, 7788000, 1188000, '1', NULL, NULL, NULL),
(228, 1, 64, 'a4976bbfe00197cc59ed9bd583850ff7', 22, 'GT-PRD-1/2017', 'LNB PLL bande C', 1, 150000, 'P', 0, 0, 150000, NULL, 150000, 177000, 27000, '1', NULL, NULL, NULL),
(229, 1, 65, '165f6fa1d3d437851c68e3ac0e3c3d65', 1, 'GT_PRD-0008', 'iDIRECT EVOLUTION X6', 1, 750000, 'P', 0, 0, 825000, NULL, 825000, 973500, 148500, '1', NULL, NULL, NULL),
(230, 1, 66, '942333cfdc0aadec3ba0418426e1c3fe', 25, 'GT-PRD-4/2017', 'Connexion  Vsat 1 Mo', 1, 1000000, 'P', 0, 0, 1000000, NULL, 1000000, 1000000, 0, '1', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `d_factures`
--

CREATE TABLE IF NOT EXISTS `d_factures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order` int(5) DEFAULT NULL,
  `id_facture` int(11) DEFAULT NULL,
  `id_produit` int(11) DEFAULT NULL,
  `ref_produit` varchar(20) DEFAULT NULL,
  `designation` varchar(40) DEFAULT NULL,
  `qte` int(11) DEFAULT NULL,
  `qte_designation` varchar(50) DEFAULT NULL COMMENT 'la valeur de la qte Année ou mois',
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
  KEY `fk_id_produit` (`id_produit`),
  KEY `fk_factures` (`id_facture`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=41 ;

--
-- Contenu de la table `d_factures`
--

INSERT INTO `d_factures` (`id`, `order`, `id_facture`, `id_produit`, `ref_produit`, `designation`, `qte`, `qte_designation`, `prix_unitaire`, `type_remise`, `remise_valeur`, `tva`, `prix_ht`, `prix_ttc`, `total_ht`, `total_ttc`, `total_tva`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(8, 1, 115, 28, 'GT-PRD-6/2017', '1024/256kbps Astra4A', 6, 'MOIS', 175000, 'P', 0, 0, NULL, NULL, 175000, 175000, 0, 'admin', '2018-01-27 00:00:00', NULL, NULL),
(9, 1, 116, 28, 'GT-PRD-6/2017', '1024/256kbps Astra4A', 6, 'MOIS', 175000, 'P', 0, 0, NULL, NULL, 175000, 175000, 0, 'admin', '2018-01-27 00:00:00', NULL, NULL),
(10, 1, 117, 23, 'GT-PRD-2/2017', 'Modem iDirect Evolution X3', 1, ' ', 1120000, 'P', 0, 0, 1232000, NULL, 1232000, 1453760, 221760, 'admin', '2018-02-04 00:00:00', NULL, NULL),
(11, 1, 118, 25, 'GT-PRD-4/2017', 'Connexion  Vsat 1 Mo', 1, 'MOIS', 1000000, 'P', 0, 0, 1100000, NULL, 1100000, 1298000, 198000, 'admin', '2018-02-04 00:00:00', NULL, NULL),
(12, 1, 119, 25, 'GT-PRD-4/2017', 'Connexion  Vsat 1 Mo', 0, ' ', 1000000, 'M', 0, 0, 999990, NULL, 2999970, 3539964.6, 539994.6, 'admin', '2018-02-25 00:00:00', NULL, NULL),
(13, 1, 120, 25, 'GT-PRD-4/2017', 'Connexion  Vsat 1 Mo', 1, 'Ans', 1000000, 'P', 0, 0, 1100000, NULL, 13200000, 15576000, 2376000, 'admin', '2018-03-02 00:00:00', NULL, NULL),
(14, 1, 121, 25, 'GT-PRD-4/2017', 'Connexion  Vsat 1 Mo', 1, 'Ans', 1000000, 'P', 0, 0, 1100000, NULL, 13200000, 15576000, 2376000, 'admin', '2018-03-02 00:00:00', NULL, NULL),
(19, 1, 126, 25, 'GT-PRD-4/2017', 'Connexion  Vsat 1 Mo', 1, 'Ans', 1000000, 'P', 10, 0, 990000, NULL, 11880000, 14018400, 2138400, 'admin', '2018-03-04 00:00:00', NULL, NULL),
(21, 1, 128, 22, 'GT-PRD-1/2017', 'LNB PLL bande C', 1, ' ', 150000, 'P', 0, 0, 150000, NULL, 150000, 177000, 27000, 'admin', '2018-03-04 00:00:00', NULL, NULL),
(22, 1, 129, 23, 'GT-PRD-2/2017', 'Modem iDirect Evolution X3', 1, ' ', 1120000, 'P', 0, 0, 1120000, NULL, 1120000, 1321600, 201600, '1', '2018-03-04 00:00:00', NULL, NULL),
(24, 2, 129, 24, 'GT-PRD-3/2017', 'Antenne VSAT 1.2m bande Ku Skyware Globa', 1, ' ', 700000, 'P', 0, 0, 700000, NULL, 700000, 826000, 126000, '1', '2018-03-04 00:00:00', NULL, NULL),
(25, 1, 130, 23, 'GT-PRD-2/2017', 'Modem iDirect Evolution X3', 1, ' ', 1120000, 'P', 0, 0, 1120000, NULL, 1120000, 1321600, 201600, '1', '2018-03-04 00:00:00', NULL, NULL),
(26, 2, 130, 24, 'GT-PRD-3/2017', 'Antenne VSAT 1.2m bande Ku Skyware Globa', 1, ' ', 700000, 'P', 0, 0, 700000, NULL, 700000, 826000, 126000, '1', '2018-03-04 00:00:00', NULL, NULL),
(27, 1, 131, 30, 'GT_PRD-0008', 'iDIRECT EVOLUTION X5', 5, ' ', 750000, 'P', 0, 0, 825000, NULL, 4125000, 4867500, 742500, '1', '2018-03-04 00:00:00', NULL, NULL),
(28, 2, 131, 22, 'GT-PRD-1/2017', 'LNB PLL bande C', 10, ' ', 150000, 'P', 0, 0, 165000, NULL, 1650000, 1947000, 297000, '1', '2018-03-04 00:00:00', NULL, NULL),
(29, 3, 131, 24, 'GT-PRD-3/2017', 'Antenne VSAT 1.2m bande Ku Skyware Globa', 5, ' ', 700000, 'P', 0, 0, 770000, NULL, 3850000, 4543000, 693000, '1', '2018-03-04 00:00:00', NULL, NULL),
(30, 1, 132, 25, 'GT-PRD-4/2017', 'Connexion  Vsat 1 Mo', 3, 'Mois', 1000000, 'P', 0, 0, 1100000, NULL, 3300000, 3894000, 594000, '1', '2018-03-04 00:00:00', NULL, NULL),
(31, 1, 133, 30, 'GT_PRD-0008', 'iDIRECT EVOLUTION X5', 5, ' ', 750000, 'P', 0, 0, 825000, NULL, 4125000, 4867500, 742500, '1', '2018-04-06 00:00:00', NULL, NULL),
(32, 2, 133, 22, 'GT-PRD-1/2017', 'LNB PLL bande C', 10, ' ', 150000, 'P', 0, 0, 165000, NULL, 1650000, 1947000, 297000, '1', '2018-04-06 00:00:00', NULL, NULL),
(33, 3, 133, 24, 'GT-PRD-3/2017', 'Antenne VSAT 1.2m bande Ku Skyware Globa', 5, ' ', 700000, 'P', 0, 0, 770000, NULL, 3850000, 4543000, 693000, '1', '2018-04-06 00:00:00', NULL, NULL),
(35, 1, 135, 25, 'GT-PRD-4/2017', 'Connexion  Vsat 1 Mo', 2, 'Mois', 1000000, 'P', 0, 0, 1100000, NULL, 2200000, 2596000, 396000, '1', '2018-04-06 00:00:00', NULL, NULL),
(36, 1, 136, 25, 'GT-PRD-4/2017', 'Connexion  Vsat 1 Mo', 2, 'Mois', 1000000, 'P', 0, 0, 1100000, NULL, 2200000, 2596000, 396000, '1', '2018-04-06 00:00:00', NULL, NULL),
(37, 1, 137, 30, 'GT_PRD-0008', 'iDIRECT EVOLUTION X5', 5, ' ', 750000, 'P', 0, 0, 825000, NULL, 4125000, 4867500, 742500, '1', '2018-04-29 00:00:00', NULL, NULL),
(38, 2, 137, 22, 'GT-PRD-1/2017', 'LNB PLL bande C', 10, ' ', 150000, 'P', 0, 0, 165000, NULL, 1650000, 1947000, 297000, '1', '2018-04-29 00:00:00', NULL, NULL),
(39, 3, 137, 24, 'GT-PRD-3/2017', 'Antenne VSAT 1.2m bande Ku Skyware Globa', 5, ' ', 700000, 'P', 0, 0, 770000, NULL, 3850000, 4543000, 693000, '1', '2018-04-29 00:00:00', NULL, NULL),
(40, 1, 138, 1, 'GT_PRD-0008', 'iDIRECT EVOLUTION X6', 1, ' ', 750000, 'P', 0, 0, 825000, NULL, 825000, 973500, 148500, '1', '2018-04-29 00:00:00', NULL, NULL);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=221 ;

--
-- Contenu de la table `d_proforma`
--

INSERT INTO `d_proforma` (`id`, `order`, `id_proforma`, `tkn_frm`, `id_produit`, `ref_produit`, `designation`, `qte`, `prix_unitaire`, `type_remise`, `remise_valeur`, `tva`, `prix_ht`, `prix_ttc`, `total_ht`, `total_ttc`, `total_tva`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(198, 1, 30, 'ceeb410b47f8253ce9bd70f989c6ec88', 25, 'GT-PRD-4/2017', 'Connexion  Vsat 1 Mo', 1, 1000000, 'P', 5, 0, NULL, NULL, 950000, 1121000, 171000, '23', NULL, NULL, NULL),
(199, 1, 31, 'fe2afb3dc9e5675281199526c97bb4cf', 26, 'GT-PRD-5/2017', 'Installation Site', 1, 50000, 'P', 2, 0, NULL, NULL, 49000, 57820, 8820, '23', NULL, NULL, NULL),
(200, 1, 32, '6d50c057765b9b9966330a3fedef1836', 22, 'GT-PRD-1/2017', 'LNB PLL bande C', 1, 150000, 'P', 10, 0, NULL, NULL, 135000, 135000, 0, '23', NULL, NULL, NULL),
(201, 2, 32, '6d50c057765b9b9966330a3fedef1836', 26, 'GT-PRD-5/2017', 'Installation Site', 1, 50000, 'P', 20, 0, NULL, NULL, 40000, 40000, 0, '2', NULL, NULL, NULL),
(202, 1, 33, 'e974dbf42ed364ae632fb42b50b4c473', 25, 'GT-PRD-4/2017', 'Connexion  Vsat 1 Mo', 1, 1000000, 'P', 0, 0, NULL, NULL, 1000000, 1180000, 180000, '2', NULL, NULL, NULL),
(204, 1, 34, '45861c7a059c1622cbe15382631f1ac6', 24, 'GT-PRD-3/2017', 'Antenne VSAT 1.2m bande Ku Skyware Globa', 2, 700000, 'P', 3, 0, NULL, NULL, 1358000, 1602440, 244440, '24', NULL, NULL, NULL),
(205, 2, 34, '45861c7a059c1622cbe15382631f1ac6', 22, 'GT-PRD-1/2017', 'LNB PLL bande C', 1, 150000, 'P', 0, 0, NULL, NULL, 150000, 177000, 27000, '24', NULL, NULL, NULL),
(206, 3, 34, '45861c7a059c1622cbe15382631f1ac6', 28, 'GT-PRD-6/2017', '1024/256kbps Astra4A', 1, 175000, 'P', 5, 0, NULL, NULL, 166250, 196175, 29925, '24', NULL, NULL, NULL),
(208, 2, NULL, '15b4669867820e5e14003e39487b3c33', 22, 'GT-PRD-1/2017', 'LNB PLL bande C', 1, 165000, 'P', 0, 0, 165000, NULL, 165000, 194700, 29700, '1', NULL, NULL, NULL),
(211, 1, NULL, '58f66356ee28b9920b3ea81693aebbd3', 23, 'GT-PRD-2/2017', 'Modem iDirect Evolution X3', 1, 1120000, 'P', 0, 0, 1232000, NULL, 1232000, 1453760, 221760, '1', NULL, NULL, NULL),
(212, 1, 35, '149afd94e32f9699809f4a2ba2d89516', 23, 'GT-PRD-2/2017', 'Modem iDirect Evolution X3', 1, 1120000, 'P', 0, 0, 1344000, NULL, 1344000, 1585920, 241920, '1', NULL, NULL, NULL),
(213, 1, 36, '05000f01c91be5328130ce8b51681502', 23, 'GT-PRD-2/2017', 'Modem iDirect Evolution X3', 1, 1120000, 'P', 0, 0, 1456000, NULL, 1456000, 1718080, 262080, '1', NULL, NULL, NULL),
(214, 1, 37, 'f0630d71d9940d7f4d24b48b87102d0a', 23, 'GT-PRD-2/2017', 'Modem iDirect Evolution X3', 2, 1120000, 'P', 0, 0, 1344000, NULL, 2688000, 3171840, 483840, '1', NULL, NULL, NULL),
(215, 2, 37, 'f0630d71d9940d7f4d24b48b87102d0a', 22, 'GT-PRD-1/2017', 'LNB PLL bande C', 1, 150000, 'P', 0, 0, 180000, NULL, 180000, 212400, 32400, '1', NULL, NULL, NULL),
(217, 2, 38, '26dbdeef9108faf5bd5304214a0465a6', 26, 'GT-PRD-5/2017', 'Installation Site', 1, 50000, 'P', 0, 0, 111111, NULL, 55000, 64900, 9900, '1', NULL, NULL, NULL),
(218, 3, 38, '26dbdeef9108faf5bd5304214a0465a6', 25, 'GT-PRD-4/2017', 'Connexion  Vsat 1 Mo', 1, 1000000, 'P', 0, 0, 1100000, NULL, 1100000, 1298000, 198000, '1', NULL, NULL, NULL),
(219, 1, 39, 'a920d82f8764a2ce940de7a94a9f6278', 25, 'GT-PRD-4/2017', 'Connexion  Vsat 1 Mo', 1, 1000000, 'P', 0, 0, 1000000, NULL, 1000000, 1000000, 0, '1', NULL, NULL, NULL),
(220, 2, 39, 'a920d82f8764a2ce940de7a94a9f6278', 26, 'GT-PRD-5/2017', 'Installation Site', 1, 50000, 'P', 0, 0, 50000, NULL, 50000, 50000, 0, '1', NULL, NULL, NULL);

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
  `date_debut` date DEFAULT NULL COMMENT 'date_debut de facturation',
  `date_fin` date DEFAULT NULL COMMENT 'date_fin de facturation',
  `etat` int(11) DEFAULT '0',
  `creusr` int(11) DEFAULT NULL,
  `credat` datetime DEFAULT CURRENT_TIMESTAMP,
  `updusr` int(11) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`,`date_echeance`,`montant`),
  KEY `fk_contrat_echeance` (`idcontrat`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=141 ;

--
-- Contenu de la table `echeances_contrat`
--

INSERT INTO `echeances_contrat` (`id`, `tkn_frm`, `order`, `date_echeance`, `montant`, `commentaire`, `idcontrat`, `date_debut`, `date_fin`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(51, NULL, NULL, '2018-03-02', 0, NULL, 77, '2018-02-28', '2019-02-27', 1, 1, '2018-02-28 11:31:07', NULL, NULL),
(52, NULL, NULL, '2019-02-28', 0, NULL, 77, '2019-02-28', '2020-02-27', 1, 1, '2018-02-28 11:31:07', NULL, NULL),
(55, NULL, NULL, '2018-03-04', 0, NULL, 78, '2018-01-01', '2018-12-31', 1, 1, '2018-03-04 01:36:11', NULL, NULL),
(56, NULL, NULL, '2019-01-01', 0, NULL, 78, '2019-01-01', '2019-12-31', 0, 1, '2018-03-04 01:36:11', NULL, NULL),
(68, NULL, NULL, '2018-01-01', 0, NULL, 79, '2018-01-01', '2018-03-31', 1, 1, '2018-03-04 14:34:18', NULL, NULL),
(69, NULL, NULL, '2018-04-01', 0, NULL, 79, '2018-04-01', '2018-06-30', 0, 1, '2018-03-04 14:34:18', NULL, NULL),
(70, NULL, NULL, '2018-07-01', 0, NULL, 79, '2018-07-01', '2018-09-30', 0, 1, '2018-03-04 14:34:18', NULL, NULL),
(71, NULL, NULL, '2018-10-01', 0, NULL, 79, '2018-10-01', '2018-12-31', 0, 1, '2018-03-04 14:34:18', NULL, NULL),
(123, NULL, NULL, '2018-04-06', 0, NULL, 97, '2018-04-06', '2018-06-05', 1, 1, '2018-04-06 21:11:11', NULL, NULL),
(124, NULL, NULL, '2018-06-06', 0, NULL, 97, '2018-06-06', '2018-08-05', 1, 1, '2018-04-06 21:11:11', NULL, NULL),
(135, 'd9601d32f390d0e719a14541e8bd2121', 1, '2018-04-29', 500000, NULL, 100, '2018-04-29', '2018-05-01', 0, 1, '2018-04-29 13:04:51', 1, '2018-04-29 13:18:21'),
(136, 'd9601d32f390d0e719a14541e8bd2121', 2, '2018-05-02', 500000, NULL, 100, '2018-05-02', '2018-05-28', 0, 1, '2018-04-29 13:10:42', 1, '2018-04-29 13:12:37'),
(139, '02a95d86b2014b20a04b3882c2dd3819', 1, '2018-04-29', 100000, NULL, 102, '2018-04-29', '2018-05-16', 0, 1, '2018-04-29 19:31:57', NULL, NULL),
(140, '02a95d86b2014b20a04b3882c2dd3819', 2, '2018-05-17', 900000, NULL, 102, '2018-05-17', '2018-05-28', 0, 1, '2018-04-29 19:33:05', 1, '2018-04-29 19:33:28');

-- --------------------------------------------------------

--
-- Structure de la table `encaissements`
--

CREATE TABLE IF NOT EXISTS `encaissements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reference` varchar(20) DEFAULT NULL,
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
  `ref_payement` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_facture` (`idfacture`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- Contenu de la table `encaissements`
--

INSERT INTO `encaissements` (`id`, `reference`, `designation`, `idfacture`, `montant`, `pj`, `date_encaissement`, `etat`, `creusr`, `credat`, `updusr`, `upddat`, `mode_payement`, `ref_payement`) VALUES
(12, 'GT_ENC-0006/2018', 'kjhkh', 117, 1000, NULL, '2018-03-01', 1, 1, '2018-03-01 20:39:42', 1, '2018-04-05 20:36:05', 0, 'reeffghh'),
(13, 'GT_ENC-0009/2018', 'test', 117, 2000, NULL, '2018-03-04', 1, 1, '2018-03-04 00:10:07', 1, '2018-04-05 20:36:21', 0, '100'),
(14, 'GT_ENC-0010/2018', 'test', 126, 1000000, NULL, '2018-03-04', 0, 1, '2018-03-04 03:05:00', NULL, NULL, 0, 'rrr'),
(15, 'GT_ENC-0009/2018', 'Paiement ', 131, 5000000, NULL, '2018-03-04', 0, 1, '2018-03-04 14:04:17', NULL, NULL, 0, NULL),
(16, 'GT_ENC-0009/2018', 'test', 131, 4407500, NULL, '2018-03-04', 0, 1, '2018-03-04 14:19:09', NULL, NULL, 0, 'ref');

-- --------------------------------------------------------

--
-- Structure de la table `entrepots`
--

CREATE TABLE IF NOT EXISTS `entrepots` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `reference` varchar(20) NOT NULL COMMENT 'Référence',
  `libelle` varchar(200) NOT NULL COMMENT 'Entrepot',
  `emplacement` varchar(500) DEFAULT NULL COMMENT 'Emplacement Physique',
  `date_creation` date DEFAULT NULL COMMENT 'Date de création',
  `etat` int(11) DEFAULT '0',
  `creusr` int(11) DEFAULT NULL,
  `credat` datetime DEFAULT CURRENT_TIMESTAMP,
  `updusr` int(11) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `entrepots`
--

INSERT INTO `entrepots` (`id`, `reference`, `libelle`, `emplacement`, `date_creation`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(1, 'GT_ENT-0001/2018', 'Entrepôt des vsat', 'bd charles de gaule', '2018-02-01', 1, 1, '2018-04-25 19:05:30', 1, '2018-04-25 20:19:49');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=203 ;

--
-- Contenu de la table `espionnage_update`
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
(51, '51ad101805323814f6af1efbbff789bd', 'devis', 40, 'projet', 'Installation VSAT trache 1 N''Djamena', '0', 'fati', '2017-10-16 23:20:02'),
(52, '51ad101805323814f6af1efbbff789bd', 'devis', 40, 'totalttc', '727000', '857860', 'fati', '2017-10-16 23:20:02'),
(53, '51ad101805323814f6af1efbbff789bd', 'devis', 40, 'totaltva', '0', '130860', 'fati', '2017-10-16 23:20:02'),
(54, 'a844503013afc92ca00d4c56a4ed6ba6', 'devis', 40, 'date_devis', '16-10-2017', '2017-10-16', 'fati', '2017-10-16 23:23:22'),
(55, 'a844503013afc92ca00d4c56a4ed6ba6', 'devis', 40, 'projet', '0', 'Installation VSAT trache 1 N''Djamena', 'fati', '2017-10-16 23:23:22'),
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
(67, '7342a1cdba36f91c5408535d5f4e52d9', 'contrats', 32, 'date_fin', '2018-10-17', '2018-10-20', 'admin', '2017-10-17 01:24:40'),
(68, '3209fc410521cf614ef443ccfcb422db', 'echeances_contrat', 43, 'commentaire', 'ok', 'okkk', 'admin', '2017-10-18 18:17:04'),
(69, '67247e0d5218371dfc846d2b004e5e7b', 'echeances_contrat', 43, 'commentaire', 'okkk', 'okkk :)', 'admin', '2017-10-18 18:17:56'),
(70, '7e43ac7027280f044b6fa857a8a086b1', 'echeances_contrat', 44, 'montant', '100000', '75000', 'admin', '2017-10-18 18:30:38'),
(71, '7af53f5f5008f6fec8f93402351202be', 'contrats', 37, 'idtype_echeance', '2', '4', 'admin', '2017-10-18 18:30:41'),
(72, '178dd4a1690eb7a7b4fa98f15f82cc83', 'echeances_contrat', 44, 'montant', '75000', '74000', 'admin', '2017-10-18 19:59:54'),
(73, '3156ec82aea717e3f687396fd07062ab', 'echeances_contrat', 45, 'montant', '100000', '1000', 'admin', '2017-10-18 20:00:02'),
(74, 'ab53095a01564f77a65c44204ef0146b', 'clients', 28, 'tva', 'NON', NULL, 'admin', '2017-10-18 21:35:44'),
(75, '5a25a7248fc745332649a5ea9ea7ba70', 'clients', 29, 'tva', 'OUI', NULL, 'admin', '2017-10-19 12:55:01'),
(76, '5376265e11eec9dc6666f93a7e3a8297', 'clients', 29, 'tva', 'NON', NULL, 'admin', '2017-10-19 12:59:42'),
(77, 'd8d95dfe4b8726a4f1bdfc377b20b25a', 'clients', 29, 'tva', 'NON', NULL, 'admin', '2017-10-19 13:00:05'),
(78, 'fd9a030111b98b652d99107b403c4947', 'clients', 29, 'tva', 'NON', NULL, 'admin', '2017-10-19 13:08:59'),
(79, 'e8176d9b23f054262301214da100ec0f', 'clients', 29, 'r_social', 'rs', 'rsg', 'admin', '2017-10-19 13:19:10'),
(80, 'e8176d9b23f054262301214da100ec0f', 'clients', 29, 'id_ville', '1', '9', 'admin', '2017-10-19 13:19:10'),
(81, 'e8176d9b23f054262301214da100ec0f', 'clients', 29, 'tva', 'NON', NULL, 'admin', '2017-10-19 13:19:10'),
(82, '6ed3111fae0493eb26d7dec878c7615b', 'fournisseurs', 36, 'id_pays', '242', '209', 'admin', '2017-10-19 21:20:19'),
(83, 'f684ec5fef2d3cd6e98ed5374ff54658', 'clients', 33, 'tva', 'OUI', NULL, 'admin', '2017-10-19 21:24:31'),
(84, '6abbeb2c944115e1ad04686f98d1dd56', 'clients', 33, 'tva', 'NON', NULL, 'admin', '2017-10-19 21:28:42'),
(85, '8aebce06ecb1cbfa87da346c481bfb34', 'ref_region', 31, 'id_pays', '242', '75', 'admin', '2017-10-19 21:36:43'),
(86, '0c8519fc23a1557437f36ef53a32b5ba', 'clients', 33, 'id_pays', '242', '75', 'admin', '2017-10-19 21:37:22'),
(87, '0c8519fc23a1557437f36ef53a32b5ba', 'clients', 33, 'id_ville', '1', '44', 'admin', '2017-10-19 21:37:22'),
(88, '0c8519fc23a1557437f36ef53a32b5ba', 'clients', 33, 'tva', 'NON', NULL, 'admin', '2017-10-19 21:37:22'),
(89, 'fc6e9d6605c6f6766336a3b19d488ab8', 'fournisseurs', 33, 'id_pays', '209', '197', 'admin', '2017-10-19 21:49:34'),
(90, '423ab9e30cd8d2c9dee069fcb983b586', 'fournisseurs', 33, 'r_social', 'rs', 'okk', 'admin', '2017-10-19 21:50:01'),
(91, '423ab9e30cd8d2c9dee069fcb983b586', 'fournisseurs', 33, 'id_pays', '197', '242', 'admin', '2017-10-19 21:50:01'),
(92, '83fecc453ec42b6a6fefc5a0ea6e02da', 'clients', 33, 'tva', 'NON', NULL, 'admin', '2017-10-19 22:14:54'),
(93, 'a564bf16b6445da8430fc0790972d997', 'clients', 37, 'tva', 'OUI', NULL, 'admin', '2017-12-02 12:19:08'),
(94, '041da327e8e00de89c5d1d5af36e2b8e', 'clients', 37, 'tva', 'NON', 'N', 'admin', '2018-01-03 11:50:50'),
(95, 'b66a69be1e6cbfa53a5d75fc7231da80', 'devis', 45, 'commission', '10', '15', 'admin', '2018-01-05 23:15:06'),
(96, 'b66a69be1e6cbfa53a5d75fc7231da80', 'devis', 45, 'date_devis', '05-01-2018', '2018-01-06', 'admin', '2018-01-05 23:15:06'),
(97, 'b66a69be1e6cbfa53a5d75fc7231da80', 'devis', 45, 'valeur_remise', '0', '0.00', 'admin', '2018-01-05 23:15:06'),
(98, 'bd66e09af3bc37479183be8ad163f393', 'devis', 45, 'commission', '15', '10', 'admin', '2018-01-05 23:15:22'),
(99, 'bd66e09af3bc37479183be8ad163f393', 'devis', 45, 'date_devis', '06-01-2018', '2018-01-06', 'admin', '2018-01-05 23:15:22'),
(100, 'bd66e09af3bc37479183be8ad163f393', 'devis', 45, 'valeur_remise', '0', '0.00', 'admin', '2018-01-05 23:15:22'),
(101, 'ebca2b2bd75326eddfc8e535e3be6fcf', 'devis', 45, 'commission', '10', '20', 'admin', '2018-01-08 17:00:37'),
(102, 'ebca2b2bd75326eddfc8e535e3be6fcf', 'devis', 45, 'date_devis', '06-01-2018', '2018-01-08', 'admin', '2018-01-08 17:00:37'),
(103, 'ebca2b2bd75326eddfc8e535e3be6fcf', 'devis', 45, 'valeur_remise', '0', '0.00', 'admin', '2018-01-08 17:00:37'),
(104, 'ebca2b2bd75326eddfc8e535e3be6fcf', 'devis', 45, 'totalht', '12320000', '13500000', 'admin', '2018-01-08 17:00:37'),
(105, 'ebca2b2bd75326eddfc8e535e3be6fcf', 'devis', 45, 'totalttc', '14537600', '15930000', 'admin', '2018-01-08 17:00:37'),
(106, 'ebca2b2bd75326eddfc8e535e3be6fcf', 'devis', 45, 'totaltva', '2217600', '2430000', 'admin', '2018-01-08 17:00:37'),
(107, 'd11ca979618c0ee3fd5ceb58c72b3a9b', 'devis', 45, 'commission', '20', '10', 'admin', '2018-01-08 17:01:01'),
(108, 'd11ca979618c0ee3fd5ceb58c72b3a9b', 'devis', 45, 'date_devis', '08-01-2018', '2018-01-08', 'admin', '2018-01-08 17:01:01'),
(109, 'd11ca979618c0ee3fd5ceb58c72b3a9b', 'devis', 45, 'valeur_remise', '0', '0.00', 'admin', '2018-01-08 17:01:01'),
(110, 'd11ca979618c0ee3fd5ceb58c72b3a9b', 'devis', 45, 'totalht', '13500000', '12375000', 'admin', '2018-01-08 17:01:01'),
(111, 'd11ca979618c0ee3fd5ceb58c72b3a9b', 'devis', 45, 'totalttc', '15930000', '14602500', 'admin', '2018-01-08 17:01:01'),
(112, 'd11ca979618c0ee3fd5ceb58c72b3a9b', 'devis', 45, 'totaltva', '2430000', '2227500', 'admin', '2018-01-08 17:01:01'),
(113, '6e9ae387dd2b230cdafe65e62fd6f8ba', 'devis', 52, 'date_devis', '11-01-2018', '2018-01-12', 'admin', '2018-01-11 23:22:29'),
(114, '6e9ae387dd2b230cdafe65e62fd6f8ba', 'devis', 52, 'valeur_remise', '0', '0.00', 'admin', '2018-01-11 23:22:29'),
(115, 'be567fafc1749bf11d807698d9c3c80d', 'proforma', 1, 'date_proforma', '23-01-2018', '2018-01-23', 'admin', '2018-01-23 00:12:07'),
(116, '4502e5248253e0f9a1ddce06ed0f3cc5', 'proforma', 1, 'date_proforma', '23-01-2018', '2018-01-23', 'admin', '2018-01-23 00:15:00'),
(117, '2f252c1ace2533d3dfa4e9f321c0dc9c', 'proforma', 1, 'date_proforma', '23-01-2018', '2018-01-23', 'admin', '2018-01-23 00:15:50'),
(118, 'b2571dbd090f91a784bd4bb5f27fa426', 'd_proforma', 216, 'ref_produit', 'GT-PRD-2/2017', NULL, 'admin', '2018-01-23 00:19:58'),
(119, 'b2571dbd090f91a784bd4bb5f27fa426', 'd_proforma', 216, 'prix_ht', '1232000', '1108800', 'admin', '2018-01-23 00:19:58'),
(120, 'b2571dbd090f91a784bd4bb5f27fa426', 'd_proforma', 216, 'remise_valeur', '0', '10.0', 'admin', '2018-01-23 00:19:58'),
(121, 'b2571dbd090f91a784bd4bb5f27fa426', 'd_proforma', 216, 'tva', '0', 'O', 'admin', '2018-01-23 00:19:58'),
(122, 'b2571dbd090f91a784bd4bb5f27fa426', 'd_proforma', 216, 'total_ht', '1232000', '1108800', 'admin', '2018-01-23 00:19:58'),
(123, 'b2571dbd090f91a784bd4bb5f27fa426', 'd_proforma', 216, 'total_ttc', '1453760', '1308384', 'admin', '2018-01-23 00:19:58'),
(124, 'b2571dbd090f91a784bd4bb5f27fa426', 'd_proforma', 216, 'total_tva', '221760', '199584', 'admin', '2018-01-23 00:19:58'),
(125, '9c8d08aca46260c262ca3c118251a7c8', 'd_proforma', 216, 'remise_valeur', '10', '10.0', 'admin', '2018-01-23 00:20:23'),
(126, '9c8d08aca46260c262ca3c118251a7c8', 'd_proforma', 216, 'tva', '0', 'O', 'admin', '2018-01-23 00:20:23'),
(127, 'd1441d168dfd9c3208045da6960e6e24', 'proforma', 1, 'date_proforma', '23-01-2018', '2018-01-23', 'admin', '2018-01-23 22:45:21'),
(128, 'd307447da03126312c88d32b20e04192', 'contrats', 38, 'date_contrat', '2017-10-20', '2018-01-24', 'admin', '2018-01-23 23:15:00'),
(129, 'df88ea6817fc8c5a42e191f9a6d05c80', 'contrats', 71, 'date_fin', '2019-01-25', '2019-02-25', 'admin', '2018-01-26 22:19:51'),
(130, 'afb4b701f3e6ae94f501bd2d00d58206', 'contrats', 71, 'idtype_echeance', '2', '5', 'admin', '2018-01-26 22:20:39'),
(131, 'ac659f4ac99948ff1d49245f8a97fe3d', 'contrats', 71, 'date_fin', '2019-02-25', '2019-01-25', 'admin', '2018-01-26 22:22:17'),
(132, 'b05a8950b4503e0df143a1cde810ea25', 'contrats', 71, 'date_fin', '2019-01-25', '2019-05-15', 'admin', '2018-01-26 22:22:37'),
(133, '3de3fadac3ee16d015549ea09cb5c3bc', 'contrats', 74, 'date_fin', '2018-07-25', '2019-01-25', 'admin', '2018-01-26 22:35:57'),
(134, '8fab66883a480d6a0b341f3439f9a0bc', 'contrats', 38, 'date_fin', '2018-10-20', '2018-10-19', 'admin', '2018-01-28 20:56:32'),
(135, '8fab66883a480d6a0b341f3439f9a0bc', 'contrats', 38, 'date_contrat', '2018-01-24', '2018-01-28', 'admin', '2018-01-28 20:56:32'),
(136, '8fab66883a480d6a0b341f3439f9a0bc', 'contrats', 38, 'idtype_echeance', '1', '2', 'admin', '2018-01-28 20:56:32'),
(137, '9e25ec5a803a609d468ff2348e02d86b', 'echeances_contrat', 44, 'date_echeance', '2018-02-25', '2019-02-25', 'admin', '2018-02-25 12:02:31'),
(138, '9e25ec5a803a609d468ff2348e02d86b', 'echeances_contrat', 44, 'montant', '300000', '3530000', 'admin', '2018-02-25 12:02:31'),
(139, '2a69841110afccc70fcec24a05fc8069', 'echeances_contrat', 44, 'montant', '3530000', '3539964', 'admin', '2018-02-25 12:02:52'),
(140, '2648796a07ec3cab29788c2f58f5eb79', 'echeances_contrat', 44, 'montant', '3539964', '3539964.6', 'admin', '2018-02-25 12:03:05'),
(141, '4f0f281320325e7e1195a10d677cfcef', 'echeances_contrat', 44, 'montant', '3539964.6', '1539964.6', 'admin', '2018-02-25 12:03:50'),
(142, '0017d3a360b6c9d54c010ec5a7ac936f', 'echeances_contrat', 43, 'montant', '2000000', '1000000', 'admin', '2018-02-25 20:33:38'),
(143, '4684030a563f072d92b0cce1367353f5', 'echeances_contrat', 45, 'montant', '1000000', '500000', 'admin', '2018-02-26 10:59:10'),
(144, 'fd26689b61c3a4be8cf69e0d4326ceda', 'contrats', 76, 'date_contrat', '2018-02-25', '2018-02-26', 'admin', '2018-02-26 11:00:32'),
(145, '79ba53fc2fe5748293fb9d1912808781', 'echeances_contrat', 46, 'date_debut', NULL, '2019-01-25', 'admin', '2018-02-26 11:18:12'),
(146, '43d41350157a7a6d743536aca4242cba', 'echeances_contrat', 45, 'date_debut', NULL, '2018-07-25', 'admin', '2018-02-26 11:18:32'),
(147, '4ee45ca1b27ce2110ea75a63d25b9642', 'echeances_contrat', 44, 'date_debut', NULL, '2019-02-25', 'admin', '2018-02-26 11:18:37'),
(148, 'd4a51b7efd5c004b71af144a99b3cd4e', 'echeances_contrat', 43, 'date_debut', NULL, '2018-02-25', 'admin', '2018-02-26 11:18:41'),
(149, '234ea65c394f06d1134020b90b1fae8f', 'contrats', 77, 'date_fin', '2019-02-27', '2020-02-27', 'admin', '2018-02-28 10:31:07'),
(150, '234ea65c394f06d1134020b90b1fae8f', 'contrats', 77, 'idtype_echeance', '3', '1', 'admin', '2018-02-28 10:31:07'),
(151, 'a448894109c4abca1fb6f33a61aaa473', 'clients', 38, 'denomination', 'test', 'testja', 'admin', '2018-03-03 23:25:14'),
(152, 'a448894109c4abca1fb6f33a61aaa473', 'clients', 38, 'tva', 'OUI', NULL, 'admin', '2018-03-03 23:25:14'),
(153, '2ae44eb325680ea120100397bbbd4511', 'clients', 38, 'tva', 'NON', NULL, 'admin', '2018-03-03 23:30:58'),
(154, '097cb3f47872f395ecedc360e0dc08dd', 'clients', 39, 'tva', 'NON', NULL, 'admin', '2018-03-03 23:43:14'),
(155, 'bc6ec49c7bbb8041e7a86f3088c1702f', 'produits', 29, 'stock_min', '10', '100', 'admin', '2018-03-04 00:16:49'),
(156, '0b7ae68a37bc049659009bcbad661cdc', 'stock', 53, 'prix_vente', '2000', '20000', 'admin', '2018-03-04 00:17:26'),
(157, 'bbf11097098fe566e9b15ebae6e82340', 'proforma', 1, 'date_proforma', '04-03-2018', '2018-03-04', 'admin', '2018-03-04 00:18:54'),
(158, '838c7ae3cdb884b836086d9fbdd9e28d', 'devis', 58, 'date_devis', '04-03-2018', '2018-03-04', 'admin', '2018-03-04 00:20:58'),
(159, '838c7ae3cdb884b836086d9fbdd9e28d', 'devis', 58, 'valeur_remise', '0', '0.00', 'admin', '2018-03-04 00:20:58'),
(160, 'ee9d18b9730ff3794b5f0399ce9d3d94', 'clients', 44, 'denomination', 'Test', 'Test2018', 'admin', '2018-03-04 11:57:16'),
(161, 'ee9d18b9730ff3794b5f0399ce9d3d94', 'clients', 44, 'tel', NULL, '66324513', 'admin', '2018-03-04 11:57:16'),
(162, '30bd60c1a1c91598d1d9bf2d77cef809', 'commerciaux', 2, 'etat', '0', '1', 'admin', '2018-03-04 12:04:13'),
(163, '5b3b8c8c7ea0417e980c5627deaee370', 'commerciaux', 2, 'etat', '1', '0', 'admin', '2018-03-04 12:04:20'),
(164, '58b62abf884f76b05a6fd63bdd60219b', 'commerciaux', 2, 'etat', '0', '1', 'admin', '2018-03-04 12:05:03'),
(165, '5b02fd753324002e7df55d284b2bdafc', 'contrats', 79, 'idtype_echeance', '3', '1', 'admin', '2018-03-04 13:32:52'),
(166, '53cc977822486d067db8d36569952398', 'contrats', 79, 'idtype_echeance', '1', '5', 'admin', '2018-03-04 13:33:14'),
(167, 'f8ff49ba95494f8ec580f685ed11425d', 'contrats', 79, 'idtype_echeance', '5', '3', 'admin', '2018-03-04 13:34:18'),
(168, '94e4f6b01e29360de05d4a4e37d99c46', 'echeances_contrat', 78, 'montant', '778800', '788000', 'admin', '2018-03-04 14:09:25'),
(169, '94e4f6b01e29360de05d4a4e37d99c46', 'echeances_contrat', 78, 'commentaire', NULL, 'ok', 'admin', '2018-03-04 14:09:25'),
(170, '56f1254a32aa97e9fbc4bd87bcef3568', 'fournisseurs', 34, 'denomination', 'fournisseur', 'fournisseuro', 'admin', '2018-03-04 14:22:58'),
(171, 'ffc70fa1a4c7a2342305305f8d7c0ba4', 'clients', 45, 'tva', 'Oui', NULL, 'admin', '2018-03-04 19:00:20'),
(172, '25f2cf5eecc52ce52b9a3a5f81a34be7', 'clients', 45, 'tva', 'Oui', 'O', 'admin', '2018-03-04 19:10:07'),
(173, '73f4f6d9df9586e6ea5f43b9b218d55e', 'clients', 47, 'tva', 'Non', 'N', 'admin', '2018-03-04 19:16:52'),
(174, 'f7ae03443678b0cdc3a7b28a38c2cbb4', 'echeances_contrat', 89, 'date_echeance', '2018-03-04', '2018-05-09', 'admin', '2018-03-04 21:24:54'),
(175, 'f7ae03443678b0cdc3a7b28a38c2cbb4', 'echeances_contrat', 89, 'date_debut', '2018-03-04', '2018-05-09', 'admin', '2018-03-04 21:24:54'),
(176, 'fdbb2d60bb85e29dc58eae3b6830830d', 'echeances_contrat', 107, 'date_echeance', '2018-03-04', '2018-09-04', 'admin', '2018-03-04 21:58:55'),
(177, 'fdbb2d60bb85e29dc58eae3b6830830d', 'echeances_contrat', 107, 'date_debut', '2018-03-04', '2018-09-04', 'admin', '2018-03-04 21:58:55'),
(178, '4fadde64fde4137e0c3f73673fc87ba0', 'echeances_contrat', 110, 'date_echeance', '2018-03-04', '2018-10-04', 'admin', '2018-03-04 22:02:32'),
(179, '4fadde64fde4137e0c3f73673fc87ba0', 'echeances_contrat', 110, 'date_debut', '2018-03-04', '2018-10-04', 'admin', '2018-03-04 22:02:32'),
(180, 'bc321826a4d12ba18360c033bbdaef20', 'contrats', 94, 'date_contrat', '2018-03-04', '2018-03-05', 'admin', '2018-03-04 23:17:03'),
(181, 'bcc2dc5d8707011680ce94567c6cf907', 'contrats', 97, 'date_fin', '2018-06-05', '2018-08-05', 'admin', '2018-04-06 20:11:11'),
(182, 'b7042a2b4e6013cead874aa0318a4382', 'tickets', 15, 'etat', '1', '2', 'admin', '2018-04-23 18:36:45'),
(183, 'e82b719ecfa1f4db2cfaa1d10bf367f4', 'tickets', 14, 'id_technicien', NULL, '2', 'admin', '2018-04-23 18:39:56'),
(184, 'e82b719ecfa1f4db2cfaa1d10bf367f4', 'tickets', 14, 'date_affectation', NULL, '2018-04-23', 'admin', '2018-04-23 18:39:56'),
(185, '4ca4917a168d477736cfe729e4a69c9b', 'tickets', 14, 'etat', '0', '1', 'admin', '2018-04-23 18:39:56'),
(186, '39a801b0a3985ab4a7a015b906d5d89c', 'tickets', 13, 'etat', '0', '2', 'admin', '2018-04-23 21:40:37'),
(187, '331baf1c97b05e2472809e921d761b4e', 'entrepots', 1, 'libelle', 'Entrepot des vsat', 'Entrepôt des vsat', 'admin', '2018-04-25 18:51:00'),
(188, 'a2326bd8b7ce87f45ef25001d2654d07', 'entrepots', 3, 'etat', '0', '1', 'admin', '2018-04-25 19:12:22'),
(189, 'caf4c4ed6e49a3b19ee4cecc547ac9a2', 'entrepots', 3, 'etat', '0', '1', 'admin', '2018-04-25 19:13:19'),
(190, '3369affae08ebcadce754b359f20eed2', 'entrepots', 3, 'etat', '0', '1', 'admin', '2018-04-25 19:16:51'),
(191, 'b1bc114df4b870c8b5130b9cbea9203d', 'entrepots', 3, 'etat', '1', '0', 'admin', '2018-04-25 19:19:33'),
(192, '2cf974194ddfca033920675a571af0e0', 'entrepots', 3, 'etat', '0', '1', 'admin', '2018-04-25 19:19:49'),
(193, '0c2cfaf506b85a2cf2d6295906db8a92', 'tickets', 13, 'id_technicien', NULL, '2', 'admin', '2018-04-29 11:36:04'),
(194, '0c2cfaf506b85a2cf2d6295906db8a92', 'tickets', 13, 'date_affectation', NULL, '2018-04-29', 'admin', '2018-04-29 11:36:04'),
(195, '5fc57acd007573b02b6a528ef18bd313', 'tickets', 13, 'etat', '0', '1', 'admin', '2018-04-29 11:36:04'),
(196, 'd91c316973b65cc9e5348979f32438aa', 'echeances_contrat', 135, 'montant', '100000', '5000000', 'admin', '2018-04-29 12:12:01'),
(197, '3df04f2181be10fa8e77233de1e46703', 'echeances_contrat', 136, 'montant', '100000', '5000000', 'admin', '2018-04-29 12:12:10'),
(198, 'e5be1c52778122f2b6ae5b1cecbc322f', 'echeances_contrat', 135, 'montant', '5000000', '500000', 'admin', '2018-04-29 12:12:29'),
(199, '0edd6bdf45c07014ba96e447792e458b', 'echeances_contrat', 136, 'montant', '5000000', '500000', 'admin', '2018-04-29 12:12:37'),
(200, '3b2bb31754427493413b7cad44b9bfc0', 'echeances_contrat', 135, 'montant', '500000', '600000', 'admin', '2018-04-29 12:18:11'),
(201, 'd4fe85cb2702e6cb4788b0d68b3c8cec', 'echeances_contrat', 135, 'montant', '600000', '500000', 'admin', '2018-04-29 12:18:21'),
(202, '867d0e9d01f84e5b3a382a29d77e3e2c', 'echeances_contrat', 140, 'montant', '9000000', '900000', 'admin', '2018-04-29 18:33:28');

-- --------------------------------------------------------

--
-- Structure de la table `factures`
--

CREATE TABLE IF NOT EXISTS `factures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reference` varchar(20) DEFAULT NULL,
  `base_fact` char(1) DEFAULT NULL COMMENT 'C/D/B Contrat/Devis/BL',
  `type_remise` varchar(1) DEFAULT 'P' COMMENT 'type de remise',
  `valeur_remise` double DEFAULT '0' COMMENT 'Valeur de la remise',
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
  `id_echeance` int(11) DEFAULT NULL COMMENT 'Id echeances_contrat',
  `iddevis` int(11) DEFAULT NULL COMMENT 'Devis',
  `idbl` int(11) DEFAULT NULL COMMENT 'Bon de livraison',
  `date_facture` date DEFAULT NULL,
  `du` date DEFAULT NULL COMMENT 'debut periode facture',
  `au` date DEFAULT NULL COMMENT 'fin periode facture',
  `etat` int(11) DEFAULT '0',
  `creusr` int(11) DEFAULT NULL,
  `credat` datetime DEFAULT CURRENT_TIMESTAMP,
  `updusr` int(11) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_contrat` (`idcontrat`),
  KEY `fk_devis` (`iddevis`),
  KEY `fk_echeance` (`id_echeance`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=139 ;

--
-- Contenu de la table `factures`
--

INSERT INTO `factures` (`id`, `reference`, `base_fact`, `type_remise`, `valeur_remise`, `total_ht`, `total_tva`, `total_ttc_initial`, `total_ttc`, `total_paye`, `reste`, `client`, `projet`, `ref_bc`, `tva`, `idcontrat`, `id_echeance`, `iddevis`, `idbl`, `date_facture`, `du`, `au`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(115, 'GT-FCT-0001/2018', 'C', 'P', 0, 1050000, 0, 1050000, 1050000, 0, 1050000, 'DCT', NULL, '', NULL, 74, NULL, NULL, NULL, '2017-07-27', '2017-07-27', '2018-01-26', 2, 1, '2018-01-27 15:39:46', NULL, NULL),
(116, 'GT-FCT-0002/2018', 'C', 'P', 0, 1050000, 0, 1050000, 1050000, 0, 1050000, 'DCT', NULL, '', NULL, 74, NULL, NULL, NULL, '2018-01-27', '2018-01-27', '2018-07-26', 0, 1, '2018-01-27 15:46:45', NULL, NULL),
(117, 'GT-FCT-0003/2018', 'D', 'P', 0, 1232000, 221760, 1453760, 1453760, 606000, 847760, 'DCT', 'PROJ', 'Ref', NULL, NULL, NULL, 54, NULL, '2018-02-04', NULL, NULL, 3, 1, '2018-02-04 17:32:00', NULL, NULL),
(118, 'GT-FCT-0004/2018', 'C', 'P', 0, 1100000, 198000, 1298000, 1298000, 0, 1298000, 'DCT', 'PROJ', 'ok', NULL, 75, NULL, NULL, NULL, '2018-02-04', '2018-02-04', '2018-03-03', 1, 1, '2018-02-04 17:41:05', NULL, NULL),
(119, 'GT-FCT-0005/2018', 'C', 'P', 0, NULL, NULL, 1000000, 1000000, 0, 1000000, 'DCT', NULL, 'tfo', NULL, 76, NULL, NULL, NULL, '2018-02-25', '2018-02-25', '2018-02-25', 0, 1, '2018-02-25 21:04:09', NULL, NULL),
(120, 'GT-FCT-0006/2018', 'C', 'P', 0, 13200000, 2376000, 15576000, 15576000, 0, 15576000, 'GSI', 'TEST', 'OK', NULL, 77, 51, NULL, NULL, '2018-03-02', '2018-02-28', '2019-02-27', 0, 1, '2018-03-02 22:16:49', NULL, NULL),
(121, 'GT-FCT-0007/2018', 'C', 'P', 0, 13200000, 2376000, 15576000, 15576000, 0, 15576000, 'GSI', 'TEST', 'OK', NULL, 77, 52, NULL, NULL, '2018-03-02', '2019-02-28', '2020-02-27', 0, 1, '2018-03-02 22:25:12', NULL, NULL),
(126, 'GT-FCT-0008/2018', 'C', 'P', 0, 11880000, 2138400, 14018400, 14018400, 1000000, 13018400, 'DCT', 'PROJ', 'OK', NULL, 78, 55, NULL, NULL, '2018-03-04', '2018-01-01', '2018-12-31', 3, 1, '2018-03-04 01:59:24', NULL, NULL),
(128, 'GT-FCT-0010/2018', 'D', 'P', 0, 150000, 27000, 177000, 177000, 0, 177000, 'DCT', 'test', 'okk', NULL, NULL, NULL, 60, NULL, '2018-03-04', NULL, NULL, 0, 1, '2018-03-04 02:10:53', NULL, NULL),
(129, 'GT-FCT-0011/2018', 'D', 'P', 0, 1820000, 327600, 2147600, 2147600, 0, 2147600, '50FED', 'TEE', 'ok', NULL, NULL, NULL, 59, NULL, '2018-03-04', NULL, NULL, 0, 1, '2018-03-04 03:12:13', NULL, NULL),
(130, 'GT-FCT-0012/2018', 'D', 'P', 0, 1820000, 327600, 2147600, 1147600, 0, 1147600, '50FED', 'TEE', 'ok', NULL, NULL, NULL, 59, NULL, '2018-03-04', NULL, NULL, 0, 1, '2018-03-04 03:16:44', NULL, NULL),
(131, 'GT-FCT-0013/2018', 'D', 'P', 0, 9625000, 1732500, 11357500, 9407500, 9407500, 0, 'Test2018', NULL, '', NULL, NULL, NULL, 61, NULL, '2018-03-04', NULL, NULL, 4, 1, '2018-03-04 12:21:33', NULL, NULL),
(132, 'GT-FCT-0014/2018', 'C', 'P', 0, 3300000, 594000, 3894000, 3894000, 0, 3894000, 'Test2018', NULL, '', NULL, 79, 68, NULL, NULL, '2018-03-04', '2018-01-01', '2018-03-31', 0, 1, '2018-03-04 13:35:22', NULL, NULL),
(133, 'GT-FCT-0015/2018', 'D', 'P', 0, 9625000, 1732500, 11357500, 11357500, 0, 11357500, 'Test2018', NULL, 'ok', NULL, NULL, NULL, 61, NULL, '2018-04-06', NULL, NULL, 0, 1, '2018-04-06 20:05:17', NULL, NULL),
(135, 'GT-FCT-0016/2018', 'C', 'P', 0, 2200000, 396000, 2596000, 2596000, 0, 2596000, 'Test2018', NULL, '', NULL, 97, 123, NULL, NULL, '2018-04-06', '2018-04-06', '2018-06-05', 0, 1, '2018-04-06 21:38:11', NULL, NULL),
(136, 'GT-FCT-0017/2018', 'C', 'P', 0, 2200000, 396000, 2596000, 2596000, 0, 2596000, 'Test2018', NULL, '', NULL, 97, 124, NULL, NULL, '2018-04-06', '2018-06-06', '2018-08-05', 0, 1, '2018-04-06 21:40:39', NULL, NULL),
(137, 'GT-FCT-0018/2018', 'D', 'P', 0, 9625000, 1732500, 11357500, 11357500, 0, 11357500, 'Test2018', NULL, 'OKK', NULL, NULL, NULL, 61, NULL, '2018-04-29', NULL, NULL, 0, 1, '2018-04-29 09:54:54', NULL, NULL),
(138, 'GT-FCT-0019/2018', 'D', 'P', 0, 825000, 148500, 973500, 973500, 0, 973500, 'DCT', 'PROJ', 'OKK', NULL, NULL, NULL, 65, NULL, '2018-04-29', NULL, NULL, 0, 1, '2018-04-29 09:55:39', NULL, NULL);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Table recovery MDP user' AUTO_INCREMENT=2 ;

--
-- Contenu de la table `forgot`
--

INSERT INTO `forgot` (`token`, `user`, `etat`, `dat`, `expir`, `id`, `ip`) VALUES
('c15b7f525c0451bc9fdcc379aca9e3d7', 19, 0, '2017-10-11 00:49:02', '2017-10-13 00:49:02', 1, '197.159.16.2');

-- --------------------------------------------------------

--
-- Structure de la table `fournisseurs`
--

CREATE TABLE IF NOT EXISTS `fournisseurs` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `reference` varchar(20) NOT NULL COMMENT 'Code fournisseur',
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
  UNIQUE KEY `unique_code` (`reference`),
  UNIQUE KEY `unique_denomination` (`denomination`),
  KEY `fk_client_pays` (`id_pays`),
  KEY `fk_client_devise` (`id_devise`),
  KEY `fk_ville` (`id_ville`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=35 ;

--
-- Contenu de la table `fournisseurs`
--

INSERT INTO `fournisseurs` (`id`, `reference`, `denomination`, `r_social`, `r_commerce`, `nif`, `nom`, `prenom`, `civilite`, `adresse`, `id_pays`, `id_ville`, `tel`, `fax`, `bp`, `email`, `rib`, `id_devise`, `pj`, `pj_photo`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(33, 'GT_FRN-0001/2017', 'test', 'okk', NULL, NULL, NULL, NULL, 'Femme', 'add', 242, NULL, '044444444444444444444', NULL, NULL, 'em@em', NULL, NULL, 535, 536, 1, 1, '2017-10-18 22:52:44', 1, '2017-10-20 00:01:24'),
(34, 'GT_FRN-0001/2018', 'fournisseuro', 'rs', NULL, NULL, 'test', 'testtt', 'Femme', 'jezfjlfjlrg', 242, NULL, '03333333333', NULL, NULL, 'em@em', NULL, NULL, NULL, NULL, 1, 1, '2018-03-04 15:22:45', 1, '2018-03-04 15:23:03');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Table Systeme Modules' AUTO_INCREMENT=142 ;

--
-- Contenu de la table `modul`
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
(126, 'users', 'Utilisateurs', 'users', 'users_sys', 'user', NULL, 0, 0, '[-1-2-3-5-]'),
(127, 'proforma', 'Gestion Proforma', 'vente/submodul/proforma', 'proforma', 'proforma', 'vente', 2, 0, '[-1-2-3-5-4-]'),
(128, 'produits', 'Gestion des produits', 'produits', 'produits, ref_unites_vente, ref_categories_produits, ref_types_produits', 'produits', NULL, 0, 0, '[-1-2-3-]'),
(130, 'commerciale', 'Commerciale', 'commerciale/main', 'commerciaux', 'commerciale', NULL, 0, 0, '[-1-]'),
(131, 'contrats', 'Abonnements', 'vente/submodul/contrats', 'contrats', 'contrats', 'vente', 2, 0, '[-1-2-3-5-]'),
(133, 'factures', 'Gestion des factures', 'factures/main', 'factures', 'factures', NULL, 0, 0, '[-1-2-3-5-]'),
(138, 'stock', 'Gestion de Stock', 'stock/main', 'stock', 'stock', NULL, 0, 0, '[-1-]'),
(139, 'entrepots', 'Gestion des Entrepôts', 'stock/submodul/entrepots', 'entrepots', 'entrepots', 'stock', 2, 0, '[-1-]'),
(140, 'mouvements_stock', 'Mouvements de Stock', 'stock/submodul/mouvements_stock', 'stock', 'mouvements_stock', 'stock', 2, 0, '[-1-]'),
(141, 'tickets', 'Gestion Tickets', 'tickets/main', 'tickets', 'tickets', NULL, 0, 0, '[-1-5-]');

-- --------------------------------------------------------

--
-- Structure de la table `produits`
--

CREATE TABLE IF NOT EXISTS `produits` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `reference` varchar(20) DEFAULT NULL COMMENT 'Reference produit',
  `designation` varchar(100) DEFAULT NULL COMMENT 'DÃ©signation',
  `stock_min` int(11) DEFAULT NULL COMMENT 'Stock minimal',
  `id_entrepot` int(11) DEFAULT NULL COMMENT 'Entrepôt',
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
  KEY `fk_produit_type` (`idtype`),
  KEY `fk_produit_entrepots` (`id_entrepot`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=33 ;

--
-- Contenu de la table `produits`
--

INSERT INTO `produits` (`id`, `reference`, `designation`, `stock_min`, `id_entrepot`, `idcategorie`, `iduv`, `idtype`, `prix_vente`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(1, 'GT_PRD-0008', 'iDIRECT EVOLUTION X6', 1, 1, 8, 6, 1, 750000, 1, 1, '2018-03-04 12:58:42', 1, '2018-03-04 12:58:55'),
(22, 'GT-PRD-1/2017', 'LNB PLL bande C', 1, 1, 9, 6, 1, 150000, 1, 19, '2017-10-11 10:47:16', 22, '2017-10-16 23:09:25'),
(23, 'GT-PRD-2/2017', 'Modem iDirect Evolution X3', 1, 1, 8, 6, 1, 1120000, 1, 19, '2017-10-11 10:47:51', 19, '2017-10-11 12:57:36'),
(24, 'GT-PRD-3/2017', 'Antenne VSAT 1.2m bande Ku Skyware Global I', 1, 1, 10, 6, 1, 700000, 1, 19, '2017-10-11 10:48:50', 19, '2017-10-11 12:57:45'),
(25, 'GT-PRD-4/2017', 'Connexion  Vsat 1 Mo', NULL, 1, 11, 2, 3, 1000000, 1, 19, '2017-10-11 10:59:12', 19, '2017-10-11 11:33:21'),
(26, 'GT-PRD-5/2017', 'Installation Site', NULL, 1, 12, 7, 2, 50000, 1, 19, '2017-10-11 11:02:47', 19, '2017-10-11 11:32:59'),
(28, 'GT-PRD-6/2017', '1024/256kbps Astra4A', 0, 1, 13, 2, 3, 175000, 1, 24, '2017-10-17 12:53:55', 24, '2017-10-17 12:55:57'),
(29, 'GT_PRD-0007', 'test', 100, 1, 11, 3, 3, 20000, 1, 1, '2018-03-04 01:16:42', 1, '2018-03-04 01:16:54'),
(30, 'GT_PRD-0008', 'iDIRECT EVOLUTION X5', 1, 1, 8, 6, 1, 750000, 1, 1, '2018-03-04 12:58:42', 1, '2018-03-04 12:58:55'),
(32, 'GT_PRD-0009', 'test entrepot', 12, 1, 13, 5, 1, NULL, 1, 1, '2018-04-25 22:33:06', 1, '2018-04-25 22:33:37');

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
  `commission` double DEFAULT NULL COMMENT 'commission(%) du commercial',
  `total_commission` double DEFAULT NULL COMMENT 'prix total de la commssion',
  `date_proforma` date DEFAULT NULL,
  `type_remise` varchar(1) DEFAULT 'P' COMMENT 'type de remise',
  `valeur_remise` double DEFAULT '0' COMMENT 'Valeur de la remise',
  `totalht` double DEFAULT '0' COMMENT 'total ht des articles',
  `totalttc` double unsigned DEFAULT '0' COMMENT 'total ttc des articles',
  `totaltva` double DEFAULT '0' COMMENT 'total tva des articles',
  `vie` int(3) NOT NULL COMMENT 'Durée de vie',
  `claus_comercial` text COMMENT 'clauses commercial devis',
  `etat` int(11) DEFAULT '0' COMMENT 'Etat devis defaut 0',
  `proforma_pdf` int(11) DEFAULT NULL COMMENT 'Generated pdf (archive table)',
  `creusr` int(11) DEFAULT NULL,
  `credat` datetime DEFAULT NULL,
  `updusr` int(11) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_id_client` (`id_client`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=40 ;

--
-- Contenu de la table `proforma`
--

INSERT INTO `proforma` (`id`, `tkn_frm`, `reference`, `id_client`, `tva`, `id_commercial`, `commission`, `total_commission`, `date_proforma`, `type_remise`, `valeur_remise`, `totalht`, `totalttc`, `totaltva`, `vie`, `claus_comercial`, `etat`, `proforma_pdf`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(31, 'fe2afb3dc9e5675281199526c97bb4cf', 'GT_PROF-0002/2017', 26, 'O', 23, NULL, NULL, '2017-10-16', 'P', 0, 0, 0, 0, 60, '<p>RAS<br></p>', 2, 519, 23, '2017-10-16 22:22:44', NULL, NULL),
(32, '6d50c057765b9b9966330a3fedef1836', 'GT_PROF-0001/2017', 25, 'N', 2, NULL, NULL, '2017-10-16', 'P', 0, 0, 0, 0, 60, '<p>RAS<br></p>', 2, 520, 23, '2017-10-16 22:26:25', 2, '2017-10-16 22:45:13'),
(34, '45861c7a059c1622cbe15382631f1ac6', 'GT_PROF-0002/2017', 27, 'O', 24, NULL, NULL, '2017-10-17', 'P', 0, 0, 0, 0, 30, '<p>Le délais de livraison est de 15 Jours <br></p>', 1, NULL, 24, '2017-10-17 14:37:33', NULL, NULL),
(35, '149afd94e32f9699809f4a2ba2d89516', 'GT_PROF-0001/2018', 27, 'O', 1, NULL, NULL, '2018-01-22', 'P', 0, 0, 0, 0, 30, 'Paiement 100% à la commande', 0, NULL, 1, '2018-01-22 00:17:24', NULL, NULL),
(37, 'f0630d71d9940d7f4d24b48b87102d0a', 'GT_PROF-0002/2018', 27, 'O', 1, 20, NULL, '2018-01-23', 'P', 0, 0, 0, 0, 30, 'Paiement 100% à la commande', 0, NULL, 1, '2018-01-23 00:24:52', NULL, NULL),
(38, '26dbdeef9108faf5bd5304214a0465a6', 'GT_PROF-0003/2018', 27, 'O', 1, 10, NULL, '2018-01-23', 'P', 0, 0, 0, 0, 30, 'Paiement 100% à la commande', 1, NULL, 1, '2018-01-23 00:25:49', 1, '2018-01-23 22:45:20'),
(39, 'a920d82f8764a2ce940de7a94a9f6278', 'GT_PROF-0004/2018', 37, 'N', 1, 0, NULL, '2018-03-04', 'P', 0, 0, 0, 0, 30, 'Paiement 100% à la commande', 2, 556, 1, '2018-03-04 01:18:43', 1, '2018-03-04 00:18:54');

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `qte_actuel`
--
CREATE TABLE IF NOT EXISTS `qte_actuel` (
`id_produit` int(11)
,`qte_act` decimal(32,0)
);
-- --------------------------------------------------------

--
-- Structure de la table `ref_categories_produits`
--

CREATE TABLE IF NOT EXISTS `ref_categories_produits` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `categorie_produit` varchar(100) NOT NULL COMMENT 'CatÃ©gorie de produits',
  `type_produit` int(11) DEFAULT NULL,
  `etat` int(11) DEFAULT '0',
  `creusr` int(11) DEFAULT NULL,
  `credat` datetime DEFAULT CURRENT_TIMESTAMP,
  `updusr` int(11) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_type_categorie` (`type_produit`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Contenu de la table `ref_categories_produits`
--

INSERT INTO `ref_categories_produits` (`id`, `categorie_produit`, `type_produit`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(8, 'Modems', 1, 1, 19, '2017-10-11 10:25:40', 24, '2017-10-17 12:48:13'),
(9, 'Lnb', 1, 1, 19, '2017-10-11 10:25:54', 19, '2017-10-11 10:45:01'),
(10, 'Antennes', 1, 1, 19, '2017-10-11 10:26:26', 19, '2017-10-11 10:45:18'),
(11, 'Accès à internet', 3, 1, 19, '2017-10-11 10:32:38', 19, '2017-10-11 10:45:22'),
(12, 'Installation', 2, 1, 19, '2017-10-11 11:00:19', 19, '2017-10-11 11:00:27'),
(13, 'Mini VSAT', 1, 1, 24, '2017-10-17 12:48:52', 24, '2017-10-17 12:49:08');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `ref_departement`
--

INSERT INTO `ref_departement` (`id`, `departement`, `id_region`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(1, 'N''djamena Centre', 24, 1, 'admin', '2017-07-09 17:38:55', 'admin', '2017-09-15 17:57:39'),
(2, 'Paris 1', 31, 1, 'admin', '2017-10-19 22:31:53', 'admin', '2017-10-19 22:31:57');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=32 ;

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
(30, 242, 'Lacc', 1, '1', '2017-04-02 13:50:53', '1', '2017-07-09 18:41:09'),
(31, 75, 'Paris centre', 1, '1', '2017-10-19 22:31:11', '1', '2017-10-19 22:36:49');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Contenu de la table `ref_type_echeance`
--

INSERT INTO `ref_type_echeance` (`id`, `type_echeance`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(1, 'Annuelle', 1, '1', '2017-09-15 20:05:43', '1', '2017-09-15 20:17:12'),
(2, 'Mensuelle', 1, '1', '2017-09-15 20:17:02', '1', '2017-09-15 20:17:16'),
(3, 'Trimestrielle', 1, '1', '2017-09-15 20:17:08', '1', '2017-09-15 20:17:14'),
(4, 'Autres', 1, '1', '2017-09-16 12:58:33', NULL, NULL),
(5, 'Simestrielle', 1, '1', '2017-09-30 01:47:47', NULL, NULL),
(6, 'Bimensuelle', 1, '1', '2018-04-05 19:39:21', NULL, NULL);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Contenu de la table `ref_unites_vente`
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=45 ;

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
(43, 1, 'N''djamena', '', '', 1, '', '2017-03-19 23:45:09', NULL, NULL),
(44, 2, 'Paris', '11111', '333333333333', 1, 'admin', '2017-10-19 22:32:16', 'admin', '2017-10-19 22:32:20');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Table store rules for each user for each App and action' AUTO_INCREMENT=41685 ;

--
-- Contenu de la table `rules_action`
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
(28941, 716, 'b7b3a09fdd73a5b0a3e5ed8a2828f548', 3, 20, 1133, 'Désactiver l''utilisateur', 1, '1', '2017-10-13 20:12:39'),
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
(29869, 716, 'b7b3a09fdd73a5b0a3e5ed8a2828f548', 2, 2, 1133, 'Désactiver l''utilisateur', 1, '2', '2017-10-16 21:30:25'),
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
(29929, 716, 'b7b3a09fdd73a5b0a3e5ed8a2828f548', 5, 19, 1133, 'Désactiver l''utilisateur', 1, '20', '2017-10-16 21:31:18'),
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
(30040, 543, 'de6285d9c0027ff8bccdf2af385ac337', 1, 22, 812, 'Editer paramètre', 0, NULL, '2017-10-16 21:34:25');
INSERT INTO `rules_action` (`id`, `appid`, `idf`, `service`, `userid`, `action_id`, `descrip`, `type`, `creusr`, `credat`) VALUES
(30041, 544, '82f83d9d3d30fdef00d4c3ef96f0f899', 1, 22, 813, 'Ajouter Paramètre', 0, NULL, '2017-10-16 21:34:25'),
(30042, 545, 'f0e54f346e9dcfdff65274709ce2c8ca', 1, 22, 814, 'Editer paramètre', 0, NULL, '2017-10-16 21:34:25'),
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
(30195, 716, 'b7b3a09fdd73a5b0a3e5ed8a2828f548', 1, 22, 1133, 'Désactiver l''utilisateur', 1, NULL, '2017-10-16 21:34:25'),
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
(31223, 716, 'b7b3a09fdd73a5b0a3e5ed8a2828f548', 1, 23, 1133, 'Désactiver l''utilisateur', 1, '20', '2017-10-16 21:36:40'),
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
(31282, 733, '7779e98d2111faedf458f7aeb548294e', 2, 2, 1162, 'Supprimer produit', 0, '22', '2017-10-16 21:39:54'),
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
(32203, 655, '28e267a2a0647d4cb37b18abb1e7d051', 3, 24, 1008, 'Voir détails', 0, '1', '2017-10-17 13:58:52');
INSERT INTO `rules_action` (`id`, `appid`, `idf`, `service`, `userid`, `action_id`, `descrip`, `type`, `creusr`, `credat`) VALUES
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
(32260, 508, '83b693fe35a1be29edafe4f6170641aa', 3, 24, 736, 'Détails Fournisseur', 0, '1', '2017-10-17 13:58:52'),
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
(32363, 716, 'b7b3a09fdd73a5b0a3e5ed8a2828f548', 3, 24, 1133, 'Désactiver l''utilisateur', 1, '1', '2017-10-17 13:58:52'),
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
(32376, 423, 'fe03a68d17c62ff2c27329573a1b3550', 3, 24, 601, 'Valider Ville', 0, '1', '2017-10-17 13:58:52'),
(35491, 768, '899d40c8f22d4f7a6f048366f1829787', 1, 22, 1237, 'Gestion des contrats', 0, '1', '2018-03-05 01:00:39'),
(35492, 768, '899d40c8f22d4f7a6f048366f1829787', 1, 23, 1237, 'Gestion des contrats', 0, '1', '2018-03-05 01:00:39'),
(35493, 768, '899d40c8f22d4f7a6f048366f1829787', 3, 24, 1237, 'Gestion des contrats', 0, '1', '2018-03-05 01:00:39'),
(35495, 768, '4aea0d5a7bdb0e2513897507947fc3de', 3, 24, 1238, 'Modifier  contrat', 0, '1', '2018-03-05 01:00:39'),
(35497, 768, '4ccf7c3c72dfa25157ab01762069929e', 3, 24, 1239, 'Détail  contrat', 0, '1', '2018-03-05 01:00:39'),
(35499, 768, '18c5260f189a488c59134c1d53270dae', 3, 24, 1240, 'Valider  contrat', 0, '1', '2018-03-05 01:00:39'),
(35501, 768, '6ca83d9c6c0b229446da30b60b74031a', 3, 24, 1241, 'Détails  Contrat', 0, '1', '2018-03-05 01:00:39'),
(35503, 768, '52eef475bfa2afb7eb065329a93b0b4c', 3, 24, 1242, 'Renouveler  Contrat', 0, '1', '2018-03-05 01:00:39'),
(35505, 768, 'b23939959d533fa68091fca749b691aa', 3, 24, 1243, 'Détails Contrat ', 0, '1', '2018-03-05 01:00:39'),
(35507, 768, 'b6cc6622e5874a5c0a04e2103d8a7dd0', 3, 24, 1244, ' Détails    Contrat', 0, '1', '2018-03-05 01:00:39'),
(35509, 768, 'c58a3038be080d0c6cdf89e0fd0a8c71', 3, 24, 1245, 'Détails  Contrat', 0, '1', '2018-03-05 01:00:39'),
(35511, 768, '656d41ad5452611636a5d9f966729e39', 1, 22, 1246, 'Renouveler Contrat', 0, '1', '2018-03-05 01:00:39'),
(35512, 768, '656d41ad5452611636a5d9f966729e39', 1, 23, 1246, 'Renouveler Contrat', 0, '1', '2018-03-05 01:00:39'),
(35513, 768, '656d41ad5452611636a5d9f966729e39', 3, 24, 1246, 'Renouveler Contrat', 0, '1', '2018-03-05 01:00:39'),
(35518, 769, '87f4c3ed4713c3bc9e3fef60a6649055', 1, 22, 1250, 'Ajouter contrat', 0, '1', '2018-03-05 01:00:39'),
(35519, 769, '87f4c3ed4713c3bc9e3fef60a6649055', 1, 23, 1250, 'Ajouter contrat', 0, '1', '2018-03-05 01:00:39'),
(35520, 769, '87f4c3ed4713c3bc9e3fef60a6649055', 3, 24, 1250, 'Ajouter contrat', 0, '1', '2018-03-05 01:00:39'),
(35522, 770, '9e49a431d9637544cefa2869fd7278b9', 1, 22, 1251, 'Modifier contrat', 0, '1', '2018-03-05 01:00:39'),
(35523, 770, '9e49a431d9637544cefa2869fd7278b9', 1, 23, 1251, 'Modifier contrat', 0, '1', '2018-03-05 01:00:39'),
(35524, 770, '9e49a431d9637544cefa2869fd7278b9', 3, 24, 1251, 'Modifier contrat', 0, '1', '2018-03-05 01:00:39'),
(35526, 771, '1e9395a182a44787e493bc038cd80bbf', 1, 22, 1252, 'Supprimer contrat', 0, '1', '2018-03-05 01:00:39'),
(35527, 771, '1e9395a182a44787e493bc038cd80bbf', 1, 23, 1252, 'Supprimer contrat', 0, '1', '2018-03-05 01:00:39'),
(35528, 771, '1e9395a182a44787e493bc038cd80bbf', 3, 24, 1252, 'Supprimer contrat', 0, '1', '2018-03-05 01:00:39'),
(35530, 772, '460d92834715b149c4db28e1643bd932', 1, 22, 1253, 'Valider contrat', 0, '1', '2018-03-05 01:00:39'),
(35531, 772, '460d92834715b149c4db28e1643bd932', 1, 23, 1253, 'Valider contrat', 0, '1', '2018-03-05 01:00:39'),
(35532, 772, '460d92834715b149c4db28e1643bd932', 3, 24, 1253, 'Valider contrat', 0, '1', '2018-03-05 01:00:39'),
(35534, 773, 'bbcf2879c2f8f60cfa55fa97c6e79268', 1, 22, 1254, 'Détail contrat', 0, '1', '2018-03-05 01:00:39'),
(35535, 773, 'bbcf2879c2f8f60cfa55fa97c6e79268', 1, 23, 1254, 'Détail contrat', 0, '1', '2018-03-05 01:00:39'),
(35536, 773, 'bbcf2879c2f8f60cfa55fa97c6e79268', 3, 24, 1254, 'Détail contrat', 0, '1', '2018-03-05 01:00:39'),
(35538, 774, 'fe058ccb890b25a54866be7f24a40363', 1, 22, 1255, 'Ajouter échéance ', 0, '1', '2018-03-05 01:00:39'),
(35539, 774, 'fe058ccb890b25a54866be7f24a40363', 1, 23, 1255, 'Ajouter échéance ', 0, '1', '2018-03-05 01:00:39'),
(35540, 774, 'fe058ccb890b25a54866be7f24a40363', 3, 24, 1255, 'Ajouter échéance ', 0, '1', '2018-03-05 01:00:39'),
(35542, 775, '36a248f56a6a80977e5c90a5c59f39d3', 1, 22, 1256, 'Modifier échéance contrat', 0, '1', '2018-03-05 01:00:39'),
(35543, 775, '36a248f56a6a80977e5c90a5c59f39d3', 1, 23, 1256, 'Modifier échéance contrat', 0, '1', '2018-03-05 01:00:39'),
(35544, 775, '36a248f56a6a80977e5c90a5c59f39d3', 3, 24, 1256, 'Modifier échéance contrat', 0, '1', '2018-03-05 01:00:39'),
(35546, 776, 'f0567980556249721f24f2fc88ebfed5', 1, 22, 1257, 'Renouveler Contrat', 0, '1', '2018-03-05 01:00:39'),
(35547, 776, 'f0567980556249721f24f2fc88ebfed5', 1, 23, 1257, 'Renouveler Contrat', 0, '1', '2018-03-05 01:00:39'),
(35548, 776, 'f0567980556249721f24f2fc88ebfed5', 3, 24, 1257, 'Renouveler Contrat', 0, '1', '2018-03-05 01:00:39'),
(36629, 796, '4c924acb9adc87d8389e8f9842a965c5', 1, 22, 1297, 'Gestion des factures', 0, '1', '2018-04-05 19:52:39'),
(36630, 796, '4c924acb9adc87d8389e8f9842a965c5', 1, 23, 1297, 'Gestion des factures', 0, '1', '2018-04-05 19:52:39'),
(36631, 796, '4c924acb9adc87d8389e8f9842a965c5', 3, 24, 1297, 'Gestion des factures', 0, '1', '2018-04-05 19:52:39'),
(36633, 796, '98a697ec628778765b25e02ba2929d38', 1, 22, 1298, 'Liste complément', 0, '1', '2018-04-05 19:52:39'),
(36634, 796, '98a697ec628778765b25e02ba2929d38', 1, 23, 1298, 'Liste complément', 0, '1', '2018-04-05 19:52:39'),
(36635, 796, '98a697ec628778765b25e02ba2929d38', 3, 24, 1298, 'Liste complément', 0, '1', '2018-04-05 19:52:39'),
(36637, 796, 'f8b20f7fec99b45b967a431d64b7f061', 1, 22, 1299, 'Liste encaissements', 0, '1', '2018-04-05 19:52:39'),
(36638, 796, 'f8b20f7fec99b45b967a431d64b7f061', 1, 23, 1299, 'Liste encaissements', 0, '1', '2018-04-05 19:52:39'),
(36639, 796, 'f8b20f7fec99b45b967a431d64b7f061', 3, 24, 1299, 'Liste encaissements', 0, '1', '2018-04-05 19:52:39'),
(36641, 796, '9a51fb5298e39a28af3ad6272fc51177', 1, 22, 1300, 'Valider facture', 0, '1', '2018-04-05 19:52:39'),
(36642, 796, '9a51fb5298e39a28af3ad6272fc51177', 1, 23, 1300, 'Valider facture', 0, '1', '2018-04-05 19:52:39'),
(36643, 796, '9a51fb5298e39a28af3ad6272fc51177', 3, 24, 1300, 'Valider facture', 0, '1', '2018-04-05 19:52:39'),
(36645, 796, '851f1d4c13f6025f69f5b9315321d350', 1, 22, 1301, 'Désactiver facture', 0, '1', '2018-04-05 19:52:39'),
(36646, 796, '851f1d4c13f6025f69f5b9315321d350', 1, 23, 1301, 'Désactiver facture', 0, '1', '2018-04-05 19:52:39'),
(36647, 796, '851f1d4c13f6025f69f5b9315321d350', 3, 24, 1301, 'Désactiver facture', 0, '1', '2018-04-05 19:52:39'),
(36649, 796, '5c79105956d28b5cac52f85784039919', 1, 22, 1302, 'Détail facture', 0, '1', '2018-04-05 19:52:39'),
(36650, 796, '5c79105956d28b5cac52f85784039919', 1, 23, 1302, 'Détail facture', 0, '1', '2018-04-05 19:52:39'),
(36651, 796, '5c79105956d28b5cac52f85784039919', 3, 24, 1302, 'Détail facture', 0, '1', '2018-04-05 19:52:39'),
(36653, 796, '7892721423af84a0b54e90250cf27ee3', 1, 22, 1303, 'Détails Facture', 0, '1', '2018-04-05 19:52:39'),
(36654, 796, '7892721423af84a0b54e90250cf27ee3', 1, 23, 1303, 'Détails Facture', 0, '1', '2018-04-05 19:52:39'),
(36655, 796, '7892721423af84a0b54e90250cf27ee3', 3, 24, 1303, 'Détails Facture', 0, '1', '2018-04-05 19:52:39'),
(36658, 796, '80a4b2643b95c2836e968411811d3c21', 1, 22, 1305, 'Détails facture', 0, '1', '2018-04-05 19:52:39'),
(36659, 796, '80a4b2643b95c2836e968411811d3c21', 1, 23, 1305, 'Détails facture', 0, '1', '2018-04-05 19:52:39'),
(36660, 796, '80a4b2643b95c2836e968411811d3c21', 3, 24, 1305, 'Détails facture', 0, '1', '2018-04-05 19:52:39'),
(36662, 796, '2f679be3c0d7b88529209f86745f9028', 1, 22, 1306, 'Détails facture', 0, '1', '2018-04-05 19:52:39'),
(36663, 796, '2f679be3c0d7b88529209f86745f9028', 1, 23, 1306, 'Détails facture', 0, '1', '2018-04-05 19:52:39'),
(36664, 796, '2f679be3c0d7b88529209f86745f9028', 3, 24, 1306, 'Détails facture', 0, '1', '2018-04-05 19:52:39'),
(36666, 796, '429558e9a1e899c11051ea5c9a4f7942', 1, 22, 1307, 'Détails facture', 0, '1', '2018-04-05 19:52:39'),
(36667, 796, '429558e9a1e899c11051ea5c9a4f7942', 1, 23, 1307, 'Détails facture', 0, '1', '2018-04-05 19:52:39'),
(36668, 796, '429558e9a1e899c11051ea5c9a4f7942', 3, 24, 1307, 'Détails facture', 0, '1', '2018-04-05 19:52:39'),
(36670, 796, '3acd11d8d74fb7e1ba8d5721e96f91bd', 1, 22, 1308, 'Liste encaissements', 0, '1', '2018-04-05 19:52:39'),
(36671, 796, '3acd11d8d74fb7e1ba8d5721e96f91bd', 1, 23, 1308, 'Liste encaissements', 0, '1', '2018-04-05 19:52:39'),
(36672, 796, '3acd11d8d74fb7e1ba8d5721e96f91bd', 3, 24, 1308, 'Liste encaissements', 0, '1', '2018-04-05 19:52:39'),
(36674, 797, '55c3c5d2d93143b315513b7401043c8b', 1, 22, 1309, 'complements', 0, '1', '2018-04-05 19:52:39'),
(36675, 797, '55c3c5d2d93143b315513b7401043c8b', 1, 23, 1309, 'complements', 0, '1', '2018-04-05 19:52:39'),
(36676, 797, '55c3c5d2d93143b315513b7401043c8b', 3, 24, 1309, 'complements', 0, '1', '2018-04-05 19:52:39'),
(36678, 797, 'dfc4772cc03cf0b92a47f54fc6a2326e', 1, 22, 1310, 'Modifier complément', 0, '1', '2018-04-05 19:52:39'),
(36679, 797, 'dfc4772cc03cf0b92a47f54fc6a2326e', 1, 23, 1310, 'Modifier complément', 0, '1', '2018-04-05 19:52:39'),
(36680, 797, 'dfc4772cc03cf0b92a47f54fc6a2326e', 3, 24, 1310, 'Modifier complément', 0, '1', '2018-04-05 19:52:39'),
(36682, 798, '03a18bdd5201e433a3c523a2b34d059a', 1, 22, 1311, 'Ajouter complément', 0, '1', '2018-04-05 19:52:39'),
(36683, 798, '03a18bdd5201e433a3c523a2b34d059a', 1, 23, 1311, 'Ajouter complément', 0, '1', '2018-04-05 19:52:39'),
(36684, 798, '03a18bdd5201e433a3c523a2b34d059a', 3, 24, 1311, 'Ajouter complément', 0, '1', '2018-04-05 19:52:39'),
(36686, 799, '88d9bc979cd1102eb8196e7f5e6042ca', 1, 22, 1312, 'Encaissement', 0, '1', '2018-04-05 19:52:39'),
(36687, 799, '88d9bc979cd1102eb8196e7f5e6042ca', 1, 23, 1312, 'Encaissement', 0, '1', '2018-04-05 19:52:39'),
(36688, 799, '88d9bc979cd1102eb8196e7f5e6042ca', 3, 24, 1312, 'Encaissement', 0, '1', '2018-04-05 19:52:39'),
(36690, 799, 'c690cc68f5257c0c225b8b8e6126ea56', 1, 22, 1313, 'Modifier encaissement', 0, '1', '2018-04-05 19:52:39'),
(36691, 799, 'c690cc68f5257c0c225b8b8e6126ea56', 1, 23, 1313, 'Modifier encaissement', 0, '1', '2018-04-05 19:52:39'),
(36692, 799, 'c690cc68f5257c0c225b8b8e6126ea56', 3, 24, 1313, 'Modifier encaissement', 0, '1', '2018-04-05 19:52:39'),
(36694, 799, '1dc06f602e8630f273d44aa2751b2127', 1, 22, 1314, 'Détails encaissement', 0, '1', '2018-04-05 19:52:39'),
(36695, 799, '1dc06f602e8630f273d44aa2751b2127', 1, 23, 1314, 'Détails encaissement', 0, '1', '2018-04-05 19:52:39'),
(36696, 799, '1dc06f602e8630f273d44aa2751b2127', 3, 24, 1314, 'Détails encaissement', 0, '1', '2018-04-05 19:52:39'),
(36700, 800, 'e4866b292dbc3c9c5d9cc37273a5b498', 1, 22, 1317, 'Ajouter encaissement', 0, '1', '2018-04-05 19:52:39'),
(36701, 800, 'e4866b292dbc3c9c5d9cc37273a5b498', 1, 23, 1317, 'Ajouter encaissement', 0, '1', '2018-04-05 19:52:39'),
(36702, 800, 'e4866b292dbc3c9c5d9cc37273a5b498', 3, 24, 1317, 'Ajouter encaissement', 0, '1', '2018-04-05 19:52:39'),
(36704, 801, '8665be10959f39df4f149962eb70041f', 1, 22, 1318, 'Modifier complément', 0, '1', '2018-04-05 19:52:39'),
(36705, 801, '8665be10959f39df4f149962eb70041f', 1, 23, 1318, 'Modifier complément', 0, '1', '2018-04-05 19:52:39'),
(36706, 801, '8665be10959f39df4f149962eb70041f', 3, 24, 1318, 'Modifier complément', 0, '1', '2018-04-05 19:52:39'),
(36708, 802, '585d411904bf7d9e83d21b2810ff1d6c', 1, 22, 1319, 'Modifier encaissement', 0, '1', '2018-04-05 19:52:39'),
(36709, 802, '585d411904bf7d9e83d21b2810ff1d6c', 1, 23, 1319, 'Modifier encaissement', 0, '1', '2018-04-05 19:52:39'),
(36710, 802, '585d411904bf7d9e83d21b2810ff1d6c', 3, 24, 1319, 'Modifier encaissement', 0, '1', '2018-04-05 19:52:39'),
(36712, 803, '8c8b058a4d030cdc8b49c9008abb2e92', 1, 22, 1320, 'Supprimer complément', 0, '1', '2018-04-05 19:52:39'),
(36713, 803, '8c8b058a4d030cdc8b49c9008abb2e92', 1, 23, 1320, 'Supprimer complément', 0, '1', '2018-04-05 19:52:39'),
(36714, 803, '8c8b058a4d030cdc8b49c9008abb2e92', 3, 24, 1320, 'Supprimer complément', 0, '1', '2018-04-05 19:52:39'),
(36716, 804, '6bf7d5180940f03567a5d711e8563ba4', 1, 22, 1321, 'Supprimer encaissement', 0, '1', '2018-04-05 19:52:39'),
(36717, 804, '6bf7d5180940f03567a5d711e8563ba4', 1, 23, 1321, 'Supprimer encaissement', 0, '1', '2018-04-05 19:52:39'),
(36718, 804, '6bf7d5180940f03567a5d711e8563ba4', 3, 24, 1321, 'Supprimer encaissement', 0, '1', '2018-04-05 19:52:39'),
(36720, 805, '256abad0ec8e3bc8ed1c0653ff177255', 1, 22, 1322, 'Valider facture', 0, '1', '2018-04-05 19:52:39'),
(36721, 805, '256abad0ec8e3bc8ed1c0653ff177255', 1, 23, 1322, 'Valider facture', 0, '1', '2018-04-05 19:52:39'),
(36722, 805, '256abad0ec8e3bc8ed1c0653ff177255', 3, 24, 1322, 'Valider facture', 0, '1', '2018-04-05 19:52:39'),
(36724, 806, 'b5dc5719c1f96df7334f371dcf51a5b6', 1, 22, 1323, 'Détail encaissement', 0, '1', '2018-04-05 19:52:39'),
(36725, 806, 'b5dc5719c1f96df7334f371dcf51a5b6', 1, 23, 1323, 'Détail encaissement', 0, '1', '2018-04-05 19:52:39'),
(36726, 806, 'b5dc5719c1f96df7334f371dcf51a5b6', 3, 24, 1323, 'Détail encaissement', 0, '1', '2018-04-05 19:52:39'),
(36728, 807, '16fbf6fdcbb72f863bcf7e4ef28d8e75', 1, 22, 1324, 'Détails facture', 0, '1', '2018-04-05 19:52:39'),
(36729, 807, '16fbf6fdcbb72f863bcf7e4ef28d8e75', 1, 23, 1324, 'Détails facture', 0, '1', '2018-04-05 19:52:39'),
(36730, 807, '16fbf6fdcbb72f863bcf7e4ef28d8e75', 3, 24, 1324, 'Détails facture', 0, '1', '2018-04-05 19:52:39'),
(36732, 808, '5efdeb41007109ca99f23f0756217827', 1, 22, 1325, 'Désactiver Facture', 0, '1', '2018-04-05 19:52:39'),
(36733, 808, '5efdeb41007109ca99f23f0756217827', 1, 23, 1325, 'Désactiver Facture', 0, '1', '2018-04-05 19:52:39'),
(36734, 808, '5efdeb41007109ca99f23f0756217827', 3, 24, 1325, 'Désactiver Facture', 0, '1', '2018-04-05 19:52:39'),
(41331, 455, 'e69f84a801ee1525f20f34e684688a9b', 1, 1, 652, 'Gestion des catégories de produits', 0, '1', '2018-04-29 17:47:07'),
(41332, 455, '90f6eba3e0ed223e73d250278cb445d5', 1, 1, 653, 'Modifier catégorie', 0, '1', '2018-04-29 17:47:07'),
(41333, 455, 'c62968a45ae9cfa8b127ac1b5573988a', 1, 1, 654, 'Valider catégorie', 0, '1', '2018-04-29 17:47:07'),
(41334, 455, '6f43a6bcbd293f958aff51953559104e', 1, 1, 655, 'Désactiver catégorie', 0, '1', '2018-04-29 17:47:07'),
(41335, 456, 'd26f5940e88a494c0eb65047aab9a17b', 1, 1, 656, 'Ajouter une catégorie', 0, '1', '2018-04-29 17:47:07'),
(41336, 457, '27957c6d0f6869d4d90287cd50b6dde9', 1, 1, 657, 'Modifier une catégorie', 0, '1', '2018-04-29 17:47:07'),
(41337, 458, '41b48dd567e4f79e35261a47b7bad751', 1, 1, 658, 'Valider une catégorie', 0, '1', '2018-04-29 17:47:07'),
(41338, 459, '90dc20c4d1ad7be7fac8ec34d5ac26b3', 1, 1, 659, 'Supprimer une catégorie', 0, '1', '2018-04-29 17:47:07'),
(41339, 333, '6edc543080c65eca3993445c295ff94b', 1, 1, 497, 'Gestion Catégorie Client', 0, '1', '2018-04-29 17:47:07'),
(41340, 333, '142a68a109abd0462ea44fcadffe56de', 1, 1, 506, 'Editer Catégorie Client', 0, '1', '2018-04-29 17:47:07'),
(41341, 333, '70df89fa2654d8b10d7fc7e75e178b7e', 1, 1, 507, 'Activer Catégorie Client', 0, '1', '2018-04-29 17:47:07'),
(41342, 333, '109e82d6db5721f63cd827e9fd224216', 1, 1, 508, 'Désactiver Catégorie Client', 0, '1', '2018-04-29 17:47:07'),
(41343, 334, 'a5c1bd0dfd87824ff0f57c6b1e1d2c3f', 1, 1, 498, 'Ajouter Catégorie Client', 1, '1', '2018-04-29 17:47:07'),
(41344, 335, '8d901f74dfd6ee3a8f44ebd0b83fbfae', 1, 1, 499, 'Editer Catégorie Client', 1, '1', '2018-04-29 17:47:07'),
(41345, 336, 'e87327563ce6b659780d6b2c9bf8ac77', 1, 1, 500, 'Supprimer Catégorie Client', 1, '1', '2018-04-29 17:47:07'),
(41346, 337, 'c955da8d244aac06ee7595d08de7d009', 1, 1, 501, 'Valider Catégorie Client', 1, '1', '2018-04-29 17:47:07'),
(41347, 394, 'f12fb1c50aedc49c3fa3dfa2bd297bd3', 1, 1, 553, 'Gestion Clients', 0, '1', '2018-04-29 17:47:07'),
(41348, 394, 'dd3d5980299911ea854af4fa6f2e7309', 1, 1, 554, 'Editer Client', 0, '1', '2018-04-29 17:47:07'),
(41349, 394, '3c5c04a20d49ad010557a64c8cdac1ce', 1, 1, 555, 'Valider Client', 0, '1', '2018-04-29 17:47:07'),
(41350, 394, '18ace52052f2551099ecaabf049ffaec', 1, 1, 556, 'Désactiver Client', 0, '1', '2018-04-29 17:47:07'),
(41351, 394, '493f9e55fc0340763e07514c1900685a', 1, 1, 557, 'Détails Client', 0, '1', '2018-04-29 17:47:07'),
(41352, 394, '03b4f949b088e41fc9a1f3f23b7906a8', 1, 1, 558, 'Détails  Client', 0, '1', '2018-04-29 17:47:07'),
(41353, 395, '2b9d8bb8f752d1c35fb681c33e38b42b', 1, 1, 559, 'Ajouter Client', 1, '1', '2018-04-29 17:47:07'),
(41354, 396, '54aa9121e05f5e698d354022a8eab71d', 1, 1, 560, 'Editer Client', 1, '1', '2018-04-29 17:47:07'),
(41355, 397, '4eaf650e8c2221d590fac5a6a6952231', 1, 1, 561, 'Supprimer Client', 1, '1', '2018-04-29 17:47:07'),
(41356, 398, '534cd4b17fb8a371d3a20565ab8fd96e', 1, 1, 562, 'Valider Client', 1, '1', '2018-04-29 17:47:07'),
(41357, 399, '95bb6aa696ef630a335aa84e1e425e2c', 1, 1, 563, 'Détails Client', 0, '1', '2018-04-29 17:47:07'),
(41358, 752, '179906c1666d7e9a7b4d1f52a1f84ec0', 1, 1, 1200, 'Commerciale', 1, '1', '2018-04-29 17:47:07'),
(41359, 752, '3e2b9ccbb5837f42342934bd9ba3aa49', 1, 1, 1201, 'Modifier Commerciale', 0, '1', '2018-04-29 17:47:07'),
(41360, 752, '1f4be058271867b2a398678fb0e49750', 1, 1, 1202, 'Valider commerciale', 0, '1', '2018-04-29 17:47:07'),
(41361, 752, '3980ecadef51319f03ae82b686e97dc4', 1, 1, 1203, 'Détails commerciale', 0, '1', '2018-04-29 17:47:07'),
(41362, 752, 'b0abfcf29eaacb4bad1a88a663331182', 1, 1, 1204, 'Détails Commerciale', 0, '1', '2018-04-29 17:47:07'),
(41363, 752, '923af4f85ccb0b69f7920e557ed03768', 1, 1, 1205, 'Désactiver commerciale', 0, '1', '2018-04-29 17:47:07'),
(41364, 752, 'f2e2a34f24f845f58e3af9e1baf3d34c', 1, 1, 1206, 'Commissions', 0, '1', '2018-04-29 17:47:07'),
(41365, 753, '03b0a2e252d4c53940ca1817d9083f0a', 1, 1, 1207, 'Ajouter commerciale', 1, '1', '2018-04-29 17:47:07'),
(41366, 754, 'ab2d80f7396b53342b8455293be7c892', 1, 1, 1208, 'Modifier Commerciale', 1, '1', '2018-04-29 17:47:07'),
(41367, 755, 'b9de9130d6ec79fa1981fd935590d9c7', 1, 1, 1209, 'Valider commerciale', 1, '1', '2018-04-29 17:47:07'),
(41368, 756, '97f27dfbac6c8d8f785585ca54b1b8d4', 1, 1, 1210, 'Supprimer commerciale', 1, '1', '2018-04-29 17:47:07'),
(41369, 757, '250db5ee83c4c30061b983a26fb91ba9', 1, 1, 1211, 'Détails commerciale', 1, '1', '2018-04-29 17:47:07'),
(41370, 758, '738a72cd47c2630b73be1a92f8117525', 1, 1, 1212, 'Commissions', 1, '1', '2018-04-29 17:47:07'),
(41371, 758, '98f53da207662a231a3ff2377af1f03b', 1, 1, 1213, 'Payer Commission   ', 0, '1', '2018-04-29 17:47:07'),
(41372, 758, 'a51a876db71f5da2d9ef1ac6e4929543', 1, 1, 1214, 'Payer Commission', 0, '1', '2018-04-29 17:47:07'),
(41373, 758, 'd4327189d68e6aae34e614fbd5ecc9b8', 1, 1, 1215, 'Modifier commission', 0, '1', '2018-04-29 17:47:07'),
(41374, 758, '31cb75b08edc1219b940b8d9c3f74dec', 1, 1, 1216, 'Valider Commission', 0, '1', '2018-04-29 17:47:07'),
(41375, 758, '7b78301b1dfe281483854f0e23102bb1', 1, 1, 1217, 'Détails commission', 0, '1', '2018-04-29 17:47:07'),
(41376, 758, '9d48b04e7eb927ba243a8a52eed24d66', 1, 1, 1218, 'Détails commission', 0, '1', '2018-04-29 17:47:07'),
(41377, 758, 'f9ecac816e2e67543667014a6e2bd01b', 1, 1, 1219, 'Détails commission', 0, '1', '2018-04-29 17:47:07'),
(41378, 758, 'a824d5f254bea8798c2c719fba1d4f3b', 1, 1, 1220, 'Détails commission', 0, '1', '2018-04-29 17:47:07'),
(41379, 759, '5adc1562f2a8f48f30492133e6d82d48', 1, 1, 1221, 'Payer Commission', 1, '1', '2018-04-29 17:47:07'),
(41380, 760, 'd374ca19122a0b3f66f67bdbf74efc60', 1, 1, 1222, 'Ajouter commission', 1, '1', '2018-04-29 17:47:07'),
(41381, 761, '25bbc454f35643c1af3371fd02cc9195', 1, 1, 1223, 'Modifier commission', 1, '1', '2018-04-29 17:47:07'),
(41382, 762, 'c35c2ca29c5083910a20996ccd465a48', 1, 1, 1224, 'Valider Commission', 1, '1', '2018-04-29 17:47:07'),
(41383, 763, '418e3a3591fc54086c56b385077bfb71', 1, 1, 1225, 'Détails commission', 1, '1', '2018-04-29 17:47:07'),
(41384, 768, '899d40c8f22d4f7a6f048366f1829787', 1, 1, 1237, 'Gestion des contrats', 0, '1', '2018-04-29 17:47:07'),
(41385, 768, '4aea0d5a7bdb0e2513897507947fc3de', 1, 1, 1238, 'Modifier  contrat', 0, '1', '2018-04-29 17:47:07'),
(41386, 768, '4ccf7c3c72dfa25157ab01762069929e', 1, 1, 1239, 'Détail  contrat', 0, '1', '2018-04-29 17:47:07'),
(41387, 768, '18c5260f189a488c59134c1d53270dae', 1, 1, 1240, 'Valider  contrat', 0, '1', '2018-04-29 17:47:07'),
(41388, 768, '6ca83d9c6c0b229446da30b60b74031a', 1, 1, 1241, 'Détails  Contrat', 0, '1', '2018-04-29 17:47:07'),
(41389, 768, '52eef475bfa2afb7eb065329a93b0b4c', 1, 1, 1242, 'Renouveler  Contrat', 0, '1', '2018-04-29 17:47:07'),
(41390, 768, 'b23939959d533fa68091fca749b691aa', 1, 1, 1243, 'Détails Contrat ', 0, '1', '2018-04-29 17:47:07'),
(41391, 768, 'b6cc6622e5874a5c0a04e2103d8a7dd0', 1, 1, 1244, ' Détails    Contrat', 0, '1', '2018-04-29 17:47:07'),
(41392, 768, 'c58a3038be080d0c6cdf89e0fd0a8c71', 1, 1, 1245, 'Détails  Contrat', 0, '1', '2018-04-29 17:47:07'),
(41393, 768, '656d41ad5452611636a5d9f966729e39', 1, 1, 1246, 'Renouveler Contrat', 0, '1', '2018-04-29 17:47:07'),
(41394, 768, '9a7cae6e28f8265acf392c94c2c38aec', 1, 1, 1247, 'Résilier Contrat', 0, '1', '2018-04-29 17:47:07'),
(41395, 768, '2e8270e01e3668cfd156816d9107d1f7', 1, 1, 1248, 'Détails   Contrat', 0, '1', '2018-04-29 17:47:07'),
(41396, 768, 'e5c2a867baf3429d758742a021d4795c', 1, 1, 1260, 'Echéances', 0, '1', '2018-04-29 17:47:07'),
(41397, 768, 'dfc236bfafa081e74f23a1c5f631fe78', 1, 1, 1261, 'Echéances', 0, '1', '2018-04-29 17:47:07'),
(41398, 769, '87f4c3ed4713c3bc9e3fef60a6649055', 1, 1, 1250, 'Ajouter contrat', 0, '1', '2018-04-29 17:47:07'),
(41399, 770, '9e49a431d9637544cefa2869fd7278b9', 1, 1, 1251, 'Modifier contrat', 0, '1', '2018-04-29 17:47:07'),
(41400, 771, '1e9395a182a44787e493bc038cd80bbf', 1, 1, 1252, 'Supprimer contrat', 0, '1', '2018-04-29 17:47:07'),
(41401, 772, '460d92834715b149c4db28e1643bd932', 1, 1, 1253, 'Valider contrat', 0, '1', '2018-04-29 17:47:07'),
(41402, 773, 'bbcf2879c2f8f60cfa55fa97c6e79268', 1, 1, 1254, 'Détail contrat', 0, '1', '2018-04-29 17:47:07'),
(41403, 774, 'fe058ccb890b25a54866be7f24a40363', 1, 1, 1255, 'Ajouter échéance ', 0, '1', '2018-04-29 17:47:07'),
(41404, 775, '36a248f56a6a80977e5c90a5c59f39d3', 1, 1, 1256, 'Modifier échéance contrat', 0, '1', '2018-04-29 17:47:07'),
(41405, 776, 'f0567980556249721f24f2fc88ebfed5', 1, 1, 1257, 'Renouveler Contrat', 0, '1', '2018-04-29 17:47:07'),
(41406, 777, 'd3fc6f1bcca0a0250c5f6de29fd72b80', 1, 1, 1258, 'Résilier Contrat', 1, '1', '2018-04-29 17:47:07'),
(41407, 778, '428bf9d4c56394d24e15f5458b077990', 1, 1, 1259, 'Echéances', 1, '1', '2018-04-29 17:47:07'),
(41408, 778, 'b0cff04f8af9234adbc81e7f679c7176', 1, 1, 1263, 'Générer Facture', 0, '1', '2018-04-29 17:47:07'),
(41409, 778, '1596760bb0380a6a77c784ec92eb6fa7', 1, 1, 1265, 'Afficher Facture', 0, '1', '2018-04-29 17:47:07'),
(41410, 779, 'b37af8eb31b7082afa5ad48f0d618f3b', 1, 1, 1262, 'Générer Facture', 1, '1', '2018-04-29 17:47:07'),
(41411, 780, 'd860c94cc554cc0ff03af97a9248d2de', 1, 1, 1264, 'Afficher Facture', 1, '1', '2018-04-29 17:47:07'),
(41412, 609, 'ec45512f34613446e7a2e367d4b4cfbd', 1, 1, 920, 'Gestion Contrats Fournisseurs', 0, '1', '2018-04-29 17:47:07'),
(41413, 609, 'e3c0d7e92dad7f8794b2415c334ec3ff', 1, 1, 921, 'Editer Contrat', 0, '1', '2018-04-29 17:47:07'),
(41414, 609, '9dfff1c8dcb804837200f38e95381420', 1, 1, 922, 'Valider Contrat', 0, '1', '2018-04-29 17:47:07'),
(41415, 609, '9fe39b496077065105a57ccd9ed05863', 1, 1, 923, 'Désactiver Contrat', 0, '1', '2018-04-29 17:47:07'),
(41416, 609, 'faee342ff51dbe9f835529ae5b9b2a0b', 1, 1, 924, 'Détails  Contrat ', 0, '1', '2018-04-29 17:47:07'),
(41417, 609, '83406b6b206ed08878f2b2e854932ae5', 1, 1, 925, 'Détails   Contrat  ', 0, '1', '2018-04-29 17:47:07'),
(41418, 609, '8447888bef30fb983477cc1357ff7e6f', 1, 1, 926, 'Détails    Contrat ', 0, '1', '2018-04-29 17:47:07'),
(41419, 609, '4cc1845128f6a5ff3ed01100292d8ebb', 1, 1, 927, '  Détails    Contrat', 0, '1', '2018-04-29 17:47:07'),
(41420, 609, 'cd82d84c5f70a633b10aae88c34e9159', 1, 1, 928, '  Renouveler   Contrat ', 0, '1', '2018-04-29 17:47:07'),
(41421, 609, 'e9e994a0f8a204f1323fca7ce30931fe', 1, 1, 929, ' Détails  Contrat ', 0, '1', '2018-04-29 17:47:07'),
(41422, 609, 'b9e0a2a0236899590c72d31b878edfb2', 1, 1, 930, ' Renouveler  Contrat ', 0, '1', '2018-04-29 17:47:07'),
(41423, 610, 'ded24eb817021c5a666a677b1565bc5e', 1, 1, 931, 'Ajouter Contrat', 0, '1', '2018-04-29 17:47:07'),
(41424, 611, 'ed6b8695494bf4ed86d5fb18690b3a59', 1, 1, 932, 'Editer Contrat', 0, '1', '2018-04-29 17:47:07'),
(41425, 612, 'b8a40913b5955209994aaa26d0e8c3d4', 1, 1, 933, 'Supprimer Contrat', 0, '1', '2018-04-29 17:47:07'),
(41426, 613, '5efb874e7d73ccd722df806e8275770f', 1, 1, 934, 'Valider Contrat', 0, '1', '2018-04-29 17:47:07'),
(41427, 614, '64a5f976687a8c5f7cd3d672cc5d9c8c', 1, 1, 935, 'Détails Contrat', 0, '1', '2018-04-29 17:47:07'),
(41428, 615, '2cc55c65e79534161108288adb00472b', 1, 1, 936, 'Renouveler  Contrat', 0, '1', '2018-04-29 17:47:07'),
(41429, 432, 'f320732af279d6f2f8ae9c98cd0216de', 1, 1, 613, 'Gestion Départements', 0, '1', '2018-04-29 17:47:07'),
(41430, 432, '96516cd0c72d814d5dcb1d86eacd29ab', 1, 1, 617, 'Editer Département', 0, '1', '2018-04-29 17:47:07'),
(41431, 432, 'ef27a63534fa9fc3bd4b5086a92db546', 1, 1, 619, 'Valider Département', 0, '1', '2018-04-29 17:47:07'),
(41432, 432, '9aed965af4c4b89a5a23c41bf685d403', 1, 1, 620, 'Désactiver Département', 0, '1', '2018-04-29 17:47:07'),
(41433, 433, '722b3ba1c7fe735e87aa7415e5502a4c', 1, 1, 614, 'Ajouter Département', 0, '1', '2018-04-29 17:47:07'),
(41434, 434, 'daeb31006124e562d284aff67360ee19', 1, 1, 615, 'Editer Département', 0, '1', '2018-04-29 17:47:07'),
(41435, 435, 'a775da608fe55c53211d4f1c6e493251', 1, 1, 616, 'Supprimer Département', 0, '1', '2018-04-29 17:47:07'),
(41436, 436, 'bbb96ec910c5000a2006db2f6e8af10a', 1, 1, 618, 'Valider Département', 0, '1', '2018-04-29 17:47:07'),
(41437, 655, '0e79510db7f03b9b6266fc7b4a612153', 1, 1, 1005, 'Gestion Devis', 1, '1', '2018-04-29 17:47:07'),
(41438, 655, 'c15b00a1e37657336df8b6aa0eea2db5', 1, 1, 1006, 'Modifier Devis', 0, '1', '2018-04-29 17:47:07'),
(41439, 655, '998fb803f2e64f22418b3b388d6240a4', 1, 1, 1007, 'Envoi Devis au client', 0, '1', '2018-04-29 17:47:07'),
(41440, 655, '28e267a2a0647d4cb37b18abb1e7d051', 1, 1, 1008, 'Voir détails', 0, '1', '2018-04-29 17:47:07'),
(41441, 655, 'd34b07afd92adad84e1c4c2ebd92ba95', 1, 1, 1009, 'Voir détails', 0, '1', '2018-04-29 17:47:07'),
(41442, 655, 'f6f55b9d0ba9d704b2861d57cda32477', 1, 1, 1010, 'Réponse Client', 0, '1', '2018-04-29 17:47:07'),
(41443, 655, '4b11c0bfb3f970a541100f7fc334927e', 1, 1, 1011, 'Voir détails', 0, '1', '2018-04-29 17:47:07'),
(41444, 655, '61a0655c2c13039b5b8262b82ae6cb51', 1, 1, 1012, 'Voir détails', 0, '1', '2018-04-29 17:47:07'),
(41445, 655, 'ed30dfbb21d8d24a0da432252358bdf8', 1, 1, 1013, 'Voir détails', 0, '1', '2018-04-29 17:47:07'),
(41446, 655, '7bd2e025ffb3893dea4776e152301716', 1, 1, 1014, 'Débloquer devis', 0, '1', '2018-04-29 17:47:07'),
(41447, 655, '6d9ec7d9ebebcdc921376e7bf0c9fdaf', 1, 1, 1015, 'Valider devis', 0, '1', '2018-04-29 17:47:08'),
(41448, 655, 'b1bcc4a4ab154f110abcf54c0c659fb3', 1, 1, 1016, 'Voir détails', 0, '1', '2018-04-29 17:47:08'),
(41449, 655, '91a90a46e3430c491ab8db654b6e87c4', 1, 1, 1017, 'Voir détails', 0, '1', '2018-04-29 17:47:08'),
(41450, 656, 'd9eeb330625c1b87e0df00986a47be01', 1, 1, 1018, 'Ajouter Devis', 0, '1', '2018-04-29 17:47:08'),
(41451, 657, 'da93cdb05137e15aed9c4c18bddd746a', 1, 1, 1019, 'Ajouter détail devis', 0, '1', '2018-04-29 17:47:08'),
(41452, 658, 'f9f3c299f9bd0fec014f6bd3f0e06adb', 1, 1, 1020, 'Modifier Devis', 0, '1', '2018-04-29 17:47:08'),
(41453, 659, 'e14cce6f1faf7784adb327581c516b90', 1, 1, 1021, 'Supprimer Devis', 0, '1', '2018-04-29 17:47:08'),
(41454, 660, '38f10871792c133ebcc6040e9a11cde8', 1, 1, 1022, 'Modifier détail Devis', 0, '1', '2018-04-29 17:47:08'),
(41455, 661, '8def42e75fd4aee61c378d9fb303850d', 1, 1, 1023, 'Afficher détail devis', 0, '1', '2018-04-29 17:47:08'),
(41456, 662, '7666e87783b0f5a7eec1eea7593f7dfe', 1, 1, 1024, 'Valider Devis', 0, '1', '2018-04-29 17:47:08'),
(41457, 663, '7f1c48324d8c369da9aa6ab8af35acd8', 1, 1, 1025, 'Validation Client Devis', 0, '1', '2018-04-29 17:47:08'),
(41458, 664, '6adf896091dde0df89f777f31606953c', 1, 1, 1026, 'Débloquer devis', 0, '1', '2018-04-29 17:47:08'),
(41459, 665, '15cbb79dd4a74266158e6b29a83e683c', 1, 1, 1027, 'Archiver Devis', 1, '1', '2018-04-29 17:47:08'),
(41460, 859, '7fde4464cf64b2fca994f7cdc128307e', 1, 1, 1409, 'Gestion des Entrepôts', 1, '1', '2018-04-29 17:47:08'),
(41461, 859, 'dfc69597b3f4c08061386486fd177e5b', 1, 1, 1412, 'Editer Entrepôt', 0, '1', '2018-04-29 17:47:08'),
(41462, 859, '4886df3c47866eac474e1d87f7774850', 1, 1, 1415, 'Valider Entrepôt', 0, '1', '2018-04-29 17:47:08'),
(41463, 859, 'f16f69131a3e43f28acab8db040dbe1c', 1, 1, 1416, 'Désactiver Entrepôt', 0, '1', '2018-04-29 17:47:08'),
(41464, 860, '462994905cf4ea3256839ee181df30f0', 1, 1, 1410, 'Ajouter Entrepôt', 1, '1', '2018-04-29 17:47:08'),
(41465, 861, 'c7c70683c8539bf442fbb3bc145062d9', 1, 1, 1411, 'Editer Entrepôt', 1, '1', '2018-04-29 17:47:08'),
(41466, 862, '883e7763f88a902baf086a152a3c81a5', 1, 1, 1413, 'Supprimer Entrepôt', 1, '1', '2018-04-29 17:47:08'),
(41467, 863, 'c717024f55a777aece4490406767f434', 1, 1, 1414, 'Valider Entrepôt', 1, '1', '2018-04-29 17:47:08'),
(41468, 796, '4c924acb9adc87d8389e8f9842a965c5', 1, 1, 1297, 'Gestion des factures', 0, '1', '2018-04-29 17:47:08'),
(41469, 796, '98a697ec628778765b25e02ba2929d38', 1, 1, 1298, 'Liste complément', 0, '1', '2018-04-29 17:47:08'),
(41470, 796, 'f8b20f7fec99b45b967a431d64b7f061', 1, 1, 1299, 'Liste encaissements', 0, '1', '2018-04-29 17:47:08'),
(41471, 796, '9a51fb5298e39a28af3ad6272fc51177', 1, 1, 1300, 'Valider facture', 0, '1', '2018-04-29 17:47:08'),
(41472, 796, '851f1d4c13f6025f69f5b9315321d350', 1, 1, 1301, 'Désactiver facture', 0, '1', '2018-04-29 17:47:08'),
(41473, 796, '5c79105956d28b5cac52f85784039919', 1, 1, 1302, 'Détail facture', 0, '1', '2018-04-29 17:47:08'),
(41474, 796, '7892721423af84a0b54e90250cf27ee3', 1, 1, 1303, 'Détails Facture', 0, '1', '2018-04-29 17:47:08'),
(41475, 796, '4b69240b3dd04f7a29457008b31d1248', 1, 1, 1304, 'Envoyer au client  ', 0, '1', '2018-04-29 17:47:08'),
(41476, 796, '80a4b2643b95c2836e968411811d3c21', 1, 1, 1305, 'Détails facture', 0, '1', '2018-04-29 17:47:08'),
(41477, 796, '2f679be3c0d7b88529209f86745f9028', 1, 1, 1306, 'Détails facture', 0, '1', '2018-04-29 17:47:08'),
(41478, 796, '429558e9a1e899c11051ea5c9a4f7942', 1, 1, 1307, 'Détails facture', 0, '1', '2018-04-29 17:47:08'),
(41479, 796, '3acd11d8d74fb7e1ba8d5721e96f91bd', 1, 1, 1308, 'Liste encaissements', 0, '1', '2018-04-29 17:47:08'),
(41480, 797, '55c3c5d2d93143b315513b7401043c8b', 1, 1, 1309, 'complements', 0, '1', '2018-04-29 17:47:08'),
(41481, 797, 'dfc4772cc03cf0b92a47f54fc6a2326e', 1, 1, 1310, 'Modifier complément', 0, '1', '2018-04-29 17:47:08'),
(41482, 798, '03a18bdd5201e433a3c523a2b34d059a', 1, 1, 1311, 'Ajouter complément', 0, '1', '2018-04-29 17:47:08'),
(41483, 799, '88d9bc979cd1102eb8196e7f5e6042ca', 1, 1, 1312, 'Encaissement', 0, '1', '2018-04-29 17:47:08'),
(41484, 799, 'c690cc68f5257c0c225b8b8e6126ea56', 1, 1, 1313, 'Modifier encaissement', 0, '1', '2018-04-29 17:47:08'),
(41485, 799, '1dc06f602e8630f273d44aa2751b2127', 1, 1, 1314, 'Détails encaissement', 0, '1', '2018-04-29 17:47:08'),
(41486, 799, '6567dc21b9b744ea7dbcbcbf83df4ac5', 1, 1, 1315, 'Valider encaissement', 0, '1', '2018-04-29 17:47:08'),
(41487, 799, 'bc335bbc5e0debff602b4e5325c89a99', 1, 1, 1316, 'Détails encaissement', 0, '1', '2018-04-29 17:47:08'),
(41488, 800, 'e4866b292dbc3c9c5d9cc37273a5b498', 1, 1, 1317, 'Ajouter encaissement', 0, '1', '2018-04-29 17:47:08'),
(41489, 801, '8665be10959f39df4f149962eb70041f', 1, 1, 1318, 'Modifier complément', 0, '1', '2018-04-29 17:47:08'),
(41490, 802, '585d411904bf7d9e83d21b2810ff1d6c', 1, 1, 1319, 'Modifier encaissement', 0, '1', '2018-04-29 17:47:08'),
(41491, 803, '8c8b058a4d030cdc8b49c9008abb2e92', 1, 1, 1320, 'Supprimer complément', 0, '1', '2018-04-29 17:47:08'),
(41492, 804, '6bf7d5180940f03567a5d711e8563ba4', 1, 1, 1321, 'Supprimer encaissement', 0, '1', '2018-04-29 17:47:08'),
(41493, 805, '256abad0ec8e3bc8ed1c0653ff177255', 1, 1, 1322, 'Valider facture', 0, '1', '2018-04-29 17:47:08'),
(41494, 806, 'b5dc5719c1f96df7334f371dcf51a5b6', 1, 1, 1323, 'Détail encaissement', 0, '1', '2018-04-29 17:47:08'),
(41495, 807, '16fbf6fdcbb72f863bcf7e4ef28d8e75', 1, 1, 1324, 'Détails facture', 0, '1', '2018-04-29 17:47:08'),
(41496, 808, '5efdeb41007109ca99f23f0756217827', 1, 1, 1325, 'Désactiver Facture', 0, '1', '2018-04-29 17:47:08'),
(41497, 809, '1127d08fb22f425fd7913c3df1b9884f', 1, 1, 1326, 'Valider encaissement', 1, '1', '2018-04-29 17:47:08'),
(41498, 810, '1bacb05aca2d17b42b1de767a8ad45de', 1, 1, 1327, 'Envoyer Facture', 1, '1', '2018-04-29 17:47:08'),
(41499, 502, '6beb279abea6434e3b73229aebadc081', 1, 1, 725, 'Gestion Fournisseurs', 0, '1', '2018-04-29 17:47:08'),
(41500, 502, 'ff95747f3a590b6539803f2a9a394cd5', 1, 1, 730, 'Editer Fournisseur', 0, '1', '2018-04-29 17:47:08'),
(41501, 502, 'fea982f5074995d4ccd6211a71ab2680', 1, 1, 731, 'Valider Fournisseur', 0, '1', '2018-04-29 17:47:08'),
(41502, 502, '1d0411a0dec15fc28f054f1a79d95618', 1, 1, 732, 'Désactiver Fournisseur', 0, '1', '2018-04-29 17:47:08'),
(41503, 502, 'a52affdd109b9362ce47ff18aad53e2a', 1, 1, 737, 'Détails Fournisseur', 0, '1', '2018-04-29 17:47:08'),
(41504, 502, 'c6fe5f222dd563204188e8bf0d69bd9e', 1, 1, 738, 'Détails  Fournisseur', 0, '1', '2018-04-29 17:47:08'),
(41505, 503, 'd644015625a9603adb2fcc36167aeb73', 1, 1, 726, 'Ajouter Fournisseur', 0, '1', '2018-04-29 17:47:08'),
(41506, 504, '58c6694abfd3228d927a5d5a06d40b94', 1, 1, 727, 'Editer Fournisseur', 0, '1', '2018-04-29 17:47:08'),
(41507, 505, 'd072f81cd779e4b0152953241d713ca3', 1, 1, 728, 'Supprimer Fournisseur', 0, '1', '2018-04-29 17:47:08'),
(41508, 506, '657351ce5aa227513e3b50dea77db918', 1, 1, 729, 'Valider Fournisseur', 0, '1', '2018-04-29 17:47:08'),
(41509, 508, '83b693fe35a1be29edafe4f6170641aa', 1, 1, 736, 'Détails Fournisseur', 0, '1', '2018-04-29 17:47:08'),
(41510, 542, '72db1c2280dc3eb6405908c1c5b6c815', 1, 1, 810, 'Information société', 0, '1', '2018-04-29 17:47:08');
INSERT INTO `rules_action` (`id`, `appid`, `idf`, `service`, `userid`, `action_id`, `descrip`, `type`, `creusr`, `credat`) VALUES
(41511, 637, 'b8e62907d367fb44d644a5189cd07f42', 1, 1, 978, 'Modules', 1, '1', '2018-04-29 17:47:08'),
(41512, 637, '05ce9e55686161d99e0714bb86243e5b', 1, 1, 979, 'Editer Module', 0, '1', '2018-04-29 17:47:08'),
(41513, 637, '819cf9c18a44cb80771a066768d585f2', 1, 1, 980, 'Exporter Module', 0, '1', '2018-04-29 17:47:08'),
(41514, 637, 'd2fc3ee15cee5208a8b9c70f1e53c196', 1, 1, 981, 'Liste task modul', 0, '1', '2018-04-29 17:47:08'),
(41515, 637, 'ad75e6b877f20e3d6fc1789da4dcb3e6', 1, 1, 982, 'Editer Module', 0, '1', '2018-04-29 17:47:08'),
(41516, 637, '064a9b0eff1006fd4f25cb4eaf894ca1', 1, 1, 983, 'Liste task modul Setting', 0, '1', '2018-04-29 17:47:08'),
(41517, 637, 'ac4eb0c94da00a48ad5d995f5e9e9366', 1, 1, 984, 'MAJ Module', 0, '1', '2018-04-29 17:47:08'),
(41518, 638, '44bd5341b0ab41ced21db8b3e92cf5aa', 1, 1, 985, 'Ajouter un Modul', 1, '1', '2018-04-29 17:47:08'),
(41519, 640, '8653b156f1a4160a12e5a94b211e59a2', 1, 1, 986, 'Liste Action Task', 0, '1', '2018-04-29 17:47:08'),
(41520, 640, '86aced763bc02e1957a5c740fb37b4f7', 1, 1, 987, 'Supprimer Application', 0, '1', '2018-04-29 17:47:08'),
(41521, 640, 'f07352e32fe86da1483c6ab071b7e7a9', 1, 1, 988, 'Ajout Affichage WF', 0, '1', '2018-04-29 17:47:08'),
(41522, 641, '1c452aff8f1551b3574e15b74147ea56', 1, 1, 989, 'Ajouter Task Modul', 1, '1', '2018-04-29 17:47:08'),
(41523, 642, 'f085fe4610576987db963501297e4d91', 1, 1, 990, 'Editer Task Modul', 1, '1', '2018-04-29 17:47:08'),
(41524, 642, '38702c272a6f4d334c2f4c3684c8b163', 1, 1, 991, 'Ajouter action modul', 1, '1', '2018-04-29 17:47:08'),
(41525, 643, 'cbae1ebe850f6dd8841426c6fedf1785', 1, 1, 992, 'Liste Action Task', 1, '1', '2018-04-29 17:47:08'),
(41526, 643, 'e30471396f9b86ccdcc94943d80b679a', 1, 1, 993, 'Editer Task Action', 0, '1', '2018-04-29 17:47:08'),
(41527, 644, '502460cd9327b46ee7af0a258ebf8c80', 1, 1, 994, 'Ajouter Action Task', 1, '1', '2018-04-29 17:47:08'),
(41528, 645, '13c107211904d4a2e65dd65c60ec7272', 1, 1, 995, 'Supprimer Application', 1, '1', '2018-04-29 17:47:08'),
(41529, 646, '8c8acf9cf3790b16b1fae26823f45eab', 1, 1, 996, 'Importer des modules', 1, '1', '2018-04-29 17:47:08'),
(41530, 647, '2f4518dab90b706e2f4acd737a0425d8', 1, 1, 997, 'Ajouter Module paramétrage', 1, '1', '2018-04-29 17:47:08'),
(41531, 648, '8e0c0212d8337956ac2f4d6eb180d74b', 1, 1, 998, 'Editer Module paramètrage', 1, '1', '2018-04-29 17:47:08'),
(41532, 649, 'fc54953b47b7fcb11cc14c0c2e2125f0', 1, 1, 999, 'Ajouter Autorisation Etat', 1, '1', '2018-04-29 17:47:08'),
(41533, 650, '966ec2dd83e6006c2d0ff1d1a5f12e33', 1, 1, 1000, 'Editer Task Action', 1, '1', '2018-04-29 17:47:08'),
(41534, 651, '3473119f6683893a3f1372dbf7d811e1', 1, 1, 1001, 'MAJ Module', 1, '1', '2018-04-29 17:47:08'),
(41535, 652, '2e2346bd422536c1d996ff25f9e71357', 1, 1, 1002, 'Dupliquer Action Task', 0, '1', '2018-04-29 17:47:08'),
(41536, 653, '8a3634181ae5bc9223b689a310158962', 1, 1, 1003, 'Supprimer Task action', 0, '1', '2018-04-29 17:47:08'),
(41537, 654, '8afb3c669307183cd3b7d189fbf204d7', 1, 1, 1004, 'Affichage Work Flow', 0, '1', '2018-04-29 17:47:08'),
(41538, 864, '530dbb6317ed3d348cd55d1f9a09e361', 1, 1, 1417, 'Mouvements de Stock', 1, '1', '2018-04-29 17:47:08'),
(41539, 475, '605450f3d7c84701b986fa31e1e9fa43', 1, 1, 684, 'Gestion Pays', 0, '1', '2018-04-29 17:47:08'),
(41540, 475, '29ba6cc689eca63dbafb109ec58bc4d6', 1, 1, 689, 'Editer Pays', 0, '1', '2018-04-29 17:47:08'),
(41541, 475, '763fe13212b4324590518773cd9a36fa', 1, 1, 690, 'Valider Pays', 0, '1', '2018-04-29 17:47:08'),
(41542, 475, '3c8427c7313d35219b17572efd380b17', 1, 1, 691, 'Désactiver Pays', 0, '1', '2018-04-29 17:47:08'),
(41543, 476, '3cd55a55307615d72aae84c6b5cf99bc', 1, 1, 685, 'Ajouter Pays', 0, '1', '2018-04-29 17:47:08'),
(41544, 477, 'cfe617d7bc6a9c7d8b86c468f21396f2', 1, 1, 686, 'Editer Pays', 0, '1', '2018-04-29 17:47:08'),
(41545, 478, 'b768486aeb655c48cc411c11fa60e150', 1, 1, 687, 'Supprimer Pays', 0, '1', '2018-04-29 17:47:08'),
(41546, 479, '15e4e24f320daa9d563ae62acff9e586', 1, 1, 688, 'Valider Pays', 0, '1', '2018-04-29 17:47:08'),
(41547, 728, '192715027870a4a612fd44d562e2752f', 1, 1, 1151, 'Gestion des produits', 0, '1', '2018-04-29 17:47:08'),
(41548, 728, 'cb96e99d5f8e381637d1ac83f1a21f1c', 1, 1, 1152, 'Modifier  produit', 0, '1', '2018-04-29 17:47:08'),
(41549, 728, '64e84ff11fea7f68bcf6a5b744c36081', 1, 1, 1153, 'Détail  produit', 0, '1', '2018-04-29 17:47:08'),
(41550, 728, '0c94d85f4ee23656a01155ad1af5001c', 1, 1, 1154, 'Valider  produit', 0, '1', '2018-04-29 17:47:08'),
(41551, 728, '6b087b20929483bb07f8862b39e41f07', 1, 1, 1155, 'Désactiver produit', 0, '1', '2018-04-29 17:47:08'),
(41552, 728, '6f1d7cc5bd1c941beffa0ae3e1efd559', 1, 1, 1156, 'Achat  produit', 0, '1', '2018-04-29 17:47:08'),
(41553, 728, '41b9c4b7028269d4540915d6ec14ee79', 1, 1, 1157, 'Détails Produit', 0, '1', '2018-04-29 17:47:08'),
(41554, 729, '93e893c307a6fa63e392f78751ec70ce', 1, 1, 1158, 'Ajouter produit', 0, '1', '2018-04-29 17:47:08'),
(41555, 730, 'bcf3beada4a98e8145af2d4fbb744f01', 1, 1, 1159, 'Modifier produit', 0, '1', '2018-04-29 17:47:08'),
(41556, 731, '796427ec57f7c13d6b737055ae686b34', 1, 1, 1160, 'Detail produit', 0, '1', '2018-04-29 17:47:08'),
(41557, 732, '1fb8cd1a179be07586fa7db05013dd37', 1, 1, 1161, 'Valider produit', 0, '1', '2018-04-29 17:47:08'),
(41558, 733, '7779e98d2111faedf458f7aeb548294e', 1, 1, 1162, 'Supprimer produit', 0, '1', '2018-04-29 17:47:08'),
(41559, 734, '8da585a04e918c256bd26f0c03f1390d', 1, 1, 1163, 'Achat produit', 0, '1', '2018-04-29 17:47:08'),
(41560, 734, 'f8c9a7413089566d1db20dcc5ca17e03', 1, 1, 1164, 'Modifier achat', 0, '1', '2018-04-29 17:47:08'),
(41561, 734, '682b4328ee832101a44dac86b22d5757', 1, 1, 1165, 'Détail achat', 0, '1', '2018-04-29 17:47:08'),
(41562, 734, 'd1ebf1c5482ddf06721b11ec64afb744', 1, 1, 1166, 'Valider achat', 0, '1', '2018-04-29 17:47:08'),
(41563, 734, '368a1e91fc63e263eb01d85a34ecd89b', 1, 1, 1167, 'Désactiver achat', 0, '1', '2018-04-29 17:47:08'),
(41564, 735, '659be5cd86a12eba7e59c52d60198a36', 1, 1, 1168, 'Ajoute achat', 0, '1', '2018-04-29 17:47:08'),
(41565, 736, '8415336a17e8ca26f3eca5741863f3b2', 1, 1, 1169, 'Modifier achat', 0, '1', '2018-04-29 17:47:08'),
(41566, 737, '2c3b4875b72f7da6a87b5c0d7e85f51d', 1, 1, 1170, 'Supprimer achat', 0, '1', '2018-04-29 17:47:08'),
(41567, 738, 'd4180eb7a4ff86c598f441ffd4543f36', 1, 1, 1171, 'Détail achat', 0, '1', '2018-04-29 17:47:08'),
(41568, 739, '4a4c9b096bad58a96d5ea6f93d66e81c', 1, 1, 1172, 'Valider achat', 0, '1', '2018-04-29 17:47:08'),
(41569, 720, '1eb847d87adcad78d5e951e6110061e5', 1, 1, 1137, 'Gestion Proforma', 0, '1', '2018-04-29 17:47:08'),
(41570, 720, '44ef6849d8d5d17d8e0535187e923d32', 1, 1, 1138, 'Editer proforma', 0, '1', '2018-04-29 17:47:08'),
(41571, 720, 'b7ce06be726011362a271678547a803c', 1, 1, 1139, 'Valider Proforma', 0, '1', '2018-04-29 17:47:08'),
(41572, 720, 'abd8c50f1d2ef4beeeddb68a72973587', 1, 1, 1140, 'Détail Proforma', 0, '1', '2018-04-29 17:47:08'),
(41573, 720, '35a88b5c359908b063ac98cafc622987', 1, 1, 1141, 'Détail Proforma', 0, '1', '2018-04-29 17:47:08'),
(41574, 720, 'e20d83df90355eca2a65f56a2556601f', 1, 1, 1142, 'Détail Proforma', 0, '1', '2018-04-29 17:47:08'),
(41575, 720, '252ed64d8956e20fb88c1be41688f74a', 1, 1, 1143, 'Envoi proforma au client', 0, '1', '2018-04-29 17:47:08'),
(41576, 721, 'd5a6338765b9eab63104b59f01c06114', 1, 1, 1144, 'Ajouter pro-forma', 0, '1', '2018-04-29 17:47:08'),
(41577, 722, '95831bde77bc886d6ab4dd5e734de743', 1, 1, 1145, 'Editer proforma', 0, '1', '2018-04-29 17:47:08'),
(41578, 723, 'cbb4e1efa1c05b42d25a3a6bcab038a2', 1, 1, 1146, 'Ajouter détail proforma', 0, '1', '2018-04-29 17:47:08'),
(41579, 724, 'e9f745054778257a255452c6609461a0', 1, 1, 1147, 'valider Proforma', 0, '1', '2018-04-29 17:47:08'),
(41580, 725, 'defef148c404c7e6ac79e4783e0a7ab7', 1, 1, 1148, 'Détail Pro-forma', 0, '1', '2018-04-29 17:47:08'),
(41581, 726, '53008d64edf241c937a06f03eff139aa', 1, 1, 1149, 'Editer détail proforma', 0, '1', '2018-04-29 17:47:08'),
(41582, 727, '30e9d3d1ad17eb7f1fc0d5cbb9b58482', 1, 1, 1150, 'Supprimer proforma', 1, '1', '2018-04-29 17:47:08'),
(41583, 470, 'd57b16b3aad4ce59f909609246c4fd36', 1, 1, 676, 'Gestion des régions', 0, '1', '2018-04-29 17:47:08'),
(41584, 470, 'd2e007184668dd70b9bae44d46d28ded', 1, 1, 677, 'Modifier région', 0, '1', '2018-04-29 17:47:08'),
(41585, 470, 'e74403c99ac8325b78735c531a20442f', 1, 1, 678, 'Valider région', 0, '1', '2018-04-29 17:47:08'),
(41586, 470, '7397a0fab078728bd5c53be61022d5ce', 1, 1, 679, 'Désactiver région', 0, '1', '2018-04-29 17:47:08'),
(41587, 471, '0237bd41cf70c3529681b4ccb041f1fd', 1, 1, 680, 'Ajouter région', 0, '1', '2018-04-29 17:47:08'),
(41588, 472, '6d290f454da473cb8a557829a410c3f1', 1, 1, 681, 'Modifier région', 0, '1', '2018-04-29 17:47:08'),
(41589, 473, '008cd9ea5767c739675fef4e1261cfe8', 1, 1, 682, 'Valider région', 0, '1', '2018-04-29 17:47:08'),
(41590, 474, 'fc477e6a4c90cd427ae81e555c11d6a9', 1, 1, 683, 'Supprimer région', 0, '1', '2018-04-29 17:47:08'),
(41591, 34, '83b9fa44466da4bcd7f8304185bfeac8', 1, 1, 28, 'Services', 0, '1', '2018-04-29 17:47:08'),
(41592, 34, '3c388c1e842851df49abe9ee73c0a2e7', 1, 1, 33, 'Valider Service', 0, '1', '2018-04-29 17:47:08'),
(41593, 34, 'dcb9555d5ca1d108e9fa95daa9da4b3a', 1, 1, 34, 'Supprimer Service', 0, '1', '2018-04-29 17:47:08'),
(41594, 34, '74950fb3fd858404b6048c1e81bd7c9a', 1, 1, 144, 'Modifier Service', 0, '1', '2018-04-29 17:47:08'),
(41595, 35, '55043bc4207521e3010e91d6267f5302', 1, 1, 29, 'Ajouter Service', 1, '1', '2018-04-29 17:47:08'),
(41596, 36, '2fea3d893f6b6e81467ddd2a744e4a76', 1, 1, 30, 'Modifier Service', 1, '1', '2018-04-29 17:47:08'),
(41597, 37, '1a0d5897d31b4d5e29022671c1112f59', 1, 1, 31, 'Valider Service', 1, '1', '2018-04-29 17:47:08'),
(41598, 38, '42083d4e159baf7c2ace2bb977e2b0a0', 1, 1, 32, 'Supprimer Service', 1, '1', '2018-04-29 17:47:08'),
(41599, 858, '79c262a9849332f387662790b8da4399', 1, 1, 1408, 'Gestion de Stock', 1, '1', '2018-04-29 17:47:08'),
(41600, 543, 'a1c5a2657cc1b2ff6f85c6fe8f1c51ac', 1, 1, 811, 'Paramètrage Système', 0, '1', '2018-04-29 17:47:08'),
(41601, 543, 'de6285d9c0027ff8bccdf2af385ac337', 1, 1, 812, 'Editer paramètre', 0, '1', '2018-04-29 17:47:08'),
(41602, 544, '82f83d9d3d30fdef00d4c3ef96f0f899', 1, 1, 813, 'Ajouter Paramètre', 0, '1', '2018-04-29 17:47:08'),
(41603, 545, 'f0e54f346e9dcfdff65274709ce2c8ca', 1, 1, 814, 'Editer paramètre', 0, '1', '2018-04-29 17:47:08'),
(41604, 546, 'aaccd24eaf085b8f18115c9c7653d401', 1, 1, 815, 'Supprimer Paramètre', 0, '1', '2018-04-29 17:47:08'),
(41605, 865, '558708106fa0f9c46f98e4d1e5fdf191', 1, 1, 1418, 'Gestion Tickets ', 0, '1', '2018-04-29 17:47:08'),
(41606, 865, '5f239991de8d679e13e20e7a1a6c8433', 1, 1, 1419, 'Modifier ticket   ', 0, '1', '2018-04-29 17:47:08'),
(41607, 865, 'd0f2728310c81c3c323da9c3ec91d998', 1, 1, 1420, 'Affecter ticket ', 0, '1', '2018-04-29 17:47:08'),
(41608, 865, 'db7e28569171b1218d7aa12f75684aa2', 1, 1, 1421, 'Détails ticket', 0, '1', '2018-04-29 17:47:08'),
(41609, 865, '7813165051eca9e0dd0809ffc571b721', 1, 1, 1422, 'Détails ticket   ', 0, '1', '2018-04-29 17:47:08'),
(41610, 865, 'ade7658a655147c067bd85e554f5493b', 1, 1, 1423, 'Reaffecter ticket', 0, '1', '2018-04-29 17:47:08'),
(41611, 865, '599cb4aa075b2cc84746e15f14cd5e41', 1, 1, 1424, 'Ajouter action', 0, '1', '2018-04-29 17:47:08'),
(41612, 865, '51691230fcf4cfd07729acff525de150', 1, 1, 1425, 'Confirmer Clôture', 0, '1', '2018-04-29 17:47:08'),
(41613, 865, 'c6c0ab5988aab9c83960e0a9f261bfae', 1, 1, 1426, 'Détails ticket', 0, '1', '2018-04-29 17:47:08'),
(41614, 865, 'f0ec59382bfa1553e2acb2cee8f0e8bf', 1, 1, 1427, 'Réaffecter ticket', 0, '1', '2018-04-29 17:47:08'),
(41615, 865, '13a62e53e80790158f2d898c2ade40de', 1, 1, 1428, 'Détails ticket', 0, '1', '2018-04-29 17:47:08'),
(41616, 866, '62191d5df0e93231d769d2b17e3b2d68', 1, 1, 1429, 'Ajouter ticket', 1, '1', '2018-04-29 17:47:08'),
(41617, 867, 'f0a2162636fb154c6f9361b86acd87d3', 1, 1, 1430, 'Modifier ticket', 1, '1', '2018-04-29 17:47:08'),
(41618, 868, '962353cac56b445aa4cc0073d1fa3b21', 1, 1, 1431, 'Supprimer ticket', 1, '1', '2018-04-29 17:47:08'),
(41619, 869, '9f7fa9f4122d308b77bd17e2b57bdd3e', 1, 1, 1432, 'Reafecter technicien', 1, '1', '2018-04-29 17:47:08'),
(41620, 870, 'ebe36becbc7f17cb4157d27d62ca7c45', 1, 1, 1433, 'Affecter ticket', 1, '1', '2018-04-29 17:47:08'),
(41621, 871, '73a480d433c6b5e4d130e83eb8d734f8', 1, 1, 1434, 'Détails ticket', 1, '1', '2018-04-29 17:47:08'),
(41622, 872, 'f496ab30882773264b90dd95dac97e07', 1, 1, 1435, 'Réaffecter ticket', 1, '1', '2018-04-29 17:47:08'),
(41623, 873, 'aea1d98c0b188c62f976936454e99bf2', 1, 1, 1436, 'Ajouter action', 1, '1', '2018-04-29 17:47:08'),
(41624, 874, '4f9403ccb1cf450f3ee7dc695e9d8f77', 1, 1, 1437, 'supprimer action ticket', 0, '1', '2018-04-29 17:47:08'),
(41625, 875, '7ee7f4b765ab9f1ac4f2ef7a5a34b988', 1, 1, 1438, 'Modifier action', 1, '1', '2018-04-29 17:47:08'),
(41626, 876, 'a4dd36b475e19e7f31667939ef1c5cf5', 1, 1, 1439, 'Résolution Réussi', 1, '1', '2018-04-29 17:47:08'),
(41627, 877, '775d77d66d847962bc363e3c8500004c', 1, 1, 1440, 'Ticket cloturé', 1, '1', '2018-04-29 17:47:08'),
(41628, 878, 'ca13381e080bc38cc9505039f618df36', 1, 1, 1441, 'Détails action', 1, '1', '2018-04-29 17:47:08'),
(41629, 460, 'b6b6bfbd070b5b3dd84acedae7b854e9', 1, 1, 660, 'Gestion des types de produits', 0, '1', '2018-04-29 17:47:08'),
(41630, 460, '3c5400b775264499825a039d66aa9c90', 1, 1, 661, 'Modifier type', 0, '1', '2018-04-29 17:47:08'),
(41631, 460, 'dcf55bc300d690af4c81e4d2335e60e5', 1, 1, 662, 'Valider type', 0, '1', '2018-04-29 17:47:08'),
(41632, 460, '230b9554d37da1c71986af94962cb340', 1, 1, 663, 'Désactiver type', 0, '1', '2018-04-29 17:47:08'),
(41633, 461, 'e0d163499b4ba11d6d7a648bc6fc6de6', 1, 1, 664, 'Ajouter un type', 0, '1', '2018-04-29 17:47:08'),
(41634, 462, 'ac5a6d087b3c8db7501fa5137a47773e', 1, 1, 665, 'Modifier type', 0, '1', '2018-04-29 17:47:08'),
(41635, 463, '2e8242a93a62a264ad7cfc953967f575', 1, 1, 666, 'Valider type', 0, '1', '2018-04-29 17:47:08'),
(41636, 464, 'e3725ba15ca483b9278f68553eca5918', 1, 1, 667, 'Supprimer type', 0, '1', '2018-04-29 17:47:08'),
(41637, 480, '312fd18860781a7b1b7e33587fa423d4', 1, 1, 692, 'Gestion Type Echeance', 0, '1', '2018-04-29 17:47:08'),
(41638, 480, '46ad76148075d6b458f43e84ddf00791', 1, 1, 697, 'Editer Type Echéance', 0, '1', '2018-04-29 17:47:08'),
(41639, 480, 'add2bf057924e606653fbf5bbd65ca09', 1, 1, 698, 'Valider Type Echéance', 0, '1', '2018-04-29 17:47:08'),
(41640, 480, '463d9e1e8367736b958f0dd84b4e36d5', 1, 1, 699, 'Désactiver Type Echéance', 0, '1', '2018-04-29 17:47:08'),
(41641, 481, '76170b14c7b6f1f7058d079fe24f739b', 1, 1, 693, 'Ajouter Type Echéance', 0, '1', '2018-04-29 17:47:08'),
(41642, 482, 'decc5ed58c4d91e6967c9c67e0975cf0', 1, 1, 694, 'Editer Type Echéance', 0, '1', '2018-04-29 17:47:08'),
(41643, 483, '89db6f23dd8e96a69c6a97f556c44e14', 1, 1, 695, 'Supprimer Type Echéance', 0, '1', '2018-04-29 17:47:08'),
(41644, 484, '7527021168823e0118d44297c7684d44', 1, 1, 696, 'Valider Type Echéance', 0, '1', '2018-04-29 17:47:08'),
(41645, 465, '55ecbb545a49c70c0b728bb0c7951077', 1, 1, 668, 'Gestion des unités de vente', 0, '1', '2018-04-29 17:47:08'),
(41646, 465, '67acd70eb04242b7091d9dcbb08295d7', 1, 1, 669, 'Modifier unité ', 0, '1', '2018-04-29 17:47:08'),
(41647, 465, '7363022ed5ad047bfe86d3de4b75b1f4', 1, 1, 670, 'Valider unité', 0, '1', '2018-04-29 17:47:08'),
(41648, 465, 'ec77eb95736c27bfc269cbffc8e113f1', 1, 1, 671, 'Désactiver unité', 0, '1', '2018-04-29 17:47:08'),
(41649, 466, '3a5e8dfe211121eda706f8b6d548d111', 1, 1, 672, 'ajouter une unité', 0, '1', '2018-04-29 17:47:08'),
(41650, 467, '9b7a578981de699286376903e96bc3c7', 1, 1, 673, 'Modifier une unité', 0, '1', '2018-04-29 17:47:08'),
(41651, 468, '62355588366c13ddbadc7a7ca1d226ad', 1, 1, 674, 'Valider une unité', 0, '1', '2018-04-29 17:47:08'),
(41652, 469, 'e5f53a3aaa324415d781156396938101', 1, 1, 675, 'Supprimer une unité', 0, '1', '2018-04-29 17:47:08'),
(41653, 709, '56de23d30d6c54297c8d9772cd3c7f57', 1, 1, 1115, 'Utilisateurs', 1, '1', '2018-04-29 17:47:08'),
(41654, 709, 'e656756fb7b39a4e6ddcabca75ff2970', 1, 1, 1116, 'Editer Utilisateur', 0, '1', '2018-04-29 17:47:08'),
(41655, 709, 'c073a277957ca1b9f318ac3902555708', 1, 1, 1117, 'Permissions', 0, '1', '2018-04-29 17:47:08'),
(41656, 709, 'c51499ddf7007787c4434661c658bbd1', 1, 1, 1118, 'Désactiver compte', 0, '1', '2018-04-29 17:47:08'),
(41657, 709, '10096b6f54456bcfc85081523ee64cf6', 1, 1, 1119, 'Supprimer utilisateur', 0, '1', '2018-04-29 17:47:08'),
(41658, 709, 'a0999cbed820aff775adf27276ee54a4', 1, 1, 1120, 'Editer Utilisateur', 0, '1', '2018-04-29 17:47:08'),
(41659, 709, '9aa6877656339ddff2478b20449a924b', 1, 1, 1121, 'Activer compte', 0, '1', '2018-04-29 17:47:08'),
(41660, 709, 'f4c79bb797b92dfa826b51a44e3171af', 1, 1, 1122, 'Utilisateurs', 0, '1', '2018-04-29 17:47:08'),
(41661, 709, 'd7f7afd70a297e5c239f6cf271138390', 1, 1, 1123, 'Utilisateur Archivé', 0, '1', '2018-04-29 17:47:08'),
(41662, 709, '17c98287fb82388423e04d24404cf662', 1, 1, 1124, 'Permissions', 0, '1', '2018-04-29 17:47:08'),
(41663, 709, '2c1c5556f30585c5b7c93fbbeaa98595', 1, 1, 1125, 'Historique session', 0, '1', '2018-04-29 17:47:08'),
(41664, 709, '7fd4ca84d162b70d3a4f0bad73e0e4b3', 1, 1, 1126, 'Liste Activitées', 0, '1', '2018-04-29 17:47:08'),
(41665, 710, 'df91a8e6f8ee2cde64495fc0cc7d6c6f', 1, 1, 1127, 'Ajouter Utilisateurs', 1, '1', '2018-04-29 17:47:08'),
(41666, 711, '2bb46b52eab9eecbdbba35605da07234', 1, 1, 1128, 'Editer Utilisateurs', 1, '1', '2018-04-29 17:47:08'),
(41667, 712, '3f59a1326df27378304e142ab3bec090', 1, 1, 1129, 'Permission', 1, '1', '2018-04-29 17:47:08'),
(41668, 713, 'b919571c88d036f8889742a81a4f41fd', 1, 1, 1130, 'Supprimer utilisateur', 1, '1', '2018-04-29 17:47:08'),
(41669, 714, '38f89764a26e39ce029cd3132c12b2a5', 1, 1, 1131, 'Compte utilisateur', 1, '1', '2018-04-29 17:47:08'),
(41670, 715, 'f988a608f35a0bc551cb038b1706d207', 1, 1, 1132, 'Activer utilisateur', 1, '1', '2018-04-29 17:47:08'),
(41671, 716, 'b7b3a09fdd73a5b0a3e5ed8a2828f548', 1, 1, 1133, 'Désactiver l''utilisateur', 1, '1', '2018-04-29 17:47:08'),
(41672, 717, '0d374b7e2fe21a2e2641c092a3c7f2e9', 1, 1, 1134, 'Changer le mot de passe', 1, '1', '2018-04-29 17:47:08'),
(41673, 718, '6f642ee30722158f0318653b9113b887', 1, 1, 1135, 'History', 1, '1', '2018-04-29 17:47:08'),
(41674, 719, 'cc907fac13631903d129c137d671d718', 1, 1, 1136, 'Activities', 1, '1', '2018-04-29 17:47:08'),
(41675, 430, '3d4eaa53061f51b0c4435bd8e4b89c17', 1, 1, 611, 'Gestion Vente', 0, '1', '2018-04-29 17:47:08'),
(41676, 89, '2c3b01c696ff401a2ac9ffedb7a06e4a', 1, 1, 114, 'Gestion Villes', 1, '1', '2018-04-29 17:47:08'),
(41677, 89, 'b9649163b368f863a0e8036f11cd81ae', 1, 1, 119, 'Editer Ville', 0, '1', '2018-04-29 17:47:08'),
(41678, 89, '89dec6dabcb210cdb9dd28bbef90d43e', 1, 1, 121, 'Editer Ville', 0, '1', '2018-04-29 17:47:08'),
(41679, 89, '4a2edbdcbda34c9d3d1e6abe73643b37', 1, 1, 602, 'Valider Ville', 0, '1', '2018-04-29 17:47:08'),
(41680, 89, '0c8ad6595a4516be83ba5a9cdb7ea9a1', 1, 1, 603, 'Désactiver Ville', 0, '1', '2018-04-29 17:47:08'),
(41681, 90, 'e152b9052d3dcfcac593489dbdc0f61c', 1, 1, 115, 'Ajouter ville', 1, '1', '2018-04-29 17:47:08'),
(41682, 91, '3107e0cd0e0df14c4e94aa088e4457d7', 1, 1, 116, 'Editer Ville', 1, '1', '2018-04-29 17:47:08'),
(41683, 92, 'da79d9214ed5819d7f4f1e3070629a3d', 1, 1, 117, 'Supprimer Ville', 1, '1', '2018-04-29 17:47:08'),
(41684, 423, 'fe03a68d17c62ff2c27329573a1b3550', 1, 1, 601, 'Valider Ville', 0, '1', '2018-04-29 17:47:08');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Table store rules for each user for each App and action' AUTO_INCREMENT=32 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

--
-- Contenu de la table `services`
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=292 ;

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
(170, 'd28a271d1c555c3adb39164d2ae667f2', 'admin', '2017-10-17 13:06:18', '2017-10-17 22:53:13', '37.208.61.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 1),
(171, '3e5127c02bdc36d7db1ba2a31e5fae56', 'ali', '2017-10-17 15:17:56', '2017-10-17 15:26:07', '37.208.61.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:53.0) Gecko/20100101 Firefox/53.0', 24),
(172, '7d8241072df21cd7b873ecfaa614a818', 'ayoubadmin', '2017-10-17 15:25:01', '2017-10-17 19:01:33', '105.154.179.34', 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:49.0) Gecko/20100101 Firefox/49.0', 23),
(173, '01e1c51dea6c5e99531e18181e0d48cf', 'ayoubadmin', '2017-10-17 19:01:33', NULL, '105.154.179.34', 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:49.0) Gecko/20100101 Firefox/49.0', 23),
(174, 'dde192b2e889c374087900e2602999b2', 'admin', '2017-10-17 22:53:13', '2017-10-18 14:28:09', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 1),
(175, '9ff7af23f1110c821073727faac72242', 'admin', '2017-10-18 14:28:22', '2017-10-18 21:27:07', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 1),
(176, 'c25ed89058d1ef6a258b21b1941a911b', 'admin', '2017-10-18 21:27:10', '2017-10-19 12:52:17', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 1),
(177, '26f9e1831b3bc93febfc4c4b18e53b17', 'admin', '2017-10-19 12:52:20', '2017-10-19 20:56:03', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 1),
(178, '1bf1214e3b0dbb6256445c078e7f0c8b', 'admin', '2017-10-19 20:56:23', '2017-10-20 19:29:37', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 1),
(179, '5b0ee4524ad6fe330a29b98cd7c735a0', 'admin', '2017-10-20 19:46:50', '2017-10-29 11:20:07', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 1),
(180, 'd01417b66a85eafd49e288fb4d1757e4', 'admin', '2017-10-29 11:20:07', '2017-10-31 17:38:51', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 1),
(181, 'b82cf65a191dc8b002ce46a311395d8b', 'admin', '2017-10-31 17:38:58', '2017-10-31 20:11:05', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 1),
(182, '7115ca830e7b146dc7e55e06ec5c3836', 'admin', '2017-10-31 20:11:16', '2017-10-31 21:12:19', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 1),
(183, 'b39c2b395225a0ab614a3d67a8e54800', 'admin', '2017-10-31 23:31:37', '2017-11-01 12:24:32', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 1),
(184, 'c2930801db0a331101b41062e112f048', 'admin', '2017-11-06 09:21:04', '2017-11-06 10:01:52', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 1),
(185, '06e3d7594f7e22aa57e9f6ba125540bc', 'admin', '2017-11-06 10:01:52', '2017-11-06 11:05:21', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 1),
(186, '53826eee99156c7a13928f2e445b7016', 'admin', '2017-11-07 19:27:07', '2017-11-07 19:40:13', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 1),
(187, 'b227dca61d65f147217636869357b193', 'admin', '2017-11-07 19:40:13', '2017-11-07 19:41:55', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 1),
(188, 'a73908ea5ad39bf987aeb8c18653356d', 'admin', '2017-11-07 19:58:48', '2017-11-07 20:56:35', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 1),
(189, '55e164f3c165e1a8ed35728b297e2266', 'admin', '2017-11-12 17:50:34', '2017-11-12 17:52:28', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 1),
(190, 'dd82e66244e761a973c97298abac0a19', 'admin', '2017-11-12 18:21:42', '2017-11-12 18:22:42', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 1),
(191, 'e4773096101f4ab3923194c69bc2b2e5', 'admin', '2017-11-12 18:30:35', '2017-11-12 18:32:05', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 1),
(192, '1be1e2bd6bd4a46533d76d4868e25be1', 'admin', '2017-11-16 20:37:10', '2017-11-16 20:43:20', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 1),
(193, '5a406ae1a903ff9e424fbdc75ffd85f2', 'admin', '2017-11-16 20:58:22', '2017-11-16 21:02:53', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0', 1),
(194, '697bff311a1e4bc0a16d81a280a32318', 'admin', '2017-11-19 18:32:26', '2017-11-19 19:13:27', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0', 1),
(195, 'ec09b606233799c237feb771ae9c453e', 'admin', '2017-11-19 19:13:37', '2017-11-19 19:14:24', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0', 1),
(196, 'a086c71c2726fde1c15c9a3623b65bfa', 'admin', '2017-11-19 19:15:05', '2017-11-19 19:16:02', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0', 1),
(197, '25d2f1655cee7b21c3644c8b92f9f3fc', 'admin', '2017-12-02 11:35:30', '2017-12-02 12:24:11', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0', 1),
(198, '02e8d0d73a490420f3afd57a3917c42f', 'admin', '2017-12-31 20:19:17', '2017-12-31 20:19:31', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0', 1),
(199, 'b8bac66061a8a7aac09c7fae55c536c9', 'admin', '2017-12-31 20:19:31', '2017-12-31 23:22:51', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0', 1),
(200, 'cb26955ab9ef67546ad1ad07d0f2d5af', 'admin', '2018-01-01 13:10:37', '2018-01-01 14:11:43', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0', 1),
(201, 'f094638ff13185b258b7e24abd82a752', 'admin', '2018-01-01 19:22:31', '2018-01-02 19:24:44', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0', 1),
(202, 'a5b0c6cf57b7a05e9a44e9b813c7ec95', 'admin', '2018-01-02 19:24:52', '2018-01-03 11:28:55', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0', 1),
(203, 'a1f6ea2bcc790f93f88b24a8498af80a', 'admin', '2018-01-03 11:29:03', '2018-01-03 18:37:32', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0', 1),
(204, 'a3d1f1f415ca09077fe7d946e3b2b223', 'admin', '2018-01-03 18:38:34', '2018-01-04 21:10:30', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0', 1),
(205, '54fc529de91010f239da06eabfeb66c1', 'admin', '2018-01-04 21:10:34', '2018-01-05 19:58:31', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0', 1),
(206, '403ab6c5067164aa6209c6239443ca42', 'admin', '2018-01-05 19:58:34', '2018-01-07 17:32:43', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0', 1),
(207, '6951dbea46480dc8ddd85573f1c5fe7c', 'admin', '2018-01-07 17:32:46', '2018-01-08 12:22:34', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0', 1),
(208, '76e161a2db3a45f7404db44516788478', 'admin', '2018-01-08 12:22:37', '2018-01-08 21:02:03', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0', 1),
(209, 'dbf23fc1c4bc8eb340092147b4a5af78', 'admin', '2018-01-08 21:04:05', '2018-01-08 23:13:37', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0', 1),
(210, 'f839340aa50876bb6b7adaa66d0660e0', 'admin', '2018-01-08 23:13:42', '2018-01-11 20:09:40', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0', 1),
(211, '371cc9d920e0a6a7442e0d533fba2b7b', 'admin', '2018-01-11 20:09:40', '2018-01-11 23:20:31', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0', 1),
(212, '845c84c9ea7ee7ae40defabce5e44140', 'admin', '2018-01-11 23:20:39', '2018-01-12 10:44:33', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0', 1),
(213, 'dfd090822c7808888fd54ed91213069c', 'admin', '2018-01-12 10:45:39', '2018-01-12 22:21:39', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0', 1),
(214, 'b2fdf6c4c5b235f1cb9068ea7a30dc6c', 'admin', '2018-01-12 22:21:42', '2018-01-12 23:24:32', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0', 1),
(215, 'ec22c41bfac7bdc0438a9fba9014e8b6', 'admin', '2018-01-14 17:30:34', '2018-01-14 20:49:10', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0', 1),
(216, 'fdf65da68ba4c1f1333ccc143a7214a7', 'admin', '2018-01-14 20:50:39', '2018-01-14 23:09:28', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0', 1),
(217, '0fefac8517ba5bd0e1a615378e381e73', 'admin', '2018-01-14 23:09:42', '2018-01-15 19:13:42', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0', 1),
(218, 'adab321cccd47b0e3cbbc328aea1cd60', 'admin', '2018-01-15 19:13:42', '2018-01-15 22:24:49', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0', 1),
(219, 'da9db9923361c7050c7c2f0d158cc58c', 'admin', '2018-01-15 22:24:58', '2018-01-16 20:33:26', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0', 1),
(220, '1bf01cf827dff8078f8160bcfc7c73b9', 'admin', '2018-01-16 20:34:55', '2018-01-16 22:57:50', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0', 1),
(221, '1139d6776df0a4479cf8bc236ee5cbeb', 'admin', '2018-01-16 22:57:58', '2018-01-17 23:34:58', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0', 1),
(222, '697c17de8f372e79fd582c9239ce1d8a', 'admin', '2018-01-17 23:35:57', '2018-01-20 19:46:34', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0', 1),
(223, '7aa1161e21cefc1482cd119e761c776b', 'admin', '2018-01-20 19:46:42', '2018-01-20 21:56:08', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0', 1),
(224, '79a3bece4d5a63f9c39a6aecacf2423c', 'admin', '2018-01-20 21:56:16', '2018-01-21 01:11:35', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0', 1),
(225, '2d532ae0b727d377923a4c0a9daa185f', 'admin', '2018-01-21 01:11:44', '2018-01-21 17:00:21', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0', 1),
(226, '32d5bfbfdaeb2bb9b78e4f6fd3950dec', 'admin', '2018-01-21 17:00:31', '2018-01-21 21:56:54', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0', 1),
(227, '5aa2c4f14bb1496aedc3b829e03b2ab1', 'admin', '2018-01-21 21:56:54', '2018-01-22 23:18:33', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0', 1),
(228, '83d62a7243415b4b379a69e4753f8e9e', 'admin', '2018-01-22 23:18:38', '2018-01-23 22:35:35', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0', 1),
(229, '38abf55d2eeb32d6ad1585314f91b459', 'admin', '2018-01-23 22:44:49', '2018-01-24 07:37:54', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0', 1),
(230, '261dd74e9ad2d58ae2aa1821c38b6014', 'admin', '2018-01-24 07:38:04', '2018-01-24 23:13:24', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0', 1),
(231, '5713c20361951e63051f9cacb4d1ffea', 'admin', '2018-01-24 23:13:29', '2018-01-25 21:27:29', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0', 1),
(232, '04a8bee794ac9deb9060c2cddbf5b945', 'admin', '2018-01-25 21:27:43', '2018-01-25 22:57:20', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0', 1),
(233, '8fb163fd21e4442704f887549064ad71', 'admin', '2018-01-25 22:57:32', '2018-01-26 10:25:14', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0', 1),
(234, '15649857c98dad1bb6dbd40a11824b7f', 'admin', '2018-01-26 10:25:25', '2018-01-26 21:53:59', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0', 1),
(235, '8c948603c8cbb4de76b7ed62703ecbd2', 'admin', '2018-01-26 21:54:34', '2018-01-27 14:59:35', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0', 1),
(236, '1df4445fe0ea7028be14569f2b4b0811', 'admin', '2018-01-27 14:59:48', '2018-01-28 19:03:37', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0', 1),
(237, '35c21481653b04aaf430ddcadb87af42', 'admin', '2018-01-28 19:03:37', '2018-02-04 17:21:47', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:58.0) Gecko/20100101 Firefox/58.0', 1),
(238, '41d4ad83b85b177268080f2aa90cb9c7', 'admin', '2018-02-04 17:21:52', '2018-02-10 16:45:40', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:58.0) Gecko/20100101 Firefox/58.0', 1),
(239, '27e0cb22dda16161894a96572d14410a', 'admin', '2018-02-10 16:46:31', '2018-02-15 22:52:36', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:58.0) Gecko/20100101 Firefox/58.0', 1),
(240, 'e083b3f6a913bbb3c756595a41930f93', 'admin', '2018-02-15 22:52:36', '2018-02-24 16:05:27', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:58.0) Gecko/20100101 Firefox/58.0', 1),
(241, '50c605dbaf94debae979b248d6266bfe', 'admin', '2018-02-24 16:05:27', '2018-02-24 18:11:33', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:58.0) Gecko/20100101 Firefox/58.0', 1),
(242, 'caaabfbdbc22ab2acf979a5cc8932307', 'admin', '2018-02-24 18:12:02', '2018-02-24 19:48:45', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:58.0) Gecko/20100101 Firefox/58.0', 1),
(243, 'eacfc504336b694bd618a33f08b4c852', 'admin', '2018-02-24 23:43:46', '2018-02-25 09:47:58', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:58.0) Gecko/20100101 Firefox/58.0', 1),
(244, '08f21863d112665ff0f5a5a53478dbf6', 'admin', '2018-02-25 09:48:14', '2018-02-25 11:51:33', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:58.0) Gecko/20100101 Firefox/58.0', 1),
(245, '59bdce9d025eddbf1bc5b17244c19e38', 'admin', '2018-02-25 11:55:23', '2018-02-25 20:30:32', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:58.0) Gecko/20100101 Firefox/58.0', 1),
(246, 'c0b61e3d01e8e48da67e1ed7a72bc21f', 'admin', '2018-02-25 20:32:36', '2018-02-25 21:57:17', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:58.0) Gecko/20100101 Firefox/58.0', 1),
(247, 'adf2562b39aef8d51e0c6b06fdeb38e7', 'admin', '2018-02-25 21:57:57', '2018-02-26 10:55:34', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:58.0) Gecko/20100101 Firefox/58.0', 1),
(248, '6cbbb37ce204b4c8405309e5a6eea543', 'admin', '2018-02-26 10:55:43', '2018-02-26 22:05:54', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:58.0) Gecko/20100101 Firefox/58.0', 1),
(249, '31a34f2ec6e097d33a07904357a4de6d', 'admin', '2018-02-26 22:06:02', '2018-02-27 22:28:01', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:58.0) Gecko/20100101 Firefox/58.0', 1),
(250, '96784624e5b28a9117c0c6aa57df68eb', 'admin', '2018-02-27 22:28:11', '2018-02-28 10:23:53', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:58.0) Gecko/20100101 Firefox/58.0', 1),
(251, 'd7eba05b218057b9eb04d6fef1f6c27f', 'admin', '2018-02-28 10:23:58', '2018-02-28 10:58:00', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:58.0) Gecko/20100101 Firefox/58.0', 1),
(252, '14a153edf5424d1eb68ab3f140c7b6cb', 'admin', '2018-02-28 10:58:03', '2018-02-28 23:01:26', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:58.0) Gecko/20100101 Firefox/58.0', 1),
(253, 'f1b70ce5d54af1c83ce753cf8d156acb', 'admin', '2018-02-28 23:01:30', '2018-03-01 18:52:13', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:58.0) Gecko/20100101 Firefox/58.0', 1),
(254, '2e14012249013ba014c86e28e403e618', 'admin', '2018-03-01 18:53:33', '2018-03-01 23:23:14', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:58.0) Gecko/20100101 Firefox/58.0', 1),
(255, '9422a093af3a95e8923a458bbd73e5d3', 'admin', '2018-03-01 23:23:22', '2018-03-02 10:36:17', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:58.0) Gecko/20100101 Firefox/58.0', 1),
(256, 'a6401818e338e8c760d89f719892fcfb', 'admin', '2018-03-02 23:38:22', '2018-03-03 12:13:24', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:58.0) Gecko/20100101 Firefox/58.0', 1),
(257, '64402e7de525bc57d37f6eb7cddb1116', 'admin', '2018-03-03 12:13:24', '2018-03-03 12:58:19', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:58.0) Gecko/20100101 Firefox/58.0', 1);
INSERT INTO `session` (`id_sys`, `id`, `user`, `dat`, `expir`, `ip`, `browser`, `userid`) VALUES
(258, '08ec6defee6bb62d65ea0f06f0ad3b4f', 'admin', '2018-03-03 12:58:19', '2018-03-03 17:34:36', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:58.0) Gecko/20100101 Firefox/58.0', 1),
(259, 'ed1b3c27641b906a0f7316f1cc36b1f7', 'admin', '2018-03-03 17:34:41', '2018-03-03 21:53:51', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:58.0) Gecko/20100101 Firefox/58.0', 1),
(260, '0f9c093682bc70767da9f7e1a3071dbf', 'admin', '2018-03-03 21:54:03', '2018-03-04 10:31:01', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:58.0) Gecko/20100101 Firefox/58.0', 1),
(261, '5e091e15a324c199583b130e28486361', 'admin', '2018-03-04 10:31:08', '2018-03-04 18:09:43', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:58.0) Gecko/20100101 Firefox/58.0', 1),
(262, 'e7d0c96867a9c3141aaf60b10c28cad4', 'admin', '2018-03-04 18:09:43', '2018-03-04 21:20:31', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:58.0) Gecko/20100101 Firefox/58.0', 1),
(263, 'deaaac525f0a37a00630b76a906c5dcb', 'admin', '2018-03-04 21:20:38', '2018-03-05 17:01:20', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:58.0) Gecko/20100101 Firefox/58.0', 1),
(264, '47b100bf52b22116e89f21b105a5dc63', 'admin', '2018-03-05 17:07:55', '2018-03-05 20:16:29', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:58.0) Gecko/20100101 Firefox/58.0', 1),
(265, '39a191eeb0861b4a864470293a0870e0', 'admin', '2018-03-05 20:16:37', '2018-03-05 22:54:52', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:58.0) Gecko/20100101 Firefox/58.0', 1),
(266, 'c2b2f5f1770577afd6af68df739f89c8', 'admin', '2018-03-05 22:54:56', '2018-03-19 23:18:11', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:58.0) Gecko/20100101 Firefox/58.0', 1),
(267, '8e9acec19d3af26ee9fd8f58aed85fae', 'admin', '2018-03-19 23:18:11', '2018-03-26 19:26:28', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:58.0) Gecko/20100101 Firefox/58.0', 1),
(268, '44c5065205607e94c8289b50513a21b4', 'admin', '2018-03-26 19:26:28', '2018-04-03 19:52:09', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:59.0) Gecko/20100101 Firefox/59.0', 1),
(269, '514c0b04b734275b118606b46adbd24d', 'admin', '2018-04-03 19:52:09', '2018-04-05 19:34:58', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:59.0) Gecko/20100101 Firefox/59.0', 1),
(270, '78fbc6cac43a996c6cab93917f78f105', 'admin', '2018-04-05 19:35:06', '2018-04-05 21:51:29', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:59.0) Gecko/20100101 Firefox/59.0', 1),
(271, 'c446b463f98b70f8b93b9885d60b2445', 'admin', '2018-04-05 21:52:13', '2018-04-06 20:02:32', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:59.0) Gecko/20100101 Firefox/59.0', 1),
(272, 'b5d28edfcf259922637da1dc8ced1c9c', 'admin', '2018-04-06 20:02:37', '2018-04-13 14:00:00', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:59.0) Gecko/20100101 Firefox/59.0', 1),
(273, '350e4f1d1bb5919c18a37eebaf785461', 'admin', '2018-04-13 14:00:05', '2018-04-16 18:09:53', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:59.0) Gecko/20100101 Firefox/59.0', 1),
(274, 'cab0f9243f2173a56ab6dcbf60719ce7', 'admin', '2018-04-16 18:09:53', '2018-04-19 21:51:46', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:59.0) Gecko/20100101 Firefox/59.0', 1),
(275, 'bab638e19d821f73130c100c7b1a6c77', 'admin', '2018-04-19 21:51:54', '2018-04-19 21:56:02', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:59.0) Gecko/20100101 Firefox/59.0', 1),
(276, 'c4924a5cb0791f7327dce5f6133fa428', 'admin', '2018-04-19 21:56:05', '2018-04-19 21:56:38', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:59.0) Gecko/20100101 Firefox/59.0', 1),
(277, '6a55af691293016be046e52f50b062e5', 'admin', '2018-04-19 21:56:40', '2018-04-19 21:58:04', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:59.0) Gecko/20100101 Firefox/59.0', 1),
(278, '0383a31312826f353b64e751bf0dd463', 'admin', '2018-04-19 21:58:06', '2018-04-20 18:50:31', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:59.0) Gecko/20100101 Firefox/59.0', 1),
(279, '225a6e3b4f26b211a5f3dde1c1f68678', 'admin', '2018-04-21 18:01:03', '2018-04-22 00:01:04', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:59.0) Gecko/20100101 Firefox/59.0', 1),
(280, '05803aaa78697673f75c4b19de521281', 'admin', '2018-04-23 18:32:58', '2018-04-24 16:49:57', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:59.0) Gecko/20100101 Firefox/59.0', 1),
(281, 'fafaee0174d110578d50ab66e33a1afc', 'admin', '2018-04-24 17:19:42', '2018-04-25 13:41:43', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:59.0) Gecko/20100101 Firefox/59.0', 1),
(282, '5936e0f9fb0b7434c2d38fcb04f612bf', 'admin', '2018-04-25 16:42:18', '2018-04-25 21:24:43', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:59.0) Gecko/20100101 Firefox/59.0', 1),
(283, '8cc8d19824b332ed22a2919b73a73254', 'admin', '2018-04-25 21:25:04', '2018-04-26 17:13:13', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:59.0) Gecko/20100101 Firefox/59.0', 1),
(284, 'c810d5c2e57a655a6682b609ccb16352', 'admin', '2018-04-26 17:19:26', '2018-04-26 21:05:29', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:59.0) Gecko/20100101 Firefox/59.0', 1),
(285, '79e4fb7cd99ef94c40c15a2babb38bef', 'admin', '2018-04-26 21:05:37', '2018-04-27 20:20:51', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:59.0) Gecko/20100101 Firefox/59.0', 1),
(286, '5e32683061fbbddb3a8a5ff594d18d5d', 'admin', '2018-04-27 20:21:16', '2018-04-29 09:37:14', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:59.0) Gecko/20100101 Firefox/59.0', 1),
(287, 'd548a3c85edc526f4fe07116d18993cf', 'admin', '2018-04-29 09:37:19', '2018-04-29 14:11:27', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:59.0) Gecko/20100101 Firefox/59.0', 1),
(288, 'a14273369334b5cabb412690617c410e', 'admin', '2018-04-29 14:11:41', '2018-04-29 17:46:39', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:59.0) Gecko/20100101 Firefox/59.0', 1),
(289, '912950ce755130f82b18d9cdfbac1d99', 'admin', '2018-04-29 17:46:45', '2018-04-30 23:02:44', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:59.0) Gecko/20100101 Firefox/59.0', 1),
(290, '1f2144884ebe69160e0317c3b4af08a8', 'admin', '2018-05-01 00:08:13', '2018-05-01 22:08:07', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:59.0) Gecko/20100101 Firefox/59.0', 1),
(291, 'aeb26fa670c3803ab48e563f23d4d1f4', 'admin', '2018-05-02 09:39:12', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:59.0) Gecko/20100101 Firefox/59.0', 1);

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
(1, 'Global-Tech', '1656', 'Avenue Charles de Gaulle, ', 'N''Djamena', 'Tchad', '(+235) 66324513 / 22514044', NULL, 'contact@globaltech.td', '9016442Y', 'TC-ABC-B026/014', 'www.globaltech.td', '1', NULL, NULL, '2017-10-17 13:31:27');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=56 ;

--
-- Contenu de la table `stock`
--

INSERT INTO `stock` (`id`, `idproduit`, `qte`, `prix_achat`, `prix_vente`, `date_achat`, `date_validite`, `mouvement`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(49, 22, 5, 150000, 200000, '2017-10-11', '2017-10-11', 'E', 0, 19, '2017-10-11 11:49:04', NULL, NULL),
(50, 22, 5, 100000, 150000, '2017-10-11', '2020-12-31', 'E', 0, 19, '2017-10-11 12:57:05', NULL, NULL),
(51, 23, 100, 850000, 1120000, '2017-10-16', '2017-10-16', 'E', 0, 1, '2017-10-16 22:38:31', NULL, NULL),
(52, 24, 5, 500000, 700000, '2017-10-17', '2017-10-17', 'E', 1, 24, '2017-10-17 14:33:25', 24, '2017-10-17 14:34:00'),
(53, 29, 120, 1000, 20000, '2018-03-04', '2018-03-29', 'E', 1, 1, '2018-03-04 01:17:18', 1, '2018-03-04 01:17:36'),
(54, 30, 1, 650000, 750000, '2018-03-04', '2022-12-31', 'E', 1, 1, '2018-03-04 13:00:02', 1, '2018-03-04 13:00:24'),
(55, 22, -2, NULL, 200000, NULL, NULL, 'S', 1, 1, '2018-04-20 00:00:00', NULL, NULL);

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
  `sesid` varchar(32) DEFAULT NULL COMMENT 'Identifiant',
  `datlog` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'Date loggining',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=553 ;

--
-- Contenu de la table `sys_log`
--

INSERT INTO `sys_log` (`id`, `message`, `type_log`, `table_use`, `idm`, `user_exec`, `sesid`, `datlog`) VALUES
(1, 'Création utlisateur', 'Insert', 'users_sys', 19, 'admin', NULL, '2017-09-13 15:53:48'),
(2, 'Modification utlisateur', 'Update', 'users_sys', 19, 'admin', NULL, '2017-09-13 15:57:03'),
(3, 'Modification utlisateur', 'Update', 'users_sys', 18, 'admin', NULL, '2017-09-13 16:17:30'),
(4, 'Modification utlisateur', 'Update', 'users_sys', 18, 'admin', NULL, '2017-09-13 16:18:15'),
(5, 'Modification utlisateur', 'Update', 'users_sys', 18, 'admin', NULL, '2017-09-13 16:28:38'),
(6, 'Insertion produit', 'Insert', 'produits', 21, 'admin', NULL, '2017-10-09 20:00:34'),
(7, 'Insertion produit', 'Insert', 'produits', 22, 'admin', NULL, '2017-10-09 20:02:14'),
(8, 'Création catégorie client', 'Insert', 'categorie_client', 2, 'admin', NULL, '2017-10-09 20:57:02'),
(9, 'Suppression catégorie client', 'Delete', 'categorie_client', 2, 'admin', NULL, '2017-10-09 20:57:07'),
(10, 'Insertion catégorie produit', 'Insert', 'ref_categories_produits', 8, 'admin', NULL, '2017-10-09 20:58:34'),
(11, 'Modification catégorie produit', 'Update', 'ref_categories_produits', 8, 'admin', NULL, '2017-10-09 20:58:39'),
(12, 'Suppression catégorie produit', 'Delete', 'ref_categories_produits', 8, 'admin', NULL, '2017-10-09 20:58:44'),
(13, 'Insertion type produit', 'Insert', 'ref_types_produits', 4, 'admin', NULL, '2017-10-09 21:04:01'),
(14, 'Modification type produit', 'Update', 'ref_types_produits', 4, 'admin', NULL, '2017-10-09 21:04:08'),
(15, 'Suppression type produit', 'Delete', 'ref_types_produits', 4, 'admin', NULL, '2017-10-09 21:04:12'),
(16, 'Insertion unité de vente', 'Insert', 'ref_unites_vente', 3, 'admin', NULL, '2017-10-09 21:05:17'),
(17, 'Modification unité de vente', 'Update', 'ref_unites_vente', 3, 'admin', NULL, '2017-10-09 21:05:21'),
(18, 'Suppression unité de vente', 'Delete', 'ref_unites_vente', 3, 'admin', NULL, '2017-10-09 21:05:26'),
(19, 'Modification produit', 'Update', 'produits', 22, 'admin', NULL, '2017-10-13 01:05:22'),
(20, 'Modification contrat abonnement', 'Update', 'contrats', 34, 'admin', NULL, '2017-10-13 01:05:52'),
(21, 'Modification contrat abonnement', 'Update', 'contrats', 34, 'admin', NULL, '2017-10-13 01:06:04'),
(22, 'Modification contrat abonnement', 'Update', 'contrats', 34, 'admin', NULL, '2017-10-13 01:06:37'),
(23, 'Modification contrat abonnement', 'Update', 'contrats', 34, 'admin', NULL, '2017-10-13 01:09:07'),
(24, 'Modification fournisseur', 'Update', 'fournisseurs', 29, 'admin', NULL, '2017-10-13 01:14:41'),
(25, 'Modification fournisseur', 'Update', 'fournisseurs', 29, 'admin', NULL, '2017-10-13 01:17:52'),
(26, 'Validation fournisseur', 'Validate', 'fournisseurs', 29, 'admin', NULL, '2017-10-13 01:17:57'),
(27, 'Modification contrat fournisseur', 'Update', 'contrats_frn', 37, 'admin', NULL, '2017-10-13 01:18:21'),
(28, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 39, 'admin', 'c4e5c5a21a6acc161ab3e5cb68b141b9', '2017-10-17 19:59:58'),
(29, 'Modification échéance contrat abonnement', 'Update', 'echeances_contrat', 39, 'admin', 'c4e5c5a21a6acc161ab3e5cb68b141b9', '2017-10-17 20:02:51'),
(30, 'Modification échéance contrat abonnement', 'Update', 'echeances_contrat', 39, 'admin', 'c4e5c5a21a6acc161ab3e5cb68b141b9', '2017-10-17 20:03:51'),
(31, 'Insertion contrat abonnement', 'Insert', 'contrats', 35, 'admin', 'c4e5c5a21a6acc161ab3e5cb68b141b9', '2017-10-17 20:03:54'),
(32, 'Modification contrat abonnement', 'Update', 'contrats', 35, 'admin', 'c4e5c5a21a6acc161ab3e5cb68b141b9', '2017-10-17 20:04:31'),
(33, 'Modification échéance contrat abonnement', 'Update', 'echeances_contrat', 39, 'admin', 'cb8d65074fdfa7372224ff6828c25868', '2017-10-17 21:16:39'),
(34, 'Modification échéance contrat abonnement', 'Update', 'echeances_contrat', 39, 'admin', 'cb8d65074fdfa7372224ff6828c25868', '2017-10-17 21:16:49'),
(35, 'Suppression contrat abonnement', 'Delete', 'contrats', 35, 'admin', 'cb8d65074fdfa7372224ff6828c25868', '2017-10-17 21:17:12'),
(36, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 40, 'admin', 'cb8d65074fdfa7372224ff6828c25868', '2017-10-17 21:17:49'),
(37, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 41, 'admin', 'cb8d65074fdfa7372224ff6828c25868', '2017-10-17 21:22:45'),
(38, 'Insertion contrat abonnement', 'Insert', 'contrats', 36, 'admin', 'cb8d65074fdfa7372224ff6828c25868', '2017-10-17 21:22:47'),
(39, 'Modification contrat fournisseur', 'Update', 'contrats_frn', 40, 'admin', '1be1e2bd6bd4a46533d76d4868e25be1', '2017-11-16 20:37:35'),
(40, 'Modification contrat fournisseur', 'Update', 'contrats_frn', 40, 'admin', '1be1e2bd6bd4a46533d76d4868e25be1', '2017-11-16 20:37:59'),
(41, 'Modification contrat fournisseur', 'Update', 'contrats_frn', 40, 'admin', '5a406ae1a903ff9e424fbdc75ffd85f2', '2017-11-16 20:59:01'),
(42, 'Modification contrat fournisseur', 'Update', 'contrats_frn', 40, 'admin', '5a406ae1a903ff9e424fbdc75ffd85f2', '2017-11-16 20:59:28'),
(43, 'Modification contrat fournisseur', 'Update', 'contrats_frn', 40, 'admin', '5a406ae1a903ff9e424fbdc75ffd85f2', '2017-11-16 20:59:45'),
(44, 'Création client', 'Insert', 'clients', 36, 'admin', '25d2f1655cee7b21c3644c8b92f9f3fc', '2017-12-02 12:12:11'),
(45, 'Création client', 'Insert', 'clients', 37, 'admin', '25d2f1655cee7b21c3644c8b92f9f3fc', '2017-12-02 12:14:35'),
(46, 'Modification client', 'Update', 'clients', 37, 'admin', '25d2f1655cee7b21c3644c8b92f9f3fc', '2017-12-02 12:19:08'),
(47, 'Modification client', 'Update', 'clients', 37, 'admin', 'a1f6ea2bcc790f93f88b24a8498af80a', '2018-01-03 11:50:50'),
(48, 'Validation client', 'Validate', 'clients', 37, 'admin', 'a1f6ea2bcc790f93f88b24a8498af80a', '2018-01-03 11:50:58'),
(49, 'Enregistrement Détail Devis 169', 'Insert', 'd_devis', 169, 'admin', 'a3d1f1f415ca09077fe7d946e3b2b223', '2018-01-03 20:05:53'),
(50, 'Enregistrement Détail Devis 170', 'Insert', 'd_devis', 170, 'admin', 'a3d1f1f415ca09077fe7d946e3b2b223', '2018-01-03 20:11:58'),
(51, 'Enregistrement Détail Devis 171', 'Insert', 'd_devis', 171, 'admin', 'a3d1f1f415ca09077fe7d946e3b2b223', '2018-01-03 21:21:07'),
(52, 'Enregistrement Devis 44', 'Insert', 'devis', 44, 'admin', 'a3d1f1f415ca09077fe7d946e3b2b223', '2018-01-03 21:23:20'),
(53, 'Enregistrement Détail Devis 172', 'Insert', 'd_devis', 172, 'admin', 'a3d1f1f415ca09077fe7d946e3b2b223', '2018-01-03 23:38:00'),
(54, 'Enregistrement Détail Devis 173', 'Insert', 'd_devis', 173, 'admin', '403ab6c5067164aa6209c6239443ca42', '2018-01-05 20:21:48'),
(55, 'Enregistrement Détail Devis 174', 'Insert', 'd_devis', 174, 'admin', '403ab6c5067164aa6209c6239443ca42', '2018-01-05 20:24:10'),
(56, 'Enregistrement Détail Devis 175', 'Insert', 'd_devis', 175, 'admin', '403ab6c5067164aa6209c6239443ca42', '2018-01-05 20:27:38'),
(57, 'Enregistrement Détail Devis 176', 'Insert', 'd_devis', 176, 'admin', '403ab6c5067164aa6209c6239443ca42', '2018-01-05 20:55:31'),
(58, 'Enregistrement Détail Devis 177', 'Insert', 'd_devis', 177, 'admin', '403ab6c5067164aa6209c6239443ca42', '2018-01-05 21:07:53'),
(59, 'Enregistrement Détail Devis 178', 'Insert', 'd_devis', 178, 'admin', '403ab6c5067164aa6209c6239443ca42', '2018-01-05 21:12:20'),
(60, 'Enregistrement Détail Devis 179', 'Insert', 'd_devis', 179, 'admin', '403ab6c5067164aa6209c6239443ca42', '2018-01-05 21:22:15'),
(61, 'Enregistrement Détail Devis 180', 'Insert', 'd_devis', 180, 'admin', '403ab6c5067164aa6209c6239443ca42', '2018-01-05 21:54:53'),
(62, 'Enregistrement Détail Devis 181', 'Insert', 'd_devis', 181, 'admin', '403ab6c5067164aa6209c6239443ca42', '2018-01-05 21:55:56'),
(63, 'Enregistrement Devis 45', 'Insert', 'devis', 45, 'admin', '403ab6c5067164aa6209c6239443ca42', '2018-01-05 23:01:09'),
(64, 'Modification Devis 45', 'Update', 'devis', 45, 'admin', '403ab6c5067164aa6209c6239443ca42', '2018-01-05 23:15:06'),
(65, 'Modification Devis 45', 'Update', 'devis', 45, 'admin', '403ab6c5067164aa6209c6239443ca42', '2018-01-05 23:15:22'),
(66, 'Enregistrement Détail Devis 182', 'Insert', 'd_devis', 182, 'admin', '76e161a2db3a45f7404db44516788478', '2018-01-08 12:25:33'),
(67, 'Enregistrement Détail Devis 183', 'Insert', 'd_devis', 183, 'admin', '76e161a2db3a45f7404db44516788478', '2018-01-08 12:30:03'),
(68, 'Enregistrement Détail Devis 184', 'Insert', 'd_devis', 184, 'admin', '76e161a2db3a45f7404db44516788478', '2018-01-08 12:33:17'),
(69, 'Enregistrement Détail Devis 185', 'Insert', 'd_devis', 185, 'admin', '76e161a2db3a45f7404db44516788478', '2018-01-08 12:35:21'),
(70, 'Enregistrement Détail Devis 186', 'Insert', 'd_devis', 186, 'admin', '76e161a2db3a45f7404db44516788478', '2018-01-08 12:41:09'),
(71, 'Enregistrement Détail Devis 187', 'Insert', 'd_devis', 187, 'admin', '76e161a2db3a45f7404db44516788478', '2018-01-08 12:51:52'),
(72, 'Enregistrement Détail Devis 188', 'Insert', 'd_devis', 188, 'admin', '76e161a2db3a45f7404db44516788478', '2018-01-08 12:55:10'),
(73, 'Enregistrement Détail Devis 189', 'Insert', 'd_devis', 189, 'admin', '76e161a2db3a45f7404db44516788478', '2018-01-08 12:56:41'),
(74, 'Enregistrement Détail Devis 190', 'Insert', 'd_devis', 190, 'admin', '76e161a2db3a45f7404db44516788478', '2018-01-08 12:57:52'),
(75, 'Enregistrement Détail Devis 191', 'Insert', 'd_devis', 191, 'admin', '76e161a2db3a45f7404db44516788478', '2018-01-08 12:59:46'),
(76, 'Enregistrement Détail Devis 192', 'Insert', 'd_devis', 192, 'admin', '76e161a2db3a45f7404db44516788478', '2018-01-08 13:01:36'),
(77, 'Enregistrement Détail Devis 193', 'Insert', 'd_devis', 193, 'admin', '76e161a2db3a45f7404db44516788478', '2018-01-08 13:03:15'),
(78, 'Enregistrement Détail Devis 194', 'Insert', 'd_devis', 194, 'admin', '76e161a2db3a45f7404db44516788478', '2018-01-08 13:03:55'),
(79, 'Enregistrement Détail Devis 195', 'Insert', 'd_devis', 195, 'admin', '76e161a2db3a45f7404db44516788478', '2018-01-08 13:06:04'),
(80, 'Enregistrement Détail Devis 196', 'Insert', 'd_devis', 196, 'admin', '76e161a2db3a45f7404db44516788478', '2018-01-08 13:20:07'),
(81, 'Enregistrement Détail Devis 197', 'Insert', 'd_devis', 197, 'admin', '76e161a2db3a45f7404db44516788478', '2018-01-08 14:00:24'),
(82, 'Enregistrement Détail Devis 198', 'Insert', 'd_devis', 198, 'admin', '76e161a2db3a45f7404db44516788478', '2018-01-08 14:04:51'),
(83, 'Enregistrement Détail Devis 199', 'Insert', 'd_devis', 199, 'admin', '76e161a2db3a45f7404db44516788478', '2018-01-08 14:11:27'),
(84, 'Enregistrement Détail Devis 200', 'Insert', 'd_devis', 200, 'admin', '76e161a2db3a45f7404db44516788478', '2018-01-08 14:13:58'),
(85, 'Enregistrement Détail Devis 201', 'Insert', 'd_devis', 201, 'admin', '76e161a2db3a45f7404db44516788478', '2018-01-08 14:39:12'),
(86, 'Enregistrement Détail Devis 202', 'Insert', 'd_devis', 202, 'admin', '76e161a2db3a45f7404db44516788478', '2018-01-08 14:42:12'),
(87, 'Enregistrement Détail Devis 203', 'Insert', 'd_devis', 203, 'admin', '76e161a2db3a45f7404db44516788478', '2018-01-08 16:55:26'),
(88, 'Modification Devis 45', 'Update', 'devis', 45, 'admin', '76e161a2db3a45f7404db44516788478', '2018-01-08 17:00:37'),
(89, 'Modification Devis 45', 'Update', 'devis', 45, 'admin', '76e161a2db3a45f7404db44516788478', '2018-01-08 17:01:01'),
(90, 'Enregistrement Détail Devis 204', 'Insert', 'd_devis', 204, 'admin', '76e161a2db3a45f7404db44516788478', '2018-01-08 17:10:26'),
(91, 'Enregistrement Devis 46', 'Insert', 'devis', 46, 'admin', '76e161a2db3a45f7404db44516788478', '2018-01-08 17:11:12'),
(92, 'Validation devis 46', 'Update', 'devis', 46, 'admin', '76e161a2db3a45f7404db44516788478', '2018-01-08 17:13:07'),
(93, 'Expédition devis 46', 'Update', 'devis', 46, 'admin', '76e161a2db3a45f7404db44516788478', '2018-01-08 17:13:16'),
(94, 'Validation client #Devis:46', 'Update', 'devis', 46, 'admin', '76e161a2db3a45f7404db44516788478', '2018-01-08 17:13:41'),
(95, 'Enregistrement Détail Devis 205', 'Insert', 'd_devis', 205, 'admin', '371cc9d920e0a6a7442e0d533fba2b7b', '2018-01-11 21:13:03'),
(96, 'Enregistrement Devis 47', 'Insert', 'devis', 47, 'admin', '371cc9d920e0a6a7442e0d533fba2b7b', '2018-01-11 21:13:22'),
(97, 'Suppression devis 47', 'Delete', 'devis', 47, 'admin', '371cc9d920e0a6a7442e0d533fba2b7b', '2018-01-11 21:16:40'),
(98, 'Enregistrement Détail Devis 206', 'Insert', 'd_devis', 206, 'admin', '371cc9d920e0a6a7442e0d533fba2b7b', '2018-01-11 21:17:01'),
(99, 'Enregistrement Devis 48', 'Insert', 'devis', 48, 'admin', '371cc9d920e0a6a7442e0d533fba2b7b', '2018-01-11 21:17:20'),
(100, 'Enregistrement Devis 49', 'Insert', 'devis', 49, 'admin', '371cc9d920e0a6a7442e0d533fba2b7b', '2018-01-11 21:17:52'),
(101, 'Enregistrement Devis 50', 'Insert', 'devis', 50, 'admin', '371cc9d920e0a6a7442e0d533fba2b7b', '2018-01-11 21:20:46'),
(102, 'Enregistrement Devis 51', 'Insert', 'devis', 51, 'admin', '371cc9d920e0a6a7442e0d533fba2b7b', '2018-01-11 21:22:28'),
(103, 'Enregistrement Devis 52', 'Insert', 'devis', 52, 'admin', '371cc9d920e0a6a7442e0d533fba2b7b', '2018-01-11 21:23:13'),
(104, 'Modification Devis 52', 'Update', 'devis', 52, 'admin', '845c84c9ea7ee7ae40defabce5e44140', '2018-01-11 23:22:29'),
(105, 'Enregistrement Détail Devis 207', 'Insert', 'd_devis', 207, 'admin', '845c84c9ea7ee7ae40defabce5e44140', '2018-01-11 23:29:29'),
(106, 'Enregistrement Détail Devis 208', 'Insert', 'd_devis', 208, 'admin', '845c84c9ea7ee7ae40defabce5e44140', '2018-01-11 23:33:03'),
(107, 'Enregistrement Détail Devis 209', 'Insert', 'd_devis', 209, 'admin', '845c84c9ea7ee7ae40defabce5e44140', '2018-01-11 23:36:08'),
(108, 'Enregistrement Détail Devis 210', 'Insert', 'd_devis', 210, 'admin', '845c84c9ea7ee7ae40defabce5e44140', '2018-01-11 23:41:17'),
(109, 'Enregistrement Détail Devis 211', 'Insert', 'd_devis', 211, 'admin', '845c84c9ea7ee7ae40defabce5e44140', '2018-01-11 23:42:23'),
(110, 'Enregistrement Détail Devis 212', 'Insert', 'd_devis', 212, 'admin', '845c84c9ea7ee7ae40defabce5e44140', '2018-01-11 23:46:07'),
(111, 'Enregistrement Détail Devis 213', 'Insert', 'd_devis', 213, 'admin', '845c84c9ea7ee7ae40defabce5e44140', '2018-01-11 23:49:23'),
(112, 'Enregistrement Détail Devis 214', 'Insert', 'd_devis', 214, 'admin', '845c84c9ea7ee7ae40defabce5e44140', '2018-01-11 23:50:13'),
(113, 'Enregistrement Devis 53', 'Insert', 'devis', 53, 'admin', '845c84c9ea7ee7ae40defabce5e44140', '2018-01-11 23:50:30'),
(114, 'Enregistrement proforma 35', 'Insert', 'proforma', NULL, 'admin', '5aa2c4f14bb1496aedc3b829e03b2ab1', '2018-01-21 23:17:24'),
(115, 'Enregistrement proforma 36', 'Insert', 'proforma', NULL, 'admin', '83d62a7243415b4b379a69e4753f8e9e', '2018-01-22 23:19:56'),
(116, 'Suppression proforma 36', 'Delete', 'proforma', 36, 'admin', '83d62a7243415b4b379a69e4753f8e9e', '2018-01-22 23:23:58'),
(117, 'Enregistrement proforma 37', 'Insert', 'proforma', NULL, 'admin', '83d62a7243415b4b379a69e4753f8e9e', '2018-01-22 23:24:52'),
(118, 'Enregistrement proforma 38', 'Insert', 'proforma', NULL, 'admin', '83d62a7243415b4b379a69e4753f8e9e', '2018-01-22 23:25:49'),
(119, 'Modification proforma 38', 'Update', 'proforma', 38, 'admin', '83d62a7243415b4b379a69e4753f8e9e', '2018-01-23 00:12:07'),
(120, 'Modification proforma 38', 'Update', 'proforma', 38, 'admin', '83d62a7243415b4b379a69e4753f8e9e', '2018-01-23 00:15:00'),
(121, 'Modification proforma 38', 'Update', 'proforma', 38, 'admin', '83d62a7243415b4b379a69e4753f8e9e', '2018-01-23 00:15:50'),
(122, 'Modification Détail proforma 38', 'Update', NULL, NULL, 'admin', '83d62a7243415b4b379a69e4753f8e9e', '2018-01-23 00:19:58'),
(123, 'Modification Détail proforma 38', 'Update', NULL, NULL, 'admin', '83d62a7243415b4b379a69e4753f8e9e', '2018-01-23 00:20:23'),
(124, 'Modification proforma 38', 'Update', 'proforma', 38, 'admin', '38abf55d2eeb32d6ad1585314f91b459', '2018-01-23 22:45:20'),
(125, 'Modification contrat abonnement', 'Update', 'contrats', 38, 'admin', '38abf55d2eeb32d6ad1585314f91b459', '2018-01-23 23:15:00'),
(126, 'Insertion contrat abonnement', 'Insert', 'contrats', 39, 'admin', '38abf55d2eeb32d6ad1585314f91b459', '2018-01-23 23:15:46'),
(127, 'Modification contrat abonnement', 'Update', 'contrats', 39, 'admin', '38abf55d2eeb32d6ad1585314f91b459', '2018-01-23 23:20:33'),
(128, 'Insertion contrat abonnement', 'Insert', 'contrats', 40, 'admin', '38abf55d2eeb32d6ad1585314f91b459', '2018-01-24 00:07:58'),
(129, 'Suppression contrat abonnement', 'Delete', 'contrats', 40, 'admin', '38abf55d2eeb32d6ad1585314f91b459', '2018-01-24 00:08:56'),
(130, 'Insertion contrat abonnement', 'Insert', 'contrats', 41, 'admin', '38abf55d2eeb32d6ad1585314f91b459', '2018-01-24 00:09:11'),
(131, 'Suppression contrat abonnement', 'Delete', 'contrats', 41, 'admin', '38abf55d2eeb32d6ad1585314f91b459', '2018-01-24 00:09:39'),
(132, 'Insertion contrat abonnement', 'Insert', 'contrats', 42, 'admin', '38abf55d2eeb32d6ad1585314f91b459', '2018-01-24 00:09:53'),
(133, 'Suppression contrat abonnement', 'Delete', 'contrats', 42, 'admin', '38abf55d2eeb32d6ad1585314f91b459', '2018-01-24 00:10:01'),
(134, 'Insertion contrat abonnement', 'Insert', 'contrats', 43, 'admin', '38abf55d2eeb32d6ad1585314f91b459', '2018-01-24 00:12:54'),
(135, 'Suppression contrat abonnement', 'Delete', 'contrats', 43, 'admin', '38abf55d2eeb32d6ad1585314f91b459', '2018-01-24 00:12:58'),
(136, 'Insertion contrat abonnement', 'Insert', 'contrats', 44, 'admin', '38abf55d2eeb32d6ad1585314f91b459', '2018-01-24 00:13:22'),
(137, 'Suppression contrat abonnement', 'Delete', 'contrats', 44, 'admin', '38abf55d2eeb32d6ad1585314f91b459', '2018-01-24 00:13:27'),
(138, 'Insertion contrat abonnement', 'Insert', 'contrats', 45, 'admin', '38abf55d2eeb32d6ad1585314f91b459', '2018-01-24 00:20:57'),
(139, 'Suppression contrat abonnement', 'Delete', 'contrats', 45, 'admin', '38abf55d2eeb32d6ad1585314f91b459', '2018-01-24 00:21:02'),
(140, 'Insertion contrat abonnement', 'Insert', 'contrats', 46, 'admin', '38abf55d2eeb32d6ad1585314f91b459', '2018-01-24 00:35:51'),
(141, 'Suppression contrat abonnement', 'Delete', 'contrats', 46, 'admin', '38abf55d2eeb32d6ad1585314f91b459', '2018-01-24 00:36:21'),
(142, 'Insertion contrat abonnement', 'Insert', 'contrats', 47, 'admin', '38abf55d2eeb32d6ad1585314f91b459', '2018-01-24 00:36:49'),
(143, 'Suppression contrat abonnement', 'Delete', 'contrats', 47, 'admin', '38abf55d2eeb32d6ad1585314f91b459', '2018-01-24 00:37:09'),
(144, 'Insertion contrat abonnement', 'Insert', 'contrats', 48, 'admin', '38abf55d2eeb32d6ad1585314f91b459', '2018-01-24 00:37:32'),
(145, 'Suppression contrat abonnement', 'Delete', 'contrats', 48, 'admin', '38abf55d2eeb32d6ad1585314f91b459', '2018-01-24 00:37:38'),
(146, 'Insertion contrat abonnement', 'Insert', 'contrats', 49, 'admin', '38abf55d2eeb32d6ad1585314f91b459', '2018-01-24 00:52:51'),
(147, 'Suppression contrat abonnement', 'Delete', 'contrats', 39, 'admin', '38abf55d2eeb32d6ad1585314f91b459', '2018-01-24 00:54:10'),
(148, 'Insertion contrat abonnement', 'Insert', 'contrats', 50, 'admin', '38abf55d2eeb32d6ad1585314f91b459', '2018-01-24 00:57:54'),
(149, 'Suppression contrat abonnement', 'Delete', 'contrats', 50, 'admin', '38abf55d2eeb32d6ad1585314f91b459', '2018-01-24 00:58:04'),
(150, 'Insertion contrat abonnement', 'Insert', 'contrats', 51, 'admin', '261dd74e9ad2d58ae2aa1821c38b6014', '2018-01-24 07:42:07'),
(151, 'Suppression contrat abonnement', 'Delete', 'contrats', 51, 'admin', '261dd74e9ad2d58ae2aa1821c38b6014', '2018-01-24 07:42:49'),
(152, 'Insertion contrat abonnement', 'Insert', 'contrats', 52, 'admin', '261dd74e9ad2d58ae2aa1821c38b6014', '2018-01-24 07:43:37'),
(153, 'Suppression contrat abonnement', 'Delete', 'contrats', 52, 'admin', '261dd74e9ad2d58ae2aa1821c38b6014', '2018-01-24 07:43:44'),
(154, 'Insertion contrat abonnement', 'Insert', 'contrats', 53, 'admin', '261dd74e9ad2d58ae2aa1821c38b6014', '2018-01-24 07:45:08'),
(155, 'Suppression contrat abonnement', 'Delete', 'contrats', 53, 'admin', '261dd74e9ad2d58ae2aa1821c38b6014', '2018-01-24 07:45:14'),
(156, 'Insertion contrat abonnement', 'Insert', 'contrats', 54, 'admin', '261dd74e9ad2d58ae2aa1821c38b6014', '2018-01-24 07:47:14'),
(157, 'Suppression contrat abonnement', 'Delete', 'contrats', 54, 'admin', '261dd74e9ad2d58ae2aa1821c38b6014', '2018-01-24 07:48:48'),
(158, 'Insertion contrat abonnement', 'Insert', 'contrats', 55, 'admin', '261dd74e9ad2d58ae2aa1821c38b6014', '2018-01-24 08:58:23'),
(159, 'Suppression contrat abonnement', 'Delete', 'contrats', 55, 'admin', '261dd74e9ad2d58ae2aa1821c38b6014', '2018-01-24 09:11:09'),
(160, 'Insertion contrat abonnement', 'Insert', 'contrats', 56, 'admin', '261dd74e9ad2d58ae2aa1821c38b6014', '2018-01-24 09:27:09'),
(161, 'Suppression contrat abonnement', 'Delete', 'contrats', 56, 'admin', '261dd74e9ad2d58ae2aa1821c38b6014', '2018-01-24 09:27:20'),
(162, 'Insertion contrat abonnement', 'Insert', 'contrats', 57, 'admin', '261dd74e9ad2d58ae2aa1821c38b6014', '2018-01-24 09:29:13'),
(163, 'Suppression contrat abonnement', 'Delete', 'contrats', 57, 'admin', '261dd74e9ad2d58ae2aa1821c38b6014', '2018-01-24 09:29:21'),
(164, 'Insertion contrat abonnement', 'Insert', 'contrats', 58, 'admin', '15649857c98dad1bb6dbd40a11824b7f', '2018-01-26 10:32:42'),
(165, 'Insertion contrat abonnement', 'Insert', 'contrats', 59, 'admin', '15649857c98dad1bb6dbd40a11824b7f', '2018-01-26 10:35:30'),
(166, 'Insertion contrat abonnement', 'Insert', 'contrats', 60, 'admin', '15649857c98dad1bb6dbd40a11824b7f', '2018-01-26 10:36:58'),
(167, 'Insertion contrat abonnement', 'Insert', 'contrats', 61, 'admin', '15649857c98dad1bb6dbd40a11824b7f', '2018-01-26 10:38:11'),
(168, 'Insertion contrat abonnement', 'Insert', 'contrats', 62, 'admin', '15649857c98dad1bb6dbd40a11824b7f', '2018-01-26 10:40:29'),
(169, 'Insertion contrat abonnement', 'Insert', 'contrats', 63, 'admin', '15649857c98dad1bb6dbd40a11824b7f', '2018-01-26 10:46:09'),
(170, 'Insertion contrat abonnement', 'Insert', 'contrats', 64, 'admin', '15649857c98dad1bb6dbd40a11824b7f', '2018-01-26 10:49:08'),
(171, 'Insertion contrat abonnement', 'Insert', 'contrats', 65, 'admin', '15649857c98dad1bb6dbd40a11824b7f', '2018-01-26 11:07:24'),
(172, 'Suppression contrat abonnement', 'Delete', 'contrats', 65, 'admin', '8c948603c8cbb4de76b7ed62703ecbd2', '2018-01-26 21:55:09'),
(173, 'Insertion contrat abonnement', 'Insert', 'contrats', 66, 'admin', '8c948603c8cbb4de76b7ed62703ecbd2', '2018-01-26 22:02:33'),
(174, 'Suppression contrat abonnement', 'Delete', 'contrats', 66, 'admin', '8c948603c8cbb4de76b7ed62703ecbd2', '2018-01-26 22:02:41'),
(175, 'Insertion contrat abonnement', 'Insert', 'contrats', 67, 'admin', '8c948603c8cbb4de76b7ed62703ecbd2', '2018-01-26 22:02:59'),
(176, 'Suppression contrat abonnement', 'Delete', 'contrats', 67, 'admin', '8c948603c8cbb4de76b7ed62703ecbd2', '2018-01-26 22:03:10'),
(177, 'Insertion contrat abonnement', 'Insert', 'contrats', 68, 'admin', '8c948603c8cbb4de76b7ed62703ecbd2', '2018-01-26 22:05:56'),
(178, 'Insertion contrat abonnement', 'Insert', 'contrats', 69, 'admin', '8c948603c8cbb4de76b7ed62703ecbd2', '2018-01-26 22:07:03'),
(179, 'Insertion contrat abonnement', 'Insert', 'contrats', 70, 'admin', '8c948603c8cbb4de76b7ed62703ecbd2', '2018-01-26 22:12:36'),
(180, 'Insertion contrat abonnement', 'Insert', 'contrats', 71, 'admin', '8c948603c8cbb4de76b7ed62703ecbd2', '2018-01-26 22:12:54'),
(181, 'Modification contrat abonnement', 'Update', 'contrats', 71, 'admin', '8c948603c8cbb4de76b7ed62703ecbd2', '2018-01-26 22:19:51'),
(182, 'Modification contrat abonnement', 'Update', 'contrats', 71, 'admin', '8c948603c8cbb4de76b7ed62703ecbd2', '2018-01-26 22:20:39'),
(183, 'Modification contrat abonnement', 'Update', 'contrats', 71, 'admin', '8c948603c8cbb4de76b7ed62703ecbd2', '2018-01-26 22:21:58'),
(184, 'Modification contrat abonnement', 'Update', 'contrats', 71, 'admin', '8c948603c8cbb4de76b7ed62703ecbd2', '2018-01-26 22:22:17'),
(185, 'Modification contrat abonnement', 'Update', 'contrats', 71, 'admin', '8c948603c8cbb4de76b7ed62703ecbd2', '2018-01-26 22:22:37'),
(186, 'Suppression contrat abonnement', 'Delete', 'contrats', 71, 'admin', '8c948603c8cbb4de76b7ed62703ecbd2', '2018-01-26 22:23:11'),
(187, 'Insertion contrat abonnement', 'Insert', 'contrats', 72, 'admin', '8c948603c8cbb4de76b7ed62703ecbd2', '2018-01-26 22:24:04'),
(188, 'Suppression contrat abonnement', 'Delete', 'contrats', 72, 'admin', '8c948603c8cbb4de76b7ed62703ecbd2', '2018-01-26 22:24:23'),
(189, 'Insertion contrat abonnement', 'Insert', 'contrats', 73, 'admin', '8c948603c8cbb4de76b7ed62703ecbd2', '2018-01-26 22:28:00'),
(190, 'Insertion contrat abonnement', 'Insert', 'contrats', 74, 'admin', '8c948603c8cbb4de76b7ed62703ecbd2', '2018-01-26 22:35:33'),
(191, 'Modification contrat abonnement', 'Update', 'contrats', 74, 'admin', '8c948603c8cbb4de76b7ed62703ecbd2', '2018-01-26 22:35:57'),
(192, 'Validation contrat abonnement', 'Validate', 'contrats', 34, 'admin', '35c21481653b04aaf430ddcadb87af42', '2018-01-28 20:46:52'),
(193, 'Validation contrat abonnement', 'Validate', 'contrats', 33, 'admin', '35c21481653b04aaf430ddcadb87af42', '2018-01-28 20:50:37'),
(194, 'Modification contrat abonnement', 'Update', 'contrats', 38, 'admin', '35c21481653b04aaf430ddcadb87af42', '2018-01-28 20:56:32'),
(195, 'Validation contrat abonnement', 'Validate', 'contrats', 38, 'admin', '35c21481653b04aaf430ddcadb87af42', '2018-01-28 20:56:47'),
(196, 'Enregistrement Détail Devis 215', 'Insert', 'd_devis', 215, 'admin', '41d4ad83b85b177268080f2aa90cb9c7', '2018-02-04 17:31:30'),
(197, 'Enregistrement Devis 54', 'Insert', 'devis', 54, 'admin', '41d4ad83b85b177268080f2aa90cb9c7', '2018-02-04 17:31:36'),
(198, 'Validation devis 54', 'Update', 'devis', 54, 'admin', '41d4ad83b85b177268080f2aa90cb9c7', '2018-02-04 17:31:44'),
(199, 'Expédition devis 54', 'Update', 'devis', 54, 'admin', '41d4ad83b85b177268080f2aa90cb9c7', '2018-02-04 17:31:50'),
(200, 'Validation client #Devis:54', 'Update', 'devis', 54, 'admin', '41d4ad83b85b177268080f2aa90cb9c7', '2018-02-04 17:32:00'),
(201, 'Enregistrement Détail Devis 216', 'Insert', 'd_devis', 216, 'admin', '41d4ad83b85b177268080f2aa90cb9c7', '2018-02-04 17:39:48'),
(202, 'Enregistrement Devis 55', 'Insert', 'devis', 55, 'admin', '41d4ad83b85b177268080f2aa90cb9c7', '2018-02-04 17:39:54'),
(203, 'Validation devis 55', 'Update', 'devis', 55, 'admin', '41d4ad83b85b177268080f2aa90cb9c7', '2018-02-04 17:40:00'),
(204, 'Expédition devis 55', 'Update', 'devis', 55, 'admin', '41d4ad83b85b177268080f2aa90cb9c7', '2018-02-04 17:40:07'),
(205, 'Validation client #Devis:55', 'Update', 'devis', 55, 'admin', '41d4ad83b85b177268080f2aa90cb9c7', '2018-02-04 17:40:16'),
(206, 'Insertion contrat abonnement', 'Insert', 'contrats', 75, 'admin', '41d4ad83b85b177268080f2aa90cb9c7', '2018-02-04 17:40:50'),
(207, 'Validation contrat abonnement', 'Validate', 'contrats', 75, 'admin', '41d4ad83b85b177268080f2aa90cb9c7', '2018-02-04 17:40:57'),
(208, 'Enregistrement Détail Devis 217', 'Insert', 'd_devis', 217, 'admin', 'e083b3f6a913bbb3c756595a41930f93', '2018-02-15 22:53:10'),
(209, 'Enregistrement Devis 56', 'Insert', 'devis', 56, 'admin', 'e083b3f6a913bbb3c756595a41930f93', '2018-02-15 22:54:23'),
(210, 'Validation devis 56', 'Update', 'devis', 56, 'admin', 'eacfc504336b694bd618a33f08b4c852', '2018-02-24 23:44:38'),
(211, 'Expédition devis 56', 'Update', 'devis', 56, 'admin', 'eacfc504336b694bd618a33f08b4c852', '2018-02-24 23:44:56'),
(212, 'Validation client #Devis:56', 'Update', 'devis', 56, 'admin', 'eacfc504336b694bd618a33f08b4c852', '2018-02-24 23:45:11'),
(213, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 43, 'admin', '59bdce9d025eddbf1bc5b17244c19e38', '2018-02-25 11:58:55'),
(214, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 44, 'admin', '59bdce9d025eddbf1bc5b17244c19e38', '2018-02-25 11:59:31'),
(215, 'Modification échéance contrat abonnement', 'Update', 'echeances_contrat', 44, 'admin', '59bdce9d025eddbf1bc5b17244c19e38', '2018-02-25 12:02:31'),
(216, 'Modification échéance contrat abonnement', 'Update', 'echeances_contrat', 44, 'admin', '59bdce9d025eddbf1bc5b17244c19e38', '2018-02-25 12:02:52'),
(217, 'Modification échéance contrat abonnement', 'Update', 'echeances_contrat', 44, 'admin', '59bdce9d025eddbf1bc5b17244c19e38', '2018-02-25 12:03:05'),
(218, 'Modification échéance contrat abonnement', 'Update', 'echeances_contrat', 44, 'admin', '59bdce9d025eddbf1bc5b17244c19e38', '2018-02-25 12:03:50'),
(219, 'Insertion contrat abonnement', 'Insert', 'contrats', 76, 'admin', '59bdce9d025eddbf1bc5b17244c19e38', '2018-02-25 12:03:55'),
(220, 'Validation contrat abonnement', 'Validate', 'contrats', 76, 'admin', '59bdce9d025eddbf1bc5b17244c19e38', '2018-02-25 12:04:06'),
(221, 'Modification échéance contrat abonnement', 'Update', 'echeances_contrat', 43, 'admin', 'c0b61e3d01e8e48da67e1ed7a72bc21f', '2018-02-25 20:33:38'),
(222, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 45, 'admin', 'c0b61e3d01e8e48da67e1ed7a72bc21f', '2018-02-25 20:33:56'),
(223, 'Modification contrat abonnement', 'Update', 'contrats', 76, 'admin', 'c0b61e3d01e8e48da67e1ed7a72bc21f', '2018-02-25 20:34:03'),
(224, 'Modification échéance contrat abonnement', 'Update', 'echeances_contrat', 45, 'admin', '6cbbb37ce204b4c8405309e5a6eea543', '2018-02-26 10:59:10'),
(225, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 46, 'admin', '6cbbb37ce204b4c8405309e5a6eea543', '2018-02-26 11:00:30'),
(226, 'Modification contrat abonnement', 'Update', 'contrats', 76, 'admin', '6cbbb37ce204b4c8405309e5a6eea543', '2018-02-26 11:00:32'),
(227, 'Modification contrat abonnement', 'Update', 'contrats', 76, 'admin', '6cbbb37ce204b4c8405309e5a6eea543', '2018-02-26 11:00:51'),
(228, 'Modification contrat abonnement', 'Update', 'contrats', 76, 'admin', '6cbbb37ce204b4c8405309e5a6eea543', '2018-02-26 11:02:38'),
(229, 'Modification échéance contrat abonnement', 'Update', 'echeances_contrat', 46, 'admin', '6cbbb37ce204b4c8405309e5a6eea543', '2018-02-26 11:18:12'),
(230, 'Modification échéance contrat abonnement', 'Update', 'echeances_contrat', 45, 'admin', '6cbbb37ce204b4c8405309e5a6eea543', '2018-02-26 11:18:32'),
(231, 'Modification échéance contrat abonnement', 'Update', 'echeances_contrat', 44, 'admin', '6cbbb37ce204b4c8405309e5a6eea543', '2018-02-26 11:18:37'),
(232, 'Modification échéance contrat abonnement', 'Update', 'echeances_contrat', 43, 'admin', '6cbbb37ce204b4c8405309e5a6eea543', '2018-02-26 11:18:41'),
(233, 'Enregistrement Détail Devis 218', 'Insert', 'd_devis', 218, 'admin', 'd7eba05b218057b9eb04d6fef1f6c27f', '2018-02-28 10:27:42'),
(234, 'Enregistrement Devis 57', 'Insert', 'devis', 57, 'admin', 'd7eba05b218057b9eb04d6fef1f6c27f', '2018-02-28 10:27:49'),
(235, 'Validation devis 57', 'Update', 'devis', 57, 'admin', 'd7eba05b218057b9eb04d6fef1f6c27f', '2018-02-28 10:28:02'),
(236, 'Expédition devis 57', 'Update', 'devis', 57, 'admin', 'd7eba05b218057b9eb04d6fef1f6c27f', '2018-02-28 10:28:09'),
(237, 'Validation client #Devis:57', 'Update', 'devis', 57, 'admin', 'd7eba05b218057b9eb04d6fef1f6c27f', '2018-02-28 10:28:17'),
(238, 'Insertion contrat abonnement', 'Insert', 'contrats', 77, 'admin', 'd7eba05b218057b9eb04d6fef1f6c27f', '2018-02-28 10:29:38'),
(239, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 47, 'admin', 'd7eba05b218057b9eb04d6fef1f6c27f', '2018-02-28 10:29:38'),
(240, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 48, 'admin', 'd7eba05b218057b9eb04d6fef1f6c27f', '2018-02-28 10:29:38'),
(241, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 49, 'admin', 'd7eba05b218057b9eb04d6fef1f6c27f', '2018-02-28 10:29:38'),
(242, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 50, 'admin', 'd7eba05b218057b9eb04d6fef1f6c27f', '2018-02-28 10:29:38'),
(243, 'Validation contrat abonnement', 'Validate', 'contrats', 77, 'admin', 'd7eba05b218057b9eb04d6fef1f6c27f', '2018-02-28 10:30:09'),
(244, 'Modification contrat abonnement', 'Update', 'contrats', 77, 'admin', 'd7eba05b218057b9eb04d6fef1f6c27f', '2018-02-28 10:31:07'),
(245, 'Suppression échéance abonnement', 'Delete', 'contrats', 77, 'admin', 'd7eba05b218057b9eb04d6fef1f6c27f', '2018-02-28 10:31:07'),
(246, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 51, 'admin', 'd7eba05b218057b9eb04d6fef1f6c27f', '2018-02-28 10:31:07'),
(247, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 52, 'admin', 'd7eba05b218057b9eb04d6fef1f6c27f', '2018-02-28 10:31:07'),
(248, 'Création client', 'Insert', 'clients', 38, 'admin', '0f9c093682bc70767da9f7e1a3071dbf', '2018-03-03 23:24:55'),
(249, 'Modification client', 'Update', 'clients', 38, 'admin', '0f9c093682bc70767da9f7e1a3071dbf', '2018-03-03 23:25:14'),
(250, 'Modification client', 'Update', 'clients', 38, 'admin', '0f9c093682bc70767da9f7e1a3071dbf', '2018-03-03 23:30:58'),
(251, 'Création client', 'Insert', 'clients', 39, 'admin', '0f9c093682bc70767da9f7e1a3071dbf', '2018-03-03 23:37:25'),
(252, 'Modification client', 'Update', 'clients', 39, 'admin', '0f9c093682bc70767da9f7e1a3071dbf', '2018-03-03 23:43:14'),
(253, 'Validation client', 'Validate', 'clients', 39, 'admin', '0f9c093682bc70767da9f7e1a3071dbf', '2018-03-03 23:47:22'),
(254, 'Validation client', 'Validate', 'clients', 39, 'admin', '0f9c093682bc70767da9f7e1a3071dbf', '2018-03-03 23:47:32'),
(255, 'Création client', 'Insert', 'clients', 43, 'admin', '0f9c093682bc70767da9f7e1a3071dbf', '2018-03-04 00:09:27'),
(256, 'Modification client', 'Update', 'clients', 43, 'admin', '0f9c093682bc70767da9f7e1a3071dbf', '2018-03-04 00:13:19'),
(257, 'Modification client', 'Update', 'clients', 43, 'admin', '0f9c093682bc70767da9f7e1a3071dbf', '2018-03-04 00:15:22'),
(258, 'Suppression client', 'Delete', 'clients', 43, 'admin', '0f9c093682bc70767da9f7e1a3071dbf', '2018-03-04 00:15:27'),
(259, 'Validation client', 'Validate', 'clients', 39, 'admin', '0f9c093682bc70767da9f7e1a3071dbf', '2018-03-04 00:15:33'),
(260, 'Insertion produit', 'Insert', 'produits', 29, 'admin', '0f9c093682bc70767da9f7e1a3071dbf', '2018-03-04 00:16:42'),
(261, 'Modification produit', 'Update', 'produits', 29, 'admin', '0f9c093682bc70767da9f7e1a3071dbf', '2018-03-04 00:16:49'),
(262, 'Validation produit', 'Validate', 'produits', 29, 'admin', '0f9c093682bc70767da9f7e1a3071dbf', '2018-03-04 00:16:54'),
(263, 'Insertion achat produit', 'Insert', 'stock', 53, 'admin', '0f9c093682bc70767da9f7e1a3071dbf', '2018-03-04 00:17:18'),
(264, 'Modification achat produit', 'Update', 'stock', 53, 'admin', '0f9c093682bc70767da9f7e1a3071dbf', '2018-03-04 00:17:26'),
(265, 'Validation achat produit', 'Validate', 'stock', 53, 'admin', '0f9c093682bc70767da9f7e1a3071dbf', '2018-03-04 00:17:36'),
(266, 'Enregistrement proforma 39', 'Insert', 'proforma', NULL, 'admin', '0f9c093682bc70767da9f7e1a3071dbf', '2018-03-04 00:18:43'),
(267, 'Modification proforma 39', 'Update', 'proforma', 39, 'admin', '0f9c093682bc70767da9f7e1a3071dbf', '2018-03-04 00:18:54'),
(268, 'Validation PROFORMA:39', 'Update', 'proforma', 39, 'admin', '0f9c093682bc70767da9f7e1a3071dbf', '2018-03-04 00:19:24'),
(269, 'Expédition proforma 39', 'Update', 'proforma', NULL, 'admin', '0f9c093682bc70767da9f7e1a3071dbf', '2018-03-04 00:19:34'),
(270, 'Enregistrement Détail Devis 219', 'Insert', 'd_devis', 219, 'admin', '0f9c093682bc70767da9f7e1a3071dbf', '2018-03-04 00:20:42'),
(271, 'Enregistrement Devis 58', 'Insert', 'devis', 58, 'admin', '0f9c093682bc70767da9f7e1a3071dbf', '2018-03-04 00:20:46'),
(272, 'Modification Devis 58', 'Update', 'devis', 58, 'admin', '0f9c093682bc70767da9f7e1a3071dbf', '2018-03-04 00:20:58'),
(273, 'Validation devis 58', 'Update', 'devis', 58, 'admin', '0f9c093682bc70767da9f7e1a3071dbf', '2018-03-04 00:30:23'),
(274, 'Expédition devis 58', 'Update', 'devis', 58, 'admin', '0f9c093682bc70767da9f7e1a3071dbf', '2018-03-04 00:30:39'),
(275, 'Validation client #Devis:58', 'Update', 'devis', 58, 'admin', '0f9c093682bc70767da9f7e1a3071dbf', '2018-03-04 00:30:49'),
(276, 'Insertion contrat abonnement', 'Insert', 'contrats', 78, 'admin', '0f9c093682bc70767da9f7e1a3071dbf', '2018-03-04 00:32:35'),
(277, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 53, 'admin', '0f9c093682bc70767da9f7e1a3071dbf', '2018-03-04 00:32:35'),
(278, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 54, 'admin', '0f9c093682bc70767da9f7e1a3071dbf', '2018-03-04 00:32:35'),
(279, 'Modification contrat abonnement', 'Update', 'contrats', 78, 'admin', '0f9c093682bc70767da9f7e1a3071dbf', '2018-03-04 00:36:11'),
(280, 'Suppression échéance abonnement', 'Delete', 'contrats', 78, 'admin', '0f9c093682bc70767da9f7e1a3071dbf', '2018-03-04 00:36:11'),
(281, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 55, 'admin', '0f9c093682bc70767da9f7e1a3071dbf', '2018-03-04 00:36:11'),
(282, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 56, 'admin', '0f9c093682bc70767da9f7e1a3071dbf', '2018-03-04 00:36:11'),
(283, 'Validation contrat abonnement', 'Validate', 'contrats', 78, 'admin', '0f9c093682bc70767da9f7e1a3071dbf', '2018-03-04 00:54:55'),
(284, 'Enregistrement Détail Devis 220', 'Insert', 'd_devis', 220, 'admin', '0f9c093682bc70767da9f7e1a3071dbf', '2018-03-04 02:00:25'),
(285, 'Enregistrement Détail Devis 221', 'Insert', 'd_devis', 221, 'admin', '0f9c093682bc70767da9f7e1a3071dbf', '2018-03-04 02:00:38'),
(286, 'Enregistrement Devis 59', 'Insert', 'devis', 59, 'admin', '0f9c093682bc70767da9f7e1a3071dbf', '2018-03-04 02:00:44'),
(287, 'Validation devis 59', 'Update', 'devis', 59, 'admin', '0f9c093682bc70767da9f7e1a3071dbf', '2018-03-04 02:00:51'),
(288, 'Expédition devis 59', 'Update', 'devis', 59, 'admin', '0f9c093682bc70767da9f7e1a3071dbf', '2018-03-04 02:00:59'),
(289, 'Validation client #Devis:59', 'Update', 'devis', 59, 'admin', '0f9c093682bc70767da9f7e1a3071dbf', '2018-03-04 02:01:08'),
(290, 'Enregistrement Détail Devis 222', 'Insert', 'd_devis', 222, 'admin', '0f9c093682bc70767da9f7e1a3071dbf', '2018-03-04 02:09:47'),
(291, 'Enregistrement Devis 60', 'Insert', 'devis', 60, 'admin', '0f9c093682bc70767da9f7e1a3071dbf', '2018-03-04 02:09:52'),
(292, 'Validation devis 60', 'Update', 'devis', 60, 'admin', '0f9c093682bc70767da9f7e1a3071dbf', '2018-03-04 02:09:58'),
(293, 'Expédition devis 60', 'Update', 'devis', 60, 'admin', '0f9c093682bc70767da9f7e1a3071dbf', '2018-03-04 02:10:04'),
(294, 'Validation client #Devis:60', 'Update', 'devis', 60, 'admin', '0f9c093682bc70767da9f7e1a3071dbf', '2018-03-04 02:10:53'),
(295, 'Validation devis 59', 'Update', 'devis', 59, 'admin', '0f9c093682bc70767da9f7e1a3071dbf', '2018-03-04 02:53:32'),
(296, 'Expédition devis 59', 'Update', 'devis', 59, 'admin', '0f9c093682bc70767da9f7e1a3071dbf', '2018-03-04 02:53:37'),
(297, 'Validation client #Devis:59', 'Update', 'devis', 59, 'admin', '0f9c093682bc70767da9f7e1a3071dbf', '2018-03-04 02:53:46'),
(298, 'Expédition devis 59', 'Update', 'devis', 59, 'admin', '0f9c093682bc70767da9f7e1a3071dbf', '2018-03-04 03:11:55'),
(299, 'Validation client #Devis:59', 'Update', 'devis', 59, 'admin', '0f9c093682bc70767da9f7e1a3071dbf', '2018-03-04 03:12:13'),
(300, 'Création client', 'Insert', 'clients', 44, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 11:54:30'),
(301, 'Modification client', 'Update', 'clients', 44, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 11:57:16'),
(302, 'Validation client', 'Validate', 'clients', 44, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 11:57:32'),
(303, 'Insertion produit', 'Insert', 'produits', 30, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 11:58:42'),
(304, 'Validation produit', 'Validate', 'produits', 30, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 11:58:55'),
(305, 'Insertion achat produit', 'Insert', 'stock', 54, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 12:00:02'),
(306, 'Validation achat produit', 'Validate', 'stock', 54, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 12:00:24'),
(307, 'Création commerciale', 'Insert', 'commerciaux', 2, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 12:04:04'),
(308, 'Changement ETAT  commerciale', 'Update', 'commerciaux', 2, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 12:04:13'),
(309, 'Changement ETAT  commerciale', 'Update', 'commerciaux', 2, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 12:04:20'),
(310, 'Modification commerciale', 'Update', 'commerciaux', 1, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 12:04:49'),
(311, 'Changement ETAT  commerciale', 'Update', 'commerciaux', 2, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 12:05:03'),
(312, 'Enregistrement Détail Devis 223', 'Insert', 'd_devis', 223, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 12:07:17'),
(313, 'Enregistrement Détail Devis 224', 'Insert', 'd_devis', 224, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 12:07:43'),
(314, 'Enregistrement Détail Devis 225', 'Insert', 'd_devis', 225, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 12:08:24'),
(315, 'Enregistrement Devis 61', 'Insert', 'devis', 61, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 12:08:46'),
(316, 'Validation devis 61', 'Update', 'devis', 61, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 12:21:05'),
(317, 'Expédition devis 61', 'Update', 'devis', 61, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 12:21:19'),
(318, 'Validation client #Devis:61', 'Update', 'devis', 61, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 12:21:33'),
(319, 'Enregistrement Détail Devis 226', 'Insert', 'd_devis', 226, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 13:21:25'),
(320, 'Enregistrement Devis 62', 'Insert', 'devis', 62, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 13:22:18'),
(321, 'Validation devis 62', 'Update', 'devis', 62, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 13:22:59'),
(322, 'Expédition devis 62', 'Update', 'devis', 62, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 13:23:08'),
(323, 'Validation client #Devis:62', 'Update', 'devis', 62, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 13:23:15'),
(324, 'Insertion contrat abonnement', 'Insert', 'contrats', 79, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 13:24:54'),
(325, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 57, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 13:24:54'),
(326, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 58, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 13:24:54'),
(327, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 59, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 13:24:54'),
(328, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 60, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 13:24:54'),
(329, 'Validation contrat abonnement', 'Validate', 'contrats', 79, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 13:25:16'),
(330, 'Modification contrat abonnement', 'Update', 'contrats', 79, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 13:28:43'),
(331, 'Suppression échéance abonnement', 'Delete', 'contrats', 79, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 13:28:43'),
(332, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 61, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 13:28:43'),
(333, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 62, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 13:28:43'),
(334, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 63, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 13:28:43'),
(335, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 64, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 13:28:43'),
(336, 'Validation contrat abonnement', 'Validate', 'contrats', 79, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 13:28:51'),
(337, 'Modification contrat abonnement', 'Update', 'contrats', 79, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 13:32:52'),
(338, 'Suppression échéance abonnement', 'Delete', 'contrats', 79, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 13:32:52'),
(339, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 65, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 13:32:52'),
(340, 'Modification contrat abonnement', 'Update', 'contrats', 79, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 13:33:14'),
(341, 'Suppression échéance abonnement', 'Delete', 'contrats', 79, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 13:33:14'),
(342, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 66, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 13:33:14'),
(343, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 67, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 13:33:14'),
(344, 'Modification contrat abonnement', 'Update', 'contrats', 79, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 13:34:18'),
(345, 'Suppression échéance abonnement', 'Delete', 'contrats', 79, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 13:34:18'),
(346, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 68, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 13:34:18'),
(347, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 69, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 13:34:18'),
(348, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 70, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 13:34:18'),
(349, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 71, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 13:34:18'),
(350, 'Validation contrat abonnement', 'Validate', 'contrats', 79, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 13:35:05'),
(351, 'Enregistrement Détail Devis 227', 'Insert', 'd_devis', 227, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 13:40:08'),
(352, 'Enregistrement Devis 63', 'Insert', 'devis', 63, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 13:40:21'),
(353, 'Validation devis 63', 'Update', 'devis', 63, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 13:40:45'),
(354, 'Expédition devis 63', 'Update', 'devis', 63, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 13:40:50'),
(355, 'Validation client #Devis:63', 'Update', 'devis', 63, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 13:40:57'),
(356, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 72, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 13:43:57'),
(357, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 73, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 13:45:41'),
(358, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 74, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 13:46:24'),
(359, 'Insertion contrat abonnement', 'Insert', 'contrats', 80, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 13:48:00'),
(360, 'Modification contrat abonnement', 'Update', 'contrats', 80, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 13:48:55'),
(361, 'Suppression échéance abonnement', 'Delete', 'contrats', 80, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 13:48:55'),
(362, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 75, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 14:00:19'),
(363, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 76, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 14:00:42'),
(364, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 77, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 14:01:02'),
(365, 'Suppression échéance contrat abonnement', 'Delete', 'echeances_contrat', NULL, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 14:07:41'),
(366, 'Suppression échéance contrat abonnement', 'Delete', 'echeances_contrat', NULL, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 14:07:45'),
(367, 'Suppression échéance contrat abonnement', 'Delete', 'echeances_contrat', NULL, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 14:07:49'),
(368, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 78, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 14:08:22'),
(369, 'Modification échéance contrat abonnement', 'Update', 'echeances_contrat', 78, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 14:09:25'),
(370, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 79, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 14:09:47'),
(371, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 80, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 14:10:04'),
(372, 'Insertion contrat abonnement', 'Insert', 'contrats', 81, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 14:10:41'),
(373, 'Suppression contrat abonnement', 'Delete', 'contrats', 81, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 14:11:58'),
(374, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 81, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 14:13:08'),
(375, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 82, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 14:13:35'),
(376, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 83, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 14:14:04'),
(377, 'Insertion contrat abonnement', 'Insert', 'contrats', 82, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 14:14:25'),
(378, 'Validation contrat abonnement', 'Validate', 'contrats', 82, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 14:15:35'),
(379, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 84, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 14:17:00'),
(380, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 85, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 14:17:25');
INSERT INTO `sys_log` (`id`, `message`, `type_log`, `table_use`, `idm`, `user_exec`, `sesid`, `datlog`) VALUES
(381, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 86, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 14:18:17'),
(382, 'Insertion contrat abonnement', 'Insert', 'contrats', 83, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 14:18:23'),
(383, 'Validation contrat abonnement', 'Validate', 'contrats', 83, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 14:18:34'),
(384, 'Insertion fournisseur', 'Insert', 'fournisseurs', 34, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 14:22:45'),
(385, 'Modification fournisseur', 'Update', 'fournisseurs', 34, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 14:22:58'),
(386, 'Validation fournisseur', 'Validate', 'fournisseurs', 34, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 14:23:03'),
(387, 'Insertion contrat fournisseur', 'Insert', 'contrats_frn', 41, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 14:24:08'),
(388, 'Modification contrat fournisseur', 'Update', 'contrats_frn', 41, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 14:24:23'),
(389, 'Validation contrat fournisseur', 'Validate', 'contrats_frn', 41, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 14:24:28'),
(390, 'Ajout Commission', 'Insert', 'compte_commerciale', 36, 'admin', '5e091e15a324c199583b130e28486361', '2018-03-04 14:28:00'),
(391, 'Création client', 'Insert', 'clients', 45, 'admin', 'e7d0c96867a9c3141aaf60b10c28cad4', '2018-03-04 18:50:01'),
(392, 'Modification client', 'Update', 'clients', 45, 'admin', 'e7d0c96867a9c3141aaf60b10c28cad4', '2018-03-04 19:00:20'),
(393, 'Modification client', 'Update', 'clients', 45, 'admin', 'e7d0c96867a9c3141aaf60b10c28cad4', '2018-03-04 19:10:07'),
(394, 'Création client', 'Insert', 'clients', 46, 'admin', 'e7d0c96867a9c3141aaf60b10c28cad4', '2018-03-04 19:12:28'),
(395, 'Création client', 'Insert', 'clients', 47, 'admin', 'e7d0c96867a9c3141aaf60b10c28cad4', '2018-03-04 19:15:58'),
(396, 'Modification client', 'Update', 'clients', 47, 'admin', 'e7d0c96867a9c3141aaf60b10c28cad4', '2018-03-04 19:16:52'),
(397, 'Validation client', 'Validate', 'clients', 47, 'admin', 'e7d0c96867a9c3141aaf60b10c28cad4', '2018-03-04 19:17:40'),
(398, 'Insertion contrat abonnement', 'Insert', 'contrats', 84, 'admin', 'deaaac525f0a37a00630b76a906c5dcb', '2018-03-04 21:22:33'),
(399, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 87, 'admin', 'deaaac525f0a37a00630b76a906c5dcb', '2018-03-04 21:22:33'),
(400, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 88, 'admin', 'deaaac525f0a37a00630b76a906c5dcb', '2018-03-04 21:23:59'),
(401, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 89, 'admin', 'deaaac525f0a37a00630b76a906c5dcb', '2018-03-04 21:24:34'),
(402, 'Modification échéance contrat abonnement', 'Update', 'echeances_contrat', 89, 'admin', 'deaaac525f0a37a00630b76a906c5dcb', '2018-03-04 21:24:54'),
(403, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 90, 'admin', 'deaaac525f0a37a00630b76a906c5dcb', '2018-03-04 21:25:09'),
(404, 'Insertion contrat abonnement', 'Insert', 'contrats', 85, 'admin', 'deaaac525f0a37a00630b76a906c5dcb', '2018-03-04 21:25:19'),
(405, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 91, 'admin', 'deaaac525f0a37a00630b76a906c5dcb', '2018-03-04 21:31:43'),
(406, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 92, 'admin', 'deaaac525f0a37a00630b76a906c5dcb', '2018-03-04 21:31:58'),
(407, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 93, 'admin', 'deaaac525f0a37a00630b76a906c5dcb', '2018-03-04 21:32:21'),
(408, 'Insertion contrat abonnement', 'Insert', 'contrats', 86, 'admin', 'deaaac525f0a37a00630b76a906c5dcb', '2018-03-04 21:32:56'),
(409, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 94, 'admin', 'deaaac525f0a37a00630b76a906c5dcb', '2018-03-04 21:37:35'),
(410, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 95, 'admin', 'deaaac525f0a37a00630b76a906c5dcb', '2018-03-04 21:37:49'),
(411, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 96, 'admin', 'deaaac525f0a37a00630b76a906c5dcb', '2018-03-04 21:38:12'),
(412, 'Insertion contrat abonnement', 'Insert', 'contrats', 87, 'admin', 'deaaac525f0a37a00630b76a906c5dcb', '2018-03-04 21:38:17'),
(413, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 97, 'admin', 'deaaac525f0a37a00630b76a906c5dcb', '2018-03-04 21:42:21'),
(414, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 98, 'admin', 'deaaac525f0a37a00630b76a906c5dcb', '2018-03-04 21:42:39'),
(415, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 99, 'admin', 'deaaac525f0a37a00630b76a906c5dcb', '2018-03-04 21:43:01'),
(416, 'Insertion contrat abonnement', 'Insert', 'contrats', 88, 'admin', 'deaaac525f0a37a00630b76a906c5dcb', '2018-03-04 21:43:10'),
(417, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 100, 'admin', 'deaaac525f0a37a00630b76a906c5dcb', '2018-03-04 21:45:58'),
(418, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 101, 'admin', 'deaaac525f0a37a00630b76a906c5dcb', '2018-03-04 21:46:17'),
(419, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 102, 'admin', 'deaaac525f0a37a00630b76a906c5dcb', '2018-03-04 21:46:47'),
(420, 'Insertion contrat abonnement', 'Insert', 'contrats', 89, 'admin', 'deaaac525f0a37a00630b76a906c5dcb', '2018-03-04 21:46:53'),
(421, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 103, 'admin', 'deaaac525f0a37a00630b76a906c5dcb', '2018-03-04 21:50:48'),
(422, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 104, 'admin', 'deaaac525f0a37a00630b76a906c5dcb', '2018-03-04 21:51:11'),
(423, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 105, 'admin', 'deaaac525f0a37a00630b76a906c5dcb', '2018-03-04 21:51:34'),
(424, 'Insertion contrat abonnement', 'Insert', 'contrats', 90, 'admin', 'deaaac525f0a37a00630b76a906c5dcb', '2018-03-04 21:51:39'),
(425, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 106, 'admin', 'deaaac525f0a37a00630b76a906c5dcb', '2018-03-04 21:58:23'),
(426, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 107, 'admin', 'deaaac525f0a37a00630b76a906c5dcb', '2018-03-04 21:58:39'),
(427, 'Modification échéance contrat abonnement', 'Update', 'echeances_contrat', 107, 'admin', 'deaaac525f0a37a00630b76a906c5dcb', '2018-03-04 21:58:55'),
(428, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 108, 'admin', 'deaaac525f0a37a00630b76a906c5dcb', '2018-03-04 21:59:16'),
(429, 'Insertion contrat abonnement', 'Insert', 'contrats', 91, 'admin', 'deaaac525f0a37a00630b76a906c5dcb', '2018-03-04 21:59:21'),
(430, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 109, 'admin', 'deaaac525f0a37a00630b76a906c5dcb', '2018-03-04 22:02:02'),
(431, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 110, 'admin', 'deaaac525f0a37a00630b76a906c5dcb', '2018-03-04 22:02:16'),
(432, 'Modification échéance contrat abonnement', 'Update', 'echeances_contrat', 110, 'admin', 'deaaac525f0a37a00630b76a906c5dcb', '2018-03-04 22:02:32'),
(433, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 111, 'admin', 'deaaac525f0a37a00630b76a906c5dcb', '2018-03-04 22:03:09'),
(434, 'Insertion contrat abonnement', 'Insert', 'contrats', 92, 'admin', 'deaaac525f0a37a00630b76a906c5dcb', '2018-03-04 22:03:15'),
(435, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 112, 'admin', 'deaaac525f0a37a00630b76a906c5dcb', '2018-03-04 22:04:59'),
(436, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 113, 'admin', 'deaaac525f0a37a00630b76a906c5dcb', '2018-03-04 22:05:39'),
(437, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 114, 'admin', 'deaaac525f0a37a00630b76a906c5dcb', '2018-03-04 22:06:05'),
(438, 'Insertion contrat abonnement', 'Insert', 'contrats', 93, 'admin', 'deaaac525f0a37a00630b76a906c5dcb', '2018-03-04 22:06:13'),
(439, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 115, 'admin', 'deaaac525f0a37a00630b76a906c5dcb', '2018-03-04 22:18:16'),
(440, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 116, 'admin', 'deaaac525f0a37a00630b76a906c5dcb', '2018-03-04 22:18:34'),
(441, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 117, 'admin', 'deaaac525f0a37a00630b76a906c5dcb', '2018-03-04 22:18:54'),
(442, 'Insertion contrat abonnement', 'Insert', 'contrats', 94, 'admin', 'deaaac525f0a37a00630b76a906c5dcb', '2018-03-04 22:19:01'),
(443, 'Modification contrat abonnement', 'Update', 'contrats', 94, 'admin', 'deaaac525f0a37a00630b76a906c5dcb', '2018-03-04 23:17:03'),
(444, 'Modification contrat abonnement', 'Update', 'contrats', 94, 'admin', 'deaaac525f0a37a00630b76a906c5dcb', '2018-03-04 23:17:42'),
(445, 'Modification contrat abonnement', 'Update', 'contrats', 94, 'admin', 'deaaac525f0a37a00630b76a906c5dcb', '2018-03-04 23:20:04'),
(446, 'Modification contrat abonnement', 'Update', 'contrats', 94, 'admin', 'deaaac525f0a37a00630b76a906c5dcb', '2018-03-04 23:20:33'),
(447, 'Validation contrat abonnement', 'Validate', 'contrats', 94, 'admin', 'deaaac525f0a37a00630b76a906c5dcb', '2018-03-04 23:41:54'),
(448, 'Validation PROFORMA:38', 'Update', 'proforma', 38, 'admin', 'deaaac525f0a37a00630b76a906c5dcb', '2018-03-05 00:54:13'),
(449, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 118, 'admin', 'deaaac525f0a37a00630b76a906c5dcb', '2018-03-05 02:49:14'),
(450, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 119, 'admin', 'deaaac525f0a37a00630b76a906c5dcb', '2018-03-05 02:49:48'),
(451, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 120, 'admin', 'deaaac525f0a37a00630b76a906c5dcb', '2018-03-05 02:50:19'),
(452, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 121, 'admin', 'deaaac525f0a37a00630b76a906c5dcb', '2018-03-05 02:50:42'),
(453, 'Insertion contrat abonnement', 'Insert', 'contrats', 95, 'admin', 'deaaac525f0a37a00630b76a906c5dcb', '2018-03-05 02:50:48'),
(454, 'Modification contrat abonnement', 'Update', 'contrats', 95, 'admin', 'deaaac525f0a37a00630b76a906c5dcb', '2018-03-05 02:51:49'),
(455, 'Validation contrat abonnement', 'Validate', 'contrats', 95, 'admin', 'deaaac525f0a37a00630b76a906c5dcb', '2018-03-05 02:51:55'),
(456, 'Insertion contrat abonnement', 'Insert', 'contrats', 96, 'admin', '8e9acec19d3af26ee9fd8f58aed85fae', '2018-03-19 23:20:54'),
(457, 'Validation contrat abonnement', 'Validate', 'contrats', 74, 'admin', '8e9acec19d3af26ee9fd8f58aed85fae', '2018-03-19 23:20:55'),
(458, 'Validation encaissement', 'Validate', 'encaissements', 12, 'admin', '78fbc6cac43a996c6cab93917f78f105', '2018-04-05 19:36:05'),
(459, 'Validation encaissement', 'Validate', 'encaissements', 13, 'admin', '78fbc6cac43a996c6cab93917f78f105', '2018-04-05 19:36:21'),
(460, 'Création tickets', 'Insert', 'tickets', 13, 'admin', 'c446b463f98b70f8b93b9885d60b2445', '2018-04-05 21:54:28'),
(461, 'Validation devis 61', 'Update', 'devis', 61, 'admin', 'b5d28edfcf259922637da1dc8ced1c9c', '2018-04-06 20:04:24'),
(462, 'Expédition devis 61', 'Update', 'devis', 61, 'admin', 'b5d28edfcf259922637da1dc8ced1c9c', '2018-04-06 20:04:32'),
(463, 'Validation client #Devis:58', 'Update', 'devis', 58, 'admin', 'b5d28edfcf259922637da1dc8ced1c9c', '2018-04-06 20:04:44'),
(464, 'Validation client #Devis:61', 'Update', 'devis', 61, 'admin', 'b5d28edfcf259922637da1dc8ced1c9c', '2018-04-06 20:05:17'),
(465, 'Insertion contrat abonnement', 'Insert', 'contrats', 97, 'admin', 'b5d28edfcf259922637da1dc8ced1c9c', '2018-04-06 20:08:07'),
(466, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 122, 'admin', 'b5d28edfcf259922637da1dc8ced1c9c', '2018-04-06 20:08:07'),
(467, 'Validation contrat abonnement', 'Validate', 'contrats', 97, 'admin', 'b5d28edfcf259922637da1dc8ced1c9c', '2018-04-06 20:08:21'),
(468, 'Modification contrat abonnement', 'Update', 'contrats', 97, 'admin', 'b5d28edfcf259922637da1dc8ced1c9c', '2018-04-06 20:11:11'),
(469, 'Suppression échéance abonnement', 'Delete', 'contrats', 97, 'admin', 'b5d28edfcf259922637da1dc8ced1c9c', '2018-04-06 20:11:11'),
(470, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 123, 'admin', 'b5d28edfcf259922637da1dc8ced1c9c', '2018-04-06 20:11:11'),
(471, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 124, 'admin', 'b5d28edfcf259922637da1dc8ced1c9c', '2018-04-06 20:11:11'),
(472, 'Validation contrat abonnement', 'Validate', 'contrats', 97, 'admin', 'b5d28edfcf259922637da1dc8ced1c9c', '2018-04-06 20:11:19'),
(473, 'Enregistrement Détail Devis 228', 'Insert', 'd_devis', 228, 'admin', 'b5d28edfcf259922637da1dc8ced1c9c', '2018-04-06 20:58:19'),
(474, 'Enregistrement Devis 64', 'Insert', 'devis', 64, 'admin', 'b5d28edfcf259922637da1dc8ced1c9c', '2018-04-06 20:58:36'),
(475, 'Insertion contrat abonnement', 'Insert', 'contrats', 98, 'admin', 'b5d28edfcf259922637da1dc8ced1c9c', '2018-04-06 21:44:15'),
(476, 'Validation contrat abonnement', 'Validate', 'contrats', 77, 'admin', 'b5d28edfcf259922637da1dc8ced1c9c', '2018-04-06 21:44:15'),
(477, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 125, 'admin', 'b5d28edfcf259922637da1dc8ced1c9c', '2018-04-06 21:44:15'),
(478, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 126, 'admin', 'b5d28edfcf259922637da1dc8ced1c9c', '2018-04-06 21:44:15'),
(479, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 127, 'admin', 'b5d28edfcf259922637da1dc8ced1c9c', '2018-04-06 21:44:15'),
(480, 'Validation contrat abonnement', 'Validate', 'contrats', 98, 'admin', 'b5d28edfcf259922637da1dc8ced1c9c', '2018-04-06 21:44:22'),
(481, 'Création action', 'Insert', 'action_ticket', NULL, 'admin', '225a6e3b4f26b211a5f3dde1c1f68678', '2018-04-21 18:39:56'),
(482, 'Modification tickets action', 'Update', 'action_ticket', 1, 'admin', '225a6e3b4f26b211a5f3dde1c1f68678', '2018-04-21 18:39:56'),
(483, 'Création action', 'Insert', 'action_ticket', NULL, 'admin', '225a6e3b4f26b211a5f3dde1c1f68678', '2018-04-21 18:40:53'),
(484, 'Modification tickets action', 'Update', 'action_ticket', 1, 'admin', '225a6e3b4f26b211a5f3dde1c1f68678', '2018-04-21 18:40:53'),
(485, 'Création action', 'Insert', 'action_ticket', 26, 'admin', '225a6e3b4f26b211a5f3dde1c1f68678', '2018-04-21 18:48:55'),
(486, 'Création action', 'Insert', 'action_ticket', 27, 'admin', '225a6e3b4f26b211a5f3dde1c1f68678', '2018-04-21 18:49:43'),
(487, 'Changement ETAT  tickets', 'Update', 'tickets', 15, 'admin', '05803aaa78697673f75c4b19de521281', '2018-04-23 18:36:45'),
(488, 'Affectation tickets', 'Update', 'tickets', 1, 'admin', '05803aaa78697673f75c4b19de521281', '2018-04-23 18:39:56'),
(489, 'Changement ETAT  tickets', 'Update', 'tickets', 14, 'admin', '05803aaa78697673f75c4b19de521281', '2018-04-23 18:39:56'),
(490, 'Création action', 'Insert', 'action_ticket', 28, 'admin', '05803aaa78697673f75c4b19de521281', '2018-04-23 18:41:06'),
(491, 'Changement ETAT  tickets', 'Update', 'tickets', 13, 'admin', '05803aaa78697673f75c4b19de521281', '2018-04-23 21:40:37'),
(492, 'Validation devis 64', 'Update', 'devis', 64, 'admin', 'fafaee0174d110578d50ab66e33a1afc', '2018-04-24 18:15:52'),
(493, 'Expédition devis 64', 'Update', 'devis', 64, 'admin', 'fafaee0174d110578d50ab66e33a1afc', '2018-04-24 18:16:02'),
(494, 'Création entrepots', 'Insert', 'entrepots', 1, 'admin', '5936e0f9fb0b7434c2d38fcb04f612bf', '2018-04-25 18:14:07'),
(495, 'Modification entrepots', 'Update', 'entrepots', 1, 'admin', '5936e0f9fb0b7434c2d38fcb04f612bf', '2018-04-25 18:51:00'),
(496, 'Modification entrepots', 'Update', 'entrepots', 1, 'admin', '5936e0f9fb0b7434c2d38fcb04f612bf', '2018-04-25 18:51:12'),
(497, 'Modification Entrepôt', 'Update', 'entrepots', 1, 'admin', '5936e0f9fb0b7434c2d38fcb04f612bf', '2018-04-25 18:53:13'),
(498, 'Création Entrepôt', 'Insert', 'entrepots', 2, 'admin', '5936e0f9fb0b7434c2d38fcb04f612bf', '2018-04-25 19:02:55'),
(499, 'Création Entrepôt', 'Insert', 'entrepots', 3, 'admin', '5936e0f9fb0b7434c2d38fcb04f612bf', '2018-04-25 19:05:30'),
(500, 'Changement ETAT  entrepots', 'Update', 'entrepots', 3, 'admin', '5936e0f9fb0b7434c2d38fcb04f612bf', '2018-04-25 19:12:22'),
(501, 'Changement ETAT  entrepots', 'Update', 'entrepots', 3, 'admin', '5936e0f9fb0b7434c2d38fcb04f612bf', '2018-04-25 19:13:19'),
(502, 'Changement ETAT  entrepots', 'Update', 'entrepots', 3, 'admin', '5936e0f9fb0b7434c2d38fcb04f612bf', '2018-04-25 19:16:51'),
(503, 'Changement ETAT  entrepots', 'Update', 'entrepots', 3, 'admin', '5936e0f9fb0b7434c2d38fcb04f612bf', '2018-04-25 19:19:33'),
(504, 'Changement ETAT  entrepots', 'Update', 'entrepots', 3, 'admin', '5936e0f9fb0b7434c2d38fcb04f612bf', '2018-04-25 19:19:49'),
(505, 'Insertion produit', 'Insert', 'produits', 31, 'admin', '8cc8d19824b332ed22a2919b73a73254', '2018-04-25 21:44:00'),
(506, 'Suppression produit', 'Delete', 'produits', 31, 'admin', '8cc8d19824b332ed22a2919b73a73254', '2018-04-25 21:32:35'),
(507, 'Insertion produit', 'Insert', 'produits', 32, 'admin', '8cc8d19824b332ed22a2919b73a73254', '2018-04-25 21:33:06'),
(508, 'Modification produit', 'Update', 'produits', 32, 'admin', '8cc8d19824b332ed22a2919b73a73254', '2018-04-25 21:33:31'),
(509, 'Validation produit', 'Validate', 'produits', 32, 'admin', '8cc8d19824b332ed22a2919b73a73254', '2018-04-25 21:33:37'),
(510, 'Enregistrement Détail Devis 229', 'Insert', 'd_devis', 229, 'admin', 'd548a3c85edc526f4fe07116d18993cf', '2018-04-29 09:54:17'),
(511, 'Enregistrement Devis 65', 'Insert', 'devis', 65, 'admin', 'd548a3c85edc526f4fe07116d18993cf', '2018-04-29 09:54:28'),
(512, 'Validation devis 65', 'Update', 'devis', 65, 'admin', 'd548a3c85edc526f4fe07116d18993cf', '2018-04-29 09:54:33'),
(513, 'Expédition devis 65', 'Update', 'devis', 65, 'admin', 'd548a3c85edc526f4fe07116d18993cf', '2018-04-29 09:54:40'),
(514, 'Validation client #Devis:61', 'Update', 'devis', 61, 'admin', 'd548a3c85edc526f4fe07116d18993cf', '2018-04-29 09:54:54'),
(515, 'Validation client #Devis:65', 'Update', 'devis', 65, 'admin', 'd548a3c85edc526f4fe07116d18993cf', '2018-04-29 09:55:39'),
(516, 'Enregistrement Détail Devis 230', 'Insert', 'd_devis', 230, 'admin', 'd548a3c85edc526f4fe07116d18993cf', '2018-04-29 10:38:30'),
(517, 'Enregistrement Devis 66', 'Insert', 'devis', 66, 'admin', 'd548a3c85edc526f4fe07116d18993cf', '2018-04-29 10:38:36'),
(518, 'Validation devis 66', 'Update', 'devis', 66, 'admin', 'd548a3c85edc526f4fe07116d18993cf', '2018-04-29 10:38:42'),
(519, 'Expédition devis 66', 'Update', 'devis', 66, 'admin', 'd548a3c85edc526f4fe07116d18993cf', '2018-04-29 10:38:48'),
(520, 'Validation client #Devis:66', 'Update', 'devis', 66, 'admin', 'd548a3c85edc526f4fe07116d18993cf', '2018-04-29 10:38:57'),
(521, 'Insertion contrat abonnement', 'Insert', 'contrats', 99, 'admin', 'd548a3c85edc526f4fe07116d18993cf', '2018-04-29 10:39:32'),
(522, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 128, 'admin', 'd548a3c85edc526f4fe07116d18993cf', '2018-04-29 10:39:32'),
(523, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 129, 'admin', 'd548a3c85edc526f4fe07116d18993cf', '2018-04-29 10:39:56'),
(524, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 130, 'admin', 'd548a3c85edc526f4fe07116d18993cf', '2018-04-29 10:40:24'),
(525, 'Suppression échéance contrat abonnement', 'Delete', 'echeances_contrat', NULL, 'admin', 'd548a3c85edc526f4fe07116d18993cf', '2018-04-29 10:41:01'),
(526, 'Suppression échéance contrat abonnement', 'Delete', 'echeances_contrat', NULL, 'admin', 'd548a3c85edc526f4fe07116d18993cf', '2018-04-29 10:41:07'),
(527, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 131, 'admin', 'd548a3c85edc526f4fe07116d18993cf', '2018-04-29 10:41:16'),
(528, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 132, 'admin', 'd548a3c85edc526f4fe07116d18993cf', '2018-04-29 10:41:23'),
(529, 'Affectation tickets', 'Update', 'tickets', 1, 'admin', 'd548a3c85edc526f4fe07116d18993cf', '2018-04-29 11:36:04'),
(530, 'Changement ETAT  tickets', 'Update', 'tickets', 13, 'admin', 'd548a3c85edc526f4fe07116d18993cf', '2018-04-29 11:36:04'),
(531, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 133, 'admin', 'd548a3c85edc526f4fe07116d18993cf', '2018-04-29 11:49:36'),
(532, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 134, 'admin', 'd548a3c85edc526f4fe07116d18993cf', '2018-04-29 11:57:46'),
(533, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 135, 'admin', 'd548a3c85edc526f4fe07116d18993cf', '2018-04-29 12:04:51'),
(534, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 136, 'admin', 'd548a3c85edc526f4fe07116d18993cf', '2018-04-29 12:10:42'),
(535, 'Modification échéance contrat abonnement', 'Update', 'echeances_contrat', 135, 'admin', 'd548a3c85edc526f4fe07116d18993cf', '2018-04-29 12:12:01'),
(536, 'Modification échéance contrat abonnement', 'Update', 'echeances_contrat', 136, 'admin', 'd548a3c85edc526f4fe07116d18993cf', '2018-04-29 12:12:10'),
(537, 'Modification échéance contrat abonnement', 'Update', 'echeances_contrat', 135, 'admin', 'd548a3c85edc526f4fe07116d18993cf', '2018-04-29 12:12:29'),
(538, 'Modification échéance contrat abonnement', 'Update', 'echeances_contrat', 136, 'admin', 'd548a3c85edc526f4fe07116d18993cf', '2018-04-29 12:12:37'),
(539, 'Insertion contrat abonnement', 'Insert', 'contrats', 100, 'admin', 'd548a3c85edc526f4fe07116d18993cf', '2018-04-29 12:12:39'),
(540, 'Modification échéance contrat abonnement', 'Update', 'echeances_contrat', 135, 'admin', 'd548a3c85edc526f4fe07116d18993cf', '2018-04-29 12:18:11'),
(541, 'Modification échéance contrat abonnement', 'Update', 'echeances_contrat', 135, 'admin', 'd548a3c85edc526f4fe07116d18993cf', '2018-04-29 12:18:21'),
(542, 'Validation contrat abonnement', 'Validate', 'contrats', 100, 'admin', 'd548a3c85edc526f4fe07116d18993cf', '2018-04-29 12:18:36'),
(543, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 137, 'admin', 'a14273369334b5cabb412690617c410e', '2018-04-29 14:15:56'),
(544, 'Insertion contrat abonnement', 'Insert', 'contrats', 101, 'admin', 'a14273369334b5cabb412690617c410e', '2018-04-29 14:51:39'),
(545, 'Validation contrat abonnement', 'Validate', 'contrats', 100, 'admin', 'a14273369334b5cabb412690617c410e', '2018-04-29 14:51:39'),
(546, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 138, 'admin', 'a14273369334b5cabb412690617c410e', '2018-04-29 14:51:39'),
(547, 'Suppression contrat abonnement', 'Delete', 'contrats', 101, 'admin', 'a14273369334b5cabb412690617c410e', '2018-04-29 14:50:37'),
(548, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 139, 'admin', '912950ce755130f82b18d9cdfbac1d99', '2018-04-29 18:31:57'),
(549, 'Insertion échéance contrat abonnement', 'Insert', 'echeances_contrat', 140, 'admin', '912950ce755130f82b18d9cdfbac1d99', '2018-04-29 18:33:05'),
(550, 'Modification échéance contrat abonnement', 'Update', 'echeances_contrat', 140, 'admin', '912950ce755130f82b18d9cdfbac1d99', '2018-04-29 18:33:28'),
(551, 'Insertion contrat abonnement', 'Insert', 'contrats', 102, 'admin', '912950ce755130f82b18d9cdfbac1d99', '2018-04-29 18:33:32'),
(552, 'Validation contrat abonnement', 'Validate', 'contrats', 100, 'admin', '912950ce755130f82b18d9cdfbac1d99', '2018-04-29 18:33:32');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Table des notification app' AUTO_INCREMENT=22 ;

--
-- Contenu de la table `sys_notifier`
--

INSERT INTO `sys_notifier` (`id`, `app`, `table`) VALUES
(9, 'clients', 'clients'),
(14, 'fournisseurs', 'fournisseurs'),
(16, 'devis', 'devis'),
(18, 'contrats_fournisseurs', 'contrats_frn'),
(20, 'produits', 'produits'),
(21, 'proforma', 'proforma');

-- --------------------------------------------------------

--
-- Structure de la table `sys_setting`
--

CREATE TABLE IF NOT EXISTS `sys_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identifiant de ligne',
  `key` varchar(30) DEFAULT '242' COMMENT 'clé paramètre',
  `value` varchar(200) CHARACTER SET latin1 NOT NULL COMMENT 'Valeur Paramètre',
  `comment` varchar(250) DEFAULT NULL COMMENT 'Description',
  `modul` int(11) NOT NULL COMMENT 'Module qui utilise paramètre',
  `etat` int(2) NOT NULL DEFAULT '1' COMMENT 'l''etat de la ligne',
  `creusr` varchar(60) NOT NULL COMMENT 'Crée par',
  `credat` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date de création',
  `updusr` varchar(60) DEFAULT NULL COMMENT 'Derniere modif par',
  `upddat` datetime DEFAULT NULL COMMENT 'Date derniere modif',
  PRIMARY KEY (`id`),
  KEY `fk_region_pays` (`key`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- Contenu de la table `sys_setting`
--

INSERT INTO `sys_setting` (`id`, `key`, `value`, `comment`, `modul`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(2, 'test', 'array(''1''=>''val1'', ''2''=>''val2'')', 'description test', 106, 1, '1', '2017-10-04 15:00:41', '1', '2017-10-04 15:10:40'),
(3, 'par2', '{"1":"val1", "2":"val2"}', 'Test array', 104, 1, '1', '2017-10-04 15:33:40', '1', '2017-10-04 15:51:42'),
(4, 'etat_valid_devis', '2', 'L''etat où le devis est validé pour exploitation dans le contrat', 105, 1, '1', '2017-10-06 15:01:26', NULL, NULL),
(5, 'abr_ste', 'GT', 'Abréviation  du nom Ste pour les Références documents', 5, 1, '1', '2017-10-09 15:22:50', NULL, NULL),
(6, 'send_mail_devis', 'false', 'Envoi devis par email ', 115, 1, '1', '2017-10-10 11:33:06', '1', '2017-10-10 11:40:47'),
(7, 'etat_valid_devis', '3', 'L''etat où le devis est validé pour exploitation dans le contrat', 115, 1, '1', '2017-10-10 11:44:31', NULL, NULL),
(8, 'etat_devis', '{"creat_devis":"0", "valid_devis":"1",  "send_devis":"2", "modif_client": "3", "valid_client":"4", "refus_client":"5", "devis_expir":"6", "devis_archive":"7"}', 'Les différents etat de WF devis', 115, 1, '1', '2017-10-12 12:33:14', '1', '2017-10-12 14:26:11'),
(9, 'etat_proforma', '{"creat_proforma":"0", "valid_proforma":"1",  "send_proforma":"2", "proforma_expir":"3", "proforma_archive":"4"}', 'Les différents etat de WF Proforma', 127, 1, '1', '2017-10-16 00:01:26', NULL, NULL),
(10, 'etat_devis', '{"0":"creat_devis"," 1":"valid_devis", "2":"send_devis", "3":"reponse_client", "4":"valid_client", "5":"refus_client"," 6":"devis_expir", "7":"devis_archive"}', 'Les différents etat de WF devis', 121, 1, '1', '2017-10-16 00:01:56', NULL, NULL),
(11, 'send_mail_devis', 'false', 'Envoi devis par email', 121, 1, '1', '2017-10-16 00:02:40', NULL, NULL),
(12, 'tva', '18', 'Valeur TVA', 93, 1, '1', '2017-10-17 01:38:52', NULL, NULL),
(13, 'commission', '{"attente_validation":"0","attente_payement":"1","payer_part":"2","payer_total":"3"}', 'commission', 1, 1, '1', '2018-03-01 20:32:36', NULL, NULL),
(15, 'etat_ticket', '{"attente_affectation":"0","resolution_encours":"1","resolution_termine":"2","ticket_cloturer":"3"}', 'tickets', 133, 1, '1', '2018-04-24 02:22:48', NULL, NULL);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Table Task of modules' AUTO_INCREMENT=879 ;

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
(709, 'user', 126, 'user', 'users', 1, 'Utilisateurs', 'users', 1, 0, 0, 'list', '[-1-]'),
(710, 'adduser', 126, 'adduser', 'users', 1, 'Ajouter Utilisateur', NULL, 1, 0, 0, 'form', '[-1-]'),
(711, 'edituser', 126, 'edituser', 'users', 1, 'Editer compte utilisateur', NULL, 1, 0, 0, 'form', '[-1-]'),
(712, 'rules', 126, 'rules', 'users', 1, 'Permission Utilisateur', 'users', 1, 0, 0, 'form', '[-1-]'),
(713, 'delete_user', 126, 'delete_user', 'users', 1, 'Supprimer utilisateur', 'trash', 1, 0, 0, 'exec', 'null'),
(714, 'compte', 126, 'compte', 'users', 1, 'Details profile utilisateur', NULL, 1, 1, 0, 'profil', '[-1-]'),
(715, 'activeuser', 126, 'activeuser', 'users', 1, 'Activer utilisateur', 'unlock', 1, 0, 0, 'exec', '[-1-]'),
(716, 'archiv_user', 126, 'archiv_user', 'users', 1, 'Désactiver l''utilisateur', 'ban', 1, 0, 0, 'exec', '[-1-]'),
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
(739, 'validbuyproduct', 128, 'validbuyproduct', 'produits', 1, 'Valider achat', 'cogs', 1, 0, 0, 'exec', '[-1-]'),
(752, 'commerciale', 130, 'commerciale', 'commerciale/main', 1, 'Commerciale', 'lock', 1, 0, 0, 'list', '[-1-]'),
(753, 'addcommerciale', 130, 'addcommerciale', 'commerciale/main', 1, 'Ajouter commerciale ', 'cogs', 1, 0, 0, 'formadd', '[-1-]'),
(754, 'editcommerciale', 130, 'editcommerciale', 'commerciale/main', 1, 'Modifier Commerciale', 'cogs', 1, 0, 0, 'formedit', '[-1-]'),
(755, 'validcommerciale', 130, 'validcommerciale', 'commerciale/main', 1, 'Valider commerciale', 'cogs', 1, 0, 0, 'exec', '[-1-]'),
(756, 'deletecommerciale', 130, 'deletecommerciale', 'commerciale/main', 1, 'Supprimer commerciale', 'cogs', 1, 0, 0, 'exec', '[-1-]'),
(757, 'detailscommerciale', 130, 'detailscommerciale', 'commerciale/main', 1, 'Détails commerciale', 'cogs', 1, 0, 0, 'profil', '[-1-]'),
(758, 'commissions', 130, 'commissions', 'commerciale/main', 1, 'Commissions', 'signal', 1, 0, 0, 'list', '[-1-]'),
(759, 'paycommission', 130, 'paycommission', 'commerciale/main', 1, 'Payer Commission', 'euro', 1, 0, 0, 'formpers', '[-1-]'),
(760, 'addcommissions', 130, 'addcommissions', 'commerciale/main', 1, 'Ajouter commission', 'cogs', 1, 0, 0, 'formadd', '[-1-]'),
(761, 'editcommissions', 130, 'editcommissions', 'commerciale/main', 1, 'Modifier commission', 'cogs', 1, 0, 0, 'formedit', '[-1-]'),
(762, 'validcommission', 130, 'validcommission', 'commerciale/main', 1, 'Valider Commission', 'cogs', 1, 0, 0, 'exec', '[-1-]'),
(763, 'detailscommission', 130, 'detailscommission', 'commerciale/main', 1, 'Détails commission', 'cogs', 1, 0, 0, 'profil', '[-1-]'),
(768, 'contrats', 131, 'contrats', 'vente/submodul/contrats', 1, 'Abonnements', 'cloud', 1, 0, 0, 'list', '[-1-2-3-5-]'),
(769, 'addcontrat', 131, 'addcontrat', 'vente/submodul/contrats', 1, 'Ajouter contrat', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(770, 'editcontrat', 131, 'editcontrat', 'vente/submodul/contrats', 1, 'Modifier contrat', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(771, 'deletecontrat', 131, 'deletecontrat', 'vente/submodul/contrats', 1, 'Supprimer contrat', 'cogs', 1, 0, 0, 'exec', '[-1-]'),
(772, 'validcontrat', 131, 'validcontrat', 'vente/submodul/contrats', 1, 'Valider contrat', 'cogs', 1, 0, 0, 'exec', '[-1-]'),
(773, 'detailcontrat', 131, 'detailcontrat', 'vente/submodul/contrats', 1, 'Détail contrat', 'cogs', 1, 0, 0, 'profil', '[-1-]'),
(774, 'addecheance_contrat', 131, 'addecheance_contrat', 'vente/submodul/contrats', 1, 'Ajouter échéance contrat', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(775, 'editecheance_contrat', 131, 'editecheance_contrat', 'vente/submodul/contrats', 1, 'Modifier échéance contrat', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(776, 'renouvelercontrat', 131, 'renouvelercontrat', 'vente/submodul/contrats', 1, 'Renouveler Contrat', 'exchange', 1, 0, 0, 'form', '[-1-]'),
(777, 'resiliercontrat', 131, 'resiliercontrat', 'vente/submodul/contrats', 1, 'Résilier Contrat', 'trash', 1, 0, 0, 'exec', '[-1-2-3-5-]'),
(778, 'echeances', 131, 'echeances', 'vente/submodul/contrats', 1, 'Echéances', 'exchange', 1, 0, 0, 'list', '[-1-2-3-5-]'),
(779, 'generatefacture', 131, 'generatefacture', 'vente/submodul/contrats', 1, 'Générer Facture', 'book', 1, 0, 0, 'exec', '[-1-2-3-5-]'),
(780, 'afficherfacture', 131, 'afficherfacture', 'vente/submodul/contrats', 1, 'Afficher Facture', 'eye', 1, 0, 0, 'profil', '[-1-2-3-5-]'),
(796, 'factures', 133, 'factures', 'factures/main', 1, 'Gestion des factures', 'file', 1, 0, 0, 'list', '[-1-2-3-5-]'),
(797, 'complements', 133, 'complements', 'factures/main', 1, 'complements', 'cogs', 1, 0, 0, 'list', '[-1-]'),
(798, 'addcomplement', 133, 'addcomplement', 'factures/main', 1, 'Ajouter complément', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(799, 'encaissements', 133, 'encaissements', 'factures/main', 1, 'Encaissement', 'cogs', 1, 0, 0, 'list', '[-1-]'),
(800, 'addencaissements', 133, 'addencaissements', 'factures/main', 1, 'Ajouter encaissement', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(801, 'editcomplement', 133, 'editcomplement', 'factures/main', 1, 'Modifier complément', 'cogs', 1, 0, 0, 'form', '[-1-]'),
(802, 'editencaissement', 133, 'editencaissement', 'factures/main', 1, 'Modifier encaissement', 'cogs', 1, 0, 0, 'list', '[-1-]'),
(803, 'deletecomplement', 133, 'deletecomplement', 'factures/main', 1, 'Supprimer complément', 'cogs', 1, 0, 0, 'exec', '[-1-]'),
(804, 'deleteencaissement', 133, 'deleteencaissement', 'factures/main', 1, 'Supprimer encaissement', 'cogs', 1, 0, 0, 'exec', '[-1-]'),
(805, 'validfacture', 133, 'validfacture', 'factures/main', 1, 'Valider facture', 'cogs', 1, 0, 0, 'exec', '[-1-]'),
(806, 'detailsencaissement', 133, 'detailsencaissement', 'factures/main', 1, 'Détail encaissement', 'eye', 1, 0, 0, 'profil', '[-1-]'),
(807, 'detailsfacture', 133, 'detailsfacture', 'factures/main', 1, 'Détails facture', 'eye', 1, 0, 0, 'profil', '[-1-]'),
(808, 'rejectfacture', 133, 'rejectfacture', 'factures/main', 1, 'Désactiver Facture', 'remove', 1, 0, 0, 'exec', '[-1-]'),
(809, 'validencaissement', 133, 'validencaissement', 'factures/main', 1, 'Valider encaissement', 'cogs', 1, 0, 0, 'exec', '[-1-2-3-5-]'),
(810, 'sendfacture', 133, 'sendfacture', 'factures/main', 1, 'Envoyer Facture', 'cogs', 1, 0, 0, 'exec', '[-1-2-3-5-]'),
(858, 'stock', 138, 'stock', 'stock/main', 1, 'Gestion de Stock', 'barcode ', 1, 0, 0, 'list', '[-1-]'),
(859, 'entrepots', 139, 'entrepots', 'stock/submodul/entrepots', 1, 'Gestion des Entrepôts', 'briefcase ', 1, 0, 0, 'list', '[-1-]'),
(860, 'addentrepots', 139, 'addentrepots', 'stock/submodul/entrepots', 1, 'Ajouter Entrepôt', 'briefcase ', 1, 0, 0, 'formadd', '[-1-]'),
(861, 'editentrepots', 139, 'editentrepots', 'stock/submodul/entrepots', 1, 'Editer Entrepôt', 'pencil', 1, 0, 0, 'formedit', '[-1-]'),
(862, 'deleteentrepots', 139, 'deleteentrepots', 'stock/submodul/entrepots', 1, 'Supprimer Entrepôt', 'trash', 1, 0, 0, 'exec', '[-1-]'),
(863, 'validentrepots', 139, 'validentrepots', 'stock/submodul/entrepots', 1, 'Valider Entrepôt', 'check', 1, 0, 0, 'exec', '[-1-]'),
(864, 'mouvements_stock', 140, 'mouvements_stock', 'stock/submodul/mouvements_stock', 1, 'Mouvements de Stock', 'refresh', 1, 0, 0, 'list', '[-1-]'),
(865, 'tickets', 141, 'tickets', 'tickets/main', 1, 'Gestion Tickets', 'bookmark ', 1, 0, 0, 'list', '[-1-]'),
(866, 'addtickets', 141, 'addtickets', 'tickets/main', 1, 'Ajouter ticket', 'cogs', 1, 0, 0, 'formadd', '[-1-]'),
(867, 'editticket', 141, 'editticket', 'tickets/main', 1, 'Modifier ticket', 'cogs', 1, 0, 0, 'formedit', '[-1-]'),
(868, 'deleteticket', 141, 'deleteticket', 'tickets/main', 1, 'Supprimer ticket', 'cogs', 1, 0, 0, 'exec', '[-1-]'),
(869, 'reafectticket', 141, 'reafectticket', 'tickets/main', 1, 'Reafecter technicien', 'cogs', 1, 0, 0, 'formadd', '[-1-]'),
(870, 'affecttechnicien', 141, 'affecttechnicien', 'tickets/main', 1, 'Affecter ticket', 'cogs', 1, 0, 0, 'formadd', '[-1-]'),
(871, 'detailsticket', 141, 'detailsticket', 'tickets/main', 1, 'Détails ticket', 'eye', 1, 0, 0, 'profil', '[-1-]'),
(872, 'reaffectticket', 141, 'reaffectticket', 'tickets/main', 1, 'Réaffecter ticket', 'cogs', 1, 0, 0, 'formedit', '[-1-]'),
(873, 'addaction', 141, 'addaction', 'tickets/main', 1, 'Ajouter action', 'cogs', 1, 0, 0, 'formadd', '[-1-]'),
(874, 'deleteactionticket', 141, 'deleteactionticket', 'tickets/main', 1, 'Supprimer ticket action', 'trash', 1, 0, 0, 'exec', '[-1-]'),
(875, 'editaction', 141, 'editaction', 'tickets/main', 1, 'Modifier action', 'pen blue', 1, 0, 0, 'formedit', '[-1-]'),
(876, 'resolution', 141, 'resolution', 'tickets/main', 1, 'Résolution Réussi', 'cogs', 1, 0, 0, 'exec', '[-1-]'),
(877, 'clotureticket', 141, 'clotureticket', 'tickets/main', 1, 'Ticket cloturé', 'cogs', 1, 0, 0, 'exec', '[-1-]'),
(878, 'detailsaction', 141, 'detailsaction', 'tickets/main', 1, 'Détails action', 'cogs', 1, 0, 0, 'profil', '[-1-]');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Table of Task_Action and Permission of Task' AUTO_INCREMENT=1442 ;

--
-- Contenu de la table `task_action`
--

INSERT INTO `task_action` (`id`, `appid`, `idf`, `descrip`, `mode_exec`, `app`, `class`, `code`, `type`, `service`, `etat_line`, `notif`, `etat_desc`, `message_class`, `message_etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(28, 34, '83b9fa44466da4bcd7f8304185bfeac8', 'Services', NULL, 'services', NULL, '', 0, '[-1-]', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(29, 35, '55043bc4207521e3010e91d6267f5302', 'Ajouter Service', NULL, 'addservice', NULL, '', 1, '[-1-2-4-]', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(30, 36, '2fea3d893f6b6e81467ddd2a744e4a76', 'Modifier Service', NULL, 'editservice', NULL, '', 1, '[-1-2-4-]', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(31, 37, '1a0d5897d31b4d5e29022671c1112f59', 'Valider Service', NULL, 'validservice', NULL, '', 1, '[-1-2-4-]', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(32, 38, '42083d4e159baf7c2ace2bb977e2b0a0', 'Supprimer Service', NULL, 'deletservice', NULL, '', 1, '[-1-2-4-]', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(33, 34, '3c388c1e842851df49abe9ee73c0a2e7', 'Valider Service', 'this_exec', 'validservices', 'check', '<li><a href="#" class="this_exec" data="%id%" rel="validservices"  ><i class="ace-icon fa fa-check bigger-100"></i> Valider Service</a></li>', 0, '[-1-2-4-]', 0, 1, 'Attente validation', 'warning', '<span class="label label-sm label-warning">Attente validation</span>', NULL, NULL, NULL, NULL),
(34, 34, 'dcb9555d5ca1d108e9fa95daa9da4b3a', 'Supprimer Service', 'this_exec', 'deleteservices', 'trash red', '<li><a href="#" class="this_exec" data="%id%" rel="deleteservices"  ><i class="ace-icon fa fa-trash red bigger-100"></i> Supprimer Service</a></li>', 0, '[-1-2-4-]', 1, 0, 'Service validé', 'success', '<span class="label label-sm label-success">Service validé</span>', NULL, NULL, NULL, NULL),
(114, 89, '2c3b01c696ff401a2ac9ffedb7a06e4a', 'Gestion Villes', NULL, 'villes', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(115, 90, 'e152b9052d3dcfcac593489dbdc0f61c', 'Ajouter ville', NULL, 'addville', NULL, '', 1, '[-1-2-]', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(116, 91, '3107e0cd0e0df14c4e94aa088e4457d7', 'Editer Ville', NULL, 'editville', NULL, '', 1, '[-1-2-]', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(117, 92, 'da79d9214ed5819d7f4f1e3070629a3d', 'Supprimer Ville', NULL, 'deleteville', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(119, 89, 'b9649163b368f863a0e8036f11cd81ae', 'Editer Ville', 'this_url', 'villes', NULL, '<li><a href="#" class="this_url" data="%id%" rel="editville"  ><i class="ace-icon fa fa-pencil bigger-100"></i> Editer Ville</a></li>', 0, '[-1-]', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(121, 89, '89dec6dabcb210cdb9dd28bbef90d43e', 'Editer Ville', 'this_url', 'villes', NULL, '<li><a href="#" class="this_url" data="%id%" rel="editville"  ><i class="ace-icon fa fa-pencil bigger-100"></i> Editer Ville</a></li>', 0, '[-1-]', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(144, 34, '74950fb3fd858404b6048c1e81bd7c9a', 'Modifier Service', 'this_url', 'editservices', 'pencil-square-o blue', '<li><a href="#" class="this_url" data="%id%" rel="editservices"  ><i class="ace-icon fa fa-pencil-square-o blue bigger-100"></i> Modifier Service</a></li>', 0, '[-1-]', 1, 0, 'Service validé', 'success', '<span class="label label-sm label-success">Service validé</span>', NULL, NULL, NULL, NULL),
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
(810, 542, '72db1c2280dc3eb6405908c1c5b6c815', 'Information société', NULL, 'info_ste', NULL, '', 0, '[-1-3-]', 0, 0, 'Confirmé', 'success', '<span class="label label-sm label-success">Confirmé</span>', NULL, NULL, NULL, NULL),
(811, 543, 'a1c5a2657cc1b2ff6f85c6fe8f1c51ac', 'Paramètrage Système', NULL, 'sys_setting', NULL, '', 0, '[-1-]', 0, 0, 'Rien', 'success', '<span class="label label-sm label-success">Rien</span>', NULL, NULL, NULL, NULL),
(812, 543, 'de6285d9c0027ff8bccdf2af385ac337', 'Editer paramètre', 'this_url', 'edit_sys_setting', NULL, '<li><a href="#" class="this_url" data="%id%" rel="edit_sys_setting"  ><i class="ace-icon fa fa-pen blue bigger-100"></i> Editer paramètre</a></li>', 0, '[-1-]', 1, 0, 'Active', 'success', '<span class="label label-sm label-success">Active</span>', NULL, NULL, NULL, NULL),
(813, 544, '82f83d9d3d30fdef00d4c3ef96f0f899', 'Ajouter Paramètre', NULL, 'add_sys_setting', NULL, '', 0, '[-1-]', 1, 0, 'Confirmé', 'success', '<span class="label label-sm label-success">Confirmé</span>', NULL, NULL, NULL, NULL),
(814, 545, 'f0e54f346e9dcfdff65274709ce2c8ca', 'Editer paramètre', NULL, 'edit_sys_setting', NULL, '', 0, '[-1-]', 1, 0, 'Validé', 'success', '<span class="label label-sm label-success">Validé</span>', NULL, NULL, NULL, NULL),
(815, 546, 'aaccd24eaf085b8f18115c9c7653d401', 'Supprimer Paramètre', NULL, 'delete_sys_setting', NULL, '', 0, '[-1-]', 1, 0, 'Active', 'success', '<span class="label label-sm label-success">Active</span>', NULL, NULL, NULL, NULL),
(920, 609, 'ec45512f34613446e7a2e367d4b4cfbd', 'Gestion Contrats Fournisseurs', NULL, 'contrats_fournisseurs', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(921, 609, 'e3c0d7e92dad7f8794b2415c334ec3ff', 'Editer Contrat', 'this_url', 'editcontrat_frn', NULL, '<li><a href="#" class="this_url" data="%id%" rel="editcontrat_frn"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Editer Contrat</a></li>', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(922, 609, '9dfff1c8dcb804837200f38e95381420', 'Valider Contrat', 'this_exec', 'validcontrat_frn', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validcontrat_frn"  ><i class="ace-icon fa fa-lock bigger-100"></i> Valider Contrat</a></li>', 0, '[-1-]', 0, 1, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(923, 609, '9fe39b496077065105a57ccd9ed05863', 'Désactiver Contrat', 'this_exec', 'validcontrat_frn', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validcontrat_frn"  ><i class="ace-icon fa fa-unlock bigger-100"></i> Désactiver Contrat</a></li>', 0, '[-1-]', 1, 0, 'Contrat Validé', 'success', '<span class="label label-sm label-success">Contrat Validé</span>', NULL, NULL, NULL, NULL),
(924, 609, 'faee342ff51dbe9f835529ae5b9b2a0b', 'Détails  Contrat ', 'this_url', 'detailscontrat_frn', NULL, '<li><a href="#" class="this_url" data="%id%" rel="detailscontrat_frn"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails  Contrat </a></li>', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(925, 609, '83406b6b206ed08878f2b2e854932ae5', 'Détails   Contrat  ', 'this_url', 'detailscontrat_frn', NULL, '<li><a href="#" class="this_url" data="%id%" rel="detailscontrat_frn"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails   Contrat  </a></li>', 0, '[-1-]', 1, 0, 'Client Validé', 'success', '<span class="label label-sm label-success">Client Validé</span>', NULL, NULL, NULL, NULL),
(926, 609, '8447888bef30fb983477cc1357ff7e6f', 'Détails    Contrat ', 'this_url', 'detailscontrat_frn', NULL, '<li><a href="#" class="this_url" data="%id%" rel="detailscontrat_frn"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails    Contrat </a></li>', 0, '[-1-]', 3, 0, 'Contrat Expiré', 'info', '<span class="label label-sm label-info">Contrat Expiré</span>', NULL, NULL, NULL, NULL),
(927, 609, '4cc1845128f6a5ff3ed01100292d8ebb', '  Détails    Contrat', 'this_url', 'detailscontrat_frn', NULL, '<li><a href="#" class="this_url" data="%id%" rel="detailscontrat_frn"  ><i class="ace-icon fa fa-eye bigger-100"></i>   Détails    Contrat</a></li>', 0, '[-1-]', 2, 0, 'Attente Renouvelement', 'danger', '<span class="label label-sm label-danger">Attente Renouvelement</span>', NULL, NULL, NULL, NULL),
(928, 609, 'cd82d84c5f70a633b10aae88c34e9159', '  Renouveler   Contrat ', 'this_url', 'renouveler_contrat', NULL, '<li><a href="#" class="this_url" data="%id%" rel="renouveler_contrat"  ><i class="ace-icon fa fa-exchange bigger-100"></i>   Renouveler   Contrat </a></li>', 0, '[-1-]', 2, 1, 'Attente Renouvelement', 'danger', '<span class="label label-sm label-danger">Attente Renouvelement</span>', NULL, NULL, NULL, NULL),
(929, 609, 'e9e994a0f8a204f1323fca7ce30931fe', ' Détails  Contrat ', 'this_url', 'detailscontrat_frn', NULL, '<li><a href="#" class="this_url" data="%id%" rel="detailscontrat_frn"  ><i class="ace-icon fa fa-eye bigger-100"></i>  Détails  Contrat </a></li>', 0, '[-1-]', 4, 0, 'Contrat Expiré', 'inverse', '<span class="label label-sm label-inverse">Contrat Expiré</span>', NULL, NULL, NULL, NULL),
(930, 609, 'b9e0a2a0236899590c72d31b878edfb2', ' Renouveler  Contrat ', 'this_url', 'renouveler_contrat', NULL, '<li><a href="#" class="this_url" data="%id%" rel="renouveler_contrat"  ><i class="ace-icon fa fa-exchange bigger-100"></i>  Renouveler  Contrat </a></li>', 0, '[-1-]', 3, 0, 'Contrat Expiré', 'info', '<span class="label label-sm label-info">Contrat Expiré</span>', NULL, NULL, NULL, NULL),
(931, 610, 'ded24eb817021c5a666a677b1565bc5e', 'Ajouter Contrat', NULL, 'addcontrat_frn', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(932, 611, 'ed6b8695494bf4ed86d5fb18690b3a59', 'Editer Contrat', NULL, 'editcontrat_frn', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(933, 612, 'b8a40913b5955209994aaa26d0e8c3d4', 'Supprimer Contrat', NULL, 'deletecontrat_frn', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(934, 613, '5efb874e7d73ccd722df806e8275770f', 'Valider Contrat', NULL, 'validcontrat_frn', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(935, 614, '64a5f976687a8c5f7cd3d672cc5d9c8c', 'Détails Contrat', NULL, 'detailscontrat_frn', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(936, 615, '2cc55c65e79534161108288adb00472b', 'Renouveler  Contrat', NULL, 'renouveler_contrat', NULL, '', 0, '[-1-]', 0, 0, 'Attente Renouvelement', 'danger', '<span class="label label-sm label-danger">Attente Renouvelement</span>', NULL, NULL, NULL, NULL),
(978, 637, 'b8e62907d367fb44d644a5189cd07f42', 'Modules', NULL, 'modul', NULL, '', 1, 'null', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(979, 637, '05ce9e55686161d99e0714bb86243e5b', 'Editer Module', 'this_url', 'modul', NULL, '<li><a href="#" class="this_url" data="%id%" rel="editmodul" >\n      <i class="ace-icon fa fa-pencil bigger-100"></i> Editer Module\n    </a></li>', 0, '-1-2-', 0, 1, NULL, NULL, '', NULL, NULL, NULL, NULL),
(980, 637, '819cf9c18a44cb80771a066768d585f2', 'Exporter Module', NULL, 'modul', NULL, '<li><a href="#" class="export_mod" data="%id%&export=1&mod=%id%" rel="modul" item="%id%" >\n      <i class="ace-icon fa fa-download bigger-100"></i> Exporter Module\n    </a></li>', 0, '-1-2-', 0, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(981, 637, 'd2fc3ee15cee5208a8b9c70f1e53c196', 'Liste task modul', 'this_url', 'modul', NULL, '<li><a href="#" class="this_url" data="%id%" rel="task" >\n     <i class="ace-icon fa fa-external-link bigger-100"></i>Application associes\n    </a></li>', 0, '-1-2-', 0, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(982, 637, 'ad75e6b877f20e3d6fc1789da4dcb3e6', 'Editer Module', 'this_url', 'modul', NULL, '<li><a href="#" class="this_url" data="%id%" rel="editmodulsetting"  ><i class="ace-icon fa fa-pencil bigger-100"></i> Editer Module</a></li>', 0, '[-1-]', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(983, 637, '064a9b0eff1006fd4f25cb4eaf894ca1', 'Liste task modul Setting', 'this_url', 'modul', NULL, '<li><a href="#" class="this_url" data="%id%" rel="task" >\n     <i class="ace-icon fa fa-external-link bigger-100"></i>Application associes\n    </a></li>', 0, '-1-2-', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(984, 637, 'ac4eb0c94da00a48ad5d995f5e9e9366', 'MAJ Module', 'this_exec', 'update_module', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="update_module"  ><i class="ace-icon fa fa-pencil-square-o bigger-100"></i> MAJ Module</a></li>', 0, '[-1-]', 0, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(985, 638, '44bd5341b0ab41ced21db8b3e92cf5aa', 'Ajouter un Modul', NULL, 'addmodul', NULL, '', 1, '-1-2-', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(986, 640, '8653b156f1a4160a12e5a94b211e59a2', 'Liste Action Task', 'this_url', 'task', NULL, '<li><a href="#" class="this_url" data="%id%" rel="taskaction"  >\n     <i class="ace-icon fa fa-external-link bigger-100"></i>Application associes\n    </a></li>', 0, '-1-2-', 0, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(987, 640, '86aced763bc02e1957a5c740fb37b4f7', 'Supprimer Application', 'this_exec', 'task', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="task"  ><i class="ace-icon fa fa-draft bigger-100"></i> Supprimer Application</a></li>', 0, '[-1-]', 0, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(988, 640, 'f07352e32fe86da1483c6ab071b7e7a9', 'Ajout Affichage WF', 'this_url', 'task', NULL, '<li><a href="#" class="this_url" data="%id%" rel="addetatrule"  ><i class="ace-icon fa fa-eye bigger-100"></i> Ajout Affichage WF</a></li>', 0, '[-1-]', 0, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(989, 641, '1c452aff8f1551b3574e15b74147ea56', 'Ajouter Task Modul', NULL, 'addtask', NULL, '', 1, '-1-2-', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(990, 642, 'f085fe4610576987db963501297e4d91', 'Editer Task Modul', NULL, 'edittask', NULL, '', 1, '-1-2-', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(991, 642, '38702c272a6f4d334c2f4c3684c8b163', 'Ajouter action modul', NULL, 'edittask', NULL, '', 1, '-1-2-', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(992, 643, 'cbae1ebe850f6dd8841426c6fedf1785', 'Liste Action Task', NULL, 'taskaction', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(993, 643, 'e30471396f9b86ccdcc94943d80b679a', 'Editer Task Action', 'this_url', 'taskaction', NULL, '<li><a href="#" class="this_url" data="%id%" rel="edittaskaction"  ><i class="ace-icon fa fa-pencil bigger-100"></i> Editer Task Action</a></li>', 0, '[-1-]', 0, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(994, 644, '502460cd9327b46ee7af0a258ebf8c80', 'Ajouter Action Task', NULL, 'addtaskaction', NULL, '', 1, '[-1-3-]', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(995, 645, '13c107211904d4a2e65dd65c60ec7272', 'Supprimer Application', NULL, 'deletetask', NULL, '', 1, 'null', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(996, 646, '8c8acf9cf3790b16b1fae26823f45eab', 'Importer des modules', NULL, 'importmodul', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(997, 647, '2f4518dab90b706e2f4acd737a0425d8', 'Ajouter Module paramétrage', NULL, 'addmodulsetting', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(998, 648, '8e0c0212d8337956ac2f4d6eb180d74b', 'Editer Module paramètrage', NULL, 'editmodulsetting', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(999, 649, 'fc54953b47b7fcb11cc14c0c2e2125f0', 'Ajouter Autorisation Etat', NULL, 'addetatrule', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(1000, 650, '966ec2dd83e6006c2d0ff1d1a5f12e33', 'Editer Task Action', NULL, 'edittaskaction', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(1001, 651, '3473119f6683893a3f1372dbf7d811e1', 'MAJ Module', NULL, 'update_module', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(1002, 652, '2e2346bd422536c1d996ff25f9e71357', 'Dupliquer Action Task', NULL, 'dupliqtaskaction', NULL, '', 0, '[-1-]', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(1003, 653, '8a3634181ae5bc9223b689a310158962', 'Supprimer Task action', NULL, 'deletetaskaction', NULL, '', 0, '[-1-]', 0, 0, 'Not Message', 'success', '<span class="label label-sm label-success">Not Message</span>', NULL, NULL, NULL, NULL),
(1004, 654, '8afb3c669307183cd3b7d189fbf204d7', 'Affichage Work Flow', NULL, 'workflow', NULL, '', 0, '[-1-]', 0, 0, 'Work flow', 'success', '<span class="label label-sm label-success">Work flow</span>', NULL, NULL, NULL, NULL),
(1005, 655, '0e79510db7f03b9b6266fc7b4a612153', 'Gestion Devis', NULL, 'devis', NULL, '', 1, '[-1-2-]', 0, 1, 'Attente validation', 'warning', '<span class="label label-sm label-warning">Attente validation</span>', NULL, NULL, NULL, NULL),
(1006, 655, 'c15b00a1e37657336df8b6aa0eea2db5', 'Modifier Devis', 'this_url', 'editdevis', NULL, '<li><a href="#" class="this_url" data="%id%" rel="editdevis"  ><i class="ace-icon fa fa-pencil-square-o blue bigger-100"></i> Modifier Devis</a></li>', 0, '[-1-2-]', 0, 1, 'Attente validation', 'warning', '<span class="label label-sm label-warning">Attente validation</span>', NULL, NULL, NULL, NULL),
(1007, 655, '998fb803f2e64f22418b3b388d6240a4', 'Envoi Devis au client', 'this_exec', 'validdevis', 'envelope blue', '<li><a href="#" class="this_exec" data="%id%" rel="validdevis"  ><i class="ace-icon fa fa-envelope blue bigger-100"></i> Envoi Devis au client</a></li>', 0, '[-1-2-3-]', 1, 1, 'Attente Expédition', 'info', '<span class="label label-sm label-info">Attente Expédition</span>', NULL, NULL, NULL, NULL),
(1008, 655, '28e267a2a0647d4cb37b18abb1e7d051', 'Voir détails', 'this_url', 'viewdevis', NULL, '<li><a href="#" class="this_url" data="%id%" rel="viewdevis"  ><i class="ace-icon fa fa-eye bigger-100"></i> Voir détails</a></li>', 0, '[-1-2-3-5-]', 1, 1, 'Attente Expédition', 'info', '<span class="label label-sm label-info">Attente Expédition</span>', NULL, NULL, NULL, NULL),
(1009, 655, 'd34b07afd92adad84e1c4c2ebd92ba95', 'Voir détails', 'this_url', 'viewdevis', NULL, '<li><a href="#" class="this_url" data="%id%" rel="viewdevis"  ><i class="ace-icon fa fa-eye bigger-100"></i> Voir détails</a></li>', 0, '[-1-2-3-5-]', 0, 1, 'Attente validation', 'warning', '<span class="label label-sm label-warning">Attente validation</span>', NULL, NULL, NULL, NULL),
(1010, 655, 'f6f55b9d0ba9d704b2861d57cda32477', 'Réponse Client', 'this_url', 'validdevisclient', NULL, '<li><a href="#" class="this_url" data="%id%" rel="validdevisclient"  ><i class="ace-icon fa fa-check green bigger-100"></i> Réponse Client</a></li>', 0, '[-1-2-3-5-]', 2, 0, 'Attente réponse client', 'info', '<span class="label label-sm label-info">Attente réponse client</span>', NULL, NULL, NULL, NULL),
(1011, 655, '4b11c0bfb3f970a541100f7fc334927e', 'Voir détails', 'this_url', 'viewdevis', NULL, '<li><a href="#" class="this_url" data="%id%" rel="viewdevis"  ><i class="ace-icon fa fa-eye bigger-100"></i> Voir détails</a></li>', 0, '[-1-2-3-5-]', 3, 1, 'Demande modification', 'info', '<span class="label label-sm label-info">Demande modification</span>', NULL, NULL, NULL, NULL),
(1012, 655, '61a0655c2c13039b5b8262b82ae6cb51', 'Voir détails', 'this_url', 'viewdevis', NULL, '<li><a href="#" class="this_url" data="%id%" rel="viewdevis"  ><i class="ace-icon fa fa-eye bigger-100"></i> Voir détails</a></li>', 0, '[-1-2-3-5-]', 2, 0, 'Attente réponse client', 'info', '<span class="label label-sm label-info">Attente réponse client</span>', NULL, NULL, NULL, NULL),
(1013, 655, 'ed30dfbb21d8d24a0da432252358bdf8', 'Voir détails', 'this_url', 'viewdevis', NULL, '<li><a href="#" class="this_url" data="%id%" rel="viewdevis"  ><i class="ace-icon fa fa-eye bigger-100"></i> Voir détails</a></li>', 0, '[-1-2-3-5-]', 4, 0, 'Devis Validé par le client', 'success', '<span class="label label-sm label-success">Devis Validé par le client</span>', NULL, NULL, NULL, NULL),
(1014, 655, '7bd2e025ffb3893dea4776e152301716', 'Débloquer devis', 'this_exec', 'debloqdevis', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="debloqdevis"  ><i class="ace-icon fa fa-unlock blue bigger-100"></i> Débloquer devis</a></li>', 0, '[-1-3-]', 3, 1, 'Demande modification', 'info', '<span class="label label-sm label-info">Demande modification</span>', NULL, NULL, NULL, NULL),
(1015, 655, '6d9ec7d9ebebcdc921376e7bf0c9fdaf', 'Valider devis', 'this_exec', 'validdevis', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validdevis"  ><i class="ace-icon fa fa-check green bigger-100"></i> Valider devis</a></li>', 0, '[-1-3-]', 0, 1, 'Attente validation', 'warning', '<span class="label label-sm label-warning">Attente validation</span>', NULL, NULL, NULL, NULL),
(1016, 655, 'b1bcc4a4ab154f110abcf54c0c659fb3', 'Voir détails', 'this_url', 'viewdevis', NULL, '<li><a href="#" class="this_url" data="%id%" rel="viewdevis"  ><i class="ace-icon fa fa-eye bigger-100"></i> Voir détails</a></li>', 0, '[-1-2-3-5-]', 5, 0, 'Devis rejeté par le client', 'danger', '<span class="label label-sm label-danger">Devis rejeté par le client</span>', NULL, NULL, NULL, NULL),
(1017, 655, '91a90a46e3430c491ab8db654b6e87c4', 'Voir détails', 'this_url', 'viewdevis', NULL, '<li><a href="#" class="this_url" data="%id%" rel="viewdevis"  ><i class="ace-icon fa fa-eye bigger-100"></i> Voir détails</a></li>', 0, '[-1-2-3-5-]', 6, 0, 'Devis Expiré', 'inverse', '<span class="label label-sm label-inverse">Devis Expiré</span>', NULL, NULL, NULL, NULL),
(1018, 656, 'd9eeb330625c1b87e0df00986a47be01', 'Ajouter Devis', NULL, 'adddevis', NULL, '', 0, '[-1-2-]', 0, 0, 'Brouillon', 'success', '<span class="label label-sm label-success">Brouillon</span>', NULL, NULL, NULL, NULL);
INSERT INTO `task_action` (`id`, `appid`, `idf`, `descrip`, `mode_exec`, `app`, `class`, `code`, `type`, `service`, `etat_line`, `notif`, `etat_desc`, `message_class`, `message_etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(1019, 657, 'da93cdb05137e15aed9c4c18bddd746a', 'Ajouter détail devis', NULL, 'add_detaildevis', NULL, '', 0, '[-1-2-]', 0, 0, 'Attente validation', 'success', '<span class="label label-sm label-success">Attente validation</span>', NULL, NULL, NULL, NULL),
(1020, 658, 'f9f3c299f9bd0fec014f6bd3f0e06adb', 'Modifier Devis', NULL, 'editdevis', NULL, '', 0, '[-1-2-]', 0, 0, 'Attente validation', 'warning', '<span class="label label-sm label-warning">Attente validation</span>', NULL, NULL, NULL, NULL),
(1021, 659, 'e14cce6f1faf7784adb327581c516b90', 'Supprimer Devis', NULL, 'deletedevis', NULL, '', 0, '[-1-3-]', 0, 0, 'Attente validation', 'warning', '<span class="label label-sm label-warning">Attente validation</span>', NULL, NULL, NULL, NULL),
(1022, 660, '38f10871792c133ebcc6040e9a11cde8', 'Modifier détail Devis', NULL, 'edit_detaildevis', NULL, '', 0, '[-1-2-3-]', 0, 0, 'Attente validation', 'warning', '<span class="label label-sm label-warning">Attente validation</span>', NULL, NULL, NULL, NULL),
(1023, 661, '8def42e75fd4aee61c378d9fb303850d', 'Afficher détail devis', NULL, 'viewdevis', NULL, '', 0, '[-1-2-3-4-18-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1024, 662, '7666e87783b0f5a7eec1eea7593f7dfe', 'Valider Devis', NULL, 'validdevis', NULL, '', 0, '[-1-2-3-5-4-]', 0, 0, 'Attente validation', 'success', '<span class="label label-sm label-success">Attente validation</span>', NULL, NULL, NULL, NULL),
(1025, 663, '7f1c48324d8c369da9aa6ab8af35acd8', 'Validation Client Devis', NULL, 'validdevisclient', NULL, '', 0, '[-1-2-3-5-]', 0, 0, 'Attente validation client', 'info', '<span class="label label-sm label-info">Attente validation client</span>', NULL, NULL, NULL, NULL),
(1026, 664, '6adf896091dde0df89f777f31606953c', 'Débloquer devis', NULL, 'debloqdevis', NULL, '', 0, '[-1-3-]', 0, 0, 'Débloquer Devis', 'inverse', '<span class="label label-sm label-inverse">Débloquer Devis</span>', NULL, NULL, NULL, NULL),
(1027, 665, '15cbb79dd4a74266158e6b29a83e683c', 'Archiver Devis', NULL, 'archivdevis', NULL, '', 1, '[-1-3-]', 0, 0, 'Devis archivé', 'inverse', '<span class="label label-sm label-inverse">Devis archivé</span>', NULL, NULL, NULL, NULL),
(1115, 709, '56de23d30d6c54297c8d9772cd3c7f57', 'Utilisateurs', NULL, 'user', NULL, '', 1, '-1-2-3-', 1, 0, 'Actif', 'success', '<span class="label label-sm label-success">Actif</span>', NULL, NULL, NULL, NULL),
(1116, 709, 'e656756fb7b39a4e6ddcabca75ff2970', 'Editer Utilisateur', 'this_url', 'user', NULL, '<li><a href="#" class="this_url" redi="user" data="%id%" rel="edituser" >\n     <i class="ace-icon fa fa-pencil bigger-100"></i> Editer compte\n   </a></li>', 0, '-1-2-3-', 1, 0, 'Actif', 'success', '<span class="label label-sm label-success">Actif</span>', NULL, NULL, NULL, NULL),
(1117, 709, 'c073a277957ca1b9f318ac3902555708', 'Permissions', 'this_url', 'user', NULL, '<li><a href="#" class="this_url" redi="user" data="%id%" rel="rules"  >\n     <i class="ace-icon fa fa-key bigger-100"></i> Permission compte\n    </a></li>', 0, '-1-2-3-', 1, 0, 'Actif', 'success', '<span class="label label-sm label-success">Actif</span>', NULL, NULL, NULL, NULL),
(1118, 709, 'c51499ddf7007787c4434661c658bbd1', 'Désactiver compte', 'this_exec', 'user', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="activeuser" ><i class="ace-icon fa fa-lock bigger-100"></i> Désactiver utilisateur</a></li>', 0, '-1-3-', 1, 0, 'Actif', 'success', '<span class="label label-sm label-success">Actif</span>', NULL, NULL, NULL, NULL),
(1119, 709, '10096b6f54456bcfc85081523ee64cf6', 'Supprimer utilisateur', 'this_exec', 'user', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="delete_user" ><i class="ace-icon fa fa-trash red bigger-100"></i> Supprimer utilisateur</a></li>', 0, '-1-2-3-', 1, 0, 'Actif', 'success', '<span class="label label-sm label-success">Actif</span>', NULL, NULL, NULL, NULL),
(1120, 709, 'a0999cbed820aff775adf27276ee54a4', 'Editer Utilisateur', 'this_url', 'user', NULL, '<li><a href="#" class="this_url" data="%id%" rel="edituser" ><i class="ace-icon fa fa-users bigger-100"></i> Editer compte</a></li>', 0, '-1-2-3-', 0, 0, 'Attente Validation', 'danger', '<span class="label label-sm label-danger">Attente Validation</span>', NULL, NULL, NULL, NULL),
(1121, 709, '9aa6877656339ddff2478b20449a924b', 'Activer compte', 'this_exec', 'user', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="activeuser" ><i class="ace-icon fa fa-unlock bigger-100"></i> Activer utilisateur</a></li>', 0, '-1-2-3-', 0, 1, 'Attente Validation', 'danger', '<span class="label label-sm label-danger">Attente Validation</span>', NULL, NULL, NULL, NULL),
(1122, 709, 'f4c79bb797b92dfa826b51a44e3171af', 'Utilisateurs', NULL, 'user', NULL, '', 0, '-1-2-3-', 0, 1, 'Attente Validation', 'danger', '<span class="label label-sm label-danger">Attente Validation</span>', NULL, NULL, NULL, NULL),
(1123, 709, 'd7f7afd70a297e5c239f6cf271138390', 'Utilisateur Archivé', NULL, 'user', NULL, 'dddd', 0, '-1-2-3-', 2, 0, 'Archivé', 'inverse', '<span class="label label-sm label-inverse">Archivé</span>', NULL, NULL, NULL, NULL),
(1124, 709, '17c98287fb82388423e04d24404cf662', 'Permissions', 'this_url', 'rules', NULL, '<li><a href="#" class="this_url" data="%id%" rel="rules"  ><i class="ace-icon fa fa-lock bigger-100"></i> Permissions</a></li>', 0, '[-1-]', 0, 1, 'Attente activation', 'danger', '<span class="label label-sm label-danger">Attente activation</span>', NULL, NULL, NULL, NULL),
(1125, 709, '2c1c5556f30585c5b7c93fbbeaa98595', 'Historique session', 'this_url', 'history', NULL, '<li><a href="#" class="this_url" data="%id%" rel="history"  ><i class="ace-icon fa fa-clock-o blue bigger-100"></i> Historique session</a></li>', 0, '[-1-3-]', 1, 0, 'Compte Active', 'success', '<span class="label label-sm label-success">Compte Active</span>', NULL, NULL, NULL, NULL),
(1126, 709, '7fd4ca84d162b70d3a4f0bad73e0e4b3', 'Liste Activitées', 'this_url', 'activities', NULL, '<li><a href="#" class="this_url" data="%id%" rel="activities"  ><i class="ace-icon fa fa-exchange blue bigger-100"></i> Liste Activitées</a></li>', 0, '[-1-3-]', 1, 0, 'Compte Actif', 'success', '<span class="label label-sm label-success">Compte Actif</span>', NULL, NULL, NULL, NULL),
(1127, 710, 'df91a8e6f8ee2cde64495fc0cc7d6c6f', 'Ajouter Utilisateurs', NULL, 'adduser', NULL, '', 1, '-1-2-', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(1128, 711, '2bb46b52eab9eecbdbba35605da07234', 'Editer Utilisateurs', NULL, 'edituser', NULL, '', 1, '-1-2-', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(1129, 712, '3f59a1326df27378304e142ab3bec090', 'Permission', NULL, 'rules', NULL, '', 1, '-1-2-', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(1130, 713, 'b919571c88d036f8889742a81a4f41fd', 'Supprimer utilisateur', NULL, 'delete_user', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(1131, 714, '38f89764a26e39ce029cd3132c12b2a5', 'Compte utilisateur', NULL, 'compte', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(1132, 715, 'f988a608f35a0bc551cb038b1706d207', 'Activer utilisateur', NULL, 'activeuser', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(1133, 716, 'b7b3a09fdd73a5b0a3e5ed8a2828f548', 'Désactiver l''utilisateur', NULL, 'archiv_user', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(1134, 717, '0d374b7e2fe21a2e2641c092a3c7f2e9', 'Changer le mot de passe', NULL, 'changepass', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(1135, 718, '6f642ee30722158f0318653b9113b887', 'History', NULL, 'history', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(1136, 719, 'cc907fac13631903d129c137d671d718', 'Activities', NULL, 'activities', NULL, '', 1, '[-1-]', 1, 0, NULL, NULL, '', NULL, NULL, NULL, NULL),
(1137, 720, '1eb847d87adcad78d5e951e6110061e5', 'Gestion Proforma', NULL, 'proforma', NULL, '', 0, '[-1-2-3-5-4-]', 0, 0, 'Attente validation', 'warning', '<span class="label label-sm label-warning">Attente validation</span>', NULL, NULL, NULL, NULL),
(1138, 720, '44ef6849d8d5d17d8e0535187e923d32', 'Editer proforma', 'this_url', 'editproforma', NULL, '<li><a href="#" class="this_url" data="%id%" rel="editproforma"  ><i class="ace-icon fa fa-pen blue bigger-100"></i> Editer proforma</a></li>', 0, '[-1-2-3-5-]', 0, 1, 'Attente validation', 'warning', '<span class="label label-sm label-warning">Attente validation</span>', NULL, NULL, NULL, NULL),
(1139, 720, 'b7ce06be726011362a271678547a803c', 'Valider Proforma', 'this_exec', 'validproforma', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validproforma"  ><i class="ace-icon fa fa-check bigger-100"></i> Valider Proforma</a></li>', 0, '[-1-3-5-]', 0, 1, 'Attente validation', 'warning', '<span class="label label-sm label-warning">Attente validation</span>', NULL, NULL, NULL, NULL),
(1140, 720, 'abd8c50f1d2ef4beeeddb68a72973587', 'Détail Proforma', 'this_url', 'viewproforma', NULL, '<li><a href="#" class="this_url" data="%id%" rel="viewproforma"  ><i class="ace-icon fa fa-eye blue bigger-100"></i> Détail Proforma</a></li>', 0, '[-1-2-3-5-]', 0, 0, 'Attente validation', 'warning', '<span class="label label-sm label-warning">Attente validation</span>', NULL, NULL, NULL, NULL),
(1141, 720, '35a88b5c359908b063ac98cafc622987', 'Détail Proforma', 'this_url', 'viewproforma', NULL, '<li><a href="#" class="this_url" data="%id%" rel="viewproforma"  ><i class="ace-icon fa fa-eye blue bigger-100"></i> Détail Proforma</a></li>', 0, '[-1-2-3-5-]', 2, 0, 'Proforma envoyée au client', 'success', '<span class="label label-sm label-success">Proforma envoyée au client</span>', NULL, NULL, NULL, NULL),
(1142, 720, 'e20d83df90355eca2a65f56a2556601f', 'Détail Proforma', 'this_url', 'viewproforma', NULL, '<li><a href="#" class="this_url" data="%id%" rel="viewproforma"  ><i class="ace-icon fa fa-eye blue bigger-100"></i> Détail Proforma</a></li>', 0, '[-1-2-3-5-]', 1, 0, 'Attente Expédition', 'info', '<span class="label label-sm label-info">Attente Expédition</span>', NULL, NULL, NULL, NULL),
(1143, 720, '252ed64d8956e20fb88c1be41688f74a', 'Envoi proforma au client', 'this_exec', 'validproforma', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validproforma"  ><i class="ace-icon fa fa-envelope bigger-100"></i> Envoi proforma au client</a></li>', 0, '[-1-2-]', 1, 1, 'Attente Expédition', 'info', '<span class="label label-sm label-info">Attente Expédition</span>', NULL, NULL, NULL, NULL),
(1144, 721, 'd5a6338765b9eab63104b59f01c06114', 'Ajouter pro-forma', NULL, 'addproforma', NULL, '', 0, '[-1-2-3-5-]', 0, 0, 'Brouillon', 'warning', '<span class="label label-sm label-warning">Brouillon</span>', NULL, NULL, NULL, NULL),
(1145, 722, '95831bde77bc886d6ab4dd5e734de743', 'Editer proforma', NULL, 'editproforma', NULL, '', 0, '[-1-2-3-5-]', 0, 0, 'Brouillon', 'warning', '<span class="label label-sm label-warning">Brouillon</span>', NULL, NULL, NULL, NULL),
(1146, 723, 'cbb4e1efa1c05b42d25a3a6bcab038a2', 'Ajouter détail proforma', NULL, 'adddetailproforma', NULL, '', 0, '[-1-2-3-5-]', 0, 0, 'Attente validation', 'warning', '<span class="label label-sm label-warning">Attente validation</span>', NULL, NULL, NULL, NULL),
(1147, 724, 'e9f745054778257a255452c6609461a0', 'valider Proforma', NULL, 'validproforma', NULL, '', 0, '[-1-2-3-5-]', 0, 0, 'Attente validation', 'warning', '<span class="label label-sm label-warning">Attente validation</span>', NULL, NULL, NULL, NULL),
(1148, 725, 'defef148c404c7e6ac79e4783e0a7ab7', 'Détail Pro-forma', NULL, 'viewproforma', NULL, '', 0, '[-1-2-3-5-]', 0, 0, 'Attente validation', 'warning', 'Attente validation', NULL, NULL, NULL, NULL),
(1149, 726, '53008d64edf241c937a06f03eff139aa', 'Editer détail proforma', NULL, 'edit_detailproforma', NULL, '', 0, '[-1-2-3-5-]', 0, 0, 'Attente validation', 'warning', '<span class="label label-sm label-warning">Attente validation</span>', NULL, NULL, NULL, NULL),
(1150, 727, '30e9d3d1ad17eb7f1fc0d5cbb9b58482', 'Supprimer proforma', NULL, 'deleteproforma', NULL, '', 1, '[-1-2-3-5-]', 0, 0, 'Attente validation', 'warning', '<span class="label label-sm label-warning">Attente validation</span>', NULL, NULL, NULL, NULL),
(1151, 728, '192715027870a4a612fd44d562e2752f', 'Gestion des produits', NULL, 'produits', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1152, 728, 'cb96e99d5f8e381637d1ac83f1a21f1c', 'Modifier  produit', 'this_url', 'editproduit', 'pencil-square-o', '<li><a href="#" class="this_url" data="%id%" rel="editproduit"  ><i class="ace-icon fa fa-pencil-square-o bigger-100"></i> Modifier  produit</a></li>', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1153, 728, '64e84ff11fea7f68bcf6a5b744c36081', 'Détail  produit', 'this_url', 'detailproduit', 'eye', '<li><a href="#" class="this_url" data="%id%" rel="detailproduit"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détail  produit</a></li>', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1154, 728, '0c94d85f4ee23656a01155ad1af5001c', 'Valider  produit', 'this_exec', 'validproduit', 'check-square-o', '<li><a href="#" class="this_exec" data="%id%" rel="validproduit"  ><i class="ace-icon fa fa-check-square-o bigger-100"></i> Valider  produit</a></li>', 0, '[-1-]', 0, 1, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1155, 728, '6b087b20929483bb07f8862b39e41f07', 'Désactiver produit', 'this_exec', 'validproduit', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validproduit"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Désactiver produit</a></li>', 0, '[-1-]', 1, 0, 'Produit  Validé', 'success', '<span class="label label-sm label-success">Produit  Validé</span>', NULL, NULL, NULL, NULL),
(1156, 728, '6f1d7cc5bd1c941beffa0ae3e1efd559', 'Achat  produit', 'this_url', 'buyproducts', 'euro', '<li><a href="#" class="this_url" data="%id%" rel="buyproducts"  ><i class="ace-icon fa fa-euro bigger-100"></i> Achat  produit</a></li>', 0, '[-1-]', 1, 0, 'Produit  Validé', 'success', '<span class="label label-sm label-success">Produit  Validé</span>', NULL, NULL, NULL, NULL),
(1157, 728, '41b9c4b7028269d4540915d6ec14ee79', 'Détails Produit', 'this_url', 'detailproduit', NULL, '<li><a href="#" class="this_url" data="%id%" rel="detailproduit"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails Produit</a></li>', 0, '[-1-]', 1, 0, 'Produit  Validé', 'success', '<span class="label label-sm label-success">Produit  Validé</span>', NULL, NULL, NULL, NULL),
(1158, 729, '93e893c307a6fa63e392f78751ec70ce', 'Ajouter produit', NULL, 'addproduit', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1159, 730, 'bcf3beada4a98e8145af2d4fbb744f01', 'Modifier produit', NULL, 'editproduit', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1160, 731, '796427ec57f7c13d6b737055ae686b34', 'Detail produit', NULL, 'detailproduit', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1161, 732, '1fb8cd1a179be07586fa7db05013dd37', 'Valider produit', NULL, 'validproduit', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1162, 733, '7779e98d2111faedf458f7aeb548294e', 'Supprimer produit', NULL, 'deleteproduit', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1163, 734, '8da585a04e918c256bd26f0c03f1390d', 'Achat produit', NULL, 'buyproducts', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1164, 734, 'f8c9a7413089566d1db20dcc5ca17e03', 'Modifier achat', 'this_url', 'editbuyproduct', NULL, '<li><a href="#" class="this_url" data="%id%" rel="editbuyproduct"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Modifier achat</a></li>', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1165, 734, '682b4328ee832101a44dac86b22d5757', 'Détail achat', 'this_url', 'detailbuyproduct', NULL, '<li><a href="#" class="this_url" data="%id%" rel="detailbuyproduct"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Détail achat</a></li>', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1166, 734, 'd1ebf1c5482ddf06721b11ec64afb744', 'Valider achat', 'this_exec', 'validbuyproduct', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validbuyproduct"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Valider achat</a></li>', 0, '[-1-]', 0, 1, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1167, 734, '368a1e91fc63e263eb01d85a34ecd89b', 'Désactiver achat', 'this_exec', 'validbuyproduct', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validbuyproduct"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Désactiver achat</a></li>', 0, '[-1-]', 1, 0, 'Achat validé', 'success', '<span class="label label-sm label-success">Achat validé</span>', NULL, NULL, NULL, NULL),
(1168, 735, '659be5cd86a12eba7e59c52d60198a36', 'Ajoute achat', NULL, 'addbuyproduct', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1169, 736, '8415336a17e8ca26f3eca5741863f3b2', 'Modifier achat', NULL, 'editbuproduct', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1170, 737, '2c3b4875b72f7da6a87b5c0d7e85f51d', 'Supprimer achat', NULL, 'deletebuyproduct', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1171, 738, 'd4180eb7a4ff86c598f441ffd4543f36', 'Détail achat', NULL, 'detailbuyproduct', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1172, 739, '4a4c9b096bad58a96d5ea6f93d66e81c', 'Valider achat', NULL, 'validbuyproduct', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1200, 752, '179906c1666d7e9a7b4d1f52a1f84ec0', 'Commerciale', NULL, 'commerciale', NULL, '', 1, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(1201, 752, '3e2b9ccbb5837f42342934bd9ba3aa49', 'Modifier Commerciale', 'this_url', 'editcommerciale', NULL, '<li><a href="#" class="this_url" data="%id%" rel="editcommerciale"  ><i class="ace-icon fa fa-pencil-square-o bigger-100"></i> Modifier Commerciale</a></li>', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(1202, 752, '1f4be058271867b2a398678fb0e49750', 'Valider commerciale', 'this_exec', 'validcommerciale', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validcommerciale"  ><i class="ace-icon fa fa-check-square-o bigger-100"></i> Valider commerciale</a></li>', 0, '[-1-]', 0, 1, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(1203, 752, '3980ecadef51319f03ae82b686e97dc4', 'Détails commerciale', 'this_url', 'detailscommerciale', NULL, '<li><a href="#" class="this_url" data="%id%" rel="detailscommerciale"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails commerciale</a></li>', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(1204, 752, 'b0abfcf29eaacb4bad1a88a663331182', 'Détails Commerciale', 'this_url', 'detailscommerciale', NULL, '<li><a href="#" class="this_url" data="%id%" rel="detailscommerciale"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails Commerciale</a></li>', 0, '[-1-2-]', 1, 0, 'Commerciale Validé', 'success', '<span class="label label-sm label-success">Commerciale Validé</span>', NULL, NULL, NULL, NULL),
(1205, 752, '923af4f85ccb0b69f7920e557ed03768', 'Désactiver commerciale', 'this_exec', 'validcommerciale', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validcommerciale"  ><i class="ace-icon fa fa-unlock bigger-100"></i> Désactiver commerciale</a></li>', 0, '[-1-]', 1, 0, 'Commerciale Validé', 'success', '<span class="label label-sm label-success">Commerciale Validé</span>', NULL, NULL, NULL, NULL),
(1206, 752, 'f2e2a34f24f845f58e3af9e1baf3d34c', 'Commissions', 'this_url', 'commissions', NULL, '<li><a href="#" class="this_url" data="%id%" rel="commissions"  ><i class="ace-icon fa fa-signal bigger-100"></i> Commissions</a></li>', 0, '[-1-5-]', 1, 0, 'Commerciale Validé', 'success', '<span class="label label-sm label-success">Commerciale Validé</span>', NULL, NULL, NULL, NULL),
(1207, 753, '03b0a2e252d4c53940ca1817d9083f0a', 'Ajouter commerciale', NULL, 'addcommerciale', NULL, '', 1, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(1208, 754, 'ab2d80f7396b53342b8455293be7c892', 'Modifier Commerciale', NULL, 'editcommerciale', NULL, '', 1, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(1209, 755, 'b9de9130d6ec79fa1981fd935590d9c7', 'Valider commerciale', NULL, 'validcommerciale', NULL, '', 1, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(1210, 756, '97f27dfbac6c8d8f785585ca54b1b8d4', 'Supprimer commerciale', NULL, 'deletecommerciale', NULL, '', 1, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(1211, 757, '250db5ee83c4c30061b983a26fb91ba9', 'Détails commerciale', NULL, 'detailscommerciale', NULL, '', 1, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(1212, 758, '738a72cd47c2630b73be1a92f8117525', 'Commissions', NULL, 'commissions', NULL, '', 1, '[-1-5-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(1213, 758, '98f53da207662a231a3ff2377af1f03b', 'Payer Commission   ', 'this_url', 'paycommission', NULL, '<li><a href="#" class="this_url" data="%id%" rel="paycommission"  ><i class="ace-icon fa fa-euro bigger-100"></i> Payer Commission   </a></li>', 0, '[-1-5-]', 1, 0, 'Attente Payement', 'success', '<span class="label label-sm label-success">Attente Payement</span>', NULL, NULL, NULL, NULL),
(1214, 758, 'a51a876db71f5da2d9ef1ac6e4929543', 'Payer Commission', 'this_url', 'paycommission', NULL, '<li><a href="#" class="this_url" data="%id%" rel="paycommission"  ><i class="ace-icon fa fa-euro bigger-100"></i> Payer Commission</a></li>', 0, '[-1-5-]', 2, 0, 'Payé Partiellement', 'info', '<span class="label label-sm label-info">Payé Partiellement</span>', NULL, NULL, NULL, NULL),
(1215, 758, 'd4327189d68e6aae34e614fbd5ecc9b8', 'Modifier commission', 'this_url', 'editcommissions', NULL, '<li><a href="#" class="this_url" data="%id%" rel="editcommissions"  ><i class="ace-icon fa fa-pencil-square-o bigger-100"></i> Modifier commission</a></li>', 0, '[-1-5-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(1216, 758, '31cb75b08edc1219b940b8d9c3f74dec', 'Valider Commission', 'this_exec', 'validcommission', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validcommission"  ><i class="ace-icon fa fa-check-square-o bigger-100"></i> Valider Commission</a></li>', 0, '[-1-5-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(1217, 758, '7b78301b1dfe281483854f0e23102bb1', 'Détails commission', 'this_url', 'detailscommission', NULL, '<li><a href="#" class="this_url" data="%id%" rel="detailscommission"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails commission</a></li>', 0, '[-1-5-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(1218, 758, '9d48b04e7eb927ba243a8a52eed24d66', 'Détails commission', 'this_url', 'detailscommission', NULL, '<li><a href="#" class="this_url" data="%id%" rel="detailscommission"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails commission</a></li>', 0, '[-1-5-]', 1, 0, 'Attente Payement', 'success', '<span class="label label-sm label-success">Attente Payement</span>', NULL, NULL, NULL, NULL),
(1219, 758, 'f9ecac816e2e67543667014a6e2bd01b', 'Détails commission', 'this_url', 'detailscommission', NULL, '<li><a href="#" class="this_url" data="%id%" rel="detailscommission"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails commission</a></li>', 0, '[-1-5-]', 2, 0, 'Payé Partiellement', 'info', '<span class="label label-sm label-info">Payé Partiellement</span>', NULL, NULL, NULL, NULL),
(1220, 758, 'a824d5f254bea8798c2c719fba1d4f3b', 'Détails commission', 'this_url', 'detailscommission', NULL, '<li><a href="#" class="this_url" data="%id%" rel="detailscommission"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails commission</a></li>', 0, '[-1-5-]', 3, 0, 'Commission Payée', 'danger', '<span class="label label-sm label-danger">Commission Payée</span>', NULL, NULL, NULL, NULL),
(1221, 759, '5adc1562f2a8f48f30492133e6d82d48', 'Payer Commission', NULL, 'paycommission', NULL, '', 1, '[-1-5-]', 0, 0, 'Attente Payement', 'info', '<span class="label label-sm label-info">Attente Payement</span>', NULL, NULL, NULL, NULL),
(1222, 760, 'd374ca19122a0b3f66f67bdbf74efc60', 'Ajouter commission', NULL, 'addcommissions', NULL, '', 1, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(1223, 761, '25bbc454f35643c1af3371fd02cc9195', 'Modifier commission', NULL, 'editcommissions', NULL, '', 1, '[-1-5-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(1224, 762, 'c35c2ca29c5083910a20996ccd465a48', 'Valider Commission', NULL, 'validcommission', NULL, '', 1, '[-1-5-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(1225, 763, '418e3a3591fc54086c56b385077bfb71', 'Détails commission', NULL, 'detailscommission', NULL, '', 1, '[-1-5-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(1237, 768, '899d40c8f22d4f7a6f048366f1829787', 'Gestion des contrats', NULL, 'contrats', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1238, 768, '4aea0d5a7bdb0e2513897507947fc3de', 'Modifier  contrat', 'this_url', 'editcontrat', NULL, '<li><a href="#" class="this_url" data="%id%" rel="editcontrat"  ><i class="ace-icon fa fa-pencil-square bigger-100"></i> Modifier  contrat</a></li>', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1239, 768, '4ccf7c3c72dfa25157ab01762069929e', 'Détail  contrat', 'this_url', 'detailcontrat', NULL, '<li><a href="#" class="this_url" data="%id%" rel="detailcontrat"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détail  contrat</a></li>', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1240, 768, '18c5260f189a488c59134c1d53270dae', 'Valider  contrat', 'this_exec', 'validcontrat', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validcontrat"  ><i class="ace-icon fa fa-check-square-o bigger-100"></i> Valider  contrat</a></li>', 0, '[-1-]', 0, 1, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1241, 768, '6ca83d9c6c0b229446da30b60b74031a', 'Détails  Contrat', 'this_url', 'detailcontrat', NULL, '<li><a href="#" class="this_url" data="%id%" rel="detailcontrat"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails  Contrat</a></li>', 0, '[-1-]', 1, 0, 'Contrat Validé', 'success', '<span class="label label-sm label-success">Contrat Validé</span>', NULL, NULL, NULL, NULL),
(1242, 768, '52eef475bfa2afb7eb065329a93b0b4c', 'Renouveler  Contrat', 'this_url', 'renouvelercontrat', NULL, '<li><a href="#" class="this_url" data="%id%" rel="renouvelercontrat"  ><i class="ace-icon fa fa-exchange bigger-100"></i> Renouveler  Contrat</a></li>', 0, '[-1-]', 2, 1, 'Attente Renouvelement', 'warning', '<span class="label label-sm label-warning">Attente Renouvelement</span>', NULL, NULL, NULL, NULL),
(1243, 768, 'b23939959d533fa68091fca749b691aa', 'Détails Contrat ', 'this_url', 'detailcontrat', NULL, '<li><a href="#" class="this_url" data="%id%" rel="detailcontrat"  ><i class="ace-icon fa fa-file bigger-100"></i> Détails Contrat </a></li>', 0, '[-1-]', 3, 0, 'Contrat Expiré', 'info', '<span class="label label-sm label-info">Contrat Expiré</span>', NULL, NULL, NULL, NULL),
(1244, 768, 'b6cc6622e5874a5c0a04e2103d8a7dd0', ' Détails    Contrat', 'this_url', 'detailcontrat', NULL, '<li><a href="#" class="this_url" data="%id%" rel="detailcontrat"  ><i class="ace-icon fa fa-exchange bigger-100"></i>  Détails    Contrat</a></li>', 0, '[-1-]', 2, 0, 'Attente Renouvelement', 'warning', '<span class="label label-sm label-warning">Attente Renouvelement</span>', NULL, NULL, NULL, NULL),
(1245, 768, 'c58a3038be080d0c6cdf89e0fd0a8c71', 'Détails  Contrat', 'this_url', 'detailcontrat', NULL, '<li><a href="#" class="this_url" data="%id%" rel="detailcontrat"  ><i class="ace-icon fa fa-file bigger-100"></i> Détails  Contrat</a></li>', 0, '[-1-]', 4, 0, 'Contrat Expiré', 'inverse', '<span class="label label-sm label-inverse">Contrat Expiré</span>', NULL, NULL, NULL, NULL),
(1246, 768, '656d41ad5452611636a5d9f966729e39', 'Renouveler Contrat', 'this_url', 'renouvelercontrat', NULL, '<li><a href="#" class="this_url" data="%id%" rel="renouvelercontrat"  ><i class="ace-icon fa fa-exchange bigger-100"></i> Renouveler Contrat</a></li>', 0, '[-1-]', 3, 0, 'Contrat Expiré', 'info', '<span class="label label-sm label-info">Contrat Expiré</span>', NULL, NULL, NULL, NULL),
(1247, 768, '9a7cae6e28f8265acf392c94c2c38aec', 'Résilier Contrat', 'this_exec', 'resiliercontrat', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="resiliercontrat"  ><i class="ace-icon fa fa-ban bigger-100"></i> Résilier Contrat</a></li>', 0, '[-1-]', 1, 0, 'Contrat Validé', 'success', '<span class="label label-sm label-success">Contrat Validé</span>', NULL, NULL, NULL, NULL),
(1248, 768, '2e8270e01e3668cfd156816d9107d1f7', 'Détails   Contrat', 'this_url', 'detailcontrat', NULL, '<li><a href="#" class="this_url" data="%id%" rel="detailcontrat"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails   Contrat</a></li>', 0, '[-1-]', 5, 0, 'Contrat Résilié', 'inverse', '<span class="label label-sm label-inverse">Contrat Résilié</span>', NULL, NULL, NULL, NULL),
(1250, 769, '87f4c3ed4713c3bc9e3fef60a6649055', 'Ajouter contrat', NULL, 'addcontrat', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1251, 770, '9e49a431d9637544cefa2869fd7278b9', 'Modifier contrat', NULL, 'editcontrat', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1252, 771, '1e9395a182a44787e493bc038cd80bbf', 'Supprimer contrat', NULL, 'deletecontrat', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1253, 772, '460d92834715b149c4db28e1643bd932', 'Valider contrat', NULL, 'validcontrat', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1254, 773, 'bbcf2879c2f8f60cfa55fa97c6e79268', 'Détail contrat', NULL, 'detailcontrat', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1255, 774, 'fe058ccb890b25a54866be7f24a40363', 'Ajouter échéance ', NULL, 'addecheance_contrat', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1256, 775, '36a248f56a6a80977e5c90a5c59f39d3', 'Modifier échéance contrat', NULL, 'editecheance_contrat', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1257, 776, 'f0567980556249721f24f2fc88ebfed5', 'Renouveler Contrat', NULL, 'renouvelercontrat', NULL, '', 0, '[-1-]', 0, 0, 'Attente Renouvelement', 'danger', '<span class="label label-sm label-danger">Attente Renouvelement</span>', NULL, NULL, NULL, NULL),
(1258, 777, 'd3fc6f1bcca0a0250c5f6de29fd72b80', 'Résilier Contrat', NULL, 'resiliercontrat', NULL, '', 1, '[-1-]', 0, 0, 'Contrat Validé', 'success', '<span class="label label-sm label-success">Contrat Validé</span>', NULL, NULL, NULL, NULL),
(1259, 778, '428bf9d4c56394d24e15f5458b077990', 'Echéances', NULL, 'echeances', '778', '', 1, '[-1-]', 0, 0, 'Attente Validation', 'success', '<span class="label label-sm label-success">Attente Validation</span>', NULL, NULL, NULL, NULL),
(1260, 768, 'e5c2a867baf3429d758742a021d4795c', 'Echéances', 'this_url', 'echeances', 'exchange', '<li><a href="#" class="this_url" data="%id%" rel="echeances"  ><i class="ace-icon fa fa-exchange bigger-100"></i> Echéances</a></li>', 0, '[-1-]', 1, 0, 'Contrat Validé', 'success', '<span class="label label-sm label-success">Contrat Validé</span>', NULL, NULL, NULL, NULL),
(1261, 768, 'dfc236bfafa081e74f23a1c5f631fe78', 'Echéances', 'this_url', 'echeances', 'exchange', '<li><a href="#" class="this_url" data="%id%" rel="echeances"  ><i class="ace-icon fa fa-exchange bigger-100"></i> Echéances</a></li>', 0, '[-1-]', 2, 0, 'Attente Renouvelement', 'warning', '<span class="label label-sm label-warning">Attente Renouvelement</span>', NULL, NULL, NULL, NULL),
(1262, 779, 'b37af8eb31b7082afa5ad48f0d618f3b', 'Générer Facture', NULL, 'generatefacture', '779', '', 1, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(1263, 778, 'b0cff04f8af9234adbc81e7f679c7176', 'Générer Facture', 'this_exec', 'generatefacture', 'book', '<li><a href="#" class="this_exec" data="%id%" rel="generatefacture"  ><i class="ace-icon fa fa-book bigger-100"></i> Générer Facture</a></li>', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(1264, 780, 'd860c94cc554cc0ff03af97a9248d2de', 'Afficher Facture', NULL, 'afficherfacture', '780', '', 1, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(1265, 778, '1596760bb0380a6a77c784ec92eb6fa7', 'Afficher Facture', 'this_url', 'afficherfacture', 'eye', '<li><a href="#" class="this_url" data="%id%" rel="afficherfacture"  ><i class="ace-icon fa fa-eye bigger-100"></i> Afficher Facture</a></li>', 0, '[-1-]', 1, 0, 'Facture Générée', 'success', '<span class="label label-sm label-success">Facture Générée</span>', NULL, NULL, NULL, NULL),
(1297, 796, '4c924acb9adc87d8389e8f9842a965c5', 'Gestion des factures', NULL, 'factures', NULL, '', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(1298, 796, '98a697ec628778765b25e02ba2929d38', 'Liste complément', 'this_url', 'complements', NULL, '<li><a href="#" class="this_url" data="%id%" rel="complements"  ><i class="ace-icon fa fa-circle bigger-100"></i> Liste complément</a></li>', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1299, 796, 'f8b20f7fec99b45b967a431d64b7f061', 'Liste encaissements', 'this_url', 'encaissements', NULL, '<li><a href="#" class="this_url" data="%id%" rel="encaissements"  ><i class="ace-icon fa fa-euro bigger-100"></i> Liste encaissements</a></li>', 0, '[-1-]', 2, 0, 'Attente Payement', 'info', '<span class="label label-sm label-info">Attente Payement</span>', NULL, NULL, NULL, NULL),
(1300, 796, '9a51fb5298e39a28af3ad6272fc51177', 'Valider facture', 'this_exec', 'validfacture', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validfacture"  ><i class="ace-icon fa fa-check bigger-100"></i> Valider facture</a></li>', 0, '[-1-]', 0, 1, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(1301, 796, '851f1d4c13f6025f69f5b9315321d350', 'Désactiver facture', 'this_exec', 'rejectfacture', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="rejectfacture"  ><i class="ace-icon fa fa-unlock bigger-100"></i> Désactiver facture</a></li>', 0, '[-1-]', 1, 0, 'Attente Envoi Client', 'success', '<span class="label label-sm label-success">Attente Envoi Client</span>', NULL, NULL, NULL, NULL),
(1302, 796, '5c79105956d28b5cac52f85784039919', 'Détail facture', 'this_url', 'detailsfacture', NULL, '<li><a href="#" class="this_url" data="%id%" rel="detailsfacture"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détail facture</a></li>', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(1303, 796, '7892721423af84a0b54e90250cf27ee3', 'Détails Facture', 'this_url', 'detailsfacture', NULL, '<li><a href="#" class="this_url" data="%id%" rel="detailsfacture"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails Facture</a></li>', 0, '[-1-]', 1, 0, 'Attente Envoi Client', 'success', '<span class="label label-sm label-success">Attente Envoi Client</span>', NULL, NULL, NULL, NULL),
(1304, 796, '4b69240b3dd04f7a29457008b31d1248', 'Envoyer au client  ', 'this_exec', 'sendfacture', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="sendfacture"  ><i class="ace-icon fa fa-fighter-jet  bigger-100"></i> Envoyer au client  </a></li>', 0, '[-1-]', 1, 0, 'Attente Envoi Client', 'success', '<span class="label label-sm label-success">Attente Envoi Client</span>', NULL, NULL, NULL, NULL),
(1305, 796, '80a4b2643b95c2836e968411811d3c21', 'Détails facture', 'this_url', 'detailsfacture', NULL, '<li><a href="#" class="this_url" data="%id%" rel="detailsfacture"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails facture</a></li>', 0, '[-1-]', 2, 0, 'Attente Payement', 'info', '<span class="label label-sm label-info">Attente Payement</span>', NULL, NULL, NULL, NULL),
(1306, 796, '2f679be3c0d7b88529209f86745f9028', 'Détails facture', 'this_url', 'detailsfacture', NULL, '<li><a href="#" class="this_url" data="%id%" rel="detailsfacture"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails facture</a></li>', 0, '[-1-]', 3, 0, 'Payé Partiellement', 'inverse', '<span class="label label-sm label-inverse">Payé Partiellement</span>', NULL, NULL, NULL, NULL),
(1307, 796, '429558e9a1e899c11051ea5c9a4f7942', 'Détails facture', 'this_url', 'detailsfacture', NULL, '<li><a href="#" class="this_url" data="%id%" rel="detailsfacture"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails facture</a></li>', 0, '[-1-]', 4, 0, 'Facture Payée', 'danger', '<span class="label label-sm label-danger">Facture Payée</span>', NULL, NULL, NULL, NULL),
(1308, 796, '3acd11d8d74fb7e1ba8d5721e96f91bd', 'Liste encaissements', 'this_url', 'encaissements', NULL, '<li><a href="#" class="this_url" data="%id%" rel="encaissements"  ><i class="ace-icon fa fa-euro bigger-100"></i> Liste encaissements</a></li>', 0, '[-1-]', 3, 0, 'Payé partiellement', 'inverse', '<span class="label label-sm label-inverse">Payé partiellement</span>', NULL, NULL, NULL, NULL),
(1309, 797, '55c3c5d2d93143b315513b7401043c8b', 'complements', NULL, 'complements', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1310, 797, 'dfc4772cc03cf0b92a47f54fc6a2326e', 'Modifier complément', 'this_url', 'editcomplement', NULL, '<li><a href="#" class="this_url" data="%id%" rel="editcomplement"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Modifier complément</a></li>', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1311, 798, '03a18bdd5201e433a3c523a2b34d059a', 'Ajouter complément', NULL, 'addcomplement', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1312, 799, '88d9bc979cd1102eb8196e7f5e6042ca', 'Encaissement', NULL, 'encaissements', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1313, 799, 'c690cc68f5257c0c225b8b8e6126ea56', 'Modifier encaissement', 'this_url', 'editencaissement', NULL, '<li><a href="#" class="this_url" data="%id%" rel="editencaissement"  ><i class="ace-icon fa fa-pencil-square-o bigger-100"></i> Modifier encaissement</a></li>', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1314, 799, '1dc06f602e8630f273d44aa2751b2127', 'Détails encaissement', 'this_url', 'detailsencaissement', NULL, '<li><a href="#" class="this_url" data="%id%" rel="detailsencaissement"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails encaissement</a></li>', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1315, 799, '6567dc21b9b744ea7dbcbcbf83df4ac5', 'Valider encaissement', 'this_exec', 'validencaissement', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="validencaissement"  ><i class="ace-icon fa fa-check-square-o bigger-100"></i> Valider encaissement</a></li>', 0, '[-1-]', 0, 1, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1316, 799, 'bc335bbc5e0debff602b4e5325c89a99', 'Détails encaissement', 'this_url', 'detailsencaissement', NULL, '<li><a href="#" class="this_url" data="%id%" rel="detailsencaissement"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails encaissement</a></li>', 0, '[-1-]', 1, 0, 'Encaissement Validé', 'success', '<span class="label label-sm label-success">Encaissement Validé</span>', NULL, NULL, NULL, NULL),
(1317, 800, 'e4866b292dbc3c9c5d9cc37273a5b498', 'Ajouter encaissement', NULL, 'addencaissement', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1318, 801, '8665be10959f39df4f149962eb70041f', 'Modifier complément', NULL, 'editcomplement', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1319, 802, '585d411904bf7d9e83d21b2810ff1d6c', 'Modifier encaissement', NULL, 'editencaissement', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1320, 803, '8c8b058a4d030cdc8b49c9008abb2e92', 'Supprimer complément', NULL, 'deletecomplement', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', 'Attente de validation', NULL, NULL, NULL, NULL),
(1321, 804, '6bf7d5180940f03567a5d711e8563ba4', 'Supprimer encaissement', NULL, 'deleteencaissement', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1322, 805, '256abad0ec8e3bc8ed1c0653ff177255', 'Valider facture', NULL, 'validfacture', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1323, 806, 'b5dc5719c1f96df7334f371dcf51a5b6', 'Détail encaissement', NULL, 'detailsencaissement', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1324, 807, '16fbf6fdcbb72f863bcf7e4ef28d8e75', 'Détails facture', NULL, 'detailsfacture', NULL, '', 0, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1325, 808, '5efdeb41007109ca99f23f0756217827', 'Désactiver Facture', NULL, 'rejectfacture', NULL, '', 0, '[-1-]', 0, 0, 'Facture Validée', 'success', '<span class="label label-sm label-success">Facture Validée</span>', NULL, NULL, NULL, NULL),
(1326, 809, '1127d08fb22f425fd7913c3df1b9884f', 'Valider encaissement', NULL, 'validencaissement', NULL, '', 1, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1327, 810, '1bacb05aca2d17b42b1de767a8ad45de', 'Envoyer Facture', NULL, 'sendfacture', NULL, '', 1, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1408, 858, '79c262a9849332f387662790b8da4399', 'Gestion de Stock', NULL, 'stock', '858', '', 1, '[-1-]', 0, 0, 'Article en stock', 'success', '<span class="label label-sm label-success">Article en stock</span>', NULL, NULL, NULL, NULL),
(1409, 859, '7fde4464cf64b2fca994f7cdc128307e', 'Gestion des Entrepôts', NULL, 'entrepots', '859', '', 1, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(1410, 860, '462994905cf4ea3256839ee181df30f0', 'Ajouter Entrepôt', NULL, 'addentrepots', '860', '', 1, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(1411, 861, 'c7c70683c8539bf442fbb3bc145062d9', 'Editer Entrepôt', NULL, 'editentrepots', '861', '', 1, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(1412, 859, 'dfc69597b3f4c08061386486fd177e5b', 'Editer Entrepôt', 'this_url', 'editentrepots', 'pencil', '<li><a href="#" class="this_url" data="%id%" rel="editentrepots"  ><i class="ace-icon fa fa-pencil bigger-100"></i> Editer Entrepôt</a></li>', 0, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(1413, 862, '883e7763f88a902baf086a152a3c81a5', 'Supprimer Entrepôt', NULL, 'deleteentrepots', '862', '', 1, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(1414, 863, 'c717024f55a777aece4490406767f434', 'Valider Entrepôt', NULL, 'validentrepots', '863', '', 1, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(1415, 859, '4886df3c47866eac474e1d87f7774850', 'Valider Entrepôt', 'this_exec', 'validentrepots', 'check', '<li><a href="#" class="this_exec" data="%id%" rel="validentrepots"  ><i class="ace-icon fa fa-check bigger-100"></i> Valider Entrepôt</a></li>', 0, '[-1-]', 0, 1, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL);
INSERT INTO `task_action` (`id`, `appid`, `idf`, `descrip`, `mode_exec`, `app`, `class`, `code`, `type`, `service`, `etat_line`, `notif`, `etat_desc`, `message_class`, `message_etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(1416, 859, 'f16f69131a3e43f28acab8db040dbe1c', 'Désactiver Entrepôt', 'this_exec', 'validentrepots', 'lock', '<li><a href="#" class="this_exec" data="%id%" rel="validentrepots"  ><i class="ace-icon fa fa-lock bigger-100"></i> Désactiver Entrepôt</a></li>', 0, '[-1-]', 1, 0, 'Entrepôt Validé', 'success', '<span class="label label-sm label-success">Entrepôt Validé</span>', NULL, NULL, NULL, NULL),
(1417, 864, '530dbb6317ed3d348cd55d1f9a09e361', 'Mouvements de Stock', NULL, 'mouvements_stock', '864', '', 1, '[-1-]', 0, 0, 'Entrée Stock', 'info', '<span class="label label-sm label-info">Entrée Stock</span>', NULL, NULL, NULL, NULL),
(1418, 865, '558708106fa0f9c46f98e4d1e5fdf191', 'Gestion Tickets ', NULL, 'tickets', NULL, '', 0, '[-1-]', 0, 0, 'Attente Affectation', 'warning', '<span class="label label-sm label-warning">Attente Affectation</span>', NULL, NULL, NULL, NULL),
(1419, 865, '5f239991de8d679e13e20e7a1a6c8433', 'Modifier ticket   ', 'this_url', 'editticket', NULL, '<li><a href="#" class="this_url" data="%id%" rel="editticket"  ><i class="ace-icon fa fa-pencil-square-o bigger-100"></i> Modifier ticket   </a></li>', 0, '[-1-]', 0, 0, 'Attente Affectation', 'warning', '<span class="label label-sm label-warning">Attente Affectation</span>', NULL, NULL, NULL, NULL),
(1420, 865, 'd0f2728310c81c3c323da9c3ec91d998', 'Affecter ticket ', 'this_url', 'affecttechnicien', NULL, '<li><a href="#" class="this_url" data="%id%" rel="affecttechnicien"  ><i class="ace-icon fa fa-fire bigger-100"></i> Affecter ticket </a></li>', 0, '[-1-]', 0, 0, 'Attente Affectation', 'warning', '<span class="label label-sm label-warning">Attente Affectation</span>', NULL, NULL, NULL, NULL),
(1421, 865, 'db7e28569171b1218d7aa12f75684aa2', 'Détails ticket', 'this_url', 'detailsticket', NULL, '<li><a href="#" class="this_url" data="%id%" rel="detailsticket"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails ticket</a></li>', 0, '[-1-]', 0, 0, 'Attente Affectation', 'warning', '<span class="label label-sm label-warning">Attente Affectation</span>', NULL, NULL, NULL, NULL),
(1422, 865, '7813165051eca9e0dd0809ffc571b721', 'Détails ticket   ', 'this_url', 'detailsticket', NULL, '<li><a href="#" class="this_url" data="%id%" rel="detailsticket"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails ticket   </a></li>', 0, '[-1-]', 1, 0, 'Traitement En Cours', 'success', '<span class="label label-sm label-success">Traitement En Cours</span>', NULL, NULL, NULL, NULL),
(1423, 865, 'ade7658a655147c067bd85e554f5493b', 'Reaffecter ticket', 'this_url', 'reaffectticket', NULL, '<li><a href="#" class="this_url" data="%id%" rel="reaffectticket"  ><i class="ace-icon fa fa-fire bigger-100"></i> Reaffecter ticket</a></li>', 0, '[-1-]', 1, 0, 'Traitement En Cours', 'success', '<span class="label label-sm label-success">Traitement En Cours</span>', NULL, NULL, NULL, NULL),
(1424, 865, '599cb4aa075b2cc84746e15f14cd5e41', 'Ajouter action', 'this_url', 'addaction', NULL, '<li><a href="#" class="this_url" data="%id%" rel="addaction"  ><i class="ace-icon fa fa-gavel  bigger-100"></i> Ajouter action</a></li>', 0, '[-1-]', 1, 0, 'Traitement En Cours', 'success', '<span class="label label-sm label-success">Traitement En Cours</span>', NULL, NULL, NULL, NULL),
(1425, 865, '51691230fcf4cfd07729acff525de150', 'Confirmer Clôture', 'this_exec', 'clotureticket', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="clotureticket"  ><i class="ace-icon fa fa-bell bigger-100"></i> Confirmer Clôture</a></li>', 0, '[-1-]', 2, 0, 'Résolution Réussi', 'danger', '<span class="label label-sm label-danger">Résolution Réussi</span>', NULL, NULL, NULL, NULL),
(1426, 865, 'c6c0ab5988aab9c83960e0a9f261bfae', 'Détails ticket', 'this_url', 'detailsticket', NULL, '<li><a href="#" class="this_url" data="%id%" rel="detailsticket"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails ticket</a></li>', 0, '[-1-]', 2, 0, 'Résolution Réussi', 'danger', '<span class="label label-sm label-danger">Résolution Réussi</span>', NULL, NULL, NULL, NULL),
(1427, 865, 'f0ec59382bfa1553e2acb2cee8f0e8bf', 'Réaffecter ticket', 'this_url', 'reaffectticket', NULL, '<li><a href="#" class="this_url" data="%id%" rel="reaffectticket"  ><i class="ace-icon fa fa-fire  bigger-100"></i> Réaffecter ticket</a></li>', 0, '[-1-]', 2, 0, 'Résolution Réussi', 'danger', '<span class="label label-sm label-danger">Résolution Réussi</span>', NULL, NULL, NULL, NULL),
(1428, 865, '13a62e53e80790158f2d898c2ade40de', 'Détails ticket', 'this_url', 'detailsticket', NULL, '<li><a href="#" class="this_url" data="%id%" rel="detailsticket"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails ticket</a></li>', 0, '[-1-]', 3, 0, 'Ticket Clôturé', 'info', '<span class="label label-sm label-info">Ticket Clôturé</span>', NULL, NULL, NULL, NULL),
(1429, 866, '62191d5df0e93231d769d2b17e3b2d68', 'Ajouter ticket', NULL, 'addtickets', NULL, '', 1, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1430, 867, 'f0a2162636fb154c6f9361b86acd87d3', 'Modifier ticket', NULL, 'editticket', NULL, '', 1, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1431, 868, '962353cac56b445aa4cc0073d1fa3b21', 'Supprimer ticket', NULL, 'deleteticket', NULL, '', 1, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1432, 869, '9f7fa9f4122d308b77bd17e2b57bdd3e', 'Reafecter technicien', NULL, 'reafectticket', NULL, '', 1, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1433, 870, 'ebe36becbc7f17cb4157d27d62ca7c45', 'Affecter ticket', NULL, 'affecttechnicien', NULL, '', 1, '[-1-]', 0, 0, 'Attente de validation', 'warning', 'Attente de validation', NULL, NULL, NULL, NULL),
(1434, 871, '73a480d433c6b5e4d130e83eb8d734f8', 'Détails ticket', NULL, 'detailsticket', NULL, '', 1, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1435, 872, 'f496ab30882773264b90dd95dac97e07', 'Réaffecter ticket', NULL, 'reaffectticket', NULL, '', 1, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(1436, 873, 'aea1d98c0b188c62f976936454e99bf2', 'Ajouter action', NULL, 'addaction', NULL, '', 1, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1437, 874, '4f9403ccb1cf450f3ee7dc695e9d8f77', 'supprimer action ticket', 'this_exec', 'deleteactionticket', NULL, '<li><a href="#" class="this_exec" data="%id%" rel="deleteactionticket"  ><i class="ace-icon fa fa-trash bigger-100"></i> supprimer action ticket</a></li>', 0, '[-1-3-4-20-]', 0, 0, 'Attente validation', 'success', '<span class="label label-sm label-success">Attente validation</span>', NULL, NULL, NULL, NULL),
(1438, 875, '7ee7f4b765ab9f1ac4f2ef7a5a34b988', 'Modifier action', NULL, 'editaction', NULL, '', 1, '[-1-3-20-]', 0, 0, 'Attente validation', 'success', '<span class="label label-sm label-success">Attente validation</span>', NULL, NULL, NULL, NULL),
(1439, 876, 'a4dd36b475e19e7f31667939ef1c5cf5', 'Résolution Réussi', NULL, 'resolution', NULL, '', 1, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL),
(1440, 877, '775d77d66d847962bc363e3c8500004c', 'Ticket cloturé', NULL, 'clotureticket', NULL, '', 1, '[-1-]', 0, 0, 'Attente de validation', 'warning', '<span class="label label-sm label-warning">Attente de validation</span>', NULL, NULL, NULL, NULL),
(1441, 878, 'ca13381e080bc38cc9505039f618df36', 'Détails action', NULL, 'detailsaction', NULL, '', 1, '[-1-]', 0, 0, 'Attente Validation', 'warning', '<span class="label label-sm label-warning">Attente Validation</span>', NULL, NULL, NULL, NULL);

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
  `code_cloture` int(200) DEFAULT NULL COMMENT 'Code cloture',
  `observation` varchar(200) DEFAULT NULL COMMENT 'observation',
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=35 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Table users Systeme' AUTO_INCREMENT=25 ;

--
-- Contenu de la table `users_sys`
--

INSERT INTO `users_sys` (`id`, `nom`, `fnom`, `lnom`, `pass`, `mail`, `service`, `tel`, `etat`, `defapp`, `agence`, `ctc`, `lastactive`, `photo`, `signature`, `form`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(1, 'admin', 'Administrateur', 'Systeme', '5a05679021426829ab75ac9fa6655947', 'rachid@atelsolution.com', 1, '6544545454', 1, 0, 1, 0, '2018-05-02 10:49:19', 1, 2, 9, NULL, '2017-01-13 13:52:42', '1', '2017-06-06 19:22:54'),
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

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `qte_actuel` AS select `produits`.`id` AS `id_produit`,if(isnull((select sum(`stock`.`qte`) from `stock` where ((`stock`.`idproduit` = `produits`.`id`) and (`stock`.`mouvement` in ('E','S'))))),0,(select sum(`stock`.`qte`) from `stock` where ((`stock`.`idproduit` = `produits`.`id`) and (`stock`.`mouvement` in ('E','S'))))) AS `qte_act` from `produits` group by `produits`.`id`;

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `bl`
--
ALTER TABLE `bl`
  ADD CONSTRAINT `fk_devis_bl` FOREIGN KEY (`iddevis`) REFERENCES `devis` (`id`) ON UPDATE CASCADE;

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
-- Contraintes pour la table `compte_commerciale`
--
ALTER TABLE `compte_commerciale`
  ADD CONSTRAINT `fk_compte_commerciale` FOREIGN KEY (`id_commerciale`) REFERENCES `commerciaux` (`id`) ON UPDATE CASCADE;

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
-- Contraintes pour la table `d_factures`
--
ALTER TABLE `d_factures`
  ADD CONSTRAINT `fk_factures` FOREIGN KEY (`id_facture`) REFERENCES `factures` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
  ADD CONSTRAINT `fk_contrat` FOREIGN KEY (`idcontrat`) REFERENCES `contrats` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_devis` FOREIGN KEY (`iddevis`) REFERENCES `devis` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_echeance` FOREIGN KEY (`id_echeance`) REFERENCES `echeances_contrat` (`id`) ON UPDATE CASCADE;

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
  ADD CONSTRAINT `fk_produits_categorie` FOREIGN KEY (`idcategorie`) REFERENCES `ref_categories_produits` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_produits_type` FOREIGN KEY (`idtype`) REFERENCES `ref_types_produits` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_produits_uv` FOREIGN KEY (`iduv`) REFERENCES `ref_unites_vente` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_produit_entrepots` FOREIGN KEY (`id_entrepot`) REFERENCES `entrepots` (`id`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `ref_categories_produits`
--
ALTER TABLE `ref_categories_produits`
  ADD CONSTRAINT `fk_type_categorie` FOREIGN KEY (`type_produit`) REFERENCES `ref_types_produits` (`id`);

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
