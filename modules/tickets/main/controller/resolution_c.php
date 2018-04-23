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
        'decision' => Mreq::tp('decision')
         );
 
 
 $checker = null;
    $empty_list = "Les champs suivants sont obligatoires:\n<ul>";

    if ($posted_data["observation"] == NULL) {
        $empty_list .= "<li>Observation</li>";
        $checker = 1;
    }

    if ($posted_data["decision"] == NULL) {
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
   


if($tickets->resolution_ticket(2))
{
	exit("1#".$tickets->log);

}else{
	exit("0#".$tickets->log);
}


}