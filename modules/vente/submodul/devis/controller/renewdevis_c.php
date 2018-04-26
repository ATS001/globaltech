<<<<<<< HEAD
<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: devis
//Created : 10-10-2017
//Controller EXEC Form
$devis = new Mdevis();
$devis->id_devis = Mreq::tp('id');


if(!MInit::crypt_tp('id', null, 'D')or !$devis->get_devis())
{  
   // returne message error red to devis 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}


//Etat for validate row
//$etat = $devis->devis_info['etat'];
//$devis->debloqdevis($etat)
//Execute Validate - delete


if($devis->duplicate_devis())
{
	
	exit("1#".$devis->log);

}else{
	exit("0#".$devis->log);
}
=======
<?php
if(MInit::form_verif('renewdevis', false))
{
  if(!MInit::crypt_tp('id', null, 'D'))
  {  
   // returne message error red to client 
    exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
  }
  
  $posted_data = array(

    'id'                => Mreq::tp('id') ,
    'id_client'         => Mreq::tp('id_client') ,
    'tva'               => Mreq::tp('tva') ,
    'tkn_frm'           => Mreq::tp('tkn_frm') ,
    'reference'         => Mreq::tp('reference') ,
    'checker_reference' => Mreq::tp('checker_reference') ,
    'date_devis'        => Mreq::tp('date_devis') ,
    'type_remise'       => Mreq::tp('type_remise') ,
    'valeur_remise'     => Mreq::tp('valeur_remise') ,
    'totalht'           => Mreq::tp('totalht') ,
    'totalttc'          => Mreq::tp('totalttc') ,
    'totaltva'          => Mreq::tp('totaltva') ,
    'projet'            => Mreq::tp('projet'),
    'vie'               => Mreq::tp('vie'),
    'claus_comercial'   => Mreq::tp('claus_comercial'),
    'id_commercial'     => Mreq::tp('id_commercial'),
    'commission'        => Mreq::tp('commission'),
    'total_commission'  => Mreq::tp('total_commission')

    );


  //Check if array have empty element return list 
  //for acceptable empty field do not put here  

  $checker = null;
  $empty_list = "Les champs suivants sont obligatoires:\n<ul>";
  if($posted_data['checker_reference'] != MInit::cryptage($posted_data['reference'],1)){

    $empty_list .= "<li>La réference est invalide</li>";
    $checker = 1;
  }
  if($posted_data['id_client'] == NULL){

    $empty_list .= "<li>Client</li>";
    $checker = 1;
  }
  if($posted_data['date_devis'] == NULL){

    $empty_list .= "<li>Date devis</li>";
    $checker = 1;
  }
  if($posted_data['vie'] == NULL OR !in_array($posted_data['vie'],  array( '30','60', '90' ))){

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
    if($posted_data['commission'] == NULL OR !is_numeric($posted_data['commission']) ){

      $empty_list .= "<li>Commission</li>";
      $checker = 1;
    }
    if($posted_data['total_commission'] == NULL OR !is_numeric($posted_data['total_commission']) ){

      $empty_list .= "<li>Total Commission</li>";
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


    $exist_devis = new  Mdevis($posted_data);
    $exist_devis->id_devis = $posted_data['id'];


  //execute Insert returne false if error
    if($exist_devis->edit_exist_devis()){

      exit("1#".$exist_devis->log);
    }else{

      exit("0#".$exist_devis->log);
    }


} else {
  view::load_view('renewdevis');
}






?>
>>>>>>> 67b4751f204c53744dc6a4d1616f7ff691bdb722
