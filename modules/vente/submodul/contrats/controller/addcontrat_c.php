<?php

defined('_MEXEC') or die;
if (MInit::form_verif('addcontrat', false)) {

    $posted_data = array(
        'tkn_frm' => Mreq::tp('tkn_frm'),
        'iddevis' => Mreq::tp('iddevis'),
        'date_effet' => Mreq::tp('date_effet'),
        'date_fin' => Mreq::tp('date_fin'),
        'idtype_echeance' => Mreq::tp('idtype_echeance'),
        'commentaire' => Mreq::tp('commentaire'),
        'periode_fact' => Mreq::tp('periode_fact'),
        'date_notif' => Mreq::tp('date_notif'),
        'pj_id' => Mreq::tp('pj-id'),
        'pj_photo_id' => Mreq::tp('pj_photo-id')
    );



    //Check if array have empty element return list
    //for acceptable empty field do not put here


    $checker = null;
    $empty_list = "Les champs suivants sont obligatoires:\n<ul>";


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

    if ($posted_data['idtype_echeance'] == NULL) {

        $empty_list .= "<li>Type échéance</li>";
        $checker = 1;
    }

    /*    if ($posted_data['commentaire'] == NULL) {

            $empty_list .= "<li>Commentaire</li>";
            $checker = 1;
        }*/
    if ($posted_data['periode_fact'] == NULL) {

        $empty_list .= "<li>Période de facturation</li>";
        $checker = 1;
    }
    if ($posted_data['date_notif'] == NULL) {

        $empty_list .= "<li>Date de notification</li>";
        $checker = 1;
    }


    $empty_list .= "</ul>";
    if ($checker == 1) {
        exit("0#$empty_list");
    }

    if (date('Y-m-d', strtotime($posted_data['date_fin'])) <= date('Y-m-d', strtotime($posted_data['date_effet']))) {

        $control_date = "<ul>La date de fin doit être supérieur de la date d'effet !!!</ul>";
        $checker = 2;
    }

    if ($checker == 2) {
        exit("0#$control_date");
    }

    if (date('Y-m-d', strtotime($posted_data['date_notif'])) >= date('Y-m-d', strtotime($posted_data['date_fin'])) or date('Y-m-d', strtotime($posted_data['date_notif'])) <= date('Y-m-d', strtotime($posted_data['date_effet']))) {

        $control_notif = "<ul>La date de notification doit être supérieur de la date d'effet et  inférieur de la date de fin !!!</ul>";
        $checker = 3;
    }

    if ($checker == 3) {
        exit("0#$control_notif");
    }


    $date1 = date_create(date('Y-m-d', strtotime($posted_data['date_effet'])));
    $date2 = date_create(date('Y-m-d', strtotime($posted_data['date_fin'])));
    $diff = date_diff($date1, $date2);

    /* if (date('Y-m-d', strtotime($posted_data['date_effet'])) < date('Y-m-d')) {

            $control_date_effet .= "<li>Date d'effet doit être supérieur ou égal à la date d'aujourd'hui</li>";
            $checker = 4;
        }

        if($checker == 4)
        {
            exit("0#$control_date_effet");
        }*/

    $contratA = new Mcontrat($posted_data);
    $contratA->get_id_type_echeance('Annuelle');

    $contratM = new Mcontrat($posted_data);
    $contratM->get_id_type_echeance('Mensuelle');

    $contratT = new Mcontrat($posted_data);
    $contratT->get_id_type_echeance('Trimestrielle');

    $contratS = new Mcontrat($posted_data);
    $contratS->get_id_type_echeance('Simestrielle');

    $contratB = new Mcontrat($posted_data);
    $contratB->get_id_type_echeance('Bimensuelle');



    /*var_dump( $diff->format('%y'));
    var_dump( $diff->format('%m'));
    var_dump( $diff->format('%a')); */


    /*var_dump($contratA->Shw_type('id',1));
    var_dump($contratM->Shw_type('id',1));
    var_dump($contratT->Shw_type('id',1));
    var_dump($contratS->Shw_type('id',1));
    var_dump($posted_data['idtype_echeance']);
    exit();*/


    if ( /*($diff->format('%a') % 365) <>0 and*/
        $posted_data['idtype_echeance'] == $contratA->Shw_type('id', 1)) {
        
        $tab=array();
        
        $date_d = $posted_data['date_effet'];
        $date_f = $posted_data['date_fin'];
        $output = [];
        $output_an = [];
        $total_jr = 0;
        $day = date('d',strtotime($date_d));
        
        if ($day == '01') {
           // var_dump('1');

        $last = date('d-m-Y', strtotime($date_f));
        $res = 0;
        $time = date($date_d);

        $datt=strtotime($date_d);
     
        do {

          
            $time = date('Y-m-d', strtotime($time . "+1 year"));
            $time2 = date('Y-m-t', strtotime($time ."-1 month"));
            
                        
            $month = date('d-m-Y', strtotime($time2));
            
            $test = date('m', strtotime($time2));
            $mon = date('m', strtotime($time2));
            $total = date('t', strtotime($time2));
            $total_jr += $total;
            $annee = date('L', strtotime($time2));
            
            $waw=date('d-m-Y',$datt);
           
            $output[] = [
                'month' => $month,
                'total' => $total,
                'tot_jr' => $total_jr,
                'annee' => $annee,
                'mo' => $mon,
                'time' => $time2,

            ];
            $datt = strtotime('+1 year', $datt);
                         
           $tab_echeance['debut']= date('d-m-Y', strtotime($waw));
           $tab_echeance['fin']=date('d-m-Y', strtotime($month));
           
           if($posted_data['periode_fact'] == 'D')
           {
            $tab_echeance['periode_fact']=date('d-m-Y', strtotime($waw));
        }
           else{
            if($posted_data['periode_fact'] == 'F')
            $tab_echeance['periode_fact']=date('d-m-Y', strtotime($month));           
           }

           array_push($tab,$tab_echeance);  
           
            //var_dump('waw :' . date('d-m-Y', strtotime($waw)));
            //var_dump('time :' . date('d-m-Y', strtotime($month)));


        } while (date('Y-m-d', strtotime($month)) < date('Y-m-d', strtotime($last)));


        }else{

        $t = new DateTime($date_d);
        $time1 = date_sub($t, date_interval_create_from_date_string('1 days'));
        $time2 = date_format($time1, 'd-m-Y');

        $time = strtotime($time2);
        $time = strtotime('+1 year', $time);
        $last = date('d-m-Y', strtotime($date_f));
        $res = 0;

        $dattt= new DateTime($date_d);
        $datttt=date_format($dattt, 'd-m-Y');
       
        $datt=strtotime($date_d);

$tab=array();
        do {
            $month = date('d-m-Y', $time);
            $mon = date('m', $time);
            $total = date('t', $time);
            $total_jr += $total;
            $annee = date('L', $time);

            $waw=date('d-m-Y',$datt);
            
            //var_dump($datt);
            //var_dump($time);

            $output[] = [
                'month' => $month,
                'total' => $total,
                'tot_jr' => $total_jr,
                'annee' => $annee,
                'mo' => $mon,
                'time' => $time,

            ];

            $time = strtotime('+1 year', $time);
            $datt = strtotime('+1 year', $datt);

        
           $tab_echeance['debut']= date('d-m-Y', strtotime($waw));
           $tab_echeance['fin']=date('d-m-Y', strtotime($month));

        
            
          if($posted_data['periode_fact'] == 'D')
           {
            $tab_echeance['periode_fact']=date('d-m-Y', strtotime($waw));
        }
           else{
            if($posted_data['periode_fact'] == 'F')
            $tab_echeance['periode_fact']=date('d-m-Y', strtotime($month));           
           }

           array_push($tab,$tab_echeance);
            

        } while (date('Y-m-d', strtotime($month)) < date('Y-m-d', strtotime($last)));
        }
        if (date('Y-m-d', strtotime($month)) <> date('Y-m-d', strtotime($last))) {
            $control_echeance_a = "<ul>Il faut choisir une période Annuelle, ou séléctionner le type d'échéance : Autres  !!!</ul>";
            $checker = 4;
        }
    }
    if ($checker == 4) {
        exit("0#$control_echeance_a");
    }


//return 12 * $nbyear + $nbmonth;
    if ($posted_data['idtype_echeance'] == $contratM->Shw_type('id', 1)) {
$tab=array();
$date_d = $posted_data['date_effet'];
        $date_f = $posted_data['date_fin'];
        $output = [];
        $output_an = [];
        $total_jr = 0;
        $day = date('d',strtotime($date_d));
        

        if ($day == '01') {
           // var_dump('1');

        $last = date('d-m-Y', strtotime($date_f));
        $res = 0;
        $time = date($date_d);

        $datt=strtotime($date_d);
     
        do {

            $time2 = date('Y-m-t', strtotime($time));
            $time = date('Y-m-d', strtotime($time . "+ 1 Month"));

            $month = date('d-m-Y', strtotime($time2));
            $test = date('m', strtotime($time2));
            $mon = date('m', strtotime($time2));
            $total = date('t', strtotime($time2));
            $total_jr += $total;
            $annee = date('L', strtotime($time2));
            
            $waw=date('d-m-Y',$datt);

            $output[] = [
                'month' => $month,
                'total' => $total,
                'tot_jr' => $total_jr,
                'annee' => $annee,
                'mo' => $mon,
                'time' => $time2,

            ];
            $datt = strtotime('+1 month', $datt);
                        
           $tab_echeance['debut']= date('d-m-Y', strtotime($waw));
           $tab_echeance['fin']=date('d-m-Y', strtotime($month));
           
           if($posted_data['periode_fact'] == 'D')
           {
            $tab_echeance['periode_fact']=date('d-m-Y', strtotime($waw));
        }
           else{
            if($posted_data['periode_fact'] == 'F')
            $tab_echeance['periode_fact']=date('d-m-Y', strtotime($month));           
           }

           array_push($tab,$tab_echeance);  
           
            //var_dump('waw :' . date('d-m-Y', strtotime($waw)));
            //var_dump('time :' . date('d-m-Y', strtotime($month)));


        } while (date('Y-m-d', strtotime($month)) < date('Y-m-d', strtotime($last)));


        }else{

            //var_dump('2');
        $t = new DateTime($date_d);
        $time1 = date_sub($t, date_interval_create_from_date_string('1 days'));
        $time2 = date_format($time1, 'd-m-Y');

        $time = strtotime($time2);
        $time = strtotime('+1 month', $time);
        $last = date('d-m-Y', strtotime($date_f));
        $res = 0;

         $datt=strtotime($date_d);
        $tab=array();
            do {
            $month = date('d-m-Y', $time);
            $mon = date('m', $time);
            $total = date('t', $time);
            $total_jr += $total;
            $annee = date('L', $time);

            $output[] = [
                'month' => $month,
                'total' => $total,
                'tot_jr' => $total_jr,
                'annee' => $annee,
                'mo' => $mon,
                'time' => $time,

            ];

            $time = strtotime('+1 month', $time);
            $time1 = date_sub($t, date_interval_create_from_date_string('1 days'));
        
            $waw=date('d-m-Y',$datt);        
            $datt = strtotime('+1 month', $datt);
           
           $tab_echeance['debut']= date('d-m-Y', strtotime($waw));
           $tab_echeance['fin']=date('d-m-Y', strtotime($month));

  if($posted_data['periode_fact'] == 'D')
           {
            $tab_echeance['periode_fact']=date('d-m-Y', strtotime($waw));
        }
           else{
            if($posted_data['periode_fact'] == 'F')
            $tab_echeance['periode_fact']=date('d-m-Y', strtotime($month));           
           }


           array_push($tab,$tab_echeance);         

        } while (date('Y-m-d', strtotime($month)) < date('Y-m-d', strtotime($last)));

        }
        
        

        if (date('Y-m-d', strtotime($month)) <> date('Y-m-d', strtotime($last))) {
            $control_echeance_m = "<ul>Il faut choisir une période Mensuelle, ou séléctionner le type d'échéance : Autres   !!!</ul>";
            $checker = 5;
        }


    }
    if ($checker == 5) {
        exit("0#$control_echeance_m");
    }
     //exit();
// FIN AYOUB

    if ($posted_data['idtype_echeance'] == $contratT->Shw_type('id', 1)) {
        $tab=array();
        $date_d = $posted_data['date_effet'];
        $date_f = $posted_data['date_fin'];
        $output = [];
        $output_an = [];
        $total_jr = 0;
        $day = date('d',strtotime($date_d));
        

        if ($day == '01') {
           // var_dump('1');

        $last = date('d-m-Y', strtotime($date_f));
        $res = 0;
        $time = date($date_d);

        $datt=strtotime($date_d);
     
        do {

            $time = date('Y-m-d', strtotime($time . "+ 3 Month"));
            $time2 = date('Y-m-t', strtotime($time . "- 1 Month"));

            $month = date('d-m-Y', strtotime($time2));
            $test = date('m', strtotime($time2));
            $mon = date('m', strtotime($time2));
            $total = date('t', strtotime($time2));
            $total_jr += $total;
            $annee = date('L', strtotime($time2));

            $waw=date('d-m-Y',$datt);
            
            $output[] = [
                'month' => $month,
                'total' => $total,
                'tot_jr' => $total_jr,
                'annee' => $annee,
                'mo' => $mon,
                'time' => $time2,

            ];
$datt = strtotime('+3 month', $datt);
                        
           $tab_echeance['debut']= date('d-m-Y', strtotime($waw));
           $tab_echeance['fin']=date('d-m-Y', strtotime($month));
           
           if($posted_data['periode_fact'] == 'D')
           {
            $tab_echeance['periode_fact']=date('d-m-Y', strtotime($waw));
        }
           else{
            if($posted_data['periode_fact'] == 'F')
            $tab_echeance['periode_fact']=date('d-m-Y', strtotime($month));           
           }

           array_push($tab,$tab_echeance);  
           
            //var_dump('waw :' . date('d-m-Y', strtotime($waw)));
            //var_dump('time :' . date('d-m-Y', strtotime($month)));


            //var_dump('time :' . date('d-m-Y', strtotime($time)));


        } while (date('Y-m-d', strtotime($month)) < date('Y-m-d', strtotime($last)));


        }else{

            //var_dump('2');
        $t = new DateTime($date_d);
        $time1 = date_sub($t, date_interval_create_from_date_string('1 days'));
        $time2 = date_format($time1, 'd-m-Y');

        $time = strtotime($time2);
        $time = strtotime('+3 month', $time);
        $last = date('d-m-Y', strtotime($date_f));
        $res = 0;


 $datt=strtotime($date_d);

 $tab=array();

            do {
            $month = date('d-m-Y', $time);
            $mon = date('m', $time);
            $total = date('t', $time);
            $total_jr += $total;
            $annee = date('L', $time);

            $output[] = [
                'month' => $month,
                'total' => $total,
                'tot_jr' => $total_jr,
                'annee' => $annee,
                'mo' => $mon,
                'time' => $time,

            ];

            $time = strtotime('+3 month', $time);
            $time1 = date_sub($t, date_interval_create_from_date_string('1 days'));
        
$waw=date('d-m-Y',$datt);
        
         $datt = strtotime('+3 month', $datt);

        
           $tab_echeance['debut']= date('d-m-Y', strtotime($waw));
           $tab_echeance['fin']=date('d-m-Y', strtotime($month));

           
  if($posted_data['periode_fact'] == 'D')
           {
            $tab_echeance['periode_fact']=date('d-m-Y', strtotime($waw));
        }
           else{
            if($posted_data['periode_fact'] == 'F')
            $tab_echeance['periode_fact']=date('d-m-Y', strtotime($month));           
           }


           array_push($tab,$tab_echeance);         

        } while (date('Y-m-d', strtotime($month)) < date('Y-m-d', strtotime($last)));

        }

        if (date('Y-m-d', strtotime($month)) <> date('Y-m-d', strtotime($last))) {
            $control_echeance_t = "<ul>Il faut choisir une période Trimestrielle, ou séléctionner le type d'échéance : Autres   !!!</ul>";
            $checker = 6;
        }

        //var_dump($output);

    }
    if ($checker == 6) {
        exit("0#$control_echeance_t");
    }

    if ($posted_data['idtype_echeance'] == $contratS->Shw_type('id', 1)) {
        $tab=array();
        $date_d = $posted_data['date_effet'];
        $date_f = $posted_data['date_fin'];
        $output = [];
        $output_an = [];
        $total_jr = 0;
        $day = date('d',strtotime($date_d));
    // var_dump('dkhel')  ; 

        if ($day == '01') {
            //var_dump('1');

        $last = date('d-m-Y', strtotime($date_f));
        $res = 0;
        $time = date($date_d);

    
        $datt=strtotime($date_d);
     
     
        do {

            $time = date('Y-m-d', strtotime($time . "+ 6 Month"));
            $time2 = date('Y-m-t', strtotime($time . "- 1 Month"));

            $month = date('d-m-Y', strtotime($time2));
            $test = date('m', strtotime($time2));
            $mon = date('m', strtotime($time2));
            $total = date('t', strtotime($time2));
            $total_jr += $total;
            $annee = date('L', strtotime($time2));

            $waw=date('d-m-Y',$datt);
            
            $output[] = [
                'month' => $month,
                'total' => $total,
                'tot_jr' => $total_jr,
                'annee' => $annee,
                'mo' => $mon,
                'time' => $time2,

            ];

            $datt = strtotime('+6 month', $datt);
                        
           $tab_echeance['debut']= date('d-m-Y', strtotime($waw));
           $tab_echeance['fin']=date('d-m-Y', strtotime($month));
           
           if($posted_data['periode_fact'] == 'D')
           {
            $tab_echeance['periode_fact']=date('d-m-Y', strtotime($waw));
        }
           else{
            if($posted_data['periode_fact'] == 'F')
            $tab_echeance['periode_fact']=date('d-m-Y', strtotime($month));           
           }

           array_push($tab,$tab_echeance); 

            //var_dump('time :' . date('d-m-Y', strtotime($time)));


        } while (date('Y-m-d', strtotime($month)) < date('Y-m-d', strtotime($last)));


        }else{

           //var_dump('2');
        $t = new DateTime($date_d);
        $time1 = date_sub($t, date_interval_create_from_date_string('1 days'));
        $time2 = date_format($time1, 'd-m-Y');

        $time = strtotime($time2);
        $time = strtotime('+6 month', $time);

        $last = date('d-m-Y', strtotime($date_f));
        $res = 0;
        $datt=strtotime($date_d);

        $tab=array();

            do {
            $month = date('d-m-Y', $time);
            $mon = date('m', $time);
            $total = date('t', $time);
            $total_jr += $total;
            $annee = date('L', $time);

            $output[] = [
                'month' => $month,
                'total' => $total,
                'tot_jr' => $total_jr,
                'annee' => $annee,
                'mo' => $mon,
                'time' => $time,

            ];

            $time = strtotime('+6 month', $time);
            $time1 = date_sub($t, date_interval_create_from_date_string('1 days'));
            
            $waw=date('d-m-Y',$datt);
            $datt = strtotime('+1 year', $datt);

        
           $tab_echeance['debut']= date('d-m-Y', strtotime($waw));
           $tab_echeance['fin']=date('d-m-Y', strtotime($month));

           if($posted_data['periode_fact'] == 'D')
           {
            $tab_echeance['periode_fact']=date('d-m-Y', strtotime($waw));
        }
           else{
            if($posted_data['periode_fact'] == 'F')
            $tab_echeance['periode_fact']=date('d-m-Y', strtotime($month));           
           }


           array_push($tab,$tab_echeance); 


        } while (date('Y-m-d', strtotime($month)) < date('Y-m-d', strtotime($last)));

        }

        if (date('Y-m-d', strtotime($month)) <> date('Y-m-d', strtotime($last))) {
            $control_echeance_s = "<ul>Il faut choisir une période Semestrielle, ou séléctionner le type d'échéance : Autres   !!!</ul>";
            $checker = 7;
        }

    }
    if ($checker == 7) {
        exit("0#$control_echeance_s");
    }
//Bimensuel
       if ($posted_data['idtype_echeance'] == $contratB->Shw_type('id', 1)) {
$tab=array();
$date_d = $posted_data['date_effet'];
        $date_f = $posted_data['date_fin'];
        $output = [];
        $output_an = [];
        $total_jr = 0;
        $day = date('d',strtotime($date_d));
        

        if ($day == '01') {
           // var_dump('1');

        $last = date('d-m-Y', strtotime($date_f));
        $res = 0;
        $time = date($date_d);

        $datt=strtotime($date_d);
     
        do {

            $time = date('Y-m-d', strtotime($time . "+ 2 Month"));
            $time2 = date('Y-m-t', strtotime($time . "- 1 Month"));

            $month = date('d-m-Y', strtotime($time2));
            $test = date('m', strtotime($time2));
            $mon = date('m', strtotime($time2));
            $total = date('t', strtotime($time2));
            $total_jr += $total;
            $annee = date('L', strtotime($time2));
            
            $waw=date('d-m-Y',$datt);

            $output[] = [
                'month' => $month,
                'total' => $total,
                'tot_jr' => $total_jr,
                'annee' => $annee,
                'mo' => $mon,
                'time' => $time2,

            ];
            $datt = strtotime('+2 month', $datt);
                        
           $tab_echeance['debut']= date('d-m-Y', strtotime($waw));
           $tab_echeance['fin']=date('d-m-Y', strtotime($month));
           
           if($posted_data['periode_fact'] == 'D')
           {
            $tab_echeance['periode_fact']=date('d-m-Y', strtotime($waw));
        }
           else{
            if($posted_data['periode_fact'] == 'F')
            $tab_echeance['periode_fact']=date('d-m-Y', strtotime($month));           
           }

           array_push($tab,$tab_echeance);  
           
            //var_dump('waw :' . date('d-m-Y', strtotime($waw)));
            //var_dump('time :' . date('d-m-Y', strtotime($month)));


        } while (date('Y-m-d', strtotime($month)) < date('Y-m-d', strtotime($last)));


        }else{

            //var_dump('2');
        $t = new DateTime($date_d);
        $time1 = date_sub($t, date_interval_create_from_date_string('1 days'));
        $time2 = date_format($time1, 'd-m-Y');

        $time = strtotime($time2);
        $time = strtotime('+2 month', $time);
        $last = date('d-m-Y', strtotime($date_f));
        $res = 0;

         $datt=strtotime($date_d);
        $tab=array();
            do {
            $month = date('d-m-Y', $time);
            $mon = date('m', $time);
            $total = date('t', $time);
            $total_jr += $total;
            $annee = date('L', $time);

            $output[] = [
                'month' => $month,
                'total' => $total,
                'tot_jr' => $total_jr,
                'annee' => $annee,
                'mo' => $mon,
                'time' => $time,

            ];

            $time = strtotime('+2 month', $time);
            $time1 = date_sub($t, date_interval_create_from_date_string('1 days'));
        
            $waw=date('d-m-Y',$datt);        
            $datt = strtotime('+2 month', $datt);
           
           $tab_echeance['debut']= date('d-m-Y', strtotime($waw));
           $tab_echeance['fin']=date('d-m-Y', strtotime($month));

  if($posted_data['periode_fact'] == 'D')
           {
            $tab_echeance['periode_fact']=date('d-m-Y', strtotime($waw));
        }
           else{
            if($posted_data['periode_fact'] == 'F')
            $tab_echeance['periode_fact']=date('d-m-Y', strtotime($month));           
           }


           array_push($tab,$tab_echeance);         

        } while (date('Y-m-d', strtotime($month)) < date('Y-m-d', strtotime($last)));

        }
        
        

        if (date('Y-m-d', strtotime($month)) <> date('Y-m-d', strtotime($last))) {
            $control_echeance_b = "<ul>Il faut choisir une période Bimensuelle, ou séléctionner le type d'échéance : Autres   !!!</ul>";
            $checker = 20;
        }


    }
    if ($checker == 20) {
        exit("0#$control_echeance_b");
    }
//Fin Bimensuel

    //End check empty element

    $new_contrat = new Mcontrat($posted_data);

    $new_contrat->exige_pj = FALSE;
    $new_contrat->exige_pj_photo = FALSE;

    $new_contrat->get_id_type_echeance('Autres');


    //execute Insert returne false if error
    if ($posted_data['idtype_echeance'] == $new_contrat->Shw_type('id', 1)) {

        $new_contrat1 = new Mcontrat($posted_data);
        $new_contrat1->get_total_devis($posted_data['iddevis']);

       // var_dump($new_contrat1->Shw_type('totalttc',1));

        //var_dump($new_contrat2->Shw_type('montant_total', 1));

        $new_contrat2 = new Mcontrat($posted_data);
        $new_contrat2->get_total_echeances($posted_data['tkn_frm']);
        //var_dump($new_contrat2->Shw_type('montant_total',1));

        if ($new_contrat1->Shw_type('totalttc', 1) > $new_contrat2->Shw_type('montant_total', 1)) {
            $montant_echeance = "<ul>Le montant total du devis est supérieur au montant total des échéances, Il faut compléter le montant !!!</ul>";
            $checker = 8;
        }

        if ($checker == 8) {
            exit("0#$montant_echeance");
        }

        if ($new_contrat1->Shw_type('totalttc', 1) < $new_contrat2->Shw_type('montant_total', 1)) {
            $montant_echeance1 = "<ul>Le montant total du devis est inférieur au montant total des échéances, Il faut minimiser le montant !!!</ul>";
            $checker = 9;
        }

        if ($checker == 9) {
            exit("0#$montant_echeance1");
        }


    }

    if ($new_contrat->save_new_contrat()) {
        if ($posted_data['idtype_echeance'] != $new_contrat->Shw_type('id', 1)){

            //var_dump($tab);
             foreach ($tab as  $value) {
              $new_contrat->save_echeance($value['debut'],$value['fin'],$value['periode_fact']);
             }
             echo("1#" . $new_contrat->log);
        } else {


            $new_contrat->update_echeances_autres($new_contrat->id_contrat,$posted_data['date_fin'],$posted_data['date_effet']);
                

        }
            echo("1#" . $new_contrat->log);
    }else {

            echo("0#" . $new_contrat->log);
    }

} else {
    $ctr = new Mcontrat();
    $ctr -> delete_null_echeances();
    view::load_view('addcontrat');
}