<?php
//Check if Post ID <==> Post idc or get_produit return false. 

if(!MInit::crypt_tp('id', null, 'D'))
{
 // returne message error red to client 
 exit('3#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}
//Get all produits info
$info_produit= new Mproduit();
//Set ID of Module with POST id
$info_produit->id_produit = Mreq::tp('id');
if($info_produit->delete_produit())
{
    exit("1#".$info_produit->log); //Return Green Message
}else{
    exit("0#".$info_produit->log); //Return Red Message
} 

?>