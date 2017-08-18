
<?php
defined('_MEXEC') or die;
if(MInit::form_verif('addanonyme', false))
{
  
  $posted_data = array(
   'titre'            => Mreq::tp('titre') ,
   'longi'      	  => Mreq::tp('longi') ,
   'latit'            => Mreq::tp('latit'),
   'technologie'      => Mreq::tp('technologie'),
   'remarque'         => Mreq::tp('remarque'),
   'date_visite'      => Mreq::tp('date_visite'),
   'photo_id'          => Mreq::tp('photo_id') ,//Array
   'photo_titl'        => Mreq::tp('photo_titl') ,//Array
  );

  
  //Check if array have empty element return list
  //for acceptable empty field do not put here
  
  

    $checker = null;
    $empty_list = "Les champs suivants sont obligatoires:\n<ul>";
    if($posted_data['titre'] == NULL){

      $empty_list .= "<li>Titre</li>";
      $checker = 1;
    }
    if($posted_data['longi'] == NULL){

      $empty_list .= "<li>Longitude</li>";
      $checker = 1;
    }    
     if($posted_data['latit'] == NULL){

      $empty_list .= "<li>Latitude</li>";
      $checker = 1;
    }
    if($posted_data['technologie'] == NULL){

      $empty_list .= "<li>Technologie</li>";
      $checker = 1;
    }
    if($posted_data['remarque'] == NULL){

      $empty_list .= "<li>Remarque</li>";
      $checker = 1;
    }
    if($posted_data['date_visite'] == NULL){

      $empty_list .= "<li>Date de visite</li>";
      $checker = 1;
    }
  
    
    $empty_list.= "</ul>";
    if($checker == 1)
    {
      exit("0#$empty_list");
    }


  $new_anonyme = new  Manonyme($posted_data);
  
  

  //execute Insert returne false if error
  if($new_anonyme->save_new_anonyme()){

    echo("1#".$new_anonyme->log);
    

  }else{

    echo("0#".$new_anonyme->log);
    
  }

} else {
  view::load('anonymes','addanonyme');
}

