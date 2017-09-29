<?php 

//Get all compte info 
 $info_proforma = new Mproforma();
//Set ID of Module with POST id
 $info_proforma->id_proforma = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D') or !$info_proforma->get_proforma())
{  
   // returne message error red to client 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}



//exit("1#".$etat.' '.$info_proforma->id_cp_proforma);
if($info_proforma->delete_proforma())
{
	exit("1#".$info_proforma->log);

}else{
	exit("0#".$info_proforma->log);
}