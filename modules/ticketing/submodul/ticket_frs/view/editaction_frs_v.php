<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: tickets
//Created : 21-04-2018
//View
//Get all tickets info 
 $info_tickets = new Mticket_frs();
//Set ID of Module with POST id
 $info_tickets->id_action_ticket = Mreq::tp('id');
//Check if Post ID <==> Post idc or get_modul return false. 
 if(!MInit::crypt_tp('id', null, 'D') or !$info_tickets->get_ticket_action())
 { 	
 	// returne message error red to client 
 	exit('3#'.$info_tickets->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
 }
$id_ticket = $info_tickets->ticket_action_info["id_ticket"];
?>

<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
		 <?php
        TableTools::btn_add('detailsticket_frs', 'Retour', MInit::crypt_tp('id', $id_ticket), $exec = NULL, 'reply');
        ?>
					
	</div>
</div>
<div class="page-header">
	<h1>
		Modifier l'action de ticket: <?php $info_tickets->sa('id_ticket_frs')?>
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
		</small>
	</h1>
</div><!-- /.page-header -->
<!-- Bloc form Add Devis-->
<div class="row">
	<div class="col-xs-12">
		<div class="clearfix">
			
		</div>
		<div class="table-header">
			Formulaire: "<?php echo ACTIV_APP; ?>"
		</div>
		<div class="widget-content">
			<div class="widget-box">
				
<?php
$id_ticket = $info_tickets->ga('id_ticket');
$form = new Mform('editaction_frs', 'editaction_frs', '1', 'detailsticket_frs&'.MInit::crypt_tp('id', $id_ticket), '0', null);
$form->input_hidden('id', $info_tickets->ga('id'));
$form->input_hidden('idc', Mreq::tp('idc'));
$form->input_hidden('idh', Mreq::tp('idh'));
$form->input_hidden('id_ticket_frs', $info_tickets->ga('id_ticket_frs'));
//Date action ==> 
                $date_act[] = array('required', 'true', 'Insérer une date ');
                $form->input_date('Date', 'date_action', 2, $info_tickets->ga('date_action'), $date_act);


//image
                $form->input('Photo', 'photo', 'file', 9, "Photo", null);
                $form->file_js('photo', 1000000, 'image',$info_tickets->ga("photo"), 1);

                //PJ
                $form->input('Pièce jointe', 'pj', 'file', 8, "Pièce jointe", null);
                $form->file_js('pj', 1000000, 'pdf',$info_tickets->ga("pj"), 1);


//Message
                $array_message[] = array("required", "true", "Insérer un message ");
                $form->input_editor('Description', 'message', 8, $info_tickets->ga('message'), $array_message, $input_height = 200);




                $form->button('Enregistrer');
//Form render
                $form->render();
              
?>
			</div>
		</div>
	</div>
</div>
<!-- End Add devis bloc -->
		
<script type="text/javascript">
$(document).ready(function() {
    
//JS Bloc    

});
</script>	

		