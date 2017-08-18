<?php

//Get all client info
 $uhf_vhf_clients_info= new Muhf_vhf_clients();
//Set ID of cllient with POST id
 $uhf_vhf_clients_info->id_uhf_vhf_clients = Mreq::tp('id');
//Check if Post ID <==> Post idc or get_prm return false. 
 if(!MInit::crypt_tp('id', null, 'D')  or !$uhf_vhf_clients_info->get_uhf_vhf_clients())
 { 	
 	//returne message error red to client 
 	exit('3#'.$uhf_vhf_clients_info->log .'<br>Les informations sont erronées contactez l\'administrateur');
 }
 
 
 $type_client=$uhf_vhf_clients_info->uhf_vhf_clients_info['type_client'];
 
switch ($type_client) {
    case "Fixe":
        view::load('uhf_vhf_stations', 'detailsuhf_vhf_clients_fixe');
        break;
    case "Mobile":
        view::load('uhf_vhf_stations', 'detailsuhf_vhf_clients_mobile');
        break;
    case "Handset":
        view::load('uhf_vhf_stations', 'detailsuhf_vhf_clients_handset');
        break;
}
 ?>