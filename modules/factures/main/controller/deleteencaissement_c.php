<?php


if (!MInit::crypt_tp('id', null, 'D')) {
    // returne message error red to client 
    exit('3#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}

$info_encaissement = new Mfacture();
//Set ID of Module with POST id
$info_encaissement->id_encaissement = Mreq::tp('id');
if ($info_encaissement->delete_encaissement()) {
    exit("1#" . $info_encaissement->log); //Return Green Message
} else {
    exit("0#" . $info_encaissement->log); //Return Red Message
}
?>