<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: bl
//Created : 02-05-2018
//Controller EXEC Form
$bl = new Mbl();
$bl->id_bl = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D')or !$bl->get_bl())
{  
   // returne message error red to bl 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}


//Etat for validate row
$etat = $bl->bl_info['etat'];
$id_bl = $bl->bl_info['id'];

if($bl->verif_qte_stock()){

  	if($bl->mouvementer_stock()){	

		if($bl->valid_bl($etat)){
			
			exit("1#".$bl->log);

		}else{
				exit("0#".$bl->log);
		}
  	}else{
			exit("0#".$bl->log);
	}
}
else{
		exit("0#".$bl->log);
}
