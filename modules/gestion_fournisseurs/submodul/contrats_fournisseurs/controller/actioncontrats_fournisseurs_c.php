<ul class="dropdown-menu dropdown-menu-right">
<?php 
//SYS GLOBAL TECH
// Modul: contrats_fournisseurs => Controller Action


$contrats_frn = new Mcontrats_fournisseurs();
$contrats_frn->id_contrats_frn= Mreq::tp('id');
$contrats_frn->get_contrats_frn();


$action = new TableTools();
$action->line_data = $contrats_frn->contrats_frn_info;

$etat_archive = Msetting::get_set('archive_contrats_fournisseurs');
$action->action_line_table('contrats_fournisseurs', 'contrats_frn',$contrats_frn->contrats_frn_info['creusr'],'deletecontrat_frn', $etat_archive);
?>

</ul>