<ul class="dropdown-menu dropdown-menu-right">
<?php 

$anonyme = new Manonyme();
$anonyme->id_anonyme = Mreq::tp('id');
$anonyme->get_anonyme();


$action = new TableTools();
$action->line_data = $anonyme->anonyme_info;
$action->action_line_table('anonymes', 'prm_anonyme', $anonyme->vsat_info['creusr'], 'deleteanonyme');


?>
<!-- <li>
	    <a href="#" class="this_url" redi="user" data="id=%id%" rel="edituser" item="%id%" >
			<i class="ace-icon fa fa-pencil bigger-100"></i> Action suplmentaire
		</a>
</li> -->
</ul>
