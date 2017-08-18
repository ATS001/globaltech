<?php 
//Get all compte info 
 $info_uhf_vhf_stations = new Muhf_vhf_stations();
//Set ID of Module with POST id
 $info_uhf_vhf_stations->id_uhf_vhf_stations = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D') or !$info_uhf_vhf_stations->get_uhf_vhf_stations())
{  
   // returne message error red to client 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}
//Execute activation desactivation
$etat = $info_uhf_vhf_stations->uhf_vhf_stations_info['etat'];

if($info_uhf_vhf_stations->valid_uhf_vhf_stations($etat))
{
	exit("1#".$info_uhf_vhf_stations->log);

}else{
	exit("0#".$info_uhf_vhf_stations->log);
}