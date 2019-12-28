DELIMITER $$

USE `globaltech`$$

DROP PROCEDURE IF EXISTS `manuel_generate_facturation`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `manuel_generate_facturation`(IN tva INT, IN  echeance INT)
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
DECLARE id_devise INT;
DECLARE id_banque INT;
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
ec.id AS echeance, d.id_devise, d.id_banque
	     
 FROM clients c, devis d,d_devis dd, contrats ctr,ref_type_echeance ech ,echeances_contrat ec  
 WHERE d.id_client=c.id AND ctr.iddevis=d.id
 AND ctr.idtype_echeance=ech.id AND d.id=dd.id_devis AND ec.idcontrat=ctr.id 
 AND ec.`id` NOT IN (SELECT id_echeance FROM factures f WHERE f.`id_echeance`=ec.`id`)
 AND ctr.etat IN (1,2)
 AND ec.id=echeance;
 
DECLARE CONTINUE HANDLER FOR NOT FOUND SET finished = TRUE;
 OPEN cur1;
 read_loop: LOOP
 FETCH cur1 INTO totalht, totaltva, totalttc, CLIENT, contrat, projet, ref_bc, du, au, order_d, id_produit, ref_produit, 
 designation, qte, qte_designation, prix_unitaire, type_remise, remise_valeur, tva, prix_ht, prix_ttc, total_ht, total_ttc, total_tva,echeance_id, id_devise, id_banque;
 
 IF finished THEN
      LEAVE read_loop;
 END IF;
SELECT IFNULL(( MAX(CAST(SUBSTR(reference, 8, LENGTH(SUBSTR(reference,8))-5) AS SIGNED))),0)+1 AS reference 
FROM factures WHERE SUBSTR(reference,LENGTH(reference)-3,4)= (SELECT  YEAR(SYSDATE())) INTO ref ;
INSERT INTO factures (reference, base_fact, total_ht, total_tva, total_ttc_initial ,total_ttc, total_paye, reste,  CLIENT ,id_devise ,id_banque , projet, ref_bc, idcontrat, id_echeance, date_facture, du, au, creusr, credat) 
VALUES(CONCAT('GT-FCT-',(IF(ref<=9,CONCAT('000',ref),IF(ref>9 AND ref<=99,CONCAT('00',ref),IF(ref>99 AND ref<=999,CONCAT('0',ref),ref)))),'/',(SELECT  YEAR(SYSDATE())))
,'C',totalht,totaltva,totalttc,totalttc,0,totalttc,CLIENT,id_devise ,id_banque , projet, ref_bc, contrat, echeance_id,(SELECT NOW() FROM DUAL), du, au, 1,(SELECT NOW() FROM DUAL));
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

DELIMITER ;