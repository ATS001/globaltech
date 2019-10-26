<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: visites
//Created : 22-10-2019
//Controller EXEC Form
$visites = new Mvisites();
$visites->id_visites = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D')or !$visites->get_visites())
{  
   // returne message error red to visites 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}


//Etat for validate row
//$etat = $visites->visites_info['etat'];
//$visites->validvisite($etat)
//Execute Validate - delete


if($visites->valid_visites(0))
{
	exit("1#".$visites->log);

}else{
	exit("0#".$visites->log);
}