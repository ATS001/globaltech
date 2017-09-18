<?php 
//Get all Devis info 
$info_devis = new Mdevis();
//Set ID of Module with POST id
$info_devis->id_devis = 22;
print $info_devis->Gettable_detail_devis();

?>