<ul class="dropdown-menu dropdown-menu-right">
<?php 

$unite_vente = new Munite_vente();
$unite_vente->id_unite_vente= Mreq::tp('id');
$unite_vente->get_unite_vente();


$action = new TableTools();
$action->line_data = $unite_vente->unite_vente_info;
$action->action_line_table('unites_vente', 'ref_unites_vente',$unite_vente->unite_vente_info['creusr'],'deleteunite_vente');


?>

</ul>
