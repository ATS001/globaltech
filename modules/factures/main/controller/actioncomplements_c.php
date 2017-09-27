<ul class="dropdown-menu dropdown-menu-right">
<?php 

$complement = new Mfacture();
$complement->id_complement= Mreq::tp('id');
$complement->get_complement();



$action = new TableTools();
$action->line_data = $complement->complement_info;

$action->action_line_table('complements', 'complement_facture',$complement->complement_info['creusr'],'deletecomplement');
?>

</ul>