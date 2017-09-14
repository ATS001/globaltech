<?php 

//Get all compte info 
 $info_devis = new Mdevis();
//Set ID of Module with POST id
 $info_devis->id_devis = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D') or !$info_devis->get_devis())
{  
   // returne message error red to client 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}



//exit("1#".$etat.' '.$info_devis->id_cp_devis);
if($info_devis->delete_devis())
{
	exit("1#".$info_devis->log);

}else{
	exit("0#".$info_devis->log);
}