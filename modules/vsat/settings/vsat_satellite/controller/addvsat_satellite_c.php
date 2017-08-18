
<?php
defined('_MEXEC') or die;
//if(MInit::form_verif(false))
if(MInit::form_verif('addvsat_satellite', false))
{
  
  $posted_data = array(
   'satellite'                    => Mreq::tp('satellite') ,
   'position_orbitale'      => Mreq::tp('position_orbitale') ,
   'pay_operator'            => Mreq::tp('pay_operator'),
   'contractor'                => Mreq::tp('contractor')  
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


  $new_vsat_satellite = new  Mvsat_satellite($posted_data);
  
  

  //execute Insert returne false if error
  if($new_vsat_satellite->save_new_vsat_satellite()){

    echo("1#".$new_vsat_satellite->log);
    

  }else{

    echo("0#".$new_vsat_satellite->log);
    
  }

} else {
  view::load('vsat/settings/vsat_satellite','addvsat_satellite');
}

