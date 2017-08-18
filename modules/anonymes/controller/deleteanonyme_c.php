<?php
//Check if Post ID <==> Post idc or get_prm return false. 

if(!MInit::crypt_tp('id', null, 'D'))
{
 // returne message error red to client 
 exit('3#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}
//Get all VSAT_Station info
$info_anonyme= new Manonyme();
//Set ID of Module with POST id
$info_anonyme->id_anonyme = Mreq::tp('id');
if($info_anonyme->delete_anonyme())
{
    exit("1#".$info_anonyme->log); //Return Green Message
}else{
    exit("0#".$info_anonyme->log); //Return Red Message
} 

?>