<?php 
//Get all revendeur info 
 $info_rev = new Mrev();
//Set ID of Module with POST id
 $info_rev->id_rev = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D') or !$info_rev->get_rev())
{  
   // returne message error red to client 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}


if($info_rev->delete_rev())
{
	exit("1#".$info_rev->log);

}else{
	exit("0#".$info_rev->log);
}