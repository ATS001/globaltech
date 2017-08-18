<?php
defined('_MEXEC') or die;
if(MInit::form_verif('editvsat_hub', false))
{
  
  $posted_data = array(
    'id'             => Mreq::tp('id') ,
   'operateur'       => Mreq::tp('operateur') ,
   'pays_hub'        => Mreq::tp('pays_hub') ,
   'ville_hub'       => Mreq::tp('ville_hub') ,
   'email_hub'       => Mreq::tp('email_hub') ,
   
   
  );

  
  //Check if array have empty element return list
  //for acceptable empty field do not put here
  
  

     $checker = null;
    $empty_list = "Les champs suivants sont obligatoires:\n<ul>";
    if($posted_data['operateur'] == NULL){

      $empty_list .= "<li>Nom de l\'opérateur</li>";
      $checker = 1;
    }
    if($posted_data['pays_hub'] == NULL){

      $empty_list .= "<li>Pays</li>";
      $checker = 1;
    }    
     if($posted_data['ville_hub'] == NULL){

      $empty_list .= "<li>Ville</li>";
      $checker = 1;
    }
    if($posted_data['email_hub'] == NULL){

      $empty_list .= "<li>E-mail</li>";
      $checker = 1;
    }
   
  
    
    $empty_list.= "</ul>";
    if($checker == 1)
    {
      exit("0#$empty_list");
    }


  //End check empty element


  $new_vsat_hub = new  Mvsat_hub($posted_data);
  $new_vsat_hub->id_vsat_hub = $posted_data['id'];
  
  
  

  //execute Insert returne false if error
  if($new_vsat_hub->update_vsat_hub()){

    exit("1#".$new_vsat_hub->log);

  }else{

    exit("0#".$new_vsat_hub->log);
  }

} else {
  view::load('vsat/settings/vsat_hub','editvsat_hub');
}


?>