<?php 
//SYS GLOBAL TECH
// Modul: categorie_client => Controller
defined('_MEXEC') or die;
if(MInit::form_verif('editcategorie_client',false))
{
	//Check if id is been the correct id compared with idc
   if(!MInit::crypt_tp('id', null, 'D'))
   {  
   // returne message error red to client 
   exit('3#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
   }
  $posted_data = array(
   'id'                 => Mreq::tp('id') ,
   'categorie_client'   => Mreq::tp('categorie_client') 

   );

  
  //Check if array have empty element return list
  //for acceptable empty field do not put here
  

$checker = null;
    $empty_list = "Les champs suivants sont obligatoires:\n<ul>";
     if(!MInit::is_regex($posted_data['categorie_client'])){

      $empty_list .= "<li>Catégorie Client (a-z 1-9)</li>";
      $checker = 1;
    }

    if($posted_data['categorie_client'] == NULL){

      $empty_list .= "<li>Catégorie Client</li>";
      $checker = 1;
    }

    
    $empty_list.= "</ul>";
    if($checker == 1)
    {
      exit("0#$empty_list");
    }
    
  
 $new_categorie_client = new  Mcategorie_client($posted_data);
 $new_categorie_client->id_categorie_client = $posted_data['id']; 
  

  //execute Insert returne false if error
  if($new_categorie_client->edit_categorie_client()){

    echo("1#".$new_categorie_client->log);
  }else{

    echo("0#".$new_categorie_client->log);
  }


} else {
  view::load('clients/settings/categorie_client','editcategorie_client');
}
?>