<?php
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: sites
//Created : 17-02-2019
//Controller ADD Form
if(MInit::form_verif('addsites', false))
{

        $posted_data = array(
            'type_site' => Mreq::tp('type_site'),
            'id_client' => Mreq::tp('id_client'),
            'date_mes' => Mreq::tp('date_mes'),
            'basestation' => Mreq::tp('basestation'),
            'secteur' => Mreq::tp('secteur'),
            'antenne' => Mreq::tp('antenne'),
            'modem' => Mreq::tp('modem'),
            'sn_modem' => Mreq::tp('sn_modem'),
            'bande' => Mreq::tp('bande'),
            'satellite' => Mreq::tp('satellite'),
            'lnb' => Mreq::tp('lnb'),
            'buc' => Mreq::tp('buc'),
            'photo_id'           => Mreq::tp('photo-id'),
            'addr_mac_radio' => Mreq::tp('addr_mac_radio'),
            'addr_mac_router' => Mreq::tp('addr_mac_router'),
            'cable' => Mreq::tp('cable'),
            'routeur' => Mreq::tp('routeur'),
            'radio' => Mreq::tp('radio'),
            'site_name' => Mreq::tp('site_name')
        );

        //Check if array have empty element return list
        //for acceptable empty field do not put here

        $checker = null;
        $empty_list = "Les champs suivants sont obligatoires:\n<ul>";

        if ($posted_data['site_name'] == NULL) {

         $empty_list .= "<li>Nom du site</li>";
         $checker = 1;
        }


           if ($posted_data['id_client'] == NULL) {

            $empty_list .= "<li>Client</li>";
            $checker = 1;
        }
           if ($posted_data['date_mes'] == NULL) {

            $empty_list .= "<li>Date mise en service</li>";
            $checker = 1;
        }

         if($posted_data['type_site'] == 'RADIO'){
             if ($posted_data['basestation'] == NULL) {
                $empty_list .= "<li>Station de base</li>";
                $checker = 1;
                 }

                 if ($posted_data['secteur'] == NULL) {
                $empty_list .= "<li>Secteur</li>";
                $checker = 1;
                 }
         }


//Check if array have empty element return list
        //for acceptable empty field do not put here
		$checker = null;
		$empty_list = "Les champs suivants sont obligatoires:\n<ul>";


		$empty_list.= "</ul>";
		if($checker == 1)
		{
			exit("0#$empty_list");
		}

       //End check empty element
		$new_sites = new  Msites($posted_data);


        //execute Insert returne false if error
		if($new_sites->save_new_sites()){

			exit("1#".$new_sites->log);
		}else{

			exit("0#".$new_sites->log);
		}


}

//No form posted show view
view::load_view('addsites');







	?>
