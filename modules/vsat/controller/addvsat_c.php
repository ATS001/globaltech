<?php
if(MInit::form_verif('addvsat', false))
{

  $posted_data = array(
    'id_perm'           => Mreq::tp('id_perm') ,
    'nom_station'       => Mreq::tp('nom_station') ,
    'ville'             => Mreq::tp('ville') ,
    'longi'             => Mreq::tp('longi') ,
    'latit'             => Mreq::tp('latit') ,
    'arch_reseau'       => Mreq::tp('arch_reseau') ,
    'bande_freq'        => Mreq::tp('bande_freq') ,
    'utilisation'       => Mreq::tp('utilisation') ,
    'pay_materiel'      => Mreq::tp('pay_materiel') ,
    'dat_entr_materiel' => Mreq::tp('dat_entr_materiel') ,
    'diametre_antenne'  => Mreq::tp('diametre_antenne') ,
    'marque_antenne'    => Mreq::tp('marque_antenne') ,
    'azimut'            => Mreq::tp('azimut') ,
    'tilt'              => Mreq::tp('tilt') ,
    'polarisation'      => Mreq::tp('polarisation') ,
    'marque_radio'      => Mreq::tp('marque_radio') ,
    'ns_radio'          => Mreq::tp('ns_radio') ,
    'tx_freq'           => Mreq::tp('tx_freq') ,
    'marque_lnb'        => Mreq::tp('marque_lnb') ,
    'ns_lnb'            => Mreq::tp('ns_lnb') ,
    'rx_freq'           => Mreq::tp('rx_freq') ,
    'marque_modem'      => Mreq::tp('marque_modem') ,
    'ns_modem'          => Mreq::tp('ns_modem') ,
    'ip'                => Mreq::tp('ip') ,
    'debit_download'    => Mreq::tp('debit_download') ,
    'debit_upload'      => Mreq::tp('debit_upload') ,
    'cout_mensuel'      => Mreq::tp('cout_mensuel') ,
    'satellite'         => Mreq::tp('satellite') ,
    'hub'               => Mreq::tp('hub') ,
    'revendeur'         => Mreq::tp('revendeur') ,
    'installateur'      => Mreq::tp('installateur') ,
    'last_visite'       => Mreq::tp('last_visite') ,
    'remarq'            => Mreq::tp('remarq') ,
    'pj_id'             => Mreq::tp('pj-id') ,
    'photo_id'          => Mreq::tp('photo_id') ,//Array
    'photo_titl'        => Mreq::tp('photo_titl') ,//Array
    );





  //Check if array have empty element return list
  //for acceptable empty field do not put here
  $checker = null;
  $empty_list = "Les champs suivants sont obligatoires:\n<ul>";

  if($posted_data['id_perm'] == null OR !MInit::exist_select('permissionnaires', $posted_data['id_perm'])){
    $empty_list .='<li>Selectionnez un permissionnaire valide';
    $checker = 1;
  }
  if($posted_data['nom_station'] == null){
    $empty_list .='<li>Insérez nom de la station ';
    $checker = 1;
  }
  if($posted_data['ville'] == null OR !MInit::exist_select('ref_ville', $posted_data['ville'])){
    $empty_list .='<li>Selectionnez une ville valide';
    $checker = 1;
  }
  if($posted_data['longi'] == null){
    $empty_list .='<li>Insérez valeur de longitude';
    $checker = 1;
  }
  if($posted_data['latit'] == null){
    $empty_list .='<li>Insérez valeur Latitude';
    $checker = 1;
  }
  if($posted_data['arch_reseau'] == null OR !in_array($posted_data['arch_reseau'], array('E','M'))){
    $empty_list .='<li>Selectionnez architecture valide';
    $checker = 1;
  }
  if($posted_data['bande_freq'] == null OR !in_array($posted_data['bande_freq'], array('C','KU', 'KA'))){
    $empty_list .='<li>Insérez la Bande de fréquence';
    $checker = 1;
  }
  if($posted_data['utilisation'] == null OR !in_array($posted_data['utilisation'], array('Internet','backhaul'))){
    $empty_list .='<li>Selectionnez l\'utilisation';
    $checker = 1;
  }
  if($posted_data['pay_materiel'] == null OR !MInit::exist_select('ref_pays', $posted_data['pay_materiel'])){
    $empty_list .='<li>';
    $checker = 1;
  }
  if($posted_data['dat_entr_materiel'] == null OR MInit::check_date($posted_data['dat_entr_materiel'],'P')){
    $empty_list .='<li>Date d\'entrée non valide '.$posted_data['dat_entr_materiel'];
    $checker = 1;
  }
  if($posted_data['diametre_antenne'] == null OR !is_numeric($posted_data['diametre_antenne'])){
    $empty_list .='<li>Diamétre non valide';
    $checker = 1;
  }
  if($posted_data['azimut'] == null OR !is_numeric($posted_data['azimut']) OR $posted_data['azimut'] > 360 ){
    $empty_list .='<li>Azimuth non valide';
    $checker = 1;
  }
  if($posted_data['tilt'] == null OR !is_numeric($posted_data['tilt']) OR $posted_data['tilt'] > 90){
    $empty_list .='<li>Inclaison non valide';
    $checker = 1;
  }
  if($posted_data['polarisation'] == null){
    $empty_list .='<li>Polarisation non valide';
    $checker = 1;
  }
  if($posted_data['marque_radio'] == null){
    $empty_list .='<li>Marque non valide';
    $checker = 1;
  }
  if($posted_data['ns_radio'] == null){
    $empty_list .='<li>NS Radio non valide';
    $checker = 1;
  }
  if($posted_data['tx_freq'] == null OR !is_numeric($posted_data['tx_freq'])){
    $empty_list .='<li>Fréquence TX non valide';
    $checker = 1;
  }
  if($posted_data['marque_lnb'] == null){
    $empty_list .='<li>Marque non valide';
    $checker = 1;
  }
  if($posted_data['ns_lnb'] == null){
    $empty_list .='<li>NS LNB non valide';
    $checker = 1;
  }
  if($posted_data['rx_freq'] == null OR !is_numeric($posted_data['rx_freq'])){
    $empty_list .='<li>Fréquence RX non valide';
    $checker = 1;
  }
  if($posted_data['marque_modem'] == null){
    $empty_list .='<li>Marque modem non valide';
    $checker = 1;
  }
  if($posted_data['ns_modem'] == null){
    $empty_list .='<li>NS Modem non valide';
    $checker = 1;
  }
  if($posted_data['ip'] == null OR !filter_var($posted_data['ip'], FILTER_VALIDATE_IP)){
    $empty_list .='<li>IP non valide';
    $checker = 1;
  }
  if($posted_data['debit_download'] == null OR !is_numeric($posted_data['debit_download'])){
    $empty_list .='<li>Valeur Débit descendant non valide';
    $checker = 1;
  }
  if($posted_data['debit_upload'] == null OR !is_numeric($posted_data['debit_upload'])){
    $empty_list .='<li>Valeur Débit montant non valide';
    $checker = 1;
  }
  if(!is_numeric($posted_data['cout_mensuel'])){
    $empty_list .='<li>Valeur Coût mensuel non valide';
    $checker = 1;
  }
  if($posted_data['satellite'] == null OR !MInit::exist_select('vsat_satellite', $posted_data['satellite'])){
    $empty_list .='<li>Selectionnez un satellite';
    $checker = 1;
  }
  if($posted_data['hub'] == null OR !MInit::exist_select('vsat_hub', $posted_data['hub'])){
    $empty_list .='<li>Selectionnez un HUB';
    $checker = 1;
  }
  /*if($posted_data['revendeur'] == null OR !MInit::exist_select('revendeurs', $posted_data['revendeur'])){
    $empty_list .='<li>Selectionnez un Revendeur';
    $checker = 1;
  }*/
  /*if($posted_data['installateur'] == null OR !MInit::exist_select('installateur', $posted_data['installateur'])){
    $empty_list .='<li>Selectionnez un Installateur';
    $checker = 1;
  }*/
  if($posted_data['last_visite'] == null OR MInit::check_date($posted_data['last_visite'],'P')){
    $empty_list .='<li>Date de dernière visite non valide';
    $checker = 1;
  }
  if($posted_data['pj_id'] == null){
    $empty_list .='<li>Ajoutez la fiche technique scannée';
    $checker = 1;
  }
  
  //Check if have error return Red message  
  $empty_list.= "</ul>";
  if($checker == 1)
  {
    exit("0#$empty_list");
  }


  
  //End check empty element

  $new_vsat = new  Mvsat($posted_data);
  //Exige PJ formulaire
  $new_vsat->exige_pj     = true;
  //execute Insert returne false if error
  if($new_vsat->save_new_vsat()){
    exit("1#".$new_vsat->log);//Return green message
  }else{
    exit("0#".$new_vsat->log);//return Red Message
  }
} else {
  //If no form posted call ADD VSAT form view
  view::load('vsat','addvsat');
}






?>