<?php 
//Get all compte info 
 $info_blr_stations = new Mblr_stations();
//Set ID of Module with POST id
 $info_blr_stations->id_blr_stations = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D') or !$info_blr_stations->get_blr_stations())
{  
   // returne message error red to client 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}
//Execute activation desactivation
$etat = $info_blr_stations->blr_stations_info['etat'];

if($info_blr_stations->valid_blr_stations($etat))
{
	exit("1#".$info_blr_stations->log);

}else{
	exit("0#".$info_blr_stations->log);
}