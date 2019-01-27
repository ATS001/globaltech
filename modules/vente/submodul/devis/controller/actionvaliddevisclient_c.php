<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: devis
//Created : 09-10-2017
//Controller
if(MInit::form_verif('add_temp_achat', false))
{
    $posted_data = array(
        'id_produit' => Mreq::tp('id_produit'), 
        'qte_exist'  => Mreq::tp('qte_exist'),  
        'need'       => Mreq::tp('need'),   
        'qte'        => Mreq::tp('qte'),
    );

            //Check if array have empty element return list
            //for acceptable empty field do not put here

    $checker = null;
    $empty_list = "Les champs suivants sont obligatoires:\n<ul>";

    $empty_list_prix = "** Attention ** \n<ul>";


    if($posted_data['qte'] == NULL OR !is_numeric($posted_data['qte'])){

        $empty_list .= "<li>Quantité invalide</li>";
        $checker = 1;
    }

    if($posted_data['qte'] < $posted_data['need']){

        $empty_list .= "<li>la Quantité ne couvre pas le besoin</li>";
        $checker = 1;
    }

    $empty_list .= "</ul>";
    if ($checker == 1) {
        echo json_encode(array('error' => false, 'mess' =>$empty_list));
        exit();
    }

    $new_achat = new Machat($posted_data);
    //execute Insert returne false if error
    if (!$new_achat->save_temp_new_achat_produit($posted_data['id_produit']))
    {
        echo json_encode(array('error' => false, 'mess' => 'Erreur Enregistrement Client Temporaire'.$new_achat->log ));
        exit();
    } else {
        echo json_encode(array('new_stok' => $posted_data['qte'] + $posted_data['qte_exist']));
        exit();
    }

}
//No form posted show view
view::load_view('add_temp_achat');