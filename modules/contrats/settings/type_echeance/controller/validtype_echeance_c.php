<?php 
//SYS GLOBAL TECH
// Modul: type_echeance => Controller

$type_echeance = new Mtype_echeance();
$type_echeance->id_type_echeance = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D')or !$type_echeance->get_type_echeance())
{  
   // returne message error red to client 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}



//Execute activation desactivation
$etat = $type_echeance->type_echeance_info['etat'];

if($type_echeance->valid_type_echeance($etat))
{
	exit("1#".$type_echeance->log);

}else{
	exit("0#".$type_echeance->log);
}