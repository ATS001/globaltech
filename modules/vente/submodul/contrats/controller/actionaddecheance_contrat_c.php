<?php
defined('_MEXEC') or die;
if(!MInit::crypt_tp('exec', null, 'D'))
{ 	
	$id     = Mreq::tp('exec');
	$idc    = MInit::crypt_tp('exec',$id);
	exit('0#<br>L\'action exécutée invalid contactez l\'administrateur '.$id.'  '.$idc);
}
//Action called from all button of this modul
$action = Mreq::tp('exec');


//Delete line detail_devis
if($action == 'delete')
{
	if(!MInit::crypt_tp('id', null, 'D'))
	{ 	
		exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
	}
//Initialise
	$id     = Mreq::tp('id');
	$idc    = MInit::crypt_tp('id',$id);
	$del_ech = new Mcontrat();
	if(!$del_ech->Delete_echance_contrat($id))
	{
		exit('0#'.$del_ech->log);
	}else{
		exit('1#'.$del_ech->log);
	}

}
////Get echeance info
//if($action == 'echeance_info')
//{
//	$id_echeance= Mreq::tp('id');
//	$sql = "SELECT * FROM echeances_contrat WHERE id = $id_echeance LIMIT 0,1";
//
//	global $db;
//
////exit($sqlRec);
//	if (!$db->Query($sql)) $db->Kill($db->Error()." SQLREC $sql");
//
//	$array_echeance = $db->RowArray();
//	
//}

