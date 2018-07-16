<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: ticket_frs
//Created : 15-07-2018
//View
?>
<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
				
		<?php TableTools::btn_add('ticket_frs','Liste des ticket_frs', Null, $exec = NULL, 'reply'); ?>
					
	</div>
</div>
<div class="page-header">
	<h1>
		Ajouter un ticket
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
 
$form = new Mform('addticket_frs', 'addticket_frs', '', 'ticket_frs', '0', null);

//For more Example see form class

//Fournisseur ==> 
                $frn_array[] = array('required', 'true', 'Choisir un fournisseur');
                $form->select_table('Fournisseur', 'id_fournisseur', 6, 'fournisseurs', 'id', 'denomination', 'denomination', $indx = '------', $selected = NULL, $multi = NULL, $where = 'etat=1', $frn_array, NULL);

//Projet ==> 

                $form->input("Site", "projet", "text", "9", null, NULL, null, $readonly = null);

//Serial number==> 

                 $form->input("Serial number", "serial_number", "text", "9", null, NULL, null, $readonly = null);


//Date prévisionnelle ==> 
                $date_prev[] = array('required', 'true', 'Insérer une date prévisionnelle');
                $form->input_date('Date prévisionnelle', 'date_previs', 2, date('d-m-Y'), $date_prev);

//Type Produit

                $hard_code_type_produit = '<label style="margin-left:15px;margin-right : 20px;">Catégorie: </label><select id="categorie_produit" name="categorie_produit" class="chosen-select col-xs-12 col-sm-6" chosen-class="' . ((6 * 100) / 12) . '" ><option >----</option></select>';
                $type_produit_array[] = array('required', 'true', 'Choisir un Type Produit');
                $form->select_table('Type Produit', 'type_produit', 3, 'ref_types_produits', 'id', 'type_produit', 'type_produit', $indx = '------', $selected = NULL, $multi = NULL, $where = 'etat = 1', $type_produit_array, $hard_code_type_produit);

                $opt_produit = array('' => '------');
                $form->select('Produit / Service', 'id_produit', 8, $opt_produit, $indx = NULL, $selected = NULL, $multi = NULL, null);

//Message
                $array_message[] = array("required", "true", "Insérer un message ");
                $form->input_editor('Description', 'message', 8, NULL, $array_message, $input_height = 200);

              
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
    
//JS bloc   

});
</script>	

		