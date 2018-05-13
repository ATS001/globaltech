<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: entrepots
//Created : 25-04-2018
//Controller EXEC Form
$entrepots = new Mentrepots();
$entrepots->id_entrepots = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D')or !$entrepots->get_entrepots())
{  
   // returne message error red to entrepots 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}


//Etat for validate row
$etat = $entrepots->entrepots_info['etat'];

//$entrepots->validentrepots($etat)
//Execute Validate - delete


if($entrepots->valid_entrepots($etat))
{
	exit("1#".$entrepots->log);

}else{
	exit("0#".$entrepots->log);
}