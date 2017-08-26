
<?php
defined('_MEXEC') or die;
if(MInit::form_verif('addtype_produit',false))
{
	
  $posted_data = array(
   'type_produit'               => Mreq::tp('type_produit') ,
    
   
   );

  //Check if array have empty element return list
  //for acceptable empty field do not put here
  
  $checker = null;
  $empty_list = "Les champs suivants sont obligatoires:\n<ul>";

  if($posted_data['type_produit'] == NULL){

    $empty_list .= "<li>Type de produit</li>";
    $checker = 1;
  }

 
  $empty_list.= "</ul>";
  if($checker == 1)
  {
    exit("0#$empty_list");
  }

  //End check empty element


  $new_type_produit = new  Mtype_produit($posted_data);
 
  //execute Insert returne false if error
 if ($new_type_produit->save_new_type_produit()) {

        echo("1#" . $new_type_produit->log);
    } else {

        echo("0#" . $new_type_produit->log);
    }
} else {
    view::load('produits/settings/types_produits', 'addtype_produit');
}
