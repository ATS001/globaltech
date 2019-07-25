<?php 

if(!defined('_MEXEC'))die();

$factures = new Mfacture();
$factures->id_encaissement = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D')or !$factures->get_encaissement())
{  
   // returne message error red to factures 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}
 
       /* $fact = new Mfacture();
        $fact->id_facture = $factures->encaissement_info['idfacture'];
        $fact->encaissement_info=$factures->encaissement_info;
        $fact->get_commerciale_devis();

        $fact_ex = new Mfacture();
        $fact_ex->id_facture = $factures->encaissement_info['idfacture'];
        $fact_ex->encaissement_info=$factures->encaissement_info;
        $fact_ex->get_commerciale_ex_devis();*/
        

    if ($factures->valid_encaissement()) {
            /*if($fact->compte_commercial_info['commission']!=0){
               
                if($fact->credit_compte_commerciale()){

                    
                    if($fact_ex->compte_commercial_ex_info['commission_ex']!=0){
               
                        if($fact_ex->credit_compte_commerciale_ex()){

                            exit("1#" . $fact_ex->log); //Green message

                        }else {

                            exit("0#" . $fact_ex->log); //Red message

                        } 
                    }

                exit("1#" . $fact->log); //Green message

                }else {

                exit("0#" . $fact->log); //Red message

                } 
            } */

            exit("1#" . $factures->log); //Green message
        }else {

            exit("0#" . $factures->log); //Red message
        }