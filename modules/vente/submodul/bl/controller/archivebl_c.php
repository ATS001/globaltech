<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: bl
//Created : 01-08-2018
//Controller EXEC Form
$bl = new Mbl();
$bl->id_bl = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D')or !$bl->get_bl())
{  
   // returne message error red to bl 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}


//Etat for validate row
//$etat = $bl->bl_info['etat'];
//$bl->archivebl($etat)
//Execute Validate - delete


if($bl->archivebl())
{
	exit("1#".$bl->log);

}else{
	exit("0#".$bl->log);
}