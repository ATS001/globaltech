	<?php
	//Check if Post ID <==> Post idc or get_unite_vente return false. 

	if(!MInit::crypt_tp('id', null, 'D'))
	{
	 // returne message error red to client 
	 exit('3#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
	}
	//Get all unites_vente info
	$info_unite_vente= new Munite_vente();
	//Set ID of Module with POST id
	$info_unite_vente->id_unite_vente = Mreq::tp('id');
	if($info_unite_vente->delete_unite_vente())
	{
	    exit("1#".$info_unite_vente->log); //Return Green Message
	}else{
	    exit("0#".$info_unite_vente->log); //Return Red Message
	} 

	?>