<ul class="dropdown-menu dropdown-menu-right">
<?php 


$contrat = new Mcontrat();
$contrat->id_contrat= Mreq::tp('id');
$contrat->get_contrat();

$action = new TableTools();
$action->line_data = $contrat->contrat_info;

$etat_archive = Msetting::get_set('archive_contrats');
$action->action_line_table('contrats', 'contrats',$contrat->contrat_info['creusr'],'deletecontrat',$etat_archive);

?>

</ul>