<ul class="dropdown-menu dropdown-menu-right">
<?php 

$encaissement = new Mfacture();
$encaissement->id_encaissement= Mreq::tp('id');
$encaissement->get_encaissement();



$action = new TableTools();
$action->line_data = $encaissement->encaissement_info;

$action->action_line_table('encaissements', 'encaissements',$encaissement->encaissement_info['creusr'],'deleteencaissement');
?>

</ul>