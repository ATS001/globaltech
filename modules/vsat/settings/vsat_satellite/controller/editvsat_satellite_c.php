<?php
defined('_MEXEC') or die;
if(MInit::form_verif('editsat', false))
{
  
  $posted_data = array(
    'id'             => Mreq::tp('id') ,
   'satellite'                    => Mreq::tp('satellite') ,
   'position_orbitale'            => Mreq::tp('position_orbitale') ,
   'pay_operator'                 => Mreq::tp('pay_operator') ,
   'contractor'                   => Mreq::tp('contractor') ,
   
   
  );

  
  //Check if array have empty element return list
  //for acceptable empty field do not put here
  
  

     $checker = null;
    $empty_list = "Les champs suivants sont obligatoires:\n<ul>";
    if($posted_data['satellite'] == NULL){

      $empty_list .= "<li>Nom du satellite</li>";
      $checker = 1;
    }
    if($posted_data['position_orbitale'] == NULL){

      $empty_list .= "<li>Position orbitale</li>";
      $checker = 1;
    }    
     if($posted_data['pay_operator'] == NULL){

      $empty_list .= "<li>Pays</li>";
      $checker = 1;
    }
    if($posted_data['contractor'] == NULL){

      $empty_list .= "<li>Fournisseur</li>";
      $checker = 1;
    }
   
  
    
    $empty_list.= "</ul>";
    if($checker == 1)
    {
      exit("0#$empty_list");
    }


  //End check empty element


  $new_satellite = new  Msatellite($posted_data);
  $new_satellite->id_satellite = $posted_data['id'];
  
  
  

  //execute Insert returne false if error
  if($new_satellite->update_satellite()){

    echo("1#".$new_satellite->log);

  }else{

    echo("0#".$new_satellite->log);
  }

} else {
  view::load('vsat/settings/vsat_satellite','editvsat_satellite');
}


?>