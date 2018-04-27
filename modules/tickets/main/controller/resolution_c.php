<?php 

//First check target no Hack
if (!defined('_MEXEC'))
    die();
//SYS GLOBAL TECH
// Modul: tickets
//Created : 06-04-2018
//Controller ADD Form
if (MInit::form_verif('resolution', false)) {
    
 $posted_data = array(    
        'id' => Mreq::tp('id'),
        'observation' => Mreq::tp('observation'),
        'code_cloture' => Mreq::tp('code_cloture')
         );
 
 
 $checker = null;
    $empty_list = "Les champs suivants sont obligatoires:\n<ul>";

    if ($posted_data["observation"] == NULL) {
        $empty_list .= "<li>Observation</li>";
        $checker = 1;
    }

    if ($posted_data["code_cloture"] == NULL) {
        $empty_list .= "<li>Décision</li>";
        $checker = 1;
    }

    $empty_list .= "</ul>";
    if ($checker == 1) {
        exit("0#$empty_list");
    }

    //End check empty element
    $tickets = new Mtickets($posted_data);
    $tickets->id_tickets=$posted_data["id"];
   
    
//Etat for validate row
$etat = Msetting::get_set('etat_ticket', 'resolution_termine');


if($tickets->resolution_ticket($etat))
{
	exit("1#".$tickets->log);

}else{
	exit("0#".$tickets->log);
}


}