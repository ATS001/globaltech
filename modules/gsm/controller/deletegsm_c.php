<?php
//Check if Post ID <==> Post idc or get_prm return false. 

if(!MInit::crypt_tp('id', null, 'D'))
{
 // returne message error red to client 
 exit('3#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}
//Get all VSAT_Station info
$info_gsm= new Mgsm();
//Set ID of Module with POST id
$info_gsm->id_gsm = Mreq::tp('id');
if($info_gsm->delete_gsm())
{
    exit("1#".$info_gsm->log); //Return Green Message
}else{
    exit("0#".$info_gsm->log); //Return Red Message
} 

?>