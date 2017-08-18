<ul class="dropdown-menu dropdown-menu-right">

<?php 
$instal = new Minstal();//New instance for installateur
$instal->id_instal = Mreq::tp('id'); //Set ID of installateur POST AJAX
$instal->get_instal(); //Get All ino of installateur

$action = new TableTools(); //New instance for table tools
$action->line_data = $instal->instal_info;//Set data for this line

//Set Task maine, Table maine, Creator user of line, Task to execute (delete) 
$action->action_line_table('installateurs', 'installateurs', $instal->instal_info['creusr'], 'deleteinstallateur');
?>

