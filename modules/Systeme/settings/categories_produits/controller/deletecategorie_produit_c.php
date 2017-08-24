<<<<<<< HEAD
<?php
//Check if Post ID <==> Post idc or get_categorie_produit return false. 

if(!MInit::crypt_tp('id', null, 'D'))
{
 // returne message error red to client 
 exit('3#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}
//Get all categorie_produit info
$info_categorie_produit= new Mcategorie_produit();
//Set ID of Module with POST id
$info_categorie_produit->id_categorie_produit = Mreq::tp('id');
if($info_categorie_produit->delete_categorie_produit())
{
    exit("1#".$info_categorie_produit->log); //Return Green Message
}else{
    exit("0#".$info_categorie_produit->log); //Return Red Message
} 

?>
=======
<?php 
//SYS GLOBAL TECH
// Modul: categories_produits => Controller
>>>>>>> refs/remotes/origin/last
