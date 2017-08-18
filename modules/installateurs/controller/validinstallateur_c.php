<?php 
//Get all instal info 
 $info_instal = new Minstal();
//Set ID of Module with POST id
 $info_instal->id_instal = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D') or !$info_instal->get_instal())
{  
   // returne message error red to client 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}
//Execute activation desactivation
$etat = $info_instal->instal_info['etat'];

if($info_instal->valid_instal($etat))
{
	exit("1#".$info_instal->log);

}else{
	exit("0#".$info_instal->log);
}