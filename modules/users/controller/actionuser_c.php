<ul class="dropdown-menu dropdown-menu-right">
<?php 


$user = new Musers();
$user->id_user = Mreq::tp('id');
$user->get_user();



$action = new TableTools();
$action->line_data = $user->user_info;
$action->action_line_table('user', 'users_sys', $user->user_info['creusr'], 'deleteuser');

?>
</ul>
