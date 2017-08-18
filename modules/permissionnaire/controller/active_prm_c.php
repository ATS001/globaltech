<?php 
//Get all compte info 
 $info_prm = new Mprms();
//Set ID of Module with POST id
 $info_prm->id_prm = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D') or !$info_prm->get_prm())
{  
   // returne message error red to client 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}
//Execute activation desactivation
$etat = $info_prm->prm_info['etat'];

if($info_prm->valid_prm($etat))
{
	exit("1#".$info_prm->log);

}else{
	exit("0#".$info_prm->log);
}