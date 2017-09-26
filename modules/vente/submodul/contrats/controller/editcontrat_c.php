<?php

//SYS GLOBAL TECH
// Modul: contrats => Controller

defined('_MEXEC') or die;
if (MInit::form_verif('editcontrat', false)) {
    //Check if id is been the correct id compared with idc
    if (!MInit::crypt_tp('id', null, 'D')) {
        // returne message error red to client 
        exit('3#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
    }
    $posted_data = array(
        'id' => Mreq::tp('id'),
        'ref' => Mreq::tp('ref'),
        'iddevis' => Mreq::tp('iddevis'),
        'idtype_echeance' => Mreq::tp('idtype_echeance'),
        'tkn_frm'           => Mreq::tp('tkn_frm') ,
        'date_effet' => Mreq::tp('date_effet'),
        'date_fin' => Mreq::tp('date_fin'),
        'commentaire' => Mreq::tp('commentaire'),
        'periode_fact'=> Mreq::tp('periode_fact'),
        'pj_id' => Mreq::tp('pj-id'),
        'pj_photo_id' => Mreq::tp('pj_photo-id')
    );


    //Check if array have empty element return list
    //for acceptable empty field do not put here


    $checker = null;
    $empty_list = "Les champs suivants sont obligatoires:\n<ul>";

    /*if ($posted_data['ref'] == NULL) {

        $empty_list .= "<li>Réference</li>";
        $checker = 1;
    }*/

    if ($posted_data['iddevis'] == NULL) {

        $empty_list .= "<li>Devis</li>";
        $checker = 1;
    }

    if ($posted_data['date_effet'] == NULL) {

        $empty_list .= "<li>Date d'effet</li>";
        $checker = 1;
    }

    if ($posted_data['date_fin'] == NULL) {

        $empty_list .= "<li>Date fin</li>";
        $checker = 1;
    }
//    if ($posted_data['commentaire'] == NULL) {
//
//        $empty_list .= "<li>Commentaire</li>";
//        $checker = 1;
//    }
    
    if ($posted_data['idtype_echeance'] == NULL) {

        $empty_list .= "<li>Type échéance</li>";
        $checker = 1;
    }

    if ($posted_data['periode_fact'] == NULL) {

        $empty_list .= "<li>Période de facturation</li>";
        $checker = 1;
    }




    $empty_list .= "</ul>";
    if ($checker == 1) {
        exit("0#$empty_list");
    }

    //End check empty element


    $contrat = new Mcontrat($posted_data);
    $contrat->id_contrat = $posted_data['id'];

    $contrat->exige_pj = FALSE;
    $contrat->exige_pj_photo = FALSE;

    //execute Insert returne false if error
    if ($contrat->edit_contrat()) {

        echo("1#" . $contrat->log);
    } else {

        echo("0#" . $contrat->log);
    }
} else {
    view::load_view('editcontrat');
}
?>