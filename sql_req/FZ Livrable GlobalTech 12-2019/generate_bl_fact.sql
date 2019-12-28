DELIMITER $$

USE `globaltech`$$

DROP PROCEDURE IF EXISTS `generate_bl_fact`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generate_bl_fact`(IN id_bl INT)
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
DECLARE totalht DOUBLE; 
DECLARE totalttc DOUBLE; 
DECLARE totaltva DOUBLE;
DECLARE cur1 CURSOR FOR 
SELECT b.`order`, b.id_produit, b.ref_produit, b.designation, b.`qte`, dd.prix_unitaire, dd.type_remise,
dd.remise_valeur, dd.tva, dd.prix_ht,dd.prix_ttc, ((dd.total_ht/dd.qte) * b.qte) AS total_ht,
((dd.total_ttc/dd.qte) * b.qte ) AS total_ttc,((dd.total_tva /dd.qte)* b.qte) AS total_tva
FROM devis d,d_devis dd, bl, d_bl b WHERE d.id=dd.`id_devis` AND d.id=bl.`iddevis` AND dd.id_produit=b.id_produit 
AND bl.`id`=b.`id_bl` AND bl.id=id_bl;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET finished = TRUE; 
SELECT IFNULL(( MAX(CAST(SUBSTR(reference, 8, LENGTH(SUBSTR(reference,8))-5) AS SIGNED))),0)+1 AS reference 
FROM factures WHERE SUBSTR(reference,LENGTH(reference)-3,4)= (SELECT  YEAR(SYSDATE())) INTO ref ;
SELECT SUM((dd.total_ht/dd.qte) * b.qte) AS total_ht, SUM((dd.total_tva/dd.qte) * b.qte) AS total_tva,
SUM((dd.total_ttc/dd.qte) * b.qte) AS total_ttc
FROM devis d,d_devis dd, bl, d_bl b WHERE d.id=dd.`id_devis` AND d.id=bl.`iddevis` 
AND dd.id_produit=b.id_produit AND bl.`id`=b.`id_bl` AND bl.id=id_bl INTO totalht, totaltva, totalttc;
INSERT INTO factures (reference, base_fact, total_ht, total_tva, total_ttc_initial ,total_ttc, total_paye, reste,
			CLIENT, id_devise, id_banque, projet, ref_bc, iddevis, idbl, date_facture, creusr, credat) 
SELECT CONCAT('GT-FCT-',(IF(ref<=9,CONCAT('000',ref),IF(ref>9 AND ref<=99,CONCAT('00',ref),IF(ref>99 AND ref<=999,CONCAT('0',ref),ref)))),'/',(SELECT  YEAR(SYSDATE())))
,'B', totalht , totaltva , totalttc , totalttc , 0, totalttc , c.denomination, d.id_devise, d.id_banque, d.projet, d.ref_bc,
d.id, bl.id, (SELECT NOW() FROM DUAL), 1,(SELECT NOW() FROM DUAL)
FROM clients c, devis d, bl
WHERE d.id_client=c.id AND d.id=bl.`iddevis`  AND bl.id=id_bl;
SELECT MAX(id) FROM factures f  WHERE f.idbl=id_bl INTO cpt_fact;
 OPEN cur1;
 read_loop: LOOP
 
 FETCH cur1 INTO ORDER_d, id_produit, ref_produit, designation, qte, prix_unitaire, type_remise,
 remise_valeur, tva, prix_ht, prix_ttc, total_ht, total_ttc, total_tva;
   
 IF finished THEN
      LEAVE read_loop;
 END IF;
INSERT INTO d_factures (`order`, id_facture, id_produit, ref_produit, designation, qte, qte_designation, prix_unitaire, type_remise, 
 remise_valeur, tva, prix_ht, prix_ttc, total_ht, total_ttc, total_tva, creusr ) 
VALUES( ORDER_d, cpt_fact, id_produit, ref_produit, designation, qte, ' ', prix_unitaire, type_remise,
 remise_valeur, tva, prix_ht, prix_ttc, total_ht, total_ttc, total_tva, 1);
  
END LOOP;
CLOSE cur1;
    END$$

DELIMITER ;