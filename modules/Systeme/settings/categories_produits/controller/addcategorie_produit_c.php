
<?php
defined('_MEXEC') or die;
if(MInit::form_verif('addcategorie_produit',false))
{
	
  $posted_data = array(
   'categorie_produit'               => Mreq::tp('categorie_produit') ,
    
   
   );

  //Check if array have empty element return list
  //for acceptable empty field do not put here
  
  $checker = null;
  $empty_list = "Les champs suivants sont obligatoires:\n<ul>";

  if($posted_data['categorie_produit'] == NULL){

    $empty_list .= "<li>Catégorie produit</li>";
    $checker = 1;
  }

 
  $empty_list.= "</ul>";
  if($checker == 1)
  {
    exit("0#$empty_list");
  }

  //End check empty element


  $new_categorie_produit = new  Mcategorie_produit($posted_data);
 
  //execute Insert returne false if error
 if ($new_categorie_produit->save_new_categorie_produit()) {

        echo("1#" . $new_categorie_produit->log);
    } else {

        echo("0#" . $new_categorie_produit->log);
    }
} else {
    view::load('categories_produits', 'addcategorie_produit');
}
