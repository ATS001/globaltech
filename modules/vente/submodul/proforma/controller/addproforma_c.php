<?php
if(MInit::form_verif('addproforma', false))
{
	
  $posted_data = array(
   
   'id_client'     => Mreq::tp('id_client') ,
   'tva'           => Mreq::tp('tva') ,
   'tkn_frm'       => Mreq::tp('tkn_frm') ,
   'vie'           => Mreq::tp('vie') ,
   'date_proforma' => Mreq::tp('date_proforma') ,
/* 'type_remise'     => Mreq::tp('type_remise') ,
   'valeur_remise'   => Mreq::tp('remise_montant') ,
   'totalht'         => Mreq::tp('totalht') ,
   'totalttc'        => Mreq::tp('totalttc') ,
   'totaltva'        => Mreq::tp('totaltva') ,*/
   'claus_comercial' => Mreq::tp('claus_comercial')

   );

  
  //Check if array have empty element return list
  //for acceptable empty field do not put here  

    $checker = null;
    $empty_list = "Les champs suivants sont obligatoires:\n<ul>";
    if($posted_data['id_client'] == NULL){

      $empty_list .= "<li>Client</li>";
      $checker = 1;
    }
    if($posted_data['date_proforma'] == NULL){

      $empty_list .= "<li>Date proforma</li>";
      $checker = 1;
    }
    if($posted_data['tva'] == NULL OR !in_array($posted_data['tva'],  array( 'O','N' ))){

      $empty_list .= "<li>Type remise est incorrecte</li>";
      $checker = 1;
    }
    if($posted_data['vie'] == NULL OR !in_array($posted_data['vie'],  array( '30','60', '90' ))){

      $empty_list .= "<li>Durée de validité</li>";
      $checker = 1;
    }
/*    if($posted_data['type_remise'] == NULL OR !in_array($posted_data['type_remise'],  array( 'P','M' ))){

      $empty_list .= "<li>Type remise est incorrecte</li>";
      $checker = 1;
    }
    if($posted_data['totalttc'] == NULL){

      $empty_list .= "<li>Total TTC</li>";
      $checker = 1;
    }
    if($posted_data['totaltva'] == NULL){

      $empty_list .= "<li>Total TVA</li>";
      $checker = 1;
    }
    if($posted_data['totalht'] == NULL){

      $empty_list .= "<li>Total HT</li>";
      $checker = 1;
    }*/
    if($posted_data['claus_comercial'] == NULL){

      $empty_list .= "<li>Clauses commerciales</li>";
      $checker = 1;
    }
    /*if($posted_data['service'] == NULL){

      $empty_list .= "<li>Service</li>";
      $checker = 1;
    }
    */
    
    $empty_list.= "</ul>";
    if($checker == 1)
    {
      exit("0#$empty_list");
    }

    
  
  //End check empty element


  $new_proforma = new  Mproforma($posted_data);
  
  

  //execute Insert returne false if error
  if($new_proforma->save_new_proforma()){

    exit("1#".$new_proforma->log);
  }else{

    exit("0#".$new_proforma->log);
  }


} else {
  view::load_view('addproforma');
}






?>