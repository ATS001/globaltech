
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
update contrats c set c.`etat`= 3 where (SELECT date(NOW()) FROM DUAL)=(c.`date_fin`+1) ;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `notify_contrat_frn`()
BEGIN
    
update contrats_frn c set c.`etat`= 2 where (SELECT date(NOW()) FROM DUAL)=c.`date_notif` and c.etat=1 ;
update contrats_frn c set c.`etat`= 3 where (SELECT date(NOW()) FROM DUAL)=(c.`date_fin`+1) ;
    END$$

DELIMITER ;
