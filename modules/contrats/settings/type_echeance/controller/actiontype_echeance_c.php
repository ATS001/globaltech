<ul class="dropdown-menu dropdown-menu-right"> 
<?php 
//SYS GLOBAL TECH
// Modul: type_echeance => Controller Action


$type_echeance = new Mtype_echeance();
$type_echeance->id_type_echeance= Mreq::tp('id');
$type_echeance->get_type_echeance();



$action = new TableTools();
$action->line_data = $type_echeance->type_echeance_info;
$action->action_line_table('type_echeance', 'ref_type_echeance',$type_echeance->type_echeance_info['creusr'],'deletetype_echeance');
?>

</ul>