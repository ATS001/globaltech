	<?php
	//Check if Post ID <==> Post idc or get_type_produit return false. 

	if(!MInit::crypt_tp('id', null, 'D'))
	{
	 // returne message error red to client 
	 exit('3#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
	}
	//Get all type_produit info
	$info_type_produit= new Mtype_produit();
	//Set ID of Module with POST id
	$info_type_produit->id_type_produit = Mreq::tp('id');
	if($info_type_produit->delete_type_produit())
	{
	    exit("1#".$info_type_produit->log); //Return Green Message
	}else{
	    exit("0#".$info_type_produit->log); //Return Red Message
	} 

	?>