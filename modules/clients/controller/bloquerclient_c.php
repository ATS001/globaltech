<?php 
//SYS GLOBAL TECH
// Modul: clients => Controller

defined('_MEXEC') or die;
if(MInit::form_verif('bloquerclient',false))
{
	//Check if id is been the correct id compared with idc
   if(!MInit::crypt_tp('id', null, 'D'))
   {  
   // returne message error red to client 
   exit('3#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
   }

  $posted_data = array(
   'id'                => Mreq::tp('id') ,
   'id_motif_blocage'  => Mreq::tp('id_motif_blocage') ,
   'commentaire'       => Mreq::tp('commentaire')
   );

  
  //Check if array have empty element return list
  //for acceptable empty field do not put here
  
  
  $checker = null;
  $empty_list = "Les champs suivants sont obligatoires:\n<ul>";

  /*if($posted_data['code'] == NULL){

    $empty_list .= "<li>Code Client</li>";
    $checker = 1;
  }*/

  if($posted_data['id_motif_blocage'] == NULL){

    $empty_list .= "<li>Motif de Blocage</li>";
    $checker = 1;
  }

  $empty_list.= "</ul>";
  if($checker == 1)
  {
    exit("0#$empty_list");
  }

  //End check empty element

  $new_client = new  Mclients($posted_data);
  $new_client->id_client = $posted_data['id']; 

  //execute Insert returne false if error
  if($new_client->bloquer_client()){

    echo("1#".$new_client->log);
  }else{

    echo("0#".$new_client->log);
  }


} else {
view::load_view('bloquerclient');
}
?>
