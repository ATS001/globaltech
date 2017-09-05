<?php 
//SYS GLOBAL TECH
// Modul: categorie_client => Controller


defined('_MEXEC') or die;
if(MInit::form_verif('addcategorie_client',false))
{
	
  $posted_data = array(
   'categorie_client'      => Mreq::tp('categorie_client') 
   );

  
  //Check if array have empty element return list
  //for acceptable empty field do not put here
  
  
$checker = null;
  $empty_list = "Les champs suivants sont obligatoires:\n<ul>";

  if($posted_data['categorie_client'] == NULL){

    $empty_list .= "<li>Catégorie client</li>";
    $checker = 1;
  }
  

  $empty_list.= "</ul>";
  if($checker == 1)
  {
    exit("0#$empty_list");
  }

  //End check empty element


 $new_categorie_client = new  Mcategorie_client($posted_data);
  
  

  //execute Insert returne false if error
  if($new_categorie_client->save_new_categorie_client()){

    echo("1#".$new_categorie_client->log);
  }else{

    echo("0#".$new_categorie_client->log);
  }


} else {
  view::load('clients/settings/categorie_client','addcategorie_client');
}
?>