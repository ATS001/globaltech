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
        'reference' => Mreq::tp('reference'),
        'iddevis' => Mreq::tp('iddevis'),
        'idtype_echeance' => Mreq::tp('idtype_echeance'),
        'tkn_frm'           => Mreq::tp('tkn_frm') ,
        'date_effet' => Mreq::tp('date_effet'),
        'date_fin' => Mreq::tp('date_fin'),
        'commentaire' => Mreq::tp('commentaire'),
        'periode_fact'=> Mreq::tp('periode_fact'),
        'date_notif'  => Mreq::tp('date_notif') ,
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

    if($posted_data['date_notif'] == NULL){

        $empty_list .= "<li>Date de notification</li>";
        $checker = 1;
    }

    $empty_list .= "</ul>";
    if ($checker == 1) {
        exit("0#$empty_list");
    }

    if(date('Y-m-d', strtotime($posted_data['date_fin'])) <= date('Y-m-d', strtotime($posted_data['date_effet'])) ){

         $control_date = "<ul>La date de fin doit être supérieur de la date d'effet !!!</ul>";
         $checker = 2;
    }

    if($checker == 2)
    {
         exit("0#$control_date");
    }

    if(date('Y-m-d', strtotime($posted_data['date_notif'])) >= date('Y-m-d', strtotime($posted_data['date_fin']))  or date('Y-m-d', strtotime($posted_data['date_notif'])) <= date('Y-m-d', strtotime($posted_data['date_effet'])) ){

        $control_notif = "<ul>La date de notification doit être supérieur de la date d'effet et  inférieur de la date de fin !!!</ul>" ;
        $checker = 3;
    }

    if($checker == 3)
    {
        exit("0#$control_notif");
    }

    $date1=date_create(date('Y-m-d', strtotime($posted_data['date_effet'])));
    $date2=date_create(date('Y-m-d', strtotime($posted_data['date_fin'])));
    $diff=date_diff($date1,$date2);

 /*   var_dump($date1);
    var_dump($date2);
   

var_dump( $diff->format('%a'));
exit();*/
    $contratA = new Mcontrat($posted_data);
    $contratA->get_id_type_echeance('Annuelle');

    $contratM = new Mcontrat($posted_data);
    $contratM->get_id_type_echeance('Mensuelle');

    $contratT = new Mcontrat($posted_data);
    $contratT->get_id_type_echeance('Trimestrielle');

    $contratS = new Mcontrat($posted_data);
    $contratS->get_id_type_echeance('Simestrielle');

/*var_dump( $diff->format('%a'));   
var_dump($contratA->Shw_type('id',1));
var_dump($contratM->Shw_type('id',1));
var_dump($contratT->Shw_type('id',1));
var_dump($contratS->Shw_type('id',1));
var_dump($posted_data['idtype_echeance']);
exit();*/


     if ( /*($diff->format('%a') % 365) <>0 and*/ $posted_data['idtype_echeance'] == $contratA->Shw_type('id',1))
    {
        $date_d = $posted_data['date_effet'];
       $date_f = $posted_data['date_fin'];
       $output = [];
       $output_an = [];
       $total_jr=0;
       $time   = strtotime($date_d);
       $last   = date('d-m-Y', strtotime($date_f));
       $res=0;


       do {
        $month = date('d-m-Y', $time);
        $mon   = date('m', $time);
        $total = date('t', $time);
        $total_jr += $total ;
        $annee= date('L', $time);

        $output[] = [
        'month' => $month,
        'total' => $total,
        'tot_jr'=> $total_jr,
        'annee' => $annee,
        'mo'=>$mon,
        'time'=>$time,

        ];

        $time = strtotime('+1 year', $time);


            } while (date('Y-m-d', strtotime($month)) < date('Y-m-d', strtotime($last)));

        if(date('Y-m-d', strtotime($month)) <> date('Y-m-d', strtotime($last)))
        {  
            $control_echeance_a = "<ul>Il faut choisir une période Annuelle, ou séléctionner le type d'échéance : Autres  !!!</ul>";
            $checker = 4;
        }
    }
    if($checker == 4)
    {
        exit("0#$control_echeance_a");
    }
