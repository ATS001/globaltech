<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: commerciale
//Created : 30-12-2017
//Controller EXEC Form
$commerciale = new Mcommerciale();
$commerciale->id_commerciale = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D')or !$commerciale->get_commerciale())
{  
   // returne message error red to commerciale 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}


//Etat for validate row
//$etat = $commerciale->commerciale_info['etat'];
//$commerciale->deletecommerciale($etat)
//Execute Validate - delete


if($commerciale->delete_commerciale())
{
	exit("1#".$commerciale->log);

}else{
	exit("0#".$commerciale->log);
}