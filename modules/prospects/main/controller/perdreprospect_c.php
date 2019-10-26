<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: prospects
//Created : 25-10-2019
//Controller EXEC Form
$prospects = new Mprospects();
$prospects->id_prospect = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D')or !$prospects->get_prospect())
{  
   // returne message error red to prospects 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}

//Etat for validate row
$etat = $prospects->prospects_info['etat'];

//Execute Validate 
if($prospects->valid_prospect($etat,'P'))
{
	exit("1#".$prospects->log);

}else{
	exit("0#".$prospects->log);
}