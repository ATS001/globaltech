
<?php
defined('_MEXEC') or die;
//if(MInit::form_verif(false))
if(MInit::form_verif('addvsat_hub', false))
{
  
  $posted_data = array(
   'operateur'                 => Mreq::tp('operateur') ,
   'ville_hub'                 => Mreq::tp('ville_hub') ,
   'email_hub'                 => Mreq::tp('email_hub'),
   'pays_hub'                  => Mreq::tp('pays_hub')  
  );

  
  //Check if array have empty element return list
  //for acceptable empty field do not put here
    $checker = null;
    $empty_list = "Les champs suivants sont obligatoires:\n<ul>";
    if($posted_data['operateur'] == NULL){

      $empty_list .= "<li>Nom de l\'operateur</li>";
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
    if($posted_data['pays_hub'] == NULL){

      $empty_list .= "<li>Pays</li>";
      $checker = 1;
    }
  
    
    $empty_list.= "</ul>";
    if($checker == 1)
    {
      exit("0#$empty_list");
    }


  $new_vsat_hub = new  Mvsat_hub($posted_data);
  
  

  //execute Insert returne false if error
  if($new_vsat_hub->save_new_vsat_hub()){

    exit("1#".$new_vsat_hub->log);
    

  }else{

    exit("0#".$new_vsat_hub->log);
    
  }

} else {
  view::load('vsat/settings/vsat_hub','addvsat_hub');
}

