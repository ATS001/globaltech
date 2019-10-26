<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: contrats
//Created : 31-07-2018
//Controller EXEC Form
$contrats = new Mcontrat();
$contrats->id_contrat = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D')or !$contrats->get_contrat())
{  
   // returne message error red to contrats 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}

if($contrats->archivecontrats())
{
	exit("1#".$contrats->log);

}else{
	exit("0#".$contrats->log);
}