DELIMITER $$

USE `globaltech`$$

DROP PROCEDURE IF EXISTS `generate_devis_fact`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generate_devis_fact`(IN `id_devis` INT)
BEGIN
DECLARE ref VARCHAR(20);
DECLARE cpt_fact INT;
DECLARE devis INT;
SELECT CONCAT((SELECT IFNULL(( MAX(CAST(SUBSTR(reference, 8, LENGTH(SUBSTR(reference,8))-5) AS SIGNED))),0)+1 AS reference 
FROM factures WHERE SUBSTR(reference,LENGTH(reference)-3,4)= (SELECT  YEAR(SYSDATE()))),'/',(SELECT  YEAR(SYSDATE()))) INTO ref ;
INSERT INTO factures (reference, base_fact, total_ht, total_tva, total_ttc_initial ,total_ttc, total_paye, reste,
			CLIENT, projet, ref_bc, iddevis, date_facture, creusr, credat) 
SELECT CONCAT('GT-FCT-',(IF(ref<=9,CONCAT('000',ref),IF(ref>9 AND ref<=99,CONCAT('00',ref),IF(ref>99 AND ref<=999,CONCAT('0',ref),ref)))))
,'D', d.totalht, d.totaltva, d.totalttc, d.totalttc, 0, d.totalttc, c.denomination, d.projet, d.ref_bc,
			d.id, (SELECT NOW() FROM DUAL), 1,(SELECT NOW() FROM DUAL)
FROM clients c, devis d
WHERE d.id_client=c.id  AND d.id=id_devis;
SELECT MAX(id),id_devis FROM factures f INTO cpt_fact,devis;
INSERT INTO d_factures SELECT (SELECT `AUTO_INCREMENT` FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'globaltech' AND   TABLE_NAME   = 'd_factures'),
  dd.`order`, cpt_fact, dd.id_produit, dd.ref_produit, dd.designation, dd.qte, ' ', dd.prix_unitaire, dd.type_remise,
  dd.remise_valeur, dd.tva, dd.prix_ht, dd.prix_ttc, dd.total_ht, dd.total_ttc, dd.total_tva,'admin', 
  (SELECT DATE(NOW()) FROM DUAL), NULL, NULL FROM d_devis dd WHERE dd.id_devis=devis
			     ; 
END$$

DELIMITER ;



DELIMITER $$

USE `globaltech`$$

DROP PROCEDURE IF EXISTS `generate_fact`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generate_fact`(IN `tva` INT)
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
			 IF(ech.type_echeance='Autres' AND d.tva='O',(SELECT ec.montant - (ec.montant * tva /100) FROM echeances_contrat ec WHERE ec.idcontrat=ctr.id AND (SELECT DATE(NOW()) FROM DUAL)=ec.date_echeance)
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
			 IF(ech.type_echeance='Autres' AND d.tva='O',(SELECT (ec.montant * tva /100) FROM echeances_contrat ec WHERE ec.idcontrat=ctr.id AND (SELECT DATE(NOW()) FROM DUAL)=ec.date_echeance)
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
 AND  (SELECT DATE(NOW())) BETWEEN ctr.date_effet AND ctr.date_fin
 AND ctr.etat IN (1,2)
 AND (SELECT DATE(NOW()) FROM DUAL)= (CASE 
	WHEN ech.type_echeance='Annuelle'       THEN 
	(IF ((SELECT COUNT(*) FROM factures f WHERE f.idcontrat=ctr.id)>0, 
	     (SELECT DATE_ADD(MAX(f.date_facture), INTERVAL 1 YEAR) FROM factures f WHERE f.idcontrat=ctr.id),
	      IF(ctr.periode_fact='F',DATE_ADD(date_effet, INTERVAL 1 YEAR),date_effet)
	     )
	)
	WHEN ech.type_echeance='Mensuelle'      THEN 
	(IF ((SELECT COUNT(*) FROM factures f WHERE f.idcontrat=ctr.id)>0, 
	     (SELECT DATE_ADD(MAX(f.date_facture), INTERVAL 1 MONTH) FROM factures f WHERE f.idcontrat=ctr.id),
	      IF(ctr.periode_fact='F',DATE_ADD(date_effet, INTERVAL 1 MONTH),date_effet)
	     )
	)	
	WHEN ech.type_echeance='Trimestrielle'  THEN 
	(IF ((SELECT COUNT(*) FROM factures f WHERE f.idcontrat=ctr.id)>0, 
	     (SELECT DATE_ADD(MAX(f.date_facture), INTERVAL 3 MONTH) FROM factures f WHERE f.idcontrat=ctr.id),
	      IF(ctr.periode_fact='F',DATE_ADD(date_effet, INTERVAL 3 MONTH),date_effet)
	     )
	)
	WHEN ech.type_echeance='Simestrielle'   THEN 
	(IF ((SELECT COUNT(*) FROM factures f WHERE f.idcontrat=ctr.id)>0, 
	     (SELECT DATE_ADD(MAX(f.date_facture), INTERVAL 6 MONTH) FROM factures f WHERE f.idcontrat=ctr.id),
	     IF(ctr.periode_fact='F',DATE_ADD(date_effet, INTERVAL 6 MONTH),date_effet)
	     )
	)
	WHEN ech.type_echeance='Autres'	       THEN 
	(IF ((SELECT COUNT(*) FROM factures f WHERE f.idcontrat=ctr.id AND (SELECT DATE(NOW()) FROM DUAL)=f.date_facture)>0, 
	     NULL,
	     (SELECT date_echeance FROM echeances_contrat ec WHERE ec.idcontrat=ctr.id AND (SELECT DATE(NOW()) FROM DUAL)=date_echeance)
	     )
	)
	
END );
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

DELIMITER ;