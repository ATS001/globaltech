
echo '<ul class="dropdown-menu dropdown-menu-right">';
$%task% = new M%task%();
$%task%->id_%task% = Mreq::tp('id');
$%task%->get_%task%();



$action = new TableTools();
$action->line_data = $%task%->%task%_info;
$action->action_line_table('%task%', '%task%', $%task%->%task%_info['creusr'], 'delete%task%');


echo '</ul>';