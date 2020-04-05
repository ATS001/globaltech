<ul class="dropdown-menu dropdown-menu-right">
<?php 


$devis = new Mdevis();
$devis->id_devis = Mreq::tp('id');
$devis->get_devis();



$action = new TableTools();
$action->line_data = $devis->devis_info;

if($devis->devis_info["type_devis"] != "ABN" && $devis->devis_info["etat"] == 4){
  $clause = "AND task_action.descrip <> 'Upgrade devis' and task_action.etat_line = 4";
  $action->clause_facultative = $clause;
}

$etat_archive = Msetting::get_set('archive_devis');
$action->action_line_table('devis', 'devis', $devis->devis_info['creusr'], 'deletedevis', $etat_archive);

?>
</ul>