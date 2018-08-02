<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: fournisseurs
//Created : 19-07-2018
//Controller ADD Form
if(MInit::form_verif('bloquerfournisseur', false))
{
    //Check if id is been the correct id compared with idc
    if(!MInit::crypt_tp('id', null, 'D'))
    {  
    // returne message error red to fournisseur 
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
	  $new_fournisseur = new  Mfournisseurs($posted_data);
        $new_fournisseur->id_fournisseur = $posted_data['id']; 

  //execute Insert returne false if error
  if($new_fournisseur->bloquer_fournisseur()){

    echo("1#".$new_fournisseur->log);
  }else{

    echo("0#".$new_fournisseur->log);
  }


}

//No form posted show view
view::load_view('bloquerfournisseur');
?>