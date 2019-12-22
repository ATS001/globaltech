<?php
if(MInit::form_verif('adddevis', false))
{

  $posted_data = array(

     'id_client'           => Mreq::tp('id_client') ,
     'tva'                 => Mreq::tp('tva') ,
     'tkn_frm'             => Mreq::tp('tkn_frm') ,
     'date_devis'          => Mreq::tp('date_devis') ,
     'type_remise'         => Mreq::tp('type_remise') ,
     'valeur_remise'       => Mreq::tp('valeur_remise') ,
     'totalht'             => Mreq::tp('totalht') ,
     'totalttc'            => Mreq::tp('totalttc') ,
     'totaltva'            => Mreq::tp('totaltva') ,
     'projet'              => Mreq::tp('projet'),
     'vie'                 => Mreq::tp('vie'),
     'claus_comercial'     => Mreq::tp('claus_comercial'),
     'id_commercial'       => json_encode(Mreq::tp('id_commercial')),
     'commission'          => Mreq::tp('commission'),
     'type_commission'     => Mreq::tp('type_commission'),
     'total_commission'    => Mreq::tp('total_commission'),
     'id_commercial_ex'    => Mreq::tp('id_commercial_ex'),
     'commission_ex'       => Mreq::tp('commission_ex'),
     'total_commission_ex' => Mreq::tp('total_commission_ex'),
     'type_commission_ex'  => Mreq::tp('type_commission_ex')

   );
 //var_dump($posted_data['id_commercial']);
 //var_dump("**************************");

  //Check if array have empty element return list
  //for acceptable empty field do not put here

    $checker = null;
    $empty_list = "Les champs suivants sont obligatoires:\n<ul>";
    if($posted_data['id_client'] == NULL){

      $empty_list .= "<li>Client</li>";
      $checker = 1;
    }
    if($posted_data['date_devis'] == NULL){

      $empty_list .= "<li>Date devis</li>";
      $checker = 1;
    }
    if($posted_data['type_remise'] == NULL OR !in_array($posted_data['type_remise'],  array( 'P','M' ))){

      $empty_list .= "<li>Type remise est incorrecte</li>";
      $checker = 1;
    }
    /*
    if($posted_data['type_commission'] == NULL OR !in_array($posted_data['type_commission'],  array( 'C','S' ))){

      $empty_list .= "<li>Type commission est incorrecte</li>";
      $checker = 1;
    }
    */
    if($posted_data['vie'] == NULL OR !in_array($posted_data['vie'],  array( '30','60', '90', '180', '365' ))){

      $empty_list .= "<li>Durée de validité</li>";
      $checker = 1;
    }
    if($posted_data['totalttc'] == NULL OR !is_numeric($posted_data['totalttc']) OR $posted_data['totalttc'] == 0){

      $empty_list .= "<li>Total TTC</li>";
      $checker = 1;
    }
    if($posted_data['valeur_remise'] == NULL OR !is_numeric($posted_data['valeur_remise'])){

      $empty_list .= "<li>Valeur Remise</li>";
      $checker = 1;
    }
    if($posted_data['totaltva'] == NULL OR !is_numeric($posted_data['totaltva'])){

      $empty_list .= "<li>Total TVA</li>";
      $checker = 1;
    }
    if($posted_data['totalht'] == NULL OR !is_numeric($posted_data['totalht']) OR $posted_data['totalht'] == 0){

      $empty_list .= "<li>Total HT</li>";
      $checker = 1;
    }
    if($posted_data['claus_comercial'] == NULL){

      $empty_list .= "<li>Clauses commerciales</li>";
      $checker = 1;
    }

    if($posted_data['id_commercial'] == NULL){

      $empty_list .= "<li>Commercial</li>";
      $checker = 1;
    }
    /*
    if($posted_data['commission'] == NULL OR !is_numeric($posted_data['commission']) ){

      $empty_list .= "<li>Commission</li>";
      $checker = 1;
    }
     * 
     */

    if($posted_data['commission_ex'] != NULL AND !is_numeric($posted_data['commission_ex']) ){

      $empty_list .= "<li>Commission Externe</li>";
      $checker = 1;
    }
    
    /*
    $set_comission = Msetting::get_set('plafond_comission');
    if($posted_data['commission'] > $set_comission  ){

      $empty_list .= "<li>Commission ne dois pas dépasser $set_comission</li>";
      $checker = 1;
    }
     * 
     */
    if($posted_data['total_commission'] == NULL OR !is_numeric($posted_data['total_commission']) ){

      $empty_list .= "<li>Total Commission</li>";
      $checker = 1;
    }

    if($posted_data['total_commission_ex'] != NULL AND !is_numeric($posted_data['total_commission_ex']) ){

      $empty_list .= "<li>Total Commission Externe</li>";
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


  $new_devis = new  Mdevis($posted_data);



  //execute Insert returne false if error
  if($new_devis->save_new_devis()){

    exit("1#".$new_devis->log);
  }else{

    exit("0#".$new_devis->log);
  }


} else {
  view::load_view('adddevis');
}






?>
