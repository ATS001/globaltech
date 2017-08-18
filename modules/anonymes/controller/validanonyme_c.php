<?php 
//Get all compte info 
 $info_anonyme = new Manonyme();
//Set ID of Module with POST id
 $info_anonyme->id_anonyme = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D') or !$info_anonyme->get_anonyme())
{  
   // returne message error red to client 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}
//Execute activation desactivation
$etat = $info_anonyme->anonyme_info['etat'];

if($info_anonyme->valid_anonyme($etat))
{
	exit("1#".$info_anonyme->log);

}else{
	exit("0#".$info_anonyme->log);
}