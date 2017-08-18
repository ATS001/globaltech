<?php
//Check if Post ID <==> Post idc or get_prm return false. 

if(!MInit::crypt_tp('id', null, 'D'))
{
 // returne message error red to client 
 exit('3#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}
//Get all VSAT_Station info
$info_vsat= new Mvsat();
//Set ID of Module with POST id
$info_vsat->id_vsat = Mreq::tp('id');
if($info_vsat->delete_vsat())
{
    exit("1#".$info_vsat->log); //Return Green Message
}else{
    exit("0#".$info_vsat->log); //Return Red Message
} 

?>