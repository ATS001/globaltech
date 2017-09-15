<?php 
//SYS GLOBAL TECH
// Modul: type_echeance => Controller
defined('_MEXEC') or die;
if(MInit::form_verif('edittype_echeance',false))
{
	//Check if id is been the correct id compared with idc
   if(!MInit::crypt_tp('id', null, 'D'))
   {  
   // returne message error red to client 
   exit('3#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
   }
  $posted_data = array(
   'id'                 => Mreq::tp('id') ,
   'type_echeance'      => Mreq::tp('type_echeance') ,
  
   );

  
  //Check if array have empty element return list
  //for acceptable empty field do not put here
  
  
$checker = null;
    $empty_list = "Les champs suivants sont obligatoires:\n<ul>";
     if(!MInit::is_regex($posted_data['type_echeance'])){

      $empty_list .= "<li>Type échéance non valide (a-z 1-9)</li>";
      $checker = 1;
    }

    if($posted_data['type_echeance'] == NULL){

      $empty_list .= "<li>Type échéance</li>";
      $checker = 1;
    }
    
    $empty_list.= "</ul>";
    if($checker == 1)
    {
      exit("0#$empty_list");
    }
    
  
  //End check empty element

  $new_type_echeance = new  Mtype_echeance($posted_data);
  $new_type_echeance->id_type_echeance = $posted_data['id'];

  //execute Insert returne false if error
  if($new_type_echeance->edit_type_echeance())
  {
    echo("1#".addslashes($new_type_echeance->log));
  }else{
    echo("0#".addslashes($new_type_echeance->log));
  }


}else{
  	
    view::load_view('edittype_echeance');
}
?>