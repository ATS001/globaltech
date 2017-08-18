<?php 
//Get all technologie info 
 $info_technologie = new Mgsm_technologie();
//Set ID of Module with POST id
 $info_technologie->id_technologie = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D') or !$info_technologie->get_technologie())
{  
   // returne message error red to client 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}


if($info_technologie->delete_technologie())
{
	exit("1#".$info_technologie->log);

}else{
	exit("0#".$info_technologie->log);
}