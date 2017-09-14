
<?php
defined('_MEXEC') or die;
if(MInit::form_verif('addunite_vente',false))
{
	
  $posted_data = array(
   'unite_vente'               => Mreq::tp('unite_vente') ,
    
   
   );

  //Check if array have empty element return list
  //for acceptable empty field do not put here
  
  $checker = null;
  $empty_list = "Les champs suivants sont obligatoires:\n<ul>";

  if($posted_data['unite_vente'] == NULL){

    $empty_list .= "<li>Unité de vente</li>";
    $checker = 1;
  }

  
  
  $empty_list.= "</ul>";
  if($checker == 1)
  {
    exit("0#$empty_list");
  }

  //End check empty element


  $new_unite_vente = new  Munite_vente($posted_data);
 
  //execute Insert returne false if error
 if ($new_unite_vente->save_new_unite_vente()) {

        echo("1#" . $new_unite_vente->log);
    } else {

        echo("0#" . $new_unite_vente->log);
    }
} else {
    view::load('Systeme/settings/unites_vente', 'addunite_vente');
}
