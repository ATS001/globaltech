<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: test_bololo
//Created : 29-10-2017
//Controller EXEC Form
$test_bololo = new Mtest_bololo();
$test_bololo->id_test_bololo = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D')or !$test_bololo->get_test_bololo())
{  
   // returne message error red to test_bololo 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
}


//Etat for validate row
//$etat = $test_bololo->test_bololo_info['etat'];
//$test_bololo->validtest_bololo($etat)
//Execute Validate - delete


if($test_bololo->validtest_bololo())
{
	exit("1#".$test_bololo->log);

}else{
	exit("0#".$test_bololo->log);
}