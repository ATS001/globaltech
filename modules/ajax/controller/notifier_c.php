<?php
function notif($modul){
global $db ;
$service = cryptage($_SESSION['service'],0);
$querynotif="SELECT count($modul.id) as nbr FROM $modul, rules WHERE $modul.etat = rules.etat and rules.notif = 1 AND rules.service = $service and rules.app='$modul'  and rules.active = 1"; 
$nbr = $db->QuerySingleValue0($querynotif);
//return $nbr;
return $nbr;

}
echo notif('depense');

?>






 