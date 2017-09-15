		<?php
		//Check if Post ID <==> Post idc or get_achat return false. 

		if(!MInit::crypt_tp('id', null, 'D'))
		{
		 // returne message error red to client 
		 exit('3#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
		}
		//Get all produits info
		$info_achat= new Machat();
		//Set ID of Module with POST id
		$info_achat->id_achat = Mreq::tp('id');
		if($info_achat->delete_achat_produit())
		{
		    exit("1#".$info_achat->log); //Return Green Message
		}else{
		    exit("0#".$info_achat->log); //Return Red Message
		} 


		?>