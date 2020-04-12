<?php


if (!MInit::crypt_tp('id', null, 'D')) {
    // returne message error red to client 
    exit('3#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}

$info_complement = new Mfacture();
//Set ID of Module with POST id
$info_complement->id_complement = Mreq::tp('id');
if ($info_complement->delete_complement()) {
    exit("1#" . $info_complement->log); //Return Green Message
} else {
    exit("0#" . $info_complement->log); //Return Red Message
}
?>