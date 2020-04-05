<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: contrats
//Created : 28-02-2018
//Controller EXEC Form
$contrat = new Mcontrat();
$contrat->id_echeance_contrat= Mreq::tp('id');


if(!MInit::crypt_tp('id', null, 'D')or !$contrat->get_echeance_contrat())
{  
   // returne message error red to contrats 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}

	if($contrat->generate_facture(Mreq::tp('id')))
	{
		exit("1#".$contrat->log);			
	}else{
		exit("0#".$contrat->log);
    }