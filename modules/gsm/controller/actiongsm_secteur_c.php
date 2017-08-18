<ul class="dropdown-menu dropdown-menu-right">
<?php 


$gsm_secteur = new Mgsm_secteur();
$gsm_secteur->id_gsm_secteur = Mreq::tp('id');
$gsm_secteur->get_gsm_secteur();


$action = new TableTools();
$action->line_data = $gsm_secteur->gsm_secteur_info;
$action->action_line_table('gsm_secteur', 'gsm_secteur', $gsm_secteur->gsm_secteur_info['creusr'], 'deletegsm_secteur');

?>

</ul>
