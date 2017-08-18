<ul class="dropdown-menu dropdown-menu-right">

<?php 
$gsm_technologie = new Mgsm_technologie();//New instance for gsm_technologie
$gsm_technologie->id_technologie = Mreq::tp('id'); //Set ID of gsm_technologie POST AJAX
$gsm_technologie->get_technologie(); //Get All info of gsm_technologie

$action = new TableTools(); //New instance for table tools
$action->line_data = $gsm_technologie->gsm_technologie_info;//Set data for this line
$action->action_line_table('gsm_technologie', 'gsm_technologie', $gsm_technologie->gsm_technologie_info['creusr'], 'deletegsm_technologie');

?>
</ul>
