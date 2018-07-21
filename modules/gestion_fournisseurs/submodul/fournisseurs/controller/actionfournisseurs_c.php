<ul class="dropdown-menu dropdown-menu-right">
<?php 
//SYS GLOBAL TECH
// Modul: fournisseurs => Controller Action 


$fournisseur = new Mfournisseurs();
$fournisseur->id_fournisseur= Mreq::tp('id');
$fournisseur->get_fournisseur();



$action = new TableTools();
$action->line_data = $fournisseur->fournisseur_info;
$action->action_line_table('fournisseurs', 'fournisseurs',$fournisseur->fournisseur_info['creusr'],'deletefournisseur');
?>

</ul>