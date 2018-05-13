--
-- Fonctions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `generate_bl`(`id_devis` INT) RETURNS int(11)
BEGIN
    
DECLARE ref_bl VARCHAR(20);
DECLARE cpt_bl INT;
DECLARE ORDER_d INT;
DECLARE id_produit INT; 
DECLARE ref_produit VARCHAR(200);
DECLARE designation VARCHAR(200); 
DECLARE qte INT; 
SELECT CONCAT((SELECT IFNULL(( MAX(CAST(SUBSTR(reference, 7, LENGTH(SUBSTR(reference,7))-5) AS SIGNED))),0)+1 AS reference 
FROM bl WHERE SUBSTR(reference,LENGTH(reference)-3,4)= (SELECT  YEAR(SYSDATE()))),'/',(SELECT  YEAR(SYSDATE()))) INTO ref_bl ;
INSERT INTO bl (reference, CLIENT, projet, ref_bc, iddevis, date_bl, creusr, credat) 
SELECT CONCAT('GT-BL-',(IF(ref_bl<=9,CONCAT('000',ref_bl),IF(ref_bl>9 AND ref_bl<=99,CONCAT('00',ref_bl),IF(ref_bl>99 AND ref_bl<=999,CONCAT('0',ref_bl),ref_bl)))))
, c.denomination, d.projet, d.ref_bc,
			d.id, (SELECT NOW() FROM DUAL), 1,(SELECT NOW() FROM DUAL)
FROM clients c, devis d
WHERE d.id_client=c.id  AND d.id=id_devis;
SELECT MAX(id) FROM bl WHERE iddevis=id_devis INTO cpt_bl;
return cpt_bl;
    END$$

DELIMITER ;

-- --------------------------------------------------------
--Execute and read result 
SELECT  generate_bl(61) ;