<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: proforma
//Created : 17-11-2019
//Controller EDIT Form
$proforma = new  Mproforma();
$proforma->id_proforma = Mreq::tp('id');
$proforma->id_proforma_pro = Mreq::tp('pro');

if(!MInit::crypt_tp('id', null, 'D') OR !$proforma->get_proforma())
{  
    // returne message error red to client 
    exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}

//Set ID of row to update

        
        //execute Update returne false if error
if($proforma->transformer_proforma_proforma_to_devis()){

    exit("1#".$proforma->log);
}else{

    exit("0#".$proforma->log);
}

?>