//return 12 * $nbyear + $nbmonth;
    if ( /*($diff->format('%a') % 28) <>0  and */$posted_data['idtype_echeance'] == $contratM->Shw_type('id',1))
    {
       $date_d = $posted_data['date_effet'];
       $date_f = $posted_data['date_fin'];
       $output = [];
       $output_an = [];
       $total_jr=0;
       $time   = strtotime($date_d);
       $last   = date('d-m-Y', strtotime($date_f));
       $res=0;


       do {
        $month = date('d-m-Y', $time);
        $mon   = date('m', $time);
        $total = date('t', $time);
        $total_jr += $total ;
        $annee= date('L', $time);

        $output[] = [
        'month' => $month,
        'total' => $total,
        'tot_jr'=> $total_jr,
        'annee' => $annee,
        'mo'=>$mon,
        'time'=>$time,

        ];

        $time = strtotime('+1 month', $time);


           } while (date('Y-m-d', strtotime($month)) < date('Y-m-d', strtotime($last)));

        if(date('Y-m-d', strtotime($month)) <> date('Y-m-d', strtotime($last)))
        {
            $control_echeance_m = "<ul>Il faut choisir une période Mensuelle, ou séléctionner le type d'échéance : Autres   !!!</ul>";
            $checker = 5;
        }

    //var_dump($output);
       
    }
    if($checker == 5)
    {
        exit("0#$control_echeance_m");
    }

    if (/*$diff->format('%a') < 88 and*/ $posted_data['idtype_echeance'] == $contratT->Shw_type('id',1))
    {
       $date_d = $posted_data['date_effet'];
       $date_f = $posted_data['date_fin'];
       $output = [];
       $output_an = [];
       $total_jr=0;
       $time   = strtotime($date_d);
       $last   = date('d-m-Y', strtotime($date_f));
       $res=0;


       do {
        $month = date('d-m-Y', $time);
        $mon   = date('m', $time);
        $total = date('t', $time);
        $total_jr += $total ;
        $annee= date('L', $time);

        $output[] = [
        'month' => $month,
        'total' => $total,
        'tot_jr'=> $total_jr,
        'annee' => $annee,
        'mo'=>$mon,
        'time'=>$time,

        ];

        $time = strtotime('+3 month', $time);


           } while (date('Y-m-d', strtotime($month)) < date('Y-m-d', strtotime($last)));

        if(date('Y-m-d', strtotime($month)) <> date('Y-m-d', strtotime($last)))
        {
            $control_echeance_t = "<ul>Il faut choisir une période Trimestrielle, ou séléctionner le type d'échéance : Autres   !!!</ul>";
            $checker = 6;
        }

    //var_dump($output);
       
    }
    if($checker == 6)
    {
        exit("0#$control_echeance_t");
    }

    if (/*$diff->format('%a') < 178 and */$posted_data['idtype_echeance'] == $contratS->Shw_type('id',1))
    {   
       $date_d = $posted_data['date_effet'];
       $date_f = $posted_data['date_fin'];
       $output = [];
       $output_an = [];
       $total_jr=0;
       $time   = strtotime($date_d);
       $last   = date('d-m-Y', strtotime($date_f));
       $res=0;


       do {
        $month = date('d-m-Y', $time);
        $mon   = date('m', $time);
        $total = date('t', $time);
        $total_jr += $total ;
        $annee= date('L', $time);

        $output[] = [
        'month' => $month,
        'total' => $total,
        'tot_jr'=> $total_jr,
        'annee' => $annee,
        'mo'=>$mon,
        'time'=>$time,

        ];

        $time = strtotime('+6 month', $time);


           } while (date('Y-m-d', strtotime($month)) < date('Y-m-d', strtotime($last)));

        if(date('Y-m-d', strtotime($month)) <> date('Y-m-d', strtotime($last)))
        {
            $control_echeance_s = "<ul>Il faut choisir une période Simestrielle, ou séléctionner le type d'échéance : Autres   !!!</ul>";
            $checker = 7;
        }

    }

    if($checker == 7)
    {
        exit("0#$control_echeance_s");
    }
    
    //End check empty element

    $contrat = new Mcontrat($posted_data);
    $contrat->id_contrat = $posted_data['id'];

    $contrat->exige_pj       = FALSE;
    $contrat->exige_pj_photo = FALSE;

    $contrat->get_id_type_echeance('Autres');


    //execute Insert returne false if error
    if ($posted_data['idtype_echeance'] == $contrat->Shw_type('id',1))
    {
       
        $new_contrat1 = new Mcontrat($posted_data);
        $new_contrat1->get_total_devis($posted_data['iddevis']);

        //var_dump($new_contrat1->Shw_type('totalttc',1));

        //var_dump($posted_data['tkn_frm']);

        $new_contrat2 = new Mcontrat($posted_data);
        $new_contrat2->get_total_echeances($posted_data['tkn_frm']);

        //var_dump($new_contrat2->Shw_type('montant_total',1));
        if($new_contrat1->Shw_type('totalttc',1) > $new_contrat2->Shw_type('montant_total',1))
        {
             $montant_echeance = "<ul>Le montant total du devis est supérieur au montant total des échéances, Il faut compléter le montant !!!</ul>";
             $checker = 8;
         }

        if($checker == 8)
        {
        exit("0#$montant_echeance");
        }

        if($new_contrat1->Shw_type('totalttc',1) < $new_contrat2->Shw_type('montant_total',1))
        {
             $montant_echeance1 = "<ul>Le montant total du devis est inférieur au montant total des échéances, Il faut minimiser le montant !!!</ul>";
             $checker = 9;
         }

        if($checker == 9)
        {
        exit("0#$montant_echeance1");
        }


    }


    //execute Insert returne false if error
    if ($contrat->edit_contrat()) {

        echo("1#" . $contrat->log);
    } else {

        echo("0#" . $contrat->log);
    }
} 
else 
{
    view::load_view('editcontrat');
}
?>