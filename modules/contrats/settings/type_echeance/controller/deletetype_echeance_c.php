<?php 
//SYS GLOBAL TECH
// Modul: type_echeance => Controller
defined('_MEXEC') or die;

//Get type_echeance info
$info_type_echeance = new Mtype_echeance();
$info_type_echeance->id_type_echeance = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D')or !$info_type_echeance->get_type_echeance())
{ 	
	exit('3#'.$info_type_echeance->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}

  //execute Delete returne false if error
if($info_type_echeance->delete_type_echeance()){

	exit("1#".$info_type_echeance->log);
}else{

	exit("0#".$info_type_echeance->log);
}