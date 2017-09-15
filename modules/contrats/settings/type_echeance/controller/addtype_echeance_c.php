<?php 
//SYS GLOBAL TECH
// Modul: type_echeance => Controller


defined('_MEXEC') or die;
if(MInit::form_verif('addtype_echeance',false))
{
	
  $posted_data = array(
   'type_echeance'            => Mreq::tp('type_echeance') ,
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
  
  

  //execute Insert returne false if error
  if($new_type_echeance->save_new_type_echeance()){

    echo("1#".$new_type_echeance->log);
  }else{

    echo("0#".$new_type_echeance->log);
  }


} else {
  view::load_view('addtype_echeance');
}
?>