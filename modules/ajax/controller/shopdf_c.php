 <?php

 function shopdf($iddoc){

 	global $db ;	
 	$query="select doc  from archive where id =$iddoc";

 	if (!$db->Query($query)) $db->Kill("0#".$db->Error());
 	$countrow=$db->RowCount();

 	if($countrow == 0){
 		exit("0#fichier n'existe pas dans la base de données");
 	}	
 	$array = $db->RowArray();
 	$targ  = $array['doc'];

 	if (!file_exists($targ)){

 		exit("0#fichier n'existe pas dans les archives");
 	}else{
 		exit("1#$targ");
 	}

 }
//Execute
 shopdf(Mreq::tp("doc"));




 ?>