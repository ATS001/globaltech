<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: contrats
//Created : 28-02-2018
//Controller EXEC Form
$contrats = new Mcontrats();
$contrats->id_contrats = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D')or !$contrats->get_contrats())
{  
   // returne message error red to contrats 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}


//Etat for validate row
//$etat = $contrats->contrats_info['etat'];
//$contrats->generatefacture($etat)
//Execute Validate - delete


if($contrats->generatefacture())
{
	exit("1#".$contrats->log);

}else{
	exit("0#".$contrats->log);
}